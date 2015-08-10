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
	font-size: 10px !important;background-color:#C8EFCE;padding:2px;border:solid 1px #55965F;
}
#report_tb td{
	padding:2px;
	font-size: 12px;border:solid 1px #55965F;background-color:#FFF;
}
</style>
<br/>
<div style="width:80%;margin:auto;border:solid 1px #ccc;overflow:auto;background-color:#FFF;padding:5px;">
	<div align="center"><h4><?php echo strtoupper($society_name); ?></h4></div>
	<div class="row-fluid" style="font-size:14px;">
		<div class="span6">
			<span style="font-size:16px;">Statement of Account</span><br/>
			<span style="font-size:12px;">From 1-Aug-2015 to 30-Aug-2015</span>
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
			<?php foreach($result_opening_balance as $data){
				$opening_blalance_date= date('Y-m-d',$data["ledger"]["op_date"]->sec);
				$opening_blalance_date= date("d-M-Y",strtotime($opening_blalance_date));
				
				$penalty=@$data["ledger"]["penalty"];
				$amount=@$data["ledger"]["amount"];
				$amount_category_id=@$data["ledger"]["amount_category_id"];
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
			<?php } ?>
			<?php
			foreach($result_new_regular_bill as $regular_bill){
				$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
				$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
				$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
				$due_for_payment=$regular_bill["new_regular_bill"]["due_for_payment"];
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
		</table>
	</div>
</div>