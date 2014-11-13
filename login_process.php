<?php
define('ZW_IN_SYSTEM', true);
require_once('inc/headerless.php');

if ($zw->Security->make_safe($_POST['process'])) {
	$Username = $zw->Security->make_safe($_POST['Username']);
	$pass = $zw->Security->make_safe($_POST['password']);
	$remember = $zw->Security->make_safe($_POST['remember']);
	$lastpage = $zw->Security->make_safe($_POST['lastpage']);
	if ($zw->Users->login($Username, $pass, $remember)) {
		if (!$lastpage) {
			$lastpage = "index.php";
		}
	    $zw->redirect($lastpage);
	}else{
	    $zw->redirect('login.php?err=invalidcreds');
	}
}else{
    $zw->redirect('login.php?err=unable2process');
}
?>