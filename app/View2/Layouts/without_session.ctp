
<!DOCTYPE html>
<html lang="en">
<head>
<title>HousingMatters</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!-- Include external files and scripts here (See HTML helper for more info.) -->
<?php
echo $this->fetch('meta');
?>
<link href="<?php echo $this->webroot ; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo $this->webroot ; ?>/assets/css/metro.css" rel="stylesheet" />
  <link href="<?php echo $this->webroot ; ?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="<?php echo $this->webroot ; ?>/assets/css/style.css" rel="stylesheet" />
  <link href="<?php echo $this->webroot ; ?>/assets/css/style_responsive.css" rel="stylesheet" />
  <link href="<?php echo $this->webroot ; ?>/assets/css/style_default.css" rel="stylesheet" id="style_color" />
  <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ; ?>/assets/uniform/css/uniform.default.css" />
   <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ; ?>/assets/chosen-bootstrap/chosen/chosen.css" />
     <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ; ?>/assets/chosen-bootstrap/chosen/chosen.css" />
     <link rel="shortcut icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<link href="<?php echo $this->webroot ; ?>/as/bootstrap.min.css" rel="stylesheet">
		<style>
		label.valid {
		  width: 24px;
		  height: 0px;
		  background: url(as/img/valid.png) center center no-repeat;
		  text-indent: -9999px;
		}
		label.error {
			/*font-weight: bold;*/
			color: red;
			padding: 2px 8px;
			margin-top: 2px;
		}
		</style>
 <script src="<?php echo $this->webroot ; ?>/as/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo $this->webroot ; ?>/as/js/jquery.validate.min.js"></script>
</head>
<body class="login">


<!-- Here's where I want my views to be displayed-->
<?php echo $this->fetch('content'); ?>




<!-----------js----------------->

<!-----js--------------->
<script src="<?php echo $this->webroot ; ?>/assets/js/jquery-1.8.3.min.js"></script>			
	<script src="<?php echo $this->webroot ; ?>/assets/breakpoints/breakpoints.js"></script>			
	<script src="<?php echo $this->webroot ; ?>/assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>	
	<script src="<?php echo $this->webroot ; ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	  <script src="<?php echo $this->webroot ; ?>/assets/uniform/jquery.uniform.min.js"></script> 
	<script src="<?php echo $this->webroot ; ?>/assets/js/jquery.blockui.js"></script>
	
	
	<script type="text/javascript" src="<?php echo $this->webroot ; ?>/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	
	
	
		<script src="<?php echo $this->webroot ; ?>/assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage('calendar');
			App.init();
		});
	</script>
	
<script src="<?php echo $this->webroot ; ?>/as/js/jquery.validate.min.js"></script>
</body>
</html>