<?php


function mspro_sportsdirect_category_getUrl($url){
    //echo file_get_contents($url);
    $out = '';
    
    

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url );
    /*curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/cookie.txt");*/
    //curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate, br'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: max-age=0'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.sportsdirect.com'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Upgrade-Insecure-Requests: 1'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Safari/537.36'));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: _gcl_au=1.1.257803233.1552121959; _igt=9462936d-c8b8-401f-e716-21de7d0b84f8; _ga=GA1.2.432959773.1552121960; _gid=GA1.2.2102583553.1552121960; _fbp=fb.1.1552121961779.352046292; um_IsMobile=False; .ASPXANONYMOUS=qF8vmPEM1QEkAAAAYWYxMzg4ZDQtZTVlMC00M2E1LTkzN2MtODQ0YjU0ZTI5MWQy0; language=en-GB; SportsDirect_AuthenticationCookie=37adab97-15ec-4ed4-b44e-7c08e51757b5; X-SD-URep=6a8e6441-da7d-44be-af2e-c9e6109efb1d; acceptedCookies=true; TS01a19d95=01e4dc9a7608d5e93c283968e585f8a7840f10894e23b7718a42aced8a73878fb72e1cad584d2a3c5787bbc7ad414b4d526d6a06b919c0f1590740dd0648ef71e00fb0bc3a2edbef140f65ac173c2d59600282c20467c6033083d5a4166fc6b7eef586b3a939ad9ac338c513b05b309487ec4b4bd0; _ig=262c34b6-f320-4e02-d844-06b0c2b67a78; ChosenSite=www; CountryRedirectCheckIsDone=true; ak_bmsc=32158DF09E9C3FC80C4E27243089E07A0214844F4C4C00006DB0835CFAEC440D~plQmIpqTtEp2+ofmC025Mk1oZL0/YmXovKRiCi9bZGKE/Bqin5eYsfGUaeRRBLo7bD1RiEaEwXjlF7MpAAZ6szhUt7ZHnKWTj3qXcEFT4WLu0YEtiYiVTl/QvehjCbb4XN70xFQCwMmhbMu/CfHctjaJTA4Maa8S59L4um5a+lMHM/aM1NKrVyH4OL+X0h7qsWRQHieUfMtkSx2IKI6khukACFyfVZmW3Ip2YzYhvwkPc='));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'));
    /*curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.sportsdirect.com/tr/kadin/giyim/ust-giyim/bluz/c/M01-C02-N01-AK101-K100024'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate, br'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.sportsdirect.com'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('cookie: ga-userType=GUEST; NSC_JO0cfqsdd1eyooqe0st5unduaigw0bz=ffffffff0966711245525d5f4f58455e445a4a42581a; inLanding=https%3A%2F%2Fwww.koton.com%2Ftr; _sgf_user_id=1822925621780483; _sgf_user_id_ext=true; spUID=151136008202912d6c7c5ca.bd815cc5; first-permission-impression=1; ktn_snid=tH17j+9clWhstp0SmwWsUMONgrcA010; JSESSIONID=8E9E6A3CA0E57D4560D12E489861BDB0.nf2; ktn_snid_.koton.com_%2F_wlf=Z2EtdXNlcklk?LqXoO0IvnV4c2UJWhqemc9FEdewA&Z2EtdXNlclR5cGVf?Cgo/Pxu6gZbXX2+w2W4YWtCgPiIA&SlNFU1NJT05JRF9f?GNSyXR6aj0sDryDTd26IZDdzxdMA&; ktn_snid_.koton.com_%2F_wat=TlNDX0pPMGNmcXNkZDFleW9vcWUwc3Q1dW5kdWFpZ3cwYnpf?K2IVXX8etYFBQy4QMenyLnJxAGIA&; _ga=GA1.2.1816087886.1511360072; _gid=GA1.2.1299145370.1511694072; _sgf_controlGroup=0; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wysi=WY.1.9394620.323644232; _wysi=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wybrs=1511703004569; _wybrs=1511703004569; insdrSV=10; scs=%7B%22t%22%3A1%7D; current-currency=; _sgf_session_id=1822925621780482; ins-gaSSId=27b6a075-f656-eab0-d7ca-38564f24cc9b_1511706588'));
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);*/

    // curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    $data = curl_exec($ch);
    // echo "kjkhrnfiuh";exit;
    //$err     = curl_errno( $ch );
    //$errmsg  = curl_error( $ch );
    //$header  = curl_getinfo( $ch );
    curl_close( $ch );
    
    //echo $errmsg;
    //echo $err;
    // echo 'HTML' . $data;exit;
    return $data;
}

