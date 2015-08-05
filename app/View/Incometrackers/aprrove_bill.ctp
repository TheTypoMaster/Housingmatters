<?php
echo $this->requestAction(array('controller' => 'hms', 'action' => 'submenu'), array('pass' => array()));
?>				   
<script>
$(document).ready(function() {
$("#fix<?php echo $id_current_page; ?>").removeClass("blue");
$("#fix<?php echo $id_current_page; ?>").addClass("red");
});
</script>
<style>
th{
	font-size: 10px !important;background-color:#D8E7EC;
}
th,td{
	padding:2px;
	font-size: 12px;border:solid 1px #31B0D5;
}
.text_bx{
	width: 50px;
	height: 15px !important;
	margin-bottom: 0px !important;
	font-size: 12px;
}
.text_rdoff{
	width: 50px;
	height: 15px !important;
	border: none !important;
	margin-bottom: 0px !important;
	font-size: 12px;
}
</style>
<div class="portlet-body" style="background-color: #fff; overflow-x: auto;" align="center">
<table >
	<thead>
		<tr>
			<th>#</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th class="hidden-phone">Username</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Mark</td>
			<td>Otto</td>
			<td class="hidden-phone">makr124</td>
			<td><span class="label label-success">Approved</span></td>
		</tr>
		<tr>
			<td>2</td>
			<td>Jacob</td>
			<td>Nilson</td>
			<td class="hidden-phone">jac123</td>
			<td><span class="label label-info">Pending</span></td>
		</tr>
		<tr>
			<td>3</td>
			<td>Larry</td>
			<td>Cooper</td>
			<td class="hidden-phone">lar</td>
			<td><span class="label label-warning">Suspended</span></td>
		</tr>
		<tr>
			<td>3</td>
			<td>Sandy</td>
			<td>Lim</td>
			<td class="hidden-phone">sanlim</td>
			<td><span class="label label-danger">Blocked</span></td>
		</tr>
	</tbody>
</table>
</div>
							