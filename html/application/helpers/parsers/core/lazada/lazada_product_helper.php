<?php


function mspro_lazada_title($html){
    $res = explode('<span class="pdp-mod-product-badge-title">' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('<title>' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $res = explode('|' , strip_tags($res[0]) , 2);
                return trim($res[0]);
            }
        }
        $res = explode('<meta name="og:title" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
    //echo $html;exit;
		$instruction = 'h1#prod_title';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'])));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
	    	return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0])));
        }
        
        $instruction = 'h1[itemprop=name]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'])));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0])));
        }
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'])));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0])));
        }
        
        $instruction = 'div.product-info-name';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'])));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return utf8_decode(trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0])));
        }
         
        return '';
}

function mspro_lazada_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#productDetails');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$temp  = $pq->find('div.pdp-product-highlights');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		
		$desc_exists = array();
		$temp  = $pq->find('div.description-detail');
		foreach ($temp as $block){
		    $temp_block = $temp->html();
		    if(!in_array($temp_block , $desc_exists)){
		        $res .= '<div>' . $temp_block . '</div>';
		        $desc_exists[] = $temp_block;
		    }
		}
		
		$temp  = $pq->find('div.product-description__inbox');
		foreach ($temp as $block){
		    $res .= '<div><h2 class="product-description__title">Specifications of ' . mspro_lazada_title($html) . '</h2>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('table.specification-table');
		foreach ($temp as $block){
		    $res .= '<div><h3>General Features:</h3><table class="specification">' . $temp->html() . '</table></div>';
		}
		
		
		$temp  = $pq->find('div.specification-table');
		foreach ($temp as $block){
		    $res .= '<div><p><strong>Product and Specification:</strong></p><ul>' . $temp->html() . '</ul></div>';
		}
		
		
		// SPECS and WHAT'S in the BOX
		$temp  = $pq->find('ul.specification-keys');
		foreach ($temp as $block){
		    $res .= '<div><ul>' . $temp->html() . '</ul></div>';
		}
		$temp  = $pq->find('div.box-content');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}

		
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		$res = str_replace(array("<noscript>", "</noscript>") , array("" , "") , $res);
		//echo $res;exit;
		
		
		// WORKING WITH IMAGES
		// get images array
		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
		//echo '<pre>'.print_r($images[0] , 1).'</pre>';exit;
		if(isset($images[0] ) && is_array($images[0] ) && count($images[0] ) > 0){
    		foreach ($images[0] as $index => $value) {
    		    $s = strpos($value, 'src="') + 5;
    		    $e = strpos($value, '"', $s + 1);
    		    if($s > 5){
    		        $res = str_ireplace($value , '<img src="' . substr($value, $s, $e - $s) . '" alt="" />' , $res);
    		    }else{
    		        $res = str_ireplace($value , "" , $res);
    		    }
    		}
		}
		
		// highlights
		$tres = explode(',"highlights":"' , $html);
		if(count($tres) > 1){
		    $tres = explode('","' , $tres[1] , 2);
		    if(count($tres) > 1){
		        $res .= '<div>' . $tres[0] . '</div>';
		    }
		}
		
		// description by ajax
		$tres = explode('","pageUrl":"//' , $html);
		if(count($tres) > 1){
		   // echo '1';
		    $tres =  explode('"' , $tres[1]);
		    //echo 'https://' . $tres[0];
		    $ajaxHtml  = getUrl('https://' . $tres[0]);
		    //echo $ajaxHtml;
		    if($ajaxHtml){
		        //echo '2';
		        $ttres = explode('"html":"' , $ajaxHtml , 2);
		        if(count($ttres) > 1){
		            // echo '3';
		            $ttres =  explode('","' , $ttres[1] , 2);
		            $res .= stripslashes(str_replace(array('\\r' , '\\n' , '\\t') , '' , $ttres[0]));
		        }else{
		            $tempHTML = (array) json_decode('[' . $ajaxHtml . ']' , 1);
		            //echo '<pre>'.print_r($tempHTML[0]['result']['components'] , 1).'</pre>';//exit;
		            if(isset($tempHTML[0]['result']['components'] ) && is_array($tempHTML[0]['result']['components'] ) && count($tempHTML[0]['result']['components'] ) > 0){
		                $IMGS = array();
		                foreach($tempHTML[0]['result']['components'] as $key => $component){
		                    if(isset($component['moduleData']['schema']['children'] ) && is_array($component['moduleData']['schema']['children']) && count($component['moduleData']['schema']['children']) > 0){
		                        foreach($component['moduleData']['schema']['children'] as $pos_IMG){
		                            if(isset($pos_IMG['src'])){
		                                $IMGS[] = $pos_IMG['src'];
		                            }
		                        }
		                    }
		                }
		                if(count($IMGS) > 0){
		                    foreach($IMGS as $IMG){
		                        $res .= '<img src="' . $IMG . '" /><br />';
		                    }
		                }
		            }
		        }
		    }
		}
		
		
        // another type of description
		$tres = explode(',"@type":"Product","description":"' , $html);
		if(count($tres) > 1){
		    $tres = explode('","' , $tres[1] , 2);
		    if(count($tres) > 1){
		        $res .= '<div>' . stripslashes(str_replace(array('\\r' , '\\n' , '\\t') , '' , $tres[0])) . '</div>';
		    }
		}
		
		
		$tres = explode(',"desc":"' , $html);
		if(count($tres) > 1){
		    $tres = explode('","' , $tres[1] , 2);
		    if(count($tres) > 1){
		        $res .= '<div>' . stripslashes(str_replace(array('\\r' , '\\n' , '\\t') , '' , $tres[0])) . '</div>';
		    }
		}

		
	   //echo 'RES:' . $res;exit;
	
		return $res;
}


