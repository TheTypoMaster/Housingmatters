
<style>
#report_tb th{
	font-size: 10px !important;background-color:#C8EFCE;padding:2px;border:solid 1px #55965F;
}
#report_tb td{
	padding:2px;
	font-size: 12px;border:solid 1px #55965F;background-color:#FFF;
}
</style>



<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$bbb = 55;
foreach($cursor1 as $data3){
			$auto_id=$data3["new_regular_bill"]["auto_id"];
			$flat_id=$data3["new_regular_bill"]["flat_id"];
			$bill_no=(int)$data3["new_regular_bill"]["bill_no"];
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

 
if($wise == 2)
{									
if($flat_id == $user_id)
{
$bbb = 555;
}
}
else if($wise == 1)
{
if($wing_id == $wing)
{	

$bbb = 555;

}
}
else if($wise == 3)
{
if($bill_number == $flat_id)
{	
$bbb = 555;	
}
}
}
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php 
if($bbb == 555)
{
?>
<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<?php
if($wise == 1)
{
?>
<a href="regular_bill_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>&w=<?php echo $wise; ?>&wi=<?php echo $wing; ?>" class="btn blue">Export in Excel</a>
<?php
}
else if($wise == 2)
{
?>
<a href="regular_bill_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>&w=<?php echo $wise; ?>&u=<?php echo $user_id; ?>" class="btn blue">Export in Excel</a>
<?php	
}
else if($wise == 3)
{
?>
<a href="regular_bill_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>&w=<?php echo $wise; ?>&u=<?php echo $bill_number; ?>" class="btn blue">Export in Excel</a>
<?php	
}
?>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />
<table id="report_tb">
<tr>
<th>Unit Number</th>
<th>Name</th>
<th>Area</th>
<th>Bill No.</th>
<th>Total</th>
<th>Arrears (Maint.)</th>
<th>Arrears (Int.)</th>
<th>Interest on Arrears </th>
<th>Due For Payment</th>
<th></th>
</tr>
<?php
foreach($cursor1 as $data3){
			$auto_id=$data3["new_regular_bill"]["auto_id"];
			$flat_id=$data3["new_regular_bill"]["flat_id"];
			$bill_no = (int)$data3["new_regular_bill"]["bill_no"];
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

 
if($wise == 2)
{									
if($flat_id == $user_id)
{
//if($m_from1 <= $date2 && $m_to1 >= $date2)
//{
?>
<tr>
<td><?php echo $wing_flat; ?></td>
<td><?php echo $user_name; ?></td>
<td><?php echo $sq_feet; ?></td>
<td><?php echo $bill_no; ?></td>
<td><?php echo $total; ?></td>
<td><?php echo $arrear_maintenance; ?></td>
<td><?php echo $arrear_intrest; ?></td>
<td><?php echo $intrest_on_arrears; ?></td>
<td><?php echo $due_for_payment; ?></td>
<td><a href="regular_bill_pdf/<?php echo $bill_no; ?>" target="_blank" class="btn mini yellow">Pdf</a></td>
</tr>
<?php 
//}
}
}
else if($wise == 1)
{
if($wing_id == $wing)
{	
//if($m_from1 <= $date2 && $m_to1 >= $date2)
//{
//$date = date('d-m-Y',strtotime($date));						
//$grand_total = $grand_total + $g_total;	
?>	
<tr>
<td><?php echo $wing_flat; ?></td>
<td><?php echo $user_name; ?></td>
<td><?php echo $sq_feet; ?></td>
<td><?php echo $bill_no; ?></td>
<td><?php echo $total; ?></td>
<td><?php echo $arrear_maintenance; ?></td>
<td><?php echo $arrear_intrest; ?></td>
<td><?php echo $intrest_on_arrears; ?></td>
<td><?php echo $due_for_payment; ?></td>
<td><a href="regular_bill_pdf/<?php echo $bill_no; ?>" target="_blank" class="btn mini yellow">Pdf</a></td>
</tr>	
<?php 	
//}
}
}
else if($wise == 3)
{
if($bill_number == $flat_id)
{	
//if($m_from1 <= $date1 && $m_to1 >= $date1)
//{
//$date = date('d-m-Y',strtotime($date));						
//$grand_total = $grand_total + $g_total;	
?>
<tr>
<td><?php echo $wing_flat; ?></td>
<td><?php echo $user_name; ?></td>
<td><?php echo $sq_feet; ?></td>
<td><?php echo $bill_no; ?></td>
<td><?php echo $total; ?></td>
<td><?php echo $arrear_maintenance; ?></td>
<td><?php echo $arrear_intrest; ?></td>
<td><?php echo $intrest_on_arrears; ?></td>
<td><?php echo $due_for_payment; ?></td>
<td><a href="regular_bill_pdf/<?php echo $bill_no; ?>" target="_blank" class="btn mini yellow">Pdf</a></td>
</tr>
<?php
//}
}
}
}
?>
<!--<tr>
<th colspan="6" style="text-align:right;">Grand Total</th>
<th style="text-align:right;"><?php 
//$grand_total = number_format($grand_total);
//echo $grand_total; ?></th>
<th class="hide_at_print"></th>
</tr>-->
</table>
<?php 
}
if($bbb == 55)
{
?>
<br /><br />
<center>
<h3 style="color:red;"><b>No Record Found in Selected Period</b></h3>
</center>
<br /><br />
<?php
}
?>
