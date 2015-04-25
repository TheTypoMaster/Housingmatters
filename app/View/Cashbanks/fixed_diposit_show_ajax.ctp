<?php
$from = date("Y-m-d", strtotime($from));
$from = new MongoDate(strtotime($from));

$to = date("Y-m-d", strtotime($to));
$to = new MongoDate(strtotime($to));


foreach($cursor2 as $collection)
{
$society_name = $collection['society']['society_name'];	
}



?>

<table class="table table-bordered" style="width:180%;">
<tr>
<th style="text-align:center;">Sr #</th>
<th style="text-align:center;">Bank Name</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">E-mail</th>
<th style="text-align:center;">Mobile</th>
<th style="text-align:center;">A/c Reference</th>
<th style="text-align:center;">Principal Amount</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">Maturity Date</th>
<th style="text-align:center;">Interest Rate</th>
<th style="text-align:center;">Maturity Amount</th>
<th style="text-align:center;">Transaction Id</th>
<th style="text-align:center;">Action</th>
<th style="text-align:center;">Remark</th>
</tr>

<?php
$n=0;
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['fix_deposit']['auto_id'];	
$bank_name = $collection['fix_deposit']['bank_name'];	
$branch = $collection['fix_deposit']['branch'];	
$account_ref = $collection['fix_deposit']['account_reference'];	
$prepaired_by = $collection['fix_deposit']['prepaired_by'];	
$principal_amt = $collection['fix_deposit']['principal_amount'];
$start_date = $collection['fix_deposit']['start_date'];
$maturity_date = $collection['fix_deposit']['maturity_date'];
$interest_rate = $collection['fix_deposit']['interest_rate'];
$remark = $collection['fix_deposit']['remark'];
$reminder = $collection['fix_deposit']['reminder'];
$tds = $collection['fix_deposit']['tds'];
$name = $collection['fix_deposit']['name'];
$email = $collection['fix_deposit']['email'];
$mobile = $collection['fix_deposit']['mobile'];

if($start_date >= $from && $start_date <= $to)
{
$n++;
$start_date2 = date('d-M-Y',$start_date->sec);
$maturity_date2 = date('d-M-Y',$maturity_date->sec);
?>
<tr>
<td style="text-align:center;"><?php echo $n; ?></td>
<td style="text-align:center;"><?php echo $bank_name; ?></td>
<td style="text-align:center;"><?php echo $name; ?></td>
<td style="text-align:center;"><?php echo $email; ?></td>
<td style="text-align:center;"><?php echo $mobile; ?></td>
<td style="text-align:center;"><?php echo $account_ref; ?></td>
<td style="text-align:center;"><?php echo $principal_amt; ?></td>
<td style="text-align:center;"><?php echo $start_date2; ?></td>
<td style="text-align:center;"><?php echo $maturity_date2; ?></td>
<td style="text-align:center;"><?php echo $interest_rate; ?></td>
<td style="text-align:center;"><?php echo $bank_name; ?></td>
<td style="text-align:center;"><?php echo $auto_id; ?></td>
<td style="text-align:center;"><?php echo $bank_name; ?></td>
<td style="text-align:center;"><?php echo $remark; ?></td>
</tr>
<?php
}}
?>
</table>
