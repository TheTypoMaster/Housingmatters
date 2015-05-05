<!--
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
-->
<div class="hide_at_print">
<center>
<h3><b>My Flat</b></h3>
</center>
</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$c_date = date('d-m-Y');
$b_date = date('1-m-Y');
?>
<center>
<div class="hide_at_print">
<form method="post" id="contact-form">
<div id="validate_result"></div>
<table>
<tbody>
<tr>
<td><input type="text" id="date1" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo $b_date; ?>"></td>
<td><input type="text" id="date2" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo $c_date; ?>"></td>
<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
</tr>
</tbody></table>
</br>
</form>
</div>
</center>
</div>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<div style="width:100%;" id="result">
</div>
</center>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>



<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
$("#go").bind('click',function(){
var date1=document.getElementById('date1').value;
var date2=document.getElementById('date2').value;
$('#validate_result').html('<div></div>');	
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("my_flat_bill_ajax?date1=" +date1+ "&date2=" +date2+ "");
});
});
</script>	






