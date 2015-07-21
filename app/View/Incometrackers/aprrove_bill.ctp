<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$nn = 55;
foreach($cursor1 as $collection)
{
$bill_number = $collection['regular_bill']['receipt_id'];
$bill_amt = $collection['regular_bill']['remaining_amount'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$nn = 555;
}

?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php if($nn == 555) { ?>
<form method="post">
<br />
<label class="checkbox line" style="text-align:right; margin-right:3%;">
<div class="checker" id="uniform-undefined"><span><input type="checkbox" value="all" style="opacity: 0;" name="all" onclick="selall()" id="aaa"></span></div>Select All 
</label>

<table style="background-color:white;" class="m-wrap table table-bordered">
<tr>
<th style="text-align:left;">Bill Number</th>
<th style="text-align:left;">Member name</th>
<th style="text-align:left;">Flat area(Sq.Ft.)</th>
<th style="text-align:left;">Wing Name</th>
<th style="text-align:left;">Unit Number</th>
<th style="text-align:left;">Bill Amount</th>
<th style="text-align:left;">Selection</th>
</tr>
<?php
$r=0;
foreach($cursor1 as $collection)
{
$r++;
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

$result6 = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_fetch'),array('pass'=>array($wing_id)));	
foreach($result6 as $data)
{
$wing_name = $data['wing']['wing_name'];	
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
<td style="text-align:left;"><?php echo $user_name; ?></td>
<td style="text-align:left;"><?php echo $area; ?> &nbsp;&nbsp; Sq.Ft.</td>
<td style="text-align:left;"><?php echo $wing_name; ?></td>
<td style="text-align:right;"><?php echo $unit_number; ?></td>
<td style="text-align:right;"><?php echo $bill_amt; ?></td>
<td style="text-align:left;">
<label class="checkbox line">
<div class="checker" id="uniform-undefined"><span><input type="checkbox" value="<?php echo $bill_number; ?>" style="opacity: 0;" name="app<?php echo $r; ?>" class="chhh"></span></div> 
</label>
</td>
</tr>
<?php
}
?>
</table>
<div style="width:100%; text-align:right;">
<button type="submit" class="btn green" name="sub" style="margin-right:3%;">Approve</button>
</div>
</form>
<?php } 
if($nn == 55)
{
?>
<br /><br />									  
<center>									  
<h3 style="color:red;"><b>No Bill Found for Approval</b></h3>									  
</center>									  
<br /><br />	
<?php 
} 
?>





<script>
function selall()
{
if($('#aaa').is(":checked"))
{
$(".chhh").parent('span').addClass('checked');
$(".chhh").attr('checked','checked');
}
else
{
$(".chhh").parent('span').removeClass('checked');
$(".chhh").removeAttr('checked','checked');
}
}
</script>