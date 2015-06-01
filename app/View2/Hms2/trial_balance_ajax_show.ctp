<?php

$from = date("Y-m-d", strtotime($from_d));
$from = new MongoDate(strtotime($from));

$to = date("Y-m-d", strtotime($to_d));
$to = new MongoDate(strtotime($to));
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<div class="hide_at_print">
<span style="float:right;"><a href="trial_balance_excel?f=<?php echo $from_d; ?>&t=<?php echo $to_d; ?>&tp=<?php echo $wise; ?>" 
class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button> 
</span> 
</div> 
 
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<?php
if($wise == 1)
{
?>
<center>
		<br />	<br /><br />
<h4><b>Sundry Creditors Control A/c</b></h4>		
		<table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
        <?php
		foreach ($cursor1 as $collection) 
		{
		$society_name = $collection['society']['society_name'];
		}
		?>
		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;"><?php echo $society_name; ?>
		</p>
		</th>
		</tr>

		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;">
		Trial balance For The Period <?php echo $from_d; ?> to <?php echo $to_d; ?>
		</p>
		</th>
		</tr>
		
		
		<tr>
		<th>Account Name</th>
		<th>Opening Balance</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Closing balance</th>
		</tr>

		<?php
$grand_total_debit = 0;
$grand_total_credit = 0;
$grand_total_opening_balance = 0;
$grand_total_closing_balance = 0;
foreach($cursor3 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
foreach ($ledger1 as $collection) 
{
$amount1 = $collection['ledger']['amount'];
$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$receipt_id = (int)$collection['ledger']['receipt_id'];


$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
foreach ($module1 as $collection) 
{
$module_name = @$collection['account_category']['ac_category'];
}

$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
foreach ($date_fetch as $collection) 
{
$date1 = @$collection[$module_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$module_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$module_name]['date'];	
}
}		

		
		if($date1 < $from)
		{
		if($ammount_type_id1 == 1)
		{
		$total_opening_balance = $total_opening_balance - $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_opening_balance = $total_opening_balance + $amount1;	
		
		}
		}
		
		if($date1 >= $from && $date1 <= $to)
		{
		if($ammount_type_id1 == 1)
		{
		$total_debit1 = $total_debit1 + $amount1;	
		$grand_total_debit = $grand_total_debit + $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_credit1 = $total_credit1 + $amount1;	
		$grand_total_credit = $grand_total_credit + $amount1;
		}
		}	
		}
		
		
		if($total_debit1 != 0 || $total_credit1 != 0)
		{
		$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
		$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
		$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;
		
        ?>
		
		<tr>
		<td>          
				<?php echo $account_name; ?>
		
		</td>
		<td><?php 
		if($total_opening_balance > 0)
		{
		$total_opening_balance = $total_opening_balance.'Cr';
		}
		else if($total_opening_balance < 0)
		{
		$total_opening_balance = abs($total_opening_balance);
		$total_opening_balance = $total_opening_balance.'Dr';
		}
		echo $total_opening_balance; ?></td>
		<td><?php echo $total_debit1; ?></td>
		<td><?php echo $total_credit1; ?></td>
		<td><?php 
		if($total_closing_balance > 0)
		{
		$total_closing_balance = $total_closing_balance.'Cr';
		}
		else if($total_closing_balance < 0)
		{
		$total_closing_balance = abs($total_closing_balance);
		$total_closing_balance = $total_closing_balance.'Dr';
		}
		echo $total_closing_balance; ?></td>
		</tr>
		
		<?php }} ?>
		
		<tr>
		<th>Total</th>
		<th><?php 
		if($grand_total_opening_balance > 0)
		{
		$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
		}
		else if($grand_total_opening_balance < 0)
		{
		$grand_total_opening_balance = abs($grand_total_opening_balance);
		$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
		}
		echo $grand_total_opening_balance; ?></th>
		<th><?php echo $grand_total_debit; ?></th>
		<th><?php echo $grand_total_credit; ?></th>
		<th><?php 
		if($grand_total_closing_balance > 0)
		{
		$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
		}
		else if($grand_total_closing_balance < 0)
		{
		$grand_total_closing_balance = abs($grand_total_closing_balance);
		$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
		}
		echo $grand_total_closing_balance; ?></th>
		</tr>
		</table>
		
		
		
		
		







