<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2">

        	<div style="color: #bd0000; font-weight: bold;">Request received after 5:00 PM Monday - Thursday or after 4:30 PM Fridays will be replied to the following business day.</div>

        </div>

<form id="contact_us" name="contact_us" action="<?php echo base_url().MSHC_CONTACT_CONTROLLER_NAME.'/send'; ?>" method="post" enctype="multipart/form-data">
         <fieldset>	

        <div id="case-summary-block">

          <table class="contactTable">

          <tr>

          	<td style="width: 140px"><label for="inquiry_type_id">Inquiry Type:</label></td>

            <td><select id="inquiry_type_id" name="inquiry_type_id">
                    	<option value="billing_information" >Document & Billing Assistance</option>
                        <option value="marketers" >Marketers</option>
                        <option value="representation_status" >Representation Status</option>
                        <option value="scheduling_question" >Scheduling Question</option>
                        <option value="settlement_request" >Settlement Request</option>
                        <option value="web_portal_support" >Technical Support</option>
                        <option value="feature_request" >Feature Request</option>
                    </select>
                    
             </td>

          </tr>

          <tr>

          	<td id="label_marketer" style="display:none;"><label for="inquiry_type_id">Marketer:</label></td>

            <td span id="select_marketer" style="display:none">
            		<select id="marketer_id" name="marketer_id" >
                        <option value="0">Marketing Distribution List</option>
						<?php 
                            if (isset($marketers_list) && is_array($marketers_list)) {
                                    for ($i = 0; $i < count($marketers_list); $i++) {
                                        echo '<option value="'.$marketers_list[$i]['id'].'" >'.$marketers_list[$i]['name'].'</option>';
									}
                            }
                        ?>
                    </select>
            </td>

          </tr>

          <tr>

          	<td><label for="name">Your Name:</label></td>

            <td><input type="text" id="name" name="name" maxlength="100" value="" placeholder="Points Medical" /></td>

          </tr>

          <tr>

          	<td><label for="phone">Your Phone:</label></td>

            <td><input type="text" id="phone" name="phone" maxlength="40" value=""/></td>

          </tr>

          <tr>

          	<td><label for="email">Your Email:</label></td>

            <td><input type="text" id="email" value="" maxlength="100" placeholder="points@medical.com"></td>

          </tr>

          <tr>

          	<td><label for="cc_to">CC To:</label></td>

            <td><textarea id="cc_to" value="" style="height: 67px;"></textarea><br>

            <em>To add multiple email addresses please separate with a comma.</em></td>

          </tr>

          <tr>

          	<td><label for="including_me">Including Me:</label></td>

            <td><input type="checkbox" id="including_me" value="1" /></td>

          </tr>

          <tr>

          	<td><label for="body">Your Inquiry:</label></td>

            <td><textarea id="body" value="" style="height: 67px"></textarea></td>

          </tr>

          <!--tr>

          	<td><label for="attachments">Attachments:</label></td>

            <td>
            
            	<span class="file-wrapper" data="{id: 1}">
                  <input type="file" name="fileupload1" id="fileupload1" />
                  <input type="text" class="file-holder" value="" style="width:250px;" /><span class="input-button-grey file-button">Choose File</span></span>
                <span class="input-button-grey" id="btn_contact_us_add">Add</span>
                
                <div id="fileupload_container"></div>

          </tr-->

          </table>

          <input type="button" value="Submit" class="sqv" style="float: left; margin-right: 20px;" id="btn_contact_us_submit">
          <input type="button" value="Clear" class="sqv" style="float: left" id="btn_contact_us_clear">

          <div class="clear"></div>

        </div>

	</fieldset>
</form>

    </div>

</div>

<script>
var VIGET = VIGET || {};
var btnClearFile = '<span class="input-button-grey file-clear">Delete File</span>';
VIGET.fileInputs = function() {
	$('.file-clear').remove();
	var $this = $(this),
				$val = $this.val(),
				valArray = $val.split('\\'),
				newVal = valArray[valArray.length-1],
				$button = $this.siblings('.file-button'),
				$fakeFile = $this.siblings('.file-holder');
	if(newVal !== '') 
	{
		if($fakeFile.length === 0) {
			$button.after('' + newVal + '');
		} else {
			$fakeFile.val(newVal);
			var id = $this.parents('.file-wrapper').metadata().id;
			$this.parents('.file-wrapper').after(btnClearFile);
			$this.parents('.file-wrapper').next('.file-clear').attr('data','{"id": '+id+'}');
		}
	}
	else
	{
		$fakeFile.val('');
	}
};

$(function() { 
	$('.file-wrapper input[type="file"]').live('change focus click', VIGET.fileInputs);
	
	$('.file-clear').live('click', function() {
		var id = $(this).metadata().id;
		$('#fileupload' + id).val('');
		$('#fileupload' + id).trigger('change');
		$(this).remove();
	});	
});
</script>