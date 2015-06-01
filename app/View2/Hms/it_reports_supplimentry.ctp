<?php ///////////////////////////////////////////////////////////////////////////////////////// ?>		
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
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
<div style="text-align:center;" class="hide_at_print">
<a href="in_head_report" class="btn">bill Report</a>
<a href="it_reports_regular" class="btn ">Regular</a>
<a href="it_reports_supplimentry" class="btn yellow">Supplementary</a>
<a href="income_heads_report" class="btn ">Income head report</a>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
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
            <td>
            <select name="type" id="tp" class="m-wrap medium">
            <option value="" style="display:none;">Select</option>
            <option value="1">All</option>
            <option value="2">Residential</option>
            <option value="3">Non-residential</option>
            </select>
            </td>
            <td><input type="text" id="date1" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo $b_date; ?>"></td>
           
            <td><input type="text" id="date2" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo $c_date; ?>"></td>
            <td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
            </tr>
            </tbody></table>
            </br>
            </form>
            </div>
</center>
	
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
   <center>
   <div id="result" style="width:94%;">
   </div>
   </center>
    
    
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
    	<!--<div align="right">
		<a href="report_excel" class="btn " target="_new" style="margin-right:5%" > <img src="as/Download-icon.png"></a>
		</div>
		<div class="controls" style="padding-left:10px;">
		<label class="radio">Short By</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined">
		<span><input type="radio" onClick="show_record(1)" checked name="optionsRadios1" value="option1" style="opacity: 0;"></span>
		</div>All
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined">
		<span><input type="radio" onClick="show_record(2)" name="optionsRadios1" value="option1" style="opacity: 0;"></span>
		</div>Residential
		</label>
		<label class="radio">
		<div class="radio" id="uniform-undefined">
		<span><input type="radio" onClick="show_record(3)" name="optionsRadios1" value="option1" style="opacity: 0;"></span>
		</div>Non-residential
		</label>
		</div>  -->
		
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		

	<!--		    <div id="result">
			        <div style="padding:20px;">
           
		            <table class="table table-bordered table-striped table-hover">
					<thead>
					<tr>
					<th>Sr No.</th>
					<th style="width:5%;">Bill No</th>
					<th style="width:10%;">Generated on</th>
					<th>Generated For</th>
					<th>Flat</th>
					<th>Amount</th>
					<th>Status</th>
					<th>View</th>
					</tr>
					</thead>
					<tbody>
					

  <?php
									$grand_total = 0;
									$i=0;
									foreach ($cursor1 as $collection) 
									{
										$i++;
									$adhoc_bill= (int)$collection['adhoc_bill']["adhoc_bill_id"];
									$pay_status=$collection['adhoc_bill']["pay_status"];
									$date=$collection['adhoc_bill']["date"];
									$residential=$collection['adhoc_bill']["residential"];
									$g_total=$collection['adhoc_bill']["g_total"];
                                    $html_bill = $collection['adhoc_bill']['bill_html'];
									if($residential=="y")
									{
									$d_user_id=(int)$collection['adhoc_bill']["person_name"];
									
$result_user55 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($d_user_id)));
								foreach($result_user55 as $collection)
								{
								$d_user_id2 = (int)$collection['ledger_sub_account']['user_id'];	
								}
									
									$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($d_user_id2)));
									foreach ($result_user as $collection) 
									{
									$wing_id = (int)$collection['user']['wing'];  
									$flat_id = (int)$collection['user']['flat'];
									$user_name = $collection['user']['user_name'];
									}	
									$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));									
                                   $bill_for = $wing_flat;
								   }

									if($residential=="n")
									{
									$user_name=$collection['adhoc_bill']["person_name"];
									$bill_for="Non-residential";
									}
									$date = date('d-m-Y',$date->sec);
									
									$grand_total = $grand_total + $g_total;
									?>
									<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $adhoc_bill; ?></td>
									<td><?php echo $date; ?></td>
									<td class="hidden-phone"><?php echo $user_name; ?></td>
									<td style="color:#666;">(<?php echo $bill_for; ?>)</td>
									<td><?php echo $g_total; ?></td>
									
									<td>
									<?php if($pay_status==0){ ?> <span class="label label-important">Unpaid</span> <?php }
									else
									 { ?> <span class="label label-success">paid</span> <?php } ?>
									</td>
									<td><a href="supplimentry_bill_view?bill=<?php echo $adhoc_bill; ?>" class="btn mini yellow" target="_blank">View</a>
									<a href="supplimentry_bill_pdf?p=<?php echo $adhoc_bill; ?>" class="btn mini purple" target="_blank">Pdf</a>
									</td>
									</tr>
								
									<?php } ?>
                                    <tr>
                                    <th colspan="5">Total</th>
                                    <th><?php echo $grand_total; ?></th>
                                    <th colspan="2"></th>
                                    </tr>
									</tbody>
									</table>
									</div>
									</div>  -->

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		var tp=document.getElementById('tp').value; 
		
		if((tp=='')) { alert('Please Select Bill Type'); }
		if((date1=='')) { alert('Please Input Date-from'); }
		if((date2=='')) { alert('Please Input Date-to'); }
		else
		{
		$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("supplimentry_reports_show_ajax?date1=" +date1+ "&date2=" +date2+ "&tp=" +tp+ "");
		}
		
	});
	
});
</script>	








