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

$this->loadmodel('new_cash_bank');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->new_cash_bank->find('all',array('conditions'=>$conditions));
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
if($this->RequestHandler->isAjax())
{
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
$date_from = @$collection['financial_year']['from'];
$date_to = @$collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
if(!empty($datef))
{
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
}
$count = sizeof(@$datef);
$this->set('datef1',@$datef1);
$this->set('datet1',@$datet1);
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
$conditions=array("ledger_id" => 33,"society_id"=>$s_society_id);
$cursor3=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

if(isset($this->request->data['bank_receipt_add']))
{

///////////////////////////////////////////
$current_date = date('Y-m-d');

 $transaction_date = $this->request->data['transaction_date'];
 $transaction_date = date('Y-m-d',strtotime($transaction_date));
 $receipt_mode = $this->request->data['receipt_mode'];
if($receipt_mode == "Cheque")
{

	 $cheque_number = $this->request->data['cheque_number'];
	
	 $cheque_date = $this->request->data['cheque_date'];
	
	 $drawn_on_which_bank = $this->request->data['drawn_on_which_bank'];
	}
else
{
 $reference_utr = $this->request->data['reference_number'];
 $cheque_date = $this->request->data['cheque_date'];
}
 $deposited_bank_id = $this->request->data['deposited_bank_id'];
 $member_type = $this->request->data['member_type'];
if($member_type == 1)
{
$party_name = (int)$this->request->data['party_name_id'];
$receipt_type = (int)$this->request->data['receipt_type'];
$flat_id = $party_name;
	if($receipt_type == 1)
	{
		$amount = $this->request->data['amount'];
	}
	else
	{
		$amount = $this->request->data['amount'];
	}

}
else 
{
	 $party_name = $this->request->data['member_type'];
	 $bill_reference = $this->request->data['member_type'];
	 $amount = $this->request->data['amount'];
}

 $narration = $this->request->data['description'];



////////////////////////////////
	
$s_society_id =(int)$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');
$s_user_id=$this->Session->read('user_id');
	
	//$member_id = (int)@$this->request->data['member'];
	//$flat_id = (int)$this->request->data['recieved_from2'];
	//$receipt_date = $this->request->data['date'];
	
	
if($member_type == 1)
{
	if($receipt_type == 1)
	{
	
	$amount = $this->request->data['amount'];
    //apply receipt in regular_bill//
	$this->loadmodel('new_regular_bill');
	$condition=array('society_id'=>$s_society_id,"flat_id"=>$flat_id);
	$order=array('new_regular_bill.one_time_id'=>'DESC');
	$result_new_regular_bill=$this->new_regular_bill->find('first',array('conditions'=>$condition,'order'=>$order)); 
	$this->set('result_new_regular_bill',$result_new_regular_bill);
	foreach($result_new_regular_bill as $data){
	$auto_id=$data["auto_id"]; 
	$arrear_intrest=$data["arrear_intrest"];
	$intrest_on_arrears=$data["intrest_on_arrears"];
	$total=$data["total"];
	$arrear_maintenance=$data["arrear_maintenance"];
	$regular_bill_one_time_id = (int)$data["one_time_id"];
	}
    	$amount_after_arrear_intrest=$amount-$arrear_intrest;
		if($amount_after_arrear_intrest<0)
		{
		$new_arrear_intrest=abs($amount_after_arrear_intrest);
		$new_intrest_on_arrears=$intrest_on_arrears;
		$new_arrear_maintenance=$arrear_maintenance;
		$new_total=$total;
		}
		else
		{
		$new_arrear_intrest=0;
		$amount_after_intrest_on_arrears=$amount_after_arrear_intrest-$intrest_on_arrears;
			if($amount_after_intrest_on_arrears<0)
			{
			$new_intrest_on_arrears=abs($amount_after_intrest_on_arrears);
			$new_arrear_maintenance=$arrear_maintenance;
			$new_total=$total;
			}
			else
			{
			$new_intrest_on_arrears=0;
			$amount_after_arrear_maintenance=$amount_after_intrest_on_arrears-$arrear_maintenance;
				if($amount_after_arrear_maintenance<0){
				$new_arrear_maintenance=abs($amount_after_arrear_maintenance);
				$new_total=$total;
				}else{
				$new_arrear_maintenance=0;
				$amount_after_total=$amount_after_arrear_maintenance-$total; 
				if($amount_after_total>0){
				$new_total=0;
				$new_arrear_maintenance=-$amount_after_total;
				}else{
							$new_total=abs($amount_after_total);
							
						}
						
					}
				}
			}

			
			$this->loadmodel('new_regular_bill');
			$this->new_regular_bill->updateAll(array('new_arrear_intrest'=>$new_arrear_intrest,"new_intrest_on_arrears"=>$new_intrest_on_arrears,"new_arrear_maintenance"=>$new_arrear_maintenance,"new_total"=>$new_total),array('auto_id'=>$auto_id));

/////////////////////////////////////////////////////////////////////////////

	$k = (int)$this->autoincrement('new_cash_bank','receipt_id');
	$this->loadmodel('new_cash_bank');
	$multipleRowData = Array( Array("receipt_id" => $k, "receipt_date" => strtotime($transaction_date), "receipt_mode" => $receipt_mode, "cheque_number" =>@$cheque_number,"cheque_date" =>$cheque_date,"drawn_on_which_bank" =>@$drawn_on_which_bank,"reference_utr" => @$reference_utr,"deposited_bank_id" => $deposited_bank_id,"member_type" => $member_type,"party_name_id"=>$party_name,"receipt_type" => $receipt_type,"amount" => $amount,"current_date" => $current_date,"society_id"=>$s_society_id,"flat_id"=>$party_name,"bill_auto_id"=>$auto_id,"bill_one_time_id"=>$regular_bill_one_time_id,));
	$this->new_cash_bank->saveAll($multipleRowData);

	
	
	
//////////////////////////////////////////////////////////////////////////////
		
}
if($receipt_type == 2)
	{

	$k = (int)$this->autoincrement('new_cash_bank','receipt_id');
	$this->loadmodel('new_cash_bank');
	$multipleRowData = Array( Array("receipt_id" => $k, "receipt_date" => strtitime($transaction_date), "receipt_mode" => $receipt_mode, "cheque_number" =>$cheque_number,"cheque_date" =>$cheque_date,"drawn_on_which_bank" =>$drawn_on_which_bank,"reference_utr" => $reference_utr,"deposited_bank_id" => $deposited_bank_id,"member_type" => $member_type,"party_name_id"=>$party_name,"receipt_type" => $receipt_type,"amount" => $amount,"current_date" => $current_date,"society_id"=>$s_society_id,"flat_id"=>$party_name));


	$this->new_cash_bank->saveAll($multipleRowData);
	}
}
else if($member_type == 2)
{
/////////////////////////

//////////////////////////
$k = (int)$this->autoincrement('new_cash_bank','receipt_id');
$this->loadmodel('new_cash_bank');
$multipleRowData = Array( Array("receipt_id" => $k, "receipt_date" => strtotime($transaction_date), "receipt_mode" => $receipt_mode, "cheque_number" =>$cheque_number,"cheque_date" =>$cheque_date,"drawn_on_which_bank" =>$drawn_on_which_bank,"reference_utr" => @$reference_utr,"deposited_bank_id" => $deposited_bank_id,"member_type" => $member_type,"party_name_id"=>$party_name,"receipt_type" => @$receipt_type,"amount" => $amount,"current_date" => $current_date,"society_id"=>$s_society_id,"flat_id"=>$party_name));
$this->new_cash_bank->saveAll($multipleRowData);
}
	
$result_new_regular_bill = $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_last_bill_info_via_flat_id'),array('pass'=>array($party_name)));
if(sizeof($result_new_regular_bill)==1){
foreach($result_new_regular_bill as $last_bill){
$bill_auto_id=$last_bill["auto_id"];
$bill_one_time_id=$last_bill["one_time_id"];
}
}
}
}

