<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;
}
error_reporting(E_ALL ^ E_NOTICE);
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('zw.class.php');
$zw = new zw();

$timezone = $zw->config['TimeZone'];
date_default_timezone_set($timezone);

$ip = $_SERVER['REMOTE_ADDR'];
$thispage = $_SERVER['PHP_SELF'];

$now = time();
$fiveago = $now - 300;
$u = $zw->Security->make_safe($_GET['u']);
$api = $zw->Security->make_safe($_GET['api']);
$isviewer = $zw->Security->make_safe($_GET['isviewer']);

$sitename = $zw->config['SiteName'];
$site_address = $zw->config['SiteAddress'];

$userid = $zw->user_info['id'];
$user_info = $zw->grid->getuser_by_id($userid);
$user = $zw->user_info['username'];

if (!$api) {
?>
<!-- AT4cM9iLW393 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<!-- Fav Icon -->
<link rel="icon" type="image/png" href="<?php echo $site_address; ?>/favicon.png">
<link rel="shortcut icon" href="<?php echo $site_address; ?>/favicon.ico">

<meta name="author" content="<?php echo $sitename; ?>">
<meta name="description" content="Innovated ideas for the metaverse">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<title id='titlebar'><?php echo $sitename." - ".$page_title; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- You can change this to your own bootstrap file -->
<link href="<?php echo $site_address; ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo $site_address; ?>/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo $site_address; ?>/css/normalize.css" rel="stylesheet">
<link href="<?php echo $site_address; ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo $site_address; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $site_address; ?>/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $site_address; ?>/ckeditor/ckeditor.js"></script>
<script src="<?php echo $site_address; ?>/ckeditor/adapters/jquery.js"></script>
<style>
body {
	padding-top: 5px;
	padding-left: 15px;
	padding-right: 15px;
	padding-bottom: 5px;
}
</style>
</head>
<body>
<div class="container">
	<div class='row'>
		<div class='col-md-6'>
			<a href='<?php echo $site_address; ?>'><img src='<?php echo $site_address; ?>/img/opensimadslogo.png' class="img-responsive" alt="Responsive image" border='0'></a>
		</div>
		<div class='col-md-6 pull-right'>
			<?php
			$q1 = $zw->SQL->query("SELECT * FROM grids");
			$n1 = $zw->SQL->num_rows($q1);
			$q2 = $zw->SQL->query("SELECT * FROM stores");
			$n2 = $zw->SQL->num_rows($q2);
			$q3 = $zw->SQL->query("SELECT * FROM avatars");
			$n3 = $zw->SQL->num_rows($q3);
			$q4 = $zw->SQL->query("SELECT * FROM tokens");
			$n4 = $zw->SQL->num_rows($q4);
			//$q5 = $zw->SQL->query("SELECT * FROM something");
			//$n5 = $zw->SQL->num_rows($q5);
			?>
			<p class="lead text-right">
			<B><?php echo $n1; ?></B> Grids<br>
			<B><?php echo $n2; ?></B> Stores<br>
			<B><?php echo $n3; ?></B> Avatars<br>
			<B><?php echo $n4; ?></B> Tokens
			</p>
		</div>
	</div>
<?php
include ('menu.php');
} // ends if (!$api)
?>
