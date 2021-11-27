<?php


function mspro_banggood_title($html){
    $res =  explode('<strong class="title_strong">' , $html);
    if(count($res) > 1){
        $res = explode('</' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
    
	$instruction = 'div.product_layout h1';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
 	if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }
        
    $instruction = 'h1[itemprop=name]';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	// echo '<pre>'.print_r($data , 1).'</pre>';exit;
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $data[0]['#text']));
    }
    if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
        return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }

	$instruction = 'div.pro_right h1';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
 	if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }
    
    $instruction = 'div.title_hd h2';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
        return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
    if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
        return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }
    
    return '';
}

function mspro_banggood_description($html){
	$res = '';
		
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('div.description');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	$temp  = $pq->find('div#tab-description');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	
	$temp = $pq->find('div.good_tabs_box div.list:first');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	/*$temp = $pq->find('div.good_tabs_box div.list:first p:first');
	foreach ($temp as $block){
	    $res .= $temp->html();
	}*/
	//echo $res;exit;

	$res = str_ireplace(array('<div id="coupon_banner"></div>') , array("") , $res);
	$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
	$t_res = explode('<p style="background: url(\'http://img.banggood.com/' , $res);
	if(count($t_res) > 1){
	    $tt_res = explode('</p>' , $t_res[1] , 2);
	    if(count($tt_res) > 1){
	        $res = $t_res[0] . $tt_res[1];
	    }
	}
	
	// images
	preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
	$images = $images[0];
	foreach ($images as $index => $value) {
	    $s = strpos($value, 'data-original="');
	    if($s > 0){
    	    $s = $s + 15;
    	    $e = strpos($value, '"', $s + 1);
    	    $src = substr($value, $s, $e - $s);
    	    if(substr($src , 0 , 2) == "//"){
    	        $src = 'http:' . $src;
    	    }
    	    $res = str_ireplace($value , '<img src="' . $src . '" />' , $res);
	    }
	}
	
	$res = str_ireplace('<div class="loading"></div>' , '' , $res);
	$res = str_ireplace('div class="Compatibility"' , 'div class="Compatibility" style="display:none;visibility:hidden;"' , $res);
	
	// echo $res;exit;
	return $res;
}


function mspro_banggood_price($html){
        $res =  explode('<div class="now" oriPrice="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) trim($price);
            }
        
        }
    
        $res =  explode('<meta itemprop="price" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) trim($price);
            }
        
        }
    
	    $res =  explode(',ecomm_totalvalue:' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) trim($price);
            }
        
        }
    
		$instruction = 'div[itemprop=price]';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($res[0]['oriprice'])) {
	        return (float) trim($res[0]['oriprice']);
	    }elseif (isset($res[0]['#text'])) {
	    	return (float) trim($res[0]['#text']);
        }
        
        $instruction = 'div.pro_price';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if (isset($res[0]['b'][0]['#text'])) {
	    	return (float) trim($res[0]['b'][0]['#text']);
        }
        
		return ''; 
}


