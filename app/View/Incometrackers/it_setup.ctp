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
<td><a href="<?php echo $webroot_path; ?>Incometrackers/select_income_heads" class="btn" rel='tab'>Selection of Income Heads</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_setup" class="btn yellow" style="font-size:16px;"  rel='tab'>Terms & Condition</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_rate_card" class="btn" style="font-size:16px;"  rel='tab'>Rate Card</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn" style="font-size:16px;"  rel='tab'>Non Occupancy Charges</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_penalty" class="btn" style="font-size:16px;"  rel='tab'>Penalty Option</a>
</td>
</tr>
</table> 
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

			<div style="width:70%; margin-left:15%;">
			<div class="row-fluid"  >
			<div class="span12">
			<div class="portlet box green" style="border:solid 1px #ffb848;">
			<div class="portlet-body form">
			<h3 class="block"></h3>

           <form  class="form-horizontal" method="post" id="contact-form">

			<div class="control-group ">
			<div class="controls">
			<label class="" style="font-size:14px;" >Terms & Conditions</label>
			<textarea class="span10 m-wrap" name="terms" style="resize:none; height:150px;" rows="3" id="tem" placeholder="Please Type Terms & Condition"></textarea>
			 
            <label id="tem"></label>
            </div>
			</div>
			<div class="form-actions">
			<input type="submit" class="btn green" value="Submit" name="sub">
			</div>
            </form>

			</div>
			</div>
			</div>
			</div>
			</div>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div class="portlet box yellow " style="width:70%; margin-left:15%; border:solid 1px #ffb848;">
<div class="portlet-body">
								
                                

<table class="table table-bordered table-hover">
<thead>
<tr>
<th style="width:5%;">#</th>
<th style="width:95%;">Terms & Conditions</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tbody>
								
								
<?php
foreach ($cursor2 as $collection) 
{
$terms_con = @$collection['society']['terms_conditions'];
}
$w=0;
for($i=0; $i<sizeof($terms_con); $i++)
{
$w++;
$terms_name = $terms_con[$i];
?>
					
<tr>
<td style="text-align:left;"><?php echo $w; ?></td>
<td style="text-align:left;"><?php echo $terms_name; ?></td>
<td style="text-align:center;">
<a href="#myModal2<?php echo $i; ?>" role="button" class="btn mini purple" data-toggle="modal">Edit</a>
</td>
<td style="text-align:center;">
<a href="#myModal<?php echo $i; ?>" role="button" class="btn mini black" data-toggle="modal">Delete</a>
</td>
</tr>
<?php } ?>
<?php ///////////////////////////////////////////////////////////////////////////////////// ?>
<?php

?>

<?php
for($j=0; $j<sizeof($terms_con); $j++)
{
$terms_edit_name = $terms_con[$j];	?>
<form method="post">
<div id="myModal<?php echo $j; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display: block;">
<div class="modal-header">
<center>
<h3 style="color:#999;"><b>Terms and Condition</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<input type="hidden" value="<?php echo $j; ?>" name="arr_id" />
<h4><b>Are You Sure</b></h4>
</center>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn blue" name="del">Confirm</button>
</div>
</div>
</form>
<?php } ?>  




<?php
for($j=0; $j<sizeof($terms_con); $j++)
{
$terms_edit_name = $terms_con[$j];	?>
<form method="post">
<div id="myModal2<?php echo $j; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="false" style="display: block;">
<div class="modal-header">
<center>
<h3 style="color:#999;"><b>Terms and Condition</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<input type="hidden" value="<?php echo $j; ?>" name="arr_id" />
<input type="text" class="m-wrap medium" name="name" value="<?php echo $terms_edit_name; ?>" />
</center>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn blue" name="edit">Confirm</button>
</div>
</div>
</form>
<?php } ?>






                              
                                
<?php ///////////////////////////////////////////////////////////////////////////////////////////// ?>






</tbody>
</table>

								
</div>
</div>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function(){
$.validator.setDefaults({ ignore: ":hidden:not(select)" });
$('#contact-form').validate({
errorElement: "label",
errorPlacement: function(error, element) {
error.appendTo('label#' + element.attr('id'));
},
rules: {
terms: {
required: true
},
},
highlight: function(element) {
$(element).closest('.control-group').removeClass('success').addClass('error');
},
success: function(element) {
element
.text('OK!').addClass('valid')
.closest('.control-group').removeClass('error').addClass('success');
}
});
}); 
</script>	

