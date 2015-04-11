<?php
App::import('Controller','Hms');
class AccountsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);

var $name = 'Accounts';




//////////////////////////////////////////START SETTINGS MODULE///////////////////////////////////////////////////

/////////////////////////////////////////Start Master Ledger Sub Account Ajax  (Accounts)////////////////////////////////////////////////////////////////
function master_ledger_sub_account_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$value = (int)$this->request->query('value');
$this->set('value',$value);

}
///////////////////////////////////////End Master Ledger Sub Account Ajax (Accounts)/////////////////////////////////////////////////////////////////////



//////////////////// Start Opening Balance Import (Accounts)////////////////////////////
function opening_balance_import()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();


$s_society_id=(int)$this->Session->read('society_id');
$nnn = 5;
$this->set('nnn',$nnn);


if ($this->request->is('post')) 
{
$file=$this->request->form['file']['name'];
$dir='C:\xampp\htdocs\cakephp\app\webroot\csv_file';
$target = "csv_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target);

$f = fopen('csv_file/'.$file, 'r') or die("ERROR OPENING DATA");
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
$total_debit = 0;
$total_credit = 0;
for($i=1;$i<sizeof($test);$i++)
{
$row_no=$i+1;
$r=explode(',',$test[$i][0]);
$date = trim($r[0]);
//$acccount_type=trim($r[1]); 
$account_name=trim($r[1]);
$amount_type=trim($r[2]);
$opening_balance=trim($r[3]);
//if($i==1) { $email_current=array(); }
//$society_name=trim($r[4]);
//$owner=trim($r[5]);
//$committee=trim($r[6]);
//$residing =trim($r[7]);
 $date1 = date("Y-m-d", strtotime($date));
$date1 = new MongoDate(strtotime($date1));

if(!empty($date)) 
{	
//$ok=2; 

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions));
$abc = 0;
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
if($date1 <= $to && $date1 >= $from)
{
$abc = 5;
break;
}
}
if($abc == 5)
{
$ok=2;
}
else
{
$ok=1; $error_msg[]="Date is not in Open Year ".$row_no.".";	
break;
}
}
else { $ok=1; $error_msg[]="Year should not be empty in row ".$row_no.".";	break;}


if(!empty($amount_type)) 
{
$ok=2;
 
if (strcasecmp($amount_type, 'debit') == 0) 
{

$amount_type_id = 1;
$total_debit = $total_debit + $opening_balance;
}	
else if(strcasecmp($amount_type, 'credit') == 0)
{
$amount_type_id = 2;
$total_credit = $total_credit + $opening_balance;
}
else
{
$ok = 1; $error_msg[]="Please Fill 'Debit' or 'Credit' ".$row_no."."; break;
}
}
else { $ok=1; $error_msg[]="Amount Type should not be empty in row ".$row_no.".";	break;}




if(!empty($opening_balance)) 
{	
$ok=2; 
if(is_numeric($opening_balance))
{

}
else
{
$ok = 1;
$error_msg[]="Opening Balance should be numeric value ".$row_no.".";	break;
}
}
else { $ok=1; $error_msg[]="Opening Balance should not be empty in row ".$row_no.".";	break;}


if(!empty($account_name)) 
{	$ok=2;

$this->loadmodel('ledger_account'); 
$conditions=array("ledger_name"=> new MongoRegex('/^' .  $account_name . '$/i'));
$result_ac=$this->ledger_account->find('all',array('conditions'=>$conditions));
$result_ac_count=sizeof($result_ac);


$this->loadmodel('ledger_sub_account'); 
$conditions=array("name"=> new MongoRegex('/^' .  $account_name . '$/i'));
$result_sac=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$result_sac_count=sizeof($result_sac);
if($result_ac_count>0)
{
$account_type_id = 2;
foreach($result_ac as $collection)
{
$account_id = (int)$collection['ledger_account']['auto_id'];
}
}
else if($result_sac_count>0)
{
$account_type_id = 1;
foreach($result_sac as $collection)
{
$account_id = (int)$collection['ledger_sub_account']['auto_id'];
}
}
else
{
$ok=1; $error_msg[]="No Account Name Match ".$row_no.".";	break;
}
}
else 
{ 
$ok=1; $error_msg[]="account name should not be empty in row ".$row_no.".";	break;
}
}
if($ok == 2)
{
if($total_debit == $total_credit)
{
$ok = 2; 
}
else
{
$ok = 1; $error_msg[]="Total Credit is not equal to Total debit";
}
}

$this->set('td',$total_debit);
$this->set('tc',$total_credit);

$this->set('error_msg',@$error_msg);
$this->set('ok',$ok);


if($ok == 2)
{
$this->Session->write('test2', $test);
$nnn = 55;
$this->set('nnn',$nnn);
$this->set('test',$test);


for($i=1;$i<sizeof($test);$i++)
{
$row_no=$i+1;
$r=explode(',',$test[$i][0]);
$date2=trim($r[0]);
//$acccount_type=trim($r[1]); 
$account_name=trim($r[1]);
$amount_type=trim($r[2]);
$opening_balance=trim($r[3]);

$date1 = date("Y-m-d", strtotime($date2));
$date1 = new MongoDate(strtotime($date1));


if (strcasecmp($amount_type, 'debit') == 0) 
{
$amount_type_id = 1;
}	
else if(strcasecmp($amount_type, 'credit') == 0)
{
$amount_type_id = 2;
}

$this->loadmodel('ledger_account'); 
$conditions=array("ledger_name"=> new MongoRegex('/^' .  $account_name . '$/i'));
$result_ac=$this->ledger_account->find('all',array('conditions'=>$conditions));
$result_ac_count=sizeof($result_ac);


$this->loadmodel('ledger_sub_account'); 
$conditions=array("name"=> new MongoRegex('/^' .  $account_name . '$/i'));
$result_sac=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$result_sac_count=sizeof($result_sac);
if($result_ac_count>0)
{
$account_type_id = 2;
foreach($result_ac as $collection)
{
$account_id = (int)$collection['ledger_account']['auto_id'];
}
}
else if($result_sac_count>0)
{
$account_type_id = 1;
foreach($result_sac as $collection)
{
$account_id = (int)$collection['ledger_sub_account']['auto_id'];
}
}
$cr_date = date("Y-m-d");
$cr_date = new MongoDate(strtotime($cr_date));

$u=$this->autoincrement('ledger','auto_id');
$this->loadmodel('ledger');
$this->ledger->saveAll(array("auto_id" => $u, "op_date" => $date1, 
"receipt_id" => "O_B","amount" => $opening_balance, "amount_category_id" => $amount_type_id, "module_id" => "O_B", "account_type" => $account_type_id,"account_id" => $account_id,"current_date" => $cr_date,"society_id" => $s_society_id));

$this->set('sucess','Csv Imported successfully.'); 
}
}
}
}

//////////////////// End Opening Balance Import (Accounts)//////////////////////////////

/////////////////////////////////// Start Master Period Status (Accounts)///////////////

function master_financial_period_status()
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



if(isset($this->request->data['status']))
{

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$order=array('financial_year.auto_id'=> 'ASC');
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['financial_year']['auto_id'];

$xyz = @$this->request->data['abc'.$auto_id];
if($xyz == 2)
{
$this->loadmodel('financial_year');
$this->financial_year->updateAll(array("status" => 1),array('auto_id'=> $auto_id));	
}
else
{
$this->loadmodel('financial_year');
$this->financial_year->updateAll(array("status" => 2),array('auto_id'=> $auto_id));	
}
}
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Financial Year</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h3><b>Record Updated Successfully</b></h3>
</center>
</div>
<div class="modal-footer">
<a href="master_financial_period_status" class="btn blue">OK</a>
</div>
</div>


<?php
}
$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$order=array('financial_year.auto_id'=> 'ASC');
$cursor1 = $this->financial_year->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor1',$cursor1);
}
///////////////////////////// End Master Period Status (Accounts)//////////////////////////
/////////////////// Start master Financial Year (Accounts)/////////////////////////////

