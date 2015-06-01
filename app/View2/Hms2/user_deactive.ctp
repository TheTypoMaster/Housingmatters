<script>
function user_active(status,act_id,auto)
{
$('#replace'+auto).load('user_deactive_ajax?t=' + act_id + '&d='+status + '&a='+auto );
}
</script>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Deactive Users
</div>
<div class="tab-content">
<div class="tab-pane active" id="tab_1_2">
<div class="portlet box ">
<div class="portlet-body">
<table class="table table-striped table-bordered" id="sample_2">
<thead>
<tr>
<th>Sr No.</th>
<th>User Name</th>
<th>Flat</th>
<th>Status</th>
<th>Email</th>
<th>Mobile</th>
<th>Change Access</th>
</tr>
</thead>
<tbody>
<?php
////connection//////
$i=0;
foreach ($result_user_deactive as $collection) 
{ 
$i++;
$user_id=(int)$collection['user']['user_id'];
$tenant=(int)$collection['user']['tenant'];
$user_name=$collection['user']["user_name"];
$wing=$collection['user']["wing"];
$flat=$collection['user']["flat"];
$email=$collection['user']["email"];
$mobile=$collection['user']["mobile"];
$det=$collection['user']["deactive"];
$wing_flat= $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
if($tenant==1)
{
$status='Owner';
}
else
{
$status='Tenant';
}
?>
<tr class="odd gradeX" >
<td><?php echo $i; ?></td>
<td><?php echo $user_name; ?></td>
<td><?php echo $wing_flat; ?></a></td>
<td><?php echo $status; ?></td>
<td><?php echo $email; ?> </td>
<td><?php echo $mobile; ?> </td>
<td id='replace<?php echo $i ; ?>'>
<?php 
if($det==0)
{?>
<button type="button" class="btn blue" onclick='user_active(0,<?php echo $user_id ; ?>,<?php echo $i ; ?>);' >Active</button> 
<?php } ?>
<?php 
if($det==1)
{?>
<button type="button" class="btn red " onclick='user_active(1,<?php echo $user_id ; ?>,<?php echo $i ; ?>);'>Deactive</button>
<?php 
} ?>
 </td>                                        
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>
</div>
</div>