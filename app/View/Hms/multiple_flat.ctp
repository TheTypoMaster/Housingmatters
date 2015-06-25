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
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Multiple Wing-Flat
</div>

<div style="width: 70%;
margin-left: auto;" >

<select name="user_sel" class='sel_u chosen'>
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


<div id='record' style="width: 70%;
margin-left: 15%;"><span style="width: 70%;
margin-left: 22%;"><?php echo @$wrong; ?></span></div>
<div></div>

</form>
<script>
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
			
	      }
		 

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


