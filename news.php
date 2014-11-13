<?php
$page_title = "News";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');
$id = $zw->Security->make_safe($_GET['id']);

if (!$id) {
	$id = "0";
}
?>
<div class="row">
	<div class="col-md-10">
  		<h3><?php echo $zw->config['SiteName']; ?> News</h3>
  		<?php echo $zw->site->getNews($id, ''); ?>
	</div>
</div>
<?php
include ('inc/footer.php');
?>