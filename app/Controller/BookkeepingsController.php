<?php
App::import('Controller','Hms');
class BookkeepingsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);
var $name = 'Bookkeepings';
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


/////////////////////////////// Start Journal Add (Accounts)///////////////////
function journal_add()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}
	
$this->ath();
$this->check_user_privilages();
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

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


$this->loadmodel('journal');
$conditions=array("society_id" => $s_society_id);
$order=array('journal.receipt_id'=> 'DESC');
$cursor=$this->journal->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['journal']['receipt_id'];
}
if(empty($last))
{
$zz= 0;
}	
else
{	
$zz=$last;
}
$this->set('zz',$zz);   

$this->loadmodel('amount_category');
$cursor1=$this->amount_category->find('all');
$this->set('cursor1',$cursor1);


$m = new MongoClient();
$collection = $m->selectCollection('accounts', 'ledger_account');
$cursor = $collection->find();

$this->loadmodel('ledger_account');
$cursor2=$this->ledger_account->find('all');
$this->set('cursor2',$cursor2);

if(isset($this->request->data['journal_add']))
{
date_default_timezone_set('Asia/Calcutta');	
$time = date('h-i-s A', time());	
$date = $this->request->data['date'];	
$date = date("Y-m-d", strtotime($date));
$date = new MongoDate(strtotime($date));	



$this->loadmodel('journal');
$conditions=array("society_id" => $s_society_id);
$order=array('journal.receipt_id'=>'DESC');
$cursor=$this->journal->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$receipt_no = $collection['journal']['receipt_id']; 
}
if(!empty($receipt_no))
{
$receipt_no++;
}	
else
{	
$receipt_no = 1001;
}

$xyz = $this->request->data['xyz'];		

for($i=1; $i<=$xyz; $i++)
{
$remark=$this->request->data['remark'.$i];	
$l_type_id=(int)$this->request->data['l_type_id'.$i];	
$l_type_name_id=(int)@$this->request->data['l_type_name'.$i];	
//$amount_category_id=(int)$this->request->data['amount_cat_id'.$i];
$debit = $this->request->data['debit'.$i];
$credit = $this->request->data['credit'.$i];
$current_date = date("d-m-Y");
if(empty($debit) && !empty($credit))
{ 
$amount_category_id = 2;
$amount = $credit;
}
if(empty($credit) && !empty($debit))
{
$amount_category_id = 1;
$amount = $debit;	
}




$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));

if($l_type_id == 34 || $l_type_id == 15 || $l_type_id == 33 || $l_type_id == 35)
{ 
$this->loadmodel('journal');
$order=array('journal.auto_id'=> 'DESC');
$cursor=$this->journal->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['journal']["auto_id"];
}
if(empty($last))
{
$ii=0;
}	
else
{	
$ii=$last;
}
$ii++;
$this->loadmodel('journal');
$multipleRowData = Array( Array("auto_id" => $ii, "receipt_id" => $receipt_no, "account_type" => 1, 
"ledger_type_id" => $l_type_name_id,"user_id" => $l_type_name_id, "transaction_date" => $date, 
"current_date" => $current_date, "amount" => $amount, "amount_category_id" => $amount_category_id, "remark" => $remark ,
"society_id" => $s_society_id,"approver" => $s_user_id, "time" =>$time));
$this->journal->saveAll($multipleRowData);
}
else
{
$this->loadmodel('journal');
$order=array('journal.auto_id'=> 'DESC');
$cursor=$this->journal->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['journal']["auto_id"];
}
if(empty($last))
{
$ii=0;
}	
else
{	
$ii=$last;
}
$ii++;
$this->loadmodel('journal');
$multipleRowData = Array( Array("auto_id" => $ii, "receipt_id" => $receipt_no, "account_type" => 2, "ledger_type_id" => $l_type_id,
"user_id" => $l_type_id, "transaction_date" => $date, "current_date" => $current_date, "amount" => $amount, 
"amount_category_id" => $amount_category_id, "remark" => $remark , "society_id" => $s_society_id,"approver" => $s_user_id,"time" => $time));
$this->journal->saveAll($multipleRowData);

}
if($l_type_id == 34 || $l_type_id == 15 || $l_type_id == 33 || $l_type_id == 35)
{

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']["auto_id"];
}
if(empty($last))
{
$k=0;
}	
else
{	
$k=$last;
}
$k++;
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $receipt_no, 
"amount" => $amount, "amount_category_id" => $amount_category_id, "table_name" => "journal", "account_type" => 1, "account_id" => $l_type_name_id,
"current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Journal"));
$this->ledger->saveAll($multipleRowData);					
}
else
{

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']["auto_id"];
}
if(empty($last))
{
$k=0;
}	
else
{	
$k=$last;
}
$k++;
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $receipt_no, 
"amount" => $amount, "amount_category_id" => $amount_category_id, "table_name" => "journal",  "account_type" => 2, 
"account_id" => $l_type_id, "current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Journal"));
$this->ledger->saveAll($multipleRowData);	
}

}

