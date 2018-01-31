// JavaScript Document

$(function () {
    var $dialogPleaseWait = $("#dialog-please-wait"),
        $dialogPromptMessage = $("#dialog-prompt-message"),
        $dialogChangePassword = $("#dialog-change-password"),
        $dialogAddUser = $("#dialog-add-new-user");

    // ------------------------------------------
    // initialize please wait dialog box
    // ------------------------------------------
    $dialogPleaseWait.dialog({
        bgiframe: true,
        resizable: false,
        autoOpen: false,
        modal: true,
        draggable: false,
        height: 240,
        width: 240,
        buttons: {},
        create: function () {
            var elem = $('#' + $(this).attr('id')),
                parent = elem.parent();

            elem.css('padding', '15px');
            parent.children('.ui-dialog-titlebar').remove();
        }
    }); //dialog_please_wait

    $dialogPleaseWait.dialog('open');
    $dialogPleaseWait.dialog('close');

    // ------------------------------------------
    // initialize general message dialog box
    // ------------------------------------------
    $("#dialog-general-message").dialog({
        bgiframe: true,
        resizable: false,
        height: 200,
        width: 400,
        autoOpen: false,
        modal: true,
        draggable: false,
        buttons: {
            'Ok': function () {
                $(this).dialog('close');
            }
        },
        close: function () {
            $("#dialog-general-message-text").html("");
        }
    }); //dialog_general_message

    // ------------------------------------------
    // initialize prompt message dialog box
    // ------------------------------------------
    $dialogPromptMessage.dialog({
        bgiframe: true,
        resizable: false,
        height: 200,
        width: 400,
        autoOpen: false,
        modal: true,
        draggable: false,
        buttons: {
            'Ok': function () {
                var funcName = $('#prompt_func_name');

                if (funcName.val()) {
                    window[funcName.val()]();
                }
                $(this).dialog('close');
            },
            'Cancel': function () {
                $(this).dialog('close');
            }
        },
        close: function () {
            $('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
            $("#dialog-prompt-message-text").html("");
        }
    }); //dialog_prompt_message

    $dialogPromptMessage.dialog('open');
    $dialogPromptMessage.dialog('close');

    // ------------------------------------------
    // initialize change password dialog box
    // ------------------------------------------
    $dialogChangePassword.dialog({
        bgiframe: true,
        resizable: false,
        height: 310,
        width: 400,
        autoOpen: false,
        modal: true,
        draggable: false,
        close: function () {
            $("#new-password").val('');
            $("#new-password-confirm").val('');
            $('#change-password-form').find('input:text').each(function () {
                this.type = 'password';
            });
            $('#new-password-show').attr('checked', false);
        }
    }); //dialog_change_password

    $dialogChangePassword.dialog('open');
    $dialogChangePassword.dialog('close');

    /*
     * USER MAINTENANCE DIALOG AND FUNCTIONALITY
     */
    $dialogAddUser.dialog({
        autoOpen: false,
        height: 480,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "userMaintenance",
        buttons: {
            "Save": function () {
                saveUserData();
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        },
        open: function () {
            var $attorneyTree = $('#firms-attorneys-tree');

            $attorneyTree.find('.collapsable ul').hide();
            $attorneyTree.find('.collapsable .collapsable-hitarea').removeClass('collapsable-hitarea').addClass('expandable-hitarea');
            $attorneyTree.find('.collapsable').removeClass('collapsable').addClass('expandable');
            $attorneyTree.find('.lastCollapsable').removeClass('lastCollapsable').addClass('lastExpandable');
        },
        close: function () {
            clearForm('user-maintenance');
            $('#view_id').val(0);
            $('.fnTabs').hide();
            $('#tabs').find('li').removeClass('tab_error');
            $('.user-general').show();
            $('#dialog-popup-content-title').html('Add New User');
            $('.fnAppendedPrimaries').remove();
        }
    });

    $dialogAddUser.dialog('open');
    $dialogAddUser.dialog('close');

    $('#btn-add-new-user').live('click', function () {
        $('#dialog-add-new-user').dialog("open");

        $('#tabs li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $('#user-general').addClass('tabs-state-active');

        if ($('#view_id').val() != 0) {
            $('#linked-firms-new').hide();
            $('#linked-firms').show();
            $('#password-maintenance').show();
            $('#last-login-date').show();
        } else {
            $('#linked-firms-new').show().append(linkedFirms);
            $('#linked-firms').hide();
            $('#password-maintenance').hide();
            $('#last-login-date').hide();
            $('input[name*="all_attorneys_"]').each(function () {
                $(this).attr('checked', true);
                var firm_id = $(this).metadata().firm_id;
                $('input[name="attorneys[]"]').each(function (index, element) {
                    if ($(this).metadata().firm_id == firm_id) {
                        $(this).attr('checked', true);
                    }
                });
            });
            $('input[name="is_primary"]').each(function (index, element) {
                if ($(this).val() == userPrimaryFirm.firm_id) {
                    $(this).attr('checked', true);
                }
            });

            $('input[name="attorneys[]"]').each(function (index, element) {
                $(this).attr('checked', false);
            });

            if ($.assocArraySize(userPrimaryFirm)) {
                for (var i = 0; i < userPrimaryFirm.attys.length; ++i) {
                    $('input[name="attorneys[]"]').each(function (index, element) {
                        var metadata = $(this).metadata();
                        if ($(this).val() == userPrimaryFirm.attys[i] && metadata.firm_id == userPrimaryFirm.firm_id)
                            $(this).attr('checked', true);
                    });
                }
            }

            $('#high_charges').attr('checked', false);

            $('input[name*="high_charge_level"]').attr('disabled', true).val('');
        }
    });

    $('#high_charges').live('click', function () {
        if ($(this).is(':checked')) {
            $('.user-highcharges input[type="text"]').attr('disabled', false).val('');
        }
        else {
            $('.user-highcharges input[type="text"]').attr('disabled', true).val('');
        }
    });

    /*
     * MARKETER DIALOG AND FUNCTIONALITY
     */
    $("#dialog-add-new-marketer").dialog({
        autoOpen: false,
        height: 460,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "marketerMaintenance",
        buttons: {
            "Save": function () {
                saveMarketerData();
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        },
        close: function () {
            clearForm('marketer-maintenance');
            $('.fnTabs').hide();
            $('.marketer-general').show();
            $('#dialog-popup-content-title').html('Add New Marketer');
            $('#marketer-maintenance #view_id').val('');
        }
    });

    $("#dialog-add-new-marketer").dialog('open');
    $("#dialog-add-new-marketer").dialog('close');

    $('#btn-add-new-marketer').live('click', function () {
        $('#dialog-add-new-marketer').dialog("open");
        $('#tabs li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $('#marketer-general').addClass('tabs-state-active');
        if ($('#view_id').val()) {
            $('#linked-marketer-new').hide();
            $('#linked-marketer').show();
            $('#last-login-date').show();
        }
        else {
            $('#linked-marketer-new').show();
            $('#linked-marketer').hide();
            $('#last-login-date').hide();
        }
    });

    /*
     * MARKETER DIALOG AND FUNCTIONALITY
     */
    $("#dialog-calculate-distance").dialog({
        autoOpen: false,
        height: 190,
        width: 960,
        modal: true,
        closeText: "close",
        draggable: true,
        resizable: false,
        dialogClass: "calculateDistance",
        close: function () {
            clearForm('calculate-distance');
            $('.calculate-general').show();
            $('#distance-table-container').hide();
            $(this).dialog('option', 'height', 190);
            $('[name=clients_address_wish]').attr('checked', false);
            try {
                $('#distance-table-container').jtable('load', {
                    sAccountID: 0,
                    sDbName: '',
                    sPractice: 0,
                    sPatient: 0,
                    sCaseNo: 0,
                    typeDist: '',
                    customAddress: ''
                });
            } catch (e) {
            }
            tableID = 'Mileage';
            tableName = 'mileage';
            dbName = 'mileage';
            $('table.jtable tr#distance-search').remove();
            $('#result_export_to_file').hide();
        }
    });

    $("#dialog-calculate-distance").dialog('open');
    $("#dialog-calculate-distance").dialog('close');

    $('.btn_calculate_distance_row').live('click', function () {
        $('#dialog-popup-content-title').html('Calculate Distance for <i>' + $(this).metadata().name + '</i>');
        $('#dialog-calculate-distance').dialog("open");
        $('#calculate-general').addClass('tabs-state-active');
        tableID = 'Distance';
        tableName = 'distance';
        dbName = 'distance';
        cases_account_id = $(this).metadata().index;
        $('#distance-table-container div.jtable-column-header-container').each(function (index, element) {
            var spanWidth = parseInt($(this).children('span').css('width')) + 10;
            $(this).css('background-position', spanWidth + 'px');
        });
    });

    $('#btn_calculate_wish_address').live('click', function () {
        if ($('[name=clients_address_wish]').is(':checked')) {
            if ($('#custom_address').val() == '' && $('[name=clients_address_wish]:checked').val() == 'address') {
                display_text_message('Please specify address.', 300, 200);
                return;
            }
            $('table.jtable tr#distance-search').remove();
            $('#distance-table-container').show();
            $('#dialog-calculate-distance').dialog('option', 'height', '450');
            $('#distance-table-container').jtable('load', {
                sAccountID: cases_account[cases_account_id].account,
                sDbName: cases_account[cases_account_id].db_name,
                sPractice: cases_account[cases_account_id].practice,
                sPatient: cases_account[cases_account_id].patient,
                sCaseNo: cases_account[cases_account_id].case_no,
                typeDist: $('[name=clients_address_wish]:checked').val(),
                customAddress: $('#custom_address').val()
            });
            window['append' + tableID + 'SearchBar']();
        }
    });

    $('[name=clients_address_wish]').live('click', function () {
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

    $('#btn-add-new-form').live('click', function () {
        isFormUpdated = false;
        $('#dialog-add-new-form').dialog("open");
        $('#tabs li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $('#form-general').addClass('tabs-state-active');
        if ($('#view_id').val()) {
            $('#linked-form-new').hide();
            $('#linked-form').show();
            $('#last-login-date').show();
        }
        else {
            $('#linked-form-new').show();
            $('#linked-form').hide();
            $('#last-login-date').hide();
            $('#file_name_uploaded').html('');
            $('#file_upload').show();
            $('#file_delete').hide();
        }
    });

    $("#dialog-add-new-form").dialog({
        autoOpen: false,
        height: 420,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "formMaintenance",
        buttons: {
            "Save": function () {
                saveFormData();
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        },
        close: function () {
            clearForm('form-maintenance');
            $('.fnTabs').hide();
            $('.form-general').show();
            $('#dialog-popup-content-title').html('Add New Forms');
            if (isFormUpdated) {
                isFormUpdated = false;
                $('#form-table-container').jtable('load');
            }
            $('#form-maintenance view_id').val('0');
        }
    });

    $("#dialog-add-new-form").dialog('open');
    $("#dialog-add-new-form").dialog('close');

    $('#btn_forms_delete_file').live('click', function () {
        rowID = $('#view_id').val();
        $('#prompt_func_name').val('deleteFileForm');
        $("#dialog-prompt-message-text").html('Are you sure you want to delete uploaded file?');
        $("#dialog-prompt-message").dialog('open');
    });

    $('#btn_portal_settings_delete_logo').live('click', function () {
        rowID = $('#view_id').val();
        $('#prompt_func_name').val('deleteLogoPortalSettings');
        $("#dialog-prompt-message-text").html('Are you sure you want to delete logo?');
        $("#dialog-prompt-message").dialog('open');
    });

    $('.fnEditRow').live('click', function () {
        if (userRole != 'Biller') {
            rowID = $(this).attr('id');
            if ($.assocArraySize($(this).metadata())) {
                tableID = $(this).metadata().tableID;
                tableName = $(this).metadata().tableName;
            }
            window[tableName + 'EditData']();
        }
    });

    $('.fnShowNotification').live('click', function () {
        rowID = $(this).attr('id');
        if ($.assocArraySize($(this).metadata())) {
            tableID = $(this).metadata().tableID;
            tableName = $(this).metadata().tableName;
        }
        window[tableName + 'EditData']();
    });

    $('#tabs li').live('click', function () {
        $(this).removeClass('tab_error');
        if ($(this).hasClass('ui-state-disabled')) return;
        $('.fnTabs').hide();
        $('#tabs li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $(this).addClass('tabs-state-active');
        $('.' + $(this).attr('id')).show();
        if ($(this).attr('id') == 'user-attorneys') {
            var top = $('#firms-attorneys-tree li:first').attr('id');
            $('#firms_attorneys_list').scrollTo($('#' + top));
        }
    });

    $('.tabs_cases li').live('click', function () {
        if ($(this).hasClass('ui-state-disabled') || cases_account_id < 0) return;

        if ($(this).attr('id') == 'li-cases-search') {
            $('#header_cases_search_div').show();
            $('#btn_client_cases_advanced_search').show();
            if (userRole == 'Biller') {
                $('#btn_client_cases_advanced_search').trigger('click');
            }
            $('#btn_client_cases_back_to_results').hide();
            $('#box_client_search').show();
            $('#box_client_case_detail').hide();
            $('#cases-table-container, #cases-new-table-container').show();
            $('.tabs_cases li').addClass('ui-state-disabled');
            $('#li-cases-search').removeClass('ui-state-disabled');

            setBulkLOB();

            $('#btn_cases_summary_div').show();
            $('#header_cases_summary_div').hide();
            $('#client_cases_search_details').prop('disabled', true);
            $('#client_cases_search_transactions').prop('disabled', true);
            $('#checkbox_with_docs').prop('disabled', true);
            $('.right_menu_ctr').html('Search Cases');
            $('#cases-table-container div.jtable-column-header-container, #cases-new-table-container div.jtable-column-header-container').each(function (index, element) {
                var spanWidth = parseInt($(this).children('span').css('width')) + 10;
                $(this).css('background-position', spanWidth + 'px');
            });

            $('.tabs_cases li').each(function (index, element) {
                if ($(this).attr('id') != 'li-cases-search')
                    $(this).addClass('ui-state-disabled');
            });

            if ($('#cases-table-container').length) tableName = 'cases';
            else tableName = 'cases-new';
            tableID = '';
            dbName = 'cases';
            cases_account_id = -1;
        } else {
            $('#header_cases_search_div').hide();
            $('#btn_client_cases_advanced_search').hide();
            $('#btn_client_cases_back_to_results').show();
            $('#box_client_search').hide();
            $('#box_client_case_detail').show();
            $('#cases-table-container').hide();
            $('#btn_cases_summary_div').hide();
            $('#header_cases_summary_div').show();
            $('#client_cases_search_details').prop('disabled', false);
            $('#client_cases_search_transactions').prop('disabled', false);
            $('#checkbox_with_docs').prop('disabled', false);
        }

        if ($(this).attr('id') == 'li-cases-summary') {
            $('.right_menu_ctr').html('Case Summary');
            $('#summary_text_div').show();
            $('#summary-table-container').show();
            tableID = '';
            tableName = 'summary';
            dbName = 'summary';
            load_summary_table();
        } else {
            if ($(this).attr('id') != 'li-cases-statement') {
                $('#summary_text_div').hide();
                $('#summary-table-container').hide();
            }
        }

        if ($(this).attr('id') == 'li-cases-visits') {
            $('#btn_visits_all_details_div').show();
            $('.right_menu_ctr').html('Visit Summary');
            $('#visits-table-container').show();
            tableID = '';
            tableName = 'visits';
            dbName = 'visits';
            load_visits_table();
        } else {
            if ($(this).attr('id') != 'li-cases-statement') {
                $('#btn_visits_all_details_div').hide();
                $('#visits-table-container').hide();
            }
        }

        if ($(this).attr('id') == 'li-cases-appointments') {
            $('.right_menu_ctr').html('Appointment History');
            $('#appointments-table-container').show();
            tableID = 'Appointments';
            tableName = 'appointments';
            dbName = 'appointments';
            $('#appointments-table-container table.jtable tr#appointments-search').remove();
            appendAppointmentsSearchBar();
            load_appointments_table();
        } else {
            if ($(this).attr('id') != 'li-cases-statement') {
                $('#appointments-table-container').hide();
                $('table.jtable tr#appointments-search').hide();
            }
        }

        if ($(this).attr('id') == 'li-cases-documents') {
            $('.right_menu_ctr').html('Case Documents');
            $('#btn_documents_open_div').show();
            $('#documents-table-container').show();
            tableID = '';
            tableName = 'documents';
            dbName = 'documents';
            load_documents_table();
        } else {
            if ($(this).attr('id') != 'li-cases-statement') {
                $('#btn_documents_open_div').hide();
                $('#documents-table-container').hide();
            }
        }

        if ($(this).attr('id') != 'li-cases-statement') {
            $('.tabs_cases li').each(function (index, element) {
                $(this).removeClass('tabs-state-active');
            });
            $(this).addClass('tabs-state-active');
            $('.' + $(this).attr('id')).show();
        }

        if ($(this).attr('id') == 'li-cases-contact') {
            $('#contact-form-container').show();
        } else {
            if ($(this).attr('id') != 'li-cases-statement') {
                $('#contact-form-container').hide();
            }
        }

    });

    $('#tabs_assigned li').live('click', function () {
        if ($(this).attr('id') == 'li-unassigned-search') {
            if ($(this).hasClass('tabs-state-active')) {

            } else {
                load_cases_managers_change_tab('unassigned');
                $('#btn_cases_assigned_div').show();
                $('#count_cases_div').html('');
            }
            cases_manager_type = 'unassigned';
        } else {
            $('#btn_cases_assigned_div').hide();
            $('#btn_cases_assigned').attr('disabled', 'disabled');
        }

        if ($(this).attr('id') == 'li-assigned-search') {
            if ($(this).hasClass('tabs-state-active')) {

            } else {
                load_cases_managers_change_tab('assigned');
                $('#btn_cases_unassigned_div').show();
                $('#count_cases_div').html('');
            }
            cases_manager_type = 'assigned';
        } else {
            $('#btn_cases_unassigned_div').hide();
            $('#btn_cases_unassigned').attr('disabled', 'disabled');
        }

        if ($(this).attr('id') == 'li-all-search') {
            if ($(this).hasClass('tabs-state-active')) {

            } else {
                load_cases_managers_change_tab('all');
                $('#count_cases_div').html('');
            }
            cases_manager_type = 'all';
        }

        $('#tabs_assigned li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $(this).addClass('tabs-state-active');
        $('.' + $(this).attr('id')).show();
    });

    $('#tabs_discharge li').live('click', function () {

        if ($(this).attr('id') == 'li-discharge-summary') {
            $('.right_menu_ctr').html('Case Summary');
            $('#summary_text_div').show();
            $('#summary-table-container').show();
            tableID = '';
            tableName = 'summary';
            dbName = 'summary';
            load_summary_table();
        } else {
            if ($(this).attr('id') != 'li-discharge-statement') {
                $('#summary_text_div').hide();
                $('#summary-table-container').hide();
            }
        }

        if ($(this).attr('id') == 'li-discharge-visits') {
            $('#btn_visits_all_details_div').show();
            $('.right_menu_ctr').html('Visit Summary');
            $('#visits-table-container').show();
            tableID = '';
            tableName = 'visits';
            dbName = 'visits';
            load_visits_table();
        } else {
            if ($(this).attr('id') != 'li-discharge-statement') {
                $('#btn_visits_all_details_div').hide();
                $('#visits-table-container').hide();
            }
        }

        if ($(this).attr('id') == 'li-discharge-appointments') {
            $('.right_menu_ctr').html('Appointment History');
            $('#appointments-table-container').show();
            tableID = 'Appointments';
            tableName = 'appointments';
            dbName = 'appointments';
            $('#appointments-table-container table.jtable tr#appointments-search').remove();
            appendAppointmentsSearchBar();
            load_appointments_table();
        } else {
            if ($(this).attr('id') != 'li-discharge-statement') {
                $('#appointments-table-container').hide();
                $('table.jtable tr#appointments-search').hide();
            }
        }

        if ($(this).attr('id') == 'li-discharge-documents') {
            $('.right_menu_ctr').html('Case Documents');
            $('#btn_documents_open_div').show();
            $('#documents-table-container').show();
            tableID = '';
            tableName = 'documents';
            dbName = 'documents';
            load_documents_table();
        } else {
            if ($(this).attr('id') != 'li-discharge-statement') {
                $('#btn_documents_open_div').hide();
                $('#documents-table-container').hide();
            }
        }

        if ($(this).attr('id') != 'li-discharge-statement') {
            $('#tabs_discharge li').each(function (index, element) {
                $(this).removeClass('tabs-state-active');
            });
            $(this).addClass('tabs-state-active');
            $('.' + $(this).attr('id')).show();
        }

    });

    $('#userrole').change(function () {
        $('.fnPerms').hide();
        $('.fnOnePerm').each(function () {
            $(this).attr('checked', false);
        });
        $('#' + $(this).val()).show();
    });

    $('#user-notif-select-all').live('click', function () {
        $('input[name="notifications[]"]').each(function (index, element) {
            $(this).attr('checked', true);
        });
    });

    $('#user-notif-unselect-all').live('click', function () {
        $('input[name="notifications[]"]').each(function (index, element) {
            $(this).attr('checked', false);
        });
    });

    $('#username').bind('change keyup', function () {
        if ($('#view_id').val() == 0) {
            $('#username-ajax-loader').show();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/check_unique_username',
                dataType: 'html',
                data: {username: $('#username').val()},
                success: function (data) {
                    uniqueUsername = data;
                    if (uniqueUsername == 'false') {
                        $('#username').addClass('ui-state-error');
                        $('#username-msg').html('Username is already exists.');
                    }
                    else {
                        $('#username').removeClass('ui-state-error');
                        $('#username-msg').html('');
                    }
                },
                complete: function () {
                    $('#username-ajax-loader').hide();
                },
                error: function (data) {
                    // display Error message
                    display_text_message('Error');
                }
            });
        }
    });

    $('#user-maintenance #email').bind('change keyup', function () {
        if ($('#view_id').val() == 0) {
            $('#email-ajax-loader').show();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/check_unique_email',
                dataType: 'html',
                data: {email: $('#email').val()},
                success: function (data) {
                    uniqueEmail = data;
                    if (uniqueEmail == 'false') {
                        $('#email').addClass('ui-state-error');
                        $('#email-msg').html('Email is already exists.');
                    }
                    else {
                        $('#email').removeClass('ui-state-error');
                        $('#email-msg').html('');
                    }
                },
                complete: function () {
                    $('#email-ajax-loader').hide();
                },
                error: function (data) {
                    // display Error message
                    display_text_message('Error');
                }
            });
        }
    });

    $('input[name*="all_attorneys_"]').live('click', function () {
        var firm_id = $(this).metadata().firm_id;
        var checked;
        if ($(this).is(':checked')) checked = true;
        else checked = false;
        $('input[name="attorneys[]"]').each(function (index, element) {
            if ($(this).metadata().firm_id == firm_id) {
                $(this).attr('checked', checked);
            }
        });

        $('#firms-attorneys-tree .collapsable ul').hide();
        $('#firms-attorneys-tree .collapsable .collapsable-hitarea').removeClass('collapsable-hitarea').addClass('expandable-hitarea');
        $('#firms-attorneys-tree .collapsable').removeClass('collapsable').addClass('expandable');
        $('#firms-attorneys-tree .lastCollapsable').removeClass('lastCollapsable').addClass('lastExpandable');

        var tree = $('#scroll_firm_' + firm_id);
        if (tree.hasClass('lastExpandable')) tree.addClass('lastCollapsable').removeClass('lastExpandable');
        if (tree.hasClass('expandable')) {
            tree.removeClass('expandable').addClass('collapsable');
            tree.children('.hitarea').addClass('collapsable-hitarea').removeClass('expandable-hitarea');
            tree.children('.hitarea').each(function (index, element) {
                if ($(this).hasClass('lastExpandable-hitarea')) $(this).addClass('lastCollapsable-hitarea').removeClass('lastExpandable-hitarea');
            });
            tree.children('ul').show();
        }
        $('#firms_attorneys_list').scrollTo(tree);
    });

    $('input[name="attorneys[]"]').live('click', function () {
        var data = {
            atty_id: $(this).val(),
            firm_id: $(this).siblings('[name*="firm_"]').val(),
            firm_name: $(this).siblings('[name*="name_"]').val()
        };

        if (!$(this).is(':checked')) {
            $('input[name="all_attorneys_' + data.firm_id + '"]').each(function (index, element) {
                $(this).attr('checked', false);
            });
        }
        else {
            var checks = 0;
            var all_attorneys = 0;
            $('input[name="attorneys[]"]').each(function (index, element) {
                var firm_id = $(this).siblings('[name*="firm_"]').val();
                if (firm_id == data.firm_id) {
                    ++all_attorneys;
                    if ($(this).is(':checked')) ++checks;
                }
            });

            if ($('#linked-firms').css('display') == 'block') {
                var linked = 'linked-firms';
            }
            else {
                var linked = 'linked-firms-new';
            }

            if (!$('.firm_' + data.firm_id + '').length) {
                $('div[id*="' + linked + '"]').each(function (index, element) {
                    $(this).append('<div class="fnAppendedPrimaries firm_' + data.firm_id + '">' +
                        '<input type="hidden" id="unlink_firm_' + data.firm_id + '" name="unlink_firm_' + data.firm_id + '" value="' + data.firm_id + '" />' +
                        '<div class="fnFirmMaintenanceBox"><div class="fnUserRemoveFirm" style="float:left;">' +
                        '<img src="/images/remove-grey.png" style="border-right:1px solid #d5d5d5;" alt="Unlink Firm" title="Unlink Firm">' +
                        '</div></div><div class="folder users-dialog-firm-name">' + data.firm_name + '</div>' +
                        '<div style="clear:both;"><input type="radio" name="is_primary" value="' + data.firm_id + '" id="is_primary"> Primary <input type="checkbox" name="all_attorneys_' + data.firm_id + '" value="1" id="all_attorneys_' + data.firm_id + '" data="{firm_id: ' + data.firm_id + '}"> All Attorneys</div>');
                });
            }

            if (checks == all_attorneys) {
                if ($('#' + linked + ' input[name="all_attorneys_' + data.firm_id + '"]').length > 0) {
                    $('#' + linked + ' input[name="all_attorneys_' + data.firm_id + '"]').each(function (index, element) {
                        $(this).attr('checked', true);
                    });
                }
            }
        }
    });

    $('.fnUserLinkFirm').live('click', function () {
        var parent = $(this).parents('li');
        var firm_id = parent.children('[name*="firm_"]').val();
        var firm_name = parent.children('[name*="name_"]').val();
        if ($('#linked-firms').css('display') == 'block') {
            var linked = 'linked-firms';
        }
        else {
            var linked = 'linked-firms-new';
        }
        if (!$('.firm_' + firm_id + '').length) {
            $('div[id*="' + linked + '"]').each(function (index, element) {
                $(this).append('<div class="fnAppendedPrimaries firm_' + firm_id + '">' +
                    '<input type="hidden" id="unlink_firm_' + firm_id + '" name="unlink_firm_' + firm_id + '" value="' + firm_id + '" />' +
                    '<div class="fnFirmMaintenanceBox"><div class="fnUserRemoveFirm" style="float:left;">' +
                    '<img src="/images/remove-grey.png" style="border-right:1px solid #d5d5d5;" alt="Unlink Firm" title="Unlink Firm">' +
                    '</div></div><div class="folder users-dialog-firm-name">' + firm_name + '</div>' +
                    '<div style="clear:both;"><input type="radio" name="is_primary" value="' + firm_id + '" id="is_primary"> Primary <input type="checkbox" name="all_attorneys_' + firm_id + '" value="1" id="all_attorneys_' + firm_id + '" data="{firm_id: ' + firm_id + '}"> All Attorneys</div>');
            });
        }

        if ($('#' + linked + ' [name="is_primary"]').length == 1) {
            $('#' + linked + ' [name="is_primary"]').attr('checked', true);
        }

        if ($('#' + linked + ' input[name="all_attorneys_' + firm_id + '"]').length > 0) {
            $('#' + linked + ' input[name="all_attorneys_' + firm_id + '"]').each(function (index, element) {
                $(this).attr('checked', true);
            });
        }

        var sibling = $(this).parent().siblings('ul');
        sibling.children('li').each(function (index, element) {
            $(this).children('span').each(function (index, element) {
                $(this).children('input[type="checkbox"]').each(function (index, element) {
                    $(this).attr('checked', true);
                });
            });
        });
    });

    $('.users-dialog-firm-name').live('click', function () {
        $('#firms-attorneys-tree .collapsable ul').hide();
        $('#firms-attorneys-tree .collapsable .collapsable-hitarea').removeClass('collapsable-hitarea').addClass('expandable-hitarea');
        $('#firms-attorneys-tree .collapsable').removeClass('collapsable').addClass('expandable');
        $('#firms-attorneys-tree .lastCollapsable').removeClass('lastCollapsable').addClass('lastExpandable');
        var firm_id = $(this).siblings('div').children('input[type="radio"]').val();
        var tree = $('#scroll_firm_' + firm_id);
        if (tree.hasClass('lastExpandable')) tree.addClass('lastCollapsable').removeClass('lastExpandable');
        if (tree.hasClass('expandable')) {
            tree.removeClass('expandable').addClass('collapsable');
            tree.children('.hitarea').addClass('collapsable-hitarea').removeClass('expandable-hitarea');
            tree.children('.hitarea').each(function (index, element) {
                if ($(this).hasClass('lastExpandable-hitarea')) $(this).addClass('lastCollapsable-hitarea').removeClass('lastExpandable-hitarea');
            });
            tree.children('ul').show();
        }
        $('#firms_attorneys_list').scrollTo(tree);
    });

    $('.fnUserRemoveFirm').live('click', function () {
        var parent = $(this).parents('.fnAppendedPrimaries');
        var firm_id = parent.children('[name*="unlink_firm_"]').val();
        $('.firm_' + firm_id).remove();
        var firm = $('#firm_' + firm_id);
        var sibling = firm.siblings('ul');
        sibling.children('li').each(function (index, element) {
            $(this).children('span').each(function (index, element) {
                $(this).children('input[type="checkbox"]').each(function (index, element) {
                    $(this).attr('checked', false);
                });
            });
        });
        if ($('#linked-firms').css('display') == 'block') {
            var linked = 'linked-firms';
        }
        else {
            var linked = 'linked-firms-new';
        }
        var is_primary = 0;
        $('#' + linked + ' [name="is_primary"]').each(function (index, element) {
            if ($(this).is(':checked')) ++is_primary
        });
        if (is_primary == 0) $('#' + linked + ' [name="is_primary"]:first').attr('checked', true);
    });

    $('[id*=all_attorneys_]').live('click', function () {
        var checked = false;
        if ($(this).is(':checked')) checked = true;
        var firm_id = $(this).metadata().firm_id;
        var firm = $('#firm_' + firm_id);
        var sibling = firm.siblings('ul');
        sibling.children('li').each(function (index, element) {
            $(this).children('span').each(function (index, element) {
                $(this).children('input[type="checkbox"]').each(function (index, element) {
                    $(this).attr('checked', checked);
                });
            });
        });
    });

    $('#btn-password-change').live('click', function () {
        $('#dialog-change-password').dialog(
            'option',
            'buttons',
            [
                {
                    text: "Change", click: function () {
                    changeUserPassword();
                }
                },
                {
                    text: "Cancel", click: function () {
                    $(this).dialog('close');
                }
                }
            ]
        );
        $("#dialog-change-password").dialog('open');
    });

    $('#btn-password-reset').live('click', function () {
        $('#prompt_func_name').val('resetUserPassword');
        $("#dialog-prompt-message-text").html('<h2>Reset Password</h2>Are you sure you want to reset user password?');
        $("#dialog-prompt-message").dialog('open');
    });

    $('.fnDeleteRow').live('click', function () {
        if (userRole != 'Biller') {
            rowID = $(this).attr('id');
            if ($.assocArraySize($(this).metadata())) {
                tableID = $(this).metadata().tableID;
                tableName = $(this).metadata().tableName;
            }
            $('#prompt_func_name').val('delete' + tableID + 'Data');
            $("#dialog-prompt-message-text").html('Are you sure you want to delete ' + tableName + '?');
            $("#dialog-prompt-message").dialog('open');
        }
    });

    $('#new-password-show').live('click', function () {
        if ($(this).is(':checked')) {
            $('#change-password-form').find('input:password').each(function () {
                $(this).prop('type', 'text');
            });
        }
        else {
            $('#change-password-form').find('input:text').each(function () {
                $(this).prop('type', 'password');
            });
        }
    });

    /*
     * FIRM MAINTENANCE DIALOGS AND FUNCTIONALITY
     */

    $("#dialog-add-name-firm").dialog({
        autoOpen: false,
        height: 222,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "firmMaintenance",
        buttons: {
            "Continue to add Attorney >": function () {
                $(this).dialog('close');
                $('#dialog-attorneys').dialog('open');
            },
            "Save": function () {
                saveFirmData();
            },
            Cancel: function () {
                closeFirmNameDlg();
            }
        },
        open: function () {
            isFirmUpdated = false;
        },
        close: function () {
            clearForm('firm-name-maintenance');
        },
        open: function (event) {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').addClass('btn-continue-add-attorney');
        }
    });

    $("#dialog-add-name-firm").dialog('open');
    $("#dialog-add-name-firm").dialog('close');

    $("#dialog-attorneys").dialog({
        autoOpen: false,
        height: 480,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "firmMaintenance",
        buttons: {
            "Save": function () {
                saveAttyData();
            },
            Cancel: function () {
                $(this).dialog('close');
            }
        },
        open: function () {
            $('.fnTabs').hide();
            $('.atty-general').show();
            $('#tabs li').removeClass('tabs-state-active');
            $('#atty-general').addClass('tabs-state-active');
        },
        close: function () {
            $('#atty-maintenance #atty_firm_id').val('');
            $('#atty-maintenance #missed_appointment_threshold').val(1);
            clearForm('atty-maintenance');
            if (isFirmUpdated) {
                $(location).attr('href', $(location).attr('href'));
            }
        }
    });

    $("#dialog-attorneys").dialog('open');
    $("#dialog-attorneys").dialog('close');

    $('#btn-add-new-firm').live('click', function () {
        $('#dialog-popup-content-title').html('Add Firm');
        $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').show();
        $("#dialog-add-name-firm").dialog("open");
        if ($('#view_id').val()) {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').attr('disabled', '').removeClass('ui-state-disabled');
        }
        else {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').attr('disabled', true).addClass('ui-state-disabled');
        }
    });

    $('.fnFirmEdit').live('click', function () {
        var data = $(this).parents('li').metadata();
        $('#dialog-popup-content-title').html('Edit Firm: <em>' + data.firm_name + '</em>');
        $('#firm-name-maintenance #view_id').val(data.firm_id);
        $('#firm-name-maintenance #name').val(data.firm_name);
        $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').hide();
        $('#firm-name-maintenance #name').trigger('change');
        $("#dialog-add-name-firm").dialog("open");
    });

    $('.fnFirmAddAttorney').live('click', function () {
        var parent = $(this).parents('li');
        var data = parent.metadata();
        $('#tabs li').each(function (index, element) {
            $(this).removeClass('tabs-state-active');
        });
        $('#atty-general').addClass('tabs-state-active');
        $('#atty-assigned-list').html('<em>No MicroMD Attorneys Assigned</em>');
        $('.fnExtAtty div').addClass('check_off').removeClass('check_on');
        assignedExtAttys = {};
        $('.attys_assigned_table tbody').html('');
        $('.attys_assigned_table').hide();
        $('#btn-atty-selecting').val('Select All');
        $('#dialog-attorneys #dialog-popup-content-title').html('Add Attorney for <em>' + data.firm_name + '</em>');
        $('#atty-maintenance #atty_firm_id').val(data.firm_id);
        isFirmUpdated = false;
        $('#dialog-attorneys').dialog('open');
    });

    $('.fnAttyEdit').live('click', function () {
        var parent = $(this).parents('li');
        var data = parent.metadata();
        $('#atty-maintenance #atty_view_id').val(data.atty_id);
        attyEditData();
    });

    $('#tabs #atty-unassigned-all').live('click', function () {
        $('.fnExtAtty div').addClass('check_off').removeClass('check_on');
    });

    $('input.ui-state-error').live('focus change keyup', function () {
        $(this).removeClass('ui-state-error');
    });

    $('#atty-maintenance #last_name').live('focus change keyup', function () {
        $('#atty-maintenance #last_name').removeClass('ui-state-error');
        $('#atty-maintenance #lastname-msg').html('');
    });

    $('#atty-maintenance #first_name').live('focus change keyup', function () {
        $('#atty-maintenance #first_name').removeClass('ui-state-error');
        $('#atty-maintenance #firstname-msg').html('');
    });

    $('#user-maintenance #high_charge_level1').live('focus change keyup', function () {
        $('#user-maintenance #high_charge_level1').removeClass('ui-state-error');
        $('#user-maintenance #high_charge_level1-msg').html('').css('visibility', 'hidden');
    });

    $('#user-maintenance #high_charge_level2').live('focus change keyup', function () {
        $('#user-maintenance #high_charge_level2').removeClass('ui-state-error');
        $('#user-maintenance #high_charge_level2-msg').html('').css('visibility', 'hidden');
    });

    $('#user-maintenance #high_charge_level3').live('focus change keyup', function () {
        $('#user-maintenance #high_charge_level3').removeClass('ui-state-error');
        $('#user-maintenance #high_charge_level3-msg').html('').css('visibility', 'hidden');
    });

    $('.fnFirmDelete').live('click', function () {
        var data = $(this).parents('li').metadata();
        $('#firm-name-maintenance #view_id').val(data.firm_id);
        $('#prompt_func_name').val('deleteFirmData');
        $("#dialog-prompt-message-text").html('<h2>Delete Firm</h2>Are you sure you want to delete firm and attorneys?');
        $("#dialog-prompt-message").dialog('open');
    });

    $('.fnAttyDelete').live('click', function () {
        var data = $(this).parents('li').metadata();
        $('#atty-maintenance #atty_view_id').val(data.atty_id);
        $('#prompt_func_name').val('deleteAttyData');
        $("#dialog-prompt-message-text").html('<h2>Delete Attorney</h2>Are you sure you want to delete attorney?');
        $("#dialog-prompt-message").dialog('open');
    });

    $('#firm-name-maintenance #name').bind('change keyup', function () {
        $('#firmname-ajax-loader').show();
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/check_unique_firmname',
            dataType: 'html',
            data: {
                firmname: $('#firm-name-maintenance #name').val(),
                firm_id: $('#firm-name-maintenance #view_id').val()
            },
            success: function (data) {
                uniqueFirmname = data;
                if (uniqueFirmname == 'false') {
                    $('#firm-name-maintenance #name').addClass('ui-state-error');
                    $('#firmname-msg').html('Firm name is already exists.').show();
                }
                else {
                    $('#firm-name-maintenance #name').removeClass('ui-state-error');
                    $('#firmname-msg').html('').hide();
                }
            },
            complete: function () {
                $('#firmname-ajax-loader').hide();
            },
            error: function (data) {
                // display Error message
                display_text_message('Error');
            }
        });
    });

    /*
     * CLIENT MAINTENANCE DIALOGS AND FUNCTIONALITY
     */

    $("#dialog-add-name-client").dialog({
        autoOpen: false,
        height: 222,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "clientMaintenance",
        buttons: {
            "Continue to add Practices >": function () {
                $(this).dialog('close');
                $('#dialog-practices').dialog('open');
            },
            "Save": function () {
                saveClientData();
            },
            Cancel: function () {
                closeClientNameDlg();
            }
        },
        open: function () {
            isClientUpdated = false;
        },
        close: function () {
        },
        open: function (event) {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').addClass('btn-continue-add-attorney');
        }
    });

    $("#dialog-add-name-client").dialog('open');
    $("#dialog-add-name-client").dialog('close');

    $("#dialog-practices").dialog({
        autoOpen: false,
        height: 480,
        width: 800,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "practiceMaintenance",
        buttons: {
            "Save": function () {
                savePracticeData();
            },
            Cancel: function () {
                $(this).dialog('close');
            }
        },
        open: function () {
            $('.fnTabs').hide();
            $('.practice-general').show();
            $('#tabs li').each(function (index, element) {
                $(this).removeClass('tabs-state-active');
            });
            $('#practice-general').addClass('tabs-state-active');
            if ($('#practice-maintenance #practice-name').val() == '') {
                $('#practice-locations').addClass('ui-state-disabled');
                $('#practice-financial').addClass('ui-state-disabled');
                $('#practice-reasons').addClass('ui-state-disabled');
            }
        },
        close: function () {
            $('#practice-maintenance #practice_client_id').val('');
            $('#practice-maintenance #prictice_view_id').val('');
            $('#dialog-practices #dialog-popup-content-title').html('Add New Practice');
            clearForm('practice-maintenance');
            if (isPracticeUpdated) {
                if (sVal.length > 0) {
                    $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                        if ($(this).val()) {
                            var ID = $(this).attr('id');
                            var ary = ID.split('-');
                            sortingFieldName = ary[1];
                        }
                    });

                    $('#practice-table-container').jtable('load', {
                        sortingFieldName: sortingFieldName,
                        sortingValue: sVal,
                        sortingQriteria: sQriteria
                    });
                }
                else {
                    $('#practice-table-container').jtable('load');
                }
            }
            $('#practice-loc-add').attr('disabled', true);
            $('#practice-loc-remove').attr('disabled', true);
            $('#practice-loc-remove-all').attr('disabled', true);
            $('#practice-locs-selected option').each(function (index, element) {
                $(this).remove();
            });
            $('#practice-locs-avail option').each(function (index, element) {
                $(this).removeClass('selected');
            });

            $('.fnFinGrpTree .fnFinGrpClassesTree').children().each(function (index, element) {
                $(this).removeClass('tree-last-child').removeClass('tree-child');
                $('#fin-avail-classes').append($(this));
            });
            $('.fnFinGrpTree').remove();

            $('#split_charges').attr('checked', false);
            $('#split_mediacal_group option, #split_surgery_group option, #split_pt_chiro_group option').remove();
            $('#split_mediacal_group, #split_surgery_group, #split_pt_chiro_group').append('<option value="0">-- not set --</option>');
            $('#split_mediacal_group').val(0).attr('disabled', true);
            $('#split_surgery_group').val(0).attr('disabled', true);
            $('#split_pt_chiro_group').val(0).attr('disabled', true);
            $('#appt-reasons-table tr').remove();
            $('#appt-reason-no').show();
            if (isClientUpdated) closeClientNameDlg();
        }
    });

    $("#dialog-practices").dialog('open');
    $("#dialog-practices").dialog('close');

    $("#dialog-practices-appt-reason").dialog({
        autoOpen: false,
        height: 185,
        width: 320,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "dialog-white-box",
        buttons: {
            "Save": function () {
                saveApptReasonData();
            },
            Cancel: function () {
                $(this).dialog('close');
            }
        },
        close: function () {
            $('#ext_dbs').val('');
            $('#system_code').val('');
            $('#portal_reason').val('');
            $('#appt_map_id').val(0);
        }
    });

    $("#dialog-practices-appt-reason").dialog('open');
    $("#dialog-practices-appt-reason").dialog('close');

    $('#btn-add-new-client').live('click', function () {
        $('#dialog-popup-content-title').html('Add Client');
        $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').show();
        $("#dialog-add-name-client").dialog("open");
        if ($('#view_id').val()) {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').attr('disabled', '').removeClass('ui-state-disabled');
        }
        else {
            $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').attr('disabled', true).addClass('ui-state-disabled');
        }
    });

    $('#practice-name, #ext-db').bind('change keyup', function () {
        checkPracticeStartup();
    });

    $('#practice-locs-avail').change(function () {
        if ($(this).val()) {
            $('#practice-loc-add').attr('disabled', false);
        }
    });

    $('#practice-locs-selected').change(function () {
        if ($(this).val()) {
            $('#practice-loc-remove').attr('disabled', false);
        }
    });

    $('#practice-loc-add').live('click', function () {
        $('#practice-locs-avail option:selected').each(function (index, element) {
            if ($('#practice-locs-selected').find('option[value="' + $(this).val() + '"]').length <= 0) {
                $(this).clone().appendTo('#practice-locs-selected');
                $(this).addClass('selected');
                $('#practice-loc-remove-all').attr('disabled', false);
            }
        });
    });

    $('#practice-loc-add-all').live('click', function () {
        $('#practice-locs-avail option').each(function (index, element) {
            if ($('#practice-locs-selected').find('option[value="' + $(this).val() + '"]').length <= 0) {
                $(this).clone().appendTo('#practice-locs-selected');
                $(this).addClass('selected');
                $('#practice-loc-remove-all').attr('disabled', false);
            }
        });
    });

    $('#practice-loc-remove').live('click', function () {
        $('#practice-locs-selected option:selected').each(function (index, element) {
            $('#practice-locs-avail').find('option[value="' + $(this).val() + '"]').removeClass('selected');
            $(this).remove();
        });
        $('#practice-loc-remove').attr('disabled', true);
        if ($('#practice-locs-selected option').length == 0) {
            $('#practice-loc-remove-all').attr('disabled', true);
        }
    });

    $('#practice-loc-remove-all').live('click', function () {
        $('#practice-locs-selected option').each(function (index, element) {
            $('#practice-locs-avail').find('option[value="' + $(this).val() + '"]').removeClass('selected');
            $(this).remove();
        });
        $('#practice-loc-remove').attr('disabled', true);
        $('#practice-loc-remove-all').attr('disabled', true);
    });

    $('#fin-grps-add-box').live('click', function () {
        if ($('#practice-add-grps-box').css('display') == 'block') return;
        var offset = $(this).offset();
        $(this).append(finGrpsAddBox);
        $(this).trigger('mouseover');
    });

    $('#fin-grps-add-box').mouseleave(function (e) {
        $('#fin-grp-other').val('');
        $('#practice-add-grps-box').remove();
    });

    $('#btn-add-group-other').live('click', function () {
        var grpName = $('#fin-grp-other').val();
        if (grpName.length > 2) {
            display_please_wait();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/process_add_fin_group',
                dataType: 'html',
                data: {name: grpName},
                success: function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.code == 200 || data.code == 201) {
                        if ($('#fin-grp-tree-' + data.fin_grp_id).length) return;
                        $('#fin-grps-box')
                            .append('<ul class="fnFinGrpTree" id="fin-grp-tree-' + data.fin_grp_id + '"><li data="{grp_id:' + data.fin_grp_id + '}" class="tree-collapsable"><div class="fnFinGrpMaintenanceBox"><img src="/images/delete-black.png" alt="Remove Group" title="Remove Group" class="fnFinGrpDelete" /></div>' + grpName + '<ul class="fnFinGrpClassesTree" id="fin-grp-' + data.fin_grp_id + '"></ul></li></ul>');
                        $('#fin-grp-other').val('');
                        $('#fin-grp-' + data.fin_grp_id).sortable({
                            connectWith: '#fin-avail-classes, .fnFinGrpClassesTree',
                            placeholder: "ui-state-highlight",
                            update: function (e, ui) {
                                updateSortList(e, ui);
                            }
                        }).disableSelection();

                        if (data.code == 200) {
                            $('#fin-grp-avail').append('<div style="padding:6px 0 6px 8px;text-align:left;"><div style="float:left; width:97px;"><p style="color:#6997b1;"><strong>' + grpName + '</strong></p></div><div style="float:left;padding-top:2px;" class="fnAddFinGroup" data="{grp_id: ' + data.fin_grp_id + '}"><img src="/images/add-plus.png" width="10" height="9" /></div><div style="clear:both;height:5px;"></div></div>');
                        }
                    }
                },
                complete: function () {
                    close_please_wait();
                },
                error: function (data) {
                    // display Error message
                    display_text_message('Error. Please contact us.', 300, 200);
                }
            });
        }
    });

    $('.fnAddFinGroup').live('click', function () {
        var fin_grp_id = $(this).metadata().grp_id;
        if ($('#fin-grp-tree-' + fin_grp_id).length) return;
        var fin_grp_name;
        $(this).siblings().each(function (index, element) {
            if ($(this).text()) fin_grp_name = $(this).text();
        });

        $('#fin-grps-box')
            .append('<ul class="fnFinGrpTree" id="fin-grp-tree-' + fin_grp_id + '"><li data="{grp_id:' + fin_grp_id + '}" class="tree-collapsable"><div class="fnFinGrpMaintenanceBox"><img src="/images/delete-black.png" alt="Remove Group" title="Remove Group" class="fnFinGrpDelete" /></div>' + fin_grp_name + '<ul class="fnFinGrpClassesTree" id="fin-grp-' + fin_grp_id + '"></ul></li></ul>');
        $('#fin-grp-other').val('');
        $('#fin-grp-' + fin_grp_id).sortable({
            connectWith: '#fin-avail-classes, .fnFinGrpClassesTree',
            placeholder: "ui-state-highlight",
            update: function (e, ui) {
                updateSortList(e, ui);
            }
        }).disableSelection();

        $('#split_mediacal_group, #split_surgery_group, #split_pt_chiro_group').append('<option value="' + fin_grp_id + '">' + fin_grp_name + '</option>');
    });

    $('.fnFinGrpDelete').live('click', function (e) {
        e.stopPropagation();
        rowID = $(this).parents('li').metadata().grp_id;
        $('#prompt_func_name').val('removeFinGroup');
        $("#dialog-prompt-message-text").html('Are you sure you want to remove this financial group?<br /><br />After removing please click on Save button for the changes to take effect.');
        $("#dialog-prompt-message").dialog('open');
        $('option[value="' + rowID + '"]').remove();
    });

    $('#fin-avail-classes').sortable({
        connectWith: '#fin-avail-classes, .fnFinGrpClassesTree',
        placeholder: "ui-state-highlight",
        update: function (e, ui) {
            $(this).children('li').each(function (index, element) {
                $(this).removeClass('tree-last-child').removeClass('tree-child');
            });

            var sender = $(ui.sender);
            if (sender.hasClass('fnFinGrpClassesTree')) {
                sender.children().each(function (index, element) {
                    $(this).removeClass('tree-last-child').removeClass('tree-child').addClass('tree-child');
                });
                sender.children(':last-child').addClass('tree-last-child').removeClass('tree-child');
            }

        }
    }).disableSelection();

    $('.tree-expandable').live('click', function () {
        var ul = $(this).children('ul');
        ul.children().each(function (index, element) {
            $(this).css('display', 'list-item');
        });
        $(this).removeClass('tree-expandable').addClass('tree-collapsable');
    });

    $('.tree-collapsable').live('click', function () {
        var ul = $(this).children('ul');
        ul.children().each(function (index, element) {
            $(this).css('display', 'none');
        });
        $(this).removeClass('tree-collapsable').addClass('tree-expandable');
    });

    $('.fnFinGrpClassesTree').on("sortstop", function (event, ui) {
        console.log(ui);
    });

    $('#btn-add-appt-reason').live('click', function () {
        rowID = 0;
        $('.dialog-white-box').removeClass('ui-corner-all');
        $('#dialog-practices-appt-reason').dialog('open');
    });

    $('.fnDeleteApptReasonRow').live('click', function () {
        rowID = $(this).parents('tr').metadata().id;
        $('#prompt_func_name').val('removeApptReason');
        $("#dialog-prompt-message-text").html('Are you sure you want to remove this appt reason?<br /><br />After removing please click on Save button for the changes to take effect.');
        $("#dialog-prompt-message").dialog('open');
    });

    $('.fnEditApptReasonRow').live('click', function () {
        rowID = $(this).parents('tr').metadata().id;
        var dbID = $(this).parents('tr').metadata().db_id;
        var codeID = $(this).parents('tr').metadata().code_id;
        var mapID = $(this).parents('tr').metadata().map_id;
        $('#ext_dbs').val(dbID);
        $('#system_code').val(codeID);
        $('#portal_reason').val($('#appt-reason-portal-id-' + rowID).text());
        $('#appt_map_id').val(mapID);
        $('#dialog-practices-appt-reason').dialog('open');

    });

    $('#split_charges').live('change', function () {
        if ($(this).is(':checked')) {
            $('#split_mediacal_group').attr('disabled', false);
            $('#split_surgery_group').attr('disabled', false);
            $('#split_pt_chiro_group').attr('disabled', false);
        }
        else {
            $('#split_mediacal_group').attr('disabled', true);
            $('#split_surgery_group').attr('disabled', true);
            $('#split_pt_chiro_group').attr('disabled', true);
        }
    });

    $('#marketer-maintenance #email').bind('change keyup', function () {
        if (!$('#view_id').val()) {
            $('#email-ajax-loader').show();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/check_unique_email',
                dataType: 'html',
                data: {email: $('#email').val(), table: 'marketers'},
                success: function (data) {
                    uniqueEmail = data;
                    if (uniqueEmail == 'false') {
                        $('#email').addClass('ui-state-error');
                        $('#email-msg').html('Email is already exists.');
                    }
                    else {
                        $('#email').removeClass('ui-state-error');
                        $('#email-msg').html('');
                    }
                },
                complete: function () {
                    $('#email-ajax-loader').hide();
                },
                error: function (data) {
                    // display Error message
                    display_text_message('Error');
                }
            });
        }
    });

    $('.fnPracticesRow').live('click', function () {
        $(location).attr('href', baseURL + adminCONTROLLER + '/clients/' + $(this).attr('id'));
    });

    $('#btn-add-new-practice').live('click', function () {
        $('#dialog-practices').dialog('open');
    });

    /*$('#ext-attorneys-tree').treeview({
     collapsed: true
     });*/

    $('#btn-atty-selecting').on('click', function (event) {
        if ($(this).val() == 'Select All') {
            $('.fnExtAtty div').addClass('check_on').removeClass('check_off');
            $(this).val('Unselect All');
        }
        else {
            $('.fnExtAtty div').addClass('check_off').removeClass('check_on');
            $(this).val('Select All');
        }
    });

    $('#search-atty-name').live('focus change keyup', function () {
        $('#search-atty-results-msg').html(unsignSearchedAttyResult).removeClass('input_error_msg');
    });

    $('#btn-search-atty-name').on('click', function (event) {
        if ($('#search-atty-name').val().length > 2) {
            display_please_wait();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/get_ext_attys',
                dataType: 'html',
                data: {
                    atty_name: $('#search-atty-name').val()
                },
                success: function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.code == 200) {
                        $('#search-atty-results-msg').html(data.total + ' attorneys found');
                        $('#search-atty-results').html(data.results);
                        $('.attys_searched_table tr:even').addClass('jtable-row-even');
                    }
                    else {
                        $('#search-atty-results-msg').html(data.error);
                    }
                },
                complete: function () {
                    close_please_wait();
                },
                error: function (data) {
                    // display Error message
                    close_please_wait();
                    display_text_message('Error. Please contact us.', 300, 200);
                }
            });
        }
        else {
            $('#search-atty-name').addClass('ui-state-error');
            unsignSearchedAttyResult = $('#search-atty-results-msg').html();
            $('#search-atty-results-msg').html('Please enter Attorney Name (at least 3 chars).').addClass('input_error_msg');
        }
    });

    $('#select_all_attys_searched').live('click', function (event) {
        if ($(this).is(':checked')) {
            $('input[name="attys_searched"]').attr('checked', true);
        }
        else {
            $('input[name="attys_searched"]').attr('checked', false);
        }
    });

    $('#select_all_attys_assigned').live('click', function (event) {
        if ($(this).is(':checked')) {
            $('input[name="attys_assigned"]').attr('checked', true);
        }
        else {
            $('input[name="attys_assigned"]').attr('checked', false);
        }
    });

    $('#btn-atty-all-assign-selected').on('click', function (event) {
        var selected_attys = [];
        $('.fnExtAtty div.check_on').each(function (index, element) {
            var obj = $(this).metadata()
            selected_attys.push(obj);
        });
        if (selected_attys.length > 0) {
            refreshAssignedExtAttys(selected_attys);
            $('.fnExtAtty div').addClass('check_off').removeClass('check_on');
        }
    });

    $('#btn-atty-search-assign-selected').on('click', function (event) {
        var selected_attys = [];
        $('[name="attys_searched"]:checked').each(function (index, element) {
            var obj = $(this).metadata()
            selected_attys.push(obj);
        });
        if (selected_attys.length > 0) {
            refreshAssignedExtAttys(selected_attys);
        }
    });

    $('#btn-atty-unassign-selected').on('click', function (event) {
        var selected_attys = [];
        $('[name="attys_assigned"]:checked').each(function (index, element) {
            delete assignedExtAttys[$(this).metadata().ext_atty_id];
        });
        refreshAssignedExtAttys(selected_attys);
        $('#select_all_attys_assigned').attr('checked', false);
    });

    $('.assigned_case_manager_tree').live('click', function () {
        $('.assigned_case_manager_tree').removeClass('assigned_case_manager_tree_select');
        $(this).addClass('assigned_case_manager_tree_select');
        var firm_id = 0;
        var atty_id = 0;
        var user_id = 0;
        if ($(this).metadata().firm_id != undefined) {
            firm_id = $(this).metadata().firm_id;
        } else {
            atty_id = $(this).metadata().attorney_id;
        }
        if ($(this).metadata().user_id != undefined) {
            user_id = $(this).metadata().user_id;
        }
        caseManagerUserID = user_id;

        $('#assigned-cases-table-container').jtable('load', {
            sUserID: user_id,
            sFirmID: firm_id,
            sAttyID: atty_id,
            sCasesType: cases_manager_type,
            sSelectFrom: $('[name="cases_params"]:checked').val(),
            sName: $('#filter_by_name').val()
        });
    });

    $('.fnFirmsTree .expandable-hitarea').live('click', function () {
        $(this).removeClass('expandable-hitarea').removeClass('lastExpandable-hitarea');
        $(this).addClass('collapsable-hitarea').addClass('lastCollapsable-hitarea');
        $(this).parent().removeClass('expandable').removeClass('lastExpandable');
        $(this).parent().addClass('collapsable').addClass('lastCollapsable');
        $(this).siblings('ul').show();
    });

    $('.fnFirmsTree .collapsable-hitarea').live('click', function () {
        $(this).removeClass('collapsable-hitarea').removeClass('lastCollapsable-hitarea');
        $(this).addClass('expandable-hitarea').addClass('lastExpandable-hitarea');
        $(this).parent().removeClass('collapsable').removeClass('lastCollapsable');
        $(this).parent().addClass('expandable').addClass('lastExpandable');
        $(this).siblings('ul').hide();
    });

    $('.fnExtDB span.lastExpandable').live('click', function () {
        $(this).removeClass('lastExpandable');
        $(this).addClass('lastCollapsable');
        $(this).siblings('div').show();
    });

    $('.fnExtDB span.lastCollapsable').live('click', function () {
        $(this).removeClass('lastCollapsable');
        $(this).addClass('lastExpandable');
        $(this).siblings('div').hide();
    });

    $('.fnExtAtty div.check_on').live('click', function () {
        $(this).removeClass('check_on');
        $(this).addClass('check_off');
    });

    $('.fnExtAtty div.check_off').live('click', function () {
        $(this).removeClass('check_off');
        $(this).addClass('check_on');
    });

    $("#dialog-notification-data").dialog({
        autoOpen: false,
        height: 400,
        width: 600,
        modal: true,
        closeText: "close",
        draggable: false,
        resizable: false,
        dialogClass: "notificationMaintenance",
        buttons: {
            "Delete": function () {
                deleteNotificationData();
            },
            'Close': function () {
                $(this).dialog("close");
            }
        },
        open: function () {

        },
        close: function () {
            $('#view_id').val(0);
        }
    });

    $("#dialog-notification-data").dialog('open');
    $("#dialog-notification-data").dialog('close');

    // ------------------------------------------
    // initialize dialog-callback-form dialog box
    // ------------------------------------------
    $("#dialog-callback-form").dialog({
        bgiframe: true,
        resizable: false,
        autoOpen: false,
        modal: true,
        draggable: false,
        resizable: false,
        height: 360,
        width: 410,
        buttons: {
            'Close': function () {
                $(this).dialog("close");
            }
        },
        create: function (event, ui) {

        }
    }); //dialog-callback-form

    $("#dialog-callback-form").dialog('open');
    $("#dialog-callback-form").dialog('close');

    $('.fnCallbackDialog').on('click', function () {
        $("#dialog-callback-form").dialog('open');
    });

    $('#ext_dbs').on('change', function () {
        if ($(this).val() == 0) {
            $('#ext_attorneys_list').html('');
        }
        else {
            display_please_wait();
            $.ajax({
                type: 'POST',
                async: true,
                url: baseURL + ajaxCONTROLLER + '/get_ext_attys_by_database',
                dataType: 'html',
                data: {
                    db: $(this).val()//$(this).children(':selected').text()
                },
                success: function (data) {
                    $('#ext_attorneys_list').html(data);
                },
                complete: function () {
                    close_please_wait();
                },
                error: function (data) {
                    // display Error message
                    display_text_message('Error. Please contact us.', 350, 150);
                }
            });
        }
    });

});