function master_financial_year()
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

if(isset($this->request->data['sub1']))
{
 $from = $this->request->data['from'];	
 $to = $this->request->data['to'];	

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$from1 = date('d-M-Y',strtotime($from));
$to1 = date('d-M-Y',strtotime($to));


$a=$this->autoincrement('financial_year','auto_id');

$this->loadmodel('financial_year');
$multipleRowData = Array( Array("auto_id" => $a, "from" => $m_from, "to" => $m_to,"user_id"=>$s_user_id, "status"=> 1, "society_id" => $s_society_id));
$this->financial_year->saveAll($multipleRowData);  

?>

<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Financial Year</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h3><b>Record Inserted Successfully</b></h3>
</center>
</div>
<div class="modal-footer">
<a href="master_financial_year" class="btn blue">OK</a>
</div>
</div>

<?php
}

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$order=array('financial_year.auto_id'=> 'ASC');
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor as $collection)
{
$f_date = $collection['financial_year']['from'];
$t_date = $collection['financial_year']['to'];

$f_d1 = date('Y-m-d',$f_date->sec);
$t_d1 = date('Y-m-d',$t_date->sec);

$this->set('fd1',$f_d1);
$this->set('td1',$t_d1);


}
}

////////////////// End Master Financial Year(Accounts)/////////////////////////////////
/////////////////////Start Financial Vali Ajax(Accounts)//////////////////////////////
function financial_vali_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$cc = (int)$this->request->query('ss');
$this->set('cc',$cc);

}
/////////////////////End Financial Vali Ajax(Accounts)//////////////////////////////
////////////////////////Start Master Ledger Accounts COA(Accounts)///////////////////
function master_ledger_account_coa()
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
$this->set('s_user_id',$s_user_id);



$this->loadmodel('ledger_account');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id),
array("society_id" => 0)
));
$cursor=$this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id']; 
if(isset($this->request->data['sub'.$auto_id]))
{
//$cata22 = $this->request->data['cat'.$auto_id];

$group_id = (int)$this->request->data['gr_id'];
$ledger_name = $this->request->data['cat'];

$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("ledger_name" => $ledger_name,"group_id"=>$group_id),array("auto_id" => $auto_id));	
}
if(isset($this->request->data['sub2'.$auto_id]))
{
$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("delete_id" => 1),array("auto_id" => $auto_id));	

//$this->response->header('Location','master_ledger_account_coa);
}

}

if(isset($this->request->data['sub']))
{
$main_id = (int)$this->request->data['main_id'];
$name = $this->request->data['cat_name'];

if($main_id == 4)
{
$rate = (int)$this->request->data['rate'];

$this->loadmodel('ledger_account');
$order=array('ledger_account.auto_id'=> 'ASC');
$cursor=$this->ledger_account->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_account');
$multipleRowData = Array( Array("auto_id" => $i, "group_id" => $main_id, "ledger_name" => $name, "rate" => $rate,"society_id"=> $s_society_id,"edit_user_id"=>$s_user_id,"delete_id" => 0));
$this->ledger_account->saveAll($multipleRowData);
}
else if($main_id == 7 || $main_id == 8)
{
$amount = $this->request->data['amount'];		


$this->loadmodel('ledger_account');
$order=array('ledger_account.auto_id'=> 'DESC');
$cursor=$this->ledger_account->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_account');
$multipleRowData = Array( Array("auto_id" => $i, "group_id" => $main_id, "ledger_name" => $name, "amount" => $amount,"society_id"=> $s_society_id,"edit_user_id"=>$s_user_id,"delete_id" => 0));
$this->ledger_account->saveAll($multipleRowData);	
}
else
{
$this->loadmodel('ledger_account');
$order=array('ledger_account.auto_id'=> 'DESC');
$cursor=$this->ledger_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_account');
$multipleRowData = Array( Array("auto_id" => $i, "group_id" => $main_id, "ledger_name" => $name,"society_id"=> $s_society_id,"edit_user_id"=>$s_user_id,"delete_id" => 0));
$this->ledger_account->saveAll($multipleRowData);	
}
}



$this->loadmodel('accounts_groups');
$cursor1=$this->accounts_groups->find('all');
$this->set('cursor1',$cursor1);	

$this->loadmodel('ledger_account');
$conditions =array( '$or' => array( 
array("society_id"=>$s_society_id),
array("society_id"=>0)
));


$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	


$this->loadmodel('accounts_group');
$conditions=array("delete_id" => 0);
$cursor3=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);



}

///////////////////////////End Master Ledger Accounts COA (Accounts)//////////////////

////////////////////////////////////// Start Master Ledger Accounts Ajax COA (Accounts)//////////////////////////////////
function master_ledger_account_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$value = $this->request->query('value');
$this->set('value',$value);
}

/////////////////////// End Master Ledger Accounts Ajax COA (Accounts)//////////////////
//////////////////// Start Master Ledger Sub Accounts COA (Accounts) //////////////////////////////////

function master_ledger_sub_accounts_coa()
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

$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);	


/*
$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
if(isset($this->request->data['sub'.$auto_id]))
{

$this->loadmodel('ledger_sub_account');
$this->ledger_sub_account->updateAll(array("delete_id" => 1),array("auto_id" => $auto_id));	
}
}
*/
$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
if(isset($this->request->data['sub'.$auto_id]))
{
$ledger_id = (int)$this->request->data['gr'];
$name = $this->request->data['name'];

$this->loadmodel('ledger_sub_account');
$this->ledger_sub_account->updateAll(array("name" => $name,"ledger_id" => $ledger_id),array("auto_id" => $auto_id));	


}
}



if(isset($this->request->data['sub']))
{
$main_id = (int)$this->request->data['main_id'];
$name = $this->request->data['cat_name'];

if($main_id == 34)
{
$user_id = (int)$this->request->data['user_id'];

$this->loadmodel('ledger_sub_account');
$order=array('ledger_sub_account.auto_id'=> 'DESC');
$cursor=$this->ledger_sub_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_sub_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_sub_account');
$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id, "user_id" => $user_id,"delete_id"=>0));
$this->ledger_sub_account->saveAll($multipleRowData);	

}
else if($main_id == 15)
{
$sp_id = (int)$this->request->data['sp_id'];	

$this->loadmodel('ledger_sub_account');
$order=array('ledger_sub_account.auto_id'=> 'DESC');
$cursor=$this->ledger_sub_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_sub_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_sub_account');
$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id, "sp_id" => $sp_id,"delete_id"=>0));
$this->ledger_sub_account->saveAll($multipleRowData);	
}
else if($main_id == 33)
{
$bank_ac = $this->request->data['bank_account'];

$this->loadmodel('ledger_sub_account');
$order=array('ledger_sub_account.auto_id'=> 'DESC');
$cursor=$this->ledger_sub_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_sub_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_sub_account');
$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id, "bank_account" => $bank_ac,"delete_id"=>0));
$this->ledger_sub_account->saveAll($multipleRowData);	
}
else if($main_id == 35)
{
$tax = (int)$this->request->data['tax'];	


$this->loadmodel('ledger_sub_account');
$order=array('ledger_sub_account.auto_id'=> 'DESC');
$cursor=$this->ledger_sub_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_sub_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_sub_account');
$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id, "tax" => $tax,"delete_id"=>0));
$this->ledger_sub_account->saveAll($multipleRowData);
}
else
{

$this->loadmodel('ledger_sub_account');
$order=array('ledger_sub_account.auto_id'=> 'DESC');
$cursor=$this->ledger_sub_account->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger_sub_account']["auto_id"];
}
if(empty($last))
{
$i=0;
}	
else
{	
$i=$last;
}
$i++;
$this->loadmodel('ledger_sub_account');
$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id,"delete_id"=>0));
$this->ledger_sub_account->saveAll($multipleRowData);	
}
}

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

}
/////////////// End Master Ledger Sub Accounts COA (Accounts) /////////////////////////


