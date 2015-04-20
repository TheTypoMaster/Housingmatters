<?php
App::import('Controller','Hms');
class CashbanksController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);
var $name = 'Cashbanks';

///////////////////// Start bank receipt View/////////////////////////////////////////////////////////
function bank_receipt_view()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
}
////////////////////End Bank receipt View////////////////////////////////////////////////////////////

/////////////////////// Start bank receipt show ajax //////////////////////////////////////////////

function bank_receipt_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_user_id',$s_user_id);
$this->set('s_role_id',$s_role_id);

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$this->set('from',$from);
$this->set('to',$to);


$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->bank_receipt->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$cursor2=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

}

///////////////////////////////////End bank receipt show ajax//////////////////////////////////////////////////

//////////////////////// Start bank receipt ////////////////////////////////////////////

function bank_receipt()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();


App::import('', 'sendsms.php');
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_user_id',$s_user_id);
$this->set('s_role_id',$s_role_id);

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$tenant_c = (int)$collection['user']['tenant'];
}
$this->set('tenant_c',$tenant_c);

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id, "status"=>1);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$date_from = $collection['financial_year']['from'];
$date_to = $collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
$count = sizeof($datef);
$this->set('datef1',$datef1);
$this->set('datet1',$datet1);
$this->set('count',$count);


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$order=array('cash_bank.receipt_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['cash_bank']['receipt_id'];
}
if(empty($last))
{
$zz=0;
}	
else
{	
$zz=$last;
}
$this->set('zz',$zz);



$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "ledger_id" => 34);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
foreach($cursor1 as $collection)
{
$user_id = (int)@$collection['ledger_sub_account']['user_id'];

$this->loadmodel('user');
$conditions=array("user_id" => $user_id);
$cursor2=$this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor',$cursor2);
}


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 33);
$cursor3=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

if(isset($this->request->data['bank_receipt_add']))
{
$current_date = date('d-m-Y');
$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));
$date = $this->request->data['date'];
$bill_no = (int)@$this->request->data['bill_no'];
$date = date("Y-m-d", strtotime($date));
$date = new MongoDate(strtotime($date));
$receipt_instruction = $this->request->data['instruction']; 
$sub_account_id = (int)$this->request->data['bank_account'];
$description = $this->request->data['description'];  
$receipt_mode = @$this->request->data['mode'];
$member_id = (int)@$this->request->data['member'];
if($receipt_mode == 'Cheque' || $receipt_mode == 'NEFT')
{
$cheque_no = (int)$this->request->data['no'];
}
else
{
$cheque_no = "";
}

if($member_id == 1)
{
$received_from = (int)$this->request->data['recieved_from2'];
$amount = $this->request->data['amount'];
}

if($member_id == 2)
{
$received_from = $this->request->data['recieved_from'];
$reference = $this->request->data['refn'];
$amount = $this->request->data['amountn'];
} 





////////////////////////////////////////////////////////
/////////////////////////////////////////////////////// 
if($member_id == 1)
{ 
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$order=array('cash_bank.transaction_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last21=$collection['cash_bank']['transaction_id'];
$last22 = $collection['cash_bank']['receipt_id'];
}
if(empty($last21))
{
$auto=0;
$i = 1000;
}	
else
{	
$auto=$last21;
$i = $last22;
}
$auto++;
$i++; 
$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $received_from, "bill_reference" => $bill_no,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "account_head" => $sub_account_id,   
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id,"member" => $member_id,"module_id"=>1,"cheque_no"=>$cheque_no));
$this->cash_bank->saveAll($multipleRowData);  


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last23=$collection['ledger']['auto_id'];
}
if(empty($last23))
{
$k=0;
}	
else
{	
$k=$last23;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 1, "account_type" => 1,  "account_id" => $received_from, 
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last24=$collection['ledger']['auto_id'];
}
if(empty($last24))
{
$k=0;
}	
else
{	
$k=$last24;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 1, "account_type" => 1, "account_id" => $sub_account_id_a,
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 


$this->loadmodel('regular_bill');
$conditions=array("receipt_id" => $bill_no);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$remain_amt = $collection['regular_bill']['remaining_amount'];
}
$due_amt = $remain_amt - $amount;
if($due_amt == 0)
{
$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("remaining_amount" => $due_amt, "status" => 1),array("receipt_id" => $bill_no));
}
else
{
$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("remaining_amount" => $due_amt, "status" => 0),array("receipt_id" => $bill_no));
}
}		
else if($member_id == 2)
{

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$order=array('cash_bank.transaction_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11=$collection['cash_bank']['transaction_id'];
$last12 = $collection['cash_bank']['receipt_id'];
}
if(empty($last11))
{
$auto=0;
$i = 1000;
}	
else
{	
$auto=$last11;
$i = $last12;
}
$auto++; 
$i++;
$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => 32, "bill_reference" => $reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "account_head" => $sub_account_id,   
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id,"member" => $member_id,"receiver_name" => $received_from,"module_id"=>1,"cheque_no"=>$cheque_no));
$this->cash_bank->saveAll($multipleRowData);  


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last13=$collection['ledger']['auto_id'];
}
if(empty($last13))
{
$k=0;
}	
else
{	
$k=$last13;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 1, "account_type" => 1,  "account_id" => 32, 
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last14=$collection['ledger']['auto_id'];
}
if(empty($last14))
{
$k=0;
}	
else
{	
$k=$last14;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 1, "account_type" => 1, "account_id" => $sub_account_id_a,
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 

}

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$order=array('cash_bank.transaction_id'=> 'ASC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach ($cursor as $collection)
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];	
}
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Bank Receipt</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Bank Receipt #<?php echo $d_receipt_id; ?> has been generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="bank_receipt_view" class="btn blue">OK</a>
</div>
</div>

