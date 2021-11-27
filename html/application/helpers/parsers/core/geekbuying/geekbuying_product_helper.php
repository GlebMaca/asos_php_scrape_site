<?php


function mspro_geekbuying_title($html){
		$instruction = 'h1#productName';
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

function mspro_geekbuying_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#DESCRIPTION_HTML');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		
		
		return $res;
}


function mspro_geekbuying_price($html){
		$res = explode('var PRODUCT_PRICE = ' , $html);
        if(count($res) > 1){
        	$res = explode('var' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $price;
        	} 
        }
        
        $res = explode('<input type="hidden" id="unitPrice" name="unitPrice" style="display: none;" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
        $res = explode('ecomm_pvalue: ' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
        return '';
}


function mspro_geekbuying_sku($html){
    $res = explode("{ Sku: '" , $html);
    if(count($res) > 1){
        $res = explode("'" , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return mspro_geekbuying_model($html);
}

function mspro_geekbuying_model($html){
	   $res = explode('Item Code: <b>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('name="product.id" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('<span class="item_code_val">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('bindSkuProperty.SetSelectProduct(' , $html);
        if(count($res) > 1){
            $res = explode(')' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        return '';
}


function mspro_geekbuying_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_geekbuying_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
			}	 
       }
       return '';
}

function mspro_geekbuying_meta_keywords($html){
      $res =  explode('<meta name="keyword" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_geekbuying_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
       		}
       		 
       }
       return  mspro_geekbuying_meta_description($html , $url);
}

function clear_geekbuying_meta_tags($str){
    return str_ireplace(array("geekbuying" , "Free Shipping") , array("" , "") , $str);
}


function mspro_geekbuying_main_image($html){
		$arr = geekbuying_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_geekbuying_other_images($html){
		$arr = geekbuying_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function geekbuying_get_images($html){
        $out = array();
    	
        $instruction = 'ul#thumbnail li a img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';//exit;
        unset($parser);
        if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    		    if(isset($pos_image['src']) && !is_array($pos_image['src']) && strlen($pos_image['src']) > 0){
    		        $res =  str_ireplace(array("make_pic" , "1.jpg") , array("ggo_pic" , ".jpg") , $pos_image['src']);
    		        $res =  str_ireplace(array("2.jpg" , "3.jpg" , "4.jpg" , "5.jpg" , "6.jpg" , "7.jpg" , "8.jpg" , "9.jpg" , "10.jpg" , "11.jpg" , "12.jpg" , "13.jpg") , ".jpg" ,$res);
    		        $out[] = $res;
    		    }elseif(isset($pos_image['data-original']) && !is_array($pos_image['data-original']) && strlen($pos_image['data-original']) > 0){
    		        $res =  str_ireplace(array("make_pic" , "1.jpg") , array("ggo_pic" , ".jpg") , $pos_image['data-original']);
    		        $res =  str_ireplace(array("2.jpg" , "3.jpg" , "4.jpg" , "5.jpg" , "6.jpg" , "7.jpg" , "8.jpg" , "9.jpg" , "10.jpg" , "11.jpg" , "12.jpg" , "13.jpg") , ".jpg" ,$res);
    		        $out[] = $res;
    		    }
    		}
        }
    	
        if(count($out) > 0){
            foreach($out as $k => $v){
                if(substr($v , 0 , 2) == "//"){
                    $out[$k] = 'http:' . $v;
                }
            }
        }
        $out = array_unique($out);
    	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    	
    	return $out;
}


/*
function mspro_geekbuying_options($html){
    $out = array();
	
    
    // COLORS
	$instruction = 'div.color_detail';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
    if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
		$OPTION = array();
		$OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
		$OPTION['type'] = "select";
		$OPTION['required'] = true;
		$OPTION['values'] = array();
		$option_values_array = $res[0]['select'][0]['option'];
		unset($option_values_array[0]);
		foreach($option_values_array as $option_value){
			if(isset($option_value['#text']) && !is_array($option_value['#text'])){
				$OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
			}
		}
		if(count($OPTION['values']) > 0){
			$out[] = $OPTION;
		}
	 }
	 
	 // SIZES
	 $instruction = 'div.size_detail';
	 $parser = new nokogiri($html);
	 $res = $parser->get($instruction)->toArray();
	 //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	 unset($parser);
	 if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
	     $OPTION = array();
	     $OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
	     $OPTION['type'] = "select";
	     $OPTION['required'] = true;
	     $OPTION['values'] = array();
	     $option_values_array = $res[0]['select'][0]['option'];
	     unset($option_values_array[0]);
	     foreach($option_values_array as $option_value){
	         if(isset($option_value['#text']) && !is_array($option_value['#text'])){
	             $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
	         }
	     }
	     if(count($OPTION['values']) > 0){
	         $out[] = $OPTION;
	     }
	 }
	
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}
*/

function mspro_geekbuying_attributes($html){
    $out = array();


    $instruction = 'table.jbEidtTable tr';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    // echo '<pre>'.print_r($res , 1).'</pre>';//exit;
    unset($parser);
    if(count($res) > 1){
        foreach($res as $pos_attr_group){
            if(isset($pos_attr_group['td'][0]['strong']['#text']) ){
                $group = $pos_attr_group['td'][0]['strong']['#text'];
                if(isset($pos_attr_group['td'][1]['p'][0]['#text']) && is_array($pos_attr_group['td'][1]['p'][0]['#text']) && count($pos_attr_group['td'][1]['p'][0]['#text']) > 0){
                    foreach($pos_attr_group['td'][1]['p'][0]['#text'] as $pos_attr){
                        $tres = explode(':' , $pos_attr);
                        if(count($tres) > 1){
                            $ATTR = array();
                            $ATTR['group'] = $group;
                            $ATTR['name'] = trim($tres[0]);
                            $ATTR['value'] = trim($tres[1]);
                            if(strlen($ATTR['name']) > 1 && strlen($ATTR['value']) > 1){
                                $out[] = $ATTR;
                            }
                        }else{
                            $ATTR = array();
                            $ATTR['group'] = $group;
                            $ATTR['name'] = $group;
                            $ATTR['value'] = trim($pos_attr);
                            if(strlen($ATTR['name']) > 1 && strlen($ATTR['value']) > 1){
                                $out[] = $ATTR;
                            }
                        }
                    }
                }elseif(isset($pos_attr_group['td'][1]['#text']) && is_array($pos_attr_group['td'][1]['#text']) && count($pos_attr_group['td'][1]['#text']) > 0){
                    foreach($pos_attr_group['td'][1]['#text'] as $pos_attr){
                        $tres = explode(':' , $pos_attr);
                        if(count($tres) > 1){
                            $ATTR = array();
                            $ATTR['group'] = $group;
                            $ATTR['name'] = trim($tres[0]);
                            $ATTR['value'] = trim($tres[1]);
                            if(strlen($ATTR['name']) > 1 && strlen($ATTR['value']) > 1){
                                $out[] = $ATTR;
                            }
                        }else{
                            $ATTR = array();
                            $ATTR['group'] = $group;
                            $ATTR['name'] = $group;
                            $ATTR['value'] = trim($tres[0]);
                            if(strlen($ATTR['name']) > 1 && strlen($ATTR['value']) > 1){
                                $out[] = $ATTR;
                            }
                        }
                    }
                }
            }
        }
    }

   // echo '<pre>'.print_r($out , 1).'</pre>';exit;

    return $out;
}



function mspro_geekbuying_noMoreAvailable($html){
	return false;
}

