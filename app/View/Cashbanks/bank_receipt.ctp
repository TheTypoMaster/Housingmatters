<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>    
<input type="hidden" id="fi" value="<?php echo $datef1; ?>" />
<input type="hidden" id="ti" value="<?php echo $datet1; ?>" />
<input type="hidden" id="cn" value="<?php echo $count; ?>" />

<!--  Start Bank Receipt form Front End  -->
<center>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" class="btn yellow" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt_view" class="btn" rel='tab'>View</a>
<a href="#" class="btn purple" role="button" id="import">Import csv</a> 
</center>
<!---- Start Import Code -->
        <div id='suces'>
        <div id="error_msg"></div>
        <div id="myModal3" class="modal hide fade in" style="display:none;">
        <div class="modal-backdrop fade in"></div>
        <form id="form1" method="post" enctype="multipart/form-data">
        <div class="modal">
        <div class="modal-header">
        <h4 id="myModalLabel1">Import csv</h4>
        </div>
        <div class="modal-body">
        <input type="file" name="file" class="default" id="image-file">
        <label id="vali"></label>			
        <strong><a href="bank_receipt_import" download>Click here for sample format</a></strong>
        <br/>
        <h4>Instruction set to import receipts</h4>
        <ol>
        <li>Kindely delete second row, it is for example.</li>
        <li>All the field are compulsory.</li>
        <li>Wing and Flat number be valid as per society setting.</li>
        <li>Befor Import Bank Receipt Regular Bill must be generated</li>
        </ol>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn" id="close_div">Close</button>
        <button type="submit" class="btn blue import_btn">Import</button>
        </div>
        </div>
        </form>
        </div>
        </div>
        </div>
<!-- End Import Code -->


<div style="background-color:#FFF; overflow:auto; border:solid #CCC; width:100%;">
<h4 style="color: #03F;font-weight: 500;border-bottom: solid 1px #DAD9D9;padding-bottom: 10px;">&nbsp;&nbsp;&nbsp;<i class="icon-money"></i> Post Bank Receipt</h4>
        
        <div style="background-color:#FFF; width:48%; float:left;">
        <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp; Transaction date<span style="color:red;">*</span></label>
        <div class="controls">
        &nbsp;&nbsp;&nbsp; <input type="text" class="date-picker m-wrap span7" data-date-format="dd-mm-yyyy" name="transaction_date" placeholder="Transaction Date" style="background-color:white !important;" id="date" value="<?php echo $default_date; ?>">
        &nbsp;&nbsp;&nbsp; <label id="date"></label>
        <div id="result11"></div>
        </div>
      
        <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Deposited In<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please select deposit bank "> </i></label>
        <div class="controls">
        &nbsp;&nbsp;<select name="deposited_bank_id" class="span9 m-wrap chosen" id="bank">
        <option value="" style="display:none;">which bank?</option>    
        <?php
        foreach ($cursor3 as $db) 
        {
        $bank_id = (int)$db['ledger_sub_account']["auto_id"];
        $bank_ac = $db['ledger_sub_account']["name"];
        $bank_account_number = $db['ledger_sub_account']["bank_account"];
        ?>
        <option value="<?php echo $bank_id; ?>"><?php echo $bank_ac; ?> &nbsp;&nbsp; <?php echo $bank_account_number; ?></option>
        <?php } ?>
        </select>
        &nbsp;&nbsp;<label id="bank"></label>
        </div>


        
        <label  style="font-size:14px;">&nbsp;&nbsp;&nbsp;Receipt Mode<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose receipt mode"> </i></label>
        <div class="controls">
       &nbsp;&nbsp; <label class="radio">
        <div class="radio" id="uniform-undefined"><span><input type="radio" name="receipt_mode" value="Cheque" style="opacity: 0;" id="mode" class="chn" onclick="cheque_view()"></span></div>
        Cheque
        </label>
        <label class="radio">
        <div class="radio" id="uniform-undefined"><span><input type="radio" name="receipt_mode" value="NEFT" style="opacity: 0;" id="mode" class="neft" onclick="neft_text_view()"></span></div>
        NEFT
        </label>
        <label class="radio">
        <div class="radio" id="uniform-undefined"><span><input type="radio" name="receipt_mode" value="PG" style="opacity: 0;" id="mode" class="pg" onclick="pg_show()"></span></div>
        PG
        </label> 
        <label id="mode"></label>
        </div>
        <br />