//////////////////////////////////////////END SETTINGS MODULE///////////////////////////////////////////////////


///////////////////////////////////// START OVERDUE REPORT MODULE/////////////////////////////////////

////////////////////////Start Over Due Report (Accounts)/////////////////////////////
function over_due_report()
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


$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id, "tenant"=>1,"deactive"=>0);
$cursor1 = $this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('wing');
$conditions=array("society_id"=> $s_society_id);
$cursor2=$this->wing->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

$this->loadmodel('user');
$order=array('user.user_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id,"tenant" => 1,"deactive"=>0);
$cursor3 = $this->user->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set("cursor3",$cursor3);
}
////////////////////// End Over Due Report (Accounts)////////////////////////////////

/////////////////////// Start over due report show ajax(Accounts)//////////////////////////
function over_due_report_show_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$soc_name = $collection['society']['society_name'];
}
$this->set('soc_name',$soc_name);

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$wise = (int)$this->request->query('w');
$this->set('wise',$wise);
if($wise == 1)
{
$wing = (int)$this->request->query('wi');
$this->set("wing",$wing);
}
else if($wise == 2)
{
$user_id = (int)$this->request->query('u');
$this->set("user_id",$user_id);
}

$this->set('from',$from);
$this->set('to',$to);


$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id,"status"=>0);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

}
/////////////////////// End over due report show ajax(Accounts)//////////////////////////

///////////////////// Start OverDue Excel//////////////////////////////////////////
function overdue_excel()
{
$this->layout="";
$filename=strtotime("now");
header ("Expires: 0");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$from = $this->request->query('f');
$to = $this->request->query('t');
$wise = (int)$this->request->query('w');
if($wise == 1)
{
$wing = (int)$this->request->query('wi');
}
else if($wise == 2)
{
$user_id = (int)$this->request->query('u');
}
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)  
{
$society_name = $collection['society']['society_name'];
}

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));



$excel="<table border='1'>
<tr>
<th colspan='8' style='text-align:center;'>
Over Due Report  ($society_name)</th>
</tr>
<tr>
<th>#</th>
<th>Bill No</th>
<th>Owner Name</th>
<th>Bill Date</th>
<th>Due date</th>
<th>Total Amount</th>
<th>Due Amount</th>
<th>Bill Amount</th>
</tr>";

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id,"status"=>0);
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$date_from = $collection['regular_bill']['bill_daterange_from'];	
$date_to = $collection['regular_bill']['bill_daterange_to'];	
$due_date = $collection['regular_bill']['due_date'];	
$total_amt = (int)$collection['regular_bill']['total_amount'];
$tax_amt = (int)$collection['regular_bill']['tax_amount'];	
$due_amt = (int)$collection['regular_bill']['total_due_amount'];	
$bill_amt = (int)$collection['regular_bill']['g_total'];	
$bill_for_user = (int)$collection['regular_bill']['bill_for_user'];

$result11 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result11 as $collection2) 
{
$user_name = $collection2['user']['user_name'];
$wing_id = (int)$collection2['user']['wing'];  
$flat_id = (int)$collection2['user']['flat'];
$tenant = (int)$collection2['user']['tenant'];
}	

$total_amount = $total_amt + $tax_amt;
if($wise == 2)
{
if($user_id == $bill_for_user)
{
if($date_from >= $m_from && $date_to <= $m_to)
{
if($due_amt > 0)
{
$fromd = date('d-M-Y',$date_from->sec);	
$tod = date('d-M-Y',$date_to->sec);	
$dued = date('d-M-Y',$due_date->sec);	
$c++;
$excel.="<tr>
<td>$c</td>
<td>$bill_no</td>
<td>$user_name</td>
<td>$fromd  -  $tod</td>
<td>$dued</td>
<td>$total_amount</td>
<td>$due_amt</td>
<td>$bill_amt</td>
</tr>";
}}
}}
else if($wise == 1)
{
if($wing == $wing_id)
{
if($date_from >= $m_from && $date_to <= $m_to)
{
if($due_amt > 0)
{
$fromd = date('d-M-Y',$date_from->sec);	
$tod = date('d-M-Y',$date_to->sec);	
$dued = date('d-M-Y',$due_date->sec);	
$c++;
$excel.="<tr>
<td>$c</td>
<td>$bill_no</td>
<td>$user_name</td>
<td>$fromd  -  $tod</td>
<td>$dued</td>
<td>$total_amount</td>
<td>$due_amt</td>
<td>$bill_amt</td>
</tr>
";
}}}
}
}

$excel.="</table>";
echo $excel;
}
////////////////////// End OverDue Excel///////////////////////////////////////////

///////////////////////////////////// END OVERDUE REPORT MODULE/////////////////////////////////////////


////////////////////////////////////// START ACCOUNTS MODULE /////////////////////////////////////////////////////

//////////////////////// Start Account Statement (Accounts)//////////////////////////////
///////Done////////
function account_statement()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"status"=>0);
$cursor1 = $this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,"tenant"=>1,"deactive"=>0);
$cursor2 = $this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);
}
////////////////End Account Statement (Accounts)/////////////////////////////////////

///////////////////// Start account statement show ajax(Accounts)////////////////////
///////Done////////////
function account_statement_show_ajax()
{
$this->layout='blank';
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
$this->set('society_name',$society_name);


$from = $this->request->query('f');
$to = $this->request->query('t');
$value = (int)$this->request->query('ff');
$this->set('value',$value);
$this->set('from',$from);
$this->set('to',$to);

}
////////////////// End account statement show ajax(Accounts)////////////////////////

/////////////////////// Start Account Statement Excel////////////////////////////////
//////////Done//////////////////////////////
function account_statement_excel()
{
$this->layout="";
$filename=strtotime("now");
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
$user_id = (int)$this->request->query('u');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$excel="<table border='1'>
<tr>
<th colspan='7' style='text-align:center;'>
Account Statement ($society_name)
</th>
</tr>
<tr>
<th style='text-align:center;'>Sr. No.</th>
<th style='text-align:center;'>User Name</th>
<th style='text-align:center;'>Bill No.</th>
<th style='text-align:center;'>Bill for Date</th>
<th style='text-align:center;'>Last Date</th>
<th style='text-align:center;'>Total Amount</th>
<th style='text-align:center;'>Due Amount</th>
</tr>";
$nn = 0;
$grand_total_amount=0;
$total_due_amount=0;
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch2'),array('pass'=>array($user_id)));	
foreach($result2 as $collection)
{
$nn++;
$bill_no = (int)$collection['regular_bill']['receipt_id'];
$date_from = $collection['regular_bill']['bill_daterange_from'];
$date_to = $collection['regular_bill']['bill_daterange_to'];
$last_date = $collection['regular_bill']['due_date'];
$total_amount = (int)$collection['regular_bill']['g_total'];
$due_amount = (int)$collection['regular_bill']['remaining_amount'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
//$bill_no = (int)$collection[''][''];
//$bill_no = (int)$collection[''][''];
$date_from1 = date('d-M-Y',$date_from->sec);
$date_to1 = date('d-M-Y',$date_to->sec);
$due_date = date('d-M-Y',$last_date->sec); 

$bill_html = $collection['regular_bill']['bill_html'];
$receipt_id = (int)$collection['regular_bill']['receipt_id']; 
$result3 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));	
foreach($result3 as $collection)
{
$user_name = $collection['user']['user_name'];
$wing = (int)$collection['user']['wing'];
$flat =(int)$collection['user']['flat'];
}
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array(@$wing,@$flat)));

