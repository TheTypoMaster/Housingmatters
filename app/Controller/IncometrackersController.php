<?php
App::import('Controller','Hms');
class IncometrackersController extends HmsController {
var $helpers = array('Html', 'Form','Js');

public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);
var $name = 'Incometrackers';

/////////////////////////Start It Regular Bill (Accounts) //////////////////////////////////////
function it_regular_bill()
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


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$socct1=$this->society->find('all',array('conditions'=>$conditions));
$this->set('socct1',$socct1);


$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$flat_tpp=$this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('flat_tpp',$flat_tpp);


$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"status"=>0,"bill_for_user"=>$s_user_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$ele_id=(int)@$collection['regular_bill']['receipt_id'];
}

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

$this->loadmodel('regular_bill');
$order=array('regular_bill.regular_bill_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach ($cursor as $collection) 
{
$d_from = $collection['regular_bill']['bill_daterange_from'];
$d_to = $collection['regular_bill']['bill_daterange_to'];
}

//$this->set('d_from',@$d_from);
if(!empty($d_from))
{
$datefb = date('Y-m-d',strtotime($d_from));
$datetb = date('Y-m-d',strtotime($d_to));

$this->set('datefb',$datefb);
$this->set('datetb',$datetb);
}
if(isset($this->request->data['sub1']))
{
$from = $this->request->data['from'];
@$penalty = $this->request->data['pen'];
$due_date = $this->request->data['due_date'];
$description = $this->request->data['description'];
$period_id = (int)$this->request->data['bill_p'];
$fromm = date("Y-m-d", strtotime($from));
$fromm = new MongoDate(strtotime($fromm));
$bill_for = (int)$this->request->data['bill_for'];
if($bill_for == 1)
{
$this->loadmodel('wing');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->wing->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];
$wing_for_bill = @$this->request->data['wing'.$wing_id];
$wing_arr[] = $wing_for_bill;
}
}
@$wing_imp = implode(",",@$wing_arr);
if($period_id == 1)
{
$to = date('Y-m-d', strtotime("+1 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 3)
{
$to = date('Y-m-d', strtotime("+3 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 4)
{
$to = date('Y-m-d', strtotime("+6 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 2)
{
$to = date('Y-m-d', strtotime("+2 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 5)
{
$to = date('Y-m-d', strtotime("+12 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}

$tom = date("Y-m-d", strtotime($to));
$tom = new MongoDate(strtotime($tom));

$due_date55 = date("Y-m-d", strtotime($due_date));
$due_date55 = new MongoDate(strtotime($due_date55));

$f1=$this->encode($from,'housingmatters');
$t1=$this->encode($to,'housingmatters');
$due1=$this->encode($due_date,'housingmatters');
$desc1=$this->encode($description,'housingmatters');
$p_id = $this->encode($period_id,'housingmatters');
$pen = $this->encode($penalty,'housingmatters');
$wing_imp_en = $this->encode($wing_imp,'housingmatters');
$bill_for_en = $this->encode($bill_for,'housingmatters');

$this->response->header('Location','regular_bill_view2?f='.$f1.'&t='.$t1.'&due='.$due1.'&d='.$desc1.'&p='.$p_id.'&pen='.$pen.'&wi='.$wing_imp_en.'&bi='.$bill_for_en.' ');
}

$this->loadmodel('income_head');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);		
		
$this->loadmodel('ledger_sub_account');
$conditions=array("society_id"=>$s_society_id, "ledger_id" => 35);
$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);			
		
$this->loadmodel('terms_condition');
$conditions=array("society_id"=>$s_society_id,"status" => 1);
$cursor3=$this->terms_condition->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);				

$this->loadmodel('bill_period');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor4 = $this->bill_period->find('all',array('conditions'=>$conditions));
$this->set('cursor4',$cursor4);


$this->loadmodel('wing');
$conditions=array("society_id" => $s_society_id);
$cursor5 = $this->wing->find('all',array('conditions'=>$conditions));
$this->set('cursor5',$cursor5);



$this->loadmodel('reference');
$conditions=array("auto_id"=>2);
$cursor = $this->reference->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_period_arr = $collection['reference']['reference'];
}
$this->set('bill_period_arr',$bill_period_arr);
}
/////////////////////// End It Regular Bill (Accounts) ////////////////////////////////

////////////////////////// Start Regular Bill View2 ////////////////////////////////////
function regular_bill_view2()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$this->ath();
$webroot_path=$this->requestAction(array('controller' => 'Hms', 'action' => 'webroot_path'));

$this->loadmodel('penalty');
$condition=array('society_id'=>$s_society_id);
$result5=$this->penalty->find('all',array('conditions'=>$condition)); 
$this->set('cursor5',$result5);

$from3 = $this->request->query('f');
$to3 = $this->request->query('t');
$due_date3 = $this->request->query('due');
$desc3 = $this->request->query('d');
$p_id = $this->request->query('p');
$pen = $this->request->query('pen');
$wing_arr_en = $this->request->query('wi');
$bill_for_en = $this->request->query('bi');

$from = $this->decode($from3,'housingmatters');
$to = $this->decode($to3,'housingmatters');
$due_date = $this->decode($due_date3,'housingmatters');
$desc = $this->decode($desc3,'housingmatters');
$p_id = (int)$this->decode($p_id,'housingmatters');
$penalty = (int)$this->decode($pen,'housingmatters');
$wing_arr_im = $this->decode($wing_arr_en,'housingmatters');
$bill_for = (int)$this->decode($bill_for_en,'housingmatters');

$this->set('bill_for',$bill_for);
$this->set('wing_arr_im',$wing_arr_im);
$this->set('p_id',$p_id);
$this->set('from',$from);
$this->set('to',$to);
$this->set('due_date',$due_date);
$this->set('desc',$desc);
$this->set('penalty',$penalty);

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor11 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor11',$cursor11);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor12 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor12',$cursor12);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
$society_reg_no = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
$pen_per = (int)@$collection['society']['tax'];
$per_type = (int)@$collection['society']['tax_type'];
$sig_img = @$collection['society']['signature'];
$log_img = @$collection['society']['logo'];
$sig_title = @$collection['society']['sig_title'];
$bank_name = @$collection['society']['bank_name'];
$bank_branch = @$collection['society']['branch'];
$account_number = @$collection['society']['ac_name'];
$ifsc_code = @$collection['society']['ifsc_code'];
}
$this->set('pen_per',$pen_per);
$this->set('per_type',$per_type);
$this->set('society_name',$society_name);
$this->set('society_reg_no',$society_reg_no);
$this->set('society_address',$society_address);

$this->loadmodel('user');
$order=array('user.user_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id, "tenant" => 1,"deactive"=>0);
$cursor1 = $this->user->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('cursor1',$cursor1);

$this->loadmodel('regular_bill');
$order=array('regular_bill.regular_bill_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id);
$cursor2 = $this->regular_bill->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('cursor2',$cursor2);

if(isset($this->request->data['sub']))
{

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$income_head_arr = @$collection['society']['income_head'];
$terms_arr = @$collection['society']['terms_conditions'];
$pen_per2 = (int)@$collection['society']['tax'];
$per_type2 = (int)@$collection['society']['tax_type'];
$society_address = $collection['society']['society_address'];
$society_sig = $collection['society']['signature'];
$society_email = $collection['society']['society_email'];
$society_phone = $collection['society']['society_phone'];
}
$bill_for = (int)$this->request->data['bill_for'];
$wing_arr_imp = $this->request->data['wing_ar'];
$from = $this->request->data['from'];
$to = $this->request->data['to'];
$due_date = $this->request->data['due'];
$description = $this->request->data['desc'];
$gtamt = @$this->request->data['gt'];
$penalty = (int)$this->request->data['penalty'];
$p_id = (int)$this->request->data['p_type'];

if($p_id == 1)
{
$multi = 1;
}
if($p_id == 2)
{
$multi = 2;
}
if($p_id == 3)
{
$multi = 3;
}
if($p_id == 4)
{
$multi = 6;
}
if($p_id == 5)
{
$multi = 12;
}

$sms_from = date('dM',strtotime($from));
$sms_to = date('dMy',strtotime($to));
$sms_due = date('dMy',strtotime($due_date));

$dueeed = $due_date;
$due_date_msg = $due_date;

$m_from = date("Y-m-d", strtotime($from));

$m_to = date("Y-m-d", strtotime($to));

$due_date = date("Y-m-d", strtotime($due_date));

$this->loadmodel('regular_bill');
$order=array('regular_bill.one_time_id'=> 'DESC');
$cursor=$this->regular_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last10=$collection['regular_bill']["one_time_id"];
}
if(empty($last10))
{
$one=0;
}	
else
{	
$one=$last10;
}
$one++;
////////////////////////// BILL FOR 2///////////////////////////////////////////////
if($bill_for == 2)
{
$this->loadmodel('user');
$order=array('user.user_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id,"tenant" => 1,"deactive"=>0);
$cursor = $this->user->find('all',array('conditions'=>$conditions,'order'=>$order));
foreach($cursor as $collection)
{
$multi_flat = array();
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$flat_id = (int)$collection['user']['flat'];
$wing_id = (int)$collection['user']['wing'];
$mobile = $collection['user']['mobile'];
$to_mail = $collection['user']['email'];
$wing_flat = $this->wing_flat($wing_id,$flat_id);
$multi_flat = @$collection['user']['multiple_flat'];

$rrr = (int)sizeof($multi_flat);
if($rrr == 0)
{
$multi_flat[] = array($wing_id,$flat_id);	
}

for($g=0; $g<sizeof($multi_flat); $g++)
{
$mul_flat2 = $multi_flat[$g];
$wing_id = (int)$mul_flat2[0];
$flat_id = (int)$mul_flat2[1];






$maint_ch = 0;

$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id, "flat_id" => $flat_id, "wing_id" => $wing_id);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$noc_ch_id = (int)@$collection['flat']['noc_ch_tp'];
$sq_feet = (int)$collection['flat']['flat_area'];
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id, "auto_id" => $flat_type_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$charge = @$collection['flat_type']['charge'];
$noc_charge = @$collection['flat_type']['noc_charge'];
}

$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'DESC');
$cursor=$this->regular_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last111=$collection['regular_bill']["receipt_id"];
}
if(empty($last111))
{
$regular_bill_id11=1000;
}	
else
{	
$regular_bill_id11=$last111;
}
$regular_bill_id11++;
$current_date11 = date('Y-m-d');
//$current_date11 = new MongoDate(strtotime($current_date11));
/////////////////////////////////////

$income_headd2 = array();
for($s=0; $s<sizeof($income_head_arr); $s++)
{
$auto_id_in = (int)$income_head_arr[$s];

$ih_amt = (int)$this->request->data['ih'.$auto_id_in.$user_id];

$income_headd = array($auto_id_in,$ih_amt);
$income_headd2[] = $income_headd;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'ASC');
$cursor=$this->ledger->find('all',array('order' =>$order));
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $ih_amt, "amount_category_id" => 2,
"table_name" => "regular_bill", "account_type" => 2, "account_id" => $auto_id_in, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);
}
///////////////////////////////////////
if($noc_ch_id == 2)
{
$noc_amt = (int)$this->request->data['noc'.$user_id];

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'ASC');
$cursor=$this->ledger->find('all',array('order' =>$order));
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $noc_amt, "amount_category_id" => 2,
"table_name" => "regular_bill", "account_type" => 2, "account_id" => 43, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);

$income_headd = array(43,$noc_amt);
$income_headd2[] = $income_headd;
}
////////////////////////////////////
//$current_date = new MongoDate(strtotime(date("Y-m-d")));
$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"bill_for_user"=>$user_id,"status"=>0,"flat_id"=>$flat_id);
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
//$due_amount11 = (int)$collection['regular_bill']['remaining_amount'];
$due_date11 = @$collection['regular_bill']['due_date'];
$from_due = @$collection['regular_bill']['bill_daterange_from'];
$tax_arrears = (int)@$collection['regular_bill']['accumulated_tax'];
$arrear_amt = (int)@$collection['regular_bill']['arrears_amt'];
$pr_amt = (int)@$collection['regular_bill']['current_bill_amt'];
$previous_penalty_amt = (int)@$collection['regular_bill']['current_tax'];

}
$cur_date = date('Y-m-d');
//$cur_datec = new MongoDate(strtotime($cur_date));

/////////////// Penalty ///////////////////
if($penalty == 1)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$from_due_date = date('Y-m-d',strtotime(@$from_due));

$penalty_amt = (int)$this->request->data['penalty'.$user_id];

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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => @$penalty_amt, "amount_category_id" => 2, 
"table_name" => "regular_bill", "account_type"=> 2, "account_id" => 41, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);









}

/////////////////////End Penalty //////////////////////////////


//$grand_total = $total_amt + $total_due_amount;

$over_due_amt = (int)$this->request->data['due'.$user_id];
/*
if(@$over_due_amt > 0)
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" =>$over_due_amt, "amount_category_id" => 2,"table_name" => "regular_bill", "account_type" => 2, "account_id" => 13, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);
}
*/
$total_due_amount = (int)@$penalty_amt + $over_due_amt;

$current_date13 = date('Y-m-d');
$regular_bill_id13 = (int)$this->autoincrement('regular_bill','regular_bill_id');

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "user_id" => $user_id, "ledger_id" => 34);
$cursor=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$l_id =  (int)$collection['ledger_sub_account']['auto_id'];
}

$current_bill_amt2 = (int)$this->request->data['tt'.$user_id];

$grand_total = (int)$this->request->data['gtt'.$user_id];

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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $current_bill_amt2, "amount_category_id" => 1, 
"table_name" => "regular_bill", "account_type"=> 1, "account_id" => @$l_id, "current_date" => $current_date13,"society_id" => $s_society_id,"module_name"=>"Regular Bill","penalty"=>"NO"));
$this->ledger->saveAll($multipleRowData);


if($penalty == 1)
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => @$penalty_amt, "amount_category_id" => 1, 
"table_name" => "regular_bill", "account_type"=> 1, "account_id" => @$l_id, "current_date" => $current_date13,"society_id" => $s_society_id,"module_name"=>"Regular Bill","penalty"=>"YES"));
$this->ledger->saveAll($multipleRowData);

}

$total_amt = (int)$this->request->data['tt'.$user_id];



$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array('status'=>1),array("society_id"=>$s_society_id,"bill_for_user"=>$user_id,"status"=>0,"flat_id"=>$flat_id));

///////////////////////////////////////////////
if($one > 1)
{
$from_due2 = date('Y-m-d',strtotime(@$from_due));
}
else
{
$from_due2 = "2000-01-01";
$from_due2 = date('Y-m-d',strtotime($from_due2));
$arrear_amt = 0;
$tax_arrears = 0;
}
$opn_principal_amt = 0;
$opn_penlty_amt = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id,"account_id" => $l_id);
$cursor=$this->ledger->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$receipt_id = "";
$op_date = "";
$op_date2 = "";
$op_amt = "";
$pen_type="";

$receipt_id = @$collection['ledger']['receipt_id'];

if($receipt_id == "O_B")
{
$op_date = $collection['ledger']['op_date'];
$op_date2 = date('Y-m-d',$op_date->sec);
$op_amt = $collection['ledger']['amount'];
@$pen_type = @$collection['ledger']['penalty'];
$amoun_cat_id = (int)@$collection['ledger']['amount_category_id'];

if($op_date2 <= $m_from && $one == 1)
{
if($amoun_cat_id == 1)
{
if($pen_type == "YES")
{
$opn_penlty_amt= $opn_penlty_amt + $op_amt;
}
else
{
$opn_principal_amt= $opn_principal_amt + $op_amt;
}
}
if($amoun_cat_id == 2)
{
$opn_principal_amt= $opn_principal_amt-$op_amt;

}
} 
}
}

/////////////////////////////////////////////////////







///////////////////////////////////
$admin_user_id = "";
$admin_user_id[] = $user_id;

$regular_bill_id = $this->autoincrement('regular_bill','regular_bill_id');

$wing_flat = $this->wing_flat($wing_id,$flat_id);

