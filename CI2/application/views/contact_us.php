<div class="main-container-header">
    <table border="0" class="main-container-header-tab">
        <tr>
            <td align="left" style="color: #cf1b00; font-size: 16px; font-weight:bold">Requests received after 5:00PM
                Monday - Thursday or after 4:30PM Fridays will be replied to the following business day.
            </td>
        </tr>
    </table>
</div>

<div class="main-container" style="padding-bottom:20px;">
    <form id="contact_us" name="contact_us" action="<?php echo base_url() . MSHC_CONTACT_CONTROLLER_NAME . '/send'; ?>"
          method="post" enctype="multipart/form-data">
        <fieldset>
            <input type="hidden" name="case_contact_account" id="case_contact_account" value="">
            <input type="hidden" name="case_contact_class" id="case_contact_class" value="">
            <input type="hidden" name="case_contact_doa" id="case_contact_doa" value="">

            <table class="contact-form-table" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="inquiry_type_id">Inquiry
                            Type:</label></td>
                    <td width="575" align="left">
                        <select id="inquiry_type_id" name="inquiry_type_id">
                            <option value="billing_information">Document & Billing Assistance</option>
                            <option value="marketers">Marketers</option>
                            <option value="representation_status">Representation Status</option>
                            <option value="scheduling_question">Scheduling Question</option>
                            <option value="settlement_request">Settlement Request</option>
                            <option value="web_portal_support">Technical Support</option>
                            <option value="feature_request">Feature Request</option>
                        </select>

                        <span id="label_marketer" style="display:none; margin-right:25px; margin-left:25px;"><label for="marketer_id">Marketer:</label> </span>
                        <span id="select_marketer" style="display:none">
                            <select id="marketer_id" name="marketer_id" style="width:252px;">
                            <option value="0">Marketing Distribution List</option>
                                <?php
                                if (isset($marketers_list) && is_array($marketers_list)) {
                                    for ($i = 0; $i < count($marketers_list); $i++) {
                                        echo '<option value="' . $marketers_list[$i]['id'] . '" >' . $marketers_list[$i]['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </span>
                    </td>
                    <td width="190" align="left">

                    </td>
                    <td align="left">
                    </td>
                </tr>
                <?php if (isset($isCasesSearch) && $isCasesSearch): ?>
                    <tr>
                        <td width="100" align="left" class="contact-form-label"><label for="name">Client Search:</label></td>
                        <td width="575" align="left">
                            <div class="pull-left" style="margin-right: 10px; width: 240px;">
                                <label class="block" for="contact_client_cases_name">Name</label>
                                <input type="text" name="contact_client_cases_name" id="contact_client_cases_name" style="width: 100%;" placeholder="Search by patient name...">
                            </div>
                            <div class="pull-left" style="margin-right: 10px; width: 240px;">
                                <label class="block" for="contact_client_cases_account">Account</label>
                                <input type="text" name="contact_client_cases_account" id="contact_client_cases_account" style="width: 100%" placeholder="Search by account #...">
                            </div>
                            <div class="pull-left" style="margin-top: 20px;">
                                <span class="input-button-grey" id="btn_contact_cases_search">Search</span>
                            </div>
                            <div class="clear"></div>
                            <select name="contact_cases_list" id="contact_cases_list" style="display: none; width: 560px; margin-top: 10px;"></select>
                        </td>
                        <td width="190" align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="name">Your Name:</label></td>
                    <td width="575" align="left">
                        <?php
                        $name = '';
                        if ($this->_user['first_name']) $name .= $this->_user['first_name'] . ' ';
                        if ($this->_user['last_name']) $name .= $this->_user['last_name'];
                        ?>
                        <input type="text" id="name" name="name" maxlength="100" value="<?php echo $name; ?>"/>
                    </td>
                    <td width="190" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="phone">Your Phone:</label></td>
                    <td width="575" align="left"><input type="text" id="phone" name="phone" maxlength="40" value=""/>
                    </td>
                    <td width="190" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="email">Your Email:</label></td>
                    <td width="575" align="left">
                        <input type="text" id="email" name="email" maxlength="100"
                               value="<?php echo isset($this->_user['email']) ? $this->_user['email'] : ''; ?>"/></td>
                    <td width="190" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="cc_to">CC To:</label></td>
                    <td width="575" align="left"><textarea id="cc_to" name="cc_to" cols="100" rows="5"></textarea></td>
                    <td width="190" align="left"><em>To add multipple email addresses please separate with a comma.</em>
                    </td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left"><label for="including_me">Including me:</label></td>
                    <td width="575" align="left"><input type="checkbox" name="including_me" id="including_me" value="1">
                    </td>
                    <td width="190" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left" class="contact-form-label"><label for="body">Your Inquiry:</label></td>
                    <td width="575" align="left"><textarea id="body" name="body" cols="100" rows="10"></textarea></td>
                    <td width="190" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100" align="left"><label>Attachments:</label> <br/><em>(10 Maximum upload files)</em>
                    </td>
                    <td width="575" align="left">
          <span class="file-wrapper" data="{id: 1}">
          <input type="file" name="fileupload1" id="fileupload1"/>
          <input type="text" class="file-holder" value="" style="width:250px;"/><span
                  class="input-button-grey file-button">Choose File</span></span>
                        <span class="input-button-grey" id="btn_contact_us_add">Add</span>
                        <div id="fileupload_container"></div>
                    </td>
                    <td width="190" align="left">
                    </td>
                    <td align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" align="left" style="padding:0;">
                        <input id="btn_contact_us_submit" type="button" name="btn_contact_us_submit" value="Submit"
                               class="contact-form-handler-btn ui-button" style="width:80px;"/>
                        <input type="button" id="btn_contact_us_clear" name="btn_contact_us_clear" value="Clear"
                               class="contact-form-handler-btn ui-button"/>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<script>
    var VIGET = VIGET || {};
    var btnClearFile = '<span class="input-button-grey file-clear">Delete File</span>';
    VIGET.fileInputs = function () {
        //$('.file-clear').remove();
        var $this = $(this),
            $val = $this.val(),
            valArray = $val.split('\\'),
            newVal = valArray[valArray.length - 1],
            $button = $this.siblings('.file-button'),
            $fakeFile = $this.siblings('.file-holder');
        $this.parents('.file-wrapper').next('.file-clear').remove();
        if (newVal !== '') {
            if ($fakeFile.length === 0) {
                $button.after('' + newVal + '');
            } else {
                $fakeFile.val(newVal);
                var id = $this.parents('.file-wrapper').metadata().id;
                $this.parents('.file-wrapper').after(btnClearFile);
                $this.parents('.file-wrapper').next('.file-clear').attr('data', '{"id": ' + id + '}');
            }
        }
        else {
            $fakeFile.val('');
        }
    };

    $(function () {
        $('.file-wrapper input[type="file"]').live('change focus click', VIGET.fileInputs);

        $('.file-clear').live('click', function () {
            var id = $(this).metadata().id;
            $('#fileupload' + id).replaceWith($('#fileupload' + id).val('').clone(true));
            $('#fileupload' + id).trigger('change');
            $('#fileupload' + id).parents('.file-wrapper').next('.file-clear').remove();
        });
    });
</script>