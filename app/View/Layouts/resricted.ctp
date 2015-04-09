<!DOCTYPE html>
<html lang="en">
<head>
<title>HousingMatters</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!-- Include external files and scripts here (See HTML helper for more info.) -->
<?php
echo $this->fetch('meta');
?>
<link href="/cakephp/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/cakephp/assets/css/metro.css" rel="stylesheet" />
	<link href="/cakephp/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="/cakephp/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="/cakephp/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
	<link href="/cakephp/assets/css/style.css" rel="stylesheet" />
	<link href="/cakephp/assets/css/style_responsive.css" rel="stylesheet" />
	<link href="/cakephp/assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link rel="stylesheet" type="text/css" href="/cakephp/assets/uniform/css/uniform.default.css" />
	    <link href="/cakephp/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
     <link rel="stylesheet" type="text/css" href="/cakephp/assets/gritter/css/jquery.gritter.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/jquery-tags-input/jquery.tagsinput.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/clockface/css/clockface.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/bootstrap-datepicker/css/datepicker.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/bootstrap-timepicker/compiled/timepicker.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/bootstrap-colorpicker/css/colorpicker.css" />
   <link rel="stylesheet" href="/cakephp/assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
   <link rel="stylesheet" href="/cakephp/assets/data-tables/DT_bootstrap.css" />
   <link rel="stylesheet" type="text/css" href="/cakephp/assets/bootstrap-daterangepicker/daterangepicker.css" />
   <link href="/cakephp/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
	<link href="/cakephp/assets/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/cakephp/as/bootstrap.min.css" rel="stylesheet">
<link href="/cakephp/as/animate.css" rel="stylesheet" />
<link href="/cakephp/as/demo-styles.css" rel="stylesheet" />
		<style>
		label.valid {
		  width: 24px;
		  height: 0px;
		  background: url(as/img/valid.png) center center no-repeat;
		  text-indent: -9999px;
		  position:fixed;
		}
		label.error {
			/*font-weight: bold;*/
			color: red;
			padding: 2px 8px;
			margin-top: 10px;
			width:200px;
		}
		</style>
		<style media="print">
		.printt {
		display:none !important;
		  }
		</style>

<!-----js--------------->
<script src="/cakephp/assets/js/jquery-1.8.3.min.js"></script>			
	<script src="/cakephp/assets/breakpoints/breakpoints.js"></script>			
	<script src="/cakephp/assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>	
	<script src="/cakephp/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="/cakephp/assets/js/jquery.blockui.js"></script>
	<script src="/cakephp/assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>	
	<script type="text/javascript" src="/cakephp/assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="/cakephp/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script src="/cakephp/assets/flot/jquery.flot.js"></script>
	<script src="/cakephp/assets/flot/jquery.flot.resize.js"></script>
	<script src="/cakephp/assets/flot/jquery.flot.pie.js"></script>
	<script src="/cakephp/assets/flot/jquery.flot.stack.js"></script>
	<script src="/cakephp/assets/flot/jquery.flot.crosshair.js"></script>
	   <script type="text/javascript" src="/cakephp/assets/ckeditor/ckeditor.js"></script>  
   <script type="text/javascript" src="/cakephp/assets/bootstrap/js/bootstrap-fileupload.js"></script>
     <script type="text/javascript" src="/cakephp/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
   <script type="text/javascript" src="/cakephp/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
   <script type="text/javascript" src="/cakephp/assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
   <script type="text/javascript" src="/cakephp/assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
   <script type="text/javascript" src="/cakephp/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="/cakephp/assets/clockface/js/clockface.js"></script>
   <script type="text/javascript" src="/cakephp/assets/bootstrap-daterangepicker/date.js"></script>
   <script type="text/javascript" src="/cakephp/assets/bootstrap-daterangepicker/daterangepicker.js"></script> 
   <script type="text/javascript" src="/cakephp/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>  
   <script type="text/javascript" src="/cakephp/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
     <script src="/cakephp/assets/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
   	<script src="/cakephp/assets/fancybox/source/jquery.fancybox.pack.js"></script>
		<script src="/cakephp/assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
			<script src="/cakephp/assets/js/jquery.cookie.js"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>	
	<script src="/cakephp/assets/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
	<script src="/cakephp/assets/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>	
		<script type="text/javascript" src="/cakephp/assets/gritter/js/jquery.gritter.js"></script>
	<script type="text/javascript" src="/cakephp/assets/js/jquery.pulsate.min.js"></script>	
	  <script src="/cakephp/assets/uniform/jquery.uniform.min.js"></script> 
 	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script src="/cakephp/assets/js/gmaps.js"></script>
	<script src="/cakephp/assets/js/demo.gmaps.js"></script>
		<script type="text/javascript" src="/cakephp/assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="/cakephp/assets/data-tables/DT_bootstrap.js"></script>
		<script src="/cakephp/assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage('calendar');
			App.init();
		});
	</script>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-37564768-1']);
	  _gaq.push(['_setDomainName', 'keenthemes.com']);
	  _gaq.push(['_setAllowLinker', true]);
	  _gaq.push(['_trackPageview']);
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
<script src="/cakephp/as/js/jquery.validate.min.js"></script>
<!------------JS-------------------->
</head>
<body class="fixed-top" style="background-color:white !important;">
<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="dashboard" style="margin-top:-9px;">
				<img src="/cakephp/as/hm/hm-logo.png" alt="logo" height="16px" width="120px"/>
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->				
				<!-- BEGIN TOP NAVIGATION MENU -->					
				<ul class="nav pull-right">
					
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="/cakephp/assets/img/avatar1_small.jpg" />
						<span class="username">Username</span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="#"><i class="icon-user"></i> My Profile</a></li>
							<li><a href="#"><i class="icon-calendar"></i> My Calendar</a></li>
							<li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>
							<li class="divider"></li>
							<li><a href="logout"><i class="icon-key"></i> Log Out</a></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU -->	
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->


	<!-- BEGIN FOOTER -->
	<div class="footer" style="background-color: #1f1f1f !important;">
		HousingMatters
		<div class="span pull-right">
			<span class="go-top"><i class="icon-angle-up"></i></span>
		</div>
	</div>
	<!-- END FOOTER -->


</body>
</html>