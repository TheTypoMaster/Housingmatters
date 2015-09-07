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
<?php 
function substrwords($text, $maxchar, $end='...') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);      
        $output = '';
        $i      = 0;
        while (1) {
            @$length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            } 
            else {
                @$output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    } 
    else {
        $output = $text;
    }
    return $output;
}
?>
<div style="overflow:auto;">
<a href="#" class="btn blue pull-right" onclick="window.print()"><i class="icon-print"></i> Print</a>
</div>
<div style="background-color:#fff;" align="center">
<?php
$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ledger_account_id)));	
$ledger_account_name=$result_income_head[0]["ledger_account"]["ledger_name"];

$result_income_head2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($ledger_sub_account_id)));
$account_number = "";
$wing_flat = "";
$sub_ledger_name=@$result_income_head2[0]["ledger_sub_account"]["name"];
if($ledger_account_id == 33)
{
$account_number = $result_income_head2[0]['ledger_sub_account']['bank_account'];	
}
if($ledger_account_id == 34)
{
$user_id = (int)$result_income_head2[0]['ledger_sub_account']['user_id'];

				$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
				foreach ($result_user as $collection) 
				{
				$user_name = $collection['user']['user_name'];  
				$wing_id = $collection['user']['wing'];
				$flat_id = $collection['user']['flat'];
				}


$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));

}

?>
<div style="font-size:14px;">
	<span style="color:#6F6D6D;font-size:16px;">LEDGER REPORT</span><br/>
	<!--<span><b><?php echo $ledger_account_name; ?> </b></span>
	<?php if(!empty($sub_ledger_name)){
		echo '<i class="icon-chevron-right" style="font-size: 11px;"></i>';
	} ?>
	
	<span ><b> <?php echo $sub_ledger_name; ?> &nbsp;&nbsp; <?php echo $account_number; ?>  <?php echo $wing_flat; ?></b></span><br/>-->
	<span>From: <?php echo date("d-m-Y",strtotime($from)); ?> To: <?php echo date("d-m-Y",strtotime($to)); ?></span>
</div>
<table id="report_tb" width="100%">
	<thead>
		<tr>
			<th>Transaction Date</th>
			<th>Source</th>
            <th>Description</th>
            <th>Party</th>
			<th>Reference</th>
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
		$transaction_date=$data["ledger"]["transaction_date"];
		$arrear_int_type=@$data["ledger"]["arrear_int_type"];
		$table_name=$data["ledger"]["table_name"];
		$element_id=(int)$data["ledger"]["element_id"];
		$refrence_no="";
		$total_debit=$total_debit+$debit;
		$total_credit=$total_credit+$credit;
		if($table_name=="new_regular_bill"){
			$source="Regular Bill";
			$result_regular_bill=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'regular_bill_info_via_auto_id'), array('pass' => array($element_id)));
			
			$bill_approved="";
			if(sizeof($result_regular_bill)>0){
				$bill_approved="yes";
				$refrence_no = $result_regular_bill[0]["new_regular_bill"]["bill_no"];
			    $description = $result_regular_bill[0]["new_regular_bill"]["description"];
				$description=substrwords($description,200,'...');
			    $flat_id = (int)$result_regular_bill[0]["new_regular_bill"]["flat_id"]; 
			
				//wing_id via flat_id//
				$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array($flat_id)));
				foreach($result_flat_info as $flat_info){
					$wing_id=$flat_info["flat"]["wing_id"];
				}
				
$user_detail = $this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'fetch_user_info_via_flat_id'), array('pass' => array($wing_id,$flat_id)));		
		foreach($user_detail as $data){
		$user_name = $data['user']['user_name'];
		$wing_id = $data['user']['wing'];
		$flat_id = $data['user']['flat'];	
		$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));
		}
			
			}
		}
		if($table_name=="new_cash_bank"){
			$source="Receipt"; 
			$element_id=$element_id+1000;
					
			$result_cash_bank=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'receipt_info_via_auto_id'), array('pass' => array($element_id)));
			$refrence_no=$result_cash_bank[0]["new_cash_bank"]["receipt_id"]; 
			$flat_id = (int)$result_cash_bank[0]["new_cash_bank"]["party_name_id"];
			$description = @$result_cash_bank[0]["new_cash_bank"]["narration"];
			$description=substrwords($description,200,'...');
			
				//wing_id via flat_id//
				$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array($flat_id)));
				foreach($result_flat_info as $flat_info){
					$wing_id=$flat_info["flat"]["wing_id"];
				}
				
				
			$user_detail = $this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'fetch_user_info_via_flat_id'), array('pass' => array($wing_id,$flat_id)));		
			foreach($user_detail as $data){
			$user_name = $data['user']['user_name'];
			$wing_id = $data['user']['wing'];
			$flat_id = $data['user']['flat'];	
			$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));
			}	
			
			
		}
		if($table_name=="opening_balance" && $arrear_int_type=="YES"){
			$source="Opening Balance (Penalty)";
			$description='Interest arrears';
		}elseif($table_name=="opening_balance"){
			$source="Opening Balance";
			$description='principle arrears';
		}
		
		if(($table_name=="new_regular_bill"  &&  $bill_approved=="yes") || $table_name=="new_cash_bank" || $table_name=="opening_balance"){
		
		?>
		<tr>
			<td><?php echo date("d-m-Y",$transaction_date); ?></td>
			<td><?php echo $source; ?></td>
            <td><?php echo @$description; ?></td>
            <td><?php echo @$user_name; ?>  <?php echo $wing_flat; ?></td>
            <td>
			<?php if($table_name=="new_regular_bill"){
				echo '<a href="'.$this->webroot.'Incometrackers/regular_bill_view/'.$element_id.'" target="_blank">'.$refrence_no.'</a>';
			}
			if($table_name=="new_cash_bank"){
				echo '<a href="'.$this->webroot.'Cashbanks/bank_receipt_html_view/'.$element_id.'" target="_blank">'.$refrence_no.'</a>';
			} ?>
			</td>
			<td><?php echo $debit; ?></td>
			<td><?php echo $credit; ?></td>
		</tr>
	<?php } } ?>
		<tr>
			<td colspan="5" align="right"><b>Total</b></td>
			<td><b><?php echo $total_debit; ?></b></td>
			<td><b><?php echo $total_credit; ?></b></td>
		</tr>
	</tbody>
</table>
</div>
hello rohit