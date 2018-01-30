<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * Base class for all MSHC controllers
 * @author Zercel
 *
 * Modification History
 * --------------------
 *
 * Core
 * @property CI_User_agent $agent
 * @property CI_Config $config
 * @property CI_DB_active_record|CI_DB_mysqli_driver $db
 * @property CI_Email $email
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input;
 * @property CI_URI $uri
 * @property CI_Session $session
 * @property CI_Cache $cache
 * @property CI_Upload $upload
 *
 * ---------------------
 * Models
 * @property Activity $activity
 * @property Clients $clients
 * @property Contacts $contacts
 * @property Firms $firms
 * @property Forms $forms
 * @property Marketers $marketers
 * @property Notifications $notifications
 * @property Portal_settings $portal_settings
 * @property Users $users
 *
 * ----------------------
 * Libraries
 * @property MSHC_Connector $mshc_connector
 * @property MSHC_General $mshc_general
 *
 */

class MSHC_Controller extends CI_Controller
{
    public $_user = array('timezone' => 'America/New_York', 'role_id' => '');
    public $_public_pages = array();
    public $_auth_error = array();
    protected $_views = array();
    protected $_custom_scripts = array();
    protected $_custom_styles = array();
    protected $_page_title = '';
    protected $_page_description = '';
    protected $_page_keywords = '';
    protected $_common_header = 'header_common';
    protected $_user_header = 'header_public';
    protected $_common_footer = 'footer_common';
    protected $_default_footer = '';
    protected $_default_menubar = '';
    protected $_menubar_text = '';
    protected $_caller_page = '';
    protected $_effect = '';
    protected $_effect_options = '';
    protected $_effect_speed = 1000;
    protected $_is_ajax = FALSE;
    protected $multiarray_sort_column;
    protected $multiarray_sort_dir;

    // Tables
    public $users_table_name = 'users';
    public $roles_table_name = 'roles';
    public $firms_table_name = 'legal_firms';
    public $legal_attorneys_table_name = 'legal_attys';
    public $activity_logs_table_name = 'portal_activity_logs';
    public $activities_table_name = 'portal_activities';
    public $legal_activities_table_name = 'legal_portal_activities';
    public $legal_firms_table_name = 'legal_firms';
    public $legal_firms_users_table_name = 'legal_firms_users';
    public $legal_attorneys_users_table_name = 'legal_attys_users';
    public $legal_users_table_name = 'legal_users';
    public $marketers_table_name = 'marketers';
    public $contacts_table_name = 'contacts';
    public $contacts_attach_table_name = 'contacts_attach';
    public $forms_table_name = 'forms';
    public $clients_table_name = 'clients';
    public $practices_table_name = 'practices';
    public $practices_finances_table_name = 'practice_finances';
    public $practices_appt_reasons_table_name = 'ext_dbs_legal_apnmt_reasons';
    public $practices_locations_table_name = 'ext_dbs_practice_locs';
    public $fin_classes_table_name = 'ext_dbs_fin_classes';
    public $fin_groups_table_name = 'fin_grps';
    public $ext_dbs_table_name = 'ext_dbs';
    public $portal_settings_table_name = 'portal_settings';
    public $ext_dbs_legal_attys_table_name = 'ext_dbs_legal_attys';
    public $notifications_table_name = 'notifications';
    public $notifications_users_table_name = 'notifications_users';
    public $cases_case_mgrs_table_name = 'legal_cases_legal_case_mgrs';
    public $high_charges_table_name = 'high_charges';
    public $zip_codes_table_name = 'zip_codes';

    public $mshc_dir_view = '';
    public $sortingTable = array();
    public $is_mobile = FALSE;

    protected $_uri_segments = array();
    protected $_breadcrumbs = '';

    protected $_settings = array();

