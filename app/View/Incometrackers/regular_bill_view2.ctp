<form method="post" onsubmit="return submit_hndlr()">
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
$multi = 3;
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

$count5 = (int)sizeof($income_head_arr);
?>
<input type="hidden" id="ccc" value="<?php echo $count5; ?>" />
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
<table class="table table-bordered" style="width:100%;" id="i_bill">
<tr>
<th>Sr.No.</th>
<th>Bill No.</th>
<th>Member</th>
<th>Unit Number</th>
<th>Area</th>
<?php
$b=0;
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
<th><?php echo $ih_name; ?>
<?php
$b++;
?>
<input type="text" class="m-wrap span12" id="head<?php echo $b; ?>" onkeyup="chng1()">
</th>
<?php
}
?>
<th>Non Occupancy charges
<input type="text" class="m-wrap span12" id="nnc" onkeyup="chng1()">
</th>
<th>Current Amount</th>
<th>Over Due Amount</th>
<th>Interest Arrears</th>
<th>Penalty Amount
<input type="text" class="m-wrap span12" id="ppn" onkeyup="chng1()">
</th>
<th>Grand Total Amount</th>
</tr>
<?php
$nnnn = 5;
foreach($cursor2 as $collection)
{
$bill_no = (int)@$collection['regular_bill']['receipt_id'];	
}
if(@$bill_no == 0)
{
$bill_no = 1000;
$nnnn = 555;	
}
$sr = 0;
$noc_tt_amt=0;
$gt_tt_amt = 0;
$gt_penalty_amt = 0;
$gt_gt_amt = 0;
$over_due_tt = 0;
$arerr_penal_amt = 0;
if($bill_for == 2)
{
$c=0;	
foreach($cursor1 as $collection)
{
$c++;
$multi_flat = array();
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];
$flat_id = (int)$collection['user']['flat'];
$multi_flat = @$collection['user']['multiple_flat'];

$rrr = (int)sizeof($multi_flat);
if($rrr == 0)
{
$multi_flat[] = array($wing_id,$flat_id);	
}

for($g=0; $g<sizeof($multi_flat); $g++)
{
$mul_flat2 = $multi_flat[$g];
$wing_id = (int)$mul_flat2[0];
$flat_id = (int)$mul_flat2[1];

@$bill_no++;
$sr++;

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array(1,1)));


$maint_ch = 0;
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch10'),array('pass'=>array($user_id,$flat_id)));
foreach($result as $collection2)
{
$due_amount = @$collection2['regular_bill']['remaining_amount'];
$due_date11 = $collection2['regular_bill']['due_date'];
$from5 = $collection2['regular_bill']['bill_daterange_from'];
$previous_bill_amt = @$collection2['regular_bill']['current_bill_amt'];
$pen_receipt_id = @$collection2['regular_bill']['receipt_id'];
$interest_arrears = @$collection2['regular_bill']['accumulated_tax'];
$arrears = $collection2['regular_bill']['arrears_amt'];
$ggg_tt = @$collection2['regular_bill']['remaining_amount'];
$arr_interest_for_receipt = @$collection2['regular_bill']['arrear_interest'];
$due_for_payment = (int)@$collection2['regular_bill']['g_total'];
$aaa_int = (int)$collection2['regular_bill']['arrear_interest'];
}

$current_date = date('Y-m-d');
$current_date = new MongoDate(strtotime($current_date));

