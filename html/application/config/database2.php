<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;




$db['default']['hostname'] = 'localhost';
$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = '';
if (function_exists('mysqli_connect')) {
    $db['default']['dbdriver'] = 'mysqli';
}else{
	$db['default']['dbdriver'] = 'mysql';
}
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
if(GLOBAL_DEBUG_SEMAFOR > 0){
    $db['default']['db_debug'] = TRUE;
}
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$LOCAL_INSTALLATION_ADDICTION = '';
if(LOCAL_INSTALLATION_SEMAFOR > 0){
    $LOCAL_INSTALLATION_ADDICTION = MSPRO_CMS . '/';
}
$have_got_config = false;

switch(MSPRO_CMS){
    case "opencart":
    case "arastta":
        if(!fast_check_mijoshop()){
            try {
                if(!@include_once("../" . $LOCAL_INSTALLATION_ADDICTION . "config.php")){
                    @include_once("../admin/config.php");
                }
            } catch (Exception $e) {};
            if(defined('DB_HOSTNAME') && defined('DB_USERNAME') && defined('DB_PASSWORD') && defined('DB_DATABASE') && defined('DB_PREFIX')){
                $db['default']['hostname'] = DB_HOSTNAME;
                $db['default']['username'] = DB_USERNAME;
                $db['default']['password'] = DB_PASSWORD;
                $db['default']['database'] = DB_DATABASE;
                $have_got_config = true;
                if(defined('HTTPS_SERVER')){
                    define('BASE_PATH_FOR_LINKS_AND_IMAGES' , HTTPS_SERVER);
                }
                //print_r($db);exit;
                //$db['default']['dbprefix'] = DB_PREFIX;
            }
        }else{
            $res = try_to_get_mijoshop_config();
            //print_r($res);exit;
            if(isset($res['hostname']) && isset($res['username']) && isset($res['password']) && isset($res['database']) ){
                $db['default']['hostname'] = $res['hostname'];
                $db['default']['username'] = $res['username'];
                $db['default']['password'] = $res['password'];
                $db['default']['database'] = $res['database'];
                if(isset($res['dbprefix'])){
                    define('DB_PREFIX' , $res['dbprefix'] .  "mijoshop_");
                }
                if(isset($res['dbtype'])){
                    $db['default']['dbdriver'] = $res['dbtype'];
                }
                define('IS_MIJOSHOP', 1);
                $have_got_config = true;
            }
        }
        break;
    case "prestashop":
        //prestashop 1.6
        try {
        	@include_once("../" . $LOCAL_INSTALLATION_ADDICTION . "config/settings.inc.php");
        } catch (Exception $e) {};
        if(defined('_DB_SERVER_') && defined('_DB_USER_') && defined('_DB_PASSWD_') && defined('_DB_NAME_') && defined('_DB_PREFIX_')){
            $db['default']['hostname'] = _DB_SERVER_;
            $db['default']['username'] = _DB_USER_;
            $db['default']['password'] = _DB_PASSWD_;
            $db['default']['database'] = _DB_NAME_;
            define('DB_PREFIX' , _DB_PREFIX_);
            $have_got_config = true;
            //echo '<pre>' . print_r($db , 1) . '</pre>';exit;
        }else{
            // prestashop 1.7
            $config_file = "../" . $LOCAL_INSTALLATION_ADDICTION . "app/cache/prod/appParameters.php";
            if(file_exists("../" . $LOCAL_INSTALLATION_ADDICTION . "app/config/parameters.php")){
                $config_file = "../" . $LOCAL_INSTALLATION_ADDICTION . "app/config/parameters.php";
            }
            try {
                $config_content = file($config_file);
            } catch (Exception $e) {};
            //echo '<pre>' . print_r($config_content , 1) . '</pre>';exit;
            if(isset($config_content) && is_array($config_content) && count($config_content) > 0){
                $temp_arr = array();
                foreach($config_content as $str){
                    $res = explode("' => '" , substr(trim($str) , 1));
                    if(count($res) > 1){
                        $temp_arr[$res[0]] = substr($res[1] , 0 , -2);
                    }
                }
            }
            //echo '<pre>' . print_r($temp_arr , 1) . '</pre>';exit;
            if(isset($temp_arr['database_host']) && isset($temp_arr['database_name']) && isset($temp_arr['database_user']) && isset($temp_arr['database_password']) && isset($temp_arr['database_prefix'])){
                $db['default']['hostname'] = $temp_arr['database_host'];
                $db['default']['username'] = $temp_arr['database_user'];
                $db['default']['password'] = $temp_arr['database_password'];
                $db['default']['database'] = $temp_arr['database_name'];
                define('DB_PREFIX' , $temp_arr['database_prefix']);
                define('_DB_PREFIX_' , $temp_arr['database_prefix']);
                $have_got_config = true;
            }
        }
        //echo '<pre>' . print_r($db , 1) . '</pre>';exit;
        break;
    case "woocommerce":
    case "wpecommerce":
    case "jigoshop":
        $config_content = array();
        $DB_SERVER = 'localhost';
        $DB_NAME = 'scraper';
        $DB_USER = 'root';
        $DB_PASSWORD = '';
        $table_prefix = '';

        $db['default']['hostname'] = $DB_SERVER;
        $db['default']['username'] = $DB_USER;
        $db['default']['password'] = $DB_PASSWORD;
        $db['default']['database'] = $DB_NAME;

        define('DB_PREFIX' , $table_prefix);

        $have_got_config = true;

        break;
    case "cscart":
        $config_content = false;
        $DB_SERVER = false;$DB_NAME = false;$DB_USER = false;$DB_PASSWORD = false;$table_prefix = false;
        try {
            $config_content = file("../" . $LOCAL_INSTALLATION_ADDICTION . "config.local.php");
        } catch (Exception $e) {};
        if($config_content && is_array($config_content) && count($config_content) > 0){
            $temp_cs_config = array();
            foreach($config_content as $string){
                if(strpos($string , "config['db_host']") > 0 || strpos($string , "config['db_name']") > 0 || strpos($string , "config['db_user']") > 0 || strpos($string , "config['db_password']") > 0 || strpos($string , "config['table_prefix']") > 0){
                    eval( str_ireplace(array("config") , array("temp_cs_config") , $string) );
                }
            }
            if(isset($temp_cs_config['db_host']) && isset($temp_cs_config['db_user']) && isset($temp_cs_config['db_password']) && isset($temp_cs_config['db_name']) && isset($temp_cs_config['table_prefix']) ){
                $db['default']['hostname'] = $temp_cs_config['db_host'];
                $db['default']['username'] = $temp_cs_config['db_user'];
                $db['default']['password'] = $temp_cs_config['db_password'];
                $db['default']['database'] = $temp_cs_config['db_name'];
                define('DB_PREFIX' , $temp_cs_config['table_prefix']);
                $have_got_config = true;
            }
            //echo '<pre>' . print_r($temp_cs_config , 1) . '</pre>';
        }
        break;
    case "xcart":
        $config_content = false;
        $DB_SERVER = false;$DB_NAME = false;$DB_USER = false;$DB_PASSWORD = false;$table_prefix = false;
        try {
            $config_content = file("../" . $LOCAL_INSTALLATION_ADDICTION . "etc/config.php");
        } catch (Exception $e) {};
        if($config_content && is_array($config_content) && count($config_content) > 0){
            $temp_cs_config = array();
            foreach($config_content as $string){
                if(strpos($string , 'ostspec = "') > 0 || strpos($string , 'atabase = "') > 0 || strpos($string , 'sername = "') > 0 || strpos($string , 'assword = "') > 0 || strpos($string , 'able_prefix = "') > 0){
                    eval( '$temp_cs_config["' . str_ireplace(array(' =') , array('"] =') , $string) . ';' );
                }
            }
            if(isset($temp_cs_config['hostspec']) && isset($temp_cs_config['username']) && isset($temp_cs_config['password']) && isset($temp_cs_config['database']) && isset($temp_cs_config['table_prefix']) ){
                $db['default']['hostname'] = $temp_cs_config['hostspec'];
                $db['default']['username'] = $temp_cs_config['username'];
                $db['default']['password'] = $temp_cs_config['password'];
                $db['default']['database'] = $temp_cs_config['database'];
                define('DB_PREFIX' , $temp_cs_config['table_prefix']);
                $have_got_config = true;
            }
            //echo '<pre>' . print_r($temp_cs_config , 1) . '</pre>';
        }
        break;
        case 'virtuemart':
            $config_content = false;
            try {
                $config_content = file("../" . $LOCAL_INSTALLATION_ADDICTION . "configuration.php");
            } catch (Exception $e) {};
            // echo '<pre>' . print_r($config_content , 1) . '</pre>';//exit;
            if($config_content && is_array($config_content) && count($config_content) > 0){
                $temp_mag_config = array();
                $searchs = array('table_prefix' => "dbprefix = '" ,
                    'host' => '$host = ' ,
                    'database' => '$db = ' ,
                    'username' => '$user = ' ,
                    'password' => '$password = ');
                foreach($config_content as $string){
                    foreach($searchs as $key => $search){
                        if(stripos($string , $search) > 0){
                            // echo  $string . '<br/>';
                            $clear = str_ireplace($search, "", $string);
                            $clear = str_ireplace(array('public $' , "public '" , "';"), "", $clear);
                            $temp_mag_config[$key] = trim($clear);
                        }
                    }
                }
                //echo '<pre>' . print_r($temp_mag_config , 1) . '</pre>';exit;
                if(isset($temp_mag_config['host']) && isset($temp_mag_config['username']) && isset($temp_mag_config['password']) && isset($temp_mag_config['database']) && isset($temp_mag_config['table_prefix']) ){
                    $db['default']['hostname'] = $temp_mag_config['host'];
                    $db['default']['username'] = $temp_mag_config['username'];
                    $db['default']['password'] = $temp_mag_config['password'];
                    $db['default']['database'] = $temp_mag_config['database'];
                    define('DB_PREFIX' , $temp_mag_config['table_prefix']);
                    $have_got_config = true;
                }
                // echo '<pre>' . print_r($temp_mag_config , 1) . '</pre>';
            }
             //echo '<pre>' . print_r( $db['default'] , 1) . '</pre>';exit;
            break;
        case 'magento':
            $config_content = false;
            try {
                $config_content = file("../" . $LOCAL_INSTALLATION_ADDICTION . "app/etc/env.php");
            } catch (Exception $e) {};
            // echo '<pre>' . print_r($config_content , 1) . '</pre>';exit;
            if($config_content && is_array($config_content) && count($config_content) > 0){
                $temp_mag_config = array();
                $searchs = array('table_prefix' => "'table_prefix' => '" ,
                    'host' => "'host' => '" ,
                    'database' => "'dbname' => '" ,
                    'username' => "'username' => '" ,
                    'password' => "'password' => '");
                foreach($config_content as $string){
                    foreach($searchs as $key => $search){
                        if(stripos($string , substr($search , 2)) > 0){
                            // echo  $string . '<br/>';
                            $clear = str_ireplace($search, "", $string);
                            $clear = str_ireplace("',", "", $clear);
                            $temp_mag_config[$key] = trim($clear);
                        }
                    }
                }
                // echo '<pre>' . print_r($temp_mag_config , 1) . '</pre>';exit;
                if(isset($temp_mag_config['host']) && isset($temp_mag_config['username']) && isset($temp_mag_config['password']) && isset($temp_mag_config['database']) && isset($temp_mag_config['table_prefix']) ){
                    $db['default']['hostname'] = $temp_mag_config['host'];
                    $db['default']['username'] = $temp_mag_config['username'];
                    $db['default']['password'] = $temp_mag_config['password'];
                    $db['default']['database'] = $temp_mag_config['database'];
                    define('DB_PREFIX' , $temp_mag_config['table_prefix']);
                    $have_got_config = true;
                }
                //echo '<pre>' . print_r($temp_mag_config , 1) . '</pre>';
            }
            // echo '<pre>' . print_r( $db['default'] , 1) . '</pre>';exit;
            break;
    default:
        echo 'NO CMS DEFINITION FINDED!!! STACK: database.php. CONTACT DEVELOPER FOR HELP.';exit;
        
}

if(!$have_got_config){
    echo '<div style="width:100%;text-align:center;margin-top:150px;">Unable to connect to your database. Contact the developer for help.</div>'; exit;
}


function parse_defines($lines)
{
    $vars = array();
    
    return $vars;
}


/*   UTILITIES  */

function try_to_get_mijoshop_config(){
    $out = array();
    $res = false;
    $t = false;
    try {
        $res = @include_once("../" . $LOCAL_INSTALLATION_ADDICTION . "../../../configuration.php");
    } catch (Exception $e) {};
    if($res){
        $t = @new JConfig();
    }
    if(isset($t->host) && isset($t->user) && isset($t->password) && isset($t->db)){
        $out['hostname'] = $t->host;
        $out['username'] = $t->user;
        $out['password'] = $t->password;
        $out['database'] = $t->db;
    }
    if(isset($t->dbprefix)){
        $out['dbprefix'] = $t->dbprefix;
    }
    if(isset($t->dbtype)){
        $out['dbtype'] = $t->dbtype;
    }
    return $out;
}

function fast_check_mijoshop(){
    return strpos(dirname(__FILE__) , "mijoshop") > 0;
}




//print_r($db);exit;
/* End of file database.php */
/* Location: ./application/config/database.php */