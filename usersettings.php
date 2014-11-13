<?php
$page_title = "User Settings";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');

if ($userid) {

$type = $zw->Security->make_safe($_GET['type']);

$username = $zw->user_info['username'];
$email = $zw->user_info['email'];
$reztime = $zw->user_info['created'];
$token = $zw->user_info['token'];
$rezday = $zw->site->time2date($reztime);

  $submit = $zw->Security->make_safe($_POST['submit']);

  if ($submit == "Save Settings") {
    $saveusername = $zw->Security->make_safe($_POST['saveusername']);
    $saveemail = $zw->Security->make_safe($_POST['saveemail']);
    $updateprofq = $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}users` SET username = '$saveusername' WHERE id = '$userid'");
    if ($updateprofq) {
      echo $zw->site->displayalert("Profile updated.", "success");
      $username = $saveusername;
      if ($saveemail != $email) {
        $findme = '@';
        $echeck = strpos($saveemail, $findme);
        if ($echeck !== false) {
            $zw->SQL->query("UPDATE `{$zw->config['db_prefix']}users` SET email = '$saveemail' WHERE id = '$userid'");
            $email = $saveemail;
        }else if ($echeck === false) {
          echo $zw->site->displayalert('Incorrect email address', "danger");
        }
      }
    }else{
      echo $zw->site->displayalert("Unable to update your profile.", "danger");
    }
  }
  $generatetoken = $zw->Security->make_safe($_POST['generatetoken']);
  if ($generatetoken) {
    $istoken = $zw->api->generatetoken();
    if ($istoken != "0") {
      $token = $istoken;
      echo $zw->site->displayalert("API Key generated.<br>New api key is: <B>".$token."</B>", "success");
    }else{
      $token = $token;
      echo $zw->site->displayalert("Unable to generate a new API Key for you at this time.", "danger");
    }
  }

if (!$type || $type == "settings") {
  $sactive = "class='active'";
  $aactive = "";
}else if ($type == "api") {
  $sactive = "";
  $aactive = "class='active'";
}
?>
<h3>User Control Panel</h3>
<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li <?php echo $sactive; ?>><a href="<?php echo $site_address; ?>/usersettings.php?type=settings">Settings</a></li>
  <li <?php echo $aactive; ?>><a href="<?php echo $site_address; ?>/usersettings.php?type=api">API</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
<?php if ($type == "settings" || !$type) { ?>
    <form class="form-horizontal" method="post" action="<?php echo $site_address; ?>/usersettings.php?type=<?php echo $type; ?>" role="form">
      <div class="form-group">
          <label for="inputUsername" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10">
            <input type="text" name="saveusername" value="<?php echo $username; ?>" id="inputUsername" class="form-control" placeholder="Username">
          </div>
      </div>
      <div class="form-group">
          <label for="inputEmail" class="col-sm-2 control-label">Change Email Address</label>
          <div class="col-sm-10">
            <input type="text" name="saveemail" value="<?php echo $email; ?>" id="inputEmail" class="form-control" placeholder="Current Email Address">
          </div>
      </div>
      <div class="form-group">
        <div class="col-sm-10">
          <input type="submit" name="submit" value="Save Settings" class="btn btn-success">
        </div>
      </div>
    </form><br>
    <a href="changepassword.php" class='btn btn-sm btn-danger'>Change Password</a>
<?php }else if ($type == "api") { ?>
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Your API Key</h3>
      </div>
      <div class="panel-body">
        <B><?php echo $token; ?></B>
      </div>
    </div>
    <form method='post' action='<?php echo $site_address; ?>/usersettings.php?type=<?php echo $type; ?>' class='form' role='form'>
      <input type='submit' name='generatetoken' value='Generate New API Key' class='btn btn-primary'>
    </form>
<?php
  }else{
  } // end if ($type)
} // end if ($userid)
include ('inc/footer.php');
?>