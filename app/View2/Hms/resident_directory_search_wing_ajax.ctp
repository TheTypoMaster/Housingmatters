

 <?php
if($search_value >0)
			{		
		foreach ($result_user2 as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				@$profile_pic = $collection['user']['profile_pic'];
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
?>

<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_user_id;?>)">
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<img src="<?php echo $this->webroot ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
<div style="float:left;margin-left:3%;">
<span style="font-size:22px;"><?php echo $c_name; ?></span><br/>
<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
</div>
</div>
</div>
 

<?php

 
}
 if($count_user2 == 0)
				{ ?>
				<center><h4 style="color:#9F2D9F;"><b>No Record Found</b></h4></center>
			<?php	}

}
?>


 <?php
if($search_value==0)
			{		
		foreach ($result_user3 as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				@$profile_pic = $collection['user']['profile_pic'];
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
?>

<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_user_id;?>)">
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<img src="<?php echo $this->webroot ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
<div style="float:left;margin-left:3%;">
<span style="font-size:22px;"><?php echo $c_name; ?></span><br/>
<span style="font-size:16px;"><?php echo $wing_flat ; ?></span>
</div>
</div>
</div>


<?php 
} }
?>


