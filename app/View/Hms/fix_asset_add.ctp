<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>	
			<?php
			if($zz == 0)
			{
			?>
			
			<div class="alert">
			<button class="close" data-dismiss="alert"></button>
			<center>
			No Previous Receipt
			</center>
			</div> 
			
			<?php
			}
			else
			{
			?>

			<div class="alert">
			<button class="close" data-dismiss="alert"></button>
			<center>
			The Last Receipt Number is : <?php echo $zz; ?>
			</center>
			</div> 

			<?php } ?>
			
<?php //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
 

 <center>
 <a href="fix_asset_add" class="btn red">Add</a>
 <a href="fix_asset_view" class="btn blue">View</a>
 </center>
 <br>     	
			
<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>			
			
				<div class="portlet box grey" style="width:60%; margin-left:20%; margin-right:20%;">
				<div class="portlet-title">
				<h4><i class="icon-reorder"></i>Fixed Assets</h4>
				</div>
				<div class="portlet-body form"> 	
				
				<form method="post">
				<center>
			    <table style="width:80%;">
			
			
			
				<tr>
				<td><br><label class="" style="font-size:14px;">Asset Category</label></td>
				<td><br>
				<select name="asset_category" class="m-wrap medium chosen">
				<option value="">Select category</option>
				<?php
				foreach ($cursor1 as $collection) 
				{
				$auto_id = (int)$collection['ledger_account']['auto_id'];
				$category = $collection['ledger_account']['ledger_name'];	
				if($auto_id != 18)
				{	
				?>
				<option value="<?php echo $auto_id; ?>"><?php echo $category; ?></option>
				<?php }} ?>
				</select>
				</td>
				</tr> 
			
			
				<tr>
				<td><br>Asset Name:</td>
				<td><br><input type="text" class="m-wrap medium" name="name"></td>
				</tr>
				
				
				<tr>
				<td valign="top"><br><label class="" style="font-size:14px;">Asset Description</label></td>
				<td><br>
				<textarea  rows="4" name="description" class="m-wrap medium" style="resize:none;"></textarea>
				</td>
				</tr> 
				
				
				<tr>
				<td><br><label class="" style="font-size:14px;">Date of Purchase</label></td>
				<td><br><input type="text" class="date-picker m-wrap medium" data-date-format="dd-mm-yyyy" name="purchase_date"> </td>
				</tr> 

				
				<tr>
				<td><br><label class="" style="font-size:14px;">Cost of Purchase</label></td>
				<td><br>
				<input type="text" class="m-wrap medium"  name="cost">
				</td>
				</tr> 


				<tr>
				<td><br><label class="" style="font-size:14px;">Name of Supplier/Vendor</label></td>
				<td><br>
				<select name="vendor" class="m-wrap medium chosen">
				<option value="">Select</option>
				<?php
				foreach ($cursor2 as $db) 
				{
				$g_id=(int)$db['ledger_sub_account']["auto_id"];
				$vendor_name=$db['ledger_sub_account']["name"];
				?>
				<option value="<?php echo $g_id; ?>"><?php echo $vendor_name; ?></option>
				<?php } ?>
				</select>
				</td>
				</tr> 

				
				<tr>
				<td><br><label class="" style="font-size:14px;">Warranty Period</label></td>
				<td><br>
				<input type="text" class="span4 m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="From*" name="from">
				<span> - </span>
				<input type="text" class="span4  m-ctrl-medium date-picker" data-date-format="dd-mm-yyyy" placeholder="to*" name="to">
				</td>
				</tr> 
				
				
				<tr>
				<td><br><label class="" style="font-size:14px;">Maintanance Schedule</label></td>
				<td><br>
				<input type="text" name="schedule" class="m-wrap medium">
				</td>
				</tr>
				
				
				
				</table><br>
				<div class="form-actions" style="background-color:#CCC;">
				<button type="submit" name="fix_add" class="btn green">Submit</button>
				<button type="button" class="btn">Cancel</button>
				</div>
 				</form>							
				
				</div> 
				</div>         
				</center>
			
			
			
			