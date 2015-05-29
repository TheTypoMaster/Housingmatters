<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<div class="show_record" style="width:100%; overflow:auto;">

<div class="portlet box green">
<div class="portlet-title">
<h4><i class="icon-cogs"></i> Csv Import</h4>
</div>
<div class="portlet-body">
<form  id="form1" method="post" >	
<div class="control-group">
<label class="control-label">Attach csv file</label>
<div class="controls">
<input type="file" name="file" class="default">
<button type="submit" class="btn blue import_btn">Import</button>
</div>
</div>
</form>	
<strong><a href="<?php echo $this->webroot; ?>csv_file/demo/demo2.csv" download="">Click here for sample format</a></strong>
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




		});
});


});
</script>