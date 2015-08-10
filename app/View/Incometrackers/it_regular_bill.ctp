
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
<input type="hidden" id="fi" value="<?php echo $datef1; ?>" />
<input type="hidden" id="ti" value="<?php echo $datet1; ?>" />
<input type="hidden" id="cn" value="<?php echo $count; ?>" />
<input type="hidden" id="fb" value="<?php echo @$datefb; ?>" />
<input type="hidden" id="tb" value="<?php echo @$datetb; ?>" />
<?php
$default_date = date('d-m-Y');
?>
<?php ///////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php 
foreach($socct1 as $data)
{
$society_registration_number = @$data['society']['society_reg_num'];
$society_address = @$data['society']['society_address'];
$society_income_head = @$data['society']['income_head'];
}
if(empty($society_registration_number) || empty($society_address))
{
$society_detail = "NOT";
}
else
{
$society_detail = "YES";	
}
?>
<?php //////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$income_head_detail = 'YES'; 
foreach($flat_tpp as $data2)
{
$charge = @$data2['flat_type']['charge'];
$noc_charge = @$data2['flat_type']['noc_charge'];
if(empty($charge) && empty($noc_charge))
{
$income_head_detail = "NOT";
break;	
}
if(empty($society_income_head) && empty($charge))
{
$income_head_detail = "YES";
break;
}
}

if($income_head_detail == 'YES')
{
$charge_count = sizeof(@$charge);
$society_income_head_count = sizeof($society_income_head);
if($charge_count != $society_income_head_count)
{
$income_head_detail = "NOT";
}
}
if($income_head_detail == 'YES')
{
for($t=0; $t<sizeof(@$charge); $t++)
{
$charge2 = $charge[$t];
$income_head_arr[] = $charge2[0];
}
$rrr = @array_diff(@$income_head_arr,@$society_income_head);
$count = sizeof($rrr);
if($count == 0)
{
$income_head_detail = "YES";	
}
else
{
$income_head_detail = "NOT";	
}
}

