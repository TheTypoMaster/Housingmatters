<?php
$m_from = date("Y-m-d", strtotime($date111));
//$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($date222));
//$m_to = new MongoDate(strtotime($m_to));
?>

<?php
$opening_balance = 0;
$closing_balance = 0;
$nnn = 1;
?>
<?php

if($main_id == 34 || $main_id == 15 || $main_id == 33 || $main_id == 35)
{
?>
<?php
$cursor1 = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
                               foreach ($cursor1 as $collection) 
									{
								    $ledger_type_name = $collection['ledger_account']['ledger_name'];	
									}
$cursor2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($sub_id)));	
                                    foreach ($cursor2 as $collection) 
									{
								    $user_name = $collection['ledger_sub_account']['name'];	
									}
									
					?>              
					
			
									<?php
                                   
                                     $opening_balance = 0;
									 foreach ($cursor3 as $collection) 
									 {
                                     $auto_id = (int)@$collection['ledger']['auto_id'];
									 $account_type = (int)@$collection['ledger']['account_type'];
									 $receipt_id = (int)@$collection['ledger']['receipt_id']; 
                                     $amount_o = @$collection['ledger']['amount'];
					                 $amount_category_id = (int)@$collection['ledger']['amount_category_id'];
									 $module_id = (int)@$collection['ledger']['module_id'];
									 $sub_account_id = (int)@$collection['ledger']['account_id']; 
									 $current_date = @$collection['ledger']['current_date'];
									 $society_id = (int)@$collection['ledger']['society_id'];
                                     $module_name = @$collection['ledger']['module_name'];
									 $table_name = @$collection['ledger']['table_name'];
									 $op_date = @$collection['ledger']['op_date'];
									 if($table_name == "cash_bank")
									 {
									 $module_id = (int)$collection['ledger']['module_id']; 	 
                                     }

if($receipt_id == 'O_B')
{									
$op_date2 = date('Y-m-d',$op_date->sec);									 
}
if($receipt_id != 'O_B')
{                                    

if($table_name == "cash_bank")
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else if($table_name == "regular_bill")
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}
 		
										foreach ($module_date_fetch as $collection) 
										{
										$date1 = @$collection[$table_name]['transaction_date'];
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['posting_date'];	
										}
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['purchase_date'];	
										}
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['date'];	
										}
										$narration = @$collection[$table_name]['narration'];
										$remark = @$collection[$table_name]['remark'];
										}
}

if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";
}
if($receipt_id == 'O_B')
{ 
if($sub_account_id == $sub_id)
{
if(@$op_date2 <= $m_from)
{
if($account_type == 1)
{
if($amount_category_id == 1)
{
$opening_balance = $opening_balance - $amount_o;
$nnn = 5;
}
else if($amount_category_id == 2)
{
$opening_balance = $opening_balance + $amount_o;
$nnn = 5;	
}
}
}
}
}
$op_date2 = "";
}
?>
<?php
$balance = $opening_balance;

?>
								
<?php
$total_debit = 0;
$total_credit = 0;
foreach ($cursor3 as $collection) 
{

$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
$module_name = @$collection['ledger']['module_name'];
$table_name = @$collection['ledger']['table_name'];
if($table_name == "cash_bank")
{
$module_id = (int)@$collection['ledger']['module_id']; 	 
}
if($receipt_id == 'O_B')
continue;

if($table_name == "cash_bank")
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else if($table_name == "regular_bill")
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}
$date = "";
									foreach ($module_date_fetch2 as $collection) 
									{
									$date = @$collection[$table_name]['transaction_date'];
									if(empty($date))
									{
									$date = @$collection[$table_name]['posting_date'];	
									}
									if(empty($date))
									{
									$date = @$collection[$table_name]['purchase_date'];	
									}
									if(empty($date))
									{
									$date = @$collection[$table_name]['date'];	
									}
									$narration = @$collection[$table_name]['narration'];
									$remark = @$collection[$table_name]['remark'];
									}

if($amount_category_id == 1)
{
$amount_category = "Debit";
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";	
}



