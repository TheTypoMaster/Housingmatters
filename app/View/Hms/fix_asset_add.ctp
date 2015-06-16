<?php //////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
<center>
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_add" class="btn red" rel='tab'>Add</a>
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_view" class="btn blue" rel='tab'>View</a>
</center>
 	
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Fix Asset</h4>			
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
<form method="post">
<div class="row-fluid">
<div class="span6">		

<label style="font-size:14px;">Asset Category<span style="color:red;">*</span>  <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select asset category"> </i></label>
<div class="controls">
<select name="asset_category" class="m-wrap span9 chosen" id="as_cat">
<option value="">Select category</option>
<?php
foreach ($cursor1 as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$category = $collection['ledger_account']['ledger_name'];	
if($auto_id != 18)
{	
?>
<option value="<?php echo $auto_id; ?>"><?php echo $category; ?></option>
<?php }} ?>
</select>
<label report="cat" class="remove_report"></label>
</div>
<br />				


<label class="" style="font-size:14px;">Name of Supplier/Vendor<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select name of supplier/vendor"> </i></label>
<div class="controls">
<select name="vendor" class="m-wrap span9 chosen" id="supp">
<option value="">Select</option>
<?php
foreach ($cursor2 as $db) 
{
$g_id=(int)$db['ledger_sub_account']["auto_id"];
$vendor_name=$db['ledger_sub_account']["name"];
?>
<option value="<?php echo $g_id; ?>"><?php echo $vendor_name; ?></option>
<?php } ?>
</select>
<label report="sup" class="remove_report"></label>
</div>
<br />	







<label style="font-size:14px;">Asset Name<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please fill asset name"> </i> </label>
<div class="controls">
<input type="text" class="m-wrap span9" name="name" id="name">
<label report="nam" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Asset Description</label>
<div class="controls">
<textarea  rows="4" name="description" class="m-wrap span9" style="resize:none;" id="desc"></textarea>
</div>
<br />


</div>
<div class="span6">	

<label style="font-size:14px;">Date of Purchase<span style="color:red;">*</span>  <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select purchase date"> </i></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="purchase_date" id="pur_dat">
<label report="dat" class="remove_report"></label>
</div>
<br />				


<label style="font-size:14px;">Cost of Purchase<span style="color:red;">*</span>  <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please fill purchase of cost"> </i></label>
<div class="controls">
<input type="text" class="m-wrap span9"  name="cost" id="cost">
<label report="cos" class="remove_report"></label>
</div>
<br />


<label style="font-size:14px;">Warranty Period</label>
<div class="controls">
<input type="text" class="span4 m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="From*" name="from" id="fr">
<span> - </span>
<input type="text" class="span4  m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="to*" name="to" id="to">
</div>
<br />				



<label style="font-size:14px;">Maintanance Schedule<span style="color:red;">*</span>  <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please fill schedule of maintanance"> </i></label>
<div class="controls">
<input type="text" name="schedule" class="m-wrap span9" id="main">
<label report="man" class="remove_report"></label>
</div>
<br />


</div>
</div>

<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_add" style="background-color: #09F;color:#fff;" class="btn" rel='tab'>Reset</a>
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
		m_data.append( 'as_cat', $('#as_cat').val());
		m_data.append( 'supp', $('#supp').val());
		m_data.append( 'name', $('#name').val());
		m_data.append( 'desc', $('#desc').val());
		m_data.append( 'pur_dat', $('#pur_dat').val());
		m_data.append( 'cost', $('#cost').val());
		m_data.append( 'from', $('#fr').val());
		m_data.append( 'to', $('#to').val());
		m_data.append( 'main', $('#main').val());		
				
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "fix_asset_json",
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


<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
			
<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Fixed Assets</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Hms/fix_asset_add" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div>             
            
            
            
            
            
            
            
            
            
            
            
            