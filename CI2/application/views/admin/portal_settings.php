<div class="main-container-header">
</div>

<div class="main-container" style="padding-bottom:20px;">
    <form id="portal_settings" name="portal_settings" action="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/save_portal_settings'; ?>" method="post" enctype="multipart/form-data">
         <fieldset>	
         <input type="hidden" id="view_id" name="view_id" value="<?php if (isset($portal_settings['id'])) echo $portal_settings['id']; ?>" />
    <table class="contact-form-table" cellpadding="0" cellspacing="0" style="width:99%;">
    	<tr>
        	<td align="left" class="portal_settings-form-label" id="logo_uploaded" name="logo_uploaded" style="width:150px;">
            	<?php 
					if ($portal_settings['logo'] != '') {
						echo '<img src=\''.base_url().MSHC_UPLOAD_FILE_PATH.'/'.$portal_settings['logo'].'\' style="border:1px" width="100px"/>';
					}
					else
					{
						?>
						<div style="border:1px solid #cecece; width:132px; height:93px; line-height:93px; text-align:center; color:#cecece;"><em>LOGO HERE</em></div>
						<?php
					}
				?>
            </td>
            <td align="left">
            	 <div id="logo_upload" style="display: <?php if ($portal_settings['logo'] != '') { echo 'none'; } else { echo 'block'; } ?>">
				 
					 <div id="file_upload" style="display:block; vertical-align::top">
						<span class="file-wrapper" style="margin-bottom:0;">
						<input type="file" name="file_name" id="file_name" style="width:296px;">
						<input type="text" class="file-holder" value="" style="width:195px;" placeholder="Browse files">
						<span class="input-button-grey file-button">Choose File</span></span>
						<div style="clear:both;"></div>
						<div style="float:left;width:80px;">&nbsp;</div>
						<div id="file_name-msg" style="color:red;height:15px;">&nbsp;</div>
					</div>
				 
					<!--<input type="file" name="logo" id="logo" value="" class="text" />-->
					<!--<div style="float:left;width:80px;">&nbsp;</div>-->
					<div id="logo-msg" style="color:red;height:15px;">&nbsp;</div>
			  </div>
			  <div id="logo_delete" style="display: <?php if ($portal_settings['logo'] != '') { echo 'block'; } else { echo 'none'; } ?>">
					<span id="logo_uploaded"></span>
					<span class="input-button-grey" id="btn_portal_settings_delete_logo" >Delete Logo</span>
				</div>
             </td>
      	</tr>
     </table>
            
     <div style="background-color:#e4ecef; line-height: 24px;border:1px solid #cecece;padding-left: 20px" align="left">
     	<h2>SMTP Server</h2>
     	<table class="settings-form-table" cellpadding="0" cellspacing="0" style="width:99%; margin:0;">
        	<tr>
            	<td width="100" align="left" class="contact-form-label"><label for="server_url">Server URL:</label></td>
          		<td align="left"><input type="text" id="server_url" name="server_url" maxlength="100" value="<?php echo $portal_settings['server_url'];?>" /></td>
                <td width="80" align="left" class="contact-form-label"><label for="server_port">Port:</label></td>
          		<td align="left"><input type="text" id="server_port" name="server_port" maxlength="10" value="<?php echo $portal_settings['server_port'];?>" style="width:100px"/></td>
            </tr>
            <tr>
            	<td width="100" align="left" class="contact-form-label"><label for="username">User Name:</label></td>
          		<td align="left"><input type="text" id="username" name="username" maxlength="100" value="<?php echo $portal_settings['username'];?>" /></td>
                <td width="80" align="left" class="contact-form-label"><label for="password">Password:</label></td>
          		<td align="left"><input type="text" id="password" name="password" maxlength="50" value="<?php echo $portal_settings['password'];?>" /></td>
            </tr>
            <tr>
            	<td width="100" align="left" class="contact-form-label"><label for="email_from">Emal From:</label></td>
          		<td align="left" colspan="3"><input type="text" id="email_from" name="email_from" maxlength="255" value="<?php echo $portal_settings['email_from'];?>" style="width:600px"/></td>
            </tr>
        </table>
     </div>
     
     <br />
     <table class="settings-form-table" cellpadding="0" cellspacing="0" style="width:99%; margin-left:20px;">
        	<tr>
            	<td width="220" align="left" class="contact-form-label"><label for="failed_password_attempt_count">How many times can user try to log in before they get locked out?</label></td>
          		<td align="left"><input type="text" id="failed_password_attempt_count" name="failed_password_attempt_count" maxlength="10" value="<?php echo $portal_settings['failed_password_attempt_count'];?>" style="width:70px"/></td>
                <td rowspan="7" width="520" align="left" style="vertical-align:top;">
                	<strong>Dashboard Banner</strong><br /><br />
                    <textarea id="dashboard_banner" name="dashboard_banner" rows="10" cols="50"><?php echo $portal_settings['dashboard_banner']; ?></textarea>
                </td>
            </tr>
            <tr>
            	<td align="left" class="contact-form-label"><label for="email_administrator">AR Administrator Email:</label></td>
          		<td align="left"><input type="text" id="email_administrator" name="email_administrator" maxlength="100" value="<?php echo $portal_settings['email_administrator'];?>" /></td>
            </tr>
            <tr>
            	<td align="left" class="contact-form-label"><label for="email_scheduling">Email for Scheduling:</label></td>
          		<td align="left"><input type="text" id="email_scheduling" name="email_scheduling" maxlength="100" value="<?php echo $portal_settings['email_scheduling'];?>" /></td>
            </tr>
             <tr>
            	<td align="left" class="contact-form-label"><label for="email_settlements">Email for Settlements:</label></td>
          		<td align="left"><input type="text" id="email_settlements" name="email_settlements" maxlength="100" value="<?php echo $portal_settings['email_settlements'];?>" /></td>
            </tr>
             <tr>
            	<td align="left" class="contact-form-label"><label for="email_patient_registration">Email for Patient Registration:</label></td>
          		<td align="left"><input type="text" id="email_patient_registration" name="email_patient_registration" maxlength="100" value="<?php echo $portal_settings['email_patient_registration'];?>" /></td>
            </tr>
             <tr>
            	<td align="left" class="contact-form-label"><label for="email_it_contact">Email for IT Contact:</label></td>
          		<td align="left"><input type="text" id="email_it_contact" name="email_it_contact" maxlength="100" value="<?php echo $portal_settings['email_it_contact'];?>" /></td>
            </tr>
            <tr>
            	<td align="left" class="contact-form-label"><label for="email_marketing_distribution_list">Email for Marketing Distribution List:</label></td>
          		<td align="left"><input type="text" id="email_marketing_distribution_list" name="email_marketing_distribution_list" maxlength="100" value="<?php echo $portal_settings['email_marketing_distribution_list'];?>" /></td>
            </tr>
      </table>
           
        <div style="text-align:left; margin-left:20px;">
           <input id="btn_portal_settings_submit" type="button" name="btn_portal_settings_submit" value="Submit" class="contact-form-handler-btn ui-button" style="width:80px;" />
           <input type="button" id="btn_portal_settings_cancel" name="btn_portal_settings_cancel" value="Cancel" class="contact-form-handler-btn ui-button" />
       </div>
           
     	</fieldset>
     </form>
	 
	 
	 <div style="background-color:#e4ecef; border:1px solid #cecece;padding-left: 20px; margin-top:20px; padding-bottom:20px;" align="left">
     	<h2>Notifications Maintenance</h2>
		
		<div style="width:412px;">
			<h3 style="float:left; margin-top:5px;">Users</h3>
			<input type="button" class="input-button-grey" id="users_select" style="float:right;" value="Select All">
		</div>
		
		<div class="clear"></div>
		
		<div style="height:300px; width:400px; overflow:auto;border:1px solid #cecece; padding:0 5px 0; float:left;">
		<?php
		foreach ($users as $user)
		{
			$data = array(
				'name'        => 'user_'.$user['id'],
				'id'          => 'user_'.$user['id'],
				'value'       => $user['id'],
				'checked'     => FALSE,
				'style'       => 'margin:5px 10px 5px 0',
			);
			
			echo form_checkbox($data).form_label($user['username'].' ('.$user['first_name'].' '.$user['last_name'].')', $data['id']);
			echo '<div class="clear"></div>';
		}
		?>
		</div>
		<div style="width:400px; float:left; margin-left:50px;">
			<h3 style="margin-bottom:10px; margin-top:0;">Notifications date range</h3>
			
			<label for="notif_date_from">From: </label>
			<input type="text" id="notif_date_from" name="notif_date_from" />
			<label for="notif_date_to">to: </label>
			<input type="text" id="notif_date_to" name="notif_date_to" />
			
			<div class="clear" style="height:10px;"></div>
			
			<h3 style="margin-bottom:5px;">Notifications type</h3>
			
			<input type="checkbox" class="notification-type" name="missed_appt" value="missed_apt" id="missed_appt" style="margin:5px 10px 5px 0">
			<label for="missed_appt">Missed Appointments</label>
			<div class="clear"></div>
			
			<input type="checkbox" class="notification-type" name="documents" value="docs" id="documents" style="margin:5px 10px 5px 0">
			<label for="documents">New Documents</label>
			<div class="clear"></div>
			
			<input type="checkbox" class="notification-type" name="discharged" value="discharged" id="discharged" style="margin:5px 10px 5px 0">
			<label for="discharged">Discharged Cases</label>
			<div class="clear"></div>
			
			<input type="checkbox" class="notification-type" name="high_charges" value="high_charge" id="high_charges" style="margin:5px 10px 5px 0">
			<label for="high_charges">High Charge Alert</label>
			<div class="clear"></div>
			
			<div class="clear" style="height:40px;"></div>
			
			<input id="create_notifications" type="button" name="create_notifications" value="Create Notifications" class="contact-form-handler-btn ui-button">
			<input id="delete_notifications" type="button" name="delete_notifications" value="Delete Notifications" class="contact-form-handler-btn ui-button" style="display:none;">
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	
</div>
<script>
	var editor = CKEDITOR.replace('dashboard_banner');
