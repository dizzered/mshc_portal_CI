<script language="javascript">
	var cases_account_id = -1;
	var cases_account = new Array();
	var cases_account_index = -1;
	var count_appointments_on_page = 10;
	var number_appointments_page = 1;
	var count_documents_on_page = 10;
	var number_documents_page = 1;
	var filter_drop_down = 'div_filter_dropdown_cases_search';
	var sorting_field = 'patient ASC';
	var count_cases_search_on_page = 10;
	var number_cases_search_page = 1;
	var sFieldName = '';
	var sValue = '';
</script>

<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2">
<!-- page  case advansed search buttons begin -->     
        	 <input id="btn_client_cases_search" name="btn_client_cases_search" value="Search" type="button">

            <input id="btn_client_cases_search_clear" name="btn_client_cases_search_clear" value="clear" type="button">
<!-- page  case advansed search buttons end-->     

<!-- page  case advansed search summary begin -->     
        	<!--div style="margin-top: 10px; display:none" id="div_cases_search_max_results"><label for="page-size">Max results</label><select id="page-size"><option>10</option><option>25</option><option>50</option><option>100</option></select></div-->
<!-- page  case advansed search summary end-->     

<!-- page  case advansed search summary result detail begin -->
			<div style="margin-top: 10px; display:none" id="div_client_cases_list" ><label for="client_cases_list">case:</label>
	            <select id="client_cases_list"></select> out of <span id="client_cases_list_count"></span>
            </div> 
<!-- page  case advansed search summary result detail end --> 

        	<div class="tab">

            	<div style="width: 17px; height: 48px; background: url(/images/mobile/tab_back2.png) no-repeat; float: right;"></div>
<!-- page  case advansed search begin -->     
                <div style="background: url(/images/mobile/tab_back1.png) no-repeat; padding: 12px 0 12px 33px; float: right; cursor:pointer" id="btn_client_cases_advanced_search">Advanced Search</div>
<!-- page  case advansed search end-->                    

<!-- pages  case advansed search results begin -->     
                <div style="background: url(/images/mobile/tab_back1.png) no-repeat; padding: 12px 0 12px 33px; float: right; display:none; cursor:pointer" id="btn_client_cases_back_to_search">Go back to search</div>
<!-- pages  case advansed search results end -->

<!-- pages  case advansed search summary, visits, appointments, documents, statments begin -->     
				<div style="background: url(/images/mobile/tab_back1.png) no-repeat; padding: 12px 0 12px 33px; float: right; display:none; cursor:pointer" id="btn_client_cases_back_to_results">Back to Results</div>
<!-- pages  case advansed search summary, visits, appointments, documents, statments end -->
           
            </div>

        </div>

<!-- page  case advansed search summary button and checkbox begin -->        
        <div style="float: left; padding: 11px; display: none" id="div_button_case_summary">

        	<input type="button" value="Case Summay" id="btn_cases_summary" style="margin-top: 0" class="ui-button">

        </div>

        <div style="float: right; padding: 20px 11px 11px; display:none" id="div_checkbox_display_closed_cases">

        	<label for="checkbox_display_closed_cases" style="margin-right: 10px;">Display closed cases</label>
            <input type="checkbox" id="checkbox_display_closed_cases" name="checkbox_display_closed_cases" value="1" />

        </div>
<!-- page  case advansed search summary button and checkbox end -->    

        <div class="clear"></div>

