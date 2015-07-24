<?php
foreach($cursor1 as $data){
	$receipt_id=$data["cash_bank"]["receipt_id"];
	$transaction_date=$data["cash_bank"]["transaction_date"];
	$receipt_mode=$data["cash_bank"]["receipt_mode"];
	$cheque_number=@$data["cash_bank"]["cheque_number"];
	$which_bank=@$data["cash_bank"]["which_bank"];
	$reference_number=@$data["cash_bank"]["reference_number"];
	$current_date=$data["cash_bank"]["current_date"];
	$member=@$data["cash_bank"]["member"];
	$account_head=@$data["cash_bank"]["account_head"];
	$user_id=@$data["cash_bank"]["user_id"];
	
	$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($user_id)));
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
	<div class="row-fluid">
	<div class="span6">
	   <div class="control-group">
		  <label class="control-label">Transaction date*</label>
		  <div class="controls">
			 <input type="text" class="span6 m-wrap" value="<?php echo $transaction_date; ?>" id="inputWarning">
		  </div>
	   </div>
	   
	   
	  <div class="control-group">
	   <label  class="control-label">Receipt Mode<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose receipt mode"> </i></label>
	   <div class="controls">
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span>
		<input type="radio" name="mode" <?php if($receipt_mode=="Cheque"){ echo 'checked=""';} ?> value="Cheque" style="opacity: 0;" id="mode" class="chn">
		</span></div>
		Cheque
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span>
		<input type="radio" name="mode" value="NEFT" <?php if($receipt_mode=="NEFT"){ echo 'checked=""';} ?> >
		</span></div>
		NEFT
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" <?php if($receipt_mode=="pg"){ echo 'checked=""';} ?>></span></div>
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
	
	Receipt Applied towards following bill:
	<table class="table table-bordered">
		<tr>
		<th>Bill No.</th>
		<th>Bill Date</th>
		<th>Bill Due Date</th>
		<th>Bill Amount</th>
		<th>Due Amount</th>
		<th>Amount Applied</th>
		</tr>
		<tr>
		<td>1001</td>
		<td>18-07-2015</td>
		<td>15-01-2015</td>
		<td>6000</td>
		<td>-4000</td>
		<td><input type="text" class="m-wrap span12"/></td>
		</tr>
	</table>
	</div>
	</div>
	   <div class="form-actions">
		  <button type="submit" class="btn green">Save</button>
		  <button type="button" class="btn">Cancel</button>
	   </div>
	</form>
	<!-- END FORM-->
 </div>
</div>