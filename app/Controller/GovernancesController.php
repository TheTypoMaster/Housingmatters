<?php
App::import('Controller', 'Hms');
class GovernancesController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Governances';


////////////////////////// Governance_designation ////////////////////////////////////////


function governance_designation()
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
$this->loadmodel('governance_designation');
$condition=array('society_id'=>$s_society_id);
$result=$this->governance_designation->find('all',array('conditions'=>$condition)); 
$this->set('result_governance_designation',$result);

}

function governance_invite_submit()
{
	
	$this->layout=null;
	$post_data=$this->request->data;
	
	$this->ath();
	$s_society_id=$this->Session->read('society_id');
	$s_role_id=$this->Session->read('role_id'); 
	$s_user_id=$this->Session->read('user_id');
	$ip=$this->hms_email_ip();
	$Invitations_type=(int)$post_data['Invitations_type'];
	$type_mettings=(int)$post_data['type_mettings'];
	$subject=$post_data['subject'];
	$date=$post_data['date'];
	$time=$post_data['time'];
	$location=$post_data['location'];
	$covering_note=$post_data['covering_note'];
	 $meeting_agenda_time=$post_data['meeting_agenda_time'];
	 $meeting_agenda_input=$post_data['meeting_agenda_input'];
	 $meeting_agenda_textarea=$post_data['meeting_agenda_textarea'];
	 $meeting_agenda_time=explode(",",$meeting_agenda_time);
	$meeting_agenda_input=explode(",",$meeting_agenda_input);
	$meeting_agenda_textarea=explode(",",$meeting_agenda_textarea);
	
	/////////////////// validation ///////////////////////////
	
		$report=array();
		if(empty($subject)){
		$report[]=array('label'=>'subject', 'text' => 'Please fill title');
		}
		if(empty($date)){
		$report[]=array('label'=>'date', 'text' => 'Please fill date');
		}
		if(empty($time)){
		$report[]=array('label'=>'time', 'text' => 'Please fill time');
		}
		if(empty($location)){
		$report[]=array('label'=>'location', 'text' => 'Please fill location');
		}
		
		
			
	/////////////////////////////////////////////////////////////////////////
	
	
		$message="";
		for($z=0;$z<sizeof($meeting_agenda_input);$z++)
		{
			
			$message[]=array($meeting_agenda_input[$z],$meeting_agenda_textarea[$z],$meeting_agenda_time[$z]);
		}
				
			if($type_mettings==1)
			{
				$moc="Managing Committee";

			}
			if($type_mettings==2)
			{
				$moc="General Body";

			}
			if($type_mettings==3)
			{
				$moc="Special General Body";

			}
			
				if(isset($_FILES['file'])){
				$target = "governances_file/";
				  $file_name=@$_FILES['file']['name']; 
				$file_tmp_name =$_FILES['file']['tmp_name'];
				$target=@$target.basename($file_name);
				move_uploaded_file($file_tmp_name,@$target);
				}
				$file_att="";
				if(!empty($file_name))
				{
				@$file_att='<br/><a href="'.$ip.'/'.$this->webroot.'governances_file/'.$file_name.'" download>Download attachment</a>';
				}

				
					$result_society=$this->society_name($s_society_id);
					foreach($result_society as $data)
					{
					$society_name=$data['society']['society_name'];
					//$user_id=$data['society']['user_id'];
					
					}
					$result_user=$this->profile_picture($s_user_id);
					foreach($result_user as $data4)
					{
						 $user_name=$data4['user']['user_name'];
						
					}
		
					  $email_id=$this->autoincrement('governance_invite','governance_invite_id');

		
		if($Invitations_type==1)
		{
			$invite_user_multi=$post_data['Invite_user1'];
			$invite_user_multi=explode(",",$invite_user_multi);
			
			////////////////////////////// validation check//////////////////
			
					if($invite_user_multi[0]=='null'){

					$report[]=array('label'=>'multi', 'text' => 'Please select at-least one recipient.');
					}

					if(sizeof($report)>0){
					$output=json_encode(array('report_type'=>'error','report'=>$report));
					die($output);
					}
			
			//////////////////////////////// end ////////////////////////////////
			
			//$user=$invite_user_multi;
		/////////////////  start email code ////////////////////////////////
		
			foreach($invite_user_multi as $data)
				{
					$da_user_id[]=(int)$data;
					$user[]=(int)$data;
					$result_user=$this->profile_picture((int)$data);
					
					foreach($result_user as $da)
					{
											
						$to=$da['user']['email']; 
						  @$message_web="<div>
						<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
						<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
						<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
						<br/><br/>
						<p><center><b>[$society_name]</b></center></p>
						<p><b>Meeting Type:</b> [ $moc ] </p>
						<p><b>Meeting Title:</b>  $subject  </p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Date</td>
						<td>Time</td>
						<td>Location</td>
						<td>Meeting ID</td>
						</tr>
						<tr class='tr_content' style=background-color:#E9E9E9;'>
						<td>$date</td>
						<td>$time</td>
						<td>$location</td>
						<td>$email_id</td>
						</tr>
						</table>
						<div>
						<p><b>Covering Note:</b><br/>
						<p>$covering_note</p>
						<p> <b>	Agenda to be discussed: </b></p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Time</td>
						<td>Meeting Agenda</td>
						
						</tr>";
						$jj=0;
						foreach($message as $ddd)
						{	$jj++;

						$message_web.="<tr>
						<td width='10%'>".urldecode($ddd[2])."</td>
						<td>".$jj.". ".urldecode($ddd[0]). " <br/> ".urldecode($ddd[1])."</td>
						</tr>";	
						}
						$message_web.="</table>
						</div>
						<br/>
						For [ $society_name ].<br/>
						$user_name<br/>
						$file_att <br/>
						</div>";
						@$title.= '['. $society_name . ']  - '.'[ '.$moc.' Meeting ] '.'  on   '.''.$date.'';	
						 
						 $this->send_email($to,'support@housingmatters.in','HousingMatters',$title,$message_web,'donotreply@housingmatters.in');
						 $title="";
						
					}
					
				}
			///////////////// end email code /////////////////////////////////////////
			
			
			  
				$this->loadmodel('governance_invite');
				$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message"=>$message,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>$Invitations_type,"file"=>@$file_name,"deleted"=>0,'user'=>$user,'location'=>$location,'meeting_type'=>$type_mettings,'covering_note'=>$covering_note));
				$this->governance_invite->saveAll($multipleRowData); 
			
		
		}
		
		if($Invitations_type==2)
		{
			    //$to=$post_data['Invite_user2'];
				 $Invite_group=$post_data['Invite_group'];
				$Invite_group=explode(",",$Invite_group);
				
				
				////////////////////////////// validation check//////////////////
			
					if($Invite_group[0]=='null' || $Invite_group[0]=='' ){

					$report[]=array('label'=>'multi_check', 'text' => 'Please check at-least one ');
					}

					if(sizeof($report)>0){
					$output=json_encode(array('report_type'=>'error','report'=>$report));
					die($output);
					}
			
			//////////////////////////////// end ////////////////////////////////
				foreach($Invite_group as $group_id)
				{
					
					$this->loadmodel('group');
					$conditions=array('group_id'=>(int)$group_id);
					$result_group=$this->group->find('all',array('conditions'=>$conditions));
					foreach($result_group as $data2)
					{
						$userl_group=$data2['group']['users'];
						
						foreach($userl_group as $data3)
						{
							$user[]=(int)$data3;
							
						}
						
						
					}
					
				}

				$user=array_unique($user);
				$user=array_values($user);
				$da_user_id=$user;
				
	/////////////////// Start email code ///////////////////////////
	
				foreach($user as $data6)
				{
					
					$result_user1=$this->profile_picture($data6);
					foreach($result_user1 as $data7)
					{
						 $to=$data7['user']['email'];
						
						
						 @$message_web="<div>
						<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
						<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
						<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
						<br/><br/>
						<p><center><b>[$society_name]</b></center></p>
						<p><b>Meeting Type:</b> [ $moc ] </p>
						<p><b>Meeting Title:</b>  $subject  </p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Date</td>
						<td>Time</td>
						<td>Location</td>
						<td>Meeting ID</td>
						</tr>
						<tr class='tr_content' style=background-color:#E9E9E9;'>
						<td>$date</td>
						<td>$time</td>
						<td>$location</td>
						<td>$email_id</td>
						</tr>
						</table>
						<div>
						<p><b>Covering Note:</b><br/>
						<p>$covering_note</p>
						<p> <b>	Agenda to be discussed: </b></p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Time</td>
						<td>Meeting Agenda</td>
						
						</tr>";
						$jj=0;
						foreach($message as $ddd)
						{	$jj++;

						$message_web.="<tr>
						<td width='10%'>".urldecode($ddd[2])."</td>
						<td>".$jj.". ".urldecode($ddd[0]). " <br/> ".urldecode($ddd[1])."</td>
						</tr>";	
						}
						$message_web.="</table>
						</div>
						<br/>
						For [ $society_name ].<br/>
						$user_name<br/>
						$file_att <br/>
						</div>";
					 @$title.= '['. $society_name . ']  - '.'[ '.$moc.' Meeting ] '.'  on   '.''.$date.'';	
					$this->send_email($to,'support@housingmatters.in','HousingMatters',$title,$message_web,'donotreply@housingmatters.in');
						$title="";
				     
					}
				}
		///////////////////////// End code ///////////////////////////////////////		
			
			
			$this->loadmodel('governance_invite');
			$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message"=>$message,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>$Invitations_type,"file"=>@$file_name,"deleted"=>0,'location'=>$location,'meeting_type'=>$type_mettings,'covering_note'=>$covering_note,'user'=>$user,'group_id'=>$Invite_group));
			$this->governance_invite->saveAll($multipleRowData); 
			
			
			
		}
		
		if($Invitations_type==3)
		{
			 $visible=(int)$post_data['visible'];
			
			$sub_visible=$post_data['sub_visible'];
			$sub_visible=explode(",",$sub_visible);
			
			//////////////////// validation //////////////
			
				if($visible==2)
				{
					
					
					if($post_data['sub_visible']==0)
					{
						
						$report[]=array('label'=>'role_check', 'text' => 'Please select at-least one');
						
					}
					
					
				}
				if($visible==3)
				{
					
					if($post_data['sub_visible']==0)
					{
						
						$report[]=array('label'=>'wing_check', 'text' => 'Please select at-least one');
						
					}
					
					
				}
				if(sizeof($report)>0){
					$output=json_encode(array('report_type'=>'error','report'=>$report));
					die($output);
					}
			
			///////////////  end /////////////////////////
			
			
			
			$recieve_info=$this->visible_subvisible($visible,$sub_visible);
			
			////////////////////  Start email code ////////////////////////////
			
			foreach($recieve_info[0] as $data=>$key )
			{
				 $to = @$key;
				 $d_user_id = @$data;	
				 $da_user_id[]=$d_user_id;		
				 $result_user=$this->profile_picture($data);
				 //$user_name=$result_user[0]['user']['user_name'];
				 @$message_web="<div>
						<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
						<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
						<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
						<br/><br/>
						<p><center><b>[$society_name]</b></center></p>
						<p><b>Meeting Type:</b> [ $moc ] </p>
						<p><b>Meeting Title:</b>  $subject  </p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Date</td>
						<td>Time</td>
						<td>Location</td>
						<td>Meeting ID</td>
						</tr>
						<tr class='tr_content' style=background-color:#E9E9E9;'>
						<td>$date</td>
						<td>$time</td>
						<td>$location</td>
						<td>$email_id</td>
						</tr>
						</table>
						<div>
						<p><b>Covering Note:</b><br/>
						<p>$covering_note</p>
						<p> <b>	Agenda to be discussed: </b></p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Time</td>
						<td>Meeting Agenda</td>
						
						</tr>";
						$jj=0;
						foreach($message as $ddd)
						{	$jj++;

						$message_web.="<tr>
						<td width='10%'>".urldecode($ddd[2])."</td>
						<td>".$jj.". ".urldecode($ddd[0]). " <br/> ".urldecode($ddd[1])."</td>
						</tr>";	
						}
						$message_web.="</table>
						</div>
						<br/>
						For [ $society_name ].<br/>
						$user_name<br/>
						$file_att <br/>
						</div>";
						@$title.= '['. $society_name . ']  - '.'[ '.$moc.' Meeting ] '.'  on   '.''.$date.'';	
				$this->send_email($to,'support@housingmatters.in','HousingMatters',$title,$message_web,'donotreply@housingmatters.in');
				$title="";
			}
			
			/////////////////////  End code /////////////////////////////
			
			$this->loadmodel('governance_invite');
			$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message"=>$message,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>$Invitations_type,"file"=>@$file_name,"deleted"=>0,'user'=>$da_user_id,'location'=>$location,'visible'=>$visible,'sub_visible'=>$sub_visible,'meeting_type'=>$type_mettings,'covering_note'=>$covering_note));
			$this->governance_invite->saveAll($multipleRowData); 
			
			
		}
		
			if(sizeof($report)>0){
			$output=json_encode(array('report_type'=>'error','report'=>$report));
			die($output);
			}
		$this->send_notification('<span class="label label-info" ><i class="icon-bullhorn"></i></span>','New Meeting Invitation published - <b>'.$subject.'</b> by',40,$email_id,$this->webroot.'Governances/governance_invite_view/',$s_user_id,$da_user_id);
			
	$output = json_encode(array('type'=>'created', 'text' =>'Invitation successfully submitted'));
	die($output);
	
}


