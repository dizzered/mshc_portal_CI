<?php
if (is_object($appt_date) && 
	is_object($appt_time)
)
{
	$date = $appt_date->format('m/d/Y');
	$time = $appt_time->format('H:i');
}
else
{
	$date = 'N/A';
	$time = '';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>MSHC Portal Missed Appointment Alert</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
Your client Account Number: <?php echo $guarantor_id; ?> has missed or cancelled their appointment scheduled on <?php echo $date; ?> at <?php echo $time; ?> with <?php echo $doc_last_name.', '.$doc_first_name; ?> for a <?php echo $reason; ?> at our <?php echo $location; ?> Office. If you have any questions regarding this alert or would like to reschedule this visit please contact our Scheduling Department by replying to this message, calling 888-807-2778 or utilize the Portal Inquiry Form – Scheduling Question Option. If you would like more details regarding the appointments for this case, please <a href="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME.'/0/appts/'.$guarantor_id.'/'.$database_name.'/'.$practice_id.'/'.$patient_no.'/'.$case_no; ?>">click here</a> to view the Case Appointments on our Attorney Portal.
<br>
<br>
Please note, in order to comply with HIPAA regulations, we are unable to list your client’s name or other Protected Health Information (PHI) within the Subject or Body of an unsecured email message. For this reason we include only your client’s Account Number which allows you to determine the client with a simple search via MSHC’s Attorney Portal.
<br>
<br>
You are receiving this email from the MSHC Attorney Portal. If you feel you are receiving this notice in error please contact the Portal Support Hotline at 443-579-1101 or forward this message to it@amm.bz.
<br><br>
Thank you,
<br>
<br>
<strong>Multi-Specialty HealthCare</strong>
<br><br>
This e-mail, including attachments, may include confidential and/or proprietary information, and may be used only by the person or entity to which it is addressed. This e-mail may also contain information which is confidential or which is protected from disclosure by federal HIPAA regulations. Any unauthorized use, disclosure or distribution of this e-mail and its attachments is prohibited. If you are not the intended recipient or believe you have received this e-mail in error, contact us immediately by reply e-mail and destroy all electronic or other copies of this message.
</td>
</tr>
</table>
</div>
</body>
</html>