?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Journal</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Journal No. #<?php echo $receipt_no; ?> is  generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="journal_view" class="btn blue">OK</a>
</div>
</div>


<?php
}
}

///////////////////////////// End Journal Add (Accounts)////////////////////////////

////////////////////////////// Start Journal Excel /////////////////////////////////
function journal_excel()
{
$this->layout="";
$filename="Journal";
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
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor =$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$excel="<table border='1'>
<tr>
<th colspan='6' style='text-align:center;'>
Journal Report  ($society_name)
</th>
</tr>
<tr>
<th>From : $from</th>
<th>To : $to</th>
<th colspan='4'></th>
</tr>
 
<tr>
<th>Journal #</th>
<th>Transaction Date</th>
<th>Ledger A/c</th>
<th>Remarks</th>
<th>Debit</th>
<th>Credit</th>
</tr>";
$total_debit = 0;
$total_credit = 0;
$this->loadmodel('journal');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->journal->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$auto_id = (int)$collection['journal']['auto_id'];
$receipt_no = $collection['journal']['receipt_id']; 
$user_id = (int)$collection['journal']['user_id'];
$date = $collection['journal']['transaction_date'];
$amount = $collection['journal']['amount'];
$amount_category_id = (int)$collection['journal']['amount_category_id'];
$remark = $collection['journal']['remark'];                                     
$account_type = (int)$collection['journal']['account_type']; 
$ledger_type_id = (int)$collection['journal']['ledger_type_id'];
$approver = (int)$collection['journal']['approver'];
$current_date = $collection['journal']['current_date'];
$creation_date = date('d-m-Y',$current_date->sec);

$resultacc = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($approver)));
foreach($resultacc as $collection)
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
if($account_type == 2)
{
$result_la = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($ledger_type_id)));
foreach ($result_la as $collection) 
{
$user_name = $collection['ledger_account']['ledger_name']; 
}
}	
if($date >= $m_from && $date <= $m_to)
{
$date2 = date('d-m-Y',$date->sec);  
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;  
}
$excel.="<tr>
		<td>$receipt_no</td>
		<td>$date2</td>
		<td>$user_name</td>
		<td>$remark</td>
		<td>";
		if($amount_category_id == 1) {
		$excel.="$amount";
		} else { 
		$excel.="-";
		} 
		$excel.="</td>
		<td>";
		if($amount_category_id == 2) {
		$excel.="$amount"; } else { 
		$excel.="-"; } 
		$excel.="</td>
         </tr>";
}}

$excel.="<tr>
			<th colspan='4'>Total</th>
			<th>$total_debit</th>
			<th>$total_credit</th>
			</tr>
			</table>";	




echo $excel;



}
//////////////////////////// End Journal Excel ///////////////////////////////////

//////////////////////////////////////////////// Start Journal View (Accounts) //////////////////////////////////////////////////////////////////////////
function journal_view()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);



}

