<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Details
</div>
<?php


foreach($result_society as $data)
{
	$society_pan=$data['society']['pan'];
	$tex_number=$data['society']['tex_number'];
	$society_address=$data['society']['society_address'];
	$society_reg_num=$data['society']['society_reg_num'];

}



?>				 
<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li  ><a href="master_sm_wing" > Wing</a></li>
<li><a href="flat_type" >Flat Type</a></li>
<li ><a href="master_sm_flat" >Flat Number</a></li>
<li ><a href="flat_nu_import" >Flat Number Import</a></li>
<li class="active" ><a href="society_details" >Society Details</a></li>
<li><a href="society_settings" >Society Settings</a></li>
</ul>
<div class="tab-content" style="min-height:300px;">
<div class="tab-pane active" id="tab_1_1">
			
<div class="portlet-body">
<form method="post" id='contact-form'>
<center>
<table>
<tr>
<td>
<div class="control-group">
<label> Society PAN #  </label>
	  	<div class="controls">
        	<div>
			<input type="text" maxlength="10"   class="m-wrap" style="font-size:16px;"  name="pan" value='<?php echo $society_pan ; ?>'>
           </div>
		</div>
	  </div>
</td>
</tr>
<tr>
<td>
<div class="control-group">
<label> Society Service Tax Number </label>
	  	<div class="controls">
        	<div>
			<input type="text"    class="m-wrap" style="font-size:16px;"  name="s_tax" value='<?php echo $tex_number ; ?>'>
           </div>
		</div>
	  </div>
</td>
</tr>
<tr>
<td>
<div class="control-group">
<label> Society Registrations Number </label>
	  	<div class="controls">
        	<div>
			<input type="text"   class="m-wrap" style="font-size:16px;"  name="s_number" value='<?php echo $society_reg_num ; ?>'>
           </div>
		</div>
	  </div>
</td>
</tr>

<tr>
<td>
<div class="control-group">
<label> Society Address </label>
	  	<div class="controls">
        	<div>
			<textarea rows='5' cols='5' style='resize:none;' name='address'><?php echo $society_address ; ?></textarea>
           </div>
		</div>
	  </div>
</td>
</tr>
<tr>
<td>
<button type="submit" class="btn blue" >Update </button>
</td>
</tr>
</table>
</center>

</form>
</div>
</div>
</div>
</div>




<script>
$(document).ready(function(){
 $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
    }, "Only enter alphanumeric letters.");


		$('#contact-form').validate({
	    rules: {
	      pan: {
	       
	        required: true,
			loginRegex : true 
	      },
		 
		 
		  
		  s_tax:
        {
            required: true,

        },
		  
	      s_number: {
	        required: true,
	      },
		  mobile: {
	       
	        required: true,
			number: true,
			minlength: 10,
			maxlength: 10,
			remote: "signup_mobileexit"
	      }
	    },
		messages: {
	                email: {
	                    remote: "Login-Id is Already Exist."
	                },
					 mobile: {
	                    remote: "Mobile-No is Already Exist."
	                }
	            },
		
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });
	  
}); 

</script>