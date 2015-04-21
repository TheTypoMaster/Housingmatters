

<script>
$( document ).ready( function() {
   jQuery('.tooltips').tooltip();
   
   
    var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
        if (test) {
            test.uniform();
        }

   
});
</script>






<div class="portlet-body" style='width:49%;margin-left:20%;'>
	<div class="accordion in collapse" id="accordion1" style="height: auto;">
								
								<?php
$i=0;

foreach ($result_hm_modules_assign as $collection) 
{
	$i++;	
	echo $module_id=(int)$collection["hm_modules_assign"]["module_id"];
	
$result_data=$this->requestAction(array('controller' => 'hms', 'action' => 'fetch_main_module_name'), array('pass' => array($module_id)));
//pr($result_data);
foreach ($result_data as $collection) 
{	
	$module_name=$collection["main_module"]["module_name"];
	$icon=$collection["main_module"]["icon"];
}
	?>
<div class="accordion-group" style="";>
	<div class="accordion-heading" style="padding: 5px;">
	<table width="100%" style="font-size: 15px;">
		<tr>
		<td width="30%"><i class="<?php echo $icon ; ?>"></i> <?php echo $module_name; ?></td>
		<td width="5%">
		<a class="btn mini   accordion-toggle collapsed"  data-toggle="collapse" data-parent="#accordion1" href="#collapse<?php echo $i; ?>" style="">
		<i class="icon-search"></i> sub-modules
		</a>
		</td>
		</tr>
	</table>
	</div>
	<div id="collapse<?php echo $i; ?>" class="accordion-body collapse" style="height: 0px;">
		<div class="accordion-inner">
			<?php
			$result_sub_module=$this->requestAction(array('controller' => 'hms', 'action' => 'fetch_sub_module'), array('pass' => array($module_id)));
			foreach ($result_sub_module as $collection) 
			{	
				$sub_module_id=$collection["sub_modules"]["auto_id"];
				$sub_module_name=$collection["sub_modules"]["sub_module_name"];
				$des=@$collection["sub_modules"]["des"];
				
			$n=$this->requestAction(array('controller' => 'hms', 'action' => 'fetch_role_privileges'), array('pass' => array($sub_module_id)));

				?>
				
				<div style="padding:5px; font-size:14px;border-bottom:solid 1px #ccc;">
				<label >
				<input type="checkbox" <?php if($n>0) { ?>checked="checked" <?php } ?> name="ch<?php echo $sub_module_id; ?>" value="1" style="height: 18px;width: 18px;"/>
				<span class="tooltips"  data-placement="right" data-original-title="<?php echo $des; ?>"><?php echo $sub_module_name; ?></span>
				
				
				</label>
				
				</div>
				
				<?php
			} ?>
		</div>
	</div>
</div>
									
<?php } ?>	
								
	<div style="padding: 10px;" >
<button type="submit" name="add_role"  class="btn blue">Assign Modules</button>
</div>	
</form>							
		</div>
	</div>
	
	
	

