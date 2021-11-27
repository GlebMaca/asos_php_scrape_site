<?php

function mspro_flipkart_getUrl($url) {
    $html = getUrl($url);
    $addHTML = '';
    $tres = explode('","image":"' , $html);
    if(count($tres) > 1){
        unset($tres[0]);
        foreach($tres as $bl){
            $ttres = explode('"' , $bl , 2);
            $addHTML .= '<img class="addHTML" src="' . $ttres[0] . '" />';
        }
    }
    $html = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $html);
    $html = preg_replace(array("'<object[^>]*?>.*?</object>'si"), Array(""), $html);
    $html .= $addHTML;
    return $html;
}


function mspro_flipkart_title($html){
   // echo $html;exit;
        $out = '';
        
        
        $res = explode('","newTitle":"' , $html);
        if(count($res) > 1){
            $res = explode('",' , $res[1]);
            if(count($res) > 1){
                $out = strip_tags($res[0]);
                return trim($out);
            }
        }
        
        $res = explode('<div class="_3aS5mM"><p>' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1]);
            if(count($res) > 1){
                $out = strip_tags($res[0]);
                return trim($out);
            }
        }
        
        $res = explode('<span class="_35KyD6">' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1]);
            if(count($res) > 1){
                $out = strip_tags($res[0]);
                return trim($out);
            }
        }
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
       // echo '<pre>'.print_r($data , 1).'</pre>';// exit;
        unset($parser);
        if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
        if(isset($data[0]['span'][1]['#text'][0]) && !is_array($data[0]['span'][1]['#text'][0])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][1]['#text'][0]));
        }
        if(isset($data[0]['span'][1]['#text'][1]) && !is_array($data[0]['span'][1]['#text'][1])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][1]['#text'][1]));
        }
        // echo 'OUT:' . strip_tags($out);exit;
        if(strlen($out) > 3){
            return $out;
        }
        
		$instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	$out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	$out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }else{
            $instruction = 'h1';
            $parser = new nokogiri($html);
            $data = $parser->get($instruction)->toArray();
            //echo '<pre>'.print_r($data , 1).'</pre>';exit;
            unset($parser);
            if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
                $out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
            }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
                $out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
            }
        }
        
        $instruction = 'span.subtitle';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
    	return $out;
}

function mspro_flipkart_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
			
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#keyFeatures');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#specifications');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div.g2dDAR');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		}
		
		$temp  = $pq->find('div._2ixwsm');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		}
		
		$temp  = $pq->find('div._1y9a40');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		}
		
		$temp  = $pq->find('div._2GNeiG');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		}
		if(strlen($res) < 5){
		    $temp  = $pq->find('div._29BZlt div.row:first');
		    foreach ($temp as $block){
		        $res .= '<div>' . $temp->html().'</div>';
		    }
		}
		
		
		
		$temp  = $pq->find('div.MocXoX');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		}
		
        $temp  = $pq->find('div.productSpecs');
		$css = '.specTable{width:100%;font-size:13px;margin:0 0 30px 0}.specTable td,.specTable th{padding:6px;vertical-align:top;text-align:left}.specTable .groupHead{background-color:#f2f2f2;font-weight:bold;text-transform:uppercase}.specTable .specsKey{width:25%;border-bottom:1px dotted #c9c9c9;border-right:1px solid #c9c9c9}.specTable .specsValue{border-bottom:1px dotted #c9c9c9;border-left:1px solid #c9c9c9}.specTable td:only-child{border-left:none;border-right:0}.keyFeaturesList{list-style-type:disc;padding-left:20px}';
		$css = '<style type="text/css">'.$css.'</style>';
		$i = 0;
		foreach ($temp as $block){
			$res .= $temp->html() . '<br />';
			$i++;
		}
		if($i > 0){
		    $res .= $css;
		}
		
		$res = str_ireplace(array('_1kyh2f"' , '_1BMpvA"') , array('" style="display:inline-block;font-weight:bold;"' , '" style="display:inline-block;"') , $res);
		$res = str_ireplace(array('Read More' , 'button') , array("" , "span") , $res);
		$res = str_ireplace(array('Manufacturing, Packaging and Import Info' , 'button') , array("" , "span") , $res);
		
	   // echo $res;exit;
	
		return $res;
}


