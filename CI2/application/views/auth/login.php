<?php

$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'style' => 'height: 18px; font-size:16px; padding: 5px; width: 190px',
	'tabindex' => 1
);
$login_label = 'Username';

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'style' => 'height: 18px; font-size:16px; padding: 5px; width: 190px',
	'tabindex' => 2
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'tabindex' => 3
);
?>

<div class="login-page-words">
<p>Multi-Specialty HealthCare's Attorney Portal provides attorneys with seamless and immediate access to all necessary medical documentation for each of their Personal Injury and Workers' Compensation cases.</p>
<p>To log in, enter your user name and password then click on the sign in button below. </p>
<p><em><strong>The password field is case sensitive</strong></em></p>
</div>

<div class="login-form-container">
<div class="login-form-wrapper">
<div class="login-form-inner">
<h2 style="font-size: 28px; margin-top:0; margin-bottom:10px;">Sign In:</h2>
<?php echo form_open(base_url().$this->uri->uri_string()); ?>
<?php
if (isset($redirect_url)) {
	echo form_hidden('redirect_url', $redirect_url);
}
?>
<table>
	<tr style="line-height: 30px">
		<td style="font-size:15px; text-align:left;" width="100"><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?><span style="color:red"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']]) ? '<br />'.$errors[$login['name']]:''; ?></span></td>
	</tr>
    <tr style="line-height: 30px">
    	<td colspan="2" style="font-size:15px; text-align:right;">
        	<?php echo anchor(base_url().'profile/forgot_password/', 'Forgot password?'); ?>
        </td>
    </tr>
	<tr style="line-height: 30px">
		<td style="font-size:15px; text-align:left;"><?php echo form_label('Password', $password['id']); ?></td>
		<td><?php echo form_password($password); ?><span style="color:red"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span></td>
	</tr>
	<tr style="line-height: 30px">
		<td colspan="3" style="font-size:15px; text-align:left; padding-top:10px;">
			<?php echo form_checkbox($remember); ?>
			<?php echo form_label('Remember me', $remember['id']); ?>
		</td>
	</tr>
    
    <tr>
    	<td colspan="2" style="text-align:center;"><?php echo form_submit('signin', 'Sign in', 'id="signin"'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>
</div>

<div class="first-time-user">
<p style="margin-bottom:0; margin-top:20px;font-size:14px;">First time user?</p>
<p style="margin-bottom:0; margin-top:1px;font-size:14px;"><a href="http://mshclegal.com/portal">Click here</a> to sign up today!</p>
</div>
<div class="ssl-img">
	<span id="siteseal">
	<script type="text/javascript" src="https://seal.starfieldtech.com/getSeal?sealID=zM6V0qEVD3vWOidydoLSaINE9CphFQZiWhzWZvZaRd2YsCKSUI9H"></script>
	</span>
</div>

<div class="clear"></div>
</div>

</div>

<script language="javascript">
$(function() {
	$('#login').focus();
});
</script>