$current_date = date('Y-m-d');
$current_bill_amt = (int)$this->request->data['tt'.$user_id];
@$tax_arrears = (int)@$tax_arrears + @$penalty_amt + $opn_penlty_amt;
@$arrear_amt = @$arrear_amt + @$pr_amt + ($opn_principal_amt);
@$total_due_amount = $total_due_amount + ($opn_principal_amt)+$opn_penlty_amt;
@$grand_total = $grand_total+($opn_principal_amt)+$opn_penlty_amt;

//////////////////////////////////////////////////////////////
$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'DESC');
$cursor=$this->regular_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$lastt=$collection['regular_bill']["receipt_id"];
}
if(empty($lastt))
{
$r=1000;
}	
else
{	
$r=$lastt;
}
$r++;
$this->loadmodel('regular_bill');
$multipleRowData = Array( Array("regular_bill_id" => $regular_bill_id,"receipt_id" => $r,
"description"=>$description,"date"=>$current_date, "society_id"=>$s_society_id,"bill_for_user"=>$user_id,
"g_total"=>$grand_total,"bill_daterange_from"=>$m_from,"bill_daterange_to"=>$m_to,
"bill_html"=>"","one_time_id"=>$one,"status" => 0,  
"due_date" => $due_date, "total_due_amount"=> $total_due_amount, "current_tax" => @$penalty_amt,"accumulated_tax"=>@$tax_arrears,"remaining_amount"=>$grand_total,"current_bill_amt" => $current_bill_amt,"arrears_amt"=>@$arrear_amt,"pay_amount"=>"", "due_amount" => @$over_due_amt,"period_id"=>$p_id,"ih_detail"=>$income_headd2,"noc_charge"=>@$noc_amt,"approve_status"=>1,"flat_id"=>$flat_id,"open_penlty"=>$opn_penlty_amt,"open_amt"=>$opn_principal_amt,"arrear_interest"=>@$tax_arrears));
$this->regular_bill->saveAll($multipleRowData);	

$ussrs[]=$user_id;

//$this->send_notification('<span class="label label-warning" ><i class="icon-money"></i></span>','New bill for your flat '.$wing_flat.' is generated ',10,$5,$this->webroot.'Incometrackers/ac_statement_bill_view/'.$r,0,$ussrs);
unset($ussrs);
///////////////////////////////////////

////////////////////////////////////////////
///////Start Bill Html Code/////////////////
$total_amount2 = 0;	
$this->loadmodel('regular_bill');
$conditions=array("one_time_id"=>$one,"bill_for_user"=>$user_id,"flat_id"=>$flat_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_no = (int)$collection['regular_bill']['regular_bill_id'];
$receipt_id = $collection['regular_bill']['receipt_id'];
$date_from = $collection['regular_bill']['bill_daterange_from'];
$date_to = $collection['regular_bill']['bill_daterange_to'];
$ih_detail2 = $collection['regular_bill']['ih_detail'];
$date_c=$collection['regular_bill']["date"];
$regular_bill_id=$collection['regular_bill']["regular_bill_id"];
$grand_total = (int)$collection['regular_bill']['g_total'];
$late_amt2 = (int)$collection['regular_bill']['current_tax'];
$due_amt2 = (int)$collection['regular_bill']['total_due_amount'];
$due_date2 = @$collection['regular_bill']['due_date'];
$narration = $collection['regular_bill']['description'];
$billing_cycle_id = (int)$collection['regular_bill']['period_id'];
$interest_arrears = (int)$collection['regular_bill']['accumulated_tax'];
$open_pen_amt2 = $collection['regular_bill']['open_penlty'];
$open_princi_amt2 = $collection['regular_bill']['open_amt'];
$amount_arrears = $collection['regular_bill']['arrears_amt'];
$remain_amount = $collection['regular_bill']['remaining_amount'];

}

$date_frm = date('M',strtotime($date_from));	
if($billing_cycle_id == 1)
{
$multi_ch = 1;
}
if($billing_cycle_id == 2)
{
$multi_ch = 2;
}
if($billing_cycle_id == 3)
{
$multi_ch = 4;
}
if($billing_cycle_id == 4)
{
$multi_ch = 6;
}
if($billing_cycle_id == 5)
{
$multi_ch = 12;
}	

$date_from = date("d-M-Y",strtotime($date_from));
$date_to = date("d-M-Y",strtotime($date_to));
$date_to2 = date('Y-m-d',strtotime($date_to));
$due_date21 = date('d-M-Y',strtotime($due_date2));
//$newDate = date("d-M-Y",strtotime($date));	

$this->loadmodel('user');
$conditions=array("user_id"=>$user_id,"society_id" => $s_society_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$user_name=$collection['user']["user_name"];	
$wing = (int)$collection['user']['wing'];
$flat = (int)$collection['user']['flat'];
}

$this->loadmodel('flat');
$conditions=array("flat_id"=>$flat,"society_id" => $s_society_id);
$cursor=$this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_area = $collection['flat']['flat_area'];
}

$wing_flat = $this->wing_flat($wing,$flat);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name=$collection['society']["society_name"];
$so_reg_no = $collection['society']['society_reg_num'];
$so_address = $collection['society']['society_address'];
$bank_name = @$collection['society']['bank_name'];	
$ac_num = @$collection['society']['ac_num'];
$branch = @$collection['society']['branch'];
$account_name = @$collection['society']['ac_name'];
$ifsc_code = @$collection['society']['ifsc_code'];		
}
$date_c = date('d-M-Y',strtotime($date_c));
$date = date('d-M-Y',strtotime($date_from));
$datett = date('M',strtotime($date_to));
//////////////////////////////////////////////
$dateA =date('m',strtotime($date));
$y = date('Y',strtotime($date));
$datt = array();
$multi_ch2 = $multi_ch+1;
$n=1;
while($n<$multi_ch2)
{
$n++;
$datt[] = date('d-'.$dateA.'-'.$y.'',strtotime($date));

if($dateA == 12)
{
$dateA=0;
$y++;
}
$dateA++;
}

$month2 = array();
for($r=0; $r<sizeof($datt); $r++)
{
$dat2 = $datt[$r];
$month2[] = date('M',strtotime($dat2));	
$year = date('Y',strtotime($dat2));
}
$monthB = implode("-",$month2);

//////////////////////////////////////////////////
//echo $log_img;
$html='<div style="width:70%;margin:auto;" class="bill_on_screen">
<div style="background-color:white; overflow:auto;">
<div style="border:solid 1px; overflow:auto;">
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">'.strtoupper($society_name).'</div>
<div style="padding:5px;">
	<div style="float:left;">
	<img src='.$webroot_path.'logo/'.$log_img.'  height="60px" width="60px" class=""></img>
	</div>
	<div style="float:right;" align="right">
	<span style="color: rgb(100, 100, 99); ">Regn# &nbsp; '.$so_reg_no.'</span><br/>
	<span style="color: rgb(100, 100, 99); ">'.$so_address.'</span><br/>
	<span>Email: '.$society_email.'</span> | <span>Phone : '.$society_phone.'</span>
	</div>
</div>
<table border="0" style="width:15%; float:left;">
<tr>
<td>

</td>
</tr>
</table>

</div>
<div style="border:solid 1px; overflow:auto; border-top:none; border-bottom:none;padding:5px;">
<div>
<table border="0" style="width:60%; float:left;">
<tr>
<td style="text-align:left; width:17%;font-weight: bold;">
Name :
</td>
<td>'.$user_name.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Bill No.:</td>
<td style="text-align:left;">'.$receipt_id.' </td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Bill Date:</td>
<td style="text-align:left;">'.$date_c.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Description:</td>
<td style="text-align:left;">'.$narration.'</td>
</tr>
<tr>
<td style="text-align:left;"></td>
<td style="text-align:left;"></td>
</tr>
</table>
<table border="0" style="width:39%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Flat/Shop No.:</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Area:</td>
<td style="text-align:left;">'.$flat_area.' Sq Feet</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Billing Period:</td>
<td style="text-align:left;">'.$monthB.''. $year.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;"><b>Due Date:</b></td>
<td style="text-align:left;"><b>'.$due_date21.'</b></td>
</tr>
</table>
</div>
</div>
<div style="overflow:auto;">
<table border="1" style="width:100%; margine-left:2px; border-collapse:collapse;" cellspacing="0" cellpadding="5">
<tr>
<th style="width:80%; text-align:left;color: #fff;background-color: rgb(4, 126, 186);">Particulars of charges</th>
<th style="text-align:right;color: #fff;background-color: rgb(4, 126, 186);">Amount (Rs.)</th>
<tr>
<tr>
<td valign="top" style="height:200px;">
<table border="0" style="width:100%;">';

for($x=0; $x<sizeof($ih_detail2); $x++)
{
$ih_det = $ih_detail2[$x];
$ih_id5 = (int)$ih_det[0];
if($ih_id5 != 43)
{
$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih_id5)));
foreach($result7 as $collection)
{
$ih_name = $collection['ledger_account']['ledger_name'];
}
}
else
{
$ih_name = "Non Occupancy charges";
}
$html.='<tr>
<td style="text-align:left;">'.$ih_name.'</td>
</tr>';
}

$html.='</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">';
for($y=0; $y<sizeof($ih_detail2); $y++)
{
$ih_det3 = $ih_detail2[$y];
$amount = $ih_det3[1];
//$amount2 = number_format($amount);
$html.='<tr>
<td style="text-align:right;padding-right: 8%;">'.$amount.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amount;
}
//$due_amt3 = $due_amt2 - $late_amt2;
$html.='</table>
</td>
</tr>
<tr>
<td valign="top">
<table border="0" style="width:60%; float:left;">
<tr>
<td colspan="2" >Cheque/NEFT payment instructions:</td>
</tr>
<tr>
<td width="35%"><b>Account Name:</b></td>
<td> &nbsp;&nbsp; '.$account_name.'</td>
</tr>
<tr>
<td><b>Account No.:</b></td>
<td> &nbsp;&nbsp; '.$ac_num.'</td>
</tr>
<tr>
<td><b>Bank Name:</b></td>
<td> &nbsp;&nbsp; '.$bank_name .'</td>
</tr>
<tr>
<td><b>Branch Name:</b></td>
<td> &nbsp;&nbsp; '.$branch .'</td>
</tr>
<tr>
<td><b>IFSC no.:</b></td>
<td> &nbsp;&nbsp; '.$ifsc_code.'</td>
</tr>
</table>
<table border="0" style="width:40%;">';
$html.='<tr>
<td rowspan="5"></td>
<td style="text-align:right; padding-right:2%;">Total:</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:2%;">Interest on arrears:</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:2%;">Arrears &nbsp; (Maint.):</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:2%;">Arrears &nbsp; (Int.):</td>
</tr>';

$html.='<tr>
<th style="text-align:right; padding-right:2%;">Due For Payment:</th>
</tr>';
$html.='</table>
</td>
<td valign="top">';

$due_amt5 = $due_amt2-$interest_arrears;
$int_show_arrears = $interest_arrears - $late_amt2;



$total_amount3 = number_format($total_amount2);
if($amount_arrears<0)
{
$amount_arrears = abs($amount_arrears);
$due_amt4 = number_format($amount_arrears);
$due_amt4 = "-".$due_amt4;
}
else
{
$due_amt4 = number_format($amount_arrears);
}


$late_amt3 = number_format($late_amt2);
if($remain_amount<0)
{
$remain_amount = abs($remain_amount);
$grand_total2 = number_format($remain_amount);
$grand_total2 = "-".$grand_total2;
}
else
{
$grand_total2 = number_format($remain_amount);
}
$int_show_arrears2 = number_format($int_show_arrears);

$html.='<table border="0" style="width:100%;">
<tr>';
$html.='
<td style="text-align:right; padding-right:8%;">'.$total_amount3.'</td>
</tr>';
$html.='
<tr>
<td style="text-align:right; padding-right:8%;">'.@$late_amt3.'</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:8%;">'.@$due_amt4.'</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:8%;">'.@$int_show_arrears2.'</td>
</tr>';

$html.='<tr>
<th style="text-align:right; padding-right:8%;">'.$grand_total2.'</th>
</tr>';
$grand_total2;
$grand_total2 = str_replace( ',', '', $grand_total2 );
$am_in_words=ucwords(strtolower($this->convert_number_to_words($grand_total2)));
$html.='</table>
</td>
</tr>
<tr><td colspan="2"><b>Due For Payment (in words) :</b> Rupees '.$am_in_words.' Only</td></tr>
</table>
</div>';

$html.='<div style="overflow:auto;border:solid 1px;border-bottom:none;padding:5px;border-top: none;">
<div style="width:70%;float:left;">
<span>Remarks:</span><br/>';
$count=0;
for($r=0; $r<sizeof($terms_arr); $r++)
{
$count++;
$tems_name = $terms_arr[$r];
$html.='<span>'.$count.'.  '.$tems_name.'</span><br/>';
}
$html.='</div>
<div style="width:30%;float:right;" align="center">For  <b>'.$society_name.' <br/><br/><br/><div align="center"><span style="border-top: solid 1px #424141;">'.$sig_title.'</span></div></div>
</div>
<div align="center" style="color: #6F6D6D;border: solid 1px;border-top: dotted 1px;">Note: This is a computer generated bill hence no signature required.</div>
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 12px;font-weight: bold;color: #fff;vertical-align: middle;border: solid 1px #000;border-top: none;">
<span>Your Society is empowered by HousingMatters - 
<i>"Making Life Simpler"</i></span><br/>
<span>Email: support@housingmatters.in</span> &nbsp;|&nbsp; <span>Phone : 022-41235568</span> &nbsp;|&nbsp; <span>www.housingmatters.co.in</span></div>

</div>
</div>
';

$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("bill_html" =>$html),array("regular_bill_id" =>$regular_bill_id));	
////////End Bill Html Code///////////////////
////////////////////////////////////////////



$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$mail_id = $collection['society']['account_email'];
}
if($mail_id == 1)
{
/*
$from_mail_date = date('d M',strtotime($date_from));
$to_mail_date = date('d M Y',strtotime($date_to));

//$my_mail = "nikhileshvyas@yahoo.com";
$subject = ''.$society_name.' : Maintanance bill, '.$from_mail_date.' to '.$to_mail_date.'';
$from_name="HousingMatters";
//$message_web = "Receipt No. :".$d_receipt_id;
$from = "accounts@housingmatters.in";
$reply="accounts@housingmatters.in";
$this->send_email($to_mail,$from,$from_name,$subject,$html,$reply);
*/
}
}
}
}
else if($bill_for == 1)
{
$wing_arr = explode(",",$wing_arr_imp);
/////////////////////////////////////////////////
for($m=0; $m<sizeof($wing_arr); $m++)
{
$wing_id_a = (int)$wing_arr[$m];
$cursor = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch3'),array('pass'=>array($wing_id_a)));

foreach($cursor as $collection)
{
$multi_flat = array();
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$flat_id = (int)$collection['user']['flat'];
$wing_id = (int)$collection['user']['wing'];
$mobile = $collection['user']['mobile'];
$to_mail = $collection['user']['email'];
$multi_flat = @$collection['user']['multiple_flat'];

$rrr = (int)sizeof($multi_flat);
if($rrr == 0)
{
$multi_flat[] = array($wing_id,$flat_id);	
}
for($g=0; $g<sizeof($multi_flat); $g++)
{
$mul_flat2 = $multi_flat[$g];
$wing_id = (int)$mul_flat2[0];
$flat_id = (int)$mul_flat2[1];



$wing_flat = $this->wing_flat($wing_id,$flat_id);

$maint_ch = 0;


$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id, "flat_id" => $flat_id, "wing_id" => $wing_id);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$noc_ch_id = (int)@$collection['flat']['noc_ch_tp'];
$sq_feet = (int)$collection['flat']['flat_area'];
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id, "auto_id" => $flat_type_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$charge = @$collection['flat_type']['charge'];
$noc_charge = @$collection['flat_type']['noc_charge'];
}

$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'ASC');
$cursor=$this->regular_bill->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$last111=$collection['regular_bill']["receipt_id"];
}
if(empty($last111))
{
$regular_bill_id11=1000;
}	
else
{	
$regular_bill_id11=$last111;
}
$regular_bill_id11++;