<?php




///////////Start Sms////////////
if($member_id == 1)
{ 

$date_sms = date('d-m-Y',$date->sec);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$sms_id = $collection['society']['account_sms'];
$society_name_sms = $collection['society']['society_name'];
}

$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $received_from);
$cursor=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$user_id_sms = $collection['ledger_sub_account']['user_id'];
}


$this->loadmodel('user');
$conditions=array("user_id" => $user_id_sms);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$user_name_sms = $collection['user']['user_name'];
//$mobile = $collection['user']['mobile'];	
$mobile = "9799463210";
}
if($sms_id == 1)
{
	
$r_sms=$this->hms_sms_ip();
$working_key=$r_sms->working_key;
$sms_sender=$r_sms->sms_sender; 	
	
$sms='Dear '.$user_name_sms.' we have received Rs '.$amount.' on '.$date_sms.' towards Society Maintanance dues. Cheque are subject to realization,Thanks '.$society_name_sms.'';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey='.$working_key.'&sender='.$sms_sender.'&to='.$mobile.'&message='.$sms1.'');
}
}
/////////////////End Sms/////////////

/////////////// Start MAIL ///////////

$this->loadmodel('user');
$conditions=array("user_id" => $received_from);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
//$to = $collection['user']['email'];	
}
$to = "nikhileshvyas@yahoo.com";

$auto_id = (int)$auto;

$this->loadmodel('bank_receipt');
$conditions=array("transaction_id" => $auto_id);
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$receipt_no = (int)$collection['bank_receipt']['receipt_id'];
$d_date = $collection['bank_receipt']['transaction_date'];
$today = date("d-M-Y");
$user_id = $collection['bank_receipt']['user_id'];
$amount = $collection['bank_receipt']['amount'];
$society_id = (int)$collection['bank_receipt']['society_id'];
$bill_reference = $collection['bank_receipt']['bill_reference'];
$narration = $collection['bank_receipt']['narration'];
$member = (int)$collection['bank_receipt']['member'];
$receipt_mode = $collection['bank_receipt']['receipt_mode'];
$bank_id = (int)$collection['bank_receipt']['sub_account_id'];
}
if(@$member == 1)
{

$resultlsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($bank_id)));
foreach ($resultlsa as $collection) 
{
$bank_name = @$collection['ledger_sub_account']['name'];
}



$resultlsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));
foreach ($resultlsa as $collection) 
{
$user_id_m = (int)@$collection['ledger_sub_account']['user_id'];
}



$resultmail = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id_m)));
foreach ($resultmail as $collection) 
{
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
$user_name = $collection['user']['user_name'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array(@$wing_id,@$flat_id)));	

$this->loadmodel('society');
$conditions=array("society_id" => $society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$date = date("d-M-Y",$d_date->sec);
//$words = $this->convert_number_to_words($amount);

$message_mail = '<table border="0" width="100%">
<tr>
<td>
<br><br>
<table width="100%">
<tr>
<th align="left"><p style="font-size:12px;">Receipt No:'.$receipt_no.'</p></th>
<th align="center"><p style="font-size:20px;">RECEIPT</p></th>
<th align="right"><p style="font-size:12px;">Date:'.$date.'</p></th>
</tr>
<tr>
<th colspan="3" style="text-align:center;"><p style="font-size:18px;">for Previous Bill</p></th>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="100%">
<tr>
<td style="width:70%;"><p style="font-size:12px;">Received with thanks from &nbsp;&nbsp;&nbsp;&nbsp;'.$user_name.'</p></td>
<td style="width:30%;" rowspan="3">
&nbsp;<div style="width:100px; height:25px; border:solid 1px; text-align:center;">
'.$wing_flat.'
</div>

<div style="width:100px; height:25px; border:solid 1px; text-align:center;">
'.$amount.'
</div>


</td>
</tr>
<tr>
<td><p style="font-size:12px;">Rs (Words) only</p></td>
</tr>
<tr>
<td><p style="font-size:12px;">Via &nbsp;&nbsp;'.$receipt_mode.'&nbsp;&nbsp'.$bank_name.' Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.</p></td>
</tr>
<tr>
<td colspan="2"><p style="font-size:12px;">Payment for Bill No.'.$receipt_no.' &nbsp;&nbsp; dated:&nbsp;'.$date.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subject to Realization of cheque</p></td>
</tr>
</table>
<table width="100%">
<tr>
<td style="text-align:right;"><p style="font-size:12px;">'.$society_name.'</p></td>
</tr>
<tr>
<td><p style="font-size:12px; text-align:right;">Secretary/Treasurer</p></td>
</tr>
</table>
</td>
</tr>
</table>
';
$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)
{
$mail_id = $collection['society']['account_email'];
}
if($mail_id == 1)
{
//$to = "nikhileshvyas@yahoo.com";
$subject = "Bank Receipt";
$from_name="HousingMatters";
//$message_web = "Receipt No. :".$d_receipt_id;
$from = "accounts@housingmatters.in";
$reply="accounts@housingmatters.in";
$this->smtpmailer($to,$from,$from_name,$subject,$message_mail,$reply);
}
}
}
}
//////////////////////// End bank receipt ////////////////////////////////////////////

////////////////// Start Bank receipt Excel (Accounts)/////////////////////////////
function bank_receipt_excel()
{
$this->layout="";
$filename="Bank Receipt";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$s_society_id = (int)$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$excel = "<table border='1'>
<tr>
<th colspan='9' style='text-align:center;'>Bank Receipt Report ($society_name)</th>
</tr>

<tr>
<th>From :$from</th>
<th>To : $to</th>
<th colspan='7'></th>
</tr>

<tr>
<th>Receipt#</th>
<th>Transaction Date </th>
<th>Party Name</th>
<th>Bill Reference</th>
<th>Payment Mode</th>
<th>Instrument/UTR</th>
<th>Deposit Bank</th>
<th>Narration</th>
<th>Amount</th>
</tr>";
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>1);
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$receipt_no = $collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by_id = (int)$collection['cash_bank']['prepaired_by'];
$member = (int)$collection['cash_bank']['member'];
$narration = $collection['cash_bank']['narration'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$receipt_instruction = $collection['cash_bank']['receipt_instruction'];
$account_id = (int)$collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];
$current_date = $collection['cash_bank']['current_date'];

