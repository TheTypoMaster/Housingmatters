<div style="background-color:white; width:100%; overflow:auto;">
<div class="modal-header">
<h4 id="myModalLabel1">Import csv</h4>
</div>
<div class="modal-body" style="overflow:auto;">
<div id="vali"></div>
<table id="open_bal2" style="width:30%;">
<tr><td>
<input type="text" class="date-picker m-wrap span10" data-date-format="dd-mm-yyyy" name="date" id="date" style="background-color:white !important;" placeholder="Date" >
</td></tr>
</table>
<br />
<table class="table table-bordered" style="width:100%; background-color:white;" id="open_bal">
<tr>
<th>Account Group</th>
<th>Account Name</th>
<th>Debit</th>
<th>Credit</th>
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
<td>
<select class="m-wrap span10">
<option value="">Select Group Account</option>
<?php
foreach($cursor3 as $collection)
{
$group_id = (int)$collection['accounts_group']['auto_id'];
$group_name1= $collection['accounts_group']['group_name'];
?>
<option value="<?php echo $group_id; ?>" <?php if($group_id == $group_id) { ?> selected="selected" <?php } ?>><?php echo $group_name1; ?></option>
<?php } ?>
</select>
</td>
<td>
<?php
$ledger_type = (int)$data[4];
$auto_id = (int)$data[3];
?>
<input type="text" value="<?php echo $data[0]; ?>" class="m-wrap span10" style="background-color:white !important;" />
<td>
<?php
$e = (int)strcasecmp("Debit",$type);
$c = (int)strcasecmp("Credit",$type);
?>
<input type="text" class="m-wrap span10" value="<?php if($e == 0) { echo $data[2]; } ?>" style="background-color:white !important;"/>
</td>
<td>
<input type="text" class="m-wrap span10" value="<?php if($c == 0) { echo $data[2]; } ?>" style="background-color:white !important;"/>
</td>
<td><a href="#" role="button" class="btn mini red delete" del="<?php echo $j; ?>"><i class="icon-remove icon-white"></i></a></td>	
</tr>
<?php }} ?>
<tr>
<th colspan="2" style="text-align:right;">Total</th>
<th id="deb"></th>
<th id="cre"></th>
<th></th>
</tr>
</table>

</div>
<div class="modal-footer">
<a class="btn" href="<?php echo $webroot_path; ?>Accounts/opening_balance_import" rel="tab">Cancel</a>
<button type="submit" class="btn blue import_op">Import</button>
</div>
</div>