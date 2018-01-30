<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2">

        	<div style="color: #bd0000; font-weight: bold;">Request received after 5:00 PM Monday - Thursday or after 4:30 PM Fridays will be replied to the following business day.</div>

        </div>

	<script>
        $(function(){
            <?php 
            if (isset($contact_id)) 
            {
                ?>
                display_text_message('Your inquiry is send with number <?php echo $contact_id; ?>.', 320, 150);
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

</div>