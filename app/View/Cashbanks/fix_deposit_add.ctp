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
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" class="btn yellow" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_view" class="btn" rel='tab'>Active Deposits</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/matured_deposit_view" class="btn" rel='tab'>Matured Deposits</a>
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
<label report="bnk" class="remove_report"></label>
</div>
<br />

  

<label style="font-size:14px;">Branch<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="branch" class="m-wrap span9" id="brc">
<label report="brch" class="remove_report"></label>
</div>
<br />
  
  

<label style="font-size:14px;">Account Reference<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="account_reference" class="m-wrap span9" id="arf"> 
<label report="acref" class="remove_report"></label>
</div>
<br />
  
  
<label style="font-size:14px;">Principal Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="principal_amount" class="m-wrap span9" id="prm">
<label report="pramt" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Reminder Days<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="reminder" class="m-wrap span9" id="rmd">
<label report="remday" class="remove_report"></label>
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
<label report="stdat" class="remove_report"></label>
</div>
<br />
			
  

<label style="font-size:14px;">Maturity Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="maturity_date" id="mtd">
<label report="matdat" class="remove_report"></label>
</div>
<br />
  
  

<label style="font-size:14px;">Interest Rate %<span style="color:red;">*</span></label>
<div class="controls">
<input type="text"  name="interest_rate" class="m-wrap span9" id="ir">
<label report="inrat" class="remove_report"></label>
</div>
<br />

<!--
<label style="font-size:14px;">TDS Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="tds" class="m-wrap span9" id="tda">
<label report="tds" class="remove_report"></label>
</div>
<br />
-->			
 
 
 
 
 
  
  
<label style="font-size:14px;">Attachment</label>
<div class="controls">
<div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
<span class="btn btn-file">
<span class="fileupload-new">Select file</span>
<span class="fileupload-exists">Change</span>
<input type="file" class="default" name="file" id="upl">
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
		m_data.append( 'bnk_name', $('#bkn').val());
		m_data.append( 'branch', $('#brc').val());
		m_data.append( 'ac_ref', $('#arf').val());
		m_data.append( 'pr_amt', $('#prm').val());
		m_data.append( 'rmd_day', $('#rmd').val());
		m_data.append( 'remark', $('#rmk').val());
		m_data.append( 'st_dat', $('#std').val());
		m_data.append( 'mat_dat', $('#mtd').val());
		m_data.append( 'int_rate', $('#ir').val());
		//m_data.append( 'tds_amt', $('#tda').val());
		m_data.append( 'name', $('#name').val());
		m_data.append( 'email', $('#email').val());
		m_data.append( 'mobile', $('#mobile').val());
		m_data.append( 'file', $('input[name=file]')[0].files[0]);
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "fix_deposit_json",
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
		
			
<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Fix Deposit</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Cashbanks/fix_deposit_add" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			























