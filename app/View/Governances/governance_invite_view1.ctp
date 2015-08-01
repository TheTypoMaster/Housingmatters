<?php
$i=0;
foreach($result_gov_invite as $data){
$gov_id=$data['governance_invite']['governance_invite_id'];
$subject=$data['governance_invite']['subject'];
$message_web=$data['governance_invite']['message'];
$date=$data['governance_invite']['date'];
$time=$data['governance_invite']['time'];
$file=$data['governance_invite']['file'];
$type=$data['governance_invite']['type'];
$location=$data['governance_invite']['location'];
 $meeting_type=(int)@$data['governance_invite']['meeting_type'];
 if($meeting_type==1)
 {
	$moc="Managing Committee";
 
 }
 if($meeting_type==2)
 {
	$moc="General Body";
 
 }
if($type==3)
{
$visible=$data['governance_invite']['visible'];
$sub_visible=$data['governance_invite']['sub_visible'];
$user=$data['governance_invite']['user'];
}
if($type==1)
{
	$user=$data['governance_invite']['user'];
}
if($type==2)
{
	$user=$data['governance_invite']['other_user'];
}

}

?>

<div style="background-color:#F7F7F7; border:solid 2px #269abc; padding:10px;">
<div align="center" style="background-color:#F7F7F7;">
<h3><b><?php echo $society_name; ?></b></h3>
</div>
<div align="right">
<span ><?php echo $date; ?>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time; ?></span>
</div>
<div  align="">
<span style="font-size:16px;"><b>To : </b></span>
<?php
if($type==3 || $type==1){
foreach($user as $id)
{
	$result_user=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array((int)$id)));
	foreach($result_user as $data)
	{
		$to=$data['user']['email'];
		$wing=$data['user']['wing'];
		$flat=$data['user']['flat'];
		if(!empty($to))
		{
		$to.=',';
		
		}
		?>
		
		<span><?php echo $to; ?></span>
		
		<?php
	}
}
}
if($type==2)
{
	?>
	<span><?php echo $user; ?></span>
	<?php
}
?>


</div>
<div  align="">
<span  style="font-size:16px;"><b>Title : </b></span><span><?php echo $subject; ?></span>

</div>
<div  align="">
<span  style="font-size:16px;"><b>Location : </b></span><span><?php echo $location; ?></span>

</div>
<div  align="">
<span  style="font-size:16px;"><b>Meeting type : </b></span><span><?php echo @$moc; ?></span>

</div>
<div align="justify"><span  style="font-size:16px;"><b>Content for Meeting agenda : </b></span>
<?php //pr($message_web);

foreach($message_web as $data)
{
	?>
	
	<div align="justify" ><?php echo urldecode($data[0]); ?><br/></div>
	<!--<div align="justify" ><?php echo urldecode($data[1]); ?><br/></div><br/>-->
<?php	
}



 ?>
</div>


<?php if(!empty($file)) { ?>
<br/>
<p style="font-size:14px;"><b>Attachment</b></p>
<div >
<a href="<?php echo $webroot_path ; ?>/governances_file/<?php echo $file; ?>" target="_blank" class="btn mini green tooltips" data-placement="bottom" data-original-title="<?php echo $file; ?>" download="download"><i class=" icon-download-alt"></i></a>
</div>
<?php } ?>


</div>