function upload_csv_cash_bank(){
	$this->layout=null;
	if(isset($_FILES['file'])){
		echo "hello"; exit;
		$file_name=$_FILES['file']['name'];
		$file_tmp_name =$_FILES['file']['tmp_name'];
		$target = "csv_file/cashbank/";
		$target=@$target.basename($file_name);
		move_uploaded_file($file_tmp_name,@$target);
		
		$f = fopen('csv_file/cashbank/'.$file_name, 'r') or die("ERROR OPENING DATA");
		$batchcount=0;
		$records=0;
		while (($line = fgetcsv($f, 4096, ';')) !== false) {
		// skip first record and empty ones
		$numcols = count($line);

		$test[]=$line;

		//echo $col = $line[0];
		//echo $batchcount++.". ".$col."\n";


		++$records;
		}

		fclose($f);
		$records;
	}
}
//////////////////////// End bank receipt email code ////////////////////////////////////////////

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
//$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

$s_society_id = (int)$this->Session->read('society_id');
$s_role_id= (int)$this->Session->read('role_id');
$s_user_id= (int)$this->Session->read('user_id');


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}



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


$total_debit = 0;
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


if($receipt_mode == "Cheque" || $receipt_mode == "NEFT")
{
$cheque_no = $collection['cash_bank']['cheque_no'];	
$receipt_mode = $receipt_mode."(".$cheque_no.")";
}

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

$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id)));									
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['name'];  
}	

if($date >= $m_from && $date <= $m_to)
{
if(@$user_id == @$s_user_id)
{
$date3 = date('d-m-Y',strtotime($date));	
$total_debit =  $total_debit + $amount; 

$excel.="<tr>
<td>$receipt_no</td>
<td>$date2</td>
<td>$user_name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$wing_flat</td>
<td>$ref</td>
<td>$receipt_mode</td>
<td>$receipt_instruction</td>
<td>$account_no</td>
<td>$narration</td>
<td>$amount</td>
</tr>";
}
else if($s_role_id == 3)
{
$date2 = date('d-m-Y',strtotime($date));
$total_debit =  $total_debit + $amount; 

$excel.="											
<tr>
<td>$receipt_no</td>
<td>$date2</td>
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
<th colspan='8' style='text-align:right;'>Total</th>
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
$date_from = @$collection['financial_year']['from'];
$date_to = @$collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}

if(!empty($datef))
{
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
}
$count = sizeof(@$datef);
$this->set('datef1',@$datef1);
$this->set('datet1',@$datet1);
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
//$date = new MongoDate(strtotime($date));
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
//$m_from = new MongoDate(strtotime($m_from));

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
//$m_to = new MongoDate(strtotime($m_to));

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

$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id))); 					   
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['bank_account'];  
}  

if($date >= $m_from && $date <= $m_to)
{
if($s_role_id == 3)
{
$date = date('d-m-Y',strtotime($date));
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
$date = date('d-m-Y',strtotime($date));									   
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
$date_from = @$collection['financial_year']['from'];
$date_to = @$collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
if(!empty($datef))
{
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
}
$count = sizeof(@$datef);
$this->set('datef1',@$datef1);
$this->set('datet1',@$datet1);
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
if(isset($this->request->data['ptr_sasdadd']))
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
$s_role_id = (int)$this->Session->read('role_id');

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
//$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

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

if($date >= $m_from && $date <= $m_to)
{
if($s_role_id == 3)
{
$date = date('d-m-Y',strtotime($date));  
$total_debit = $total_debit + $amount;
$excel.="<tr>
<td>$receipt_no</td>
<td>$date</td>
<td>$narration</td>
<td>$user_name &nbsp&nbsp&nbsp&nbsp $wing_flat</td>
<td>$amount</td>
</tr>";
 }}}
$excel.="<tr>
<th colspan='4'>Total</th>
<th>$total_debit</th>  
</tr></table>";

 
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
$date_from = @$collection['financial_year']['from'];
$date_to = @$collection['financial_year']['to'];

$date_from1 = date('Y-m-d',$date_from->sec);
$date_to1 = date('Y-m-d',$date_to->sec);

$datef[] = $date_from1;
$datet[] = $date_to1;
}
if(!empty($datef))
{
$datef1 = implode(',',$datef);
$datet1 = implode(',',$datet);
}
$count = sizeof(@$datef);
$this->set('datef1',@$datef1);
$this->set('datet1',@$datet1);
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
//$date = new MongoDate(strtotime($date));
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
$s_user_id = (int)$this->Session->read('user_id');

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
//$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

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
//$amount_category_id = (int)$collection['cash_bank']['amount_category_id'];
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

