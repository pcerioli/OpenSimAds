<?php
$page_title = "Vendor Manager";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');

$url = "http://198.61.243.161:9500/lslhttp/53c7a423-ec17-46fc-a453-5367c433017c/";
//$data = "I farted from the website";
$data = "GIVEINV=8f7a347e-02f1-4611-9667-286acd6262ec";
echo $zw->grid->senddata($url, $data);

require_once('inc/footer.php');
?>