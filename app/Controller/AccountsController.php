<?php
App::import('Controller','Hms');
class AccountsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);
var $name = 'Accounts';
//////////////////////// Start Balance Shit //////////////////////////////
function balance_sheet()
{
				if($this->RequestHandler->isAjax()){
				$this->layout='blank';
				}else{
				$this->layout='session';
				}	

			$this->loadmodel('accounts_group');	
			$result_accounts_group=$this->accounts_group->find('all');	
			$this->set('result_accounts_group',$result_accounts_group);
}
////////////////////// End Balance Shit //////////////////////////////////////

////////////////// Start Master Ledger Sub Account Ajax ///////////////////////
function master_ledger_sub_account_ajax()
{
		$this->layout='blank';
		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=$this->Session->read('user_id');	

			$value = (int)$this->request->query('value');
			$this->set('value',$value);
}
/////////////////////////////End Master Ledger Sub Account Ajax///////////////////////////////

//////////////////////////// Start Opening Balance Import (Accounts)//////////////////////////
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
}
//////////////////// End Opening Balance Import (Accounts)/////////////////////////////////////

/////////////////////////////////// Start Master Period Status (Accounts)//////////////////////
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
///////////////////////////// End Master Period Status (Accounts)//////////////////////////////

/////////////////// Start master Financial Year (Accounts)//////////////////////////////////////
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
			<a href="master_financial_period_status" class="btn blue">OK</a>
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
////////////////// End Master Financial Year(Accounts)////////////////////////////////////////

/////////////////////Start Financial Vali Ajax(Accounts)//////////////////////////////////////
function financial_vali_ajax()
{
	$this->layout='blank';
	$s_role_id=$this->Session->read('role_id');
	$s_society_id = (int)$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');	

	$cc = (int)$this->request->query('ss');
	$this->set('cc',$cc);
}
/////////////////////End Financial Vali Ajax(Accounts)/////////////////////////////////////////

////////////////////////Start Master Ledger Accounts COA(Accounts)/////////////////////////////
function master_ledger_account_coa()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}

	$this->ath();
	$this->check_user_privilages();

		$ledger = array();
		$y=0;
		$this->loadmodel('ledger_account');
		$order=array('ledger_account.auto_id'=> 'ASC');
		$cursor=$this->ledger_account->find('all',array('order' =>$order));
		foreach ($cursor as $collection) 
		{
		$y++;
		$ledger_name = $collection['ledger_account']['ledger_name'];
		$ledger[] = $ledger_name;
		}
		
		$ledger2 = implode(",",$ledger);
		$this->set('ledger2',$ledger2);
		$this->set('y',$y);

		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=$this->Session->read('user_id');	
		$this->set('s_user_id',$s_user_id);

	$this->loadmodel('ledger_account');
	$conditions =array( '$or' => array(array('society_id' =>$s_society_id),array("society_id" => 0)));
	$cursor=$this->ledger_account->find('all',array('conditions'=>$conditions));
	foreach($cursor as $collection) 
	{
		$auto_id = (int)$collection['ledger_account']['auto_id']; 
			if(isset($this->request->data['sub'.$auto_id]))
			{
			$group_id = (int)$this->request->data['gr_id'];
			$ledger_name = $this->request->data['cat'];

			$this->loadmodel('ledger_account');
			$this->ledger_account->updateAll(array("ledger_name" => $ledger_name,"group_id"=>$group_id),array("auto_id" => $auto_id));	
			}
			
			if(isset($this->request->data['sub2'.$auto_id]))
			{
			$this->loadmodel('ledger_account');
			$this->ledger_account->updateAll(array("delete_id" => 1),array("auto_id" => $auto_id));	
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
			$multipleRowData = Array( Array("auto_id" => $i, "group_id" => $main_id, "ledger_name" => $name, "society_id"=> $s_society_id,"edit_user_id"=>$s_user_id,"delete_id" => 0));
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
			$conditions =array( '$or' => array(array("society_id"=>$s_society_id),array("society_id"=>0)));
			$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
			$this->set('cursor2',$cursor2);	

			$this->loadmodel('accounts_group');
			$conditions=array("delete_id" => 0);
			$cursor3=$this->accounts_group->find('all',array('conditions'=>$conditions));
			$this->set('cursor3',$cursor3);
}
///////////////////////////End Master Ledger Accounts COA (Accounts)///////////////////////////

///////////////////////// Start Master Ledger Accounts Ajax COA (Accounts)///////////////////////
function master_ledger_account_ajax()
{
		$this->layout='blank';
		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=$this->Session->read('user_id');	

		$value = $this->request->query('value');
		$this->set('value',$value);
}
/////////////////////// End Master Ledger Accounts Ajax COA (Accounts)////////////////////////////

//////////////////// Start Master Ledger Sub Accounts COA (Accounts) /////////////////////////////
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

		$ledger = array();
		$t=0;
		$this->loadmodel('ledger_sub_account');
		$conditions=array("society_id" => $s_society_id);
		$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
		foreach($cursor as $collection)
		{
		$t++;
		$sub_ledger_name = $collection['ledger_sub_account']['name'];
		$ledger[] = $sub_ledger_name;
		}
		
			$ledger2 = implode(",",$ledger);
			$this->set('ledger2',$ledger2);
			$this->set('t',$t);

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
		$multipleRowData = Array( Array("auto_id" => $i, "ledger_id" => $main_id, "name" => $name, "society_id" => $s_society_id, "user_id" => $user_id,"delete_id"=>0,"deactive"=>0));
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
///////////////////////// End Master Ledger Sub Accounts COA (Accounts) //////////////////////////////

////////////////////////////////// Start Over Due Report (Accounts) ///////////////////////////////////
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
///////////////////////////////////// End Over Due Report (Accounts)///////////////////////////////////

/////////////////////// Start over due report show ajax(Accounts)/////////////////////////////////////
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
				$conditions=array("society_id"=> $s_society_id,"status"=>0,"approve_status" => 2);
				$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
				$this->set('cursor1',$cursor1);	
}
/////////////////////// End over due report show ajax(Accounts)/////////////////////////////