$current_date11 = date('Y-m-d');
//$current_date11 = new MongoDate(strtotime($current_date11));
/////////////////////////////////////
$total_amt = 0;
$income_headd2 = array();
for($s=0; $s<sizeof($income_head_arr); $s++)
{
$auto_id_in = (int)$income_head_arr[$s];

$ih_amt = (int)$this->request->data['ih'.$auto_id_in.$user_id];

$income_headd = array($auto_id_in,$ih_amt);
$income_headd2[] = $income_headd;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'ASC');
$cursor=$this->ledger->find('all',array('order' =>$order));
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $ih_amt, "amount_category_id" => 2,
"table_name" => "regular_bill", "account_type" => 2, "account_id" => $auto_id_in, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);

}

///////////////////////////////////////
if($noc_ch_id == 2)
{
$noc_amt = (int)$this->request->data['noc'.$user_id];

$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'ASC');
$cursor=$this->ledger->find('all',array('order' =>$order));
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $noc_amt, "amount_category_id" => 2,
"table_name" => "regular_bill", "account_type" => 2, "account_id" => 43, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);

$income_headd = array(43,$noc_amt);
$income_headd2[] = $income_headd;
}

////////////////////////////////////
//$tax_amount = round(($tax_per/100)*$total_amount);
$current_date = date('Y-m-d');
$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"bill_for_user"=>$user_id,"status"=>0,"flat_id"=>$flat_id);
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
//$due_amount11 = (int)$collection['regular_bill']['remaining_amount'];
$due_date11 = $collection['regular_bill']['due_date'];
$from_due = $collection['regular_bill']['bill_daterange_from'];
$tax_arrears = (int)@$collection['regular_bill']['accumulated_tax'];
$arrear_amt = (int)@$collection['regular_bill']['arrears_amt'];
$pr_amt = (int)$collection['regular_bill']['current_bill_amt'];
$previous_penalty_amt = (int)@$collection['regular_bill']['current_tax'];
}
$cur_date = date('Y-m-d');
//$cur_datec = new MongoDate(strtotime($cur_date));

if($penalty == 1)
{
$due_date12 = date('Y-m-d',strtotime(@$due_date11));
$from_due_date = date('Y-m-d',strtotime(@$from_due));

$penalty_amt = (int)$this->request->data['penalty'.$user_id];

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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => @$penalty_amt, "amount_category_id" => 2, 
"table_name" => "regular_bill", "account_type"=> 2, "account_id" => 41, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);
}


$over_due_amt = (int)$this->request->data['due'.$user_id];
/*
if(@$over_due_amt > 0)
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" =>$over_due_amt, "amount_category_id" => 2,"table_name" => "regular_bill", "account_type" => 2, "account_id" => 13, "current_date" => $current_date11,"society_id" => $s_society_id,"module_name"=>"Regular Bill"));
$this->ledger->saveAll($multipleRowData);
}
*/
$total_due_amount = (int)@$penalty_amt + $over_due_amt;

$current_date13 = date('Y-m-d');
//$current_date13 = new MongoDate(strtotime($current_date13));

$regular_bill_id13 = (int)$this->autoincrement('regular_bill','regular_bill_id');

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "user_id" => $user_id, "ledger_id" => 34);
$cursor=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$l_id =  (int)$collection['ledger_sub_account']['auto_id'];
}

$current_bill_amt2 = (int)$this->request->data['tt'.$user_id];
$grand_total = (int)$this->request->data['gtt'.$user_id];

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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $current_bill_amt2, "amount_category_id" => 1, 
"table_name" => "regular_bill", "account_type"=> 1, "account_id" => @$l_id, "current_date" => $current_date13,"society_id" => $s_society_id,"module_name"=>"Regular Bill","penalty"=>"NO"));
$this->ledger->saveAll($multipleRowData);

if($penalty == 1)
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $penalty_amt, "amount_category_id" => 1, 
"table_name" => "regular_bill", "account_type"=> 1, "account_id" => @$l_id, "current_date" => $current_date13,"society_id" => $s_society_id,"module_name"=>"Regular Bill","penalty"=>"YES"));
$this->ledger->saveAll($multipleRowData);
}



$total_amt = (int)$this->request->data['tt'.$user_id];

$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array('status'=>1),array("society_id"=>$s_society_id,"bill_for_user"=>$user_id,"status"=>0,"flat_id"=>$flat_id));

///////////////////////////////////////////////
if($one > 1)
{
$from_due2 = date('Y-m-d',strtotime(@$from_due));
}
else
{
$from_due2 = "2000-01-01";
$from_due2 = date('Y-m-d',strtotime($from_due2));
$arrear_amt = 0;
$tax_arrears = 0;
}


$opn_principal_amt = 0;
$opn_penlty_amt = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id,"account_id" => $l_id);
$cursor=$this->ledger->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$receipt_id = "";
$op_date = "";
$op_date2 = "";
$op_amt = "";
$pen_type = "";

$receipt_id = @$collection['ledger']['receipt_id'];

if($receipt_id == "O_B")
{
$op_date = $collection['ledger']['op_date'];
$op_date2 = date('Y-m-d',$op_date->sec);
$op_date2 = date('Y-m-d',strtotime($op_date2));
$op_amt = $collection['ledger']['amount'];
@$pen_type = @$collection['ledger']['penalty'];
$amoun_cat_id = (int)@$collection['ledger']['amount_category_id'];
if($op_date2 <= $m_from && $one == 1)
{
if($amoun_cat_id == 1)
{
if($pen_type == "YES")
{
$opn_penlty_amt= $opn_penlty_amt + $op_amt;
}
else
{
$opn_principal_amt= $opn_principal_amt + $op_amt;
}
}
if($amoun_cat_id == 2)
{
$opn_principal_amt= $opn_principal_amt - $op_amt;
}

}
}
}

/////////////////////////////////////////////////////







///////////////////////////////////
$admin_user_id = "";
$admin_user_id[] = $user_id;

$regular_bill_id = $this->autoincrement('regular_bill','regular_bill_id');

$wing_flat = $this->wing_flat($wing_id,$flat_id);

$current_bill_amt = (int)$this->request->data['tt'.$user_id];
@$tax_arrears = (int)$tax_arrears + @$penalty_amt+$opn_penlty_amt;
@$arrear_amt = @$arrear_amt + @$pr_amt+($opn_principal_amt);
@$total_due_amount = $total_due_amount+($opn_principal_amt)+$opn_penlty_amt;
@$grand_total = $grand_total+($opn_principal_amt)+$opn_penlty_amt;

$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'DESC');
$cursor=$this->regular_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$lastt=$collection['regular_bill']["receipt_id"];
}
if(empty($lastt))
{
$r=1000;
}	
else
{	
$r=$lastt;
}
$r++;
$this->loadmodel('regular_bill');
$multipleRowData = Array( Array("regular_bill_id" => $regular_bill_id,"receipt_id" => $r,
"description"=>$description,"date"=>$current_date, "society_id"=>$s_society_id,"bill_for_user"=>$user_id,
"g_total"=>$grand_total,"bill_daterange_from"=>$m_from,"bill_daterange_to"=>$m_to,
"bill_html"=>"","one_time_id"=>$one,"status" => 0,  
"due_date" => $due_date, "total_due_amount"=> $total_due_amount, "current_tax" => @$penalty_amt,"accumulated_tax"=>@$tax_arrears,"remaining_amount"=>$grand_total,"current_bill_amt" => $current_bill_amt,"arrears_amt"=>@$arrear_amt,"pay_amount"=>"", "due_amount" => @$over_due_amt,"period_id"=>$p_id,"ih_detail"=>$income_headd2,"noc_charge"=>@$noc_amt,"approve_status"=>1,"flat_id"=>$flat_id,"open_penlty"=>$opn_penlty_amt,"open_amt"=>$opn_principal_amt,"arrear_interest"=>@$tax_arrears));
$this->regular_bill->saveAll($multipleRowData);	


////////////////////

///////////////////////////////////////
$ussrs[]=$user_id;

$this->send_notification('<span class="label label-warning" ><i class="icon-money"></i></span>','New bill for your flat '.$wing_flat.' is generated ',10,$r,$this->webroot.'Incometrackers/ac_statement_bill_view/'.$r,0,$ussrs);
unset($ussrs);
////////////////////////////////////////////
///////Start Bill Html Code/////////////////
	$total_amount2 = 0;	
	$this->loadmodel('regular_bill');
	$conditions=array("one_time_id"=>$one,"bill_for_user"=>$user_id,"flat_id"=>$flat_id);
	$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
	foreach($cursor as $collection)
	{
	$bill_no = (int)$collection['regular_bill']['regular_bill_id'];
	$receipt_id = $collection['regular_bill']['receipt_id'];
	$date_from = $collection['regular_bill']['bill_daterange_from'];
	$date_to = $collection['regular_bill']['bill_daterange_to'];
	$ih_detail2 = $collection['regular_bill']['ih_detail'];
	$date_c=$collection['regular_bill']["date"];
	$regular_bill_id=$collection['regular_bill']["regular_bill_id"];
	$grand_total = (int)$collection['regular_bill']['g_total'];
	$late_amt2 = (int)$collection['regular_bill']['current_tax'];
	$due_amt2 = (int)$collection['regular_bill']['total_due_amount'];
	$due_date2 = @$collection['regular_bill']['due_date'];
	$narration = $collection['regular_bill']['description'];
	$billing_cycle_id = (int)$collection['regular_bill']['period_id'];
	$interest_arrears = (int)$collection['regular_bill']['accumulated_tax'];
	$open_pen_amt2 = $collection['regular_bill']['open_penlty'];
	$open_princi_amt2 = $collection['regular_bill']['open_amt'];
	$arrears_amt=$collection['regular_bill']['arrears_amt'];
	$remain_amount = $collection['regular_bill']['remaining_amount'];
	}
	
$date_frm = date('M',strtotime($date_from));	
if($billing_cycle_id == 1)
{
$multi_ch = 1;
}
if($billing_cycle_id == 2)
{
$multi_ch = 2;
}
if($billing_cycle_id == 3)
{
$multi_ch = 4;
}
if($billing_cycle_id == 4)
{
$multi_ch = 6;
}
if($billing_cycle_id == 5)
{
$multi_ch = 12;
}	

	
$date_from = date("d-M-Y",strtotime($date_from));
$date_to = date("d-M-Y",strtotime($date_to));
$date_to2 = date('Y-m-d',strtotime($date_to));

//$due_date = date('Y-m-d', strtotime($date_to2 .'+'. $due_days2.'day'));
$due_date21 = date('d-M-Y',strtotime(@$due_date2));
//$newDate = date("d-M-Y",strtotime(@$date));	


$this->loadmodel('user');
$conditions=array("user_id"=>$user_id,"society_id" => $s_society_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$user_name=$collection['user']["user_name"];	
$wing = (int)$collection['user']['wing'];
$flat = (int)$collection['user']['flat'];
}

$this->loadmodel('flat');
$conditions=array("flat_id"=>$flat,"society_id" => $s_society_id);
$cursor=$this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_area = $collection['flat']['flat_area'];
}

$wing_flat = $this->wing_flat($wing,$flat);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name=$collection['society']["society_name"];
$so_reg_no = $collection['society']['society_reg_num'];
$so_address = $collection['society']['society_address'];	
$bank_name = @$collection['society']['bank_name'];	
$ac_num = @$collection['society']['ac_num'];
$branch = @$collection['society']['branch'];
$account_name = @$collection['society']['ac_name'];
$ifsc_code = @$collection['society']['ifsc_code'];
}
$date_c = date('d-M-Y',strtotime($date_c));
$date = date('d-M-Y',strtotime($date_from));
$datett = date('M',strtotime($date_to));

/////////////////////////////////////
$dateA =date('m',strtotime($date));
$y = date('Y',strtotime($date));

$datt = array();
$multi_ch2 = $multi_ch+1;
$n=1;
while($n<$multi_ch2)
{
$n++;
$datt[] = date('d-'.$dateA.'-'.$y.'',strtotime($date));

if($dateA == 12)
{
$dateA=0;
$y++;
}
$dateA++;
}

$month2 = array();
for($r=0; $r<sizeof($datt); $r++)
{
$dat2 = $datt[$r];
$month2[] = date('M',strtotime($dat2));	
$year = date('Y',strtotime($dat2));
}
$monthB = implode("-",$month2);

//////////////////////////////////////////
$html='<div style="width:70%;margin:auto;"  class="bill_on_screen">
<div style="background-color:white; overflow:auto;">
<div style="border:solid 1px; overflow:auto;">
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 16px;font-weight: bold;color: #fff;">'.strtoupper($society_name).' </div>
<div style="padding:5px;">
	<div style="float:left;">
	<img src='.$webroot_path.'logo/'.$log_img.' height="60px" width="60px" class=""></img>
	</div>
	<div style="float:right;" align="right">
	<span style="color: rgb(100, 100, 99); ">Regn# &nbsp; '.$so_reg_no.'</span><br/>
	<span style="color: rgb(100, 100, 99); ">'.$so_address.'</span><br/>
	<span>Email: '.$society_email.'</span> | <span>Phone : '.$society_phone.'</span>
	</div>
</div>
<table border="0" style="width:15%; float:left;">
<tr>
<td>

</td>
</tr>
</table>

</div>
<div style="border:solid 1px; overflow:auto; border-top:none; border-bottom:none;padding:5px;">
<div>
<table border="0" style="width:60%; float:left;">
<tr>
<td style="text-align:left; width:17%;font-weight: bold;">
Name :
</td>
<td>'.$user_name.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Bill No.:</td>
<td style="text-align:left;">'.$receipt_id.' </td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Bill Date:</td>
<td style="text-align:left;">'.$date_c.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Description:</td>
<td style="text-align:left;">'.$narration.'</td>
</tr>
<tr>
<td style="text-align:left;"></td>
<td style="text-align:left;"></td>
</tr>
</table>
<table border="0" style="width:39%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Flat/Shop No.:</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Area:</td>
<td style="text-align:left;">'.$flat_area.' Sq Feet</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;">Billing Period:</td>
<td style="text-align:left;">'.$monthB.''. $year.'</td>
</tr>
<tr>
<td style="text-align:left;font-weight: bold;"><b>Due Date:</b></td>
<td style="text-align:left;"><b>'.$due_date21.'</b></td>
</tr>

</table>
</div>

</div>
<div style="overflow:auto;">
<table border="1" style="width:100%; margine-left:2px; border-collapse:collapse;" cellspacing="0" cellpadding="5">
<tr>
<th style="width:80%; text-align:left;color: #fff;background-color: rgb(4, 126, 186);">Particulars of charges</th>
<th style="text-align:right;color: #fff;background-color: rgb(4, 126, 186);">Amount (Rs.)</th>
<tr>
<tr>
<td valign="top" style="height:200px;">
<table border="0" style="width:100%;">';

for($x=0; $x<sizeof($ih_detail2); $x++)
{
$ih_det = $ih_detail2[$x];
$ih_id5 = (int)$ih_det[0];
if($ih_id5 != 43)
{
$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih_id5)));
foreach($result7 as $collection)
{
$ih_name = $collection['ledger_account']['ledger_name'];
}
}
else
{
$ih_name = "Non Occupancy charges";
}
$html.='<tr>
<td style="text-align:left;">'.$ih_name.'</td>
</tr>';
}

