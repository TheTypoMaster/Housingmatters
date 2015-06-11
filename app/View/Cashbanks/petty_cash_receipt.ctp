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
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
<center>                
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt" class="btn yellow" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt_view" class="btn" rel='tab'>View</a>
</center>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$default_date = date('d-m-Y');
?>
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
<label report="ac_grp" class="remove_report"></label>
</div>
<br />



<label style="font-size:14px;">Income/Party A/c<span style="color:red;">*</span></label>
<div class="controls" id="show_user">
<select name="user_id" class="m-wrap span9 chosen" id="usr">
<option value="">Select</option>
</select> 
<label report="prt_ac" class="remove_report"></label>
</div>
<br />



<div id="show_bill" class="controls">

</div>
<br />



<label style="font-size:14px;">Account Head<span style="color:red;">*</span></label>
<div class="controls">
<select   name="account_head" class="m-wrap span9 chosen" id="acn">
<option value="" style="display:none;">Select</option>
<option value="32">Cash-in-hand</option>
</select> 
<label report="ac_head" class="remove_report"></label>
</div>
</div>


<div class="span6">

<label style="font-size:14px;">Transaction Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date" value="<?php echo $default_date; ?>">
<label report="tr_dat" class="remove_report"></label>
<div id="result11"></div>
</div>
<br />

<label style="font-size:14px;">Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9"  name="ammount" id="amt">
<label report="amt" class="remove_report"></label>
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
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz" id="vali">Submit</button>
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt" style="background-color: #09F;color:#fff;" class="btn" rel='tab'>Reset</a>
<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
$("#usr").live('change',function(){	
var type = $("#go").val();	
if(type == 1)
{
var value1 = document.getElementById('usr').value;
$("#show_bill").load("bank_receipt_reference_ajax?value1=" +value1 + "");	
}
else
{
$("#show_bill").html("");
}
});
});
</script>
<script>
$(document).ready(function() {
$("#go").bind('change',function(){
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

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


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
<a href="<?php echo $webroot_path; ?>Cashbanks/petty_cash_receipt" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 


<?php ///////////////////////////////////////////////////////////////////////////////// ?>
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