//////////////////////////// Start OverDue Excel////////////////////////////////////////////
function overdue_excel()
{
	$this->layout="";
	$filename="OverDue Report";
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
						$m_to = date("Y-m-d", strtotime($to));

						$excel="<table border='1'>
						<tr>
						<th colspan='8' style='text-align:center;'>
						Over Due Report  ($society_name Society)</th>
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
		$c = 0;
		$grand_total = 0;
		$total_due_amt = 0;
		$total_bill_amt = 0;
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
		$tax_amt = (int)$collection['regular_bill']['due_amount_tax'];	
		$due_amt = (int)$collection['regular_bill']['total_due_amount'];	
		$bill_amt = (int)$collection['regular_bill']['g_total'];	
		$bill_for_user = (int)$collection['regular_bill']['bill_for_user'];
		$date = $collection['regular_bill']['date'];

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
			if($date >= $m_from && $date <= $m_to)
			{
				if($due_amt > 0)
				{
					$fromd = date('d-M-Y',strtotime($date_from));	
					$tod = date('d-M-Y',strtotime($date_to));	
					$dued = date('d-M-Y',strtotime($due_date));	
					$c++;
					$grand_total = $grand_total + $total_amount;
					$total_due_amt = $total_due_amt + $due_amt;
					$total_bill_amt = $total_bill_amt + $bill_amt;
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
				}
			}
		}
	}
	else if($wise == 1)
	{
		if($wing == $wing_id)
		{
			if($date >= $m_from && $date <= $m_to)
			{
				if($due_amt > 0)
				{
					$fromd = date('d-M-Y',strtotime($date_from));	
					$tod = date('d-M-Y',strtotime($date_to));	
					$dued = date('d-M-Y',strtotime($due_date));	
					$c++;
					$grand_total = $grand_total + $total_amount;
					$total_due_amt = $total_due_amt + $due_amt;
					$total_bill_amt = $total_bill_amt + $bill_amt;
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
				}
			}				
		}
	}
}
		$excel.="
		<tr>
		<th colspan='5' style='text-align:right;'>Total</th>
		<th>$grand_total</th>
		<th>$total_due_amt</th>
		<th>$total_bill_amt</th>
		</tr>";
		$excel.="</table>";

		echo $excel;
}
//////////////////////////////////////////// End OverDue Excel///////////////////////////////////////////

/////////////////////////////// Start Account Statement (Accounts)////////////////////////////////////////
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
/////////////////////////// End Account Statement (Accounts)/////////////////////////////////////

////////////////////////// Start account statement show ajax(Accounts)///////////////////////////
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
/////////////////////////////////// End account statement show ajax(Accounts)////////////////////////////

//////////////////////////////// Start Account Statement Excel//////////////////////////////////////////
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
					$date = $collection['regular_bill']['date'];
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
/////////////////////// End Account Statement Excel/////////////////////////////////////////////////

/////////////////////////// Start ac statement Bill View/////////////////////////////////////////////
function ac_statement_bill_view($receipt_id=null)
{
	$this->layout='blank';
	$s_role_id=$this->Session->read('role_id');
	$s_society_id = (int)$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');

		$receipt_id = (int)$receipt_id; 
		$this->loadmodel('regular_bill');
		$conditions=array("receipt_id"=>$receipt_id,"society_id" => $s_society_id);
		$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
		foreach($cursor as $collection)
		{
		$bill_html = $collection['regular_bill']['bill_html'];	
		}
			$this->set('bill_html',$bill_html);
}
///////////////////////////////////// End ac statement Bill View////////////////////////////////////////

//////////////////////// Start My Flat Bill (Accounts) //////////////////////////////
function my_flat_bill()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
	$last_date=date('t'); 
	$current_month=date('m');
	$current_year=date('Y');
	$from=$current_year."-".$current_month."-01";
	$to=$current_year."-".$current_month."-".$last_date;
	$this->set("from",$from);
	$this->set("to",$to);
	$this->ath();
	$this->check_user_privilages();
	$s_society_id = (int)$this->Session->read('society_id');
	
	$s_user_id=$this->Session->read('user_id');
	$this->set("s_user_id",$s_user_id);
	
	$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($s_user_id)));
	foreach ($result_user_info as $collection2){
		$user_id=$collection2["user"]["user_id"];
		$user_name=$collection2["user"]["user_name"];
		$this->set('user_name',$user_name);
		$multiple_flat=@$collection2["user"]["multiple_flat"];
		$this->set('multiple_flat',$multiple_flat);
		$flat_id=$collection2["user"]["flat"];
	}
	
	$this->loadmodel('society');
	$conditions=array("society_id" => $s_society_id);
	$result_society=$this->society->find('all',array('conditions'=>$conditions));
	$this->set('result_society',$result_society);
	
	$this->loadmodel('ledger_sub_account');
	$conditions=array("society_id" => $s_society_id,"user_id" => (int)$user_id);
	$result_ledger_sub_account=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
	$ledger_sub_account_id=$result_ledger_sub_account[0]["ledger_sub_account"]["auto_id"];
	
	$this->loadmodel('ledger');
	$conditions=array("society_id" => $s_society_id,"ledger_account_id" => 34,"ledger_sub_account_id" => $ledger_sub_account_id,'transaction_date'=> array('$gte' => strtotime($from),'$lte' => strtotime($to)));
	$order=array('new_regular_bill.one_time_id'=>'ASC');
	$result_ledger=$this->ledger->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_ledger',$result_ledger);
	//pr($result_ledger);

}
////////////////////////////////// End My Flat Bill /////////////////////////////////////////////////////

