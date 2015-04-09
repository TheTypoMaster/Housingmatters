<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {
$("#fix<?php echo @$id_current_page; ?>").removeClass("blue");
$("#fix<?php echo @$id_current_page; ?>").addClass("red");
});
</script>

<div align="center">
<a href="events" class="btn blue" rel='tab'>Table View</a>
<a href="events_calendar" class="btn red" rel='tab'>Calendar View</a>
</div>
<br/>
<div id="calendar_div" style="width:80%;margin-left:10%;"></div>
<script>
$(document).ready(function() {
	$("#calendar_div").html('<div align="center"><img src="<?php echo $this->webroot ; ?>/as/windows.gif" /></div>').load('<?php echo $this->webroot; ?>Hms/calendar');

	$(".next").live('click',function(){
		var d=$(this).attr('result');
		
		$("#calendar_div").html('<div align="center"><img src="<?php echo $this->webroot ; ?>/as/windows.gif" /></div>').load('<?php echo $this->webroot; ?>Hms/calendar?m_y='+d);
	 });
});
</script>