<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Setup
</div>


<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li class="active" ><a href="<?php echo @$webroot_path; ?>Hms/master_sm_wing" rel='tab'> Wing</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/flat_type" rel='tab'>Flat Type</a></li>
<li ><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Number</a></li>
<li ><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab'>Society Details</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
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
<td style="text-align:center;">
<label >Wing Name <span style="font-size:12px; color:#999;">(Maximum 10 characters.)</span></label>
<input type="text" class="m-wrap" name="wing_name" maxlength="10" id="wing"></td>
<!--<td style="text-align:center;">
<label>Number of Flat</label>
<input type="text" name="flat_no" id="flat_no" class="m-wrap medium" style="margin-top:0.5%;" />
</td>-->
<td><button  type="submit" class="btn blue" value="Submit"  id="go5" style="margin-top:36%;" name="sub">Submit</button></td>
</tr>
<tr>
<td><label id="wing"></label></td>
<td></td>
</tr>
</table>
</form>
</div>
</div>
</div>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>   



<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>   
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
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
	
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>	

<script>
$(document).ready(function() {
$("#go").live('click',function(){

var wing = document.getElementById("wing").value;
var no_flat = document.getElementById("flat_no").value;

if(wing=== '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Fill All     Fields</div>'); return false; }



document.getElementById("nu").value= no_flat;
document.getElementById("wi").value= wing;
$(".edit_div").show();
$("#show").empty();
for(var h=1; h<=no_flat; h++)
{
$("#show").append('<tr><td><input type="text" class="m-wrap small" name="no' + h + '"></td></tr>');
}

});
});
</script>	

<script>
$(document).ready(function() {
	$("#close_edit").live('click',function(){
$(".edit_div").hide();
});
});
</script>	



<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<form method="post">
<div class="edit_div" style="display:none;">
<div class="modal-backdrop fade in"></div>
<div class="modal" id="poll_edit_content">

<div class="modal-header">
<center>
	<h4 id="myModalLabel1"><b>Flat Number</b></h4>
</center>
</div>
<div class="modal-body">
<center>
<input type="hidden" value="" name="nu" id="nu"/>
<input type="hidden" value="" name="wi" id="wi"/>
<table id="show">	
</table>		   
</center>					   
</div>
<div class="modal-footer">
<button class="btn" id="close_edit">Close</button>
<button type="submit" name="suxasdS" class="btn blue">Save</button>
</div>
</div>
</div>
</form>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

 <script>
$(document).ready(function(){

$.validator.setDefaults({ ignore: ":hidden:not(select)" });


		$('#contact-form').validate({
		
		errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    },
		
		
		
	    rules: {
	     
		 wing_name: {
			 required: true
			
			 
	      },

           

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