function governance_invite()
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
	
$this->loadmodel('user');
$conditions1=array("society_id"=>$s_society_id,'user.email'=> array('$ne' => ""));
$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions1))); 

$this->loadmodel('group');
$conditions=array("society_id"=>$s_society_id,'group_show_id'=>0);
$result_group=$this->group->find('all',array('conditions'=>$conditions)); 
$this->set('result_group',$result_group); 

$this->loadmodel('user');
$conditions2=array("society_id"=>$s_society_id,'role_id'=>1);
$this->set('result_users_com',$this->user->find('all',array('conditions'=>$conditions2))); 


$this->loadmodel('user');
$conditions2=array("society_id"=>$s_society_id,'role_id'=>array('$ne'=>1));
$this->set('result_users_non_com',$this->user->find('all',array('conditions'=>$conditions2))); 


$this->loadmodel('role');
$conditions=array("society_id" => $s_society_id);
$role_result=$this->role->find('all',array('conditions'=>$conditions));
$this->set('role_result',$role_result);
$this->loadmodel('wing');
$conditions=array("society_id" => $s_society_id);
$wing_result=$this->wing->find('all',array('conditions'=>$conditions));
$this->set('wing_result',$wing_result);

$this->loadmodel('governance_designation');
$conditions=array("society_id" => $s_society_id);
$gov_result=$this->governance_designation->find('all',array('conditions'=>$conditions));
$this->set('governance_designation_result',$gov_result);
	if (isset($this->request->data['send'])) {

		
		echo $hide_val=$this->request->data['hide_val'];
			for($i=1;$i<=$hide_val;$i++)
			{
				$comm_1[]=$this->request->data['comm_'.$i.''];
				
			}
			pr($comm_1);
	exit;
				$ip=$this->hms_email_ip();
			$radio=$this->request->data['radio'];
			 $type_mettings=$this->request->data['type_mettings']; 
			$message_db=$this->request->data['email'];
			//$designation_id=$this->request->data['designation'];
			$subject=$this->request->data['subject'];
			$date=$this->request->data['date'];
			$time=$this->request->data['time'];
			$location=$this->request->data['location'];
			$file=$this->request->form['file']['name'];
			$message_web="<div>
			<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
			<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
			<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
			<br/>
			
			<div>$message_db</div>
			<br/>
		
			Thank you.<br/>
			HousingMatters (Support Team)<br/>
			www.housingmatters.co.in
			</div>";


			if(!empty($file))
			{
			$message_web.='<br/><a href="'.$ip.'/'.$this->webroot.'governances_file/'.$file.'" download>Download attachment</a>';
			}
		
			$target = "governances_file/";
			$target=@$target.basename( @$this->request->form['file']['name']);
			$ok=1;
			move_uploaded_file(@$this->request->form['file']['tmp_name'],@$target); 

			
			
					
			if($radio==1)
			{
				$multi=$this->request->data['multi'];
				$multi=array_unique($multi);
				foreach($multi as $data)
				{
				$ex = explode(",", $data);
				$user[]=$ex[0];
				$to=$ex[1];
				//echo $email[$i];
				$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'donotreply@housingmatters.in');
				}
				$email_id=$this->autoincrement('governance_invite','governance_invite_id');
				$this->loadmodel('governance_invite');
				$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message_web"=>$message_db,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>1,"file"=>$file,"deleted"=>0,'user'=>$user,'location'=>$location,'meeting_type'=>$type_mettings));
				$this->governance_invite->saveAll($multipleRowData); 

			}
			
	if($radio==3)
	{
		 $visible=(int)$this->request->data['visible'];
		
		if($visible==1)
		{	
		$visible=1;
		$sub_visible[]=0;
		/////////////////////////////////////////// All user ////////////////////////////
		
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
		$result_user=$this->all_wing_wise_deactive($wing);
		foreach($result_user as $data)
		{
		$da_to[]=$data['user']['email'];
		$da_user_name[]=$data['user']['user_name'];
		$da_user_id[]=$data['user']['user_id'];
		}
		}
		}
		}
		//$da_to[]=$sender_email;
		$da_user_id=array_unique($da_user_id);	
		$da_to=array_unique($da_to);
		$da_to=array_filter($da_to);


		foreach($da_to as $data)
		{

		$ex = explode(",", $data);
		if(!empty($ex[0])) { $to=$ex[0]; }


		//echo $email[$i];
		$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'donotreply@housingmatters.in');
		}
	
			$email_id=$this->autoincrement('governance_invite','governance_invite_id');
				$this->loadmodel('governance_invite');
				$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message_web"=>$message_db,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>3,"file"=>$file,"deleted"=>0,'user'=>$da_user_id,'location'=>$location,'visible'=>$visible,'sub_visible'=>$sub_visible,'meeting_type'=>$type_mettings));
				$this->governance_invite->saveAll($multipleRowData); 

		
		
		
		
	}
		if($radio==2)
		{
			
			 $to=$this->request->data['other_user'];
			
			$this->send_email($to,'support@housingmatters.in','HousingMatters',$subject,$message_web,'donotreply@housingmatters.in');
			$email_id=$this->autoincrement('governance_invite','governance_invite_id');
			$this->loadmodel('governance_invite');
			$multipleRowData = Array( Array("governance_invite_id" => $email_id,"message_web"=>$message_db,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject,"type"=>2,"file"=>$file,"deleted"=>0,'location'=>$location,'other_user'=>$to,'meeting_type'=>$type_mettings));
			$this->governance_invite->saveAll($multipleRowData); 
			
		}
			
		?>

		<!----alert-------------->
		<div class="modal-backdrop fade in"></div>
		<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-body" style="font-size:16px;">
		Successfully invited.
		</div> 
		<div class="modal-footer">
		<a href="governance_invite_view" class="btn green">OK</a>
		</div>
		</div>
		<!----alert-------------->
		<?php		

    }
}

