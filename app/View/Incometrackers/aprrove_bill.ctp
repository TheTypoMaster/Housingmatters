<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>	
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<style>
th{
	font-size: 10px !important;background-color:#FCE4BF;
}
th,td{
	padding:2px;
	font-size: 12px;border:solid 1px #FFB848;
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
<?php 
foreach($result_society as $data){
	$income_heads=$data["society"]["income_head"];
}
?>
<form method="post" >
<div class="portlet-body" style="background-color: #fff; overflow-x: auto;overflow-y:hidden;" align="center">
<table >
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
			<th><input style="opacity: 0;" value="" type="checkbox" id="select_all" onclick="select_all_check()"></th>
		</tr>
	</thead>
	<tbody>
	<?php 
	if(sizeof($result_new_regular_bill)>0){
		foreach($result_new_regular_bill as $data3){
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
			<td>
			<label class="checkbox">
					<span><input style="opacity: 0;" value="1" name="check<?php echo $auto_id; ?>" class="group_check1" type="checkbox" /></span>
				</label>
			</td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
<button type="submit" name="approve" class="btn green">APPROVE BILLS</button>
</div> 
</form>









<script>
function select_all_check(){
	$(document).ready(function() {
		if($("#select_all").is(":checked")==true){
			$(".group_check1").parent('span').addClass('checked');
			$(".group_check1").prop('checked',true);
		}else{
			$(".group_check1").parent('span').removeClass('checked');
			$(".group_check1").prop('checked',false);
		}
	});
}
</script>
							