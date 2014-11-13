<?php
$page_title = "Register";
$hide_sidebars = true;
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');

if (!$userid && $zw->config['AllowRegistration'] == "y") {
	if (isset($_POST['process'])) {
		echo $zw->Users->register_user();
	}else{
        require_once('register_form.php');
	}
}else if (!$userid && $zw->config['AllowRegistration'] == "n") {
	echo $zw->site->displayalert("Registrations for this site are currently closed.", "danger");
}else if ($userid) {
    echo $zw->site->displayalert("You are already logged in.", "danger");
}

require_once('inc/footer.php');
?>