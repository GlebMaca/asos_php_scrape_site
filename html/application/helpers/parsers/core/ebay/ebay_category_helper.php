<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'a.vi-url';
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
        
         if (count($links) < 1) {
         	$link = 'div#ResultSetItems h3 a';
	        $parser = new nokogiri($html);
	        $links = $parser->get($link)->toArray();
	        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
	        unset($parser);
         }
         
         
         // FOR LINKS LIKE THIS: http://www.ebay.in/cat/mobiles/operatingSystem/Android
         if (count($links) < 1) {
             $link = 'div#itmCont span a';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             //echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                            $result[] = $value['href'];
                         }else{
                            $result[] = $domain . $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }
         // echo $html;
         
         if (count($result) < 1) {
             $link = 'ul#ListViewInner a';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             // echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                             $result[] = $value['href'];
                         }else{
                             $result[] = $domain . $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }
         
         /*if (count($result) < 1) {
             $link = 'ul.srp-results a';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             //echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                             $result[] = $value['href'];
                         }else{
                             $result[] = $domain . $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }*/
         
         /*if (count($result) < 1) {
             $link = 'ul.srp-list a';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                             $result[] = $value['href'];
                         }else{
                             $result[] = $domain . $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }*/
         
         if (count($result) < 1) {
             $link = 'a.s-item__link';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             // echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                             $result[] = $value['href'];
                         }else{
                             $result[] = $domain . $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }
          
         
         if (count($result) < 1) {
             $link = 'table.grid a[itemprop=name]';
             $parser = new nokogiri($html);
             $links = $parser->get($link)->toArray();
             // echo '<pre>'.print_r($links , 1).'</pre>';exit;
             unset($parser);
             if (isset($links) && is_array($links) && count($links) > 0) {
                 $domain = ebay_get_domain_from_task($task['url']);
                 foreach ($links as $value) {
                     if(strpos($value['href'] , "avascript:") < 1){
                         if(strpos($value['href'] , 'ebay.') > 0){
                             $result[] = $value['href'];
                         }
                     }
                 }
             }
             unset($links);
         }
         

        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(strpos($value['href'] , "avascript:") < 1){
                    $result[] = $value['href'];
                }
            }
        }
        
        $result = array_unique($result);
       // echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
    // echo $html;exit;
        $nextPage = 'a.nextBtn';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	return $next['href'];
        }
        
        $nextPage = 'a[rel=next]';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
            return $next['href'];
        }
        
        
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
            return $next['href'];
        }
        
        $nextPage = 'td.pagn-next a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
            return $next['href'];
        }
        
        $nextPage = 'td.next a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
       // echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
            if(stripos($next['href'] , 'ebaystores') < 1 && stripos($next['href'] , 'ebay.co') < 1){
                if(stripos($task['url'] , 'ebaystores') > 0){
                    return 'http://www.ebaystores.com/' . $next['href'];
                }elseif(stripos($task['url'] , 'ebay.co') > 0){
                    return 'https://www.ebay.com/' . $next['href'];
                }
            }else{
                return $next['href'];
            }
        }

        return false;
}


function ebay_get_domain_from_task($url){
        $res = parse_url($url , PHP_URL_HOST);
        if($res){
            return 'http://' . $res;
        }
        return 'http://www.ebay.com';
}