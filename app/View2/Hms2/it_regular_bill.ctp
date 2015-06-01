<input type="hidden" id="fi" value="<?php echo $datef1; ?>" />
<input type="hidden" id="ti" value="<?php echo $datet1; ?>" />
<input type="hidden" id="cn" value="<?php echo $count; ?>" />
<input type="hidden" id="fb" value="<?php echo $datefb; ?>" />
<input type="hidden" id="tb" value="<?php echo $datetb; ?>" />

<?php ///////////////////////////////////////////////////////////////////////////////////////// ?>		
		
<table width="100%" border="1" bordercolor="#FFFFFF" cellpadding="0">
<tr>
<td style="width:25%">
<a href="it_regular_bill" class="btn red btn-block"   style="font-size:16px;"> Regular Bill</a>
</td>
<td style="width:25%">
<a href="it_supplimentry_bill" class="btn blue btn-block"  style="font-size:16px;">Supplementary Bill</a>
</td>
<td style="width:25%">
<a href="in_head_report" class="btn blue btn-block"  style="font-size:16px;">Reports</a>
</td>
<td style="width:25%">
<a href="select_income_heads" class="btn blue btn-block"  style="font-size:16px;">Set-Up</a>
</td>
</tr>
</table>
		
<?php ////////////////////////////////////////////////////////////////////////////////////////// ?>		
		
		<div style="width:70%; margin-left:15%;">
		<div class="row-fluid">
		<div class="span12">
		<div class="portlet box green" style="border:solid 1px #ffb848;">
		<div class="portlet-body form">
		<h3 class="block"></h3>		
  
<form class="form-horizontal" method="post" id="contact-form" novalidate>		


        <div class="control-group">
        <div class="controls">
        <label class="" style="font-size:14px;">Billing Cycle</label>
        <select name="bill_p" id="bp" class="m-wrap medium">
        <option value="" style="display:none;">Select</option>
        <?php
		foreach($cursor4 as $collection)
		{
		$auto_id = (int)$collection['bill_period']['auto_id'];
		$period = $collection['bill_period']['period_name'];
		?>
        <option value="<?php echo $auto_id; ?>"><?php echo $period; ?></option>
        <?php
		}
		?>
        </select>
        <label id="bp" ></label>
        </div>
        </div>	




<div class="controls">
<label class="" style="font-size:14px;">Bill Date</label>
<input type="text" name="from" class="m-wrap medium date-picker" data-date-format="dd-mm-yyyy" placeholder="Bill Date" id="from" />
<label id="from"></label>
</div>

<br />




		
		
		<!-- <div class="controls">
		<label class="" style="font-size:14px;">Period*</label>
		<div class="input-prepend"><span class="add-on">from</span></div>
		<input type="text" class="span3 m-wrap  m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="From*" name="from" id="from">
		<span> - </span>
		<div class="input-prepend"><span class="add-on">to</span></div>
		<input type="text" class="span3 m-wrap  m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="to*" name="to" id="to">
		<label id="from" ></label>
		<label id="to" ></label>
        <div id="result11"></div>
		</div><br/>		-->
		

<div class="control-group">
<div class="controls">
<label class="" style="font-size:14px; color:red;">Payment Due Date</label>
<input type="text" class="span3 m-wrap  m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="Due Date" name="due_date" id="due" style="color:red; border-color:red;">
<label id="due" ></label>
<div id="result12"></div>
</div>
</div>	




	<!--	<div class="control-group">
		<div class="controls">
		<label class="" style="font-size:14px;">Income-heads</label>
		<select data-placeholder="Account Heads"  name="i_head[]" id="i_head" class="chosen m-wrap large" multiple="multiple" tabindex="6">	
		<?php
		foreach ($cursor1 as $collection) 
		{
		$income_heads_id=$collection['income_head']["auto_id"];
		$income_heads_name=$collection['income_head']["ih_name"];
		?>
		<option value="<?php echo $income_heads_id; ?>"><?php echo $income_heads_name; ?></option>
		<?php } ?>
        <option value="43">Non Occupancy charges</option>
		</select>
		<label id="i_head"></label>
		</div>
		</div>		
		


		<div class="control-group">
		<div class="controls">
		<label class="" style="font-size:14px;">Taxes</label>
		<?php
		foreach ($cursor2 as $collection) 
		{
		
		$taxes_id=$collection['ledger_sub_account']["auto_id"];
		$taxes_name=$collection['ledger_sub_account']["name"];
		if($taxes_id != 33)
		{
		?>
		<label class="radio">
		<div class="radio" id="uniform-undefined">
		<span><input type="radio" name="tax" value="<?php echo $taxes_id; ?>" style="opacity: 0;" id="tax"></span>
		</div>
		<?php echo $taxes_name; ?>
		</label>
      
		<?php }} ?>
		<label id="tax"></label>
		</div>
		</div> -->

