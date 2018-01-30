<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>New case registration request</title>
<style>
p {
	margin-bottom:0px;
	margin-top:0px;
}
</style>
</head>
<body>
<div style="max-width: 800px; margin: 0; padding: 0;">
	<h2>New case registration request</h2>
	<p>Name: <?php echo element('name', $data); ?></p>
	<p>DOB: <?php echo element('dob', $data); ?></p>
	<p>DOA: <?php echo element('doa', $data); ?></p>
	<p>Address 1: <?php echo element('address1', $data); ?></p>
	<p>Address 2: <?php echo element('address2', $data); ?></p>
	<p>City: <?php echo element('city', $data); ?></p>
	<p>State: <?php echo element('state', $data); ?></p>
	<p>Zip: <?php echo element('zip', $data); ?></p>
	<p>Home Phone: <?php echo element('home_phone', $data); ?></p>
	<p>Work Phone: <?php echo element('work_phone', $data); ?></p>
	<p>Other Phone: <?php echo element('other_phone', $data); ?></p>
	<p>Email: <?php echo element('email', $data); ?></p>
	<p>Insurance Carrier: <?php echo element('insurer', $data); ?></p>
	<p>Claim No: <?php echo element('claim_no', $data); ?></p>
	<p>Adjuster: <?php echo element('adjuster', $data); ?></p>
	<p>- - - - - - - - -</p>
	<p>Appointment Request</p>
	<p>Location: <?php echo element('new_case_register_location', $data); ?></p>
	<p>Appointment Date: <?php echo element('appt_date', $data); ?></p>
	<p>Accident Type: <?php echo element('new_case_register_accidents', $data); ?></p>
	<p>Attorney: <?php echo element('new_case_register_attorneys', $data); ?></p>
	<p>Comment: <?php echo element('comment', $data); ?></p>
	<p>Requested by: <?php echo element('name', $data); ?></p>
	<p>Email: <?php echo element('email', $data); ?></p>
	<p>- - - - - - - - -</p>
	<p>Sent on: <?php echo date('m/d/Y h:i A'); ?> by MSHC Attorney Portal</p>
	<p>&nbsp;</p>
</div>
</body>
</html>