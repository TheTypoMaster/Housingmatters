<div style="float:left;">
<a href="<?php echo $this->webroot; ?>Governances/minute_view" rel="tab" class="btn  green hide_at_print"><i class="icon-caret-left"></i> Back</a>
</div>
<div style="float:right;">
<a class="btn green hide_at_print" onclick="window.print();">Print </a>
<a class="btn purple  hide_at_print" href="<?php echo $this->webroot; ?>Governances/governace_minute_pdf?con=<?php echo $governance_minute_id ?>">Pdf </a>
</div>
<br/><br/>
<?php
$i=0;
foreach($result_gov_minute as $data){

$message_web=$data['governance_minute']['message'];
$governance_minute_id=(int)$data['governance_minute']['governance_minute_id'];
$present_user=$data['governance_minute']['present_user'];
 $file=$data['governance_minute']['file'];
}

?>

<div style="background-color:#fff; border:solid 1px;">
<div class="bg_co" align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">
<?php echo $society_name; ?>
</div>
<div  align="center" style="padding: 2px;">
<span style="font-size:14px;"> <b> Minutes  </b> </span>
</div>
<div style="padding: 5px;">
<span  style="font-size:14px;"><b> Following Members were present: </b></span>

<table  cellpadding='5' width='100%;' >
<tr>
<td>Sr.no</td>
<td>Name of Member</td>
<td>Designation	</td>
</tr>
<?php 
$c=0;
foreach($present_user as $data7){
$c++;
$result_user=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($data7)));
foreach($result_user as $data2){
	$user_name=$data2['user']['user_name'];
	$wing=(int)$data2['user']['wing'];
	$flat=(int)$data2['user']['flat'];
	$designation_id=(int)@$data2['user']['designation_id'];
}
$flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing,$flat)));

$designation_name=$this->requestAction(array('controller' => 'governances', 'action' => 'designation_find_by_user'), array('pass' => array($designation_id)));

?>
<tr>
<td><?php echo $c; ?></td>
<td><?php echo $user_name ?> <?php echo $flat ; ?></td>
<td><?php echo $designation_name; ?></td>

</tr>
<?php } ?>
</table>


</div>

<div  align="" style="padding: 5px;">
<table  cellpadding='5' width="100%" border="">
<tr class='tr_heading' style=''>
<td width="65%"><span  style="font-size:14px;"><b>Agenda: </b></span></td>
<td><span  style="font-size:14px;"><b>Minutes: </b></span></td>
</tr>
<?php
$z=0;
foreach($message_web as $data)
{ $z++;?>
	
	<tr style=''>
	<td style=""><p><span style="font-size:14px;"> <?php echo $z; ?>. <?php  echo urldecode($data[0]); ?>  </span><br/><span><?php echo urldecode($data[1]); ?></span></p></td>
	<td style=""><p><span><?php echo urldecode($data[3]); ?></span></p></td>
	</tr>
<?php	
} ?>
</table>
</div>
<?php if(!empty($file)) { ?>
<div class="hide_at_print">
<p style="font-size:14px; padding:5px;"><b>Attachment</b></p>
<div style="padding:5px;" >
<a href="<?php echo $webroot_path ; ?>/governances_file/<?php echo $file; ?>" target="_blank" class="btn mini green tooltips" data-placement="bottom" data-original-title="<?php echo $file; ?>" download="download"><i class=" icon-download-alt"></i></a>
</div>
</div>
<?php } ?>
<br/>
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 12px;font-weight: bold;color: #fff;vertical-align: middle;">
<span>Your Society is empowered by HousingMatters - 
<i>"Making Life Simpler"</i></span><br>
<span style="color:#FFF;">Email: support@housingmatters.in</span> &nbsp;|&nbsp; <span>Phone : 022-41235568</span> &nbsp;|&nbsp; <span style="color:#FFF;">www.housingmatters.co.in</span></div>


</div>