<div id="practice-add-grps-box" class="practice-add-grps">
    <div style="padding:6px 8px; text-align:center;"><strong>Add Groups</strong></div>
    <hr style="padding:0;margin:6px 0 0 0;border-bottom:1px solid #d2d2d2;">
    <div id="fin-grp-avail">
    <?php
    if (count($fin_grps_avail))
    {
        for($i = 0; $i < count($fin_grps_avail); $i++)
        {
            ?>            
            <div style="padding:6px 0 6px 8px;text-align:left;">
                <div style="float:left; width:97px;">
                <p style="color:#6997b1;"><strong><?php echo $fin_grps_avail[$i]['name']; ?></strong></p>
                </div>
                <div style="float:left;padding-top:2px;" class="fnAddFinGroup" data="{grp_id: <?php  echo $fin_grps_avail[$i]['id']; ?>}">
                <img src="/images/add-plus.png" width="10" height="9" />
                </div>
                <div style="clear:both;height:5px;"></div>
            </div>
            <?php
        }
    }
    ?>
    </div>
    <div style="padding:6px 0 6px 8px;text-align:left;">
        <div style="float:left; width:97px;">
        <p style="color:#6997b1;"><em><strong>Other</strong></em></p>
        <input type="text" value="" id="fin-grp-other" style="width:85px;"  />
        </div>
        <div style="float:left;padding-top:18px;" id="btn-add-group-other">
        <img src="/images/add-plus.png" width="10" height="9" /></div>
        <div style="clear:both"></div>
    </div>
</div>

<script>
var finGrpsAddBox = $('#practice-add-grps-box');
$('#practice-add-grps-box').remove();
</script>

