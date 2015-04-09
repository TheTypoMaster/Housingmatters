<script>
function user_active(status,act_id,auto)
{
$('#replace'+auto).load('user_deactive_ajax?t=' + act_id + '&d='+status + '&a='+auto );
}
</script>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Active & Deactive Users
</div>

<div id="replace"></div>
<div class="tab-content">
<div class="tab-pane active" id="tab_1_2">
<div class="portlet box ">
<div class="portlet-body">
<table class="table table-striped table-bordered" id="">
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
@$det=$collection['user']["deactive"];
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
<td>
 <div class="control-group">
                              <!--<label class="control-label">Toggle Buttons with Text</label>-->
                              <div class="controls">
                                 <div class="text-toggle-button check">
                                    <input type="checkbox" class="toggle" id="<?php echo $i; ?>" <?php if($det==0){ ?>  checked=""/ <?php } ?> idd="<?php echo $user_id ; ?>"  >
                                 </div>
                              </div>
                           </div>
 </td>                                        
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>
</div>
</div>

<script>
$(document).ready(function(){
$( ".checkable" ).click(function() {
var id=$(this).parent().children().attr("id");
 var did=$(this).parent().children().attr("idd");
value = +$('#'+id).is( ':checked' );
$('#replace').load('user_deactive_ajax?t=' + did + '&d='+value);
});


});
</script>

