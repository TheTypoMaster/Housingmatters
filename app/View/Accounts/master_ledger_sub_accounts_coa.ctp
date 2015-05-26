<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_account_coa" class="btn" rel='tab'>Ledger Accounts Add</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_accounts_coa" class="btn yellow" rel='tab'>Ledger Sub Accounts Add</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_accounts_view" class="btn">Master Ledger  Account View</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_account_view" class="btn">Master Ledger Sub Account View</a>
</center>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
<br />
<center>
<form method="post" id="contact-form"> 
<table>
<tr>
<td>
<select class="large m-wrap chosen" name="main_id" id="go">
<option value="" style="display:none;">Select Ledger Account</option>
<?php
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$name = $collection['ledger_account']['ledger_name']; 
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
			            <label id="ui"></label><label id="si"></label><label id="ba"></label><label id="tx"></label>
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
			   
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php /*
<form method="post">
<?php
foreach ($cursor2 as $collection) 
{
$auto_id2 = (int)$collection['ledger_sub_account']['auto_id'];
$name2 = $collection['ledger_sub_account']['name'];
$ledger_id2 = (int)$collection['ledger_sub_account']['ledger_id'];


?>
<div id="myModal<?php echo $auto_id2; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display: block;">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Ledger Sub Accounts</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<table border="0">
<tr>
<td>
<select name="gr" class="m-wrap medium">
<?php
foreach($cursor1 as $collection3)
{
$led_id = (int)$collection3['ledger_account']['auto_id'];
$ledger_name = $collection3['ledger_account']['ledger_name'];	
?>
<option value="<?php echo $led_id; ?>" <?php if($led_id == $ledger_id2) { ?> selected="selected" <?php } ?>><?php echo $ledger_name; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<input type="text" value="<?php echo $name2; ?>" name="name"  class="m-wrap medium"/>
</td>
</tr>
</table>
</center>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
<button type="submit" class="btn blue" name="sub<?php echo $auto_id2; ?>">Update</button>
</div>
</div>                        
<?php } ?>                    
</form>  
*/ ?>
               
<?php ///////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
	$("#go").live('change',function(){
		var value=document.getElementById('go').value;
		
		
		$("#result").load("master_ledger_sub_account_ajax?value=" +value+ "");
		
		
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
		  
		
		 user_id: {
	       
	        required: true
	      },
		  
		   sp_id: {
	       
	        required: true
	      },
		
		 bank_account: {
	       
	        required: true
	      },
		 
		  tax: {
	       
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































