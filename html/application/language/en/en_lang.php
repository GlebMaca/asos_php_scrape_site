<?php



/*****************  TASKS **************/
$lang['tasks_title']= "Tasks for your MultiScraper";
$lang['no_tasks_for_maspro']= "You have not yet made ​​any task for your MSPRO. Please, create the first one";


// table and actions
$lang['tasks_table_name']= "Task name";
$lang['tasks_table_state']= "Switched";
$lang['tasks_table_priority']= "Priority";
$lang['tasks_table_category']= "Category";
$lang['tasks_table_manufactuer']= "Manufacturer";
$lang['tasks_table_seourls']= "SeoURL";
$lang['tasks_table_margins']= "Margins fixed/relative";
$lang['tasks_table_products_found_parsed']= "Products found/grabbed";
$lang['tasks_table_comment']= "Comment";

$lang['actions_button']= "Actions";
$lang['actions_delete_button']= "Delete";
$lang['actions_delete_products_button']= "Delete with products";
$lang['actions_restart_button']= "Restart";
$lang['actions_edit_button']= "Edit task";


$lang['set_switch_action']= "Switch";
$lang['tasks_table_state_on']= "ON";
$lang['tasks_table_state_off']= "OFF";

$lang['tasks_table_seourl_on']= "ON";
$lang['tasks_table_seourl_off']= "OFF";


$lang['set_priority_action']= "Set priority";
$lang['tasks_priority_vars_0']= "Normal";
$lang['tasks_priority_vars_1']= "High";
$lang['tasks_priority_vars_2']= "Urgent";


$lang['tasks_what_to_do_product_not_exists_vars_0']= "Nothing";
$lang['tasks_what_to_do_product_not_exists_vars_1']= "Delete this product from store";
$lang['tasks_what_to_do_product_not_exists_vars_2']= 'Set "Out of Stock" status for this product';
$lang['tasks_what_to_do_product_not_exists_vars_3']= "Disable this product (make inactive)";
$lang['tasks_what_to_do_product_not_exists_vars_4']= "Set 0 quantity for this product";




// add/edit form
$lang['tasks_form_name']= "Task name";
$lang['tasks_form_state']= "Switched";
$lang['tasks_form_priority']= "Priority";
$lang['tasks_form_category_urls_1']= '"PRODUCTS LISTING" URLs';
$lang['tasks_form_category_urls_2']= 'each one from new line';
$lang['tasks_form_product_urls_1']= '"PRODUCT" URLs';
$lang['tasks_form_product_urls_2']= 'each one from new line';
$lang['tasks_form_category']= "Push into Category";
$lang['tasks_form_manufacturer']= "Manufacturer";
$lang['tasks_form_taxclass']= "Tax class";
$lang['tasks_form_main_image_limit']= "Main images limit";
$lang['tasks_form_description_image_limit']= "Images in description limit";
$lang['tasks_form_do_not_upload_description_image']= "Do not upload images in description";
$lang['tasks_form_image_folder']= "Separate image folder";
$lang['tasks_form_donor_currency']= "Donor market currency";
$lang['tasks_form_margin_fixed']= "Fixed margin ";
$lang['tasks_form_products_quantity']= "Products quantity";
$lang['tasks_form_margin_relative']= "Relative margin(%) ";
$lang['tasks_form_what_to_do_product_not_exists']= "What should I do if the product no more available at the donor market";
$lang['tasks_form_donot_update_price']= "Do not update the price after grabbing";
$lang['tasks_form_create_disabled']= 'Insert products as "Disabled" into my store';
$lang['tasks_form_get_options']= 'Get product Options (if available)';
$lang['tasks_form_do_not_update_options']= 'Do not update Options after grabbing';
$lang['tasks_form_do_not_update_manufacturer']= 'Do not update Manufacturer after grabbing';
$lang['tasks_form_do_not_update_taxclass']= 'Do not update Tax class after grabbing';
$lang['tasks_form_fieds_to_insert']= "Fields to insert";
$lang['tasks_form_fieds_to_update']= "Fields to update";
$lang['tasks_form_seo_url']= "SEO URL";
$lang['tasks_form_comment']= "Comment";

$lang['tasks_form_no_manufacturer_option']= "Do not set";
$lang['tasks_form_no_taxclass_option']= "Do not set";

$lang['tasks_form_sure_to_delete']= "Are you sure you want to delete this task?";
$lang['tasks_form_sure_to_restart']= "Are you sure you want to restart all instructions of this task?";

