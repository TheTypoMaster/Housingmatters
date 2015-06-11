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

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<center>
<a href="<?php echo $webroot_path; ?>Bookkeepings/journal_add" class="btn yellow" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Bookkeepings/journal_view" class="btn" rel='tab'>View</a>
<br><br>              
</center>            					
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
$("#button_add").bind('click',function(){
var c=$("#main_table tr").length;
c++;
$.ajax({
url: 'journal_add_row?con=' + c,
}).done(function(response) {
$('table#main_table').append(response);
});
});

$(".delete_row").live('click',function(){
var id=$(this).attr("id");
$('#tr'+id).remove();
});
});
</script>		
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>					

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<?php
$default_date = date('d-m-Y');

?>
<div id="succ">	
<div class="portlet box grey" style="width:100%;">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Journal</h4>
</div>
<div class="portlet-body form">
<?php 
if($zz == 0)
{
?>      
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">No Previous Journal Voucher</p>
</center>
</div> 
<?php
}
else
{
?>
<div style="background-color:#FCEBF8;">
<center>
<p style="color:#A99185;">The Last Journal Voucher Number is : <?php echo $zz; ?></p>
</center>
</div> 
<?php } ?>


<form  method="POST" onSubmit="return balance()" >			  
<br>
<input type="text" id="date"  name="date" class="all_validate  m-wrap m-ctrl-medium date-picker"  data-date-format="dd-mm-yyyy" style="background-color:#FFF !important;" placeholder="Transaction Date" value="<?php echo $default_date; ?>">
<br><br>
<center>
<div id="error_msg"></div>
<div id="result11"></div>
					
					
					
					
					<input type="hidden" id="t_box" name="xyz" value="2">
					
					<div style="padding:2px;">
					<div style="border:solid 1px #eee;">
					<div  style="background-color:rgba(226, 228, 255, 0.39); ">
					
					
					<table width="100%" >
					<tr class="table table-bordered table-hover" style="font-size:16px;">
					<th width="20.2%">Ledger A/c</th>
					<th width="19.8%">Subledger</th>
					<th width="19%">Debits</th>
					<th width="19%">Credits</th>
					<th width="20%">Description</th>
					<th width="2%"></th>
					</tr>
					</table>
					
					
					</div>					
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>					
<div id="add_div" >
<table width="100%"  id="main_table">
<tr class="table table-bordered table-hover" id="tr1">
<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<select class="all_validate span12 m-wrap chosen" onchange="show_ledger_type(this.value,1)" name="l_type_id1" id="lac1">
<option value="">--SELECT--</option>					
<?php
foreach ($cursor2 as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$name = $collection['ledger_account']['ledger_name'];
?>
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>
<?php } ?>               
</select>
</div>
</div>
</td>					
<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;" id="show_ledger_type1">
</td>					
				
<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important;" onblur="total_am('1')" name="debit1" placeholder="Debits" id="debit1">
</div>
</div>
</td>					
					
<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important;" name="credit1" onblur="total_amc('1')" placeholder="Credits" id="credit1">
</div>
</div>
</td>

<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<input type="text"  name="remark1" class="all_validate span12 m-wrap m-ctrl-medium"  style="background-color:#FFF !important;"placeholder="Description" id="desc1">
</td>
<td width="2%"></td>
</tr>
<tr class="table table-bordered table-hover" id="tr2">

<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<select class="all_validate span12 m-wrap chosen qwerty" onchange="show_ledger_type(this.value,2)" name="l_type_id2" id="lac2">
<option value="">--SELECT--</option>
<?php
foreach ($cursor2 as $collection) 
{
$auto_id = (int)$collection['ledger_account']['auto_id'];
$name = $collection['ledger_account']['ledger_name'];
?>
<option value="<?php echo $auto_id; ?>"><?php echo $name; ?></option>               
<?php } ?>               
</select>
</div>
</div>
</td>


<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;" id="show_ledger_type2">
</td>
<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important;" onblur="total_am('2')" name="debit2" placeholder="Debits" id="debit2">
</div>
</div>
</td>

<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important;"  name="credit2" onblur="total_amc('2')" placeholder="Credits" id="credit2">
</div>
</div>
</td>

<td style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<input type="text"  name="remark2" class="all_validate span12 m-wrap m-ctrl-medium" style="background-color:#FFF !important;" placeholder="Description" id="desc2">
</td>
<td width="2%"><a href="#" role="button" class="btn mini black delete_row" id="2"><i class="icon-remove"></i></a></td>
</tr>					
</table>					
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>					

