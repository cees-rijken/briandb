<?php
/**
 * Don't call this file directly...
 */
if($_SERVER['REQUEST_URI']=='/CONFIG.php')
{
    die('Brian says no...');
}
/**
 * Set your locale
 */
$a['env_locale'] = 'nl_NL.UTF-8';
/**
 * Show errors on screen or not?
 */
$a['env_showerrors'] = 1;

/**
 * Database settings
 */
$a['db_host'] = 'localhost';
$a['db_name'] = 'xxxxxxxxxxxxxx';
$a['db_user'] = 'xxxxxxxxxxxxxxxxxxx';
$a['db_password'] = 'xxxxxxxxxxxxxxx';
$a['db_encoding'] = 'utf8';

$a['folder_logs'] = $_SERVER['DOCUMENT_ROOT'] . '/logs/';
$a['folder_files'] = $_SERVER['DOCUMENT_ROOT'] . '/media/';

/**
 * JWT/Token settings
 */
$a['secret_jwt'] = 'xxxxxxxxxxxxxxxx';