//------------------------------------------
//Display general message to the user
//------------------------------------------
function display_text_message(message, width, height, resizable, buttons) {
    if (!width) width = 600;
    if (!height) height = 500;
    if (!resizable) resizable = false;
    if (buttons) {
        $("#dialog-general-message").dialog('option', 'buttons', buttons);
    }
    else {
        // Ensure OK button is here.  sbd
        // If after a please wait, it's not there.
        buttons = new Array();
        buttons[0] = {
            text: "Ok",
            click: function () {
                $(this).dialog('close');
            }
        };
        $("#dialog-general-message").dialog("option", "buttons", buttons);
    }

    $("#dialog-general-message").dialog('option', 'height', height);
    $("#dialog-general-message").dialog('option', 'width', width);
    $("#dialog-general-message").dialog('option', 'resizable', resizable);

    $("#dialog-general-message-text").html(message);
    $("#dialog-general-message").dialog('open');
}

function close_text_message() {
    $("#dialog-general-message").dialog('close');
}

function display_please_wait(message, width, height) {
    if (!width) width = 180;
    if (!height) height = 163;
    if (!message) message = 'Please wait..';

    $('#dialog-please-wait-title').html(message);
    $("#dialog-please-wait").dialog('option', 'height', height);
    $("#dialog-please-wait").dialog('option', 'width', width);
    $("#dialog-please-wait").dialog('open');
}

