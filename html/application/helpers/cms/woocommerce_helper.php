<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function cms_init() {
	define('WORDPRESS_PATH', realpath(dirname(__file__). '/../../../../' ));
	$ci =& get_instance();
	$ci->load->database();
	$ci->db->query("SET NAMES utf8");
	return true;
}

function cms_getTaxClasses() {
	return array();
}

function cms_getImagesLocation() {
	$path = '/wp-content/uploads/'. date('Y'). '/'. date('m') . '/'. date('d') . '/'. date('H') . '/';
	// создать папку, если ее не существует (а это вполне вероятно)
	@mkdir(WORDPRESS_PATH . $path, 0777, true);

	return $path;
}

function cms_getCategories() {
	$ci =& get_instance();
	$ci->load->database();
	$id_lang_default = 1;
	$wp_categories = array();

	$result = $ci->db->query("SELECT * FROM ". DB_PREFIX. "term_taxonomy LEFT JOIN ". DB_PREFIX. "terms ON ". DB_PREFIX. "terms.term_id=". DB_PREFIX. "term_taxonomy.term_id WHERE `taxonomy`='product_cat' ORDER BY `parent` ASC");

	if ($result->num_rows()) {
		foreach ($result->result() as $row) {
			$name = $row->name;
			if(($row->parent !== 0) && isset($wp_categories[$row->parent]['name'])){
				$name = $wp_categories[$row->parent]['name'] . ' > ' . $row->name;
			}

			$wp_categories[$row->term_id] = array('category_id' => $row->term_id, 'name' => $name, 'parent' => $row->parent);
		}

	}

	return $wp_categories;
}

function cms_getManufacturers(){
	/*
	*  получаем список производителей магазина в формате массива:
		Array
				(
					[0] => Array
						(
							[manufacturer_id] => 8
							[name] => Apple
							[image] => data/demo/apple_logo.jpg
						)
					[1] => Array
						(
							[manufacturer_id] => 9
							[name] => Canon
							[image] => data/demo/canon_logo.jpg
						)
				);
	*
	*  [image] необязательное
	*
	*/

	return array();
}

function cms_getCurrencies(){
	/*
	*  получаем список валют магазина в формате массива:
		Array
			(
				[EUR] => Array
					(
						[currency_id] => 3
						[title] => Euro
						[code] => EUR
						[symbol_left] =>
						[symbol_right] => €
						[decimal_place] => 2
						[value] => 0.80339998
						[status] => 1
						[date_modified] => 2014-11-28 21:37:48
					)

				[GBP] => Array
					(
						[currency_id] => 1
						[title] => Pound Sterling
						[code] => GBP
						[symbol_left] => ВЈ
						[symbol_right] =>
						[decimal_place] => 2
						[value] => 0.63970000
						[status] => 1
						[date_modified] => 2014-11-28 21:37:48
					)
			)
	*
	*  обязательные только [currency_id] [title] и [code]
	*
	*/

	/*return array(
		'UAH' => array(
			'currency_id' 	=> 1,
			'title' 		=> 'UAH',
			'code' 			=> 'UAH',
			'symbol_left'	=> '',
			'value'			=> 1,
			'status'		=> 1
		)
	);*/

	return array();
}

function cms_getCurrenciesArray(){
	/*
	*  получаем список валют магазина в формате массива:
		Array
			(
				[3] => Array
					(
						[currency_id] => 3
						[title] => Euro
						[code] => EUR
						[symbol_left] =>
						[symbol_right] => €
						[decimal_place] => 2
						[value] => 0.80339998
						[status] => 1
						[date_modified] => 2014-11-28 21:37:48
					)

				[1] => Array
					(
						[currency_id] => 1
						[title] => Pound Sterling
						[code] => GBP
						[symbol_left] => ВЈ
						[symbol_right] =>
						[decimal_place] => 2
						[value] => 0.63970000
						[status] => 1
						[date_modified] => 2014-11-28 21:37:48
					)
			)
	*
	*  обязательные [currency_id] [title] [code] [value]
	*
	*  [value] это дёйствующий курс данной валюты к дефолтной валюте магазине
	*
	*  в случае если курс получается с помощью какого либо веб сервиса, эта функция требует уточнения у меня
	*
	*/

	/*return array(
		'1' => array(
			'currency_id' 	=> 1,
			'title' 		=> 'UAH',
			'code' 			=> 'UAH',
			'symbol_left'	=> '',
			'value'			=> 1,
			'status'		=> 1
		)
	);*/


	 return array();
}

