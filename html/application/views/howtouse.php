<script type="text/javascript">
jQuery(document).ready(function($) {
    $( function() {
        $( "#tabs" ).tabs();
     });
});
</script>
<div id="howtouse" class="blocks">
    <h1>How to use</h1>
    <div class="text-block">
        <div id="howtouse-form" class="ms_form">
        
        
        
        <div id="tabs">
              <ul>
                <li><a href="#tabs-overview"><h2 class="tabs">Overview</h2></a></li>
                <li><a href="#tabs-configuration"><h2 class="tabs">Configuration</h2></a></li>
                <!-- <li><a href="#tabs-usage"><h2 class="tabs">Usage</h2></a></li>  -->
                <li><a href="#tabs-faq"><h2 class="tabs">FAQ</h2></a></li>
                <li><a href="#tabs-proxy"><h2 class="tabs">Proxy support</h2></a></li>
                <li><a href="#tabs-safety"><h2 class="tabs">Safety</h2></a></li>
              </ul>
              
              
              <!-- OVERVIEW -->
              <div id="tabs-overview">
                        <div class="howtouse_form_content" >
                    		<b>MultiScraper</b> is the stand-alone application that will grab products from other big online marketplaces (amazon, aliexpress, etsy, focalprice, or any others that are configured)
                                and insert them directly into your <?php echo  MSPRO_CMS_DISPLAY_NAME ?> store.<br /><br />
                    		<b>MultiScraper</b> able to grab separate products lists as well as the whole categories or search results listings, even if the result lists are paginated.<br /><br />
                    		<b>MultiScraper</b> able to make different modifications with the grabed data on-the-fly (for example, add relative or fixed margin to product price).<br />                    
                    	    <h3>Your <b>MultiScraper</b> now able to grab the next marketplaces:</h3><br /><br /><br />
                    		<span style="font-size: 20px;color: #597D84;margin-left: 25px;">Core:</span><br />
                    		<?php 
                    		if(isset($markets['core']) && is_array($markets['core']) && count($markets['core']) > 0){
                    			foreach($markets['core'] as $market){
                    				echo '  -  ' . $market['title'] . '<br />';
                    			}
                    		}
                    		
                    		if($this->config->item('ms_trmode') === true){
                    		    switch(MSPRO_CMS){
                    		        case "opencart":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=17857" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features.</p>';
                    		            break;
                    		        case "xcart":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed.</p>';
                    		            break;
                    		        case "prestashop":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/4581/multiscraper-for-prestashop?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        case "woocommerce":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/6107/multiscraper-for-woocommerce?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        case "cscart":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/6107/multiscraper-for-woocommerce?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        case "magento":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/6107/multiscraper-for-woocommerce?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        case "virtuemart":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/6107/multiscraper-for-woocommerce?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        case "zencart":
                    		            echo '<br /><p style="font-weight:bold;"><span style="color:red;">WARNING</span>: You use the TRIAL version of MultiScraper. It able to grab Banggood.com website only and is limited to 30 products grabbed. Purcahse the <a href="https://www.codester.com/items/6107/multiscraper-for-woocommerce?ref=multiscraper" target="_self" style="text-decoration:underline;color:#4856EA;">commercial license</a> to get all features</p>';
                    		            break;
                    		        default:
                    		            break;
                    		    }
                    		}
                    	   
                    		if(isset($markets['additional']) && is_array($markets['additional']) && count($markets['additional']) > 0){
                    		?>
                    		<span style="font-size: 24px;color: #597D84;margin-left: 25px;">Additional:</span><br />
                    		<?php 
                    			foreach($markets['additional'] as $market){
                    				echo  '  -  ' . $market['title'] . '<br />';
                    			}
                    		?>
                    		<?php 
                    		}
                    		?>
                    	</div>
              </div>
              
              
              
              <!-- CONFIGURATION -->
              <div id="tabs-configuration">
                        <div class="howtouse_form_content" >
                    		<b>MultiScraper</b> created to be used in the automatical mode: you add the tasks for scraping and MultiScraper grabs products one by one while you relax.<br /><br />
                    		It also has the ability to be launched manually (see <a href="<?php echo $this->config->item("base_url"); ?>manual" target="_self" style="text-decoration:underline;color:#4856EA;">Manual Launch</a> page) but this feature is just for testing or demonstration.<br /><br />
                    		
                    		To let MultiScraper grabbing products automatically you must add 1 task into your server's CRON.<br />
                    		CRON is a server utility designed to run scripts on a schedule (once in hour, every 10 minute, every minute, etc).<br /><br />
                    		
                    		The task for CRON is opening the particular url (just like you do it using your web browser): <b>"http://YOURSTORE.COM/multiscraper/process/"</b><br />
                    		as a rule this is command like this:<br />
                    		<b><span style="color:#2D75FA;">wget -O - http://YOURSTORE.COM/multiscraper/process/ >/dev/null 2>&1</span></b><br /><br />
                    		
                    		Recommended startup interval for MultiScraper is <b>EVERY 2 MINUTES</b>.<br /><br />
                    		
                    		Try to configure it by yourself and if will have no success just ask the server administrator help you to create this task.<br />
                    		This is very frequent request to the hosting support and as a usual it is resolved very quickly.<br /><br />
                    		
                    		Also, at the "Settings" page you will find <b>"Items grabbed each iteration"</b> parameter.<br />
                    		This is the quantity of products that will be grabbed in one iteration (during 1 launch of MultiScraper).<br />
                    		Using these numbers you may define your MultiScraper's data capacity.<br /><br />
                    		
                    		For example, you configured your MultiScraper to launch every <b>2</b> minutes and defined "Products grabbed in each iteration" as 2 products.<br />
                    		So, your MultiScraper's data capacity is: <b>( 60/2 minutes  * 2 products) * 24hours  =  1440 products</b> grabbed daily.<br />
                    		Using these two parameters (MultiScraper startup interval and "Products grabbed in each iteration") you may adjust your MultiScraper's power, make the scraping more or less intensive.
                	   </div>
              </div>
              
              
              
              <!-- USAGE -->
              <!-- <div id="tabs-usage">
                        <div class="howtouse_form_content" >
                        USAGE goes here
                        </div>
              </div> -->
              
              
              
              <!-- FAQ -->
               <div id="tabs-faq">
                       <div class="howtouse_form_content" >
                       		<h3>Why <b>MultiScraper</b> has to be upgraded so often?</h3><br /><br /><br />
                           		First of all, <b>MultiScraper</b> is HTML grabber, it gets the product page's source code and then parsing it to distinguish particular data (title, description, price, images URLs etc).<br /><br />
        						Big websites CHANGE it's products pages layouts quite often so we have to change the parsing code accordingly. Promise, we are happy of this fact not more than you.<br /><br />
    
                        		Also, <b>MultiScraper</b> is constantly evolving so we recommend you to keep it up-to-dated.<br />
                        		The optimal frequency of updating is 1 month (and any date when it stopped grabbing products properly).<br /><br />
                        		
                        		We don't think this will be very inconvenient for you, the upgrade is as simple as possible: just upload new files over the old ones and continue using <b>MultiScraper</b>.<br />
                        	<h3>What Hosting provider is the most suitable for <b>MultiScraper</b> if i want to grab thousands of products?</h3><br /><br /><br />
							    This is one of the most complicated and frequently asked question.<br />
							    The similar one is "What is the sufficient server's capacity for using <b>MultiScraper</b>?".<br /><br />

							    First of all, grabbing different websites requires different throughput of the recepient's server. <br />
							    Some are very easy (friendly for scraping), you may grab thousands of products daily from them even on a weak cheap hosting.<br />
							    Another ones vice versa: extremely hard, the examples are Alibaba.com, Taobao.com, Wholesale7.net and some others. <br />
							    There are many hi-res images, too heavy product pages source code and also a good protection from intensive requesting. <br />
							    You have to have really good and stable server if you want to get products from them. <br /><br />

							    Another factor is the question how much products you are going to grab from particular websites.<br />
							    In other words, how intensively you are going to use <b>MultiScraper</b>.<br /><br />

							    As for the Web Hosting we can only advice the services which have the minimum number of complaints from our customers.<br />
							    These are the most famous Hostings companies like <b><a href="http://partners.hostgator.com/c/290417/177309/3094" target="_blank" style="text-decoration:underline;color:#4856EA;">HostGator.com</a></b>, <b><a href="http://www.a2hosting.com?aid=multiscraper" target="_blank" style="text-decoration:underline;color:#4856EA;">a2hosting.com</a></b>, <b><a href="http://liquidweb.evyy.net/c/290417/278394/4464" target="_blank" style="text-decoration:underline;color:#4856EA;">LiquidWeb.com</a></b> or <b><a href="http://track.ehost.com/5845a10ad8064/click" target="_blank" style="text-decoration:underline;color:#4856EA;">eHost.com</a></b>.<br />
							    Anyway the final decision about the choice of the hosting provider you are taking it.<br />
							<h3>May i see the example of already published website with products grabbed by <b>MultiScraper</b>?</h3><br /><br /><br />
							    Sorry, this is out of <a href="https://multiscraper.com/legal#privacy" target="_blank" style="text-decoration:underline;color:#4856EA;">Privacy Policy</a><br />
							<h3>If the images at the donator website are watermarked, will they be grabbed watermarked also?</h3><br /><br /><br />
							    We always try to find the way how to get non-watermaked images from the source and in most cases we have success.<br /><br />
							    
							    Unfortunately there in no way to remove watermarkes from images automatically, so for some marketplaces the images will be grabbed watermarked.<br />
								The examples are Geekbuying.com, particular products from Buyincoins.com, Dx.com, Tinydeal.com and some others.<br />
							<h3>In some marketplaces the product's variations are interdependent, will <b>MultiScraper</b> grabs the valid combinations of variations (sizes, colors etc)</h3><br /><br /><br />
							    Unfortunately no, <b>MultiScraper</b> will grab all variations that are enabled, but not taking into account the interdependence between them. :(<br />
							<h3>What action the <b>"Reinstall MultiScraper"</b> button does?</h3><br /><br /><br />
							    It will delete all settings and tasks of current installation of  <b>MultiScraper</b> and will install it from the beginning.<br />
								Note, that already grabbed products will not be deleted (because they already in your database) but they will not be updated by  <b>MultiScraper</b> anymore.<br />
							<h3>What action the <b>"Restart task"</b> button does?</h3><br /><br /><br />
							    This action will launch the grabbing for this particular task from the beginning.<br />
								Already grabbed products will not be deleted but they will be marked as "non-grabbed yet".<br />
								The grabbing of LISTINGS will be launched from the beginning (from the first page of listing).<br />
							    

                       </div>
              </div>
              
              
              
              <!-- PROXY SUPPORT -->
              <div id="tabs-proxy">
                       <div class="howtouse_form_content" >
                       		Many users of <b>MultiScraper</b> faced the huge problem grabbing products from Aliexpress, Alibaba and some other marketplaces.<br />
                            Sometimes it occurs right after the MultiScraper's installation, sometimes some later.<br />
                            Almost always such problem occurs while intensive grabbing: remote website just blocks the domain/IP when see too many requests from it - remote website thinks that this is some kind of DDoS attack and redirects all requests from this IP to the authorisation page or just returns the blank page.<br />
                            Trying to resolve this problem for our customers we added the PROXY support feature to the <b>MultiScraper</b>.<br />
                            It works as follows: if attempt to get product's page by the usual methods is failed, the page will be requested through an elite or an anonymous proxy server.<br />
                            <br />
                            The situation is complicated by the fact that we have only limited number of the proxy, but already have an impressive number of users of <b>MultiScraper</b>, so this is not the stable solution.<br />
                            To resolve this, we added the ability to use YOUR OWN PROXY and do not depend on ours.<br />
                            You may create them by yourself or use special services where you can buy or rent a list of proxies of different levels of anonymity (about PROXY types you can read <a href="http://forumaboutproxy.com/general-discussions/what-is-an-anonymous-proxy-server-proxy-types-transparent-anonymous-or-elite/" target="_blank" style="text-decoration:underline;color:#4856EA;cursor: help;"><b>here</b></a>).<br />
                            Such services are easy to find at the request of "Rent elite proxy" or "Rent anonymous proxy".<br />
                            The examples of such services are <b><a href="https://infatica.io/aff.php?aff=27" target="_blank" style="text-decoration:underline;color:#4856EA;">Infatica.io</a></b>, <b><a href="https://secure.avangate.com/affiliate.php?ACCOUNT=MYPROXY&AFFILIATE=86636&PATH=http%3A%2F%2Fwww.didsoft.com" target="_blank" style="text-decoration:underline;color:#4856EA;">My-proxy.com</a></b> or <b><a href="http://coolproxies.com/aff.php?aff=4" target="_blank" style="text-decoration:underline;color:#4856EA;">CoolProxies.com</a></b> etc. There are many similar services and unfortunately we cannot test them all or give you any guarantee of any of them.<br />
                                You may use them at your own risk.<br />
                            <br />
                            How to install your OWN proxies list: in the root of your MultiScraper's folder you will find the <b>proxyMyOwn.txt</b> file. Open it, paste your proxy and save.<br />
                            You may paste the list of proxies (each one from new line), it should look <a onclick="$('#bpopup_proxy_1').bPopup();" style="text-decoration:underline;color:#4856EA;cursor: help;"><b>[like this]</b></a><br />
                            If this file is not empty, MultiScraper will use the proxies from it. If empty - it will try to use our proxies list.<br />
                            <br />
                            <b><span style="color:red;">Warning:</span></b> using Proxy you load the MultiScraper (and so your server) more then usual, so it is NOT recomended to make intensive grabbing from such "hard" marketplaces as Aliexpress, Alibaba, Taobao, Amazon and so on.<br />
                       </div>
              </div>
              
              
              
              <!-- SAFETY -->
              <div id="tabs-safety">
                       <div class="howtouse_form_content" >
                    		<h3>Change password for your <b>MultiScraper</b></h3><br /><br /><br />
                    		Initial password is <b>"password"</b> and it is recommended to change it in the Settings section.<br />
                    		<h3>Rename your <b>MultiScraper</b> location folder</h3><br /><br /><br />
                    		Original location is "http://yourstore.com/multiscraper/". It is recommended to change it to your own location name.<br />
                    		<span class="terms_nums">1</span> choose appropriate location name (for example: {your_secret_word}_scraper )<br />
                    		<span class="terms_nums">2</span> open file multiscraper/.htaccess <br />
                    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    		find: <b>multiscraper</b><br />
                    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    		replace with: {YOUR_CHOOSEN_LOCATION_NAME}<br />
                    		<span class="terms_nums">3</span> rename "multiscraper" folder into "{YOUR_CHOOSEN_LOCATION_NAME}" folder<br />
                    		Then try the next url in your browser: "http://{yourshop.com}/{YOUR_CHOOSEN_LOCATION_NAME}/". You will get the same interface, but more safely.<br /><br />
                    		Do not forget to edit your CRONJOBS scripts location (see "Configuration" section).
                    	</div>
              </div>
        </div>
        	
    	
        </div>
    </div>
</div>


<div id="bpopup_proxy_1" class="bpopups">
    	<span class="bpopup_button b-close"><span>X</span></span> 	
    	<img src="<?php echo $this->config->item("base_url"); ?>public/images/proxy.png" style="width:1100px;" />
</div>
