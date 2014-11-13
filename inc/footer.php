<?php
if (!$api) {
?>
<footer>
  <div class="row">
  	<div class="col-md-3">
      <div class='visible-md visible-lg'>
        QR Code to view this site on your smartphone and/or tablet<br>
        <img src='<?php echo $site_address; ?>/img/opensimadsqr.png' class='img-responsive'>
      </div>
    </div>
  	<div class="col-md-3">
  	</div>
  	<div class="col-md-3">
  	</div>
    <div class="col-md-3">
    </div>
  </div>
</footer>

</div> <!-- Ends the container div found in header.php -->
<script type="text/JavaScript">
$(document).ready(function(){
	$('.dropdown-toggle').dropdown();
	$('#tooltip').tooltip('hide');
	$(".accordion").collapse('toggle');
	$('.collapse').collapse('toggle');
	$('#modal').modal('toggle');
	$('.carousel').carousel({interval: 10000});
	$('#tabs a:first').tab('show');
  $('textarea.editor').ckeditor();
});
</script>
<script type="text/javascript" src="<?php echo $site_address; ?>/js/bootstrap.js"></script>
<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
var disqus_shortname = '<?php echo $zw->config['DisqusShortName']; ?>'; // required: replace example with your forum shortname

/* * * DON'T EDIT BELOW THIS LINE * * */
(function () {
    var s = document.createElement('script'); s.async = true;
    s.type = 'text/javascript';
    s.src = '//' + disqus_shortname + '.disqus.com/count.js';
    (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
}());
</script>
</body>
</html>
<?php
}
?>