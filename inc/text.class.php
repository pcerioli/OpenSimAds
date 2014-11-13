<?php
if (!defined('ZW_IN_SYSTEM')) {
exit; 
}

class text {

var $zw;

      function text(&$zw) {
        $this->zw = &$zw;
      }

      function howlongago($lastdate){
      $current = date('Y-m-d H:i:s');
      $dateexplode = explode(" ", $lastdate);
      $newday = explode("-", $dateexplode[0]);
      $newtime = explode(":", $dateexplode[1]);

      $yearsago = date('Y') - $newday[0];
      $monthsago = date('m') - $newday[1];
      $daysago = date('d') - $newday[2];

      $hoursago = date('H') - $newtime[0];
      $minutesago = date('i') - $newtime[1];
      $secondsago = date('s') - $newtime[2];

      $lastdate = "$years $months $days $hours $minutes $seconds ago";
      return $lastdate;
      }

      function filterBadWords($str){
          $badword[] = array("Fuck", "****");
          $badword[] = array("fuck", "****");
          $badword[] = array("Shit", "****");
          $badword[] = array("shit", "****");
          $badword[] = array("whore", "****");
          $badword[] = array("bitch", "****");
          $badword[] = array("jerk", "****");
          $badword[] = array("asshole", "****");
          $badword[] = array("ass", "****");
          $badword[] = array("cunt", "****");
          $badword[] = array("tits", "****");
          $badword[] = array("pussy", "****");
          $badword[] = array("dick", "****");
          $badword[] = array("cock", "****");
          foreach ($badword as $badword) {
      	    $str = str_replace($badword[0],$badword[1],$str);
          }
          return $str;
      }

      function getAge($then) {
          $then = date('Ymd', strtotime($then));
          $diff = date('Ymd') - $then;
          return substr($diff, 0, -4);
      }

      function online_status($status) {
       if ($status == "0") {
       $status = "Offline";
       }else if ($status == "1") {
       $status = "Online";
       }else if ($status == "2") {
       $status = "Busy";
       }else if ($status == "3") {
       $status = "Away";
       }else if ($status == "4") {
       $status = "Idle";
       }
      return $status;
      }

      function smiley($text) { 
          $emoticons = array();
          $emoticons[] = array(":)", "[img]".$this->zw->config['SiteAddress']."/forum/set1/001_smile.gif[/img]");
          $emoticons[] = array(":D", "[img]".$this->zw->config['SiteAddress']."/forum/set1/biggrin.gif[/img]");
          $emoticons[] = array(":(", "[img]".$this->zw->config['SiteAddress']."/forum/set1/sad.gif[/img]");
          $emoticons[] = array("8O", "[img]".$this->zw->config['SiteAddress']."/forum/set1/blink.gif[/img]");
          $emoticons[] = array(":?", "[img]".$this->zw->config['SiteAddress']."/forum/set1/confused1.gif[/img]");
          $emoticons[] = array("8)", "[img]".$this->zw->config['SiteAddress']."/forum/set1/001_cool.gif[/img]");
          $emoticons[] = array(":lol:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/lol.gif[/img]");
          $emoticons[] = array(":x", "[img]".$this->zw->config['SiteAddress']."/forum/set1/mad.gif[/img]");
          $emoticons[] = array(":P", "[img]".$this->zw->config['SiteAddress']."/forum/set1/tongue_smilie.gif[/img]");
          $emoticons[] = array(":oops:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/blushing.gif[/img]");
          $emoticons[] = array(":cry:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/crying.gif[/img]");
          $emoticons[] = array(":evil:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/angery.gif[/img]");
          $emoticons[] = array(":twisted:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/icon_twisted.gif[/img]");
          $emoticons[] = array(":roll:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/icon_twisted.gif[/img]");
          $emoticons[] = array(":wink:", "[img]".$this->zw->config['SiteAddress']."/forum/set1/wink.gif[/img]");
          $emoticons[] = array(";)", "[img]".$this->zw->config['SiteAddress']."/forum/set1/wink.gif[/img]");
          $emoticons[] = array(":o", "[img]".$this->zw->config['SiteAddress']."/forum/set1/ohmy.gif[/img]");
          foreach ($emoticons as $emoticon) {
      	    $text = str_replace($emoticon[0],$emoticon[1],$text);
          }
          return $text;
      }

      function read_mycode($str) {
        $str = $this->bbcode_format($str);
        $str = $this->bbcode_quote($str);
        $str = $this->bbcode_img($str);
        $str = str_replace(chr(13),"<br>".chr(13),$str);
        $str = htmlspecialchars_decode($str, ENT_NOQUOTES);
        $str = html_entity_decode($str);
        return $str;
      }