<div id="cheque_show_by_query" class="hide">
        <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;Cheque No.<span style="color:red;">*</span><span style="margin-left:12%;">Cheque Date<span style="color:red;">*</span></span></label>
        <div class="controls">
        &nbsp;&nbsp;&nbsp;<input type="text"  name="cheque_number" class="m-wrap span3" placeholder="Cheque No." style="background-color:white !important;" id="ins"> &nbsp;&nbsp; 
        <input type="text"  class="date-picker m-wrap span4" name="cheque_date1" data-date-format="dd-mm-yyyy" placeholder="Date" />
        &nbsp;&nbsp;&nbsp;<label id="ins"></label> 
        </div>


    <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;Drawn on which bank?<span style="color:red;">*</span> </label>
    <div class="controls">
    &nbsp;&nbsp;&nbsp;<input type="text"  name="drawn_on_which_bank" class="m-wrap span9" placeholder="Drawn on which bank?" style="background-color:white !important;" id="ins">
    &nbsp;&nbsp;&nbsp;<label id="ins"></label>
    </div>
    </div>



    <div class="hide" id="neft_show">
    <label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Reference/UTR #<span style="color:red;">*</span><span style="margin-left:15%;">Date<span style="color:red;">*</span></span></label>
    <div class="controls">
    &nbsp;&nbsp;&nbsp;<input type="text"  name="reference_number" class="m-wrap span4 ignore" placeholder="Reference/UTR #" style="background-color:white !important;" id="ins">&nbsp;&nbsp;
    <input type="text"  name="cheque_date" class="m-wrap span3 date-picker" placeholder="Date" data-date-format="dd-mm-yyyy" style="background-color:white !important;" id="ins">
    <label id="ins"></label>
    </div>
    </div>


<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Received from<span style="color:red;">*</span> <i class=" icon-info-sign tooltips" data-placement="right" data-original-title="Please choose member/non-member "> </i></label>
<div class="controls">
&nbsp;&nbsp;<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member_type" class="hhh" value="1" style="opacity: 0;" id="mem" onclick="for_residential()"></span></div>
Residential
</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="member_type" class="go6" value="2" style="opacity: 0;" onclick="for_non_residential()" id="mem" onclick="for_non_residential()"></span></div>
Non-Residential
</label>
<label id="mem"></label>
</div>
<br />
</div>

<div style="background-color:#FFF; width:50%; float:right; overflow:hidden;">


<div id="for_resident_view" class="hide">
<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Select Resident<span style="color:red;">*</span></label>
<div class="control">
&nbsp;&nbsp;<select name="resident_name" class="m-wrap span9" id="resident_flat_id">
<option value="">Select Resident</option>
<?php
foreach($cursor1 as $data)
{
$user_id = (int)$data['ledger_sub_account']['user_id'];	
$resident_name = $data['ledger_sub_account']['name'];

$user_detail = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($user_id)));
foreach($user_detail as $user_data)
{
$flat_id = (int)$user_data['user']['flat'];	
}
?>
<option value="<?php echo $flat_id; ?>"><?php echo $resident_name; ?></option>
<?php
}
?>
</select>
</div>


<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Receipt Type<span style="color:red;">*</span></label>
<div class="controls">
&nbsp;&nbsp;<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="receipt_type" class="hhh" value="1" style="opacity: 0;" id="receipt_for"></span></div>Maintanace Receipt</label>
<label class="radio">
<div class="radio" id="uniform-undefined"><span><input type="radio" name="receipt_type" class="go6" value="2" style="opacity: 0;" onclick="hidediv('div12')" id="receipt_for2"></span></div>Other Receipt</label>
<label id="mem"></label>
<br />
</div>
</div>


<div id="result">
</div>

<div class="hide" id="non_residential_view">
 
<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Party Name<span style="color:red;">*</span></label>
<div class="controls">
&nbsp;&nbsp;<input type="text" class="m-wrap span9" name="party_name" id="party" />
&nbsp;&nbsp;<label id="party"></label>
</div>

<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Bill Reference<span style="color:red;">*</span></label>
<div class="controls">
&nbsp;&nbsp;<input type="text" class="m-wrap span9" name="bill_reference" id="bill_ref" />
&nbsp;&nbsp;<label id="bill_ref"></label>
</div>

