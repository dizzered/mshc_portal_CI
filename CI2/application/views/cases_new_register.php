<script language="javascript">
	var cases_account_id = -1;
	var cases_account = new Array();
	var cases_account_index = -1;
</script>

<div class="main-container-header">
	<table width="100%" border="0" class="main-container-header-tab">
		<tr>
			<td align="left"><div id="header_cases_search_div"> <a id="btn_client_cases_register_search" name="btn_client_cases_register_search" class="alpha-link orange-button-text-white">Locate</a> </div>
				<div id="header_cases_summary_div" style="display:none"> <a id="btn_client_cases_first" name="btn_client_cases_first" class="alpha-link orange-button-text-grey">First</a> <a id="btn_client_cases_prev" name="btn_client_cases_prev" class="alpha-link orange-button-text-grey">Prev</a> <strong>case: </strong>
					<select id="client_cases_list">
					</select>
					<strong> out of <span id="client_cases_list_count"></span></strong> <a id="btn_client_cases_next" name="btn_client_cases_next" class="alpha-link orange-button-text-grey">Next</a> <a id="btn_client_cases_last" name="btn_client_cases_last" class="alpha-link orange-button-text-grey">Last</a> </div></td>
			<td align="right">
				<div id="btn_client_cases_register_new" class="tab-right-beige" style="position:relative;">
					<a href="/<?php echo MSHC_CASES_CONTROLLER_NAME;?>/<?php echo MSHC_CASES_REGISTER;?>">
						<p>Register New Case</p>
					</a>
				</div>
				<div class="tab-left-beige" style="position:relative;"></div>
				<?php
				if ($show_back)
				{
				?>
				<div id="btn_client_cases_back" class="tab-right-beige" style="margin-right:-35px; padding-right:15px; background:#bc8d01;">
					<a href="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME;?>/<?php echo MSHC_CASES_NEW_NAME;?>" id="new_case_register_back">
						<p style="color:#ffffff;">Back to Results</p>
					</a></div>
					<div class="tab-left-orange">
				</div>
				<?php
				}
				?>
				<div style="clear:both"></div>
			</td>
		</tr>
	</table>
</div>

<div class="search-tabs-wrapper">
	<div class="statement-wrapper">
		<p><strong>Generate Statement:</strong></p>
		<ul class="tabs_cases">
			<li id="li-cases-statement" class="ui-state-disabled">
                <span style="padding: 0; margin-left:0">
                    <span style="text-decoration: underline; padding: 0px"
						  id="span_statement_doc">Statement</span> (Details <select
						id="client_cases_search_details" disabled="disabled">
						<option>Full</option>
						<option>Charges</option>
						<option>Summary</option>
					</select>
                    LOB:
                    <select id="statement_lob">
						<option value="all">All</option>
					</select>
                    <span id="statement_finance_wrapper">
                        Financial Class:
                        <select id="statement_finance" disabled="disabled">
							<option value="all">All</option>
							<option value="MD">MD</option>
							<option value="PT">PT</option>
						</select>
                    </span>
					<?php if ($this->_user['role_id'] == MSHC_AUTH_BILLER) :?>
						Last Posting Day
						<select id="client_cases_search_transactions" disabled="disabled">
							<option value="0">--- No Open Day Sheets ---</option>
						</select>
					<?php else : ?>
						<input type="hidden" id="client_cases_search_transactions" value="0">
					<?php endif ?>
					<input type="checkbox" name="checkbox_with_docs" id="checkbox_with_docs" value="1"
						   disabled="disabled"/>
                    With Docs)</span>
				<span>&nbsp;</span>
			</li>
		</ul>
	</div>

	<table class="cases-search-tabs-table" style="margin-top: 17px">
		<tr>
			<td class="cases-search-tabs-round-left">&nbsp;</td>
			<td class="cases-search-tabs-round-center"><ul class="tabs_cases">
					<li id="li-cases-search" class="tabs-state-active"> <span><b>Search</b></span> <span>&nbsp;</span> </li>
					<li id="li-cases-summary" class="ui-state-disabled"> <span><b>&nbsp;Summary</b></span> <span>&nbsp;</span> </li>
					<li id="li-cases-visits" class="ui-state-disabled"> <span><b>Visits</b></span> <span>&nbsp;</span> </li>
					<li id="li-cases-appointments" class="ui-state-disabled"> <span><b>Appointments</b></span> <span>&nbsp;</span> </li>
					<li id="li-cases-documents" class="ui-state-disabled"> <span><b>Documents</b></span> <span>&nbsp;</span> </li>
				</ul></td>
			<td>&nbsp;</td>
			<td class="cases-search-tabs-round-right">&nbsp;</td>
		</tr>
	</table>