<?php

}
?>
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php
if($wise == 4)
{
?>
<center>
		<br />	<br /><br />	
		<h4><b>Bank Accounts</b></h4>	
		<table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
        <?php
		foreach ($cursor1 as $collection) 
		{
		$society_name = $collection['society']['society_name'];
		}
		?>
		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;"><?php echo $society_name; ?>
		</p>
		</th>
		</tr>

		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;">
		Trial balance For The Period <?php echo $from_d; ?> to <?php echo $to_d; ?>
		</p>
		</th>
		</tr>
		
		
		<tr>
		<th>Account Name</th>
		<th>Opening Balance</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Closing balance</th>
		</tr>

		<?php
		$grand_total_debit = 0;
		$grand_total_credit = 0;
        $grand_total_opening_balance = 0;
		$grand_total_closing_balance = 0;
foreach($cursor5 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
				foreach ($ledger1 as $collection) 
				{
				$amount1 = $collection['ledger']['amount'];
				$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
				$module_id = (int)@$collection['ledger']['module_id'];
		        $receipt_id = (int)$collection['ledger']['receipt_id'];


$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
		foreach ($module1 as $collection) 
		{
		$module_name = @$collection['account_category']['ac_category'];
		}

	$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
		foreach ($date_fetch as $collection) 
		{
		$date1 = @$collection[$module_name]['transaction_date'];
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['posting_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['purchase_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['date'];	
		}
		}		
		
		
		if($date1 < $from)
		{
		if($ammount_type_id1 == 1)
		{
		$total_opening_balance = $total_opening_balance - $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_opening_balance = $total_opening_balance + $amount1;	
		
		}
		}
		
		if($date1 >= $from && $date1 <= $to)
		{
		if($ammount_type_id1 == 1)
		{
		$total_debit1 = $total_debit1 + $amount1;	
		$grand_total_debit = $grand_total_debit + $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_credit1 = $total_credit1 + $amount1;	
		$grand_total_credit = $grand_total_credit + $amount1;
		}
		}	
		}
		
		
		if($total_debit1 != 0 || $total_credit1 != 0)
		{
		$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
		$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
		$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;
		
        ?>
		
		<tr>
		<td>          
				<?php echo $account_name; ?>
		
		</td>
		<td><?php 
		if($total_opening_balance > 0)
		{
		$total_opening_balance = $total_opening_balance.'Cr';
		}
		else if($total_opening_balance < 0)
		{
		$total_opening_balance = abs($total_opening_balance);
		$total_opening_balance = $total_opening_balance.'Dr';
		}
		echo $total_opening_balance; ?></td>
		<td><?php echo $total_debit1; ?></td>
		<td><?php echo $total_credit1; ?></td>
		<td><?php 
		if($total_closing_balance > 0)
		{
		$total_closing_balance = $total_closing_balance.'Cr';
		}
		else if($total_closing_balance < 0)
		{
		$total_closing_balance = abs($total_closing_balance);
		$total_closing_balance = $total_closing_balance.'Dr';
		}
		echo $total_closing_balance; ?></td>
		</tr>
		
		<?php }} ?>
		
		<tr>
		<th>Total</th>
		<th><?php 
		if($grand_total_opening_balance > 0)
		{
		$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
		}
		else if($grand_total_opening_balance < 0)
		{
		$grand_total_opening_balance = abs($grand_total_opening_balance);
		$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
		}
		
		echo $grand_total_opening_balance; ?></th>
		<th><?php echo $grand_total_debit; ?></th>
		<th><?php echo $grand_total_credit; ?></th>
		<th><?php 
		if($grand_total_closing_balance > 0)
		{
		$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
		}
		else if($grand_total_closing_balance < 0)
		{
		$grand_total_closing_balance = abs($grand_total_closing_balance);
		$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
		}
		
		echo $grand_total_closing_balance; ?></th>
		</tr>
		</table>
		
		
		
		
		







<?php

}
?>
<?php///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

