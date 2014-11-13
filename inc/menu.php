<!-- top menu -->
<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
  	<div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse-1">

		<!-- <li><a href='<?php echo $site_address; ?>/'>Example of a single menu link</a></li> -->
    	<ul class="nav navbar-nav">

    		<?php
    		$mq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}mainmenu` WHERE childof = '0' ORDER BY `sortby` ASC LIMIT 0,100");
    		while($mr = $zw->SQL->fetch_array($mq)) {
    			$mid = $mr['id'];
    			$mname = $mr['name'];
    			$murl = $mr['url'];
    			$msq = $zw->SQL->query("SELECT * FROM `{$zw->config['db_prefix']}mainmenu` WHERE childof = '$mid' ORDER BY `sortby` ASC LIMIT 0,100");
    			$msc = $zw->SQL->num_rows($msq);
    			if ($msc) {
    				echo "<li class='dropdown'>
    						<a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$mname." <b class='caret'></b></a>
    							<ul class='dropdown-menu'>";
    							while ($msr = $zw->SQL->fetch_array($msq)) {
    								$msid = $msr['id'];
    								$msname = $msr['name'];
    								$msurl = $msr['url'];
                    if(strpos($msurl, "http://") !== false || strpos($msurl, "https://") !== false) {
                      $msaddy = $msurl;
                    }else{
                      $msaddy = $site_address."/".$msurl;
                    }
    								echo "<li><a href='".$msaddy."'>".$msname."</a></li>";
    							}
    							echo "</ul>
									</li>";
    			}else{
            if(strpos($murl, "http://") !== false || strpos($murl, "https://") !== false) {
              $maddy = $murl;
            }else{
              $maddy = $site_address."/".$murl;
            }
    				echo "<li><a href='".$maddy."'>".$mname."</a></li>";
    			}
    		}
    		?>
		</ul>
		<!-- This shouldnt be in a menu since its part of the system. -->
		<ul class="nav navbar-nav navbar-right">
			<?php
      if ($userid) {
      ?>
			<li class="dropdown">
				<a href='#' class="dropdown-toggle" data-toggle="dropdown"><?php echo $user; ?> <b class='caret'></b></a>
				<ul class="dropdown-menu">
					<!-- <li><a href='<?php echo $site_address; ?>/profile.php?u=<?php echo $user; ?>'>Profile</a></li> -->
          <li><a href='<?php echo $site_address; ?>/usersettings.php'>User Control Panel</a></li>
          <li class="divider"></li>
          <li><a href='<?php echo $site_address; ?>/logout.php'>Logout</a></li>
				</ul>
			<li>
<?php
if ($zw->grid->isAdmin($userid)) {
?>
      <li class="dropdown">
        <a href='#' class="dropdown-toggle" data-toggle="dropdown">Admin <b class='caret'></b></a>
        <ul class="dropdown-menu">
          <li><a href='<?php echo $site_address; ?>/admin/settings.php'>Settings</a></li>
          <li><a href='<?php echo $site_address; ?>/admin/menubar.php'>Menu Bar</a></li>
          <li><a href='<?php echo $site_address; ?>/admin/news.php'>News</a></li>
          <li><a href='<?php echo $site_address; ?>/admin/users.php'>Users</a></li>
        </ul>
      </li>
<?php
}else{ // if user is admin
}
}else{ // if user is logged in
?>
			<li><a href='<?php echo $site_address; ?>/login.php?lp=<?php echo $thispage; ?>'><i class="fa fa-lock"></i> Member Login</a></li>
			<?php } ?>
		</ul>
    </div>
  </div>
</nav>
