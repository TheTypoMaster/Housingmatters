<?php 
App::import('Controller','Hms');
class ExpensetrackersController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);
var $name = 'Expensetrackers';

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









/////////////////////////Start Expense Tracker Add (Accounts) ///////////////////////

function expense_tracker_add()
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

$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$order=array('expense_tracker.receipt_id'=> 'DESC');
$cursor=$this->expense_tracker->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['expense_tracker']['receipt_id'];
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


$this->loadmodel('accounts_group');
$conditions=array("accounts_id" => 4);
$cursor1=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 15);
$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);



if(isset($this->request->data['ext_addxfdfg']))
{

$posting_date = $this->request->data['posting_date'];
$posting_date = date("Y-m-d", strtotime($posting_date));
$posting_date = new MongoDate(strtotime($posting_date));
$file_name = $_FILES['uploaded']['name'];
$expense_head = (int)$this->request->data['ex_head'];
$invoice_date = $this->request->data['invoice_date'];
$invoice_amount = (int)$this->request->data['invoice_amount']; 
$due_date = $this->request->data['due_date'];
$party_head = (int)$this->request->data['party_head'];

$description = $this->request->data['description'];
$current_date = date("d-m-Y");
$invoice_reference = $this->request->data['invoice_reference'];
$invoice_date = date("Y-m-d", strtotime($invoice_date));
$invoice_date = new MongoDate(strtotime($invoice_date));


$due_date = date("Y-m-d", strtotime($due_date));
$due_date = new MongoDate(strtotime($due_date));

$current_date = date("Y-m-d", strtotime($current_date));
$current_date = new MongoDate(strtotime($current_date));


$target = "expenset/";
$target = $target . basename( $_FILES['uploaded']['name']) ;
$ok=1;
move_uploaded_file($_FILES['uploaded']['tmp_name'], $target);











////////////////////////////////////////
$p = 1;
while($p < 3)
{
if($p == 1)
{


$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$order=array('expense_tracker.auto_id'=> 'DESC','expense_tracker.receipt_id'=>'DESC');
$cursor=$this->expense_tracker->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['expense_tracker']["auto_id"];
$r_last = $collection['expense_tracker']['receipt_id']; 
}
if(empty($last) && empty($r_last))
{
$i=0;
$r = 1000;
}	
else
{	
$i=$last;
$r = $r_last;
}
$i++;
$r++;
$this->loadmodel('expense_tracker');
$multipleRowData = Array( Array("auto_id" => $i, "receipt_id" => $r, "society_id" => $s_society_id, "current_date" => $current_date, 
"approver" => $s_user_id, "expense_head" => $expense_head, "invoice_date" => $invoice_date, 
"due_date" => $due_date, "party_head" => $party_head, "description" => $description, "posting_date" => $posting_date,
"amount" => $invoice_amount, "amount_category_id" => 1 , "invoice_reference" => $invoice_reference,"file_name"=>$file_name));
$this->expense_tracker->saveAll($multipleRowData);   


}
if($p == 2)
{
/*	$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$order=array('expense_tracker.auto_id'=> 'DESC','expense_tracker.receipt_id'=>'DESC');
$cursor=$this->expense_tracker->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['expense_tracker']["auto_id"];
$r_last = $collection['expense_tracker']['receipt_id']; 
}
if(empty($last) && empty($r_last))
{
$i=0;
$r = 1000;
}	
else
{	
$i=$last;
$r = $r_last;
}
$i++;
$r++;
$this->loadmodel('expense_tracker');
$multipleRowData = Array( Array("auto_id" => $i, "receipt_id" => $r, "society_id" => $s_society_id, "current_date" => $current_date, 
"approver" => $s_user_id, "expense_head" => $expense_head, "invoice_date" => $invoice_date, 
"due_date" => $due_date, "party_head" => $party_head, "description" => $description, "posting_date" => $posting_date,
"amount" => $invoice_amount, "amount_category_id" => 2, "invoice_reference" => $invoice_reference));
$this->expense_tracker->saveAll($multipleRowData);   
*/
}
$p++;
}
/////////////////////////////////////////////////////////////////////
$sub_account_id_p = $party_head;
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $r, 
"amount" => $invoice_amount, "amount_category_id" => 2, "table_name" => "expense_tracker", "account_type" =>  1, "account_id" => $sub_account_id_p, 
"current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Expense Tracker"));
$this->ledger->saveAll($multipleRowData);   





