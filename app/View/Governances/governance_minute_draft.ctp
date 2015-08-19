<?php
$i=0;
//pr($result_gov_minute);
foreach($result_gov_minute as $data){

	$message_web=$data['governance_minute']['message'];

	$governance_minute_id=(int)$data['governance_minute']['governance_minute_id'];
	$meeting_id=(int)$data['governance_minute']['meeting_id'];
	$present_user=$data['governance_minute']['present_user'];
	$file=$data['governance_minute']['file'];
	$any_other=@$data['governance_minute']['any_other'];
	$result_gov_invite=$this->requestAction(array('controller' => 'governances', 'action' => 'governace_invite_meeting'), array('pass' => array($meeting_id)));
	
	foreach($result_gov_invite as $data1){
		$title=$data1['governance_invite']['subject'];
		$date=$data1['governance_invite']['date'];
		$time=$data1['governance_invite']['time'];
		$location=$data1['governance_invite']['location'];
		$notice_of_date=@$data1['governance_invite']['notice_of_date'];
		$meeting_type=(int)@$data1['governance_invite']['meeting_type'];
	}
}

?>

<div style="border:solid 2px #4cae4c; width:90%; margin:auto;" class='portal'>
<div style="border-bottom:solid 2px #4cae4c; color:white; background-color: #5cb85c; padding:4px; font-size:20px;" ><i class="icon-envelope-alt"></i> Minutes Draft</div>
<div style="padding:10px;background-color:#FFF;">
<form method="post" id="contact-form" name="myform" enctype="multipart/form-data" >
<div id="output"></div>

<!-------------------------->
<div class="row-fluid">

<div class="span6 responsive">
<label style="font-size:14px; font-weight:bold;">Meeting ID</label>
<div class="controls">
 <select name="meeting_id" id="meeting_id" class="chosen span12 change_ag">
 <option></option>
  <option  selected value="<?php echo $meeting_id ; ?>"> <?php echo $meeting_id ; ?> - <?php echo $title ; ?> - <?php echo $date ; ?></option>

 </select>
 <label report="subject" class="remove_report"></label>
</div>

</div>


</div>
<!-------------------------->



<label style="font-size:14px; font-weight:bold;">Select attendees present </label>

<!------------------------->
<div class="control-group" id="" >
  <div class="controls">
   
<select data-placeholder="Select attendees user"  name="multi" id="multi" class="chosen span9" multiple="multiple" tabindex="6">
<?php
foreach ($result_users as $collection) 
{
$user_id=$collection["user"]["user_id"];
$user_name=$collection["user"]["user_name"];
$email=$collection["user"]["email"];
$wing=$collection["user"]["wing"];
$flat=$collection["user"]["flat"];


$flat=$this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'), array('pass' => array($wing,$flat)));

?>
<option value="<?php echo $user_id; ?>" <?php if(in_array($user_id,$present_user)){ ?> selected <?php } ?> ><?php echo $user_name; ?>&nbsp;&nbsp;<?php echo $flat; ?></option>
<?php } ?>           
		  
	 </select>
	 
  </div>
  <label report="multi" class="remove_report"></label>
</div>

<!------------------------->
<div id="display_meeting">
<div class="row-fluid">
<label style="font-size:14px; font-weight:bold;"><span>Date </span> <span style="margin-left:50px;"> Time </span> <span style="margin-left:50px;"> Location </span></label> 
<span> <?php echo @$date ; ?> </span> <span style="margin-left:15px;"> <?php echo @$time ; ?> </span> <span style="margin-left:30px;"> <?php echo @$location ; ?> </span>
</div>
<br/>


<div class="row-fluid">
<table  width="100%" id="count_table" border="0">
<thead>
<tr>
<td width="60%"><b> Agenda </b></td><td> <b> Minutes </b></td></tr>

</thead>
<tbody>
 <?php
 $z=0;
		

		  foreach($message_web as $data){
			  $z++;
			  
			  $data[1];
			  ?>
			  <tr>
			  <td>
			 <b> <?php echo $z; ?> <?php echo urldecode($data[0]); ?> </b><br/> <?php echo urldecode($data[1]); ?>
			  </td>
			  <td>
			  <textarea name="min_<?php echo $z; ?>" class="span12" rows="4"><?php echo urldecode($data[3]); ?></textarea>
			  </td>
			 </tr> 
			 <?php 
		  }
			   
 ?>
 </tbody>
 </table>
 
		  
 


