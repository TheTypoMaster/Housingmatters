<script>
$(document).ready(function(){
jQuery('.tooltips').tooltip();
});
</script>



<?php
$nnn = 55;
$m_from = date("Y-m-d", strtotime($from));
//$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
foreach ($cursor2 as $collection) 
{
$receipt_no = $collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by_id = (int)$collection['cash_bank']['prepaired_by'];
$user_id = (int)$collection['cash_bank']['user_id'];   
$invoice_reference = $collection['cash_bank']['invoice_reference'];
$description = $collection['cash_bank']['narration'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$receipt_instruction = $collection['cash_bank']['receipt_instruction'];
$account_id = (int)$collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
//$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];		
$current_date = $collection['cash_bank']['current_date'];		
$ac_type = $collection['cash_bank']['account_type'];
										
$creation_date = date('d-m-Y',$current_date->sec);											
if($ac_type == 1)
{						
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];  
}	
}											
else if($ac_type == 2)
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];  
}	
}											
$result55 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by_id)));
foreach ($result55 as $collection) 										
{
$prepaired_by_name = $collection['user']['user_name'];
}									 
									
$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['bank_account'];  
}    		
if($date >= $m_from && $date <= $m_to)
{
if($user_id == $s_user_id)
{
$nnn = 555;
}
else if($s_role_id == 3)
{
$nnn = 555;	
}
}
}
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php
if($nnn == 555)
{
?>
<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="bank_payment_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />			
			
			
<table class="table table-bordered" style=" background-color:white;">
<tr>
<th colspan="10" style="text-align:center;">
<p style="font-size:16px;">
Bank Payment Report  (<?php echo $society_name; ?>)
</p>
</th>
</tr>


<tr>
<th>From : <?php echo $from; ?></th>
<th>To : <?php echo $to; ?></th>
<th colspan="8"></th>
</tr>

<tr>
<th>Transaction Date</th>
<th>Payment Voucher</th>
<th>Amount</th>
<th>Paid To</th>
<th>Invoice Ref</th>
<th>Paid By</th>
<th>Cheque/UTR</th>
<th>Bank Account </th>
<th>Description</th>
<th class="hide_at_print">Action</th>
</tr>
											

<?php
$total_credit = 0;
$total_debit = 0;
foreach ($cursor2 as $collection) 
{
$receipt_no = $collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by_id = (int)$collection['cash_bank']['prepaired_by'];
$user_id = (int)$collection['cash_bank']['user_id'];   
$invoice_reference = $collection['cash_bank']['invoice_reference'];
$description = $collection['cash_bank']['narration'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$receipt_instruction = $collection['cash_bank']['receipt_instruction'];
$account_id = (int)$collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
//$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];		
$current_date = $collection['cash_bank']['current_date'];		
$ac_type = $collection['cash_bank']['account_type'];

										
$creation_date = date('d-m-Y',$current_date->sec);											
if($ac_type == 1)
{						
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];  
}	
}											
else if($ac_type == 2)
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];  
}	
}											
$result55 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by_id)));
foreach ($result55 as $collection) 										
{
$prepaired_by_name = $collection['user']['user_name'];
}									 
									
$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['bank_account'];  
}    		
if($date >= $m_from && $date <= $m_to)
{
if($user_id == $s_user_id)
{
$date = date('d-m-Y',strtotime($date));
$total_debit =  $total_debit + $amount;
$amount = number_format($amount);
?>			
<tr>
<td><?php echo $date; ?> </td>
<td><?php echo $receipt_no; ?> </td>
<td><?php echo $amount; ?> </td>
<td><?php echo $user_name; ?> </td>
<td><?php echo $invoice_reference; ?> </td>
<td><?php echo $receipt_mode; ?> </td>
<td><?php echo $receipt_instruction; ?> </td>
<td><?php echo $account_no; ?> </td>
<td><?php echo $description; ?> </td>
<td class="hide_at_print">
<a href="bank_payment_pdf?c=<?php echo $transaction_id; ?>&m=2" class="btn mini blue tooltips" target="_blank" data-placement="bottom" data-original-title="Download Pdf">Pdf</a>
<a href="" class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
Creation Date : <?php echo $creation_date; ?>" >!</a>
</td>
</tr>		

<?php
}
else if($s_role_id == 3)
{
$date = date('d-m-Y',strtotime($date));
$total_debit =  $total_debit + $amount; 
$amount = number_format($amount);
?>
<tr>
<td><?php echo $date; ?> </td>
<td><?php echo $receipt_no; ?> </td>
<td><?php echo $amount; ?> </td>
<td><?php echo $user_name; ?></td>
<td><?php echo $invoice_reference; ?> </td>
<td><?php echo $receipt_mode; ?> </td>
<td><?php echo $receipt_instruction; ?> </td>
<td><?php echo $account_no; ?> </td>
<td><?php echo $description; ?> </td>
<td class="hide_at_print">
<a href="bank_payment_pdf?c=<?php echo $transaction_id; ?>&m=2" class="btn mini purple tooltips" target="_blank" ata-placement="bottom" data-original-title="Download Pdf">Pdf</a>
<a href="" class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
Creation Date : <?php echo $creation_date; ?>" >!</a>
</td>
</tr>
<?php  }}} ?>
<?php
											
									    
									   
										 ?>
										   <tr>
										<th colspan="2"> Total</th>
                                        <th><?php 
										$total_debit = number_format($total_debit);
										echo $total_debit; ?> <?php //echo "  DR"; ?></th>
                                        <th colspan="6"></th>
									    <th class="hide_at_print"></th>
                                        </tr>
											</table>
											
											
											
											
											
<?php } 
if($nnn == 55)
{
?>											
									
									
<br /><br />											
<center>
<h3 style="color:red;">
<b>No Records Found in Selected Period</b>
</h3>
</center>
<br /><br />											
		
											
											
<?php 
}
?>
											
											
											
											
											
											
											
											
											
											
											
											
											
											