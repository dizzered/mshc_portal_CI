<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td>
        <a href="#" class="ico-excel-15">Export to Excel</a><a href="#" class="ico-word-15">Export to Word</a>
        </td>
        <td align="right">
        	<div id="btn-add-new-form" class="tab-right-beige"><p>Add New Forms</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>

<div class="main-container">
	<script>
	$(function(){
    	<?php 
		if (isset($form_id)) 
		{
			?>
			display_text_message('Form was <?php if (isset($new_form)) echo 'uploaded'; else echo 'updated' ?> with id <?php echo $form_id; ?>.', 320, 150);
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
			$(location).attr('href', '<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_FORMS_NAME; ?>');
		});
	});
	</script>
</div>