</div>

</div>

<!------------------------->
<br/>

<label style="font-size:14px; font-weight:bold;">Any Other business </label>
<div class="control-group">
	<div class="controls">
	 <textarea name="any_other" class="span12" rows="5" ><?php echo urldecode($any_other) ; ?></textarea>
	</div>
</div>

<!--<div class="control-group">
  <label class="control-label">Attachment <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Limit 2MB"> </i> </label>
  <div class="controls">
	 <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
		<div class="input-append">
		   <div class="uneditable-input">
			  <i class="icon-file fileupload-exists"></i> 
			  <span class="fileupload-preview"></span>
		   </div>
		   <span class="btn btn-file">
		   <span class="fileupload-new">Select file</span>
		   <span class="fileupload-exists">Change</span>
		   <input type="file" name="file" id="file" class="default" >
		   </span>
		   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
		</div>
	 </div>
  </div>
</div>
<label style="color: #696969;font-size: 12px;">
Note: File size must be less than 2 MB and All extension are allowed.
</label>
<label id="file"></label><br/>	-->			   


<input type="hidden" value="<?php echo $governance_minute_id; ?>" id="minute_id">
<button type="submit" name="send" class="btn blue" id=""><i class=" icon-envelope-alt "></i> Send</button>
</form>

</div>
</div>

<div class="alert alert-block alert-success fade in" style="display:none;">
	<h4 class="alert-heading">Success!</h4>
</div>

<script>
$(document).ready(function(){
$("#add_row").bind('click',function(){
	var count = $("#url_main div").length;
	count++;
	var agenda="Agenda";
	$("#hid_v").val(count);
	$("#url_main").append('<div class="content_'+count+'"><input type="text" class="m-wrap span4"  id="nu" name="comm_'+count+'" placeholder='+agenda+' style="height: 50px!important;"> <textarea class="span4" name="comment_'+count+'" placeholder="description" ></textarea> <a href="#" role="button" id='+count+' class="btn black mini delete_btn"><i class="icon-remove-sign"></i></a></div>');


});
$(".delete_btn").live("click",function(){
var id = $(this).attr("id");
$('.content_'+id).remove();
});
});
</script>

<script>
$(document).ready(function(){
$(".change_ag").change(function(){
	
var r=$(this).val();

//var meeting_id=$('select[name=meeting_id]').val();
$("#display_meeting").load("governance_minute_ajax?con="+r);
});	
	
});


</script>


<script>
$(document).ready(function(){
 	
  $("#r1").click(function(){
    $("#d2").hide();
    $("#d1").show();
	$("#d3").hide();
	 $("#d4").hide();
	 $("#d5").hide();
	$(".chosen").removeClass("ignore");
	$(".requirecheck2").addClass("ignore");
	$(".requirecheck1").addClass("ignore");
	$(".requirecheck3").addClass("ignore");
  });
  $("#r2").click(function(){
    $("#d1").hide();
    $("#d2").show();
	$("#d3").hide();
	 $("#d4").hide();
	 $("#d5").hide();$("#d6").hide();
	$(".chosen").addClass("ignore");
	$(".requirecheck2").addClass("ignore");
	$(".requirecheck1").addClass("ignore");
	$(".requirecheck3").removeClass("ignore");
  });
  $("#r3").click(function(){
    $("#d1").hide();
    $("#d3").show();
	$("#d2").hide();
	 $("#d4").hide();
	 $("#d5").hide();
	 $("#d6").hide();
	$(".chosen").addClass("ignore");
	$(".requirecheck2").addClass("ignore");
	$(".requirecheck1").addClass("ignore");
	$(".requirecheck3").addClass("ignore");
  });
  $("#r4").click(function(){
	  
	 value = +$('#r4').is( ':checked' );
	 alert(value);
	$("#d1").hide();
	$("#d3").hide();
	$("#d4").show();
	$("#d2").hide();
	$("#d5").hide();
	$("#d6").show();
	$(".chosen").removeClass("ignore");
	$(".requirecheck2").addClass("ignore");
	$(".requirecheck1").addClass("ignore");
	$(".requirecheck3").addClass("ignore");
  });
  
   $("#r5").click(function(){
	   
	  var r=$(this).val();
	$("#d1").hide();
	$("#d3").hide();
	$("#d4").hide();
	$("#d2").hide();
	$("#d5").show();
	$("#d6").show();
	$(".chosen").removeClass("ignore");
	$(".requirecheck2").addClass("ignore");
	$(".requirecheck1").addClass("ignore");
	$(".requirecheck3").addClass("ignore");
  });
   
  
});
</script>


