<?php
if($p_id == 1)
{
$multi = 1;
}
if($p_id == 2)
{
$multi = 2;
}
if($p_id == 3)
{
$multi = 4;
}
if($p_id == 4)
{
$multi = 6;
}
if($p_id == 5)
{
$multi = 12;
}
?>
<?php /////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$wing_arr = explode(',',$wing_arr_im);
foreach($cursor12 as $collection)
{
$income_head_arr = @$collection['society']['income_head'];	
}
$cur_date11 = date('d-M-Y');
$due_date11 = date('d-M-Y',strtotime($due_date));
?>
<?php //////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>

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
<p style="font-size:18px;">
<?php echo $society_reg_no; ?>
</p>
</td>
</tr>
<tr>
<td colspan="2" style="text-align:center;">
<p style="font-size:18px;"><?php echo $society_address; ?></p>
</td>
</tr>
<tr>
<th style="text-align:center;">
<p style="font-size:18px; margin-left:35%;">
<?php $to = date('d-m-Y',strtotime($to)); ?>
Bill for date From :<?php echo $from; ?> To : <?php echo $to; ?>    
</p>
</th>
<td style="text-align:right;">
<table border="0" style="margin-left:50%;">
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
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<table class="table table-bordered" style="width:100%;">
<tr>
<th>Sr.No.</th>
<th>Bill No.</th>
<th>Member</th>
<th>Unit Number</th>
<th>Area</th>
<?php
for($p=0; $p<sizeof($income_head_arr); $p++)
{
$income_head_arr_id = (int)$income_head_arr[$p];
$result21 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head_arr_id)));	
foreach($result21 as $collection)
{
$ih_name = $collection['ledger_account']['ledger_name'];
}
$total_ih[] = 0;	
?>
<th><?php echo $ih_name; ?></th>
<?php
}
?>
<th>Non Occupancy charges</th>
<th>Current Amount</th>
<th>Over Due Amount</th>
<th>Penalty Amount</th>
<th>Grand Total Amount</th>
</tr>
<?php
foreach($cursor2 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
}

$sr = 0;
$noc_tt_amt=0;
$gt_tt_amt = 0;
$gt_penalty_amt = 0;
$gt_gt_amt = 0;
$over_due_tt = 0;

if($bill_for == 2)
{
foreach($cursor1 as $collection)
{
@$bill_no++;
$sr++;
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];
$flat_id = (int)$collection['user']['flat'];

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

$maint_ch = 0;
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch'),array('pass'=>array($user_id)));
foreach($result as $collection2)
{
$due_amount = $collection2['regular_bill']['remaining_amount'];
$due_date11 = $collection2['regular_bill']['due_date'];
$from5 = $collection2['regular_bill']['bill_daterange_from'];
$previous_bill_amt = $collection2['regular_bill']['total_amount'];
}
$current_date = date('Y-m-d');
$current_date = new MongoDate(strtotime($current_date));

$result3 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat_id,$wing_id)));
foreach($result3 as $collection3)
{
$flat_type_id = (int)$collection3['flat']['flat_type_id'];
$sq_feet = (int)$collection3['flat']['flat_area'];
$noc_ch_id = (int)@$collection3['flat']['noc_ch_tp'];
}

$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_fetch'),array('pass'=>array(@$flat_type_id)));
foreach($result5 as $collection5)
{
$charge = @$collection5['flat_type']['charge'];
$noc_charge = @$collection5['flat_type']['noc_charge'];
}

