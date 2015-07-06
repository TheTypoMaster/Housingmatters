<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

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
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/neft_add" class="btn" style="font-size:16px;" rel='tab'>Add NEFT</a>
</td>
</tr>
</table> 
<div align="center">
<a href="master_noc" class='btn blue' role="button" rel='tab'>Non Occupancy Charges</a>
<a href="master_noc_status" class='btn red' role="button"  rel='tab'>Non Occupancy Status</a>
</div>
<br/>
<form method="post">
<div align="right">
Select All <input type="checkbox" value="1"  style="opacity: 0;" class="chk" id="1">
</div>
<div style="background-color: #fff;">
<br/>
<table class="table table-striped table-bordered dataTable" id="sample_1" aria-describedby="sample_1_info" >
<thead>
<tr>
<th>Sr.n.</th>
<th>User Name</th>
<th>Unit</th>
<th>Is unit given on  lease ?</th>
</tr>
</thead>
<tbody>
<?php 
//pr($result_user);
$i=0;
foreach($result_user as $data)
{
	$i++;
	$user_name=$data['user']['user_name'];
	$user_id=(int)$data['user']['user_id'];
	$flat=(int)$data['user']['flat'];
	$wing=(int)$data['user']['wing'];
	$wing_flat= $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));
  $noc_flat= $this->requestAction(array('controller' => 'hms', 'action' => 'flat_fetch'),array('pass'=>array($flat)));
foreach($noc_flat as $dafa)
{
	$noc_type=$dafa['flat']['noc_ch_tp'];
}
	?>
	<tr>
	<td><?php echo $i ; ?></td>
	<td><?php echo $user_name ; ?></td>
	<td><?php echo $wing_flat ; ?></td>
	
	<td>
	<div class="controls">
	<label class="checkbox">
	<div class="checker" id="uniform-undefined">
	<span>
	<input type="checkbox" value="1" name='<?php echo $user_id; ?>' <?php if(@$noc_type==2) { ?> checked <?php } ?> style="opacity: 0;" class="check_all">
	</span>
	</div> 
	</label>
	</div>
	</td>
	</tr>
	<?php
}

?>
</tbody>
</table>
</div>
	<div class="">
	<button type="submit" class="btn blue"><i class="icon-ok"></i> Update</button>

	</div>
</form>


<script>
$(document).ready(function(){
$(".chk").live('click',function(){
var c=$(this).val();
value = +$('#'+c).is( ':checked' );
if(value==0)
{
$(".check_all").parent('span').removeClass('checked');
$(".check_all").removeAttr('checked','checked');
}
else
{
$(".check_all").parent('span').addClass('checked');
$(".check_all").attr('checked','checked');
}
});


})
</script>