if($m_from <= $date_from && $m_to >= $date_to)
{
$grand_total_amount = $grand_total_amount + $total_amount;
$total_due_amount = $total_due_amount + $due_amount;	

$excel.="<tr>
<td style='text-align:center;'>$nn</td>
<td style='text-align:center;'>$user_name&nbsp;&nbsp;$wing_flat</td>
<td style='text-align:center;'>$bill_no</td>
<td style='text-align:center;'>$date_from1&nbsp;&nbsp;To&nbsp;&nbsp;$date_to1</td>
<td style='text-align:center;'>$due_date</td>
<td style='text-align:center;'>$total_amount</td>
<td style='text-align:center;'>$due_amount</td>
</tr>";
}}
$excel.="<tr>
<th colspan='5' style='text-align:center;'>Total</th>
<th style='text-align:center;'>$grand_total_amount</th>
<th style='text-align:center;'>$total_due_amount</th>
</tr>";

$excel.="</table>";

echo $excel;
}
/////////////////////// End Account Statement Excel////////////////////////////////

//////////////// Start ac statement Bill View////////////////////////////////////////
/////////// Done////////////////////////////
function ac_statement_bill_view()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$receipt_id = (int)$this->request->query('bill');
$this->loadmodel('regular_bill');
$conditions=array("receipt_id"=>$receipt_id,"society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['regular_bill']['bill_html'];	
}
$this->set('bill_html',$bill_html);

}
//////////////// End ac statement Bill View////////////////////////////////////////

////////////////////////////////////// START ACCOUNTS MODULE /////////////////////////////////////////////////////

//////////////////////////////// START MY FLAT MODULE///////////////////////////////////////////////////////////

//////////////////////// Start My Flat Bill (Accounts) //////////////////////////////
function my_flat_bill()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();



$s_role_id = (int)$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id,"status"=>0);
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$ele_id = (int)$collection['regular_bill']['regular_bill_id'];
}
$this->seen_notification(10,$ele_id);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id);
$cursor1 = $this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('user');
$conditions=array("user_id" => $s_user_id);
$cursor = $this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_id = (int)$collection['user']['flat'];
$wing_id = (int)$collection['user']['wing'];
}

$this->loadmodel('flat');
$conditions=array("flat_id" => $flat_id);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_name = $collection['flat']['flat_name'];
$flat_type_id = (int)$collection['flat']['flat_type_id'];
if($flat_type_id == 0)
{
$flat_size = $collection['flat']['sqr_feet'];
$flat_size = $flat_size.'&nbsp;&nbsp;'.'Sqr Feet';
}
else
{
$this->loadmodel('flat_rent');
$conditions=array("auto_id" => $flat_type_id);
$cursor8 = $this->flat_rent->find('all',array('conditions'=>$conditions));
foreach($cursor8 as $collection)
{
$flat_size = $collection['flat_rent']['name'];
}
}

}


$this->loadmodel('wing');
$conditions=array("wing_id" => $wing_id);
$cursor = $this->wing->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$wing_name = $collection['wing']['wing_name'];
}
$this->set('flat_size',$flat_size);
$this->set('flat_name',$flat_name);
$this->set('wing_name',$wing_name);
}

/////////////////////////// End My Flat Bill (Accounts) ////////////////////////////

///////////////// Start my flat bill Ajax(accounts)///////////////////////////////////
function my_flat_bill_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$value = (int)$this->request->query('tp');
$from = $this->request->query('date1');
$to = $this->request->query('date2');
$this->set('to',$to);
$this->set('from',$from);
$this->set('value',$value);

$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id);
$cursor1 = $this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id,"status"=>0);
$cursor2 = $this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id,"status"=>1);
$cursor3 = $this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);
}
///////////////// End my flat bill Ajax(accounts)//////////////////////////////////

//////////////////////// Start my flat Bill Excel////////////////////////////////////
function my_flat_bill_excel()
{
$this->layout="";
$filename=strtotime("now");
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
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}


$from = $this->request->query('f');
$to = $this->request->query('t');
$tp = (int)$this->request->query('tp');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

if($tp == 1)
{
$excel="
<table border='1'>
<tr>
<th style='text-align:center;' colspan='6'>
Bill Detail  ($society_name)
</th>
</tr>
<tr>
<th style='text-align:center;'>#</th>
<th style='text-align:center;'>Bill No.</th>
<th style='text-align:center;'>Bill Date</th>
<th style='text-align:center;'>Due Date</th>
<th style='text-align:center;'>Total Amount</th>
<th style='text-align:center;'>Pay Amount</th>
</tr>";
$nn=0;
$gt_amt = 0;
$gt_pay_amt = 0;
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id);
$cursor1 = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$from2 = $collection['regular_bill']['bill_daterange_from'];
$to2 = $collection['regular_bill']['bill_daterange_to'];
$due_date = $collection['regular_bill']['due_date'];
$total_amount = (int)$collection['regular_bill']['g_total'];
$remaining_amt = (int)$collection['regular_bill']['remaining_amount'];
$fromm = date('d-M-Y',$from2->sec);
$tom = date('d-M-Y',$to2->sec);
$due = date('d-M-Y',$due_date->sec);
$pay_amt = $total_amount - $remaining_amt; 
if($m_from <= $from2 && $m_to >= $to2)
{
$nn++;
$gt_amt = $gt_amt + $total_amount;
$gt_pay_amt = $gt_pay_amt + $pay_amt;
$excel.="<tr>
<td style='text-align:center;'>$nn</td>
<td style='text-align:center;'>$bill_no</td>
<td style='text-align:center;'>$fromm - $tom</td>
<td style='text-align:center;'>$due</td>
<td style='text-align:center;'>$total_amount</td>
<td style='text-align:center;'>$pay_amt</td>
</tr>";
}}
$excel.="<tr>
<th colspan='4'>Grand Total</th>
<th style='text-align:center;'>$gt_amt</th>
<th style='text-align:center;'>$gt_pay_amt</th>
</table>";
}
if($tp == 2)
{
$excel="
<table border='1'>
<tr>
<th style='text-align:center;' colspan='6'>
Bill Detail  ($society_name)
</th>
</tr>
<tr>
<th style='text-align:center;'>#</th>
<th style='text-align:center;'>Bill No.</th>
<th style='text-align:center;'>Bill Date</th>
<th style='text-align:center;'>Due Date</th>
<th style='text-align:center;'>Total Amount</th>
<th style='text-align:center;'>Pay Amount</th>
</tr>";
$nn=0;
$gt_amt = 0;
$gt_pay_amt = 0;
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id,"status"=>0);
$cursor2 = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor2 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$from2 = $collection['regular_bill']['bill_daterange_from'];
$to2 = $collection['regular_bill']['bill_daterange_to'];
$due_date = $collection['regular_bill']['due_date'];
$total_amount = (int)$collection['regular_bill']['g_total'];
$remaining_amt = (int)$collection['regular_bill']['remaining_amount'];
$fromm = date('d-M-Y',$from2->sec);
$tom = date('d-M-Y',$to2->sec);
$due = date('d-M-Y',$due_date->sec);
$pay_amt = $total_amount - $remaining_amt; 
if($m_from <= $from2 && $m_to >= $to2)
{
$nn++;
$gt_amt = $gt_amt + $total_amount;
$gt_pay_amt = $gt_pay_amt + $pay_amt;
$excel.="<tr>
<td style='text-align:center;'>$nn</td>
<td style='text-align:center;'>$bill_no</td>
<td style='text-align:center;'>$fromm - $tom</td>
<td style='text-align:center;'>$due</td>
<td style='text-align:center;'>$total_amount</td>
<td style='text-align:center;'>$pay_amt</td>
</tr>";
}}
$excel.="<tr>
<th colspan='4'>Grand Total</th>
<th style='text-align:center;'>$gt_amt</th>
<th style='text-align:center;'>$gt_pay_amt</th>
</tr>
</table>";
}