<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Amount Applied<span style="color:red;">*</span></label>
<div class="controls">
&nbsp;&nbsp;<input type="text" class="m-wrap span9" name="amount" id="amt" />
&nbsp;&nbsp;<label id="amt"></label>
</div>
</div>


<label style="font-size:14px;">&nbsp;&nbsp;&nbsp;Narration<span style="color:red;">*</span></label>
<div class="controls">
&nbsp;&nbsp;<textarea   rows="4" name="description" class="span9 m-wrap" placeholder="Narration" style="background-color:white !important;resize:none;" id="nar" ></textarea>
</div>




</div>
</div>

<script>
function cheque_view()
{
$("#cheque_show_by_query").show();
$("#neft_show").hide();
}
function neft_text_view()
{	
$("#cheque_show_by_query").hide();
$("#neft_show").show();
}
function pg_show()
{
$("#cheque_show_by_query").hide();
$("#neft_show").show();
}

function for_non_residential()
{
$("#non_residential_view").show();
$("#for_resident_view").hide();
$('#result').html('');
}
function for_residential()
{
$("#for_resident_view").show();
$("#non_residential_view").hide();
}
</script>	




<!--  End Bank Receipt form Front End  -->
 
		
		
		<script>
		$(document).ready(function(){
		
		$("#resident_flat_id").bind('change',function(){
		var flat_id = document.getElementById('resident_flat_id').value;
		var receipt_for = $('#receipt_for:checked').val();
			
		$("#result").load("bank_receipt_reference_ajax?flat=" +flat_id+ "&rf=" +receipt_for+ "");
		});
				
		$("#receipt_for").bind('click',function(){
		var flat_id = document.getElementById('resident_flat_id').value;
		var receipt_for = $('#receipt_for:checked').val();
		$("#result").load("bank_receipt_reference_ajax?flat=" +flat_id+ "&rf=" +receipt_for+ "");
		});
		
        $("#receipt_for2").bind('click',function(){
		//var flat_id = document.getElementById('resident_flat_id').value;
		var receipt_for = $('#receipt_for2:checked').val();
		
		$("#result").load("bank_receipt_reference_ajax?rf=" +receipt_for+ "");
		});


	


		
		$("#i_head").live('change',function(){
		
		var ss = $("[id=i_head]").val();
		
		$("#result2").html('Loading...').load("bank_receipt_amount_ajax?ss=" +ss + "");	
		
		});
		
		});
		</script>	  
			  
		
		
	
