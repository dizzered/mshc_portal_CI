<div class="main-container-header">
</div>

<div class="main-container">
	<script>
	$(function(){
    	<?php 
		if (isset($ps_id)) 
		{
			?>
			display_text_message('Portal settings was save with id <?php echo $ps_id; ?>.', 320, 150);
			<?php
		} 
		else 
		{
			if (isset($error)) 
			{
				?>
				display_text_message('<?php echo $error; ?>.', 320, 150);	
				<?php
			}
		}
		?>
		$('#dialog-general-message').on('dialogclose', function(event, ui) {
			$(location).attr('href', '<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_SETTINGS_NAME; ?>');
		});
	});
	</script>
</div>