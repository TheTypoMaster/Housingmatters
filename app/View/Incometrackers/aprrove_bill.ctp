<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<br />
<table style="background-color:white;" class="m-wrap table table-bordered">
<tr>
<th style="text-align:left;">Bill Number</th>
<th style="text-align:left;">Member name</th>
<th style="text-align:left;">Tenant</th>
<th style="text-align:left;">Flat area(Sq.Ft.)</th>
<th style="text-align:left;">Unit Number</th>
<th style="text-align:left;">Bill Amount</th>
<th style="text-align:left;">Approval</th>
</tr>
<?php
foreach($cursor1 as $collection)
{
$bill_number = $collection['regular_bill']['receipt_id'];
$bill_amt = $collection['regular_bill']['remaining_amount'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
//$bill_number = $collection['regular_bill'][''];

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}


$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat_id,$wing_id)));	
foreach($result5 as $collection)
{
$area = $collection['flat']['flat_area'];
$unit_number = $collection['flat']['flat_name'];
}

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	
if($tenant == 1)
{
$ten = "Owner";	
}
else
{
$ten = "Tenant";		
}
?>
<tr>
<td style="text-align:right;"><?php echo $bill_number; ?></td>
<td style="text-align:left;"><?php echo $user_name; ?> &nbsp;&nbsp; (<?php echo $wing_flat; ?>)</td>
<td style="text-align:left;"><?php echo $ten; ?></td>
<td style="text-align:left;"><?php echo $area; ?> &nbsp;&nbsp; Sq.Ft.</td>
<td style="text-align:right;"><?php echo $unit_number; ?></td>
<td style="text-align:right;"><?php echo $bill_amt; ?></td>
<td style="text-align:left;"><a href="aprrove_bill?del=<?php echo $bill_number ?>" class="btn mini blue">Approve  <i class="icon-ok"></i></a></td>
</tr>
<?php
}
?>
</table>