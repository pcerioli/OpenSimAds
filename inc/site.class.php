<?php
if (!defined('ZW_IN_SYSTEM')) {
exit;	
}

class site {

var $zw;

	function site(&$zw) {
	$this->zw = &$zw;
	}

	function time2date($time) {
		$now = date('M d Y', time());
		$tcheck = date('M d Y', $time);
		if ($tcheck == $now) {
			return "Today @ ".date('g:ia T');
		}else{
			return date('M d Y g:ia T', $time);
		}
	}

	function getage($then) {
	$then = date('Ymd', strtotime($then));
	$diff = date('Ymd') - $then;
	return substr($diff, 0, -4);
	}

	function avg($currentcount, $totalcount) {
	$count1 = $currentcount / $totalcount;
	$count = $count1 * 100;
	return $count;
	}

	function ago($tm) {
	    $rcs = time();
	    $cur_tm = time();
	    $dif = $cur_tm-$tm;
	    $pds = array('second','minute','hour','day','week','month','year','decade');
	    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
	    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
   
	    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
	    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
	    return $x;
	}

	function banners($type) {
		$offset_result = $this->zw->SQL->query("SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `bannerads` WHERE type = '$type'");
		$offset_row = $this->zw->SQL->fetch_object($offset_result);
		$offset = $offset_row->offset;
		$result = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}bannerads` WHERE type = '$type' LIMIT $offset, 1");
		$row = $this->zw->SQL->fetch_array($result);
		$id = $row['id'];
		$linkurl = $row['linkurl'];
		$imgurl = $row['imgurl'];
		$alt = $row['alt'];
		$code = $row['code'];

		if ($type == "h") {
		$style = "max-width: 1000px; max-height: 100px;";
		}else if ($type == "v") {
		$style = "max-width: 100px; max-height: 800px;";
		}else if ($type == "b") {
		$style = "max-width: 300px; max-height: 300px;";
		}
			if (!$code) {
			$return = "<a href='$linkurl' target='_blank'><img src='$imgurl' border='0' alt='$alt' style='$style'></a>";
			}else if ($code) {
			$return = "$code";
			}
	return $return;
	}


	function getuserinfo() {
		$userid = $this->zw->user_info['id'];
		$q = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}users` WHERE id = '$userid'");
		$r = $this->zw->SQL->fetch_array($q);
		return $r;
	}

	function randcode($length) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWZYZ';
		for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, (strlen($characters)-1))];
		}
	return $string;
	}

	function sendemail($remail, $esubject, $emessage) {
		$headers = 'From: '.$this->zw->config['SiteEmail']."\r\n".'Content-type: text/html; charset=utf-8'."\r\n".'X-Mailer: PHP/'.phpversion();
		$emailsent = @mail($remail, $esubject, $emessage, $headers);
		if ($emailsent) {
			return true;
		}else{
			return false;
		}
	}

	function disqus($page, $id, $title) {
	$address = $this->zw->config['SiteAddress'];
	$return = "<div id=\"disqus_thread\"></div>
    <script type=\"text/javascript\">
        var disqus_shortname = '".$this->zw->config['DisqusShortName']."';
    	 var disqus_url = '".$address."/".$page."".$id."';
        var disqus_identifier = '".$id." ".$address."/".$page."".$id."';
        var disqus_container_id = 'disqus_thread';
        var disqus_domain = 'disqus.com';
        var disqus_title = '".$title."';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href=\"http://disqus.com/?ref_noscript\">comments powered by Disqus.</a></noscript>
    <a href=\"http://disqus.com\" class=\"dsq-brlink\">comments powered by <span class=\"logo-disqus\">Disqus</span></a>";
	return $return;
	}

	function selectcomments($page, $id, $title) {
	$disqus = $this->disqus($page, $id, $title);
	$return = "
<ul class='nav nav-tabs'>
  <li><a href='#ltc' data-toggle='tab'>".$this->zw->config['SiteName']."</a></li>
  <li class='active'><a href='#disqus' data-toggle='tab'>Disqus</a></li>
</ul>
<div class='tab-content'>
  <div class='tab-pane fade' id='ltc'>ZetamexWeb's own commenting system coming soon</div>
  <div class='tab-pane fade in active' id='disqus'>".$disqus."</div>
</div>
";
	return $return;
	}

	function getNews($id) {
		if ($id == "0") {
			$where = "ORDER BY `time` DESC LIMIT 0, 10";
		}else{
			$where = "WHERE id = '$id'";
		}
		echo "
		<div class='row'>
  			<div class='col-md-12'>
  					";
  						$newsq = $this->zw->SQL->query("SELECT * FROM `{$this->zw->config['db_prefix']}news` $where");
  						while ($r = $this->zw->SQL->fetch_array($newsq)) {
  							$nid = $r['id'];
  							$title = $r['title'];
  							$msg = $r['msg'];
  							$time = $r['time'];
  							$poster = $r['poster'];
  							$date = $this->time2date($time);
  							$postername = $this->zw->grid->id2name($poster);
  							$linkname = str_replace(" ", ".", $postername);
  							$postername = "<a href='".$this->zw->config['SiteAddress']."/profile.php?u=".$linkname."'>".$postername."</a>";
  							$msg = str_replace(chr(13),"<br>".chr(13),$msg);
							$message = htmlspecialchars_decode($msg, ENT_NOQUOTES);
							$msg = html_entity_decode($msg);
  							if ($id == "0") {
  								echo "
  								<h4><a href='".$this->zw->config['SiteAddress']."/news.php?id=".$nid."'>".$title."</a></h4><small>".$date." - <a href='".$this->zw->config['SiteAddress']."/news.php?id=".$nid."#disqus_thread'>".$title."</a></small><br><hr><br>
  								";
  							}else{
  								echo "
  								<p><h3>".$title."</h3>".$msg."<br><small>Posted by ".$postername." on ".$date." - <a href='".$this->zw->config['SiteAddress']."/news.php?id=".$nid."#disqus_thread'>".$title."</a></small></p>
  								";
  							}
  						}
  						if ($id == "0") {
						}else{
							echo "<p>".$this->selectcomments('news.php?id=', $id, $title)."</p>";
						}
  						echo "
  			</div>
  		</div>";
	}

	function displayalert($msg, $type = "") {
		if (!$type) {
			$type = "warning";
		}
		return "<div class='alert alert-dismissable alert-".$type."'>
  				<button type='button' class='close' data-dismiss='alert'>X</button>
  				<p class='".$type."'><B>".$msg."</B></p>
			 </div>";
	}

	function twitter() {
		$tweet = $this->zw->config['Twitter'];
		if ($tweet) {
			$tweetkey = $this->zw->config['TwitterAPIKey'];
			$return = "<a class='twitter-timeline' data-dnt='true' href='https://twitter.com/".$tweet."' data-widget-id='".$tweetkey."'>Tweets by @".$tweet."</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
			";
		}else{
			$return = "";
		}
		return $return;
	}
}
?>