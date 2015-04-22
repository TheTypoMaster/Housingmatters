<?php
if($zz == 0)
{
?>
<div class="alert">
<button class="close" data-dismiss="alert"></button>
<center>
No Previous Receipt
</center>
</div> 
<?php
}
else
{
?>
<div class="alert">
<button class="close" data-dismiss="alert"></button>
<center>
The Last Receipt Number is : <?php echo $zz; ?>
</center>
</div> 
<?php } ?>

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

<?php //////////////////////////////////////////////////////////////////////////////////////////
	/*
		   if($s_role_id == 3)
           { ?>
            <table width="100%" border="1" bordercolor="#FFFFFF" cellpadding="0">
            <tr>
            <td style="width:20%">
            <a href="bank_receipt_view" class="btn red btn-block"  style="font-size:16px;">Bank Receipt</a>
            </td>
            <td style="width:20%">
            <a href="bank_payment_view" class="btn blue btn-block"   style="font-size:16px;">Bank Payment</a>
            </td>
            <td style="width:20%">
            <a href="petty_cash_receipt_view" class="btn blue btn-block"  style="font-size:16px;">Petty Cash Receipt</a>
            </td>
            <td style="width:20%">
            <a href="petty_cash_payment_view" class="btn blue btn-block"  style="font-size:16px;">Petty Cash Payment</a>
            </td>
            <td style="width:20%">
            <a href="fix_deposit_view" class="btn blue btn-block"  style="font-size:16px;">Fixed Deposit</a>
            </td>
            </tr>
            </table>     
           <?php }
		   if($s_role_id == 2)
		   {
		   ?>
            <table width="100%" border="1" bordercolor="#FFFFFF" cellpadding="0">
            <tr>            
			<?php
			if($tenant_c == 1)
			{
			?>
			
			
            <td style="width:25%">
            <a href="bank_receipt_view" class="btn red btn-block"  style="font-size:16px;">Bank Receipt</a>
            </td>
            <?php } ?>
			<td style="width:25%">
            <a href="bank_payment_view" class="btn blue btn-block"   style="font-size:16px;">Bank Payment</a>
            </td>
            <td style="width:25%">
            <a href="petty_cash_receipt_view" class="btn blue btn-block"  style="font-size:16px;">Petty Cash Receipt</a>
            </td>
            <td style="width:25%">
            <a href="petty_cash_payment_view" class="btn blue btn-block"  style="font-size:16px;">Petty Cash Payment</a>
            </td>
            </tr>
            </table>   
           <?php } 
		   
		   */?>
<?php //////////////////////////////////////////////////////////////////////////////////////// ?>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" class="btn red" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt_view" class="btn blue" rel='tab'>View</a>
</center>	
<?php /////////////////////////////////////////////////////////////////////////////////////////?>
     
<br />	          
<div class="portlet box grey" style="width:60%; margin-left:20%; margin-right:20%;">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Bank Receipt</h4>
</div>
<div class="portlet-body form">
<form id="contact-form" method="POST" class="form-horizontal" enctype="multipart/form-data">
<center>             
<table  style="width:80%;">                   
<tr>
<td align="left">
<br />
<label  style="font-size:14px;">Transaction date</label>
</td>
<td>
<br />
<input type="text" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="date" placeholder="Transaction Date" style="background-color:white !important;" id="date">
<label id="date"></label>
<div id="result11"></div>
</td>
</tr>
<tr>
<td align="left">
<br />
<label  style="font-size:14px;">Receipt Mode</label>
</td>
<td>
<br />
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="Cheque" style="opacity: 0;" id="mode" class="chn"></span></div>
Cheque
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="NEFT" style="opacity: 0;" id="mode" class="neft"></span></div>
NEFT
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" value="PG" style="opacity: 0;" id="mode" class="pg"></span></div>
PG
</label> 
<label id="mode"></label>
</td>
</tr>
<tr>
<td id="td1">
</td>
<td id="td2">


</td>
</tr>
<tr>
<td align="left">
<br />
<label style="font-size:14px;">Instrument/UTR</label>
</td>
<td>
<br />
<input type="text"  name="instruction" class="m-wrap medium" placeholder="Instrument/UTR" style="background-color:white !important;" id="ins">
</td>
</tr>
<tr>
<td align="left">
<br />
<label style="font-size:14px;">Deposited In</label>
</td>
<td>
<br />
<select name="bank_account" class="medium m-wrap chosen" id="bank">
<option value="" style="display:none;">Deposited In</option>    
<?php
foreach ($cursor3 as $db) 
{
$bank_id = (int)$db['ledger_sub_account']["auto_id"];
$bank_ac = $db['ledger_sub_account']["name"];
?>
<option value="<?php echo $bank_id; ?>"><?php echo $bank_ac; ?></option>
<?php } ?>
</select>
<label id="bank"></label>
</td>
</tr>


<tr>
<td align="left">
<br />
<label style="font-size:14px;">Received from</label>
</td>
<td>
<br />
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member" class="go5" value="1" style="opacity: 0;" id="mem"></span></div>
Member
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member" class="go6" value="2" style="opacity: 0;" onclick="hidediv('div12')" id="mem"></span></div>
Non-Member
</label>
<label id="mem"></label>
</td>
</tr>


