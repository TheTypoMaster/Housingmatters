<?php 
$bill_start_date=date("Y-m-d", strtotime($bill_start_date));

foreach($cursor1 as $data){
	$income_heads=$data["society"]["income_head"];
	$tax=(float)$data["society"]["tax"];
	$penalty=$tax/100;
}
?>
<style>
th{
	font-size: 10px !important;background-color:#F5F5F5;
}
th,td{
	padding:2px;
	font-size: 12px;border:1px solid #C2C2C2;
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
<form method="Post" >
<div class="portlet-body" style="background-color: #fff; overflow-x: auto;" align="center">
	<table border="1">
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
			</tr>
		</thead>
		<tbody>
		<?php
            
			$regular_bill_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_one_time_id_fetch'),array('pass'=>array(1)));	
			foreach($regular_bill_fetch as $data3)
			{
			$auto_id=$data3["new_regular_bill"]["auto_id"];
			$flat_id=$data3["new_regular_bill"]["flat_id"];
			$bill_no=$data3["new_regular_bill"]["bill_no"];
			$income_head_array=$data3["new_regular_bill"]["income_head_array"];
			$noc_charges=$data3["new_regular_bill"]["noc_charges"];
			$total=$data3["new_regular_bill"]["total"];
			$arrear_maintenance=$data3["new_regular_bill"]["arrear_maintenance"];
			$arrear_intrest=$data3["new_regular_bill"]["arrear_intrest"];
			$intrest_on_arrears=$data3["new_regular_bill"]["intrest_on_arrears"];
			$due_for_payment=$data3["new_regular_bill"]["due_for_payment"];
			$bill_start_date = $data3['new_regular_bill']['bill_start_date'];
		
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
				$bill_for_user = $user_info["user"]["user_id"];
			}
		
		$result_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array(@$flat_id,$wing_id))); 
		foreach($result_flat as $data2){
		$flat_type_id = (int)$data2['flat']['flat_type_id'];
		$noc_ch_id = (int)$data2['flat']['noc_ch_tp'];
		$sq_feet = (int)$data2['flat']['flat_area'];
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<tr>
		<td><?php echo $$wing_flat; ?></td>
		<td>			</td>
		<td>			</td>
		<td>			</td>
		<td>			</td>	
		<td>			</td>
		<td>			</td>
		<td>			</td>
        <td>			</td>
		<td>			</td>
		<td>			</td>
</tr>
	<?php
			}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
			?>