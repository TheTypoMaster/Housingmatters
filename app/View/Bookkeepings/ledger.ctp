<div class="hide_at_print">	

<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
</div>

		
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
<?php  
$default_date_from = date('1-m-Y'); 
$default_date_to = date('d-m-Y')
?> 
<center>
<form method="post" onSubmit="return valid()">

<div  class="hide_at_print">
             
<div id="div1">             
<table style="width:60%;">
<tr>
<td>
<select class="medium m-wrap chosen" tabindex="1" name="type" id="main_id">
<option value="" style="display:none;">Select Ledger A/c</option>
<?php
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$name = $collection['ledger_account']['ledger_name'];
?>
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>
<?php } ?>
</select>
</td>
<td id="result1">
<select class="medium m-wrap" tabindex="1" name="user_name" id="sub_id" style="margin-top:7px;">
<option value="0">Select Sub Ledger A/c</option>
</select>
</td>

<td>
<input type="text" placeholder="From Date" id="date1" class="date-picker medium m-wrap" data-date-format="dd-mm-yyyy" name="from" style="background-color:white !important; margin-top:7px;" value="<?php echo $default_date_from; ?>">
</td>

<td>
<input type="text" placeholder="To Date" id="date2" class="date-picker medium m-wrap" data-date-format="dd-mm-yyyy" name="to" style="background-color:white !important; margin-top:7px;" value="<?php echo $default_date_to; ?>">
</td>
<td valign="top">
<button type="button" id="go" name="sub" class="btn yellow" style="margin-top:7px;">Search</button>
</td>
</tr>
</table>
</div>
<?php /////////////////////////////////////////////////// ?>


<div id="flat1" class="hide">
<table style="width:60%;">
<tr>
<td>
<select class="m-wrap medium chosen" id="flat_id">
<option value="" style="display:none;">Select Flat</option>
<?php
foreach ($cursor2 as $collection) 
{
$auto_id = (int)$collection['flat']['flat_id'];
$name = $collection['flat']['flat_name'];
?>
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>
<?php } ?>
</select>

</td>
<td>
<input type="text" placeholder="From Date" id="date1" class="date-picker medium m-wrap" data-date-format="dd-mm-yyyy" name="from" style="background-color:white !important; margin-top:7px;" value="<?php echo $default_date_from; ?>">
</td>

<td>
<input type="text" placeholder="To Date" id="date2" class="date-picker medium m-wrap" data-date-format="dd-mm-yyyy" name="to" style="background-color:white !important; margin-top:7px;" value="<?php echo $default_date_to; ?>">
</td>
<td valign="top">
<button type="button" id="go2" name="sub" class="btn yellow" style="margin-top:7px;">Search</button>
</td>
</tr>
</table>
</div>
<div id="validate_result"></div>
<?php ///////////////////////////////////////////////////// ?>
<br>
</div>	 
</form>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////?>



<?php //////////////////////////////////////////////////////////////////////////////////?>		
<div id="result" style="width:100%;">
</div>
		
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 		
		

    	
		
<script>
$(document).ready(function() {
	$("#main_id").bind('change',function(){
		var c1 = document.getElementById('main_id').value;
		$("#result1").html('loading...');
		$("#result1").load("ledger_ajax?c1=" +c1+ "");
	});
	
});
</script>			
		
<script>
$(document).ready(function() {
	$("#go").bind('click',function(){
		
		var main_id = document.getElementById('main_id').value;
		if(main_id==15 ||  main_id==33 || main_id==34 ||  main_id==35){
			var sub_id = document.getElementById('sub_id').value;
		}else{
			var sub_id =null;
		}
		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		$("#result").html('<div align="center" style="padding:10px;"><img src="<?php echo $webroot_path; ?>as/loding.gif" />Loading....</div>').load("ledger_show_ajax?date1=" +date1+ "&date2=" +date2+ "&main_id=" +main_id+ "&sub_id=" +sub_id+ "&type=1");
	});


	
});
</script>			
