<form  method="post" id="contact-form">
<div class="portlet box blue span9">
	<div class="portlet-title">
		<h4><i class=" icon-tasks"></i>Create Flash Message</h4>
	</div>
	
	
								
	<div class="portlet-body">
		<div class="alert alert-success" style="display:none;" id="success">
			<button class="close" data-dismiss="alert"></button>
			<strong>Success!</strong> The page has been added.
		</div>
	
	
		<div class="row-fluid">
			<div class="control-group span6">
			  <label class="control-label">Title</label>
			  <div class="controls">
				<input type="text" class="span12 m-wrap" name="title" id="title" style="font-size:16px;" placeholder="Title*">
				<label id="title"></label>
			  </div>
			</div>
			<div class="control-group span6">
			  <label class="control-label">Select Theme</label>
			  <div class="controls">
				<select class="span6 m-wrap" data-placeholder="Select Theme" tabindex="1" name="theme" id="theme">
					<option value="">Select...</option>
					<option value="flash_blue">flash_blue</option>
					<option value="flash_red">flash_red</option>
				</select>
				<label id="theme"></label>
			  </div>
			</div>
		
		</div>
		<div class="control-group">
		  <label class="control-label">Description</label>
		  <div class="controls">
			<textarea class="span9 m-wrap" rows="3" placeholder="Description*" name="description" id="description"></textarea>
			<label id="description"></label>
		  </div>
		</div>
		
	<span style="font-size:14px;color:#878585;">preview</span>
	<div id="preview">
		<span>Please fill above fields.</span>
	</div>
	
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
		
		if(title=="" || theme==""){
			$("#preview").html("<span>Please fill above fields.</span>");
		}else{
			$("#preview").html('<div class='+theme+'><button type="button" class="close" ></button><span class="title">'+title+'</span><br><span class="description">'+description+'</span></div>');
		}
	});
}


$(document).ready(function() {
	$("input[name=title],textarea[name=description]").bind('keyup',function(){
		preview();
	});
	$("select[name=theme]").bind('change',function(){
		preview();
	});
});
</script>

<script>
$.validator.addMethod('requirecheck1', function (value, element) {
	 return $('.requirecheck1:checked').size() > 0;
}, 'Please check at least one role.');

$.validator.addMethod('requirecheck2', function (value, element) {
	 return $('.requirecheck2:checked').size() > 0;
}, 'Please check at least one wing.');

$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param) 
});

$(document).ready(function(){
			var checkboxes = $('.requirecheck1');
			var checkbox_names = $.map(checkboxes, function(e, i) {
				return $(e).attr("name")
			}).join(" ");
			
			
			var checkboxes2 = $('.requirecheck2');
			var checkbox_names2 = $.map(checkboxes2, function(e, i) {
				return $(e).attr("name")
			}).join(" ");
			
			
			
	
		$('#contact-form').validate({
		
		 errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    }, 
	    groups: {
            asdfg: checkbox_names,
			qwerty: checkbox_names2
        },
		
		
		rules: {
	      title: {
			required: true,
			maxlength: 50
	      },
		  description: {
			required: true,
			maxlength: 200
	      },
		  theme: {
			required: true,
	      },
		  
		 
	    },
		
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
				
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			},
			
			submitHandler: function(form) {
				alert('inside');
				var m_data = new FormData();
				m_data.append( 'title', $('#title').val());
				m_data.append( 'description', $('#description').val());
				m_data.append( 'theme', $('#theme').val());
				$.ajax({
						url: "submit_flash_message",
						data: m_data,
						processData: false,
						contentType: false,
						type: 'POST',
					}).done(function(response) {
						$("#success").show();
					});
				return false;
          }
		
	  });

}); 
</script>