if($date >= $m_from && $date <= $m_to)
{
if($s_user_id == $user_id)  
{
$date = date('d-m-Y',strtotime($date));     
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
$date = date('d-m-Y',strtotime($date));     
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

//$flat_id = (int)$this->request->query('value1'); 
$flat_id = (int)$this->request->query('flat');
$type = (int)$this->request->query('type');
$this->set('type',$type);
$this->set('flat_id',$flat_id);
//$this->set('flat_id',$flat_id);




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

function b_receipt_view()
{
$this->layout = 'session'; //this will use the pdf.ctp layout 
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

function b_receipt_edit($trns_id=null,$module_id=null){
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	$s_role_id = (int)$this->Session->read('role_id');
	$s_society_id = (int)$this->Session->read('society_id');
	$s_user_id = (int)$this->Session->read('user_id');	

	$trns_id=(int)$trns_id;
	$module_id=(int)$module_id;
	$this->ath();
	
	$this->loadmodel('ledger_sub_account');
	$conditions=array("ledger_id" => 33,"society_id"=>$s_society_id);
	$cursor3=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
	$this->set('cursor3',$cursor3);

	$this->loadmodel('cash_bank');
	$conditions=array("transaction_id" => $trns_id,"module_id"=>$module_id);
	$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
	$this->set('cursor1',$cursor1);
	
	if(isset($this->request->data['bank_receipt_update'])){
		$transaction_date = $this->request->data['t_date']; 
		$mode = $this->request->data['mode'];
		$cheque_number = @$this->request->data['cheque_number'];
		$which_bank = $this->request->data['which_bank'];
		$reference_number = $this->request->data['reference_number'];
		$cheque_date = $this->request->data['cheque_date'];
		$bank_account = $this->request->data['bank_account'];
		$bank_rrr = (int)$this->request->data['rrrr'];
		$member = (int)$this->request->data['mmmm'];
		$amount = $this->request->data['amount'];
		$t_id = $this->request->data['t_id'];
		
		if($member == 1)
		{
		$bill_for = (int)$this->request->data['ffff'];
		if($bill_for == 1)
		{
		$bill_no = (int)$this->request->data['regrec'];
		}
		}
		$current_date = date('Y-m-d');
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$this->loadmodel('cash_bank');
$this->cash_bank->updateAll(array("transaction_date" => $transaction_date, "prepaired_by" => $s_user_id,"bill_reference" => $bill_no,"receipt_mode" => $mode,"account_head" => $bank_account,"amount" => $amount,"member" => $member,"module_id"=>1,"cheque_number"=>$cheque_number,"reference_number"=>$reference_number,"which_bank"=>$which_bank,"cheque_date"=>$cheque_date,"receipt_for_type"=>$bill_for),array("receipt_id" => $bank_rrr,"society_id"=>$s_society_id,"module_id"=>1));


 
$this->loadmodel('ledger');
$this->ledger->updateAll(array("amount" => $amount,"current_date" => $current_date),array("receipt_id" => $bank_rrr,"module_id" => 1)); 
$this->redirect(array('controller' => 'Cashbanks','action' => 'b_receipt_view?c='.$t_id.'&m=1'));






	
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}

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


$this->loadmodel('reference');
$conditions=array("auto_id" => 3);
$cursor1=$this->reference->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$tds_arr = $collection['reference']['reference'];
}
for($t=0; $t<sizeof($tds_arr); $t++)
{
$tds_arr2 = $tds_arr[$t];
$tds_id = (int)$tds_arr2[1];
if($tds_id == $tds)
{
$charge = $tds_arr2[0];
break;
}
}
$tds_charge = (float)((@$charge/100)*$amount);
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
//$tds = $this->request->data['tds'];
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
"principal_amount" => $principal_amount, "start_date" => $start_date,"maturity_date" => $maturity_date, "interest_rate" => $interest_rate,"remark" => $remark, "reminder" => $reminder,"name" => $name, "society_id" => $s_society_id, "email" => $email,"mobile" => $mobile, "current_date"=>$current_date));
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

$this->loadmodel('fix_deposit');
$conditions=array("society_id" => $s_society_id,"status"=>0);
$cursor1 = $this->fix_deposit->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


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

///////////////////////////////////////// Start Bank Payment Json //////////////////////////////////////////////////
function bank_payment_json()
{
$this->layout=null;
$post_data=$this->request->data;
$this->ath();
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$transaction_date = $post_data['tra_dat'];
$ac_group = (int)$post_data['ac_grp'];
$exp_party = (int)$post_data['ex_prt_acn'];
$invoice_ref = $post_data['inv_ref'];
$narration = $post_data['desc'];
$mode = $post_data['mode'];
$inst_utr = $post_data['inst_utr'];
$bank_ac = (int)$post_data['bank_acn'];
$amt = $post_data['amt'];
$tds = (int)$post_data['tds'];
$tt_amt = $post_data['tt_amt'];

$report = array();

if(empty($transaction_date)){
$report[]=array('label'=>'tr_dat', 'text' => 'Please select Transaction Date');
}	

if(empty($ac_group)){
$report[]=array('label'=>'ac_gr', 'text' => 'Please select Account Group');
}
	
if(empty($exp_party)){
$report[]=array('label'=>'ex_prt', 'text' => 'Please select Expense Party Account');
}	

if(empty($invoice_ref)){
$report[]=array('label'=>'inv_ref', 'text' => 'Please Fill Invoice Reference');
}	


if($mode == 'undefined'){
$report[]=array('label'=>'mode', 'text' => 'Please select Mode of Payment');
}	

if(empty($inst_utr)){
$report[]=array('label'=>'ins_utr', 'text' => 'Please Fill Instrument/UTR');
}	

if(empty($bank_ac)){
$report[]=array('label'=>'bank_ac', 'text' => 'Please select Bank Account');
}	

if(empty($amt)){
$report[]=array('label'=>'amt', 'text' => 'Please Fill Amount');
}	



if(empty($tds)){
$report[]=array('label'=>'tds', 'text' => 'Please select Tds Charge Persentage');
}	


if(!empty($amt))
{
if(is_numeric($amt))
{
}
else
{
$report[]=array('label'=>'amt', 'text' => 'Pleaes Fill Numeric Value');
}
}










$date4 = date("Y-m-d", strtotime($transaction_date));
$date4 = new MongoDate(strtotime($date4));



$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
if($from <= $date4 && $to >= $date4)
{
$abc = 55;
break;
}
else
{
$abc = 555; 
}
}


if(!empty($transaction_date))
{
if($abc == 555)
{
$report[]=array('label'=>'tr_dat', 'text' => 'The Date is not in Open Financial Year, Please Select another Date');
}
}

if(sizeof($report)>0)
{
$output=json_encode(array('report_type'=>'error','report'=>$report));
die($output);
}

$transaction_date2 = date("Y-m-d", strtotime($transaction_date));
//$transaction_date2 = new MongoDate(strtotime($transaction_date2));

$date = date('Y-m-d');
$current_date = new MongoDate(strtotime($date));



if($ac_group == 1)
{
$account_type = 1;
}
else if($ac_group == 2 || $ac_group == 3)
{
$account_type = 2;
}


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
"transaction_date" => $transaction_date2, "prepaired_by" => $s_user_id, 
"user_id" => $exp_party, "invoice_reference" => $invoice_ref,"narration" => $narration, "receipt_mode" => $mode,
"receipt_instruction" => $inst_utr, "account_head" => $bank_ac,  
"amount" => $amt,"society_id" => $s_society_id, "tds_id" => $tds,"account_type" => $account_type,"module_id"=>2));
$this->cash_bank->saveAll($multipleRowData);  