function cms_getLanguages(){
	/*
	*  получаем список валют магазина в формате массива:
	*	Array
			(
				[en] => Array
					(
						[language_id] => 1
						[name] => English
						[code] => en
						[locale] => en_US.UTF-8,en_US,en-gb,english
						[image] => gb.png
						[directory] => english
						[filename] => english
						[sort_order] => 1
						[status] => 1
					)

				[ru] => Array
					(
						[language_id] => 2
						[name] => Русский
						[code] => ru
						[locale] => ru,ru_RU,ru_RU.UTF-8
						[image] => ru.png
						[directory] => russian
						[filename] => russian
						[sort_order] => 1
						[status] => 1
					)

			)
	*
	*  обязательные [language_id] [name] [code]
	*
	*  это список языков для мультиязычного магазина, когда для каждого товара есть описание на разных языках
	*/

	/*return array(
		'ru' => array(
			'language_id' 	=> 1,
			'name'			=> 'Русский',
			'code'			=> 'ru'
		)
	);*/

	return array();
}

function cms_emptyProduct() {
	$product_description = array(
		'name' => '',
		'seo_title' => '',
		'description' => ''
	);

	return array(
		'product_description' => $product_description,
		'post_excerpt' => 'published',
		'comment_status' => 'open',
		'post_type' => 'product',
		'sku' => '',
		'price' => '',
		'weight' => '',
		'length' => '',
		'width' => '',
		'height' => '',
		'product_category' => array(),
		'product_attribute' => array()
	);
}


