<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class admin
{

var $zw;
	
	function admin(&$zw)
	{
		$this->zw = &$zw;
	}

	function childoflist($mainid, $mainchildof) {
		$return3 = "";
		if ($mainchildof == "0") {
			$mainbarselect = "SELECTED";
		}else{
			$mainbarselect = "";
		}
		$return3 .= "<option value='0' ".$mainbarselect.">Mainbar</option>";
		$q3 = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}mainmenu` WHERE id != '$mainid'");
		while ($r3 = $this->zw->SQL->fetch_array($q3)) {
			$mainid3 = $r3['id'];
			$mainname3 = $r3['name'];
			$mainchildof3 = $r3['childof'];
			if ($mainid3 == $mainchildof) {
				$mainselect3 = "SELECTED";
			}else{
				$mainselect3 = "";
			}
			$return3 .= "<option value='".$mainid3."' ".$mainselect3.">".$mainname3."</option>";
		}
		return $return3;
	}

	function getchilds($chidof) {
		$return2 = "";
		$q2 = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}mainmenu` WHERE childof = '$chidof' ORDER BY `sortby` ASC");
		while ($r2 = $this->zw->SQL->fetch_array($q2)) {
			$mainid2 = $r2['id'];
			$mainname2 = $r2['name'];
			$mainurl2 = $r2['url'];
			$mainchildof2 = $r2['childof'];
			$mainsortby2 = $r2['sortby'];
			$childoflist2 = $this->childoflist($mainid2, $mainchildof2);
			$return2 .= "<tr>
			<td><input type='checkbox' name='id[".$mainid2."]' value='".$mainid2."'></td>
			<td><input type='text' name='name[".$mainid2."]' value='".$mainname2."' class='form-control'></td>
			<td><input type='text' name='url[".$mainid2."]' value='".$mainurl2."' class='form-control'></td>
			<td>
			<select name='childof[".$mainid2."]' class='form-control'>
			".$childoflist2."
			</select>
			</td>
			<td><input type='text' name='sortby[".$mainid2."]' value='".$mainsortby2."' class='form-control'></td>
			</tr>";
		}
		return $return2;
	}
}
?>