function close_please_wait() {
    $("#dialog-please-wait").dialog('close');
}

function clearForm(form_id) {
    //$('#'+form_id+' input[type="hidden"]').val('');
    $('#' + form_id + ' input[type="text"]').val('').removeClass('ui-state-error');
    $('#' + form_id + ' textarea').val('').removeClass('ui-state-error');
    $('#' + form_id + ' input[type="password"]').val('').removeClass('ui-state-error');
    $('#' + form_id + ' input[type="file"]').val('').removeClass('ui-state-error');
    $('#' + form_id + ' input[type="checkbox"]').attr('checked', false);
    //$('#'+form_id+' input[type=radio]').attr('checked',false);
    $('#' + form_id + ' select').val(0).trigger('change').removeClass('ui-state-error');
    $('div[id*="-msg"]').html('');
    $('.ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
}

function userEditData() {
    uniqueUsername = 'true';
    uniqueEmail = 'true';
    display_please_wait();
    var userEditData = {};
    userEditData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_user_data',
        dataType: 'html',
        data: userEditData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#view_id').val(rowID);
                var user_data = data.user_data[0];
                var user_firms = data.user_firms;
                var username = user_data.last_name;
                if (user_data.first_name != '') username += ', ' + user_data.first_name;
                $('#dialog-add-new-user #dialog-popup-content-title').html('Edit User <em>' + username + '</em>');
                $('#username').val(user_data.username);
                $('#lastname').val(user_data.last_name);
                $('#firstname').val(user_data.first_name);
                $('#userrole').val(user_data.role_id);
                $('#userrole').trigger('change');

                $('#email').val(user_data.email);
                $('#comment').val(user_data.comment);
                close_please_wait();
                $('#btn-add-new-user').trigger('click');
                $('#user-maintenance .user-general').show();
                $('#user-maintenance input[type="checkbox"]').attr('checked', false);
                if (user_data.my_cases_only == 1) {
                    $('#my_cases_only').attr('checked', true);
                }
                if (user_data.is_locked_out == 1) {
                    $('#is_locked_out').attr('checked', true);
                }
                if (user_data.last_login_date) {
                    var login_date = Date.parse(user_data.last_login_date);
                    $('#last-login-date').html('<div style="float:left;width:100px;">Last Login:</div>' + login_date.toString('MMM d, yyyy, HH:mm') + '<div style="color:red;height:20px;">&nbsp;</div>');
                }
                else {
                    $('#last-login-date').html('');
                }
                if (user_data.missed_appointments_notified == 1) {
                    $('input[value="missed_appointments_notified"]').attr('checked', true);
                }
                if (user_data.case_discharge_notified == 1)
                    $('input[value="case_discharge_notified"]').attr('checked', true);
                if (user_data.consult_notified == 1)
                    $('input[value="consult_notified"]').attr('checked', true);
                if (user_data.disability_notified == 1)
                    $('input[value="disability_notified"]').attr('checked', true);
                if (user_data.medical_report_notified == 1)
                    $('input[value="medical_report_notified"]').attr('checked', true);
                if (user_data.missed_appointments_notified == 1)
                    $('input[value="missed_appointments_notified"]').attr('checked', true);
                if (user_data.outside_medical_record_notified == 1)
                    $('input[value="outside_medical_record_notified"]').attr('checked', true);
                if (user_data.pharmacy_notified == 1)
                    $('input[value="pharmacy_notified"]').attr('checked', true);
                if (user_data.pt_note_notified == 1)
                    $('input[value="pt_note_notified"]').attr('checked', true);
                if (user_data.ptbwr_referral_notified == 1)
                    $('input[value="ptbwr_referral_notified"]').attr('checked', true);

                var linked_firms = new Array;
                $('#linked-firms').html('');

                for (var i = 0; i < user_firms.length; ++i) {
                    if ($.inArray(user_firms[i].legal_firm_id, linked_firms) == -1) {
                        if (user_firms[i].is_primary == 1) var checked = ' checked="checked"';
                        else var checked = '';
                        $('#linked-firms').append('<div class="fnAppendedPrimaries firm_' + user_firms[i].legal_firm_id + '">' +
                            '<input type="hidden" id="unlink_firm_' + user_firms[i].legal_firm_id +
                            '" name="unlink_firm_' + user_firms[i].legal_firm_id + '" value="' + user_firms[i].legal_firm_id + '" />' +
                            '<div class="fnFirmMaintenanceBox"><div class="fnUserRemoveFirm" style="float:left;">' +
                            '<img src="/images/remove-grey.png" style="border-right:1px solid #d5d5d5;" alt="Unlink Firm" title="Unlink Firm">' +
                            '</div></div><div class="folder users-dialog-firm-name">' + user_firms[i].name + '</div>' +
                            '<div style="clear:both;"><input' + checked + ' type="radio" name="is_primary" value="' + user_firms[i].legal_firm_id +
                            '" id="is_primary"> Primary ' +
                            '<input type="checkbox" name="all_attorneys_' + user_firms[i].legal_firm_id + '" value="1" ' +
                            'id="all_attorneys_' + user_firms[i].legal_firm_id + '" data="{firm_id: ' + user_firms[i].legal_firm_id + '}"> All Attorneys</div>');

                        /*$('#linked-firms').append('<span class="users-dialog-firm-name">'+user_firms[i].name+'</span><br>');
                         if (user_firms[i].is_primary == 1) var checked = ' checked="checked"';
                         else var checked = '';
                         $('#linked-firms').append('<input type="radio" name="is_primary" value="'+user_firms[i].legal_firm_id+'" id="is_primary"'+checked+'> Primary ');
                         $('#linked-firms').append('<input type="checkbox" name="all_attorneys_'+user_firms[i].legal_firm_id+'" value="1"  id="all_attorneys_'+user_firms[i].legal_firm_id+'" data="{firm_id: '+user_firms[i].legal_firm_id+'}"> All Attorneys ');		*/
                        if (user_firms[i].all_attorneys == 1) {
                            $('input[name="all_attorneys_' + user_firms[i].legal_firm_id + '"]').each(function (index, element) {
                                $(this).attr('checked', true);
                            });
                        }

                        /*$('#linked-firms').append('<br>');*/
                        linked_firms.push(user_firms[i].legal_firm_id);
                    }
                    /*$('input[name="attorneys[]"]').each(function(index, element) {
                     var metadata = $(this).metadata();
                     if (metadata.atty_id == user_firms[i].legal_atty_id && metadata.firm_id == user_firms[i].legal_firm_id)
                     $(this).attr('checked',true);
                     });*/

                    if (user_firms[i].is_linked) {
                        $('#attorney_' + user_firms[i].legal_atty_id).attr('checked', true);
                    }
                }

                if (user_data.maintain_clients_allowed == 1)
                    $('#maintain_clients_allowed_' + user_data.role_id).attr('checked', true);
                if (user_data.maintain_attorneys_allowed == 1)
                    $('#maintain_attorneys_allowed_' + user_data.role_id).attr('checked', true);
                if (user_data.maintain_firms_allowed == 1)
                    $('#maintain_firms_allowed_' + user_data.role_id).attr('checked', true);
                if (user_data.view_cases_for_firm_allowed == 1)
                    $('#view_cases_for_firm_allowed_' + user_data.role_id).attr('checked', true);
                if (user_data.maintain_marketers_allowed == 1)
                    $('#maintain_marketers_allowed_' + user_data.role_id).attr('checked', true);
                if (user_data.view_portal_activity_logs_allowed == 1)
                    $('#view_portal_activity_logs_allowed_' + user_data.role_id).attr('checked', true);

                if (user_data.high_charges_notified == 1) {
                    $('#high_charges').attr('checked', true);
                    $('input[name*="high_charge_level"]').attr('disabled', false);

                    if (user_data.high_charges_level1 == 0) var hcLevel1 = '';
                    else var hcLevel1 = user_data.high_charges_level1;
                    $('#high_charge_level1').val(hcLevel1);

                    if (user_data.high_charges_level2 == 0) var hcLevel2 = '';
                    else var hcLevel2 = user_data.high_charges_level2;
                    $('#high_charge_level2').val(hcLevel2);

                    if (user_data.high_charges_level3 == 0) var hcLevel3 = '';
                    else var hcLevel3 = user_data.high_charges_level3;
                    $('#high_charge_level3').val(hcLevel3);
                }
                else {
                    $('#high_charges').attr('checked', false);
                    $('input[name*="high_charge_level"]').attr('disabled', true);
                }
            }
            else {
                close_please_wait();
                display_text_message(data.message, 350, 150);
            }
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
};

