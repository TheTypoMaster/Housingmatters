<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>


<div style="background-color:#fff;padding:10px;">
<table class="table table-striped table-bordered" id="sample_2">
<thead>
    <tr>
    <th width="7%"> Sr. No.</th>
    <th width="50%">Title</th>
    <th>Posted on</th>
	 <th>Time</th>
	 <th>Location</th>
    <th></th>
    </tr>
</thead>
<tbody>
<?php
$i=0;
foreach($result_gov_invite as $data){
$gov_id=(int)$data['governance_invite']['governance_invite_id'];
$subject=$data['governance_invite']['subject'];
$message_web=$data['governance_invite']['message'];
$date=$data['governance_invite']['date'];
$time=$data['governance_invite']['time'];
$type=$data['governance_invite']['type'];
$location=$data['governance_invite']['location'];
if($type==3)
{
$visible=$data['governance_invite']['visible'];
$sub_visible=$data['governance_invite']['sub_visible'];
}
$i++;
?>
<tr class="odd gradeX">
    <td><?php echo $i ; ?></td>
    <td><?php echo $subject ; ?></td>
    <td><?php echo $date ; ?></td>
	 <td><?php echo $time ; ?></td>
	 <td><?php echo $location ; ?></td>
    <td><a href="<?php echo $webroot_path; ?>Governances/governance_invite_view1/<?php echo $gov_id; ?>" rel='tab' class="btn mini yellow tooltips" ><i class="icon-search"></i> View </a>
	
	</td>
<?php } ?>	
	
</tr>

</tbody>
</table> 
</div>