</div>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<table width="100%">
<tr class="table table-bordered table-hover">
<th style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:40.1%; text-align:right;">
Total
</th>
<th style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:19.9%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important; border:none !important;" id="total" style="border:none !important">
</div>
</div>
</th>
<th style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;">
<div class="control-group">
<div class="controls">
<input type="text" class="all_validate span12 m-wrap" style="background-color:#FFF !important; border:none !important;" id="total_c" style="border:none !important">
</div>
</div>
</th>

<th style="padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:0px; width:20%;"></th>
</tr>
</table>

<?php /////////////////////////////////////////////////////////////////////////////////////////// ?>

<input type="hidden" id="total_dr5">
					
<?php ///////////////////////////////////////////////////////////////////////////////////////////////// ?>
</div>
</div>
<br>
<div class="form-actions" style="background-color:#CCC;">
<button type="submit" class="btn blue" name="journal_add" id="submit">Submit</button>
<button type="button" id="button_add" class="btn blue"> <i class="icon-plus"></i> Add Row</button>
<button type="button" id="button_remove" class="btn red"> <i class=" icon-remove"></i>Delete Row</button>
<a href="journal_add" class="btn">Reset</a>
</div>
<br><Br>
</form>
</div>
</div>
</div>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<script>
function show_ledger_type(c1,t)
{
$(document).ready(function() {
$("#show_ledger_type" + t).load("show_ledger_type?c1=" +c1+ "&t=" +t+ "");
});
}
</script>
<script>
function total_amc(l)
{
var t_c = 0;
var count = $("#main_table tr").length;
for(var k = 1; k<=count; k++)
{
var credit = document.getElementById('credit' + k).value;
if(credit == "")
{
credit = 0;
}
else
{
credit = eval(credit);
}
t_c = eval(t_c + credit);
}
document.getElementById('total_c').value = t_c;
}
</script>

<script>
function total_am(x)
{
var t_d = 0;
var count = $("#main_table tr").length;
for(var j = 1; j<=count; j++)
{
var debit = document.getElementById('debit' + j).value;
if(debit == "")
{
debit = 0;
}
else
{
debit = eval(debit);
}
if(debit!=0)
{
t_d = eval(t_d + debit);
}
}
document.getElementById('total').value = t_d;
}
</script>

<script>
$(document).ready(function() {
	$('form').submit( function(ev){
	ev.preventDefault();
	$("#submit").addClass("disabled").text("submiting...");
		
		var hidden=$("#main_table tr").length;
		
		var date = $("#date").val();
		
		var ar = [];
		for(var i=1;i<=hidden;i++)
		{
		var ledger = $("#main_table tr:nth-child("+i+") td:nth-child(1) select").val();
		
		if(ledger == 15 || ledger == 33 || ledger == 35 || ledger == 34)
		{		
		var ledger_sub = $("#main_table tr:nth-child("+i+") td:nth-child(2) select").val();
		}
		var debit = $("#main_table tr:nth-child("+i+") td:nth-child(3) input").val();
		var credit = $("#main_table tr:nth-child("+i+") td:nth-child(4) input").val();
		var desc = $("#main_table tr:nth-child("+i+") td:nth-child(5) input").val();
		if(ledger == 15 || ledger == 33 || ledger == 35 || ledger == 34)
		{
		ar.push([ledger,ledger_sub,debit,credit,desc]);
		}
		else
		{
		ar.push([ledger,debit,credit,desc]);
		}
		var myJsonString = JSON.stringify(ar);
		var date2 = JSON.stringify(date)
		}
		
			$.ajax({
			url: "journal_validation?q="+myJsonString+"&b="+date2,
			dataType:'json',
			}).done(function(response) {
			
				if(response.type == 'error'){  
					output = '<div class="alert alert-error">'+response.text+'</div>';
					$("#submit").removeClass("disabled").text("submit");
					$("html, body").animate({
					 scrollTop:0
					 },"slow");
				     }
				if(response.type=='succ'){
				$("#succ").html('<div class="alert alert-block alert-success fade in"><h4 class="alert-heading">Success!</h4><p>Record Inserted Successfully</p><p><a class="btn green" href="<?php echo $webroot_path; ?>Bookkeepings/journal_view" rel="tab">OK</a></p></div>');
				
			}
				
				$("#error_msg").html(output);
});
});
});
</script>
				

