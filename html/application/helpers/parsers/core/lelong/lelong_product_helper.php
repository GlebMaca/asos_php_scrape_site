<?php


function mspro_lelong_title($html){
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

function mspro_lelong_description($html){
		$out = '';
		
		// add item Specs
		$res = explode('<!-- #Item Specification -->' , $html);
		if(count($res) > 1){
		    $res = explode('<!-- #' , $res[1]);
		    $out .= $res[0];
		}
		
		$res = explode('<table width="100%" border=0 cellspacing=1 cellpadding=1>' , $html);
        if(count($res) > 1){
        	$res = explode('<br><br>' , $res[1] , 2);
        	if(count($res) > 1){
        	    $out .= $res[0];
        	} 
        }
        
        $instruction = 'img.width950';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data) && is_array($data) && $data > 0 ) {
            foreach($data as $pos_img){
                if(isset($pos_img['src']) && !is_array($pos_img['src'])){
                    $im = $pos_img['src'];
                    if(substr($im , 0 , 2) == '//'){
                        $im = 'https:' . $im;
                    }
                    $out .= '<div><img src="' . $im . '" /></div>';
                }
            }
        }
        
        $instruction = 'img.width1180';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data) && is_array($data) && $data > 0 ) {
            foreach($data as $pos_img){
                if(isset($pos_img['src']) && !is_array($pos_img['src'])){
                    $im = $pos_img['src'];
                    if(substr($im , 0 , 2) == '//'){
                        $im = 'https:' . $im;
                    }
                    $out .= '<div><img src="' . $im . '" /></div>';
                }
            }
        }
        
        $instruction = 'img.img-responsive';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data) && is_array($data) && $data > 0 ) {
            foreach($data as $pos_img){
                if(isset($pos_img['src']) && !is_array($pos_img['src'])){
                    $im = $pos_img['src'];
                    if(substr($im , 0 , 2) == '//'){
                        $im = 'https:' . $im;
                    }
                    $out .= '<div><img src="' . $im . '" /></div>';
                }
            }
        }
        
        $instruction = 'img.maxWidth950';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data) && is_array($data) && $data > 0 ) {
            foreach($data as $pos_img){
                if(isset($pos_img['src']) && !is_array($pos_img['src']) &&
                     stripos($pos_img['src'], 'Tempelate/post.jpg') < 1 &&
                     stripos($pos_img['src'], 'Tempelate/poslaju.jpg') < 1 &&
                     stripos($pos_img['src'], 'Tempelate/visa.jpg') < 1 &&
                     stripos($pos_img['src'], 'Tempelate/master.jpg') < 1 &&
                     stripos($pos_img['src'], 'Tempelate/NetPay.jpg') < 1){
                    $im = $pos_img['src'];
                    if(substr($im , 0 , 2) == '//'){
                        $im = 'https:' . $im;
                    }
                    $out .= '<div><img src="' . $im . '" /></div>';
                    // break;
                }
            }
        }
        
        
        // add Product Description
        $res = explode('<!-- #Product Description -->' , $html);
        if(count($res) > 1){
            $res = explode('<!-- #' , $res[1]);
            $out .= strip_tags($res[0] , '<table><tr><td><p><strong><span><br/>');
        }
        
        /*$pq = phpQuery::newDocumentHTML($html);
        $temp  = $pq->find('table#desc-tbl');
        foreach ($temp as $block){
            $out .= '<table>' . $temp->html() . '</table>';
        }*/
        $out = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $out);
        $out = '<div>' . $out . '</div>';
        
        // echo $out;exit;
		// return utf8_encode($out);
		return $out;
}


function mspro_lelong_price($html){
		 $res = explode('<meta property="og:price:amount" content="' , $html);
        if(count($res) > 1){
        	$res = explode('"' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $price;
        	} 
        }
        $res = explode('<span id=ItemPrice itemprop=price>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<span itemprop=price>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<input type=hidden name=ProductBasePrice id=ProductBasePrice value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('","pr":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        return '';
}


function mspro_lelong_sku($html){
		return mspro_lelong_model($html); 
}

function mspro_lelong_manufacturer($html){
        $res = explode('Brand<span> : ' , $html);
        if(count($res) > 1){
        	$res = explode('</span>' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim(strip_tags($res[0]));
        	} 
        }
        return '';
}

function mspro_lelong_model($html){
		$res = explode('Item ID :' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim($res[0]);
        	} 
        }
        $res = explode('name=ProductID id=ProductID value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('Model / SKU<span> :' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        return '';
}


function mspro_lelong_meta_description($html){
	  $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
			}	 
       }
       return '';
}

function mspro_lelong_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_lelong_meta_description($html);
}


