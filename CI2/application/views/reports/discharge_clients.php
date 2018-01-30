<div class="main-container-header">
    <table border="0" class="main-container-header-tab">
        <tr>
            <td align="left" valign="middle">
            	<span id="discharge_sort_by_attorney" style="font-size: 16px"><strong>Filter by
                        Attorney:</strong><select id="discharge_attorneys_list" name="discharge_attorneys_list">
                        <!--<option value=""> --- Display All ---</option>-->
                        <?php
                        if (isset($attorneys_list) && is_array($attorneys_list)) {
                            for ($i = 0; $i < count($attorneys_list); $i++)
                                echo '<option value="' .
                                    $attorneys_list[$i]['id'] . '"' .
                                    ($i == 0 ? ' selected="selected"' : '') .
                                    '>' . $attorneys_list[$i]['last_name'] . ', ' . $attorneys_list[$i]['first_name'] . '</option>'; // may be external id????
                        }
                        ?>
                    </select></span>

                <div id="header_cases_summary_div" style="display:none">
                    <a id="btn_client_cases_first" name="btn_client_cases_first"
                       class="alpha-link orange-button-text-grey">First</a>
                    <a id="btn_client_cases_prev" name="btn_client_cases_prev"
                       class="alpha-link orange-button-text-grey">Prev</a>
                    <strong>case: </strong><select id="client_cases_list"></select><strong> out of <span
                            id="client_cases_list_count"></span></strong>
                    <a id="btn_client_cases_next" name="btn_client_cases_next"
                       class="alpha-link orange-button-text-grey">Next</a>
                    <a id="btn_client_cases_last" name="btn_client_cases_last"
                       class="alpha-link orange-button-text-grey">Last</a>
                </div>
            </td>
            <td align="right">
                <div id="btn_export_to_word2" name="btn_export_to_word2" class="ico-word-15"
                     style="float:right; font-size: 14px">Export to Word
                </div>
                <div id="btn_export_to_excel2" name="btn_export_to_excel2" class="ico-excel-15"
                     style="float:right; font-size: 14px">Export to Excel
                </div>
                <div id="btn_client_cases_back_to_report" class="tab-right-beige" style="display:none"><p>Back to
                        Report</p></div>
                <div id="btn_client_cases_back_to_report_left" class="tab-left-beige" style="display:none"></div>
                <div style="clear:both"></div>
            </td>
        </tr>
    </table>
</div>
<div id="cases_search_tabs_summary" style="border: 1px solid #cacaca; box-shadow: -2px 0px #cacaca inset; border-top: 0; border-bottom: 0; display:none">
    <div class="statement-wrapper" style="margin-bottom: 17px;">
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
                    <?php
                    if (isset($maxServiceDate['MaxServiceDate'])) {
                        $msdHtml = date('M d, Y', strtotime($maxServiceDate['MaxServiceDate']->format('m/d/Y')));
                    } else {
                        $msdHtml = date('M d, Y');
                    }
                    ?>
                    <?php if ($this->_user['role_id'] == MSHC_AUTH_BILLER || $this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) :?>
                        Last Posting Day
                        <select id="client_cases_search_transactions" disabled="disabled">
                            <option value="0">--- No Open Day Sheets ---</option>
                            <?php if (isset($maxServiceDate['MaxServiceDate'])): ?>
                                <?php $start = strtotime($maxServiceDate['MaxServiceDate']->format('m/d/Y'). '+ 1 day'); ?>
                                <?php $end = time(); ?>
                                <?php for ($i = $start; $i < $end; $i += 3600 * 24): ?>
                                    <option value="<?php echo $i ?>"><?php echo date('m/d/Y', $i) ?></option>
                                <?php endfor ?>
                                <option value="<?php echo $end ?>"><?php echo date('m/d/Y', $end) ?></option>
                            <?php endif ?>
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

    <table class="cases-search-tabs-table">
        <tr>
            <td class="cases-search-tabs-round-left">&nbsp;</td>
            <td class="cases-search-tabs-round-center">
                <ul id="tabs_discharge">
                    <li id="li-discharge-summary">
                        <span><b>&nbsp;Summary</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-discharge-visits">
                        <span><b>Visits</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-discharge-appointments">
                        <span><b>Appointments</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-discharge-documents">
                        <span><b>Documents</b></span>
                        <span>&nbsp;</span>
                    </li>
                </ul>
            </td>
            <td>&nbsp;</td>
            <td class="cases-search-tabs-round-right">&nbsp;</td>
        </tr>
    </table>
</div>

