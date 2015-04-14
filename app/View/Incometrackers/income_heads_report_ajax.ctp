<?php
$date1 = date("Y-m-d", strtotime($from));
$date1 = new MongoDate(strtotime($date1));

$date2 = date("Y-m-d", strtotime($to));
$date2 = new MongoDate(strtotime($date2)); 

$c=0;
$ledgerac2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array(7)));			
foreach($ledgerac2 as $collection2)
{
$ac_name = $collection2['ledger_account']['ledger_name'];
$ac_id = (int)$collection2['ledger_account']['auto_id'];		
if($ac_id != 43 && $ac_id != 39 && $ac_id != 40)
{
$c++;
}}
$cnt = $c+5;


?>


<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="income_head_report_excel?f=<?php echo $from; ?>&t=<?php echo $to; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />

           
		    <table class="table table-bordered" style="background-color:white; width:190%;">
            <thead>
            <tr>
            <th colspan="<?php echo $cnt; ?>" style="text-align:center;"><?php echo $society_name; ?> Society</th>
            <tr>
            <th>Bill No.</th>
            <th style="width:6%;">Flat No.</th>
            <th style="width:10%;">Name of Resident</th>
		    <?php 	
$ledgerac = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array(7)));			
foreach($ledgerac as $collection2)
{
$ac_name = $collection2['ledger_account']['ledger_name'];
$ac_id = (int)$collection2['ledger_account']['auto_id'];		
if($ac_id != 43 && $ac_id != 39 && $ac_id != 40)
{
$ih_id[] = (int)$ac_id;
$gt_amt[] = 0;
$noc_tt = 0;
$grand_tt = 0;
?>
<th style="text-align:center;"><?php echo $ac_name; ?></th>
<?php            
}}
?>  
<th style="text-align:center;">Non Occupancy Charges</th>
<th style="text-align:center;">Total</th>          
</tr>
</thead>
<?php
$h=0;
foreach($cursor2 as $collection)
{
$total = 0;	
$bill_id = $collection['regular_bill']['receipt_id'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$ih_detail2 = $collection['regular_bill']['ih_detail'];

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
<td style="text-align:center;"><?php echo $bill_id; ?></td>
<td style="text-align:center;"><?php echo $wing_flat; ?></td>
<td style="text-align:center;"><?php echo $user_name; ?></td>
<?php
for($k=0; $k<sizeof($ih_id); $k++)
{
$ih_id1 = (int)$ih_id[$k];
$nnn = 5;
$amount = 0;	
for($l=0; $l<sizeof($ih_detail2); $l++)
{
$ih_detail = $ih_detail2[$l];
$ih_id2 = (int)$ih_detail[0];

if($ih_id1 == $ih_id2)
{
$amount = $ih_detail[1];
$nnn = 55;
break;
}
}
?>
<td style="text-align:center;">
<?php
echo $amount;
?>
</td>
<?php
$total = $total + $amount;
$gt_amt[$k] = $gt_amt[$k] + $amount;
}

for($q=0; $q<sizeof($ih_detail2); $q++)
{
$aaa = 5;
$amt = 0;	
$ihd = $ih_detail2[$q];	
$ih_id3 = (int)$ihd[0];	
if($ih_id3 == 43)
{
$amt = $ihd[1];
$aaa = 55;
break;
}
}
?>
<td style="text-align:center;">
<?php
echo $amt;
$total = $total + $amt;
$noc_tt = $noc_tt + $amt;
?>
</td>
<th style="text-align:center;"><?php echo $total; ?></th>
</tr>
<?php
$grand_tt = $grand_tt + $total;
}
?>
<tr>
<th colspan="3">Grand Total</th>
<?php
for($o=0; $o<sizeof($gt_amt); $o++)
{
$gt_amt2 = $gt_amt[$o];
?>
<th style="text-align:center;"><?php echo $gt_amt2; ?></th>
<?php
}
?>
<th style="text-align:center;"><?php echo $noc_tt; ?></th>
<th style="text-align:center;"><?php echo $grand_tt; ?></th>
</tr>
</table>

			
			
			
			
			
			
			
			
			
			
			
			