// JavaScript Document

var reloadTransactionsDate = true;

$(function(){
	
	display_text_message('', 1, 1);
	close_text_message();
	
	//$( document ).tooltip({show: {effect: "slideDown",delay: 250}});
	
	$('#btn_client_cases_search_clear').live('click', function() {
		var $radio = $('input[type="radio"]');

		$('input[type="text"]').val('');
		$radio.attr('checked', false);
		$radio.each(function(){
			if ($(this).val() == 'accident' || $(this).val() == 'my')	$(this).attr('checked', true);
		});
			$('#client_cases_search_firms').val('').trigger('change');
	});
	
	$('#cases_client_checkbox_all').live('click', function() {
		if ($(this).is(':checked')) {
			$('[id*=cases_client_checkbox_]').attr('checked', true).parents('tr').addClass('case-documents-list-selected');
			$('#cases_client_checkbox_all').parents('tr').removeClass('case-documents-list-selected');
			$('#btn_cases_summary').removeClass('opacity25');
            setStatementStatus(true)
		} else {
			$('[id*=cases_client_checkbox_]').attr('checked', false).parents('tr').removeClass('case-documents-list-selected');
			$('#btn_cases_summary').removeClass('opacity25').addClass('opacity25');
            setStatementStatus(false);
		}
	});
	
	$('#cases_managers_checkbox_all').live('click', function() {
		if ($(this).is(':checked')) {
			$('[id*=cases_managers_checkbox_]').attr('checked', true).parents('tr').addClass('case-documents-list-selected');
			$('#cases_managers_checkbox_all').parents('tr').removeClass('case-documents-list-selected');
			if (cases_manager_type != 'all')
				$('#btn_cases_' +  (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).removeClass('opacity25');
		} else {
			$('[id*=cases_managers_checkbox_]').attr('checked', false).parents('tr').removeClass('case-documents-list-selected');
			if (cases_manager_type != 'all')
				$('#btn_cases_' +  (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).removeClass('opacity25').addClass('opacity25');
		}
	});
	
	$('#btn_cases_summary').live('click', function() {
	    var $checked = $('[id*=cases_client_checkbox_]:checked');

		if ($checked.length > 0) {
			sCases = [];
			$checked.each(function() {
				if ($(this).attr('id') != 'cases_client_checkbox_all')
					sCases.push($(this).metadata());
			});
			
			go_to_cases_summary(sCases);
		}
	});
	
	$('#btn_cases_assigned, #btn_cases_unassigned').live('click', function() {
		if ($('[id*=cases_managers_checkbox_]:checked').length > 0) {
			sCases = new Array;
			$('[id*=cases_managers_checkbox_]:checked').each(function() {
				if ($(this).attr('id') != 'cases_managers_checkbox_all')
					sCases.push($(this).metadata());
			});
			cases_managers(sCases);
		}
	});
	
	$('.btn_cases_assigned, .btn_cases_unassigned').live('click', function() {
		sCases = new Array;
		sCases.push($(this).metadata());
		cases_managers(sCases);
	});
	
	$('.btn_cases_search_summary, .new_case_select').live('click', function() {
		sCases = new Array;
		sCases.push($(this).metadata());
		if ($('.fnResults').length) $('.fnResults').show();
		go_to_cases_summary(sCases);
	});
	
	$('.btn_discharge_report_summary').live('click', function() {
		sCases = new Array;
		sCases.push($(this).metadata());
		if ($('.fnResults').length) $('.fnResults').show();
		
		$('#discharge_sort_by_attorney').hide();
		$('#btn_export_to_word2').hide();
		$('#btn_export_to_excel2').hide();
		$('table.jtable tr#discharge-search').remove();
		$('#discharge-table-container').hide();
		
		$('#header_cases_summary_div').show();
		$('#btn_client_cases_back_to_report').show();
		$('#btn_client_cases_back_to_report_left').show();
		$('#cases_search_tabs_summary').show();
		$('#tabs_discharge li').each(function(index, element) {
			$(this).removeClass('tabs-state-active');
		});
		$('#li-discharge-summary').addClass('tabs-state-active');
		$('#box_client_case_detail').show();
		$('#summary-table-container').show();
		
		go_to_cases_summary(sCases);
	});
	
	$('#btn_client_cases_back_to_report').live('click', function(e) {
		$('#header_cases_summary_div').hide();
		$('#btn_client_cases_back_to_report').hide();
		$('#btn_client_cases_back_to_report_left').hide();
		$('#box_client_case_detail').hide();
		$('#summary-table-container').hide();
		$('#btn_visits_all_details_div').hide();
		$('#btn_documents_open_div').hide();
		$('#visits-table-container').hide();
		$('#appointments-table-container').hide();
		$('#documents-table-container').hide();
		$('#contact-form-container').hide();
		$('#client_cases_search_details').prop('disabled', true);
		$('#client_cases_search_transactions').prop('disabled', true);
		$('#checkbox_with_docs').prop('disabled', true);
		$('#cases_search_tabs_summary').hide();
		$('#tabs_discharge li').each(function(index, element) {
            $(this).removeClass('tabs-state-active');
        });
		$('#li-cases-search').addClass('tabs-state-active');
		if ($('#btn_client_cases_first').addClass('orange-button-text-white')) {
			$('#btn_client_cases_first').removeClass('orange-button-text-white');
			$('#btn_client_cases_first').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_prev').addClass('orange-button-text-white')) {
			$('#btn_client_cases_prev').removeClass('orange-button-text-white');
			$('#btn_client_cases_prev').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_next').addClass('orange-button-text-white')) {
			$('#btn_client_cases_next').removeClass('orange-button-text-white');
			$('#btn_client_cases_next').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_last').addClass('orange-button-text-white')) {
			$('#btn_client_cases_last').removeClass('orange-button-text-white');
			$('#btn_client_cases_last').addClass('orange-button-text-grey');
		}
		$('.right_menu_ctr').html('Discharge Report & Client List');
		tableID = 'Discharge';
		tableName = 'discharge';
		dbName = 'discharge';
		cases_account_id = -1;
		$('#summary_text_div').hide();
		
		$('#discharge_sort_by_attorney').show();
		$('#btn_export_to_word2').show();
		$('#btn_export_to_excel2').show();
		window['append'+tableID+'SearchBar']();
		$('#discharge-table-container').show();
		$('#discharge-table-container div.jtable-column-header-container').each(function(index, element) {
			var spanWidth = parseInt($(this).children('span').css('width')) + 10;
			$(this).css('background-position', spanWidth+'px');
		});
	});
	
	$('.case_managers_checkbox').live('click', function() {
		var nameButton = 'assigned';
		if (cases_manager_type == 'assigned') {
			nameButton = 'unassigned';
		}
		if ($('[id*=cases_managers_checkbox_]:checked').length > 0) {
			$('#btn_case_' + nameButton).attr('disabled', 'false');
			$('#btn_cases_' + nameButton).removeClass('opacity25');
		} else {
			$('#btn_case_' + nameButton).attr('disabled', 'disabled');
			$('#btn_cases_' + nameButton).removeClass('opacity25').addClass('opacity25');
		}
	});
	
	$('#document_checkbox_all').live('click', function() {
		if ($('#document_checkbox_all').is(':checked')) {
			$('[id*=document_checkbox_]').attr('checked', true).parents('tr').addClass('case-documents-list-selected');
			$('#document_checkbox_all').parents('tr').removeClass('case-documents-list-selected');
		} else {
			$('[id*=document_checkbox_]').attr('checked', false).parents('tr').removeClass('case-documents-list-selected');
		}
	});
	
	$('.case_dosuments_checkbox').live('click', function() {
		if ($(this).is(':checked')) {
			$(this).parents('tr').addClass('case-documents-list-selected');
		} else {
			$(this).parents('tr').removeClass('case-documents-list-selected');
		}
	});
	
	$('.case_clients_checkbox').live('click', function() {
		if ($(this).is(':checked')) {
			$(this).parents('tr').addClass('case-documents-list-selected');
		} else {
			$(this).parents('tr').removeClass('case-documents-list-selected');
		}
		if ($('[id*=cases_client_checkbox_]:checked').length > 0) {
			$('#btn_cases_summary').removeClass('opacity25');
            setStatementStatus(true);
		} else {
			$('#btn_cases_summary').removeClass('opacity25').addClass('opacity25');
            setStatementStatus(false);
		}
	});
	
	$('.btn_document_open').live('click', function() {
		checkId = $(this).metadata().index;
		pageId = $(this).metadata().pageID;
		$('input[name="document_checkbox[]"]').attr('checked', false);
		$('#document_checkbox_' + checkId + '_' + pageId).attr('checked', true);
		$('#document_open_form').submit();
	});
	
	$('.fnOpenNotifiedDoc').live('click', function(e) {
		e.preventDefault();
		var path = $(this).attr('href');
		$('#document_open_form input[name="document_checkbox[]"]').val(path);
		$('#document_open_form').submit();
	});
	
	$('#btn_client_cases_back_to_results, .fnResults').live('click', function(e) {
		$('#header_cases_search_div').show();
		$('#header_cases_summary_div').hide();
		$('#btn_client_cases_advanced_search').show();
        if (userRole == 'Biller') {
            $('#btn_client_cases_advanced_search').trigger('click');
        }
		$('#btn_client_cases_back_to_results').hide();
		$('#box_client_search').show();
		$('#btn_cases_summary_div').show();
		$('#box_client_case_detail').hide();
		$('#cases-table-container').show();
		$('#summary-table-container').hide();
		$('#btn_visits_all_details_div').hide();
		$('#btn_documents_open_div').hide();
		$('#visits-table-container').hide();
		$('#appointments-table-container').hide();
		$('#documents-table-container').hide();
		$('#contact-form-container').hide();
		$('#client_cases_search_details').prop('disabled', true);
        $('#client_cases_search_transactions').prop('disabled', true);
		$('#checkbox_with_docs').prop('disabled', true);
		$('.tabs_cases li').each(function(index, element) {
            $(this).removeClass('tabs-state-active');
			if ($(this).attr('id') != 'li-cases-search')
				$(this).addClass('ui-state-disabled');
        });
		$('#li-cases-search').addClass('tabs-state-active');

        setBulkLOB();

		if ($('#btn_client_cases_first').addClass('orange-button-text-white')) {
			$('#btn_client_cases_first').removeClass('orange-button-text-white');
			$('#btn_client_cases_first').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_prev').addClass('orange-button-text-white')) {
			$('#btn_client_cases_prev').removeClass('orange-button-text-white');
			$('#btn_client_cases_prev').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_next').addClass('orange-button-text-white')) {
			$('#btn_client_cases_next').removeClass('orange-button-text-white');
			$('#btn_client_cases_next').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_last').addClass('orange-button-text-white')) {
			$('#btn_client_cases_last').removeClass('orange-button-text-white');
			$('#btn_client_cases_last').addClass('orange-button-text-grey');
		}
		$('#cases-table-container div.jtable-column-header-container').each(function(index, element) {
			var spanWidth = parseInt($(this).children('span').css('width')) + 10;
			$(this).css('background-position', spanWidth+'px');
		});
		$('.right_menu_ctr').val('Search Cases');
		tableName = 'cases';
		dbName = 'cases';
		cases_account_id = -1;
		$('#summary_text_div').hide();
	});

	$('.fnResults').live('click', function(e) {
		e.preventDefault();
		$(this).hide();
		$('#header_cases_search_div').show();
		$('#header_cases_summary_div').hide();
		$('#btn_client_cases_advanced_search').show();
		$('#btn_client_cases_back_to_results').hide();
		$('#box_client_search').show();
		$('#btn_cases_summary_div').show();
		$('#box_client_case_detail').hide();
		$('#cases-new-table-container').show();
		$('#summary-table-container').hide();
		$('#btn_visits_all_details_div').hide();
		$('#btn_documents_open_div').hide();
		$('#visits-table-container').hide();
		$('#appointments-table-container').hide();
		$('#documents-table-container').hide();
        $('#contact-form-container').hide();
		$('#client_cases_search_details').prop('disabled', true);
        $('#client_cases_search_transactions').prop('disabled', true);
		$('#checkbox_with_docs').prop('disabled', true);
		$('.tabs_cases li').each(function(index, element) {
            $(this).removeClass('tabs-state-active');
			if ($(this).attr('id') != 'li-cases-search')
				$(this).addClass('ui-state-disabled');
        });
		$('#li-cases-search').addClass('tabs-state-active');

        setBulkLOB();

		if ($('#btn_client_cases_first').addClass('orange-button-text-white')) {
			$('#btn_client_cases_first').removeClass('orange-button-text-white');
			$('#btn_client_cases_first').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_prev').addClass('orange-button-text-white')) {
			$('#btn_client_cases_prev').removeClass('orange-button-text-white');
			$('#btn_client_cases_prev').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_next').addClass('orange-button-text-white')) {
			$('#btn_client_cases_next').removeClass('orange-button-text-white');
			$('#btn_client_cases_next').addClass('orange-button-text-grey');
		}
		if ($('#btn_client_cases_last').addClass('orange-button-text-white')) {
			$('#btn_client_cases_last').removeClass('orange-button-text-white');
			$('#btn_client_cases_last').addClass('orange-button-text-grey');
		}
		$('#cases-table-container div.jtable-column-header-container').each(function(index, element) {
			var spanWidth = parseInt($(this).children('span').css('width')) + 10;
			$(this).css('background-position', spanWidth+'px');
		});
		$('.right_menu_ctr').val('Search Cases');
		tableName = 'cases-new';
		dbName = 'cases-new';
		cases_account_id = -1;
		$('#summary_text_div').hide();
	});
	
	$('#close_advanced_search').live('click', function() {
			$('.add_option_advaced_search').slideUp();
			$('#client_cases_date_from').val('');
			$('#client_cases_date_to').val('');
			$('input[type="radio"]').attr('checked', false);
			$('input[type="radio"]').each(function(){ 
				if ($(this).val() == 'accident' || $(this).val() == 'my')	$(this).attr('checked', true);
			});
			$('#client_cases_category_like').val('');
			$('#client_cases_search_firms').val('');
			$('#client_cases_search_firms').trigger('change');
	});
	
	$('#btn_client_cases_advanced_search').live('click', function() {
			$('.add_option_advaced_search').slideDown();
	});
	
	$('#client_cases_show_unselected').live('change', function() {
			 if($('input[name=client_cases_show_unselected]').is(':checked')==false){
				 $('#div_attorneys_list input:checkbox').not(':checked').parent('div').hide();
			} else {
				$('#div_attorneys_list input:checkbox').not(':checked').parent('div').show();
			}
	});
			
	$('#firms-attorneys-tree').treeview({
		collapsed: true
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
			$('#select_marketer').val('').hide();
			$('#fileupload_container').html('');
			$('#contact_us input[type="file"]').val('');
	});

    var iframe;
    var ajaxForm = function($form, on_complete) {
        if (!$form.attr('target')) {
            //create a unique iframe for the form
            iframe = $("<iframe></iframe>").attr('name', 'ajax_form_' + Math.floor(Math.random() * 999999)).hide().appendTo($('body'));
            $form.attr('target', iframe.attr('name'));

            $form.append('<input type="hidden" name="isAjax" value="1">');
        }

        $form.submit();

        if (on_complete) {
            iframe = iframe || $('iframe[name=" ' + $form.attr('target') + ' "]');
            iframe.load(function () {
                //get the server response
                var response = iframe.contents().find('body').text();
                window[on_complete](response);
            });
        }
    };

	$('#btn_contact_us_submit').live('click', function() {
        if ( $('#name').val() == '' ) {
            display_text_message('Please enter valid \'Your Name\'.', 320, 150);
            return;
        }
        if (! validateValueByPattern($('#email').val(), emailPattern) ) {
            display_text_message('Please enter valid \'Your Email\'.', 320, 150);
            return;
        }

        var ccTo = $('#cc_to');
        if ( ccTo.val() != '') {
            var emails = ccTo.val().split(',');
            var new_emails = '';
            for (var i = 0; i < emails.length; i++) {
                emails[i] = $.trim(emails[i]);
                if ( ! validateValueByPattern(emails[i], emailPattern) ) {
                    display_text_message('Please enter valid emails in \'CC  To\' .', 320, 150);
                    return;
                }
                if (i > 0) new_emails += ", ";
                new_emails += emails[i];
            }
            ccTo.val(new_emails);
        }
        if ( $('#body').val() == '' ) {
            display_text_message('Please enter valid \'Your Inquiry \'.', 320, 150);
            return;
        }

        display_please_wait();

        if ($('#contact-form-container').length) {
            ajaxForm($('#contact_us'), 'contactComplete');
        } else {
            $('#contact_us').submit();
        }
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
		
	$('#btn_export_to_excel, #btn_export_to_excel2').on('click', function() {
		export_to_file('xls');
	});
	
	$('#btn_export_to_word, #btn_export_to_word2').on('click', function() {
		export_to_file('doc');
	});
		
	$('#btn_portal_settings_cancel').live('click', function() {
		$(location).attr('href', $(location).attr('href'));
	});
		
	$('input').on('focus', function(){
		$(this).removeClass('input_error');
	});
	
	$('#btn_portal_settings_submit').live('click', function() {
		var errors = [];
		/*if ( $('#portal_settings #server_url').val() == '' ) {
			$('#portal_settings #server_url').addClass('input_error');
			errors.push('Please enter \'Server URL\'.');
		}
		if ( $('#portal_settings #username').val() == '' ) {
			$('#portal_settings #username').addClass('input_error');
			errors.push('Please enter valid \'User Name\'.');
		}
		if ( $('#portal_settings #password').val() == '' ) {
			$('#portal_settings #password').addClass('input_error');
			errors.push('Please enter valid \'Password\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_from').val(), emailPattern) ) {
			$('#portal_settings #email_from').addClass('input_error');
			errors.push('Please enter valid \'Email From\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_administrator').val(), emailPattern) ) {
			$('#portal_settings #email_administrator').addClass('input_error');
			errors.push('Please enter valid \'AR Administrator Email\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_scheduling').val(), emailPattern) ) {
			$('#portal_settings #email_scheduling').addClass('input_error');
			errors.push('Please enter valid \'Email for Scheduling\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_settlements').val(), emailPattern) ) {
			$('#portal_settings #email_settlements').addClass('input_error');
			errors.push('Please enter valid \'Email for Settlements\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_patient_registration').val(), emailPattern) ) {
			$('#portal_settings #email_patient_registration').addClass('input_error');
			errors.push('Please enter valid \'Email for Patient Registration\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_it_contact').val(), emailPattern) ) {
			$('#portal_settings #email_it_contact').addClass('input_error');
			errors.push('Please enter valid \'Email for IT Contact\'.');
		}
		if (! validateValueByPattern($('#portal_settings #email_marketing_distribution_list').val(), emailPattern) ) {
			$('#portal_settings #email_marketing_distribution_list').addClass('input_error');
			errors.push('Please enter valid \'Email for Marketing Distribution List\'.');
		}
		if (! validateValueByPattern( $('#portal_settings #server_port').val(), integerPattern ) ) {
			$('#portal_settings #server_port').addClass('input_error');
			errors.push('Please enter numeric \'Server Port\'.');
		}
		if (! validateValueByPattern( $('#portal_settings #failed_password_attempt_count').val(), integerPattern ) ) {
			$('#portal_settings #failed_password_attempt_count').addClass('input_error');
			errors.push('Please enter numeric \'How many times can user try to log in before they get locked out?\'.');
		}*/
		if (errors.length > 0)
		{
			var msg = '';
			for (i = 0; i < errors.length; ++i)
			{
				msg += errors[i]+'<br />'
			}
			display_text_message('<strong>Errors occured:</strong><br /><br />'+msg, 400, 30*errors.length)
			return;
		}
		$('#portal_settings').submit();
	});
	
	$('#btn_client_cases_select_all').live('click', function() {
		$('#div_attorneys_list input[type="checkbox"]').attr('checked', true);
	});
	
	$('#btn_client_cases_unselect_all').live('click', function() {
		$('#div_attorneys_list input[type="checkbox"]').attr('checked', false);
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

        if (userRole == 'Biller' && $('input:radio[name=client_cases_attorneys]:checked').val() == 'all') {
            $('#btn_client_cases_select_all, #btn_client_cases_unselect_all').prop('disabled', true);
        } else {
            $('#btn_client_cases_select_all, #btn_client_cases_unselect_all').prop('disabled', false);
        }
	});

	//$('input:radio[name=client_cases_attorneys]').trigger('change');

    $('#cases_activity_type').live('change', function() {
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/process_update_legal_user',
            dataType: 'html',
            data: {data: {cases_search_cases_type: $('#cases_activity_type').val()}},
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
	
	$( "#client_cases_dob" ).datepicker({
		defaultDate: "+1w",
		changeYear: true,
		yearRange: "c-100:c",
		changeMonth: true,
		numberOfMonths: 1,
		prevText : '',
		nextText: '',
		onClose: function( selectedDate ) {
			$( "#client_cases_dob" ).datepicker( "option", "minDate", selectedDate );
		}
    });
	
	$( "#dob" ).datepicker({
		defaultDate: "+1w",
		changeYear: true,
		yearRange: "c-100:c",
		changeMonth: true,
		numberOfMonths: 1,
		prevText : '',
		nextText: '',
		onClose: function( selectedDate ) {
			$( "#dob" ).datepicker( "option", "minDate", selectedDate );
		}
    });
	
	$( "#doa" ).datepicker({
		defaultDate: "+1w",
		changeYear: true,
		yearRange: "c-100:c",
		changeMonth: true,
		numberOfMonths: 1,
		prevText : '',
		nextText: '',
		onClose: function( selectedDate ) {
			$( "#doa" ).datepicker( "option", "minDate", selectedDate );
		}
    });
	
	$( "#appt_date" ).datepicker({
		defaultDate: "+1w",
		changeYear: true,
		yearRange: "c-100:c",
		changeMonth: true,
		numberOfMonths: 1,
		prevText : '',
		nextText: '',
		onClose: function( selectedDate ) {
			$( "#appt_date" ).datepicker( "option", "minDate", selectedDate );
		}
    });
	
	$( "#new_cases_dob" ).datepicker({
		defaultDate: "+1w",
		changeYear: true,
		yearRange: "c-100:c",
		changeMonth: true,
		numberOfMonths: 1,
		prevText : '',
		nextText: '',
    });
	
	$('.orange-button-text-white').live('click', function() {
		if ($(this).attr('id') == 'btn_client_cases_search' 
			|| $(this).attr('id') == 'btn_client_cases_search_clear' 
			|| $(this).attr('id') == 'btn_activity_log_search_clear' 
			|| $(this).attr('id') == 'btn_activity_log_search' 
			|| $(this).attr('id') == 'btn_client_cases_new_search') return;
		var currentElm = $('#client_cases_list option:selected');
		if ($(this).attr('id') == 'btn_client_cases_first') {
			$('#btn_client_cases_first').removeClass('orange-button-text-white');
			$('#btn_client_cases_first').addClass('orange-button-text-grey');
			$('#btn_client_cases_prev').removeClass('orange-button-text-white');
			$('#btn_client_cases_prev').addClass('orange-button-text-grey');
			if ($('#btn_client_cases_next').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_next').removeClass('orange-button-text-grey');
				$('#btn_client_cases_next').addClass('orange-button-text-white');
				$('#btn_client_cases_last').removeClass('orange-button-text-grey');
				$('#btn_client_cases_last').addClass('orange-button-text-white');
			}
			currentElm = $('#client_cases_list :first');
		} else if ($(this).attr('id') == 'btn_client_cases_prev') {
			currentElm = $('#client_cases_list option:selected').prev().attr('selected', 'selected');
			if ($('#client_cases_list :first').val() == currentElm.val()) {
				$('#btn_client_cases_first').removeClass('orange-button-text-white');
				$('#btn_client_cases_first').addClass('orange-button-text-grey');
				$('#btn_client_cases_prev').removeClass('orange-button-text-white');
				$('#btn_client_cases_prev').addClass('orange-button-text-grey');
			}
			if ($('#btn_client_cases_next').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_next').removeClass('orange-button-text-grey');
				$('#btn_client_cases_next').addClass('orange-button-text-white');
				$('#btn_client_cases_last').removeClass('orange-button-text-grey');
				$('#btn_client_cases_last').addClass('orange-button-text-white');
			}
		} else if ($(this).attr('id') == 'btn_client_cases_next') {
			currentElm = $('#client_cases_list option:selected').next().attr('selected', 'selected');
			if ($('#client_cases_list :last').val() == currentElm.val()) {
				$('#btn_client_cases_last').removeClass('orange-button-text-white');
				$('#btn_client_cases_last').addClass('orange-button-text-grey');
				$('#btn_client_cases_next').removeClass('orange-button-text-white');
				$('#btn_client_cases_next').addClass('orange-button-text-grey');
			}
			if ($('#btn_client_cases_prev').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_prev').removeClass('orange-button-text-grey');
				$('#btn_client_cases_prev').addClass('orange-button-text-white');
				$('#btn_client_cases_first').removeClass('orange-button-text-grey');
				$('#btn_client_cases_first').addClass('orange-button-text-white');
			}
		} else if ($(this).attr('id') == 'btn_client_cases_last') {
			if ($('#btn_client_cases_first').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_first').removeClass('orange-button-text-grey');
				$('#btn_client_cases_first').addClass('orange-button-text-white');
				$('#btn_client_cases_prev').removeClass('orange-button-text-grey');
				$('#btn_client_cases_prev').addClass('orange-button-text-white');
			}
			$('#btn_client_cases_next').removeClass('orange-button-text-white');
			$('#btn_client_cases_next').addClass('orange-button-text-grey');
			$('#btn_client_cases_last').removeClass('orange-button-text-white');
			$('#btn_client_cases_last').addClass('orange-button-text-grey');
			currentElm = $('#client_cases_list :last');
		}
		$('#client_cases_list').val(currentElm.val());
		set_values_by_cases_id(currentElm.val());
		cases_account_id = currentElm.val();
		if ($('#li-cases-summary').hasClass('tabs-state-active')) {
			load_summary_table();
		}
		if ($('#li-cases-appointments').hasClass('tabs-state-active')) {
			load_appointments_table();
		}
		if ($('#li-cases-visits').hasClass('tabs-state-active')) {
			load_visits_table();
		}
		if ($('#li-cases-documents').hasClass('tabs-state-active')) {
			load_documents_table();
		}
	});
	
	$('#client_cases_list').live('change', function() {
		var currentElm = $('#client_cases_list option:selected');
		cases_account_id = currentElm.val(); 
		if ($('#client_cases_list :first').val() == currentElm.val()) {
			$('#btn_client_cases_first').removeClass('orange-button-text-white');
			$('#btn_client_cases_first').addClass('orange-button-text-grey');
			$('#btn_client_cases_prev').removeClass('orange-button-text-white');
			$('#btn_client_cases_prev').addClass('orange-button-text-grey');
			if ($('#btn_client_cases_next').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_next').removeClass('orange-button-text-grey');
				$('#btn_client_cases_next').addClass('orange-button-text-white');
				$('#btn_client_cases_last').removeClass('orange-button-text-grey');
				$('#btn_client_cases_last').addClass('orange-button-text-white');
			}
		} else if ($('#client_cases_list :last').val() == currentElm.val()) {
			if ($('#btn_client_cases_prev').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_first').removeClass('orange-button-text-grey');
				$('#btn_client_cases_first').addClass('orange-button-text-white');
				$('#btn_client_cases_prev').removeClass('orange-button-text-grey');
				$('#btn_client_cases_prev').addClass('orange-button-text-white');
			}
			$('#btn_client_cases_next').removeClass('orange-button-text-white');
			$('#btn_client_cases_next').addClass('orange-button-text-grey');
			$('#btn_client_cases_last').removeClass('orange-button-text-white');
			$('#btn_client_cases_last').addClass('orange-button-text-grey');
		} else {
			if ($('#btn_client_cases_next').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_next').removeClass('orange-button-text-grey');
				$('#btn_client_cases_next').addClass('orange-button-text-white');
				$('#btn_client_cases_last').removeClass('orange-button-text-grey');
				$('#btn_client_cases_last').addClass('orange-button-text-white');
			}
			if ($('#btn_client_cases_prev').hasClass('orange-button-text-grey')) {
				$('#btn_client_cases_first').removeClass('orange-button-text-grey');
				$('#btn_client_cases_first').addClass('orange-button-text-white');
				$('#btn_client_cases_prev').removeClass('orange-button-text-grey');
				$('#btn_client_cases_prev').addClass('orange-button-text-white');
			}
		}
		set_values_by_cases_id(cases_account_id);
		if ($('#li-cases-summary').hasClass('tabs-state-active')) {
			load_summary_table();
		}
		if ($('#li-cases-appointments').hasClass('tabs-state-active')) {
			load_appointments_table();
		}
		if ($('#li-cases-visits').hasClass('tabs-state-active')) {
			load_visits_table();
		}
		if ($('#li-cases-documents').hasClass('tabs-state-active')) {
			load_documents_table();
		}
	});
	
	$('.visit-summary-detail-closed').live('click', function() {
		$(this).removeClass('visit-summary-detail-closed');
		$(this).addClass('visit-summary-detail-open');
		var parent = $(this).parent('tr');
		if (parent.hasClass('visit-summary-detail2-name')) return;
		var tr_id = $(this).parent('tr').attr('id');
		$('#tr_' + tr_id).show();
		display_please_wait();
		get_visit_summary_detail_by_date(tr_id);
	});
	
	$('.visit-summary-detail-open').live('click', function() {
		$(this).removeClass('visit-summary-detail-open');
		$(this).addClass('visit-summary-detail-closed');
		$('#tr_' + $(this).parent('tr').attr('id')).hide();
	});
	
	$('.visit-summary-detail2-name .visit-summary-detail-open').live('click', function() {
		var parent = $(this).parent('tr');
		var next = parent;
		var flag = true;
		var i = 0;
		do {
			if (next.length == 0) flag = false;
			else
			{
				next = next.next('tr');
				if (next.length != 0)
				{
					if (next.hasClass('visit-summary-detail2-name')) flag = false;
					else
					{
						if (next.attr('id') && next.attr('id').substring(0,7) == 'payment')
						{
							next.children(':nth-child(2)').removeClass('visit-summary-detail-open').addClass('visit-summary-detail-closed');
						}
						next.hide();
						console.log(next);
						parent.css({
							"border-bottom-width":"1px",
							"border-bottom-style":"solid",
							"border-bottom-color":"grey"
						});
					}
				}
			}
			
		} while (flag)		
	});
	
	$('.visit-summary-detail2-name .visit-summary-detail-closed').live('click', function() {
		var parent = $(this).parent('tr');
		var next = parent;
		var flag = true;
		var i = 0;
		do {
			if (next.length == 0) flag = false;
			else
			{
				next = next.next('tr');
				if (next.length != 0)
				{
					if (next.hasClass('visit-summary-detail2-name')) flag = false;
					else
					{
						next.show();
						if (next.attr('id') && next.attr('id').substring(0,10) == 'tr_payment') next.hide();
						parent.css({
							"border-bottom":"none"
						});
					}
				}
			}
			
		} while (flag)		
	});
	
	$('#btn_expand_all_details').live('click', function() {
		$('.visit-summary-detail-closed').removeClass('visit-summary-detail-closed').addClass('visit-summary-detail-open');
		$('[id*="payment_"]').show();
		$('.visit-summary-detail2-total').show();
		$('.visit-summary-detail2-name').css({
			"border-bottom":"none"
		});
		var trSection = $.find('[id*="tr_payment_"]');
		display_please_wait();
		for (var i = 0; i < trSection.length; i++) {
			trSection[i].style.display = 'table-row';
			get_visit_summary_detail_by_date(trSection[i].id.substring(3));
		}
	});
	
	$('#btn_collapse_all_details').live('click', function() {
		$('.visit-summary-detail-open').each(function(index, element) {
			var parent = $(this).parent('tr');
			if (!parent.hasClass('visit-summary-detail2-name'))
			{
				$(this).removeClass('visit-summary-detail-open').addClass('visit-summary-detail-closed');
			}
		});
		var topTrSection = $.find('[id*="company_"]');
		for (var i = 0; i < topTrSection.length; i++) {
			//$('#' + topTrSection[i].id + ' td:first-child').removeClass('visit-summary-detail-closed').addClass('visit-summary-detail-open');
		}
		var trSection = $.find('[id*="tr_payment_"]');
		for (var i = 0; i < trSection.length; i++) {
			trSection[i].style.display = 'none';
		}
	});
	
	$.statementDownload = function(url, data, method) {
	    //url and data options required
        var statementUrl = data.statementUrl,
            statementSplit = data.statementSplit,
            dateType = data.dateType,
            dateFrom = data.dateFrom,
            dateTo = data.dateTo;

		if( url && statementUrl ) {
            var inputs = '';
            for (var i = 0; i < statementUrl.length; ++i) {
                //data can be string of parameters or array/object
                utl = typeof statementUrl[i] == 'string' ? statementUrl[i] : jQuery.param(statementUrl[i]);
                //split params into form inputs
                jQuery.each(statementUrl[i].split('&'), function () {
                    var pair = this.split('=');
                    inputs += '<input type="hidden" name="' + pair[0] + '[]" value="' + pair[1] + '" />';
                });
            }
            inputs += '<input type="hidden" name="statementSplit" value="' + statementSplit + '" />';
            inputs += '<input type="hidden" name="dateType" value="' + dateType + '" />';
            inputs += '<input type="hidden" name="dateFrom" value="' + dateFrom + '" />';
            inputs += '<input type="hidden" name="dateTo" value="' + dateTo + '" />';

			//console.log(inputs);
			//send request
            var $formId = randString(10);
			jQuery('<form id="'+$formId+'" action="'+ url +'" target="_blank" method="'+ (method||'post') +'" class="statement-form">'+inputs+'</form>').appendTo('body');
            $('#'+$formId).submit().remove();
		}
	};

    $.simpleDownload = function(url, data, method) {
        //url and data options required
        if( url && data ) {
            //data can be string of parameters or array/object
            data = typeof data == 'string' ? data : jQuery.param(data);
            //split params into form inputs
            var inputs = '';
            jQuery.each(data.split('&'), function(){
                var pair = this.split('=');
                inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />';
            });
            //console.log(inputs);
            //send request
            var $formId = randString(10);
            jQuery('<form id="'+$formId+'" action="'+ url +'" target="_blank" method="'+ (method||'post') +'" class="statement-form">'+inputs+'</form>').appendTo('body');
            $('#'+$formId).submit().remove();
        }
    };

	$('[name="callback_code"]').jqxMaskedInput({
		width: '38px', 
		height: '23px', 
		textAlign: 'center',
		mask: '###'
	});
	
	$('[name="callback_number"]').jqxMaskedInput({
		width: '65px', 
		height: '23px', 
		textAlign: 'center',
		mask: '####'
	});
	
	$('[name="callback_station_code"]').jqxMaskedInput({
		width: '38px', 
		height: '23px', 
		textAlign: 'center',
		mask: '###'
	});
	
	$('[name="callback_code"]').on('keyup', function() {
		if ($(this).val().indexOf('_') == -1 && $(this).caret() == 3)
		{
			$('[name="callback_station_code"]').focus().caretToStart();
		}
	});
	
	$('[name="callback_station_code"]').on('keyup', function() {
		if ($(this).val().indexOf('_') == -1 && $(this).caret() == 3)
		{
			$('[name="callback_number"]').focus().caretToStart();
		}
	});
		
	$('[name="callback_btn"]').live('click', function() {
		var code_phone = $('[name="callback_code"]').jqxMaskedInput('value');
		if (code_phone != null) code_phone = code_phone.replace(/_/g, '');
		
		var station_code = $('[name="callback_station_code"]').jqxMaskedInput('value');
		if (station_code != null) station_code = station_code.replace(/_/g, '');
		
		var number_phone = $('[name="callback_number"]').jqxMaskedInput('value');
		if (number_phone != null) number_phone = number_phone.replace(/_/g, '');

		if (code_phone != null && station_code != null && number_phone != null && 
			code_phone.length == 3 && station_code.length == 3 && number_phone.length == 4) 
		{
			display_please_wait();
			$.ajax({
				type: 'POST',
				async: true,
				url: baseURL + ajaxCONTROLLER + '/send_email_with_number_phone',
				dataType: 'html',
				data: {
						sCodePhone: code_phone,
						sStationCode: station_code,
						sNumberPhone: number_phone
				},
				success: function(data) {
					display_text_message(data, 400, 200);
				},
				complete: function() {
					$("#dialog-callback-form").dialog('close');
					close_please_wait();
				},
				error: function(data){
					// display Error message
					display_text_message('Error', 400, 200);
				}
			});
		} else {
			display_text_message('Please enter correct phone number.', 300, 200);
		}
	});
	
	$('#btn_client_cases_register_new').live('click', function(e) {
		e.preventDefault();
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_name" value="'+ $('#client_cases_name').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_ssn" value="'+ $('#client_cases_ssn').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_phone" value="'+ $('#client_cases_phone').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_dob" value="'+ $('#client_cases_dob').val() +'" />');
		$('#new_registration_cases_form').submit();
	});
	
	$('.btn_new_case_register').live('click', function(){
		
		var dataArray = new Array();
		dataArray.push($(this).metadata());
		var data = cases_account[dataArray[0].index];
		var inputs = '';
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_name" value="'+ $('#client_cases_name').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_ssn" value="'+ $('#client_cases_ssn').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_phone" value="'+ $('#client_cases_phone').val() +'" />');
		$('#new_registration_cases_form').append('<input type="hidden" name="client_cases_dob" value="'+ $('#client_cases_dob').val() +'" />');
		for (var key in data) {
			var val = data[key];
			if (key == 'dob' || key == 'accident_date')
			{
				val = data[key].date;
			}
			$('#new_registration_cases_form').append('<input type="hidden" name="'+ key +'" value="'+ val +'" />');
		}
		//$('#new_registration_cases_form').append(inputs);
		$('#new_registration_cases_form').submit();
	});
	
	$('#name').on('focus', function() {
		$('#name').css({
			'border-color':'#CFCFCF'
		});
	});
			
	$('#btn_send_new_case').on('click', function() {
		var data = {};
		data.name = $('#name').val();
		if (data.name.length == 0)
		{
			display_text_message('Please enter Patient name.', 300, 200);
			$('#name').css({
				'border-color':'red'
			});
			return;
		}
		data.address1 = $('#address1').val();
		data.address2 = $('#address2').val();
		data.city = $('#city').val();
		data.state = $('#state').val();
		data.home_phone = $('#home_phone').val();
		data.work_phone = $('#work_phone').val();
		data.email = $('#email').val();
		data.insurer = $('#insurer').val();
		data.adjuster = $('#adjuster').val();
		data.dob = $('#dob').val();
		data.doa = $('#doa').val();
		data.zip = $('#zip').val();
		data.other_phone = $('#other_phone').val();
		data.claim_no = $('#claim_no').val();
		data.appt_date = $('#appt_date').val();
		data.comment = $('#comment').val();
		/*if ($("#new_case_register_location").jqxDropDownList('getSelectedIndex') == 0) data.location = '';
		else data.location = $('[name="new_case_register_location"]').val();
		if ($("#new_case_register_accidents").jqxDropDownList('getSelectedIndex') == 0) data.accidentType = '';
		else data.accidentType = $('[name="new_case_register_accidents"]').val();
		if ($("#new_case_register_attorneys").jqxDropDownList('getSelectedIndex') == 0) data.attorney = '';
		else data.attorney = $('[name="new_case_register_attorneys"]').val();*/
		
		var error_message = '';
		if (data.name == '') 	error_message += 'Please enter "Name"<br />';
		if (data.address1 == '') 	error_message += 'Please enter "Address 1"<br />';
		if (data.dob == '') 	error_message += 'Please enter "DOB"<br />';
		if (data.state == '') 	error_message += 'Please enter "State"<br />';
		if (data.city == '') 	error_message += 'Please enter "City"<br />';
		if (data.email == '') 	error_message += 'Please enter "Email"<br />';
		if (data.zip == '') 	error_message += 'Please enter "Zip Code"<br />';
		var error_message = '';
		
		if (error_message != '') {
			display_text_message(error_message, 500, 300);
		} else {
			display_please_wait();
			$('#new_case_register_form').submit();
		}

	});
	
	$('#discharge_attorneys_list').live('change', function() {
		var attyId = $(this).val();
		$('#discharge-table-container').jtable('load', {
			extAttyID: attyId,
			sortingFieldName: sortingFieldName,
			sortingValue: sVal,
			sortingValue2: sVal2,
			sortingQriteria: sQriteria
		});
	});
	
	$('#btn_client_cases_dashboard').on('click', function(e) {
		e.preventDefault();
		
		var sMyCases = true;
		if ($('#client_cases_my_cases').length)
		{
			if ($('#client_cases_my_cases').is(':checked'))
			{
				sMyCases = false;
				$('#client_cases_search').attr('action', '/cases/search/advanced');
			}
		}
		
		var sAttys = true;
		if ($('#client_cases_atty').length)
		{
			if ($('#client_cases_atty').val() != 0)
			{
				sAttys = false;
				$('#client_cases_search').attr('action', '/cases/search/advanced');
			}
		}
		
		if ($('#client_cases_name').val() == '' && 
			$('#client_cases_ssn').val() == '' && 
			$('#client_cases_account').val() == '' && 
			sMyCases && sAttys
		) 
		{
			display_text_message('Please select at least one parameter.', 300, 150);
			return;
		}
		
		$('#client_cases_search').submit();
	});
	
	$('#new_cases_locate').on('click', function(e) {
		e.preventDefault();
		sName = $('#new_cases_name').val();
		sPhone = $('#new_cases_phone').val();
		sSSN = $('#new_cases_ssn').val();
		sDOB = $('#new_cases_dob').val();
		
		if (sName.length > 0 || sPhone.length > 0 || sSSN.length > 0 || sDOB.length > 0)
		{	
			$('#client_new_cases_search').submit();
		}
		else
		{
			display_text_message('Please select at least one parameter.', 320, 150);
		}
	});
	
	$(document).ajaxStop(function() {
		close_please_wait();
	});
	
	$(document).ajaxComplete(function(event, xhr, settings) {
		if (xhr.responseText.length && isJSON(xhr.responseText))
		{
			data = $.parseJSON(xhr.responseText);
			if (data.hasOwnProperty('login_error'))
			{
				url = parse_url(location.href);
				window.location = baseURL + 'profile/login?r='+trim(url.path, '/');
			}
		}
	});

	$('#statement_lob').on('change', function () {
        if ($(this).val() == 'MSHC' || $(this).val() == 'Multi') {
            $('#statement_finance').prop('disabled', false);
        } else {
            $('#statement_finance').prop('disabled', true).val('all');
        }
    });

	$('#client_cases_search_transactions').on('change', function() {
        reloadTransactionsDate = false;
        if (cases_account_id != -1) {
            load_summary_table();
            load_visits_table();
        }
    });

    $('#span_statement_doc').live('click', function() {
        if ( ! $('#li-cases-statement').hasClass('ui-state-disabled') ) {
            load_statements_table();
        }
    });

    $('#client_cases_search_company').on('click', function() {
        if ($(this).val() == 'Multi') {
            $('.search-financial').slideDown();
        } else {
            $('.search-financial').slideUp();
            $('#client_cases_search_financial').val('');
        }
    });

	$('#btn_contact_cases_search').on('click', function() {
		var name = $('#contact_client_cases_name'),
            account = $('#contact_client_cases_account');

        if (name.val().length < 3 && account.val().length < 3) {
            display_text_message('Please enter patient name or account #', 300, 150);
            return;
        }

        display_please_wait();
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/contact_cases_search',
            dataType: 'html',
            data: {
                name: name.val(),
                account: account.val()
            },
            success: function(response) {
                var $list = $('#contact_cases_list');

                if (isJSON(response)) {
                    var data = $.parseJSON(response);
                    if (data.list.length) {
                        $list.children().remove();
                        $list.append('<option value="0">--- Please select case ---</option>');
                        for (var i = 0; i < data.list.length; ++i)
                        {
                            $list.append('<option data-account="' + data.list[i].account + '" data-class="' +
                                data.list[i].case_category + '" data-doa="' + data.list[i].accident_date+ '" value="' + data.list[i].account + '">' +
                                data.list[i].last_name + ', ' + data.list[i].first_name + '; ' +
                                'Account #: ' + data.list[i].account + '; ' + data.list[i].case_category +
                                '; DOA: ' + data.list[i].accident_date + '</option>');
                        }
                        $list.show();
                    }
                } else {
                    $list.hide();
                }

                $list.trigger('change');
            },
            complete: function() {
                close_please_wait();
            },
            error: function(data){
                // display Error message
                display_text_message('Error', 400, 200);
            }
        });
	});

    $('#contact_cases_list').on('change', function () {
        var $selected = $(this).find(':selected');

        if ($(this).val() != 0) {
            $('#case_contact_account').val($selected.attr('data-account'));
            $('#case_contact_class').val($selected.attr('data-class'));
            $('#case_contact_doa').val($selected.attr('data-doa'));
        } else {
            $('#case_contact_account').val('');
            $('#case_contact_class').val('');
            $('#case_contact_doa').val('');
        }
    })
});

function validateValueByPattern(value, needPattern)
{
	if (value) {
		var pattern = new RegExp(needPattern);
		if (pattern.test(value)) {
			return true;
		}
	}
	return false;
}

var statementUrl;
var statementCount = 0;
var intervalID;
var load_statements_table = function () {
    var statementUrl = [],
        sCases = [],
        statementSplit = 1,
        dateType,
        dateFrom,
        dateTo,
		checked = $('[id*=cases_client_checkbox_]:checked');

    if (checked.length > 0) {
        checked.each(function() {
            if ($(this).attr('id') != 'cases_client_checkbox_all')
                sCases.push($(this).metadata());
        });
        statementSplit = 0;
    }

    dateType = $('[name="client_cases_dates"]:checked').val();
    dateFrom = $('#client_cases_date_from').val();
    dateTo = $('#client_cases_date_to').val();

    if (sCases.length == 0) {
        sCases.push({index: cases_account_id});
    }

    $.each(sCases, function(index, value) {
        var jj = value.index;
        var withDocs;

        if ($('#checkbox_with_docs').is(':checked')) {
            withDocs = '&sWithDocs=1';
        } else {
            withDocs = '';
        }

		statementUrl.push(
            'sAccountID=' + cases_account[jj].account + '&sDbName=' + cases_account[jj].db_name
			+ '&sPractice=' + cases_account[jj].practice + '&sPatient=' + cases_account[jj].patient + '&sCaseNo=' + cases_account[jj].case_no
            + '&sTypeStatement=' + $('#client_cases_search_details').val()
            + '&sTransactions=' + $('#client_cases_search_transactions').val()
            + '&sLOB=' + $('#statement_lob').val()
            + '&sFinance=' + $('#statement_finance').val()
            + withDocs
        );
    });

    if (statementUrl.length) {
        $.statementDownload(baseURL + 'cases/statements/', {statementUrl: statementUrl, statementSplit: statementSplit, dateType: dateType, dateFrom: dateFrom, dateTo: dateTo}, 'post');
    }
};

function openStatement()
{
    if (statementCount < statementUrl.length) {
        window.open(statementUrl[statementCount], '_blank');
        ++statementCount;
    } else {
        clearInterval(intervalID);
    }
}
function export_to_file(type_file) {
	var jtSorting = '';
	var typeSorting = '';
	var url = baseURL + 'files/export/' + type_file + '/' + dbName + '/' + jtSorting + '/' + typeSorting;
	var data = 'sTypeFile=' + type_file + '&sNameFunction=' + dbName + '&sJtSorting=' + jtSorting + '&sTypeSorting=' + typeSorting;

	if (dbName == 'discharge') {
		var attyId = $('#discharge_attorneys_list').val();
		if (attyId != '') {
			data += '&extAttyID=' + attyId;
		}
	} else if (dbName == 'cases') {
        data += '&sName=' + $('#client_cases_name').val();
        data += '&sAccount=' + $('#client_cases_account').val();
        data += '&sSSN=' + $('#client_cases_ssn').val();
        data += '&sDateFrom=' + $('#client_cases_date_from').val();
        data += '&sDateTo=' + $('#client_cases_date_to').val();
        data += '&sClass=' + $('#client_cases_category_like').val();
        data += '&sMyCases=' + ($('#my_cases').is(':checked') ? '1' : '0');
        data += '&sCompany=' + $('#client_cases_search_company').val();
        data += '&sFinancial=' + $('#client_cases_search_financial').val();
        data += '&sTransactions=' + $('#client_cases_search_transactions').val();
        data += '&sCasesType=' + $('#cases_activity_type').val();
        data += '&sAttyType=' + $('[name="client_cases_attorneys"]:checked').val();

        $('#div_attorneys_list input:checkbox:checked').each(function() {
            data += '&sAttys[]=' + $(this).val();
        });

        data += '&sTypeDate=' + $('input:radio[name=client_cases_dates]:checked').val();
    } else if (dbName == 'activities') {
        data += '&user_id=' + $('#activity_log_search_users').val();
        data += '&event_name=' + $('#activity_log_search_events').val();
    }
	
	var addParam = false;
	$('#search-clear').parents('td').children('div').each( function() { 
		$(this).children().each(function() {
			if ($(this).attr('type') == 'checkbox') {
				var split_charges = 1;
				if ( ! $(this).is(':checked'))  split_charges = 0;
				url += '/' + $(this).attr('id') + '/' + split_charges + '/' + sQriteria;
				data += '&sNameFieldFilter=' + $(this).attr('id') + '&sValueFieldFilter=' + split_charges + '&sTypeFieldFilter=' + sQriteria;
				addParam = true;
			} else if (dbName == 'discharge' && $(this).val() != '' ) {
				var nameFilter = $(this).attr('id');
				console.log(nameFilter);
				if (nameFilter.indexOf('_date-to') > 0) {
					data += '&sValueFieldFilter2=' + $(this).val();
				} else {
					if (nameFilter.indexOf('_date-from') > 0) {
						var posEnd = nameFilter.indexOf('_date-');
						nameFilter = nameFilter.substr(0, posEnd+5) + '_between-value';
					}
					url += '/' + nameFilter + '/' + $(this).val() + '/' + sQriteria;
					data += '&sNameFieldFilter=' + nameFilter + '&sValueFieldFilter=' + $(this).val() + '&sTypeFieldFilter=' + sQriteria;
				}
				console.log(data);
				addParam = true;
			} else if ($(this).val() != '') {
				url += '/' + $(this).attr('id') + '/' + $(this).val() + '/' + sQriteria;
				data += '&sNameFieldFilter=' + $(this).attr('id') + '&sValueFieldFilter=' + $(this).val() + '&sTypeFieldFilter=' + sQriteria;
				console.log(data);
				addParam = true;
			}
		}); 
	});

	if (clientID > 0) {
		if ( ! addParam ) url += '/0/0/0';
		url += '/' + clientID;
		data += '&sClientID=' + clientID;
	}
	
	if (cases_account_id > -1) {
		data += '&sAccountID=' + cases_account[cases_account_id].account + '&sDbName=' + cases_account[cases_account_id].db_name + '&sPractice=' + cases_account[cases_account_id].practice + '&sPatient=' + cases_account[cases_account_id].patient + '&sCaseNo=' + cases_account[cases_account_id].case_no;
	}
    //console.log(data);return;
	$.simpleDownload(baseURL + 'files/export/', data, 'post');
//	$(location).attr('href', url);
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
	$('#client_case_class, #case_contact_class').val(cases_account[case_id].case_category);

	if (cases_account[case_id].accident_date !== '' && cases_account[case_id].accident_date !== null) {
        $('#client_case_doa, #case_contact_doa').val(cases_account[case_id].accident_date);
    } else {
        $('#client_case_doa, #case_contact_doa').val('');
    }

	$('#client_case_account, #case_contact_account').val(cases_account[case_id].account);
	$('#client_case_status').val(cases_account[case_id].status);
	$('#client_case_appt_status').val(cases_account[case_id].appt_status);
	$('#client_case_ssn').val('xxxxx' + cases_account[case_id].ssn.substring(5));
	$('#client_case_database').val(cases_account[case_id].db_name);
	$('#client_email').val(cases_account[case_id].e_mail_address);
    $('#client_phone').val(cases_account[case_id].phone);
    $('#client_work_phone').val(cases_account[case_id].work_phone);
    $('#client_cell_phone').val(cases_account[case_id].cell_phone);
	if ($('#documents_account').length > 0)
	{
		$('#documents_account').val(cases_account[case_id].account);
	}
}

function load_summary_table() {
	var jj = cases_account_id;

	$('#summary-table-container').jtable('load', {
		sAccountID: cases_account[jj].account,
		sDbName: cases_account[jj].db_name,
		sPractice: cases_account[jj].practice,
		sPatient: cases_account[jj].patient,
		sCaseNo: cases_account[jj].case_no,
        sTransactionsDate: $('#client_cases_search_transactions').val()
	});
}

function go_to_cases_summary(sInds) {
	var count_cases = sInds.length;
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
	$('#summary-table-container').show();
	$('#li-cases-search').removeClass('tabs-state-active');
    reloadTransactionsDate = true;
	$('#li-cases-summary').addClass('tabs-state-active');
	$('#client_cases_list_count').val(count_cases);
	if (count_cases > 1) {
		$('#btn_client_cases_next').removeClass('orange-button-text-grey').addClass('orange-button-text-white');
		$('#btn_client_cases_last').removeClass('orange-button-text-grey').addClass('orange-button-text-white');
	}
	$('#client_cases_search_details').attr('disabled', false);
    $('#client_cases_search_transactions').attr('disabled', false);
	$('#checkbox_with_docs').attr('disabled', false);
    $('#statement_lob').attr('disabled', false);
    $('.case_clients_checkbox, #cases_client_checkbox_all').attr('checked', false);
    $('#cases-table-container .jtable-data-row').removeClass('case-documents-list-selected');
    $('#btn_cases_summary').removeClass('opacity25').addClass('opacity25');
	tableID = '';
	tableName = 'summary';
	dbName = 'summary';
	get_cases_dropdown(sInds);
	var jj = sInds[0].index;
	set_values_by_cases_id(jj);
	cases_account_id = jj;
	$('#client_cases_list_count').html(count_cases);
	$('#summary_text_div').show();
	$('.right_menu_ctr').html('Case Summary');
	load_summary_table();
}

function cases_managers(sInds) {
	$('#btn_cases_' + cases_manager_type).attr('disabled', 'disabled');
	var cases_keys = new Array();
	for (var i = 0 ; i < sInds.length; i++) {
		var ind = sInds[i].index;
		cases_keys.push(cases_account[ind]['attorney_id'] + '|||' + cases_account[ind]['db_name'] + '|||' + cases_account[ind]['account'] + '|||' + cases_account[ind]['practice'] + '|||' + cases_account[ind]['case_no'] + '|||' + cases_account[ind]['patient']);
	}
	
	if (cases_manager_type == 'all')
	{
		var s_type = cases_account[sInds[0].index]['assigned'] == 1 ? 'unassigned' : 'assigned';
	}
	else
	{
		var s_type = cases_manager_type == 'unassigned' ? 'assigned' : (cases_manager_type == 'assigned' ? 'unassigned' : '');
	}
	
	
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/cases_managers',
		dataType: 'html',
		data: {
			sArrayKeys : cases_keys,
			sUserID : caseManagerUserID,
			sType : s_type
		},
		success: function(data) {
			//display_text_message('Selected cases was ' + cases_manager_type + '.');
			load_cases_managers_change_tab(cases_manager_type);
		},
		complete: function() {
		},
		error: function(data){
			// display Error message
			display_text_message('Error');
		}
	});
}

function load_appointments_table() {
	var jj = cases_account_id;
	$('#appointments-table-container').jtable('load', {
		sAccountID: cases_account[jj].account,
		sDbName: cases_account[jj].db_name,
		sPractice: cases_account[jj].practice,
		sPatient: cases_account[jj].patient,
		sCaseNo: cases_account[jj].case_no
	});
}

function load_documents_table() {
	var jj = cases_account_id;
	$('#documents-table-container').jtable('load', {
		sAccountID: cases_account[jj].account,
		sDbName: cases_account[jj].db_name,
		sPractice: cases_account[jj].practice,
		sPatient: cases_account[jj].patient,
		sCaseNo: cases_account[jj].case_no
	});
}

function load_visits_table() {
	var jj = cases_account_id;
	$('#visits-table-container').jtable('load', {
		sAccountID: cases_account[jj].account,
		sDbName: cases_account[jj].db_name,
		sPractice: cases_account[jj].practice,
		sPatient: cases_account[jj].patient,
		sCaseNo: cases_account[jj].case_no,
        sTransactionsDate: $('#client_cases_search_transactions').val()
	});
}

function get_visit_summary_detail_by_date(trId) {
//	alert($('td_' + trId).text());
	if ($('#td_' + trId).html() == '') {
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
					sCompany: $('#' + trId + ' td:nth-child(2)').children('span').metadata().company,
					sDOV: $('#' + trId + ' td:nth-child(2)').children('span').metadata().dov,
					sSequence : $('#' + trId + ' td:nth-child(2)').children('span').metadata().sequence
			},
			success: function(data) {
				$('#td_' + trId).html(data);
			},
			complete: function() {
				
			},
			error: function(data){
				// display Error message
				display_text_message('Error');
			}
		});
	}
	else
	{
		close_please_wait();
	}
}

