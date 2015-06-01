<?php
foreach($result_view as $data)
{
$n_draft_id=$data['notice']['n_draft_id'];
$n_subject=$data['notice']['n_subject'];
$n_message=$data['notice']['n_message'];
$n_date=$data['notice']['n_date'];
$n_time=$data['notice']['n_time'];
}
?>

<div style="background-color:#F3F3F3; border:solid 2px #fcb322; padding:10px; width:80%; margin-left:10%;">
<div align="center" style="background-color:#CCC;"><h3><b><?php echo $n_subject; ?></b></h3></div>
<div align="right"><span ><?php echo $n_date; ?>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $n_time; ?></span></div>
<div align="justify"><p style='font-size:15px;'><?php echo $n_message; ?></p></div>
</div>