//////////////////// End Insert///////////////////////////////
///////////// TDS CALCULATION /////////////////////
$this->loadmodel('reference');
$conditions=array("auto_id" => 3);
$cursor4=$this->reference->find('all',array('conditions'=>$conditions));
foreach($cursor4 as $collection)
{
$tds_arr = $collection['reference']['reference'];	
}

for($r=0; $r<sizeof($tds_arr); $r++)
{
$tds_sub_arr = $tds_arr[$r];
$tds_id2 = (int)$tds_sub_arr[1];
if($tds_id2 == $tds)
{
$tds_rate = $tds_sub_arr[0];
break;
}
}

$tds_amount = (round(($tds_rate/100)*$amt));
$total_tds_amount = ($amt - $tds_amount);
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
"amount" => $amt, "amount_category_id" => 1, "module_id" => 2, "account_type" => $account_type, "account_id" => $exp_party,
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Payment"));
$this->ledger->saveAll($multipleRowData); 



$sub_account_id_a = (int)$bank_ac;
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
"amount" => $tds_amount, "amount_category_id" => 2, "module_id" => 2, "account_type" => 2, "account_id" => $sub_account_id_t,"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Payment"));
$this->ledger->saveAll($multipleRowData);

}

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>2);
$order=array('cash_bank.receipt_id'=> 'ASC');
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];	
}



$output=json_encode(array('report_type'=>'publish','report'=>'Bank Payment Voucher #'.$d_receipt_id.' is generated successfully'));
die($output);

}
///////////////////////////////////////// End Bank Payment Json /////////////////////////////////////////////////////

////////////////////////////////////////// Start Petty Cash Receipt Json/////////////////////////////////////////////
function petty_cash_receipt_json()
{
$this->layout=null;
$post_data=$this->request->data;
$this->ath();
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$account_group = (int)$post_data['ac_gr'];
$party_account = (int)$post_data['prt_ac'];
$account_head = (int)$post_data['ac_head'];
$transaction_date = $post_data['tra_dat'];
if($account_group == 2)
{
$amt = $post_data['amt'];
}
$narration = $post_data['desc'];
if($account_group == 1)
{
$amt2 = $post_data['deb'];
$bill_receipt = (int)$post_data['bill'];
}

$report = array();
if(empty($account_group)){
$report[]=array('label'=>'ac_grp', 'text' => 'Please select Account Group');
}	
	
if(empty($party_account)){
$report[]=array('label'=>'prt_ac', 'text' => 'Please select Party Account');
}	

if(empty($account_head)){
$report[]=array('label'=>'ac_head', 'text' => 'Please select Account Head');
}	

if(empty($transaction_date)){
$report[]=array('label'=>'tr_dat', 'text' => 'Please select Transaction Date');
}	
if($account_group == 2)
{
if(empty($amt)){
$report[]=array('label'=>'amt', 'text' => 'Please Fill Amount');
}	
}
if($account_group == 1)
{
if(empty($amt2)){
$report[]=array('label'=>'amt2', 'text' => 'Please Fill Amount');
}	
}
if($account_group == 2)
{
if(!empty($amt))
{
if(is_numeric($amt))
{
}
else
{
$report[]=array('label'=>'amt', 'text' => 'Pleaes Fill Numeric Value');
}
}
}
if($account_group == 1)
{
if(!empty($amt2))
{
if(is_numeric($amt2))
{
}
else
{
$report[]=array('label'=>'amt2', 'text' => 'Pleaes Fill Numeric Value');
}
}
}

$date4 = date("Y-m-d", strtotime($transaction_date));
$date4 = new MongoDate(strtotime($date4));

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
if($from <= $date4 && $to >= $date4)
{
$abc = 55;
break;
}
else
{
$abc = 555; 
}
}


