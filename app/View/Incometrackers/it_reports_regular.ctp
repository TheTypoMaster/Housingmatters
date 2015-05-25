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
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		

<div style="text-align:center;" class="hide_at_print">
<a href="<?php echo $webroot_path; ?>Incometrackers/in_head_report" class="btn" rel='tab'>Bill Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_regular" class="btn yellow" rel='tab'>Regular Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_supplimentry" class="btn" rel='tab'>Supplementary Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/income_heads_report" class="btn" rel='tab'>Income head report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/account_statement" class="btn" rel='tab'>Account Statement</a>
</div>

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$c_date = date('d-m-Y');
$b_date = date('1-m-Y'); 
?> 
<center>
<div class="hide_at_print">
<form method="post" id="contact-form">
<label style="background-color:white;" id="v"></label>
<div id="validate_result"></div>
<table>
<tr>
<td colspan="2" style="text-align:center;">
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="wise" value="1" style="opacity: 0;" onclick="wing_wise()" class="wiseq" rad="1" id="v"></span></div>
Wing Wise
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="wise" value="2" style="opacity: 0;" onclick="member()" class="wiseq" rad="2" id="v"></span></div>
Member Wise
</label>
</td>
</tr>
<tr>
<td colspan="2" style="text-align:center;">
<div class="hide" id="one">
<select id="wing" class="m-wrap medium chosen">
<option value="">--SELECT WING--</option>
<?php
foreach($cursor2 as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];	
$wing_name = $collection['wing']['wing_name'];	
?>
<option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
<?php } ?>
</select>
</div>
<div class="hide" id="two">
<select id="mem" class="m-wrap medium">
<option value="">--SELECT MEMBER--</option>
<?php 
foreach($cursor3 as $collection)
{
$user_id = (int)$collection['user']['user_id'];	
$user_name = $collection['user']['user_name'];	
?>
<option value="<?php echo $user_id; ?>"><?php echo $user_name; ?></option>
<?php } ?>
</select>
</div>
</td>
</tr>

<tr>
<td><input type="text" id="date1" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo $b_date; ?>"></td>
<td><input type="text" id="date2" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo $c_date; ?>"></td>
<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
</tr>
</table>
</br>
</form>
</div>
</center>
 
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
<center>
<div id="result" style="width:94%;">
</div>
</center>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
				
				
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
$("#go").bind('click',function(){
var date1=document.getElementById('date1').value;
if(date1 === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Fill Date</div>'); return false; }

var date2=document.getElementById('date2').value;
if(date2 === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Fill Date</div>'); return false; }
var wise = $(".wiseq:checked").attr("rad");

if(wise === undefined) {
$('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Select Wing wise of Member wise</div>'); return false; }

if(wise == 1)
{
var wing = $("#wing").val();
if(wing === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Select Wing </div>'); return false; }
}
else if(wise == 2)
{
var user_id = $("#mem").val();
if(user_id === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Select Member </div>'); return false; }
}

$('#validate_result').html('<div></div>');

if(wise == 1)
{
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("regular_report_show_ajax?date1=" +date1+ "&date2=" +date2+ "&wise=" +wise+ "&wing=" +wing+ "");
}
else if(wise == 2)
{
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("regular_report_show_ajax?date1=" +date1+ "&date2=" +date2+ "&wise=" +wise+ "&user=" +user_id+ "");
}
});
});
</script>	
     	
<script>
function wing_wise()
{
$("#one").show();	
$("#two").hide();	
}
function member()
{
$("#one").hide();	
$("#two").show();	
}
</script>


	      
		
		
	












