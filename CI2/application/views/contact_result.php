
<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr>
            <td align="left" style="color: #cf1b00; font-size: 16px; font-weight:bold">Requests received after 5:00PM Monday - Thursday or after 4:30PM Fridays will be replied to the following business day.</td>
        </tr>
    </table>
</div>

<div class="main-container">
	<script>
	$(function(){
    	<?php 
		if (isset($contact_id)) 
		{
			?>
			display_text_message('Your inquiry was sent successfully.', 320, 150);
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
			$(location).attr('href', '<?php echo base_url().MSHC_CONTACT_CONTROLLER_NAME; ?>');
		});
	});
	</script>
</div>