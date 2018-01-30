<?php echo $users_dialog; ?>

<?php
if ($dashboard_banner)
{
	echo '<div class="dashboard-banner">'.$dashboard_banner.'</div>';
}
?>

<table width="100%" style="margin:40px 0;">
	<tr>
	<td style="padding-right: 25px; width:600px;">
		<?php echo $cases_search_form; ?>
		
		<div class="clear" style="height:30px;"></div>
		
		<?php echo $users_table; ?>
	</td>
    <td style="width:410px;">
		<div class="main-container-header" style="background-color:#e4ecef">
			<table border="0" class="main-container-header-tab">
				<tr><td><span class="ico-header-title" style="margin-right:15px;">Firm/Attorney Maintenance</span></td></tr>
			</table>
		</div>
		
		<?php echo $firms_table; ?>
		
		<div class="clear" style="height:30px;"></div>
		
		<div class="main-container-header" style="background-color:#e4ecef">
			<table border="0" class="main-container-header-tab">
				<tr><td><span class="ico-header-title" style="margin-right:15px;">Patient Form Maintenance</span>
				<div id="btn-add-new-form" class="tab-right-beige" style="visibility:hidden;"><p>Add New Forms</p></div></td></tr>
			</table>
		</div>
		
		<?php echo $forms_table; ?>
	</td>
    </tr>
</table>

<div class="dashboard-bottom-icons">
<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_SETTINGS_NAME; ?>">
<img src="/images/dashboard_portal_settings.png" />
</a>
<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_CLIENTS_NAME; ?>">
<img src="/images/dashboard_practice.png" />
</a>
<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_MARKETERS_NAME; ?>">
<img src="/images/dashboard_marketer.png" />
</a>
<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_ACTIVITIES_NAME; ?>">
<img src="/images/dashboard_events.png" />
</a>
</div>
