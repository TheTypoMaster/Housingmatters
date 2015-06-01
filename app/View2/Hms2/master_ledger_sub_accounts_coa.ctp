<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>


<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<h3><b>Master Ledger Sub Accounts</b></h3>
</center>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<a href="master_ledger_account_coa" class="btn yellow">Ledger Accounts</a>
<a href="master_ledger_sub_accounts_coa" class="btn purple">Ledger Sub Accounts</a>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
                         <form method="post" id="contact-form"> 
                         <table>
                         <tr>
                         <td>
                         <select class="medium m-wrap chosen" name="main_id" id="go">
                         <option value="">--SELECT CATEGORY--</option>
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
			            <input type="text" name="cat_name" placeholder="Name" class="m-wrap medium" style="background-color:white !important;" id="cat">
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
			   
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////

if(!empty($del_id))
{
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<form method="post">
<div class="modal-body" style="font-size:16px;">
Are you sure
<input type="hidden" value="<?php echo $del_id; ?>" name="del_id" />

</div> 
<div class="modal-footer">
<a href="master_ledger_sub_accounts_coa" class="btn">Cancel</a>
<button type="submit" name="delc" class="btn green">Delete</button>
</form>
</div>
</div>
<!----alert-------------->
<?php
}
////////////////////////////////////////////////////////////////////////////////////////////////////// ?>	












			   
			<br>

					<center>
					<div style="width:85%;">
					<table style="width:100%; background-color:#FDFDEE;" class="table table-bordered" >			
					<tr>
					<th>Sr.No.</th>
					<th>Account Category Name</th>
					<th>Accounts Group Name</th>
					<th>Ledger Name</th>
					<th>Ledger Sub Account Name</th>
                    <th>Delete</th>
					</tr>        
            
					<?php
					$n = 1;
					foreach ($cursor2 as $collection) 
					{
					$ledger_id = (int)$collection['ledger_sub_account']['ledger_id'];
					$name = $collection['ledger_sub_account']['name'];
                    $auto_id = (int)$collection['ledger_sub_account']['auto_id'];
  $result_la = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account'),array('pass'=>array($ledger_id)));
					foreach ($result_la as $collection) 
					{
					$group_id = (int)$collection['ledger_account']['group_id'];	
					$ledger_name = $collection['ledger_account']['ledger_name'];	
					}
					
					
					
					
					
					$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($group_id)));
					foreach ($result_ag as $collection) 
					{
					$accounts_id = (int)$collection['accounts_group']['accounts_id'];	
					$group_name = $collection['accounts_group']['group_name'];	
					}

					$result_ac = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_category'),array('pass'=>array($accounts_id)));		   
					foreach ($result_ac as $collection) 
					{
					$main_name = $collection['accounts_category']['category_name'];	
					} 
					?>        
					
					<tr>
					<td><?php echo $n; ?></td>
					<td><?php echo $main_name; ?></td>
					<td><?php echo $group_name; ?></td>
					<td><?php echo $ledger_name; ?></td>
					<td><?php echo $name;     ?> </td>
                    <td style="text-align:center;">
                    <a href="master_ledger_sub_accounts_coa?con=<?php echo $auto_id; ?>" class="btn mini black">Delete</a>
                    </td>
                    </tr>           
					<?php $n++; } ?>   
					</table>
					</div>         
					</center>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

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































