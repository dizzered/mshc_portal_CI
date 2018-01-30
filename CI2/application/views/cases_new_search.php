<script language="javascript">
	var cases_account_id = -1;
	var cases_account = new Array();
	var cases_account_index = -1;
</script>

<div class="main-container-header">
	<table width="100%" border="0" class="main-container-header-tab">
		<tr>
			<td align="left"><div id="header_cases_search_div"> <a id="btn_client_cases_new_search" name="btn_client_cases_new_search" class="alpha-link orange-button-text-white">Locate</a> </div>
				<div id="header_cases_summary_div" style="display:none"> <a id="btn_client_cases_first" name="btn_client_cases_first" class="alpha-link orange-button-text-grey">First</a> <a id="btn_client_cases_prev" name="btn_client_cases_prev" class="alpha-link orange-button-text-grey">Prev</a> <strong>case: </strong>
					<select id="client_cases_list">
					</select>
					<strong> out of <span id="client_cases_list_count"></span></strong> <a id="btn_client_cases_next" name="btn_client_cases_next" class="alpha-link orange-button-text-grey">Next</a> <a id="btn_client_cases_last" name="btn_client_cases_last" class="alpha-link orange-button-text-grey">Last</a> </div></td>
			<td align="right"><div id="btn_client_cases_register_new" class="tab-right-beige" style="position:relative;"><a href="/<?php echo MSHC_CASES_CONTROLLER_NAME;?>/<?php echo MSHC_CASES_REGISTER;?>">
					<p>Register New Case</p>
					</a></div>
				<div class="tab-left-beige" style="position:relative;"></div>
				<div class="fnResults" style="display:none; float:right;">
					<div id="btn_client_cases_back" class="tab-right-beige" style="margin-right:-35px; padding-right:15px; background:#bc8d01;">
						<a href="/<?php echo MSHC_CASES_CONTROLLER_NAME;?>/<?php echo MSHC_CASES_NEW_NAME;?>">
							<p style="color:#ffffff;">Back to Results</p>
						</a>
					</div>
					<div class="tab-left-orange"></div>
				</div>
				<div style="clear:both"></div></td>
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
				<td align="left" width="80">Name: </td>
				<td align="left"><input type="text" id="client_cases_name" name="client_cases_name" value="<?php if(isset($client_cases_name)) echo $client_cases_name; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80">SSN: </td>
				<td align="left"><input type="text" id="client_cases_ssn" name="client_cases_ssn" value="<?php if(isset($client_cases_ssn)) echo $client_cases_ssn; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80">Phone: </td>
				<td align="left"><input type="text" id="client_cases_phone" name="client_cases_phone" value="<?php if(isset($client_cases_phone)) echo $client_cases_phone; ?>" class="search_field_cases cases_search_input_text"/></td>
				<td align="left" width="80">DOB: </td>
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
	<div id="cases-new-table-container"></div>
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
</div>
<div id="summary_text_div" style="display:none; text-align:left">
	<p style="color: red; padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">Information reflects transactions through <strong id="max_service_date">Jun 24, 2012</strong>. For up to date balance information please contact the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</p>
	<p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
      The dollar amounts shown include Surgical Fees for the Provider's Professional Services only. <strong style="color: red">Facility Fees from the Ambulatory Surgical Center are not included</strong> 
      and must be obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.
  </p>
  <p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
              Our <strong style="color: red">Anesthesia Services</strong> at the Harford County Ambulatory Surgical Center (HCASC)  
              <strong style="color: red">are outsourced and will not be included in the charges listed above.</strong> 
              If your client has received care at our facility (HCASC) and you have not received a bill from Anesthesia Concepts please contact them directly: 
              <br/><br/>
              <strong style="color: red">Anesthesia Concepts, LLC</strong><br/>
              1302 Rising Ridge Road, Suite #1 <br/>
              Mt. Airy, MD 21771 <br/>
              301-829-7683 main office x 109 <br/>
              301-829-7694 fax <br/>
              <a href="mailto:klewis@anesthesiaconcepts.com" target="_top" style="color: blue">klewis@anesthesiaconcepts.com</a> <br/>
              <a href="http://www.anesthesiaconcepts.com" target="_blank" style="color: blue">www.anesthesiaconcepts.com</a> 
            </p>
</div>
<div id="new_registration_cases" style="display:none">
	<form id="new_registration_cases_form" name="new_registration_cases_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_REGISTER; ?>">
	</form>
</div>
<script>
	$(function() {
		$('.visit-summary-detail-payment tr:even').addClass('visit-summary-detail-payment-even');
	});
	<?php if (isset($case_search_now) && $case_search_now == 'true') { ?>
		$(function(){
			loadTable = false;
			newCaseSearch();
		});
	<?php } ?>
</script>