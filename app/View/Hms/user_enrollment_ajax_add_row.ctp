
<tr id="tr<?php echo $h; ?>">
<td><input type="text" class="span12 m-wrap tboxClass" name="name<?php echo $h; ?>" id="name<?php echo $h; ?>" style="background-color: white !important;" placeholder="Name*" value=""></td>
<td>
<select class="span12 m-wrap wing" name="wing<?php echo $h; ?>" id="wing<?php echo $h; ?>" inc_id="<?php echo $h; ?>">
<option value="">-Wing-</option>
<?php 
foreach($result_wing as $data) { 
$wing_id=$data["wing"]["wing_id"];
$wing_name=$data["wing"]["wing_name"];
?>
<option value="<?php echo $wing_id; ?>"><?php echo $wing_name; ?></option>
<?php } ?>
</select>
</td>
<td id="echo_flat<?php echo $h; ?>">
<select class="span12 m-wrap" name="flat<?php echo $h; ?>" id="flat<?php echo $h; ?>">
<option value="">-Flat-</option>
</td>
<td><input type="text" class="span12 m-wrap" name="email<?php echo $h; ?>" id="email<?php echo $h; ?>" style="font-size:16px;  background-color: white !important;" placeholder="Email*" value=""></td>
<td><input type="text" class="span12 m-wrap" name="mobile<?php echo $h; ?>" id="mobile<?php echo $h; ?>" style="font-size:16px;  background-color: white !important;" placeholder="Mobile*" value=""></td>
<td>
<div class="controls">
    <label class="radio line"><input type="radio" class="owner" name="owner<?php echo $h; ?>" value="1" inc_id="<?php echo $h; ?>">Yes</label>
    <label class="radio line"><input type="radio" class="owner" name="owner<?php echo $h; ?>" value="2" inc_id="<?php echo $h; ?>">No</label>
</div>
</td>
<td>
<div class="controls" id="committe<?php echo $h; ?>">
    <label class="radio line"><input type="radio" name="committe<?php echo $h; ?>" value="1">Yes</label>
    <label class="radio line"><input type="radio" name="committe<?php echo $h; ?>" value="2">No</label>
</div>
<div id="no<?php echo $h; ?>" style="display:none;">No</div>
</td>
<td>
<div class="controls">
    <label class="radio line"><input type="radio" name="residing<?php echo $h; ?>" value="1">Yes</label>
    <label class="radio line"><input type="radio" name="residing<?php echo $h; ?>" value="2">No</label>
</div>
</td>
</tr>
<script>
$( document ).ready( function() {
    var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)");
        if (test) {
            test.uniform();
        }
});
</script>