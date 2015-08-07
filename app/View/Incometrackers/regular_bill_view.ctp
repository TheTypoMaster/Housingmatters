<?php 
foreach($result_new_regular_bill as $regular_bill){
	$bill_html=$regular_bill["new_regular_bill"]["bill_html"];
} ?>
<style>
@media screen {
    .bill_on_screen {
       width:70%;
    }
}

@media print {
    .bill_on_screen {
       width:96% !important;
    }
}
</style>
<a href="#" class="btn green pull-right" role="button" onclick="window.print()">Print</a>
<?php echo $bill_html; ?>