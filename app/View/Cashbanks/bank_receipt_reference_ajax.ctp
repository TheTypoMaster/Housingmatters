<?php
if($type == 1 && !empty($value1))
{
foreach ($cursor1 as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}

?>
<?php
$result_rb = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill'),array('pass'=>array(@$user_id)));
foreach ($result_rb as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];
$bill_date = $collection['regular_bill']['date'];
$bill_due_date = $collection['regular_bill']['due_date'];
$remain_amt = (int)$collection['regular_bill']['remaining_amount'];
$gt_amt = (int)$collection['regular_bill']['g_total'];

$bill_date = date('d-m-Y',strtotime(@$bill_date));
$bill_due_date = date('d-m-Y',strtotime(@$bill_due_date));
}

?>

Receipt Applied towards following bill:
<table style="width:100%;" border="1" class="table table-bordered">
<tr>
<th style="text-align:center;">Bill No.</th>
<th style="text-align:center;">Bill Date</th>
<th style="text-align:center;">Bill Due Date</th>
<th style="text-align:center;">Bill Amount</th>
<th style="text-align:center;">Due Amount</th>
<th style="text-align:center;">Amount Applied</th>
</tr>
         

<tr>
<td style="text-align:center;"><?php echo $bill_no; ?></td>
<td style="text-align:center;"><?php echo $bill_date; ?></td>
<td style="text-align:center;"><?php echo $bill_due_date; ?></td>
<td style="text-align:center;"><?php echo $gt_amt; ?></td>
<td style="text-align:center;"><?php echo $remain_amt; ?></td>
<td style="text-align:center;"><input type="text" class="m-wrap small" style="background-color:white !important;" name="amount" id="ab"/></td>
</tr>
<tr>
<td colspan="6" style="text-align:right;"><label id="ab"></label></td>
</table>

<input type="hidden" value="<?php echo $bill_no; ?>" name="bill_no" id="bll" />
<label report="amt2" class="remove_report"></label>

<?php
}
if($type == 2)
{
?>	
<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="aammtt" class="m-wrap span9" id="mmm" />
</div>
<label report="mmm"></label>
<br />	
<?php	
}
?>












