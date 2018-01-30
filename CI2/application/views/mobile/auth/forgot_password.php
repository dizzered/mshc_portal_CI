<?php

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value' => set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30
);
$email_label = 'Your E-mail: ';
?>
<section class="login">

	<div class="logo-form-border1">

		<div class="logo-form-border2">

        	<?php echo form_open(base_url().$this->uri->uri_string()); ?>
			<?php
           if (isset($message)) {
				echo '<p class="forgot-message">'.$message;
				echo '<br /><br />';
				echo 'Go to <a href="'.base_url().'">Login Page</a></p>';
			} else {
            ?>

            	<h1>Forgot password:</h1>

                <div>

                	<?php echo form_label($email_label, $email['id']); ?>

                    <?php echo form_input($email); ?>
					
					<p class="clear"></p>

                </div>
				
				<div>
					<?php
					if ($users)
					{
						echo form_label('Username:', 'user_id');
						
						$users_dropdown = array();
						$users_dropdown[0] = '--- choose user ---';
						foreach ($users as $user)
						{
							$users_dropdown[$user['id']] = $user['username'];
						}
						echo form_dropdown(
							'user_id',
							$users_dropdown,
							NULL,
							'id="user_id" class="forgot-password-users"'
						);
						?>
						<p class="clear"></p>
						<span style="color:#286dca; line-height: 1;padding-top: 5px;display: block; padding-left:167px; font-size:16px;">More than one user was found. Please select the one you need to restore password for.</span>
						<?php
					}
					?>
				</div>
				
                <div>

                	<?php echo form_submit('submit', 'Send', 'id="send_forgot"'); ?>

                </div>

                <div class="clear"></div>

			<?php 
			echo form_close(); 
			}
			?>

        </div>

    </div>

</section>