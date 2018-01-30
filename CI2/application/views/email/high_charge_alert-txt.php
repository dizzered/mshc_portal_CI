<?php 
$account = 0;
$name = mb_convert_encoding($first_name, 'UTF-8').' '.mb_convert_encoding($last_name, 'UTF-8');
if (is_object($accident_date))
{
	$accident_date = $accident_date->format('Y-m-d');
}
else
{
	$accident_date = '0';
}
if (is_object($birth_date))
{
	$birth_date = $birth_date->format('Y-m-d');
}
else
{
	$birth_date = '0';
}
?>
PLEASE NOTE: THE TOTAL CHARGES LISTED FOR THIS CASE DO NOT REFLECT ANY FACILITY FEES OR ANESTHESIA FEES IF YOUR CLIENT WAS SEEN AT THE HARFOD COUNTY AMBULATORY SURGICAL CENTER.

PLEASE CONTACT OUR BILLING DEPARTMENT TO FOR THE DETAILS OF THESE SERVICES.

The case for your client <!--Account Number: <?php //echo $account; ?>--> reached a total charge threshold of <?php echo number_format($grand_total, 2); ?>.  If you have any questions regarding this alert or if you would like more details regarding the charges for this client, please click here - <?php echo urlencode(base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME.'/1/0/0/0/0/0/0/'.$name.'/'.$accident_date.'/'.$birth_date); ?> - to find the related cases on our Attorney Portal. You can also contact our Business Office Managers by replying to this message, calling 410-933-5678 or utilize the Portal Inquiry Form – Billing Question Option.

<strong>Please note, in order to comply with HIPAA regulations, we are unable to list your client’s name or other Protected Health Information (PHI) within the Subject or Body of an unsecured email message. For this reason we include only your client’s Account Number as a hyperlink, which allows you to click to be connected directly to the case information on Multi-Specialty’s Attorney Portal.</strong>

You are receiving this email from the MSHC Attorney Portal. If you feel you are receiving this notice in error please contact the Portal Support Hotline at 443-579-1101 or forward this message to <a href="mailto:it@amm.bz">it@amm.bz</a>.

Thank you,

Multi-Specialty HealthCare

This e-mail, including attachments, may include confidential and/or proprietary information, and may be used only by the person or entity to which it is addressed. This e-mail may also contain information which is confidential or which is protected from disclosure by federal HIPAA regulations. Any unauthorized use, disclosure or distribution of this e-mail and its attachments is prohibited. If you are not the intended recipient or believe you have received this e-mail in error, contact us immediately by reply e-mail and destroy all electronic or other copies of this message.