<div class="control-group">		
<div class="controls">
<label class="" style="font-size:14px;">Penalty</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="pen" value="1" style="opacity: 0;" id="pen"></span></div>
Yes
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="pen" value="2" style="opacity: 0;" id="pen"></span></div>
No
</label>
<label id="pen"></label>
</div>        
</div>        
        
        
        
        
        
        
		
		<div class="control-group ">
		<div class="controls">
        
		<label class="" style="font-size:14px;">Billing Description</label>
		<textarea class="span8 m-wrap" name="description" id="description" style="resize:none;" rows="3"></textarea>
		<label id="description"></label>
		</div>
		</div>
		
		
		<!-- <div class="control-group ">
		<div class="controls">
		<label class="" style="font-size:14px;">Terms and Condition</label>
		<select data-placeholder="Select Terms and Conditions"  name="terms[]" id="terms" class="chosen m-wrap large" multiple="multiple" tabindex="6">	
		<?php
		$q=0;
		foreach ($cursor3 as $collection) 
		{
		$q++;
		$terms_conditions = $collection['terms_condition']['terms_conditions'];
		$terms_conditions_id = (int)$collection['terms_condition']['terms_conditions_id'];
		?>
		<option value="<?php echo $terms_conditions_id; ?>"><?php echo $terms_conditions; ?></option>			
		<?php } ?>
		</select>
		<label id="terms"></label>
		</div>
		</div> -->
		
		<div class="form-actions">
		<button type="submit" class="btn green" value="Generate Bill" name="sub1" id="go" onclick="vali()">Preview Bill</button>
		<a href="it_regular_bill" class="btn">Reset</a>
		</div>
<?php //////////////////////////////////////////////////////////////////////////////////////////////?>		
		

	
	
<?php ///////////////////////////////////////////////////////////////////////////////////////////?>		
		
		
		
		</form>	

		</div>
		</div>
		</div>
		</div>
		</div>		

		
<script>
$.validator.addMethod('requirecheck1', function (value, element) {
	 return $('.requirecheck1:checked').size() > 0;
}, 'Please check at least one role.');

$.validator.addMethod('requirecheck2', function (value, element) {
	 return $('.requirecheck2:checked').size() > 0;
}, 'Please check at least one wing.');

$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param) 
});

$(document).ready(function(){
			var checkboxes = $('.requirecheck1');
			var checkbox_names = $.map(checkboxes, function(e, i) {
				return $(e).attr("name")
			}).join(" ");
			
			
			var checkboxes2 = $('.requirecheck2');
			var checkbox_names2 = $.map(checkboxes2, function(e, i) {
				return $(e).attr("name")
			}).join(" ");
			
			
			
	$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$('#contact-form').validate({
		
		 errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    }, 
	    groups: {
            asdfg: checkbox_names,
			qwerty: checkbox_names2
        },
		
		
		rules: {
			pen: {
			 required: true	
			},
			
	      from: {
	        required: true
	      },
		 		  due_date : {
			  required: true  
		  },
		 		   bill_p: {
	        required: true
	      },
		 
		   "terms[]": {
	        required: true
	      },
		 
		  "i_head[]": {
			 required: true
	      },
		 
	    },
		messages: {
	                from: {
	                    required: "Bill Date is Required."
	                },
					to: {
	                    required: "To date is required."
	                },
					file: {
						accept: "File extension must be gif or jpg",
	                    filesize: "File size must be less than 1MB."
	                },
					description: {
	                    maxlength: "Max 500 characters allowed."
	                }
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

<script>
		$(document).ready(function() {
		$("#go").live('click',function(){
        
		var from1 = document.getElementById("from").value;
		var per_tp = document.getElementById("bp").value;
		
		
		var fi = document.getElementById("fi").value;
		var ti = document.getElementById("ti").value;
		var cn = document.getElementById("cn").value;
		var fe = fi.split(",");
		var te = ti.split(",");
		
		
		var due1 = document.getElementById("due").value;
		var fb = document.getElementById("fb").value;
		var tb= document.getElementById("tb").value;
		
		var from = from1.split("-").reverse().join("-");
		var to = to1.split("-").reverse().join("-");
		var due = due1.split("-").reverse().join("-");
		if(from == "")
		{
		}
		else if(to == "")
		{
			
		}
		else if(Date.parse(to) <= Date.parse(from))
		{
       	$("#result11").load("regular_vali?ss=" + 1 + "");
        return false;
		}
		else if(Date.parse(tb) >= Date.parse(from))
		{
		$("#result11").load("regular_vali?ss=" + 5 + "");
        return false;	
		}
		else
		{
		$("#result11").load("regular_vali?ss=" + 11 + "");
       	}
		
		var nnn = 55;
		for(var i=0; i<cn; i++)
		{
		var fd = fe[i];
		var td = te[i]
		
		    if(from == "")
			{
				nnn = 555;
			break;	
			}
			else if(to == "")
			{
				nnn = 555;
				break;
			}
			else if(Date.parse(fd) <= Date.parse(from))
		     {
			 if(Date.parse(td) >= Date.parse(to))
			 {
				 nnn = 5;
				 break;
			 }
			 else
			 {
				 
			 }
        	 } 
			 }
			 
		
		if(nnn == 55)
		{
		$("#result11").load("regular_vali?ss=" + 2 + "");
        return false;	
		}
		else if(nnn == 555)
		{
			
		}
		else
		{
		$("#result11").load("regular_vali?ss=" + 12 + "");		
		}
		
		
		
		
		if(due == "")
		{
			
		}
		else if(Date.parse(due) <= Date.parse(from))	 
		{
		$("#result12").load("regular_vali?ss=" + 3 + "");
		return false;
		}
		else
		{
		$("#result12").load("regular_vali?ss=" + 13 + "");	 
		}
		 
		
		});
		});
		</script>