function cms_insertProduct($product) {
	$ci =& get_instance();
	$ci->load->database();

	$check_sku = $ci->db->query("SELECT post_id FROM ".DB_PREFIX."postmeta WHERE meta_key IN ('_sku', 'iv_sku') AND meta_value = '{$product['sku']}'");

	if ($check_sku->num_rows()) {
		$post_id = $check_sku->result()[0]->post_id;

		$check_id = $ci->db->query("SELECT ID FROM ".DB_PREFIX."posts WHERE ID = $post_id");
		if ($check_id->num_rows()) return false;
	}

	$product_description = $product['product_description'];

	$insert = array(
		'post_author' => 1,
		'post_date' => date('Y-m-d H:i:s'),
		'post_date_gmt' => date('Y-m-d H:i:s'),
		'post_content' => addslashes($product_description['description']),
		'post_title' => addslashes($product_description['name']),
		'post_excerpt' => "",
		'to_ping' => "",
		'pinged' => "",
		'post_content_filtered' => "",
		'post_status' => 'publish',
		'comment_status' => 'open',
		'ping_status' => 'closed',
		'post_name' => addslashes($product['translit_name']),
		'post_modified' => date('Y-m-d H:i:s'),
		'post_modified_gmt' => date('Y-m-d H:i:s'),
		'post_type' => 'product'
	);

	$fields = '`' . implode('`, `', array_keys($insert)) . '`';
	$values = "'" . implode("', '", $insert) . "'";

	$ci->db->query("INSERT INTO " . DB_PREFIX . "posts (" . $fields . ") VALUES (" . $values . ")");

	$product_id = $ci->db->insert_id();

	var_dump( "INSERT INTO " . DB_PREFIX . "posts (" . $fields . ") VALUES (" . $values . ")" );
	var_dump( $product_id );

	$site_url = parse_url(site_url(), PHP_URL_SCHEME) . '://' . parse_url(site_url(), PHP_URL_HOST) . '/';
	$ci->db->query("UPDATE " . DB_PREFIX . "posts SET `guid` = '" . $site_url . "?post_type=product&p=" . $product_id . "' where `ID` = " . $product_id);

	$woocommerce_version = $ci->db->query("SELECT `option_value` FROM " . DB_PREFIX . "options WHERE `option_name` = 'woocommerce_version'")->row_array();

	$insert = array(
		'_edit_last' => 1,
		'_edit_lock' => '',
		'_visibility' => 'visible',
		'_stock_status' => $product['status'] > 0?'instock':'outofstock',
		'_layout' => '',
		'total_sales' => 0,
		'_downloadable' => 'no',
		'_virtual' => 'no',
		'_regular_price' => $product['price'],
		'_price' => isset($product['priceSpecial'])?$product['priceSpecial']:$product['price'],
		'__price' => isset($product['priceSpecial'])?$product['priceSpecial']:$product['price'],
		'_sale_price' => isset($product['priceSpecial'])?$product['priceSpecial']:'',
		'_tax_status' => 'taxable',
		'_tax_class' => '',
		'_purchase_note' => '',
		'_featured' => 'no',
		'_weight' => $product['weight'],
		'_length' => $product['length'],
		'_width' => $product['width'],
		'_height' => $product['height'],
		'_sku' => $product['sku'],
		'_product_attributes' => serialize(array()),
		'_sale_price_dates_from' => '',
		'_sale_price_dates_to' => '',
		'_sold_individually' => '',
		'_manage_stock' => 'yes',
		'_backorders' => 'no',
		'_stock' => $product['status'] > 0?$product['quantity']:0,
		'_upsell_ids' => serialize(array()),
		'_crosssell_ids' => serialize(array()),
		'_product_version' => count($woocommerce_version) > 0 ? $woocommerce_version['option_value'] : '2.4.10',
		'_product_image_gallery' => ''
	);

	foreach ($insert as $meta_key => $meta_value)
		$ci->db->query("INSERT INTO " . DB_PREFIX . "postmeta (`post_id`, `meta_key`, `meta_value`) VALUES (" . $product_id . ", '" . $meta_key . "', '" . $meta_value . "')");


	if (count($product['product_category']) > 0 && strlen(implode(', ', $product['product_category'])) > 0) {
		$query = $ci->db->query("SELECT `term_taxonomy_id`, `term_id` FROM " . DB_PREFIX . "term_taxonomy WHERE `term_id` IN (" . implode(', ', $product['product_category']) . ")")->result_array();
		if (count($query) > 0) {
			$term_taxonomy_id = array();
			foreach ($query as $row) {
				$term_taxonomy_id[$row['term_id']] = $row['term_taxonomy_id'];
			}
			foreach ($product['product_category'] as $category_id)
				$ci->db->query("INSERT INTO " . DB_PREFIX . "term_relationships (`object_id`, `term_taxonomy_id`) VALUES (" . $product_id . ", " . $term_taxonomy_id[$category_id] . ")");
		}
	}

	// VARIATIONS
	// echo '<pre>'.print_r($product['product_option'] , 1).'</pre>';exit;
	if (isset($product['product_option']) && is_array($product['product_option']) && count($product['product_option']) > 0) {
		$simple = $ci->db->query("SELECT term_id FROM " . DB_PREFIX . "terms WHERE `slug` = 'simple'");
		$simple_result = end($simple->result_array());

		$variable = $ci->db->query("SELECT term_id FROM " . DB_PREFIX . "terms WHERE `slug` = 'variable'");
		$variable_result = end($variable->result_array());

		if ($variable->num_rows() > 0) {
			$variable_term_id = $variable_result['term_id'];
			$simple_term_id = $simple_result['term_id'];

			$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = $product_id AND `term_taxonomy_id` = $simple_term_id");
			$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = $product_id AND `term_taxonomy_id` = $variable_term_id");
			$ci->db->query("INSERT INTO " . DB_PREFIX . "term_relationships (object_id, term_taxonomy_id) VALUES ($product_id, $variable_term_id)");
		}

		foreach ($product['product_option'] as $product_varation) {
			$is_variation = $product_varation['name'] == 'Color' || $product_varation['name'] == 'Size' ? 1 : 0;

			if(isset($product_varation['option_name']) && isset($product_varation['product_option_values'])){
				$postmeta[$product_varation['option_name']] = array(
					'name' => $product_varation['name'],
					'value' => $product_varation['product_option_values'],
					'position' => 0,
					'is_visible' => 1,
					'is_variation' => $is_variation,
					'is_taxonomy' => 0
				);
			}
			foreach($product_varation['product_option_values'] as $product_option_value){
				if(isset($product_option_value['option_value_id'])){
					$sql = "INSERT INTO " . DB_PREFIX . "term_relationships SET `object_id` = " . (int)$product_id . ", `term_taxonomy_id` = " . $product_option_value['option_value_id'];
					// echo  $sql . '<br />';
					$ci->db->query($sql);
				}
			}
		}

		$sql = "UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = '" . serialize($postmeta) . "' WHERE `post_id` = " . (int)$product_id . " AND `meta_key` = '_product_attributes'";
		// echo  $sql . '<br />';
		$ci->db->query($sql);
	}
	

	return $product_id;
}


