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
<label class="m-wrap pull-right">Search: <input type="text" id="search" class="m-wrap medium" style="background-color:#FFF !important;"></label>
<table class="table table-striped table-bordered dataTable" width="100%" >
<thead>
    <tr>
    <th>Meeting ID </th>
	<th >Date of Notice</th>
    <th >Meeting Title</th>
	 <th>Meeting Type</th>
    <th >Meeting Date</th>
	 <th >Meeting Time</th>
	 <th>Meeting Location</th>
	 <th>Invitees </th>
    <th></th>
    </tr>
</thead>
<tbody id="table">
<?php
$i=0;
foreach($result_gov_invite as $data){
$gov_id=(int)$data['governance_invite']['governance_invite_id'];
$subject=$data['governance_invite']['subject'];
$notice_of_date=@$data['governance_invite']['notice_of_date'];
$message_web=$data['governance_invite']['message'];
$date=$data['governance_invite']['date'];
$time=$data['governance_invite']['time'];
$type=$data['governance_invite']['type'];
$location=$data['governance_invite']['location'];
$meeting_type=(int)@$data['governance_invite']['meeting_type'];
 $user=@$data['governance_invite']['user'];
 $invite_user=sizeof($user);
 if($meeting_type==1)
 {
	$moc="Managing Committee";
 
 }
 if($meeting_type==2)
 {
	$moc="General Body";
 
 }
 if($meeting_type==3)
 {
	$moc="Special General Body";
 
 }

if($type==3)
{
$visible=$data['governance_invite']['visible'];
$sub_visible=$data['governance_invite']['sub_visible'];
}
$i++;
?>
<tr>
    <td><?php echo $gov_id; ?></td>
	 <td><?php echo $notice_of_date ; ?></td>
    <td><?php echo $subject ; ?></td>
	 <td><?php echo $moc ; ?></td>
    <td><?php echo $date ; ?></td>
	 <td><?php echo $time ; ?></td>
	 <td><?php echo $location ; ?></td>
	  <td><span class="label label-info"><?php echo $invite_user ; ?></span></td>
    <td><a href="<?php echo $webroot_path; ?>Governances/governance_invite_view1/<?php echo $gov_id; ?>" rel='tab' class="btn mini yellow tooltips" ><i class="icon-search"></i> View </a>
	</td>
<?php } ?>	
	
</tr>

</tbody>
</table> 
</div>

<script type="text/javascript">
		 var $rows = $('#table tr');
		 $('#search').keyup(function() {
			var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
			
			$rows.show().filter(function() {
				var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
				return !~text.indexOf(val);
			}).hide();
		});
 </script>