/////////////////////////////////// Start my_flat_bill_ajax ////////////////////////////////////////////
function my_flat_bill_ajax($from=null,$to=null,$flat_id=null)
{
		if($this->RequestHandler->isAjax()){
		$this->layout='blank';
		}else{
		$this->layout='session';
		} 
			 $from=date("Y-m-d",strtotime($from));
			 $this->set("from",$from);
			 $to=date("Y-m-d",strtotime($to));
			 $this->set("to",$to);
			 
			 $this->set("flat_id",$flat_id);
		
		$this->ath();
		$s_society_id = (int)$this->Session->read('society_id');
	
		$s_user_id=$this->Session->read('user_id');
		$this->set("s_user_id",$s_user_id);
	
	$flat_id=(int)$flat_id; 
	if($flat_id==0)
	{
		$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($s_user_id)));
		foreach($result_user_info as $collection2)
		{
		$user_name=$collection2["user"]["user_name"];
		$this->set('user_name',$user_name);
		$wing_id=$collection2["user"]["wing"];
		$flat_id=$collection2["user"]["flat"];
		}

		$wing_flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));
		$this->set('wing_flat',$wing_flat);
	}else{
		$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array((int)$flat_id)));
		foreach($result_flat_info as $flat_info){
		$wing_id=$flat_info["flat"]["wing_id"];
		} 
		
	$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,(int)$flat_id)));
	$this->set('wing_flat',$wing_flat);
		
		//user info via flat_id//
		$result_user_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($wing_id,$flat_id)));
		foreach($result_user_info as $user_info){
			$user_id=(int)$user_info["user"]["user_id"];
			$user_name=$user_info["user"]["user_name"];
			$this->set('user_name',$user_name);
		} 
	}
	
	$this->loadmodel('society');
	$conditions=array("society_id" => $s_society_id);
	$result_society=$this->society->find('all',array('conditions'=>$conditions));
	$this->set('result_society',$result_society);
	
	$this->loadmodel('ledger_sub_account');
	$conditions=array("society_id" => $s_society_id,"flat_id" => (int)$flat_id);
	$result_ledger_sub_account=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
	$ledger_sub_account_id=$result_ledger_sub_account[0]["ledger_sub_account"]["auto_id"];
	
	$this->loadmodel('ledger');
	$conditions=array("society_id" => $s_society_id,"ledger_account_id" => 34,"ledger_sub_account_id" => $ledger_sub_account_id,'transaction_date'=> array('$gte' => strtotime($from),'$lte' => strtotime($to)));
	$order=array('ledger.transaction_date'=>'ASC');
	$result_ledger=$this->ledger->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_ledger',$result_ledger);
}

function my_flat_bill_excel_export($from=null,$to=null,$flat_id=null)
{
		
		$this->layout=null;
		
		$filename="Regular_Bill";
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );
	
			 $from=date("Y-m-d",strtotime($from));
			 $this->set("from",$from);
			 $to=date("Y-m-d",strtotime($to));
			 $this->set("to",$to);
		
		$this->ath();
		$s_society_id = (int)$this->Session->read('society_id');
	
		$s_user_id=$this->Session->read('user_id');
		$this->set("s_user_id",$s_user_id);
	
	$flat_id=(int)$flat_id; 
	if($flat_id==0)
	{
		$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($s_user_id)));
		foreach($result_user_info as $collection2)
		{
		$user_name=$collection2["user"]["user_name"];
		$this->set('user_name',$user_name);
		$wing_id=$collection2["user"]["wing"];
		$flat_id=$collection2["user"]["flat"];
		}

		$wing_flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));
		$this->set('wing_flat',$wing_flat);
	}else{
		$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array((int)$flat_id)));
		foreach($result_flat_info as $flat_info){
		$wing_id=$flat_info["flat"]["wing_id"];
		} 
		
	$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,(int)$flat_id)));
	$this->set('wing_flat',$wing_flat);
		
		//user info via flat_id//
		$result_user_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($wing_id,$flat_id)));
		foreach($result_user_info as $user_info){
			$user_id=(int)$user_info["user"]["user_id"];
			$user_name=$user_info["user"]["user_name"];
			$this->set('user_name',$user_name);
		} 
	}
	
	$this->loadmodel('society');
	$conditions=array("society_id" => $s_society_id);
	$result_society=$this->society->find('all',array('conditions'=>$conditions));
	$this->set('result_society',$result_society);
	
	$this->loadmodel('ledger_sub_account');
	$conditions=array("society_id" => $s_society_id,"flat_id" => (int)$flat_id);
	$result_ledger_sub_account=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
	$ledger_sub_account_id=$result_ledger_sub_account[0]["ledger_sub_account"]["auto_id"];
	
	$this->loadmodel('ledger');
	$conditions=array("society_id" => $s_society_id,"ledger_account_id" => 34,"ledger_sub_account_id" => $ledger_sub_account_id,'transaction_date'=> array('$gte' => strtotime($from),'$lte' => strtotime($to)));
	$order=array('ledger.transaction_date'=>'ASC');
	$result_ledger=$this->ledger->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_ledger',$result_ledger);
}
/////////////////////////// End my_flat_bill_ajax (Accounts) /////////////////////////////////////

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
/////////////////////////////////// End Bank Receipt Pdf (Accounts)////////////////////////////////////

