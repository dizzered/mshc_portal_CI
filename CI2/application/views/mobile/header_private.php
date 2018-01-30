<script>
    var userRole = '<?php echo get_user_role_name($this->_user['role_id']); ?>';
</script>

<?php
	if (isset($main_menu) && is_array($main_menu))
	{
?>
	<div id="nav_menu">
    	<ul>
	    	<li class="welcome">Welcome back,<br>
	        <a href="<?php echo base_url(); ?>"><span class="username"><?php echo $user['username']; ?></span></a></li>
            <?php
				 	foreach($main_menu as $path => $title)
                    {
                        echo '<li>'.anchor(base_url().$path, $title).'</li>';
                    }
			?>
		</ul>
    </div>
<?php
	}
?>
<div id="contentWrapper">
<header>
    <div id="header-line1">
    
        <div id="header-contact-block">
        
        	<div id="menu"></div>

            <div id="profile"></div>
    
            <div id="faq"><?php  echo anchor(base_url().MSHC_HELP_CONTROLLER_NAME,'Help/Faq');?></div>
    
            <div class="faq-selector">|</div>
    
            <div id="contact-us">Contact us: (410) 933-5678</div>
    
        </div>
    
        <div class="clear"></div>
    
    </div>
    <div id="header-line2">
    
        <div id="portal-title">Portal</div>
    
        <div id="attorney-selector"></div> 
    
        <div id="attorney-title">Attorney</div>
    
    </div>
</header>

<section class="page">

