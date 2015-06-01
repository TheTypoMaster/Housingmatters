<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
        <div class="hide_at_print">
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
		<a href="select_income_heads" class="btn blue btn-block"  style="font-size:16px;">Set-Up</a>
		</td>
		</tr>
		</table>
        </div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		

<div style="text-align:center;" class="hide_at_print">
<a href="in_head_report" class="btn">bill Report</a>
<a href="it_reports_regular" class="btn yellow">Regular</a>
<a href="it_reports_supplimentry" class="btn ">Supplementary</a>
<a href="income_heads_report" class="btn ">Income head report</a>
</div>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$c_date = date('d-m-Y');
$b_date = date('1-m-Y'); 
?> 
 
 
<center>
<div class="hide_at_print">
<form method="post" id="contact-form">
<br>
<table>
<tbody><tr>
<td><input type="text" id="date1" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo $b_date; ?>"></td>
<td><input type="text" id="date2" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo $c_date; ?>"></td>
<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
</tr>
</tbody></table>
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
 <!--
 
 
 
 
 <div style="width:100%;">
 <a href="regular_bill_excel" class="btn blue">Export in Excel</a>
 </div>    
     
     
     
     <div style="padding:20px;">
								
			<table class="table table-bordered ">
			<thead>
			<tr>
			<th>#</th>
			<th>Generated on</th>
			<th>Flat</th>
			<th>Member Name</th>
			<th>Period From</th>
			<th>Period To</th>
			<th>Amount</th>
			<th>Details</th>
			</tr>
			</thead>
			<tbody>
		
		<?php
				$grand_total = 0;
				$i=0; 
				foreach ($cursor1 as $collection) 
				{
				$i++;
				$one_time_id =(int)$collection['regular_bill']["one_time_id"];
				$regular_bill_id=(int)$collection['regular_bill']["regular_bill_id"];
				$bill_daterange_from=$collection['regular_bill']["bill_daterange_from"];
				
				$bill_daterange_from= date('d-m-Y', $bill_daterange_from->sec);
				
				$bill_daterange_to=$collection['regular_bill']["bill_daterange_to"];
				$bill_daterange_to= date('d-m-Y', $bill_daterange_to->sec);
				$bill_for_user=(int)$collection['regular_bill']["bill_for_user"];
				$bill_html=$collection['regular_bill']["bill_html"];
				$g_total=$collection['regular_bill']["g_total"];
				$date=$collection['regular_bill']["date"]; 
				$pay_status=(int)@$collection['regular_bill']["pay_status"];
				
				
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($bill_for_user)));				
foreach ($result as $collection) 
{
$user_name = $collection['user']['user_name'];
$wing_id = $collection['user']['wing'];  
$flat_id = (int)$collection['user']['flat'];
$tenant = (int)$collection['user']['tenant'];
}	
$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));									

$date = date('d-m-Y', $date->sec);						
$grand_total = $grand_total + $g_total;
									
									?>
									<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $date; ?></td>
									<td><?php echo $wing_flat; ?></td>
									<td><?php echo $user_name; ?></td>
									<td><?php echo $bill_daterange_from; ?></td>
									<td><?php echo $bill_daterange_to; ?></td>
									<td><?php echo $g_total; ?></td>
									<td><a href="regular_bill_view?bill=<?php echo $regular_bill_id; ?>" class="btn mini yellow" target="_blank">View</a>
									<a href="regular_bill_pdf?p=<?php echo $regular_bill_id; ?>" class="btn mini purple" target="_blank">Pdf</a>
									
									</td>			
				
				</tr>
										
										
									<?php } ?>
									<tr>
                                    <th colspan="6">Grand Total</th>
                                    <th><?php echo $grand_total; ?></th>
                                    <th></th>
                                    </tr>
                                    
                                    </tbody>
								</table>
							</div>
				
				
-->				
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
$("#go").live('click',function(){
var date1=document.getElementById('date1').value;
var date2=document.getElementById('date2').value;

if((date1=='')) { alert('Please Input Date-from'); }
if((date2=='')) { alert('Please Input Date-to'); }
else
{
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("regular_report_show_ajax?date1=" +date1+ "&date2=" +date2+ "");
}
});
});
</script>	
     	



















