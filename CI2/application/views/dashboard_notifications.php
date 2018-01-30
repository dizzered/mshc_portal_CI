<?php
if (count($notifications))
{
	?>
	<form id="document_open_form" name="document_open_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/documents'; ?>" target="_blank">
	<input type="hidden" value="" name="document_checkbox[]" />
	<input id="documents_account" name="documents_account" type="hidden" value="" />
	<?php
	foreach ($notifications as $one_notification) 
	{ 
		?> 
		<div class="notification icon_<?php echo $one_notification['type']; ?>">
		
			<h2 style="font-weight:<?php echo $one_notification['read'] == 1 ? 'normal' : 'bold'; ?>;"><?php echo $one_notification['title']; ?></h2>

			<p><?php echo $one_notification['body']; ?></p>

		</div>
   		<?php
	}
	?>
	</form>
	<div class="notification-bottom">
	
		<div style="float: left"><a href="<?php echo base_url().MSHC_NOTIFICATIONS_CONTROLLER_NAME; ?>">
		<?php echo $count_new_notifications; ?> New notification</a></div>
		
		<div style="float: right"><a href="<?php echo base_url().MSHC_NOTIFICATIONS_CONTROLLER_NAME; ?>">view all ></a></div>
		
		<div class="clear"></div>
	
	</div>
	<?php
}
else
{
	?>
	<h2>Notifications not found.</h2>
	<?php
}
?>