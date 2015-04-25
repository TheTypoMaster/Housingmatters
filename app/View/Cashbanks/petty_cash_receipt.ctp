

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
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt" class="btn red" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt_view" class="btn blue" rel='tab'>View</a>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Petty Cash Receipt</h4>
<?php
if($zz == 0)
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">No Previous receipt</p>
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
<form method="post">
<div class="row-fluid">
<div class="span6">






<label style="font-size:14px;">A/c Group<span style="color:red;">*</span></label>
<div class="controls">
<select name="type" id="go" class="m-wrap span9 chosen">
<option value="" style="display:none;">Select</option>
<option value="1">Sundry Debtors Control A/c</option>
<option value="2">Other Income</option>
</select>
</div>
<br />



<label style="font-size:14px;">Income/Party A/c<span style="color:red;">*</span></label>
<div class="controls" id="show_user">
<select name="user_id" class="m-wrap span9 chosen" id="usr">
<option value="">Select</option>
</select> 
</div>
<br />


<label style="font-size:14px;">Account Head<span style="color:red;">*</span></label>
<div class="controls">
<select   name="account_head" class="m-wrap span9 chosen" id="acn">
<option value="" style="display:none;">Select</option>
<option value="32">Cash-in-hand</option>
</select> 
</div>
</div>


<div class="span6">

<label style="font-size:14px;">Transaction Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date">
</div>
<br />

<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9"  name="ammount" id="amt">
</div>
<br />


<label style="font-size:14px;">Narration</label></td>
<div class="controls">
<textarea   rows="4" name="narration" class="m-wrap span9" style="resize:none;" id="narr"></textarea>
</div>
<br />
</div>
</div>



<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_payment" style="background-color: #09F;color:#fff;" class="btn" rel='tab'>Reset</a>
<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
$("#go").live('change',function(){
var value=document.getElementById('go').value;
{
$("#show_user").load("petty_cash_receipt_ajax?value=" +value+ "");
}
});
});
</script>	

<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


	
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


<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>









