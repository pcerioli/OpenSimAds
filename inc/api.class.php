<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class api
{

var $zw;
	
	function api(&$zw) {
		$this->zw = &$zw;
	}

	function generatetoken() {
		$userid = $this->zw->user_info['id'];
		$newcode = $this->zw->site->randcode($this->zw->config['APITokenLength']);
		$u = $this->zw->SQL->query("UPDATE `{$this->zw->config['db_prefix']}users` SET token = '$newcode' WHERE id = '$userid'");
		if ($u) {
			return $newcode;
		}else{
			return "0";
		}
	}

	function istokenvalid($token) {
		if (strlen($token) == $this->zw->config['APITokenLength']) {
			$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE token = '$token'");
			$n = $this->zw->SQL->num_rows($q);
			if ($n) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function token2user($token) {
		$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE token = '$token'");
		$r = $this->zw->SQL->fetch_array($q);
		return $r['id'];
	}

	function qr($url, $size = "100", $error = "H") {
		$url = urlencode($url);
		$randserver = rand(0,9);
		$host = "https://".$randserver.".chart.apis.google.com/chart?";
		$params = "chs=".$size."x".$size;
		$params .= "&cht=qr";
		$params .= "&chld=".$error;
		$params .= "&chl=".$url;
		return "<img src='".$host.$params."' />";
	}
	function btcqr($address, $label, $amount, $error = "H") {
		$url = "address=".$address;
		$url .= "&label=".$label;
		$url .= "&amount=".$amount;
		$url .= "&error=".$error;
		return "<img src='http://www.btcfrog.com/qr/bitcoinPNG.php?".$url."' />";
	}
}
?>