$sub_account_id_e = $expense_head;
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $r, 
"amount" => $invoice_amount, "amount_category_id" => 1, "table_name" => "expense_tracker", "account_type" => 2,  
"account_id" => $sub_account_id_e, "current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Expense Tracker"));
$this->ledger->saveAll($multipleRowData);   

/////////////////////////////////////////////////////////////////////


$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$cursor3=$this->expense_tracker->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$d_receipt_id = (int)$collection['expense_tracker']['receipt_id'];	
}
?>

<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Expense Tracker</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Expense Voucher #<?php echo $d_receipt_id; ?> is  generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="expense_tracker_view" class="btn blue">OK</a>
</div>
</div>

<?php		
}
}
///////////////////////End Expense Tracker Add (Accounts) ////////////////////////////

/////////////////////////////////////////////////////////////Start Expense Tracker View (Accounts)///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function expense_tracker_view()
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

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,"ledger_id" => 15);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
//////////////////////////////////////////////////////////////// End Expense Tracker View (Accounts)////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////// Start Expense Tracker Pie Chart (Accounts)///////////////////////////////
function expense_tracker_pie_chart()
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

}
///////////////////// End Expense Tracker Pie Chart (Accounts)/////////////////////////////////

///////////////Start Expense Tracker Pie Chart Ajax(Accounts)//////////////////////////////////

function expense_tracker_pie_chart_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');
$this->set('date1',$date1);
$this->set('date2',$date2);

$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->expense_tracker->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
///////////////End Expense Tracker Pie Chart Ajax(Accounts)//////////////////////////////////


////////////////////// Start Expense Tracker Show Ajax ///////////////////////////////////
function expense_tracker_ajax_view2()
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

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('expense_tracker');
$cursor1=$this->expense_tracker->find('all');
$this->set('cursor1',$cursor1);

$this->loadmodel('accounts_group');
$conditions=array("accounts_id"=>4);
$cursor2 = $this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


$this->loadmodel('expense_tracker');
$conditions=array("society_id"=>$s_society_id);
$cursor3 = $this->expense_tracker->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);


}
/////////////////////////////End Expense Tracker Show Ajax///////////////////////////

/////////////////////Start Function expense Tracker Add Fetch2 (Accounts)//////////////
function expense_tracker_fetch2($auto_id) 
{
$this->loadmodel('ledger_account');
$conditions=array("group_id" => $auto_id);
return $this->ledger_account->find('all',array('conditions'=>$conditions));
}
////////////////End Function Fetch expense Tracker Add Fetch2 (Accounts)//////////////

///////////////////////////////////////////////////////////Start Function expense Tracker View Fetch1 (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function expense_tracker_fetch1($auto_id) 
{
$this->loadmodel('expense_tracker');
$conditions=array("party_head" => $auto_id,"amount_category_id" => 2);
return $this->expense_tracker->find('all',array('conditions'=>$conditions));


}

//////////End Function Fetch expense Tracker View Fetch1 (Accounts)//////////////////////////////////////////////////

////////////////// Start Expense Tracker Excel/////////////////////////////////////
function expense_tracker_excel()
{
$this->layout="";
$filename="Expense Tracker";
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

$s_role_id = (int)$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$start    = (new DateTime($from));
$end      = (new DateTime($to));
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);


