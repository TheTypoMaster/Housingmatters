<div style="border:solid 2px #269abc; width:80%; margin-left:10%;overflow: auto;">
<div style="border-bottom:solid 2px #269abc; color:white; background-color: #39b3d7; padding:4px; font-size:20px; " align="center">Feedback View </div>
<?php
$i=0;
foreach ($result_feedback as $collection) 
{ 
$i++;
$feedback_sub=$collection['feedback']['feedback_subject'];
$feedback_date=$collection['feedback']['feedback_date'];
$feedback_time=$collection['feedback']['feedback_time'];
$feedback_category=(int)$collection['feedback']['feedback_category'];
$da_user_id=(int)$collection['feedback']['user_id'];
$feedback_id=(int)$collection['feedback']['feedback_id'];
$da_society_id=(int)$collection['feedback']['society_id'];
$feedback_des=@$collection['feedback']['feedback_des'];
$feedback_cat_name= $this->requestAction(array('controller' => 'hms', 'action' => 'feedback_category_name'),array('pass'=>array($feedback_category)));
$result_user= $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($da_user_id)));
$result_society= $this->requestAction(array('controller' => 'hms', 'action' => 'society_name'),array('pass'=>array($da_society_id)));
foreach ($result_society as $collection) 
{ 
$society_name=$collection['society']["society_name"];
}
foreach($result_user as $collection) 
{ 
$user_name=$collection['user']["user_name"];
$wing=$collection['user']["wing"];
$flat=$collection['user']["flat"];
$email=$collection['user']["email"];
$mobile=$collection['user']["mobile"];
}
$wing_flat= $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));

}
?>



<div style="padding:10px;overflow:auto;">

<div class="pull-right">
<span style="color:#269abc;font-size:14px;"><?php echo $feedback_date ; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $feedback_time ; ?></span><br><br>
<span style="color:#269abc;font-size:14px;">Society Name:- &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $society_name ; ?></span><br>

</div>


<span style="color:#269abc;font-size:16px;">From:- <?php  echo $user_name; ?> &nbsp;(<?php echo $wing_flat ; ?>)</span><br><br>
<span style="color:#269abc;font-size:16px;">Email:-  <?php  echo $email; ?></span><br><br>
<span style="color:#269abc;font-size:16px;">subject :-  <?php  echo $feedback_sub; ?></span><br><br>
<span style="color:#269abc;font-size:16px;"><p>Message :-  sdf dfd gdfg fd gfd gfd gfdg fdgfdgfdgfd gdf gf gdf gdf gfdg fg fd gfdgf dfg fgfdgfd gfdg dfgdfg fgfdg fgfgfdgdg fdg fdgfdgfdgfd fdg fdgdfg f gfdgfdgdfgfdg httr yyuyuyt uytuytu</p></span><br><br>
<hr>

<button></button>







						  






						  

</div>
</div>