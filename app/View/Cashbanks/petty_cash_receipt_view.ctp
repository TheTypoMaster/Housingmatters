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
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<?php
if($s_role_id == 3)
{
?>
<div class="hide_at_print">
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt" class="btn blue" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt_view" class="btn red" rel='tab'>View</a>
</div>
<?php } ?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
	   
<?php
$c_date = date('d-m-Y');
$b_date = date('1-m-Y');
?>       



		 <div class="hide_at_print">
            <form method="post" id="contact-form">
            <br>
            <table>
            <tbody><tr>
            
            <td><input type="text" class="date-picker m-wrap medium" id="date1" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:#FFF !important;" value="<?php echo $b_date; ?>"></td>
            
            <td><input type="text" class="date-picker m-wrap medium" id="date2" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:#FFF !important;" value="<?php echo $c_date; ?>"></td>
            <td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
            </tr>
            </tbody></table>
            <br>
            </form>
            </div>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
<center>
<div id="result" style="width:94%;">
</center>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
	$("#go").bind('click',function(){
		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		
		if((date1=='')) { alert('Please Input Date-from'); }
		if((date2=='')) { alert('Please Input Date-to'); }
		else
		{
		$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("petty_cash_receipt_show_ajax?date1=" +date1+ "&date2=" +date2+ "");
		}
		
	});
	
});
</script>				
			