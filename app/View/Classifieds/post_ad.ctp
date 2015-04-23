<div style="background-color:#fff;padding:5px;">
	<h4 style="color: #1BBC9B;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-shopping-cart"></i> Post New Classified Ad</h4>
<!--FORM CONTENT START-->
<div id="output"></div>
<form method="post">
<div class="row-fluid">

	<div class="span6">
		<div id="selected_name"></div>
		<a href="#myModal1" role="button" data-toggle="modal">Select Category</a>
		<div id="myModal1" class="modal fade hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
			<div class="modal-header">
				<h4 id="myModalLabel1">Select Category for Classifieds Creation</h4>
			</div>
			<div class="modal-body">
				<div class="tabbable tabbable-custom tabs-left">
					<!-- Only required for left/right tabs -->
					<ul class="nav nav-tabs tabs-left">
					<?php 
					$i=0;
					foreach($result_select_category as $category) {
					$i++;
					$category_name=$category["master_classified_category"]["category_name"];
					$category_id=$category["master_classified_category"]["category_id"]; ?>
						<li <?php if($i==1) {echo 'class="active"'; } ?> ><a href="#tab_<?php echo $category_id; ?>" data-toggle="tab"><?php echo $category_name; ?></a></li>
					<?php } ?>
					</ul>
					<div class="tab-content">
					<?php 
					$i=0;
					foreach($result_select_category as $category) {
					$i++;
					$category_name=$category["master_classified_category"]["category_name"];
					$category_id=(int)$category["master_classified_category"]["category_id"]; ?>
						<div <?php if($i==1) {echo 'class="tab-pane active"'; } else{ echo 'class="tab-pane"';} ?> id="tab_<?php echo $category_id; ?>">
						
							<?php
							$sub_cat = $this->requestAction(array('controller' => 'hms', 'action' => 'master_classified_subcategory'),array('pass'=>array($category_id)));
							foreach ($sub_cat as $collection) {
							$subcategory_id = $collection['master_classified_subcategory']['subcategory_id'];
							$subcategory_name = $collection['master_classified_subcategory']["subcategory_name"];	
							?>
								<a href="#" role="button" data-dismiss="modal" aria-hidden="true" class="select_cat" value="<?php echo $category_id.','.$subcategory_id; ?>" cat_name="<?php echo $category_name.' <i class=icon-arrow-right></i> '.$subcategory_name; ?>"><?php echo $subcategory_name; ?></a><br/>
							<?php } ?>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
		
		
			
		<br/><br/>	
		<label class="control-label">Title</label>
		<div class="controls">
		 <input type="text" class="span10 m-wrap">
		</div>
						   
	</div>
	
	<div class="span6">
		
		<div class="control-group">
		  <label class="control-label">Image Upload</label>
		  <div class="controls">
			 <div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
				   <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
				</div>
				<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
				<div>
				   <span class="btn btn-file"><span class="fileupload-new">Select image</span>
				   <span class="fileupload-exists">Change</span>
				   <input type="file" class="default" /></span>
				   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				</div>
			 </div>
			 <span class="label label-important">NOTE!</span>
			 <span>
			 Attached image thumbnail is
			 supported in Latest Firefox, Chrome, Opera, 
			 Safari and Internet Explorer 10 only
			 </span>
		  </div>
	   </div>
		
	</div>
</div>

<button type="submit" class="btn blue form_post" name="publish" submit_type="publish">Publish Notice</button>
</form>
<!--FORM CONTENT END-->
</div>



<script>
$(document).ready(function() {
	$(".select_cat").bind('click',function(){
		var c=$(this).attr("value");
		var cn=$(this).attr("cat_name");
		$("#selected_name").html('<span class="label" style="background-color: #1BBC9B;">'+cn+'</span>');
		$('a[href="#myModal1"]').text("Select another category");
		
	});
	 });
</script>

<script>
$(document).ready(function() {
	$(".form_post").bind('click', function(e){
		$(".form_post").removeClass("clicked");
		$(this).addClass("clicked");
	});

			
	$('form').submit( function(ev){
	ev.preventDefault();
		if( $(this).find(".clicked").attr("submit_type") === "publish" ){
			var post_type=1;
		}
		if( $(this).find(".clicked").attr("submit_type") === "draft" ){
			var post_type=2;
		}
		var m_data = new FormData();    
		m_data.append( 'notice_subject', 1);
		
		m_data.append( 'post_type', post_type);
		
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "submit_ad",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType:'json',
			}).done(function(response) {
			if(response.type=='approve'){
				$(".portlet").remove();
				$(".alert-success").show().append("<p>"+response.text+"</p><p><a class='btn green' href='<?php echo $webroot_path; ?>Notices/new_notice' rel='tab' >ok</a></p>");
				$("#output").remove();
			}
			if(response.type=='created'){
				$(".portlet").remove();
				$(".alert-success").show().append("<p>"+response.text+"</p><p><a class='btn green' href='<?php echo $webroot_path; ?>Notices/notice_publish' rel='tab' >ok</a></p>");
				$("#output").remove();
			}
			if(response.type=='draft'){
				$(".portlet").remove();
				$(".alert-success").show().append("<p>"+response.text+"</p><p><a class='btn green' href='<?php echo $webroot_path; ?>Notices/notice_draft' rel='tab' >ok</a></p>");
				$("#output").remove();
			}
			if(response.type=='error'){
				$("#output").html('<div class="alert alert-error">'+response.text+'</div>');
			}
			$("html, body").animate({
			scrollTop:0
			},"slow");
			$(".form_post").removeClass("disabled");
			$("#wait").hide();
			});

	 
	});
});

</script>
