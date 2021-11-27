<script type="text/javascript">
var ConsoleMode = <?php echo GLOBAL_DEBUG_SEMAFOR > 0?"true":"false"; ?>;
var Tasks = {};
var Categories = {};
var Manufacturers = {};
var Taxclasses = {};
var Currencies = {};
var Fields = {};
var TableSemafors = {};
<?php
if(is_array($instructions) && count($instructions) > 0){
	foreach($instructions as $instruction){
		echo 'Tasks.task' . $instruction['id'] . ' = {};';
		echo 'Tasks.task' . $instruction['id'] . '.state = ' . $instruction['state'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.priority = ' . $instruction['priority'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.name = "' . addslashes($instruction['name']) . '";';
		
		echo 'Tasks.task' . $instruction['id'] . '.category_urls = "";';
		if(is_array($instruction['category_urls']) && count($instruction['category_urls']) > 0){
			foreach($instruction['category_urls'] as $category_url){
				echo 'Tasks.task' . $instruction['id'] . '.category_urls += "'.addslashes($category_url).'\n";
		    ';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.product_urls = "";';
		if(is_array($instruction['product_urls']) && count($instruction['product_urls']) > 0){
			foreach($instruction['product_urls'] as $product_url){
				echo 'Tasks.task' . $instruction['id'] . '.product_urls += "'.addslashes($product_url).'\n";
		    ';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.category_id = new Array();';
		if(is_array($instruction['category_id']) && count($instruction['category_id']) > 0){
			foreach($instruction['category_id'] as $category_id){
				echo 'Tasks.task' . $instruction['id'] . '.category_id.push('.$category_id.') ;';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.manufacturer = "' . $instruction['manufacturer_id'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_manufacturer = "' . ((isset($instruction['do_not_update_manufacturer']) && $instruction['do_not_update_manufacturer'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.taxclass = "' . ((isset($instruction['tax_class_id']) && $instruction['tax_class_id'] > 0)?$instruction['tax_class_id']:0) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_taxclass = "' . ((isset($instruction['do_not_update_taxclass']) && $instruction['do_not_update_taxclass'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.main_image_limit = "' . ((isset($instruction['main_image_limit']) && $instruction['main_image_limit'] > -1)?$instruction['main_image_limit']:-1) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.description_image_limit = "' . ((isset($instruction['description_image_limit']) && $instruction['description_image_limit'] > -1)?$instruction['description_image_limit']:-1) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_upload_description_image = "' . ((isset($instruction['do_not_upload_description_image']) && $instruction['do_not_upload_description_image'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.image_folder = "' . $instruction['image_folder'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.donor_currency = "' . $instruction['donor_currency'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.products_quantity = "' . $instruction['products_quantity'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.margin_fixed = "' . $instruction['margin_fixed'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.margin_relative = "' . $instruction['margin_relative'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.what_to_do_product_not_exists = ' . $instruction['what_to_do_product_not_exists'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.donot_update_price = "' . ($instruction['donot_update_price'] > 0?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.create_disabled = "' . ($instruction['create_disabled'] > 0?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.get_options = "' . ((isset($instruction['get_options']) && $instruction['get_options'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_options = "' . ((isset($instruction['do_not_update_options']) && $instruction['do_not_update_options'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.get_attributes = "' . ((isset($instruction['get_attributes']) && $instruction['get_attributes'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_attributes = "' . ((isset($instruction['do_not_update_attributes']) && $instruction['do_not_update_attributes'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.seo_url = "' . $instruction['seo_url'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.comment = "' . $instruction['comment'] . '";';
	}
}
?>
<?php
if(is_array($categories) && count($categories) > 0){
	foreach($categories as $key => $category){
		echo 'Categories.cat'.$key.' = "' . addslashes($category) . '";
    ';
	}
}
if(is_array($manufacturers) && count($manufacturers) > 0){
	foreach($manufacturers as $key => $manufacturer){
		echo 'Manufacturers.man'.$key.' = "' . addslashes($manufacturer) . '";
    ';
	}
}
if(is_array($taxclasses) && count($taxclasses) > 0){
	foreach($taxclasses as $key => $taxclass){
		echo 'Taxclasses.tax'.$key.' = "' . $taxclass . '";
    ';
	}
}
if(is_array($currencies) && count($currencies) > 0){
	foreach($currencies as $key => $currency){
		echo 'Currencies.cur'.$key.' = "' . $currency . '";
    ';
	}
}
?>

if(ConsoleMode){
	console.log(Tasks);
	console.log(Categories);
	console.log(Manufacturers);
	console.log(Taxclasses);
	console.log(Currencies);
	console.log(Fields);
}
jQuery(document).ready(function($) {
	//console.log($("#button-search").attr("value"));
	$("#form_search_input").bind("keypress" , function(event){
		 if (event.keyCode == 13) {
			 action_search();
	        }
    	}
    );
    // checkboxes of tasks
	$(".ins_checkboxes").bind("change" , function(){
			//console.log(this.id);
			// close actions_to_all form
			$("#actions_to_all_form_wrapper").css("display" , "none");
			var id = this.id;
			// if pressed ALL
			if(id == "ins_checkboxs_all"){
				$(".ins_checkboxes").prop('checked', $(this).is(':checked'));
			}
			// button
			var actions_to_all_form_opened = false;
			$(".ins_checkboxes").each( function () {
				if($(this).is(':checked') == true){
					actions_to_all_form_opened = true;
				}
			});
			if(actions_to_all_form_opened == true){
				$("#actions_button_all").addClass("btn-blue");$("#actions_button_all").removeClass("btn-grey");
			}else{
				$("#actions_button_all").addClass("btn-grey");$("#actions_button_all").removeClass("btn-blue");
			}
	});
    // button for form to all
    $("#actions_button_all").bind("click" , function(){
        	if($(this).hasClass("btn-blue") == true){
        		$("#actions_switch_select_all").val("none")
        		$("#actions_set_priority_all").val("none")
        		$("#actions_to_all_form_wrapper").css("display" , "inline-block");
        	}
    });
});



<!--   список спарсенных товаров  //-->
<?php
$table_lang = "";
/*if($this->lang->line('language_key') == "ru"){
	$table_lang = ', "language": { "url": "public/scripts/dataTables.russian.lang" }';
}*/
?>
<?php
$products_grabbed_all = false;
$products_grabbed_popup = '';
if(isset($products_grabbed) && is_array($products_grabbed) && count($products_grabbed) > 0 ){
	$products_grabbed_all = true;
	$products_grabbed_popup .= '<div id="bpopup_productsList_all" class="bpopups" style="font-size: 1.2em;right:auto !important;">';
	$products_grabbed_popup .= '<span class="bpopup_button b-close"><span>X</span></span>';
	$products_grabbed_popup .= '<div class="grabbed_product_popup_title">';
	$products_grabbed_popup .=   '<span class="grabbed_product_popup_number"></span> Products grabbed by MultiScraper';
	$products_grabbed_popup .= '</div><br />';
	// table
	$products_grabbed_popup .= '<table id="products_grabbed_all" class="display" cellspacing="0">';
	$products_grabbed_popup .= '<thead><tr><th>No</th>';
	$products_grabbed_popup .= '<th>----</th><th>Link at your ' . MSPRO_CMS_DISPLAY_NAME . ' store</th>';
	$products_grabbed_popup .= '<th>Link at the DONATOR-WEBSITE</th>';
	$products_grabbed_popup .= '<th>Price</th>';
	$products_grabbed_popup .= '<th>Quantity</th>';
	$products_grabbed_popup .= '<th>Stock status</th>';
	$products_grabbed_popup .= '<th>Date grabbed</th>';
	$products_grabbed_popup .= '<th>Date updated</th></tr></thead>';
	$products_grabbed_popup .= '</table>';
	$products_grabbed_popup .= '</div>';
	echo 'TableSemafors.semafor_all = 0;';
	foreach($products_grabbed as $ins_id){
		$products_grabbed_popup .= '<div id="bpopup_productsList_' . $ins_id . '" class="bpopups" style="font-size: 1.2em;right:auto !important;">';
		$products_grabbed_popup .= '<span class="bpopup_button b-close"><span>X</span></span>';
		$products_grabbed_popup .= '<div class="grabbed_product_popup_title">';
		$products_grabbed_popup .=   '<span class="grabbed_product_popup_number"></span> Products grabbed by this Task';
		$products_grabbed_popup .= '</div><br />';
		// table
		$products_grabbed_popup .= '<table id="products_grabbed_' . $ins_id . '" class="display" cellspacing="0">';
		$products_grabbed_popup .= '<thead><tr><th>No</th>';
		$products_grabbed_popup .= '<th>----</th><th>Link at your ' . MSPRO_CMS_DISPLAY_NAME . ' store</th>';
		$products_grabbed_popup .= '<th>Link at the DONATOR-WEBSITE</th>';
		$products_grabbed_popup .= '<th>Price</th>';
		$products_grabbed_popup .= '<th>Quantity</th>';
		$products_grabbed_popup .= '<th>Stock status</th>';
		$products_grabbed_popup .= '<th>Date grabbed</th>';
		$products_grabbed_popup .= '<th>Date updated</th></tr></thead>';
		$products_grabbed_popup .= '</table>';
		$products_grabbed_popup .= '</div>';
		echo 'TableSemafors.semafor_' . $ins_id . ' = 0;';
	}
}
?>

function show_grabbed_table(target){
	eval("var sem = TableSemafors.semafor_" + target + ";");
	show_overlay();
	//console.log(sem);
	
	if(sem < 1){
		$("#products_grabbed_" + target).dataTable( {
			"processing": true,
	        "serverSide": true, 
			 "ajax": {"url" : "<?php echo $this->config->item("base_url"); ?>products/" + target , "type": "POST"},
			  retrieve: true,
			   "order": [[ 0, "asc" ]],
			   "initComplete": function () {
				   hide_overlay();
				   $('#bpopup_productsList_' + target).bPopup();
			   }
				 <?php echo $table_lang; ?>
				 
		});
	}else{
		$("#products_grabbed_" + target).dataTable();
		hide_overlay();
		$('#bpopup_productsList_' + target).bPopup();
	}
	eval("TableSemafors.semafor_" + target + " = 1;");
}

function show_overlay(){
	var docHeight = $(document).height();
	$("body").append("<div id='overlay'><img id='overlay_img' src='<?php echo $this->config->item("base_url"); ?>public/images/loader.gif' /></div>");
	 $("#overlay_img").css({'margin-top': docHeight / 2});
	 $("#overlay").height(docHeight).css({'z-index': 5000});
}

function hide_overlay(){
	$("#overlay").remove();
}


/*
 *  ОТРИСОВКА ФОРМЫ
 */
function action_form(id){
	$("#dialog_form").remove();
	$("#tasks-form").append('<div id="dialog_form"></div>');
	$("#dialog_form").attr("title" , (id !== undefined? "Edit task" : "Create new task" ) );
	$("#dialog_form").html('<div> ' +
			 
							'<!--  Task name , Switched, Priority  //-->' +
								  '<div class="addedit_form_capts">Task name : </div>'+
									'<input type="text" id="addeditform_name" name="addeditform_name" style="width:800px;" value="' + (id !== undefined? eval("Tasks.task" + id + ".name") : "" ) + '" /><br />'+
								  '<div class="addedit_form_capts">Switched : </div>'+
								  '<select name="addeditform_state" id="addeditform_state">'+
								  	'<option value="1">ON</option>'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".state") < 1 )? "selected" : "" ) + '>OFF</option>'+
								  '</select><br />' +
								  '<div class="addedit_form_capts">Priority : </div>'+
								  '<select name="addeditform_priority" id="addeditform_priority">'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") < 1 )? "selected" : "" ) + '>Normal</option>'+
								  	'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") == 1 )? "selected" : "" ) + '>High</option>'+
								  	'<option value="2" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") > 1 )? "selected" : "" ) + '>Urgent</option>'+
								  '</select>' +

							'<!--  "PRODUCTS LISTING" URLs  , "PRODUCT" URLs  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts addedit_form_capts_testareas">"PRODUCTS LISTING" URLs <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_listing\').bPopup();" /><br />each one from new line</div>'+
								'<textarea rows="4" cols="88" id="addeditform_category_urls" name="addeditform_category_urls" ' + (id !== undefined?"disabled":"") + '>' + (id !== undefined? eval("Tasks.task" + id + ".category_urls") : "" ) + '</textarea><br />'+
								'<div class="addedit_form_capts addedit_form_capts_testareas">"PRODUCT" URLs <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_product\').bPopup();" /><br />each one from new line</div>'+
								'<textarea rows="4" cols="88" id="addeditform_product_urls" name="addeditform_product_urls" ' + (id !== undefined?"disabled":"") + '>' + (id !== undefined? eval("Tasks.task" + id + ".product_urls") : "" ) + '</textarea><br />'+
								
							'<!--  Push into Category , Tax classes   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts">Push into Category<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_category\').bPopup();" /> : </div>'+
								'<div class="mspro_scrollbox">' + get_categories_choice(id) + '</div><br />'+
								'<!-- <div class="addedit_form_capts">Tax class<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_taxclass\').bPopup();" /> : </div>'+
								'<select name="addeditform_taxclass" id="addeditform_taxclass">'+ get_taxclass_choice(id) + '</select>&nbsp;&nbsp;&nbsp;'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_taxclass">Do not update Tax class after grabbing</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_taxclass\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_taxclass" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_taxclass") : "" ) + ' style="width: 30px;"><br /> //-->' + 

							'<!--   Manufacturer , Do not update manufacturer after grabbing  //-->' +
								'<!-- <div class="addedit_form_capts">Manufacturer<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_manufacturer\').bPopup();" /> : </div>'+
								'<select name="addeditform_manufacturer" id="addeditform_manufacturer">'+ get_manufacturers_choice(id) + '</select>&nbsp;&nbsp;&nbsp;'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_manufacturer">Do not update Manufacturer after grabbing</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_manufacturer\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_manufacturer" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_manufacturer") : "" ) + ' style="width: 30px;"><br /><br /> //-->' + 

							'<!--  Images limits, Do not upload description image setting, Separate image folder , Products quantity  //-->' +
							'<hr />'+
    							'<div class="addedit_form_capts" style="min-width: 230px;">Main images limit<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_main_image_limit\').bPopup();" /> : </div>'+
    							'<input  type="number" class="inputs" name="addeditform_main_image_limit" id="addeditform_main_image_limit" value="' + (id !== undefined? eval("Tasks.task" + id + ".main_image_limit") : "-1" ) + '"  maxlength="2" style="width: 60px;" />'+
    							'<div class="addedit_form_capts" style="text-align: right;width: 150px;margin-left: 25px;">Images in description limit<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_description_image_limit\').bPopup();" /> : </div>'+
    							'<input  type="number" class="inputs" name="addeditform_description_image_limit" id="addeditform_description_image_limit" value="' + (id !== undefined? eval("Tasks.task" + id + ".description_image_limit") : "-1" ) + '"  maxlength="2" style="width: 60px;" />'+
    							'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_upload_description_image">Do not upload images in description</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_upload_description_image\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_upload_description_image" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_upload_description_image") : "" ) + ' style="width: 30px;"><br />' + 
								'<!-- <div class="addedit_form_capts" style="margin-bottom:15px;">Separate image folder<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_imageFolder\').bPopup();" /> : </div>'+
								'<input type="text" id="addeditform_image_folder" name="addeditform_image_folder" style="width:500px;" value="' + (id !== undefined? eval("Tasks.task" + id + ".image_folder") : "" ) + '" ' + (id !== undefined?"disabled":"") + ' /><br /> //-->'+
								'<div class="addedit_form_capts" >Products quantity<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_products_quantity\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_products_quantity" id="addeditform_products_quantity" value="' + (id !== undefined? eval("Tasks.task" + id + ".products_quantity") : "<?php echo  $this->config->item("ms_default_quantity_of_products"); ?>" ) + '"  maxlength="2" style="width: 45px;" />'+

							'<!--  Donor market currency , Fixed margin , Relative margin(%)  //-->' +
							'<hr />'+
								'<!-- <div class="addedit_form_capts">Donator-website currency<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_currency\').bPopup();" /> : </div>'+
								'<select name="addeditform_donor_currency" id="addeditform_donor_currency">'+ get_donor_currency_choice(id) + '</select>//-->'+
								'<div class="addedit_form_capts" style="text-align: right;width: 150px;margin-left: 25px;">Fixed margin <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_margin_fixed\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_donor_margin_fixed" id="addeditform_donor_margin_fixed" value="' + (id !== undefined? eval("Tasks.task" + id + ".margin_fixed") : "" ) + '"  maxlength="2" style="width: 60px;" />'+
								'<div class="addedit_form_capts" style="text-align: right;width: 210px;">Relative margin(%) <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_margin_relative\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_donor_margin_relative" id="addeditform_donor_margin_relative" value="' + (id !== undefined? eval("Tasks.task" + id + ".margin_relative") : "" ) + '"  maxlength="2" style="width: 60px;" />'+

							'<!--  What should I do if the product no more available at the donor market  , ( закомментированные fieds_to_insert fieds_to_update)  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="width: 500px;">What should I do if the product no more available at the marketplace<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_what_to_do_product_not_exists\').bPopup();" /> : </div>'+
								  '<select name="addeditform_what_to_do_product_not_exists" id="addeditform_what_to_do_product_not_exists">'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") < 1 )? "selected" : "" ) + '>Nothing</option>'+
								  	'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") == 1 )? "selected" : "" ) + '>Delete this product from store</option>'+
								  	'<option value="2" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") == 2 )? "selected" : "" ) + '>Set "Out of Stock" status for this product</option>'+
								  	'<option value="4" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") > 3 )? "selected" : "" ) + '>Set 0 quantity for this product</option>'+
								  '</select>' +

							'<!--  Do not update the price after first scraping , Insert grabbed products as ""Disabled"   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_donot_update_price">Do not update the Price after grabbing</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_donot_update_price\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_donot_update_price" ' + (id !== undefined? eval("Tasks.task" + id + ".donot_update_price") : "" ) + ' style="width: 30px;">' + 

								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_create_disabled">Insert products in "Out Of Stock" status into my store</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_create_disabled\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_create_disabled" ' + (id !== undefined? eval("Tasks.task" + id + ".create_disabled") : "" ) + ' style="width: 30px;">' + 

							'<!--  Get ATTRIBUTES, Do not update Options   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_get_options">Get product Attributes (if available)</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_get_options\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_get_options" ' + (id !== undefined? eval("Tasks.task" + id + ".get_options") : "checked" ) + ' style="width: 30px;">' + 

								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_options">Do not update Attributes after grabbing</label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_options\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_options" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_options") : "" ) + ' style="width: 30px;">' +

							'<!--  SEO URL , Comment  //-->' +
							'<hr />'+
								'<!-- <div class="addedit_form_capts" style="margin-bottom: 15px;">SEO URL<img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_seourl\').bPopup();" /> : </div>'+
								'<select name="addeditform_seo_url" id="addeditform_seo_url">'+
									'<option value="0" >OFF</option>'+
							  		'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".seo_url") > 0 )? "selected" : "" ) + '>ON</option>'+
							  	'</select><br /> //-->'+
								'<div class="addedit_form_capts">Comment : </div>'+
								'<input type="text" id="addeditform_comment" name="addeditform_comment" value="' +  (id !== undefined? eval("Tasks.task" + id + ".comment") : "" )  + '" style="width:500px;" />'+
							'</div>'
							);
	$("#dialog_form").dialog({ width: 1200 ,
										buttons: [{
	                                               	text: (id !== undefined? "Edit task" : "Create new task" ),
	                                               	click: function() { get_form_data(id);$(this).dialog("close") }
	                                                },{
	                                              	text: "Cancel", click: function() { $(this).dialog("close"); }
	                                      }]});
		
}

/*
 *  GET DATA FROM add/edit FORM, SEND AJAX AND RELOAD PAGE
 */
function get_form_data(id){
	if(id !== undefined){
		action_close(id);
	}
	var action = (id !== undefined? "edit":"add");
	var ins_id = (id !== undefined? id:"");
	var category_id = '';
	$("input[name='addeditform_category_id[]']").each( function () {
		if($(this).is(':checked') == true){
			category_id += $(this).val() + ',';
		}
	 });
	 var fields_to_insert = '';
	 /*$("input[name='addeditform_fields_to_insert[]']").each( function () {
			if($(this).is(':checked') == true){
				fields_to_insert += $(this).val() + ',';
			}
	 });*/
	 var fields_to_update = '';
	 /*$("input[name='addeditform_fields_to_update[]']").each( function () {
			if($(this).is(':checked') == true){
				fields_to_update += $(this).val() + ',';
			}
	});*/
	$.post('' , {"action" : action ,
				 "data[name]" : $("#addeditform_name").val(),
				 "data[state]" : $("#addeditform_state").val(),
				 "data[priority]" : $("#addeditform_priority").val(),
				 "data[category_urls]" : $("#addeditform_category_urls").val(),
				 "data[product_urls]" : $("#addeditform_product_urls").val(),
				 "data[category_id]" : category_id,
				 "data[manufacturer_id]" : $("#addeditform_manufacturer").val(),
				 "data[do_not_update_manufacturer]" : $("#addeditform_do_not_update_manufacturer").is(':checked')?1:0,
				 "data[tax_class_id]" : $("#addeditform_taxclass").val(),
				 "data[do_not_update_taxclass]" : $("#addeditform_do_not_update_taxclass").is(':checked')?1:0,
				 "data[main_image_limit]" : $("#addeditform_main_image_limit").val(),
				 "data[description_image_limit]" : $("#addeditform_description_image_limit").val(),
				 "data[do_not_upload_description_image]" : $("#addeditform_do_not_upload_description_image").is(':checked')?1:0,
				 "data[image_folder]" : $("#addeditform_image_folder").val(),
				 "data[products_quantity]" : $("#addeditform_products_quantity").val(),
				 "data[donor_currency]" : $("#addeditform_donor_currency").val(),
				 "data[margin_fixed]" : $("#addeditform_donor_margin_fixed").val(),
				 "data[margin_relative]" : $("#addeditform_donor_margin_relative").val(),
				 "data[what_to_do_product_not_exists]" : $("#addeditform_what_to_do_product_not_exists").val(),
				 "data[donot_update_price]" : $("#addeditform_donot_update_price").is(':checked')?1:0,
				 "data[create_disabled]" : $("#addeditform_create_disabled").is(':checked')?1:0,
				 "data[get_options]" : $("#addeditform_get_options").is(':checked')?1:0,
				 "data[do_not_update_options]" : $("#addeditform_do_not_update_options").is(':checked')?1:0,
				 "data[get_attributes]" : $("#addeditform_get_attributes").is(':checked')?1:0,
				 "data[do_not_update_attributes]" : $("#addeditform_do_not_update_attributes").is(':checked')?1:0,
				 "data[fields_to_insert]" : fields_to_insert,
				 "data[fields_to_update]" : fields_to_update,
				 "data[seo_url]" : $("#addeditform_seo_url").val(),
				 "data[comment]" : $("#addeditform_comment").val(),
				 "id" : ins_id
				 } ,
			function(data){
					if(ConsoleMode){
						 console.log(data);
					 }
					// demo mode
					if(data.result !== undefined){
						if(data.result == "demo"){
							alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
						}
					}else{
						location.reload();
					}
	 		}
	);
}

/*
 * 
 */
function get_categories_choice(id){
	var res = '';
	var IDS = [];
	var cls = "odd";
	if(id !== undefined){
		eval('var IDS = Tasks.task' + id + '.category_id;');
	}
	$.each(Categories , function( index, value ) {
		if(cls == "even"){
			cls = "odd";
		}else{
			cls = "even";
		}
		res += '<div class="' + cls + '">';
		var checked = '';
		$.each(IDS , function( id_index, id_value ) {
			if(index.substr(3) == id_value){
				checked = 'checked="checked"';
			}
		});
		res += '<input type="checkbox" name="addeditform_category_id[]" value="' + index.substr(3) + '" ' + checked + ' style="width: 25px;" id="addeditform_category_id' + index.substr(3) + '" />';
		res +=  '<label for="addeditform_category_id' + index.substr(3) + '">' + value + '</label>';
		res += '</div>';
	});
	return res;
}


function get_manufacturers_choice(id){
	var res = '<option value="0">Do not set</option>';
	$.each(Manufacturers , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".manufacturer")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}

function get_taxclass_choice(id){
	var res = '<option value="0">Do not set</option>';
	$.each(Taxclasses , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".taxclass")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}


function get_donor_currency_choice(id){
	var res = '';
	$.each(Currencies , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".donor_currency")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}


function  get_fields_choice(id , update){
	var type = update == true?"update":"insert";
	var res = '<div class="addedit_form_fields_divs">';
	res += '<div style="float:left;margin-left:15px;">';
	var count = 0;
	if(id !== undefined){
		eval('var FIELDS = Tasks.task' + id + '.fields_to_' + type + ';');
	}
	$.each(Fields , function( index, value ) {
		var checked = '';
		if(id !== undefined){
			$.each(FIELDS , function( f_index, f_value ) {
				if(f_value == index){
					checked = 'checked';
				}
			});
		}else{
			checked = 'checked';
		}
		res += '<input type="checkbox" name="addeditform_fields_to_' + type + '[]" value="' + index + '" ' + checked + ' style="width: 25px;" id="addeditform_fields_to_' + type + '_' + index + '" />';
		res +=  '<label for="addeditform_fields_to_' + type + '_' + index + '">' + value + '</label>';
		res +=  '<br />';
		count++;
		if(count > 3){
			res +=  '</div><div style="float:left;margin-left:15px;">';
			count = 0;
		}
	});
	res += '</div>';
	res +=  '</div>';
	return res;
}


/*
 *  open actions block
 */
function open_actions(id){
	var open = $("#actions_tr_" + id).css("display");
	if(open == 'none'){
		$(".actions").css("display" , "none");
		$("#actions_tr_" + id).css("display" , "table-row");
	}else{
		$("#actions_tr_" + id).css("display" , "none");
	}
}


function clear_ids_from_coma(str){
	str = str.toString();
	if(str.substr(-1) === ","){
		str = str.substr(0 , str.length - 1);
	}
	return str;
}

/*
 *  action delete
 */
function action_delete(ids , with_products){
	alertify.set({ buttonReverse: true });
	alertify.set({ labels: { ok: "Delete", cancel: "Cancel" } });
	alertify.confirm("Are you sure you want to delete this task?", function (e) {
		if (e) {
			var ids_submit = '';
	    	var idsArr = new Array();
	    	if(ids == "all"){
	    		$(".ins_checkboxes").each( function () {
	    			if($(this).is(':checked') == true && $(this).attr("id") !== "ins_checkboxs_all"){
	    				ids_submit += $(this).attr("id").substr(13)+ ",";
	    				idsArr.push($(this).attr("id").substr(13));
	    			}
	    		});
	    	}else{
	    		idsArr.push(ids);
	    		ids_submit = ids;
	    		action_close(ids);
	    	}
			$.post('' , {"action" : "delete" , "task_id" : clear_ids_from_coma(ids_submit) , "with_products" : with_products} ,
				function(data){
					if(ConsoleMode){
						console.log(data);
					}
					// demo mode
					if(data.result !== undefined){
						if(data.result == "demo"){
							alertify.error('Sorry, you are unable to do this in the DEMO mode.');
						}
					}else{
						for(var i=0; i < idsArr.length; i++) {
							$("tr#instruction_tr_" + idsArr[i]).remove();
							$("tr#actions_tr_" + idsArr[i]).remove();
						}
						/*if($("tr.instruction_trs").length < 1){
							$("tr#instruction_table_header").html('<td colspan="10" >You have not yet made ​​any task for your MSPRO. Please, create the first one.</td>');
						}*/
						redirect_to_tasks()
						//location.reload();
					}
				}
			);
		} else {
			alertify.error('You\'ve cancelled the "Delete" action');
			return false;
		}
	});
}


/*
 *  action restart
 */
function action_restart(ids){
	alertify.set({ buttonReverse: true });
	alertify.set({ labels: { ok: "Restart", cancel: "Cancel" } });
	alertify.confirm("Are you sure you want to restart all instructions of this task?", function (e) {
		if (e) {
			var ids_submit = '';
	    	var idsArr = new Array();
	    	if(ids == "all"){
	    		$(".ins_checkboxes").each( function () {
	    			if($(this).is(':checked') == true && $(this).attr("id") !== "ins_checkboxs_all"){
	    				ids_submit += $(this).attr("id").substr(13)+ ",";
	    				idsArr.push($(this).attr("id").substr(13));
	    			}
	    		});
	    	}else{
	    		idsArr.push(ids);
	    		ids_submit = ids;
	    		action_close(ids);
	    	}
			$.post('' , {"action" : "restart" , "task_id" : clear_ids_from_coma(ids_submit) } ,
				function(data){
					if(ConsoleMode){
						console.log(data);
					}
					// demo mode
					if(data.result !== undefined){
						if(data.result == "demo"){
							alertify.error('Sorry, you are unable to do this in the DEMO mode.');
						}
					}else{
						alertify.success("This task was successfully restarted");
						location.reload();
					}
				}
			);
		} else {
			alertify.error('You\'ve cancelled the "Restart" action');
			return false;
		}
	});
}

/*
 * set switch action
 */
function change_switch(res, ids){
	var value = res.value;
	if(value !== "none"){
		var ids_submit = '';
    	var idsArr = new Array();
    	if(ids == "all"){
    		$(".ins_checkboxes").each( function () {
    			if($(this).is(':checked') == true && $(this).attr("id") !== "ins_checkboxs_all"){
    				ids_submit += $(this).attr("id").substr(13)+ ",";
    				idsArr.push($(this).attr("id").substr(13));
    			}
    		});
    	}else{
    		idsArr.push(ids);
    		ids_submit = ids;
    	}
    	$.post('' , {"action" : "switch" , "task_id" : clear_ids_from_coma(ids_submit) , "switch" : value} , function(data){
    		if(data.result == "success"){
    			if(value > 0){
    				for(var i=0; i < idsArr.length; i++) {
						$("#state_td_" + idsArr[i]).html("<span class='tasks_table_state_td_on'>ON</span>");
					}
    			}else{
    				for(var i=0; i < idsArr.length; i++) {
						$("#state_td_" + idsArr[i]).html("<span class='tasks_table_state_td_off'>OFF</span>");
					}
    			}
    		}
    		if(data.result == "demo"){
    			alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
    		}
    		if(ids !== "all"){
    			$("#actions_tr_" + ids).css("display" , "none");
    		}
    	})
	}
}



/*
 *  set priority actions
 */

 
function change_priority(res , ids){
	var value = res.value;
	if(value !== "none"){
    	var ids_submit = '';
    	var idsArr = new Array();
    	if(ids == "all"){
    		$(".ins_checkboxes").each( function () {
    			if($(this).is(':checked') == true && $(this).attr("id") !== "ins_checkboxs_all"){
    				ids_submit += $(this).attr("id").substr(13)+ ",";
    				idsArr.push($(this).attr("id").substr(13));
    			}
    		});
    	}else{
    		idsArr.push(ids);
    		ids_submit = ids;
    	}
    	$.post('' , {"action" : "set_priority" , "task_id" : clear_ids_from_coma(ids_submit) , "priority" : value} , function(data){
    		if(data.result == "success"){
    			switch(value){
    				case '2':
    					for(var i=0; i < idsArr.length; i++) {
    						$("#priority_td_" + idsArr[i]).css("background-color" , "palevioletred");
    						$("#priority_td_" + idsArr[i]).html("Urgent");
    					}
    					break;
    				case '1':
    					for(var i=0; i < idsArr.length; i++) {
    						$("#priority_td_" + idsArr[i]).css("background-color" , "peachpuff");
    						$("#priority_td_" + idsArr[i]).html("High");
    					}
    					break;
    				default:
    					for(var i=0; i < idsArr.length; i++) {
    						$("#priority_td_" + idsArr[i]).css("background-color" , "");
    						$("#priority_td_" + idsArr[i]).html("Normal");
    					}
    					break;
    			}
    		}
    		if(data.result == "demo"){
    			alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
    		}
    		if(ids !== "all"){
    			$("#actions_tr_" + ids).css("display" , "none");
    		}
    	})
	}
}


/*
 *  search action
 */
function action_search(){
	var term = $("#form_search_input").val();
	$.post('' , {"action" : "search" , "searchTerm" : term} ,
		function(data){
			if(ConsoleMode){
				console.log(data);
			}
			// demo mode
			if(data.result !== undefined){
				if(data.result == "demo"){
					alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
				}
			}else{
				redirect_to_tasks();
			}
		}
	);
}


/*
 *  number per page action
 */
function change_num(){
	var num = $("#actions_num").val();
	$.post('' , {"action" : "numperpage" , "numPerPage" : num} ,
		function(data){
			if(ConsoleMode){
				console.log(data);
			}
			// demo mode
			if(data.result !== undefined){
				if(data.result == "demo"){
					alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
				}
			}else{
				redirect_to_tasks();
			}
		}
	);
}

function redirect_to_tasks(){
	var lochref = location.href.substr(0 , location.href.indexOf('/tasks')) + "/tasks";
	document.location.href = lochref;
}


function action_close(id){
	$("#actions_tr_" + id).css("display" , "none");
}

</script>
                        	
<div class="blocks">
    <h1>Tasks for your MultiScraper</h1>
    <div class="text-block">
        
 <?php
 // create "Grabbed products list" button (green or grey depending on products grabbed number)
 
 
 
 ?>     
   
        <input type="button" value=" + Create new task" class="btn btn-blue" onclick="action_form();" id="tasks_add_button" style="float: right;margin: 10px 30px 10px 0;"/>
        <input type="button" value="Products grabbed" class="btn btn-<?php echo $products_grabbed_all?"green":"grey"; ?>" onclick="<?php echo $products_grabbed_all?"show_grabbed_table('all');":""; ?>" id="tasks_grabbed_product_button" style="float: left;margin: 10px 30px 10px 30px;"/>
        <div class="clear"></div>
        
        <div id="tasks-form" class="ms_form" style="padding: 10px;width: 1240px;padding-bottom: 0;">
        	<div id="form-content">
        				
            		<table class="list" id="parser_instruction_table">
					        <thead>
					            <tr id="actions_tr_all"><td colspan="11">
					               <?php if(is_array($instructions) && count($instructions) > 1){ ?>
					               <div id="actions_tr_all_left">
					                  <input type="checkbox" class="ins_checkboxes" id="ins_checkboxs_all" style="width: 40px; padding:0;" />
					                  <input type="button" value="With marked >>" class="btn btn-grey actions_button" id="actions_button_all" style="width: 105px;" onclick="open_actions()" />
					                  &nbsp;&nbsp;&nbsp;
		                              <div id="actions_to_all_form_wrapper" style="display:none;">
		                                  Switch: 
		                                  <select onchange="change_switch(this , 'all');" id="actions_switch_select_all">
		                                      <option value="none" "selected" >--</option>'
		                                      <option value="1">ON</option>';
					        				  <option value="0">OFF</option>'
		                                  </select>
		                                  <input type="button" value="Restart" class="btn btn-blue" onclick="action_restart('all');" style="margin-left:10px;width:85px;" />
		                                  &nbsp;Set priority: 
		                                  <select onchange="change_priority(this , 'all');" id="actions_set_priority_all">
		                                      <option value="none" "selected">-----</option>
		                                      <option value="0">Normal</option>
		                                      <option value="1">High</option>
		                                      <option value="2">Urgent</option>
		                                  </select>
		                                  <input type="button" value="Delete" class="btn btn-red" onclick="action_delete('all' , false);" style="margin-left:10px;width:60px;" />
		                                  <input type="button" value="Delete with products" class="btn btn-red" onclick="action_delete('all' , true);" style="margin-left:10px;width:135px;" />
		                              </div>
		                           </div>
		                           <?php } ?>
		                           <?php if( isset($instructions_data['count_all_ins']) && $instructions_data['count_all_ins'] > 10) { ?>
		                           <div id="actions_tr_all_right">
		                              <div class="form_search" id="form_search">
                                        <input type="text" class="form-control" id="form_search_input" value="<?php echo (isset($searchTerm))?$searchTerm:""; ?>" />
                                        
                                      </div>
                                      <div id="form_pager">Show: 
                                          <select onchange="change_num();" id="actions_num">
    		                                      <option value="none">All</option>
    		                                      <option value="10" <?php echo (int) $numberPerPage == 10?"selected":''; ?>>10</option>
    		                                      <option value="20" <?php echo (int) $numberPerPage == 20?"selected":''; ?>>20</option>
    		                                      <option value="50" <?php echo (int) $numberPerPage == 50?"selected":''; ?>>50</option>
    		                                      <option value="100" <?php echo (int) $numberPerPage == 100?"selected":''; ?>>100</option>
    		                                      <option value="200" <?php echo (int) $numberPerPage == 200?"selected":''; ?>>200</option>
    		                              </select>
    		                          </div>
		                           </div>
		                           <?php } ?>
					            </td></tr>
					            
					            <tr id="instruction_table_header">
					            <?php 
					        	if(is_array($instructions) && count($instructions) > 0){
					        	?>
					        	    <td class="left" style="width:30px;"></td>
					                <td class="left" style="width:70px;"></td>
					                <td class="left" style="width: 250px;">Task name</td>
					                <td class="left" style="width: 50px;">Switched</td>
					                <td class="left" style="width: 50px;">Priority</td>
					                <td class="left" style="width: 150px;" >Category</td>
					                <td class="left" style="width: 150px;">Manufacturer</td>
					                <!--  <td class="left" style="width: 50px;">SeoURL</td>  //-->
					                <td class="left" style="width: 100px;">Margins<br />fixed <b>/</b> relative</td>
					                <td class="left" style="width: 100px;">Products<br />found <b>/</b> grabbed</td>
					                <td class="left">Comment</td>
					            <?php
					        	}else{ 
					            ?>
					            	<td colspan="10" >
					            	  <p style="margin: 0;padding: 15px;font-size: 1.3em;color: #d14030;">
					            	      You have not yet made ​​any task for your MultiScraper. Please, <a onclick="action_form();" style="text-decoration:underline;cursor:pointer;">create</a> the first one.
					            	  </p>
					            	  <script>jQuery(document).ready(function($) { alertify.error('You have not yet made ​​any task for your MultiScraper.\r\n Please, create the first one.'); });</script>
					            	</td>
					            <?php } ?>
					            </tr>
					        </thead>
					        <tbody>
					        	<?php 
					        		if(is_array($instructions) && count($instructions) > 0){
					        			$tr = '';
					        			foreach($instructions as $instruction){
					        				// prepare category urls
					        				$cats = '';
					        				$cats_res = $instruction['category_urls'];
					        				if(count($cats_res) > 0){
					        					foreach($cats_res as $cat_res){
					        						$cats .= $cat_res."\n";
					        					}
					        				}
					        				// prepare product urls
					        				$products = '';
					        				$products_res = $instruction['product_urls'];
					        				if(count($products_res) > 0){
					        					foreach($products_res as $product_res){
					        						$products .= $product_res."\n";
					        					}
					        				}
					        				/*****   PREPARE DATA FOR DISPLAY   ********/ 
					        				// name
					        				$name = strlen( $instruction['name'] ) > 70? substr($instruction['name'] , 0 , 70). '...' : $instruction['name'];
					        				if(isset($searchTerm) && strlen($searchTerm) > 1){
					        				    $name = str_ireplace($searchTerm , '<span style="font-weight:bold;color:#2D75FA;background-color:yellow;">' . $searchTerm . '</span>' , $name);
					        				}
					        				// state
					        				if($instruction['state'] > 0){
					        					$state = '<span class="tasks_table_state_td_on">ON</span>';
					        				}else{
					        					$state = '<span class="tasks_table_state_td_off">OFF</span>';
					        				}
					        				// priority
					        				switch($instruction['priority']){
					        					case 2:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="background-color:palevioletred;">Urgent</td>';
					        						break;
					        					case 1:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="background-color:peachpuff;">High</td>';
					        						break;
					        					default:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="">Normal</td>';
					        				}
					        				// category
					        				//$category = isset($categories[$instruction['category_id']])?$categories[$instruction['category_id']]:"";
					        				$category = '';
					        				if(is_array($instruction['category_id']) && count($instruction['category_id']) > 0){
					        					foreach($instruction['category_id'] as $category_id){
					        						if(isset($categories[$category_id])){
					        							$category .=  $categories[$category_id] . '<br />';
					        						}
					        					}
					        				}
					        				// manufacturer
					        				$manufacturer = isset($manufacturers[$instruction['manufacturer_id']])?$manufacturers[$instruction['manufacturer_id']]:"";
					        				// seo url
					        				if($instruction['seo_url'] > 0){
					        					$seo_url = '<span class="tasks_table_state_td_on">ON</span>';
					        				}else{
					        					$seo_url = '<span class="tasks_table_state_td_off">OFF</span>';
					        				}
					        				// margins
					        				$margins = $instruction['margin_fixed'] . '<b> / </b>'. $instruction['margin_relative'] .'%';
					        				// products_found_parsed
					        				$products_found_parsed = $instruction['products_found_grabbed']['found'] . '<b> / </b>'. ( $instruction['products_found_grabbed']['grabbed'] > 0? '<span style="color:green;">'.$instruction['products_found_grabbed']['grabbed'].'</span>' : $instruction['products_found_grabbed']['grabbed']) ;
					        				if($instruction['products_found_grabbed']['grabbed'] > 0){
					        					$products_found_parsed .= '<img src="' . $this->config->item("base_url") . 'public/images/list.png" onclick="show_grabbed_table(\'' . $instruction['id'] . '\');" style="margin-left: 14px;"/>';
					        				}
					        				// comment
					        				$comment = strlen( $instruction['comment'] ) >70? substr($instruction['comment'] , 0 , 70). '...' : $instruction['comment'];
					        				if(isset($searchTerm) && strlen($searchTerm) > 1){
					        				    $comment = str_ireplace($searchTerm , '<span style="font-weight:bold;color:#2D75FA;background-color:yellow;">' . $searchTerm . '</span>' , $comment);
					        				}
					        				
					        				$tr .= '<tr id="instruction_tr_'.$instruction['id'].'" class="instruction_trs">
		                                                <td>
		                                                     <input type="checkbox" class="ins_checkboxes" id="ins_checkbox_'.$instruction['id'].'" style="width: 30px;" />
		                                                </td>
										                <td>
										                	<input type="button" value="Actions" class="btn btn-blue actions_button" id="actions_button_'.$instruction['id'].'" style="width: 65px;" onclick="open_actions('.$instruction['id'].')" />
										                </td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$name.'</td>
										                <td class="left" id="state_td_'.$instruction['id'].'">'.$state.'</td>
										                '.$priority.'
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$category.'</td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$manufacturer.'</td>
										                <!-- <td class="left">'.$seo_url.'</td> //-->
										                <td class="left">'.$margins.'</td>
										                <td class="left">'.$products_found_parsed.'</td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$comment.'</td>
										            </tr>';
					        				
					        				
					        				// ГОТОВИМ ACTIONS
					        				$select_switch = '<select onchange="change_switch(this , '.$instruction['id'].')" id="actions_switch_select_'.$instruction['id'].'">';
					        				$select_switch .= '<option value="1" '. ($instruction['state'] > 0?'selected':'') .' >ON</option>';
					        				$select_switch .= '<option value="0" '. ($instruction['state'] > 0?'':'selected')   .' >OFF</option>';
					        				$select_switch .= '</select>';
					        				
					        				$select_priority = '<select onchange="change_priority(this , '.$instruction['id'].')" id="actions_priority_select_'.$instruction['id'].'">';
					        				/*for($i = 0; $i < 3; $i++){
					        					$select_priority .= '<option value="'.$i.'" ';
					        					if($i == $instruction['priority']){
					        						$select_priority .= 'selected';
					        					}
					        					$select_priority .= '>'.$this->lang->line('tasks_priority_vars_'.$i).'</option>';
					        				}*/
					        				$select_priority .= '<option value="0" ' . ($instruction['priority'] < 1?"selected":'') . '>Normal</option>';
					        				$select_priority .= '<option value="1" ' . ($instruction['priority'] == 1?"selected":'') . '>High</option>';
					        				$select_priority .= '<option value="2" ' . ($instruction['priority'] > 1?"selected":'') . '>Urgent</option>';
					        				$select_priority .= '</select>';
					        				
					        				
					        				$tr .= '<tr id="actions_tr_'.$instruction['id'].'" class="actions"><td colspan="11">
					        							<input type="button" value="Delete with products" class="btn btn-red" onclick="action_delete('.$instruction['id'].' , true);" style="float:right;margin-left:10px;width:135px;" />	
					        							<input type="button" value="Delete" class="btn btn-red" onclick="action_delete('.$instruction['id'].' , false);" style="float:right;margin-left:20px;width:60px;" />
					        							<div style="float:right;margin-left:10px;">
					        								Set priority:
					        								'.$select_priority.'
					        							</div>
					        							<input type="button" value="Restart" class="btn btn-blue" onclick="action_restart('.$instruction['id'].');" style="float:right;margin-left:20px;width:85px;" />
					        							<input type="button" value="Edit task" class="btn btn-blue" onclick="action_form('.$instruction['id'].');" style="float:right;margin-left:20px;width:100px;" />
					        							<div style="float:right;margin-left:10px;">
					        								Switch:
					        								'.$select_switch.'
					        							</div>
					        							<img src="'.$this->config->item("base_url").'public/images/arrow_top.png" onclick="action_close('.$instruction['id'].' , false);" style="float:left;margin-left:20px;height: 25px;" />
					        									
					        							
					        						</td></tr>';
					        			}
					        			echo $tr ;
					        			
					        		}
					        	?>
			           		</tbody>
			        </table>
			        <?php echo $pager; ?>
            	</div>
            	
            	
            	
            	
            	
            	
            </div>
    </div>
</div>




<!--   "PRODUCTS LISTING" URLs TIP  //-->
 <div id="bpopup_ai_listing" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<b>Listing URL</b> is a page with a list of products<br />
    	This may be a Category page, search results page or any other product listing<br /><br /><br />
    	The examples of <b>Listing URL</b>:<br />
    	<?php 
    	foreach($this->config->item("ms_tip_cats") as $tip_cat_link){
    	    echo '<a href="' . $tip_cat_link . '" target="_blank">' . $tip_cat_link . '</a><br />';
    	}
    	?>	
    	
</div>
<!--   "PRODUCT" URLs TIP  //-->
 <div id="bpopup_ai_product" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<b>Product URL</b> is the particular product page URL
</div>


<!--   Push into Category TIP  //-->
 <div id="bpopup_ai_category" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	Grabbed products will be inserted into the choosen category (or several categories) of your <?php echo MSPRO_CMS_DISPLAY_NAME ?> store.<br /><br />
    	You may choose one or several
</div>
<!--   Tax class TIP  //-->
 <div id="bpopup_ai_taxclass" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	You may manage your Tax classes in your <?php echo MSPRO_CMS_DISPLAY_NAME ?> admin panel: <b>Settings -> Localization -> Taxes -> Tax classes</b><br /><br />
    	The one that be chosen here will be applied to all products grabbed by this Task 
</div>
<!--   DO not update Tax class TIP  //-->
 <div id="bpopup_ai_do_not_update_taxclass" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked the Tax class will not be updated after grabbing<br /><br />
    	This may be useful if you are going to change Tax class manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products
</div>
<!--   Main image limit TIP  //-->
 <div id="bpopup_ai_main_image_limit" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	You may limit the main product's images (the main image and other images in gallery/slideshow)<br /><br />
    	Set this setting to "-1" to break the limit
</div>
<!--   Images in description limit TIP  //-->
 <div id="bpopup_ai_description_image_limit" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	You may limit the images in the product's description, all other will be cut from the description<br /><br />
    	Set this setting to "-1" to break the limit
</div>
<!--   DO not upload description image TIP  //-->
 <div id="bpopup_ai_do_not_upload_description_image" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked - the images in description will not be uploaded to your server (the images will be displayed from the external source - from the donator-website)<br /><br />
    	This may be useful if you want to save disk space
</div>

<!--   Manufacturer TIP //-->
 <div id="bpopup_ai_manufacturer" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If chosen <b>"Do not set"</b> the MultiScraper will try to define manufacturer by itself<br />
    	But this feature is implemented NOT FOR ALL donor marketplaces<br /><br />
    	Or you may define the particular manufacturer for all products grabbed by this Task
</div>
<!--   Do not update Manufacturer after grabbing TIP  //-->
 <div id="bpopup_ai_do_not_update_manufacturer" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked the manufacturer will not be updated after grabbing<br /><br />
    	This may be useful if you are going to change manufacturer manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products
</div>


<!--   Separate image folder TIP  //-->
 <div id="bpopup_ai_imageFolder" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	"By default, MultiScraper will grab all images which it will find relates to particular product (images in slideshow, in the description) and place them into your image folder.<br /><br />
    	All images grabbed by this particular task may be located in custom subfolder (of your main <b>image/data/</b> image location) configured here.<br /><br />
    	You may create the any nesting level subfolder. The Examples are:<br /><br /><br />
    	<span style="color: grey;font-size: 1.4em;">/mysubfolder/products/</span><br />
    	<span style="color: grey;font-size: 1.4em;">/mysubfolder1/test/mysubfolder1</span><br />
    	<span style="color: grey;font-size: 1.4em;">/onelevelsubfolder/</span><br /><br /><br />
    	Leave this field blank and all images will be pushed into your main  <b>image/data/</b> folder (<b>image/catalog/</b> for 2.x version)<br />
</div>
<!--   Products quantity TIP //-->
 <div id="bpopup_ai_products_quantity" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	This is the quantity of each product grabbed by this Task
</div>


<!--   Donor market currency TIP  //-->
 <div id="bpopup_ai_currency" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	You have to configure the currency of the DONATOR WEBSITE.<br /><br />
    	If your store is in the another currency MultiScraper will translate the price into your currency using your <?php echo MSPRO_CMS_DISPLAY_NAME ?> exchange rates.
</div>
<!--   Margin Fixed TIP  //-->
 <div id="bpopup_ai_margin_fixed" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	This is the fixed margin in your store's currency. For example you want to add $10 to the price of each product grabbed<br /><br />
    	This margin will be applied to the price AFTER been converted into your store default currency
</div>
<!--  Margin relative TIP  //-->
 <div id="bpopup_ai_margin_relative" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	This is the relative margin (in percents) in your store's currency. For example you want to add 20% to the price of each product grabbed<br /><br />
    	This margin will be applied to the price AFTER been converted into your store default currency
</div>

<!--  What should I do if the product no more available at the donor market TIP  //-->
 <div id="bpopup_ai_what_to_do_product_not_exists" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	What should the MultiScraper do with the existing product if while updating it can't find the original product at the donator-website (it was removed or Sold out).
</div>

<!--  Do not update the price after grabbing TIP  //-->
 <div id="bpopup_ai_donot_update_price" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked the Price will not be updated after grabbing<br /><br />
    	This may be useful if you are going to change Price manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products
</div>
<!--  Insert products in "Out Of Stock" status into my store TIP  //-->
 <div id="bpopup_ai_create_disabled" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked - the grabbed products will be inserted in your store in the "Out Of Stock" status<br /><br />
    	This may be useful if you don't want the visitors of your store be able to buy these products for a while
</div>

<!-- Get product Options (if available) TIP  //-->
 <div id="bpopup_ai_get_options" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	Uncheck if you don't want MultiScraper grab product Attributes (Sizes, Colors etc)
</div>
<!--  Do not update Options after grabbing TIP  //-->
 <div id="bpopup_ai_do_not_update_options" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	If checked - MultiScraper will NOT update the product Attributes while updating the grabbed product<br /><br />
    	May be needed if you are going to edit the Attributes after the products are grabbed
</div>

<!--   SEO URL TIP  //-->
 <div id="bpopup_ai_seourl" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	MultiScraper just use the <?php echo MSPRO_CMS_DISPLAY_NAME ?> built-it SEO url mechanism.<br /><br />
    	Switch ON this setting and MultiScraper will create auto SEO url for each product grabbed.<br /><br />
    	if you don't know about SEO url, you may swith ON or OFF this feature, nothing will be damaged.
</div>





<?php 
echo $products_grabbed_popup;
?>
