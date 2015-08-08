<a href="#" class="btn green pull-right hide_at_print" role="button" onclick="window.print()"><i class="icon-print"></i> Print All</a>
<?php 
foreach($result_new_regular_bill as $regular_bill){
	echo $bill_html=$regular_bill["new_regular_bill"]["bill_html"];
	echo '<DIV style="page-break-after:always"></DIV>';
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

