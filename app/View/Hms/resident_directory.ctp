
<style>
.r_d{
width:32%; float:left; padding:5px;
}

@media (min-width: 650px) and (max-width: 1200px){
.r_d{
width:46%;float:left; padding:5px;
}
}

@media (max-width: 650px) {
.r_d{
width:100%; float:left; padding:5px;
}
}

.hv_b:hover{
background-color:rgb(218, 236, 240);
}
</style>

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

	  function search_record()
		  {
			 
		
		    if(xobj)
			 {			
					
				 
		
           var c1=document.getElementById("get_search").value;
		  
			 var query="?con=" + c1;
			 xobj.open("GET","resident_directory_search_name" +query,true);
			 xobj.onreadystatechange=function()
			  {
			  if(xobj.readyState==4 && xobj.status==200)
			   {	   
			   document.getElementById("view_search").innerHTML=xobj.responseText;
			   }
			  }
			  
			 }
			 xobj.send(null);
		  }
		  
	  function search_wing_record()
		  {
			 
		
		    if(xobj)
			 {			
				
           var c1=document.getElementById("wing_value").value;
			 var query="?con=" + c1;
			 xobj.open("GET","resident_directory_search_wing_ajax" +query,true);
			 xobj.onreadystatechange=function()
			  {
			  if(xobj.readyState==4 && xobj.status==200)
			   {	   
			   document.getElementById("view_search").innerHTML=xobj.responseText;
			   }
			  }
			  
			 }
			 xobj.send(null);
		  }
		  
	</script>

<div id="all_dir">

<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:2px; box-shadow:5px; font-size:16px; color:#006;">
     
                <table width="100%" >
                <tr>
  <td width="60%" style="color:#666666; font-size:24px; padding-left:10px;">Resident Directory  <span style='font-size:18px;'> (<?php echo sizeof($result_user); ?>)<span>  </td>
                <td width="20%" valign="bottom"><select style="" id="wing_value" onchange="search_wing_record()"><option value="0">All Wing</option>
                
                 <?php  
				                                  
                                                   foreach ($result_wing as $collection) 
				                                      {
				                                      $wing_id_edit = $collection['wing']['wing_id'];
				                                      $wing_name_edit = $collection['wing']['wing_name'];	
				                                  ?>
				                                  <option value="<?php echo $wing_id_edit; ?>"><?php  echo $wing_name_edit; ?></option>				
					                              <?php }	?>
                
                
                
                
                </select></td>
                <td width="20%" valign="bottom" style="padding-top:10px;" align="right"><div class="controls"><input type="text" placeholder="Name"  style="" id="get_search" onkeyup="search_record()"></div></td>
                </tr>
                </table>
                 </div>



<div id="view_search" >

 <?php
  
			foreach ($result_user as $collection)            
			{  
				$c_user_id = (int)$collection['user']['user_id'];          
				$c_wing_id = $collection['user']['wing'];
				$medical_pro = @$collection['user']['medical_pro'];
				$c_flat_id = $collection['user']['flat'];
				$c_name = $collection['user']['user_name'];
				@$profile_pic = $collection['user']['profile_pic'];
				$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($c_wing_id,$c_flat_id)));			  
				if(empty($profile_pic))
				{
				$profile_pic="blank.jpg"; 
				}
?>

<div class="r_d fadeleftsome" onclick="view_ticket(<?php echo $c_user_id;?>)">
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<img src="<?php echo $this->webroot ; ?>/profile/<?php echo $profile_pic; ?>" style="float:left;width:25%;height:80px;"/>
<div style="float:left;margin-left:3%;">
<span style="font-size:22px;"><?php echo $c_name; ?> &nbsp; </span> 
<?php if(@$medical_pro==1){ ?> <span style="float:right;color:red; font-size:18px;"> <i class="icon-plus-sign"></i> </span> <?php } ?> <br/>
<span style="font-size:16px;"><?php echo $wing_flat ; ?></span><br>

</div>
</div>
</div>


<?php 
}
?>
</div>
</div>



<div id="view_dir" style="display:none;" class="fadeleftsome">

<br/><br/><div align="center" style="font-size:24px;"><img src="<?php echo $this->webroot ; ?>/as/loading.gif" height="50px" width="50px"/><br/>Please Wait</div>

</div>

<script>
$(document).ready(function() {
	$("#back").live('click',function(){
			$("#view_dir").hide();
			$("#all_dir").show();	
	});
});

</script>

<script>

function view_ticket(id)
{

	$(document).ready(function() {
				
				
				$( "#view_dir" ).load( 'resident_directory_view?id=' + id , function() {
				
				  $("#all_dir").hide();
				 
				  $("#view_dir").show();
				});
		
		
		});
	
}
</script>