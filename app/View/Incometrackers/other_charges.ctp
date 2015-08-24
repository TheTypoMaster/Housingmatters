<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<table  align="center" border="1" bordercolor="#FFFFFF" cellpadding="0">
<tr>
<td><a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn " rel='tab'>Selection of Income Heads</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_rate_card" class="btn" style="font-size:16px;" rel='tab'>Rate Card</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn" style="font-size:16px;" rel='tab'>Non Occupancy Charges</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_penalty" class="btn" style="font-size:16px;" rel='tab'>Penalty Option</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/neft_add" class="btn" style="font-size:16px;" rel='tab'>Add NEFT</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_setup" class="btn" style="font-size:16px;" rel='tab'>Remarks</a>
</td>
<td><a href="<?php echo $webroot_path; ?>Incometrackers/other_charges" class="btn yellow" rel='tab'>Other Charges</a>
</td>
</tr>
</table>


<style>
label.control-label{
	color: purple;
font-weight: bold;
}
</style>
	<!-- BEGIN VALIDATION STATES-->
	<div class="portlet box purple">
	 <div class="portlet-title">
		<h4><i class="icon-briefcase" style="font-size:16px;"></i> OTHER CHARGES</h4>
	 </div>
	 <div class="portlet-body form">
		<!-- BEGIN FORM-->
		<form METHOD="POST" class="form-horizontal" id="contact-form">
		   <div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Select Income Head</label>
						<div class="controls">
							<select name="income_head"  id="income_head" class="span12 chosen" data-placeholder="Choose a Category" tabindex="1">
								<option value="">
								<?php
								foreach($result_ledger_account as $data){
									$ledger_account_auto_id = (int)$data["ledger_account"]["auto_id"];
									$ledger_name = $data["ledger_account"]["ledger_name"];
									?>
								<option value="<?php echo $ledger_account_auto_id; ?>"><?php echo $ledger_name; ?>
								<?php } ?>
							 </select>
							  <label id="income_head"></label>
						</div>
						
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						  <label class="control-label">Enter Amount</label>
						  <div class="controls">
							 <input name="amount" class="span8 m-wrap" type="text" placeholder="Amount" id="amount">
							 <label id="amount"></label>
						  </div>
					</div>
				</div>
		   </div>
			<div class="row-fluid">
				<div class="span7">
					<div class="control-group">
						  <label class="control-label">Select Flats</label>
						  <div class="controls">
							<select name="flats[]" data-placeholder="Your Favorite Football Teams" id="flats" class="chosen span12" multiple="multiple" tabindex="6">
								<option value="">
								<?php foreach($result_user as $user_data){ 
								$user_id=(int)$user_data["user"]["user_id"];
								$user_name=$user_data["user"]["user_name"];
								$wing=$user_data["user"]["wing"];
								$flat=$user_data["user"]["flat"];
								
								$wing_flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing,$flat))); 
								?>
								<option  value="<?php echo $flat; ?>"><?php echo $user_name.' '.$wing_flat; ?>
								<?php } ?>
							</select>
							 <label id="flats"></label>
						  </div>
					</div>
				</div>
				<div class="span5"><button type="submit" name="add_charges" class="btn purple"><i class=" icon-plus-sign"></i> Add charge for selected flats</button></div>
			</div>
			</form>
			
<<<<<<< HEAD
			
			<hr>
			
			<table class="table table-striped table-bordered table-advance">
				<tbody>
				<?php  $sr_no=0; foreach($result_user as $user_data){ $sr_no++;
					$user_id=(int)$user_data["user"]["user_id"];
					$user_name=$user_data["user"]["user_name"];
					$wing=$user_data["user"]["wing"];
					$flat=$user_data["user"]["flat"];
					if(!empty($flat)){
						$wing_flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing,$flat))); 
						
						$result_other_charges = $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_other_charges_via_flat_id'),array('pass'=>array($flat))); 
						if(sizeof($result_other_charges)>0){
						?>
						<tr>
							<td><b><?php echo $user_name.' '.$wing_flat; ?></b></td>
							<td>
							<span style="float:right;" class="" data-placement="left" data-original-title="delete all charge">
							<a href="#" role="button" idd="<?php echo $flat ; ?>" class="btn black mini other_charges_delete">Delete All</a>
							</span>
							
							
							
							<?php 
							if(sizeof($result_other_charges)>0){
									echo '<div class="row-fluid">
											
										</div>';
										
								foreach($result_other_charges as $income_head_id=>$amount){ 
								 
									$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head_id)));	
									foreach($result_income_head as $data2){
										$income_head_name = $data2['ledger_account']['ledger_name'];
									} ?>
									<div class="row-fluid">
							<div class="span8"><?php echo $income_head_name; ?></div> 
							<div class="span4"><?php echo $amount; ?> 
							<span style="float:right;" class="tooltips" data-placement="left" data-original-title="delete current charge">
							<a href="#" role="button" idd="<?php echo $flat ; ?>" inch_id="<?php echo $income_head_id ; ?>" class="btn black mini other_charges_delete_oneby"><i class="icon-remove-sign"></i></a>
							</span></div> 
									</div>
								<?php } ?>
							<?php } ?>
							
								
							</td>
						</tr>
				<?php } } }?>
				</tbody>
			</table>
		
		
		</form>
		<!-- END FORM-->
		
		
		
		

		
		
	 </div>
	</div>
	<div id="delete_topic_result"></div>
	<!-- END VALIDATION STATES-->
