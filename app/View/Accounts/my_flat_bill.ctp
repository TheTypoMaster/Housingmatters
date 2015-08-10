<?php 
foreach($result_society as $data){
	$society_name=$data["society"]["society_name"];
	$society_reg_num=$data["society"]["society_reg_num"];
	$society_address=$data["society"]["society_address"];
	$society_email=$data["society"]["society_email"];
	$society_phone=$data["society"]["society_phone"];
}

$result_opening_balance= $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_opening_balance_via_user_id'),array('pass'=>array($s_user_id)));



?>
 <style>
#report_tb th{
	font-size: 12px !important;background-color:#C8EFCE;padding:2px;border:solid 1px #55965F;
}
#report_tb td{
	padding:2px;
	font-size: 14px;border:solid 1px #55965F;background-color:#FFF;
}
</style>



<div align="center">
	<table>
		<tr>
		<td><input class="date-picker m-wrap medium" id="from" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo date("d-m-Y",strtotime($from)); ?>" type="text"></td>
		<td><input class="date-picker  m-wrap medium" id="to" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo date("d-m-Y",strtotime($to)); ?>" type="text"></td>
		<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
		</tr>
	</table>
</div>



<br/>
<div style="width:80%;margin:auto;overflow:auto;background-color:#FFF;padding:5px;" id="result_statement">
	<div align="center"><h4><?php echo strtoupper($society_name); ?></h4></div>
	<div class="row-fluid" style="font-size:14px;">
		<div class="span6">
			<span style="font-size:16px;">Statement of Account</span><br/>
			<span style="font-size:12px;">From <?php echo date("d-m-Y",strtotime($from)); ?> to <?php echo date("d-m-Y",strtotime($to)); ?></span><br/>
			For : <?php echo $user_name; ?>
		</div>
		<div class="span6" align="right">
			Regn # <?php echo $society_reg_num; ?><br/>
			<?php echo $society_address; ?><br/>
			Email: <?php echo $society_email; ?> | Phone : <?php echo $society_phone; ?>
		</div>
	</div>
	<div>
		<table id="report_tb" width="100%">
			<tr>
				<th>Date</th>
				<th>Description</th>
				<th>Credit</th>
				<th>Amount</th>
			</tr>
			<?php $no_record=0; ?>
			<?php foreach($result_opening_balance as $data){
				$opening_blalance_date= date('Y-m-d',$data["ledger"]["op_date"]->sec);
				$opening_blalance_date= date("d-M-Y",strtotime($opening_blalance_date));
				$opening_blalance_compare_date= date("Y-m-d",strtotime($opening_blalance_date));
				$penalty=@$data["ledger"]["penalty"];
				$amount=@$data["ledger"]["amount"];
				$amount_category_id=@$data["ledger"]["amount_category_id"];
				if(strtotime($opening_blalance_compare_date)>=$from && strtotime($opening_blalance_compare_date)<=$to){
					$no_record=1;
			?>
			<tr>
				<td><?php echo $opening_blalance_date; ?></td>
				<td>
				<?php if($penalty=="YES"){ 
					echo 'Opening Balance (Intrest Arrears)';
				}else{
					echo 'Opening Balance (Maintenance Arrears)';
				} ?>
				</td>
				<td align="right"><?php if($amount_category_id==2){ echo $amount; }else{ echo "-"; } ?></td>
				<td align="right"><?php if($amount_category_id==1){ echo $amount; }else{ echo "-"; } ?></td>
			</tr>
			<?php } } ?>
			<?php
			foreach($result_new_regular_bill as $regular_bill){
				$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
				$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
				$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
				$due_for_payment=$regular_bill["new_regular_bill"]["due_for_payment"];
				$no_record=1;
				?>
				<tr>
					<td><?php echo date("d-M-Y",$bill_start_date); ?></td>
					<td>Bill for <?php echo date("M-Y",$bill_start_date); ?></td>
					<td align="right">-</td>
					<td align="right"><?php echo $due_for_payment; ?></td>
				</tr>
				<?php
				$result_new_cash_bank = $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_last_receipt_info_via_flat_id'),array('pass'=>array($flat_id,$one_time_id)));
					if(sizeof($result_new_cash_bank)>=1){
						foreach($result_new_cash_bank as $last_receipt){
							$receipt_date=@$last_receipt["new_cash_bank"]["receipt_date"]; 
							$receipt_amount=$last_receipt["new_cash_bank"]["amount"];
						}
						?>
						<tr>
							<td><?php echo date("d-M-Y",$receipt_date); ?></td>
							<td>Receipt</td>
							<td align="right"><?php echo $receipt_amount; ?></td>
							<td align="right">-</td>
						</tr>
						<?php
					}
			}
			?>
			<?php if($no_record==0){
				?>
				<tr><td colspan="4" align="center">No Record Found</td></tr>
				<?php
			} ?>
		</table>
	</div>
</div>

<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		var from=$("#from").val();
		var to=$("#to").val();
		$("#result_statement").html('<div align="center"><h4>Loading...</h4></div>').load('my_flat_bill_ajax/'+from+'/'+to);
	});
});
</script>