$result4 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array(@$flat_id,$wing_id)));
foreach($result4 as $data)
{
$flat_type_id = (int)$data['flat']['flat_type_id'];
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
$r=0;
for($q=0; $q<sizeof($income_head_arr); $q++)
{
$ih_id2 = (int)$income_head_arr[$q];	
for($y=0; $y<sizeof($charge); $y++)
{
$charge3 = $charge[$y];	
$ih_id7 = (int)$charge3[0];
$tp = (int)$charge3[1];
$amtt = $charge3[2];
if($tp == 2)
{
$ih_amt = round($amtt * $sq_feet);	
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
$r++;
?>
<input type="text" name="ih<?php echo $ih_id2; ?><?php echo $user_id; ?>" value="<?php echo $ih_amt5; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="head2<?php echo $r; ?><?php echo $c; ?>" />
<?php
}
else
{
$r++;
?>
<input type="text" name="ih<?php echo $ih_id2; ?><?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="head2<?php echo $r; ?><?php echo $c; ?>"/>
<?php 
}
?></td>
<?php //////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$n++;
}
}
}
$noc_amt ="";
if($noc_ch_id == 2)
{
$tp_id2 = $noc_charge[0];
if($tp_id2 == 2)
{
$amount = $noc_charge[1];
$noc_amt = round($amount*$sq_feet);	
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
$gt_amt = $gt_amt + $total_amt;
$gt_amt = $gt_amt*$multi;
$gt_amt = $gt_amt + @$due_amount;
?>

<td style="text-align:right;"><?php if(!empty($noc_amt)) { 
$noc_amt5 = (int)$noc_amt*$multi;
?>
<input type="text" name="noc<?php echo $user_id; ?>" value="<?php echo $noc_amt5; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="nnc2<?php echo $c; ?>"/>
<?php
} else { 
?>
<input type="text" name="noc<?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" readonly="readonly" style="border: none;"/>
<?php
} ?>
</td>


<td style="text-align:right;background-color:rgb(255, 255, 216);"><?php 
$total_amt5 = $total_amt*$multi;
$curr_amt = $total_amt5;
?>
<input type="text" name="tt<?php echo $user_id; ?>" value="<?php echo $total_amt5; ?>" class="m-wrap span12" readonly="readonly" style="border:none;"/>
</td>


<td style="text-align:right;"><?php if(!empty($due_amount)) { 
$due_amount5 = $due_amount;
?>
<input type="text" name="due<?php echo $user_id; ?>" value="<?php echo $due_amount5; ?>" class="m-wrap span12" readonly="readonly" style="border:none;"/>
<?php
} else { 
?> 
<input type="text" name="due<?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12" readonly="readonly" style="border:none;"/>
<?php 
} ?>
</td>
<?php
////////////////////////////////////// Start Penalty ///////////////////////	
$penalty_amt = 0;
if($penalty == 1)
{
$due_amtttt =(int)(@$ggg_tt) - (@$interest_arrears);
//$due_amtttt = (int)$arrears+$previous_bill_amt;	
if(@$arrears <= 0)
{
$penalty_amt = 0;	
}

if($due_amtttt > @$previous_bill_amt && $due_amtttt > 0)
{
$start_date = date('Y-m-d',strtotime(@$from5));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));           

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days1 = $interval->format('%a');
	
$subpenalty1 = round(($previous_bill_amt*$days1*$pen_per)/(365*100));

$cal_amt = (int)$due_amtttt-$previous_bill_amt-$aaa_int;

$date1 = date_create($start_date);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days2 = $interval->format('%a');

$subpenalty2 = round(($cal_amt*$days2*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty2+$subpenalty1;
}

if($due_amtttt <= @$previous_bill_amt && $due_amtttt > 0)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));  

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days3 = $interval->format('%a');

$subpenalty3 = round((@$due_amtttt*$days3*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty3;
}

$result6 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch3'),array('pass'=>array($user_id)));
foreach($result6 as $collection5)
{
$bank_user_id = (int)$collection5['ledger_sub_account']['auto_id'];	
}

$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'bank_receipt_fetch'),array('pass'=>array($bank_user_id,@$pen_receipt_id)));
foreach($result7 as $collection7)
{
$transaction_date = $collection7['cash_bank']['transaction_date'];	
$bank_amount = (int)$collection7['cash_bank']['amount'];
$bank_amount = @$bank_amount - @$arr_interest_for_receipt;
$arr_interest_for_receipt = 0;
if($due_for_payment >=$bank_amount)
{
$due_for_payment = $due_for_payment - $bank_amount;
$bank_amount = $bank_amount;	
}
else
{
if($due_for_payment>0)
{
$bank_amount = $due_for_payment;
}
else
{
$bank_amount = 0;	
}
}
	
$bank_date = date('Y-m-d',strtotime(@$transaction_date));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
if($bank_date > $due_date12)
{
$date1 = date_create($due_date12);
$date2 = date_create($bank_date);
$interval = date_diff($date1,$date2);
$days4 = $interval->format('%a');

$subpenalty4 = round((@$bank_amount*$days4*$pen_per)/(365*100));
$penalty_amt = $penalty_amt+$subpenalty4;
}
}