function cms_updateProduct($product , $product_id , $ins){
	// echo '<pre>'.print_r($ins , 1).'</pre>';exit;
	$time_now = date("Y-m-d H:i:s");
	$ci =& get_instance();$ci->load->database();
   
	// UPDATE CATEGORY
	$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = " . $product_id . " AND `term_taxonomy_id` IN (SELECT `term_taxonomy_id` FROM `" . DB_PREFIX . "term_taxonomy` WHERE `taxonomy` = 'product_cat')");
	if (count($product['product_category']) > 0 && strlen(implode(', ', $product['product_category'])) > 0) {
		$query = $ci->db->query("SELECT `term_taxonomy_id`, `term_id` FROM " . DB_PREFIX . "term_taxonomy WHERE `term_id` IN (" . implode(', ', $product['product_category']) . ")")->result_array();
		if (count($query) > 0) {
			$term_taxonomy_id = array();
			foreach ($query as $row) {
				$term_taxonomy_id[$row['term_id']] = $row['term_taxonomy_id'];
			}
			foreach ($product['product_category'] as $category_id)
				$ci->db->query("INSERT INTO " . DB_PREFIX . "term_relationships (`object_id`, `term_taxonomy_id`) VALUES (" . $product_id . ", " . $term_taxonomy_id[$category_id] . ")");
		}
	}
	
	// UPDATE PRICE
	 $priceSpecial = isset($product['priceSpecial'])?$product['priceSpecial']:$product['price'];
	 $salePrice = isset($product['priceSpecial'])?$product['priceSpecial']:'""';
	 $ci->db->query("UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = " . $product['price'] . " WHERE `meta_key` = '_regular_price' AND `post_id` = " . $product_id);
	 $ci->db->query("UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = " . $priceSpecial . " WHERE `meta_key` = '_price' AND `post_id` = " . $product_id);
	 $ci->db->query("UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = " . $priceSpecial . " WHERE `meta_key` = '__price' AND `post_id` = " . $product_id);
	 $ci->db->query("UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = " . $salePrice . " WHERE `meta_key` = '_sale_price' AND `post_id` = " . $product_id);
	
	// UPDATE QUANTITY
	$ci->db->query("UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = " . $product['quantity'] . " WHERE `meta_key` = '_stock' AND `post_id` = " . $product_id);
	
	
	// UPDATE VARIATIONS (if !setup "do not update variations")
	// echo '<pre>'.print_r($product['product_option'] , 1).'</pre>';// exit;
	if ($ins['do_not_update_options'] < 1 && isset($product['product_option']) && is_array($product['product_option']) && count($product['product_option']) > 0) {

		$simple = $ci->db->query("SELECT term_id FROM " . DB_PREFIX . "terms WHERE `slug` = 'simple'");
		$simple_result = end($simple->result_array());

		$variable = $ci->db->query("SELECT term_id FROM " . DB_PREFIX . "terms WHERE `slug` = 'variable'");
		$variable_result = end($variable->result_array());

		if ($variable->num_rows() > 0) {
			$variable_term_id = $variable_result['term_id'];
			$simple_term_id = $simple_result['term_id'];

			$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = $product_id AND `term_taxonomy_id` = $simple_term_id");
			$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = $product_id AND `term_taxonomy_id` = $variable_term_id");
			$ci->db->query("INSERT INTO " . DB_PREFIX . "term_relationships (object_id, term_taxonomy_id) VALUES ($product_id, $variable_term_id)");
		}

		$postmeta = array();
		foreach ($product['product_option'] as $product_varation) {
			if(isset($product_varation['option_name']) && isset($product_varation['product_option_values']) ) {
				$is_variation = $product_varation['name'] == 'Color' || $product_varation['name'] == 'Size' ? 1 : 0;
				$postmeta[$product_varation['option_name']] = array(
					'name' => $product_varation['name'],
					'value' => $product_varation['product_option_values'],
					'position' => 0,
					'is_visible' => 1,
					'is_variation' => $is_variation,
					'is_taxonomy' => 0
				);
			}
			// get all term_taxonomy_ids for this option
			// delete all binds from 
			$sql = "DELETE FROM `" . DB_PREFIX . "term_relationships` WHERE `object_id` = " . (int)$product_id . " 
				AND `term_taxonomy_id` IN (SELECT `term_taxonomy_id` FROM `" . DB_PREFIX . "term_taxonomy` WHERE `taxonomy` = '" . utf8_encode($product_varation['option_name']) ."')";
			// echo  $sql . '<br /><br />';
			$ci->db->query($sql);
			foreach($product_varation['product_option_values'] as $product_option_value){
				if(isset($product_option_value['option_value_id'])){
					$sql = "INSERT INTO " . DB_PREFIX . "term_relationships SET `object_id` = " . (int)$product_id . ", `term_taxonomy_id` = " . $product_option_value['option_value_id'];
					// echo  $sql . '<br /><br />';
					$ci->db->query($sql);
				}
			}
		}
		$sql = "UPDATE " . DB_PREFIX . "postmeta SET `meta_value` = '" . serialize($postmeta) . "' WHERE `post_id` = " . (int)$product_id . " AND `meta_key` = '_product_attributes'";
		// echo  $sql . '<br /><br />';
		$ci->db->query($sql);
	}
	
	// echo '<pre>'.print_r($product , 1).'</pre>';exit;
}