$html.='</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">';
for($y=0; $y<sizeof($ih_detail2); $y++)
{
$ih_det3 = $ih_detail2[$y];
$amount = $ih_det3[1];
//$amount2 = number_format($amount);
$html.='<tr>
<td style="text-align:right;padding-right: 8%;">'.$amount.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amount;
}
//$due_amt3 = $due_amt2 - $late_amt2;
$html.='</table>
</td>
</tr>
<tr>
<td valign="top">
<table border="0" style="width:60%; float:left;">
<tr>
<td colspan="2" >Cheque/NEFT payment instructions:</td>
</tr>
<tr>
<td width="35%"><b>Account Name:</b></td>
<td> &nbsp;&nbsp; '.$account_name.'</td>
</tr>
<tr>
<td><b>Account No.:</b></td>
<td> &nbsp;&nbsp; '.$ac_num.'</td>
</tr>
<tr>
<td><b>Bank Name:</b></td>
<td> &nbsp;&nbsp; '.$bank_name .'</td>
</tr>
<tr>
<td><b>Branch Name:</b></td>
<td> &nbsp;&nbsp; '.$branch .'</td>
</tr>
<tr>
<td><b>IFSC no.:</b></td>
<td> &nbsp;&nbsp; '.$ifsc_code.'</td>
</tr>
</table>
<table border="0" style="width:40%;">';
$html.='<tr>
<td rowspan="5"></td>
<td style="text-align:right; padding-right:2%;">Total:</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:2%;">Interest on arrears:</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:2%;">Arrears &nbsp; (Maint.):</td>
</tr>';
$html.='<tr>
<td style="text-align:right;">Arrears &nbsp; (Int.):</td>
</tr>';

$html.='<tr>
<th style="text-align:right; padding-right:2%;">Due For Payment:</th>
</tr>';
$html.='</table>
</td>
<td valign="top">';
//$due_amt5 = (int)$due_amt2 - $interest_arrears;
$int_show_arrears = (int)$interest_arrears-$late_amt2;

$total_amount3 = number_format($total_amount2);
if($arrears_amt < 0)
{
$arrears_amt = abs($arrears_amt);
$due_amt4 = number_format($arrears_amt);
$due_amt4 = "-".$due_amt4;
}
else
{
$due_amt4 = number_format($arrears_amt);
}

$late_amt3 = number_format($late_amt2);
if($remain_amount < 0)
{
$remain_amount = abs($remain_amount);
$grand_total2 = number_format($remain_amount);
$grand_total2 = "-".$grand_total2;
}
else
{
$grand_total2 = number_format($remain_amount);
}
$int_show_arrears2 = number_format($int_show_arrears);

$html.='<table border="0" style="width:100%;">
<tr>';
$html.='
<td style="text-align:right; padding-right:8%;">'.$total_amount3.'</td>
</tr>';
$html.='
<tr>
<td style="text-align:right; padding-right:8%;">'.@$late_amt3.'</td>
</tr>';

$html.='<tr>
<td style="text-align:right; padding-right:8%;">'.@$due_amt4.'</td>
</tr>';
$html.='<tr>
<td style="text-align:right; padding-right:8%;">'.@$int_show_arrears2.'</td>
</tr>';

$html.='<tr>
<th style="text-align:right; padding-right:8%;">'.$grand_total2.'</th>
</tr>';
$grand_total2 = str_replace( ',', '', $grand_total2 );
$am_in_words=ucwords(strtolower($this->convert_number_to_words($grand_total2)));
$html.='</table>
</td>
</tr>
<tr><td colspan="2"><b>Due For Payment (in words) :</b> Rupees '.$am_in_words.' Only</td></tr>
</table>
</div>';

$html.='<div style="overflow:auto;border:solid 1px;border-bottom:none;padding:5px;border-top: none;">
<div style="width:70%;float:left;">
<span>Remarks:</span><br/>';
$count=0;
for($r=0; $r<sizeof($terms_arr); $r++)
{
$count++;
$tems_name = $terms_arr[$r];
$html.='<span>'.$count.'.  '.$tems_name.'</span><br/>';
}
$html.='</div>
<div style="width:30%;float:right;" align="center">For  <b>'.$society_name.' <br/><br/><br/><div align="center"><span style="border-top: solid 1px #424141;">'.$sig_title.'</span></div></div>
</div>
<div align="center" style="color: #6F6D6D;border: solid 1px;border-top: dotted 1px;">Note: This is a computer generated bill hence no signature required.</div>
<div align="center" style="background-color: rgb(0, 141, 210);padding: 5px;font-size: 12px;font-weight: bold;color: #fff;vertical-align: middle;border: solid 1px #000;border-top: none;">
<span>Your Society is empowered by HousingMatters - 
<i>"Making Life Simpler"</i></span><br/>
<span>Email: support@housingmatters.in</span> &nbsp;|&nbsp; <span>Phone : 022-41235568</span> &nbsp;|&nbsp; <span>www.housingmatters.co.in</span></div>

</div>
</div>
';

//////////////////////////////////

$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("bill_html" =>$html),array("regular_bill_id" =>$regular_bill_id));	
////////End Bill Html Code///////////////////
////////////////////////////////////////////

///////////////Bill Html for mail////////////
/*
$html_mail='<center>
<div style="width:700px; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:96%; border:solid 1px; overflow:auto; border-bottom:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:center;">
<p style="font-size:22px;">'.$society_name.' Society</p>
</th>
</tr>
<tr>
<th style="text-align:center;">'.$so_reg_no.'</th>
</tr>
<tr>
<th style="text-align:center;">'.$so_address.'</th>
</tr>
</table>
</div>
<div style="width:96%; border:solid 1px; overflow:auto; border-bottom:none;">
<table border="0" style="width:65%; float:left;">
<tr>
<td style="text-align:left; width:20%;">
Name :
</td>
<td style="text-align:left;">'.$user_name.'</td>
</tr>
<tr>
<td style="text-align:left;">Bill No. :</td>
<td style="text-align:left;">'.$receipt_id.'</td>
</tr>
<tr>
<td style="text-align:left;">Bill Date :</td>
<td style="text-align:left;">'.$date.'</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">'.$due_date21.'</td>
</tr>
</table>
<table border="0" style="width:30%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;">Flat/Shop No. :</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>
<tr>
<td style="text-align:left;">Area:</td>
<td style="text-align:left;">'.$flat_area.' Sq Feet</td>
</tr>
<tr>
<td style="text-align:left;">Billing Period:</td>
<td style="text-align:left;">'.$monthB.''. $year.'</td>
</tr>
</table>
</div>
<div style="width:96.2%; overflow:auto;">
<table border="1" style="width:100%; border:black;  border-collapse:collapse; margine-left:2px;" cellpadding="0" cellspacing="0">
<tr>
<td style="width:80%; text-align:center;">Particulars</td>
<td style="text-align:center;">Amount (Rs.)</td>
</tr>
<tr>
<td valign="top" style="height:200px;">
<table border="0" style="width:100%;">';

for($x=0; $x<sizeof($ih_detail2); $x++)
{
$ih_det = $ih_detail2[$x];
$ih_id5 = (int)$ih_det[0];
if($ih_id5 != 43)
{
$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih_id5)));
foreach($result7 as $collection)
{
$ih_name = $collection['ledger_account']['ledger_name'];
}
}
else
{
$ih_name = "Non Occupancy charges";
}
$html_mail.='<tr>
<td style="text-align:left;">'.$ih_name.'</td>
</tr>';
}
$html_mail.='</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">';
for($y=0; $y<sizeof($ih_detail2); $y++)
{
$ih_det3 = $ih_detail2[$y];
$amount = $ih_det3[1];
$amount2 = number_format($amount);
$html_mail.='<tr>
<td style="text-align:center;">'.$amount2.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amount;
}
$due_amt3 = $due_amt2 - $late_amt2;
$html_mail.='</table>
</td>
</tr>';
$total_amount3 = number_format($total_amount2);
$due_amt4 = number_format($due_amt3);
$late_amt3 = number_format($late_amt2);
$grand_total2 = number_format($grand_total);
$html.='
<tr>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td rowspan="4"></td>
<td style="text-align:right;">Sub-Total:</td>
</tr>
<tr>
<td style="text-align:right;">Over Due Amount:</td>
</tr>
<tr>
<td style="text-align:right;">Over Due Interest:</td>
</tr>
<tr>
<th style="text-align:right;">Grand Total:</th>
</tr>
</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:center;">'.$total_amount3.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$due_amt4.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$late_amt3.'</td>
</tr>
<tr>
<th style="text-align:center;">'.$grand_total2.'</th>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div style="width:96%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:70%; float:left;">
<tr>
<th style="text-align:left;">Description:</th>
</tr>
<td style="text-align:left;">'.$narration.'</td>
</tr>
</table>
</div>
<div style="width:96%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Terms And Conditions:
</th>
</tr>';
for($r=0; $r<sizeof($terms_arr); $r++)
{
$tems_name = $terms_arr[$r];
$html_mail.='
<tr>
<td style="text-align:left;">'.$tems_name.'</td>
</tr>';
}
$html_mail.='</table> 
</div>
<div style="width:96%; overflow:auto; border:solid 1px; border-top:none;">
<br><br><br>
<table border="0" style="width:100%;">
<tr>
<td style="text-align:right;">
<p style="font-size:18px;"><b>'.$society_name.' Society</b></p>
</td>
</tr>
</table>
</div>
<br><br><br><br>
</div>
';
*/
////////////End Html For mail/////////////////
}
}
}
///////////////////////////////////////////////
}
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Regular Bill</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Bills generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="it_regular_bill" class="btn blue">OK</a>
</div>
</div>
<?php
}
}
////////////////////////// End Regular Bill View2 /////////////////////////////////////////////////////////////
///////////////// Start It Supplimentry Bill (Accounts)///////////////////////////////////////////////////////

function it_supplimentry_bill()
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






if(isset($this->request->data['sub1']))
{
$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name2 = $collection['society']['society_name'];
}
$from2 = $this->request->data['from'];
//$to2 =  $this->request->data['to'];
$bill_type_id = $this->request->data['type'];
$due_date = $this->request->data['due_date'];
if($bill_type_id == 1)
{
$com_name = $this->request->data['c_name'];
$person_name = $this->request->data['p_name'];
$amt5 = (int)$this->request->data['amt'];
}
else if($bill_type_id == 2)
{
$resi_id = (int)$this->request->data['r_name'];

$this->loadmodel('ledger_account');
$conditions =array( '$or' => array( 
array("society_id" => 0,"group_id"=>8),
array("society_id" => $s_society_id,"group_id"=>8)
));
$cursor = $this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)	
{
$ih_id = (int)$collection['ledger_account']['auto_id'];
$ih_idp = (int)@$this->request->data['ih'.$ih_id];
if($ih_idp != 0)
{
$amt = $this->request->data['amt'.$ih_id];
$ih_id2 = array($ih_idp,$amt);
$ih_id3 = implode(",",$ih_id2);
$ih_id4[] = $ih_id3;
}
}
$ih_idn = (int)@$this->request->data['ih43'];
if($ih_idn != 0)
{
$amt = $this->request->data['amt43'];
$ih_id2 = array(43,$amt);
$ih_id3 = implode(",",$ih_id2);
$ih_id4[] = $ih_id3;
}
//$inhd = $this->request->data['i_head'];
}
//$taxid2 = (int)$this->request->data['tax'];
$descp2 = $this->request->data['description'];
//$terms2 = $this->request->data['terms'];

$fromm = date("Y-m-d", strtotime($from2));
$fromm = new MongoDate(strtotime($fromm));
//$tom = date("Y-m-d", strtotime($to2));
//$tom = new MongoDate(strtotime($tom));

$due_date55 = date("Y-m-d", strtotime($due_date));
$due_date55 = new MongoDate(strtotime($due_date55));

//$regular[] = array($from,$to,$due_date,$i_head,$tax,$description,$terms);

if($bill_type_id == 2)
{
$ih2 = implode('/',$ih_id4);
}
//$terms3 = implode(',',$terms2);
if($bill_type_id == 1)
{
$f1=$this->encode($from2,'housingmatters');
//$t1=$this->encode($to2,'housingmatters');
$due1=$this->encode($due_date,'housingmatters');
//$tax3=$this->encode($taxid2,'housingmatters');
$desc3=$this->encode($descp2,'housingmatters');
//$terms4=$this->encode($terms3,'housingmatters');
$bill_type3=$this->encode($bill_type_id,'housingmatters');
$person3=$this->encode($person_name,'housingmatters');
$com3=$this->encode($com_name,'housingmatters');
$amt6=$this->encode($amt5,'housingmatters');


$this->response->header('Location','supplimentry_bill_view2?f='.$f1.'&due='.$due1.'&d='.$desc3.'&tp='.$bill_type3.'&pn='.$person3.'&com='.$com3.'&amt='.$amt6.' ');

}
else
{
$f2=$this->encode($from2,'housingmatters');
//$t2=$this->encode($to2,'housingmatters');
$due3=$this->encode($due_date,'housingmatters');
$ih3=$this->encode($ih2,'housingmatters');
//$tax3=$this->encode($taxid2,'housingmatters');
$desc3=$this->encode($descp2,'housingmatters');
//$tem3=$this->encode($terms3,'housingmatters');
$bill_tp3=$this->encode($bill_type_id,'housingmatters');
$resi3=$this->encode($resi_id,'housingmatters');



$this->response->header('Location','supplimentry_bill_view2?f='.$f2.'&due='.$due3.'&ih='.$ih3.'&d='.$desc3.'&tp='.$bill_tp3.'&res='.$resi3.' ');



}
}


$this->loadmodel('ledger_sub_account');
$conditions=array("society_id"=>$s_society_id, "ledger_id" => 34,"deactive"=>0);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

$this->loadmodel('ledger_account');
$conditions =array( '$or' => array( 
array("society_id" => 0,"group_id"=>8),
array("society_id" => $s_society_id,"group_id"=>8)
));
$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id"=>$s_society_id, "ledger_id" => 35);
$cursor3=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);	

$this->loadmodel('terms_conditions');
$conditions=array("society_id"=>$s_society_id);
$cursor4=$this->terms_conditions->find('all',array('conditions'=>$conditions));
$this->set('cursor4',$cursor4);	

$this->loadmodel('bill_period');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor5 = $this->bill_period->find('all',array('conditions'=>$conditions));
$this->set('cursor5',$cursor5);

}

///////////////////End It Supplimentry Bill (Accounts)//////////////////////////////////
///////////////////// Start supplimentry bill view2(Accounts)///////////////////////////

function supplimentry_bill_view2()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
$society_reg_no = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
$terms_arr = @$collection['society']['terms_conditions'];
}
$this->set('society_name',$society_name);
$this->set('society_reg_no',$society_reg_no);
$this->set('society_address',$society_address);
$this->set('terms_arr',$terms_arr);
$from4 = $this->request->query('f');
//$to4 = $this->request->query('t');
$due_date4 = $this->request->query('due');
//$tax4 = $this->request->query('tax');
$desc4 = $this->request->query('d');
//$tem4 = $this->request->query('tem');
$type4 = $this->request->query('tp');

$from=$this->decode($from4,'housingmatters');
//$to=$this->decode($to4,'housingmatters');
$due_date=$this->decode($due_date4,'housingmatters');
//$tax=(int)$this->decode($tax4,'housingmatters');
$desc=$this->decode($desc4,'housingmatters');
//$tem=$this->decode($tem4,'housingmatters');
$type=(int)$this->decode($type4,'housingmatters');


if($type == 1)
{
$person_name4 = $this->request->query('pn');
$com_name4 = $this->request->query('com');
$amt6 = $this->request->query('amt');

$person_name=$this->decode($person_name4,'housingmatters');
$com_name=$this->decode($com_name4,'housingmatters');
$amt5=$this->decode($amt6,'housingmatters');

}
else
{
$res_id4 = $this->request->query['res'];
$ih4 = $this->request->query('ih');

$res_id=(int)$this->decode($res_id4,'housingmatters');
$ih=$this->decode($ih4,'housingmatters');

}
$this->set('from',$from);
//$this->set('to',$to);
$this->set('due_date',$due_date);
//$this->set('tax1',$tax);
$this->set('desc',$desc);
//$this->set('tem',$tem);
$this->set('type',$type);
if($type == 1)
{
$this->set('person_name',$person_name);
$this->set('com_name',$com_name);
$this->set('amt5',$amt5);
}
else
{
$this->set('res_id',$res_id);
$this->set('ih',$ih);
}
//////////////////////////////
$this->loadmodel('adhoc_bill');
$order=array('adhoc_bill.receipt_id'=> 'DESC');
$cursor=$this->adhoc_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last11=$collection['adhoc_bill']["receipt_id"];
}
if(empty($last11))
{
$z=1000;
}	
else
{	
$z=$last11;
}
$z++;

