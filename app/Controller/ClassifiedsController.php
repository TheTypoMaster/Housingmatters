<?php
App::import('Controller', 'Hms');
class ClassifiedsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Classifieds';

function post_ad(){
	if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	$this->ath();
	
	$this->loadmodel('master_classified_category');
	$this->set('result_select_category',$this->master_classified_category->find('all'));

}

function submit_ad(){
	$this->layout=null;
	$output = json_encode(array('type'=>'error', 'text' => 'Please fill subject.'));
		die($output);
}


}
?>