function cms_deleteProduct($product_id){
	$ci =& get_instance();
	$ci->load->database();
	$result = $ci->db->query("SELECT `ID` FROM ". DB_PREFIX. "posts WHERE `post_parent` = " . $product_id);
	$childIds = array();
	if ($result->num_rows()){
		foreach ($result->result() as $row){
			$childIds[] = $row->ID;
		}
	}
	$ci->db->query("DELETE FROM " . DB_PREFIX . "posts WHERE `ID` = " . $product_id);
	$ci->db->query("DELETE FROM " . DB_PREFIX . "posts WHERE `post_parent` = " . $product_id . " AND `post_type` = 'attachment'");
	$ci->db->query("DELETE FROM " . DB_PREFIX . "postmeta WHERE `post_id` = " . $product_id);
	if(count($childIds) > 0){
		$ci->db->query("DELETE FROM " . DB_PREFIX . "postmeta WHERE `post_id` IN (" . implode(",", $childIds) . ")");
	}
	$ci->db->query("DELETE FROM " . DB_PREFIX . "term_relationships WHERE `object_id` = " . $product_id);
}



/*
*  Сохраняем изображение
*/
function cms_saveImage($translit_name , $url ,  $id = -1 , $product_id = false , $descriptionImageID = false, $main = false) {

	$local_add = '';
	if(LOCAL_INSTALLATION_SEMAFOR > 0){ $local_add = 'woocommerce';}
	if(substr($url , 0 , 2) == '//'){ $url = 'http:' . $url;}

	// add $id to $translit_name if NOT descriptionImage and NOT mainImage
	if($descriptionImageID == false && $main == false){ $translit_name .= '-' . $id; }

	$info = pathinfo($url);

	$ext = 'jpg';
	if (strpos($url , ".png") > 0) $ext = 'png';
	if (strpos($url , ".svg") > 0) $ext = 'svg';
	if (strpos($url , ".gif") > 0) $ext = 'gif';
	if (strpos($url , ".tiff") > 0) $ext = 'tiff';

	$MIME = "image/jpeg";

	if ($descriptionImageID == false) {
		$ci =& get_instance();
		$ci->load->database();

		$sql = "INSERT INTO ". DB_PREFIX. "posts SET 
			`post_author` = 1,
			`post_date` = NOW(),
			`post_date_gmt` = NOW(),
			`post_content` = '',
			`post_content_filtered` = '',
			`post_title` = ". $ci->db->escape($translit_name). ",
			`post_status` = 'inherit',
			`comment_status` = 'closed',
			`ping_status` = 'closed',
			`to_ping` = '',
			`pinged` = '',
			`post_excerpt` = 'published',
			`post_name` = ". $ci->db->escape($translit_name). ",
			`post_modified` = NOW(),
			`post_modified_gmt` = NOW(),
			`post_parent` = '". intval($product_id). "',
			`guid` = '',
			`post_type` = 'attachment',
			`post_mime_type` = ". $ci->db->escape($MIME);

		$result = $ci->db->query($sql);
		$img_id = $ci->db->insert_id();
		
		$sql = "INSERT INTO ". DB_PREFIX. "postmeta SET
				`meta_key`= '_wp_attached_file',
				`post_id` = $img_id,
				`meta_value` = " . $ci->db->escape($url);
		//echo $sql . '<br /><br />';
		$ci->db->query($sql);
		
		// IF MAIN IMAGE:
		if($main !== false){
			$sql = "INSERT INTO ". DB_PREFIX. "postmeta SET `post_id` = " . intval($product_id) . ", `meta_key`= '_thumbnail_id', `meta_value` = '" . $img_id . "'";
			//echo $sql . '<br /><br />';
			$ci->db->query($sql);
		}

		return $img_id;

	} else {
		$basePath = '../' . $local_add . cms_getImagesLocation();
		if (!is_dir($basePath)) {
			$res = mkdir($basePath, 0755, true);
			if (!$res) return '';
		}

		$filename = $translit_name . '-details-' . $id . '.' . $ext;
		$contents = getUrl($url, false, false, true);
		file_put_contents($basePath . '/' . $filename , $contents);
		if (strlen($local_add) < 1 && (LOCAL_INSTALLATION_SEMAFOR_WOOCOMMERCE < 1)) {
			return substr( cms_getImagesLocation() , 1 , -1) . '/' . $filename;
		} else {
			return '../..' . cms_getImagesLocation() . '/' . $filename;
		}
	}

	return $filename;
}

