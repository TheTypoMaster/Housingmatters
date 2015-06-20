<?php
foreach ($cursor1 as $collection) 
{
$receipt_no = (int)$collection['cash_bank']['receipt_id'];
$d_date = $collection['cash_bank']['transaction_date'];
$today = date("d-M-Y");
$user_id_d = (int)$collection['cash_bank']['user_id'];
$amount = $collection['cash_bank']['amount'];
$society_id = (int)$collection['cash_bank']['society_id'];
$narration = $collection['cash_bank']['narration'];
$ac_type = (int)$collection['cash_bank']['account_type'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
}
if($ac_type == 1)
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id_d)));
foreach($result_lsa as $collection)
{
$user_name = $collection['ledger_sub_account']['name'];
}
}
else if($ac_type == 2)
{

$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($user_id_d)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];  
}	
}
foreach ($cursor2 as $collection) 
{
$society_name = $collection['society']['society_name'];
}

$date = date("d-M-Y",strtotime($d_date));
//$words = convert_number_to_words($amount);




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
$tcpdf->SetFont($textfont,'B',2); 

$tcpdf->writeHTML('
<table border="1" width="100%">
<tr>
<td>
<br><br><br><br>
<table border="0" width="94%">
<tr>
<td align="center">
<p style="font-size:10px;">
Receipt No:'.$receipt_no.'</p>
</td>
<td align="center">
<p style="font-size:18px;">'.$society_name.'</p>
</td>
<td align="right">
<p style="font-size:10px;">
Date:'.$date.'
</p>
</td>
</tr>
<tr>
<td></td>
<td align="center">
<p style="font-size:12px;">
Voucher
</p>
</td>
<td></td>
</tr>
<tr>
<td colspan="2">
<br><br><br><br>
<p style="font-size:10px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received with thanks from: '.$user_name.'<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs. (words) only 
</p>
</td>
<td align="center">
<p style="font-size:10px;">

</p>
</td>
</tr>
<tr>
<td colspan="2">
<p style="font-size:10px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Via '.$receipt_mode.'
</p>
</td>
<td align="center">
<p style="font-size:10px;">
Rs. &nbsp;&nbsp;
'.$amount.'
</p>
</td>
</tr>
<tr>
<td colspan="2">
<p style="font-size:10px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment for Bill No. '.$receipt_no.' Dated '.$date.'
</p>
</td>
<td align="center">
<p style="font-size:10px;">
Subject to realization of Cheque
</p>
</td>
</tr>
<tr>
<td colspan="2">
</td>
<td align="center">
<br><br><br><Br>
<p style="font-size:10px;">
For:'.$society_name.'<br>
Secretary/Treasurer
</p>
<br>
</td>
</tr>
</table>


</td>
</tr>
</table>
<img src="../../../../../../Users/Nikhilesh Vyas/Pictures/DSC_0436.JPG"></img>
');



echo $tcpdf->Output('Bank Payment.pdf', 'D'); 

?>