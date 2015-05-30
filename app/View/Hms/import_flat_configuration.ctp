<?php 
$i=0;

foreach($table as $child){
	$i++;
	?>
<tr id="tr<?php echo $i; ?>">
				
		<td width="25%">
		<select class="span12 m-wrap wing" id="wing2" name="wing" inc_id="<?php echo $i; ?>">
		<option value="">-Wing-</option>
		<?php 
		foreach($result_wing as $data) { 
		$wing_id=$data["wing"]["wing_id"];
		$wing_name=$data["wing"]["wing_name"];
		?>
		<option value="<?php echo $wing_id; ?>" <?php if($wing_id==$child[0]){ echo 'selected';} ?> ><?php echo $wing_name; ?></option>
		<?php } ?>

		</select>
		</td>
		
		<td id="echo_flat<?php echo $i; ?>" width="25%">
		<?php $result_flat=$this->requestAction(array('controller' => 'Hms', 'action' => 'flat'), array('pass' => array($child[0]))); ?>
		<select class="span12 m-wrap" id="flat1" name="flat" >
		<option value="">Flat</option>
		<?php 
		foreach($result_flat as $data) { 
		$flat_id=$data["flat"]["flat_id"];
		$flat_name=$data["flat"]["flat_name"];
		?>
		<option value="<?php echo $flat_id; ?>" <?php if($flat_id==$child[1]){ echo 'selected';} ?> ><?php echo $flat_name; ?></option>
		<?php } ?>
		</select>
		</td>
		
		<td id="echo_flat_type<?php echo $i; ?>" width="25%">
		
		<select class="span12 m-wrap" id="flat1" name="flat_type" >
		<option value="">Flat type</option>
		<?php 
		
		foreach($result_flat_type as $data) { 
		$flat_type_id=$data["flat_type_name"]["auto_id"];
		$flat_type_name=$data["flat_type_name"]["flat_name"];
		?>
		<option value="<?php echo $flat_type_id; ?>" <?php if($flat_type_id==$child[2]){ echo 'selected';} ?> ><?php echo $flat_type_name; ?></option>
		<?php } ?>
		</select>
		</td>
		
					
		<td width="25%" style=""><input type="text" class="span12 m-wrap textbox" name="area1" id="area" style="font-size:16px;  background-color: white !important;" placeholder="area" value="<?php echo $child[3]; ?>"></td>
		<td align="top"><div class="pull-right"><a href="#" role="button" class="btn mini red delete" id="<?php echo $i; ?>"><i class="icon-remove icon-white"></i></a></div>
		
		</td>
			
	</tr>
<?php } ?>