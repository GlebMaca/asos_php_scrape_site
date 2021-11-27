<?php

function mspro_asos_getUrl($url) {
	$headers = array(
		'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'accept-language: en-US,en;q=0.9',
		'cache-control: no-cache',
		'pragma: no-cache',
		'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
		'sec-ch-ua-mobile: ?0',
		'sec-fetch-dest: document',
		'sec-fetch-mode: navigate',
		'sec-fetch-site: none',
		'sec-fetch-user: ?1',
		'upgrade-insecure-requests: 1'
	);

	$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);

	return $output;
}

echo mspro_asos_getUrl('https://www.asos.com/api/product/catalogue/v3/stockprice?productIds=24371778&store=ROW&currency=USD');

die;

// phpinfo();exit;

/*
require_once "support/web_browser.php";
require_once "support/tag_filter.php";

// Retrieve the standard HTML parsing array for later use.
$htmloptions = TagFilter::GetHTMLOptions();
echo '1';
// Retrieve a URL (emulating Firefox by default).
$url = "https://www.aliexpress.com/af/category/202003451.html?d=n&catName=tops-tees&CatId=202003451&origin=n&spm=a2g0v.best.101.4.10df4c65EaF98b&isViewCP=y&jump=afs&switch_new_app=y";
$web = new WebBrowser();
$result = $web->Process($url);

// Check for connectivity and response errors.
if (!$result["success"])
{
	echo "Error retrieving URL.  " . $result["error"] . "\n";
	exit();
}

if ($result["response"]["code"] != 200)
{
	echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
	exit();
}

echo '<pre>' . print_r($result , 1) . '</pre>';exit;

echo $result;


exit;
*/



//$str = str_replace('&quot;' , '"' , $str);

$str = stripslashes($str);

echo $str;


$res =  (array) json_decode($str , 1);
echo '<pre>' . print_r($res , 1) . '</pre>';exit;
/*****************************************************************************/


$is = getimagesize('test.jpg');
print_r($is);
 exit;


$markets = array('aliexpress' , 'amazon' , 'banggood' , 'ebay' , 'etsy' , 'flipkart' , 'sunsky' , 'overstock' , 'lazada' , 'tinydeal' , 'bestbuy' , 'walmart');
$platforms = array('codecanyon' , 'codegrape' , 'codester' , 'opencart');
$sizes = array('590x295' , '680x340' , '80x80' , '200x200' , '748x374' , '1500x500' , '1600x800' , '100x100' , '260x152' , '500x500' , '710x380' , '1200x1200');

foreach($markets as $market){
	foreach($platforms as $platform){
		foreach($sizes as $size){
			 rename('images/' . $market . '/' . $platform . '/' . $market . '_' . $size . '.png' , 'images/' . $market . '/' . $platform . '/' . $market . '_' . $size . '.jpg' );
			//png2jpg('images/' . $market . '/' . $platform . '/' . $market . '_' . $size . '.png' , 'images/' . $market . '/' . $platform . '/' . $market . '_' . $size . '.jpg', 100);
		}
	}
}

function png2jpg($originalFile, $outputFile, $quality) {
	$image = imagecreatefrompng($originalFile);
	imagejpeg($image, $outputFile, $quality);
	imagedestroy($image);
}

/*****************************************************************************/




$is = @getimagesize('aliexpress590x300.jpg');
print_r($is);exit;


echo '<pre>' . print_r($res , 1) . '</pre>';



exit;

require('shd/simple_html_dom.php');

