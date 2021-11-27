<script type="text/javascript">
jQuery(document).ready(function($) { 
	
	// char count
	$("#ch_pass_button").on("click" , function(){
		var dis = $("#change_pass-form_div").css("display");
		if(dis == "none"){
			$("#change_pass-form_div").slideDown(500);
		}else{
			$("#change_pass-form_div").slideUp(500);
		}
	});

	// BIND REINSTALL BUTTON
	$("#reinstall_button_a").on( 'click', function () {
		//reset();
		var demo = "<?php  echo $demo; ?>";
		if(demo !== 'yes'){
    		alertify.set({ buttonReverse: true });
    		alertify.set({ labels: { ok: "Reinstall", cancel: "Cancel" } });
    		alertify.confirm("All your tasks will be lost. All your settings will be lost. Continue?\r\n", function (e) {
    			if (e) {
    				alertify.success('"Reinstall" action was initiated');
    				location.href = "reinstall"; 
    			} else {
    				alertify.error('You\'ve cancelled the "Reinstall" action');
    				return false;
    			}
    		});
		}else{
			alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
		}
		return false;
	});
	
});
</script>

<?php if(isset($notifications['modified'])){ ?>
<div id="success-content" class="success-notification">Settings has been modified.</div>
<script>jQuery(document).ready(function($) { alertify.success('Settings has been modified.'); });</script>
<?php } ?>
<?php if(isset($notifications['nopass'])){ ?>
<div id="nopass-content" class="failure-notification">Wrong current password.</div>
<script>jQuery(document).ready(function($) { alertify.error('Wrong current password.'); });</script>
<?php } ?>
<?php if(isset($notifications['shortpass'])){ ?>
<div id="shortpass-content" class="failure-notification">New password is too short, choose another one.</div>
<script>jQuery(document).ready(function($) { alertify.error('New password is too short, choose another one.'); });</script>
<?php } ?>
<?php if(isset($notifications['nopassmatch'])){ ?>
<div id="nopassmatch-content" class="failure-notification">Passwords do not match!</div>
<script>jQuery(document).ready(function($) { alertify.error('Passwords do not match!'); });</script>
<?php } ?>
<?php if(isset($notifications['passchanged'])){ ?>
<div id="nopassmatch-content" class="success-notification">Password changed!</div>
<script>jQuery(document).ready(function($) { alertify.success('Password changed!'); });</script>
<?php } ?>  
            	
