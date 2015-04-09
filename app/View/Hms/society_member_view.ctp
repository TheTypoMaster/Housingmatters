
	<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {

$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<div class="portlet-body" style="padding:10px;";>
									<!--BEGIN TABS-->
									<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">
											
										</ul>
										<div class="tab-content" style="min-height:500px;">
											<div class="tab-pane active" id="tab_1_1">

                    
<?php 

foreach ($result_society as $collection) 
{ 
$society_name=$collection['society']['society_name'];
}
?>
<div style="text-align:center; font-size:24px">
 <?php echo $society_name ; ?></div>
<span class="label label-info pull-right" style="padding:10px; font-size:20px">Total Users : <?php echo $n ; ?></span>
<br><br>
<div><h5><b><span style="float:left;margin-left:20px;">Role</span> <span style="float:right;"><span><a href='society_member_excel'class='blue mini btn tooltips' download='download'  data-placement='bottom' data-original-title="Download Export"><i class=" icon-download-alt"></i> </a></span> &nbsp  Member</span></b></h5></div>





	
							<div class="portlet-body">
								<div class="accordion in collapse" id="accordion1" style="height: auto;">
									<div class="accordion-group">
									
									<?php
									$r=0; $j=0;

									foreach ($result_role as $collection) 
									{ 
									$j++;
									$designation_id=$collection['role']['role_id'];
									$designation_name=$collection['role']['role_name'];
									foreach ($result_user as $collection) 
									{ 
									$role_id=$collection['user']['role_id'];
									$da_user_id=$collection['user']['user_id'];
									$user_name=$collection['user']['user_name']; 
									$date=$collection['user']['date'];
									if(@in_array($designation_id,$role_id))
									{
									$r++;
									$u_name[]=$user_name; 
									$dad_user_id[]=$da_user_id;
									$u_date[]=$date;
									}
									}
									
									?>
									
										<div class="accordion-heading">
										<div style="float:right; padding:5px; "><span class="label label-info pull" style=" font-size:14px"><?php echo $r; ?></span> </div>
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1<?php echo $j ; ?>" style="text-decoration:none;">
											
											<b><?php echo $designation_name; ?> </b>
											</a>
										</div>
										
										
										
										
										
										<div id="collapse_1<?php echo $j ; ?>" class="accordion-body collapse" style="height: 0px;">
											<div class="accordion-inner">
<table class="table">
<tr>
<td><b>Name</b></td>
<td><b>Unit</b></td>
<td><b>Mobile</b></td>
<td><b>Email</b></td>
<td><b>Validation Pending</b></td>
<td><b>Portal joining date</b></td>
</tr>						
						
<?php 
for($ii=0;$ii<sizeof(@$u_name);$ii++)
{
  $user= $dad_user_id[$ii];
  $d_date=$u_date[$ii];
  $result_user1 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user)));
	 foreach ($result_user1 as $collection) 
		{	
		
		 $wing=$collection['user']['wing'];
		 $email=$collection['user']['email'];
		 $mobile=$collection['user']['mobile'];
		 $flat=$collection['user']['flat'];
		 $tenant=$collection['user']['tenant'];
		 @$profile_status=$collection['user']['profile_status'];
		}
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
		
if($tenant==1)
	{ ?>
	
<tr style="color:#13D17E;">
<td>
<?php echo $u_name[$ii]; ?>  
<?php if($profile_status!=2) { ?>  
<span style="color:red; font-size:10px;"> <i class=' icon-star'></i> </span> 
<?php } ?>
</td>
<td>
 <?php echo $wing_flat; if(empty($wing_flat)){ echo" -- "; }?>
</td>
<td>
 <?php echo $mobile; if(empty($mobile)){ echo" -- "; } ?>
</td>
<td>
 <?php echo $email; if(empty($email)){ echo" -- "; } ?>
</td>
<td>
<?php if($profile_status!=2) { ?>  
<?php if(!empty($email)) { ?> 
<a href="#" role='button' class="btn green mini resend" id="<?php echo $user; ?>">Send Reminder</a> <?php } elseif(!empty($mobile)) { ?>
<a href="#" role='button' class="btn green mini resend_sms" id="<?php echo $user; ?>">Send Reminder</a> <?php } ?>
<?php } ?> 
</td>
<td>
<span> <?php echo $d_date; ?></span>
</td>
</tr>	
		
<?php	}
	else
	{ ?>
<tr style="color:#C709F0;">
<td>
<?php echo $u_name[$ii]; ?> 
<?php if($profile_status!=2) { ?>  
<span style="color:red; font-size:10px;"> <i class=' icon-star'></i> </span> 
<?php } ?> 
</td>
<td>
 <?php echo $wing_flat; if(empty($wing_flat)){ echo" -- "; } ?>
</td>
<td>
 <?php echo $mobile; if(empty($mobile)){ echo" -- "; } ?>
</td>
<td>
 <?php echo $email; if(empty($email)){ echo" -- "; } ?>
</td>

<td>
<?php if($profile_status!=2) { ?> 
<?php if(!empty($email)) { ?> 
<a href="#" role='button' class="btn green mini resend" id="<?php echo $user; ?>">Send Reminder</a> <?php } elseif(!empty($mobile)) { ?>
<a href="#" role='button' class="btn green mini resend_sms" id="<?php echo $user; ?>">Send Reminder</a> <?php } ?>
<?php } ?> 
</td>
<td><span> <?php echo $d_date; ?></span></td>
</tr>

				
	
<?php	}

}

unset($u_name);
unset($dad_user_id);
unset($u_date);

?>
</table>	
<br>
			<br>
		<div style="float:left;"> <a class="btn mini green"></a> <span>Owner &nbsp; 
		</span> <a class="btn mini purple"></a> <span> &nbsp; Tenant &nbsp; 
		 &nbsp; 
		<span style="color:red; font-size:14px;"> <i class=' icon-star'></i> </span> 
		<span> Awaiting User Validation  </span>
		<hr>
		</div>
	
						
											
											</div>
										</div>
										
										<?php $r=0; } ?>
										
									</div>
									
									
									
								</div>
							</div>
						
						
						
						
						
		</div>
</div>
</div>
</div>		
				

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

<script>
$(document).ready(function() { 
	 $(".resend_sms").live('click',function(){
		var id=$(this).attr('id');
		$(this).html('Sending Sms...').load( 'resident_approve_resend_sms?con=' + id, function() {
		$(this).removeClass( "resend_sms green" ).addClass( "red" );
		});
	 });
	 
});
</script>	



			