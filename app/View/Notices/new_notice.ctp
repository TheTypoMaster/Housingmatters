<?php
$this->Html->script('/TinyMCE/js/tiny_mce/tiny_mce.js', array(
    'inline' => false
));
$this->Html->script('/yourapp/TinyMCE/js/tiny_mce/tiny_mce.js', array(
    'inline' => false
));
$this->TinyMCE->editor(array('theme' => 'advanced', 'mode' => 'textareas'));
?>
<div class="portlet box red">
	 <div class="portlet-title">
		<h4>Time Pickers</h4>
	 </div>
	 <div class="portlet-body form">
		<!-- BEGIN FORM-->
		<form id="contact-form" method="POST" class="form-horizontal" enctype="multipart/form-data">
		   <div class="control-group">
			  <label class="control-label">WYSIWYG Editor</label>
			  <div class="controls">
				 <textarea class="span12 wysihtml5 m-wrap" rows="6" name="notice" ></textarea>
				 
			  </div>
		   </div>
		   <input type="submit" value="Post" name="post" />
		</form>
		<!-- END FORM-->  
	 </div>
  </div>
<script>
$(document).ready(function() {
$(".wysihtml5-sandbox").on('keyup',function(e){
e.preventDefault();
alert();
});
});
</script>