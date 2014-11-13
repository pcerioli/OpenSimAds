<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class other
{

var $zw;
	
	function other(&$zw)
	{
		$this->zw = &$zw;
	}

	/*
	* More info at http://php.net/manual/en/function.curl-setopt.php
	*/
	function curl($array) {
		if (is_array($array)) {
			$ch = curl_init();
			foreach ($array as $key => $value) {
				curl_setopt($ch, $key, $value);
			}
			$xml = curl_exec($ch);
			if (!$xml) {
                throw new Exception("Error getting data from server ($url): " . curl_error($ch));
        	}
			curl_close($ch);
			return $xml;
		}else{
			return "Data is not a array";
		}
	}

	function postdata($url, $post) {
		$data = array(CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9',
			CURLOPT_URL => ($url),
			CURLOPT_ENCODING => 'UTF-8',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $post
			);
		return $this->curl($data);
	}

	function sshcommand($server, $command)
	{
		require_once('ssh/Net/SSH2.php');
		$ssh = new Net_SSH2($server);
		if (!$ssh->login($this->zw->config['ssh_user'], $this->zw->config['ssh_pass'] )) {
			return "Login Failed";
		}
		return $ssh->exec($command);
	}
}
?>