/////////////////////////////// Start my flat Bill Excel ////////////////////////////////////////////
function my_flat_bill_excel()
{
	$this->layout="";
	$filename="My Flat";
	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );

		$s_role_id=(int)$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=(int)$this->Session->read('user_id');	

			$this->loadmodel('ledger_sub_account');
			$conditions=array("user_id"=>$s_user_id,"society_id"=>$s_society_id);
			$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
			foreach($cursor as $collection)
			{
			$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
			$user_name = $collection['ledger_sub_account']['name'];
			}

				$this->loadmodel('society');
				$conditions=array("society_id"=>$s_society_id);
				$cursor = $this->society->find('all',array('conditions'=>$conditions));
				foreach($cursor as $collection)
				{
				$society_name = $collection['society']['society_name'];
				}

				$from = $this->request->query('f');
				$to = $this->request->query('t');

		$m_from = date("Y-m-d", strtotime($from));
		$m_to = date("Y-m-d", strtotime($to));

			$excel="<table border='1'>
			<tr>
			<th colspan='9' style='text-align:center;'>
			<p style='font-size:16px;'>
			Bill Detail($society_name)
			</p>
			</th>
			</tr>
			<tr>
			<th style='text-align:center;'>Bill No.</th>
			<th colspan='2'>Bill Date</th>
			<th style='text-align:center;' colspan='2'>Bill Period</th>
			<th style='text-align:center;'>Due Date</th>
			<th style='text-align:center;'>Total Amount</th>
			<th style='text-align:center;'>Paid Amount</th>
			<th style='text-align:center;'>Due Amount</th>
			</tr>";
	$nn=0;
	$gt_amt = 0;
	$gt_pay_amt = 0;
	$due_amt = 0;
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
			$date = $collection['regular_bill']['date'];
			$fromm = date('d-M-Y',strtotime($from2));
			$tom = date('d-M-Y',strtotime($to2));
			$due = date('d-M-Y',strtotime($due_date));
			$pay_amt = $total_amount - $remaining_amt; 
			
				if($m_from <= $date && $m_to >= $date)
				{
					$nn++;
					$gt_amt = $gt_amt + $total_amount;
					$gt_pay_amt = $gt_pay_amt + $pay_amt;
					$due_amt = $due_amt + $remaining_amt;
					$date1 = date('d-m-Y',strtotime($date));

						$excel.="<tr>
						<td style='text-align:center;'>$bill_no</td>
						<td colspan='2'>$date1</td>
						<td style='text-align:center;' colspan='2'>$fromm - $tom</td>
						<td style='text-align:center;'>$due</td>
						<td style='text-align:center;'>$total_amount</td>
						<td style='text-align:center;'>$pay_amt</td>
						<td style='text-align:center;'>$remaining_amt</td>
						</tr>";
				}
			}
				$excel.="<tr>
				<th colspan='6' style='text-align:right;'>Grand Total</th>
				<th style='text-align:center;'>$gt_amt</th>
				<th style='text-align:center;'>$gt_pay_amt</th>
				<th style='text-align:center;'>$due_amt</th>
				</tr>
				<tr>
				<th style='text-align:center;' colspan='9'>
				<p style='font-size:16px;'>Bank Receipt Detail($society_name)</p>
				</th>
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
	$this->loadmodel('cash_bank');
	$conditions=array("user_id"=>@$auto_id,"society_id"=>$s_society_id,"module_id"=>1);
	$cursor4 = $this->cash_bank->find('all',array('conditions'=>$conditions));
	foreach ($cursor4 as $collection) 
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
			$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($received_from_id)));	
			foreach($result1 as $collection)
			{	
			$user_id = (int)$collection['ledger_sub_account']['user_id'];
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
				$tr_date = date('d-M-Y',strtotime($date));
				$total_debit = $total_debit + $amount;		
				$excel.="<tr>
				<td>$receipt_no</td>
				<td>$tr_date</td>
				<td>$user_name &nbsp&nbsp&nbsp&nbsp $wing_flat</td> 
				<td>$ref</td>
				<td>$receipt_mode</td>
				<td>$receipt_instruction</td>
				<td>$account_no</td>
				<td>$narration</td>
				<td>$amount</td>
				</tr>";					
				}
			}
				$excel.="<tr>
				<th colspan='8' style='text-align:right;'>Grand Total</th>
				<th>$total_debit</th>
				</tr>
				<tr>
				<th colspan='9' style='text-align:center;'>
				<p style='font-size:16px;'>Petty Cash Receipt Detail($society_name)</p></th>
				</tr>
				<tr>
				<th colspan='2'>PC Receipt #</th>
				<th colspan='2'>Transaction Date</th>
				<th colspan='2'>Narration</th>
				<th colspan='2'>Received From</th>
				<th>Amount</th>
				</tr>";
	$n=1;
	$total_credit = 0;
	$total_debit = 0;
		$this->loadmodel('cash_bank');
		$conditions=array("society_id" => $s_society_id,"module_id"=>3);
		$cursor11=$this->cash_bank->find('all',array('conditions'=>$conditions));
		foreach($cursor11 as $collection)
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
		$user_id = (int)$collection['ledger_sub_account']['user_id'];
		}
		$resultttt = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
			foreach ($resultttt as $collection) 
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

	if($account_type == 1)
	{
		if($date >= $m_from && $date <= $m_to)
		{
			if($s_user_id == $user_id)  
			{
				$date = date('d-m-Y',strtotime($date));
				$total_debit = $total_debit + $amount;
				$amount = number_format($amount);

					$excel.="<tr>
					<td colspan='2'>$receipt_no</td>
					<td colspan='2'>$date</td>
					<td colspan='2'>$narration</td>
					<td colspan='2'>$user_name &nbsp&nbsp&nbsp&nbsp $wing_flat</td>
					<td>$amount</td>
					</tr>";
			}
		}
	}
}
	$total_debit = number_format($total_debit);
		$excel.="<tr>
		<th colspan='8' style='text-align:right;'>Grand Total</th>
		<th>$total_debit</th>
		</tr></table>";

	echo $excel;
}
//////////////////////// End my flat Bill Excel///////////////////////////////////////

///////////////////////Start my flat receipt(Accounts)/////////////////////////////////
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
///////////////////////End my flat receipt(Accounts)/////////////////////////////////////