function mspro_lazada_price($html){
    $res = explode('<p class="product-price">' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('","price":' , $html, 2);
    if(count($res) > 1){
        $res = explode(',' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode(',"salePrice":{"text":"RM' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
        $res = explode('<span class="product-price">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]); 
        		return (float) $price;
            }
        }
	   $res = explode('id="special_price_box">' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        	    $res = preg_replace("/[^0-9.]/", "",  $res[0]); 
        		return (float) $res;
        	} 
        }
        $res = explode('id="product_price" class="hidden">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $res;
            }
        }
        $res = explode('itemprop="price" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $res;
            }
        }
        return '';
}


function mspro_lazada_sku($html){
        $res = explode('"pdt_simplesku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('"pdt_sku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('"sku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('data-simple-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('data-config-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        
        return '';
}

function mspro_lazada_model($html){
		 $res = explode('<td class="bold">Model</td>' , $html);
        if(count($res) > 1){
            $res = explode('</td>' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        return mspro_lazada_sku($html);
}

function mspro_lazada_weight($html){
		$out = array();
			
		$instruction = 'div[itemprop=weight] meta[itemprop=value]';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		unset($parser);
		if(isset($data[0]['content']) && !is_array($data[0]['content']) && strlen(trim($data[0]['content'])) > 0){
		    $res = preg_replace("/[^0-9.]/", "",  $data[0]['content']); 
		    $out['weight'] = (float) $res;
		    $out['weight_class_id'] = 1;
		}
        
        return $out;
}


function mspro_lazada_meta_description($html){
	  $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;" , 'lazada' , "Lazada") , array(" " , "`" , "" , "") , $res[0]);
			}	 
       }
       $res =  explode('<meta name="description"' , $html);
       if(count($res) > 1){
           $res = str_ireplace(array('content="') , array(""), $res[1]);
           $res = explode('"' , trim($res) );
           if(count($res) > 1){
               return str_replace(array("&nbsp;" , "&amp;" , 'lazada' , "Lazada") , array(" " , "`" , "" , "") , $res[0]);
           }
       }
       return '';
}

function mspro_lazada_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       $res =  explode('<meta name="keywords"' , $html);
       if(count($res) > 1){
           $res = str_ireplace(array('content="') , array(""), $res[1]);
           $res = explode('"' , trim($res) );
           if(count($res) > 1){
               return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
           }
       
       }
       return  mspro_lazada_meta_description($html);
}


function mspro_lazada_main_image($html){
	$imgs_arr = mspro_lazada_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 0){
	    // echo 'URL:' . $imgs_arr[0];exit;
		return $imgs_arr[0];
	}
	return '';	
}