<?php
if($wise == 2)
{
?>
<center>
		<br />	<br /><br />	
		<h4><b>Sundry Debtors Control A/c</b></h4>	
		<table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
        <?php
		foreach ($cursor1 as $collection) 
		{
		$society_name = $collection['society']['society_name'];
		}
		?>
		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;"><?php echo $society_name; ?>
		</p>
		</th>
		</tr>

		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;">
		Trial balance For The Period <?php echo $from_d; ?> to <?php echo $to_d; ?>
		</p>
		</th>
		</tr>
		
		
		<tr>
		<th>Account Name</th>
		<th>Opening Balance</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Closing balance</th>
		</tr>

		<?php
		$grand_total_debit = 0;
		$grand_total_credit = 0;
        $grand_total_opening_balance = 0;
		$grand_total_closing_balance = 0;
foreach($cursor4 as $collection)
{
$auto_id11 = (int)$collection['ledger_sub_account']['auto_id'];
$account_name = $collection['ledger_sub_account']['name'];
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($auto_id11)));		
				foreach ($ledger1 as $collection) 
				{
				$amount1 = $collection['ledger']['amount'];
				$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
				$module_id = (int)@$collection['ledger']['module_id'];
		        $receipt_id = (int)$collection['ledger']['receipt_id'];


$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
		foreach ($module1 as $collection) 
		{
		$module_name = @$collection['account_category']['ac_category'];
		}

	$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
		foreach ($date_fetch as $collection) 
		{
		$date1 = @$collection[$module_name]['transaction_date'];
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['posting_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['purchase_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['date'];	
		}
		}		
		
		
		if($date1 < $from)
		{
		if($ammount_type_id1 == 1)
		{
		$total_opening_balance = $total_opening_balance - $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_opening_balance = $total_opening_balance + $amount1;	
		}
		}
		
		if($date1 >= $from && $date1 <= $to)
		{
		if($ammount_type_id1 == 1)
		{
		$total_debit1 = $total_debit1 + $amount1;	
		$grand_total_debit = $grand_total_debit + $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_credit1 = $total_credit1 + $amount1;	
		$grand_total_credit = $grand_total_credit + $amount1;
		}
		}	
		}
		
		
		if($total_debit1 != 0 || $total_credit1 != 0)
		{
		$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1;
		$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
		$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;
		
        ?>
		
		<tr>
		<td>          
				<?php echo $account_name; ?>
		
		</td>
		<td><?php 
		if($total_opening_balance > 0)
		{
		$total_opening_balance = $total_opening_balance.'Cr';
		}
		else if($total_opening_balance < 0)
		{
		$total_opening_balance = abs($total_opening_balance);
		$total_opening_balance = $total_opening_balance.'Dr';
		}
		echo $total_opening_balance; ?></td>
		<td><?php echo $total_debit1; ?></td>
		<td><?php echo $total_credit1; ?></td>
		<td><?php 
		if($total_closing_balance > 0)
		{
		$total_closing_balance = $total_closing_balance.'Cr';
		}
		else if($total_closing_balance < 0)
		{
		$total_closing_balance = abs($total_closing_balance);
		$total_closing_balance = $total_closing_balance.'Dr';
		}
		
		echo $total_closing_balance; ?></td>
		</tr>
		
		<?php }} ?>
		
		<tr>
		<th>Total</th>
		<th><?php 
		if($grand_total_opening_balance > 0)
		{
		$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
		}
		else if($grand_total_opening_balance < 0)
		{
		$grand_total_opening_balance = abs($grand_total_opening_balance);
		$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
		}
		echo $grand_total_opening_balance; ?></th>
		<th><?php echo $grand_total_debit; ?></th>
		<th><?php echo $grand_total_credit; ?></th>
		<th><?php
        if($grand_total_closing_balance > 0)
        {		
        $grand_total_closing_balance = $grand_total_closing_balance.'Cr';
        }
		else if($grand_total_closing_balance < 0)
		{
		$grand_total_closing_balance = abs($grand_total_closing_balance);
		$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
		}
		echo $grand_total_closing_balance; ?></th>
		</tr>
		</table>
		