$lang['tasks_form_task_restarted']= "This task was successfully restarted";

$lang['tasks_add_button']= " + Add new task";
$lang['tasks_grabbed_product_button']= "Products grabbed";
$lang['tasks_save_button']= "Save";
$lang['tasks_cancel_button']= "Cancel";
$lang['tasks_table_category_urls'] = '"List of Products" Urls';
$lang['tasks_table_category_urls_hint_1'] = "each entry on a new line";
$lang['tasks_table_category_urls_hint_2'] = "exapmle";
$lang['tasks_table_products_urls'] = '"Products" Urls';
$lang['tasks_table_products_urls_hint_1'] = "each entry on a new line";
$lang['tasks_table_products_urls_hint_2'] = "exapmle";
$lang['tasks_table_margin_hint']= "Example: enter 1.15 here for 15% margin";

$lang['tasks_form_button_create'] = "Create new task";
$lang['tasks_form_button_edit'] = "Edit task";


/*   TIPS ON PRODUCT FORM   */
$lang['tasks_form_tip_listing_1'] = "<b>Listing URL</b> is a page with a list of products";
$lang['tasks_form_tip_listing_2'] = "This may be a Category page, search results page or any other product listing";
$lang['tasks_form_tip_listing_3'] = "The examples of <b>Listing URL</b>:";
$lang['tasks_form_tip_listing_4'] = '<a href="http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html" target="_blank">http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html</a>';
$lang['tasks_form_tip_listing_5'] = '<a href="http://www.aliexpress.com/category/100005062/tablet-pcs.html" target="_blank">http://www.aliexpress.com/category/100005062/tablet-pcs.html</a>';

$lang['tasks_form_tip_product_1'] = "<b>Product URL</b> is the particular product page URL";

$lang['tasks_form_tip_category_1'] = "Grabbed products will be inserted into the choosen category (or several categories) of your " . MSPRO_CMS_DISPLAY_NAME . " store";
$lang['tasks_form_tip_category_2'] = "You may choose one or several ";

$lang['tasks_form_tip_taxclass_1'] = "You may manage your Tax classes in your " . MSPRO_CMS_DISPLAY_NAME . " admin panel: <b>Settings -> Localization -> Taxes -> Tax classes</b>";
$lang['tasks_form_tip_taxclass_2'] = "The one that be chosen here will be applied to all products grabbed by this Task ";

$lang['tasks_form_tip_do_not_update_taxclass_1'] = "If checked the Tax class will not be updated after grabbing";
$lang['tasks_form_tip_do_not_update_taxclass_2'] = "This may be useful if you are going to change Tax class manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";

$lang['tasks_form_tip_main_image_limit_1'] = "You may limit the main product's images (the main image and other images in gallery/slideshow)";
$lang['tasks_form_tip_main_image_limit_2'] = 'Set this setting to "-1" to break the limit';

$lang['tasks_form_tip_description_image_limit_1'] = "You may limit the images in the product's description, all other will be cut from the description";
$lang['tasks_form_tip_description_image_limit_2'] = 'Set this setting to "-1" to break the limit';

$lang['tasks_form_tip_do_not_upload_description_image_1'] = "If checked - the images in description will not be uploaded to your server (the images will be displayed from the external source - from the donor market)";
$lang['tasks_form_tip_do_not_upload_description_image_2'] = "This may be useful if you want to save disk space";

$lang['tasks_form_tip_manufacturer_1'] = 'If chosen <b>"Do not set"</b> the MultiScraper will try to define manufacturer by itself';
$lang['tasks_form_tip_manufacturer_2'] = "But this feature is implemented NOT FOR ALL donor markets";
$lang['tasks_form_tip_manufacturer_3'] = "Or you may define the particular manufacturer for all products grabbed by this Task";

$lang['tasks_form_tip_do_not_update_manufacturer_1'] = "If checked the manufacturer will not be updated after grabbing";
$lang['tasks_form_tip_do_not_update_manufacturer_2'] = "This may be useful if you are going to change manufacturer manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";


