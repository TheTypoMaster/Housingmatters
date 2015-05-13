<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {

$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<?php 
foreach ($result_society as $collection){ 
	$society_name=$collection['society']['society_name'];
}
?>
<div align="center">
	<h3 class="page-title"><?php echo $society_name; ?></h3>
	<div class="pull-right">
	<a href='society_member_excel'class='blue mini btn' download='download'  ><i class=" icon-download-alt"></i> Download in Excel</a>
		<a href="<?php echo $webroot_path; ?>Hms/society_member_view" class="btn yellow" rel="tab">All Active Users</a>
		<a href="<?php echo $webroot_path; ?>Hms/user_deactive" class="btn" rel="tab">All De-active Users</a>
	</div>
	<div class="pull-left"> 
		<a class="btn mini green"></a> <span>Owner &nbsp; 
		</span> <a class="btn mini purple"></a> <span> &nbsp; Tenant &nbsp; 
		&nbsp; 
		<span style="color:red; font-size:14px;"> <i class=' icon-star'></i> </span> 
		<span> Awaiting User Validation  </span>
	</div>
</div>

<div class="portlet-body" style="background-color:#fff;">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>Validation Pending</th>
				<th>Portal joining date</th>
				<th>Role</th>
				<th>De-active</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($result_user as $data) { 
		$user_id=$data['user']['user_id'];
		$user_name=$data['user']['user_name'];
		$result_user1 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
		foreach ($result_user1 as $collection) 
		{	
			$role_ids=$collection['user']['role_id'];
			$wing=$collection['user']['wing'];
			$email=$collection['user']['email'];
			$mobile=$collection['user']['mobile'];
			$flat=$collection['user']['flat'];
			$tenant=$collection['user']['tenant'];
			$date=$collection['user']['date'];
			@$profile_status=$collection['user']['profile_status'];
		}
		$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
		if($tenant==1){ $color="#13D17E"; }else{ $color="#C709F0"; }
		
		$role_name=array();
		if(sizeof($role_ids)>0){
			foreach($role_ids as $role_id){
				$role_name[] = $this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_rolename_via_roleid'),array('pass'=>array($role_id)));
			}
		}
		
		$role_name_des=implode(",",$role_name);
		unset($role_name);
		?>
			<tr id="tr<?php echo $user_id; ?>">
				<td style="color:<?php echo $color; ?>">
					<?php echo $user_name; ?>
					<?php if($profile_status!=2) { ?>  
					<span style="color:red; font-size:10px;"> <i class=' icon-star'></i> </span> 
					<?php } ?> 
				</td>
				<td><?php echo $wing_flat; ?></td>
				<td><?php echo $mobile; ?></td>
				<td><?php echo $email; ?></td>
				<td>
					<?php if($profile_status!=2) { ?>  
					<?php if(!empty($email)) { ?> 
					<a href="#" role='button' class="btn green mini resend" id="<?php echo $user_id; ?>">Send Reminder</a> <?php } elseif(!empty($mobile)) { ?>
					<a href="#" role='button' class="btn green mini resend_sms" id="<?php echo $user_id; ?>">Send Reminder</a> <?php } ?>
					<?php } ?>
				</td>
				<td><?php echo $date; ?></td>
				<td><?php echo $role_name_des; ?></td>
				<td><a href="#" class="btn red mini deactive" id="<?php echo $user_id; ?>" role="button">De-active</a></td>
			</tr>
		<?php } ?>	
		</tbody>
	</table>
	
</div>

<script>
$(document).ready(function() {
	$(".deactive").bind('click', function(e){
		$(this).text("Wait...");
		var id=$(this).attr("id");
		$.ajax({
			url: "<?php echo $webroot_path; ?>/Hms/user_deactive_ajax?t="+id+"&d=0",
			}).done(function(response) {
				$("tr#tr"+id).html('<td colspan="8"><div style="margin-bottom: 0px;" class="alert alert-success"><strong>Success!</strong> User de-activated successfully.</div></td>');
				
				setTimeout(function() {
					$("tr#tr"+id).remove();
				}, 2000);
			});
	});
});
</script>
<script>
$(document).ready(function() { 
	 $(".resend").live('click',function(){
		var id=$(this).attr('id');
		
		$(this).html('Sending Email...').load( 'resident_approve_resend_mail?con=' + id, function() {
		$(this).removeClass( "resend green" ).addClass( "red" );
		});
	 });
	 
});
</script>