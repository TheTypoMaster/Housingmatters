

<table width="100%" id="tab<?php echo $t; ?>" >
<tr class="table table-bordered table-hover">

<td width="21%" style="text-align:center;">
<select name="wing_name<?php echo $t; ?>" class=" m-wrap medium" id="sel<?php echo $t; ?>"  >
<option value="">Select Category</option>
<?php
foreach ($user_wing as $collection) 
{
$wing_id=$collection['wing']["wing_id"];
$wing_name=$collection['wing']["wing_name"];
?>
<option value="<?php echo $wing_id ?> "><?php echo $wing_name ?></option>
<?php } ?>
</select>
</td>					
					
					
					
					
<td width="21%" style="text-align:center;">
<input type="text" class="m-wrap medium" id="flat_id<?php echo $t; ?>" name="flat_name<?php echo $t; ?>" maxlength="4" onkeyup="search_topic();">
</td>					
					
					
					
<td width="21%" style="text-align:center;">
<select name="flat_type<?php echo $t; ?>" class="m-wrap medium" onchange="show_area(this.value,<?php echo $t; ?>)" id="fltp<?php echo $t; ?>">
<option value="">--SELECT FLAT TYPE--</option>
<?php
foreach($cursor2 as $collection)
{
$auto_id = (int)$collection['flat_type']['flat_type_id'];	
//$flat_name = $collection['flat_type']['flat_name'];


$fl_tp = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($auto_id)));		
foreach($fl_tp as $collection)
{
$flat_name = $collection['flat_type_name']['flat_name'];
}
?>
<option value="<?php echo $auto_id; ?>"><?php echo $flat_name; ?></option>
<?php } ?>
</select>
</td>					
					
					
<td width="21%" style="text-align:center;" id="show<?php echo $t; ?>">
</td>
<td style="text-align:center;" width="16%">
<select name="noctp<?php echo $t; ?>" class="m-wrap small" id="noc<?php echo $t; ?>">
<option value="">Select</option>
<?php
foreach($cursor3 as $collection)
{
$noc_id = (int)$collection['noc_type']['auto_id'];
$noc_name = $collection['noc_type']['noc_type_name'];
?>
<option value="<?php echo $noc_id; ?>"><?php echo $noc_name; ?></option>
<?php
}
?>
</select>
</td>
</tr>
					
</table>	