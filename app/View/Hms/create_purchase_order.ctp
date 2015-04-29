<center>
<a href="create_purchase_order" class="btn red">Create</a>
<a href="" class="btn blue">View</a>
</center>
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
<select class="m-wrap span9" id="itm">
<option value=""></option>
<option value="1">Main Item1</option>
<option value="2">Main Item2</option>
</select>
</div>
<br />



<label style="font-size:14px;">Sub Item<span style="color:red;">*</span></label>
<div class="controls">
<select class="m-wrap span9" id="itm">
<option value=""></option>
<option value="1">sub Item1</option>
<option value="2">sub Item2</option>
</select>
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


<label style="font-size:14px;">PO Issue<span style="color:red;">*</span></label>
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

  <script>
$(document).ready(function() { 
	$('form').submit( function(ev){
	ev.preventDefault();
		
		var m_data = new FormData();
		m_data.append( 'ac_gr', $('#go').val());
		m_data.append( 'prt_ac', $('#usr').val());
		m_data.append( 'ac_head', $('#acn').val());
		m_data.append( 'tra_dat', $('#date').val());
		m_data.append( 'amt', $('#amt').val());
		m_data.append( 'desc', $('#narr').val());
				
		$(".form_post").addClass("disabled");
		$("#wait").show();
			
			$.ajax({
			url: "purchase_order_json",
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







<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////  ?>