<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'ul.productList p.title a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            		$result[] = 'http://www.dx.com' . $value['href'];
            	}
                
            }
        }

        $link = 'ul.product-list li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
                if( isset($value['href']) && !is_array($value['href']) ){
                    $result[] = 'http://www.dx.com' . $value['href'];
                }
        
            }
        }
        
        // AJAX
        // echo $html;
        if(count($result) < 1){
            $res = explode('filterParam: {' , $html);
            // echo count($res);
            if(count($res) > 1){
                $res = explode('};' , $res[1] , 2);
                $res = explode(',' , $res[0]);
                $data = array();
                foreach($res as $bl){
                    $tres = explode(':' , $bl);
                    if(count($tres) > 1 && trim($tres[0]) !== 'attribute'){
                        $arg = str_ireplace(array("['']" , "'true'" , "'false'" , "'") , array('', 'true' , 'false' , '') , trim($tres[1]));
                        // $arg = trim($tres[1]);
                        $data[trim($tres[0])] = $arg;
                    }
                }
                // echo '<pre>' . print_r($data , 1) . '</pre>';
                $response = getUrl('https://www.dx.com/home/product/getCategorySpuList' , $data);
                $response = str_ireplace("\/", '/', $response);
                
                $ttres = explode('"total"' , $response);
                if(count($ttres) > 1){
                    $res =  (array) json_decode('[{"total"' . $ttres[1] . ']', 1);
                    if(isset($res[0]['data']) && is_array($res[0]['data']) && count($res[0]['data']) > 0){
                        foreach($res[0]['data'] as $pr){
                            if(isset($pr['LinkUrl'])){
                                $result[] = 'http://www.dx.com' . $pr['LinkUrl'];
                            }
                        }
                    }
                    
                    //echo '<pre>' . print_r($res , 1) . '</pre>';
                    // echo '[{"total"' . $ttres[1] . ']';exit;
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
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	return 'http://www.dx.com/'.trim($next['href']);
        }

        unset($parser);

        return false;
}