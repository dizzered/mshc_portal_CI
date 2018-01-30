<?php
if ($role_id == MSHC_AUTH_SYSTEM_ADMIN)
{
	$dialog_width = 640;
	$dialog_height = 255;
	$display_notifs = 'none';
}
else
{
	$dialog_width = 640;
	$dialog_height = 460;
	$display_notifs = 'block';
}
?>

<div id="dialog-user-account">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">My Account</h2>
            <form id="user-account">
            <fieldset>
            <input type="hidden" id="account_view_id" name="account_view_id" value="<?php echo $user_id; ?>" />
            <input type="hidden" id="account_username" name="account_username" value="<?php echo $username; ?>" />
            <div style="float:left;width:305px;">
                <label for="account_lastname">Last Name:</label>
                <input type="text" name="account_lastname" id="account_lastname" value="<?php echo $last_name; ?>" class="text" /><br>
                <div style="float:left;width:80px;">&nbsp;</div>
                <div id="account_lastname-msg" style="color:red;height:15px;">&nbsp;</div>
                <label for="account_firstname">First Name:</label>
                <input type="text" name="account_firstname" id="account_firstname" value="<?php echo $first_name; ?>" class="text" /><br>
                <div style="float:left;width:80px;">&nbsp;</div>
                <div id="account_firstname-msg" style="color:red;height:15px;">&nbsp;</div>
                <label for="account_email">Email:</label>
                <input type="text" name="account_email" id="account_email" value="<?php echo $email; ?>" class="text" />
                <div style="float:left;width:80px;">&nbsp;</div>
                <div id="account_email-msg" style="color:red;height:15px;">&nbsp;</div>                
            </div>
            <div style="float:left;width:305px;">
                <div id="user-account-password-maintenance">
                <div style="float:left;width:100px; margin-top:9px;">Password:</div>
                <input type="button" value="Change" id="btn-user-account-password-change" class="input-button-grey" /> 
                <input type="button" value="Reset" id="btn-user-account-password-reset" class="input-button-grey" />
                </div>
            </div>
            
            <div style="clear:both;"></div>

            <div id="user-account-notifications" style="display:<?php echo $display_notifs; ?>;">
                <table border="0" cellpadding="0" cellspacing="0" style="background-color:#efefef;width:730px;margin:0 0 15px 0;">
                <tr>
                <td style="padding:10px;vertical-align:middle;" align="left">
                <h3 style="font-weight:bold;font-size:14px;">Notifications</h3>
                </td>
                <td style="padding:10px;vertical-align:middle;" align="right">
                <input type="button" value="Select All" id="user-account-notif-select-all" class="input-button-grey">
                <input type="button" value="Unselect All" id="user-account-notif-unselect-all" class="input-button-grey">
                </td>
                </tr>
                </table>
                <div style="clear:both;"></div>
                <div style="float:left;width:275px;">
                    <div style="width:160px; float:left;margin-top:3px;">Missed Appointments:</div>
                    <?php echo form_checkbox('account_notifications[]','missed_appointments_notified',$missed_appointments_notified); ?>
                    <div style="clear:both;height:25px;"></div>
                    <div style="width:160px; float:left;margin-top:3px;">Patient Case Discharge:</div>
                    <?php echo form_checkbox('account_notifications[]','case_discharge_notified',$case_discharge_notified); ?>
                 </div>
                 <div style="float:left;width:455px;">
                    <div class="new-docs-notifications-container">
                    <strong>New Documents</strong><br />
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                    <td align="left">
                    <div style="width:170px; float:left;margin-top:3px;">Medical Reports:</div>
                    <?php echo form_checkbox('account_notifications[]','medical_report_notified',$medical_report_notified); ?>
                    <div style="clear:both;"></div>
                    <div style="width:170px; float:left;margin-top:3px;">PT Note:</div>
                    <?php echo form_checkbox('account_notifications[]','pt_note_notified',$pt_note_notified); ?>
                    <div style="clear:both;"></div>
                    <div style="width:170px; float:left;margin-top:3px;">Outside Medical Record:</div>
                    <?php echo form_checkbox('account_notifications[]','outside_medical_record_notified',$outside_medical_record_notified); ?>
                    <div style="clear:both;"></div>
                    <div style="width:170px; float:left;margin-top:3px;">Consult:</div>
                    <?php echo form_checkbox('account_notifications[]','consult_notified',$consult_notified); ?>
                    </td>
                    <td align="left">
                    <div style="width:170px; float:left;margin-top:3px;">PT-BWR Referral:</div>
                    <?php echo form_checkbox('account_notifications[]','ptbwr_referral_notified',$ptbwr_referral_notified); ?>
                    <div style="clear:both;"></div>
                    <div style="width:170px; float:left;margin-top:3px;">Disability:</div>
                    <?php echo form_checkbox('account_notifications[]','disability_notified',$disability_notified); ?>
                    <div style="clear:both;"></div>
                    <div style="width:170px; float:left;margin-top:3px;">Pharmacy:</div>
                    <?php echo form_checkbox('account_notifications[]','pharmacy_notified',$pharmacy_notified); ?>
                    </td></tr>
                    </table>
                    </div>
                </div>
            </div>
            </fieldset>
            </form>            
        </div>
    </div>
