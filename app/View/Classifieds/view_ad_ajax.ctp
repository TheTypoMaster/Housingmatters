<?php foreach ($result_classified as $classified){
	$classified_id=(int)$classified['classified']['classified_id'];
	$title=strtoupper($classified['classified']['title']);
	$file=$classified['classified']['file'];
	$price=$classified['classified']['price'];
	$price_type=$classified['classified']['price_type'];
	if($price_type==1){
		$price_type_text="Negotiable";
	}elseif($price_type==2){
		$price_type_text="Fixed";
	}
	$ad_type=$classified['classified']['ad_type'];
	$condition=$classified['classified']['condition'];
	if($condition==1){
		$condition_text="Used";
	}elseif($condition==2){
		$condition_text="New";
	}
	$offer=(int)$classified['classified']['offer'];
	$created=date('Y-m-d',$classified['classified']['created']->sec);
	$now = time();
	$your_date = strtotime($created);
	$datediff = $now - $your_date;
	$days=floor($datediff/(60*60*24));
	$offer_for=$offer-$days;

	$description=$classified['classified']['description'];
	$category=$classified['classified']['category'];
	$category_name=$this->requestAction(array('controller' => 'Hms', 'action' => 'classified_category_name'), array('pass' => array($category)));
	$sub_category=$classified['classified']['sub_category'];
	$sub_category_name=$this->requestAction(array('controller' => 'Hms', 'action' => 'classified_subcategory_name'), array('pass' => array($sub_category)));
}
?>
<div class="modal-body">
	<div class="row-fluid">
		<div class="span7" align="center">
		<?php if(!empty($file)) { ?>
		<img src="<?php echo $webroot_path; ?>Classifieds/<?php echo $file; ?>" style="height:400px;" />
		<?php } ?>
		</div>
		<div class="span5" >
		<!--Ad content start-->
		<table width="100%">
			<tr>
				<td colspan="2" width="100%">
					<div class="title_v pull-left"><?php echo $title; ?><br/><span class="category_v"><?php echo $category_name; ?> -> <?php echo $sub_category_name; ?></span></div>
				</td>
			</tr>
			<tr>
				<td width="20%" class="tag">Condition:</td>
				<td class="tag_val"><?php echo $condition_text; ?></td>
			</tr>
			<tr>
				<td width="20%" class="tag">Price: </td>
				<td class="tag_val"><div class="price_v">&#8377; <?php echo $price; ?>&nbsp;&nbsp;<span class="category"><?php echo $price_type_text; ?></span></div></td>
			</tr>
			<tr>
				<td width="20%" class="tag">Offer for:</td>
				<td class="tag_val"><?php echo $offer_for.'<span style="color:red;">*</span> Days'; ?></td>
			</tr>
			<tr>
				<td width="20%" class="tag" valign="top">Description:</td>
				<td class="tag_des"><?php echo $description; ?></td>
			</tr>
		</table>
			<!--Ad content end-->
		</div>
	</div>
</div>
<div class="modal-footer">
	<button class="btn blue pull-left" data-dismiss="modal" aria-hidden="true">Previous</button>
	<a href="#" role="button" class="btn model_close">Close</a>
	<button data-dismiss="modal" class="btn blue">Next</button>
</div>