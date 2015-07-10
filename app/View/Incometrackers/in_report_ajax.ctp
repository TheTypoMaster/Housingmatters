<?php
$n=1;
foreach($cursor1 as $collection)
{
if($n == 1)
{
$from_dm = $collection['regular_bill']['bill_daterange_from'];
$to_dm = $collection['regular_bill']['bill_daterange_to'];
$curr_dm = $collection['regular_bill']['date'];
$due_dm = $collection['regular_bill']['due_date'];
$ih_arr = $collection['regular_bill']['ih_detail'];
}
$n++;
}

$from = date('d-M-Y',strtotime($from_dm));
$to = date('d-M-Y',strtotime($to_dm));
$cur_date11 = date('d-M-Y',strtotime($curr_dm));
$due_date11 = date('d-M-Y',strtotime($due_dm));
?>

<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="in_head_excel?f=<?php echo $un; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />


<div style="width:100%; overflow:auto; background-color:white;">
<br /><br />
<table Border="0" style="width:100%;">
<tr>
<th style="text-align:center;" colspan="2">
<p style="font-size:30px;"><?php echo $society_name; ?></p>
</th>
</tr>
<tr>
<td colspan="2" style="text-align:center;">
<p style="font-size:16px;">
<?php echo $society_reg_nu; ?>
</p>
</td>
</tr>
<tr>
<td colspan="2" style="text-align:center;">
<p style="font-size:16px;"><?php echo $society_address; ?></p>
</td>
</tr>
<tr>
<th style="text-align:center;">
<p style="font-size:16px;">
Bill for date From :<?php echo $from; ?> To : <?php echo $to; ?>    
</p>
</th>
<td>
<table border="0">
<tr>
<td>Bill Date:</td><td><?php echo $cur_date11; ?></td>
</tr>
<tr>
<td>Due date:</td><td><?php echo $due_date11; ?></td>
</tr>
</table>
</td>
</tr>
</table>
<br /><br />
<table class="table table-bordered" style="width:100%;">
<tr>
<th style="text-align:left;">Sr.No.</th>
<th style="text-align:left;">Bill No.</th>
<th style="text-align:left;">Name of Resident</th>
<th style="text-align:left;">Unit No.</th>
<?php
for($k=0; $k<sizeof($ih_arr); $k++)
{
$sub_arr = $ih_arr[$k];
$ih_id1 = (int)$sub_arr[0];

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih_id1)));
foreach($result as $collection)
{
$in_name = $collection['ledger_account']['ledger_name'];	
}
if($ih_id1 != 43)
{
$ih_tt_amt[] = 0;
?>
<th style="text-align:left;"><?php echo $in_name; ?></th>
<?php }} ?>
<th style="text-align:left;">Non Occupancy charges</th>
<th style="text-align:left;">Total</th>
<th style="text-align:left;">Interest on Arrears</th>
<th style="text-align:left;">Arrears (Maint.)</th>
<th style="text-align:left;">Arrears (Int.)</th>
<th style="text-align:left;">Due For Payment</th>
</tr>

<?php

$m=0;
$tt_current_amt = 0;
$tt_over_due_amt = 0;
$total_penalty_amt = 0;
$tt_gt_amt = 0;
$tt_noc_amt = 0;
$total_arrears_amt = 0;
$total_arrears_penalty = 0;
foreach($cursor1 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$current_amt = $collection['regular_bill']['current_bill_amt'];
$over_due_amt = $collection['regular_bill']['due_amount'];
$penalty_amt = $collection['regular_bill']['current_tax'];
$gt_amt = $collection['regular_bill']['g_total'];
$ih_det = $collection['regular_bill']['ih_detail'];
$arrears_amt = $collection['regular_bill']['arrears_amt2'];
$accumulated_tax = $collection['regular_bill']['arrear_interest'];


$int_show_arrears = $accumulated_tax - $penalty_amt;


$total_arrears_amt = $total_arrears_amt + $arrears_amt;
$total_arrears_penalty = $total_arrears_penalty + $int_show_arrears;


$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($result2 as $collection)
{
$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];
$flat_id = (int)$collection['user']['flat'];
}

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));


$tt_current_amt = $tt_current_amt + $current_amt;
$tt_over_due_amt = $tt_over_due_amt + $over_due_amt;
$total_penalty_amt = $total_penalty_amt + $penalty_amt;
$tt_gt_amt = $tt_gt_amt + $gt_amt;
$m++;
?>
<tr>
<td style="text-align:right;"><?php echo $m; ?></td>
<td style="text-align:right;"><?php echo $bill_no; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $wing_flat; ?></td>
<?php
for($x=0; $x<sizeof($ih_det); $x++)
{
$charge3 = $ih_det[$x];
$ih_id5 = (int)$charge3[0];
if($ih_id5 != 43)
{	
$amt = $charge3[1];
$ih_tt_amt[$x] = $ih_tt_amt[$x] + $amt;
?>
<td style="text-align:right;"><?php 
$amt = number_format($amt);
echo $amt; ?></td>
<?php
}
}
$n=5;
for($y=0; $y<sizeof($ih_det); $y++)
{
$charge4 = $ih_det[$y];
$ih_id6 = (int)$charge4[0];
if($ih_id6 == 43)
{
$n=55;
$amt2 = $charge4[1];
$tt_noc_amt = $tt_noc_amt + $amt2;
?>
<td style="text-align:right;"><?php 
$amt2 = number_format($amt2);
echo $amt2; ?></td>	
<?php
}
}
if($n == 5)
{
?>
<td style="text-align:right;"><?php echo "0"; ?></td>	
<?php
}
?>
<td style="text-align:right;"><?php 
$current_amt = number_format($current_amt);
echo $current_amt; ?></td>


<td style="text-align:right;"><?php if(!empty($penalty_amt)) { 
$penalty_amt = number_format($penalty_amt);
echo $penalty_amt; } else { echo "0"; } ?></td>
<td style="text-align:right;"><?php 
$arrears_amt2 = number_format($arrears_amt);
echo $arrears_amt2; ?></td>
<td style="text-align:right;"><?php 
$int_show_arrears2 = number_format($int_show_arrears);
echo $int_show_arrears2; ?></td>
<td style="text-align:right;"><?php 
$gt_amt = number_format($gt_amt);
echo $gt_amt; ?></td>
</tr>
<?php } ?>
<tr>
<th colspan="4" style="text-align:right;">Total</th>
<?php
for($v=0; $v<sizeof(@$ih_tt_amt); $v++)
{
$tt_amt = $ih_tt_amt[$v];	
?>
<th style="text-align:right;"><?php 
$tt_amt = number_format($tt_amt);
echo $tt_amt; ?></th>
<?php } ?>
<th style="text-align:right;"><?php 
$tt_noc_amt = number_format($tt_noc_amt);
echo $tt_noc_amt; ?></th>

<th style="text-align:right;"><?php 
$tt_current_amt = number_format($tt_current_amt);
echo $tt_current_amt; ?></th>

<th style="text-align:right;"><?php 
$total_penalty_amt = number_format($total_penalty_amt);
echo $total_penalty_amt; ?></th>

<th style="text-align:right;"><?php 
$total_arrears_amt2 = number_format($total_arrears_amt);
echo $total_arrears_amt2; ?></th>

<th style="text-align:right;"><?php 
$total_arrears_penalty2 = number_format($total_arrears_penalty);
echo $total_arrears_penalty2; ?></th>


<th style="text-align:right;"><?php 
$tt_gt_amt = number_format($tt_gt_amt);
echo $tt_gt_amt; ?></th>
</tr>
</table>
</div>
</center>



