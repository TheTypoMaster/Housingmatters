<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>


<style>
.r_d{
width:14%; float:left; padding:5px;
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

.hv_b{
background-color:rgba(218, 221, 240, 0.58);
}
</style>




<div>

<div>
<span class="label label-info"style="padding:10px;font-size:16px;float:right;">Total empty four wheeler slot: <?php echo $four_n;?></span></div></br><br>
<div><span class="label label-info"style="padding:10px;font-size:16px;float:right;">Total empty two wheeler slot: <?php echo $two_n;?></span></div>
</div>


<div align="center"><select class="chosen"><option></option></select></div>

<!--<div><button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></div>-->
<br><br>





<div style="overflow:auto;">
<?php
$i=0;
foreach($result_parking as $data)
{
$i++;
@$slot_no=@$data['parking']['slot_no'];
@$type=@$data['parking']['type'];
@$stiker_number=@$data['parking']['stiker_number'];

?>

<div class="r_d fadeleftsome">
<div class="hv_b" style="overflow: auto;padding: 5px;cursor: pointer;" title="">
<div style="float:left;margin-left:3%;">

<span style="font-size:14px; float:left;">Stiker Number : &nbsp; </span><span style="font-size:14px; float:right;"><?php echo $stiker_number; ?> &nbsp; </span>  
 <br/>
<span style="font-size:14px; float:left;">Type : &nbsp; </span><span style="font-size:14px; float:right;"><?php echo $type ; ?>-wheeler </span><br>
<span style="font-size:14px; float:left;">Slot number : &nbsp; </span><span style="font-size:14px; float:right;"><?php echo $slot_no ; ?></span><br>
</div>
</div>
</div>
<?php  } ?>
</div>