if(!empty($transaction_date))
{
if($abc == 555)
{
$report[]=array('label'=>'tr_dat', 'text' => 'The Date is not in Open Financial Year, Please Select another Date');
}
}

if(sizeof($report)>0)
{
$output=json_encode(array('report_type'=>'error','report'=>$report));
die($output);
}

if($account_group == 1)
{
$amt=(int)$amt2;
}
$date = date('Y-m-d');
$current_date = new MongoDate(strtotime($date));

$transaction_date2 = date("Y-m-d", strtotime($transaction_date));
//$transaction_date2 = new MongoDate(strtotime($transaction_date2));



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
"current_date" => $current_date, "account_type" => $account_group,"transaction_date" => $transaction_date2, "user_id" => $party_account, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $amt, "society_id" => $s_society_id,"module_id"=>3,"bill_reference"=>@$bill_receipt));
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
"amount" => $amt, "amount_category_id" => 2, "module_id" => 3, "account_type" => $account_group, "account_id" => $party_account, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Receipt"));
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
"amount" => $amt, "amount_category_id" => 1, "module_id" => 3, "account_type" => 2, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Petty Cash Receipt"));
$this->ledger->saveAll($multipleRowData); 

if($account_group == 1)
{

$this->loadmodel('regular_bill');
$conditions=array("receipt_id" => $bill_receipt);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$remain_amt = (int)$collection['regular_bill']['remaining_amount'];
$arrears_amt = (int)$collection['regular_bill']['arrears_amt'];
$arrears_int = $collection['regular_bill']['accumulated_tax'];
//$total_due_amt = $collection['regular_bill']['total_due_amount'];
}
$due_amt = $remain_amt - $amt;
//$total_due_amt = $total_due_amt-$amt;
if($arrears_int <= $amt)
{
$amt = $amt-$arrears_int;
$arrears_int = 0;
}
else
{
$arrears_int = $arrears_int -$amt;
$amt = 0;
}
if($amt >= $arrears_amt)
{
$arrears_amt = 0;
}
else
{
$arrears_amt = (int)$arrears_amt - $amt;
}

if($due_amt == 0)
{
$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("remaining_amount" => $due_amt,"arrears_amt"=>$arrears_amt,"accumulated_tax"=>$arrears_int,"status" => 1),array("receipt_id" => $bill_receipt));
}
else
{
$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("remaining_amount" => $due_amt,"arrears_amt"=>$arrears_amt,"accumulated_tax"=>$arrears_int,"status" => 0),array("receipt_id" => $bill_receipt));
}
}

$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>3);
$order=array('cash_bank.receipt_id'=> 'ASC');
$cursor1=$this->cash_bank->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['cash_bank']['receipt_id'];	 
}

$output=json_encode(array('report_type'=>'publish','report'=>'Petty Cash Receipt #'.$d_receipt_id.' is generated successfully'));
die($output);

}
////////////////////////////////////////// End Petty Cash Receipt Json/////////////////////////////////////////////

////////////////////////////////////////// Start Fix Deposit Jason ////////////////////////////////////////////////
function fix_deposit_json()
{
$this->layout=null;
$post_data=$this->request->data;
$this->ath();
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$bank_name = $post_data['bnk_name'];
$branch = $post_data['branch'];
$account_ref = $post_data['ac_ref'];
$principal_amt = $post_data['pr_amt'];
$remind_day = $post_data['rmd_day'];
$remarks = $post_data['remark'];
$start_date = $post_data['st_dat'];
$mat_date = $post_data['mat_dat'];
$int_rate = $post_data['int_rate'];
//$tds_amt = $post_data['tds_amt'];
$name = $post_data['name'];
$email = $post_data['email'];
$mobile = $post_data['mobile'];

$file_name="";
if(isset($_FILES['file'])){
$file_name=$_FILES['file']['name'];
$file_size=$_FILES['file']['size'];
$file_tmp_name=$_FILES['file']['tmp_name'];
$file_type=$_FILES['file']['type'];
}


$report = array();
if(empty($bank_name)){
$report[]=array('label'=>'bnk', 'text' => 'Please Fill Bank Name');
}	



if(empty($branch)){
$report[]=array('label'=>'brch', 'text' => 'Please Fill Branch of the Bank');
}	



if(empty($account_ref)){
$report[]=array('label'=>'acref', 'text' => 'Please Fill Account Reference');
}	



if(empty($principal_amt)){
$report[]=array('label'=>'pramt', 'text' => 'Please Fill Principal Amount');
}	



if(empty($remind_day)){
$report[]=array('label'=>'remday', 'text' => 'Please Reminder Days');
}	




if(empty($start_date)){
$report[]=array('label'=>'stdat', 'text' => 'Please select Start Date');
}	



if(empty($mat_date)){
$report[]=array('label'=>'matdat', 'text' => 'Please select Maturity Date');
}	



if(empty($int_rate)){
$report[]=array('label'=>'inrat', 'text' => 'Please Fill Interest Rate');
}	



//if(empty($tds_amt)){
//$report[]=array('label'=>'tds', 'text' => 'Please Fill Tds Amount');
//}	




if(!empty($principal_amt))
{
if(is_numeric($principal_amt))
{
}
else
{
$report[]=array('label'=>'pramt', 'text' => 'Please Fill Numeric Value');
}	
}

if(!empty($remind_day))
{
if(is_numeric($remind_day))
{
}
else
{
$report[]=array('label'=>'remday', 'text' => 'Please Fill Numeric Value');
}	
}

if(!empty($int_rate))
{
if(is_numeric($int_rate))
{
}
else
{
$report[]=array('label'=>'inrat', 'text' => 'Please Fill Numeric Value');
}	
}
/*
if(!empty($tds_amt))
{
if(is_numeric($tds_amt))
{
}
else
{
$report[]=array('label'=>'tds', 'text' => 'Please Fill Numeric Value');
}	
}
*/
$date4 = date("Y-m-d", strtotime($start_date));
$date4 = new MongoDate(strtotime($date4));



$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
if($from <= $date4 && $to >= $date4)
{
$abc = 55;
break;
}
else
{
$abc = 555; 
}
}

