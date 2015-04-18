
<?php
foreach($result_contact_handbook as $collection)            
			{  
				$c_h_id=$collection['contact_handbook']["c_h_id"];

				 $mobile=$collection['contact_handbook']["c_h_mobile"];
				$user_id=(int)$collection['contact_handbook']['user_id'];
				 $name=$collection['contact_handbook']["c_h_name"];
				  $email=$collection['contact_handbook']["c_h_email"];
				   $web=$collection['contact_handbook']["c_h_web"];
				    $service=$collection['contact_handbook']["c_h_service"];
				
	@$result_user=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));			  
		foreach($result_user as $data)
		{
			 $user_name=$data['user']['user_name'];

		}			


?>	
<div class="r_d fadeleftsome" style="width:45%" onmouseover="show_tooltips();" >
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<div style="float:left;margin-left:3%;"  >
<i class="icon-user"></i> <span style="font-size:16px;"><?php echo $name; ?></span><br/>
<i class="icon-phone-sign"></i> <span style="font-size:14px;"><?php echo $mobile ; ?></span><br/>
<i class="icon-envelope-alt"></i> <span style="font-size:14px;"><?php echo $email ; ?></span><br/>
<i class="icon-sitemap"></i> <span style="font-size:14px;"><?php echo $web ; ?></span><br/>
 <i class=" icon-wrench"></i> <span style="font-size:14px;">Services : <?php echo $service ; ?></span><br/>
<i class="icon-user"></i> <span class=" tooltips" data-placement="right" data-original-title="<?php echo $user_name ; ?>"  >Update By</span><br/> 
<div style="">
<?php
if($s_user_id==$user_id || $role_id==3)
{
?>
<span class="btn mini yellow "onclick="contact_add(<?php echo $c_h_id ; ?>,'<?php echo $mobile ; ?>','<?php echo $name ; ?>','<?php echo $email; ?>','<?php echo $web; ?>','<?php echo $service ; ?>');">edit</span> 
<?php } ?>
<?php 
if($role_id==3)
{
?>
<span ><a href="contact_handbook_delete?con=<?php echo $c_h_id ; ?>" class="btn mini red" >Delete</a></span>
<?php } ?>
</div>
</div>
</div>
</div>
<?php 
}
if(empty($mobile) &&  empty($name))
				{ ?>
				<center><br><h4 style="color:#9F2D9F;"><b>No Record Found</b></h4></center>
			<?php	}

?>

