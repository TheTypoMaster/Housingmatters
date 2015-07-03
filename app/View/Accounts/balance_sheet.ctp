
<div style="background-color:#EFEFEF; border-top:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; padding:10px; box-shadow:5px; font-size:16px; color:#006;">
Balance sheet view
</div>
<!--<div style="float:right;"><span><a href="tenant_excel" class="blue mini btn" download="download"><i class=" icon-download-alt"></i> Download in Excel</a></span></div>-->
<div class="portlet-body" style="padding:10px;";>
									<!--BEGIN TABS-->
									<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">

										</ul>
										<div class="tab-content" style="min-height:500px;">
											<div class="tab-pane active" id="tab_1_1">
					
					
				
            
            <div class="portlet-body">
            <table class="table table-striped table-bordered" id="">
            <thead>
            <tr >
            <th>Group Name</th>
			<th>Ledger Name</th>
			<th>Debit</th>
			<th>Credit</th>
			 </tr>
            </thead>
            <tbody>
          
            <?php
			$i=0;
			$grand_total_debit = 0;
			$grand_total_credit = 0;
			foreach($result_accounts_group as $data)
			{
			$i++;
			$group_name=$data['accounts_group']['group_name'];
			$auto_id=$data['accounts_group']['auto_id'];
			$result_ledger_account = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_account_fetch'),array('pass'=>array($auto_id)));
			?>
			<tr style="color: rgb(78, 23, 213);">
			
			<td colspan="4"><?php echo $group_name ; ?></td>
			
			</tr>
			
			
			<?php 
			
			foreach($result_ledger_account as $ddd)
			{
				 $ledger_name=$ddd['ledger_account']['ledger_name'];
				 $ledger_auto_id=$ddd['ledger_account']['auto_id'];
				 
				 
				 $result_ledger_amount = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch_new'),array('pass'=>array($ledger_auto_id)));
				$credit=0;$debit=0;
				foreach($result_ledger_amount as $data)
				{
				
					$amount=$data['ledger']['amount'];
					$account_type=$data['ledger']['account_type'];
					$amount_category=(int)$data['ledger']['amount_category_id'];
					if($account_type==2)
					{
						if($amount_category==1)
						{
							$debit=$debit+$amount;
						}
						else
						{
							$credit=$credit+$amount;
						}
					
					}
			
				}

				
				
				
if($ledger_auto_id==15 || $ledger_auto_id==33 || $ledger_auto_id==34 || $ledger_auto_id==35)
{
$credit=0;$debit=0;
$result5 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_sub_account_fetch2'),array('pass'=>array($ledger_auto_id)));
foreach($result5 as $data)
{
$aa=(int)$data['ledger_sub_account']['auto_id'];




$result_ledger_amount2 = $this->requestAction(array('controller' => 'hms', 'action' => 'ledger_fetch1'),array('pass'=>array($aa)));

foreach($result_ledger_amount2 as $dafa)
{
$amount=$dafa['ledger']['amount'];
$account_type=$dafa['ledger']['account_type'];
$amount_category=(int)$dafa['ledger']['amount_category_id'];

if($amount_category==1)
{
$debit=$debit+$amount;
}
else
{
$credit=$credit+$amount;
}
}
}




}



			
				 
            ?>
             <tr>
            
            <td></td>
			<td><?php echo $ledger_name; ?></td>
			<td><?php echo $debit; ?></td>
			<td><?php echo $credit; ?></td>
            </tr> <?php 
$grand_total_debit=$grand_total_debit+$debit;
$grand_total_credit=$grand_total_credit+$credit;

			} } ?>
			
			<tr>
			<th colspan="2">Total</th>
			<th><?php echo $grand_total_debit; ?></th>
			<th><?php echo $grand_total_credit; ?></th>
			</tr>
            </tbody>
			
            </table>
            </div>
            </div>
			
					
											</div>
											
										</div>
</div>