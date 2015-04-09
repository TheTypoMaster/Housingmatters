<center><h3><b>Master Ledger Accounts</b></h3></center>
<br>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<div style="width:100%;">
<a href="master_accounts_category_hm" class="btn yellow">Accounts Category</a>
<a href="master_accounts_group_hm" class="btn yellow">Accounts Group</a>
<a href="master_ledger_account_hm" class="btn purple">Ledger Account</a>
<a href="ledger_account_excel_hm" class="btn blue" style="float:right;">Export in Excel</a>
</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
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
                         $auto_id = (int)$collection['accounts_group']['auto_id'];
                         $name = $collection['accounts_group']['group_name']; 
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
<a href="master_ledger_account_hm" class="btn">Cancel</a>
<button type="submit" name="delc" class="btn green">Delete</button>
</form>
</div>
</div>
<!----alert-------------->
<?php
}
////////////////////////////////////////////////////////////////////////////////////////////// ?>
<br>
			
            
                   
<center>
<div class="portlet box grey" style="width:90%;">
<div style="background-color:#B2B2B2; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
<b style="color:white;">  Ledger Accounts </b>
</div>

<div class="portlet-body">
					<table style="width:100%; background-color:white;" class="table table-bordered" id="sample_2">			
					<thead>
                    <tr>
					<th style="text-align:left;">Sr.No.</th>
					<th style="text-align:left;">Accounts Category</th>
					<th style="text-align:left;">Accounts Group</th>
					<th style="text-align:left;">Ledger Accounts</th>
                    <th style="text-align:left;">Edit / Delete</th>
					</tr> 
                    </thead>
                    <tbody>  
            <?php
            $n = 1;
			foreach ($cursor2 as $collection) 
			{
			$auto_id5 = (int)$collection['ledger_account']['auto_id'];
            $sub_id = (int)$collection['ledger_account']['group_id'];
			$name = $collection['ledger_account']['ledger_name'];
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
			<td style="text-align:left;"><?php echo $n; ?></td>
			<td style="text-align:left;"><?php echo $main_name; ?></td>
			<td style="text-align:left;"><?php echo $group_name; ?></td>
			<td style="text-align:left;"><?php echo $name; ?></td>
            <td style="text-align:left;">
           <?php if($edit_id == $s_user_id)
		   {
		   ?>
           
           <!-- <a href="#collapse<?php echo $auto_id5; ?>" class="btn mini purple accordion-toggle     collapsed" data-toggle="collapse" data-parent="#accordion1">Edit</a> -->
            <a href="#myModal<?php echo $auto_id5; ?>" role="button" class="btn mini purple" data-toggle="modal">Edit</a>
           <!--<a href="master_ledger_account_hm?con=<?php echo $auto_id5; ?>" class="btn mini black">Delete</a>-->
         <?php } ?> 
            </td>
			</tr> 
            <!-- <tr>
            <td colspan="5" style="margin:0px; padding:0px; text-align:center;" >
             <div id="collapse<?php echo $auto_id5; ?>" class="accordion-body collapse" style="height: 0px;">
    <input type="text" style="margin-top:10px; background-color:white !important;" class="m-wrap medium" value="<?php echo $name; ?>" name="cat<?php echo $auto_id5; ?>" >
    <button type="submit" class="btn yellow" style="margin-top:10px;" name="sub<?php echo $auto_id5; ?>">Update</button>
    </div>
            </td>
            </tr> -->  
            <?php $n++; } ?>
            </tbody>   
			</table>
			</div>
                     
			</center>
   
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
//$group_name = $collection['accounts_group']['group_name'];	
}

?>            
<div id="myModal<?php echo $auto_id2; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display: block;">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
<center>
<h3 id="myModalLabel3">Ledger Accounts</h3>
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
<option value="<?php echo $group_id2; ?>" <?php if($group_id2 == $group_id) { ?> selected="selected" <?php } ?>><?php echo $group_name2; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<input type="text" style="margin-top:10px; background-color:white !important;" class="m-wrap medium" value="<?php echo $name2; ?>" name="cat<?php echo $auto_id2; ?>" >
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
<?php
}
?>            
            
            
            
            
            
            
            
            
            
            
            
            
            
			</form>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			

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



















