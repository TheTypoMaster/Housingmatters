<?php 

if($type == 1 && !empty($flat_id)){
	//last bill//
	$result_new_regular_bill = $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_last_bill_info_via_flat_id'),array('pass'=>array($flat_id)));
	
	foreach($result_new_regular_bill as $data){
		$bill_no=$data["bill_no"];
		$bill_start_date=$data["bill_start_date"];
		$due_date=$data["due_date"];
		$due_for_payment=$data["due_for_payment"];
		$due_date=$data["due_date"];
		$last_bill_one_time_id=$data["one_time_id"];
		//last receipt//
		$result_new_cash_bank = $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_last_receipt_info_via_flat_id'),array('pass'=>array($flat_id,$last_bill_one_time_id)));
		$total_amount=0;
		foreach($result_new_cash_bank as $data2){
		$amount=$data2["new_cash_bank"]["amount"];
		$total_amount+=$amount;
		}
		?>
		
        <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Amount Applied for Bill <b><?php echo $bill_no; ?></b><span style="color:red;">*</span></label>
        <div class="controls">
        &nbsp;&nbsp;<input type="text" name="amount" class="m-wrap span5" id="amt_other" /><a href="#myModal2" role="button" class="btn btn-danger" data-toggle="modal">Bill Detail</a> 
        &nbsp;&nbsp;<label id="amt_other"></label>	      
        </div>
       
       
       
        <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="false" style="display: block;">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3 id="myModalLabel2">Alert Header</h3>
        </div>
        <div class="modal-body">
        <p>Body goes here...</p>
        </div>
        <div class="modal-footer">
        <button data-dismiss="modal" class="btn green">OK</button>
        </div>
        </div>
        
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
        
        <!--<table style="width:100%;" border="1" class="table table-bordered">
		<tr>
		<th style="text-align:center;">Bill No.</th>
		<th style="text-align:center;">Bill Date</th>
		<th style="text-align:center;">Bill Due Date</th>
		<th style="text-align:center;">Bill Amount</th>
		<th style="text-align:center;">Due Amount</th>
		<th style="text-align:center;">Amount Applied</th>
		</tr>
				 
<?php
$bill_start_date = date('d-m-Y',($bill_start_date));
$due_date = date('d-m-Y',($due_date));

?>
		<tr>
		<td style="text-align:center;"><?php echo $bill_no; ?></td>
		<td style="text-align:center;"><?php echo $bill_start_date; ?></td>
		<td style="text-align:center;"><?php echo $due_date; ?></td>
		<td style="text-align:center;"><?php echo $due_for_payment; ?></td>
		<td style="text-align:center;"><?php echo $due_for_payment-$total_amount; ?></td>
		<td style="text-align:center;"><input type="text" class="m-wrap small" style="background-color:white !important;" name="amount" id="ab"/></td>
		</tr>
		<tr>
		<td colspan="6" style="text-align:right;"><label id="ab"></label></td>
		</table>
        <br /> -->
		<?php
}
}
else if($type == 2)
{
?>	
<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="amount" class="m-wrap span9" id="amt_other" />  
<label id="amt_other"></label>	
</div>
<br />	 
<?php	
}
?> 