=======
<hr>
<p style="font-size:18px; font-weight:500;">Delete Other Charges</p>		
<hr />



<label style="font-size:14px;">Select Member</label>
<div class="controls">
<select name="flat_resident" class="m-wrap span6" id="flat">
<option value="" style="display:none;">Select</option>
<?php
foreach($flat_detail as $data)
{
$flat = (int)$data['flat']['flat_id'];	
$wing = (int)$data['flat']['wing_id'];

$user_name = "";

	
$user_detail=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'fetch_user_info_via_flat_id'), array('pass' => array($flat)));
foreach($user_detail as $user_data)
{
$user_name = $user_data['user']['user_name'];	
}

$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat_with_brackets'), array('pass' => array($wing,$flat)));
if(!empty($user_name))
{
?>
<option value="<?php echo $flat; ?>"><?php echo $user_name; ?>&nbsp;&nbsp;<?php echo $wing_flat; ?></option>
<?php
}
}
?>
</select>
</div>
<br />

<div id="show_other_charges">
</div>



		
</div>
</div>
<!-- END VALIDATION STATES-->
>>>>>>> origin/master
<script>
$(document).ready(function(){
$('#contact-form').validate({
ignore: ".ignore",
			errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    }, 
	   	

rules: {
  amount: {
	required: true,
	number: true
  },
   "flats[]": {
	required: true,
  },
   income_head: {
	required: true
  },
 
  

},

messages: {
			"multi[]": {
				required: "Please select at-least one recipient."
			},
			file: {
					accept: "File extension must be png or jpg",
					filesize: "File size must be less than 2MB."
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
	
});

$('.other_charges_delete').bind('click',function(){
	var id=$(this).attr('idd');
   
	$('#delete_topic_result').html('<div id="pp"><div class="modal-backdrop fade in"></div><div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true"><div class="modal-body" style="font-size:14px;"><i class="icon-warning-sign" style="color:#d84a38;"></i> Are you sure you want to delete all charges for this flat ? </div><div class="modal-footer"><a href="<?php echo $webroot_path; ?>Incometrackers/other_charges_all_remove?con='+id+'&con2=0" class="btn blue" id="yes">Yes</a><a href="#"  role="button" id="can" class="btn">No</a></div></div></div>');
	$("#can").live('click',function(){
	   $('#pp').hide();
	});
}); 
$('.other_charges_delete_oneby').bind('click',function(){
	var id=$(this).attr('idd');
    var inch_id=$(this).attr('inch_id');
	
	$('#delete_topic_result').html('<div id="pp"><div class="modal-backdrop fade in"></div><div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true"><div class="modal-body" style="font-size:14px;"><i class="icon-warning-sign" style="color:#d84a38;"></i> Are you sure you want to delete this charge for flat ? </div><div class="modal-footer"><a href="<?php echo $webroot_path; ?>Incometrackers/other_charges_all_remove?con='+id+'&con2=1&con3='+inch_id+'" class="btn blue" id="yes">Yes</a><a href="#"  role="button" id="can" class="btn">No</a></div></div></div>');
	$("#can").live('click',function(){
	   $('#pp').hide();
	});
});

}); 
</script>

<script>
$(document).ready(function() {
$("#flat").bind('change',function(){

var flat_value = $("#flat").val();

$("#show_other_charges").html('Loading...').load("other_charges_ajax_for_delete?flat_id="+flat_value"");

});
});
</script>	



















