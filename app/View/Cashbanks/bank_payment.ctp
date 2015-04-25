


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
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment" class="btn red" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment_view" class="btn blue" rel='tab'>View</a>
</center>	
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post bank Voucher</h4>
<?php			
if($zz == 0)
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">No Previous Voucher</p>
</center>
</div> 
<?php
}
else
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">The Last Voucher Number is : <?php echo $zz; ?></p>
</center>
</div> 
<?php } ?>
<br />
<form method="post">
<div class="row-fluid">
<div class="span6">



							
<label style="font-size:14px;">Transaction Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date">
</div>
<br />


<label style="font-size:14px;">A/c Group<span style="color:red;">*</span></label>
<div class="controls">
<select name="ac_type" class="m-wrap chosen span9" id="type">
<option value="">--SELECT--</option>							 
<option value="1">Sundry Creditors Control A/c</option>
<option value="2">Liability</option>
<option value="3">Expenditure</option>
</select>
</div>
<br />



<label style="font-size:14px;">Expense Party A/c<span style="color:red;">*</span></label></td>
<div class="controls" id="result2">
<select name="expense_ac" class="m-wrap chosen span9" id="exp">
<option value="">--SELECT--</option>
</select>
</div>
<br />


 
<label style="font-size:14px;">Invoice Reference<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"   name="invoice_reference" class="m-wrap span9" id="ref">
</div>
<br />						   





 
<label style="font-size:14px;">Narration</label></td>
<div class="controls">
<textarea   rows="4" name="description" class="m-wrap span9" style="resize:none;" id="des"></textarea>
</div>
<br />
</div>


<div class="span6">


  
                          
<label style="font-size:14px;">Mode of Payment<span style="color:red;">*</span></label>
<div class="controls">
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="Cheque" style="opacity: 0;" id="mode"></span></div>
Cheque
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="NEFT" style="opacity: 0;" id="mode"></span></div>
NEFT
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="PG" style="opacity: 0;" id="mode"></span></div>
PG
</label>
</div>
<br />                         


 
<label style="font-size:14px;">Instrument/UTR<span style="color:red;">*</span></label></td>
<div class="controls">
<input type="text"   name="instruction" class="m-wrap span9" id="inst">
</div>
<br />						  




<label style="font-size:14px;">Bank Account<span style="color:red;">*</span></label></td>
<div class="controls">
<select name="bank_account" onchange="get_value(this.value)" class="m-wrap chosen span9" id="acb">
<option value="" style="display:none;">Select</option>    
<?php
foreach ($cursor2 as $db) 
{
$sub_account_id =(int)$db['ledger_sub_account']['auto_id'];
$sub_account_name =$db['ledger_sub_account']['name'];
?>
<option value="<?php echo $sub_account_id; ?>"><?php echo $sub_account_name; ?></option>
<?php } ?>
</select>
</div>
<br />


<label style="font-size:14px;">Amount<span style="color:red;">*</span></label></td>
<div class="controls">
<input type="text"   name="ammount" class="m-wrap span9" id="amount">
</div>
<br />


<label style="font-size:14px;">TDS in Percentage<span style="color:red;">*</span></label></td>
<div class="controls">
<select name="tds_p" id="go" class="m-wrap chosen span9">
<option value="" style="display:none;">Select</option>
<?php
for($k=0; $k<sizeof($tds_arr); $k++)
{
$tds_sub_arr = $tds_arr[$k];	
$tds_id = (int)$tds_sub_arr[1];
$tds_tax = $tds_sub_arr[0];	
?>
<option value= "<?php echo $tds_id; ?>"><?php echo $tds_tax; ?></option>
<?php } ?>                           
</select>
</div>
<br />



<label style="font-size:14px;">Total Amount</label>
<div class="controls" id="result">
<span id="total_am">
<input type="text" readonly class="m-wrap span9" id="amt">
</span>
</div>

						  















</div>
</div>
<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment" style="background-color: #09F;color:#fff;" class="btn" rel='tab'>Reset</a>
<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
           
<script>
$(document).ready(function() {
$("#go").live('change',function(){
var tds = document.getElementById('go').value;
var amount=document.getElementById('amount').value;
$("#result").load('bank_payment_tds_ajax?tds='+tds+'&amount='+amount+'');
});
});
</script>	

	
<script>
$(document).ready(function() {
$("#type").live('change',function(){
var type = document.getElementById('type').value;
$("#result2").load('bank_payment_type_ajax?type='+type+'');
});
});
</script>		
	
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
				
	