<div class="main-container">
    <div id="box_client_case_detail" class="detail-cases" style="border: 1px solid #d8d8d8;">
        <table width="100%" class="table-cases-summary" style="margin-left: 20px">
            <tr>
                <td colspan="2" id="client_case_name" align="left"
                    style="font-weight:bold; text-transform:uppercase"></td>
                <td align="left" width="75px">Class:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_class"
                                        name="client_case_class" value="" disabled="disabled"/></td>
                <td align="left" width="95px">DOA:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_doa"
                                        name="client_case_doa" value="" disabled="disabled"/></td>
            </tr>
            <tr>
                <td align="left" width="65px">Account:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_account"
                                        name="client_case_account" value="" disabled="disabled"/></td>
                <td align="left" width="65px">SSN:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_ssn"
                                        name="client_case_ssn" value="" disabled="disabled"/></td>
                <td align="left" width="65px">E-mail:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_email"
                                        name="client_email" value="" disabled="disabled"/></td>
            </tr>
            <tr>
                <td align="left" width="65px">Home:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_phone"
                                        name="client_phone" value="" disabled="disabled"/></td>
                <td align="left" width="65px">Work:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_work_phone"
                                        name="client_work_phone" value="" disabled="disabled"/></td>
                <td align="left" width="65px">Cell:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_cell_phone"
                                        name="client_cell_phone" value="" disabled="disabled"/></td>
            </tr>
            <tr>
                <td align="left" width="75px">Status:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_status"
                                        name="client_case_status" value="" disabled="disabled"/></td>
                <td align="left" width="75px">Database:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_database"
                                        name="client_case_database" value="" disabled="disabled"/></td>
                <td align="left" width="95px">Appt. Status:</td>
                <td align="left"><input type="text" class="cases_search_input_text" id="client_case_appt_status"
                                        name="client_case_appt_status" value="" disabled="disabled"/></td>
            </tr>
        </table>
    </div>
    <script>
        tableID = 'Discharge';
        tableName = 'discharge';
        dbName = 'discharge';
    </script>
    <div id="btn_visits_all_details_div" style="text-align:right; margin-bottom: 10px; margin-top: 10px; display:none">
        <input id="btn_expand_all_details" type="button" value="Expand All Details" style="color: white"
               class="ui-button"/>
        <input id="btn_collapse_all_details" type="button" value="Collapse All Details" style="color: white"
               class="ui-button"/>
    </div>
    <div id="discharge-table-container"></div>
    <div id="summary-table-container" style="display:none; font-size:16px; margin-top:15px"></div>
    <div id="visits-table-container" style="display:none"></div>
    <div id="appointments-table-container" style="display:none">
        <table
            style="margin-top:15px; background-color:#eaeaea; border: 1px solid grey; line-height: 30px; font-size:16px"
            width="100%">
            <tr data="{table: 'case-appointments-list'}">
                <th align="left" style="padding-left: 10px"><strong>View Appointments</strong></th>
                <th>
                    <div id="btn_export_to_word" name="btn_export_to_word1" class="ico-word-15"
                         style="float:right; font-size: 12px">Export to Word
                    </div>
                    <div id="btn_export_to_excel1" name="btn_export_to_excel" class="ico-excel-15"
                         style="float:right; font-size: 12px">Export to Excel
                    </div>
                </th>
            </tr>
        </table>
    </div>
    <form id="document_open_form" name="document_open_form" method="post"
          action="<?php echo base_url() . MSHC_CASES_CONTROLLER_NAME . '/documents'; ?>" target="_blank">
        <div id="btn_documents_open_div" style="text-align:left; margin-bottom: 10px; margin-top: 10px; display: none">
            <input id="btn_documents_open" type="submit" value="Open Selected" disabled="disabled" class="ui-button"/>
            <input id="documents_account" name="documents_account" type="hidden" value=""/>

            <div id="count_documents_div" align="right" style="float:right; font-size:16px"></div>
        </div>
        <div id="documents-table-container" style="display:none"></div>
    </form>
</div>
<div id="summary_text_div" style="display:none; text-align:left">
    <p style="color: red; padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
        Information reflects transactions through <strong id="max_service_date">Jun 24, 2012</strong>. For up to date
        balance information please contact the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on
        this site.</p>

    <p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
        The dollar amounts shown include Surgical Fees for the Provider's Professional Services only. <strong
            style="color: red">Facility Fees from the Ambulatory Surgical Center are not included</strong> and must be
        obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.
    </p>
</div>
<script>
    $(function () {
        $('.visit-summary-detail-payment tr:even').addClass('visit-summary-detail-payment-even');

        loadTable = false;
        $('#discharge_attorneys_list').trigger('change');
    });
</script>