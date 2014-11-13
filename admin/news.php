<?php
$page_title = "News";
define('ZW_IN_SYSTEM', true);
require_once('../inc/header.php');

$submit = $zw->Security->make_safe($_POST['submit']);
$title = $zw->Security->make_safe($_POST['title']);
$message = $zw->Security->make_safe($_POST['message']);
$tags = $zw->Security->make_safe($_POST['tags']);
$editid = $zw->Security->make_safe($_POST['editid']);
$delid = $zw->Security->make_safe($_POST['delid']);
$edit = $zw->Security->make_safe($_GET['edit']);

if ($zw->grid->isAdmin($userid)) {
if ($submit == "Send") {
	if ($title) {
		if ($message) {
		$insrt = $zw->SQL->query("INSERT INTO `{$zw->config['db_prefix']}news` (title, msg, tags, time, poster) VALUES ('$title', '$message', '$tags', '$now', '$userid')");
			if ($insrt == true) {
			echo $zw->site->displayalert('NEWS ADDED');
			}else if ($insrt == false) {
			echo $zw->site->displayalert('News not added');
			}
		}
	}
}else if ($submit == "Edit") {
		$updateq = $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}news` SET title = '$title', msg = '$message', tags = '$tags', edit_time = '$now' WHERE id = '$editid'");
			if ($updateq) {
			echo $zw->site->displayalert('NEWS UPDATED');
			}else if ($insrt == false) {
			echo $zw->site->displayalert('News not updated');
			}
}else if ($submit == "Delete") {
$dq1 = $zw->SQL->query("DELETE FROM `{$zw->config['db_prefix']}news` WHERE id = '$delid'");
	if ($dq1) {
	echo $zw->site->displayalert('News and all comments for that news post have been deleted.');
	}else{
	echo $zw->site->displayalert('Error with deleting news post');
	}
}
if ($edit) {
$editq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}news` WHERE id = '$edit'");
$editrow = $zw->SQL->fetch_array($editq);
	$nid = $editrow['id'];
	$title = $editrow['title'];
	$message = $editrow['msg'];
	$ntags = $editrow['tags'];

	$message = htmlspecialchars_decode($message, ENT_NOQUOTES);
	$message = html_entity_decode($message);
	echo "<form method='post' action='' class='form-horizontal'>
<input type='hidden' name='editid' value='$edit'>
<input type='text' name='title' value='$title' class='input-xlarge form-control' placeholder='Title'><br>
<textarea class='input-xlarge form-control' rows='10' name='message'>$message</textarea><br>
<input type='text' name='tags' data-provide='tag' value='$ntags' id='tags' class='input-xlarge form-control' placeholder='Tag example: fart, poop, roflmao'><br>
<input type='submit' name='submit' value='Edit' class='btn btn-success'>
</form>";
}else{
?>
<form method="post" action="" class="form-horizontal">
<input type="text" name="title" class="input-xlarge form-control" placeholder="Title"><br>
<textarea class="input-xlarge form-control" rows="10" name="message"></textarea><br>
<input type='text' name='tags' data-provide='tag' id='tags' class='input-xlarge form-control' placeholder='Tag example: fart, poop, roflmao'><br>
<input type="submit" name='submit' value="Send" class="btn btn-success">
<script>
CKEDITOR.env.isCompatible = true;
CKEDITOR.replace('message');
</script>
</form>
<?php
}

$q = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}news` ORDER BY `id` DESC LIMIT 0,10");
while ($r = $zw->SQL->fetch_array($q))
{
$nid = $r['id'];
$title = $r['title'];
$message = $r['msg'];
$postedby = $r['poster'];
$time = $r['time'];
$date = $zw->site->time2date($time);

$postedname = $zw->grid->id2name($postedby);

$message = str_replace(chr(13),"<br>".chr(13),$message);
$message = htmlspecialchars_decode($message, ENT_NOQUOTES);
$message = html_entity_decode($message);

echo "<p align='left'>
<form method='post' action='' class='form-horizontal'>
<input type='hidden' name='delid' value='$nid'>
<a href='$address/news.php?id=$nid'><B>$title</B></a> - <small><i>Posted by: $postedname</i></small><br>
$message<br>
<small><i>Posted $date</i></small><br><a href='news.php?edit=$nid' class='btn btn-info'>Edit</a> <input type='submit' name='submit' value='Delete' class='btn btn-warning'>
</form>
</p>
<hr>
";

		++$i;
		}
}
include ('../inc/footer.php');
?>