$result8 = $this->requestAction(array('controller' => 'hms', 'action' => 'petty_cash_receipt_fetch'),array('pass'=>array($bank_user_id,@$pen_receipt_id)));
foreach($result8 as $collection8)
{
$transaction_date = $collection8['cash_bank']['transaction_date'];	
$bank_amount = (int)$collection8['cash_bank']['amount'];
$bank_amount = @$bank_amount - @$$arr_interest_for_receipt;
$arr_interest_for_receipt = 0;
if($due_for_payment >=$bank_amount)
{
$due_for_payment = $due_for_payment - $bank_amount;
$bank_amount = $bank_amount;	
}
else
{
if($due_for_payment>0)
{
$bank_amount = $due_for_payment;
}
else
{
$bank_amount = 0;	
}	
}

	
$bank_date = date('Y-m-d',strtotime(@$transaction_date));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
if($bank_date > $due_date12)
{
$date1 = date_create($due_date12);
$date2 = date_create($bank_date);
$interval = date_diff($date1,$date2);
$days4 = $interval->format('%a');

$subpenalty5 = round((@$bank_amount*$days4*$pen_per)/(365*100));
$penalty_amt = $penalty_amt+$subpenalty5;
}
}

}

///////////////////////////////////////  End Penalty ///////////////////////	
?>
<td style="text-align:right;"><input type="text" name="int_ars<?php echo $user_id; ?>" 
value="<?php if(!empty($interest_arrears)) { echo $interest_arrears; } else { echo "0"; } ?>" readonly="readonly" class="m-wrap span12" style="border:none;"/></td>