// Create DOM from URL or file
$html = file_get_contents('https://www.heavengifts.com/');
$html = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $html);
$html = preg_replace(array("'<object[^>]*?>.*?</object>'si"), Array(""), $html);
$html = str_replace(array('<iframe' , '</iframe>'), Array(""), $html);
/* $html = preg_replace(array("'<iframe[^>]*?>.*?</iframe>'si"), Array(""), $html); */
echo "HTML:" . $html;exit;
/*
$str = 'https://www.ikrush.com/get/browsePage/fetchProducts.php?sort-by=mostrecent%2Cdesc&perpage=30&undefined=&price%3Agte=7&price%3Alte=70&categoryIds%3Aor=12%2C23%2C24%2C25%2C26%2C27%2C28%2C29%2C108%2C243%2C142%2C182%2C264%2C265%2C266%2C267%2C268%2C269&store=1&stock%3Agt=0&showall=true&sale=false&brandid=0&catid=12&startnumber=0&order=na&sex=m&keyword=&slidelock=false&hasfiltered=true&isfiltering=true&submitfilter=Filter&pagenum=2&ajaxForm=true';
echo urldecode($str);

exit;

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'http://www.carters.com/carters-baby-boy-tops/VC_225G757.html?cgid=carters-baby-boy-tops&dwvar_VC__225G757_color=Red&dwvar_VC__225G757_size=24M');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
$query = curl_exec($curl_handle);
curl_close($curl_handle);

echo $query ;exit;
*/
echo  utf8_encode("bcibcobr");exit;
//phpinfo();exit;

$str = 'a:5:{s:5:"width";i:4096;s:6:"height";i:2304;s:4:"file";s:29:"2018/01/P_20170527_140016.jpg";s:5:"sizes";a:7:{s:9:"thumbnail";a:4:{s:4:"file";s:29:"P_20170527_140016-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:10:"image/jpeg";}s:6:"medium";a:4:{s:4:"file";s:29:"P_20170527_140016-300x169.jpg";s:5:"width";i:300;s:6:"height";i:169;s:9:"mime-type";s:10:"image/jpeg";}s:12:"medium_large";a:4:{s:4:"file";s:29:"P_20170527_140016-768x432.jpg";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:10:"image/jpeg";}s:5:"large";a:4:{s:4:"file";s:30:"P_20170527_140016-1024x576.jpg";s:5:"width";i:1024;s:6:"height";i:576;s:9:"mime-type";s:10:"image/jpeg";}s:14:"shop_thumbnail";a:4:{s:4:"file";s:29:"P_20170527_140016-180x180.jpg";s:5:"width";i:180;s:6:"height";i:180;s:9:"mime-type";s:10:"image/jpeg";}s:12:"shop_catalog";a:4:{s:4:"file";s:29:"P_20170527_140016-300x300.jpg";s:5:"width";i:300;s:6:"height";i:300;s:9:"mime-type";s:10:"image/jpeg";}s:11:"shop_single";a:4:{s:4:"file";s:29:"P_20170527_140016-600x600.jpg";s:5:"width";i:600;s:6:"height";i:600;s:9:"mime-type";s:10:"image/jpeg";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"2";s:6:"credit";s:0:"";s:6:"camera";s:11:"ASUS_Z010DA";s:7:"caption";s:0:"";s:17:"created_timestamp";s:10:"1495893615";s:9:"copyright";s:0:"";s:12:"focal_length";s:3:"4.6";s:3:"iso";s:3:"200";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}';
$str = 'a:2:{s:7:"pa_size";a:6:{s:4:"name";s:7:"pa_size";s:5:"value";s:0:"";s:8:"position";i:0;s:10:"is_visible";i:1;s:12:"is_variation";i:0;s:11:"is_taxonomy";i:1;}s:10:"customattr";a:6:{s:4:"name";s:10:"CustomAttr";s:5:"value";s:64:"CustomAttrVal | CustomAttrVal1 | CustomAttrVal2 | CustomAttrVal3";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:0;s:11:"is_taxonomy";i:0;}}';
$str = '[{"data":{"id":4053,"slug":"PowerPoint-What-Your-Investor-Pitch-Should-Cover","name":"What Your Investor Pitch Should Cover","slides":14,"bundle":false,"price":52,"download_credit_cost":1,"purchases":495,"discount":0,"description":"","rating":3.7000000000000002,"rating_num":7,"tags":["What Your Investor Pitch Should Cover business pitch deck presentation company marketing flat"],"meta_description":"","meta_keywords":"What Your Investor Pitch Should Cover business pitch deck presentation company marketing flat","images":{"data":[{"priority":1,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97254\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":2,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97255\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":3,"src":"https:\/\/static.slideshop.com\/resources\/slide_imgs\/4053\/97256\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":4,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97257\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":5,"src":"https:\/\/static3.slideshop.com\/resources\/slide_imgs\/4053\/97258\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":6,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97259\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":7,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97260\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":8,"src":"https:\/\/static.slideshop.com\/resources\/slide_imgs\/4053\/97261\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":9,"src":"https:\/\/static.slideshop.com\/resources\/slide_imgs\/4053\/97262\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":10,"src":"https:\/\/static3.slideshop.com\/resources\/slide_imgs\/4053\/97263\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":11,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97264\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":12,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97265\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":13,"src":"https:\/\/static3.slideshop.com\/resources\/slide_imgs\/4053\/97266\/What-Your-Investor-Pitch-Should-Cover-l.png"},{"priority":14,"src":"https:\/\/static2.slideshop.com\/resources\/slide_imgs\/4053\/97267\/What-Your-Investor-Pitch-Should-Cover-l.png"}]}}}]';
/*
$res = (array) json_decode($str , 1);


echo '<pre>' . print_r($res , 1) . '</pre>';
echo json_last_error();
exit;
*/