$this->set('bill_no',$z);

///////////////////////////////////
if(isset($this->request->data['sub_sup']))
{
$s_from = $this->request->data['from'];
//$s_to = $this->request->data['to'];
$s_due_date = $this->request->data['due_date'];
//$s_tax = (int)$this->request->data['tax1'];
$s_desc = $this->request->data['desc'];
//$s_tem = $this->request->data['tem'];
$s_type = (int)$this->request->data['type'];
if($s_type == 1)
{
$s_person_name = $this->request->data['person_name'];
$s_com_name = $this->request->data['com_name'];
$amt5 = $this->request->data['amt5'];
}
else
{
$s_res_id = (int)$this->request->data['res_id'];
$s_ih = $this->request->data['ih'];
}
//$s_tem1 = explode(',',$s_tem);
$s_cur_date = date('Y-m-d');
//$s_cur_date = new MongoDate(strtotime($s_cur_date));

$s_from2 = date("Y-m-d", strtotime($s_from));
//$s_from2 = new MongoDate(strtotime($s_from2));

//$s_to2 = date("Y-m-d", strtotime($s_to));
//$s_to2 = new MongoDate(strtotime($s_to2));




if($s_type == 2)
{
$s_ih1 = explode('/',$s_ih);

$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($res_id)));	
foreach($result1 as $collection)
{
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));	
foreach($result2 as $collection)
{
//$residing = (int)$collection['user']['residing'];
$user_name = $collection['user']['user_name'];
$wing = (int)$collection['user']['wing'];
$flat =(int)$collection['user']['flat'];
}
$flat1 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat,$wing)));
foreach($flat1 as $collection)
{
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$area = $collection['flat']['flat_area'];
//$flat_master_id = (int)$collection['flat']['flat_master_id'];
}
/*
$flat2 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_fetch'),array('pass'=>array($flat_type_id)));
foreach($flat2 as $collection)
{
$charge = $collection['flat_type']['charge'];	
$noc_charge = $collection['flat_type']['noc_charge'];
}
$flat3 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_master_fetch'),array('pass'=>array($flat_master_id)));
foreach($flat3 as $collection)
{
$sq_feet = (int)$collection['flat_master']['flat_area'];
}
*/
$l = (int)$this->autoincrement('adhoc_bill','adhoc_bill_id');
$total = 0;
for($p=0; $p<sizeof($s_ih1); $p++)
{
$s_ih2 = $s_ih1[$p];
$s_ih3 = explode(",",$s_ih2);
$ihid5 = (int)$s_ih3[0];
$amt = $s_ih3[1];

$k = (int)$this->autoincrement('ledger','auto_id');
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $z, 
"amount" => $amt, "amount_category_id" => 2, "table_name" => "adhoc_bill", "account_type" => 2, "account_id" => $ihid5, "current_date" => $s_cur_date,"society_id" => $s_society_id,"module_name"=>"Supplimentry Bill"));
$this->ledger->saveAll($multipleRowData);	
$total = $total + $amt;

$ih_det[] = $s_ih3;
}

$k = (int)$this->autoincrement('ledger','auto_id');
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $z, 
"amount" => $total, "amount_category_id" => 1, "table_name" => "adhoc_bill", "account_type" => 1,
"account_id" => $res_id, "current_date" => $s_cur_date,
"society_id" => $s_society_id,"module_name"=>"Supplimentry Bill"));
$this->ledger->saveAll($multipleRowData);

//////////////////////////
$this->loadmodel('adhoc_bill');
$order=array('adhoc_bill.adhoc_bill_id'=> 'DESC');
$cursor=$this->adhoc_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last22=$collection['adhoc_bill']["adhoc_bill_id"];
$last33=$collection['adhoc_bill']['receipt_id'];
}
if(empty($last22))
{
$adhoc_bill_id=0;
$receipt_id=1000;
}	
else
{	
$adhoc_bill_id=$last22;
$receipt_id=$last33;
}
$adhoc_bill_id++;
$receipt_id++;
////////////////////////

$this->loadmodel('adhoc_bill');
$multipleRowData = Array( Array("adhoc_bill_id" => $adhoc_bill_id, "receipt_id" => $receipt_id, "company_name"=> "",
"person_name"=>$s_res_id,"description"=>$s_desc,"date"=>$s_cur_date,"society_id"=>$s_society_id,"residential"=>"y" ,"g_total"=> $total,"bill_daterange_from"=>$s_from2,"remaining_amt"=>$total,
"bill_html"=>"","pay_status"=>0,"ih_detail"=>$ih_det));
$this->adhoc_bill->saveAll($multipleRowData);	
}
else
{
//////////////////////////
$this->loadmodel('adhoc_bill');
$order=array('adhoc_bill.adhoc_bill_id'=> 'DESC');
$cursor=$this->adhoc_bill->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last22=$collection['adhoc_bill']["adhoc_bill_id"];
$last33=$collection['adhoc_bill']['receipt_id'];
}
if(empty($last22))
{
$adhoc_bill_id=0;
$receipt_id=1000;
}	
else
{	
$adhoc_bill_id=$last22;
$receipt_id=$last33;
}
$adhoc_bill_id++;
$receipt_id++;
////////////////////////

$this->loadmodel('adhoc_bill');
$multipleRowData = Array( Array("adhoc_bill_id" => $adhoc_bill_id, "receipt_id" => $receipt_id,"company_name"=> $s_com_name,
"person_name"=>$s_person_name,"description"=>$s_desc,"date"=>$s_cur_date,"society_id"=>$s_society_id,"residential"=>"n","g_total"=> $amt5,"remaining_amt"=>$amt5,
"bill_daterange_from"=>$s_from2,"bill_html"=>"","pay_status"=>0));
$this->adhoc_bill->saveAll($multipleRowData);
}

/////////////START HTML BILL///////////////////
$date = date('d-M-Y');
if($s_type == 2)
{
$ih3 =explode('/',$s_ih);
}
$html='<center>
<div style="width:90%; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:90%; border:solid 1px;">
<br>
<table border="0">
<tr>
<th style="text-align:center;">
<p style="font-size:22px;">'.$society_name.' Society</p>
</th>
</tr>
<td style="text-align:center;">Regn# &nbsp; &nbsp; 
'.$society_reg_no.'
</td>
</tr>
<tr>
<td style="text-align:center;">
'.$society_address.'
</td>
</tr>
</table>
</div>
<div style="width:90%; border:solid 1px; border-top:none; border-bottom:none; overflow:auto;">
<table border="0" style="width:65%; float:left;">
<tr>
<th colspan="2" style="text-align:left;"><p style="font-size:14px;">Bill for the date :'. $s_from.'</p></th>
</tr>';
if($type == 2)
{
$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($s_res_id)));	
foreach($result1 as $collection)
{	
$user_id = (int)$collection['ledger_sub_account']['user_id'];
}
$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));	
foreach($result2 as $collection)
{
//$residing = (int)$collection['user']['residing'];
$user_name = $collection['user']['user_name'];
$wing = (int)$collection['user']['wing'];
$flat =(int)$collection['user']['flat'];
}

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
	
$html.='<tr>
<td style="text-align:left; width:30%;">
Name:
</td>
<td style="text-align:left;">'.$user_name.'</td>
</tr>
<tr>
<td style="text-align:left;">Flat No.</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>';
}
else if($type == 1)
{
$html.='<tr>
<td style="text-align:left; width:30%;">
Company Name:
</td>
<td style="text-align:left;">'.$s_com_name.'</td>
</tr>
<tr>
<td style="text-align:left;">Person Name:</td>
<td style="text-align:left;">'.$s_person_name.'</td>
</tr>';
}
$html.='</table>
<table border="0" style="width:30%; float:right;">
<tr>
<td style="text-align:left;">Bill No.:</td>
<td style="text-align:left;">'.$receipt_id.'</td>
</tr>
<tr>
<td style="text-align:left;">Bill Creation Date:</td>
<td style="text-align:left;">'.$date.'</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">'.$s_due_date.'</td>
</tr>';
if($type == 2)
{
$html.='<tr>
<td style="text-align:left;">AREA:</td>
<td style="text-align:left;">'.$area.' Sq Feet</td>
</tr>';
}
$html.='</table>
</div>
<div style="width:90.25%;">
<table border="1" style="width:100%;">
<tr>
<td style="width:80%; text-align:center;">Particulars</td>
<td style="text-align:center;">Amount(in Rs.)</td>
<tr>
<tr>
<td valign="top" style="height:200px;">';
if($s_type == 2)
{
$html.='<table border="0" style="width:100%;">';
for($q=0; $q<sizeof($ih_det); $q++)
{
$ih_detail2 = $ih_det[$q];
$ihid1 = (int)$ih_detail2[0];

$result3 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ihid1)));		
foreach($result3 as $collection)
{
$income_head_name = $collection['ledger_account']['ledger_name'];
//$income_head_id1 = (int)$collection['income_head']['auto_id'];
}

	
$html.='<tr>
<td style="text-align:left;">'.$income_head_name.'</td>
</tr>';
}
$html.='
</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">';
$total_amount2 = 0;
for($t=0; $t<sizeof($ih_det); $t++)
{
$ih_detail5 = $ih_det[$t];
$amt = $ih_detail5[1];
$amt2 = number_format($amt);
$html.='<tr>
<td style="text-align:center;">'.$amt2.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amt;
}
$gt = $total_amount2;
$html.='</table>';
}
else
{
$html.='
<table border="0" style="width:100%;">
<tr>
<td style="text-align:left;">Amount Applied</td>
</tr>';
$html.='</table>';
$amt6 = number_format($amt5);
$html.='<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:center;">'.$amt6.'</td>
</tr>';
$gt = $amt5;
$html.='</table>';
}
$gt2 = number_format($gt);
$html.='
</td>
</tr>
<tr>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:right;">Grand Total:</th>
</tr>
</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:center;">'.$gt2.'</th>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div style="width:90%; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Description:
</th>
</tr>
<tr>
<td>'.$desc.'</td>
</table>
</div>
<div style="width:90%; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Terms And Conditions:
</th>
</tr>';
for($r=0; $r<sizeof($terms_arr); $r++)
{
$tems_name = $terms_arr[$r];
$html.='<tr>
<td style="text-align:left;">'.$tems_name.'</td>
</tr>';
}
$html.='</table> 
</div>
<div style="width:90%; border:solid 1px; border-top:none;">

<table border="0" style="width:100%;">
<tr>
<td style="text-align:right;">
<p style="font-size:16px; margin-right:10%;"><b>'.$society_name.' Society</b></p>
</td>
</tr>
</table>
<br><br><br>
</div>
<br><br><br><br>
</div>';
$this->loadmodel('adhoc_bill');
$this->adhoc_bill->updateAll(array("bill_html" =>$html),array("adhoc_bill_id" =>$adhoc_bill_id));	

/////////////////////////////////END HTML BILL/////////////////////////////////////////
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Supplimentry Bill</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Bills generated successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="it_supplimentry_bill" class="btn blue">OK</a>
</div>
</div>
<?php
}
}

//////////////////////// End supplimentry bill view2(Accounts)/////////////////////////////

///////////////////// Start regular report show ajax///////////////////////////////
function regular_report_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


$this->loadmodel('society');
$conditions=array("society_id"=> $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)	
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$wise = (int)$this->request->query('wise');
if($wise == 1)
{
$wing = (int)$this->request->query('wing');
$this->set('wing',$wing);
}
else if($wise == 2)
{
$user_id = (int)$this->request->query('user');
$this->set('user_id',$user_id);
}
else if($wise == 3)
{
$bill_number = $this->request->query('user');
$this->set('bill_number',$bill_number);
}
$this->set('wise',$wise);
$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id,"approve_status" => 2);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

}
///////////////////////// End regular report show ajax///////////////////////////////

////////////////////////Start It Reports Regular (Accounts)////////////////////////////
function it_reports_regular()
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

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id,"approve_status"=>2);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
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
//////////////////////// End It Reports Regular (Accounts)//////////////////////

///////////////////////// Start In head report (Accounts)//////////////////////////
function in_head_report()
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

$this->loadmodel('regular_bill');
$condition=array('society_id'=>$s_society_id,"approve_status"=>2);
$result2=$this->regular_bill->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);

}
///////////////////////// End In head report (Accounts)//////////////////////////

/////////////////////// Start It Reports Supplimentry Bill (Accounts)///////////////

function it_reports_supplimentry()
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

$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor1=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
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
/////////////////////////////////////////////////////// End It Reports Supplimentry Bill (Accounts)//////////////////////////////////////////////////////

///////////////////////////////////////////// Start It Reports Supplimentry Ajax (Accounts)/////////////////////////////////////////////////////////////
function it_reports_supplimentry_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		

$this->set('s_society_id',$s_society_id);

$c = (int)$this->request->query('c');
$this->set('c',$c);

$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id,"residential"=> "y");
$cursor1=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id,"residential"=> "n");
$cursor2=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor3=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);	

}
//////////////////////////////// End It Reports Supplimentry Ajax (Accounts)/////////////////////////////////////////

//////////////////////// Start income Head report Excel///////////////////////////////
function income_head_report_excel()
{
$this->layout="";
$filename="Income Head  Report";
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

$from = $this->request->query('f');
$to = $this->request->query('t');

$m_from = date("Y-m-d", strtotime($from));
//$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

/////////////////////////////
$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
$society_reg_num = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
}

////////////////////////////////////////
$this->loadmodel('flat_type');
$conditions=array("society_id"=>$s_society_id);
$cursor9 = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor9 as $collection) 
{
$charge = $collection['flat_type']['charge'];	
$income_heade_charge[] = $charge[0];
}
for($i=0; $i<sizeof($charge); $i++)
{
$inc_id = $charge[$i];
$income_head_charge[] = $inc_id[0];
}
$cnt=0;
for($y=0; $y<sizeof($income_head_charge); $y++)
{
$total[]="";	
$cnt++;	
}
$cnt = $cnt+6;
/////////////////////////////////////////
$excel="<table border='1'>
<thead>
<tr>
<th colspan='$cnt' style='text-align:center;'>$society_name Society</th>
</tr>
<tr>
<th style='text-align:left;'>Bill No.</th>
<th style='text-align:left;'>Flat No.</th>
<th style='text-align:left;'>Name of Resident</th>
<th style='text-align:left;'>Area (Sq.Ft.)</th>";
for($r=0; $r<sizeof($income_head_charge); $r++)
{
$abc = (int)$income_head_charge[$r];	
$ledgerac = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($abc)));			
foreach($ledgerac as $collection2)
{
$ac_name = $collection2['ledger_account']['ledger_name'];
}
$excel.="<th style='text-align:left;'>$ac_name</th>";
}
$excel.="
<th style='text-align:left;'>Non Occupancy Charges</th>
<th style='text-align:left;'>Total</th>
</tr>";
$total_noc_amt = 0;
$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'ASC');
$conditions=array("society_id"=>$s_society_id);
$cursor2=$this->regular_bill->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor2 as $collection)
{
$bill_id = $collection['regular_bill']['receipt_id'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$ih_detail2 = $collection['regular_bill']['ih_detail'];
$noc_amt = $collection['regular_bill']['noc_charge'];
$date = $collection['regular_bill']['date'];

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$user_name = $collection['user']['user_name'];
}
$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat_id,$wing_id)));	
foreach($result5 as $collection)
{
$area = $collection['flat']['flat_area'];
$unit_number = $collection['flat']['flat_name'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action'=>'wing_flat'),array('pass'=>array($wing_id,$flat_id)));
if($m_from<= $date && $m_to>= $date)
{
$excel.="<tr>
<td style='text-align:right;'>$bill_id</td>
<td style='text-align:left;'>$wing_flat</td>
<td style='text-align:left;'>$user_name</td>
<td style='text-align:left;'>$area &nbsp; sq.Ft.</td>";
$total_amt = 0;
for($y=0; $y<sizeof($income_head_charge); $y++)
{
$income_head_arr_id = $income_head_charge[$y];	
$nnn = 55;
for($r=0; $r<sizeof($ih_detail2); $r++)
{
$ih_detail1 = $ih_detail2[$r];	
$ih_id1 = $ih_detail1[0];
$amount = $ih_detail1[1];
if($income_head_arr_id == $ih_id1)
{
$total[$y] = $total[$y] + $amount;
$excel.="<td style='text-align:right;'>";
 
$amount2 = number_format($amount);
$excel.="$amount2</td>";
$total_amt=$total_amt+$amount;
$nnn = 555;
break;
}
}
if($nnn == 55)
{
$excel.="<td style='text-align:right;'> 0 </td>";
}
}
$total_noc_amt = $total_noc_amt + $noc_amt;
$total_amt=$total_amt+$noc_amt;

$excel.="<td style='text-align:right;'>";
$noc_amt2 = number_format($noc_amt);
$excel.="$noc_amt2</td>
<td style='text-align:right;'>";
$total_amt2 = number_format($total_amt);
$excel.="$total_amt2</td>
</tr>";
}
}
$excel.="<tr>
<th colspan='4' style='text-align:right;'>Grand Total</th>";
$grand_total = 0;
for($h=0; $h<sizeof($total); $h++)
{  
$excel.="<th style='text-align:right;'>";
@$totalh2 = number_format($total[$h]);
$excel.="$totalh2</th>";
$grand_total = $grand_total + $total[$h];
}
$grand_total = $grand_total + $total_noc_amt;
$excel.="<th style='text-align:right;'>";
$total_noc_amt2 = number_format($total_noc_amt);
$excel.="$total_noc_amt2</th>
<th style='text-align:right;'>";
$grand_total2 = number_format($grand_total);
$excel.="$grand_total2</th>
</tr>
</table>";

echo $excel;

}
//////////////////////// End income Head report Excel///////////////////////////////