if($tp == 3)
{
$excel="<table border='1'>
<tr>
<th style='text-align:center;' colspan='6'>
Bill Detail  ($society_name)
</th>
</tr>
<tr>
<th style='text-align:center;'>#</th>
<th style='text-align:center;'>Bill No.</th>
<th style='text-align:center;'>Bill Date</th>
<th style='text-align:center;'>Due Date</th>
<th style='text-align:center;'>Total Amount</th>
<th style='text-align:center;'>Pay Amount</th>
</tr>";

$nn=0;
$gt_amt = 0;
$gt_pay_amt = 0;
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $s_user_id,"society_id"=>$s_society_id,"status"=>1);
$cursor3 = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$from2 = $collection['regular_bill']['bill_daterange_from'];
$to2 = $collection['regular_bill']['bill_daterange_to'];
$due_date = $collection['regular_bill']['due_date'];
$total_amount = (int)$collection['regular_bill']['g_total'];
$remaining_amt = (int)$collection['regular_bill']['remaining_amount'];
$fromm = date('d-M-Y',$from2->sec);
$tom = date('d-M-Y',$to2->sec);
$due = date('d-M-Y',$due_date->sec);
$pay_amt = $total_amount - $remaining_amt; 
if($m_from <= $from2 && $m_to >= $to2)
{
$nn++;
$gt_amt = $gt_amt + $total_amount;
$gt_pay_amt = $gt_pay_amt + $pay_amt;
$excel.="<tr>
<td style='text-align:center;'>$nn</td>
<td style='text-align:center;'>$bill_no</td>
<td style='text-align:center;'>$fromm - $tom</td>
<td style='text-align:center;'>$due</td>
<td style='text-align:center;'>$total_amount</td>
<td style='text-align:center;'>$pay_amt</td>
</tr>";
}}
$excel.="<tr>
<th colspan='4'>Grand Total</th>
<th style='text-align:center;'>$gt_amt</th>
<th style='text-align:center;'>$gt_pay_amt</th>
</tr>
</table>";
}
echo $excel;
}
//////////////////////// End my flat Bill Excel////////////////////////////////////

///////////////////////Start my flat receipt(Accounts)/////////////////////////////
function my_flat_receipt()
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
///////////////////////End my flat receipt(Accounts)//////////////////////////////

////////////////// Start My Flat receipt Excel/////////////////////////////////////
function my_flat_receipt_excel()
{
$this->layout="";
$filename=strtotime("now");
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

$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));


$this->loadmodel('ledger_sub_account');
$conditions=array("user_id"=>$s_user_id,"society_id"=>$s_society_id);
$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
$user_name = $collection['ledger_sub_account']['name'];
}


$excel="<table border='1'>
<tr>
<th colspan='9' style='text-align:center;'>
Bank Receipt Report</th>
</tr>

<tr>
<th>From : $from</th>
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

$total_credit = 0;
$total_debit = 0;
$this->loadmodel('bank_receipt');
$conditions=array("user_id"=>$auto_id,"society_id"=>$s_society_id,"amount_category_id"=>1);
$cursor1 = $this->bank_receipt->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$receipt_no = $collection['bank_receipt']['receipt_id'];
$transaction_id = (int)$collection['bank_receipt']['transaction_id'];	
$date = $collection['bank_receipt']['transaction_date'];
$prepaired_by_id = (int)$collection['bank_receipt']['prepaired_by'];
$member = (int)$collection['bank_receipt']['member'];
$narration = $collection['bank_receipt']['narration'];
$receipt_mode = $collection['bank_receipt']['receipt_mode'];
$receipt_instruction = $collection['bank_receipt']['receipt_instruction'];
$account_id = (int)$collection['bank_receipt']['sub_account_id'];
$amount = $collection['bank_receipt']['amount'];
$amount_category_id = (int)$collection['bank_receipt']['amount_category_id'];
$current_date = $collection['bank_receipt']['current_date'];  
                     
if($member == 1)
{
$received_from_id = (int)$collection['bank_receipt']['user_id'];
$ref = $collection['bank_receipt']['bill_reference'];
$ref = "Bill No:".$ref;
}     

$creation_date = date('d-m-Y',$current_date->sec);	         
$result_prb = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($prepaired_by_id)));
foreach ($result_prb as $collection) 
{
$prepaired_by_name = $collection['user']['user_name'];
}	

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
//$user_name = $collection['user']['user_name'];
$wing_id = (int)$collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	                  

$result_lsa2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($account_id)));						
foreach ($result_lsa2 as $collection) 
{
$account_no = $collection['ledger_sub_account']['name'];  
}
	
if($date >= $m_from && $date <= $m_to)
{
$tr_date = date('d-M-Y',$date->sec);
$total_debit = $total_debit + $amount;		

$excel.="<tr>
<td style='text-align:center;'>$receipt_no</td>
<td style='text-align:center;'>$tr_date</td>
<td width='15%' style='text-align:center;'>$narration</td>
<td style='text-align:center;'>$user_name&nbsp;&nbsp;&nbsp;&nbsp;$wing_flat</td>
<td style='text-align:center;'>$ref</td>
<td style='text-align:center;'>$receipt_mode</td>
<td style='text-align:center;'>$receipt_instruction</td>
<td style='text-align:center;'>$account_no</td>
<td style='text-align:center;'>$amount</td>
</tr>";			
}}
$excel.="<tr>
<th colspan='8'> Total</th>
<th style='text-align:center;'>$total_debit</th>
</tr>										 
</table>"; 		

echo $excel;
}
////////////////// End My Flat receipt Excel/////////////////////////////////////////

//////////////////////Start my flat receipt show (Accounts)////////////////////////
function my_flat_receipt_show()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	


$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
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



$this->set('user_id',$s_user_id);

$this->loadmodel('ledger_sub_account');
$conditions=array("user_id"=>$s_user_id,"society_id"=>$s_society_id);
$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
$user_name = $collection['ledger_sub_account']['name'];
}
$this->set('user_name',$user_name);


$this->loadmodel('bank_receipt');
$conditions=array("user_id"=>$auto_id,"society_id"=>$s_society_id,"amount_category_id"=>1);
$cursor1 = $this->bank_receipt->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


}
//////////////////////End my flat receipt show (Accounts)//////////////////////////////





//////////////////////////////// END MY FLAT MODULE///////////////////////////////////////////////////////////

/////////////////////////////////////////////////// START FINANCIAL REPORT MODULE /////////////////////////////////////

//////////////////// Start Trial Balance Excel///////////////////////////////////////
function trial_balance_excel()
{
$this->layout="";
$filename="Trial balance";
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
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)  
{
$society_name = $collection['society']['society_name'];
}

$from = $this->request->query('f');
$to = $this->request->query('t');
$tp = (int)$this->request->query('tp');

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

////////
if($tp == 1)
{
$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>
$society_name</th>
</tr>
<tr>
<th colspan='5' style='text-align:center;'>
Trial balance For The Period $from to $to
</th>
</tr>
<tr>
<th style='text-align:center;'>Account Name</th>
<th style='text-align:center;'>Opening Balance</th>
<th style='text-align:center;'>Debit</th>
<th style='text-align:center;'>Credit</th>
<th style='text-align:center;'>Closing balance</th>
</tr>";
$grand_total_debit = 0;
$grand_total_credit = 0;
$grand_total_opening_balance = 0;
$grand_total_closing_balance = 0;
$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>15);
$cursor3 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;

$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
foreach ($ledger1 as $collection) 
{
$amount1 = $collection['ledger']['amount'];
$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];
$op_date = $collection['ledger']['op_date'];
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}



