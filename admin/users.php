<?php
$page_title = "Users";
define('ZW_IN_SYSTEM', true);
require_once('../inc/header.php');
if ($zw->grid->isAdmin($userid)) {

$c = $zw->Security->make_safe($_GET['c']);
$page = $zw->Security->make_safe($_GET['page']);

$sort = $zw->Security->make_safe($_GET['sort']);
$order = $zw->Security->make_safe($_GET['order']);

//$onlinecount = $zw->grid->onlinecount();

$totaloffset = "100";
if (!$page || $page == "0" || $page == "1") {
$page = "1";
$offset = "0";
}else if ($page == "2") {
$offset = $totaloffset;
}else if ($page >= "2") {
$offset = $totaloffset / 2 * $page;
}

if (!$order) {
	$order = "ASC";
}
if (!$sort) {
	$sortorder = "`username` $order";
}else{
	$sortorder = $sort." ".$order;
}
?>
<div class='table-responsive'>
<table class='table table-hover table-bordered table-striped'>
<thead>
<tr>
<th>
Username <small>Currently online: <?php echo $onlinecount; ?></small>
<span class="pull-right">
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=&order=ASC'><i class="fa fa-caret-up"></i></a><br>
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=&order=DESC'><i class="fa fa-caret-down"></i></a>
</span>
</th>
<th>
User Email
<span class="pull-right">
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=email&order=ASC'><i class="fa fa-caret-up"></i></a><br>
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=email&order=DESC'><i class="fa fa-caret-down"></i></a>
</span>
</th>
<th>
Date Created
<span class="pull-right">
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=created&order=ASC'><i class="fa fa-caret-up"></i></a><br>
<a href='<?php echo $site_address; ?>/admin/users.php?c=<?php echo $c; ?>&page=<?php echo $page; ?>&sort=created&order=DESC'><i class="fa fa-caret-down"></i></a>
</span>
</th>
<th>Last Login</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php
$userq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}users` ORDER BY $sortorder LIMIT $offset, $totaloffset");
while ($userr = $zw->SQL->fetch_array($userq)) {
$aviid = $userr['id'];
$aviemail = $userr['email'];
$aviuname = $userr['username'];
$aviuserlevel = $userr['rank'];
$avicreate = $zw->site->time2date($userr['created']);
$lastaction = $zw->site->time2date($userr['last_login']);

if ($zw->grid->online($aviid)) {
	$onoff = "onlinedot.png";
}else{
	$onoff = "offlinedot.png";
}

$aviname = "<a href='".$site_address."/profile.php?u=".$aviuname."'>".$aviuname."</a>";
if ($aviuserlevel == "0") {
	$uleveltr = "";
}else if ($aviuserlevel == $zw->config['admin_level'] || $aviuserlevel >= $zw->config['admin_level']) {
	$uleveltr = "success";
}else if ($aviuserlevel == "1") {
	$uleveltr = "danger";
}else if ($aviuserlevel == "2") {
	$uleveltr = "info";
}

echo "<tr class='".$uleveltr."'>
<td>".$aviname." <img src='".$site_address."/img/".$onoff."'> <small>(".$aviid.")</small></td>
<td>".$aviemail."</td>
<td>".$avicreate."</td>
<td>".$lastaction."</td>
<td>
<form method='post' action='edituser.php' class='form-horizontal' role='form'>
<input type='hidden' name='aviuuid' value='".$aviid."'>
<input type='submit' name='edituser' value='Edit User' class='btn btn-info btn-sm'>
</form>
</td>
</tr>";
}
?>
</tbody>
</table>
</div>
<?php
$pager = $site_address."/admin/users.php?c=".$c."&sort=".$sort."&order=".$order;
$tbl_name = "`{$zw->config['db_prefix']}users`";
echo $zw->pagination->paging($tbl_name, $pager, $totaloffset);
}
include ('../inc/footer.php');
?>