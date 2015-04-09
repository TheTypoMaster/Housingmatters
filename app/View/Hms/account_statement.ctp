<?php ////////////////////////////////////////////////////////////////////////////////?>

<?php //////////////////////////////////////////////////////////////////////////////////////?>
<center>
<h3><b>Account Statement</b></h3>
</center>


<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<center>
<div class="hide_at_print">
<table border="0">
<tr>
<td>
<select name="usr_id" class="m-wrap medium chosen" id="abc">
<option value="" style="display:none;">Select Resident Name</option>
<?php
foreach($cursor2 as $collection)
{
$user_id1 = (int)$collection['user']['user_id'];
$user_name1 = $collection['user']['user_name'];
?>
<option value="<?php echo $user_id1; ?>"><?php echo $user_name1; ?></option>
<?php
}
?>
</select>
</td>
<td>
<input type="text" class="medium m-wrap date-picker" name="from" id="from" style="background-color:white !important; margin-top:8px;" />
</td>
<td>
<input type="text" class="medium m-wrap date-picker" name="to" id="to" style="background-color:white !important; margin-top:8px;" />
</td>
<td>
<button type="button" name="" id="go" class="btn yellow" style="">Go</button>
</td>
</tr>
</table>
</div>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<div id="show" style="width:94%;">
</div>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
<!-- <center>
<br><br><br>		
<div style="width:94%;">
<table class="table table-bordered" style="width:100%; background-color:white;"> 

<tr>
<th style="text-align:center;">Sr. No.</th>
<th style="text-align:center;">User Name</th>
<th style="text-align:center;">Bill No.</th>
<th style="text-align:center;">Bill for Date</th>
<th style="text-align:center;">Last Date</th>
<th style="text-align:center;">Total Amount</th>
<th style="text-align:center;">Due Amount</th>
<th style="text-align:center;">Action</th>
</tr>
         

<?php
/*
$nn = 0;
$grand_total_amount=0;
$total_due_amount=0;
foreach ($cursor1 as $collection) 
{
		$nn++;
		$bill_no = (int)$collection['regular_bill']['receipt_id'];
		$date_from = $collection['regular_bill']['bill_daterange_from'];
		$date_to = $collection['regular_bill']['bill_daterange_to'];
		$last_date = $collection['regular_bill']['due_date'];
		$total_amount = (int)$collection['regular_bill']['g_total'];
		$due_amount = (int)$collection['regular_bill']['remaining_amount'];
		$user_id = (int)$collection['regular_bill']['bill_for_user'];
        //$bill_no = (int)$collection[''][''];
        //$bill_no = (int)$collection[''][''];
        $date_from1 = date('d-M-Y',$date_from->sec);
        $date_to1 = date('d-M-Y',$date_to->sec);
        $due_date = date('d-M-Y',$last_date->sec); 
        $grand_total_amount = $grand_total_amount + $total_amount;
        $total_due_amount = $total_due_amount + $due_amount;

//$result2 = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));	
//foreach($result2 as $collection)
//{
//$user_name = $collection['user']['user_name'];
//$wing = (int)$collection['user']['wing'];
//$flat =(int)$collection['user']['flat'];
//}
//$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));	

*/

?>
<tr>
<td style="text-align:center;"><?php //echo $nn; ?></td>
<td style="text-align:center;"><?php //echo $user_name; ?>&nbsp;&nbsp;<?php //echo $wing_flat; ?></td>
<td style="text-align:center;"><?php //echo $bill_no; ?></td>
<td style="text-align:center;"><?php //echo $date_from1; ?>&nbsp;&nbsp;To&nbsp;&nbsp;<?php //echo $date_to1; ?></td>
<td style="text-align:center;"><?php //echo $due_date; ?></td>
<td style="text-align:center;"><?php //echo $total_amount; ?></td>
<td style="text-align:center;"><?php //echo $due_amount; ?></td>
<td style="text-align:center;"></td>
</tr>
<?php
//}
?>
<th colspan="5" style="text-align:right;">Grand Total</th>
<th style="text-align:center;"><?php //echo $grand_total_amount; ?></th>
<th style="text-align:center;"><?php //echo $total_due_amount; ?></th>
<th style=""></th>
</table> -->
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>

<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		
	var usid = document.getElementById('abc').value;	
	var from = document.getElementById('from').value;	
	var to = document.getElementById('to').value;	

	
	$("#show").html('Loading...').load("account_statement_show_ajax?ff=" + usid + "&f=" + from +"&t=" + to +"");
	
	
	});
});
</script>




























