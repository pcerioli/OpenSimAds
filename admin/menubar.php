<?php
$page_title = "Edit Menubar";
define('ZW_IN_SYSTEM', true);
require_once('../inc/header.php');

if ($zw->grid->isAdmin($userid)) {

$submit = $zw->Security->make_safe($_POST['submit']);

if ($submit == "Save") {
	$updatemenu = "";
	$postname = $zw->Security->make_safe($_POST['name']);
	foreach ($postname as $namekey => $namevalue) {
		$updatemenu .= $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}mainmenu` SET name = '$namevalue'  WHERE id = '$namekey'");
	}
	$posturl = $zw->Security->make_safe($_POST['url']);
	foreach ($posturl as $urlkey => $urlvalue) {
		$updatemenu .= $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}mainmenu` SET url = '$urlvalue'  WHERE id = '$urlkey'");
	}
	$postchildof = $zw->Security->make_safe($_POST['childof']);
	foreach ($postchildof as $childofkey => $childofvalue) {
		$updatemenu .= $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}mainmenu` SET childof = '$childofvalue'  WHERE id = '$childofkey'");
	}
	$postsortby = $zw->Security->make_safe($_POST['sortby']);
	foreach ($postsortby as $sortbykey => $sortbyvalue) {
		$updatemenu .= $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}mainmenu` SET sortby = '$sortbyvalue'  WHERE id = '$sortbykey'");
	}
	if ($updatemenu) {
		echo $zw->site->displayalert('Menu updated', "success");
	}else{
		echo $zw->site->displayalert('Menu not updated', "danger");
	}
}
if ($submit == "Delete") {
	$delmenu = "";
	$postid = $zw->Security->make_safe($_POST['id']);
	foreach ($postid as $idkey => $idvalue) {
		$delmenu .= $zw->SQL->query("DELETE FROM `{$zw->config['db_prefix']}mainmenu` WHERE id = '$idkey'");
	}
	if ($delmenu) {
		echo $zw->site->displayalert('Menu deleted', "success");
	}else{
		echo $zw->site->displayalert('Menu not deleted', "danger");
	}
}
if ($submit == "Add New") {
	$postname = $zw->Security->make_safe($_POST['name']);
	$posturl = $zw->Security->make_safe($_POST['url']);
	$postchildof = $zw->Security->make_safe($_POST['childof']);
	$postsortby = $zw->Security->make_safe($_POST['sortby']);
	$insertnew = $zw->SQL->query("INSERT INTO `{$zw->config['db_prefix']}mainmenu` (name, url, childof, sortby) VALUES ('$postname','$posturl','$postchildof','$postsortby')");
	if ($insertnew) {
		echo $zw->site->displayalert('Menu Added', "success");
	}else{
		echo $zw->site->displayalert('Menu not Added', "danger");
	}
}

echo "<form method='post' action='' class='form' role='form'>
<h3>Menu Bar</h3>
<table class='table table-striped table-hover'>
<thead>
<tr>
<th></th>
<th>NAME</th>
<th>URL</th>
<th>Child Of</th>
<th>Sort By</th>
</tr>
</thead>
<tbody>";
$q1 = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}mainmenu` WHERE childof = '0' ORDER BY `sortby` ASC");
if ($zw->SQL->num_rows($q1)) {
	while ($r1 = $zw->SQL->fetch_array($q1)) {
		$mainid = $r1['id'];
		$mainname = $r1['name'];
		$mainurl = $r1['url'];
		$mainchildof = $r1['childof'];
		$mainsortby = $r1['sortby'];
		$childoflist = $zw->admin->childoflist($mainid, $mainchildof);
		$showchilds = $zw->admin->getchilds($mainid);
		echo "
		<tr>
			<td><input type='checkbox' name='id[".$mainid."]' value='".$mainid."'></td>
			<td><input type='text' name='name[".$mainid."]' value='".$mainname."' class='form-control'></td>
			<td><input type='text' name='url[".$mainid."]' value='".$mainurl."' class='form-control'></td>
			<td>
				<select name='childof[".$mainid."]' class='form-control'>
				".$childoflist."
				</select>
			</td>
			<td><input type='text' name='sortby[".$mainid."]' value='".$mainsortby."' class='form-control'></td>
			<td>
		</tr>
		";
		echo $showchilds;
	}
}
echo "</tbody>
<table>
<input type='submit' name='submit' value='Save' class='btn btn-success'>
<input type='submit' name='submit' value='Delete' class='btn btn-danger'>
<h3>Add a new menu</h3>
<table class='table table-striped table-hover'>
<thead>
<tr>
<th>NAME</th>
<th>URL</th>
<th>Child Of</th>
<th>Sort By</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type='text' name='name' value='' class='form-control' placeholder='Name'></td>
<td><input type='text' name='url' value='' class='form-control' placeholder='URL'></td>
<td>
	<select name='childof' class='form-control'>
	".$zw->admin->childoflist("0", "0")."
	</select>
</td>
<td><input type='text' name='sortby' value='' class='form-control' placeholder='Sort Order'></td>
</tr>
</tbody>
</table>
<input type='submit' name='submit' value='Add New' class='btn btn-success'>
</form>
";

}else{
	echo $zw->site->displayalert('You are not a admin', "danger");
}
include ('../inc/footer.php');
?>