<?php
function mspro_sunsky_getUrl($url){
    $out = '';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/cookie.txt");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING , "");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Upgrade-Insecure-Requests: 1'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.koton.com/tr/kadin/giyim/ust-giyim/bluz/c/M01-C02-N01-AK101-K100024'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate, br'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.koton.com'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('cookie: ga-userType=GUEST; NSC_JO0cfqsdd1eyooqe0st5unduaigw0bz=ffffffff0966711245525d5f4f58455e445a4a42581a; inLanding=https%3A%2F%2Fwww.koton.com%2Ftr; _sgf_user_id=1822925621780483; _sgf_user_id_ext=true; spUID=151136008202912d6c7c5ca.bd815cc5; first-permission-impression=1; ktn_snid=tH17j+9clWhstp0SmwWsUMONgrcA010; JSESSIONID=8E9E6A3CA0E57D4560D12E489861BDB0.nf2; ktn_snid_.koton.com_%2F_wlf=Z2EtdXNlcklk?LqXoO0IvnV4c2UJWhqemc9FEdewA&Z2EtdXNlclR5cGVf?Cgo/Pxu6gZbXX2+w2W4YWtCgPiIA&SlNFU1NJT05JRF9f?GNSyXR6aj0sDryDTd26IZDdzxdMA&; ktn_snid_.koton.com_%2F_wat=TlNDX0pPMGNmcXNkZDFleW9vcWUwc3Q1dW5kdWFpZ3cwYnpf?K2IVXX8etYFBQy4QMenyLnJxAGIA&; _ga=GA1.2.1816087886.1511360072; _gid=GA1.2.1299145370.1511694072; _sgf_controlGroup=0; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wyidfp=77e61cae-cc22-4f78-9324-6b966410fc72; _wysi=WY.1.9394620.323644232; _wysi=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wysis=WY.1.9394620.323644232; _wybrs=1511703004569; _wybrs=1511703004569; insdrSV=10; scs=%7B%22t%22%3A1%7D; current-currency=; _sgf_session_id=1822925621780482; ins-gaSSId=27b6a075-f656-eab0-d7ca-38564f24cc9b_1511706588'));
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_REFERER, "https://www.koton.com");
    $data = curl_exec($ch);

    // echo 'HTML' . $data;exit;
    return $data;
}

function mspro_sunsky_title($html){
        $instruction = 'title';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;" , 'SUNSKY - ') , array(" " , "`" , "") ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;" , "SUNSKY - ") , array(" " , "`"  , "") ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_sunsky_description($html){
		$out = '';
		
		$res = explode('id=overview>' , $html , 2);
		if(count($res) > 1){
			$res = explode('<DIV style="clear:both">' , $res[1] , 2);
			if(count($res) > 1){
				$out .= '<div>' . $res[0] . '</div>';
			}
		}
		//echo $out;exit;
		
		$res = explode('<H3>More Pictures</H3>' , $html , 2);
		if(count($res) > 1){
		    $res = explode('<DIV style="clear:both">' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = explode('<IMG src="' , $res[0]);
		        if(isset($res) && is_array($res) && count($res) > 1){
		            unset($res[0]);
		            foreach($res as $pos_image){
		                $t_res = explode('"' , $pos_image , 2);
		                if(count($t_res) > 1){
		                    $out .= '<img src="' . $t_res[0] . '" /><br />';
		                }
		            }
		        }
		    }
		}
		
		// remove TPO OF THE PAGE button
		$t_res = explode('<DIV style="float:right;">' , $out);
		if(count($t_res) > 1){
		    $tt_res = explode('</DIV>' , $t_res[1] , 2);
		    if(count($tt_res) > 1){
		        $out = $t_res[0] . $tt_res[1];
		    }
		}
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#morePictures');
		foreach ($temp as $block){
		    $out .= '<div>' . $temp->html() . '</div>';
		}
		
		$out = str_ireplace(array('<IMG alt="Top of Page" src="https://img.sunsky-online.com/htdocs/images/to_top.gif">' , '<A class=toTop href="#"><IMG alt="Top of Page" src="https://img.sunsky-online.com/htdocs/images/to_top.gif"></A>' , 'Pack All Images and Download' , '<img src="https://img.sunsky-online.com/htdocs/images/printer_icon.gif" align="absmiddle">' , '<a href="javascript:void(0)" onclick="downloadImgs(this, event)">Pack All Images and Download</a>') , array("") , $out);
		$out = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $out);
		
        return $out;
}


