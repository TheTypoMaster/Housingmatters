<?php
$date1 = date("Y-m-d", strtotime($from));
$date1 = new MongoDate(strtotime($date1));

$date2 = date("Y-m-d", strtotime($to));
$date2 = new MongoDate(strtotime($date2)); 
?>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
foreach($cursor9 as $collection) 
{
$charge = $collection['flat_type']['charge'];	
$income_heade_charge[] = $charge[0];
}
for($i=0; $i<sizeof($charge); $i++)
{
$inc_id = $charge[$i];
$income_head_charge[] = $inc_id[0];
}
$cnt=0;
for($y=0; $y<sizeof($income_head_charge); $y++)
{
$total[]="";	

$cnt++;	
}
$cnt = $cnt+4;
?>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<table class="table table-bordered" style="background-color:white; width:100%;">
<thead>
<tr>
<th colspan="<?php echo $cnt; ?>" style="text-align:center;"><?php echo $society_name; ?> Society</th>
</tr>
<tr>
<th>Bill No.</th>
<th>Flat No.</th>
<th>Name of Resident</th>
<?php 
for($r=0; $r<sizeof($income_head_charge); $r++)
{
$abc = (int)$income_head_charge[$r];	
$ledgerac = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($abc)));			
foreach($ledgerac as $collection2)
{
$ac_name = $collection2['ledger_account']['ledger_name'];
}
?>
<th><?php echo $ac_name; ?></th>
<?php
}
?>
<th>Non Occupancy Charges</th>
<th>Total</th>
</tr>
<?php

foreach($cursor2 as $collection)
{
$bill_id = $collection['regular_bill']['receipt_id'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$ih_detail2 = $collection['regular_bill']['ih_detail'];
$noc_amt = $collection['regular_bill']['noc_charge'];


$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$user_name = $collection['user']['user_name'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action'=>'wing_flat'),array('pass'=>array($wing_id,$flat_id)));
?>
<tr>
<td><?php echo $bill_id; ?></td>
<td><?php echo $wing_flat; ?></td>
<td><?php echo $user_name; ?></td>
<?php
$total_amt = 0;
for($y=0; $y<sizeof($income_head_charge); $y++)
{
$income_head_arr_id = $income_head_charge[$y];	
for($r=0; $r<sizeof($ih_detail2); $r++)
{
$ih_detail1 = $ih_detail2[$r];	
$ih_id1 = $ih_detail1[0];
$amount = $ih_detail1[1];
if($income_head_arr_id == $ih_id1)
{
$total[$y] = $total[$y] + $amount;
?>
<td><?php echo $amount; ?></td>
<?php
$total_amt=$total_amt+$amount;
}
}
}
?>
<td><?php echo $noc_amt; ?></td>
<td><?php echo $total_amt; ?></td>
</tr>
<?php 
}
?>
<tr>
<th colspan="3">Grand Total</th>
<?php 
$grand_total = 0;
for($h=0; $h<sizeof($total); $h++)
{  
?>
<th><?php echo $total[$h]; ?></th>
<?php 
$grand_total = $grand_total + $total[$h];
}
?>
<th></th>
<th><?php echo $grand_total; ?></th>
</tr>

</table>
