if($sub_account_id == $sub_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 1)
{
$nnn = 5;
?>

<?php
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}
$closing_balance = $opening_balance - $total_debit + $total_credit;
?>
<?php 
}}}} 
?>
										
										
<?php 
}
else
{
?>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

 
									<?php
                                    
$ledger_account_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
								    foreach ($ledger_account_fetch as $collection) 
									{
									$group_id = (int)$collection['ledger_account']['group_id'];
								    $user_name = $collection['ledger_account']['ledger_name'];	
									}

$accounts_group = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($group_id)));
                                    foreach ($accounts_group as $collection) 
									{
								    $ledger_type_name = $collection['accounts_group']['group_name'];	
									}
									
									
									?>

                                 

<?php 
 									
									$opening_balance = 0;
									foreach ($cursor3 as $collection) 
									{
								    $auto_id = (int)@$collection['ledger']['auto_id'];
								 	$account_type = (int)@$collection['ledger']['account_type'];
									$receipt_id = (int)@$collection['ledger']['receipt_id']; 
									$amount_o = @$collection['ledger']['amount'];
									$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
									$module_id = (int)@$collection['ledger']['module_id'];
									$sub_account_id = (int)@$collection['ledger']['account_id']; 
									$current_date = @$collection['ledger']['current_date'];
									$society_id = (int)@$collection['ledger']['society_id'];
                                    $table_name = @$collection['ledger']['table_name'];
									$module_name = @$collection['ledger']['module_name'];
									$op_date = @$collection['ledger']['op_date'];
									if($table_name == "cash_bank")
									{
									$module_id = $collection['ledger']['module_id'];	
									}


if($receipt_id == 'O_B')
{ 
$op_date2 = date('Y-m-d',$op_date->sec);
}
if($receipt_id != 'O_B')
{                                  
if($table_name == "cash_bank")
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));   	
}
else if($table_name == "regular_bill")
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));   
}

foreach ($module_date_fetch3 as $collection) 
{
$date1 = @$collection[$table_name]['transaction_date'];
if(empty($date1))
{
$date1 = @$collection[$table_name]['posting_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['purchase_date'];	
}
if(empty($date1))
{
$date1 = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
$remark = @$collection[$table_name]['remark'];
}
}





if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";		
}
if($receipt_id == 'O_B')
{ 									
if($sub_account_id == $main_id)
{
if(@$op_date2 <= $m_from)
{
if($account_type == 2)
{
if($amount_category_id == 1)
{
$opening_balance = $opening_balance - $amount_o;
$nnn = 5;
}
else if($amount_category_id == 2)
{
$opening_balance = $opening_balance + $amount_o;
$nnn = 5;	
}
}
}
}
}
$op_date2 = "";
} 
?>
<?php
$balance = $opening_balance;

?>										

<?php
$total_debit = 0;
$total_credit = 0;
foreach ($cursor3 as $collection) 
{
$auto_id = (int)@$collection['ledger']['auto_id'];
$account_type = (int)@$collection['ledger']['account_type'];
$receipt_id = (int)@$collection['ledger']['receipt_id']; 
$amount = @$collection['ledger']['amount'];
$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
$module_id = (int)@$collection['ledger']['module_id'];
$sub_account_id = (int)@$collection['ledger']['account_id']; 
$current_date = @$collection['ledger']['current_date'];
$society_id = (int)@$collection['ledger']['society_id'];
$table_name = @$collection['ledger']['table_name'];
$module_name = @$collection['ledger']['module_name'];
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id'];	 
}
if($receipt_id == 'O_B')
continue;

if($table_name == "cash_bank")
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id))); 	
}
else if($table_name == "regular_bill")
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));   
}
	$date = "";								
	foreach ($module_date_fetch4 as $collection) 
	{
	$date = @$collection[$table_name]['transaction_date'];
	if(empty($date))
	{
	$date = @$collection[$table_name]['posting_date'];	
	}
	if(empty($date))
	{
	$date = @$collection[$table_name]['purchase_date'];	
	}
	if(empty($date))
	{
	$date = @$collection[$table_name]['date'];	
	}
	$narration = @$collection[$table_name]['narration'];
	$remark = @$collection[$table_name]['remark'];
	}
	
if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";	
}

									
if($sub_account_id == $main_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 2)
{
$nnn = 5;
												

?>



<?php
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}
$closing_balance = $opening_balance - $total_debit + $total_credit;
?>

<?php }}}} ?>
                            
								
	<?php } ?>								 

<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>

