<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'li.searchResultItem a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href']) && strlen($value['href']) > 0 && stripos($value['href'] , 'customer-reviews') < 1){
            	    $out = $value['href'];
            	    if(stripos($out , 'geekbuying.c') < 1){
            	        $out = 'http://www.geekbuying.com/' . $out;
            	    }
            	    $result[] =  $out;
            	}
            }
        }    
        
        
        $result = array_unique($result);
        // echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}





function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
        if (isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next_link){
                if(isset($pos_next_link['href']) && !is_array($pos_next_link['href']) && isset($pos_next_link['title']) && trim($pos_next_link['title']) == "next"){
                    $result = trim($pos_next_link['href']);
                    if(stripos($result , 'geekbuying.c') < 1){
                        $result = 'http://www.geekbuying.com/' . $result;
                    }
                    //echo $result;exit;
                    return $result;
                }
            }
        }
        return false;
}


