<script>
$(document).ready(function(){
jQuery('.tooltips').tooltip();
});
</script> 
<?php
//$m_from = strtotime($from);
//$m_to = strtotime($to);
?>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$nnn = 55;
foreach ($cursor2 as $collection) 
{
$receipt_date = $collection['new_cash_bank']['receipt_date'];
if($receipt_date >= $m_from && $receipt_date <= $m_to)
{
$nnn = 555;
}
}			
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
if($nnn == 555)
{
?>
<div style="width:100%; overflow:auto;" class="hide_at_print">
<span style="float:right;">
<a href="bank_receipt_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue" target="_blank">Export in Excel</a></span>
<span style="float:right; margin-right:1%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>	
<br />			

<table class="table table-bordered" width="100%" style=" background-color:white;">
<tr>
<th colspan="10" style="text-align:center;">
<p style="font-size:16px;">
Bank Receipt Report  (<?php echo $society_name; ?>)
</p>
</th>
</tr>
<tr>
<th colspan="8">From : <?php echo $from; ?> &nbsp;&nbsp; To : <?php echo $to; ?></th>
<th colspan="8"></th>
</tr>
<tr>
<th>Receipt#</th>
<th>Receipt Date </th>
<th>Party Name</th>
<th>Payment Mode</th>
<th>Instrument/UTR</th>
<th>Deposit Bank</th>
<th>Narration</th>
<th>Amount</th>
<th class="hide_at_print">View|Edit|Pdf|Info</th> 
</tr>
<?php
			$total_credit = 0;
			$total_debit = 0;
			foreach ($cursor2 as $collection) 
			{
			$receipt_no = $collection['new_cash_bank']['receipt_id'];
			$transaction_id = (int)$collection['new_cash_bank']['receipt_date'];	
			$receipt_mode = $collection['new_cash_bank']['receipt_mode'];
			$receipt_date = $collection['new_cash_bank']['receipt_date'];
			if($receipt_mode == "Cheque")
   			{
			 $cheque_number = $this->request->data['cheque_number'];
			 $cheque_date = $this->request->data['cheque_date'];
			 $drawn_on_which_bank = $this->request->data['drawn_on_which_bank'];
			}
			else
			{
 			 $reference_utr = $this->request->data['reference_utr'];
 			 $cheque_date = $this->request->data['cheque_date'];
			}
			$member_type = $collection['new_cash_bank']['member_type'];
			$narration = $collection['new_cash_bank']['narration'];
			if($member_type == 1)
   			{
			$party_name_id = (int)$collection['new_cash_bank']['party_name_id'];
			$receipt_type = $collection['new_cash_bank']['receipt_type'];
			
$user_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($party_name_id)));	
			foreach($user_fetch as $rrr)
			{
			$party_name = $rrr['user']['user_name'];	
			$wing_id = $rrr['user']['wing'];
			}
			
			$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat_new'),array('pass'=>array($wing_id,$party_name_id)));

			}
			else
			{
			$wing_flat = "";
			$party_name = $collection['new_cash_bank']['party_name_id'];
			$bill_reference = $collection['new_cash_bank']['bill_reference'];	
			}
			$amount=$collection['new_cash_bank']['party_name_id'];
			$flat_id = $collection['new_cash_bank']['flat_id'];
			$deposited_bank_id = (int)$collection['new_cash_bank']['deposited_bank_id'];
			$current_date = $collection['new_cash_bank']['current_date'];
			if($receipt_mode == "Cheque")
			{
			$receipt_mode = $receipt_mode."(".$cheque_number.")";
			}
			
			
$ledger_sub_account_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($deposited_bank_id)));			
foreach($ledger_sub_account_fetch as $rrrr)
			{
			$deposited_bank_name = $rrr['ledger_sub_account']['name'];	
			}			
			
			
			
if($s_role_id == 3)
{
$date = date('d-m-Y',strtotime($receipt_date));
$total_debit =  $total_debit + $amount; 
$amount = number_format($amount);
?>
<tr>
<td><?php echo $receipt_no; ?> </td>
<td><?php echo $date; ?> </td>
<td><?php echo $party_name; ?> &nbsp&nbsp&nbsp&nbsp<?php echo $wing_flat; ?> </td>
<td><?php echo $receipt_mode; ?> </td>
<td><?php echo $reference_utr; ?> </td>
<td><?php echo $deposited_bank_name; ?> </td>
<td><?php echo $narration; ?> </td>
<td><?php echo $amount; ?></td>
<td class="hide_at_print">
</td>
</tr>
<?php	
}		 
}

?>
<tr>
<th colspan="8" style="text-align:right;"> Total</th>
<th><?php 
$total_debit = number_format($total_debit);
echo $total_debit; ?> <?php //echo "  dr"; ?></th>
<th class="hide_at_print"></th>
</tr>										 
</table> 
			 
<?php 
}
if($nnn == 55)
{
?>					 
<br /><br />					 
<center>					 
<h3 style="color:red;"><b>No Record Found in Selected Period</b></h3>					 
</center>					 
<br /><br />					 
<?php
}
?>
					 
					 
					 
					 