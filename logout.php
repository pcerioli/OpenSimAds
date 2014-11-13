<?php
define('ZW_IN_SYSTEM', true);
require_once('inc/headerless.php');
if ($zw->Users->logout_user()) {
	$zw->redirect($zw->config['logout_redirect']);
}
include ('inc/footer.php');
?>