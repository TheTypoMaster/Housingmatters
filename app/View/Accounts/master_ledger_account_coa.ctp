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
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_account_coa" class="btn purple" rel='tab'>Ledger Accounts</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_accounts_coa" class="btn yellow" rel='tab'>Ledger Sub Accounts</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_accounts_view" class="btn yellow">Master Ledger  Account View</a>
<a href="<?php echo $webroot_path; ?>Accounts/master_ledger_sub_account_view" class="btn yellow">Master Ledger Sub Account View</a>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center><h3><b>Master Ledger Accounts</b></h3></center>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<form method="post" id="contact-form"> 
<table>
<tr>
<td>
<select class="medium m-wrap chosen" name="main_id" id="go">
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
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			   
<br>
<center>
<div class="portlet box grey" style="width:90%;">
<div style="background-color:#B2B2B2; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
<b style="color:white;">  Ledger Accounts </b>
</div>
<div class="portlet-body">
<div style="width:100%;">
					<table style="width:100%; background-color:white;" class="table table-bordered" id="sample_2">
                    <thead>			
					<tr>
					<th>Sr.No.</th>
					<th>Account Category</th>
					<th>Accounts Group</th>
					<th>Ledger</th>
                    <th>Edit/Delete</th>
					</tr>        
                    </thead>
			<tbody>
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
<a href="#myModal5<?php echo $auto_id5; ?>" role="button" class="btn mini purple" data-toggle="modal">Edit</a>
            <?php  } ?>
            </td>
			</tr>
            <?php $n++; } ?> 
             </tbody>  
			</table>
			</div>  
            </div> 
            </div>      
			</center>
			
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
<form method="post">
<?php

foreach ($cursor2 as $collection) 
{
$auto_id2 = (int)$collection['ledger_account']['auto_id'];
$sub_id2 = (int)$collection['ledger_account']['group_id'];
$name2 = $collection['ledger_account']['ledger_name'];

$result_ag = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($sub_id2)));
foreach ($result_ag as $collection) 
{
$group_id = (int)$collection['accounts_group']['auto_id'];	
} 
?>
<div id="myModal5<?php echo $auto_id2; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display:block;" >
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Ledger Accounts</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<table border="0">
<tr>
<td>
<select name="gr_id" class="m-wrap medium">
<?php
foreach($cursor3 as $collection)
{
$group_id2 = (int)$collection['accounts_group']['auto_id'];	
$group_name2 = $collection['accounts_group']['group_name'];	
?>
<option value="<?php echo $group_id2; ?>" <?php if($group_id2 == $group_id) { ?> selected="selected" <?php } ?> ><?php echo $group_name2; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<input type="text" style="margin-top:10px; background-color:white !important;" class="m-wrap medium" value="<?php echo $name2; ?>" name="cat" >
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
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>   

<?php /*

<form method="post">
<?php
foreach ($cursor2 as $collection) 
{
$auto_id3 = (int)$collection['ledger_account']['auto_id'];
$sub_id3 = (int)$collection['ledger_account']['group_id'];
$name3 = $collection['ledger_account']['ledger_name'];


?>
<div id="myModal2<?php echo $auto_id3; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display:block;" >
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Ledger Accounts</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h3><b>Are You Sure</b></h3>
</center>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
<button type="submit" class="btn blue" name="sub2<?php echo $auto_id3; ?>">Confirm</button>
</div>
</div>    
<?php } ?>
</form>
*/ ?>

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
			   
			   
			   
			   
		   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   
			   