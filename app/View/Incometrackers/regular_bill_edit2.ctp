<style>
.textbx{
	margin-bottom: 0px !important;
	height: 15px !important;width: 100px;
}
</style>
<?php 
foreach($result_society as $data){
	$income_heads=$data["society"]["income_head"];
}
foreach($result_new_regular_bill as $regular_bill){
	$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
	$bill_no=$regular_bill["new_regular_bill"]["bill_no"];
	$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
	$due_date=$regular_bill["new_regular_bill"]["due_date"];
	$income_head_array=$regular_bill["new_regular_bill"]["income_head_array"];
	$total=$regular_bill["new_regular_bill"]["total"];
	$intrest_on_arrears=$regular_bill["new_regular_bill"]["intrest_on_arrears"];
	$arrear_maintenance=$regular_bill["new_regular_bill"]["arrear_maintenance"];
	$arrear_intrest=$regular_bill["new_regular_bill"]["arrear_intrest"];
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
}; ?>
<div class="portlet box blue">
	<div class="portlet-title">
		<h4><i class="icon-edit"></i> Edit Bill -<?php echo $bill_no; ?></h4>
	</div>
	<div class="portlet-body" style="overflow:auto;">
		<table style="width:100%; float:left;" >
			<tr>
				<td width="10%">Name: </td>
				<td><?php echo $user_name; ?></td>
				<td width="10%">Flat/Shop No.: </td>
				<td><?php echo $wing_flat; ?></td>
			</tr>
			<tr>
				<td width="10%">Bill Date:</td>
				<td><?php echo date("d-M-Y",$bill_start_date); ?></td>
				<td width="10%">Due Date:</td>
				<td><?php echo date("d-M-Y",$due_date); ?></td>
			</tr>
		</table>
		<form method="post">
		<div class="portlet-body span6">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Particulars of charges</th>
						<th>Amount (Rs.)</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($income_heads as $income_head){ 
					$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head)));	
					foreach($result_income_head as $data2){
						$income_head_name = $data2['ledger_account']['ledger_name'];
					} ?>
					<tr>
						<td><?php echo $income_head_name; ?></td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $income_head_array[$income_head]; ?>" name="income_head<?php echo $income_head; ?>"/></td>
					</tr>
					<?php } ?>
					<tr>
						<td style="text-align: right;">Total</td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $total; ?>" name="total" /></td>
					</tr>
					<tr>
						<td style="text-align: right;">Interest on arrears</td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $intrest_on_arrears; ?>" name="interest_on_arrears" /></td>
					</tr>
					<tr>
						<td style="text-align: right;">Arrears   (Maint.)</td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $arrear_maintenance; ?>" name="arrear_maintenance" /></td>
					</tr>
					<tr>
						<td style="text-align: right;">Arrears   (Int.)</td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $arrear_intrest; ?>" name="arrear_intrest" /></td>
					</tr>
					<tr>
						<td style="text-align: right;"><b>Due For Payment</b></td>
						<td><input type="text" class="m-wrap textbx" value="<?php echo $due_for_payment; ?>" name="due_for_payment" /></td>
					</tr>
				</tbody>
			</table>
			<button type="submit" name="edit_bill" class="btn green">UPDATE BILL</button>
		</div>
		
		</form>
		
		
	</div>
</div>