</div>

<div class="main-container">
	<div id="box_client_search" style="background-color:#e4ecef; border: 1px solid #d8d8d8; margin-top: -15px">
		<h3 align="left" style="padding: 10px; font-weight:bold">Locate Patient</h3>
		<table class="cases_search_table" style="width:98%;">
			<tr>
				<td align="left" width="80px">Name: </td>
				<td align="left"><input type="text" id="client_cases_name" name="client_cases_name" value="<?php if(isset($client_cases_name)) echo $client_cases_name; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80px">SSN: </td>
				<td align="left"><input type="text" id="client_cases_ssn" name="client_cases_ssn" value="<?php if(isset($client_cases_ssn)) echo $client_cases_ssn; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80px">Phone: </td>
				<td align="left"><input type="text" id="client_cases_phone" name="client_cases_phone" value="<?php if(isset($client_cases_phone)) echo $client_cases_phone; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80px">DOB: </td>
				<td align="left"><input type="text" id="client_cases_dob" name="client_cases_dob" value="<?php if(isset($client_cases_dob)) echo $client_cases_dob; ?>" class="search_field_cases cases_search_input_text" style="width:190px;"/></td>
			</tr>
			<tr>
				<td colspan="8" style="line-height: 18px">&nbsp;</td>
			</tr>
		</table>
	</div>
	
	<div id="box_client_case_detail" class="detail-cases" style="border: 1px solid #d8d8d8;">
		<table width="100%" class="table-cases-summary" style="margin-left: 20px">
			<tr>
				<td colspan="2" id="client_case_name" align="left" style="font-weight:bold; text-transform:uppercase"></td>
				<td align="left" width="80px">Class: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_class" name="client_case_class" value="" disabled="disabled"/></td>
				<td align="left" width="100px">DOA: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_doa" name="client_cases_doa" value="" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="left" width="70px">Account: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_account" name="client_case_account" value="" disabled="disabled"/></td>
				<td align="left" width="80px">Status: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_status" name="client_case_status" value="" disabled="disabled"/></td>
				<td align="left" width="100px">Appt. Status: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_appt_status" name="client_case_appt_status" value="" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="left" width="70px">SSN: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_ssn" name="client_case_ssn" value="" disabled="disabled"/></td>
				<td align="left" width="80px">Database: </td>
				<td align="left"><input type="text" class="cases_search_input_text" id="client_case_database" name="client_case_database" value="" disabled="disabled"/></td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	<script>
	tableID = '';
	tableName = 'cases-new';
	dbName = 'cases';
	</script>
	<div id="btn_cases_summary_div" style="text-align:left; margin-bottom: 10px; margin-top: 10px">
		<input id="btn_cases_summary" type="button" value="Case Summary" disabled="disabled" class="ui-button opacity25" style="color:white; visibility:hidden;" />
		<div id="count_cases_div" align="right" style="float:right; font-size:14px; margin-top:5px"></div>
	</div>
	<div id="btn_visits_all_details_div" style="text-align:right; margin-bottom: 10px; margin-top: 10px; display:none">
		<input id="btn_expand_all_details" type="button" value="Expand All Details" style="color: white" class="ui-button" />
		<input id="btn_collapse_all_details" type="button" value="Collapse All Details" style="color: white" class="ui-button" />
	</div>
	<div id="cases-new-table-container" style="display:none;"></div>
	<div id="summary-table-container" style="display:none; font-size:16px; margin-top:15px"></div>
	<div id="visits-table-container" style="display:none"></div>
	<div id="appointments-table-container" style="display:none">
		<table style="margin-top:15px; background-color:#eaeaea; border: 1px solid grey; line-height: 30px; font-size:16px" width="100%">
			<tr>
				<th align="left" style="padding-left: 10px"><strong>View Appointments</strong></th>
				<th><div id="btn_export_to_word" class="ico-word-15" style="float:right; font-size: 12px">Export to Word</div>
					<div id="btn_export_to_excel" class="ico-excel-15" style="float:right; font-size: 12px">Export to Excel</div></th>
			</tr>
		</table>
	</div>
	<form id="document_open_form" name="document_open_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/documents'; ?>" target="_blank">
		<div id="btn_documents_open_div" style="text-align:left; margin-bottom: 10px; margin-top: 10px; display: none">
			<input id="btn_documents_open" type="submit" value="Open Selected" disabled="disabled" class="ui-button" />
			<input id="documents_account" name="documents_account" type="hidden" value="" />
			<div id="count_documents_div" align="right" style="float:right; font-size:16px"></div>
		</div>
		<div id="documents-table-container" style="display:none"></div>
	</form>
	
	<div class="new-case-registration"> 
		
		<?php echo form_open(base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_REGISTER, array('id' => 'new_case_register_form')); ?>
	
		<h2>New Appointment Request</h2>
		<div class="left">
			<?php
			$name_value = '';
			if (isset($data['last_name'])) $name_value = $data['last_name'];
			if (isset($data) && isset($data['first_name'])) $name_value .= ' '.$data['first_name'];
			
			$name = array(
				'id' => 'name',
				'name' => 'name',
				'value' => $name_value,
				'style' => 'width:552px;'
			);
			echo form_label('Name:', $name['id']).form_input($name);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$address1 = array(
				'id' => 'address1',
				'name' => 'address1',
				'value' => (isset($data) && isset($data['address1']) ? $data['address1']: ''),
				'style' => 'width:552px;'
			);
			echo form_label('Address 1:', $address1['id']).form_input($address1);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$address2 = array(
				'id' => 'address2',
				'name' => 'address2',
				'value' => (isset($data) && isset($data['address2']) ? $data['address2']: ''),
				'style' => 'width:552px;'
			);
			echo form_label('Address 2:', $address2['id']).form_input($address2);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$city = array(
				'id' => 'city',
				'name' => 'city',
				'value' => (isset($patient_info) && isset($patient_info['pnt_addr_city']) ? $patient_info['pnt_addr_city']: ''),
				'style' => 'width:207px;margin-right:31px;'
			);
			echo form_label('City:', $city['id']).form_input($city);
	
			$state = array(
				'id' => 'state',
				'name' => 'state',
				'value' => (isset($patient_info) && isset($patient_info['pnt_addr_state']) ? $patient_info['pnt_addr_state']: ''),
				'style' => 'width:207px;'
			);
			echo form_label('State:', $state['id']).form_input($state);
	
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$home_phone = array(
				'id' => 'home_phone',
				'name' => 'home_phone',
				'value' => (isset($data) && isset($data['phone']) ? $data['phone']: ''),
				'style' => 'width:207px;margin-right:31px;'
			);
			echo form_label('Home Phone:', $home_phone['id']).form_input($home_phone);
	
			$work_phone = array(
				'id' => 'work_phone',
				'name' => 'work_phone',
				'value' => (isset($data) && isset($data['work_phone']) ? $data['work_phone']: ''),
				'style' => 'width:207px;'
			);
			echo form_label('Work Phone:', $work_phone['id']).form_input($work_phone);
	
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$email = array(
				'id' => 'email',
				'name' => 'email',
				'value' => (isset($data) && isset($data['email']) ? $data['email']: ''),
				'style' => 'width:552px;'
			);
			echo form_label('Email:', $email['id']).form_input($email);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$insurer = array(
				'id' => 'insurer',
				'name' => 'insurer',
				//'value' => (isset($patient_insurance) && isset($patient_insurance['InsuranceCompany']) ? $patient_insurance['InsuranceCompany']: ''),
				'value' => '',
				'style' => 'width:552px;'
			);
			echo form_label('Insurer:', $insurer['id']).form_input($insurer);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$adjuster = array(
				'id' => 'adjuster',
				'name' => 'adjuster',
				//'value' => (isset($patient_insurance) && isset($patient_insurance['Adjuster']) ? $patient_insurance['Adjuster']: ''),
				'value' => '',
				'style' => 'width:552px;'
			);
			echo form_label('Adjuster:', $adjuster['id']).form_input($adjuster);
			?>
			<div class="clear" style="height:15px;"></div>
		</div>
		<div class="right">
			<div class="clear" style="height:42px;"></div>
			<?php
			$dob = array(
				'id' => 'dob',
				'name' => 'dob',
				'value' => (isset($data) && isset($data['dob']) ? date('m/d/Y', strtotime($data['dob'])) : ''),
				'style' => 'width:182px;'
			);
			echo form_label('DOB:', $dob['id']).form_input($dob);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$doa = NULL;
			$doa = array(
				'id' => 'doa',
				'name' => 'doa',
				'value' => '',
				'style' => 'width:182px;'
			);
			echo form_label('DOA:', $doa['id']).form_input($doa);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$zip = array(
				'id' => 'zip',
				'name' => 'zip',
				'value' => (isset($patient_info) && isset($patient_info['pnt_addr_zip']) ? $patient_info['pnt_addr_zip']: ''),
				'style' => 'width:182px;',
				'maxlength' => 5
			);
			echo form_label('Zip Code:', $zip['id']).form_input($zip);
			?>
			<div class="clear" style="height:15px;"></div>
			<?php
			$other_phone = array(
				'id' => 'other_phone',
				'name' => 'other_phone',
				'value' => (isset($data) && isset($data['other_phone']) ? $data['other_phone']: ''),
				'style' => 'width:182px;'
			);
			echo form_label('Other Phone:', $other_phone['id']).form_input($other_phone);
			?>
			<div class="clear" style="height:15px;"></div>
			<div class="clear" style="height:42px;"></div>
			<?php
			$claim_no = array(
				'id' => 'claim_no',
				'name' => 'claim_no',
				'value' => '',
				'style' => 'width:182px;'
			);
			echo form_label('Claim No:', $claim_no['id']).form_input($claim_no);
			?>
			<div class="clear" style="height:15px;"></div>
		</div>
		
		<div class="clear"></div>
		
		<div class="bottom">
			
			<h3>Appointment Request (Optional)</h3>
			
			<div class="left">
				
				<span class="first">Location:</span>
				
				<span class="second">Select from</span>
				
				<span style="padding-right:10px;">
				<?php
				$all_locations = array(
					'id' => 'all_locations',
					'name' => 'locations',
					'value' => 'all_locations',
					'style' => '',
					'checked' => 'checked'
				);
				echo form_radio($all_locations).form_label('All locations', $all_locations['id']);
				?>
				</span>

				<span>
				<?php
				$near_locations = array(
					'id' => 'near_locations',
					'name' => 'locations',
					'value' => 'near_locations',
					'style' => '',
				);
				echo form_radio($near_locations).form_label('Near locations', $near_locations['id']);
				?>
				</span>
				
				<div class="clear"></div>
				
				<div id="dialog-near-locations" style="display:none; margin-left:95px; line-height:21px;">
					
					<div class="clear" style="height:10px;"></div>
					<span style="float:left;">Zip code:</span> 
					<span style="float:left; margin-left:7px;"><input type="text" id="location_zip" name="location_zip" maxlength="5" /></span>
					<span style="float:left; margin-left:7px;">in radius: </span>
					<div style="float:left; margin-left:7px;" id="location_distance"></div>
					<span style="float:left; margin-left:7px;">miles</span>
					<span style="float:left; margin-left:7px;"><input id="btn_get_near_locations" type="button" value="Find"></span>
					<div class="clear" style="height:15px;"></div>
					
				</div>				
				
				<div class="clear" style="height:18px;"></div>
				
				<div id="locationsListCount">No locations</div>
				
				<script>
				<?php
				echo 'patientLocation = "'.(isset($patient_info) && isset($patient_info['pnt_addr_city']) ? $patient_info['pnt_addr_city']: '').'";';
				?>
				locationsSource = [
					'--- Not selected ---',
					<?php
					if (is_array($locations_list))
					{
						foreach ($locations_list as $location)
						{
							echo '"'.$location['display_name'].'", ';
						}
					}
					?>
				];
				
				var initialLocations = locationsSource.length;
				
				if (locationsSource.length)
				{
					$('#locationsListCount').html((locationsSource.length - 1) + ' locations');
				}
				</script>
				
				<div id="new_case_register_location"></div>
				
				<div class="clear" style="height:18px;"></div>
				
				<script>
				<?php
				//echo 'patientAccident = "'.(isset($data) && isset($data['case_category']) ? $data['case_category']: '').'";';
				echo 'patientAccident = "";';
				?>
				accidentsTypeSource = [
					'--- Not selected ---',
					'Auto Accident',
					'Auto Work Comp',
					'Impairment Rating',
					'Independent Medical Evaluation (IME)',
					'Motorcycle Bus Cab',
					'Slip and Fall',
					'Subsequent Injury Fund Evaluation (SIF)',
					'Worker’s Compensation',
				];
				</script>
				
				<span class="first" style="margin-top:5px;">Accident Type:</span>
				
				<div id="new_case_register_accidents"></div>
				
				<div class="clear" style="height:18px;"></div>
				
				<script>
				<?php
				echo 'patientAttorney = "'.(isset($patient_info) && isset($patient_info['atty_name']) ? trim($patient_info['atty_name']) : '').'";';
				?>
				attorneysSource = [
					'--- Not selected ---',
					<?php
					if ($attorneys_list)
					{
						foreach ($attorneys_list as $attorney)
						{
							echo '"'.trim($attorney['last_name']).', '.trim($attorney['first_name']).'", ';
						}
					}
					?>
				];
				</script>
				
				<span class="first" style="margin-top:5px;">Attorney:</span>
				
				<div id="new_case_register_attorneys"></div>
				
				<div class="clear" style="height:18px;"></div>
				
				<?php
				$comment = array(
					'id' => 'comment',
					'name' => 'comment',
					'value' => '',
					'style' => 'width:424px;height:65px;'
				);
				echo form_label('Comment:', $comment['id']).form_textarea($comment);
				?>
				
			</div>
			
			<div class="right">
			
				<?php
				$appt_date = array(
					'id' => 'appt_date',
					'name' => 'appt_date',
					'value' => '',
					'style' => 'width:182px;'
				);
				echo form_label('Appt Date:', $appt_date['id']).form_input($appt_date);
				?>
				
				<div class="clear" style="height:18px;"></div>
				
				<p class="red_text">Requests recieved after 5:00 PM Monday - Thursday or after 4:30 PM Friday will be replied to the following business day.</p>
				
				<p class="red_text bold_text">For immediate appointment please call our Appointment Request Line at 888-807-2778 or enter your number below and we will call you immediately!</p>
				
				<div class="call_back">
				
					<p class="bold_text">Enter your number and we will call you.</p>
					<?php
					$callback_code = array(
						'name' => 'callback_code',
						'value' => '',
						'maxlength' => '3'
					);
					$callback_station_code = array(
						'name' => 'callback_station_code',
						'value' => '',
						'maxlength' => '3'
					);
					$callback_number = array(
						'name' => 'callback_number',
						'value' => '',
						'maxlength' => '4'
					);
					?>
					<span class="bold_text" style="margin-right:3px; margin-top:3px;">(</span>
					<?php echo form_input($callback_code); ?>
					<span class="bold_text" style="margin:3px 8px 0 3px;">)</span>
					<?php echo form_input($callback_station_code); ?>
					<span class="bold_text" style="margin:3px 7px 0 7px;">&ndash;</span>
					<?php echo form_input($callback_number); ?>
					<input type="button" value="Call" name="callback_btn" class="input-button-dark-grey" style="padding:6px 22px;margin-top:-1px; margin-left:15px;" />
					
					<div class="clear" style="height:10px;"></div>
					
					<div style="text-align:right;"><a href="http://www.patlive.com/" target="_blank">Powered by TeleRep</a></div>
					
					<div class="clear"></div>
					
				</div>
				
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<div class="clear" style="height:15px;"></div>
		
		<input id="btn_send_new_case" type="button" value="Send" class="ui-button" style="color:white">
		
		<input id="btn_cancel_new_case" type="button" value="Cancel" class="ui-button" style="color:white">
		
		<?php echo form_close(); ?>
</div>

<div id="summary_text_div" style="display:none; text-align:left">
	<p style="color: red; padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">Information reflects transactions through <strong id="max_service_date">Jun 24, 2012</strong>. For up to date balance information please contact the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</p>
	<p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">The dollar amounts shown include Surgical Fees for the Provider's Professional Services only. <strong style="color: red">Facility Fees from the Ambulatory Surgical Center are not included</strong> and must be obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</p>
</div>
<div id="new_registration_cases" style="display:none">
	<form id="new_registration_cases_form" name="new_registration_cases_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_REGISTER; ?>">
    	
    </form>
</div>
<script>
$(function() {
	$('.visit-summary-detail-payment tr:even').addClass('visit-summary-detail-payment-even');
});

var emailFormTo = '<?php $send_to; ?>';
</script>
