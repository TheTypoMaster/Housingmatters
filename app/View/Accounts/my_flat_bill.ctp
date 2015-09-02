

<?php
foreach($result_society as $data){
	$society_name=$data["society"]["society_name"];
	$society_reg_num=$data["society"]["society_reg_num"];
	$society_address=$data["society"]["society_address"];
	$society_email=$data["society"]["society_email"];
	$society_phone=$data["society"]["society_phone"];
}

$result_opening_balance= $this->requestAction(array('controller' => 'Incometrackers', 'action' => 'fetch_opening_balance_via_user_id'),array('pass'=>array($s_user_id)));



?>
 <style>
#report_tb th{
	font-size: 14px !important;background-color:#C8EFCE;padding:5px;border:solid 1px #55965F;text-align: left;
}
#report_tb td{
	padding:5px;
	font-size: 15px;border:solid 1px #55965F;background-color:#FFF;
}
table#report_tb tr:hover td {
background-color: #E6ECE7;
}
</style>



<div align="center">
	<table>
		<tr>
		<td>
		<select class="m-wrap" data-placeholder="Choose a Category"  id="flat_select_box">
			<option value="" style="display:none;" >Select...</option>
			<?php foreach($multiple_flat as $flat_data){
				//wing_id via flat_id//
				$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array($flat_data[1])));
				foreach($result_flat_info as $flat_info){
					$wing_id=$flat_info["flat"]["wing_id"];
				}
				
				$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_data[1])));
			?>
			<option value="<?php echo $flat_data[1]; ?>"><?php echo $wing_flat; ?></option>
			<?php } ?>
		</select>
		</td>
		<td><input class="date-picker m-wrap medium" id="from" data-date-format="dd-mm-yyyy" name="from" placeholder="From" style="background-color:white !important;" value="<?php echo date("d-m-Y",strtotime($from)); ?>" type="text"></td>
		<td><input class="date-picker  m-wrap medium" id="to" data-date-format="dd-mm-yyyy" name="to" placeholder="To" style="background-color:white !important;" value="<?php echo date("d-m-Y",strtotime($to)); ?>" type="text"></td>
		<td valign="top"><button type="button" name="sub" class="btn yellow" id="go">Go</button></td>
		</tr>
	</table>
</div>



<br/>
<div style="width:80%;margin:auto;overflow:auto;background-color:#FFF;padding:5px;display:none;border:solid 1px #ccc;" id="result_statement" >
	
</div>

<script>
$(document).ready(function() {
	$("#go").live('click',function(){
		var flat_id=$("#flat_select_box").val();
		var from=$("#from").val();
		var to=$("#to").val();
		$("#result_statement").show();
		$("#result_statement").html('<div align="center"><h4>Loading...</h4></div>').load('my_flat_bill_ajax/'+from+'/'+to+'/'+flat_id);
	});
});
</script>