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
<span style="float:right;"><a href="petty_cash_payment_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue">Export in Excel</a></span>
<span style="float:right; margin-right:1%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br /><br />
<table class="table table-bordered" width="100%" style="background-color:#FDFDEE;">

<tr>
<th colspan="6" style="text-align:center;">
<p style="font-size:16px;">
Petty Cash Payment Report  (<?php echo $society_name; ?>)
</p>
</th>
</tr>

<tr>
<th>From : <?php echo $from; ?></th>
<th>To : <?php echo $to; ?></th>
<th colspan="4"></th>
</tr>

                                        <tr>
                                            <th>PC Payment Vochure</th>
											<th>Transaction Date</th>
											 <th>Paid To</th>
											<th>Narration</th>
                                           
                                            <th>Amount</th>
                                            <th class="hide_at_print">Action </th>
                                        </tr>



   <?php
			
			$total_debit = 0;
			$total_credit = 0;
			foreach ($cursor1 as $collection) 
			{
			$receipt_no = (int)@$collection['petty_cash_payment']['receipt_id'];
			$transaction_id = (int)$collection['petty_cash_payment']['transaction_id'];	
			$account_type = (int)$collection['petty_cash_payment']['account_type'];
			$user_id = (int)$collection['petty_cash_payment']['user_id'];
			$date = $collection['petty_cash_payment']['transaction_date'];
			$prepaired_by = (int)$collection['petty_cash_payment']['prepaired_by'];   
			$narration = $collection['petty_cash_payment']['narration'];
			$account_head = $collection['petty_cash_payment']['account_head'];
			$amount = $collection['petty_cash_payment']['amount'];
			$amount_category_id = (int)$collection['petty_cash_payment']['amount_category_id'];
            $current_date = $collection['petty_cash_payment']['current_date'];
$creation_date = date('d-m-Y',$current_date->sec);

$result_gh = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by)));
				foreach ($result_gh as $collection) 
				{
				$prepaired_by_name = $collection['user']['user_name'];
				}			


                                    if($account_type == 1)
									{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));
									foreach ($result_lsa as $collection) 
									  {
									   $user_name = $collection['ledger_sub_account']['name'];	  
									  }
									}
									else if($account_type == 2)
									{
$result_la = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($user_id)));
									foreach ($result_la as $collection) 
									  {
									   $user_name = $collection['ledger_account']['ledger_name'];	  
									  }
									}      

											
$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id)));
									foreach ($result_amt as $collection) 
									{
									$amount_category_name = $collection['amount_category']['amount_category'];	  
									}  

									if($amount_category_id == 1)
									{
									if($date >= $m_from && $date <= $m_to)
									{
									if($s_user_id == $user_id)  
									{
									$date = date('d-m-Y',$date->sec);     
									$total_debit = $total_debit + $amount;
                                    ?>
<tr>

<td><?php echo $receipt_no; ?> </td>
<td><?php echo $date; ?> </td>
<td><?php echo $user_name; ?> </td>
<td><?php echo $narration; ?> </td>
<td><?php echo $amount; ?></td>

<td class="hide_at_print"><a href="petty_cash_payment_pdf?c=<?php echo $transaction_id; ?>" target="_blank" class="btn mini purple tooltips"
 data-placement="bottom" data-original-title="Download Pdf" >Pdf</a>
<a class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
										   Creation Date : <?php echo $creation_date; ?>">!</a>
 
 </td>
</tr>
 <?php
                                         }
										   else if($s_role_id == 3)
										   {
										 $date = date('d-m-Y',$date->sec);	   
										 $total_debit = $total_debit + $amount;
										 
										 ?>
                                        <tr>
											
                                            <td><?php echo $receipt_no; ?> </td>
											<td><?php echo $date; ?> </td>
											 <td><?php echo $user_name; ?> </td>
                                            <td><?php echo $narration; ?> </td>
                                           
                                            <td><?php echo $amount; ?></td>
                                          
                                          <td class="hide_at_print"><a href="petty_cash_payment_pdf?c=<?php echo $transaction_id; ?>" target="_blank" class="btn mini purple tooltips"
										   data-placement="bottom" data-original-title="Download Pdf" >Pdf</a>
                                          <a class="btn mini black tooltips" data-placement="bottom" data-original-title="Created By:<?php echo $prepaired_by_name; ?>
										   Creation Date : <?php echo $creation_date; ?>">!</a>
										  
										  
										  </td>
                                          </tr>
 <?php	   
											   
									   }}}
									    }
									?>
 <tr>
                                    <th colspan="4">Total</th>
                                    <th><?php echo $total_debit; ?></th>
                                    <th class="hide_at_print"></th>
                                    </tr>
                                        </table>





























