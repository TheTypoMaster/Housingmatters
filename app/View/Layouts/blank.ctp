<?php echo $this->fetch('content'); ?>
<script>
var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
if (test) {
	test.uniform();
}
$('.date-picker').datepicker().on('changeDate', function(){
 $(this).blur();
}); 
 $('.timepicker-default').timepicker();
$(".chosen").chosen(); 
$('.text-toggle-button').toggleButtons({
	width: 200,
	label: {
		enabled: "Active",
		disabled: "Deactive"
	}
});
$('.basic-toggle-button').toggleButtons();
if (App.isTouchDevice()) { // if touch device, some tooltips can be skipped in order to not conflict with click events
	jQuery('.tooltips:not(.no-tooltip-on-touch-device)').tooltip();
} else {
	jQuery('.tooltips').tooltip();
}
$('.wysihtml5').wysihtml5();
$('.scroller').each(function () {
	$(this).slimScroll({
		//start: $('.blah:eq(1)'),
		size: '7px',
		color: '#a1b2bd',
		height: $(this).attr("data-height"),
		alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
		railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
		disableFadeOut: true
	});
});
jQuery('.popovers').popover();
</script>
<script src="<?php echo $this->webroot ; ?>/as/charts/Chart.js"></script>
<script>
	var pieData = [
			{
				value: <?php echo $open; ?>,
				color:"#d43f3a",
				highlight: "#d43f3a",
				label: "Open Tickets"
			},
			{
				value: <?php echo $open_and_assigned; ?>,
				color: "#eea236",
				highlight: "#eea236",
				label: "Open & assigned Tickets"
			},
			{
				value:<?php echo $closed; ?>,
				color: "#4cae4c",
				highlight: "#4cae4c",
				label: "Closed Tickets"
			}

		];

		
			var ctx = document.getElementById("chart-area").getContext("2d");
			window.myPie = new Chart(ctx).Pie(pieData);
		
</script>