<?php

function mspro_asos_getUrl($url) {
	$ip = '127.0.0.1';

	$headers = array(
		'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'accept-language: en-US,en;q=0.9',
		'cache-control: no-cache',
		'pragma: no-cache',
		'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
		'sec-ch-ua-mobile: ?0',
		'sec-fetch-dest: document',
		'sec-fetch-mode: navigate',
		'sec-fetch-site: none',
		'sec-fetch-user: ?1',
		'upgrade-insecure-requests: 1',
		"CLIENT-IP: {$ip}",
		"X-FORWARDED-FOR: {$ip}"
	);

	$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);

	return $output;
}

function mspro_asos_json($html) {
	preg_match_all('/config\.product = (.*);/', $html, $matches);

	if (@$matches[1][0]) {
		$data = json_decode($matches[1][0]);
	}

	return $data;
}

function mspro_asos_title($html) {
	preg_match('/<title>(.*)<\/title>/', $html, $match);

	if ($match[1]) {
		$title = $match[1];
		$title = str_replace(' | ASOS', '', $title);
		$title = str_replace('ASOS DESIGN', '', $title);
		$title = str_replace('ASOS', '', $title);
		$title = str_replace('Topman', 'IVLOO DESIGN', $title);
		$title = str_replace('Topshop', 'IVLOO DESIGN', $title);
		return trim($title);
	}

	return '';
}

function mspro_asos_description($html) {
	return '';
	$pq = phpQuery::newDocumentHTML($html);
	return $pq->find('#product-details-container .col')->html();
}

function mspro_asos_price($html) {
	$json = mspro_asos_json($html);

	$data = mspro_asos_getUrl("https://www.asos.com/api/product/catalogue/v3/stockprice?productIds={$json->id}&store=ROW&currency=USD");

	if ( $data ) {
		$stockprice = json_decode($data);
		if ( count($stockprice) ) {
			$productPrice = $stockprice[0]->productPrice;
			return $productPrice->rrp->value ? $productPrice->rrp->value : $productPrice->current->value;
		}
	}

	return '';
}

function mspro_asos_sku($html) {
	$json = mspro_asos_json($html);
	return $json->id;
}

function mspro_asos_model($html) {
	return '';
}

function mspro_asos_meta_description($html) {
	$res =  explode("<meta name='description' content='" , $html);

	if (count($res) > 1) {
		$res = explode('"' , $res[1]);
		if (count($res) > 1) {
			return clear_asos_meta_tags(str_replace(array("&nbsp;", "&amp;"), array(" ", "`") , $res[0]));
		}
	}

	return '';
}

function mspro_asos_meta_keywords($html) {
	$res = explode("<meta name='keywords' content='", $html);

	if (count($res) > 1) {
		$res = explode('"', $res[1]);
		if (count($res) > 1 && strlen($res[0]) > 2) {
			return clear_asos_meta_tags(str_replace(array("&nbsp;" , "&amp;"), array(" ", "`") , $res[0]));
		}
	}

	return mspro_asos_meta_description($html);
}

function clear_asos_meta_tags($str) {
	return str_ireplace(array('asos.com', 'asos'), array('', '') , $str);
}

function mspro_asos_main_image($html) {
	$arr = asos_get_images($html);
	if (isset($arr[0]) && strlen($arr[0]) > 0) {
		return $arr[0];
	}
	return '';
}

function mspro_asos_other_images($html) {
	$arr = asos_get_images($html);
	if (count($arr) > 1) {
		unset($arr[0]);
		return $arr;
	}
	return array();
}

function asos_get_images($html) {
	$out = array();

	$pq = phpQuery::newDocument($html);
	$images = $pq->find('.thumbnails img');

	foreach ($images as $image) {
		$out[] = $image->getAttribute('src');
	}

	return $out;
}

function mspro_asos_options($html) {
	$out = array();

	$pq = phpQuery::newDocumentHTML($html);
	$json = mspro_asos_json($html);

	foreach ($json->variants as $variant) {
		$out['Size']['name'] = 'Size';
		$out['Size']['type'] = 'select';
		$out['Size']['values'][] = $variant->size;
	}

	$size_fit = $pq->find('#product-details-container .size-and-fit p')->text();
	$size_fit = str_replace("'", "’", $size_fit);
	$size_fit = str_replace("Model’s", "</br>Model’s", $size_fit);

	$care= $pq->find('#product-details-container .care-info p')->text();

	$about = [];
	$aboutme = $pq->find('#product-details-container .about-me')->html();
	$aboutcontent = explode('</h2>', $aboutme)[1];
	$aboutcontents = strip_tags($aboutcontent);

	foreach( explode("\n", $aboutcontents) as $part ) {
		$trimmed = trim($part);
		if ( empty($trimmed) ) continue;
		$about[] = str_replace("'", "’", $trimmed);
	}

	if ( !empty($size_fit) ) {
		$out['Size & Fit'] = array(
			'name' => 'Size & Fit',
			'type' => 'select',
			'values' => explode( '</br>', $size_fit )
		);
	}

	if ( !empty($care) ) {
		$out['Care'] = array(
			'name' => 'Care',
			'type' => 'select',
			'values' => [ $care ]
		);
	}

	if ( !empty($about) ) {
		$out['About Me'] = array(
			'name' => 'About Me',
			'type' => 'select',
			'values' => $about
		);
	}

	return $out;
}

function mspro_asos_noMoreAvailable($html) {
	return false;
}

function mspro_asos_finished($html, $product, $url, $new) {
	if ($product <= 0) return;

	$payload = http_build_query(array(
		'product' => $product
	));

	$request = curl_init();
	curl_setopt($request, CURLOPT_URL, 'https://ivloo.com/wp-json/scraper/update-variations');
	curl_setopt($request, CURLOPT_POST, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_exec($request);
	curl_close($request);
}
