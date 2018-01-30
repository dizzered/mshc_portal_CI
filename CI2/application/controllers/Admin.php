<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MSHC_Controller 
{
	private $yesterday_begin;
	private $yesterday_end;
	
    public function __construct() 
    {
        parent::__construct();
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
		redirect(base_url().MSHC_HOME_CONTROLLER_NAME);
	}
	
	public function users()
	{
		$this->_add_custom_script('/js/jquery/jquery.scrollTo-min.js');
		
		// Set page name
		$this->_set_page_title('User Maintenance');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View User Maintenance', 'portal', $activity_info);
		
		// Get user roles		
		$data['user_roles'] = get_user_roles_array($this->_user['role_id']);
		
		// Get user permissions
		$data['user_permissions'] = get_user_permissions_array();
		
		// Get user's primary firm
		$data['user_primary_firm'] = $this->firms->get_primary_firm_by_user_id($this->_user['user_id']);
		
		// Get user's firms
		$data['user_firms_attaorneys'] = $this->firms->get_firms_by_user_id($this->_user['user_id']);
		
		$data['dialog_firms_attorneys'] = $this->firms->get_firms_attorneys('all', 'asc', TRUE);

		// Get user's firms/attorneys
		$data['user_linked_firms_attorneys'] = $this->firms->get_linked_firms_attorneys_by_user_id($this->_user['user_id']);
		
		$data['users_dialog'] = $this->load->view('admin/users_dialog', $data, TRUE);
		
		$this->_add_view('admin/users_maintenance',1,$data);
		
		$this->_render();
	}


	public function marketers()
	{		
		// Set page name
		$this->_set_page_title('Marketer Maintenance');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Marketer Maintenance', 'portal', $activity_info);
		
		$this->_add_view('admin/marketers_maintenance',1,$data = array());
		
		$this->_render();
	}


	public function forms()
	{		
		// Set page name
		$this->_set_page_title('Patient Forms Maintenance');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Patient Forms Maintenance', 'portal', $activity_info);
		
		$this->load->library('user_agent');
		if ($this->agent->is_browser('Safari')) $this->_add_custom_style('/css/safari.css');
		
		$this->_add_view('admin/forms_maintenance',1,$data = array());
		$this->_render();
	}
	
	
	/*
	* Update form to DB
	*/
	public function save_form()
	{
		$data = $this->input->post(NULL, TRUE);
		$form_id = $data['id'] = intval($data['form_view_id']);
		$old_file = element('file_name_uploads', $data);
		if ($old_file)
		{
			$data['file_name'] = $old_file;
		}
		unset($data['file_name_uploads']);
		unset($data['form_view_id']);
		
		$config['upload_path'] = MSHC_UPLOAD_FILE_PATH;
		$this->load->library('upload', $config);

		if ($form_id)
		{
			$action = 'Update';
			$data['modified'] = date('Y-m-d H:i:s');
			$data['modified_by'] = $this->_user['user_id'];
			$form_id = $this->forms->update_form($data);
			$new_form = 'false';
			
		}
		else
		{
			$action = 'Add';
			$data['created'] = date('Y-m-d H:i:s');
			$data['created_by'] = $this->_user['user_id'];
			$data['modified'] = date('Y-m-d H:i:s');
			$data['modified_by'] = $this->_user['user_id'];
			$form_id = $this->forms->add_new_form($data);
			$new_form = 'true';
		}

		$file_src = $form_id.'_'.$_FILES["file_name"]["name"];
		if ( ! move_uploaded_file($_FILES["file_name"]["tmp_name"], MSHC_UPLOAD_FILE_PATH.'/'.$file_src)) {
			$data['error'] = "Error uploading file ".$_FILES["file_name"]["name"].'.<br /><br />Permission denied.';
		} else {
			$data['file_name'] = $file_src;
			$data['id'] = $form_id;
			$this->forms->update_form($data);
		}
		
		// Add portal activity
		$activity_info = 'Form Name: '.$data['name'].'; File: '.$data['file_name'].';';
		$this->activity->add_activity_log($action.' Patient Form', 'portal', $activity_info);
	
		$data['form_id'] = $form_id;
		$data['new_form'] = $new_form;
		$this->_add_view('admin/forms_result', 1, $data);
		$this->_render();
	}


	public function firms($sort_by = 'all', $order_by = 'asc')
	{		
		// Set page name
		$this->_set_page_title('Firm/Attorney Maintenance');

		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Firm/Attorney Maintenance', 'both', $activity_info);
		
		// Get list of firms with attorneys
		$data['attorneys'] = $this->firms->get_firms_attorneys($sort_by, $order_by, TRUE);
		
		// Get list of external attorneys
		$this->load->library('mshc_connector');
		$data['ext_dbs'] = $this->mshc_connector->getDBArray();
		$data['ext_attorneys'] = NULL;//$this->firms->get_ext_attorneys();
		$data['firms_table_collumns'] = 2;
		$data['firms_table_header'] = TRUE;
		$data['firms_table_view_all'] = FALSE;
		$this->_add_view('admin/firms_maintenance',1,$data);
		
		$this->_render();
	}
		
	
	public function activities()
	{		
		// Set page name
		$this->_set_page_title('Activity Log');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Activity Log', 'portal', $activity_info);
		
		// Get users list
		$user_query['fields'] = array(
			'id' => '',
			'first_name' => '',
			'last_name' => '',
			'username' => '',
			'created' => ''
		);
		$user_query['order'][] = array(
			'last_name' => 'ASC'
		);
		
		if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN)
		{
			$this->db->select('olfu.user_id');
			$this->db->from($this->legal_firms_users_table_name.' AS lfu');
			$this->db->join($this->legal_firms_users_table_name.' AS olfu','olfu.legal_firm_id = lfu.legal_firm_id');
			$this->db->join($this->users_table_name.' AS u','u.id = olfu.user_id AND u.role_id != "'.MSHC_AUTH_SYSTEM_ADMIN.'"');
			$this->db->where('lfu.user_id', $this->_user['user_id']);
			$this->db->group_by('olfu.user_id');
			$query = $this->db->get();
			$users = array($this->_user['user_id']);
			if ($query->num_rows())
			{
				$users = array();
				$result = $query->result();
				foreach ($result as $user)
				{
					$users[] = $user->user_id;
				}
			}
			$user_query['where_in'] = array(
				'id' => $users
			);
		}
		
		$data['users_list'] = $this->users->get_users($user_query);
		
		// Get events list
		$data['events_list'] = $this->activity->get_events_list();
		
		$this->_add_view('admin/activity_log',1,$data);
		
		$this->_render();
	}
	
	/*
	* Clients maintenance
	*/
	public function clients()
	{
		$data = array();
		$client_id = $data['client_id'] = intval($this->uri->segment(3));
		
		if ($client_id) {
			// Set page name
			$this->_set_page_title('Practice Maintenance');	
			
			$viewer = 'practices_maintenance';
			
			// Get client
			$param['where'] = array(
				'id' => $client_id
			);
			$data['client'] = $this->clients->get_clients($param);
			
			// Add portal activity
			$activity_info = 'Client Name: '.$data['client'][0]['name'].';';
			$this->activity->add_activity_log('View Practices Maintenance', 'both', $activity_info);
			
			// Get practices for client
			$param['where'] = array(
				'client_id' => $client_id
			);
			$data['practices'] = $this->clients->get_practices($param);
		} else {
			// Set page name
			$this->_set_page_title('Client Maintenance');	
			
			// Add portal activity
			$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
			$this->activity->add_activity_log('View Client Maintenance', 'both', $activity_info);
			
			$viewer = 'clients_maintenance';
		}
		
		// Get available locations
		$data['locs_avail'] = $this->clients->get_external_locations();
		
		// Get appointment reasons
		$data['appt_reasosns'] = $this->clients->get_external_appt_reasons();
		
		$data['ext_dbs'] = $this->mshc_connector->getDBArray();
		
		// Get available financial classes
		$data['fin_classes_avail'] = $this->clients->get_available_fin_classes();

		// Get available financial groups
		$data['fin_grps_avail'] = $this->clients->get_available_fin_groups();
		
		// Get external DBs
		//$data['ext_dbs'] = $this->clients->get_external_dbs();
				
		$this->_add_view('admin/'.$viewer,1,$data);
		
		$this->_render();
	}

	/*
	* Portal settings
	*/
	public function portal_settings()
	{
		// Set page name
		$this->_set_page_title('Portal Settings');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Portal Settings', 'portal', $activity_info);
		
		$data = array();
		
		// Get portal settings
		$data['portal_settings'] = $this->portal_settings->get_portal_settings();
		
		if ( count($data['portal_settings']) == 0) {
			$data['portal_settings'] = array(
			  'logo' => '',
			  'server_url' => '',
			  'username' => '',
			  'server_port' => '',
			  'email_from' => '',
			  'password' => '',
			  'failed_password_attempt_count' => '',
			  'email_administrator' => '',
			  'email_scheduling' => '',
			  'email_settlements' => '',
			  'email_patient_registration' => '',
			  'email_it_contact' => '',
			  'email_marketing_distribution_list' => '',
			  'dashboard_banner' => ''
			);
		}
		
		$this->_add_custom_script("/ckeditor_434/ckeditor.js");
		$this->_add_custom_script('/ckeditor_434/config.js');
		
		$params = array(
			'order' => array(
				array(
					'username' => 'ASC'
				)
			)
		);
		$data['users'] = $this->users->get_users($params);
		$this->_add_view('admin/portal_settings', 1, $data);
		$this->_render();
	}
	
	/*
	* Update portal_settings to DB
	*/
	public function save_portal_settings()
	{
		$data = $this->input->post(NULL, TRUE);
		$dashboard_banner = $this->input->post('dashboard_banner');
		$data['dashboard_banner'] = $dashboard_banner;
		$data['id'] = $data['view_id'];
		unset($data['view_id']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		
		$config['upload_path'] = MSHC_UPLOAD_FILE_PATH;
		$this->load->library('upload', $config);

		$ps_id = $this->portal_settings->update_portal_settings($data);

		if ($ps_id === FALSE) {
			$ps_id = $this->portal_settings->add_new_portal_settings($data);
			$data['new_ps'] = 'true';
		}
		if ($_FILES["file_name"]["name"]) {
			$file_src = 'portal_'.$_FILES["file_name"]["name"];
			if ( ! move_uploaded_file($_FILES["file_name"]["tmp_name"], MSHC_UPLOAD_FILE_PATH.'/'.$file_src)) {
				$data['error'] = "ERROR CANNOT UPLOAD FILE ".$_FILES["file_name"]["name"];
			} else {
				$data['logo'] = $file_src;
				$data['id'] = $ps_id;
				$this->portal_settings->update_portal_settings($data);
			}
		}
		
		if (isset($data['error'])) {
			$this->session->set_userdata('general_flash_message','{"type":"error","text":"'.$data['error'].'", "class": "wide"}');
		} else {
			$this->session->set_userdata('general_flash_message','{"type":"success","text":"Portal settings has been saved.", "class": "wide"}');
		}
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('Edit Portal Settings', 'portal', $activity_info);
		
		redirect(base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_SETTINGS_NAME);
		return;
	}

	public function show_users_notifs()
    {
        return;
        $this->db->select('*');
        $this->db->from('users u');
        $this->db->join('legal_users lu', 'lu.user_id = u.id');
        $this->db->join('legal_firms_users lfu', 'lfu.user_id = u.id');
        $this->db->join('legal_firms lf', 'lf.id = lfu.legal_firm_id');

        $query = $this->db->get();
        $result = $query->result_array();
        //echo '<pre>'.print_r($result, true).'</pre>';exit;

        $data= array();

        foreach ($result as $user)
        {
            if ($user['high_charges_notified'] || $user['pharmacy_notified'] || $user['disability_notified'] || $user['ptbwr_referral_notified'] || $user['consult_notified']
                || $user['outside_medical_record_notified'] || $user['pt_note_notified'] || $user['medical_report_notified'] || $user['case_discharge_notified']
                || $user['missed_appointments_notified']) {

                $data[] = array(
                    'username' => $user['username'],
                    'last_name' => $user['last_name'],
                    'first_name' => $user['first_name'],
                    'firm_name' => $user['name'],
                    'email' => $user['email'],
                    'missed_appointments_notified' => $user['missed_appointments_notified'] ? 'yes' : 'no',
                    'case_discharge_notified' => $user['case_discharge_notified'] ? 'yes' : 'no',
                    'medical_report_notified' => $user['medical_report_notified'] ? 'yes' : 'no',
                    'pt_note_notified' => $user['pt_note_notified'] ? 'yes' : 'no',
                    'outside_medical_record_notified' => $user['outside_medical_record_notified'] ? 'yes' : 'no',
                    'consult_notified' => $user['consult_notified'] ? 'yes' : 'no',
                    'ptbwr_referral_notified' => $user['ptbwr_referral_notified'] ? 'yes' : 'no',
                    'disability_notified' => $user['disability_notified'] ? 'yes' : 'no',
                    'pharmacy_notified' => $user['pharmacy_notified'] ? 'yes' : 'no',
                    'high_charges_notified' => $user['high_charges_notified'] ? 'yes' : 'no'
                );
            }
        }

        if (count($data)) {
            // Выводим HTTP-заголовки
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment;filename=export_users_notifs_'.time().'.csv');

            $output = fopen('php://output', 'w');

            $headers = array(
                'username',
                'last_name',
                'first_name',
                'firm_name',
                'email',
                'missed_appointments_notified',
                'case_discharge_notified',
                'medical_report_notified',
                'pt_note_notified',
                'outside_medical_record_notified',
                'consult_notified',
                'ptbwr_referral_notified',
                'disability_notified',
                'pharmacy_notified',
                'high_charges_notified'
            );

            if (count($headers)) {
                $headerData = array_map(function ($item) {
                    return iconv("UTF-8", "Windows-1251", $item);
                }, $headers);
                fputcsv($output, $headerData, ';');
            }

            foreach($data as $row)
            {
                $rowData = array_map(function ($item) {
                    return iconv("UTF-8", "Windows-1251", $item);
                }, $row);
                fputcsv($output, $rowData, ';');
            }

            fclose($output);

            exit;
        }
        //echo '<pre>'.print_r($result, true).'</pre>';
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */