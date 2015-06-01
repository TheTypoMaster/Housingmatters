<?php
class HmsController extends AppController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Hms';

function encode($string,$key) {
$key = sha1($key);
$strLen = strlen($string);
$keyLen = strlen($key);
for ($i = 0; $i < $strLen; $i++) {
$ordStr = ord(substr($string,$i,1));
if (@$j == $keyLen) { $j = 0; }
$ordKey = ord(substr($key,@$j,1));
@$j++;
@$hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
}
return $hash;
}

function decode($string,$key) {
$key = sha1($key);
$strLen = strlen($string);
$keyLen = strlen($key);
for ($i = 0; $i < $strLen; $i+=2) {
$ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
if (@$j == $keyLen) { @$j = 0; }
$ordKey = ord(substr($key,@$j,1));
@$j++;
@$hash .= chr($ordStr - $ordKey);
}
return @$hash;
}

function smtpmailer($to, $from, $from_name, $subject, $message_web,$reply)
{
App::import('Vendor', 'PhpMailer', array('file' => 'phpmailer' . DS . 'class.phpmailer.php'));	
$account="AKIAIQ6NPU33IN7AVLIA";
$password="AkaJYpgMa9CDGrjjGdPhRvztZMWG5yLzvmWHKZ+Ylv34";
//$to="rohitkumarjoshi43@gmail.com";
//$from="alerts@housingmatters.in";
//$from_name="testing";
//$message_web="<strong>Amazon Mail</strong>"; // HTML message
//$subject="Amazon Email to Mahantesh Email Account";
/*End Config*/
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = "email-smtp.us-west-2.amazonaws.com";
$mail->SMTPAuth= true;
$mail->Port = 587;
$mail->Username= $account;
$mail->Password= $password;
$mail->SMTPSecure = 'tls';
$mail->From = $from;
$mail->FromName= $from_name;
$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = $message_web;
$mail->AddReplyTo($reply ,"HousingMatters");
$mail->addAddress($to);

//$mail->SMTPDebug = 1;
if(!$mail->send())
{
//echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
//echo "E-Mail has been sent";
//return true;
}
}

function cronjob()
{
	$this->loadmodel('email_requests');
	$conditions=array('flag'=>0);
	$result1_email=$this->email_requests->find('all',array('conditions'=>$conditions,'limit'=>2));
	foreach($result1_email as $data)
	{
		$e_id=$data['email_requests']['e_id'];
		$to=$data['email_requests']['to'];
		$from=$data['email_requests']['from'];
		$from_name=$data['email_requests']['from_name'];
		$subject=$data['email_requests']['subject'];
		$message_web=$data['email_requests']['message_web'];
		$reply=$data['email_requests']['reply'];
		
		$this->smtpmailer($to,$from,$from_name,$subject,$message_web,$reply,$is_gmail = true);
		
		$this->loadmodel('email_requests');
		$this->email_requests->updateAll(array('flag'=>1),array('e_id'=>$e_id));
	}
	
}

function content_moderation_society($content_check)
{
	$s_society_id=$this->Session->read('society_id');
	$this->loadmodel('society');
	$conditions=array('society_id'=>$s_society_id);
	$result1=$this->society->find('all',array('conditions'=>$conditions));
	foreach($result1 as $data)
	{
		 $content=$data['society']['content_moderation'];

	}
	
		
		foreach($content_check as $c_moda)
		{	
			
				if(in_array($c_moda,$content))
				{

				 return 0;
					
				}
				
				
			
		}
		return 1;
	
}

function content_check_des()
{
	$this->layout='blank';
	$description=$this->request->query['description'];
	$des=explode(' ',$description);
	$r=$this->content_moderation_society($des);
	if($r==0)
	{
		echo "false";
	}
	else
	{
		echo "true";
	}
	
}

function ath()
{
$user_id=$this->Session->read('user_id');
if(empty($user_id))
{
$this->Session->destroy();
$this->redirect(array('action' => 'index'));
}

date_default_timezone_set('Asia/Kolkata');	


}

function send_email($to,$from,$from_name,$subject,$message_web,$reply)
{
//$this->layout='session';
$this->loadmodel('email_request');
$er=$this->autoincrement('email_request','e_id');
$this->email_request->saveAll(array('e_id' => $er, 'to' => $to, 'from' => $from, 'from_name' => $from_name, 'subject' => $subject, 
'message_web' => $message_web, 'reply' => $reply, 'flag' => 0));
}


function logout() 
{

$this->layout='blank';
$this->Session->destroy();
$this->redirect(array('action' => 'index'));
}

function beforeFilter()
{
$s_society_id=$this->Session->read('society_id');
$this->set('s_society_id',$s_society_id);
$s_role_id=$this->Session->read('role_id');
$this->set('s_role_id',$s_role_id);

$this->loadmodel('role_privileges');
$conditions=array("society_id" => $s_society_id,"role_id" => $s_role_id);
$this->set('result',$this->role_privileges->find('all',array('conditions'=>$conditions)));
//Configure::write('debug', 0);

}

function fetch_submoduleid_usermanagement($module_id) 
{
$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('role_privilege');
$conditions=array("module_id" => $module_id,"society_id" => $s_society_id,"role_id" => $s_role_id);
$order=array('role_privilege.sub_module_id'=> 'ASC');
return $result=$this->role_privilege->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
}


function fetch_module_type_id($module_id) 
{
$this->loadmodel('main_module');
$conditions=array("auto_id" => $module_id);
$result_moduletype_id=$this->main_module->find('all',array('conditions'=>$conditions));
foreach ($result_moduletype_id as $ddq) 
{
return $mt_id=$ddq["main_module"]["mt_id"];
}
}

function fetch_module_type_name($result_moduletype_id) 
{
$this->loadmodel('module_type');
$conditions=array("module_type_id" => $result_moduletype_id);
return $this->module_type->find('all',array('conditions'=>$conditions));

}


function fetch_pagename_usermanagement($sub_module_id) 
{
$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('page');
$conditions=array("sub_module_id" => $sub_module_id);
return $result=$this->page->find('all',array('conditions'=>$conditions,'limit'=>1));
}

function fetch_mainmodulename_usermanagement($module_id) 
{
$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('main_module');
$conditions=array("auto_id" => $module_id);
return $result=$this->main_module->find('all',array('conditions'=>$conditions));
}

function fetch_rolename_via_roleid($s_role_id) 
{
$s_society_id=$this->Session->read('society_id');

$this->loadmodel('role');
$conditions=array("society_id"=>$s_society_id,"role_id"=>$s_role_id);
$result=$this->role->find('all',array('conditions'=>$conditions));
foreach ($result as $dd) 
{
return $role_name=$dd["role"]["role_name"];
}
}

function fetch_wingname_via_wingid($wing_id) 
{
$s_society_id=$this->Session->read('society_id');

$this->loadmodel('wing');
$conditions=array("society_id"=>$s_society_id,"wing_id"=>$wing_id);
$result=$this->wing->find('all',array('conditions'=>$conditions));
foreach ($result as $dd) 
{
return $wing_name=$dd["wing"]["wing_name"];
}
}

function fetch_users_role() 
{
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('user');
$conditions=array("user_id"=>$s_user_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
return @$data['user']['role_id'];	 

}	
}






function change_role() 
{
$this->layout='blank';
$role=(int)$this->request->query('role');
$this->Session->write('role_id', $role);
$this->redirect(array('action' => 'dashboard'));
}


function change_society() 
{
$this->layout='blank';
$s_login_id=(int)$this->Session->read('login_id');
$society=(int)$this->request->query('society');
$this->loadmodel('user');
$conditions=array('login_id'=>$s_login_id,'society_id'=>$society);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$user_id=$data['user']['user_id'];
	$user_name=$data['user']['user_name'];
	$wing=$data['user']['wing'];
	$tenant=$data['user']['tenant'];
	$role_id=$data['user']['default_role_id'];
	}

$this->Session->write('user_id', $user_id);
$this->Session->write('role_id', $role_id);
$this->Session->write('user_name', $user_name);
$this->Session->write('wing', $wing);
$this->Session->write('tenant', $tenant);
$this->Session->write('society_id', $society);
$this->redirect(array('action' => 'dashboard'));
}


function submenu()
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$page_namr_url=pathinfo($_SERVER[ 'REQUEST_URI'],PATHINFO_FILENAME);
$url = parse_url($page_namr_url) ;
$page_namr_url=  $url['path'];
$this->loadmodel('page');
$conditions=array("page_name" => $page_namr_url);
$cursor=$this->page->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{					
$module_id=$collection["page"]["module_id"];
$sub_module_id=$collection["page"]["sub_module_id"];
}

$this->loadmodel('role_privilege');
$conditions=array("module_id" => @$module_id,"society_id" => $s_society_id,"role_id" => $s_role_id);
$cursor=$this->role_privilege->find('all',array('conditions'=>$conditions));
sort($cursor);
if(sizeof($cursor)>1)
{
?>
<div align="center">
<?php
foreach ($cursor as $collection) 
{					
$sub_module_id=$collection["role_privilege"]["sub_module_id"];

$this->loadmodel('page');
$conditions=array("sub_module_id" => $sub_module_id);
$cursor_page=$this->page->find('all',array('conditions'=>$conditions,'limit'=>1));
foreach ($cursor_page as $collection) 
{					
$page_name=$collection["page"]["page_name"];
}

$this->loadmodel('sub_module');
$conditions=array("auto_id" => $sub_module_id);
$cursor_sub_module=$this->sub_module->find('all',array('conditions'=>$conditions,'limit'=>1));
foreach ($cursor_sub_module as $collection) 
{					
$sub_module_name=$collection["sub_module"]["sub_module_name"];
}
$sub_module_id_fix="fix".$sub_module_id;
echo '<a href='.$page_name.' id='.$sub_module_id_fix.' class="btn blue allsubmenu" style="margin-left: 2px;margin-bottom: 4px;">'.$sub_module_name.'</a>';
}
?>
</div>
<?php

}
}


function check_housingmatters_privilages()
{
$s_society_id=$this->Session->read('society_id');
if($s_society_id!=0)
{
$this->layout='resricted';
?>
<div style="min-height: 85%;margin-top: 60px; " align="center">
<h2>Sorry<br/>You are not allowed to access this page.</h2>
<img src="<?php echo $this->webroot ; ?>/as/hm/hm-logo.png" alt="logo" >
<br/><h4>Back to <a href="dashboard">Dashboard</a></h4>
</div>
<?php
}

}


function check_user_privilages()
{

$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$page_namr_url=pathinfo($_SERVER[ 'REQUEST_URI'],PATHINFO_FILENAME);
$url = parse_url($page_namr_url) ;
 $page_namr_url=  $url['path'];
$this->loadmodel('page');
$conditions=array("page_name" => $page_namr_url);
$cursor=$this->page->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{					
$module_id=$collection["page"]["module_id"];
$sub_module_id=$collection["page"]["sub_module_id"];
$this->set('id_current_page',$sub_module_id);
}

$this->loadmodel('role_privilege');
$conditions=array("module_id" => $module_id,"sub_module_id" => $sub_module_id,"society_id" => $s_society_id,"role_id" => $s_role_id);
$num=$this->role_privilege->find('count',array('conditions'=>$conditions));
if($num==0)
{
$this->layout='resricted';
?>
<div style="min-height: 85%;margin-top: 60px; " align="center">
<h2>Sorry<br/>You are not allowed to access this page.</h2>
<img src="<?php echo $this->webroot ; ?>/as/hm/hm-logo.png" alt="logo" >
<br/><h4>Back to <a href="dashboard">Dashboard</a></h4>
</div>
<?php

}

}

function rendom_color($last_color) 
{
$allcolors=array('#285e8e','#398439','#269abc','#d58512','#ac2925','#45b6af','#3ea7a0','#9b59b6');
$key = array_search($last_color,$allcolors);
if($key!==false){
unset($allcolors[$key]);
}
return $allcolors[array_rand($allcolors)];
}

function random_color_part() {
return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}
function rendom_color_new() 
{
//$allcolors=array('#285e8e','#398439','#269abc','#d58512','#ac2925','#45b6af','#3ea7a0','#9b59b6');
//return $allcolors[array_rand($allcolors)];
return '#'.$this->random_color_part() . $this->random_color_part() . $this->random_color_part();
}



function autoincrement($table,$field) 
{

$this->loadmodel($table);
$order=array($table.'.'.$field=>'DESC');
$cursor=$this->$table->find('all',array('order'=>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection[$table][$field];
}
if(empty($last))
{
$auto=0;
}
else
{
$auto=$last;	
}
return ++$auto;

}

function autoincrement_with_society($table,$field) 
{
$s_society_id=$this->Session->read('society_id');
$this->loadmodel($table);
$conditions=array("society_id" => $s_society_id);
$order=array($table.'.'.$field=>'DESC');
$cursor=$this->$table->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection[$table][$field];
}
if(empty($last))
{
$auto2=0;
}
else
{
$auto2=$last;	
}
return ++$auto2;

}

function autoincrement_with_society_ticket($table,$field) 
{
$s_society_id=$this->Session->read('society_id');
$this->loadmodel($table);
$conditions=array("society_id" => $s_society_id);
$order=array($table.'.'.$field=>'DESC');
$cursor=$this->$table->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection[$table][$field];
}
if(empty($last))
{
$auto2=1000;
}
else
{
$auto2=$last;	
}
return ++$auto2;

}

///////////////////////////// Setting ///////////////////////////////

function society_settings()
{
	
	$this->layout='session';
	$this->ath();
	$s_society_id=$this->Session->read('society_id');
	
	$this->set('s_user_id',$this->Session->read('user_id'));
	$this->set('role_id',$this->Session->read('role_id'));
	if(isset($this->request->data['sub']))
	{
			@$signup_auto=$this->request->data['signup'];
			@$help_desk=$this->request->data['help_desk'];
			@$family_member=$this->request->data['family_member'];
			@$notice=$this->request->data['notice'];
			@$document=$this->request->data['document'];
			@$discussion_forum=$this->request->data['discussion_forum'];
			@$discussion_forum_email=$this->request->data['discussion_forum1'];
			@$poll=$this->request->data['poll'];
			@$account_email=$this->request->data['account1'];
			@$account_sms=$this->request->data['account2'];
			@$account_zero_ammount=$this->request->data['account3'];
			@$banned_word=$this->request->data['banned'];
			@$banned_word= explode(",",$banned_word);
			//$society_pan=$this->request->data['pan'];
			//$society_tax=$this->request->data['tax_num'];
			if(empty($signup_auto))
			{
				$signup_auto=0;
				
			}
	    	if(empty($help_desk))
			{
				$help_desk=0;
				
			}
			if(empty($notice))
			{
				$notice=0;
				
			}
			if(empty($document))
			{
				$document=0;
				
			}
			if(empty($discussion_forum))
			{
				$discussion_forum=0;
				
			}
			if(empty($discussion_forum_email))
			{
				$discussion_forum_email=0;
				
			}
			if(empty($poll))
			{
				$poll=0;
				
			}
			if(empty($account_email))
			{
				$account_email=0;
				
			}
			if(empty($account_sms))
			{
				$account_sms=0;
				
			}
			if(empty($family_member))
			{
				$family_member=0;
				
			}
			if(empty($account_zero_ammount))
			{
				$account_zero_ammount=0;
				
			}
			
			$this->loadmodel('society');
			$this->society->updateAll(array('signup'=>$signup_auto,'help_desk'=>$help_desk,'notice'=>$notice,'document'=>$document,'discussion_forum'=>$discussion_forum,'discussion_forum_email'=>$discussion_forum_email,'poll'=>$poll,'account_email'=>$account_email,'account_sms'=>$account_sms,'account_zero_ammount'=>$account_zero_ammount,'content_moderation'=>$banned_word,'family_member'=>$family_member),array('society_id'=>$s_society_id));
		
	}
	
	$this->loadmodel('society');
	$result=$this->society->find('all',array('conditions'=>array('society_id'=>$s_society_id)));
	$this->set('result_society',$result);

}

///////////////////////// end Setting /////////////////////////////


///////////////////////   Deactive functionality start   //////////////////////////////////////


function add_field()
{
$this->layout="session";	
$s_society_id=$this->Session->read('society_id');	
$this->loadmodel('user');
$result=$this->user->find('all');
$this->set('result_user',$result);
foreach($result as $data)
{
$user_id=$data['user']['user_id'];
$user_name=$data['user']['user_name'];
$email=$data['user']['email'];
$mobile=$data['user']['mobile'];
$password=$data['user']['password'];
@$signup_random=$data['user']['signup_random'];
$user=$email;
if(empty($email))
{
$user=$mobile;
}
$log_i=$this->autoincrement('login','login_id');
$this->loadmodel('login');
$this->login->saveAll(array('login_id'=>$log_i,'user_name'=>$user,'password'=>$password,'signup_random'=>$signup_random,'mobile'=>$mobile));
$this->loadmodel('user');
$this->user->updateAll(array('login_id'=>$log_i,'s_default'=>1),array('user_id'=>$user_id));
}

}



function user_deactive()
{
$this->layout="session";	
$s_society_id=$this->Session->read('society_id');	
$this->loadmodel('user');
$conditions=array('society_id'=>$s_society_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user_deactive',$result);
}

function user_deactive_ajax()
{
	 $this->layout="blank";
	 $s_society_id=$this->Session->read('society_id');
	 $user_id=(int)$this->request->query['t'];
	 $status=(int)$this->request->query['d'];
	  $auto=(int)$this->request->query['a'];
	  $this->set('i',$auto);
	  $this->set('user_id',$user_id);
	 $this->set('det',$status);
	 if($status==0)
	 {
		$this->loadmodel('user');
		date_default_timezone_set('Asia/kolkata');
		$date=date("d-m-Y");
		$time=date('h:i:a',time());
		$this->user->updateAll(array('deactive'=>1),array('user_id'=>$user_id));
		$this->loadmodel('log');
		$i=$this->autoincrement('log','log_id');
		$this->log->save(array('log_id'=>$i,'user_id'=>$user_id,'society_id'=>$s_society_id,'deactive_date'=>$date,'deactive_time'=>$time,'status'=>1));
		
		$this->loadmodel('ledger_sub_account');
		$this->ledger_sub_account->updateAll(array('deactive'=>1),array('user_id'=>$user_id));
		
		
	 }
	 
	 
	 if($status==1)
	 {
		 
		$this->loadmodel('user');
		date_default_timezone_set('Asia/kolkata');
		$date=date("d-m-Y");
		$time=date('h:i:a',time());
		$this->user->updateAll(array('deactive'=>0),array('user_id'=>$user_id));
		$this->loadmodel('log');
		$i=$this->autoincrement('log','log_id');
		$this->log->save(array('log_id'=>$i,'user_id'=>$user_id,'society_id'=>$s_society_id,'active_date'=>$date,'active_time'=>$time,'status'=>2));
		
		$this->loadmodel('ledger_sub_account');
		$this->ledger_sub_account->updateAll(array('deactive'=>0),array('user_id'=>$user_id));
	 }
		
}

function all_user_deactive()
{
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id,'deactive'=>0);
	return $result_user=$this->user->find('all',array('conditions'=>$conditions));
}


function all_owner_deactive()
{
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id,'tenant'=>1,'deactive'=>0);
	return $result_user=$this->user->find('all',array('conditions'=>$conditions));
}


function all_tenant_deactive()
{
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id,'tenant'=>2,'deactive'=>0);
	return $result_user=$this->user->find('all',array('conditions'=>$conditions));
}
function multiple_flat()
{
	$this->layout="session";
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$result=$this->all_user_deactive();
	$this->set('result_user',$result);
	if($this->request->is('post'))
	{
		
		$user_sel=(int)$this->request->data['user_sel'];
		$wing=(int)$this->request->data['sel_wing_id'];
		$flat=(int)$this->request->data['sel_flat_id'];
		
		$this->loadmodel('user');
		$conditions =array( '$or' => array( 
		array("wing" => $wing, "flat" => $flat,'society_id'=>$s_society_id),
		array("multiple_flat" => array('$in' => array($wing)), "multiple_flat" => array('$in' => array($flat)),'society_id'=>$s_society_id),
		));
		 $result_count=$this->user->find('all',array('conditions'=>$conditions));
		$n= sizeof($result_count);
		if($n==0)
		{
		$this->loadmodel('user');
		$result2=$this->user->find('all',array('conditions'=>array('user_id'=>$user_sel)));
		foreach($result2 as $data)
		{
			  $wing_id=$data['user']['wing'];
			  $flat_id=$data['user']['flat'];
			  $multiple_flat=$data['user']['multiple_flat'];
		}
		
		if(empty($multiple_flat))
		{	
			$ar[]=array($wing,$flat);
			$ar[]=array($wing_id,$flat_id);
								
		}	
		
		
		if(!empty($multiple_flat))
		{
			$ar[]=array($wing,$flat);
			foreach($multiple_flat as $da)
			{
				$w=$da[0];
				$f=$da[1];
				$ar[]=array($w,$f);
			}
		}	
										
		$this->loadmodel('user');
		$this->user->updateAll(array('multiple_flat'=>$ar),array('user_id'=>$user_sel));
	 }	
		else
		{
			$this->set('wrong','<span style="color:red; font-size:14px;">Wing-Flat is already exits</span>');
		}	
	}
	
}
function multiple_flat_ajax1()
{
    $this->layout="blank";
	 $flat_id=(int)$this->request->query('vb');	
	 $this->loadmodel('flat');
	 $this->set('result_flat',$this->flat->find('all',array('conditions'=>array('wing_id'=>$flat_id))));
	
}
function multiple_flat_ajax()
{
	 $this->layout="blank";
     $s_society_id=$this->Session->read('society_id');
	 $user_id=(int)$this->request->query('con');
	$conditions=array('user_id'=>$user_id);
	$this->loadmodel('user');
	$result=$this->user->find('all',array('conditions'=>$conditions));
	$this->set('result_user',$result);
	$this->loadmodel('wing');
	$conditions2=array('society_id'=>$s_society_id);
	
	$this->set('result_wing',$this->wing->find('all',array('conditions'=>$conditions2)));
}	



function all_role_wise_deactive($role_id)
{
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id,'role_id'=>$role_id,'deactive'=>0);
	return $result_user=$this->user->find('all',array('conditions'=>$conditions));
}


function all_wing_wise_deactive($wing_id)
{
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id,'wing'=>$wing_id,'deactive'=>0);
	return $result_user=$this->user->find('all',array('conditions'=>$conditions));
}


///////////////////////////// End  ///////////////////////////////////////


function profile_picture($user_id)
{
$this->loadmodel('user');
$conditions=array("user_id" => $user_id);
return $this->user->find('all',array('conditions'=>$conditions));
}

function wing_flat($wing_id,$flat_id)
{
$this->loadmodel('wing');
$conditions=array("wing_id" => $wing_id);
$result=$this->wing->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
$wing_name=$data['wing']['wing_name'];
}

$this->loadmodel('flat');
$conditions=array("flat_id" => $flat_id);
$result2=$this->flat->find('all',array('conditions'=>$conditions));
foreach($result2 as $data)
{
$flat_name=$data['flat']['flat_name'];
}

if(!empty($wing_name) && !empty($flat_name))
{
return @$wing_name.'-'.@$flat_name;
}


}

function csv_import()
{
$this->layout='session';
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


for($i=1;$i<sizeof($test);$i++)
{
$r=explode(',',$test[$i][0]);

if(!empty($r[0])) {	$ok1=2; }
else { $ok1=1; $error_msg[]="UserName should not be empty";	}

if(!empty($r[1]))
{	
$ok2=2; 

if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $r[1])) {
$ok3=2;
}
else {
$ok3=1; $error_msg[]="Email Id is not valid.";
}

}
else { $ok2=1; $error_msg[]="Email should not be empty";	}

if(!empty($r[2])) {	$ok4=2; }
else { $ok4=1; $error_msg[]="Password should not be empty";	}


if($ok1==2 && $ok2==2 && $ok3==2 && $ok4==2)
{

}	

}

$this->set('error_msg',@$error_msg);

$this->set('ok1',$ok1);
$this->set('ok2',$ok2);
$this->set('ok3',$ok3);
$this->set('ok4',$ok4);

if($ok1==2 && $ok2==2 && $ok3==2 && $ok4==2)
{
//$cmd='cmd.exe /c C:\\mongodb\bin\mongoimport -d test -c test  -type csv -f name,age  '.$dir.'/'.$file;
//$cmd='cmd.exe /c C:\\mongodb\bin\mongoimport -d test -c test  -type csv  '.$dir.'/'.$file.' --headerline';
//exec($cmd,$output,$err);

for($i=1;$i<sizeof($test);$i++)
{
$r=explode(',',$test[$i][0]);
$u=$this->autoincrement('user','user_id');
$this->loadmodel('user');
$this->user->saveAll(array('user_id' => $u, 'user_name' => $r[0],'email' => $r[1], 'password' => $r[2], 'mobile' => "",  'society_id' => 0, 'role_id' => array(2), 'committee' => 2 , 'tenant' => 1, 'wing' => 0, 'flat' => 0,'residing' => 1,'default_role_id'=>2,'profile_pic'=>"blank.jpg"));
}

$this->set('sucess','Csv Imported successfully.');
}

}
}


function VerifyMailAddress($address) 
{
$Syntax='#^[w.-]+@[w.-]+.[a-zA-Z]{2,5}$#';
if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
return 0;
}
else
{ return 1; }
}


function VerifyMobileNo($number) 
{
if (preg_match('/^\d{10}$/', $number)) {
return 1;
} else {
return 0;
}
}






function csv_import_signup()
{
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$soc_result=$this->society_name($s_society_id);
foreach($soc_result as $data)
{
	$society_name=$data['society']['society_name'];
	
}
if ($this->request->is('post')) 
{

date_default_timezone_set('Asia/kolkata');
 $date=date("d-m-Y");
 $time=date('h:i:a',time());
$file=$this->request->form['file']['name'];
$extension = pathinfo($file, PATHINFO_EXTENSION);
if($extension!='csv') { echo '<script>alert("file extension should be csv." ); location="csv_import_signup"; </script>';	exit; }


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


for($i=1;$i<sizeof($test);$i++)
{
$row_no=$i+1;
$r=explode(',',$test[$i][0]);
$username=trim($r[0]);
$wing=trim($r[1]); 
$flat=trim($r[2]);
$email=trim($r[3]);
if($i==1) { $email_current=array(); }
$mobile=trim($r[4]);
$owner=trim($r[5]);
$committee=trim($r[6]);
$residing =trim($r[7]);


if(!empty($username)) {	$ok=2; }
else { $ok=1; $error_msg[]="UserName should not be empty in row ".$row_no.".";	break;}

$this->loadmodel('wing'); 
$conditions=array("society_id"=>$s_society_id,"wing_name"=> new MongoRegex('/^' .  $wing . '$/i'));
$result_wing=$this->wing->find('all',array('conditions'=>$conditions));
$result_wing_count=sizeof($result_wing);

if($result_wing_count>0) 
{	
$ok=2; 
$wing_id=$result_wing[0]['wing']['wing_id'];
$wing_id_d[]=$wing_id;
}
else { $ok=1; $error_msg[]="Wing name is not right in row ".$row_no."."; break;}

$this->loadmodel('flat'); 
$conditions=array("wing_id"=>$wing_id,"flat_name"=> new MongoRegex('/^' .  $flat . '$/i'));
$result_flat=$this->flat->find('all',array('conditions'=>$conditions));
$result_flat_count=sizeof($result_flat);

if($result_flat_count>0) 
{ 
$ok=2; 		
$flat_id=$result_flat[0]['flat']['flat_id'];	
$flat_id_d[]=$flat_id;
}
else { $ok=1; $error_msg[]="Flat name is not right in row ".$row_no.".";  break;}


if(!empty($email))
{
$email_varify=$this->VerifyMailAddress($email);
if($email_varify==1) 
{
$this->loadmodel('user'); 
$conditions=array("email"=>$email);
$result_email=$this->user->find('all',array('conditions'=>$conditions));
$result_email_count=sizeof($result_email);


if (in_array($email, $email_current))
{
$ok=1; $error_msg[]="Email Id can't be same in row ".$row_no.".";  break;
}
$email_current[]=$email;   
if($result_email_count==0) 
{ 
$ok=2; 	
}

else { $ok=1; $error_msg[]="Email Id is already exist in row ".$row_no.".";  break;}
}
else { $ok=1; $error_msg[]="Email Id format is not valid in row ".$row_no.".";  break;}
}
else { $ok=1; $error_msg[]="Email Id should not be empty in row ".$row_no.".";  break;}




if(!empty($mobile))
{
	
$ok=2; 
$mobile_varify=$this->VerifyMobileNo($mobile);
if($mobile_varify==1)
{
$this->loadmodel('user'); 
$conditions=array("mobile"=>$mobile);
$result_mobile=$this->user->find('all',array('conditions'=>$conditions));
$result_mobile_count=sizeof($result_mobile);	
if($result_mobile_count==0)
{	
	
$ok=2; 
}
else{ $ok=1; $error_msg[]="Mobile Number is already exist in row ".$row_no.".";  break; }

}
else { $ok=1; $error_msg[]="Mobile format is not valid in row ".$row_no.".";  break;}

}
else { $ok=1; $error_msg[]="Mobile should not be empty in row ".$row_no.".";  break;}



if(!empty($owner))
{
$result_owner_yes = strcasecmp($owner, 'yes');
$result_owner_no = strcasecmp($owner, 'no');
if ($result_owner_yes == 0 || $result_owner_no == 0) 
{

$ok=2;
} 
else { $ok=1; $error_msg[]="Owner should be yes or no in row ".$row_no.".";  break;}
}
else { $ok=1; $error_msg[]="Owner should not be empty in row ".$row_no.".";  break;}



if(!empty($committee))
{
$result_committee_yes = strcasecmp($committee, 'yes');
$result_committee_no = strcasecmp($committee, 'no');
if ($result_committee_yes == 0 || $result_committee_no == 0) 
{
$ok=2;
} 
else { $ok=1; $error_msg[]="Committee should be yes or no in row ".$row_no.".";  break;}
}
else { $ok=1; $error_msg[]="Committee should not be empty in row ".$row_no.".";  break;}


if(!empty($residing))
{
$result_residing_yes = strcasecmp($residing, 'yes');
$result_residing_no = strcasecmp($residing, 'no');
if ($result_residing_yes == 0 || $result_residing_no == 0) 
{

$ok=2;
} 
else { $ok=1; $error_msg[]="Residing should be yes or no in row ".$row_no.".";  break;}
}
else { $ok=1; $error_msg[]="Residing should not be empty in row ".$row_no.".";  break;}


}

$this->set('error_msg',@$error_msg);

$this->set('ok',$ok);


if($ok==2)
{

//$cmd='cmd.exe /c C:\\mongodb\bin\mongoimport -d test -c test  -type csv -f name,age  '.$dir.'/'.$file;
//$cmd='cmd.exe /c C:\\mongodb\bin\mongoimport -d test -c test  -type csv  '.$dir.'/'.$file.' --headerline';
//exec($cmd,$output,$err);

for($i=1;$i<sizeof($test);$i++)
{
$r=explode(',',$test[$i][0]);

$ii=$i-1;
$username=$r[0];
$email=trim($r[3]);
$mobile=trim($r[4]);

$owner=trim($r[5]);
$result_owner_yes = strcasecmp($owner, 'yes');
$result_owner_no = strcasecmp($owner, 'no');
if ($result_owner_yes == 0) { $result_owner=1; }
if ($result_owner_no == 0) { $result_owner=2; }

$committee=trim($r[6]);
$result_committee_yes = strcasecmp($committee, 'yes');
$result_committee_no = strcasecmp($committee, 'no');
if ($result_committee_yes == 0) { $result_committee=1; }
if ($result_committee_no == 0) { $result_committee=2; }
if ($result_owner==2) { $result_committee=2; }

$residing =trim($r[7]);
$result_residing_yes = strcasecmp($residing, 'yes');
$result_residing_no = strcasecmp($residing, 'no');
if ($result_residing_yes == 0) { $result_residing=1; }
if ($result_residing_no == 0) { $result_residing=2; }	

$u=$this->autoincrement('user','user_id');
$log_i=$this->autoincrement('login','login_id');
$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;	
$de_user_id=$this->encode($u,'housingmatters');
$random=$de_user_id.'/'.$random;
$role_id[]=2;
if($result_committee==1)
{
 $role_id[]=1;
}
///////////////// insert user table /////////////////////////////

$this->loadmodel('user');
$this->user->saveAll(array('user_id' => $u, 'user_name' => $username,'email' => $email, 'password' => @$random, 'mobile' => $mobile,  'society_id' => $s_society_id, 'role_id' => $role_id,'tenant' => $result_owner, 'wing' => $wing_id_d[$ii], 'flat' => $flat_id_d[$ii],'residing' => $result_residing,'default_role_id'=>2,'profile_pic'=>"blank.jpg",'date' => $date, 'time' => $time,'sex'=>'','signup_random'=>$random,'deactive'=>0,'login_id'=>$log_i,'s_default'=>1));

///////////////////////// end //////////////////////////////////////

////////////////////  insert login table  ///////////////////

$this->loadmodel('login');
$this->login->saveAll(array('login_id'=>$log_i,'user_name'=>$email,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

////////////////////////////////////////////////////////////////// 


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $username,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b>
<a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br/>
www.housingmatters.co.in
</div >
</div>";

$from_name="HousingMatters";
$reply="support@housingmatters.in";
$to=$email;
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Welcome to ".$society_name." portal ";
$this->send_email($to,$from,$from_name,$subject,@$message_web,$reply);



///////////////  Insert code ledger Sub Accounts //////////////////////

 $this->loadmodel('ledger_sub_account');
 $j=$this->autoincrement('ledger_sub_account','auto_id');
 $this->ledger_sub_account->saveAll(array('auto_id'=>$j,'ledger_id'=>34,'name'=>$username,'society_id' => $s_society_id,'user_id'=>$u,'deactive'=>0));

/////////////  End code ledger sub accounts //////////////////////////




////////////////Notification email user all checked code  //////////////////////////
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
  $auto_id = (int)$data['email']['auto_id'];
  $this->loadmodel('notification_email');
  $lo=$this->autoincrement('notification_email','notification_id');
  $this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $u,'chk_status'=>0));
}

//////////////// End all checked code   //////////////////////////




unset($role_id);
}

$this->set('sucess','Csv Imported successfully.');
}

}
}



function society_name($d_society_id)
{
$this->loadmodel('society');
$conditions=array("society_id"=>$d_society_id);
return $this->society->find('all',array('conditions'=>$conditions));
} 

function cron_email() 
{
$this->layout='blank';

$this->loadmodel('email_request');
$conditions=array("flag"=>0);
$result_cron=$this->email_request->find('all',array('conditions'=>$conditions,'limit'=>3));
foreach($result_cron as $data4)
{
echo $to=$data4['email_request']['to'];
echo $from=$data4['email_request']['from'];
echo $from_name=$data4['email_request']['from_name'];
echo $subject=$data4['email_request']['subject'];
$message_web=$data4['email_request']['message_web'];
echo $reply=$data4['email_request']['reply'];

$this->smtpmailer($to, $from, $from_name, $subject, $message_web,$reply);
}
//$this->smtpmailer('abhilashlohar01@gmail.com', 'alerts@housingmatters.in', 'housingmatters', 'testing','hello','alerts@housingmatters.in');

}


function notifications_count() 
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('notification');
$conditions=array('users' =>array('$in' => array($s_user_id)),'seen_users' =>array('$nin' => array($s_user_id)));
$order=array('notification.notification_id'=>'DESC');
$this->set('result_notifications_count',$this->notification->find('count',array('conditions'=>$conditions,'order'=>$order)));
}

function notifications() 
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('notification');
$conditions=array('users' =>array('$in' => array($s_user_id)),'seen_users' =>array('$nin' => array($s_user_id)));
$order=array('notification.notification_id'=>'DESC');
$this->set('result_notification',$this->notification->find('all',array('conditions'=>$conditions,'order'=>$order)));

$this->loadmodel('notification');
$this->notification->updateAll(array('seen_users'=>1),array('user_id'=>$s_user_id));
}

function send_notification($icon,$text,$module_id,$element_id,$url,$by_user,$users) 
{


$s_society_id=$this->Session->read('society_id');

$now=date('Y-m-d');

$notification_id=$this->autoincrement('notification','notification_id');
$this->loadmodel('notification');
$this->notification->saveAll(array('notification_id' => $notification_id,'icon' => $icon,'module_id' => $module_id,'element_id' => $element_id,'text' => $text, 'url' =>$url, 'by_user' =>$by_user, 'users' =>$users, 'society_id' =>$s_society_id, 'date' =>$now));
}


function seen_notification($module_id,$element_id) 
{


$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

	$this->loadmodel('notification');
	$conditions=array("module_id" => $module_id,"element_id" => $element_id);
	$notification_result=$this->notification->find('all', array('conditions' => $conditions));
	
	foreach($notification_result as $notification_result_data)
	{
		$seen_users=@$notification_result_data['notification']['seen_users'];
		
	if(sizeof($seen_users)==0)	{ $seen_users=array(); }
	
	if (!in_array($s_user_id, $seen_users))
	{
	
		if(sizeof($seen_users)==0)
		{
		$seen_users[]=$s_user_id;
		
		}
		else
		{
		$t=$s_user_id;
		array_push($seen_users,$t);
		}
		
		
		$this->notification->updateAll(array('seen_users'=>$seen_users),array('notification.module_id'=>$module_id,'notification.element_id'=>$element_id));
	}
	
	}
	
	
}

function seen_alert($module_id,$element_id) 
{


$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

	$this->loadmodel('alert');
	$conditions=array("module_id" => $module_id,"element_id" => $element_id);
	$alert_result=$this->alert->find('all', array('conditions' => $conditions));
	
	foreach($alert_result as $alert_result_data)
	{
		$seen_users=@$alert_result_data['alert']['seen_users'];
		
	if(sizeof($seen_users)==0)	{ $seen_users=array(); }
	
	if (!in_array($s_user_id, $seen_users))
	{
	
		if(sizeof($seen_users)==0)
		{
		$seen_users[]=$s_user_id;
		
		}
		else
		{
		$t=$s_user_id;
		array_push($seen_users,$t);
		}
		
		
		$this->alert->updateAll(array('seen_users'=>$seen_users),array('alert.module_id'=>$module_id,'alert.element_id'=>$element_id));
	}
	
	}
	
	
}

function recent_activities($icon,$by_user,$text,$url,$users,$module_id) 
{


$s_society_id=$this->Session->read('society_id');

$now=date('Y-m-d');

$activity_id=$this->autoincrement('activity','activity_id');
$this->loadmodel('activity');
$this->activity->saveAll(array('activity_id' => $activity_id,'icon' => $icon,'by_user' =>$by_user,'text' => $text, 'url' =>$url,'users' =>$users,'module_id' =>$module_id ,'society_id' =>$s_society_id, 'date' =>$now));
}

function alerts_count() 
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('alert');
$conditions=array('users' =>array('$in' => array($s_user_id)),'seen_users' =>array('$nin' => array($s_user_id)));
$order=array('alert.alert_id'=>'DESC');
$this->set('result_alerts_count',$this->alert->find('count',array('conditions'=>$conditions,'order'=>$order)));
}

function alerts()
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

date_default_timezone_set('Asia/Kolkata');	
$now=date('d-m-Y');
$now_for_search=date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));


$current_date_for_search = new MongoDate(strtotime($now_for_search)); 

////////////////notice//////////////////////////////////////
$this->loadmodel('notice');
$conditions=array("n_draft_id" => 0, "n_delete_id" => 0,"society_id"=> $s_society_id,'n_expire_date' => array('$gte'=>$current_date_for_search));
$notice_result=$this->notice->find('all',array('conditions'=>$conditions));

foreach($notice_result as $data1)
{
$notice_id=$data1['notice']['notice_id'];
$n_subject=$data1['notice']['n_subject'];

$visible_user_id=$data1['notice']['visible_user_id'];



$n_expire_date=$data1['notice']['n_expire_date'];
$n_expire_date= date('d-m-Y',$n_expire_date->sec);

$datediff = strtotime($n_expire_date) - strtotime($now);

$remening_days=floor($datediff/(60*60*24));

	if($remening_days<=15)
	{
	
	$this->loadmodel('alert');
	$conditions=array("module_id" => 2, "element_id" => $notice_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));
	
		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label label-info" ><i class="icon-bullhorn"></i></span>','Notice - <b>'.$n_subject.'</b> will expire on <b>'.$n_expire_date.'</b>',2,$notice_id,'notice_publish_view?con='.$notice_id,$visible_user_id);
		}
	
	}
}

////////////////notice//////////////////////////////////////


////////////////Events//////////////////////////////////////
$this->loadmodel('event');
$conditions=array("society_id"=> $s_society_id);
$result_event=$this->event->find('all',array('conditions'=>$conditions));

foreach($result_event as $data1)
{
$event_id=$data1['event']['event_id'];
$e_name=$data1['event']['e_name'];

$visible_user_id=$data1['event']['visible_user_id'];


$date_to=$data1['event']['date_to'];
$date_to= date('d-m-Y',$date_to->sec);

$datediff = strtotime($date_to) - strtotime($now);

$remening_days=floor($datediff/(60*60*24));

	if($remening_days<=3 and $remening_days>=0)
	{
	$this->loadmodel('alert');
	$conditions=array("module_id" => 6, "element_id" => $event_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));
	
		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label" style="background-color:#44b6ae;" ><i class="icon-gift"></i></span>','Event - <b>'.$e_name.'</b> will expire on <b>'.$date_to.'</b>',6,$event_id,'event_info?e='.$event_id,$visible_user_id);
		}
	
	}
	if($remening_days<0)
	{
	$this->loadmodel('alert');
	$conditions=array("module_id" => 6, "element_id" => $event_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));
	
		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label" style="background-color:#44b6ae;" ><i class="icon-gift"></i></span>','Event - <b>'.$e_name.'</b> have been expired on <b>'.$date_to.'</b>',6,$event_id,'event_info?e='.$event_id,$visible_user_id);
		}
	
	}

}

////////////////Events//////////////////////////////////////

////////////////Polls//////////////////////////////////////
$this->loadmodel('poll');
$conditions=array("society_id"=> $s_society_id);
$result_poll=$this->poll->find('all',array('conditions'=>$conditions));
foreach($result_poll as $data1)
{
$poll_id=$data1['poll']['poll_id'];
$question=$data1['poll']['question'];

$visible_user_id=$data1['poll']['visible_user_id'];


$close_date=$data1['poll']['close_date'];
$close_date= date('d-m-Y',$close_date->sec);

$datediff = strtotime($close_date) - strtotime($now);

$remening_days=floor($datediff/(60*60*24));

	if(($remening_days<=3 or $remening_days<=7) and ($remening_days>=0))
	{
	$this->loadmodel('alert');
	$conditions=array("module_id" => 7, "element_id" => $poll_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));
	
		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label" style="background-color:#6d1b81;" ><i class="icon-question-sign"></i></span>','Voting for Poll - <b>'.$question.'</b> will close on <b>'.$close_date.'</b>',7,$poll_id,'',$visible_user_id);
		}
	
	}
	if($remening_days<0)
	{
	$this->loadmodel('alert');
	$conditions=array("module_id" => 7, "element_id" => $poll_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));
	
		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label" style="background-color:#6d1b81;" ><i class="icon-question-sign"></i></span>','Voting for Poll - <b>'.$question.'</b> have been closed on <b>'.$close_date.'</b>',7,$poll_id,'',$visible_user_id);
		}
	
	}

}

////////////////Polls//////////////////////////////////////

////////////////profile incompleteness//////////////////////////////////////
$this->loadmodel('user');
$conditions=array("user_id"=> $s_user_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user as $collection)   
{
$c_email = $collection['user']['email'];
$c_mobile = $collection['user']['mobile'];
$c_name = $collection['user']['user_name'];
@$profile_pic = $collection['user']['profile_pic'];
$c_sex = (int)@$collection['user']['sex'];
$c_wing_id = (int)$collection['user']['wing'];
 $c_flat_id = (int)$collection['user']['flat'];
$da_dob=@$collection['user']['dob'];
$per_address=@$collection['user']['per_address'];
$com_address=@$collection['user']['comm_address'];
$hobbies=@$collection['user']['hobbies'];
$private_field=@$collection['user']['private'];

}

$flat = $this->wing_flat($c_wing_id,$c_flat_id);

$ccc=0;
	if(!empty($c_email))
	{
		$ccc++;
	}
	if(!empty($c_mobile))
	{
		$ccc++;
	}
	if(!empty($c_name))
	{
		$ccc++;
	}
	if(!empty($profile_pic))
	{
		$ccc++;
	}
	if(!empty($c_sex))
	{
		$ccc++;
	}
	if(!empty($c_wing_id))
	{
		$ccc++;
	}
	if(!empty($c_flat_id))
	{
		$ccc++;
	}
	if(!empty($da_dob))
	{
		$ccc++;
	}
	if(!empty($per_address))
	{
		$ccc++;
	}
	if(!empty($com_address))
	{
		$ccc++;
	}
	if(!empty($hobbies))
	{
		$ccc++;
	}
$progres=$ccc*100/11;
$progres=round($progres);
$incomplete=100-$progres;

	if($incomplete!=100)
	{
	$this->loadmodel('alert');
	$conditions=array("module_id" => 101, "element_id" => $s_user_id);
	$alert_result_count=$this->alert->find('count',array('conditions'=>$conditions));

		if($alert_result_count==0)
		{
		$this->send_alert('<span class="label label-success"><i class="icon-user"></i></span>','Your Profile is <b>'.$incomplete.'%</b> incomplete',101,$s_user_id,'',array($s_user_id));
		}

	}

////////////////profile incompleteness//////////////////////////////////////


$this->loadmodel('alert');
$conditions=array('users' =>array('$in' => array($s_user_id)),'seen_users' =>array('$nin' => array($s_user_id)));
$order=array('alert.alert_id'=>'DESC');
$this->set('result_alerts',$this->alert->find('all',array('conditions'=>$conditions,'order'=>$order)));
}

function send_alert($icon,$text,$module_id,$element_id,$url,$users)
{
$this->layout='blank';

$s_society_id=$this->Session->read('society_id');

$now=date('Y-m-d');

$alert_id=$this->autoincrement('alert','alert_id');
$this->loadmodel('alert');
$this->alert->saveAll(array('alert_id' => $alert_id,'icon' => $icon,'module_id' => $module_id,'element_id' => $element_id,'text' => $text, 'url' =>$url, 'users' =>$users, 'society_id' =>$s_society_id, 'date' =>$now));
}


function index()
{
$ua=$this->Cookie->read('username');
$pa=$this->Cookie->read('password');
$this->set('bgColor',$ua);
$this->set('txtColor',$pa);
$this->layout='without_session';	
if ($this->request->is('post')) 
{
	
	 $username=htmlentities($this->request->data["username"]);
	 $password=htmlentities($this->request->data["password"]);
	 $rememberme=htmlentities(@$this->request->data["rememberme"]);
		$this->loadmodel('login');
		$conditions =array( '$or' => array( 
		array("user_name" => $username, "password" => $password),
		array("mobile" => $username, "password" => $password),
		));
	 $result_login=$this->login->find('all',array('conditions'=>$conditions));
	 $count=sizeof($result_login);
	 if($count>0)
	 {
		 
			if($rememberme==1)
			{
			$this->Cookie->write('username',$username,3600);
			$this->Cookie->write('password',$password,3600);
			}
			else
			{
			$this->Cookie->delete('username');
			$this->Cookie->delete('password');
			}
		 
		 
		foreach($result_login as $data)
		{
			
			 //$da_society_id=(int)$data['login']['society_id'];
			 $login_id=(int)$data['login']['login_id'];
		}
		 
			 $this->loadmodel('user');
			 $conditions1=array('s_default'=>1,'login_id'=>$login_id,'deactive'=>0);
			 $result_user=$this->user->find('all',array('conditions'=>$conditions1));
			 $n=sizeof($result_user);
			 if($n>0)
			 {
				foreach($result_user as $data)
				{
				
				$user_id=$data['user']['user_id'];
				$society_id=$data['user']['society_id'];
				$user_name=$data['user']['user_name'];
				$wing=$data['user']['wing'];
				$tenant=$data['user']['tenant'];
				$role_id=$data['user']['default_role_id'];
				$profile=@$data['user']['profile_status'];
				}
				 
					$this->loadmodel('user');
					$conditions5=array('signup_random'=>$password);
					$res_n=$this->user->find('all',array('conditions'=>$conditions5));
					$result_no=sizeof($res_n);
					if($result_no>0)
					{
						
					$de_user_id=$this->encode($user_id,'housingmatters');
					$random=$de_user_id.'/'.$password;
					$this->response->header('Location', $this->webroot.'hms/set_new_password?q='.$random.' ');
					}
					else
					{
						
					date_default_timezone_set('Asia/kolkata');
					$date=date("d-m-Y");
					$time=date('h:i:a',time());
					$this->loadmodel('log');
					$i=$this->autoincrement('log','log_id');
					$this->log->save(array('log_id'=>$i,'user_id'=>$user_id,'society_id'=>$society_id,'date'=>$date,'time'=>$time,'status'=>0));
				    $this->Session->write('user_id', $user_id);
					$this->Session->write('login_id', $login_id);
					$this->Session->write('role_id', $role_id);
					$this->Session->write('society_id', $society_id);
					$this->Session->write('user_name', $user_name);
					$this->Session->write('wing', $wing);
					$this->Session->write('tenant', $tenant);
					$this->redirect(array('action' => 'dashboard'));
				 
				 	}
				
				 
				 
			 }
			 else
			 {
				$this->set('wrong', 'Username and Password are Incorrect'); 
			 }
	 }
	 else
	 {
		$this->loadmodel('login');
		$condition3=array('user_name'=>$username);
		$result_login1=$this->login->find('all',array('conditions'=>$condition3));
		$res_n=sizeof($result_login1);
		if($res_n>0)
		{
			$this->set('wrong', 'Password is Incorrect');
		}
		else
		{
			$this->loadmodel('login');
		    $condition4=array('password'=>$password);
		    $result_login2=$this->login->find('all',array('conditions'=>$condition4));
		    $res_n1=sizeof($result_login2);
				if($res_n1>0)
				{
				$this->set('wrong', 'Username is Incorrect');
				
				}
				else
				{
					$this->set('wrong', 'Username and Password are Incorrect');
					
				}
			
		}
	 }
	 
	
}
	
}
function login_user_id($login_id)
{
	$this->loadmodel('user');
	$conditions=array('login_id'=>$login_id);
	return $this->user->find('all',array('conditions'=>$conditions));
	
}


function index23() 
{	
$ua=$this->Cookie->read('username');
$pa=$this->Cookie->read('password');
$this->set('bgColor',$ua);
$this->set('txtColor',$pa);
$this->layout='without_session';
if ($this->request->is('post')) 
{
 $username=htmlentities($this->request->data["username"]);
 $password=htmlentities($this->request->data["password"]);
$rememberme=htmlentities(@$this->request->data["rememberme"]);
$this->loadmodel('user');
$conditions =array( '$or' => array( 
array("email" => $username, "password" => $password,'deactive'=>0),
array("mobile" => $username, "password" => $password,'deactive'=>0),
));
$result = $this->user->find('all',array('conditions'=>$conditions));
$n = sizeof($result);

if($n>0)
{
if($rememberme==1)
{
$this->Cookie->write('username',$username,3600);
$this->Cookie->write('password',$password,3600);
}
else
{
$this->Cookie->delete('username');
$this->Cookie->delete('password');
}
foreach($result as $data)
{
	
$user_id=$data['user']['user_id'];
$society_id=$data['user']['society_id'];
$user_name=$data['user']['user_name'];
$wing=$data['user']['wing'];
$tenant=$data['user']['tenant'];
$role_id=$data['user']['default_role_id'];
$profile=@$data['user']['profile_status'];
}

$this->loadmodel('user');
$conditions5=array('signup_random'=>$password);
$res_n=$this->user->find('all',array('conditions'=>$conditions5));
$result_no=sizeof($res_n);
if($result_no>0)
{
	$de_user_id=$this->encode($user_id,'housingmatters');
	$random=$de_user_id.'/'.$password;
	$this->response->header('Location', $this->webroot.'hms/set_new_password?q='.$random.' ');
}
else
{
$this->loadmodel('user');
if($profile==2)
{
$conditions =array( '$or' => array( 
array("email" => $username, "password" => $password,"profile_status" =>2),
array("mobile" => $username, "password" => $password,"profile_status" =>2),
));
}
else
{
$conditions =array( '$or' => array( 
array("email" => $username, "password" => $password,"profile_status" =>1),
array("mobile" => $username, "password" => $password,"profile_status" =>1),
));	
}
$result = $this->user->find('all',array('conditions'=>$conditions));
$pro = sizeof($result);
if($pro==0)
{
$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>1),array('user_id'=>$user_id));
}
else
{
$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>2),array('user_id'=>$user_id));
}

date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$this->loadmodel('log');
$i=$this->autoincrement('log','log_id');
$this->log->save(array('log_id'=>$i,'user_id'=>$user_id,'society_id'=>$society_id,'date'=>$date,'time'=>$time,'status'=>0));
$this->Session->write('user_id', $user_id);
$this->Session->write('role_id', $role_id);
//$this->Session->write('master_society', $multi_society_id);
$this->Session->write('society_id', $society_id);
$this->Session->write('user_name', $user_name);
$this->Session->write('wing', $wing);
$this->Session->write('tenant', $tenant);
$this->redirect(array('action' => 'dashboard'));
}
}
else
{
$this->loadmodel('user');
$conditions =array( '$or' => array( 
array("email" => $username, 'deactive'=>0),
array("mobile" => $username, 'deactive'=>0),
));
$result1 = $this->user->find('all',array('conditions'=>$conditions));
$n1 = sizeof($result1);
if($n1>0)
{ 
$this->set('wrong', 'Password is Incorrect');
}
else
{
$this->loadmodel('user');
$conditions=array("password" => $password,'deactive'=>0);
$result2 = $this->user->find('all',array('conditions'=>$conditions));
$n2 = sizeof($result2);
if($n2>0)
{ 
$this->set('wrong', 'Username is Incorrect');
}
else
{
$this->set('wrong', 'Username and Password are Incorrect');
}
}
}
}
}

function sign_up()
{
$this->layout='without_session';
App::import('', 'sendsms.php');
if ($this->request->is('POST')) 
{
//$code=mt_rand(10000,99999);
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$name=htmlentities($this->request->data['name']);
$email=htmlentities($this->request->data['email']);
$mobile=htmlentities($this->request->data['mobile']);
$i=$this->autoincrement('user_temp','user_temp_id');
$this->loadmodel('user_temp');
$this->user_temp->save(array('user_temp_id' => $i, 'user_name' => $name,'email' => $email, 'password' => '', 'mobile' => $mobile,  'society_id' => 0, 'role' => 0, 'committee' => 2 , 'tenant' => 2, 'wing' => 0, 'flat' => 0,'residing' => 1,'complete_signup' => 0 , 'reply_mail' => "", 'date' => $date, 'time' => $time,'reject' =>0 ));
//$sms='Hello!+Please+enter+your+code+'.$code.'+on+the+signup+screen+to+continue+your+HousingMatters+registration+process.';
//$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms.'');
$this->response->header('Location', 'sign_up_next?user='.$i.' ');


}
}

function sign_up_otp()
{
$this->layout='without_session';
App::import('', 'sendsms.php');
$user=(int)$this->request->query['user'];
$mob=$this->request->query['mobile'];
$try=@$this->request->query['try'];
$this->set('us',$user);
$this->set('mo',$mob);
if (isset($this->request->data['login'])) 
{
$captch=(int)htmlentities($this->request->data['name']);
$this->loadmodel('user_temp');
$conditions=array("captch_otp" => $captch);
$result2 = $this->user_temp->find('all',array('conditions'=>$conditions));
$n2 = sizeof($result2);
if($n2>0)
{
?><script>
location="sign_up_next?user=<?php echo $user; ?>";
</script> <?php 	
}
else
{
$this->set('error', '<label style="color:red;">you have entered incorrect code</label>');
}
}


if(!empty($try))
{
$this->loadmodel('user_temp');
$conditions=array('user_temp_id'=>$user);
$result_user_temp=$this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{
$mobile=@$collection['user_temp']["mobile"];
}

$code=mt_rand(10000,99999);
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array('captch_otp'=>$code),array('user_temp_id'=>$user));
$sms='Hello!+Please+enter+your+code+'.$code.'+on+the+signup+screen+to+continue+your+HousingMatters+registration+process.';
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms.'');

}


}



function sign_up_next()
{
$this->layout='without_session';	
$user=$this->request->query['user'];
$this->set('user', $user);
}



function resident_signup()
{
$this->layout='without_session';
$user=(int)$this->request->query['user'];
$this->set('user_id', $user);
$this->loadmodel('society');
$this->set('result', $this->society->find('all'));
if($this->request->is('post')) 
{

$society_id=(int)$this->request->data['society'];
$tenant=(int)$this->request->data['tenant'];
if($tenant==1)
{
$committe=(int)$this->request->data['committe'];
}
else
{
$committe=2;
}
$wing=(int)$this->request->data['wing'];
$flat=(int)$this->request->data['flat'];
$residing=(int)$this->request->data['residing'];
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array("society_id" => $society_id,"committee" => $committe, 
'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing,"role"=>2,"complete_signup"=>1,'multiple_society'=>0),array("user_temp.user_temp_id" => $user));


$this->loadmodel('user_temp');
$conditions=array("user_temp_id" => $user);
$result_user=$this->user_temp->find('all',array('conditions'=>$conditions));

foreach($result_user as $data1)
{
$user_name_post=$data1['user_temp']['user_name'];
}
$wing_flat=$this->wing_flat($wing,$flat);

$this->loadmodel('user');
$conditions=array("society_id" => $society_id,'role_id' =>array('$in' => array(3)));
$result_user_admin=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user_admin as $data2)
{
$admin_user_id[]=$data2['user']['user_id'];
}

$this->send_alert('<span class="label label-success"><i class="icon-user"></i></span>','New sign up by '.$user_name_post.' '.$wing_flat.' is pending for action in Resident Approve in Admin tab.','resident_approve',$admin_user_id);

$this->send_notification('<span class="label label-success" ><i class="icon-user"></i></span>','New User <b>'.$user_name_post.' '.$wing_flat.'</b> awaiting your approval/action',100,$user,'resident_approve',0,$admin_user_id);
////////////////////////////////// mail functionality //////////////////////////////////////////////////////////////////////
$this->loadmodel('society');
$conditions=array("society_id" => $society_id);
$result_society = $this->society->find('all',array('conditions'=>$conditions));
foreach ($result_society as $collection) 
{
$user_id=$collection['society']['user_id'];
}
$this->loadmodel('user');
$conditions=array("user_id" => $user_id);
$result_user = $this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user as $collection) 
{
$email=$collection['user']['email'];
$mobile=$collection['user']['mobile'];
}
$this->loadmodel('user_temp');
$conditions=array("user_temp_id" => $user);
$result_user_temp = $this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{
$mobile1=$collection['user_temp']['mobile'];
$user_name1=$collection['user_temp']['user_name'];
}
$this->loadmodel('wing');
$conditions=array("wing_id" => $wing);
$result_wing = $this->wing->find('all',array('conditions'=>$conditions));
foreach ($result_wing as $collection) 
{
$wing_name=$collection['wing']['wing_name'];
}
$this->loadmodel('wing');
$conditions=array("wing_id" => $wing);
$result_wing = $this->wing->find('all',array('conditions'=>$conditions));
foreach ($result_wing as $collection) 
{
$wing_name=$collection['wing']['wing_name'];
}
$this->loadmodel('flat');
$conditions=array("flat_id" => $flat);
$result_flat = $this->flat->find('all',array('conditions'=>$conditions));
foreach ($result_flat as $collection) 
{
$flat_name=$collection['flat']['flat_name'];
}
$wing_flat = $wing_name.'-'.$flat_name;

$z=1;
if($z==1)
{
if($tenant==1)
{
$owner="Yes";
}
else
{
$owner="No";
}

if($tenant==1)
{
$tenant="Owner";
}
else
{
$tenant="Tenant";
}

$sms='Hello!+New+User+request+:+'.$user_name1.'+'.$wing_flat.'+'.$tenant.'+Please+log+into+HousingMatters+for+further+action.';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
$to=$email;


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>

</br><p>Dear Administrator,</p>
One new user request in your society has been received for your approval.<br/><br/>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Flat</td>
<td>Name</td>
<td>Mobile</td>
<td>Owner</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$wing_flat</td>
<td>$user_name1</td>
<td>$mobile1</td>
<td>$owner</td>
</tr>
</table>
<div>
<p>Kindly log into <a href='http://www.housingmatters.co.in'> HousingMatters portal </a> and review </p>
<p>the request under 'Admin -> Resident Approve' for further action at your end.</p><br/>
For any assistance, please email us on support@housingmatters.in<br/><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";

$from_name="HousingMatters";
$reply="support@housingmatters.in";
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="New User Request for approval";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$z++;			
}


if($z==2)
{

$this->loadmodel('user_temp');
$conditions=array("user_temp_id" => $user);
$result_user_temp = $this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{
$email1=$collection['user_temp']['email'];
$user_name=$collection['user_temp']['user_name'];
$mobile1=$collection['user_temp']['mobile'];
}
$to=$email1;
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $user_name,</p>
We have received your registration request.<br/>
<p>You will be notified by email once your request has been successfully</p>
approved by your society administrator.<br/>
<p>For any assistance, please email us on support@housingmatters.in</p><br/><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >

</div>";

$from_name="HousingMatters";
$reply="support@housingmatters.in";

$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Registration Request";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		


	
$this->response->header('Location', 'r_ack');



}
}





function r_ack()
{
$this->layout='without_session';	
}




function society_signup()
{

App::import('', 'sendsms.php');
App::import('phpmailer', 'mail.php');
$this->layout='without_session';	
$user=(int)$this->request->query['user'];
$this->set('user_id', $user);
if($this->request->is('post')) 
{
$society_name=htmlentities($this->request->data['society_name']);
$pin_code=htmlentities($this->request->data['pin_code']);
$association=htmlentities($this->request->data['association']);
$no_flat=htmlentities($this->request->data['no_flat']);
$i=$this->autoincrement('society','society_id');

$this->loadmodel('society');
$this->society->save(array('society_id' => $i, 'society_name' => $society_name, 
'association_formed' => $association, 'user_id' => $user,"aprvl_status"=>0,"pin_code"=>$pin_code,"flats"=>$no_flat));
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array("society_id" => $i,"role" => 3,"complete_signup"=>1),array("user_temp.user_temp_id" => $user));

//////////////////////mail functionality//////////////////////////////////////////////////////////////////////////////////////////


$z=1;
if($z==1)
{

$this->loadmodel('user');
$conditions=array("society_id" => 0);
$result2 = $this->user->find('all',array('conditions'=>$conditions));
foreach ($result2 as $collection) 
{
$mobile=$collection['user']['mobile'];
}
////////////////////////////// Sms functionality ////////////////////////////////////////////////////////////	

$sms='New Request for Society registration into HousingMatters. Kindly approve the request.';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');

////////////////////////////////////////// ////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////// ////		
$to="admin@housingmatters.in";
$reply="support@housingmatters.in";


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/>New Request for Society registration into HousingMatters. Kindly approve the request.<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >

</div>";

$from_name="HousingMatters";

$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
$sub=$collection['email']['subject'];
}

$subject='New Society  Set up in HousingMatters:   ' . $society_name . '';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$z++;
}

if($z==2)
{
$this->loadmodel('user_temp');
$conditions=array("user_temp_id" => $user);
$result_user_temp = $this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{
$email=$collection['user_temp']['email'];
$mobile1=$collection['user_temp']['mobile'];
}
$to=$email;


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/>We have receive your application<br/>
We will review and get back to you in 24 hours. <br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >

</div>";

$from_name="HousingMatters";
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result1_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result1_email as $collection) 
{
$from=$collection['email']['from'];
$sub=$collection['email']['subject'];
}
$reply="support@housingmatters.in";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
$this->response->header('Location', 'r_ack');

}
}


function resident_signup_ajax()
{
$this->layout='blank';	
$so_id=(int)$this->request->query['con1'];
$this->loadmodel('wing');
$conditions=array("society_id" => $so_id);
$result = $this->wing->find('all',array('conditions'=>$conditions));
$this->set('result3',$result);
}


function resident_signup_wing_flat_ajax()
{
$this->layout='blank';	
$wing_id=(int)$this->request->query['con2'];
$this->loadmodel('flat');
$conditions=array("wing_id" => $wing_id);
$result = $this->flat->find('all',array('conditions'=>$conditions));
$this->set('result3',$result);
}

function signup_emilexits()
{
$this->layout='blank';
$email=$this->request->query['email'];
$this->loadmodel('user_temp');
$conditions=array("email" => $email,'reject'=>0);
$result3 = $this->user_temp->find('all',array('conditions'=>$conditions));
$n3 = sizeof($result3);
$this->loadmodel('user');
$conditions=array("email" => $email);
$result4 = $this->user->find('all',array('conditions'=>$conditions));
$n4 = sizeof($result4);
$e=$n3+$n4;
if ($e > 0) {
echo "false";
} else {
echo "true";
}
}

function signup_mobileexit()
{
$this->layout='blank';
$mobile=$this->request->query['mobile'];
$this->loadmodel('user_temp');
$conditions=array("mobile" => $mobile,'reject'=>0);
$result3 = $this->user_temp->find('all',array('conditions'=>$conditions));
$n3 = sizeof($result3);
$this->loadmodel('user');
$conditions=array("mobile" => $mobile);
$result4 = $this->user->find('all',array('conditions'=>$conditions));
$n4 = sizeof($result4);
$e=$n3+$n4;
if ($e > 0) {
echo "false";
} else {
echo "true";
}
}


function forget() 
{
$this->layout='without_session';
if ($this->request->is('POST')) 
{

$to=$this->request->data['email'];
$this->loadmodel('user');
$conditions=array("email" => $to);
$result3 = $this->user->find('all',array('conditions'=>$conditions));
foreach($result3 as $collection)
{
$username=$collection['user']['user_name'];
}
$n = sizeof($result3);
if($n>0)
{ 
$random=mt_rand(10000,99999);
$this->loadmodel('user');
$this->user->updateAll(array('password'=>$random),array('user.email'=>$to));
$from_name=$username;
$subject="Password";



$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/><br/>username = $to<br/>
Code = $random <br/><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >

</div>";




$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$reply=$from;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$this->response->header('Location', 'verification?con='.$to.' ');
}
else
{ 
$this->set('wrong','This Email is not exist');

}

}

}



function verification() 
{
$this->layout='without_session';
$emil=$this->request->query['con'];

if ($this->request->is('POST')) 
{
$verification=(int)$this->request->data['email'];
$this->loadmodel('user');
$conditions=array('email'=> $emil,"password"=>$verification);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
$n= sizeof($result_user);
if($n>0)
{ 
?>
<SCRIPT LANGUAGE='JavaScript'>
window.location.href='change_password?con=<?php echo $emil;?>';
</SCRIPT>
<?php

}
else
{
$this->set('wrong','This verification is not exist');
}

}

}




function change_password() 
{
$this->layout='without_session';
$emil=$this->request->query['con'];
if ($this->request->is('POST')) 
{
$pass=$this->request->data['pass'];
$this->loadmodel('user');
$conditions=array('email'=> $emil);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
$n= sizeof($result_user);
if($n>0)
{ 
foreach ($result_user as $collection) 
{
$user_id=$collection['user']["user_id"];
$society_id=$collection['user']["society_id"];
$user_name=$collection['user']["user_name"];
$role_id=$collection['user']["default_role_id"];
}
$this->Session->write('user_id', $user_id);
$this->Session->write('role_id', $role_id);
$this->Session->write('society_id', $society_id);
$this->Session->write('user_name', $user_name);
$this->loadmodel('user');
$this->user->updateAll(array('password'=>$pass),array('user.email'=>$emil));
$this->redirect(array('action' => 'dashboard'));
}

}
}


function dashboard2() 
{
Configure::version();

if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
}


function dashboard() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}

$this->ath();
$r=$this->request->query('try');
$s_user_id=$this->Session->read('user_id');
$s_society_id=$this->Session->read('society_id');

$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$this->set('role_id',$role_id);
$wing=$this->Session->read('wing');


$current_date = new MongoDate(strtotime(date("Y-m-d")));


if(!empty($r))
{
$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>2),array('user_id'=>$s_user_id));
$this->redirect(array('action' => 'dashboard'));
}
$this->loadmodel('user');
$conditions=array("user_id" => $s_user_id);
$this->set('result_user',$this->user->find('all',array('conditions'=>$conditions))); 

//////////////recent activity/////////////////
$this->loadmodel('activity');
$conditions=array("module_id" => 1,"society_id" => $s_society_id);
$this->set('result_activity',$this->activity->find('all',array('conditions'=>$conditions)));
//////////////recent activity///////////////// 


//////////////Help-desk  last 3 tickets///////////////// 
$this->loadmodel('help_desk');
if($role_id==3) { 
$conditions=array("society_id" => $s_society_id);
}

if($role_id!=3) { 
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
}

$order=array('help_desk.ticket_id'=> 'DESC');
$result_help_desk=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order,'limit' =>3));
$this->set('result_help_desk',$result_help_desk);
//////////////Help-desk  last 3 tickets///////////////// 

//////////////discussion  last 3 topic///////////////// 
$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_discussion_topics',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order,'limit' =>3)));
//////////////discussion  last 3 topic///////////////// 


//////////////event  last 3///////////////// 
$this->loadmodel('event');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)));
$order=array('event.event_id'=>'DESC');
$this->set('result_event_last',$this->event->find('all', array('conditions' => $conditions,'order' => $order,'limit' =>3)));
//////////////event  last 3 topic///////////////// 


//////////////pie chart help_desk///////////////// 
$this->loadmodel('help_desk');
$conditions=array("society_id" => $s_society_id);
$result_help_desk_report1=$this->help_desk->find('all',array('conditions'=>$conditions));
$this->set('result_help_desk_report1',$result_help_desk_report1);
//////////////pie chart help_desk///////////////// 



//////////////notice///////////////// 
$this->loadmodel('notice');
$result_notice_visible_last=array();
if($role_id==3) { 
$conditions=array("n_draft_id" => 0, "n_delete_id" => 0,"society_id"=> $s_society_id);
$order=array('notice_id'=>'DESC');
}

if($role_id!=3) { 
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'visible' =>1,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>2,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>3,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>4,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>5,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date))
));
}


$order=array('notice.notice_id'=>'DESC');
$result_notice_visible_last_q=$this->notice->find('all', array('conditions' => $conditions,'order' => $order,'limit' =>3));
$current_date=date("d-m-Y");


$result_notice_visible_last=array();
foreach($result_notice_visible_last_q as $data)
{
$n_expire_date=$data['notice']['n_expire_date'];
$n_expire_date= date('d-m-Y', $n_expire_date->sec);


if(strtotime($n_expire_date) >= strtotime($current_date))
{
$result_notice_visible_last[]=$data;

}


}



$this->set('result_notice_visible_last',$result_notice_visible_last);


//////////////notice///////////////// 


//////////////polls  last 3///////////////// 
$this->loadmodel('poll');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)),"approved" => 1,"deleted" => 0);
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll_last',$this->poll->find('all', array('conditions' => $conditions,'order' => $order,'limit' =>3)));

//////////////polls  last 3///////////////// 

//////////////documents  last 3///////////////// 
$this->loadmodel('resource');

if($role_id==3) { 
$conditions=array('society_id'=>$s_society_id);
}

if($role_id!=3) { 
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'visible' =>1),
array('society_id' =>$s_society_id,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'visible' =>4,'sub_visible' =>$tenant),
array('society_id' =>$s_society_id,'visible' =>5,'sub_visible' =>$tenant)
));
}

$order=array('resource.resource_id'=>'DESC');
$result_resource_last=$this->resource->find('all',array('conditions'=>$conditions,'order' => $order,'limit' =>3));
$this->set('result_resource_last',$result_resource_last);
//////////////documents  last 3///////////////// 
}



function dashboard_old() 
{
if ($this->request->isAjax()){
        $this->layout = 'blank';
        $this->view = 'view_ajax'; //Other view that doesn't needs layout, only if necessary 
		}else{
		$this->layout='session';
		}
   
	

$this->ath();
$r=$this->request->query('try');
$s_user_id=$this->Session->read('user_id');
$s_society_id=$this->Session->read('society_id');

if(!empty($r))
{
$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>2),array('user_id'=>$s_user_id));
$this->redirect(array('action' => 'dashboard'));
}
$this->loadmodel('user');
$conditions=array("user_id" => $s_user_id);
$this->set('result_user',$this->user->find('all',array('conditions'=>$conditions))); 

//--------notice view------------//
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');

$current_date = new MongoDate(strtotime(date("Y-m-d")));

$this->loadmodel('notice');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'visible' =>1,'sub_visible' =>array('$in' => array($tenant)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>2,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>3,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date))
));
$order=array('notice.notice_id'=>'DESC');
$this->set('result_notice_visible',$this->notice->find('all', array('conditions' => $conditions,'order' => $order,'limit'=>5)));
//--------notice view end------------//



//--------notice view------------//
$this->loadmodel('event');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)));
$order=array('event.event_id'=>'DESC');
$this->set('result_event',$this->event->find('all', array('conditions' => $conditions,'order' => $order)));
//--------notice view end------------//

//--------notice view------------//
$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_discussion_last',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>5)));
//--------notice view end------------//


//help_desk//
$this->loadmodel('help_desk');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order,'limit' =>5));
$this->set('result_help_desk',$result);
//help_desk//

//polls//
$this->loadmodel('poll');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)));
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order,'limit' =>5)));
//polls//

//resource//
$this->loadmodel('resource');
$conditions=array("resource_delete"=>0,"society_id"=>$s_society_id);
$result=$this->resource->find('all',array('conditions'=>$conditions,'limit' =>5));
$this->set('result_resource',$result);
//resource//

//event//
$this->loadmodel('event');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)));
$order=array('event.event_id'=>'DESC');
$this->set('result_event',$this->event->find('all', array('conditions' => $conditions,'order' => $order,'limit' =>5)));
//event//
}

//////////////////// Start notice Board ///////////////////////////////
function notice_category_name($category_id)
{

$this->loadmodel('master_notice_category');
$conditions=array("category_id" => $category_id);
$result_category=$this->master_notice_category->find('all',array('conditions'=>$conditions));
foreach ($result_category as $collection) 
{
return $notice_category_name=$collection['master_notice_category']['category_name'];
}

}

function notice_approval()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
    $this->check_user_privilages();	
	$s_society_id=$this->Session->read('society_id'); 
	$this->loadmodel('notice');
	$conditions=array("n_draft_id" => 4, "n_delete_id" => 0,"society_id"=> $s_society_id);
	$order=array('notice_id'=>'DESC');
	$res_notice=$this->notice->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_notice_publish',$res_notice);	

}

function notice_approval_ajax()
{
	$this->layout='blank';
	$id=(int)$this->request->query('con');
	$s_society_id=$this->Session->read('society_id'); 
	$this->loadmodel('notice');
	$conditions=array('notice_id'=>$id);
	$result=$this->notice->find('all',array('conditions'=>$conditions));
	foreach($result as $data)
	{
		
		$category=$data['notice']['n_category_id'];
		$sub=$data['notice']['n_subject'];
		$date=$data['notice']['n_date'];
		$visible=(int)$data['notice']['visible'];
		$sub_visible=$data['notice']['sub_visible'];
		$message=$data['notice']['n_message'];
		
		if($visible==1)
		{	
		$send='All Users'; 
		$visible=1;
		$sub_visible[]=0;
		/////////////////////////////////////////// All user ////////////////////////////
		$result_user= $this->all_user_deactive();
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}
		/////////////////////////////////////////// All user ////////////////////////////
		}
		if($visible==4)
		{	
		$send='All Owners';
		$visible=4;
		$sub_visible=1;
		/////////////////////////////////////////// All Owners ////////////////////////////

		$result_user=$this->all_owner_deactive();
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}
		/////////////////////////////////////////// All Owners ////////////////////////////
		}

		if($visible==5)
		{
		 $send='All Tenants'; 
		$visible=5;
		$sub_visible=2;
		/////////////////////////////////////////// All Tenant ////////////////////////////

		$result_user=$this->all_tenant_deactive();
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}
		/////////////////////////////////////////// All Tenant ////////////////////////////
		}


		if($visible==2)
		{
			$send='Roll Wise'; 			
		$visible=2;
		foreach ($sub_visible as $collection) 
		{
		$role_id=$collection;
		/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////

		$result_user=$this->all_role_wise_deactive($role_id);
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}

		//////////////////////////////// end mail ////////////////////////////////////////////////////////	

		}
		$da_to=array_unique($da_to);
		}



		if($visible==3)
		{
		$send='Wing Wise'; 
		$visible=3;
		foreach ($sub_visible as $collection) 
		{
		$wing_id=$collection;

		/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////

		$result_user=$this->all_wing_wise_deactive($wing_id);
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}

		//////////////////////////////// end mail ////////////////////////////////////////////////////////	

		}

		}
		
		 $da_to11=array_unique($da_user_id);

			$this->loadmodel('email');
			$conditions=array('auto_id'=>2);
			$result_email=$this->email->find('all',array('conditions'=>$conditions));
			foreach ($result_email as $collection) 
			{
			 $from=$collection['email']['from'];
			}
			$from_name="HousingMatters";
			$reply="donotreply@housingmatters.in";
			$category_name=$this->notice_category_name($category);
			$society_result=$this->society_name($s_society_id);
			foreach($society_result as $data)
			{
			  $society_name=$data['society']['society_name'];
			}
			for($k=0;$k<sizeof(@$da_to);$k++)
			{
			$to = @$da_to[$k];
			$d_user_id = @$da_user_id[$k];	 
			$user_name = @$da_user_name[$k];	

			$message_web="<div>
			<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
			<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
			<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
			
			</br><p>Dear  $user_name,</p>
			<p>A new notice has been posted on your society Notice Board.</p>
			<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
			<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
			<td>Date</td>
			<td>Subject</td>
			<td>Category</td>
			<td>Sent to</td>
			</tr>
			<tr class='tr_content' style=background-color:#E9E9E9;'>
			<td>$date</td>
			<td>$sub</td>
			<td>$category_name</td>
			<td>$send</td>
			</tr>
			</table>
			<div>
			<p style='font-size:16px;'> <strong>Notice Description:</strong></p>
			<p style='font-size:15px;'>$message</p><br/><br/>
			<center><p>To view / respond
			<a href='http://123.63.2.150:8080".$this->webroot."hms'><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
			<br/>
			<p>For any software related queries, please contact <span style='color:#00A0E3;'> support@housingmatters.in </span></p>
			www.housingmatters.co.in
			</div>
			</div>";
			$this->loadmodel('notification_email');
			$conditions7=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
			$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
			$n=sizeof($result5);
			if($n>0)
			{
			@$subject.= ''. $society_name . '' .' New Notice '.'     '.''.$sub.'';
			$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
			$subject="";
			}	
			}
			
		$this->loadmodel('notice');
		$this->notice->updateAll(array('visible_user_id' => $da_to11,'n_draft_id'=>0),array('notice_id'=>$id));
		
		
	}
	
	
}

function notice_approval_view()
{
	
		if($this->RequestHandler->isAjax()){
		$this->layout='blank';
		}else{
		$this->layout='session';
		}
		$this->ath();
		$n_id=(int)$this->request->query['con'];
		
		$this->set('n_id',$n_id);
		$this->loadmodel('notice');
		$conditions=array("notice_id" => $n_id);
		$this->set('result_view', $this->notice->find('all',array('conditions'=>$conditions)));
		$this->loadmodel('notice_board_reply');
		$conditions=array("notice_id" => $n_id);
		$this->set('result_reply',$this->notice_board_reply->find('all',array('conditions'=>$conditions)));

}

function create_notice() 
{
	
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);
$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);
$s_user_id=$this->Session->read('user_id');
$date=date('d-m-Y');
$time = date(' h:i a', time());

$result=$this->society_name($s_society_id);
	foreach($result as $data)
	{
	@$notice=$data['society']['notice'];

	}
	if($notice==1)
	{
		if(isset($this->request->data['publish'])) 
		{
			$category_id=(int)$this->request->data['notice_category'];
			$text=htmlentities($this->request->data['notice_subject']);
			$sub = wordwrap($text, 25, " ", true);
			$expire_date = new MongoDate(strtotime(date("Y-m-d", strtotime($this->request->data['notice_expire_date']))));
			 $message=$this->request->data['description'];
			$visible=(int)$this->request->data['visible'];
			$att=$this->request->form['file']['name'];
					if($visible==1)
					{	
					$visible=1;
					$sub_visible[]=0;
					}
					
					if($visible==4)
					{	
					$visible=4;
					$sub_visible=1;
					}
					
					if($visible==5)
					{
					$visible=5;
					$sub_visible=2;
					}
					
					if($visible==2)
					{	
						$visible=2;
						foreach ($role_result as $collection) 
						{
							$role_id=$collection["role"]["role_id"];

							$role_id=@(int)$this->request->data['role'.$role_id];
							if(!empty($role_id))
							{
							$sub_visible[]=(int)$role_id;
							}
						}
					}
					if($visible==3)
					{	
					 $visible=3;
						foreach ($wing_result as $collection) 
						{
							$wing_id=(int)$collection["wing"]["wing_id"];

							$wing=@(int)$this->request->data['wing'.$wing_id];
							if(!empty($wing))
							{
								$sub_visible[]=(int)$wing;
							}
						}
					}
					
						$target = "notice_file/";
						$target=@$target.basename( @$this->request->form['file']['name']);
						$ok=1;
						move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
						$notice_id=$this->autoincrement('notice','notice_id');
						$this->loadmodel('notice');
						$this->notice->save(array('notice_id' => $notice_id, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'n_category_id' => $category_id ,'n_subject' => $sub , 'n_expire_date' => $expire_date, 'n_attachment' => $att , 'n_message' => $message,'n_date' => $date, 'n_time' => $time, 'n_delete_id' => 0,'n_draft_id' => 4,'visible' => $visible,'sub_visible' => $sub_visible));

					?>
                
				<!----alert-------------->
				<div class="modal-backdrop fade in"></div>
				<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
				<div class="modal-body" style="font-size:16px;">
				Notices are sent for approval
				</div> 
				<div class="modal-footer">
				<a href="create_notice" class="btn green">OK</a>
				</div>
				</div>
				<!----alert-------------->
				
                <?php		
			
					
					
			
		}
	}	
	else
	{
		
		
if(isset($this->request->data['publish'])) 
{
	
$category_id=(int)$this->request->data['notice_category'];
$text=htmlentities($this->request->data['notice_subject']);
$sub = wordwrap($text, 25, " ", true);
$expire_date = new MongoDate(strtotime(date("Y-m-d", strtotime($this->request->data['notice_expire_date']))));
 $message=$this->request->data['description'];
$message= ($message);
$visible=(int)$this->request->data['visible'];
$att=$this->request->form['file']['name'];

if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_user_deactive();

foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}


/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible=1;
/////////////////////////////////////////// All Owners ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible=2;
/////////////////////////////////////////// All Tenant ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;

/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	


}
}
$da_to=array_unique($da_to);
}

if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=(int)$collection["wing"]["wing_id"];

$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;
/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}
}

}

$da_to11=array_unique($da_user_id);

$target = "notice_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
$notice_id=$this->autoincrement('notice','notice_id');
$this->loadmodel('notice');
$this->notice->save(array('notice_id' => $notice_id, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'n_category_id' => $category_id ,'n_subject' => $sub , 'n_expire_date' => $expire_date, 'n_attachment' => $att , 'n_message' => $message,'n_date' => $date, 'n_time' => $time, 'n_delete_id' => 0,'n_draft_id' => 0,'visible' => $visible,'sub_visible' => $sub_visible,'visible_user_id' => $da_to11 ));

////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$this->loadmodel('email');
$conditions=array('auto_id'=>2);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$category_name=$this->notice_category_name($category_id);
$society_result=$this->society_name($s_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}

if($visible==1)
{
$send='All Users'; 
}
if($visible==2)
{
$send='Roll Wise'; 
}
if($visible==3)
{
$send='Wing Wise'; 
}

if($visible==4)
{
$send='All Owners'; 
}

if($visible==5)
{
$send='All Tenants'; 
}

for($k=0;$k<sizeof(@$da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];	

 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>

</br><p>Dear  $user_name,</p>
<p>A new notice has been posted on your society Notice Board.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Date</td>
<td>Subject</td>
<td>Category</td>
<td>Sent to</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$date</td>
<td>$sub</td>
<td>$category_name</td>
<td>$send</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Notice Description:</strong></p>
<p style='font-size:15px;'>$message</p><br/><br/>
<center><p>To view / respond
<a href='http://123.63.2.150:8080".$this->webroot."hms'><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
<br/>
<p>For any software related queries, please contact <span style='color:#00A0E3;'> support@housingmatters.in </span></p>
www.housingmatters.co.in
</div>
</div>";
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . '' .' New Notice '.'     '.''.$sub.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}


$da_user_id[]=$d_user_id;
$this->send_notification('<span class="label label-info" ><i class="icon-bullhorn"></i></span>','New Notice published - <b>'.$sub.'</b> by',2,$notice_id,'notice_board_view?con='.$notice_id,$s_user_id,$da_user_id);

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Notice has been Published.
</div> 
<div class="modal-footer">
<a href="notice_publish" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	

}

		
		
	}



if (isset($this->request->data['draft'])) 
{
$category_id=$this->request->data['notice_category'];
$text=htmlentities($this->request->data['notice_subject']);
$sub = wordwrap($text, 25, " ", true);
$expire_date = new MongoDate(strtotime(date("Y-m-d", strtotime($this->request->data['notice_expire_date']))));
$message=$this->request->data['Editor3'];
$visible=(int)$this->request->data['visible'];
$att=$this->request->form['file']['name'];
if($visible==1)
{
$visible=1;
$sub_visible[]=0;
}
if($visible==4)
{
$visible=1;
$sub_visible=1;
}
if($visible==5)
{
$visible=1;
$sub_visible=2;
}
if($visible==2)
{	
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;
}
}

}



if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=$collection["wing"]["wing_id"];
$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;
}
}
}
$target = "notice_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
$notice_id=$this->autoincrement('notice','notice_id');	
$this->loadmodel('notice');
$this->notice->save(array('notice_id' => $notice_id, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'n_category_id' => $category_id ,'n_subject' => $sub , 'n_expire_date' => $expire_date, 'n_attachment' => $att , 'n_message' => $message,'n_date' => $date, 'n_time' => $time, 'n_delete_id' => 0,'n_draft_id' => 1,'visible' => $visible,'sub_visible' => $sub_visible ));

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Notice has been saved in Draft Folder.
</div> 
<div class="modal-footer">
<a href="notice_draft" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
}


}





function notice_board() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');
$q=$this->request->query('con');
$cat=$this->decode($q,'housingmatters');
$this->set('blue_cat',$cat);
$current_date = new MongoDate(strtotime(date("Y-m-d")));

$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
$this->loadmodel('notice');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'visible' =>1,'n_draft_id' =>0,'n_delete_id' =>0,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>2,'n_draft_id' =>0,'n_delete_id' =>0,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>3,'n_draft_id' =>0,'n_delete_id' =>0,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>4,'n_draft_id' =>0,'n_delete_id' =>0,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'visible' =>5,'n_draft_id' =>0,'n_delete_id' =>0,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date))
));

$order=array('notice.notice_id'=>'DESC');

$this->set('result_notice_visible',$this->notice->find('all', array('conditions' => $conditions,'order' => $order)));


if(!empty($cat))
{
	
$this->set('red_cat',$cat);
$this->loadmodel('notice');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>1,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>2,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>3,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>4,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>5,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date))
));
$order=array('notice.notice_id'=>'DESC');
$this->set('result_notice_visible',$this->notice->find('all', array('conditions' => $conditions,'order' => $order)));
}
}

function notice_from_visible_to_notice($notice_id) 
{
$this->loadmodel('notice');
$conditions=array("notice_id" => $notice_id,"n_draft_id" => 0);
return $result=$this->notice->find('all',array('conditions'=>$conditions));
}

function notice_board_view() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();

$n_id=(int)$this->request->query['con'];
$this->set('n_id',$n_id);

$this->seen_notification(2,$n_id);

$this->loadmodel('notice');
$conditions=array("notice_id" => $n_id);
$this->set('result_view', $this->notice->find('all',array('conditions'=>$conditions)));

$this->loadmodel('notice_board_reply');
$conditions=array("notice_id" => $n_id);
$this->set('result_reply',$this->notice_board_reply->find('all',array('conditions'=>$conditions)));
}

function notice_publish_view() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();


$n_id=(int)$this->request->query['con'];
$this->set('n_id',$n_id);

$this->loadmodel('notice');
$conditions=array("notice_id" => $n_id);
$this->set('result_view', $this->notice->find('all',array('conditions'=>$conditions)));

$this->loadmodel('notice_board_reply');
$conditions=array("notice_id" => $n_id);
$this->set('result_reply',$this->notice_board_reply->find('all',array('conditions'=>$conditions)));
}

function notice_save_reply()
{
		$this->layout='blank';
		$reply=htmlentities($this->request->query('reply'));
		$rep=explode(' ',$reply);
		$r=$this->content_moderation_society($rep);
		
		

$n_id=(int)$this->request->query('n_id');

$s_user_id=$this->Session->read('user_id');


$date=date("d-m-Y");
$time=date('h:i:a',time());

$t=$this->autoincrement('notice_board_reply','reply_id');
$this->loadmodel('notice_board_reply');
$multipleRowData = Array( Array("reply_id" => $t, "reply" => $reply , "notice_id" => $n_id, "date" => $date,"time" => $time,"class" => "outt","user_id"=>$s_user_id));

if($r==0)
		{
			echo'<span style="color:red;font-size:14px;">You have enter wrong word.</span>';
		}
		else
		{
			$this->notice_board_reply->saveAll($multipleRowData); 
			
		}
$this->loadmodel('notice_board_reply');
$conditions=array("notice_id" => $n_id);
$order=array('notice_board_reply.notice_id'=>'ASC');
$this->set('result_reply',$this->notice_board_reply->find('all',array('conditions'=>$conditions,'order'=>$order)));

}

function notice_draft() 
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$q=$this->request->query('con');
$cat=$this->decode($q,'housingmatters');
$this->set('blue_cat',$cat);
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
$this->loadmodel('notice');
$conditions=array("n_draft_id" => 1, "n_delete_id" => 0,"society_id"=> $s_society_id);
$this->set('result_notice_draft',$this->notice->find('all',array('conditions'=>$conditions)));
	if(!empty($cat))
	{
		$this->set('red_cat',$cat);	
		$this->loadmodel('notice');
		$conditions1=array('n_draft_id'=>1,'n_delete_id'=>0,'society_id'=>$s_society_id,'n_category_id'=>$cat);
		$result=$this->notice->find('all',array('conditions'=>$conditions1));
		$this->set('result_notice_draft',$result);
	}
	
}


function notice_edit() 
{
$this->layout='session';
$this->ath();
$s_society_id=$this->Session->read('society_id');
$notice_id=(int)$this->request->query['n'];
$this->set('notice_id',$notice_id);
$this->loadmodel('notice');
$conditions=array("notice_id" => $notice_id);
$result5= $this->notice->find('all',array('conditions'=>$conditions));
$this->set('result_notices',$result5); 
$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);
$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);

foreach($result5 as $data)
{
$visible=$data['notice']['visible'];
$sub_visible=$data['notice']['sub_visible'];
$attachment=$data['notice']['n_attachment'];
$date=$data['notice']['n_date'];

}
if(isset($this->request->data['publish_d'])) 
{
	

	
$category_id=(int)$this->request->data['notice_category'];
$text=htmlentities($this->request->data['notice_subject']);
$sub = wordwrap($text, 25, " ", true);
$expire_date = new MongoDate(strtotime(date("Y-m-d", strtotime($this->request->data['notice_expire_date']))));
$message=$this->request->data['Editor3'];
$notice_att=$this->request->form['file']['name'];

if(empty($notice_att))
{
$notice_att=$attachment;
}
$target = "notice_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
$this->notice->updateAll(array('n_draft_id'=>0,'n_category_id' => $category_id ,'n_subject' => $sub , 'n_expire_date' => $expire_date,'n_attachment'=>$notice_att),array('notice.notice_id'=>$notice_id));

if($visible==1)
{
/////////////////////////////////////////// All User mail functionality conditions //////////////////////////////////////////////////////

//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////		



if($visible==4)
{
/////////////////////////////////////////// All Owner mail functionality conditions //////////////////////////////////////////////////////

//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////		

}
if($visible==5)
{
/////////////////////////////////////////// All Tenant mail functionality conditions //////////////////////////////////////////////////////

//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////		
}
if($visible==2)
{
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];
if(in_array($role_id,$sub_visible))
{

/////////////////////////////////////////// All role  functionality  conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}

}
$da_to=array_unique($da_to);

}
if($visible==3)
{
foreach ($wing_result as $collection) 
{
$wing_id=$collection["wing"]["wing_id"];
if(in_array($wing_id,$sub_visible))
{

/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////	
}
}
}

////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$this->loadmodel('email');
$conditions=array('auto_id'=>2);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$category_name=$this->notice_category_name($category_id);
$society_result=$this->society_name($s_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}

if($visible==1)
{
$send='All Users'; 
}
if($visible==2)
{
$send='Roll Wise'; 
}
if($visible==3)
{
$send='Wing Wise'; 
}

if($visible==4)
{
$send='All Owners'; 
}

if($visible==5)
{
$send='All Tenants'; 
}
for($k=0;$k<sizeof(@$da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];	
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $user_name,</p>
<p>A new notice has been posted on your society Notice Board.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Date</td>
<td>Subject</td>
<td>Category</td>
<td>Sent to</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$date</td>
<td>$sub</td>
<td>$category_name</td>
<td>$send</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Notice Description:</strong></p>
<p style='font-size:15px;'>$message</p><br/><br/>
<center><p>To view / respond
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
<p>For any software related queries, please contact <span style='color:#00A0E3;'> support@housingmatters.in </span></p>
www.housingmatters.co.in
</div>
</div>"; 
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . '' .' New Notice '.'     '.''.$sub.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
}
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Notice has been Published.
</div> 
<div class="modal-footer">
<a href="notice_publish" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
}


}


function notice_visible_role_check($role_id,$notice_id) 
{
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('notice_visible');
$conditions=array("notice_id" => $notice_id,"visible" => 2,"sub_visible" => $role_id);
return $this->notice_visible->find('count',array('conditions'=>$conditions));
}

function notice_visible_wing_check($wing_id,$notice_id) 
{
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('notice_visible');
$conditions=array("notice_id" => $notice_id,"visible" => 3,"sub_visible" => $wing_id);
return $this->notice_visible->find('count',array('conditions'=>$conditions));
}


function notice_archive()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
//$this->layout='session';
$this->ath();
$this->check_user_privilages();	
$s_society_id=$this->Session->read('society_id');
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');
$q=$this->request->query('con');
$cat=$this->decode($q,'housingmatters');
$this->set('blue_cat',$cat);
$current_date = new MongoDate(strtotime(date("Y-m-d")));
$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
$this->loadmodel('notice');
$conditions=array("n_draft_id" => 2, "n_delete_id" => 0,"society_id"=> $s_society_id);
$order=array('notice_id'=>'DESC');
$this->set('result_notice_publish',$this->notice->find('all',array('conditions'=>$conditions,'order'=>$order)));	
if(!empty($cat))
{
	$this->set('red_cat',$cat);	
	$conditions=array("n_draft_id" => 2, "n_delete_id" => 0,"society_id"=> $s_society_id,'n_category_id'=>(int)$cat);
	$order=array('notice.notice_id'=>'DESC');
	$this->set('result_notice_publish',$this->notice->find('all',array('conditions'=>$conditions,'order'=>$order)));
}
}


function notice_move_archive()
{
	$this->layout='blank';	
	$notice_id=(int)$this->request->query('con');
	$this->loadmodel('notice');
	$this->notice->updateAll(array('n_draft_id'=>2),array('notice_id'=>$notice_id));
	$this->response->header('location','notice_archive');
	
}


function notice_publish() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();
$q=$this->request->query('con');
$cat=$this->decode($q,'housingmatters');
$this->set('blue_cat',$cat);
$s_society_id=$this->Session->read('society_id');
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$this->set('role_id',$role_id);
$wing=$this->Session->read('wing');
$current_date = new MongoDate(strtotime(date("Y-m-d")));
$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));
if($role_id==3)
{
$this->loadmodel('notice');
$conditions=array("n_draft_id" => 0, "n_delete_id" => 0,"society_id"=> $s_society_id);
$order=array('notice_id'=>'DESC');
$res_notice=$this->notice->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('result_notice_publish',$res_notice);
$current_date=date("d-m-Y");

foreach($res_notice as $data)
{
$notice_id=$data['notice']['notice_id'];
$n_expire_date=$data['notice']['n_expire_date'];
$n_expire_date= date('d-m-Y', $n_expire_date->sec);
	if(strtotime($n_expire_date) < strtotime($current_date))
	{
		$this->loadmodel('notice');
		$this->notice->updateAll(array('n_draft_id'=>2),array('notice_id'=>$notice_id));
	}
	
}



if(!empty($cat))
{
$this->set('red_cat',$cat);	
$conditions=array("n_draft_id" => 0, "n_delete_id" => 0,"society_id"=> $s_society_id,'n_category_id'=>(int)$cat);
$order=array('notice.notice_id'=>'DESC');
$this->set('result_notice_publish',$this->notice->find('all',array('conditions'=>$conditions,'order'=>$order)));
}
}
else
{
$this->loadmodel('notice');
$conditions =array( '$or' => array( 
array('n_draft_id' => 0,'society_id' =>$s_society_id,'visible' =>1,'n_expire_date' => array('$gte'=>$current_date)),
array('n_draft_id' => 0,'society_id' =>$s_society_id,'visible' =>2,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('n_draft_id' => 0,'society_id' =>$s_society_id,'visible' =>3,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date)),
array('n_draft_id' => 0,'society_id' =>$s_society_id,'visible' =>4,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date)),
array('n_draft_id' => 0,'society_id' =>$s_society_id,'visible' =>5,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date))
));
$order=array('notice.notice_id'=>'DESC');
$this->set('result_notice_publish',$this->notice->find('all', array('conditions' => $conditions,'order' => $order)));
if(!empty($cat))
{
$this->set('red_cat',$cat);
$this->loadmodel('notice');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>1,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>2,'sub_visible' =>array('$in' => array($role_id)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>3,'sub_visible' =>array('$in' => array($wing)),'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>4,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date)),
array('society_id' =>$s_society_id,'n_category_id'=>(int)$cat,'visible' =>5,'sub_visible' =>$tenant,'n_expire_date' => array('$gte'=>$current_date))
));
$order=array('notice.notice_id'=>'DESC');
$this->set('result_notice_publish',$this->notice->find('all', array('conditions' => $conditions,'order' => $order)));
}

}

}
///////////////////////////////// End Notice board ////////////////////////////////

/////////////////////////////Start of Event//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
function event_add()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$date = new MongoDate(strtotime(date('Y-m-d')));



$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);

$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);

$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id,'deactive'=>0);
$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions))); 

if (isset($this->request->data['create_event'])) 
{
$e_name=htmlentities($this->request->data['e_name']);
$day_type=(int)$this->request->data['day_type'];

if($day_type==2)
{
$date_from=$this->request->data['date_from'];
$date_from=date("Y-m-d",strtotime($date_from));
$date_from = new MongoDate(strtotime($date_from));
$date_to=$this->request->data['date_to'];
$date_to=date("Y-m-d",strtotime($date_to));
$date_to = new MongoDate(strtotime($date_to));
}

if($day_type==1)
{
$date_from=$this->request->data['date_single'];
$date_from=date("Y-m-d",strtotime($date_from));
$date_from = new MongoDate(strtotime($date_from));

$date_to=$this->request->data['date_single'];
$date_to=date("Y-m-d",strtotime($date_to));
$date_to = new MongoDate(strtotime($date_to));

}

$location=htmlentities($this->request->data['location']);
$description=htmlentities($this->request->data['description']);
$visible=(int)$this->request->data['visible'];

if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible[]=0;
/////////////////////////////////////////// All Owners ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible[]=0;
/////////////////////////////////////////// All Tenant ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;

/////////////////////////////////////////// Role Wise ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// Role Wise ////////////////////////////
}
}

}

if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=$collection["wing"]["wing_id"];

$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;

/////////////////////////////////////////// Wing Wise ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing);
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// Wing Wise ////////////////////////////
}
}

}

if($visible==6)
{	
$visible=6;
$sub_visible[]=0;
/////////////////////////////////////////// Manually ////////////////////////////
$visible_user_id1=$this->request->data['multi'];
foreach($visible_user_id1 as $data_user)
{
$visible_user_id[]=(int)$data_user;
}
/////////////////////////////////////////// Manually ////////////////////////////
}
$visible_user_id = array_unique($visible_user_id);

ksort($visible_user_id);
foreach($visible_user_id as $x=>$x_value)
{
$visible_user_id_new[]=$x_value;
}

$event_id=$this->autoincrement('event','event_id');
$this->loadmodel('event');
$this->event->saveAll(array('event_id' => $event_id,'e_name' => $e_name, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'date_from' => $date_from , 'date_to' => $date_to, 'day_type' => $day_type, 'location' => $location,'description' => $description,'visible' => $visible,'sub_visible' => $sub_visible,'visible_user_id' => $visible_user_id_new,'date' => $date));


$this->send_notification('<span class="label" style="background-color:#44b6ae;"><i class="icon-gift"></i></span>','New Event <b>'.$e_name.'</b> submitted by',6,$event_id,'event_info?e='.$event_id,$s_user_id,$visible_user_id_new);
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Event has been created.
</div> 
<div class="modal-footer">
<a href="events" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php


}

}

function calendar()
{
$this->layout='blank';

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$m_y=@$this->request->query('m_y');
if(empty($m_y))
{ 
$m_y = date('m-Y');
}


$m_y_ex=explode('-',$m_y);
$m=$m_y_ex[0];
$y=$m_y_ex[1];

/////////////////
$start='1-'.$m_y;
$start = date("Y-m-d", strtotime($start));
$start = new MongoDate(strtotime($start));

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);

$end=$days_in_month.'-'.$m_y;
$end = date("Y-m-d", strtotime($end));
$end = new MongoDate(strtotime($end));

$event_info=array();
$this->loadmodel('event');
$conditions=array('date_from' => array('$gte'=>$start,'$lte'=>$end));
$result_event_info=$this->event->find('all',array('conditions'=>$conditions));
foreach($result_event_info as $data)
{
$date_from = date("Y-m-d", $data['event']['date_from']->sec);
$date_to = date("Y-m-d", $data['event']['date_to']->sec);
$event_info[]=array($data['event']['event_id'],$data['event']['e_name'],$date_from,$date_to);
}
if(sizeof($event_info)==0) { $event_info=array(); }
$this->set('event_info',$event_info);
/////////////////



$dateObj   = DateTime::createFromFormat('!m', $m);
$month_name = $dateObj->format('F'); // March

$this->set('month',$m);
$this->set('month_name',$month_name);
$this->set('year',$y);

}

function check_event($e_date)
{
$this->layout='blank';

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('event');
$conditions=array("date_from" =>$e_date);
$result_event_info=$this->event->find('all');

return $result_event_info;
}

function events()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel('event');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)));
$order=array('event.event_id'=>'DESC');
$this->set('result_event',$this->event->find('all', array('conditions' => $conditions,'order' => $order)));
}

function events_calendar()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();

}

function event_info()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
$this->ath();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

$e_id=(int)$this->request->query('e');
$this->set('e_id',$e_id);

$this->seen_notification(6,$e_id);

if (isset($this->request->data['sub_update'])) 
{
$title=htmlentities($this->request->data['title']);
$title=wordwrap($title, 25, " ", true);
$title_cat=$this->request->data['title_cat'];
$title_des=htmlentities($this->request->data['description']);

$title_des=nl2br(wordwrap($title_des, 25, " ", true));



$this->loadmodel('event');
$conditions=array("event_id" => $e_id);
$event_result_update=$this->event->find('all', array('conditions' => $conditions));
$this->set('event_result_update',$event_result_update);
$update=@$event_result_update[0]['event']['updates'];
$e_name=@$event_result_update[0]['event']['e_name'];
$visible_user_id=@$event_result_update[0]['event']['visible_user_id'];

if(sizeof($update)==0)
{
$update[]=array("title"=>$title,"color"=>$title_cat,"des"=>$title_des);
}
else
{
$t=array("title"=>$title,"color"=>$title_cat,"des"=>$title_des);
array_push($update,$t);
}

//$updates=array("title"=>$title,"color"=>$title_cat,"des"=>$title_des);
$this->event->updateAll(array('updates'=>$update),array('event.event_id'=>$e_id));


$this->send_notification('<span class="label" style="background-color:#d43f3a;"><i class="icon-tags"></i></span>','Updates for Event <b>'.$e_name.'</b> submitted by',6,$e_id,'event_info?e='.$e_id,$s_user_id,$visible_user_id);

}

if (isset($this->request->data['up_photo'])) 
{
$file=$this->request->form['file']['name'];


$file=$this->request->form['file']['name'];
if (!file_exists('event_file/event'.$e_id)) 
{
mkdir('event_file/event'.$e_id);
}
move_uploaded_file(@$this->request->form['file']['tmp_name'], "event_file/event".$e_id."/".$file);

$this->loadmodel('event');
$conditions=array("event_id" => $e_id);
$event_result_update=$this->event->find('all', array('conditions' => $conditions));
$this->set('event_result_update',$event_result_update);
$photo=@$event_result_update[0]['event']['photos'];

if(sizeof($photo)==0)
{
$photo[]=$file;
}
else
{
array_push($photo,$file);
}

$updates=array(array("title"=>"dfdgdf","color"=>"dfdgdf","des"=>"dfdgdf"));
$this->event->updateAll(array('photos'=>$photo),array('event.event_id'=>$e_id));

}

$this->loadmodel('event');
$conditions=array("event_id" => $e_id,"visible_user_id" => array('$in' => array($s_user_id)));
$result_event_detail=$this->event->find('all', array('conditions' => $conditions));
$this->set('result_event_detail',$result_event_detail);



}



function save_rsvp()
{
$this->layout='blank';

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$e=(int)$this->request->query('e');
$type=(int)$this->request->query('type');

	if($type==1)
	{
	$this->loadmodel('event');
	$conditions=array("event_id" => $e);
	$event_result=$this->event->find('all', array('conditions' => $conditions));
	$rsvp=@$event_result[0]['event']['rsvp'];
	if(sizeof($rsvp)==0)	{ $rsvp=array(); }
	
	if (!in_array($s_user_id, $rsvp))
	{
	
		if(sizeof($rsvp)==0)
		{
		$rsvp[]=$s_user_id;
		
		}
		else
		{
		$t=$s_user_id;
		array_push($rsvp,$t);
		}
		
		
		$this->event->updateAll(array('rsvp'=>$rsvp),array('event.event_id'=>$e));
	}
	echo "Thank you";
	}
	
	if($type==2)
	{
	$this->loadmodel('event');
	$conditions=array("event_id" => $e);
	$event_result=$this->event->find('all', array('conditions' => $conditions));
	@$not_in_rsvp=@$event_result[0]['event']['not_in_rsvp'];
	
	if(sizeof($not_in_rsvp)==0)	{ $not_in_rsvp=array(); }
	
	if (!in_array($s_user_id, $not_in_rsvp))
	{
	
		if(sizeof($not_in_rsvp)==0)
		{
		$not_in_rsvp[]=$s_user_id;
		
		}
		else
		{
		$t=$s_user_id;
		array_push($not_in_rsvp,$t);
		}
		
		
		$this->event->updateAll(array('not_in_rsvp'=>$not_in_rsvp),array('event.event_id'=>$e));
	}
	
	echo "Thank you";
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////End of Event//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////start of polls//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
function poll_add()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');




$this->loadmodel('master_notice_category');
$this->set('result1', $this->master_notice_category->find('all'));


$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);

$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);

$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id);
$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions))); 

$result_so=$this->society_name($s_society_id);
	foreach($result_so as $data)
	{
	  @$poll_society=$data['society']['poll'];
	}
	if($poll_society==1)
	{
		
		if (isset($this->request->data['create_poll'])) 
		{
			$question=htmlentities($this->request->data['question']);
			$question=wordwrap($question, 25, " ", true);
			$description=htmlentities($this->request->data['description']);
			$description=wordwrap($description, 25, " ", true);
			$poll_close_date=$this->request->data['poll_close_date'];
			$type=(int)$this->request->data['type'];
			$private=(int)@$this->request->data['private']; 
			if(empty($private)) { $private=0; }
			$choice_text_box=(int)$this->request->data['choice_text_box'];
			for($z=1;$z<=$choice_text_box;$z++)
			{
			$color=$this->rendom_color_new();
			$choice[]=array(htmlentities($this->request->data['choice'.$z]),$color);

			}
			$current_date = date('Y-m-d');
			$current_date = new MongoDate(strtotime($current_date));


			if(empty($poll_close_date)) 
			{ 
			$current_date_add=date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days'));
			$poll_close_date=$current_date_add;

			}
			$poll_close_date = date("Y-m-d", strtotime($poll_close_date));
			$close_date = new MongoDate(strtotime($poll_close_date));
			
			$file=$this->request->form['file']['name'];

			$target = "polls_file/";
			$target=@$target.basename( @$this->request->form['file']['name']);
			$ok=1;
			move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 


			$visible=(int)$this->request->data['visible'];
			
			if($visible==1)
			{	
			$visible=1;
			$sub_visible[]=0;
			}

			if($visible==4)
			{	
			$visible=4;
			$sub_visible=0;
			}

			if($visible==5)
			{
			$visible=5;
			$sub_visible=0;
			}
			if($visible==2)
					{	
						$visible=2;
						foreach ($role_result as $collection) 
						{
							$role_id=$collection["role"]["role_id"];

							$role_id=@(int)$this->request->data['role'.$role_id];
							if(!empty($role_id))
							{
							$sub_visible[]=(int)$role_id;
							}
						}
					}
					
					
					if($visible==3)
					{	
					 $visible=3;
						foreach ($wing_result as $collection) 
						{
							$wing_id=(int)$collection["wing"]["wing_id"];

							$wing=@(int)$this->request->data['wing'.$wing_id];
							if(!empty($wing))
							{
								$sub_visible[]=(int)$wing;
							}
						}
					}
					
					
					$poll_id=$this->autoincrement('poll','poll_id');
					$this->loadmodel('poll');
					$this->poll->saveAll(array('poll_id' => $poll_id,'question' => $question , 'des' => $description, 'type' => $type, 'choice' => $choice,'visible' => $visible,'sub_visible' => $sub_visible,'date' => $current_date,'close_date' => $close_date,'file' => $file,'society_id' => $s_society_id,'user_id' => $s_user_id,"deleted" => 4,"private" => $private));
				
				?>
		<!----alert-------------->
		<div class="modal-backdrop fade in"></div>
		<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-body" style="font-size:16px;">
		Polls are sent for approval.
		</div> 
		<div class="modal-footer">
		<a href="Polls" class="btn green">OK</a>
		</div>
		</div>
		<!----alert-------------->
		<?php

				
				
				
		}
	}
	else
	{
		
		if (isset($this->request->data['create_poll'])) 
		{
			
		$question=htmlentities($this->request->data['question']);
		$question=wordwrap($question, 25, " ", true);
		$description=htmlentities($this->request->data['description']);
		$description=wordwrap($description, 25, " ", true);
		$poll_close_date=$this->request->data['poll_close_date'];
		$type=(int)$this->request->data['type'];
		$private=(int)@$this->request->data['private']; 
		if(empty($private)) { $private=0; }
		$choice_text_box=(int)$this->request->data['choice_text_box'];
		for($z=1;$z<=$choice_text_box;$z++)
		{
		$color=$this->rendom_color_new();
		$choice[]=array(htmlentities($this->request->data['choice'.$z]),$color);

		}
		$current_date = date('Y-m-d');
		$current_date = new MongoDate(strtotime($current_date));


		if(empty($poll_close_date)) 
		{ 
		$current_date_add=date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days'));
		$poll_close_date=$current_date_add;

		}
		$poll_close_date = date("Y-m-d", strtotime($poll_close_date));
		$close_date = new MongoDate(strtotime($poll_close_date));



		$s_message='Your Poll has been created.';

		$file=$this->request->form['file']['name'];

		$target = "polls_file/";
		$target=@$target.basename( @$this->request->form['file']['name']);
		$ok=1;
		move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 


		$visible=(int)$this->request->data['visible'];

		if($visible==1)
		{	
		$visible=1;
		$sub_visible[]=0;
		/////////////////////////////////////////// All user ////////////////////////////
		//$this->loadmodel('user');
		//$conditions=array('society_id'=>$s_society_id);
		//$result_user=$this->user->find('all',array('conditions'=>$conditions));
		$result_user=$this->all_user_deactive();
		foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
		/////////////////////////////////////////// All user ////////////////////////////
		}

		if($visible==4)
		{	
		$visible=4;
		$sub_visible[]=0;
		/////////////////////////////////////////// All Owners ////////////////////////////
		//$this->loadmodel('user');
		//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
		//$result_user=$this->user->find('all',array('conditions'=>$conditions));
		$result_user=$this->all_owner_deactive();
		foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
		/////////////////////////////////////////// All Owners ////////////////////////////
		}

		if($visible==5)
		{
		$visible=5;
		$sub_visible[]=0;
		/////////////////////////////////////////// All Tenant ////////////////////////////
		//$this->loadmodel('user');
		//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
		//$result_user=$this->user->find('all',array('conditions'=>$conditions));
		$result_user=$this->all_tenant_deactive();
		foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
		/////////////////////////////////////////// All Tenant ////////////////////////////
		}


		if($visible==2)
		{
		$visible=2;
		foreach ($role_result as $collection) 
		{
		$role_id=$collection["role"]["role_id"];

		$role_id=@(int)$this->request->data['role'.$role_id];
		if(!empty($role_id))
		{
		$sub_visible[]=(int)$role_id;

		/////////////////////////////////////////// Role Wise ////////////////////////////
		//$this->loadmodel('user');
		//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
		//$result_user=$this->user->find('all',array('conditions'=>$conditions));
		$result_user=$this->all_role_wise_deactive($role_id);
		foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
		/////////////////////////////////////////// Role Wise ////////////////////////////
		}
		}

		}

		if($visible==3)
		{	
		$visible=3;
		foreach ($wing_result as $collection) 
		{
		$wing_id=$collection["wing"]["wing_id"];

		$wing=@(int)$this->request->data['wing'.$wing_id];
		if(!empty($wing))
		{
		$sub_visible[]=(int)$wing;

		/////////////////////////////////////////// Wing Wise ////////////////////////////
		//$this->loadmodel('user');
		//$conditions=array('wing'=>$wing,'society_id'=>$s_society_id);
		//$result_user=$this->user->find('all',array('conditions'=>$conditions));
		$result_user=$this->all_wing_wise_deactive($wing);
		foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
		/////////////////////////////////////////// Wing Wise ////////////////////////////
		}
		}

		}


		$visible_mobile = array_unique($visible_mobile);
		$visible_email = array_unique($visible_email);

		$visible_user_id = array_unique($visible_user_id);

		ksort($visible_user_id);
		foreach($visible_user_id as $x=>$x_value)
		{
		$visible_user_id_new[]=$x_value;
		}






		$poll_id=$this->autoincrement('poll','poll_id');
		$this->loadmodel('poll');
		$this->poll->saveAll(array('poll_id' => $poll_id,'question' => $question , 'des' => $description, 'type' => $type, 'choice' => $choice,'visible' => $visible,'sub_visible' => $sub_visible,'visible_user_id' => $visible_user_id_new,'date' => $current_date,'close_date' => $close_date,'file' => $file,'society_id' => $s_society_id,'user_id' => $s_user_id,"deleted" => 0,"private" => $private));

$this->send_notification('<span class="label" style="background-color:#46b8da;"><i class="icon-question-sign"></i></span>','New Poll <b>'.$question.'</b> started by',7,$poll_id,'Polls',$s_user_id,$visible_user_id_new);



		$this->loadmodel('society');
		$conditions12=array('society_id'=>$s_society_id);
		$result12=$this->society->find('all',array('conditions'=>$conditions12));
		foreach($result12 as $data)
		{
		$s_name=$data['society']['society_name'];
		}
		$message_web="<div>
		<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
		<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
		<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
		</br>
		<p>A new poll has been created on your society poll booth.</p>

		<div style='border:solid 1px #ccc;padding:10px;'>
		<span style='color:#00A0E3;'>$question<span><br/>
		<span style='color:#000;font-size:12px;'>$description<span>
		<hr>";
		$message_web.="<ol Type='A' >";
		foreach($choice as $data)
		{
		$message_web.="<li ><span style='font-size:14px;'>".$data[0]."</span></li>";
		}
		$message_web.="</ol>";
		$message_web.="<center><p>To view / vote
		<a href='http://123.63.2.150:8080".$this->webroot."hms' target='_blank'><button style='width:100px;height:30px;background-color:#00A0E3;color:white;'> Click Here </button></a></p></center>";
		$message_web.="</div>
		<br/>
		<br/>
		www.housingmatters.co.in
		</div >
		</div>";


		$reply="support@housingmatters.in";
		$subject="[".$s_name."]-".$question;
		$from_name="HousingMatters";
		$this->loadmodel('email');
		$conditions=array("auto_id" => 4);
		$result_email = $this->email->find('all',array('conditions'=>$conditions));
		foreach ($result_email as $collection) 
		{
		$from=$collection['email']['from'];
		}

		foreach($visible_email as $to)
		{
		$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
		}
		?>
		<!----alert-------------->
		<div class="modal-backdrop fade in"></div>
		<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-body" style="font-size:16px;">
		<?php echo $s_message; ?>
		</div> 
		<div class="modal-footer">
		<a href="Polls" class="btn green">OK</a>
		</div>
		</div>
		<!----alert-------------->
		<?php


		}
		
	}
	
}



function poll_approve_ajax()
{
	$this->layout="blank";	
	$s_society_id=$this->Session->read('society_id');
	 $poll_id=(int)$this->request->query('p_id');
	 $this->loadmodel('poll');
	$conditions=array('poll_id'=>$poll_id);
	$result=$this->poll->find('all',array('conditions'=>$conditions));
	
	foreach($result as $data)
	{
		
		 $visible=$data['poll']['visible'];
		 $sub_visible=$data['poll']['sub_visible'];
		 $question=$data['poll']['question'];
		 $choice=$data['poll']['choice'];
		 $description=$data['poll']['des'];
	}
	
	
if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
$result_user= $this->all_user_deactive();
foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible=1;
/////////////////////////////////////////// All Owners ////////////////////////////

$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible=2;
/////////////////////////////////////////// All Tenant ////////////////////////////

$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($sub_visible as $collection) 
{
$role_id=$collection;
/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////

$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}
$da_to=array_unique($da_to);
}



if($visible==3)
{	
$visible=3;
foreach ($sub_visible as $collection) 
{
$wing_id=$collection;

/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////

$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
		{
		$visible_user_id[]=$data['user']['user_id'];
		$visible_mobile[]=$data['user']['mobile'];
		$visible_email[]=$data['user']['email'];
		
		}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}

}
	
$visible_user_id_new = array_unique($visible_user_id);	

 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br>
<p>A new poll has been created on your society poll booth.</p>

<div style='border:solid 1px #ccc;padding:10px;'>
<span style='color:#00A0E3;'>$question<span><br/>
<span style='color:#000;font-size:12px;'>$description<span>
<hr>";
$message_web.="<ol Type='A' >";
foreach($choice as $data)
{
$message_web.="<li ><span style='font-size:14px;'>".$data[0]."</span></li>";
}
$message_web.="</ol>";
$message_web.="<center><p>To view / vote
<a href='http://123.63.2.150:8080".$this->webroot."hms' target='_blank'><button style='width:100px;height:30px;background-color:#00A0E3;color:white;'> Click Here </button></a></p></center>";
$message_web.="</div>
<br/>
<br/>
www.housingmatters.co.in
</div >
</div>";

		$result1=$this->society_name($s_society_id);
		foreach($result1 as $data)
		{
			$s_name=$data['society']['society_name'];
			
		}
	  	$reply="support@housingmatters.in";
		 $subject="[".$s_name."]-".$question;
		$from_name="HousingMatters";
		$this->loadmodel('email');
		$conditions=array("auto_id" => 4);
		$result_email = $this->email->find('all',array('conditions'=>$conditions));
		foreach ($result_email as $collection) 
		{
		$from=$collection['email']['from'];
		}

		foreach($visible_email as $to)
		{
			if(!empty($to))
			{	
			  $this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
			}
		}
	
		$this->loadmodel('poll');
		$this->poll->updateAll(array("deleted" => 0,'visible_user_id' => $visible_user_id_new),array('poll_id'=>$poll_id));
		
}



function poll_approve()
{
	$this->layout='session';
	$this->check_user_privilages();
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->set('s_user_id',$s_user_id);
	$current_date=date("Y-m-d");
	$current_date = new MongoDate(strtotime($current_date));
	$this->loadmodel('poll');
	$conditions=array("society_id" => $s_society_id,"deleted" => 4,'close_date' => array('$gt' => $current_date));
	$order=array('poll.poll_id'=>'DESC');
	$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order)));
	
}



function polls()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

$current_date=date("Y-m-d");
$current_date = new MongoDate(strtotime($current_date));

$this->loadmodel('poll');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)),"deleted" => 0,'close_date' => array('$gt' => $current_date));
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order)));
}

function my_polls()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

if (isset($this->request->data['edit_save'])) 
{
$p_id=(int)$this->request->data['poll_id'];
$poll_des=htmlentities($this->request->data['poll_des']);

$this->loadmodel('poll');
$this->poll->updateAll(array('des'=>$poll_des),array('poll.poll_id'=>$p_id));

}

if (isset($this->request->data['delete_save'])) 
{
$p_id=(int)$this->request->data['poll_id_d'];

$this->loadmodel('poll');
$this->poll->updateAll(array('deleted'=>1),array('poll.poll_id'=>$p_id));

}


$this->loadmodel('poll');
$conditions=array("user_id" => $s_user_id,"deleted" => 0);
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order)));
}

function closed_polls()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

$current_date=date("Y-m-d");
$current_date = new MongoDate(strtotime($current_date));

$this->loadmodel('poll');
$conditions=array("society_id" => $s_society_id,"visible_user_id" =>array('$in' => array($s_user_id)),"deleted" => 0,'close_date' => array('$lt' => $current_date));
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order)));
}

function polls_approve()
{
$this->layout='session';
$this->ath();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);


$this->loadmodel('poll');
$conditions=array("society_id" => $s_society_id,"approved" => 0,"deleted" => 0);
$order=array('poll.poll_id'=>'DESC');
$this->set('result_poll',$this->poll->find('all', array('conditions' => $conditions,'order' => $order)));
}

function poll_approve_reject_submit()
{
$this->layout='blank';
$this->ath();

$p_id=(int)$this->request->query('p_id');
$a_r=(int)$this->request->query('a_r');

if($a_r==1)
{	
$this->loadmodel('poll');
$this->poll->updateAll(array('approved'=>1),array('poll.poll_id'=>$p_id));
}

if($a_r==2)
{	
$comm=$this->request->query('comm');

$this->loadmodel('poll');
$this->poll->updateAll(array('approved'=>2,'reject_comm'=>$comm),array('poll.poll_id'=>$p_id));
echo $comm;
}

}

function poll_view()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$p_id=(int)$this->request->query('id');

$this->loadmodel('poll');
$conditions=array("poll_id" => $p_id);
$this->set('result_poll_detail',$this->poll->find('all', array('conditions' => $conditions)));
}

function poll_save_vote()
{
$this->layout='blank';

$type=(int)$this->request->query('type');
$poll_id=(int)$this->request->query('poll_id');
$c_id=$this->request->query('c_id');

$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

if($type==1)
{
$c_id=(int)$c_id;

$this->loadmodel('poll');
$conditions=array("poll_id" => $poll_id);
$poll_vote=$this->poll->find('all', array('conditions' => $conditions));
$this->set('poll_vote',$poll_vote);
$vote=@$poll_vote[0]['poll']['result'];
if(sizeof($vote)==0)
{
$vote[]=array($s_user_id,$c_id);
}
else
{
$t=array($s_user_id,$c_id);
array_push($vote,$t);
}
$this->poll->updateAll(array('result'=>$vote),array('poll.poll_id'=>$poll_id));
}

if($type==2)
{
$choices_id=explode(",",$c_id);

$this->loadmodel('poll');
$conditions=array("poll_id" => $poll_id);
$poll_vote=$this->poll->find('all', array('conditions' => $conditions));
$this->set('poll_vote',$poll_vote);
$vote=@$poll_vote[0]['poll']['result'];

foreach($choices_id as $ch_id)
{
$ch_id=(int)$ch_id;
if(sizeof($vote)==0)
{
$vote[]=array($s_user_id,$ch_id);
}
else
{
$t=array($s_user_id,$ch_id);
array_push($vote,$t);
}
}

$this->poll->updateAll(array('result'=>$vote),array('poll.poll_id'=>$poll_id));
}
}

function poll_result_after_vote()
{
$this->layout='blank';

$type=(int)$this->request->query('type');
$poll_id=(int)$this->request->query('poll_id');
$c_id=(int)$this->request->query('c_id');

$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);

$this->loadmodel('poll');
$conditions=array("poll_id" => $poll_id);
$poll_vote=$this->poll->find('all', array('conditions' => $conditions));
$this->set('poll_vote',$poll_vote);

}



function poll_edit()
{
$this->layout='blank';
$p_id=(int)$this->request->query('p_id');
$edit=(int)$this->request->query('edit');
$this->set('edit',$edit);
if($edit==1)
{
$des=$this->request->query('des');
$c_date=$this->request->query('c_date');

$c_date = date("Y-m-d", strtotime($c_date));
$c_date = new MongoDate(strtotime($c_date));

$this->loadmodel('poll');
$this->poll->updateAll(array('des'=>$des,'close_date'=>$c_date),array('poll.poll_id'=>$p_id));
}

if($edit==0)
{
$this->loadmodel('poll');
$conditions=array("poll_id" => $p_id);
$poll_result=$this->poll->find('all', array('conditions' => $conditions));
$this->set('poll_result',$poll_result);
}

}

function poll_delete()
{
$this->layout='blank';
$p_id=(int)$this->request->query('p_id');
$delete=(int)$this->request->query('delete');
$this->set('delete',$delete);
if($delete==1)
{


$this->loadmodel('poll');
$this->poll->updateAll(array('deleted'=>1),array('poll.poll_id'=>$p_id));
}

if($delete==0)
{
$this->set('poll_id',$p_id);
}

}

///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////End of polls//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
function resident_approve() 
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();
$society_id=(int)$this->Session->read('society_id');
$user_id=(int)$this->Session->read('user_id');
$this->loadmodel('user_temp');
$conditions=array("society_id"=>$society_id,"complete_signup"=>1,"reject"=>0,"role"=>2);
$result=$this->user_temp->find('all',array('conditions'=>$conditions));
$this->set('result_user_temp',$result);
}




function resident_approve_reply()
{
$this->layout='blank';
$subject=htmlentities($this->request->query('con1'));
$message_web=htmlentities($this->request->query('con2'));
$to=htmlentities($this->request->query('con3'));
$user_id=(int)htmlentities($this->request->query('con4'));
$from_name="HousingMatters";
$from="Support@housingmatters.in";
$reply="Support@housingmatters.in";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array('reply_mail'=>$message_web),array('user_temp.user_temp_id'=>$user_id));

}

function resident_approve_reject()
{
$this->layout='blank';
$email=$this->request->query('con');
$user_id=(int)$this->request->query('con1');
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array('reject'=>1),array('user_temp.user_temp_id'=>$user_id));
$this->response->header('Location', 'resident_approve');


}


function resident_approve_mail() 
{
	$this->layout='blank';
	$user_temp_id=(int)$this->request->query('con');


// //////////////fetch data user_temp table  ////////////////////
$this->loadmodel('user_temp');
$conditions=array('user_temp_id'=>$user_temp_id);
$result_user_temp=$this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{ 
$society_id=(int)$collection['user_temp']['society_id'];
$user_name=$collection['user_temp']['user_name'];
$date=$collection['user_temp']['date'];
$time=$collection['user_temp']['time'];
$mobile=$collection['user_temp']['mobile'];
$email=$collection['user_temp']['email'];
$password=$collection['user_temp']['password'];
$wing=(int)$collection['user_temp']['wing'];
$flat=(int)$collection['user_temp']['flat'];
$committee=(int)$collection['user_temp']['committee'];
$tenant=(int)$collection['user_temp']['tenant'];
$residing=(int)$collection['user_temp']['residing'];
 @$login_id=(int)$collection['user_temp']['login_id'];
 @$multiple_society=$collection['user_temp']['multiple_society'];
}
///////////end fetch data ////////////////////

$role_id[]=2;
$default_role_id=2;
if($committee==1)
{
$role_id[]=1;
}



$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;

//////////////// insert data user table //////////////////////////
$this->loadmodel('user');
$i=$this->autoincrement('user','user_id');
$de_user_id=$this->encode($i,'housingmatters');
$random=$de_user_id.'/'.$random;


if($multiple_society==0)
{
	$login_id=$this->autoincrement('login','login_id');
	$s_default=1;
	$this->loadmodel('login');
	$this->login->save(array('login_id'=>$login_id,'user_name'=>$email,'mobile'=>$mobile,'signup_random'=>$random,'password'=>$random));
	
}
if($multiple_society==1)
{
	$s_default=0;
}


$this->user->save(array('user_id' => $i, 'user_name' => $user_name,'email' => $email, 'password' => $password, 'mobile' => $mobile,  'society_id' => $society_id, 'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>$role_id,'default_role_id'=>$default_role_id,'signup_random'=>$random,'deactive'=>0,'login_id'=>$login_id,'profile_status'=>1,'s_default'=>$s_default));





//////////////// end insert code  //////////////////////////

///////////////  Insert code ledger Sub Accounts //////////////////////

$this->loadmodel('ledger_sub_account');
$j=$this->autoincrement('ledger_sub_account','auto_id');
$this->ledger_sub_account->save(array('auto_id'=>$j,'ledger_id'=>34,'name'=>$user_name,'society_id' => $society_id,'user_id'=>$i,'deactive'=>0));

/////////////  End code ledger sub accounts //////////////////////////

///////////////////////////////////////////// approve mail functionality ///////////////////////////////////////
if($multiple_society==0)
{
$to=$email;
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $user_name,</p>
<p>Congratulations! Welcome to HousingMatters...making life simpler for</p>
<p>managing your housing society affairs.</p><br/>
<p>Your registration request has been successfully approved.</p><br/>
<p><a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters</p>
<p>For any assistance, please email us on support@housingmatters.in</p>
<p>alternatively, feel free to get in touch via our online chat support.</p><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$subject="Welcome to HousingMatters";
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////Notification email user all checked code  //////////////////////////

$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
$auto_id = (int)$data['email']['auto_id'];
$this->loadmodel('notification_email');
$lo=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $i,'chk_status'=>0));
}

//////////////// End all checked code   //////////////////////////



//////////////// Remove entry user_temp table  //////////////////////////
$this->loadmodel('user_temp');
$conditions=array('user_temp_id'=>$user_temp_id);
$this->user_temp->deleteAll($conditions);
//$this->user_temp->deleteAll(array('user_temp.user_temp_id' => true), false);

//////////////// End Remove entry user_temp table  //////////////////////////

}


function resident_approve_resend_sms()
{
	
$this->layout='blank';
$user_temp_id=(int)$this->request->query('con');

$s_society_id=(int)$this->Session->read('society_id');
$result_society=$this->society_name($s_society_id);
foreach($result_society as $dd)
{
  $society_name=$dd['society']['society_name'];
}

$s_n='';
$sco_na=$society_name;
$dd=explode(' ',$sco_na);
$first=$dd[0];
$two=$dd[1];
$three=$dd[2];
$s_n.=" $first $two $three ";

$this->loadmodel('user');
$conditions=array('user_id'=>$user_temp_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user as $data)
{
	 $mobile=$data['user']['mobile'];
	 $user_name=$data['user']['user_name'];
	 $login_id=(int)$data['user']['login_id'];
}

$random=(string)mt_rand(1000,9999);
$sms="".$user_name.", Your housing society ".$s_n." has enrolled you in HousingMatters portal. Pls log into www.housingmatters.co.in One Time Password ".$random."";
$sms1=str_replace(" ", '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
$this->loadmodel('user');
$this->user->updateAll(array('password'=>$random,'signup_random'=>$random),array('user_id'=>$user_temp_id));
$this->loadmodel('login');
$this->login->updateAll(array('password'=>$random,'signup_random'=>$random),array('login_id'=>$login_id));
}


function resident_approve_resend_mail() 
{
$this->layout='blank';
$user_temp_id=(int)$this->request->query('con');

$s_society_id=(int)$this->Session->read('society_id');
// //////////////fetch data user_temp table  ////////////////////
$this->loadmodel('user');
$conditions=array('user_id'=>$user_temp_id);
$result_user_temp=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{ 
$society_id=(int)$collection['user']['society_id'];
$user_name=$collection['user']['user_name'];
$mobile=$collection['user']['mobile'];
$email=$collection['user']['email'];
$password=$collection['user']['password'];
$wing=(int)$collection['user']['wing'];
$flat=(int)$collection['user']['flat'];
$tenant=(int)$collection['user']['tenant'];
$residing=(int)$collection['user']['residing'];

}
///////////end fetch data ////////////////////

$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;

//////////////// insert data user table //////////////////////////

$de_user_id=$this->encode($user_temp_id,'housingmatters');
$random=$de_user_id.'/'.$random;


$this->loadmodel('user');
$this->user->updateAll(array('signup_random'=>$random),array('user.user_id'=>$user_temp_id));



//////////////// end insert code  //////////////////////////

$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result12=$this->society->find('all',array('conditions'=>$conditions12));
foreach($result12 as $data)
{
$s_name=$data['society']['society_name'];
}
///////////////////////////////////////////// approve mail functionality ///////////////////////////////////////

$to=$email;
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<p style='color:green;'><strong>Reminder!</strong></p>
<p>Dear  $user_name,</p>
<p>'We at $s_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent &amp; smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $s_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>

<li>log &amp; track complaints</li>
<li>start new discussions</li>
<li>check your maintenance dues</li>
<li>post classifieds</li>
<li>receive important SMS &amp; emails from your committee</li>
<li>and much more in the portal.</li>



<p><b><a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random' target='_blank'><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'>Click here</button></a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>


<p>Regards,</p>
<p>Administrator of $s_name</p>

<p>PS: add <a href='http://www.housingmatters.co.in' target='_blank'>www.housingmatters.co.in</a> in your favorite bookmarks for future use.</p>



</div >
</div>";
$subject="[$s_name]";
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



}



function resend_email()
{
$this->layout='session';

$this->loadmodel('user');
$conditions=array('user.profile_status'=> array('$ne' => 2));
$this->set('result_not_login',$this->user->find('all',array('conditions'=>$conditions)));

}

function set_new_password()
{


$this->layout='without_session';
 $q=$this->request->query['q'];

$q_new=explode('/',$q);
$q_new[0];

$user_id=(int)$this->decode($q_new[0],'housingmatters');
$randm=$q_new[1];

$this->loadmodel('user');
$conditions =array( '$or' => array( 
array('user_id'=> $user_id,'signup_random'=>$q),
array('user_id'=> $user_id,'signup_random'=>$randm),
));
//$conditions=array('user_id'=> $user_id,'signup_random'=>$q);
$result_check=$this->user->find('all',array('conditions'=>$conditions));
$n= sizeof($result_check);

if($n>0)
{ 

}
else
{
echo "Sorry, you have used this link.This link is one time login link.";	
exit;
}

if ($this->request->is('POST')) 
{
$pass=$this->request->data['pass'];

$this->loadmodel('user');
$conditions=array('user_id'=> $user_id); 
$result_user=$this->user->find('all',array('conditions'=>$conditions));
$n= sizeof($result_user);
if($n>0)
{ 
foreach ($result_user as $collection) 
{
$user_id=$collection['user']["user_id"];
$login_id=$collection['user']["login_id"];
$society_id=$collection['user']["society_id"];
$user_name=$collection['user']["user_name"];
$role_id=$collection['user']["default_role_id"];
$profile_status=@$collection['user']["profile_status"];
}

$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>1),array('user.user_id'=>$user_id));
$this->Session->write('user_id', $user_id);
$this->Session->write('login_id', $login_id);
$this->Session->write('role_id', $role_id);
$this->Session->write('society_id', $society_id);
$this->Session->write('user_name', $user_name);
$this->loadmodel('user');
$this->user->updateAll(array('password'=>$pass,'signup_random'=>'','deactive'=>0),array('user.user_id'=>$user_id));
$this->loadmodel('login');
$this->login->updateAll(array('password'=>$pass,'signup_random'=>''),array('login.login_id'=>$login_id));
$this->redirect(array('action' => 'dashboard'));
}

}

}





function verify_email()
{
$var=1;	
$this->layout='without_session';
@$q=$this->request->query['q'];
$q_new=explode('/',$q);
$q_new[0];
$user_id=(int)$this->decode($q_new[0],'housingmatters');
$randm=$q_new[1];
$this->loadmodel('user');
$conditions=array('user_id'=> $user_id); 
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user as $collection) 
{
$email=$collection['user']["email"];
}
$this->set('email',$email);


if (isset($this->request->data['login'])) 
{
$var=2;
$captch=htmlentities($this->request->data['name']);
$this->loadmodel('user');
$conditions=array("user_id" => $user_id,"password" => $captch);
$result2 = $this->user->find('all',array('conditions'=>$conditions));
$n2 = sizeof($result2);
if($n2>0)
{
	$this->response->header('Location', 'set_new_password?q='.$q.' ');
}
else
{
$this->set('error', '<label style="color:red;">you have entered incorrect code</label>');
}
}

$this->loadmodel('user');
$conditions=array('user_id'=> $user_id,'signup_random'=>$q);
$result_check=$this->user->find('all',array('conditions'=>$conditions));
$n= sizeof($result_check);
if($n>0)
{ 
$random_otp=(string)mt_rand(1000,9999);

if($var==1)
{
$this->loadmodel('user');
$this->user->updateAll(array('password'=>$random_otp),array('user.user_id'=>$user_id));
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$to=$email;
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Verification your email";
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<p>Hello! Please enter your code $random_otp  on the signup
screen to continue your HousingMatters
registration process.</p>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$this->smtpmailer($to,$from,$from_name,$subject,$message_web,$reply);
}
}
else
{
echo "Sorry, you have used this link.This link is one time login link.";	
exit;
}

}


function verify_mobile()
{

$var=1;

$this->layout='without_session';
$q=$this->request->query['q'];

$q_new=explode('/',$q);
$q_new[0];

$user_id=(int)$this->decode($q_new[0],'housingmatters');

$randm=$q_new[1];
$this->set('user_id',$user_id);
$this->loadmodel('user');
$conditions=array('user_id'=> $user_id); 
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user as $collection) 
{
$mobile=$collection['user']["mobile"];
}
$this->set('mobb',@$mobile);

if (isset($this->request->data['login'])) 
{
$var=2;
$captch=htmlentities($this->request->data['name']);
$this->loadmodel('user');
$conditions=array("user_id" => $user_id,"password" => $captch);
$result2 = $this->user->find('all',array('conditions'=>$conditions));

$n2 = sizeof($result2);
if($n2>0)
{
	$this->response->header('Location', 'set_new_password?q='.$q.' ');

}
else
{
$this->set('error', '<label style="color:red;">you have entered incorrect code</label>');
}


}

$this->loadmodel('user');
$conditions=array('user_id'=> $user_id,'signup_random'=>$q);
$result_check=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_check as $data9)
{
	$user_name=$data9['user']['user_name'];
	$deactive=$data9['user']['deactive'];
}
$n= sizeof($result_check);
if($n>0)
{ 
$random_otp=(string)mt_rand(1000,9999);

if($deactive==0)
{
$sms='Dear '.$user_name.' Please enter your code '.$random_otp.' on the signup screen to continue your HousingMatters registration process. Thank you';
$sms1=str_replace(' ', '+', $sms);
//@$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
$this->user->updateAll(array('password'=>$random_otp,'deactive'=>2),array('user.user_id'=>$user_id));

}

}
else
{
echo "Sorry, you have used this link.This link is one time login link.";	
exit;
}

}


function verify_mobile_ajax()
{
	
	$this->layout='blank';
	$id=(int)$this->request->query['con'];
	$this->loadmodel('user');
	$result=$this->user->find('all',array('conditions'=>array('user_id'=>$id)));
	foreach($result as $data)
	{
		 $mobile= $data['user']['mobile'];
		 $user= $data['user']['user_name'];
	}
	 $random_otp=(string)mt_rand(1000,9999);
$sms='Dear '.$user.' Please enter your code '.$random_otp.' on the signup screen to continue your HousingMatters registration process. Thank you';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
$this->user->updateAll(array('password'=>$random_otp),array('user.user_id'=>$id));

}

function society_approve()
{

$this->layout='session';
$this->ath();
$this->check_housingmatters_privilages();
$society_id=(int)$this->Session->read('society_id');
$user_id=(int)$this->Session->read('user_id');
$this->loadmodel('user_temp');
$conditions=array("complete_signup"=>1,"reject"=>0,"role"=>3);
$result=$this->user_temp->find('all',array('conditions'=>$conditions));
$this->set('result_user_temp',$result);
}


function new_society_enrollment()
{
$this->layout='session';
$this->ath();
if ($this->request->is('POST')) 
{
 $society_name=htmlentities($this->request->data['society_name']);
 $user_name=htmlentities($this->request->data['user_name']);
 $email=htmlentities($this->request->data['email']);
 $mobile=htmlentities($this->request->data['mobile']);
 $pin_code=htmlentities($this->request->data['pin_code']);
 $association=htmlentities($this->request->data['association']);
 $no_flat=htmlentities($this->request->data['no_flat']);
 $i=$this->autoincrement('user','user_id');
 $society_id=$this->autoincrement('society','society_id');  
 $random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;	
$de_user_id=$this->encode($i,'housingmatters');
$random=$de_user_id.'/'.$random;
$log_i=$this->autoincrement('login','login_id');

//////////////////////////////////////// Insert society table ////////////////////////////////////////

$this->loadmodel('society');
$this->society->save(array('society_id' => $society_id, 'society_name' => $society_name, 
'association_formed' => $association, 'user_id' => $i,"aprvl_status"=>1,"pin_code"=>$pin_code,"flats"=>$no_flat));
 
/////////////////////////////////////// End code /////////////////////////////////////////////////
 
 
//////////////////////////////////////// Insert data user table ///////////////////// ///////////////////////////////////
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$role_id[]=3;
$default_role_id=3;
$this->loadmodel('user');
$this->user->save(array('user_id' => $i, 'user_name' => $user_name,'email' => $email, 'password' =>'', 'mobile' => $mobile,  'society_id' => $society_id, 'tenant' => 2, 'wing' =>0, 'flat' =>0,'residing' => 1, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>$role_id,'default_role_id'=>$default_role_id,'signup_random'=>$random,'deactive'=>0,'login_id'=>$log_i,'s_default'=>1));

//////////////////////////////////// End Code ////////////////////////////////////////////////////////////////////////

////////////////////////////// Mail functionality /////////////////////////////////////////////////////////////////


/////////////////////////////////////// Mail functinality //////////////////////////////////////

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/><br/>Login-Id: $email<br/>
<p> Password: <b>
<a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p> <br/>
Congratulations your registration request has been successfully approved  <br/>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$from_name="HousingMatters";
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
$subject=$collection['email']['subject'];
}
$reply=$from;
$to=$email;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

///////////////////////////////// End Mail functionality //////////////////////////////////////////////////////////

////////////////////////////// Notification email checked code start////////////////////////////////////////////////////////
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
$auto_id = (int)$data['email']['auto_id'];
$this->loadmodel('notification_email');
$lo=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $i,'chk_status'=>0));
}

//////////////////////// /////////////////// End code notification  ////////////////////////////////////////////////////////

///////////////////// login table insert //////////////////////////////////


$this->loadmodel('login');
$this->login->save(array('login_id'=>$log_i,'user_name'=>$email,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

//////////////////// end code login table ///////////////////////////////

//////////////// Role to assign code for Society  //////////////////////////
for($p=1;$p<=3;$p++)
{
if($p==1) { $d="Committee Member"; }
if($p==2) { $d="Resident"; }
if($p==3) { $d="Admin"; }

$this->loadmodel('role');
$k=$this->autoincrement('role','auto_id');
$this->role->saveAll(array("auto_id" => $k, "role_name" => $d, 'role_id'=>$p, "society_id" => $society_id));

}	
//////////////// Role to assign end   //////////////////////////


?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
New society registered successfully.
</div> 
<div class="modal-footer">
<a href="new_society_enrollment" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}
}


function society_approve_mail()
{
$this->layout='blank';
$user_temp_id=(int)htmlentities($this->request->query('con1'));
$this->loadmodel('user_temp');
$conditions=array('user_temp_id'=>$user_temp_id); 
$result_user_temp=$this->user_temp->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{ 
$society_id=(int)$collection['user_temp']['society_id'];
$user_name=$collection['user_temp']['user_name'];
$date=$collection['user_temp']['date'];
$time=$collection['user_temp']['time'];
$mobile=$collection['user_temp']['mobile'];
$email=$collection['user_temp']['email'];
$password=$collection['user_temp']['password'];
$wing=(int)$collection['user_temp']['wing'];
$flat=(int)$collection['user_temp']['flat'];
$committee=(int)$collection['user_temp']['committee'];
$tenant=(int)$collection['user_temp']['tenant'];
$residing=(int)$collection['user_temp']['residing'];
} 


$i=$this->autoincrement('user','user_id'); 
$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;	
$de_user_id=$this->encode($i,'housingmatters');
$random=$de_user_id.'/'.$random;
 

/////////////////////////////////////// Mail functinality //////////////////////////////////////

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/><br/>Login-Id: $email<br/>
<p> Password: <b>
<a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p><br>
Congratulations your registration request has been successfully approved  <br/>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$from_name="HousingMatters";
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
$subject=$collection['email']['subject'];
}
$reply=$from;
$to=$email;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

//////////////////////////////////////// Insert data user table ///////////////////// ///////////////////////////////////

$login_id=$this->autoincrement('login','login_id');

$role_id[]=3;
$default_role_id=3;
$this->loadmodel('user');
$this->user->save(array('user_id' => $i, 'user_name' => $user_name,'email' => $email, 'password' => $password, 'mobile' => $mobile,  'society_id' => $society_id, 'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>$role_id,'default_role_id'=>$default_role_id,'signup_random'=>$random,'deactive'=>0,'login_id'=>$login_id,'s_default'=>1));

//////////////////////// insert login table //////////////////////////////////////


$this->loadmodel('login');
$this->login->save(array('login_id'=>$login_id,'user_name'=>$email,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

////////////////////////// End login code ////////////////////////////////////

//////////////////////////////////////////////////// End insert code  /////////////////////////////////////////////////////////////////////////////////

////////////////////////// update approval status in society //////////////////////////////////////////
$this->loadmodel('society');
$this->society->updateAll(array('aprvl_status'=>1,'user_id'=>$i),array('society.user_id'=>$user_temp_id));

/////////////////////////////////////////////////////end code /////////////////////////////////////////////


////////////////////////////// Notification email checked code start////////////////////////////////////////////////////////
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
$auto_id = (int)$data['email']['auto_id'];
$this->loadmodel('notification_email');
$lo=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $i,'chk_status'=>0));
}

//////////////////////// /////////////////// End code notification  ////////////////////////////////////////////////////////


//////////////// Remove entry user_temp table  //////////////////////////
$this->loadmodel('user_temp');
$conditions=array("user_temp_id" => $user_temp_id);
$this->user_temp->deleteAll($conditions);

//////////////// End Remove entry user_temp table  //////////////////////////


//////////////// Role to assign code for Society  //////////////////////////
for($p=1;$p<=3;$p++)
{
if($p==1) { $d="Committee Member"; }
if($p==2) { $d="Resident"; }
if($p==3) { $d="Admin"; }

$this->loadmodel('role');
$k=$this->autoincrement('role','auto_id');
$this->role->saveAll(array("auto_id" => $k, "role_name" => $d, 'role_id'=>$p, "society_id" => $society_id));

}	
//////////////// Role to assign end   //////////////////////////


}

function society_approve_reject()
{
$this->layout='blank';	
$email=htmlentities($this->request->query('con'));
$user_id=(int)htmlentities($this->request->query('con1'));
$this->loadmodel('user_temp');
$this->user_temp->updateAll(array('reject'=>1),array('user_temp.user_temp_id'=>$user_id));
$this->response->header('Location','society_approve');

}


function role_add()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');

if (isset($this->request->data['add_role'])) 
{
$role_name=$this->request->data['role_name'];
$auto_id=$this->autoincrement('role','auto_id');
$role_id=$this->autoincrement_with_society('role','role_id');



$this->loadmodel('role');
$multipleRowData = Array( Array('auto_id'=>$auto_id,'role_id' => $role_id, 'role_name' => $role_name,'society_id' => $s_society_id));
$this->role->saveAll($multipleRowData); 
}

$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$this->set('result_role',$this->role->find('all',array('conditions'=>$conditions)));


}



function asisgn_module_to_role()
{
$this->layout='session';
$this->ath();
//$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');
//$role_id=$this->request->query('con');

if (isset($this->request->data['add_role'])) 
{
$role_id=(int)$this->request->data['r_name'];

$this->loadmodel('hm_modules_assign');
$conditions=array("society_id" => $s_society_id);
$result_hm_modules_assign=$this->hm_modules_assign->find('all',array('conditions'=>$conditions));
foreach($result_hm_modules_assign as $data)
{
$module_id=$data['hm_modules_assign']['module_id'];

$this->loadmodel('sub_modules');
$conditions=array("module_id" => $module_id);
$result_sub_modules=$this->sub_modules->find('all',array('conditions'=>$conditions));
foreach($result_sub_modules as $data)
{
$sub_module_id=(int)$data["sub_modules"]["auto_id"];
$sub_module_name=$data["sub_modules"]["sub_module_name"];

$check_box=@$this->request->data['ch'.$sub_module_id];
if($check_box>0)
{
$this->loadmodel('role_privilege');
$conditions=array("society_id" => $s_society_id ,"role_id" => $role_id,"sub_module_id" => $sub_module_id,"module_id" => $module_id,"sub_module_id" => $sub_module_id);
$n=$this->role_privilege->find('count',array('conditions'=>$conditions));

if($n==0)
{
$this->loadmodel('role_privilege');
$data_row = Array( Array("society_id" => $s_society_id, "role_id" => $role_id , "module_id" => $module_id,"sub_module_id" => $sub_module_id));
$this->role_privilege->saveAll($data_row); 

}

}
else
{
$this->loadmodel('role_privilege');
$conditions=array("society_id" => $s_society_id ,"role_id" => $role_id,"sub_module_id" => $sub_module_id);
$n=$this->role_privilege->deleteall($conditions);
}



}


}
$this->redirect('asisgn_module_to_role');
}


$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$this->set('result_role',$this->role->find('all',array('conditions'=>$conditions)));


}

function assign_modules_to_role_ajax()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');
$s_role_id=$this->Session->read('role_id');

$this->loadmodel('hm_modules_assign');
$conditions=array("society_id" => $s_society_id);
$this->set('result_hm_modules_assign',$this->hm_modules_assign->find('all',array('conditions'=>$conditions)));

}

function fetch_main_module_name($module_id)
{
$this->layout='blank';
$this->ath();

$this->loadmodel('main_module');
$conditions=array("auto_id" => $module_id);
return $result_main_module=$this->main_module->find('all',array('conditions'=>$conditions));
}

function fetch_sub_module($main_module_id)
{
$this->layout='blank';
$this->ath();

$this->loadmodel('sub_modules');
$conditions=array("module_id" => $main_module_id);
return $result_sub_modules=$this->sub_modules->find('all',array('conditions'=>$conditions));
}

function fetch_role_privileges($sub_module_id)
{
$this->layout='blank';
$this->ath();

$role_id=(int)$this->request->query('con');

$s_society_id=$this->Session->read('society_id');

$this->loadmodel('role_privilege');
$conditions=array("sub_module_id" => $sub_module_id,"society_id" => $s_society_id,"role_id" => $role_id);
return $result_role_privileges=$this->role_privilege->find('count',array('conditions'=>$conditions));
}

function hm_assign_module()
{
$this->layout='session';
$this->loadmodel('society');
$result=$this->society->find('all');
$this->set('result_society',$result);
if ($this->request->is('post')) 
{
$society_id=(int)$this->request->data['r_name'];
$this->loadmodel('main_module');
$result_main_module=$this->main_module->find('all');

foreach ($result_main_module as $collection) 
{		  
$module_id =(int)$collection['main_module']['auto_id'];
$value =@$this->request->data[$module_id];
if($value==1)
{

$this->loadmodel('hm_modules_assign');
$conditions=array("society_id" => $society_id, "module_id" => $module_id);
$result1=$this->hm_modules_assign->find('all',array('conditions'=>$conditions));

$n = sizeof($result1);
if($n==0)
{
$this->loadmodel('hm_modules_assign');
$this->hm_modules_assign->saveAll(array("society_id" => $society_id, "module_id" => $module_id));
}   
}
else
{
$this->loadmodel('hm_modules_assign');
$conditions= array("society_id" => $society_id, "module_id" => $module_id);
$this->hm_modules_assign->deleteAll($conditions);
$this->loadmodel('role_privilege');
$conditions= array("society_id" => $society_id, "module_id" => $module_id);
$this->role_privilege->deleteAll($conditions);
}

}

}
}

function hm_society_view()
{
$this->layout='session';
$this->ath();
$this->check_housingmatters_privilages();
$this->loadmodel('society');
$result=$this->society->find('all');
$this->set('n',sizeof($result));
$this->set('result_society',$result);
}


function hm_resident_approve_resend_mail()
{
	$this->layout='blank';
	$user_temp_id=(int)$this->request->query('con');	
	$s_society_id=(int)$this->request->query('con2');		
$this->loadmodel('user');
$conditions=array('user_id'=>$user_temp_id);
$result_user_temp=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user_temp as $collection) 
{ 
$society_id=(int)$collection['user']['society_id'];
$user_name=$collection['user']['user_name'];
$mobile=$collection['user']['mobile'];
$email=$collection['user']['email'];
$password=$collection['user']['password'];
$wing=(int)$collection['user']['wing'];
$flat=(int)$collection['user']['flat'];
$tenant=(int)$collection['user']['tenant'];
$residing=(int)$collection['user']['residing'];

}
///////////end fetch data ////////////////////

$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;

//////////////// insert data user table //////////////////////////

$de_user_id=$this->encode($user_temp_id,'housingmatters');
$random=$de_user_id.'/'.$random;


$this->loadmodel('user');
$this->user->updateAll(array('signup_random'=>$random),array('user.user_id'=>$user_temp_id));



//////////////// end insert code  //////////////////////////

$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result12=$this->society->find('all',array('conditions'=>$conditions12));
foreach($result12 as $data)
{
$s_name=$data['society']['society_name'];
}
///////////////////////////////////////////// approve mail functionality ///////////////////////////////////////

$to=$email;
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<p style='color:green;'><strong>Reminder!</strong></p>
<p>Dear  $user_name,</p>
<p>'We at $s_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent &amp; smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $s_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>

<li>log &amp; track complaints</li>
<li>start new discussions</li>
<li>check your maintenance dues</li>
<li>post classifieds</li>
<li>receive important SMS &amp; emails from your committee</li>
<li>and much more in the portal.</li>



<p><b><a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random' target='_blank'><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'>Click here</button></a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>


<p>Regards,</p>
<p>Administrator of $s_name</p>

<p>PS: add <a href='http://www.housingmatters.co.in' target='_blank'>www.housingmatters.co.in</a> in your favorite bookmarks for future use.</p>



</div >
</div>";

$subject="[$s_name]";
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
	
}


function hm_resident_approve_resend_sms()
{
	
		$this->layout='blank';
		$user_temp_id=(int)$this->request->query('con');	
		$s_society_id=(int)$this->request->query('con2');	
		$result_society=$this->society_name($s_society_id);
		foreach($result_society as $dd)
		{
		$society_name=$dd['society']['society_name'];
		}
		
		$s_n='';
		$sco_na=$society_name;
		$dd=explode(' ',$sco_na);
		$first=$dd[0];
		$two=$dd[1];
		$three=$dd[2];
		$s_n.=" $first $two $three ";
		
		$this->loadmodel('user');
		$conditions=array('user_id'=>$user_temp_id);
		$result_user=$this->user->find('all',array('conditions'=>$conditions));
		foreach($result_user as $data)
		{
		$mobile=$data['user']['mobile'];
		$user_name=$data['user']['user_name'];
		$login_id=(int)$data['user']['login_id'];
		}
		
		$random=(string)mt_rand(1000,9999);
		$sms="".$user_name.", Your housing society ".$s_n." has enrolled you in HousingMatters portal. Pls log into www.housingmatters.co.in One Time Password ".$random."";
		$sms1=str_replace(" ", '+', $sms);
		$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
		$this->loadmodel('user');
		$this->user->updateAll(array('password'=>$random,'signup_random'=>$random),array('user_id'=>$user_temp_id));
		$this->loadmodel('login');
		$this->login->updateAll(array('password'=>$random,'signup_random'=>$random),array('login_id'=>$login_id));


}


function hm_society_member_view()
{
$this->layout='session';
$this->ath();	
$this->loadmodel('society');	
$this->set('result_society',$this->society->find('all'));
$this->loadmodel('user');		
$result1=$this->user->find('all',array('conditions'=>array('deactive'=>0)));	
$this->set('result_user',$result1);
$this->set('n',sizeof($result1));	
	
}


function society_count_user($society_id)
{
$this->loadmodel('user');
$conditions=array('society_id'=>$society_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
return sizeof($result);

}


function hm_assign_module_ajax()
{
$this->layout='blank';
$society_id=(int)$this->request->query('con');
$this->loadmodel('main_module');
$result=$this->main_module->find('all');
$this->set('result_main_module',$result);
$this->set('society_id',$society_id);


}
function count($module_id,$society_id)
{
$this->loadmodel('hm_modules_assign');
$conditions=array("society_id" => $society_id, "module_id" => $module_id); 
$result=$this->hm_modules_assign->find('all',array('conditions'=>$conditions)); 
return $n=sizeof($result);         
}	



function user_assign_role()
{
$s_society_id=$this->Session->read('society_id');
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$this->loadmodel('user');
$conditions1=array('society_id'=>$s_society_id);
$result=$this->user->find('all',array('conditions'=>$conditions1));
$this->set('result_user',$result);
if ($this->request->is('post')) 
{

$user_id=(int)$this->request->data['user'];
$this->loadmodel('role');
$conditions2=array("society_id" => $s_society_id);
$result_role=$this->role->find('all',array('conditions'=>$conditions2));	
foreach ($result_role as $collection) 
{					
$role_id=(int)$collection['role']["role_id"];
$r=@$this->request->data['role'.$role_id];
if($r==1)
{
$j[]=$role_id;
}
}			

$this->loadmodel('user');
$this->user->updateAll(array('role_id'=>$j),array('user_id'=>$user_id));


}

}


function user_assign_role_ajax()
{
$this->layout='blank';
$user_id=(int)$this->request->query('con');
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('role');
$conditions=array('society_id'=>$s_society_id);	
$result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('result_role',$result);
$this->set('user_id',$user_id);
}

function user_role($role_id,$user_id)
{
$this->loadmodel('user');
$conditions=array("user_id" => $user_id, "role_id" => $role_id); 
$result=$this->user->find('all',array('conditions'=>$conditions)); 
return $n=sizeof($result);        
}


///////////////////////////////////////////////// Help Desk  Model Start //////////////////////////////////// //////////////////////////////////////////



function help_desk_r_open_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel('help_desk');
$conditions=array("help_desk_status" => 0,"society_id" => $s_society_id,"user_id" => $s_user_id,'help_desk_draft'=>0);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);


}

function help_desk_category_name($complain_type_id)
{


$this->loadmodel('help_desk_category');
$conditions=array("help_desk_category_id" => $complain_type_id);
$result_category=$this->help_desk_category->find('all',array('conditions'=>$conditions));

foreach ($result_category as $collection) 
{
return $help_desk_category_name=$collection['help_desk_category']['help_desk_category_name'];
}
}





function help_desk_r_close_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("help_desk_status" => 1,"society_id" => $s_society_id,"user_id" => $s_user_id,'help_desk_draft'=>0);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);

}

function help_desk_r_all_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("society_id" => $s_society_id,"user_id" => $s_user_id,'help_desk_draft'=>0);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);


}

function help_desk_r_draft_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();	
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('help_desk');
$conditions=array("help_desk_draft" =>1,"user_id" => $s_user_id);
$order=array('help_desk.help_desk_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('result_help_desk_draft',$result);
}


function help_desk_draft_delete()
{
$this->layout='blank';
$id=(int)$this->request->query('con');
$this->loadmodel('help_desk');
$this->help_desk->updateAll(array("help_desk_draft" =>2),array("help_desk_id" => $id));
$this->response->header('Location:help_desk_r_draft_ticket');

}


function help_desk_send_to_sm()
{
$this->layout='session';
$this->ath();
$s_society_id= $this->Session->read('society_id');
$s_user_id= $this->Session->read('user_id');
$id=(int)$this->request->query('id');
$this->loadmodel('help_desk_category');
$order=array('help_desk_category.help_desk_category_name'=> 'ASC');					
$result=$this->help_desk_category->find('all',array('order'=>$order));					
$this->set('result_help_desk_category',$result);
$this->loadmodel('help_desk');
$conditions=array('help_desk_id'=>$id);
$result_help=$this->help_desk->find('all',array('conditions'=>$conditions));	
$this->set('result_help_desk_draft',$result_help);
foreach($result_help as $data)
{
	 $att=$data['help_desk']['help_desk_file'];
}

if(isset($this->request->data['sub']))
{
 $category=(int)$this->request->data['category'];
 $textarea=htmlentities($this->request->data['comment']);
  $ticket_priority=(int)$this->request->data['priority'];
 $t=$this->autoincrement_with_society_ticket('help_desk','ticket_id');
 date_default_timezone_set('Asia/kolkata');
 $date=date("d-m-y");
 $time=date('h:i:a',time());
 $file=$this->request->form['file']['name'];
	if(empty($file))
	{
	$file=$att;	
	}
$target = "help_desk_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
$this->loadmodel('help_desk');

$this->help_desk->updateAll(array('ticket_id'=>$t,'help_desk_draft'=>0, "society_id" => $s_society_id , "user_id" => $s_user_id, "help_desk_complain_type_id" => $category,"help_desk_description" => $textarea, "help_desk_date" =>$date,"help_desk_assign_date" =>"", "help_desk_time" =>$time, "help_desk_status" => 0, "help_desk_service_provider_id" => 0,"help_desk_file"=>$file ,"help_desk_close_comment"=>"","help_desk_close_date"=>"","ticket_priority"=>$ticket_priority),array('help_desk_id'=>$id));


//------------------mail functinality  start SM -------------------
$user_mail=1;
if($user_mail==1)	
{
$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result1=$this->society->find('all',array('conditions'=>$conditions12));

foreach ($result1 as $collection) 
{
$user=$collection['society']["user_id"];
$society_name=$collection['society']["society_name"];
}
$this->loadmodel('user');
$conditions2=array("user_id"=>$user);
$result_user=$this->user->find('all',array('conditions'=>$conditions2));
foreach ($result_user as $collection) 
{
$to=$collection['user']["email"];
$mobile=$collection['user']["mobile"];
}

$this->loadmodel('user');
$conditions3=array("user_id"=>$s_user_id);
$result3=$this->user->find('all',array('conditions'=>$conditions3));
foreach ($result3 as $collection) 
{
$user_name=$collection['user']["user_name"];
$reply=$collection['user']["email"];
$wing=(int)$collection['user']["wing"];
$flat=(int)$collection['user']["flat"];
$da_society_id=(int)$collection['user']['society_id'];
}

$this->loadmodel('wing');
$conditions4=array("wing_id"=>$wing);
$result_wing=$this->wing->find('all',array('conditions'=>$conditions4));
foreach ($result_wing as $collection) 
{
$wing_name=$collection['wing']["wing_name"];
}
$this->loadmodel('flat');
$conditions5=array("flat_id"=>$flat);
$result_flat=$this->flat->find('all',array('conditions'=>$conditions5));
foreach ($result_flat as $collection) 
{
$flat_name=$collection['flat']["flat_name"];
}
@$wing_flat=$wing_name.'-'.$flat_name;

if($ticket_priority==1)
{
$ticket_priority="Urgent";
}
else
{
$ticket_priority="Normal";
}
 $ticket_no=$t;
 $i=$id;
 $category_name=$this->help_desk_category_name($category);

 $sms='New Helpdesk ticket '.$ticket_no.' - '.$category_name.' raised+by '.$user_name.' - '.$wing_flat.' Please log into HousingMatters for further action.';

$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');		
  $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear Administrator,</p><br/>
<p>A new helpdesk ticket is raised in your society.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Posted by</td>
<td>Flat #</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_no</td>
<td>$ticket_priority</td>
<td>$user_name</td>
<td>$wing_flat</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$textarea</p><br/>
<center><p>To view the ticket or post response
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
HousingMatters (Support Team)<br/>
www.housingmatters.co.in
</div>
</div>";

$from_name="HousingMatters";
$this->loadmodel('email');
$conditions6=array("auto_id"=>1);
$result4=$this->email->find('all',array('conditions'=>$conditions6));
foreach ($result4 as $collection) 
{
$from=$collection['email']["from"];

}
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>1,"user_id"=>$user,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if(1==1)
{
@$subject.= ''. $society_name . '' . '- New Helpdesk Ticket ' . '  #   ' .$ticket_no .'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
}	
/////////////////////////////////// end sm mailfunctionality ////////////////////////

$user_will_get[]=$user;
$this->recent_activities('icon-barcode',$s_user_id,'lodge a new ticket','help_desk_sm_view?id='.$i.'&status=0',$user_will_get,1);



///////////////////////// Send Mail User ///////////////////////////	

$this->loadmodel('help_desk_category');
$conditions=array("help_desk_category_id" => $category);
$cursor=$this->help_desk_category->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection2) 
{
$help_desk_category_name=$collection2['help_desk_category']['help_desk_category_name'];
}

$user_d[]=$user;
$this->send_notification('<span class="label" style="background-color:#d43f3a;"><i class="icon-plus"></i></span>','New Help-desk ticket# <b>'.$t.'-'.$help_desk_category_name.'</b> lodged by',1,$i,'help_desk_sm_view?id='.$i.'&status=0',$s_user_id,$user_d);


$user_mail=2;
if($user_mail==2)	
{
$to=$reply;
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$society_name_user=$this->society_name($da_society_id);

  $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>

</br><p>Dear $user_name,</p><br/>
<p>Please find below details of new helpdesk ticket raised by you.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Description</td>

</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_no</td>
<td>$ticket_priority</td>
<td>$textarea</td>
</tr>
</table>
<div>
<br/>
<center><p>To view status update or respond
<a href='http://123.63.2.150:8080' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/>
www.housingmatters.co.in
</div ><br/>
</div>";
$this->loadmodel('notification_email');
$conditions8=array("module_id" =>1,"user_id"=>$s_user_id);
$result6=$this->notification_email->find('all',array('conditions'=>$conditions8));
$s=sizeof($result6);
if($s>0)
{
@$subject.= ''. $society_name . '' . '- New Helpdesk Ticket ' . '  #  ' .$ticket_no .'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}

///////////////////////////////////////////////////////////////End Mail functionality ..../////////////////////////////////////////////////////////////////

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Ticket has been generated.<br/>
Your Ticket Id is: #<?php echo $t; ?> .
</div> 
<div class="modal-footer">
<a href="help_desk_r_open_ticket" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	
}
	
}


function help_desk_genarate_ticket()
{	
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id= $this->Session->read('society_id');
$s_user_id= $this->Session->read('user_id');

$this->loadmodel('help_desk_category');
$order=array('help_desk_category.help_desk_category_name'=> 'ASC');					
$result=$this->help_desk_category->find('all',array('order'=>$order));					
$this->set('result_help_desk_category',$result);

if(isset($this->request->data['sub']))
{
$category=(int)$this->request->data['category'];
$textarea=htmlentities($this->request->data['description']);
$ticket_priority=(int)$this->request->data['priority'];
$i=$this->autoincrement('help_desk','help_desk_id');
$t=$this->autoincrement_with_society_ticket('help_desk','ticket_id');
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-y");
$time=date('h:i:a',time());
$file=$this->request->form['file']['name'];
$target = "help_desk_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 



$this->loadmodel('help_desk');
$this->help_desk->saveAll(array("help_desk_id" => $i, "ticket_id" => $t, "society_id" => $s_society_id , "user_id" => $s_user_id, "help_desk_complain_type_id" => $category,"help_desk_description" => $textarea, "help_desk_date" =>$date,"help_desk_assign_date" =>"", "help_desk_time" =>$time, "help_desk_status" => 0, "help_desk_service_provider_id" => 0,"help_desk_file"=>$file ,"help_desk_close_comment"=>"","help_desk_close_date"=>"","ticket_priority"=>$ticket_priority,'help_desk_draft'=>0));






//////////////////////////////////////////////////////////////  Mail Functionality starting /////////////////////////////////////////////////////////////////
//------------------mail functinality  start SM -------------------
$user_mail=1;
if($user_mail==1)	
{
$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result1=$this->society->find('all',array('conditions'=>$conditions12));

foreach ($result1 as $collection) 
{
$user=$collection['society']["user_id"];
$society_name=$collection['society']["society_name"];
}
$this->loadmodel('user');
$conditions2=array("user_id"=>$user);
$result_user=$this->user->find('all',array('conditions'=>$conditions2));
foreach ($result_user as $collection) 
{
$to=$collection['user']["email"];
$mobile=$collection['user']["mobile"];
}
$this->loadmodel('user');
$conditions3=array("user_id"=>$s_user_id);
$result3=$this->user->find('all',array('conditions'=>$conditions3));
foreach ($result3 as $collection) 
{
$user_name=$collection['user']["user_name"];
$reply=$collection['user']["email"];
$wing=(int)$collection['user']["wing"];
$flat=(int)$collection['user']["flat"];
$da_society_id=(int)$collection['user']['society_id'];
}
$this->loadmodel('wing');
$conditions4=array("wing_id"=>$wing);
$result_wing=$this->wing->find('all',array('conditions'=>$conditions4));
foreach ($result_wing as $collection) 
{
$wing_name=$collection['wing']["wing_name"];
}
$this->loadmodel('flat');
$conditions5=array("flat_id"=>$flat);
$result_flat=$this->flat->find('all',array('conditions'=>$conditions5));
foreach ($result_flat as $collection) 
{
$flat_name=$collection['flat']["flat_name"];
}
@$wing_flat=$wing_name.'-'.$flat_name;
if($ticket_priority==1)
{
$ticket_priority="Urgent";
}
else
{
$ticket_priority="Normal";
}
$ticket_no=$t;
$category_name=$this->help_desk_category_name($category);
$sms='New Helpdesk ticket '.$ticket_no.' - '.$category_name.' raised+by '.$user_name.' - '.$wing_flat.' Please log into HousingMatters for further action.';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');		
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear Administrator,</p><br/>
<p>A new helpdesk ticket is raised in your society.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Posted by</td>
<td>Flat #</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_no</td>
<td>$ticket_priority</td>
<td>$user_name</td>
<td>$wing_flat</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$textarea</p><br/>
<center><p>To view the ticket or post response
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
HousingMatters (Support Team)<br/>
www.housingmatters.co.in
</div>
</div>";

$from_name="HousingMatters";
$this->loadmodel('email');
$conditions6=array("auto_id"=>1);
$result4=$this->email->find('all',array('conditions'=>$conditions6));
foreach ($result4 as $collection) 
{
$from=$collection['email']["from"];

}
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>1,"user_id"=>$user,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if(1==1)
{
@$subject.= ''. $society_name . '' . '- New Helpdesk Ticket ' . '  #   ' .$ticket_no .'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
}	
/////////////////////////////////// end sm mailfunctionality ////////////////////////
$user_will_get[]=$user;
$this->recent_activities('icon-barcode',$s_user_id,'lodge a new ticket','help_desk_sm_view?id='.$i.'&status=0',$user_will_get,1);

///////////////////////// Send Mail User ///////////////////////////	

$this->loadmodel('help_desk_category');
$conditions=array("help_desk_category_id" => $category);
$cursor=$this->help_desk_category->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection2) 
{
$help_desk_category_name=$collection2['help_desk_category']['help_desk_category_name'];
}

$user_d[]=$user;
$this->send_notification('<span class="label" style="background-color:#d43f3a;"><i class="icon-plus"></i></span>','New Help-desk ticket# <b>'.$t.'-'.$help_desk_category_name.'</b> lodged by',1,$i,'help_desk_sm_view?id='.$i.'&status=0',$s_user_id,$user_d);


$user_mail=2;
if($user_mail==2)	
{
$to=$reply;
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$society_name_user=$this->society_name($da_society_id);

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>

</br><p>Dear $user_name,</p><br/>
<p>Please find below details of new helpdesk ticket raised by you.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Description</td>

</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_no</td>
<td>$ticket_priority</td>
<td>$textarea</td>

</tr>
</table>
<div>
<br/>
<center><p>To view status update or respond
<a href='http://123.63.2.150:8080' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/>
www.housingmatters.co.in
</div ><br/>
</div>";



$this->loadmodel('notification_email');
$conditions8=array("module_id" =>1,"user_id"=>$s_user_id);
$result6=$this->notification_email->find('all',array('conditions'=>$conditions8));
$s=sizeof($result6);
if($s>0)
{
@$subject.= ''. $society_name . '' . '- New Helpdesk Ticket ' . '  #  ' .$ticket_no .'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	

}

///////////////////////////////////////////////////////////////End Mail functionality ..../////////////////////////////////////////////////////////////////

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Ticket has been generated.<br/>
Your Ticket Id is: #<?php echo $t; ?> .
</div> 
<div class="modal-footer">
<a href="help_desk_r_open_ticket" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	
}

if(isset($this->request->data['draft']))
{

$category=(int)$this->request->data['category'];
//@$file= $this->response->data['file_up']['name'];
$textarea=htmlentities($this->request->data['description']);
$ticket_priority=(int)$this->request->data['priority'];
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-y");
$time=date('h:i:a',time());
$file=$this->request->form['file']['name'];
$target = "help_desk_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
$j=$this->autoincrement('help_desk','help_desk_id');
$this->loadmodel('help_desk');
$this->help_desk->saveAll(array("help_desk_id" => $j, "ticket_id" => 0, "society_id" => $s_society_id , "user_id" => $s_user_id, "help_desk_complain_type_id" => $category,"help_desk_description" => $textarea, "help_desk_date" =>$date,"help_desk_assign_date" =>"", "help_desk_time" =>$time, "help_desk_status" => 0, "help_desk_service_provider_id" => 0,"help_desk_file"=>$file ,"help_desk_close_comment"=>"","help_desk_close_date"=>"","ticket_priority"=>$ticket_priority,'help_desk_draft'=>1));

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Ticket has been saved in draft folder.
</div> 
<div class="modal-footer">
<a href="help_desk_r_draft_ticket" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
}


}




function help_desk_sm_open_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("help_desk_status" => 0,"society_id" => $s_society_id,'help_desk_draft'=>0);
$order=array('help_desk.ticket_id'=> 'DESC');
$result_help_desk=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result_help_desk);
foreach ($result_help_desk as $collection) 
{

$d_user_id=(int)$collection['help_desk']['user_id'];
$ticket_priority=$collection['help_desk']['ticket_priority'];
$ticket_id=(int)$collection['help_desk']['ticket_id'];
$help_generate_date=$collection['help_desk']['help_desk_date'];
$help_desk_description=$collection['help_desk']['help_desk_description'];
$da_society_id=(int)$collection['help_desk']['society_id'];

$result_user = $this->profile_picture($d_user_id);
foreach ($result_user as $collection) 
{
$user_name=$collection['user']['user_name'];
$email=$collection['user']['email'];
}
}




////////////////////////////////////////////////////////
////////////////////close ticket///////////////////////
///////////////////////////////////////////////////////
if (isset($this->request->data['close'])) 
{
$hd_id=(int)$this->request->data['hd_id'];
$close_date=date("d-m-y");
$massage_close=htmlspecialchars($_POST['close_msg']);
$to= $email;
if($ticket_priority==1)
{
$ticket_priority="Urgent";
}
else
{
$ticket_priority="Normal";
}

/* $message_web="<div style=' padding:25px;  font-size:14px; border:1px solid #BCE8F1; width:80%; background-color: #fcf8e3;'>
<p style='background-color:#60F;  font-size:16px; padding:10px;'><b style='color: white; '> HousingMatters</b></p><br/>
<p>Dear $user_name,</p><br/>
<p>Your helpdesk ticket has been resolved & closed.</p>
<table border='1' cellpadding='10' width='100%;'  style='margin-bottom:2px; ' >
<tr bgcolor='#717BD7'>
<td ><b style='color: white; '>HelpDesk Ticket</b></td>
<td><b style='color: white; '>Priority </b></td>
<td><b style='color: white; '>Ticket Date</b></td>
<td><b style='color: white; '>Closure Date</b></td>
</tr>
<tr bgcolor='#717BD7'>
<td ><b style=' '>$ticket_id</b></td>
<td><b style=' '>$ticket_priority</b></td>
<td><b style=' '>$help_generate_date</b></td>
<td><b style=' '>$close_date</b></td>
</tr>
</table>
<div style=' padding:5px;  font-size:14px; border:1px solid #BCE8F1; background-color:#B6AFF3;'>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$help_desk_description</p>
</div ><br/>
<div style=' padding:5px;  font-size:14px; border:1px solid #BCE8F1; background-color:#B6AFF3;'>
<p style='font-size:16px;'> <strong>Ticket Description by user:</strong></p>
<p style='font-size:15px;'>$massage_close</p>
</div ><br/>
</div>"; 

*/


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $user_name,</p><br/>
<p>Your helpdesk ticket has been resolved & closed.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Ticket Date</td>
<td>Closure Date</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_id</td>
<td>$ticket_priority</td>
<td>$help_generate_date</td>
<td>$close_date</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$help_desk_description</p> <br/>
<p style='font-size:16px;'> <strong>Ticket Description by user:</strong></p>
<p style='font-size:15px;'>$massage_close</p>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";


$reply="donotreply@housingmatters.in";
$from_name="HousingMatters";

$this->loadmodel('email');
$conditions=array("auto_id" => 1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}

$society_result=$this->society_name($da_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}
$this->loadmodel('notification_email');
$conditions=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
$n=$this->notification_email->find('count',array('conditions'=>$conditions));
if($n>0)
{
@$subject.= ''. $society_name . '' . ' Closure of Helpdesk Ticket #'. '' .$ticket_id.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
$close_date;
$massage_close;
$this->loadmodel('help_desk');
$this->help_desk->updateAll(array("help_desk_close_comment" => $massage_close,"help_desk_close_date"=>$close_date,"help_desk_status" => 1),array("help_desk_id" => $hd_id));
$this->response->header('Location:help_desk_sm_close_ticket');

}
////////////////////////////////////////////////////////
////////////////////close ticket///////////////////////
///////////////////////////////////////////////////////



}

function assign_ticket()
{
$this->layout='blank';
}


function help_desk_sm_close_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('help_desk');
$conditions=array("help_desk_status" =>1,"society_id" => $s_society_id);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);


}


function help_desk_sm_all_ticket()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("society_id" => $s_society_id);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);
foreach ($result as $collection) 
{
$d_user_id=(int)$collection['help_desk']['user_id'];
$ticket_priority=$collection['help_desk']['ticket_priority'];
$ticket_id=(int)$collection['help_desk']['ticket_id'];
$help_generate_date=$collection['help_desk']['help_desk_date'];
$help_desk_description=$collection['help_desk']['help_desk_description'];
$da_society_id=(int)$collection['help_desk']['society_id'];
$result_user = $this->profile_picture($d_user_id);
foreach ($result_user as $collection) 
{
$user_name=$collection['user']['user_name'];
$email=$collection['user']['email'];
}
}
/////////////////////////////////////////////////////// Close Ticket code and Email Code ///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($this->request->data['close'])) 
{
$hd_id=(int)$this->request->data['hd_id'];
$close_date=date("d-m-y");
$massage_close=htmlspecialchars($_POST['close_msg']);
$to= $email;
if($ticket_priority==1)
{
$ticket_priority="Urgent";
}
else
{
$ticket_priority="Normal";
}
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $user_name,</p><br/>
<p>Your helpdesk ticket has been resolved & closed.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Ticket Date</td>
<td>Closure Date</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_id</td>
<td>$ticket_priority</td>
<td>$help_generate_date</td>
<td>$close_date</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$help_desk_description</p> <br/>
<p style='font-size:16px;'> <strong>Ticket Description by user:</strong></p>
<p style='font-size:15px;'>$massage_close</p>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";

$reply="donotreply@housingmatters.in";
$from_name="HousingMatters";

$this->loadmodel('email');
$conditions=array("auto_id" => 1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}

$society_result=$this->society_name($da_society_id);
foreach($society_result as $data)
{
	$society_name=$data['society']['society_name'];
}

$this->loadmodel('notification_email');
$conditions=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
$n=$this->notification_email->find('count',array('conditions'=>$conditions));
if($n>0)
{
@$subject.= ''. $society_name . '' . ' Closure of Helpdesk Ticket #'. '' .$ticket_id.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
$close_date;
$massage_close;
$this->loadmodel('help_desk');
$this->help_desk->updateAll(array("help_desk_close_comment" => $massage_close,"help_desk_close_date"=>$close_date,"help_desk_status" => 1),array("help_desk_id" => $hd_id));
$this->response->header('Location:help_desk_sm_close_ticket');
}
//////////////////////////////////////////////////////End close ticket code and Email functionality ///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}




function help_desk_r_view()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();

$hd_id=(int)$this->request->query('id');
$this->set('hd_id',$hd_id);
$status=(int)$this->request->query('status');
$this->set('status',$status);

$this->seen_notification(1,$hd_id);

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("help_desk_id" => $hd_id);
$result=$this->help_desk->find('all',array('conditions'=>$conditions));
foreach ($result as $collection) 
{
$this->set('help_desk_description',$collection['help_desk']['help_desk_description']);
$this->set('help_desk_file',$collection['help_desk']['help_desk_file']);
$this->set('ticket_id',(int)$collection['help_desk']['ticket_id']);
$this->set('help_desk_close_date',@$collection['help_desk']['help_desk_close_date']);
$this->set('help_desk_close_comment',@$collection['help_desk']['help_desk_close_comment']);
$help_desk_complain_type_id=(int)$collection['help_desk']['help_desk_complain_type_id'];
$this->set('help_desk_complain_type_id',$help_desk_complain_type_id);
$this->set('help_desk_date',$collection['help_desk']['help_desk_date']);
$this->set('help_desk_time',$collection['help_desk']['help_desk_time']);
}

$this->loadmodel('help_desk_category');
$conditions=array("help_desk_category_id" => 5);
$cursor=$this->help_desk_category->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection2) 
{
$this->set('help_desk_category_name',$collection2['help_desk_category']['help_desk_category_name']);
}

$this->loadmodel('help_desk_reply');
$conditions=array("help_desk_id" => $hd_id);
$this->set('result_reply',$this->help_desk_reply->find('all',array('conditions'=>$conditions)));

}


function help_desk_sm_view()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();

$hd_id=(int)$this->request->query('id');
$this->set('hd_id',$hd_id);
$status=(int)$this->request->query('status');
$this->set('status',$status);

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->seen_notification(1,$hd_id);
////////////////////////////////////////////////////////
////////////////////close ticket///////////////////////
///////////////////////////////////////////////////////
$this->loadmodel('help_desk');
$conditions=array("help_desk_id" => $hd_id);
$result_help_desk=$this->help_desk->find('all',array('conditions'=>$conditions));
$this->set('result_help_desk',$result_help_desk);
foreach ($result_help_desk as $collection) 
{

$d_user_id=(int)$collection['help_desk']['user_id'];
$ticket_priority=$collection['help_desk']['ticket_priority'];
$ticket_id=(int)$collection['help_desk']['ticket_id'];
$help_generate_date=$collection['help_desk']['help_desk_date'];
$help_desk_description=$collection['help_desk']['help_desk_description'];
$da_society_id=(int)$collection['help_desk']['society_id'];

$result_user = $this->profile_picture($d_user_id);
foreach ($result_user as $collection) 
{
$user_name=$collection['user']['user_name'];
$email=$collection['user']['email'];
}
}
if (isset($this->request->data['close'])) 
{
$close_date=date("d-m-y");
$massage_close=htmlspecialchars($_POST['close_msg']);
$to= $email;
if($ticket_priority==1)
{
$ticket_priority="Urgent";
}
else
{
$ticket_priority="Normal";
}
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $user_name,</p><br/>
<p>Your helpdesk ticket has been resolved & closed.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Priority </td>
<td>Ticket Date</td>
<td>Closure Date</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_id</td>
<td>$ticket_priority</td>
<td>$help_generate_date</td>
<td>$close_date</td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Ticket Description:</strong></p>
<p style='font-size:15px;'>$help_desk_description</p> <br/>
<p style='font-size:16px;'> <strong>Ticket Description by user:</strong></p>
<p style='font-size:15px;'>$massage_close</p>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";


$reply="donotreply@housingmatters.in";
$from_name="HousingMatters";

$this->loadmodel('email');
$conditions=array("auto_id" => 1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}

$society_result=$this->society_name($da_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}
$this->loadmodel('notification_email');
$conditions=array("module_id" =>1,"user_id"=>$d_user_id,'chk_status'=>0);
$n=$this->notification_email->find('count',array('conditions'=>$conditions));
if($n>0)
{
@$subject.= ''. $society_name . '' . ' Closure of Helpdesk Ticket #'. '' .$ticket_id.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}
$close_date;
$massage_close;
$this->loadmodel('help_desk');
$this->help_desk->updateAll(array("help_desk_close_comment" => $massage_close,"help_desk_close_date"=>$close_date,"help_desk_status" => 1),array("help_desk_id" => $hd_id));

$da_user_id[]=$d_user_id;
$this->send_notification('<span class="label" style="background-color:#4cae4c;"><i class="icon-ok"></i></span>','Your help-desk ticket#<b>'.$ticket_id.'</b> closed',1,$hd_id,'help_desk_r_view?id='.$hd_id.'&status=1',$s_user_id,$da_user_id);


$this->response->header('Location:help_desk_sm_close_ticket');
}
////////////////////////////////////////////////////////
////////////////////close ticket///////////////////////
///////////////////////////////////////////////////////
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk');
$conditions=array("help_desk_id" => $hd_id);
$result=$this->help_desk->find('all',array('conditions'=>$conditions));
foreach ($result as $collection) 
{
$this->set('help_desk_description',$collection['help_desk']['help_desk_description']);
$this->set('help_desk_file',$collection['help_desk']['help_desk_file']);
$this->set('ticket_id',(int)$collection['help_desk']['ticket_id']);
$this->set('help_desk_close_date',@$collection['help_desk']['help_desk_close_date']);
$this->set('help_desk_close_comment',@$collection['help_desk']['help_desk_close_comment']);
$help_desk_complain_type_id=(int)$collection['help_desk']['help_desk_complain_type_id'];
$this->set('help_desk_complain_type_id',$help_desk_complain_type_id);
$this->set('help_desk_date',$collection['help_desk']['help_desk_date']);
$this->set('help_desk_time',$collection['help_desk']['help_desk_time']);
$this->set('d_user_id',$collection['help_desk']['user_id']);
$this->set('help_desk_status',$collection['help_desk']['help_desk_status']);
$this->set('hd_sp_id',(int)$collection['help_desk']['help_desk_service_provider_id']);
$this->set('help_desk_assign_date',$collection['help_desk']['help_desk_assign_date']);
}

$this->loadmodel('help_desk_category');
$conditions=array("help_desk_category_id" => $help_desk_complain_type_id);
$cursor=$this->help_desk_category->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection2) 
{
$this->set('help_desk_category_name',$collection2['help_desk_category']['help_desk_category_name']);
}

$this->loadmodel('help_desk_reply');
$conditions=array("help_desk_id" => $hd_id);
$this->set('result_reply',$this->help_desk_reply->find('all',array('conditions'=>$conditions)));

$this->loadmodel('vendor');
$conditions=array("category_id" => $help_desk_complain_type_id);
$result_vendor=$this->vendor->find('all',array('conditions'=>$conditions));
$this->set('result_vendor',$result_vendor);
foreach ($result_vendor as $collection)
{
$vendor_id = (int)$collection['vendor']['vendor_id'];
}

$result_sp2=$this->fetch_service_provider_info_via_vendor_id($vendor_id);
foreach ($result_sp2 as $collection3)
{
$this->set('sp_name',$collection3['service_provider']['sp_name']);
}
}


function help_desk_reports()
{
$this->layout='session';
$this->ath();
}

function help_desk_report_1()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');

$d1=$this->request->query('d1');
$d2=$this->request->query('d2');
if(empty($d1) || empty($d2)) { echo '<span style="color:red;">Please select Date-period.</span>'; exit;}
if(strtotime($d1)>strtotime($d2)) { echo '<span style="color:red;">Please select valid Date-period.</span>'; exit;}
$d1=date("Y-m-d",strtotime($d1));
$d2=date("Y-m-d",strtotime($d2));
$this->set('d1',$d1);
$this->set('d2',$d2);

	$this->loadmodel('help_desk');
	$conditions=array("society_id" => $s_society_id);
	$result_help_desk_report1=$this->help_desk->find('all',array('conditions'=>$conditions));
	$this->set('result_help_desk_report1',$result_help_desk_report1);
}

function help_desk_report_2()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');

$d1=$this->request->query('d1');
$d2=$this->request->query('d2');
if(empty($d1) || empty($d2)) { echo '<span style="color:red;">Please select Date-period.</span>'; exit;}
if(strtotime($d1)>strtotime($d2)) { echo '<span style="color:red;">Please select valid Date-period.</span>'; exit;}
$d1=date("Y-m-d",strtotime($d1));
$d2=date("Y-m-d",strtotime($d2));
$this->set('d1',$d1);
$this->set('d2',$d2);

	$this->loadmodel('help_desk');
	$conditions=array("society_id" => $s_society_id,"help_desk_status" => 1);
	$result_help_desk_report1=$this->help_desk->find('all',array('conditions'=>$conditions));
	$this->set('result_help_desk_report1',$result_help_desk_report1);
}


function help_desk_report_3()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');

$d1=$this->request->query('d1');
$d2=$this->request->query('d2');
if(empty($d1) || empty($d2)) { echo '<span style="color:red;">Please select Date-period.</span>'; exit;}
if(strtotime($d1)>strtotime($d2)) { echo '<span style="color:red;">Please select valid Date-period.</span>'; exit;}
$d1=date("Y-m-d",strtotime($d1));
$d2=date("Y-m-d",strtotime($d2));
$this->set('d1',$d1);
$this->set('d2',$d2);

	$this->loadmodel('help_desk');
	$conditions=array("society_id" => $s_society_id);
	$result_help_desk_report1=$this->help_desk->find('all',array('conditions'=>$conditions));
	$this->set('result_help_desk_report1',$result_help_desk_report1);
}

function help_desk_report_4()
{
$this->layout='blank';
$this->ath();

$s_society_id=$this->Session->read('society_id');

$d1=$this->request->query('d1');
$d2=$this->request->query('d2');
if(empty($d1) || empty($d2)) { echo '<span style="color:red;">Please select Date-period.</span>'; exit;}
if(strtotime($d1)>strtotime($d2)) { echo '<span style="color:red;">Please select valid Date-period.</span>'; exit;}
$d1=date("Y-m-d",strtotime($d1));
$d2=date("Y-m-d",strtotime($d2));
$this->set('d1',$d1);
$this->set('d2',$d2);

	$this->loadmodel('help_desk');
	$conditions=array("society_id" => $s_society_id);
	$result_help_desk_report1=$this->help_desk->find('all',array('conditions'=>$conditions));
	$this->set('result_help_desk_report1',$result_help_desk_report1);
}

function assign_ticket_to_sp()
{
$this->layout='blank';
$this->ath();
$sp_id=(int)$this->request->query('sp_id');
$msg=$this->request->query('msg');
$hd_id=(int)$this->request->query('hd_id');
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$date=date("d-m-y");
$this->loadmodel('help_desk');
$conditions=array("help_desk_id" => $hd_id);
$result=$this->help_desk->find('all',array('conditions'=>$conditions));
foreach ($result as $collection) 
{
$ticket_id=(int)$collection['help_desk']['ticket_id'];
$d_user_id=(int)$collection['help_desk']['user_id'];
}
$this->loadmodel('service_provider');
$conditions=array("sp_id" => $sp_id,"society_id" => $s_society_id);
$result_sp=$this->service_provider->find('all',array('conditions'=>$conditions));
foreach ($result_sp as $collection) 
{
$sp_id=(int)$collection['service_provider']['sp_id']; 
$sp_name=$collection['service_provider']['sp_name'];
$sp_email=$collection['service_provider']['sp_email'];
$mobile=$collection['service_provider']['sp_mobile'];
$sp_user_id=$collection['service_provider']['user_id'];
$sp_society_id=(int)$collection['service_provider']['society_id'];
}
$to= $sp_email;
$sms="Assign Ticket";
$sms1=str_replace(' ', '+', $sms);
$from_name="HousingMatters";
$this->loadmodel('email');
$conditions=array("auto_id" => 1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection2) 
{
$from=$collection2['email']['from'];
$sub=$collection2['email']['subject'];
}
$this->loadmodel('society');
$conditions=array("society_id"=>$sp_society_id);
$result_society=$this->society->find('all',array('conditions'=>$conditions));
foreach ($result_society as $collection3) 
{
$society_name=$collection3['society']['society_name'];
$society_user_id=(int)$collection3['society']['user_id'];
}

$this->loadmodel('user');
$conditions=array("user_id"=>$society_user_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result_user as $collection4) 
{
$adm_user_name=$collection4['user']['user_name'];
$adm_mobile=$collection4['user']['mobile'];
$reply=$collection4['user']['email'];
}
@$subject.= ''. $society_name . '' . ' New Helpdesk Ticket #'. '' .$ticket_id.'';
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>1,"user_id"=>$sp_user_id,'chk_status'=>1);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms.'');
}
/*  $message_web="<div style=' padding:25px;  font-size:14px; border:1px solid #BCE8F1; width:80%; background-color: #fcf8e3;'>
<p style='background-color:#60F;  font-size:16px; padding:10px;'><b style='color: white; '> HousingMatters</b></p><br/>
<p>Dear $sp_name,</p><br/>
<p>Please find below details of our helpdesk ticket for your prompt action.</p>
<table border='1' cellpadding='10' width='100%;'  style='margin-bottom:2px; ' >
<tr bgcolor='#717BD7'>
<td ><b style='color: white; '>HelpDesk Ticket</b></td>
<td><b style='color: white; '>Description </b></td>
</tr>
<tr bgcolor='#717BD7'>
<td ><b style=' '>$ticket_id</b></td>
<td><b style=' '>$msg</b></td>
</tr>
</table><br/>

<p style='font-size:15px;'>Please quote the Helpdesk ticket number in your correspondence.</p><br/><br/>
<p>For $society_name </p>
<p>$adm_user_name</p>
<p>$adm_mobile</p>
<br/></div>"; */
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $sp_name,</p><br/>
<p>Please find below details of our helpdesk ticket for your prompt action.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>HelpDesk Ticket</td>
<td>Description </td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$ticket_id</td>
<td>$msg</td>
</tr>
</table>
<div>
<p style='font-size:15px;'>Please quote the Helpdesk ticket number in your correspondence.</p><br/><br/>
<p>For $society_name </p>
<p>$adm_user_name</p>
<p>$adm_mobile</p>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
$this->loadmodel('help_desk');
$this->help_desk->updateAll(array("help_desk_service_provider_id" => $sp_id,"help_desk_assign_date" => $date),array("help_desk_id" => $hd_id));

$da_user_id[]=$d_user_id;
$this->send_notification('<span class="label" style="background-color:#eea236;"><i class="icon-share"></i></span>','Your help-desk ticket#<b>'.$ticket_id.'</b> assigned to '.$sp_name,1,$hd_id,'help_desk_r_view?id='.$hd_id.'&status=0',$s_user_id,$da_user_id);

$this->response->header('Location:help_desk_sm_open_ticket');
}




function fetch_service_provider_info_via_vendor_id($vendor_id)
{
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('service_provider');
$conditions=array("sp_id" => $vendor_id,"society_id" => $s_society_id);
return $this->service_provider->find('all',array('conditions'=>$conditions));
}





function save_reply_resident()
{
$this->layout='blank';
$reply=htmlentities($this->request->query('reply'));
$reply=nl2br($reply);
$rep=explode(' ',$reply);

$r=$this->content_moderation_society($rep);



	
	
$hd_id=(int)$this->request->query('id');

$s_user_id=$this->Session->read('user_id');

$date=date("d-m-y");
$time=date('h:i:a',time());

$t=$this->autoincrement('help_desk_reply','hd_reply_id');
$this->loadmodel('help_desk_reply');
$multipleRowData = Array( Array("hd_reply_id" => $t, "reply" => $reply , "help_desk_id" => $hd_id, "date" => $date,"time" => $time,"class" => "outt","user_id"=>$s_user_id));
if($r==0)
{
	echo'<span style="color:red;font-size:14px;">You have enter wrong word.</span>';	

}
else
{
 $this->help_desk_reply->saveAll($multipleRowData); 
}
$this->loadmodel('help_desk_reply');
$conditions=array("help_desk_id" => $hd_id);
$order=array('help_desk_reply.hd_reply_id'=>'ASC');
$this->set('result_reply',$this->help_desk_reply->find('all',array('conditions'=>$conditions,'order'=>$order)));


}



///////////////////////////////////////////////// Service Provider ///////////////////////////////...............................////////////////////////
function service_provider_add()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('help_desk_category');	
$order=array('help_desk_category.help_desk_category_name'=>'ASC');
$result=$this->help_desk_category->find('all',array('order'=>$order));
$this->set('result_help_desk_category',$result);
if($this->request->is('post')) 
{
@$file_upload=$this->request['form']['file']['name'];
$text=htmlentities($this->request->data['name']);	
$name=wordwrap($text, 25, " ", true);
$text1=htmlentities($this->request->data['person']);
$person = wordwrap($text1, 25, " ", true);
$mobile=$this->request->data['mobile'];
$email=$this->request->data['email'];
@$cont_start=$this->request->data['cont_start'];
@$cont_end=$this->request->data['cont_end'];

if(!empty($cont_start))
{
$contract_type="AMC";	
}
else
{
$contract_type="Adhoc";
}

$this->loadmodel('service_provider');
$i=$this->autoincrement('service_provider','sp_id');
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$this->service_provider->saveAll(array("sp_id" => $i, "sp_attachment" => $file_upload , "sp_name" => $name,"sp_date"=>$date,"user_id"=>$s_user_id,"society_id"=>$s_society_id,"sp_time"=>$time,"sp_delete"=>0,"sp_cont_start"=>$cont_start,"sp_cont_end"=>$cont_end,"sp_person"=>$person,"sp_email"=>$email,"sp_mobile"=>$mobile,"sp_contract_type"=>$contract_type));

$this->loadmodel('help_desk_category');
$result=$this->help_desk_category->find('all');
foreach ($result as $collection)
{ 
$id=$collection['help_desk_category']['help_desk_category_id'];
$servies=$collection['help_desk_category']['help_desk_category_name'];
@$check_id=(int)$this->request->data[$id];
if(!empty($check_id))
{
$this->loadmodel('vendor');
$j=$this->autoincrement('vendor','auto_id');
$this->vendor->saveAll(array("auto_id" => $j, "vendor_id" => $i, "category_id" =>  $check_id));
}
}

?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Successfully add service provider.
</div> 
<div class="modal-footer">
<a href="service_provider_view" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php			
}
}
function service_provider_vendor($auto_id)
{


$this->loadmodel('vendor');
$conditions=array("vendor_id" =>  $auto_id);
return $this->vendor->find('all',array('conditions'=>$conditions));                  


}

function service_provider_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_society_id=$this->Session->read('society_id');
$this->set('role_id',$s_role_id=$this->Session->read('role_id'));
$this->loadmodel('service_provider');
$condition=array("sp_delete"=>0,"society_id"=>$s_society_id);
$this->set('result_service_provider',$this->service_provider->find('all',array('conditions'=>$condition)));

}
function service_provider_delete()
{
$this->layout='blank';	
$id=(int)$this->request->query('con');
$this->loadmodel('service_provider');
$this->service_provider->updateAll(array('sp_delete'=>1),array('sp_id'=>$id));
$this->response->header('Location', 'service_provider_view');
}

function service_provider_mail()
{

$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$society_result= $this->society_name($s_society_id);
foreach($society_result as $data)
{
	$society_name=$data['society']['society_name'];
}
$subject=$society_name;
$text=htmlentities($this->request->query('con2'));
$message_web = wordwrap($text, 25, " ", true);
$to=$this->request->query('con3');
$this->loadmodel('user');
$conditions=array("user_id"=>$s_user_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
foreach ($result as $collection) 
{ 

$email=$collection['user']["email"];
}


$from_name="HousingMatters";
$from="support@housingmatters.in";
$reply=$email;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

}

function service_provider_edit()
{
$this->layout='session';
$id=(int)$this->request->query('con');
$this->loadmodel('service_provider');
$conditions=array("sp_id"=> $id);
$res= $this->service_provider->find('all',array('conditions'=>$conditions));
foreach ($res as $collection) 
{
$attachment=$collection['service_provider']['sp_attachment'];
$Contract_start=$collection['service_provider']['sp_cont_start'];
$Contract_end=$collection['service_provider']['sp_cont_end'];
}
$this->set('result_sp',$this->service_provider->find('all',array('conditions'=>$conditions))); 
if($this->request->is('post'))
{
@$file_upload=$this->request['form']['file']['name'];
if(empty($file_upload))
{
$file_upload=$attachment;
}
$text=htmlentities($this->request->data['name']);	
$name=wordwrap($text, 25, " ", true);
$text1=htmlentities($this->request->data['person']);
$person = wordwrap($text1, 25, " ", true);
$mobile=$this->request->data['mobile'];
$email=$this->request->data['email'];
@$cont_start=$this->request->data['cont_start'];
@$cont_end=$this->request->data['cont_end'];
$radio=$this->request->data['amc'];
if($radio==1)
{
$Contract_type="AMC";
}
else
{
$Contract_type="Adhoc";
}
if(empty($cont_start))
{
$cont_start= $Contract_start;
$cont_end= $Contract_end;
}
$this->loadmodel('service_provider');
$this->service_provider->updateAll(array("sp_name" => $name,"sp_mobile"=>$mobile,'sp_person'=> $person,"sp_email"=>$email,"sp_attachment"=>$file_upload,'sp_cont_start'=>$cont_start,'sp_cont_end'=> $cont_end,'sp_contract_type'=> $Contract_type),array("sp_id" => $id));
$this->response->header('location:service_provider_view');			

}

}
///////////////////////////////////////////////// Service Provider End ///////////////////////////////...............................////////////////////////
/////////////////////////////////////////////////////End Help Desk /////////////////////////



////////////////////////////////////// Notification email and Sms Start ///////////////////////////////////////

function notification_email()
{

$this->layout='session';
$user_id=$this->Session->read('user_id');	
$this->set('s_user_id',$user_id);
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result=$this->email->find('all',array('conditions'=>$conditions));
$this->set('result_email',$result);
if($this->request->is('post'))
{
foreach($result as $data)
{

$notification_id=(int)$data['email']['notification_id'];
$auto_id = (int)$data['email']['auto_id'];
$module_name = $data['email']['module_name']; 
$chk_email=@$this->request->data['check_email'.$auto_id];
$chk_sms=@$this->request->data['check_sms'.$auto_id];

if($chk_email==1)
{
$this->loadmodel('notification_email');
$conditions5=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions5));
$n= sizeof($result5);
if($n==0)
{
$this->loadmodel('notification_email');
$i=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $i, "module_id" => $auto_id , "user_id" => $user_id,'chk_status'=>0));
}

}
else
{
$this->loadmodel('notification_email');
$conditions6=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>0);
$this->notification_email->deleteAll($conditions6);
}

if($chk_sms==1)
{
$this->loadmodel('notification_email');
$conditions5=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>1);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions5));
$n= sizeof($result5);
if($n==0)
{
$this->loadmodel('notification_email');
$i=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $i, "module_id" => $auto_id , "user_id" => $user_id,'chk_status'=>1));
}
}
else
{
$this->loadmodel('notification_email');
$conditions6=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>1);
$this->notification_email->deleteAll($conditions6);
}

}


}



}


function notification_count_email($auto_id,$user_id)
{
$this->loadmodel('notification_email');
$conditions=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>0);
$result=$this->notification_email->find('all',array('conditions'=>$conditions));
return  $n= sizeof($result);
}


function notification_count_sms($auto_id,$user_id)
{
$this->loadmodel('notification_email');
$conditions=array('module_id'=>$auto_id,'user_id'=>$user_id,'chk_status'=>1);
$result=$this->notification_email->find('all',array('conditions'=>$conditions));
return  $n= sizeof($result);
}

/////////////////////////////////// End notification  //////////////////






////////////////////////////////// Start Yellow Page /////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////



function yellow_registration()
{
$this->layout='session';	
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 	
$this->set('role_id',$s_role_id=$this->Session->read('role_id'));
$this->loadmodel('yellow_category');
$result_yellow=$this->yellow_category->find('all');	
$this->set('result_yellow_category',$result_yellow);	

if($this->request->is('post'))
{


$file=$this->request->form['file']['name'];
if(empty($file))
{
$file='blank.jpg';
}
$name=htmlentities($this->request->data['name']);
$address=htmlentities($this->request->data['address']);
$mobile=htmlentities($this->request->data['mobile']);
$to=htmlentities($this->request->data['email']);
$website=htmlentities($this->request->data['website']);
$message_web="Thank you. You have been enroll in HousingMatters";
$this->loadmodel('email');
$condition=array('auto_id'=>7);
$result4=$this->email->find('all',array('conditions'=>$condition));
foreach($result4 as $data)
{
$from=$data['email']['from'];

}
$from_name="HousingMatters";
$subject="HousingMatters";
$reply=$from;

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/><p>Thank you. You have been enroll in HousingMatters</p><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";


$target = "yellow_page_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
foreach($result_yellow as $data)
{
$id=(int)$data['yellow_category']['yellow_cat_id'];
$servies=$data['yellow_category']['yellow_cat_name'];
$check_id=(int)@$this->request->data[$id];
if(!empty($check_id))
{
$category[]=$check_id;
}
}
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$i=$this->autoincrement('yellow_registration','yellowpage_id');
$this->loadmodel('yellow_registration');
$this->yellow_registration->saveAll(array("yellowpage_id" => $i, "yellowpage_attachment" => $file , "yellowpage_name" => $name,"yellowpage_date"=>$date,"yellowpage_category"=>$category,"user_id"=>$s_user_id,"society_id"=>$s_society_id,"yellowpage_time"=>$time,"yellowpage_delete"=>0,"yellowpage_website"=>$website,"yellowpage_address"=>$address,"yellowpage_email"=>$to,"yellowpage_mobile"=>$mobile));
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
//$this->smtpmailer($to,$from,$from_name,$subject,$message_web,$reply);


?>	

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
<?php echo $name; ?> successfully registered in HousingMatters.
</div> 
<div class="modal-footer">
<a href="yellow_page" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php 		 

}

}

function yellow_page()
{
$this->layout='session';	
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('yellow_category');
$result_yellow=$this->yellow_category->find('all');	
$this->set('result_yellow_category',$result_yellow);
$this->loadmodel('yellow_registration');
$conditions=array('yellowpage_delete'=>0);
$result=$this->yellow_registration->find('all',array('conditions'=>$conditions));
$this->set('result_ye_registration',$result);

}


function yellow_category_name($category)
{
$this->loadmodel('yellow_category');
$conditions=array('yellow_cat_id'=>$category);
return $this->yellow_category->find('all',array('conditions'=>$conditions));

}

function yellow_page_view()
{
$this->layout='blank';

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 	
$yellow_id=(int)$this->request->query('id');
$this->loadmodel('yellow_registration');
$conditions1=array('yellowpage_id'=>$yellow_id);
$this->set('result_yellow',$this->yellow_registration->find('all',array('conditions'=>$conditions1)));

}

function yellow_page_view_ajax()
{
$this->layout='blank';
$search_cat=(int)$this->request->query('con');
$this->set('search_value',$search_cat);
$this->loadmodel('yellow_registration');
$conditions=array('yellowpage_category'=>$search_cat);
$result= $this->yellow_registration->find('all',array('conditions'=>$conditions));
$this->set('count_yellow',sizeof($result));
$this->set('result_yellow',$result);

$this->loadmodel('yellow_registration');
$conditions1=array('yellowpage_delete'=>0);
$result1=$this->yellow_registration->find('all',array('conditions'=>$conditions1));
$this->set('result_ye_registration',$result1);


}


////////////////////////////  End Yellow Page //////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////	
/////////////////////////////////////////////////////start Message//////////////////////
////////////////////////////////////////////////////////////////////////////////////////	
function message()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 

$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id,'user.mobile'=> array('$ne' => ""));
$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('group');
$conditions=array("society_id"=>$s_society_id);
$result_group=$this->group->find('all',array('conditions'=>$conditions)); 
$this->set('result_group',$result_group); 

$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);
$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);


$this->loadmodel('template');
$conditions=array("cat"=>1);
$this->set('result_template1',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>2);
$this->set('result_template2',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>3);
$this->set('result_template3',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>4);
$this->set('result_template4',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>5);
$this->set('result_template5',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>6);
$this->set('result_template6',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>7);
$this->set('result_template7',$this->template->find('all',array('conditions'=>$conditions))); 

if (isset($this->request->data['send'])) 
{
$radio=$this->request->data['radio'];
$s_date=$this->request->data['date'];
$d = explode("-", $s_date);
$s_date_ex0=$d[0];
$s_date_ex1=$d[1];
$s_date_ex2=$d[2];
$time_h=$this->request->data['time_h'];
$time_m=$this->request->data['time_m'];
//$time_m=30;

$date=date("d-m-y");
$time=date('h:i:a',time());

$massage=$this->request->data['massage'];
$massage_str=str_replace(' ', '+', $massage);

$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($s_user_id)));
foreach ($result_user_info as $collection2) 
{
$name=$collection2["user"]["user_name"];
$wing=$collection2["user"]["wing"];
$flat=$collection2["user"]["flat"];
$sender_email=$collection2["user"]["email"];
}

if($radio==1)
{
$multi=$this->request->data['multi'];
$multi[]=$sender_email;
for($i=0; $i<sizeof($multi); $i++)
{
$multi_new=$multi[$i];
$ex = explode(",", $multi_new);
$mobile[]=$ex[1];
$user[]=$ex[0];
}
$mobile_im=implode(",", $mobile);
//$user=implode(",", $user); 

$s_date_ex0.$s_date_ex1.$s_date_ex2.$time_h.$time_m;
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile_im.'&message='.$massage_str.'&time='.$s_date_ex0.$s_date_ex1.$s_date_ex2.$time_h.$time_m);

	


$sms_id=$this->autoincrement('sms','sms_id');
$this->loadmodel('sms');
$multipleRowData = Array( Array("sms_id" => $sms_id,"text"=>$massage,"user_id"=>$user,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"type"=>1,"deleted"=>0));
$this->sms->saveAll($multipleRowData);
}

if($radio==2)
{
$user_new = array(); 
foreach ($result_group as $collection) 
{
$group_id=$collection["group"]["group_id"];

$g_id=@$this->request->data['grp'.$group_id];
if(!empty($g_id))
{
$groups_id[]=(int)$g_id;
$users=$collection["group"]["users"];
$user_new=array_merge($user_new,$users);
}
}
$result_user_unique = array_unique($user_new);


foreach ($result_user_unique as $data) 
{
$data=(int)$data;
$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($data)));
foreach ($result_user_info as $collection2) 
{
$mobile[]=$collection2["user"]["mobile"];
}
}
$mobile_im=implode(",", $mobile);

$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile_im.'&message='.$massage_str.'&time='.$s_date_ex0.$s_date_ex1.$s_date_ex2.$time_h.$time_m);

$sms_id=$this->autoincrement('sms','sms_id');
$this->loadmodel('sms');
$multipleRowData = Array( Array("sms_id" => $sms_id,"text"=>$massage,"user_id"=>$result_user_unique,"date"=>$date,"time"=>$timd,"type"=>2,"society_id"=>$s_society_id,"deleted"=>0));	

$this->sms->saveAll($multipleRowData);
}



if($radio==3)
{
$visible=(int)$this->request->data['visible'];
	if($visible==1)
	{	
	$visible=1;
	$sub_visible[]=0;
	/////////////////////////////////////////// All user ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['mobile'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All user ////////////////////////////
	}
	
	if($visible==4)
	{	
	$visible=4;
	$sub_visible[]=0;
	/////////////////////////////////////////// All Owners ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['mobile'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All Owners ////////////////////////////
	}
	
	if($visible==5)
	{
	$visible=5;
	$sub_visible[]=0;
	/////////////////////////////////////////// All Tenant ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['mobile'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All Tenant ////////////////////////////
	}
	
	if($visible==2)
	{	
	$visible=2;
	foreach ($role_result as $collection) 
	{
	$role_id=$collection["role"]["role_id"];

	$role_id=@(int)$this->request->data['role'.$role_id];
	if(!empty($role_id))
	{
	$sub_visible[]=(int)$role_id;

	/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
	$this->loadmodel('user');
	$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['mobile'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}

	//////////////////////////////// end mail ////////////////////////////////////////////////////////	


	}
	}
	$da_user_id=array_unique($da_user_id);
	}
	
	if($visible==3)
	{	
	$visible=3;
	foreach ($wing_result as $collection) 
	{
	$wing_id=$collection["wing"]["wing_id"];

	$wing=@(int)$this->request->data['wing'.$wing_id];
	if(!empty($wing))
	{
	$sub_visible[]=(int)$wing;


	/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
	$this->loadmodel('user');
	$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
		if(!empty($data['user']['mobile']))
		{
			$da_to[]=$data['user']['mobile'];
			$da_user_name[]=$data['user']['user_name'];
			$da_user_id[]=$data['user']['user_id'];
		}
	
	}
	}
	}
	}
$da_to[]=$sender_email;
$da_user_id=array_unique($da_user_id);	
$da_to=array_unique($da_to);	
$da_to=array_filter($da_to);
$mobile_im=implode(',',$da_to);


	
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile_im.'&message='.$massage_str.'&time='.$s_date_ex0.$s_date_ex1.$s_date_ex2.$time_h.$time_m);

$sms_id=$this->autoincrement('sms','sms_id');
$this->loadmodel('sms');
$multipleRowData = Array( Array("sms_id" => $sms_id,"text"=>$massage,"user_id"=>$da_user_id,"date"=>$date,"time"=>$time,"type"=>1,"society_id"=>$s_society_id,"deleted"=>0));	


$this->sms->saveAll($multipleRowData);

}

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your SMS has been Sent.
</div> 
<div class="modal-footer">
<a href="message_view" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	

}


}




function message_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 

$this->loadmodel('sms');
$conditions=array("society_id"=>$s_society_id,"deleted"=>0);
$order=array('sms.sms_id'=>'DESC');
$this->set('result_sms',$this->sms->find('all',array('conditions'=>$conditions,'order'=>$order))); 
}

function message_view_ajax()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 

$id=(int)$this->request->query('id');

$this->loadmodel('sms');
$conditions=array("sms_id"=>$id);
$this->set('result_smsview',$this->sms->find('all',array('conditions'=>$conditions))); 

}

//////////////////////////////////////////////////////////////////////////////
///////////////////////////EMAIL///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function email()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id,'user.email'=> array('$ne' => ""));
$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('group');
$conditions=array("society_id"=>$s_society_id);
$result_group=$this->group->find('all',array('conditions'=>$conditions)); 
$this->set('result_group',$result_group); 

$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);
$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);


$this->loadmodel('template');
$conditions=array("cat"=>1);
$this->set('result_template1',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>2);
$this->set('result_template2',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>3);
$this->set('result_template3',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>4);
$this->set('result_template4',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>5);
$this->set('result_template5',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>6);
$this->set('result_template6',$this->template->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('template');
$conditions=array("cat"=>7);
$this->set('result_template7',$this->template->find('all',array('conditions'=>$conditions))); 

if (isset($this->request->data['send'])) 
{
$radio=$this->request->data['radio'];
$message_db=$this->request->data['email'];
$file=$this->request->form['file']['name'];


$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result12=$this->society->find('all',array('conditions'=>$conditions12));
foreach($result12 as $data)
{
$s_name=$data['society']['society_name'];
}


$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($s_user_id)));
foreach ($result_user_info as $collection2) 
{
$name=$collection2["user"]["user_name"];
$wing=$collection2["user"]["wing"];
$flat=$collection2["user"]["flat"];
$sender_email=$collection2["user"]["email"];
}
$wing_flat=$this->wing_flat($wing,$flat);
$result_society_info= $this->society_name($s_society_id);
foreach($result_society_info as $data_info)
{
	$society_name=$data_info['society']['society_name'];
}

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<br/>
<div>$message_db</div>
<br/>
<div style='color: #7B7B7B;'>Regards,</div>
<div style='color: #7B7B7B;'>$name&nbsp;&nbsp;$wing_flat</div>
<div style='color: #7B7B7B;'>$society_name</div>
</div >
</div>";


if(!empty($file))
{
$message_web.='<br/><a href="http://123.63.2.150:8080/'.$this->webroot.'email_file/'.$file.'" download>Download attachment</a>';
}


$subject="[".$s_name."]-";
$subject.=htmlentities($this->request->data['subject']);



$target = "email_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 

$date=date("d-m-y");
$time=date('h:i:a',time());

if($radio==1)
{
$multi=$this->request->data['multi'];
$multi[]=$sender_email;

foreach($multi as $data)
{

$ex = explode(",", $data);
$user[]=$ex[0];
$to=$ex[1];
//echo $email[$i];
$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'support@housingmatters.in');
}




$email_id=$this->autoincrement('email_communication','email_id');
$this->loadmodel('email_communication');
$multipleRowData = Array( Array("email_id" => $email_id,"message_web"=>$message_web,"user_id"=>$user,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>1,"file"=>$file,"deleted"=>0));
$this->email_communication->saveAll($multipleRowData); 
}

if($radio==2)
{
$user_new = array(); 
foreach ($result_group as $collection) 
{
$group_id=$collection["group"]["group_id"];

$g_id=@$this->request->data['grp'.$group_id];
if(!empty($g_id))
{
$groups_id[]=(int)$g_id;
$users=$collection["group"]["users"];
$user_new=array_merge($user_new,$users);
}
}
$result_user_unique = array_unique($user_new);

foreach ($result_user_unique as $data) 
{
$data=(int)$data;
$result_user_info=$this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'), array('pass' => array($data)));
foreach ($result_user_info as $collection2) 
{
$to=$collection2["user"]["email"];
$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'support@housingmatters.in');
}
}




$email_id=$this->autoincrement('email_communication','email_id');
$this->loadmodel('email_communication');
$multipleRowData = Array( Array("email_id" => $email_id,"message_web"=>$message_web,"user_id"=>$result_user_unique,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"groups_id"=>$groups_id,"type"=>2,"file"=>$file,"deleted"=>0));
$this->email_communication->saveAll($multipleRowData); 
}

if($radio==3)
{
$visible=(int)$this->request->data['visible'];
	if($visible==1)
	{	
	$visible=1;
	$sub_visible[]=0;
	/////////////////////////////////////////// All user ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['email'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All user ////////////////////////////
	}
	
	if($visible==4)
	{	
	$visible=4;
	$sub_visible[]=0;
	/////////////////////////////////////////// All Owners ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['email'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All Owners ////////////////////////////
	}
	
	if($visible==5)
	{
	$visible=5;
	$sub_visible[]=0;
	/////////////////////////////////////////// All Tenant ////////////////////////////
	$this->loadmodel('user');
	$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['email'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	/////////////////////////////////////////// All Tenant ////////////////////////////
	}
	
	if($visible==2)
	{	
	$visible=2;
	foreach ($role_result as $collection) 
	{
	$role_id=$collection["role"]["role_id"];

	$role_id=@(int)$this->request->data['role'.$role_id];
	if(!empty($role_id))
	{
	$sub_visible[]=(int)$role_id;

	/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
	$this->loadmodel('user');
	$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['email'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}

	//////////////////////////////// end mail ////////////////////////////////////////////////////////	


	}
	}
	$da_user_id=array_unique($da_user_id);
	}
	
	if($visible==3)
	{	
	$visible=3;
	foreach ($wing_result as $collection) 
	{
	$wing_id=$collection["wing"]["wing_id"];

	$wing=@(int)$this->request->data['wing'.$wing_id];
	if(!empty($wing))
	{
	$sub_visible[]=(int)$wing;


	/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
	$this->loadmodel('user');
	$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
	$result_user=$this->user->find('all',array('conditions'=>$conditions));
	foreach($result_user as $data)
	{
	$da_to[]=$data['user']['email'];
	$da_user_name[]=$data['user']['user_name'];
	$da_user_id[]=$data['user']['user_id'];
	}
	}
	}
	}
$da_to[]=$sender_email;
$da_user_id=array_unique($da_user_id);	
$da_to=array_unique($da_to);
$da_to=array_filter($da_to);


foreach($da_to as $data)
{

$ex = explode(",", $data);
if(!empty($ex[0])) { $to=$ex[0]; }


//echo $email[$i];
$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'support@housingmatters.in');
}




$email_id=$this->autoincrement('email_communication','email_id');
$this->loadmodel('email_communication');
$multipleRowData = Array( Array("email_id" => $email_id,"message_web"=>$message_web,"user_id"=>$da_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>1,"file"=>$file,"deleted"=>0));
$this->email_communication->saveAll($multipleRowData); 

}


?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Email has been sent.
</div> 
<div class="modal-footer">
<a href="email_view" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	

}
}


function email_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 

$this->loadmodel('email_communication');
$conditions=array("society_id"=>$s_society_id,"deleted"=>0);
$order=array('email_communication.email_id'=>'DESC');
$this->set('result_email',$this->email_communication->find('all',array('conditions'=>$conditions,'order'=>$order))); 
}



function email_view_ajax()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('society');
$conditions12=array('society_id'=>$s_society_id);
$result12=$this->society->find('all',array('conditions'=>$conditions12));
foreach($result12 as $data)
{
$this->set('s_name',$data['society']['society_name']);
}


$id=(int)$this->request->query('id');

$this->loadmodel('email_communication');
$conditions=array("email_id"=>$id);
$this->set('result_eamilview',$this->email_communication->find('all',array('conditions'=>$conditions))); 

}

function email_delete()
{
$this->layout='blank';

$id=(int)$this->request->query('id');

$this->loadmodel('email_communication');
$this->email_communication->updateAll(array("deleted" => 1),array("email_id" => $id));

$this->response->header('Location', 'email_view');
}

function sms_delete()
{
$this->layout='blank';

$id=(int)$this->request->query('id');

$this->loadmodel('sms');
$this->sms->updateAll(array("deleted" => 1),array("sms_id" => $id));

$this->response->header('Location', 'message_view');
}

function email_view_pdf()
{
//$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$this->ath(); 

$con=(int)$this->request->query('con');
$this->set('con',$con);

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('email_communication');
$conditions=array("email_id"=>$con);
$this->set('result_eamilview',$this->email_communication->find('all',array('conditions'=>$conditions))); 
}

function sms_view_pdf()
{
//$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$this->ath(); 

$con=(int)$this->request->query('con');
$this->set('con',$con);

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('sms');
$conditions=array("sms_id"=>$con);
$this->set('result_smsview',$this->sms->find('all',array('conditions'=>$conditions))); 
}

////////////////////////////////////////////////////////////////////////////////////////	
/////////////////////////////////////////////////////start groups//////////////////////
////////////////////////////////////////////////////////////////////////////////////////
function groups()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath();
$this->check_user_privilages();
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 

if (isset($this->request->data['add'])) 
{
	$group_name=$this->request->data['group_name'];

	$this->loadmodel('group');
	$conditions=array("society_id"=>$s_society_id,"group_name"=>$group_name);
	$group_duplicate=$this->group->find('count',array('conditions'=>$conditions));

	
	if(!empty($group_name) and ($group_duplicate==0))
	{
	$group_id=$this->autoincrement('group','group_id');
	$this->loadmodel('group');
	$multipleRowData = Array( Array("group_id" => $group_id,"group_name"=>$group_name,"society_id"=>$s_society_id,"users"=>array()));
	$this->group->saveAll($multipleRowData); 
	$this->response->header('Location', 'groupview?gid='.$group_id);
	}
	else{
		$this->set('error_addgroup','Group name should not be duplicate.');
	}
}

$this->loadmodel('group');
$conditions=array("society_id"=>$s_society_id);
$order=array('group.group_id'=>'DESC');
$this->set('result_group',$this->group->find('all',array('conditions'=>$conditions,'order'=>$order))); 
}

function groupview() 
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
	$this->ath();
	//$this->check_user_privilages();
	$s_user_id=$this->Session->read('user_id'); 
	$s_society_id=$this->Session->read('society_id'); 
	$gid=(int)$this->request->query('gid');
	$this->set('gid',$gid);
	$group_name=$this->fetch_group_name_from_gruop_id($gid);
	$this->set('group_name',$group_name);
	
	if (isset($this->request->data['update_members'])) 
	{
		$all_users=$this->all_user_deactive();
		$members=array();
		foreach($all_users as $user)
		{
		
			$value=@$this->request->data['user'.$user['user']['user_id']];
			if(!empty($value)) { $members[]=$user['user']['user_id']; }
		}
		
		$this->loadmodel('group');
		$this->group->updateAll(array("users" =>$members),array("group_id" => $gid));
	}
	
	$this->loadmodel('group');
	$conditions=array("group_id" => $gid);
	$result_group_info=$this->group->find('all',array('conditions'=>$conditions));
	
	$result_group_info=$result_group_info[0]['group']['users'];

	$this->set('result_group_info',$result_group_info);
	$this->set('all_users',$this->all_user_deactive());
}


function fetch_group_name_from_gruop_id($group_id)
{


$this->loadmodel('group');
$conditions=array("group_id" => $group_id);
$result_group_name=$this->group->find('all',array('conditions'=>$conditions));

foreach ($result_group_name as $collection) 
{
return $group_name=$collection['group']['group_name'];
}
}




////////////////////////////////////////////////////////////////////////////////////////	
/////////////////////////////////////////////////////start discussion//////////////////////
////////////////////////////////////////////////////////////////////////////////////////	
function discussion_forum()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	
$t=(int)$this->request->query('t');
$this->set('t',$t);
$list=(int)$this->request->query('list');
$this->set('list',$list);
$new=(int)$this->request->query('new');
$this->set('new',$new);

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id');
$this->set('s_user_id',$s_user_id);
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');

$this->seen_notification(3,$t);

//////////////////////current user info///////////////
$result_self=$this->profile_picture($s_user_id);
foreach($result_self as $data3)
{
$this->set('user_name',$data3["user"]["user_name"]);
$wing=$data3["user"]["wing"];
$flat=$data3["user"]["flat"];
}
$this->set('flat_info',$this->wing_flat($wing,$flat));
//////////////////////current user info///////////////

$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);

$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);



///////////////////////start new topic//////////////////////////////////
if ($this->request->is('post')) 
{
$text=htmlentities($this->request->data['topic']);
$topic = wordwrap($text, 25, " ", true);

$text12=htmlentities($this->request->data['description']);
$description = nl2br(wordwrap($text12, 25, " ", true));

$file=$this->request->form['file']['name'];

$target = "discussion_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 

$date=date("d-m-y");
$time=date('h:i:a',time());

$visible=(int)$this->request->data['visible'];
if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible[]=0;
/////////////////////////////////////////// All Owners ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible[]=0;
/////////////////////////////////////////// All Tenant ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;

/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	


}
}
$da_to=array_unique($da_to);
}

if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=$collection["wing"]["wing_id"];

$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;


/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	



}
}

}

$discussion_post_id=$this->autoincrement('discussion_post','discussion_post_id');
$this->loadmodel('discussion_post');
$multipleRowData = Array( Array("discussion_post_id" => $discussion_post_id, "user_id" => $s_user_id , "society_id" => $s_society_id, "topic" => $topic,"description" => $description, "file" =>$file,"delete_id" =>0, "date" =>$date, "time" => $time, "visible" => $visible, "sub_visible" => $sub_visible,"users"=>$da_user_id));
$this->discussion_post->saveAll($multipleRowData); 
$this->response->header('Location', 'discussion_delete_topic');

$this->send_notification('<span class="label" style="background-color:#269abc;"><i class="icon-comment"></i></span>','New Discussion <b>'.$topic.'</b> created by',3,$discussion_post_id,'discussion_forum?t='.$discussion_post_id.'&list=0',$s_user_id,$da_user_id);


////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$this->loadmodel('email');
$conditions=array('auto_id'=>10);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$reply="donotreply@housingmatters.in";
$from_name="HousingMatters";
$sub="New Topic";
$result= $this->society_name($s_society_id);
foreach($result as $data)
{
	$society_name=$data['society']['society_name'];
	$dis_email_setting=$data['society']['discussion_forum_email'];

}

$result_user=$this->profile_picture($s_user_id);
foreach($result_user as $data1)
{
$user_name_post=$data1['user']['user_name'];
$wing=$data1['user']['wing'];
$flat=$data1['user']['flat'];

}
$wing_flat=$this->wing_flat($wing,$flat);
if($dis_email_setting==1)
{
for($k=0;$k<sizeof($da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];	

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Hello  $user_name </p>
<p>A new topic is posted in your society Discussion Forum.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>New Discussion Topic</td>
<td>Posted by</td>
<td>Flat #</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$topic</td>
<td>$user_name_post</td>
<td>$wing_flat</td>
</tr>
</table>
<div>
<br/>
<center><p>To view or post response
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>10,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . '  ' .'     '.' '.$sub.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}
}


////////////////////////////////////////////End Mail Functionality //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


}
///////////////////////End start new topic//////////////////////////////////


$this->loadmodel('discussion_post');

if($list==0 or empty($list))
{
	$conditions =array( '$or' => array( 
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
	));
}
if($list==1)
{
	$conditions =array( '$or' => array( 
	array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>1),
	array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
	array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
	array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>4),
	array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>5)
	));
}
if($list==2)
{
	$conditions =array( '$or' => array( 
	array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>1),
	array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
	array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
	array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>4),
	array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>5)
	));
}

$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_discussion',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order)));   


$this->loadmodel('discussion_post');
if(empty($t)){
	$conditions =array( '$or' => array( 
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
	array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
	));
}
else{
	$this->loadmodel('discussion_post');
	$conditions=array('discussion_post_id' =>$t,'users' =>array('$in' => array($s_user_id)));
	$count=$this->discussion_post->find('count',array('conditions'=>$conditions));
	if($count>0){	$conditions=array('discussion_post_id' =>$t);	}
	else{
		
		$conditions =array( '$or' => array( 
		array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
		array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
		array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
		array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
		array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
		));
	
	}
}

$order=array('discussion_post.discussion_post_id'=>'DESC');
$result_discussion_last=$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
foreach($result_discussion_last as $data2)
{
$discussion_post_id=(int)$data2["discussion_post"]["discussion_post_id"];
}
$this->set('result_discussion_last',$result_discussion_last);
$this->set('last_discussion_post_id',@$discussion_post_id); 	

$this->loadmodel('discussion_comment');
$conditions =array( '$or' => array( 
array('discussion_post_id' =>@$discussion_post_id,'delete_id' =>0),array('discussion_post_id' =>@$discussion_post_id,'delete_id' =>2)));
//$conditions=array("discussion_post_id"=>@$discussion_post_id,"delete_id"=>0);
$this->set('result_comment_last',$this->discussion_comment->find('all',array('conditions'=>$conditions))); 
}

function discussion()
{
if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->ath(); 
$this->check_user_privilages();

$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id');
$this->set('s_user_id',$s_user_id);
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');

//////////////////////current user info///////////////
$result_self=$this->profile_picture($s_user_id);
foreach($result_self as $data3)
{
$this->set('user_name',$data3["user"]["user_name"]);
$wing=$data3["user"]["wing"];
$flat=$data3["user"]["flat"];
}
$this->set('flat_info',$this->wing_flat($wing,$flat));
//////////////////////current user info///////////////

$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);

$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
$this->set('wing_result',$wing_result);



///////////////////////start new topic//////////////////////////////////
if ($this->request->is('post')) 
{
$text=htmlentities($this->request->data['topic']);
$topic = wordwrap($text, 25, " ", true);

$text12=htmlentities($this->request->data['description']);
$description = nl2br(wordwrap($text12, 25, " ", true));

$file=$this->request->form['file']['name'];

$target = "discussion_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 

$date=date("d-m-y");
$time=date('h:i:a',time());

$visible=(int)$this->request->data['visible'];
if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible[]=0;
/////////////////////////////////////////// All Owners ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible[]=0;
/////////////////////////////////////////// All Tenant ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;

/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	


}
}
$da_to=array_unique($da_to);
}

if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=$collection["wing"]["wing_id"];

$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;


/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	



}
}

}

$discussion_post_id=$this->autoincrement('discussion_post','discussion_post_id');
$this->loadmodel('discussion_post');
$multipleRowData = Array( Array("discussion_post_id" => $discussion_post_id, "user_id" => $s_user_id , "society_id" => $s_society_id, "topic" => $topic,"description" => $description, "file" =>$file,"delete_id" =>0, "date" =>$date, "time" => $time, "visible" => $visible, "sub_visible" => $sub_visible));
$this->discussion_post->saveAll($multipleRowData); 
$this->response->header('Location', 'discussion_delete_topic');

$this->send_notification('<span class="label" style="background-color:#269abc;"><i class="icon-comment"></i></span>','New Discussion <b>'.$topic.'</b> created by',3,$discussion_post_id,'discussion',$s_user_id,$da_user_id);


////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$this->loadmodel('email');
$conditions=array('auto_id'=>10);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$reply="donotreply@housingmatters.in";
$from_name="HousingMatters";
$sub="New Topic";
$result= $this->society_name($s_society_id);
foreach($result as $data)
{
	$society_name=$data['society']['society_name'];
	$dis_email_setting=$data['society']['discussion_forum_email'];

}

$result_user=$this->profile_picture($s_user_id);
foreach($result_user as $data1)
{
$user_name_post=$data1['user']['user_name'];
$wing=$data1['user']['wing'];
$flat=$data1['user']['flat'];

}
$wing_flat=$this->wing_flat($wing,$flat);
if($dis_email_setting==1)
{
for($k=0;$k<sizeof($da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];	

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Hello  $user_name </p>
<p>A new topic is posted in your society Discussion Forum.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>New Discussion Topic</td>
<td>Posted by</td>
<td>Flat #</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$topic</td>
<td>$user_name_post</td>
<td>$wing_flat</td>
</tr>
</table>
<div>
<br/>
<center><p>To view or post response
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>10,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . '  ' .'     '.' '.$sub.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}
}


////////////////////////////////////////////End Mail Functionality //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


}
///////////////////////End start new topic//////////////////////////////////


$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_discussion',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order)));   


$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$result_discussion_last=$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
foreach($result_discussion_last as $data2)
{
$discussion_post_id=(int)$data2["discussion_post"]["discussion_post_id"];
}
$this->set('result_discussion_last',$result_discussion_last);
$this->set('last_discussion_post_id',@$discussion_post_id); 	

$this->loadmodel('discussion_comment');
$conditions =array( '$or' => array( 
array('discussion_post_id' =>@$discussion_post_id,'delete_id' =>0),array('discussion_post_id' =>@$discussion_post_id,'delete_id' =>2)));
//$conditions=array("discussion_post_id"=>@$discussion_post_id,"delete_id"=>0);
$this->set('result_comment_last',$this->discussion_comment->find('all',array('conditions'=>$conditions))); 
}


function discussion_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$this->ath(); 

$con=(int)$this->request->query('con');
$this->set('con',$con);

$s_user_id=$this->Session->read('user_id'); 
$this->set('s_user_id',$s_user_id);
$s_society_id=$this->Session->read('society_id'); 


$this->loadmodel('discussion_post');
$conditions=array("discussion_post_id"=>$con);
$this->set('result_topic_view',$this->discussion_post->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('discussion_comment');
$conditions=array("discussion_post_id"=>$con,"delete_id"=>0);
$order=array('discussion_comment.discussion_comment_id'=>'ASC');
$this->set('result_comment',$this->discussion_comment->find('all',array('conditions'=>$conditions,'order'=>$order))); 

}



function discussion_delete_topic()
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id'); 

$con=(int)$this->request->query('con');
if($con==0) { $this->response->header('Location', 'discussion_forum'); }

$this->loadmodel('discussion_post');
$this->discussion_post->updateAll(array("delete_id" =>1),array("discussion_post_id" => $con));
$this->response->header('Location', 'discussion_forum');
}

function discussion_comment_delete_ajax()
{
$this->layout='blank';

$s_society_id=$this->Session->read('society_id'); 

$c_id=(int)$this->request->query('c_id');

$this->loadmodel('discussion_comment');
$this->discussion_comment->updateAll(array("delete_id" =>1),array("discussion_comment_id" => $c_id));
//$this->response->header('Location', 'discussion');
}



function discussion_delete_topic_archive()
{
	$this->layout='blank';
	$s_society_id=$this->Session->read('society_id'); 
	$con=(int)$this->request->query('con');
	if($con==0) { $this->response->header('Location', 'discussion'); }
	$this->loadmodel('discussion_post');
	$this->discussion_post->updateAll(array("delete_id" =>2),array("discussion_post_id" => $con));
	$this->response->header('Location', 'discussion');
	
}


function discussion_my_topic()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id');
$this->set('s_user_id',$s_user_id);
$s_society_id=$this->Session->read('society_id'); 

$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');

$q=(int)$this->request->query('q');
$this->set('q',$q);

if($q==1)
{
$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>1),
array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>4),
array('user_id' =>$s_user_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_my_topic',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order)));   
}

if($q==2)
{
$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_all_topic',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order)));   
}

if($q==3)
{
$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>1),
array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>4),
array('society_id' =>$s_society_id,'delete_id' =>1,'visible' =>5)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_deleted_topic',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order)));   
}
}

function discussion_search_topic()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id');

$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');

$s=$this->request->query('s');
$regex = new MongoRegex("/.*$s.*/i");


$this->loadmodel('discussion_post');
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>1,'topic' =>$regex),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>2,'topic' =>$regex,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>3,'topic' =>$regex,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>4,'topic' =>$regex),
array('society_id' =>$s_society_id,'delete_id' =>0,'visible' =>5,'topic' =>$regex)
));
$order=array('discussion_post.discussion_post_id'=>'DESC');
$this->set('result_all_topic',$this->discussion_post->find('all',array('conditions'=>$conditions,'order'=>$order))); 

}

function topic_view()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$this->set('s_user_id',$s_user_id);
$s_society_id=$this->Session->read('society_id'); 
$t=(int)$this->request->query('t');
$this->set('t',$t);

$this->loadmodel('discussion_post');
$conditions=array("discussion_post_id"=>$t);
$this->set('result_topic_view',$this->discussion_post->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('discussion_comment');
//$conditions=array("discussion_post_id"=>$t,"delete_id"=>0);
$conditions =array( '$or' => array( 
array('discussion_post_id' =>$t,'delete_id' =>0),array('discussion_post_id' =>$t,'delete_id' =>2)));

$order=array('discussion_comment.discussion_comment_id'=>'ASC');
$this->set('result_comment',$this->discussion_comment->find('all',array('conditions'=>$conditions,'order'=>$order))); 
}

function discussion_comment_refresh()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$this->set('s_user_id',$s_user_id);
$s_society_id=$this->Session->read('society_id'); 
$t_id=(int)$this->request->query('con');
$this->set('t_id',$t_id);

$this->loadmodel('discussion_comment');
//$conditions=array("discussion_post_id"=>$t_id,"delete_id"=>0);
$conditions =array( '$or' => array( 
array('discussion_post_id' =>$t_id,'delete_id' =>0),array('discussion_post_id' =>$t_id,'delete_id' =>2)));
$order=array('discussion_comment.discussion_comment_id'=>'ASC');
$this->set('result_comment_ref',$this->discussion_comment->find('all',array('conditions'=>$conditions,'order'=>$order)));
}


function discussion_offensive_delete_ajax()
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id'); 
$co_id=(int)$this->request->query('c_id');
$c_u_id=(int)$this->request->query('c_u_id');
$this->loadmodel('discussion_comment');
$conditions=array('discussion_comment_id' => $co_id);
$result= $this->discussion_comment->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
$r=$data['discussion_comment']['offensive_user'];	
}
if(!empty($r))
{
array_push($r,$c_u_id);
}
else
{
$r=array($c_u_id);
}
$this->loadmodel('discussion_comment');
$this->discussion_comment->updateAll(array("delete_id" =>2,'offensive_user'=>$r),array("discussion_comment_id" => $co_id));

}

function discussion_offensive_view()
{
$this->layout="session";
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('discussion_comment');
$conditions=array('society_id'=>$s_society_id,'delete_id'=>2);
$result=$this->discussion_comment->find('all',array('conditions'=>$conditions));
$this->set('result_discussion_comment',$result);	
}


function discussion_offensive_delete_ajax1()
{
$this->layout="blank";
$co_id=(int)$this->request->query('con');
$this->loadmodel('discussion_comment');
$this->discussion_comment->updateAll(array("delete_id" =>3),array("discussion_comment_id" => $co_id));
$this->response->header('Location', 'discussion_offensive_view');

}


function topic_view_deleted()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 
$t=(int)$this->request->query('t');
$this->set('t',$t);

$this->loadmodel('discussion_post');
$conditions=array("discussion_post_id"=>$t);
$this->set('result_topic_view',$this->discussion_post->find('all',array('conditions'=>$conditions))); 

$this->loadmodel('discussion_comment');
$conditions=array("discussion_post_id"=>$t,"delete_id"=>0);
$this->set('result_comment',$this->discussion_comment->find('all',array('conditions'=>$conditions))); 
}


function discussion_save_comment()
{
$this->layout='blank';
$this->ath(); 
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 
$tid=(int)$this->request->query('tid');
$g=$this->request->query('c');
$c=htmlentities(wordwrap($g, 25, " ", true));
$c_mod=explode(' ',$g);
$c=nl2br($c);
$date=date("d-m-y");
$time=date('h:i:a',time());
$r=$this->content_moderation_society($c_mod);

if($r==0)
{
echo $word='You have enter wrong word  <br/> ';
exit;
	
}
else
{
	
$this->loadmodel('discussion_comment');
$conditions=array("delete_id"=>0);
$order=array('discussion_comment.discussion_comment_id'=>'DESC');
$cursor_last_color=$this->discussion_comment->find('all',array('conditions'=>$conditions,'order'=>$order,'limit'=>1));
foreach ($cursor_last_color as $collection_color) 
{
$last_color=$collection_color["discussion_comment"]["color"];
}
if(sizeof($cursor_last_color)==0) {  $last_color='blue'; }
$color_in=$this->rendom_color($last_color);
//////////////////end color///////////////////

$discussion_comment_id=$this->autoincrement('discussion_comment','discussion_comment_id');
$this->loadmodel('discussion_comment');
$multipleRowData = Array( Array("discussion_comment_id" => $discussion_comment_id, "user_id" => $s_user_id , "society_id" => $s_society_id, "comment" => $c,"discussion_post_id" => $tid, "delete_id" =>0, "date" =>$date, "time" => $time, "color" => $color_in));
$this->discussion_comment->saveAll($multipleRowData); 

	
}


 //////////////// Moderation content check start ///////////////////////////
/*
$this->loadmodel('society');
$conditions=array('society_id'=>$s_society_id);
$result1=$this->society->find('all',array('conditions'=>$conditions));
foreach($result1 as $data)
{
  $content=$data['society']['content_moderation'];

}


foreach($c_mod as $c_moda)
{
if(in_array($c_moda,$content))
{
echo $word='You have enter wrong word  <br/> ';
exit;
}
}
*/
//////////////////color///////////////////




////////////////// Modaration content check End ///////////////////////


}

function count_comment_of_topic($id)
{
$this->layout='blank';

$this->loadmodel('discussion_comment');
//$conditions=array("discussion_post_id"=>$id,"delete_id" => 0);
$conditions =array( '$or' => array( 
array('discussion_post_id' =>$id,'delete_id' =>0),array('discussion_post_id' =>$id,'delete_id' =>2)));
return $this->discussion_comment->find('count',array('conditions'=>$conditions)); 
}

///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////end of discussion forum//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////  ALL  Repotr start /////////////////////////////////////


function all_report()
{

$this->layout='session';	




}

function contact_report()
{
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('user');
$conditions=array('society_id'=>$s_society_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user',$result);


}

function log_user($da_user_id)
{
	
$this->loadmodel('log');
$conditions=array('user_id'=>$da_user_id);
return $result=$this->log->find('all',array('conditions'=>$conditions));	

}


function log_all_report()
{
	$this->layout='session';
	$id=(int)$this->request->query('con');
	$this->loadmodel('log');
	$conditions=array('user_id'=>$id,'status'=>0);
	$order=array('log.log_id'=> 'DESC');
	$result=$this->log->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_log',$result);
}


function login_report_user()
{

$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('user');
$conditions=array('society_id'=>$s_society_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user',$result);

}







function login_report_unit()
{

$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('user');
$conditions=array('society_id'=>$s_society_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user',$result);
}


function complaint_closed_report()
{

$this->layout="session";	
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('help_desk');
$conditions=array("help_desk_status" =>1,"society_id" => $s_society_id);
$order=array('help_desk.ticket_id'=> 'DESC');
$result=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result);



}


function complaint_open_report()
{
$this->layout="session";
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('help_desk');
$conditions=array("help_desk_status" => 0,"society_id" => $s_society_id);
$order=array('help_desk.ticket_id'=> 'DESC');
$result_help_desk=$this->help_desk->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('result_help_desk',$result_help_desk);

}



function sp_performance_report()
{
	

$this->layout="session";
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


if($this->request->is('post')) 
{
 $this->set('date1',$this->request->data['from']);
 $this->set('date2',$this->request->data['to']);
 
 $this->loadmodel('help_desk');
$conditions=array('society_id'=>$s_society_id,'help_desk.help_desk_service_provider_id'=> array('$ne' => 0));
$result12=$this->help_desk->find('all',array('conditions'=>$conditions));
$this->set('result_help_desk',$result12);
	
}

}


function sp_performance_report_pdf()
{

$this->layout="pdf";
$this->layout="session";
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
 $date1=$this->request->query('con');
 $date2=$this->request->query('con2');

$this->loadmodel('help_desk');
$conditions=array('society_id'=>$s_society_id,'help_desk.help_desk_service_provider_id'=> array('$ne' => 0));
$result12=$this->help_desk->find('all',array('conditions'=>$conditions));
$this->set('result_help_desk',$result12);
App::import('Vendor','xtcpdf');  
$tcpdf = new XTCPDF(); 
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
$tcpdf->SetAutoPageBreak( true ); 
//$tcpdf->setHeaderFont(array($textfont,'',40)); 
$tcpdf->xheadercolor = array(255,255,255); 
$tcpdf->xheadertext = ''; 
$tcpdf->xfootertext = 'HousingMatters'; 
$tcpdf->AddPage(); 
$tcpdf->SetTextColor(0, 0, 0); 
$tcpdf->SetFont($textfont,'N',12);
$html='
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Service Provider Performance Report
</div>
<br>
<table><tr><th><b>Sr No.</b></th>
<th><b>Ticket</b></th>
<th><b>Service Provider</b></th>
<th><b>Assigned Date</b></th>
<th><b>Closure Date</b></th>
<th><b>Average</b></th></tr>';
$i=0;
foreach($result12 as $data)
{
 $avg='';
$assign_date=$data['help_desk']['help_desk_assign_date'];
$close_date=@$data['help_desk']['help_desk_close_date'];
$help_desk_date=$data['help_desk']['help_desk_date'];
$sp_id=$data['help_desk']['help_desk_service_provider_id'];
$ticket_id=$data['help_desk']['ticket_id'];
 $help_desk_date1=date("d-m-y", strtotime($help_desk_date));
 $help_desk_date2 = date("Y-m-d", strtotime($help_desk_date1));
 $help_desk_date3 = date("d-m-Y", strtotime($help_desk_date2));

if(!empty($assign_date) && !empty($close_date))
{
$newDate = date("d-m-y", strtotime($assign_date));
$newDate1 = date("Y-m-d", strtotime($newDate));
$newDate2 = date("d-m-y", strtotime($close_date));
$newDate3 = date("Y-m-d", strtotime($newDate2));
$datetime1 = date_create($newDate1);
$datetime2 = date_create($newDate3);
$interval = date_diff($datetime1, $datetime2);
$avg= $interval->format('%R%a days');
}
$sp=$this->fetch_service_provider_info_via_vendor_id($sp_id);
foreach($sp as $data)
{
	$sp_name=$data['service_provider']['sp_name'];
	
}
if(strtotime($date1)<=strtotime($help_desk_date3) && strtotime($date2)>=strtotime($help_desk_date3))
{
$i++;
$html.='
<tr>
<td>'.$i .'</td>
<td>'.$ticket_id.'</td>
<td>'.$sp_name.'</td>
<td>'.$assign_date.'</td>
<td>'.$close_date.'</td>
<td>'.$avg.'</td></tr>
';
} }
$html.="</table>";
$tcpdf->writeHTML($html);
echo $tcpdf->Output('sp_report.pdf', 'D'); 
}

////////////////////////////////////////////  End Report  ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////// Resource Start ////////////////////////////////////////////////////////////	

function resource_add()
{

	$this->layout='session';
	$this->ath();
	$this->check_user_privilages();
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$this->set('role_id',$s_role_id=$this->Session->read('role_id')); 
	$this->loadmodel('resource_category');
	$this->set('result_resource_category',$this->resource_category->find('all'));  
	$this->loadmodel('role');
	$conditions=array("society_id" => $s_society_id);
	$role_result=$this->role->find('all',array('conditions'=>$conditions));
	$this->set('role_result',$role_result);
	$this->loadmodel('wing');
	$wing_result=$this->wing->find('all');
	$this->set('wing_result',$wing_result);


	$result=$this->society_name($s_society_id);
	foreach($result as $data)
	{
	@$document=$data['society']['document'];

	}
	if($document==1)
	{		

				
			if($this->request->is('post'))
			{
				$resource_title= $this->request->data['title'];
				$resource_cat= (int)$this->request->data['sel'];
				$resource_att=$this->request->form['file']['name'];
				$i=$this->autoincrement('resource','resource_id');
				$visible=(int)$this->request->data['visible'];
				
				
					if($visible==1)
					{	
					$visible=1;
					$sub_visible[]=0;
					}
					
					if($visible==4)
					{	
					$visible=4;
					$sub_visible=1;
					}
					
					if($visible==5)
					{
					$visible=5;
					$sub_visible=2;
					}
					
					if($visible==2)
					{	
						$visible=2;
						foreach ($role_result as $collection) 
						{
							$role_id=$collection["role"]["role_id"];

							$role_id=@(int)$this->request->data['role'.$role_id];
							if(!empty($role_id))
							{
							$sub_visible[]=(int)$role_id;
							}
						}
					}
					
						
					if($visible==3)
					{	
					 $visible=3;
						foreach ($wing_result as $collection) 
						{
							$wing_id=(int)$collection["wing"]["wing_id"];

							$wing=@(int)$this->request->data['wing'.$wing_id];
							if(!empty($wing))
							{
								$sub_visible[]=(int)$wing;
							}
						}
					}
					
							
				$date=date("d-m-Y");
				$time=date('h:i:a',time());
				$target = "resource_file/";
				$target=@$target.basename( @$this->request->form['file']['name']);
				$ok=1;
				move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
					
				$this->loadmodel('resource');
				$this->resource->saveAll(array("resource_id" => $i, "resource_attachment" => $resource_att , "resource_title" => $resource_title,"resource_date"=>$date,"resource_category"=>$resource_cat,"user_id"=>$s_user_id,"society_id"=>$s_society_id,"resource_time"=>$time,"resource_delete"=>4,"visible"=>$visible,"sub_visible"=>$sub_visible));	
				?>
                

				<!----alert-------------->
				<div class="modal-backdrop fade in"></div>
				<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
				<div class="modal-body" style="font-size:16px;">
				Documents are sent for approval
				</div> 
				<div class="modal-footer">
				<a href="resource_view" class="btn green">OK</a>
				</div>
				</div>
				<!----alert-------------->
								
                
                
                
                <?php		
			
			
			
			}
	}
	else
	{
	
	
if($this->request->is('post'))
{
$resource_title= $this->request->data['title'];
$resource_cat= (int)$this->request->data['sel'];
$resource_att=$this->request->form['file']['name'];
$i=$this->autoincrement('resource','resource_id');
$visible=(int)$this->request->data['visible'];	
$date=date("d-m-Y");
$time=date('h:i:a',time());
$target = "resource_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 

if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user= $this->all_user_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All user ////////////////////////////
}


if($visible==4)
{	
$visible=4;
$sub_visible=1;
/////////////////////////////////////////// All Owners ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible=2;
/////////////////////////////////////////// All Tenant ////////////////////////////
//$this->loadmodel('user');
//$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];

$role_id=@(int)$this->request->data['role'.$role_id];
if(!empty($role_id))
{
$sub_visible[]=(int)$role_id;

/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	


}
}
$da_to=array_unique($da_to);
}

if($visible==3)
{	
$visible=3;
foreach ($wing_result as $collection) 
{
$wing_id=(int)$collection["wing"]["wing_id"];

$wing=@(int)$this->request->data['wing'.$wing_id];
if(!empty($wing))
{
$sub_visible[]=(int)$wing;


/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
//$this->loadmodel('user');
//$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
//$result_user=$this->user->find('all',array('conditions'=>$conditions));
$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	



}
}

}


$this->loadmodel('resource');
$this->resource->saveAll(array("resource_id" => $i, "resource_attachment" => $resource_att , "resource_title" => $resource_title,"resource_date"=>$date,"resource_category"=>$resource_cat,"user_id"=>$s_user_id,"society_id"=>$s_society_id,"resource_time"=>$time,"resource_delete"=>0,"visible"=>$visible,"sub_visible"=>$sub_visible));	
////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$this->loadmodel('email');
$conditions=array('auto_id'=>6);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$category_name=$this->resource_category_name($resource_cat);
$society_result=$this->society_name($s_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}
if($visible==1)
{
$send='All Users'; 
}
if($visible==2)
{
$send='Roll Wise'; 
}
if($visible==3)
{
$send='Wing Wise'; 
}

if($visible==4)
{
$send='All Owners'; 
}

if($visible==5)
{
$send='All Tenants'; 
}
if(sizeof(@$da_to)==0) { $da_to=array(); }
for($k=0;$k<sizeof($da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $user_name,</p>
<p>A new document has been uploaded in your society Resource section.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Date</td>
<td>Category</td>
<td>Title</td>
<td>Attention</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$date</td>
<td>$category_name</td>
<td>$resource_title</td>
<td>$send</td>
</tr>
</table>
<div>
<center><p>To view document 
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>6,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . ''.'-' . 'New Document upload'.  '    ' .$resource_title;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}



$this->send_notification('<span class="label label-warning" ><i class="icon-folder-open"></i></span>','New document <b>'.$resource_title.'</b> submitted by',4,$i,'resource_view',$s_user_id,$da_user_id);
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Resources are published
</div> 
<div class="modal-footer">
<a href="resource_view" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}

}

}

function resource_category_name($category_id)
{
$this->loadmodel('resource_category');
$conditions=array("resource_cat_id" => $category_id);
$result_category=$this->resource_category->find('all',array('conditions'=>$conditions));
foreach ($result_category as $collection) 
{
return $resource_cat_name=$collection['resource_category']['resource_cat_name'];
}
}

function resource_approval()
{

	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
$this->check_user_privilages();	
$this->ath();	
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('resource');
$conditions=array('society_id'=>$s_society_id,'resource_delete'=>4);	
$order=array('resource.resource_id'=>'DESC');
$result=$this->resource->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('result_resource',$result);	
	
}
function resource_reject()
{
	$this->layout="blank";	
	$id=(int)$this->request->query('con');
	$this->loadmodel('resource');
	$this->resource->updateAll(array('resource_delete'=>5),array('resource_id'=>$id));
	$this->response->header('location','resource_approval');	
}	
function resource_approve_ajax()
{
	$this->layout='blank';
	$s_society_id=$this->Session->read('society_id');
	$id=(int)$this->request->query('t');
	$this->loadmodel('resource');
	$conditions=array('resource_id'=>$id);
	$result_resource=$this->resource->find('all',array('conditions'=>$conditions));
	foreach($result_resource as $data)
	{
		$title=$data['resource']['resource_title'];
		$date=$data['resource']['resource_date'];
		$resource_category=$data['resource']['resource_category'];
		$visible=(int)$data['resource']['visible'];
		$sub_visible=$data['resource']['sub_visible'];
	}
	
if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
$result_user= $this->all_user_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible=1;
/////////////////////////////////////////// All Owners ////////////////////////////

$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible=2;
/////////////////////////////////////////// All Tenant ////////////////////////////

$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
/////////////////////////////////////////// All Tenant ////////////////////////////
}


if($visible==2)
{	
$visible=2;
foreach ($sub_visible as $collection) 
{
$role_id=$collection;
/////////////////////////////////////////// All role  functionality  conditions /////////////////////////////////////////////

$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}
$da_to=array_unique($da_to);
}



if($visible==3)
{	
$visible=3;
foreach ($sub_visible as $collection) 
{
$wing_id=$collection;

/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////

$result_user=$this->all_wing_wise_deactive($wing_id);
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	

}

}
$this->loadmodel('email');
$conditions=array('auto_id'=>6);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$category_name=$this->resource_category_name($resource_category);

$society_result=$this->society_name($s_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}
if($visible==1)
{
$send='All Users'; 
}
if($visible==2)
{
$send='Roll Wise'; 
}
if($visible==3)
{
$send='Wing Wise'; 
}

if($visible==4)
{
$send='All Owners'; 
}

if($visible==5)
{
$send='All Tenants'; 
}
if(sizeof(@$da_to)==0) { $da_to=array(); }
for($k=0;$k<sizeof($da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];


$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $user_name,</p>
<p>A new document has been uploaded in your society Resource section.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Date</td>
<td>Category</td>
<td>Title</td>
<td>Attention</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$date</td>
<td>$category_name</td>
<td>$title</td>
<td>$send</td>
</tr>
</table>
<div>
<center><p>To view document 
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";

$this->loadmodel('notification_email');
$conditions7=array("module_id" =>6,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . ''.'-' . 'New Document upload'.  '    ' .$resource_title;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}
$this->loadmodel('resource');
$this->resource->updateAll(array('resource_delete'=>0),array('resource_id'=>$id));	
echo"<td colspan='8'>Documents have published</td>";	
}

function resource_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');
$tenant=$this->Session->read('tenant');
$role_id=$this->Session->read('role_id');
$wing=$this->Session->read('wing');
$s_user_id=$this->Session->read('user_id');
$this->set('role_id',$role_id); 
$this->loadmodel('resource');
//$conditions=array('society_id'=>$s_society_id);
$conditions =array( '$or' => array( 
array('society_id' =>$s_society_id,'visible' =>1,'resource_delete'=>0),
array('society_id' =>$s_society_id,'resource_delete'=>0,'visible' =>2,'sub_visible' =>array('$in' => array($role_id))),
array('society_id' =>$s_society_id,'resource_delete'=>0,'visible' =>3,'sub_visible' =>array('$in' => array($wing))),
array('society_id' =>$s_society_id,'resource_delete'=>0,'visible' =>4,'sub_visible' =>$tenant),
array('society_id' =>$s_society_id,'visible' =>5,'sub_visible' =>$tenant,'resource_delete'=>0)
));
$order=array('resource.resource_id'=>'DESC');
$result=$this->resource->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('result_resource',$result);

}

function resource_sm_delete()
{
$this->layout='blank';
$a=(int)$this->request->query('con');
$this->loadmodel('resource');
$this->resource->updateAll(array("resource_delete" =>1),array("resource_id" => $a));
$this->response->header('Location', 'resource_view');
}
function resource_category_name_edit($category_id)
{
$this->loadmodel('resource_category');
$conditions=array("resource_cat_id" => $category_id);
return $result=$this->resource_category->find('all',array('conditions'=>$conditions));

}

function resource_edit()
{
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$res_id=(int)$this->request->query('con');
$this->loadmodel('resource');
$conditions=array("resource_id"=> $res_id);
$result=$this->resource->find('all',array('conditions'=>$conditions));

foreach($result as $data)
{
$attachment=$data['resource']['resource_attachment'];
//$visible=$data['resource']['r_visible_id'];
//$sub_visible=$data['resource']['r_sub_visible_id'];
$resource_date=$data['resource']['resource_date'];
}

$this->set('result_resource',$this->resource->find('all',array('conditions'=>$conditions))); 
$this->loadmodel('resource_category');
$this->set('result_cat',$this->resource_category->find('all'));
$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->loadmodel('wing');
$wing_result=$this->wing->find('all');
if($this->request->is('post'))
{

$resource_title= $this->request->data['title'];
$resource_cat= (int)$this->request->data['sel'];
$resource_att=$this->request->form['file']['name'];
if(empty($resource_att))
{
$resource_att=$attachment;
}
$target = "resource_file/";
$target=@$target.basename( @$this->request->form['file']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 
/*
if($visible==1)
{
if(in_array(1,$sub_visible))
{
/////////////////////////////////////////// All Owner mail functionality conditions //////////////////////////////////////////////////////

$this->loadmodel('user');
$conditions=array('tenant'=>1,'society_id'=>$s_society_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////		

}
if(in_array(2,$sub_visible))
{

/////////////////////////////////////////// All Tenant mail functionality conditions //////////////////////////////////////////////////////

$this->loadmodel('user');
$conditions=array('tenant'=>2,'society_id'=>$s_society_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}
//////////////////////////////// end mail ////////////////////////////////////////////////////////					


}


}

if($visible==2)
{
foreach ($role_result as $collection) 
{
$role_id=$collection["role"]["role_id"];
if(in_array($role_id,$sub_visible))
{

/////////////////////////////////////////// All role  functionality  conditions //////////////////////////////////////////////////////
$this->loadmodel('user');
$conditions=array('role_id'=>$role_id,'society_id'=>$s_society_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////	 


}

}
$da_to=array_unique($da_to);
}

if($visible==3)
{

foreach($wing_result as $collection)
{

$wing_id=$collection['wing']['wing_id'];

if(in_array($wing_id,$sub_visible))
{

/////////////////////////////////////////// All wing wise  functionality conditions //////////////////////////////////////////////////////
$this->loadmodel('user');
$conditions=array('wing'=>$wing_id,'society_id'=>$s_society_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions));
foreach($result_user as $data)
{
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
$da_user_id[]=$data['user']['user_id'];
}

//////////////////////////////// end mail ////////////////////////////////////////////////////////		

}

}

}




////////////////////////////////////////////// Email Code Start ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$this->loadmodel('email');
$conditions=array('auto_id'=>6);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$category_name=$this->resource_category_name($resource_cat);

$society_result=$this->society_name($s_society_id);
foreach($society_result as $data)
{
$society_name=$data['society']['society_name'];
}
if($visible==1)
{
$send='All Users'; 
}
if($visible==2)
{
$send='Roll Wise'; 
}
if($visible==3)
{
$send='Wing Wise'; 
}

for($k=0;$k<sizeof(@$da_to);$k++)
{
$to = @$da_to[$k];
$d_user_id = @$da_user_id[$k];	 
$user_name = @$da_user_name[$k];
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear  $user_name,</p>
<p>A new document has been uploaded in your society Resource section.</p>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Date</td>
<td>Category</td>
<td>Title</td>
<td>Attention</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$resource_date</td>
<td>$category_name</td>
<td>$resource_title</td>
<td>$send</td>
</tr>
</table>
<div>
<center><p>To view document 
<a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$this->loadmodel('notification_email');
$conditions7=array("module_id" =>6,"user_id"=>$d_user_id,'chk_status'=>0);
$result5=$this->notification_email->find('all',array('conditions'=>$conditions7));
$n=sizeof($result5);
if($n>0)
{
@$subject.= ''. $society_name . ''.'-' . 'New Document upload'.  '    ' .$resource_title;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";
}	
}
*/


$this->loadmodel('resource');
$this->resource->updateAll( array("resource_attachment" => $resource_att,"resource_title"=>$resource_title,'resource_category'=> $resource_cat),array("resource_id" => $res_id));
$this->response->header('Location', 'resource_view');
}

}


////////////////////////////////////////////////////Resource End /////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////Classified Start //////////////////////////////////////////////////////////////////

function classified_select_category()
{	
$this->layout='session';
$this->loadmodel('master_classified_category');
$this->set('result_select_category',$this->master_classified_category->find('all'));


}


function main_classified_category_name($main_category)
{	
$this->loadmodel('master_classified_category');
$conditions=array("category_id" => $main_category);
$resut=$this->master_classified_category->find('all',array('conditions'=>$conditions));
foreach ($resut as $collection)
{
return $main_category_name=$collection['master_classified_category']['category_name'];
}	

}





function master_classified_subcategory($classified_category_id)
{

$this->loadmodel('master_classified_subcategory');
$conditions=array('category_id' => $classified_category_id);
return $this->master_classified_subcategory->find('all',array('conditions'=>$conditions));

}



function classified_post_ad()
{
$this->ath();
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$post_category_id = (int)$this->request->query('a');
$post_sub_category_id = (int)$this->request->query('b');
if(isset($this->request->data['pub'])) 
{

date_default_timezone_set('Asia/Kolkata');
$date=date("d-m-Y");
$time = date(' h:i a', time());
$title=htmlentities($this->request->data['title']);
$description=htmlentities($this->request->data['description']);
$price=$this->request->data['price'];
$offer_up_to_date=$this->request->data['offer'];
if(empty($offer_up_to_date))
{
$offer_up_to_date_s=date('Y-m-d', strtotime($date. ' +30 days'));
$offer_up_to_date=date('d-m-Y', strtotime($offer_up_to_date_s));
}
$price_type=(int)$this->request->data['optionsRadios1'];
$condition=(int)$this->request->data['condition'];
$sell=(int)$this->request->data['sell'];
$photo_name =$this->request->form['photo_upload']['name'];
$target = "classified_photos/";
$target=@$target.basename( @$this->request->form['photo_upload']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['photo_upload']['tmp_name'],@$target); 
$this->loadmodel('classified');
$i=$this->autoincrement('classified','classified_id');
$this->classified->saveAll(array('classified_id' => $i, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'classified_title' => $title ,
'classified_attachment' => $photo_name , 'classified_price' => $price, 'classified_price_type' => $price_type , 'classified_type_ad' => $sell ,'classified_condition' => $condition ,'classified_description' => $description, 'classified_offer_up_to_date' => $offer_up_to_date, 'classified_post_category_id' => $post_category_id, 'classified_post_sub_category_id' => $post_sub_category_id, 'classified_date' => $date , 'classified_time' => $time , 'classified_delete_id' => 0,'c_status'=>1));
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Post Classified Ads is Publish
</div> 
<div class="modal-footer">
<a href="classified" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}


if(isset($this->request->data['draft'])) 
{


date_default_timezone_set('Asia/Kolkata');
$date=date("d-m-Y");
$time = date(' h:i a', time());
$title=htmlentities($this->request->data['title']);
$description=htmlentities($this->request->data['description']);
$price=$this->request->data['price'];
$offer_up_to_date=$this->request->data['offer'];
$price_type=(int)$this->request->data['optionsRadios1'];
$condition=(int)$this->request->data['condition'];
$sell=(int)$this->request->data['sell'];
$photo_name =$this->request->form['photo_upload']['name'];
$target = "classified_photos/";
$target=@$target.basename( @$this->request->form['photo_upload']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['photo_upload']['tmp_name'],@$target); 
$this->loadmodel('classified');
$i=$this->autoincrement('classified','classified_id');
$this->classified->saveAll(array('classified_id' => $i, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'classified_title' => $title ,
'classified_attachment' => $photo_name , 'classified_price' => $price, 'classified_price_type' => $price_type , 'classified_type_ad' => $sell ,'classified_condition' => $condition ,'classified_description' => $description, 'classified_offer_up_to_date' => $offer_up_to_date, 'classified_post_category_id' => $post_category_id, 'classified_post_sub_category_id' => $post_sub_category_id, 'classified_date' => $date , 'classified_time' => $time , 'classified_delete_id' => 0,'c_status'=>0 ));

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Post Classified Ads is Draft
</div> 
<div class="modal-footer">
<a href="classified_draft" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}
}


function classified()
{	
$this->layout='session';
$this->ath();

$cat1=(int)@$this->request->query('cat');
$this->set('cat',$cat1);

$this->loadmodel('master_classified_category');
$order=array('master_classified_category.category_name'=>'ASC');
$this->set('result_classified',$this->master_classified_category->find('all',array('order'=>$order)));
$this->loadmodel('classified');
if(empty($cat1)) 
{
$condition1=array("classified_delete_id" => 0,"c_status" =>1);
$this->set('resut_cat',$this->classified->find('all',array('conditions'=>$condition1)));
}

if(!empty($cat1)) 
{
$condition1=array("classified_delete_id" => 0,"classified_post_category_id" => $cat1,"c_status" =>1);
$this->set('resut_cat',$this->classified->find('all',array('conditions'=>$condition1)));
}

}

function classified_detail()
{
$this->ath();
$this->layout='session';
$id=(int)@$this->request->query('con');
$this->loadmodel('classified');
$conditions=array("classified_id" => $id);
$this->set('result_cate',$this->classified->find('all',array('conditions'=>$conditions)));

}

function mail_post_ad()
{
$this->layout='session';
$to=@$this->request->query('r');
$this->set('title',@$this->request->query('con'));

if($this->request->is('post'))
{
$subject=htmlentities($this->request->data['subject']);
$message_web=htmlentities($this->request->data['message']);
$from_name="HousingMatters";
$this->loadmodel('email');
$conditions=array("auto_id" => 3);
$res=$this->email->find('all',array('conditions'=>$conditions));
foreach ($res as $collection)
{ 
$from = $collection['email']['from'];
}
$reply=$from;
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
}

}


function classified_draft()
{

$this->ath();
$this->layout='session';
$s_user_id=$this->Session->read('user_id');
$cat1=(int)@$this->request->query('cat');
$this->set('cat',$cat1);
$this->loadmodel('master_classified_category');
$order=array('master_classified_category.category_name'=>'ASC');
$this->set('result_classified_draft',$this->master_classified_category->find('all',array('order'=>$order)));
$this->loadmodel('classified');
$condition1=array("classified_delete_id" => 0,"c_status" =>0,"user_id" =>$s_user_id);
$this->set('resut_cat',$this->classified->find('all',array('conditions'=>$condition1)));



}

function classified_my_post()
{
$this->ath();
$this->layout='session';
$s_user_id=$this->Session->read('user_id');
$cat1=(int)@$this->request->query('cat');
$this->set('cat',$cat1);
$this->loadmodel('master_classified_category');
$order=array('master_classified_category.category_name'=>'ASC');
$this->set('result_classified_my_post',$this->master_classified_category->find('all',array('order'=>$order)));
$this->loadmodel('classified');
$condition1=array("classified_delete_id" => 0,"c_status" =>1,"user_id" =>$s_user_id);
$this->set('resut_cat',$this->classified->find('all',array('conditions'=>$condition1)));

}


function classified_detail_mypost()
{
$this->ath();
$this->layout='session';
$id=(int)@$this->request->query('con');
$this->loadmodel('classified');
$conditions=array("classified_id" => $id);
$this->set('result_cate',$this->classified->find('all',array('conditions'=>$conditions)));

}

function classified_post_draft_edit()
{
$this->ath();
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$get_id = (int)$this->request->query('e');
$this->loadmodel('master_classified_category');
$this->set('result1',$this->master_classified_category->find('all'));
$this->loadmodel('master_classified_subcategory');
$this->set('result1',$this->master_classified_category->find('all'));
$this->loadmodel('classified');
$conditions=array("classified_id" => $get_id);
$result= $this->classified->find('all',array('conditions'=>$conditions));
foreach($result as $collection)
{
$view_attachment = $collection['classified']['classified_attachment'];
}

$this->set('result_classified',$result); 


if(isset($this->request->data['sub'])) 
{
date_default_timezone_set('Asia/Kolkata');
$date=date("d-m-Y");
$time = date(' h:i a', time());
$title=htmlentities($this->request->data['title']);
$description=htmlentities($this->request->data['description']);
$price=$this->request->data['price'];
$offer_up_to_date=$this->request->data['offer'];
$price_type=(int)$this->request->data['optionsRadios1'];
$condition_ad=(int)$this->request->data['condition'];
$sell_ad=(int)$this->request->data['sell'];
$photo_name =$this->request->form['photo_upload']['name'];
$cat_main=(int)$this->request->data['class_main'];
$cat_sub=(int)$this->request->data['class_sub'];
if(empty($photo_name))
{
$photo_name = $view_attachment;
}
$target = "classified_photos/";
$target=@$target.basename( @$this->request->form['photo_upload']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['photo_upload']['tmp_name'],@$target); 
$this->loadmodel('classified');
$this->classified->updateAll(array('user_id' => $s_user_id, 'society_id' => $s_society_id, 'classified_title' => $title ,
'classified_attachment' => $photo_name , 'classified_price' => $price, 'classified_price_type' => $price_type ,  'classified_type_ad' => $sell_ad ,'classified_condition' => $condition_ad ,'classified_description' => $description, 'classified_offer_up_to_date' => $offer_up_to_date, 'classified_date' => $date , 'classified_time' => $time , 'classified_delete_id' => 0,'classified_post_category_id'=>$cat_main,'classified_post_sub_category_id'=>$cat_sub,'c_status' =>0),array('classified_id'=> $get_id));

?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Post Classified Ads is Draft
</div> 
<div class="modal-footer">
<a href="classified_draft" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php




}

if(isset($this->request->data['pub'])) 
{

date_default_timezone_set('Asia/Kolkata');
$date=date("d-m-Y");
$time = date(' h:i a', time());
$title=htmlentities($this->request->data['title']);
$description=htmlentities($this->request->data['description']);
$price=$this->request->data['price'];
$offer_up_to_date=$this->request->data['offer'];
$price_type=(int)$this->request->data['optionsRadios1'];
$condition_ad=(int)$this->request->data['condition'];
$sell_ad=(int)$this->request->data['sell'];
$photo_name =$this->request->form['photo_upload']['name'];
$cat_main=(int)$this->request->data['class_main'];
$cat_sub=(int)$this->request->data['class_sub'];

if(empty($photo_name))
{
$photo_name = $view_attachment;
}
$target = "classified_photos/";
$target=@$target.basename( @$this->request->form['photo_upload']['name']);
$ok=1;
move_uploaded_file(@$this->request->form['photo_upload']['tmp_name'],@$target); 
$this->loadmodel('classified');
$this->classified->updateAll(array('user_id' => $s_user_id, 'society_id' => $s_society_id, 'classified_title' => $title ,
'classified_attachment' => $photo_name , 'classified_price' => $price, 'classified_price_type' => $price_type ,  'classified_type_ad' => $sell_ad ,'classified_condition' => $condition_ad ,'classified_description' => $description,'classified_offer_up_to_date' => $offer_up_to_date,'classified_date' => $date , 'classified_time' => $time ,'classified_delete_id' => 0,'classified_post_category_id'=>$cat_main,'classified_post_sub_category_id'=>$cat_sub,'c_status' =>1),array('classified_id'=> $get_id));

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Post Classified Ads is Publish
</div> 
<div class="modal-footer">
<a href="classified" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}






}

function classified_cat_subcategory_ajax()
{

$this->layout='blank';
$this->set('category_id',(int)$this->request->query('con1'));

}

///////////////////////////////////////////////// Classified End /////////////////////////////////////////////////////////////////////




////////////////////////////////// /////////////////////////// Profile  Start //////////////////////////////////////////////////

function flat($c_wing_id)
{
$this->loadmodel('flat');
$conditions=array("wing_id" => $c_wing_id);
return $this->flat->find('all',array('conditions'=>$conditions));

}



function profile() 
{

$this->ath();
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$r=$this->request->query('try');

if(!empty($r))
{
$this->loadmodel('user');
$this->user->updateAll(array('profile_status'=>2),array('user_id'=>$s_user_id));
$this->redirect(array('action' => 'profile'));
}

$this->loadmodel('user');
$conditions2=array('user_id'=>$s_user_id);
$result_user=$this->user->find('all',array('conditions'=>$conditions2));
foreach($result_user as $data)
{
	  $profile=$data['user']['profile_pic'];
}

if(isset($this->request->data['sub']))
{
	
$name=htmlentities($this->request->data['name']);	
$mobile=htmlentities($this->request->data['mobile']);
$email=htmlentities($this->request->data['email']);
 $sex=(int)htmlentities($this->request->data['sex']);
 $dob=htmlentities($this->request->data['dob']);
 $per_address=htmlentities($this->request->data['per_address']);
 $com_address=htmlentities($this->request->data['com_address']);
 $hob=htmlentities($this->request->data['hob']);
 $photo_name =$this->request->form['profile_photo']['name'];
 $blood_group=htmlentities($this->request->data['blood_group']);
 
if($blood_group==1)
{
$b_group="Group A";
}
if($blood_group==2)
{
$b_group="Group B";
}
if($blood_group==3)
{
$b_group="Group AB";
}
if($blood_group==4)
{
$b_group="Group O";
}
 
if(empty($photo_name))
{
	$photo_name=$profile;
	
}
	$target = "profile/";
	$target=@$target.basename( @$this->request->form['profile_photo']['name']);
	$ok=1;
	move_uploaded_file(@$this->request->form['profile_photo']['tmp_name'],@$target); 
	$this->loadmodel('user');
$this->user->updateAll(array("user_name" => $name,"email" => $email,'mobile'=>$mobile,'sex'=>$sex,'dob'=>$dob,'per_address'=>$per_address,'comm_address'=>$com_address,'hobbies'=>$hob,'profile_pic'=>$photo_name,'blood_group'=>$blood_group),array("user_id" => $s_user_id));

$to=$email;
$from_name="HousingMatters";
$subject='Profile Update';
$this->loadmodel('email');
$conditions=array('auto_id'=>4);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$reply=$from;

$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
<p> Your profile is successfully update. </p>
<p> Name : $name </p>
<p> Mobile : $mobile </p>
<p> Email : $email </p>
<p> Date of Birth : $dob </p>
<p> Permanent address : $per_address </p>
<p> Communication address : $com_address </p>
<p> Hobbies : $hob </p>
<p> Blood Group : $b_group </p>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >

</div>";

$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
 Your profile is successfully update.
</div> 
<div class="modal-footer">
<a href="profile" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
	
}



$this->loadmodel('user');
$conditions=array("user_id" => $s_user_id);
$this->set('result_user',$this->user->find('all',array('conditions'=>$conditions)));            
$this->loadmodel('wing'); 
$this->set('result1',$this->wing->find('all'));   
}

function profile_flat_ajax()
{
$this->layout='blank';	
$wing_id=(int)$this->request->query['con'];
$this->loadmodel('flat');
$conditions=array("wing_id" => $wing_id);
$result = $this->flat->find('all',array('conditions'=>$conditions));
$this->set('result3',$result);
}


function profile_check_private()
{
$this->layout='without_session';
$s_user_id=$this->Session->read('user_id');
$pub=$this->request->query('con');
$t= explode(',',$pub);
$field=$t[0];
$private_pubice=$t[1];
if($private_pubice==1)
{
$this->loadmodel('user');
$conditions=array('user_id'=>$s_user_id);
$res= $this->user->find('all',array('conditions'=>$conditions));
foreach($res as $data)
{
$row =@$data['user']['private'];
}

if(@!in_array($field,$row))
{
$row[]=$field;
$this->loadmodel('user');
$this->user->updateAll(array('private'=>$row),array('user_id'=>$s_user_id));
}

}
elseif($private_pubice==0)
{
$this->loadmodel('user');
$conditions=array('user_id'=>$s_user_id);
$res= $this->user->find('all',array('conditions'=>$conditions));
foreach($res as $data)
{
$row =$data['user']['private'];
}


if(($key=array_search($field,$row))!== false)
{
unset($row[$key]);

$this->loadmodel('user');
$this->user->updateAll(array('private'=>$row),array('user_id'=>$s_user_id));

}

}


}
/////////////////////////// ////////////////////////////// /End Profile ///////////////////////////////////////////////////////


/////////////////////////// start Content modaration  //////////////////////////////

function society_details()
{
	$this->layout='session';
	$s_society_id=$this->Session->read('society_id'); 
	if($this->request->is('post'))
	{
			 $pan=$this->request->data['pan'];
			 $s_tax=$this->request->data['s_tax'];
			 $s_number=$this->request->data['s_number'];
			 $address=$this->request->data['address'];
			 $this->loadmodel('society');
			 $conditions=array('society_id'=>$s_society_id);
			 $this->society->updateAll(array('pan'=>$pan,'tex_number'=>$s_tax,'society_address'=>$address,'society_reg_num'=>$s_number),array());
	?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Successfully add Society details
</div> 
<div class="modal-footer">
<a href="society_details" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->



<?php		
	}
	
	$this->loadmodel('society');
	$conditions=array('society_id'=>$s_society_id);
	$result=$this->society->find('all',array('conditions'=>$conditions));
	$this->set('result_society',$result);
	
}

function content_moderation()
{
	$this->layout='session';
	$s_society_id=$this->Session->read('society_id'); 
	$this->loadmodel('society');
	$conditions=array('society_id'=>$s_society_id);
	$result=$this->society->find('all',array('conditions'=>$conditions));
	$this->set('result_society',$result);
	if($this->request->is('post'))
	{
		
	     $id=$this->request->data['text_name'];
		 
		if(!empty($id))
		{
			$content=$this->request->data['name'];
			echo $content ;
			$r=explode(',',$content);
			
			if(!empty($r))
			{
				
				$this->loadmodel('society');
				$this->society->updateAll(array('content_moderation'=>$r),array('society_id'=>$s_society_id));
			}
		}
		else
		{
			
		 $content[]=$this->request->data['name'];
		foreach($result as $data)
		{
			 $con=@$data['society']['content_moderation'];
		}
		if(!empty($con))
		{
		 $content=$this->request->data['name'];
		array_push($con,$content);	
		$this->loadmodel('society');
		$this->society->updateAll(array('content_moderation'=>$con),array('society_id'=>$s_society_id));
		
		}
		else
		{
			$this->loadmodel('society');
		   $this->society->updateAll(array('content_moderation'=>$content),array('society_id'=>$s_society_id));
		   
		}
		}
		$this->response->header('location','content_moderation');
		
	}
}


function content_moderation_delete()
{
	 $id=(int)$this->request->query('con');
	 $this->loadmodel('moderation');
	 $this->moderation->updateAll(array('c_m_delete'=>1),array('auto_id'=>$id));
	 $this->response->header('location','content_moderation');
	
}

///////////////////////// End Contant Modaration  /////////////////////////////////

/////////////////// Start Contact Handbook ////////////////////////////


function contact_handbook_export()
{
	
$this->layout="";
$s_society_id=$this->Session->read('society_id');
$filename='contact handbook';
header ("Expires: 0");
header ("border: 1");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header ("Content-Disposition: attachment; filename=".$filename.".xls");
header ("Content-Description: Generated Report" );

$export="<table border='1'>
<tr>
<th>Sr no.</th>
<th>Name</th>
<th>Mobile</th>
<th>Email</th>
<th>Website</th>
<th>Services Provider</th>
</tr>
";	
$i=0;
$this->loadmodel('contact_handbook');
$conditions=array('society_id'=>$s_society_id,'c_h_delete'=>0);
$result=$this->contact_handbook->find('all',array('conditions'=>$conditions));
	foreach($result as $collection)
	{
		$i++;
		$c_h_id=$collection['contact_handbook']["c_h_id"];
		$mobile=$collection['contact_handbook']["c_h_mobile"];
		$user_id=(int)$collection['contact_handbook']['user_id'];
		$name=$collection['contact_handbook']["c_h_name"];
		$email=$collection['contact_handbook']["c_h_email"];
		$web=$collection['contact_handbook']["c_h_web"];
		$service=$collection['contact_handbook']["c_h_service"];
		$result_user=$this->profile_picture($user_id);
		foreach($result_user as $data)
		{
			 $user_name=$data['user']['user_name'];

		}	
		$export.="<tr>
		<td>".$i."</td>
		<td>".$user_name."</td>
		<td>".$mobile."</td>
		<td>".$email."</td>
		<td>".$web."</td>
		<td>".$service."</td> </tr>";
		
	}
	 $export.="</table>" ;
	 echo $export ;
}




function contact_handbook()
{
$this->layout='session';	
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 	
$this->set('role_id',$s_role_id=$this->Session->read('role_id'));
$this->set('s_user_id',$s_user_id);
$this->loadmodel('contact_handbook');
$conditions=array('society_id'=>$s_society_id,'c_h_delete'=>0);
$result=$this->contact_handbook->find('all',array('conditions'=>$conditions));
$this->set('result_contact_handbook',$result);	
if($this->request->is('post'))
{
$id=(int)$this->request->data['text_id'];
$name=htmlentities($this->request->data['name']);
$mobile=htmlentities($this->request->data['mobile']);
 $email=htmlentities($this->request->data['email']);
 $web=htmlentities($this->request->data['web']);
 $service=htmlentities($this->request->data['service']);

if(!empty($id))
{
	$this->loadmodel('contact_handbook');
	$this->contact_handbook->updateAll(array('c_h_name'=>$name,'c_h_mobile'=>$mobile,'c_h_email'=>$email,'c_h_web'=>$web,'c_h_service'=>$service),array('c_h_id'=>$id));
	$this->response->header('location','contact_handbook');
}
else
{
	
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$i=$this->autoincrement('contact_handbook','c_h_id');
$this->loadmodel('contact_handbook');
$this->contact_handbook->saveAll(array("c_h_id" => $i, "c_h_name" => $name,"c_h_date"=>$date,"user_id"=>$s_user_id,"society_id"=>$s_society_id,"c_h_time"=>$time,"c_h_mobile"=>$mobile,'c_h_email'=>$email,'c_h_web'=>$web,'c_h_service'=>$service,'c_h_delete'=>0));


$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
}

$this->send_notification('<span class="label label-warning" ><i class="icon-phone"></i></span>','Addition to contact handbook  <b>'.$name.'</b> added by',21,$i,'contact_handbook',$s_user_id,$visible_user_id);

$this->response->header('location','contact_handbook');

}



}

}

function contact_handbook_delete()
{
	$this->layout='blank';
	$id=(int)$this->request->query('con');
	$this->loadmodel('contact_handbook');
	$this->contact_handbook->updateAll(array('c_h_delete'=>1),array('c_h_id'=>$id));
	$this->response->header('location','contact_handbook');
}

function contact_handbook_view()
{
$this->layout='session';	
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('contact_handbook');
$conditions=array('society_id'=>$s_society_id);
$result=$this->contact_handbook->find('all',array('conditions'=>$conditions));
$this->set('result_contact_handbook',$result);
}


function contact_handbook_view_page()
{
$this->layout='blank';
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 	
$this->set('role_id',$s_role_id=$this->Session->read('role_id'));
$this->set('s_user_id',$s_user_id);
$c_h_id=$this->request->query('con');
$this->set('search_value',$c_h_id);
$regex = new MongoRegex("/.*$c_h_id.*/i");
$this->loadmodel('contact_handbook');
$conditions =array( '$or' => array( 
array('c_h_name' =>$regex,'society_id'=>$s_society_id,'c_h_delete'=>0),
array('c_h_mobile' =>$regex,'society_id'=>$s_society_id,'c_h_delete'=>0),
array('c_h_email' =>$regex,'society_id'=>$s_society_id,'c_h_delete'=>0),
array('c_h_web' =>$regex,'society_id'=>$s_society_id,'c_h_delete'=>0),
array('c_h_service' =>$regex,'society_id'=>$s_society_id,'c_h_delete'=>0)));
$result=$this->contact_handbook->find('all',array('conditions'=>$conditions));
$this->set('result_contact_handbook',$result);
$this->set('count_yellow',sizeof($result));	

}

/////////////////// End Contact handbook ////////////////////////////




////////////////////////////////////////////////////   Resident Directory Start ////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function resident_directory() 
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');	

$s_society_id=$this->Session->read('society_id');
$this->loadmodel('wing');
$conditions1=array('society_id'=>$s_society_id);
$result1=$this->wing->find('all',array('conditions'=>$conditions1));
$this->set('result_wing',$result1);
$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,'deactive'=>0);
$order=array('user.user_name'=> 'ASC');
$result=$this->user->find('all',array('conditions'=> $conditions,'order'=>$order));
$this->set('result_user',$result);


}


function resident_directory_view()
{
$this->layout="blank";
$s_role_id=$this->Session->read('role_id');	
$this->set('role_id',$s_role_id);
$this->set('user_id',$this->Session->read('user_id'));
$user_id=(int)$this->request->query('id');
$this->loadmodel('user');
$conditions=array("user_id" => $user_id);
$result=$this->user->find('all',array('conditions'=> $conditions));
$this->set('result_user1',$result);



}



function resident_directory_search_wing_ajax()
{
$this->layout="blank";
$search_wing=(int)$this->request->query('con');
$this->set('search_value',$search_wing);
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('user');
$order=array('user.user_name'=> 'ASC');
$conditions=array("society_id" => $s_society_id,'wing'=>$search_wing,'deactive'=>0);
$result1=$this->user->find('all',array('conditions'=> $conditions,'order'=>$order));
$n=sizeof($result1);
$this->set('result_user2',$result1);
$this->set('count_user2',$n);
$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,'deactive'=>0);
$order1=array('user.user_name'=> 'ASC');
$result2=$this->user->find('all',array('conditions'=> $conditions,'order'=>$order1));
$this->set('result_user3',$result2);
}


function resident_directory_search_name()
{
$this->layout="blank";
$s_society_id=$this->Session->read('society_id');
$search=$this->request->query('con');
$this->set('search_value',$search);
$regex = new MongoRegex("/.*$search.*/i"); 
$this->loadmodel('user');
$conditions=array('user_name'=>$regex,'society_id'=>$s_society_id,'deactive'=>0);
$result=$this->user->find('all',array('conditions'=>$conditions));
$this->set('result_user',$result);
$n=sizeof($result);
$this->set('count_user2',$n);
$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id,'deactive'=>0);
$order1=array('user.user_name'=> 'ASC');
$result2=$this->user->find('all',array('conditions'=> $conditions,'order'=>$order1));
$this->set('result_user3',$result2);
}

////////////////////////////////////////////////////   Resident Directory End ////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	
///////////////////  Start Family member functionality //////////////////////



function dob_check()
{
	
	$this->layout='blank';
	$dob=$this->request->query['dob'];
	date_default_timezone_set('Asia/kolkata');
	$date=date("d-m-Y");
	$newDate = date("d-m-Y", strtotime($date));
	$newDate1 = date("Y-m-d", strtotime($newDate));
	$newDate2 = date("d-m-Y", strtotime($dob));
	$newDate3 = date("Y-m-d", strtotime($newDate2));
	$datetime1 = date_create($newDate1);
	$datetime2 = date_create($newDate3);
	$interval = date_diff($datetime2, $datetime1);
	 $years = $interval->y;
	 if($years>=13)
	 {
		echo'true';
	 }
	 else
	 {
		echo'false'; 
	 }
}

function family_member_view()
{
	$this->layout="session";
	$s_user_id=$this->Session->read('user_id'); 
	$s_society_id=$this->Session->read('society_id'); 
	$this->loadmodel('user');
	$conditions=array('family_member'=>$s_user_id);
	$result=$this->user->find('all',array('conditions'=>$conditions));
	$this->set('result_user',$result);
}

function family_member()
{
	
$this->layout="session";	
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 
$this->loadmodel('user');
$conditions=array('user_id'=>$s_user_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
	$tenant=(int)$data['user']['tenant'];
	$wing=(int)$data['user']['wing'];
	$flat=(int)$data['user']['flat'];
	$residing=(int)$data['user']['residing'];
	
}
$result_society=$this->society_name($s_society_id);	
foreach($result_society as $data)
{
	 $society_name=$data['society']['society_name'];
	 @$family_member=$data['society']['family_member'];
}

if($this->request->is('post'))
		{
		if($family_member==1)
		{			
			date_default_timezone_set('Asia/kolkata');
			$date=date("d-m-Y");
			$time=date('h:i:a',time());
			$name=$this->request->data['name'];
			$email=$this->request->data['email'];
			$mobile=$this->request->data['mobile'];
			$this->loadmodel('user');	
			$i=$this->autoincrement('user','user_id');	
			$random1=mt_rand(1000000000,9999999999);
			$random2=mt_rand(1000000000,9999999999);
			$random=$random1.$random2 ;	
			$de_user_id=$this->encode($i,'housingmatters');
			$random=$de_user_id.'/'.$random;
			$dob=$this->request->data['dob'];
			$relation=$this->request->data['relation'];
			$blood_group=$this->request->data['blood_group'];
			$log_i=$this->autoincrement('login','login_id');
			
////////////////////////// insert user table //////////////////////////
		
$this->user->save(array('user_id' => $i, 'user_name' => $name,'email' => $email, 'password' =>'', 'mobile' => $mobile,  'society_id' => $s_society_id, 'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>2,'default_role_id'=>2,'signup_random'=>$random,'family_member'=>$s_user_id,'dob'=>$dob,'relation'=>$relation,'login_id'=>$log_i,'s_default'=>1,'blood_group'=>$blood_group));

////////////////////// End user table ///////////////////////////////////////////////////

////////////////////////////////// insert login table /////////////////////////////////////

$this->loadmodel('login');
$this->login->save(array('login_id'=>$log_i,'user_name'=>$email,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

/////////////////////////  End login table /////////////////////////////////////////

 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b><a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br/><br/>
www.housingmatters.co.in
</div >
</div>";
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$to=$email;
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Welcome to ".$society_name." portal ";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);

?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Successfully add family member.
</div> 
<div class="modal-footer">
<a href="" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php
		}
		else
		{
			?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Administrator has not allowed family member login into the portal.
</div> 
<div class="modal-footer">
<a href="" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php
			
		}
	}
	
}



//////////////////////////// End  family member //////////////////////////////

function committee_metters_view()
{

$this->layout='session';
$this->ath();	
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id');	
$this->loadmodel('committee_metter');
$conditions=array('society_id'=>$s_society_id);
$result=$this->committee_metter->find('all',array('conditions'=>$conditions));
$this->set('result_com',$result);
}


//////////////////////////////////// Committee_metters end  //////////////////////////////////	



////////////////////////////////////////////// Society Report view start //////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

function society_member_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();	
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('society');	
$conditions=array('society_id'=>$s_society_id);	
$this->set('result_society',$this->society->find('all',array('conditions'=>$conditions)));
$this->loadmodel('user');	
$conditions1=array('society_id'=>$s_society_id,'deactive'=>0);
$result1=$this->user->find('all',array('conditions'=>$conditions1));	
$this->set('result_user',$result1);
$this->set('n',sizeof($result1));
$this->loadmodel('role');	
$conditions2=array('society_id'=>$s_society_id);
$this->set('result_role',$this->role->find('all',array('conditions'=>$conditions2)));


}


function society_member_excel()
{
$this->layout="";
$s_society_id=$this->Session->read('society_id');
$filename='user_list';
@header("Expires: 0");
@header("border: 1");
@header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
@header("Content-type: application/vnd.ms-excel");
@header("Content-Disposition: attachment; filename=".$filename.".xls");
@header("Content-Description: Generated Report");

$excel="<table border='1'>
<tr>
<td><strong>Sr</strong></td>
<td><strong>Name</strong></td>
<td><strong>Unit</strong></td>
<td><strong>Mobile</strong></td>
<td><strong>Email</strong></td>
<td><strong>Status</strong></td>
<td><strong>joining Date</strong></td>
<td><strong>Awaiting User Validation</strong></td>
</tr>";
$i=0;
$this->loadmodel('user');	
$conditions1=array('society_id'=>$s_society_id,'deactive'=>0);
$result1=$this->user->find('all',array('conditions'=>$conditions1));	
foreach($result1 as $data)
{
	$i++;
    $user_name=$data['user']['user_name'];
	 @$email=$data['user']['email'];
	 @$mobile=$data['user']['mobile'];
	$date=$data['user']['date'];
	$tenant=(int)$data['user']['tenant'];
	$wing=(int)$data['user']['wing'];
	$flat=(int)$data['user']['flat'];
	$wing_flat1=$this->wing_flat($wing,$flat);
	@$profile_status=(int)$data['user']['profile_status'];
	if($tenant==1)
	{
	$tenant='Owner';	
	}
    else
	{
		$tenant='Tenant';
	}
	if(@$profile_status!=2)
	{
		$profile='Yes';
		
	}
	else
	{
		$profile='No';
	}
$excel.="<tr>
<td>".$i."</td>
<td>".$user_name."</td>
<td>".$wing_flat1."</td>
<td>".@$mobile."</td>
<td>".@$email."</td>
<td>".$tenant."</td>
<td>".$date."</td>
<td>".$profile."</td>
</tr>";
}	
$excel.="</table>";
echo $excel ;
}



////////////////////////////////////////////End Society member view /////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////// multiple society ///////////////////

function multi_society_enrollment()
{
	
$this->layout="session";
$this->ath();	
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
	
$this->loadmodel('society');
$result=$this->society->find('all');	
$this->set('result_society',$result);	
	if($this->request->is('post')) 
	{
		date_default_timezone_set('Asia/kolkata');
		$date=date("d-m-Y");
		$time=date('h:i:a',time());
		$society_id=(int)$this->request->data['society'];
		$wing=(int)$this->request->data['wing'];
		$flat=(int)$this->request->data['flat'];
		$residing=(int)$this->request->data['residing'];
		$tenant=(int)$this->request->data['tenant'];
		if($tenant==1)
		{
		$committee=(int)$this->request->data['committe'];
		}
		else
		{
		$committee=2;
		}
		
		$this->loadmodel('user');
		$conditions=array('user_id'=>$s_user_id);
		$result_user=$this->user->find('all',array('conditions'=>$conditions));
		
		foreach($result_user as $data)
		{
			$user_name=$data['user']['user_name'];
			$login_id=(int)$data['user']['login_id'];
			$email=$data['user']['email'];
			$mobile=$data['user']['mobile'];
		}
	
$i=$this->autoincrement('user_temp','user_temp_id');
$this->loadmodel('user_temp');
$this->user_temp->save(array('user_temp_id'=>$i,'user_name'=>$user_name,'email'=>$email,'mobile'=>$mobile,'password'=>'',"society_id" => $society_id,"committee" => $committee,'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing,"role"=>2,"complete_signup"=>1,'reject'=>0,'login_id'=>$login_id,'date'=>$date,'time'=>$time,'multiple_society'=>1));
	
	
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
 Your request is sent for approval to Society Administrator.<br/>
</div> 
<div class="modal-footer">
<a href="dashboard" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php	
	
	}
	
}


////////////////////////////////////////////////////////


///////////////////////////////////////// New User Enrollment ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function hm_new_user_enrollment()
{
$this->layout="session";
$this->ath();	
$this->loadmodel('society');
$result=$this->society->find('all');	
$this->set('result_society',$result);
if($this->request->is('post')) 
{
	
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$society_id=(int)$this->request->data['society'];
$result_society=$this->society_name($society_id);
foreach($result_society as $data)
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

$name=$this->request->data['name'];
$email=$this->request->data['email'];
$mobile=$this->request->data['mobile'];
$wing=(int)$this->request->data['wing'];
$flat=(int)$this->request->data['flat'];
$residing=(int)$this->request->data['residing'];
$tenant=(int)$this->request->data['tenant'];
if($tenant==1)
{
$committee=(int)$this->request->data['committe'];
}
else
{
$committee=2;
}
$role_id[]=2;
$default_role_id=2;
if($committee==1)
{
$role_id[]=1;
}
$this->loadmodel('user');
$i=$this->autoincrement('user','user_id');
$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;	
$de_user_id=$this->encode($i,'housingmatters');
$random=$de_user_id.'/'.$random;
$log_i=$this->autoincrement('login','login_id');

if(!empty($mobile))
{
if(empty($email))
{
$login_user=$mobile;
$random=(string)mt_rand(1000,9999);
	
$sms="".$name.", Your housing society  ".$s_n." has enrolled  you in HousingMatters portal. Pls log into www.housingmatters.co.in One Time Password ".$random."";
 $sms1=str_replace(" ", '+', $sms);
 $payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');

}
}

$this->user->save(array('user_id' => $i, 'user_name' => $name,'email' => $email, 'password' => @$random, 'mobile' => $mobile,  'society_id' => $society_id, 'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>$role_id,'default_role_id'=>$default_role_id,'signup_random'=>$random,'deactive'=>0,'login_id'=>$log_i,'s_default'=>1));

///////////////  Insert code ledger Sub Accounts //////////////////////

$this->loadmodel('ledger_sub_account');
$j=$this->autoincrement('ledger_sub_account','auto_id');
$this->ledger_sub_account->save(array('auto_id'=>$j,'ledger_id'=>34,'name'=>$name,'society_id' => $society_id,'user_id'=>$i,'deactive'=>0));

/////////////  End code ledger sub accounts //////////////////////////


	
if(!empty($email) && !empty($mobile))
{
$login_user=$email;
  $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b>
<a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br/>
www.housingmatters.co.in
</div >
</div>";


}
		
if(!empty($email) && empty($mobile))
{
$login_user=$email;	
 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b><a href='http://123.63.2.150:8080".$this->webroot."/hms/set_new_password?q=$random'>Click here</a> for one time verification of your email and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br/>
www.housingmatters.co.in
</div >
</div>";

}
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$to=$email;
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Welcome to ".$society_name." portal ";
if(!empty($email))
{
$this->send_email($to,$from,$from_name,$subject,@$message_web,$reply);
}

////////////////Notification email user all checked code  //////////////////////////
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
$auto_id = (int)$data['email']['auto_id'];
$this->loadmodel('notification_email');
$lo=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $i,'chk_status'=>0));
}

//////////////// End all checked code   //////////////////////////


$this->loadmodel('login');
$this->login->save(array('login_id'=>$log_i,'user_name'=>$login_user,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
New member registered into your society successfully.
</div> 
<div class="modal-footer">
<a href="hm_new_user_enrollment" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php

}
}


function new_user_enrollment()
{
$this->layout="session";
$this->ath();
$this->check_user_privilages();
App::import('', 'sendsms.php');
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('wing');
$conditions=array('society_id'=>$s_society_id);
$result=$this->wing->find('all',array('conditions'=>$conditions));
$this->set('result_wing',$result);
$res_society=$this->society_name($s_society_id);
foreach($res_society as $data)
{
 $society_name=$data['society']['society_name'];

}
$s_n='';
$sco_na=$society_name;
$dd=explode(' ',$sco_na);
 $first=$dd[0];
 $two=$dd[1];
 $three=$dd[2];
$s_n.=" $first $two $three ";



if($this->request->is('post')) 
{
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$name=$this->request->data['name'];
$email=$this->request->data['email'];
$mobile=$this->request->data['mobile'];
$wing=(int)$this->request->data['wing'];
$flat=(int)$this->request->data['flat'];
$residing=(int)$this->request->data['residing'];
$tenant=(int)$this->request->data['tenant'];
if($tenant==1)
{
$committee=(int)$this->request->data['committe'];
}
else
{
$committee=2;
}
$role_id[]=2;
$default_role_id=2;
if($committee==1)
{
$role_id[]=1;
}

$this->loadmodel('user');
$i=$this->autoincrement('user','user_id');
$log_i=$this->autoincrement('login','login_id');
$random1=mt_rand(1000000000,9999999999);
$random2=mt_rand(1000000000,9999999999);
$random=$random1.$random2 ;	
$de_user_id=$this->encode($i,'housingmatters');
$random=$de_user_id.'/'.$random;
if(!empty($mobile))
{
if(empty($email))
{
	$login_user=$mobile;
$random=(string)mt_rand(1000,9999);
 $sms="".$name.", Your housing society ".$s_n." has enrolled you in HousingMatters portal. Pls log into www.housingmatters.co.in One Time Password ".$random."";
$sms1=str_replace(" ", '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
}
}

$this->user->save(array('user_id' => $i, 'user_name' => $name,'email' => $email, 'password' => @$random, 'mobile' => $mobile,  'society_id' => $s_society_id, 'tenant' => $tenant, 'wing' => $wing, 'flat' => $flat,'residing' => $residing, 'date' => $date, 'time' => $time,"profile_pic"=>'blank.jpg','sex'=>'','role_id'=>$role_id,'default_role_id'=>$default_role_id,'signup_random'=>$random,'deactive'=>0,'login_id'=>$log_i,'s_default'=>1));




///////////////  Insert code ledger Sub Accounts //////////////////////

$this->loadmodel('ledger_sub_account');
$j=$this->autoincrement('ledger_sub_account','auto_id');
$this->ledger_sub_account->save(array('auto_id'=>$j,'ledger_id'=>34,'name'=>$name,'society_id' => $s_society_id,'user_id'=>$i,'deactive'=>0));

/////////////  End code ledger sub accounts //////////////////////////

	
	
if(!empty($email) && !empty($mobile))
{
$login_user=$email;	
	
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b>
<a href='http://123.63.2.150:8080".$this->webroot."/hms/verify_mobile?q=$random'>Click here</a> for one time verification of your mobile number and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br/>
www.housingmatters.co.in
</div >
</div>";
}
		
if(!empty($email) && empty($mobile))
{
	$login_user=$email;	
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name,</p>
<p>'We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, we have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<p>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</p>
<p>You can receive important SMS & emails from your committee.</p>
<br/>				
<p><b><a href='http://123.63.2.150:8080".$this->webroot."/hms/set_new_password?q=$random'>Click here</a> for one time verification of your email and Login into HousingMatters  for making life simpler for all your housing matters!</b></p>
<br/>
<p>Pls add www.housingmatters.co.in in your favorite bookmarks for future use.</p>
<p>Regards,</p>	
<p>Administrator of $society_name</p><br>
www.housingmatters.co.in
</div >
</div>";
}
$from_name="HousingMatters";
$reply="support@housingmatters.in";
$to=$email;
$this->loadmodel('email');
$conditions=array("auto_id" => 4);
$result_email = $this->email->find('all',array('conditions'=>$conditions));
foreach ($result_email as $collection) 
{
$from=$collection['email']['from'];
}
$subject="Welcome to ".$society_name." portal ";
if(!empty($email))
{
$this->send_email($to,$from,$from_name,$subject,@$message_web,$reply);
}
////////////////Notification email user all checked code  //////////////////////////
$this->loadmodel('email');	
$conditions=array('notification_id'=>1);
$result_email=$this->email->find('all',array('conditions'=>$conditions));
foreach($result_email as $data)
{
$auto_id = (int)$data['email']['auto_id'];
$this->loadmodel('notification_email');
$lo=$this->autoincrement('notification_email','notification_id');
$this->notification_email->saveAll(array("notification_id" => $lo, "module_id" => $auto_id , "user_id" => $i,'chk_status'=>0));
}

//////////////// End all checked code   //////////////////////////

////////////////////  insert login table  ///////////////////

$this->loadmodel('login');
$this->login->save(array('login_id'=>$log_i,'user_name'=>$login_user,'password'=>$random,'signup_random'=>$random,'mobile'=>$mobile));

//////////////////////////////////////////////////////////////////


?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
New member registered into your society successfully.
</div> 
<div class="modal-footer">
<a href="society_member_view" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
}
}




/////////////////////////////////// End  new User Enrolment ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////// New Tenant Enrollment Start //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function new_tenant_enrollment()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('user');	
$conditions1=array('society_id'=>$s_society_id,'tenant'=>2);
$result1=$this->user->find('all',array('conditions'=>$conditions1));	
$this->set('result_user',$result1);
$this->loadmodel('wing');
$conditions=array('society_id'=>$s_society_id);
$result=$this->wing->find('all',array('conditions'=>$conditions));
$this->set('result_wing',$result);
if($this->request->is('post')) 
{
date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$tenant_user_id=(int)$this->request->data['sel'];
$this->loadmodel('user');	
$conditions1=array('user_id'=>$tenant_user_id);
$result1=$this->user->find('all',array('conditions'=>$conditions1));
foreach($result1 as $data)
{
$user_name=$data['user']['user_name'];
$mobile=@$data['user']['mobile'];
}
$ten_age=(int)@$this->request->data['ten_agr'];
// $user_name=@$this->request->data['name_tenant'];
// $wing=(int)@$this->request->data['wing'];
// $flat=(int)@$this->request->data['flat'];
$pol_ver=(int)@$this->request->data['pol_ver'];
$address=$this->request->data['address'];
$start_date=$this->request->data['start_date'];
$end_date=$this->request->data['end_date'];
$verification=$this->request->data['verification'];
//$this->loadmodel('user');
//$this->user->updateAll(array( "user_name" => $user_name,'wing'=>$wing,'flat'=>$flat),array('user_id'=>$tenant_user_id));
$this->loadmodel('tenant');	
$conditions2=array('user_id'=>$tenant_user_id);
$result2=$this->tenant->find('all',array('conditions'=>$conditions2));
$n=sizeof($result2);
if($n==0)
{

$i=$this->autoincrement('tenant','tenant_id');
$this->loadmodel('tenant');
$this->tenant->saveAll((array("tenant_id" => $i, "name" => $user_name , "user_id" => $tenant_user_id,"t_start_date"=>$start_date,"t_end_date"=>$end_date,"society_id"=>$s_society_id,"t_time"=>$time,"t_mobile"=>$mobile,"t_address"=>$address,"verification"=>$verification,'t_agreement'=>$ten_age,'t_police'=>$pol_ver)));

$this->response->header('Location', 'new_tenant_enrollment_view');


}
else
{

$this->loadmodel('tenant');
$this->tenant->updateAll(array( "name" => $user_name ,"t_start_date"=>$start_date,"t_end_date"=>$end_date,"society_id"=>$s_society_id,"t_time"=>$time,"t_mobile"=>$mobile,"t_address"=>$address,"verification"=>$verification,'t_agreement'=>$ten_age,'t_police'=>$pol_ver),array("user_id" => $tenant_user_id));
$this->response->header('Location', 'new_tenant_enrollment_view');		
}

}

}

function new_tenant_enrollment_ajax()
{
$this->layout='blank';
$s_society_id=$this->Session->read('society_id');
$t=(int)$this->request->query('con');
$this->loadmodel('tenant');	
$conditions1=array('user_id'=>$t);
$result1=$this->tenant->find('all',array('conditions'=>$conditions1));	
$this->set('result_tenant',$result1);
$this->loadmodel('user');	
$conditions2=array('user_id'=>$t);
$result2=$this->user->find('all',array('conditions'=>$conditions2));	
$this->set('result_user',$result2);
$this->loadmodel('wing');
$conditions=array('society_id'=>$s_society_id);
$result3=$this->wing->find('all',array('conditions'=>$conditions));
$this->set('result_wing',$result3);

}


function new_tenant_enrollment_view()
{
$this->layout='session';
$this->ath();
$this->check_user_privilages();
$s_society_id=$this->Session->read('society_id');
$this->loadmodel('tenant');
$condition=array('society_id'=>$s_society_id);
$result=$this->tenant->find('all',array('condtions'=>$condition)); 
$this->set('user_tenant',$result);

}



////////////////////////////////////////////////////////////////End new Tenant enrollment //////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////// Start  Feedback functionality ///////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function feedback_category_name($category)
{
$this->loadmodel('feedback_category');
$conditions=array('feedback_cat_id'=>$category);
$result=$this->feedback_category->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
return $category_name=$data['feedback_category']['feedback_cat_name'];

}

}

function feedback()
{
$this->layout='session';
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('user');
$conditions=array('user_id'=>$s_user_id);
$result=$this->user->find('all',array('conditions'=>$conditions));
foreach($result as $data)
{
$user=$data['user']['user_name'];
$mobile=$data['user']['mobile'];
$email=$data['user']['email'];	
}
$this->set('user_name',$user);

$this->loadmodel('feedback_category');
$this->set('result_fed_cat',$this->feedback_category->find('all'));

if($this->request->is('post')) 
{

$feedback_cat_id=(int)$this->request->data['sel'];
$subject= htmlentities($this->request->data['subject']);
$message= htmlentities($this->request->data['mess']);
$feedback_cat_name=$this->feedback_category_name($feedback_cat_id);
$result_society=$this->society_name($s_society_id);
foreach ($result_society as $collection) 
{ 
$society_name=$collection['society']["society_name"];
}
$to = "Support@housingmatters.in";
$from="Support@housingmatters.in";
$reply="Support@housingmatters.in";
$from_name="HousingMatters"; 
 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>

</br><p>Dear Administrator,</p>
<br/>
<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
<td>Name</td>
<td>Category</td>
<td>Society Name</td>
<td>Details</td>
</tr>
<tr class='tr_content' style=background-color:#E9E9E9;'>
<td>$user</td>
<td>$feedback_cat_name</td>
<td>$society_name</td>
<td><p>User Email-Id: &nbsp; $email </p>
<p>Mobile No: &nbsp; $mobile </p></td>
</tr>
</table>
<div>
<p style='font-size:16px;'> <strong>Message Description:</strong></p>
<p style='font-size:15px;'>$message</p>
<center><p>To view the feedback response <a href='http://123.63.2.150:8080".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";

date_default_timezone_set('Asia/kolkata');
$date=date("d-m-Y");
$time=date('h:i:a',time());
$i=$this->autoincrement('feedback','feedback_id');
$this->loadmodel('feedback');
$this->feedback->saveAll(array("feedback_id" => $i,"feedback_subject" => $subject,"feedback_date"=>$date,"feedback_category"=>$feedback_cat_id,"user_id"=>$s_user_id,"feedback_time"=>$time,"feedback_message"=>$message_web,"society_id"=>$s_society_id,"feedback_des"=>$message));
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);			
?>     



<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Thank you for getting in touch with us. <br> We shall Respond to you within 24 hours. 
</div> 
<div class="modal-footer">
<a href="dashboard" class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php


}

}


function feedback_view()
{

$this->layout='session';	
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('feedback');
$order=array('feedback.feedback_id'=>'DESC');
$result=$this->feedback->find('all',array('order'=>$order));
$this->set('result_feedback',$result);	
}


/////////////////////////////////////// End Feedback functionality /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////// Start Invitation Member ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

function invite_member()
{
$this->layout='session';
$s_society_id=(int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
if(isset($this->request->data['sub'])) 
{
date_default_timezone_set('Asia/Kolkata');
$date=date("d-m-Y");
$time = date(' h:i a', time());
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$from="alerts@housingmatters.in";
$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$result_society=$this->society->find('all',array('conditions'=>$condition));
foreach($result_society as $data)
{
$society_name=$data['society']['society_name'];
}
$result_user=$this->profile_picture($s_user_id);
foreach($result_user as $dd)
{
	$user_name=$dd['user']['user_name'];
	$role_id=$dd['user']['role_id'];
	$wing=$dd['user']['wing'];
	$flat=$dd['user']['flat'];
}
if(in_array(3,$role_id))
{
	 $role='Admin';
}

if(!in_array(3,$role_id))
{
	 $role=$this->wing_flat($wing,$flat);
	
}
 @$radio=$this->request->data['committe'];
if($radio==1)
{
  $subject="".$society_name." - Invitation to HousingMatters"; 
}
if($radio==0)
{

 $subject="".$user_name." - Invites you to HousingMatters "; 
}

$r=$this->request->data['hid_name'];
for($i=2;$i<=$r;$i++)
{
$to=$this->request->data['email'.$i];
$name_user=$this->request->data['name_user'.$i];
if($radio==1)
{
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name_user,</p>
<p>We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, I have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<ul type='disc'>
<li>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</li>
<li>You can receive important SMS & emails from your committee.</li>
</ul>
<p>Signup today to <a href='http://123.63.2.150:8080".$this->webroot."/hms/sign_up''>HousingMatters </a> for making life simpler for all your housing matters!</p>
<p>Regards,<br/>
$user_name $role  <br/>
www.housingmatters.co.in
</div >
</div>";
}
if($radio==0)
{
 $message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name_user,</p>
<p>We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<ul type='disc'>
<li>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</li>
<li>You can receive important SMS & emails from your committee.</li>
</ul>
<p>Signup today to <a href='http://123.63.2.150:8080".$this->webroot."/hms/sign_up''>HousingMatters </a> for making life simpler for all your housing matters!</p>
<p>Regards,<br/>
$user_name <br/>
www.housingmatters.co.in
</div >
</div>";
}

$this->loadmodel('invitation');
$j= $this->autoincrement('invitation','invite_id');
$this->invitation->saveAll(array('invite_id'=>$j,'name'=>$name_user,'email'=>$to,'user_id'=>$s_user_id,'society_id'=>$s_society_id,'date'=>$date,'time'=>$time,'subject'=>$subject));
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply); 
}
$sucess=1;
$this->set('sm',$sucess);
}
}


function invite_member_view()
{
$this->layout='session';
$s_society_id=(int)$this->Session->read('society_id');
$this->loadmodel('invitation');
$condition=array('society_id'=>$s_society_id);
$order=array('invitation.date'=>'DESC');
$result=$this->invitation->find('all',array('conditions'=>$condition,'order'=>$order));
$this->set('result_invitation',$result);
}
function invitation_remainder()
{
$this->layout='session';
$s_society_id=(int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('invitation');
$result= $this->invitation->find('all');
foreach($result as $data)
{
$check_email=$data['invitation']['email'];
$check_date=$data['invitation']['date'];
$name_user=$data['invitation']['name'];
$subject=$data['invitation']['subject'];
$society_id=(int)$data['invitation']['society_id'];
$this->loadmodel('user');
$condition1=array('email'=>$check_email);
$result1=$this->user->find('all',array('conditions'=>$condition1));
$n=sizeof($result1);
if($n==0)
{
date_default_timezone_set('Asia/Kolkata');
$current_date=date("d-m-Y");
$r_date= date('d-m-Y', strtotime($check_date. ' + 7 days'));
if(strtotime($r_date)<strtotime($current_date))
{
$to=$check_email;	
$from_name="HousingMatters";
$reply="donotreply@housingmatters.in";
$from="alerts@housingmatters.in";
//$subject="Invitation to HousingMatters";
$this->loadmodel('society');
$condition=array('society_id'=>$society_id);
$result_society=$this->society->find('all',array('conditions'=>$condition));
foreach($result_society as $data)
{
$society_name=$data['society']['society_name'];
}
$message_web="<div>
<img src='http://123.63.2.150:8080".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='http://123.63.2.150:8080".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Dear $name_user,</p>
<p>We at $society_name use HousingMatters - a dynamic web portal to interact with all owners/residents/staff for transparent & smart management of housing society affairs.</p>
<p>As you are an owner/resident/staff of $society_name, I have added your email address in HousingMatters portal.</p>
<p>Here are some of the important features related to our portal on HousingMatters:</p>
<ul type='disc'>
<li>You can log & track complaints, start new discussions, check your dues, post classifieds and many more in the portal.</li>
<li>You can receive important SMS & emails from your committee.</li>
</ul>
<p>Signup today to <a href='http://123.63.2.150:8080".$this->webroot."/hms/sign_up''>HousingMatters </a> for making life simpler for all your housing matters!</p>
<p>Regards,<br/>
Administrator of $society_name <br/>
<a href='http://www.housingmatters.co.in''>www.housingmatters.co.in </a>
</p><br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div >
</div>";
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
}
}
}
}
////////////////////////////End Invitation Member ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////// Society Setup //////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function master_sm_wing_ajax()
{
$this->layout='blank';
$s_society_id=(int)$this->Session->read('society_id');
$wing=$this->request->query['wing_name'];
$this->loadmodel('wing');
$conditions=array("wing_name" => $wing,'society_id'=>$s_society_id);
$result3 = $this->wing->find('all',array('conditions'=>$conditions));
$n3 = sizeof($result3);
if ($n3 > 0) {
echo "false";
} else {
echo "true";
}
}

function master_sm_wing()
{

$this->layout='session';
$s_society_id=$this->Session->read('society_id');
if($this->request->is('post')) 
{
$wing_name=$this->request->data['wing_name'];
$this->loadmodel('wing');
$i=$this->autoincrement('wing','wing_id');
$this->wing->saveAll(array("wing_id" => $i,"society_id"=> $s_society_id,"wing_name"=>$wing_name));

}
$this->loadmodel('wing');
$condition=array('society_id'=>$s_society_id);
$result=$this->wing->find('all',array('conditions'=>$condition)); 
$this->set('user_wing',$result);


}


/////////////////////////////// Start Master Sm Flat//////////////////////////////////////

function master_sm_flat()
{
$this->layout='session';
$s_society_id= (int)$this->Session->read('society_id');
$nnn = 0;
if(isset($this->request->data['flat_add']))
{
$count = $this->request->data['xyz'];

for($j=1; $j<=$count; $j++)
{
$wing_id = (int)$this->request->data['wing_name'.$j];
$flat_name = $this->request->data['flat_name'.$j];
$flat_type_id = (int)$this->request->data['flat_type'.$j];
$flat_master_id = (int)$this->request->data['area_id'.$j];
$noc_type = (int)$this->request->data['noctp'.$j];


$arr1[] = $wing_id.'-'.$flat_name;
$arr2[] = $flat_type_id.'-'.$flat_master_id;
$noc_arr[] = $noc_type;


$this->loadmodel('flat');
$condition=array("wing_id"=>$wing_id,"flat_name"=>$flat_name,'society_id'=>$s_society_id);
$result=$this->flat->find('all',array('conditions'=>$condition)); 
foreach($result as $collection)
{
$nnn=5;
}

$this->loadmodel('flat');
$condition=array("flat_type_id"=>$flat_type_id,"flat_master_id"=>$flat_master_id,'society_id'=>$s_society_id);
$result=$this->flat->find('all',array('conditions'=>$condition)); 
foreach($result as $collection)
{
$nnn=55;
}

/*$auto_id=$this->autoincrement('flat','flat_id');
$this->loadmodel('flat');
$this->flat->saveAll(array('flat_id' => $auto_id, 'wing_id' => $wing_id,"flat_name"=>$flat_name,"flat_type_id"=>$flat_type_id,"flat_master_id"=>$flat_master_id,'society_id'=>$s_society_id));*/
}
if($nnn == 5)
{
$vali = "Wing and is Already Exist";
$this->set('vali',$vali);
}
else if($nnn == 55)
{
$vali = "Flat Area is Already Exist";
 $this->set('vali',$vali);
}
else if(count($arr1) != count(array_unique($arr1))) 
{
 $vali = "Wing and Flat Name Should not be Same";
 $this->set('vali',$vali);
}
else if( count($arr2) != count(array_unique($arr2)))
{
$vali = "Flat Type and Flat Area Should not be same";
$this->set('vali',$vali);
}
else
{
for($l=0; $l<sizeof($arr1); $l++)
{
$one = $arr1[$l];
$two = $arr2[$l];
$one1 = explode('-',$one);
$two1 = explode('-',$two);
$wing = (int)$one1[0];
$flat = $one1[1];
$fl_tp = (int)$two1[0];
$fl_mas = (int)$two1[1];
$noc_id = (int)$noc_arr[$l];
$auto_id=$this->autoincrement('flat','flat_id');
$this->loadmodel('flat');
$this->flat->saveAll(array('flat_id' => $auto_id, 'wing_id' => $wing,"flat_name"=>$flat,"flat_type_id"=>$fl_tp,"flat_master_id"=>$fl_mas,'society_id'=>$s_society_id,"noc_ch_type"=>$noc_id));
}
}
}
//$this->loadmodel('flat');
//$i=$this->autoincrement('flat','flat_id');
//$this->flat->saveAll(array("flat_id" => $i,"wing_id"=> $wing_id,"flat_name"=>$flat_name));



$this->loadmodel('wing');
$condition=array('society_id'=>$s_society_id);
$result=$this->wing->find('all',array('conditions'=>$condition)); 
$this->set('user_wing',$result);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$result2);

$this->loadmodel('flat');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$cursor1);


$this->loadmodel('noc_type');
$cursor3 = $this->noc_type->find('all'); 
$this->set('cursor3',$cursor3);


}

/////////////////////////////// End Master Sm Flat////////////////////////////////////////

function master_sm_flat_ajax()
{
$this->layout='blank';
$flat=$this->request->query['con1'];
$res=(int)$this->request->query['con2'];
$this->set('r',$res);
$this->loadmodel('flat');
$conditions=array("flat_name" => $flat,'wing_id'=>$res);
$result3 = $this->flat->find('all',array('conditions'=>$conditions));
$n = sizeof($result3);
$this->set('n3',$n);
}

function society_setting()
{
$this->layout='session';



}

//////////////////////////////////////////////// End Society Setup
//////////////////////////////////////////////////////////////////////////////

///////////////////////////////Start Accounts Bank Receipt/////////////////////////////

function bank_receipt()
{
$this->layout='session';
App::import('', 'sendsms.php');
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
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


$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.receipt_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['receipt_id'];
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

$p=1;
while($p < 3)
{
if($p == 1)
{

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.receipt_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['receipt_id'];
}
if(empty($last))
{
$i=1000;
}	
else
{	
$i=$last;
}
$i++; 


$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.transaction_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('bank_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $received_from, "bill_reference" => $bill_no,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "sub_account_id" => $sub_account_id,   
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id,"member" => $member_id));
$this->bank_receipt->saveAll($multipleRowData);  
}
if($p == 2)
{

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.transaction_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('bank_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $received_from, "bill_reference" => $bill_no,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "sub_account_id" => $sub_account_id,  
"amount" => $amount, "amount_category_id" => 2, "society_id" => $s_society_id,"member" => $member_id));
$this->bank_receipt->saveAll($multipleRowData); 
}
$p++;
}



$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 1, "account_type" => 1,  "account_id" => $received_from, 
"current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 1, "account_type" => 1, "account_id" => $sub_account_id_a,
"current_date" => $current_date, "society_id" => $s_society_id));
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

$p=1;
while($p < 3)
{
if($p == 1)
{

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.receipt_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['receipt_id'];
}
if(empty($last))
{
$i=1000;
}	
else
{	
$i=$last;
}
$i++; 


$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.transaction_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('bank_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => 32, "bill_reference" => $reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "sub_account_id" => $sub_account_id,   
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id,"member" => $member_id,"receiver_name" => $received_from));
$this->bank_receipt->saveAll($multipleRowData);  
}
if($p == 2)
{

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_receipt.transaction_id'=> 'DESC');
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('bank_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => 32, "bill_reference" => $reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "sub_account_id" => $sub_account_id,   
"amount" => $amount, "amount_category_id" => 2, "society_id" => $s_society_id,"member" => $member_id,"receiver_name" => $received_from));
$this->bank_receipt->saveAll($multipleRowData); 
}
$p++;
}



$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 1, "account_type" => 1,  "account_id" => 32, 
"current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 1, "account_type" => 1, "account_id" => $sub_account_id_a,
"current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 


}

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection)

{
$d_receipt_id = (int)$collection['bank_receipt']['receipt_id'];	
}
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Bank Receipt #<?php echo $d_receipt_id; ?> has been generated
</div> 
<div class="modal-footer">
<a href="bank_receipt_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
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
$sms='Dear '.$user_name_sms.' we have received Rs '.$amount.' on '.$date_sms.' towards Society Maintanance dues. Cheque are subject to realization,Thanks '.$society_name_sms.'';
$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
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
if($member == 1)
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

///////////////////////////////End  Bank Receipt (Accounts)//////////////////////////

/////////////////////Start Bank Receipt Show Ajax (Accounts) ////////////////////////
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

$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id,"amount_category_id" => 1);
$cursor2=$this->bank_receipt->find('all',array('conditions'=>$conditions));
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
////////////////////////////////////////////////////// Start Bank Receipt Show Ajax (Accounts) //////////////////////////////////////////////////////////

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

/////////////////////////////////////////////////////// End Bank Receipt Amount Ajax(Accounts)/////////////////////////////////////////////////////////


//////////////////////////////////////////////////////// Start Rgular Bill Fetch (Accounts)//////////////////////////////////////////////////////////////
function regular_bill($user_id)
{
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $user_id,"status" => 0);
return $this->regular_bill->find('all',array('conditions'=>$conditions));
}
///////////////////// End Rgular Bill Fetch (Accounts)///////////////////////////////////////


///////////////////////////////////////////////////////////Start Function Fetch Amount Income heads(Accounts)///////////////////////////////////////////
function fetch_amount($data_d) 
{

$this->loadmodel('ledger_account');
$conditions=array("auto_id" => $data_d);
return $this->ledger_account->find('all',array('conditions'=>$conditions));


}

///////////////////////////////////////////////////////////End Function Fetch Amount Income heads (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////// Start Bank Receipt View (Accounts)///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function bank_receipt_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


}



////////////////////////////////////////////////////////// End Bank Receipt View (Accounts)////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////Start Bank Payment (Accounts)//////////////////////////

function bank_payment()
{
$this->layout='session';
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









$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_payment.receipt_id'=> 'DESC');
$cursor=$this->bank_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_payment']['receipt_id'];
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
 
$p = 1;
while($p < 3)
{
if($p == 1)
{

$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_payment.receipt_id'=> 'DESC');
$cursor=$this->bank_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_payment']['receipt_id'];
}
if(empty($last))
{
$bbb=0;
}	
else
{	
$bbb=$last;
}
$bbb++; 


$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_payment.transaction_id'=> 'DESC');
$cursor=$this->bank_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_payment']['transaction_id'];
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
$this->loadmodel('bank_payment');
$multipleRowData = Array( Array("transaction_id" => $i, "receipt_id" => $bbb,  "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $paid_to, "invoice_reference" => $invoice_reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "account_id" => $sub_account_id,  
"amount" => $amount, "amount_category_id" => 1, "society_id" => $s_society_id, "tds_id" => $tds_id,"account_type" => $account_type));
$this->bank_payment->saveAll($multipleRowData);  

}
if($p == 2)
{


$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('bank_payment.transaction_id'=> 'DESC');
$cursor=$this->bank_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['bank_payment']['transaction_id'];
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
$this->loadmodel('bank_payment');
$multipleRowData = Array( Array("transaction_id" => $i, "receipt_id" => $bbb,  "current_date" => $current_date, 
"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"user_id" => $paid_to, "invoice_reference" => $invoice_reference,"narration" => $description, "receipt_mode" => $receipt_mode,
"receipt_instruction" => $receipt_instruction, "account_id" => $sub_account_id,  
"amount" => $amount, "amount_category_id" => 2, "society_id" => $s_society_id, "tds_id" => $tds_id,"account_type" => $account_type));
$this->bank_payment->saveAll($multipleRowData);  

}
$p++;			
}

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
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 4, "account_type" => $account_type, "account_id" => $paid_to,
"current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 



$sub_account_id_a = (int)$sub_account_id;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $total_tds_amount, "amount_category_id" => 2, "module_id" => 4, "account_type" => 1, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 

if($tds_amount > 0)
{
$sub_account_id_t = 16;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $bbb, 
"amount" => $tds_amount, "amount_category_id" => 2, "module_id" => 4, "account_type" => 2, "account_id" => $sub_account_id_t, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);
}
//////////////////END LEDGER ENTRY/////////////////////
$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->bank_payment->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['bank_payment']['receipt_id'];	
}
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Payment Voucher No. <?php echo $d_receipt_id; ?> is  generated successfully
</div> 
<div class="modal-footer">
<a href="bank_payment_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->


<?php
}
}

/////////////////////////End Bank Payment(Accounts)///////////////////////////////////

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


$this->loadmodel('bank_payment');
$conditions=array("society_id" => $s_society_id,"amount_category_id" => 1);
$cursor2=$this->bank_payment->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);






}

//////////////////////////////////////////End Bank Payment Show Ajax (Accounts)////////////////////////////////////////////////////////////////////////



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

//////////////////////// Start Bank Payment View (Accounts) ////////////////////////
function bank_payment_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
}
//////////////////////// End Bank Payment View (Accounts) ///////////////////////////

///////////////////// Start Petty cash Receipt (Accounts)///////////////////////////

function petty_cash_receipt()
{
$this->layout='session';
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







$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_receipt.receipt_id'=> 'DESC');
$cursor=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_receipt']['receipt_id'];
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
$p = 1;
while($p < 3)
{
if($p == 1)
{


$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_receipt.receipt_id'=> 'DESC');
$cursor=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_receipt']['receipt_id'];
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


$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_receipt.transaction_id'=> 'DESC');
$cursor=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('petty_cash_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i, "prepaired_by" => $s_user_id,
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "user_id" => $user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $ammount, "amount_category_id" => 1, 
"society_id" => $s_society_id, "category_id" => 3));
$this->petty_cash_receipt->saveAll($multipleRowData);  

}
if($p == 2)
{

$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_receipt.transaction_id'=> 'DESC');
$cursor=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_receipt']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('petty_cash_receipt');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i,  "prepaired_by" => $s_user_id,
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "user_id" => $user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $ammount, "amount_category_id" => 2, 
"society_id" => $s_society_id, "category_id" => 3));
$this->petty_cash_receipt->saveAll($multipleRowData);  
}
$p++;
}


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $ammount, "amount_category_id" => 2, "module_id" => 3, "account_type" => $account_type, "account_id" => $user_id, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 




$sub_account_id_a = (int)$account_head;


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $ammount, "amount_category_id" => 1, "module_id" => 3, "account_type" => 2, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 


$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions));
foreach ($cursor1 as $collection) 
{
$d_receipt_id = (int)$collection['petty_cash_receipt']['receipt_id'];	 
}
?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Receipt No. <?php echo $d_receipt_id; ?> is  Generated Successfully
</div> 
<div class="modal-footer">
<a href="petty_cash_receipt_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->




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



$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
}

//////////////////////////////////// End Petty Cash Receipt Show Ajax (Accounts)///////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////Start Petty Cash Receipt Ajax (Accounts)//////////////////////////////////////////////////////////
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





///////////////////////////////////////////////////////End Petty Cash Receipt Ajax (Accounts)//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Start Petty Cash Receipt View (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function petty_cash_receipt_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);









}

//////////////////////////////////////////////////////////End Petty Cash Receipt View (Accounts) ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////// Start Petty Cash Payment (Accounts) /////////////////////////// 

function petty_cash_payment()
{
$this->layout='session';
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


$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_payment.receipt_id'=> 'DESC');
$cursor=$this->petty_cash_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_payment']['receipt_id'];
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

$p = 1;
while($p < 3)
{
if($p == 1)
{
$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_payment.receipt_id'=> 'DESC');
$cursor=$this->petty_cash_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_payment']['receipt_id'];
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

$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_payment.transaction_id'=> 'DESC');
$cursor=$this->petty_cash_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_payment']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('petty_cash_payment');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i,  "user_id" => $user_id, 
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $amount, "amount_category_id" => 1, 
"society_id" => $s_society_id));
$this->petty_cash_payment->saveAll($multipleRowData);  

}
if($p == 2)
{

$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$order=array('petty_cash_payment.transaction_id'=> 'DESC');
$cursor=$this->petty_cash_payment->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['petty_cash_payment']['transaction_id'];
}
if(empty($last))
{
$auto=0;
}	
else
{	
$auto=$last;
}
$auto++; 
$this->loadmodel('petty_cash_payment');
$multipleRowData = Array( Array("transaction_id" => $auto, "receipt_id" => $i,  "user_id" => $user_id, 
"current_date" => $current_date, "account_type" => $account_type,"transaction_date" => $date, "prepaired_by" => $s_user_id, 
"narration" => $narration, "account_head" => $account_head,  "amount" => $amount, "amount_category_id" => 2, 
"society_id" => $s_society_id));
$this->petty_cash_payment->saveAll($multipleRowData);  

}
$p++;
}

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
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 1, "module_id" => 2, "account_type" => $account_type, "account_id" => $user_id, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 


$sub_account_id_a =  (int)$account_head;
$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['ledger']['auto_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $i, 
"amount" => $amount, "amount_category_id" => 2, "module_id" => 2, "account_type" => 2, "account_id" => $sub_account_id_a, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData); 




$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$cursor3=$this->petty_cash_payment->find('all',array('conditions'=>$conditions));
foreach($cursor3 as $collection)
{
$d_receipt_id = (int)$collection['petty_cash_payment']['receipt_id'];		
}
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Petty Cash Voucher <?php echo $d_receipt_id; ?> is  generated successfully
</div> 
<div class="modal-footer">
<a href="petty_cash_payment_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php
}
///////////////////////////////////////////
//////////////////////////////////////////
}


//////////////////////// End Petty cash Payment (Accounts) ////////////////////////////

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
/////////////////////////////////////////////End Petty Cash Payment Ajax (Accounts) ///////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Petty Cash Payment Ajax(amount_cal_p)(Accounts) //////////////////////////////////////////////////////
function amount_cal_p()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);	

$tds = (int)$this->request->query('data');
$amount = $this->request->query('amount');

$this->set('tds',$tds);
$this->set('amount',$amount);



}

//////////////////////////////////////////// End Petty Cash Payment Ajax(amount_cal_p)(Accounts) ////////////////////////////////////////////////////////

///////////////////////////////////////// Start Fetch tds (Accounts) ////////////////////////////////////////////////////////////////////////////////////
function fetch_tds($auto_id)
{
$this->loadmodel('master_tds');
$conditions=array("auto_id" => $auto_id);
return $this->master_tds->find('all',array('conditions'=>$conditions));	

}
///////////////////////////////////////// End Fetch tds (Accounts) ////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////// Start Petty cash Payment View (Accounts)/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function petty_cash_payment_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);



}

//////////////////////////////////////////////////////////// End Petty cash Payment View (Accounts) /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->petty_cash_payment->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);







}


////////////////////////End Petty Cash Payment Show Ajax (Accounts)//////////////////////

//////////////////////////////////////////////Start Fix Deposit Add (Accounts) //////////////////////////////////////////////////////////////////////////
function fix_deposit_add()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


}

//////////////////////////////////////////////End Fix Deposit Add (Accounts) //////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////// Start Fix Deposit View (Accounts) ////////////////////////////////////////////////////////
function fix_deposit_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


}










//////////////////////////////////////////////////////////// End Fix Deposit View (Accounts) ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////Start Expense Tracker View (Accounts)///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function expense_tracker_view()
{
$this->layout='session';
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

///////////////////////////////////////////////////////////Start Function expense Tracker View Fetch1 (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function expense_tracker_fetch1($auto_id) 
{
$this->loadmodel('expense_tracker');
$conditions=array("party_head" => $auto_id,"amount_category_id" => 2);
return $this->expense_tracker->find('all',array('conditions'=>$conditions));


}

///////////////////////////////////////////////////////////End Function Fetch expense Tracker View Fetch1 (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////Start Expense Tracker Add (Accounts) ///////////////////////

function expense_tracker_add()
{
$this->layout='session';
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



if(isset($this->request->data['ext_add']))
{

$posting_date = $this->request->data['posting_date'];
$posting_date = date("Y-m-d", strtotime($posting_date));
$posting_date = new MongoDate(strtotime($posting_date));
//$file_name = $_FILES['uploaded']['name'];
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
"amount" => $invoice_amount, "amount_category_id" => 1 , "invoice_reference" => $invoice_reference));
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
"amount" => $invoice_amount, "amount_category_id" => 2, "module_id" => 6, "account_type" =>  1, "account_id" => $sub_account_id_p, 
"current_date" => $current_date, "society_id" => $s_society_id));
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
"amount" => $invoice_amount, "amount_category_id" => 1, "module_id" => 6, "account_type" => 2,  
"account_id" => $sub_account_id_e, "current_date" => $current_date, "society_id" => $s_society_id));
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
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Expense Voucher #<?php echo $d_receipt_id; ?> is  generated successfully
</div> 
<div class="modal-footer">
<a href="expense_tracker_view"   class="btn green">OK</a>
</div>
</div>
<!----alert--------------> 
<?php		

////////////////////////////////////////////////////////////
}
}

///////////////////////End Expense Tracker Add (Accounts) ////////////////////////////


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
////////////////////// Start Expense Tracker Show Ajax ///////////////////////////////////
function expense_tracker_show_ajax()
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

////////////////// Start Expense Tracker View History (Accounts)//////////////////////

function expense_tracker_view_history()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);
$vendor_id = (int)$this->request->query['b'];

$this->set('vendor_id',$vendor_id);


$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $vendor_id);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$vendor_name = $collection['ledger_sub_account']['name'];
}
$this->set('vendor_name',$vendor_name);


$this->loadmodel('expense_tracker');
$conditions=array("party_head" => $vendor_id);
$cursor2=$this->expense_tracker->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);




}

/////////////////////////////////////////////// End Expense Tracker View History (Accounts)//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////Start Expense Tracker View History Approver Fetch (Accounts) ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function approver($user_id)
{
$this->loadmodel('user');
$conditions=array("user_id" => $user_id);
return $this->user->find('all',array('conditions'=>$conditions));
}
/////////////////////////////////////// End Expense Tracker View History Approver Fetch (Accounts)////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////// Start Expense Tracker View History Expense Head (Accounts) /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function expense_head($expense_head)
{
$this->loadmodel('ledger_account');
$conditions=array("auto_id" => $expense_head);
return $this->ledger_account->find('all',array('conditions'=>$conditions));
}
///////////////////////////////////////// End Expense Tracker View History Expense Head Fetch (Accounts) /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////// Start report_excel_expense_tracker (Accounts)////////////////////////////////////////////////////////////////////
function report_excel_expense_tracker()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$vendor_id = (int)$this->request->query['c'];
$this->set('vendor_id',$vendor_id);



$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $vendor_id);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);



$this->loadmodel('expense_tracker');
$conditions=array("party_head" => $vendor_id);
$cursor2=$this->expense_tracker->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


}


/////////////////////////////////////// End report_excel_expense_tracker (Accounts)////////////////////////////////////////////////////////////////////

////////////////////////////////////// Start Fix Asset View (Accounts)///////////////////////////////////////////////////////////////////////////////////
function fix_asset_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);



}


/////////////////////////////////////// End Fix Asset View (Accounts)////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////Start Fix Asset Add (Accounts)////////////////////////////////////////// ////////////////////////////////////////////
function fix_asset_add()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$this->loadmodel('fix_asset');
$conditions=array("society_id" => $s_society_id);
$order=array('fix_asset.receipt_id'=> 'DESC');
$cursor=$this->fix_asset->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['fix_asset']['receipt_id'];
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


$this->loadmodel('ledger_account');
$conditions=array("group_id" => 4);
$cursor1=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => 15);
$cursor2=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


///////////////////////////////////////////
//////////////////////////////////////////
if(isset($this->request->data['fix_add']))
{

$asset_category_id = (int)$this->request->data['asset_category'];
$asset_name = $this->request->data['name'];
$asset_description = $this->request->data['description'];
$purchase_date = $this->request->data['purchase_date'];
$purchase_cost = $this->request->data['cost'];
$supplier = (int)$this->request->data['vendor'];
$warranty_period_from = $this->request->data['from'];
$warranty_period_to = $this->request->data['to'];
$schedule = $this->request->data['schedule'];	


$current_date = date("Y-m-d");
$current_date = new MongoDate(strtotime($current_date));

$purchase_date = date("Y-m-d", strtotime($purchase_date));
$purchase_date = new MongoDate(strtotime($purchase_date));


$warranty_period_from = date("Y-m-d", strtotime($warranty_period_from));
$warranty_period_from = new MongoDate(strtotime($warranty_period_from));

$warranty_period_to = date("Y-m-d", strtotime($warranty_period_to));
$warranty_period_to = new MongoDate(strtotime($warranty_period_to));




$this->loadmodel('fix_asset');
$conditions=array("society_id" => $s_society_id);
$order=array('fix_asset.receipt_id'=>'DESC');
$cursor=$this->fix_asset->find('all',array('conditions'=>$conditions,'order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$r_last = $collection['fix_asset']['receipt_id']; 
}
if(empty($r_last))
{
$r = 0;
}	
else
{	
$r = $r_last;
}
$r++;






$this->loadmodel('fix_asset');
$order=array('fix_asset.auto_id'=> 'DESC');
$cursor=$this->fix_asset->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['fix_asset']["auto_id"];
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
$this->loadmodel('fix_asset');
$multipleRowData = Array( Array("auto_id" => $i, "receipt_id" => $r, "asset_category_id" => $asset_category_id, 
"asset_name" => $asset_name, "narration" => $asset_description, 
"purchase_date" => $purchase_date, "purchase_cost" => $purchase_cost, "supplier" => $supplier, 
"warranty_period_from" => $warranty_period_from,
"warranty_period_to" => $warranty_period_to, "schedule" => $schedule, "society_id" => $s_society_id));
$this->fix_asset->saveAll($multipleRowData);   


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection["auto_id"]; 
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
"amount" => $purchase_cost, "amount_category_id" => 2, "module_id" => 7, "account_type" => 1,"account_id" => $supplier,
"current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);   


$this->loadmodel('ledger');
$order=array('ledger.auto_id'=> 'DESC');
$cursor=$this->ledger->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection["auto_id"]; 
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
"amount" => $purchase_cost, "amount_category_id" => 1, "module_id" => 7, "account_type" => 2, 
"account_id" => $asset_category_id, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);   



?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
The Fix Asset Receipt No. <?php echo $r; ?> is Generated Successfully
</div> 
<div class="modal-footer">
<a href="fix_asset_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php		
}




///////////////////////////////////////////////
///////////////////////////////////////////////


}

////////////////////////////////////End Fix Asset Add (Accounts)////////////////////////////////////////// ////////////////////////////////////////////

////////////////////////////////////////// Start Fix Asset Show Ajax (Accounts) /////////////////////////////////////////////////////////////////////////

function fix_asset_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);

$from = $this->request->query('date1');
$to = $this->request->query('date2');
$this->set('from',$from);
$this->set('to',$to);


$this->loadmodel('fix_asset');
$conditions=array("society_id" => $s_society_id);
$cursor1=$this->fix_asset->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('fix_asset');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->fix_asset->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}

////////////////////////////////////////// End Fix Asset Show Ajax (Accounts) /////////////////////////////////////////////////////////////////////////
//////////////////////////////////////// Start Ledger Sub Account Fetch (Accounts)///////////////////////////////////////////////////////////////////////
function ledger_sub_account_fetch($auto_id) 
{

$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $auto_id);
return $this->ledger_sub_account->find('all',array('conditions'=>$conditions));


}

//////////////////////////////////////// End Ledger Sub Account Fetch (Accounts)///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// Start Ledger (Accounts)//////////////////////////////////////////////////////////////////////////////////////
function ledger()
{
$this->layout='session';
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

}
/////////////////////////////////////////// End Ledger (Accounts)//////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////// Start Ledger Ajax (Accounts) //////////////////////////////////////////////////////////////////////////////
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



///////////////////////////////////////////// End Ledger Ajax (Accounts) //////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////// Start Ledger Show Ajax (Accounts)////////////////////////////////////////////////////////////////////////
function ledger_show_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->set('s_role_id',$s_role_id);


$main_id = (int)$this->request->query('main_id');
$sub_id = (int)$this->request->query('sub_id');
$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');
$this->set('main_id',$main_id);
$this->set('sub_id',$sub_id);
$this->set('date111',$date1);
$this->set('date222',$date2);

$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
}
$year = date('Y');
$date_f = $from.'-'.$year;

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
$this->set('society_name',$society_name);







}
/////////////////////////////////////////////// End Ledger Show Ajax (Accounts)////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////// Start Module Fetch (Accounts)////////////////////////////////////////////////////////////////////////////
function module_fetch($module_id) 
{

$this->loadmodel('account_category');
$conditions=array("ac_id" => $module_id);
return $this->account_category->find('all',array('conditions'=>$conditions));
}

/////////////////////////////////////////////// End Module Fetch (Accounts)//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////// Start Module Name Fetch Date (Accounts)/////////////////////////////////////////////////////////////////
function module_main_fetch($module_name,$receipt_id) 
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel($module_name);
$conditions=array("receipt_id" => $receipt_id, "society_id" => $s_society_id);
return $this->$module_name->find('all',array('conditions'=>$conditions));
}



//////////////////////////////////////////////// End Module Name Fetch Date (Accounts)/////////////////////////////////////////////////////////////////

////////////////////////////////////////////// Start Module Fetch (Accounts) ////////////////////////////////////////////////////////////////////////////

function module_main_fetch2($module_name) 
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel($module_name);
$conditions=array("society_id" => $s_society_id);
return $this->$module_name->find('all',array('conditions'=>$conditions));
}






////////////////////////////////////////////// End Module Fetch (Accounts) ////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////Start Trial Balance Module Fetch(Accounts)///////////////////////////////////////////////////////////////////

function module_main_fetch3($module_name,$receipt_id) 
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel($module_name);
$conditions=array("society_id" => $s_society_id, "receipt_id" => $receipt_id);
return $this->$module_name->find('all',array('conditions'=>$conditions));
}





//////////////////////////////////////////End Trial Balance Module Fetch(Accounts)///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////// Start Amount Category Fetch (Accounts)/////////////////////////////////////////////////////////////////
function amount_category($amount_category_id) 
{
$this->loadmodel('amount_category');
$conditions=array("amount_category_id" => $amount_category_id);
return $this->amount_category->find('all',array('conditions'=>$conditions));
}

//////////////////////////////////////////////// End Amount Category Fetch (Accounts)/////////////////////////////////////////////////////////////////

///////////////////////////////////////////////// Start Accounts Group Fetch (Accounts) ///////////////////////////////////////////////////////////////
function accounts_group($group_id) 
{
$this->loadmodel('accounts_group');
$conditions=array("auto_id" => $group_id);
return $this->accounts_group->find('all',array('conditions'=>$conditions));
}


///////////////////////////////////////////////// End Accounts Group Fetch (Accounts) ///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////// Start Journal View (Accounts) //////////////////////////////////////////////////////////////////////////
function journal_view()
{
$this->layout='session';
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

////////////////////////////////////////////////End Journal View Ajax(Accounts)//////////////////////////////////////////////////////////////////////////


/////////////////////////////// Start Journal Add (Accounts)///////////////////

function journal_add()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
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
$l_type_name_id=(int)$this->request->data['l_type_name'.$i];	
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
"amount" => $amount, "amount_category_id" => $amount_category_id, "module_id" => 5, "account_type" => 1, "account_id" => $l_type_name_id,
"current_date" => $current_date, "society_id" => $s_society_id));
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
"amount" => $amount, "amount_category_id" => $amount_category_id, "module_id" => 5, "account_type" => 2, 
"account_id" => $l_type_id, "current_date" => $current_date, "society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);	
}

}

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Journal No. #<?php echo $receipt_no; ?> is  generated successfully
</div> 
<div class="modal-footer">
<a href="journal_view"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php
}
}

///////////////////////////// End Journal Add (Accounts)////////////////////////////

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

///////////////////////////////////////////////////// Start Journal Add Row (Accounts)///////////////////////////////////////////////////////////////////
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

/////////////////////////Start It Regular Bill (Accounts) /////////////////////////////

function it_regular_bill()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

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

$this->loadmodel('regular_bill');
$order=array('regular_bill.regular_bill_id'=> 'ASC');
$cursor=$this->regular_bill->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$d_from = $collection['regular_bill']['bill_daterange_from'];
$d_to = $collection['regular_bill']['bill_daterange_to'];
}
$datefb = date('Y-m-d',@$d_from->sec);
$datetb = date('Y-m-d',@$d_to->sec);

$this->set('datefb',$datefb);
$this->set('datetb',$datetb);

if(isset($this->request->data['sub1']))
{
$from = $this->request->data['from'];
//$to = $this->request->data['to'];
@$penalty = $this->request->data['pen'];
$due_date = $this->request->data['due_date'];
//$i_head = $this->request->data['i_head'];
//$tax = (int)$this->request->data['tax'];
$description = $this->request->data['description'];
//$terms = $this->request->data['terms'];
$period_id = (int)$this->request->data['bill_p'];
$fromm = date("Y-m-d", strtotime($from));
$fromm = new MongoDate(strtotime($fromm));

if($period_id == 1)
{
$to = date('Y-m-d', strtotime("+1 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 2)
{
$to = date('Y-m-d', strtotime("+4 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 3)
{
$to = date('Y-m-d', strtotime("+6 months", strtotime($from)));
$to = date('Y-m-d', strtotime("-1 days", strtotime($to)));
}
else if($period_id == 4)
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

//$regular[] = array($from,$to,$due_date,$i_head,$tax,$description,$terms);
//$ih2 = implode(',',$i_head);
//$terms2 = implode(',',$terms);

$f1=$this->encode($from,'housingmatters');
$t1=$this->encode($to,'housingmatters');
$due1=$this->encode($due_date,'housingmatters');
//$ih3=$this->encode($ih2,'housingmatters');
//$tax3=$this->encode($tax,'housingmatters');
$desc1=$this->encode($description,'housingmatters');
//$terms3=$this->encode($terms2,'housingmatters');
$p_id = $this->encode($period_id,'housingmatters');
$pen = $this->encode($penalty,'housingmatters');

$this->response->header('Location','regular_bill_view2?f='.$f1.'&t='.$t1.'&due='.$due1.'&d='.$desc1.'&p='.$p_id.'&pen='.$pen.' ');

?>
<script>
//window.location.href="regular_bill_view2?f=<?php echo $f1; ?>&t=<?php echo $t1; ?>&due=<?php echo $due1; ?>&ih=<?php echo $ih3; ?>&tax=<?php echo $tax3; ?>&d=<?php echo $desc1; ?>&tem=<?php echo $terms3; ?>&p=<?php echo $p_id; ?>";
</script>
<?php
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

}



/////////////////////// End It Regular Bill (Accounts) ////////////////////////////////

////////////////////////Start It Reports Regular (Accounts)////////////////////////////
function it_reports_regular()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	



}
//////////////////////// End It Reports Regular (Accounts)//////////////////////

/////////////////////// Start It Reports Supplimentry Bill (Accounts)///////////////

function it_reports_supplimentry()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('adhoc_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor1=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	
}

/////////////////////////////////////////////////////// End It Reports Supplimentry Bill (Accounts)//////////////////////////////////////////////////////

////////////////////////////////////////////// Start It Reports Supplimentry Ajax (Accounts)/////////////////////////////////////////////////////////////
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
////////////////////////////////////////////// End It Reports Supplimentry Ajax (Accounts)/////////////////////////////////////////////////////////////


////////////////////////////////////////////////// Start Income Heads Report (Accounts)//////////////////////////////////////////////////////////////////
function income_heads_report()
{
$this->layout='session';
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
}
$this->set('society_name',$society_name);

$from = $this->request->query('date1');
$to = $this->request->query('date2');

$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=> 'ASC');
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor1',$cursor1);	

$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id);
$cursor2=$this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	

}
////////////////////////////// End Income Heads Report Ajax(Accounts)////////////////

/////////////////// Start Income Head Fetch Regular Bill (Accounts)//////////////////
function it_income_head_fetch($user_id,$date1,$date2)
{

$this->loadmodel('regular_bill');
$conditions=array("bill_daterange_from" => array('$gt' => $date1),"bill_daterange_to" => array('$lte' => $date2),"bill_for_user"=>$user_id);
return $this->regular_bill->find('all',array('conditions'=>$conditions));
}
////////////////////// End Income Head Fetch Regular Bill (Accounts)///////////////


////////////////// Start Income Head Amount Fetch (Accounts)/////////////////////////////

function income_head_amount($inhe)
{

$this->loadmodel('ledger_account');
$conditions=array("auto_id" =>$inhe);
return $this->ledger_account->find('all',array('conditions'=>$conditions));


}

/////////////////////// End Income Head Amount Fetch (Accounts)///////////////////////

///////////////// Start It Supplimentry Bill (Accounts)////////////////////////////////

function it_supplimentry_bill()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

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
$conditions=array("society_id" => $s_society_id,"delete_id"=>0,"group_id"=>8);
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

?>
<script>
//window.location.href="supplimentry_bill_view2?f=<?php echo $f1; ?>&t=<?php echo $t1; ?>&due=<?php echo $due1; ?>&tax=<?php echo $tax3; ?>&d=<?php echo $desc3; ?>&tem=<?php echo $terms4; ?>&tp=<?php echo $bill_type3; ?>&pn=<?php echo $person3; ?>&com=<?php echo $com3; ?>&amt=<?php echo $amt6; ?>";
</script>
<?php
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


?>
<script>
//window.location.href="supplimentry_bill_view2?f=<?php echo $f2; ?>&t=<?php echo $t2; ?>&due=<?php echo $due3; ?>&ih=<?php echo $ih3; ?>&tax=<?php echo $tax3; ?>&d=<?php echo $desc3; ?>&tem=<?php echo $tem3; ?>&tp=<?php echo $bill_tp3; ?>&res=<?php echo $resi3; ?>";
</script>
<?php
}
}


$this->loadmodel('ledger_sub_account');
$conditions=array("society_id"=>$s_society_id, "ledger_id" => 34,"deactive"=>0);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

$this->loadmodel('ledger_account');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0,"group_id"=>8);
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

/////////////////// Start Supplimentry Bill View(Accounts)////////////////////////////
function supplimentry_bill_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$auto_id = (int)$this->request->query('bill');


$this->loadmodel('adhoc_bill');
$conditions=array("adhoc_bill_id"=>$auto_id,"society_id" => $s_society_id);
$cursor=$this->adhoc_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['adhoc_bill']['bill_html'];	
}

$this->set('bill_html',$bill_html);

}
///////////////////////////////////////////////////////// End Supplimentry Bill View(Accounts)///////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Start Regular Bill View (Accounts)//////////////////////////////////////////////////////////////
function regular_bill_view()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$auto_id = (int)$this->request->query('bill');

$this->loadmodel('regular_bill');
$conditions=array("regular_bill_id"=>$auto_id,"society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['regular_bill']['bill_html'];	
}

$this->set('bill_html',$bill_html);

}
//////////////////////////////////////////////////////// End Regular Bill View (Accounts)////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////// Start It Setup (Accounts) ////////////////////////////////////////////////////////////////////////




function it_setup()
{
$this->layout='session';
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

$this->loadmodel('terms_condition');
$order=array('terms_condition.terms_conditions_id'=> 'DESC');
$cursor=$this->terms_condition->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['terms_condition']["terms_conditions_id"];
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
$this->loadmodel('terms_condition');
$multipleRowData = Array( Array("terms_conditions_id" => $i,"terms_conditions"=> $terms,"society_id"=>$s_society_id, "status" => 1));
$this->terms_condition->saveAll($multipleRowData);	
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
$this->loadmodel('terms_conditions');
$conditions=array("society_id"=>$s_society_id,"status"=>1);
$cursor=$this->terms_conditions->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)		
{
$terms_id = (int)$collection['terms_conditions']['terms_conditions_id'];	

if(isset($this->request->data['edt_tms'.$terms_id]))
{

$tms = $this->request->data['edit_terms'.$terms_id];

$this->loadmodel('terms_conditions');
$this->terms_conditions->updateAll(array("terms_conditions" => $tms),array("terms_conditions_id" => $terms_id));	
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Terms & Condition Updated Successfully
</div> 
<div class="modal-footer">
<a href="it_setup"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php
}

}


$this->loadmodel('terms_conditions');
$conditions=array("society_id"=>$s_society_id,"status"=>1);
$cursor1=$this->terms_conditions->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);									
}




///////////////////////////////////////////////////////// End It Setup (Accounts) ///////////////////////////////////////////////////////////////////////
/////////////////////Start It Due date /////////////////////////////////////////////////

function it_due_date()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id);
$order = array('regular_bill.one_time_id'=> 'ASC');
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions,'order'=>$order));
$this->set('cursor1',$cursor1);


if(isset($this->request->data['sub']))
{
$day = (int)$this->request->data['due_day'];


$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("due_days" => $day),array("update_id" => 5));	
?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Due Days updated successfully
</div> 
<div class="modal-footer">
<a href="it_due_date"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php

}
}

/////////////////End It Due date /////////////////////////////////////////////////////////


////////////////////////Start Over Due Report (Accounts)/////////////////////////////
function over_due_report()
{

$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		


$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id, "tenant"=>1,"deactive"=>0);
$cursor1 = $this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}

////////////////////// End Over Due Report (Accounts)////////////////////////////////

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


if($amount_category_id == 1)
{
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


//////////////////////////// Start Ledger Excel (Accounts)/////////////////////////////////////
function ledger_excel()
{
$this->layout="";
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
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

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

$excel.="									<tr>
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
					
$opening_balance = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount_o = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];

$module_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_fetch'),array('pass'=>array($module_id)));										
foreach ($module_fetch as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}

$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($module_name,$receipt_id)));
foreach ($module_date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
$narration = @$collection[$module_name]['narration'];
$remark = @$collection[$module_name]['remark'];
}

$amount_category_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'amount_category'),array('pass'=>array($amount_category_id)));						
foreach ($amount_category_fetch as $collection) 
{
$amount_category = @$collection['amount_category']['amount_category'];
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

$excel.="
<tr>
<th colspan='3'></th>
<th colspan='2'>Opening Balance:</th>
<th>";
$opening_balance = $opening_balance + ($open_bal_import);
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
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];

$module_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));
foreach ($module_fetch2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
$module_name2 = @$collection['account_category']['module_name'];
}

$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($module_name,$receipt_id)));

foreach ($module_date_fetch2 as $collection) 
{
$date = @$collection[$module_name]['transaction_date'];
if(empty($date))
{
$date = @$collection[$module_name]['posting_date'];	
}
if(empty($date))
{
$date = @$collection[$module_name]['purchase_date'];	
}
if(empty($date))
{
$date = @$collection[$module_name]['date'];	
}
$narration = @$collection[$module_name]['narration'];
if(empty($narration))
{
$narration = @$collection[$module_name]['remark'];
}
if(empty($narration))
{
$narration = @$collection[$module_name]['description'];	
}
$remark = @$collection[$module_name]['remark'];
}

$amount_category_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' =>'amount_category'),array('pass'=>array($amount_category_id)));
foreach ($amount_category_fetch2 as $collection) 
{
$amount_category = @$collection['amount_category']['amount_category'];
}

if($sub_account_id == $sub_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 1)
{
$date = date('d-m-Y',$date->sec);	

$excel.="<tr>
<td>$date</td>
<td>$narration</td>
<td>$module_name2</td>
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
$closing_balance = $opening_balance - $total_debit + $total_credit;
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
$opening_balance = $opening_balance.'Cr';
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

$opening_balance = 0;
$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->ledger->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount_o = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];

$account_category_fetch = $this->requestAction(array('controller' => 'hms', 'action' =>'module_fetch'),array('pass'=>array($module_id)));									
foreach ($account_category_fetch as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}

$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($module_name,$receipt_id)));   
foreach ($module_date_fetch3 as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
$narration = @$collection[$module_name]['narration'];
$remark = @$collection[$module_name]['remark'];
}

$amount_category_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' =>'amount_category'),array('pass'=>array($amount_category_id)));
foreach ($amount_category_fetch3 as $collection) 
{
$amount_category = @$collection['amount_category']['amount_category'];
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
$excel.="<tr>
<th colspan='3'></th>
<th colspan='2'>Opening Balance:</th>
<th>";
$opening_balance = $opening_balance + ($open_bal_import);
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
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];

$account_category_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));									
foreach ($account_category_fetch2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
$module_name2 = @$collection['account_category']['module_name'];
}

$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($module_name,$receipt_id)));   
foreach ($module_date_fetch4 as $collection) 
{
$date = @$collection[$module_name]['transaction_date'];
if(empty($date))
{
$date = @$collection[$module_name]['posting_date'];	
}
if(empty($date))
{
$date = @$collection[$module_name]['purchase_date'];	
}
if(empty($date))
{
$date = @$collection[$module_name]['date'];	
}
$narration = @$collection[$module_name]['narration'];
if(empty($narration))
{
$narration = @$collection[$module_name]['remark'];
}
if(empty($narration))
{
$narration = @$collection[$module_name]['description'];	
}
$remark = @$collection[$module_name]['remark'];
}

$amount_category_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' =>'amount_category'),array('pass'=>array($amount_category_id)));
foreach ($amount_category_fetch4 as $collection) 
{
$amount_category = @$collection['amount_category']['amount_category'];
} 

if($sub_account_id == $main_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 2)
{
$date = date('d-m-Y',$date->sec);	

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
$closing_balance = $opening_balance - $total_debit + $total_credit;
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
</tr></table>";
}
echo $excel;
}
//////////////////////////// End Ledger Excel (Accounts)/////////////////////////////


////////////////////////// Start Master Opening Balance (Accounts)///////////////////
function master_opening_balance()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);



if(isset($this->request->data['sub']))
{
$year = $this->request->data['year'];
$la_id = (int)$this->request->data['le_ac'];
$opening_bal = $this->request->data['balance'];

if($la_id == 15 || $la_id == 33 || $la_id == 34 || $la_id == 35)
{
$lsa_id = (int)$this->request->data['su_le_ac'];

$opening_balance_id=$this->autoincrement('opening_balance','opening_balance_id');
$this->loadmodel('opening_balance');
$this->opening_balance->saveAll(array('opening_balance_id' => $opening_balance_id,'year' => $year,'account_type'=> 1, 'account_id' => $lsa_id,'opening_balance_amount' => $opening_bal,
"society_id" => $s_society_id));

}
else
{

$opening_balance_id=$this->autoincrement('opening_balance','opening_balance_id');
$this->loadmodel('opening_balance');
$this->opening_balance->saveAll(array('opening_balance_id' => $opening_balance_id,'year' => $year,'account_type'=> 2, 'account_id' => $la_id,'opening_balance_amount' => $opening_bal,"society_id" => $s_society_id));


}

?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Record Inserted successfully
</div> 
<div class="modal-footer">
<a href="master_opening_balance"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->
<?php
}


}
////////////////////////// End Master Opening Balance (Accounts)/////////////////////////////



/////////////////////// Start Opening Balance Ajax (Accounts)/////////////////////////////////
function opening_balance_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$value1 = (int)$this->request->query('value1');
$this->set('value1',$value1);

}


/////////////////////// End Opening Balance Ajax (Accounts)////////////////////////////////////



////////////////////////// Start Opening Balance Report (Account)//////////////////////////////
function opening_balance_report()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);

}
////////////////////////// Start Opening Balance Report (Account)//////////////////////////////



////////////////////////// Start Opening Balance Report Ajax (Accounts)///////////////////////
function opening_balance_report_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$value1 = (int)$this->request->query('ff');

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "ledger_id" => $value1);
$cursor1=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);



}
////////////////////////// End Opening Balance Report Ajax (Accounts)/////////////////////////


//////////////////////////// Start Opening Balance Show Ajax (Accounts)////////////////////////
function opening_balance_show_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$year = $this->request->query('year');
$le_ac = (int)$this->request->query('le_ac');

$this->set('le_ac',$le_ac);
$this->set('year',$year);

if($le_ac == 15 || $le_ac == 33 || $le_ac == 34 || $le_ac == 35)
{
$ls_ac = (int)$this->request->query('ls_ac');

$this->loadmodel('opening_balance');
$conditions=array("society_id" => $s_society_id, "year" => $year, "account_type" => 1,"account_id" => $ls_ac);
$cursor =$this->opening_balance->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$op_ba = $collection['opening_balance']['opening_balance_amount'];
}

$this->loadmodel('ledger_sub_account');
$conditions=array("auto_id" => $ls_ac, "society_id" => $s_society_id);
$cursor =$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$subl_name = $collection['ledger_sub_account']['name'];
}

$this->set('subl_name',$subl_name);
$this->set('op_ba',$op_ba);
}
else
{
$this->loadmodel('opening_balance');
$conditions=array("society_id" => $s_society_id, "year" => $year, "account_type" => 2,"account_id" => $le_ac);
$cursor =$this->opening_balance->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$op_ba = $collection['opening_balance']['opening_balance_amount'];
}
$this->loadmodel('ledger_account');
$conditions=array("auto_id" => $le_ac);
$cursor =$this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$ledger_ac_name = $collection['ledger_account']['ledger_name'];
}
$this->set('le_ac_name',$ledger_ac_name);
$this->set('op_ba',$op_ba);

}
}
//////////////////////////// End Opening Balance Show Ajax (Accounts)//////////////

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

/////////////////// Start master Financial Year (Accounts)/////////////////////////////

function master_financial_year()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		
$nnn = 5;
$this->set('nnn',$nnn);

if(isset($this->request->data['sub1']))
{
$f11 = $this->request->data['from'];
$t11 = $this->request->data['to'];
$nnn = 55;
$f1 = date('Y-m-d',strtotime($f11));
$t1 = date('Y-m-d',strtotime($t11));
$this->set('nnn',$nnn);
$this->set('f1',$f1);
$this->set('t1',$t1);

}
if(isset($this->request->data['sub2']))
{
 $from = $this->request->data['fm'];	
 $to = $this->request->data['tm'];	

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
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
New Financial Year From <?php echo $from1; ?> To <?php echo $to1; ?> is created
</div> 
<div class="modal-footer">
<a href="master_financial_year"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->	
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



/////////////////////////////////// Start Master Period Status (Accounts)///////////////

function master_financial_period_status()
{
$this->layout = 'session';
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
}
$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$order=array('financial_year.auto_id'=> 'ASC');
$cursor1 = $this->financial_year->find('all',array('conditions'=>$conditions,'order' =>$order));
$this->set('cursor1',$cursor1);
}
///////////////////////////// End Master Period Status (Accounts)//////////////////////////


//////////////////////////////////////////////// Start Master Flat Rent (Accounts) //////////////////////////////////////////////////////////////////////
function master_flat_rent()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	



if(isset($this->request->data['sub']))
{
$user_id = (int)$this->request->data['user_id'];
$type = $this->request->data['flat_type'];

if($type == 1)
{
$size = $this->request->data['flat_size_s'];

$this->loadmodel('user');
$this->user->updateAll(array("flat_type" => $type, "flat_size" => $size),array("user_id" => $user_id));	
}

if($type == 2)
{
$size_id = (int)$this->request->data['flat_size'];

$this->loadmodel('user');
$this->user->updateAll(array("flat_type" => $type, "flat_size" => $size_id),array("user_id" => $user_id));	
}
?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Record Updated
</div> 
<div class="modal-footer">
<a href="master_flat_rent"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->

<?php

}

$this->loadmodel('user');
$conditions=array("society_id"=>$s_society_id);
$cursor1=$this->user->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

$this->loadmodel('flat_rent');
$conditions=array("flat_type" => 2);
$cursor2=$this->flat_rent->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	






}

//////////////////////////////////////////////// End Master Flat Rent (Accounts) //////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////// Start Flat Assign Ajax (Accounts) ////////////////////////////////////////////////////////////////////
function flat_assign_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$user_id = (int)$this->request->query('user_id');
$this->set('user_id',$user_id);











}
////////////////////////////////////////////////// End Flat Assign Ajax (Accounts) ////////////////////////////////////////////////////////////////////


//////////////////////////////////////////// Start Trial Balance (Accounts) /////////////////////////////////////////////////////////////////////////////
function trial_balance()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
}




//////////////////////////////////////////// End Trial Balance (Accounts) /////////////////////////////////////////////////////////////////////////////

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

////////////////////////////////////// Start Accounts Group Fetch (Accounts)////////////////////////////////////////////////////////////////////////////
function accounts_group_fetch($auto_id) 
{
$this->loadmodel('accounts_group');
$conditions=array("accounts_id" => $auto_id);
return $this->accounts_group->find('all',array('conditions'=>$conditions));
}

////////////////////////////////////// End Accounts Group Fetch (Accounts)//////////////////////////////////////////////////////////////////////////////
/////////////////////// Start Ledger Account Fetch (Accounts)////////////////////////////////////////////////////////////////////////
function ledger_account_fetch($auto_id) 
{
$this->loadmodel('ledger_account');
$conditions=array("group_id" => $auto_id);
return $this->ledger_account->find('all',array('conditions'=>$conditions));
}




//////////////////////////////////////// End Ledger Account Fetch (Accounts)////////////////////////////////////////////////////////////////////////

//////////////////////// Start Account Statement (Accounts)//////////////////////////////
function account_statement()
{
$this->layout='session';
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

//////////////////////////////////////////////Start Ledger Fetch1 (Accounts)/ ///////////////////////////////////////////////////////////////////////////
function ledger_fetch1($sub_id)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		


$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id, "account_type" => 1, "account_id" => $sub_id);
return $this->ledger->find('all',array('conditions'=>$conditions));

}
//////////////////////////////////////////////End Ledger Fetch1 (Accounts)///////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Ledger Fetch2 (Accounts)//////////////////////////////////////////////////////////////////////////////

function ledger_fetch2($sub_id)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		


$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id, "account_type" => 2, "account_id" => $sub_id);
return $this->ledger->find('all',array('conditions'=>$conditions));

}


//////////////////////////////////////////// End Ledger Fetch2 (Accounts)//////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////// Start Ledger Sub Account Fetch (Accounts)/////////////////////////////////////////////////////////////////////
function ledger_sub_account_fetch2($auto_id) 
{

$this->loadmodel('ledger_sub_account');
$conditions=array("ledger_id" => $auto_id);
return $this->ledger_sub_account->find('all',array('conditions'=>$conditions));

}

////////////////////////////////////////// End Ledger Sub Account Fetch (Accounts)///////////////////////////////////////////////////////////////////////

////////////////////////Start Master Ledger Accounts COA(Accounts)///////////////////
function master_ledger_account_coa()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$this->set('s_user_id',$s_user_id);
$del_id = (int)$this->request->query('con');
$this->set('del_id',$del_id);

if(isset($this->request->data['delc']))
{
$del = (int)$this->request->data['del_id'];

$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("delete_id" => 1),array("auto_id" => $del));	
?>
<script>
window.location.href="master_ledger_account_coa";
</script>
<?php
}

$this->loadmodel('ledger_account');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id']; 
if(isset($this->request->data['sub'.$auto_id]))
{
$cata22 = $this->request->data['cat'.$auto_id];

$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("ledger_name" => $cata22),array("auto_id" => $auto_id));	
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
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	
}

///////////////////////////End Master Ledger Accounts COA (Accounts)//////////////////

/////////////////////////////////////////////// Start Master Ledger Accounts Ajax COA (Accounts)/////////////////////////////////////////////////////////
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

////////////////////////////// Start Master Ledger Account Hm (Accounts)///////////////
function master_ledger_account_hm()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id= (int)$this->Session->read('user_id');	
$this->set('s_user_id',$s_user_id);

$del_id = (int)$this->request->query('con');
$this->set('del_id',$del_id);

if(isset($this->request->data['delc']))
{
$del = (int)$this->request->data['del_id'];

$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("delete_id" => 1),array("auto_id" => $del));	
?>
<script>
window.location.href="master_ledger_account_hm";
</script>
<?php
}
$this->loadmodel('ledger_account');
$cursor=$this->ledger_account->find('all');
foreach ($cursor as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
if(isset($this->request->data['sub'.$auto_id]))
{
$ledger_name = $this->request->data['cat'.$auto_id];
$gr_id = $this->request->data['gr_id'];



$this->loadmodel('ledger_account');
$this->ledger_account->updateAll(array("ledger_name" => $ledger_name,"group_id"=>$gr_id),array("auto_id" => $auto_id));	

}
}

if(isset($this->request->data['sub']))
{
$main_id = (int)$this->request->data['main_id'];
$name = $this->request->data['cat_name'];

$this->loadmodel('society');
$cursor=$this->society->find('all');
foreach ($cursor as $collection) 
{
$society_id2 = (int)$collection['society']['society_id'];


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
$multipleRowData = Array( Array("auto_id" => $i, "group_id" => $main_id, "ledger_name" => $name,"delete_id"=>0, "edit_user_id"=>$s_user_id,"society_id"=>$society_id2));
$this->ledger_account->saveAll($multipleRowData);	
}
}

$this->loadmodel('accounts_group');
$cursor1=$this->accounts_group->find('all');
$this->set('cursor1',$cursor1);	

$this->loadmodel('ledger_account');
$conditions=array("delete_id" => 0);
$cursor2=$this->ledger_account->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);	


$this->loadmodel('accounts_group');
$conditions=array("delete_id" => 0);
$cursor3=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor3',$cursor3);

}
//////////////////////// End Master Ledger Account Hm (Accounts)/////////////////////

////////////////////////////////////////// Start Accounts Category Fetch (Accounts) /////////////////////////////////////////////////////////////////////
function accounts_category($accounts_id) 
{

$this->loadmodel('accounts_category');
$conditions=array("auto_id" => $accounts_id);
return $this->accounts_category->find('all',array('conditions'=>$conditions));

}
////////////////////////////////////////// End Accounts Category Fetch (Accounts) ///////////////////////////////////////////////////////////////////////

//////////////////// Start Master Ledger Sub Accounts COA (Accounts) //////////////////

function master_ledger_sub_accounts_coa()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('ledger_account');
$cursor1=$this->ledger_account->find('all');
$this->set('cursor1',$cursor1);	

$del_id = (int)$this->request->query('con');
$this->set('del_id',$del_id);

if(isset($this->request->data['delc']))
{
$del = (int)$this->request->data['del_id'];

$this->loadmodel('ledger_sub_account');
$this->ledger_sub_account->updateAll(array("delete_id" => 1),array("auto_id" => $del));	
?>
<script>
window.location.href="master_ledger_sub_accounts_coa";
</script>
<?php
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

////////////////////////////////////////// Start Ledger Account Fetch (Accounts)/////////////////////////////////////////////////////////////////////////
function ledger_account($ledger_id) 
{

$this->loadmodel('ledger_account');
$conditions=array("auto_id" => $ledger_id);
return $this->ledger_account->find('all',array('conditions'=>$conditions));

}
////////////////////////////////////////// End Ledger Account Fetch (Accounts)///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////Start Ledger Account Fetch2 (Accounts)////////////////////////////////////////////////////////////////////////
function ledger_account2($group_id) 
{

$this->loadmodel('ledger_account');
$conditions=array("group_id" => $group_id);
return $this->ledger_account->find('all',array('conditions'=>$conditions));

}

///////////////////////////////////////////End Ledger Account Fetch2 (Accounts)////////////////////////////////////////////////////////////////////////

//////////////////////Start Master Accounts Category Hm (Accounts)///////////////////////
function master_accounts_category_hm()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$del_id = (int)$this->request->query('con');
$this->set('del_id',$del_id);

if(isset($this->request->data['delc']))
{
$del = (int)$this->request->data['del_id'];
$this->loadmodel('accounts_category');
$this->accounts_category->updateAll(array("delete_id" => 1),array("auto_id" => $del));	
?>
<script>
window.location.href="master_accounts_category_hm";
</script>
<?php
}
$this->loadmodel('accounts_category');
$order=array('accounts_category.auto_id'=> 'ASC');
$cursor=$this->accounts_category->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$auto_id = (int)$collection['accounts_category']['auto_id'];
if(isset($this->request->data['sub'.$auto_id]))
{
$cata22 = $this->request->data['cat'.$auto_id];
$this->loadmodel('accounts_category');
$this->accounts_category->updateAll(array("category_name" => $cata22),array("auto_id" => $auto_id));	
}
}
if(isset($this->request->data['sub']))
{
$name = $this->request->data['cat_name'];
$this->loadmodel('accounts_category');
$order=array('accounts_category.auto_id'=> 'ASC');
$cursor=$this->accounts_category->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$last=$collection['accounts_category']["auto_id"];
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
$this->loadmodel('accounts_category');
$multipleRowData = Array( Array("auto_id" => $i, "category_name" => $name));
$this->accounts_category->saveAll($multipleRowData);	
}

$this->loadmodel('accounts_category');
$conditions=array("delete_id" => 0);
$cursor1=$this->accounts_category->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
}

//////////////////// End Master Accounts Category Hm (Accounts)//////////////////////

/////////////// Start Master Accounts Group Hm (Accounts) ////////////////////////////

function master_accounts_group_hm()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$del_id = (int)@$this->request->query['con'];
$this->set('del_id',$del_id);

if(isset($this->request->data['delc']))
{
$del = (int)$this->request->data['del_id'];

$this->loadmodel('accounts_group');
$this->accounts_group->updateAll(array("delete_id" => 1),array("auto_id" => $del));
?>
<script>
window.location.href="master_accounts_group_hm";
</script>
<?php
}
$this->loadmodel('accounts_group');
$order=array('accounts_group.auto_id'=> 'ASC');
$cursor=$this->accounts_group->find('all',array('order' =>$order));
foreach ($cursor as $collection) 
{
$auto_id = (int)$collection['accounts_group']['auto_id'];
if(isset($this->request->data['sub'.$auto_id]))
{
$group_name = $this->request->data['cat'.$auto_id];
$this->loadmodel('accounts_group');
$this->accounts_group->updateAll(array("group_name" => $group_name),array("auto_id" => $auto_id));	
}
}

if(isset($this->request->data['sub']))
{
$main_id = $this->request->data['main_id'];
$name = $this->request->data['cat_name'];

$this->loadmodel('accounts_group');
$order=array('accounts_group.auto_id'=> 'ASC');
$cursor=$this->accounts_group->find('all',array('order' =>$order,'limit'=>1));
foreach ($cursor as $collection) 
{
$last=$collection['accounts_group']["auto_id"];
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
$this->loadmodel('accounts_group');
$multipleRowData = Array( Array("auto_id" => $i, "accounts_id" => $main_id, "group_name" => $name));
$this->accounts_group->saveAll($multipleRowData);	
}

$this->loadmodel('accounts_group');
$conditions=array("delete_id" => 0);
$cursor1=$this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('accounts_category');
$conditions=array("delete_id" => 0);
$cursor2=$this->accounts_category->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);
}

//////////////// End Master Accounts Group Hm (Accounts) //////////////////////////////


/////////////////////////////////////////// Start Master Flat Type (Accounts) ///////////////////////////////////////////////////////////////////////////
function master_flat_type()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


if(isset($this->request->data['sub']))
{
$flat_type = (int)$this->request->data['flat_type'];

if($flat_type == 1)
{
$square_rs = $this->request->data['rs_feet'];	

$this->loadmodel('flat_rent');
$this->flat_rent->updateAll(array("rs" => $square_rs),array("flat_type" => 1));	
}

if($flat_type == 2)
{
$flat_cat = (int)$this->request->data['flat_cat'];
$rs = $this->request->data['rs'];
if($flat_cat == 1)
{
$name = "1 BHK";	
}
else if($flat_cat == 2)
{
$name ="2 BHK";	
}
else if($flat_cat == 3)
{
$name ="3 BHK";	
}
else if($flat_cat == 4)
{
$name ="4 BHK";	
}
else if($flat_cat == 5)
{
$name ="5 BHK";	
}
else if($flat_cat == 6)
{
$name ="6 BHK";	
}
$this->loadmodel('flat_rent');
$this->flat_rent->updateAll(array("rs" => $rs),array("name" => $name));	

}
}
}
/////////////////////////////////////////// End Master Flat Type (Accounts) ///////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////Start Master Flat Assign ///////////////////////////////////////////////////////////////////////////////////
function master_flat_assign()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

if(isset($this->request->data['sub']))
{
$this->loadmodel('flat');
$cursor=$this->flat->find('all');
foreach($cursor as $collection)
{	
$flat_id = (int)$collection['flat']['flat_id'];	

$type = (int)$this->request->data['flat_type'.$flat_id];

$this->loadmodel('flat');
$this->flat->updateAll(array("flat_type_id" => $type, "sqr_feet" => null),array("flat_id" => $flat_id));

}
}

$this->loadmodel('flat');
$cursor1=$this->flat->find('all');
$this->set('cursor1',$cursor1);	
}
/////////////////////////////////////////////End Master Flat Assign /////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////Start Master Flat Assign Second ///////////////////////////////////////////////////////////////////////////
function master_flat_assign2()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

if(isset($this->request->data['sub']))
{
$type = (int)$this->request->data['type'];
if($type == 1)
{
$this->loadmodel('flat');
$conditions=array("flat_type_id" => 0);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_id = (int)$collection['flat']['flat_id'];	

$sq_feet = (int)$this->request->data['sq_feet'.$flat_id];

$this->loadmodel('flat');
$this->flat->updateAll(array("sqr_feet" => $sq_feet),array("flat_id" => $flat_id));
}
}
if($type == 2)
{
$this->loadmodel('flat');
$conditions=array("flat_type_id" => 2);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_id = (int)$collection['flat']['flat_id']; 

$bhk_id = (int)$this->request->data['bhk'.$flat_id];

$this->loadmodel('flat');
$this->flat->updateAll(array("flat_type_id" => $bhk_id),array("flat_id" => $flat_id));




}
}
}







}
//////////////////////////////////////////////End Master Flat Assign Second /////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Master Flat Assign2 Ajax /////////////////////////////////////////////////////////////////////////////
function master_flat_assign2_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$value = (int)$this->request->query('value1');
$this->set('value',$value);

$this->loadmodel('flat');
$conditions=array("flat_type_id" => 0);
$cursor1=$this->flat->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('flat');
$conditions=array("flat_type_id" => 2);
$cursor2=$this->flat->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);


$this->loadmodel('flat_rent');
$cursor3=$this->flat_rent->find('all');
$this->set('cursor3',$cursor3);

}
/////////////////////////////////// End Master Flat Assign2 Ajax ////////////////////////////////

/////////////////////////// Start Bank Receipt Pdf (Accounts)//////////////////////////////////////
function bank_receipt_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


$trns_id = (int)$this->request->query('c');
$this->set('trns_id',$trns_id);

$this->loadmodel('bank_receipt');
$conditions=array("transaction_id" => $trns_id);
$cursor1=$this->bank_receipt->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);



$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);



}
////////////////////////////////////////// End Bank Receipt Pdf (Accounts)////////////////////////////////////////////////////////////////////////////



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

//////////////////////////////////////// Start bank payment Pdf (Accounts)//////////////////////////////////////////////////////////////////////////////
function bank_payment_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);

$this->loadmodel('bank_payment');
$conditions=array("transaction_id" => $tns_id);
$cursor1=$this->bank_payment->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
//////////////////////////////////////// End bank payment Pdf (Accounts)////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////// Start Petty cash Receipt Pdf (Accounts)///////////////////////////////////////////////////////////////////////
function petty_cash_receipt_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);

$this->loadmodel('petty_cash_receipt');
$conditions=array("transaction_id" => $tns_id);
$cursor1=$this->petty_cash_receipt->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);








}
///////////////////////////////////////// End Petty cash Receipt Pdf (Accounts)///////////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Petty Cash Payment Pdf (Accounts)////////////////////////////////////////////////////////////////////
function petty_cash_payment_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$tns_id = (int)$this->request->query('c');
$this->set('tns_id',$tns_id);

$this->loadmodel('petty_cash_payment');
$conditions=array("transaction_id" => $tns_id);
$cursor1=$this->petty_cash_payment->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);

}
//////////////////////////////////////////// End Petty Cash Payment Pdf (Accounts)//////////////////////////////////////////////////////////////////////

/////////////////////////////////////////// Start Expense History Pdf (Accounts)////////////////////////////////////////////////////////////////////////
function expense_history_pdf()
{
$this->layout = 'pdf'; //this will use the pdf.ctp layout 
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$auto_id = (int)$this->request->query('a');
$this->set('auto_id',$auto_id);

$this->loadmodel('expense_tracker');
$conditions=array("auto_id" => $auto_id);
$cursor1=$this->expense_tracker->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor2=$this->society->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);



}
/////////////////////////////////////////// End Expense History Pdf (Accounts)//////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////// Start Regular Bill Pdf(Accounts)/////////////////////////////////////////////////////////////////
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

}
//////////////////////////////////////////// End Regular Bill Pdf(Accounts)/////////////////////////////////////////////////////////////////////////////

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



}
//////////////////////////////////////////// End Supplimentry Bill Pdf (Accounts)///////////////////////////////////////////////////////////////////////

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

/////////////////////////////////////////// Start Function Convert Rupee ///////////////////////////////////////////////////////////////////////////////

function convert_number_to_words($number) 
{

$hyphen      = '-';
$conjunction = ' and ';
$separator   = ', ';
$negative    = 'negative ';
$decimal     = ' point ';
$dictionary  = array(


01                   => 'One',
02                   => 'Two',
03                   => 'Three',
04                   => 'Four',
05                   => 'Five',
06                   => 'Six',
07                   => 'Seven',
08                   => 'Eight',
09                   => 'Nine',



0                   => 'Zero',
1                   => 'One',
2                   => 'Two',
3                   => 'Three',
4                   => 'Four',
5                   => 'Five',
6                   => 'Six',
7                   => 'Seven',
8                   => 'Eight',
9                   => 'Nine',
10                  => 'Ten',
11                  => 'Eleven',
12                  => 'Twelve',
13                  => 'Thirteen',
14                  => 'Fourteen',
15                  => 'Fifteen',
16                  => 'Sixteen',
17                  => 'Seventeen',
18                  => 'Eighteen',
19                  => 'Nineteen',
20                  => 'Twenty',
30                  => 'Thirty',
40                  => 'Fourty',
50                  => 'Fifty',
60                  => 'Sixty',
70                  => 'Seventy',
80                  => 'Eighty',
90                  => 'Ninety',
100                 => 'Hundred',
1000                => 'Thousand',
1000000             => 'Million',
1000000000          => 'Billion',
1000000000000       => 'Trillion',
1000000000000000    => 'Quadrillion',
1000000000000000000 => 'Quintillion'
);

if (!is_numeric($number)) {
return false;
}

if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
trigger_error(
'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
E_USER_WARNING
);
return false;
}

if ($number < 0) {
return $negative . convert_number_to_words(abs($number));
}

$string = $fraction = null;

if (strpos($number, '.') !== false) {
list($number, $fraction) = explode('.', $number);
}

switch (true) {
case $number < 21:
$string = $dictionary[$number];
break;
case $number < 100:
$tens   = ((int) ($number / 10)) * 10;
$units  = $number % 10;
$string = $dictionary[$tens];
if ($units) {
$string .= $hyphen . $dictionary[$units];
}
break;
case $number < 1000:
$hundreds  = $number / 100;
$remainder = $number % 100;
$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
if ($remainder) {
$string .= $conjunction . convert_number_to_words($remainder);
}
break;
default:
$baseUnit = pow(1000, floor(log($number, 1000)));
$numBaseUnits = (int) ($number / $baseUnit);
$remainder = $number % $baseUnit;
$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
if ($remainder) {
$string .= $remainder < 100 ? $conjunction : $separator;
$string .= convert_number_to_words($remainder);
}
break;
}

if (null !== $fraction && is_numeric($fraction)) {
$string .= $decimal;
$words = array();
foreach (str_split((string) $fraction) as $number) {
$words[] = $dictionary[$number];
}
$string .= implode(' ', $words);
}

return $string;
}

function serial_no($number)
{

$str_lenth=strlen($number);
if($str_lenth==1)
{
$number='000'.$number;
}
else if($str_lenth==2)
{
$number='00'.$number;
}

else if($str_lenth==3)
{
$number='0'.$number;
}
echo $number;


}







/////////////////////////////////////////// End Function Convert Rupee ///////////////////////////////////////////////////////////////////////////////





/////////////////////// Start Flat Fetch(Accounts)//////////////////////////////////////////

function flat_fetch($flat_id) 
{
$this->loadmodel('flat');
$conditions=array("flat_id" => $flat_id);
return $this->flat->find('all',array('conditions'=>$conditions));
}

/////////////////////// End Flat Fetch(Accounts)//////////////////////////////////////////////



//////////////////// Start Flat Rent Fetch(Accounts)//////////////////////////////////////////

function flat_rent_fetch($auto_id) 
{
$this->loadmodel('flat_rent');
$conditions=array("auto_id" => $auto_id);
return $this->flat_rent->find('all',array('conditions'=>$conditions));
}

///////////////////////End Flat Rent Fetch (Accounts)//////////////////////////////////////////


/////////////// Start Terms Conditions Fetch (Accounts)///////////////////////////////////////

function terms_fetch($auto_id) 
{
$this->loadmodel('terms_condition');
$conditions=array("terms_conditions_id" => $auto_id);
return $this->terms_condition->find('all',array('conditions'=>$conditions));
}
/////////////// End Terms Conditions Fetch (Accounts)//////////////////////////////////////////

//////////////////// Start Opening Balance Import (Accounts)////////////////////////////
function opening_balance_import()
{
$this->layout='session';
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
$ok=2; 

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
}
}
if($abc == 5)
{
$ok=2;
}
else
{
$ok=1; $error_msg[]="Date is not in Open Year ".$row_no.".";	break;
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
$ok = 1; $error_msg[]="Please Fill 'Debit' or 'Credit' ".$row_no.".";	break;
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
{ $ok=1; $error_msg[]="account name should not be empty in row ".$row_no.".";	break;}

}
if($total_debit == $total_credit)
{
$ok = 2; 
}
else
{
$ok = 1; $error_msg[]="Total Credit is not equal to Total debit";
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

////////////////// Start Regular Bill Fetch(Accounts)/////////////////////////////////////////
function regular_bill_fetch($user_id) 
{
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $user_id, "status" => 0);
return $this->regular_bill->find('all',array('conditions'=>$conditions));
}

////////////////// End Regular Bill Fetch(Accounts)/////////////////////////////////////////

///////////////////// Start Expense Tracker Pie Chart (Accounts)///////////////////////////////
function expense_tracker_pie_chart()
{
$this->layout = 'session';
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

///////////////////// Start Ledger Account Fetch(Accounts)/////////////////////////////////////
function ledger_account_fetch2($auto_id) 
{
$this->loadmodel('ledger_account');
$conditions=array("auto_id" => $auto_id);
return $this->ledger_account->find('all',array('conditions'=>$conditions));
}

/////////////////////End Ledger Account Fetch(Accounts)/////////////////////////////////////

/////////////////////////// Start Nikhil Test (Accounts)///////////////////////////////////////
function nikhil_test()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');		


}
/////////////////////////// Start Nikhil Test (Accounts)///////////////////////////////////////

////////////////////////// Start Profit And Loss Report////////////////////////////////////////
function profit_loss_report()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


}
////////////////////////// End Profit And Loss Report//////////////////////////////////////////

//////////////////////////Start Profit Loss Report Show Ajax(Accounts)/////////////////////////
function profit_loss_report_show_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');
$this->set('date1',$date1);
$this->set('date2',$date2);

$this->loadmodel('accounts_category');
$cursor1=$this->accounts_category->find('all');
$this->set('cursor1',$cursor1);


}
//////////////////////////End Profit Loss Report Show Ajax(Accounts)//////////////////////////

/////////////////////// Start ledger  Fetch1 (Accounts)///////////////////////////////////////
function ledger_fetch($auto_id)
{
$this->loadmodel('ledger');
$conditions=array("auto_id" => $auto_id, "account_type" => 1);
return $this->ledger->find('all',array('conditions'=>$conditions));
}

/////////////////////// End ledger  Fetch1 (Accounts)///////////////////////////////////////

/////////////////////// Start ledger  Fetch2 (Accounts)///////////////////////////////////////
function ledger_fetch3($auto_id)
{
$this->loadmodel('ledger');
$conditions=array("auto_id" => $auto_id, "account_type" => 2);
return $this->ledger->find('all',array('conditions'=>$conditions));
}

/////////////////////// End ledger  Fetch2 (Accounts)///////////////////////////////////////

/////////////////////////////// Start My Flat (Accounts)//////////////////////////////////////
function my_flat()
{
$this->layout = 'session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

}
/////////////////////////////// End My Flat (Accounts)//////////////////////////////////////

///////////////////////////// Start My Flat Ajax(Accounts)///////////////////////////

function my_flat_ajax()
{
$this->layout = 'blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$this->loadmodel('user');
$conditions=array("society_id" => $s_society_id, "user_id"=>$s_user_id);
$cursor = $this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_id = (int)$collection['user']['flat'];
$wing_id = (int)$collection['user']['wing'];
}

$this->loadmodel('wing');
$conditions=array("society_id" => $s_society_id, "wing_id"=>$wing_id);
$cursor = $this->wing->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$wing_name = $collection['wing']['wing_name'];
}
$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id, "flat_id"=>$flat_id);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_name = $collection['flat']['flat_name'];
$flat_mas_id = (int)$collection['flat']['flat_master_id'];
$flat_tp_id = (int)$collection['flat']['flat_type_id'];
}

$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id, "auto_id"=>$flat_mas_id);
$cursor = $this->flat_master->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_area = $collection['flat_master']['flat_area'];
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id, "auto_id"=>$flat_tp_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_type = $collection['flat_type']['flat_name'];
}
$this->set('wing_name',$wing_name);
$this->set('flat_name',$flat_name);
$this->set('flat_area',$flat_area);
$this->set('flat_type',$flat_type);




$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);













$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "user_id"=>$s_user_id);
$cursor = $this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$account_id = (int)$collection['ledger_sub_account']['auto_id'];
$name = $collection['ledger_sub_account']['name'];
$ledger_id = (int)$collection['ledger_sub_account']['ledger_id'];
}
$this->set('account_id',@$account_id);
$this->set('name',@$name);
$this->set('ledger_id',@$ledger_id);










$date1 = $this->request->query('date1');
$date2 = $this->request->query('date2');

$this->set('from',$date1);
$this->set('to',$date2);


$this->loadmodel('financial_year');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->financial_year->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$from = $collection['financial_year']['from'];
$to = $collection['financial_year']['to'];
}
$cm = date('m');
$fm = date('m',strtotime($to));

if($cm <= $fm)
{
$year = date('Y');
$year = $year-1;
}
else
{
$year = date('Y');
}
$from1 = $from.'-'.$year; 

$year = $year-1;
$from2 = $from.''.$year;


$nv = 1;
$op_deb = 0;
$op_cred = 0;
while($nv < 3)
{
if($nv == 1)
{
$datefrom = date('Y-m-d',strtotime($from1));
$datefrom = new MongoDate(strtotime($datefrom));
}
else
{
$datefrom = date('Y-m-d',strtotime($from2));
$datefrom = new MongoDate(strtotime($datefrom));
}
$this->loadmodel('ledger'); 
$conditions=array("op_date"=>$datefrom,"account_type"=> 1,"account_id"=>
@$account_id,"receipt_id"=>"O_B","society_id"=>$s_society_id);
$cursor=$this->ledger->find('all',array('conditions'=>$conditions));

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
$nv ++;
}
$this->set('op_deb',$op_deb);
$this->set('op_cred',$op_cred);




$this->loadmodel('ledger');
$conditions=array("society_id" => $s_society_id, "account_id"=>@$account_id,"account_type"=>1);
$cursor1 = $this->ledger->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

}

///////////////////////////// End My Flat Ajax(Accounts)////////////////////////////

//////////////////////// Start My Flat Bill (Accounts) //////////////////////////////

function my_flat_bill()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}
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

/////////////////////////Start Master rate Card(Accounts)//////////////////////////////

function master_rate_card()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$nnn = 5;
$this->set('nnn',$nnn);

if(isset($this->request->data['sub']))
{

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor1 as $collection)
{
$auto_id1 = (int)$collection['flat_type']['auto_id'];
$rate_arr = array();
$rate_arri = array();
$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor2 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
foreach($cursor2 as $collection)
{

$auto_id2 = (int)$collection['income_head']['auto_id'];


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

}

//////////////////////// End Master rate Card(Accounts)//////////////////////////////

////////////////////////// start master sm flat add row /////////////////////////////////////
function master_sm_flat_add_row()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$t = $this->request->query('con');
$this->set('t',$t);

$this->loadmodel('wing');
$condition=array('society_id'=>$s_society_id);
$result=$this->wing->find('all',array('conditions'=>$condition)); 
$this->set('user_wing',$result);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$result2);

$this->loadmodel('noc_type');
$cursor3 = $this->noc_type->find('all'); 
$this->set('cursor3',$cursor3);


}
////////////////////////// End master sm flat add row /////////////////////////////////////

////////////////////////// Start Regular Bill View2 ////////////////////////////////////

function regular_bill_view2()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


$this->loadmodel('penalty');
$condition=array('society_id'=>$s_society_id);
$result5=$this->penalty->find('all',array('conditions'=>$condition)); 
$this->set('cursor5',$result5);

$from3 = $this->request->query('f');
$to3 = $this->request->query('t');
$due_date3 = $this->request->query('due');
//$ih3 = $this->request->query('ih');
//$tax3 = $this->request->query('tax');
$desc3 = $this->request->query('d');
//$tem3 = $this->request->query('tem');
$p_id = $this->request->query('p');
$pen = $this->request->query('pen');



$from = $this->decode($from3,'housingmatters');
$to = $this->decode($to3,'housingmatters');
$due_date = $this->decode($due_date3,'housingmatters');
//$ih = $this->decode($ih3,'housingmatters');
//$tax = (int)$this->decode($tax3,'housingmatters');
$desc = $this->decode($desc3,'housingmatters');
//$tem = $this->decode($tem3,'housingmatters');
$p_id = (int)$this->decode($p_id,'housingmatters');
$penalty = (int)$this->decode($pen,'housingmatters');



$this->set('p_id',$p_id);
$this->set('from',$from);
$this->set('to',$to);
$this->set('due_date',$due_date);
//$this->set('ih',$ih);
//$this->set('tax',$tax);
$this->set('desc',$desc);
//$this->set('tem',$tem);
$this->set('penalty',$penalty);

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor11 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor11',$cursor11);


$this->loadmodel('penalty');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->penalty->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$pen_per = (int)$collection['penalty']['tax'];
}
$this->set('pen_per',$pen_per);


$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);

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
 $from = $this->request->data['from'];
 $to = $this->request->data['to'];
 $due_date = $this->request->data['due'];
//$ih = $this->request->data['ih'];
//$tax = (int)$this->request->data['tax'];
 $description = $this->request->data['desc'];
//$terms = $this->request->data['tem'];
$gtamt = $this->request->data['gt'];
$penalty = (int)$this->request->data['penalty'];

$sms_from = date('dM',strtotime($from));
$sms_to = date('dMy',strtotime($to));

$sms_due = date('dMy',strtotime($due_date));

$dueeed = $due_date;
$due_date_msg = $due_date;

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$due_date = date("Y-m-d", strtotime($due_date));
$due_date = new MongoDate(strtotime($due_date));



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

//$bill_period = $this->requestAction(array('controller' => 'hms', 'action' => 'bill_period_fetch'),array('pass'=>array($p_id)));
//foreach($bill_period as $collection)
//{
//$over_due_tax = $collection['bill_period']['tax']; 	
//$period_name = $collection['bill_period']['period_name'];
//}

$this->loadmodel('user');
$order=array('user.user_id'=> 'ASC');
$conditions=array("society_id" => $s_society_id,"tenant" => 1,"deactive"=>0);
$cursor = $this->user->find('all',array('conditions'=>$conditions,'order'=>$order));
foreach($cursor as $collection)
{
$user_id = (int)$collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
$flat_id = (int)$collection['user']['flat'];
$wing_id = (int)$collection['user']['wing'];
$mobile = $collection['user']['mobile'];
$to_mail = $collection['user']['email'];
//$residing = (int)$collection['user']['residing'];
//$mobile = '9799463210';
$wing_flat = $this->wing_flat($wing_id,$flat_id);
//////////////////////////////////////////////////////////////

$maint_ch = 0;
$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$sms_id = (int)$collection['society']['account_sms'];
}

if($sms_id == 1)
{
$sms='Dear '.$user_name.' '.$wing_flat.', your maintenance bill for period '.$sms_from.'-'.$sms_to.' is Rs '.$gtamt.'.Kindly pay by due '.$sms_due.'.'.$society_name.' Society';

$sms1=str_replace(' ', '+', $sms);
$payload = file_get_contents('http://alerts.sinfini.com/api/web2sms.php?workingkey=149981t853o14262m1119&sender=HSGMTR&to='.$mobile.'&message='.$sms1.'');
}
/////////////////////////////////////////////////////////////

$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id, "flat_id" => $flat_id, "wing_id" => $wing_id);
$cursor = $this->flat->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$flat_master_id = (int)$collection['flat']['flat_master_id'];
$noc_ch_id = (int)$collection['flat']['noc_ch_type'];
}

$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id, "auto_id" => $flat_master_id);
$cursor = $this->flat_master->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$sq_feet = (int)$collection['flat_master']['flat_area'];
}

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id, "auto_id" => $flat_type_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$charge = $collection['flat_type']['charge'];
$noc_charge = $collection['flat_type']['noc_charge'];
}
$regular_bill_id11 = (int)$this->autoincrement('regular_bill','regular_bill_id');
$current_date11 = date('Y-m-d');
$current_date11 = new MongoDate(strtotime($current_date11));

/////////////////////////////////////
$total_amt = 0;
$income_headd2 = array();
$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
foreach($cursor as $collection)
{
$ih_id11 = (int)$collection['income_head']['ih_id'];
$auto_id_in = (int)$collection['income_head']['auto_id'];
for($j=0; $j<sizeof($charge); $j++)
{
$charge2 = $charge[$j];
$auto_ih = (int)$charge2[0];
$type = (int)$charge2[1];
$ch_amt1 = $charge2[2];
if($auto_id_in == $auto_ih)
{
if($type == 2)
{
$ch_amt = $ch_amt1 * $sq_feet;
}
else
{
$ch_amt = $ch_amt1;
}
if($ih_id11 == 42)
{
$maint_ch = $ch_amt;
}
$income_headd = array($auto_ih,$ch_amt);
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $ch_amt, "amount_category_id" => 2,
"module_id" => 9, "account_type" => 2, "account_id" => $ih_id11, "current_date" => $current_date11,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);
$total_amt = $total_amt + $ch_amt;
}
}
}

///////////////////////////////////////
if($noc_ch_id == 2)
{
$tp_id = (int)$noc_charge[0];
if($tp_id == 2)
{
$noc_amt = $noc_charge[1];
$noc_amt2 = $noc_amt*$sq_feet;
}
else if($tp_id == 4)
{
echo $noc_amt2 = round((10/100)*$maint_ch);
}
else
{
$noc_amt = $noc_charge[1];
$noc_amt2 = $noc_amt;
}
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => $noc_amt2, "amount_category_id" => 2,
"module_id" => 9, "account_type" => 2, "account_id" => 43, "current_date" => $current_date11,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);

$income_headd = array(43,$noc_amt2);
$income_headd2[] = $income_headd;
$total_amt = $total_amt + $noc_amt2;

}


////////////////////////////////////
//$tax_amount = round(($tax_per/100)*$total_amount);
$current_date = new MongoDate(strtotime(date("Y-m-d")));

$this->loadmodel('regular_bill');
$conditions=array("society_id" => $s_society_id,"bill_for_user"=>$user_id,"status"=>0);
$cursor = $this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$due_amount11 = (int)$collection['regular_bill']['remaining_amount'];
$due_date11 = $collection['regular_bill']['due_date'];
}
$cur_date = date('Y-m-d');
$cur_datec = new MongoDate(strtotime($cur_date));

if($penalty == 1)
{
if($cur_datec > @$due_date11)
{
$due_date12 = date('Y-m-d',@$due_date11->sec);
$date1 = date_create($due_date12);
$date2 = date_create($cur_date);
$interval = date_diff($date1, $date2);
$days = $interval->format('%a');

$this->loadmodel('penalty');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->penalty->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$pen_per2 = (int)$collection['penalty']['tax'];
}
$due_tax = round((@$due_amount11 * $days * $pen_per2)/365);

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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id11, "amount" => @$due_tax, "amount_category_id" => 2, 
"module_id" => 9, "account_type"=> 2, "account_id" => 43, "current_date" => $current_date11,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);

}
}


/////////////////////Penalty

$total_due_amount = @$due_tax + @$due_amount11;
$grand_total = $total_amt + $total_due_amount;

if($due_amount11 > 0)
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id12, "amount" =>$due_amount11, "amount_category_id" => 2,"module_id" => 9, "account_type" => 2, "account_id" => 13, "current_date" => $current_date12,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);
}

$current_date13 = date('Y-m-d');
$current_date13 = new MongoDate(strtotime($current_date13));

$regular_bill_id13 = (int)$this->autoincrement('regular_bill','regular_bill_id');

$this->loadmodel('ledger_sub_account');
$conditions=array("society_id" => $s_society_id, "user_id" => $user_id, "ledger_id" => 34);
$cursor=$this->ledger_sub_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$l_id =  (int)$collection['ledger_sub_account']['auto_id'];
}


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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $regular_bill_id13, "amount" => $grand_total, "amount_category_id" => 1, 
"module_id" => 9, "account_type"=> 1, "account_id" => @$l_id, "current_date" => $current_date13,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);


$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array('status'=>1),array("society_id"=>$s_society_id,"bill_for_user"=>$user_id,"status"=>0));


///////////////////////////////////
$admin_user_id = "";
$admin_user_id[] = $user_id;

$regular_bill_id = $this->autoincrement('regular_bill','regular_bill_id');

//////////////////////////////////////////////////////////////
$wing_flat = $this->wing_flat($wing_id,$flat_id);


$this->send_notification('<span class="label label-success" ><i class="icon-user"></i></span>','New Bill for <b>'.$user_name.' '.$wing_flat.'</b> is generated',10,$regular_bill_id,'my_flat_bill',$s_user_id,$admin_user_id);
///////////////////////////








//////////////////////////////////////////////////////////////

$this->loadmodel('regular_bill');
$multipleRowData = Array( Array("regular_bill_id" => $regular_bill_id,"receipt_id" => $regular_bill_id,
"description"=>$description,"date"=>$current_date, "society_id"=>$s_society_id,"bill_for_user"=>$user_id,
"g_total"=>$grand_total,"bill_daterange_from"=>$m_from,"bill_daterange_to"=>$m_to,
"bill_html"=>"","one_time_id"=>$one,"status" => 0,  
"due_date" => $due_date, "total_due_amount"=> $total_due_amount, "due_amount_tax" => @$due_tax,"remaining_amount"=>$grand_total,"total_amount" => $total_amt,"pay_amount"=>"", "due_amount" => @$due_amount11,"period_id"=>$p_id,"ih_detail"=>$income_headd2));
$this->regular_bill->saveAll($multipleRowData);	


///////////////////////////////////////


////////////////////////////////////////////
///////Start Bill Html Code/////////////////
	$total_amount2 = 0;	
	$this->loadmodel('regular_bill');
	$conditions=array("one_time_id"=>$one,"bill_for_user"=>$user_id);
	$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
	foreach($cursor as $collection)
	{
	$bill_no = (int)$collection['regular_bill']['regular_bill_id'];
	$date_from = $collection['regular_bill']['bill_daterange_from'];
	$date_to = $collection['regular_bill']['bill_daterange_to'];
	//$ih_id1 = $collection['regular_bill']["ih_id"];
	$ih_detail2 = $collection['regular_bill']['ih_detail'];
	//$tax_id=(int)$collection['regular_bill']["tax_id"]; 
	$date=$collection['regular_bill']["date"];
	//$terms_conditions_id=$collection['regular_bill']["terms_conditions_id"];
	$regular_bill_id=$collection['regular_bill']["regular_bill_id"];
	//$rent2 = (int)$collection['regular_bill']['rent'];	
	//$tax_amount = (int)$collection['regular_bill']['tax_amount'];
	$grand_total = (int)$collection['regular_bill']['g_total'];
	$late_amt2 = (int)$collection['regular_bill']['due_amount_tax'];
	$due_amt2 = (int)$collection['regular_bill']['total_due_amount'];
	$due_date2 = @$collection['regular_bill']['due_date'];
	}
	

$date_from = date("d-M-Y", $date_from->sec);
$date_to = date("d-M-Y", $date_to->sec);
$date_to2 = date('Y-m-d',strtotime($date_to));

//$due_date = date('Y-m-d', strtotime($date_to2 .'+'. $due_days2.'day'));
$due_date21 = date('d-M-Y',@$due_date2->sec);

$newDate = date("d-M-Y", $date->sec);	


$this->loadmodel('user');
$conditions=array("user_id"=>$user_id,"society_id" => $s_society_id);
$cursor=$this->user->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$user_name=$collection['user']["user_name"];	
$wing = (int)$collection['user']['wing'];
$flat = (int)$collection['user']['flat'];
}
$wing_flat = $this->wing_flat($wing,$flat);

$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name=$collection['society']["society_name"];	
}

 $date = date('d-M-Y',$date->sec);




$html='<center>
<div style="700px; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:80%; border:solid 1px; overflow:auto;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:center;">
<p style="font-size:22px;">'.$society_name.'</p>
</th>
</tr>
<tr>
<br>
<th style="text-align:center;">Society Detail and Address</th>
</tr>
</table>
</div>
<div style="width:80%; border:solid 1px; overflow:auto; border-top:none; border-bottom:none;">
<table border="0" style="width:65%; float:left;">
<tr>
<td style="text-align:left; width:17%;">
Name :
</td>
<td style="text-align:left;">'.$user_name.'</td>
</tr>
<tr>
<td style="text-align:left;">Bill No. :</td>
<td style="text-align:left;">'.$bill_no.'     For  October-November-December-2014 </td>
</tr>
<tr>
<td style="text-align:left;">Bill Date :</td>
<td style="text-align:left;">'.$date.'   Area 0 Sft</td>
</tr>
</table>
<table border=0" style="width:34%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;">Flat/Shop No. :</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">'.$due_date21.'</td>
</tr>
</table>
</div>
<div style="width:80.4%; overflow:auto;">
<table border="1" style="width:100%; margine-left:2px; border-collapse:collapse;" cellspacing="0" cellpadding="0">
<tr>
<td style="width:80%; text-align:center;">Particulars</td>
<td style="text-align:center;">Amount(in Rs.)</td>
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
$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'income_head_fetch'),array('pass'=>array($ih_id5)));
foreach($result7 as $collection)
{
$ih_name = $collection['income_head']['ih_name'];
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

$html.='<tr>
<td style="text-align:center;">'.$amount.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amount;
 }
$due_amt3 = $due_amt2 - $late_amt2;
$html.='</table>
</td>
</tr>
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
<td style="text-align:center;">'.$total_amount2.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$due_amt3.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$late_amt2.'</td>
</tr>
<tr>
<th style="text-align:center;">'.$grand_total.'</th>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div style="width:80%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Terms And Conditions:
</th>
</tr>';
$this->loadmodel('terms_condition');
$conditions=array("status"=>1,"society_id" => $s_society_id);
$cursor=$this->terms_condition->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{	
$tems_name = $collection['terms_condition']['terms_conditions'];

$html.='
<tr>
<td style="text-align:left;">'.$tems_name.'</td>
</tr>';
}
$html.='</table> 
</div>
<br><br><br><br>
</div>
';



$this->loadmodel('regular_bill');
$this->regular_bill->updateAll(array("bill_html" =>$html),array("regular_bill_id" =>$regular_bill_id));	
////////End Bill Html Code///////////////////
////////////////////////////////////////////

///////////////Bill Html for mail////////////

$html_mail='<center>
<div style="width:700px; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:96%; border:solid 1px; overflow:auto; border-bottom:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:center;">
<p style="font-size:22px;">'.$society_name.'</p>
</th>
</tr>
<tr>
<br>
<th style="text-align:center;">Society Detail and Address</th>
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
<td style="text-align:left;">'.$bill_no.'     For  October-November-December-2014 </td>
</tr>
<tr>
<td style="text-align:left;">Bill Date :</td>
<td style="text-align:left;">'.$date.'   Area 0 Sft</td>
</tr>
</table>
<table border=0" style="width:30%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;">Flat/Shop No. :</td>
<td style="text-align:left;">'.$wing_flat.'</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">'.$due_date21.'</td>
</tr>
</table>
</div>
<div style="width:96.4%; overflow:auto;">
<table border="1" style="width:100%; border:black;  border-collapse:collapse; margine-left:2px;" cellpadding="0" cellspacing="0">
<tr>
<td style="width:80%; text-align:center;">Particulars</td>
<td style="text-align:center;">Amount(in Rs.)</td>
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
$result7 = $this->requestAction(array('controller' => 'hms', 'action' => 'income_head_fetch'),array('pass'=>array($ih_id5)));
foreach($result7 as $collection)
{
$ih_name = $collection['income_head']['ih_name'];
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

$html_mail.='<tr>
<td style="text-align:center;">'.$amount.'</td>
</tr>';
$total_amount2 = $total_amount2 + $amount;
}
$due_amt3 = $due_amt2 - $late_amt2;
$html_mail.='</table>
</td>
</tr>
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
<td style="text-align:center;">'.$total_amount2.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$due_amt3.'</td>
</tr>
<tr>
<td style="text-align:center;">'.@$late_amt2.'</td>
</tr>
<tr>
<th style="text-align:center;">'.$grand_total.'</th>
</tr>
</table>
</td>
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
$this->loadmodel('terms_condition');
$conditions=array("status"=>1,"society_id"=>$s_society_id);
$cursor=$this->terms_condition->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{	
$tems_name = $collection['terms_condition']['terms_conditions'];

$html_mail.='
<tr>
<td style="text-align:left;">'.$tems_name.'</td>
</tr>';

}

$html_mail.='</table> 
</div>
<br><br><br><br>
</div>
';

////////////End Html For mail/////////////////

$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$mail_id = $collection['society']['account_email'];
}
if($mail_id == 1)
{
$from_mail_date = date('d M',strtotime($date_from));
$to_mail_date = date('d M Y',strtotime($date_to));

//$my_mail = "nikhileshvyas@yahoo.com";
$subject = ''.$society_name.' : Maintanance bill, '.$from_mail_date.' to '.$to_mail_date.'';
$from_name="HousingMatters";
//$message_web = "Receipt No. :".$d_receipt_id;
$from = "accounts@housingmatters.in";
$reply="accounts@housingmatters.in";
$this->send_email($to_mail,$from,$from_name,$subject,$html_mail,$reply);
}
}
?>
<!----alert-------------->
	<div class="modal-backdrop fade in"></div>
	<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-body" style="font-size:16px;">
	Bills generated successfully
	</div> 
	<div class="modal-footer">
	<a href="it_regular_bill"   class="btn green">OK</a>
	</div>
	</div>
	<!----alert-------------->
<?php
}
}


////////////////////////// End Regular Bill View2 ////////////////////////////////////

////////////////////// Start Flat Fetch (Accounts)///////////////////////////////////
function flat_fetch2($flat,$wing)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');


$this->loadmodel('flat');
$conditions=array("society_id" => $s_society_id, "flat_id" => $flat, "wing_id" => $wing);
return $this->flat->find('all',array('conditions'=>$conditions));

}
////////////////////// End Flat Fetch (Accounts)///////////////////////////////////////////

//////////////////////// Start Flat Master Fetch(Accounts)//////////////////////////////////
function flat_master_fetch($auto_id)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id, "auto_id" => $auto_id);
return $this->flat_master->find('all',array('conditions'=>$conditions));

}
/////////////////////////// End Flat master fetch (accounts)///////////////////////////////

///////////////////////// Start Flat Type fetch(Accounts)/////////////////////////////////
function flat_type_fetch($auto_id)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id, "auto_id" => $auto_id);
return $this->flat_type->find('all',array('conditions'=>$conditions));

}

/////////////////////////End Flat Type Fetch (Accounts)/////////////////////////////////////

//////////////////// Start regular Bill Fetch(Accounts)/////////////////////////////////////
function regular_bill_fetch3($date1,$date2)
{
$s_role_id = (int)$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$this->loadmodel('regular_bill');
$order=array('regular_bill.regular_bill_id'=>'ASC');
$conditions=array("bill_daterange_from" => array('$gt' => $date1),"bill_daterange_to" => array('$lte' => $date2),"society_id"=>$s_society_id);
return $this->regular_bill->find('all',array('conditions'=>$conditions,'order'=>$order));
}
//////////////////// End regular Bill Fetch(Accounts)/////////////////////////////

////////////////// Start user Fetch(Accounts)////////////////////////////////////////////
function user_fetch($user_id)
{
$s_role_id = (int)$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');

$this->loadmodel('user');
$conditions=array("user_id" => $user_id,"society_id"=>$s_society_id);
return $this->user->find('all',array('conditions'=>$conditions));

}
////////////////// End user Fetch(Accounts)///////////////////////

///////////////////// Start supplimentry bill view2(Accounts)///////////////////////////

function supplimentry_bill_view2()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	


$this->loadmodel('terms_condition');
$conditions=array("status"=>1,"society_id" => $s_society_id);
$cursorr =$this->terms_condition->find('all',array('conditions'=>$conditions));
$this->set('cursorr',$cursorr);











$this->loadmodel('society');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}
$this->set('society_name',$society_name);
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
$z = (int)$this->autoincrement('adhoc_bill','adhoc_bill_id');
$this->set('bill_no',$z);


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
$s_cur_date = new MongoDate(strtotime($s_cur_date));

$s_from2 = date("Y-m-d", strtotime($s_from));
$s_from2 = new MongoDate(strtotime($s_from2));

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
$residing = (int)$collection['user']['residing'];
$user_name = $collection['user']['user_name'];
$wing = (int)$collection['user']['wing'];
$flat =(int)$collection['user']['flat'];
}
$flat1 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch2'),array('pass'=>array($flat,$wing)));
foreach($flat1 as $collection)
{
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$flat_master_id = (int)$collection['flat']['flat_master_id'];
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
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $l, 
"amount" => $amt, "amount_category_id" => 2, "module_id" => 10, "account_type" => 2, "account_id" => $ihid5, "current_date" => $s_cur_date,"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);	
$total = $total + $amt;

$ih_det[] = $s_ih3;
}

$k = (int)$this->autoincrement('ledger','auto_id');
$this->loadmodel('ledger');
$multipleRowData = Array( Array("auto_id" => $k, "receipt_id" => $l, 
"amount" => $total, "amount_category_id" => 1, "module_id" => 10, "account_type" => 1,
"account_id" => $res_id, "current_date" => $s_cur_date,
"society_id" => $s_society_id));
$this->ledger->saveAll($multipleRowData);

$adhoc_bill_id = (int)$this->autoincrement('adhoc_bill','adhoc_bill_id');
$this->loadmodel('adhoc_bill');
$multipleRowData = Array( Array("adhoc_bill_id" => $adhoc_bill_id, "receipt_id" => $adhoc_bill_id, "company_name"=> "",
"person_name"=>$s_res_id,"description"=>$s_desc,"date"=>$s_cur_date,"society_id"=>$s_society_id,"residential"=>"y" ,"g_total"=> $total,"bill_daterange_from"=>$s_from2,"remaining_amt"=>$total,
"bill_html"=>"","pay_status"=>0,"ih_detail"=>$ih_det));
$this->adhoc_bill->saveAll($multipleRowData);	
}
else
{
$l = (int)$this->autoincrement('adhoc_bill','adhoc_bill_id');

$this->loadmodel('adhoc_bill');
$multipleRowData = Array( Array("adhoc_bill_id" => $l, "receipt_id" => $l,"company_name"=> $s_com_name,
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
<div style="width:75%; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:70%;">
<table border="0">
<tr>
<th style="text-align:center;">
<p style="font-size:22px;">'.$society_name.'</p>
</th>
</tr>
<td style="text-align:center;">
Society registation Number
</td>
</tr>
<tr>
<td style="text-align:center;">
Society Address
</td>
</tr>
</table>
</div>
<div style="width:70%;">
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
$residing = (int)$collection['user']['residing'];
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
<td style="text-align:left;">'.$l.'</td>
</tr>
<tr>
<td style="text-align:left;">Bill Creation Date:</td>
<td style="text-align:left;">'.$date.'</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">'.$s_due_date.'</td>
</tr>
<tr>
<td style="text-align:left;">AREA:</td>
<td style="text-align:left;">1120</td>
</tr>
</table>
</div>
<div style="width:70%;">
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
$html.='<tr>
<td style="text-align:center;">'.$amt.'</td>
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
$html.='<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:center;">'.$amt5.'</td>
</tr>';
$gt = $amt5;
$html.='</table>';
}
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
<th style="text-align:center;">'.$gt.'</th>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div style="width:70%;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Terms And Conditions:
</th>
</tr>';
$this->loadmodel('terms_condition');
$conditions=array("status"=>1,"society_id" => $s_society_id);
$cursor =$this->terms_condition->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$tems_name = $collection['terms_condition']['terms_conditions'];
$html.='<tr>
<td style="text-align:left;">'.$tems_name.'</td>
</tr>';
}
$html.='</table> 
</div>
<br><br><br><br>
</div>';
$this->loadmodel('adhoc_bill');
$this->adhoc_bill->updateAll(array("bill_html" =>$html),array("adhoc_bill_id" =>$l));	

////////////////END HTML BILL//////////////////
?>
<!----alert-------------->
	<div class="modal-backdrop fade in"></div>
	<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-body" style="font-size:16px;">
	Bill generated successfully 
	</div> 
	<div class="modal-footer">
	<a href="it_supplimentry_bill"   class="btn green">OK</a>
	</div>
	</div>
	<!----alert-------------->
<?php
}
}




//////////////////////// End supplimentry bill view2(Accounts)/////////////////////////////

//////////////////////// Start terms Condition fetch(Accounts)//////////////////////
function terms_condition_fetch($auto_id) 
{

$this->loadmodel('terms_condition');
$conditions=array("terms_conditions_id" => $auto_id);
return $this->terms_condition->find('all',array('conditions'=>$conditions));

}

////////////////// End terms Condition fetch(Accounts) (Accounts)//////////////////////

///////////////////// Start Flat Type //////////////////////////////////////////////

function flat_type()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$nnn = 5;
$this->set('nnn',$nnn);

if(isset($this->request->data['sub']))
{
$flat_type1 = (int)$this->request->data['flat_type'];
$number_of_flat=(int)$this->request->data['number'];
$auto_id=$this->autoincrement('flat_type','auto_id');
$this->loadmodel('flat_type');
$this->flat_type->saveAll(array('auto_id' => $auto_id, 'flat_type_id' => $flat_type1,"number_of_flat"=>$number_of_flat,"status"=>0,'society_id'=>$s_society_id));

$nnn = 55;
$this->set('nnn',$nnn);
$this->set('flat_type_id',$flat_type1);
$this->set('nof',$number_of_flat);
//$this->set('auto_id',$auto_id);
}

if(isset($this->request->data['sub_area']))
{
$no_of_flat = (int)$this->request->data['nof'];
$flat_type_id = (int)$this->request->data['auto_id'];
$this->set('nof',$no_of_flat);
$this->set('flat_type_id',$flat_type_id);
$area_arr = array();
for($q=1; $q<=$no_of_flat; $q++)
{
$area = (int)$this->request->data['area'.$q];

if(in_array($area, $area_arr))
{
$vali = "Flat Area Should not be Same";
$nnn = 55;
break;
}
else
{
$vali = "";
$nnn = 5;
$area_arr[] = $area;
}

/* $auto_id=$this->autoincrement('flat_master','auto_id');
$this->loadmodel('flat_master');
$this->flat_master->saveAll(array('auto_id' => $auto_id, 'flat_type_id' => $flat_type_id,"flat_area"=>$area,'society_id'=>$s_society_id,"status"=> 0));
*/
}
if($nnn == 55)
{
$this->set('vali',$vali);
$this->set('nnn',$nnn);
}
if($nnn == 5)
{
for($k=0; $k<sizeof($area_arr); $k++)
{
$area = $area_arr[$k];

$area2 = $area_arr[$k];
$auto_id=$this->autoincrement('flat_master','auto_id');
$this->loadmodel('flat_master');
$this->flat_master->saveAll(array('auto_id' => $auto_id, 'flat_type_id' => $flat_type_id,"flat_area"=>$area2,'society_id'=>$s_society_id,"status"=> 0));
}

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('status'=>1),array('flat_type_id'=>$flat_type_id,"society_id"=>$s_society_id));
$this->set('nnn',$nnn);
}
}

/////////////
$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['flat_type']['auto_id'];

if(isset($this->request->data['sub'.$auto_id]))
{
$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id,"status"=>0);
$cursor = $this->flat_master->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$mas_id = (int)$collection['flat_master']['auto_id'];

$area = $this->request->data['area'.$mas_id];


$this->loadmodel('flat_master');
$this->flat_master->updateAll(array('flat_area' => $area),array('auto_id' => $mas_id,"society_id" => $s_society_id,"flat_type_id"=>$auto_id));

}
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Record Updated Successfully
</div> 
<div class="modal-footer">
<a href="flat_type"   class="btn green">OK</a>
</div>
</div>
<!----alert-------------->


<?php
}
}
//////////////

$del_id = (int)$this->request->query('d');
$this->set('del_id',$del_id);

if(isset($this->request->data['del']))
{
$fl_tp_id = (int)$this->request->data['delete'];

$this->loadmodel('flat_type');
$this->flat_type->updateAll(array('status' => 2),array('flat_type_id' => $fl_tp_id,"society_id" => $s_society_id));
$this->loadmodel('flat_master');
$this->flat_master->updateAll(array('status' => 1),array('flat_type_id' => $fl_tp_id,"society_id" => $s_society_id));
$this->response->header('Location','flat_type');
}
$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


$this->loadmodel('flat_type_name');
$cursor2 = $this->flat_type_name->find('all');
$this->set('cursor2',$cursor2);

$b=0;
$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$b++;
$flat_tp_id2 = (int)@$collection['flat_type']['flat_type_id'];
$fl_t[] = $flat_tp_id2;
}
if(!empty($fl_t))
{
$fl_ti = implode(",",$fl_t);
$this->set('fl_ti',$fl_ti);
$this->set('b',$b);
}
}


///////////////////// End Flat Type ////////////////////////////////////////////////

///////////////////////// Start Flat No. Ajax (Accounts)////////////////////////////
function flat_no_ajax()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$flat_type_id = (int)$this->request->query('con');
$t = (int)$this->request->query('t2');
$this->set('t',$t);


$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id, "flat_type_id"=>$flat_type_id);
$cursor1 = $this->flat_master->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);
}
///////////////////////// End Flat No. Ajax (Accounts)/////////////////////////////////////
//////////////// Start ac statement Bill View////////////////////////////////////
function ac_statement_bill_view()
{
$this->layout='blank';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$receipt_id = (int)$this->request->query('bill');

$this->loadmodel('regular_bill');
$conditions=array("regular_bill_id"=>$receipt_id,"society_id" => $s_society_id);
$cursor=$this->regular_bill->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$bill_html = $collection['regular_bill']['bill_html'];	
}
$this->set('bill_html',$bill_html);

}
//////////////// End ac statement Bill View////////////////////////////////////////

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

///////////////////////Start my flat receipt(Accounts)/////////////////////////////
function my_flat_receipt()
{
if($this->RequestHandler->isAjax()){
$this->layout='blank';
}else{
$this->layout='session';
}
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

}
///////////////////////End my flat receipt(Accounts)//////////////////////////////
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
$flat_id = (int)$this->request->query('flat');

$this->set('from',$from);
$this->set('to',$to);
$this->set('flat',$flat_id);

}
/////////////////////// End over due report show ajax(Accounts)//////////////////////////

////////////////// Start Regular Bill Fetch2(Accounts)///////////////////////////
function regular_bill_fetch2($user_id) 
{
$this->loadmodel('regular_bill');
$conditions=array("bill_for_user" => $user_id);
return $this->regular_bill->find('all',array('conditions'=>$conditions));
}
////////////////// End Regular Bill Fetch2(Accounts)//////////////////////////////

////////////////// Start Flat type name fetch(Accounts)///////////////////////////
function flat_type_name_fetch($auto_id) 
{
$this->loadmodel('flat_type_name');
$conditions=array("auto_id" => $auto_id);
return $this->flat_type_name->find('all',array('conditions'=>$conditions));
}
////////////////// End Flat type name fetch(Accounts)//////////////////////////////








///////////////////////// Start user fetch2(Accounts)/////////////////////////////
function user_fetch2($flat_id) 
{
$s_society_id=(int)$this->Session->read('society_id');


$this->loadmodel('user');
$conditions=array("flat" => $flat_id,"society_id" => $s_society_id);
return $this->user->find('all',array('conditions'=>$conditions));
}
///////////////////////// End user fetch2(Accounts)/////////////////////////////////////
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

////////////////////// Start it due tax (Accounts) //////////////////////////////////////

function it_due_tax()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('bill_period');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor1 = $this->bill_period->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


if(isset($this->request->data['taxp']))
{
$type = (int)$this->request->data['type'];
$per = (int)$this->request->data['tax_p'];

$cur_date = date('Y-m-d');
$cur_date = new MongoDate(strtotime($cur_date));

$this->loadmodel('bill_period');
$this->bill_period->updateAll(array('tax' => $per),array('auto_id' => $type,"society_id" => $s_society_id,"status"=>1));

}
}


////////////////////// End it due tax (Accounts) //////////////////////////////////////

////////////////// Start Due Tax (Accounts)///////////////////////////////////////////////
function due_tax_fetch() 
{
$s_society_id=(int)$this->Session->read('society_id');

$this->loadmodel('due_tax');
$conditions=array("society_id" => $s_society_id, "status" => 1);
return $this->due_tax->find('all',array('conditions'=>$conditions));
}
////////////////// End Due Tax Fetch2(Accounts)///////////////////////////////////////////

////////////////// Start Regular Bill Excel (Accounts)//////////////////////////////
function regular_bill_excel()
{
$s_society_id=(int)$this->Session->read('society_id');
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

$this->loadmodel('society');
$conditions=array("society_id"=> $s_society_id);
$cursor=$this->society->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}

$m_from = date("Y-m-d", strtotime($from));
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));






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
$bill_daterange_from2= date('d-m-Y', $bill_daterange_from->sec);
$bill_daterange_to=$collection['regular_bill']["bill_daterange_to"];
$bill_daterange_to2= date('d-m-Y', $bill_daterange_to->sec);
$bill_for_user=(int)$collection['regular_bill']["bill_for_user"];
$bill_html=$collection['regular_bill']["bill_html"];
$g_total=$collection['regular_bill']["g_total"];
$date=$collection['regular_bill']["date"]; 
$pay_status=(int)@$collection['regular_bill']["pay_status"];
				
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

if($m_from <= $bill_daterange_from && $m_to >= $bill_daterange_to)
{
$date = date('d-m-Y', $date->sec);						
$total_amt = $total_amt + $g_total;									
$excel.="								
<tr>
<td>$i</td>
<td>$date</td>
<td>$wing_flat</td>
<td>$user_name</td>
<td>$bill_daterange_from2</td>
<td>$bill_daterange_to2</td>
<td>$g_total</td>";
}}
$excel.="
<tr>
<th colspan='6'>Total</th>
<th>$total_amt</th>
</tr>
";


$excel.="</table>";

echo $excel;
}
////////////////// End Regular Bill Excel (Accounts)////////////////////////////////

/////////////////////// Start Wing Fetch(Accounts) //////////////////////////////////
function wing_fetch($wing) 
{
$s_society_id = $this->Session->read('society_id');

$this->loadmodel('wing');
$conditions=array("wing_id" => $wing,"society_id"=>$s_society_id);
return $this->wing->find('all',array('conditions'=>$conditions));
}
/////////////////////// End Wing Fetch(Accounts)/////////////////////////////////////

/////////////////////// Start Flat Type Fetch(Accounts) ////////////////////////////
function flat_type_fetch2($tp) 
{
$s_society_id = $this->Session->read('society_id');

$this->loadmodel('flat_type');
$conditions=array("auto_id" => $tp,"society_id"=>$s_society_id);
return $this->flat_type->find('all',array('conditions'=>$conditions));
}
/////////////////////// End  Flat Type Fetch(Accounts)//////////////////////////////

/////////////////////// Start Flat Master Fetch (Accounts) ////////////////////////////
function flat_master_fetch2($flm) 
{
$s_society_id = $this->Session->read('society_id');

$this->loadmodel('flat_master');
$conditions=array("auto_id" => $flm,"society_id"=>$s_society_id);
return $this->flat_master->find('all',array('conditions'=>$conditions));
}
/////////////////////// End Flat Master Fetch (Accounts)//////////////////////////////

/////////////////////// Start bill Period Fetch Fetch (Accounts)/////////////////
function bill_period_fetch($auto_id) 
{
$s_society_id = $this->Session->read('society_id');

$this->loadmodel('bill_period');
$conditions=array("auto_id" => $auto_id,"society_id" => $s_society_id,"status"=>1);
return $this->bill_period->find('all',array('conditions'=>$conditions));
}
/////////////////////// End bill Period Fetch Fetch (Accounts)//////////////////////

/////////////////// Start Select Income Heads (Accounts)//////////////////////////////
function select_income_heads()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

if(isset($this->request->data['sub']))
{
$cur_date = date('Y-m-d');
$cur_date = new MongoDate(strtotime($cur_date));

$ih_arr = $this->request->data['i_head'];

$this->loadmodel('income_heads');
$this->income_heads->updateAll(array('delete_id'=> 1),array('status'=>1,'society_id'=>$s_society_id));

for($j=0; $j<sizeof($ih_arr); $j++)
{
$ih = (int)$ih_arr[$j];


$ledgerac = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($ih)));			
foreach($ledgerac as $collection)
{
$ih_name = $collection['ledger_account']['ledger_name'];
}

$k=$this->autoincrement('income_heads','auto_id');

$this->loadmodel('income_heads');
$multipleRowData = Array( Array("auto_id" => $k,"ih_id"=> $ih,"ih_name"=>$ih_name,"status"=>1,"delete_id"=>0, 
"current_date" => $cur_date, "society_id" => $s_society_id));
$this->income_heads->saveAll($multipleRowData);

}
}

$this->loadmodel('accounts_group');
$conditions=array("delete_id"=>0,"accounts_id"=>3);
$cursor1 = $this->accounts_group->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);

$this->loadmodel('income_heads');
$conditions=array("delete_id"=>0,"society_id"=>$s_society_id);
$cursor2 = $this->income_heads->find('all',array('conditions'=>$conditions));
$this->set('cursor2',$cursor2);











}
/////////////////////End Select Income Heads (Accounts)//////////////////////////////

//////////////// Start Income Head Fetch2(Accounts)/////////////////////////
function income_heads_fetch2() 
{
$s_society_id = $this->Session->read('society_id');

$this->loadmodel('income_head');
$conditions=array("society_id" => $s_society_id);
$order=array('income_head.auto_id'=> 'ASC');
return $this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
}
/////////////////// End Income Head Fetch2(Accounts)////////////////////

///////////////////// Start Income Heads Fetch(Accounts)/////////////////////////
function income_head_fetch($auto_id) 
{
$this->loadmodel('income_head');
$conditions=array("auto_id" => $auto_id);
return $this->income_head->find('all',array('conditions'=>$conditions));
}

/////////////////////End Income Heads Fetch(Accounts)//////////////////////////
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

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->flat_type->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id1 = $collection['flat_type']['auto_id'];
$rate_arr = array();

$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
foreach($cursor as $collection)
{
$auto_id2 = (int)$collection['income_head']['auto_id'];

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





}

/////////////////////// End Rate Card View2 (Accounts)/////////////////////////////
////////////////////// Start Flat master Fetch2 (Accounts)/////////////////////////
function flat_master_fetch3($flat_type_id)
{
$s_role_id=$this->Session->read('role_id');
$s_society_id = $this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');

$this->loadmodel('flat_master');
$conditions=array("society_id" => $s_society_id, "flat_type_id" => $flat_type_id);
return $this->flat_master->find('all',array('conditions'=>$conditions));
}
////////////////////// End Flat Master Fetch2 (Accounts)///////////////////////////
///////////////////////// Start In head report (Accounts)//////////////////////////
function in_head_report()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

$this->loadmodel('regular_bill');
$condition=array('society_id'=>$s_society_id);
$result2=$this->regular_bill->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);

}
///////////////////////// End In head report (Accounts)//////////////////////////
///////////////////// Start Master rate Card Edit ///////////////////////////////////
function master_rate_card_edit()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	
$nnn = 5;
$this->set("nnn",$nnn);
if(isset($this->request->data['sub']))
{
$tp_id = (int)$this->request->data['au'];

$this->loadmodel('income_head');
$condition=array('society_id'=>$s_society_id,"delete_id"=>0);
$cursor = $this->income_head->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$auto_id = (int)$collection['income_head']['auto_id'];
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
$this->response->header('Location','master_rate_card_view');
}










$auto_id = (int)$this->request->query('a');
$this->set('auto_id',$auto_id);

$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id,"auto_id"=>$auto_id,"status"=>1);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);

$this->loadmodel('income_head');
$condition=array('society_id'=>$s_society_id,"delete_id"=>0);
$cursor2 = $this->income_head->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$cursor2);


}
///////////////////// End Master rate Card Edit ///////////////////////////////////

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
}
$this->set('society_name',$society_name);
}
/////////////////// /// End in report ajax ////////////////////////////////////

///////////////////////////// Start add_ac_field //////////////////////////////////

function add_ac_field()
{
 $this->layout="session";
 $this->loadmodel('ledger_sub_account');	
 $result=$this->ledger_sub_account->find('all');
	
	foreach($result as $data)
	{
		 $user_id=(int)@$data['ledger_sub_account']['user_id'];
		if(!empty($user_id))
		{
			$this->loadmodel('ledger_sub_account');
			$this->ledger_sub_account->updateAll(array('deactive'=>0),array('user_id'=>$user_id));
		}
		
	}
	
}

////////////////////////////// End add_ac_field //////////////////////////////////

/////////////////////////// Start Master Noc (Accounts)/////////////////////////////
function master_noc()
{
$this->layout='session';
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
///////////////////////// End master Noc (Accounts)/////////////////////////////////

///////////////////// Start IT Penalty (Accounts)///////////////////////////////////

function it_penalty()
{
$this->layout='session';
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');	

if(isset($this->request->data['sub']))
{
//$base = (int)$this->request->data['base'];
$type = (int)$this->request->data['type'];

$tax = $this->request->data['tax'];

$mm = 0;
$this->loadmodel('penalty');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->penalty->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$mm++;
}
if($mm == 0)
{
$k=$this->autoincrement('penalty','auto_id');
$this->loadmodel('penalty');
$multipleRowData = Array( Array("auto_id" => $k,"tax"=>$tax,"status"=>0,"society_id" => $s_society_id,"tax_type"=>$type));
$this->penalty->saveAll($multipleRowData);
}
else
{
$this->loadmodel('penalty');
$this->penalty->updateAll(array('tax'=>$tax,"tax_type"=>$type),array('society_id'=>$s_society_id));

}
}
}

///////////////////// End IT Penalty (Accounts)///////////////////////////////////

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
$this->loadmodel('bank_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->bank_receipt->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
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
if($member == 2)
{
$ref = $collection['bank_receipt']['bill_reference'];
$receiver_name = @$collection['bank_receipt']['receiver_name'];
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
									
									
if($amount_category_id == 1)
{
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
}}}}
$excel.="
<tr>
<th colspan='8'> Total</th>
<th>$total_debit</th>
</tr>
<table>";

echo $excel;
}
////////////////// End Bank receipt Excel (Accounts)/////////////////////////////

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

$excel = "<table border='1'>";
$excel.="<tr>
<th colspan='8' style='text-align:center;'>
Expense Tracker Report ($society_name)  
</th>
</tr>";
$excel.="<tr>
<th colspan='8' style='text-align:center;'>
From: $from To: $to
</th>
</tr>
<tr>
<th>Posting Date</th>
<th>Expense Head</th>
<th>Vendor</th>
<th>Invoice Reference</th>
<th>Invoice Date</th>
<th>Due Date</th>
<th>Description</th>
<th>Rs</th>
</tr>";

$total_amount = 0;
$this->loadmodel('expense_tracker');
$conditions=array("society_id" => $s_society_id);
$cursor=$this->expense_tracker->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id = (int)$collection['expense_tracker']['auto_id'];
$receipt_id = (int)$collection['expense_tracker']['receipt_id'];
$society_id_d = (int)$collection['expense_tracker']['society_id'];
$current_date = $collection['expense_tracker']['current_date'];
$approver_id = (int)$collection['expense_tracker']['approver'];
$expense_head = (int)$collection['expense_tracker']['expense_head'];
$invoice_date = $collection['expense_tracker']['invoice_date'];
$due_date =  $collection['expense_tracker']['due_date'];
$party_head = (int)$collection['expense_tracker']['party_head'];
$description = $collection['expense_tracker']['description'];
$posting_date = $collection['expense_tracker']['posting_date'];
$amount = (int)$collection['expense_tracker']['amount'];
$amount_cat_id = (int)$collection['expense_tracker']['amount_category_id'];
$invoice_ref = $collection['expense_tracker']['invoice_reference'];

if($posting_date >= $m_from && $posting_date <= $m_to)
{
$result23 = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($approver_id)));
foreach($result23 as $collection)
{
$prepaired_by_name = $collection['user']['user_name'];
}

$current_date = date('d-m-Y',$current_date->sec);
$invoice_date = date('d-m-Y',$invoice_date->sec);
$due_date = date('d-m-Y',$due_date->sec);
$posting_date = date('d-m-Y',$posting_date->sec);

$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_head'),array('pass'=>array($expense_head)));
foreach($result1 as $collection)
{
$expense_name = $collection['ledger_account']['ledger_name'];
}

$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($party_head)));
foreach($result2 as $collection)
{
$vendor_name = $collection['ledger_sub_account']['name'];
}
$total_amount = $total_amount + $amount;
$excel.="
<tr>
<td>$posting_date</td>
<td>$expense_name</td>
<td>$vendor_name</td>
<td>$invoice_ref</td>
<td>$invoice_date</td>
<td>$due_date</td>
<td>$description</td>
<td>$amount</td>
</tr>";
}}
$excel.="
<tr>
<th colspan='7'>Total Amount</th>
<th>$total_amount</th>
</tr>
</table>";
echo $excel;
}
////////////////////// End Expense Tracker Excel/////////////////////////////////////

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
$this->loadmodel('petty_cash_receipt');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->petty_cash_receipt->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$receipt_no = @$collection['petty_cash_receipt']['receipt_id'];
$transaction_id = (int)$collection['petty_cash_receipt']['transaction_id'];	
$account_type = (int)$collection['petty_cash_receipt']['account_type'];				  
$d_user_id = (int)$collection['petty_cash_receipt']['user_id'];
$date = $collection['petty_cash_receipt']['transaction_date'];
$prepaired_by = (int)$collection['petty_cash_receipt']['prepaired_by'];   
$narration = $collection['petty_cash_receipt']['narration'];
$account_head = $collection['petty_cash_receipt']['account_head'];
$amount = $collection['petty_cash_receipt']['amount'];
$amount_category_id = (int)$collection['petty_cash_receipt']['amount_category_id'];
$prepaired_by = (int)$collection['petty_cash_receipt']['prepaired_by'];   
$current_date = $collection['petty_cash_receipt']['current_date'];
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

if($amount_category_id == 1)
{
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
 }}}}
 
$excel.="<tr>
<th colspan='4'>Total</th>
<th>$total_debit</th>  
</tr>
</table>"; 
echo $excel;

}
/////////////////////// End Petty cash receipt excel /////////////////////////////

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
$this->loadmodel('petty_cash_payment');
$conditions=array("society_id" => $s_society_id);
$cursor = $this->petty_cash_payment->find('all',array('conditions'=>$conditions));
foreach ($cursor as $collection) 
{
$receipt_no = (int)@$collection['petty_cash_payment']['receipt_id'];
$transaction_id = (int)$collection['petty_cash_payment']['transaction_id'];	
$account_type = (int)$collection['petty_cash_payment']['account_type'];
$user_id = (int)$collection['petty_cash_payment']['user_id'];
$date = $collection['petty_cash_payment']['transaction_date'];
$prepaired_by = (int)$collection['petty_cash_payment']['prepaired_by'];   
$narration = $collection['petty_cash_payment']['narration'];
$account_head = $collection['petty_cash_payment']['account_head'];
$amount = $collection['petty_cash_payment']['amount'];
$amount_category_id = (int)$collection['petty_cash_payment']['amount_category_id'];
$current_date = $collection['petty_cash_payment']['current_date'];
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
if($amount_category_id == 1)
{
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
   }}}}

$excel.="<tr>
<th colspan='4'>Total</th>
<th>$total_debit</th>
</tr>
</table>";

echo $excel;
}
/////////////////////// End Petty Cash Payment Excel////////////////////////////////

/////////////////////// Start Account Statement Excel////////////////////////////////
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
$this->set('from',$from);
$this->set('to',$to);

$this->loadmodel('regular_bill');
$conditions=array("society_id"=> $s_society_id);
$cursor1=$this->regular_bill->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);	

}
///////////////////////// End regular report show ajax///////////////////////////////

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
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

if($tp == 1)
{
$excel="<table border='1'>
<tr>
<th colspan='8' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Bill Type</th>
<th>Member Name</th>
<th>Period From</th>
<th>Period To</th>
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
$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',$bill_date_from->sec);
$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);

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

if($m_from <= $bill_date_from && $m_to >= $bill_date_to)
{
$i++;
$date = date('d-m-Y',$date->sec);
$grand_total = $grand_total + $g_total;
$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$bill_type</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$bill_date_to2</td>
<td>$g_total</td>
</tr>";
}}
$excel.="<tr>
<th colspan='7'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
else if($tp == 2)
{
$excel="<table border='1'>
<tr>
<th colspan='7' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
</tr>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Member Name</th>
<th>Period From</th>
<th>Period To</th>
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
$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',$bill_date_from->sec);
$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);

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

if($m_from <= $bill_date_from && $m_to >= $bill_date_to)
{
	$i++;
$date = date('d-m-Y',$date->sec);
$grand_total = $grand_total + $g_total;

$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$bill_date_to2</td>
<td>$g_total</td>
</tr>";
}}}
$excel.="<tr>
<th colspan='6'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
else if($tp == 3)
{
$excel="<table border='1'>
<tr>
<th colspan='7' style='text-align:center;'>
Supplimentry Bill Report ($society_name)
</th>
</tr>
<tr>
<th>Sr No.</th>
<th>Bill No</th>
<th>Generated on</th>
<th>Member Name</th>
<th>Period From</th>
<th>Period To</th>
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
$bill_date_to = $collection['adhoc_bill']['bill_daterange_to'];
$bill_date_from2 = date('d-m-Y',$bill_date_from->sec);
$bill_date_to2 = date('d-m-Y',$bill_date_to->sec);	
if($residential=="n")
{
$user_name=$collection['adhoc_bill']["person_name"];
//$bill_for="Non-residential";
$bill_type = "Non-residential";
$wing_flat = "";

if($m_from <= $bill_date_from && $m_to >= $bill_date_to)
{
$i++;
$date = date('d-m-Y',$date->sec);
$grand_total = $grand_total + $g_total;
$excel.="<tr>
<td>$i</td>
<td>$adhoc_bill</td>
<td>$date</td>
<td>$user_name&nbsp;&nbsp;$wing_flat</td>
<td>$bill_date_from2</td>
<td>$bill_date_to2</td>
<td>$g_total</td>
</tr>";
}}}
$excel.="<tr>
<th colspan='6'>Total</th>
<th>$grand_total</th>
</tr>
</table>";
}
echo $excel;

}
///////////////////// End supplimentry Bill Excel/////////////////////////////////

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
$m_from = new MongoDate(strtotime($m_from));
$m_to = date("Y-m-d", strtotime($to));
$m_to = new MongoDate(strtotime($m_to));

$n=0;
$this->loadmodel('income_head');
$order=array('income_head.auto_id'=> 'ASC');
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor1 as $collection)
{
$n++;
}
$cols = 5 + $n;

$excel="<table border='1'>
<tr>
<th colspan='$cols' style='text-align:center;'>
Income Head Report ($society_name)
</th>
</tr>
<tr>
<th>Bill No.</th>
<th>Flat No.</th>
<th>Name of Resident</th>";
$this->loadmodel('income_head');
$order=array('income_head.auto_id'=> 'ASC');
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor1 as $collection)
{
$g_t[] = 0;
$income_heads_name = $collection['income_head']['ih_name'];
$excel.="<th>$income_heads_name</th>";
}
$excel.="
<th>Non Occupancy charges</th>
			<th>Total</th>
			</tr>";


$total_noc = 0;
$fetch_ih22 = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch3'),array('pass'=>array($m_from,$m_to)));
$grand_total = 0;
foreach($fetch_ih22 as $collection2)
{
$bill_no = (int)$collection2['regular_bill']['receipt_id'];	
$ih_det = $collection2['regular_bill']['ih_detail'];
$user_id = (int)$collection2['regular_bill']['bill_for_user'];

$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($result_user as $collection3)
{
$user_id = (int)$collection3['user']['user_id'];   
$wing=@$collection3['user']["wing"];
$flat=$collection3['user']["flat"];
$user_name = $collection3['user']['user_name'];
}

$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch'),array('pass'=>array($flat)));
foreach($result2 as $collection)
{
$flat_type_id = $collection['flat']['flat_type_id'];
$flat_master_id = $collection['flat']['flat_master_id'];
}

$result3 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_master_fetch'),array('pass'=>array($flat_master_id)));
foreach($result3 as $collection)
{
$sq_feet = $collection['flat_master']['flat_area'];
}

$result4 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_fetch'),array('pass'=>array($flat_type_id)));
foreach($result4 as $collection)
{
$charge_id = $collection['flat_type']['charge'];	
}

$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
foreach ($result as $collection) 
{
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
}
	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));

$excel.="<tr>
<td>$bill_no</td>			
<td>$wing_flat</td>				
<td>$user_name</td>";

$total = 0;
$p=0;
$this->loadmodel('income_head');
$order=array('income_head.auto_id'=> 'ASC');
$conditions=array("delete_id" => 0,"society_id"=>$s_society_id);
$cursor1=$this->income_head->find('all',array('conditions'=>$conditions,'order' =>$order));
foreach($cursor1 as $collection)
{
$ih_id1 = (int)$collection['income_head']['auto_id'];
$amt = 0;
for($i=0; $i<sizeof($ih_det); $i++)
{
$ih_det2 = $ih_det[$i];
$ih_id2 = (int)$ih_det2[0];
$rate = $ih_det2[1];
if($ih_id1 == $ih_id2)
{
$amt = $rate;
}
}
$excel.="<td>$amt</td>";
$total = $total + $amt;
$g_t[$p] = $g_t[$p]+$amt;
$p++;
} 

for($l=0; $l<sizeof($ih_det); $l++)
{
$ih_det3 = $ih_det[$l];
$ih_id3 = (int)$ih_det3[0];
$rate3 = $ih_det3[1];
if($ih_id3 == 43)
{
$excel.="
<td>$rate3</td>";
$total = $total + $rate3;
$total_noc = $total_noc +$rate3;
}
}
$grand_total = $grand_total + $total;
$excel.="
<td>$total</td>
</tr>";
}
$excel.="
<tr>
<th colspan='3'>Grand Total</th>";

for($k=0; $k<sizeof($g_t); $k++)
{
$g_amt = $g_t[$k];
$excel.="
<th>$g_amt</th>";
}
$excel.="
<th>$total_noc</th>
<th>$grand_total</th>
</tr>
</tbody>
</table>";	


echo $excel;


}
//////////////////////// End income Head report Excel///////////////////////////////

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
$fl = (int)$this->request->query('fl');

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

$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch2'),array('pass'=>array($fl)));
foreach($result1 as $collection)
{
$user_id = $collection['user']['user_id'];
$user_name = $collection['user']['user_name'];
}

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

$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch2'),array('pass'=>array($user_id)));
$c=0;
foreach($result2 as $collection)
{
$bill_no = (int)$collection['regular_bill']['receipt_id'];	
$date_from = $collection['regular_bill']['bill_daterange_from'];	
$date_to = $collection['regular_bill']['bill_daterange_to'];	
$due_date = $collection['regular_bill']['due_date'];	
$total_amt = (int)$collection['regular_bill']['total_amount'];
$tax_amt = (int)$collection['regular_bill']['tax_amount'];	
$due_amt = (int)$collection['regular_bill']['total_due_amount'];	
$bill_amt = (int)$collection['regular_bill']['g_total'];	

$total_amount = $total_amt + $tax_amt;

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
}}}
$excel.="</table>";
echo $excel;
}
////////////////////// End OverDue Excel///////////////////////////////////////////

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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];

$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}

$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
foreach ($date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
}	

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

if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];

$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}

$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
foreach ($date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
}		
		
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
if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];
$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
foreach ($date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
}		

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

if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];

$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
foreach ($date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
}	

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

if($total_debit1 != 0 || $total_credit1 != 0)
{
$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1; 
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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id_s = (int)$collection['ledger']['receipt_id'];

$module2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));		   
foreach ($module2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
	
$date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id_s)));	
foreach ($date_fetch2 as $collection) 
{
$date2 = @$collection[$module_name]['transaction_date'];
if(empty($date2))
{
$date2 = @$collection[$module_name]['posting_date'];	
}
if(empty($date2))
{
$date2 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date2))
{
$date2 = @$collection[$module_name]['date'];	
}
}	

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

if($credit_sub != 0 || $debit_sub != 0)
{
$closing_balance_sub = $opening_balance_sub - $debit_sub + $credit_sub;
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
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id2 = (int)$collection['ledger']['receipt_id'];

$module_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));		
foreach ($module_fetch2 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}
$module_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' =>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id2)));
foreach ($module_fetch3 as $collection) 
{
$date3 = @$collection[$module_name]['transaction_date'];
if(empty($date3))
{
$date3 = @$collection[$module_name]['posting_date'];	
}
if(empty($date3))
{
$date3 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date3))
{
$date3 = @$collection[$module_name]['date'];	
}
}		

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
if($total_debit !=0 || $total_credit != 0)
{ 
$total_closing_balance2 = $total_opening_balance2 + $total_credit - $total_debit;
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
for($k=0; $k<sizeof($ih_arr); $k++)
{
$sub_arr = $ih_arr[$k];
$ih_id1 = (int)$sub_arr[0];
if($ih_id1 != 43)
{
$ih_tt_amt[] = 0;
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'income_head_fetch'),array('pass'=>array($ih_id1)));
foreach($result as $collection)
{
$in_name = $collection['income_head']['ih_name'];	
}
}
$excel.="<th style='text-align:center;'>$in_name</th>";
}
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
for($x=0; $x<sizeof($ih_det); $x++)
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
for($y=0; $y<sizeof($ih_det); $y++)
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
<td style='text-align:center;'>$penalty_amt</td>
<td style='text-align:center;'>$gt_amt</td>
</tr>";
}
$excel.="<tr>
<th colspan='3'>Total</th>";
for($v=0; $v<sizeof($ih_tt_amt); $v++)
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
/////////////////////////// Start master rate card view (Accounts)/////////////////////////////////
function master_rate_card_view()
{
$this->layout="session";
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	


$this->loadmodel('flat_type');
$condition=array('society_id'=>$s_society_id,"status"=>1);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$result2);


$this->loadmodel('income_head');
$order=array('income_head.auto_id'=>'ASC');
$conditions=array("society_id" => $s_society_id,"delete_id"=>0);
$cursor3 = $this->income_head->find('all',array('conditions'=>$conditions,'order' => $order));
$this->set('cursor3',$cursor3);
}
/////////////////////////// End master rate card view (Accounts)/////////////////////////////////

//////////////////////////// Start Flat type edit ///////////////////////////////////////////////
function flat_type_edit()
{
$this->layout="session";
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	

if(isset($this->request->data['update']))
{
$tp_id2 = (int)$this->request->data['tp'];

$area = array();
$this->loadmodel('flat_master');
$condition=array('society_id'=>$s_society_id,"status"=>0,"flat_type_id"=>$tp_id2);
$cursor = $this->flat_master->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$auto_id = (int)$collection['flat_master']['auto_id'];
$area2 = (int)$this->request->data['area'.$auto_id];
if(in_array($area2, $area))
{
$vali = "Flat Area Should not be Same";
$nnn = 55;
break;
}
else
{
$vali = "";
$nnn = 5;
$area[] = $area2;
}
}
if($nnn == 55)
{
$this->set('vali',$vali);
}
else
{
$x=0;
$this->loadmodel('flat_master');
$condition=array('society_id'=>$s_society_id,"status"=>0,"flat_type_id"=>$tp_id2);
$cursor = $this->flat_master->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$auto_id2 = (int)$collection['flat_master']['auto_id'];
$area3 = $area[$x];
$this->loadmodel('flat_master');
$this->flat_master->updateAll(array('flat_area'=>$area3),array('auto_id'=>$auto_id2,"society_id"=>$s_society_id));
$x++;
}
?>

<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<form method="post">
<div class="modal-body" style="font-size:16px;">
Record Updated
<input type="hidden" value="<?php echo $del_id; ?>" name="delete" />
</div> 
<div class="modal-footer">
<a href="flat_type"   class="btn green">OK</a>
</form>
</div>
</div>
<!----alert-------------->




<?php
}
}








$fl_tp_id = (int)$this->request->query('e');
$this->set('fl_tp_id',$fl_tp_id);

$this->loadmodel('flat_type_name');
$cursor1 = $this->flat_type_name->find('all'); 
$this->set('cursor1',$cursor1);

$this->loadmodel('flat_master');
$condition=array('society_id'=>$s_society_id,"status"=>0,"flat_type_id"=>$fl_tp_id);
$cursor2 = $this->flat_master->find('all',array('conditions'=>$condition)); 
$this->set('cursor2',$cursor2);

}
//////////////////////////// End Flat type edit ///////////////////////////////////////////////

////////////////////////////// Start Flat Excel ///////////////////////////////////////////////
function flat_excel()
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

$this->loadmodel('society');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->society->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$society_name = $collection['society']['society_name'];
}












$excel="<table border='1'>
<tr>
<th colspan='5' style='text-align:center;'>$society_name</th></tr>

<tr>
<th>Sr No.</th>
<th>Wing-Name</th>
<th>Flat-Name</th>
<th>Flat Type</th>
<th>Flat Area</th>
</tr>";

$q = 0;
$this->loadmodel('flat');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->flat->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$q++;						
$wing_id = (int)$collection['flat']['wing_id'];
$flat_name = $collection['flat']['flat_name'];
$flat_type_id = (int)$collection['flat']['flat_type_id'];
$flat_master_id = (int)$collection['flat']['flat_master_id'];

$wing_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_fetch'),array('pass'=>array($wing_id)));	
foreach($wing_fetch as $collection)
{							
$wing_name = $collection['wing']['wing_name'];							
}

$fl_tp = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($flat_type_id)));		
foreach($fl_tp as $collection)
{
//$auto_id1 = (int)$collection['flat_type_name']['auto_id'];	
$flat_type = $collection['flat_type_name']['flat_name'];
}

$fmaster = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_master_fetch2'),array('pass'=>array($flat_master_id)));	
foreach($fmaster as $collection)
{							
$sqfeet = $collection['flat_master']['flat_area'];							
}

$excel.="<tr>
<td>$q</td>
<td>$wing_name</td>
<td>$flat_name</td>
<td>$flat_type</td>
<td>$sqfeet</td>
</tr>";
}
$excel.="</table>";
echo $excel;
}
/////////////////////////// End Flat Excel/////////////////////////////////////////////////////////

////////////////////////// Start master noc view/////////////////////////////////////////////////
function master_noc_view()
{
$this->layout="session";
$s_role_id=$this->Session->read('role_id');
$s_society_id = (int)$this->Session->read('society_id');
$s_user_id = (int)$this->Session->read('user_id');	

$this->loadmodel('flat_type');
$conditions=array("society_id" => $s_society_id,"status"=>1);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$conditions));
$this->set('cursor1',$cursor1);


}
////////////////////////// End master noc view/////////////////////////////////////////////////

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
$condition=array('society_id'=>$s_society_id,"auto_id"=>$auto_id,"status"=>1);
$result2=$this->flat_type->find('all',array('conditions'=>$condition)); 
$this->set('cursor1',$result2);
}
//////////////////////// End NOC Edit //////////////////////////////////////////////////////////////

//////////////////////// Start Flat Nu Import ///////////////////////////////////////////////////////
function flat_nu_import()
{
$this->layout='session';
$s_society_id=(int)$this->Session->read('society_id');

if($this->request->is('post')) 
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
$wing_name = trim($r[0]);
//$acccount_type=trim($r[1]); 
$flat_num = trim($r[1]);
$flat_type = trim($r[2]);
$flat_area = trim($r[3]);
//if($i==1) { $email_current=array(); }
//$society_name=trim($r[4]);
//$owner=trim($r[5]);
//$committee=trim($r[6]);
//$residing =trim($r[7]);
$date1 = date("Y-m-d", strtotime($date));
$date1 = new MongoDate(strtotime($date1));
$ok=2; 
}
/*
if(!empty($date)) 
{	
$ok=2; 

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
}
}
if($abc == 5)
{
$ok=2;
}
else
{
$ok=1; $error_msg[]="Date is not in Open Year ".$row_no.".";	break;
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
$ok = 1; $error_msg[]="Please Fill 'Debit' or 'Credit' ".$row_no.".";	break;
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
{ $ok=1; $error_msg[]="account name should not be empty in row ".$row_no.".";	break;}

}
if($total_debit == $total_credit)
{
$ok = 2; 
}
else
{
$ok = 1; $error_msg[]="Total Credit is not equal to Total debit";
}


$this->set('td',$total_debit);
$this->set('tc',$total_credit);

$this->set('error_msg',@$error_msg);
$this->set('ok',$ok);

*/

if($ok == 2)
{
$this->Session->write('test2', $test);
$nnn = 55;
$this->set('nnn',$nnn);
$this->set('test',$test);

$c = 0;
for($i=1;$i<sizeof($test);$i++)
{
$row_no=$i+1;
$r=explode(',',$test[$i][0]);
$wing_name = trim($r[0]);
//$acccount_type=trim($r[1]); 
$flat_nu = trim($r[1]);
$flat_type = trim($r[2]);
$flat_area = trim($r[3]);

$date1 = date("Y-m-d", strtotime($date2));
$date1 = new MongoDate(strtotime($date1));

$this->loadmodel('wing');
$condition=array('society_id'=>$s_society_id);
$cursor = $this->wing->find('all',array('conditions'=>$condition)); 
foreach($cursor as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];
$str1 = $collection['wing']['wing_name'];
if (strcasecmp($str1, $wing_name) == 0) 
{
$wing = (int)$wing_id;
}
}

$this->loadmodel('flat_type_name');
$cursor = $this->flat_type_name->find('all'); 
foreach($cursor as $collection)
{
$fl_tp_id = (int)$collection['flat_type_name']['auto_id'];
$fl_name = $collection['flat_type_name']['flat_name'];
if (strcasecmp($fl_name, $flat_type) == 0) 
{
$flat_type_id = (int)$fl_tp_id;
}
}

$x=$this->autoincrement('flat_type','auto_id');
$this->loadmodel('flat_type');
$this->flat_type->saveAll(array("auto_id" => $x,"flat_type_id"=>$flat_type_id));

$this->set('sucess','Csv Imported successfully.'); 
}
}
}


}
//////////////////////// End Flat Nu Import ///////////////////////////////////////////////////////

////////////////////// Start Accounts Category Excel HM/////////////////////////////////////////////////
function accounts_category_excel_hm()
{
$this->layout="";
$filename="Accounts Category";
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


$excel="<table border='1'>
<tr>
<th style='text-align:center;' colspan='2'>Accounts Category</th>
</tr>
<tr>
<th style='text-align:left;'>Sr. No.</th>
<th style='text-align:left;'>Accounts Category</th>
</tr>";
$n = 1;
$this->loadmodel('accounts_category');
$conditions=array("delete_id" => 0);
$cursor = $this->accounts_category->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$name = $collection['accounts_category']['category_name'];
$auto_id = (int)$collection['accounts_category']['auto_id'];
$excel.="<tr>
<td style='text-align:left;'>$n</td>
<td style='text-align:left;'>$name</td>";
$n++;
}
$excel.="</table>";


echo $excel;
}
////////////////////// End Accounts Category Excel HM/////////////////////////////////////////////////

///////////////////////// Start accounts gruo excel hm ////////////////////////////////////////////////
function accounts_group_excel_hm()
{
$this->layout="";
$filename="Accounts Group";
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

$excel="<table border='1'>
<tr>
<th colspan='2' style='text-align:center;'>Accounts Group</th>
</tr>
<tr>
<th style='text-align:left;'>Sr. No.</th>
<th style='text-align:left;'>Accounts Group</th>
</tr>";
$n = 1;
$this->loadmodel('accounts_group');
$conditions=array("delete_id" => 0);
$cursor = $this->accounts_group->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$name = $collection['accounts_group']['group_name'];
$auto_id = (int)$collection['accounts_group']['auto_id'];

$excel.="
<tr>
<td style='text-align:left;'>$n</td>
<td style='text-align:left;'>$name</td>
</tr>
";
}
$excel.="</table>";

echo $excel;
}
///////////////////////// End accounts gruo excel hm //////////////////////////////////////////////////

/////////////////////// Start Ledger Account Excel Hm ///////////////////////////////////////////////////
function ledger_account_excel_hm()
{
$this->layout="";
$filename="Ledger Accounts";
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

$excel="<table border='1'>
<tr>
<th colspan='4'>Ledger Accounts</th>
</tr>
<tr>
<th style='text-align:left;'>Sr. No.</th>
<th style='text-align:left;'>Accounts Category</th>
<th style='text-align:left;'>Accounts Group</th>
<th style='text-align:left;'>Ledger Accounts</th>
</tr>";
$n = 1;
$this->loadmodel('ledger_account');
$conditions=array("delete_id" => 0);
$cursor = $this->ledger_account->find('all',array('conditions'=>$conditions));
foreach($cursor as $collection)
{
$auto_id5 = (int)$collection['ledger_account']['auto_id'];
$sub_id = (int)$collection['ledger_account']['group_id'];
$name = $collection['ledger_account']['ledger_name'];
$edit_id = (int)$collection['ledger_account']['edit_user_id'];

$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($sub_id)));
foreach ($result_ag as $collection) 
{
$accounts_id = (int)$collection['accounts_group']['accounts_id'];	
$group_name = $collection['accounts_group']['group_name'];	
}

$result_ac = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_category'),array('pass'=>array($accounts_id)));		   
foreach ($result_ac as $collection) 
{
$main_name = $collection['accounts_category']['category_name'];	
}
$excel.="<tr>
<td style='text-align:left;'>$n</td>
<td style='text-align:left;'>$main_name</td>
<td style='text-align:left;'>$group_name</td>
<td style='text-align:left;'>$name</td>
</tr>";
$n++;
}	
$excel.="</table>";

echo $excel;

}
/////////////////////// End Ledger Account Excel Hm ///////////////////////////////////////////////////

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
$this->response->header('Location', 'master_noc_view');
}

$this->loadmodel('flat_type');
$order=array('flat_type.auto_id'=>'ASC');
$condition=array('society_id'=>$s_society_id);
$cursor1 = $this->flat_type->find('all',array('conditions'=>$condition,'order' => $order)); 
$this->set('cursor1',$cursor1);
}
/////////////////////////// End Nov View2 ///////////////////////////////////////////////////////////


function update_wing_flat()
{
	$this->layout="session";
	$s_society_id = (int)$this->Session->read('society_id');
	
	/*$this->loadmodel('user');
	$result_user_acc=$this->user->find('all',array('conditions'=>array('society_id'=>$s_society_id),'role_id' =>array('$in' => array(2))));
	foreach($result_user_acc as $data_acc)
	{
		$user_name=$data_acc["user"]["user_name"];
		$user_id=$data_acc["user"]["user_id"];
		$deactive=$data_acc["user"]["deactive"];
		
		$this->loadmodel('ledger_sub_account');
		$k=$this->autoincrement('ledger_sub_account','auto_id');
		$this->ledger_sub_account->saveAll(array("auto_id" => $k, "ledger_id" =>34, 'name'=>$user_name,"society_id" => $s_society_id,"deactive" => $deactive,"user_id" => $user_id));
	}*/
	
	
	if(isset($this->request->data['update']))
	{
		$user=(int)$this->request->data['user'];
		$wing=(int)$this->request->data['wing'];
		$flat=(int)$this->request->data['flat'];
		
		$this->loadmodel('user');
		$this->user->updateAll(array('wing'=>$wing,'flat'=>$flat),array('user_id'=>$user));
		
	}
	
	$this->loadmodel('user');
	$this->set('result_user',$this->user->find('all',array('conditions'=>array('society_id'=>$s_society_id))));
	
	$this->loadmodel('wing');
	$this->set('result_wing',$this->wing->find('all',array('conditions'=>array('society_id'=>$s_society_id))));
}

function update_wing_flat_ajax()
{
	$this->layout="blank";
	$w=(int)$this->request->query('w');
	$s_society_id = (int)$this->Session->read('society_id');
	$this->loadmodel('flat');
	$this->set('result_flat',$this->flat->find('all',array('conditions'=>array('wing_id'=>$w))));
}

}
?>