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

/////////////////////////////////////////////////////

foreach ($cursor1 as $collection) 
{
$receipt_no = (int)$collection['new_cash_bank']['receipt_id'];
$d_date = $collection['new_cash_bank']['receipt_date'];
$today = date("d-M-Y");
$flat_id = $collection['new_cash_bank']['party_name_id'];
$amount = $collection['new_cash_bank']['amount'];
$society_id = (int)$collection['new_cash_bank']['society_id'];
$bill_reference = $collection['new_cash_bank']['reference_utr'];
$narration = @$collection['new_cash_bank']['narration'];
$member = (int)$collection['new_cash_bank']['member_type'];
$receiver_name = @$collection['new_cash_bank']['receiver_name'];
$receipt_mode = $collection['new_cash_bank']['receipt_mode'];
$cheque_number = @$collection['new_cash_bank']['cheque_number'];
$which_bank = @$collection['new_cash_bank']['which_bank'];
$reference_number = @$collection['new_cash_bank']['reference_number'];
$cheque_date = @$collection['new_cash_bank']['cheque_date'];
$sub_account = (int)$collection['new_cash_bank']['deposited_bank_id'];
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
$user_name = $flat_id;
$wing_flat = "";
}
else
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch2'),array('pass'=>array($flat_id)));
foreach($result_lsa as $collection)
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
                                    

$date=date("d-m-Y",($d_date));

$q='table style="width:70%; background-color:#FFF;" border="1">';
$q.='<tr></td>
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">'.strtoupper($society_name).'</div>
<div align="center">
<span style="font-size:12px;color:rgb(100, 100, 99);">Regn# '.$society_reg_no.'</span><br/>
<span style="font-size:12px;color:rgb(100, 100, 99);">Regn# '.$society_address.'</span>
</div>';
$q.='</td></tr></table>';		
	



///////////////////////////////////////////////////////////////////////
$tcpdf->writeHTML($q);

echo $tcpdf->Output('bank_receipt.pdf', 'D'); 

?>