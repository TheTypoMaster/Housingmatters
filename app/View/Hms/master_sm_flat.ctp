<?php ////////////////////////////////////////////////////////////////////////////// ?>
<script>
$(document).ready(function() {
$("#button_add").bind('click',function(){
var c=$('#t_box').val();
c++;
$('#add_div').append($('<div>').load('master_sm_flat_add_row?con=' + c));
document.getElementById('t_box').value=c;
});
$("#button_remove").live('click',function(){
d=document.getElementById('t_box').value;
if(d>1) {
$('#tab' + d).remove();
d--; 
$('#t_box').val(d);
}
});
});
</script>



<script type="text/javascript">
var xobj;
//modern browers
if(window.XMLHttpRequest)
{
xobj=new XMLHttpRequest();
}
else if(window.ActiveXObject)
{
xobj=new ActiveXObject("Microsoft.XMLHTTP");
}
else
{
alert("Your broweser doesnot support ajax");
}
function search_topic()
{
if(xobj)
{
var count = document.getElementById('t_box').value;		 
for(var i=1; i<=count; i++)
{
var c2=document.getElementById("flat_id"+ i).value;
var c3=document.getElementById("sel"+ i).value;	
var query="?con1=" + c2 + "&con2=" + c3;
xobj.open("GET","master_sm_flat_ajax" +query,true);
xobj.onreadystatechange=function()
{
if(xobj.readyState==4 && xobj.status==200)
{	   
document.getElementById("ser_top").innerHTML=xobj.responseText;
}
}
}
}
xobj.send(null);
}
</script>
<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?> 
<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Setup
</div>
				 
				 
				 
	<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
			<li ><a href="<?php echo $webroot_path; ?>Hms/master_sm_wing" rel='tab'> Wing</a></li>
            <li><a href="<?php echo $webroot_path; ?>Hms/flat_type" rel='tab'>Flat Type</a></li>
            <li class="active"><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Number</a></li>
            <li ><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>
			<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab' >Society Details</a></li>
			<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
			</ul>
				<div class="tab-content" style="min-height:300px;">
					<div class="tab-pane active" id="tab_1_1">
					<div>
					<!--<div id="ser_top" align="center" style="margin-right:7%;"></div>-->
                    
					<div id="succ">
<?php /////////////////////////////////////////////////////////////////////////////// ?>				
        <div class="portlet box grey" style="width:100%;">
              <div class="portlet-title">
              <h4><i class="icon-reorder"></i>Flat Setup</h4>
              </div>
              <div class="portlet-body form">               
<?php /////////////////////////////////////////////////////////////////////////////////// ?>                
              
	<form  class="form-horizontal" method="post" id="contact-form" onSubmit="return validate()">     
     
     

<div id="error_msg"></div>
                	<input type="hidden" id="t_box" name="xyz" value="1">
					
					
					
					
					<table width="100%" style="background-color:#CDD5ED;">
					<tr class="table table-bordered table-hover" style="font-size:16px;">
				
					<th style="text-align:center;" width="21%">Wing</th>
					<th style="text-align:center;" width="21%">Flat Number</th>
					<th style="text-align:center;" width="21%">Flat Type</th>
					<th style="text-align:center;" width="21%">Flat Area</th>
					<th style="text-align:center;" width="16%">Noc Type</th>
					</tr>
					</table>
					
     
<?php //////////////////////////////////////////////////////////////////////////////////////////////// ?>     
<div id="add_div" >
<table width="100%"  >
<tr class="table table-bordered table-hover">

<td width="21%" style="text-align:center;">
<select name="wing_name1" class=" m-wrap medium" id="sel1" onchange="show_flat(this.value,1)">
<option value="">Select Category</option>
<?php
foreach($user_wing as $collection) 
{
$wing_id=$collection['wing']["wing_id"];
$wing_name=$collection['wing']["wing_name"];
?>
<option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
<?php } ?>
</select>
</td>					
					
					
					
					
<td width="21%" style="text-align:center;" id="showflat1">
<!--<input type="text" class="m-wrap medium" id="flat_id1" name="flat_name1" maxlength="4" onkeyup="search_topic();">-->
</td>					
					
					
<td width="21%" style="text-align:center;">
<select name="flat_type1" class="m-wrap medium" id="fltp1">
<option value="">--SELECT FLAT TYPE--</option>
<?php
foreach($cursor4 as $collection)
{
$auto_id = (int)$collection['flat_type_name']['auto_id'];
$flat_type_name = $collection['flat_type_name']['flat_name'];	

?>
<option value="<?php echo $auto_id; ?>"><?php echo $flat_type_name; ?></option>
<?php } ?>
</select>
</td>

