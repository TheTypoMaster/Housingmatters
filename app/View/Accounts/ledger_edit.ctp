<?php
if($edit == 0) { 
foreach($cursor1 as $collection)
{
$group_id = (int)$collection['ledger_account']['group_id'];	
$ledger_name = $collection['ledger_account']['ledger_name'];
}
?>

<div class="modal-header" >
	<h4 id="myModalLabel1">Edit Ledger Account</h4>
</div>
<div class="modal-body">
	
	
	<div class="control-group">
	  <label class="control-label">Select Account Group</label>
	  <div class="controls">
		<select id="group" class="m-wrap span10">
        <?php
		foreach($cursor2 as $collection2)
		{
		$group_id2 = (int)$collection2['accounts_group']['auto_id'];
		$group_name = $collection2['accounts_group']['group_name'];
		?>
        <option value="<?php echo $group_id2; ?>" <?php if($group_id2 == $group_id) { ?> selected="selected" <?php } ?>><?php echo $group_name; ?></option>
        <?php } ?>
        </select>
	  </div>
   </div>
   
 
 
 
<div class="control-group">
	  <label class="control-label">Fill Ledger Account</label>
	  <div class="controls">
		<input type="text" id="ledger" class="m-wrap span10" value="<?php echo $ledger_name; ?>" />
	  </div>
   </div> 
 
 				   
					   
</div>
<div class="modal-footer">
	<button class="btn" id="close_edit">Close</button>
	<button class="btn blue save_edited_terms" tems_id="<?php echo $ledger_id; ?>">Save</button>
</div>
<?php  } ?>


<?php if($edit == 1) { ?>
<div class="modal-body">Ledger Account Updated Sucessfully</div>
<div class="modal-footer"><button class="btn blue" id="close_edit">Ok</button></div>
<?php } ?>