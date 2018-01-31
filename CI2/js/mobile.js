// JavaScript Document
$(function(){
	
	// ------------------------------------------
	// initialize please wait dialog box
	// ------------------------------------------
	$("#dialog-please-wait").dialog({
		bgiframe: true,
		resizable: false,
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,
		buttons: {},
		create: function(event, ui) {
			var parent = $('#'+$(this).attr('id')).parent();
			$('#'+$(this).attr('id')).css('padding','15px');
			parent.children('.ui-dialog-titlebar').remove();
		}
	}); //dialog_please_wait
	
	$("#dialog-please-wait").dialog('open');
	$("#dialog-please-wait").dialog('close');
	
	// ------------------------------------------
	// initialize general message dialog box
	// ------------------------------------------
	$("#dialog-general-message").dialog({
		bgiframe: true,
		resizable: false,
		height:200,
		width:500,
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,		
		buttons: {
			'Ok': function() {
				$(this).dialog('close');
			}
		},
		close: function(event, ui) { 
			$("#dialog-general-message-text").html("");
		}
	}); //dialog_general_message
	
	$("#dialog-general-message").dialog('open');
	$("#dialog-general-message").dialog('close');
	
	// ------------------------------------------
	// initialize prompt message dialog box
	// ------------------------------------------
	$("#dialog-prompt-message").dialog({
		bgiframe: true,
		resizable: false,
		height:200,
		width:400,
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,
		buttons: {
			'Ok': function(data) {
				if ($('#prompt_func_name').val()) 
				{
					window[$('#prompt_func_name').val()]();
				}
				$(this).dialog('close');
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		},
		close: function(event, ui) {
			$('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
			$("#dialog-prompt-message-text").html("");
		}
	}); //dialog_prompt_message
	
	$("#dialog-prompt-message").dialog('open');
	$("#dialog-prompt-message").dialog('close');
	
	// ------------------------------------------
	// initialize mileage dialog box
	// ------------------------------------------
	$("#bottomUp").dialog({
		bgiframe: true,
		resizable: false,
		height:360,
		width:600,
		autoOpen: false,
		modal: true,
		draggable: true,
		resizable: false,
		buttons: {
			'Close': function() {
				$(this).dialog('close');
			}
		},
		close: function(event, ui) {
			$('#bottomUp .tableHeader').hide();
			$('#bottomUp .tableBody').html('').hide();
			$(this).dialog('option', 'height', 360);
			
		}
	}); //dialog_prompt_message
	
	$("#bottomUp").dialog('open');
	$("#bottomUp").dialog('close');
	
	$('#btn_client_cases_search').on('click', function() {
			
			display_please_wait('Data loading ...');
			
			sName = $('#client_cases_name').val();
			sAccount = $('#client_cases_account').val();
			sSSN = $('#client_cases_ssn').val();
			sDateFrom = $('#client_cases_date_from').val();
			sDateTo = $('#client_cases_date_to').val();
			sClass = $('#client_cases_category_like').val();
			sMyCases = $('#my_cases').is(':checked') ? true : false;
			sAttys = new Array;
			$('#div_attorneys_list input:checkbox:checked').each(function() {
				sAttys.push($(this).val());
			});

			if (sAttys.length == 0 && userRole != 'Biller')
			{
				display_text_message('Please select at least one attorney.', 600, 300);
                close_please_wait();
				return;
			}
			
			if ( sName.length == 0 && sAccount.length == 0 && sSSN.length == 0 && sDateFrom.length == 0 && sDateTo.length == 0 && sClass.length == 0 && sAttys.length == 0) {
				display_text_message('Please select at least one parameter.', 320, 150);
				return;
			}
		
			/*if ( $('#client_cases_name').val() == '' && $('#client_cases_ssn').val() == '' && $('#client_cases_account').val() == '' 
					&& $('#client_cases_date_from').val() == '' && $('#client_cases_date_to').val() == '' 	&& $('#client_cases_category_like').val() == '' && sAttys.length == 0)  {
				display_text_message('Please select at least one parameter.', 500, 300);
				return;
			}*/		
			
			$.ajax({
				type: 'POST',
				async: true,
				url: baseURL + ajaxCONTROLLER + '/get_cases_table?jtSorting='+sorting_field + '&jtPageSize=' + count_cases_search_on_page + '&jtStartIndex=' + (number_cases_search_page-1)*count_cases_search_on_page,
				dataType: 'html',
				data: {
						sName: $('#client_cases_name').val() ,
						sSSN: $('#client_cases_ssn').val(),
						sAccount: $('#client_cases_account').val(),
						sTypeDate: $('input:radio[name=client_cases_dates]:checked').val(),
						sDateFrom: $('#client_cases_date_from').val(),
						sDateTo: $('#client_cases_date_to').val(),
						sClass: $('#client_cases_category_like').val(),
						sCasesClosed: ($('#checkbox_display_closed_cases').is(':checked') ? $('#checkbox_display_closed_cases').val() : ''),
						sAttys: sAttys,
						sMyCases: sMyCases
					},
					success: function(data) {
						var results_obj = jQuery.parseJSON(data);
						
						if (results_obj != null && results_obj.Result == 'OK') {
							var total = results_obj.TotalRecordCount;
							if (results_obj.Records.length == 0 && total > 0)
							{
								display_text_message('Please narrow down your search criteria.', 600, 300);
								return;
							}
							$('#btn_client_cases_search').hide();
							$('#btn_client_cases_search_clear').hide();
							$('#btn_client_cases_advanced_search').hide();
							$('#box_client_search').hide();
							$('#div_filter_dropdown_cases_search').hide();
							
							$('#div_name_filter').show();
							$('#div_cases_search_max_results').show();
							$('#btn_client_cases_back_to_search').show();
							$('#div_button_case_summary').show();
							$('#div_checkbox_display_closed_cases').show();
							$('#cases_search_result').show();
							$('#header_title').html('Case Summary');
							
							var cases_result = '<li class="header"><div class="checkbox"><input id="cases_client_checkbox_all" type="checkbox" value="1" /></div><div style="float: right;"><span id="count_patient_cases">0</span> patient cases found</div><div class="clear"></div></li>';
							
							for (var i = 0; results_obj.Records.length > i; i++) {
								cases_result += '<li><div class="checkbox">' + '<input id="cases_client_checkbox_' + i + '" type="checkbox" name="Summary" value="1" data="{index: \'' + i + '\'}"/>' + '</div>';
								cases_result += '<div class="column1"><div><strong>Attorney:</strong><br />' + results_obj.Records[i].attorney_name + '</div>';
								cases_result += '<div><strong>Patient:</strong><br />' + results_obj.Records[i].last_name + ' ' + results_obj.Records[i].first_name + ' ' + results_obj.Records[i].middle_name + '</div>';
								cases_result += '<div><strong>Account:</strong>' + results_obj.Records[i].account + '</div></div>';
								
								cases_result += '<div class="column2"><div><strong>Class:</strong> ' + results_obj.Records[i].case_category + '</div>';
								cases_result += '<div><strong>DOA:</strong>' + results_obj.Records[i].accident_date +'</div>';
								cases_result += '<div><strong>Database:</strong> ' + results_obj.Records[i].db_name+ '</div>';
								cases_result += '<div><span id="summary_' + i + '" style="text-decoration:underline; cursor:pointer" class="btn_cases_search_summary" data="{index: \'' + i + '\'}">Summary</span></div></div><div class="clear"></div></li>';

							}
							
							cases_result += '<div class="paging"><div class="arrow-block">';
							if (results_obj.TotalRecordCount > count_cases_search_on_page)
								if (number_cases_search_page > 1) {
									cases_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
								} else {
									cases_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
								}
							
							cases_result += '<div class="page-number">' + number_cases_search_page + '</div>';
							
							if (results_obj.TotalRecordCount > count_cases_search_on_page)
								if (number_cases_search_page * count_cases_search_on_page < results_obj.TotalRecordCount) {
									cases_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
								} else {
									cases_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
								}
							
							var count_pages = parseInt( results_obj.TotalRecordCount / count_cases_search_on_page);
							if (results_obj.TotalRecordCount % count_cases_search_on_page > 0) count_pages++;	
							cases_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
				//			app_result += '<option ' + (count_appointments_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
							cases_result += '<option ' + (count_cases_search_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
							cases_result += '<option ' + (count_cases_search_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
							cases_result += '<option ' + (count_cases_search_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
							cases_result += '<option ' + (count_cases_search_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
							cases_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_cases_search">' + count_pages + '</strong> pages</div><div class="clear"></div></div>';
							
							$('#cases_search_result').html(cases_result);
							$('#count_patient_cases').html(results_obj.TotalRecordCount);
							cases_account = results_obj.Records;
//							console.log(cases_account);
						} else {
							$('#cases_search_result').hide();
						}
						cases_account_index = -1;
						
					},
					complete: function() {
						close_please_wait();
					},
					error: function(data){
						// display Error message
						display_text_message('Error');
					}
			});
	});
	
	$('#btn_client_cases_select_all').live('click', function() {
		$('#div_attorneys_list input[type="checkbox"]').attr('checked', true);
	});
	
	$('#btn_client_cases_unselect_all').live('click', function() {
		$('#div_attorneys_list input[type="checkbox"]').attr('checked', false);
	});
	
	$('#div_name_filter').live('click', function() {
		$('#' + filter_drop_down).toggle();
		if ($(this).hasClass('plus')) {
			$(this).removeClass('plus').addClass('minus');
		} else {
			$(this).removeClass('minus').addClass('plus');
			$('.minusDrop').hide();
			$('.minus2').removeClass('minus2').addClass('plus2');
		}
	});
	
	$('.search_field_cases').keyup(function(e) {
            // Enter pressed?
            if(e.which == 13) {
                $('#btn_client_cases_search').trigger('click');
            }
    });
	
	$('#btn_client_cases_search_clear').live('click', function() {
			$('input[type="text"]').val('');
			$('input[type="radio"]').attr('checked', false);
			$('input[type="radio"]').each(function(){ 
				if ($(this).val() == 'accident' || $(this).val() == 'my')	$(this).attr('checked', true);
			});
			$('#client_cases_search_firms').val('');
			$('#client_cases_search_firms').trigger('change');
	});
	
	$('#cases_client_checkbox_all').live('click', function() {
		if ($('#cases_client_checkbox_all').is(':checked')) {
			$('[id*=cases_client_checkbox_]').attr('checked', true);
			$('#cases_search_result > li').addClass('activ');
			$('#btn_cases_summary').css('color', 'white');
		} else {
			$('[id*=cases_client_checkbox_]').attr('checked', false);
			$('#cases_search_result > li').removeClass('activ');
			$('#btn_cases_summary').css('color', 'grey');
		}
	});
	
	$('[id*=cases_client_checkbox_]').live('click', function() {
		if ($(this).is(':checked')) {
			$(this).parent('div').parent('li').addClass('activ');
			$('#btn_cases_summary').css('color', 'white');
		} else {
			$(this).parent('div').parent('li').removeClass('activ');
			if ($('cases_search_result > li').hasClass('activ')) {} else { $('[id*=cases_client_checkbox_all]').attr('checked', false); }
			if ($('[id*=cases_client_checkbox_]').is(':checked')) {
			} else {
				$('#btn_cases_summary').css('color', 'grey');
			}
		}
	});
	
	$('#btn_cases_summary').live('click', function() {
		if ($('[id*=cases_client_checkbox_]:checked').length > 0) {
			sCases = new Array;
			$('[id*=cases_client_checkbox_]:checked').each(function() {
				if ($(this).attr('id') != 'cases_client_checkbox_all')
					sCases.push($(this).metadata());
			});
			go_to_cases_summary(sCases);
		}
	});
	
	$('.btn_cases_search_summary, .btn_discharge_report_summary').live('click', function() {
		sCases = new Array;
		sCases.push($(this).metadata());
		go_to_cases_summary(sCases);
	});
	
	$('#btn_client_cases_back_to_search').live('click', function() {
		$('#btn_client_cases_advanced_search').show();
		$('#btn_client_cases_search').show();
		$('#btn_client_cases_search_clear').show();
		$('#box_client_search').show();
		
		$('#btn_client_cases_back_to_search').hide();
		$('#box_client_case_detail').hide();
		$('#cases_search_result').hide();
		$('#div_name_filter').hide();
		$('#div_filter_dropdown_cases_search').hide();
		$('#div_filter_dropdown_appointments').hide();		
		$('#div_cases_search_max_results').hide();
		$('#div_button_case_summary').hide();
		$('#div_checkbox_display_closed_cases').hide();
		
		$('#header_title').html('Search Cases');
		cases_account_id = -1;
	});
	
	$('#btn_client_cases_back_to_results').live('click', function() {
		$('#btn_client_cases_back_to_search').show();
		$('#div_cases_search_max_results').show();
		$('#div_button_case_summary').show();
		$('#div_checkbox_display_closed_cases').show();
		$('#div_name_filter').show();
		$('#div_filter_dropdown_cases_search').hide();
		$('#div_filter_dropdown_appointments').hide();
		$('#cases_search_result').show();
		filter_drop_down = 'div_filter_dropdown_cases_search';
		sorting_field = 'attorney_name ASC';
		
		$('#btn_client_cases_back_to_results').hide();
		$('#div_client_cases_list').hide();
		$('#case-summary-block').hide();
		
		$('#tab-visits-activ').hide();
		$('#btn_visits_all_details_div').hide();
		$('#div_visits_summary_detail').hide();
		
		$('#tab-appointments-activ').hide();
		$('#div_appointments_detail').hide();
		
		$('#tab-documents-activ').hide();
		$('#div_documents_detail').hide();
		
		$('#div_summary_detail').hide();
		$('#summary_text_div').hide();
		
		$('#btn_documents_open_div').hide();		
		
		$('#header_title').html('Search Cases');
		cases_account_id = -1;
	});
	
	$('#btn_client_cases_back_to_report').live('click', function() {
		$('#discharge_sort_by_attorney').show();
		$('#div_cases_search_max_results').show();
		$('#div_name_filter').show();
		$('#div_filter_dropdown_cases_search').hide();
		$('#div_filter_dropdown_appointments').hide();
		$('#div_discharge_report_detail').show();
		filter_drop_down = 'div_filter_dropdown_discharge_report';
		sorting_field = 'patient ASC';
		
		$('#btn_client_cases_back_to_report').hide();
		$('#btn_client_cases_back_to_report_left').hide();
		$('#div_client_cases_list').hide();
		$('#case-summary-block').hide();
		
		$('#tab-visits-activ').hide();
		$('#btn_visits_all_details_div').hide();
		$('#div_visits_summary_detail').hide();
		
		$('#tab-appointments-activ').hide();
		$('#div_appointments_detail').hide();
		
		$('#tab-documents-activ').hide();
		$('#div_documents_detail').hide();
		
		$('#div_summary_detail').hide();
		$('#summary_text_div').hide();
		
		$('#btn_documents_open_div').hide();		
		
		$('#header_title').html('Discharge Report & Client List');
		cases_account_id = -1;
	});
	
	$('#close_advanced_search').on('click', function() {
			$('#add_option_advaced_search').slideUp();
			$('#client_cases_date_from').val('');
			$('#client_cases_date_to').val('');
			$('#client_cases_dates').val('accident');
			$('#client_cases_category_like').val('');
	});
	
	$('#btn_client_cases_advanced_search').live('click', function() {
			$('#add_option_advaced_search').slideDown();
			$('#client_cases_date_from').val('');
			$('#client_cases_date_to').val('');
			$('#client_cases_category_like').val('');
			$('input[type="radio"]').attr('checked', false);
			$('input[type="radio"]').each(function(){ 
				if ($(this).val() == 'accident' || $(this).val() == 'my')	$(this).attr('checked', true);
			});
			$('#client_cases_search_firms').val('');
			$('#client_cases_search_firms').trigger('change');
	});
	
	$('#client_cases_list').live('change', function() {
		var currentElm = $('#client_cases_list option:selected');
		cases_account_id = currentElm.val(); 

		set_values_by_cases_id(cases_account_id);

		if ($('#tab-summary-activ').css('display') == 'block') {
			load_summary_table();
		}
		if ($('#tab-appointments-activ').css('display') == 'block') {
			load_appointments_table();
		}
		if ($('#tab-visits-activ').css('display') == 'block') {
			load_visits_table();
		}
		if ($('#tab-documents-activ').css('display') == 'block') {
			load_documents_table();
		}
	});
	
	$('#tab-summary, #tab-visits, #tab-appointments, #tab-documents').live('click',function() {
		
		if ($(this).attr('id') == 'tab-summary') {
			$('#tab-summary-activ').show();
			$('#div_name_filter').hide();
			load_summary_table();
			$('#statement').removeClass('ui-state-disabled');
			$('#statement').css('cursor', 'pointer');
		} else {
			$('#tab-summary-activ').hide();
			$('#div_summary_detail').hide();
			$('#summary_text_div').hide();
//			$('#statement').addClass('ui-state-disabled');
//			$('#statement').css('cursor', 'none');
		}
		
		if ($(this).attr('id') == 'tab-visits') {
			$('#tab-visits-activ').show();
			$('#div_name_filter').hide();
			load_visits_table();
		} else {
			$('#tab-visits-activ').hide();
			$('#btn_visits_all_details_div').hide();
			$('#div_visits_summary_detail').hide();
		}
		
		if ($(this).attr('id') == 'tab-appointments') {
			$('#tab-appointments-activ').show();
			sorting_field = 'date ASC';
			filter_drop_down = 'div_filter_dropdown_appointments';
			load_appointments_table();
		} else {
			$('#tab-appointments-activ').hide();
			$('#div_appointments_detail').hide();
		}
		
		if ($(this).attr('id') == 'tab-documents') {
			$('#tab-documents-activ').show();
			sorting_field = 'date_of_service ASC';
			filter_drop_down = 'div_filter_dropdown_documents';
			load_documents_table();
		} else {
			$('#tab-documents-activ').hide();
			$('#btn_documents_open_div').hide();	
			$('#div_documents_detail').hide();
		}
		
	});
	
	$('#span_statement_doc').live('click', function() {
		
			load_statements_table();
		
	});
	
	
	$('.visit-summary-detail-closed').live('click', function() {
		var div_id = $(this).attr('id');
		$('#div_amount_' + div_id).show();
		$('#div_payment_' + div_id).show();
		$(this).removeClass('visit-summary-detail-closed');
		$(this).removeClass('plus3');
		$(this).addClass('visit-summary-detail-open');
		$(this).addClass('minus3');
		get_visit_summary_detail_by_date(div_id);
	});
	
	$('.visit-summary-detail-open').live('click', function() {
		var div_id = $(this).attr('id');
		$('#div_amount_' + div_id).hide();
		$('#div_payment_' + div_id).hide();
		$(this).removeClass('visit-summary-detail-open');
		$(this).removeClass('minus3');
		$(this).addClass('visit-summary-detail-closed');
		$(this).addClass('plus3');
	});
	
	$('#btn_expand_all_details').live('click', function() {
		$('.visit-summary-detail-closed').removeClass('visit-summary-detail-closed').addClass('visit-summary-detail-open').removeClass('plus3').addClass('minus3');
		var divSection = $.find('[id*="div_payment_"]');
		$('[id*="div_amount_"]').show();
		$('[id*="div_payment_"]').show();
		for (var i = 0; i < divSection.length; i++) {
			get_visit_summary_detail_by_date(divSection[i].id.substring(12));
		}
	});
	
	$('#btn_collapse_all_details').live('click', function() {
		$('.visit-summary-detail-open').removeClass('visit-summary-detail-open').addClass('visit-summary-detail-closed').addClass('plus3').removeClass('minus3');
		$('[id*="div_amount_"]').hide();
		$('[id*="div_payment_"]').hide();
	});
	
	$('#page-size').live('change', function() {
		if (filter_drop_down == 'div_filter_dropdown_cases_search') {
			count_cases_search_on_page = $(this).val();
			number_cases_search_page = 1;
			$('#btn_client_cases_search').trigger('click');
		}
		if (filter_drop_down == 'div_filter_dropdown_appointments') {
			count_appointments_on_page = $(this).val();
			number_appointments_page = 1;
			load_appointments_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_documents') {
			count_documents_on_page = $(this).val();
			number_documents_page = 1;
			load_documents_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_discharge_report') {
			count_records_on_page = $(this).val();
			number_discharge_report_page = 1;
			load_discharge_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_mileage_report') {
			count_records_on_page = $(this).val();
			number_mileage_report_page = 1;
			load_mileage_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_notifications') {
			count_records_on_page = $(this).val();
			number_notifications_page = 1;
			load_notifications_table();
		}
	});
	
	$('.arrow-first').live('click', function() {
		if (filter_drop_down == 'div_filter_dropdown_cases_search' && number_cases_search_page > 1) {
			number_cases_search_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			$('#btn_client_cases_search').trigger('click');
		}
		if (filter_drop_down == 'div_filter_dropdown_appointments' && number_appointments_page > 1) {
			number_appointments_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_appointments_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_documents' && number_documents_page > 1) {
			number_documents_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_documents_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_discharge_report' && number_discharge_report_page > 1) {
			number_discharge_report_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_discharge_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_mileage_report' && number_mileage_report_page > 1) {
			number_mileage_report_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_mileage_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_notifications' && number_notifications_page > 1) {
			number_notifications_page = 1;
			$('.arrow-first').css('cursor', 'none');
			$('.arrow-back').css('cursor', 'none');
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_notifications_table();
		}
	});
	
	$('.arrow-back').live('click', function() {
		if (filter_drop_down == 'div_filter_dropdown_cases_search' && number_cases_search_page > 1) {
			number_cases_search_page--;
			if (number_cases_search_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			$('#btn_client_cases_search').trigger('click');
		}
		if (filter_drop_down == 'div_filter_dropdown_appointments' && number_appointments_page > 1) {
			number_appointments_page--;
			if (number_appointments_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_appointments_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_documents' && number_documents_page > 1) {
			number_documents_page--;
			if (number_documents_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_documents_table();
		}
		if (filter_drop_down == 'div_filter_dropdown_discharge_report' && number_discharge_report_page > 1) {
			number_discharge_report_page--;
			if (number_discharge_report_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_discharge_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_mileage_report' && number_mileage_report_page > 1) {
			number_mileage_report_page--;
			if (number_mileage_report_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_mileage_report();
		}
		if (filter_drop_down == 'div_filter_dropdown_notifications' && number_notifications_page > 1) {
			number_notifications_page--;
			if (number_notifications_page == 1) {
				$('.arrow-first').css('cursor', 'none');
				$('.arrow-back').css('cursor', 'none');
			}
			$('.arrow-last').css('cursor', 'pointer');
			$('.arrow-next').css('cursor', 'pointer');
			load_notifications_table();
		}
	});
	
	$('.arrow-next').live('click', function() {
		if (filter_drop_down == 'div_filter_dropdown_cases_search') {
			var count_pages_cases_search = parseInt($('#count_pages_cases_search').html());
			if (number_cases_search_page < count_pages_cases_search) {
				number_cases_search_page++;
				if (number_cases_search_page == count_pages_cases_search) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				$('#btn_client_cases_search').trigger('click');
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_appointments') {
			var count_pages_appointments = parseInt($('#count_pages_appointments').html());
			if (number_appointments_page < count_pages_appointments) {
				number_appointments_page++;
				if (number_appointments_page == count_pages_appointments) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_appointments_table();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_documents') {
			var count_pages_documents = parseInt($('#count_pages_documents').html());
			if (number_documents_page < count_pages_documents) {
				number_documents_page++;
				if (number_documents_page == count_pages_documents) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_documents_table();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_discharge_report') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_discharge_report_page < count_pages) {
				number_discharge_report_page++;
				if (number_discharge_report_page == count_pages) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_discharge_report();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_mileage_report') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_mileage_report_page < count_pages) {
				number_mileage_report_page++;
				if (number_mileage_report_page == count_pages) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_mileage_report();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_notifications') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_notifications_page < count_pages) {
				number_notifications_page++;
				if (number_notifications_page == count_pages) {
					$('.arrow-last').css('cursor', 'none');
					$('.arrow-next').css('cursor', 'none');
				}
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_notifications_table();
			}
		}
	});
	
	$('.arrow-last').live('click', function() {
		if (filter_drop_down == 'div_filter_dropdown_cases_search') {
			var count_pages_cases_search = parseInt($('#count_pages_cases_search').html());
			if (number_cases_search_page < count_pages_cases_search) {
				number_cases_search_page = count_pages_cases_search;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				$('#btn_client_cases_search').trigger('click');
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_appointments') {
			var count_pages_appointments = parseInt($('#count_pages_appointments').html());
			if (number_appointments_page < count_pages_appointments) {
				number_appointments_page = count_pages_appointments;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_appointments_table();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_documents') {
			var count_pages_documents = parseInt($('#count_pages_documents').html());
			if (number_documents_page < count_pages_documents) {
				number_documents_page = count_pages_documents;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_documents_table();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_discharge_report') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_discharge_report_page < count_pages) {
				number_discharge_report_page = count_pages;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_discharge_report();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_mileage_report') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_mileage_report_page < count_pages) {
				number_mileage_report_page = count_pages;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_mileage_report();
			}
		}
		if (filter_drop_down == 'div_filter_dropdown_notifications') {
			var count_pages = parseInt($('#count_pages_records').html());
			if (number_notifications_page < count_pages) {
				number_notifications_page = count_pages;
				$('.arrow-last').css('cursor', 'none');
				$('.arrow-next').css('cursor', 'none');
				$('.arrow-first').css('cursor', 'pointer');
				$('.arrow-back').css('cursor', 'pointer');
				load_notifications_table();
			}
		}
	});
	
	$('.minusDrop').live('click', function() {
		if ($(this).metadata().sort != undefined) {
			sorting_field = $(this).metadata().sort;
			$('#' + filter_drop_down).hide();
			$('#div_name_filter').removeClass('minus').addClass('plus');
			$('.minus2').removeClass('minus2').addClass('plus2');
			$('.minusDrop').hide();
			if ( filter_drop_down == 'div_filter_dropdown_cases_search') {
				$('#btn_client_cases_search').trigger('click');
			}
			if ( filter_drop_down == 'div_filter_dropdown_appointments') {
				load_appointments_table();
			}
			if ( filter_drop_down == 'div_filter_dropdown_documents') {
				load_documents_table();
			}
			if ( filter_drop_down == 'div_filter_dropdown_discharge_report') {
				load_discharge_report();
			}
			if ( filter_drop_down == 'div_filter_dropdown_mileage_report') {
				load_mileage_report();
			}
		}
	});
	
	$('.plus2').live('click', function() {
		$('.minusDrop').hide();
		$('.minus2').addClass('plus2').removeClass('minus2');
		$('.' + $(this).attr('id')).toggle();
		$(this).removeClass('plus2').addClass('minus2');
	});
	
	$('.minus2').live('click', function() {
		$('.' + $(this).attr('id')).toggle();
		$(this).removeClass('minus2').addClass('plus2');
		$('.minusDrop').hide();
	});
	
	$('#document_checkbox_all').live('click', function() {
		if ($('#document_checkbox_all').is(':checked')) {
			$('[id*=document_checkbox_]').attr('checked', true).parents('ul').parents('li').addClass('activ');
		} else {
			$('[id*=document_checkbox_]').attr('checked', false).parents('ul').parents('li').removeClass('activ');
		}
	});
	
	$('.case_dosuments_checkbox').live('click', function() {
		if ($(this).is(':checked')) {
			$(this).parents('ul').parents('li').addClass('activ');
		} else {
			$(this).parents('ul').parents('li').removeClass('activ');
		}
	});
	
	
	$('#appointments_date_search').live('click', function() {
		if ($('#date_contains').val() != '') {
			sFieldName = 'date';
			sValue = $('#date_contains').val();
			$('#div_name_filter').trigger('click');
			load_appointments_table();
		}
	});
	
	$('#appointments_provider_search').live('click', function() {
		if ($('#provider_contains').val() != '') {
			sFieldName = 'provider';
			sValue = $('#provider_contains').val();
			$('#div_name_filter').trigger('click');
			load_appointments_table();
		}
	});
	
	$('#appointments_reason_search').live('click', function() {
		if ($('#reason_contains').val() != '') {
			sFieldName = 'reason';
			sValue = $('#reason_contains').val();
			$('#div_name_filter').trigger('click');
			load_appointments_table();
		}
	});
	
	$('#appointments_location_search').live('click', function() {
		if ($('#location_contains').val() != '') {
			sFieldName = 'location';
			sValue = $('#location_contains').val();
			$('#div_name_filter').trigger('click');
			load_appointments_table();
		}
	});
	
	$('#appointments_status_search').live('click', function() {
		if ($('#status_contains').val() != '') {
			sFieldName = 'status';
			sValue = $('#status_contains').val();
			$('#div_name_filter').trigger('click');
			load_appointments_table();
		}
	});
	
	
	$('#discharge_report_patient_search').live('click', function() {
		if ($('#patient_contains').val() != '') {
			sFieldName = 'patient';
			sValue = $('#patient_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	$('#discharge_report_account_search').live('click', function() {
		if ($('#account_contains').val() != '') {
			sFieldName = 'account';
			sValue = $('#account_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	$('#discharge_report_case_category_search').live('click', function() {
		if ($('#case_category_contains').val() != '') {
			sFieldName = 'case_category';
			sValue = $('#case_category_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	$('#discharge_report_accident_date_search').live('click', function() {
		if ($('#accident_date_contains').val() != '') {
			sFieldName = 'accident_date';
			sValue = $('#accident_date_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	$('#discharge_report_discharge_date_search').live('click', function() {
		if ($('#discharge_date_contains').val() != '') {
			sFieldName = 'discharge_date';
			sValue = $('#discharge_date_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	$('#discharge_report_status_search').live('click', function() {
		if ($('#status_contains').val() != '') {
			sFieldName = 'status';
			sValue = $('#status_contains').val();
			$('#div_name_filter').trigger('click');
			load_discharge_report();
		}
	});
	
	
	$('#mileage_report_last_name_search').live('click', function() {
		if ($('#mileage_last_name_contains').val() != '') {
			sFieldName = 'last_name';
			sValue = $('#mileage_last_name_contains').val();
			$('#div_name_filter').trigger('click');
			load_mileage_report();
		}
	});
	
	$('#mileage_report_first_name_search').live('click', function() {
		if ($('#mileage_first_name_contains').val() != '') {
			sFieldName = 'first_name';
			sValue = $('#mileage_first_name_contains').val();
			$('#div_name_filter').trigger('click');
			load_mileage_report();
		}
	});
	
	$('#mileage_report_account_search').live('click', function() {
		if ($('#mileage_account_contains').val() != '') {
			sFieldName = 'account';
			sValue = $('#mileage_account_contains').val();
			$('#div_name_filter').trigger('click');
			load_mileage_report();
		}
	});
	
	$('#mileage_report_accident_date_search').live('click', function() {
		if ($('#mileage_accident_date_contains').val() != '') {
			sFieldName = 'accident_date';
			sValue = $('#mileage_accident_date_contains').val();
			$('#div_name_filter').trigger('click');
			load_mileage_report();
		}
	});
	
	$('#mileage_report_case_category_search').live('click', function() {
		if ($('#mileage_case_category_contains').val() != '') {
			sFieldName = 'class';
			sValue = $('#mileage_case_category_contains').val();
			$('#div_name_filter').trigger('click');
			load_mileage_report();
		}
	});
	
	
	$('.btn_document_open').live('click', function() {
		checkId = $(this).metadata().index;
		$('input[name="document_checkbox[]"]').attr('checked', false);
		$('#document_checkbox_' + checkId).attr('checked', true);
		$('#document_open_form').submit();
	});
	
	$('#btn_documents_open').live('click', function() {
		$('#document_open_form').submit();
	});
	
	$('#client_cases_show_unselected').live('change', function() {
			 if($('input[name=client_cases_show_unselected]').is(':checked')==false){
				 $('#div_attorneys_list input:checkbox').not(':checked').parent('div').hide();
			} else {
				$('#div_attorneys_list input:checkbox').not(':checked').parent('div').show();
			}
	});
			
	$('#contact_us #inquiry_type_id').live('change', function() {
			if ($(this).val() == 'marketers') {
				$('#label_marketer').show();
				$('#select_marketer').show();
			} else {
				$('#label_marketer').hide();
				$('#select_marketer').hide();
				$('#marketer_id').val('');
			}
	});
	
	$('#btn_contact_us_clear').live('click', function() {
			$('#contact_us input[type="text"]').val('');
			$('#contact_us input[type="checkbox"]').attr('checked', false);
			$('#contact_us textarea').val('');
			$('#contact_us_inqury_type').val('');
			$('#label_marketer').hide();
			$('#select_marketer').hide();
			$('#select_marketer').val('');
			$('#fileupload_container').html('');
			$('#contact_us input[type="file"]').val('');
	});
	
	$('#btn_contact_us_submit').live('click', function() {
			if ( $('#name').val() == '' ) {
				display_text_message('Please enter valid \'Your Name\'.');
				return;
			}
			if (! validateValueByPattern($('#email').val(), emailPattern) ) {
				display_text_message('Please enter valid \'Your Email\'.');
				return;
			}
			if ( $('#cc_to').val() != '') {
				var emails = $('#cc_to').val().split(',');
				var new_emails = '';
				for (var i = 0; i < emails.length; i++) {
					emails[i] = $.trim(emails[i]);
					if ( ! validateValueByPattern(emails[i], emailPattern) ) {
						display_text_message('Please enter valid emails in \'CC  To\' .');
						return;
					}
					if (i > 0) new_emails += ", ";
					new_emails += emails[i];
				}
				$('#cc_to').val(new_emails);
			}
			if ( $('#body').val() == '' ) {
				display_text_message('Please enter valid \'Your Inquiry \'.');
				return;
			}
			$('#contact_us').submit();
	});
	
	$('#btn_contact_us_add').live('click', function() {
		if (counterFileUpload == maxCounterFileUpload) {
			return;
		}
		counterFileUpload++;
		$('#fileupload_container').append('<span class="file-wrapper" data="{id: ' + counterFileUpload + '}"><input type="file" name="fileupload' + counterFileUpload + '" id="fileupload' + counterFileUpload + '" /><input type="text" class="file-holder" value="" style="width:250px;"><span class="input-button-grey file-button" style="display:inline-block;">Choose File</span></span>');
		var $this = $(this);
		$(this).remove();
		$('#fileupload_container').append($this);
		if (counterFileUpload == maxCounterFileUpload) {
			$('#btn_contact_us_add').attr('disabled', true).addClass('ui-state-disabled');
		}
	});
	
	$('input:radio[name=client_cases_attorneys], #client_cases_search_firms').live('change', function() {
		$.ajax({
			type: 'POST',
			async: true,
			url: baseURL + ajaxCONTROLLER + '/get_attorneys',
			dataType: 'html',
			data: {atty_type: $('input:radio[name=client_cases_attorneys]:checked').val(), 
						firm_id: $('#client_cases_search_firms').val()},
				success: function(data) {
					$('#div_attorneys_list').html(data);
					$('#client_cases_show_unselected').attr('checked', true);
				},
				complete: function() {
				},
				error: function(data){
					// display Error message
					display_text_message('Error');
				}
		});
	});
	
	$( "#client_cases_date_from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
	  changeYear: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: '',
      onClose: function( selectedDate ) {
        $( "#client_cases_date_to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
	
    $( "#client_cases_date_to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
	  changeYear: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: '',
      onClose: function( selectedDate ) {
        $( "#client_cases_date_from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
	
	$('[name=clients_address_wish]').live('click', function() {
		if ($('[name=clients_address_wish]').is(':checked')) {
			$('#btn_calculate_wish_address').attr('disabled', false).removeClass('opacity25');
			if ($('[name=clients_address_wish]:checked').val() == 'address') {
				$('#custom_address').show();
			} else {
				$('#custom_address').val('');
				$('#custom_address').hide();
			}
		}
	});
	
	$('.btn_calculate_distance_row').live('click',function() {
		$('#dialog-popup-content-title').html('Calculate Distance for <i>' + $(this).metadata().name + '</i>');
		$('#bottomUp').dialog('open');
		calculate_account_id = $(this).metadata().index;
	});
	
	$('#close_calculate_distance').live('click', function() {
		$('#bottomUp').hide();
		calculate_account_id = 0;
	});
	
	$('#btn_calculate_wish_address').live('click', function() {
		if ($('[name=clients_address_wish]').is(':checked')) 
		{
			if ($('#custom_address').val() == '' && $('[name=clients_address_wish]:checked').val() == 'address')
			{
				display_text_message('Please specify address.');
				return;
			}
			load_calculate_distance($('[name=clients_address_wish]:checked').val());
		}
	});
	
	$('.delete_notification').live('click', function() {
		if (confirm('Are you sure you want to delete this notification?')) {
			$.ajax({
				type: 'POST',
				async: true,
				url: baseURL + ajaxCONTROLLER + '/delete_notification',
				dataType: 'html',
				data: {
					sNotificationID: $(this).metadata.id
				},
				success: function(data) {
					
				},
				complete: function() {
				},
				error: function(data){
					// display Error message
					display_text_message('Error');
				}
			});
		}
	});
	
	$('#attorneys_list').live('change', function() {
		load_discharge_report();
	});
	
	$('.fnOpenNotifiedDoc').live('click', function(e) {
		e.preventDefault();
		var path = $(this).attr('href');
		$('#document_open_form input[name="document_checkbox[]"]').val(path);
		$('#document_open_form').submit();
	});
	
	$('#btn_client_cases_dashboard').on('click', function(e) {
		e.preventDefault();
		
		var sMyCases = true;
		if ($('#client_cases_my_cases').length)
		{
			if ($('#client_cases_my_cases').is(':checked'))
			{
				sMyCases = false;
				//$('#client_cases_search').attr('action', '/cases/search/advanced');
			}
		}
		
		var sAttys = true;
		if ($('#client_cases_atty').length)
		{
			if ($('#client_cases_atty').val() != 0)
			{
				sAttys = false;
				//$('#client_cases_search').attr('action', '/cases/search/advanced');
			}
		}
		
		if ($('#client_cases_name').val() == '' && 
			$('#client_cases_ssn').val() == '' && 
			$('#client_cases_account').val() == '' && 
			sMyCases && sAttys
		) 
		{
			display_text_message('Please select at least one parameter.', 600, 300);
			return;
		}
		
		$('#client_cases_search').submit();
	});
	
	$('#discharge_attorneys_list').live('change', function() {
		load_discharge_report();
	});
			
});

function display_text_message(message, width, height, resizable, buttons) {
    if (!width) width = 600;
    if (!height) height = 400;
    if (!resizable) resizable = false;
    if (buttons) 
    {
        $("#dialog-general-message").dialog('option','buttons', buttons);
    }
    else
    {
    	// Ensure OK button is here.  sbd
    	// If after a please wait, it's not there.
    	buttons = new Array();
		buttons[0] = {
			text:"Ok",
			click:function () {
				$(this).dialog('close');
			}
		};
    	$("#dialog-general-message").dialog("option", "buttons", buttons);
    }
    
    $("#dialog-general-message").dialog('option','height', height);
    $("#dialog-general-message").dialog('option','width', width);
    $("#dialog-general-message").dialog('option','resizable', resizable);
	
    $("#dialog-general-message-text").html(message);
    $("#dialog-general-message").dialog('open');
}

function display_please_wait(message, width, height) 
{
    if (!width) width = 300;
    if (!height) height = 280;
	if (!message) message = 'Please wait..';
	
	$('#dialog-please-wait-title').html(message);
	$('#dialog-please-wait #ajax-loader').css('height', 200).css('width', 200);
    $("#dialog-please-wait").dialog('option','height', height);
    $("#dialog-please-wait").dialog('option','width', width);
	$("#dialog-please-wait").dialog('open');
}

function close_please_wait()
{
	$("#dialog-please-wait").dialog('close');
}


function go_to_cases_summary(sInds) {
	var count_cases = sInds.length;
	
	if (filter_drop_down == 'div_filter_dropdown_cases_search') {
		$('#btn_client_cases_back_to_search').hide();
		$('#box_client_search').hide();
		$('#add_option_advaced_search').hide();
		
		$('#btn_client_cases_back_to_results').show();
		$('#box_client_case_detail').show();
		
		$('#div_cases_search_max_results').hide();
		$('#div_button_case_summary').hide();
		$('#div_checkbox_display_closed_cases').hide();
	} else {
		$('#discharge_sort_by_attorney').hide();
		$('#div_discharge_report_detail').hide();
		
		$('#btn_client_cases_back_to_report').show();
		$('#btn_client_cases_back_to_report_left').show();
		$('#box_client_case_detail').show();
	}
	
	$('#li-cases-search').removeClass('tabs-state-active');
	$('#li-cases-summary').addClass('tabs-state-active');
	$('#div_name_filter').hide();
	$('#div_filter_dropdown_cases_search').hide();
	$('#div_filter_dropdown_appointments').hide();
	$('#cases_search_result').hide();
	
	$('#client_cases_list_count').val(count_cases);
	$('#div_client_cases_list').show();
	$('#case-summary-block').show();
	
	$('#header_title').html('Case Summary');
	
	$('#client_cases_search_details').attr('disabled', false);
	$('#client_cases_search_transactions').attr('disabled', false);
	$('#checkbox_with_docs').attr('disabled', false);
	
	get_cases_dropdown(sInds);
	var jj = sInds[0].index;
	set_values_by_cases_id(jj);
	cases_account_id = jj;
	$('#client_cases_list_count').html(count_cases);
	$('#summary_text_div').show();
	load_summary_table();
}

function validateValueByPattern(value, needPattern) {
	if (value) {
		var pattern = new RegExp(needPattern);
		if (pattern.test(value)) {
			return true;
		}
	}
	return false;
}

function get_cases_dropdown(sInds) {
	if (typeof(sInds)=='object' && (sInds instanceof Array)) {
		if ( $('#client_cases_list') ) {
			// clear options from select
			if ( $('#client_cases_list option').length > 0) { $('#client_cases_list option').remove(); } 
			// add new options to select
			for (var i = 0; i < sInds.length; i++) {
				var j = sInds[i].index;
				$('#client_cases_list').append(	$('<option></option>').val(j).html((i+1) + ' - ' + cases_account[j].last_name + ', ' + cases_account[j].first_name));
			}
		}
	}
}

function set_values_by_cases_id(case_id) {
	$('#client_case_name').html(cases_account[case_id].last_name + ', ' + cases_account[case_id].first_name);
	$('#client_case_class').html(cases_account[case_id].case_category);
	if (cases_account[case_id].accident_date.length > 0) {
        $('#client_case_doa').html(cases_account[case_id].accident_date);
    }
	$('#client_case_account').html(cases_account[case_id].account);
	$('#client_case_status').html(cases_account[case_id].status);
	$('#client_case_appt_status').html(cases_account[case_id].appt_status);
	$('#client_case_ssn').html('xxxxx' + cases_account[case_id].ssn.substring(5));
	$('#client_case_database').html(cases_account[case_id].db_name);
    $('#client_email').html(cases_account[case_id].e_mail_address);
    $('#client_phone').html(cases_account[case_id].phone);
    $('#client_work_phone').html(cases_account[case_id].work_phone);
    $('#client_cell_phone').html(cases_account[case_id].cell_phone);
	if ($('#documents_account').length > 0)
	{
		$('#documents_account').val(cases_account[case_id].account);
	}
}

function load_summary_table() {
	var jj = cases_account_id;
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_summary_case_table',
		dataType: 'html',
		data: {
			sAccountID: cases_account[jj].account,
			sDbName: cases_account[jj].db_name,
			sPractice: cases_account[jj].practice,
			sPatient: cases_account[jj].patient,
			sCaseNo: cases_account[jj].case_no
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				$('#case-summary-block').show();
				$('#div_summary_detail').show();
				$('#header_title').html('Case Summary');
							
				var cases_result = '<div class="tableHeader"><ul class="columns4"><li>Charges</li><li>Payments</li><li>Adjustments</li><li>Balance</li></ul><div class="clear"></div></div><div class="tableBody"><ul class="rows">';
				
				for (var i = 0; results_obj.Records.length > i; i++) {
					cases_result += '<li><strong>'+ results_obj.Records[i].company + '</strong><br />';
					cases_result += '<ul class="columns4"><li>$' + parseFloat(results_obj.Records[i].charges).toFixed(2) + '</li>';
					cases_result += '<li' + (results_obj.Records[i].payments < 0 ? ' class="negative-balance" ' : '') + '>$' + parseFloat(results_obj.Records[i].payments).toFixed(2)  + '</li>';
					cases_result += '<li>$' + parseFloat(results_obj.Records[i].adjustments).toFixed(2) + '</li>';
					cases_result += '<li>$' + parseFloat(results_obj.Records[i].balance).toFixed(2) + '</li></ul><div class="clear"></div></li>';
				}
				
				$('#div_summary_detail').html(cases_result + '</ul></div>');

				if (data.serverResponse.hasOwnProperty('msdHtml')) {
					$('#max_service_date').html(data.serverResponse.msdHtml);
				}
			} else {
				
			}
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_visits_table() {
	var jj = cases_account_id;
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_visits_summary_table',
		dataType: 'html',
		data: {
			sAccountID: cases_account[jj].account,
			sDbName: cases_account[jj].db_name,
			sPractice: cases_account[jj].practice,
			sPatient: cases_account[jj].patient,
			sCaseNo: cases_account[jj].case_no
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				$('#btn_visits_all_details_div').show();
				$('#div_visits_summary_detail').show();
				$('#header_title').html('Visits Summary');
							
				var cases_result = '<div class="tableHeader"><ul class="columns4"><li>Charges</li><li>Payments</li><li>Adjustments</li><li>Balance</li></ul><div class="clear"></div></div>';
				
				for (var i = 0; results_obj.Records.length > i; i++) {
					if (results_obj.Records[i].company != '') {
						if (results_obj.Records[i].charges == '') {
							cases_result += '<div class="tableBody"><ul class="rows">	<li class="minus-row"><div id="minus_' + i + '" class="minus3"></div>Company: '+ results_obj.Records[i].company + '</li>';
						} else {
							cases_result += '<li class="total"><strong>' + results_obj.Records[i].company + ':</strong>';
							cases_result += '<ul class="columns4"><li>$' + parseFloat(results_obj.Records[i].charges).toFixed(2) + '</li>';
                            cases_result += '<li ' + (results_obj.Records[i].payments < 0 ? ' class="negative-balance" ' : '') +'>- $' + parseFloat(results_obj.Records[i].payments).toFixed(2) + '</li>';
                            cases_result += '<li>$' + parseFloat(results_obj.Records[i].adjustments).toFixed(2) + '</li>';
                            cases_result += '<li>$' + parseFloat(results_obj.Records[i].balance).toFixed(2) + '</li></ul><div class="clear"></div></li></ul></div>';
						}
					} else {
						cases_result += '<li><div id="open_' + i + '" class="visit-summary-detail-closed plus3" data="{company: \'' + results_obj.Records[i].companyName + '\', dov: \'' + results_obj.Records[i].dov.date + '\', sequence: \'' +results_obj.Records[i].SequenceNo + '\'}"></div>Dov: ' + results_obj.Records[i].dov;
						cases_result += '<ul class="columns4"><li>$' + parseFloat(results_obj.Records[i].charges).toFixed(2) + '</li>';
						cases_result += '<li class="negative-balance">- $' + parseFloat(results_obj.Records[i].payments).toFixed(2) + '</li>';
						cases_result += '<li>$' + parseFloat(results_obj.Records[i].adjustments).toFixed(2) + '</li>';
						cases_result += '<li>$' + parseFloat(results_obj.Records[i].balance).toFixed(2) + '</li></ul><div class="clear"></div></li>';
						cases_result += '<div id="div_amount_open_' + i + '" class="amount" style="display: none">Amount</div><div class="tableBody" style="border-bottom: none; display: none" id="div_payment_open_' + i + '"></div>';
					}
				}
				
				$('#div_visits_summary_detail').html(cases_result);
			} else {
				
			}
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function get_visit_summary_detail_by_date(divId) {
//	alert($('td_' + trId).text());
	if ($('#div_payment_' + divId).html() == '') {
		var jj = cases_account_id;
//		console.log($('#' + trId + ' td:nth-child(2)').children('span').metadata().company);
		$.ajax({
				type: 'POST',
				async: true,
				url: baseURL + ajaxCONTROLLER + '/get_visits_summary_details_table',
				dataType: 'html',
				data: {
						sAccountID: cases_account[jj].account,
						sDbName: cases_account[jj].db_name,
						sPractice: cases_account[jj].practice,
						sPatient: cases_account[jj].patient,
						sCaseNo: cases_account[jj].case_no,
						sCompany: $('#' + divId).metadata().company,
						sDOV: $('#' + divId).metadata().dov,
						sSequence: $('#' + divId).metadata().sequence
				},
				success: function(data) {
					$('#div_payment_' + divId).html(data);
				},
				complete: function() {
				},
				error: function(data){
					// display Error message
					display_text_message('Error');
				}
		});
	}
}

function load_appointments_table() {
	var jj = cases_account_id;
	filter_drop_down = 'div_filter_dropdown_appointments';
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_appointments_table?jtSorting=' + sorting_field + '&jtPageSize='+count_appointments_on_page + '&jtStartIndex=' + (number_appointments_page-1)*count_appointments_on_page,
		dataType: 'html',
		data: {
			sAccountID: cases_account[jj].account,
			sDbName: cases_account[jj].db_name,
			sPractice: cases_account[jj].practice,
			sPatient: cases_account[jj].patient,
			sCaseNo: cases_account[jj].case_no,
			sortingFieldName: sFieldName,
			sortingValue: sValue,
			sortingQriteria: 'sorting-contains'
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				$('#case-summary-block').show();
				$('#div_appointments_detail').show();
				$('#header_title').html('Appointment History');
				$('#div_name_filter').show();
				$('#div_name_filter').removeClass('minus').removeClass('plus').addClass('plus');
							
				var app_result = '<div class="tableHeader" style="padding: 5px"><strong><label for="view-appointments">View Appointments</label></strong></div><div class="tableBody" style="border-bottom: none;"><ul class="rows">';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					app_result += '<li><ul class="columns2"><li><strong>Date:</strong> ' + results_obj.Records[i].date + '<br />';
                    app_result += '<strong>Provider:</strong> '  + results_obj.Records[i].provider + '<br />';
                    app_result += '<strong>Location:</strong> '  + results_obj.Records[i].location + '</li>';
                    app_result += '<li><strong>Time:</strong> ' + results_obj.Records[i].time + '<br />';
                    app_result += '<strong>Reason:</strong> ' + results_obj.Records[i].reason + '<br />';
                    app_result += '<strong>Status:</strong> ' + results_obj.Records[i].status + '</li></ul><div class="clear"></div></li>';
				}
				app_result +=  '</ul></div>';
				
				app_result += '<div class="paging"><div class="arrow-block">';
				if (results_obj.TotalRecordCount > count_appointments_on_page)
					if (number_appointments_page > 1) {
						app_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
					} else {
						app_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
					}
				
				app_result += '<div class="page-number">' + number_appointments_page + '</div>';
				
				if (results_obj.TotalRecordCount > count_appointments_on_page)
					if (number_appointments_page * count_appointments_on_page < results_obj.TotalRecordCount) {
						app_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
					} else {
						app_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
					}
				
				var count_pages = parseInt( results_obj.TotalRecordCount / count_appointments_on_page);
				if (results_obj.TotalRecordCount % count_appointments_on_page > 0) count_pages++;
				app_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
	//			app_result += '<option ' + (count_appointments_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
				app_result += '<option ' + (count_appointments_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
				app_result += '<option ' + (count_appointments_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
				app_result += '<option ' + (count_appointments_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
				app_result += '<option ' + (count_appointments_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
				app_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_appointments">' + count_pages + '</strong> pages</div><div class="clear"></div></div>';
				
				$('#div_appointments_detail').html(app_result);
			} else {
				
			}
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_documents_table() {
	var jj = cases_account_id;
	filter_drop_down = 'div_filter_dropdown_documents';
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_documents_table?jtSorting=' + sorting_field + '&jtPageSize=' + count_documents_on_page + '&jtStartIndex=' + (number_documents_page-1)*count_documents_on_page,
		dataType: 'html',
		data: {
			sAccountID: cases_account[jj].account,
			sDbName: cases_account[jj].db_name,
			sPractice: cases_account[jj].practice,
			sPatient: cases_account[jj].patient,
			sCaseNo: cases_account[jj].case_no
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				$('#case-summary-block').show();
				$('#div_documents_detail').show();
				$('#header_title').html('Case Documents');
				$('#div_name_filter').show();
				$('#div_name_filter').removeClass('minus').removeClass('plus').addClass('plus');
				$('#btn_documents_open_div').show();
				
				var count_pages = parseInt( results_obj.TotalRecordCount / count_documents_on_page);
				if (results_obj.TotalRecordCount % count_documents_on_page > 0) count_pages++;
							
				var doc_result = '<div class="tableHeader" style="padding: 5px"><div class="checkbox"><input id="document_checkbox_all" type="checkbox" value="1"  style="margin-left: 8px" /></div><div style="float: right;"><span id="count_pages_documents">' + results_obj.TotalRecordCount + '</span> documents found</div><div class="clear"></div></div><div class="tableBody" style="border-bottom: none;"><ul class="rows">';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					doc_result += '<li><ul class="columns3"><li><input id="document_checkbox_' + results_obj.Records[i].id + '" type="checkbox" class="case_dosuments_checkbox" name="document_checkbox[]" value="' + results_obj.Records[i].full_path + '"/></li><li><strong>Dos:</strong> ' + (results_obj.Records[i].date_of_service != '' && results_obj.Records[i].date_of_service != null ? results_obj.Records[i].date_of_service : '') + '<br /><strong>Document Type:</strong> ' + results_obj.Records[i].document_type + '<br /><strong>Document:</strong> ' + results_obj.Records[i].document_name + '</li>';
					doc_result += '<li><span id="open_document_' + results_obj.Records[i].id + '" style="text-decoration:underline; cursor:pointer" class="btn_document_open" data="{index: \'' + results_obj.Records[i].id + '\', full_path: \'' + results_obj.Records[i].full_path + '\'}">Open</li></ul><div class="clear"></div></li>';                   
				}
				doc_result +=  '</ul></div>';
				
				/*doc_result += '<div class="paging"><div class="arrow-block">';
				if (results_obj.TotalRecordCount > count_documents_on_page)
					if (number_documents_page > 1) {
						doc_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
					} else {
						doc_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
					}
				
				doc_result += '<div class="page-number">' + number_documents_page + '</div>';
				
				if (results_obj.TotalRecordCount > count_documents_on_page)
					if (number_documents_page * count_documents_on_page < results_obj.TotalRecordCount) {
						doc_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
					} else {
						doc_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
					}
				
				doc_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
	//			app_result += '<option ' + (count_appointments_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
				doc_result += '<option ' + (count_documents_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
				doc_result += '<option ' + (count_documents_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
				doc_result += '<option ' + (count_documents_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
				doc_result += '<option ' + (count_documents_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
				doc_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_documents">' + count_pages + '</strong> pages</div><div class="clear"></div></div>';*/
				
				$('#div_documents_detail').html(doc_result);
			} else {
				
			}
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_statements_table() {
		var jj = cases_account_id;		
		
		var url = baseURL + 'cases/statements/' + cases_account[jj].account + '/' + cases_account[jj].db_name + '/'
            + cases_account[jj].practice + '/' + cases_account[jj].patient + '/' + cases_account[jj].case_no + '/'
            + $('#client_cases_search_details').val()
			/*+ '/' + $('#client_cases_search_transactions').val()
			+ '/' + $('#statement_lob').val()
			+ '/' +	$('#statement_finance').val()*/;
		
		if ($('#checkbox_with_docs').is(':checked')) {
			url += '/1';
		}
		
		window.open(url, '_blank');
}

function load_discharge_report() {
	display_please_wait();
	filter_drop_down = 'div_filter_dropdown_discharge_report';
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_discharge_clients_table?jtSorting=' + sorting_field + '&jtPageSize=' + count_records_on_page + '&jtStartIndex=' + (number_discharge_report_page-1)*count_records_on_page,
		dataType: 'html',
		data: {	
			extAttyID: $('#discharge_attorneys_list').val(),
			sortingFieldName: sFieldName,
			sortingValue: sValue,
			sortingQriteria: 'sorting-contains'
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				$('#div_name_filter').show();
				$('#div_name_filter').removeClass('minus').removeClass('plus').addClass('plus');
				
				var count_pages = parseInt( results_obj.TotalRecordCount / count_records_on_page);
				if (results_obj.TotalRecordCount % count_records_on_page > 0) count_pages++;
							
				var doc_result = '<div class="tableHeader" style="height: 32px"></div><div class="tableBody" style="border-bottom: none;"><ul class="rows">';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					doc_result += '<li><ul class="columns2"><li><strong>Patient:</strong> ' + results_obj.Records[i].last_name + ' ' + results_obj.Records[i].first_name + ' ' + results_obj.Records[i].middle_name + '<br /><strong> Account:</strong> ' + results_obj.Records[i].account + '<br />';
					doc_result += '<strong> Class:</strong> ' + results_obj.Records[i].case_category + '<br /><strong>DOA:</strong> ' + (results_obj.Records[i].accident_date != '' && results_obj.Records[i].accident_date != null ? results_obj.Records[i].accident_date : '') + '</li>';
                    doc_result += '<li><strong>Discharge Date:</strong> ' + (results_obj.Records[i].discharge_date != '' && results_obj.Records[i].discharge_date != null ? results_obj.Records[i].discharge_date : '') + '<br />';
                    doc_result += '<strong>Status:</strong> ' + results_obj.Records[i].status + '<br /><span id="summary_' + i + '" style="text-decoration:underline; cursor:pointer" class="btn_discharge_report_summary" data="{index: \'' + i + '\'}">Summary</span></li></ul><div class="clear"></div></li>';                   
				}
				doc_result +=  '</ul></div>';
				
				doc_result += '<div class="paging"><div class="arrow-block">';
				if (results_obj.TotalRecordCount > count_records_on_page)
					if (number_discharge_report_page > 1) {
						doc_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
					} else {
						doc_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
					}
				
				doc_result += '<div class="page-number">' + number_discharge_report_page + '</div>';
				
				if (results_obj.TotalRecordCount > count_records_on_page)
					if (number_discharge_report_page * count_records_on_page < results_obj.TotalRecordCount) {
						doc_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
					} else {
						doc_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
					}
				
				doc_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
	//			app_result += '<option ' + (count_appointments_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
				doc_result += '<option ' + (count_records_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
				doc_result += '<option ' + (count_records_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
				doc_result += '<option ' + (count_records_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
				doc_result += '<option ' + (count_records_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
				doc_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_records">' + count_pages + '</strong> pages</div><div class="clear"></div></div>';
				
				cases_account = results_obj.Records;
				
				$('#div_discharge_report_detail').html(doc_result);
			} else {
				
			}
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_mileage_report() {
	display_please_wait();
	filter_drop_down = 'div_filter_dropdown_mileage_report';
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_mileage_report_table?jtSorting=' + sorting_field + '&jtPageSize=' + count_records_on_page + '&jtStartIndex=' + (number_mileage_report_page-1)*count_records_on_page,
		dataType: 'html',
		data: {
			sortingFieldName: sFieldName,
			sortingValue: sValue,
			sortingQriteria: 'sorting-contains'
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
//			console.log(data);
			
			if (results_obj.Result == 'OK') {
				$('#div_name_filter').show();
				$('#div_name_filter').removeClass('minus').removeClass('plus').addClass('plus');
				
				cases_account = new Array();
				cases_account = results_obj.Records;
				
				var count_pages = parseInt( results_obj.TotalRecordCount / count_records_on_page);
				if (results_obj.TotalRecordCount % count_records_on_page > 0) count_pages++;
							
				var doc_result = '<div class="tableHeader" style="height: 32px"></div><div class="tableBody" style="border-bottom: none;"><ul class="rows">';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					doc_result += '<li><ul class="columns2"><li><strong>Last Name: </strong>' + results_obj.Records[i].last_name + '<br /><strong>First Name: </strong> ' + results_obj.Records[i].first_name + '<br />';
                    doc_result += '<strong>Account: </strong> ' + results_obj.Records[i].account + '</li><li><strong>Class:</strong> ' + results_obj.Records[i].case_category + '<br />';
                    doc_result += '<strong>DOA:</strong> ' + (results_obj.Records[i].accident_date != '' && results_obj.Records[i].accident_date != null ? results_obj.Records[i].accident_date : '') + '<br /><input id="calculate_distance_' + i + '" type="button" class="round btn_calculate_distance_row" data="{index: \'' + i + '\', name: \'' + results_obj.Records[i].first_name + ' ' + results_obj.Records[i].last_name + '\'}" value="Calculate Distance" /></li></ul><div class="clear"></div></li>';
				}
				doc_result +=  '</ul></div>';
				
				doc_result += '<div class="paging"><div class="arrow-block">';
				if (results_obj.TotalRecordCount > count_records_on_page)
					if (number_mileage_report_page > 1) {
						doc_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
					} else {
						doc_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
					}
				
				doc_result += '<div class="page-number">' + number_mileage_report_page + '</div>';
				
				if (results_obj.TotalRecordCount > count_records_on_page)
					if (number_mileage_report_page * count_records_on_page < results_obj.TotalRecordCount) {
						doc_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
					} else {
						doc_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
					}
				
				doc_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
	//			app_result += '<option ' + (count_appointments_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
				doc_result += '<option ' + (count_records_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
				doc_result += '<option ' + (count_records_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
				doc_result += '<option ' + (count_records_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
				doc_result += '<option ' + (count_records_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
				doc_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_records">' + count_pages + '</strong> pages</div><div class="clear"></div></div>';
				
				$('#div_mileage_report_detail').html(doc_result);
			} else {
				
			}
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_calculate_distance(address_wish) {
	display_please_wait();
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_calculate_distance_table',
		dataType: 'html',
		data: {
			sAccountID: cases_account[calculate_account_id].account,
			sDbName: cases_account[calculate_account_id].db_name,
			sPractice: cases_account[calculate_account_id].practice,
			sPatient: cases_account[calculate_account_id].patient,
			sCaseNo: cases_account[calculate_account_id].case_no,
			customAddress : $('#custom_address').val(),
			typeDist : address_wish
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK' && results_obj.Records.length > 0) {
				$('#bottomUp').dialog('option', 'height', 640);
				$('#bottomUp .tableHeader').show();
				$('#bottomUp .tableBody').show();
				var dist_result = '<ul class="rows">';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					dist_result += '<li><ul class="columns2"><li><strong>Date:</strong> ' + (results_obj.Records[i].date != '' && results_obj.Records[i].date != null ? results_obj.Records[i].date : '') + '<br /><strong>Provider:</strong>  ' + results_obj.Records[i].provider + '<br />';
                    dist_result += '<strong>Location:</strong> ' + results_obj.Records[i].location + '</li><li><strong>Time:</strong> ' + (results_obj.Records[i].time != '' && results_obj.Records[i].time != null ? results_obj.Records[i].time : '') + '<br />';
                    dist_result += '<strong>Reason:</strong> ' + results_obj.Records[i].reason + '<br /><strong>Distance:</strong> ' + results_obj.Records[i].distance + '</li></ul><div class="clear"></div></li>';
				}
				dist_result += '</ul>';
				
				$('#div_calculate_distance').html(dist_result);
			} else {
				display_text_message('No appointments is found.', 600, 300);
			}
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_notifications_table() {
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_notifications_table?jtSorting=created%20DESC&jtPageSize=' + count_records_on_page + '&jtStartIndex=' + (number_notifications_page-1)*count_records_on_page,
		dataType: 'html',
		data: {
			sortingFieldName: sField
		},
		success: function(data) {
			var results_obj = jQuery.parseJSON(data);
			
			if (results_obj.Result == 'OK') {
				var count_pages = parseInt( results_obj.TotalRecordCount / count_records_on_page);
				if (results_obj.TotalRecordCount % count_records_on_page > 0) count_pages++;
							
				var not_result = '<div class="roundbox-header header1">' + results_obj.TotalRecordNew + ' New Notifications</div>';
				
				for (var i = 0; i < results_obj.Records.length; i++) {
					if (results_obj.Records[i]['read'] == 1) var is_read = 'normal';
					else var is_read = 'bold';
					not_result += '<div class="notification icon_' + results_obj.Records[i]['type'] + '"><h2 style="font-weight:'+
					is_read +'">' + 
					results_obj.Records[i]['title'] + '</h2><p>' + results_obj.Records[i]['body'] + '</p></div>';       
					//<img class="delete_notification" src="/images/mobile/delete-grey.png" data="{id: ' + results_obj.Records[i]['id'] + '}"/>
				}
				
				not_result += '<div class="paging">';
				if (results_obj.TotalRecordCount > 0) {
					not_result += '<div class="arrow-block">';
					if (results_obj.TotalRecordCount > count_records_on_page)
						if (number_notifications_page > 1) {
							not_result += '<div class="arrow-first"></div><div class="arrow-back"></div>';
						} else {
							not_result += '<div class="arrow-first" style="cursor:none" ></div><div class="arrow-back" style="cursor:none"></div>';
						}
					
					not_result += '<div class="page-number">' + number_notifications_page + '</div>';
					
					if (results_obj.TotalRecordCount > count_records_on_page)
						if (number_notifications_page * count_records_on_page < results_obj.TotalRecordCount) {
							not_result += '<div class="arrow-next"></div><div class="arrow-last"></div>';
						} else {
							not_result += '<div class="arrow-next" style="cursor:none"></div><div class="arrow-last" style="cursor:none"></div>';
						}
					
						not_result += '</div><div class="page-size"><label for="page-size">Page size:</label> <select id="page-size">';
			//			not_result += '<option ' + (count_records_on_page == 2 ? 'selected="selected"' : '') + '>2</option>';
						not_result += '<option ' + (count_records_on_page == 10 ? 'selected="selected"' : '') + '>10</option>';
						not_result += '<option ' + (count_records_on_page == 25 ? 'selected="selected"' : '') + '>25</option>';
						not_result += '<option ' + (count_records_on_page == 50 ? 'selected="selected"' : '') + '>50</option>';
						not_result += '<option ' + (count_records_on_page == 100 ? 'selected="selected"' : '') + '>100</option>';
						not_result += '</select><br /><strong>'  + results_obj.TotalRecordCount + '</strong> items in <strong id="count_pages_records">' + count_pages + '</strong> pages</div>';
					}
					not_result += '<div class="clear"></div></div>';
				
					$('#div_notifications_detail').html(not_result);
			} else {
				
			}
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

$.assocArraySize = function(obj) {
    // http://stackoverflow.com/a/6700/11236
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};