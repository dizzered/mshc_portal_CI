// JavaScript Document

$(function() {
	// comment table of users
	$('#user-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'username ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_users_table'
		},
        recordsLoaded: function (e, data) {
		    if (data.serverResponse.readOnly == true) {
		        $('.fnEditRow, .fnDeleteRow').addClass('disabled');
            }
        },
		fields: {
			user_id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			username: {
				title: 'Username',
				width: '160px'
			},
			last_name: {
				title: 'Last Name',
				list: true,
				width: '160px'
			},
			first_name: {
				title: 'First Name',
				list: true,
				width: '160px'
			},
			firm_name: {
				title: 'Primary Firm',
				list: true,
				width: '270px'
			},
			role_name: {
				title: 'Role',
				list: true,
				width: '160px'
			},
			last_login_date: {
				title: 'Last Login',
				list: true,
				width: '160px'
			}
		}
	});
	
	// comment table of users
	$('#dashboard-user-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'username ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_users_table'
		},
		fields: {
			user_id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'" data="{tableName: \'user\', tableID: \'User\'}">Edit</span>';
				},
				list: true,
				width: '40px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'" data="{tableName: \'user\', tableID: \'User\'}">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			username: {
				title: 'Username',
				width: '150px'
			},
			last_name: {
				title: 'Last Name',
				list: true,
				width: '150px'
			},
			first_name: {
				title: 'First Name',
				list: true,
				width: '150px'
			},
			firm_name: {
				title: 'Primary Firm',
				list: true,
				width: '150px'
			}
		}
	});

	// comment table of marketers
	$('#marketer-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'last_name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_marketers_table'
		},
		fields: {
			marketers_id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			last_name: {
				title: 'Last Name',
				list: true,
				width: '160px'
			},
			first_name: {
				title: 'First Name',
				list: true,
				width: '160px'
			},
			middle_name: {
				title: 'Middle Name',
				list: true,
				width: '160px'
			},
			phone: {
				title: 'Phone',
				list: true,
				width: '160px'
			},
			email: {
				title: 'Email',
				list: true,
				width: '160px'
			}
		}
	});
	
	// comment table of marketers
	$('#form-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_forms_table'
		},
		fields: {
			form_id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			name: {
				title: 'Name',
				list: true,
				width: '160px'
			},
			file_name: {
				title: 'File Name',
				list: true,
				width: '160px'
			},
			description: {
				title: 'Description',
				list: true,
				width: '320px'
			},
			weight: {
				title: 'Order By',
				list: true,
				width: '160px'
			}
		}
	});
	
	$('#dashboard-form-table-container').jtable({
		title: '',
		paging: true,
		pageSize: 20,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_forms_table'
		},
		fields: {
			form_id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'" data="{tableName: \'form\', tableID: \'Form\'}">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'" data="{tableName: \'form\', tableID: \'Form\'}">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			name: {
				title: 'Name',
				list: true,
				width: '160px'
			},
			file_name: {
				title: 'File Name',
				list: true,
				width: '160px',
				display: function(data) {
					return (data.record.file_name != null) ? data.record.file_name.substr(0,20)+'...' : '';
				}
			}
		}
	});
	
	// comment table of activity log
	$('#activity-log-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'created DESC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_activity_log_table'
		},
		fields: {
			created: {
				title: 'Date',
				width: '20%'
			},
			username: {
				title: 'User',
				list: true,
				width: '15%'
			},
			firm_name: {
				title: 'Firm',
				list: true,
				width: '15%'
			},
			event: {
				title: 'Event',
				list: true,
				width: '20%'
			},
			info: {
				title: 'Details',
				list: true,
				width: '30%'
			}
		}
	});

	// comment table of clients
	$('#client-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_clients_table'
		},
		fields: {
			id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			name: {
				title: 'Client Name',
				width: '460px'
			},
			practices_count: {
				title: 'Number of Practices',
				list: true,
				width: '560px'
			},
			practices: {			
				title: '',
				sorting: false,
				display: function(data) {
					return '<input type="button" class="fnPracticesRow" id="'+data.record.id+'" value="Practices" />';
				},
				list: true,
				width: '50px'
			}
		}
	});

	// comment table of clients
	$('#practice-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'practice_name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_practices_table/' + clientID
		},
		fields: {
			id: {
				key: true,
				list: false
			},
			edit_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnEditRow" id="'+data.record.id+'">Edit</span>';
				},
				list: true,
				width: '50px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
			practice_name: {
				title: 'Practice Name',
				width: '360px'
			},
			micro_db_name: {
				title: 'MicroMD Database',
				list: true,
				width: '270px',
				display: function(data) {
					return data.record.micro_db_name.toUpperCase();
				}
			},
			rundown_db_name: {
				title: 'Rundown Database',
				list: true,
				width: '280px',
				display: function(data) {
					if (data.record.rundown_db_name) {
						return data.record.rundown_db_name.toUpperCase();
					} else {
						return '';
					}
				}
			},
			split_charges: {
				title: 'Split Charges',
				list: true,
				display: function(data) {
					if (data.record.split_charges == 1)
						return '<input type="checkbox" checked="checked" disabled="disabled" />';
					else
						return '<input type="checkbox" disabled="disabled" />';
				},
				width: '190px'
			}
		}
	});
	
	// comment table of cases
	$('#cases-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'patient ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_cases_table'
		},
		recordsLoaded: function (e, data) {
		    var btn_cases_summary = $('#btn_cases_summary');

			cases_account = [];
			
			if (data.records.length > 0) {
				btn_cases_summary.attr('disabled', false);
				$('#cases_client_checkbox_all').attr('disabled', false);
				$('#count_cases_div').html(data.serverResponse.TotalRecordCount + ' patient cases found');
				$('.jtable-bottom-panel').show();
				cases_account = data.records;

                $('#btnSearchExport').removeClass('disabled');
			} else {
				btn_cases_summary.attr('disabled', true);
				$('#cases_client_checkbox_all').attr('disabled', true);
				$('#client_cases_search_details').attr('disabled', true);
                $('#client_cases_search_transactions').attr('disabled', true);
				$('#checkbox_with_docs').attr('disabled', true);
				$('.jtable-bottom-panel').hide();
				$('#count_cases_div').html('0 patient cases found');
				var total = data.serverResponse.TotalRecordCount;
				if (total > 0)
				{
					display_text_message('Please narrow down your search criteria.', 300, 200);
				}

                $('#btnSearchExport').addClass('disabled');
			}

			if (btn_cases_summary.hasClass('opacity25') == false) {
				btn_cases_summary.addClass('opacity25');
			}

			cases_account_index = -1;
			
			$('#cases-table-container div.jtable-column-header-container').each(function(index, element) {
				var spanWidth = parseInt($(this).children('span').css('width')) + 10;
				$(this).css('background-position', spanWidth+'px');
			});
			
			$('#cases-table-container table.jtable').addClass('case-clients-list');
			$('#cases_client_checkbox_all').attr('checked', false);

			setBulkLOB();
		},
		fields: {
			cases_id: {
				title: '<input id="cases_client_checkbox_all" type="checkbox" value="1"  disabled="disabled"/>',
				sorting: false,
				width: '20px',
				display: function(data) {
					cases_account_index++;
					return '<input id="cases_client_checkbox_' + cases_account_index + '" type="checkbox" class="case_clients_checkbox" name="Summary" value="1" data="{index: \'' + cases_account_index + '\'}"/>';
				}
			},
			attorney_name: {
				title: 'Attorney',
				list: true,
				width: '150px'
			},
			patient: {
				title: 'Patient',
				list: true,
				width: '150px',
				display: function(data) {
					return data.record.last_name + ' ' + data.record.first_name + ' ' + data.record.middle_name;
				}
			},
			account: {
				title: 'Account',
				list: true,
				width: '70px'
			},
			case_category: {
				title: 'Class',
				list: true,
				width: '100px'
			},
			accident_date: {
				title: 'DOA',
				list: true,
				width: '100px'
			},
			status: {
				title: 'Status',
				list: true,
				width: '100px'
			},
			db_name: {
				title: 'Database',
				list: true,
				width: '100px',
				display: function(data) {
					return data.record.db_name.toUpperCase();
				}
			},
			summary: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span id="summary_' + cases_account_index + '" style="text-decoration:underline; cursor:pointer" class="btn_cases_search_summary" data="{index: \'' + cases_account_index + '\'}">Summary</span>';
				}
			}
		}
	});
	
	// comment table of cases
	$('#cases-new-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'patient ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_cases_new_table'
		},
		recordsLoaded: function (e, data) { 
			cases_account = new Array();
			if (data.records.length > 0) {
				$('#btn_cases_summary').attr('disabled', false);
				$('#count_cases_div').html(data.serverResponse.TotalRecordCount + ' patient cases found');
				$('.jtable-bottom-panel').show();
				cases_account = data.records;
			} else {
				$('#btn_cases_summary').attr('disabled', true);
				$('#client_cases_search_details').attr('disabled', true);
                $('#client_cases_search_transactions').attr('disabled', true);
				$('#checkbox_with_docs').attr('disabled', true);
				$('.jtable-bottom-panel').hide();
				$('#count_cases_div').html('0 patient cases found');
				var total = data.serverResponse.TotalRecordCount;
				if (total > 0)
				{
					display_text_message('Please narrow down your search criteria.', 300, 200);
				}
			}
			cases_account_index = -1;
			$('#cases-table-container table.jtable').addClass('case-clients-list');
			$('table.jtable div.jtable-column-header-container').each(function(index, element) {
				var spanWidth = parseInt($(this).children('span').css('width')) + 10;
				$(this).css('background-position', spanWidth+'px');
			});
		},
		fields: {
			cases_id: {
				title: '',
				sorting: false,
				width: '90px',
				display: function(data) {
					cases_account_index++;
					return '<span id="cases_client_checkbox_' + cases_account_index + '" class="new_case_select" data="{index: \'' + cases_account_index + '\'}">Select</span>';
				}
			},
			patient: {
				title: 'Patient',
				list: true,
				width: '210px',
				display: function(data) {
					return data.record.last_name + ' ' + data.record.first_name + ' ' + data.record.middle_name;
				}
			},
			dob: {
				title: 'DOB',
				list: true,
				width: '65px'
			},
			ssn: {
				title: 'SSN',
				list: true,
				width: '65px'
			},
			address: {
				title: 'Address',
				list: true,
				width: '100px',
				display: function(data) {
					var fullAddress = [];
					if (data.record.zip4) fullAddress.push(data.record.zip4);
					if (data.record.address1) fullAddress.push(data.record.address1);
					if (data.record.address2) fullAddress.push(data.record.address2);
					return fullAddress.join(', ');
				}
			},
			phone: {
				title: 'Phones',
				list: true,
				width: '90px',
				display: function(data) {
					var fullPhones = [];
					if (data.record.phone) fullPhones.push(data.record.phone);
					if (data.record.work_phone) fullPhones.push(data.record.work_phone);
					return fullPhones.join(', ');
				}
			},
			e_mail_address: {
				title: 'Email',
				list: true,
				width: '75px'
			},
			accident_date: {
				title: 'DOA',
				list: true,
				width: '65px'
			},
			attorney_name: {
				title: 'Attorney',
				list: true,
				width: '150px'
			},
			new_case: {
				title: '',
				list: true,
				width: '80px',
				display: function(data) {
					return '<input type="button" id="btn_new_case_' + cases_account_index + '" class="btn_new_case_register" value="New Case" data="{index: \'' + cases_account_index + '\'}" />';
				}
			},
		}
	});
	
	// comment table of notifications
	$('#notification-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'created DESC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_notifications_table'
		},
		fields: {
			notification_id: {
				key: true,
				list: false
			},
			type: {
				title: 'Type',
				display: function(data) {
					return '<div class="fnNotificationTypeRow '+data.record.type+'"></div>';
				},
				width: '50px'
			},
			title: {
				title: 'Title',
				width: '800px',
				display: function(data) {
					var title = '<div id="'+data.record.id+'" class="fnShowNotification notif_'+data.record.id;
					if (data.record.read == 0)
					{
						classRead = ' unread';
					}else
					{
						classRead = '';
					}
					title += classRead+'">'+data.record.title+'</div>';
					return title;
				},
			},
			created: {
				title: 'Create Date',
				list: true,
				width: '160px'
			},
			delete_id: {
				title: '',
				sorting: false,
				display: function(data) {
					return '<span class="fnDeleteRow" id="'+data.record.id+'">Delete</span>';
				},
				list: true,
				width: '50px'
			},
		}
	});
	
	$('#cases_managers_search_apply').live('click', function(){
		load_cases_managers_change_tab(cases_manager_type);
	});
	
	$('input[name="cases_params"]').live('click', function() {
		load_cases_managers_change_tab(cases_manager_type);
	});
	
	$('#filter_by_name').keyup(function(e) {
		// Enter pressed?
		if (e.which == 13) 
		{
			load_cases_managers_change_tab(cases_manager_type);
		}
    });
	
	$('#assigned-cases-table-container').jtable({
		title: '',
		paging: false,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'patient ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_cases_managers_table'
		},
		recordsLoaded: function (e, data) {
			cases_account = new Array(); 
			if (data.records.length > 0) {
				$('#count_cases_div').html(data.serverResponse.TotalRecordCount + ' cases found');
				if (cases_manager_type != 'all') {
					$('#cases_managers_checkbox_all').attr('disabled', false);
					$('#btn_cases_' + (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).attr('disabled', false);
				}
				$('.jtable-bottom-panel').show();
				cases_account = data.records;
//				console.log(cases_account);
				cases_account_index = -1;
			} else {
				if (cases_manager_type != 'all')
					$('#btn_cases_' + (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).attr('disabled', true);
				$('#cases_managers_checkbox_all').attr('disabled', true);
				$('.jtable-bottom-panel').hide();
				$('#count_cases_div').html('');
			}
			if ($( cases_manager_type != 'all' && '#btn_cases_' + (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).hasClass('opacity25') == false)
				$('#btn_cases_' + + (cases_manager_type == 'assigned' ? 'unassigned' : 'assigned')).addClass('opacity25');
			
			$('#cases-managers-table-container div.jtable-column-header-container').each(function(index, element) {
				var spanWidth = parseInt($(this).children('span').css('width')) + 10;
				$(this).css('background-position', spanWidth+'px');
			});
			
			$('#cases-managers-table-container table.jtable').addClass('case-clients-list');
			$('#cases_managers_checkbox_all').attr('checked', false);
		},
		fields: {
			cases_id: {
				title: function(data) {
					if (cases_manager_type != 'all') {
						return '<input id="cases_managers_checkbox_all" type="checkbox" value="1" />';
					}
					else
					{
						return '&nbsp;';
					}
				},
				sorting: false,
				width: '20px',
				display: function(data) {
					cases_account_index++;
					if (cases_manager_type != 'all') {
						return '<input id="cases_managers_checkbox_' + cases_account_index + '" type="checkbox" class="case_managers_checkbox" value="1" data="{index: \'' + cases_account_index + '\'}"/>';
					}
				}
			},
			attorney_name: {
				title: 'Attorney',
				list: true,
				width: '150px'
			},
			patient: {
				title: 'Patient',
				list: true,
				width: '150px',
				display: function(data) {
					return data.record.last_name + ' ' + data.record.first_name + ' ' + data.record.middle_name;
				}
			},
			account: {
				title: 'Account',
				list: true,
				width: '70px'
			},
			case_category: {
				title: 'Class',
				list: true,
				width: '100px'
			},
			accident_date: {
				title: 'DOA',
				list: true,
				width: '100px'
			},
			status: {
				title: 'Status',
				list: true,
				width: '100px'
			},
			db_name: {
				title: 'Database',
				list: true,
				width: '100px',
				display: function(data) {
					return data.record.db_name.toUpperCase();
				}
			},
			buttons: {
				title: '',
				sorting: false,
				display: function(data) {
					var buttons = '';
					if (cases_manager_type == 'unassigned' || cases_manager_type == 'all' && data.record.assigned == 0) {
						buttons += '<span id="cases_managers_' + cases_account_index + '" style="text-decoration:underline; cursor:pointer" class="btn_cases_assigned" data="{index: \'' + cases_account_index + '\'}">Assign</span>';
					}
					if (cases_manager_type == 'assigned' || cases_manager_type == 'all' && data.record.assigned == 1) {
						buttons += '&nbsp;<span id="cases_managers_' + cases_account_index + '" style="text-decoration:underline; cursor:pointer" class="btn_cases_unassigned" data="{index: \'' + cases_account_index + '\'}">Unassign</span>';
					}
					return buttons;
				}
			}
		}
	});
	
	var chkSelector = 'tr td:first-child :checkbox';

	$('.jtable ' + chkSelector).live('click', function(e) {
		var $table = $(this).parents('table');
		var lastRow = $table.data('lastRow');
		var thisRow = $(this).parents('tr').index();
	
		if (lastRow !== undefined && e.shiftKey) {
		  var numChecked = 0;
		  var start = lastRow < thisRow ? lastRow : thisRow;
		  var end = lastRow > thisRow ? lastRow : thisRow;
//		  console.log($table	.find(chkSelector));
		  $table	.find(chkSelector).slice(start, end).prop('checked', true);
		  if ($table.hasClass('case-documents-list')) {
		  	$table.find('input[type="checkbox"]').each( function(){
//				console.log($(this));
				if ($(this).is(':checked'))
					$(this).parents('tr').removeClass('jtable-row-even').addClass('case-documents-list-selected')
			});
		  }
		}
		$table.data('lastRow', thisRow);
	});

    function setMaxServiceDate(data)
    {
        if (data.serverResponse.hasOwnProperty('msdHtml')) {
            $('#max_service_date').html(data.serverResponse.msdHtml);
        }

        if (data.serverResponse.hasOwnProperty('MaxServiceDateOpts')) {
            $('#client_cases_search_transactions').append(data.serverResponse.MaxServiceDateOpts);
        } else if (data.serverResponse.hasOwnProperty('MaxServiceDate')) {
            if (data.serverResponse.MaxServiceDate.MaxServiceDate != '' && data.serverResponse.MaxServiceDate.MaxServiceDate != null) {
                var date1 = Date.parse(data.serverResponse.MaxServiceDate.MaxServiceDate.date);
                var date2 = new Date();
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                for (var i = 1; i < diffDays; ++i) {
                    var newDate = new Date(date1.getFullYear(), date1.getMonth(), date1.getDate() + i);
                    $('#client_cases_search_transactions').append('<option value="' + (newDate.getTime() / 1000) + '">' + newDate.toString('MM/dd/yyyy') + '</option>')
                }
            }
        }
    }

	// comment table of summary
	$('#summary-table-container').jtable({
		title: '',
		paging: false,
		pageSize: jtablePageSize,
		sorting: false,
		columnResizable: false,
		defaultSorting: 'company ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_summary_case_table'
		},
		recordsLoaded: function(e, data) {
			$('#summary-table-container .jtable-bottom-panel').remove();
			//$('#summary-table-container .jtable tbody tr:last-child').addClass('totalLine');
			$('#summary-table-container .jtable tbody td:contains("Total")').parent().addClass('totalLine');
			$('#summary-table-container .jtable th').css('text-align', 'right');
			$('#summary-table-container .jtable th span').css('font-weight', 'bold');
			$('#summary-table-container .jtable th:first-child').css('text-align', 'left').css('padding-left', '5px');
			$('#summary-table-container .jtable td').css('text-align', 'right').css('font-weight', 'bold');
			$('#summary-table-container .jtable td:first-child').css('text-align', 'left').css('font-weight', 'normal').css('padding-left', '5px');
			$('#summary-table-container .jtable').css('border-bottom', '1px solid #cfcfcf');
			//console.log(data.serverResponse.MaxServiceDate[0].MaxServiceDate);
			if (reloadTransactionsDate) {
				$('#client_cases_search_transactions option[value!="0"]').remove();
				setMaxServiceDate(data);
			} else {
                var $date = $('#client_cases_search_transactions').val();
                if ($date != 0) {
                    $date = new Date($date * 1000);
                    $('#max_service_date').html($date.toString('MMM dd, yyyy'));
                } else {
                    setMaxServiceDate(data);
                }
            }
			if (data.serverResponse.hasOwnProperty('company')) {
				var $lob = $('#statement_lob'),
                    $finance = $('#statement_finance'),
                    $company = [];

                $lob.find('option').remove();
                $lob.append('<option value="all">All</span>');
                for (var $i = 0; $i < data.serverResponse.company.length; ++$i)
                {
                    var name = data.serverResponse.company[$i];
					var key;
					if (name == 'MED, LLC') {
						key = 'RX';
					} else {
						key = name;
					}
                    $lob.append('<option value="' + key + '">' + name + '</span>');
                }

                $finance.prop('disabled', true).val('all');
			}
		},
		fields: {
			company: {
				title: 'Company',
				sorting: false,
				width: '200px'
			},
			charges: {
				title: 'Charges',
				list: true,
				width: '150px',
				display: function(data) {
					if (data.record.charges != '&nbsp;') return '$' + number_format(data.record.charges, 2, '.', ',');
				}
			},
			payments: {
				title: 'Payments',
				list: true,
				width: '150px',
				display: function(data) {
					if (data.record.payments < 0)
					{
						var style = ' style="color: red"';
					}
					else
					{
						var style = '';
					}
					if (data.record.payments != '&nbsp;') {
						var str = '<span'+style+'>';
						str += data.record.payments < 0 ? '&ndash;' : '';
						var pmnts = data.record.payments < 0 ? 0 - data.record.payments : data.record.payments;
						str += '$' + number_format(pmnts, 2, '.', ',') + '</span>';
						
						return str;
					}
				}
			},
			adjustments: {
				title: 'Adjustments',
				list: true,
				width: '150px',
				display: function(data) {
					if (data.record.adjustments < 0)
					{
						var style = ' style="color: red"';
					}
					else
					{
						var style = '';
					}
					if (data.record.adjustments != '&nbsp;') {
						var str = '<span'+style+'>';
						str += data.record.adjustments < 0 ? '&ndash;' : '';
						var adjmts = data.record.adjustments < 0 ? 0 - data.record.adjustments : data.record.adjustments;
						str += '$' + number_format(adjmts, 2, '.', ',') + '</span>';
						
						return str;
					}
				}
			},
			balance: {
				title: 'Balance',
				list: true,
				width: '150px',
				display: function(data) {
					if (data.record.balance < 0)
					{
						var style = ' style="color: red"';
					}
					else
					{
						var style = '';
					}
					if (data.record.balance != '&nbsp;') {
						var str = '<span'+style+'>';
						str += data.record.balance < 0 ? '&ndash;' : '';
						var balance = data.record.balance < 0 ? 0 - data.record.balance : data.record.balance;
						str += '$' + number_format(balance, 2, '.', ',') + '</span>';
						
						return str;
					}
				}
			}
		}
	});
	
		// comment table of visits summary
	$('#visits-table-container').jtable({
		title: '',
		paging: false,
		pageSize: jtablePageSize,
		sorting: false,
		columnResizable: false,
		defaultSorting: 'dov ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_visits_summary_table'
		},
		recordsLoaded: function() {
			var p_ids_tr = new Array();
			var k = 0;
			$('#visits-table-container .jtable-bottom-panel').remove();
			$('#visits-table-container .jtable').addClass('visit-summary-detail2');
			$('#visits-table-container .jtable th span').css('font-weight', 'bold');
			$('#visits-table-container .jtable th').css('text-align', 'right');
			$('#visits-table-container .jtable th:nth-child(3)').css('text-align', 'left');
			$('#visits-table-container .jtable th:first-child').css('text-align', 'left').css('padding-left', '5px');
			$('#visits-table-container .jtable td').css('text-align', 'right').css('font-weight', 'bold');
			$('#visits-table-container .jtable td:nth-child(3)').css('text-align', 'left').css('font-weight', 'normal').css('padding-left', '5px');
			var trs = $.find('#visits-table-container .jtable tr');
			for (var i = 0; i < trs.length; i++) {
				if (trs[i].innerHTML.indexOf('company-total') > 0) {
					trs[i].className = 'visit-summary-detail2-total';
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:first-child').css('background-color', '#d9d9d9').css('border-bottom', '1px solid grey').css('border-top', '1px solid #d9d9d9');
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:nth-child(2)').css('background-color', '#d9d9d9').css('border-bottom', '1px solid grey').css('border-top', '1px solid #d9d9d9');
				} else if (trs[i].innerHTML.indexOf('grand-total') > 0) {
					
				} else if (trs[i].innerHTML.indexOf('company-name') > 0) {
					trs[i].className = 'visit-summary-detail2-name';
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:first-child').addClass('visit-summary-detail-open');
					$('#visits-table-container .jtable tr:nth-child(' + i + ')').attr('id', 'company_' + i);
				} else {
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:nth-child(2)').addClass('visit-summary-detail-closed');
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:first-child').css('background-color', '#d9d9d9');
					$('#visits-table-container .jtable tr:nth-child(' + i + ') td:nth-child(2)').css('background-color', '#d9d9d9');
					$('#visits-table-container .jtable tr:nth-child(' + i + ')').attr('id', 'payment_' + i);
					if (i > 0)
						p_ids_tr[k++] = i;
				}
			}
			//console.log(p_ids_tr);
			for (var i = 0; i < p_ids_tr.length; i++) {
				$('#payment_' + p_ids_tr[i]).after('<tr id="tr_payment_' +  p_ids_tr[i] + '" style="display: none"><td style="background-color:#d9d9d9"></td><td style="background-color:#d9d9d9"></td><td id="td_payment_' +  p_ids_tr[i] + '" colspan="5" style="padding-right: 0px"></td></tr>');
			}
		},
		fields: {
			col1: {
				title: '',
				width: '20px'
			},
			col2: {
				title: '',
				width: '20px',
				display: function(data) {
					if (data.record.companyName != '') {
						return '<span data="{company: \'' + data.record.companyName + '\', dov: \'' + data.record.dov + '\', sequence: \'' + data.record.SequenceNo + '\'}" />';
					}
				}
			},
			dov: {
				title: 'DOV',
				width: '300px',
				display: function(data) {
					if (data.record.dov != '') {
						return data.record.dov;
					} else if (data.record.company != '') {
						if (data.record.charges == '') {
							return '<span class="company-name" style="margin-left: -25px; font-weight: bold">' + data.record.company + '</span>';
						} else {
							return data.record.company;
						}
					}
				}
			},
			charges: {
				title: 'Charges',
				list: true,
				width: '120px',
				display: function(data) {
					if (data.record.charges != '') {
						return '$' + number_format(data.record.charges,2,'.',',');
					}
				}
			},
			payments: {
				title: 'Payments',
				list: true,
				width: '120px',
				display: function(data) {
					if (data.record.payments != '') 
					{
						if (data.record.payments < 0)
						{
							var style = ' style="color: red"';
						}
						else
						{
							var style = '';
						}
						if (data.record.payments != '&nbsp;') {
							var str = '<span'+style+'>';
							str += data.record.payments < 0 ? '&ndash;' : '';
							var pmnts = data.record.payments < 0 ? 0 - data.record.payments : data.record.payments;
							str += '$' + number_format(pmnts, 2, '.', ',') + '</span>';
							
							return str;
						}
					}
				}
			},
			adjustments: {
				title: 'Adjustments',
				list: true,
				width: '120px',
				display: function(data) {
					if (data.record.adjustments != '') 
					{						
						if (data.record.adjustments < 0)
						{
							var style = ' style="color: red"';
						}
						else
						{
							var style = '';
						}
						if (data.record.adjustments != '&nbsp;') {
							var str = '<span'+style+'>';
							str += data.record.adjustments < 0 ? '&ndash;' : '';
							var pmnts = data.record.adjustments < 0 ? 0 - data.record.adjustments : data.record.adjustments;
							str += '$' + number_format(pmnts, 2, '.', ',') + '</span>';
							
							return str;
						}
					} 
					else 
					{
						if (data.record.charges != '')
							return '$0.00';
					}
				}
			},
			balance: {
				title: 'Balance',
				list: true,
				width: '120px',
				display: function(data) {
					if (data.record.balance != '') 
					{						
						if (data.record.balance < 0)
						{
							var style = ' style="color: red"';
						}
						else
						{
							var style = '';
						}
						if (data.record.balance != '&nbsp;') {
							var str = '<span'+style+'>';
							str += data.record.balance < 0 ? '&ndash;' : '';
							var pmnts = data.record.balance < 0 ? 0 - data.record.balance : data.record.balance;
							str += '$' + number_format(pmnts, 2, '.', ',') + '</span>';
							
							return str;
						}
					} 
					else 
					{
						if (data.record.charges != '')
							return '$0.00';
					}
				}
			}
		}
	});
	
	// comment table of appointments
	$('#appointments-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'date ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_appointments_table'
		},
		recordsLoaded: function(e, data) {
			$('table.jtable th.jtable-column-header').each(function(index, element) {
				var spanWidth =  parseInt($(this).children('div').children('span').css('width'));
				var tdWidth = parseInt($(this).css('width'));
				var backgroundPosition = parseInt( (tdWidth + spanWidth)/ 2) + 30;
				$(this).children('div').css('background-position', backgroundPosition+'px');
			});
			$('#appointments-table-container table.jtable').addClass('case-appointments-list');
			$( "#search-appt_date_from-value" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				prevText : '',
				nextText: '',
				onClose: function( selectedDate ) {
					$( "#search-appt_date_to-value" ).datepicker( "option", "minDate", selectedDate );
				}
			});
				
			$( "#search-appt_date_to-value" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				prevText : '',
				nextText: '',
				onClose: function( selectedDate ) {
					$( "#search-appt_date_from-value" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
		},
		fields: {
			date: {
				title: 'Date',
				list: true,
				width: '175px'
			},
			time: {
				title: 'Time',
				list: true,
				sorting: false,
				width: '80px'
			},
			provider: {
				title: 'Provider',
				list: true,
				width: '150px'
			},
			reason: {
				title: 'Reason',
				list: true,
				width: '150px'
			},
			location: {
				title: 'Location',
				list: true,
				width: '150px'
			},
			status: {
				title: 'Status',
				list: true,
				width: '150px'
			}
		}
	});
	
	// comment table of documents
	$('#documents-table-container').jtable({
		title: '',
		paging: false,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'date_of_service ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_documents_table'
		},
		recordsLoaded: function(e, data) {
			$('#count_documents_div').html($.assocArraySize(data.records) + ' documents found');
			$('table.jtable th.jtable-column-header').each(function(index, element) {
				var spanWidth =  parseInt($(this).children('div').children('span').css('width'));
				var tdWidth = parseInt($(this).css('width'));
				var backgroundPosition = parseInt( (tdWidth + spanWidth)/ 2) + 10;
//				console.log(tdWidth + ' --- ' + spanWidth + ' --- ' + backgroundPosition);
				$(this).children('div').css('background-position', backgroundPosition+'px');
			});
			if (data.records.length > 0) {
				$('#btn_documents_open').attr('disabled', false);
				$('#btn_documents_open').css('color', 'white');
				$('#document_checkbox_all').attr('disabled', false);
			} else {
				$('#btn_documents_open').attr('disabled', true);
				$('#btn_documents_open').css('color', 'grey');
				$('#document_checkbox_all').attr('disabled', true);
			}
			$('#documents-table-container table.jtable').addClass('case-documents-list');
		},
		fields: {
			document_id: {
				title: '<input id="document_checkbox_all" type="checkbox" value="1"  style="margin-left: 3px" disabled="disabled"/>',
				sorting: false,
				width: '20px',
				display: function(data) {
					return '<input id="document_checkbox_' + data.record.id + '_' + data.record.lPAGEID 
						+'" type="checkbox" class="case_dosuments_checkbox" name="document_checkbox[]" value="' 
						+ data.record.files + '"/>';
				}
			},
			date_of_service: {
				title: 'DOS',
				list: true,
				width: '100px'
			},
			document_type: {
				title: 'Document Type',
				list: true,
				width: '200px'
			},
			document_name: {
				title: 'Document',
				list: true,
				width: '350px'
			},
			open_section: {
				title: '',
				sorting: false,
				width: '100px',
				display: function(data) {
//					if (data.record.FileType == 'doc') {
					return '<span id="open_document_' + data.record.id + '_' + data.record.lPAGEID 
						+ '" style="text-decoration:underline; cursor:pointer" class="btn_document_open" data="{index: \'' 
						+ data.record.id + '\', pageID: \'' + data.record.lPAGEID + '\'}">Open</span>';
//					} else {
//						return '<a href="' + data.record.full_path + '" target="_blank">Open</a>';
//					}
				}
			}
		}
	});
	
	// comment table of reports mileage
	$('#mileage-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'last_name ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_mileage_report_table'
		},
		loadingRecords: function(e, data) {
			cases_account_id = -1;
		},
		recordsLoaded: function(e, data) {
			cases_account = new Array();
			if (data.records.length > 0) {
				$('.jtable-bottom-panel').show();
				cases_account = data.records;
			} else {
				$('.jtable-bottom-panel').hide();
			}
			$('#mileage-table-container').css('font-size', '12px');
		},
		fields: {
			last_name: {
				title: 'Last Name',
				list: true,
				width: '150px'
			},
			first_name: {
				title: 'First Name',
				list: true,
				width: '150px'
			},
			account: {
				title: 'Account',
				list: true,
				width: '150px'
			},
			case_category: {
				title: 'Class',
				list: true,
				width: '150px'
			},
			accident_date: {
				title: 'DOA',
				list: true,
				width: '125px'
			},
			calculate: {
				title: '',
				sorting: false,
				width: '150px',
				display: function(data) {
					cases_account_id++;
					return '<div align="right"><input id="calculate_distance_' + cases_account_id + '" type="button" class="btn_calculate_distance_row" data="{index: \'' + cases_account_id + '\', name: \'' + data.record.first_name + ' ' + data.record.last_name + '\'}" value="Calculate Distance" /></div>';
				}
			}
		}
	});
	
	// comment table of calculate distance
	$('#distance-table-container').jtable({
		title: '',
		paging: false,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'date ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_calculate_distance_table'
		},
		recordsLoaded: function(event, data) {
			if (data.serverResponse.Message)
			{
				display_text_message(data.serverResponse.Message, 300, 200);
			}
			else
			{
				$('#result_export_to_file').show();
			}
			$('#distance-table-container div.jtable-column-header-container').each(function(index, element) {
				var spanWidth = parseInt($(this).children('span').css('width')) + 10;
				$(this).css('background-position', spanWidth+'px');
			});
		},
		fields: {
			date: {
				title: 'Date',
				list: true,
				width: '80px'
			},
			time: {
				title: 'Time',
				list: true,
				sorting: false,
				width: '80px'
			},
			provider: {
				title: 'Provider',
				list: true,
				width: '120px'
			},
			reason: {
				title: 'Reason',
				list: true,
				width: '100px'
			},
			location: {
				title: 'Location',
				list: true,
				width: '120px'
			},
			distance: {
				title: 'Distance',
				list: true,
				width: '80px'
			}
		}
	});
	
	// comment table of discharge and client list
	$('#discharge-table-container').jtable({
		title: '',
		paging: true,
		pageSize: jtablePageSize,
		sorting: true,
		columnResizable: false,
		defaultSorting: 'patient ASC',
		saveUserPreferences: false,
		columnSelectable: false,
		actions: {
			listAction: baseURL + ajaxCONTROLLER + '/get_discharge_clients_table'
		},
		recordsLoaded: function(e, data) {
			cases_account = new Array();
			if (data.records.length > 0) {
//				$('#btn_cases_summary').attr('disabled', false);
//				$('#cases_client_checkbox_all').attr('disabled', false);
//				$('#count_cases_div').html(data.serverResponse.TotalRecordCount + ' patient cases found');
				$('.jtable-bottom-panel').show();
				cases_account = data.records;
			} else {
//				$('#btn_cases_summary').attr('disabled', true);
//				$('#cases_client_checkbox_all').attr('disabled', true);
//				$('#client_cases_search_details').attr('disabled', true);
				$('#checkbox_with_docs').attr('disabled', true);
				$('.jtable-bottom-panel').hide();
//				$('#count_cases_div').html('0 patient cases found');
			}
//			if ($('#btn_cases_summary').hasClass('opacity25') == false)
//				$('#btn_cases_summary').addClass('opacity25');

			$('#discharge-table-container div.jtable-column-header-container').each(function(index, element) {
				var spanWidth = parseInt($(this).children('span').css('width')) + 10;
				$(this).css('background-position', spanWidth+'px');
			});
			cases_account_index = -1;
			
			$( "#search-accident_date-from-value" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  changeYear: true,
			  numberOfMonths: 1,
			  prevText : '',
			  nextText: '',
			  onClose: function( selectedDate ) {
				$( "#search-accident_date-to-value" ).datepicker( "option", "minDate", selectedDate );
			  }
			});
			
			 $( "#search-accident_date-to-value" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  changeYear: true,
			  numberOfMonths: 1,
			  prevText : '',
			  nextText: '',
			  onClose: function( selectedDate ) {
				$( "#search-accident_date-from-value" ).datepicker( "option", "maxDate", selectedDate );
			  }
			});
			
			$( "#search-discharge_date-from-value" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  changeYear: true,
			  numberOfMonths: 1,
			  prevText : '',
			  nextText: '',
			  onClose: function( selectedDate ) {
				$( "#search-discharge_date-to-value" ).datepicker( "option", "minDate", selectedDate );
			  }
			});
			
			 $( "#search-discharge_date-to-value" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  changeYear: true,
			  numberOfMonths: 1,
			  prevText : '',
			  nextText: '',
			  onClose: function( selectedDate ) {
				$( "#search-discharge_date-from-value" ).datepicker( "option", "maxDate", selectedDate );
			  }
			});
			
		},
		fields: {
			patient: {
				title: 'Patient',
				list: true,
				width: '120px',
				display: function(data) {
					return  data.record.first_name + ' ' + data.record.middle_name + ' ' + data.record.last_name;
				}
			},
			account: {
				title: 'Account',
				list: true,
				width: '120px'
			},
			case_category: {
				title: 'Class',
				list: true,
				width: '120px'
			},
			accident_date: {
				title: 'DOA',
				list: true,
				width: '200px'
			},
			discharge_date: {
				title: 'Discharge Date',
				list: true,
				width: '200px'
			},
			status: {
				title: 'Status',
				list: true,
				width: '120px'
			},
			/*balance: {
				title: 'Balance',
				list: true,
				width: '100px',
				display: function(data) {
					if (data.record.balance != '') {
						return '$' + data.record.balance;
					} else {
						if (data.record.charges != '')
							return '$0';
					}
				}
			}*/
			summary: {
				title: '',
				sorting: false,
				display: function(data) {
					cases_account_index++;
					return '<span id="summary_' + cases_account_index + '" style="text-decoration:underline; cursor:pointer" class="btn_discharge_report_summary" data="{index: \'' + cases_account_index + '\'}">Summary</span>';
				},
				width: '60px'
			}			
		}
	});
	
	$('#jtable-page-size').live('change',function () {
		jtablePageSize = $(this).val();
		$('#' + tableName + '-table-container').jtable({
			pageSize: jtablePageSize
		});
		$('#' + tableName + '-table-container').jtable('reload');		
	});
	
	$('#search-clear').live('click', function() {
		if (tableName == 'appointments') {
			var jj = $('#client_cases_list option:selected').val();
			$('#' + tableName + '-table-container').jtable('load', {
				sAccountID: cases_account[jj].account,
				sDbName: cases_account[jj].db_name,
				sPractice: cases_account[jj].practice,
				sPatient: cases_account[jj].patient,
				sCaseNo: cases_account[jj].case_no
			});
			$( "#search-appt_date_from-value" ).datepicker( "option", "maxDate", null );
			$( "#search-appt_date_to-value" ).datepicker( "option", "minDate", null );
		} else if (tableName == 'distance') {
			var jj = cases_account_id;
			$('#' + tableName + '-table-container').jtable('load', {
				sAccountID: cases_account[jj].account,
				sDbName: cases_account[jj].db_name,
				sPractice: cases_account[jj].practice,
				sPatient: cases_account[jj].patient,
				sCaseNo: cases_account[jj].case_no,
				typeDist : $('[name=clients_address_wish]:checked').val(),
				customAddress : $('#custom_address').val(),
			});	
		} else if (tableName == 'discharge') {
			$( "#search-accident_date-from-value" ).datepicker( "option", "maxDate", null );
			$( "#search-accident_date-to-value" ).datepicker( "option", "minDate", null );
			$( "#search-discharge_date-from-value" ).datepicker( "option", "maxDate", null );
			$( "#search-discharge_date-to-value" ).datepicker( "option", "minDate", null );
			$('#' + tableName + '-table-container').jtable('load', {
				extAttyID: $('#discharge_attorneys_list').val()
			});	
		} else {
			var parent = $(this).parents('table.jtable').parent().parent();
			if (parent.length)
			{
				parentID = parent.attr('id');
				$('#' + parentID).jtable('load');
			} 
			else
			{
				$('#' + tableName + '-table-container').jtable('load');
			}
		}
		$(this).remove();
		sVal = '';
		$('input[id*="search"]').each(function(index, element) {
            $(this).val('');
			$(this).attr('checked', false);
        });
	});
		
	$('#search-username').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-last_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-first_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-is_primary').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-role_id').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-username').live('click', function() {
		sortingFieldName = 'username';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-last_name').live('click', function(e) {
		sortingFieldName = 'last_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-first_name').live('click', function(e) {
		sortingFieldName = 'first_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-is_primary').live('click', function(e) {
		sortingFieldName = 'is_primary';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-role_id').live('click', function(e) {
		sortingFieldName = 'role_id';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-middle_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-phone').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-file_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-description').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-weight').mouseleave(function(e) {
        $('#sorting-digit').remove();
		sortingFieldName = '';
    });
	
	$('#search-email').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-middle_name').live('click', function() {
		sortingFieldName = 'middle_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-phone').live('click', function(e) {
		sortingFieldName = 'phone';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-email').live('click', function(e) {
		sortingFieldName = 'email';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-name').live('click', function() {
		sortingFieldName = 'name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-file_name').live('click', function() {
		sortingFieldName = 'file_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-description').live('click', function() {
		sortingFieldName = 'description';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-weight').live('click', function() {
		sortingFieldName = 'weight';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingDigitHolder);
		$('#sorting-digit').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-practices_count').live('click', function() {
		sortingFieldName = 'practices_count';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingDigitHolder);
		$('#sorting-digit').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-client_name').live('click', function() {
		sortingFieldName = 'client_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-client_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-practices_count').mouseleave(function(e) {
        $('#sorting-digit').remove();
		sortingFieldName = '';
    });

	$('#search-practice_name').live('click', function() {
		sortingFieldName = 'practice_name';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-practice_name').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-microdb').live('click', function() {
		sortingFieldName = 'microdb';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-microdb').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });

	$('#search-rundown').live('click', function() {
		sortingFieldName = 'rundown';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-rundown').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-reason').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	
	$('#search-provider').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-location').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-status').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		if (tableName != 'discharge')
			sortingFieldName = '';
    });
	
	$('#search-balance').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-date').mouseleave(function(e) {
        $('#sorting-date').remove();
		sortingFieldName = '';
    });
	
	$('#search-patient').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		if (tableName != 'discharge')
			sortingFieldName = '';
    });
	
	$('#search-accident_date').mouseleave(function(e) {
        $('#sorting-date').remove();
		sortingFieldName = '';
    });
	
	$('#search-account').mouseleave(function(e) {
        $('#sorting-date').remove();
		if (tableName != 'discharge')
			sortingFieldName = '';
    });
	
	$('#search-distance').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		sortingFieldName = '';
    });
	
	$('#search-case_category').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		if (tableName != 'discharge')
			sortingFieldName = '';
    });
	
	$('#search-class').mouseleave(function(e) {
        $('#sorting-alpha').remove();
		if (tableName != 'discharge')
			sortingFieldName = '';
    });
	
	$('#search-distance').live('click', function() {
		sortingFieldName = 'distance';
		var offset = $(this).offset();
		var dialog_left = 0;
		var dialog_top = 0;
		if ($('#dialog-calculate-distance') != undefined) {
			var offset_dialog = $('#dialog-calculate-distance').offset();
			dialog_left = offset_dialog.left;
			dialog_top = offset_dialog.top;
		}
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-51-dialog_left).css('top',offset.top+19-dialog_top);
		$(this).trigger('mouseover');
	});
	
	$('#search-date').live('click', function() {
		sortingFieldName = 'date';
		var offset = $(this).offset();
		var dialog_left = 0;
		var dialog_top = 0;
		if (dbName == 'distance') {
			var offset_dialog = $('#dialog-calculate-distance').offset();
			dialog_left = offset_dialog.left;
			dialog_top = offset_dialog.top;
		}
		var top = $(this).css('top');
		$(this).append(sortingDateHolder);
		$('#sorting-date').css('left',offset.left-1-dialog_left).css('top',offset.top+19-dialog_top);
		$(this).trigger('mouseover');
	});
	
	$('#search-discharge_date_between').live('click', function() {
		sortingFieldName = 'discharge_date_between';
		sQriteria = 'sorting-between';
		sVal = $('#search-discharge_date-from-value').val();
		sVal2 = $('#search-discharge_date-to-value').val();
//		console.log(sortingFieldName + ' ' + sQriteria + ' ' + sVal);
		$('input[id*="search-"]').each(function(index, element) {
            $(this).val('');
			$(this).attr('checked',false);
        });
		$('#search-discharge_date-from-value').val(sVal);
		$('#search-discharge_date-to-value').val(sVal2);
		$('#search-clear').remove();
		if (sVal.length > 0 || sVal2.length > 0)
		{
			if (tableName == 'discharge') {
				$('#' + tableName + '-table-container').jtable('load', {
					extAttyID: $('#discharge_attorneys_list').val(),
					sortingQriteria: sQriteria,
					sortingFieldName: sortingFieldName,
					sortingValue: sVal,
					sortingValue2: sVal2
				});				
			}
			$('#search-'+sortingFieldName).after('<input id="search-clear" type="button" class="jtable-button-search-clear" align="absmiddle" alt="Clear Search" title="Clear Search" />');
		}		
	});
	
	$('#search-accident_date_between').live('click', function() {
		sortingFieldName = 'accident_date_between';
		sQriteria = 'sorting-between';
		sVal = $('#search-accident_date-from-value').val();
		sVal2 = $('#search-accident_date-to-value').val();
//		console.log(sortingFieldName + ' ' + sQriteria + ' ' + sVal);
		$('input[id*="search-"]').each(function(index, element) {
            $(this).val('');
			$(this).attr('checked',false);
        });
		$('#search-accident_date-from-value').val(sVal);
		$('#search-accident_date-to-value').val(sVal2);
		$('#search-clear').remove();
		if (sVal.length > 0 || sVal2.length > 0)
		{
			if (tableName == 'discharge') {
				$('#' + tableName + '-table-container').jtable('load', {
					extAttyID: $('#discharge_attorneys_list').val(),
					sortingQriteria: sQriteria,
					sortingFieldName: sortingFieldName,
					sortingValue: sVal,
					sortingValue2: sVal2
				});				
			}
			$('#search-'+sortingFieldName).after('<input id="search-clear" type="button" class="jtable-button-search-clear" align="absmiddle" alt="Clear Search" title="Clear Search" />');
		}		
	});
	
	$('#search-reason').live('click', function() {
		sortingFieldName = 'reason';
		var offset = $(this).offset();
		var dialog_left = 0;
		var dialog_top = 0;
		if (dbName == 'distance') {
			var offset_dialog = $('#dialog-calculate-distance').offset();
			dialog_left = offset_dialog.left;
			dialog_top = offset_dialog.top;
		}
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1-dialog_left).css('top',offset.top+19-dialog_top);
		$(this).trigger('mouseover');
	});
	
	$('#search-patient').live('click', function() {
		sortingFieldName = 'patient';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-provider').live('click', function() {
		sortingFieldName = 'provider';
		var offset = $(this).offset();
		var dialog_left = 0;
		var dialog_top = 0;
		if (dbName == 'distance') {
			var offset_dialog = $('#dialog-calculate-distance').offset();
			dialog_left = offset_dialog.left;
			dialog_top = offset_dialog.top;
		}
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1-dialog_left).css('top',offset.top+19-dialog_top);
		$(this).trigger('mouseover');
	});
	
	$('#search-balance').live('click', function() {
		sortingFieldName = 'balance';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
		
	$('#search-location').live('click', function() {
		sortingFieldName = 'location';
		var offset = $(this).offset();
		var dialog_left = 0;
		var dialog_top = 0;
		if (dbName == 'distance') {
			var offset_dialog = $('#dialog-calculate-distance').offset();
			dialog_left = offset_dialog.left;
			dialog_top = offset_dialog.top;
		}
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1-dialog_left).css('top',offset.top+19-dialog_top);
		$(this).trigger('mouseover');
	});
	
	$('#search-status').live('click', function() {
		sortingFieldName = 'status';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-case_category').live('click', function() {
		sortingFieldName = 'case_category';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-class').live('click', function() {
		sortingFieldName = 'class';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingAlphaHolder);
		$('#sorting-alpha').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-account').live('click', function() {
		sortingFieldName = 'account';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingDateHolder);
		$('#sorting-date').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});
	
	$('#search-accident_date').live('click', function() {
		sortingFieldName = 'accident_date';
		var offset = $(this).offset();
		var top = $(this).css('top');
		$(this).append(sortingDateHolder);
		$('#sorting-date').css('left',offset.left-1).css('top',offset.top+19);
		$(this).trigger('mouseover');
	});

	$('#search-split_charges').live('click', function() {
		sortingFieldName = 'split_charges';
		sQriteria = 'sorting-equal';
		if ($('#search-'+sortingFieldName+'-value').is(':checked'))
			sVal = 1;
		else
			sVal = 0;
		$('#search-clear').remove();		
		$('input[id*="search"]').each(function(index, element) {
            $(this).val('');
        });
		$('#' + tableName + '-table-container').jtable('load', {
			sortingFieldName: sortingFieldName,
			sortingValue: sVal,
			sortingQriteria: sQriteria
		});
		$('#search-'+sortingFieldName).after('<input id="search-clear" type="button" class="jtable-button-search-clear" align="absmiddle" alt="Clear Search" title="Clear Search" />');
	});
	
	$('.jtable-sorting-container div').live('click',function() {	
		sQriteria = $(this).attr('id');
		sVal = $('#search-'+sortingFieldName+'-value').val();
		$('input[id*="search"]').each(function(index, element) {
            $(this).val('');
			$(this).attr('checked',false);
        });
		$('#search-'+sortingFieldName+'-value').val(sVal);
		$('#search-clear').remove();
		if (sVal.length > 0)
		{
			if (tableName == 'appointments') {
				var jj = $('#client_cases_list option:selected').val();
				$('#' + tableName + '-table-container').jtable('load', {
					sAccountID: cases_account[jj].account,
					sDbName: cases_account[jj].db_name,
					sPractice: cases_account[jj].practice,
					sPatient: cases_account[jj].patient,
					sCaseNo: cases_account[jj].case_no,
					sortingFieldName: sortingFieldName,
					sortingValue: sVal,
					sortingQriteria: sQriteria
				});				
			} else if (tableName == 'distance') {
				var jj = cases_account_id;
				$('#' + tableName + '-table-container').jtable('load', {
					sAccountID: cases_account[jj].account,
					sDbName: cases_account[jj].db_name,
					sPractice: cases_account[jj].practice,
					sPatient: cases_account[jj].patient,
					sCaseNo: cases_account[jj].case_no,
					typeDist : $('[name=clients_address_wish]:checked').val(),
					customAddress : $('#custom_address').val(),
					sortingFieldName: sortingFieldName,
					sortingValue: sVal,
					sortingQriteria: sQriteria
				});				
			} else if (tableName == 'discharge') {
				$('#' + tableName + '-table-container').jtable('load', {
					extAttyID: $('#discharge_attorneys_list').val(),
					sortingFieldName: sortingFieldName,
					sortingValue: sVal,
					sortingQriteria: sQriteria
				});				
			} else {
				var parent = $(this).parents('table.jtable').parent().parent();
				if (parent.length)
				{
					parentID = parent.attr('id');
					$('#' + parentID).jtable('load', {
						sortingFieldName: sortingFieldName,
						sortingValue: sVal,
						sortingQriteria: sQriteria
					});
				} 
				else
				{
					$('#' + tableName + '-table-container').jtable('load', {
						sortingFieldName: sortingFieldName,
						sortingValue: sVal,
						sortingQriteria: sQriteria
					});
				}
			}
			$('#search-'+sortingFieldName).after('<input id="search-clear" type="button" class="jtable-button-search-clear" align="absmiddle" alt="Clear Search" title="Clear Search" />');
		}
	});
			
	$('#btn_activity_log_search').live('click', function() {
		var sUsers = $('#activity_log_search_users');
		var sEvents = $('#activity_log_search_events');
        $('#activity-log-table-container').jtable('load', {
 				user_id: sUsers.val(),
				event_name: sEvents.val(),
        });
	});
	
	$('#btn_activity_log_search_clear').live('click', function() {
		$('#activity_log_search_users').val('');
		$('#activity_log_search_events').val('');
		$('#activity-log-table-container').jtable('load');
	});
	
	$('table.jtable div.jtable-column-header-container').each(function(index, element) {
        var spanWidth = parseInt($(this).children('span').css('width')) + 10;
		$(this).css('background-position', spanWidth+'px');
    });
	
	
	$('table.table div.table-header-container').each(function(index, element) {
        var spanWidth = parseInt($(this).children('span').css('width')) + 10;
		$(this).css('background-position', spanWidth+'px');
    });
	
	$('#btn_client_cases_search').live('click',function() {
		var sName = $('#client_cases_name').val(),
			sAccount = $('#client_cases_account').val(),
		    sSSN = $('#client_cases_ssn').val(),
		    sDateFrom = $('#client_cases_date_from').val(),
		    sDateTo = $('#client_cases_date_to').val(),
            sClass = $('#client_cases_category_like').val(),
		    sMyCases = $('#my_cases').is(':checked') ? true : false,
		    sCompany = $('#client_cases_search_company').val(),
            sFinancial = $('#client_cases_search_financial').val(),
		    sTransactions = $('#client_cases_search_transactions').val(),
		    sCasesType = $('#cases_activity_type').val(),
            sAttyType = $('[name="client_cases_attorneys"]:checked').val();

		sAttys = [];
		$('#div_attorneys_list input:checkbox:checked').each(function() {
			sAttys.push($(this).val());
		});
		
		if (sAttys.length == 0 && (userRole != 'Biller' || sAttyType == 'my')) {
			display_text_message('Please select at least one attorney.', 320, 150);
			return;
		}
		
		if ( sName.length == 0 && sAccount.length == 0 && sSSN.length == 0 && sDateFrom.length == 0 && sDateTo.length == 0 && sClass.length == 0 && sAttys.length == 0 && sCompany == '') {
			display_text_message('Please select at least one parameter.', 320, 150);
			return;
		}		
		
		sTypeDate = $('input:radio[name=client_cases_dates]:checked').val();

		$('#' + tableName + '-table-container').jtable('load', {
			sName: sName,
			sAccount: sAccount,
			sSSN: sSSN,
			sDateFrom: sDateFrom,
			sDateTo: sDateTo,
			sTypeDate: sTypeDate,
			sClass: sClass,
            sCasesType: sCasesType,
			sAttys: sAttys,
			sMyCases: sMyCases,
            sCompany: sCompany,
            sFinancial: sFinancial,
            sTransactions: sTransactions
		});
	});

    $('#btnSearchExport').live('click', function() {
        export_to_file('xls');
    });

	$('#btn_client_cases_new_search').live('click',function() {
		newCaseSearch();
	});
	
	$('#btn_client_cases_register_search').live('click',function() {
		$('#prompt_func_name').val('newCaseSearch');
		$("#dialog-prompt-message-text").html('Form data will be lost. Are you sure you want to leave page?');
		$("#dialog-prompt-message").dialog('open');
	});
		
	$('#new_case_register_back').live('click',function(e) {
		e.preventDefault();
		$('#prompt_func_name').val('newCaseRegisterBack');
		$("#dialog-prompt-message-text").html('Form data will be lost. Are you sure you want to leave page?');
		$("#dialog-prompt-message").dialog('open');
	});
		
	$('.search_field_cases').keyup(function(e) {
		// Enter pressed?
		if (e.which == 13) 
		{
			$('#btn_client_cases_search, #btn_client_cases_new_search, #btn_client_cases_register_search').trigger('click');
		}
    });
	
	$('#search-appt_date_between').live('click', function() {
		sortingFieldName = 'appt_date_between';
		sQriteria = 'sorting-between';
		sVal = $('#search-appt_date_from-value').val();
		sVal2 = $('#search-appt_date_to-value').val();
//		console.log(sortingFieldName + ' ' + sQriteria + ' ' + sVal);
		$('input[id*="search-"]').each(function(index, element) {
            $(this).val('');
			$(this).attr('checked',false);
        });
		$('#search-appt_date_from-value').val(sVal);
		$('#search-appt_date_to-value').val(sVal2);
		$('#search-clear').remove();
		if (sVal.length > 0 || sVal2.length > 0)
		{
			var jj = $('#client_cases_list option:selected').val();
			$('#appointments-table-container').jtable('load', {
				sAccountID: cases_account[jj].account,
				sDbName: cases_account[jj].db_name,
				sPractice: cases_account[jj].practice,
				sPatient: cases_account[jj].patient,
				sCaseNo: cases_account[jj].case_no,
				sortingQriteria: sQriteria,
				sortingFieldName: sortingFieldName,
				sortingValue: sVal,
				sortingValue2: sVal2
			});
			$('#search-'+sortingFieldName).after('<input id="search-clear" type="button" class="jtable-button-search-clear" align="absmiddle" alt="Clear Search" title="Clear Search" />');
		}		
	});
	
});