if($member == 1)
{
$received_from_id = (int)$collection['cash_bank']['user_id'];
$ref = $collection['cash_bank']['bill_reference'];
$ref = "Bill No:".$ref;
}
if($member == 2)
{
$ref = $collection['cash_bank']['bill_reference'];
$receiver_name = @$collection['cash_bank']['receiver_name'];
}
$creation_date = date('d-m-Y',$current_date->sec);		

$result_prb = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by_id)));
foreach ($result_prb as $collection) 
{
$prepaired_by_name = $collection['user']['user_name'];
}	
if($member == 2)
{
$user_name = $receiver_name;
$wing_flat = "";
	
}		
else
{			
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($received_from_id)));			
foreach ($result_lsa as $collection) 
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];	
}						
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	
			
}			
	$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id)));
	foreach ($result_amt as $collection) 
	{
	$amount_category = $collection['amount_category']['amount_category'];  
	}			

	$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id)));									
	foreach ($result_lsa2 as $collection) 
	{
	$account_no = $collection['ledger_sub_account']['name'];  
	}	
									
									

if($date >= $m_from && $date <= $m_to)
{
if(@$user_id == @$s_user_id)
{
$date = date('d-m-Y',$date->sec);	
$total_debit =  $total_debit + $amount; 


$excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$narration</td>
<td>$user_name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$wing_flat</td>
<td>$ref</td>
<td>$receipt_mode</td>
<td>$receipt_instruction</td>
<td>$account_no</td>
<td>$amount</td>
</tr>";
}
else if($s_role_id == 3)
{
$date = date('d-m-Y',$date->sec);
$total_debit =  $total_debit + $amount; 

$excel.="											
<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$user_name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$wing_flat</td>
<td>$ref</td>
<td>$receipt_mode</td>
<td>$receipt_instruction</td>
<td>$account_no</td>
<td>$narration</td>
<td>$amount</td>
</tr>";
}}}
$excel.="
<tr>
<th colspan='8'> Total</th>
<th>$total_debit</th>
</tr>
<table>";

echo $excel;
}
////////////////// End Bank receipt Excel (Accounts)/////////////////////////////

////////////////////////////////Start Bank Payment (Accounts)//////////////////////////
function bank_payment()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}

	
$this->ath();
$this->check_user_privilages();	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$tenant_c = (int)$collection['user']['tenant'];
}
$this->set('tenant_c',$tenant_c);



$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id, "status"=>1);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$date_from = $collection['financial_year']['from'];
$date_to = $collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
$count = sizeof($datef);
$this->set('datef1',$datef1);
$this->set('datet1',$datet1);
$this->set('count',$count);









$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$order=array('cash_bank.receipt_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['cash_bank']['receipt_id'];
}
if(empty($last))
{
$zz=0;
}	
else
{	
$zz=$last;
}
$this->set('zz',$zz);

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "ledger_id" => 15);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);



$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "ledger_id" => 33);
$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

$this->loadmodel('master_tds');
$cursor3=$this->master_tds->find('all');
$this->set('cursor3',$cursor3);

$this->loadmodel('reference');
$conditions=array("auto_id"=>3);
$cursor = $this->reference->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$tds_arr = $collection['reference']['reference'];
}
$this->set("tds_arr",$tds_arr);

if(isset($this->request->data['bank_payment_add']))
{

$date = $this->request->data['date'];
$date = date("Y-m-d", strtotime($date));
$date = new MongoDate(strtotime($date));

$paid_to = (int)$this->request->data['expense_ac'];
$invoice_reference = $this->request->data['invoice_reference'];
$description = $this->request->data['description']; 
$receipt_mode = $this->request->data['mode'];
$receipt_instruction = $this->request->data['instruction'];
$sub_account_id = (int)$this->request->data['bank_account'];
$amount = $this->request->data['ammount'];				
$tds_id = (int)$this->request->data['tds_p'];
$current_date = date("d-m-Y");		
$ac_type = (int)$this->request->data['ac_type'];

if($ac_type == 1)
{
$account_type = 1;
}
else if($ac_type == 2 || $ac_type == 3)
{
$account_type = 2;
}

$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date)); 

///////////////////Start Insert //////////////////////////////////////
 


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$order=array('cash_bank.transaction_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11 = $collection['cash_bank']['transaction_id'];
$last12 = $collection['cash_bank']['receipt_id'];
}
if(empty($last11))
{
$i=0;
$bbb = 1000;
}	
else
{	
$i=$last11;
$bbb = $last12;
}
$i++; 
$bbb++;
$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $i, "receipt_id" => $bbb,  "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $paid_to, "invoice_reference" => $invoice_reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "account_head" => $sub_account_id,  
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id, "tds_id" => $tds_id,"account_type" => $account_type,"module_id"=>2));
$this->cash_bank->saveAll($multipleRowData);  

//////////////////// End Insert///////////////////////////////
///////////// TDS CALCULATION /////////////////////
$this->loadmodel('master_tds');
$conditions=array("auto_id" => $tds_id);
$cursor4=$this->master_tds->find('all',array('conditions'=>$conditions));
foreach($cursor4 as $collection)
{
$tds_rate = (int)$collection['master_tds']['charge'];	
}

