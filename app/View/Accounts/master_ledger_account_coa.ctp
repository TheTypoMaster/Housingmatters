<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<center>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_account_coa" class="btn yellow" rel='tab'>Ledger Accounts Add</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_accounts_coa" class="btn" rel='tab'>Ledger Sub Accounts Add</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_accounts_view" class="btn">Master Ledger  Account View</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_account_view" class="btn">Master Ledger Sub Account View</a>
</center>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<br />
<center>
<form method="post" id="contact-form"> 
<table>
<tr>
<td>
<select class="large m-wrap chosen" name="main_id" id="go">
<option value="" style="display:none;">Select Group Account</option>
<?php
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['accounts_groups']['auto_id'];
$name = $collection['accounts_groups']['group_name']; 
?>
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>
<?php } ?>
</select>
<label id="go"></label>
</td>
</tr>
<tr>
<td>
<input type="text" name="cat_name" placeholder="Name" class="m-wrap large" style="background-color:white !important;" id="cat">
<label id="cat"></label>
</td>
</tr>
        
<tr>
<td id="result">
<label id="rate"></label><label id="amt"></label>
</td>
</tr>
                  
<tr>
<td>
<button type="submit" name="sub" class="btn blue">Add</button>
</td>
</tr>
</table>
</form>
</center>

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$("#go").live('change',function(){
		var value = document.getElementById('go').value;
		
		$("#result").load("master_ledger_account_ajax?value=" +value+ "");
		
		
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
	      main_id: {
	       
	        required: true
	      },
		  
		  
		  cat_name: {
	       
	        required: true
	      },
		  
		   rate: {
	       
	        required: true
	      },
		  
		  
		  
		   amount: {
	       
	        required: true
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
			   
			   
			   
			   
		   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   