$str = 'a:2:{s:5:"color";a:6:{s:4:"name";s:5:"Color";s:5:"value";s:19:"Black | Rose | Wine";s:8:"position";i:0;s:10:"is_visible";i:1;s:12:"is_variation";i:0;s:11:"is_taxonomy";i:0;}s:4:"size";a:6:{s:4:"name";s:4:"Size";s:5:"value";s:10:"S | XL | M";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:0;s:11:"is_taxonomy";i:0;}}';
//$str = 
echo '<pre>' . print_r(unserialize($str) , 1) . '</pre>';exit;
exit;

//$res = '[{"id":261033410,"title":"Red Fire","option1":"Red Fire","option2":null,"option3":null,"sku":"8560d","requires_shipping":true,"taxable":true,"featured_image":{"id":220342060,"product_id":114249358,"position":2,"created_at":"2012-12-09T19:34:52-08:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560d.jpg?v=1409637631","variant_ids":[261033410]},"available":true,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Red Fire","public_title":"Red Fire","options":["Red Fire"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":47,"inventory_management":"shopify","inventory_policy":"deny","barcode":null},{"id":261033810,"title":"Blue Ice","option1":"Blue Ice","option2":null,"option3":null,"sku":"8560e","requires_shipping":true,"taxable":true,"featured_image":{"id":220342062,"product_id":114249358,"position":4,"created_at":"2012-12-09T19:34:52-08:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560e.jpg?v=1409637631","variant_ids":[261033810]},"available":true,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Blue Ice","public_title":"Blue Ice","options":["Blue Ice"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":6,"inventory_management":"shopify","inventory_policy":"deny","barcode":null},{"id":688833929,"title":"Green Ice","option1":"Green Ice","option2":null,"option3":null,"sku":"8560f","requires_shipping":true,"taxable":true,"featured_image":{"id":982168033,"product_id":114249358,"position":5,"created_at":"2014-07-25T20:33:12-07:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560f.JPG?v=1409637631","variant_ids":[688833929]},"available":false,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Green Ice","public_title":"Green Ice","options":["Green Ice"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":-5,"inventory_management":"shopify","inventory_policy":"deny","barcode":null},{"id":261033882,"title":"Purple Midnight","option1":"Purple Midnight","option2":null,"option3":null,"sku":"8560g","requires_shipping":true,"taxable":true,"featured_image":{"id":220342066,"product_id":114249358,"position":7,"created_at":"2012-12-09T19:34:52-08:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560g.jpg?v=1409637631","variant_ids":[261033882]},"available":false,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Purple Midnight","public_title":"Purple Midnight","options":["Purple Midnight"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":-5,"inventory_management":"shopify","inventory_policy":"deny","barcode":null},{"id":688833525,"title":"Purple Sun","option1":"Purple Sun","option2":null,"option3":null,"sku":"8560h","requires_shipping":true,"taxable":true,"featured_image":{"id":714792297,"product_id":114249358,"position":6,"created_at":"2014-04-15T15:57:03-07:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560h.JPG?v=1409637631","variant_ids":[688833525]},"available":false,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Purple Sun","public_title":"Purple Sun","options":["Purple Sun"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":-5,"inventory_management":"shopify","inventory_policy":"deny","barcode":null},{"id":688835009,"title":"Red Ice","option1":"Red Ice","option2":null,"option3":null,"sku":"8560i","requires_shipping":true,"taxable":true,"featured_image":{"id":989360897,"product_id":114249358,"position":8,"created_at":"2014-08-01T12:24:26-07:00","updated_at":"2014-09-01T23:00:31-07:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/0193\/6253\/products\/8560i.JPG?v=1409637631","variant_ids":[688835009]},"available":true,"name":"Crazy Mirrored Lens Colorized Horned Rim Sunglasses 8560 - Red Ice","public_title":"Red Ice","options":["Red Ice"],"price":999,"weight":27,"compare_at_price":null,"inventory_quantity":4,"inventory_management":"shopify","inventory_policy":"deny","barcode":null}]';
$res = '[{"9564":{"id":"9564","code":"ca_4302","label":"Storlek","options":[{"id":"43221","label":"C46\/146","price":"0","oldPrice":"0","products":["159095"]},{"id":"43222","label":"C48\/148","price":"0","oldPrice":"0","products":["159096"]},{"id":"43223","label":"C50\/150","price":"0","oldPrice":"0","products":["159097"]},{"id":"43224","label":"C52\/152","price":"0","oldPrice":"0","products":["159098"]},{"id":"43225","label":"C54\/154","price":"0","oldPrice":"0","products":["159099"]},{"id":"43226","label":"C56\/156","price":"0","oldPrice":"0","products":["159100"]},{"id":"43227","label":"C58\/158","price":"0","oldPrice":"0","products":["159101"]},{"id":"43210","label":"D100","price":"0","oldPrice":"0","products":["159092"]},{"id":"43211","label":"D104","price":"0","oldPrice":"0","products":["159093"]},{"id":"43212","label":"D108","price":"0","oldPrice":"0","products":["159094"]},{"id":"43229","label":"D112","price":"0","oldPrice":"0","products":["159104"]},{"id":"43230","label":"D116","price":"0","oldPrice":"0","products":["159105"]},{"id":"43231","label":"D88","price":"0","oldPrice":"0","products":["159074"]},{"id":"43228","label":"D92","price":"0","oldPrice":"0","products":["159103"]},{"id":"43219","label":"D96","price":"0","oldPrice":"0","products":["159102"]}]},"10299":{"id":"10299","code":"ca_6071","label":"Svart","options":[{"id":"43232","label":"Svart","price":"0","oldPrice":"0","products":["159074","159092","159093","159094","159095","159096","159097","159098","159099","159100","159101","159102","159103","159104","159105"]}]}}]';
$res = str_ireplace("&quot;", '"', $res);
$res = str_ireplace("\/", '/', $res);
$res =  (array) json_decode($res , 1);


echo '<pre>' . print_r($res , 1) . '</pre>';exit;
$res = preg_replace("/\\\\u([0-9a-f]{3,4})/i", "&#x\\1;", $res);
		$res = html_entity_decode($res, null, 'UTF-8');
		//$res = utf8_encode($res);
		$res = stripslashes($res);
		//echo $res;
		$res =  (array) json_decode($res , 1);
		
		
		echo '<pre>' . print_r($res , 1) . '</pre>';
		echo json_last_error();





exit;


phpinfo();exit;

?>