function mspro_sunsky_price($html){
    // echo $html;exit;
    $res = explode('<meta property="og:product:price" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    
    $res = explode('SPAN class="bold red">$' , $html);
    if(count($res) > 1){
        $res = explode('</' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    $res = explode('SPAN class="bold red">' , $html);
    if(count($res) > 1){
        $res = explode('</' , $res[1] , 2);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
    	return '';
}


function mspro_sunsky_sku($html){
	    $res = explode('Item #: <B>' , $html);
        if(count($res) > 1){
        	$res = explode('</B>' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim($res[0]);
        	}
        }
        $res = explode('var ITEM_NO = "' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('Article #: <B>' , $html);
        if(count($res) > 1){
            $res = explode('</B>' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        $res = explode('key="ITEM_NO" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
		return '';
}

function mspro_sunsky_model($html){
		return mspro_sunsky_sku($html);
}


function mspro_sunsky_meta_description($html){
	   $res =  explode('<META name=description content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_sunsky_meta_keywords($html){
       $res =  explode('<META name=keywords content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1 && strlen(trim($res[0])) > 0){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		} 
       }
       return mspro_sunsky_title($html);
}


function mspro_sunsky_main_image($html){
		$arr = sunsky_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_sunsky_other_images($html){
		$arr = sunsky_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function sunsky_get_images($html){
		$out = array();
		
		
		
		$res = explode('id=mainImg src="' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
			

		$res = explode('<IMG src="http://img.sunsky-online.com/upload/store/detail_l/' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = 'http://img.sunsky-online.com/upload/store/detail_l/'.$res_t[0];
				}
			}
		}
		
		$res = explode('<IMG width="150" height="150" src="' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
		
		$res = explode('onmouseover="showImg(\'' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode("'" , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
		
		$out = array_unique($out);
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		
		return $out;
}


/*
function mspro_sunsky_options($html){
	$out = array();
	
	$opt_arr = array();
	$instruction = 'input#productAttributesJson';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	if(isset($res[0]['value']) && !is_array($res[0]['value']) ){
		@$res_t = json_decode($res[0]['value']);
		if (isset($res_t) && is_array($res_t) && count($res_t) > 0){
			foreach($res_t as $pos_opt){
				if(isset($pos_opt->name) && isset($pos_opt->value) && isset($pos_opt->soldOut) && $pos_opt->soldOut < 1 && isset($pos_opt->supc) ){
					$temp = array('value' => $pos_opt->value , 'supc' => $pos_opt->supc);
					if(!array_key_exists($pos_opt->name, $opt_arr)){
						$opt_arr[$pos_opt->name] = array();
					}
					$opt_arr[$pos_opt->name][] = $temp;
				}
			}
		}
	}
	//echo '<pre>'.print_r($opt_arr , 1).'</pre>';exit;
	unset($parser);
	if (is_array($opt_arr) && count($opt_arr) > 0){
		$catid = sunsky_get_catid($html);
		$originalPrice = mspro_sunsky_price($html);
		foreach($opt_arr as $opt_name => $option_values){
				$OPTION = array();
				$OPTION['name'] = $opt_name;
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($option_values as $option_value){
					$OPTION['values'][] = array('name' => $option_value['value'] , 'price' => sunsky_get_option_price($option_value['supc'] , $catid , $originalPrice) );
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
		}
	}
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}
*/



function mspro_sunsky_noMoreAvailable($html){
    if(stripos($html , 'ArrivalNotice.gif') > 0){
        return true;
    }
	return false;
}

