<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
      <!--  <div class="hide_at_print">
		<table width="100%" border="1" bordercolor="#FFFFFF" cellpadding="0">
		<tr>
		<td style="width:25%">
		<a href="it_regular_bill" class="btn blue btn-block"   style="font-size:16px;"> Regular Bill</a>
		</td>
		<td style="width:25%">
		<a href="it_supplimentry_bill" class="btn blue btn-block"  style="font-size:16px;">Supplementary Bill</a>
		</td>
		<td style="width:25%">
		<a href="in_head_report" class="btn red btn-block"  style="font-size:16px;">Reports</a>
		</td>
		<td style="width:25%">
		<a href="select_income_heads" class="btn blue btn-block"  style="font-size:16px;">Accounting Setup</a>
		</td>
		</tr>
		</table>
        </div> -->
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		

<div style="text-align:center;" class="hide_at_print">
<a href="<?php echo $this->webroot; ?>Incometrackers/in_head_report" class="btn yellow" rel='tab'>bill Report</a>
<a href="<?php echo $this->webroot; ?>Incometrackers/it_reports_regular" class="btn" rel='tab'>Regular</a>
<a href="<?php echo $this->webroot; ?>Incometrackers/it_reports_supplimentry" class="btn" rel='tab'>Supplementary</a>
<a href="<?php echo $this->webroot; ?>Incometrackers/income_heads_report" class="btn" rel='tab'>Income head report</a>
</div>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>

          <br />
           <div class="hide_at_print">
           <table border="0">
           <tr>
           <td>
           <select name="period" class="m-wrap medium" id="un">
           <option value="" style="display:none;">Select</option>
           <?php
		   foreach($cursor1 as $collection)
		   {
		   $date_from = $collection['regular_bill']['bill_daterange_from'];   
		   $date_to = $collection['regular_bill']['bill_daterange_to'];
		   $unic_id = (int)$collection['regular_bill']['one_time_id'];
		   if($abc == $unic_id)
		   continue;
		   $abc = $unic_id;
		   $from = date('d-m-Y',$date_from->sec);
		   $to = date('d-m-Y',$date_to->sec);
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
           </div>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<br />
<div style="width:100%;" id="result">


</div>


<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		var unic = document.getElementById('un').value;
		
		
		
	
		
		$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loding....</div>').load("in_report_ajax?un=" +unic+ "");
		
		
	});
	
});
</script>	



