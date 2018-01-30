<?php

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value' => set_value('email'),
	'maxlength'	=> 80,
	'style' => 'height: 18px; font-size:16px; padding: 5px; width: 190px',
);
$email_label = 'Your E-mail: ';
?>

<div class="login-form-container">
<div class="login-form-wrapper">
<div class="login-form-inner">
<h2 style="font-size: 28px; margin-top:0; margin-bottom:10px;">Forgot Password:</h2>
<?php
if (isset($message)) {
	echo $message;
	echo '<br /><br />';
	echo 'Go to <a href="'.base_url().'">Login Page</a>';
} else {
?>
<?php echo form_open(base_url().$this->uri->uri_string()); ?>
<table>
	<tr style="line-height: 30px">
		<td style="font-size:15px; text-align:left;" width="100"><?php echo form_label($email_label, $email['id']); ?></td>
		<td>
			<?php echo form_input($email); ?>
			<span style="color:red; line-height: 1;padding-top: 5px;display: block;"><?php echo form_error($email['name']); ?><?php echo isset($errors)?$errors:''; ?></span>
		</td>
	</tr>
	
	<?php
	if ($users)
	{
		?>
		<tr style="line-height: 30px;">
			<td style="font-size:15px; text-align:left; padding-top:15px;" width="100">Username:</td>
			<td style="padding-top:15px;">
				<?php
				$users_dropdown = array();
				$users_dropdown[0] = '--- choose user ---';
				foreach ($users as $user)
				{
					$users_dropdown[$user['id']] = $user['username'];
				}
				echo '<label class="forgot-password-users-arrows">'.form_dropdown(
					'user_id',
					$users_dropdown,
					NULL,
					'id="user_id" class="forgot-password-users"'
				).'</label>';
				?>
				<span style="color:#286dca; line-height: 1;padding-top: 5px;display: block;">More than one user was found. Please select the one you need to restore password for.</span>
			</td>
		</tr>
		<?php
	}
	?>
	
</table>
<br /><br />
<div style="text-align:center;">
<?php echo form_submit('submit', 'Send', 'id="send_forgot"'); ?>
</div>
<?php 
	echo form_close();
}
?>
</div>
</div>
</div>