<?php
foreach($flat_detail as $data)
{
$other_charges_array = @$data['flat']['other_charges'];	
}

if(empty($other_charges_array))
{
?>
<p style="font-size:16px; font-weight:500; color:#F00;">No Other Charges Found for This Page</p>
<?php	
}
else
{
?>
<table class="table table-bordered">
<tr>
<th style="text-align:left;">Other Charges Name</th>
<th style="text-align:left;">Amount</th>
<th style="text-align:center;">Delete</th>
</tr>
<?php
foreach($result_ledger_account as $ledger_detail)
{	
$other_charges_name = "";
$ledger_id = (int)$ledger_detail['ledger_account']['auto_id'];
$ledger_name = $ledger_detail['ledger_account']['ledger_name'];
$other_charges_name = @$other_charges_array[$ledger_id];
if(!empty($other_charges_name))
{
?>
<tr>
<td style="text-align:left;"><?php echo $ledger_name; ?></td>
<td style="text-align:left;"><?php echo $other_charges_name; ?></td>
<td style="text-align:center;"><a href="delete_other_charges?delete_id=<?php echo $ledger_id; ?>" class="btn mini black">delete</a></td>
</tr>
<?php
}
}
?>
</table>


<?php
}
?>