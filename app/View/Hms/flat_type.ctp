<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Setup
</div>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li><a href="<?php echo $webroot_path; ?>Hms/master_sm_wing" rel='tab'> Wing</a></li>
<li class="active"><a href="<?php echo $webroot_path; ?>Hms/flat_type" rel='tab'>Flat Type</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Number</a></li>
<li ><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab'>Society Details</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
</ul>
<div class="tab-content" style="min-height:300px;">
<div class="tab-pane active" id="succ">
<div id="error_msg"></div>   
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>   
	  <div align="center">
      <div id="ser_top" align="center" ><?php echo @$rr; ?></div>
     <br>
     <div>
     <div id="succ"></div>
     <?php
	if($nnn == 5)
	{
	?>
    <form  class="form-horizontal" method="post" id="contact-form" onsubmit="return validate()">
    <input type="hidden" value="<?php echo @$fl_ti; ?>" id="fl_ti" />
     <input type="hidden" value="<?php echo @$b; ?>" id="b" />
    <table>
    <tr>
    <td style="text-align:center;">
     <label >Wing</label>
     <select name="wing" id="tp" class="m-wrap medium">
     <option value="">Select</option>
     <?php
     foreach($cursor2 as $collection)
	 {
	 $wing_id = (int)$collection['wing']['wing_id'];	 
     $wing_name = $collection['wing']['wing_name'];		 
     ?>
     <!-- <input type="text" class="m-wrap" name="flat_type" maxlength="10" id="tp">-->
     <option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
     <?php } ?>
     </td>
   

<td style="text-align:center;">
<label >Flat Number</span></label>
<input type="text" class="m-wrap medium" name="number" maxlength="10" id="nu"></td>

<td style="text-align:right;"><button type="submit" class="btn blue" name="journal_add" id="submit" style="margin-top:25px;">Submit</button></td>
</tr>
<tr>
<td>
<div id="vali"></div>
<label id="tp"></label></td>
<td><label id="nu"></label></td>
<td></td>
</tr>
</table>
<div id="valid"></div>
</form>
 </div>   
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    

  
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
    
<?php } ?>
</div>
</div>
</div>
</div>
</div>
	
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
 
	
<script>
$(document).ready(function() { 
$('form').submit( function(ev){
	
ev.preventDefault();
$("#submit").addClass("disabled").text("submiting...");

var ar = [];
var wing = $("#tp").val();
var flat = $("#nu").val();

ar.push([wing,flat]);
var myJsonString = JSON.stringify(ar);



$.ajax({
url: "flat_type_validation?q="+myJsonString,
dataType:'json',
}).done(function(response) {

if(response.type == 'error'){  
output = '<div class="alert alert-error">'+response.text+'</div>';
$("#submit").removeClass("disabled").text("submit");
$("html, body").animate({
scrollTop:0
},"slow");
}
if(response.type=='succ'){
$("#succ").html('<div class="alert alert-block alert-success fade in"><h4 class="alert-heading">Success!</h4><p>Record Inserted Successfully</p><p><a class="btn green" href="<?php echo $webroot_path; ?>Hms/flat_type" rel="tab">OK</a></p></div>');
}

$("#error_msg").html(output);
});


});
});

</script>   













	    
        