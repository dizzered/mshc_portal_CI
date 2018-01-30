<div class="main-container-header">
    <table width="100%" border="0" class="main-container-header-tab">
        <tr>
            <td align="left">
                <div id="header_cases_search_div">
                    <a id="btn_client_cases_search" name="btn_client_cases_search"
                       class="alpha-link orange-button-text-white">Search</a>
                    <a id="btn_client_cases_search_clear" name="btn_client_cases_search_clear"
                       class="alpha-link orange-button-text-white">Clear</a>

                    <span style="font-size: 13px; font-weight:bold; margin-left: 20px">Display cases: </span>

                    <?php echo form_dropdown(
                        'cases_activity_type',
                        array(
                            'all' => 'All',
                            'discharged' => 'Discharged Only',
                            'active' => 'Active Only'
                        ),
                        'all',//$this->_user['cases_search_cases_type'],
                        'id="cases_activity_type" style="padding: 2px 5px;"'
                    ); ?>

                    <span id="btnSearchExport" class="ico-excel-15 disabled"
                          style="margin-left: 10px;">Export to Excel</span>
                </div>
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
                <div id="btn_client_cases_advanced_search" class="tab-right-beige"><p>Advanced Search</p></div>
                <div id="btn_client_cases_back_to_results" class="tab-right-beige" style="display:none">
                    <p><?php echo($summary_conds || $appts_conds ? 'Search' : 'Back to Results'); ?></p>
                </div>
                <div class="tab-left-beige"></div>
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
                    <?php
                    if (isset($maxServiceDate['MaxServiceDate'])) {
                        $msdHtml = date('M d, Y', strtotime($maxServiceDate['MaxServiceDate']->format('m/d/Y')));
                    } else {
                        $msdHtml = date('M d, Y');
                    }
                    ?>
                    <?php if ($this->_user['role_id'] == MSHC_AUTH_BILLER || $this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) : ?>
                        Last Posting Day
                        <select id="client_cases_search_transactions" disabled="disabled">
                            <option value="0">--- No Open Day Sheets ---</option>
                            <?php if (isset($maxServiceDate['MaxServiceDate'])): ?>
                                <?php $start = strtotime($maxServiceDate['MaxServiceDate']->format('m/d/Y') . '+ 1 day'); ?>
                                <?php $end = strtotime(date('Y-m-d 00:00:00')); ?>
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

    <table class="cases-search-tabs-table" style="margin-top: 17px">
        <tr>
            <td class="cases-search-tabs-round-left">&nbsp;</td>
            <td class="cases-search-tabs-round-center">
                <ul class="tabs_cases">
                    <li id="li-cases-search" class="tabs-state-active">
                        <span><b>Search</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-cases-summary" class="ui-state-disabled">
                        <span><b>&nbsp;Summary</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-cases-visits" class="ui-state-disabled">
                        <span><b>Visits</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-cases-appointments" class="ui-state-disabled">
                        <span><b>Appointments</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-cases-documents" class="ui-state-disabled">
                        <span><b>Documents</b></span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="li-cases-contact" class="ui-state-disabled">
                        <span><b>Contact Us</b></span>
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
    <div id="box_client_search" style="background-color:#e4ecef; border: 1px solid #d8d8d8; margin-top: -15px">
        <h3 align="left" style="padding: 10px; font-weight:bold">Client Search</h3>
        <table class="cases_search_table">
            <tr>
                <td align="left" width="100px">Name:</td>
                <td align="left"><input type="text" id="client_cases_name" name="client_cases_name"
                                        value="<?php if (isset($client_cases_name)) echo $client_cases_name; ?>"
                                        class="search_field_cases cases_search_input_text"/></td>
                <td align="left" width="80px">Account:</td>
                <td align="left"><input type="text" id="client_cases_account" name="client_cases_account"
                                        value="<?php if (isset($client_cases_account)) echo $client_cases_account; ?>"
                                        class="search_field_cases cases_search_input_text"/></td>
                <td align="left" width="80px">SSN:</td>
                <td align="left"><input type="text" id="client_cases_ssn" name="client_cases_ssn"
                                        value="<?php if (isset($client_cases_ssn)) echo $client_cases_ssn; ?>"
                                        class="search_field_cases cases_search_input_text"/></td>
            </tr>
            <tr>
                <td colspan="6" style="line-height: 7px">&nbsp;</td>
            </tr>
            <?php
            if ($user['role_id'] == MSHC_AUTH_CASE_MANAGER) {
                $userMdl = new Users();
                $internalUser = $userMdl->is_mshc_internal_user($user['id']);
                $hideMyCasesSelection = (!$internalUser and $user['my_cases_only'] == '1');
                ?>
                <tr class="add_option_advaced_search" style="display:none;">
                    <td style="line-height: 18px">
                        <?php if ($hideMyCasesSelection) { ?>
                            <label for="my_cases"></label>
                        <?php } else { ?>
                            <label for="my_cases">My Cases</label>
                        <?php } ?>
                    </td>
                    <td colspan="5" style="line-height: 18px">
                        <?php if ($hideMyCasesSelection) { ?>
                            <input type="checkbox" name="my_cases" id="my_cases" style="display:none"
                                   checked="checked"/>
                        <?php } else { ?>
                            <input type="checkbox" name="my_cases" id="my_cases" checked="checked"
                                   style="margin:0; padding:0;"/>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="line-height: 7px">&nbsp;</td>
                </tr>
                <?php
            }
            ?>

            <tr style="display:none;" class="add_option_advaced_search">
                <td align="left">Firm:</td>
                <td align="left">
                    <select id="client_cases_search_firms" class="select_cases_search cases_search_input_text">
                        <option value="">--- All firms---</option>
                        <?php
                        if (is_array($firm_list)) {
                            $firms_array = array();
                            for ($i = 0; $i < count($firm_list); $i++) {
                                if (element('legal_firm_id', $firm_list[$i])) $firm_id = element('legal_firm_id', $firm_list[$i]);
                                else $firm_id = element('id', $firm_list[$i]);
                                if (!in_array($firm_id, $firms_array)) {
                                    echo '<option value="' . $firm_id . '"' .
                                        ($client_cases_firm_id == $firm_id ? ' selected="selected"' : '') . '>' .
                                        $firm_list[$i]["name"] . '</option>';
                                    $firms_array[] = $firm_id;
                                }
                            }
                        }
                        ?>
                    </select></td>
                <td colspan="4" align="left"><strong>Date Range</strong></td>
            </tr>
            <tr>
                <td colspan="6" class="add_option_advaced_search" style="line-height: 8px; display:none">&nbsp;</td>
            </tr>
            <tr style="display:none" class="add_option_advaced_search">
                <td align="left"> Attorneys:</td>
                <td rowspan="4" align="left">
                    <div id="div_attorneys_list" class="cases_search_attorney_list">
                        <?php
                        if (isset($attorneys_list) && is_array($attorneys_list)) {
                            for ($i = 0; $i < count($attorneys_list); $i++) {
                                if (isset($client_cases_atty) && $client_cases_atty) {
                                    if ($client_cases_atty == $attorneys_list[$i]["id"]) $chk = ' checked="checked"';
                                    else $chk = '';
                                } else {
                                    $chk = ' checked="checked"';
                                }
                                echo '<div><input type="checkbox" value="' . $attorneys_list[$i]["id"] .
                                    '" id="client_cases_attorneys_list_' . $attorneys_list[$i]["id"] .
                                    '" name="client_cases_attorneys_list"' . $chk .
                                    ' /> ' . $attorneys_list[$i]["last_name"] . ', ' . $attorneys_list[$i]["first_name"] . '</div>';
                            }
                        }
                        ?>
                    </div>
                </td>
                <td align="left" colspan="4"><input name="client_cases_dates" type="radio" value="accident"
                                                    checked="checked" style="margin-left: -1px"/>
                    Date of Accident
                    <input name="client_cases_dates" type="radio" value="service"/>
                    Date of Service
                </td>
            </tr>
            <tr style="display:none" class="add_option_advaced_search">
                <td align="center">
                    <input name="client_cases_attorneys" type="radio"
                           value="my"<?php echo($client_cases_firm_id ? '' : ' checked="checked"') ?> />My
                    <input name="client_cases_attorneys" type="radio"
                           value="all"<?php echo($client_cases_firm_id ? ' checked="checked"' : '') ?> />All
                </td>
                <td align="left" style="padding-top: 10px">From:</td>
                <td align="left" style="padding-top: 10px"><input type="text" class="cases_search_input_text"
                                                                  id="client_cases_date_from"
                                                                  name="client_cases_date_from"
                                                                  value="<?php if (isset($client_cases_doa)) echo $client_cases_doa; ?>"/>
                </td>
                <td align="left" style="padding-top: 10px">To:</td>
                <td align="left" style="padding-top: 10px"><input type="text" class="cases_search_input_text"
                                                                  id="client_cases_date_to" name="client_cases_date_to"
                                                                  value="<?php if (isset($client_cases_doa)) echo $client_cases_doa; ?>"/>
                </td>
            </tr>
            <tr style="display:none" class="add_option_advaced_search">
                <td align="center">
                    <input type="button" id="btn_client_cases_select_all" value="Select All"
                           style="width: 80px; margin-bottom: 5px; font-size: 11px;"/>
                    <br/>
                    <input type="button" id="btn_client_cases_unselect_all" value="Unselect All"
                           style="width: 80px; font-size: 11px;"/>
                </td>
                <td colspan="4"></td>
            </tr>
            <tr style="display:none" class="add_option_advaced_search">
                <td align="center" style="font-size:11px">
                    Show Unselected <br/>
                    <input type="checkbox" value="1" name="client_cases_show_unselected"
                           id="client_cases_show_unselected" checked="checked"/></td>
                <td align="left">
                    <strong>Class</strong> <br/>
                    Category Like:
                </td>
                <td align="left"><br/><input type="text" class="cases_search_input_text" id="client_cases_category_like"
                                             name="client_cases_category_like" value=""/></td>
                <td align="left">Company</td>
                <td align="left">
                    <select id="client_cases_search_company" class="select_cases_search cases_search_input_text">
                        <option value="">--- All companies---</option>
                        <?php
                        if (is_array($company_list)) {
                            $company_array = array();
                            foreach ($company_list as $val) {
                                echo '<option value="' . $val . '"' .
                                    ($client_cases_company_id == $val ? ' selected="selected"' : '') . '>' .
                                    $val . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="search-financial" style="display: none;">
                <td colspan="4">&nbsp;</td>
                <td align="left">Financial Class</td>
                <td>
                    <select id="client_cases_search_financial" class="select_cases_search">
                        <option value="">--- All ---</option>
                        <option value="MD">MD</option>
                        <option value="PT">PT</option>
                    </select>
                </td>
            </tr>
        </table>
        <div id="close_advanced_search" align="right"
             style="padding-right: 10px; padding-bottom: 10px; color:#4c7d8e; font-weight:bold; cursor: pointer; display:none"
             class="add_option_advaced_search">close advanced search >
        </div>
    </div>
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
        tableID = '';
        tableName = 'cases';
        dbName = 'cases';
    </script>
    <div id="btn_cases_summary_div" style="text-align:left; margin-bottom: 10px; margin-top: 10px">
        <input id="btn_cases_summary" type="button" value="Case Summary" disabled="disabled" class="ui-button opacity25"
               style="color:white"/>

        <div id="count_cases_div" align="right" style="float:right; font-size:14px; margin-top:5px"></div>
    </div>
    <div id="btn_visits_all_details_div" style="text-align:right; margin-bottom: 10px; margin-top: 10px; display:none">
        <input id="btn_expand_all_details" type="button" value="Expand All Details" style="color: white"
               class="ui-button"/>
        <input id="btn_collapse_all_details" type="button" value="Collapse All Details" style="color: white"
               class="ui-button"/>
    </div>
    <div id="cases-table-container"></div>
    <div id="summary-table-container" style="display:none; font-size:16px; margin-top:15px"></div>
    <div id="visits-table-container" style="display:none"></div>
    <div id="appointments-table-container" style="display:none">
        <table
                style="margin-top:15px; background-color:#eaeaea; border: 1px solid grey; line-height: 30px; font-size:16px"
                width="100%">
            <tr data="{table: 'case-appointments-list'}">
                <th align="left" style="padding-left: 10px"><strong>View Appointments</strong></th>
                <th>
                    <div id="btn_export_to_word" class="ico-word-15"
                         style="float:right; font-size: 12px">Export to Word
                    </div>
                    <div id="btn_export_to_excel" class="ico-excel-15"
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
    <div id="contact-form-container" style="display:none"><?php $this->view('contact_us'); ?></div>
</div>
<div id="summary_text_div" style="display:none; text-align:left">
    <p style="color: red; padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
        Information reflects transactions through <strong id="max_service_date"><?php echo $msdHtml ?></strong>.
        For up to date balance information please contact the MSHC Business Office at 410-933-5678 or utilize the
        Contact Us page on this site.
    </p>

    <p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
        The dollar amounts shown include Surgical Fees for the Provider's Professional Services only.
        <strong style="color: red">Facility Fees from the Ambulatory Surgical Center are not included</strong>
        and must be obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on
        this site.
    </p>

    <p style="padding:10px 0 10px 25px; background:url(../../images/point-dirty-green.png) 7px 14px no-repeat; font-size:16px">
        Our <strong style="color: red">Anesthesia Services</strong> at the Harford County Ambulatory Surgical Center
        (HCASC)
        <strong style="color: red">are outsourced and will not be included in the charges listed above.</strong>
        If your client has received care at our facility (HCASC) and you have not received a bill from Anesthesia
        Concepts please contact them directly:
        <br/><br/>
        <strong style="color: red">Anesthesia Concepts, LLC</strong><br/>
        1302 Rising Ridge Road, Suite #1 <br/>
        Mt. Airy, MD 21771 <br/>
        301-829-7683 main office x 109 <br/>
        301-829-7694 fax <br/>
        <a href="mailto:klewis@anesthesiaconcepts.com" target="_top"
           style="color: blue">klewis@anesthesiaconcepts.com</a> <br/>
        <a href="http://www.anesthesiaconcepts.com" target="_blank" style="color: blue">www.anesthesiaconcepts.com</a>
    </p>
</div>
<script>
    $(function () {
        $('.visit-summary-detail-payment tr:even').addClass('visit-summary-detail-payment-even');
    });
    <?php if (isset($case_search_now) && $case_search_now == 'true'): ?>
    $(function () {
        loadTable = false;
        $('#btn_client_cases_search').trigger('click');
    });
    <?php else: ?>
    $(function () {
        $('input:radio[name=client_cases_attorneys]').trigger('change');
    });
    <?php endif ?>
    <?php if ($search_advanced) { ?>
    $(function () {
        loadTable = false;
        $('#btn_client_cases_advanced_search').trigger('click');
    });
    <?php } ?>
</script>

<?php
if ($summary_conds) {
    ?>
    <script>
        $(function () {
            loadTable = false;
            cases_account.push(<?php echo json_encode($summary_conds); ?>);
            sCases = new Array;
            sCases.push({'index': 0});
            go_to_cases_summary(sCases);
        });
    </script>
    <?php
}

if ($appts_conds) {
    ?>
    <script>
        $(function () {
            loadTable = false;
            cases_account.push(<?php echo json_encode($appts_conds); ?>);
            sCases = new Array;
            sCases.push({'index': 0});
            $('.right_menu_ctr').html('Appointment History');
            $('#appointments-table-container').show();

            $('.tabs_cases li').removeClass('ui-state-disabled');
            $('#header_cases_search_div').hide();
            $('#header_cases_summary_div').show();
            $('#btn_client_cases_advanced_search').hide();
            $('#btn_client_cases_back_to_results').show();
            $('#box_client_search').hide();
            $('#btn_cases_summary_div').hide();
            $('.add_option_advaced_search').hide();
            $('#box_client_case_detail').show();
            $('#cases-table-container, #cases-new-table-container').hide();
            $('#summary-table-container').hide();
            $('#client_cases_list_count').val('1');
            $('#client_cases_search_details').attr('disabled', false);
            $('#checkbox_with_docs').attr('disabled', false);
            $('.tabs_cases li').removeClass('tabs-state-active');
            $('#li-cases-appointments').addClass('tabs-state-active');
            $('.li-cases-appointments').show();

            tableID = 'Appointments';
            tableName = 'appointments';
            dbName = 'appointments';
            $('#appointments-table-container table.jtable tr#appointments-search').remove();

            get_cases_dropdown(sCases);
            var jj = sCases[0].index;
            set_values_by_cases_id(jj);
            cases_account_id = jj;
            $('#client_cases_list_count').html(sCases.length);

            load_appointments_table();
        });
    </script>
    <?php
}
?>