
<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3 style="color:#999;">Family Member</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>

<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">
											<li class=""><a href="profile" >Basic</a></li>
											<li class="active"><a href="family_member_view" >Family Member</a></li>
										</ul>
										<div class="tab-content">
										
										<table class="table table-striped table-bordered" id="sample_2">
<thead>                                               
											   <tr>
												<td>Sr.no</td>
												<td>Name</td>
												<td>Flat</td>
												<td>Mobile</td>
												<td>Email</td>
												<td>Relation</td>
												<td>Dob</td>
												<td>Blood Group</td>
												</tr>
												</thead>
<tbody>

												<?php
												$i=0;
											foreach($result_user as $data)
											{
												
												$i++;
												$user_name=$data['user']['user_name'];
												$mobile=$data['user']['mobile'];
												$email=$data['user']['email'];
												$dob=$data['user']['dob'];
												$relation=$data['user']['relation'];
												@$blood_group=$data['user']['blood_group'];
												$wing=(int)$data['user']['wing'];
												$flat = (int)$data['user']['flat'];
												$flat_wing = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));				  
													if($blood_group==1)
													{
													$b_group="Group A";
													}
													if($blood_group==2)
													{
													$b_group="Group B";
													}
													if($blood_group==3)
													{
													$b_group="Group AB";
													}
													if($blood_group==4)
													{
													$b_group="Group O";
													}
												?>
												<tr>
												
												<td><?php echo $i ; ?></td>
												<td><?php echo $user_name ; ?></td>
												<td><?php echo $flat_wing ; ?></td>
												<td><?php echo $mobile ; ?></td>
												<td><?php echo $email ; ?></td>
												<td><?php echo $relation; ?></td>
												<td><?php echo $dob; ?></td>
												<td><?php echo @$b_group; ?></td>
												</tr>
									  <?php } ?>
												</tbody>
												</table>
										
										</div>
										</div>
										
										
</div>										