<div class="headerLine">
	<div id="header_title"><?php echo $page_title; ?></div>
    <div class="filter plus" style="display:none" id="div_name_filter">Sort</div>

    <div class="filter-dropdown" style="display: none;" id="div_filter_dropdown_cases_search">

        <ul>

            <li class="plus2" id="cases_search_attorney_name">Attorney</li>
            
            <li class="minusDrop cases_search_attorney_name" id="attorney_name_asc" data="{sort: 'attorney_name ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop cases_search_attorney_name" id="attorney_name_desc" data="{sort: 'attorney_name DESC'}" style="display:none">— Z-A</li>

            <li class="plus2" id="cases_search_patient">Patient</li>
            
            <li class="minusDrop cases_search_patient" id="patient_asc" data="{sort: 'patient ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop cases_search_patient" id="patient_desc" data="{sort: 'patient DESC'}" style="display:none">— Z-A</li>

            <li class="plus2" id="cases_search_account">Account</li>
            
            <li class="minusDrop cases_search_account" id="account_low" data="{sort: 'account ASC'}" style="display:none">— Low</li>

            <li class="minusDrop cases_search_account" id="account_high" data="{sort: 'account DESC'}" style="display:none">— High</li>

            <li class="plus2" id="cases_search_case_category">Class</li>
            
             <li class="minusDrop cases_search_case_category" id="case_category_asc" data="{sort: 'case_category ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop cases_search_case_category" id="case_category_desc" data="{sort: 'case_category DESC'}" style="display:none">— Z-A</li>

            <li class="plus2" id="cases_search_accident_date">DOA</li>
            
            <li class="minusDrop cases_search_accident_date" id="accident_date_low" data="{sort: 'accident_date ASC'}" style="display:none">— Low</li>

            <li class="minusDrop cases_search_accident_date" id="accident_date_high" data="{sort: 'accident_date DESC'}" style="display:none">— High</li>

            <li class="plus2" id="cases_search_status">Status</li>
            
            <li class="minusDrop cases_search_status" id="status_asc" data="{sort: 'status ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop cases_search_status" id="status_desc" data="{sort: 'status DESC'}" style="display:none">— Z-A</li>

            <li class="plus2" id="cases_search_db_name">Database</li>
            
            <li class="minusDrop cases_search_db_name" id="db_name_asc" data="{sort: 'db_name ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop cases_search_db_name" id="db_name_desc" data="{sort: 'db_name DESC'}" style="display:none">— Z-A</li>

        </ul>

    </div>
    
    <div class="filter-dropdown" style="display: none;" id="div_filter_dropdown_appointments">

    	<ul>

            <li class="plus2" id="appointments_date">Date</li>

            <li class="minusDrop appointments_date" id="date_low" data="{sort: 'date ASC'}" style="display:none">— Low</li>

            <li class="minusDrop appointments_date" id="date_high" data="{sort: 'date DESC'}" style="display:none">— High</li>

            <li class="minusDrop appointments_date" id="date_range" style="display:none">— Contains<br />

            	<input type="text" id="date_contains" value="" style="width: 420px"> <input id="appointments_date_search" type="button" value="Search">

            </li>

            <li class="plus2" id="appointments_provider">Provider</li>

             <li class="minusDrop appointments_provider" id="provider_asc" data="{sort: 'provider ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop appointments_provider" id="provider_desc" data="{sort: 'provider DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop appointments_provider"  style="display:none">— Contains<br />

            <input type="text" id="provider_contains" value="" style="width: 420px"> <input id="appointments_provider_search" type="button" value="Search">

            </li>

            <li class="plus2" id="appointments_reason">Reason</li>
            
             <li class="minusDrop appointments_reason" id="reason_asc" data="{sort: 'reason ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop appointments_reason" id="reason_desc" data="{sort: 'reason DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop appointments_reason" style="display:none">— Contains<br />

            <input type="text" id="reason_contains" value="" style="width: 420px"> <input id="appointments_reason_search" type="button" value="Search">

            </li>

            <li class="plus2" id="appointments_location">Location</li>
            
             <li class="minusDrop appointments_location" id="location_asc" data="{sort: 'location ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop appointments_location" id="location_desc" data="{sort: 'location DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop appointments_location" style="display:none">— Contains<br />

            <input type="text" id="location_contains" value="" style="width: 420px"> <input id="appointments_location_search" type="button" value="Search">

            </li>

            <li class="plus2" id="appointments_status">Status</li>
            
            <li class="minusDrop appointments_status" id="status_asc" data="{sort: 'status ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop appointments_status" id="status_desc" data="{sort: 'status DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop appointments_status" style="display:none">— Contains<br />

            <input type="text" id="status_contains" value="" style="width: 420px"> <input id="appointments_status_search" type="button" value="Search">

            </li>

        </ul>

    </div>
    
    <div class="filter-dropdown" style="display: none;" id="div_filter_dropdown_documents">
    
    	<ul>

            <li class="plus2" id="documents_date_of_service">DOS</li>
            
            <li class="minusDrop documents_date_of_service" id="date_of_service_low" data="{sort: 'date_of_service ASC'}" style="display:none">— Low</li>

            <li class="minusDrop documents_date_of_service" id="date_of_service_high" data="{sort: 'date_of_service DESC'}" style="display:none">— High</li>

            <li class="plus2" id="documents_document_type">Document Type</li>
            
            <li class="minusDrop documents_document_type" id="document_type_asc" data="{sort: 'document_type ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop documents_document_type" id="document_type_desc" data="{sort: 'document_type DESC'}" style="display:none">— Z-A</li>

            <li class="plus2" id="documents_document_name">Document</li>
            
            <li class="minusDrop documents_document_name" id="document_name_asc" data="{sort: 'document_name ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop documents_document_name" id="document_name_desc" data="{sort: 'document_name DESC'}" style="display:none">— Z-A</li>

        </ul>

    </div>
    
    <div class="filter-dropdown" style="display: none;" id="div_filter_dropdown_discharge_report">

        <ul>

            <li class="plus2" id="discharge_report_patient">Patient</li>

             <li class="minusDrop discharge_report_patient" data="{sort: 'patient ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop discharge_report_patient" data="{sort: 'patient DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop discharge_report_patient" style="display:none">— Contains<br>

            	<input type="text" id="patient_contains" value="" style="width: 420px"> <input type="button" id="discharge_report_patient_search" value="Search">

            </li>
            
            <li class="plus2" id="discharge_report_account" >Account</li>

            <li class="minusDrop discharge_report_account" data="{sort: 'account ASC'}" style="display:none">— Low</li>

            <li class="minusDrop discharge_report_account" data="{sort: 'account DESC'}" style="display:none">— High</li>

            <li class="minusDrop discharge_report_account" style="display:none">— Contains<br>

            	<input type="text" id="account_contains" value="" style="width: 420px"> <input type="button" id="discharge_report_account_search" value="Search">

            </li>

         	<li class="plus2" id="discharge_report_case_category">Class</li>

             <li class="minusDrop discharge_report_case_category" data="{sort: 'case_category ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop discharge_report_case_category" data="{sort: 'case_category DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop discharge_report_case_category" style="display:none">— Contains<br>

            	<input type="text" id="case_category_contains" value="" style="width: 420px"> <input type="button" id="discharge_report_case_category_search" value="Search">

            </li>
            
            <li class="plus2" id="discharge_report_accident_date">DOA</li>

            <li class="minusDrop discharge_report_accident_date" data="{sort: 'accident_date ASC'}" style="display:none">— Low</li>

            <li class="minusDrop discharge_report_accident_date" data="{sort: 'accident_date DESC'}" style="display:none">— High</li>

            <li class="minusDrop discharge_report_accident_date" style="display:none">— Contains<br />

            	<input type="text" id="accident_date_contains" value="" style="width: 420px"> <input id="discharge_report_accident_date_search" type="button" value="Search">

            </li>
            
            <li class="plus2" id="discharge_report_discharge_date">Discharge Date</li>

            <li class="minusDrop discharge_report_discharge_date" data="{sort: 'discharge_date ASC'}" style="display:none">— Low</li>

            <li class="minusDrop discharge_report_discharge_date" data="{sort: 'discharge_date DESC'}" style="display:none">— High</li>

            <li class="minusDrop discharge_report_discharge_date" style="display:none">— Contains<br />

            	<input type="text" id="discharge_date_contains" value="" style="width: 420px"> <input id="discharge_report_discharge_date_search" type="button" value="Search">

            </li>
            
            <li class="plus2" id="discharge_report_status">Status</li>

             <li class="minusDrop discharge_report_status" data="{sort: 'status ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop discharge_report_status" data="{sort: 'status DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop discharge_report_status" style="display:none">— Contains<br>

            	<input type="text" id="status_contains" value="" style="width: 420px"> <input type="button" id="discharge_report_status_search" value="Search">

            </li>

        </ul>

    </div>
    
    <div class="filter-dropdown" style="display: none;" id="div_filter_dropdown_mileage_report">

        <ul>

            <li class="plus2" id="mileage_report_last_name">Last Name</li>
            
            <li class="minusDrop mileage_report_last_name" data="{sort: 'last_name ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop mileage_report_last_name" data="{sort: 'last_name DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop mileage_report_last_name" style="display:none">— Contains<br>

            	<input type="text" id="mileage_last_name_contains" value="" style="width: 420px"> <input type="button" id="mileage_report_last_name_search" value="Search">

            </li>

            <li class="plus2" id="mileage_report_first_name">First Name</li>
            
            <li class="minusDrop mileage_report_first_name" data="{sort: 'first_name ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop mileage_report_first_name" data="{sort: 'first_name DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop mileage_report_first_name" style="display:none">— Contains<br>

            	<input type="text" id="mileage_first_name_contains" value="" style="width: 420px"> <input type="button" id="mileage_report_first_name_search" value="Search">

            </li>

            <li class="plus2" id="mileage_report_account">Account</li>
            
            <li class="minusDrop mileage_report_account" data="{sort: 'account ASC'}" style="display:none">— Low</li>

            <li class="minusDrop mileage_report_account" data="{sort: 'account DESC'}" style="display:none">— High</li>

            <li class="minusDrop mileage_report_account" style="display:none">— Contains<br>

            	<input type="text" id="mileage_account_contains" value="" style="width: 420px"> <input type="button" id="mileage_report_account_search" value="Search">

            </li>

            <li class="plus2" id="mileage_report_case_category">Class</li>
            
            <li class="minusDrop mileage_report_case_category" data="{sort: 'case_category ASC'}" style="display:none">— A-Z</li>

            <li class="minusDrop mileage_report_case_category" data="{sort: 'case_category DESC'}" style="display:none">— Z-A</li>

            <li class="minusDrop mileage_report_case_category" style="display:none">— Contains<br>

            	<input type="text" id="mileage_case_category_contains" value="" style="width: 420px"> <input type="button" id="mileage_report_case_category_search" value="Search">

            </li>

            <li class="plus2" id="mileage_report_accident_date">DOA</li>
            
            <li class="minusDrop mileage_report_accident_date" data="{sort: 'accident_date ASC'}" style="display:none">— Low</li>

            <li class="minusDrop mileage_report_accident_date" data="{sort: 'accident_date DESC'}" style="display:none">— High</li>

            <li class="minusDrop mileage_report_accident_date" style="display:none">— Contains<br />

            	<input type="text" id="mileage_accident_date_contains" value="" style="width: 420px"> <input id="mileage_report_accident_date_search" type="button" value="Search">

            </li>

        </ul>

    </div>

</div>

<script>
	$('#profile').on('click', function(){
		$(location).attr('href', '/home');
	});
	$('#menu').on('click', function(){
		$('#nav_menu').toggleClass('expandedMenu');
		$('#contentWrapper').toggleClass('slideContent');
		
		if ($('#nav_menu').hasClass('expandedMenu') && $('#contentWrapper').hasClass('slideContent')) {
			$('#contentWrapper').animate({"left": "514px"}, "slow");
			$('#nav_menu').animate({"left": "0px"}, "slow");
		} else {
			$('#contentWrapper').animate({"left": "0px"}, "slow");
			$('#nav_menu').animate({"left": "-500px"}, "slow");
		}
		
	});
</script>