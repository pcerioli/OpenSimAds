<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;
}
?>
	<form action="register.php" method="post">
		<input type="hidden" name="process" value="true" />
<div class="container">
 <div class="row">
  <div class="col-md-12">
   <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-lock"></i> Register</div>
    <div class="panel-body">

	<div class="form-group">
		<label for="InputUsername" class="col-sm-3 control-label">Username <font color="Red">^</font></label>
		<div class="col-sm-9">
		<input type="text" id="InputUsername" class="form-control" name="username" maxlength="100" value="" placeholder="Username" required />
		</div>
	</div>

	<div class="form-group">
		<label for="InputPassword" class="col-sm-3 control-label">Password <font color="Red">* ^</font></label>
		<div class="col-sm-9">
		<input type="password" id="InputPassword" class="form-control" name="password" maxlength="<?php echo $zw->config['max_password']; ?>" placeholder="Password" required />
		</div>
	</div>

	<div class="form-group">
		<label for="InputCPassword" class="col-sm-3 control-label">Confirm Password <font color="Red">* ^</font></label>
		<div class="col-sm-9">
		<input type="password" id="InputCPassword" class="form-control" name="password_c" maxlength="<?php echo $zw->config['max_password']; ?>" placeholder="Confirm Password" required />
		</div>
	</div>

	<div class="form-group">
		<label for="InputEmail" class="col-sm-3 control-label">Email address <font color="Red">^</font></label>
		<div class="col-sm-9">
		<input type="text" id="InputEmail" class="form-control" name="email" maxlength="100" value="" placeholder="Email Address" required />
		</div>
	</div>
<?php
/* START SECURITY IMAGE */
if ($zw->config['security_image'] == 'yes') {
?>
	<div class="form-group">
		<label for="InputRobotTest" class="col-sm-3 control-label">Are you human?</label>
		<div class="col-sm-9">
			<?php
			require_once('recaptchalib.php');
  			$publickey = $zw->config['ReCaptcha_Public_Key'];
  			echo recaptcha_get_html($publickey);
			?>
		</div>
	</div>
<?php
}
/* END SECURITY IMAGE */
?>
	<div class="form-group">
		<label for="Submit" class="col-sm-3 control-label"></label>
		<div class="col-sm-9">
			<input type="submit" id="Submit" value="Register" class="btn btn-primary" /><br>
			<br><small><font color="Red">*</font> Passwords MUST be <?php echo $zw->config['min_password']; ?> to <?php echo $zw->config['max_password']; ?> characters.</small>
			<br><small><font color="Red">^</font> Required to join.</small>
		</div>
	</div>

    </div>
<div class="panel-footer">Already Registred? <a href="<?php echo $site_address; ?>/login.php">Login here</a></div>
   </div>
  </div>
 </div>
</div>
	</form>
<p>
<small>
This website uses cookies to store login information so the system knows its you without forcing you to login all the time.<br>
By registering you agree to allow this site place a cookie on your computer.<br>
Don't worry it doesn't store your password. Just your id, the current time and your session id.<br>
For more information google ZetamexWeb.<br>
<br>
When registering you agree to our <a href='<?php echo $site_address; ?>/tos.php'>Terms of Service</a> and our <a href='<?php echo $site_address; ?>/privacypolicy.php'>Cookie and Privacy Policy</a><br>
We only require a valid email address at registration to confirm that you are human and not a spam bot.<br>
We wont email you anything else without your approval.<br>
We also will NOT sell or give your email address to any annoying spam company because we hate spam too.
</small>
</p>