/*
function mspros_sportsdirect_category_getUrl($url){
    // echo $url;
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
    echo 'CONTENT' . $content;exit;
    return $content;
    
    // echo $url;exit;
}*/


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		
		
		 
		$link = 'ul#navlist li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            	    if(strpos($value['href'] , 'sportsdirect.') > 0){
            	        $result[] = $value['href'];
            	    }else{
            	        $result[] = 'http://www.sportsdirect.com' . $value['href'];
            	    }
            	}
                
            }
        }
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        // FILTERING RESULTS (they are filtered at javascript at the page)
        /*if(count($result) > 0){
            $t_res = explode("Filter=" , $task['url']);
            if(count($t_res) > 1){
                $tt_res = explode("&" , $t_res[1]);
                if(count($tt_res) > 1){
                    $filters = $tt_res[0];
                }else{
                    $filters = $t_res[1];
                }
                
                $filteredAPIurl = sportsdirectGetFilteredUrl($html , $filters);
                //echo $filteredAPIurl;exit;
                if($filteredAPIurl){
                    $filterRes = getUrl($filteredAPIurl);
                    if($filterRes){
                        $filterRes = (array) json_decode($filterRes , 1);     
                        if(isset($filterRes['products']) && is_array($filterRes['products']) && count($filterRes['products']) > 0){
                            $result = array();
                            foreach($filterRes['products'] as $product){
                                if(isset($product['PrdUrl']) && !is_array($product['PrdUrl']) && strlen($product['PrdUrl']) > 0){
                                    if(strpos($product['PrdUrl'] , 'portsdirect.') > 0){
                                        $result[] = $product['PrdUrl'];
                                    }else{
                                        $result[] = 'http://www.sportsdirect.com/' . $product['PrdUrl'];
                                    }
                                }
                            }
                        }
                        //echo '<pre>' . print_r($filterRes , 1) . '</pre>';exit;
                    }
                }
            }
        }
        */
        
       
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}





function parse_next_page($html , $task){
    //echo $task;exit;
    
        // hack for filtered results
        if(stripos($task['url'] , 'Filter=') > 0){
            $items = parse_products($html , $task);
            $itemsCount = 0;
            if(is_array($items)){
                $itemsCount = count($items);
            }
            $countPerPage = sportsdirectGetProductsPerPage($task['url']);
            //echo $itemsCount;
            //echo $countPerPage;
            if($itemsCount < $countPerPage){
                return false;
            }
        }
    
        $nextPage = 'a.NextLink';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next[0]['href']) && !is_array($next[0]['href'])){
                    if(strpos($next[0]['href'] , 'sportsdirect.') > 0){
            	        return $next[0]['href'];
            	    }else{
            	        return 'http://www.sportsdirect.com' . $next[0]['href'];
            	    }
        }
        return false;
}


function sportsdirectGetProductsPerPage($url){
    $res = explode('dppp=' , $url);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        return (int) $res[0];
    }
    return 100;
}


function sportsdirectGetFilteredUrl($html , $filters){
    $out = false;
    
    $instruction = 'div#productlistcontainer';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data[0]['data-category']) && 
        isset($data[0]['data-defaultpageno']) && 
        isset($data[0]['data-defaultpagelength']) && 
        isset($data[0]['data-defaultsortorder']) && 
        isset($data[0]['data-descfilter']) && 
        isset($data[0]['data-searchtermcategory']) && 
        isset($data[0]['data-fltrselectedcurrency'])
        ){
            $baseAPIurl = 'http://www.sportsdirect.com/DesktopModules/BrowseV2/API/BrowseV2Service/GetProductsInformation?';
            $baseAPIurl .= 'categoryName=' . trim($data[0]['data-category']);
            $baseAPIurl .= '&currentPage=' . $data[0]['data-defaultpageno'];
            $baseAPIurl .= '&productsPerPage=' . $data[0]['data-defaultpagelength'];
            $baseAPIurl .= '&sortOption=' . $data[0]['data-defaultsortorder'];
            $baseAPIurl .= '&selectedFilters=' . $filters;
            $baseAPIurl .= '&isSearch=false&descriptionFilter=' . $data[0]['data-descfilter'];
            $baseAPIurl .= '&columns=4&mobileColumns=2&clearFilters=false&pathName=' . sportsdirectGetFilteredUrlpathName($html);
            $baseAPIurl .= '&searchTermCategory=' . $data[0]['data-searchtermcategory'];
            $baseAPIurl .= '&selectedCurrency=' . $data[0]['data-fltrselectedcurrency'];
            $out = $baseAPIurl;
    }

    return $out;
}

function sportsdirectGetFilteredUrlpathName($html){
    $res = explode('<form method="post" action="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return urlencode($res[0]);
        }
    }
    return '';
}
