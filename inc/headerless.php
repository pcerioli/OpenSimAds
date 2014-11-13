<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;
}
error_reporting(0);
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('zw.class.php');
$zw = new zw();

date_default_timezone_set($zw->config['TimeZone']);

$ip = $_SERVER['REMOTE_ADDR'];
$thispage = $_SERVER['PHP_SELF'];

$now = time();
$fiveago = $now - 300;

$u = $zw->Security->make_safe($_GET['u']);

$gridname = $zw->config['SiteName'];
$site_address = $zw->config['SiteAddress'];

$userid = $zw->user_info['id'];
$user = $zw->user_info['username'];
?>
