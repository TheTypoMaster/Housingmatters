<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>



<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////// ?>            
<table  align="center" border="1" bordercolor="#FFFFFF" cellpadding="0">
<tr>
<td><a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn" rel='tab'>Selection of Income Heads</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_setup" class="btn" style="font-size:16px;" rel='tab'>Terms & Condition</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_rate_card" class="btn" style="font-size:16px;" rel='tab'>Rate Card</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn yellow" style="font-size:16px;" rel='tab'>Non Occupancy Charges</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_penalty" class="btn" style="font-size:16px;" rel='tab'>Penalty Option</a>
</td>
</tr>
</table> 
<br />            
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>  
<br /><br />
<center>
<form method="post">
<div id="error_msg" style="width:80%;"></div>
<table class="table table-bordered" style="width:80%; background-color:white;">
<tr>
<th style="text-align:center;">Flat Type</th>
<th style="text-align:center;" colspan="3">Non Occupancy charges(Leased Only)</th>
</tr>

<?php
$n = 0;
foreach($cursor1 as $collection)
{
$noc_ch = "";	
$n++;
$noc_ch = @$collection['flat_type']['noc_charge'];
$flat_type_id = (int)$collection['flat_type']['flat_type_id'];
//$flat_name = $collection['flat_type']['flat_name'];	
$auto_id = 	(int)$collection['flat_type']['auto_id'];
$result = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($flat_type_id)));	
foreach($result as $collection)
{
$flat_name = $collection['flat_type_name']['flat_name'];	
}
?>
<tr>
<th style="text-align:center;"><?php echo $flat_name; ?></th>
<td style="text-align:center;">
<?php
$type = $noc_ch[0];
if($type != 4)
{
$amt = $noc_ch[1];	
}
else
{
$amt = "";	
}
?>
<select name="ctp<?php echo $auto_id; ?>" class="m-wrap medium go" id="tp<?php echo $n; ?>">
<option value="" style="display:none;">Select</option>
<option value="1" <?php if($type == 1) { ?> selected="selected" <?php } ?>>Lump Sum</option>
<option value="2" <?php if($type == 2) { ?> selected="selected" <?php } ?>>Per Square Feet</option>
<option value="3" <?php if($type == 3) { ?> selected="selected" <?php } ?>>Flat Type</option>
<option value="4" <?php if($type == 4) { ?> selected="selected" <?php } ?>>10% of Maintanance Charge</option>
</select>

<input type="text" name="amt<?php echo $auto_id; ?>" <?php if($type == 4) { ?> disabled="disabled" <?php } ?>  class="m-wrap medium" id="tx<?php echo $n; ?>" value="<?php echo $amt; ?>"/>
<input type="hidden" value="<?php echo $auto_id; ?>" id="fltp<?php echo $n; ?>" />
</td>
</tr>
<?php
}
?>
<input type="hidden" value="<?php echo $n; ?>" id="count" />
</table>
<br />
<div style="width:100%">
<button type="submit" class="btn green form_post" name="sub" submit_type="sub">Submit</button>
</div>
<input type="hidden" value="<?php echo $n; ?>" id="cnt" />

<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>NOC Charges</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn blue" rel='tab'>No</a>
<button type="submit" class="btn blue form_post" submit_type="con">Yes</button>
</div>
</div>
</div> 



</form>
</center>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<script>
$(document).ready(function() {
$(".go").live('change',function(){
var count = document.getElementById("cnt").value;		
for(var i=1; i<=count; i++)
{
var tp = document.getElementById("tp" + i).value;
if(tp == 4)
{
$("#tx" + i).value = "none";
$("#tx" + i).attr('disabled','disabled');
}
else
{
$("#tx" + i).removeAttr('disabled');	
}
}
});
});
</script>	

<?php //////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function() {
	$(".form_post").bind('click', function(e){
		$(".form_post").removeClass("clicked");
		$(this).addClass("clicked");
	});
 
	$('form').submit( function(ev){
	ev.preventDefault();
	if( $(this).find(".clicked").attr("submit_type") === "sub" ){
			var post_type=1;
		}
		if( $(this).find(".clicked").attr("submit_type") === "con" ){
			var post_type=2;
		}
		var hidden=$("#cnt").val();
		var ar = [];
		for(var i=1;i<=hidden;i++)
		{
		var fltp = $("#fltp"+i).val();
		var type = $("#tp"+i).val();
		if(type != 4)
		{
		var amt = $("#tx"+i).val();
		}
		if(type != 4)
		{
		ar.push([type,amt,fltp]);
		}
		else
		{
		ar.push([type,fltp]);	
		}
		var myJsonString = JSON.stringify(ar);
		}
		
		var abc = JSON.stringify(post_type);
			
			$.ajax({
			url: "noc_json?q="+myJsonString+"&b="+abc,
			dataType:'json',
			}).done(function(response) {
			
				if(response.type == 'error'){  
					output = '<div class="alert alert-error">'+response.text+'</div>';
					$("#submit").removeClass("disabled").text("submit");
					$("html, body").animate({
					 scrollTop:0
					 },"slow");
				}
				if(response.type=='succ'){
				$("#shwd").show();
				$(".success_report").show().html(response.text);
			    }
				
				if(response.type=='okk'){
				$("#shwd").hide();
				}
				
				$("#error_msg").html(output);
			});

	 
	});
});

</script>































          
            