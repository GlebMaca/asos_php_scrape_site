<?php


/*
function mspro_aliexpress_category_getUrl($url){
    return getUrl($url , false, true, false);
}
*/

function mspro_aliexpress_category_getUrl($url){
    //echo $url;exit;
     require_once "support/web_browser.php";
    require_once "support/tag_filter.php";

    // Retrieve the standard HTML parsing array for later use.
    $htmloptions = TagFilter::GetHTMLOptions();
     // echo '1';
    // Retrieve a URL (emulating Firefox by default).
    // $url = "https://www.aliexpress.com/af/category/202003451.html?d=n&catName=tops-tees&CatId=202003451&origin=n&spm=a2g0v.best.101.4.10df4c65EaF98b&isViewCP=y&jump=afs&switch_new_app=y";
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


    if(isset($result['body']) && isset($result['success']) && $result['success'] == 1){
        return $result['body'];
    }
    exit;
    return 'LIB ULTIMATE UNABLE TO GET THIS URL';
}


function parse_category($html  , $task){
    //echo 'HTML:' . $html;// exit;
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	  //echo "HTML:" . $html;// exit;
    	$link = 'ul#list-items li.detail h3 a';
        $parser = new nokogiri($html);
        $result = array();
        $links = $parser->get($link)->toArray();
        unset($parser);
        
         if (count($links) < 1) {
         	$link = 'ul#list-items li .detail h2 a';
	        $parser = new nokogiri($html);
	        $links = $parser->get($link)->toArray();
	        unset($parser);
	        
	        if(count($links) < 1){
	        	$link = 'div.info h3 a.product';
		        $parser = new nokogiri($html);
		        $links = $parser->get($link)->toArray();
		        unset($parser);
		        
		        if(count($links) < 1){
		        	$link = 'div.info h3 a.history-item';
			        $parser = new nokogiri($html);
			        $links = $parser->get($link)->toArray();
			        unset($parser);
			        
			        if(count($links) < 1){
			        	$link = 'div.detail h3 a';
				        $parser = new nokogiri($html);
				        $links = $parser->get($link)->toArray();
				        unset($parser);
				        
				        if(count($links) < 1){
				            $link = 'ul#list-items li.list-item h3 a';
				            $parser = new nokogiri($html);
				            $links = $parser->get($link)->toArray();
				            unset($parser);
				            
				            if(count($links) < 1){
				                $link = 'ul#hs-below-list-items li a';
				                $parser = new nokogiri($html);
				                $links = $parser->get($link)->toArray();
				                unset($parser);
				                
				                if(count($links) < 1){
				                    $link = 'ul.list-items li a';
				                    $parser = new nokogiri($html);
				                    $links = $parser->get($link)->toArray();
				                    unset($parser);
				                    
				                    if(count($links) < 1){
				                        $link = 'div.product-lists ul li a';
				                        $parser = new nokogiri($html);
				                        $links = $parser->get($link)->toArray();
				                        unset($parser);
				                        
				                        if(count($links) < 1){
				                            $link = 'ul.items-list li a';
				                            $parser = new nokogiri($html);
				                            $links = $parser->get($link)->toArray();
				                            unset($parser);
				                        }
				                    }
				                }
				            }
				        }
			        }
		        }
	        }
         }
         if (count($links) > 0) {
             foreach ($links as $value) {
                 if(isset($value['href'])){
                     $r = $value['href'];
                     if(substr($r , 0 , 2) == "//"){
                         $r = 'https:' . $r;
                     }
                     $result[] = $r;
                 }
             }
         }
        //echo count($links);exit;

         // TRY JSON
        if (count($result) < 1) {
            $res = explode('window.runParams = {' , $html);
            if(count($res) > 1){
                unset($res[0]);
                foreach($res as $bl){
                    $tres = explode('};' , $bl , 2);
                    if(count($tres) > 1 && stripos($tres[0] , 'productDetailUrl') > 0){
                        $block = '[{' . $tres[0] . '}]';
                        $data =  (array) json_decode($block , 1);
                        //echo '<pre>' . print_r($data , 1) . '</pre>';//exit;
                        //echo $block;
                        if(isset($data[0]['items']) && is_array($data[0]['items']) && count($data[0]['items']) > 0){
                            foreach($data[0]['items'] as $pr){
                                if(isset($pr['productDetailUrl'])){
                                    $result[] = $pr['productDetailUrl'];
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }
        
        // TRY JSON for NEXT PAGES AJAX
        if (count($result) < 1) {
            //echo '1';
            $res = explode('","productDetailUrl":"' , $html);
            if(count( explode('","productDetailUrl":"' , $html)) > 2 || count( explode('&quot;,&quot;productDetailUrl&quot;' , $html)) > 2){
               // echo '2';
                $block = '[' . htmlspecialchars_decode($html) . ']';
                $data =  (array) json_decode($block , 1);
                //echo '<pre>DATA:' . print_r($data , 1) . '</pre>';//exit;
                //echo $block;
                if(isset($data[0]['items']) && is_array($data[0]['items']) && count($data[0]['items']) > 0){
                    foreach($data[0]['items'] as $pr){
                        if(isset($pr['productDetailUrl'])){
                            $result[] = $pr['productDetailUrl'];
                        }
                    }
                }
            }
        }
        
        
        // TRY SIMPLY PARSE BY productDetailUrl
        if (count($result) < 1) {
            //echo '1';
            $res = explode('","productDetailUrl":"' , $html);
            if(count($res) > 1){
                // echo '2';
                unset($res[0]);
                foreach($res as $block){
                    $tres = explode('.html' , $block , 2);
                    if(count($tres) > 1){
                        $result[] = $tres[0] . '.html';
                    }
                }
            }
        }
        
        
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                if(substr($value , 0 , 2) == "//"){
                    $result[$key] = 'https:' . $value;
                }
            }
        }
        
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'div.ui-pagination-navi a.ui-pagination-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href']) && strlen($next['href']) > 10){
        	return aliexpress_prepare_next_page(trim($next['href']));
        }
        unset($parser);
    	
    	//echo '1';
    	
    	$nextPage = 'div.pagination a.page-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href']) && strlen($next['href']) > 10){
        	return aliexpress_prepare_next_page(trim($next['href']));
        }
        
        //echo '2';
        //echo $html;//exit;
    	
        $nextPage = 'span#new-list-pg a.pg-next-btn';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['class']) && strpos($next['class'], 'pg-next-btn-disable') === false){ 
            return aliexpress_prepare_next_page(trim($next['href']) );
        }
        
        //echo '3';
        //echo $html;exit;
        
       
        $nextPage = 'a.page-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        unset($parser);
        if (isset($next['class']) && strpos($next['class'], 'pg-next-btn-disable') === false){
            return aliexpress_prepare_next_page(trim($next['href']));
        }
        
        
       // echo '4';
        
        // NEW LAYOUT WITH AJAX
        $res = explode('&page=' , $task['url']);
        if(count($res) > 1){
            // NEXT PAGES
            //echo '5';
            $number = (int) ((int) $res[1] + 1);
            $res = $res[0] . '&page=' . $number;
           // echo $res;
            return $res;
        }else{
            $res = explode("searchAjaxUrl: '" , $html);
            //echo '6';
            if(count($res) > 1){
                //echo '7';
                $res = explode("'" , $res[1] , 2);
                if(count($res) > 1){
                    // echo '8';
                    $res = $res[0] . '&page=2';
                    if(substr($res , 0 , 2) == "//"){
                        $res = 'https:' . $res;
                    }
                    //echo $res;
                    return $res;
                }
            }
        }
        
        
        return false;
}

function aliexpress_prepare_next_page($url){
    if(substr($url , 0 , 2) == "//"){
        return 'https:' . $url;
    }
    return $url;
}