if($receipt_id != 'O_B')
{
/*
$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));	
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/
if($table_name == "cash_bank")
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));				
}
else
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id)));	
}
foreach ($date_fetch as $collection) 
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
}	
}
else 
{
if($op_date < $from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}
else
{
if($ammount_type_id1 == 1)
{
$total_closing_balance = $total_closing_balance - $amount1;	
}
else if($ammount_type_id1 == 2)
{
$total_closing_balance = $total_closing_balance + $amount1;	
}
}
}

if($receipt_id != 'O_B')
{
if($date1 < $m_from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}

if($date1 >= $m_from && $date1 <= $m_to)
{
if($ammount_type_id1 == 1)
{
$total_debit1 = $total_debit1 + $amount1;	
$grand_total_debit = $grand_total_debit + $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_credit1 = $total_credit1 + $amount1;	
$grand_total_credit = $grand_total_credit + $amount1;
}
}	
}
}
if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_closing_balance + $total_opening_balance + $total_credit1 - $total_debit1;
$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;
$excel.="<tr><td style='text-align:center;'>          
$account_name
</td>
<td style='text-align:center;'>";
if($total_opening_balance > 0)
{
$total_opening_balance = $total_opening_balance.'Cr';
}
else if($total_opening_balance < 0)
{
$total_opening_balance = abs($total_opening_balance);
$total_opening_balance = $total_opening_balance.'Dr';
}
$excel.="$total_opening_balance</td>
<td style='text-align:center;'>$total_debit1</td>
<td style='text-align:center;'>$total_credit1</td>
<td>";
if($total_closing_balance > 0)
{
$total_closing_balance = $total_closing_balance.'Cr';
}
else if($total_closing_balance < 0)
{
$total_closing_balance = abs($total_closing_balance);
$total_closing_balance = $total_closing_balance.'Dr';
}
$excel.="$total_closing_balance</td>
</tr>";
}}	
$excel.="<tr>
<th style='text-align:center;'>Total</th>
<th style='text-align:center;'>"; 
if($grand_total_opening_balance > 0)
{
$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
}
else if($grand_total_opening_balance < 0)
{
$grand_total_opening_balance = abs($grand_total_opening_balance);
$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
}
$excel.="$grand_total_opening_balance</th>
<th style='text-align:center;'>$grand_total_debit</th>
<th style='text-align:center;'>$grand_total_credit</th>
<th style='text-align:center;'>";
if($grand_total_closing_balance > 0)
{
$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
}
else if($grand_total_closing_balance < 0)
{
$grand_total_closing_balance = abs($grand_total_closing_balance);
$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
}
$excel.="$grand_total_closing_balance</th>
</tr>
</table>";
	
}
///////
if($tp == 2)
{
$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>
$society_name
</th>
</tr>
<tr>
<th colspan='5' style='text-align:center;'>
Trial balance for the Period $from to $to
</th>
</tr>
<tr>
<th style='text-align:center;'>Account Name</th>
<th style='text-align:center;'>Opening Balance</th>
<th style='text-align:center;'>Debit</th>
<th style='text-align:center;'>Credit</th>
<th style='text-align:center;'>Closing balance</th>
</tr>";
$grand_total_debit = 0;
$grand_total_credit = 0;
$grand_total_opening_balance = 0;
$grand_total_closing_balance = 0;
$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>34);
$cursor4 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor4 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
foreach ($ledger1 as $collection) 
{
$amount1 = $collection['ledger']['amount'];
$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];
$op_date = $collection['ledger']['op_date']; 
$table_name = $collection['ledger']['table_name']; 
if($table_name == "cash_bank")
{ 
$module_id = (int)$collection['ledger']['module_id']; 
}
if($receipt_id != 'O_B')
{
/*
$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));	
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/
if($table_name == "cash_bank")
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id)));	
}			
foreach ($date_fetch as $collection) 
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
}		
}
else
{
if($op_date < $from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}
else
{
if($ammount_type_id1 == 1)
{
$total_closing_balance = $total_closing_balance - $amount1;	
}
else if($ammount_type_id1 == 2)
{
$total_closing_balance = $total_closing_balance + $amount1;	
}
}
}



if($receipt_id != 'O_B')
{		
if($date1 < $m_from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}

if($date1 >= $m_from && $date1 <= $m_to)
{
if($ammount_type_id1 == 1)
{
$total_debit1 = $total_debit1 + $amount1;	
$grand_total_debit = $grand_total_debit + $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_credit1 = $total_credit1 + $amount1;	
$grand_total_credit = $grand_total_credit + $amount1;
}
}	
}
}
if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_closing_balance + $total_opening_balance + $total_credit1 - $total_debit1;
$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;
$excel.="<tr>
<td style='text-align:center;'>          
$account_name
</td><td style='text-align:center;'>"; 
if($total_opening_balance > 0)
{
$total_opening_balance = $total_opening_balance.'Cr';
}
else if($total_opening_balance < 0)
{
$total_opening_balance = abs($total_opening_balance);
$total_opening_balance = $total_opening_balance.'Dr';
}
$excel.="$total_opening_balance</td>
<td style='text-align:center;'>$total_debit1</td>
<td style='text-align:center;'>$total_credit1</td>
<td style='text-align:center;'>";
if($total_closing_balance > 0)
{
$total_closing_balance = $total_closing_balance.'Cr';
}
else if($total_closing_balance < 0)
{
$total_closing_balance = abs($total_closing_balance);
$total_closing_balance = $total_closing_balance.'Dr';
}
$excel.="$total_closing_balance</td>
</tr>";
}}
$excel.="
<tr>
<th style='text-align:center;'>Total</th>
<th style='text-align:center;'>"; 
if($grand_total_opening_balance > 0)
{
$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
}
else if($grand_total_opening_balance < 0)
{
$grand_total_opening_balance = abs($grand_total_opening_balance);
$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
}
$excel.="$grand_total_opening_balance</th>
<th style='text-align:center;'>$grand_total_debit</th>
<th style='text-align:center;'>$grand_total_credit</th>
<th style='text-align:center;'>";
if($grand_total_closing_balance > 0)
{		
$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
}
else if($grand_total_closing_balance < 0)
{
$grand_total_closing_balance = abs($grand_total_closing_balance);
$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
}
$excel.="$grand_total_closing_balance</th>
</tr>
</table>";
}
if($tp == 4)
{
$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>
$society_name</th>
</tr>

<tr>
<th colspan='5' style='text-align:center;'>
Trial balance for the Period $from to $to
</th>
</tr>
<tr>
<th style='text-align:center;'>Account Name</th>
<th style='text-align:center;'>Opening Balance</th>
<th style='text-align:center;'>Debit</th>
<th style='text-align:center;'>Credit</th>
<th style='text-align:center;'>Closing balance</th>
</tr>";

$grand_total_debit = 0;
$grand_total_credit = 0;
$grand_total_opening_balance = 0;
$grand_total_closing_balance = 0;

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>33);
$cursor5 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor5 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;

$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
foreach ($ledger1 as $collection) 
{
$amount1 = $collection['ledger']['amount'];
$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];
$op_date = $collection['ledger']['op_date'];
$table_name = $collection['ledger']['table_name']; 
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
} 
 
if($receipt_id != 'O_B')
{
/*
$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));	
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/
if($table_name == "cash_bank")
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));
}
else
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id)));
}

				
foreach ($date_fetch as $collection) 
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
}		
}
else
{
if($op_date < $from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}
else
{
if($ammount_type_id1 == 1)
{
$total_closing_balance = $total_closing_balance - $amount1;	
}
else if($ammount_type_id1 == 2)
{
$total_closing_balance = $total_closing_balance + $amount1;	
}
}
}


if($receipt_id != 'O_B')
{
if($date1 < $m_from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}

if($date1 >= $m_from && $date1 <= $m_to)
{
if($ammount_type_id1 == 1)
{
$total_debit1 = $total_debit1 + $amount1;	
$grand_total_debit = $grand_total_debit + $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_credit1 = $total_credit1 + $amount1;	
$grand_total_credit = $grand_total_credit + $amount1;
}
}	
}
}
if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_closing_balance + $total_opening_balance + $total_credit1 - $total_debit1;
$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;

$excel.="<tr>
<td style='text-align:center;'>$account_name</td>
<td style='text-align:center;'>";
if($total_opening_balance > 0)
{
$total_opening_balance = $total_opening_balance.'Cr';
}
else if($total_opening_balance < 0)
{
$total_opening_balance = abs($total_opening_balance);
$total_opening_balance = $total_opening_balance.'Dr';
}
$excel.="$total_opening_balance</td>
<td style='text-align:center;'>$total_debit1</td>
<td style='text-align:center;'>$total_credit1</td>
<td style='text-align:center;'>";
if($total_closing_balance > 0)
{
$total_closing_balance = $total_closing_balance.'Cr';
}
else if($total_closing_balance < 0)
{
$total_closing_balance = abs($total_closing_balance);
$total_closing_balance = $total_closing_balance.'Dr';
}
$excel.="$total_closing_balance</td>
</tr>";
}}
$excel.="<tr>
<th style='text-align:center;'>Total</th>
<th style='text-align:center;'>"; 
if($grand_total_opening_balance > 0)
{
$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
}
else if($grand_total_opening_balance < 0)
{
$grand_total_opening_balance = abs($grand_total_opening_balance);
$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
}
$excel.="$grand_total_opening_balance</th>
<th style='text-align:center;'>$grand_total_debit</th>
<th style='text-align:center;'>$grand_total_credit</th>
<th style='text-align:center;'>";
if($grand_total_closing_balance > 0)
{
$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
}
else if($grand_total_closing_balance < 0)
{
$grand_total_closing_balance = abs($grand_total_closing_balance);
$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
}
$excel.="$grand_total_closing_balance</th>
</tr>
</table>";
}

if($tp == 3)
{
$excel="
<table border='1'>
<tr>
<th colspan='6' style='text-align:center;'>
$society_name
</th>
</tr>
<tr>
<th colspan='6' style='text-align:center;'>
Trial balance for the Period $from to $to
</th>
</tr>

<tr>
<th style='text-align:center;'>Account Name</th>
<th style='text-align:center;'>Sub Account Name</th>
<th style='text-align:center;'>Opening Balance</th>
<th style='text-align:center;'>Debit</th>
<th style='text-align:center;'>Credit</th>
<th style='text-align:center;'>Closing balance</th>
</tr>
";
$grand_total_debit = 0;
$grand_total_credit = 0;
$grand_total_opening_balance = 0;
$grand_total_closing_balance = 0;
$this->loadmodel('accounts_category');
$order=array('accounts_category.auto_id'=> 'ASC');
$cursor2 = $this->accounts_category->find('all',array('order' =>$order));
foreach($cursor2 as $collection)
{
$auto_id11 = (int)$collection['accounts_category']['auto_id'];
$result11 = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group_fetch'),array('pass'=>array($auto_id11)));
foreach($result11 as $collection)
{
$auto_id22 = (int)$collection['accounts_group']['auto_id'];
$result22 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array($auto_id22)));
foreach($result22 as $collection)
{
$auto_id3 = (int)$collection['ledger_account']['auto_id'];
$account_name = $collection['ledger_account']['ledger_name'];

if($auto_id3 == 34 || $auto_id3 == 15 || $auto_id3 == 33 || $auto_id3 == 35)
{	
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$n=1;
$result_lsa1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch2'),array('pass'=>array($auto_id3)));
foreach ($result_lsa1 as $collection) 
{
$sub_id1 = (int)$collection['ledger_sub_account']['auto_id'];
$sub_account_name1 = $collection['ledger_sub_account']['name'];

$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($sub_id1)));		
foreach ($ledger1 as $collection) 
{
$amount1 = $collection['ledger']['amount'];
$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];
$op_date = $collection['ledger']['op_date'];
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}
if($receipt_id != 'O_B')
{
/*	
$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));	
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/
if($table_name == "cash_bank")
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else
{
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id)));				
}

foreach ($date_fetch as $collection) 
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
}	
}
else
{
if($op_date < $from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}
else
{
if($ammount_type_id1 == 1)
{
$total_closing_balance = $total_closing_balance - $amount1;	
}
else if($ammount_type_id1 == 2)
{
$total_closing_balance = $total_closing_balance + $amount1;	
}
}
}


if($receipt_id != 'O_B')
{	
if($date1 < $m_from)
{
if($ammount_type_id1 == 1)
{
$total_opening_balance = $total_opening_balance - $amount1;
}
else if($ammount_type_id1 == 2)
{
$total_opening_balance = $total_opening_balance + $amount1;	
}
}

if($date1 >= $m_from && $date1 <= $m_to)
{
if($ammount_type_id1 == 1)
{
$total_debit1 = $total_debit1 + $amount1;	
}
else if($ammount_type_id1 == 2)
{
$total_credit1 = $total_credit1 + $amount1;	
}
}	
}
}
}
if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_closing_balance + $total_opening_balance + $total_credit1 - $total_debit1; 
$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;  

$excel.="<tr>
<td>$account_name</td>
<td></td>
<td>"; 
if($total_opening_balance > 0)
{
$total_opening_balance = $total_opening_balance.'Cr';
}
else if($total_opening_balance < 0) 
{ 
$total_opening_balance = abs($total_opening_balance);
$total_opening_balance = $total_opening_balance.'Dr';
}
$excel.="</td>
<td></td>
<td></td>
<td>";
if($total_closing_balance > 0)
{
$total_closing_balance = $total_closing_balance.'Cr';
}
else if($total_closing_balance < 0)
{
$total_closing_balance = abs($total_closing_balance);
$total_closing_balance = $total_closing_balance.'Dr';
}
$excel.="</td>
</tr>
<tr>
<td colspan='6'>";
$excel.="
<table border='1'> 
";

$n++;
$total_sub_credit = 0;
$total_sub_debit = 0;
$total_sub_opening_balance = 0;
$total_sub_closing_balance = 0;
$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch2'),array('pass'=>array($auto_id3)));			
foreach ($result_lsa as $collection) 
{
$sub_id = (int)$collection['ledger_sub_account']['auto_id'];
$sub_account_name = $collection['ledger_sub_account']['name'];

$debit_sub = 0;
$credit_sub = 0;
$opening_balance_sub = 0;
$closing_balance_sub = 0;
$ledger2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($sub_id)));			
foreach ($ledger2 as $collection) 
{
$amount = $collection['ledger']['amount'];
$ammount_type_id = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id_s = (int)$collection['ledger']['receipt_id'];
$op_date2 = $collection['ledger']['op_date'];
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}



if($receipt_id_s != 'O_B')
{	
/*
$module2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));  
foreach ($module2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/	
if($table_name == "cash_bank")
{
$date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id_s,$module_id)));
}
else
{
$date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id_s)));
}	
foreach ($date_fetch2 as $collection) 
{
$date2 = @$collection[$table_name]['transaction_date'];
if(empty($date2))
{
$date2 = @$collection[$table_name]['posting_date'];	
}
if(empty($date2))
{
$date2 = @$collection[$table_name]['purchase_date'];	
}
if(empty($date2))
{
$date2 = @$collection[$table_name]['date'];	
}
}	
}
else
{
if($op_date2 < $from)
{
if($ammount_type_id == 1)
{
$opening_balance_sub = $opening_balance_sub - $amount;
}
else if($ammount_type_id == 2)
{
$opening_balance_sub = $opening_balance_sub + $amount;	
}
}
else
{
if($ammount_type_id == 1)
{
$closing_balance_sub = $closing_balance_sub - $amount;	
}
else if($ammount_type_id == 2)
{
$closing_balance_sub = $closing_balance_sub + $amount;	
}
}	
}

if($receipt_id_s != 'O_B')
{
if($date2 < $m_from)
{
if($ammount_type_id == 1)
{
$opening_balance_sub = $opening_balance_sub - $amount;
}
else if($ammount_type_id == 2)
{
$opening_balance_sub = $opening_balance_sub + $amount;
}
}

if($date2 >= $m_from && $date2 <= $m_to)
{
if($ammount_type_id == 1)
{
$debit_sub = $debit_sub + $amount;
$total_sub_debit = $total_sub_debit + $amount;
$grand_total_debit = $grand_total_debit + $amount;
}
else if($ammount_type_id == 2)
{
$credit_sub = $credit_sub + $amount;
$total_sub_credit = $total_sub_credit + $amount;
$grand_total_credit =$grand_total_credit + $amount;
}
}
}
}
if($credit_sub != 0 || $debit_sub != 0)
{
$closing_balance_sub = $closing_balance_sub + $opening_balance_sub - $debit_sub + $credit_sub;
$total_sub_closing_balance = $total_sub_closing_balance + $closing_balance_sub;
$total_sub_opening_balance = $total_sub_opening_balance + $opening_balance_sub;
$excel.="<tr>
<td></td>
<td>$sub_account_name</td>
<td>";
if($opening_balance_sub > 0)
{
$opening_balance_sub = $opening_balance_sub.'Cr';
}
else if($opening_balance_sub < 0)
{
$opening_balance_sub = abs($opening_balance_sub);
$opening_balance_sub = $opening_balance_sub.'Dr';
}
$excel.="$opening_balance_sub</td>
<td>$debit_sub</td>
<td>$credit_sub</td>
<td>";
if($closing_balance_sub > 0)
{
$closing_balance_sub = $closing_balance_sub.'Cr';
}
else if($closing_balance_sub < 0)
{
$closing_balance_sub = abs($closing_balance_sub);
$closing_balance_sub = $closing_balance_sub.'Dr';
}
$excel.="$closing_balance_sub</td>
</tr>";
}}
/*
$excel.="<tr>
<th colspan=''>Total</th>
<th>";
if($total_sub_opening_balance > 0)
{
$total_sub_opening_balance = $total_sub_opening_balance.'Cr';
}
else if($total_sub_opening_balance < 0)
{
$total_sub_opening_balance = abs($total_sub_opening_balance);
$total_sub_opening_balance = $total_sub_opening_balance.'Dr';
}
$excel.="$total_sub_opening_balance</th>
<th>$total_sub_debit</th>
<th>$total_sub_credit</th>
<th>";
if($total_sub_closing_balance > 0)
{
$total_sub_closing_balance = $total_sub_closing_balance.'Cr';
}
else if($total_sub_closing_balance < 0)
{
$total_sub_closing_balance = abs($total_sub_closing_balance);
$total_sub_closing_balance = $total_sub_closing_balance.'Dr';
}
$excel.="$total_sub_closing_balance</th>
</tr>*/



$excel.="</table>
</td>
</tr>";
}}
else
{
$total_debit = 0;
$total_credit = 0;
$total_opening_balance2 = 0;
$total_closing_balance2 = 0;
$ledger_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch2'),array('pass'=>array($auto_id3)));	
foreach ($ledger_fetch2 as $collection) 
{
$amount = $collection['ledger']['amount'];
$amount_type_id = (int)$collection['ledger']['amount_category_id'];
//$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id2 = (int)$collection['ledger']['receipt_id'];
$op_date3 = $collection['ledger']['op_date'];
$table_name = $collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];
}


