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
 
}
$html='<div style="background-color:#fff; ">
<div class="bg_co" align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">'.$society_name.'</div>
<div style="padding: 5px;">
<span  style="font-size:12px;"><b> Following Members were present: </b></span><br/>
<table  cellpadding="5" width="100%;" >
<tr>
<td>Sr.no</td>
<td>Name of Member</td>
<td>Designation	</td>
</tr>';
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

$html.='<tr>
<td>'.$c.'</td>
<td>'.$user_name.' '.$flat.'</td>
<td>'.$designation_name.'</td>
</tr>';
}
$html.='</table></div>
<div  align="" style="padding: 10px;">
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