if(!empty($start_date))
{
if($abc == 555)
{
$report[]=array('label'=>'stdat', 'text' => 'The Date is not in Open Financial Year, Please Select another Date');
}
}

if(sizeof($report)>0)
{
$output=json_encode(array('report_type'=>'error','report'=>$report));
die($output);
}

$start_date2 = date("Y-m-d", strtotime($start_date));
$start_date2 = new MongoDate(strtotime($start_date2));

$mat_date2 = date("Y-m-d", strtotime($mat_date));
$mat_date2 = new MongoDate(strtotime($mat_date2));

$current_date = date("Y-m-d");
$current_date = new MongoDate(strtotime($current_date));

if(isset($_FILES['file'])){
$target = "fix_deposit/";
$target=@$target.basename($file_name);
move_uploaded_file($file_tmp_name,@$target);
}

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
$i=1000;
}	
else
{	
$i=$last11;
}
$i++; 
$this->loadmodel('fix_deposit');
$multipleRowData = Array( Array("auto_id" => $i, "bank_name" => $bank_name,  "branch" => $branch, "account_reference" => $account_ref, "prepaired_by" => $s_user_id, 
"principal_amount" => $principal_amt, "start_date" => $start_date2,"maturity_date" => $mat_date2, "interest_rate" => $int_rate,"remark" => $remarks, "reminder" => $remind_day,"name" => $name, "society_id" => $s_society_id, "email" => $email,"mobile" => $mobile, "current_date"=>$current_date,"file_name"=>$file_name,"status"=>0));
$this->fix_deposit->saveAll($multipleRowData);

$output=json_encode(array('report_type'=>'publish','report'=>'Record Inserted Successfully'));
die($output);

}
////////////////////////////////////////// End Fix Deposit Jason ////////////////////////////////////////////////
///////////////////////// Start Matured Deposit View ////////////////////////////////////////////////
function matured_deposit_view()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();


$s_society_id=(int)$this->Session->read('society_id');

}
///////////////////////////////// End Matured Deposit View /////////////////////////////////////////////////

////////////////////////////////// Start Fix Deposit view (Active) Excel///////////////////////////////////////////
function fix_deposit_excel()
{
$this->layout="";
$filename="Fix Deposit Excel";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	

//$from = $this->request->query('f');
//$to = $this->request->query('t');

$excel="<table border='1'>
<tr>
<th>Sr #</th>
<th>Bank Name</th>
<th>Branch</th>
<th>Name</th>
<th>E-mail</th>
<th>Mobile</th>
<th>A/c Reference</th>
<th>Principal Amount</th>
<th>Start Date</th>
<th>Maturity Date</th>
<th>Interest Amount</th>
<th>Interest Rate</th>
<th>Maturity Amount</th>
<th>Transaction Id</th>
<th>Remark</th>
</tr>";
$n=0;
$principal_tt = 0;
$int_tt = 0;
$mat_tt = 0;
$this->loadmodel('fix_deposit');
$conditions=array("society_id" => $s_society_id,"status"=>0);
$cursor1 = $this->fix_deposit->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['fix_deposit']['auto_id'];	
$bank_name = $collection['fix_deposit']['bank_name'];	
$branch = $collection['fix_deposit']['branch'];	
$account_ref = $collection['fix_deposit']['account_reference'];	
$prepaired_by = $collection['fix_deposit']['prepaired_by'];	
$principal_amt = $collection['fix_deposit']['principal_amount'];
$start_date = $collection['fix_deposit']['start_date'];
$maturity_date = $collection['fix_deposit']['maturity_date'];
$interest_rate = $collection['fix_deposit']['interest_rate'];
$remark = $collection['fix_deposit']['remark'];
$reminder = $collection['fix_deposit']['reminder'];
$name = $collection['fix_deposit']['name'];
$email = $collection['fix_deposit']['email'];
$mobile = $collection['fix_deposit']['mobile'];
$file_name = $collection['fix_deposit']['file_name'];

$n++;

$start_date2 = date('d-M-Y',$start_date->sec);
$maturity_date2 = date('d-M-Y',$maturity_date->sec);

function dateDiff($d1, $d2)
{
return round(abs(strtotime($d1)-strtotime($d2))/86400);
}
 
$days = dateDiff($start_date2,$maturity_date2);

$interest = round(($principal_amt * $interest_rate *($days/365))/100);

$mat_amt = $principal_amt + $interest;

$principal_tt = $principal_tt + $principal_amt;
$int_tt = $int_tt + $interest;
$mat_tt = $mat_tt + $mat_amt;

$excel.="
<tr>
<td>$n</td>
<td>$bank_name</td>
<td>$branch</td>
<td>$name</td>
<td>$email</td>
<td>$mobile</td>
<td>$account_ref</td>
<td>$principal_amt</td>
<td>$start_date2</td>
<td>$maturity_date2</td>
<td>$interest_rate</td>
<td>$interest</td>
<td>$mat_amt</td>
<td>$auto_id</td>
<td>$remark</td>
</tr>";
}
$excel.="
<tr>
<th colspan='7' style='text-align:right;'>Total</th>
<th style='text-align:center;'>$principal_tt</th>
<th colspan='3'></th>
<th>$int_tt</th>
<th>$mat_tt</th>
<th colspan='2'></th>
</tr>";
$excel.="</table>";

echo $excel;
}
////////////////////////////////// Start Fix Deposit view (Active) Excel///////////////////////////////////////////
/////////////////////////////////// Start Edit PCP //////////////////////////////////////////////////////////////
function edit_pcp($rr_id)
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	




