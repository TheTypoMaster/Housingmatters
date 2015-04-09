<select name="flat_no<?php echo $t; ?>" class="m-wrap medium" id="fl<?php echo $t; ?>">
<option value="">--Select Flat Number--</option>
<?php
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['flat']['flat_id'];	
$flat_number = $collection['flat']['flat_name'];	
	
?>
<option value="<?php echo $auto_id; ?>"><?php echo $flat_number; ?></option>
<?php } ?>
</select>
