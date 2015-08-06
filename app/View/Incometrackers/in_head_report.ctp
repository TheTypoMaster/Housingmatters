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
<a href="<?php echo $webroot_path; ?>Incometrackers/in_head_report" class="btn yellow" rel='tab'>Bill Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_regular" class="btn" rel='tab'>Regular Report</a>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_reports_supplimentry" class="btn" rel='tab'>Supplementary Report</a>
<!--<a href="<?php //echo $webroot_path; ?>Incometrackers/income_heads_report" class="btn" rel='tab'>Income head report</a>-->
<a href="<?php echo $webroot_path; ?>Incometrackers/account_statement" class="btn" rel='tab'>Account Statement</a>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
           <center>
           <div class="hide_at_print">
           <table border="0">
           <tr>
           <td>
           <select name="period" class="m-wrap large" id="un">
           <option value="" style="display:none;">Select</option>
           <?php
		   foreach($cursor1 as $collection)
		   {
		   $date_from = $collection['new_regular_bill']['bill_daterange_from'];   
		   $date_to = $collection['new_regular_bill']['bill_daterange_to'];
		   $unic_id = (int)$collection['new_regular_bill']['one_time_id'];
		   if($abc == $unic_id)
		   continue;
		   $abc = (int)$unic_id;
		   $from = date('d-m-Y',strtotime($date_from));
		   $to = date('d-m-Y',strtotime($date_to));
		   ?>
           <option value="<?php echo $unic_id; ?>"><?php echo $from; ?> to <?php echo $to; ?></option>
           <?php } ?>
           </select>
           </td>
           <td>
           <button class="btn yellow" id="go" style="margin-bottom:10px;">Go</button>
           </td>
           </tr>
           </table>
           <div id="validate_result"></div> 
           </div>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<br />
<div style="width:100%;" id="result">


</div>


<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$("#go").bind('click',function(){
	var unic = document.getElementById('un').value;
	
if(unic === '') { $('#validate_result').html('<div style="background-color:white; color:red; padding:5px;">Please Selectan Option</div>'); return false; }
else 
{
$('#validate_result').html('<div></div>');
}

$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loding....</div>').load("in_report_ajax?un=" +unic+ "");
});

});
</script>	
