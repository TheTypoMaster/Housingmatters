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
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" class="btn blue" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_view" class="btn red" rel='tab'>Active Deposits</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/matured_deposit_view" class="btn blue" rel='tab'>Matured Deposits</a>
</center>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$b_date = date('1-m-Y');
$c_date = date('d-m-Y');

?>
<center>
<br />
<table border='0'>
<tr>
<td><input type="text" name="from" id="date1" class="m-wrap medium date-picker" data-date-format="dd-mm-yyyy" placeholder="From" style="background-color:white !important;" value="<?php echo $b_date; ?>"/></td>
<td><input type="text" name="to" id="date2" class="m-wrap medium date-picker" data-date-format="dd-mm-yyyy" placeholder="To" style="background-color:white !important;" value="<?php echo $c_date; ?>"/></td>
<td><button class="btn yellow" id="go" style="margin-bottom:10px;">Go</button></td>
</tr>
</table>
</center>

<div id="result" style="background-color:white; overflow-x:scroll; width:100%;"> </div>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<script>
$(document).ready(function() {
$("#go").bind('click',function(){
var date1=document.getElementById('date1').value;
var date2=document.getElementById('date2').value;
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("fixed_diposit_show_ajax?date1=" +date1+ "&date2=" +date2+ "");
});
});
</script>