    public function __construct()
    {
        parent::__construct();
        //echo phpinfo();
        $this->load->model('users');
        $this->load->model('marketers');
        $this->load->model('forms');
        $this->load->model('firms');
        $this->load->model('contacts');
        $this->load->model('activity');
        $this->load->model('clients');
        $this->load->model('portal_settings');
        $this->load->model('notifications');
        $this->load->library('session');
        $this->load->library('user_agent');
        $this->load->config('auth', TRUE);
        $this->load->helper(array('date', 'url', 'html', 'form', 'mshc_helper', 'array', 'download'));

        $this->_is_ajax = $this->input->is_ajax_request();

        $this->_uri_segments = $this->uri->segment_array();
        $this->_public_pages = array(
            MSHC_AUTH_CONTROLLER_NAME . '/login' => 'auth/login',
            MSHC_AUTH_CONTROLLER_NAME . '/forgot_password' => 'auth/forgot_password',
            'cron/create_notifications' => 'cron/create_notifications',
            'cron/create_high_charges' => 'cron/create_high_charges',
            'cron/delete_converted' => 'cron/delete_converted'
        );

        // Try to autologin
        $this->_autologin();

        $this->_check_login();

        date_default_timezone_set(MSHC_TIMEZONE);

        $this->load->library('user_agent');

        $this->is_mobile = $this->isMobile();

        if ($this->session->userdata('site_state')
            && $this->session->userdata('site_state') == 'view_full_site'
            || !$this->isMobile()
        ) {
            $this->mshc_dir_view = '';
            //define('MSHC_DIR_VIEW', ''); // temp
        } else {
            $this->mshc_dir_view = 'mobile/';
            //define('MSHC_DIR_VIEW', 'mobile/');
        }

        $call_controller = get_array_value(1, $this->_uri_segments);

        if (!$this->_is_allowed()) {
            // NOT allowed
            if ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
                if ($call_controller != MSHC_HOME_CONTROLLER_NAME) {
                    $this->session->set_flashdata('general_flash_message', '{type:"not_allowed",text:"You have not permission to perform this action."}');
                }
                redirect(base_url() . MSHC_CASES_CONTROLLER_NAME);
            } else {
                $this->session->set_flashdata('general_flash_message', '{type:"not_allowed",text:"You have not permission to perform this action."}');
                redirect(base_url());
            }
            return;
        }

        if (!$this->_is_ajax && $call_controller != MSHC_CASES_CONTROLLER_NAME) {
            $this->session->unset_userdata('client_cases_name');
            $this->session->unset_userdata('client_cases_ssn');
            $this->session->unset_userdata('client_cases_phone');
            $this->session->unset_userdata('client_cases_dob');
        }

        $this->_add_custom_style('/js/jqwidgets-ver3.0.3/styles/jqx.base.css');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxcore.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxmaskedinput.js');
        $this->_add_custom_script('/js/jquery/jquery-caret/jquery.caret.js');

        $this->_settings = $this->portal_settings->get_portal_settings();