$.assocArraySize = function(obj) {
    // http://stackoverflow.com/a/6700/11236
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function array_search(needle, haystack, strict) {
	// Searches the array for a given value and returns the corresponding key if
    // successful
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	
    var strict = !!strict;
	
    for (var key in haystack)
	{
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle))
		{
			return key;
        }
    }
    return false;
}

function number_format (number, decimals, dec_point, thousands_sep) 
{
	// http://kevin.vanzonneveld.net
	// +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +     bugfix by: Michael White (http://getsprink.com)
	// +     bugfix by: Benjamin Lupton
	// +     bugfix by: Allan Jensen (http://www.winternet.no)
	// +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +     bugfix by: Howard Yeend
	// +    revised by: Luke Smith (http://lucassmith.name)
	// +     bugfix by: Diogo Resende
	// +     bugfix by: Rival
	// +      input by: Kheang Hok Chin (http://www.distantia.ca/)
	// +   improved by: davook
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +      input by: Jay Klehr
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +      input by: Amir Habibi (http://www.residence-mixte.com/)
	// +     bugfix by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Theriault
	// +      input by: Amirouche
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// *     example 1: number_format(1234.56);
	// *     returns 1: '1,235'
	// *     example 2: number_format(1234.56, 2, ',', ' ');
	// *     returns 2: '1 234,56'
	// *     example 3: number_format(1234.5678, 2, '.', '');
	// *     returns 3: '1234.57'
	// *     example 4: number_format(67, 2, ',', '.');
	// *     returns 4: '67,00'
	// *     example 5: number_format(1000);
	// *     returns 5: '1,000'
	// *     example 6: number_format(67.311, 2);
	// *     returns 6: '67.31'
	// *     example 7: number_format(1000.55, 1);
	// *     returns 7: '1,000.6'
	// *     example 8: number_format(67000, 5, ',', '.');
	// *     returns 8: '67.000,00000'
	// *     example 9: number_format(0.9, 0);
	// *     returns 9: '1'
	// *    example 10: number_format('1.20', 2);
	// *    returns 10: '1.20'
	// *    example 11: number_format('1.20', 4);
	// *    returns 11: '1.2000'
	// *    example 12: number_format('1.2000', 3);
	// *    returns 12: '1.200'
	// *    example 13: number_format('1 000,50', 2, '.', ' ');
	// *    returns 13: '100 050.00'
	// Strip all characters but numerical ones.
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) 
	{
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) 
	{
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function isJSON(data)
{
	var IS_JSON = true;
	try
	{
		var obj = $.parseJSON(data);
	}
	catch (error)
	{
		IS_JSON = false;
	}
	return IS_JSON;
}

function parse_url (str, component) {
  // http://kevin.vanzonneveld.net
  // +      original by: Steven Levithan (http://blog.stevenlevithan.com)
  // + reimplemented by: Brett Zamir (http://brett-zamir.me)
  // + input by: Lorenzo Pisani
  // + input by: Tony
  // + improved by: Brett Zamir (http://brett-zamir.me)
  // %          note: Based on http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  // %          note: blog post at http://blog.stevenlevithan.com/archives/parseuri
  // %          note: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  // %          note: Does not replace invalid characters with '_' as in PHP, nor does it return false with
  // %          note: a seriously malformed URL.
  // %          note: Besides function name, is essentially the same as parseUri as well as our allowing
  // %          note: an extra slash after the scheme/protocol (to allow file:/// as in PHP)
  // *     example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
  // *     returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}
  var query, key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port',
            'relative', 'path', 'directory', 'file', 'query', 'fragment'],
    ini = (this.php_js && this.php_js.ini) || {},
    mode = (ini['phpjs.parse_url.mode'] &&
      ini['phpjs.parse_url.mode'].local_value) || 'php',
    parser = {
      php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
      strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
      loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-scheme to catch file:/// (should restrict this)
    };

  var m = parser[mode].exec(str),
    uri = {},
    i = 14;
  while (i--) {
    if (m[i]) {
      uri[key[i]] = m[i];
    }
  }

  if (component) {
    return uri[component.replace('PHP_URL_', '').toLowerCase()];
  }
  if (mode !== 'php') {
    var name = (ini['phpjs.parse_url.queryKey'] &&
        ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
    parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
    uri[name] = {};
    query = uri[key[12]] || '';
    query.replace(parser, function ($0, $1, $2) {
      if ($1) {uri[name][$1] = $2;}
    });
  }
  delete uri.source;
  return uri;
}

function trim (str, charlist) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: mdsjack (http://www.mdsjack.bo.it)
  // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
  // +      input by: Erkekjetter
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: DxGx
  // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
  // +    tweaked by: Jack
  // +   bugfixed by: Onno Marsman
  // *     example 1: trim('    Kevin van Zonneveld    ');
  // *     returns 1: 'Kevin van Zonneveld'
  // *     example 2: trim('Hello World', 'Hdle');
  // *     returns 2: 'o Wor'
  // *     example 3: trim(16, 1);
  // *     returns 3: 6
  var whitespace, l = 0,
    i = 0;
  str += '';

  if (!charlist) {
    // default list
    whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  } else {
    // preg_quote custom list
    charlist += '';
    whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
  }

  l = str.length;
  for (i = 0; i < l; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(i);
      break;
    }
  }

  l = str.length;
  for (i = l - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
    }
  }

  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

/**
 * Function generates a random string for use in unique IDs, etc
 */
function randString(n) {
    if(!n) {
        n = 5;
    }

    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for(var i=0; i < n; i++)
    {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

function setStatementStatus(status) {
    if (status) {
        $('#li-cases-statement').removeClass('ui-state-disabled');
    } else {
        $('#li-cases-statement').addClass('ui-state-disabled');
    }
    $('#client_cases_search_transactions').attr('disabled', !status);
    $('#checkbox_with_docs').attr('disabled', !status);
    $('#client_cases_search_details').attr('disabled', !status);
    $('#statement_lob').attr('disabled', !status);
}

function setBulkLOB() {
	var $lob = $('#statement_lob'), $finance = $('#statement_finance');

	$lob.find('option').remove();
	$lob.append('<option value="all">All</span>');
	$lob.append('<option value="MSHC">MSHC</span>');
	$lob.append('<option value="MED">MED</span>');
	$lob.append('<option value="BWR">BWR</span>');
	$lob.append('<option value="MRI">MRI</span>');
    $lob.append('<option value="NTI">NTI</span>');

	$finance.prop('disabled', true).val('all');
}

function contactComplete(response) {
    close_please_wait();
    display_text_message('Your inquiry was sent successfully', 400, 150);
}