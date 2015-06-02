<div class="modal-header">
<h4 id="myModalLabel1">Import csv</h4>
</div>
<div class="modal-body">
<div id="vali"></div>
<table class="table table-bordered" style="width:100%; background-color:white;" id="open_bal">
<tr>
<th>Date</th>
<th>Account Name</th>
<th>Amount Type</th>
<th>Amount (Opening Balance)</th>
<th>Delete</th>
</tr>
<?php
$j=0;
?>
<?php foreach($table as $data){ 
$amt_type1 = "";
$amt1 = "";
//$type = $data[1];
$amt_type1 = $data[1];
$amt1 = $data[2];
if(!empty($amt_type1) && !empty($amt1))
{
$j++;	
$type = $data[1];
$group_id = (int)$data[5];
$group = $data[6];
?>
<tr id="tr<?php echo $j; ?>">
<td><input type="text" class="date-picker m-wrap span10" data-date-format="dd-mm-yyyy" name="date" id="date" style="background-color:white !important;"></td>
<td>
<?php
$ledger_type = (int)$data[4];
$auto_id = (int)$data[3];
?>
<select class="m-wrap span10">
<option value=""><?php echo $group; ?></option>
<?php 
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['ledger_sub_account']['auto_id'];
$sub_ledger_name = $collection['ledger_sub_account']['name'];
$ledger_id = (int)$collection['ledger_sub_account']['ledger_id'];	
?>
<option value="<?php echo $auto_id1; ?>,1" <?php if($ledger_type == 1 && $auto_id1 == $auto_id) { ?> selected="selected" <?php } ?> ><?php echo $sub_ledger_name; ?></option>
<?php
}
foreach($cursor2 as $collection)
{
$auto_id2 = (int)$collection['ledger_account']['auto_id'];	
$ledger_name = $collection['ledger_account']['ledger_name'];
$grp_id = (int)$collection['ledger_account']['group_id'];
?>
<option value="<?php echo $auto_id2; ?>,2" <?php if($ledger_type == 2 && $auto_id2 == $auto_id) { ?> selected="selected" <?php } ?> ><?php echo $ledger_name; ?></option>
<?php 	
}
?>
</select>
<td>
<?php
$e = (int)strcasecmp("Debit",$type);
$c = (int)strcasecmp("Credit",$type);
?>
<select class="m-wrap span10">
<option value="">Select</option>
<option value="1" <?php if($e == 0) { ?> selected="selected" <?php } ?>>Debit</option>
<option value="2" <?php if($c == 0) { ?> selected="selected" <?php } ?>>Credit</option>
</select>
</td>
<td><input type="text" value="<?php echo $data[2]; ?>" class="m-wrap span10" style="background-color:white !important;" /></td>
<td><a href="#" role="button" class="btn mini red delete" del="<?php echo $j; ?>"><i class="icon-remove icon-white"></i></a></td>	
</tr>
<?php }} ?>
</table>
</div>
<div class="modal-footer">
<a class="btn" href="<?php echo $webroot_path; ?>Accounts/opening_balance_import" rel="tab">Cancel</a>
<button type="submit" class="btn blue import_op">Import</button>
</div>