function appendSearchBar()
{
	return;
}

function appendUserSearchBar()
{
	$('table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-username-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-username" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-last_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-last_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-first_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-first_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-is_primary-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-is_primary" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-role_id-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-role_id" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td>&nbsp;</td></tr>');
}

function appendUserDashboardSearchBar()
{
	$('#dashboard-user-table-container table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-username-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-username" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-last_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-last_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-first_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-first_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-is_primary-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-is_primary" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');
}

function appendMarketerSearchBar()
{
	$('table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-last_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-last_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-first_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-first_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-middle_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-middle_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-phone-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-phone" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-email-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-email" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');
}

function appendFormSearchBar()
{
	$('table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-file_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-file_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-description-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-description" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-weight-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-weight" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');
}

function appendFormDashboardSearchBar()
{
	$('#dashboard-form-table-container table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-file_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-file_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');
}

function appendClientSearchBar()
{
	$('table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-client_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-client_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-practices_count-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-practices_count" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td>&nbsp;</td></tr>');
}

function appendPracticeSearchBar()
{
	$('table.jtable thead').after('<tr class="jtable-search-bar"><td>&nbsp;</td><td>&nbsp;</td><td><div style="float:left;"><input id="search-practice_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-practice_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-microdb-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-microdb" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-rundown-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-rundown" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-split_charges-value" type="checkbox" align="absmiddle" /></div><div id="search-split_charges" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');
}

