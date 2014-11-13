<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class avatar
{

var $zw;
	
	function avatar(&$zw) {
		$this->zw = &$zw;
	}

	function insertavi($grid, $uuid, $name) {
		$u = $this->zw->SQL->query("INSERT INTO avatars (grid, uuid, name, cash) VALUES ('$grid', '$uuid', '$name', '0.00')");
		if ($u) {
			return true;
		}else{
			return false;
		}
	}
	function getavatar($grid, $uuid, $name) {
		$u = $this->zw->SQL->query("SELECT * FROM avatars WHERE grid = '$grid' AND uuid = '$uuid'");
		$n = $this->zw->SQL->num_rows($u);
		if ($n) {
			return true;
		}else{
			return $this->insertavi($grid, $uuid, $name);
		}
	}
}
?>