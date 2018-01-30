<script language="javascript">
	var cases_account_id = -1;
	var cases_account = new Array();
	var cases_account_index = -1;
	var count_appointments_on_page = 10;
	var number_appointments_page = 1;
	var count_documents_on_page = 10;
	var number_documents_page = 1;
	var count_records_on_page = 10;
	var number_discharge_report_page = 1;
	var filter_drop_down = 'div_filter_dropdown_discharge_report';
	var sorting_field = 'patient ASC';
	var sFieldName = '';
	var sValue = '';
</script>

<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2">

        	<div id="discharge_sort_by_attorney" style="margin-top: 10px;"><label for="attorneys_list">Filter by Attorney:</label> <select id="discharge_attorneys_list" name="discharge_attorneys_list"><!--<option value=""> --- Display All ---</option>-->
				<?php 
                    if (isset($attorneys_list) && is_array($attorneys_list)) {
                            for ($i = 0; $i < count($attorneys_list); $i++)
                                echo '<option value="'.
								$attorneys_list[$i]['id'].'"'.
								($i == 0 ? ' selected="selected"' : '').
								'>'.$attorneys_list[$i]['last_name'].', '.$attorneys_list[$i]['first_name'].'</option>'; // may be external id????
                    }
                ?>
                </select></div> 
            
            <div style="margin-top: 10px; display:none" id="div_client_cases_list" ><label for="client_cases_list">case:</label>
	            <select id="client_cases_list"></select> out of <span id="client_cases_list_count"></span>
            </div> 
<!-- page  case advansed search summary result detail end --> 

        	<div class="tab">

            	<div id="btn_client_cases_back_to_report_left" style="width: 17px; height: 48px; background: url(/images/mobile/tab_back2.png) no-repeat; float: right; display:none;"></div>

<!-- pages  case advansed search results begin -->     
                <div style="background: url(/images/mobile/tab_back1.png) no-repeat; padding: 12px 0 12px 33px; float: right; display:none; cursor:pointer" id="btn_client_cases_back_to_report">Go back to report</div>
<!-- pages  case advansed search results end -->

            </div>

        </div>

		<div class="summary" id="div_discharge_report_detail"></div>

        <div id="case-summary-block" style="display:none">
          
          <div id="tabs">

                <div id="tab-summary"></div>

                <div id="tab-summary-activ"></div>

                <div id="tab-visits"></div>

                <div id="tab-visits-activ" style="display: none;"></div>

                <div id="tab-appointments"></div>

                <div id="tab-appointments-activ" style="display: none;"></div>

                <div id="tab-documents"></div>

                <div id="tab-documents-activ" style="display: none;"></div>

            </div>

            <div id="statement">

            	<span id="span_statement_doc" style="cursor:pointer">Statement</span>   (Details: <select id="client_cases_search_details" disabled="disabled">
                                                                    <option>Full</option>
                                                                    <option>Charges</option>
                                                                    <option>Summary</option>
                                                               </select>
                            	<input type="checkbox" name="checkbox_with_docs" id="checkbox_with_docs" value="1" disabled="disabled"/> With Docs) 

            </div>

            <div id="statement2">

            	<h2 id="client_case_name">ACCIDENT, ALICE</h2>

                <table>

                	<tr>

                    	<td>Account: <span id="client_case_account">42105</span><br />

                        SSN: <span id="client_case_ssn">xxxxx6698</span><br />

                        Class: <span id="client_case_class">Auto Accident</span></td>

                        <td>Status: <span id="client_case_status">Discharged</span><br />

                        Database: <span id="client_case_database">Live</span><br />

                        DOA: <span id="client_case_doa">Feb 25, 2011</span></td>

                        <td>Appt. Status: <span id="client_case_appt_status">64.29%</span></td>

                    </tr>

                </table>

            </div>
            
            <div id="btn_visits_all_details_div" class="button-right" style="display:none">
            	<input id="btn_expand_all_details" type="button" value="Expand All Details" style="color: white" />
        		<input id="btn_collapse_all_details" type="button" value="Collapse All Details" style="color: white" />
            </div>
            
            <div id="btn_documents_open_div" class="button-left" style="display: none">
            	<input type="button" value="Open Selected">
            </div>

            <div class="summary" id="div_summary_detail" style="display:none">
            
            </div>
            
            <div class="summary" id="div_visits_summary_detail" style="display:none">
            
            </div>
            
            <div class="summary" id="div_appointments_detail" style="display:none">
            
            </div>
            
            <div class="summary" id="div_documents_detail" style="display:none">
            
            </div>

        </div>
    	
        <ul class="bottomInfo" id="summary_text_div" style="display:none;">
    
            <li class="red">Information reflects transactions through <strong id="max_service_date">Jun 24, 2012</strong>. For up to date balance information please contact the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</li>
    
            <li>The dollar amounts shown include Surgical Fees for the Provider's Professional Services only <strong class="red">Facility Fees from the Ambulatory Surgical Center are not included</strong> and must be obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</li>
       </ul>
<!-- page  case advansed search summary detail end -->

    </div>
</div>

<script language="javascript">
$(function(){
	load_discharge_report();
});
</script>