//////////////////////////////////////////////// End Journal View (Accounts) //////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////// Start Journal View Ajax(Accounts)///////////////////////////////////////////////////////////////////////
function journal_view_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('journal');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->journal->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];	
}
$this->set('society_name',$society_name);

}

/////////////////////////////////////End Journal View Ajax(Accounts)/////////////////////////////

///////////////////////////////////////////// Start Journal Pdf (Accoints)//////////////////////////////////////////////////////////////////////////////
function journal_pdf()
{
$this->layout = 'pdf';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		

$auto_id = (int)$this->request->query('c');	
$this->set('auto_id',$auto_id);	



$this->loadmodel('journal');
$conditions=array("auto_id" => $auto_id);
$cursor1=$this->journal->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}


///////////////////////////////////////////// End Journal Pdf (Accoints)//////////////////////////////////////////////////////////////////////////////

//////////////////////////////// Start Journal Add Row (Accounts)///////////////////////////////
function journal_add_row()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$t = $this->request->query('con');
$this->set('t',$t);

$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);


$this->loadmodel('amount_category');
$cursor2=$this->amount_category->find('all');
$this->set('cursor2',$cursor2);

}
/////////////////////////////////// End Journal Add Row (Accounts)/////////////////

//////////////////////////////////////////////////////////// Start Show Ledger Type Journal(Accounts) ///////////////////////////////////////////////////
function show_ledger_type()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_society_id',$s_society_id);

$value =(int)$this->request->query('c1');
$t = $this->request->query('t');

$this->set('value',$value);
$this->set('t',$t);


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => $value,"society_id" => $s_society_id);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
//////////////////////////////////////////////////////////// End Show Ledger Type Journal(Accounts) ///////////////////////////////////////////////////

////////////////////////// Start Ledger (Accounts)//////////////////////////////////////////////////////////////////
function ledger()
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

$m = new MongoClient();
$collection = $m->selectCollection('accounts', 'ledger_account');
$cursor = $collection->find();

$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);


$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->flat->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
/////////////////////////////////////////// End Ledger (Accounts)//////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Ledger Ajax (Accounts) //////////////////////////////////////////////////////////////////////////////
function ledger_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$get_id = (int)$this->request->query('c1');
$this->set('get_id',$get_id);

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,'ledger_id' => $get_id);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
////////////////////////////// End Ledger Ajax (Accounts) ////////////////////////////////////////////////////////

//////////////////////////// Start Ledger Excel (Accounts)//////////////////////////////////////////////////////
function ledger_excel()
{
$this->layout=null;
$filename="Ledger Report";
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$from = $this->request->query('f');
$to = $this->request->query('t');
$main_id = (int)$this->request->query('m');
$sub_id = (int)$this->request->query('s');

$m_from = date("Y-m-d", strtotime($from));
//$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

if($main_id == 34 || $main_id == 15 || $main_id == 33 || $main_id == 35)
{
$excel = "<table border='1'>";
$cursor1 = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
foreach ($cursor1 as $collection) 
{
$ledger_type_name = $collection['ledger_account']['ledger_name'];	
}
$cursor2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($sub_id)));	
foreach ($cursor2 as $collection) 
{
$user_name = $collection['ledger_sub_account']['name'];	
}
$excel.="									
<tr>
<th colspan = '6' style='text-align:center;'>
$society_name
</th>
</tr>
<tr>
<th colspan = '6' style='text-align:center;'>
Transaction for The Period $from to $to
</th>
</tr>
<tr>
<th>$user_name  A/c</th>
<th>Grouping : $ledger_type_name</th>
<th colspan='4'></th>
</tr>";

$close = 0;	
$opening_balance = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = @$collection['ledger']['receipt_id']; 
$amount_o = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
$op_date = @$collection['ledger']['op_date'];
if($receipt_id != 'O_B')
{
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)@$collection['ledger']['module_id'];
}
}

$op_im_deb = 0;
$op_im_cre = 0;
if($receipt_id == 'O_B')
{
if($sub_account_id == $sub_id)
{
if($account_type == 1)
{
if($amount_category_id == 1)
{
$op_im_deb = $amount_o; 
}
else
{
$op_im_cre = $amount_o; 	 
}
}
}
}