$this->loadmodel('cash_bank');
$conditions=array("society_id" => $s_society_id,"module_id"=>4,"receipt_id"=>$rr_id);
$cursor1 = $this->cash_bank->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
/////////////////////////////////// End Edit PCP //////////////////////////////////////////////////////////////
/////////////////////////////////// Start bank_receipt_import ////////////////////////////////////////////////////////
function bank_receipt_import()
{
$this->layout="";
$filename="Bank Receipt Import";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . "GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".csv");
header ("Content-Description: Generated Report" );

$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$excel = "Transaction Date,Receipt Mode,Cheque No.,Reference/UTR,Date,Deposited In Which Bank?,Received from(member/non-member),User Name,Wing,Flat,Receipt For(Maintenance/Other),Party Name,Bill Reference,Amount,Narration";


echo $excel;
}
/////////////////////////////// End bank_receipt_import ////////////////////////////////////////////////////////////

////////////////////////////// Start bank_receipt_import_ajax //////////////////////////////////////////////////////////
function bank_receipt_import_ajax()
{
$this->layout="blank";
$this->ath();

$s_society_id= (int)$this->Session->read('society_id');
/*
if(isset($_FILES['ffff'])){
$file_name=$_FILES['ffff']['name'];
$file_tmp_name =$_FILES['ffff']['tmp_name'];
$target = "csv_file/bank/";
$target=@$target.basename($file_name);
move_uploaded_file($file_tmp_name,@$target);

$f = fopen('csv_file/bank/'.$file_name,'r') or die("ERROR OPENING DATA");
$batchcount=0;
$records=0;
while (($line = fgetcsv($f, 4096, ';')) !== false) {
// skip first record and empty ones
$numcols = count($line);
$test[]=$line;
++$records;
}
fclose($f);
$records;
}
*/

if(isset($_FILES['file'])){
$file_name=$_FILES['file']['name'];
$file_tmp_name =$_FILES['file']['tmp_name'];
$target = "csv_file/bank/";
$target=@$target.basename($file_name);
move_uploaded_file($file_tmp_name,@$target);

$f = fopen('csv_file/bank/'.$file_name, 'r') or die("ERROR OPENING DATA");
$batchcount=0;
$records=0;
while (($line = fgetcsv($f, 4096, ';')) !== false) {
// skip first record and empty ones
$numcols = count($line);
$test[]=$line;
++$records;
}
fclose($f);
$records;
}
$i=0;
foreach($test as $child)
{
if($i>0)
{
$child_ex=explode(',',$child[0]);
/////////////////////////////////////////////////////
$TransactionDate = $child_ex[0];
$ReceiptMod = $child_ex[1];
$ChequeNo = $child_ex[2];
$Reference = $child_ex[3];
$DrawnBankname = $child_ex[4];
$Deposited = $child_ex[5];
$Date1 = $child_ex[6];
$MemberName = $child_ex[7];
$Wing = $child_ex[8];
$Flat = $child_ex[9];
$Amount = $child_ex[10];	  
////////////////////////////////////////////////////////////


$this->loadmodel('wing'); 
$conditions=array("wing_name"=> new MongoRegex('/^' . $Wing . '$/i'),"society_id"=>$s_society_id);
$result_ac=$this->wing->find('all',array('conditions'=>$conditions));
foreach($result_ac as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];
}

$this->loadmodel('flat'); 
$conditions=array("flat_name"=> new MongoRegex('/^' . $Flat . '$/i'), "society_id"=>$s_society_id);
$result_ac=$this->flat->find('all',array('conditions'=>$conditions));
foreach($result_ac as $collection)
{
$flat_id = (int)$collection['flat']['flat_id'];
}

 
$this->loadmodel('ledger_sub_account'); 
$conditions=array("name"=> new MongoRegex('/^' . $MemberName . '$/i'),"society_id"=>$s_society_id,"ledger_id"=>34);
$result_ac=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($result_ac as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
$hhhhhh = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($hhhhhh as $fff)
{
$wing = (int)$fff['user']['wing'];
$flat = (int)$fff['user']['flat'];
}
if($wing_id == $wing && $flat_id == $flat)
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
}
} 
 
$this->loadmodel('ledger_sub_account'); 
$conditions=array("name"=> new MongoRegex('/^' . $Deposited . '$/i'),"society_id"=>$s_society_id);
$result_ac=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($result_ac as $collection)
{
$bank_id = (int)$collection['ledger_sub_account']['auto_id'];
}
/*
$auto_id = "";
$this->loadmodel('ledger_account'); 
$conditions=array("ledger_name"=> new MongoRegex('/^' .  $ac_name . '$/i'),"group_id"=>$group_id);
$result_ac=$this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($result_ac as $collection)
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$ledger_type = 2;
}
$this->loadmodel('ledger_sub_account'); 
$conditions=array("name"=> new MongoRegex('/^' .  $ac_name . '$/i'),"ledger_id"=>$group_id);
$result_sac2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($result_sac2 as $collection2)
{
$auto_id = (int)$collection2['ledger_sub_account']['auto_id'];
if($group_id == 34)
{
////////////////
$wing_name = $child_ex[2];
$flat_name = $child_ex[3];

$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$wing_id2 = (int)$collection['flat']['wing_id'];
$flat_name2 = $collection['flat']['flat_name'];
$flat_id2 = (int)$collection['flat']['flat_id'];
if($flat_name2 == $flat_name)
{ 
$wing = (int)$wing_id2;
$flat = (int)$flat_id2;
}
}
///////////////
$user_id = (int)$collection2['ledger_sub_account']['user_id'];

$hhhhhh = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($hhhhhh as $fff)
{
$wing_id = (int)$fff['user']['wing'];
$flat_id = (int)$fff['user']['flat'];
}
}
if($flat_id == $flat)
break;
$ledger_type = 1;
}
*/
$table[] = array(@$TransactionDate,@$ReceiptMod,@$ChequeNo,@$Reference,@$DrawnBankname,@$bank_id,@$Date1,@$auto_id,@$Amount);
} 
$i++;
}
$this->set('aaa',$table);

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,"ledger_id"=>33);
$cursor1 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,"ledger_id"=>34);
$cursor2 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
////////////////////////////// End bank_receipt_import_ajax //////////////////////////////////////////////////////////
///////////////////////////////// Start Save bank Imp ///////////////////////////////////////////////////////////////
function save_bank_imp()
{
$this->layout='blank';
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');
	
$q=$this->request->query('q'); 
$myArray = json_decode($q, true);

$r=1;
foreach($myArray as $child)
{
$r++;
$TransactionDate = $child[0];
$ReceiptMod = $child[1];
//$ChequeNo = $child[2];
//$DrawnBankname = $child[4];
//$Date1 = $child[6];
$bank_id = $child[4];
$auto_id = $child[6];
$Amount = $child[7];

if(empty($TransactionDate))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Transaction Date in row'.$r));
die($output);
}

if(empty($ReceiptMod))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Receipt Mode in row'.$r));
die($output);
}
$c = (int)strcasecmp("Cheque",$ReceiptMod);
$n = (int)strcasecmp("NEFT",$ReceiptMod);
$p = (int)strcasecmp("PG",$ReceiptMod);
if($c == 0)
{
$ChequeNo = $child[2];
$DrawnBankname = $child[3];
$Date1 = $child[8];	

if(empty($ChequeNo))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Cheque Number in row'.$r));
die($output);
}

