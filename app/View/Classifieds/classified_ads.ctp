<div align="center">
<a href="/Housingmatters/Classifieds/classified_ads" class="btn allsubmenu red" style="margin-left: 2px;margin-bottom: 4px;" rel="tab">View Classified Ads</a>
<a href="/Housingmatters/Classifieds/post_ad" class="btn blue allsubmenu" style="margin-left: 2px;margin-bottom: 4px;" rel="tab">Post new Classified Ad</a></div>



<?php
$c=0;
foreach ($result_classifieds as $classified){
	$c++;
	if ($c % 2 != 0) {
		echo '<div class="row-fluid">';
	}
	$title=$classified['classified']['title'];
	$file=$classified['classified']['file'];
	$price=$classified['classified']['price'];
	$category=$classified['classified']['category'];
	$category_name=$this->requestAction(array('controller' => 'Hms', 'action' => 'classified_category_name'), array('pass' => array($category)));
	$sub_category=$classified['classified']['sub_category'];
	$sub_category_name=$this->requestAction(array('controller' => 'Hms', 'action' => 'classified_subcategory_name'), array('pass' => array($sub_category)));?>
	
	<div class="span6" >
		<div class="white">
			<!--Ad content start-->
			<table width="100%">
				<tr>
					<td width="30%" align="center" style=" background-color: #F1F3FA; ">
						<?php if(!empty($file)) { ?>
						<img src="<?php echo $webroot_path; ?>Classifieds/<?php echo $file; ?>" style="height:120px;" />
						<?php } ?>
						
					</td>
					<td width="70%" valign="top">
						<table width="100%">
							<tr>
								<td width="70%"><span class="title"><?php echo $title; ?></span></td>
								<td width="30%" align="right"><span class="price">&#8377; <?php echo $price; ?></span></td>
							</tr>
							<tr>
								<td width="30%" colspan="2"><span class="category"><?php echo $category_name; ?> -> <?php echo $sub_category_name; ?></span></td>
							</tr>
							<tr><td>sfdf</td><td>sdfsdfsd</td></tr>
							<tr><td>sfdf</td><td>sdfsdfsd</td></tr>
							<tr><td>sfdf</td><td>sdfsdfsd</td></tr>
						</table>
					</td>
				</tr>
			</table>
			<!--Ad content end-->
		</div>
	</div>
<?php if ($c % 2 == 0) {
			echo '</div>';
		}
		if (sizeof($result_classifieds)%2 != 0 and sizeof($result_classifieds)==$c) {
			echo '</div>';
		}
	} ?>



<div class="view_div"  style="display:none;">
	<div class="modal-backdrop fade in"></div>
	<div id="myModal3" class="modal fade in" style="width: 96%; margin: auto; left: 2%;">
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span9" align="center">
					<img src="<?php echo $webroot_path; ?>Classifieds/<?php echo $file; ?>" style="max-height: 400px;"/>
				</div>
				<div class="span3">
					dasdasdas
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn pull-left" data-dismiss="modal" aria-hidden="true">Close</button>
			<button data-dismiss="modal" class="btn blue">Confirm</button>
		</div>
	</div>
</div>

<style>
.white{
background-color: white;
padding:2px;
border: 1px solid #E7E7E7;
margin-bottom: 5px;
}
.white:hover{
border: 1px solid rgba(27, 188, 158, 0.24);
background-color: rgba(27, 188, 158, 0.1);
cursor: pointer;
}
.white:active{
border: 2px solid rgba(27, 188, 158, 0.24);
}
.title{
font-size: 15px;
color: #1BBC9B;
}
.price{
font-size: 15px;
}
.category{
color: rgb(142, 142, 142);
font-size: 12px;
}
body.modal-open {
    overflow: hidden;
}
</style>
<script>
function view_classified(){
	$(document).ready(function() {
		$(".view_div").show();
		$("body").addClass("modal-open");
	});
}
</script>
<?php if(!empty($id)){ ?>
	<script>view_classified();</script>
<?php } ?>