$tds_amount = (int)(round(($tds_rate/100)*$amount));
$total_tds_amount = (int)($amount - $tds_amount);
////////////END TDS CALCULATION //////////////////// 
////////////////START LEDGER ENTRY///////////////////////

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last21 =$collection['ledger']['auto_id'];
}
if(empty($last21))
{
$k=0;
}	
else
{	
$k=$last21;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 2, "account_type" => $account_type, "account_id" => $paid_to,
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Payment"));
$this->ledger->saveAll($multipleRowData); 



$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last22=$collection['ledger']['auto_id'];
}
if(empty($last22))
{
$k=0;
}	
else
{	
$k=$last22;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $total_tds_amount, "amount_category_id" => 2, "module_id" => 2, "account_type" => 1, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Payment"));
$this->ledger->saveAll($multipleRowData); 

if($tds_amount > 0)
{
$sub_account_id_t = 16;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last23=$collection['ledger']['auto_id'];
}
if(empty($last23))
{
$k=0;
}	
else
{	
$k=$last23;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $tds_amount, "amount_category_id" => 2, "module_id" => 2, "account_type" => 2, "account_id" => $sub_account_id_t, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Payment"));
$this->ledger->saveAll($multipleRowData);
}
//////////////////END LEDGER ENTRY/////////////////////
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$order=array('cash_bank.receipt_id'=> 'ASC');
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];	
}
?>

<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Bank Payment</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Payment Voucher No. <?php echo $d_receipt_id; ?> is  generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="bank_payment_view" class="btn blue">OK</a>
</div>
</div>


<?php
}
}

/////////////////////////End Bank Payment(Accounts)///////////////////////////////////

//////////////////////// Start Bank Payment View (Accounts) ////////////////////////
function bank_payment_view()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
}
//////////////////////// End Bank Payment View (Accounts) ///////////////////////////

//////////////////////Start Bank Payment Show Ajax (Accounts)////////////////////////
function bank_payment_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
$this->set('s_user_id',$s_user_id);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->bank_payment->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$cursor2=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
/////////////////////////////////////End Bank Payment Show Ajax (Accounts)////////////////////////////////////////

////////////////////////////// Start Bank Payment Excel //////////////////////////////
function bank_payment_excel()
{
$this->layout="";
$filename="Bank Payment";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$s_society_id = (int)$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));
$excel = "<table border='1'>
<tr>
<th colspan='9' style='text-align:center;'>Bank Payment Report  ($society_name)</th>
</tr>
<tr>
<th>$from</th>
<th>$to</th>
<th colspan='7'></th>
</tr>
<tr>
<th>Transaction Date</th>
<th>Payment Voucher</th>
<th>Amount</th>
<th>Paid To</th>
<th>Invoice Ref</th>
<th>Paid By</th>
<th>Cheque/UTR</th>
<th>Bank Account</th>
<th>Description</th>
</tr>";

$total_debit = 0;
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$receipt_no = $collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by_id = (int)$collection['cash_bank']['prepaired_by'];
$user_id = (int)$collection['cash_bank']['user_id'];   
$invoice_reference = $collection['cash_bank']['invoice_reference'];
$description = $collection['cash_bank']['narration'];
$receipt_mode = $collection['cash_bank']['receipt_mode'];
$receipt_instruction = $collection['cash_bank']['receipt_instruction'];
$account_id = (int)$collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];		
$current_date = $collection['cash_bank']['current_date'];		
$ac_type = $collection['cash_bank']['account_type'];

if($ac_type == 1)
{						


$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];  
}	
}											
else if($ac_type == 2)
{

$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];  
}	
}		
$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id))); 									  
foreach ($result_amt as $collection) 
{
$amount_category = $collection['amount_category']['amount_category'];  
}  

$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['bank_account'];  
}  



if($date >= $m_from && $date <= $m_to)
{
if($s_role_id == 3)
{
$date = date('d-m-Y',$date->sec);
$excel.= "
<tr>
<td>$date</td>
<td>$receipt_no</td>
<td>$amount</td>
<td>$user_name</td>
<td>$invoice_reference</td>
<td>$receipt_mode</td>
<td>$receipt_instruction</td>
<td>$account_no</td>
<td>$description</td>
</tr>";
$total_debit = $total_debit + $amount;								   
}
else if($user_id == $s_user_id)
{
$date = date('d-m-Y',$date->sec);									   
$excel.="
<tr>
<td>$date</td>
<td>$receipt_no</td>
<td>$amount</td>
<td>$user_name</td>
<td>$invoice_reference</td>
<td>$receipt_mode</td>
<td>$receipt_instruction</td>
<td>$account_no</td>
<td>$description</td>
</tr>
";
$total_debit = $total_debit + $amount;
}
}
}				

$excel.="
<tr>
<th colspan='2'></th>
<th>$total_debit</th>
<th colspan='6'></th>
</tr>
</table>
";

echo $excel;




/*


echo $from."\t";
echo $to."\n";

echo "Transaction Date \t Payment Voucher \t Amount \t Paid To \t Invoice Ref \t Paid By \t Cheque/UTR \t Bank Account \t Description \n";
$total_debit = 0;
$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->bank_payment->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$receipt_no = $collection['bank_payment']['receipt_id'];
$transaction_id = (int)$collection['bank_payment']['transaction_id'];	
$date = $collection['bank_payment']['transaction_date'];
$prepaired_by_id = (int)$collection['bank_payment']['prepaired_by'];
$user_id = (int)$collection['bank_payment']['user_id'];   
$invoice_reference = $collection['bank_payment']['invoice_reference'];
$description = $collection['bank_payment']['narration'];
$receipt_mode = $collection['bank_payment']['receipt_mode'];
$receipt_instruction = $collection['bank_payment']['receipt_instruction'];
$account_id = (int)$collection['bank_payment']['account_id'];
$amount = $collection['bank_payment']['amount'];
$amount_category_id = (int)$collection['bank_payment']['amount_category_id'];		
$current_date = $collection['bank_payment']['current_date'];		
$ac_type = $collection['bank_payment']['account_type'];

if($ac_type == 1)
{						


$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];  
}	
}											
else if($ac_type == 2)
{

$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($user_id)));  
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];  
}	
}		
$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id))); 									  
foreach ($result_amt as $collection) 
{
$amount_category = $collection['amount_category']['amount_category'];  
}  

$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['bank_account'];  
}  
*/

