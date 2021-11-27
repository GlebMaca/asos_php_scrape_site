<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div#list_content ul.infobox li.proName a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        
         if (count($links) < 1) {
         	$link = 'div.items div.itembox ul li.proImg a';
	        $parser = new nokogiri($html);
	        $links = $parser->get($link)->toArray();
	        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
	        unset($parser);
         }
        //echo count($links);exit;

        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(strpos($value['href'], 'ocalprice') < 1){
            		$res_t = explode("focalprice" , $task['url']);
            		$value['href'] = $res_t[0].'focalprice.com/'.trim($value['href']);
            	}
                $result[] = $value['href'];
            }
        }
        
        
        if (count($links) < 1) {
            $link = 'ul.product_list li a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (is_array($links) && count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'cart?add') < 1 && trim($value['href']) !== '#'){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        

        $result = array_unique($result);
        if(count($result) > 0){
            foreach($result as $key => $res){
                if(substr($res , 0, 2) == "//"){
                    $result[$key] = 'https:' . $res;
                }
            }
        }
        
        
        
        
        // echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
        $out = false;
        
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	if(strpos($next['href'] , "focalprice") > 0){
        		$out = trim($next['href']);
        	}else{
        		$res_t = explode("focalprice" , $task['url']);
        		$out = $res_t[0].'focalprice.com'.trim($next['href']);
        	}
        }
        
        if(substr($out , 0, 2) == "//"){
           $out = 'https:' . $out;
        }
        
        if(strlen($out) < 2 || $out == false){
            $nextPage = 'a[rel=next]';
            $parser = new nokogiri($html);
            $next = $parser->get($nextPage)->toArray();
            $next = reset($next);
            unset($parser);
            //echo '<pre>'.print_r($next , 1).'</pre>';exit;
            if (isset($next['href'])){
                if(strpos($next['href'] , "focalprice") > 0){
                    $out = trim($next['href']);
                }else{
                    $res_t = explode("focalprice" , $task['url']);
                    $out = $res_t[0].'focalprice.com'.trim($next['href']);
                }
            }
        }
        
        // echo 'OUT:' . $out;exit;
        
        return $out;
}