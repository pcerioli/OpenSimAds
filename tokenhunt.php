<?php
$page_title = "Home";
$hide_sidebars = "true";
define('ZW_IN_SYSTEM', true);
require_once('inc/header.php');
$order = $zw->Security->make_safe($_GET['order']);
if (!$order) {
	$order = "DESC";
}
?>
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Token Hunt</h3>
  </div>
  <div class="panel-body">
Token Hunt is designed to help store/mall owners to bring traffic to their place of business and to give other avatars a way to make some in world money.<br>
Buy the Token Hunt pack from a OpensimAds location, rez out afew tokens around your place of business then add some money to those tokens.<br>
The money in those tokens goes towards paying your visitors.<br>
A Token Hunter can buy the Token Hunt HUD from any of our locations and use it to find rezzed tokens.<br>
When a hunter finds out, they can touch it and wait for a set time before claiming that amount it pays.<br>
Example: Store Owner rezzes out a 0.01 token and puts 10 in world money into it, the Token Hunter sees the token and touches it,
 they then wait 30 seconds and then a dialog box will pop up to make sure they are human,
 if they press the correct number button according to the number shown in the dialog box they get 0.01.<br>
That person then can go cash out if they have earned at least 1 token at one of our ATM's.
  </div>
</div>
<span class="pull-left">
<a href='<?php echo $site_address; ?>/tokenhunt.php?order=ASC' class='btn btn-lg <?php if ($order == "ASC") { echo "btn-default active disabled"; }else{ echo "btn-primary"; } ?>'><i class="fa fa-sort-alpha-asc"></i></a>
<a href='<?php echo $site_address; ?>/tokenhunt.php?order=DESC' class='btn btn-lg <?php if ($order == "DESC") { echo "btn-default active disabled"; }else{ echo "btn-primary"; } ?>'><i class="fa fa-sort-alpha-desc"></i></a>
</span>
<div class='table-responsive'>
	<table class='table table-striped table-bordered table-hover table-condensed'>
		<thead>
			<tr>
				<th>Rank</th>
				<th>Grid</th>
				<th>Payout</th>
				<th>Token Owner</th>
				<th>Location</th>
				<th class='visible-md visible-lg'>TP</th>
				<th>Claimed</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$tokenhuntq = $zw->SQL->query("SELECT * FROM tokens ORDER BY `tokenworth` DESC, `claims` DESC LIMIT 0,100");
		$tokenhunti = 1;
		while ($tokenhuntr = $zw->SQL->fetch_array($tokenhuntq)) {
			$grid = $tokenhuntr['grid'];
			$ownername = $tokenhuntr['ownername'];
			$sim = $tokenhuntr['sim'];
			$parcel = $tokenhuntr['parcel'];
			$pos = $tokenhuntr['pos'];
			$tokenworth = $tokenhuntr['tokenworth'];
			$claims = $tokenhuntr['claims'];
			$loc = str_replace(", ", "/", $pos);
			if ($grid == "InWorldz") {
				$tp = "<a href='inworldz://".$sim."/".$loc."/' class='bt btn-sm btn-info'>InWorldz Viewer</a> <a href='secondlife://".$sim."/".$loc."/' class='bt btn-sm btn-danger'>Other Viewers</a>";
				$currency = "I'z";
			}else{
				$tp = "<a href='secondlife://".$sim."/".$loc."/' class='bt btn-sm btn-danger'>Teleport</a>";
				$currency = "OS$";
			}
			$tokenordering[$tokenhunti] = "
			<tr>
				<td>".$tokenhunti."</td>
				<td>".$grid."</td>
				<td>".$currency." ".$tokenworth."</td>
				<td>".$ownername."</td>
				<td>".$parcel." @ ".$sim."</td>
				<td class='visible-md visible-lg'>".$tp."</td>
				<td>".$claims."</td>
			</tr>
			";
			$tokenhunti++;
		}
		if ($order == "ASC") {
			ksort($tokenordering);
		}else if ($order == "DESC") {
			krsort($tokenordering);
		}
		foreach ($tokenordering as $key => $val) {
		    echo $val;
		}
		?>
		</tbody>
	</table>
</div>
<?php
include ('inc/footer.php');
?>