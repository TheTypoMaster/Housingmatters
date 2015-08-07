<?php
foreach($result_society as $data){
	$income_heads=$data["society"]["income_head"];
}
?>
<table id="report_tb">
	<thead>
		<tr>
			<th>Unit Number</th>
			<th>Name</th>
			<th>Area</th>
			<th>Bill No.</th>
			<?php foreach($income_heads as $income_head){ 
			$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head)));	
			foreach($result_income_head as $data2){
				$income_head_name = $data2['ledger_account']['ledger_name'];
			} ?>
			<th><?php echo $income_head_name; ?></th>	
			<?php } ?>
			<th>Non Occupancy charges</th>
			<th>Total</th>
			<th>Arrears (Maint.)</th>
			<th>Arrears (Int.)</th>
			<th>Interest on Arrears </th>
			<th>Due For Payment</th>
			<th>View</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach($result_new_regular_bill as $regular_bill){
	$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
	$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
	$bill_end_date=$regular_bill["new_regular_bill"]["bill_end_date"];
	$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
	$bill_no=$regular_bill["new_regular_bill"]["bill_no"];
	$income_head_array=$regular_bill["new_regular_bill"]["income_head_array"];
	$noc_charges=$regular_bill["new_regular_bill"]["noc_charges"];
	$total=$regular_bill["new_regular_bill"]["total"];
	$arrear_maintenance=$regular_bill["new_regular_bill"]["arrear_maintenance"];
	$arrear_intrest=$regular_bill["new_regular_bill"]["arrear_intrest"];
	$intrest_on_arrears=$regular_bill["new_regular_bill"]["intrest_on_arrears"];
	$due_for_payment=$regular_bill["new_regular_bill"]["due_for_payment"];
	//wing_id via flat_id//
	$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array($flat_id)));
	foreach($result_flat_info as $flat_info){
		$wing_id=$flat_info["flat"]["wing_id"];
	}
	
	$wing_flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));
	
	//user info via flat_id//
	$result_user_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($flat_id)));
	foreach($result_user_info as $user_info){
		$user_name=$user_info["user"]["user_name"];
	}
	
	$result_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array(@$flat_id,$wing_id))); 
	foreach($result_flat as $data2){
		$flat_type_id = (int)$data2['flat']['flat_type_id'];
		$noc_ch_id = (int)$data2['flat']['noc_ch_tp'];
		$sq_feet = (int)$data2['flat']['flat_area'];
	}
	?>
	<tr>
		<td><?php echo $wing_flat; ?></td>
		<td><?php echo $user_name; ?></td>
		<td><?php echo $sq_feet; ?></td>
		<td><?php echo $bill_no; ?></td>
		<?php foreach($income_heads as $income_head){ 
		$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head)));	
		foreach($result_income_head as $data2){
			$income_head_name = $data2['ledger_account']['ledger_name'];
			$income_head_id = $data2['ledger_account']['auto_id'];
		} ?>
		<td><?php echo $income_head_array[$income_head_id]; ?></td>	
		<?php } ?>
		<td><?php echo $noc_charges; ?></td>
		<td><?php echo $total; ?></td>
		<td><?php echo $arrear_maintenance; ?></td>
		<td><?php echo $arrear_intrest; ?></td>
		<td><?php echo $intrest_on_arrears; ?></td>
		<td><?php echo $due_for_payment; ?></td>
		<td><a href="#" class="btn mini yellow"><i class="icon-search"></i></a></td>
	</tr>
		
	<?php
}
?>
	</tbody>
</table>