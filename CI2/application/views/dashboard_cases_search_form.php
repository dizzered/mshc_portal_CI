<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
		<tr><td><span class="ico-header-title" style="margin-right:15px;">Search by...</span></td></tr>
	</table>
</div>
<div class="main-container" style="padding-bottom:0;"> 
	<div id="box_client_search" style="background-color:#ededed; padding:10px">
		<h2 style="margin-top:0;margin-bottom:10px;"><i>Client Cases</i></h2>
		<form name="client_cases_search" id="client_cases_search" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME;?>" method="post">
		<table>
			<tr>
				<td>Name: </td><td><input type="text" size="25" id="client_cases_name" name="client_cases_name" value=""/></td>
				<td>SSN: </td><td><input type="text" size="25" id="client_cases_ssn" name="client_cases_ssn" value=""/> <input type="submit" id="btn_client_cases_dashboard" name="btn_client_cases_dashboard" value="search" class="ui-button" style="color:white"/></td>
			</tr>
			<tr>
				<td>Account: </td><td><input type="text" size="25" id="client_cases_account" name="client_cases_account" value=""/></td>
				<?php
				if ($attys)
				{
					?>
					<td>Attorney: </td>
					<td>
					<?php
					$attys_list = array();
					$attys_list[0] = '--- Choose Attorney ---';
					foreach ($attys as $atty)
					{
						if (element('legal_atty_id', $atty)) $atty_id = element('legal_atty_id', $atty);
						else $atty_id = element('id', $atty);
						$attys_list[$atty_id] = $atty['last_name'].', '.$atty['first_name'];
					}
					echo '<div class="styled-select select_cases_search_container">';
					echo form_dropdown('client_cases_atty', $attys_list, NULL, 'class="select_cases_search" id="client_cases_atty"');
					echo '</div>';
					?>
					</td>
					<?php
				}
				elseif (isset($my_cases) && $my_cases)
				{
					?>
					<td colspan="2">
					<label for="my_cases" style="margin-right:10px;">My Cases:</label>
					<input type="checkbox" name="client_cases_my_cases" id="client_cases_my_cases" checked="checked" value="true" style="margin:0; padding:0;" />
					</td>
					<?php
				}
				else
				{
					?>
					<td colspan="2">&nbsp;</td>
					<?php
				}
				?>
			</tr>
		</table>
		</form>
		<div align="right" style="padding-right: 10px"><a href="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME.'/advanced';?>" style="text-decoration:none; font-weight:bold; cursor: pointer; color:#878787;">advanced search ></a></div>
	</div>
	
	<div style="padding:10px; text-align:left;">
		<h2 style="margin-top:0;margin-bottom:10px;"><i>New Cases</i></h2>
		<form name="client_new_cases_search" id="client_new_cases_search" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_NEW_NAME;?>" method="post">
		<table>
			<tr>
				<td>Name: </td><td><input type="text" size="25" id="new_cases_name" name="new_cases_name" value=""/></td>
				<td>SSN: </td><td><input type="text" size="25" id="new_cases_ssn" name="new_cases_ssn" value=""/> <input type="submit" id="new_cases_locate" name="new_cases_locate" value="locate" class="ui-button" style="color:white"/></td>
			</tr>
			<tr>
				<td>Phone: </td><td><input type="text" size="25" id="new_cases_phone" name="new_cases_phone" value=""/></td>
				<td>DOB: </td><td><input type="text" size="25" id="new_cases_dob" name="new_cases_dob" value=""/></td>
			</tr>
		</table>
		</form>
	</div>
</div>
