<style>

 #bg_color th{
	font-size: 10px !important;background-color:#C8EFCE;padding:2px;border:solid 1px #55965F;
}
#report_tb td{
	padding:2px;
	font-size: 12px;border:solid 1px #55965F;background-color:#FFF;
}
.text_bx{
	width: 50px;
	height: 15px !important;
	margin-bottom: 0px !important;
	font-size: 12px;
}
.text_rdoff{
	width: 50px;
	height: 15px !important;
	border: none !important;
	margin-bottom: 0px !important;
	font-size: 12px;
}

</style>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$nnn = 55;
foreach ($cursor2 as $collection) 
{
$receipt_date = $collection['new_cash_bank']['receipt_date'];
$nnn = 555;

}			
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
if($nnn == 555)
{
?>
<table  width="100%" style=" background-color:white;" id="report_tb">
</tr>
<tr>
<th colspan="10" >From : <?php echo $from; ?> &nbsp;&nbsp; To : <?php echo $to; ?></th>
</tr>
<tr id="bg_color">
<th>Receipt#</th>
<th>Receipt Date </th>
<th>Party Name</th>
<th>Unit Detail</th>
<th>Payment Mode</th>
<th>Instrument/UTR</th>
<th>Deposit Bank</th>
<th>Narration</th>
<th>Amount</th>
<th class="hide_at_print">View|Edit|Pdf</th> 
</tr>
		<?php
        	$total_credit = 0;
        	$total_debit = 0;
       		 $n=0;
        foreach ($cursor2 as $collection) 
        	{
       	 	$n++;
        	$receipt_no = $collection['new_cash_bank']['receipt_id'];
        	$receipt_mode = $collection['new_cash_bank']['receipt_mode'];
        	$TransactionDate = $collection['new_cash_bank']['receipt_date'];
						
					if($receipt_mode == "Cheque")
					{
						$reference_utr = $collection['new_cash_bank']['cheque_number'];
						$cheque_date = $collection['new_cash_bank']['cheque_date'];
						$drawn_on_which_bank = $collection['new_cash_bank']['drawn_on_which_bank'];
					}
					else
					{
						$reference_utr = $collection['new_cash_bank']['reference_utr'];
						$cheque_date = $collection['new_cash_bank']['cheque_date'];
					}
						$member_type = $collection['new_cash_bank']['member_type'];
						$narration = @$collection['new_cash_bank']['narration'];
				if($member_type == 1)
   				{
					$party_name_id = (int)$collection['new_cash_bank']['party_name_id'];
					$receipt_type = $collection['new_cash_bank']['receipt_type'];
			
			$user_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_user_info_via_flat_id'),array(				'pass'=>array($party_name_id)));	
			foreach($user_fetch as $rrr)
			{
				$party_name = $rrr['user']['user_name'];	
				$wing_id = $rrr['user']['wing'];
			}
			
		$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat_with_brackets'),array('pass'=>array($wing_id,$party_name_id)));
		}
				else
				{
				$wing_flat = "";
				$party_name = $collection['new_cash_bank']['party_name_id'];
				$bill_reference = @$collection['new_cash_bank']['bill_reference'];	
				}
				$amount=$collection['new_cash_bank']['amount'];
				$flat_id = $collection['new_cash_bank']['flat_id'];
				$deposited_bank_id = (int)$collection['new_cash_bank']['deposited_bank_id'];
				$current_date = $collection['new_cash_bank']['current_date'];
				if($receipt_mode == "Cheque")
				{
				$receipt_mode = $receipt_mode;
				}
			
			
			$ledger_sub_account_fetch_result = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($deposited_bank_id)));			
			foreach($ledger_sub_account_fetch_result as $rrrr)
			{
				$deposited_bank_name = $rrrr['ledger_sub_account']['name'];	
			}			
			
		if($s_role_id == 3)
		{
			$TransactionDate = date('d-m-Y',$TransactionDate);
			$total_debit =  $total_debit + $amount; 
		if(empty($reference_utr))
		{
			$reference_utr = $reference_utr;
		}
?>
<tr>
<td><?php echo $receipt_no; ?> </td>
<td><?php echo $TransactionDate; ?></td>
<td><?php echo $party_name; ?></td>
<td> <?php echo $wing_flat; ?></td>
<td><?php echo $receipt_mode; ?> - <?php echo $drawn_on_which_bank; ?></td>
<td><?php echo @$reference_utr; ?> </td>
<td><?php echo $deposited_bank_name; ?> </td>
<td><?php echo $narration; ?> </td>
<td align='right'><?php 
$amount = number_format($amount);
echo $amount; ?></td>
<td class="hide_at_print"><center>
<a href="bank_receipt_html_view/<?php echo $receipt_no; ?>" target="_blank" class="btn mini yellow"><i class="icon-search"></i></a>
<a href="b_receipt_edit/<?php echo $receipt_no; ?>" class="btn mini blue"><i class="icon-edit"></i></a> 
<a href="bank_receipt_pdf/<?php echo $receipt_no; ?>" target="_blank"   class="btn mini purple"><i class="icon-file"></i></a></center>
</td>
</tr>
<?php	
}		 
}

?>
<tr>

<td colspan="8" style="text-align:right;"><b>Total</b></td>
<td align="right"><b><?php 
$total_debit = number_format($total_debit);
echo $total_debit; ?> <?php //echo "  dr"; ?></b></td>
<td class="hide_at_print"></td>
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
			 