<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("X-Robots-Tag: noindex, nofollow", true);
/**
 * Include all the necessary files. Please DO NOT change the order of inclusion!!!
 */
require_once("Class.Logger.php");
require_once("Class.Settings.php");
require_once("Class.Validate.php");
require_once("Class.Cheesemaker.php");
require_once("Class.Database.php");
?>