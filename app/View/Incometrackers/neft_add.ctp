<div class="hide_at_print">
<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
</div>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
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
<a href="<?php echo $webroot_path; ?>Incometrackers/master_noc" class="btn" style="font-size:16px;" rel='tab'>Non Occupancy Charges</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/it_penalty" class="btn" style="font-size:16px;" rel='tab'>Penalty Option</a>
</td>
<td>
<a href="<?php echo $webroot_path; ?>Incometrackers/neft_add" class="btn yellow" style="font-size:16px;" rel='tab'>Add NEFT</a>
</td>
</tr>
</table> 
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post NEFT Detail</h4>
<form method="post" id="contact-form">
<div class="row-fluid">
<div class="span6">

<label style="font-size:14px;">Account Name<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="acno" id="acno" value="">
<label id="acno"></label>
</div>
<br />

<label style="font-size:14px;">Bank Name<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="bank_name" id="bnk" />
<label id="bnk"></label>
</div>
<br />

</div>
<div class="span6">

<label style="font-size:14px;">Branch<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="branch" class="m-wrap span9" id="bnch"/>
<label id="bnch"></label>
</div>
<br />


<label style="font-size:14px;">IFSC Code<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="ifsc" id="cdd"/>
<label id="cdd"></label>
</div>
<br />


</div>
</div>
<button type="submit" class="btn green" name="sub" value="xyz">Submit</button>
<a href="<?php echo $webroot_path; ?>Incometrackers/neft_add" class="btn" rel='tab'>Reset</a>
</form>
</div>

<script>
$(document).ready(function(){

jQuery.validator.addMethod("notEqual", function(value, element, param) {
return this.optional(element) || value !== param;
}, "Please choose Other value!");	


$.validator.setDefaults({ ignore: ":hidden:not(select)" });

$('#contact-form').validate({
errorElement: "label",
//place all errors in a <div id="errors"> element
errorPlacement: function(error, element) {
//error.appendTo("label#errors");
error.appendTo('label#' + element.attr('id'));
},

rules: {

acno: {
required: true
},


bank_name: {
required: true
},

branch: {
required: true
},

ifsc: {
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







































































