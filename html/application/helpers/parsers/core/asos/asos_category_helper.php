<?php

function mspro_asos_category_getUrl($url) {
	$agents = array(
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
		'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
		'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);

	return $output;
}

function parse_category($html, $task) {
	return array(
		'products' => parse_products($html, $task),
		'next_page' => parse_next_page($html, $task)
	);
}

function parse_products($html, $task) {
	$result = array();

	$link = 'section article a';
	$parser = new nokogiri($html);
	$links = $parser->get($link)->toArray();

	unset($parser);

	foreach ($links as $value) {
		if ( empty($value['href']) || is_array($value['href']) ) {
			continue;
		}
		if ( strpos($value['href'], '/prd/') === false /*&& strpos($value['href'], '/grp/') === false*/ ) {
			continue;
		}
		$result[] = $value['href'];
	}

	return array_unique($result);
}

function parse_next_page($html , $task) {
	preg_match('/rel="next" href="([^"]+)"/', $html, $matches);

	if ( !empty($matches) && $matches[1] ) {
		return $matches[1];
	}

	return false;
}