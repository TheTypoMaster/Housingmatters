<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<?php
if(!empty($del_id))
{
?>
<!----alert-------------->
<div class="modal-backdrop fade in"></div>
<div   class="modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<form method="post">
<div class="modal-body" style="font-size:16px;">
Are You Sure
<input type="hidden" value="<?php echo $del_id; ?>" name="delete" />
</div> 
<div class="modal-footer">
<a href="flat_type"   class="btn">Cancel</a>
<button type="submit" name="del" class="btn green">Delete</button>
</form>
</div>
</div>
<!----alert-------------->
<?php
}
?>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
<input type="hidden" value="<?php echo $wing_imp; ?>" id="wing_imp" />
<input type="hidden" value="<?php echo $flat_imp; ?>" id="flat_imp" />
<input type="hidden" value="<?php echo $m; ?>" id="m" />
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Society Setup
</div>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li><a href="<?php echo $webroot_path; ?>Hms/master_sm_wing" rel='tab'> Wing</a></li>
<li class="active"><a href="<?php echo $webroot_path; ?>Hms/flat_type" rel='tab'>Flat Type</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/master_sm_flat" rel='tab'>Flat Number</a></li>
<li ><a href="<?php echo $webroot_path; ?>Hms/flat_nu_import" rel='tab'>Flat Number Import</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_details" rel='tab'>Society Details</a></li>
<li><a href="<?php echo $webroot_path; ?>Hms/society_settings" rel='tab'>Society Settings</a></li>
</ul>
<div class="tab-content" style="min-height:300px;">
<div class="tab-pane active" id="tab_1_1">
    
<?php ////////////////////////////////////////////////////////////////////////////////////////////// ?>   
	  <div align="center">
      <div id="ser_top" align="center" ><?php echo @$rr; ?></div>
     <br>
     <div>
     <?php
	if($nnn == 5)
	{
	?>
    <form  class="form-horizontal" method="post" id="contact-form" onsubmit="return validate()">
    <input type="hidden" value="<?php echo @$fl_ti; ?>" id="fl_ti" />
     <input type="hidden" value="<?php echo @$b; ?>" id="b" />
    <table>
    <tr>
    <td style="text-align:center;">
     <label >Wing</label>
     <select name="wing" id="tp" class="m-wrap medium">
     <option value="">Select</option>
     <?php
     foreach($cursor2 as $collection)
	 {
	 $wing_id = (int)$collection['wing']['wing_id'];	 
     $wing_name = $collection['wing']['wing_name'];		 
     ?>
     <!-- <input type="text" class="m-wrap" name="flat_type" maxlength="10" id="tp">-->
     <option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
     <?php } ?>
     </td>
   

<td style="text-align:center;">
<label >Flat Number</span></label>
<input type="text" class="m-wrap medium" name="number" maxlength="10" id="nu"></td>

<td style="text-align:right;"> <input type="submit" class="btn blue" value="Submit"  name="sub" style="margin-top:25px;"></td>
</tr>
<tr>
<td>
<div id="vali"></div>
<label id="tp"></label></td>
<td><label id="nu"></label></td>
<td></td>
</tr>
</table>
<div id="valid"></div>
</form>
    
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
<div style="width:100%;">       
<table class="table table-bordered" style="width:100%; background-color:white;">    
<tr>
<th style="text-align:center;">Sr #</th>    
<th style="text-align:center;">Flat Type</th>    
<th style="text-align:center;">Number of Flat</th>    
<th style="text-align:center;">Edit</th>    
</tr>
<?php
$x=0;
foreach($cursor1 as $collection)
{
$x++;
$auto_id = (int)$collection['flat_type']['auto_id'];
//$flat_type = $collection['flat_type']['flat_name'];
$no_of_flat = $collection['flat_type']['number_of_flat'];
$fl_tp_id = (int)$collection['flat_type']['flat_type_id'];

$fl_tp = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($fl_tp_id)));		
foreach($fl_tp as $collection)
{
//$auto_id1 = (int)$collection['flat_type_name']['auto_id'];	
$flat_type = $collection['flat_type_name']['flat_name'];
}