//if($amount_category_id == 1)
//{
//if($date >= $m_from && $date <= $m_to)
//{
//if($s_role_id == 3)
//{
//$date = date('d-m-Y',$date->sec);									   
//echo $date."\t"; 
//echo $receipt_no."\t";
//echo $amount."\t"; 
//echo $user_name."\t"; 
///echo $invoice_reference."\t"; 
//echo $receipt_mode."\t"; 
//echo $receipt_instruction."\t"; 
//echo $account_no."\t"; 
//echo $description."\n"; 

//$total_debit = $total_debit + $amount;
//}
//else if($user_id == $s_user_id)
//{
//$date = date('d-m-Y',$date->sec);									   
//echo $date."\t"; 
//echo $receipt_no."\t";
//echo $amount."\t"; 
//echo $user_name."\t"; 
//echo $invoice_reference."\t"; 
//echo $receipt_mode."\t"; 
//echo $receipt_instruction."\t"; 
//echo $account_no."\t"; 
//echo $description."\n"; 
//$total_debit = $total_debit + $amount;
//}
//}
//}									   




//}
//echo "\t";									   
//echo "\t";									   
//echo $total_debit;	
}

/////////////////////////// End Bank Payment Excel ///////////////////////////////

///////////////////// Start Petty cash Receipt (Accounts)///////////////////////////

function petty_cash_receipt()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$tenant_c = (int)$collection['user']['tenant'];
}
$this->set('tenant_c',$tenant_c);


$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id, "status"=>1);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$date_from = $collection['financial_year']['from'];
$date_to = $collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
$count = sizeof($datef);
$this->set('datef1',$datef1);
$this->set('datet1',$datet1);
$this->set('count',$count);







$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$order=array('cash_bank.receipt_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['cash_bank']['receipt_id'];
}
if(empty($last))
{
$zz=0;
}	
else
{	
$zz=$last;
}
$this->set('zz',$zz);


///////////////////////////////////////////
//////////////////////////////////////////
if(isset($this->request->data['ptr_add']))
{
$date = $this->request->data['date'];
$date = date("Y-m-d", strtotime($date));
$date = new MongoDate(strtotime($date));

$user_id = (int)$this->request->data['user_id'];
$narration = $this->request->data['narration']; 
$account_head = (int)$this->request->data['account_head'];
$ammount = $this->request->data['ammount'];
$current_date = date("d-m-Y");
$account_type = (int)$this->request->data['type'];




$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$order=array('cash_bank.transaction_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11 = $collection['cash_bank']['transaction_id'];
$last22 = $collection['cash_bank']['receipt_id'];
}
if(empty($last11))
{
$auto=0;
$i = 1000;
}	
else
{	
$auto = $last11;
$i = $last22;
}
$auto++;
$i++; 
$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "prepaired_by" => $s_user_id,
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "user_id" => $user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $ammount, "amount_category_id" => 1, 
"society_id" => $s_society_id,"module_id"=>3));
$this->cash_bank->saveAll($multipleRowData);  


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last21=$collection['ledger']['auto_id'];
}
if(empty($last21))
{
$k=0;
}	
else
{	
$k=$last21;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $ammount, "amount_category_id" => 2, "module_id" => 3, "account_type" => $account_type, "account_id" => $user_id, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Receipt"));
$this->ledger->saveAll($multipleRowData); 




$sub_account_id_a = (int)$account_head;


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last22=$collection['ledger']['auto_id'];
}
if(empty($last22))
{
$k=0;
}	
else
{	
$k=$last22;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $ammount, "amount_category_id" => 1, "module_id" => 3, "account_type" => 2, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Receipt"));
$this->ledger->saveAll($multipleRowData); 


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$order=array('cash_bank.receipt_id'=> 'ASC');
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];	 
}
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Petty Cash Receipt</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Receipt No. <?php echo $d_receipt_id; ?> is  Generated Successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="petty_cash_receipt_view" class="btn blue">OK</a>
</div>
</div>

<?php
}
}
////////////////////// End Petty Cash Receipt (Accounts) //////////////////////////////

///////////////// Start Petty Cash Receipt Show Ajax (Accounts)////////////////////////

function petty_cash_receipt_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_society_id',$s_society_id);
$this->set('s_role_id',$s_role_id);
$this->set('s_user_id',$s_user_id);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);


$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);



$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
}

//////////////////////////////////// End Petty Cash Receipt Show Ajax (Accounts)///////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Start Petty Cash Receipt View (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function petty_cash_receipt_view()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);




}

//////////////////////////////////////////////////////////End Petty Cash Receipt View (Accounts) ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////// Start Petty cash receipt excel /////////////////////////////
function petty_cash_receipt_excel()
{
$this->layout="";
$filename="Petty Cash Receipt";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$s_society_id = (int)$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$society_name = $collection['society']['society_name'];
}




$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>
Petty Cash Receipt Report  ($society_name)
</th>
</tr>