function appendAppointmentsSearchBar()
{
	$('#appointments-table-container table.jtable thead').after('<tr id="appointments-search" class="jtable-search-bar"><td><div style="float:left;"><input id="search-appt_date_from-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /> to <input id="search-appt_date_to-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /></div><div id="search-appt_date_between" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td>&nbsp;</td><td><div style="width:135px; margin: 0 auto;"><div style="float:left;"><input id="search-provider-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-provider" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></div></td><td><div style="width:135px; margin: 0 auto;"><div style="float:left;"><input id="search-reason-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-reason" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></div></td><td><div style="width:135px; margin: 0 auto;"><div style="float:left;"><input id="search-location-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-location" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></div></td><td><div style="width:135px; margin: 0 auto;"><div style="float:left;"><input id="search-status-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-status" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></div></td></tr>');
}

function appendMileageSearchBar()
{
	$('#mileage-table-container table.jtable thead').after('<tr id="mileage-search" class="jtable-search-bar"><td><div style="float:left;"><input id="search-last_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-last_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-first_name-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-first_name" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-account-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-account" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-class-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-class" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-accident_date-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-accident_date" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td></td></tr>');

	$( "#search-accident_date-value" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: ''
    });
}

function appendDistanceSearchBar()
{
//	console.log('123');
	$('#distance-table-container table.jtable thead').after('<tr id="distance-search" class="jtable-search-bar"><td><div style="float:left;"><input id="search-date-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-date" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td>&nbsp;</td><td><div style="float:left;"><input id="search-provider-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-provider" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-reason-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-reason" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-location-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-location" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-distance-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-distance" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td></tr>');

	$( "#search-date-value" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
	  prevText : '',
	  nextText: ''
    });
}