<script>
$(document).ready(function() { 
	 $("#v3").live('click',function(){
		$("#show_3").slideDown('fast');
		$("#show_2").slideUp('fast');
		$("#show_1").slideUp('fast');
		$(".requirecheck2").removeClass("ignore");
		$(".requirecheck1").addClass("ignore");
	 });
	 
	 $("#v2").live('click',function(){
		$("#show_2").slideDown('fast');
		$("#show_3").slideUp('fast');
		$("#show_1").slideUp('fast');
		$(".requirecheck1").removeClass("ignore");
		$(".requirecheck2").addClass("ignore");
	 });
	 
	 $("#v1").live('click',function(){
		$("#show_1").slideDown('fast');
		$("#show_2").slideUp('fast');
		$("#show_3").slideUp('fast');
	 });
	
	 
	
	});
</script>

<script>
$(document).ready(function(){
$('form#contact-form').submit( function(ev){
	ev.preventDefault();	
	var m_data = new FormData(); 
	var present_user=$('select[name=multi]').val();
	var minute_id=$('input[id=minute_id]').val();
	
	var meeting_id=$('select[name=meeting_id]').val();
	m_data.append('minute_id',minute_id);
	m_data.append( 'present_user',present_user );
	m_data.append( 'meeting_id',meeting_id );
	//m_data.append( 'file', $('input[name=file]')[0].files[0]);
	var any_other=encodeURIComponent($('textarea[name=any_other]').val());
	m_data.append('any_other',any_other);
	
	var count1 = $("table#count_table tbody tr").length;
	
	var minute = [];
	for(var j=1;j<=count1;j++)
	{
		var min=encodeURIComponent($('textarea[name=min_'+j+']').val());
		minute.push([min]);
	}
	
	m_data.append('minute_agenda',minute);
	
	/*var Invitations =$('input:radio[name=radio]:checked').val();
	m_data.append( 'Invitations_type',Invitations );
	if(Invitations==1)
	{
		
		var invite=$('select[name=multi12]').val();
		m_data.append( 'Invite_user1',invite );
		
	}
	
	if(Invitations==2)
	{
		//var other=$('input[name=other_user]').val();
		var group_n = [];
		$('.group_name:checked').each(function() {
		group_n.push($(this).val());
		});
		
		//m_data.append( 'Invite_user2',other );
		m_data.append( 'Invite_group',group_n );
	}
	
	if(Invitations==3)
	{
		var visible=$('input:radio[name=visible]:checked').val();
		m_data.append( 'visible',visible );
	
		if(visible==2){
			var allVals = [];
			$('.v2:checked').each(function() {
			allVals.push($(this).val());
			});
			
			if(allVals.length==0){
				m_data.append( 'sub_visible', 0);
			}else{
				m_data.append( 'sub_visible', allVals);
			}
			
		}
		if(visible==3){
			var allVals = [];
			$('.v3:checked').each(function() {
			allVals.push($(this).val());
			});
			if(allVals.length==0){
				m_data.append( 'sub_visible', 0);
			}else{
				m_data.append( 'sub_visible', allVals);
			}
			
		}
		if(visible==1 || visible==4 || visible==5){
			m_data.append( 'sub_visible', 0);
		}
		
		
	} */
	
	$.ajax({
			url: "<?php echo $webroot_path; ?>Governances/governance_minute_drft_submit",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType:'json',
			}).done(function(response) { 
			
			//$("#output").html(response);
				if(response.type=='created'){
					$(".portal").remove();
				$(".alert-success").show().append("<p>"+response.text+"</p><p><a class='btn green' href='<?php echo $webroot_path; ?>Governances/minute_view' rel='tab' >ok</a></p>");
				$("#output").remove();
				}
				if(response.type=='error'){
				$("#output").html('<div class="alert alert-error">'+response.text+'</div>');
				
				}
				if(response.report_type=='error'){
				
					$(".remove_report").html('');
						jQuery.each(response.report, function(i, val) {
						$("label[report="+val.label+"]").html('<span style="color:red;">'+val.text+'</span>');
					});
				}
				$("html, body").animate({
				scrollTop:0
				},"slow");
				
				});
	
	
});


});
</script> 