<td style="text-align:right;">
<?php
if($penalty == 1)
{ ?>
<input type="text" name="penalty<?php echo $user_id; ?>" value="<?php echo $penalty_amt; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="ppn2<?php echo $c; ?>"/>
<?php }
else
{
?>
<input type="text" name="penalty<?php echo $user_id; ?>" value="0" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" readonly="readonly">
<?php
}
?>
</td>
<td style="text-align:right;background-color:rgb(220, 234, 220);;"><?php
$gt_amt5 = $gt_amt+$penalty_amt; 
?>
<input type="text" name="gtt<?php echo $user_id; ?>" value="<?php echo $gt_amt5; ?>" class="m-wrap span12" readonly="readonly" style="border:none;" />
</td>
</tr>
<?php
$gt_tt_amt = $gt_tt_amt + $total_amt5;
$gt_penalty_amt = $gt_penalty_amt+@$penalty_amt;
$gt_gt_amt = $gt_gt_amt + $gt_amt5;
$over_due_tt = $over_due_tt + @$due_amount;
$arerr_penal_amt=$arerr_penal_amt+@$interest_arrears;
}
}
?>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////  
}
else if($bill_for == 1)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
$c=0;
for($m=0; $m<sizeof($wing_arr); $m++)
{
$wing_id_a = (int)$wing_arr[$m];	
$cursor1 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch3'),array('pass'=>array($wing_id_a)));

foreach($cursor1 as $collection)
{
$c++;
$multi_flat = array();	
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];
$flat_id = (int)$collection['user']['flat'];
//$residing = (int)$collection['user']['residing'];
$multi_flat = @$collection['user']['multiple_flat'];

$rrr = (int)sizeof($multi_flat);
if($rrr == 0)
{
$multi_flat[] = array($wing_id,$flat_id);	
}

for($g=0; $g<sizeof($multi_flat); $g++)
{
$mul_flat2 = $multi_flat[$g];
$wing_id = (int)$mul_flat2[0];
$flat_id = (int)$mul_flat2[1];

@$bill_no++;
$sr++;




$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

$maint_ch = 0;
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch10'),array('pass'=>array($user_id,$flat_id)));
foreach($result as $collection2)
{
$due_amount = @$collection2['regular_bill']['remaining_amount'];
$due_date11 = $collection2['regular_bill']['due_date'];
$from5 = $collection2['regular_bill']['bill_daterange_from'];
$previous_bill_amt = (int)@$collection2['regular_bill']['current_bill_amt'];
$pen_receipt_id = (int)@$collection2['regular_bill']['receipt_id'];
$interest_arrears = (int)@$collection2['regular_bill']['accumulated_tax'];
$arrears = (int)@$collection2['regular_bill']['arrears_amt'];
$ggg_tt = (int)@$collection2['regular_bill']['remaining_amount'];
$arr_interest_for_receipt = @$collection2['regular_bill']['arrear_interest'];
$due_for_payment = (int)@$collection2['regular_bill']['g_total'];
$aaa_int = (int)$collection2['regular_bill']['arrear_interest'];
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
$r=0;
for($q=0; $q<sizeof($income_head_arr); $q++)
{
$ih_id2 = (int)$income_head_arr[$q];	

for($y=0; $y<sizeof(@$charge); $y++)
{
$charge3 = $charge[$y];	
$ih_id7 = (int)$charge3[0];
$tp = (int)$charge3[1];
$amtt = $charge3[2];
if($tp == 2)
{
$ih_amt = round($amtt*$sq_feet);	
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
$r++; 
?>
<input type="text" name="ih<?php echo $ih_id2; ?><?php echo $user_id; ?>" value="<?php echo $ih_amt2; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="head2<?php echo $r; ?><?php echo $c; ?>"/>
<?php
}
else
{
$r++; 
?>
<input type="text" name="ih<?php echo $ih_id2; ?><?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="head2<?php echo $r; ?><?php echo $c; ?>"/>
<?php
}
?></td>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$n++;
}
}
}
$noc_amt ="";
if($noc_ch_id == 2)
{
$tp_id2 = (int)$noc_charge[0];
if($tp_id2 == 2)
{
$amount = $noc_charge[1];
$noc_amt = round($amount * $sq_feet);	
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
$gt_amt = $gt_amt*$multi;
$gt_amt = $gt_amt + @$due_amount;
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<td style="text-align:right;"><?php if(!empty($noc_amt)) {
$noc_amt2 = $noc_amt*$multi;	 
?>
<input type="text" name="noc<?php echo $user_id; ?>" value="<?php echo $noc_amt2; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="nnc2<?php echo $c; ?>"/>
<?php
} else { 
?>
<input type="text" name="noc<?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" readonly="readonly" style="border-color:#00F;"/>
<?php
} 
?>
</td>

<td style="text-align:right;"><?php 
$total_amt2 = $total_amt*$multi;
$curr_amt = $total_amt2;
?>
<input type="text" name="tt<?php echo $user_id; ?>" value="<?php echo $total_amt2; ?>" class="m-wrap span12" readonly="readonly"/>
</td>


<td style="text-align:right;"><?php if(!empty($due_amount)) { 
$due_amount2 = $due_amount;
?>
<input type="text" name="due<?php echo $user_id; ?>" value="<?php echo $due_amount2; ?>" class="m-wrap span12" readonly="readonly"/>
<?php
} else { 
?>
<input type="text" name="due<?php echo $user_id; ?>" value="<?php echo "0"; ?>" class="m-wrap span12" readonly="readonly"/>
<?php } ?>
</td>
<?php
////////////////////////////////////// Start Penalty ///////////////////////	
////////////////////////////////////// Start Penalty ///////////////////////	
$penalty_amt = 0;
if($penalty == 1)
{
$due_amtttt =(int)@$ggg_tt - @$interest_arrears;
//$due_amtttt = (int)$arrears+$previous_bill_amt;	
if(@$arrears <= 0)
{
$penalty_amt = 0;	
}

if($due_amtttt > @$previous_bill_amt && $due_amtttt > 0)
{
$start_date = date('Y-m-d',strtotime(@$from5));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));           

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days1 = $interval->format('%a');
	
$subpenalty1 = round(($previous_bill_amt*$days1*$pen_per)/(365*100));

$cal_amt = (int)$due_amtttt-$previous_bill_amt-$aaa_int;

$date1 = date_create($start_date);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days2 = $interval->format('%a');

$subpenalty2 = round(($cal_amt*$days2*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty2+$subpenalty1;
}

if($due_amtttt <= @$previous_bill_amt && $due_amtttt > 0)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$current_start_date = date('Y-m-d',strtotime($from));  

$date1 = date_create($due_date12);
$date2 = date_create($current_start_date);
$interval = date_diff($date1,$date2);
$days3 = $interval->format('%a');

$subpenalty3 = round((@$due_amtttt*$days3*$pen_per)/(365*100));

$penalty_amt = $penalty_amt+$subpenalty3;
}

$result6 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch3'),array('pass'=>array($user_id)));
foreach($result6 as $collection5)
{
$bank_user_id = (int)$collection5['ledger_sub_account']['auto_id'];	
}

$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'bank_receipt_fetch'),array('pass'=>array($bank_user_id,@$pen_receipt_id)));
foreach($result7 as $collection7)
{
$transaction_date = $collection7['cash_bank']['transaction_date'];	
$bank_amount = (int)$collection7['cash_bank']['amount'];
$bank_amount =$bank_amount-@$arr_interest_for_receipt;
$arr_interest_for_receipt = 0;
if($due_for_payment >=$bank_amount)
{
@$due_for_payment = @$due_for_payment -$bank_amount; 
$bank_amount = (int)$bank_amount;	
}
else
{
if($due_for_payment>0)
{
$bank_amount = $due_for_payment;
}
else
{
$bank_amount = 0;	
}	
}

	
$bank_date = date('Y-m-d',strtotime(@$transaction_date));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
if($bank_date > $due_date12)
{
$date1 = date_create($due_date12);
$date2 = date_create($bank_date);
$interval = date_diff($date1,$date2);
$days4 = $interval->format('%a');

$subpenalty4 = round((@$bank_amount*$days4*$pen_per)/(365*100));
$penalty_amt = $penalty_amt+$subpenalty4;
}
}

$result8 = $this->requestAction(array('controller' => 'hms', 'action' => 'petty_cash_receipt_fetch'),array('pass'=>array($bank_user_id,@$pen_receipt_id)));
foreach($result8 as $collection8)
{
$transaction_date = $collection8['cash_bank']['transaction_date'];	
$bank_amount = (int)$collection8['cash_bank']['amount'];
$bank_amount =$bank_amount-@$arr_interest_for_receipt;
$arr_interest_for_receipt = 0;
if($due_for_payment >=$bank_amount)
{
@$due_for_payment = @$due_for_payment -$bank_amount; 
$bank_amount = (int)$bank_amount;	
}
else
{
if($due_for_payment>0)
{
$bank_amount = $due_for_payment;
}
else
{
$bank_amount = 0;	
}
}

	
$bank_date = date('Y-m-d',strtotime(@$transaction_date));
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
if($bank_date > $due_date12)
{
$date1 = date_create($due_date12);
$date2 = date_create($bank_date);
$interval = date_diff($date1,$date2);
$days4 = $interval->format('%a');

$subpenalty5 = round((@$bank_amount*$days4*$pen_per)/(365*100));
$penalty_amt = $penalty_amt+$subpenalty5;
}
}

}
///////////////////////////////////////  End Penalty ///////////////////////	

///////////////////////////////////////  End Penalty ///////////////////////	
?>
<td style="text-align:right;"><input type="text" name="int_ars<?php echo $user_id; ?>" 
value="<?php if(!empty($interest_arrears)) { echo $interest_arrears; } else { echo "0"; } ?>" readonly="readonly" class="m-wrap span12"/></td>
<td style="text-align:right;"><?php if($penalty == 1)
{ 
?>
<input type="text" name="penalty<?php echo $user_id; ?>" value="<?php echo $penalty_amt; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" id="ppn2<?php echo $c; ?>"/>
<?php
} else { 
?>
<input type="text" name="penalty<?php echo $user_id; ?>" value="<?php  echo "0"; ?>" class="m-wrap span12 inhd" row_no="<?php echo $sr; ?>" readonly="readonly"/>
<?php }?>
</td>


<td style="text-align:right;"><?php
$gt_amt2 = $gt_amt+$penalty_amt; 
?>
<input type="text" name="gtt<?php echo $user_id; ?>" value="<?php echo $gt_amt2; ?>" class="m-wrap span12" readonly="readonly"/>
</td>
</tr>
<?php
$gt_tt_amt = $gt_tt_amt + $total_amt2;
$gt_penalty_amt = $gt_penalty_amt+@$penalty_amt;
$gt_gt_amt = $gt_gt_amt + $gt_amt2;
$over_due_tt = $over_due_tt + @$due_amount;
$arerr_penal_amt=$arerr_penal_amt+@$interest_arrears;


////////////////////////////////////////////////////////////////////////////////////////////////////////
}}}}
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
$gt_tt_amt2 = $gt_tt_amt;
$gt_tt_amt2 = number_format($gt_tt_amt2);
echo $gt_tt_amt2; ?></th>
<th style="text-align:right;"><?php 
$over_due_tt2 = $over_due_tt;
$over_due_tt2 = number_format($over_due_tt2);
echo $over_due_tt2; ?></th>
<th style="text-align:right;"><?php 
$arerr_penal_amt = $arerr_penal_amt;
$arerr_penal_amt = number_format($arerr_penal_amt);
echo $arerr_penal_amt; ?></th>
<th style="text-align:right;"><?php 
$gt_penalty_amt2 = $gt_penalty_amt;
$gt_penalty_amt2 = number_format($gt_penalty_amt2);
echo $gt_penalty_amt2; ?></th>
<th style="text-align:right;"><?php 
$gt_gt_amt2 = $gt_gt_amt;
$gt_gt_amt2 = number_format($gt_gt_amt2);
echo $gt_gt_amt2; ?></th>
</tr>
</table>
</div>
</center>



