<?php 
if($det==1)
{?>
<button type="button" class="btn blue" onclick='user_active(0,<?php echo $user_id ; ?>,<?php echo $i ; ?>);' >Active</button> 
<?php } ?>
<?php 
if($det==0)
{?>
<button type="button" class="btn red " onclick='user_active(1,<?php echo $user_id ; ?>,<?php echo $i ; ?>);'>Deactive</button>
<?php 
} ?>
