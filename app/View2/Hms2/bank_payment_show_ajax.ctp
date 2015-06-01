<script>
$(document).ready(function(){
jQuery('.tooltips').tooltip();
});
</script>


<?php
$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

?>
<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="bank_payment_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />			
			
			
<table class="table table-bordered" style=" background-color:#FDFDEE;">


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
									  foreach ($cursor1 as $collection) 
									  {
									   $receipt_no = $collection['bank_payment']['receipt_id'];
									   $transaction_id = (int)$collection['bank_payment']['transaction_id'];	
									   $date = $collection['bank_payment']['transaction_date'];
									   $prepaired_by_id = (int)$collection['bank_payment']['prepaired_by'];
									   $user_id = (int)$collection['bank_payment']['user_id'];   
                                       $invoice_reference = $collection['bank_payment']['invoice_reference'];
									   $description = $collection['bank_payment']['narration'];
									   $receipt_mode = $collection['bank_payment']['receipt_mode'];
									   $receipt_instruction = $collection['bank_payment']['receipt_instruction'];
									   $account_id = (int)$collection['bank_payment']['account_id'];
									   $amount = $collection['bank_payment']['amount'];
									   $amount_category_id = (int)$collection['bank_payment']['amount_category_id'];		
									   $current_date = $collection['bank_payment']['current_date'];		
										$ac_type = $collection['bank_payment']['account_type'];

										
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

$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id))); 									  
   								  foreach ($result_amt as $collection) 
									   {
									   $amount_category = $collection['amount_category']['amount_category'];  
									   }  
									   		
									
									
									
									
$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
									   foreach ($result_lsa2 as $collection) 
									   {
									   $account_no = $collection['ledger_sub_account']['bank_account'];  
									   }    		
											
											
								 if($amount_category_id == 1)
									   {
										   if($date >= $m_from && $date <= $m_to)
										   {
											    if($user_id == $s_user_id)
											   {
									   
									       $date = date('d-m-Y',$date->sec);
										    $total_debit =  $total_debit + $amount;
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
                                            <a href="bank_payment_pdf?c=<?php echo $transaction_id; ?>" class="btn mini blue tooltips" target="_blank" data-placement="bottom" data-original-title="Download Pdf">Pdf</a>
											 <a href="" class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
										     Creation Date : <?php echo $creation_date; ?>" >!</a>
											
											</td>
										    </tr>		
											
											 <?php
									         }
									        else if($s_role_id == 3)
									         {
										    $date = date('d-m-Y',$date->sec);
											 $total_debit =  $total_debit + $amount; 
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
                                           <a href="bank_payment_pdf?c=<?php echo $transaction_id; ?>" class="btn mini purple tooltips" target="_blank" ata-placement="bottom" data-original-title="Download Pdf">Pdf</a>
							               <a href="" class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
										   Creation Date : <?php echo $creation_date; ?>" >!</a>
                                           </td>
										   </tr>
											
										   <?php  }}}} ?>
											<?php
											
									    
									   
										 ?>
										   <tr>
										<th colspan="2"> Total</th>
                                        <th><?php echo $total_debit; ?> <?php //echo "  DR"; ?></th>
                                        <th colspan="6"></th>
									    <th class="hide_at_print"></th>
                                        </tr>
											</table>
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											