if($receipt_id != 'O_B')
{
if($table_name == "cash_bank")
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));
}
else
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}

foreach ($module_date_fetch as $collection) 
{
$date1 = @$collection[$table_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$table_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
$remark = @$collection[$table_name]['remark'];
}


if($sub_account_id == $sub_id)
{
if(@$date1 < $m_from)
{
if($account_type == 1)
{
if($amount_category_id == 1)
{
$opening_balance = $opening_balance - $amount_o;
}
else if($amount_category_id == 2)
{
$opening_balance = $opening_balance + $amount_o;	
}
}
}
}
}

if($op_date < $m_from)
{
$opening_balance = $opening_balance + $op_im_cre - $op_im_deb;
}
else
{
$close	= $close + $op_im_cre - $op_im_deb;
}
}

$excel.="
<tr>
<th colspan='3'></th>
<th colspan='2'>Opening Balance:</th>
<th>";
$op_bal2 = $opening_balance;
if($opening_balance > 0)
{
$opening_balance = $opening_balance.'&nbsp;&nbsp;Cr';
}
else if($opening_balance < 0)
{
$opening_balance = abs($opening_balance);
$opening_balance = $opening_balance.'&nbsp;&nbsp;Dr';
}
$excel.="$opening_balance</th>
</tr>";
$balance = $opening_balance;

$excel.="<tr>
<th>Transaction Date</th>
<th>Narration</th>
<th>Source</th>
<th>Reference #</th>
<th>Debit</th>
<th>Credit</th>
</tr>";

$total_debit = 0;
$total_credit = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = @$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
if($receipt_id != 'O_B')
{
$table_name = $collection['ledger']['table_name'];
$module_name = $collection['ledger']['module_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}
}
if($receipt_id == 'O_B')
continue;

if($table_name == "cash_bank")
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));
}
else
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}
foreach ($module_date_fetch2 as $collection) 
{
$date = @$collection[$table_name]['transaction_date'];
if(empty($date))
{
$date = @$collection[$table_name]['posting_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['purchase_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
if(empty($narration))
{
$narration = @$collection[$table_name]['remark'];
}
if(empty($narration))
{
$narration = @$collection[$table_name]['description'];	
}
$remark = @$collection[$table_name]['remark'];
}


if($sub_account_id == $sub_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 1)
{
$date = date('d-m-Y',strtotime($date));	

$excel.="<tr>
<td>$date</td>
<td>$narration</td>
<td>$module_name</td>
<td>$receipt_id</td>
<td>";
if($amount_category_id == 1) { $balance = $balance - $amount; 
$excel.="$amount"; } else { 
$excel.="-"; 
} 
$excel.="</td>";

$excel.="<td>";
if($amount_category_id == 2) { $balance = $balance + $amount;   
$excel.="$amount"; 

} else { 
$excel.="-"; 
} 
$excel.="</td>
</tr>";
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}
$closing_balance = $op_bal2 - $total_debit + $total_credit + ($close);
}}}}
$excel.="
<tr>
<th colspan='4' style='text-align:right;'><b> Total </b></th>
<th>$total_debit</th>
<th>$total_credit</th>
</tr>
<tr>
<th style='text-align:center;'>Opening Balance</th>
<th colspan='' style='text-align:center;'>
Total Debits
</th>
<th style='text-align:center;'>Total credits</th>
<th colspan='3' style='text-align:center;'>
Closing balance
</th>
</tr>";

