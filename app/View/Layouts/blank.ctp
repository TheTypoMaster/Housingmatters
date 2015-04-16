<?php echo $this->fetch('content'); ?>
<script>
var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
if (test) {
	test.uniform();
}
$('.date-picker').datepicker().on('changeDate', function(){
 $(this).blur();
}); 
$(".chosen").chosen(); 
$('.text-toggle-button').toggleButtons({
	width: 200,
	label: {
		enabled: "Active",
		disabled: "Deactive"
	}
});
if (App.isTouchDevice()) { // if touch device, some tooltips can be skipped in order to not conflict with click events
	jQuery('.tooltips:not(.no-tooltip-on-touch-device)').tooltip();
} else {
	jQuery('.tooltips').tooltip();
}	
</script>
