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
	
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" class="btn yellow" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt_view" class="btn" rel='tab'>View</a>
</center>	
<?php /////////////////////////////////////////////////////////////////////////////////////////?>
<?php 
$default_date = date('d-m-Y')
?>
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Bank Receipt</h4>
<?php
if($zz == 0)
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">No Previous Receipt</p>
</center>
</div> 
<?php
}
else
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">The Last Receipt Number is : <?php echo $zz; ?></p>
</center>
</div> 
<?php } ?>
<br />  
<form id="contact-form" method="post">
<div class="row-fluid">
<div class="span6">  
  


<label style="font-size:14px;">Transaction date<span style="color:red;">*</span> </label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" placeholder="Transaction Date" style="background-color:white !important;" id="date" value="<?php echo $default_date; ?>">
<label id="date"></label>
<div id="result11"></div>
</div>
<br /> 
 


<label  style="font-size:14px;">Receipt Mode<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose receipt mode"> </i></label>
<div class="controls">
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="mode" checked="" value="Cheque" style="opacity: 0;" id="mode" class="chn"></span></div>
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
</div>
<br />





 
<div id="cheque_div">
<label style="font-size:14px;">Cheque No.<span style="color:red;">*</span> </label>
<div class="controls">
<input type="text"  name="cheque_number" class="m-wrap span9" placeholder="Cheque No." style="background-color:white !important;" id="ins">
<label id="ins"></label>
</div>

<label style="font-size:14px;">Drown on which bank?<span style="color:red;">*</span> </label>
<div class="controls">
<input type="text"  name="which_bank" class="m-wrap span9" placeholder="Drown on which bank?" style="background-color:white !important;" id="ins">
<label id="ins"></label>
</div>



</div>

<div id="neft_div" style="display:none;">
<label style="font-size:14px;">Reference/UTR #<span style="color:red;">*</span> </label>
<div class="controls">
<input type="text"  name="reference_number" class="m-wrap span9 ignore" placeholder="Reference/UTR #" style="background-color:white !important;" id="ins">
<label id="ins"></label>
</div>

</div>

<label style="font-size:14px;">Cheque Date<span style="color:red;">*</span> </label>
<div class="controls">
<input type="text"  name="cheque_date" class="m-wrap span9 date-picker" placeholder="Cheque Date" data-date-format="dd-mm-yyyy" style="background-color:white !important;" id="ins">
<label id="ins"></label>
</div>
<br /> 
 
 
 

<label style="font-size:14px;">Deposited In<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select deposit bank "> </i></label>
<div class="controls">
<select name="bank_account" class="span9 m-wrap chosen" id="bank">
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
</div>
<br />


<label style="font-size:14px;">Narration<span style="color:red;">*</span></label>
<div class="controls">
<textarea   rows="4" name="description" class="span9 m-wrap" placeholder="Narration" style="background-color:white !important; resize:none; margin-right:70%;"  id="nar"></textarea>
</div>
<br />
  
  
</div>

<div class="span6">




<label style="font-size:14px;">Received from<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose member/non-member "> </i></label>
<div class="controls">
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member" class="hhh" value="1" style="opacity: 0;" id="mem"></span></div>
Member
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member" class="go6" value="2" style="opacity: 0;" onclick="hidediv('div12')" id="mem"></span></div>
Non-Member
</label>
<label id="mem"></label>
</div>
<br />



<div id="div11"></div>



<div id="div13" class="hide">
<label style="font-size:14px;">Bill Reference<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="refn" placeholder="Bill Reference" style="background-color:white !important;" id="refn"/>
<label id="refn"></label>
</div>
<br />



<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="amountn" class="m-wrap span9" placeholder="Amount" style="background-color:white !important;" id="amt"/>
<label id="amt"></label>
</div>
<br />
</div>




<div id="div12">
<div id="result" style="width:94%;" >
</div>
</div>




























</div>
</div>
<br />  
<button type="submit" class="btn green" name="bank_receipt_add" value="xyz" id="vali">Submit</button>
<a href="bank_receipt" class="btn">Reset</a>
     
</div>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
     
              
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

		$("#result2").html('Loading...').load("bank_receipt_amount_ajax?ss=" +ss + "");	

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
	$(".hhh").live('click',function(){
		
		document.getElementById('div12').style.display='block';
		document.getElementById('div13').style.display='none';
		//$("#div1").show();
		//$("#div2").hide();
		//$("#div13").hide();
		
	
	$("#div11").html('Loading...').load("bank_receipt_ajax?ff=" + 5 + "");
	
	
	});
	
	$(".go6").live('click',function(){
		
		//$("#div2").show();
		//$("#div1").hide();
		
		$("#div13").show();
	
	$("#div11").html('Loading...').load("bank_receipt_ajax?ff=" + 8 + "");
	
	});
	
});
</script>		

 <?php ////////////////////////////////////// ?>


<script>
$(document).ready(function(){
	
	 jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value !== param;
}, "Please choose Other value!");
	
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		
		$('#contact-form').validate({
		ignore: ".ignore",
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
		instruction: {
			 required: true
		         },
		 reference_number: {
	        required: true
	      },
		  cheque_number: {
	        required: true
	      },
		  which_bank: {
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
		
<script>
$(document).ready(function() {
$(".chn").live('click',function(){
$('#cheque_div').show();    
$('#neft_div').hide();
});

$(".neft").live('click',function(){
$('#cheque_div').hide();    
$('#neft_div').show(); 	
});

$(".pg").live('click',function(){
$('#cheque_div').hide();    
$('#neft_div').show(); 	
});
});
</script>	















