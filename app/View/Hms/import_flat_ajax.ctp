<div class="modal-header">
	<h4 id="myModalLabel1">Import csv</h4>
</div>
<div class="modal-body">
	<table class="table table-bordered">
		<tr>
			<th>Wing</th>
			<th>Unit number</th>
		</tr>
	</table>
	<table class="table table-bordered">
	<?php foreach($table as $data){ 
	pr($data); ?>
		<tr>result_wing
			<td>
				<select class="span6 m-wrap" data-placeholder="Choose a Category" tabindex="1">
				<option value="">Select...</option>
				<?php foreach($result_wing as $wdata){
				$wing_id=$wdata["wing"]["wing_id"];
				$wing_name=$wdata["wing"]["wing_name"];
				?>
				<option value="<?php echo $wing_id; ?>" <?php if($wing_id==$data[0]){ echo 'selected'; }><?php echo $wing_name; ?></option>
				<?php } ?>
				
				</select>
			</td>
			<td>First Name</td>
		</tr>
	<?php } ?>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn" id="import_close">Cancel</button>
	<button type="submit" class="btn blue import_btn">Import</button>
</div>