<?php
/**
 * Settings Class
 * PLEASE do not change anything in this file or it will break functionality!!
 */
set_time_limit(0);
class Settings
{
    /**
     * Collecting both fixed as well as user settings (from CONFIG.php) in an array
     */
    public static $params = array();
    public static function init()
    {
        /**
         * Fixed settings
         */
        self::$params['api_version'] = 'v1';

        /**
         * User settings from /CONFIG.php
         */
        if (file_exists(dirname(__FILE__) . "/../CONFIG.php")) 
        {
            require_once(dirname(__FILE__) . "/../CONFIG.php");
            foreach ($a as $k => $v) 
            {//has to be done through an array, because you cannot declare static variables in a foreach loop
                self::$params[$k] = $v;
            }
        } 
        else 
        {
            Logger::_logger("Config is missing");
            echo '{"code":"999","message":"Config is missing"}';
            exit();
        }        
    }

    /**
     * Setting getter function to get a specific setting
     */
    public static function _get($key)
    {
        return self::$params[$key];
    }
}
/**
 * Trigger the init-function to set the array-params...
 */
Settings::init();

/**
 * PHP Settings
 */
error_reporting(E_ALL);
ini_set("display_errors", Settings::_get('env_showerrors'));
ini_set("log_errors", 1);
ini_set('error_log', Settings::_get('folder_logs') . date("d-m-Y") . ".log");

setlocale(LC_ALL, Settings::_get('env_locale'));
?>