<!-- page  case advansed search parametrs begin -->      
        <div class="search-box" id="box_client_search">

            <table class="search-table">

            	<tr>

                	<td colspan="2"><h2>Client Search</h2></td>

                </tr>

                <tr>

                	<td><label for="search-name">Name:</label></td>

                    <td class="input"><input type="text" value="<?php if(isset($client_cases_name)) echo $client_cases_name; ?>" id="client_cases_name" name="client_cases_name" class="search_field_cases" /></td>

                </tr>

                <tr>

                	<td><label for="search-ssn">SSN:</label></td>

                    <td class=""><input type="text" value="<?php if(isset($client_cases_ssn)) echo $client_cases_ssn; ?>" id="client_cases_ssn" name="client_cases_ssn" class="search_field_cases" /></td>

                </tr>

                <tr>

                	<td style="width:100px;"><label for="search-account">Account:</label></td>

                    <td class=""><input type="text" value="<?php if(isset($client_cases_account)) echo $client_cases_account; ?>" id="client_cases_account" name="client_cases_account" class="search_field_cases" /></td>

                </tr>

            </table>
            
             <table class="search-table" id="add_option_advaced_search" style="display:none">
             	
				<?php
				if ($user['role_id'] == MSHC_AUTH_CASE_MANAGER)
				{
          $userMdl = new Users(); 
          $internalUser = $userMdl->is_mshc_internal_user($user['id']);
          $hideMyCasesSelection = (!$internalUser and $user['my_cases_only'] == '1');
				?>
				<tr>
                  <?php  if($hideMyCasesSelection) { ?>
                  <td style="width:100px;"><label for="my_cases"></label></td>
                  <?php } else { ?>
                  <td style="width:100px;"><label for="my_cases">My Cases</label></td>
                  <?php } ?>

                  <td class="input" style="text-align: left;padding-bottom: 0;padding-top: 10px;">
                      <?php  if($hideMyCasesSelection) { ?>
                      <input type="checkbox" name="my_cases" id="my_cases" style="display:none" checked="checked" />
                      <?php } else { ?>
                      <input type="checkbox" name="my_cases" id="my_cases" checked="checked" style="margin:0; padding:0;" />
                      <?php } ?>
                  </td>

                </tr>
				<?php
				}
				?>
				
				<tr>

                  <td><label for="search-firm">Firm:</label></td>

                  <td class="input"><select id="client_cases_search_firms" class="select_cases_search cases_search_input_text">
					<option value="">--- All firms---</option>
					<?php 
						if (is_array($firm_list)) 
						{
							$firms_array = array();
							for ($i = 0; $i < count($firm_list); $i++) 
							{
								if (element('legal_firm_id', $firm_list[$i])) $firm_id = element('legal_firm_id', $firm_list[$i]);
								else $firm_id = element('id', $firm_list[$i]);
								if (!in_array($firm_id, $firms_array))
								{
									echo '<option value="'.$firm_id.'"'.
									($client_cases_firm_id == $firm_id ? ' selected="selected"' : '').'>'.
									$firm_list[$i]["name"].'</option>';
									$firms_array[] = $firm_id;
								}
							}
						}
					?>
				  </select>
                  </td>

                </tr>

                <tr>

                	<td colspan="2">

                    	<div class="attorney">

                        	Attorneys:<br />

                            <input name="client_cases_attorneys" type="radio" value="my" checked="checked" style="margin-top:9px;" />
                            <label for="attorney-my"  style="margin-right:9px;">My</label>	
                  			<input name="client_cases_attorneys" type="radio" value="all" style="margin-top:9px;" />
                            <label for="attorney-all">All</label>

                            <div>
                            
                                <input type="button" id="btn_client_cases_select_all" value="Select All" style="width: 124px; margin-bottom:20px;"/>
                                
                                <input type="button" id="btn_client_cases_unselect_all" value="Unselect All" style="width: 124px; margin-bottom:20px;"/>
        						
                               <label for="show-unselected">Show Unselected</label>
                               <br />
                               <input type="checkbox" value="1" name="client_cases_show_unselected" id="client_cases_show_unselected" checked="checked"/>
                                   
                            </div>

                        </div>

                        <div id="div_attorneys_list" style="float: left; width: 391px; height: 229px; border: 1px solid #e5e5e5; background: #fff; padding: 15px; overflow-y:scroll;">

                        	<?php 
									if (isset($attorneys_list) && is_array($attorneys_list)) {
										for ($i = 0; $i < count($attorneys_list); $i++) {
											if (isset($client_cases_atty) && $client_cases_atty)
											{
												if ($client_cases_atty == $attorneys_list[$i]["id"]) $chk = ' checked="checked"';
												else $chk = '';
											}
											else
											{
												$chk = ' checked="checked"';
											}
											echo '<div><input type="checkbox" value="'.$attorneys_list[$i]["id"].'" id="client_cases_attorneys_list_'.$attorneys_list[$i]["id"].'" name="client_cases_attorneys_list" '.$chk.'/> '.$attorneys_list[$i]["last_name"].' '.$attorneys_list[$i]["first_name"].'</div>';
										}
									}
								?>

                        </div>

                        <div class="clear"></div>

                    </td>

                </tr>

                <tr>

                	<td colspan="2"><h2>Date Range</h2></td>

                </tr>

                <tr>

                	<td colspan="2">

                        <input name="client_cases_dates" type="radio" value="accident" checked="checked" style="margin-top:9px;"/><label for="date-of-acccident" style="margin-right: 49px;" >Date of Accident</label>
                        <input name="client_cases_dates" type="radio" value="service" style="margin-top:9px;" /><label for="date-of-service" style="margin-right: 49px;">Date of Service</label>
                    
                    </td>

                </tr>

                <tr>

                	<td><label for="client_cases_date_from">From:</label></td>

                	<td class="input"><input type="text" size="30" id="client_cases_date_from" name="client_cases_date_from" value="" /></td>

                </tr>

                <tr>

                	<td><label for="client_cases_date_to">To:</label></td>

                	<td class="input"><input type="text" size="30" id="client_cases_date_to" name="client_cases_date_to" value=""/></td>

                </tr>

                <tr>

                	<td colspan="2"><h2>Class</h2></td>

                </tr>

                <tr>

                	<td colspan="2">
                    	<label for="category-like">Category Like:</label>
                        <input type="text" id="client_cases_category_like" name="client_cases_category_like" style="width: 360px;" value=""/>
                    </td>

                </tr>

                <tr>

                	<td colspan="2" class="close-search" style="padding-top:10px;"><span id="close_advanced_search">close advanced search ></span></td>

                </tr>

             </table>

        </div>
