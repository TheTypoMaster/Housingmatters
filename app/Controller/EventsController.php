<?php
App::import('Controller', 'Hms');
class EventsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Events';



function event_add()
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
$s_result= $this->society_name($s_society_id);
foreach($s_result as $data)
{
	
	$society_name=$data['society']['society_name'];
	
}
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
$ip=$this->hms_email_ip();
if($day_type==2)
{
$date_from1=$this->request->data['date_from'];
$date_from=date("Y-m-d",strtotime($date_from1));
$date_from = new MongoDate(strtotime($date_from));
$date_to1=$this->request->data['date_to'];
$date_to=date("Y-m-d",strtotime($date_to1));
$date_to = new MongoDate(strtotime($date_to));
$date_email="from $date_from1 to $date_to1";
}

if($day_type==1)
{
$date_from1=$this->request->data['date_single'];
$date_from=date("Y-m-d",strtotime($date_from1));
$date_from = new MongoDate(strtotime($date_from));

$date_to1=$this->request->data['date_single'];
$date_to=date("Y-m-d",strtotime($date_to1));
$date_to = new MongoDate(strtotime($date_to));
$date_email="on $date_to1";
}




$location=htmlentities($this->request->data['location']);
$description=htmlentities($this->request->data['description']);
$visible=(int)$this->request->data['visible'];

if($visible==1)
{	
$visible=1;
$sub_visible[]=0;
/////////////////////////////////////////// All user ////////////////////////////
$result_user=$this->all_user_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
}
/////////////////////////////////////////// All user ////////////////////////////
}

if($visible==4)
{	
$visible=4;
$sub_visible[]=0;
/////////////////////////////////////////// All Owners ////////////////////////////
$result_user=$this->all_owner_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
}
/////////////////////////////////////////// All Owners ////////////////////////////
}

if($visible==5)
{
$visible=5;
$sub_visible[]=0;
/////////////////////////////////////////// All Tenant ////////////////////////////
$result_user=$this->all_tenant_deactive();
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
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
$result_user=$this->all_role_wise_deactive($role_id);
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
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
$result_user=$this->all_wing_wise_deactive($wing);
foreach($result_user as $data)
{
$visible_user_id[]=$data['user']['user_id'];
$da_to[]=$data['user']['email'];
$da_user_name[]=$data['user']['user_name'];
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
	$d_u_id=(int)$data_user;
	$res_user=$this->profile_picture($d_u_id);
	foreach($res_user as $ddd)
	{
		$da_to[]=$ddd['user']['email'];
		$da_user_name[]=$ddd['user']['user_name'];
	}
	
  $visible_user_id[]=(int)$data_user;
}
/////////////////////////////////////////// Manually ////////////////////////////
}


///////// creator send email code //////////////////

$result_user=$this->profile_picture($s_user_id);
foreach($result_user as $data)
{
	 $c_email=$data['user']['email'];
	 $c_user_id=$data['user']['user_id'];
	 $c_user_name=$data['user']['user_name'];
	
}
$da_to[]=$c_email;
$da_user_name[]=$c_user_name;
$visible_user_id[]=$c_user_id;

$da_to=array_unique($da_to);
$da_user_name=array_unique($da_user_name);
$visible_user_id=array_unique($visible_user_id);

/////////////////////////  end code ////////////////////////////////

////////////////// Email Functionality code ////////////////////////

$from="Support@housingmatters.in";
$reply="Support@housingmatters.in";
$from_name="HousingMatters";
for($k=0;$k<sizeof($da_to);$k++)
{
$to = @$da_to[$k];
$user_name = @$da_user_name[$k];	

$message_web="<div>
<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
</br><p>Hello $user_name </p>
<p>A new event has been created.</p>
<p><span>$e_name</span></p>
<p><span>$date_email</span></p>
<p><span>$location</span></p>
<div>
<br/>
<br/>
Thank you.<br/>
HousingMatters (Support Team)<br/><br/>
www.housingmatters.co.in
</div>
</div>";
 @$subject.= ''. $society_name . '  ' .'     '.' '.$e_name.'';
$this->send_email($to,$from,$from_name,$subject,$message_web,$reply);
$subject="";



}



////////////////// End Email code ////////////////////////////////////







$visible_user_id = array_unique($visible_user_id);

ksort($visible_user_id);
foreach($visible_user_id as $x=>$x_value)
{
$visible_user_id_new[]=$x_value;
}

$event_id=$this->autoincrement('event','event_id');
$this->loadmodel('event');
$this->event->saveAll(array('event_id' => $event_id,'e_name' => $e_name, 'user_id' => $s_user_id, 'society_id' => $s_society_id, 'date_from' => $date_from , 'date_to' => $date_to, 'day_type' => $day_type, 'location' => $location,'description' => $description,'visible' => $visible,'sub_visible' => $sub_visible,'visible_user_id' => $visible_user_id_new,'date' => $date));


$this->send_notification('<span class="label" style="background-color:#44b6ae;"><i class="icon-gift"></i></span>','New Event <b>'.$e_name.'</b> submitted by',6,$event_id,$this->webroot.'Events/event_info/'.$event_id,$s_user_id,$visible_user_id_new);
?>
<!----alert-------------->
<div id="submit_success">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-body" style="font-size:16px;">
Your Event has been created.
</div> 
<div class="modal-footer">
<a href="<?php echo $this->webroot; ?>Events/events" rel='tab' class="btn green" role="hide_submit_success">OK</a>
</div>
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



function event_info($e_id=null)
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
$this->set('s_user_id',$s_user_id);

$e_id=(int)$e_id;
$this->set('e_id',$e_id);

$this->seen_notification(6,$e_id);
$this->seen_alert(6,$e_id);

if (isset($this->request->data['sub_update'])) 
{
	

$title=htmlentities($this->request->data['title']);
$title=wordwrap($title, 25, " ", true);
$title_cat=$this->request->data['title_cat'];
$title_des=htmlentities($this->request->data['description']);


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


$this->send_notification('<span class="label" style="background-color:#d43f3a;"><i class="icon-tags"></i></span>','Updates for Event <b>'.$e_name.'</b> submitted by',6,$e_id,$this->webroot.'Events/event_info?e='.$e_id,$s_user_id,$visible_user_id);

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


} ?>