function mspro_lazada_other_images($html){
	$imgs_arr = mspro_lazada_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 1){
		unset($imgs_arr[0]);
		return $imgs_arr;
	}
	return array();
}


function mspro_lazada_images_array($html){
    //echo $html;exit;
    $out = array();
    $exists = array();

    $instruction = 'ul.prd-moreImagesList li';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data[0]['div']) && is_array($data[0]['div']) && count($data[0]['div']) > 0){
        foreach($data[0]['div'] as $pos_image){
            if(isset($pos_image['div'][0]['data-zoom-image']) && !is_array($pos_image['div'][0]['data-zoom-image'])){
                $out[] = $pos_image['div'][0]['data-zoom-image'];
            }elseif(isset($pos_image['div'][0]['data-big']) && !is_array($pos_image['div'][0]['data-big'])){
                $out[] = $pos_image['div'][0]['data-big'];
            }elseif(isset($pos_image['div'][0]['data-swap-image']) && !is_array($pos_image['div'][0]['data-swap-image'])){
                $out[] = $pos_image['div'][0]['data-swap-image'];
            }
        }
    }

    $instruction = 'ul.prd-moreImagesList li';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data[0]['span']) && is_array($data[0]['span']) && count($data[0]['span']) > 0){
        foreach($data[0]['span'] as $pos_image){
            if(isset($pos_image['span'][0]['data-zoom-image']) && !is_array($pos_image['span'][0]['data-zoom-image'])){
                $out[] = $pos_image['span'][0]['data-zoom-image'];
            }elseif(isset($pos_image['span'][0]['data-swap-image']) && !is_array($pos_image['span'][0]['data-swap-image'])){
                $out[] = $pos_image['span'][0]['data-swap-image'];
            }
        }
    }

    $instruction = 'div.swiper-slide img';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data) && is_array($data) && count($data) > 0){
        foreach($data as $pos_img){
            if(isset($pos_img['data-largeurl']) && !is_array($pos_img['data-largeurl']) && strlen(trim($pos_img['data-largeurl'])) > 0){
                $out[] = $pos_img['data-largeurl'];
            }
        }
    }

    $instruction = 'div.product-image-container img';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen(trim($data[0]['src'])) > 0){
        $out[] = $data[0]['src'];
    }


    // color images
    $instruction = 'ul.prd-colorList li';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data) && is_array($data) && count($data) > 0){
        foreach($data as $pos_image){
            if(isset($pos_image['a'][0]['span'][0]['img'][0]['data-large-image']) && !is_array($pos_image['a'][0]['span'][0]['img'][0]['data-large-image'])){
                $out[] = $pos_image['a'][0]['span'][0]['img'][0]['data-large-image'];
            }
        }
    }

    $instruction = 'ul.prd-moreImagesList div.productImage';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data) && is_array($data) && count($data) > 0){
        foreach($data as $pos_img){
            if(isset($pos_img['data-zoom-image']) && !is_array($pos_img['data-zoom-image']) && strlen(trim($pos_img['data-zoom-image'])) > 0){
                $out[] = $pos_img['data-zoom-image'];
            }elseif(isset($pos_img['data-big']) && !is_array($pos_img['data-big']) && strlen(trim($pos_img['data-big'])) > 0){
                $out[] = $pos_img['data-big'];
            }
        }
    }

    $instruction = 'img.pdp-mod-common-image';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';
    unset($parser);
    if(isset($data) && is_array($data) && count($data) > 0){
        foreach($data as $pos_img){
            if(isset($pos_img['src']) && !is_array($pos_img['src']) && strlen(trim($pos_img['src'])) > 0){
                $baseSRC = 'http:' . str_replace(array('_720x720q75.jpg' , '_120x120q75.jpg') , "" , $pos_img['src']);
                if(!in_array($baseSRC , $exists)){
                    $exists[] = $baseSRC;
                    $out[] = 'http:' . str_replace(array('_120x120q75.jpg') , "_720x720q75.jpg" , $pos_img['src']);
                }
            }
        }
    }

    // echo '<pre>'.print_r($out , 1).'</pre>';
    if(count($out) < 1){
        $res = explode('"skuGalleries":' , $html);
        if(count($res) > 1){
            $res = explode(']},"' , $res[1] , 2);
           // echo '[' . $res[0] . ']}]';
            $res =  (array) json_decode( '[' . $res[0] . ']}]' , 1);
            //echo '<pre>'.print_r($res , 1).'</pre>';exit;
            if(isset($res[0]) && is_array($res[0]) && count($res[0]) > 0){
                foreach($res[0] as $k => $val){
                    if(is_array($val) && count($val) > 0){
                        foreach($val as $key => $valuesArr){
                            if(isset($valuesArr['src'])){
                                if(substr($valuesArr['src'] , 0 , 2) == "//"){
                                    $valuesArr['src'] = 'http:' . $valuesArr['src'];
                                }
                                if(!in_array($valuesArr['src'] , $exists)){
                                    $out[] = $valuesArr['src'];
                                }
                            }elseif(isset($valuesArr['poster'])){
                                if(substr($valuesArr['poster'] , 0 , 2) == "//"){
                                    $valuesArr['src'] = 'http:' . $valuesArr['poster'];
                                }
                                if(!in_array($valuesArr['poster'] , $exists)){
                                    $out[] = $valuesArr['poster'];
                                }
                            }
                        }
                    }
                    
                }
            }
        }
    }
    
    
    $realout = array();
    if(count($out) > 1){
        foreach($out as $k => $v){
            if(stripos($v , 'youtube.com') < 1 && stripos($v , 'youtu.be') < 1 ){
                $realout[]= $v;
            }
        }
    }

    $realout = array_unique($realout);
   //echo '<pre>'.print_r($realout , 1).'</pre>'; exit;

    return $realout;
}