function cms_saveGallery($product_id, $galleryArr){
	$ci =& get_instance();$ci->load->database();
	$sql = "UPDATE ". DB_PREFIX. "postmeta SET `meta_value` = '" . implode(',' , $galleryArr) . "' WHERE `meta_key` = '_product_image_gallery' AND `post_id` = " . $product_id;
	//echo $sql . '<br />';
	$ci->db->query($sql);
}

function cms_getImageTypes($set){
	/*
	 * SET1: thumbnail, medium, shop_thumbnail, shop_catalog
	 * SET2: thumbnail, medium, medium_large, large, shop_thumbnail, shop_single, shop_catalog
	 */
	$ci =& get_instance();$ci->load->database();
	$out = array();$arr = array();
	$optionsMetaNames = array('thumbnail_size_w' , 'medium_size_w' , 'medium_large_size_w' , 'large_size_w' , 'shop_thumbnail_image_size' , 'shop_single_image_size' , 'shop_catalog_image_size');
	$initial = array('thumbnail' => 150, 'medium' => 300, 'shop_thumbnail' => 180, 'shop_catalog' => 300 );
	if($set > 1){ $initial['medium_large'] = 768; $initial['large'] = 1024; $initial['shop_single'] = 600; }
	
	$result = $ci->db->query("SELECT * FROM ". DB_PREFIX. "options WHERE `option_name` IN ('" . implode("','" , $optionsMetaNames) . "')");
	if ($result->num_rows()){
		foreach ($result->result() as $row){
			switch($row->option_name){
				case 'thumbnail_size_w': $initial['thumbnail'] = $row->option_value; break;
				case 'medium_size_w': $initial['medium'] = $row->option_value; break;
				case 'medium_large_size_w': if($set > 1){ $initial['medium_large'] = $row->option_value;} break;
				case 'large_size_w': if($set > 1){ $initial['large'] = $row->option_value;} break;
				case 'shop_thumbnail_image_size':
					$tArr = unserialize($row->option_value);if(isset($tArr['width'])){ $initial['shop_thumbnail'] = $tArr['width']; } break;
				case 'shop_single_image_size':
					$tArr = unserialize($row->option_value);if(isset($tArr['width']) && $set > 1){ $initial['shop_single'] = $tArr['width']; } break;
				case 'shop_catalog_image_size':
					$tArr = unserialize($row->option_value);if(isset($tArr['width'])){ $initial['shop_catalog'] = $tArr['width']; } break;  
			}
			// echo $row->option_name . ' ---- ' . $row->option_value . '<br />';
		}
	}
	foreach($initial as $key => $width){ $arr[] = $width;}
	$out = $initial; $out['arr'] = array_unique($arr);
	return $out;
}


