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
<li class="active"><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Configuration</a></li>
<!--<li><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>-->
<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab' >Society Details</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
</ul>
<div class="tab-content" style="min-height:300px;">
<div class="tab-pane active" id="tab_1_1">



<a href="#" class="btn purple" role="button" id="import">Import</a>
<div id='suces'>
<div id="error_msg"></div>
<div id="myModal3" class="modal hide fade in" style="display: none;">

<div class="modal-backdrop fade in"></div>
	<form id="form1" method="post">
	<div class="modal">
		<div class="modal-header">
			<h4 id="myModalLabel1">Import csv</h4>
		</div>
		<div class="modal-body">
			<input type="file" name="file" class="default">
			
			<strong><a href="<?php echo $this->webroot; ?>csv_file/unit_flat/flat_import.csv" download>Click here for sample format</a></strong>
			<br/>
			<h4>Instruction set to import users</h4>
			<ol>
			<li>All the field are compulsory.</li>
			<li>Wing and Flat name be valid as per society setting.</li>
			<li>Flat type be valid as per society setting. </li>
			<li>Flat area be valid as per society setting. </li>
			</ol>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn" id="close_div">Close</button>
			<button type="submit" class="btn blue import_btn">Import</button>
		</div>
	</div>
	</form>
</div>


<table width="100%" style="background-color:#CDD5ED;">
<tr class="table table-bordered table-hover" style="font-size:16px;">
<th style="text-align:center;" width="25%">Wing</th>
<th style="text-align:center;" width="25%">Flat Number</th>
<th style="text-align:center;" width="25%">Flat Type</th>
<th style="text-align:center;" width="25%">Flat Area (Sq.Ft.)</th>
</tr>
</table>
<form id="form2" method="post">
<div id="url_main" >



<table width="100%" id="myTable">
<tr class="table table-bordered table-hover" id="tr1">
<td width="25%" style="text-align:center;">
<select name="wing_name1" class=" m-wrap medium wing" id="sel1" inc_id="1">
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
	
<td width="25%" style="text-align:center;" id="showflat1">

</td>	

					
<td width="25%" style="text-align:center;">
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



<td width="25%" style="text-align:center;">
<input type="text" name="area1" class="m-wrap medium" id="ar1" />
</td>
				
</tr>
</table>


</div>
<br/>
<a role="button" class="btn blue " id="add_row"><i class="icon-plus"></i> Add row</a>
<div align="center">
<button type="submit" id="submit" class="btn blue">Submit</button>
</div>
</form>


</div>
</div>
</div>
</div>			





<br>
                    
<div>
<a href="flat_excel" class="btn blue" style="float:right;">Export in Excel</a>
</div>                    
  <br />                  

					<div class="portlet box" style="width:80%;overflow:auto;">

					<div class="portlet-body" style="float:right; width:70%;" align="center">
					
					<table class="table table-striped table-bordered" id="sample_2" style="width:100%;">
					<thead>
					<tr>
					<th>Sr No.</th>
					<th>Wing</th>
					<th>Flat-Number</th>
                    <th>Flat Type</th>
                    <th>Flat Area (Sq. Ft.)</th>
                    <th>NOC Type</th>
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
							$noc_type = (int)@$collection['flat']['noc_ch_tp'];
							
							
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

$fl_tp2 = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array(@$flat_type_id2)));		
foreach($fl_tp2 as $collection)
{
$flat_type = $collection['flat_type_name']['flat_name'];
}
if($noc_type == 1)
{
$noc_type_name = "Self Occupied";	
}
else if($noc_type == 2)
{
$noc_type_name = "Leased";
}

?>
<tr>
<td><?php echo $q; ?></td>
<td><?php echo $wing_name; ?></td>
<td><?php echo $flat_name; ?></td>
<td><?php if($sqfeet == 0) { echo "null"; } else { echo $flat_type; } ?></td>
<td><?php if($sqfeet == 0) { echo "null"; } else { echo $sqfeet; } ?></td>
<td><?php if($noc_type == 0) { echo "not defined"; } else { echo $noc_type_name;  } ?> </td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>
    
  
</div>
</div>
	


<script>
$(document).ready(function(){
	 $("#add_row").bind('click',function(){
		var count = $("#myTable tr").length;
		count++;
		$("#url_main").append();
		$.get('master_sm_flat_add_row?con='+count, function(data){
			content= data;
			$('#myTable').append(content);
		});
	 });
	
	 $(".delete").live('click',function(){
		var id = $(this).attr("id");
		$('#tr'+id).remove();
	 });
	 
	 
	 $(".wing").live('change',function(){
		var c=this.value;
		var i=$(this).attr('inc_id');
		$('#echo_flat'+i).html("Loading...").load('return_flat_via_wing_id3?con2='+c+'&con1='+i);
	 });
	 
	 $('form#form2').submit( function(ev){
		ev.preventDefault();
		var count = $("#myTable tr").length;
		var ar = [];
		if(count>0)
		{
		for(var i=1;i<=count;i++)
		{
		
		var w=$("#url_main table tr:nth-child("+i+") td:nth-child(1) select").val();
		var f=$("#url_main table tr:nth-child("+i+") td:nth-child(2) select").val();
		var ft=$("#url_main table tr:nth-child("+i+") td:nth-child(3) select").val();
		var a=$("#url_main table tr:nth-child("+i+")  input[name=area1]").val();
		ar.push([w,f,ft,a]);
		}
		
		var myJsonString = JSON.stringify(ar);
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
					$('#suces').show().html('<div class="alert alert-success">'+response.text+'</div>');
					}
			$("#error_msg").html(output);
			
			});

		 };
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
	$(".wing").live('change',function(){
		var c=this.value;
		var i=$(this).attr('inc_id');
		$('#showflat'+i).html("Loading...").load('return_flat_via_wing_id3?con2='+c+'&con1='+i);
	 });
	$('form#form1').submit( function(ev){
			ev.preventDefault(); 
			
		$(".import_btn").text("Importing...");
		var m_data = new FormData();
		m_data.append( 'file', $('input[name=file]')[0].files[0]);
		$.ajax({
			url: "import_flat_configuration",
			data: m_data,
			processData: false,
			contentType: false,
			type: 'POST',
			}).done(function(response) {
			$("#myModal3").hide();
			$("#url_main table").html(response);
	 });
});

}); 
</script>				