$lang['tasks_form_tip_imageFolder_0'] = "By default, MultiScraper will grab all images which it will find relates to particular product (images in slideshow, in the description) and place them into your image folder.";
$lang['tasks_form_tip_imageFolder_1'] = "All images grabbed by this particular task may be located in custom subfolder (of your main <b>image/data/</b> image location) configured here.";
$lang['tasks_form_tip_imageFolder_2'] = "You may create the any nesting level subfolder. The Examples are:";
$lang['tasks_form_tip_imageFolder_3'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder/products/</span>';
$lang['tasks_form_tip_imageFolder_4'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder1/test/mysubfolder1</span>';
$lang['tasks_form_tip_imageFolder_5'] = '<span style="color: grey;font-size: 1.4em;">/onelevelsubfolder/</span>';
$lang['tasks_form_tip_imageFolder_6'] = 'Leave this field blank and all images will be pushed into your main  <b>image/data/</b> folder (<b>image/catalog/</b> for 2.x version)';

$lang['tasks_form_tip_products_quantity_1'] = 'This is the quantity of each product grabbed by this Task';

$lang['tasks_form_tip_currency_1'] = "You have to configure the currency of the DONOR MARKET.";
$lang['tasks_form_tip_currency_2'] = "If your store is in the another currency MultiScraper will translate the price into your currency using your " . MSPRO_CMS_DISPLAY_NAME . " exchange rates.";

$lang['tasks_form_tip_margin_fixed_1'] = "This is the fixed margin in your store's currency. For example you want to add $10 to the price of each product grabbed ";
$lang['tasks_form_tip_margin_fixed_2'] = 'This margin will be applied to the price AFTER been converted into your store default currency';

$lang['tasks_form_tip_margin_relative_1'] = "This is the relative margin (in percents) in your store's currency. For example you want to add 20% to the price of each product grabbed";
$lang['tasks_form_tip_margin_relative_2'] = 'This margin will be applied to the price AFTER been converted into your store default currency';

$lang['tasks_form_tip_what_to_do_product_not_exist_1'] = "What should the MultiScraper do with the existing product if while updating it can't find the original product at the donor market (it was removed or Sold out)";

$lang['tasks_form_tip_donot_update_price_1'] = "If checked the Price will not be updated after grabbing";
$lang['tasks_form_tip_donot_update_price_2'] = "This may be useful if you are going to change Price manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";

$lang['tasks_form_tip_create_disabled_1'] = 'If checked - the grabbed products will be inserted in your store with "Disabled" status';
$lang['tasks_form_tip_create_disabled_2'] = "This may be useful if you don't want the visitors of your store view these products for a while";

$lang['tasks_form_tip_get_options_1'] = "Uncheck if you don't want MultiScraper grab product options (Sizes, Colors etc)";

$lang['tasks_form_tip_do_not_update_options_1'] = 'If checked - MultiScraper will NOT update the product options while updating the grabbed product';

$lang['tasks_form_tip_seourl_1'] = "MultiScraper just use the " . MSPRO_CMS_DISPLAY_NAME . " built-it SEO url mechanism.";
$lang['tasks_form_tip_seourl_2'] = "Switch ON this setting and MultiScraper will create auto SEO url for each product grabbed.";
$lang['tasks_form_tip_seourl_3'] = "if you don't know about SEO url, you may swith ON or OFF this feature, nothing will be damaged."; 


/* PRODUCTS GRABBED POPUPS, TABLES AND BUTTONS   */
$lang['tasks_form_grabbed_products_popup_title'] = "Products grabbed by this Task";
$lang['tasks_form_grabbed_products_popup_title_all'] = "Products grabbed by MultiScraper";
$lang['tasks_form_grabbed_products_popup_table_1'] = "No";
$lang['tasks_form_grabbed_products_popup_table_2'] = "Link at your " . MSPRO_CMS_DISPLAY_NAME . " store";
$lang['tasks_form_grabbed_products_popup_table_3'] = "Link at DONOR MARKET";
$lang['tasks_form_grabbed_products_popup_table_4'] = "Price";
$lang['tasks_form_grabbed_products_popup_table_5'] = "Quantity";
$lang['tasks_form_grabbed_products_popup_table_6'] = "Stock status";
$lang['tasks_form_grabbed_products_popup_table_7'] = "Date grabbed";
$lang['tasks_form_grabbed_products_popup_table_8'] = "Date updated";

$lang['tasks_form_grabbed_products_popup_table_status_5'] = "Out Of Stock";
$lang['tasks_form_grabbed_products_popup_table_status_6'] = "2-3 days Out Of Stock";
$lang['tasks_form_grabbed_products_popup_table_status_7'] = "In Stock";
$lang['tasks_form_grabbed_products_popup_table_status_8'] = "Pre-Order";




