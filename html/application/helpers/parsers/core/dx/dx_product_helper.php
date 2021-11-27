<?php


function mspro_dx_title($html){
    //echo $html;
		$instruction = 'span[itemprop=name]';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
    		return trim($data[0]['#text']);
    	}elseif(isset($data[0]['title']) && !is_array($data[0]['title'])){
    		return trim($data[0]['title']);
    	}
    	
    	$res = explode('<title>' , $html);
            if(count($res) > 1){
            	$res = explode('</title>' , $res[1] , 2);
            	if(count($res) > 1){
            		return trim($res[0]);
            	} 
            }
    	return '';
}

function mspro_dx_description($html){
		$res = '';
		// echo $html;
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#overview div.pinfo_content');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		//echo utf8_encode($res);exit;
		
		$temp  = $pq->find('div#productDescriptBox');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('div#specification div.pinfo_content');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		
		if(strlen(trim(res)) < 4){
		    $sku = mspro_dx_sku($html);
		    $response = getUrl('https://www.dx.com/home/product/getSpuDescription' , array('spu' => $sku));
		    $response = str_ireplace("\/", '/', $response);
		    $response =  (array) json_decode('[' . $response . ']', 1);
		    if(isset($response[0]['Descriptions'])){
		        $res = $response[0]['Descriptions'];
		    }
		    /*echo '<pre>'.print_r($response , 1).'</pre>';exit;
		    echo $response;*/
		}
		
		//echo $res;exit;
		
		return $res;
}


function mspro_dx_price($html){
    $res = explode('<meta itemprop="price" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('<i class="low-sale-price">' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode("value: '" , $html);
    if(count($res) > 1){
        $res = explode("'" , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode("pvalues : ['" , $html);
    if(count($res) > 1){
        $res = explode("'" , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    
		$res = explode('"price": "' , $html);
		if(count($res) > 1){
			$res = explode('"' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		
		$res = explode('pvalues : [' , $html);
		if(count($res) > 1){
			$res = explode(']' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		
		$res = explode('itemprop="price">' , $html);
		if(count($res) > 1){
			$res = explode('</span>' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		 
		return '';
}


function mspro_dx_sku($html){
    $res = explode('data-spuid="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('<dd class="sku">' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('<meta itemprop="sku" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('<input id="spuId" type="hidden" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }	
        $instruction = 'span#sku';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
			return trim($data[0]['#text']);
		}
		
		
		$res = explode('sku="' , $html);
		if(count($res) > 1){
			$res = explode('"' , $res[1] , 2);
			if(count($res) > 1){
				return trim($res[0]);
			}
		}
		
		return ''; 
}

function mspro_dx_model($html){
        return mspro_dx_sku($html);
}


function mspro_dx_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]) );
			}	 
       }
       return '';
}

function mspro_dx_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){ 
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]) );
			}	 
       }
       return '';
}


function mspro_dx_main_image($html){
		$arr = dx_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_dx_other_images($html){
		$arr = dx_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function dx_get_images($html){
	    $out = array();
	    
	    $instruction = 'ul.product-small-images li a';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0) {
	        foreach($data as $pos_img){
	            if(isset($pos_img['href'])){
	                $out[] = $pos_img['href'];
	            }
	        }
	    }
	    
	    
	    $res = explode('<meta property="og:image" content="' , $html);
	    if(count($res) > 1){
	        $res = explode('"' , $res[1] , 2);
	        /*if(stripos($res[0] , 'ttp') < 1){
	           $baseIm = 'https://' . $res[0];
	        }*/
	        $baseIm = stripos($res[0] , 'ttp') < 1? 'https://' . $res[0] : $res[0];
	        $out[] = $baseIm;
	        $temp = explode('1.jpg', $baseIm);
	        if(count($temp) > 1){
	            for($i = 2; $i < 12; $i++){
	                $tempIm = $temp[0] . $i . '.jpg';
	                if(mspro_dx_is_image($tempIm)){
	                    $out[] = $tempIm;
	                }
	            }
	        }
	    }
	    
	    
	    if( is_array($out) && count($out) > 0 ){
	        foreach($out as $key => $value){
	            if(substr($value , 0 , 2) == '//'){
	                $out[$key] = substr($value , 2);
	            }
	        }
	    }
	    
	    
	    $out = clear_images_array($out);
	    
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    
	    return $out;
}

function mspro_dx_is_image($filename) {
    $is = @getimagesize($filename);
    if(!$is){
        return false;
    }elseif(!in_array($is[2], array(1,2,3))){
        return false;
    }else{
        return true;
    }
}



function mspro_dx_options($html){
    $out = array();
    
    $ID = mspro_dx_sku($html);
    $res = getUrl('https://www.dx.com/home/product/getSpuBaseInfo' , array('spu' => $ID));
    $res = (array) json_decode($res , 1);
    
    //echo '<pre>O'.print_r($res , 1).'</pre>';exit;
    
    
    if(isset($res['AttrList']) && is_array($res['AttrList']) && count($res['AttrList']) > 0){
        foreach($res['AttrList'] as $attr){
            if(isset($attr['name']) && isset($attr['attr']) && is_array($attr['attr']) && count($attr['attr']) > 0){
                $OPTION = array();
                $OPTION['name'] = $attr['name'];
                $OPTION['type'] = "select";
                $type = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($attr['attr'] as $option_value){
                    if( (isset($option_value['option_name']) && !is_array($option_value['option_name'])) ){
                        $name = $option_value['option_name'];
                        $vals_arr = array('name' => $name , 'price' => 0);
                        /*if(isset($option_value['data-src']) && !is_array($option_value['data-src']) && strlen(trim($option_value['data-src'])) > 0){
                            $vals_arr['image'] = $option_value['data-src'];
                            $type = 'image';
                        }elseif(isset($option_value['a']['img']['src']) && !is_array($option_value['a']['img']['src']) && strlen(trim($option_value['a']['img']['src'])) > 0){
                            $vals_arr['image'] = $option_value['a']['img']['src'];
                            $type = 'image';
                        }elseif(isset($option_value['data-large']) && !is_array($option_value['data-large']) && strlen(trim($option_value['data-large'])) > 0){
                            $vals_arr['image'] = $option_value['data-large'];
                            $type = 'image';
                        }*/
                        $OPTION['values'][] = $vals_arr;
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    //echo '<pre>S'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}



/*
function mspro_dx_noMoreAvailable($html){
	return false;
}
*/
