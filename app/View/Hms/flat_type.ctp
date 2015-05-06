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
<li><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Configuration</a></li>
<li ><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab'>Society Details</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
</ul>
<div class="tab-content" style="min-height:300px;">
<div class="tab-pane active" id="succ">
<div id="error_msg"></div>   
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>   
	  
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">      
<div class="row-fluid">
<div class="span5">    
<form  method="post">
   
<label style="font-size:14px;">Select Wing</label>   
<div class="controls">
<select name="wing" id="tp" class="m-wrap span7">
<option value="">Select</option>
<?php
foreach($cursor2 as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];	 
$wing_name = $collection['wing']['wing_name'];		 
?>
<option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
<?php } ?>
</select> 
<label report="wing" class="remove_report"></label>  
</div>
<br />   


<label style="font-size:14px;">Flat Number</label>
<div class="controls">
<input type="text" class="m-wrap span7" maxlength="10" id="nu">
<label report="num" class="remove_report"></label>
</div>
<br />
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>
</form>
</div>
<div class="span7">

<div style="height:350px;">
<table class="table table-striped table-bordered" style="overflow:Y-scroll;">
<tr>
<th style="text-align:center;">Sr #</th>
<th style="text-align:center;">Wing Name</th>
<th style="text-align:center;">Flat Number</th>
</tr>
<?php
$c=0;
foreach($cursor1 as $collection)
{
$c++;
$wing_id = (int)$collection['flat']['wing_id'];	
$flat_number = $collection['flat']['flat_name'];	

$result_prb = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_fetch'),array('pass'=>array($wing_id)));
foreach ($result_prb as $collection) 
{
$wing_name = $collection['wing']['wing_name'];	
}

?>
<tr>
<td style="text-align:center;"><?php echo $c; ?></td>
<td style="text-align:center;"><?php echo $wing_name; ?></td>
<td style="text-align:center;"><?php echo $flat_number; ?></td>
</tr>
<?php } ?>
</table>
</div>
</div>
</div>
</div>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
</div>
</div>
</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
 
 <script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		
		var m_data = new FormData();
		m_data.append( 'wing', $('#tp').val());
		m_data.append( 'number', $('#nu').val());	
		
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "flat_type_validation",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType:'json',
			}).done(function(response) {
				if(response.report_type=='error'){
					$(".remove_report").html('');
						jQuery.each(response.report, function(i, val) {
						$("label[report="+val.label+"]").html('<span style="color:red;">'+val.text+'</span>');
					});
				}
				if(response.report_type=='publish'){
                $("#shwd").show()
				$(".success_report").show().html(response.report);	
				}
			
			$("html, body").animate({
			scrollTop:0
			},"slow");
			$(".form_post").removeClass("disabled");
			$("#wait").hide();
			});

	});
});
</script>		

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>



<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Flat Type</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Hms/flat_type" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 	








	    
        