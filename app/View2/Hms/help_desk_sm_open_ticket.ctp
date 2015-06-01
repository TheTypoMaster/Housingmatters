<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>

				   
<div  align="center" >
<a href="help_desk_sm_open_ticket" rel='tab' class="btn red  tooltips"  data-placement="bottom" data-original-title="Click to view tickets which are not yet resolved"><i class="icon-folder-open"></i> Open Tickets</a>
<a href="help_desk_sm_close_ticket"  rel='tab' class="btn blue  tooltips"  data-placement="bottom" data-original-title="Click to view old tickets resolved and closed"><i class="icon-folder-close"></i> Closed Tickets</a>
<a href="help_desk_sm_all_ticket"  rel='tab' class="btn blue  tooltips"  data-placement="bottom" data-original-title="View all your open and closed tickets"><i class="icon-globe "></i> All Tickets</a>
</div>
<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet box">
							<!--<div class="portlet-title">
								
							</div>-->
							<div class="portlet-body">
								<table class="table table-striped table-bordered" id="sample_2">
									<thead>
    									<tr>
											<th style="width:5%;">Sr No</th>
                                        	<th>Date</th>
                                            <th>Time</th>
											<th>Ticket No.</th>
                                            <th> Priority</th>
                                            <th>Raised by</th>
                                            <th>Flat</th>
											<th>Category</th>
                                            <th style="width:10%;">View</th>
										</tr>
									</thead>
						<tbody id="open_ticket">
   <?php
   $z=0;
	
	foreach ($result_help_desk as $collection) 
	{
		$z++;
		$help_desk_id=$collection['help_desk']['help_desk_id'];
		$date=$collection['help_desk']['help_desk_date'];
	    $time=$collection['help_desk']['help_desk_time'];
		$ticket_id=$collection['help_desk']['ticket_id'];
		$complain_type_id=(int)$collection['help_desk']['help_desk_complain_type_id'];
		$d_user_id=(int)$collection['help_desk']['user_id'];
		$help_desk_status=(int)$collection['help_desk']['help_desk_status'];
		
		
		$ticket_priority=(int)$collection['help_desk']['ticket_priority'];
		if($ticket_priority==1)
		{
			$ticket_priority="Urgent";
		}
		else
		{
			$ticket_priority="Normal";
		}
			
$result_user = $this->requestAction(array('controller' => 'hms', 'action' => 'profile_picture'),array('pass'=>array($d_user_id)));
	foreach ($result_user as $collection) 
	{
		$name=$collection['user']['user_name'];
		$wing=$collection['user']['wing'];
		$flat=$collection['user']['flat'];
	}
	
$flat = $this->requestAction(array('controller' => 'hms', 'action' => 'wing_flat'),array('pass'=>array($wing,$flat)));

$help_desk_category_name = $this->requestAction(array('controller' => 'hms', 'action' => 'help_desk_category_name'),array('pass'=>array($complain_type_id)));
?>
<tr class="odd gradeX">
	<td><?php echo $z ; ?></td>
	<td><?php echo $date; ?></td>
	<td><?php echo $time; ?></td>
	<td><?php echo $ticket_id; ?></td>
	<td><?php echo $ticket_priority; ?></td>
	<td><?php echo $name; ?></td>
	<td><?php echo $flat; ?></td>
	<td><?php echo $help_desk_category_name; ?></td>
	<td><a href="help_desk_sm_view?id=<?php echo $help_desk_id; ?>&status=<?php echo $help_desk_status; ?>" rel='tab' class="btn yellow mini green"  >View</a></td>
	
	
	
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>
<!-- END EXAMPLE TABLE PORTLET-->
<!-- END PAGE CONTENT-->
</div>









<!--------REPLY------------>
<link href="<?php echo $this->webroot ; ?>/as/reply.css" rel="stylesheet" />
<?php $a=1; ?>
<script>
	

$(document).ready(function() {
	$("#reply").live('click',function(){
	
			var r=$("#msg_reply").val();
			var a=$("#hd_id").val();

			if(r!="")
			{
			//$("#reply_post").append('<div class="outt"><div><span class="pull-right" style="font-size:12px; color:#A5A5A5;">Few minutes ago</span><br>'+ r +'</div></div>');
			
			r=encodeURIComponent(r);
			$("#reply_post").load('save_reply_resident?reply=' + r + '&id=' + a);
			$("#msg_reply").val('');
			
			}
			
				
	});
});

</script>
