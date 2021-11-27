<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		
		if(stripos($task['url'] , 'search?campaign_id=') > 0){
		    //NEXT PAGES
		    // echo $html;exit;
		    $res =  (array) json_decode('[' . $html . ']' , 1);
		    // echo '<pre>' . print_r($res , 1) . '</pre>';exit;
		    if(isset($res[0]['data']) && is_array($res[0]['data']) && count($res[0]['data']) > 0){
		        foreach($res[0]['data'] as $link){
		            if(isset($link['url'])){
		                $result[] = $link['url'];
		            }
		        }
		    }
		}else{
    		 // FIRST PAGE
    		$link = 'ul.item-box-wrapper li div.placard a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href']) && strlen(trim($value['href'])) > 0 && stripos($value['href'] , "ascript:void(") < 1){
                    $result[] = $value['href'];
                }
            }
    
            $link = 'div.grid-list a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href']) && strlen(trim($value['href'])) > 0 && stripos($value['href'] , "ascript:void(") < 1){
                    $result[] = $value['href'];
                }
                 
            }
            
            $res = explode('"itemListElement":' , $html);
            if(count($res) > 1){
                $res = explode('</script>' , $res[1]);
                if(count($res) > 1){
                    $bl = explode(',"url":"' , $res[0]);
                    if(count($bl) > 1){
                        unset($bl[0]);
                        foreach($bl as $posBl){
                            $t_res = explode('"' , $posBl , 2);
                            $result[] = stripcslashes( $t_res[0]);
                        }
                    }
                }
            }
		}
        
        $result = array_unique($result);
        // echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
    
        $link = 'li.pagination-next a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        // echo '<pre>'.print_r($links , 1).'</pre>';exit;
        if(isset($links[0]['href'])){
            return $links[0]['href'];
        }
        
        
        
        
        $t_res = explode('&page=' , $task['url']);
        if(count($t_res) < 2){
            // FIRST PAGE
            return 'https://supermarket.souq.com/eg-en/search?campaign_id=' . mspro_souq_campainID($html) .'&page=2';
        }else{
            // NEXT PAGES
            $nextNumber = (int) ( (int)$t_res[1] + 1 );
            return $t_res[0] . '&page=' . $nextNumber;
        }

        return false;
}


function mspro_souq_campainID($html){
    $res = explode('campaign_id=' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        return $res[0];
    }
    return '';
}










