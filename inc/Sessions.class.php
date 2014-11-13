<?php
if (!defined('ZW_IN_SYSTEM')) {
	exit;
}

class Sessions {

var $zw;

	function Sessions(&$zw) {
		$this->zw = &$zw;
	}

	function update_user($page = "") {
		$now = time();
		$user = $this->zw->user_info['id'];
		if ($user) {
			$usercheckq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$user'");
			$usercheckn = $this->zw->SQL->num_rows($usercheckq);
			if ($usercheckn) {
				$this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET last_action = '$now' WHERE id = '$user'");
			}else{
				// do nothing
			}
		}
	}

	function clear_old_sessions() {
        $time_minus_defined = time() - $this->zw->config['cookie_length'];
        $this->zw->SQL->query("DELETE FROM `{$this->zw->config['db_prefix']}sessions` WHERE time < '$time_minus_defined'");
	}

	function create_session($id, $remember) {
		$time = time();

		$code = $this->zw->site->randcode($this->zw->config['max_password']);

		$sesscheckq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}sessions` WHERE id = '$id'");
		$sesschecker = $this->zw->SQL->num_rows($sesscheckq);
		if ($sesschecker) {
			$this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}sessions` SET code = '$code', time = '$time' WHERE id = '$id'");
		}else{
			$this->zw->SQL->query("INSERT INTO `{$this->zw->config['db_prefix']}sessions` (id, code, time) VALUES ('$id','$code','$time')");
		}
		$this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET session = '$code' WHERE id = '$id'");
		
		if ($remember == "true") {
			setcookie($this->zw->config['cookie_prefix'] . 'id', $id, $time + $this->zw->config['cookie_length'], $this->zw->config['cookie_path'], $this->zw->config['cookie_domain'], false, false);
			setcookie($this->zw->config['cookie_prefix'] . 'time', sha1($time), $time + $this->zw->config['cookie_length'], $this->zw->config['cookie_path'], $this->zw->config['cookie_domain'], false, false);
			setcookie($this->zw->config['cookie_prefix'] . 'code', $code, $time + $this->zw->config['cookie_length'], $this->zw->config['cookie_path'], $this->zw->config['cookie_domain'], false, false);
		}

		$_SESSION[$this->zw->config['cookie_prefix'] . 'id'] = $id;
		$_SESSION[$this->zw->config['cookie_prefix'] . 'time'] = sha1($time);
		$_SESSION[$this->zw->config['cookie_prefix'] . 'code'] = $code;
	}

	function fetch_session($information) {
		$uid = $information[0];
		$utime = $information[1];
		$ucode = $information[2];
        $session_infoq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}sessions` WHERE code = '$ucode'");
        $session_info = $this->zw->SQL->fetch_array($session_infoq);
        $sess_time = sha1($session_info['time']);
		if ($session_info['id'] == $uid) {
			if ($sess_time == $utime) {
			    return true;
			}else{
			    return false;
			}
		}else{
		   return false;
		}
	}

	function validate_session($information) {
		if (is_array($information)) {
			$uid = $information[0];
			$utime = $information[1];
			$ucode = $information[2];

			if ($this->fetch_session($information)) {
                    $new_time = time();
                    $sha1_time = sha1($new_time);

                    if ($utime) {
                        setcookie($this->zw->config['cookie_prefix'] . 'time', $sha1_time, $new_time + $this->zw->config['cookie_length'], $this->zw->config['cookie_path'], $this->zw->config['cookie_domain'], false, false);
                    }

                    $_SESSION[$this->zw->config['cookie_prefix'] . 'time'] = $sha1_time;

                    $this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}sessions` SET time = '$new_time' WHERE id = '$uid'");

					$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE session = '$ucode'");
					$r = $this->zw->SQL->fetch_array($q);
                    foreach ($r as $key => $value) {
						$this->zw->user_info[$key] = $value;
					}
				return true;
			}else{
				return false;
			}
		}else{
		    return false;
		}
	}

	function find_session() {
		if ($_COOKIE[$this->zw->config['cookie_prefix'] . 'id']) {
            $information = array(
                $_COOKIE[$this->zw->config['cookie_prefix'] . 'id'],
                $_COOKIE[$this->zw->config['cookie_prefix'] . 'time'],
                $_COOKIE[$this->zw->config['cookie_prefix'] . 'code']
            );
		}else if ($_SESSION[$this->zw->config['cookie_prefix'] . 'id']) {
            $information = array(
                $_SESSION[$this->zw->config['cookie_prefix'] . 'id'],
                $_SESSION[$this->zw->config['cookie_prefix'] . 'time'],
                $_SESSION[$this->zw->config['cookie_prefix'] . 'code']
            );
		}else{
            $information = "";
		}
		return $this->validate_session($information);
	}
}
?>