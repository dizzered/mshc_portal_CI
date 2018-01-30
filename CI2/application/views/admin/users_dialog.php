<div id="dialog-add-new-user">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add New User</h2>
            <table class="dialog-popup-tabs-table">
                <tr>
                <td class="dialog-popup-tabs-round-left">&nbsp;</td>
                <td class="dialog-popup-tabs-round-center">
                <ul id="tabs">
                    <li id="user-general">
                        <span>General</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="user-attorneys">
                        <span>Attorneys</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="user-notifications">
                        <span>Notifications</span>
                        <span>&nbsp;</span>
                    </li>
					<li id="user-highcharges">
                        <span>High Charges</span>
                        <span>&nbsp;</span>
                    </li>
                </ul>
                </td>
                <td>&nbsp;</td>
                <td class="dialog-popup-tabs-round-right">&nbsp;</td>
                </tr>
            </table>
            <div class="tabs-container">
                <form id="user-maintenance">
                <fieldset>
                <input type="hidden" id="view_id" name="view_id" value="" />
                <div class="user-general fnTabs">
                    <div style="float:left;width:305px;">
                        <label for="username">User Name:</label>
                        <input type="text" name="username" id="username" class="text" />
                        <img id="username-ajax-loader" src="/images/ajax_loader.gif" width="16" height="16" style="display:none;" />
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="username-msg" style="color:red;height:15px;">&nbsp;</div>
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" value="" class="text" /><br>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="lastname-msg" style="color:red;height:15px;">&nbsp;</div>
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" id="firstname" value="" class="text" /><br>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="firstname-msg" style="color:red;height:15px;">&nbsp;</div>
                        <label for="role">Role:</label> 
                        <?php
                        $roles = array_unshift_assoc($user_roles,0,'--- Select User Role ---');
                        echo form_dropdown('userrole',$roles,'','id="userrole"');
						?>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="userrole-msg" style="color:red;height:15px;">&nbsp;</div>
						<?php
                        if (count($user_permissions))
                        {
                            foreach($user_permissions as $perm_key => $perm_val)
                            {
                                ?>
                                <div id="<?php echo $perm_key; ?>" class="fnPerms" style="display:none;">
                                -- Additional Roles --<br />
                                <?php
                                foreach($perm_val as $key => $val)
                                {
									$data = array(
										'name'        => 'permissions[]',
										'id'          => $key.'_'.$perm_key,
										'value'       => $key,
										'checked'     => FALSE,
										'class'       => 'fnOnePerm',
									);
                                    echo form_checkbox($data).' '.$val.br();
                                }
                                ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" value="" class="text" />
                        <img id="email-ajax-loader" src="/images/ajax_loader.gif" width="16" height="16" style="display:none;" />
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="email-msg" style="color:red;height:15px;">&nbsp;</div>
                        <label for="comment">Comment:</label>
                        <textarea id="comment" name="comment" style="margin-bottom:10px;"></textarea>
                    </div>
                    <div style="float:left;width:305px;">
                        <div id="last-login-date"></div>
                        <div style="float:left;width:100px;">My Cases Only:</div>
                        <input type="checkbox" name="my_cases_only" id="my_cases_only" value="0">
                        <div style="color:red;height:10px;">&nbsp;</div>
                        <div style="float:left;width:100px;">Lock Out:</div>
                        <input type="checkbox" name="is_locked_out" id="is_locked_out" value="1">
                        <div style="color:red;height:10px;">&nbsp;</div>
                        <div id="password-maintenance">
                        <div style="float:left;width:100px; margin-top:9px;">Password:</div>
                        <input type="button" value="Change" id="btn-password-change" class="input-button-grey" /> 
                        <input type="button" value="Reset" id="btn-password-reset" class="input-button-grey" />
                        </div>
                    </div>
                </div>
                
                <div class="user-attorneys fnTabs">
                    <div id="linked_firms_title" style="color:#5b4132;font-weight:bold; text-align:center;">Linked Firms and Attorneys</div>
                    <div id="firms_title" style="color:#5b4132;font-weight:bold; text-align:center;">Available for linking</div>
                    <div style="clear:both;"></div>
                    <?php
					$primary_key = NULL;
					$primary_firm_id = NULL;
                    foreach($user_linked_firms_attorneys as $key => $one)
                    {
                        if ($one['is_primary'] != NULL) 
						{
                            $primary_key = $key;
                            $primary_firm_id = $one['legal_firm_id'];
							echo '
							<script>
							var userPrimaryFirm = {firm_id: '.$one['legal_firm_id'].'};
							userPrimaryFirm.attys = new Array;
							</script>
							';
                            break;
                        }
                    }
                    foreach($user_linked_firms_attorneys as $key => $one)
                    {
                        if ($one['is_primary'] != NULL) 
						{
							echo '
							<script>
							userPrimaryFirm.attys.push('.$one['legal_atty_id'].');
							</script>
							';
						}
					}
                    ?>
                    <div id="linked-firms-new">
                    <?php
					
					if (!is_null($primary_key))
					{
						echo '
						<div class="fnAppendedPrimaries firm_'.$primary_firm_id.'"><input type="hidden" id="unlink_firm_'.$primary_firm_id.'" name="unlink_firm_'.$primary_firm_id.'" value="'.$primary_firm_id.'"><div class="fnFirmMaintenanceBox"><div class="fnUserRemoveFirm" style="float:left;"><img src="/images/remove-grey.png" style="border-right:1px solid #d5d5d5;" alt="Unlink Firm" title="Unlink Firm"></div></div><div class="folder users-dialog-firm-name">'.$user_linked_firms_attorneys[$primary_key]['name'].'</div><div style="clear:both;"><input type="radio" name="is_primary" value="'.$primary_firm_id.'" id="is_primary"> Primary <input type="checkbox" name="all_attorneys_'.$primary_firm_id.'" value="1" id="all_attorneys_'.$primary_firm_id.'" data="{firm_id: '.$primary_firm_id.'}" checked="checked"> All Attorneys</div></div>';					
					}
                    ?>
                    </div>
                    <div id="linked-firms"></div>
                    <div id="firms_attorneys_list">
                    <?php
                    $firms_ary = array();
					$data_str = '<ul id="firms-attorneys-tree" class="filetree">';
                    foreach($dialog_firms_attorneys as $firm_id => $firm)
                    {
						if (!in_array($firm_id,$firms_ary))
                        {
                            $firms_ary[] = $firm_id;
							$data_str .= '<li id="scroll_firm_'.$firm_id.'">
							<input type="hidden" id="firm_'.$firm_id.'" name="firm_'.$firm_id.'" value="'.$firm_id.'" />
							<input type="hidden" id="name_'.$firm_id.'" name="name_'.$firm_id.'" value="'.$firm['firm_name'].'" />
							<div class="fnFirmMaintenanceBox">
							<div class="fnUserLinkFirm" style="float:left;">
							<img src="/images/add-grey.png" style="border-right:1px solid #d5d5d5;" alt="Link Firm" title="Link Firm"></div>
							</div>
							<div class="folder fnTreeName">'.$firm['firm_name'].'</div>
							<ul>';
						}
                        foreach($firm['firm_attorneys'] as $atty_id => $atty)
                        {
							if ($atty_id) {
								if ($firm_id == $primary_firm_id) {
                                    $checked = ' checked="checked"';
                                } else {
                                    $checked = '';
                                }
								$atty_name = $atty['last_name'];
								if (!empty($atty['first_name']) && !empty($atty['last_name'])) $atty_name .= ', ';
								$atty_name .= $atty['first_name'];
								$data_str .= '<li><span class="">
								<input id="attorney_'.$atty_id.'" name="attorneys[]" data="{firm_id: '.$firm_id.'}" type="checkbox"'.$checked.' value="'.$atty_id.'"> '.$atty_name.'
								<input type="hidden" id="atty_'.$atty_id.'_firm_'.$firm_id.'" name="atty_'.$atty_id.'_firm_'.$firm_id.'" value="'.$firm_id.'" />
								<input type="hidden" id="atty_'.$atty_id.'_name_'.$firm_id.'" name="atty_'.$atty_id.'_name_'.$firm_id.'" value="'.$firm['firm_name'].'" />
								</span></li>';
							}
                        }
						$data_str .= '</ul></li>';
                    }
                    $data_str .= '</ul>';
                    echo $data_str;
                    ?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                
                <div class="user-notifications fnTabs">
                    <div style="text-align:right;width:580px; margin-bottom:10px;">
                        <input type="button" value="Select All" id="user-notif-select-all" class="input-button-grey"> 
                        <input type="button" value="Unselect All" id="user-notif-unselect-all" class="input-button-grey">
                    </div>
                    <div style="clear:both;"></div>
                    <div style="float:left;width:225px; padding-right:50px;">
                        <div style="width:160px; float:left;margin-top:3px;">Missed Appointments:</div>
                        <input type="checkbox" name="notifications[]" value="missed_appointments_notified">
                        <div style="clear:both;height:25px;"></div>
                        <div style="width:160px; float:left;margin-top:3px;">Patient Case Discharge:</div>
                        <input type="checkbox" name="notifications[]" value="case_discharge_notified">
						<div style="clear:both;height:25px;"></div>
						<em><strong>Important</strong>: Please open High Charges to sign up for High Charges notifications.</em> </div>
                  <div style="float:left;width:335px;">
                    	<div class="new-docs-notifications-container">
                        <strong>New Documents</strong><br />
                        <div style="width:170px; float:left;margin-top:3px;">Medical Reports:</div>
                        <input type="checkbox" name="notifications[]" value="medical_report_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">PT Note:</div>
                        <input type="checkbox" name="notifications[]" value="pt_note_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">Outside Medical Record:</div>
                        <input type="checkbox" name="notifications[]" value="outside_medical_record_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">Consult:</div>
                        <input type="checkbox" name="notifications[]" value="consult_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">PT-BWR Referral:</div>
                        <input type="checkbox" name="notifications[]" value="ptbwr_referral_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">Disability:</div>
                        <input type="checkbox" name="notifications[]" value="disability_notified">
                        <div style="clear:both;"></div>
                        <div style="width:170px; float:left;margin-top:3px;">Pharmacy:</div>
                        <input type="checkbox" name="notifications[]" value="pharmacy_notified">
                        <div style="clear:both;"></div>
                        </div>
                    </div>
                </div>
				
				<div class="user-highcharges fnTabs">
					<div style="width:180px; float:left;margin-top:3px;">High Charges Notifications:</div>
					<input type="checkbox" name="high_charges" id="high_charges" value="1">
					<div style="clear:both;height:25px;"></div>
					<div class="new-docs-notifications-container">
						Enter High Charge limits:
						<div style="clear:both;height:15px;"></div>
						<label for="high_charge_lower">Level 1:</label>
						<input type="text" name="high_charge_level1" id="high_charge_level1" value="0" class="text" />
						<div style="clear:both;"></div>
						<div id="high_charge_level1-msg" style="color:red; height:20px; padding-left:80px; visibility:hidden;">&nbsp;</div>
						<div style="clear:both;"></div>
						<label for="high_charge_middle">Level 2:</label>
						<input type="text" name="high_charge_level2" id="high_charge_level2" value="0" class="text" />
						<div style="clear:both;"></div>
						<div id="high_charge_level2-msg" style="color:red; height:20px; padding-left:80px; visibility:hidden;">&nbsp;</div>
						<div style="clear:both;"></div>
						<label for="high_charge_middle">Level 3:</label>
						<input type="text" name="high_charge_level3" id="high_charge_level3" value="0" class="text" />
						<div style="clear:both;"></div>
						<div id="high_charge_level3-msg" style="color:red; height:20px; padding-left:80px; visibility:hidden;">&nbsp;</div>
						<div style="clear:both;"></div>
					</div>
				</div>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var linkedFirms = $('.fnAppendedPrimaries');
</script>