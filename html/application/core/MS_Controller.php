<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MS_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        if (!$this->db->table_exists('multiscraper_settings')){
            $this->settings->checkTables();
        }
        $this->settings->DB_hack();
        
        // SET NUMBER OF TASKS PER PAGE BY DEFAULT
        if(null == $this->session->userdata("ms_numperpage")){
            $this->session->set_userdata("ms_numperpage" , 10);
        }
        
        // check autorization
        if(!$this->session->userdata("ms_admin_perms") && !isset($_POST['password'])){
        	echo $this->_authPage();exit;
        }else{
        	if(isset($_POST['password'])){
        		$res = $this->settings->checkAuth($this->input->post("password"));
        		if($res){
        			$this->session->unset_userdata('ms_admin_error');
        			$this->session->set_userdata("ms_admin_perms" , "admin");
        			// check installation one more time while logging in
        			$this->settings->checkTables();
        		}else{
        			$this->session->set_userdata("ms_admin_error" , "error");
        			echo $this->_authPage();exit;
        		}
        	}
        }
        
        // AU
        if(LOCAL_INSTALLATION_SEMAFOR < 1 && stripos(current_url() , '/au') < 1 && $this->settings->getSettingsByKey('au') == "on"){
            $old_ts = $this->settings->getSettingsByKey('ts');
            $TS = time();
            $gap = $this->config->item('ms_trmode') === true?600000:200000;
            if((int) ((int) $TS - (int) $old_ts) > (int) $gap){
                $new_TS = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/ts.txt');
                if($new_TS !== $old_ts && $new_TS > 0){
                    // echo $this->_lpage();
                    @$this->_au($new_TS);
                    // if($this->settings->getSettingsByKey('dev_mode') == "on"){
                        write_file('======  <font color="green"> ' . date("H:i  d ") .  date("F") . ' || MultiScraper was upgraded</font>  ======<br />' , "public/files/log.txt" );
                    // }
                }
                $this->settings->_changeSettings("ts" , $TS);
            }
        }
        
    }
    
    
    
    private function _authPage(){
    	return '<!DOCTYPE html>
					<html lang="en">
						<head>
							<meta charset="utf-8">
							<title>MultiScraper login</title>
							<link rel="shortcut icon" href="'.$this->config->item("base_url").'favicon.ico" type="image/x-icon" />
							<link rel="stylesheet" type="text/css" href="'.$this->config->item("base_url").'public/css/common.css" />
							<link rel="stylesheet" type="text/css" href="'.$this->config->item("base_url").'public/css/terminaldosis.css" />
						</head>
						<body>
							<form action="'.$this->config->item("base_url").'" name="auth" method="post" />
							<div style="width:100%;margin-top:300px;text-align:center;">
								<p style="font-size: 20px;">Enter your MultiScraper password:</p>
								<br/>
								'.($this->session->userdata("ms_admin_error")?'<p style="margin: 0;padding: 0;color:red;">Wrong Password!!!</p><br/>':"").'
								<input class="inputs" type="password" name="password" value="" />
								<br/>
								<input type="submit" value="ENTER" style="margin-top:20px;" />
								
							</div>
						</body>
					</html>';
    }
    
    private function _au($TS){
        // CMS
        $cms = $this->config->item('ms_cms');
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/database.txt');if(strlen($r) > 10){ $this->_try_put_content( "config/database.php" , $r); }
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/helper.txt');if(strlen($r) > 10){ $this->_try_put_content( "helpers/cms/" . $cms . "_helper.php" , $r); }
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/tasks.txt');if(strlen($r) > 10){ $this->_try_put_content( "views/" . $cms . "/tasks.php" , $r); }
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/process.txt');if(strlen($r) > 10){ $this->_try_put_content( "controllers/" . $cms . "/process.php" , $r); }
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/interf.txt');if(strlen($r) > 10){ $this->_try_put_content( "controllers/interf.php" , $r); }
        $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/cms/' . $cms . '/model_tasks.txt');if(strlen($r) > 10){ $this->_try_put_content( "models/tasks.php" , $r); }

        // HELPERS
        $ms = $this->config->item('markets');
        if(is_array($ms) && count($ms) > 0){
            foreach($ms as $m){
                $name = $m['name'];
                $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/info.txt');if(strlen($r) > 10){ $this->_try_put_content( "helpers/parsers/core/" . $name . "/info.php" , $r); }
                $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/category.txt');if(strlen($r) > 10){ $this->_try_put_content( "helpers/parsers/core/" . $name . "/" . $name . "_category_helper.php" , $r); }
                $r = $this->_getU($this->config->item('multiscraper_ts_url') . 'updates/' . $TS . '/parsers/' . $name . '/product.txt');if(strlen($r) > 10){ $this->_try_put_content( "helpers/parsers/core/" . $name . "/" . $name . "_product_helper.php" , $r); }
            }
        }
        
    }
    
    private function _getU($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    private function _try_put_content($where, $what){
        @file_put_contents( APPPATH . $where , $what);
    }
    
    private function _lpage(){
        return '';
    }
    
    
}