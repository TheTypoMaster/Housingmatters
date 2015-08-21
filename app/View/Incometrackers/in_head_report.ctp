<div class="hide_at_print">	
<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));

foreach($result_society as $data){
	$income_heads=$data["society"]["income_head"];
}
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
</div>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
<style>
#report_tb th{
	font-size: 10px !important;background-color:#C8EFCE;padding:2px;border:solid 1px #55965F;
}
#report_tb td{
	padding:2px;
	font-size: 12px;border:solid 1px #55965F;background-color:#FFF;
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
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		

<div style="text-align:center;" class="hide_at_print">
<a href="<?php echo $webroot_path; ?>Incometrackers/in_head_report" class="btn yellow" rel='tab'>Bill Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_regular" class="btn" rel='tab'>Regular Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_supplimentry" class="btn" rel='tab'>Supplementary Report</a>
<!--<a href="<?php //echo $webroot_path; ?>Incometrackers/income_heads_report" class="btn" rel='tab'>Income head report</a>-->
<a href="<?php echo $webroot_path; ?>Incometrackers/account_statement" class="btn" rel='tab'>Account Statement</a>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php 
if(sizeof($result_new_regular_bill)>0){
	foreach($result_new_regular_bill as $regular_bill){
		$auto_id=$regular_bill["new_regular_bill"]["auto_id"];
		$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
		
		$array_for_select_box[$auto_id]=$one_time_id;
	}
	$array_for_select_box=array_unique($array_for_select_box);
}else{
	?>
	<div align="center" style="font-size:16px;">
		<br/><br/>No any bill rised.
	</div>
	<?php
	exit;
} 



?>
    
           <div class="hide_at_print" align="center">
           <table border="0">
           <tr>
           <td>
           <select name="period" class="m-wrap large" id="un">
           <?php
		   $count=0;
		   foreach($array_for_select_box as $key=>$value)
		   { $count++;
			   if($count==1){ $last_one_time_id=$value; };
		   foreach($result_new_regular_bill as $regular_bill){
				$auto_id=$regular_bill["new_regular_bill"]["auto_id"];
				
				if($auto_id==$key){
					$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
					$bill_end_date=$regular_bill["new_regular_bill"]["bill_end_date"];
				}
			}
		   ?>
           <option value="<?php echo $value; ?>"><?php echo date("d-M",$bill_start_date); ?> to <?php echo date("d-M-Y",$bill_end_date); ?></option>
           <?php } ?>
           </select>
           </td>
           <td>
           <button class="btn yellow" id="go" style="margin-bottom:10px;">Go</button>
           </td>
           </tr>
           </table>
           <div id="validate_result"></div> 
           </div>

<?php 
foreach($result_new_regular_bill as $regular_bill){
	$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
	if($one_time_id==$last_one_time_id){
		$income_head_array=$regular_bill["new_regular_bill"]["income_head_array"];
	}
} ?>
<br />
<div style="width:100%;" id="result" align="center">
<div align="right">
<a href="in_head_excel?one=<?php echo @$last_one_time_id; ?>" class="btn blue">Export</a>
<a href="print_all_bill/<?php echo @$last_one_time_id; ?>" target="_blank" class="btn purple"><i class="icon-print"></i> Print All</a>
</div>
<table id="report_tb">
	        <thead>
		    <tr>
			<th>Unit Number</th>
			<th>Name</th>
			<th>Area</th>
			<th>Bill No.</th>
			<?php foreach($income_head_array as $income_head=>$value){ 
			$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head)));	
			foreach($result_income_head as $data2){
				$income_head_name = $data2['ledger_account']['ledger_name'];
			} ?>
			<th><?php echo $income_head_name; ?></th>	
			<?php } ?>
			<th>Non Occupancy charges</th>
			
			<?php foreach($other_charges_ids as $other_charges_id){
				$result_income_head = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($other_charges_id)));	
					foreach($result_income_head as $data2){
						$income_head_name = $data2['ledger_account']['ledger_name'];
					}
				?>
				<th><?php echo $income_head_name; ?></th>
				<?php
			} ?>
			<th>Total</th>
			<th>Arrears (Maint.)</th>
			<th>Arrears (Int.)</th>
			<th>Interest on Arrears </th>
			<th>Due For Payment</th>
			<th>View|Edit</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach($result_new_regular_bill as $regular_bill){
	$one_time_id=$regular_bill["new_regular_bill"]["one_time_id"];
	if($one_time_id==$last_one_time_id){
		$auto_id=$regular_bill["new_regular_bill"]["auto_id"];
		$bill_start_date=$regular_bill["new_regular_bill"]["bill_start_date"];
		$bill_end_date=$regular_bill["new_regular_bill"]["bill_end_date"];
		$flat_id=$regular_bill["new_regular_bill"]["flat_id"];
		$bill_no=(int)$regular_bill["new_regular_bill"]["bill_no"];
		$income_head_array=$regular_bill["new_regular_bill"]["income_head_array"];
		$noc_charges=$regular_bill["new_regular_bill"]["noc_charges"];
		$other_charges_array=$regular_bill["new_regular_bill"]["other_charges_array"];
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
			$noc_ch_id = (int)@$data2['flat']['noc_ch_tp'];
			$sq_feet = (int)$data2['flat']['flat_area'];
		}
		?>
		<tr>
			<td><?php echo $wing_flat; ?></td>
			<td><?php echo $user_name; ?></td>
			<td><?php echo $sq_feet; ?></td>
			<td><?php echo $bill_no; ?></td>
			<?php foreach($income_head_array as $income_head=>$value){ 
			 ?>
			<td><?php echo $value; ?></td>	
			<?php } ?>
			<td><?php echo $noc_charges; ?></td>
			<?php foreach($other_charges_ids as $other_charges_id){
				?>
				<td><?php echo @(int)$other_charges_array[$other_charges_id]; ?></td>
				<?php
			} ?>
			<td><?php echo $total; ?></td>
			<td><?php echo $arrear_maintenance; ?></td>
			<td><?php echo $arrear_intrest; ?></td>
			<td><?php echo $intrest_on_arrears; ?></td>
			<td><?php echo $due_for_payment; ?></td>
			<td><a href="regular_bill_view/<?php echo $auto_id; ?>" target="_blank" class="btn mini yellow"><i class="icon-search"></i></a>
			<a href="regular_bill_edit2/<?php echo $auto_id; ?>" role="button" rel='tab' class="btn mini blue"><i class="icon-edit"></i></a>
  
            </td>
		</tr>
			
		<?php
	}
}
?>
	</tbody>
</table>
</div>


<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$("#go").bind('click',function(){
	var unic = document.getElementById('un').value;
	
if(unic === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Selectan Option</div>'); return false; }
else 
{
$('#validate_result').html('<div></div>');
}

$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loding....</div>').load("in_report_ajax?un=" +unic+ "");
});





});
</script>	
<?php 
$bill_updated=(int)$this->Session->read('bill_updated');
if($bill_updated==1){ ?>
<script>
$(document).ready(function() {
	$.gritter.add({
		title: '<i class="icon-plus-sign"></i> Income Tracker',
		text: '<p>Bill Updated Successfully</p>',
		sticky: false,
		time: '10000',
	});
});
</script>
<?php }
$this->requestAction(array('controller' => 'hms', 'action' => 'griter_notification'), array('pass' => array(1111)));
?>

