<?php 
foreach ($result_receipt as $collection) 
{
$receipt_no = (int)$collection['cash_bank']['receipt_id'];
$d_date = $collection['cash_bank']['transaction_date'];
$today = date("d-M-Y");
$user_id_d = $collection['cash_bank']['user_id'];
$amount = $collection['cash_bank']['amount'];
$society_id = (int)$collection['cash_bank']['society_id'];
$bill_reference = $collection['cash_bank']['bill_reference'];
$narration = $collection['cash_bank']['narration'];
$member = (int)$collection['cash_bank']['member'];
$receiver_name = @$collection['cash_bank']['receiver_name'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$sub_account = (int)$collection['cash_bank']['account_head'];
}
$amount = str_replace( ',', '', $amount );
$am_in_words=ucwords($this->requestAction(array('controller' => 'hms', 'action' => 'convert_number_to_words'), array('pass' => array($amount))));
$date=date("d-m-Y", strtotime($d_date));

$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id_d)));
foreach($result_lsa as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
											foreach ($result as $collection) 
											{
											$wing_id = $collection['user']['wing'];  
											$flat_id = (int)$collection['user']['flat'];
											$tenant = (int)$collection['user']['tenant'];
											$user_name = $collection['user']['user_name'];
											}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action'=>'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	

foreach ($cursor2_society_id as $collection2) 
{
$society_name = $collection2['society']['society_name'];
$society_reg_no = $collection2['society']['society_reg_num'];
$society_address = $collection2['society']['society_address'];
$sig_title = $collection2['society']['sig_title'];
$merge_receipt = @$collection2['society']['merge_receipt'];
}
?>
<div style="width:100%;" class="hide_at_print">
           <span style="margin-left:90%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
            </div>



<?php
echo $bill_html;
?>
</b>
<?php if($merge_receipt==1){ ?>
<div style="width:70%;margin:auto;margin-top:2px;border:solid 1px;overflow: auto;background-color:#FFF;" class="bill_on_screen">
<div align="center"><span style="border-bottom:solid 1px;font-size:15px;font-weight:bold;">RECEIPT</span></div>
	<div style="padding:5px;overflow: auto;">
		<div style="float:left;"><b>Receipt No: </b><?php echo $receipt_no; ?></div>
		<div style="float:right;"><b>Date: </b> <?php echo $date; ?></div>
	</div>
	<div style="padding:5px;border-bottom:solid 1px;">
		Received with thanks from: <b><?php echo $user_name; ?> <?php echo $wing_flat; ?></b>
		<br/>
		Rupees <?php echo $am_in_words; ?> Only
		<br/>
		Via <?php echo $receipt_mode; ?>
		<br/>
		Payment for Bill No. <?php echo $receipt_no; ?> Dated <?php echo $date; ?>
	</div>
	<div style="padding:5px;float:left;width:65%;">
	<span style="font-size:16px;"><b>Rs <?php echo $amount; ?></b></span><br/>Subject to realization of Cheque(s)
	</div>
	<div style="float:right;width:30%;" align="center">
	For <?php echo $society_name; ?><br/><?php echo $sig_title; ?>
	</div>
</div>
<?php } ?>
<style>
@media screen {
    .bill_on_screen {
       width:70%;
    }
}

@media print {
    .bill_on_screen {
       width:96% !important;
    }
}
</style>