function mspro_banggood_sku($html){
        $res =  explode('<span class="sku">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                return trim(str_ireplace(array("SKU") , array(" ") , $res[0]) );
            }
        
        }
	   $instruction = 'span[itemprop=sku]';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if(isset($res[0]['#text']) && !is_array($res[0]['#text']) && strlen($res[0]['#text']) > 1){
            $res = trim( str_ireplace(array("SKU", '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]['#text']));
            return $res;
        }
        
        $res = explode('>SKU:' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $res = trim( str_ireplace(array("SKU" , '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]));
                return $res;
            }
        }
    
		$instruction = 'em#sku';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['#text'])) {
	        $res = trim( str_ireplace(array("SKU", '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]['#text']) );
	    	return $res;
        }
       
		return ''; 
}

function mspro_banggood_model($html){
    $res =  explode('"id": "' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
    $res =  explode('<li class="productid"><b>Product ID: <span>' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
	return mspro_banggood_sku($html);
}


function mspro_banggood_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_banggood_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_banggood_weight($html){
    $out = array();
    //$res = explode('Weight:' , $html);
    $res = explode('<span style="font-size:14px;">Weight</span></td>', $html);
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    if(count($res) > 1){
        $res = explode('</' , $res[1] , 2);
        if(count($res) > 1){
            $weight = strip_tags($res[0]);
            $out['weight_class_id'] = 2;
            if(strpos($weight , "kilogram") > 1 || strpos($weight , "kg") > 1){$out['weight_class_id'] = 1;}
            $out['weight'] = (float) preg_replace("/[^0-9.]/", "",  $weight);;
        }
    }
    // echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}


function mspro_banggood_dimensions($html){
    $out = array();
    $res = explode('<span style="font-size:14px;">Size</span></td>' , $html);
    if(count($res) > 1){
        $res = explode('</' , $res[1] , 2);
        if(count($res) > 1){
            $dims = str_replace("&nbsp;" , "" , strip_tags($res[0]) );
            $t_res = explode("x" , $dims);
            if(count($t_res) > 1){
                $out['length'] = (float) preg_replace("/[^0-9,.]/", "", $t_res[0]);
                $out['width'] = (float) preg_replace("/[^0-9,.]/", "",  $t_res[1]);
            }
            if(isset($t_res[2])){
                $out['height'] = (float) preg_replace("/[^0-9,.]/", "", $t_res[2]);
            }
            $out['length_class_id'] = 1;
            if(strpos($dims , "mm") > 1 ){$out['length_class_id'] = 2; }
            if(strpos($dims , "inch") > 1 ){$out['length_class_id'] = 3; }
        }
    }
    return $out;
}


function mspro_banggood_main_image($html){
	$arr = mspro_banggood_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_banggood_other_images($html){
	$arr = mspro_banggood_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_banggood_get_images_arr($html){
		$out = array();
		
		$instruction = 'div#big img:first';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    if(isset($res[0]['src']) && !is_array($res[0]['src'])){
	    	$out[] = $res[0]['src'];
	    }
		
		$instruction = 'ul#bigImgDatas li img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (is_array($res) && count($res) > 1) {
	    	unset($res[0]);
	    	foreach($res as $k => $v){
	    		if(isset($res[$k]["src"])){
	    			//$out[] = $res[$k]["src"];
	    			$out[] = str_replace('thumb/large', 'images', $res[$k]["src"]);
	    		}
	    	}
        }
        
        $instruction = 'div.good_photo_min ul li a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if (is_array($res) && count($res) > 0) {
	    	foreach($res as $pos_img){
	    		if($pos_img['big']){
	    			$out[] = str_replace('thumb/large', 'images', $pos_img['big']);
	    		}elseif(isset($pos_img['ref'])){
	    			$out[] = str_replace('thumb/large', 'images', $pos_img['ref']);
	    		}
	    	}
        }
        
		$instruction = 'li.pic img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    if (is_array($res) && count($res) > 0) {
	    	foreach($res as $pos_img){
	    		if(isset($pos_img['src']) && stripos($pos_img['src'] , 'grey.gif') < 1){
	    			$out[] = $pos_img['src'];
	    		}
	    	}
	    }
	    
	    
	    $instruction = 'div.image_additional a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (is_array($res) && count($res) > 0) {
	        foreach($res as $pos_img){
	            if(isset($pos_img['data-origin'])){
	                $out[] = $pos_img['data-origin'];
	            }elseif(isset($pos_img['img'][0]['data-normal'])){
	                $out[] = $pos_img['img'][0]['data-normal'];
	            }
	        }
	    }
	    
	    
	    if(count($out) < 1){
	        // echo 'IS';
    	    $instruction = 'div.thumbnail-box ul li';
    	    $parser = new nokogiri($html);
    	    $res = $parser->get($instruction)->toArray();
    	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    	    unset($parser);
    	    if (is_array($res) && count($res) > 0) {
    	        foreach($res as $pos_img){
    	            if(isset($pos_img['data-large'])){
    	                $out[] = $pos_img['data-large'];
    	            }elseif(isset($pos_img['data-src'])){
    	                $out[] = $pos_img['data-src'];
    	            }
    	        }
    	    }
	    }
        
	    $out = clear_images_array($out);
	    /*if(count($out) < 1){
	        echo $html;
	    }
	       echo '<pre>'.print_r($out , 1).'</pre>';exit;*/
	    return $out;
}



function mspro_banggood_options($html){
    $out = array();

    $instruction = 'tr.pro_attr_content';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['th'][0]['span']['#text']) && !is_array($pos_option['th'][0]['span']['#text']) &&  isset($pos_option['td'][0]['ul'][0]['li']) && is_array($pos_option['td'][0]['ul'][0]['li']) && count($pos_option['td'][0]['ul'][0]['li']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['th'][0]['span']['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['td'][0]['ul'][0]['li'] as $option_value){
                    if(isset($option_value['span'][0]['#text']) && !is_array($option_value['span'][0]['#text'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['#text'] , 'price' => 0);
                    }else if(isset($option_value['span'][0]['#text'][0]) && !is_array($option_value['span'][0]['#text'][0])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['#text'][0] , 'price' => 0);
                    }elseif(isset($option_value['span'][0]['img'][0]['title']) && !is_array($option_value['span'][0]['img'][0]['title'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['img'][0]['title'] , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    //echo '<pre>'.print_r($out , 1).'</pre>';
    
    $instruction = 'div.item_box';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        $originalPrice =  mspro_banggood_price($html);
        foreach($res as $pos_option){
            if(isset($pos_option['option_id']) &&
               (isset($pos_option['class']) && strpos($pos_option['class'] , 'attr') > 0) &&
               ( (isset($pos_option['div'][0]['#text']) && !is_array($pos_option['div'][0]['#text'])) || (isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])) ) &&
               isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0)
            {
                    $OPTION = array();
                    if(is_array($pos_option['div'][0]['#text']) && isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])){
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text'][0]);
                    }else{
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text']);
                    }
                    $OPTION['name'] = $name;
                    $OPTION['type'] = "select";
                    $type = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($pos_option['a'] as $option_value){
                        if(isset($option_value['ori_name']) && !is_array($option_value['ori_name'])){
                            $price = 0;
                            if(isset($option_value['oriPrice']) && ((float) $option_value['oriPrice'] < 0 ||  (float) $option_value['oriPrice'] > 0)){
                                $price = (float) $option_value['oriPrice'];
                            }elseif(isset($option_value['oriprice']) && isset($option_value['oriprice']) && isset($option_value['price_prefix']) ){
                                if(strpos($option_value['price_prefix'] , "+") > -1){
                                    $price = (float) $option_value['oriprice'];
                                }else{
                                    $price = 0 - (float) $option_value['oriprice'];
                                }
                            }
                             $vals_arr = array('name' => $option_value['ori_name'] , 'price' => $price);
                             if(isset($option_value['img'][0]['viewimage']) && !is_array($option_value['img'][0]['viewimage']) && strlen(trim($option_value['img'][0]['viewimage'])) > 0){
                                 $vals_arr['image'] = $option_value['img'][0]['viewimage'];
                                 $type = 'image';
                             }
                             $OPTION['values'][] = $vals_arr;
                        }
                    }
                    $OPTION['original_type'] = $type;
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                    }
                }
        }
    }
    
    
    // COLOR NEW
    $instruction = 'div.color_list ul li';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    // echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        $originalPrice =  mspro_banggood_price($html);
        $OPTION = array();
        $OPTION['name'] = "Color";
        $OPTION['type'] = "select";
        $type = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        foreach($res as $option_value){
            if( (isset($option_value['data-name']) && !is_array($option_value['data-name'])) || (isset($option_value['title']) && !is_array($option_value['title'])) ){
                $name = isset($option_value['title'])?$option_value['title']:$option_value['data-name'];
                $vals_arr = array('name' => $name , 'price' => 0);
                if(isset($option_value['data-src']) && !is_array($option_value['data-src']) && strlen(trim($option_value['data-src'])) > 0){
                    $vals_arr['image'] = $option_value['data-src'];
                    $type = 'image';
                }elseif(isset($option_value['a']['img']['src']) && !is_array($option_value['a']['img']['src']) && strlen(trim($option_value['a']['img']['src'])) > 0){
                    $vals_arr['image'] = $option_value['a']['img']['src'];
                    $type = 'image';
                }elseif(isset($option_value['data-large']) && !is_array($option_value['data-large']) && strlen(trim($option_value['data-large'])) > 0){
                    $vals_arr['image'] = $option_value['data-large'];
                    $type = 'image';
                }
                $OPTION['values'][] = $vals_arr;
            }
        }
        $OPTION['original_type'] = $type;
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
    
    
    // Size NEW
    $instruction = 'div.item_size_box div';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    // echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    // $res[0]['div'][0]['div'][0]['span'][0]['em'][0]['#text']
    // $res[4]['div'][0]['ul'][0]['li']
    if (isset($res[0]['div'][0]['div'][0]['span'][0]['em'][0]['#text']) && 
        !is_array($res[0]['div'][0]['div'][0]['span'][0]['em'][0]['#text']) &&
        isset($res[4]['div'][0]['ul'][0]['li']) && 
        is_array($res[4]['div'][0]['ul'][0]['li']) &&
        count($res[4]['div'][0]['ul'][0]['li']) > 0){
        // $originalPrice =  mspro_banggood_price($html);
        $OPTION = array();
        $OPTION['name'] = $res[0]['div'][0]['div'][0]['span'][0]['em'][0]['#text'];
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        foreach($res[4]['div'][0]['ul'][0]['li'] as $option_value){
            if(isset($option_value['data-name']) && !is_array($option_value['data-name'])){
                $OPTION['values'][] =  array('name' => $option_value['data-name'] , 'price' => 0);
            }elseif(isset($option_value['a']['#text']) && !is_array($option_value['a']['#text'])){
                $OPTION['values'][] =  array('name' => $option_value['a']['#text'] , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }else{
        $instruction = 'div.item_size_box ul li.listSize';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        // echo '<pre>'.print_r($res , 1).'</pre>';exit;
        unset($parser);
        if (is_array($res) && count($res) > 0){
            // $originalPrice =  mspro_banggood_price($html);
            $OPTION = array();
            $OPTION['name'] = "Size";
            $OPTION['type'] = "select";
            $OPTION['required'] = true;
            $OPTION['values'] = array();
            foreach($res as $option_value){
                if(isset($option_value['data-name']) && !is_array($option_value['data-name'])){
                    $OPTION['values'][] =  array('name' => $option_value['data-name'] , 'price' => 0);
                }elseif(isset($option_value['a']['#text']) && !is_array($option_value['a']['#text'])){
                    $OPTION['values'][] =  array('name' => $option_value['a']['#text'] , 'price' => 0);
                }
            }
            if(count($OPTION['values']) > 0){
                $out[] = $OPTION;
            }
        }
    }
    
    
    //echo '<pre>S'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}


function mspro_banggood_attributes($html){
    $out = array();
    
    
    $instruction = 'ul.list_filter_show li';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    // echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(count($res) > 1){
        if(isset($res[0]['b']['#text'])){
            $group = $res[0]['b']['#text'];
            unset($res[0]);
            foreach($res as $pos_attr){
                if(isset($pos_attr['span'][0]['#text']) && isset($pos_attr['#text'][0])){
                    $ATTR = array();
                    $ATTR['group'] = $group;
                    $ATTR['name'] = trim(str_replace(":" , "" , $pos_attr['span'][0]['#text']));
                    $ATTR['value'] = trim($pos_attr['#text'][0]);
                    $out[] = $ATTR;
                }
            }
        }
    }

   // echo '<pre>'.print_r($out , 1).'</pre>';exit;

    return $out;
}


function mspro_banggood_noMoreAvailable($html){
    if(stripos($html , 'sold out</ti') > 0 ){
        return true;
    }
	return false;
}

