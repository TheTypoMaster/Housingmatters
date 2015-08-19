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
				$user_id = (int)$collection['ledger_sub_account']['user_id'];

				$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($user_id)));
				foreach ($result_user as $collection) 
				{
				$user_name = $collection['user']['user_name'];  
				$wing_id = $collection['user']['wing'];
				$flat_id = $collection['user']['flat'];
				}


$wing_flat=$this->requestAction(array('controller' => 'Bookkeepings', 'action' => 'wing_flat'), array('pass' => array($wing_id,$flat_id)));




				?>
                <option value="<?php echo $auto_id; ?>"><?php echo $user_name; ?> &nbsp;&nbsp; <?php echo $wing_flat; ?></option> 
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

