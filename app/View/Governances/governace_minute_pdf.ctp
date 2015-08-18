<?php
App::import('Vendor','xtcpdf');  
$tcpdf = new XTCPDF(); 
$textfont = 'times'; // looks better, finer, and more condensed than 'dejavusans' 

$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
$tcpdf->SetAutoPageBreak( true ); 
//$tcpdf->setHeaderFont(array($textfont,'',40)); 
$tcpdf->xheadercolor = array(255,255,255); 
$tcpdf->xheadertext = ''; 
$tcpdf->xfootertext = 'HousingMatters'; 

// add a page (required with recent versions of tcpdf) 
$tcpdf->AddPage(); 

// Now you position and print your page content 
// example:  
$tcpdf->SetTextColor(0, 0, 0); 
$tcpdf->SetFont($textfont,12); 
$tcpdf->SetLineWidth(0.1);

foreach($result_gov_minute as $data){

$message_web=$data['governance_minute']['message'];
$governance_minute_id=(int)$data['governance_minute']['governance_minute_id'];
$present_user=$data['governance_minute']['present_user'];
$meeting_id=(int)$data['governance_minute']['meeting_id'];
$any_other=@$data['governance_minute']['any_other'];
$result_gov_invite=$this->requestAction(array('controller' => 'governances', 'action' => 'governace_invite_meeting'), array('pass' => array($meeting_id)));
foreach($result_gov_invite as $data1)
	{
		$title=$data1['governance_invite']['subject'];
		$date=$data1['governance_invite']['date'];
		$time=$data1['governance_invite']['time'];
		$location=$data1['governance_invite']['location'];
		$notice_of_date=@$data1['governance_invite']['notice_of_date'];
		$meeting_type=(int)@$data1['governance_invite']['meeting_type'];
	}
	if($meeting_type==1){
	$moc="Managing Committee";
	}
	if($meeting_type==2){
	$moc="General Body";
	}
	if($meeting_type==3){
	$moc="Special General Body";
	}

}
$html='<div style="background-color:#fff; ">
<div class="bg_co" align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">'.$society_name.'</div>
<div style="padding: 5px;">
<span  style="font-size:12px;"><b> Following Members were present: </b></span><br/>';

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
$flat_name='';
if(!empty($flat))
{
	$flat_name='('.$flat.')';
}
$to=$user_name.' '.$flat_name.' '.$designation_name.',';
$html.='<span style="font-size:12px;">'.$to.'</span>';
}
$html.='</div><br/>';

$html.='<div  style="padding: 5px;" >
<table  cellpadding="5" width="100%;" border="1">
<tr>
<td width="30%" ><span  style="font-size:14px;"><b> Type : </b></span><br/> <span><?php echo @$moc; ?></span></td>
<td width="20%" ><span  style="font-size:14px;"><b> ID : </b></span> <br/><span><?php echo $meeting_id; ?></span></td>
<td width="20%"><span  style="font-size:14px;"><b> Notice of Date : </b></span> <br/><span><?php echo $notice_of_date; ?></span></td>
</tr>
<tr>
<td ><span  style="font-size:14px;"><b> Location : </b></span> <br/><span><?php echo $location; ?></span></td>
<td ><span  style="font-size:14px;"><b> Date of Meeting : </b></span><br/> <span><?php echo $date; ?></span></td>
<td><span  style="font-size:14px;"><b> Time : </b></span> <br/><span><?php echo $time; ?></span></td>
</tr>
</table>
<table cellpadding="5" width='100%;'>
<tr>
<td><span  style="font-size:14px;"><b>Title : </b></span> <span><?php echo $title; ?></span></td>
</tr>
</table>
</div>';


$html.='<div  align="" style="padding: 10px;">
<table border="1" cellpadding="4" width="100%"><tr>
<td width="70%"><span  style="font-size:12px;"><b> Agenda: </b></span></td>
<td width="30%"><span  style="font-size:12px;"><b> Minutes: </b></span></td>
</tr>';
$z=0;
foreach($message_web as $data){ $z++;
$html.='<tr>
	<td style=""><p><span style="font-size:10px;"> '. $z.' '. urldecode($data[0]).' </span><br/><span style="font-size:10px;">'.urldecode($data[1]).'</span></p></td>
	<td style="font-size:10px;"><p><span>'.urldecode($data[3]).'</span></p></td>
	</tr>';
}	
$html.='</table></div>
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 12px;font-weight: bold;color: #fff;vertical-align: middle;">
<span>Your Society is empowered by HousingMatters - 
<i>"Making Life Simpler"</i></span><br>
<span style="color:#FFF;">Email: support@housingmatters.in</span> &nbsp;|&nbsp; <span>Phone : 022-41235568</span> &nbsp;|&nbsp; <span style="color:#FFF;">www.housingmatters.co.in</span></div>
</div>';

$tcpdf->writeHTML($html);
echo $tcpdf->Output('governance_minute.pdf', 'D'); 

?>