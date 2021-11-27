<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	//echo $html;exit;
		$out = array();
	
		$link = 'div.grid li.name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
		$link = 'div.product_grid dd.name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
        $link = 'span.title a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
		unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
        $link = 'div.product_gallery ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
                    $out[] = $value['href'];
                }
            }
        }
        
        $out = array_unique($out);
        foreach($out as $k => $v){
            if(stripos($v , 'vascript:') > 0){
                unset($out[$k]);
            }
        }
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}


function parse_next_page($html , $task){
        $nextPage = 'div.page_num a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if(isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next){
                if (isset($pos_next['href']) && isset($pos_next['title']) && (trim($pos_next['title']) == 'Next page' || (isset($pos_next['span']['class']) && trim($pos_next['span']['class']) == "arrow_d") ) ){
                    $result = trim($pos_next['href']);
                    if(stripos($result , 'vascript:') < 1){
                        return $result;
                    }
                }
            }
        }
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href']) && stripos($next['href'] , 'vascript:') < 1 && $next['href'] !== '#'){
            $result = trim($next['href']);
            //echo '2:' . $result;exit;
            return $result;
        }     

        return false;
}