?>
<tr>
<td style="text-align:center;"><?php echo $x; ?></td>
<td style="text-align:center;"><?php echo $flat_type; ?></td>
<td style="text-align:center;"><?php echo $no_of_flat; ?></td>
<td style="text-align:center;">
<!-- <a href="flat_type?d=<?php echo $fl_tp_id; ?>" class="btn mini black">Delete</a> -->
<a class="btn mini purple" href="flat_type_edit?e=<?php echo $auto_id; ?>">Edit</a></td>
</tr>
<?php
}
?>    
</table>   
</div>    
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>    
    
    <?php } ?>
    <?php if($nnn == 55)
	{

$fl_tp = $this->requestAction(array('controller' => 'hms', 'action' => 'flat_type_name_fetch'),array('pass'=>array($flat_type_id)));		
foreach($fl_tp as $collection)
{
$flat_name = $collection['flat_type_name']['flat_name'];
}
?>
<h4 style="color:red;"><b>You have <?php echo $nof; ?> Flats of <?php echo $flat_name; ?>,  Please Insert Area in square feet:</b></h4>
<form method="post" id="contact-form2">
<input type="hidden" name="nof" value="<?php echo $nof; ?>" id="cnt"/>
<input type="hidden" name="auto_id" value="<?php echo $auto_id; ?>" />
<table border="0" style="width:100%;">
<?php
for($k=1; $k<=$nof; $k++)
	{
	?>
    <tr>
    <td style="text-align:center;">
    <input type="text" name="area<?php echo $k; ?>" class="m-wrap medium" id="ar<?php echo $k; ?>"/>       square feet
    </td>	
	</tr>	
	<?php
	}
	?>
    <tr>
    <td style="text-align:center;">
    <div id="validate_result">
    <p style="color:red;"><?php echo @$vali; ?></p>
    </div>
    </td>
    </tr>
    <tr>
    <td>
    <button type="submit" class="btn green" name="sub_area" style="margin-left:53%;">Submit</button>
    </td> 
    </tr>
   
    </table>
    </form>
    <?php } ?>
    </div>
    </div>
    </div>
   
    <br>
<div class="portlet box" style="width:80%;">
<?php /////////////////////////////////////////////////////////////////////////////////////////// ?>
                   <!-- <div class="portlet-body" style="float:right; width:70%;" align="center">
					<table class="table table-striped table-bordered" id="sample_2" style="width:100%;">
					<thead>
					<tr>
					<th style="text-align:center;">Sr No.</th>
					<th style="text-align:center;">Flat Type</th>
					<th style="text-align:center;">Number of Flat</th>
					</tr>
							</thead>
							<tbody>
							<?php
							$q=0;
							foreach ($cursor1 as $collection) 
							{
							$q++;
     						$auto_id = (int)$collection['flat_type']['auto_id'];
							$name = $collection['flat_type']['flat_name'];
							$no = (int)$collection['flat_type']['number_of_flat'];
							?>
							<tr>
							<td style="text-align:center;"><?php echo $q; ?></td>
							<td style="text-align:center;"><?php echo $name; ?></td>
							<td style="text-align:center;"><?php echo $no; ?></td>
							<?php } ?>
							</tbody>
							</table>
							</div> -->

<?php //////////////////////////////////////////////////////////////////////////////////////////// ?>
</div>
    
  
    </div>
   
    </div>
	
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
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
	     
		 number: {
			 required: true,
			 number: true
			 
	      },

            wing: {
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
	
    
 <script>
$(document).ready(function(){

$.validator.setDefaults({ ignore: ":hidden:not(select)" });


		$('#contact-form2').validate({
		
		errorElement: "label",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        //error.appendTo("label#errors");
						error.appendTo('label#' + element.attr('id'));
                    },
		
		
		
	    rules: {
	     
		 number: {
			 required: true,
			 number: true
			 
	      },

            flat_type: {
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


<script>
function validate()
{
var wing_imp = document.getElementById("wing_imp").value;	
var flat_imp = document.getElementById("flat_imp").value;
var wing = document.getElementById("tp").value;
var flat = document.getElementById("nu").value;
var m =document.getElementById("m").value;

var wing_ex = wing_imp.split(",");
var flat_ex = flat_imp.split(",");
for(var i=0; i<m; i++)
{
var wing_arr = wing_ex[i];
var flat_arr = flat_ex[i];
if(wing == wing_arr && flat == flat_arr)
{
var n = 55;
break;	
}
else
{
var n = 5;	
}
}
if(n == 55)
{
$('#valid').html('<div style="background-color:white; color:red; padding:5px;">With this wing the flat number is already Exist</div>');
return false;
}



}
</script>



<script>
function validate2()
{
var fl_tpi = document.getElementById("fl_ti").value;
var fl_tpe = fl_tpi.split(",");
var flt = document.getElementById("tp").value;
var b = document.getElementById("b").value;
for(var x=0; x<b; x++)
{
var flt2 = fl_tpe[x];

if(flt2 == flt)
{
$('#vali').html('<div style="color:red; padding:5px;">Flat Type Already Exist</div>'); return false;
break;
}
}

}
</script>
	    
        