<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	 	$result = array();
	 
		$link = 'a.abox';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $link) {
            	if(stripos($link['href'] , 'holesale7.') < 1){
            		$link['href'] = 'http://www.wholesale7.net' . $link['href'];
            	}
            	 $result[] = $link['href'];
            }
        }
        
        if(count($result) < 1){
        	$link = 'div.list_h a';
        	$parser = new nokogiri($html);
        	$links = $parser->get($link)->toArray();
        	//echo '<pre>'.print_r($links , 1).'</pre>';exit;
	        if (isset($links) && is_array($links) && count($links) > 0) {
	            foreach ($links as $link) {
	            	if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
		            	if(stripos($link['href'] , 'holesale7.') < 1){
		            		$link['href'] = 'http://www.wholesale7.net' . $link['href'];
		            	}
		            	 $result[] = $link['href'];
	            	}
	            }
	        }
        }
        
        if(count($result) < 1){
            $link = 'div.content a.pica';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                    if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
                        if(stripos($link['href'] , 'holesale7.') < 1){
                            $link['href'] = 'http://www.wholesale7.net' . $link['href'];
                        }
                        $result[] = $link['href'];
                    }
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'ul.grid a.pica';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                    if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
                        if(stripos($link['href'] , 'holesale7.') < 1){
                            $link['href'] = 'http://www.wholesale7.net' . $link['href'];
                        }
                        $result[] = $link['href'];
                    }
                }
            }
        }
        
        
        if(count($result) < 1){
            $link = 'div.classify_goods_list div.each_item a.item_top';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                    if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
                        if(stripos($link['href'] , 'holesale7.') < 1){
                            $link['href'] = 'http://www.wholesale7.net' . $link['href'];
                        }
                        $result[] = $link['href'];
                    }
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
        unset($parser);
        if (isset($next['href'])){
        	if(stripos($next['href'] , 'holesale7.') < 1){
		    	$next['href'] = 'http://www.wholesale7.net' . $next['href'];
		    }
		    return $next['href'];
        }
        
        
        $nextPage = 'div.page a[rel=nofollow]';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        // $next = reset($next);
        // echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if(isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next){
                if (isset($pos_next['href']) && isset($pos_next['span']['class']) && trim($pos_next['span']['class']) == "triangle_next"){
                    if(stripos($pos_next['href'] , 'holesale7.') < 1){
                        $pos_next['href'] = 'http://www.wholesale7.net' . $pos_next['href'];
                    }
                    return $pos_next['href'];
                }
            }
        }
        

        $nextPage = 'a.pageYou';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
            if(stripos($next['href'] , 'holesale7.') < 1){
                $next['href'] = 'http://www.wholesale7.net' . $next['href'];
            }
            return $next['href'];
        }

        return false;
}