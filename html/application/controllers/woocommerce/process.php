<?php

// @ini_set('max_execution_time', '3600');
// @ini_set('memory_limit', '10240M');

class Process extends CI_Controller {
	private $translit_name;
	private $donor;

	function __construct(){
		parent::__construct();
 
		$this->load->helper("cms/" . $this->config->item("ms_cms"));

		// получаем настройки мультика
		$settings = $this->settings->getSettings();

		$db_prefix = DB_PREFIX;
		$ci =& get_instance();
		$ci->load->database();
		$ci->db->query("OPTIMIZE {$db_prefix}posts");
		$ci->db->query("OPTIMIZE {$db_prefix}postmeta");

		// если превышен лимит по триалу - не пущаем
		if($this->config->item('ms_trmode')){
			if( (int) $this->settings->getSettingsByKey("trmode_num_product") >= (int) $this->config->item('ms_trmode_num_product_max')){
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
					header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "trial"));exit;
				}
				echo "You use the TRIAL version and have already grabbed several products. Purchase the full version to continue...";
				exit;
			}
		}
		// если демо - сюда вообще не пускаем (DEMO)
		if($this->config->item('ms_DEMO') ){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "demo"));exit;
			}
			echo "Sorry, you are unable to do this in the DEMO mode.";
			exit;
		}
	}

	public function index(){
		date_default_timezone_set('America/Los_Angeles');
		// первая строчка лога
		$log =  '===  <font color="green"> ' . date("H:i  d ") .  date("F") . ' </font>|| ';

		$manualStart = false;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$manualStart = true;
		}
		
		// определяем включен ли MSPRO
		$settings = $this->settings->getSettings();
		if($settings['state']!== "on"){
			if($settings['dev_mode'] == "on"){
				write_file($log . "<font color='red'>MSPRO is SWITCHED OFF </font><br/>" , "public/files/log.txt" );
			}
			if($manualStart){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "switched_off"));
			}
			exit;
		}
		
		/*
		 *  HACK FOR "BUSY" FIELD IN multiscraper_tasks
		 */
		$this->settings->DB_hack();
		//print_r($settings);
				
		// определяем какой task на очереди (category или product) и есть ли он
		$tasks = $this->tasks->getTasksForProcess((int) $settings['num_product']);

		if(!$tasks){
			if($settings['dev_mode'] == "on"){
				write_file($log . "<font color='red'>NO TASKS FOR MSPRO</font><br/>" , "public/files/log.txt" );
			}
			if($manualStart){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "no_tasks"));
			}
			echo 'process -> NO TASKS';
			exit;
		}
		//echo '<pre>'.print_r($tasks , 1).'</pre>';exit;
		$log .= "<font color='green'> Started </font>" . ($manualStart?"<font color='green'> (Manual Startup) </font>":"");
		
		// подтягиваем все парсерные либы
		$this->_getParsers();
		
		// отправляем таски на выполнение
		if(is_array($tasks) && count($tasks) > 0){ 
			if($tasks[0]['owner'] == "category"){
				$log_vstavka = '';
				if($settings['dev_mode'] == "on"){
					$log_vstavka = ' (Task: ' . $tasks[0]['url'] . ')';	
				}
				$log .= '|| <font color="green">Target: Listing parsing</font> ' . $log_vstavka . ' ===================<br />';
				// выполняем парсинг листинга
				$parse_category_res = $this->_processCategory($tasks[0] , $settings);
				if(is_array($parse_category_res) && isset($parse_category_res['products_found']) && isset($parse_category_res['next_page_exists']) ){
					write_file( $log . 'Products Found : <b>'. $parse_category_res['products_found'] . '</b> || Next Page : ' . $parse_category_res['next_page_exists'] . '<br />' , "public/files/log.txt" );
				}
			}else{
				// делаем таски занятыми дабы не запустить их ещё раз пока идёт парсинг
				if(GLOBAL_DEBUG_SEMAFOR < 1){
					$this->tasks->makeProductTasksBusy($tasks);
				}
				$log .= '|| <font color="green">Target: Product parsing</font> ===================<br />';
				$inserted = 0;
				$updated = 0;
				// выполняем парсинг товаров

				foreach($tasks as $task) {
					@$p = $this->_processProduct($task, $settings);
					$this->tasks->makeProductTaskFree($task);

					if ($p) {
						$task['product_id'] > 0 ? $updated++ : $inserted++;
					} else {
						$log .= '<br /><span style="color:orange;"><b>product skipped: '.$task['url'].'</b></span><br />';
					}
				}

				write_file( "{$log} Products Inserted: <b>{$inserted}</b>; Products Updated: <b>{$updated}</b>; <br />" , "public/files/log.txt" );
			}
		}

		exit;
	}

	private function _processCategory($task, $settings){
		$this->tasks->unBusyAllTasks();
		$inv_mode = false;
		if($settings['proxy'] === "on"){
			$inv_mode = true;
		}
		$products_found = 0;
		$next_page_exists = "NO";
		//print_r($task);exit;
		// определяем какой рынок обрабатывается
		$market = $this->_defineMarket($task['url']);
		$parser = $market['name'];
		$this->donor = $parser;
		//echo 'MARKET:<pre>' . print_r($market) . '</pre>';exit;
		if(!$market){
			if($settings['dev_mode'] == "on"){
				write_file("CATEGORY PARSER - CANNOT FIND MARKET FOR URL - " . $task['url'] . "; TASK ID: " . $task['ins_id'] . "<br/>" , "public/files/log.txt" );
				$this->tasks->deleteTask($task['id']);
			}
			exit;
		}
		
		// подтягиваем файл парсинга категории для данного рынка
		$this->load->helper("parsers/core/{$parser}/{$parser}_category");
		$this->load->helper("parsers/additional/{$parser}/{$parser}_category");
		// подтягиваем файл кастомного парсинга категории для данного рынка
		$this->load->helper("parsers/custom/{$parser}/{$parser}_category");

		if (function_exists("mspro_{$parser}_category_getUrl")) {
			$html = call_user_func("mspro_{$parser}_category_getUrl" , $task['url']);
		} else {
			$html = getUrl($task['url'] , false , $inv_mode);
		}

		if (!$html && function_exists("file_get_contents") ) {
			try {
				@$html = file_get_contents($task['url']);
			} catch (Exception $e) {};
		}

		if ($html) {
			$result = false;

			if (function_exists('parse_category')) {
				$result = parse_category($html , $task);
			}
			if (function_exists('parse_category_custom')) {
				$result = parse_category_custom($html , $task);
			}

			if(isset($result) && is_array($result['products']) && count($result['products']) > 0){
				$product_ids = array();
				foreach($result['products'] as $product){
					$p_id = $this->tasks->insertTask($product , $task['ins_id'] , 0);
					$product_ids[] = $p_id;
				}
				$products_found = count(array_unique($product_ids));
			}
			
			// таск на следующую страницу
			if(isset($result) && $result['next_page'] && strlen(trim($result['next_page'])) > 0 && is_array($result['products']) && count($result['products']) > 0){
				$next_page_exists = "YES";
				//echo $result['next_page'];
				$this->tasks->insertTask($result['next_page'] , $task['ins_id'] , 0 , "category");
			}

			if($products_found > 0 || $result['next_page']){
				$this->tasks->deleteTask($task['id']);
			}
		} else {
			if ($settings['dev_mode'] == "on") {
				write_file("CANNOT GET HTML FOR THIS URL " . $task['url'] . "<br/>" , "public/files/log.txt" );
			}
		}

		$this->settings->clearCategoryQueque();

		$this->tasks->updateTask($task['id'] , false , false , true);

		return array(
			'products_found' => $products_found,
			'next_page_exists' => $next_page_exists
		);

		exit;
	}

	private function _processProduct($task, $settings) {
		if ($task['product_id'] == 1) return;

		$inv_mode = false;
		if($settings['proxy'] === "on"){
			$inv_mode = true;
		}

		$cms_loader = cms_init();
		if(!$cms_loader){
			echo 'could not process cms_init() function!!! stack: process.php';exit;
		}
		
		$ins = $this->tasks->getInsById($task['ins_id']);

		$market = $this->_defineMarket($task['url']);
		$parser = $market['name'];
		$this->donor = $parser;

		if (!$market) {
			if($settings['dev_mode'] == "on"){
				write_file("PRODUCT PARSER - CANNOT FIND MARKET FOR URL - " . $task['url'] . "<br/>" , "public/files/log.txt" );
			}
			$this->tasks->deleteTask($task['id']);
			return false;
		}


		$languages = cms_getLanguages(); 
		$currencies = cms_getCurrenciesArray(); 

		$this->load->helper("parsers/core/{$parser}/{$parser}_product");
		$this->load->helper("parsers/additional/{$parser}/{$parser}_product");
		$this->load->helper("parsers/custom/{$parser}/{$parser}_product");

		$html = false;
		if (function_exists("mspro_{$parser}_getUrl_custom")) {
		   $html = call_user_func("mspro_{$parser}_getUrl_custom" , $task['url']);
		} elseif (function_exists("mspro_{$parser}_getUrl")) {
		   $html = call_user_func("mspro_{$parser}_getUrl", $task['url']);
		} else {
			$html = getUrl($task['url'], false, $inv_mode);
		}

		$PRODUCT = cms_emptyProduct();

		$title = false;
		if (function_exists("mspro_{$parser}_title")) {
			$title = call_user_func("mspro_{$parser}_title", $html);
		}
		if (function_exists("mspro_{$parser}_title_custom")) {
			$title = call_user_func("mspro_{$parser}_title_custom", $title , $html);
		}

		$title = str_ireplace(array('"', '&nbsp;', 'sheins', 'shein'), array('&quot;', ' ', '', ''), $title);

		if (!$title || $title == '') {
			if ($task['product_id'] > 0 && !$html) {
			   $this->_productNoMoreAvailable($ins , $task);
			}

			$this->tasks->updateTask($task['id']);

			if ($settings['dev_mode'] == "on") {
				echo 'HAVE GOT EMPTY TITLE. TASK URL: ' . $task['url'] . '<br>HTML: ' . $html;
			}

			return true;
			exit;
		}

		$title = trim($title);

		$desciption = '';
		if (function_exists("mspro_{$parser}_description")) {
			  $desciption = call_user_func("mspro_{$parser}_description", $html , $task['url']);
		}
		if (function_exists("mspro_{$parser}_description_custom")) {
			  $desciption = call_user_func("mspro_{$parser}_description_custom", $desciption , $html);
		}

		$meta_desciption = '';
		if (function_exists("mspro_{$parser}_meta_description")) {
			$meta_desciption = call_user_func("mspro_{$parser}_meta_description", $html);
		}
		if (function_exists("mspro_{$parser}_meta_description_custom")) {
			$meta_desciption = call_user_func("mspro_{$parser}_meta_description_custom", $meta_desciption , $html);
		}
		$meta_desciption = $this->_clearMetaTags($meta_desciption , $parser);

		$meta_keywords = '';
		if (function_exists("mspro_{$parser}_meta_keywords")) {
			$meta_keywords = call_user_func("mspro_{$parser}_meta_keywords", $html);
		}
		if (function_exists("mspro_{$parser}_meta_keywords_custom")) {
			$meta_keywords = call_user_func("mspro_{$parser}_meta_keywords_custom", $meta_keywords , $html);
		}
		$meta_keywords = $this->_clearMetaTags($meta_keywords, $parser);

		$meta_title = '';
		if (function_exists("mspro_{$parser}_meta_title")) {
			$meta_title = call_user_func("mspro_{$parser}_meta_title", $html);
		}
		if (function_exists("mspro_{$parser}_meta_title_custom")) {
			$meta_title = call_user_func("mspro_{$parser}_meta_title_custom", $meta_title , $html);
		}

		$meta_h1 = '';
		if (function_exists("mspro_{$parser}_meta_h1")) {
			$meta_h1 = call_user_func("mspro_{$parser}_meta_h1", $html);
		}
		if (function_exists("mspro_{$parser}_meta_h1_custom")) {
			$meta_h1 = call_user_func("mspro_{$parser}_meta_h1_custom", $meta_h1, $html);
		}

		$margin_relative = 100 + (float) $ins['margin_relative'];
		$margin_relative = $margin_relative / 100;
		$margin_fixed = (float) $ins['margin_fixed'];

		$rate = 1;
		$price = false;
		if (function_exists("mspro_{$parser}_price")) {
			$price = (float) call_user_func("mspro_{$parser}_price", $html, $task['url']);
		}
		if (function_exists("mspro_{$parser}_price_custom")) {
			$price = (float) call_user_func("mspro_{$parser}_price_custom", $price , $html);
		}
		if($price && (is_int($price) || is_float($price) || is_numeric($price)) ){
			$PRODUCT['price'] = $this->_apply_margins_to_price($price , $rate , $margin_fixed , $margin_relative);
		}

		// PRICE ORIGINAL
		$priceOriginal = false;
		if (function_exists("mspro_{$parser}_priceOriginal")) {
			$priceOriginal = call_user_func("mspro_{$parser}_priceOriginal", $html , $task['url']);
		}
		if (function_exists("mspro_{$parser}_price_custom")) {
			$priceOriginal = call_user_func("mspro_{$parser}_priceOriginal_custom", $priceOriginal , $html , $task['url']);
		}
		if($priceOriginal && (is_int($priceOriginal) || is_float($priceOriginal) || is_numeric($priceOriginal)) ){
			$priceOriginal = $this->_apply_margins_to_price($priceOriginal , $rate , $margin_fixed , $margin_relative);
			$PRODUCT['priceSpecial'] = $PRODUCT['price'];
			$PRODUCT['price'] = round($priceOriginal , 2);
		}

		// получаем SKU, UPC, MODEL
		$sku = '';
		if (function_exists("mspro_{$parser}_sku")) $sku = call_user_func("mspro_{$parser}_sku", $html);
		if (function_exists("mspro_{$parser}_sku_custom")) $sku = call_user_func("mspro_{$parser}_sku_custom", $sku , $html);
		$PRODUCT['sku'] = $sku;

		$upc = '';
		if (function_exists("mspro_{$parser}_upc")) $upc = call_user_func("mspro_{$parser}_upc", $html);
		if (function_exists("mspro_{$parser}_upc_custom")) $upc = call_user_func("mspro_{$parser}_upc_custom", $upc , $html);
		$PRODUCT['upc'] = $upc;

		$mpn = '';
		if (function_exists("mspro_{$parser}_mpn")) $mpn = call_user_func("mspro_{$parser}_mpn", $html);
		if (function_exists("mspro_{$parser}_mpn_custom")) $mpn = call_user_func("mspro_{$parser}_mpn_custom", $mpn , $html);
		$PRODUCT['mpn'] = $mpn;
		
		$ean = '';
		if (function_exists("mspro_{$parser}_ean")) $ean = call_user_func("mspro_{$parser}_ean", $html);
		if (function_exists("mspro_{$parser}_ean_custom")) $ean = call_user_func("mspro_{$parser}_ean_custom", $ean , $html);
		$PRODUCT['ean'] = $ean;
		
		$isbn = '';
		if (function_exists("mspro_{$parser}_isbn")) $isbn = call_user_func("mspro_{$parser}_isbn", $html);
		if (function_exists("mspro_{$parser}_isbn_custom")) $isbn = call_user_func("mspro_{$parser}_isbn_custom", $isbn , $html);
		$PRODUCT['isbn'] = $isbn;

		$model = '';
		if (function_exists("mspro_{$parser}_model")) $model = call_user_func("mspro_{$parser}_model", $html);
		if (function_exists("mspro_{$parser}_model_custom")) $model = call_user_func("mspro_{$parser}_model_custom", $model , $html);
		$PRODUCT['model'] = $model;
		
		if(empty($PRODUCT['sku']) && !empty($PRODUCT['model'])){ 
			$PRODUCT['sku'] = $PRODUCT['model'];
		}
		
		// weight
		// DEFAULTS: 1 - Kilogram, 2 - Gram, 5 - Pound, 6 - Ounce
		$weight = false;
		if (function_exists("mspro_{$parser}_weight")) $weight = call_user_func("mspro_{$parser}_weight", $html);
		if (function_exists("mspro_{$parser}_weight_custom")) $weight = call_user_func("mspro_{$parser}_weight_custom", $weight , $html);
		if (isset($weight['weight'])) $PRODUCT['weight'] = $weight['weight'];
		if (isset($weight['weight_class_id'])) $PRODUCT['weight_class_id'] = $weight['weight_class_id'];
		
		// length, width, height
		// DEFAULTS: 1 - cm, 2 - mm, 3 - inch 
		$length = false;$width = false;$height = false;
		if (function_exists("mspro_{$parser}_dimensions")) $dims = call_user_func("mspro_{$parser}_dimensions", $html);
		if (function_exists("mspro_{$parser}_dimensions_custom")) $dims = call_user_func("mspro_{$parser}_dimensions_custom", $dims , $html);
		if (isset($dims['length'])) $PRODUCT['length'] = $dims['length'];
		if (isset($dims['width'])) $PRODUCT['width'] = $dims['width'];
		if (isset($dims['height'])) $PRODUCT['height'] = $dims['height'];
		if (isset($dims['length_class_id'])) $PRODUCT['length_class_id'] = $dims['length_class_id'];
		// echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
		
		// определяем колличество товара
		$PRODUCT['quantity'] = (isset($ins['products_quantity']) && $ins['products_quantity'] >= 0 )?$ins['products_quantity']:$this->config->item('ms_default_quantity_of_products');
		
		// устанавливаем tax_class_id
		$PRODUCT['tax_class_id'] = (isset($ins['tax_class_id']) && $ins['tax_class_id'] > 0 )?$ins['tax_class_id']:0;
			
		// CATEGORY
		$c = 0;
		$category = explode("," , $ins['category_id']);
		if(count($category) > 0){
			foreach($category as $cat){
				if($c < 1){ $PRODUCT['main_category_id'] = $cat; }
				$PRODUCT['product_category'][] = $cat;
				$c++;
			}
		}

		// SEO URL
		$this->translit_name = $this->_getSeoUrl($title , $PRODUCT['sku'], $PRODUCT['model']);
		//echo $this->translit_name;exit;
		$PRODUCT['keyword'] = $this->translit_name;
		$PRODUCT['translit_name'] = $this->translit_name;

		if ( ($task['product_id'] < 1 && isset($ins['get_options']) && $ins['get_options'] > 0) || ($task['product_id'] > 0 && isset($ins['do_not_update_options']) && $ins['do_not_update_options'] < 1) ) {
			$options = array();
			if (function_exists("mspro_{$parser}_options")) $options = call_user_func("mspro_{$parser}_options", $html);
			if (function_exists("mspro_{$parser}_options_custom")) $options = call_user_func("mspro_{$parser}_options_custom", $options , $html);
			if (count($options) > 0) {
				$PRODUCT['product_option'] = $this->_prepare_options($options , $rate , $margin_relative , $PRODUCT['quantity']);
			}
			// echo '<pre>'.print_r($PRODUCT['product_option'] , 1).'</pre>';exit;
		}

		$product_id = 0;
		$new = $task['product_id'] < 1;

		if ($new) {
			$PRODUCT['product_description'] = array();
			$data['name'] = $title;
			$DESC = $this->_processSpecImages($desciption , $this->translit_name, $ins['description_image_limit'], $ins['do_not_upload_description_image']);
			// $DESC = $desciption;
			$data['description'] = trim($DESC);
			$data['meta_description'] = $meta_desciption;
			$data['seo_h1'] = $meta_h1;
			$data['meta_keyword'] = $meta_keywords;
			$data['meta_title'] = $title;
			$data['seo_title'] = $meta_title;
			$PRODUCT['product_description'] = $data;

			$PRODUCT['status'] = 1;
			if ($ins['create_disabled'] > 0) $PRODUCT['status'] = 0;

			$product_id = cms_insertProduct($PRODUCT, $ins);

			if ($product_id == false) {
				$this->tasks->updateTask($task['id'], 1, true, false, $title, $PRODUCT['price']);
				return false;
			}

			$main_image = false;
			if (function_exists("mspro_{$parser}_main_image")) {
				$main_image = call_user_func("mspro_{$parser}_main_image", $html);
			}
			if (function_exists("mspro_{$parser}_main_image_custom")) {
				$main_image = call_user_func("mspro_{$parser}_main_image_custom", $html);
			}
			if ($main_image && $main_image != '' && ( (isset($ins['main_image_limit']) && (int) $ins['main_image_limit'] !== 0) || !isset($ins['main_image_limit']) ) ) {
				$mainImageId = cms_saveImage( $this->translit_name , $main_image,  rand(0 , 10000) ,  $product_id , false , true);
			}

			$other_images = false;          
			if (function_exists("mspro_{$parser}_other_images")) {
				$other_images = call_user_func("mspro_{$parser}_other_images", $html , $task['url']);
			}
			if (function_exists("mspro_{$parser}_other_images_custom")) {
				$other_images = call_user_func("mspro_{$parser}_other_images_custom", $html);
			}
			// echo '<pre>'.print_r($other_images , 1).'</pre>';exit;

			if(isset($ins['main_image_limit']) && (int) $ins['main_image_limit'] > 1){
				array_splice($other_images, (int) ($ins['main_image_limit'] - 1) );
			}elseif(isset($ins['main_image_limit']) && ( (int) $ins['main_image_limit'] == 0 || (int) $ins['main_image_limit'] == 1 )){
				unset($other_images);
			}
			
			if($other_images){
				$otherImages = array();
				if (count($other_images) > 0 && is_array($other_images)) {
					foreach ($other_images as $index => $value) {
						$otherImages[] = cms_saveImage($this->translit_name , $value,  $index , $product_id, false, false);
					}
					if(count($otherImages) > 0) { cms_saveGallery($product_id , $otherImages); }
				}
			}

			$this->tasks->updateTask($task['id'], $product_id, true, false , $title , $PRODUCT['price']);
		} else {
			$product_id = $task['product_id'];
			cms_updateProduct($PRODUCT , $task['product_id'] , $ins);		
			$this->tasks->updateTask($task['id'] , false , false , false , $title , $PRODUCT['price']);
		}

		$noMoreAvailable = false;
		if (function_exists("mspro_{$parser}_noMoreAvailable")) {
			$noMoreAvailable = call_user_func("mspro_{$parser}_noMoreAvailable", $html);
		} elseif (function_exists("mspro_{$parser}_noMoreAvailable_custom")) {
			$noMoreAvailable = call_user_func("mspro_{$parser}_noMoreAvailable_custom", $html);
		}
		if ($noMoreAvailable === true) {
			$this->_productNoMoreAvailable($ins , $task);
		}

		if (function_exists("mspro_{$parser}_finished")) {
			call_user_func("mspro_{$parser}_finished", $html, $product_id, $task['url'], $new);
		}

		// добавляем счётчик category_queque
		$this->settings->addCategoryQueque();
		// если TRIAL  - добавляем счётчик спарсенных товаров 
		if($this->config->item('ms_trmode')){
			$this->settings->add_trmode_num_product();
		}

		return true;
	}

	private function _productNoMoreAvailable($ins , $task){
		switch($ins['what_to_do_product_not_exists']){
			// nothing doing
			case '0':break;
			// delete product from store
			case 1:$this->tasks->deleteTask($task['id']);cms_deleteProduct($task['product_id']);break;
			// set "Out of Stock" status
			case 2:cms_setOutOfStock($task['product_id']);break;
			// set 0 quantity
			case 4:cms_setZeroQuantity($task['product_id']);break;
			default:break;
		}
	}

	private function _defineMarket($url){
		//echo $url;exit;
		// get markets array (see markets_helper.php)
		$markets = merge_custom_markets($this->config->item("markets") , $this->config->item("additionalmarkets"));
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		// get target host from url
		$host = parse_url($url);

		if(!isset($host['host']) && isset($host['path']) && strpos($url , "ttp") < 1 ){
			$host = parse_url("http://" . $url);
		}
		$host = str_ireplace(array("www.") , array("") , trim($host['host']));
		//echo '<pre>'.print_r($host , 1).'</pre>';
		
		
		// сравниваем url_aliases каждого рынка с хостом таска
		foreach($markets as $market){
			if(isset($market['url_aliases']) && is_array($market['url_aliases']) && count($market['url_aliases']) > 0){
				foreach($market['url_aliases'] as $market_alias){
					if(strpos($host , $market_alias) > -1){
						//echo '<pre>'.print_r($market , 1).'</pre>';exit;
						return $market;	
					}
				}
			}
		}
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		return false;
	}
	
	
	private function _getParsers(){
		$this->load->helper('libs/parser');
		$this->load->helper('libs/phpquery');
		$this->load->helper('libs/nokogiri');
	}
	
	
	private function _getSeoUrl_OLD($name , $sku , $model){
		$out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $name);
		$out = substr($out , 0, 150);
		if(strlen($sku) > 1){
			$out .= '-'.$sku;
		}elseif(strlen($model) > 1){
			$out .= '-'.$model;
		}
		$out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $out);
		//echo $out;
		return $out;
	}
	
	private function _getSeoUrl($name , $sku , $model){
		$name = str_ireplace(array('®' , '&quot;' , '&amp;' , '"' , "'" , '@' , '#' , '%' , '^' , '&' , '*') , "", $name);
		if(function_exists("transliterator_transliterate")){
			$out = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $name));
			if(strlen($sku) > 1){
				$out .= '-'.$sku;
			}elseif(strlen($model) > 1){
				$out .= '-'.$model;
			}
			$out = str_ireplace(" ", "-" , $out);
		}else{
			$out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $name);
			$out = substr($out , 0, 100);
			if(strlen($sku) > 1){
				$out .= '-'.$sku;
			}elseif(strlen($model) > 1){
				$out .= '-'.$model;
			}
			$random_add = '';
			$hieroglifs = array('taobao' , 'alibaba');
			//echo 'donor:' . $this->donor;exit;
			if(in_array($this->donor , $hieroglifs)){
				$random_add = '_' . rand(0,10000);
			}
		}
		$out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $out . $random_add);
		$out = str_replace(array("----" , "---" , "--") , "-" , $out);
		//echo $out;
		return $out;
	}
	
	private function _clearMetaTags($str , $market , $originalPrice = false){
		//echo $market . ' - ' . $str . ' - ';
		$markets_array = array("$market.com" , "$market.co.uk" , "$market.fr" , "$market.ru" , "$market.net" , "$market.de" , "$market.pt" , "$market.es" , "$market.us" , $market);
		return str_ireplace($markets_array , "" , $str);
	}
	
	
	private function _processSpecImages($description , $productName , $description_image_limit, $do_not_upload_description_image){
		// get images array
		preg_match_all('/(<img[^<]+>)/Usi', $description, $images);
		$image = array();
		foreach ($images[0] as $index => $value) {
			$s = strpos($value, 'src="') + 5;
			$e = strpos($value, '"', $s + 1);
			$image[$value] =   substr($value, $s, $e - $s);
		}
		//echo '<pre>'.print_r($image , 1).'</pre>';exit;
		$cnt_others = 0; 
		foreach ($image as $index => $value) {
			
			// only for focalprice
			$value = str_ireplace(array("860x666") , array("550x426") , $value);
			$value = str_replace(array(" ") , array("%20"), $value);
			
			if($description_image_limit > -1){
				if($cnt_others >= $description_image_limit){
					$description = str_replace($index, '', $description);
				}else{
					if($do_not_upload_description_image < 1){
						$res = cms_saveImage($productName , $value,  $cnt_others , false, true, false);
						$description = str_replace($index, '<img src="' . $res . '" alt="' . $productName . '" />', $description);
					}
				}
			}else{
				if($do_not_upload_description_image < 1){
					$res = cms_saveImage($productName , $value,  $cnt_others , false, true, false);
					$description = str_replace($index, '<img src="' . $res . '" alt="' . $productName . '" />', $description);
				}
			}
			$cnt_others++;
		}
		return $description;
	}

	private function _prepare_options($options , $rate , $margin_relative , $quantity ){
		$out = array();

		foreach($options as $option){
			if(isset($option['name']) && isset($option['values']) && is_array($option['values']) && count($option['values']) > 0 ){
				$OPTION = array();
				$OPTION['product_option_id'] = '';
				$OPTION['option_name'] = cms_getOption($option['name'] , $option['type']);
				$OPTION['name'] = $option['name'];
				$OPTION['type'] = $option['type'];
				$OPTION['product_option_values'] = implode(' | ' , $option['values']);
				$out[] = $OPTION;
			}
		}

		return $out;
	}

	function _getOptionValue($valueName , $option_id){
		$res = cms_getOptionValue($valueName, $option_id);
		if(!$res){
			$res = cms_insertOptionValue($valueName , $option_id);
		}
		return (int) $res;
	}
	
	function _apply_margins_to_price($price , $rate , $margin_fixed , $margin_relative){
		$res = $price / $rate;
		$res = ( $res * $margin_relative ) + $margin_fixed;
		return $res;
	}

	private function _prepare_attributes($attributes){
		$out = array();
		foreach($attributes as $attribute){
			if(isset($attribute['group']) && isset($attribute['name']) && isset($attribute['value']) ){
				$ATTR = array();
				$ATTR['name'] = $attribute['name'];
				$attributeGroupID = $this->_getAttributeGroupID($attribute['group']);
				$ATTR['attribute_id'] = $this->_getAttributeID($attribute['name'] , $attributeGroupID);
				$ATTR['product_attribute_description'] = array('1' => array('text' => $attribute['value']));
				$out[] = $ATTR;
			}
		}
		return $out;
	}

	private function _getAttributeGroupID($groupName){
		$res = cms_getAttributeGroup($groupName);
		if(!$res){
			$res = cms_insertAttributeGroup($groupName);
		}
		return (int) $res;
	}
	
	// try to find Attribute ID by Name and Attribute GROUP ID (otherwise will create this attribute)
	private function _getAttributeID($name , $groupID){
		$res = cms_getAttribute($name , $groupID);
		if(!$res){
			$res = cms_insertAttribute($name , $groupID);
		}
		return (int) $res;
	}	
}