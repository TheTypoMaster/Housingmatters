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
				<th>Role</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>Validation Status</th>
				<th>Portal joining date</th>
				<th>Deactivate?</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($result_user as $collection) { 
			$user_id=$collection['user']['user_id'];
			$user_name=$collection['user']['user_name'];
			$role_ids=$collection['user']['role_id'];
			$wing=$collection['user']['wing'];
			$email=$collection['user']['email'];
			$mobile=$collection['user']['mobile'];
			$multiple_flat=@$collection['user']['multiple_flat'];
			$flat=$collection['user']['flat'];
			$tenant=$collection['user']['tenant'];
			$date=$collection['user']['date'];
			@$profile_status=$collection['user']['profile_status'];

		if(!empty($multiple_flat)){
				foreach($multiple_flat as $data4){
				
					$wing=$data4[0];
					$flat=$data4[1];
				//user info via flat_id//
					$result_user_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($wing,$flat)));
					foreach($result_user_info as $user_info){
						$user_id=(int)$user_info["user"]["user_id"];
						$user_name=$user_info["user"]["user_name"];
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
						<td><?php echo $role_name_des; ?></td>
						<td><?php echo $mobile; ?></td>
						<td><?php echo $email; ?></td>
						<td>
						<?php if($profile_status!=2) { ?>  
						<?php if(!empty($email)) { ?> 
						<a href="#" role='button' class="btn green mini resend" id="<?php echo $user_id; ?>"><i class=" icon-exclamation-sign"></i>  Send Reminder</a> <?php } elseif(!empty($mobile)) { ?>
						<a href="#" role='button' class="btn green mini resend_sms" id="<?php echo $user_id; ?>"><i class=" icon-exclamation-sign"></i> Send Reminder</a> <?php } ?>
						<?php }
						else
						{ ?>
						<span> <a class="btn green mini"><i class=" icon-ok"></i>  done</a></span>

						<?php 
						}


						?>
						</td>
						<td><?php echo $date; ?></td>
						<td><a href="#" class="btn red mini deactive_conferm tooltips" id="<?php echo $user_id; ?>" data-placement="bottom" data-original-title="Deactivate?" role="button"><i class=" icon-remove-sign"></i></a>
						</td>
						</tr>
				
				<?php
			}
			
	}else{

					$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
					if($tenant==1){ $color="#13D17E"; }else{ $color="#C709F0"; }

					$role_name=array();
					if(sizeof($role_ids)>0){
						foreach($role_ids as $role_id){
							$role_name[] = $this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_rolename_via_roleid'),array('pass'=>array($role_id)));
						}
					}

					$role_name_des=implode(",",$role_name);
					unset($role_name);?>
					
						<tr id="tr<?php echo $user_id; ?>">
									<td style="color:<?php echo $color; ?>">
										<?php echo $user_name; ?>
										<?php if($profile_status!=2) { ?>  
										<span style="color:red; font-size:10px;"> <i class=' icon-star'></i> </span> 
										<?php } ?> 
									</td>
									<td><?php echo $wing_flat; ?></td>
									<td><?php echo $role_name_des; ?></td>
									<td><?php echo $mobile; ?></td>
									<td><?php echo $email; ?></td>
									<td>
										<?php if($profile_status!=2) { ?>  
										<?php if(!empty($email)) { ?> 
										<a href="#" role='button' class="btn green mini resend" id="<?php echo $user_id; ?>"><i class=" icon-exclamation-sign"></i>  Send Reminder</a> <?php } elseif(!empty($mobile)) { ?>
										<a href="#" role='button' class="btn green mini resend_sms" id="<?php echo $user_id; ?>"><i class=" icon-exclamation-sign"></i> Send Reminder</a> <?php } ?>
										<?php }
											else
											{ ?>
											<span> <a class="btn green mini"><i class=" icon-ok"></i>  done</a></span>
											
											<?php 
											}


										?>
									</td>
									<td><?php echo $date; ?></td>
									<td><a href="#" class="btn red mini deactive_conferm tooltips" id="<?php echo $user_id; ?>" data-placement="bottom" data-original-title="Deactivate?" role="button"><i class=" icon-remove-sign"></i></a>
									</td>
						</tr>	
							
  <?php } ?>
			
<?php } ?>	
		</tbody>
	</table>
	
</div>
<div class="edit_div" style=""></div>
<script>
$(document).ready(function() {
	$(".deactive").live('click', function(e){
		$(".edit_div").hide();
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
	$(".deactive_conferm").off().on('click', function(e){
		var id=$(this).attr("id");
		$('.edit_div').show();
		$('.edit_div').html('<div class="modal-backdrop fade in"></div><div class="modal" id="poll_edit_content"><div class="modal-body"><span style="font-size:16px;"> <i class="icon-warning-sign" style="color:#d84a38;"></i>  Are you sure you want to deactivate user ? </span></div><div class="modal-footer"><a href="#" class="btn red deactive tooltips" id='+id+' data-placement="bottom" data-original-title="Deactivate?" role="button"> Yes</a><button class="btn" id="close_edit">No</button></div></div>');
		return false;
	});
	$("#close_edit").live('click', function(e){
		$('.edit_div').hide();
	});
});
</script>
<script>
$(document).ready(function() {
	 $(".resend").bind('click',function(){
		var id=$(this).attr('id');
		
		$(this).html('Sending Email...').load( 'resident_approve_resend_mail?con=' + id, function() {
		$(this).removeClass( "resend green" ).addClass( "red" );
		});
	 });
	 
});
</script>