/////////////////// Start Select Income Heads (Accounts)//////////////////////////////
function select_income_heads()
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

if(isset($this->request->data['sub']))
{
$cur_date = date('Y-m-d');
$cur_date = new MongoDate(strtotime($cur_date));

$ih_arr = $this->request->data['i_head'];

$this->loadmodel('society');
$this->society->updateAll(array('income_head'=> $ih_arr),array('society_id'=>$s_society_id));


?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Selection of Income Heads</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Record Updated Successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="select_income_heads" class="btn blue">OK</a>
</div>
</div>
<?php
}

$this->loadmodel('accounts_group');
$conditions=array("delete_id"=>0,"accounts_id"=>3);
$cursor1 = $this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('income_heads');
$conditions=array("delete_id"=>0,"society_id"=>$s_society_id);
$cursor2 = $this->income_heads->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor3 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

}
/////////////////////End Select Income Heads (Accounts)//////////////////////////////

/////////////////////////////////// Start It Setup (Accounts) ///////////////////////////////////////////////
function it_setup()
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

$tems_id = (int)$this->request->query('d');

if(!empty($tems_id))
{
$this->loadmodel('terms_condition');
$this->terms_condition->updateAll(array("status" => 2),array("terms_conditions_id" => $tems_id));
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Terms & Condition Deleted Successfully
</div> 
<div class="modal-footer">
<a href="it_setup"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php	
}

if(isset($this->request->data['sub']))
{
$terms=$this->request->data['terms'];

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)		
{
$terms_con = @$collection['society']['terms_conditions'];
}
$terms_con[] = $terms;

$this->loadmodel('society');
$this->society->updateAll(array("terms_conditions" => $terms_con),array("society_id" => $s_society_id));	
?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Terms & Condition Added Successfully
</div> 
<div class="modal-footer">
<a href="it_setup"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php	
}
if(isset($this->request->data['del']))
{
$del_id = (int)$this->request->data['arr_id'];

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$terms_arr = $collection['society']['terms_conditions'];
}
for($k=0; $k<sizeof($terms_arr); $k++)
{
$terms_name = $terms_arr[$k];

if($k == $del_id)
continue;

$terms_new_arr[] = $terms_name;
}
$this->loadmodel('society');
$this->society->updateAll(array("terms_conditions" => $terms_new_arr),array("society_id" => $s_society_id));
}


if(isset($this->request->data['edit']))
{
$terms_name = $this->request->data['name'];
$edit_id = (int)$this->request->data['arr_id'];

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$terms_arr = $collection['society']['terms_conditions'];
}
$terms_arr[$edit_id] = $terms_name;

$this->loadmodel('society');
$this->society->updateAll(array("terms_conditions" => $terms_arr),array("society_id" => $s_society_id));
}

$this->loadmodel('terms_conditions');
$conditions=array("society_id"=>$s_society_id,"status"=>1);
$cursor1=$this->terms_conditions->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
	
$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

								
}

///////////////////////////////// End It Setup (Accounts) /////////////////////////////////////////////////

/////////////////////////Start Master rate Card(Accounts)//////////////////////////////
function master_rate_card()
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
$nnn = 5;
$this->set('nnn',$nnn);

if(isset($this->request->data['sub']))
{

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2 = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor2 as $collection)
{
$income_head_arr = $collection['society']['income_head'];
}


$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['flat_type']['auto_id'];
$rate_arr = array();
$rate_arri = array();
for($l=0; $l<sizeof($income_head_arr); $l++)
{
$auto_id2 = (int)$income_head_arr[$l];


$charge_type_id = (int)$this->request->data['charge_type'.$auto_id1.$auto_id2];
$charge = (int)$this->request->data['charge'.$auto_id1.$auto_id2];
$arr = array($auto_id1,$auto_id2,$charge_type_id,$charge);
$arri = implode('-',$arr);
$rate_arri[] = $arri;
$rate_arr[] = $arr;
}
$main_arri = implode("/",$rate_arri);
$show_arr[] = $main_arri;

//$this->loadmodel('flat_type');
//$this->flat_type->updateAll(array('charge' => $rate_arr),array('auto_id' => $auto_id));
}
$show_arri = implode(',',$show_arr);

$this->response->header('Location','rate_card_view2?arr='.$show_arri.' ');
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['flat_type']['auto_id'];
@$charge = $collection['flat_type']['charge'];



if(isset($this->request->data['sub'.$auto_id]))
{
$count = $this->request->data['count'.$auto_id];
$n=0;
for($k=0; $k<sizeof($charge); $k++)
{
$n++;
$charge2 = $charge[$k];
$ih_id = (int)$charge2[0];
$tp_id = (int)$this->request->data['tp'.$n];
$amt = $this->request->data['amt'.$n];
$arr = array($ih_id,$tp_id,$amt);
$rat_arr2[] = $arr;
}

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('charge' => $rat_arr2),array('auto_id' => $auto_id));


}
}


$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor2 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor2',$cursor2);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor3 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

}
//////////////////////// End Master rate Card(Accounts)//////////////////////////////

/////////////////////////// Start master rate card view (Accounts)/////////////////////////////////
function master_rate_card_view()
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
$s_user_id = (int)$this->Session->read('user_id');	


$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id,"status"=>0);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$result2);


$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor3 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor3',$cursor3);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor4 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor4',$cursor4);

}
/////////////////////////// End master rate card view (Accounts)/////////////////////////////////
////////////////////// Start Supplimentry Vali (Accounts)////////////////////////////////
function supplimentry_vali()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$cc = (int)$this->request->query('ss');
$this->set('cc',$cc);
}
////////////////////// End Supplimentry Vali (Accounts)////////////////////////////////
/////////////////////Start Financial Vali Ajax(Accounts)//////////////////////////////
function regular_vali()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$cc = (int)$this->request->query('ss');
$this->set('cc',$cc);

}
/////////////////////End Financial Vali Ajax(Accounts)//////////////////////////////


///////////////////// Start Master rate Card Edit ///////////////////////////////////
function master_rate_card_edit($auto_id5=null)
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}


$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$nnn = 5;
$this->set("nnn",$nnn);
if(isset($this->request->data['sub']))
{
$tp_id = (int)$this->request->data['au'];

$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$income_head_arr = $collection['society']['income_head'];
}
for($g=0; $g<sizeof($income_head_arr); $g++)
{
$auto_id = (int)$income_head_arr[$g];
$tp = (int)$this->request->data['tp'.$auto_id];
$amt = $this->request->data['amt'.$auto_id];
$ch = array($auto_id,$tp,$amt);
$ch2 = implode(",",$ch);
$ch3[] = $ch2;
$ch4 = implode("/",$ch3);
}
$nnn = 55;
$this->set("nnn",$nnn);
$this->set("ch4",$ch4);
$this->set("au",$tp_id);

//$this->loadmodel('flat_type');
//$this->flat_type->updateAll(array('charge'=>$ch2),array('auto_id'=>$tp_id));

}

if(isset($this->request->data['sub2']))
{
$au = (int)$this->request->data['auto_id'];
echo $ch4 = $this->request->data['val'];
$ch3 = explode("/",$ch4);
echo "<br>";
for($i=0; $i<sizeof($ch3); $i++)
{
$ch2 = $ch3[$i];
$ch1 = explode(",",$ch2);
$a1 = (int)$ch1[0];
$a2 = (int)$ch1[1];
$a3 = $ch1[2];
$ch = array($a1,$a2,$a3);
$ch_arr[] = $ch;
}
$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('charge'=>$ch_arr),array('auto_id'=>$au));
//$this->response->header('Location','master_rate_card_view');
$this->redirect(array('controller' => 'Incometrackers','action' => 'master_rate_card_view'));
}

$auto_id5 = (int)$auto_id5;
$this->set('auto_id',$auto_id5);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id,"auto_id"=>$auto_id5,"status"=>0);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);

$this->loadmodel('income_head');
$condition=array('society_id'=>$s_society_id,"delete_id"=>0);
$cursor2 = $this->income_head->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$cursor2);

$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor3 = $this->society->find('all',array('conditions'=>$condition)); 
$this->set('cursor3',$cursor3);

}
///////////////////// End Master rate Card Edit ///////////////////////////////////

/////////////////////////// Start Master Noc (Accounts)/////////////////////////////
function master_noc()
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
if(isset($this->request->data['sub']))
{

$this->loadmodel('flat_type');
$order=array('flat_type.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$condition,'order' => $order)); 
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['flat_type']['auto_id'];
$fl_tp_id = (int)$collection['flat_type']['flat_type_id'];
$arr2 = array();

$tp = (int)$this->request->data['ctp'.$auto_id1];
if($tp == 4)
{
$arr1 = array($tp,$fl_tp_id);
$arr2 = implode(",",$arr1);
}
else
{
$amt = $this->request->data['amt'.$auto_id1];
$arr1 = array($tp,$fl_tp_id,$amt);
$arr2 = implode(",",$arr1);
}
$show_arr[] = $arr2;

}
$show_arr2 = implode("/",$show_arr);
//$this->loadmodel('flat_type');
//$this->flat_type->updateAll(array('noc_charge' => $arr1),array('auto_id' => $auto_id1));
$this->response->header('Location','noc_view2?arr='.$show_arr2.' ');
}

$this->loadmodel('flat_type');
$order=array('flat_type.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$condition,'order' => $order)); 
$this->set('cursor1',$cursor1);

$this->loadmodel('noc_charge');
$order=array('noc_charge.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor2 = $this->noc_charge->find('all',array('conditions'=>$condition,'order' => $order)); 
$this->set('cursor2',$cursor2);

}


function master_noc_status()
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
$conditions=array('society_id'=>$s_society_id,'deactive'=>0);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user',$result);	

if ($this->request->is('post')) 
{

foreach($result as $data)
{
	  $user_id=$data['user']['user_id'];
	   $flat_id1=(int)$data['user']['flat'];
	   $value =(int)@$this->request->data[$user_id];
	if($value==1)
	{
		
		$this->loadmodel('flat');
		$this->flat->updateAll(array('noc_ch_tp'=>2),array('flat_id'=>$flat_id1));
		
	}
	else{
		
		$this->loadmodel('flat');
		$this->flat->updateAll(array('noc_ch_tp'=>1),array('flat_id'=>$flat_id1));
		
	}
		
	
}

}

}
///////////////////////// End master Noc (Accounts)/////////////////////////////////

////////////////////////// Start master noc view/////////////////////////////////////////////////
function master_noc_view()
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
$s_user_id = (int)$this->Session->read('user_id');	

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id,"status"=>0);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


}
////////////////////////// End master noc view/////////////////////////////////////////////////
///////////////////// Start IT Penalty (Accounts)///////////////////////////////////

function it_penalty()
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

if(isset($this->request->data['sub']))
{
//$base = (int)$this->request->data['base'];
$type = (int)$this->request->data['type'];
$tax = $this->request->data['tax'];

$this->loadmodel('society');
$this->society->updateAll(array('tax'=>$tax,"tax_type"=>$type),array('society_id'=>$s_society_id));


?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Penalty</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Record Updated Successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="it_penalty" class="btn blue">OK</a>
</div>
</div>
<?php
}

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$tax = @$collection['society']['tax'];
$tax_type = @$collection['society']['tax_type'];
}
$this->set("tax",$tax);
$this->set("tax_type",$tax_type);
}

///////////////////// End IT Penalty (Accounts)///////////////////////////////////

/////////////////////// Start NOC Edit ////////////////////////////////////////////////////////
function noc_edit()
{
$this->layout="session";
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');
$nnn = 5;
$this->set("nnn",$nnn);
if(isset($this->request->data['sub']))
{
$aut = (int)$this->request->data['au'];

$type = (int)$this->request->data['tp'];
if($type == 4)
{
$ch = array($type);
$ch1 = implode(",",$ch);
}
else
{
$amt = $this->request->data['amt'];
$ch = array($type,$amt);
$ch1 = implode(",",$ch);
}
$nnn = 55;
$this->set("nnn",$nnn);
$this->set("au",$aut);
$this->set('ch1',$ch1);
//$this->loadmodel('flat_type');
//$this->flat_type->updateAll(array('noc_charge'=>$ch),array('auto_id'=>$aut));

//$this->response->header('Location', 'master_noc_view');
}

if(isset($this->request->data['sub2']))
{
$ch2 = $this->request->data['val'];
$aut2 = (int)$this->request->data['auto_id'];
$ch3 = explode(",",$ch2);

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('noc_charge'=>$ch3),array('auto_id'=>$aut2));

$this->response->header('Location', 'master_noc_view');

}
$auto_id = (int)$this->request->query('a');
$this->set('auto_id',$auto_id);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id,"auto_id"=>$auto_id,"status"=>0);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);
}
//////////////////////// End NOC Edit //////////////////////////////////////////////////////////////

/////////////////// /// Start in report ajax ////////////////////////////////////
function in_report_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$un_id = (int)$this->request->query('un');
$this->set('un',$un_id);
$this->loadmodel('regular_bill');
$condition=array('society_id'=>$s_society_id,"one_time_id"=>$un_id);
$result2=$this->regular_bill->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);

$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
$society_reg_nu = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
}
$this->set('society_name',$society_name);
$this->set('society_reg_nu',$society_reg_nu);
$this->set('society_address',$society_address);
}
/////////////////// /// End in report ajax ////////////////////////////////////

///////////////////////// Start In Head Excel///////////////////////////////////////
function in_head_excel()
{
$this->layout="";
$filename="Regular_Bill";
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

$un = (int)$this->request->query('f');

$this->loadmodel('regular_bill');
$condition=array('society_id'=>$s_society_id,"one_time_id"=>$un);
$cursor = $this->regular_bill->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$ih_arr = $collection['regular_bill']['ih_detail'];
break;
}
$excel="<table border='1'>
<tr>
<th>Sr.No.</th>
<th>Bill No.</th>
<th>Name of Resident</th>";
for($k=0; $k<sizeof(@$ih_arr); $k++)
{
$sub_arr = $ih_arr[$k];
$ih_id1 = (int)$sub_arr[0];

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih_id1)));
foreach($result as $collection)
{
$in_name = $collection['ledger_account']['ledger_name'];	
}
if($ih_id1 != 43)
{
$ih_tt_amt[] = 0;
$excel.="<th style='text-align:center;'>$in_name</th>";
}}
$excel.="
<th>Non Occupancy charges</th>
<th>Current Amount</th>
<th>Over Due Amount</th>
<th>Penalty Amount</th>
<th>Grand Total Amount</th>
</tr>";
$m=0;
$tt_current_amt = 0;
$tt_over_due_amt = 0;
$total_penalty_amt = 0;
$tt_gt_amt = 0;
$tt_noc_amt = 0;