<!-- page  case advansed search parametrs end -->   

<!-- page  case advansed search summary results begin -->
		<ul class="searchResult" style="display:none" id="cases_search_result"></ul>
<!-- page  case advansed search summary results end -->   

<!-- page  case advansed search summary detail begin -->
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

                    	<td>

                            Class: <span id="client_case_class"></span><br />

                            DOA: <span id="client_case_doa"></span><br />

                            Account: <span id="client_case_account"></span><br />

                            SSN: <span id="client_case_ssn"></span><br />

                            Status: <span id="client_case_status">Discharged</span><br />

                            E-mail: <span id="client_email"></span>

                        </td>

                        <td>

                            Phone: <span id="client_phone"></span><br />

                            Work: <span id="client_work_phone"></span><br />

                            Cell: <span id="client_cell_phone"></span><br />

                            Database: <span id="client_case_database">Live</span><br />

                            Appt. Status: <span id="client_case_appt_status">64.29%</span>

                        </td>

                    </tr>

                </table>

            </div>
            
            <div id="btn_visits_all_details_div" class="button-right" style="display:none">
            	<input id="btn_expand_all_details" type="button" value="Expand All Details" style="color: white" />
        		<input id="btn_collapse_all_details" type="button" value="Collapse All Details" style="color: white" />
            </div>
            
            <div id="btn_documents_open_div" class="button-left" style="display: none">
				<input id="btn_documents_open" type="button" value="Open Selected" />
            </div>

            <div class="summary" id="div_summary_detail" style="display:none">
            
            </div>
            
            <div class="summary" id="div_visits_summary_detail" style="display:none">
            
            </div>
            
            <div class="summary" id="div_appointments_detail" style="display:none">
            
            </div>
            
			<form id="document_open_form" name="document_open_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME; ?>/documents" target="_blank">
				<input type="hidden" value="" name="document_checkbox[]" />
				<input id="documents_account" name="documents_account" type="hidden" value="" />
				
	            <div class="summary" id="div_documents_detail" style="display:none">
            
    	        </div>
			
			</form>
			
        </div>
    	
        <ul class="bottomInfo" id="summary_text_div" style="display:none;">
    
            <li class="red">Information reflects transactions through <strong id="max_service_date">Jun 24, 2012</strong>. For up to date balance information please contact the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.</li>
    
            <li>The dollar amounts shown include Surgical Fees for the Provider's Professional Services only 
                <strong class="red">Facility Fees from the Ambulatory Surgical Center are not included</strong> 
                and must be obtained separately from the MSHC Business Office at 410-933-5678 or utilize the Contact Us page on this site.
            </li>
                <li>
              Our <strong class="red">Anesthesia Services</strong> at the Harford County Ambulatory Surgical Center (HCASC)  
              <strong class="red">are outsourced and will not be included in the charges listed above.</strong> 
              If your client has received care at our facility (HCASC) and you have not received a bill from Anesthesia Concepts please contact them directly: 
              <br/><br/>
              <strong class="red">Anesthesia Concepts, LLC</strong> <br/>
              1302 Rising Ridge Road, Suite #1 <br/>
              Mt. Airy, MD 21771 <br/>
              301-829-7683 main office x 109 <br/>
              301-829-7694 fax <br/>
              <a href="mailto:klewis@anesthesiaconcepts.com" target="_top" style="color: blue">klewis@anesthesiaconcepts.com</a> <br/>
              <a href="http://www.anesthesiaconcepts.com" target="_blank" style="color: blue">www.anesthesiaconcepts.com</a> 
            </li>
       </ul>
<!-- page  case advansed search summary detail end -->

    </div>

</div>

<script>
	<?php if (isset($case_search_now) && $case_search_now == 'true') { ?>
		$(function(){
			$('#btn_client_cases_search').trigger('click');
		});
	<?php } 	?>
	<?php if ($search_advanced) { ?>
		$(function(){
			loadTable = false;
			$('#btn_client_cases_advanced_search').trigger('click');
		});
	<?php } ?>
</script>