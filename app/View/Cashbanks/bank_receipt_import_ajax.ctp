<div style="background-color:white; width:100%; overflow:auto;">
<div class="modal-header">
<h4 id="myModalLabel1">Import csv</h4>
</div>
<div class="modal-body" style="overflow:auto;">
<div id="vali"></div>
<br />
<table class="table table-bordered" style="width:100%; background-color:white;" id="open_bal">
<tr>
<th>Tranasction Date</th>
<th>Receipt Mode</th>
<th>Cheque No</th>
<th>Reference/UTR#</th>
<th>Drawn Bank</th>
<th>Date</th>
<th>Deposited In</th>
<th>Party Name</th>
<th>Amount</th>
<th>Delete</th>
</tr>
<?php 
foreach($aaa as $data)
{ 
$TransactionDate = $data[0];
$ReceiptMod = $data[1];
$ChequeNo = $data[2];
$Reference = $data[3];
$DrawnBankname = $data[4];
$bank_id = $data[5];
$Date1 = $data[6];
$auto_id = $data[7];
$Amount = $data[8];

/*
$c = (int)strcasecmp("Cheque",$ReceiptMod);
$n = (int)strcasecmp("NEFT",$ReceiptMod);
$p = (int)strcasecmp("PG",$ReceiptMod);
if($c == 0)
{
$mode_id = 1;	
}
if($n == 0)
{
$mode_id = 1;	
}
if($p == 0)
{
$mode_id = 1;	
}
*/
?>
<tr>
<td>
<input type="text" class="date-picker m-wrap span12" data-date-format="dd-mm-yyyy" name="date" placeholder="Transaction Date" style="background-color:white !important;" id="date" value="<?php echo $TransactionDate; ?>">
</td>
<td>
<input type="text" name="" class="m-wrap span12" readonly="readonly" value="<?php echo $ReceiptMod; ?>" />
</td>
<td>
<input type="text" name="" class="m-wrap span12" value="<?php echo $ChequeNo; ?>" />
</td>
<td>
<input type="text" name="" class="m-wrap span12" value="<?php echo $Reference; ?>" />
</td>
<td>
<input type="text" name="" class="m-wrap span12" value="<?php echo $DrawnBankname; ?>" />
</td>
<td>
<input type="text" class="date-picker m-wrap span12" data-date-format="dd-mm-yyyy" name="date" placeholder="Transaction Date" style="background-color:white !important;" id="date" value="<?php echo $Date1; ?>">
</td>
<td> 
<select name="" class="m-wrap span12">
<?php
foreach($cursor1 as $collection)
{
$b_id = (int)$collection['ledger_sub_account']['auto_id'];
$name = $collection['ledger_sub_account']['name'];		
?>
<option value="<?php echo $b_id; ?>" <?php if($b_id == $bank_id) { ?> selected="selected" <?php } ?> ><?php echo $name; ?></option>
<?php
}
?>
</select>
</td>
<td>
<select name="" class="m-wrap span12">
<?php
foreach($cursor2 as $collection)
{
$a_id = (int)$collection['ledger_sub_account']['auto_id'];
$name1 = $collection['ledger_sub_account']['name'];		
?>
<option value="<?php echo $b_id; ?>" <?php if($a_id == $auto_id) { ?> selected="selected" <?php } ?> ><?php echo $name1; ?></option>
<?php
}
?>
</select>
</td>
<td>
<input type="text" name="" class="m-wrap span12" value="<?php echo $Amount; ?>" />
</td>
<th>Delete</th>
</tr>
<?php
}
?>
</table>
</div>
<div class="modal-footer">
<a class="btn" href="<?php echo $webroot_path; ?>Cashbanks/bank_receipt" rel="tab">Cancel</a>
<button type="submit" class="btn blue import_op">Import</button>
</div>
</div>