?>	
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
if($society_detail == 'YES')
{
if($income_head_detail == 'YES')	
{	
?>
<div style="background-color:#fff;padding:5px;width:100%; overflow:auto;" class="form_div">
<h4 style="color: #09F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;"><i class="icon-money"></i> Generate Regular Bill(Income Tracker)</h4>
<form method="post" id="contact-form">
<div class="row-fluid">
<div class="span6">

<label style="font-size:14px;">Billing Cycle<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select billing cycle"> </i></label>
<div class="controls">
<select name="bill_p" id="bp" class="m-wrap span7 chosen">
<option value="" style="display:none;">Select</option>
<?php
for($k=0; $k<sizeof($bill_period_arr); $k++)
{
$period_arr = $bill_period_arr[$k];
$priod_name = $period_arr[0];
$period_id = $period_arr[1];	
?>
<option value="<?php echo $period_id; ?>"><?php echo $priod_name; ?></option>
<?php
}
?>
</select>
<label id="bp"></label>
</div>
<br />



<label style="font-size:14px;">Billing Start Date<span style="color:red;">*</span></label>
<div class="controls">
<input type="text" name="from" class="m-wrap span7 date-picker" data-date-format="dd-mm-yyyy" placeholder="Bill Date" id="from" value="<?php echo $default_date; ?>"/>
<label id="from"></label>
<div id="result11"></div>
</div>
<br />


<label style="font-size:14px; color:red;">Payment Due Date<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select payment due date "> </i></label>
<div class="controls">
<input type="text" class="m-wrap span7 date-picker" data-date-format="dd-mm-yyyy" placeholder="Due Date" name="due_date" id="due" style="color:red; border-color:red;">
<label id="due" ></label>
<div id="result12"></div>
</div>
<br />



<label class="" style="font-size:14px;">Bill For<span style="color:red;">*</span><i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose bill "> </i></label>
<div class="controls">
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="bill_for" value="1" style="opacity: 0;" id="bill_for"  onclick="wing()"></span></div>
Wing Wise
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="bill_for" value="2" style="opacity: 0;" id="bill_for" onclick="flat()"></span></div>
All Units
</label>
<label id="bill_for"></label>
</div>       
<br />   


<div id="show_bill_for" class="hide">
<div class="controls">
<label style="font-size:14px;">Select Wing<span style="color:red;">*</span></label>
<?php
foreach($cursor5 as $collection)
{
$wing_id = (int)$collection['wing']['wing_id'];	
$wing_name = $collection['wing']['wing_name'];		
?>
<label class="checkbox">
<div class="checker" id="uniform-undefined"><span>
<input type="checkbox" value="<?php echo $wing_id; ?>" style="opacity: 0;" name="wing<?php echo $wing_id; ?>" id="win"></span></div><?php echo $wing_name; ?> 
</label>
<?php } ?>
<label id="chk_vali"></label>
</div>
</div>        
<br />    


</div>
<div class="span6">
<div class="control-group">		
<div class="controls">
<label class="" style="font-size:14px;">Penalty<i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose penalty yes/no "> </i></label>
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
<br />



<div class="control-group">
<div class="controls">
<label style="font-size:14px;">Billing Description</label>
<textarea class="span9 m-wrap" name="description" id="description" style="resize:none;" rows="3"></textarea>
<label id="description"></label>
</div>
</div>

</div>
</div>
<hr />
<button type="submit" class="btn green" value="Generate Bill" name="sub1" id="go" onclick="vali()">Preview Bill</button>
<a href="it_regular_bill" class="btn">Reset</a>
</form>	
<br /><br />
</div>

<?php //////////////////////////////////////////////////////////////////////////////////////////////// ?>		
<?php
/*
<div style="width:70%; margin-left:15%;">
<div class="row-fluid">
<div class="span12">
<div class="portlet box green" style="border:solid 1px #ffb848;">
<div class="portlet-body form">
<h3 class="block"></h3>		
<form class="form-horizontal" method="post" id="contact-form" novalidate>		
<div class="control-group">
<div class="controls">
</div>
</div>	


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
        
        */
        
        ?>
        
        
<?php //////////////////////////////////////////////////////////////////////////////////////////////?>		

<?php ///////////////////////////////////////////////////////////////////////////////////////////?>		
		
<script>
$.validator.addMethod('requirecheck1', function (value, element) {
	 return $('.requirecheck1:checked').size() > 0;
}, 'Please select at list one wing.');

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
	   
		   bill_for: {
		    required: true
	      },
		  
		  
		
	    },
		submitHandler: function (form) {
			$("#go").hide();
			$("#submiting_div").show();
			form.submit();
			//return true; // required to block normal submit since you used ajax
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
		$("#go").bind('click',function(){
	
		var from1 = document.getElementById("from").value;
		var per_tp = document.getElementById("bp").value;
		var date = from1.split("-"); 
		var d = date[0];
		var m = date[1];
		var y = date[2];
		var date2 = m + "/" + d + "/" + y; 
		//alert(date2);
		var datobj=new Date(date2);
			
		if(per_tp == 1)
		{
		var to1 = new Date(date2).addMonths(1);  //
		to1 = to1.setDate(to1.getDate()-1);
		to1 =  new Date(to1);
		var to1 = to1.toString("dd-MM-yyyy");
		}
		else if(per_tp == 2)
		{
		var to1 = new Date(date2).addMonths(2);  //
		to1 = to1.setDate(to1.getDate()-1);
		to1 =  new Date(to1);
		var to1 = to1.toString("dd-MM-yyyy");
		}
		else if(per_tp == 3)
		{
		var to1 = new Date(date2).addMonths(3);  //
		to1 = to1.setDate(to1.getDate()-1);
		to1 =  new Date(to1);
		var to1 = to1.toString("dd-MM-yyyy");
		}
		else if(per_tp == 4)
		{
		var to1 = new Date(date2).addMonths(6);  //
		to1 = to1.setDate(to1.getDate()-1);
		to1 =  new Date(to1);
		var to1 = to1.toString("dd-MM-yyyy");
		}
		else if(per_tp == 5)
		{
		var to1 = new Date(date2).addMonths(12);  //
		to1 = to1.setDate(to1.getDate()-1);
		to1 =  new Date(to1);
		var to1 = to1.toString("dd-MM-yyyy");
		}
	
		
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
		
		
var bb = $('input[type=radio]:checked').val();
if(bb == 1)
{       
if($('input[type=checkbox]:checked').length == 0)
{
$('#chk_vali').html('<p style="color:red;">Select at list One wing</p>'); return false;
}		
else
{
$('#chk_vali').html('<p style="color:red;"></p>');	
}
}		
		
		
		

});
});
		
		
		</script>
        
<script>        
function wing()
{
$("#show_bill_for").show();	
}
function flat()
{
$("#show_bill_for").hide();	
}
</script>   

<?php }} ?>
<?php 
if($society_detail == 'NOT')
{
?>	     
<br /><br />
<center>
<div  class="alert alert-info" style="width:70%;">
<h4><b>
Dear Sir, For Regular Bill genereation you have to full fill the Society Registartion Number and Society Address at Society Setup. Without these detail you can not generate Regular Bill, So Please fill these details.
</b></h4>
</div>
</center>
<?php } ?>
<?php 
if($income_head_detail == 'NOT')
{
?>
<br /><br />
<center>
<div  class="alert alert-info" style="width:70%;">
<h4><b>
Dear Sir, For Regular Bill genereation you have to full fill the Non Occupancy Charges and Rate card at Accounting Setup. Without these detail you can not generate Regular Bill, So Please fill these details.
</b></h4>
</div>
</center>
<?php
}
?>






<div id="submiting_div" style="display:none;">
	<div class="modal-backdrop fade in"></div>
	<div class="modal" id="poll_edit_content">
		<div class="modal-body">
		<div align="center">
		<img src="<?php echo $webroot_path; ?>as/fb_loading.gif" style="height: 15px;" />
		<h4>Please Wait</h4>
		<h5>Your data is submiting to database.</h5>
		</div>
        </div>
	</div>
</div>
