<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MSHC_Controller 
{	
    public function __construct() 
    {
        parent::__construct();
	}

    /**
     * Index Page for this controller.
     * @param string $sort_by
     * @param string $order_by
     */
	public function index($sort_by = 'a', $order_by = 'asc')
	{
		$this->_add_custom_script('/js/jquery/jquery.scrollTo-min.js');
		
		$call_method = get_array_value(2,$this->_uri_segments);
		
		if ($call_method == 'view_full_site') $this->view_full_site($sort_by, $order_by);
		elseif ($call_method == 'view_mobile_site') $this->view_mobile_site($sort_by, $order_by);
		else $this->dashboard($sort_by, $order_by);
	}
	
	protected function view_full_site($sort_by = 'a', $order_by = 'asc') 
	{
		$this->session->set_userdata(array('site_state' => 'view_full_site'));
		$this->mshc_dir_view = '';
		$this->dashboard($sort_by, $order_by);
	}
	
	protected function view_mobile_site($sort_by = 'a', $order_by = 'asc') 
	{
		$this->session->set_userdata(array('site_state' => 'view_mobile_site'));
		$this->mshc_dir_view = 'mobile/';
		$this->dashboard($sort_by, $order_by);
	}
	
	protected function dashboard($sort_by = 'a', $order_by = 'asc')
	{
		if ($this->mshc_dir_view == '')
		{
			switch ($this->_user['role_id'])
			{
				case MSHC_AUTH_SYSTEM_ADMIN : $this->system_admin_dashboard($sort_by, $order_by); break;
				
				case MSHC_AUTH_GENERAL_USER : $this->general_dashboard(); break;
				
				case MSHC_AUTH_FIRM_ADMIN : $this->firm_admin_dashboard(); break;
				
				case MSHC_AUTH_ATTORNEY : $this->attorney_case_dashboard(); break;
				
				case MSHC_AUTH_CASE_MANAGER : $this->attorney_case_dashboard(); break;
			}
		} 
		else 
		{
			$this->mobile_dashboard();
		}
	}
	
	protected function general_dashboard()
	{
		// Set page name
		$this->_set_page_title('General User Dashboard');

		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View General User Dashboard', 'both', $activity_info);
				
		// Get last added users
		$user_query['fields'] = array(
			'first_name' => '',
			'last_name' => '',
			'username' => '',
			'created' => ''
		);
		$user_query['order'][] = array(
			'created' => 'DESC'
		);
		$user_query['limit'] = 10;
		$data['last_users'] = $this->users->get_users($user_query);
		$data['recently_added_users'] = $this->load->view('recently_added_users', $data, true);
		
		// Get last added firms/attorneys
		$firm_query['fields'] = array(
			$this->firms_table_name.'.name' => '',
			$this->firms_table_name.'.created' => '',
			'COUNT('.$this->legal_attorneys_table_name.'.id)' => 'attorneys'
		);
		$firm_query['from'] = array(
			$this->legal_attorneys_table_name => ''
		);
		$firm_query['join'][] = array(
			'table' => $this->firms_table_name,
			'condition' => $this->firms_table_name.'.id = '.$this->legal_attorneys_table_name.'.legal_firm_id',
			'type' => 'right'
		);
		$firm_query['order'][] = array(
			$this->firms_table_name.'.created' => 'DESC'
		);
		$firm_query['group'] = array(
			$this->firms_table_name.'.id'
		);
		$firm_query['limit'] = 10;
		$data['last_firms'] = $this->firms->get_firms($firm_query);
		
		$data['dashboard_banner'] = $this->_settings['dashboard_banner'];
		
		$this->_add_view('dashboard_general', 1, $data);
		$this->_render();
	}
	
	protected function system_admin_dashboard($sort_by = 'a', $order_by = 'asc')
	{
		$this->load->driver('cache');
		$cache_file = 'dbc'.$this->_user['user_id'];
		
		// Set page name
		$this->_set_page_title('System Administrator Dashboard');

		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View System Administrator Dashboard', 'both', $activity_info);
		
		$cache = $this->cache->file->get($cache_file);
		$data = array();
		
		if ($cache === FALSE) {
			$atty_query['order'][] = array(
				'last_name' => 'ASC'
			);
			$data['attys'] = $this->firms->get_attorneys($atty_query);
			
			// Get user roles		
			$data['user_roles'] = get_user_roles_array($this->_user['role_id']);
			// Get user permissions
			$data['user_permissions'] = get_user_permissions_array();
			// Get user's firms/attorneys
			$data['user_linked_firms_attorneys'] = $this->firms->get_linked_firms_attorneys_by_user_id($this->_user['user_id']);
			$data['firms_attorneys'] = $this->firms->get_firms_attorneys('a', 'asc', TRUE);
			$data['dialog_firms_attorneys'] = $this->firms->get_firms_attorneys('all', 'asc', TRUE);
			
			// Get list of firms with attorneys
			$data['attorneys'] = $this->firms->get_firms_attorneys($sort_by, $order_by, TRUE);

			// Get list of external attorneys
			$this->load->library('mshc_connector');
			$data['ext_dbs'] = $this->mshc_connector->getDBArray();
			$data['ext_attorneys'] = NULL;//$this->firms->get_ext_attorneys();
			$data['firms_table_collumns'] = 1;
			$data['firms_table_header'] = FALSE;
			$data['firms_table_alpha'] = $sort_by;
			$data['firms_table_view_all'] = TRUE;
			
			//$this->cache->file->save($cache_file, $data, 1800);
		} else {
			$data = $cache;
		}
		
		$views = array();
		$views['cases_search_form'] = $this->load->view('dashboard_cases_search_form', $data, TRUE);
		$views['users_table'] = $this->load->view('dashboard_users_table', $data, TRUE);
		$views['users_dialog'] = $this->load->view('admin/users_dialog', $data, TRUE);
		$views['firms_table'] = $this->load->view('admin/firms_maintenance', $data, TRUE);
		$views['forms_table'] = $this->load->view('dashboard_forms_table', $data, TRUE);
		
		$views['dashboard_banner'] = element('dashboard_banner', $this->_settings);
		
		$this->_add_view('dashboard_system_admin', 1, $views);
		$this->_render();
	}
	
	protected function attorney_case_dashboard()
	{
				// Set page name
		if ($this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
			$this->_set_page_title('Attorney Dashboard');
			$data['attys'] = $this->firms->get_linked_firms_attorneys_by_user_id($this->_user['user_id']);
			$data['my_cases'] = FALSE;
		} else {
			$this->_set_page_title('Case Manager Dashboard');
			$data['attys'] = NULL;
			$data['my_cases'] = TRUE;
		}

		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Attorney Dashboard', 'both', $activity_info);
				
		// get notifications and count new
		$notifications_param['where'] = ' AND nu.deleted = 0 ';
		$notifications_param['jtStartIndex'] = 0;
		$notifications_param['jtPageSize'] = 4;
		$data['notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param);
		$notifications_param_count['where'] = ' AND nu.read = 0 AND nu.deleted = 0';
		$data['count_new_notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param_count, TRUE);
		$data['notifications'] = $this->load->view('dashboard_notifications', $data, TRUE);
		
		$data['cases_search_form'] = $this->load->view('dashboard_cases_search_form', $data, TRUE);
		
		$data['dashboard_banner'] = $this->_settings['dashboard_banner'];
		
		$this->_add_view('dashboard_attorney', 1, $data);
		$this->_render();
	}
	
	protected function firm_admin_dashboard()
	{
		// Set page name
		$this->_set_page_title('Firm Administrator Dashboard');
		
		$data['attys'] = NULL;
		$data['my_cases'] = FALSE;
			
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Firm Administrator Dashboard', 'both', $activity_info);
				
		// Get last added users
		/*$user_query['fields'] = array(
			'first_name' => '',
			'last_name' => '',
			'username' => '',
			'created' => ''
		);
		$user_query['order'][] = array(
			'created' => 'DESC'
		);
		$user_query['limit'] = 5;
		$data['last_users'] = $this->users->get_users($user_query);
		$data['recently_added_users'] = $this->load->view('recently_added_users', $data, true);*/
		
		// Get last added activity portal
		/*$log_query['fields'] = array(
			$this->activity_logs_table_name.'.created' => '',
			$this->users_table_name.'.last_name' => '',
			$this->users_table_name.'.first_name' => '',
			$this->activities_table_name.'.name' => '',
			$this->activity_logs_table_name.'.info' => ''
		);
		$log_query['from'] = array(
			$this->activity_logs_table_name => ''
		);
		$log_query['join'][] = array(
			'table' => $this->users_table_name,
			'condition' => $this->users_table_name.'.id = '.$this->activity_logs_table_name.'.user_id',
			'type' => 'right'
		);
		$log_query['join'][] = array(
			'table' => $this->activities_table_name,
			'condition' => $this->activities_table_name.'.id = '.$this->activity_logs_table_name.'.portal_activity_id',
			'type' => 'right'
		);
		$log_query['order'][] = array(
			$this->activity_logs_table_name.'.created' => 'DESC'
		);
		$firm_query['group'] = array(
			$this->activity_logs_table_name.'.id'
		);
		$log_query['limit'] = 10;
		$data['latest_activity_log'] = $this->activity->get_latest_activity_log($log_query);
		$data['latest_activity_log'] = $this->load->view('latest_activity_log', $data, true);*/
		
		// get notifications and count new
		$notifications_param['where'] = ' AND nu.deleted = 0 ';
		$notifications_param['jtStartIndex'] = 0;
		$notifications_param['jtPageSize'] = 4;
		$data['notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param);
		$notifications_param_count['where'] = ' AND nu.read = 0 AND nu.deleted = 0';
		$data['count_new_notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param_count, TRUE);
		$data['notifications'] = $this->load->view('dashboard_notifications', $data, TRUE);
		
		$data['attys'] = NULL;
		$data['cases_search_form'] = $this->load->view('dashboard_cases_search_form', $data, TRUE);
		
		$data['users_table'] = $this->load->view('dashboard_users_table', $data, TRUE);
		
		// Get user roles		
		$data['user_roles'] = get_user_roles_array($this->_user['role_id']);
		// Get user permissions
		$data['user_permissions'] = get_user_permissions_array();
		// Get user's firms/attorneys
		$data['user_linked_firms_attorneys'] = $this->firms->get_linked_firms_attorneys_by_user_id($this->_user['user_id']);
		$data['firms_attorneys'] = $this->firms->get_firms_attorneys('all', 'asc', TRUE);
		$data['users_dialog'] = $this->load->view('admin/users_dialog', $data, TRUE);
		
		$data['dashboard_banner'] = $this->_settings['dashboard_banner'];
		
		$this->_add_view('dashboard_firm_admin', 1, $data);
		$this->_render();
	}
	
	protected function mobile_dashboard()
	{
		// Set page name
		$this->_set_page_title('Dashboard');
		
		if ($this->_user['role_id'] == MSHC_AUTH_ATTORNEY) {
			$data['attys'] = $this->firms->get_linked_firms_attorneys_by_user_id($this->_user['user_id']);
			$data['my_cases'] = FALSE;
		} elseif ($this->_user['role_id'] == MSHC_AUTH_CASE_MANAGER) {
			$data['attys'] = NULL;
			$data['my_cases'] = TRUE;
		}
		else
		{
			$data['attys'] = NULL;
			$data['my_cases'] = FALSE;
		}
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Attorney Dashboard', 'both', $activity_info);
				
		// get notifications and count new
		$notifications_param['jtStartIndex'] = 0;
		$notifications_param['jtPageSize'] = 3;
		$notifications_param['where'] = '';
		$data['notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param, FALSE);

		$notifications_param_count['where'] = ' AND nu.read = 0 AND nu.deleted = 0 ';
		$data['count_new_notifications'] = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param_count, TRUE);
		
		$data['dashboard_banner'] = $this->_settings['dashboard_banner'];
		
		$this->_add_view('dashboard', 1, $data);
		$this->_render();
	}
		
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */