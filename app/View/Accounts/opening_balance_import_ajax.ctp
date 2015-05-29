<div class="modal-header">
<h4 id="myModalLabel1">Import csv</h4>
</div>
<div class="modal-body">
<table class="table table-bordered" style="width:100%; background-color:white;">
<tr>
<th>Date</th>
<th>Account Name</th>
<th>Amount Type</th>
<th>Amount (Opening Balance)</th>
</tr>
<?php foreach($table as $data){ 
$type = $data[2];
?>
<tr>
<td><input type="text" class="date-picker m-wrap span10" data-date-format="dd-mm-yyyy" name="date" id="date" value="<?php echo $data[0]; ?>"></td>
<td>
<?php
$ledger_type = (int)$data[5];
$auto_id = (int)$data[4];
?>
<select class="m-wrap span10">
<option value="">Select</option>
<?php 
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['ledger_sub_account']['auto_id'];
$sub_ledger_name = $collection['ledger_sub_account']['name'];	
?>
<option value="<?php echo $auto_id1; ?>" <?php if($ledger_type == 1 && $auto_id1 == $auto_id) { ?> selected="selected" <?php } ?> ><?php echo $sub_ledger_name; ?></option>
<?php
}
foreach($cursor2 as $collection)
{
$auto_id2 = (int)$collection['ledger_account']['auto_id'];	
$ledger_name = $collection['ledger_account']['ledger_name'];
?>
<option value="<?php echo $auto_id2; ?>" <?php if($ledger_type == 2 && $auto_id2 == $auto_id) { ?> selected="selected" <?php } ?> ><?php echo $ledger_name; ?></option>
<?php 	
}
?>
</select>
<td>
<?php
$d = (int)strcasecmp("Debit",$type);
$c = (int)strcasecmp("Credit",$type);
?>
<select class="m-wrap span10">
<option value="">Select</option>
<option value="1" <?php if($d == 0) { ?> selected="selected" <?php } ?> >Debit</option>
<option value="2" <?php if($c == 0) { ?> selected="selected" <?php } ?>>Credit</option>
</select>
</td>
<td><input type="text" value="<?php echo $data[3]; ?>" class="m-wrap span10" /></td>	
</tr>
<?php } ?>
</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn" id="import_close">Cancel</button>
	<button type="submit" class="btn blue ">Import</button>
</div>