<td width="21%" style="text-align:center;">
<input type="text" name="area1" class="m-wrap medium" id="ar1" />
</td>
<td style="text-align:center;" width="16%;">
<select name="noctp1" class="m-wrap small" id="noc1">
<option value="">Select</option>

<option value="1">Self Occupied</option>
<option value="2">Leased</option>
</select>
</td>
					
</tr>
</table>
	   
</div>    

<div>
<p style="color:red;"><?php echo @$vali; ?></p>
</div>	
<?php ////////////////////////////////////////////////////////////////////// ?>  
<br />
<div class="form-actions" style="background-color:#CCC;">
<button type="submit" class="btn blue" name="flat_add" onMouseOver="search_topic();" id="subb">Submit</button>
<button type="button" id="button_add" class="btn blue"> <i class="icon-plus"></i> Add Row</button>
<button type="button" id="button_remove" class="btn red"> <i class=" icon-remove"></i>Delete Row</button>
</div>
<?php ///////////////////////////////////////////////////////////////////////////// ?>   
</form>               
                
<?php ////////////////////////////////////////////////////////////////////////////////// ?>                    
</div>
</div>                    
                    
                    
<?php /////////////////////////////////////////////////////////////////////////////////// ?>                    
                    
                 

</div>
</div>
</div>

					<br>
                    
<div style="width:100%;">
<a href="flat_excel" class="btn blue" style="float:right;">Export in Excel</a>
</div>                    
  <br />                  

					<div class="portlet box" style="width:80%;">

					<div class="portlet-body" style="float:right; width:70%;" align="center">
					<table class="table table-striped table-bordered" id="sample_2" style="width:100%;">
					<thead>
					<tr>
					<th>Sr No.</th>
					<th>Wing-Name</th>
					<th>Flat-Name</th>
                    <th>Flat Type</th>
                    <th>Flat Area</th>
					</tr>
							</thead>
							<tbody>
							<?php
							$q=0;
	                        foreach($cursor1 as $collection)
	                        {
								
							$q++;						
							$wing_id = (int)$collection['flat']['wing_id'];
							$flat_name = $collection['flat']['flat_name'];
							$flat_type_id = (int)@$collection['flat']['flat_type_id'];
							$sqfeet = (int)@$collection['flat']['flat_area'];
							
$wing_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_fetch'),array('pass'=>array($wing_id)));	
foreach($wing_fetch as $collection)
{							
$wing_name = $collection['wing']['wing_name'];							
}

$fl_tp = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_fetch2'),array('pass'=>array($flat_type_id)));		
foreach($fl_tp as $collection)
{
$flat_type_id2 = (int)$collection['flat_type']['flat_type_id'];
}

$fl_tp2 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($flat_type_id2)));		
foreach($fl_tp2 as $collection)
{
$flat_type = (int)$collection['flat_type_name']['flat_name'];
}




?>
<tr>
<td><?php echo $q; ?></td>
<td><?php echo $wing_name; ?></td>
<td><?php echo $flat_name; ?></td>
<td><?php if($sqfeet == 0) { echo "null"; } else { echo $flat_type; } ?></td>
<td><?php if($sqfeet == 0) { echo "null"; } else { echo $sqfeet; } ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
    
  
</div>
</div>
		
		
<?php /////////////////////////////////////////////////////////////////////////////////////////////////// ?>		
<script>
function show_flat(e,y)
{
var count = document.getElementById('t_box').value;
$("#showflat"+ y).html('<div align="center" style="padding:10px;"><img src="as/loading.gif" />Loading....</div>').load("flat_show_ajax?con=" +e+ "&t2=" +y+ "");
}
</script>


<script>
$(document).ready(function() { 
$('form').submit( function(ev){
ev.preventDefault();
$("#submit").addClass("disabled").text("submiting...");

var hidden = $("#t_box").val();
var ar = [];
for(var i=1;i<=hidden;i++)
{
var wing = $("#sel"+i).val();
var flat_nu = $("#fl"+i).val();
var flat_type_id = $("#fltp"+i).val();
var flat_area = $("#ar"+i).val();
var noc_type = $("#noc"+i).val();

ar.push([wing,flat_nu,flat_type_id,flat_area,noc_type]);

var myJsonString = JSON.stringify(ar);

}
$.ajax({
url: "master_sm_flat_vali?q="+myJsonString,
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
$("#shwd").show()
$(".success_report").show().html(response.text);	
}


$("#error_msg").html(output);
});


});
});
</script>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////// ?>


<div id="shwd" class="hide">
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
<center>
<h3 id="myModalLabel3" style="color:#999;"><b>Expense Tracker</b></h3>
</center>
</div>
<div class="modal-body">
<center>
<h5><b class="success_report"></b></h5>
</center>
</div>
<div class="modal-footer">
<a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" class="btn blue" rel='tab'>OK</a>
</div>
</div>
</div> 





