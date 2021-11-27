<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}

function mspro_sunsky_category_getUrl($url){
   $out = '';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/cookie.txt");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING , "");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Upgrade-Insecure-Requests: 1'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.koton.com/tr/kadin/giyim/ust-giyim/bluz/c/M01-C02-N01-AK101-K100024'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate, br'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.koton.com'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('cookie: ga-userType=GUEST; NSC_JO0cfqsdd1eyooqe0st5unduaigw0bz=ffffffff0966711245525d5f4f58455e445a4a42581a; inLanding=https%3A%2F%2Fwww.koton.com%2Ftr; _sgf_user_id=1822925621780483; _sgf_user_id_ext=true; spUID=151136008202912d6c7c5ca.bd815cc5; first-permission-impression=1; ktn_snid=tH17j+9clWhstp0SmwWsUMONgrcA010; JSESSIONID=8E9E6A3CA0E57D4560D12E489861BDB0.nf2; ktn_snid_.koton.com_%2F_wlf=Z2EtdXNlcklk?LqXoO0IvnV4c2UJWhqemc9FEdewA&Z2EtdXNlclR5cGVf?Cgo/Pxu6gZbXX2+w2W4YWtCgPiIA&SlNFU1NJT05JRF9f?GNSyXR6aj0sDryDTd26IZDdzxdMA&; ktn_snid_.koton.com_%2F_wat=TlNDX0pPMGNmcXNkZDFleW9vcWUwc3Q1dW5kdWFpZ3cwYnpf?K2IVXX8etYFBQy4QMenyLnJxAGIA&; _ga=GA1.2.1816087886.1511360072; _gid=GA1.2.1299145370.1511694072; _sgf_controlGroup=0; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wysi=WY.1.9394620.323644232; _wysi=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wybrs=1511703004569; _wybrs=1511703004569; insdrSV=10; scs=%7B%22t%22%3A1%7D; current-currency=; _sgf_session_id=1822925621780482; ins-gaSSId=27b6a075-f656-eab0-d7ca-38564f24cc9b_1511706588'));
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_REFERER, "https://www.koton.com");
    $data = curl_exec($ch);
    
    // echo 'HTML' . $data;exit;
    return $data;
}


function parse_products($html , $task){
    //echo 'HELPER HTML: ' . $html;exit;
		$result = array();

		$res = explode('<h3>' , $html);
		if(count($res) > 1){
		    unset($res[0]);
		    foreach($res as $pos_block){
		        $t_res = explode('</h3>' , $pos_block , 2);
		        if(count($t_res) > 1){
		            $t_res = explode('href="' , $t_res[0]);
		            if(count($t_res) > 1){
		                $t_res = explode('"' , $t_res[1] , 2);
		                if(count($t_res) > 1){
		                    $result[] = $t_res[0];
		                }
		            }
		        }
		    }
		    
		}
		
		
		/*$link = 'div.productinfodisplay';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            		$result[] = $value['href'];
            	}
                
            }
        }*/
        
        
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
        
        
        $result = array_unique($result);
        // echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}


function parse_next_page($html , $task){
        $page = false;
        $url = $task['url'];
        $cat_id = sunsky_getCatId($html);
        
        // get domain lang
        $domain_lang = '';
        $tres = explode('/' , $task['url']);
        if(isset($tres[3]) && strlen($tres[3]) == 2){
             $domain_lang = $tres[3] . '/';
        }
        
        // next pages
        if($cat_id){
            if(strpos($url , '&page=') > 1){
                $res = explode('&page=' , $url);
                if(count($res) > 1){
                    $page = $res[0] . '&page=' .  (int) ( (int) $res[1] + 1);
                }
            }else{
                // first page
                $page = 'http://www.sunsky-online.com/' . $domain_lang . 'product/default!search.do?categoryId=' . $cat_id . '&page=2';
               
            }
        }
        // echo $page;
        return $page;
}


function sunsky_getCatId($html){
    $res = explode('name="categoryId" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    
    $res = explode('search.do?categoryId=' , $html);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    
    return false;
}