<script>
		function hidediv(id)
		{
		document.getElementById('div13').style.display='block';
		document.getElementById(id).style.display='none';
		}
		$(document).ready(function() {
		$(".hhh").live('click',function(){
		document.getElementById('div12').style.display='block';
		document.getElementById('div13').style.display='none';
		
		
	
	$("#div11").html('Loading...').load("bank_receipt_ajax?ff=" + 5 + "");
	});
	
	$(".go6").live('click',function(){
		
		//$("#div2").show();
		//$("#div1").hide();
		
		$("#div13").show();
	
	$("#div11").html('Loading...').load("bank_receipt_ajax?ff=" + 8 + "");
	
	});
	
$("#ttt").live('click',function(){

//document.getElementById('div12').style.display='block';
//document.getElementById('div13').style.display='none';
var val =  $('#ttt:checked').val();
var flat_id = $("#go").val();

$("#show_amount").html('Loading...').load("bank_receipt_reference_ajax?type="+val+"&flat="+flat_id+"");
		
});	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
});
</script>		

 <?php ////////////////////////////////////// ?>


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
	      date: {
	       
	        required: true
	      },
		  
		  aammtt: {
			required: true  
		  },
		
		 rr_type : {
		   required: true  	
		  },
				  
		   bank_account: {
	       
	        required: true
	      },
		  
		 
		 
	         receipt_mode: {
                required: true
	      },

	     member: {
	       
	        required: true
	      },
		
		recieved_from2: {
	       
	        required: true
	      },
		
		
		
		recieved_from: {
	       
	        required: true
	      },
		instruction: {
			 required: true
		         },
		 
		 
		 amountn: {
	        required: true,
			number: true,
			notEqual: "0"
	      },
		amount : {
			required: true,
			number: true,
			notEqual: "0"
		
		},
		no: {
			required: true,
			number: true
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

<script>
$(document).ready(function() {

		$("#vali").bind('click',function(){
		var fi = document.getElementById("fi").value;
		var ti = document.getElementById("ti").value;
		var cn = document.getElementById("cn").value;
		var fe = fi.split(",");
		var te = ti.split(",");
		var date1 = document.getElementById("date").value;
		var date = date1.split("-").reverse().join("-");
		var nnn = 55;
			for(var i=0; i<cn; i++)
			{
			var fd = fe[i];
			var td = te[i]
			if(date == "")
			{
			nnn = 555;
			break;	
			}
			else if(Date.parse(fd) <= Date.parse(date))
			{
			if(Date.parse(td) >= Date.parse(date))
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
			$("#result11").load("cash_bank_vali?ss=" + 2 + "");
			return false;	
			}
			else if(nnn == 555)
			{
			
			}
			else
			{
			$("#result11").load("cash_bank_vali?ss=" + 12 + "");		
			}
			});
			});
		</script>	
		



<script>
$(document).ready(function() {

$("#import").bind('click',function(){
$("#myModal3").show();
});

$("#close_div").bind('click',function(){
$("#myModal3").hide();
});

});
</script>


	<!-- Start Bank Receipt query -->

		<script>
        $(document).ready(function(){

		$(".delete").live('click',function(){
		var id = $(this).attr("del");
		$('#tr'+id).remove();
		});	
		$('form#form1').submit( function(ev){
		ev.preventDefault();
		
		var im_name=$("#image-file").val();
		var insert = 1;
		if(im_name==""){
		$("#vali").html("<span style='color:red;'>Please Select a Csv File</span>");	
		return false;
		}
		
		var ext = $('#image-file').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['csv']) == -1) {
		$("#vali").html("<span style='color:red;'>Please Select a Csv File</span>");
		return false;
		}

			$(".import_btn").text("Importing...");
			var m_data = new FormData();
			m_data.append( 'file', $('input[name=file]')[0].files[0]);
			$.ajax({
			url: "bank_receipt_import_ajax",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			}).done(function(response){
			$("#myModal3").hide();
			$("#url_main").html(response);
		
			$(".bank_receipt_import").bind('click',function(){
			var count = $("#open_bal tr").length;
			var ar = [];
			var insert = 2;

			for(var i=2; i<=count; i++)
			{
			$("#open_bal tr:nth-child("+i+") span.report").remove();
			$("#open_bal tr:nth-child("+i+") span.report").css("background-color","#FFF;");
			var TransactionDate = $("#open_bal tr:nth-child("+i+") td:nth-child(1) input").val();
			var ReceiptMod=$("#open_bal tr:nth-child("+i+") td:nth-child(2) input").val();
			var ChequeNo=$("#open_bal tr:nth-child("+i+") td:nth-child(3) input").val();
			var Reference=$("#open_bal tr:nth-child("+i+") td:nth-child(4) input").val();
			var DrawnBankname=$("#open_bal tr:nth-child("+i+") td:nth-child(5) input").val();
			var Date1=$("#open_bal tr:nth-child("+i+") td:nth-child(6) input").val();
			var bank_id=$("#open_bal tr:nth-child("+i+") td:nth-child(7) select").val();
			var auto_id=$("#open_bal tr:nth-child("+i+") td:nth-child(8) select").val();
			var Amount=$("#open_bal tr:nth-child("+i+") td:nth-child(9) input").val();
			var narration=$("#open_bal tr:nth-child("+i+") td:nth-child(10) input").val();
			ar.push([TransactionDate,ReceiptMod,ChequeNo,Reference,DrawnBankname,Date1,bank_id,auto_id,Amount,insert,narration				]);
			}

			var myJsonString = JSON.stringify(ar);
			myJsonString=encodeURIComponent(myJsonString);
			$.ajax({
			url: "save_bank_imp?q="+myJsonString,
			type: 'POST',
			dataType:'json',
			}).done(function(response) {
			if(response.report_type=='validation')
			{
			$(".validat_text").html('<b style="color:red;">'+response.text+'</b>');
			}
			if(response.report_type=='done')
			{
			$(".bank_rr").html('<div class="alert alert-block alert-success fade in"><h4 class="alert-heading">Success!</h4><p>Record Inserted Successfully</p><p><a class="btn green" href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" rel="tab">OK</a></p></div>');
		}
		});
		});
		});
		});
		});
</script>	
<!--   End Receipt Import query -->







