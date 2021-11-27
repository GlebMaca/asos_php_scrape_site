<?php


function mspro_shopclues_title($html){
		$instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        return '';
}

function mspro_shopclues_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#content_block_description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('div#content_block_features');
		foreach ($temp as $block){
			$res .=  $temp->html();
		}
		
		
		// remove description NOTES
		$t_res = explode('<div class="desc-note">' , $res);
		if(count($t_res) > 1){
		    $tt_res = explode('</div>' , $t_res[1] , 2);
		    if(count($tt_res) > 1){
		        $res = $t_res[0] . $tt_res[1];
		    }
		}
		
		$desc = getUrl("http://www.shopclues.com/ajaxCall/getProductDesc/" . mspro_shopclues_ID($html));
		if($desc){
		    $res .= '<h4>Product Description</h4><div>' . $desc . '</div>';
		}
		
		$temp  = $pq->find('div#specification');
		foreach ($temp as $block){
		    $res .= '<h4>Specification</h4><div>' . $temp->html() . '</div>';
		}
		
		$res = str_replace(array("<h3" , "</h3>") , array("<h5" , "</h5>") , $res);
		
		// remove external links
		$res = str_replace(array('(<a class="cm-tooltip  prd_pg " title="List of items that will get shipped">?</a>)') , array("") , $res);	
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		
		//echo $res;exit;
		
		return $res;
}


function mspro_shopclues_price($html){
		$res = explode('<div class="price"><label>' , $html);
        if(count($res) > 1){
        	$res = explode('</div>' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = str_replace(array("Rs.") , array("") , $res[0]);
        	    $price = preg_replace("/[^0-9.]/", "",  $price);
        		return (float) $price;
        	} 
        }
        
        $res = explode('\'price\':"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
        $res = explode('_atm_params.price=' , $html);
        if(count($res) > 1){
            $res = explode(';' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
        $res = explode('ecomm_totalvalue: ' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
        return '';
}

    
function mspro_shopclues_ID($html){
        $res = explode('var pid = "' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('span class="pID">Product Id :' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('moe_product_id = "' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('[product_id]\' value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('<input type="hidden" id="quantityId" name=\'product_data[' , $html);
        if(count($res) > 1){
            $res = explode(']' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        return '';
}

function mspro_shopclues_model($html){
        return mspro_shopclues_ID($html);
}

function mspro_shopclues_sku($html){
    return mspro_shopclues_ID($html);
}


function mspro_shopclues_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_shopclues_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
			}	 
       }
       return '';
}

function mspro_shopclues_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_shopclues_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
       		}
       		 
       }
       return  mspro_shopclues_meta_description($html , $url);
}

function clear_shopclues_meta_tags($str){
    return str_ireplace(array("shopclues" , "Free Shipping") , array("" , "") , $str);
}


function mspro_shopclues_main_image($html){
		$arr = shopclues_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_shopclues_other_images($html){
		$arr = shopclues_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function shopclues_get_images($html){
        $out = array();
        
        // main img
        $instruction = 'img#zoom_picture_gall';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        if(isset($data[0]['data-zoom-image']) && !is_array($data[0]['data-zoom-image'])){
            if(strpos($data[0]['data-zoom-image'] , "shopclues.") < 1){
                $out[] = 'http://cdn.shopclues.net' . $data[0]['data-zoom-image'];
            }else{
                $out[] = $data[0]['data-zoom-image'];
            }
        }elseif(isset($data[0]['src']) && !is_array($data[0]['src'])){
            if(strpos($data[0]['src'] , "shopclues.") < 1){
                $out[] = 'http://cdn.shopclues.net' . $data[0]['src'];
            }else{
                $out[] = $data[0]['src'];
            }
        }
    	
    	$instruction = 'a.jqzoom';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['href']) && !is_array($pos_image['href'])){
    				if(strpos($pos_image['href'] , "shopclues.") < 1){
    					$out[] = 'http://cdn.shopclues.net' . $pos_image['href'];
    				}else{
    					$out[] = $pos_image['href'];
    				}
    			}elseif(isset($pos_image['input'][0]['value']) && !is_array($pos_image['input'][0]['value'])){
    			    if(strpos($pos_image['input'][0]['value'] , "shopclues.") < 1){
    					$out[] = 'http://cdn.shopclues.net' . $pos_image['input'][0]['value'];
    				}else{
    					$out[] = $pos_image['input'][0]['value'];
    				}
    			}
    		}
    	}
    	
    	$instruction = 'ul.slides li';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    	    foreach($data as $pos_image){
    	        if(isset($pos_image['input'][0]['value']) && !is_array($pos_image['input'][0]['value'])){
    	            if(strpos($pos_image['input'][0]['value'] , "shopclues.") < 1){
    	                $out[] = 'http://cdn.shopclues.net' . $pos_image['input'][0]['value'];
    	            }else{
    	                $out[] = $pos_image['input'][0]['value'];
    	            }
    	        }elseif(isset($pos_image['a'][0]['input'][0]['value']) && !is_array($pos_image['a'][0]['input'][0]['value'])){
    	            if(strpos($pos_image['a'][0]['input'][0]['value'] , "shopclues.") < 1){
    	                $out[] = 'http://cdn.shopclues.net' . $pos_image['a'][0]['input'][0]['value'];
    	            }else{
    	                $out[] = $pos_image['a'][0]['input'][0]['value'];
    	            }
    	        }
    	    }
    	}
    	
    	// another onevariation
    	$instruction = 'ul#thumblist li a';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	if(isset($data) && is_array($data) && count($data) > 0){
    	    foreach($data as $pos_image){
    	        if(isset($pos_image['data-zoom-image']) && !is_array($pos_image['data-zoom-image'])){
    	            $out[] = $pos_image['data-zoom-image'];
    	        }elseif(isset($pos_image['data-image']) && !is_array($pos_image['data-image'])){
    	            $out[] = $pos_image['data-image'];
    	        }
    	    }
    	}
    	
        $out = array_unique($out);
    	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    	
    	return $out;
}



function mspro_shopclues_options($html){
    $out = array();
	
	$instruction = 'div.f_variation ul li';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['scname']) && !is_array($pos_option['scname']) && isset($pos_option['span']) && is_array($pos_option['span']) && count($pos_option['span']) > 0){
				$OPTION = array();
				$OPTION['name'] = trim($pos_option['scname']);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($pos_option['span'] as $option_value){
					if(isset($option_value['#text']) && !is_array($option_value['#text'])){
						$OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
		}
	}
	
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}



/*
function mspro_shopclues_noMoreAvailable($html){
	return false;
}
*/
