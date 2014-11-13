<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class Users
{

var $zw;
	
	function Users(&$zw)
	{
		$this->zw = &$zw;
	}

	function validate_password($input) {
		if (strlen($input) <= $this->zw->config['max_password'] && strlen($input) >= $this->zw->config['min_password']) {
		    return true;
		}else{
		    return false;
		}
	}

	function validate_login() {
		if ($this->zw->Sessions->find_session()) {
		    return true;
		}else{
		    return false;
		}
	}

	function generate_password_salt() {
		$randomuuid = $this->zw->getNewUUID();
		$strrep = str_replace("-", "", $randomuuid);
		return md5($strrep);
	}

	function generate_password_hash($psswrd, $code) {
		return md5(md5($psswrd).":".$code);
	}

	function compare_passwords($input_password, $real_password, $code) {
        $input_hash = $this->generate_password_hash($input_password, $code);

		if ($input_hash == $real_password) {
		    return true;
		}else{
		    return false;
		}
	}

	function login($username, $pass, $remember) {
		$q1 = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE username = '$username'");
		$n1 = $this->zw->SQL->num_rows($q1);
		if ($n1) {
			$r1 = $this->zw->SQL->fetch_array($q1);
			$userid = $r1['id'];
			$user_pass = $r1['password'];
			$user_code = $r1['salt'];

			$time = time();

			if ($this->validate_password($pass)) {
				if ($this->compare_passwords($pass, $user_pass, $user_code)) {
					if ($remember == "1") {
						$this->zw->Sessions->create_session($userid, "true");
					}else{
						$this->zw->Sessions->create_session($userid, "false");
					}
					$this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET last_login = '$time' WHERE id = '$userid'");
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function logout_user() {
	    $session_names = array('id', 'time', 'code');
	    $ses_id = $_SESSION[$this->zw->config['cookie_prefix'] . 'id'];
		if (isset($ses_code)) {
            $this->zw->SQL->query("DELETE FROM `{$this->zw->config['db_prefix']}sessions` WHERE id = '$ses_id'");
		}
        $_SESSION = array();

		if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), '', time() - 42000, '/');
		}

		if (isset($_COOKIE[$this->zw->config['cookie_prefix'] . 'id'])) {
			foreach ($session_names as $value) {
			    setcookie($this->zw->config['cookie_prefix'] . $value, 0, time() - 3600, $this->zw->config['cookie_path'], $this->zw->config['cookie_domain'], false, false);
			}
		}
		return true;
	}

	function check_user_exist($username) {
		if (!$username) {
			return false;
		}
		$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE username = '$username'");
		$r = $this->zw->SQL->num_rows($q);
		if ($r) {
			return true;
		}else{
			return false;
		}
	}

	function id_to_username($userid) {
        $userid = (is_numeric($userid) && $userid > 0) ? $userid : 0;
        $result = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$userid'");
        $row = $this->zw->SQL->fetch_array($result);
        $username = $row['username'];
        return $username;
	}

	function emailconfirmation($userid, $email, $isnewuser) {
		$randomid = $this->zw->site->randcode("6");
		$this->zw->SQL->query("INSERT INTO `{$this->zw->config['db_prefix']}emailconfirm` (id, email, code, isnewuser) VALUES ('$userid', '$email', '$randomid', '$isnewuser')");
		$resetaddyer = $this->zw->config['SiteAddress']."/confirmemail.php?email=".$email."&code=".$randomuuid;
		$esubject = $this->zw->config['SiteName']." email validation.";
		$emessage = "Please validate your email address for ".$this->zw->config['SiteName']." by clicking the link below.<br>
		<a href='".$resetaddyer."'>".$resetaddyer."</a>";
		$this->zw->site->sendemail($email, $esubject, $emessage);
	}

	function emailconfirmed($email, $code) {
		$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}emailconfirm` WHERE code = '$code' AND email = '$email'");
		$n = $this->zw->SQL->num_rows($q);
		if ($n) {
			$r = $this->zw->SQL->fetch_array($q);
			$userid = $r['id'];
			$isnewuser = $r['isnewuser'];
			if ($isnewuser == "y") {
				$this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET email = '$email' WHERE id = '$userid'");
			}else if ($isnewuser == "n") {

			}
			$this->zw->SQL->query("DELETE FROM `{$this->zw->config['db_prefix']}emailconfirm` WHERE id = '$userid'");
			return true;
		}else{
			return false;
		}
	}

	function register_user() {
		$username = $_POST['username'];
		$pass = $_POST['password'];
		$cpass = $_POST['password_c'];
		$email = $_POST['email'];
		
		if ($this->zw->config['security_image'] == "yes") {
			require_once('recaptchalib.php');
			$privatekey = $this->zw->config['ReCaptcha_Private_Key'];
			$resp = recaptcha_check_answer ($privatekey,
	                            $_SERVER["REMOTE_ADDR"],
	                            $_POST["recaptcha_challenge_field"],
	                            $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				// What happens when the CAPTCHA was entered incorrectly
				return $this->zw->site->displayalert('ReCaptcha is wrong', "danger");
				die ("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")");
				$isago = false;
			}else{
				$isago = true;
			}
		}else if ($this->zw->config['security_image'] == "no") {
			$isago = true;
		}
		if ($isago) {
			// Your code here to handle a successful verification
			if ($this->check_user_exist($username)) {
				// If avatar name already exist in the database we fail the registration. Dont need two Asshat Jockstrap's running around.
				return $this->zw->site->displayalert('User already exist', "danger");
			}else{
				if ($pass == $cpass) { // this makes sure that both password feilds are the same
					if ($this->validate_password($pass)) { // this makes sure the password entered is a valid length.
						$findme = '@';
						$echeck = strpos($email, $findme);
						if ($echeck !== false) {
							// now we can start processing the registration.
							$salt = $this->generate_password_salt();
							$hashedpass = $this->generate_password_hash($pass, $salt);
							$time = time();
							$activation_type = $this->zw->config['activation_type'];
							if ($activation_type == "0") {
								$UserLevel = "2";
							}else{
								$UserLevel = "1";
							}
							$insert1 = $this->zw->SQL->query("INSERT INTO `{$this->zw->config['db_prefix']}users` (username, password, salt, email, rank, created) VALUES ('$username', '$hashedpass', '$salt', '$email', '$UserLevel', '$time')");
							if ($insert1) {
								$isregistered = true;
							}else{
								$isregistered = false;
							}
							if ($activation_type == "0" && $isregistered) {
								return $this->zw->site->displayalert('<strong>REGISTERED!</strong> Welcome to '.$this->zw->config['SiteName'], "success");
							}else if ($activation_type == "1" && $isregistered) {
								$this->emailconfirmation($email, "y");
								return $this->zw->site->displayalert('<strong>REGISTERED!</strong> However this grid requires you to confirm your email address.', "success");
							}else if ($activation_type == "2" && $isregistered) {
								//$this->zw->api->sendmsg($username." has joined ".$this->zw->config['SiteName'], "yellow");
								return $this->zw->site->displayalert('<strong>REGISTERED!</strong> However this grid requires a admin to approve you.', "success");
							}else if ($activation_type >= "2") {
								return $this->zw->site->displayalert('Unable to figure out how to register you to the grid.', "danger");
							}else if ($isregistered == false) {
								return $this->zw->site->displayalert('ERROR in saving your data to the ZetamexWeb database.', "danger");
							}
						}else if ($echeck === false) {
							return $this->zw->site->displayalert('Incorrect email address', "danger");
						}
					}else{
						return $this->zw->site->displayalert('Password is incorrect', "danger");
					}
				}else{
					return $this->zw->site->displayalert('The two passwords you entered do not match with each other.', "danger");
				}
			}
		}
	}

	function get_zw_user($id) {
		$getuuidq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$id'");
		$getuuidr = $this->zw->SQL->fetch_array($getuuidq);
		return $getuuidr;
	}

	function changepassword($currentpass, $newpass, $confirmpass) {
		if ($currentpass && $newpass && $confirmpass) {
			$loggedinid = $this->zw->user_info['id'];
			$q1 = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$loggedinid'");
			$n1 = $this->zw->SQL->num_rows($q1);
			if ($n1) {
				$r1 = $this->zw->SQL->fetch_array($q1);
				$user_pass = $r1['password'];
				$user_code = $r1['salt'];
				if ($this->validate_password($currentpass)) {
					if ($this->compare_passwords($currentpass, $user_pass, $user_code)) {
						if ($newpass == $confirmpass) {
							$salt = $this->generate_password_salt();
							$hashedpass = $this->generate_password_hash($newpass, $salt);
							$time = time();
							$pupdate = $this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET password = '$hashedpass', passwordSalt = '$salt' WHERE id = '$loggedinid'");
							if ($pupdate) {
								$getemailq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$loggedinid'");
								$getemailr = $this->zw->SQL->fetch_array($getemailq);
								$email = $getemailr['email'];
								$username = $getemailr['username'];
								$resetaddyer = $this->zw->config['SiteAddress']."/resetpassword.php";
								$esubject = "Your ".$this->zw->config['SiteName']." password has been changed.";
								$emessage = "Your ".$this->zw->config['SiteName']." password for ".$username." has been changed.<br>
								If you did not do this then please visit <a href='".$resetaddyer."'>".$resetaddyer."</a> for a new temporary password.";
								$this->zw->site->sendemail($email, $esubject, $emessage);
								return true;
							}else{
								return false;
							}
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function resetpass($id) {
		if ($id != "") {
			$checkifexistq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$id'");
			$checkifexistn = $this->zw->SQL->num_rows($checkifexistq);
			if ($checkifexistn) {
				$checkifexistr = $this->zw->SQL->fetch_array($checkifexistq);
				$email = $checkifexistr['email'];
				$username = $checkifexistr['username'];
				$randpass = $this->zw->site->randcode($this->zw->config['min_password']);
				$salt = $this->generate_password_salt();
				$hashedpass = $this->generate_password_hash($randpass, $salt);
				$time = time();
				$pupdate = $this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET password = '$hashedpass', salt = '$salt' WHERE id = '$id'");
				if ($pupdate) {
					$resetaddy = $this->zw->config['SiteAddress']."/login.php";
					$esubject = "Your new ".$this->zw->config['SiteName']." temporary password.";
					$emessage = "Your new ".$this->zw->config['SiteName']." temporary password for <B>".$username."</B> is:<br>
					".$randpass."<br>
					Please visit <a href='".$resetaddy."'>".$resetaddy."</a> with your new temporary password.";
					$this->zw->site->sendemail($email, $esubject, $emessage);
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>