      function bbcode_format ($str) {
        $str = htmlentities($str);
          $simple_search = array(  
                      //added line break  
                      '/\[br\]/is',
                      '/\[center\](.*?)\[\/center\]/is',
                      '/\[b\](.*?)\[\/b\]/is',
                      '/\[i\](.*?)\[\/i\]/is',
                      '/\[u\](.*?)\[\/u\]/is',
                      '/\[url\=(.*?)\](.*?)\[\/url\]/is',
                      '/\[url\](.*?)\[\/url\]/is',
                      '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',
                      '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',
                      '/\[mail\](.*?)\[\/mail\]/is',
                      '/\[font\=(.*?)\](.*?)\[\/font\]/is',
                      '/\[size\=(.*?)\](.*?)\[\/size\]/is',
                      '/\[color\=(.*?)\](.*?)\[\/color\]/is',
                        //added textarea for code presentation  
                     '/\[codearea\](.*?)\[\/codearea\]/is',
                       //added pre class for code presentation  
                    '/\[code\](.*?)\[\/code\]/is',
                      //added paragraph  
                    '/\[p\](.*?)\[\/p\]/is',
                    '/\[video\](.*?)\[\/video\]/is',
                      );  
        
          $simple_replace = array(  
      				//added line break  
                     '<br />',
                      '<center>$1</center>',
                      '<strong>$1</strong>',
                      '<em>$1</em>',
                      '<u>$1</u>',
      				// added nofollow to prevent spam  
                      '<a href="$1" target="_parent">$2</a>',
                      '<a href="$1" target="_parent">$1</a>',
                      '<div style="text-align: $1;">$2</div>',
      				//added alt attribute for validation  
                      '<a href="mailto:$1" target="_parent">$2</a>',
                      '<a href="mailto:$1" target="_parent">$1</a>',
                      '<span style="font-family: $1;">$2</span>',
                      '<span style="font-size: $1;">$2</span>',
                      '<span style="color: $1;">$2</span>',
      				//added textarea for code presentation  
      				'<textarea class="code_container" rows="5" cols="50">$1</textarea>',
      				//added pre class for code presentation  
      				'<pre class="code">$1</pre>',
      				//added paragraph  
      				'<p>$1</p>',
      '<iframe src="$1" width="800" height="450" frameborder="0" scrolling="no"></iframe>',
                      ); 
        $str = str_replace($simple_search, $simple_replace, $str);
        return $str;
      }

      function bbcode_quote ($str) {
        $open = '<blockquote>';
        $close = '</blockquote>';
        preg_match_all ('/\[quote\]/i', $str, $matches);
        $opentags = count($matches['0']);
        preg_match_all ('/\[\/quote\]/i', $str, $matches);
        $closetags = count($matches['0']);
        $unclosed = $opentags - $closetags;
        for ($i = 0; $i < $unclosed; $i++) {
          $str .= '</blockquote>';
        }
        $str = str_replace('[quote]', $open, $str);
        $str = str_replace('[/quote]', $close, $str);
        return $str;
      }

      function bbcode_img ($str) {
        $open = '<img src="';
        $close = '" alt="" border="0">';
        preg_match_all ('/\[img\]/i', $str, $matches);
        $opentags = count($matches['0']);
        preg_match_all ('/\[\/img\]/i', $str, $matches);
        $closetags = count($matches['0']);
        $unclosed = $opentags - $closetags;
        for ($i = 0; $i < $unclosed; $i++) {
          $str .= '" alt="" border="0">';
        }
        $str = str_replace('[img]', $open, $str);
        $str = str_replace('[/img]', $close, $str);
        return $str;
      }

      function genra($mvgenra) {
      $genra[] = array("00" , "Music Video");
      $genra[] = array("01" , "WoW Music");
      $genra[] = array("02" , "Aion Music");
      $genra[] = array("03" , "Dance / Techno / DnB");
      $genra[] = array("04" , "Hardcore / Rave / Gabber");
      $genra[] = array("05" , "Hip Hop / Rap");
      $genra[] = array("06" , "Rock / Alt / Heavy Metal");
      $genra[] = array("07" , "Classical");
      $genra[] = array("08" , "Oldies");
      $genra[] = array("09" , "Other music");
      $genra[] = array("10" , "TV series Show");
      $genra[] = array("11" , "TV news cast");
      $genra[] = array("12" , "Personal video");
      $genra[] = array("13" , "Live action");
      $genra[] = array("14" , "Comedy");
      $genra[] = array("15" , "Movie");
      $genra[] = array("16" , "Action movie");
      $genra[] = array("17" , "Scifi movie");
      $genra[] = array("18" , "Comedy movie");
      $genra[] = array("19" , "Scary movie");
      $genra[] = array("20" , "How-To turtorial video");
      $genra[] = array("99" , "Other");
          foreach ($genra as $genra) {
      	    $mvgenra = str_replace($genra[0],$genra[1],$mvgenra);
          }
      return $mvgenra;
      }