////////////////// Start My Flat receipt Excel//////////////////////////////////////////
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
		$this->loadmodel('cash_bank');
		$conditions=array("user_id"=>$auto_id,"society_id"=>$s_society_id,"module_id"=>1);
		$cursor1 = $this->cash_bank->find('all',array('conditions'=>$conditions));
		foreach ($cursor1 as $collection) 
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

	$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($received_from_id)));	
	foreach($result1 as $collection)
	{	
	$user_id = (int)$collection['ledger_sub_account']['user_id'];
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

			$this->loadmodel('cash_bank');
			$conditions=array("user_id"=>$auto_id,"society_id"=>$s_society_id,"module_id"=>1);
			$cursor1 = $this->cash_bank->find('all',array('conditions'=>$conditions));
			$this->set('cursor1',$cursor1);
}
//////////////////////End my flat receipt show (Accounts)/////////////////////////////////////

//////////////////// Start Trial Balance Excel/////////////////////////////////////////////////
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
						$m_to = date("Y-m-d", strtotime($to));

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
		$op_date = "";
		$amount1 = $collection['ledger']['amount'];
		$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
		$receipt_id = $collection['ledger']['receipt_id'];
			if($receipt_id == 'O_B')
			{
			$op_date = @$collection['ledger']['op_date'];
			}
				$table_name = $collection['ledger']['table_name'];
					if($table_name == "cash_bank")
					{
					$module_id = (int)$collection['ledger']['module_id'];
					}

	if($receipt_id != 'O_B')
		{
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
				if($receipt_id == 'O_B')
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
			$op_date = @$collection['ledger']['op_date']; 
			$table_name = $collection['ledger']['table_name']; 
			if($table_name == "cash_bank")
			{ 
			$module_id = (int)$collection['ledger']['module_id']; 
			}
				if($receipt_id != 'O_B')
				{
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
		$receipt_id = (int)$collection['ledger']['receipt_id'];
		$op_date = @$collection['ledger']['op_date'];
		$table_name = $collection['ledger']['table_name']; 
			if($table_name == "cash_bank")
			{
			$module_id = (int)$collection['ledger']['module_id'];
			} 
 
	if($receipt_id != 'O_B')
	{
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
</tr>";

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
	$receipt_id = (int)$collection['ledger']['receipt_id'];
	$op_date = @$collection['ledger']['op_date'];
	$table_name = $collection['ledger']['table_name'];
		if($table_name == "cash_bank")
		{
		$module_id = (int)$collection['ledger']['module_id'];
		}
			if($receipt_id != 'O_B')
			{
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
						<table border='1'>";

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
	$receipt_id_s = (int)$collection['ledger']['receipt_id'];
	$op_date2 = @$collection['ledger']['op_date'];
	$table_name = $collection['ledger']['table_name'];
		if($table_name == "cash_bank")
		{
		$module_id = (int)$collection['ledger']['module_id'];
		}
	if($receipt_id_s != 'O_B')
	{	
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
	$receipt_id2 = (int)$collection['ledger']['receipt_id'];
	$op_date3 = @$collection['ledger']['op_date'];
	$table_name = $collection['ledger']['table_name'];
		if($table_name == "cash_bank")
		{
		$module_id = (int)$collection['ledger']['module_id'];
		}
	if($receipt_id2 != 'O_B')
	{
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
			}
echo $excel;
}
//////////////////////////// End Trial Balance Excel/////////////////////////////////////////////

/////////////////////////////// Start Trial Balance (Accounts) //////////////////////////////////
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
////////////////////////// End Trial Balance (Accounts) /////////////////////////////////////////////////

//////////////////////////////// Start Trial Balance Ajax Show (Accounts) //////////////////////////////
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

			$this->loadmodel('ledger');
			$conditions=array("society_id"=>$s_society_id);
			$cursor6 = $this->ledger->find('all',array('conditions'=>$conditions));
			$this->set('cursor6',$cursor6);
}
////////////////////// End Trial Balance Ajax Show (Accounts) //////////////////////////////////////////

///////////////////// Start Regular Bill View (Accounts)//////////////////////////////////////////////////////////////
	function regular_bill_view($auto_id=null)
	{
		$this->layout='session';
		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=$this->Session->read('user_id');

			$auto_id = (int)$auto_id;

				$this->loadmodel('regular_bill');
				$conditions=array("receipt_id"=>$auto_id,"society_id" => $s_society_id);
				$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
				foreach($cursor as $collection)
				{
				$bill_html = $collection['regular_bill']['bill_html'];	
				}
		$this->set('bill_html',@$bill_html);
	}
////////////////////////////////// End Regular Bill View (Accounts)//////////////////////////////////////////

///////////////////////////////////// Start Master Ledger Sub Account View/////////////////////////////////////////////
	function master_ledger_sub_account_view()
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

		$this->loadmodel('ledger_sub_account');
		$conditions=array("society_id" => $s_society_id);
		$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
		$this->set('cursor2',$cursor2);	
}
///////////////////////////////////// End Master Ledger Sub Account View/////////////////////////////////////////////

//////////////////////// Start Master Ledger Accounts View ////////////////////////////////////////////////////////////
function master_ledger_accounts_view()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}

		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id=$this->Session->read('user_id');	
		$this->set('s_user_id',$s_user_id);

	$this->ath();
	$this->check_user_privilages();

	$this->loadmodel('ledger_account');
	$conditions = array( '$or' => array(array('society_id' =>$s_society_id),array('society_id' =>0)));
	$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
	$this->set('cursor2',$cursor2);	

		$this->loadmodel('accounts_group');
		$conditions=array("delete_id" => 0);
		$cursor3=$this->accounts_group->find('all',array('conditions'=>$conditions));
		$this->set('cursor3',$cursor3);
}
/////////////////////// End Master Ledger Accounts View ////////////////////////////////////////////////