$excel.="<tr>
<th style='text-align:center;'>";
if($opening_balance > 0) 
{ 
$opening_balance = $opening_balance;
} 
else if($opening_balance < 0)
{
$opening_balance = abs($opening_balance);
$opening_balance = $opening_balance.'Dr';
}
$excel.="$opening_balance</th>
<th colspan='' style='text-align:center;'>$total_debit</th>
<th style='text-align:center;'>$total_credit</th>
<th colspan='3' style='text-align:center;'>";
if($closing_balance > 0) 
{ 
$closing_balance = $closing_balance.'&nbsp;&nbsp;Cr';  
}
else if($closing_balance < 0)
{ 										
$closing_balance = abs($closing_balance);
$closing_balance = $closing_balance.'&nbsp;&nbsp;Dr';
}
$excel.="$closing_balance</th>
</tr>
</table>";

}
else
{
$excel = "<table border='1'>";

$ledger_account_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
foreach ($ledger_account_fetch as $collection) 
{
$group_id = (int)$collection['ledger_account']['group_id'];
$user_name = $collection['ledger_account']['ledger_name'];	
}

$accounts_group = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($group_id)));
foreach ($accounts_group as $collection) 
{
$ledger_type_name = $collection['accounts_group']['group_name'];	
}

$excel.="<tr>
<th colspan = '6' style='text-align:center;'>
$society_name
</th>
</tr>
<tr>
<th colspan = '6' style='text-align:center;'>
Transaction for The Period $from to $to
</th>
</tr>
<tr>
<th>$user_name A/c</th>
<th>Grouping : $ledger_type_name </th>
<th colspan='4'></th>
</tr>";
$close = 0;
$opening_balance = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = @$collection['ledger']['receipt_id']; 
$amount_o = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
$op_date = @$collection['ledger']['op_date'];
if($receipt_id != 'O_B')
{
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}
}

$op_im_deb = 0;
$op_im_cre = 0;
if($receipt_id == 'O_B')
{
if($sub_account_id == $main_id)
{
if($account_type == 2)
{
if($amount_category_id == 1)
{
$op_im_deb = $amount_o; 
}
else
{
$op_im_cre =  $amount_o; 	 
}
}
}
}


if($receipt_id != 'O_B')
{
if($table_name == "cash_bank")
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));
}
else
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}   
foreach ($module_date_fetch3 as $collection) 
{
$date1 = @$collection[$table_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$table_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
$remark = @$collection[$table_name]['remark'];
}


if($sub_account_id == $main_id)
{
if(@$date1 < $m_from)
{
if($account_type == 2)
{
if($amount_category_id == 1)
{
$opening_balance = $opening_balance - $amount_o;
}
else if($amount_category_id == 2)
{
$opening_balance = $opening_balance + $amount_o;	
}
}
}
}
}

if($op_date < $m_from)
{
$opening_balance = $opening_balance + $op_im_cre - $op_im_deb;
}
else
{
$close = $close + $op_im_cre - $op_im_deb;
}
} 
$excel.="<tr>
<th colspan='3'></th>
<th colspan='2'>Opening Balance:</th>
<th>";

$op_bal2 = $opening_balance;
if($opening_balance > 0)
{
$opening_balance = $opening_balance.'&nbsp;&nbsp;Cr';
}
else if($opening_balance < 0)
{
$opening_balance = abs($opening_balance);
$opening_balance = $opening_balance.'&nbsp;&nbsp;Dr';
}
$excel.="$opening_balance</th>
</tr>";

$balance = $opening_balance;
$excel.="<tr>
<th>Transaction Date</th>
<th>Narration</th>
<th>Source</th>
<th>Reference #</th>
<th>Debit</th>
<th>Credit</th>
</tr>";	


$total_debit = 0;
$total_credit = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = @$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
if($receipt_id != 'O_B')
{ 
$module_name2 = $collection['ledger']['module_name'];
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}
}
if($receipt_id == 'O_B')
continue;