<?php
}
?>
<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
 <?php       
		if($wise == 3)
		{
		?>
		<center>
		<br />	<br /><br />	
		<table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
        <?php
		foreach ($cursor1 as $collection) 
		{
		$society_name = $collection['society']['society_name'];
		}
		?>
		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;"><?php echo $society_name; ?>
		</p>
		</th>
		</tr>

		<tr>
		<th colspan="5" style="text-align:center;">
		<p style="font-size:15px;">
		Trial balance For The Period <?php echo $from_d; ?> to <?php echo $to_d; ?>
		</p>
		</th>
		</tr>
		
		
		<tr>
		<th>Account Name</th>
		<th>Opening Balance</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Closing balance</th>
		</tr>
<?php
		$grand_total_debit = 0;
		$grand_total_credit = 0;
        $grand_total_opening_balance = 0;
		$grand_total_closing_balance = 0;
foreach($cursor2 as $collection)
{
 $auto_id11 = (int)$collection['accounts_category']['auto_id'];
$result11 = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group_fetch'),array('pass'=>array($auto_id11)));
foreach($result11 as $collection)
{
$auto_id22 = (int)$collection['accounts_group']['auto_id'];
$result22 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array($auto_id22)));
foreach($result22 as $collection)
{
$auto_id3 = (int)$collection['ledger_account']['auto_id'];
$account_name = $collection['ledger_account']['ledger_name'];

if($auto_id3 == 34 || $auto_id3 == 15 || $auto_id3 == 33 || $auto_id3 == 35)
{	

		
$total_debit1 = 0;
$total_credit1 = 0;
$total_opening_balance = 0;
$total_closing_balance = 0;
$result_lsa1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch2'),array('pass'=>array($auto_id3)));
				foreach ($result_lsa1 as $collection) 
				{
				$sub_id1 = (int)$collection['ledger_sub_account']['auto_id'];
				$sub_account_name1 = $collection['ledger_sub_account']['name'];
			
$ledger1 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($sub_id1)));		
				foreach ($ledger1 as $collection) 
				{
				$amount1 = $collection['ledger']['amount'];
				$ammount_type_id1 = (int)$collection['ledger']['amount_category_id'];
				$module_id = (int)@$collection['ledger']['module_id'];
		        $receipt_id = (int)$collection['ledger']['receipt_id'];
				
				
				
		$module1 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));			
		foreach ($module1 as $collection) 
		{
		$module_name = @$collection['account_category']['ac_category'];
		}

			
			
			
		$date_fetch=$this->requestAction(array('controller'=>'hms','action'=>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id)));				
		foreach ($date_fetch as $collection) 
		{
		$date1 = @$collection[$module_name]['transaction_date'];
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['posting_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['purchase_date'];	
		}
		if(empty($date1))
		{
		$date1 = @$collection[$module_name]['date'];	
		}
		}			
		if($date1 < $from)
		{
		if($ammount_type_id1 == 1)
		{
		$total_opening_balance = $total_opening_balance - $amount1;
		}
		else if($ammount_type_id1 == 2)
		{
		$total_opening_balance = $total_opening_balance + $amount1;	
		}
		}
		
		if($date1 >= $from && $date1 <= $to)
		{
		if($ammount_type_id1 == 1)
		{
		$total_debit1 = $total_debit1 + $amount1;	
		}
		else if($ammount_type_id1 == 2)
		{
		$total_credit1 = $total_credit1 + $amount1;	
		}
		}	
		}
		}
		
		if($total_debit1 != 0 || $total_credit1 != 0)
		{
		$total_closing_balance = $total_opening_balance + $total_credit1 - $total_debit1; 
		$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance;
        $grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance;       

	    ?>
		<tr>
		<td>          
		<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#aa<?php echo $auto_id3; ?>">
		<?php echo $account_name; ?>
		</a>
		</td>
		<td><?php 
		if($total_opening_balance > 0)
		{
		$total_opening_balance = $total_opening_balance.'Cr';
		}
		else if($total_opening_balance < 0) 
		{ 
		$total_opening_balance = abs($total_opening_balance);
		$total_opening_balance = $total_opening_balance.'Dr';
		}
		
		echo $total_opening_balance; ?></td>
		<td><?php echo $total_debit1; ?></td>
		<td><?php echo $total_credit1; ?></td>
		<td><?php 
		if($total_closing_balance > 0)
		{
		$total_closing_balance = $total_closing_balance.'Cr';
		}
		else if($total_closing_balance < 0)
		{
		$total_closing_balance = abs($total_closing_balance);
		$total_closing_balance = $total_closing_balance.'Dr';
		}
		
		echo $total_closing_balance; ?></td>
		</tr>
		
		<tr>
		<td colspan="5">
		<div id="aa<?php echo $auto_id3; ?>" class="accordion-body collapse">
		<div class="accordion-inner">
		<table class="table table-bordered"> 
		
		<tr>
		<th>Account Name</th>
		<th>Opening Balance</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Closing Balance</th>
		</tr>
		

		<?php   
						   
		$total_sub_credit = 0;
		$total_sub_debit = 0;
		$total_sub_opening_balance = 0;
		$total_sub_closing_balance = 0;
		$result_lsa = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch2'),array('pass'=>array($auto_id3)));			
		foreach ($result_lsa as $collection) 
		{
		$sub_id = (int)$collection['ledger_sub_account']['auto_id'];
		$sub_account_name = $collection['ledger_sub_account']['name'];
		
		$debit_sub = 0;
		$credit_sub = 0;
		$opening_balance_sub = 0;
		$closing_balance_sub = 0;
		$ledger2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($sub_id)));			
		foreach ($ledger2 as $collection) 
		{
		$amount = $collection['ledger']['amount'];
		$ammount_type_id = (int)$collection['ledger']['amount_category_id'];
		$module_id = (int)@$collection['ledger']['module_id'];
		$receipt_id_s = (int)$collection['ledger']['receipt_id'];
		
		$module2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));		   
		foreach ($module2 as $collection) 
		{
		$module_name = @$collection['account_category']['ac_category'];
		}

		
		$date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id_s)));	
		foreach ($date_fetch2 as $collection) 
		{
		$date2 = @$collection[$module_name]['transaction_date'];
		if(empty($date2))
		{
		$date2 = @$collection[$module_name]['posting_date'];	
		}
		if(empty($date2))
		{
		$date2 = @$collection[$module_name]['purchase_date'];	
		}
		if(empty($date2))
		{
		$date2 = @$collection[$module_name]['date'];	
		}
		}		
		if($date2 < $from)
		{
		if($ammount_type_id == 1)
		{
		
		$opening_balance_sub = $opening_balance_sub - $amount;
		}
		else if($ammount_type_id == 2)
		{
		$opening_balance_sub = $opening_balance_sub + $amount;
        }
		
		}
		if($date2 >= $from && $date2 <= $to)
		{
		if($ammount_type_id == 1)
		{
		$debit_sub = $debit_sub + $amount;
		$total_sub_debit = $total_sub_debit + $amount;
		$grand_total_debit = $grand_total_debit + $amount;
		}
		else if($ammount_type_id == 2)
		{
		$credit_sub = $credit_sub + $amount;
		$total_sub_credit = $total_sub_credit + $amount;
		$grand_total_credit =$grand_total_credit + $amount;
        }
		}
		}
		
		if($credit_sub != 0 || $debit_sub != 0)
		{
		$closing_balance_sub = $opening_balance_sub - $debit_sub + $credit_sub;
		$total_sub_closing_balance = $total_sub_closing_balance + $closing_balance_sub;
		$total_sub_opening_balance = $total_sub_opening_balance + $opening_balance_sub;
		
		?>
		<tr>

		<td><?php echo $sub_account_name; ?></td>
		<td><?php 
		if($opening_balance_sub > 0)
		{
		$opening_balance_sub = $opening_balance_sub.'Cr';
		}
		else if($opening_balance_sub < 0)
		{
		$opening_balance_sub = abs($opening_balance_sub);
		$opening_balance_sub = $opening_balance_sub.'Dr';
		}
		echo $opening_balance_sub; ?></td>
		<td><?php echo $debit_sub; ?></td>
		<td><?php echo $credit_sub; ?></td>
		<td><?php 
		if($closing_balance_sub > 0)
		{
		$closing_balance_sub = $closing_balance_sub.'Cr';
		}
		else if($closing_balance_sub < 0)
		{
		$closing_balance_sub = abs($closing_balance_sub);
		$closing_balance_sub = $closing_balance_sub.'Dr';
		}
		echo $closing_balance_sub; ?></td>
		</tr>
		<?php }} ?>
		
		<tr>
		<th colspan="">Total</th>
		<th><?php 
		if($total_sub_opening_balance > 0)
		{
		$total_sub_opening_balance = $total_sub_opening_balance.'Cr';
		}
		else if($total_sub_opening_balance < 0)
		{
		$total_sub_opening_balance = abs($total_sub_opening_balance);
		$total_sub_opening_balance = $total_sub_opening_balance.'Dr';
		}
		echo $total_sub_opening_balance; ?></th>
		<th><?php echo @$total_sub_debit; ?></th>
		<th><?php echo @$total_sub_credit; ?></th>
		<th><?php 
		if($total_sub_closing_balance > 0)
		{
		$total_sub_closing_balance = $total_sub_closing_balance.'Cr';
		}
		else if($total_sub_closing_balance < 0)
		{
		$total_sub_closing_balance = abs($total_sub_closing_balance);
		$total_sub_closing_balance = $total_sub_closing_balance.'Dr';
		}
		echo $total_sub_closing_balance; ?></th>
		</tr>
		</table>
		</div>
		</div>
		
		</td>
		</tr>




