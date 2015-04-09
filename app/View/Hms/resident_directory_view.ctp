

<div id="back" class="btn blue" >Back</div>
<br>
<div align="center">

 <?php
			foreach ($result_user1 as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$d_role_id = $collection['user']['role_id'];
				$tenant = $collection['user']['tenant'];
				$c_flat_id = $collection['user']['flat'];
				$c_email = $collection['user']['email'];
				$c_mobile = $collection['user']['mobile'];
				$c_name = $collection['user']['user_name'];
				$private_field = @$collection['user']['private'];
				$da_dob=@$collection['user']['dob'];
				$per_address=@$collection['user']['per_address'];
				$com_address=@$collection['user']['comm_address'];
				$hobbies=@$collection['user']['hobbies'];
				@$profile_pic = $collection['user']['profile_pic'];
				$medical_pro = @$collection['user']['medical_pro'];
				if($medical_pro==1)
				{
					$medical="Yes";
				
				}
				if($medical_pro==2)
				{
					$medical="No";
				}
				if(@in_array(1,@$d_role_id))
				{
				$commitee='Yes';
				}
				else
				{
				$commitee='No';
				}
				
				if($tenant==1)
				{
				$owner='Yes';
				}
				else
				{
				$owner='No';
				}
				
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				$result_society = $this->requestAction(array('controller' => 'hms', 'action' => 'society_name'),array('pass'=>array($s_society_id)));			  
				foreach($result_society as $data)
				{
					$society_name=$data['society']['society_name'];
				}				
					
					
					if(@in_array('mobile',$private_field) && $role_id!=3 )
					{
			
						if($user_id==$c_user_id)
						{
						$c_mobile;
						}
						else
						{
						$c_mobile="*";
						
						}
					
					}	
					if(@in_array('email',$private_field) && $role_id!=3)
					{
					
						if($user_id==$c_user_id)
						{
						$c_email;
						}
						else
						{
						$c_email="*";
						}

					}	
					if(@in_array('date',$private_field) && $role_id!=3)
					{
						if($user_id==$c_user_id)
						{
						$da_dob;
						}
						else
						{
						$da_dob="*";
						}
					
					}	
					if(@in_array('per_address',$private_field) && $role_id!=3)
					{
						if($user_id==$c_user_id)
						{
						$per_address;
						}
						else
						{
						$per_address="*";
						}
					}
					if(@in_array('com_address',$private_field) && $role_id!=3)
					{
						if($user_id==$c_user_id)
						{
						$com_address;
						}
						else
						{
						$com_address="*";
						}
						
					}					
					if(@in_array('hobi',$private_field) && $role_id!=3)
					{
						if($user_id==$c_user_id)
						{
						$hobbies;
						}
						else
						{
						$hobbies="*";
						}
						
					}
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
?>

 <div class="portlet-body" style="width:65%;">
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										
									</thead>
									<tbody>
										<tr>
											<td rowspan="4" width="30%"  valign="top">
                                            
                                            
               
                    <img src="<?php echo $this->webroot ; ?>/profile/<?php echo $profile_pic; ?>" style="width:100%; height:160px;">
                  							
                                            
                                            
                                            </td>
											<td><label>Name</label></td>
											<td class="hidden-phone">&nbsp&nbsp<?php echo $c_name; ?></td>
											
											
										</tr>
										<tr>
											<td><label>Flat</label></td>
											<td class="hidden-phone">(<?php echo $wing_flat ; ?> )</td>
											
										</tr>
										
										<tr>
											<td><label>Mobile</label></td>
											<td class="hidden-phone">&nbsp&nbsp<?php echo  $c_mobile; ?></td>
											
										</tr>
										
										
										
										
										<tr>
											<td><label>Email</label></td>
											<td class="hidden-phone">&nbsp&nbsp<?php echo $c_email; ?> </td>
											
										</tr>
										
									</tbody>
								</table>
                                
                                <br>
                                <div>
                                <p style="font-size:18px; color:#666;">Other Information</p>
                                </div>
                                
                                <table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										
									</thead>
									<tbody>
										<tr>
                                        <td width="20%">
                                        </td>
										<td width="30%">
										<p style=" font-size:14px; color:#666;">Commitee Member</p>	
                                        </td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $commitee ; ?>
                                        </td>
											
											
										</tr>
										<tr>
                                        <td width="20%">
                                        </td>
										<td width="30%">
										<p style=" font-size:14px; color:#666;">Owner</p>	
                                        </td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $owner ; ?>
                                        </td>
											
											
										</tr>
										
										<tr>
                                        <td width="20%">
                                        </td>
										<td width="30%">
										<p style=" font-size:14px; color:#666;">Society</p>	
                                        </td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $society_name ; ?>
                                        </td>
											
											
										</tr>
										
										<!--<tr>
                                        <td width="20%">
                                        </td>
										<td width="30%">
										<p style=" font-size:14px; color:#666;">Date of Birth</p>	
                                        </td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $da_dob ; ?>
                                        </td>
											
											
										</tr>-->
										
										<?php if($role_id==3) { ?>
										<tr>
                                        <td width="20%">
                                        </td>
                                        <td width="30%">
										<p style=" font-size:14px; color:#666;">Permanent address:	</p>
										</td>
										<td width="20%">
                                        </td>	
									    <td class="hidden-phone" width="30%">
										<?php echo $per_address; ?>
                                        </td>
											
										</tr> 
										
										
										<tr>
										<td width="20%">
                                        </td>	
                                        <td>
										<p style=" font-size:14px; color:#666;">Communication address:</p>
										</td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $com_address; ?>
                                        </td>
											
										</tr> <?php } ?>
										
										
										<tr>
										<td width="20%">
                                        </td>	
                                        <td>
										<p style=" font-size:14px; color:#666;">Hobbies:</p>
										</td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo $hobbies; ?>
                                        </td>
											
										</tr>
										
																				<tr>
										<td width="20%">
                                        </td>	
                                        <td>
										<p style=" font-size:14px; color:#666;">Medical Professional</p>
										</td>
										<td width="20%">
                                        </td>
                                        <td class="hidden-phone" width="30%">
										<?php echo @$medical; ?>
                                        </td>
											
										</tr>

										
									</tbody>
								</table>
							</div>

<?php 
}
?>
</div>