if($table_name == "cash_bank")
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));   
}
else
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));   
}
foreach ($module_date_fetch4 as $collection) 
{
$date = @$collection[$table_name]['transaction_date'];
if(empty($date))
{
$date = @$collection[$table_name]['posting_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['purchase_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
if(empty($narration))
{
$narration = @$collection[$table_name]['remark'];
}
if(empty($narration))
{
$narration = @$collection[$table_name]['description'];	
}
$remark = @$collection[$table_name]['remark'];
}

if($sub_account_id == $main_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 2)
{
$date = date('d-m-Y',strtotime($date));	

$excel.="<tr>
<td>$date</td>
<td>$narration</td>
<td>$module_name2</td>
<td>$receipt_id</td>
<td>";
if($amount_category_id == 1) { $balance = $balance - $amount;   
$excel.="$amount"; 
} else { 
$excel.="-"; } 
$excel.="</td>
<td>";
if($amount_category_id == 2) { $balance = $balance + $amount;  
$excel.="$amount"; } else { 
$excel.="-"; 
} 
$excel.="</td></tr>";
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}
$closing_balance = $op_bal2 - $total_debit + $total_credit + ($close);
}}}}

$excel.="<tr>
<th colspan='4' style='text-align:right;'><b> Total </b></th>
<th>$total_debit</th>
<th>$total_credit</th>
</tr>";
 
$excel.="<tr>
<th style='text-align:center;'>Opening Balance:</th>
<th style='text-align:center;'>Total Debits</th>
<th style='text-align:center;'>Total Credits</th>
<th colspan='3' style='text-align:center;'>Closing balance</th>
</tr>"; 


$excel.="<tr>
<th style='text-align:center;'>";
if($opening_balance > 0)
{
$opening_balance = $opening_balance;
}
else if($opening_balance < 0)
{
$opening_balance = abs($opening_balance);
$opening_balance = $opening_balance;
}
$excel.="$opening_balance</th>";

$excel.="<th colspan='' style='text-align:center;'>
$total_debit</th>
<th style='text-align:center;'>$total_credit</th>
<th colspan='3' style='text-align:center;'>";
if($closing_balance > 0)
{
$closing_balance = $closing_balance.'&nbsp;&nbsp;Cr';
}
else if($closing_balance < 0)
{
$closing_balance = abs($closing_balance);
$closing_balance = $closing_balance.'&nbsp;&nbsp;Dr';
}
$excel.="$closing_balance</th>
</tr>";

$excel.="</table>";
}
echo $excel;
}
//////////////////////////// End Ledger Excel (Accounts)/////////////////////////////

////////////////////////////////////////////// Start Ledger Show Ajax (Accounts)////////////////////////////////////////////////////////////////////////
function ledger_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
$type = (int)$this->request->query('type');
$this->set('type',$type);
if($type == 1)
{
$main_id = (int)$this->request->query('main_id');
$sub_id = (int)$this->request->query('sub_id');
$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');
$this->set('main_id',$main_id);
$this->set('sub_id',$sub_id);
$this->set('date111',$date1);
$this->set('date222',$date2);
$this->set('type',$type);
}
if($type == 2)
{
$main_id = 34;
$flat_id = (int)$this->request->query('flat_id');
$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"flat"=>$flat_id);
$cursor = $this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$user_id = (int)@$collection['user']['user_id'];
}
$result_gh = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch3'),array('pass'=>array(@$user_id)));
foreach ($result_gh as $collection) 
{
$sub_id = (int)$collection['ledger_sub_account']['auto_id'];
}	
$this->set('main_id',$main_id);
$this->set('sub_id',$sub_id);
$this->set('date111',$date1);
$this->set('date222',$date2);
$this->set('type',$type);

}

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
}
$year = date('Y');
$date_f = @$from.'-'.$year;

$datefrom = date('Y-m-d',strtotime($date_f));
$datefrom = new MongoDate(strtotime($datefrom));