function cms_image_resize($source_path, $destination_path, $newwidth, $newheight = FALSE, $quality = FALSE){
	ini_set("gd.jpeg_ignore_warning", 1); // иначе на некотоых jpeg-файлах не работает
	list($oldwidth, $oldheight, $type) = getimagesize($source_path);
	switch ($type) {
		case IMAGETYPE_JPEG: $typestr = 'jpeg'; break;
		case IMAGETYPE_GIF: $typestr = 'gif' ;break;
		case IMAGETYPE_PNG: $typestr = 'png'; break;
	}
	$function = "imagecreatefrom$typestr";
	$src_resource = $function($source_path);

	if (!$newheight) { $newheight = round($newwidth * $oldheight/$oldwidth); }
	elseif (!$newwidth) { $newwidth = round($newheight * $oldwidth/$oldheight); }
	$destination_resource = imagecreatetruecolor($newwidth,$newheight);

	imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);
	if ($type == 2) { # jpeg
		imageinterlace($destination_resource, 1); // чересстрочное формирование изображение
		imagejpeg($destination_resource, $destination_path, $quality);
	}else { # gif, png
		$function = "image$typestr";
		$function($destination_resource, $destination_path);
	}
	imagedestroy($destination_resource);
	imagedestroy($src_resource);
}



/*
 *  получить данные товаров по массиву id
 */