function saveUserData() {
    $('#user-general').removeClass('tab_error');
    var userData = {};
    var errors = false;
    if ($('#username').val().length) {
        userData.username = $('#username').val();
        if (uniqueUsername == 'false') {
            $('#username').addClass('ui-state-error');
            $('#username-msg').html('Username is already exists.');
            $('.userMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
            errors = true;
        }
    }
    else {
        $('#username').addClass('ui-state-error');
        $('#username-msg').html('Please enter username.');
        $('.userMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errors = true;
    }
    userData.last_name = $('#lastname').val();
    userData.first_name = $('#firstname').val();
    if ($('#userrole').val() != 0) {
        userData.role_id = $('#userrole').val();
        $('#userrole').removeClass('ui-state-error');
    } else {
        $('#userrole').addClass('ui-state-error');
        $('.userMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errors = true;
    }
    /*userData.permissions = {};
     $('input[name="permissions[]"]').each(function(index, element) {
     userData.permissions[$(this).val()] = null;

     });
     $('input[name="permissions[]"]:checked').each(function(index, element) {
     userData.permissions[$(this).val()] = 1;
     });*/

    var emVal = $('#email').val();

    if (emVal.length && validateValueByPattern(emVal, emailPattern)) {
        userData.email = emVal;
        if (uniqueEmail == 'false') {
            $('#email').addClass('ui-state-error');
            $('#email-msg').html('Email is already exists.');
            $('.userMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
            return;
        }
        $('#email').removeClass('ui-state-error');
    }
    else {
        $('#email').addClass('ui-state-error');
        $('#email-msg').html('Please enter valid email.');

        errors = true;
    }
    userData.comment = $('#comment').val();
    if ($('#my_cases_only').is(':checked')) userData.my_cases_only = 1;
    else userData.my_cases_only = 0;
    if ($('#is_locked_out').is(':checked')) userData.is_locked_out = 1;
    else userData.is_locked_out = 0;
    userData.notifications = {};
    $('input[name="notifications[]"]').each(function (index, element) {
        if ($(this).is(':checked')) userData.notifications[$(this).val()] = 1;
        else userData.notifications[$(this).val()] = null;
    });

    userData.firms = {}
    /*$('input[name="attorneys[]"]').each(function(index, element) {
     if ($(this).is(':checked'))
     {
     var firm_id = $(this).siblings('[name*="atty_'+$(this).val()+'_firm_"]').val();
     if (userData.firms[firm_id] == undefined)
     {
     userData.firms[firm_id] = {};
     userData.firms[firm_id].all_attorneys = 0;
     $('input[name="all_attorneys_'+firm_id+'"]').each(function(index, element) {
     if ($(this).is(':checked')) userData.firms[firm_id].all_attorneys = 1;
     else userData.firms[firm_id].all_attorneys = 0;
     });
     userData.firms[firm_id].attorneys = new Array;
     }
     userData.firms[firm_id].attorneys.push($(this).val());
     }
     });*/

    $('input[name="is_primary"]').each(function (index, element) {
        if ($(this).is(':checked')) {
            userData.is_primary = $(this).val();
        }
    });

    $('[name*="unlink_firm_"]').each(function (index, element) {
        var firm_id = $(this).val();
        userData.firms[firm_id] = {};
        userData.firms[firm_id].attorneys = new Array;
        $('input[name="all_attorneys_' + firm_id + '"]').each(function (index, element) {
            if ($(this).is(':checked')) userData.firms[firm_id].all_attorneys = 1;
            else userData.firms[firm_id].all_attorneys = 0;
        });
    });

    $('input[name="attorneys[]"]').each(function (index, element) {
        if ($(this).is(':checked')) {
            var firm_id = $(this).siblings('[name*="atty_' + $(this).val() + '_firm_"]').val();
            userData.firms[firm_id].attorneys.push($(this).val());
        }
    });

    userData.view_id = $('#view_id').val();
    if (userData.view_id != 0) method = 'process_update_user';
    else method = 'process_add_user';

    var hcError = false;

    userData.high_charges = {};
    $('#high_charge_level1-msg').css('visibility', 'hidden');
    $('#high_charge_level2-msg').css('visibility', 'hidden');
    $('#high_charge_level3-msg').css('visibility', 'hidden');
    $('input[name*="high_charge_level"]').removeClass('ui-state-error');

    if ($('#high_charges').is(':checked')) {
        var hcLevel1 = parseFloat($('#high_charge_level1').val());
        var hcLevel2 = parseFloat($('#high_charge_level2').val());
        var hcLevel3 = parseFloat($('#high_charge_level3').val());

        if (isNaN(hcLevel1)) {
            hcError = true;
            $('#high_charge_level1').addClass('ui-state-error');
            $('#high_charge_level1-msg').html('High Charge Limit Level 1 is required.').css('visibility', 'visible');
        }
        else if (isNaN(hcLevel2) && !isNaN(hcLevel3)) {
            hcError = true;
            $('#high_charge_level2').addClass('ui-state-error');
            $('#high_charge_level2-msg').html('High Charge Limit Level 2 must be defined.').css('visibility', 'visible');
        }
        else if (!isNaN(hcLevel2) && (hcLevel1 > hcLevel2)) {
            hcError = true;
            $('#high_charge_level2').addClass('ui-state-error');
            $('#high_charge_level2-msg').html('High Charge Limit Level 2 must be larger than Level 1.').css('visibility', 'visible');
        }
        else if (!isNaN(hcLevel3) && (hcLevel2 > hcLevel3)) {
            hcError = true;
            $('#high_charge_level3').addClass('ui-state-error');
            $('#high_charge_level3-msg').html('High Charge Limit Level 3 must be larger than Level 2.').css('visibility', 'visible');
        }
        else if (!isNaN(hcLevel3) && (hcLevel1 > hcLevel3)) {
            hcError = true;
            $('#high_charge_level3').addClass('ui-state-error');
            $('#high_charge_level3-msg').html('High Charge Limit Level 3 must be larger than Level 1.').css('visibility', 'visible');
        }
        if (hcError) {
            $('#user-highcharges').addClass('tab_error');
        }
        else {
            userData.notifications['high_charges_notified'] = 1;
            if ($('#high_charge_level1').val().length > 0) userData.high_charges.high_charges_level1 = $('#high_charge_level1').val();
            else userData.high_charges.high_charges_level1 = 0;

            if ($('#high_charge_level2').val().length > 0) userData.high_charges.high_charges_level2 = $('#high_charge_level2').val();
            else userData.high_charges.high_charges_level2 = 0;

            if ($('#high_charge_level3').val().length > 0) userData.high_charges.high_charges_level3 = $('#high_charge_level3').val();
            else userData.high_charges.high_charges_level3 = 0;
        }
    }
    else {
        userData.notifications['high_charges_notified'] = null;
        userData.high_charges.high_charges_level1 = 0;
        userData.high_charges.high_charges_level2 = 0;
        userData.high_charges.high_charges_level3 = 0;
    }

    if (errors) {
        $('#user-general').addClass('tab_error');
    }

    if (errors || hcError) {
        display_text_message('Some erorrs occured. Please check tabs for details.', 300, 200);
        $('.userMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        return;
    }

    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: userData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
            }
            $("#dialog-add-new-user").dialog('close');
            display_text_message('<h2>User Saved</h2>' + data.message, 400, 280);
        },
        complete: function () {
            close_please_wait();
            if (sVal.length > 0) {
                $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                    if ($(this).val()) {
                        var ID = $(this).attr('id');
                        var ary = ID.split('-');
                        sortingFieldName = ary[1];
                    }
                });

                if ($('#user-table-container').length) $('#user-table-container').jtable('load', {
                    sortingFieldName: sortingFieldName,
                    sortingValue: sVal,
                    sortingQriteria: sQriteria
                });
                else if ($('#dashboard-user-table-container').length) $('#dashboard-user-table-container').jtable('load', {
                    sortingFieldName: sortingFieldName,
                    sortingValue: sVal,
                    sortingQriteria: sQriteria
                });
            }
            else {
                if ($('#user-table-container').length) $('#user-table-container').jtable('load');
                else if ($('#dashboard-user-table-container').length) $('#dashboard-user-table-container').jtable('load');
            }
        },
        error: function (data) {
            // display Error message
            $("#dialog-add-new-user").dialog('close');
            display_text_message('Error. Please contact us.' + data.message, 300, 200);
        }
    });
}

