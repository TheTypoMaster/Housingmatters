<?php 
function substrwords($text, $maxchar, $end='...') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);      
        $output = '';
        $i      = 0;
        while (1) {
            @$length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            } 
            else {
                @$output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    } 
    else {
        $output = $text;
    }
    return $output;
}
?>

 <?php
if(!empty($search_value))
{
		if(!empty($result_usser_flat))
		{
			
		  foreach($result_usser_flat as $d_user_flat)
		  {
			$result_user22 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($d_user_flat)));

			foreach ($result_user22 as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				$multiple_flat = $collection['user']['multiple_flat'];
				$medical_pro = @$collection['user']['medical_pro'];
				$c_name=substrwords($c_name,20,'...');
				@$profile_pic = $collection['user']['profile_pic'];
				
				
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
				?>

				<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_user_id;?>)">
				<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
				<img src="<?php echo $webroot_path ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
				<div style="float:left;margin-left:3%;">
				<span style="font-size:22px;"><?php echo $c_name; ?></span><?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
				<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
				</div>
				</div>
				</div>


			  <?php


			}
			
}			
			
			
			
			
		}
else{

		foreach ($result_user as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				$multiple_flat = @$collection['user']['multiple_flat'];
				$medical_pro = @$collection['user']['medical_pro'];
				$c_name=substrwords($c_name,20,'...');
				@$profile_pic = $collection['user']['profile_pic'];
				
				
				
				
				
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
				
				if(!empty($multiple_flat)){
					
					foreach($multiple_flat as $data33)
					{
						$wing=$data33[0];
						$flat=$data33[1];
						$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));	
						?>
						
				<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $flat;?>)">
				<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
				<img src="<?php echo $webroot_path ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
				<div style="float:left;margin-left:3%;">
				<span style="font-size:22px;"><?php echo $c_name; ?></span><?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
				<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
				</div>
				</div>
				</div>
						
						
			<?php		}
					
					
					
				}else{
?>

				<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_flat_id;?>)">
				<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
				<img src="<?php echo $webroot_path ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
				<div style="float:left;margin-left:3%;">
				<span style="font-size:22px;"><?php echo $c_name; ?></span><?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
				<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
				</div>
				</div>
				</div>
				 

				<?php
				}
		 
		}
		 if($count_user2 == 0)
						{ ?>
						<center><h4 style="color:#9F2D9F;"><b>No Record Found</b></h4></center>
					<?php	}

	}
}
?>


 <?php
if(empty($search_value))
			{		
		foreach ($result_user3 as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				$multiple_flat = @$collection['user']['multiple_flat'];
				$medical_pro = @$collection['user']['medical_pro'];
				$c_name=substrwords($c_name,20,'...');
				@$profile_pic = $collection['user']['profile_pic'];
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
				
				if(!empty($multiple_flat)){
					
					foreach($multiple_flat as $data22){
						
						
						$wing=$data22[0];
						$flat=$data22[1];
						$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
					?>

					<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $flat;?>)">
					<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
					<img src="<?php echo $webroot_path; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
					<div style="float:left;margin-left:3%;">
					<span style="font-size:22px;"><?php echo $c_name; ?></span><?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
					<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
					</div>
					</div>
					</div>




				<?php					
					}
					
					
				}else{
					
					
?>

<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_flat_id;?>)">
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<img src="<?php echo $webroot_path; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
<div style="float:left;margin-left:3%;">
<span style="font-size:22px;"><?php echo $c_name; ?></span><?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
</div>
</div>
</div>


<?php 
			} } }
?>