$this->loadmodel('regular_bill');
$condition=array('society_id'=>$s_society_id,"one_time_id"=>$un);
$cursor = $this->regular_bill->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$current_amt = $collection['regular_bill']['total_amount'];
$over_due_amt = $collection['regular_bill']['due_amount'];
$penalty_amt = $collection['regular_bill']['due_amount_tax'];
$gt_amt = $collection['regular_bill']['g_total'];
$ih_det = $collection['regular_bill']['ih_detail'];

$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($result2 as $collection)
{
$user_name = $collection['user']['user_name'];
}
$tt_current_amt = $tt_current_amt + $current_amt;
$tt_over_due_amt = $tt_over_due_amt + $over_due_amt;
$total_penalty_amt = $total_penalty_amt + $penalty_amt;
$tt_gt_amt = $tt_gt_amt + $gt_amt;

$m++;
$excel.="<tr>
<td style='text-align:center;'>$m</td>
<td style='text-align:center;'>$bill_no</td>
<td style='text-align:center;'>$user_name</td>";
for($x=0; $x<sizeof(@$ih_det); $x++)
{
$charge3 = $ih_det[$x];
$ih_id5 = (int)$charge3[0];
if($ih_id5 != 43)
{	
$amt = $charge3[1];
$ih_tt_amt[$x] = $ih_tt_amt[$x] + $amt;

$excel.="<td style='text-align:center;'>$amt</td>";
}}
$n=5;
for($y=0; $y<sizeof(@$ih_det); $y++)
{
$charge4 = $ih_det[$y];
$ih_id6 = (int)$charge4[0];
if($ih_id6 == 43)
{
$n=55;
$amt2 = $charge4[1];
$tt_noc_amt = $tt_noc_amt + $amt2;
$excel.="<td style='text-align:center;'>$amt2</td>";
}}

if($n == 5)
{
$excel.="<td style='text-align:center;'> 0 </td>";	
}
$excel.="
<td style='text-align:center;'>$current_amt</td>
<td style='text-align:center;'>";
if(!empty($over_due_amt)) { $excel.="$over_due_amt";
} else { $excel.="0"; } 
$excel.="</td>
<td style='text-align:center;'>";
if(!empty($over_due_amt)) { $excel.="$penalty_amt";
} else { $excel.="0"; } 
$excel.="
</td>
<td style='text-align:center;'>$gt_amt</td>
</tr>";
}
$excel.="<tr>
<th colspan='3'>Total</th>";
for($v=0; $v<sizeof(@$ih_tt_amt); $v++)
{
$tt_amt = $ih_tt_amt[$v];	
$excel.="<th>$tt_amt</th>";
}
$excel.="<th>$tt_noc_amt</th>
<th>$tt_current_amt</th>
<th>$tt_over_due_amt</th>
<th>$total_penalty_amt</th>
<th>$tt_gt_amt</th>
</tr>
</table>";

echo $excel;
}
///////////////////////// End In Head Excel///////////////////////////////////////

////////////////// Start Regular Bill Excel (Accounts)//////////////////////////////
function regular_bill_excel()
{
$s_society_id=(int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');
$this->layout="";
$filename= "Regular_Bill";
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
else if($wise == 3)
{
$bill_id = $this->request->query('u');
}
$this->loadmodel('society');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$m_from = date("Y-m-d", strtotime($from));
//$m_from = new MongoDate(strtotime($m_from1));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to1));

$excel = "<table border='1'>
<tr>
<th colspan='7' style='text-align:center;'>
Regular Bill Report  ($society_name)
</th>
<tr>
<th style='text-align:center;'>#</th>
<th style='text-align:center;'>Generated On</th>
<th style='text-align:center;'>Flat</th>
<th style='text-align:center;'>Member Name</th>
<th style='text-align:center;'>Period From</th>
<th style='text-align:center;'>Period To</th>
<th style='text-align:center;'>Amount</th>
</tr>";

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
$i=0;
$total_amt = 0;
foreach($cursor as $collection)
{
$i++;
$one_time_id =(int)$collection['regular_bill']["one_time_id"];
$regular_bill_id=(int)$collection['regular_bill']["regular_bill_id"];
$bill_daterange_from=$collection['regular_bill']["bill_daterange_from"];
$bill_daterange_from2= date('d-m-Y',strtotime($bill_daterange_from));
$bill_daterange_to=$collection['regular_bill']["bill_daterange_to"];
$bill_daterange_to2= date('d-m-Y',strtotime($bill_daterange_to));
$bill_for_user=(int)$collection['regular_bill']["bill_for_user"];
$bill_html=$collection['regular_bill']["bill_html"];
$g_total=$collection['regular_bill']["g_total"];
$date=$collection['regular_bill']["date"]; 
$date2= date('Y-m-d',strtotime($date));
$pay_status=(int)@$collection['regular_bill']["pay_status"];
$receipt_id = $collection['regular_bill']['receipt_id'];
				
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

if($wise == 2)
{
if($user_id == $bill_for_user)
{
if($m_from <= $date2 && $m_to >= $date2)
{
$date = date('d-m-Y',strtotime($date));						
$total_amt = $total_amt + $g_total;									
$excel.="								
<tr>
<td>$i</td>
<td>$date</td>
<td>$wing_flat</td>
<td>$user_name</td>
<td>$bill_daterange_from2</td>
<td>$bill_daterange_to2</td>
<td>$g_total</td>
</tr>";
}
}}

else if($wise == 1)
{
if($wing_id == $wing)
{
if($m_from <= $date2 && $m_to >= $date2)
{
$date = date('d-m-Y',strtotime($date));						
$total_amt = $total_amt + $g_total;									
$excel.="								
<tr>
<td>$i</td>
<td>$date</td>
<td>$wing_flat</td>
<td>$user_name</td>
<td>$bill_daterange_from2</td>
<td>$bill_daterange_to2</td>
<td>$g_total</td>
</tr>";
}
}
}
else if($wise == 3)
{
if($bill_id == $receipt_id)
{
if($m_from <= $date2 && $m_to >= $date2)
{
$date = date('d-m-Y',strtotime($date));						
$total_amt = $total_amt + $g_total;	
$excel.="								
<tr>
<td>$i</td>
<td>$date</td>
<td>$wing_flat</td>
<td>$user_name</td>
<td>$bill_daterange_from2</td>
<td>$bill_daterange_to2</td>
<td>$g_total</td>
</tr>";
}
}
}
}
$excel.="
<tr>
<th colspan='6'>Total</th>
<th>$total_amt</th>
</tr>";


$excel.="</table>";
echo $excel;
}
////////////////// End Regular Bill Excel (Accounts)////////////////////////////////

////////////////////// Start Supplimentry reports show ajax//////////////////////////
function supplimentry_reports_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id"=> $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

$from =  $this->request->query('date1');
$to = $this->request->query('date2');
$tp = $this->request->query('tp');
/*
$wise = (int)$this->request->query('w');
$this->set('wise',$wise);
if($wise == 1)
{
$wing = (int)$this->request->query('wi');
$this->set('wing',$wing);
}
else if($wise == 2)
{
$user = (int)$this->request->query('u');
$this->set('user',$user);
}*/
$this->set('from',$from);
$this->set('to',$to);
$this->set('tp',$tp);




$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor1=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}
////////////////////// Start Supplimentry reports show ajax//////////////////////////

///////////////////// Start supplimentry Bill Excel/////////////////////////////////

function supplimentry_bill_excel()
{
$this->layout="";
$filename="Supplimentry_Bill";
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
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$society_name = $collection['society']['society_name'];
}


$from = $this->request->query('f');
$to = $this->request->query('t');
$tp = $this->request->query('tp');

$m_from = date("Y-m-d", strtotime($from));
//$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

if($tp == 1)
{
$excel="<table border='1'>
<tr>
<th colspan='7' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Bill Type</th>
<th>Member Name</th>
<th>Bill Date</th>

<th>Bill Amount</th>
</tr>";

$grand_total = 0;
$i=0;
$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$adhoc_bill= (int)$collection['adhoc_bill']["adhoc_bill_id"];
$pay_status=$collection['adhoc_bill']["pay_status"];
$date=$collection['adhoc_bill']["date"];
$residential=$collection['adhoc_bill']["residential"];
$g_total=$collection['adhoc_bill']["g_total"];
$html_bill = $collection['adhoc_bill']['bill_html'];
$bill_date_from = $collection['adhoc_bill']['bill_daterange_from'];
//$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',strtotime($bill_date_from));
//$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);

if($residential=="y")
{
$d_user_id=(int)$collection['adhoc_bill']["person_name"];
$result_user55 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($d_user_id)));
foreach($result_user55 as $collection)
{
$d_user_id2 = (int)$collection['ledger_sub_account']['user_id'];	
}
$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($d_user_id2)));
foreach ($result_user as $collection) 
{
$wing_id = (int)$collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$user_name = $collection['user']['user_name'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));									
$bill_for = $wing_flat;
$bill_type = "Residential";
}

if($residential=="n")
{
$user_name=$collection['adhoc_bill']["person_name"];
//$bill_for="Non-residential";
$bill_type = "Non-residential";
$wing_flat = "";
}

if($m_from <= $date && $m_to >= $date)
{
$i++;
$date = date('d-m-Y',strtotime($date));
$grand_total = $grand_total + $g_total;
$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$bill_type</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$g_total</td>
</tr>";
}}
$excel.="<tr>
<th colspan='6'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
else if($tp == 2)
{
$excel="<table border='1'>
<tr>
<th colspan='6' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
</tr>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Member Name</th>
<th>Bill Date</th>
<th>Bill Amount</th>
</tr>";

$grand_total = 0;
$i=0;
$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{

$adhoc_bill= (int)$collection['adhoc_bill']["adhoc_bill_id"];
$pay_status=$collection['adhoc_bill']["pay_status"];
$date=$collection['adhoc_bill']["date"];
$residential=$collection['adhoc_bill']["residential"];
$g_total=$collection['adhoc_bill']["g_total"];
$html_bill = $collection['adhoc_bill']['bill_html'];
$bill_date_from = $collection['adhoc_bill']['bill_daterange_from'];
//$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',strtotime($bill_date_from));
//$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);

if($residential=="y")
{
$d_user_id=(int)$collection['adhoc_bill']["person_name"];
$result_user55 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($d_user_id)));
foreach($result_user55 as $collection)
{
$d_user_id2 = (int)$collection['ledger_sub_account']['user_id'];	
}
$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($d_user_id2)));
foreach ($result_user as $collection) 
{
$wing_id = (int)$collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$user_name = $collection['user']['user_name'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));									
$bill_for = $wing_flat;

$bill_type = "Residential";

if($m_from <= $date && $m_to >= $date)
{
	$i++;
$date = date('d-m-Y',strtotime($date));
$grand_total = $grand_total + $g_total;

$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$g_total</td>
</tr>";
}}}
$excel.="<tr>
<th colspan='5'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
else if($tp == 3)
{
$excel="<table border='1'>
<tr>
<th colspan='6' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
</tr>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Member Name</th>
<th>Bill Date</th>
<th>Bill Amount</th>
</tr>";
$grand_total = 0;
$i=0;
$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)  
{

$adhoc_bill= (int)$collection['adhoc_bill']["adhoc_bill_id"];
$pay_status=$collection['adhoc_bill']["pay_status"];
$date=$collection['adhoc_bill']["date"];
$residential=$collection['adhoc_bill']["residential"];
$g_total=$collection['adhoc_bill']["g_total"];
$html_bill = $collection['adhoc_bill']['bill_html'];
$bill_date_from = $collection['adhoc_bill']['bill_daterange_from'];
//$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',strtotime($bill_date_from));
//$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);	
if($residential=="n")
{
$user_name=$collection['adhoc_bill']["person_name"];
//$bill_for="Non-residential";
$bill_type = "Non-residential";
$wing_flat = "";

if($m_from <= $date && $m_to >= $date)
{
$i++;
$date = date('d-m-Y',strtotime($date));
$grand_total = $grand_total + $g_total;
$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$g_total</td>
</tr>";
}}}
$excel.="<tr>
<th colspan='5'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
echo $excel;

}
///////////////////// End supplimentry Bill Excel/////////////////////////////////

////////////////////////////////////////////////// Start Income Heads Report (Accounts)//////////////////////////////////////////////////////////////////
function income_heads_report()
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
/////////////////////////// End Income Heads Report (Accounts)///////////////////////

//////////////////////////// Start Income Heads Report Ajax(Accounts)///////////////
function income_heads_report_ajax()
{
$this->layout='blank';
$s_role_id= (int)$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
$society_reg_num = $collection['society']['society_reg_num'];
$society_address = $collection['society']['society_address'];
}
$this->set('society_name',$society_name);
$this->set('society_reg_num',$society_reg_num);
$this->set('society_address',$society_address);

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=> 'ASC');
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor1',$cursor1);	

$this->loadmodel('regular_bill');
$order=array('regular_bill.receipt_id'=> 'ASC');
$conditions=array("society_id"=>$s_society_id);
$cursor2=$this->regular_bill->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor2',$cursor2);	

$this->loadmodel('flat_type');
$conditions=array("society_id"=>$s_society_id);
$cursor9 = $this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('cursor9',$cursor9);


}
////////////////////////////// End Income Heads Report Ajax(Accounts)////////////////
///////////////////////////////// Start Regular Bill Pdf(Accounts)///////////////////////////////////////
function regular_bill_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$bill_id = (int)$this->request->query('p');
$this->set('bill_id',$bill_id);

$this->loadmodel('regular_bill');
$conditions=array("regular_bill_id" => $bill_id);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name=$collection['society']["society_name"];
$so_reg_no = $collection['society']['society_reg_num'];
$so_address = $collection['society']['society_address'];	
}
$this->set("society_name",$society_name);
$this->set("so_reg_no",$so_reg_no);
$this->set("so_address",$so_address);

}
/////////////////////////////////// End Regular Bill Pdf(Accounts)/////////////////////////////////////////////////////

///////////////////////////////////////////// Start Supplimentry Bill Pdf (Accounts)////////////////////////////////////////////////////////////////////
function supplimentry_bill_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$bill_id = (int)$this->request->query('p');
$this->set('bill_id',$bill_id);

$this->loadmodel('adhoc_bill');
$conditions=array("adhoc_bill_id" => $bill_id);
$cursor1=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$tems_arr = $collection['society']['terms_conditions'];
}
$this->set('tems_arr',$tems_arr);






}
//////////////////////////////////////////// End Supplimentry Bill Pdf (Accounts)///////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Start Regular Bill View (Accounts)//////////////////////////////////////////////////////////////
function regular_bill_view($auto_id=null)
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$auto_id = (int)$auto_id;

$this->loadmodel('regular_bill');
$conditions=array("regular_bill_id"=>$auto_id,"society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['regular_bill']['bill_html'];
$receipt_id = $collection['regular_bill']['receipt_id'];
$one_time_id = (int)$collection['regular_bill']['one_time_id'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$flat_id = (int)$collection['regular_bill']['flat_id'];
}

$this->set('bill_html',$bill_html);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2_society_id=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2_society_id',$cursor2_society_id);


$one_time_id2 = (int)$one_time_id-1;



$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"status"=>1,"bill_for_user"=>$user_id,"flat_id"=>$flat_id,"one_time_id"=>$one_time_id2);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
@$receipt_id2 = (int)$collection['regular_bill']['receipt_id'];
}

