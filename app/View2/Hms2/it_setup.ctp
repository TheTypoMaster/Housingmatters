<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>            
			<table width="100%" border="1" bordercolor="#FFFFFF" cellpadding="0">
            <tr>
            <td style="width:25%">
            <a href="it_regular_bill" class="btn blue btn-block"   style="font-size:16px;"> Regular Bill</a>
            </td>
            <td style="width:25%">
             <a href="it_supplimentry_bill" class="btn blue btn-block"  style="font-size:16px;">Supplementary Bill</a>
            </td>
            <td style="width:25%">
            <a href="in_head_report" class="btn blue btn-block"  style="font-size:16px;">Reports</a>
            </td>
            <td style="width:25%">
            <a href="select_income_heads" class="btn red btn-block"  style="font-size:16px;">Set-Up</a>
            </td>
            </tr>
            </table>
            
           <table  align="center" border="1" bordercolor="#FFFFFF" cellpadding="0">
            <tr>
			<td><a href="select_income_heads" class="btn">Selection of Income Heads</a>
			</td>
			<!--<td>
            <a href="it_due_tax" class="btn" style="font-size:16px;">Due tax</a>
            </td> -->
            <td>
            <a href="it_setup" class="btn yellow" style="font-size:16px;">Terms & Condition</a>
            </td>
            <td>
            <a href="master_rate_card" class="btn" style="font-size:16px;">Rate Card</a>
            </td>
			<td>
            <a href="master_noc" class="btn" style="font-size:16px;">Non Occupancy Charges</a>
            </td>
			<td>
            <a href="it_penalty" class="btn" style="font-size:16px;">Penalty Option</a>
            </td>
			</tr>
			</table> 
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
 
		

			<div style="width:70%; margin-left:15%;">
			<div class="row-fluid"  >
			<div class="span12">
			<div class="portlet box green" style="border:solid 1px #ffb848;">
			<div class="portlet-body form">
			<h3 class="block"></h3>

           <form  class="form-horizontal" method="post" id="contact-form">

			<div class="control-group ">
			<div class="controls">
			<label class="" style="font-size:14px;" >Terms & Conditions</label>
			<textarea class="span10 m-wrap" name="terms" style="resize:none; height:150px;" rows="3" id="tem" placeholder="Please Type Terms & Condition"></textarea>
			 
            <label id="tem"></label>
            </div>
			</div>
			<div class="form-actions">
			<input type="submit" class="btn green" value="Submit" name="sub">
			</div>
            </form>

			</div>
			</div>
			</div>
			</div>
			</div>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

								<div class="portlet box yellow " style="width:70%; margin-left:15%; border:solid 1px #ffb848;">
								<div class="portlet-body">
								<form method="post">
								<table class="table table-bordered table-hover">
								<thead>
								<tr>
								<th style="width:5%;">#</th>
								<th style="width:95%;">Terms & Conditions</th>
								<th>Edit</th>
								<th>Delete</th>
								</tr>
								</thead>
								<tbody>
								
								
								<?php
								$q=0;
								foreach ($cursor1 as $collection) 
								{
								$q++;
								$terms_id = (int)$collection['terms_conditions']['terms_conditions_id'];
								$terms_conditions=$collection['terms_conditions']['terms_conditions'];
								?>
								
								
								<tr>
								<td><?php echo $q; ?></td>
								<td><?php echo $terms_conditions; ?></td>
								<td><a href="#col<?php echo $terms_id; ?>"  class="accordion-toggle collapsed btn mini purple" data-toggle="collapse" data-parent="#accordion1">Edit</a></td>
								
								
								
								<td><a href="it_setup?d=<?php echo $terms_id; ?>" class="btn mini black">Delete</a></td>
								</tr>
								
								<tr>
								<td colspan="4" style="text-align:center; margin:0px;; padding:0px;">
								<div id="col<?php echo $terms_id; ?>" class="accordion-body collapse ">
								<div class="accordion-inner">
								<input type="hidden" value="<?php echo $terms_id; ?>" name="tms_id">
								
								<input type="text" name="edit_terms<?php echo $terms_id; ?>" class="m-wrap large" value=" <?php echo $terms_conditions; ?>">
								<button type="submit" name="edt_tms<?php echo $terms_id; ?>" class="btn green">Save</button>
								</div>
								</div>
								</td>
								</tr>
								
 								<?php } ?>
								</tbody>
								</table>
								</form>
								
								</div>
								</div>


<?php ///////////////////////////////////////////////////////////////////////////////////////////////// ?>

<script>
$(document).ready(function(){

$.validator.setDefaults({ ignore: ":hidden:not(select)" });


		$('#contact-form').validate({
		
		errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    },
		
		
		
	    rules: {
	     
		 terms: {
			 required: true
	      },

		},
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });

}); 
</script>	
	

































































