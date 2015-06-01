<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<div id="done" style="overflow:auto;">
<form  id="form1" method="post" >	
<div class="show_record" style="width:100%; overflow:auto;">
<div class="portlet box green">
<div class="portlet-title">
<h4><i class="icon-cogs"></i> Csv Import</h4>
</div>
<div class="portlet-body">

<div class="control-group">
<label class="control-label">Attach csv file</label>
<div class="controls">
<input type="file" name="file" class="default">
<button type="submit" class="btn blue import_btn">Import</button>
</div>
</div>

<strong><a href="<?php echo $this->webroot; ?>csv_file/demo/Opening Balance Import.csv" download="">Click here for sample format</a></strong>
<br>
<h4>Instruction set to import users</h4>
<ol>
<li>All the field are compulsory.</li>
<li>Opening Balance Amount should be Numeric</li>
<li>Amount Type should be 'Debit' or 'Credit'</li>
<li>Total Debit should be same to total Credit</li>
</ol>
</div>
</div>

</form>	
</div>



<?php //////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function(){



$('form#form1').submit( function(ev){
		ev.preventDefault();
		$(".import_btn").text("Importing...");
		
		var m_data = new FormData();
		m_data.append( 'file', $('input[name=file]')[0].files[0]);
		$.ajax({
		url: "opening_balance_import_ajax",
		data: m_data,
		processData: false,
		contentType: false,
		type: 'POST',
		}).done(function(response) {
		$(".show_record").html(response);
var insert = 1;
var count = $("#open_bal tr").length;
var ar = [];

for(var i=2;i<=count;i++)
{
$("#open_bal tr:nth-child("+i+") span.report").remove();
$("#open_bal tr:nth-child("+i+") span.report").css("background-color", "white");
var date = $("#open_bal tr:nth-child("+i+") td:nth-child(1) input").val();
var ac=$("#open_bal tr:nth-child("+i+") td:nth-child(2) select").val();
var type=$("#open_bal tr:nth-child("+i+") td:nth-child(3) select").val();
var amt=$("#open_bal tr:nth-child("+i+") td:nth-child(4) input").val();

ar.push([date,ac,type,amt,insert]);
}

var myJsonString = JSON.stringify(ar);
myJsonString=encodeURIComponent(myJsonString);



$.ajax({
url: "save_open_bal?q="+myJsonString,
type: 'POST',
dataType:'json',
}).done(function(response) {
if(response.report_type=='error'){
jQuery.each(response.report, function(i, val) {
$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").append('<span class="report" style="color:red;">'+val.text+'</span>');

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");
});
}
});

$(".import_op").bind('click',function(){

var insert = 2;
	
var count = $("#open_bal tr").length;
var ar = [];

for(var i=2;i<=count;i++)
{
$("#open_bal tr:nth-child("+i+") span.report").remove();
$("#open_bal tr:nth-child("+i+") span.report").css("background-color", "white");
var date = $("#open_bal tr:nth-child("+i+") td:nth-child(1) input").val();
var ac=$("#open_bal tr:nth-child("+i+") td:nth-child(2) select").val();
var type=$("#open_bal tr:nth-child("+i+") td:nth-child(3) select").val();
var amt=$("#open_bal tr:nth-child("+i+") td:nth-child(4) input").val();

ar.push([date,ac,type,amt,insert]);
}

var myJsonString = JSON.stringify(ar);
myJsonString=encodeURIComponent(myJsonString);	
	
		
$.ajax({
url: "save_open_bal?q="+myJsonString,
type: 'POST',
dataType:'json',
}).done(function(response) {
if(response.report_type=='error'){	
jQuery.each(response.report, function(i, val) {
$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").append('<span class="report" style="color:red;">'+val.text+'</span>');

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");

$("#open_bal tr:nth-child("+val.tr+") td:nth-child("+val.td+")").css("background-color", "#f2dede");
});
}
if(response.report_type=='fina')
{
$("#vali").html('<b style="color:red;">'+response.text+'</b>');	
}
if(response.report_type=='done')
{
$("#done").html('<div class="alert alert-block alert-success fade in"><h4 class="alert-heading">Success!</h4><p>Record Inserted Successfully</p><p><a class="btn green" href="<?php echo $webroot_path; ?>Accounts/opening_balance_import" rel="tab">OK</a></p></div>');
}

});	
	
	
	
	
	
	
		
		
});
	





});
});





});
</script>