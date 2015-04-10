<?php
App::import('Controller', 'Hms');
class DiscussionsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Discussions';




function index($id=null,$list=null){
	if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	$this->ath();
	$this->set('id',$id);
	$id=(int)$this->decode($id,'housingmatters');
	$this->set('list',$list);
	
	
	$s_user_id=$this->Session->read('user_id'); 
	$s_society_id=$this->Session->read('society_id');
	$s_role_id=$this->Session->read('role_id');
	$this->set('s_user_id',$s_user_id);
	$tenant=$this->Session->read('tenant');
	$role_id=$this->Session->read('role_id');
	$wing=$this->Session->read('wing');

	$this->seen_notification(3,$id);

	//////////////////////current user info///////////////
	$result_self=$this->profile_picture($s_user_id);
	foreach($result_self as $data3)
	{
	$this->set('user_name',$data3["user"]["user_name"]);
	$wing=$data3["user"]["wing"];
	$flat=$data3["user"]["flat"];
	}
	$this->set('flat_info',$this->wing_flat($wing,$flat);
	//////////////////////current user info///////////////

	$this->loadmodel('role');
	$conditions=array("society_id" => $s_society_id);
	$role_result=$this->role->find('all',array('conditions'=>$conditions));
	$this->set('role_result',$role_result);

	$this->loadmodel('wing');
	$wing_result=$this->wing->find('all');
	$this->set('wing_result',$wing_result);

	//////////////////////view///////////////
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
	if(empty($id)){
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
		$conditions=array('discussion_post_id' =>$id,'users' =>array('$in' => array($s_user_id)));
		$count=$this->discussion_post->find('count',array('conditions'=>$conditions));
		if($count>0){ $conditions=array('discussion_post_id' =>$id);	}
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
	$this->set('result_comment_last',$this->discussion_comment->find('all',array('conditions'=>$conditions))); 
	
}

function new_topic(){
	if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	$this->ath();
	
	
	$s_user_id=$this->Session->read('user_id'); 
	$s_society_id=$this->Session->read('society_id');
	$s_role_id=$this->Session->read('role_id');
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
	$this->set('flat_info',$this->wing_flat($wing,$flat);
	//////////////////////current user info///////////////
	$this->loadmodel('role');
	$conditions=array("society_id" => $s_society_id);
	$role_result=$this->role->find('all',array('conditions'=>$conditions));
	$this->set('role_result',$role_result);

	$this->loadmodel('wing');
	$wing_result=$this->wing->find('all');
	$this->set('wing_result',$wing_result);
	///////////////////////start new topic//////////////////////////////////

	$result_soc=$this->society_name($s_society_id);
	foreach($result_soc as $data)
	{
		 @$discussion_forum1=$data['society']['discussion_forum'];
		//@$s_duser_id=$data['society']['user_id'];
	}
if($discussion_forum1==1 && $s_role_id!=3)
{
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
			}

			if($visible==4)
			{	
			$visible=4;
			$sub_visible[]=0;
			}

			if($visible==5)
			{
			$visible=5;
			$sub_visible[]=0;
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
					
					
	$discussion_post_id=$this->autoincrement('discussion_post','discussion_post_id');
	$this->loadmodel('discussion_post');
	$multipleRowData = Array( Array("discussion_post_id" => $discussion_post_id, "user_id" => $s_user_id , "society_id" => $s_society_id, "topic" => $topic,"description" => $description, "file" =>$file,"delete_id" =>4, "date" =>$date, "time" => $time, "visible" => $visible, "sub_visible" => $sub_visible));
	$this->discussion_post->saveAll($multipleRowData); 
					
	?>
                

				<!----alert-------------->
				<div class="modal-backdrop fade in"></div>
				<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
				<div class="modal-body" style="font-size:16px;">
				Discussion Forum are sent for approval.
				</div> 
				<div class="modal-footer">
				<a href="<?php echo $this->webroot; ?>Discussions/index" class="btn green">OK</a>
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
	
	$ip=$this->hms_email_ip();
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
	$params = array(
		'controller' => 'Hms',
		'action' => 'all_wing_wise_deactive',
		'plugin' => null,
		'pass' => array($wing_id)
		);
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
	

	$this->send_notification('<span class="label" style="background-color:#269abc;"><i class="icon-comment"></i></span>','New Discussion <b>'.$topic.'</b> created by',3,$discussion_post_id,$this->webroot.'Discussions/index/'.$discussion_post_id.'/0',$s_user_id,$da_user_id);


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
	<img src='$ip".$this->webroot."/as/hm/hm-logo.png'/><span  style='float:right; margin:2.2%;'>
	<span class='test' style='margin-left:5px;'><a href='https://www.facebook.com/HousingMatters.co.in' target='_blank' ><img src='$ip".$this->webroot."/as/hm/fb.png'/></a></span>
	<a href='#' target='_blank'><img src='$ip".$this->webroot."/as/hm/tw.png'/></a><a href'#'><img src='$ip".$this->webroot."/as/hm/ln.png'/ class='test' style='margin-left:5px;'></a></span>
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
	<a href='$ip".$this->webroot."hms' ><button style='width:100px; height:30px;  background-color:#00A0E3;color:white'> Click Here </button></a></p></center><br/>
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
	$this->redirect(array('controller' => 'Discussions','action' => 'index'));

	}
		
		
		
	}



	///////////////////////End start new topic//////////////////////////////////
	
}



function delete_topic(){
$this->layout='blank';
$s_society_id=$this->Session->read('society_id'); 

$con=(int)$this->request->query('con');
$con=(int)$this->decode($con,'housingmatters');
if($con==0) { $this->redirect(array('controller' => 'Discussions','action' => 'index')); }

$this->loadmodel('discussion_post');
$this->discussion_post->updateAll(array("delete_id" =>1),array("discussion_post_id" => $con));

$this->redirect(array('controller' => 'Discussions','action' => 'index/mytopics/1'));
}

function archive()
{
	$this->layout='blank';
	$s_society_id=$this->Session->read('society_id'); 
	$con=(int)$this->request->query('con');
	$con=(int)$this->decode($con,'housingmatters');
	if($con==0) { $this->redirect(array('controller' => 'Discussions','action' => 'index')); }
	$this->loadmodel('discussion_post');
	$this->discussion_post->updateAll(array("delete_id" =>2),array("discussion_post_id" => $con));
	$this->redirect(array('controller' => 'Discussions','action' => 'index/archives/2'));
	
}

function discussion_save_comment(){
$this->layout='blank';
$this->ath();
$s_user_id=$this->Session->read('user_id'); 
$s_society_id=$this->Session->read('society_id'); 
$tid=(int)$this->request->query('tid');
$g=$this->request->query('c');
$c=htmlentities(wordwrap($g, 25, " ", true));

$c=nl2br($c);
$date=date("d-m-y");
$time=date('h:i:a',time());
	
	$r=$this->content_moderation_society('pass' => array($g));
	
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

function discussion_comment_refresh(){
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

function discussion_comment_delete_ajax(){
$this->layout='blank';

$s_society_id=$this->Session->read('society_id'); 

$c_id=(int)$this->request->query('c_id');

$this->loadmodel('discussion_comment');
$this->discussion_comment->updateAll(array("delete_id" =>1),array("discussion_comment_id" => $c_id));
//$this->response->header('Location', 'discussion');
}



function discussion_offensive_delete_ajax(){
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


function discussion_search_topic(){
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


}
?>