function governance_invite_view()
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
	$this->loadmodel('governance_invite');
	$conditions=array('society_id'=>$s_society_id);
    $order=array('governance_invite.governance_invite_id'=> 'DESC');
	$result_gov_inv=$this->governance_invite->find('all',array('conditions'=>$conditions,'order'=>$order));
	$this->set('result_gov_invite',$result_gov_inv);
	foreach($result_gov_inv as $data4)
	{
		$this->seen_notification(40,$data4["governance_invite"]["governance_invite_id"]);
		
	}
}

function governance_invite_view1($id)
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	$this->ath();
	 $s_society_id=$this->Session->read('society_id');
		$result_society=$this->society_name($s_society_id);
		foreach($result_society as $data)
		{
			$society_name=$data['society']['society_name'];
			$this->set('society_name',$society_name);
		}
	$this->loadmodel('governance_invite');
	$conditions=array('governance_invite_id'=>(int)$id);
	$result_gov_inv=$this->governance_invite->find('all',array('conditions'=>$conditions));
	
	$this->set('result_gov_invite',$result_gov_inv);

	
}
function governance_minutes()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	$this->ath();
	$this->check_user_privilages();
	$s_society_id=$this->Session->read('society_id');
	$this->loadmodel('user');
	$conditions1=array("society_id"=>$s_society_id,'deactive'=>0);
	$this->set('result_users',$this->user->find('all',array('conditions'=>$conditions1))); 

	$this->loadmodel('governance_invite');
	$conditions1=array("society_id"=>$s_society_id);
	$this->set('result_governance_invite',$this->governance_invite->find('all',array('conditions'=>$conditions1)));

	
}

