<?php


function mspro_walmart_title($html){
		$instruction = 'h1.productTitle';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['#text'])) );
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['#text'][0])) );
        }
        
		if (isset($data[0]['span'][1]['#text']) && !is_array($data[0]['span'][1]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['span'][1]['#text'])) );
        }
		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['span'][0]['#text'])) );
        }
        
        
		$instruction = 'h1.product-name';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['#text'])) );
        }
		if (isset($data[0]['span'][1]['#text']) && !is_array($data[0]['span'][1]['#text']) && strlen(trim($data[0]['span'][1]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['span'][1]['#text'])) );
        }
		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text']) && strlen(trim($data[0]['span'][0]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['span'][0]['#text'])) );
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 0) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['#text'][0])) );
        }
        
        
        $instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if (isset($data[0]['div']['#text']) && !is_array($data[0]['div']['#text'])) {
	        return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['div']['#text'])) );
	    }elseif (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , mspro_walmart_clear_names($data[0]['#text'])) );
        }
        
        return '';
}

function mspro_walmart_description($html){
    
    $res = '';
    //echo $html;
    $html = htmlspecialchars_decode($html);
		$existsBlock = array();
		$specsExists = false;
		$pq = phpQuery::newDocumentHTML($html);
		
		// Description
		$temp  = $pq->find('div[itemprop=description]');
		foreach ($temp as $block){
			$bl = trim($temp->html());
			$existsBlock[] = $bl;
			$res .= $bl;
			$specsExists = true;
		}
		
		
		
		$temp  = $pq->find('div.about-product-section div.about-item-complete');
		foreach ($temp as $block){
			$bl = trim($temp->html());
			if(!in_array($bl , $existsBlock)){
    			$existsBlock[] = $bl;
    			$res .= $bl;
			}
		}
		
		$temp  = $pq->find('div.about-desc');
		foreach ($temp as $block){
		    $bl = trim($temp->html());
		    if(!in_array($bl , $existsBlock) && $specsExists === false){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		//echo $res;exit;
	    	
		// Specification
		$temp  = $pq->find('table.SpecTable');
		foreach ($temp as $block){
		    $bl = trim($temp->html());
		    if(!in_array($bl , $existsBlock) ){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		
		$temp  = $pq->find('section#productSpecs');
		foreach ($temp as $block){
		    $bl = trim($temp->html());
		    if(!in_array($bl , $existsBlock)){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		$temp  = $pq->find('div.Specifications');
		foreach ($temp as $block){
		    $bl = trim($temp->html());
		    if(!in_array($bl , $existsBlock)){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		
		
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
		    $bl =  trim($temp->html());
		    if(!in_array($bl , $existsBlock)){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		
		$temp  = $pq->find('div#product-characteristics');
		foreach ($temp as $block){
		    $bl =  trim($temp->html());
		    if(!in_array($bl , $existsBlock)){
		        $existsBlock[] = $bl;
		        $res .= '<div>' . $bl . '</div>';
		    }
		}
		
		
		$t_res =  explode('"ShortDescription":{"product_short_description":{"values":["' , $html);
		if(count($t_res) > 1 && $specsExists === false){
		    $t_res = explode('"]' , $t_res[1]);
		    if(count($t_res) > 1){
		        $bl = trim( $t_res[0] );
		        if(!in_array($bl , $existsBlock)){
		            $existsBlock[] = $bl;
		            $res .= '<div>' . $bl . '</div>';
		        }
		    }
		}
		//echo $res;exit;
		
		$res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $res);
		$res = str_replace(array('<div class="js-show-more-trigger show-more"><span>Show </span></div>' , '<section class="product-specs-section"><section class="product-specs"><div class="js-idml-video-container" data-idml-host="//www.walmart-content.com"></div></section>' , '</div> <div class="js-show-more-trigger show-more caret"><span>Show </span></div>'), array("" , "" , ""), $res);
		
		// remove "more" button
		$t_res = explode('<button type="button" class="more-characteristics btn-link">' , $res);
		if(is_array($t_res) && count($t_res) > 1){
		    $tt_res = explode('</button>' , $t_res[1]);
		    if(is_array($tt_res) && count($tt_res) > 1){
		        $res = $t_res[0] . $tt_res[1];
		    }
		}
		
		//remove MANULAS section
		$res = preg_replace(array("'<section class=\"product-specs-section slick-module\"><h3 class=\"idml-documents-main-title\">Manuals[^>]*?>.*?</section>'si"), Array(""), $res);
		$res = mspro_walmart_clear_names($res);
		
		
		// ADD MEDIA BLOCK
		$res .= mspro_walmart_addMediaBlock($html);
		
		// echo $res;exit;
		
		return $res;
}

function mspro_walmart_clear_names($str){
    return trim(str_ireplace( array("at Walmart.com" , "at Wal-mart.com" , "Walmart.com" , "Wal-mart.com" , "at Walmart" , "at Wal-mart" , "Walmart" , "Wal-mart") , array("") , $str));
}


function mspro_walmart_addMediaBlock($html){
    $res = '';
    
    $tres = explode('div%20id%3D%5C%22media-' , $html);
    if(count($tres) > 1){
        $tres = explode('%' , $tres[1] , 2);
        if(count($tres) > 1){
            $id = $tres[0];
            $html = urldecode($html);
            $html = str_ireplace(array( '\\n' , '\\t') , " ", $html);
            $html = stripslashes($html);
            $pq = phpQuery::newDocumentHTML($html);
            // Description
            
            
            
            $temp  = $pq->find('div#inline-' . $id);
            $pq->find('div.ccs-cc-ig-cloud')->remove();
            foreach ($temp as $block){
                $res .= $temp->html();
            }
            
            $res = str_ireplace(array('src="data:image/gif;base64,"' , 'data-lazy' , 'data-src') , array('' , 'src' , 'src') , $res);
            $res = stripslashes($res);
            // $res .= '<script type="text/javascript" src="https://cdn.cnetcontent.com/static/pe/190527/ccsVideoPlayer/ccs-video-player.min.js" ></script>';
            return $res;
        }
    }
    
   
}

function mspro_walmart_price($html){
    // echo $html;exit;
    $res = explode('{"priceType":"REDUCED","price":' , $html);
    if(count($res) > 1){
        $res = explode(',' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('<span class="price-characteristic" itemprop="price" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('<span class="Price-characteristic" itemprop="price" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('","price":' , $html);
    if(count($res) > 1){
        $res = explode(',' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('":{"CURRENT":{"price":' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
	$res = explode('"price_store_price":["' , $html);
	if(count($res) > 1){
		$res = explode('"' , $res[1] , 2);
		if(count($res) > 1){
			return (float) trim($res[0]);
		}
	} 

	$res = explode('currentItemPrice:' , $html);
	if(count($res) > 1){
		$res = explode(',' , $res[1] , 2);
		if(count($res) > 1){
			return (float) trim( $res[0] );
		}
	} 
	
	
	$res = explode('<meta itemprop=price itemtype="http://schema.org/Product" content="' , $html);
	if(count($res) > 1){
	    $res = explode('"' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	
	$res = explode('<meta itemprop="price" content="' , $html);
	if(count($res) > 1){
	    $res = explode('"' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9,]/", "",  $res[0]);
	        $res = str_replace(array(","), array(".") , $res);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('<strong class=display-price>$' , $html);
	if(count($res) > 1){
	    $res = explode('<' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('<span class="payment-price"><strong><span class="int">' , $html);
	if(count($res) > 1){
	    $res = explode('>' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9,]/", "",  $res[0]);
	        $res = str_replace(array(","), array(".") , $res);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('data-product-price=' , $html);
	if(count($res) > 1){
	    $res = explode('>' , $res[1] , 2);
	    if(count($res) > 1){
	        return (float) trim( $res[0] );
	    }
	}
	
	$res = explode('<strong class=display-price>$' , $html);
	if(count($res) > 1){
	    $res = explode('<' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	return '';
}


function mspro_walmart_clear_price($price){
    $res = preg_replace("/[^0-9.]/", "",  $price);
    return $res;
}


function mspro_walmart_sku($html){
    $res =  explode('","sku":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
        $res =  explode('<span itemprop=sku>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
		$res =  explode('" data-ref-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
        
        $res =  explode(',"refProductSku":' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
        
    	return mspro_walmart_model($html);
}

function mspro_walmart_model($html){
    $res =  explode('","manufacturerProductId":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('","model":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('","itemId":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode(',"item_id":{"values":["' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
	   $res =  explode('<meta itemprop=productID itemtype="http://schema.org/Product" content=' , $html);
       if(count($res) > 1){
       		$res = explode('/>' , $res[1]);
       		if(count($res) > 1){
       			return trim( $res[0] );	
       		}
       }
       
       $res =  explode('<td class="value-field Referencia-do-Modelo">' , $html);
       if(count($res) > 1){
           $res = explode('<' , $res[1]);
           if(count($res) > 1){
               return trim( $res[0] );
           }
       }
 
       return '';
}

function mspro_walmart_upc($html){
    $res =  explode('","upc":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    return '';
}

function mspro_walmart_prodid($html){
    $res =  explode('","productId":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    $res =  explode('","primaryProductId":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    return false;
}


function mspro_walmart_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       }
		$res =  explode('<meta name=description content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       }
		$res =  explode('<meta name="Description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       }
       
       return '';
}

function mspro_walmart_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       }
 		$res =  explode('<meta name=keywords content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       }
		$res =  explode('<meta name="Keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , mspro_walmart_clear_names($res[0]) );	
       		}
       		 
       } 

       return mspro_walmart_meta_description($html);
}


function mspro_walmart_main_image($html){
	$arr = mspro_walmart_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}



function mspro_walmart_other_images($html){
	$arr = mspro_walmart_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}

function mspro_walmart_get_images_arr($html){
		$out = array();
		$res = explode("posterImages.push('" , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode("');" , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
		
		$instruction = 'img[itemprop=image]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    // echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data[0]['src'])){
	        $tres = explode('?' , $data[0]['src']);
	    	$out[] = $tres[0];
	    }
	    
	    $instruction = 'div#carousel ul li img';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0){
	    	foreach($data as $pos_img){
	    		if(isset($pos_img['src'])){
	    			if(substr($pos_img['src'] , 0 , 2) == "//"){
	    				$pos_img['src'] = substr($pos_img['src'] , 2);
	    			}
	    			$out[] = $pos_img['src'];
	    		}
	    	}
	    }
	    
		$instruction = 'a.product-thumb';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0){
	    	foreach($data as $pos_image){
	    		if(isset($pos_image['href'])){
	    			$out[] = $pos_image['href'];
	    		}elseif(isset($pos_image['data-zoom-image'])){
	    			$out[] = $pos_image['data-zoom-image'];
	    		}elseif(isset($pos_image['data-hero-image'])){
	    			$out[] = $pos_image['data-hero-image'];
	    		}
	    	}
	    }
	    
	    $instruction = 'div#wm-pictures-carousel a';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if(isset($data) && is_array($data) && count($data) > 0){
	        foreach($data as $pos_image){
	            if(isset($pos_image['data-zoom'])){
	                $out[] = $pos_image['data-zoom'];
	            }elseif(isset($pos_image['data-normal'])){
	                $out[] = $pos_image['data-normal'];
	            }
	        }
	    }
	     
	     
	    $instruction = 'img.js-product-primary-image';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen($data[0]['src']) > 5){
	        $out[] = $data[0]['src'];
	    }
	     
	    $t_res = explode('","hero":"' , $html);
	    //echo count($t_res);exit;
	    if(is_array($t_res) && count($t_res) > 1){
	        unset($t_res[0]);
	        foreach($t_res as $pos_image){
	            $tt_res = explode('"' , $pos_image , 2);
	            if(is_array($tt_res) && count($tt_res) > 1){
	                if(strlen(trim($tt_res[0])) > 0){
	                    $out[] = $tt_res[0];
	                }
	            }
	        }
	    }
	    
	    $t_res = explode('"zoom":"' , $html);
	    //echo count($t_res);exit;
	    if(is_array($t_res) && count($t_res) > 1){
	        unset($t_res[0]);
	        foreach($t_res as $pos_image){
	            $tt_res = explode('"' , $pos_image , 2);
	            if(is_array($tt_res) && count($tt_res) > 1){
	                if(strlen(trim($tt_res[0])) > 0){
	                    $out[] = $tt_res[0];
	                }
	            }
	        }
	    }
		
	    $out = clear_images_array($out);
		// echo '<pre>'.print_r($out , 1).'</pre>';exit;
		
	    
		return $out;
}


function mspro_walmart_options($html){
    $out = array();

    $instruction = 'div.variants-container';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
   // echo '<pre>'.print_r($res , 1).'</pre>';// exit;
    unset($parser);
    if(isset($res) && is_array($res) && count($res) > 0){
        foreach($res as $POS_OPTION){
            if (isset($POS_OPTION['div'][0]['span']['span'][0]['#text']) && !is_array($POS_OPTION['div'][0]['span']['span'][0]['#text']) && strlen($POS_OPTION['div'][0]['span']['span'][0]['#text']) > 0 &&
                 isset($POS_OPTION['div'][1]['span']) && is_array($POS_OPTION['div'][1]['span']) && count($POS_OPTION['div'][1]['span']) > 0){
                $OPTION = array();
                $name = trim(str_replace( array(":") , array("") , $POS_OPTION['div'][0]['span']['span'][0]['#text']));
                $OPTION['name'] = $name;
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($POS_OPTION['div'][1]['span'] as $option_value){
                    if(isset($option_value['title']) && !is_array($option_value['title']) && strlen(trim($option_value['title'])) > 0){
                        $val = trim($option_value['title']);
                        if(substr($val , -1) == ","){
                            $val = substr($val , 0 , -1);
                        }
                        if(substr($val , 0  , strlen($name) + 1) == $name . ' '){
                            $val = substr($val , strlen($name) + 1);
                        }
                        $OPTION['values'][] = array('name' =>  $val, 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }elseif(isset($POS_OPTION['div'][0]['span'][0]['span'][0]['#text']) && !is_array($POS_OPTION['div'][0]['span'][0]['span'][0]['#text']) && strlen($POS_OPTION['div'][0]['span'][0]['span'][0]['#text']) > 0 &&
             isset($POS_OPTION['div'][1]['span']) && is_array($POS_OPTION['div'][1]['span']) && count($POS_OPTION['div'][1]['span']) > 0){
                $OPTION = array();
                $name = trim(str_replace( array(":") , array("") , $POS_OPTION['div'][0]['span'][0]['span'][0]['#text']));
                $OPTION['name'] = $name;
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($POS_OPTION['div'][1]['span'] as $option_value){
                    if(isset($option_value['title']) && !is_array($option_value['title']) && strlen(trim($option_value['title'])) > 0){
                        $val = trim($option_value['title']);
                        if(substr($val , -1) == ","){
                            $val = substr($val , 0 , -1);
                        }
                        if(substr($val , 0  , strlen($name) + 1) == $name . ' '){
                            $val = substr($val , strlen($name) + 1);
                        }
                        $OPTION['values'][] = array('name' => $val , 'price' => 0);
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



function mspro_walmart_attributes($html){
    $out = array();
    
    $model = mspro_walmart_prodid($html);
    
    if($model !== false){
        $t_res = explode('<script id="btf-content" type="application/json">' , $html);
        if(count($t_res) > 1){
            $t_res = explode('</script>' , $t_res[1] , 2);
            if(count($t_res) > 1){
                $str = '[' . trim($t_res[0]) . ']';
                $data =  (array) json_decode($str , 1);
                if(isset($data[0]['btf-content']['product']['idmlMap'][$model]['modules']['Specifications']['specifications'])){
                    $spec = $data[0]['btf-content']['product']['idmlMap'][$model]['modules']['Specifications']['specifications'];
                    if(isset($spec['displayName']) && !is_array($spec['displayName']) && isset($spec['values'][0]) && is_array($spec['values'][0]) && count($spec['values'][0]) > 0){
                        $vals = $spec['values'][0];
                        // echo '<pre>' . print_r($vals , 1) . '</pre>';
                        $group = $spec['displayName'];
                        foreach($vals as $val){
                            $ATTR = array();
                            $ATTR['group'] = $group;
                            if(is_array($val) && count($val) > 0){
                                foreach($val as $key => $VAL){
                                    if(isset($VAL['displayName']) && !is_array($VAL['displayName']) && isset($VAL['displayValue']) && !is_array($VAL['displayValue'])){
                                        $ATTR['name'] = trim($VAL['displayName']);
                                        $ATTR['value'] = trim($VAL['displayValue']);
                                        $out[] = $ATTR;
                                    }
                                }
                            }
                            
                        }
                    }
                }
            }
        }
    }
    
    if(count($out) < 1){
        $res =  explode('{"displayName":"Specifications","values":[' , $html);
        if(count($res) > 1){
            $res = explode(']}},"' , $res[1] , 2);
            if(count($res) > 1){
                $res = $res[0];
                $res = str_ireplace("&quot;", '"', $res);
                $res =  (array) json_decode($res , 1);
                if(is_array($res) && count($res) > 0){
                    foreach($res as $BL){
                        foreach($BL as $key => $vals){
                            if(isset($vals['displayName']) && isset($vals['displayValue'])){
                                $ATTR = array();
                                $ATTR['group'] = "Specifications";
                                $ATTR['name'] = trim($vals['displayName']);
                                $ATTR['value'] = trim($vals['displayValue']);
                                $out[] = $ATTR;
                            }
                            // echo '<pre>'.print_r($vals , 1).'</pre>';
                        }
                    }
                }
                // echo $res;
            }
        }
    }

    // echo '<pre>'.print_r($out , 1).'</pre>';exit;

    return $out;
}

/*
function mspro_walmart_noMoreAvailable($html){
	return false;
}
*/