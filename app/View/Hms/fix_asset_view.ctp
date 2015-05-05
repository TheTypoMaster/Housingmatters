 <center>
 <a href="<?php echo $webroot_path; ?>Hms/fix_asset_add" class="btn blue" rel='tab'>Add</a>
 <a href="<?php echo $webroot_path; ?>Hms/fix_asset_view" class="btn red" rel='tab'>View</a>
 </center>
 


<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<div style="width:50%;">
<form method="post" id="contact-form">
<br>
<table>
<tbody><tr>
<td><input type="text" class="date-picker m-wrap medium" id="date1" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;"></td>
<td><input type="text" class="date-picker m-wrap medium" id="date2" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;"></td>
<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Search</button></td>
</tr>
</tbody></table>
<br>
</form>
</div>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>

<center>
<div id="result" style="width:100%;">
</div>
</center>

<?php /////////////////////////////////////////////////////////////////////////////////////////////// ?>

    
  <script>
$(document).ready(function() {
	$("#go").bind('click',function(){

		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		
		if((date1=='')) { alert('Please Input Date-from'); }
		if((date2=='')) { alert('Please Input Date-to'); }
		else
		{
		$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("fix_asset_show_ajax?date1=" +date1+ "&date2=" +date2+ "");
		}
		
	});
	
});
</script>	














    
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	