foreach($period as $data)
{
$mon1 = $data->format("M-Y");

$this->loadmodel('expense_tracker');
$conditions=array("society_id"=>$s_society_id);
$cursor3 = $this->expense_tracker->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$auto_id = (int)$collection['expense_tracker']['auto_id'];	
$expense_date_mongo = $collection['expense_tracker']['posting_date'];
$expense_date = date('d-m-Y',$expense_date_mongo->sec);
$expense_month = date("M-Y",strtotime($expense_date));
if($expense_month == $mon1) 
{
$expense_arr[] = array($auto_id,$expense_month); 
}
}
}
$this->loadmodel('expense_tracker');
$conditions=array("society_id"=>$s_society_id);
$cursor3 = $this->expense_tracker->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$expense_date_mongo = $collection['expense_tracker']['posting_date'];
$expense_date = date('d-m-Y',$expense_date_mongo->sec);
$expense_month = date("M-Y",strtotime($expense_date));
$expense_month_arr[] = $expense_month;
}

$excel="
<table border='1'>
<tr>
<th>Expense Head</th>";

foreach ($period as $dt){
$month_name1 = $dt->format("M-Y");
for($p=0; $p<sizeof($expense_month_arr); $p++)
{
$month_name2 = $expense_month_arr[$p];
if($month_name1 == $month_name2)
{
$abc[] = $month_name1;

$excel.="<th style='text-align:center;'>$month_name1</th>";
break;
}}}
$excel.="</tr>";
$total = 0;
$this->loadmodel('accounts_group');
$conditions=array("accounts_id"=>4);
$cursor2 = $this->accounts_group->find('all',array('conditions'=>$conditions));
foreach($cursor2 as $collection)
{
$group_id = (int)$collection['accounts_group']['auto_id'];	
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array($group_id)));
foreach($result2 as $collection2)
{
$ex_head = (int)$collection2['ledger_account']['auto_id'];	
$expense_head = $collection2['ledger_account']['ledger_name'];
for($k=0; $k<sizeof(@$expense_arr); $k++)
{
$exp_arr1 = $expense_arr[$k];
$auto_id2 = (int)$exp_arr1[0];
$month5 = $exp_arr1[1];
$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_tracker_fetch'),array('pass'=>array($auto_id2)));
foreach($result5 as $collection3)
{
$exp_head2 = (int)$collection3['expense_tracker']['expense_head'];
$amount = $collection3['expense_tracker']['amount'];
}
if($exp_head2 == $ex_head)
{

$excel.="<tr><td style='text-align:left;'>$expense_head</td>";

for($m=0; $m<sizeof($abc); $m++)
{
$total = 0;
$month_name3 = $abc[$m];
$this->loadmodel('expense_tracker');
$conditions=array("society_id"=>$s_society_id);
$cursor3 = $this->expense_tracker->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection6)
{
$exps_head = (int)$collection6['expense_tracker']['expense_head'];
$posting_date = $collection6['expense_tracker']['posting_date'];
$amount = $collection6['expense_tracker']['amount'];
$posting_date = date('M-Y',$posting_date->sec);
if($posting_date == $month_name3 && $exp_head2 == $exps_head)
{
$total = $total + $amount;	
}
}

$excel.="<td style='text-align:center;'>$total</td>";
$total = 0;
}
$excel.="</tr>";
break;
}}}}
$excel.="</table>";

echo $excel;





}
////////////////////// End Expense Tracker Excel///////////////////////////////////////////////////////////////

