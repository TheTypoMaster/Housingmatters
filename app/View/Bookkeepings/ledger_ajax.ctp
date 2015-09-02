		<?php
		if($get_id == 15 || $get_id == 33 || $get_id == 35)
		{
		?>
			<select class="medium m-wrap chosen" tabindex="1" name="user_name2" id="sub_id" style="margin-top:7px;">
			<option value="">Sub Sub Ledger A/c</option>
			<?php
		
			foreach ($cursor1 as $collection) 
			{
			$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
			$name = $collection['ledger_sub_account']['name'];
			$user_id = (int)$collection['ledger_sub_account']['user_id'];
            $account_number = ""; 
			if($get_id == 33)
			{
			$account_number = $collection['ledger_sub_account']['bank_account'];	
			}
			
			
			
			
			?>
			<option value="<?php echo $auto_id; ?>"><?php echo $name; ?>&nbsp;&nbsp;<b> <?php echo $account_number; ?></b></option> 
		<?php } ?>
		</select>
		
		<?php 
		}
elseif($get_id == 34)
{
?>
 
                <select class="medium m-wrap chosen" tabindex="1" name="user_name2" id="sub_id" style="margin-top:7px;">
                <option value="">Sub Sub Ledger A/c</option>
                <?php
				
                foreach ($cursor1 as $collection) 
				{
				$auto_id = (int)$collection['ledger_sub_account']['auto_id'];
                $name = $collection['ledger_sub_account']['name'];
				$flat_id = (int)$collection['ledger_sub_account']['flat_id'];
				
				//wing_id via flat_id//
				$result_flat_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_wing_id_via_flat_id'),array('pass'=>array($flat_id)));
				foreach($result_flat_info as $flat_info){
					$wing_id=$flat_info["flat"]["wing_id"];
				}
				
				//user info via flat_id//
				$result_user_info=$this->requestAction(array('controller' => 'Hms', 'action' => 'fetch_user_info_via_flat_id'),array('pass'=>array($wing_id,$flat_id)));
				foreach($result_user_info as $user_info){
					$user_id=(int)$user_info["user"]["user_id"];
					$user_name=$user_info["user"]["user_name"];
				} 
				
				


				$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat_with_brackets'), array('pass' => array($wing_id,$flat_id)));




				?>
                <option value="<?php echo $auto_id; ?>"><?php echo $user_name; ?>&nbsp;&nbsp;<?php echo $wing_flat; ?></option> 
                <?php } ?>
                </select>

<?php 


}
else
{
?>



<?php	
}
?>

