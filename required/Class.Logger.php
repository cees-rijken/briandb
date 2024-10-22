<?php

/**
 * Response Class
 */
header('Content-Type: application/json');
class Logger
{
    /**
     * Add an entry to the logfile that is defined in Class.Settings.php
     */
    static function _logger($message)
    {
        error_log(date("H:i:s") . " " . self::_userip() . ": " . $message, 0);
    }

    /**
     * Retrieve users IP address for logging purposes
     */
    private static function _userip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }     
}
?>