<?php if($nnn == 5) { ?>

<?php
$m_from = date("Y-m-d", strtotime($date111));
//$m_from = new MongoDate(strtotime($m_from));

$m_to = date("Y-m-d", strtotime($date222));
//$m_to = new MongoDate(strtotime($m_to));

$opening_balance = 0;
$closing_balance = 0;
?>
<div style="width:100%;" class="hide_at_print">
<span style="margin-left:80%;">
<a href="ledger_excel?f=<?php echo $date111; ?>&t=<?php echo $date222; ?>&m=<?php echo $main_id; ?>&s=<?php echo $sub_id; ?>" class="btn blue">Export in Excel</a>
<button type="button" class=" printt btn green" onclick="window.print()"><i class="icon-print"></i> Print</button></span>
</div>
<br />
<?php

if($main_id == 34 || $main_id == 15 || $main_id == 33 || $main_id == 35)
{
?>
<table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
<?php
$cursor1 = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
                                    foreach ($cursor1 as $collection) 
									{
								    $ledger_type_name = $collection['ledger_account']['ledger_name'];	
									}
$cursor2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch'),array('pass'=>array($sub_id)));	
                                    foreach ($cursor2 as $collection) 
									{
								    $user_name = $collection['ledger_sub_account']['name'];	
									}
					                ?>              
					
					
                                    <tr>
                                    <th colspan = "6" style="text-align:center;">
                                    <?php echo $society_name; ?>
                                    </th>
                                    </tr>
                                    <tr>
                                    <th colspan = "6" style="text-align:center;">
                                    Transaction for The Period <?php echo $date111; ?> to <?php echo $date222; ?>
                                    </th>
                                    </tr>
					
<tr>
<th><?php echo @$user_name; ?>  A/c</th>
<th>Grouping :<?php echo @$ledger_type_name; ?></th>
<th colspan="4"></th>
</tr>
							
					<?php
                    $close = 0;
                    $opening_balance = 0;
                    foreach ($cursor3 as $collection) 
                    {
                    $auto_id = (int)@$collection['ledger']['auto_id'];
                    $account_type = (int)@$collection['ledger']['account_type'];
                    $receipt_id = @$collection['ledger']['receipt_id']; 
                    $amount_o = @$collection['ledger']['amount'];
                    $amount_category_id = (int)@$collection['ledger']['amount_category_id'];
                    $module_id = (int)@$collection['ledger']['module_id'];
                    $sub_account_id = (int)@$collection['ledger']['account_id']; 
                    $current_date = @$collection['ledger']['current_date'];
                    $society_id = (int)@$collection['ledger']['society_id'];
                    $op_date = @$collection['ledger']['op_date'];
                    $table_name = @$collection['ledger']['table_name'];
                    $module_name = @$collection['ledger']['module_name'];
                    if($table_name == "cash_bank")
                    {
                    $module_id = (int)$collection['ledger']['module_id'];	 
                    }
                    $op_im_deb = 0;
                    $op_im_cre = 0;
									
if($receipt_id == 'O_B')
{
$op_date2 = date('Y-m-d',$op_date->sec);
if($sub_account_id == $sub_id)
{
if($account_type == 1)
{
if($amount_category_id == 1)
{
$op_im_deb = $amount_o; 
}
else
{
$op_im_cre = $amount_o; 	 
}
}
}
}

if($receipt_id != 'O_B')
{ 
if($table_name == "cash_bank")
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else if($table_name == "regular_bill")
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}
 		
										foreach ($module_date_fetch as $collection) 
										{
										$date1 = @$collection[$table_name]['transaction_date'];
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['posting_date'];	
										}
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['purchase_date'];	
										}
										if(empty($date1))
										{
										$date1 = @$collection[$table_name]['date'];	
										}
										$narration = @$collection[$table_name]['narration'];
										$remark = @$collection[$table_name]['remark'];
										}
}
										
