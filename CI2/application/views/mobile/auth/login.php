<?php

$login = array(
	'name'	=> 'login',
	'id'	=> 'login-username',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$login_label = 'Username';

$password = array(
	'name'	=> 'password',
	'id'	=> 'login-password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'login-checkbox',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
?>
<section class="login">

	<div class="logo-form-border1">

		<div class="logo-form-border2">

        	<?php echo form_open(base_url().$this->uri->uri_string()); ?>
			<?php
            if (isset($redirect_url)) {
                echo form_hidden('redirect_url', $redirect_url);
            }
            ?>

            	<h1>Sign In:</h1>

                <div>

                	<?php echo form_label($login_label, $login['id']); ?>

                    <?php echo form_input($login); ?>

                </div>

                <div id="forgot-password">

                	<?php echo anchor(base_url().'profile/forgot_password/', 'Forgot password?'); ?>

                </div>

                <div>

                	<?php echo form_label('Password', $password['id']); ?>

                    <?php echo form_password($password); ?>

               	</div>

                <div>

                	<?php echo form_checkbox($remember); ?>
					
					<?php echo form_label('Remember me', $remember['id']); ?>

                </div>

                <div>

                	<?php echo form_submit('submit', 'Sign in', 'id="signin"'); ?>

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

			<?php echo form_close(); ?>

        </div>

    </div>

</section>
<script language="javascript">
	$('#login-username').focus();
</script>