<div id="dialog-practices" title="">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add New Practice</h2>
            <table class="dialog-popup-tabs-table">
                <tr>
                <td class="dialog-popup-tabs-round-left">&nbsp;</td>
                <td class="dialog-popup-tabs-round-center">
                <ul id="tabs">
                    <li id="practice-general">
                        <span>General</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="practice-locations">
                        <span>Locations</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="practice-financial">
                        <span>Financial</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="practice-reasons">
                        <span>Appt Reasons</span>
                        <span>&nbsp;</span>
                    </li>
                </ul>
                </td>
                <td>&nbsp;</td>
                <td class="dialog-popup-tabs-round-right">&nbsp;</td>
                </tr>
            </table>
            <div class="tabs-container">
                <form id="practice-maintenance">
                <fieldset>
                <input type="hidden" id="prictice_view_id" name="prictice_view_id" value="" />
                <input type="hidden" id="prictice_client_id" name="prictice_client_id" value="<?php echo $client_id; ?>" />
                <div class="practice-general fnTabs">
                    <div>
                        <label for="last_name">Practice Name:</label>
                        <input type="text" name="practice-name" id="practice-name" value="" class="text" /><br>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="practice-name-msg" style="color:red;height:15px;">&nbsp;</div>
                        <div style="border:1px solid #cccccc;background-color:#eaeaea;padding:10px;width:312px;">
                        	<div style="color:#454545;font-weight:bold;margin-bottom:10px;">Live Database</div>
                            <label for="ext-db" style="width:120px;">External Database:</label>
                            <select id="ext-db" style="width:160px;">
                            	<option value="0">-- not set --</option>
								<?php
                                foreach ($ext_dbs as $ext_db_id => $ext_db_name)
                                {
                                    echo '<option value="'.$ext_db_id.'">'.$ext_db_name.'</option>';
                                }
                                ?>                                
                            </select>
                        	<div style="color:red;height:10px;">&nbsp;</div>
                        	<label for="live_practice_id" style="width:120px;">Practice ID:</label>
                        	<input type="text" name="live_practice_id" id="live_practice_id" value="" style="width:20px;" />
                        </div>
                        <div style="color:red;height:5px;">&nbsp;</div>
                        <div style="border:1px solid #cccccc;background-color:#eaeaea;padding:10px;width:312px;">
                        	<div style="color:#454545;font-weight:bold;margin-bottom:10px;color:#003eff;">Rundown Database</div>
                            <label for="rundown-db2" style="width:120px;color:#023eff;">External Database:</label>
                            <select id="rundown-db2" style="width:160px;">
                            	<option value="0">-- not set --</option>
								<?php
                                foreach ($ext_dbs as $ext_db_id => $ext_db_name)
                                {
                                    echo '<option value="'.$ext_db_id.'">'.$ext_db_name.'</option>';
                                }
                                ?>                                
                            </select>
                        	<div style="color:red;height:10px;">&nbsp;</div>
                        	<label for="rundown_practice_id2" style="width:120px;color:#023eff;">Practice ID:</label>
                        	<input type="text" name="rundown_practice_id2" id="rundown_practice_id2" value="" style="width:20px;" />
                        </div>
                        <div style="color:red;height:5px;">&nbsp;</div>
                        <div style="border:1px solid #cccccc;background-color:#eaeaea;padding:10px;width:312px;">
                        	<div style="color:#454545;font-weight:bold;margin-bottom:10px;color:#003eff;">Rundown Database</div>
                            <label for="rundown-db3" style="width:120px;color:#023eff;">External Database:</label>
                            <select id="rundown-db3" style="width:160px;">
                            	<option value="0">-- not set --</option>
								<?php
                                foreach ($ext_dbs as $ext_db_id => $ext_db_name)
                                {
                                    echo '<option value="'.$ext_db_id.'">'.$ext_db_name.'</option>';
                                }
                                ?>                                
                            </select>
                        	<div style="color:red;height:10px;">&nbsp;</div>
                        	<label for="rundown_practice_id3" style="width:120px;color:#023eff;">Practice ID:</label>
                        	<input type="text" name="rundown_practice_id3" id="rundown_practice_id3" value="" style="width:20px;" />
                        </div>
                    </div>
                </div>
                
                <div class="practice-locations fnTabs">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:610px;">
                    <tr>
                    	<td><div style="color:#5b4132;text-align:center;">Available locations</div></td>
                        <td>&nbsp;</td>
                        <td><div style="color:#5b4132;text-align:center;">Selected locations</div></td>
                    </tr>
                    <tr>
                    	<td width="210" align="left">
                        <?php
						if (is_array($locs_avail) && count($locs_avail))
						{
							?>
                            <select name="practice-locs-avail" id="practice-locs-avail" style="width:210px;height:225px;" multiple="multiple">
							<?php
							for ($i = 0; $i < count($locs_avail); ++$i)
							{
								echo '<option value="'.$locs_avail[$i]['display_name'].'" data="{\'map_id\': \'\'}">'.$locs_avail[$i]['display_name'].'</option>';
							}
							?>
                            </select>
							<?php
						}					
                        ?>
                        </td>
                      	<td width="155" align="center">
                        <input type="button" id="practice-loc-add" value="Add" style="width:105px;" disabled /><br>
                        <?php
						if (count($locs_avail)) $all_disabled = '';
						else $all_disabled = ' disabled';
                        ?>
                        <input type="button" id="practice-loc-add-all" value="Add All" style="width:105px;"<?php echo $all_disabled; ?> /><br>
                        <input type="button" id="practice-loc-remove" value="Remove" style="width:105px;" disabled /><br>
                        <input type="button" id="practice-loc-remove-all" value="Remove All" style="width:105px;" disabled />
                        </td>
                      <td width="210" align="left">
                        <?php
						$selected_opt = 'id="practice-locs-selected" style="width:210px;height:225px;"';
						echo form_multiselect('practice-locs-selected', array(), NULL, $selected_opt);
                        ?>
                      </td>
                    </tr>
                    </table>
                </div>
                
                <div class="practice-financial fnTabs">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:675px;">
                    <tr>
                    <td align="left" class="dark-bg-container" style="width:180px;">
                        <div>Available Financial Classes</div>
                        <?php
                        if (count($fin_classes_avail))
                        {
                            ?>
                            <ul id="fin-avail-classes">
                            <?php
                            for ($i = 0; $i < count($fin_classes_avail); ++$i)
                            {
                                ?>
                                <li class="fnDraggable" data="{fin_class_id: <?php echo $fin_classes_avail[$i]['id']; ?>}" style="display:list-item;">
								<p><?php echo $fin_classes_avail[$i]['name']; ?></p></li>
                                <?php
                            }
                            ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </td>
                    <td style="width:10px;">&nbsp;</td>
                    <td id="fin-grps-box" class="dark-bg-container" style="width:180px;" align="left">
                      <div id="fin-grps-add-box" class="fin-grps-add"><img src="/images/add-plus.png" width="12" height="11" /></div>
                      <div>Financial Class Groups</div>
                      <div style="clear:both;border:none;padding:0;margin:0;"></div>
                    </td>
                    <td style="width:20px;">&nbsp;</td>
                    <td align="left">
                    	<label for="split_charges" style="width:100px;">Split Charges:</label> <input type="checkbox" value="1" id="split_charges" />
                        <div style="height:15px"></div>
                        <label for="split_mediacal_group" style="width:100px;">Medical Group:</label>
                        <select id="split_mediacal_group" style="width:140px;" disabled="disabled">
                        	<option value="0">-- not set --</option>
                        </select>
                        <div style="height:15px"></div>
                        <label for="split_surgery_group" style="width:100px;">Surgery Group:</label>
                        <select id="split_surgery_group" style="width:140px;" disabled="disabled">
                        	<option value="0">-- not set --</option>
                        </select>
                        <div style="height:15px"></div>
                        <label for="split_pt_chiro_group" style="width:100px;">PT/Chiro Group:</label>
                        <select id="split_pt_chiro_group" style="width:140px;" disabled="disabled">
                        	<option value="0">-- not set --</option>
                        </select>
                    </td>
                    </tr>
                    </table>
                    <div style="height:10px;"></div>
                  	<em>Use mouse to drag and drop financial classes between groups.</em>
                </div>
                
                <div class="practice-reasons fnTabs">
                	<input type="button" value="Add New Record" id="btn-add-appt-reason" class="input-button-grey" />
                    <div id="appt-reason-no" style="margin-top:15px;"><em>No appt reason mapped for this practice.</em></div>
                    <table border="0" cellpadding="0" cellspacing="0" style="width:680px;margin-top:15px;" id="appt-reasons-table">
                    </table>
                </div>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="dialog-practices-appt-reason">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add New Record</h2>
            <label for="ext_dbs">External Database</label>
			<input type="hidden" name="appt_map_id" id="appt_map_id" value="0" />
            <select id="ext_dbs">
                <option value="0">-- choose database --</option>
                <?php
                foreach ($ext_dbs as $ext_db_id => $ext_db_name)
				{
					echo '<option value="'.$ext_db_id.'">'.$ext_db_name.'</option>';
				}
				?>
            </select>
            <div style="clear:both;height:5px"></div>
            <label for="system_code">System Code</label>
			<?php
			$appt_rsns_array = array();
			$appt_rsns_array[] = '-- choose system code --';
			if ($appt_reasosns)
			{
				foreach ($appt_reasosns as $external_id => $pms_reason)
				{
					$appt_rsns_array[$pms_reason['PMSReason']] = $pms_reason['PMSReason'];
				}
			}
			echo form_dropdown('system_code', $appt_rsns_array, NULL, 'id="system_code"');
			?>
            <div style="clear:both;height:5px"></div>
            <label for="portal_reason">Portal Reason</label>
            <input type="text" value="" id="portal_reason" name="portal_reason" />
        </div>
    </div>
</div>