function mspro_flipkart_price($html){
    // echo $html;exit;
        $res = explode('-->₹' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
        $res = explode(',"price":' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
		$instruction = 'meta[itemprop=price]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
	    unset($parser);
	    if (isset($data[0]['content']) && !is_array($data[0]['content'])) {
	    	$price = preg_replace("/[^0-9.]/", "",  trim($data[0]['content']) );
	    	return (float) $price;
        }
        
        $instruction = 'span.selling-price';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $price = str_replace(array("Rs.") , array("") , trim($data[0]['#text']) );
            $price = str_replace(array(",") , array(".") , $price );
            $price = preg_replace("/[^0-9.]/", "",  $price );
            return (float) $price;
        }
        
        $instruction = 'span#exchangePrice';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $price = str_replace(array(",") , array(".") , trim($data[0]['#text']) );
            $price = preg_replace("/[^0-9.]/", "",  $price );
            return (float) $price;
        }
        
        $res = explode('3O-aY3"><span>₹<!-- -->' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
        $res = explode('"SPECIAL_PRICE","strikeOff":false,"value":' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode('}' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
        $res = explode('"prexoAvailable":false,"price":' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
        $res = explode('<div class="_1vC4OE _3qQ9m1">₹' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
        $res = explode('<span class="_18Zlgn">₹<!-- -->' , $html);
        // echo count($res);exit;
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  strip_tags($res[0]) );
                return (float) $price;
            }
        }
        
		return '';
}


function mspro_flipkart_meta_description($html){
	   $res =  explode('<meta name="Description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_flipkart_meta_keywords($html){
       $res =  explode('<meta name="Keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return mspro_flipkart_meta_description($html);
}

function mspro_flipkart_model($html){
    $res = explode('<td class="specsKey">Model Name</td>' , $html);
    if(count($res) > 1){
        $res = explode('</td>' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    $res = explode('Model Number</td>' , $html);
    if(count($res) > 1){
        $res = explode('</' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    $res = explode('<td class="specsKey">ISBN-13</td>' , $html);
    if(count($res) > 1){
        $res = explode('</td>' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    return mspro_flipkart_sku($html);
}

function mspro_flipkart_sku($html){
    $res = explode('&sku[0]=' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    return '';
}

function mspro_flipkart_manufacturer($html){
    $res =  explode('"brand":{"@type":"Thing","name":"', $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('"},"brand":"', $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('"brand":{"@type":"Thing","name":"', $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('"brand": "' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    return '';
}


function mspro_flipkart_main_image($html){
    $arr = mspro_flipkart_get_images_arr($html);
    if(isset($arr[0]) && strlen($arr[0]) > 0){
        return $arr[0];
    }
    return '';
}


function mspro_flipkart_other_images($html){
    $arr = mspro_flipkart_get_images_arr($html);
    if(count($arr) > 1){
        unset($arr[0]);
        return $arr;
    }
    return array();
}


function mspro_flipkart_get_images_arr($html){
    // echo $html;
    $out = array();
    
    $instruction = 'div.image-wrapper img';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (isset($res[0]['src'])) {
        $main_image = trim($res[0]['src']);
        $out[] = $main_image;
    }
    if (isset($res[0]['data-zoom-src']) && !empty($res[0]['data-zoom-src'])) {
        $main_image = trim($res[0]['data-zoom-src']);
        $out[] = $main_image;
    }
    
    $instruction = 'div#mprodimg-id img';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (isset($res[0]['data-src'])) {
        $main_image = trim($res[0]['data-src']);
        $out[] = $main_image;
    }
    
    $instruction = 'div#multi-images img';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';
    unset($parser);
    if (isset($res[0]['src'])) {
        foreach($res as $oth_imgs){
            if(isset($oth_imgs['src']) && strpos($oth_imgs['src'] , 'ajax-loader') < 1){
                $out[] = flipkart_try_get_bigger($oth_imgs['src']);
            }
        }
    }
    if (isset($res[0]['data-carousel-src'])) {
        foreach($res as $oth_imgs){
            if(isset($oth_imgs['data-carousel-src']) && strpos($oth_imgs['data-carousel-src'] , 'ajax-loader') < 1){
                $out[] = flipkart_try_get_bigger($oth_imgs['data-carousel-src']);
            }
        }
    }
     
     
    $instruction = 'div.imgWrapper img';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';
    unset($parser);
    if (isset($res) && is_array($res) && count($res) > 1) {
        unset($res[0]);
        foreach($res as $pos_img){
            if(isset($pos_img['data-zoomimage']) && !is_array($pos_img['data-zoomimage'])  ){
                $out[] = $pos_img['data-zoomimage'];
            }elseif(isset($pos_img['data-src']) && !is_array($pos_img['data-src'])  ){
                $out[] = $pos_img['data-src'];
            }
        }
    }
    
    $instruction = 'img.sfescn';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    // echo '<pre>'.print_r($res , 1).'</pre>';
    unset($parser);
    if (isset($res) && is_array($res) && count($res) > 0) {
        foreach($res as $pos_img){
            if(isset($pos_img['src']) && !is_array($pos_img['src'])  ){
                $out[] = $pos_img['src'];
            }
        }
    }
    
    $res = explode('"contentType":"IMAGE","source":"ORGANIC","url":"' , $html);
    if(count($res) > 1){
        unset($res[0]);
        foreach($res as $bl){
            $tres = explode('"' , $bl , 2);
            if(count($tres) > 1){
                $out[] = str_ireplace(array('/{@width}/{@height}') , array("") , $tres[0]);
            }
        }
    }
    // echo '<pre>'.print_r($out , 1).'</pre>';exit;
    
    $res = explode('/{@width}/{@height}/' , $html);
    if(count($res) > 1){
        unset($res[0]);
        foreach($res as $bl){
            $tres = explode('"' , $bl , 2);
            if(count($tres) > 1){
                $out[] = 'http://rukmini1.flixcart.com/image/' . $tres[0];
            }
        }
    }
    // echo '<pre>'.print_r($out , 1).'</pre>';exit;
    
    $res = explode('/128/128/' , $html);
    if(count($res) > 1){
        unset($res[0]);
        foreach($res as $bl){
            $tres = explode('"' , $bl , 2);
            if(count($tres) > 1){
                $out[] = 'http://rukmini1.flixcart.com/image/' . $tres[0];
            }
        }
    }
    
   // echo $html;
    //print_r($out);
    if(count($out) < 1){
        $tres = explode('<img class="addHTML" src="' , $html);
        echo count($tres);
        if(count($tres) > 1){
            unset($tres[0]);
            foreach($tres as $bl){
                $ttres = explode('"' , $bl , 2);
                if(count($ttres) > 1){
                    $out[] = str_ireplace("128/128/" , "" , $ttres[0]);
                }
            }
        }
    }
    //print_r($out);exit;
     
    $out = clear_images_array($out);
    foreach($out as $k => $v){
        $out[$k] = str_ireplace(array('?q={@quality}' , '?q=70' , ')') , '' , $v);
    }
    
    // echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}


 function flipkart_try_get_bigger($src){
 	return str_replace(array("100x100") , array("400x400") , $src);
 }
 
 
 function mspro_flipkart_options($html){
     $out = array();
 
    $instruction = 'div.multiSelectionWidget';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
		    // COLOR OPTION
			if(isset($pos_option['div'][0]['#text']) &&
			   !is_array($pos_option['div'][0]['#text']) &&
			   strlen(trim($pos_option['div'][0]['#text'])) > 0 &&
			   isset($pos_option['div'][1]['div'][0]['title']) &&
			   !is_array($pos_option['div'][1]['div'][0]['title']) &&
			   strlen(trim($pos_option['div'][1]['div'][0]['title'])) > 0 ){
        				$OPTION = array();
        				$OPTION['name'] = str_replace( array(":" , "Select") , array("" , "") , trim($pos_option['div'][0]['#text']) );
        				$OPTION['type'] = "select";
        				$OPTION['required'] = true;
        				$OPTION['values'] = array();
        				$pos_options = array();
        				$pos_options[] = $pos_option['div'][1]['div'][0]['title'];
        				if(isset($pos_option['div'][1]['a']) && is_array($pos_option['div'][1]['a']) && count($pos_option['div'][1]['a']) > 0){
        				    foreach($pos_option['div'][1]['a'] as $pos_adiitional_option){
        				        if(isset($pos_adiitional_option['div'][0]['title']) && !is_array($pos_adiitional_option['div'][0]['title']) && strlen(trim($pos_adiitional_option['div'][0]['title'])) > 0){
        				            $pos_options[] = $pos_adiitional_option['div'][0]['title'];
        				        }
        				    }
        				}
        				foreach($pos_options as $option_value){
        					$OPTION['values'][] = array('name' => $option_value , 'price' => 0);
        				}
        				if(count($OPTION['values']) > 0){
        					$out[] = $OPTION;
        				}
			}elseif(isset($pos_option['div'][0]['#text']) &&
    			   !is_array($pos_option['div'][0]['#text']) &&
    			   strlen(trim($pos_option['div'][0]['#text'])) > 0 &&
    			   isset($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue']) &&
    			   !is_array($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue']) &&
    			   strlen(trim($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue'])) > 0 ){
            				$OPTION = array();
            				$OPTION['name'] = str_replace( array(":" , "Select" , "&nbsp;" , "\n" , "\r") , array("" , "" , "" , "" , "") , trim($pos_option['div'][0]['#text']) );
            				$OPTION['type'] = "select";
            				$OPTION['required'] = true;
            				$OPTION['values'] = array();
            				$pos_options = array();
            				$pos_options[] = $pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue'];
            				if(isset($pos_option['div'][1]['a']) && is_array($pos_option['div'][1]['a']) && count($pos_option['div'][1]['a']) > 0){
            				    foreach($pos_option['div'][1]['a'] as $pos_adiitional_option){
            				        if(isset($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue']) && !is_array($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue']) && strlen(trim($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue'])) > 0){
            				            $pos_options[] = $pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue'];
            				        }
            				    }
            				}
            				foreach($pos_options as $option_value){
            					$OPTION['values'][] = array('name' => $option_value , 'price' => 0);
            				}
            				if(count($OPTION['values']) > 0){
            					$out[] = $OPTION;
            				}
			}
		}
	}
	
	$instruction = 'div.rPoo01 div._2a2WU_';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	// echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['span'][0]['#text']) && !is_array($pos_option['span'][0]['#text']) && isset($pos_option['ul'][0]['li']) && is_array($pos_option['ul'][0]['li']) && count($pos_option['ul'][0]['li']) > 0){
	            $OPTION = array();
	            $OPTION['name'] = trim($pos_option['span'][0]['#text']);
	            $OPTION['type'] = "select";
	            $OPTION['required'] = true;
	            $OPTION['values'] = array();
	            foreach($pos_option['ul'][0]['li'] as $option_value){
	                if(isset($option_value['div'][0]['div']['#text']) && !is_array($option_value['div'][0]['div']['#text']) && strlen(trim($option_value['div'][0]['div']['#text'])) > 0){
	                    $OPTION['values'][] = array('name' => trim($option_value['div'][0]['div']['#text']) , 'price' => 0);
	                }elseif(isset($option_value['div'][0]['div']['#text'][0]) && !is_array($option_value['div'][0]['div']['#text'][0]) && strlen(trim($option_value['div'][0]['div']['#text'][0])) > 0){
	                    $OPTION['values'][] = array('name' => trim($option_value['div'][0]['div']['#text'][0]) , 'price' => 0);
	                }
	            }
	            if(count($OPTION['values']) > 0){
	                $out[] = $OPTION;
	            }
	        }
	    }
	}
	
	
     // echo '<pre>'.print_r($out , 1).'</pre>';exit;
     return $out;
 }
 
 
 function mspro_flipkart_noMoreAvailable($html){
	if(strpos($html , "out-of-stock-status") > 0){
		return true;
	}
	return false;
}
 
