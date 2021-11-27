<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Multiscraper</title>
	<link rel="shortcut icon" href="<?php echo $this->config->item('base_url'); ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/menu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/blocks.css" />
	
	<?php 
		if(isset($addCSS)){
			foreach($addCSS as $css){
				echo '<link rel="stylesheet" type="text/css" href="'.$this->config->item('base_url').'public/css/'.$css.'.css" />';
			}
		}
	
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/terminaldosis.css" />
	
	
	<script src="<?php echo $this->config->item('base_url'); ?>public/scripts/jquery.min.js"></script>
	<?php 
		if(isset($addJS)){
			foreach($addJS as $js){
					echo '<script type="text/javascript"  src="'.$this->config->item('base_url').'public/scripts/'.$js.'.js" /></script>';
			}
		}
	
	?>
	
	
</head>
<body>

<div id="container">

	<div id="header">
		<img src="<?php echo $this->config->item('base_url'); ?>public/images/logo.png" id="header_img"  />
		 <div id="logo_text_wrapper">
		 	<span id="" style="font-size: 36px;">MultiScraper</span>
		 	<br />
		 	<span id="" style="color:#DE1D73;font-size: 17px;">Grab products from other marketplaces directly into your <?php echo MSPRO_CMS_DISPLAY_NAME ?> store</span>
		 </div>
		 <div style="float: right;margin: 10px;">
		 	<a href="<?php echo $this->config->item('base_url'); ?>logout"> 
		 		<img src="<?php echo $this->config->item('base_url'); ?>public/images/exit.png" style="width: 50px;height: 50px;" title="Exit from multiscraper admin panel"/>
		 	</a>
		 </div>
	</div>
