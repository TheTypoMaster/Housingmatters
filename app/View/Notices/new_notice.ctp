<div class="portlet box" style="1px solid #8E8E8E">
	 <div class="portlet-title" style="color:Black;">
		<h4>Time Pickers</h4>
	 </div>
	 <div class="portlet-body form">
		<!-- BEGIN FORM-->
		<form id="contact-form" method="POST" class="form-horizontal" enctype="multipart/form-data">
			<div id="summernote"></div>
			<input type="submit" value="Post" name="post" />
		</form>
		<!-- END FORM-->  
	 </div>
  </div>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
<link href="<?php echo $webroot_path ; ?>summernote.css" rel="stylesheet">
<script src="<?php echo $webroot_path ; ?>summernote.min.js"></script>

<script>
$(document).ready(function() {
$('#summernote').summernote();
});
</script>