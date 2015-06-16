<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<br />
<table style="background-color:white;" class="m-wrap table table-bordered">
<tr>
<th style="text-align:left;">Bill Number</th>
<th style="text-align:left;">Member name</th>
<th style="text-align:left;">Bill Amount</th>
<th style="text-align:left;">Approval</th>
</tr>
</table>