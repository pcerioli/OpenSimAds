<?php
$page_title = "Login";
$hide_sidebars = "true";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');

//$lastpage = $zw->Security->make_safe($_GET['lp']);
$err = $zw->Security->make_safe($_GET['err']);

if ($zw->user_info['username'] == '') {
	require_once('login_form.php');
}else{
	echo $zw->site->displayalert("You are already logged in.", "danger");
}
include ('inc/footer.php');
?>