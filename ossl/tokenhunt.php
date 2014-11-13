<?php
define('ZW_IN_SYSTEM', true);
require_once('../inc/headerless.php');
$method = $zw->Security->make_safe($_GET['method']);
if ($method == "get") {
	die();
}
$type = $zw->Security->make_safe($_POST['type']);
$grid = $zw->Security->make_safe($_POST['grid']);
$gridq = $zw->SQL->query("SELECT * FROM grids WHERE shortname = '$grid'");
$gridn = $zw->SQL->num_rows($gridq);
if (!$gridn) {
die();
}
$dbid = $zw->Security->make_safe($_POST['dbid']);
$toucher = $zw->Security->make_safe($_POST['toucher']);
$touchername = $zw->Security->make_safe($_POST['touchername']);
$amount = $zw->Security->make_safe(round($_POST['amount'], 2));
$sim = $zw->Security->make_safe($_POST['sim']);
$pos = $zw->Security->make_safe($_POST['pos']);
$parcel = $zw->Security->make_safe($_POST['parcel']);
$primkey = $zw->Security->make_safe($_POST['primkey']);
$tokenworth = $zw->Security->make_safe(round($_POST['tokenworth'], 2));
$owner = $zw->Security->make_safe($_POST['owner']);
$ownername = $zw->Security->make_safe($_POST['ownername']);

$pos = str_replace("&lt;", "", $pos);
$pos = str_replace("&gt;", "", $pos);

if ($dbid) {
	$where = "dbid = '$dbid'";
}else{
	$where = "primkey = '$primkey'";
}
$checktokenq = $zw->SQL->query("SELECT * FROM tokens WHERE grid = '$grid' AND $where");
$checktokenn = $zw->SQL->num_rows($checktokenq);
if ($checktokenn) {
	$isrezzed = true;
	$checktokenr = $zw->SQL->fetch_array($checktokenq);
	$rechecktokenworth = round($checktokenr['tokenworth'], 2);
	$currentamount = round($checktokenr['amount'], 2);
}else{
	$isrezzed = false;
}
if ($type == "rez") {
	if ($isrezzed) {
		$zw->SQL->query("UPDATE tokens SET sim = '$sim', parcel = '$parcel', pos = '$pos', primkey = '$primkey', tokenworth = '$tokenworth' WHERE $where");
		echo "alreadyrezzed=".$dbid."=".$currentamount;
	}else{
		$dbid = $zw->getNewUUID();
		$zw->SQL->query("INSERT INTO tokens (grid, dbid, owner, ownername, sim, parcel, pos, primkey, tokenworth) VALUES ('$grid', '$dbid', '$owner', '$ownername', '$sim', '$parcel', '$pos', '$primkey', '$tokenworth')");
		echo "rezzed=".$dbid."=0.00";
	}
	$zw->avatar->getavatar($grid, $owner, $ownername);
}
if ($type == "deleteprim") {
	$aviq = $zw->SQL->query("SELECT * FROM avatars WHERE grid = '$grid' AND uuid = '$owner'");
	$avir = $zw->SQL->fetch_array($aviq);
	$newavicash = $avir['cash'] + $currentamount;
	$movemulaq = $zw->SQL->query("UPDATE avatars SET cash = '$newavicash' WHERE grid = '$grid' AND uuid = '$owner'");
	if ($movemulaq) {
		$delq = $zw->SQL->query("DELETE FROM tokens WHERE grid = '$grid' AND dbid = '$dbid' AND owner = '$owner'");
		if ($delq) {
			echo "success=".$newavicash;
		}else{
			echo "error on deleting token in the db.";
		}
	}else{
		echo "Error moving money to account.";
	}
}
if ($type == "gettoucher") {
	if ($currentamount != "0.00") {
		if ($zw->avatar->getavatar($grid, $toucher, $touchername)) {
			$avicheckq = $zw->SQL->query("SELECT * FROM avatar_actions WHERE grid = '$grid' AND dbid = '$dbid' AND type = 'tokenhunt' AND toucher = '$toucher' ORDER BY `time` DESC LIMIT 0,1");
			$avicheckn = $zw->SQL->num_rows($avicheckq);
			if ($avicheckn) {
				$avicheckr = $zw->SQL->fetch_array($avicheckq);
				$avichecketimer = $avicheckr['time'] + 86400;
				if ($avichecketimer <= $now) {
					echo "nottouched";
				}else{
					echo "touched";
				}
			}else{
				$zw->SQL->query("INSERT INTO avatar_actions (grid, dbid, type, toucher, firsttouchtime) VALUES ('$grid', '$dbid', 'tokenhunt', '$toucher', '$now')");
				echo "nottouched";
			}
		}
	}else{
		echo "outofcash";
	}
}
if ($type == "updatemoney") {
	$newamount = $currentamount + $amount;
	$zw->SQL->query("UPDATE tokens SET amount = '$newamount' WHERE $where");
	echo "success=".$amount."=".$newamount;
}
if ($type == "claimtoken") {
	$aviq = $zw->SQL->query("SELECT * FROM avatars WHERE grid = '$grid' AND uuid = '$toucher'");
	$avin = $zw->SQL->num_rows($aviq);
	if ($avin) {
		$avir = $zw->SQL->fetch_array($aviq);
		$avicash = $avir['cash'];
		$aviactionq = $zw->SQL->query("SELECT * FROM avatar_actions WHERE grid = '$grid' AND toucher = '$toucher'");
		$aviactionr = $zw->SQL->fetch_array($aviactionq);
		$newaviclaim = $aviactionr['timesclaimed'] + 1;

		$newcash = $avicash + $rechecktokenworth;
		$newamount = $currentamount - $rechecktokenworth;
		$newclaim = $checktokenr['claims'] + 1;
		$zw->SQL->query("UPDATE avatars SET cash = '$newcash' WHERE grid = '$grid' AND uuid = '$toucher'");
		$zw->SQL->query("UPDATE tokens SET amount = '$newamount', claims = '$newclaim' WHERE grid = '$grid' AND $where");
		$zw->SQL->query("UPDATE avatar_actions SET time = '$now', timesclaimed = '$newaviclaim' WHERE grid = '$grid' AND dbid = '$dbid' AND type = 'tokenhunt' AND toucher = '$toucher'");
		echo $toucher."=".$newcash."=".$newamount;
	}
}
?>