if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";		
}

								/*if($sub_account_id == $sub_id)
								{
								if(@$op_date2 < $m_from)
								{
								if($account_type == 1)
								{

								if($amount_category_id == 1)
								{
								$opening_balance = $opening_balance - $amount_o;
												
								}
								else if($amount_category_id == 2)
								{
								$opening_balance = $opening_balance + $amount_o;	
								}
								}
								}
								}*/
								if($op_date2 <= $m_from)
								{
								$opening_balance = $opening_balance + $op_im_cre - $op_im_deb;
								}
								else
								{
								$close	= $close + $op_im_cre - $op_im_deb;
								}
								}
								  ?>
                                    <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Opening Balance:</th>
                                    <th><?php
									$opening_balance = $opening_balance;
                                   	$op_bal2 = $opening_balance;
									if($opening_balance > 0)
									{
									$opening_balance = number_format($opening_balance);	
									$opening_balance = $opening_balance.'&nbsp;&nbsp;Cr';
									}
									else if($opening_balance < 0)
									{
									$opening_balance = abs($opening_balance);
									$opening_balance = number_format($opening_balance);	
									$opening_balance = $opening_balance.'&nbsp;&nbsp;Dr';
									}
									echo $opening_balance; ?></th>
                                    </tr>
                                    <?php
                                    $balance = $opening_balance;
									?>
									
									<tr>
									<th>Transaction Date</th>
									<th>Narration</th>
									<th>Source</th>
									<th>Reference #</th>
									<th>Debit</th>
									<th>Credit</th>
									</tr>
                                    <?php
									$total_debit = 0;
									$total_credit = 0;
									foreach ($cursor3 as $collection) 
									{
									 $date = "";
									 $op_date2 = "";
									 $op_date = "";
									 $auto_id = (int)@$collection['ledger']['auto_id'];
									 $account_type = (int)@$collection['ledger']['account_type'];
									 $receipt_id = @$collection['ledger']['receipt_id']; 
                                     $amount = @$collection['ledger']['amount'];
					                 $amount_category_id = (int)@$collection['ledger']['amount_category_id'];
									 $module_id = (int)@$collection['ledger']['module_id'];
									 $sub_account_id = (int)@$collection['ledger']['account_id']; 
									 $current_date = @$collection['ledger']['current_date'];
									 $society_id = (int)@$collection['ledger']['society_id'];
                                     $table_name = @$collection['ledger']['table_name'];
									 $module_name = @$collection['ledger']['module_name'];
									 $pen_type = @$collection['ledger']['penalty'];
									 $op_date = @$collection['ledger']['op_date'];
									if($receipt_id == "O_B")
									{
									$op_date2 = date('Y-m-d',$op_date->sec);	
									}
									
									
if($receipt_id != "O_B")
{								
if(!empty($pen_type))
{
if($pen_type == "NO")
{
$bill_type = "Maint."; 	 
}
else
{
$bill_type = "Int.";  
}
}
								
/////////////////////////////////////////////////									 
if($module_name == "Regular Bill")								 
{									 
$one = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch7'),array('pass'=>array($receipt_id)));									
foreach($one as $dddd)									 
{
$bill_user_id = (int)$dddd['regular_bill']['bill_for_user'];	
}
$two = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($bill_user_id)));									foreach($two as $ddd)
{
$fl_id1 = (int)$ddd['user']['flat'];	
$wn_id1 = (int)$ddd['user']['wing'];	
}

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wn_id1,$fl_id1)));									
								 
}
else
{
$wing_flat = "";	
}
/////////////////////////////////////////////////////////									 
									 
									 
if($table_name == "cash_bank")
{
$module_id = (int)$collection['ledger']['module_id']; 
}

									
if($table_name == "cash_bank")	
{								
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));									
}
else if($table_name == "regular_bill")
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch2 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));
}
$date = "";  
foreach ($module_date_fetch2 as $collection) 
{
$date = @$collection[$table_name]['transaction_date'];
if(empty($date))
{
$date = @$collection[$table_name]['posting_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['purchase_date'];	
}
if(empty($date))
{
$date = @$collection[$table_name]['date'];	
}
$narration = @$collection[$table_name]['narration'];
if(empty($narration))
{
$narration = @$collection[$table_name]['remark'];
}
if(empty($narration))
{
$narration = @$collection[$table_name]['description'];	
}
$remark = @$collection[$table_name]['remark'];
}
}


if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 2)
{
$amount_category = "Credit";		
}
									