////////////////////////////// Start ledger Edit //////////////////////////////////////////////////////
function ledger_edit()
{
$this->layout='blank';

	$s_society_id = (int)$this->Session->read('society_id');
	$auto_id = (int)$this->request->query('t_id');
	$edit = (int)$this->request->query('edit');
	$this->set('edit',$edit);
	
		if($edit == 0)
		{
			$this->set('ledger_id',$auto_id);

				$this->loadmodel('ledger_account');
				$conditions=array('$or' => array( 
				array("society_id" => 0, "auto_id" => $auto_id),
				array("society_id" => $s_society_id, "auto_id" => $auto_id),));
				$cursor1=$this->ledger_account->find('all', array('conditions' => $conditions));
				$this->set('cursor1',$cursor1);

			$this->loadmodel('accounts_group');
			$cursor2=$this->accounts_group->find('all');
			$this->set('cursor2',$cursor2);
}
	if($edit == 1)
	{
		$ledger_name = $this->request->query('led');
		$group_id = (int)$this->request->query('g');

		$this->loadmodel('ledger_account');
		$this->ledger_account->updateAll(array('group_id'=>$group_id,'ledger_name'=>$ledger_name),array('auto_id'=>$auto_id));
	}
}
////////////////////////////// End ledger Edit ///////////////////////////////////////////////////////////////////

/////////////////////////////// Start SubLedgerEdit ////////////////////////////////////////////////////
function subledger_edit()
{
	$this->layout='blank';
	$s_society_id = (int)$this->Session->read('society_id');
}
/////////////////////////////// End SubLedgerEdit ////////////////////////////////////////////////////

///////////////////////////////////// Start Opening Balance Import Ajax //////////////////////////////////////////
function opening_balance_import_ajax()
{
	$this->layout="blank";
	$this->ath();

	$s_society_id= (int)$this->Session->read('society_id');

if(isset($_FILES['file'])){
$file_name=$_FILES['file']['name'];
$file_tmp_name =$_FILES['file']['tmp_name'];
$target = "csv_file/unit/";
$target=@$target.basename($file_name);
move_uploaded_file($file_tmp_name,@$target);

$f = fopen('csv_file/unit/'.$file_name, 'r') or die("ERROR OPENING DATA");
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
		
		$flat_id = "";
		$wing_id = "";
		$group_name = $child_ex[0];
		$account_name = $child_ex[1];
		$wingg_nammm = $child_ex[2];
		$flatt_nammm = $child_ex[3];
		$debit_or_credit = $child_ex[4];
		$priciple_amount = $child_ex[5];
		$penalty_amount = $child_ex[6];
		$wing_flat = "";
		$group_id = "";


			$this->loadmodel('ledger_account'); 
			$conditions=array("ledger_name"=> new MongoRegex('/^' . $group_name . '$/i'));
			$group_detail=$this->ledger_account->find('all',array('conditions'=>$conditions));
			foreach($group_detail as $group_data)
			{
			$group_id = (int)$group_data['ledger_account']['auto_id'];
			}

			$this->loadmodel('accounts_group'); 
			$conditions=array("group_name"=> new MongoRegex('/^' .  $group_name . '$/i'));
			$group_detail2=$this->accounts_group->find('all',array('conditions'=>$conditions));
			foreach($group_detail2 as $group_data2)
			{
			$group_id = (int)$group_data2['accounts_group']['auto_id'];
			}



			$auto_id = "";
			$validdddnnn=5;
        
			$account_nameee = trim($account_name);
			$account_nameee = htmlentities($account_nameee);
		
		
		$this->loadmodel('ledger_account'); 
			$conditions=array("ledger_name"=> new MongoRegex('/^' .  trim($account_name) . '$/i'),"group_id"=>$group_id);
			$conditions =array( '$or' => array( 
			array("ledger_name"=> new MongoRegex('/^' .  trim($account_name) . '$/i'),"group_id"=>$group_id),
			array("ledger_name"=> $account_name ,"group_id"=>$group_id),
			array("ledger_name"=> $account_nameee,"group_id"=>$group_id)));
	$ledg_ddtaill=$this->ledger_account->find('all',array('conditions'=>$conditions));
		foreach($ledg_ddtaill as $ledgr_dattt)
		{
		$auto_id = (int)$ledgr_dattt['ledger_account']['auto_id'];
		$ledger_type = 2;
		$validdddnnn=555;
		}
		
	
	  
	

		if($group_id == 34)
		{
		$this->loadmodel('flat'); 
		$conditions=array("flat_name"=> new MongoRegex('/^' .  $flatt_nammm . '$/i'),"society_id"=>$s_society_id);
		$flat_data=$this->flat->find('all',array('conditions'=>$conditions));
		foreach($flat_data as $flltdddt)
		{
		$flt_idddd = (int)$flltdddt['flat']['flat_id'];
		$wingg_idddd = (int)$flltdddt['flat']['wing_id'];
		}	
		
       
	    		
		$this->loadmodel('ledger_sub_account'); 
		$conditions=array("flat_id"=>$flt_idddd, "ledger_id"=>$group_id);
		$subledger_data=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
		foreach($subledger_data as $sub_lddrr_dddttt)
		{
		$auto_id = (int)$sub_lddrr_dddttt['ledger_sub_account']['auto_id'];
		$ledger_type = 1;
		$validdddnnn=555;
		$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat_with_brackets'),array('pass'=>array($wingg_idddd,$flt_idddd)));	
		}
		}
		else
		{
		$this->loadmodel('ledger_sub_account'); 
		$conditions=array("name"=> new MongoRegex('/^' .  $account_name . '$/i'),"ledger_id"=>$group_id);
		$subledger_data=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
		foreach($subledger_data as $sub_lddrr_dddttt)
		{
		$auto_id = (int)$sub_lddrr_dddttt['ledger_sub_account']['auto_id'];
		$ledger_type = 1;
		$validdddnnn=555;
		}
		}
		
		
$table[] = array(@$account_name,@$debit_or_credit,@$priciple_amount,@$auto_id,@$ledger_type,@$group_id,@$group_name,@$penalty_amount,@$flat_id,@$wing_flat,@$validdddnnn,@$flt_idddd);
	  }
      $i++;
	  }

