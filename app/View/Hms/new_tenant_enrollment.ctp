    <script type="text/javascript">
 var xobj;
   //modern browers
   if(window.XMLHttpRequest)
    {
	  xobj=new XMLHttpRequest();
	  }
	  //for ie
	  else if(window.ActiveXObject)
	   {
	    xobj=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else
		{
		  alert("Your broweser doesnot support ajax");
		  }
	function tenant_update()
		  {
		    if(xobj)
			 {	
			var c1= document.getElementById('ten_update').value;
			
			var query="?con=" + c1;
			 xobj.open("GET","new_tenant_enrollment_ajax" +query,true);
			 xobj.onreadystatechange=function()
			  {
			  if(xobj.readyState==4 && xobj.status==200)
			   {	   
			   document.getElementById("auto_field").innerHTML=xobj.responseText;
			   test12();
			   }
			  }
			  
			 }
			 xobj.send(null);
		  }
		  
		  
	  
function test12()
{
 var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
        if (test) {
            test.uniform();
        }

}	
function wing_flat()
{		
if(xobj)
{

var c2=document.getElementById("wi_flat").value;
var query="?con2=" + c2;
xobj.open("GET","resident_signup_wing_flat_ajax" +query,true);
xobj.onreadystatechange=function()
{
if(xobj.readyState==4 && xobj.status==200)
{	   
document.getElementById("echo_flat").innerHTML=xobj.responseText;
}
}

}
xobj.send(null);
}
		  
    </script>
<script>
 
    function datepicker()
    {                    
    $(document).ready(function() {	
    $('.date-picker').datepicker();
    $('.date-picker').datepicker().on('changeDate', function(){
    $(this).blur();
    })});
    }
    </script>

<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {

$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

<!--<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Tenant Enrollment
</div>-->

<div class="portlet-body" style="padding:10px;";>
									<!--BEGIN TABS-->
									<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">
											
										</ul>
										<div class="tab-content" style="min-height:500px;">
											<div class="tab-pane active" id="tab_1_1">
					
					
					
					<form  id="contact-form" class="form-horizontal" method="post" enctype="multipart/form-data" style='center'>
                         <fieldset>
                         
                         
                            <div class="control-group ">
                              <div class="controls">
                               <label class="" style="font-size:14px;"></label>
                                 <select name="sel" class="span5 m-wrap " onChange="tenant_update();" id='ten_update'  >
                            <option value="">--Please select any Tenant--*</option>
                            <?php
							
							foreach ($result_user as $collection) 
							{
							$user_tenant_id=$collection['user']['user_id'];
							$wing_id=$collection['user']['wing'];
							$flat_id=$collection['user']['flat'];
							$user_name=$collection['user']['user_name'];
							$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing_id,$flat_id)));
	
				?>			
                            <option value="<?php echo $user_tenant_id ?> "><?php echo $user_name ?> ( <?php echo $wing_flat ; ?> )</option>
                            <?php } ?>
                            </select> 
                              </div>
                           </div>
						   
						
                         <div id='auto_field'> 
						 
						 <!-- <div class="control-group ">
                            <div class="controls">
                               <label class="" style="font-size:14px;" >Name </label>
                                 <input type="text" class="span5 m-wrap" id="inputWarning" name="name_tenant">
                              </div>
                           </div>
						    
						<div class="control-group" >
						<div class="controls">
						<select id="wi_flat" onChange="wing_flat()" class=" span5 m-wrap" name="wing"  data-placeholder="Choose a Category"   tabindex="1">
						<option value="">--Wing(Block)--</option>
						<?php

						foreach ($result_wing as $db) 
						{
						$c_wing_id=$db['wing']["wing_id"];
						$c_wing_name=$db['wing']["wing_name"];
						?>
						<option value="<?php echo $c_wing_id; ?>"><?php echo $c_wing_name; ?></option>
						<?php } ?>
						</select>
						</div>
						</div>
						<div class="control-group" id="echo_flat" style="width:51.5%">
						<div class="controls">
						<select class=" span12 m-wrap" name="flat"  data-placeholder="Choose a Category"   tabindex="1">
						<option value="" style="">--Flat--</option>
						</select>
						</div>
						</div> -->
						 
						 
						 
                         <div class="control-group ">
                              <div class="controls">
                               <label class="" style="font-size:14px;" > Permanent address </label>
                                <textarea cols="" rows="5" name="address" class="span5 m-wrap" style="resize:none" ></textarea>
                              </div>
                           </div>
                          
                           <div class="control-group ">
                            <div class="controls">
                               <label class="" style="font-size:14px;" >Tenancy start date </label>
                                 <input type="text" class="span5 m-wrap  date-picker"  data-date-format="dd-mm-yyyy" name="start_date">
                              </div>
                           </div>
                           <div class="control-group ">
                           <div class="controls">
                               <label class="" style="font-size:14px;" >Tenancy end date </label>
                                 <input type="text" class="span5 m-wrap  date-picker" data-date-format="dd-mm-yyyy"  name="end_date">
                              </div>
                           </div>
                         
                            <div class="control-group ">
                            <div class="controls">
                               <label class="" style="font-size:14px;" >Verification </label>
                                 <input type="text" class="span5 m-wrap" id="inputWarning" name="verification">
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <div class="controls">
                                 <label class="">
                                <input type="checkbox" value="1" name="ten_agr" > Tenancy agreement 
                                 </label>
                                 <label class="">
                                <input type="checkbox" value="1" name="pol_ver" >Police verification
                                 </label>
                              </div>
                           </div>
                            </div> 
                           <div class="form-actions">
                              <input type="submit" class="btn green" value="Submit" name="sub">
                           </div>
                           
                           </fieldset>
                        </form>
					
					
					
					
											</div>
											
										</div>
									</div>
									<!--END TABS-->
</div>




<script>
$(document).ready(function(){
$('#contact-form').validate({
rules: {
sel: {
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