if($sub_account_id == $sub_id)
{
if(@$date >= $m_from && @$date <= $m_to)
{
if($account_type == 1)
{
	
$date = date('d-m-Y',strtotime($date));	
?>
<tr>
<td><?php echo $date; ?></td>
<td><?php echo $narration; ?></td>
<td><?php echo $module_name; ?><?php  if(!empty($pen_type)) { ?> &nbsp; (<?php echo $bill_type; ?>)<?php } ?>
&nbsp;&nbsp; <?php if(!empty($wing_flat)) { echo $wing_flat; } ?>
</td>
<td><?php echo $receipt_id; ?></td>
<td><?php if($amount_category_id == 1) { $balance = $balance - $amount;   
$amount2 = number_format($amount);
echo $amount2; } else { echo "-"; } ?></td>
<td><?php if($amount_category_id == 2) { $balance = $balance + $amount;  
$amount3 = number_format($amount);
echo $amount3; } else { echo "-"; } ?></td>
</tr>
                                       

<?php
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}
//$closing_balance = $op_bal2 - $total_debit + $total_credit + ($close);
?>

<?php $pen_type=""; 
}}
else 
{
if(@$op_date2 >= $m_from && @$op_date2 <= $m_to)
{
if($account_type == 1)
{
$op_date3 = date('d-m-Y',strtotime($op_date2));
?>
<tr>
<td><?php echo $op_date3; ?></td>
<td>Opening Balance</td>
<td>Opening Balance</td>
<td>Opening Balance</td>
<td><?php if($amount_category_id == 1) { $balance = $balance - $amount;   
$amount2 = number_format($amount);
echo $amount2; } else { echo "-"; } ?></td>
<td><?php if($amount_category_id == 2) { $balance = $balance + $amount;  
$amount3 = number_format($amount);
echo $amount3; } else { echo "-"; } ?></td>
</tr>
<?php
if($amount_category_id == 1)
{
$total_debit = $total_debit + $amount;
}
else if($amount_category_id == 2)
{
$total_credit = $total_credit + $amount;
}

?>
		
<?php

}
}
}
}
}
$closing_balance = $op_bal2 - $total_debit + $total_credit;

 ?>
<tr>
<th colspan="4" style="text-align:right;"><b> Total </b></th>

<th><?php 
$total_debit = number_format($total_debit);
echo $total_debit; ?>  <?php //echo "    dr"; ?></th>
<th><?php 
$total_credit = number_format($total_credit);
echo $total_credit; ?> <?php //echo "    cr"; ?></th>

</tr>
<tr>
<th style="text-align:center;">Opening Balance</th>
<th colspan="" style="text-align:center;">
Total Debits
</th>
<th style="text-align:center;">Total credits</th>
<th colspan="3" style="text-align:center;">
Closing balance
</th>
</tr>

<tr>
<th style="text-align:center;"><?php 
$opening_balance2 ="0";
if($op_bal2 > 0) 
{ 
$opening_balance2 = number_format($op_bal2);
$opening_balance2 = $opening_balance2.'Cr';
} 
else if($op_bal2 < 0)
{
$opening_balance2 = abs($op_bal2);
$opening_balance2 = number_format($opening_balance2);
$opening_balance2 = $opening_balance2.'Dr';
}
echo @$opening_balance2; ?></th>
<th colspan="" style="text-align:center;"><?php 
//$total_debit = number_format($total_debit);
echo $total_debit ?></th>

<th style="text-align:center;"><?php 
//$total_credit = number_format($total_credit);
echo $total_credit; ?></th>
<th colspan="3" style="text-align:center;"><?php 

if($closing_balance > 0) 
{ 
$closing_balance = number_format($closing_balance);
$closing_balance = $closing_balance.'&nbsp;&nbsp;Cr';  
}
else if($closing_balance < 0)
{ 										
$closing_balance = abs($closing_balance);
$closing_balance = number_format($closing_balance);
$closing_balance = $closing_balance.'&nbsp;&nbsp;Dr';
}
echo $closing_balance; ?></th>
</tr>


