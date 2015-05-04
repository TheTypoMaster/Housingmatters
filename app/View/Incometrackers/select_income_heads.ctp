<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<table  align="center" border="1" bordercolor="#FFFFFF" cellpadding="0">
<tr>
<td><a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn yellow" rel='tab'>Selection of Income Heads</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_setup" class="btn" style="font-size:16px;" rel='tab'>Terms & Condition</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_rate_card" class="btn" style="font-size:16px;" rel='tab'>Rate Card</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn" style="font-size:16px;" rel='tab'>Non Occupancy Charges</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_penalty" class="btn" style="font-size:16px;" rel='tab'>Penalty Option</a>
</td>
</tr>
</table> 
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array(7)));			
foreach($result1 as $collection)
{
$ac_name = $collection['ledger_account']['ledger_name'];
$ac_id = (int)$collection['ledger_account']['auto_id'];		
if($ac_id != 43 && $ac_id != 39 && $ac_id != 40)
{
$income_head_arr1[] = (int)$ac_id;	
}
}
foreach($cursor3 as $collection)
{
$income_head_selected_arr = $collection['society']['income_head'];
}
if(!empty($income_head_selected_arr))
{
$income_head_arr2 = array_diff($income_head_arr1,$income_head_selected_arr);
}
else
{
$income_head_arr2 = $income_head_arr1;	
}
foreach($income_head_arr2 as $data)
{
$income_arrr[] = $data;
}
for($r=0; $r<sizeof($income_arrr); $r++)
{
echo $f = (int)$income_arrr[$r];
}
?>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto; height:400px;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Select Income Heads for Bill Charges</h4>
<form method="post" id="contact-form">
<div class="row-fluid">
<div class="span6">
<br />      
       
<label style="font-size:14px;">Select Income Heads<span style="color:red;">*</span></label>
<div class="controls">
<select data-placeholder="Select Account Heads"  name="i_head[]" id="i_head" class="m-wrap span9 chosen" multiple="multiple" tabindex="6">	
<option value="" style="display:none;">Select</option>
<?php
for($r=0; $r<sizeof($income_arrr); $r++)
{ 
$income_id = (int)$income_arrr[$r];

$ledgerac = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_id)));			
foreach($ledgerac as $collection2)
{
$ac_name = $collection2['ledger_account']['ledger_name'];
$ac_id = (int)$collection2['ledger_account']['auto_id'];		
}
?>
<option value="<?php echo $income_id; ?>"><?php echo $ac_name; ?></option>
<?php } ?>
</select>
<label report="head" class="remove_report"></label>
</div>
<br />        
<a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn" rel='tab'>Cancel</a>
<button type="submit" class="btn green" name="sub">Submit</button>
</div>
<div class="span6">
<br />
<center>
<div style="height:350px;">
<table class="table table-bordered table-stripped" style="width:100%; overflow:Y-scroll;">
<tr>
<th>Sr #</th>
<th>Account Name</th>
</tr>
<?php 
$m=0;
foreach($cursor3 as $collection)
{
$income_head_arr = @$collection['society']['income_head'];
}
$m=0;
for($i=0; $i<sizeof(@$income_head_arr); $i++)
{
$m++;
$income_head_id = (int)$income_head_arr[$i];	
$result1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch2'),array('pass'=>array($income_head_id)));	
foreach($result1 as $collection)
{
$income_head_name = $collection['ledger_account']['ledger_name'];	
}
?>
<tr>
<td><?php echo $m; ?></td>
<td><?php echo $income_head_name; ?></td>
</tr>
<?php } ?>
</table>
</div>
</center>
</div>
</div>
</form> 
</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

  <script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		var abc = $("#i_head").val();
		
		
		var m_data = new FormData();
		m_data.append( 'head',abc);
		
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "select_income_head_json",
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
<h3 id="myModalLabel3" style="color:#999;"><b>Selection of Income Head</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 