        $this->_settings['email_administrator'] = element('email_administrator', $this->_settings) ? $this->_settings['email_administrator'] : MSHC_ADMIN_EMAIL;
    }


    public function isMobile()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
            return true;
        else
            return false;
    }

    /**
     * Login user on the site. Rturn TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param    string
     * @param    string
     * @param    bool
     * @return    bool
     */
    public function _login($login, $password, $remember)
    {
        if ((strlen($login) > 0) && (strlen($password) > 0)) {
            if (!is_null($user = $this->users->get_user_by_username($login))) {
                // login ok
                // Does password match hash in database?
                $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'auth'),
                    $this->config->item('phpass_hash_portable', 'auth'));

                if ($hasher->CheckPassword($password, $user['password'])) {
                    // password ok
                    if ($user['is_locked_out'] == 1) {
                        // fail - locked out
                        $this->_auth_error = array(
                            'locked_out' => 'You are locked out. Signing in is not allowed. Please contact with your system administrator.'
                        );
                    } else {
                        $this->set_user_session_data($user);

                        if ($remember) {
                            $this->users->_create_autologin($user['id']);
                        }

                        $this->users->clear_login_attempts($login);

                        $this->users->update_login_info($user['id']);

                        // Add portal activity
                        $activity_info = 'IP: ' . $this->session->userdata('ip_address') . '; Browser: '
                            . $this->agent->browser() . ' ' . $this->agent->version() . ';';
                        $this->activity->add_activity_log('Login Successful', 'both', $activity_info);

                        return TRUE;
                    }
                } else {
                    // fail - wrong password
                    $this->users->increase_login_attempt($login);
                    $this->_auth_error = array('password' => 'Incorrect password');
                }
            } else {
                // fail - wrong login
                //$this->users->increase_login_attempt($login);
                $this->_auth_error = array('login' => 'Incorrect username');
            }
        }
        return FALSE;
    }

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return    void
     */
    private function _autologin()
    {
        if (!$this->_is_logged_in()) {
            // not logged in (as any user)
            $this->load->helper('cookie');
            if ($cookie = get_cookie($this->config->item('autologin_cookie_name', 'auth'), TRUE)) {
                $data = unserialize($cookie);

                if (isset($data['key']) && isset($data['user_id'])) {
                    $user = $this->users->_get_autologin($data['user_id'], $data['key']);

                    if (!is_null($user)) {
                        // Login user
                        $this->set_user_session_data($user);

                        //date_default_timezone_set ($user['timezone']);

                        // Add portal activity
                        $activity_info = 'IP: ' . $this->session->userdata('ip_address') . '; Browser: '
                            . $this->agent->browser() . ' ' . $this->agent->version() . ';';
                        $this->activity->add_activity_log('Autologin Successful', 'both', $activity_info);

                        // Renew users cookie to prevent it from expiring
                        set_cookie(array(
                            'name' => $this->config->item('autologin_cookie_name', 'auth'),
                            'value' => $cookie,
                            'expire' => $this->config->item('autologin_cookie_life', 'auth'),
                        ));

                        $this->users->update_login_info($user['id']);
                    }
                }
            }
        }
    }

    /**
     * Loggin out user from the site
     *
     * @return    void
     */
    function _logout()
    {
        // Add portal activity
        $activity_info = 'IP: ' . $this->session->userdata('ip_address') . '; Browser: ' . $this->agent->browser() . ' ' . $this->agent->version() . ';';
        $this->activity->add_activity_log('Logging Out', 'both', $activity_info);

        $this->users->_delete_autologin($this->_user['id']);

        // See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
        $this->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => ''));
        $this->session->sess_destroy();
        redirect();
        return;
    }

    /**
     * sets necessary session variables when user logs in
     *
     * @param array $user
     */
    private function set_user_session_data($user)
    {
        $firm = $this->users->get_primary_firm($user['id']);

        $this->session->set_userdata(array(
            'user_id' => $user['id'],
            'firm_id' => $firm ? $firm->id : NULL,
            'username' => $user['username'],
            'status' => 1,
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role_id' => $user['role_id'],
            'timezone' => $user['timezone']
        ));
    }

    public function _render()
    {
        // Load header
        $data['custom_scripts'] = $this->_custom_scripts;
        $data['custom_styles'] = $this->_custom_styles;
        $data['page_title'] = $this->_page_title;
        $data['page_description'] = $this->_page_description;
        $data['page_keywords'] = $this->_page_keywords;
        $data['main_css'] = '/css/main.css';
        $data['general_flash_message'] = $this->session->userdata('general_flash_message');
        $data['is_mobile'] = $this->is_mobile;
        $this->session->unset_userdata('general_flash_message');
        $this->load->view($this->mshc_dir_view . $this->_common_header, $data);

        // Check privacy
        if ($this->_is_logged_in()) {
            $this->_user_header = 'header_private';
            // Load breadcrumbs
            $data['breadcrumbs'] = $this->_get_breadcrumbs();

            // Load main menu
            $data['main_menu'] = $this->_get_main_menu();

            // Load sub menus
            $data['sub_menus'] = $this->_get_sub_menus();

            // Load account dialog
            $data['account_dialog'] = $this->load->view($this->mshc_dir_view . 'account', $this->_user, TRUE);
        } else {
            $this->_user_header = 'header_public';
            // Load breadcrumbs
            $data['breadcrumbs'] = '';

            // Load sub menus
            $data['sub_menus'] = array(
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    MSHC_HELP_MANUAL_NAME => 'How to Manual'
                )
            );

            $data['account_dialog'] = '';
        }

        // Set authorized user
        $data['user'] = $this->_user;

        // Load logos
        $data['header_logos'] = $this->load->view($this->mshc_dir_view . 'header_logos', array(), TRUE);

        $data['current_path'] = $this->uri->segment(2);

        // Load user header
        $this->load->view($this->mshc_dir_view . $this->_user_header, $data);

        // Load flash message
        if ($data['general_flash_message']) $this->load->view($this->mshc_dir_view . 'flash_message', $data);

        // Load views
        if ($this->_views && is_array($this->_views)) {
            // reset views data in case we need to populate the 'mega-view' holding other views
            $mega_view_data = array();

            // ensure array is sorted in ascending order
            ksort($this->_views);

            foreach ($this->_views as $one_display_order) {
                foreach ($one_display_order as $one_view) {
                    $mega_view_data = array_merge($mega_view_data, $one_view['data']);

                    if ($one_view['to_string'] == TRUE) {
                        if (get_array_value($one_view['string_var'], $mega_view_data) === NULL) {
                            $mega_view_data[$one_view['string_var']] = $this->load->view($this->mshc_dir_view . $one_view['name'], $mega_view_data, true);
                        } else {
                            $mega_view_data[$one_view['string_var']] .= $this->load->view($this->mshc_dir_view . $one_view['name'], $mega_view_data, true);
                        }

                    } else {
                        $this->load->view($this->mshc_dir_view . $one_view['name'], $mega_view_data);
                    }
                }
            }
        }

        // Load footer
        $this->load->view($this->mshc_dir_view . $this->_common_footer, $data);
    }

    /*
    * Check is user logged in, get role and permissions
    */
    protected function _check_login()
    {
        $pos = strpos($this->uri->uri_string(),'/statement/');
        if ($pos !== false) {
            return;
        } else if (!array_key_exists($this->uri->uri_string(), $this->_public_pages)) {
            if (!$this->_is_logged_in()) {
                // NOT logged in
                if ($this->input->is_ajax_request()) {
                    print_r(json_encode(array('login_error' => 403)));
                    exit();
                } else {
                    $redirect_url = urlencode($this->uri->uri_string());
                    //if ($redirect_url != '')
                    //$this->session->set_flashdata('general_flash_message', '{type:"not_logged",text:"You are not logged in."}');
                    redirect(base_url() . MSHC_AUTH_CONTROLLER_NAME . '/login?r=' . $redirect_url);
                    return;
                }
            }
        } else {
            return;
        }

        $this->_user = $this->users->_get_user();

        // Set permissions
        $this->_user['permissions'] = array(
            'maintain_clients_allowed' => $this->_user['maintain_clients_allowed'],
            'maintain_attorneys_allowed' => $this->_user['maintain_attorneys_allowed'],
            'maintain_firms_allowed' => $this->_user['maintain_firms_allowed'],
            'maintain_marketers_allowed' => $this->_user['maintain_marketers_allowed'],
            'view_portal_activity_logs_allowed' => $this->_user['view_portal_activity_logs_allowed'],
            'view_cases_for_firm_allowed' => $this->_user['view_cases_for_firm_allowed']
        );
    }

    /**
     * Check if user logged in. Also test if user is activated or not.
     *
     * @param    bool
     * @return    bool
     */
    protected function _is_logged_in()
    {
        return $this->session->userdata('status') === 1;
    }

    // Check permissions
    protected function _is_allowed()
    {
        $call_controller = get_array_value(1, $this->_uri_segments);
        if (!$call_controller) $call_controller = MSHC_HOME_CONTROLLER_NAME;
        $call_method = get_array_value(2, $this->_uri_segments);
        if (!$call_method) $call_method = '';

        // if public page
        if (array_key_exists($this->uri->uri_string(), $this->_public_pages)) {
            return TRUE;
        }

        // redirect biller from home
        if ($call_controller == MSHC_HOME_CONTROLLER_NAME && $this->_user['role_id'] == MSHC_AUTH_BILLER) {
            return FALSE;
        }

        // if home page
        if (
            $call_controller == MSHC_HOME_CONTROLLER_NAME ||
            $call_controller == MSHC_AUTH_CONTROLLER_NAME ||
            $call_controller == MSHC_HELP_CONTROLLER_NAME ||
            $call_controller == MSHC_AJAX_CONTROLLER_NAME ||
            $call_controller == MSHC_CONTACT_CONTROLLER_NAME ||
            $call_controller == MSHC_NOTIFICATIONS_CONTROLLER_NAME
        ) {
            return TRUE;
        }

        // if system administrator
        if ($this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            return TRUE;
        }

        // if cases pages
        if ($call_controller == MSHC_CASES_CONTROLLER_NAME) {
            if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER) {
                return FALSE;
            }

            if ($call_method == MSHC_CASES_ASSIGN_MANAGER_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
                    return FALSE;
                }

                if ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
                    return FALSE;
                }
            }

            if ($call_method == MSHC_CASES_NEW_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
                    return FALSE;
                }
            }

            return TRUE;
        }

        // if admin pages
        if ($call_controller == MSHC_ADMIN_CONTROLLER_NAME) {
            if ($this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
                return FALSE;
            }

            if ($this->_user['role_id'] == MSHC_AUTH_CASE_MANAGER) {
                return FALSE;
            }

            if ($call_method != MSHC_ADMIN_USERS_NAME && $this->_user['role_id'] == MSHC_AUTH_BILLER) {
                return FALSE;
            }

            if ($call_method == MSHC_ADMIN_ACTIVITIES_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER && !$this->_user['view_portal_activity_logs_allowed']) {
                    return FALSE;
                }

                return TRUE;
            }

            if ($call_method == MSHC_ADMIN_CLIENTS_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER && $this->_user['maintain_clients_allowed']) {
                    return TRUE;
                }

                return FALSE;
            }

            if ($call_method == MSHC_ADMIN_MARKETERS_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER && !$this->_user['maintain_marketers_allowed']) {
                    return FALSE;
                }

                return TRUE;
            }

            if ($call_method == MSHC_ADMIN_FORMS_NAME) {
                if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER && !$this->_user['maintain_patient_forms_allowed']) {
                    return FALSE;
                }

                return TRUE;
            }

            if ($call_method == MSHC_ADMIN_FIRMS_NAME) {
                if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
                    return FALSE;
                }

                return TRUE;
            }

            return TRUE;
        }

        // if forms page
        if ($call_controller == MSHC_FORMS_CONTROLLER_NAME) {
            if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER) {
                return FALSE;
            }

            if ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
                return FALSE;
            }

            return TRUE;
        }

        // if reports page
        if ($call_controller == MSHC_REPORTS_CONTROLLER_NAME) {
            if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER) {
                return FALSE;
            }

            return TRUE;
        }

        if ($call_controller == MSHC_FILES_CONTROLLER_NAME) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Adds another view to array of views for future rendering
     *
     * @param    string  $name of the view to be loaded
     * @param    int $order in which the view to be loaded
     * @param    $data array of associated data that should be tied to this view
     * @param    bool $to_string TRUE/FALSE specifying whether to output results to screen or string
     * @param    string $string_var variable name to which this view should be output
     * @return void
     */
    public function _add_view($name, $order = 1, $data = array(), $to_string = FALSE, $string_var = '')
    {
        $new_view = array(
            'name' => $name,
            'data' => (array)$data,
            'to_string' => (bool)$to_string,
            'string_var' => $string_var
        );
        $this->_views[(int)$order][] = $new_view;
    }

    /**
     * add a custom script
     *
     * @param string
     */
    public function _add_custom_script($script)
    {
        $this->_custom_scripts[] = '<script src="' . $script . '"></script>' . "\n";
    }

    /**
     * add a custom style
     *
     * @param string
     */
    public function _add_custom_style($style)
    {
        $this->_custom_styles[] = '<link rel="stylesheet" type="text/css" media="all" href="' . $style . '" />' . "\n";
    }

    /*
    * Set page title
    */
    public function _set_page_title($page_title)
    {
        $this->_page_title = $page_title;
    }

    /*
    * Get main menu
    */
    protected function _get_main_menu()
    {
        $menu_ary = array();

        if ($this->_user['role_id'] == MSHC_AUTH_CASE_MANAGER || $this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
            if ($this->mshc_dir_view == 'mobile/') {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_CASES_CONTROLLER_NAME => 'Client Search',
                    MSHC_FORMS_CONTROLLER_NAME => 'Forms',
                    MSHC_REPORTS_CONTROLLER_NAME . '/' . MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_CONTROLLER_NAME . '/' . MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report',
                    MSHC_NOTIFICATIONS_CONTROLLER_NAME => 'Notifications',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us',
                    'profile/logout' => 'Signout'
                );
            } else {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_CASES_CONTROLLER_NAME => 'Client Cases',
                    MSHC_FORMS_CONTROLLER_NAME => 'Forms',
                    MSHC_REPORTS_CONTROLLER_NAME => 'Reports',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us'
                );
            }
        } elseif (
            $this->_user['role_id'] == MSHC_AUTH_FIRM_ADMIN ||
            $this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN
        ) {
            if ($this->mshc_dir_view == 'mobile/') {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_CASES_CONTROLLER_NAME => 'Client Search',
                    MSHC_FORMS_CONTROLLER_NAME => 'Forms',
                    MSHC_REPORTS_CONTROLLER_NAME . '/' . MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_CONTROLLER_NAME . '/' . MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report',
                    MSHC_NOTIFICATIONS_CONTROLLER_NAME => 'Notifications',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us',
                    'profile/logout' => 'Signout'
                );
            } else {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_CASES_CONTROLLER_NAME => 'Client Cases',
                    MSHC_ADMIN_CONTROLLER_NAME => 'Administration',
                    MSHC_FORMS_CONTROLLER_NAME => 'Forms',
                    MSHC_REPORTS_CONTROLLER_NAME => 'Reports',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us'
                );
            }
        } elseif ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER) {
            if ($this->mshc_dir_view == 'mobile/') {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_NOTIFICATIONS_CONTROLLER_NAME => 'Notifications',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us',
                    'profile/logout' => 'Signout'
                );
            } else {
                $menu_ary = array(
                    MSHC_HOME_CONTROLLER_NAME => 'Home',
                    MSHC_ADMIN_CONTROLLER_NAME => 'Administration',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us'
                );
            }
        } elseif ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
            if ($this->mshc_dir_view == 'mobile/') {
                $menu_ary = array(
                    MSHC_CASES_CONTROLLER_NAME => 'Client Search',
                    MSHC_ADMIN_CONTROLLER_NAME . '/' . MSHC_ADMIN_USERS_NAME => 'Users',
                    MSHC_REPORTS_CONTROLLER_NAME => 'Reports',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us',
                    'profile/logout' => 'Signout'
                );
            } else {
                $menu_ary = array(
                    MSHC_CASES_CONTROLLER_NAME => 'Client Search',
                    MSHC_ADMIN_CONTROLLER_NAME . '/' . MSHC_ADMIN_USERS_NAME => 'Users',
                    MSHC_REPORTS_CONTROLLER_NAME => 'Reports',
                    MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us'
                );
            }
        }
        return $menu_ary;
    }

    /*
    * Get controllers
    */
    protected function _get_controllers()
    {
        $menu_ary = array(
            MSHC_HOME_CONTROLLER_NAME => 'Home',
            MSHC_CASES_CONTROLLER_NAME => 'Client Cases',
            MSHC_ADMIN_CONTROLLER_NAME => 'Administration',
            MSHC_FORMS_CONTROLLER_NAME => 'Forms',
            MSHC_REPORTS_CONTROLLER_NAME => 'Reports',
            MSHC_CONTACT_CONTROLLER_NAME => 'Contact Us',
            MSHC_HELP_CONTROLLER_NAME => 'Help',
            MSHC_NOTIFICATIONS_CONTROLLER_NAME => 'Notifications'
        );

        return $menu_ary;
    }

    /*
    * Get sub menus
    */
    protected function _get_methods()
    {
        $sub_menus_ary = array(
            MSHC_CASES_CONTROLLER_NAME => array(
                MSHC_CASES_CLIENT_SEARCH_NAME => 'Client Search',
                MSHC_CASES_NEW_NAME => 'New Cases',
                MSHC_CASES_REGISTER => 'New Case Registration',
                MSHC_CASES_ASSIGN_MANAGER_NAME => 'Assign Case Manager'
            ),
            MSHC_ADMIN_CONTROLLER_NAME => array(
                MSHC_ADMIN_USERS_NAME => 'User Maintenance',
                MSHC_ADMIN_ACTIVITIES_NAME => 'View Activity Log',
                MSHC_ADMIN_CLIENTS_NAME => 'Client Maintenance',
                MSHC_ADMIN_FIRMS_NAME => 'Firm/Attorney Maintenance',
                MSHC_ADMIN_MARKETERS_NAME => 'Marketer Maintenance',
                MSHC_ADMIN_SETTINGS_NAME => 'Portal Settings',
                MSHC_ADMIN_FORMS_NAME => 'Patient Forms Maintenance'
            ),
            MSHC_REPORTS_CONTROLLER_NAME => array(
                MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report'
            ),
            MSHC_HELP_CONTROLLER_NAME => array(
                MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                MSHC_HELP_MANUAL_NAME => 'How to Manual'
            )
        );
        return $sub_menus_ary;
    }

    /*
    * Get sub menus
    */
    protected function _get_sub_menus()
    {
        $sub_menus_ary = array();
        if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER) {
            $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_USERS_NAME] = 'User Maintenance';
            if ($this->_user['view_portal_activity_logs_allowed']) {
                $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_ACTIVITIES_NAME] = 'View Activity Log';
            }
            if ($this->_user['maintain_clients_allowed']) {
                $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_CLIENTS_NAME] = 'Client Maintenance';
            }
            $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_FIRMS_NAME] = 'Firm/Attorney Maintenance';
            if ($this->_user['maintain_marketers_allowed']) {
                $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_MARKETERS_NAME] = 'Marketer Maintenance';
            }
            if ($this->_user['maintain_patient_forms_allowed']) {
                $sub_menus_ary[MSHC_ADMIN_CONTROLLER_NAME][MSHC_ADMIN_FORMS_NAME] = 'Patient Forms Maintenance';
            }
            $sub_menus_ary[MSHC_HELP_CONTROLLER_NAME] = array(
                MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
            );
        } elseif ($this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            $sub_menus_ary = array(
                MSHC_CASES_CONTROLLER_NAME => array(
                    MSHC_CASES_CLIENT_SEARCH_NAME => 'Client Search',
                    MSHC_CASES_NEW_NAME => 'New Cases',
                    MSHC_CASES_ASSIGN_MANAGER_NAME => 'Assign Case Manager'
                ),
                MSHC_ADMIN_CONTROLLER_NAME => array(
                    MSHC_ADMIN_USERS_NAME => 'User Maintenance',
                    MSHC_ADMIN_ACTIVITIES_NAME => 'View Activity Log',
                    MSHC_ADMIN_CLIENTS_NAME => 'Client Maintenance',
                    MSHC_ADMIN_FIRMS_NAME => 'Firm/Attorney Maintenance',
                    MSHC_ADMIN_MARKETERS_NAME => 'Marketer Maintenance',
                    MSHC_ADMIN_SETTINGS_NAME => 'Portal Settings',
                    MSHC_ADMIN_FORMS_NAME => 'Patient Forms Maintenance'
                ),
                MSHC_REPORTS_CONTROLLER_NAME => array(
                    MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report'
                ),
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
                )
            );
        } elseif ($this->_user['role_id'] == MSHC_AUTH_FIRM_ADMIN) {
            $sub_menus_ary = array(
                MSHC_CASES_CONTROLLER_NAME => array(
                    MSHC_CASES_CLIENT_SEARCH_NAME => 'Client Search',
                    MSHC_CASES_NEW_NAME => 'New Cases',
                    MSHC_CASES_ASSIGN_MANAGER_NAME => 'Assign Case Manager'
                ),
                MSHC_ADMIN_CONTROLLER_NAME => array(
                    MSHC_ADMIN_USERS_NAME => 'User Maintenance',
                    MSHC_ADMIN_ACTIVITIES_NAME => 'View Activity Log',
                    /*MSHC_ADMIN_FIRMS_NAME => 'Firm/Attorney Maintenance'*/
                ),
                MSHC_REPORTS_CONTROLLER_NAME => array(
                    MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report',
                ),
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
                )
            );
        } elseif ($this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
            $sub_menus_ary = array(
                MSHC_CASES_CONTROLLER_NAME => array(
                    MSHC_CASES_CLIENT_SEARCH_NAME => 'Client Search',
                    MSHC_CASES_NEW_NAME => 'New Cases'
                ),
                MSHC_REPORTS_CONTROLLER_NAME => array(
                    MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report',
                ),
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
                )
            );
        } elseif ($this->_user['role_id'] == MSHC_AUTH_CASE_MANAGER) {
            $sub_menus_ary = array(
                MSHC_CASES_CONTROLLER_NAME => array(
                    MSHC_CASES_CLIENT_SEARCH_NAME => 'Client Search',
                    MSHC_CASES_NEW_NAME => 'New Cases',
                    MSHC_CASES_ASSIGN_MANAGER_NAME => 'Assign Case Manager'
                ),
                MSHC_REPORTS_CONTROLLER_NAME => array(
                    MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report',
                ),
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
                )
            );
        } elseif ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
            $sub_menus_ary = array(
                MSHC_REPORTS_CONTROLLER_NAME => array(
                    MSHC_REPORTS_DISCHARGE_NAME => 'Discharge Report & Client List',
                    MSHC_REPORTS_MILEAGE_NAME => 'Mileage Report'
                ),
                MSHC_HELP_CONTROLLER_NAME => array(
                    MSHC_HELP_FAQ_NAME => 'Frequently Asked Questions',
                    /*MSHC_HELP_MANUAL_NAME => 'How to Manual'*/
                )
            );
        }
        return $sub_menus_ary;
    }

    protected function _get_breadcrumbs()
    {
        $controllers = $this->_get_controllers();
        $methods = $this->_get_methods();
        $call_controller = get_array_value(1, $this->_uri_segments);
        if (!$call_controller) $call_controller = MSHC_HOME_CONTROLLER_NAME;
        $call_method = get_array_value(2, $this->_uri_segments);
        $breadcrumbs[$call_controller] = $controllers[$call_controller];
        if ($call_method) {
            foreach ($methods as $key => $val) {
                if ($key == $call_controller) {
                    $breadcrumbs[$call_method] = get_array_value($call_method, $val);
                }
            }
        }
        return $breadcrumbs;
    }

    protected function _public_page()
    {
        return base_url() . MSHC_ADMIN_CONTROLLER_NAME . '/login';
    }

    protected function _get_public_page()
    {
        $this->_add_view('public_page');
        $this->_render();
    }

    /*
    *	Formating order by from post
    */
    public function get_order_by_post($data = array())
    {
        $order_comma = explode(',', $data['jtSorting']);
//		print_r($order_comma);
        $input_order = array();
        foreach ($order_comma as $k => $v) {
            $input_order[] = explode(' ', $v);
        }
//		print_r($input_order);

        $order = array();
        $field = '';
        foreach ($input_order as $key => $value) {
            foreach ($value as $k => $v) {
                if (!in_array(strtoupper($v), array('DESC', 'ASC'))) {
                    if ($field != '') {
                        $order[$field] = '';
                        $field = '';
                    } else {
                        $field = $v;
                    }
                } else {
                    $order[$field] = strtoupper($v);
                    $field = '';
                }
            }
        }

        if ($field != '') {
            $order[$field] = '';
        }

        return $order;
    }

    /*
    * Send mail with params
    * @param array
    */
    public function _send_mail($params = array(), $debug = true)
    {
        if (!is_array($params) && !count($params)) {
            return FALSE;
        }

        $smtp_host = element('server_url', $this->_settings);
        $smtp_user = element('username', $this->_settings);
        $smtp_pass = element('password', $this->_settings);
        $smtp_port = element('server_port', $this->_settings);

        if ($smtp_host && $smtp_user && $smtp_pass && $smtp_port) {
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = $smtp_host;
            $config['smtp_user'] = $smtp_user;
            $config['smtp_pass'] = $smtp_pass;
            $config['smtp_port'] = $smtp_port;
            $config['mailtype'] = element('mailtype', $params) ? $params['mailtype'] : 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $this->email->initialize($config);
            $this->email->set_crlf("\r\n");

            $send_to = element('send_to', $params) ? $params['send_to'] : $this->_settings['email_administrator'];

            $default_send_from = element('email_from', $this->_settings)
                ? element('email_from', $this->_settings)
                : $this->_settings['email_administrator'];

            $send_from = element('send_from', $params) ? $params['send_from'] : $default_send_from;
            $send_from_name = element('send_from_name', $params) ? $params['send_from_name'] : 'MSHC Attorney Portal';
            $subject = element('subject', $params) ? $params['subject'] : 'Email from MSHC Attorney Portal';
            $message = element('message', $params) ? $params['message'] : '';
            $alt_message = element('alt_message', $params) ? $params['alt_message'] : '';
            $cc = element('cc', $params) ? $params['cc'] : NULL;
            $reply_to = element('reply_to', $params) ? $params['reply_to'] : NULL;
            $attach = element('attach', $params) ? $params['attach'] : NULL;

            $this->email->from($send_from, $send_from_name);
            $this->email->to($send_to);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->set_alt_message($alt_message);
            if ($cc) $this->email->cc($cc);
            if ($reply_to) {
                $reply_to_name = isset($params['userdata']['name']) ? $params['userdata']['name'] : $send_from_name;
                $this->email->reply_to($reply_to, $reply_to_name);
            }

            if ($attach) {
                foreach ($attach as $single) {
                    $this->email->attach($single);
                }
            }

            $sending = $this->email->send();

            if ($debug) return $this->email->print_debugger();
            else return $sending;
        }
        return FALSE;
    }

    /**
     * ensures that the function was invoked by GET method
     * @param    send_email - send email to us
     * @param    extra_info - any additional details
     *
     * @return void
     */
    public function _ensure_post_method()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            @ob_end_clean(); // clear output buffer
            header('HTTP/1.1 400 Bad Request');
            return;
        }
    }

    /*
     * sort multi-dimensional array by column in second level array
     *
     * example of array:
     * array(
     * 	'a' => array(
     * 			'a1' => 'av1',
     * 			'a2' => 'av2'
     * 	),
     * 	'b' => array(
     * 			'b1' => 'bv1',
     * 			'b2' => 'bv2'
     * 	)
     * );
    */
    public function multiarray_sort($i, $j)
    {
        if ($this->multiarray_sort_dir == 'ASC') {
            $a = $i[$this->multiarray_sort_column];
            $b = $j[$this->multiarray_sort_column];
            if ($a == $b) return 0;
            elseif ($a > $b) return 1;
            else return -1;
        } else {
            $a = $i[$this->multiarray_sort_column];
            $b = $j[$this->multiarray_sort_column];
            if ($a == $b) return 0;
            elseif ($a > $b) return -1;
            else return 1;
        }
    }

}

/* End of file MSHC_Controller.php */
/* Location: ./application/core/MSHC_Controller.php */