$this->loadmodel('cash_bank');
$conditions=array("bill_reference"=>@$receipt_id2,"society_id" => $s_society_id,"module_id"=>1);
$result_receipt=$this->cash_bank->find('all',array('conditions'=>$conditions,'limit'=>1));
$this->set('result_receipt',$result_receipt);

}
////////////////////////////////// End Regular Bill View (Accounts)//////////////////////////////////////////

/////////////////////// Start Rate Card View2 (Accounts)/////////////////////////////

function rate_card_view2()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

//$ihe = $this->request->query('ii');
$show_arr = $this->request->query('arr');
$this->set('show_arr',$show_arr);
//$this->set('ihe',$ihe);


if(isset($this->request->data['sub']))
{
//$ih_head = explode(',',$ihe);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$income_head_arr = $collection['society']['income_head'];
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id1 = $collection['flat_type']['auto_id'];
$rate_arr = array();
for($r=0; $r<sizeof($income_head_arr); $r++)
{
$auto_id2 = (int)$income_head_arr[$r];

$charge_type_id = (int)$this->request->data['tp'.$auto_id1.$auto_id2];
$charge = (int)$this->request->data['chg'.$auto_id1.$auto_id2];

$arr = array($auto_id2,$charge_type_id,$charge);
$rate_arr[] = $arr;
}

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('charge' => $rate_arr),array('auto_id' => $auto_id1));
}
$this->response->header('Location','master_rate_card_view');
}

$this->loadmodel('society');
$conditions=array("society_id"=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('s_name',$society_name);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$result2);


$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor3 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor3',$cursor3);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor4 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor4',$cursor4);



}

/////////////////////// End Rate Card View2 (Accounts)/////////////////////////////

/////////////////////////// Start Nov View2 ///////////////////////////////////////////////////////////
function noc_view2()
{
$this->layout="session";
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$show_arr2 = $this->request->query('arr');
$this->set("show_arr2",$show_arr2);

if(isset($this->request->data['sub']))
{
$this->loadmodel('flat_type');
$order=array('flat_type.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$condition,'order' => $order)); 
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['flat_type']['auto_id'];
$type_id = (int)$this->request->data['tp'.$auto_id1];
if($type_id == 4)
{
$arr = array($type_id);
}
else
{
$amt = $this->request->data['amt'.$auto_id1];
$arr = array($type_id,$amt);
}

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('noc_charge' => $arr),array('auto_id' => $auto_id1));
}
$this->response->header('Location', 'master_noc');
}

$this->loadmodel('flat_type');
$order=array('flat_type.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$condition,'order' => $order)); 
$this->set('cursor1',$cursor1);
}
/////////////////////////// End Nov View2 ///////////////////////////////////////////////////////////

//////////////////////////////Start Supplimentry Bill show/////////////////////////////////////////
function supplimentry_view($auto_id=null)
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$auto_id = (int)$auto_id;

$this->loadmodel('adhoc_bill');
$conditions=array("receipt_id"=>$auto_id,"society_id" => $s_society_id);
$cursor=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['adhoc_bill']['bill_html'];	
}

$this->set('bill_html',$bill_html);
}
//////////////////////////////End Supplimentry Bill show/////////////////////////////////////////

//////////////////////////////// Start Noc Json ////////////////////////////////////////////////////////////////////
function noc_json()
{
$this->layout='blank';
$q=$this->request->query('q');
$q = html_entity_decode($q);
$typ = $this->request->query('b');
$typ2 = json_decode($typ, true);

$s_society_id = (int)$this->Session->read('society_id');
$s_user_id  = (int)$this->Session->read('user_id');

$myArray = json_decode($q, true);

if($typ2 == 1)
{
foreach($myArray as $child)
{

if($child[0]!=5){
	if(empty($child[0])){
	$output = json_encode(array('type'=>'error', 'text' => 'Please Fill All Fields'));
	die($output);
	}
}	
$child1=(int)$child[1];
if($child[0] != 4)
{
	if($child[0]!=5){
		if(empty($child1))
		{
		$output = json_encode(array('type'=>'error', 'text' => 'Please Fill All Fields'));
		die($output);
		}
	}

if(is_numeric($child1))
{
}	
else
{
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Numeric value'));
die($output);
}
}

}

$output = json_encode(array('type'=>'succ', 'text' => 'Are You Sure'));
die($output);

}
if($typ2 == 2)
{
foreach($myArray as $child)
{
$ch_type = (int)$child[0];
if($ch_type != 4)
{
$amt = $child[1];
$fltp = (int)$child[2];
$arr = array($ch_type,$amt);
}
else
{
$fltp = (int)$child[1];
$arr = array($ch_type);
}


$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('noc_charge' => $arr),array('auto_id' => $fltp));
}
$output = json_encode(array('type'=>'okk', 'text' => 'Are You Sure'));
die($output);

}
}
//////////////////////////////// End Noc Json ////////////////////////////////////////////////////////////////////

////////////////////////////////////// Start Rate Card Json //////////////////////////////////////////////////////////
function rate_card_json()
{
$this->layout='blank';
$q=$this->request->query('q');
$q = html_entity_decode($q);
$typ = $this->request->query('b');
$typ2 = json_decode($typ, true);

$s_society_id = (int)$this->Session->read('society_id');
$s_user_id  = (int)$this->Session->read('user_id');

$myArray = json_decode($q, true);

if($typ2 == 1)
{
foreach($myArray as $child)
{

if(empty($child[0])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill All Fields'));
die($output);
}	

if(empty($child[1])){
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill All Fields'));
die($output);
}

if(is_numeric($child[1]))
{
}	
else
{
$output = json_encode(array('type'=>'error', 'text' => 'Please Fill Numeric value'));
die($output);
}
}
$output = json_encode(array('type'=>'succ', 'text' => 'Are You Sure'));
die($output);
}
if($typ2 == 2)
{
$c=0;
foreach($myArray as $child)
{
$c++;
$type = (int)$child[0];
$amt = $child[1];
$flat_type_id = (int)$child[2];
$income_head = (int)$child[3];
$mm = (int)$child[4];
$arr = array($income_head,$type,$amt);
$arrr[] = $arr;
if($c == $mm)
{
$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('charge' => $arrr),array('auto_id' => $flat_type_id));
$arrr = array();
$c=0;
}
}
$output = json_encode(array('type'=>'okk', 'text' => 'Are You Sure'));
die($output);
}
}
////////////////////////////////////// End Rate Card Json //////////////////////////////////////////////////////////

/////////////////////////////////////// Start Select Income Head Json //////////////////////////////////////////////////
function select_income_head_json()
{
$this->layout=null;

$post_data=$this->request->data;
$this->ath();
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$arrr = $post_data['head'];
$type = (int)$post_data['type'];	
$ar = explode(",",$arrr);



if($type == 1)
{
$report = array();
if($arrr == 'null')
{
$report[]=array('label'=>'head', 'text' => 'Please select Income Heads');
}	

if(sizeof($report)>0)
{
$output=json_encode(array('report_type'=>'error','report'=>$report));
die($output);
}

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{

$arrr1 = $collection['society']['income_head'];
}
for($j=0; $j<sizeof($ar); $j++)
{
$head_id = (int)$ar[$j];
$arrr1[] = $head_id;
}

$this->loadmodel('society');
$this->society->updateAll(array('income_head'=> $arrr1),array('society_id'=>$s_society_id));

$output=json_encode(array('report_type'=>'publish','report'=>'Income Head Inserted Successfully'));
die($output);
}


}
/////////////////////////////////////// Start Select Income Head Json //////////////////////////////////////////////////
/////////////////////////////////// Start delete_select_income ////////////////////////////////////////////////////////
function delete_select_income()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$inid = (int)$this->request->query('con');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$arr = $collection['society']['income_head'];
}
for($k=0; $k<sizeof($arr); $k++)
{
$incid = (int)$arr[$k];
if($incid != $inid)
{
$arrr[] = $incid;
}
}
$this->loadmodel('society');
$this->society->updateAll(array('income_head'=> @$arrr),array('society_id'=>$s_society_id));

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection2)
{
$auto_id = (int)$collection2['flat_type']['auto_id'];
$charge3 = array();
$charge = @$collection2['flat_type']['charge'];
foreach($charge as $charge2)
{
$in1 = $charge2[0];
if($in1 != $inid)
{
$charge3[] = $charge2;
}
}
$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('charge'=> @$charge3),array('society_id'=>$s_society_id,"auto_id"=>$auto_id));
}


$this->redirect(array('controller' => 'Incometrackers','action' => 'select_income_heads'));
}
/////////////////////////////////// End delete_select_income ////////////////////////////////////////////////////////
//////////////////////// Start Account Statement (Accounts)//////////////////////////////
function account_statement()
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

//////////////// Start ac statement Bill View////////////////////////////////////////
/////////// Done////////////////////////////
function ac_statement_bill_view($receipt_id=null)
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->seen_notification(10,$receipt_id);

//$receipt_id = (int)$this->request->query('bill');
$receipt_id = (int)$receipt_id; 
$this->loadmodel('regular_bill');
$conditions=array("receipt_id"=>$receipt_id,"society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['regular_bill']['bill_html'];	
}
$this->set('bill_html',@$bill_html);

}
//////////////// End ac statement Bill View////////////////////////////////////////

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
//////////Done/////////////
function account_statement_excel()
{
$this->layout="";
$filename="Account Statement";
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
//$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
//$m_to = new MongoDate(strtotime($m_to));

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
$date = $collection['regular_bill']['date'];
$user_id = (int)$collection['regular_bill']['bill_for_user'];
//$bill_no = (int)$collection[''][''];
//$bill_no = (int)$collection[''][''];
$date_from1 = date('d-M-Y',strtotime($date_from));
$date_to1 = date('d-M-Y',strtotime($date_to));
$due_date = date('d-M-Y',strtotime($last_date)); 

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

if($m_from <= $date && $m_to >= $date)
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
/////////////////////// End Account Statement Excel/////////////////////////////////////////////////////////////

///////////////////////// Start Delete Terms ///////////////////////////////////////////////////////////////////
function delete_terms()
{
$this->layout='blank';
$s_society_id = (int)$this->Session->read('society_id');

$delete = (int)$this->request->query('delete');
$t_id = (int)$this->request->query('t_id');
$this->set('delete',$delete);
if($delete == 0)
{
$this->set('t_id',$t_id);
}
if($delete == 1)
{
$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$terms_arr = @$collection['society']['terms_conditions'];
}
$k=0;
$terms_arr2 = array();
for($h=0; $h<sizeof($terms_arr); $h++)
{
$k++;
$terms_name = $terms_arr[$h];
if($k != $t_id)
{
$terms_arr2[] = $terms_name;
} 
}
$this->loadmodel('society');
$this->society->updateAll(array('terms_conditions'=>$terms_arr2),array("society_id" => $s_society_id));
}
}
//////////////////////////// End Delete Terms ///////////////////////////////////////////////////////////////

//////////////////////// Start Edit Terms ////////////////////////////////////////////////////
function edit_terms()
{
$this->layout='blank';
$s_society_id = (int)$this->Session->read('society_id');
$t_id = (int)$this->request->query('t_id');
$edit = (int)$this->request->query('edit');
$this->set('edit',$edit);

if($edit == 0)
{
$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
$this->set('t_id',$t_id);
}
if($edit == 1)
{
$tems_name = $this->request->query('tem');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$terms_arr = $collection['society']['terms_conditions'];
}
$hh = $t_id-1;
$terms_arr[$hh] = $tems_name;

$this->loadmodel('society');
$this->society->updateAll(array('terms_conditions'=>$terms_arr),array("society_id" => $s_society_id));
}
}
//////////////////////// End Edit Terms ////////////////////////////////////////////////////////////////

//////////////////////////////////// Start Approve Bill /////////////////////////////////////////////////////////////////
function aprrove_bill()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$s_society_id = (int)$this->Session->read('society_id');

$this->ath();
$this->check_user_privilages();

$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"approve_status"=>1);
$order=array('regular_bill.receipt_id'=> 'ASC');
$cursor1 = $this->regular_bill->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor1',$cursor1);


if(isset($this->request->data['sub']))
{
$all = @$this->request->data['all'];
$r=0;
$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"approve_status"=>1);
$order=array('regular_bill.receipt_id'=> 'ASC');
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor as $collection)
{
$r++;
echo $app = (int)@$this->request->data['app'.$r];
if($app != 0)
{
$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"approve_status"=>1,"receipt_id"=>$app);
$cursor5 = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor5 as $collection)
{
$user_id = (int)$collection['regular_bill']['bill_for_user'];
$html = $collection['regular_bill']['bill_html'];
$from = $collection['regular_bill']['bill_daterange_from'];
$to = $collection['regular_bill']['bill_daterange_to'];
$due_date = $collection['regular_bill']['due_date'];
$grand_total = $collection['regular_bill']['g_total'];
$receipt_id = $collection['regular_bill']['receipt_id'];

}
$sms_from = date('d-M',strtotime($from));
$sms_to = date('dMy',strtotime($to));
$sms_due = date('dMy',strtotime($due_date));

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
$mobile = $collection['user']['mobile'];
$email = $collection['user']['email'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));	
$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$sms_id = (int)$collection['society']['account_sms'];
$email_id = (int)$collection['society']['account_email'];
$society_name = $collection['society']['society_name'];
}

$ussrs[]=$user_id;

$this->send_notification('<span class="label label-warning" ><i class="icon-money"></i></span>','New bill for your flat '.$wing_flat.' is generated ',10,$receipt_id,$this->webroot.'Incometrackers/ac_statement_bill_view/'.$receipt_id,0,$ussrs);
unset($ussrs);

if($sms_id == 1)
{
$r_sms=$this->hms_sms_ip();
$working_key=$r_sms->working_key;
$sms_sender=$r_sms->sms_sender; 
$sms='Dear '.$user_name.' '.$wing_flat.', your maintenance bill for period '.$sms_from.'-'.$sms_to.' is Rs '.$grand_total.'.Kindly pay by due '.$sms_due.'.'.$society_name.' Society';

$sms1=str_replace(' ', '+', $sms);
 ////sms-closed//// $payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey='.$working_key.'&sender='.$sms_sender.'&to='.$mobile.'&message='.$sms1.''); 
}
if($email_id == 1)
{
$from_mail_date = date('d M',strtotime($from));
$to_mail_date = date('d M Y',strtotime($to));

//$my_mail = "nikhileshvyas@yahoo.com";
$subject = ''.$society_name.' : Maintanance bill, '.$from_mail_date.' to '.$to_mail_date.'';
$from_name="HousingMatters";
//$message_web = "Receipt No. :".$d_receipt_id;
$from = "accounts@housingmatters.in";
$reply="accounts@housingmatters.in";
$this->send_email($email,$from,$from_name,$subject,$html,$reply);
}

$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("approve_status" => 2),array("receipt_id" => $app,"society_id"=>$s_society_id));
}
}
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Regular Bill Approve</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>This Bills Approved Suceessfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="aprrove_bill" class="btn blue">OK</a>
</div>
</div>
<?php
}
}
//////////////////////////////////// End Approve Bill /////////////////////////////////////////////////////////////////
////////////////////////////////////////// Start NEFT Add //////////////////////////////////////////////////////////////
function neft_add()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}

$this->ath();
$this->check_user_privilages();

$s_society_id=(int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


if(isset($this->request->data['sub']))
{
$ac_name = $this->request->data['acno'];
$bank_name = $this->request->data['bank_name'];
$branch = $this->request->data['branch'];
$ifsc_code = $this->request->data['ifsc'];
$ac_number = $this->request->data['acnu'];

$this->loadmodel('society');
$this->society->updateAll(array("ac_name" => $ac_name,"bank_name"=>$bank_name,"branch"=>$branch,"ifsc_code"=>$ifsc_code,"ac_num"=>$ac_number),array("society_id" => $s_society_id));
?>
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Income Tracker</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b>Record Inserted Successfully</b></h5>
</center>
</div>
<div class="modal-footer">
<a href="neft_add" class="btn blue">OK</a>
</div>
</div>
<?php
}
}
////////////////////////////////////////// End NEFT Add //////////////////////////////////////////////////////////////



}
?>