////////////////////////// Start Expense Tracker Json ///////////////////////////////////////////////////////////
function expense_tracker_json()
{
$this->layout=null;
$post_data=$this->request->data;
$this->ath();
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$expense_head = (int)$post_data['expense_head'];	
$invoice_ref = $post_data['invoice_ref'];	
$party_ac_head = (int)$post_data['party'];		
$amount = $post_data['amount'];	
$description = $post_data['desc'];		
$posting_date = $post_data['posting'];	
$due_date = $post_data['due'];	
$invoice_date = $post_data['inv_date'];		

$report = array();
if(empty($expense_head)){
		$report[]=array('label'=>'ex_head', 'text' => 'Please select Expense Head');
	}	
	
if(empty($invoice_ref)){
		$report[]=array('label'=>'inv_ref', 'text' => 'Please Fill Invoice Reference');
	}		
	
if(empty($party_ac_head)){
		$report[]=array('label'=>'prt_head', 'text' => 'Please Select Party Account Head');
	}		
if(empty($amount)){
		$report[]=array('label'=>'amt', 'text' => 'Pleaes Fill Invoice Amount');
	}		
if(empty($posting_date)){
$report[]=array('label'=>'pos_dat', 'text' => 'Please Select Posting Date');
}	

if(empty($due_date)){
$report[]=array('label'=>'du_dat', 'text' => 'Please Select Due Date');
}
	
if(empty($invoice_date)){
$report[]=array('label'=>'inv_dat', 'text' => 'Please Select Invoice Date');
}

if(!empty($amount))
{
if(is_numeric($amount))
{
}
else
{
$report[]=array('label'=>'amt', 'text' => 'Pleaes Fill Numeric Value');
}
}
	
$date4 = date("Y-m-d", strtotime($posting_date));
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
if(!empty($posting_date))
{
if($abc == 555)
{
$report[]=array('label'=>'pos_dat', 'text' => 'The Date is not in Open Financial Year, Please Select another Date');
}
}
if(sizeof($report)>0)
{
$output=json_encode(array('report_type'=>'error','report'=>$report));
die($output);
}

$date = date('Y-m-d');
$current_date = new MongoDate(strtotime($date));

$posting_date2 = date("Y-m-d", strtotime($posting_date));
$posting_date2 = new MongoDate(strtotime($posting_date2));

$due_date2 = date("Y-m-d", strtotime($due_date));
$due_date2 = new MongoDate(strtotime($due_date2));

$invoice_date2 = date("Y-m-d", strtotime($invoice_date));
$invoice_date2 = new MongoDate(strtotime($invoice_date2));





$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$order=array('expense_tracker.auto_id'=> 'DESC','expense_tracker.receipt_id'=>'DESC');
$cursor=$this->expense_tracker->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['expense_tracker']["auto_id"];
$r_last = $collection['expense_tracker']['receipt_id']; 
}
if(empty($last) && empty($r_last))
{
$i=0;
$r = 1000;
}	
else
{	
$i=$last;
$r = $r_last;
}
$i++;
$r++;
$this->loadmodel('expense_tracker');
$multipleRowData = Array( Array("auto_id" => $i, "receipt_id" => $r, "society_id" => $s_society_id, "current_date" => $current_date, 
"approver" => $s_user_id, "expense_head" => $expense_head, "invoice_date" => $invoice_date2, 
"due_date" => $due_date2, "party_head" => $party_ac_head, "description" => $description, "posting_date" => $posting_date2,
"amount" => $amount, "invoice_reference" => $invoice_ref));
$this->expense_tracker->saveAll($multipleRowData); 


	
$sub_account_id_p = $party_ac_head;
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $r, 
"amount" => $amount, "amount_category_id" => 2, "table_name" => "expense_tracker", "account_type" =>  1, "account_id" => $sub_account_id_p,"current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Expense Tracker"));
$this->ledger->saveAll($multipleRowData);   





$sub_account_id_e = $expense_head;
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $r, 
"amount" => $amount, "amount_category_id" => 1, "table_name" => "expense_tracker", "account_type" => 2,  
"account_id" => $sub_account_id_e, "current_date" => $current_date, "society_id" => $s_society_id,"module_name"=>"Expense Tracker"));
$this->ledger->saveAll($multipleRowData);  


$output=json_encode(array('report_type'=>'publish','report'=>'Expense Voucher #'.$r.' is generated successfully'));
die($output);

	
}
////////////////////////// End Expense Tracker Json ///////////////////////////////////////////////////////////
}
?>
