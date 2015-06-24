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

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<!--
<br />
<center>
<div style="700px; background-color:white; overflow:auto;">
<br><Br><br>
<div style="width:80%; border:solid 1px; overflow:auto;">
<br>
<table border="0" style="width:15%; float:left;">
<tr>
<td><img src="<?php echo $webroot_path; ?>logo/Pacific_Bulb_Society_logo.jpg" height="60px;" width="130px;"></img></td>
</tr>
</table>
<table border="0" style="width:85%;">
<tr>
<th style="text-align:center;">
<p style="font-size:22px; padding-right:14%;">RADHE KRISHNA SOCIETY</p>
</th>
</tr>
<tr>
<th style="text-align:center; padding-right:14%;">Regn# &nbsp;&nbsp; HGFSDGHHH 66777 hdh</th>
</tr>
<tr>
<th style="text-align:center; padding-right:14%;">Sewashram Kothari Complex</th>
</tr>
</table>
</div>
<div style="width:80%; border:solid 1px; overflow:auto; border-top:none; border-bottom:none;">
<table border="0" style="width:60%; float:left;">
<tr>
<td style="text-align:left; width:17%;">
Name :
</td>
<td style="text-align:left;">Nikhilesh Vyas</td>
</tr>
<tr>
<td style="text-align:left;">Bill No. :</td>
<td style="text-align:left;">10001</td>
</tr>
<tr>
<td style="text-align:left;">Bill Date :</td>
<td style="text-align:left;">1/1/2015</td>
</tr>
<tr>
<td style="text-align:left;"></td>
<td style="text-align:left;"></td>
</tr>
</table>
<table border="0" style="width:39%; float:right;">
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align:left;">Flat/Shop No. :</td>
<td style="text-align:left;">Shop (101)</td>
</tr>
<tr>
<td style="text-align:left;">Area:</td>
<td style="text-align:left;">2000 Sq Feet</td>
</tr>
<tr>
<td style="text-align:left;">Billing Period:</td>
<td style="text-align:left;">Jan Feb Mar</td>
</tr>
<tr>
<td style="text-align:left;">Due Date:</td>
<td style="text-align:left;">21/5/2015</td>
</tr>
</table>
</div>
<div style="width:80.2%; overflow:auto;">
<table border="1" style="width:100%; margine-left:2px; border-collapse:collapse;" cellspacing="0" cellpadding="0">
<tr>
<th style="width:80%; text-align:left; padding-left:2%;">Particulars</th>
<th style="text-align:right; padding-right:1.2%;" >Amount (Rs.)</th>
<tr>
<tr>
<td valign="top" style="height:200px;">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:left; padding-left:2%;">Maintanance Bill</td>
</tr>
<tr>
<td style="text-align:left; padding-left:2%;">dfgdfgdfgdfgl</td>
</tr>
<tr>
<td style="text-align:left; padding-left:2%;">dfgdfgdfgdf</td>
</tr>
</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:right;  padding-right:8%;">10000</td>
</tr>
<tr>
<td style="text-align:right; padding-right:8%;">20000</td>
</tr>
<tr>
<td style="text-align:right; padding-right:8%;">30000</td>
</tr>
</table>
</td>
</tr>
<tr>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td rowspan="4"></td>
<td style="text-align:right; padding-right:2%;">Total:</td>
</tr>
<tr>
<td style="text-align:right; padding-right:2%;">Interest:</td>
</tr>
<tr>
<td style="text-align:right; padding-right:2%;">Arrears:</td>
</tr>
<tr>
<th style="text-align:right; padding-right:2%;">Due For Payment:</th>
</tr>
</table>
</td>
<td valign="top">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:right; padding-right:8%;">50000</td>
</tr>
<tr>
<td style="text-align:right; padding-right:8%;">60000</td>
</tr>
<tr>
<td style="text-align:right; padding-right:8%;">30000</td>
</tr>
<tr>
<th style="text-align:right; padding-right:8%;">20000</th>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div style="width:80%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:70%; float:left;">
<tr>
<th style="text-align:left;">Description:</th>
</tr>
<tr>
<td style="text-align:left;">
kjdsfhdshfjksdhfkdhskfdhfjksdhfhkshfkkdshfksdhfkhsdhf
</td>
</tr>
</table> 
</div>
<div style="width:80%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<th style="text-align:left;">
Remarks:
</th>
</tr>
<tr>
<td style="text-align:left;">Thank You</td>
</tr>
<tr>
<td style="text-align:left;">Go Green</td>
</tr>
</table> 
</div>
<div style="width:80%; overflow:auto; border:solid 1px; border-top:none;">
<table border="0" style="width:100%;">
<tr>
<td style="text-align:left; valign:top;">
Society-Email:abc@gmail.com
</td>
<td style="text-align:right;">
<p style="font-size:18px;"><b>RADHE KRISHNA Society</b></p>
</td>
</tr>
<tr>
<td style="text-align:left;" valign="top">
Society-Phone:9799463210
</td>
<td style="text-align:right;">
<img src="<?php echo $webroot_path; ?>sig/RO968.jpg" height="60px;" width="130px;" style="margin-right:10%;"></img>
</td>
</tr>
<tr>
<td></td>
<td style="text-align:right;">
<p style="font-size:14px; margin-right:10%;"><b>Secratary</b></p>
</td>
</tr>
</table>
<br><br><br>
</div>
<br><br><br><br>
</div>


-->































































