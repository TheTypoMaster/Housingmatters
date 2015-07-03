<?php 
foreach ($cursor1 as $collection) 
{
$receipt_no = (int)$collection['cash_bank']['receipt_id'];
$d_date = $collection['cash_bank']['transaction_date'];
$today = date("d-M-Y");
$user_id_d = $collection['cash_bank']['user_id'];
$amount = $collection['cash_bank']['amount'];
$society_id = (int)$collection['cash_bank']['society_id'];
$bill_reference = $collection['cash_bank']['bill_reference'];
$narration = $collection['cash_bank']['narration'];
$member = (int)$collection['cash_bank']['member'];
$receiver_name = @$collection['cash_bank']['receiver_name'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$sub_account = (int)$collection['cash_bank']['account_head'];
}
$amount = str_replace( ',', '', $amount );
$am_in_words=ucwords($this->requestAction(array('controller' => 'hms', 'action' => 'convert_number_to_words'), array('pass' => array($amount))));
foreach ($cursor2 as $collection) 
{
$society_name = $collection['society']['society_name'];
$society_reg_no = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
$sig_title = $collection['society']['sig_title'];
}
if($member == 2)
{
$user_name = $receiver_name;
$wing_flat = "";
}
else
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id_d)));
foreach($result_lsa as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
											foreach ($result as $collection) 
											{
											$wing_id = $collection['user']['wing'];  
											$flat_id = (int)$collection['user']['flat'];
											$tenant = (int)$collection['user']['tenant'];
											$user_name = $collection['user']['user_name'];
											}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action'=>'wing_flat'),array('pass'=>array($wing_id,$flat_id)));									
}  
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($sub_account))); 
foreach($result2 as $collection)
{
$bank_name = $collection['ledger_sub_account']['name'];
}
                                    

$date=date("d-m-Y", strtotime($d_date));
//$date = date("d-M-Y",$d_date->sec);
//$words = $this->requestAction(array('controller' => 'hms', 'action'=>'convert_number_to_words'),array('pass'=>array($amount)));	


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
$tcpdf->SetFont($textfont,'B',10); 
$tcpdf->writeHTML('
<table width="100%" border="1">
<tr>
<td>
		<table width="100%">
			<tr>
				<td align="center" ><span style="font-size:14px;">'.strtoupper($society_name).' SOCIETY</span></td>
			</tr>
			<tr>
				<td align="center" ><span style="font-size:10px;color:rgb(100, 100, 99);">Regn# '.$society_reg_no.'</span></td>
			</tr>
			<tr>
				<td align="center" ><span style="font-size:10px;color:rgb(100, 100, 99);">Regn# '.$society_address.'</span><hr/></td>
			</tr>
		</table>
		<table width="100%" cellpadding="5px">
			<tr>
				<td>Receipt No: '.$receipt_no.'</td>
				<td align="right">Date: '.$date.'</td>
			</tr>
			<tr>
				<td>
				Received with thanks from: '.$user_name.' '.$wing_flat.'
				<br/>
				Rupees '.$am_in_words.' Only
				<br/>
				Via '.$receipt_mode.'
				<br/>
				Payment for Bill No. '.$receipt_no.' Dated '.$date.'
				</td>
				<td></td>
			</tr>
		</table>
		<hr/>
		<table width="100%" cellpadding="5px">
			<tr>
				<td><span style="font-size:16px;">Rs '.$amount.'</span><br/>Subject to realization of Cheque(s)</td>
			</tr>
		</table>
		<table width="100%" cellpadding="5px">
			<tr>
				<td width="50%"></td>
				<td align="right">
				<table width="100%">
					<tr>
						<td align="center">
						For '.$society_name.'
						</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
			<td width="50%"></td>
			<td align="right">
			<table width="100%">
					<tr>
						<td align="center"><br/>'.$sig_title.'</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
</td>
</tr>
</table>');







// ... 
// etc. 
// see the TCPDF examples  

echo $tcpdf->Output('Bank Receipt.pdf', 'D'); 

?>
