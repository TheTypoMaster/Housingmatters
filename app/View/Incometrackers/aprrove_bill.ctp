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










<div style="overflow:auto;">
	<table style="width:100%; margine-left:2px; border-collapse:collapse;" border="1" cellpadding="5" cellspacing="0">
		<tr>
			<th style="width:85%; text-align:left;color: #fff;background-color: rgb(4, 126, 186);">Particulars of charges</th>
			<th style="text-align:right;color: #fff;background-color: rgb(4, 126, 186);">Amount (Rs.)</th>
		</tr>
		<tr>
		</tr>
		<tr>
			<td valign="top">
				<table style="width:100%;" border="0"><tbody><tr>
				<td style="text-align:left;">Maintenance charges</td>
				</tr><tr>
				<td style="text-align:left;"><br><br></td>
				</tr></tbody></table>
			</td>
			<td valign="top">
				<table style="width:100%;" border="0"><tbody><tr>
				<td style="text-align:right;padding-right: 8%;">5000</td>
				</tr><tr>
				<td style="text-align:left;"><br><br></td>
				</tr></tbody></table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table style="width:75%; float:left;font-size: 11px;" border="0">
				<tbody><tr>
				<td colspan="2">Cheque/NEFT payment instructions:</td>
				</tr>
				<tr>
				<td valign="top" width="30%"><b>Account Name:</b></td>
				<td>SBI</td>
				</tr>
				<tr>
				<td><b>Account No.:</b></td>
				<td>3212315616</td>
				</tr>
				<tr>
				<td><b>Bank Name:</b></td>
				<td>sbi</td>
				</tr>
				<tr>
				<td><b>Branch Name:</b></td>
				<td>hiran mangri</td>
				</tr>
				<tr>
				<td><b>IFSC no.:</b></td>
				<td>5165</td>
				</tr>
				</tbody></table>
				<table style="width:25%;" border="0"><tbody><tr>
				<td rowspan="5"></td>
				<td style="text-align:right; padding-right:2%;">Total:</td>
				</tr><tr>
				<td style="text-align:right; padding-right:2%;">Interest on arrears:</td>
				</tr><tr>
				<td style="text-align:right; padding-right:2%;">Arrears &nbsp; (Maint.):</td>
				</tr><tr>
				<td style="text-align:right; padding-right:2%;">Arrears &nbsp; (Int.):</td>
				</tr><tr>
				<th style="text-align:right; padding-right:2%;">Due For Payment:</th>
				</tr></tbody></table>
				</td>
			<td valign="top"><table style="width:100%;" border="0">
				<tbody><tr>
				<td style="text-align:right; padding-right:8%;">5,000</td>
				</tr>
				<tr>
				<td style="text-align:right; padding-right:8%;">0</td>
				</tr><tr>
				<td style="text-align:right; padding-right:8%;">0</td>
				</tr><tr>
				<td style="text-align:right; padding-right:8%;">0</td>
				</tr><tr>
				<th style="text-align:right; padding-right:8%;">5,000</th>
				</tr></tbody></table>
			</td>
		</tr>
		<tr><td colspan="2"><b>Due For Payment (in words) :</b> Rupees Five Thousand Only</td></tr>
	</table>
</div>

<div style="overflow:auto;border:solid 1px;border-bottom:none;padding:5px;border-top: none;">
<div style="width:70%;float:left;font-size: 11px;line-height: 15px;">
<span>Remarks:</span><br><span>1.  Thank You</span><br></div>
<div style="width:30%;float:right;" align="center">For  <b>Vrindavan Dham <br><br><br><div align="center"><span style="border-top: solid 1px #424141;">Society Manager</span></div></b></div><b>
</b></div><b>
<div style="color: #6F6D6D;border: solid 1px black;border-top: dotted 1px;" align="center">Note: This is a computer generated bill hence no signature required.</div>
<div style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 12px;font-weight: bold;color: #fff;vertical-align: middle;border: solid 1px #000;border-top: none;" align="center">
<span>Your Society is empowered by HousingMatters - 
<i>"Making Life Simpler"</i></span><br>
<span style="color:#FFF;">Email: support@housingmatters.in</span> &nbsp;|&nbsp; <span>Phone : 022-41235568</span> &nbsp;|&nbsp; <span style="color:#FFF;">www.housingmatters.co.in</span></div>

</b></div><b>
</b></div>


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
							