if($receipt_id2 != 'O_B')
{
/*
$module_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));		
foreach ($module_fetch2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
*/
if($table_name == "cash_bank")
{
$module_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' =>'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id2,$module_id)));
}
else
{
$module_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' =>'module_main_fetch3'),array('pass'=>array($table_name,$receipt_id2)));
}
foreach ($module_fetch3 as $collection) 
{
$date3 = @$collection[$table_name]['transaction_date'];
if(empty($date3))
{
$date3 = @$collection[$table_name]['posting_date'];	
}
if(empty($date3))
{
$date3 = @$collection[$table_name]['purchase_date'];	
}
if(empty($date3))
{
$date3 = @$collection[$table_name]['date'];	
}
}		
}
else
{
if($op_date3 < $from)
{
if($amount_type_id == 1)
{
$total_opening_balance2 = $total_opening_balance2 - $amount;
}
else if($amount_type_id == 2)
{
$total_opening_balance2 = $total_opening_balance2 + $amount;	
}
}
else
{
if($amount_type_id == 1)
{
$total_closing_balance2 = $total_closing_balance2 - $amount;	
}
else if($amount_type_id == 2)
{
$total_closing_balance2 = $total_closing_balance2 + $amount;	
}
}		
}


if($receipt_id2 != 'O_B')
{
if($date3 < $m_from)
{
if($amount_type_id == 1)
{
$total_opening_balance2 = $total_opening_balance2 - $amount;
}
else if($amount_type_id == 2)
{
$total_opening_balance2 = $total_opening_balance2 + $amount;
}
}

if(@$date3 >= $m_from && @$date3 <= $m_to)
{
if($amount_type_id == 1)
{
$total_debit = $total_debit + $amount;
$grand_total_debit = $grand_total_debit + $amount;
}
else if($amount_type_id == 2)
{
$total_credit = $total_credit + $amount;
$grand_total_credit = $grand_total_credit + $amount;
}
}
}
}
if($total_debit !=0 || $total_credit != 0)
{ 
$total_closing_balance2 = $total_closing_balance2 + $total_opening_balance2 + $total_credit - $total_debit;
$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance2;
$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance2;

$excel.="<tr>
<td>$account_name</td>
<td></td>
<td>"; 
if($total_opening_balance2 > 0)
{
$total_opening_balance2 = $total_opening_balance2.'Cr';
}
else if($total_opening_balance2 < 0)
{
$total_opening_balance2 = abs($total_opening_balance2);
$total_opening_balance2 = $total_opening_balance2.'Dr';
}
$excel.="$total_opening_balance2</th>
<td>$total_debit</td>
<td>$total_credit</td>
<td>";
if($total_closing_balance2 > 0)
{
$total_closing_balance2 = $total_closing_balance2.'Cr';
}
else if($total_closing_balance2 < 0)
{
$total_closing_balance2 = abs($total_closing_balance2);
$total_closing_balance2 = $total_closing_balance2.'Dr';
}
$excel.="$total_closing_balance2</td>
</tr>";  
}}}}}
$excel.="<tr>
	<th colspan=''>Grand Total</th>
	<th></th>
    <th>";
	if($grand_total_opening_balance > 0)
	{
	$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
	}
	else if($grand_total_opening_balance < 0)
	{
	$grand_total_opening_balance = abs($grand_total_opening_balance);
	$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
	}
	$excel.="$grand_total_opening_balance</th>   
	<th>$grand_total_debit</th>
	<th>$grand_total_credit</th>
	<th>"; 
	if($grand_total_closing_balance > 0)
	{
	$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
	}
	else if($grand_total_closing_balance < 0)
	{
	$grand_total_closing_balance = abs($grand_total_closing_balance);
	$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
	}
	$excel.="$grand_total_closing_balance</th>
	</tr>

	</table>";


/////////////////
}

echo $excel;

}
//////////////////// End Trial Balance Excel/////////////////////////////////////////

/////////////////////////////////////////// Start Trial Balance (Accounts) /////////////////////////////////////////////////////////////////////////////
function trial_balance()
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
////////////////////////// End Trial Balance (Accounts) //////////////////////////////////////////////////////////

//////////////////// Start Trial Balance Ajax Show (Accounts) //////////////////////
function trial_balance_ajax_show()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$wise = $this->request->query('wise');


$this->set('wise',$wise);
$this->set('from_d',$from);
$this->set('to_d',$to);

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor1=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);		

$this->loadmodel('accounts_category');
$order=array('accounts_category.auto_id'=> 'ASC');
$cursor2 = $this->accounts_category->find('all',array('order' =>$order));
$this->set('cursor2',$cursor2);

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>15);
$cursor3 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>34);
$cursor4 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor4',$cursor4);


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id"=>33);
$cursor5 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor5',$cursor5);





}

////////////////////////////////////////// End Trial Balance Ajax Show (Accounts) //////////////////////////////////////////////////////////////////////








/////////////////////////////////////////////////// END FINANCIAL REPORT MODULE /////////////////////////////////////



}
?>