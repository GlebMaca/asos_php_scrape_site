<?php


function mspro_lazada_category_getUrl($url){
    
    // echo 'URL:' . $url . '<br />';
    
    require_once "support/web_browser.php";
    require_once "support/tag_filter.php";
    
    $htmloptions = TagFilter::GetHTMLOptions();
    $web = new WebBrowser();
    $result = $web->Process($url);
    
    // Check for connectivity and response errors.
    if (!$result["success"])
    {
        echo "Error retrieving URL.  " . $result["error"] . "\n";
        exit();
    }
    
    if ($result["response"]["code"] != 200)
    {
        echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
        exit();
    }
    //echo $result["body"];
    return $result["body"];
}


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
   // echo $task['url'] . '<br />';
	 	$result = array();
	 	//echo $html;
	 	
	 	
	 	
	 if(substr(trim($html) , 0 , 1)  == "{"){
	     
	     $res = (array) json_decode('[' . $html . ']' , 1);
	     // echo '<pre>'.print_r($res , 1).'</pre>';exit;
	     
	     if(isset($res[0]['mods']['listItems']) && is_array($res[0]['mods']['listItems']) && count($res[0]['mods']['listItems']) > 0){
	         foreach($res[0]['mods']['listItems'] as $pos_product){
	             if(isset($pos_product['productUrl'])){
	                 $result[] = 'https:' . $pos_product['productUrl'];
 	             }
	         }
	     }
	     
	     
	 }else{
    	 	$domain = parse_domain($task['url']);
    	 
    		$link = 'div.component-product_list a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                	if(isset($link['href'])){
                		 $result[] = $link['href'];
                	}
                }
            }

            if(count($result) < 1){
                $link = 'div.c-product-list div.c-product-card__gallery-img-wrapper';
                $parser = new nokogiri($html);
                $links = $parser->get($link)->toArray();
                //echo '<pre>'.print_r($links , 1).'</pre>';exit;
                unset($parser);
                if (isset($links) && is_array($links) && count($links) > 0) {
                    foreach ($links as $link) {
                        if(isset($link['data-group'])){
                            $t_res = explode('"url": "' , $link['data-group']);
                            if(count($t_res) > 1){
                                $t_res = explode('"' , $t_res[1] , 2);
                                if(count($t_res) > 1){
                                    $result[] = $domain. $t_res[0];
                                }
                            }
                        }
                    }
                }
            }
            
            if(count($result) < 1){
                $link = 'div.c-product-list a';
                $parser = new nokogiri($html);
                $links = $parser->get($link)->toArray();
                //echo '<pre>'.print_r($links , 1).'</pre>';exit;
                unset($parser);
                if (isset($links) && is_array($links) && count($links) > 0) {
                    foreach ($links as $link) {
                        if(isset($link['href'])){
                            $result[] = $domain . $link['href'];
                        }
                    }
                }
            }
            
            if(count($result) < 1){
                $tres = explode('],"productUrl":"//' , $html);
                if(count($tres) > 1){
                    unset($tres[0]);
                    foreach($tres as $p_block){
                        $ttres = explode('"' , $p_block , 2);
                        $result[] = 'https://' . $ttres[0];
                    }
                }
            }

	 }
	 
	 $result = array_unique($result);
	 // echo '<pre>'.print_r($result , 1).'</pre>';exit;
	 return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next_link';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	if(stripos($next['href'] , 'lazada.') < 1){
        	    $domain = parse_domain($task['url']);
		    	$next['href'] = $domain . $next['href'];
		    }
		    return $next['href'];
        }
        
        $nextPage = 'a.c-paging__next-link';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next[0]['href'])){
            return $next[0]['href'];
        }
        
        
        $tres = explode('page=' , $task['url']);
        if(count($tres) > 1){
            // OTHER PAGES
            $ttres =  explode('&' , $tres[1]);
            $number = (int) ((int) $ttres[0]  + 1);
            return $tres[0] . 'page=' . $number . '&' . $ttres[1];
        }else{
            // FIRST PAGE
            $res = explode('<link rel="next" href="' , $html);
            if(count($res) > 1){
                $res = explode('"' , $res[1] , 2);
                //echo $tres[0];exit;
                return $res[0] . '&ajax=true';
            }
        }

        return false;
}


function parse_domain($url){
    $res = @parse_url($url , PHP_URL_HOST);
    if(isset($res) && is_string($res) && strlen(trim($res)) > 1){
        return 'http://' . $res;
    }
    return 'http://www.lazada.com.my';
}