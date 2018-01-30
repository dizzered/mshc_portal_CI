<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Global Constants
*/

define('MSHC_COMPANY_NAME', 'Multi-Specialty HealthCare\'s Attorney Portal');
define('MSHC_ADMIN_EMAIL', 'dizzered@gmail.com');
define('MSHC_UPLOAD_FILE_PATH', 'uploads');
define('MSHC_CONVERT_FILE_PATH', 'converts');
define('MSHC_CLAIMS_FILE_PATH', 'claims');
define('MSHC_STATEMENTS_FILE_PATH', 'statements');
define('MSHC_TIMEZONE', 'America/New_York');

// Controllers Aliases
define('MSHC_HOME_CONTROLLER_NAME', 'home');
define('MSHC_CASES_CONTROLLER_NAME', 'cases');
define('MSHC_ADMIN_CONTROLLER_NAME', 'admin');
define('MSHC_FORMS_CONTROLLER_NAME', 'patient_forms');
define('MSHC_REPORTS_CONTROLLER_NAME', 'reports');
define('MSHC_HELP_CONTROLLER_NAME', 'help');
define('MSHC_USER_CONTROLLER_NAME', 'user');
define('MSHC_AUTH_CONTROLLER_NAME', 'profile');
define('MSHC_CONTACT_CONTROLLER_NAME', 'contact');
define('MSHC_AJAX_CONTROLLER_NAME', 'ajax');
define('MSHC_NOTIFICATIONS_CONTROLLER_NAME', 'notification');
define('MSHC_FILES_CONTROLLER_NAME', 'files');

// Methods Aliases
define('MSHC_CASES_CLIENT_SEARCH_NAME', 'search');
define('MSHC_CASES_NEW_NAME', 'new_cases');
define('MSHC_CASES_REGISTER', 'register');
define('MSHC_CASES_ASSIGN_MANAGER_NAME', 'assign');

define('MSHC_ADMIN_USERS_NAME', 'users');
define('MSHC_ADMIN_ACTIVITIES_NAME', 'activities');
define('MSHC_ADMIN_CLIENTS_NAME', 'clients');
define('MSHC_ADMIN_FIRMS_NAME', 'firms');
define('MSHC_ADMIN_MARKETERS_NAME', 'marketers');
define('MSHC_ADMIN_SETTINGS_NAME', 'portal_settings');
define('MSHC_ADMIN_FORMS_NAME', 'forms');

define('MSHC_REPORTS_DISCHARGE_NAME', 'discharge_clients');
define('MSHC_REPORTS_MILEAGE_NAME', 'mileage');

define('MSHC_HELP_FAQ_NAME', 'faq');
define('MSHC_HELP_MANUAL_NAME', '');

// ------------------------------------------------
// Auth and stuff
// ------------------------------------------------

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

// User roles
// This data get from database table 'roles'
define('MSHC_AUTH_SYSTEM_ADMIN', 'ebf3bfc4-2e48-11e2-bd4c-75618680eb1a');
define('MSHC_AUTH_GENERAL_USER', '2f12536a-2e49-11e2-bd4c-75618680eb1a');
define('MSHC_AUTH_FIRM_ADMIN', '53e2325a-2e49-11e2-bd4c-75618680eb1a');
define('MSHC_AUTH_ATTORNEY', '59abde66-2e49-11e2-bd4c-75618680eb1a');
define('MSHC_AUTH_CASE_MANAGER', '612f7e04-2e49-11e2-bd4c-75618680eb1a');
define('MSHC_AUTH_BILLER', '195f723b-73d2-11e5-93a4-50465d69d7dd');

define('MSHC_AUTH_SYSTEM_ADMIN_NAME', 'System Administrator');
define('MSHC_AUTH_GENERAL_USER_NAME', 'General User');
define('MSHC_AUTH_FIRM_ADMIN_NAME', 'Firm Administrator');
define('MSHC_AUTH_ATTORNEY_NAME', 'Attorney');
define('MSHC_AUTH_CASE_MANAGER_NAME', 'Case Manager');
define('MSHC_AUTH_BILLER_NAME', 'Biller');

define('MSHC_AMM_FIRM_ID', '132');

/* End of file global.php */
/* Location: ./application/config/global.php */