<div id="settings" class="blocks">
    <h1>Configure your MultiScraper</h1>
    <div class="text-block">
        
        
        
        <div id="settings-form" class="ms_form">
        	<div id="form-content">
        				
            			<h2>Your MultiScraper Settings</h2>
            			<form id="g_form" method="post" action="<?php echo $this->config->item('base_url'); ?>" >
            				
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" >State:</label>
            					</div>
            					<input type="radio" id="radio_state_on" name="state" value="on" style="width:auto;" <?php echo ($settings['state'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_state_on">
            						ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_state_off" name="state" value="off" style="width:auto;" <?php echo ($settings['state'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_state_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" >Dev mode <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$('#bpopup_ai_2').bPopup();" /> :</label>
            					</div>
            					<input type="radio" id="radio_dev_mode_on" name="dev_mode" value="on" style="width:auto;" <?php echo ($settings['dev_mode'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_dev_mode_on">
            						ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_dev_mode_off" name="dev_mode" value="off" style="width:auto;" <?php echo ($settings['dev_mode'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_dev_mode_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				
            			
            				<div class="form-element-wrappers" <?php if($this->config->item("ms_trmode") === true){ echo 'style="opacity:0.3"';} ?>>
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" >Auto Upgrade <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$('#bpopup_ai_4').bPopup();" /> :</label>
            					</div>
            					<input type="radio" id="radio_au_on" name="au" <?php if($this->config->item("ms_trmode") === true){ echo 'disabled';} ?> value="on" style="width:auto;" <?php echo ($settings['au'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_au_on">
            						 ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_au_off" <?php if($this->config->item("ms_trmode") === true){ echo 'disabled';} ?> name="au" value="off" style="width:auto;" <?php echo ($settings['au'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_au_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="num_product" class="labels" >Items grabbed each iteration <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$('#bpopup_ai_3').bPopup();" /> :</label>
            					</div>
            					<input  type="number" class="inputs" name="num_product" id="num_product" value="<?php echo $settings['num_product']; ?>"  maxlength="2" style="width: 40px;" />
            					<div class="clear"></div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<input type="button" value=" + Change password" class="btn btn-blue" id="ch_pass_button" />
            					<br />
            					<div id="change_pass-form_div" class="disp_none">
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="old_pass" class="labels" >Current password:</label>
			            					</div>
			            					<input type="text" class="inputs" name="old_pass" id="old_pass" value=""  maxlength="32" />
            							</div>
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="new_pass" class="labels" >New password:</label>
			            					</div>
			            					<input type="text" class="inputs" name="new_pass" id="new_pass" value=""  maxlength="32" />
			            					<div class="clear"></div>
            							</div>
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="confirm_pass" class="labels" >Confirm password:</label>
			            					</div>
			            					<input type="text" class="inputs" name="confirm_pass" id="confirm_pass" value=""  maxlength="32" />
			            					<div class="clear"></div>
            							</div>
            					</div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<a href="#" id="reinstall_button_a" >
            						<input type="button" value="Reinstall MultiScraper" class="btn btn-red" id="reinstall_button" />
            					</a>
            				</div>
            				
            				<div  style="margin:15px;text-align:center;" >
            						<input type="submit" value="Save" class="btn btn-green" id="c_send" />
            				</div>
            				
            				
            			</form>
            	</div>
            </div>
    </div>
</div>

<!--   "INVISIBLE MODE" TIP  //-->
 <div id="bpopup_ai_1" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<b>Invisible mode</b> allows to grab products anonymously, it uses the number of proxy servers and makes requests to the donor sites through them.<br /><br />
    	This may be useful if you are grabbing stores that use protection from content grabbing or have switched on the protection from DDos attacks.<br />
    	the examples are <b>Miniinthebox.com</b> and <b>Lightinthebox.com</b>.<br /><br /><br />
    	<b>There are several warnings about it:</b><br />
    	 - DO NOT use this mode without necessity<br />
    	 - DO NOT use on weak servers or servers with limited abilities<br />
    	 - DO NOT use if you do not understand what is it<br />
    	 - Note that there may be some losses in grabbed products while using this feature<br />
    	 - set the <b>MINIMUM</b> "Products grabbed in each iteration" when using this mode<br />
</div>
<!--   "DEV MODE" TIP  //-->
 <div id="bpopup_ai_2" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<b>Dev mode</b> may be needed if the MultiScraper's developer will have to debug this application on your side<br /><br />
    	<b>There are several warnings about it:</b><br />
    	 - DO NOT use this mode without necessity<br />
    	 - DO NOT use if you do not understand what is it
</div>
<!--   "PRODUCTS GRABBED EACH ITERATION" TIP  //-->
 <div id="bpopup_ai_3" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	As described at the "How to use" page ("Configuration" section): MultiScraper does it's job iteratively.<br /><br />
    	This is the number of products that MultiScraper will grab each time when launched.<br /><br />
    	NOTE, that we established the foolproof and you unable to set this number more then 10.<br />
    	This is quite enough for grabbing lots of products and do not load your server too much. <br />
</div>
<!--   "Auto Update" TIP  //-->
 <div id="bpopup_ai_4" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	MultiScraper able to upgrade itself, it will upload the updates (if available) from our server and rewrite its own core files.<br /><br />
    	Using this setting you may switch OFF the auto-upgrade system.<br /><br />
    	This may be useful if you are going to change the core files of MultiScraper so it will not erase the changes made.<br />
</div>

