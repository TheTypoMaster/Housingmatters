<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" class="btn red" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_view" class="btn blue" rel='tab'>Active Deposits</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/matured_deposit_view" class="btn blue" rel='tab'>Matured Deposits</a>
</center>	

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Fix Deposit</h4>  
   
<form method="post">
<div class="row-fluid">
<div class="span6">
   

<label style="font-size:14px;">Bank Name<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="bank_name" class="m-wrap span9" id="bkn">
</div>
<br />

  

<label style="font-size:14px;">Branch<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="branch" class="m-wrap span9" id="brc">
</div>
<br />
  
  

<label style="font-size:14px;">Account Reference<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="account_reference" class="m-wrap span9" id="arf"> 
</div>
<br />
  
  
<label style="font-size:14px;">Principal Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="principal_amount" class="m-wrap span9" id="prm">
</div>
<br />


<label style="font-size:14px;">Reminder Days<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="reminder" class="m-wrap span9" id="rmd">
</div>
<br />


<label style="font-size:14px;">Remarks</label>
<div class="controls">
<textarea name="remark" class="m-wrap span9" id="rmk" rows="4"></textarea>
</div>
<br />











</div> 
<div class="span6">  
  
  
 
<label style="font-size:14px;">Start Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="start_date" id="std">
</div>
<br />
			
  

<label style="font-size:14px;">Maturity Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="maturity_date" id="mtd">
</div>
<br />
  
  

<label style="font-size:14px;">Interest Rate %<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="interest_rate" class="m-wrap span9" id="ir">
</div>
<br />

	
<label style="font-size:14px;">TDS Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="tds" class="m-wrap span9" id="tda">
</div>
<br />
			
 
 
 
 
 
  
  
<label style="font-size:14px;">Attachment</label>
<div class="controls">
<div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
<span class="btn btn-file">
<span class="fileupload-new">Select file</span>
<span class="fileupload-exists">Change</span>
<input type="file" class="default" name="uploaded" id="upl">
</span>
<span class="fileupload-preview"></span>
<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
</div>
</div>        
<br />			
</div>
</div>
<hr/>
<div class="row-fluid">
<div class="span6">  
<label style="font-size:14px;"><b>Contact Detail</b>(Optional)</label>
<br />


<label style="font-size:14px;">Name</label>
<div class="controls">
<input type="text"  name="name" class="m-wrap span9" id="name">
</div>          
<br />            

<label style="font-size:14px;">E-mail</label>
<div class="controls">
<input type="text" name="email" class="m-wrap span9" id="email">
</div>
<br />

<label style="font-size:14px;">Mobile</label>
<div class="controls">
<input type="text" name="mobile" class="m-wrap span9" id="mobile">
</div>





</div>
</div>
<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" style="background-color: #09F;color:#fff;" class="btn" rel='tab'>Reset</a>
<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>   
              
 <script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		var m_data = new FormData();
		m_data.append( 'ac_gr', $('#go').val());
		m_data.append( 'prt_ac', $('#usr').val());
		m_data.append( 'ac_head', $('#acn').val());
		m_data.append( 'tra_dat', $('#date').val());
		m_data.append( 'amt', $('#amt').val());
		m_data.append( 'desc', $('#narr').val());
				
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "petty_cash_receipt_json",
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

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>   
		
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			























