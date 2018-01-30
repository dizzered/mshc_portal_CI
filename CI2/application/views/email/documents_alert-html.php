<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>MSHC Portal Documents Alert</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
Your client Account Number: <?php echo $guarantor_id; ?> has a new <?php echo $document_type; ?>. If you have any questions regarding this alert please contact our Billing Department by replying to this message, calling 410-933-5678 or utilize the Portal Inquiry Form – Billing Question Option. To open this document, please <a href="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/documents/'.$guarantor_id.'/'.$practice_id.'/'.$patient_no.'/'.$case_no.'/'.$id.'/'.($lPAGEID ? $lPAGEID : '0').'/'.strtolower($database_name); ?>">click here</a>.
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