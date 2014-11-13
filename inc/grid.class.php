<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class grid
{

var $zw;
	
	function grid(&$zw)
	{
		$this->zw = &$zw;
	}

	function name2id($username) {
	$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE username = '$username'");
	$r = $this->zw->SQL->fetch_array($q);
	$id = $r['id'];
	return $id;
	}

	function getuser_by_id($id) {
	$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$id'");
	$r = $this->zw->SQL->fetch_array($q);
	return $r;
	}

	function getuser_by_name($username) {
	$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE username = '$username'");
	$r = $this->zw->SQL->fetch_array($q);
	return $r;
	}

	function id2name($id) {
	$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$id'");
	$r = $this->zw->SQL->fetch_array($q);
	$name = $r['username'];
	return $name;
	}

	function online($ID) {
	$fiveago = time() - 300;
	$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$ID'");
	$n = $this->zw->SQL->num_rows($q);
	if ($n) {
		$r = $this->zw->SQL->fetch_array($q);
		$laston = $r['last_action'];
		if ($laston >= $fiveago) {
			$online = true;
		}else{
			$online = false;
		}
	}else{
		$online = false;
	}
	return $online;
	}

	function isAdmin($id) {
		if ($id) {
			$admin_level = $this->zw->config['admin_level'];
			$r = $this->getuser_by_id($id);
			$level = $r['rank'];
			if ($level == $admin_level || $level >= $admin_level) {
				return true;
			}else if ($level <= $admin_level) {
				return false;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function oscat($catid) {
		switch ($catid) {
		case "0":
			$r = "Any";
			break;
		case "18":
			$r = "Discussion";
			break;
		case "19":
			$r = "Sports";
			break;
		case "20":
			$r = "Live Music";
			break;
		case "22":
			$r = "Commercial";
			break;
		case "23":
			$r = "Nightlife/Entertainment";
			break;
		case "24":
			$r = "Games/Contests";
			break;
		case "25":
			$r = "Pageants";
			break;
		case "26":
			$r = "Education";
			break;
		case "27":
			$r = "Arts and Culture";
			break;
		case "28":
			$r = "Charity/Support Groups";
			break;
		case "29":
			$r = "Miscellaneous";
			break;
		}
	return $r;
	}

	function gridonline($loginuri) {
		$urlrep = str_replace("http://", "", $loginuri);
		$explode = explode(":", $urlrep);
		$ip2robust = $explode[0];
		$port2robust = $explode[1];
		$fp = @fsockopen($ip2robust, $port2robust, $errno, $errstr, 1);
		if ($fp) {
			$return = true;
			fclose($fp);
		}else{
			$return = false;
		}
		return $return;
	}

	function clearexpiredresets() {
		$now = time();
		$this->zw->SQL->query("DELETE FROM `{$this->zw->config['db_prefix']}resetcode` WHERE expiry < '$now'");
	}

	function sendresetconfirm($id) {
		$checkifexistq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$id'");
		$checkifexistn = $this->zw->SQL->num_rows($checkifexistq);
		if ($checkifexistn) {
			$checkifexistr = $this->zw->SQL->fetch_array($checkifexistq);
			$email = $checkifexistr['email'];
			$checkifresetexistq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}resetcode` WHERE id = '$id'");
			$checkifresetexistn = $this->zw->SQL->num_rows($checkifresetexistq);
			$randomid = $this->zw->site->randcode("6");
			$expiry = time() + 172800;
			if ($checkifresetexistn) {
				$codeq = $this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}resetcode` SET code = '$randomid', expiry = '$expiry' WHERE id = '$id'");
			}else{
				$codeq = $this->zw->SQL->query("INSERT INTO `{$this->zw->config['db_prefix']}resetcode` (id, code, expiry) VALUES ('$id', '$randomid', '$expiry')");
			}
			if ($codeq) {
				$expirydate = $this->zw->site->time2date($expiry);
				$confirmaadress = $this->zw->config['SiteAddress']."/confirmreset.php?reset=".$randomid."&id=".$uuid;
				$esubject = "Request for a Zetamex Status temporary password.";
				$emessage = "Someone has requested a new ".$this->zw->config['SiteName']." temporary password for <B>".$FirstName." ".$LastName."</B><br>
				Please visit <a href='".$confirmaadress."'>".$confirmaadress."</a> to confirm it was you.<br>
				The link will become invalid on ".$expirydate."<br>
				If you did not request a password reset please disregard this email.";
				$this->zw->site->sendemail($email, $esubject, $emessage);
				return true;
			}else{
				return false;
			}
		}
	}

	function senddata($Host, $PostData = "") {
		$Method = "POST";
		 if (empty($PostData))
		  {$Method = "GET";}
		 $Port = 80;
		 if (strtolower(substr($Host, 0, 5)) == "https")
		  {$Port = 443;}
		 $Host = explode("//", $Host, 2);
		 if (count($Host) < 2)
		  {$Host[1] = $Host[0];}
		 $Host = explode("/", $Host[1], 2);
		 if ($Port == 443)
		  {$SSLAdd = "ssl://";}
		 $Host[0] = explode(":", $Host[0]);
		 if (count($Host[0]) > 1)
		 {
		  $Port = $Host[0][1];
		  $Host[0] = $Host[0][0];
		 }
		 else
		  {$Host[0] = $Host[0][0];}
		 $Socket = fsockopen($SSLAdd.$Host[0], $Port, $Dummy1, $Dummy2, 10);
		 if ($Socket)
		 {
		  fputs($Socket, $Method." /".$Host[1]." HTTP/1.1\r\n".
						 "Host: ".$Host[0]."\r\n".
						 "Content-type: application/x-www-form-urlencoded\r\n".
						 "User-Agent: Opera/9.01 (Windows NT 5.1; U; en)\r\n".
						 "Accept-Language: de-DE,de;q=0.9,en;q=0.8\r\n".
						 "Accept-Charset: iso-8859-1, utf-8, utf-16, *;q=0.1\r\n".
						 "Content-length: ".strlen($PostData)."\r\n".
						 "Connection: close\r\n".
						 "\r\n".
						 $PostData);
		  $Tme = time();
		  while(!feof($Socket) && $Tme + 30 > time())
		   {$Res = $Res.fgets($Socket, 256);}
		  fclose($Socket);
		 }
		 $Res = explode("\r\n\r\n", $Res, 2);
		 return $Res[1];
	}
}
?>