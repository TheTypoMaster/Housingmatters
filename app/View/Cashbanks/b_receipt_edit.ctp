<?php
pr($cursor1); ?>
<div class="portlet box blue">
 <div class="portlet-title">
	<h4><i class="icon-reorder"></i>Edit Reciept</h4>
 </div>
 <div class="portlet-body form">
	<!-- BEGIN FORM-->
	<form method="post" class="form-horizontal">
	<div class="row-fluid">
	<div class="span6">
	   <div class="control-group">
		  <label class="control-label">Transaction date*</label>
		  <div class="controls">
			 <input type="text" class="span6 m-wrap" id="inputWarning">
		  </div>
	   </div>
	   
	   
	  <div class="control-group">
	   <label  class="control-label">Receipt Mode<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose receipt mode"> </i></label>
	   <div class="controls">
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" checked="" value="Cheque" style="opacity: 0;" id="mode" class="chn"></span></div>
		Cheque
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="NEFT" style="opacity: 0;" id="mode" class="neft"></span></div>
		NEFT
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="PG" style="opacity: 0;" id="mode" class="pg"></span></div>
		PG
		</label> 
		<label id="mode"></label>
		</div>
		</div>
		
		<div id="cheque_div">
			<div class="control-group">
			<label class="control-label">Cheque No.<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="cheque_number" class="m-wrap span9 " placeholder="Cheque No." style="background-color:white !important;" id="ins">
			<label id="ins"></label>
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label">Drawn on which bank?<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="which_bank" class="m-wrap span9 " placeholder="Drawn on which bank?" style="background-color:white !important;" id="ins">
			<label id="ins"></label>
			</div>
			</div>
		</div>
		
		<div id="neft_div" style="display:;">
			<div class="control-group">
			<label class="control-label">Reference/UTR #<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="reference_number" class="m-wrap span9 ignore" placeholder="Reference/UTR #" style="background-color:white !important;" id="ins">
			<label id="ins"></label>
			</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Date<span style="color:red;">*</span> </label>
			<div class="controls">
			<input type="text"  name="cheque_date" class="m-wrap span9 date-picker" placeholder="Date" data-date-format="dd-mm-yyyy" style="background-color:white !important;" id="ins">
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
			<option value="<?php echo $bank_id; ?>"><?php echo $bank_ac; ?></option>
			<?php } ?>
			</select>
			<label id="bank"></label>
			</div>
		</div>
		
		
		
	</div>
	<div class="span6">
	Received from  -  Member / Non Member<br/>
	Party Name - Abhilash Lohar<br/>
	
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