<tr>
<th>From : $from</th>
<th>To : $to</th>
<th colspan='3'></th>
</tr>
<tr>
<th>PC Receipt#</th>
<th>Transaction Date</th>
<th>Narration</th>
<th>Received From</th>
<th>Amount</th>
</tr>";
$n=1;
$total_credit = 0;
$total_debit = 0;
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$cursor = $this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$receipt_no = @$collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$account_type = (int)$collection['cash_bank']['account_type'];				  
$d_user_id = (int)$collection['cash_bank']['user_id'];
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by = (int)$collection['cash_bank']['prepaired_by'];   
$narration = $collection['cash_bank']['narration'];
$account_head = $collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];
$prepaired_by = (int)$collection['cash_bank']['prepaired_by'];   
$current_date = $collection['cash_bank']['current_date'];
$creation_date = date('d-m-Y',$current_date->sec);					

$result_gh = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by)));
foreach ($result_gh as $collection) 
{
$prepaired_by_name = (int)$collection['user']['user_name'];
}	

if($account_type == 1)
{

$user_id1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($d_user_id)));
foreach ($user_id1 as $collection)
{
$user_id = $collection['ledger_sub_account']['user_id'];
}
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));
}


if($account_type == 2)
{
$user_name1 = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($d_user_id)));
foreach ($user_name1 as $collection)
{
$user_name = $collection['ledger_account']['ledger_name'];
$wing_flat = "";
}
}
			
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by)));
foreach ($result2 as $collection) 
{
$prepaired_by_name = $collection['user']['user_name'];   
$society_id = $collection['user']['society_id'];  	
}	

$amount_cat1 = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id)));
foreach ($amount_cat1 as $collection) 
{
$amount_category_name = $collection['amount_category']['amount_category'];	
}	


if($date >= $m_from && $date <= $m_to)
{
if($s_user_id == $d_user_id)  
{
$date = date('d-m-Y',$date->sec);
$total_debit = $total_debit + $amount;
 $excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$narration</td>
<td>$user_name&nbsp&nbsp&nbsp&nbsp$wing_flat</td>
<td>$amount</td>
</tr>";
}
else
if($s_role_id == 3)
{
$date = date('d-m-Y',$date->sec);  
$total_debit = $total_debit + $amount;
$excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$narration</td>
<td>$user_name &nbsp&nbsp&nbsp&nbsp$wing_flat</td>
<td>$amount</td>
</tr>";
 }}}
 
$excel.="<tr>
<th colspan='4'>Total</th>
<th>$total_debit</th>  
</tr>
</table>"; 
echo $excel;

}
/////////////////////// End Petty cash receipt excel /////////////////////////////

////////////////////////////////Start Petty Cash Receipt Ajax (Accounts)///////////////////////////////////
function petty_cash_receipt_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$value = (int)$this->request->query('value');
$this->set('value',$value);

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 34, "society_id" => $s_society_id,"deactive"=>0);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('ledger_account');
$conditions=array("group_id" => 8);
$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}

////////////////////////////End Petty Cash Receipt Ajax (Accounts)///////////////////////////////////////////////

///////////////////////////// Start Petty cash Receipt Pdf (Accounts)///////////////////////////////////////////
function petty_cash_receipt_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);


$this->loadmodel('cash_bank');
$conditions=array("transaction_id" => $tns_id,"module_id"=>3);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
/////////////////////////// End Petty cash Receipt Pdf (Accounts)//////////////////////////////////////////////////

/////////////////////// Start Petty Cash Payment (Accounts) /////////////////////////// 

function petty_cash_payment()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$tenant_c = (int)$collection['user']['tenant'];
}
$this->set('tenant_c',$tenant_c);



$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id, "status"=>1);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$date_from = $collection['financial_year']['from'];
$date_to = $collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
$count = sizeof($datef);
$this->set('datef1',$datef1);
$this->set('datet1',$datet1);
$this->set('count',$count);


$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4);
$order=array('cash_bank.receipt_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['cash_bank']['receipt_id'];
}
if(empty($last))
{
$zz=0;
}	
else
{	
$zz=$last;
}
$this->set('zz',$zz);



$this->loadmodel('master_tds');
$cursor1=$this->master_tds->find('all');
$this->set('cursor1',$cursor1);

//////////////////////////////////////////////////
//////////////////////////////////////////////////
if(isset($this->request->data['ptp_add']))
{

$date = $this->request->data['date'];
$date = date("Y-m-d", strtotime($date));
$date = new MongoDate(strtotime($date));
$user_id = (int)$this->request->data['user_id'];
$narration = $this->request->data['narration']; 
$account_head = (int)$this->request->data['account_head'];
$amount = $this->request->data['ammount'];
$current_date = date("d-m-Y");
//$tds_id = (int)$this->request->data['tds_pp'];
$account_type = (int)$this->request->data['type'];

$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));



$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4);
$order=array('cash_bank.transaction_id'=> 'DESC');
$cursor=$this->cash_bank->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11 = $collection['cash_bank']['transaction_id'];
$last12 = $collection['cash_bank']['receipt_id'];
}
if(empty($last11))
{
$auto=0;
$i = 1000;
}	
else
{	
$auto=$last11;
$i = $last12;
}
$auto++; 
$i++;
$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i,  "user_id" => $user_id, 
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $amount, "amount_category_id" => 1, 
"society_id" => $s_society_id,"module_id"=>4));
$this->cash_bank->saveAll($multipleRowData);  







/* $this->loadmodel('master_tds');
$conditions=array("auto_id" => $tds_id);
$cursor2=$this->master_tds->find('all',array('conditions'=>$conditions));
foreach($cursor2 as $collection)
{
$tds_rate = (int)$collection['master_tds']['charge'];
}
$tds_amount = (int)(round(($tds_rate/100)*$amount));
$total_tds_amount = (int)($amount - $tds_amount);
*/

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last21=$collection['ledger']['auto_id'];
}
if(empty($last21))
{
$k=0;
}	
else
{	
$k=$last21;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 4, "account_type" => $account_type, "account_id" => $user_id, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Payment"));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a =  (int)$account_head;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last22=$collection['ledger']['auto_id'];
}
if(empty($last22))
{
$k=0;
}	
else
{	
$k=$last22;
}
$k++; 
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 4, "account_type" => 2, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Payment"));
$this->ledger->saveAll($multipleRowData); 