function governance_minute_submit()
{
	
	$this->layout=null;
	$post_data=$this->request->data;
	pr($post_data);
	$this->ath();
	$s_society_id=$this->Session->read('society_id');
	$s_role_id=$this->Session->read('role_id'); 
	$s_user_id=$this->Session->read('user_id');
	$ip=$this->hms_email_ip();	
	$present_user=$post_data['present_user'];
	$subject1=$post_data['subject'];
	$meeting_id=(int)$post_data['meeting_id'];
	$date=$post_data['date'];
	$time=$post_data['time'];
	$location=$post_data['location'];
	$covering_note=$post_data['covering_note'];
	$meeting_agenda_input=$post_data['meeting_agenda_input'];
	$meeting_agenda_textarea=$post_data['meeting_agenda_textarea'];
	$meeting_agenda_input=explode(",",$meeting_agenda_input);
	$meeting_agenda_textarea=explode(",",$meeting_agenda_textarea);
	$present_user=explode(",",$present_user);
	/////////////////// validation ///////////////////////////
	
		$report=array();
		if(empty($subject1)){
		$report[]=array('label'=>'subject', 'text' => 'Please fill title');
		}
		if(empty($date)){
		$report[]=array('label'=>'date', 'text' => 'Please fill date');
		}
		if(empty($time)){
		$report[]=array('label'=>'time', 'text' => 'Please fill time');
		}
		if(empty($location)){
		$report[]=array('label'=>'location', 'text' => 'Please fill location');
		}
		
		
			
	/////////////////////////////////////////////////////////////////////////
		
		$result_society=$this->society_name($s_society_id);
		foreach($result_society as $data2){
			$society_name=$data2['society']['society_name'];
			
		}
		$result_user=$this->profile_picture($s_user_id);
		foreach($result_user as $data3){
			$user_name=$data3['user']['user_name'];
			
		}
		if(isset($_FILES['file'])){
				$target = "governances_file/";
				  $file_name=@$_FILES['file']['name']; 
				$file_tmp_name =$_FILES['file']['tmp_name'];
				$target=@$target.basename($file_name);
				move_uploaded_file($file_tmp_name,@$target);
				}
				$file_att="";
				if(!empty($file_name)){
				@$file_att='<br/><a href="'.$ip.'/'.$this->webroot.'governances_file/'.$file_name.'" download>Download attachment</a>';
				}

	
			$message="";
		for($z=0;$z<sizeof($meeting_agenda_input);$z++){
			
			$message[]=array($meeting_agenda_input[$z],$meeting_agenda_textarea[$z]);
		}
		
			$this->loadmodel('governance_invite');
			$conditions=array("governance_invite_id"=>$meeting_id);
			$result_gov_int=$this->governance_invite->find("all",array('conditions'=>$conditions));
				foreach($result_gov_int as $data){
					
					$user=$data['governance_invite']['user'];
					
				}
				
				$user1=array_merge($user,$present_user);
				$user=array_unique($user1);
				$user=array_values($user);
				
			
				$minut_id=$this->autoincrement('governance_minute','governance_minute_id');
				$this->loadmodel('governance_minute');
				$multipleRowData = Array( Array("governance_minute_id" => $minut_id,"message"=>$message,"user_id"=>$s_user_id,"date"=>$date,"time"=>$time,"society_id"=>$s_society_id,"subject"=>$subject1,"file"=>@$file_name,"deleted"=>0,'user'=>$user,'location'=>$location,'meeting_id'=>$meeting_id,'covering_note'=>$covering_note,'present_user'=>$present_user));
				//$this->governance_invite->saveAll($multipleRowData); 
	
	////////////////////////////// Email code start //////////////////////////////////
				foreach($user as $data){
					   @$message_web="<div>
						<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
						<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
						<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
						<br/><br/>
						<p><center><b>[$society_name]</b></center></p>
					
						<p><b>Minutes Title:</b>  $subject1  </p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Date</td>
						<td>Time</td>
						<td>Location</td>
						<td>Meeting ID</td>
						</tr>
						<tr class='tr_content' style=background-color:#E9E9E9;'>
						<td>$date</td>
						<td>$time</td>
						<td>$location</td>
						<td>$meeting_id</td>
						</tr>
						</table>
						<div>
						<p><b>Covering Note:</b><br/>
						<p>$covering_note</p>
						<p> <b>	Agenda to be discussed: </b></p>
						<table  cellpadding='10' width='100%;' border='1' bordercolor='#e1e1e1'  >
						<tr class='tr_heading' style='background-color:#00A0E3;color:white;'>
						<td>Minutes Agenda</td>
						</tr>";
						$jj=0;
						foreach($message as $ddd){	$jj++;

							$message_web.="<tr>
							
							<td>".$jj.". ".urldecode($ddd[0]). " <br/> ".urldecode($ddd[1])."</td>
							</tr>";	
						}
						$message_web.="</table>
						</div>
						<br/>
						For [ $society_name ].<br/>
						$user_name<br/>
						$file_att <br/>
						</div>";
				}
	echo $message_web;
	exit;
	//////////////////////////////// End ////////////////////////////////////////
	
	
	
}

