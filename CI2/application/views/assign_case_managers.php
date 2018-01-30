<script language="javascript">
	var cases_manager_type = 'unassigned';
	var cases_account = new Array();
	var cases_account_index = -1;
</script>

<div class="main-container-header">
<table width="100%" border="0" class="main-container-header-tab">
  <tr>
    <td ></td>
  </tr>
</table>
</div>

<div style="border: 1px solid #cacaca; box-shadow: -2px 0px #cacaca inset; border-top: 0; border-bottom: 0; padding-top: 17px">
        <table class="assign-case-managers-tabs-table">
            <tr>
                <td class="assign-case-managers-tabs-round-left">&nbsp;</td>
                <td class="assign-case-managers-tabs-round-center">
                    <ul id="tabs_assigned" style="margin-left: -22px">
                        <li id="li-unassigned-search" class="tabs-state-active">
                            <span><b>Unassigned</b></span>
                            <span>&nbsp;</span>
                        </li>
                        <li id="li-assigned-search">
                            <span><b>&nbsp;Assigned</b></span>
                            <span>&nbsp;</span>
                        </li>
                        <li id="li-all-search">
                            <span><b>All</b></span>
                            <span>&nbsp;</span>
                        </li>
                    </ul>
               </td>
                <td>&nbsp;</td>
                <td class="assign-case-managers-tabs-round-right">&nbsp;</td>
            </tr>
        </table>
</div>
<div class="main-container">
<div id="cases_managers_search" style="background-color:#e4ecef; border: 1px solid #d8d8d8; margin-top: -15px">
      <table class="cases_managers_search_table" style="margin: 15px; line-height: 24px">
        <tr>
          <td align="left" width="100px">Select from: </td>
          <td align="left"><input name="cases_params" type="radio" value="all" checked="checked" style="margin-left: 5px"/>  All cases (500 max)</td>
          <td align="left"><input name="cases_params" type="radio" value="dos_60" />  DOS within last 60 days</td>
          <td align="left"><input name="cases_params" type="radio" value="doa_60" />  DOA within last 60 days</td>
          <td align="left"><input name="cases_params" type="radio" value="dos_30" />  DOS within last 30 days</td>
          <td align="left"><input name="cases_params" type="radio" value="doa_30" />  DOA within last 30 days</td>
        </tr>
        <tr>
            <td align="left">Filter by name: </td>
            <td align="left" colspan="5">
             	<input type="text" id="filter_by_name" name="filter_by_name" value="" style="width: 330px"/> <input type="button" id="cases_managers_search_apply" value="Apply" class="input-button-grey"/></td>
          </tr>
     </table>
</div>
<script>
tableID = '';
tableName = 'assigned-cases';
dbName = 'assigned-cases';
</script>
	
    <table style="margin-top: 10px">
    	<tr>
        	<td align="left">
                <div id="btn_cases_assigned_div" style="text-align:left;">
                    <input id="btn_cases_assigned" type="button" value="Assign" disabled="disabled" class="ui-button opacity25" style="color:white" />
                </div>
                <div id="btn_cases_unassigned_div" style="text-align:left; display:none">
                    <input id="btn_cases_unassigned" type="button" value="Unassign" disabled="disabled" class="ui-button opacity25" style="color:white" />
                </div>
    		</td>
            <td align="right">
    		    <div id="count_cases_div" align="right" style="float:right; font-size:14px; margin-top: 0 5px"></div>
            </td>
        </tr>
    </table>
    
    <table style="margin-top: 10px">
    	<tr>
        	<td style="width: 200px; text-align: left;  border: 1px solid #d8d8d8; background-color:#f7f7f7;">
                <div id="firm_attorneys" style="width: 200px; ">
				<?php 
				if (isset($case_managers)) 
				{
					echo '<ul id="case_managers_tree" class="filetree">';
					foreach ($case_managers as $mngr_key => $manager)
					{
						echo '<li><span class="caseManager" data="{user_id: '.$manager['user_id'].'}">'.
						$manager['user_last_name'].', '.$manager['user_first_name'].'</span>';
						
						if (isset($manager['user_firms']) && is_array($manager['user_firms']))
						{
							foreach ($manager['user_firms'] as $firm_key => $firm)
							{
								echo '<ul id="firm_attorney_tree_'.$mngr_key.'_'.$firm_key.'" class="filetree">
								<li><div class="assigned_case_manager_tree" data="{user_id: '.$manager['user_id'].', firm_id: '.$firm_key.'}">'.
								$firm['firm_name'].'</div>';
								if (isset($firm['attys']) && is_array($firm['attys']))
								{
									echo '<ul id="attorneys_tree_'.$mngr_key.'_'.$firm_key.'" class="filetree">';
									foreach ($firm['attys'] as $atty_key => $atty)
									{
										echo '<li><div class="assigned_case_manager_tree" data="{user_id: '.
										$manager['user_id'].', attorney_id: '.$atty_key.'}">'.
										$atty['atty_last_name'].', '.$atty['atty_first_name'].'</div></li>';
									}
									echo '</ul>';
								}
								echo '</li></ul>';
							}
						}
						echo '</li>';
					}
					echo '</ul>';
					/*for ($i = 0; $i < count($case_managers); $i++) {
						echo '<li><span>'.$case_managers[$i]['last_name'].', '.$case_managers[$i]['first_name'].'</span>';
						$firm_id = 0;
						//print_r($case_managers[$i]['firm_attorneys']);
						if (isset($case_managers[$i]['firm_attorneys']) && is_array($case_managers[$i]['firm_attorneys'])) {
							for ($k = 0; $k < count($case_managers[$i]['firm_attorneys']); $k++) {
								if ($firm_id != $case_managers[$i]['firm_attorneys'][$k]['legal_firm_id']) {
									if ($firm_id != 0) {
										echo '</ul></li></ul>';
									}
									echo '<ul id="firm_attorney_tree_'.$i.'_'.$k.'" class="filetree"><li><div class="assigned_case_manager_tree" data="{firm_id: '.$case_managers[$i]['firm_attorneys'][$k]['legal_firm_id'].'}">'.$case_managers[$i]['firm_attorneys'][$k]['name'].'</div><ul id="attorneys_tree_'.$i.'_'.$k.'" class="filetree">';
									$firm_id = $case_managers[$i]['firm_attorneys'][$k]['legal_firm_id'];
								}
								echo '<li><div class="assigned_case_manager_tree" data="{attorney_id: '.$case_managers[$i]['firm_attorneys'][$k]['legal_atty_id'].'}">'.$case_managers[$i]['firm_attorneys'][$k]['last_name'].' '.$case_managers[$i]['firm_attorneys'][$k]['first_name'].'</div></li>';
							}
							echo '</ul></li></ul>';
						}
						'</li>';
					}
				echo '</ul>';*/
				}
				?>
                </div>
            </td>
            <td style="text-align: left;  border: 1px solid #d8d8d8;">
                <div id="assigned-cases-table-container"></div>
            </td>
        </tr>
    </table>
    
<script>
	$('#case_managers_tree').treeview({
         collapsed: true
     });
</script>