
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

	
	function designation(c1)
		  {
			 
		
		    if(xobj)
			 {			
				
			 var query="?con=" + c1;
			 xobj.open("GET","hm_assign_module_ajax" +query,true);
			 xobj.onreadystatechange=function()
			  {
			  if(xobj.readyState==4 && xobj.status==200)
			   {	   
			   document.getElementById("show_designation").innerHTML=xobj.responseText;
			   test();
			   }
			  }
			  
			 }
			 xobj.send(null);
		  }
	function test()
	{
	var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
	if (test) {
	test.uniform();
	}
	}



</script>	
<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>                
                   
                
               <form method="post">
                     <div class="control-group" style="width:40%; margin-left:28%;">
                              <div class="controls" >
                              
                              <label style="margin-left:30%;">Society Name</label>
                                
                                
                               
                                 <span style="margin-left:10%;">
                                 <select class="span8 chosen" name="r_name"  data-placeholder="Choose User Name" tabindex="1" onchange="designation(this.value)">
                                    <option value="" style="display:none;"></option>
                                    <?php 
									
									foreach ($result_society as $collection) 
									{
									$society_name = $collection['society']['society_name'];
									$society_id=$collection['society']["society_id"];
                                    ?>
                                    <option value="<?php echo $society_id; ?>" /><?php echo $society_name; ?></option>
                                 	<?php }  ?>
                                 </select>
                                 </span>
                                                          
                              </div>
                           </div>
                    
               
               <div id="show_designation" style="width:60%; margin-left:20%;">
               
               </div>
              
               </form>
    
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>                             
<!-- END PAGE CONTENT-->
			</div>