function cms_getProducts($product_ids){
	$ci =& get_instance();$ci->load->database();
	$out = array();
	if(count($product_ids) > 0){
		$result = $ci->db->query("SELECT * FROM ". DB_PREFIX. "posts WHERE `ID` IN ('" . implode("','" , $product_ids) . "')");
		if ($result->num_rows()){
			foreach ($result->result() as $row){
				$prodData = $ci->db->query("SELECT * FROM ". DB_PREFIX. "postmeta WHERE `post_id`  = " . $row->ID . " AND `meta_key` IN ('_stock_status' , '_sale_price' , '_price' , '_stock' , '_thumbnail_id')");
				if ($prodData->num_rows()){
					$tech_arr = array();
					foreach ($prodData->result() as $dataRow){
						$tech_arr[$dataRow->meta_key] = $dataRow->meta_value;
					}
				}
				$arr = array(
					'product_id' => $row->ID,
					'stock_status_id' => isset($tech_arr['_stock_status'])?$tech_arr['_stock_status']:"unknown",
					'quantity' => isset($tech_arr['_stock'])?$tech_arr['_stock']:0,
					'image' => isset($tech_arr['_thumbnail_id'])?$tech_arr['_thumbnail_id']:"",
					'price' => (isset($tech_arr['_sale_price']) && $tech_arr['_sale_price'] > 0)?$tech_arr['_sale_price']:@$tech_arr['_price'],
					'name' => $row->post_title
				);
				$out[$row->ID] = $arr;
			}
		}
	}
	// echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}



function cms_createProductStoreLink($product_id){
	if(LOCAL_INSTALLATION_SEMAFOR > 0){
		return "http://woocommerce/?post_type=product&p=" . $product_id;
	}else{
		return '../?post_type=product&p=' . $product_id;
	}
}


/*
 * формируем название статуса для AJAX таблицы с товарами grabbed
 */
function cms_createStatusById($prod_id){
	switch($prod_id){
		case 'instock': $out = '<span style="color:green;">In Stock</span>';break;
		case 'outofstock': $out = '<span style="color:red;">Out Of Stock</span>';break;
		default: $out = '<span style="color:grey;">UNKNOWN</span>';break;
	}
	return $out;
}

/*
*  формируем путь к превью (тумбе) основной фотки товара
*/
function cms_getImageThumb($thumb_id){
	if($thumb_id > 0){
		$ci =& get_instance();$ci->load->database();
		$base = '../';
		if(LOCAL_INSTALLATION_SEMAFOR > 0){
			$base = 'http://woocommerce/';
		}
		$q = "SELECT * FROM ". DB_PREFIX. "postmeta WHERE `post_id` = " . $thumb_id . " AND `meta_key` = '_wp_attached_file'";
		//echo $q;//exit;
		$result = $ci->db->query($q);
		//var_dump($result);exit;
		if($result){
			$result = $result->row_array();
		}
		if(isset($result['meta_value'])){
			return '<img src="' . $base . 'wp-content/uploads/' . $result['meta_value'] . '" width="40" height="40" />';
		}
	}
	return '';
}



/*
 *  ставим товару с id $product_id статус "Нет в продаже"
 */
function cms_setOutOfStock($product_id){
	$ci =& get_instance();$ci->load->database();
	$ci->db->query("UPDATE ". DB_PREFIX. "postmeta SET `meta_value` = 'outofstock' WHERE `post_id` = " . $product_id . " AND `meta_key` = '_stock_status'");
	$ci->db->query("UPDATE ". DB_PREFIX. "postmeta SET `meta_value` = '0' WHERE `post_id` = " . $product_id . " AND `meta_key` = '_stock'");
}

/*
 *  устанавливаем 0 кол-во этого товару с id $product_id
 */
function cms_setZeroQuantity($product_id){
	$ci =& get_instance();$ci->load->database();
	$ci->db->query("UPDATE ". DB_PREFIX. "postmeta SET `meta_value` = 0 WHERE `post_id` = " . $product_id . " AND `meta_key` = '_stock'");
}

/*
 *  отключаем товар с id $product_id (статус Disabled)
 */
/*function cms_disableProduct($product_id){}*/




/***************************  OPTIONS  ******************************/


function cms_getOption($name , $type){
	/*
	*  возвращаем pa_{NAME} опции по её названию если такая существует, иначе вставляем её и возвращаем pa_{INSERTED_NAME};
	*/
	$ci =& get_instance();$ci->load->database();
	$attr_name = strtolower($name);
	$sql = "SELECT * FROM " . DB_PREFIX . "woocommerce_attribute_taxonomies 
			WHERE `attribute_label` = '" . trim($name) . "' AND `attribute_name` = '" . utf8_encode($attr_name) . "' AND `attribute_type` = 'select' ";
	// echo $sql;exit;
	$query = $ci->db->query($sql)->result_array();
	//echo '<pre>'.print_r($query , 1).'</pre>';exit;
	if (count($query) < 1) {
		$sql= "INSERT INTO " . DB_PREFIX . "woocommerce_attribute_taxonomies (`attribute_name`, `attribute_label` , `attribute_type` , `attribute_orderby` , `attribute_public`) 
					VALUES ('" . $attr_name . "' , '" . $name . "' , 'select' , 'menu_order' , 0)";
		$ci->db->query($sql);
	}
	// echo  "pa_" . $attr_name;exit;
	// return "pa_" . $attr_name;
	return $attr_name;
}


function cms_getOptionValue($option_value_name , $option_name){
	/*
	 * $option_value_name -> title вариации
	 * $option_name -> pa_{NAME} опции
	*  возвращаем term_taxonomy_id значения опции (если такого нет то вставляем), потом оно используется для привязки товара к опции в term_relationships;
	*/
	$ci =& get_instance();$ci->load->database();
	$sql = "SELECT * FROM " . DB_PREFIX . "term_taxonomy tt LEFT JOIN " . DB_PREFIX . "terms t ON tt.term_id = t.term_id 
			WHERE t.name = '" . trim($option_value_name) . "' AND tt.taxonomy = '" . $option_name . "'";
	// echo $sql;
	$query = $ci->db->query($sql)->result_array();
	//echo '<pre>'.print_r($query , 1).'</pre>';exit;
	if (count($query) < 1) {
		$sql= "INSERT INTO " . DB_PREFIX . "terms (`name`, `slug`)
					VALUES ('" . trim($option_value_name) . "' , '" . strtolower(trim($option_value_name)) . "')";
		$ci->db->query($sql);
		$term_id = $ci->db->insert_id();
		$sql= "INSERT INTO " . DB_PREFIX . "term_taxonomy (`term_id`, `taxonomy` , `description`)
					VALUES (" . $term_id . " , '" . $option_name . "' , '" . $option_value_name . "')";
		$ci->db->query($sql);
		$term_taxonomy_id = $ci->db->insert_id();
	}else{
		$term_taxonomy_id = $query[0]['term_taxonomy_id'];
	}
	// echo  $term_taxonomy_id;exit;
	return $term_taxonomy_id;
}