</table>
</center>
<?php 
}
else
{
?>
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

 <table class="table table-bordered" style="width:100%; background-color:#FDFDEE;">
                                    
									<?php
                                    
$ledger_account_fetch = $this->requestAction(array('controller' => 'hms', 'action' => 'fetch_amount'),array('pass'=>array($main_id)));
								    foreach ($ledger_account_fetch as $collection) 
									{
									$group_id = (int)$collection['ledger_account']['group_id'];
								    $user_name = $collection['ledger_account']['ledger_name'];	
									}

$accounts_group = $this->requestAction(array('controller' => 'hms', 'action' => 'accounts_group'),array('pass'=>array($group_id)));
                                    foreach ($accounts_group as $collection) 
									{
								    $ledger_type_name = $collection['accounts_group']['group_name'];	
									}
									
									
									?>

                                 
								<tr>
								<th colspan = "6" style="text-align:center;">
							    <?php echo $society_name; ?>
								</th>
								</tr>



								 <tr>
								  <th colspan = "6" style="text-align:center;">
								  Transaction for The Period <?php echo $date111; ?> to <?php echo $date222; ?>
								  </th>
					              </tr>








                                    <tr>
                                     <th><?php echo @$user_name; ?>  A/c</th>
                                     <th>Grouping : <?php echo @$ledger_type_name; ?></th>
                                    <th colspan="4"></th>
                                    </tr>
                                    
                                                                      


<?php 
 									//$op_im_deb = 0;
									//$op_im_cre = 0;
									$close = 0;
									$opening_balance = 0;
									foreach ($cursor3 as $collection) 
									{
								    $auto_id = (int)@$collection['ledger']['auto_id'];
								 	$account_type = @$collection['ledger']['account_type'];
									$receipt_id = @$collection['ledger']['receipt_id']; 
									$amount_o = @$collection['ledger']['amount'];
									$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
									$module_id = (int)@$collection['ledger']['module_id'];
									$sub_account_id = (int)@$collection['ledger']['account_id']; 
									$current_date = @$collection['ledger']['current_date'];
									$society_id = (int)@$collection['ledger']['society_id'];
                                    $op_date = @$collection['ledger']['op_date'];
                                    $table_name = @$collection['ledger']['table_name'];
									$module_name = @$collection['ledger']['module_name'];
									if($table_name == "cash_bank")
									{
									$module_id = $collection['ledger']['module_id'];
									}
									
									
									
									$op_im_deb = 0;
                                    $op_im_cre = 0;
									if($receipt_id == 'O_B')
									{
									$op_date2 = date('Y-m-d',$op_date->sec);
									if($sub_account_id == $main_id)
	                                {
                                    if($account_type == 2)
									{
									if($amount_category_id == 1)
									{
									$op_im_deb = $amount_o; 
									}
									else
									{
									$op_im_cre =  $amount_o; 	 
									}
									}
									}
									}
									
									





if($receipt_id != "O_B")									
{									
if($table_name == "cash_bank")
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else if($table_name == "regular_bill")
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch3 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));   
}
									
									foreach ($module_date_fetch3 as $collection) 
									{
									$date1 = @$collection[$table_name]['transaction_date'];
									if(empty($date1))
									{
									$date1 = @$collection[$table_name]['posting_date'];	
									}
									if(empty($date1))
									{
									$date1 = @$collection[$table_name]['purchase_date'];	
									}
									if(empty($date1))
									{
									$date1 = @$collection[$table_name]['date'];	
									}
									$narration = @$collection[$table_name]['narration'];
									$remark = @$collection[$table_name]['remark'];
									}
}
									

if($amount_category_id == 1)
{
$amount_category = "Debit";	
}
else if($amount_category_id == 1)
{
$amount_category = "Credit";		
}
									
									/*
									if($sub_account_id == $main_id)
	                                {
									if(@$op_date2 < $m_from)
									{
								    if($account_type == 2)
								    {
									if($amount_category_id == 1)
									{
									$opening_balance = $opening_balance - $amount_o;
									}
									else if($amount_category_id == 2)
									{
									$opening_balance = $opening_balance + $amount_o;	
									}
									}
									}
									}
									*/
									
									
