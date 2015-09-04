<div class="hide_at_print">
<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script> 
</div>
<center>  
<div class="hide_at_print">            
<?php
if($s_role_id == 3)
{
?>              
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" class="btn" rel='tab'>Create</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt_view" class="btn" rel='tab'>View</a>
<a href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt_deposit_slip" class="btn yellow" rel='tab'>Deposit Slip</a>
<?php } ?>
</div>
</center>
<a href="print_deposit_slip" class="btn yellow" target="_blank">Generate Deposit Slip</a>


















