<?php

$from1 = date("Y-m-d", strtotime($from));
$from1 = new MongoDate(strtotime($from1));

$to1 = date("Y-m-d", strtotime($to));
$to1 = new MongoDate(strtotime($to1));

$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch2'),array('pass'=>array(@$user_id)));
foreach($result1 as $collection)
{
$user_id = $collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
}

?>
<?php /////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php 
$nnn = 55;
?>
<?php 
foreach($cursor1 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$date_from = $collection['regular_bill']['bill_daterange_from'];	
$date_to = $collection['regular_bill']['bill_daterange_to'];	
$due_date = $collection['regular_bill']['due_date'];	
$total_amt = (int)$collection['regular_bill']['total_amount'];
//$tax_amt = (int)$collection['regular_bill']['tax_amount'];	
$due_amt = (int)$collection['regular_bill']['total_due_amount'];	
$bill_amt = (int)$collection['regular_bill']['g_total'];	
$bill_for_user = (int)$collection['regular_bill']['bill_for_user'];
$date = $collection['regular_bill']['date'];
$total_amount = $total_amt;

$result11 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result11 as $collection2) 
{
$user_name = $collection2['user']['user_name'];
$wing_id = (int)$collection2['user']['wing'];  
$flat_id = (int)$collection2['user']['flat'];
$tenant = (int)$collection2['user']['tenant'];
}	

if($wise == 2)
{
if($user_id == $bill_for_user)
{
if($date >= $from1 && $date <= $to1)
{
if($due_amt > 0)
{

$nnn = 555;

}
}
}
}
else if($wise == 1)
{
if($wing == $wing_id)
{
if($date >= $from1 && $date <= $to1)
{
if($due_amt > 0)
{

$nnn = 555;

}
}
}
}
}
?>


























<?php //////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php if($nnn == 555) { ?>

<div style="width:100%;" class="hide_at_print">
<?php
if($wise == 1)
{
?>
<span style="float:right;"> <a href="overdue_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>&w=<?php echo $wise; ?>&wi=<?php echo $wing; ?>" class="btn blue">Export in Excel</a></span>
<?php
}
else if($wise == 2)
{
?>
<span style="float:right;"> <a href="overdue_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>&w=<?php echo $wise; ?>&u=<?php echo $user_id; ?>" class="btn blue">Export in Excel</a></span>
<?php 
}
?>
<span style="float:right; margin-right:1%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />	<br />

<table class="table table-bordered" style="width:100%; background-color:white;">
<tr>
<th colspan="8" style="text-align:center;">
<p style="font-size:16px;">
Over Due Report  (<?php echo $soc_name; ?> Society)</p>
</th>
</tr>
<tr>
<th style="text-align:left;">#</th>
<th style="text-align:left;">Bill No</th>
<th style="text-align:left;">Owner Name</th>
<th style="text-align:left;">Bill Date</th>
<th style="text-align:left;">Due date</th>
<th style="text-align:left;">Total Amount</th>
<th style="text-align:left;">Due Amount</th>
<th style="text-align:left;">Bill Amount</th>
<th style="text-align:left;" class="hide_at_print">Bill View</th>
</tr>
<?php 
$c=0;
$grand_total = 0;
$total_due_amt = 0;
$total_bill_amt = 0;
foreach($cursor1 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$date_from = $collection['regular_bill']['bill_daterange_from'];	
$date_to = $collection['regular_bill']['bill_daterange_to'];	
$due_date = $collection['regular_bill']['due_date'];	
$total_amt = (int)$collection['regular_bill']['total_amount'];
//$tax_amt = (int)$collection['regular_bill']['tax_amount'];	
$due_amt = (int)$collection['regular_bill']['total_due_amount'];	
$bill_amt = (int)$collection['regular_bill']['g_total'];	
$bill_for_user = (int)$collection['regular_bill']['bill_for_user'];
$date = $collection['regular_bill']['date'];
$total_amount = $total_amt;


$result11 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result11 as $collection2) 
{
$user_name = $collection2['user']['user_name'];
$wing_id = (int)$collection2['user']['wing'];  
$flat_id = (int)$collection2['user']['flat'];
$tenant = (int)$collection2['user']['tenant'];
}	

if($wise == 2)
{
if($user_id == $bill_for_user)
{
if($date >= $from1 && $date <= $to1)
{
if($due_amt > 0)
{
$fromd = date('d-M-Y',$date_from->sec);	
$tod = date('d-M-Y',$date_to->sec);	
$dued = date('d-M-Y',$due_date->sec);	
$c++;
$grand_total = $grand_total + $total_amount;
$total_due_amt = $total_due_amt + $due_amt;
$total_bill_amt = $total_bill_amt + $bill_amt;

$total_amount = number_format($total_amount);
$due_amt = number_format($due_amt);
$bill_amt = number_format($bill_amt);


?>
<tr>
<td style="text-align:right;"><?php echo $c; ?></td>
<td style="text-align:right;"><?php echo $bill_no; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $fromd; ?>  -  <?php echo $tod; ?></td>
<td style="text-align:left;"><?php echo $dued; ?></td>
<td style="text-align:right;"><?php echo $total_amount; ?></td>
<td style="text-align:right;"><?php echo $due_amt; ?></td>
<td style="text-align:right;"><?php echo $bill_amt; ?></td>
<td style="text-align:right;" class="hide_at_print"><a href="regular_bill_view/<?php echo $bill_no; ?>" class="btn mini yellow" target="_blank">Bill View</a></td>
</tr>
<?php
}
}
}
}
else if($wise == 1)
{
if($wing == $wing_id)
{
if($date >= $from1 && $date <= $to1)
{
if($due_amt > 0)
{
$fromd = date('d-M-Y',$date_from->sec);	
$tod = date('d-M-Y',$date_to->sec);	
$dued = date('d-M-Y',$due_date->sec);	
$c++;

$grand_total = $grand_total + $total_amount;
$total_due_amt = $total_due_amt + $due_amt;
$total_bill_amt = $total_bill_amt + $bill_amt;

$total_amount = number_format($total_amount);
$due_amt = number_format($due_amt);
$bill_amt = number_format($bill_amt);
?>
<tr>
<td style="text-align:right;"><?php echo $c; ?></td>
<td style="text-align:right;"><?php echo $bill_no; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $fromd; ?>  -  <?php echo $tod; ?></td>
<td style="text-align:left;"><?php echo $dued; ?></td>
<td style="text-align:right;"><?php echo $total_amount; ?></td>
<td style="text-align:right;"><?php echo $due_amt; ?></td>
<td style="text-align:right;"><?php echo $bill_amt; ?></td>
<td style="text-align:right;" class="hide_at_print"><a href="regular_bill_view/<?php echo $bill_no; ?>" class="btn mini yellow" target="_blank">Bill View</a></td>
</tr>
<?php
}
}
}
}
}
?>
<?php 
$grand_total = number_format($grand_total);
$total_due_amt = number_format($total_due_amt);
$total_bill_amt = number_format($total_bill_amt);
?>
<tr>
<th style="text-align:right;" colspan="5">Total</th>
<th style="text-align:right;"><?php echo $grand_total; ?></th>
<th style="text-align:right;"><?php echo $total_due_amt; ?></th>
<th style="text-align:right;"><?php echo $total_bill_amt; ?></th>
<th style="text-align:right;" class="hide_at_print"></th>
</tr>
</table>

<?php }
else if($nnn == 55)
{
?>
<br /><br />											
<center>
<h3 style="color:red;">
<b>No Records Found in Selected Period</b>
</h3>
</center>
<br /><br />
<?php 
}
?>