function governance_assign_user()
{
	if($this->RequestHandler->isAjax()){
	$this->layout='blank';
	}else{
	$this->layout='session';
	}
	$this->ath();
	$this->check_user_privilages();
	$s_society_id=$this->Session->read('society_id');
	$this->loadmodel('user');
	$conditions2=array("society_id"=>$s_society_id,'role_id'=>1);
	$result_user=$this->user->find('all',array('conditions'=>$conditions2));
	$this->set('result_users_com',$result_user); 
	$this->loadmodel('governance_designation');
	$conditions=array("society_id" => $s_society_id);
	$gov_result=$this->governance_designation->find('all',array('conditions'=>$conditions));
	$this->set('governance_designation_result',$gov_result);
	if(isset($this->request->data['send'])) {

		/*
			$multi=$this->request->data['multi1'];
			$designation=(int)$this->request->data['designation'];
			foreach($multi as $data)
			{
				$id=(int)$data;
			$this->loadmodel('user');
			$this->user->updateAll(array('designation_id'=>$designation),array('user_id'=>$id));
			}
			
			*/
			foreach ($result_user as $collection) 
			{
			$user_id=$collection["user"]["user_id"];
			$designation=(int)$this->request->data['designation'.$user_id];	
			if($designation!=0)
			{
				$this->loadmodel('user');
				$this->user->updateAll(array('designation_id'=>$designation),array('user_id'=>$user_id));
			}
			else{
				$this->loadmodel('user');
				$this->user->updateAll(array('designation_id'=>$designation),array('user_id'=>$user_id));

			}	
			}
			
			
	    ?>
		
		<!----alert-------------->
		<div class="modal-backdrop fade in"></div>
		<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
		<div class="modal-body" style="font-size:16px;">
		Successfully assign to designation role.
		</div> 
		<div class="modal-footer">
		<a href="governance_assign_user" class="btn green">OK</a>
		</div>
		</div>
		<!----alert-------------->
		
		
		<?php
	
	}
}


