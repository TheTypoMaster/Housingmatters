<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>



<center><h3><b>Master Ledger Accounts</b></h3></center>
<br>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<a href="master_ledger_account_coa" class="btn purple">Ledger Accounts</a>
<a href="master_ledger_sub_accounts_coa" class="btn yellow">Ledger Sub Accounts</a>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
 <center>
               <form method="post" id="contact-form"> 
                         <table>
                         <tr>
                         <td>
                         <select class="medium m-wrap" name="main_id" id="go">
                         <option value="">--SELECT CATEGORY--</option>
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
			            <input type="text" name="cat_name" placeholder="Name" class="m-wrap medium" style="background-color:white !important;" id="cat">
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
<a href="master_ledger_account_coa" class="btn">Cancel</a>
<button type="submit" name="delc" class="btn green">Delete</button>
</form>
</div>
</div>
<!----alert-------------->

<?php
}
////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			   
			<br>
			<form method="post">
					<center>
					<div style="width:85%;">
					<table style="width:100%; background-color:white;" class="table table-bordered" >			
					<tr>
					<th>Sr.No.</th>
					<th>Account Category Name</th>
					<th>Accounts Group Name</th>
					<th>Ledger Name</th>
                    <th>Edit/Delete</th>
					</tr>        
            
			
			<?php
            $n = 1;
			foreach ($cursor2 as $collection) 
			{
            $sub_id = (int)$collection['ledger_account']['group_id'];
			$name = $collection['ledger_account']['ledger_name'];
			$auto_id5 = (int)$collection['ledger_account']['auto_id'];
			$edit_id = (int)$collection['ledger_account']['edit_user_id'];
$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($sub_id)));
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
			<td><?php echo $name; ?></td>
            <td> 
             <?php if($edit_id == $s_user_id)
			 {
             ?>
             <a href="#collapse<?php echo $auto_id5; ?>" class="btn mini purple accordion-toggle     collapsed" data-toggle="collapse" data-parent="#accordion1">Edit</a>
<a href="master_ledger_account_coa?con=<?php echo $auto_id5; ?>" class="btn mini black">Delete</a>
            <?php  } ?>
            </td>
			</tr>
            <tr>
            <td colspan="5" style="margin:0px; padding:0px; text-align:center;" >
             <div id="collapse<?php echo $auto_id5; ?>" class="accordion-body collapse" style="height: 0px;">
    <input type="text" style="margin-top:10px; background-color:white !important;" class="m-wrap medium" value="<?php echo $name; ?>" name="cat<?php echo $auto_id5; ?>" >
    <button type="submit" class="btn yellow" style="margin-top:10px;" name="sub<?php echo $auto_id5; ?>">Update</button>
    </div>
            </td>
            </tr>  
            <?php $n++; } ?>   
			</table>
			</div>         
			</center>
			</form>  
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			   
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
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   