$this->set('table',$table);

	$this->loadmodel('ledger_sub_account');
	$conditions=array("society_id" => $s_society_id);
	$cursor1 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
	$this->set('cursor1',$cursor1);

		$this->loadmodel('ledger_account');
		$cursor2 = $this->ledger_account->find('all');
		$this->set('cursor2',$cursor2);

	$this->loadmodel('accounts_group');
	$cursor3 = $this->accounts_group->find('all');
	$this->set('cursor3',$cursor3);
}
///////////////////////////////////// End Opening Balance Import Ajax //////////////////////////////////////////

//////////////////////////// Start Save Open Bal //////////////////////////////////////////////////////////
function save_open_bal()
{
	$this->layout='blank';
	$s_society_id = (int)$this->Session->read('society_id');
	$s_user_id = (int)$this->Session->read('user_id');
	
		$q=$this->request->query('q'); 
		$myArray = json_decode($q, true);
	
		$c=1;
		$report=array();
		$array1 = array();
		foreach($myArray as $child){
			$c++;
				if(empty($child[0])){
				$report[]=array('tr'=>$c,'td'=>1, 'text' => 'Required');
				}
				if(empty($child[1])){
				$report[]=array('tr'=>$c,'td'=>2, 'text' => 'Required');
				}

				if(empty($child[2]) && empty($child[3])){
				$report[]=array('tr'=>$c,'td'=>3, 'text' => 'Required');
			}
		}
		
		if(sizeof($report)>0){
		$output=json_encode(array('report_type'=>'error','report'=>$report));
		die($output);
		}

		$t=1;
		$total_debit = 0;
		$total_credit = 0;
			foreach($myArray as $child)
			{
			$t++;

			$date2 = $child[5];
			$date1 = date("Y-m-d", strtotime($date2));
			$date1 = new MongoDate(strtotime($date1));

				if(empty($child[5]))
				{
				$output=json_encode(array('report_type'=>'fina','text'=>'Please Select Date'));
				die($output);
				}

	$this->loadmodel('financial_year');
	$conditions=array("society_id" => $s_society_id,"status"=>1);
	$cursor = $this->financial_year->find('all',array('conditions'=>$conditions));
	$abc = 555;
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
		if($abc == 555)
		{
		$output=json_encode(array('report_type'=>'fina','text'=>'Date is not in Financial Year'));
		die($output);
		}

		$opening_bal = $child[3];
			if($opening_bal == "")
			{
			$opening_bal = $child[2];
			}

				if(is_numeric($opening_bal))
				{
				}
				else
				{
				$output=json_encode(array('report_type'=>'fina','text'=>'Amount (Opening Balance Should be Numeric in row '.$t));
				die($output);
				}
					$penalty_amt = (int)$child[6];
					$ch2 = (int)$child[2];
					$ch3 = (int)$child[3];
			if($ch2 != 0)
			{
			$total_debit = $total_debit + $child[2] + $penalty_amt;
			}
			if($ch3 != 0)
			{
			$total_credit = $total_credit + $child[3];
			}
		}

		if($total_credit != $total_debit)
		{
		$output=json_encode(array('report_type'=>'fina','text'=>'Total Debit must be Equal to Total Credit','deb'=>$total_debit,'cre'=>$total_credit));
		die($output);
		}

		foreach($myArray as $child){
			$excel_ledger_id = (int)$child[0];
			$excel_account_name = trim($child[1]);
			$debit = (int)$child[2];
			$credit =(int)$child[3];
			$insert = (int)$child[4];
			$transaction_date =date("Y-m-d",strtotime($child[5]));
			$intrest_arrear = (int)$child[6];
			$flll_id = (int)$child[7];

	if($insert == 2){
	if($excel_ledger_id==34){
	
		$this->loadmodel('ledger_sub_account'); 
		$conditions=array("ledger_id"=>34,"name"=> new MongoRegex('/^' .  $excel_account_name . '$/i'),"flat_id"=>$flll_id);
		$result_ledger_sub_account=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
		$ledger_sub_account_id=$result_ledger_sub_account[0]["ledger_sub_account"]["auto_id"];

		$this->loadmodel('ledger');
		$ledger_auto_id=$this->autoincrement('ledger','auto_id');
		$this->ledger->saveAll(array("auto_id" => $ledger_auto_id,"ledger_account_id" => 34,"ledger_sub_account_id" => $ledger_sub_account_id,"debit"=>$debit,"credit"=>$credit,"table_name"=>"opening_balance","element_id"=>null,"society_id"=>$s_society_id,"transaction_date"=>strtotime($transaction_date)));

		
		if($intrest_arrear>0){
		$this->loadmodel('ledger');
		$ledger_auto_id=$this->autoincrement('ledger','auto_id');
		$this->ledger->saveAll(array("auto_id" => $ledger_auto_id,"ledger_account_id" => 34,"ledger_sub_account_id" => $ledger_sub_account_id,"debit"=>$intrest_arrear,"credit"=>null,"table_name"=>"opening_balance","element_id"=>null,"society_id"=>$s_society_id,"transaction_date"=>strtotime($transaction_date),"arrear_int_type"=>"YES"));
		}
		}else{
		$this->loadmodel('ledger_account'); 
		$conditions=array("group_id"=>$excel_ledger_id,"ledger_name"=> new MongoRegex('/^' .  $excel_account_name . '$/i'));
		$result_ledger_account=$this->ledger_account->find('all',array('conditions'=>$conditions));
		$ledger_account_id=$result_ledger_account[0]["ledger_account"]["auto_id"];

		$this->loadmodel('ledger');
		$ledger_auto_id=$this->autoincrement('ledger','auto_id');
		$this->ledger->saveAll(array("auto_id" => $ledger_auto_id,"ledger_account_id" => $ledger_account_id,"ledger_sub_account_id" => null,"debit"=>$debit,"credit"=>$credit,"table_name"=>"opening_balance","element_id"=>null,"society_id"=>$s_society_id,"transaction_date"=>strtotime($transaction_date)));

		}
		}
		} 
			$output=json_encode(array('report_type'=>'done','text'=>'Total Debit must be Equal to Total Credit'));
			die($output);
	}
