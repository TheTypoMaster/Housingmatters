<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Setup
</div>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li class="active" ><a href="master_sm_wing" > Wing</a></li>
<li><a href="flat_type" >Flat Type</a></li>
<li ><a href="master_sm_flat" >Flat Number</a></li>
<li ><a href="flat_nu_import" >Flat Number Import</a></li>
<li><a href="society_details" >Society Details</a></li>
<li><a href="society_settings" >Society Settings</a></li>
</ul>
    <div class="tab-content" style="min-height:300px;">
    <div class="tab-pane active" id="tab_1_1">
    
    <div align="center">
    <div id="ser_top" align="center" ><?php echo @$rr; ?></div>
    <br>
    <div>
    <form  class="form-horizontal" method="post" id="contact-form">
    <table>
    <tr>
      <label >Wing Name <span style="font-size:12px; color:#999;">(Maximum 10 characters.)</span></label>
    <td valign="top">
    <input type="text" class="m-wrap" name="wing_name" maxlength="10"></td>
    <td valign="top"> <input type="submit" class="btn blue" value="Submit"  name="sub"></td>
    </tr>
    </table>
    </form>
     </div>
    </div>
    </div>
   <br>
    <div class="portlet box" style="width:80%;">

<div class="portlet-body" style="float:right; width:70%;">
<table class="table table-striped table-bordered" id="sample_2" style="width:100%;">
<thead>
<tr>
<th>Sr No.</th>
<th>Wing-Name</th>

</tr>
</thead>
<tbody>

	<?php
      $q=0;
						
						foreach ($user_wing as $collection) 
						{
							$q++;
						$wing_name=$collection['wing']['wing_name'];
 
	?>
   
<tr class="odd gradeX" >
<td><?php echo $q; ?></td>
<td><?php echo $wing_name; ?></td></tr>
<?php
						}
  
    ?>
</tbody>
</table>
</div>
</div>
    
  
    </div>
   
    </div>
	
	
   <script>
$(document).ready(function(){
		$('#contact-form').validate({
	    rules: {
	      wing_name: {
	       
	        required: true,
			maxlength: 10,
			remote:"master_sm_wing_ajax"
			
	      }		  
	    },
		 messages: {
	                wing_name: {
	                    maxlength: "Please Maximum 10 characters.",
						 remote: "Wing Name is Already Exists."
						 
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
	