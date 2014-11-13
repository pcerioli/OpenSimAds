<?php
$page_title = "Edit Users";
define('ZW_IN_SYSTEM', true);
require_once('../inc/header.php');

if ($zw->grid->isAdmin($userid)) {

$aviuuid = $zw->Security->make_safe($_POST['aviuuid']);
$edituser = $zw->Security->make_safe($_POST['edituser']);
$submit = $zw->Security->make_safe($_POST['submit']);

if ($aviuuid != "") {

	if ($submit == "Reset Password") {
		if ($zw->grid->sendresetconfirm($aviuuid)) {
			$resetuserq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}users` WHERE id = '$aviuuid'");
			$resetuserr = $zw->SQL->fetch_array($resetuserq);
			$resetuserfirst = $resetuserr['FirstName'];
			$resetuserlast = $resetuserr['LastName'];
			echo $zw->site->displayalert("Confirmation for a password reset has been sent to ".$resetuserfirst." ".$resetuserlast. " via email address ".$resetuseremail, "success");
		}else{
			echo $zw->site->displayalert("Unable to send confirmation for a password reset. Please see inform a ZMW developer.", "danger");
		}
	}
	if ($submit == "Save User") {
		$postemail = $zw->Security->make_safe($_POST['postemail']);
		$postlevel = $zw->Security->make_safe($_POST['postlevel']);
		$u = $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}users` SET email = '$postemail', rank = '$postlevel' WHERE id = '$aviuuid'");
		if ($u) {
			echo $zw->site->displayalert("User Updated", "success");
		}else{
			echo $zw->site->displayalert("User was not updated", "danger");
		}
	}
	if ($submit == "Approve User" || $submit == "Unban User") {
		$u = $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}users` SET rank = '2' WHERE id = '$aviuuid'");
	}
	if ($submit == "Ban User") {
		$u = $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}users` SET rank = '1' WHERE id = '$aviuuid'");
	}
	$q = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}users` WHERE id = '$aviuuid'");
	$r = $zw->SQL->fetch_array($q);
	$aviemail = $r['email'];
	$avilevel = $r['rank'];
	$avicreated = $r['created'];
	$avlastlogin = $r['last_login'];
	if ($avlastlogin == "0") {
		$avilastlogin = "Never";
	}else{
		$avilastlogin = $zw->site->time2date($avlastlogin);
	}
	if ($zw->grid->online($aviuuid)) {
		$online = "onlinedot.png";
	}else{
		$online = "offlinedot.png";
	}
	if ($avilevel == "0") {
		$userstatusbuttons = "<input type='submit' name='submit' value='Approve User' class='btn btn-success btn-sm'>";
	}else if ($avilevel == "1") {
		$userstatusbuttons = "<input type='submit' name='submit' value='Unban User' class='btn btn-warning btn-sm'>";
	}else if ($avilevel >= "1") {
		$userstatusbuttons = "<input type='submit' name='submit' value='Ban User' class='btn btn-danger btn-sm'>";
	}
	echo "
	<div class='table-responsive'>
	<form method='post' action='' class='form' role='form'>
	<input type='hidden' name='aviuuid' value='".$aviuuid."'>
	<table class='table table-hover table-striped'>
	<tbody>
		<tr>
			<td><B>Name</B></td>
			<td>".$zw->grid->id2name($aviuuid)." <img src='".$site_address."/img/".$online."'></td>
		</tr>
		<tr>
			<td><B>ID</B></td>
			<td>".$aviuuid."</td>
		</tr>
		<tr>
			<td><B>Created</B></td>
			<td>".$zw->site->time2date($avicreated)."</td>
		</tr>
		<tr>
			<td><B>Last Login</B></td>
			<td>".$avilastlogin."</td>
		</tr>
		<tr>
			<td><B>Email</B></td>
			<td><input type='text' name='postemail' value='".$aviemail."' class='form-control'></td>
		</tr>
		<tr>
			<td><B>User Level</B></td>
			<td>
				<select name='postlevel' class='form-control'>
					<option value='0' "; if ($avilevel == "0") { echo "SELECTED"; } echo">Awating Approval = 0</option>
					<option value='1' "; if ($avilevel == "1") { echo "SELECTED"; } echo">Banned = 1</option>
					<option value='2' "; if ($avilevel == "2") { echo "SELECTED"; } echo">Member = 2</option>
					<option value='3' "; if ($avilevel == "3") { echo "SELECTED"; } echo">Premium = 3</option>
					<option value='4' "; if ($avilevel == "4") { echo "SELECTED"; } echo">Moderator = 4</option>
					<option value='5' "; if ($avilevel == "5") { echo "SELECTED"; } echo">Admin = 5</option>
				</select>
			</td>
		</tr>
	</tbody>
	</table>
		<input type='submit' name='submit' value='Save User' class='btn btn-success btn-sm'>
		<input type='submit' name='submit' value='Reset Password' class='btn btn-danger btn-sm'>
		".$userstatusbuttons."
	</form>
	</div>";
}

}else{
	echo $zw->site->displayalert("You are not the captian.", "danger");
}
include ('../inc/footer.php');
?>