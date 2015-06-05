<div class="hide_at_print">
<center>
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_add" class="btn blue" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_view" class="btn red" rel='tab'>View</a>
</center>
</div>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
/*
$date_from = date('1-m-Y');
$date_to = date('d-m-Y');
?>
<center>
<div style="width:50%;">
<form method="post" id="contact-form">
<table>
<tbody><tr>
<td><input type="text" class="date-picker m-wrap medium" id="date1" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo $date_from; ?>"></td>
<td><input type="text" class="date-picker m-wrap medium" id="date2" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo $date_to; ?>"></td>
<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Search</button></td>
</tr>
</tbody></table>
</form>
</div>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<div id="result" style="width:100%;">
</div>
</center>
*/ ?>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$bbb = 55;
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['fix_asset']['auto_id'];		
$asset_category_id = (int)$collection['fix_asset']['asset_category_id'];
$asset_name = $collection['fix_asset']['asset_name'];
$narration = $collection['fix_asset']['narration'];
$purchase_date = $collection['fix_asset']['purchase_date'];
$purchase_cost = $collection['fix_asset']['purchase_cost'];
$supplier = (int)$collection['fix_asset']['supplier'];
$warranty_period_from = $collection['fix_asset']['warranty_period_from'];
$warranty_period_to = $collection['fix_asset']['warranty_period_to'];
$schedule = $collection['fix_asset']['schedule'];
if(!empty($warranty_period_from) and !empty($warranty_period_to)) 
{
$warranty_period_from= date('d-m-Y', $warranty_period_from->sec);
$warranty_period_to= date('d-m-Y', $warranty_period_to->sec);
}
else
{
$warranty_period_from = "";
$warranty_period_to = "";	
}
$asset_category_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($asset_category_id)));										
foreach ($asset_category_fetch as $collection) 
{
$asset_category = $collection['ledger_account']['ledger_name'];
}
$supply = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($supplier)));									
foreach ($supply as $collection) 
{
$supplier_name = $collection['ledger_sub_account']['name'];
}
$purchase_date = date('d-m-Y',$purchase_date->sec);	

$bbb = 555;
 
} 
?>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php if($bbb == 555)
{
?>
<br />
<div class="hide_at_print">
<span style="float:right;">
<a href="fix_asset_excel" class="btn blue" target="_blank">Export in Excel</a></span>
<span style="float:right; margin-right:1%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br /><br />

<table class="table table-bordered" style="width:100%; background-color:white;">
<tr>
<th style="text-align:center;" colspan="11"><?php echo $society_name; ?> Society (Fixed Assets)</th>
</tr>
</tr>
<tr>
<th>Sr.No.</th>
<th>Asset Category</th>
<th>Asset Name</th>
<th>Narration</th>
<th>Date of Purchase</th>
<th>Cost of Purchase</th>
<th>Supplier</th>
<th>Warranty Period From</th>
<th>Warranty Period From</th>
<th>Maintanance Schedule</th>
<th width="10%" class="hide_at_print">Action</th>
</tr>
<?php
$purchase_cost_tt = 0;
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['fix_asset']['auto_id'];
$receipt_id = $collection['fix_asset']['receipt_id'];		
$asset_category_id = (int)$collection['fix_asset']['asset_category_id'];
$asset_name = $collection['fix_asset']['asset_name'];
$narration = $collection['fix_asset']['narration'];
$purchase_date = $collection['fix_asset']['purchase_date'];
$purchase_cost = $collection['fix_asset']['purchase_cost'];
$supplier = (int)$collection['fix_asset']['supplier'];
$warranty_period_from = $collection['fix_asset']['warranty_period_from'];
$warranty_period_to = $collection['fix_asset']['warranty_period_to'];
$schedule = $collection['fix_asset']['schedule'];
if(!empty($warranty_period_from) and !empty($warranty_period_to)) 
{
$warranty_period_from= date('d-m-Y', $warranty_period_from->sec);
$warranty_period_to= date('d-m-Y', $warranty_period_to->sec);
}
else
{
$warranty_period_from = "";
$warranty_period_to = "";	
}
$asset_category_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($asset_category_id)));										
foreach ($asset_category_fetch as $collection) 
{
$asset_category = $collection['ledger_account']['ledger_name'];
}
$supply = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($supplier)));									
foreach ($supply as $collection) 
{
$supplier_name = $collection['ledger_sub_account']['name'];
}
$purchase_date = date('d-m-Y',$purchase_date->sec);
$purchase_cost_tt = $purchase_cost_tt+$purchase_cost;	
?>
<tr>
<td><?php echo $receipt_id; ?></td>	
<td><?php echo $asset_category; ?></td>	
<td><?php echo $asset_name; ?></td>	
<td><?php echo $narration; ?></td>	
<td><?php echo $purchase_date; ?></td>	
<td><?php echo $purchase_cost; ?></td>	
<td><?php echo $supplier_name; ?></td>	
<td><?php echo $warranty_period_from; ?></td>	
<td><?php echo $warranty_period_to; ?></td>	
<td><?php echo $schedule  ?></td>	
<td class="hide_at_print"><a href="#myModal<?php echo $auto_id; ?>" class="btn mini blue" data-toggle="modal">Current Rs.</a></td>		
</tr>
<?php } ?>
<tr>
<th style="text-align:right;" colspan="5">Total</th>
<th><?php echo $purchase_cost_tt; ?></th>
<th colspan="5"></th>
</tr>
</table>

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 

<?php foreach ($cursor2 as $collection) 
{
$auto_id = (int)$collection['fix_asset']['auto_id'];		    
$asset_category_id = (int)$collection['fix_asset']['asset_category_id']; 
$purchase_cost = $collection['fix_asset']['purchase_cost'];
$purchase_date = $collection['fix_asset']['purchase_date'];
$current_date = date("Y-m-d");
$date1 = date('Y-m-d',$purchase_date->sec);
$date1 = date(strtotime($date1));
$date2 = date(strtotime($current_date));
$difference = $date2 - $date1;
$months = floor($difference / 86400 / 30 );
                    
									
$asset_category_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($asset_category_id)));									
foreach ($asset_category_fetch2 as $collection) 
{
$rate = (int)$collection['ledger_account']['rate'];
}
$one_year_dep = round(($rate/100) * $purchase_cost);
$one_month_dep = round($one_year_dep/12);
$total_dep = round($one_month_dep * $months);
$current_rs = round($purchase_cost - $total_dep); 
?>
<!-------------------- Popup box ----------------->
<div id="myModal<?php echo $auto_id; ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="false">
<div class="modal-header">
<center>
<h3 id="myModalLabel2">Depriciation Rs.</h3>
</center>
</div>
<div class="modal-body">
<center>
<h4>Current Rs. : <?php echo $current_rs; ?></h4>
</center>	
</div>
<div class="modal-footer">
<button data-dismiss="modal" class="btn green">OK</button>
</div>
</div>
<?php } ?>                                
<!--------------------Popup-----------------------> 
<?php
}
if($bbb == 55)
{
?>
<br /><br />
<center>
<h3 style="color:red;"><b>No Record Found</b></h3>
</center>
<br /><br />
<?php 
}
?>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>   
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














    
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	