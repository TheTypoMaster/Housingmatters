<?php 
foreach($result_society as $data){
	$society_name=$data["society"]["society_name"];
	$society_reg_num=$data["society"]["society_reg_num"];
	$society_address=$data["society"]["society_address"];
	$society_email=$data["society"]["society_email"];
	$society_phone=$data["society"]["society_phone"];
}



foreach($result_ledger as $ledger_data){
	$table_name=$ledger_data["ledger"]["table_name"];
	$debit=$ledger_data["ledger"]["debit"];
	$credit=$ledger_data["ledger"]["credit"];
	$credit=$ledger_data["ledger"]["credit"];
	$arrear_int_type=@$ledger_data["ledger"]["arrear_int_type"];
	if($table_name=="opening_balance"){
		if($arrear_int_type=="YES"){
			$opening_balance_int=$debit+$credit;
		}else{
			$opening_balance=$debit+$credit;
		}
	}
	
}
?>
<style>
#report_tb th{
	font-size: 14px !important;background-color:#C8EFCE;padding:5px;border:solid 1px #55965F;text-align: left;
}
#report_tb td{
	padding:5px;
	font-size: 15px;border:solid 1px #55965F;background-color:#FFF;
}
table#report_tb tr:hover td {
background-color: #E6ECE7;
}
</style>

	
	<div>
	
		<table id="report_tb" width="100%" border="1">
			<tr>
				<td colspan="7">
				<span style="font-size:16px;">From <?php echo date("d-m-Y",strtotime($from)); ?> to <?php echo date("d-m-Y",strtotime($to)); ?></span>
				</td>
			</tr>
			<tr>
				<th>Date</th>
				<th>Reference</th>
				<th>Description</th>
				<th>Maint. Charges</th>
				<th>Interest</th>
				<th>Credits</th>
				<th>Account Balance</th>
			</tr>
			<?php 
			if(sizeof($result_ledger)==0){
				?>
				<tr>
					<td colspan="7" align="center">No Record Found for above selected period.</td>
				</tr>
				<?php
			}
			$account_balance=0; $total_maint_charges=0; $total_interest=0; $total_credits=0;  $total_account_balance=0; 
			foreach($result_ledger as $ledger_data){ 
				$transaction_date=$ledger_data["ledger"]["transaction_date"];
				$table_name=$ledger_data["ledger"]["table_name"];
				$element_id=$ledger_data["ledger"]["element_id"];
				$debit=$ledger_data["ledger"]["debit"];
				$credit=$ledger_data["ledger"]["credit"];
				$credit=$ledger_data["ledger"]["credit"];
				$arrear_int_type=@$ledger_data["ledger"]["arrear_int_type"];
				$intrest_on_arrears=@$ledger_data["ledger"]["intrest_on_arrears"];
				if($table_name=="opening_balance"){
					$description="Opening Balance/Arrears";
					$refrence_no="";
					if($arrear_int_type=="YES"){
						$maint_charges="";
						$interest=$debit+$credit;
						$account_balance=$account_balance+(int)$interest;
					}else{
						$interest="";
						$maint_charges=$debit+$credit;
						$account_balance=$account_balance+(int)$maint_charges;
					}
					$credits="";
					
					
				}
				if($table_name=="new_regular_bill"){
					$result_regular_bill=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'regular_bill_info_via_auto_id'), array('pass' => array($element_id)));
					if(sizeof($result_regular_bill)>0){
						$bill_approved="yes";
						$refrence_no = $result_regular_bill[0]["new_regular_bill"]["bill_no"];
						$description = $result_regular_bill[0]["new_regular_bill"]["description"];
					}
					
					
					if($intrest_on_arrears=="YES"){
						$maint_charges="";
						$interest=$debit+$credit;
						$account_balance=$account_balance+(int)$interest;
					}else{
						$interest="";
						$maint_charges=$debit+$credit;
						$account_balance=$account_balance+(int)$maint_charges;
					}
					$credits="";
				}
				if($table_name=="new_cash_bank"){
					
					$element_id=$element_id+1000;
					
					$result_cash_bank=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'receipt_info_via_auto_id'), array('pass' => array($element_id)));
					$refrence_no=$result_cash_bank[0]["new_cash_bank"]["receipt_id"]; 
					$flat_id = (int)$result_cash_bank[0]["new_cash_bank"]["party_name_id"];
					$description = @$result_cash_bank[0]["new_cash_bank"]["narration"];
			
					
					$interest="";
					$maint_charges="";
					$credits=$debit+$credit;
					$account_balance=$account_balance-(int)$credits;
				} 
				$total_maint_charges=$total_maint_charges+(int)$maint_charges;
				$total_interest=$total_interest+(int)$interest;
				$total_credits=$total_credits+(int)$credits;
				?>
					<tr>
						<td><?php echo date("d-m-Y",$transaction_date); ?></td>
						<td>
						<?php if($table_name=="new_regular_bill"){
							echo $refrence_no;
						}
						if($table_name=="new_cash_bank"){
							echo $refrence_no;
						} ?>
						</td>
						<td><?php echo $description; ?></td>
						<td><?php echo $maint_charges; ?></td>
						<td><?php echo $interest; ?></td>
						<td><?php echo $credits; ?></td>
						<td><?php echo $account_balance; ?></td>
					</tr>
				
			<?php } ?>
					<tr>
						<td colspan="3" align="right"><b>Total</b></td>
						<td><b><?php echo $total_maint_charges; ?></b></td>
						<td><b><?php echo $total_interest; ?></b></td>
						<td><b><?php echo $total_credits; ?></b></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="6" align="right" style="color:#33773E;"><b>Closing Balance</b></td>
						<td style="color:#33773E;"><b><?php echo $account_balance; ?></b></td>
					</tr>
		</table>
	</div>