<input type="hidden" name="from" value="<?php echo $from; ?>" />
<input type="hidden" name="due" value="<?php echo $due_date; ?>" />
<input type="hidden" name="desc" value="<?php echo $desc; ?>" />
<input type="hidden" name="p_type" value="<?php echo $p_id; ?>" />
<input type="hidden" name="penalty" value="<?php echo $penalty; ?>" />
<input type="hidden" name="to" value="<?php echo $to; ?>" />
<input type="hidden" name="bill_for" value="<?php echo $bill_for; ?>" />
<input type="hidden" name="wing_ar" value="<?php echo $wing_arr_im; ?>" />



<input type="hidden" value="<?php echo $b; ?>" id="cnt1" />
<input type="hidden" value="<?php echo $c; ?>" id="cnt2" />
<br />
<div style="width:100%;">
<a href="it_regular_bill" class="btn green"  style="margin-left:70%;"><i class="icon-arrow-left"></i>Back</a>
<button type="submit" name="sub" class="btn red" id="submit_bill">Submit</button>
</div>
</form>




<script>
function calculation_generator(row_no){
	$(document).ready(function() {
		row_no++;
		
		var count = $("#ccc").val();
		
		var tr_count1=$('table#i_bill tr').length;
		
		var ih_total=0;
		var g_total=0;
		
		columnTh = $("table th:contains('Area')");
		columnIndex = columnTh.index() + 2;
		
		for(var c=0;c<count;c++){
			
			//columnIndex=columnIndex+c;
			
			var qa=$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex+') input').val();
			
			ih_total=parseInt(ih_total)+parseInt(qa);
			var in_hd_total=0;
			columnIndexqw=columnIndex-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
			columnIndex++;
		}
		columnTh2 = $("table th:contains('Non Occupancy charges')");
		columnIndex2 = columnTh2.index() + 1;
		var noc=$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex2+') input').val();
		ih_total=parseInt(ih_total)+parseInt(noc);
		
		var in_hd_total=0;
			columnIndexqw=columnIndex2-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex2+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
		
		columnTh3 = $("table th:contains('Current Amount')");
		columnIndex3 = columnTh3.index() + 1;
		$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex3+') input').val(ih_total);
		
		var in_hd_total=0;
			columnIndexqw=columnIndex3-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex3+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
			
		
		columnTh4 = $("table th:contains('Over Due Amount')");
		columnIndex4 = columnTh4.index() + 1;
		var oda=$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex4+') input').val();
		g_total=parseInt(ih_total)+parseInt(oda);
		
		
		var in_hd_total=0;
			columnIndexqw=columnIndex4-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex4+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
		
		
		columnTh5 = $("table th:contains('Penalty Amount')");
		columnIndex5 = columnTh5.index() + 1;
		var pa=$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex5+') input').val();
		g_total=parseInt(g_total)+parseInt(pa);
		
		var in_hd_total=0;
			columnIndexqw=columnIndex5-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex5+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
		
		columnTh6 = $("table th:contains('Grand Total Amount')");
		columnIndex6 = columnTh6.index() + 1;
		$('table#i_bill tr:nth-child('+row_no+') td:nth-child('+columnIndex6+') input').val(g_total);
		
		
		var in_hd_total=0;
			columnIndexqw=columnIndex6-4;
			for(var w=2;w<tr_count1;w++){ 
				var in_hd=$('table#i_bill tr:nth-child('+w+') td:nth-child('+columnIndex6+') input').val();
				
				in_hd_total=parseInt(in_hd_total)+parseInt(in_hd);
				$('table#i_bill tr:nth-child('+tr_count1+') th:nth-child('+columnIndexqw+')').html(in_hd_total);
			}
		
		
		
	});
}

