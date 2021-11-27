<?php


function mspro_overstock_category_getUrl($url){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 20,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    // echo $content;exit;
    return $content;

    // echo $url;exit;
}

function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
   // echo $html;
		$result = array();
		
		$link = 'a.pro-thumb';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href'])){
                	$result[] = $value['href'];
            	}
            }
        }
        
        if(count($result) < 1){
            $link = 'a.product-link';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href'])){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        
        if(count($result) < 1){
            $link = 'a.productCardLink';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            // echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href'])){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        if(count($result) > 0){
            foreach($result as $key => $value){
                if(substr($value, 0 , 2) == "//"){
                    $result[$key] = 'https:' . $value;
                }
            }
        }
        
        $result = array_unique($result);
		// echo '<pre>'.print_r($result , 1).'</pre>';
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
        //$next = reset($next);
	    // echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next[0]['href']) && isset($next[0]['title']) && trim($next[0]['title']) == 'Next Page'){
            if(stripos($next[0]['href'] , 'overstock.com') > 0){
                return trim($next[0]['href']);
            }else{
        	   return 'https://www.overstock.com' . trim($next[0]['href']);
            }
        }

        return false;
}