function deleteUserData() {
    var userDeleteData = {};
    userDeleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_user',
        dataType: 'html',
        data: userDeleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                if (sVal.length > 0) {
                    $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                        if ($(this).val()) {
                            var ID = $(this).attr('id');
                            var ary = ID.split('-');
                            sortingFieldName = ary[1];
                        }
                    });

                    if ($('#user-table-container').length)
                        $('#user-table-container').jtable('load', {
                            sortingFieldName: sortingFieldName,
                            sortingValue: sVal,
                            sortingQriteria: sQriteria
                        });
                    else if ($('#dashboard-user-table-container').length)
                        $('#dashboard-user-table-container').jtable('load', {
                            sortingFieldName: sortingFieldName,
                            sortingValue: sVal,
                            sortingQriteria: sQriteria
                        });
                }
                else {
                    if ($('#user-table-container').length) $('#user-table-container').jtable('load');
                    else if ($('#dashboard-user-table-container').length) $('#dashboard-user-table-container').jtable('load');
                }
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function changeUserPassword() {
    if ($('#email').val().length == 0) {
        display_text_message("User's password change not possible, email address not present.", 350, 150);
        return;
    }
    var newPassword = $("#new-password").val();
    var newPasswordConfirm = $("#new-password-confirm").val();
    $('#new-password-error').html('&nbsp;');
    $('#new-password-confirm-error').html('&nbsp;');
    if (newPassword.length < 7) {
        $('#new-password-error').html('Password is too short.');
        $("#new-password").val('');
        $("#new-password-confirm").val('');
        $('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        return;
    }
    if (newPassword != newPasswordConfirm) {
        $('#new-password-confirm-error').html('Passwords is mismatched.');
        $("#new-password-confirm").val('');
        $('.ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        return;
    }

    // check for correctness and change
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/change_user_password',
        dataType: 'html',
        data: {
            user_id: $('#view_id').val(),
            username: $('#username').val(),
            email: $('#email').val(),
            password: newPassword
        },
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $("#dialog-change-password").dialog('close');
                display_text_message('<h2>Change Password</h2>' + data.message, 400, 220);
            }
            else {
                $('#new-password-error').html(data.message);
            }
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 300, 200);
        }
    });
}