<?php }}
else
{

			$total_debit = 0;
			$total_credit = 0;
			$total_opening_balance2 = 0;
			$total_closing_balance2 = 0;
			$ledger_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch2'),array('pass'=>array($auto_id3)));	
			foreach ($ledger_fetch2 as $collection) 
			{
			$amount = $collection['ledger']['amount'];
			$amount_type_id = (int)$collection['ledger']['amount_category_id'];
			$module_id = (int)@$collection['ledger']['module_id'];
			$receipt_id2 = (int)$collection['ledger']['receipt_id'];
			

			$module_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_fetch'),array('pass'=>array($module_id)));		
			foreach ($module_fetch2 as $collection) 
			{
			$module_name = @$collection['account_category']['ac_category'];
			}
 
 
			$module_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' =>'module_main_fetch3'),array('pass'=>array($module_name,$receipt_id2)));
			foreach ($module_fetch3 as $collection) 
			{
			$date3 = @$collection[$module_name]['transaction_date'];
			if(empty($date3))
			{
			$date3 = @$collection[$module_name]['posting_date'];	
			}
			if(empty($date3))
			{
			$date3 = @$collection[$module_name]['purchase_date'];	
			}
			if(empty($date3))
			{
			$date3 = @$collection[$module_name]['date'];	
			}
			}		
			if($date3 < $from)
			{
			if($amount_type_id == 1)
			{
			$total_opening_balance2 = $total_opening_balance2 - $amount;
			}
			else if($amount_type_id == 2)
			{
			$total_opening_balance2 = $total_opening_balance2 + $amount;
			
			}
			}
			
			if(@$date3 >= $from && @$date3 <= $to)
			{
		    if($amount_type_id == 1)
			{
			$total_debit = $total_debit + $amount;
			$grand_total_debit = $grand_total_debit + $amount;
		   	}
			else if($amount_type_id == 2)
			{
			$total_credit = $total_credit + $amount;
			$grand_total_credit = $grand_total_credit + $amount;
			}
			}
			
			}

			if($total_debit !=0 || $total_credit != 0)
			{ 
			
			$total_closing_balance2 = $total_opening_balance2 + $total_credit - $total_debit;
			$grand_total_closing_balance = $grand_total_closing_balance + $total_closing_balance2;
			$grand_total_opening_balance = $grand_total_opening_balance + $total_opening_balance2;
			
			?>

			<tr>

			<td><?php echo $account_name; ?></td>
			<td><?php 
			if($total_opening_balance2 > 0)
			{
			$total_opening_balance2 = $total_opening_balance2.'Cr';
			}
			else if($total_opening_balance2 < 0)
			{
			$total_opening_balance2 = abs($total_opening_balance2);
			$total_opening_balance2 = $total_opening_balance2.'Dr';
			}
			echo $total_opening_balance2; ?></th>
			<td><?php echo $total_debit; ?></td>
			<td><?php echo $total_credit; ?></td>
			<td><?php 
			if($total_closing_balance2 > 0)
			{
			$total_closing_balance2 = $total_closing_balance2.'Cr';
			}
			else if($total_closing_balance2 < 0)
			{
			$total_closing_balance2 = abs($total_closing_balance2);
			$total_closing_balance2 = $total_closing_balance2.'Dr';
			}
			echo $total_closing_balance2; ?></td>
			</tr>  
			
<?php			
}}}}}
 ?>
	<tr>
	<th colspan="">Grand Total</th>
    <th><?php 
	if($grand_total_opening_balance > 0)
	{
	$grand_total_opening_balance = $grand_total_opening_balance.'Cr';
	}
	else if($grand_total_opening_balance < 0)
	{
	$grand_total_opening_balance = abs($grand_total_opening_balance);
	$grand_total_opening_balance = $grand_total_opening_balance.'Dr';
	}
	echo $grand_total_opening_balance; ?></th>   
	<th><?php echo $grand_total_debit; ?></th>
	<th><?php echo $grand_total_credit; ?></th>
	<th><?php 
	if($grand_total_closing_balance > 0)
	{
	$grand_total_closing_balance = $grand_total_closing_balance.'Cr';
	}
	else if($grand_total_closing_balance < 0)
	{
	$grand_total_closing_balance = abs($grand_total_closing_balance);
	$grand_total_closing_balance = $grand_total_closing_balance.'Dr';
	}
	echo $grand_total_closing_balance; ?></th>
	</tr>

	</table>



	</center>			
									
<?php } ?>	
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			