</div>
<script>
$(function() 
{
	/*
	* USER ACCOUNT
	*/
	
	$( "#dialog-user-account" ).dialog({
            autoOpen: false,
            modal: true,
			closeText: "close",
			draggable: false,
			resizable: false,
			dialogClass: "",
            buttons: {
                "Save": function() {
					saveUserAccountData();
                },
                Cancel: function() {
                    $(this).dialog( "close" );
                }
            }
    });
	
	$("#dialog-user-account").dialog('open');
	$("#dialog-user-account").dialog('close');
	
	$('.usernamelink').live('click', function(e) {
		e.preventDefault();		
		$('#dialog-user-account').dialog('option', 'height', <?php echo $dialog_height; ?>);
		$('#dialog-user-account').dialog('option', 'width', <?php echo $dialog_width; ?>);
		$('#dialog-user-account').dialog('open');
	});
	
	$('#user-account-notif-select-all').live('click', function() {
		$('input[name="account_notifications[]"]').each(function(index, element) {
            $(this).attr('checked',true);
        });
	});

	$('#user-account-notif-unselect-all').live('click', function() {
		$('input[name="account_notifications[]"]').each(function(index, element) {
            $(this).attr('checked',false);
        });
	});

	$('#btn-user-account-password-change').live('click', function() {
		$('#dialog-change-password').dialog(
			'option', 
			'buttons', 
			[
				{
					text: "Change", click: function() {
						changeUserAccountPassword();
					}
				},
				{
					text: "Cancel", click: function() {
						$(this).dialog('close');
					}
				}				
			]
		);
		$('#dialog-change-password').dialog('open');
	});

	$('#btn-user-account-password-reset').live('click', function() {
		$('#prompt_func_name').val('resetUserAccountPassword');
		$("#dialog-prompt-message-text").html('<h2>Reset Password</h2>Are you sure you want to reset password?');
		$("#dialog-prompt-message").dialog('open');		
	});
	
});

function saveUserAccountData()
{
	var userData = {};
	userData.last_name = $('#account_lastname').val();
	userData.first_name = $('#account_firstname').val();
	if ($('#account_email').val().length) {
		userData.email = $('#account_email').val();
		$('#account_email').removeClass('ui-state-error');
		$('#account_email-msg').html('');
	}
	else {
		$('#account_email').addClass('ui-state-error');
		$('#account_email-msg').html('Please enter valid email.');
		return;
	}
	userData.notifications = {};
	$('input[name="account_notifications[]"]').each(function(index, element) {
		if ($(this).is(':checked'))	userData.notifications[$(this).val()] = 1;
		else userData.notifications[$(this).val()] = null;
	});
	userData.view_id = $('#account_view_id').val();
	display_please_wait();
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/process_update_user_account',
		dataType: 'html',
		data: userData,
		success: function(data){
			data = jQuery.parseJSON(data);
			if (data.code == 200) {
				display_text_message('<h2>User Saved</h2>'+data.message, 400, 240);
				$('#dialog-general-message').on('dialogclose', function(event, ui) {
					$(location).attr('href', $(location).attr('href'));
				});
			}
			else
			{
				display_text_message(data.message, 350, 150);
			}
			$( "#dialog-user-account" ).dialog('close');
			
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			$( "#dialog-user-account" ).dialog('close');
			display_text_message('Error. Please contact us.', 300, 200);
		}
	});
}

function changeUserAccountPassword()
{
	$('.ui-dialog-buttonpane .ui-dialog-buttonset').addClass('ui-button-ajax-loader');
	var newPassword = $("#new-password").val();
	var newPasswordConfirm = $("#new-password-confirm").val();
	$('#new-password-error').html('&nbsp;');
	$('#new-password-confirm-error').html('&nbsp;');
	if (newPassword.length < 7)
	{
		$('#new-password-error').html('Password is too short.');
		$("#new-password").val('');
		$("#new-password-confirm").val('');
		$('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
		return;
	}
	if (newPassword != newPasswordConfirm)
	{
		$('#new-password-confirm-error').html('Passwords is mismatched.');
		$("#new-password-confirm").val('');
		$('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
		return;
	}
	
	// check for correctness and change
	$.ajax({
		type: 'POST',
		async: false,
		url: baseURL + ajaxCONTROLLER + '/change_user_password',
		dataType: 'html',
		data: {
			user_id: $('#account_view_id').val(), 
			username: $('#account_username').val(), 
			email: $('#account_email').val(), 
			password: newPassword
		},
		success: function(data) {
			data = jQuery.parseJSON(data);
			if (data.code == 200)
			{
				$("#dialog-change-password").dialog('close');
				display_text_message('<h2>Change Password</h2>'+data.message, 400, 220);
				$('#dialog-general-message').on('dialogclose', function(event, ui) {
					$(location).attr('href', '<?php echo base_url().MSHC_AUTH_CONTROLLER_NAME.'/logout'?>');
				});		
			}
			else
			{
				$('#new-password-error').html(data.message);
			}
		},
		complete: function() {
			$('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
		},
		error: function(data){
			// display Error message
			display_text_message('Error. Please contact us.', 300, 200);
		}
	});
}

function resetUserAccountPassword()
{
	$('.ui-dialog-buttonpane .ui-dialog-buttonset').addClass('ui-button-ajax-loader');	
	$.ajax({
		type: 'POST',
		async: false,
		url: baseURL + ajaxCONTROLLER + '/reset_user_password',
		dataType: 'html',
		data: {
			user_id: $('#account_view_id').val(), 
			username: $('#account_username').val(), 
			email: $('#account_email').val()
		},
		success: function(data) {
			data = jQuery.parseJSON(data);
			display_text_message('<h2>Reset Password</h2>'+data.message, 400, 220);
			$('#dialog-general-message').on('dialogclose', function(event, ui) {
				$(location).attr('href', '<?php echo base_url().MSHC_AUTH_CONTROLLER_NAME.'/logout'?>');
			});		
		},
		complete: function() {
			$('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
		},
		error: function(data){
			// display Error message
			display_text_message('Error. Please contact us.', 300, 200);
		}
	});	
}

</script>