function appendDischargeSearchBar()
{
	$('table.jtable thead').after('<tr id="discharge-search" class="jtable-search-bar"><td><div style="float:left;"><input id="search-patient-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-patient" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-account-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-account" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-case_category-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-case_category" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-accident_date-from-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /> to <input id="search-accident_date-to-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /></div><div id="search-accident_date_between" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-discharge_date-from-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /> to <input id="search-discharge_date-to-value" type="text" class="jtable-search-field" style="width:65px !important" align="absmiddle" /></div><div id="search-discharge_date_between" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td><div style="float:left;"><input id="search-status-value" type="text" class="jtable-search-field" align="absmiddle" /></div><div id="search-status" style="float:left;"><input type="button" class="jtable-button-search" align="absmiddle" /></div></td><td></td></tr>');
}

function appendNotificationSearchBar()
{
	return;
}

function appendPageSize()
{
	$('.jtable-page-info').before('<span class="jtable-page-size">Page size: <select id="jtable-page-size"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></span>');
	$('#jtable-page-size').val(jtablePageSize);
}

function newCaseSearch()
{
	sName = $('#client_cases_name').val();
	sPhone = $('#client_cases_phone').val();
	sSSN = $('#client_cases_ssn').val();
	sDOB = $('#client_cases_dob').val();
	
	if (sName.length > 0 || sPhone.length > 0 || sSSN.length > 0 || sDOB.length > 0)
	{	
		$('.new-case-registration').hide();
		$('#' + tableName + '-table-container').show();
		$('#' + tableName + '-table-container').jtable('load', {
			sName: sName,
			sPhone: sPhone,
			sSSN: sSSN,
			sDOB: sDOB
		});
	}
	else
	{
		display_text_message('Please select at least one parameter.', 320, 150);
	}	
}

function newCaseRegisterBack()
{
	window.location = $('#new_case_register_back').attr('href');
}