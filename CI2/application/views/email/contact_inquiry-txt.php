Inquiry from <?php echo $name; ?> (Username: <?php echo $this->_user['username']; ?>)

Email: <?php echo $email; ?>
Inquiry type: <?php echo $inquiry_type; echo ($inquiry_type_id == 'marketers' && isset($marketer_info)) ? '; Marketer - '.$marketer_info['first_name'].' '.$marketer_info['last_name'] : ''; ?>

Case Information:

Account #: <?php echo $case_contact_account; ?>
Class: <?php echo $case_contact_class; ?>
DOA: <?php echo $case_contact_doa; ?>

Phone: <?php echo $phone; ?>

Message: <?php echo $body; ?>

Multi-Specialty HealthCare

This e-mail, including attachments, may include confidential and/or proprietary information, and may be used only by the person or entity to which it is addressed. This e-mail may also contain information which is confidential or which is protected from disclosure by federal HIPAA regulations. Any unauthorized use, disclosure or distribution of this e-mail and its attachments is prohibited. If you are not the intended recipient or believe you have received this e-mail in error, contact us immediately by reply e-mail and destroy all electronic or other copies of this message.