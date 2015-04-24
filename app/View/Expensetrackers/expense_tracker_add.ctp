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
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #F497BA;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Expense</h4>
<form method="post">
<div class="row-fluid">
<div class="span6">


<label style="font-size:14px;">Expense Head<span style="color:red;">*</span></label>
<div class="controls">
<select name="ex_head" class="m-wrap chosen span9" id="ex">
<option value=""></option>
<?php
foreach ($cursor1 as $collection)
{
$c_id =  (int)$collection['accounts_group']['auto_id'];
$c_name = $collection['accounts_group']['category_name'];
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'expense_tracker_fetch2'),array('pass'=>array($c_id)));
foreach ($result as $db)
{
$g_id =  (int)$db['ledger_account']['auto_id'];
$name = $db['ledger_account']['ledger_name'];
?>
<option value="<?php echo $g_id; ?>"><?php echo $name; ?></option>
<?php }} ?>
</select>
<label report="ex_head" class="remove_report"></label>
</div>
<br />


						
							


<label style="font-size:14px;">Invoice Reference<span style="color:red;">*</span></label>
<div class="controls">	
<input type="text" class="m-wrap span9"  name="invoice_reference" id="ref">
<label report="inv_ref" class="remove_report"></label>
</div>
<br />







<label style="font-size:14px;">Party Account Head<span style="color:red;">*</span></label>
<div class="controls">	
<select name="party_head" class="m-wrap chosen span9" id="ph">
<option value=""></option>
<?php
foreach ($cursor2 as $collection)
{
$id = $collection['ledger_sub_account']['auto_id'];
$name = $collection['ledger_sub_account']['name']; 
?>                             
<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php } ?>
</select>
<label report="prt_head" class="remove_report"></label>
</div>
<br />




<label style="font-size:14px;">Amount of Invoice<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9"   name="invoice_amount" id="ia">
<label report="amt" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Description</label>
<div class="controls">
<textarea  rows="4" name="description" class="m-wrap span9" style="resize:none;" id="des"></textarea>
</div>
</div>













<div class="span6">

<label style="font-size:14px;">Posting Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="posting_date" id="pd">
<label report="pos_dat" class="remove_report"></label>
</div>
<br />

<label style="font-size:14px;">Payment Due Date<span style="color:red;">*</span></label>
<div class="controls">	
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="due_date" id="due">
<label report="du_dat" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Date of Invoice<span style="color:red;">*</span></label>
<div class="controls">							
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="invoice_date" id="date">
<label report="inv_dat" class="remove_report"></label>
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










</div>
</div>




<hr/>
<button type="submit" class="btn form_post" style="background-color: #E0619D;color:#fff;" name="ext_add" value="xyz" id="vali">Submit</button>
<a href="<?php echo $webroot_path; ?>Expensetrackers/expense_tracker_add" style="background-color: #E0619D;color:#fff;" class="btn" rel='tab'>Reset</a>
<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>


<?php /////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		var m_data = new FormData();
		m_data.append( 'expense_head', $('#ex').val());
		m_data.append( 'invoice_ref', $('#ref').val());
		m_data.append( 'party', $('#ph').val());
		m_data.append( 'amount', $('#ia').val());
		m_data.append( 'desc', $('#des').val());
		m_data.append( 'posting', $('#pd').val());
		m_data.append( 'due', $('#due').val());
		m_data.append( 'inv_date', $('#date').val());
			
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "expense_tracker_json",
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



<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>




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
<a href="<?php echo $webroot_path; ?>Expensetrackers/expense_tracker_view" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div>
















