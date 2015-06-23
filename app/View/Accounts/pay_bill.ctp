<?php
$default_date = date('d-m-Y');
?>
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Bill Payment Detail</h4>
<form method="post">
<div class="row-fluid">
<div class="span6">


<label style="font-size:14px;">Transaction Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date" value="<?php echo $default_date; ?>">
</div>
<br />

<label style="font-size:14px;">Bank Name<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="bank_name" />
</div>
<br />

<label style="font-size:14px;">Mobile<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="mobile" />
</div>
<br />

<label style="font-size:14px;">Bill Number:<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="bill_no" />
</div>
<br />

<label style="font-size:14px;">Pay Amount<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="amt" />
</div>
<br />

</div>

<div class="span6">


<label style="font-size:14px;">Paying Mode<span style="color:red;">*</span></label>
<div class="controls">
<select class="m-wrap span9" name="mode" onchange="show(this.value)">
<option value="" style="display:none;">Select</option>
<option value="1"> By Cheque</option>
<option value="2"> By Cash</option>
</select>
</div>
<br />


<div id="one" class="hide">
<label style="font-size:14px;">Cheque Number<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="chq_no" class="m-wrap span9" />
</div>
<br />
</div>

<label style="font-size:14px;">Branch<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="branch" class="m-wrap span9" />
</div>
<br />


<label style="font-size:14px;">Account Number<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="acno" class="m-wrap span9" />
</div>
<br />





</div>
</div>
<button type="submit" class="btn green" name="ptp_add" value="xyz" id="vali">Submit</button>
<a href="<?php echo $webroot_path; ?>Accounts/pay_bill" class="btn" rel='tab'>Reset</a>
<a href="<?php echo $webroot_path; ?>Accounts/my_flat_bill" class="btn" rel='tab'>Back</a>
</form>
</div>



<script>
function show(g)
{
if(g == 1)
{
$("#one").show();	
}
else
{
$("#one").hide();	
}
}
</script>