$(document).ready(function() {
	$(".inhd").keyup(function(){
		var row_no=$(this).attr("row_no");
		calculation_generator(row_no);
	});
});
</script>


<script>
function chng1()
{
var cnt1 = $("#cnt1").val();
var cnt2 = $("#cnt2").val();

for(var h=1; h<=cnt1; h++)
{
var value = $("#head" + h).val();	
if(value != "")
{
for(var j=1; j<=cnt2; j++)
{
$("#head2" + h + j).val(value);	
}
}
}
var noc_val = $("#nnc").val();
if(noc_val != "")
{
for(var j=1; j<=cnt2; j++)
{
$("#nnc2" + j).val(noc_val);	
}
}
var penalty = $("#ppn").val();

if(penalty != "")
{
for(var j=1; j<=cnt2; j++)
{
$("#ppn2" + j).val(penalty);	
}
}
var tr_count1=$('table#i_bill tr').length;


for(var t=1; t<tr_count1; t++){
	calculation_generator(t);
}
	
}

function submit_hndlr(){
	$(document).ready(function() {
		$("#submit_bill").hide();
		$("#submiting_div").show();
	});
}
</script>



<div id="submiting_div" style="display:none;">
	<div class="modal-backdrop fade in"></div>
	<div class="modal" id="poll_edit_content">
		<div class="modal-body">
		<div align="center">
		<img src="<?php echo $webroot_path; ?>as/fb_loading.gif" style="height: 15px;" />
		<h4>Please Wait a while</h4>
		<h5>Bill(s) are Preparing.</h5>
		</div>
	</div>
</div>












