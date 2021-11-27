<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
    //echo $html;exit;
	  	$result = array();
	  
		$link = 'div.prodLists ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(strpos($value['href'], 'item') > 0){
	            	if(strpos($value['href'], 'buyincoins') < 1){
	            		$value['href'] = 'http://buyincoins.com/' . trim($value['href']);
	            	}
	                $result[] = $value['href'];
            	}
            }
        }

        $link = 'div.ull ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
                if(strpos($value['href'], 'item') > 0){
                    if(strpos($value['href'], 'buyincoins') < 1){
                        $value['href'] = 'https://buyincoins.com/' . trim($value['href']);
                    }
                    $result[] = $value['href'];
                }
            }
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'li.next a';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		// echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	if(strpos($next['href'], 'buyincoins') < 1){
	        	return 'https://buyincoins.com/' . trim($next['href']);
	        }
        	return trim($next['href']);
        }
        unset($parser);

        return false;
}