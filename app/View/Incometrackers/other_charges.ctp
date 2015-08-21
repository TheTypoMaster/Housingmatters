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
		<form METHOD="POST" class="form-horizontal">
		   <div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Select Income Head</label>
						<div class="controls">
							<select name="income_head" class="span12 chosen" data-placeholder="Choose a Category" tabindex="1">
								<option value="">
								<?php
								foreach($result_ledger_account as $data){
									$ledger_account_auto_id = (int)$data["ledger_account"]["auto_id"];
									$ledger_name = $data["ledger_account"]["ledger_name"];
									?>
								<option value="<?php echo $ledger_account_auto_id; ?>"><?php echo $ledger_name; ?>
								<?php } ?>
							 </select>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						  <label class="control-label">Enter Amount</label>
						  <div class="controls">
							 <input name="amount" class="span8 m-wrap" type="text" placeholder="Amount">
						  </div>
					</div>
				</div>
		   </div>
			<div class="row-fluid">
				<div class="span7">
					<div class="control-group">
						  <label class="control-label">Select Flats</label>
						  <div class="controls">
							<select name="flats[]" data-placeholder="Your Favorite Football Teams" class="chosen span12" multiple="multiple" tabindex="6">
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
						  </div>
					</div>
				</div>
				<div class="span5"><button type="submit" name="add_charges" class="btn purple"><i class=" icon-plus-sign"></i> Add charge for selected flats</button></div>
			</div>
			
			
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
							<td><?php echo $user_name.' '.$wing_flat; ?></td>
							<td>
							<?php 
							if(sizeof($result_other_charges)>0){
									echo '<div class="row-fluid">
											<div class="span8"><b>CHARGE NAME</b></div>
											<div class="span4"><b>AMOUNT</b></div>
										</div>';
										
								foreach($result_other_charges as $income_head_id=>$amount){ 
								 
									$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head_id)));	
									foreach($result_income_head as $data2){
										$income_head_name = $data2['ledger_account']['ledger_name'];
									} ?>
									<div class="row-fluid">
										<div class="span8"><?php echo $income_head_name; ?></div>
										<div class="span4"><?php echo $amount; ?></div>
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
	<!-- END VALIDATION STATES-->
