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
$s_society_id=$this->Session->read('society_id');
$s_user_id=$this->Session->read('user_id');
$this->loadmodel('governance_designation');
$condition=array('society_id'=>$s_society_id);
$result=$this->governance_designation->find('all',array('conditions'=>$condition)); 
$this->set('result_governance_designation',$result);

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


}

?>