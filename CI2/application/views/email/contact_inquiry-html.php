<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>MSHC Attorney Portal: New Inquiry</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                Inquiry from <?php echo $name; ?> <em>(Username:
                    <strong><?php echo $this->_user['username']; ?></strong>)</em><br><br>
                Email: <?php echo $email; ?>
                <br><br>
                Inquiry type: <?php echo $inquiry_type;
                echo ($inquiry_type_id == 'marketers' && isset($marketer_info))
                    ? '; Marketer - ' . $marketer_info['first_name'] . ' ' . $marketer_info['last_name']
                    : ''; ?>
                <br>
                Case Information:
                <br>
                Account #: <?php echo $case_contact_account; ?>
                <br>
                Class: <?php echo $case_contact_class; ?>
                <br>
                DOA: <?php echo $case_contact_doa; ?>
                <br><br>
                Phone: <?php echo $phone; ?>
                <br><br>
                Message: <?php echo $body; ?>
                <br><br>
                <strong>Multi-Specialty HealthCare</strong>
                <br><br>
                This e-mail, including attachments, may include confidential and/or proprietary information, and may be
                used only by the person or entity to which it is addressed. This e-mail may also contain information
                which is confidential or which is protected from disclosure by federal HIPAA regulations. Any
                unauthorized use, disclosure or distribution of this e-mail and its attachments is prohibited. If you
                are not the intended recipient or believe you have received this e-mail in error, contact us immediately
                by reply e-mail and destroy all electronic or other copies of this message.
            </td>
        </tr>
    </table>
</div>
</body>
</html>