<form method="post">
<div class="portlet box blue span9">
	<div class="portlet-title">
		<h4><i class=" icon-tasks"></i>Create Flash Message</h4>
	</div>
	
	<div class="portlet-body">
		<div class="row-fluid">
			<div class="control-group span6">
			  <label class="control-label">Title</label>
			  <div class="controls">
				<input type="text" class="span12 m-wrap" name="title" style="font-size:16px;" placeholder="Title*">
			  </div>
			</div>
			<div class="control-group span6">
			  <label class="control-label">Select Theme</label>
			  <div class="controls">
				<select class="span6 m-wrap" data-placeholder="Select Theme" tabindex="1" name="theme">
					<option value="">Select...</option>
					<option value="success">Success</option>
					<option value="categor">Info</option>
					<option value="warning">Warning</option>
					<option value="error">Error</option>
				</select>
			  </div>
			</div>
		
		</div>
		<div class="control-group">
		  <label class="control-label">Description</label>
		  <div class="controls">
			<textarea class="span6 m-wrap" rows="3" placeholder="Description*" name="description"></textarea>
		  </div>
		</div>
		
	preview<hr/>
	hello
	
	<hr/>
	<button type="submit" class="btn blue" name="set_flash">Set Flash</button>
	</div>
	
	
	
</div>
</form>

<script>
function preview(){
	$(document).ready(function() {
		var title=$("input[name=title]").val();
		var description=$("textarea[name=description]").val();
		var theme=$("select[name=theme]").val();
		alert(title);
		alert(description);
		alert(theme);
	});
}


$(document).ready(function() {
	$("input[name=title]").bind('click',function(){
		preview();
	});
});
</script>