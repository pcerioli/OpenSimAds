<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

define('SYSTEM_INSTALLED', true);

$db_host = "localhost"; // ip / address to your database
$db_port = "3306"; // port number to your database, default is 3306
$db_user = "user"; // username to your db
$db_pass = "password"; // password to your db
$db_name = "databasename"; // database name for your website
$db_type = "MySQLie"; // type of database, if using MySQL which is default for Robust, please set this to MySQLie
$db_prefix = "zw_"; // prefix for ZetamexWebCMS tables, default is zw_
$db_perst = false; // no real idea what this does yet
?>
