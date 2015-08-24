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
			
<hr>
<p style="font-size:18px; font-weight:500;">Delete Other Charges</p>		
<hr />

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



		
		
		<!-- END FORM-->
		
		
		
		

		
		
	 </div>
	</div>
	<!-- END VALIDATION STATES-->
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

}); 
</script>