?>
<tr>
<td style="text-align:right;"><?php echo $sr; ?></td>
<td style="text-align:right;"><?php echo $bill_no; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $wing_flat; ?></td>
<td style="text-align:left;"><?php echo $sq_feet; ?> &nbsp; Sq.Ft.</td>
<?php
$total_amt = 0;
$gt_amt = 0;
$n=0;
for($q=0; $q<sizeof($income_head_arr); $q++)
{
$ih_id2 = (int)$income_head_arr[$q];	
for($y=0; $y<sizeof($charge); $y++)
{
$charge3 = $charge[$y];	
$ih_id7 = (int)$charge3[0];
$tp = (int)$charge3[1];
$amtt = (int)$charge3[2];
if($tp == 2)
{
$ih_amt = $amtt * $sq_feet;	
}
else
{
$ih_amt = $amtt;
}
if($ih_id2 == $ih_id7)
{
$total_amt = $total_amt + $ih_amt;
$total_ih[$n]= $total_ih[$n] + $ih_amt;
if($ih_id2 == 42)
{
$maint_ch = $ih_amt;
}
?>
<?php /////////////////////////////////////////////////////////////////////////////////////// ?>
<td style="text-align:right;">
<?php 
if(!empty($ih_amt))
{
$ih_amt5 = $ih_amt*$multi;
$ih_amt5 = number_format($ih_amt5);
echo $ih_amt5; 
}
else
{
echo "0";	
}
?></td>
<?php //////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$n++;
}
}
}
if($noc_ch_id == 2)
{
$tp_id2 = $noc_charge[0];
if($tp_id2 == 2)
{
$amount = $noc_charge[1];
$noc_amt = $amount * $sq_feet;	
}
else if($tp_id2 == 4)
{
$noc_amt = round((10/100)*$maint_ch);	
}
else
{
$amount = $noc_charge[1];
$noc_amt = $amount;	
}
}
$noc_tt_amt = $noc_tt_amt + @$noc_amt;
$total_amt = $total_amt + @$noc_amt;
$gt_amt = $gt_amt + @$due_taxamt + $total_amt + @$due_amount;
$gt_tt_amt = $gt_tt_amt + $total_amt;
$gt_penalty_amt = $gt_penalty_amt + @$due_taxamt;
$gt_gt_amt = $gt_gt_amt + $gt_amt;
$over_due_tt = $over_due_tt + @$due_amount;
?>

<td style="text-align:right;"><?php if(!empty($noc_amt)) { 
$noc_amt5 = $noc_amt*$multi;
$noc_amt5 = number_format($noc_amt5);
echo $noc_amt5; } else { echo "0"; } ?></td>


<td style="text-align:right;"><?php 
$total_amt5 = $total_amt*$multi;
$curr_amt = $total_amt5;
$total_amt5 = number_format($total_amt5);
echo $total_amt5; ?></td>


<td style="text-align:right;"><?php if(!empty($due_amount)) { 
$due_amount5 = $due_amount*$multi;
$due_amount5 = number_format(@$due_amount5);
echo $due_amount5; } else { echo "0"; } ?></td>
<?php
////////////////////////////////////// Start Penalty ///////////////////////	
$penalty_amt = 0;
if($penalty == 1)
{
if($due_amount <= 0)
{
$penalty_amt = 0;	
}

if($due_amount > $curr_amt)
{
$start_date = date('Y-m-d',strtotime(@$from5));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));           


$date1 = date_create($due_date12);
$date2 = date_create($start_date);
$interval = date_diff($date1,$date2);
$days1 = $interval->format('%a');
	
$subpenalty1 = round(($previous_bill_amt*$days1*$pen_per)/(365*100));

$cal_amt = $due_amount-$previous_bill_amt;

$date1 = date_create($start_date);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days2 = $interval->format('%a');

$subpenalty2 = round(($cal_amt*$days2*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty2+$subpenalty1;
}

if($due_amount < $curr_amt)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));  

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days3 = $interval->format('%a');

$subpenalty3 = round(($due_amount*$days3*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty3;
}
}
///////////////////////////////////////  End Penalty ///////////////////////	
?>

<td style="text-align:right;"><?php echo $penalty_amt; ?></td>