function resetUserPassword() {
    if ($('#email').val().length == 0) {
        display_text_message("User's password reset not possible, email address not present.", 350, 150);
        return;
    }
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/reset_user_password',
        dataType: 'html',
        data: {
            user_id: $('#view_id').val(),
            username: $('#username').val(),
            email: $('#email').val()
        },
        success: function (data) {
            data = jQuery.parseJSON(data);
            display_text_message('<h2>Reset Password</h2>' + data.message, 400, 220);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 300, 200);
        }
    });
}

function saveFirmData() {
    //$('#firm-name-maintenance #name').trigger('change');
    var formData = {};
    if ($('#firm-name-maintenance #name').val().length) {
        formData.name = $('#firm-name-maintenance #name').val();
        if (uniqueFirmname == 'false') {
            $('#firm-name-maintenance #name').addClass('ui-state-error');
            $('#formname-msg').html('Firm name is already exists.').show();
            return;
        }
        $('#firm-name-maintenance #name').removeClass('ui-state-error');
    }
    else {
        $('#firm-name-maintenance #name').addClass('ui-state-error');
        $('#firmname-msg').html('Please enter firm name.').show();
        return;
    }
    formData.view_id = $('#firm-name-maintenance #view_id').val();
    if (formData.view_id) method = 'process_update_firm';
    else method = 'process_add_firm';
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: formData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                isFirmUpdated = true;
                $('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').attr('disabled', false).removeClass('ui-state-disabled');
                $('#firm-name-maintenance #view_id').val(data.firm_id);

                if ($('.ui-dialog-buttonpane').find('button:contains("Continue to add Attorney")').css('display') == 'none') {
                    $('#dialog-general-message').on('dialogclose', function (event, ui) {
                        $(location).attr('href', $(location).attr('href'));
                    });
                }
            }
            $('#dialog-attorneys #dialog-popup-content-title').html('Add Attorney for <em>' + formData.name + '</em>');
            $('#atty-maintenance #atty_firm_id').val(data.firm_id);
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function closeFirmNameDlg() {
    if (isFirmUpdated) {
        $(location).attr('href', $(location).attr('href'));
    }
    $('#view_id').val('');
    $("#dialog-add-name-firm").dialog("close");
}

function deleteFirmData() {
    var firm_id = $('#firm-name-maintenance #view_id').val();
    if (firm_id > 0) {
        display_please_wait();
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/process_delete_firm',
            dataType: 'html',
            data: {firm_id: firm_id},
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.code == 200) {
                    isFirmUpdated = true;
                    $('#firm-name-maintenance #view_id').val('');

                    $('#dialog-general-message').on('dialogclose', function (event, ui) {
                        $(location).attr('href', $(location).attr('href'));
                    });
                }
                display_text_message(data.message, 350, 150);
            },
            complete: function () {
                close_please_wait();
            },
            error: function (data) {
                // display Error message
                display_text_message('Error. Please contact us.', 350, 150);
            }
        });

        $("#dialog-prompt-message").dialog('close');
    }

}

function marketerEditData() {
    uniqueEmail = 'true';
    display_please_wait();
    var marketerEditData = {};
    marketerEditData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_marketer_data',
        dataType: 'html',
        data: marketerEditData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#dialog-popup-content-title').html('Edit');
                $('#view_id').val(rowID);
                var markete_data = data.marketer_data[0];
                $('#middlename').val(markete_data.middle_name);
                $('#lastname').val(markete_data.last_name);
                $('#firstname').val(markete_data.first_name);
                $('#phone').val(markete_data.phone);
                $('#email').val(markete_data.email);

                close_please_wait();
                $('#btn-add-new-marketer').trigger('click');
            }
            else {
                close_please_wait();
                display_text_message(data.message, 350, 150);
            }
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
};

function saveMarketerData() {
    var marketerData = {};
    marketerData.last_name = $('#lastname').val();
    marketerData.first_name = $('#firstname').val();
    marketerData.middle_name = $('#middlename').val();
    marketerData.phone = $('#phone').val();

    if ($('#email').val().length) {
        marketerData.email = $('#email').val();
        if (uniqueEmail == 'false') {
            $('#email').addClass('ui-state-error');
            $('#email-msg').html('Email is already exists.');
            $('.marketerMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
            return;
        }
        $('#email').removeClass('ui-state-error');
    }
    else {
        $('#email').addClass('ui-state-error');
        $('#email-msg').html('Please enter valid email.');
        $('.marketerMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        return;
    }
    marketerData.view_id = $('#view_id').val();
    if (marketerData.view_id) method = 'process_update_marketer';
    else method = 'process_add_marketer';

    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: marketerData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
            }
            $("#dialog-add-new-marketer").dialog('close');
            display_text_message('<h2>Marketer Saved</h2>' + data.message, 400, 240);
        },
        complete: function () {
            close_please_wait();
            if (sVal.length > 0) {
                $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                    if ($(this).val()) {
                        var ID = $(this).attr('id');
                        var ary = ID.split('-');
                        sortingFieldName = ary[1];
                    }
                });

                $('#marketer-table-container').jtable('load', {
                    sortingFieldName: sortingFieldName,
                    sortingValue: sVal,
                    sortingQriteria: sQriteria
                });
            }
            else {
                $('#marketer-table-container').jtable('load');
            }
        },
        error: function (data) {
            // display Error message
            $("#dialog-add-new-marketer").dialog('close');
            display_text_message('Error. Please contact us.', 300, 200);
        }
    });
}

function deleteMarketerData() {
    var marketerDeleteData = {};
    marketerDeleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_marketer',
        dataType: 'html',
        data: marketerDeleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {

            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
    if (sVal.length > 0) {
        $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
            if ($(this).val()) {
                var ID = $(this).attr('id');
                var ary = ID.split('-');
                sortingFieldName = ary[1];
            }
        });

        $('#marketer-table-container').jtable('load', {
            sortingFieldName: sortingFieldName,
            sortingValue: sVal,
            sortingQriteria: sQriteria
        });
    }
    else {
        $('#marketer-table-container').jtable('load');
    }
}

