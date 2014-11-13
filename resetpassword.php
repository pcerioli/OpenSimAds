<?php
$page_title = "Reset Password";
$hide_sidebars = true;
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');

$postusername = $zw->Security->make_safe($_POST['postusername']);
$submit = $zw->Security->make_safe($_POST['submit']);

if (!$userid) {
	if ($postfirst && $postlast && $submit == "Reset Password") {
		$getosuser = $zw->grid->getuser_by_name($postusername);
		$uid = $getosuser['id'];
		$emailaddy = $getosuser['email'];
		if ($zw->grid->sendresetconfirm($uid)) {
			echo $zw->site->displayalert("A confirmation email to reset your password has been sent to ".$emailaddy, "success");
			echo "<br>";
			echo $zw->site->displayalert("Please check your spam folder and add ".$zw->config['SiteEmail']." to your safe list.", "danger");
		}else{
			echo $zw->site->displayalert("Unable to send a confirmation email to reset your password. Please see a grid admin for a reset.", "danger");
		}
	}else{ // else if ($postemail is empty && $submit is not "Reset Password")
?>
	<h3>Forgot Password</h3>
	<form class="form-horizontal" method="post" action="" role="form">
		<div class="form-group">
		    <input type="text" name="postusername" class="form-control" placeholder="Your <?php echo $zw->config['GridName']; ?> Username">
		</div>
		<div class="form-group">
		    <input type="submit" name="submit" value="Reset Password" class="btn btn-success">
		</div>
	</form>
<?php
	} // end if ($postemail)
}else if ($userid) {
	echo $zw->site->displayalert("You are already logged in. Please logout to reset your password or use the User Control Panel to change your password.", "danger");
}
include ('inc/footer.php');
?>