function designation_edit()
{
	$this->layout='blank';
	$designation_id=(int)$this->request->query('d_id');
	 $edit=(int)$this->request->query('edit');
	 $this->set('edit',$edit);
	if($edit==0)
	{
	$this->loadmodel('governance_designation');
	$conditions=array("governance_designation_id" => $designation_id);
	$des_result=$this->governance_designation->find('all', array('conditions' => $conditions));
	$this->set('des_result',$des_result);
	}
	if($edit==1)
	{
	 $des=$this->request->query('des');	
	 $this->loadmodel('governance_designation');
	 $this->governance_designation->updateAll(array('designation_name'=>$des),array('governance_designation.governance_designation_id'=>$designation_id));
		
	}
	
}
function governance_designation_ajax()
{
	$this->layout=null;	
	$post_data=$this->request->data;
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$date=date('d-m-Y');
	$time = date(' h:i a', time());	
	$designation = htmlentities($post_data['designation']);
	$report = array();
	if(empty($designation)){
	$report[]=array('label'=>'win', 'text' => 'Please Fill designation Name');
	}
				
	if(sizeof($report)>0)
	{
	$output=json_encode(array('report_type'=>'error','report'=>$report));
	die($output);
	}
	
	
	$this->loadmodel('governance_designation');
	$governance_designation_id=$this->autoincrement('governance_designation','governance_designation_id');
	$this->governance_designation->saveAll(array('governance_designation_id'=>$governance_designation_id,'society_id'=>$s_society_id,'user_id'=>$s_user_id,'date'=>$date,'time'=>$time,'designation_name'=>$designation));

$output=json_encode(array('report_type'=>'publish','report'=>'Designation Inserted Successfully'));
die($output);
	
}
//////////////////////////  end deginations ////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////	
/////////////////////////////////////////////////////start groups//////////////////////
////////////////////////////////////////////////////////////////////////////////////////
function groups_new()
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
	$multipleRowData = Array( Array("group_id" => $group_id,"group_name"=>$group_name,"society_id"=>$s_society_id,'group_show_id'=>0,"users"=>array()));
	$this->group->saveAll($multipleRowData); 
	$this->response->header('Location', 'groupview/'.$group_id);
	}
	else{
		$this->set('error_addgroup','Group name should not be duplicate.');
	}
}

$this->loadmodel('group');
$conditions=array("society_id"=>$s_society_id,'group_show_id'=>0);
$order=array('group.group_id'=>'DESC');
$this->set('result_group',$this->group->find('all',array('conditions'=>$conditions,'order'=>$order))); 
}

function groupview($gid=null) 
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
	$gid=(int)$gid;
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


}

?>