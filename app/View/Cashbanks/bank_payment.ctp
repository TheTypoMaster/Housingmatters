<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<input type="hidden" id="fi" value="<?php echo $datef1; ?>" />
<input type="hidden" id="ti" value="<?php echo $datet1; ?>" />
<input type="hidden" id="cn" value="<?php echo $count; ?>" />
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment" class="btn yellow" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment_view" class="btn" rel='tab'>View</a>
</center>	
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$default_date = date('d-m-Y');
?>
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
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date" value="<?php echo $default_date; ?>">
<label report="tr_dat" class="remove_report"></label>
<div id="result11"></div>
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
<label report="ac_gr" class="remove_report"></label>
</div>
<br />



<label style="font-size:14px;">Expense Party A/c<span style="color:red;">*</span></label></td>
<div class="controls" id="result2">
<select name="expense_ac" class="m-wrap chosen span9" id="ex_prt_ac">
<option value="">--SELECT--</option>
</select>
<label report="ex_prt" class="remove_report"></label>
</div>
<br />


 
<label style="font-size:14px;">Invoice Reference<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"   name="invoice_reference" class="m-wrap span9" id="ref">
<label report="inv_ref" class="remove_report"></label>
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
<label report="mode" class="remove_report"></label>
</div>
<br />                         


 
<label style="font-size:14px;">Instrument/UTR<span style="color:red;">*</span></label></td>
<div class="controls">
<input type="text"   name="instruction" class="m-wrap span9" id="inst">
<label report="ins_utr" class="remove_report"></label>
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
<label report="bank_ac" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Amount<span style="color:red;">*</span></label></td>
<div class="controls">
<input type="text"   name="ammount" class="m-wrap span9" id="amount">
<label report="amt" class="remove_report"></label>
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
<label report="tds" class="remove_report"></label>
</div>
<br />



<label style="font-size:14px;">Total Amount</label>
<div class="controls" id="result">
<span id="total_am">
<input type="text" readonly class="m-wrap span9" id="amt" id="tt">
</span>
</div>


</div>
</div>
<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz" id="vali">Submit</button>
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
				
	
  <script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		var m_data = new FormData();
		m_data.append( 'tra_dat', $('#date').val());
		m_data.append( 'ac_grp', $('#type').val());
		m_data.append( 'ex_prt_acn', $('#ex_prt_ac').val());
		m_data.append( 'inv_ref', $('#ref').val());
		m_data.append( 'desc', $('#des').val());
		m_data.append( 'mode', $('input:radio[name=mode]:checked').val());
		m_data.append( 'inst_utr', $('#inst').val());
		m_data.append( 'bank_acn', $('#acb').val());
		m_data.append( 'amt', $('#amount').val());
		m_data.append( 'tds', $('#go').val());
		m_data.append( 'tt_amt', $('#tt').val());
		
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "bank_payment_json",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType:'json',
			}).done(function(response) {
				if(response.report_type=='error'){
					$(".remove_report").html('');
						jQuery.each(response.report, function(i, val) {
						$("label[report="+val.label+"]").html('<span style="color:red;">'+val.text+'</span>');
					});
				}
				if(response.report_type=='publish'){
                $("#shwd").show()
				$(".success_report").show().html(response.report);	
				}
			
			$("html, body").animate({
			scrollTop:0
			},"slow");
			$(".form_post").removeClass("disabled");
			$("#wait").hide();
			});

	 
	});
});

</script>
  
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
    
    
    
    
    
<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Expense Tracker</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 
    
    
    
    
<script>
$(document).ready(function() {
$("#vali").bind('click',function(){

var fi = document.getElementById("fi").value;
var ti = document.getElementById("ti").value;
var cn = document.getElementById("cn").value;
var fe = fi.split(",");
var te = ti.split(",");
var date1 = document.getElementById("date").value;

var date = date1.split("-").reverse().join("-");

var nnn = 55;
for(var i=0; i<cn; i++)
{
var fd = fe[i];
var td = te[i]

if(date == "")
{
nnn = 555;
break;	
}
else if(Date.parse(fd) <= Date.parse(date))
{
if(Date.parse(td) >= Date.parse(date))
{
nnn = 5;
break;
}
else
{

}
} 
}


if(nnn == 55)
{
$("#result11").load("cash_bank_vali?ss=" + 2 + "");
return false;	
}
else if(nnn == 555)
{

}
else
{
$("#result11").load("cash_bank_vali?ss=" + 12 + "");		
}



});
});
</script>   
    
    
    
    
    
    
    
    
    
    
    
    
    