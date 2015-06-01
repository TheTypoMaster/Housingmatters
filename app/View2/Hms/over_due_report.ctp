<center>
<h3 class="hide_at_print"><b>Over Due Report</b></h3>
</center>
<br>
<center>
<div id="validate_result"></div>
<div class="hide_at_print">
<table border="0">
<tr>
<td>
<select name="flat" id="flat" class="m-wrap medium chosen">
<option value="" style="display:none;">Select Flat</option>
<?php
foreach($cursor1 as $collection)
{
$flat_id = (int)$collection['user']['flat'];

$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch'),array('pass'=>array($flat_id)));
foreach($result1 as $collection)
{
$flat_name = $collection['flat']['flat_name'];	
}
//$flat_name = $collection['user']['flat_name'];
?>
<option value="<?php echo $flat_id; ?>"><?php echo $flat_name; ?></option>
<?php
}
?>
</select>
</td>
<td>
<input type="text" placeholder="From Date" id="date1" style="margin-top:8px; background-color:white !important;" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="from">
</td>
<td> 
<input type="text" placeholder="To Date" id="date2" style="margin-top:8px; background-color:white !important;" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="to">
</td>

<td><button class="btn yellow" id="go" style="margin-bottom:2px;">Go</button></td>

</tr>
</table>
</div>
</center>







<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<div id="result" style="width:96%;"></div>
</center>
<!--<div style="width:98%;" class="hide_at_print">
<span style="margin-left:90%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br>
<center>
<table class="table table-bordered" style="width:90%; background-color:white;">
<tr>
<th colspan="8" style="text-align:center;"><p style="font-size:18px;"><?php //echo $so_name; ?></p></th>
</tr>
<tr>
<th colspan="8"></th>
</tr>
<tr>
<th style="text-align:center;">#</th>
<th style="text-align:center;">Bill No</th>
<th style="text-align:center;">Owner Name</th>
<th style="text-align:center;">Total Amount</th>
<th style="text-align:center;">Due Amount</th>
<th style="text-align:center;">Pay Amount</th>
<th style="text-align:center;">Bill Amount</th>
<th style="text-align:center;">Bill View</th>
</tr>









<!--
<tr>
<td style="text-align:center;"><?php //echo $nnn; ?></td>
<td style="text-align:center;"><?php //echo $bill_no; ?></td>
<td style="text-align:center;"><?php //echo $user_name; ?>&nbsp;&nbsp;&nbsp;&nbsp; <?php //echo $wing_flat; ?></td>
<td style="text-align:center;"><b>From:</b><?php //echo $from; ?>&nbsp;&nbsp;<b>To:</b><?php //echo $to; ?></td>
<td style="text-align:center;"><?php //echo $last_date; ?></td>
<td style="text-align:center;"><?php //echo $grand_total_amt; ?></td>
<td style="text-align:center;"><?php //echo $due_amount; ?></td>

</tr>



</table>
</center>-->
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		var flat = document.getElementById('flat').value;
		
		if(flat=== '') { $('#validate_result').html('<div style="background-color:#f2dede; color:#b94a48; padding:5px;"><b>Please Select Flat</b></div>'); return false; }
			
		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		if((date1=='')) { 
		$('#validate_result').html('<div style="background-color:#f2dede; color:#b94a48; padding:5px;"><b>Please Select From Date</b></div>'); return false;
		}
		if((date2=='')) {
		$('#validate_result').html('<div style="background-color:#f2dede; color:#b94a48; padding:5px;"><b>Please Select To Date</b></div>'); return false; 
		}
		
		
		$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("over_due_report_show_ajax?date1=" +date1+ "&date2=" +date2+ "&flat=" +flat+ "");
		
		
	});
	
});
</script>		

