      function get_rank_name($groupid_to_name) {
      $groupid[] = array("1" , "Banned");
      $groupid[] = array("2" , "Free");
      $groupid[] = array("3" , "ViP");
      $groupid[] = array("4" , "Admin");
      $groupid[] = array("5" , "Manager");
          foreach ($groupid as $groupid) {
      	    $groupid_to_name = str_replace($groupid[0],$groupid[1],$groupid_to_name);
          }
      return $groupid_to_name;
      }

      function avg($currentcount, $totalcount) {
      $count1 = $currentcount / $totalcount;
      $count = $count1 * 100;
      return $count;
      }

      function avg_rating($currentcount, $totalcount) {
      $count = $currentcount / $totalcount;
      return $count;
      }

      function month_name($month) {
      $m[] = array("01" , "Janurary");
      $m[] = array("02" , "Feburary");
      $m[] = array("03" , "March");
      $m[] = array("04" , "April");
      $m[] = array("05" , "May");
      $m[] = array("06" , "June");
      $m[] = array("07" , "July");
      $m[] = array("08" , "August");
      $m[] = array("09" , "September");
      $m[] = array("10" , "October");
      $m[] = array("11" , "November");
      $m[] = array("12" , "December");
          foreach ($m as $m) {
      	    $month = str_replace($m[0],$m[1],$month);
          }
      return $month;
      }

      function music_genre() {
      $genre = array("Blues","Classic Rock","Country","Dance","Disco","Funk","Grunge",
       "Hip-Hop","Jazz","Metal","New Age","Oldies","Other","Pop","R&B",
       "Rap","Reggae","Rock","Techno","Industrial","Alternative","Ska",
       "Death Metal","Pranks","Soundtrack","Euro-Techno","Ambient",
       "Trip-Hop","Vocal","Jazz+Funk","Fusion","Trance","Classical",
       "Instrumental","Acid","House","Game","Sound Clip","Gospel",
       "Noise","AlternRock","Bass","Soul","Punk","Space","Meditative",
       "Instrumental Pop","Instrumental Rock","Ethnic","Gothic",
       "Darkwave","Techno-Industrial","Electronic","Pop-Folk",
       "Eurodance","Dream","Southern Rock","Comedy","Cult","Gangsta",
       "Top 40","Christian Rap","Pop/Funk","Jungle","Native American",
       "Cabaret","New Wave","Psychadelic","Rave","Showtunes","Trailer",
       "Lo-Fi","Tribal","Acid Punk","Acid Jazz","Polka","Retro",
       "Musical","Rock & Roll","Hard Rock","Folk","Folk-Rock",
       "National Folk","Swing","Fast Fusion","Bebob","Latin","Revival",
       "Celtic","Bluegrass","Avantgarde","Gothic Rock","Progressive Rock",
       "Psychedelic Rock","Symphonic Rock","Slow Rock","Big Band",
       "Chorus","Easy Listening","Acoustic","Humour","Speech","Chanson",
       "Opera","Chamber Music","Sonata","Symphony","Booty Bass","Primus",
       "Porn Groove","Satire","Slow Jam","Club","Tango","Samba",
       "Folklore","Ballad","Power Ballad","Rhythmic Soul","Freestyle",
       "Duet","Punk Rock","Drum Solo","Acapella","Euro-House","Dance Hall", "Drum & Bass", "Hardcore");
      return $genre;
      }

      function getExtension($str) {
               $i = strrpos($str,".");
               if (!$i) { return ""; }
               $l = strlen($str) - $i;
               $ext = substr($str,$i+1,$l);
               return $ext;
      }

      function ago($tm, $rcs) {
          $cur_tm = time();
          $dif = $cur_tm-$tm;
          $pds = array('second','minute','hour','day','week','month','year','decade');
          $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
          for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
         
          $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
          if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
          return $x;
      }

      function imageResize($width, $height, $target) { 
      //takes the larger size of the width and height and applies the formula accordingly...this is so this script will work dynamically with any size image 
      if ($width > $height) { 
      $percentage = ($target / $width); 
      } else { 
      $percentage = ($target / $height); 
      } //gets the new value and applies the percentage, then rounds the value 
      $width = round($width * $percentage); 
      $height = round($height * $percentage); //returns the new sizes in html image tag format...this is so you can plug this function inside an image tag and just get the return
      return "width='$width' height='$height'"; 
      }

      function progressbar ($currentcount, $totalcount) {
      $avg1 = $this->avg($currentcount, $totalcount);
      $avg2 = $currentcount." / ".$totalcount;
      $avg3 = $avg1."%<br>".$avg2;
      return $avg3;
      }
}
?>