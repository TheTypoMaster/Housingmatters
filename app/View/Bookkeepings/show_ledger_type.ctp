<?php
if($value == 15 || $value == 33 || $value == 35)
{
?>
<div class="control-group">
<div class="controls">
<select class="span12 m-wrap chosen" name="l_type_name<?php echo $t; ?>" id="sul<?php echo $t; ?>">
<option value="">--SELECT--</option>
<?php

foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
$name = $collection['ledger_sub_account']['name'];
?>	
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>
<?php } ?>
</select>
</div>
</div>

<?php
}
else
{
?>
	


<?php	
}
?>

<?php
if($value == 34)
{
?>
<div class="control-group">
<div class="controls">
<select class="span12 m-wrap chosen" name="l_type_name<?php echo $t; ?>" id="sul<?php echo $t; ?>">
<option value="">--SELECT--</option>
<?php

foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
$user_id = (int)$collection['ledger_sub_account']['user_id'];

$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
	foreach ($result_user as $collection) 
	{
	$user_name = $collection['user']['user_name'];  
	$wing_id = (int)$collection['user']['wing'];
	$flat_id = (int)$collection['user']['flat'];
	}
	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat_with_brackets'),array('pass'=>array($wing_id,$flat_id)));	

?>	
<option value="<?php echo $auto_id; ?>"><?php echo $user_name; ?> &nbsp;&nbsp; <?php echo $wing_flat; ?></option>
<?php } ?>
</select>
</div>
</div>

<?php
}