function formEditData() {
    isFormUpdated = false;
    display_please_wait();
    var formEditData = {};
    formEditData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_forms_data',
        dataType: 'html',
        data: formEditData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#btn-add-new-form').trigger('click');
                $('#form-maintenance .form-general').show();
                $('#dialog-popup-content-title').html('Edit');
                $('#form_view_id').val(rowID);
                var form_data = data.form_data[0];
                $('#name').val(form_data.name);
                $('#description').val(form_data.description);
                $('#weight').val(form_data.weight);
                if (form_data.file_name != '' && form_data.file_name != null) {
                    $('#file_name_uploaded').html(form_data.file_name);
                    $('#file_name_uploads').val(form_data.file_name);
                    $('#file_delete').show();
                    $('#file_upload').hide();
                }

                close_please_wait();
            }
            else {
                close_please_wait();
                display_text_message(data.message, 350, 150);
            }
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
};

function saveFormData() {
    var extFile = ('doc', 'pdf');
    var errorMessage = false;
    if (!$('#name').val().length) {
        $('#name').addClass('ui-state-error');
        $('#name-msg').html('Please enter name.');
        $('.formMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errorMessage = true;
    }
    if (!$('#file_name').val().length && !$('#file_name_uploads').val().length) {
        $('#file_name').addClass('ui-state-error');
        $('#file_name-msg').html('Please choose file to upload.');
        $('.formMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errorMessage = true;
    } else if (!validateValueByPattern($('#file_name').val(), extFileFormsPattern) && !validateValueByPattern($('#file_name_uploads').val(), extFileFormsPattern)) {
        $('#file_name').addClass('ui-state-error');
        $('#file_name-msg').html('Extension file to upload is not valid.');
        $('.formMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errorMessage = true;
    }
    if (!$('#weight').val().length) {
        $('#weight').addClass('ui-state-error');
        $('#weight-msg').html('Please enter weight.');
        $('.formMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errorMessage = true;
    } else if (!validateValueByPattern($('#weight').val(), integerPattern)) {
        $('#weight').addClass('ui-state-error');
        $('#weight-msg').html('Please enter integer number.');
        $('.formMaintenance .ui-dialog-buttonpane .ui-dialog-buttonset').removeClass('ui-button-ajax-loader');
        errorMessage = true;
    }

    if (errorMessage) {
        console.log($('#file_name').val());
        return;
    }
    //TODO
    $('#form-maintenance').submit();
}

function deleteFormData() {
    var deleteData = {};
    deleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_form',
        dataType: 'html',
        data: deleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                if ($('#form-table-container').length) $('#form-table-container').jtable('load');
                else if ($('#dashboard-form-table-container').length) $('#dashboard-form-table-container').jtable('load');
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function deleteFileForm() {
    var deleteData = {};
    deleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_file_form',
        dataType: 'html',
        data: deleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                isFormUpdated = true;
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
            $('#file_name_uploaded').html('');
            $('#file_upload').show();
            $('#file_delete').hide();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function deleteLogoPortalSettings() {
    var deleteData = {};
    deleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_logo_portal_settings',
        dataType: 'html',
        data: deleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                isFormUpdated = true;
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
            $('#logo_uploaded').html('');
            $('#logo_upload').show();
            $('#logo_delete').hide();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function saveAttyData() {
    var last_name = $('#last_name').val();
    var first_name = $('#first_name').val();
    var errors = false;
    if (last_name.length < 3) {
        $('#atty-maintenance #last_name').addClass('ui-state-error');
        $('#atty-maintenance #lastname-msg').html('Please enter Last Name (at least 3 chars).');
        errors = true;
    }
    if (first_name.length < 3) {
        $('#atty-maintenance #first_name').addClass('ui-state-error');
        $('#atty-maintenance #firstname-msg').html('Please enter First Name (at least 3 chars).');
        errors = true;
    }
    formData = {};
    formData.atty_view_id = $('#atty-maintenance #atty_view_id').val();
    if (formData.atty_view_id) method = 'process_update_attorney';
    else method = 'process_add_attorney';
    formData.legal_firm_id = $('#atty-maintenance #atty_firm_id').val();
    formData.last_name = last_name;
    formData.first_name = first_name;
    formData.statement_delivery_method = $('#atty-maintenance #statements').val();
    formData.missed_appointment_notification_delivery_method = $('#atty-maintenance #missed_appintment').val();
    formData.statement_frequency = $('#atty-maintenance #statement_frequency').val();
    formData.missed_appointment_threshold = $('#atty-maintenance #missed_appointment_threshold').val();
    formData.assigned = [];
    $('input[name="attys_assigned"]').each(function (index, element) {
        formData.assigned.push($(this).metadata());
    });

    if (errors) {
        $('#atty-general').addClass('tab_error');
        display_text_message('Some erorrs occured. Please check tabs for details.', 300, 200);
        return;
    }

    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: formData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#dialog-general-message').on('dialogclose', function (event, ui) {
                    $(location).attr('href', $(location).attr('href'));
                });
            }
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function attyEditData() {
    var atty_id = $('#atty-maintenance #atty_view_id').val();
    if (atty_id) {
        isFirmUpdated = false;
        display_please_wait();
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/process_get_attorney_data',
            dataType: 'html',
            data: {atty_id: atty_id},
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.code == 200) {
                    $('#tabs li').each(function (index, element) {
                        $(this).removeClass('tabs-state-active');
                    });
                    $('#atty-general').addClass('tabs-state-active');
                    var atty = data.atty_data[0];

                    //Assigned List
                    assignedExtAttys = {};
                    $('#atty-assigned-list').html('');
                    refreshAssignedExtAttys(data.assigned_attys)

                    var atty_name = atty.last_name;
                    if (atty.first_name != '') atty_name += ', ' + atty.first_name;
                    $('#dialog-attorneys #dialog-popup-content-title').html('Edit ' + atty_name + ' for <em>' + atty.firm_name + '</em>');
                    $('#atty-maintenance #atty_firm_id').val(atty.legal_firm_id);
                    $('#atty-maintenance #last_name').val(atty.last_name);
                    $('#atty-maintenance #first_name').val(atty.first_name);
                    $('#atty-maintenance #statements').val(atty.statement_delivery_method);
                    $('#atty-maintenance #missed_appintment').val(atty.missed_appointment_notification_delivery_method);
                    $('#atty-maintenance #statement_frequency').val(atty.statement_frequency);
                    $('#atty-maintenance #missed_appointment_threshold').val(atty.missed_appointment_threshold);
                    $('#btn-atty-all-assign-selected').show();
                    $('#btn-atty-search-assign-selected').show();
                    $('#dialog-attorneys').dialog('open');
                }
                else {
                    display_text_message(data.message, 350, 150);
                }
            },
            complete: function () {
                close_please_wait();
            },
            error: function (data) {
                // display Error message
                display_text_message('Error. Please contact us.', 350, 150);
            }
        });
    }
}

function deleteAttyData() {
    var atty_id = $('#atty-maintenance #atty_view_id').val();
    if (atty_id > 0) {
        display_please_wait();
        $.ajax({
            type: 'POST',
            async: true,
            url: baseURL + ajaxCONTROLLER + '/process_delete_attorney',
            dataType: 'html',
            data: {atty_id: atty_id},
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.code == 200) {
                    isFirmUpdated = true;
                    $('#atty-maintenance #atty_view_id').val('');

                    $('#dialog-general-message').on('dialogclose', function (event, ui) {
                        $(location).attr('href', $(location).attr('href'));
                    });
                }
                display_text_message(data.message, 350, 150);
            },
            complete: function () {
                close_please_wait();
            },
            error: function (data) {
                // display Error message
                display_text_message('Error. Please contact us.', 350, 150);
            }
        });
        $("#dialog-prompt-message").dialog('close');
    }
}

function closeClientNameDlg() {
    if (isClientUpdated) {
        if (sVal.length > 0) {
            $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                if ($(this).val()) {
                    var ID = $(this).attr('id');
                    var ary = ID.split('-');
                    sortingFieldName = ary[1];
                }
            });

            $('#client-table-container').jtable('load', {
                sortingFieldName: sortingFieldName,
                sortingValue: sVal,
                sortingQriteria: sQriteria
            });
        }
        else {
            $('#client-table-container').jtable('load');
        }
    }
    $('#name').val('Client Name');
    $('#view_id').val('');
    $("#dialog-add-name-client").dialog("close");
}

function saveClientData() {
    //$('#firm-name-maintenance #name').trigger('change');
    var formData = {};
    if ($('#client-name-maintenance #name').val().length) {
        formData.name = $('#client-name-maintenance #name').val();
        $('#client-name-maintenance #name').removeClass('ui-state-error');
    }
    else {
        $('#client-name-maintenance #name').addClass('ui-state-error');
        $('#clientname-msg').html('Please enter client name.').show();
        return;
    }
    formData.view_id = $('#client-name-maintenance #view_id').val();
    if (formData.view_id) method = 'process_update_client';
    else method = 'process_add_client';
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: formData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                isClientUpdated = true;
                $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').attr('disabled', false).removeClass('ui-state-disabled');
                $('#client-name-maintenance #view_id').val(data.client_id);
                $('#practice-maintenance #prictice_client_id').val(data.client_id);

                if ($('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').css('display') == 'none') {
                    $('#dialog-general-message').on('dialogclose', function (event, ui) {
                        $('#dialog-add-name-client').dialog('close');
                    });
                }
            }
            $('#dialog-practices #dialog-popup-content-title').html('Add New Practice to <em>' + formData.name + '</em>');
            $('#practice-maintenance #client_id').val(data.client_id);
            display_text_message(data.message, 350, 150);
            if (sVal.length > 0) {
                $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                    if ($(this).val()) {
                        var ID = $(this).attr('id');
                        var ary = ID.split('-');
                        sortingFieldName = ary[1];
                    }
                });

                $('#client-table-container').jtable('load', {
                    sortingFieldName: sortingFieldName,
                    sortingValue: sVal,
                    sortingQriteria: sQriteria
                });
            }
            else {
                $('#client-table-container').jtable('load');
            }
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function clientEditData() {
    isClientUpdated = false;
    display_please_wait();
    var formData = {};
    formData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_client_data',
        dataType: 'html',
        data: formData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#dialog-add-name-client #dialog-popup-content-title').html('Edit Client');
                $('#client-name-maintenance #view_id').val(rowID);
                var client_data = data.client_data[0];
                $('#client-name-maintenance #name').val(client_data.name);
                $('.ui-dialog-buttonpane').find('button:contains("Continue to add Practices")').hide();
                $('#dialog-add-name-client').dialog('open');
            }
            else {
                display_text_message(data.message, 350, 150);
            }
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function deleteClientData() {
    var deleteData = {};
    deleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_client',
        dataType: 'html',
        data: deleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                if (sVal.length > 0) {
                    $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                        if ($(this).val()) {
                            var ID = $(this).attr('id');
                            var ary = ID.split('-');
                            sortingFieldName = ary[1];
                        }
                    });

                    $('#client-table-container').jtable('load', {
                        sortingFieldName: sortingFieldName,
                        sortingValue: sVal,
                        sortingQriteria: sQriteria
                    });
                }
                else {
                    $('#client-table-container').jtable('load');
                }
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function updateSortList(e, ui) {
    var sender = $(ui.sender);
    if (sender.hasClass('fnFinGrpClassesTree')) {
        sender.children().each(function (index, element) {
            $(this).removeClass('tree-last-child').removeClass('tree-child').addClass('tree-child');
        });
        sender.children(':last-child').addClass('tree-last-child').removeClass('tree-child');
    }

    var parent = $('#' + $(ui.item).parents('ul').attr('id'));
    parent.children().each(function (index, element) {
        $(this).css('display', 'list-item').removeClass('tree-last-child').removeClass('tree-child').addClass('tree-child');
    });
    parent.children(':last-child').addClass('tree-last-child').removeClass('tree-child');
    parent.parents('li').removeClass('tree-expandable').addClass('tree-collapsable');
}

function removeFinGroup() {
    $('#fin-grp-tree-' + rowID + ' .fnFinGrpClassesTree').children().each(function (index, element) {
        $(this).removeClass('tree-last-child').removeClass('tree-child');
        $('#fin-avail-classes').append($(this));
    });
    $('#fin-grp-tree-' + rowID).remove();
}

function saveApptReasonData() {
    /*var ext_db_id = $('#ext_dbs').val();
     var ext_db_name = $('#ext_dbs option:selected').text();*/
    var sys_code_id = $('#system_code').val();
    var sys_code_name = $('#system_code option:selected').text();
    var portal_reason = $('#portal_reason').val();
    var map_id = $('#appt_map_id').val();
    if (/*ext_db_id == 0 || */sys_code_id == 0 || portal_reason.length < 2) {
        display_text_message('Please fill in all fields.', 330, 170);
        return;
    }
    if (rowID) {
        $('#appt-reason-code-id-' + rowID).html(sys_code_name);
        $('#appt-reason-portal-id-' + rowID).html(portal_reason);
        $('#appt-reason-id-' + rowID).attr('data', '{id: \'' + rowID + '\', map_id: \'' + map_id + '\', code_id: \''
            + sys_code_id + '\', reason_id: \'' + portal_reason + '\'}');
    }
    else {
        ++counterApptReason;
        $('#appt-reasons-table').append('<tr id="appt-reason-id-' + counterApptReason + '" data="{id: \'' + counterApptReason + '\', map_id: 0, code_id: \'' + sys_code_id + '\', reason_id: \'' + portal_reason + '\'}"><td id="appt-reason-code-id-' + counterApptReason + '">' + sys_code_name + '</td><td style="width:310px;" id="appt-reason-portal-id-' + counterApptReason + '">' + portal_reason + '</td><td style="width:50px;"><span class="fnEditApptReasonRow">Edit</span></td><td style="width:50px;"><span class="fnDeleteApptReasonRow">Delete</span></td></tr>');
        $('#appt-reasons-table tr:even').addClass('appt-reason-tr-even');
        $('#appt-reason-no').hide();
    }
    $("#dialog-practices-appt-reason").dialog('close');
}

function removeApptReason() {
    $('#appt-reason-id-' + rowID).remove();
    $('#appt-reasons-table tr').removeClass('appt-reason-tr-even');
    $('#appt-reasons-table tr:even').addClass('appt-reason-tr-even');
    if ($('#appt-reasons-table tr').length <= 0) {
        $('#appt-reason-no').show();
    }
}

function checkPracticeStartup() {
    if ($('#practice-name').val().length > 2 && $('#ext-db').val() > 0) {
        $('#practice-locations').removeClass('ui-state-disabled');
        $('#practice-financial').removeClass('ui-state-disabled');
        $('#practice-reasons').removeClass('ui-state-disabled');
    }
    else {
        $('#practice-locations').addClass('ui-state-disabled');
        $('#practice-financial').addClass('ui-state-disabled');
        $('#practice-reasons').addClass('ui-state-disabled');
    }
}

