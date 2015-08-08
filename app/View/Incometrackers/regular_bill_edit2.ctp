<?php 
foreach($result_new_regular_bill as $regular_bill){
	$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
	$bill_no=$regular_bill["new_regular_bill"]["bill_no"];
	echo $bill_start_date=date('d-m-Y',strtotime($regular_bill["new_regular_bill"]["bill_start_date"]));
	echo $bill_end_date=date('Y-m-d',$regular_bill["new_regular_bill"]["bill_end_date"]);
	
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
				<td><?php echo date("Y-m-d",$bill_start_date); ?></td>
				<td width="10%">Area:</td>
				<td><?php echo $sq_feet; ?></td>
			</tr>
		</table>
	</div>
</div>
