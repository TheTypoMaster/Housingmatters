<?php
$webroot_path=$this->requestAction(array('controller' => 'Hms', 'action' => 'webroot_path'));
?>
<style>
#report_tb th{
	font-size: 14px !important;background-color:#C8EFCE;padding:5px;border:solid 1px #55965F;
}
#report_tb td{
	padding:5px;
	font-size: 14px;border:solid 1px #55965F;background-color:#FFF;
}
table#report_tb tr:hover td {
background-color: #E6ECE7;
}
</style>
<div style="background-color:#fff;" >
<?php
$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ledger_account_id)));	
$ledger_account_name=$result_income_head[0]["ledger_account"]["ledger_name"];
?>
<div style="font-size:14px;">
	<span style="color:#6F6D6D;font-size:16px;">Ledger Report</span><br/>
	<span><b><?php echo $ledger_account_name; ?></b></span><br/>
	<span>From: <?php echo date("d-m-Y",strtotime($from)); ?> To: <?php echo date("d-m-Y",strtotime($to)); ?></span>
</div>
<table id="report_tb">
	<thead>
		<tr>
			<th>Sr.No.</th>
			<th>Tranjection Date</th>
			<th>Source</th>
			<th>Refrence</th>
			<th>Debit</th>
			<th>Credit</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$i=0; $total_debit=0; $total_credit=0;
	foreach($result_ledger as $data){ $i++;
		$debit=$data["ledger"]["debit"];
		$credit=$data["ledger"]["credit"];
		$tranjection_date=$data["ledger"]["tranjection_date"];
		$table_name=$data["ledger"]["table_name"];
		$element_id=(int)$data["ledger"]["element_id"];
		$refrence_no="";
		$total_debit=$total_debit+$debit;
		$total_credit=$total_credit+$credit;
		if($table_name=="new_regular_bill"){
			$source="Bill";
			$result_regular_bill=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'regular_bill_info_via_auto_id'), array('pass' => array($element_id)));
			$refrence_no=$result_regular_bill[0]["new_regular_bill"]["bill_no"]; 
		}
		if($table_name=="new_cash_bank"){
			$source="Receipt";
		}
		if($table_name=="opening_balance"){
			$source="Opening Balance";
		}
		
		
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo date("d-m-Y",$tranjection_date); ?></td>
			<td><?php echo $source; ?></td>
			<td><a href="<?php echo $this->webroot; ?>/Incometrackers/regular_bill_view/<?php echo $element_id; ?>" target="_blank"><?php echo $refrence_no; ?></a></td>
			<td><?php echo $debit; ?></td>
			<td><?php echo $credit; ?></td>
		</tr>
	<?php } ?>
		<tr>
			<td colspan="4" align="right"><b>Total</b></td>
			<td><b><?php echo $total_debit; ?></b></td>
			<td><b><?php echo $total_credit; ?></b></td>
		</tr>
	</tbody>
</table>
</div>