<tr>

<td align="left">
<br />
<label style="font-size:14px;">Party Name</label>
</td>
<td>
<br />
<span id="div11"></span> 

<label id="go"></label>
<label id="re"></label>        
</td>
</tr>


<tr>
<td align="left">
<br />
<label style="font-size:14px;">Narration</label>
</td>
<td>
<br />
<textarea   rows="4" name="description" class="medium m-wrap" placeholder="Narration" style="background-color:white !important; resize:none; margin-right:70%;"  id="nar"></textarea>
</td>
</tr>
</table>              
<div id="div13" class="hide">
<table border="0" style="width:80%;">
<tr>
<td align="left">
<br />
<label style="font-size:14px;">Bill Reference</label>
</td>
<td>
<br />
<span style="margin-left:6%;">
<input type="text" class="m-wrap medium" name="refn" placeholder="Bill Reference" style="background-color:white !important;" id="refn"/>
</span>
<label id="refn" style="margin-left:6%;"></label>
</td>
</tr>
<tr>
<td align="left">
<br />
<label style="font-size:14px;">Amount</label>
</td>
<td>
<br />
<span style="margin-left:6%;">
<input type="text" name="amountn" class="m-wrap medium" placeholder="Amount" style="background-color:white !important;" id="amt"/>
</span>
<label id="amt" style="margin-left:6%;"></label>
</td>
</tr>
</table>
</div>
<br />
<div id="div12">
<div id="result" style="width:94%;" >
</div>
</div>

</center>
<div class="form-actions" style="background-color:#CCC;">
<button type="submit" class="btn green" name="bank_receipt_add" value="xyz" id="vali">Submit</button>
<a href="bank_receipt" class="btn">Reset</a>
</div>
</form>
</div>
</div>
              
              
              
              
              
              
              
              
              
              
              
              
              
<?php /////////////////////////////////////////////////////////////////////////////////////////////// ?>              


		<script>
		$(document).ready(function() {
		$("#go").live('change',function(){

		var value1 = document.getElementById('go').value;
		//var date2=document.getElementById('date2').value;


		$("#result").load("bank_receipt_reference_ajax?value1=" +value1 + "");


		});

		$("#i_head").live('change',function(){

		var ss = $("[id=i_head]").val();

		$("#result2").html('Loding...').load("bank_receipt_amount_ajax?ss=" +ss + "");	

		});

		});
		</script>	  
		  
		
		
	
<script>
function hidediv(id)
{
	document.getElementById('div13').style.display='block';
	document.getElementById(id).style.display='none';
}
$(document).ready(function() {
	$(".go5").live('click',function(){
		
		document.getElementById('div12').style.display='block';
		document.getElementById('div13').style.display='none';
		$("#div1").show();
		$("#div2").hide();
		//$("#div11").show();
		//$("#div22").hide();
	
	$("#div11").html('Loding...').load("bank_receipt_ajax?ff=" + 5 + "");
	
	
	});
	
	$(".go6").live('click',function(){
		$("#div2").show();
		$("#div1").hide();
		//$("#div22").show();
		//$("#div11").hide();
		$("div13").show();
	
	$("#div11").html('Loding...').load("bank_receipt_ajax?ff=" + 8 + "");
	
	});
	
});
</script>		
























<?php /////////////// ?>

 <?php ////////////////////////////////////// ?>


<script>
$(document).ready(function(){
	
	 jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value !== param;
}, "Please choose Other value!");
	
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		
		$('#contact-form').validate({
		
		errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    },
					
	    rules: {
	      date: {
	       
	        required: true
	      },
		  
		  
				  
		   bank_account: {
	       
	        required: true
	      },
		  
		 
		 
	         mode: {
                required: true
	      },

	     member: {
	       
	        required: true
	      },
		
		recieved_from2: {
	       
	        required: true
	      },
		
		
		
		recieved_from: {
	       
	        required: true
	      },
		
		 refn: {
	       
	        required: true
	      },
		 
		 amountn: {
	        required: true,
			number: true,
			notEqual: "0"
	      },
		amount : {
			required: true,
			number: true,
			notEqual: "0"
		
		},
		no: {
			required: true,
			number: true
		},
		},
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });

}); 
</script>


<script>
		$(document).ready(function() {
		$("#vali").live('click',function(){
        
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
		


<script>
$(document).ready(function() {
	$(".chn").live('click',function(){
	$('#td1').html('<br><label  style="font-size:14px;">Receipt/NEFT No.</label>');    
	$('#td2').html('<br><input type="text" name="no" class="m-wrap medium" id="no2"><label id="no2"></label>');   
	});
	
	$(".neft").live('click',function(){
	$('#td1').html('<br><label  style="font-size:14px;">Receipt/NEFT No.</label>');    
	$('#td2').html('<br><input type="text" name="no" class="m-wrap medium" id="no2"><label id="no2"></label>');   
	
		
	});
	$(".pg").live('click',function(){
	$('#td1').html('');    
	$('#td2').html('');   	
	   
	});
});
</script>	