if($op_date2 <= $m_from)
{
$opening_balance = $opening_balance + $op_im_cre - $op_im_deb;
}
else
{
$close = $close + $op_im_cre - $op_im_deb;
}
} 


 ?>
                                    <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Opening Balance:</th>
                                    <th><?php 
									$opening_balance = $opening_balance;
									$op_bal2 = $opening_balance;
									
									if($opening_balance > 0)
									{
									$opening_balance = number_format($opening_balance);
									$opening_balance = $opening_balance.'&nbsp;&nbsp;Cr';
									}
									else if($opening_balance < 0)
									{
									$opening_balance = abs($opening_balance);
									$opening_balance = number_format($opening_balance);
									$opening_balance = $opening_balance.'&nbsp;&nbsp;Dr';
									}
									//$opening_balance = number_format($opening_balance);
									echo $opening_balance; ?></th>
                                    </tr>
									
                                    
                                    
                                    <?php
                                    
									$balance = $opening_balance;
									?>
									<tr>
											<th>Transaction Date</th>
                                            <th>Narration</th>
											<th>Source</th>
											<th>Reference #</th>
											<th>Debit</th>
                                            <th>Credit</th>
											
									</tr>									
														
										
										
								<?php
								
									$total_debit = 0;
									$total_credit = 0;
									foreach ($cursor3 as $collection) 
									{
										 $date = "";
										$op_date2 = "";	
										$op_date = "";
										$auto_id = (int)@$collection['ledger']['auto_id'];
										$account_type = (int)@$collection['ledger']['account_type'];
										$receipt_id = @$collection['ledger']['receipt_id']; 
										$amount = @$collection['ledger']['amount'];
										$amount_category_id = (int)@$collection['ledger']['amount_category_id'];
										$module_id = (int)@$collection['ledger']['module_id'];
										$sub_account_id = (int)@$collection['ledger']['account_id']; 
										$current_date = @$collection['ledger']['current_date'];
										$society_id = (int)@$collection['ledger']['society_id'];
										$table_name = @$collection['ledger']['table_name'];                                     
										$module_name = @$collection['ledger']['module_name'];
										$op_date = @$collection['ledger']['op_date'];
										if($table_name == "cash_bank")
										{
										$module_id = $collection['ledger']['module_id'];	 
										}
										
										if($receipt_id == 'O_B')
										{
										$op_date2 = date('Y-m-d',$op_date->sec);
										}
									

if($receipt_id != "O_B")
{
/////////////////////////////////////////////////									 
if($module_name == "Regular Bill")								 
{									 
$one = $this->requestAction(array('controller' => 'hms', 'action' => 'regular_bill_fetch7'),array('pass'=>array($receipt_id)));									
foreach($one as $dddd)									 
{
$bill_user_id = (int)$dddd['regular_bill']['bill_for_user'];	
}
$two = $this->requestAction(array('controller' => 'hms', 'action' => 'user_fetch'),array('pass'=>array($bill_user_id)));									foreach($two as $ddd)
{
$fl_id1 = (int)$ddd['user']['flat'];	
$wn_id1 = (int)$ddd['user']['wing'];	
}

$wing_flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wn_id1,$fl_id1)));									
					 
}
else
{
$wing_flat = "";	
}
/////////////////////////////////////////////////////////	


								
if($table_name == "cash_bank")
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch5'),array('pass'=>array($table_name,$receipt_id,$module_id)));	
}
else if($table_name == "regular_bill")
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch10'),array('pass'=>array($table_name,$receipt_id)));	
}
else
{
$module_date_fetch4 = $this->requestAction(array('controller' => 'hms', 'action' => 'module_main_fetch'),array('pass'=>array($table_name,$receipt_id)));   
}
$date = "";

									
									foreach ($module_date_fetch4 as $collection) 
									{
									$date = @$collection[$table_name]['transaction_date'];
									if(empty($date))
									{
									$date = @$collection[$table_name]['posting_date'];	
									}
									if(empty($date))
									{
									$date = @$collection[$table_name]['purchase_date'];	
									}
									if(empty($date))
									{
									$date = @$collection[$table_name]['date'];	
									}
									$narration = @$collection[$table_name]['narration'];
									if(empty($narration))
									{
									$narration = @$collection[$table_name]['remark'];
									}
									if(empty($narration))
									{
									$narration = @$collection[$table_name]['description'];	
									}
									$remark = @$collection[$table_name]['remark'];
									}
									
									
									
									
									
									
}
									
									
									
									
									
	if($amount_category_id == 1)
	{
	$amount_category = "Debit";	
	}
	else if($amount_category_id == 2)
	{
	$amount_category = "Credit";	
	}
								
									
									if($sub_account_id == $main_id)
									{
										if(@$date >= $m_from && @$date <= $m_to)
								         {
											if($account_type == 2)
											{
										
											 //$date = date('d-m-Y',$date->sec);	
                                         
 										    
									 	?>
										
										<tr>
											<td><?php echo $date; ?></td>
                                            <td><?php echo $narration; ?></td>
											<td><?php echo $module_name; ?>
                                            &nbsp;&nbsp; <?php if(!empty($wing_flat)) { echo $wing_flat; } ?>
                                            </td>
											<td><?php echo $receipt_id; ?></td>
											
											<td><?php if($amount_category_id == 1) { $balance = $balance - $amount;   
											$amount2 = number_format($amount);
											echo $amount2; } else { echo "-"; } ?></td>
										    <td><?php if($amount_category_id == 2) { $balance = $balance + $amount;   
											 $amount3 = number_format($amount);
											 echo $amount3; } else { echo "-"; } ?></td>
										    
                                        </tr>
										
										
										<?php
									    if($amount_category_id == 1)
										{
										$total_debit = $total_debit + $amount;
										}
										else if($amount_category_id == 2)
										{
										$total_credit = $total_credit + $amount;
										}
                                        //$closing_balance = $op_bal2 - $total_debit + $total_credit + ($close);
										?>
										
										 <?php
										
										  }}
										  else
										  {
										if(@$op_date2 >= $m_from && @$op_date2 <= $m_to)
										{
										if($account_type == 2)
										{
										$op_date3 = date('d-m-Y',strtotime($op_date2));
										?>	
											
										<tr>
										<td><?php echo $op_date3; ?></td>
										<td>Opening Balance</td>
										<td>Opening Balance
										</td>
										<td>Opening Balance</td>
										
										<td><?php if($amount_category_id == 1) { $balance = $balance - $amount;   
										$amount2 = number_format($amount);
										echo $amount2; } else { echo "-"; } ?></td>
										<td><?php if($amount_category_id == 2) { $balance = $balance + $amount;   
										$amount3 = number_format($amount);
										echo $amount3; } else { echo "-"; } ?></td>
										</tr>
																			
										<?php
									    if($amount_category_id == 1)
										{
										$total_debit = $total_debit + $amount;
										}
										else if($amount_category_id == 2)
										{
										$total_credit = $total_credit + $amount;
										}
                                       
										?>
										
										
										
										
										
										
										
										
										<?php	
											
											
											
											
											}}
											  
										  }
										  
										  
										  
										  
										  
										  
										  
										  
										  
										  }
										 
										  
										  
										  } 
										  
										   $closing_balance = $op_bal2 - $total_debit + $total_credit;
										  ?>
							   
							   <tr>
                               <th colspan="4" style="text-align:right;"><b> Total </b></th>
                               <th><?php 
							   $total_debit = number_format($total_debit);
							   echo $total_debit; ?>  <?php //echo "    dr"; ?></th>
                               <th><?php 
							    $total_credit = number_format($total_credit);
							   echo $total_credit; ?> <?php //echo "    cr"; ?></th>
                               
                                </tr>
								
								 <tr>
                                <th style="text-align:center;">Opening Balance:</th>
                                <th style="text-align:center;">Total Debits
								
								
							</th>
								<th style="text-align:center;">Total Credits</th>
								<th colspan="3" style="text-align:center;">
								Closing balance
								
								
								</th>
                                </tr> 
								   
								<tr>
                                <th style="text-align:center;">
								<?php 
								 $opening_balance2 = "0";
								if($op_bal2 > 0)
								{
								$opening_balance2 = number_format($opening_balance2);
								$opening_balance2 = $op_bal2.'&nbsp;&nbsp;Cr';
								}
								else if($op_bal2 < 0)
								{
								$opening_balance2 = abs($op_bal2);
								$opening_balance2 = number_format($opening_balance2);
								$opening_balance2 = $opening_balance2.'&nbsp;&nbsp;Dr';
								}
								echo $opening_balance2; ?>
								</th>
                                <th colspan="" style="text-align:center;"><?php 
								//$total_debit = number_format($total_debit);
								echo $total_debit ?></th>
                                <th style="text-align:center;"><?php 
								//$total_credit = number_format($total_credit);
								echo $total_credit; ?></th>
                                <th colspan="3" style="text-align:center;"><?php 
								if($closing_balance > 0)
								{
								$closing_balance = number_format($closing_balance);
								$closing_balance = $closing_balance.'&nbsp;&nbsp;Cr';
								}
								else if($closing_balance < 0)
								{
								$closing_balance = abs($closing_balance);
								$closing_balance = number_format($closing_balance);
								$closing_balance = $closing_balance.'&nbsp;&nbsp;Dr';
								}
								
								echo $closing_balance;
								?></th>
                                </tr>
                                </table>	
	<?php } ?>
	
		

<?php } 
else if($nnn == 1)
{
?>
<br /><br />											
<center>
<h3 style="color:red;">
<b>No Records Found in Selected Period</b>
</h3>
</center>
<br /><br />
<?php 
}
?>	