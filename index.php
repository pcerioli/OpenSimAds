<?php
$page_title = "Home";
$hide_sidebars = "true";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');
?>
<!-- THIS IS JUST A DEMO. CHANGE ALL IF YOU WANT TO. -->
<div class="row">
	<div class="col-md-8">
		<h3><?php echo $zw->config['SiteName']; ?> News</h3>
  		<?php echo $zw->site->getNews('0'); ?>
	</div>
</div>
<?php
include ('inc/footer.php');
?>