$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4);
$order=array('cash_bank.receipt_id'=> 'ASC');
$cursor3=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];		
}
?>

<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Petty Cash Payment</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Petty Cash Voucher <?php echo $d_receipt_id; ?> is  generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="petty_cash_payment_view" class="btn blue">OK</a>
</div>
</div>

<?php
}
///////////////////////////////////////////
//////////////////////////////////////////
}

//////////////////////// End Petty cash Payment (Accounts) ////////////////////////////

////////////////////////////////////////////////////////// Start Petty cash Payment View (Accounts)/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function petty_cash_payment_view()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

}

/////////////////////////////////// End Petty cash Payment View (Accounts) ///////////////////////////////////

///////////////////////Start Petty Cash Payment Show Ajax (Accounts)/////////////////////
function petty_cash_payment_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$this->set('s_user_id',$s_user_id);
$this->set('s_role_id',$s_role_id);

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
////////////////////////End Petty Cash Payment Show Ajax (Accounts)//////////////////////

//////////////////////////////////////////// Start Petty Cash Payment Pdf (Accounts)////////////////////////////////////////////////////////////////////
function petty_cash_payment_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$module_id = (int)$this->request->query('m');
$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);
$this->set('module_id',$module_id);


$this->loadmodel('cash_bank');
$conditions=array("transaction_id" => $tns_id,"module_id"=>4);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
//////////////////////////////////////////// End Petty Cash Payment Pdf (Accounts)//////////////////////////////////////
/////////////////////////////////////////////Start Petty Cash Payment Ajax (Accounts) ///////////////////////////////////////////////////////////////////

function petty_cash_payment_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$value1 = (int)$this->request->query('value1');
$this->set('value1',$value1);


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 15);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('accounts_group');
$conditions=array("accounts_id" => 4);
$cursor2=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);
}
///////////////////////////////////////End Petty Cash Payment Ajax (Accounts) ////////////////////////////////

/////////////////////// Start Petty Cash Payment Excel//////////////////////////////
function petty_cash_payment_excel()
{
$this->layout="";
$filename="Petty Cash Payment";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$s_society_id = (int)$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$society_name = $collection['society']['society_name'];
}


$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>
Petty Cash Payment Report  ($society_name)
</th>
</tr>
<tr>
<th>From : $from</th>
<th>To : $to</th>
<th colspan='3'></th>
</tr>
<tr>
<th>PC Payment Vochure</th>
<th>Transaction Date</th>
<th>Paid To</th>
<th>Narration</th>
<th>Amount</th>
</tr>";
										
$total_debit = 0;
$total_credit = 0;
$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4);
$cursor = $this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$receipt_no = (int)@$collection['cash_bank']['receipt_id'];
$transaction_id = (int)$collection['cash_bank']['transaction_id'];	
$account_type = (int)$collection['cash_bank']['account_type'];
$user_id = (int)$collection['cash_bank']['user_id'];
$date = $collection['cash_bank']['transaction_date'];
$prepaired_by = (int)$collection['cash_bank']['prepaired_by'];   
$narration = $collection['cash_bank']['narration'];
$account_head = $collection['cash_bank']['account_head'];
$amount = $collection['cash_bank']['amount'];
$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];
$current_date = $collection['cash_bank']['current_date'];
$creation_date = date('d-m-Y',$current_date->sec);										
										
$result_gh = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by)));
foreach ($result_gh as $collection) 
{
$prepaired_by_name = $collection['user']['user_name'];
}			
if($account_type == 1)
{
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($user_id)));
foreach ($result_lsa as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];	  
}
}										
else if($account_type == 2)
{
$result_la = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($user_id)));
foreach ($result_la as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name'];	  
}
}   										
$result_amt = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id)));
foreach ($result_amt as $collection) 
{
$amount_category_name = $collection['amount_category']['amount_category'];	  
}  

if($date >= $m_from && $date <= $m_to)
{
if($s_user_id == $user_id)  
{
$date = date('d-m-Y',$date->sec);     
$total_debit = $total_debit + $amount;										

$excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$user_name</td>
<td>$narration</td>
<td>$amount</td>										
</tr>";	
}
else if($s_role_id == 3)
{
$date = date('d-m-Y',$date->sec);	   
$total_debit = $total_debit + $amount;

$excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$user_name</td>
<td>$narration</td>
<td>$amount</td>
</tr>";
   }}}

$excel.="<tr>
<th colspan='4'>Total</th>
<th>$total_debit</th>
</tr>
</table>";

echo $excel;
}
/////////////////////// End Petty Cash Payment Excel////////////////////////////////

/////////////////////////////////////////////////////////////// Start Bank receipt Reference Ajax (Accounts)/////////////////////////////////////////////
function bank_receipt_reference_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$value1 = (int)$this->request->query('value1');
$this->set('value1',$value1);

$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $value1);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}

/////////////////////////////////////////////////////////////End Bank Receipt Reference Ajax (Accounts)//////////////////////////////////////////////////


/////////////////////////////////////////////////////// Start Bank Receipt Amount Ajax(Accounts)/////////////////////////////////////////////////////////
function bank_receipt_amount_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$i_head = $this->request->query('ss');
$this->set('i_head',$i_head);

}

////////////////////////////// End Bank Receipt Amount Ajax(Accounts)/////////////////////////////////////