function mspro_lelong_main_image($html){
		$arr = lelong_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_lelong_other_images($html){
		$arr = lelong_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function lelong_get_images($html){
		$out = array();
	
    	$instruction = 'img[itemprop=image]';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen($data[0]['src']) > 0){
    		$out[] = $data[0]['src'];
    	}
    	
    	$instruction = 'ul#pro-thumbnail img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';
    	unset($parser);
    	if (isset($data) && is_array($data) && $data > 0 ) {
    	    foreach($data as $pos_img){
    	        if(isset($pos_img['src']) && !is_array($pos_img['src'])){
    	            $im = $pos_img['src'];
    	            if(substr($im , 0 , 2) == '//'){
    	                $im = 'https:' . $im;
    	            }
    	            $out[] = $im;
    	        }
    	    }
    	}
    	
    	$instruction = 'div#pro-thumbnail img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	// echo '<pre>'.print_r($data , 1).'</pre>';
    	unset($parser);
    	if (isset($data) && is_array($data) && $data > 0 ) {
    	    foreach($data as $pos_img){
    	        if(isset($pos_img['data-lazy']) && !is_array($pos_img['data-lazy'])){
    	            $im = $pos_img['data-lazy'];
    	            if(substr($im , 0 , 2) == '//'){
    	                $im = 'https:' . $im;
    	            }
    	            $out[] = $im;
    	        }elseif(isset($pos_img['src']) && !is_array($pos_img['src'])){
    	            $im = $pos_img['src'];
    	            if(substr($im , 0 , 2) == '//'){
    	                $im = 'https:' . $im;
    	            }
    	            $out[] = $im;
    	        }
    	    }
    	}
    	
    	$instruction = 'div.thumbnail-img img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	/// echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if (isset($data) && is_array($data) && $data > 0 ) {
    	    foreach($data as $pos_img){
    	        if(isset($pos_img['data-original']) && !is_array($pos_img['data-original'])){
    	            $im = $pos_img['data-original'];
    	            if(substr($im , 0 , 2) == '//'){
    	                $im = 'https:' . $im;
    	            }
    	            $out[] = $im;
    	        }
    	    }
    	}
    	
	    $out = clear_images_array($out);
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    
	    return $out;
}


function mspro_lelong_options($html){
	$out = array();
	

	$instruction = 'form#myorder table tr[valign=top]';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
	if (isset($res) && is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['td'][0]['#text']) && !is_array($pos_option['td'][0]['#text']) && strlen(trim($pos_option['td'][0]['#text'])) > 0 && isset($pos_option['td'][2]['select']['option']) && is_array($pos_option['td'][2]['select']['option']) && count($pos_option['td'][2]['select']['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['td'][0]['#text']) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values = $pos_option['td'][2]['select']['option'];
				unset($option_values[0]);
				foreach($option_values as $option_value){
					if(isset($option_value['#text']) && !is_array($option_value['#text']) && strpos($option_value['#text'] , "Sold Out") < 1){
						$OPTION['values'][] = array('name' => mspro_lelong_clear_option_value($option_value['#text']) , 'price' => mspro_lelong_get_option_price($option_value['#text']) );
					}elseif(isset($option_value['#text'][0]) && !is_array($option_value['#text'][0]) && strpos($option_value['#text'] , "Sold Out") < 1){
						$OPTION['values'][] = array('name' => mspro_lelong_clear_option_value($option_value['#text'][0]) , 'price' => mspro_lelong_get_option_price($option_value['#text'][0]) );
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
		}
	}
	
	$instruction = 'select#selVariant option';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 1){
	    $originalPrice = mspro_lelong_price($html);
	    $OPTION = array();
	    $OPTION['name'] = "Option";
	    $OPTION['type'] = "select";
	    $OPTION['required'] = true;
	    $OPTION['values'] = array();
	    unset($res[0]);
	    foreach($res as $option_value){
	        if(isset($option_value['#text']) && !is_array($option_value['#text'])){
    	        $price = 0;
    	        $name = $option_value['#text'];
    	        $t_res = explode('(' , $name);
    	        if(count($t_res) > 1 && stripos($t_res[1] , ")") > 0){
    	            $name = $t_res[0];
    	            $price = (float) preg_replace("/[^0-9+-.]/", "",  $t_res[1]);
    	        }
    	        $OPTION['values'][] = array('name' => $name , 'price' => $price );
	        }
	    }
	    if(count($OPTION['values']) > 0){
	        $out[] = $OPTION;
	    }
	}
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	return $out;
}

function mspro_lelong_clear_option_value($option_value){
    $option_value = preg_replace("/(\\(.*\\))/U" , "" , $option_value);
    return $option_value;
}

function mspro_lelong_get_option_price($option_value){
    $res = explode('(' , $option_value);
    if(count($res) > 1){
        $res = preg_replace("/[^0-9.-]/", "",  $res[count($res) - 1]);
        if((float) $res > 0){
            return (float) $res; 
        }
    }
    return 0;
}



function mspro_lelong_noMoreAvailable($html){
    if(stripos($html , 'sold out') > 0){
        //echo 'sold out';
        return false;
    }
	return false;
}

