<?php
foreach($cursor1 as $data){
	$receipt_id=$data["new_cash_bank"]["receipt_id"];
	$transaction_date=$data["new_cash_bank"]["receipt_date"];
	$transaction_date=date("d-m-Y",($transaction_date));
	//$t_id=$data["new_cash_bank"]["transaction_id"];
	
	$receipt_mode=$data["new_cash_bank"]["receipt_mode"];
	$cheque_number=@$data["new_cash_bank"]["cheque_number"];
	$which_bank=@$data["new_cash_bank"]["which_bank"];
	$reference_number=@$data["new_cash_bank"]["reference_number"];
	$current_date=$data["new_cash_bank"]["current_date"];
	$current_date= date('d-m-Y',strtotime($current_date));
	$member=@$data["new_cash_bank"]["member_type"];
	$amount=@(int)$data["new_cash_bank"]["amount"];
	
	$account_head=@$data["new_cash_bank"]["account_head"];
	$user_id_d=@$data["new_cash_bank"]["party_name_id"];
	

	if($member == 1)
	{
	$regular_receipt = (int)@$data['new_cash_bank']['bill_reference'];
	$bill_for = (int)@$data["new_cash_bank"]["receipt_type"];
	
	
	}
	
	$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch2'), array('pass' => array($user_id_d)));
	foreach($result_user_info as $collection2)
	{
	$user_name=$collection2["user"]["user_name"];
	$wing=$collection2["user"]["wing"];
	$flat=$collection2["user"]["flat"];
	}

	$flat_info=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing,$flat)));
	
} ?>
<div class="portlet box blue">
 <div class="portlet-title">
	<h4><i class="icon-reorder"></i>Edit Reciept - <?php echo $receipt_id; ?></h4>
 </div>
 <div class="portlet-body form">
	<!-- BEGIN FORM-->
	<form method="post" class="form-horizontal">
	
	<input type="hidden" value="<?php echo $receipt_id; ?>" name="rrrr">
	<input type="hidden" value="<?php echo $bill_for; ?>" name="ffff">
	<input type="hidden" value="<?php echo $member; ?>" name="mmmm">
	<input type="hidden" value="<?php echo $regular_receipt; ?>" name="regrec">
	<input type="hidden" value="<?php echo $receipt_id; ?>" name="t_id">
	
	
	<div class="row-fluid">
	<div class="span6">
	   <div class="control-group">
		  <label class="control-label">Transaction date*</label>
		  <div class="controls">
			 <input type="text" class="span6 m-wrap date-picker" name="t_date"  value="<?php echo $transaction_date; ?>" id="inputWarning" data-date-format="dd-mm-yyyy">
		  </div>
	   </div>
	   
	   
	  <div class="control-group">
	   <label  class="control-label">Receipt Mode<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose receipt mode"> </i></label>
	   <div class="controls">
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span>
		<input type="radio" name="mode" <?php if($receipt_mode=="Cheque"){ echo 'checked=""';} ?> value="Cheque" style="opacity: 0;" id="Cheque" class="chn">
		</span></div>
		Cheque
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span>
		<input type="radio" name="mode" value="NEFT" <?php if($receipt_mode=="NEFT"){ echo 'checked=""';} ?> id="NEFT">
		</span></div>
		NEFT
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span>
		<input type="radio" name="mode" <?php if($receipt_mode=="pg"){ echo 'checked=""';} ?> id="PG">
		</span></div>
		PG
		</label> 
		<label id="mode"></label>
		</div>
		</div>
		
		 
		<div id="cheque_div" <?php if($receipt_mode=="Cheque") { echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?> >
			<div class="control-group">
			<label class="control-label">Cheque No.<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="cheque_number" class="m-wrap span9 " placeholder="Cheque No."  value="<?php echo @$cheque_number; ?>">
			<label id="ins"></label>
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label">Drawn on which bank?<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="which_bank" class="m-wrap span9 " placeholder="Drawn on which bank?" value="<?php echo @$which_bank; ?>">
			<label id="ins"></label>
			</div>
			</div>
		</div>
		
		
		<div id="neft_div" <?php if($receipt_mode=="Cheque") { echo 'style="display:none;"'; }else{ echo 'style="display:block;"'; } ?> >
			<div class="control-group">
			<label class="control-label">Reference/UTR #<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="reference_number" class="m-wrap span9 ignore" placeholder="Reference/UTR #" value="<?php echo @$reference_number; ?>">
			<label id="ins"></label>
			</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Date<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="cheque_date" class="m-wrap span9 date-picker" placeholder="Date" data-date-format="dd-mm-yyyy" value="<?php echo @$current_date; ?>">
			<label id="ins"></label>
			</div>
		</div>
		
		
		<div class="control-group">
			<label class="control-label">Deposited In<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select deposit bank "> </i></label>
			<div class="controls">
			<select name="bank_account" class="span9 m-wrap chosen" id="bank">
			<option value="" style="display:none;">which bank?</option>    
			<?php
			foreach ($cursor3 as $db) 
			{
			$bank_id = (int)$db['ledger_sub_account']["auto_id"];
			$bank_ac = $db['ledger_sub_account']["name"];
			?>
			<option value="<?php echo $bank_id; ?>" <?php if($bank_id==$account_head){ echo 'selected="selected"'; } ?>><?php echo $bank_ac; ?></option>
			<?php } ?>
			</select>
			<label id="bank"></label>
			</div>
		</div>
		
		
		
	</div>
	<div class="span6">
	Received from  -  <?php if($member==1) { echo '<b>Member</b>'; }else{ echo '<b>Non Member</b>'; } ?><br/>
	Party Name - <b><?php echo $user_name.' '.$flat_info; ?></b><br/><br/>
	
	<label>Amount</label>
	<input type="text" class="m-wrap" value="<?php echo @$amount; ?>" name="amount" />
	
	</div>
	</div>
	   <div class="form-actions">
		  <button type="submit" class="btn green" name="bank_receipt_update">Save</button>
		  <button type="button" class="btn">Cancel</button>
	   </div>
	</form>
	<!-- END FORM-->
 </div>
</div>

<script>
$(document).ready(function() {
	$("#Cheque").live('click',function(){
		$("#cheque_div").show();
		$("#neft_div").hide();
	});
	$("#NEFT").live('click',function(){
		$("#neft_div").show();
		$("#cheque_div").hide();
	});
	$("#PG").live('click',function(){
		$("#neft_div").show();
		$("#cheque_div").hide();
	});
});
</script>