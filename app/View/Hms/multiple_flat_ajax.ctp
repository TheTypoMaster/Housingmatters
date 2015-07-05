<br>
<div>
<?php
foreach($result_user as $data)
{
  $user_name=$data['user']['user_name'];
  $wing=(int)$data['user']['wing'];
  $flat=(int)$data['user']['flat'];
  @$multiple_flat=$data['user']['multiple_flat'];

  $wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
}
$r=explode('-',$wing_flat);
  @$wing_name1=$r[0];
 @$flat_name=$r[1];

?>
<br>

<div style="float:left; width: 100%;">
<div style="padding-right: 2px;float: left;">
<select class="sel_wing" name='sel_wing_id' id='ccf'>
<option value=''>Select Wing</option>
<?php

foreach($result_wing as $data)
{
	$wing_id=(int)$data['wing']['wing_id'];
	$wing_name=$data['wing']['wing_name'];
	
?>

<option value="<?php echo $wing_id ; ?> "> <?php echo $wing_name ; ?> </option>

<?php } ?>
</select>
<label id='ccf'></label>
</div>
<div style="padding-right: 2px;float: left;">
<div id='sel_flat11'>
<select >
<option>Select flat</option>
</select>
</div>
</div>
<div style="padding-right: 2px;float: left;">
<select name="noc_charg" id="test">
<option value=""> Select noc type</option>
<option value="1">Self Occupied</option>
<option value="2">Leased</option>
</select>
<label id="test"></label>
</div>


<div>
<button type="submit" class="btn blue" >Submit </button>
</div>
</div>

<br/>
<table  class='table table-striped table-bordered'>
<tr>
<td>Wing</td>
<td>Flat</td>
</tr>

<?php if(empty($multiple_flat))
{
?>
<tr>
<td>
<?php echo $wing_name1 ;?>
</td>
<td>
<?php echo $flat_name ;?>
</td></tr>
<?php 
} ?>
<?php
if(!empty($multiple_flat))
{

foreach($multiple_flat as $data)
{
  $wing_id=$data[0];
  $flat_id=$data[1];
 $wing_flat1 = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));
 $r2=explode('-',$wing_flat1);
  $wing_name2=$r2[0];
  $flat_name2=$r2[1];
 
?>
<tr>
<td>
<?php echo $wing_name2;?>
</td>
<td>
<?php echo $flat_name2 ;?>
</td> </tr>
<?php } } ?>

</table>

</div>