/////////////////////////// Start Bank Receipt Pdf (Accounts)//////////////////////////////////////
function bank_receipt_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$module_id = (int)$this->request->query('m');
$trns_id = (int)$this->request->query('c');
$this->set('trns_id',$trns_id);
$this->set('module_id',$module_id);

$this->loadmodel('cash_bank');
$conditions=array("transaction_id" => $trns_id,"module_id"=>$module_id);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);



$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);



}
////////////////////////////////////////// End Bank Receipt Pdf (Accounts)////////////////////////////////////

/////////////////// Start Cash Bank Vali (Accounts) ////////////////////////////////////
function cash_bank_vali()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$cc = (int)$this->request->query('ss');
$this->set('cc',$cc);
}
/////////////////// End Cash Bank Vali (Accounts) ////////////////////////////////////////

//////////////////////////// Start Bank Receipt ajax (Accounts)///////////////////////
function bank_receipt_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$ff = $this->request->query('ff');
$this->set('ff',$ff);

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 33);
$cursor3=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "ledger_id" => 34,"deactive"=>0);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
//////////////////////// End bank receipt Ajax (Accounts)/////////////////////////////////

//////////////////////////////////////////////////////////// Start tds Bank Payment Ajax (Accounts)//////////////////////////////////////////////////////
function bank_payment_tds_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$tds = (int)$this->request->query('tds');
$amount = (int)$this->request->query('amount');


$this->loadmodel('master_tds');
$conditions=array("auto_id" => $tds);
$cursor1=$this->master_tds->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$charge = (int)$collection['master_tds']['charge'];
}
$tds_charge = (float)(($charge/100)*$amount);
$total_amount = round($amount - $tds_charge); 
$this->set('total_amount',$total_amount);
}
/////////////////////// End tds bank Payment Ajax (Accounts)///////////////////////

////////////////////////// Start Bank Payment Type Ajax////////////////////////////////////
function bank_payment_type_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$type= (int)$this->request->query('type');
$this->set('type',$type);

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 15);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('accounts_group');
$conditions=array("accounts_id" => 1);
$cursor2=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

$this->loadmodel('accounts_group');
$conditions=array("accounts_id" => 4);
$cursor3=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);
}
////////////////////////End Bank Payment Type Ajax /////////////////////////////////////

//////////////////////////////////////// Start bank payment Pdf (Accounts)///////////////////////////////////////
function bank_payment_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$module_id = (int)$this->request->query('m');
$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);
$this->set('module_id',$module_id);



$this->loadmodel('cash_bank');
$conditions=array("transaction_id" => $tns_id,"module_id"=>$module_id);
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
//////////////////////////////////////// End bank payment Pdf (Accounts)////////////////////////////////////////////

//////////////////////////////////Start Fix Deposit Add (Accounts) ////////////////////////////////////////////////////
function fix_deposit_add()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();		
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

if(isset($this->request->data['sub']))
{
$bank_name = $this->request->data['bank_name'];
$branch = $this->request->data['branch'];
$account_reference = $this->request->data['account_reference'];
$principal_amount = $this->request->data['principal_amount'];
$start_date = $this->request->data['start_date'];
$maturity_date = $this->request->data['maturity_date'];
$interest_rate = $this->request->data['interest_rate'];
$remark = $this->request->data['remark'];
$reminder = $this->request->data['reminder'];
$tds = $this->request->data['tds'];
$name = $this->request->data['name'];
$email = $this->request->data['email'];
$mobile = $this->request->data['mobile'];

$current_date = date('d-m-Y');
$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));

$start_date = date("Y-m-d", strtotime($start_date));
$start_date = new MongoDate(strtotime($start_date));

$maturity_date = date("Y-m-d", strtotime($maturity_date));
$maturity_date = new MongoDate(strtotime($maturity_date));

$this->loadmodel('fix_deposit');
$conditions=array("society_id" => $s_society_id);
$order=array('fix_deposit.auto_id'=> 'DESC');
$cursor=$this->fix_deposit->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11 = $collection['fix_deposit']['auto_id'];
}
if(empty($last11))
{
$i=0;
}	
else
{	
$i=$last11;
}
$i++; 
$this->loadmodel('fix_deposit');
$multipleRowData = Array( Array("auto_id" => $i, "bank_name" => $bank_name,  "branch" => $branch, "account_reference" => $account_reference, "prepaired_by" => $s_user_id, 
"principal_amount" => $principal_amount, "start_date" => $start_date,"maturity_date" => $maturity_date, "interest_rate" => $interest_rate,"remark" => $remark, "reminder" => $reminder,"tds" => $tds, "name" => $name, "society_id" => $s_society_id, "email" => $email,"mobile" => $mobile, "current_date"=>$current_date));
$this->fix_deposit->saveAll($multipleRowData);
?>

<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Fix Deposit</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Record Inserted Successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="fix_deposit_view" class="btn blue">OK</a>
</div>
</div>












<?php
}
















}
/////////////////////////////////////End Fix Deposit Add (Accounts) //////////////////////////////////////////////////////

////////////////////////////////////////////////////////////// Start Fix Deposit View (Accounts) ////////////////////////////////////////////////////////
function fix_deposit_view()
{
if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();		
	
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


}

//////////////////////////////////////////////////////////// End Fix Deposit View (Accounts) /////////////////////////////

//////////////////////////////////// Start Fix Deposit Show Ajax ///////////////////////////////////////////////////////

function fixed_diposit_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

//$from = date("Y-m-d", strtotime($from));
//$from = new MongoDate(strtotime($from));

//$to = date("Y-m-d", strtotime($to));
//$to = new MongoDate(strtotime($to));

$this->loadmodel('fix_deposit');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->fix_deposit->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
//////////////////////////////////// End Fix Deposit Show Ajax ///////////////////////////////////////////////////////


}
?>