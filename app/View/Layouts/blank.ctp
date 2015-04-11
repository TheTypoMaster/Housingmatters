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
</script>
