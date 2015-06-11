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

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" class="btn" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_view" class="btn yellow" rel='tab'>Active Deposits</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/matured_deposit_view" class="btn" rel='tab'>Matured Deposits</a>
</center>
</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$nnn = 55;
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['fix_deposit']['auto_id'];	
$bank_name = $collection['fix_deposit']['bank_name'];	
$branch = $collection['fix_deposit']['branch'];	
$account_ref = $collection['fix_deposit']['account_reference'];	
$prepaired_by = $collection['fix_deposit']['prepaired_by'];	
$principal_amt = $collection['fix_deposit']['principal_amount'];
$start_date = $collection['fix_deposit']['start_date'];
$maturity_date = $collection['fix_deposit']['maturity_date'];
$interest_rate = $collection['fix_deposit']['interest_rate'];
$remark = $collection['fix_deposit']['remark'];
$reminder = $collection['fix_deposit']['reminder'];
//$tds = $collection['fix_deposit']['tds'];
$name = $collection['fix_deposit']['name'];
$email = $collection['fix_deposit']['email'];
$mobile = $collection['fix_deposit']['mobile'];
$file_name = $collection['fix_deposit']['file_name'];

$nnn = 555;
}
?>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php
if($nnn == 555)
{
foreach($cursor2 as $collection)
{
$society_name = $collection['society']['society_name'];	
}
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<div style="width:100%;" class="hide_at_print">
<span style="float:right;"><a href="fix_deposit_excel" class="btn blue" style="margin-right:80px;">Export in Excel</a></span>
<span style="float:right; margin-right:1%;"><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br /><br />
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<table class="table table-bordered" style="width:100%; background-color:white;">
<tr>
<th style="text-align:center;">Sr #</th>
<th style="text-align:center;">Bank Name</th>
<th style="text-align:center;">Branch</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">E-mail</th>
<th style="text-align:center;">Mobile</th>
<th style="text-align:center;">A/c Reference</th>
<th style="text-align:center;">Principal Amount</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">Maturity Date</th>
<th style="text-align:center;">Interest Amount</th>
<th style="text-align:center;">Interest Rate</th>
<th style="text-align:center;">Maturity Amount</th>
<th style="text-align:center;">Transaction Id</th>
<th style="text-align:center;">Remark</th>
<th style="text-align:center;">Download Attachment</th>
<th style="text-align:center;">Action</th>
</tr>

<?php
$n=0;
$principal_tt = 0;
$int_tt = 0;
$mat_tt = 0;
foreach($cursor1 as $collection)
{
$auto_id = (int)$collection['fix_deposit']['auto_id'];	
$bank_name = $collection['fix_deposit']['bank_name'];	
$branch = $collection['fix_deposit']['branch'];	
$account_ref = $collection['fix_deposit']['account_reference'];	
$prepaired_by = $collection['fix_deposit']['prepaired_by'];	
$principal_amt = $collection['fix_deposit']['principal_amount'];
$start_date = $collection['fix_deposit']['start_date'];
$maturity_date = $collection['fix_deposit']['maturity_date'];
$interest_rate = $collection['fix_deposit']['interest_rate'];
$remark = $collection['fix_deposit']['remark'];
$reminder = $collection['fix_deposit']['reminder'];
//$tds = $collection['fix_deposit']['tds'];
$name = $collection['fix_deposit']['name'];
$email = $collection['fix_deposit']['email'];
$mobile = $collection['fix_deposit']['mobile'];
$file_name = $collection['fix_deposit']['file_name'];

$n++;
$start_date2 = date('d-M-Y',$start_date->sec);
$maturity_date2 = date('d-M-Y',$maturity_date->sec);

function dateDiff2($d1, $d2)
{
return round(abs(strtotime($d1)-strtotime($d2))/86400);
} 

$days = dateDiff2($start_date2,$maturity_date2);

$interest = round(($principal_amt * $interest_rate *($days/365))/100);

$mat_amt = $principal_amt + $interest;

$principal_tt = $principal_tt + $principal_amt;
$int_tt = $int_tt + $interest;
$mat_tt = $mat_tt + $mat_amt;


?>
<tr>
<td style="text-align:center;"><?php echo $n; ?></td>
<td style="text-align:center;"><?php echo $bank_name; ?></td>
<td style="text-align:center;"><?php echo $branch; ?></td>
<td style="text-align:center;"><?php echo $name; ?></td>
<td style="text-align:center;"><?php echo $email; ?></td>
<td style="text-align:center;"><?php echo $mobile; ?></td>
<td style="text-align:center;"><?php echo $account_ref; ?></td>
<td style="text-align:center;"><?php echo $principal_amt; ?></td>
<td style="text-align:center;"><?php echo $start_date2; ?></td>
<td style="text-align:center;"><?php echo $maturity_date2; ?></td>
<td style="text-align:center;"><?php echo $interest_rate; ?></td>
<td style="text-align:center;"><?php echo $interest; ?></td>
<td style="text-align:center;"><?php echo $mat_amt; ?></td>
<td style="text-align:center;"><?php echo $auto_id; ?></td>
<td style="text-align:center;"><?php echo $remark; ?></td>
<td style="text-align:center;"><a download href="<?php echo $this->webroot ?>fix_deposit/<?php echo $file_name; ?>">Download</a></td>
<td style="text-align:center;"></td>
</tr>
<?php
}
?>
<tr>
<th colspan="7" style="text-align:left;">Total</th>
<th style="text-align:center;"><?php echo $principal_tt; ?></th>
<th colspan="3"></th>
<th style="text-align:center;"><?php echo $int_tt; ?></th>
<th style="text-align:center;"><?php echo $mat_tt; ?></th>
<th colspan="4"></th>


</tr>
</table>


<?php }
if($nnn == 55)
{
?>
<br /><br />
<center>
<h3 style="color:red;"><b>No Record Found</b></h3>
</center>
<br /><br />

<?php } ?>

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
$("#go").bind('click',function(){
var date1=document.getElementById('date1').value;
var date2=document.getElementById('date2').value;
$("#result").html('<div align="center" style="padding:10px;"><img src="as/loding.gif" />Loading....</div>').load("fixed_diposit_show_ajax?date1=" +date1+ "&date2=" +date2+ "");
});
});
</script>