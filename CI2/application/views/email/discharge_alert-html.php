<?php
if (is_object($service_date_from)) {
    $date = $service_date_from->format('m/d/Y');
} else {
    $date = 'N/A';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>MSHC Portal Discharged Client Alert</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                Your client Account Number: <?php echo $guarantor_id; ?> has been discharged from care
                on <?php echo $date; ?>. If you would like more details regarding this client,  please <a
                    href="<?php echo base_url() . MSHC_CASES_CONTROLLER_NAME . '/' . MSHC_CASES_CLIENT_SEARCH_NAME . '/0/summary/' . $guarantor_id . '/' . $database_name . '/' . $practice_id . '/' . $patient_no . '/' . $case_no; ?>">click
                    here</a> to view the Case Summary on our Attorney Portal.
                <br>
                <br>
                <span style="color: red;"><strong>Please be aware that the complete account summary will not be immediately available on the Portal due to the billing transactions not being finalized for the discharge date. The billing review process normally takes 7 to 10 business days to complete. Please revisit the Attorney Portal at that time.</strong>
                <br>
                <br>
                <span style="font-size: 16px;"><strong>Need an immediate settlement?</strong></span>
                <br>
                To <strong>start the settlement process</strong> for this case, or if you would like more details regarding the discharge, please contact our <strong>Settlement Department</strong> by:
                <br><br>
                - <strong>Replying</strong> to this message
                <br>
                - <strong>Calling</strong> 410-933-5678 Option 2
                <br>
                - Or utilize the <strong>Attorney Portal Contact Us – Settlement Request Option</strong></span>
                <br>
                <br>
                Please note, in order to comply with HIPAA regulations, we are unable to list your client’s name or
                other Protected Health Information (PHI) within the Subject or Body of an unsecured email message. For
                this reason we include only your client’s Account Number which allows you to determine the client with a
                simple search via MSHC’s Attorney Portal.
                <br>
                <br>
                You are receiving this email from the MSHC Attorney Portal. If you feel you are receiving this notice in
                error please contact the Portal Support Hotline at 443-579-1101 or forward this message to it@amm.bz.
                <br><br>
                Thank you,
                <br>
                <br>
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