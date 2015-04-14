<?php
$from = date("Y-m-d",strtotime($from));
$to = date("Y-m-d",strtotime($to));

$start    = (new DateTime($from));
$end      = (new DateTime($to));
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);


foreach($period as $data)
{
$mon1 = $data->format("M-Y");
foreach($cursor3 as $collection)
{
$auto_id = (int)$collection['expense_tracker']['auto_id'];	
$expense_date_mongo = $collection['expense_tracker']['posting_date'];
$expense_date = date('d-m-Y',$expense_date_mongo->sec);
$expense_month = date("M-Y",strtotime($expense_date));
if($expense_month == $mon1) 
{
$expense_arr[] = array($auto_id,$expense_month); 
}
}
}



/////////////////////////////////////////////////////////////////////////
foreach($cursor3 as $collection)
{
$expense_date_mongo = $collection['expense_tracker']['posting_date'];
$expense_date = date('d-m-Y',$expense_date_mongo->sec);
$expense_month = date("M-Y",strtotime($expense_date));
$expense_month_arr[] = $expense_month;

}

////////////////////////////////////////////////////////////////////////////











//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<table class="table table-bordered" style="background-color:white;">
<tr>
<th>Expense Head</th>
<?php
foreach ($period as $dt){
$month_name1 = $dt->format("M-Y");

for($p=0; $p<sizeof($expense_month_arr); $p++)
{
$month_name2 = $expense_month_arr[$p];

if($month_name1 == $month_name2)
{
$abc[] = $month_name1;
?>
<th style="text-align:center;">
<?php echo $month_name1; ?>
</th>
<?php
break;
 }}} ?>
</tr>

<?php
$total = 0;
foreach($cursor2 as $collection)
{
$group_id = (int)$collection['accounts_group']['auto_id'];	
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array($group_id)));
foreach($result2 as $collection2)
{
$ex_head = (int)$collection2['ledger_account']['auto_id'];	
$expense_head = $collection2['ledger_account']['ledger_name'];
for($k=0; $k<sizeof(@$expense_arr); $k++)
{
$exp_arr1 = $expense_arr[$k];
$auto_id2 = (int)$exp_arr1[0];
$month5 = $exp_arr1[1];
$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_tracker_fetch'),array('pass'=>array($auto_id2)));
foreach($result5 as $collection3)
{
$exp_head2 = (int)$collection3['expense_tracker']['expense_head'];
$amount = $collection3['expense_tracker']['amount'];
}
if($exp_head2 == $ex_head)
{

?>
<tr>
<td style="text-align:left;">
<?php echo $expense_head;  ?>
</td>



















<?php

for($m=0; $m<sizeof($abc); $m++)
{
	$total = 0;
$month_name3 = $abc[$m];

foreach($cursor3 as $collection6)
{
$exps_head = (int)$collection6['expense_tracker']['expense_head'];
$posting_date = $collection6['expense_tracker']['posting_date'];
$amount = $collection6['expense_tracker']['amount'];
$posting_date = date('M-Y',$posting_date->sec);
if($posting_date == $month_name3 && $exp_head2 == $exps_head)
{
$total = $total + $amount;	
}
}
	
?>
<td style="text-align:center;">
<?php echo $total; ?>
</td>
<?php
$total = 0;
}
?>
</tr>
<?php
break;
}}}}
?>
</table>

<?php
for($l=0; $l<sizeof(@$abc); $l++)
{
$month1 = $abc[$l];

echo $month2 = date("d-m-Y",strtotime);

}

?>