<td style="text-align:right;"><?php
$gt_amt5 = $gt_amt*$multi+$penalty_amt; 
$gt_amt5 = number_format($gt_amt5);
echo $gt_amt5; ?></td>
</tr>
<?php 
}
?>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////  
}
else if($bill_for == 1)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
for($m=0; $m<sizeof($wing_arr); $m++)
{
$wing_id_a = (int)$wing_arr[$m];	
$cursor1 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch3'),array('pass'=>array($wing_id_a)));
foreach($cursor1 as $collection)
{
@$bill_no++;
$sr++;
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];
$flat_id = (int)$collection['user']['flat'];
//$residing = (int)$collection['user']['residing'];


$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

$maint_ch = 0;
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch'),array('pass'=>array($user_id)));
foreach($result as $collection2)
{
$due_amount = $collection2['regular_bill']['remaining_amount'];
$due_date11 = $collection2['regular_bill']['due_date'];
$from5 = $collection2['regular_bill']['bill_daterange_from'];
$previous_bill_amt = $collection2['regular_bill']['total_amount'];
}

$current_date = date('Y-m-d');
$current_date = new MongoDate(strtotime($current_date));


$result3 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat_id,$wing_id)));
foreach($result3 as $collection3)
{
$flat_type_id = (int)$collection3['flat']['flat_type_id'];
$sq_feet = (int)$collection3['flat']['flat_area'];
$noc_ch_id = (int)@$collection3['flat']['noc_ch_tp'];
}

$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_fetch'),array('pass'=>array($flat_type_id)));
foreach($result5 as $collection5)
{
$charge = @$collection5['flat_type']['charge'];
$noc_charge = @$collection5['flat_type']['noc_charge'];
}
?>
<tr>
<td style="text-align:right;"><?php echo $sr; ?></td>
<td style="text-align:right;"><?php echo $bill_no; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $wing_flat; ?></td>
<td style="text-align:left;"><?php echo $sq_feet; ?> &nbsp; Sq.Ft.</td>
<?php
$total_amt = 0;
$gt_amt = 0;
$n=0;
for($q=0; $q<sizeof($income_head_arr); $q++)
{
$ih_id2 = (int)$income_head_arr[$q];	

for($y=0; $y<sizeof(@$charge); $y++)
{
$charge3 = $charge[$y];	
$ih_id7 = (int)$charge3[0];
$tp = (int)$charge3[1];
$amtt = (int)$charge3[2];
if($tp == 2)
{
$ih_amt = $amtt * $sq_feet;	
}
else
{
$ih_amt = $amtt;
}
if($ih_id2 == $ih_id7)
{
$total_amt = $total_amt + $ih_amt;
$total_ih[$n]= $total_ih[$n] + $ih_amt;
if($ih_id2 == 42)
{
$maint_ch = $ih_amt;
}
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<td style="text-align:right;"><?php
if(!empty($ih_amt))
{
$ih_amt2 = $ih_amt*$multi; 
$ih_amt2 = number_format($ih_amt2);
echo $ih_amt2; 
}
else
{
echo "0";	
}
?></td>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$n++;
}
}
}
if($noc_ch_id == 2)
{
$tp_id2 = (int)$noc_charge[0];
if($tp_id2 == 2)
{
$amount = $noc_charge[1];
$noc_amt = $amount * $sq_feet;	
}
else if($tp_id2 == 4)
{
$noc_amt = round((10/100)*$maint_ch);	
}
else
{
$amount = $noc_charge[1];
$noc_amt = $amount;	
}
}
$noc_tt_amt = $noc_tt_amt + @$noc_amt;
$total_amt = $total_amt + @$noc_amt;
$gt_amt = $gt_amt + @$due_taxamt + $total_amt + @$due_amount;
$gt_tt_amt = $gt_tt_amt + $total_amt;
$gt_penalty_amt = $gt_penalty_amt + @$due_taxamt;
$gt_gt_amt = $gt_gt_amt + $gt_amt;
$over_due_tt = $over_due_tt + @$due_amount;
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<td style="text-align:right;"><?php if(!empty($noc_amt)) {
$noc_amt2 = $noc_amt*$multi;	 
$noc_amt2 = number_format($noc_amt2);
echo $noc_amt2; } else { echo "0"; } ?></td>


<td style="text-align:right;"><?php 
$total_amt2 = $total_amt*$multi;
$curr_amt = $total_amt2;
$total_amt2 = number_format($total_amt2);
echo $total_amt2; ?></td>


<td style="text-align:right;"><?php if(!empty($due_amount)) { 
$due_amount2 = $due_amount*$multi;
$due_amount2 = number_format($due_amount2);
echo $due_amount2; } else { echo "0"; } ?></td>