</script>

<script>
var VIGET = VIGET || {};
var btnClearFile = '<span class="input-button-grey file-clear">Delete File</span>';
VIGET.fileInputs = function() {
	$('.file-clear').remove();
	var $this = $(this),
				$val = $this.val(),
				valArray = $val.split('\\'),
				newVal = valArray[valArray.length-1],
				$button = $this.siblings('.file-button'),
				$fakeFile = $this.siblings('.file-holder');
	if(newVal !== '') 
	{
		if($fakeFile.length === 0) {
			$button.after('' + newVal + '');
		} else {
			$fakeFile.val(newVal);
			var id = $this.parents('.file-wrapper').metadata().id;
			$this.parents('.file-wrapper').after(btnClearFile);
			$this.parents('.file-wrapper').next('.file-clear').attr('data','{"id": '+id+'}');
		}
	}
	else
	{
		$fakeFile.val('');
	}
};

$(function() { 
	$('.file-wrapper input[type="file"]').live('change focus click', VIGET.fileInputs);
	
	$('.file-clear').live('click', function() {
		var id = $(this).metadata().id;
		$('#fileupload' + id).val('');
		$('#fileupload' + id).trigger('change');
		$(this).remove();
	});	
	
	$( "#notif_date_from" ).datepicker({
      defaultDate: "c",
      changeMonth: true,
	  changeYear: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: '',
	  maxDate: 'c',
      onClose: function( selectedDate ) {
        $( "#notif_date_to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
	
    $( "#notif_date_to" ).datepicker({
      defaultDate: "c",
      changeMonth: true,
	  changeYear: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: '',
	  maxDate: 'c',
      onClose: function( selectedDate ) {
        $( "#notif_date_from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
	
	$('#users_select').on('click', function() {
		if ($(this).val() == 'Select All')
		{
			$('input[name*="user_"]').attr('checked', true);
			$(this).val('Unselect All');
		}
		else
		{
			$('input[name*="user_"]').attr('checked', false);
			$(this).val('Select All');
		}
	});
	
	$('#create_notifications').on('click', function() {
		var dateFrom = $('#notif_date_from').val(),
			dateTo = $('#notif_date_to').val(),
			usersList = [],
			notifTypes = [];
			
		if (dateFrom == null || dateTo == null)
		{
			display_text_message('Please select date range.', 300, 200);
			return;
		}
		
		$('input[name*="user_"]:checked').each(function(index, element) {
			usersList.push($(this).val());
		});

		if (usersList.length == 0)
		{
			display_text_message('Please select at least one user.', 300, 200);
			return;
		}
		
		$('.notification-type:checked').each(function(index, element) {
			notifTypes.push('\'' + $(this).val() + '\'');
		});
		
		if (notifTypes.length == 0)
		{
			display_text_message('Please select at least one notification type.', 300, 200);
			return;
		}
		
		display_please_wait();
		$.ajax({
			type: 'POST',
			async: true,
			url: baseURL + ajaxCONTROLLER + '/create_notifications',
			dataType: 'html',
			data: {
				dateFrom: dateFrom,
				dateTo: dateTo,
				notifTypes: notifTypes,
				usersList: usersList
			},
			success: function(data) {
				data = $.parseJSON(data);
				var msg = '';
				for (var i = 0; i < data.length; ++i)
				{
					msg += data[i] + '<br />';
				}
				display_text_message(msg, 600, 400);
			},
			complete: function() {
				close_please_wait();
			},
			error: function(data){
				// display Error message
				display_text_message('Error');
			}
		});
	});
});
</script>