if($main_id == 15 || $main_id == 33 || $main_id == 34 || $main_id == 35)
{
$this->loadmodel('ledger'); 
$conditions=array("op_date"=>$datefrom,"account_type"=> 1,"account_id"=>
$sub_id,"receipt_id"=>"O_B","society_id"=>$s_society_id);
$cursor=$this->ledger->find('all',array('conditions'=>$conditions));
$op_deb = 0;
$op_cred = 0;
foreach($cursor as $collection)
{
$amount_type2 = (int)$collection['ledger']['amount_category_id'];
$amount2 = $collection['ledger']['amount'];
}
if(@$amount_type2 == 1)
{
$op_deb = $op_deb + $amount2;
}
else if(@$amount_type2 == 2)
{
$op_cred = $op_cred + $amount2;
}

}
else
{
$this->loadmodel('ledger'); 
$conditions=array("op_date"=>$datefrom,"account_type"=> 2,"account_id"=>
$main_id,"receipt_id"=>"O_B","society_id"=>$s_society_id);
$cursor=$this->ledger->find('all',array('conditions'=>$conditions));
$op_deb = 0;
$op_cred = 0;
foreach($cursor as $collection)
{
$amount_type2 = (int)$collection['ledger']['amount_category_id'];
$amount2 = $collection['ledger']['amount'];
}
if(@$amount_type2 == 1)
{
$op_deb = $op_deb + $amount2;
}
else if(@$amount_type2 == 2)
{
$op_cred = $op_cred + $amount2;
}
}
$this->set('op_deb',$op_deb);
$this->set('op_cred',$op_cred);

$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor3=$this->ledger->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];	
}
$this->set('society_name',@$society_name);



}
/////////////////////////////////////// End Ledger Show Ajax (Accounts)//////////////////////////////////////////////

//////////////////////////////// Start Jounal add new /////////////////////////////////////////////////////////////
function journal_add_new()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}


//$this->ath();
//$this->check_user_privilages();	

$this->loadmodel('ledger_account');
$cursor2=$this->ledger_account->find('all');
$this->set('cursor2',$cursor2);





}
//////////////////////////////// End Jounal add new /////////////////////////////////////////////////////////////

///////////////////////////////// Start journal validation///////////////////////////////////////////////////////////
function journal_validation()
{
$this->layout='blank';
$q=$this->request->query('q');
$q = html_entity_decode($q);
$date2 = $this->request->query('b');

$s_society_id = (int)$this->Session->read('society_id');
$s_user_id  = (int)$this->Session->read('user_id');

$res_society=$this->society_name($s_society_id);
foreach($res_society as $data)
{
$society_name=$data['society']['society_name'];
}

$s_n='';
$sco_na=$society_name;
$dd=explode(' ',$sco_na);
$first=$dd[0];
@$two=$dd[1];
@$three=$dd[2];
$s_n.=" $first $two $three ";

date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());

$date3 = json_decode($date2, true);
if(empty($date3))
{
$output = json_encode(array('type'=>'error', 'text' => 'Please Select Transaction Date'));
die($output);
}

$date4 = date("Y-m-d", strtotime($date3));
$date4 = new MongoDate(strtotime($date4));
$cnnn = 55;
$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor=$this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$cnnn = 555;
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

if($cnnn == 55)
{
$output = json_encode(array('type'=>'error', 'text' => 'Transaction Date is not in Financial Year'));
die($output);
}
if($abc == 555)
{
$output = json_encode(array('type'=>'error', 'text' => 'Transaction Date is not in Financial Year'));
die($output);
}

$myArray = json_decode($q, true);
$c=0;
$total_debit = 0;
$total_credit = 0;
foreach($myArray as $child)
{
$c++;

if(empty($child[0])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Select Ledger Account in rows'.$c));
die($output);
}	
if($child[0] == 15 || $child[0] == 33 || $child[0] == 34 || $child[0] == 35)
{	
	
if(empty($child[1])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Select Ledger Sub Account in rows'.$c));
die($output);
}	
if(empty($child[2]) and empty($child[3])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Debit or Credit in rows'.$c));
die($output);
}
if(is_numeric($child[2]) || is_numeric($child[3]))
{
}	
else
{
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Numeric value in Debit or Credit'.$c));
die($output);
}
$total_debit = $total_debit + $child[2];
$total_credit = $total_credit + $child[3];
}	
else
{
if(empty($child[1]) and empty($child[2])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Debit or Credit in rows'.$c));
die($output);
}	
if(is_numeric($child[1]) || is_numeric($child[2]))
{
}	
else
{
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Numeric value in Debit or Credit'.$c));
die($output);
}
$total_debit = $total_debit + $child[1];
$total_credit = $total_credit + $child[2];
}	


}	
if($total_debit != $total_credit)
{
$output = json_encode(array('type'=>'error', 'text' => 'Debit and Credit not Match '));
die($output);
}	
	