//////////////////////////// End Save Open Bal //////////////////////////////////////////////////////////

///////////////////////////////// Start pay Bill ////////////////////////////////////////////////////////
function pay_bill()
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

				$receipt_no = (int)$this->request->query('b');
				$this->set('receipt_no',$receipt_no);

	if(isset($this->request->data['sub']))
	{
		$transaction_date = $this->request->data['date'];
		$bank_name = $this->request->data['bank_name'];
		$mobile = $this->request->data['mobile'];
		$bill_receipt = (int)$this->request->data['bill_no'];
		$branch = $this->request->data['branch'];
		$account_number = $this->request->data['acno'];
		$pay_amt = $this->request->data['amt'];
		$paying_mode = (int)$this->request->data['mode'];
			
			if($paying_mode == 1)
			{
			$cheque_number = $this->request->data['chq_no'];
			$mode="Cheque";
			}
			else
			{
			$cheque_number = "";
			$mode="Cash";
			}
			
		$transaction_date = date('Y-m-d',strtotime($transaction_date));
		$this->loadmodel('regular_bill');
		$this->regular_bill->updateAll(array("payment_date" => $transaction_date,"bank_name"=>$bank_name,"mobile"=>$mobile,"branch"=>$branch,"account_number"=>$account_number,"pay_amount"=>$pay_amt,"pay_mode"=>$mode,"cheque_no"=>$cheque_number),array("society_id" => $s_society_id,"receipt_id"=>$bill_receipt));
		
		?>
		<div class="modal-backdrop fade in"></div>
		<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-header">
		<center>
		<h3 id="myModalLabel3" style="color:#999;"><b>Pay Bill Detail</b></h3>
		</center>
		</div>
		<div class="modal-body">
		<center>
		<h5><b>Record Inserted Successfully</b></h5>
		</center>
		</div>
		<div class="modal-footer">
		<a href="my_flat_bill" class="btn blue">OK</a>
		</div>
		</div>
		<?php
	}
}
///////////////////////////////// End pay Bill //////////////////////////////////////////////////////////////////

////////////////////// Start Opening Balance  Excel Export ///////////////////////////////////////////////////
function open_excel()
{
		$this->layout="";
		$filename="Opening_Balance_Import";
		header ("Expires: 0");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/vnd.ms-excel");
		header ("Content-Disposition: attachment; filename=".$filename.".csv");
		header ("Content-Description: Generated Report" );

		$s_role_id=$this->Session->read('role_id');
		$s_society_id = (int)$this->Session->read('society_id');
		$s_user_id = (int)$this->Session->read('user_id');


$excel = "Group Name,A/c name,wing,unit,Amount Type(Debit or Credit),Amount(Opening Balance),Penalty \n";

		$this->loadmodel('ledger_accounts');
		$conditions = array('$or'=>array(array('society_id' =>$s_society_id),array('society_id' =>0)));
		$cursor = $this->ledger_accounts->find('all',array('conditions'=>$conditions));
		foreach($cursor as $collection)
			{
			$group_id = (int)$collection['ledger_accounts']['group_id'];
			$ledger_name = $collection['ledger_accounts']['ledger_name'];
		    $ledger_idddd = (int)$collection['ledger_accounts']['auto_id'];
				if($ledger_idddd != 34 && $ledger_idddd != 33 && $ledger_idddd != 35 && $ledger_idddd != 15)
				{	
				$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($group_id)));
				foreach ($result_ag as $collection) 
				{
				$accounts_id = (int)$collection['accounts_group']['accounts_id'];	
				$group_name = $collection['accounts_group']['group_name'];	
				}
				$excel.= "$group_name,$ledger_name \n";
				}
			}

			$this->loadmodel('ledger_sub_account');
			$conditions=array("society_id" => $s_society_id);
			$result1 = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
			foreach($result1 as $datadd)
			{
			$user_id = "";
			$flat_id = "";
				$ledger_id = (int)$datadd['ledger_sub_account']['ledger_id'];
				$name = $datadd['ledger_sub_account']['name'];
				$user_id = (int)@$datadd['ledger_sub_account']['user_id'];
				$flat_id = (int)@$datadd['ledger_sub_account']['flat_id'];
	
			if($ledger_id == 34)
			{
				$flat_dtttl = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch'),array('pass'=>array($flat_id)));
				foreach($flat_dtttl as $flltdetll)
				{
				$wing_id = (int)$flltdetll['flat']['wing_id'];
				$flat_name = $flltdetll['flat']['flat_name'];
				}

			$wing_data = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_fetch'),array('pass'=>array($wing_id)));
			foreach($wing_data as $wnngdddtt){
			$wing_name = $wnngdddtt['wing']['wing_name'];
			}
			}	

		$result_la = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account'),array('pass'=>array($ledger_id)));
		foreach ($result_la as $collection) 
		{
		$ledger_name = $collection['ledger_account']['ledger_name'];	
		}


		if($ledger_id==34){
		$excel.= "$ledger_name,$name,$wing_name,$flat_name \n";
		}
		else {
		$excel.= "$ledger_name,$name \n";
		}
			}
echo $excel;
}
////////////////////// End Opening Balance  Excel Export ///////////////////////////////////////////////////////////
}
?>