<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_account_coa" class="btn yellow" rel='tab'>Ledger Accounts</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_accounts_coa" class="btn yellow" rel='tab'>Ledger Sub Accounts</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_accounts_view" class="btn purple">Master Ledger  Account View</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_account_view" class="btn yellow">Master Ledger Sub Account View</a>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<br><br />
<center>
<div class="portlet box grey" style="width:90%;">
<div style="background-color:#B2B2B2; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
<b style="color:white;">  Ledger Accounts </b>
</div>
<div class="portlet-body">
<div style="width:100%;">
<table style="width:100%; background-color:white;" class="table table-bordered" id="sample_2">
<thead>			
<tr>
<th>Sr.No.</th>
<th>Account Category</th>
<th>Accounts Group</th>
<th>Ledger</th>
<th>Edit/Delete</th>
</tr>        
</thead>
<tbody>
<?php
$n = 1;
foreach ($cursor2 as $collection) 
{
$sub_id = (int)$collection['ledger_account']['group_id'];
$name = $collection['ledger_account']['ledger_name'];
$auto_id5 = (int)$collection['ledger_account']['auto_id'];
$edit_id = (int)$collection['ledger_account']['edit_user_id'];
$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($sub_id)));
foreach ($result_ag as $collection) 
{
$accounts_id = (int)$collection['accounts_group']['accounts_id'];	
$group_name = $collection['accounts_group']['group_name'];	
}

$result_ac = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_category'),array('pass'=>array($accounts_id)));		   
foreach ($result_ac as $collection) 
{
$main_name = $collection['accounts_category']['category_name'];	
}
?>        
			
<tr>
<td><?php echo $n; ?></td>
<td><?php echo $main_name; ?></td>
<td><?php echo $group_name; ?></td>
<td><?php echo $name; ?></td>
<td> 
<?php if($edit_id == $s_user_id)
{
?>
<a href="#myModal5<?php echo $auto_id5; ?>" role="button" class="btn mini purple" data-toggle="modal">Edit</a>
<?php  } ?>
</td>
</tr>
<?php $n++; } ?> 
</tbody>  
</table>
</div>  
</div> 
</div>      
</center>