<script type="text/javascript">
Glob = {};
Glob.manual_launch = 0;
var ConsoleMode = false;

function manual(){
	if(Glob.manual_launch < 1){
		Glob.manual_launch = 1;
		toogle_button();
		$.post('process' , {} ,
				function(data){
				Glob.manual_launch = 0;
				toogle_button();
					if(ConsoleMode){
						console.log(data);
					}
					if(data.fail !== undefined){
						switch(data.fail){
							case "switched_off":
								alertify.alert('Your MultiScraper is switched OFF. Please, switch on it at the "Settings" section.');
								break;
							case "no_tasks":
								alertify.alert('You have no active tasks for MultiScraper.');
								break;
							case "demo":
								alertify.alert('Sorry, you are unable to do this in the DEMO mode.');
								break;
							case "trial":
								alertify.alert('You use the TRIAL version and have already grabbed several products. Purchase the full version to continue...');
								break;
							default:
								break;
						}
					}else{
						location.href = "log";
					}
				}
		);
	}
}


function toogle_button(){
	var loader = $("#loader").css("display");
	if(loader == "none"){
		$("#loader").css("display" , "inline-block");
		$("#manual_button").css("display" , "none");
	}else{
		$("#loader").css("display" , "none");
		$("#manual_button").css("display" , "inline-block");
	}
}
</script>



            	
            	
<div class="blocks">
    <h1>Manual Launch</h1>
    <div class="text-block" style="min-height: 450px;">
        
        
        
        <div id="tasks-form" class="ms_form">
        	<div id="form-content" style="font-size: 1.4em;">
        		<b>MultiScraper</b> created to be used in the automatical mode: you add the tasks for scraping and it grabs product one by one while you relax.
        		<br />
        		You may find how to get it to work in automatic mode at the "How to" page in the "Configuration" section.
        		<br />
        		<br />
        		<br />
        		But here you can startup your <b>MultiScraper</b> manually.
        		<br />
        		It is very useful for demonstration or testing purposes.
        		<br />
        		<br />
        		Each time you press the "Launch" button you will launch 1 iteration of <b>MultiScraper</b>.
        		<br />
        		<br />
        		After this you will be redirected to the "Log" section where you will be able to view the results of processing.
        		<br />
        		<br />
        		<input type="button" id="manual_button" value="Launch" class="btn btn-blue actions_button" style="width: 80px;" onclick="manual();" />
        		<div id="loader" style="display:none;">
        			<img src="<?php echo $this->config->item("base_url"); ?>public/images/loader.gif" ><br />
        			<span style="color:#DE1D73;font-size: 18px;">
        				Grabbing is not the easy process. So this may take some time.
        			</span>
        		</div>
        	</div>
        </div>
    </div>
</div>