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
			<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_payment" class="btn yellow" rel='tab'>Create</a>
			<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_payment_view" class="btn" rel='tab'>View</a>
			</center>	   
<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?>		   
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Petty Cash Payment</h4>
<?php
if($zz == 0)
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">No Previous Voucher</p>
</center>
</div> 
<?php } else { ?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">The Last Voucher Number is : <?php echo $zz; ?></p>
</center>
</div> 
<?php } ?>
<br />
<form id="contact-form" method="post">
<div class="row-fluid">
<div class="span6">


<label style="font-size:14px;">Transaction Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date">
<label id="date"></label>
<div id="result11"></div>
</div>
<br />


<label style="font-size:14px;">A/c Group<span style="color:red;">*</span></label>
<div class="controls">
<select name="type" id="go" class="m-wrap span9 chosen">
<option value="" style="display:none;">Select</option>
<option value="1">Sundry Creditors Control A/c</option>
<option value="2">All Expenditure A/cs</option>
</select>
<label id="go"></label>
</div>
<br />


<label style="font-size:14px;">Expense/Party A/c<span style="color:red;">*</span></label></td>
<div class="controls" id="show_user">
<select   name="user_id" class="m-wrap span9 chosen" id="usr">
<option value="" style="display:none;">Select</option>
</select>
<label id="usr"></label>
</div>
</div>


<div class="span6">

<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="ammount" id="amount" class="m-wrap span9">
<label id="amount"></label>
</div>
<br />



<label style="font-size:14px;">Paid From<span style="color:red;">*</span></label>
<div class="controls">
<select   name="account_head" class="m-wrap span9 chosen" id="ach">
<option value="" style="display:none;">Select</option>
<option value="32">Cash-in-hand</option>
</select> 
<label id="ach"></label>
</div>
<br />


<label style="font-size:14px;">Narration<span style="color:red;">*</span></label>
<div class="controls">
<textarea   rows="4" name="narration" style="resize:none;" class="m-wrap span9" id="nr"></textarea>
<label id="nr"></label>
</div>

</div>
</div>
<button type="submit" class="btn green" name="ptp_add" value="xyz" id="vali">Submit</button>
<a href="petty_cash_payment" class="btn">Reset</a>
</form>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>		   
<?php	   
/*
<form id="contact-form" method="POST" class="form-horizontal" enctype="multipart/form-data">

<div class="portlet box grey" style="width:60%; margin-left:20%; margin-right:20%;">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Petty Cash Payment</h4>
</div>
<div class="portlet-body form">  

<form id="contact-form" method="POST" class="form-horizontal" enctype="multipart/form-data">
<center>
<table border="0" style="width:80%;">                  

																		</table>

									<br><Br>

									<div class="form-actions" style="background-color:#999;">
									<button type="submit" class="btn green" name="ptp_add" value="xyz" id="vali">Submit</button>
									<a href="petty_cash_payment" class="btn">Reset</a>
									</div>



									</center>
									</form>



									</div>
									</div>
									
								*/	?>

<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$("#go").live('change',function(){
		
		var value1 = document.getElementById('go').value;
		$("#show_user").load("petty_cash_payment_ajax?value1=" +value1 + "");
	});
	
});
</script>	


<script>
$(document).ready(function() {
	$("#data_tds").live('change',function(){
		
		var data_tds = document.getElementById('data_tds').value;
		var amount = document.getElementById('amount').value;
		
		$("#total_am").load("amount_cal_p?data=" + data_tds + "&amount="+ amount +"");
	});
	});
</script>	

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
		  
		  
		  type: {
	       
	        required: true
	      },
		  
		   user_id: {
	       
	        required: true
	      },
		  
		  
		  
		   ammount: {
	       
	        required: true,
			number: true,
			notEqual: "0"
	      },
		 
		  
		 
		 
		    narration: {
	       
	        required: true
	      },

	     account_head: {
	       
	        required: true
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
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   