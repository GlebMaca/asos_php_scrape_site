<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interf extends MS_Controller {

	
	/*
	 *   main - SETTINGS page
	 */
	public function index(){
		$nots = array();
		if($this->input->post("state") && !$this->config->item('ms_DEMO')){			
			$state = $this->input->post("state");
			$dev_mode = $this->input->post("dev_mode");
			$au = $this->input->post("au");
			$inv_mode = $this->input->post("inv_mode");
			$lang = $this->input->post("lang");
			$num_product = $this->input->post("num_product");
			// passwords
			$old_pass = trim($this->input->post("old_pass"));
			$new_pass = trim($this->input->post("new_pass"));
			$confirm_pass = trim($this->input->post("confirm_pass"));
			
			if($state || $lang || $num_product || $dev_mode || $inv_mode || $au){
				$this->settings->changeSettings($state , $lang, $num_product , $dev_mode , $inv_mode , $au);
				$nots["modified"] = true;
				//if($lang) $this->lang->load($lang , $lang);
			}
			if( !($old_pass == "" && $new_pass == "" && $confirm_pass == "")){
				$res  = $this->settings->changePass($old_pass , $new_pass , $confirm_pass);
				if(array_key_exists('nopass', $res)) {$nots["nopass"] = true;}
				if(array_key_exists('shortpass', $res)) {$nots["shortpass"] = true;}
				if(array_key_exists('nopassmatch', $res)) {$nots["nopassmatch"] = true;}
				if(array_key_exists('passchanged', $res)) {$nots["passchanged"] = true;}
			}
			//print_r($nots);exit;
		}
		
		$add = array('addJS' => array('bpopup' , 'alertify') , 'addCSS' => array('bpopup' , 'alertify' , 'alertify_default'));
		$settings = $this->settings->getSettings();
		//print_r($nots);
		$menu_data  = array('active' => "settings");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('settings' , array('settings' => $settings,
											 'langs' => $this->config->item('ms_langs'),
											 'notifications' => $nots,
		                                     'demo' => $this->config->item('ms_DEMO')?"yes":"no"
											));
		$this->load->view('footer');
	}
	
	/*
	 *  HOW TO USE MSPRO PAGE
	 */
	public function howtouse(){
		$add = array('addJS' => array('ui/js/jquery-ui.min' , 'bpopup' , 'ui/js/widget') , 'addCSS' => array('cupertino/jquery-ui.min' , 'cupertino/tabs' , 'bpopup'));
		$menu_data  = array('active' => "howtouse");
		$markets = array('core' => $this->config->item("markets") , 'additional' =>  $this->config->item("additionalmarkets"));
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('howtouse' , array('markets' => $markets ));
		$this->load->view('footer');
	}
	
	
	/*
	 *  TASKS
	 */
	public function tasks($page = false){
	    //echo $page;
		$this->load->model("tasks");
		$this->load->helper("cms/" . $this->config->item("ms_cms"));
		
		$cms_loader = cms_init();
		
		if(!$cms_loader){
			echo 'could not process cms_init() function!!! stack: controller interf';exit;
		}
		$settings = $this->settings->getSettings();
		$categories = cms_getCategories();
		$manufacturers = cms_getManufacturers();
		$taxclasses = cms_getTaxClasses();
		$currencies = cms_getCurrencies();
		
		// Vendors for CS-Cart
		$vendors = array();
		if(function_exists("cms_getVendors")){
		    $vendors = cms_getVendors();
		}
		// SEARCH TERM
		$searchTerm = '';
		if($this->session->userdata("ms_search_term") && strlen(trim($this->session->userdata("ms_search_term"))) > 1){
		    $searchTerm = $this->session->userdata("ms_search_term");
		}
		// NUMBER OF TASKS PER PAGE
		$numberPerPage = false;
		$page = $page == false?1:$page;
		if($this->session->userdata("ms_numperpage") && (int) $this->session->userdata("ms_numperpage") > 1){
		    $numberPerPage = $this->session->userdata("ms_numperpage");
		}
		if($page !== false && (int) $page > 0){ $page = (int) $page; }else{ $page = 1;}
		/*   АЯКСОМ ИДУТ САБМИТЫ ФОРМ */
		// check for ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $this->input->post("action") ) {
				$action  = $this->input->post("action");
				$out = array();
				if($this->config->item('ms_DEMO')){
					header('Content-type: application/json; charset=utf-8');
					echo json_encode(array('result' => "demo"));exit;
				}
				switch($action){
					case 'add':
						if(isset($_POST['data']) && is_array($_POST['data']) && count($_POST['data']) > 0 ){
							$this->tasks->createIns($_POST['data']);
						}
						break;
					case 'edit':
						if(isset($_POST['data']) && is_array($_POST['data']) && count($_POST['data']) > 0 && isset($_POST['id'])){
							$this->tasks->updateIns($_POST['id'] , $_POST['data']);
						}
						break;
					case 'switch':
						if(isset($_POST['task_id']) && isset($_POST['switch'])){
						    $taskIDS = explode("," , $_POST['task_id']);
							if($this->tasks->setSwitch($taskIDS , $_POST['switch'])){
								$out['result'] = "success";
							}
						}
						break;
					case 'set_priority':
						if(isset($_POST['task_id']) && isset($_POST['priority'])){
						    $taskIDS = explode("," , $_POST['task_id']);
							if($this->tasks->setPriority($taskIDS , $_POST['priority'])){
								$out['result'] = "success";
							}
						}
						break;
					case 'restart':
					    // echo $_POST['task_id'];exit;
						if(isset($_POST['task_id'])){
						    $taskIDS = explode("," , $_POST['task_id']);
						    // echo '<pre>'.print_r($taskIDS , 1).'</pre>';exit;
							$this->tasks->restartIns($taskIDS);
						}
						break;
					case 'delete':
						if(isset($_POST['task_id']) && isset($_POST['with_products']) ){
						    $taskIDS = explode("," , $_POST['task_id']);
							$delete_products_ids = $this->tasks->deleteIns($taskIDS , $_POST['with_products']);
							// удаляем товары из магазина
							if(is_array($delete_products_ids) && count($delete_products_ids) > 0){
								foreach($delete_products_ids as $delete_products_id){
									cms_deleteProduct( $delete_products_id );
								}
							}
						}
						break;
					case 'search':
					    $search = (string) trim($_POST['searchTerm']);
					    if(strlen($search) > 1){
					        $this->session->set_userdata("ms_search_term" , $search);
					    }else{
					        $this->session->unset_userdata('ms_search_term');
					    }
						break;
					case 'numperpage':
					    $numperpage = (string) trim($_POST['numPerPage']);
					    if((int) $numperpage > 1){
					        $this->session->set_userdata("ms_numperpage" , $numperpage);
					    }else{
					        $this->session->unset_userdata('ms_numperpage');
					    }
					    break;
					default:
						break;
				}
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($out);exit;
				exit;
		}
		
		//echo '<pre>'.print_r($this->_prepareGrabbedProductData($this->tasks->getGrabbedProducts()) ,1 ).'</pre>';exit;
		//echo '<pre>'.print_r( $this->tasks->getAllIns($searchTerm, $page, $numberPerPage) ,1 ).'</pre>';exit;
		//echo '<pre>'.print_r($this->tasks->getInstructionsIdsWithGrabbedProducts() ,1 ).'</pre>';exit;
		
		$add = array('addJS' => array('ui/js/jquery-ui.min' , 'bpopup',  'dataTable' , 'alertify') , 'addCSS' => array("lists" , 'cupertino/jquery-ui.min' , 'bpopup' , 'alertify' , 'alertify_default' , 'dataTable'));
		$menu_data  = array('active' => "tasks");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		// ALL INS COUNT
		$count_all_ins = $this->tasks->getInsCount();
		// ALL INS COUNT CONSIDER SEARCH
		$count_all_ins_search = strlen($searchTerm) > 1?$this->tasks->getInsCount($searchTerm):$count_all_ins;
		$instructions_data = array('count_all_ins' => $count_all_ins, 'count_all_ins_search' => $count_all_ins_search);
		$this->load->view( MSPRO_CMS . '/tasks' , array(
		                                  'instructions' =>  $this->tasks->getAllIns($searchTerm, $page, $numberPerPage),
		                                  'instructions_data' => $instructions_data,
										  'categories'	 => $this->_prepareCats($categories),
										  'settings' => $settings,
										  'manufacturers'	 => $this->_prepareMans($manufacturers),
		                                  'taxclasses'	 => $this->_prepareTaxes($taxclasses),
										  'currencies'	 => $this->_prepareCurs($currencies),
		                                  'vendors'	 => $vendors,
		                                  'searchTerm' => $searchTerm,
		                                  'numberPerPage' => $numberPerPage,
		                                  'pager' => $this->_preparePager($count_all_ins_search , $page, $numberPerPage),
										  'products_grabbed' => $this->tasks->getInstructionsIdsWithGrabbedProducts($searchTerm)
										   ));
		$this->load->view('footer');
	}
	
	/*
	 *  PRODUCTS FOR AJAX TABLE
	 */
	public function products($target){
		$this->load->model("tasks");
		$this->load->helper("cms/" . $this->config->item("ms_cms"));
		cms_init();
		// search
		if(!isset($_POST['search']['value'])){ 
		    $_POST['search']['value'] = "";
		}
		// sorting
        $sorting_cells = array('id' , 'p_date' , 'product_name' , 'product_name' , 'product_price' , 'p_date' , 'p_date' , 'p_date' , 'p_date_update');
        if(!isset($_POST['order'][0]['column'])){ 
            $_POST['order'][0]['column'] = 7;
        }
        if(!isset($_POST['order'][0]['dir'])){ 
            $_POST['order'][0]['dir'] = "desc";
        }
		$PRODUCTS = $this->tasks->getGrabbedProducts( $target  , $_POST['start'] , $_POST['length'] , $_POST['search']['value']  , $sorting_cells[$_POST['order'][0]['column']] , $_POST['order'][0]['dir']);
		// echo '<pre>'.print_r($PRODUCTS , 1) .'</pre>';// exit;
		$products = $PRODUCTS['data'];
		// echo '<pre>zero:'.print_r($products , 1) .'</pre>';// exit;
		if(count($products) > 0){
			$products = $this->_prepareGrabbedProductData( $products );
		}
		//echo '<pre>'.print_r($products , 1) .'</pre>';exit;
		$res = array( "data" => $products, "recordsTotal" => $PRODUCTS['recordsTotal'], "recordsFiltered" => $PRODUCTS['recordsFiltered'], "draw" => $_POST['draw']);
		echo json_encode($res);//exit;
	}
	
	
	
	/*	 LOG PAGE
	 * to write new content to the LOG file 
	   write_file("what to add" , "public/files/log.txt"  , false);
	   write_file("what to add" , "public/files/devlog.txt" , false); 
	 * logs file is in the  "public/files/" folder
	 */
	public function log(){
		$log_info = read_file();
		if($this->input->post("action")){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				header('Content-type: application/json');
				// DEMO MODE
				if($this->config->item('ms_DEMO')){
					echo json_encode(array('response' => "demo"));exit;
				}
				switch($this->input->post("action")){
					case 'refresh':
						echo json_encode(array('response' => $log_info));exit; 
						break;
					case 'clear':
						write_file("" , "public/files/log.txt"  , true);
						$log_info = read_file();
						echo json_encode(array('response' => $log_info));exit; 
						break;
					default;
					/* write используется в коде как 
					write_file("what to add" , "public/files/log.txt"  , false);
					write_file("what to add" , "public/files/devlog.txt" , false); 
					*/
					break;
				}
  				echo $this->input->post("action");exit;
			}
		}
		$menu_data  = array('active' => "log");
		$add = array('addJS' => array('alertify') , 'addCSS' => array( 'alertify' , 'alertify_default'));
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('log' , array('info' => $log_info));
		$this->load->view('footer');
	}
	
	
	
	/*
	 * MANUAL LAUNCH PAGE 
	 */
	public function manual(){
		$add = array('addJS' => array('alertify') , 'addCSS' => array('alertify' , 'alertify_default' ));
		$menu_data  = array('active' => "contact");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('manual' );
		$this->load->view('footer');
	}
	
	
	public function logout(){
		$this->session->unset_userdata('ms_admin_perms');
		redirect('/', 'refresh');
	}
	
	public function reinstall(){
	    if( !$this->config->item('ms_DEMO') ){
    		$this->settings->deleteTables();
    		$this->session->set_userdata("multiscraper_restarted" , "yes");
	    }
		redirect('/', 'refresh');
	}
	
	
	
	
	
	/************************************   PRIVATE   *****************************/
	private function _prepareCats($categories){
		$out = array();
		if(is_array($categories) && count($categories) > 0){
			foreach($categories as $category){
				$out[$category['category_id']] = $category['name'];
			}
		}
		return $out;
	}
	
	private function _prepareMans($manufacturers){
		$out = array();
		if(is_array($manufacturers) && count($manufacturers) > 0){
			foreach($manufacturers as $manufacturer){
				$out[$manufacturer['manufacturer_id']] = $manufacturer['name'];
			}
		}
		return $out;
	}
	
	private function _prepareTaxes($taxclasses){
	    $out = array();
	    if(is_array($taxclasses) && count($taxclasses) > 0){
	        foreach($taxclasses as $taxclass){
	            $out[$taxclass['tax_class_id']] = $taxclass['title'];
	        }
	    }
	    //echo '<pre>' . print_r($taxclasses , 1) . '</pre>';exit;
	    return $out;
	}
	
	
	private function _prepareCurs($currencies){
		$out = array();
		if(is_array($currencies) && count($currencies) > 0){
			foreach($currencies as $currency){
				$out[$currency['currency_id']] = $currency['title'];
			}
		}
		return $out;
	}
	
	private function _trimArr($arr){
		$out = array();
		if(is_array($arr) && count($arr) > 0){
			foreach($arr as $k => $v){
				$out[] = trim($v);
			}
		}
		return $out;
	}
	
	
	private function _prepareGrabbedProductData( $arr ){
		$out = array();
		if(count($arr) > 0){
		    // echo '<pre>TESTY:'.print_r((array) $arr , 1).'</pre>';// exit;
			$product_ids = array();
			foreach($arr as $prod_id => $prod){
				$product_ids[] = $prod_id;
				$arr[$prod_id] = (array) $prod;
			}
			$PRODUCTS = cms_getProducts($product_ids);
			foreach($arr as $prod_id => $prod){
				if(isset($PRODUCTS[$prod_id]) && is_array($PRODUCTS[$prod_id])){
					$arr[$prod_id] = array_merge($prod , $PRODUCTS[$prod_id]);
				}
			}
		    /// echo '<pre>PRODUCTS:'.print_r($PRODUCTS , 1).'</pre>'; //exit;
			$number = 1;
			$statuses =  $this->config->item("statuses");
			// echo '<pre>PRODUCTS:'.print_r($statuses , 1).'</pre>';exit;
			foreach($arr as $prod){ 
				if( isset($prod['product_id']) && isset($prod['name']) && isset($prod['image']) ) {
					// echo '<pre>'.print_r($prod , 1).'</pre>';exit;
					$product = array();
					$product[] = $number;$number++;
					$product[] = cms_getImageThumb($prod['image']);
					//echo '<pre>sex:'.print_r($product , 1).'</pre>';
					$product[] = '<a href="' .  cms_createProductStoreLink($prod['product_id']) . '" target="_blank" style="text-decoration:underline;">' . $this->_prepareGrabbedProductName($prod['name']) . '</a>';
					//echo '<pre>1'.print_r($product , 1).'</pre>';
					$product[] = '<a href="' . $prod['url'] . '" target="_blank" style="text-decoration:underline;">' . $this->_prepareGrabbedProductName($prod['name']) . '</a>';
					//echo '<pre>2'.print_r($product , 1).'</pre>';
					$product[] = $prod['price'];
					$product[] = $prod['quantity'];
					
					$product[] = cms_createStatusById($prod['stock_status_id'] , $statuses);
					$product[] = $prod['p_date'];
					$product[] = $prod['p_date_update'];
					$out[] = $product;
				}
			}
		// echo '<pre>END OF _prepareGrabbedProductData function'.print_r($out , 1).'</pre>';exit;
		return $out;
		}
	}

	
	private function _prepareGrabbedProductName($name){
		if(function_exists("mb_strlen") && function_exists("mb_substr")){
			return mb_strlen($name) > 40?mb_substr($name , 0 , 40 , "utf-8").'...':$name;
		}else{
			return strlen($name) > 40?substr($name , 0 , 40).'...':$name;
		}
	}
	
	
	// PAGINATION
	private function _preparePager($insCount, $page, $numberPerPage){
	    $out = '';
	    if($numberPerPage !== false && (int) $numberPerPage > 0 && $insCount > $numberPerPage){
	        $out = '<div><ul class="pagination">';
	        $pageCount = ceil($insCount/$numberPerPage);
	        // previous page
	        if($page > 1){
	            $out .= '<li><a href="' . $this->config->item("base_url") . 'tasks/' . ($page - 1) . '">&lt;</a>';
	        }
	        // pager
	        for($i = 1; $i <= $pageCount; $i++){
	            if($i == $page){
	                $out .= '<li class="active"><span>' . $i . '</span></li>';
	            }else{
	               $out .= '<li><a href="' . $this->config->item("base_url") . 'tasks/' . $i . '" style="text-decoration:underline;">' . $i . '</a></li>';
	            }
	        }
	        // next page
	        if($page < $pageCount){
	            $out .= '<li><a href="' . $this->config->item("base_url") . 'tasks/' . ($page + 1) . '">&gt;</a>';
	        }
	        $out .= '</ul></div>';
	    }
	    return $out;
	}
	
	
	
	
	
	
	
	// HELPER TO CREATE NEW HELPERS )))
	// MYSQL LOGGING: https://blog.skunkbad.com/databases/mysql/enable-general-and-slow-query-logging
	/*
	SET GLOBAL general_log = 'ON';
	SET GLOBAL slow_query_log = 'ON';
	SET GLOBAL general_log = 'OFF';
	SET GLOBAL slow_query_log = 'OFF';
	*/
	// TARGETS:
	// 1 - cms_getImagesLocation
	// 2 - cms_getCategories
	// 3 - cms_getManufacturers
	// 4 - cms_getCurrencies
	// 5 - cms_getCurrenciesArray
	// 6 - cms_getLanguages
	// 7 - cms_getTaxClasses
	// 8 - cms_deleteProduct
	// 9 - cms_createProductStoreLink
	// 10 - cms_setOutOfStock
	// 11 - cms_setZeroQuantity
	// 12 - cms_disableProduct
	// 13 - cms_getShopDefault
	// 14 - cms_getLangDefault
	// 15 - cms_getImageTypes
	// USAGE: http://multiscraper/create/PASSWORD/TARGET
	public function create($pass , $target , $id = false){
	    if(md5($pass) === "9063c2ed10414912a12eee657d961999"){
	        $out = false;
	        $willGrab = 'aliexpress';
	        $this->load->helper('parsers/core/aliexpress/aliexpress_product');
	        $this->load->helper('parsers/core/focalprice/focalprice_product');
	        $this->load->helper('parsers/core/banggood/banggood_product');
	        $this->load->helper("cms/" . $this->config->item("ms_cms"));
	        $productPage = file_get_contents(realpath(dirname(__FILE__) . '/../../public/files/testLocalProduct.txt'));
	        //echo $productPage;
	        cms_init();
	        switch ($target){
	            case 1: $out = cms_getImagesLocation();break;
	            case 2: $out = cms_getCategories();break;
	            case 3: $out = cms_getManufacturers();break;
	            case 4: $out = cms_getCurrencies();break;
	            case 5: $out = cms_getCurrenciesArray();break;
	            case 6: $out = cms_getLanguages();break;
	            case 7: $out = cms_getTaxClasses();break;
	            case 8: if((int) $id > 0){ $out = cms_deleteProduct($id); }else{echo "NO ID for function cms_deleteProduct()";};break;
	            case 9: if((int) $id > 0){ $out = cms_createProductStoreLink($id); }else{echo "NO ID for function cms_createProductStoreLink()";};break;
	            case 10: $out = cms_setOutOfStock($id);break;
	            case 11: $out = cms_setZeroQuantity($id);break;
	            case 12: $out = cms_disableProduct($id);break;
	            case 13: $out = cms_getShopDefault();break;
	            case 14: $out = cms_getLangDefault();break;
	            case 15: $out = cms_getImageTypes($id);break;
	            default: echo 'Specify the target function';exit;break;
	        }
	        if(is_array($out)){
	            echo '<pre>'.print_r($out , 1).'</pre>';
	        }else{
	            echo var_dump($out);
	        }
	    }else{
	        echo 'FOAD';
	    }
	    exit;
	}
	
	
	// HELPER TO GET DUMPS
	// TARGETS:
	// 1 - all
	// 2 - instructions
	// 3 - tasks
	// 4 - settings
	// USAGE: http://multiscraper/dump/PASSWORD/TARGET
	public function dump($pass , $target){
	    if(md5($pass) === "9063c2ed10414912a12eee657d961999"){
	        switch ($target){
	            case 1: echo $this->settings->get_dump_all();break;
	            case 2: echo $this->settings->get_dump_ins();break;
	            case 3: echo $this->settings->get_dump_tasks();break;
	            case 4: echo $this->settings->get_dump_settings();break;
	            default: echo 'Specify the target function';exit;break;
	        }
	    }else{
	        echo 'FOAD';
	    }
	    exit;
	}
	
	public function au($TS){
	    // CMS
	    $cms = $this->config->item('ms_cms');
	    echo '=============  CMS: ' . $cms . ' ================<br />';
	    $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/database.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "config/database.php" , $r); echo 'DATABASE file upgraded<br />'; }
	    $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/helper.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "helpers/cms/" . $cms . "_helper.php" , $r); echo 'HELPER file upgraded<br />'; }
	    $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/tasks.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "views/" . $cms . "/tasks.php" , $r); echo 'TASKS file upgraded<br />'; }
	    $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/process.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "controllers/" . $cms . "/process.php" , $r); echo 'PROCESS file upgraded<br />'; }
	    
	    // HELPERS
	    echo '<br /><br />============= HELPERS =============';
	    $ms = $this->config->item('markets');
	    if(is_array($ms) && count($ms) > 0){
	        foreach($ms as $m){
	            $name = $m['name'];
	            echo $name . ': ';
	            $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/info.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "helpers/parsers/core/" . $name . "/info.php" , $r); echo 'INFO file upgraded; '; }
	            $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/category.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "helpers/parsers/core/" . $name . "/" . $name . "_category_helper.php" , $r); echo 'CATEGORY file upgraded; '; }
	            $r = $this->_getUI($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/product.txt');if(strlen($r) > 10){ file_put_contents( APPPATH . "helpers/parsers/core/" . $name . "/" . $name . "_product_helper.php" , $r); echo 'PRODUCT file upgraded;<br />'; }
	        }
	    }
	    
	    // update database
	    $this->settings->_changeSettings("ts" , $TS);
	    echo 'SETTINGS in the DB upgraded with new TS';
	}
	
	private function _getUI($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}
	
}