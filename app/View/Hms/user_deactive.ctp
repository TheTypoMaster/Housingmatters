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
		<a href="<?php echo $webroot_path; ?>Hms/society_member_view" class="btn" rel="tab">All Active Users</a>
		<a href="<?php echo $webroot_path; ?>Hms/user_deactive" class="btn yellow" rel="tab">All De-active Users</a>
	</div>
</div>

<div class="portlet-body" style="background-color:#fff;">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<th>Owner/Tenant</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>Activate?</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($result_user_deactive as $data) { 
		$user_id=$data['user']['user_id'];
		$user_name=$data['user']['user_name'];
		$result_user1 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
		foreach ($result_user1 as $collection) 
		{	
			$wing=$collection['user']['wing'];
			$email=$collection['user']['email'];
			$mobile=$collection['user']['mobile'];
			$flat=$collection['user']['flat'];
			$tenant=$collection['user']['tenant'];
			$date=$collection['user']['date'];
			@$profile_status=$collection['user']['profile_status'];
		}
		$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
		if($tenant==1){ $status="Owner"; }else{ $status="Tenant"; }
		
		?>
			<tr id="tr<?php echo $user_id; ?>">
				<td>
					<?php echo $user_name; ?>
				</td>
				<td><?php echo $wing_flat; ?></td>
				<td><?php echo $status; ?></td>
				<td><?php echo $mobile; ?></td>
				<td><?php echo $email; ?></td>
				<td>
				<a href="#" class="btn green mini deactive_conferm tooltips" data-placement="bottom" data-original-title="Activate?" id="<?php echo $user_id; ?>" role="button"><i class=" icon-ok-sign"></i></a></td>
			</tr>
		<?php } ?>	
		</tbody>
	</table>
</div>
<div class="edit_div" style=""></div>
<script>
$(document).ready(function() {
	$(".deactive12").live('click', function(e){
		$(".edit_div").hide();
		$(this).text("Wait...");
		var id=$(this).attr("id");
		$.ajax({
			url: "<?php echo $webroot_path; ?>/Hms/user_deactive_ajax?t="+id+"&d=1",
			}).done(function(response) {
				$("tr#tr"+id).html('<td colspan="8"><div style="margin-bottom: 0px;" class="alert alert-success"><strong>Success!</strong> User Activated successfully.</div></td>');
				
				setTimeout(function() {
					$("tr#tr"+id).remove();
				}, 2000);
			});
		return false;
	});
	
	$(".deactive_conferm").off().on('click', function(e){
		var id=$(this).attr("id");
		$('.edit_div').show();
		$('.edit_div').html('<div class="modal-backdrop fade in"></div><div class="modal" id="poll_edit_content"><div class="modal-header"><h4 id="myModalLabel1">Edit poll</h4></div><div class="modal-body">12345</div><div class="modal-footer"><button class="btn" id="close_edit">No</button>	<a href="#" class="btn green mini deactive12 tooltips" data-placement="bottom" data-original-title="Activate?" id='+id+' role="button"><i class=" icon-ok-sign"></i></a></div></div>');
		return false;
	});
	$("#close_edit").live('click', function(e){
		$('.edit_div').hide();
	});
});
</script>