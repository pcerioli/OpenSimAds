<?php
$page_title = "Settings";
define('ZW_IN_SYSTEM', true);
require_once('../inc/header.php');

$hctoken = $zw->config['HipChatToken'];

$cat = $zw->Security->make_safe($_GET['cat']);
$type = $zw->Security->make_safe($_GET['type']);
if (!$type) {
  $type = "site";
}

$savesettings = $zw->Security->make_safe($_POST['savesettings']);
$zwsettings = $zw->Security->make_safe($_POST['zwsettings']);

if ($zw->grid->isAdmin($userid)) {
  if ($savesettings) {
  	foreach ($zwsettings as $key => $value) {
  	 $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}settings` SET value = '$value' WHERE name = '$key'");
  	}
  echo $zw->site->displayalert('<strong>SAVED!</strong> Settings saved', "success");
  }

echo "
<ul class='nav nav-tabs' role='tablist'>
";
$menuq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}settings_menu` ORDER BY `id` ASC LIMIT 0,100");
while ($menur = $zw->SQL->fetch_array($menuq)) {
  $menuname = $menur['name'];
  if ($type == $menuname) {
    $typeselected = "class='active'";
  }else{
    $typeselected = "";
  }
  echo "<li ".$typeselected."><a href='settings.php?type=".$menuname."'>".$menuname."</a></li>";
}
echo "
</ul>
<form class='form-horizontal' role='form' method='post' action='settings.php?type=".$type."'>
   <div class='form-group'>
    <div class='col-sm-2'>
      <B>Setting Name</B>
    </div>
    <div class='col-sm-4'>
      <B>Setting Value</B>
    </div>
    <div class='col-sm-4'>
      <B>Setting Info</B>
    </div>
  </div>";

$sq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}settings` WHERE type = '$type' ORDER BY `name` ASC LIMIT 0,100");
while ($sr = $zw->SQL->fetch_array($sq)) {
  $sid = $sr['id'];
  $sname = $sr['name'];
  $svalue = $sr['value'];
  $info = $sr['info'];

  if ($info) {
    $sinfo = "<small>".$info."</small>";
  }else{
    $sinfo = "";
  }

  if ($sname == "activation_type") {
    $inputa = "";
    $inputa .= "<select name='zwsettings[activation_type]' class='form-control'>
    <option value='0'";
    if ($svalue == "0" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">None</option>
    <option value='1'";
    if ($svalue == "1" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">User</option>
    <option value='2'";
    if ($svalue == "2" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">Admin</option>
    </select>";
    $input = $inputa;
  }else if ($sname == "AllowRegistration") {
    $inputa = "";
    $inputa .= "<select name='zwsettings[AllowRegistration]' class='form-control'>
    <option value='n'";
    if ($svalue == "n" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">No</option>
    <option value='y'";
    if ($svalue == "y" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">Yes</option>
    </select>";
    $input = $inputa;
  }else if ($sname == "redirect_type") {
    $inputa = "";
    $inputa .= "<select name='zwsettings[redirect_type]' class='form-control'>
    <option value='1'";
    if ($svalue == "1" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">PHP</option>
    <option value='2'";
    if ($svalue == "2" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">HTML</option>
    <option value='3'";
    if ($svalue == "3" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">JavaScript</option>
    </select>";
    $input = $inputa;
  }else if ($sname == "admin_level") {
    $inputa = "";
    $inputa .= "<select name='zwsettings[admin_level]' class='form-control'>
    <option value='4'";
    if ($svalue == "4" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">4</option>
    <option value='5'";
    if ($svalue == "5" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">5</option>
    </select>";
    $input = $inputa;
  }else if ($sname == "security_image") {
    $inputa = "";
    $inputa .= "<select name='zwsettings[security_image]' class='form-control'>
    <option value='no'";
    if ($svalue == "no" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">No</option>
    <option value='yes'";
    if ($svalue == "yes" ) {
      $inputa .= "SELECTED";
    }else{
    }
    $inputa .= ">Yes</option>
    </select>";
    $input = $inputa;
  }else{
    $input = "<input type='text' class='form-control' id='".$sname."' placeholder='".$sname."' name='zwsettings[".$sname."]' value='".$svalue."'>";
  }
  echo "
   <div class='form-group'>
    <label for='".$sname."' class='col-sm-2 control-label'>".$sname."</label>
    <div class='col-sm-4'>
      ".$input."
    </div>
    <div class='col-sm-4'>
      ".$sinfo."
    </div>
  </div>
  ";
}
?>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="savesettings" value="Save">
    </div>
  </div>
</form>
<?php
}
include ('../inc/footer.php');
?>