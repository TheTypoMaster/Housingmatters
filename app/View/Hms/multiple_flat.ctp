<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));

?>
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>



<form method="post" id='contact-form'>
<div style="background-color:#FFF; width:100%; border:solid #999; overflow:auto; height:700px;">
<p style="color:#00F; font-weight:500; font-size:16px; border-bottom:solid 1px #DAD9D9; padding:10px;">Assign Multiple Flat</p>
<br />
<div style="width:48%; float:left;">

<label style="font-size:14px; margin-left:3%;">Select Resident<span style="color:#F00;">*</span></label>
<div class="controls" style="margin-left:3%;">
<select name="user_sel" class='m-wrap span9 sel_u chosen' onchange="userr_fffnctt(this.value)">
<option> Select User </option>
<?php 
foreach($result_user as $data)
{
$user_id=$data['user']['user_id'];
$user_name=$data['user']['user_name'];
$wing=$data['user']['wing'];
$flat=$data['user']['flat'];
?>
<option value='<?php echo $user_id ; ?>'><?php echo $user_name ; ?></option>
<?php } ?>
</select>
</div>
<br />

<label style="font-size:14px; margin-left:3%;">Select Resident<span style="color:#F00;">*</span></label>
<div class="controls" style="margin-left:3%;">
<select class="m-wrap span9 chosen winggg" name="wing" id="wing_validation" onchange="wing_functtt(this.value)">
<option value="" style="display:none;">Select Wing</option>
<?php
foreach($wing_data as $data)
{
$wing_id = (int)$data['wing']['wing_id'];
$wing_name = $data['wing']['wing_name'];
?>
<option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
<?php } ?>
</select>
<label id="wing_validation"></label>
<br />
</div>

<div id='record' style="margin-left:3%;">

</div>



</div>
<br />
<div style="width:50%; float:right;">

<div id="user_fl_detail">
</div>




</div>
</div>

<!-- --------------------------------------- -->

</form>
<script>
function wing_functtt(wingg)
{
$('#record').load('multiple_flat_ajax?wngg=' + wingg);
}

function userr_fffnctt(usridd)
{
$('#user_fl_detail').load('multiple_flat_ajax?wngg=' + usridd + '&vv=' +55+ '');
}

$( document ).ready(function() {
$(".sel_u").live('change',function(){
var u=$(this).val();
$('#record').load('multiple_flat_ajax?con=' + u);


});
});


$( document ).ready(function() {
$(".sel_wing").live('change',function(){
var xx=$(this).val();
var z = encodeURIComponent(xx);
$('#sel_flat11').load('multiple_flat_ajax1?vb=' + z);
});
});
</script>




<script>

$(document).ready(function(){

$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$('#contact-form').validate({
			
		
		errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    },
		
		
		
	    rules: {
	     
		 sel_wing_id: {
			 required: true
			
			 
	      },
		   sel_flat_id: {
			 required: true,
			
	      },
		  	noc_charg: {
			required: true,

			},


           		},
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });

}); 
</script>


