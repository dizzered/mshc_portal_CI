<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
		<tr><td>
		<span class="ico-header-title" style="margin-right:15px;">User Maintenance</span><div id="btn-add-new-user" style="visibility:hidden;"></div>
		</td></tr>
	</table>
</div>
<div class="main-container"> 
	<div id="dashboard-user-table-container"></div>
	
	<div style="text-align:right;padding-top:10px; margin-bottom:-15px;">
	<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_USERS_NAME; ?>" style="color:#b88f01; text-decoration: none; font-weight: bold; cursor: pointer">view all ></a>
	</div>
</div>

<script>
tableID = 'User';
tableName = 'user';
dbName = 'users';
$(function() {
	$('#dashboard-user-table-container').jtable('load');
	
	window['appendUserDashboardSearchBar']();
});
</script>