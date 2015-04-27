
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<div style="background-color:#fff;padding:5px;width:96%;margin:auto; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Post Purchase Order</h4>

<form method="post">
<div class="row-fluid">
<div class="span6">

<label style="font-size:14px;">R&Q Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="date" id="date">
</div>
<br />


<label style="font-size:14px;">Item<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="itm" id="itm">
</div>
<br />


<label style="font-size:14px;">Servece Description<span style="color:red;">*</span></label>
<div class="controls">
<textarea   rows="4" name="desc" class="m-wrap span9" style="resize:none;" id="desc"></textarea>
</div>
<br />
</div>

<div class="span6">

<label style="font-size:14px;">Unit of Measurement<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="unt" id="unt">
</div>
<br />


<label style="font-size:14px;">Sent To<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="unt" id="unt">
</div>
<br />


<label style="font-size:14px;">PO<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" class="m-wrap span9" name="unt" id="unt">
</div>
<br />

















</div>
</div>

<hr/>
<button type="submit" class="btn form_post" style="background-color: #09F; color:#fff;" value="xyz">Submit</button>

<div style="display:none;" id='wait'><img src="<?php echo $webroot_path; ?>as/fb_loading.gif" /> Please Wait...</div>
<br /><br />
</form>
</div>




<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>