<?php
////////////////////////////////////// Start Penalty ///////////////////////	
$penalty_amt = 0;
if($penalty == 1)
{
if($due_amount <= 0)
{
$penalty_amt = 0;	
}

if($due_amount > $curr_amt)
{
$start_date = date('Y-m-d',strtotime(@$from5));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));           


$date1 = date_create($due_date12);
$date2 = date_create($start_date);
$interval = date_diff($date1,$date2);
$days1 = $interval->format('%a');
	
$subpenalty1 = round(($previous_bill_amt*$days1*$pen_per)/(365*100));

$cal_amt = $due_amount-$previous_bill_amt;

$date1 = date_create($start_date);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days2 = $interval->format('%a');

$subpenalty2 = round(($cal_amt*$days2*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty2+$subpenalty1;
}

if($due_amount < $curr_amt)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));  

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days3 = $interval->format('%a');

$subpenalty3 = round(($due_amount*$days3*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty3;
}
}
///////////////////////////////////////  End Penalty ///////////////////////	
?>

<td style="text-align:right;"><?php if(!empty($penalty_amt)) { 
$penalty_amt = $penalty_amt*$multi;
$due_tax = number_format($penalty_amt);
echo $due_tax; } else { echo "0"; }?></td>


<td style="text-align:right;"><?php
$gt_amt2 = $gt_amt*$multi+$penalty_amt; 
$gt_amt2 = number_format($gt_amt2);
echo $gt_amt2; ?></td>
</tr>
<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////
}}}

?>
<tr>
<th colspan="5" style="text-align:right;">Total</th>
<?php
for($k=0; $k<sizeof(@$total_ih); $k++)
{
$tt_amt = $total_ih[$k];
?>
<th style="text-align:right;"><?php 
$tt_amt2 = $tt_amt*$multi;
$tt_amt2 = number_format($tt_amt2);
echo $tt_amt2; ?></th>
<?php
}
?>
<th style="text-align:right;"><?php 
$noc_tt_amt2 = $noc_tt_amt*$multi;
$noc_tt_amt2 = number_format($noc_tt_amt2);
echo $noc_tt_amt2; ?></th>
<th style="text-align:right;"><?php 
$gt_tt_amt2 = $gt_tt_amt*$multi;
$gt_tt_amt2 = number_format($gt_tt_amt2);
echo $gt_tt_amt2; ?></th>
<th style="text-align:right;"><?php 
$over_due_tt2 = $over_due_tt*$multi;
$over_due_tt2 = number_format($over_due_tt2);
echo $over_due_tt2; ?></th>
<th style="text-align:right;"><?php 
$gt_penalty_amt2 = $gt_penalty_amt*$multi;
$gt_penalty_amt2 = number_format($gt_penalty_amt2);
echo $gt_penalty_amt2; ?></th>
<th style="text-align:right;"><?php 
$gt_gt_amt2 = $gt_gt_amt*$multi;
$gt_gt_amt2 = number_format($gt_gt_amt2);
echo $gt_gt_amt2; ?></th>
</tr>
</table>
</div>
</center>


<form method="post">
<input type="hidden" name="from" value="<?php echo $from; ?>" />
<input type="hidden" name="due" value="<?php echo $due_date; ?>" />
<input type="hidden" name="desc" value="<?php echo $desc; ?>" />
<input type="hidden" name="p_type" value="<?php echo $p_id; ?>" />
<input type="hidden" name="penalty" value="<?php echo $penalty; ?>" />
<input type="hidden" name="to" value="<?php echo $to; ?>" />
<input type="hidden" name="bill_for" value="<?php echo $bill_for; ?>" />
<input type="hidden" name="wing_ar" value="<?php echo $wing_arr_im; ?>" />

<br />
<div style="width:100%;">
<a href="it_regular_bill" class="btn green"  style="margin-left:70%;"><i class="icon-arrow-left"></i>Back</a>
<button type="submit" name="sub" class="btn red">Submit</button>
</div>
</form>








