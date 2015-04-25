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

function classified_ads($id=null){
	if($this->RequestHandler->isAjax()){
		$this->layout='blank';
	}else{
		$this->layout='session';
	}
	$this->ath();
	$this->set('id',$id);
	
	$this->loadmodel('classified');
	$conditions=array('delete'=>0,'draft'=>0);
	$result_classifieds=$this->classified->find('all',array('conditions'=>$conditions));
	$this->set('result_classifieds',$result_classifieds);
}

function submit_ad(){
	$this->layout=null;
	$post_data=$this->request->data;
	$this->ath();
	$s_society_id=$this->Session->read('society_id');
	$s_user_id=$this->Session->read('user_id');
	$date=date('d-m-Y');
	$time = date(' h:i a', time());
		
	$cat_id=$post_data["cat_id"];
	if(!empty($cat_id)){
		$cat_id_ar=explode(',',$cat_id);
		$category=(int)$cat_id_ar[0];
		$sub_category=(int)$cat_id_ar[1];
	}
	
	
	$title=$post_data["title"];
	$price=$post_data["price"];
	$price_type=$post_data["price_type"];
	$ad_type=$post_data["ad_type"];
	$condition=$post_data["condition"];
	$offer=(int)$post_data["offer"];
	if(empty($offer)){ $offer=30; }
	$description=$post_data["description"];
	$post_type=$post_data["post_type"];
	
	$file_name="";
	if(isset($_FILES['file'])){
		$file_name=$_FILES['file']['name'];
		$file_size=$_FILES['file']['size'];
		$file_tmp_name=$_FILES['file']['tmp_name'];
		$file_type=$_FILES['file']['type'];
		if($file_size>100000){
			$report[]=array('label'=>'file', 'text' => 'Image size is too big. It should be less than 1 MB.');
		}
	}
	
	$report=array();
	if(empty($cat_id)){
		$report[]=array('label'=>'cat_id', 'text' => 'Please select category');
	}
	if(empty($title)){
		$report[]=array('label'=>'title', 'text' => 'Please fill title');
	}
	if(empty($price)){
		$report[]=array('label'=>'price', 'text' => 'Please fill price');
	}
	if(empty($price_type)){
		$report[]=array('label'=>'price_type', 'text' => 'Please select price_type');
	}
	if(empty($ad_type)){
		$report[]=array('label'=>'ad_type', 'text' => 'Please fill ad_type');
	}
	if(empty($condition)){
		$report[]=array('label'=>'condition', 'text' => 'Please fill condition');
	}
	if(empty($description)){
		$report[]=array('label'=>'description', 'text' => 'Please fill description');
	}
	
	if(sizeof($report)>0){
		$output=json_encode(array('report_type'=>'error','report'=>$report));
		die($output);
	}
	
	
	if($post_data['post_type']==1){
		
		if(isset($_FILES['file'])){
		$target = "Classifieds/";
		$target=@$target.basename($file_name);
		move_uploaded_file($file_tmp_name,@$target);
		}

		$classified_id=$this->autoincrement('classified','classified_id');
		$this->loadmodel('classified');
		$this->classified->save(array('classified_id' => $classified_id, 'category' => $category, 'sub_category' => $sub_category, 'title' => $title ,'price' => $price , 'price_type' => $price_type, 'ad_type' => $ad_type, 'condition' => $condition,'offer' => $offer, 'description' => $description, 'delete' => 0,'draft' => 0,'user_id' => $s_user_id,'society_id' => $s_society_id,'file' => $file_name));
		
		$output=json_encode(array('report_type'=>'publish','report'=>'Your Classified ad has been published successfully.'));
		die($output);
	}
	if($post_data['post_type']==2){
		
		if(isset($_FILES['file'])){
		$target = "Classifieds/";
		$target=@$target.basename($file_name);
		move_uploaded_file($file_tmp_name,@$target);
		}
		
		$classified_id=$this->autoincrement('classified','classified_id');
		$this->loadmodel('classified');
		$this->classified->save(array('classified_id' => $classified_id, 'category' => $category, 'sub_category' => $sub_category, 'title' => $title ,'price' => $price , 'price_type' => $price_type, 'ad_type' => $ad_type, 'condition' => $condition,'offer' => $offer, 'description' => $description, 'delete' => 0,'draft' => 1,'user_id' => $s_user_id,'society_id' => $s_society_id,'file' => $file_name));
		
		$output=json_encode(array('report_type'=>'publish','report'=>'Your Classified ad has been saved as draft successfully.'));
		die($output);
	}
	
}


}
?>