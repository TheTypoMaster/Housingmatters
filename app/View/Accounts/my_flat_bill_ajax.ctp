<?php
$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));
?>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="my_flat_bill_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<br />
<table class="table table-bordered" style="width:100%; background-color:white;">
<tr>
<th colspan="10" style="text-align:center;">
<p style="font-size:16px;">
Bill Detail(<?php echo $society_name; ?>)
</p>
</th>
</tr>
<tr>
<th style="text-align:center;">#</th>
<th style="text-align:center;">Bill No.</th>
<th style="text-align:center;" colspan="2">Bill Date</th>
<th style="text-align:center;" colspan="2">Due Date</th>
<th style="text-align:center;">Total Amount</th>
<th style="text-align:center;">Pay Amount</th>
<th style="text-align:center;">Due Amount</th>
<th style="text-align:center;" class="hide_at_print">Action</th>
</tr>
<?php
$nn=0;
$gt_amt = 0;
$gt_pay_amt = 0;
$due_amt = 0;
foreach($cursor1 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$from2 = $collection['regular_bill']['bill_daterange_from'];
$to2 = $collection['regular_bill']['bill_daterange_to'];
$due_date = $collection['regular_bill']['due_date'];
$total_amount = (int)$collection['regular_bill']['g_total'];
$remaining_amt = (int)$collection['regular_bill']['remaining_amount'];
$date = $collection['regular_bill']['date'];
$fromm = date('d-M-Y',$from2->sec);
$tom = date('d-M-Y',$to2->sec);
$due = date('d-M-Y',$due_date->sec);
$pay_amt = $total_amount - $remaining_amt; 
if($m_from <= $date && $m_to >= $date)
{
$nn++;
$gt_amt = $gt_amt + $total_amount;
$gt_pay_amt = $gt_pay_amt + $pay_amt;
$due_amt = $due_amt + $remaining_amt;
?>
<tr>
<td style="text-align:center;"><?php echo $nn; ?></td>
<td style="text-align:center;"><?php echo $bill_no; ?></td>
<td style="text-align:center;" colspan="2"><?php echo $fromm; ?>-<?php echo $tom; ?></td>
<td style="text-align:center;" colspan="2"><?php echo $due; ?></td>
<td style="text-align:center;"><?php echo $total_amount; ?></td>
<td style="text-align:center;"><?php echo $pay_amt; ?></td>
<td style="text-align:center;"><?php echo $remaining_amt; ?></td>
<td style="text-align:center;" class="hide_at_print">
<a href="ac_statement_bill_view/<?php echo $bill_no; ?>" class="btn mini yellow" target="_blank">View Bill</a>

</td>
</tr>
<?php
}}
?>
<tr>
<th colspan="6">Grand Total</th>
<th style="text-align:center;"><?php echo $gt_amt; ?></th>
<th style="text-align:center;"><?php echo $gt_pay_amt; ?></th>
<th style="text-align:center;"><?php echo $due_amt; ?></th>
<th class="hide_at_print"></th>
</tr>
<tr>
<th style="text-align:center;" colspan="10">
<p style="font-size:16px;">Bank Receipt Detail</p>
</th>
</tr>
<tr>
<th>Receipt#</th>
<th>Transaction Date </th>
<th>Party Name</th>
<th>Bill Reference</th>
<th>Payment Mode</th>
<th>Instrument/UTR</th>
<th>Deposit Bank</th>
<th>Narration</th>
<th>Amount</th>
<th class="hide_at_print">Action</th> 
</tr>
<?php
$total_credit = 0;
$total_debit = 0;
foreach ($cursor4 as $collection) 
{
$receipt_no = $collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by_id = (int)$collection['cash_bank']['prepaired_by'];
$member = (int)$collection['cash_bank']['member'];
$narration = $collection['cash_bank']['narration'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$receipt_instruction = $collection['cash_bank']['receipt_instruction'];
$account_id = (int)$collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];
$current_date = $collection['cash_bank']['current_date'];  
if($member == 1)
{
$received_from_id = (int)$collection['cash_bank']['user_id'];
$ref = $collection['cash_bank']['bill_reference'];
$ref = "Bill No:".$ref;
}        
$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($received_from_id)));	
foreach($result1 as $collection)
{	
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}			  
$creation_date = date('d-m-Y',$current_date->sec);	         
$result_prb = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by_id)));
foreach ($result_prb as $collection) 
{
$prepaired_by_name = $collection['user']['user_name'];
}	         
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$wing_id = (int)$collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	                  
$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id)));									
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['name'];  
}		
if($date >= $m_from && $date <= $m_to)
{
$tr_date = date('d-M-Y',$date->sec);
$total_debit = $total_debit + $amount		
?>	
<tr>
<td><?php echo $receipt_no; ?> </td>
<td><?php echo $tr_date; ?> </td>
<td width="15%"><?php echo $narration; ?> </td>
<td><?php echo $user_name; ?> &nbsp&nbsp&nbsp&nbsp<?php echo $wing_flat; ?></td>
<td><?php echo $ref; ?> </td>
<td><?php echo $receipt_mode; ?> </td>
<td><?php echo $receipt_instruction; ?> </td>
<td><?php echo $account_no; ?> </td>
<td><?php echo $amount; ?></td>

<td class="hide_at_print"> <!--<a href="#" class="btn mini blue">Reverse</a> -->
<a href="bank_receipt_pdf?c=<?php echo $transaction_id; ?>&m=1" class="btn mini purple  tooltips" target="_blank" data-placement="bottom" data-original-title="Download Pdf">Pdf</a>
<a href="" class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
Creation Date : <?php echo $creation_date; ?>">!</a>
</td>
</tr>					
<?php		
}
}
?>	
<tr>
<th colspan="8"> Total</th>
<th><?php echo $total_debit; ?></th>
<th class="hide_at_print"></th>
</tr>	
</table>

















</table>