////////////////////////////////////////////////////////

$this->loadmodel('journal');
$conditions=array("society_id" => $s_society_id);
$order=array('journal.receipt_id'=>'DESC');
$cursor=$this->journal->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$receipt_no = $collection['journal']['receipt_id']; 
}
if(!empty($receipt_no))
{
$receipt_no++;
}	
else
{	
$receipt_no = 1001;
}

foreach($myArray as $child)
{



$current_date = date("Y-m-d");
$current_date = new MongoDate(strtotime($current_date));





$ledger = (int)$child[0];
if($ledger == 15 || $ledger == 33 || $ledger == 34 || $ledger == 35)
{
$ledger_sub = (int)$child[1];
$debit = $child[2];
$credit = $child[3];
$desc = $child[4];
if(empty($debit) && !empty($credit))
{ 
$amount_category_id = 2;
$amount = $credit;
}
if(empty($credit) && !empty($debit))
{
$amount_category_id = 1;
$amount = $debit;	
}
$this->loadmodel('journal');
$order=array('journal.auto_id'=> 'DESC');
$cursor=$this->journal->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['journal']["auto_id"];
}
if(empty($last))
{
$ii=0;
}	
else
{	
$ii=$last;
}
$ii++;
$this->loadmodel('journal');
$multipleRowData = Array( Array("auto_id" => $ii, "receipt_id" => $receipt_no, "account_type" => 1, 
"ledger_type_id" => $ledger,"user_id" => $ledger_sub, "transaction_date" => $date4, 
"current_date" => $current_date, "amount" => $amount, "amount_category_id" => $amount_category_id, "remark" => $desc ,
"society_id" => $s_society_id,"approver" => $s_user_id));
$this->journal->saveAll($multipleRowData);


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']["auto_id"];
}
if(empty($last))
{
$k=0;
}	
else
{	
$k=$last;
}
$k++;
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $receipt_no, 
"amount" => $amount, "amount_category_id" => $amount_category_id, "table_name" => "journal", "account_type" => 1, "account_id" => $ledger_sub,
"current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Journal"));
$this->ledger->saveAll($multipleRowData);	

}
else
{
$debit = $child[1];
$credit = $child[2];
$desc = $child[3];
if(empty($debit) && !empty($credit))
{ 
$amount_category_id = 2;
$amount = $credit;
}
if(empty($credit) && !empty($debit))
{
$amount_category_id = 1;
$amount = $debit;	
}

$this->loadmodel('journal');
$order=array('journal.auto_id'=> 'DESC');
$cursor=$this->journal->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['journal']["auto_id"];
}
if(empty($last))
{
$ii=0;
}	
else
{	
$ii=$last;
}
$ii++;
$this->loadmodel('journal');
$multipleRowData = Array( Array("auto_id" => $ii, "receipt_id" => $receipt_no, "account_type" => 2, "ledger_type_id" => $ledger,
"user_id" => $ledger, "transaction_date" => $date4, "current_date" => $current_date, "amount" => $amount, 
"amount_category_id" => $amount_category_id, "remark" => $desc , "society_id" => $s_society_id,"approver" => $s_user_id));
$this->journal->saveAll($multipleRowData);

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']["auto_id"];
}
if(empty($last))
{
$k=0;
}	
else
{	
$k=$last;
}
$k++;
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $receipt_no, 
"amount" => $amount, "amount_category_id" => $amount_category_id, "table_name" => "journal",  "account_type" => 2, 
"account_id" => $ledger, "current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Journal"));
$this->ledger->saveAll($multipleRowData);
}
}
////////////////////////////////////////////////////////////////
$output = json_encode(array('type'=>'succ', 'text' => 'New Journal Entry Inserted in society successfully.'));
    die($output);
}

///////////////////////////////// Start journal validation///////////////////////////////////////////////////////////

}
?>