if(empty($DrawnBankname))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Drawn Bank name Receipt Mode in row'.$r));
die($output);
}

if(empty($Date1))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Date in row'.'30'));
die($output);
}
}
else if($n == 0)
{
//$Reference = $child[3];
$Date1 = $child[4];

//if(empty($Reference))
//{
//$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Reference in row'.$r));
//die($output);
//}

if(empty($Date1))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Date in row'.$r));
die($output);
}

}
else if($p == 0)
{
//$Reference = $child[3];
$Date1 = $child[4];	

//if(empty($Reference))
//{
//$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Reference in row'.$r));
//die($output);
//}
if(empty($Date1))
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill Date in row'.$r));
die($output);
}
}
else
{
$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill "Cheque", "NEFT" or PG in Receipt Mode in row'.$r));
die($output);
}
}

foreach($myArray as $child)
{
$current_date = date('Y-m-d');
$TransactionDate = $child[0];
$ReceiptMod = $child[1];
//$ChequeNo = $child[2];
//$Reference = $child[3];
//$DrawnBankname = $child[4];
//$Date1 = $child[6];
$bank_id = (int)$child[6];
$auto_id77 = (int)$child[7];
$Amount = $child[8];
$c = (int)strcasecmp("Cheque",$ReceiptMod);
$n = (int)strcasecmp("NEFT",$ReceiptMod);
$p = (int)strcasecmp("PG",$ReceiptMod);
if($c == 0)
{
$ChequeNo = $child[2];
$DrawnBankname = $child[4];
$w = $child[5];
}
else if($n == 0)
{
$Reference = $child[3];
$w = $child[5];
}
else if($p == 0)
{
$Reference = $child[3];
$w = $child[5];	
}

$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $auto_id77);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}

$result_rb = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill'),array('pass'=>array(@$user_id)));
foreach ($result_rb as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];
}

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

$output=json_encode(array('report_type'=>'validation','text'=>'Please Fill "Cheque", "NEFT" or PG in Receipt Mode in row'.'555'));
die($output);

$this->loadmodel('cash_bank');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date"=>$TransactionDate,"prepaired_by" => $s_user_id,"user_id" => $auto_id77,"bill_reference"=>$bill_no,"receipt_mode" => $ReceiptMod,"receipt_instruction" => $receipt_instruction,"account_head"=>$bank_id,"amount" => $Amount,"amount_category_id" => 1, "society_id" => $s_society_id,"member" =>1,"module_id"=>1,"cheque_number"=>$ChequeNo,"reference_number"=>$Reference,"reference_number"=>$Reference,"which_bank"=>$DrawnBankname,"cheque_date"=>$w,"receipt_for_type"=>1));
$this->cash_bank->saveAll($multipleRowData);  


$trns_id=(int)$auto;
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
"amount" => $amount, "amount_category_id" => 2, "module_id" => 1, "account_type" => 1,  "account_id" => $auto_id77, 
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 


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
"amount" => $amount, "amount_category_id" => 1, "module_id" => 1, "account_type" => 1, "account_id" => $bank_id,
"current_date" => $current_date, "society_id" => $s_society_id,"table_name"=>"cash_bank","module_name"=>"Bank Receipt"));
$this->ledger->saveAll($multipleRowData); 


$this->loadmodel('regular_bill');
$conditions=array("receipt_id" => $bill_no,"society_id"=>$s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$remain_amt = $collection['regular_bill']['remaining_amount'];
$arrears_amt = (int)$collection['regular_bill']['arrears_amt'];
$arrears_int = $collection['regular_bill']['accumulated_tax'];
$total_due_amt = $collection['regular_bill']['total_due_amount'];
}
$due_amt = $remain_amt - $amount;
@$total_due_amt = $total_due_amt - $amount;
if($arrears_int <= $amount)
{
$amount = $amount-$arrears_int;
$arrears_int = 0;
}
else
{
$arrears_int = $arrears_int -$amount;
$amount = 0;
}

if($amount >= $arrears_amt)
{
$arrears_amt = (int)$arrears_amt - $amount;
}
else
{
$arrears_amt = (int)$arrears_amt - $amount;
}

$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("remaining_amount" => $due_amt,"arrears_amt"=>$arrears_amt,"accumulated_tax"=>$arrears_int,"total_due_amount"=>$total_due_amt),array("receipt_id" => $bill_no));

}
$output=json_encode(array('report_type'=>'done','text'=>'Please Fill Date in row'.$n));
die($output);
}
///////////////////////////////// End Save bank Imp ///////////////////////////////////////////////////////////////
}
?>