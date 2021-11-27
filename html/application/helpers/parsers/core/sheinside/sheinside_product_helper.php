<?php

function mspro_sheinside_json($html) {
	preg_match_all('/goodsInfo: (.*)/', $html, $matches);

	if (@$matches[1][0]) {
		$data = json_decode($matches[1][0]);
		if ($data == null) $data = json_decode(substr($matches[1][0], 0, -2));
	} else {
		preg_match_all('/productIntroData: (.*)/', $html, $matches);
		$data = json_decode($matches[1][0]);
		if ($data == null) $data = json_decode(substr($matches[1][0], 0, -1));
	}

	return $data;
}

function mspro_sheinside_title($html){
	preg_match('/<title>(.*)<\/title>/', $html, $match);
	if ($match[1]) {
		return str_replace(' | SHEIN', '', $match[1]);
	}

	return '';
}

function mspro_sheinside_description($html){
		$out = '';
		
		$res = explode('<div class="description ' , $html);
		if(count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$t_res = explode('<div class="000">' , $block , 2);
				if(count($t_res) > 1){
					$block = $t_res[0];
				}
				//echo $block;
				if(stripos($block , '">Description<') > 0 || stripos($block , '>model Measurements<') > 0){
					$out .= '<div class="' . $block;
				}
			}
		}
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.ItemSpecificationCenter');
		foreach ($temp as $block){
			$out .= '<div>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('div.goods_att_content');
		foreach ($temp as $block){
			$out .= '<div>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('div.j-desc-con div.kv');
		foreach ($temp as $block){
			$out .= '<div><h2>Descrption</h2>' . $temp->html() . '</div>';
		}
		
		$out = str_ireplace(array('class="kv-row">' , 'class="key" ' , 'class="val"') , array('style="display:table-row;">',  'style="display:table-cell;" ' , 'style="display:table-cell;" ') , $out);
		$out = str_ireplace(array('<span class="vis iconfont">&#xe644;</span>' , '<span class="hid iconfont">&#xe643;</span>' , '<div class="blank12"></div>') , array("") , $out);
		
		//echo $out;exit;
		
		return $out;
}

function mspro_sheinside_price($html){
	$json = mspro_sheinside_json($html);
	return $json->detail->retailPrice->usdAmount;
}

function mspro_sheinside_sku($html){
	$json = mspro_sheinside_json($html);
	return $json->detail->goods_sn;
}

function mspro_sheinside_model($html){
	$json = mspro_sheinside_json($html);
	return $json->detail->goods_sn;
}

function mspro_sheinside_meta_description($html){
	$res =  explode("<meta name='description' content='" , $html);
	if (count($res) > 1) {
		$res = explode('"' , $res[1]);
		if (count($res) > 1) {
			return clear_sheinside_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
		}
	}

	return '';
}

function mspro_sheinside_meta_keywords($html){
	$res = explode("<meta name='keywords' content='" , $html);
	if (count($res) > 1) {
		$res = explode('"' , $res[1]);
		if (count($res) > 1 && strlen($res[0]) > 2) {
			return clear_sheinside_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
		}
	}

	return mspro_sheinside_meta_description($html);
}

function clear_sheinside_meta_tags($str){
	return str_ireplace(array("sheinside.com" , "shein.com" , "sheinside" , "sheinside" , "Free Shipping") , array("" , "") , $str);
}

function mspro_sheinside_main_image($html){
	$arr = sheinside_get_images($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}

function mspro_sheinside_other_images($html){
	$arr = sheinside_get_images($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}

function sheinside_get_images($html){
	$json = mspro_sheinside_json($html);
	$images = $json->goods_imgs;
	$out = array($images->main_image->origin_image);
	foreach ($images->detail_image as $img) $out[] = $img->origin_image;
	return $out;
}

function mspro_sheinside_options($html){
	$out = array();

	$data = mspro_sheinside_json($html);
	if (!$data) return $out;

	$products = array_merge(array($data->detail), $data->relation_color);

	$attributes = array();

	$allow = array('Color', 'Size', 'Style', 'Fit Type', 'Waistline', 'Material', 'Fabric', 'Composition', 'Season');

	foreach ($products as $variation) {
		$atts = array();

		foreach ($variation->productDetails as $attr) {
			if (!in_array($attr->attr_name, $allow)) continue;
			$atts[$attr->attr_name][] = $attr->attr_value_en;
		}

		foreach ($atts as $name => $atts_item) {
			$atts[$name] = implode(', ', $atts_item);
			$attributes[$name]['name'] = $name;
			$attributes[$name]['type'] = 'select';
			$attributes[$name]['values'][] = implode(', ', $atts_item);
		}
	}

	if ($data->attrSizeList) foreach ($data->attrSizeList as $attr) {
		$attributes[$attr->attr_name]['name'] = $attr->attr_name;
		$attributes[$attr->attr_name]['type'] = 'select';
		$attributes[$attr->attr_name]['values'][] = $attr->attr_value_en;
	}

	foreach ($attributes as $attribute) {
		if ($attribute['name'] != 'Color') {
			$attribute['values'] = array_unique($attribute['values']);
		}
		$out[] = $attribute;
	}

	return $out;
}

function mspro_sheinside_noMoreAvailable($html) {
	preg_match('/-p-([\d]+)/', $html, $matches);

	if ($matches[1]) {
		$url = 'https://ar.shein.com/product/isOnSaleAndStock?id=' . $matches[1];
		$res = file_get_contents($url);
		$data = json_decode($res);
		if ($data->info->stock == 0) return true;
	}

	return false;
}

function mspro_sheinside_finished($html, $product, $url, $new) {
	if ($product <= 0) return;

	$ci =& get_instance();
	$ci->load->database();
	$dbprefix = DB_PREFIX;

	$data = mspro_sheinside_json($html);
	if (!$data) return;

	$products = array_merge(array($data->detail), $data->relation_color);

	$sizeinfo = json_encode(array(
		'localsize' => $data->multiLocalSize->size_rule_list,
		'sizeinfo' => $data->sizeInfoDes
	));

	$spu = $data->detail->productRelationID;

	$ci->db->query("DELETE FROM {$dbprefix}postmeta WHERE meta_key = 'shein_url' AND post_id = $product");
	$ci->db->query("INSERT INTO {$dbprefix}postmeta (post_id, meta_key, meta_value) VALUES ($product, 'shein_url', '$url')");

	$url = str_replace('www.shein', 'ar.shein', $url);

	$variations = array();

	foreach ($products as $variation) {
		$name = array();
		foreach ($variation->productDetails as $attr) {
			if ($attr->attr_id == 27) $name[] = $attr->attr_value_en;
		}

		$varianturl = preg_replace('/-p-\d+/', "-p-{$variation->goods_id}", $url);
		if ($varianturl == $url) {
			$variantdata = $data;
		} else {
			$variantdata = mspro_sheinside_json(file_get_contents($varianturl));
		}

		$sizes = array();
		foreach ($variantdata->attrSizeList as $size) {
			$sizes[$size->attr_value] = $size->stock;
		}

		$variations[implode(', ', $name)] = array(
			'sku' => $variation->goods_sn,
			// 'img' => cms_saveImage($variation->color_image),
			'product_img' => cms_saveImage($variation->original_img),
			'sizes' => $sizes
		);
	}

	$payload = http_build_query(array(
		'product' => $product,
		'variations' => $variations
	));

	$request = curl_init();
	curl_setopt($request, CURLOPT_URL, 'https://ivloo.com/wp-json/scraper/update-variations');
	curl_setopt($request, CURLOPT_POST, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_exec($request);
	curl_close($request);

	if (!$new) return;

	$modelinfo = json_encode($data->model);

	$ci->db->query("DELETE FROM {$dbprefix}postmeta WHERE meta_key IN ('sizeinfo', 'modelinfo') AND post_id = $product");
	$ci->db->query("INSERT INTO {$dbprefix}postmeta (post_id, meta_key, meta_value) VALUES
		($product, 'sizeinfo', '$sizeinfo'),
		($product, 'modelinfo', '$modelinfo')");

	$limit = rand(1005, 1195);
	$data = file_get_contents("https://www.shein.com/product/getCommentByAbc?spu={$spu}&limit={$limit}");
	$reviews = json_decode($data)->info->commentInfo;

	if ($reviews) {
		$ci->db->query("DELETE FROM {$dbprefix}commentmeta WHERE comment_id IN (SELECT comment_ID FROM wpz1_comments WHERE comment_post_ID = $product)");
		$ci->db->query("DELETE FROM {$dbprefix}comments WHERE comment_post_ID = $product");

		$ci->db->query("DELETE FROM {$dbprefix}postmeta WHERE post_id = $product AND meta_key IN ('_wc_review_count', '_wc_rating_count', '_wc_average_rating')");

		$count = 0;
		foreach ($reviews as $review) {
			if (empty($review->comment_image)) continue;

			$count++;

			$content = str_replace("'", "\'", $review->content);
			$content = $ci->db->_escape_str($content);

			$ci->db->query("INSERT INTO {$dbprefix}comments (comment_post_ID, comment_author, comment_author_email, comment_date, comment_date_gmt, comment_content, comment_karma, comment_approved, comment_agent, comment_type, comment_parent, user_id) VALUES ($product, '$review->user_name', '', '$review->comment_time', '$review->comment_time', '$content', 0, 1, '', 'review', 0, 0)");

			$comment = $ci->db->insert_id();

			$images = [];
			foreach ($review->comment_image as $image) {
				$images[] = "https://img.shein.com/$image->member_image_middle";
			}
			$images = serialize($images);

			$ci->db->query("INSERT INTO {$dbprefix}commentmeta (comment_id, meta_key, meta_value) VALUES
				($comment, 'rating', '$review->comment_rank'),
				($comment, 'verified', 1),
				($comment, 'images', '$images')");
		}

		if ($count > 0) {
			$rating_count = serialize(array('5' => $count));

			$ci->db->query("INSERT INTO {$dbprefix}postmeta (post_id, meta_key, meta_value) VALUES
				($product, '_wc_review_count', '$count'),
				($product, '_wc_rating_count', '$rating_count'),
				($product, '_wc_average_rating', '5')");
		}
	}
}