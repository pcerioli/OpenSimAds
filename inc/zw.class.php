<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class zw
{

	function zw()
	{
		require_once('Security.class.php');
		$this->Security = new Security($this);

		Require_once('SQL.class.php');
		$this->SQL = new SQL($this);

		$cq = $this->SQL->query("SELECT * FROM `{$this->config['db_prefix']}settings`");
		while ($crow = $this->SQL->fetch_array($cq)) {
			$this->config[$crow['name']] = $crow['value'];
		}

		Require_once('Sessions.class.php');
		$this->Sessions = new Sessions($this);

		Require_once('Users.class.php');
		$this->Users = new Users($this);

		Require_once('pagination.class.php');
		$this->pagination = new pagination($this);

		Require_once('other.class.php');
		$this->other = new other($this);

		Require_once('site.class.php');
		$this->site = new site($this);

		Require_once('grid.class.php');
		$this->grid = new grid($this);

		Require_once('text.class.php');
		$this->text = new text($this);

		Require_once('admin.class.php');
		$this->admin = new admin($this);

		Require_once('api.class.php');
		$this->api = new api($this);
		
		Require_once('avatar.class.php');
		$this->avatar = new avatar($this);

		Require_once('lsl.class.php');
		$this->lsl = new lsl($this);

		$this->main_directory = str_replace('/inc', '', dirname(__FILE__));

		$this->Users->validate_login();
		$this->Sessions->update_user($_SERVER['PHP_SELF']);
		$this->Sessions->clear_old_sessions();
		$this->grid->clearexpiredresets();
		$this->nullkey = "00000000-0000-0000-0000-000000000000";
	}

	function redirect($url) {
		switch ($this->config['redirect_type']) {
			default:
                header('Location: ' . $url);
                exit;
			break;
			case 2:
			    echo <<<META
<html><head><meta http-equiv="Refresh" content="0;URL={$url}" /></head><body></body></html>
META;
			    break;
			case 3:
			    echo <<<SCRIPT
<html><body><script>location="{$url}";</script></body></html>
SCRIPT;
			    break;
		}
	}

	function getNewUUID() {
		$UUID = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
		return $UUID;
	}

	function RESTget($address) {
		$address = str_replace("http://", "", $address);
		$port = 80;
		$fp = @fsockopen($address, $port, $errno, $errstr, 10);
		if ($fp) {
			$return = fgets($fp);
		}else{
			$return = "ERROR: ".$errno." - ".$errstr."<br />\n";
		}
		return $return;
	}
}
?>