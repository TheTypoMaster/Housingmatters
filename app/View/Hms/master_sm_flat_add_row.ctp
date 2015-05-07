

<table width="100%" id="tab<?php echo $t; ?>" >
<tr class="table table-bordered table-hover">

<td width="25%" style="text-align:center;">
<select name="wing_name<?php echo $t; ?>" class=" m-wrap medium" id="sel<?php echo $t; ?>" onchange="show_flat(this.value,<?php echo $t; ?>)" >
<option value="">Select Category</option>
<?php
foreach ($user_wing as $collection) 
{
$wing_id=$collection['wing']["wing_id"];
$wing_name=$collection['wing']["wing_name"];
?>
<option value="<?php echo $wing_id ?>"><?php echo $wing_name ?></option>
<?php } ?>
</select>
</td>					
	
					
					
					
<td width="25%" style="text-align:center;" id="showflat<?php echo $t; ?>">
</td>					
					
<td width="25%" style="text-align:center;">
<select name="flat_type<?php echo $t; ?>" class="m-wrap medium" id="fltp<?php echo $t; ?>">
<option value="">--SELECT FLAT TYPE--</option>
<?php
foreach($cursor4 as $collection)
{
$auto_id = (int)$collection['flat_type_name']['auto_id'];	
$flat_name = $collection['flat_type_name']['flat_name'];	
?>
<option value="<?php echo $auto_id; ?>"><?php echo $flat_name; ?></option>
<?php } ?>
</select>
</td>					
					
<td width="25%" style="text-align:center;">
<input type="text" name="area<?php echo $t; ?>" class="m-wrap medium" id="ar<?php echo $t; ?>"/>
</td>
</tr>
					
</table>	