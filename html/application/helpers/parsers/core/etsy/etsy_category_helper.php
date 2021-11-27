<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.block-grid-item a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'listing/') > 0 && stripos($value['href'] , 'similar?') < 1 ){
            		$result[] = $value['href'];
            	}
                
            }
        }
        
        
        /*if(count($result) < 1){
            $link = 'div.product_listing_outer a.prodLink';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) ){
                        $result[] = $value['href'];
                    }
            
                }
            }
        }*/
        
        if(count($result) < 1){
            $link = 'ul#reorderable-listing-results li.block-grid-item a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'ef=more_like_th') < 1){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        
        if(count($result) < 1){
            $link = 'ul.responsive-listing-grid li a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'ef=more_like_th') < 1){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'li.block-grid-item a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'ef=more_like_th') < 1){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        
        $result = array_unique($result);
        // echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
    
    $res = explode('link rel="next" href="' , $html);
    if(count($res) > 1){
        $res_t = explode('"' , $res[1] , 2);
        if(count($res_t) > 1){
            return $res_t[0];
        }
    }
    $res = explode('<meta property="og:title" content="' , $html);
    if(count($res) > 1){
        $res_t = explode('"' , $res[1] , 2);
        if(count($res_t) > 1){
            return $res_t[0];
        }
    }
    
    // echo $html;exit;
    $nextPage = 'nav.pagination a:last';
    $parser = new nokogiri($html);
    $next = $parser->get($nextPage)->toArray();
    //echo '<pre>'.print_r($next , 1).'</pre>';exit;
    $next = array_pop($next);
    //echo '<pre>'.print_r($next , 1).'</pre>';exit;
    if (isset($next['href'])){
        return trim($next['href']);
    }
    
    $nextPage = 'nav[role=navigation] a:last';
    $parser = new nokogiri($html);
    $next = $parser->get($nextPage)->toArray();
    //echo '<pre>'.print_r($next , 1).'</pre>';exit;
    $next = array_pop($next);
    // echo '<pre>'.print_r($next , 1).'</pre>';exit;
    if (isset($next['href'])){
        return trim($next['href']);
    }
    
        $nextPage = 'a.btn-icon';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        $next = array_pop($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	return trim($next['href']);
        }

        return false;
}