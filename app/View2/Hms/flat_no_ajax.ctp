<select name="area_id<?php echo $t; ?>" class="m-wrap medium" id="ar<?php echo $t; ?>">
<option value="">--SELECT AREA--</option>
<?php
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['flat_master']['auto_id'];	
$flat_area = $collection['flat_master']['flat_area'];	
	
?>
<option value="<?php echo $auto_id; ?>"><?php echo $flat_area; ?></option>
<?php } ?>
</select>