function savePracticeData() {
    if ($('#practice-name').val().length < 3 || $('#ext-db').val() <= 0) {
        display_text_message('Please fill in Practice name / External Database.', 300, 150);
        return;
    }
    var formData = {};
    formData.practice_name = $('#practice-name').val();
    formData.ext_db_id1 = $('#ext-db').val();
    formData.external_id1 = $('#live_practice_id').val();
    formData.ext_db_id2 = $('#rundown-db2').val();
    formData.external_id2 = $('#rundown_practice_id2').val();
    formData.ext_db_id3 = $('#rundown-db3').val();
    formData.external_id3 = $('#rundown_practice_id3').val();
    formData.client_id = $('#prictice_client_id').val();

    // LOCATIONS
    var locsData = [];
    $('#practice-locs-selected option').each(function (index, element) {
        var map_id = $(this).metadata().map_id;
        locsData.push({name: $(this).val(), map_id: map_id});
    });

    // FINANCIAL
    if ($('#split_charges').is(':checked')) formData.split_charges = 1;
    else formData.split_charges = 0;
    var finGroups = [];
    $('.fnFinGrpClassesTree li').each(function (index, element) {
        finGroups.push({group_id: $(this).parents('li').metadata().grp_id, class_id: $(this).metadata().fin_class_id});
    });

    formData.medical_group = $('#split_mediacal_group').val() ? $('#split_mediacal_group').val() : null;
    formData.surgical_group = $('#split_surgery_group').val() ? $('#split_surgery_group').val() : null;
    formData.pt_group = $('#split_pt_chiro_group').val() ? $('#split_pt_chiro_group').val() : null;

    // APPT REASON
    var apptReasons = [];
    $('#appt-reasons-table tr').each(function (index, element) {
        var trID = $(this).metadata().id;
        var mapID = $(this).metadata().map_id;
        var codeID = $('#appt-reason-code-id-' + trID).text();
        var reasonID = $('#appt-reason-portal-id-' + trID).text();
        apptReasons.push({map_id: mapID, code_id: codeID, reason_id: reasonID});
    });

    formData.client_id = $('#prictice_client_id').val();
    formData.view_id = $('#prictice_view_id').val();
    if (formData.view_id) method = 'process_update_practice';
    else method = 'process_add_practice';

    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/' + method,
        dataType: 'html',
        data: {form_data: formData, locations: locsData, fin_groups: finGroups, appt_reasons: apptReasons},
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                if (isClientUpdated) {
                    if (sVal.length > 0) {
                        $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                            if ($(this).val()) {
                                var ID = $(this).attr('id');
                                var ary = ID.split('-');
                                sortingFieldName = ary[1];
                            }
                        });

                        $('#client-table-container').jtable('load', {
                            sortingFieldName: sortingFieldName,
                            sortingValue: sVal,
                            sortingQriteria: sQriteria
                        });
                    }
                    else {
                        $('#client-table-container').jtable('load');
                    }
                    closeClientNameDlg();
                }
                else {
                    if (sVal.length > 0) {
                        $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                            if ($(this).val()) {
                                var ID = $(this).attr('id');
                                var ary = ID.split('-');
                                sortingFieldName = ary[1];
                            }
                        });

                        $('#practice-table-container').jtable('load', {
                            sortingFieldName: sortingFieldName,
                            sortingValue: sVal,
                            sortingQriteria: sQriteria
                        });
                    }
                    else {
                        $('#practice-table-container').jtable('load');
                    }
                }
                $("#dialog-practices").dialog('close');
            }
            display_text_message(data.message + data.errors, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function practiceEditData() {
    isPracticeUpdated = false;
    display_please_wait();
    var formData = {};
    formData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_practice_data',
        dataType: 'html',
        data: formData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                var practiceData = data.practice.practiceData[0];
                $('#prictice_view_id').val(rowID);
                $('#prictice_client_id').val(clientID);
                $('#practice-name').val(practiceData.name);
                $('#ext-db').val(practiceData.ext_db_id1);
                $('#live_practice_id').val(practiceData.external_id1);
                $('#rundown-db2').val(practiceData.ext_db_id2);
                $('#rundown_practice_id2').val(practiceData.external_id2);
                $('#rundown-db3').val(practiceData.ext_db_id3);
                $('#rundown_practice_id3').val(practiceData.external_id3);

                var practiceLocs = data.practice.practiceLocs;
                var loc_name;
                var selectedLocs = [];
                for (loc in practiceLocs) {
                    if (practiceLocs.hasOwnProperty(loc)) {
                        loc_name = $.trim(practiceLocs[loc]['name']);
                        if ($.inArray(loc_name, selectedLocs) == -1) {
                            selectedLocs.push(loc_name);
                            $('#practice-locs-avail option').each(function (index, element) {
                                if ($(this).val() == loc_name) {
                                    $(this).addClass('selected');

                                }
                            });
                            $('#practice-locs-selected').append('<option value="' +
                                loc_name + '" data="{\'map_id\': \'' +
                                practiceLocs[loc]['map_id'] +
                                '\'}">' + loc_name + '</option>');
                        }
                        $('#practice-loc-remove-all').attr('disabled', false);

                        //console.log(practiceLocs[loc]['map_id']);
                    }
                }
                /*for (var i = 0; i < practiceLocs.length; ++i)
                 {
                 loc_name = $.trim(practiceLocs[i].display_name);
                 if ($.inArray(loc_name, selectedLocs) == -1)
                 {
                 selectedLocs.push(loc_name);
                 $('#practice-locs-avail option').each(function(index, element) {
                 if ($(this).val() == loc_name)
                 {
                 //$(this).clone().appendTo('#practice-locs-selected');
                 $(this).addClass('selected');

                 }
                 });
                 $('#practice-locs-selected').append('<option value="'+loc_name+'" data="{\'database_name\': \''+practiceLocs[i].database_name
                 +'\', \'cost_center_id\': '+practiceLocs[i].cost_center_id
                 +', \'practice_id\': '+practiceLocs[i].practice_id
                 +', \'portal_practice_id\': '+practiceLocs[i].PortalPracticeID
                 +', \'id\': '+practiceLocs[i].id+'}">'+loc_name+'</option>');
                 }
                 $('#practice-loc-remove-all').attr('disabled', false);
                 }*/

                var practiceFin = data.practice.practiceFin;
                var ext_fin_class;
                var fin_group_id;
                var fin_group_name;

                for (var i = 0; i < practiceFin.length; ++i) {
                    ext_fin_class = practiceFin[i].ext_dbs_fin_class_id;
                    fin_group_id = practiceFin[i].fin_grp_id;
                    fin_group_name = practiceFin[i].fin_group_name;

                    if ($('#fin-grp-tree-' + fin_group_id).length <= 0) {
                        $('#split_mediacal_group, #split_surgery_group, #split_pt_chiro_group')
                            .append('<option value="' + fin_group_id + '">' + fin_group_name + '</option>');

                        $('#fin-grps-box')
                            .append('<ul class="fnFinGrpTree" id="fin-grp-tree-' + fin_group_id + '"><li data="{grp_id:' + fin_group_id + '}" class="tree-collapsable"><div class="fnFinGrpMaintenanceBox"><img src="/images/delete-black.png" alt="Remove Group" title="Remove Group" class="fnFinGrpDelete" /></div>' + fin_group_name + '<ul class="fnFinGrpClassesTree" id="fin-grp-' + fin_group_id + '"></ul></li></ul>');
                        $('#fin-grp-' + fin_group_id).sortable({
                            connectWith: '#fin-avail-classes, .fnFinGrpClassesTree',
                            placeholder: "ui-state-highlight",
                            update: function (e, ui) {
                                updateSortList(e, ui);
                            }
                        }).disableSelection();
                    }

                    $('#fin-avail-classes .fnDraggable').each(function (index, element) {
                        if ($(this).metadata().fin_class_id == ext_fin_class) {
                            $(this).appendTo($('#fin-grp-' + fin_group_id));
                        }
                    });

                    $('#fin-grp-' + fin_group_id).children().each(function (index, element) {
                        $(this).removeClass('tree-last-child').removeClass('tree-child').addClass('tree-child');
                    });
                    $('#fin-grp-' + fin_group_id).children(':last-child').addClass('tree-last-child').removeClass('tree-child');
                }

                if (practiceData.split_charges == 1) {
                    $('#split_charges').attr('checked', true);
                    $('#split_mediacal_group').attr('disabled', false).val(practiceData.medical_group);
                    $('#split_surgery_group').attr('disabled', false).val(practiceData.surgical_group);
                    $('#split_pt_chiro_group').attr('disabled', false).val(practiceData.pt_group);
                }
                else {
                    $('#split_charges').attr('checked', false);
                    $('#split_mediacal_group').attr('disabled', true).val(0);
                    $('#split_surgery_group').attr('disabled', true).val(0);
                    $('#split_pt_chiro_group').attr('disabled', true).val(0);
                }

                var practiceAppt = data.practice.practiceAppt;
                counterApptReason = 0;
                for (var i = 0; i < practiceAppt.length; ++i) {
                    ++counterApptReason;
                    $('#appt-reasons-table').append('<tr id="appt-reason-id-' + counterApptReason + '" data="{id: \'' +
                        counterApptReason + '\', map_id: \'' + practiceAppt[i].MappingId + '\', code_id: \'' +
                        practiceAppt[i].PMSReason + '\', \'reason_id\': \'' + practiceAppt[i].AMMReason
                        + '\'}"><td id="appt-reason-code-id-' + counterApptReason + '">' +
                        practiceAppt[i].PMSReason + '</td><td style="width:310px;" id="appt-reason-portal-id-' +
                        counterApptReason + '">' + practiceAppt[i].AMMReason +
                        '</td><td style="width:50px;"><span class="fnEditApptReasonRow">Edit</span></td>'
                        + '<td style="width:50px;"><span class="fnDeleteApptReasonRow">Delete</span></td></tr>');
                    $('#appt-reasons-table tr:even').addClass('appt-reason-tr-even');
                    $('#appt-reason-no').hide();
                }

                $('#practice-locations').removeClass('ui-state-disabled');
                $('#practice-financial').removeClass('ui-state-disabled');
                $('#practice-reasons').removeClass('ui-state-disabled');
                $('#dialog-practices #dialog-popup-content-title').html('Edit <em>' + practiceData.name + '</em>');
                $('#dialog-practices').dialog('open');
            }
            else {
                display_text_message(data.message, 350, 150);
            }
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function deletePracticeData() {
    var deleteData = {};
    deleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_practice',
        dataType: 'html',
        data: deleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                if (sVal.length > 0) {
                    $('.jtable-search-bar input[id*="search"]').each(function (index, element) {
                        if ($(this).val()) {
                            var ID = $(this).attr('id');
                            var ary = ID.split('-');
                            sortingFieldName = ary[1];
                        }
                    });

                    $('#practice-table-container').jtable('load', {
                        sortingFieldName: sortingFieldName,
                        sortingValue: sVal,
                        sortingQriteria: sQriteria
                    });
                }
                else {
                    $('#practice-table-container').jtable('load');
                }
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function refreshAssignedExtAttys(attys) {
    for (var i = 0; i < attys.length; ++i) {
        if (!assignedExtAttys.hasOwnProperty(attys[i].ext_atty_id)) {
            assignedExtAttys[attys[i].ext_atty_id] = attys[i];
        }
    }
    var len = $.assocArraySize(assignedExtAttys);
    if (len > 0) {
        $('.attys_assigned_table tbody').html('');
        $('#atty-assigned-list').html($.assocArraySize(assignedExtAttys) + ' attorneys assigned');
        for (var key in assignedExtAttys) {
            if (assignedExtAttys.hasOwnProperty(key)) {
                $('.attys_assigned_table tbody').append('<tr><td><input type="checkbox" name="attys_assigned" value="1" id="attys_assigned_'
                    + key + '" data="{ext_atty_id:' + assignedExtAttys[key].ext_atty_id
                    + ', ext_db_id:' + assignedExtAttys[key].ext_db_id
                    + ', ext_atty_name: \'' + assignedExtAttys[key].ext_atty_name
                    + '\', ext_db_name: \'' + assignedExtAttys[key].ext_db_name + '\'}"></td><td>'
                    + assignedExtAttys[key].ext_db_name + '</td><td>' + assignedExtAttys[key].ext_atty_id
                    + '</td><td>' + assignedExtAttys[key].ext_atty_name + '</td></tr>');
            }
        }
        $('.attys_assigned_table tr:even').addClass('jtable-row-even');
        $('.attys_assigned_table').show();
    }
    else {
        $('.attys_assigned_table tbody').html('');
        $('.attys_assigned_table').hide();
        $('#atty-assigned-list').html('<em>No MicroMD Attorneys Assigned</em>');
    }
}

function load_cases_managers_change_tab(tab_type) {
    var firm_id = 0;
    var atty_id = 0;

    $('.assigned_case_manager_tree').each(function () {
        if ($(this).hasClass('assigned_case_manager_tree_select')) {
            if ($(this).metadata().firm_id != undefined) {
                firm_id = $(this).metadata().firm_id;
            } else {
                atty_id = $(this).metadata().attorney_id;
            }
        }
    });

    $('#assigned-cases-table-container').jtable('load', {
        sUserID: caseManagerUserID,
        sFirmID: firm_id,
        sAttyID: atty_id,
        sCasesType: tab_type,
        sSelectFrom: $('[name="cases_params"]:checked').val(),
        sName: $('#filter_by_name').val()
    });
}

function notificationEditData() {
    display_please_wait();
    var notifEditData = {};
    notifEditData.id = rowID;
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_get_notification_data',
        dataType: 'html',
        data: notifEditData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('.notif_' + rowID).removeClass('unread');
                $('#dialog-notification-data .dialog-popup-content').html('').html(data.output);
                $('#dialog-notification-data').dialog('open');
            }
            else {
                display_text_message(data.message, 350, 200);
            }
        },
        complete: function (data) {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

function deleteNotificationData() {
    var notifDeleteData = {};
    notifDeleteData.id = rowID;
    display_please_wait();
    $.ajax({
        type: 'POST',
        async: true,
        url: baseURL + ajaxCONTROLLER + '/process_delete_user_notification',
        dataType: 'html',
        data: notifDeleteData,
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.code == 200) {
                $('#notification-table-container').jtable('load');
                $('#dialog-notification-data').dialog('close');
            }
            $("#dialog-prompt-message").dialog('close');
            display_text_message(data.message, 350, 150);
        },
        complete: function () {
            close_please_wait();
        },
        error: function (data) {
            // display Error message
            display_text_message('Error. Please contact us.', 350, 150);
        }
    });
}

var unsignSearchedAttyResult = '';
var assignedExtAttys = {};