function mspro_lazada_options($html){
    $out = array();


    // ONLY SIZE OPTION
    $instruction = 'span.select_product-size';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
        $OPTION = array();
        $OPTION['name'] = str_replace( array(":") , array("") , 'Size' );
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        $option_values = $res[0]['select'][0]['option'];
        unset($option_values[0]);
        foreach($option_values as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text'])){
                $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }

    // echo '<pre>'.print_r($out , 1).'</pre>';exit;

    $res = explode('"skuBase":{"properties":' , $html);
    if(count($res) > 1){
        $res = explode('],"' , $res[1] , 2);
        //echo $res[0] . ']';
        $res =  (array) json_decode($res[0] . ']' , 1);
        //echo '<pre>'.print_r($res , 1).'</pre>';
        if(count($res) > 0){
            foreach($res as $pos_opt){
                if(isset($pos_opt['name']) && isset($pos_opt['values'][0]['value']) && count($pos_opt['values'][0]['value']) > 0){
                    $OPTION = array();
                    $OPTION['name'] = str_replace( array(":") , array("") , $pos_opt['name'] );
                    $type = "select";
                    $originalType = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($pos_opt['values'][0]['value'] as $option_value){
                        if(isset($option_value['name']) && !is_array($option_value['name']) && trim($option_value['name']) !== "…"){
                            $OPTION['values'][] = array('name' => trim($option_value['name']) , 'price' => 0);
                        }
                    }
                    $OPTION['type'] = $type;
                    $OPTION['original_type'] = $originalType;
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                    }
                }elseif(isset($pos_opt['name']) && isset($pos_opt['values']) && count($pos_opt['values']) > 0){
                    $OPTION = array();
                    $OPTION['name'] = str_replace( array(":") , array("") , $pos_opt['name'] );
                    $type = "select";
                    $originalType = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($pos_opt['values'] as $option_value){
                        if(isset($option_value['name']) && !is_array($option_value['name']) && trim($option_value['name']) !== "…"){
                            $OPTS = array('name' => trim($option_value['name']) , 'price' => 0);
                            if(isset($option_value['image'])){
                                $OPTS['image'] = substr($option_value['image'] , 0 , 2) == '//'?'http:' . $option_value['image'] : $option_value['image'];
                                $originalType = "image";
                            }
                            $OPTION['values'][] = $OPTS;
                        }
                    }
                    $OPTION['type'] = $type;
                    $OPTION['original_type'] = $originalType;
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                    }
                }
            }
        }
    }

    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}



/*
function mspro_lazada_noMoreAvailable($html){
	return false;
}
*/
