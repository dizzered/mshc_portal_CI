<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MSHC_Controller 
{
	protected $documents_sort_column;
	
    public function __construct() 
    {
        parent::__construct();
	}
	
	/*
	* Check unique username
	*/
	public function check_unique_username()
	{
		$username = $this->input->post('username', TRUE);
		if ($username)
		{
			$unique = $this->users->check_unique_username($username);
			echo json_encode($unique);
		}
	}

	/*
	* Check unique email
	*/
	public function check_unique_email()
	{
		echo json_encode(TRUE);
		return;

		/**
        $email = $this->input->post('email', TRUE);
		$table = $this->input->post('table', TRUE);
		if (is_null($table) || $table == 'users') {
			if ($email) {
				$unique = $this->users->check_unique_email($email);
				echo json_encode($unique);
			}
		} else if ($table == 'marketers') {
			if ($email) {
				$unique = $this->marketers->check_unique_email($email);
				echo json_encode($unique);
			}
		}
        */
	}

	/*
	* Check unique firm name
	*/
	public function check_unique_firmname()
	{
		$firmname = $this->input->post('firmname', TRUE);
		$firm_id = $this->input->post('firm_id', TRUE);
		if ($firmname) {
			$unique = $this->firms->check_unique_firm_name($firmname, $firm_id);
			echo json_encode($unique);
		}		
	}
	
	/*
	* Add new user
	*/
	public function process_add_user()
	{
		$data = $this->input->post(NULL, TRUE);
		//print_r($data);return;
		//$permissions = $data['permissions'];
		$firms = get_array_value('firms',$data);
		if ($firms) unset($data['firms']);
		$is_primary = get_array_value('is_primary',$data);
		if ($is_primary) unset($data['is_primary']);
		$legal_user = array();
		switch ($data['role_id'])
		{
			case MSHC_AUTH_SYSTEM_ADMIN: 
				$data['view_portal_activity_logs_allowed'] = 1;
				$data['maintain_marketers_allowed'] = 1;
				$legal_user['maintain_clients_allowed'] = 1;
				$legal_user['maintain_firms_allowed'] = 1;
				$legal_user['maintain_attorneys_allowed'] = 1;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_FIRM_ADMIN: 
				$data['view_portal_activity_logs_allowed'] = 1;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 1;
				$legal_user['maintain_attorneys_allowed'] = 1;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_ATTORNEY: 
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_CASE_MANAGER: 
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			default:
				/*$data['view_portal_activity_logs_allowed'] = (int) $permissions['view_portal_activity_logs_allowed'];
				$data['maintain_marketers_allowed'] = (int) $permissions['maintain_marketers_allowed'];
				$legal_user['maintain_clients_allowed'] = (int) $permissions['maintain_clients_allowed'];
				$legal_user['maintain_firms_allowed'] = (int) $permissions['maintain_firms_allowed'];
				$legal_user['maintain_attorneys_allowed'] = (int) $permissions['maintain_attorneys_allowed'];
				$legal_user['view_cases_for_firm_allowed'] = (int) $permissions['view_cases_for_firm_allowed'];*/
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 0;				
				break;
		}
		
		//unset($data['permissions']);
		$notifications = $data['notifications'];
		unset($data['notifications']);
		$high_charges = $data['high_charges'];
		unset($data['high_charges']);
		
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$password = get_random_password();
		$data['password'] = $this->users->_secure_password($password);
		$data['last_password_changed_date'] = date('Y-m-d H:i:s');
		$data['failed_password_attempt_count'] = 0;		
		unset($data['view_id']);
		
		// Add new user in USERS table
		$user_id = $this->users->add_new_user($data);
		
		if ($user_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error adding new user. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add portal activity
		$activity_info = 'Username: '.$data['username'].';';
		$this->activity->add_activity_log('Add New User', 'both', $activity_info);
		
		$legal_user['user_id'] = $user_id;
		foreach($notifications as $key => $val)
		{
			$legal_user[$key] = (int) $val;
		}
		
		foreach($high_charges as $key => $val)
		{
			$legal_user[$key] = (int) $val;
		}
		
		// Add new user in LEGAL_USERS table
		if ($this->users->add_new_legal_user($legal_user) === FALSE) {
			$error['code'] = 401;
			$error['message'] = 'Error adding new legal user. Please contact us.<br /><br />Password for new user: '.$password.'<br /><br />
			Also, the password was sent to the specified e-mail address.';
			echo json_encode($error);
			return;			
		}	
		
		// Create new user's relations with firms and attorneys
		if ($firms) {
			$firms_batch = array();
			$attorneys_batch = array();

			foreach($firms as $firm_id => $firm_attorneys)
			{
				if ($firm_id == $is_primary) $this_primary = 1;
				else $this_primary = NULL;
				$firms_batch[] = array(
					'legal_firm_id' => $firm_id,
					'user_id' => $user_id,
					'is_primary' => $this_primary,
					'all_attorneys' => get_array_value('all_attorneys',$firm_attorneys)
				);

				/** @var array $attorneys */
				$attorneys = element('attorneys', $firm_attorneys);
				if ($attorneys) {
					foreach($attorneys as $attorney)
					{
						$attorneys_batch[] = array(
							'legal_atty_id' => $attorney,
							'user_id' => $user_id
						);
					}
				}
			}

			if (count($firms_batch)) $this->firms->add_user_firms($firms_batch);
			if (count($attorneys_batch)) $this->firms->add_user_attorneys($attorneys_batch);
		} else if ($is_primary) {
			$firms_batch[] = array(
				'legal_firm_id' => $is_primary,
				'user_id' => $user_id,
				'is_primary' => 1,
				'all_attorneys' => NULL
			);
			$this->firms->add_user_firms($firms_batch);
		}
		
		$result['code'] = 200;
		$result['message'] = 'User added successfully.<br /><br />Please remember this password: '.$password.'<br /><br />
		Also, the password was sent to the specified e-mail address.';
		
		$this->load->library('mshc_general');
		$mail_params['message'] = $this->load->view(
			'email/welcome-html', 
			array(
				'username' => $data['username'],
				'password' => $password
			), 
			TRUE
		);
		$mail_params['alt_message'] = $this->load->view(
			'email/welcome-txt', 
			array(
				'username' => $data['username'],
				'password' => $password
			), 
			TRUE
		);
		$mail_params['subject'] = 'MSHC Portal: New User Registration';
		$send = $this->mshc_general->send_new_password($data['email'], $mail_params);

		if (!$send) $result['error'] = 'Password not sended. Error occured.';
		echo json_encode($result);
	}	

	/*
	* Update user data
	*/
	public function process_update_user()
	{
		$data = $this->input->post(NULL, TRUE);
		if (! $data['view_id'])
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating user info. Please try again later.';
			echo json_encode($error);
			return;			
		}
		$user_id = $data['view_id'];
		unset($data['view_id']);		
		//$permissions = $data['permissions'];
		$firms = get_array_value('firms',$data);
		if ($firms) unset($data['firms']);
		$is_primary = get_array_value('is_primary',$data);
		if ($is_primary) unset($data['is_primary']);
		
		switch ($data['role_id']) {
			case MSHC_AUTH_SYSTEM_ADMIN: 
				$data['view_portal_activity_logs_allowed'] = 1;
				$data['maintain_marketers_allowed'] = 1;
				$legal_user['maintain_clients_allowed'] = 1;
				$legal_user['maintain_firms_allowed'] = 1;
				$legal_user['maintain_attorneys_allowed'] = 1;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_FIRM_ADMIN: 
				$data['view_portal_activity_logs_allowed'] = 1;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 1;
				$legal_user['maintain_attorneys_allowed'] = 1;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_ATTORNEY: 
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			case MSHC_AUTH_CASE_MANAGER: 
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 1;
				break;
			default:
				/*$data['view_portal_activity_logs_allowed'] = (int) $permissions['view_portal_activity_logs_allowed'];
				$data['maintain_marketers_allowed'] = (int) $permissions['maintain_marketers_allowed'];
				$legal_user['maintain_clients_allowed'] = (int) $permissions['maintain_clients_allowed'];
				$legal_user['maintain_firms_allowed'] = (int) $permissions['maintain_firms_allowed'];
				$legal_user['maintain_attorneys_allowed'] = (int) $permissions['maintain_attorneys_allowed'];
				$legal_user['view_cases_for_firm_allowed'] = (int) $permissions['view_cases_for_firm_allowed'];*/
				$data['view_portal_activity_logs_allowed'] = 0;
				$data['maintain_marketers_allowed'] = 0;
				$legal_user['maintain_clients_allowed'] = 0;
				$legal_user['maintain_firms_allowed'] = 0;
				$legal_user['maintain_attorneys_allowed'] = 0;
				$legal_user['view_cases_for_firm_allowed'] = 0;				
				break;
		}
		
		//unset($data['permissions']);
		$notifications = $data['notifications'];
		unset($data['notifications']);
		$high_charges = $data['high_charges'];
		unset($data['high_charges']);
		
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];		
		
		// Update user data in USERS table
		if ($this->users->update_user($user_id, $data) === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating user info. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add portal activity
		$activity_info = 'Username: '.$data['username'].';';
		$this->activity->add_activity_log('Edit User', 'both', $activity_info);
		
		$legal_user['user_id'] = $user_id;
		foreach($notifications as $key => $val)
		{
			$legal_user[$key] = (int) $val;
		}
		
		foreach($high_charges as $key => $val)
		{
			$legal_user[$key] = (int) $val;
		}
		
		// Update user info in LEGAL_USERS table
		$legal_user_data = $this->users->get_legal_user($user_id);
		
		if ($legal_user_data) {
			if ($this->users->update_legal_user($user_id, $legal_user) === FALSE) {
				$error['code'] = 401;
				$error['message'] = 'Error updating legal user. Please contact us.';
				echo json_encode($error);
				return;			
			}
		} else {
			if ($this->users->add_new_legal_user($legal_user) === FALSE) {
				$error['code'] = 401;
				$error['message'] = 'Error adding new legal user. Please contact us.<br /><br />
				Also, the password was sent to the specified e-mail address.';
				echo json_encode($error);
				return;			
			}
		}
		
		// Clear old user's relations with firms and attorneys		
		$this->firms->delete_user_firms($user_id);
			
		$this->firms->delete_user_attorneys($user_id);
		
		// Create new user's relations with firms and attorneys
		if ($firms)
		{
			$firms_batch = array();
			$attorneys_batch = array();
			foreach($firms as $firm_id => $firm_attorneys)
			{
				if ($firm_id == $is_primary) $this_primary = 1;
				else $this_primary = NULL;
				$firms_batch[] = array(
					'legal_firm_id' => $firm_id,
					'user_id' => $user_id,
					'is_primary' => $this_primary,
					'all_attorneys' => $firm_attorneys['all_attorneys']
				);

				/** @var array $attorneys */
				$attorneys = element('attorneys', $firm_attorneys);
				if ($attorneys) {
					foreach($attorneys as $attorney)
					{
						$attorneys_batch[] = array(
							'legal_atty_id' => $attorney,
							'user_id' => $user_id
						);
					}
				}
			}
			if (count($firms_batch)) $this->firms->add_user_firms($firms_batch);
			if (count($attorneys_batch)) $this->firms->add_user_attorneys($attorneys_batch);
		}
		
		$result['code'] = 200;
		$result['message'] = 'User updated successfully.';	
		echo json_encode($result);
	}	

	/*
	* Update user account data
	*/
	public function process_update_user_account()
	{
		$data = $this->input->post(NULL, TRUE);

		if (! $data['view_id'])
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating user info. Please try again later.';
			echo json_encode($error);
			return;			
		}
		
		/*if ($this->users->check_email_for_user($data['view_id'], $data['email']) == FALSE)
		{
			$error['code'] = 403;
			$error['message'] = 'Email already exists. Please enter another.';
			echo json_encode($error);
			return;				
		}*/
		
		$user_id = $data['view_id'];
		unset($data['view_id']);		
		
		$notifications = $data['notifications'];
		unset($data['notifications']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];		
		
		// Update user data in USERS table
		if ($this->users->update_user($user_id, $data) === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating user info. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add portal activity
		$activity_info = 'Username: '.$data['username'].';';
		$this->activity->add_activity_log('Update User Account', 'both', $activity_info);
		
		$legal_user['user_id'] = $user_id;
		foreach($notifications as $key => $val)
		{
			$legal_user[$key] = (int) $val;
		}
		
		// Update user info in LEGAL_USERS table
		if ($this->users->update_legal_user($user_id, $legal_user) === FALSE)
		{
			$error['code'] = 401;
			$error['message'] = 'Error updating legal user. Please contact us.';
			echo json_encode($error);
			return;			
		}
				
		$result['code'] = 200;
		$result['message'] = 'User updated successfully.';	
		echo json_encode($result);
	}	

	/*
	* Delete user and relations
	*/
	public function process_delete_user()
	{
		$user_id = $this->input->get_post('id', TRUE);
		if ($user_id)
		{
			/*if (!$this->users->is_user_editable($user_id))
			{
				$result['code'] = 500;
				$result['message'] = 'You have not permission to perform this action.';
				echo json_encode($result);
				return;		
			}*/
			
			$params = array(
				'from' => array(
					$this->users_table_name =>  'u'
				),
				'where' => array(
					'u.id' => $user_id
				),
				'join' => array(
					array(
						'table' => $this->legal_users_table_name.' AS lu',
						'condition' => 'lu.user_id = u.id',
						'type' => 'left'
					)
				)
			);
			$user_data = $this->users->get_users($params);
			$this->users->delete_user($user_id);
			
			/*$this->firms->delete_user_firms($user_id);
			
			$this->firms->delete_user_attorneys($user_id);*/

			// Add portal activity
			$activity_info = 'Username: '.$user_data[0]['username'].';';
			$this->activity->add_activity_log('Delete User', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'User delete successfully.';
			echo json_encode($result);
			return;
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting user. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Change user password // prior check for regular expression
	*/
	public function change_user_password()
	{
		$new_password = $this->input->get_post('password', TRUE);
		$user_id = $this->input->get_post('user_id', TRUE);
		$username = $this->input->get_post('username', TRUE);
		$email = $this->input->get_post('email', TRUE);
		if (preg_match($this->config->item('password_regexp', 'auth'), $new_password))
		{
			$this->users->change_user_password($user_id, $new_password);
			
			$this->load->library('mshc_general');
			
			$mail_params['message'] = $this->load->view(
				'email/change_password-html', 
				array(
					'username' => $username,
					'password' => $new_password
				), 
				TRUE
			);
			$mail_params['alt_message'] = $this->load->view(
				'email/change_password-txt', 
				array(
					'username' => $username,
					'password' => $new_password
				), 
				TRUE
			);
			$mail_params['subject'] = 'MSHC Portal: Change User Password';
			$this->mshc_general->send_new_password($email, $mail_params);

			// Add portal activity
			$activity_info = 'Username: '.$username.';';
			$this->activity->add_activity_log('Change User Password', 'portal', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'Password changed successfully.<br /><br />New password was sent to the specified e-mail address.';
			echo json_encode($result);
			return;
		}
		else
		{
			$result['code'] = 400;
			$result['message'] = 'Password is too weak.';
			echo json_encode($result);
			return;
		}
	}

	/*
	* Reset user password for randomly generated
	*/
	public function reset_user_password()
	{
		$user_id = $this->input->get_post('user_id', TRUE);
		$username = $this->input->get_post('username', TRUE);
		$email = $this->input->get_post('email', TRUE);
		$new_password = get_random_password();
		$this->users->change_user_password($user_id, $new_password);
			
		$this->load->library('mshc_general');
		$mail_params['message'] = $this->load->view(
			'email/reset_password-html', 
			array(
				'username' => $username,
				'password' => $new_password
			), 
			TRUE
		);
		$mail_params['alt_message'] = $this->load->view(
			'email/reset_password-txt', 
			array(
				'username' => $username,
				'password' => $new_password
			), 
			TRUE
		);
		$mail_params['subject'] = 'MSHC Portal: New User Password';
		$this->mshc_general->send_new_password($email, $mail_params);
		
		// Add portal activity
		$activity_info = 'Username: '.$username.';';
		$this->activity->add_activity_log('Reset User Password', 'portal', $activity_info);
			
		$result['code'] = 200;
		$result['message'] = 'Password reseted successfully.<br /><br />
		New password '.$new_password.' was sent to the specified e-mail address.';
		echo json_encode($result);
		return;
	}
	
	/*
	* Add new firm
	*/
	public function process_add_firm()
	{
		$data = $this->input->post(NULL, TRUE);
		unset($data['view_id']);
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$firm_id = $this->firms->add_new_firm($data);

		if ($firm_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error adding new firm. Please try again later.';
			echo json_encode($error);
			return;
		}
		$firms_batch[] = array(
			'legal_firm_id' => $firm_id,
			'user_id' => $this->_user['user_id'],
			'is_primary' => NULL,
			'all_attorneys' => NULL
		);
		$this->firms->add_user_firms($firms_batch);

		// Add portal activity
		$activity_info = 'Firm Name: '.$data['name'].';';
		$this->activity->add_activity_log('Add New Firm', 'both', $activity_info);

		$result['code'] = 200;
		$result['message'] = 'Firm added successfully.<br /><br />Now you can continue to add attorney.';
		$result['firm_id'] = $firm_id;
		echo json_encode($result);
		
	}

	/*
	* Update firm
	*/
	public function process_update_firm()
	{
		$data = $this->input->post(NULL, TRUE);
		$data['id'] = $data['view_id'];
		unset($data['view_id']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$firm_id = $this->firms->update_firm($data);

		if ($firm_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating firm. Please try again later.';
			echo json_encode($error);
			return;
		}

		// Add portal activity
		$activity_info = 'Firm Name: '.$data['name'].';';
		$this->activity->add_activity_log('Edit Firm Info', 'both', $activity_info);

		$result['code'] = 200;
		$result['message'] = 'Firm updated successfully.';
		$result['firm_id'] = $firm_id;
		echo json_encode($result);
		
	}

	/*
	* Delete firm, attorneys and relations
	*/
	public function process_delete_firm()
	{
		$firm_id = $this->input->post('firm_id', TRUE);
		
		if ($firm_id)
		{
			// Delete firm
			$params = array(
				'from' => array(
					$this->firms_table_name =>  'f'
				),
				'where' => array(
					'f.id' => $firm_id
				)
			);
			$firm_data = $this->firms->get_firms($params);
			$this->firms->delete_firm($firm_id);

			// Add portal activity
			$activity_info = 'Firm Name: '.$firm_data[0]['name'].';';
			$this->activity->add_activity_log('Delete Firm', 'both', $activity_info);

			$error['code'] = 200;
			$error['message'] = 'Firm deleted succsesfully.';
			echo json_encode($error);
			return;		
		}
		
		$error['code'] = 400;
		$error['message'] = 'Error deleting firm. Please try again later.';
		echo json_encode($error);
		return;		
	}
	
	/*
	* Get users table for admin/users
	*/
	public function get_users_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		/*$use_cache = FALSE;
		if (!$search_data) {
			$this->load->library('user_agent');
			$referrer = $this->agent->referrer();
			if ($referrer) {
				if (strpos($referrer, 'admin') === FALSE) {
					$this->load->driver('cache');
					$cache_file = 'dbcut'.$this->_user['user_id'];
					$cache = $this->cache->file->get($cache_file);
					$use_cache = TRUE;
				}
			}
			
			if ($use_cache) {
				if ($cache) {
					print_r(json_encode($cache));
					return;
				}
			}
		}*/
		
		$data = $this->input->get(NULL, TRUE);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$this->load->library('mshc_general');
		$this->sortingTable = $this->get_order_by_post($data);
		
		$params = $this->mshc_general->get_users_params($search_data, $this->sortingTable, $data);
		
		$result = array();
		
		// Get total users count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_users = count($this->users->get_users($total_params, FALSE));

		// Get users
		$users = $this->users->get_users($params, FALSE);

		if (count($users)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_users;
			$result['Records'] = $users;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

        $result['readOnly'] = $this->_user['role_id'] == MSHC_AUTH_BILLER;
		
		/*if ($use_cache) {
			$this->cache->file->save($cache_file, $result, 1800);
		}*/

		print_r(json_encode($result));
	}
		
	/*
	* Get user data for edit dialog
	*/
	public function process_get_user_data()
	{
		$user_id = $this->input->get_post('id', TRUE);
		if ($user_id)
		{
			$params = array(
				'from' => array(
					$this->users_table_name =>  'u'
				),
				'where' => array(
					'u.id' => $user_id
				),
				'join' => array(
					array(
						'table' => $this->legal_users_table_name.' AS lu',
						'condition' => 'lu.user_id = u.id',
						'type' => 'left'
					)
				)
			);
			$user_data = $this->users->get_users($params);
			if (!count($user_data))
			{
				$result['code'] = 400;
				$result['message'] = 'User not found.';
				echo json_encode($result);
				return;		
			}
			
			/*if (!$this->users->is_user_editable(0, $user_data[0]['role_id'])) 
			{
				$result['code'] = 500;
				$result['message'] = 'You have not permission to perform this action.';
				echo json_encode($result);
				return;		
			}*/
			
			$user_firms = $this->firms->get_firms_attorneys_by_user_id($user_id);
			//echo '<pre>'.print_r($user_firms, true).'</pre>';
			
			// Add portal activity
			$activity_info = 'Username: '.$user_data[0]['username'].';';
			$this->activity->add_activity_log('View User Info', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['user_data'] = $user_data;
			$result['user_firms'] = $user_firms;
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting user info. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Get activity log table for admin
	*/
	public function get_activity_log_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
        $this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$this->sortingTable = $this->get_order_by_post($data);
		$order = array();

		foreach ($this->sortingTable as $field => $type)
		{
			switch ($field) {
				case 'username': $order[] = array('username' => $type);break;
				case 'event': $order[] = array('event' => $type);break;
                case 'firm_name': $order[] = array('firm_name' => $type);break;
				default: $order[] = array('pal.'.$field => $type);break;
			}
		}
		
		$params = array(
			'fields' => array(
				'DATE_FORMAT(pal.created,\'%b %e, %Y, %k:%i\')' => 'created',
				'IF( u.first_name = \'\', IF( u.last_name = \'\', u.username, u.last_name ) , IF( u.last_name = \'\', u.first_name, CONCAT( u.last_name, \', \', u.first_name ) ) )' => 'username', 
				'IF( pal.portal_activity_id IS NULL , pa.name, pa.name )' => 'event', 
				'pal.info' => '',
                'lf_fn.name' => 'firm_name'
			),
			'from' => array(
				$this->activity_logs_table_name => 'pal',
				$this->users_table_name => 'u',
				$this->activities_table_name => 'pa'
			),
			'join' => array(
				array(
                    'table' => $this->legal_firms_users_table_name.' AS lfu_fn',
                    'condition' => 'lfu_fn.user_id = u.id AND lfu_fn.is_primary = 1',
                    'type' => 'left'
                ),
                array(
                    'table' => $this->legal_firms_table_name.' AS lf_fn',
                    'condition' => 'lf_fn.id = lfu_fn.legal_firm_id',
                    'type' => 'left'
                )
			),
			'order' => $order,
			'group' => array (
				'pal.id'
			),
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
			'where' => array (
				'pal.user_id = u.id' => '',
				'( pal.portal_activity_id IS NOT NULL	AND pal.portal_activity_id = pa.id )' => '',
			)
		);

		if (is_array($search_data)) {
            if ($search_data['event_name'] != '') {
                $params['where'] = array (
                    'pal.user_id = u.id' => '',
                    '( pal.portal_activity_id IS NOT NULL AND pal.portal_activity_id = pa.id AND pa.name = \''.$search_data['event_name'].'\' )' => ''
                );
            }
            if ($search_data['user_id'] != 0) {
                $params['where']['u.id'] = $search_data['user_id'];
            }
		}
		
		if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
			$this->db->select('olfu.user_id');
			$this->db->from($this->legal_firms_users_table_name.' AS lfu');
			$this->db->join($this->legal_firms_users_table_name.' AS olfu','olfu.legal_firm_id = lfu.legal_firm_id');
			$this->db->join($this->users_table_name.' AS u','u.id = olfu.user_id AND u.role_id != "'.MSHC_AUTH_SYSTEM_ADMIN.'"');
			$this->db->where('lfu.user_id', $this->_user['user_id']);
			$this->db->group_by('olfu.user_id');
			$query = $this->db->get();
			$users = array($this->_user['user_id']);

			if ($query->num_rows()) {
				$users = array();
				$result = $query->result();
				foreach ($result as $user)
				{
					$users[] = $user->user_id;
				}
			}
			$params['where_in'] = array(
				'u.id' => $users
			);
		}
		
		$result = array();

		// Get total users count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_activity_log = $this->activity->get_total_activities($total_params, FALSE);

        // Get activities
		$activity_logs = $this->activity->get_activities($params, FALSE);

		if (count($activity_logs)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_activity_log;
			$result['Records'] = $activity_logs;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}
	
	
	/*
	* Add new marketer
	*/
	public function process_add_marketer()
	{
		$data = $this->input->post(NULL, TRUE);
		unset($data['view_id']);
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$marketer_id = $this->marketers->add_new_marketer($data);

		if ($marketer_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error adding new marketer. Please try again later.';
			echo json_encode($error);
			return;
		}

		// Add portal activity
		$activity_info = 'Marketer Name: '.$data['last_name'].($data['last_name'] ? ', ' : '').$data['first_name'].';';
		$this->activity->add_activity_log('Add New Marketer', 'portal', $activity_info);

		$result['code'] = 200;
		$result['message'] = 'Marketer added successfully.';
		$result['marketer_id'] = $marketer_id;
		echo json_encode($result);
		
	}

	/*
	* Update marketer
	*/
	public function process_update_marketer()
	{
		$data = $this->input->post(NULL, TRUE);
		$data['id'] = $data['view_id'];
		unset($data['view_id']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$marketer_id = $this->marketers->update_marketer($data);

		if ($marketer_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating marketer. Please try again later.';
			echo json_encode($error);
			return;
		}

		// Add portal activity
		$activity_info = 'Marketer Name: '.$data['last_name'].($data['last_name'] ? ', ' : '').$data['first_name'].';';
		$this->activity->add_activity_log('Edit Marketer Info', 'portal', $activity_info);

		$result['code'] = 200;
		$result['message'] = 'Marketer updated successfully.';
		$result['marketer_id'] = $marketer_id;
		echo json_encode($result);
		
	}


	/*
	* Delete marketer from db
	*/
	public function process_delete_marketer()
	{
		$marketer_id = $this->input->get_post('id', TRUE);
		if ($marketer_id)
		{
			$params = array(
				'from' => array(
					$this->marketers_table_name =>  'm'
				),
				'where' => array(
					'm.id' => $marketer_id
				)
			);
			$marketer_data = $this->marketers->get_marketers($params);
			$this->marketers->delete_marketer($marketer_id);

			// Add portal activity
			$activity_info = 'Marketer Name: '.$marketer_data[0]['last_name'].
				($marketer_data[0]['last_name'] ? ', ' : '').$marketer_data[0]['first_name'].';';
			$this->activity->add_activity_log('Delete Marketer', 'portal', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'Marketer delete successfully.';
			echo json_encode($result);
			return;
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting marketer. Please try again later.';
		echo json_encode($result);		
	}
	
	
	/*
	* Get marketers table for admin
	*/
	public function get_marketers_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$this->load->library('mshc_general');
		$this->sortingTable = $this->get_order_by_post($data);
		$params = $this->mshc_general->get_marketers_params($search_data, $this->sortingTable, $data);
					
		$result = array();

		// Get total marketers count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_marketers = count($this->marketers->get_marketers($total_params, FALSE));

		// Get marketers
		$marketers = $this->marketers->get_marketers($params, FALSE);

		if (count($marketers)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_marketers;
			$result['Records'] = $marketers;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}	
	
	/*
	* Get marketer data for edit dialog
	*/
	public function process_get_marketer_data()
	{
		$marketer_id = $this->input->get_post('id', TRUE);
		if ($marketer_id)
		{
			$params = array(
				'from' => array(
					$this->marketers_table_name =>  ''
				),
				'where' => array(
					'id' => $marketer_id
				)
			);
			$marketer_data = $this->marketers->get_marketers($params);
			if (!count($marketer_data))
			{
				$result['code'] = 400;
				$result['message'] = 'Marketer not found.';
				echo json_encode($result);
				return;		
			}

			// Add portal activity
			$activity_info = 'Marketer Name: '.$marketer_data[0]['last_name'].
				($marketer_data[0]['last_name'] ? ', ' : '').$marketer_data[0]['first_name'].';';
			$this->activity->add_activity_log('View Marketer Info', 'portal', $activity_info);
			
			$result['code'] = 200;
			$result['marketer_data'] = $marketer_data;
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting marketer info. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Get forms table for admin
	*/
	public function get_forms_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$this->load->library('mshc_general');
		$this->sortingTable = $this->get_order_by_post($data);
		$params = $this->mshc_general->get_forms_params($search_data, $this->sortingTable, $data);
		$result = array();

		// Get total forms count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_forms = count($this->forms->get_forms($total_params, FALSE));

		// Get forms
		$forms = $this->forms->get_forms($params, FALSE);

		if (count($forms)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_forms;
			$result['Records'] = $forms;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}
	
	
	/*
	* Get forms data for edit dialog
	*/
	public function process_get_forms_data()
	{
		$form_id = $this->input->get_post('id', TRUE);
		if ($form_id)
		{
			$params = array(
				'from' => array(
					$this->forms_table_name =>  ''
				),
				'where' => array(
					'id' => $form_id
				)
			);
			$form_data = $this->forms->get_forms($params);
			if (!count($form_data))
			{
				$result['code'] = 400;
				$result['message'] = 'Forms not found.';
				echo json_encode($result);
				return;		
			}

			// Add portal activity
			$activity_info = 'Form Name: '.$form_data[0]['name'].';';
			$this->activity->add_activity_log('View Form Info', 'portal', $activity_info);
			
			$result['code'] = 200;
			$result['form_data'] = $form_data;
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting form info. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Delete form from db
	*/
	public function process_delete_form()
	{
		$form_id = $this->input->get_post('id', TRUE);
		if ($form_id)
		{
			$params = array(
				'from' => array(
					$this->forms_table_name =>  ''
				),
				'where' => array(
					'id' => $form_id
				)
			);
			$form_data = $this->forms->get_forms($params);
			$this->forms->delete_form($form_id);

			// Add portal activity
			$activity_info = 'Form Name: '.$form_data[0]['name'].';';
			$this->activity->add_activity_log('Delete Form', 'portal', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'Form delete successfully.';
			echo json_encode($result);
			return;
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting form. Please try again later.';
		echo json_encode($result);		
	}

	/*
	* Delete file of form from db and file
	*/
	public function process_delete_file_form()
	{
		$form_id = $this->input->get_post('id', TRUE);
		if ($form_id)
		{
			
			$forms = $this->forms->get_form_by_id($form_id);
			if (isset($forms) and count($forms)) {
				unlink(MSHC_UPLOAD_FILE_PATH.'/'.$forms['file_name']);
				$forms['file_name'] = '';
				$forms['modified'] = date('Y-m-d H:i:s');
				$forms['modified_by'] = $this->_user['user_id'];
				$form_id = $this->forms->update_form($forms);
	
				if ($form_id) {
					// Add portal activity
					$activity_info = 'Form Filename: '.$forms['file_name'].';';
					$this->activity->add_activity_log('Deleting File From Form', 'portal', $activity_info);
					
					$result['code'] = 200;
					$result['message'] = 'File Uploaded delete successfully.';
					echo json_encode($result);
					return;
				}
			}
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting file uploaded form. Please try again later.';
		echo json_encode($result);		
	}

	/*
	* Delete logo of portal_settings from db and file
	*/
	public function process_delete_logo_portal_settings()
	{
		$ps_id = $this->input->get_post('id', TRUE);
		if ($ps_id)
		{
			
			$portal_settings = $this->portal_settings->get_portal_settings_by_id($ps_id);
			if (isset($portal_settings) and count($portal_settings)) {
				unlink(MSHC_UPLOAD_FILE_PATH.'/'.$portal_settings['logo']);
				$portal_settings['logo'] = '';
				$portal_settings['modified'] = date('Y-m-d H:i:s');
				$portal_settings['modified_by'] = $this->_user['user_id'];
				$ps_id = $this->portal_settings->update_portal_settings($portal_settings);
	
				if ($ps_id) {
					// Add portal activity
					$activity_info = 'Filename: '.$portal_settings['logo'].';';
					$this->activity->add_activity_log('Deleting Logo From Portal Settings', 'portal', $activity_info);
					
					$result['code'] = 200;
					$result['message'] = 'Logo delete successfully.';
					echo json_encode($result);
					return;
				}
			}
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting logo of portal_settings. Please try again later.';
		echo json_encode($result);		
	}

	/*
	* Add new contact
	*/
	public function process_add_contact()
	{
		$data = $this->input->post(NULL, TRUE);
		$data['created'] = date('Y-m-d H:i:s');
		$contact_id = $this->contacts->add_new_contact($data);

		if ($contact_id === FALSE) {
			$error['code'] = 400;
			$error['message'] = 'Error adding new contact. Please try again later.';
			echo json_encode($error);
			return;
		}

		$result['code'] = 200;
		$result['message'] = 'Contact added successfully.';
		$result['contact_id'] = $contact_id;
		echo json_encode($result);
	}
		
	/*
	* Add attorney
	*/
	public function process_add_attorney()
	{
		$data = $this->input->post(NULL, TRUE);
		unset($data['atty_view_id']);
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$ext_attys = get_array_value('assigned', $data);
		unset($data['assigned']);
		$atty_id = $this->firms->add_attorney($data);
		
		// Add portal activity
		$params = array(
			'from' => array(
				$this->firms_table_name =>  'f'
			),
			'where' => array(
				'f.id' => $data['legal_firm_id']
			)
		);
		$firm_data = $this->firms->get_firms($params);
		
		$activity_info = 'Attorney Name: '.$data['last_name'].
			($data['last_name'] ? ', ' : '').$data['first_name'].'; '.
			'Firm Name: '.$firm_data[0]['name'].';';
		$this->activity->add_activity_log('Add New Attorney', 'both', $activity_info);
		
		if ($atty_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error adding new attorney. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add link with external DBs
		if (count($ext_attys))
		{
			$this->firms->add_ext_attorneys($atty_id, $ext_attys);
		}
		
		
		// Link atty with necessary users
		$users = $this->firms->get_users_by_firm_id($data['legal_firm_id']);
		if ($users)
		{
			$attorneys_batch = array();
			foreach($users as $user)
			{
				$attorneys_batch[] = array(
					'user_id' => $user->user_id,
					'legal_atty_id' => $atty_id
				);
			}
			
			$this->firms->add_user_attorneys($attorneys_batch);
		}
		
		$result['code'] = 200;
		$result['message'] = 'Attorney added successfully.';
		$result['atty_id'] = $atty_id;
		echo json_encode($result);
	}

	/*
	* Update attorney
	*/
	public function process_update_attorney()
	{
		$data = $this->input->post(NULL, TRUE);
		$atty_id = $data['atty_view_id'];
		unset($data['atty_view_id']);
		$legal_firm_id = $data['legal_firm_id'];
		unset($data['legal_firm_id']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$ext_attys = get_array_value('assigned', $data);
		unset($data['assigned']);		
		$update = $this->firms->update_attorney($atty_id, $data);
		
		if ($update === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating attorney info. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add portal activity
		$params = array(
			'from' => array(
				$this->firms_table_name =>  'f'
			),
			'where' => array(
				'f.id' => $legal_firm_id
			)
		);
		$firm_data = $this->firms->get_firms($params);
		
		$activity_info = 'Attorney Name: '.$data['last_name'].
			($data['last_name'] ? ', ' : '').$data['first_name'].'; '.
			'Firm Name: '.$firm_data[0]['name'].';';
		$this->activity->add_activity_log('Edit Attorney', 'both', $activity_info);

		// Add link with external DBs
		$this->firms->add_ext_attorneys($atty_id, $ext_attys);
		
		$result['code'] = 200;
		$result['message'] = 'Attorney updated successfully.';
		$result['atty_id'] = $atty_id;
		echo json_encode($result);
	}

	/*
	* Delete attorney and relations
	*/
	public function process_delete_attorney()
	{
		$atty_id = $this->input->post('atty_id', TRUE);
		
		if ($atty_id)
		{
			// Delete attorney
			$params = array(
				'fields' => array(
					'la.*' => '',
					'lf.name' => 'firm_name'
				),
				'from' => array(
					$this->legal_attorneys_table_name => 'la'
				),
				'join' => array(
					array(
						'table' => $this->legal_firms_table_name.' AS lf',
						'condition' => 'lf.id = la.legal_firm_id'
					)
				),
				'where' => array(
					'la.id' => $atty_id
				)
			);
			$atty_data = $this->firms->get_attorneys($params);
			$this->firms->delete_attorney($atty_id);

			// Add portal activity
			$activity_info = 'Attorney Name: '.$atty_data[0]['last_name'].
				($atty_data[0]['last_name'] ? ', ' : '').$atty_data[0]['first_name'].'; '.
				'Firm Name: '.$atty_data[0]['firm_name'].';';
			$this->activity->add_activity_log('Delete Attorney', 'both', $activity_info);

			$error['code'] = 200;
			$error['message'] = 'Attorney deleted succsesfully.';
			echo json_encode($error);
			return;		
		}
		
		$error['code'] = 400;
		$error['message'] = 'Error deleting attorney. Please try again later.';
		echo json_encode($error);
		return;		
	}

	/*
	* Get attorney data for edit dialog
	*/
	public function process_get_attorney_data()
	{
		$atty_id = $this->input->get_post('atty_id', TRUE);
		if ($atty_id) {
			$params = array(
				'fields' => array(
					'la.*' => '',
					'lf.name' => 'firm_name'
				),
				'from' => array(
					$this->legal_attorneys_table_name => 'la'
				),
				'join' => array(
					array(
						'table' => $this->legal_firms_table_name.' AS lf',
						'condition' => 'lf.id = la.legal_firm_id'
					)
				),
				'where' => array(
					'la.id' => $atty_id
				)
			);
			$atty_data = $this->firms->get_attorneys($params);
			if (!count($atty_data)) {
				$result['code'] = 400;
				$result['message'] = 'Attorney not found.';
				echo json_encode($result);
				return;		
			}

			// Add portal activity
			$activity_info = 'Attorney Name: '.$atty_data[0]['last_name'].
				($atty_data[0]['last_name'] ? ', ' : '').$atty_data[0]['first_name'].'; '.
				'Firm Name: '.$atty_data[0]['firm_name'].';';
			$this->activity->add_activity_log('View Attorney Info', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['atty_data'] = $atty_data;
			$result['assigned_attys'] = $this->firms->get_assigned_attorneys($atty_id);
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting attorney info. Please try again later.';
		echo json_encode($result);		
	}

	/*
	* Get clients table for admin
	*/
	public function get_clients_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$this->load->library('mshc_general');
		$this->sortingTable = $this->get_order_by_post($data);
		$params = $this->mshc_general->get_clients_params($search_data, $this->sortingTable, $data);
		$result = array();

		// Get total clients count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_clients = count($this->clients->get_clients($total_params, FALSE));

		// Get marketers
		$clients = $this->clients->get_clients($params, FALSE);

		if (count($clients)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_clients;
			$result['Records'] = $clients;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}
	
	/*
	* Add new client
	*/
	public function process_add_client()
	{
		$data = $this->input->post(NULL, TRUE);
		unset($data['view_id']);
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$client_id = $this->clients->add_new_client($data);

		if ($client_id === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error adding new client. Please try again later.';
			echo json_encode($error);
			return;
		}

		// Add portal activity
		$activity_info = 'Client Name: '.$data['name'].';';
		$this->activity->add_activity_log('Add New Client', 'both', $activity_info);

		$result['code'] = 200;
		$result['message'] = 'Client added successfully.<br /><br />Now you can continue to add practices.';
		$result['client_id'] = $client_id;
		echo json_encode($result);
		
	}

	/*
	* Update client
	*/
	public function process_update_client()
	{
		$data = $this->input->post(NULL, TRUE);
		$client_id = $data['view_id'];
		unset($data['view_id']);
		$data['modified'] = date('Y-m-d H:i:s');
		$data['modified_by'] = $this->_user['user_id'];
		$update = $this->clients->update_client($client_id, $data);

		if ($update === FALSE)
		{
			$error['code'] = 400;
			$error['message'] = 'Error updating client info. Please try again later.';
			echo json_encode($error);
			return;
		}
		
		// Add portal activity
		$activity_info = 'Client Name: '.$data['name'].';';
		$this->activity->add_activity_log('Edit Client Info', 'both', $activity_info);
		
		$result['code'] = 200;
		$result['message'] = 'Client updated successfully.';
		$result['client_id'] = $client_id;
		echo json_encode($result);
	}

	/*
	* Delete client and relations
	*/
	public function process_delete_client()
	{
		$client_id = $this->input->post('id', TRUE);
		
		if ($client_id)
		{
			// Delete firm
			$params = array(
				'fields' => array(
					'name' => ''
				),
				'where' => array(
					'id' => $client_id
				)
			);
			$client_data = $this->clients->get_clients($params);
			$this->clients->delete_client($client_id);

			// Add portal activity
			$activity_info = 'Client Name: '.$client_data[0]['name'].';';
			$this->activity->add_activity_log('Delete Client', 'both', $activity_info);

			$error['code'] = 200;
			$error['message'] = 'Client deleted succsesfully.';
			echo json_encode($error);
			return;		
		}
		
		$error['code'] = 400;
		$error['message'] = 'Error deleting client. Please try again later.';
		echo json_encode($error);
		return;		
	}

	/*
	* Get client data for edit dialog
	*/
	public function process_get_client_data()
	{
		$client_id = $this->input->get_post('id', TRUE);
		if ($client_id)
		{
			$params = array(
				'fields' => array(
					'name' => ''
				),
				'where' => array(
					'id' => $client_id
				)
			);
			$client_data = $this->clients->get_clients($params);
			if (!count($client_data))
			{
				$result['code'] = 400;
				$result['message'] = 'Client not found.';
				echo json_encode($result);
				return;		
			}

			// Add portal activity
			$activity_info = 'Client Name: '.$client_data[0]['name'].';';
			$this->activity->add_activity_log('View Client Info', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['client_data'] = $client_data;
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting client info. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Add new fin group
	*/
	public function process_add_fin_group()
	{
		$name = $this->input->get_post('name', TRUE);
		if (!empty($name)) 
		{
			$params = array(
				'where' => array(
					'name' => $name
				)
			);
			$fin_grp = $this->clients->get_fin_groups($params);
			
			if (count($fin_grp))
			{
				$result['code'] = 201;
				$result['message'] = 'Existing financial group added.';
				$result['fin_grp_id'] = $fin_grp[0]['id'];
				echo json_encode($result);
				return;
			}
			
			$data['name'] = $name;
			$fin_grp_id = $this->clients->add_fin_group($data);

			if ($fin_grp_id === FALSE)
			{
				$error['code'] = 400;
				$error['message'] = 'Error adding new financial group. Please try again later.';
				echo json_encode($error);
				return;
			}

			// Add portal activity
			$activity_info = 'Group Name: '.$name.';';
			$this->activity->add_activity_log('Add Financial Group', 'both', $activity_info);
					
			$result['code'] = 200;
			$result['message'] = 'Financial group added successfully.';
			$result['fin_grp_id'] = $fin_grp_id;
			echo json_encode($result);
			return;
		}
		$error['code'] = 400;
		$error['message'] = 'Error adding new financial group. Please try again later.';
		echo json_encode($error);
	} // process_add_fin_group
	
	/*
	* Add new practice
	*/
	public function process_add_practice()
	{
		$data = $this->input->post(NULL, TRUE);
		$practice_data = get_array_value('form_data', $data);
		$locations = get_array_value('locations', $data);
		$fin_groups_data = get_array_value('fin_groups', $data);
		$appt_reason_data = get_array_value('appt_reasons', $data);
		$error = '';
		
		// Add new practice
		if ($practice_data['client_id'])
		{
			$data = array(
				'name' => $practice_data['practice_name'],
				'created' => date('Y-m-d H:i:s'),
				'created_by' => $this->_user['user_id'],
				'modified' => date('Y-m-d H:i:s'),
				'modified_by' => $this->_user['user_id'],
				'client_id' => $practice_data['client_id'],
				'ext_db_id1' => $practice_data['ext_db_id1'],
				'external_id1' => $practice_data['external_id1'],
				'ext_db_id2' => $practice_data['ext_db_id2'],
				'external_id2' => $practice_data['external_id2'],
				'ext_db_id3' => $practice_data['ext_db_id3'],
				'external_id3' => $practice_data['external_id3'],
				'split_charges' => $practice_data['split_charges'],
				'medical_group' => $practice_data['medical_group'],
				'surgical_group' => $practice_data['surgical_group'],
				'pt_group' => $practice_data['pt_group'],
			);
			$practice_id = $this->clients->add_practice($data);
			if ($practice_id)
			{
				// Add practice locations
				
				$this->clients->update_practice_locations($practice_id, $locations, array($data));
				
				/*if (is_array($locations) && count($locations))
				{
					$all_locs = $this->clients->get_external_locations(FALSE);
					//echo '<pre>'.print_r($data['all_locs'], true).'</pre>';return;
					if (is_array($all_locs) && count($all_locs))
					{
						$db_locs = array();
						$this->load->library('mshc_connector');
						$dbs = $this->mshc_connector->getDBArray();
						foreach ($all_locs as $ext_loc)
						{
							if (in_array($ext_loc['display_name'], $locations))
							{
								$db_locs[] = array(
									'external_id' => $ext_loc['cost_center_id'],
									'ext_db_id' => array_search($ext_loc['database_name'], $dbs),
									'practice_id' => $practice_id,
									'external_name' => $ext_loc['display_name']
								);
							}
						}
						
						if (count($db_locs))
						{
							if ($this->clients->add_practice_locations($db_locs) === FALSE)
							{
								$error .= '<br /><br />Some errors occured while update practice locations.';
							}
						}
					}
				}*/
				
				// Add financial groups and classes
				if (is_array($fin_groups_data) && count($fin_groups_data))
				{
					$data_batch = array();
					for ($i = 0; $i < count($fin_groups_data); ++$i)
					{
						$data_batch[] = array(
							'fin_grp_id' => $fin_groups_data[$i]['group_id'],
							'ext_dbs_fin_class_id' => $fin_groups_data[$i]['class_id'],
							'practice_id' => $practice_id
						);
					}
					if (count($data_batch))
					{
						if ($this->clients->add_practice_finances($data_batch) === FALSE)
						{
							$error .= '<br /><br />Some errors occured while add practice finances.';
						}
					}
				}
				
				//Update appt reasons
				$this->clients->update_practice_appt_reasons($practice_id, $appt_reason_data);
			
				// Add appt reasons
				/*if (is_array($appt_reason_data) && count($appt_reason_data))
				{
					for ($i = 0; $i < count($appt_reason_data); ++$i)
					{
						$appt_reason_data[$i]['practice_id'] = $practice_id;
					}
					if (count($appt_reason_data))
					{
						if ($this->clients->add_practice_appt_reasons($appt_reason_data) === FALSE)
						{
							$error .= '<br /><br />Some errors occured while add practice appt reasons.';
						}
					}					
				}*/
				
				// Add portal activity
				$activity_info = 'Practice Name: '.$practice_data['practice_name'].';';
				$this->activity->add_activity_log('Add Practice', 'both', $activity_info);
				
				$result['code'] = 200;
				$result['message'] = 'Practice added successfully.';
				$result['errors'] = $error;
				$result['practice_id'] = $practice_id;
				echo json_encode($result);
				return;
			}
		}
		$error['code'] = 400;
		$error['message'] = 'Error adding new practice. Please try again later.';
		$error['errors'] = '';
		echo json_encode($error);		
	} // process_add_practice

	/*
	* Update practice
	*/
	public function process_update_practice()
	{
		$data = $this->input->post(NULL, TRUE);
		$practice_data = get_array_value('form_data', $data);
		$locations = get_array_value('locations', $data);
		$fin_groups_data = get_array_value('fin_groups', $data);
		$appt_reason_data = get_array_value('appt_reasons', $data);
		$error = '';
		$practice_id = $practice_data['view_id'];
		
		// Update practice
		if ($practice_id)
		{
			$data = array(
				'name' => $practice_data['practice_name'],
				'modified' => date('Y-m-d H:i:s'),
				'modified_by' => $this->_user['user_id'],
				'ext_db_id1' => $practice_data['ext_db_id1'],
				'external_id1' => $practice_data['external_id1'],
				'ext_db_id2' => $practice_data['ext_db_id2'],
				'external_id2' => $practice_data['external_id2'],
				'ext_db_id3' => $practice_data['ext_db_id3'],
				'external_id3' => $practice_data['external_id3'],
				'split_charges' => $practice_data['split_charges'],
				'medical_group' => $practice_data['medical_group'],
				'surgical_group' => $practice_data['surgical_group'],
				'pt_group' => $practice_data['pt_group'],
			);
			$this->clients->update_practice($practice_id, $data);
			
			// Remove old practice locations
			//$this->clients->delete_practice_locations($practice_id);
			
			$this->clients->update_practice_locations($practice_id, $locations, array($data));

			// Add updated practice locations
			/*if (is_array($locations) && count($locations))
			{
				$all_locs = $this->clients->get_external_locations(FALSE);
				//echo '<pre>'.print_r($data['all_locs'], true).'</pre>';return;
				if (is_array($all_locs) && count($all_locs))
				{
					$db_locs = array();
					$this->load->library('mshc_connector');
					$dbs = $this->mshc_connector->getDBArray();
					foreach ($all_locs as $ext_loc)
					{
						if (in_array($ext_loc['display_name'], $locations))
						{
							$db_locs[] = array(
								'external_id' => $ext_loc['cost_center_id'],
								'ext_db_id' => array_search($ext_loc['database_name'], $dbs),
								'practice_id' => $practice_id,
								'external_name' => $ext_loc['display_name']
							);
						}
					}
					
					if (count($db_locs))
					{
						if ($this->clients->add_practice_locations($db_locs) === FALSE)
						{
							$error .= '<br /><br />Some errors occured while update practice locations.';
						}
					}
				}
			}*/

			// Remove old practice finances
			$this->clients->delete_practice_finances($practice_id);
			
			// Add updated practice finances
			if (is_array($fin_groups_data) && count($fin_groups_data))
			{
				$data_batch = array();
				for ($i = 0; $i < count($fin_groups_data); ++$i)
				{
					$data_batch[] = array(
						'fin_grp_id' => $fin_groups_data[$i]['group_id'],
						'ext_dbs_fin_class_id' => $fin_groups_data[$i]['class_id'],
						'practice_id' => $practice_id
					);
				}
				if (count($data_batch))
				{
					if ($this->clients->add_practice_finances($data_batch) === FALSE)
					{
						$error .= '<br /><br />Some errors occured while update practice finances.';
					}
				}
			}
			
			//Update appt reasons
			$this->clients->update_practice_appt_reasons($practice_id, $appt_reason_data);
			
			// Remove old practice appt reasons
			/*$this->clients->delete_practice_appt_reasons($practice_id);
			
			// Update appt reasons
			if (is_array($appt_reason_data) && count($appt_reason_data))
			{
				for ($i = 0; $i < count($appt_reason_data); ++$i)
				{
					$appt_reason_data[$i]['practice_id'] = $practice_id;
				}
				if (count($appt_reason_data))
				{
					if ($this->clients->add_practice_appt_reasons($appt_reason_data) === FALSE)
					{
						$error .= '<br /><br />Some errors occured while update practice appt reasons.';
					}
				}					
			}*/
			
			// Add portal activity
			$activity_info = 'Practice Name: '.$practice_data['practice_name'].';';
			$this->activity->add_activity_log('Edit Practice Info', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'Practice updated successfully.';
			$result['errors'] = $error;
			$result['practice_id'] = $practice_id;
			echo json_encode($result);
			return;
		}
		$error['code'] = 400;
		$error['message'] = 'Error updating practice. Please try again later.';
		$error['errors'] = '';
		echo json_encode($error);		
	} // process_update_practice

	/*
	* Get practices table for admin
	*/
	public function get_practices_table($client_id)
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting'], 'clientID' => $client_id));
		$this->load->library('mshc_general');
		$this->sortingTable = $this->get_order_by_post($data);
		$data['client_id'] = $client_id;
		$params = $this->mshc_general->get_practices_params($search_data, $this->sortingTable, $data);
		$result = array();

		// Get total practices count
		$total_params = $params;
		unset($total_params['offset']);
		unset($total_params['limit']);
		$total_practices = count($this->clients->get_practices($total_params, FALSE));

		// Get practices
		$practices = $this->clients->get_practices($params, FALSE);

		if (count($practices)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_practices;
			$result['Records'] = $practices;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	} // get_practices_table
	
	/*
	* Get practice data
	*/
	public function process_get_practice_data()
	{
		$practice_id = $this->input->get_post('id', TRUE);
		if ($practice_id)
		{
			// Get practice data
			$params = array(
				'from' => array(
					$this->practices_table_name => ''
				),
				'where' => array(
					'id' => $practice_id
				)
			);
			$practice_data = $this->clients->get_practices($params, FALSE);
			
			// Add portal activity
			$activity_info = 'Practice Name: '.$practice_data[0]['name'].';';
			$this->activity->add_activity_log('View Practice Info', 'both', $activity_info);
			
			// Get practice locations
			$practice_locations = $this->clients->get_practice_locations($practice_id, $practice_data);
			//print_r($practice_locations);
			// Get practice finances
			$practice_financess = $this->clients->get_practice_finances($practice_id);

			// Get practice appt reasons
			$practice_appt_reasons = $this->clients->get_practice_appt_reasons($practice_id);
			//print_r($practice_appt_reasons);
			
			$result['code'] = 200;
			$result['practice'] = array(
				'practiceData' => $practice_data,
				'practiceLocs' => $practice_locations,
				'practiceFin' => $practice_financess,
				'practiceAppt' => $practice_appt_reasons
			);
			echo json_encode($result);
			return;
		}
		$result['code'] = 400;
		$result['message'] = 'Errors occured while getting practice info. Please try again later.';
		echo json_encode($result);		
	} // process_get_practice_data

	/*
	* Delete practice and relations
	*/
	public function process_delete_practice()
	{
		$practice_id = $this->input->post('id', TRUE);
		
		if ($practice_id)
		{
			// Delete practice
			$params = array(
				'from' => array(
					$this->practices_table_name => ''
				),
				'where' => array(
					'id' => $practice_id
				)
			);
			$practice_data = $this->clients->get_practices($params, FALSE);
			$this->clients->delete_practice($practice_id);

			// Add portal activity
			$activity_info = 'Practice Name: '.$practice_data[0]['name'].';';
			$this->activity->add_activity_log('Delete Practice', 'both', $activity_info);

			$error['code'] = 200;
			$error['message'] = 'Practice deleted succsesfully.';
			echo json_encode($error);
			return;		
		}
		
		$error['code'] = 400;
		$error['message'] = 'Error deleting practice. Please try again later.';
		echo json_encode($error);
		return;		
	}
	
	
	/*
	* Get cases table for admin
	*/
	public function get_cases_table()
	{
		$data = $this->input->get(NULL, TRUE);

		$result = array();

		// short search parameters
		$client_cases_name = $this->input->post('sName', true);
		$client_cases_ssn = $this->input->post('sSSN', true);
		$client_cases_account = $this->input->post('sAccount', true);
		$client_cases_type_date = $this->input->post('sTypeDate', true);
		$client_cases_date_from = $this->input->post('sDateFrom', true);
		$client_cases_date_to = $this->input->post('sDateTo', true);
		$client_cases_class = $this->input->post('sClass', true);
		$client_cases_cases_type = $this->input->post('sCasesType', true);
		$client_cases_attys = $this->input->post('sAttys', true);
		$client_cases_my_cases = filter_var($this->input->post('sMyCases', true), FILTER_VALIDATE_BOOLEAN);
        $client_cases_company = $this->input->post('sCompany', true);
		$client_cases_financial = $this->input->post('sFinancial', true);
		
		if (!is_array($client_cases_attys)) $client_cases_attys = array();

		if ($client_cases_name == '' && $client_cases_ssn == '' && $client_cases_account == '' && $client_cases_date_from == '' && $client_cases_date_to == '' && $client_cases_class == '' && count($client_cases_attys) == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');
		$fields = array('first_name', 'last_name', 'middle_name', 'account', 'ssn', 'accident_date', 'db_name', 'attorney_name', 'attorney_id', 'status', 'case_category', 'patient', 'case_no', 'practice');
		$conds = array();
		
		if ($client_cases_name != '') {
			$conds['name'] = $client_cases_name;
		}
		
		if ($client_cases_ssn != '') {
			$conds['ssn'] = array('op' => '%val', 'value' => $client_cases_ssn);
		}
		
		if ($client_cases_account != '') {
			$conds['account'] = $client_cases_account;
		}

        $status = array();
        switch ($client_cases_cases_type) {
            case 'active': $status['value'][] = 'active'; break;
            case 'discharged': $status['value'][] = 'discharged'; break;
        }
        if (element('value', $status)) {
            $conds['status'] = $status;
            $conds['status']['op'] = 'IN';
        }
		
		if ($client_cases_class != '') {
			$conds['case_category']['value'] = $client_cases_class;
			$conds['case_category']['op'] = 'contains';
		}
		
		if ($client_cases_type_date != '' && ($client_cases_date_from != '' || $client_cases_date_to != '')) {
			if ($client_cases_type_date == 'accident') {
				$accident_date_between['value'][] = $client_cases_date_from ? $client_cases_date_from : date('m/d/Y', strtotime('1/1/1990'));
				$accident_date_between['value'][] = $client_cases_date_to ? $client_cases_date_to : date('m/d/Y');
				$conds['accident_date_val'] = $accident_date_between;
				$conds['accident_date_val']['op'] = 'BETWEEN';
			} else {
				$service_date_between['value'][] = $client_cases_date_from ? $client_cases_date_from : date('m/d/Y', strtotime('1/1/1990'));
				$service_date_between['value'][] = $client_cases_date_to ? $client_cases_date_to : date('m/d/Y');
				$conds['service_date_val'] = $service_date_between;
				$conds['service_date_val']['op'] = 'BETWEEN';
			}
		}

		if ($client_cases_company != '') {
			switch ($client_cases_company) {
                case 'Multi': $company_value = $client_cases_financial == '' ? array('PT', 'MD') : $client_cases_financial; break;
                case 'MED': $company_value = 'RX'; break;
                default: $company_value = $client_cases_company; break;
            }
			if (is_array($company_value)) {
                $conds['company'] = array(
                    'op' => 'or',
                    'value' => $company_value
                );
            } else {
                $conds['company'] = $company_value;
            }
		}

		$dbs = $this->mshc_connector->getDBArray();
		
		if ($client_cases_my_cases === TRUE) {
			$assigned_cases = $this->firms->get_assigned_cases(array('where' => array('user_id' => $this->_user['user_id'])));
			
			if (count($assigned_cases)) {
				$conds['cases'] = array(
					'op' => 'include',
					'value' => array()
				);
				
				foreach ($assigned_cases as $case)
				{
					$conds['cases']['value'][] = array(
						'db_name' => $dbs[$case['ext_db_id']],
						'account' => $case['external_id1'],
						'practice' => $case['external_id2'],
						'case_no' => $case['external_id3'],
						'patient' => $case['external_id4']
					);
				}
			}
		}

		$attorneys_list = array();
        $join_conds = '';
		/*if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
			$join_conds = ' AND edla.ext_db_id = 3';
		}*/

		if (count($client_cases_attys)) {
			$params = array(
				'fields' => array(
					'la.*' => '',
					'edla.*' => ''
				),
				'from' => array(
					$this->legal_attorneys_table_name => 'la'
				),
				'join' => array(
					array(
						'table' => $this->ext_dbs_legal_attys_table_name.' AS edla',
						'condition' => ' la.id = edla.legal_atty_id'.$join_conds
					)
				)
			);
			$where_or = '';
			foreach ($client_cases_attys as $k=>$v) {
				if ($where_or != '') $where_or .= ' OR ';
				$where_or .= ' la.id = '.$v;
			}
			$params['where'][$where_or] = '';
			//print_r($params);
			$attorneys_list = $this->firms->get_attorneys($params, FALSE);
			//echo $this->db->last_query();
		} elseif ($this->_user['role_id'] == MSHC_AUTH_BILLER) {
            /*$attys = $this->mshc_connector->getAttorneys(
                array(1,2,3,4,5),
                'all',
                array(
                    'order' => array('employer_name'),
                    'debugReturn' => 'sample',
                )
            );

            if ($attys && count($attys)) {
                foreach ($attys as $atty)
                {
                    $attorneys_list[] = array(
                        'external_id' => $atty['employer_id'],
                        'ext_db_id' => array_search(strtoupper($atty['database_name']), $dbs)
                    );
                }
            }*/
        } else {
			$this->load->library('mshc_general');
			$attorneys_list = $this->mshc_general->getUserAttorneys();
		}
		//echo '<pre>'.print_r($attorneys_list, true).'</pre>';exit;

		if (count($attorneys_list) == 0 && $this->_user['role_id'] != MSHC_AUTH_BILLER) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			//print_r(json_encode($result));
			return;
		}
		
		if (count($attorneys_list) > 0) {
			$conds['attorney_id'] = array(
				'op' => 'or',
				'value' => array()
			);
			foreach ($attorneys_list as $atty) 
			{
				$conds['attorney_id']['value'][] = array(
					'attorney_id' => $atty['external_id'],
					'database' => $dbs[$atty['ext_db_id']]
				);
			}
		}
		
		//echo '<pre>'.print_r($conds, true).'</pre>';return;
		
		$total_cases = $this->mshc_connector->getCases(array(1,2,3,4,5), 'count', array('fields' => $fields, 'conds' => $conds, 'debugReturn' => 'sample_all'));
		if (is_array($total_cases) && $total_cases['count'] > 1000) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = (PMS_CONN == 'live') ? $total_cases['count'] : count($total_cases);
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}
		
		$page_size = get_array_value('jtPageSize',$data);
		$page_start = get_array_value('jtStartIndex',$data);
		$page_count = ceil($total_cases['count'] / $page_size);
		if (($page_count - 1) == ($page_start / $page_size)) {
			$page_size = $total_cases['count'] % $page_size;
			
		}
		
		$limit[] = $page_size ? $page_size : get_array_value('jtPageSize',$data);
		$limit[] = $page_start;
		//echo '<pre>'.print_r($conds,true).'</pre>';
        $this->session->set_userdata(array('jtSorting' => $data['jtSorting']));

        $cases = $this->mshc_connector->getCases(
			array(1,2,3,4,5), 
			'all', 
			array(
				'fields' => $fields, 
				'conds' => $conds, 
				'limit' => $limit, 
				'order' => $this->get_order_by_post($data), 
				'debugReturn' => 'sample_all'
			)
		);

		if (is_array($cases) && count($cases) > 0) {
			for ($i = 0; $i < count($cases); $i++)
			{
				$conds = array();
				$conds['account'] = $cases[$i]['account'];
				$conds['db_name'] = $cases[$i]['db_name'];
				$conds['practice'] = $cases[$i]['practice'];
				$conds['case_no'] = $cases[$i]['case_no'];
				$conds['patient'] = $cases[$i]['patient'];
				$appts = $this->mshc_connector->getApptStatus(array(1,2,3,4,5), 'all', array('fields' => $fields, 'conds' => $conds, 'debugReturn' => 'sample'));
				$cases[$i]['appt_status'] = number_format($appts[0]['appt_status']*100, 2).'%';

                $accident_date = $cases[$i]['accident_date'];
                if ($accident_date instanceof DateTime) {
                    $cases[$i]['accident_date'] = $accident_date->format('m/d/Y');
                }
			}
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = (PMS_CONN == 'live') ? $total_cases['count'] : count($total_cases);
			$result['Records'] = $cases;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}
		print_r(json_encode($result));
	}

	/*
	* Get cases table for creation new cases
	*/
	public function get_cases_new_table()
	{

		$data = $this->input->get(NULL, TRUE);
		
		$result = array();

		// short serach parameters
		$client_cases_name = $this->input->post('sName', true);
		$client_cases_ssn = $this->input->post('sSSN', true);
		$client_cases_phone = $this->input->post('sPhone', true);
		$client_cases_dob = $this->input->post('sDOB', true);
		
		if ($client_cases_name) $this->session->set_userdata('client_cases_name', $client_cases_name);
		//else $this->session->unset_userdata('client_cases_name');
		if ($client_cases_ssn) $this->session->set_userdata('client_cases_ssn', $client_cases_ssn);
		//else $this->session->unset_userdata('client_cases_ssn');
		if ($client_cases_phone) $this->session->set_userdata('client_cases_phone', $client_cases_phone);
		//else $this->session->unset_userdata('client_cases_phone');
		if ($client_cases_dob) $this->session->set_userdata('client_cases_dob', $client_cases_dob);
		//else $this->session->unset_userdata('client_cases_dob');
			
		if ($client_cases_name == '' && $client_cases_ssn == '' && $client_cases_phone == '' && $client_cases_dob == '') {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');
		$fields = array('first_name', 'last_name', 'middle_name', 'account', 'ssn', 'accident_date', 'db_name', 'attorney_name', 'attorney_id', 'status', 'case_category', 'patient', 'case_no', 'practice', 'address1', 'address2', 'dob', 'zip4', 'e_mail_address', 'phone', 'work_phone');
		
		$conds = array();
		
		if ($client_cases_name != '') {
			$conds['name'] = $client_cases_name;
		}
		
		if ($client_cases_ssn != '') {
			$conds['ssn'] = array('op' => '%val', 'value' => $client_cases_ssn);
		}
		
		if ($client_cases_phone != '') {
			$conds['phone'] = array('op' => 'CONTAINS', 'value' => $client_cases_phone);
		}

		if ($client_cases_dob != '') {
			$conds['dob'] = $client_cases_dob;
		}

		$this->load->library('mshc_general');
		$attorneys_list = $this->mshc_general->getUserAttorneys();
		
		$dbs = $this->mshc_connector->getDBArray();
		if (count($attorneys_list) > 0) {
			$conds['attorney_id'] = array(
				'op' => 'or',
				'value' => array()
			);
			foreach ($attorneys_list as $atty) 
			{
				$conds['attorney_id']['value'][] = array(
					'attorney_id' => $atty['external_id'],
					'database' => $dbs[$atty['ext_db_id']]
				);
			}
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}	
		
		$total_cases = $this->mshc_connector->getCases(array(1, 2, 3, 4, 5), 'all', array('fields' => $fields, 'conds' => $conds, 'debugReturn' => 'sample_all'));
		
		$total_count = element('count', $total_cases);
		if (is_array($total_cases) && $total_count > 1000) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = (PMS_CONN == 'live') ? $total_cases['count'] : count($total_cases);
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}
		
		$page_size = get_array_value('jtPageSize',$data);
		$page_start = get_array_value('jtStartIndex',$data);
		$page_count = ceil($total_count / $page_size);
		if (($page_count - 1) == ($page_start / $page_size)) {
			$page_size = $total_count % $page_size;
			
		}
		
		$limit[] = $page_size ? $page_size : get_array_value('jtPageSize',$data);
		$limit[] = $page_start;
		
		$cases = $this->mshc_connector->getCases(
		    array(1, 2, 3, 4, 5),
            'all',
            array('fields' => $fields, 'conds' => $conds, 'limit' => $limit, 'order' => $this->get_order_by_post($data), 'debugReturn' => 'sample_all')
        );

		if (count($cases) > 0) {
			for ($i = 0; $i < count($cases); $i++) {
				$conds = array();
				$conds['account'] = $cases[$i]['account'];
				$conds['db_name'] = $cases[$i]['db_name'];
				$conds['practice'] = $cases[$i]['practice'];
				$conds['case_no'] = $cases[$i]['case_no'];
				$conds['patient'] = $cases[$i]['patient'];
				$appts = $this->mshc_connector->getApptStatus(array(1), 'all', array('fields' => $fields, 'conds' => $conds, 'debugReturn' => 'sample'));
				$cases[$i]['appt_status'] = number_format($appts[0]['appt_status']*100, 2).'%';
				$cases[$i]['ssn'] = str_repeat('X', strlen($cases[$i]['ssn']) - 4).substr($cases[$i]['ssn'], -4);

                $accident_date = $cases[$i]['accident_date'];
                if ($accident_date instanceof DateTime) {
                    $cases[$i]['accident_date'] = $accident_date->format('m/d/Y');
                }
                $dob = $cases[$i]['dob'];
                if ($dob instanceof DateTime) {
                    $cases[$i]['dob'] = $dob->format('m/d/Y');
                }
			}
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($total_cases);
			$result['Records'] = $cases;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}
	
	/*
	* Get attorneys by firm_id and my or all
	*/
	public function get_attorneys()
	{
		$this->load->library('mshc_general');
		$result = '';

		$firm_id = $this->input->post('firm_id', true);
		$atty_type = $this->input->post('atty_type', true);

        $this->users->update_legal_user($this->_user['user_id'], array('cases_search_attys_type' => $atty_type));

		$params = array(
			'fields' => array(
				'la.*' => ''
			),
			'from' => array(
				$this->legal_attorneys_table_name => 'la'
			),
			'join' =>  array(
				array(
					'table' => $this->ext_dbs_legal_attys_table_name.' AS edla ',
					'condition' => ' la.id = edla.legal_atty_id AND edla.id IS NOT NULL '
				)
			),
			'group' => array('la.id'),
			'order' => array(
				array('la.last_name' => 'ASC', 'la.first_name' => 'ASC')
			)
		);
		
		if ($firm_id != '') {
			$params['where']['la.legal_firm_id'] = $firm_id;
		}
		
		if ($atty_type == 'my') {
			$params['join'][] = array(
					'table' => $this->legal_attorneys_users_table_name.' AS lau',
					'condition' => 'la.id = lau.legal_atty_id'
				);
			$params['where']['lau.user_id'] = $this->_user['user_id'];

            $sql = "SELECT la.*
            FROM legal_firms_users lfu
            JOIN legal_attys la ON la.legal_firm_id = lfu.legal_firm_id
            JOIN ext_dbs_legal_attys edla ON edla.legal_atty_id = la.id
            WHERE lfu.user_id = ".$this->_user['user_id']." AND lfu.all_attorneys = 1

            UNION

            SELECT la.*
            FROM legal_attys_users lau
            JOIN ext_dbs_legal_attys edla ON lau.legal_atty_id = edla.legal_atty_id
            JOIN legal_attys AS la ON la.id = edla.legal_atty_id
            WHERE lau.user_id = ".$this->_user['user_id']."
            GROUP BY la.id";

            $query = $this->db->query($sql);
            $attorneys_list = $query->num_rows() ? $query->result_array() : array();

            $params = null;
		} else if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN && $this->_user['role_id'] != MSHC_AUTH_BILLER) {
			$params['from'] = array(
				$this->legal_firms_users_table_name => 'lfu'
			);
			$params['where']['lfu.user_id'] = $this->_user['user_id'];
			$params['join'] = array(
				array(
					'table' => $this->legal_attorneys_table_name.' AS la',
					'condition' => 'la.legal_firm_id = lfu.legal_firm_id'
				),
				array(
					'table' => $this->ext_dbs_legal_attys_table_name.' AS edla ',
					'condition' => ' la.id = edla.legal_atty_id AND edla.id IS NOT NULL '
				)
			);
		}

        if ($params) {
            $attorneys_list = $this->firms->get_attorneys($params);
        }
		
		if (isset($attorneys_list) && is_array($attorneys_list)) {
			for ($i = 0; $i < count($attorneys_list); $i++) {
				$result .= '<div><input checked="checked" type="checkbox" value="'.
                    $attorneys_list[$i]["id"].'" id="client_cases_attorneys_list_'.
                    $attorneys_list[$i]["id"].'" name="client_cases_attorneys_list" '.($this->_user['role_id'] == MSHC_AUTH_BILLER && $atty_type == 'all' ? 'disabled="disabled"' : '').' /> '.
                    $attorneys_list[$i]["last_name"].', '.$attorneys_list[$i]["first_name"].'</div>';
			}
		}

		print_r($result);
	}
	
	/*
	* Get summary case table for admin
	*/
	public function get_summary_case_table()
	{
		$result = array();

		// short serach parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);
        $client_cases_transactions_date = $this->input->post('sTransactionsDate', true);

		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '')) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');

		$conds = array();
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;

        if ($client_cases_transactions_date) {
            $conds['last_post_date'] = date('Y-m-d', $client_cases_transactions_date);
        }

		$summaries = $this->mshc_connector->getSummary(array(1), 'all', array('conds' => $conds, 'debugReturn' => 'sample'));
        $maxServiceDate = $this->mshc_connector->getMaxServiceDate(array(1,2,3,4,5), 'all', array('conds' => $conds, 'debugReturn' => 'sample' ));
        $maxServiceDate = element(0, $maxServiceDate);
        $maxServiceDateOpts = '';
        $msdHtml = date('M d, Y');

        $maxServiceDate = element('MaxServiceDate', $maxServiceDate);
        if ($maxServiceDate instanceof DateTime) {
            $msdHtml = $maxServiceDate->format('M d, Y');
            $start = strtotime($maxServiceDate->format('m/d/Y') . '+ 1 day');
            $end = time();
            for ($i = $start; $i < $end; $i += 3600 * 24) {
                $maxServiceDateOpts .= '<option value="' . $i . '">' . date('m/d/Y', $i) . '</option>';
            }
            $maxServiceDateOpts .= '<option value="' . $end . '">' . date('m/d/Y', $end) . '</option>';
        }

		if (is_array($summaries) && count($summaries)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($summaries);
			$charges_total = 0;
			$payments_total = 0;
			$adjustments_total = 0;
			$unupplied_charges_total = $unupplied_payments_total = $unupplied_adjustments_total = 0;
			$formated = array();
			$charges = array();
			$unupplieds = array();
			$company = array();

			foreach ($summaries as $summary)
			{
				if ($summary['is_charge'] == 1) {
					$charges[] = array(
						'company' => $summary['company'],
						'charges' => $summary['charges'],
						'payments' => $summary['payments'],
						'adjustments' => $summary['adjustments'],
						'balance' => $summary['charges'] + $summary['payments'] + $summary['adjustments'],
					);
				} else {
					$unupplieds[] = array(
						'company' => $summary['company'],
						'charges' => $summary['charges'],
						'payments' => $summary['payments'],
						'adjustments' => $summary['adjustments'],
						'balance' => $summary['charges'] + $summary['payments'] + $summary['adjustments'],
					);
				}
			}

			if (count($charges)) {
				foreach ($charges as $charge)
				{
					$tmp = explode('-', $charge['company']);
					$name = trim($tmp[0]);
					if (!in_array($name, $company)) {
						$company[] = $name;
					}
					$formated[] = array(
						'company' => $charge['company'],
						'charges' => $charge['charges'],
						'payments' => $charge['payments'],
						'adjustments' => $charge['adjustments'],
						'balance' => $charge['balance']
					);
					$charges_total += $charge['charges'];
					$payments_total += $charge['payments'];
					$adjustments_total += $charge['adjustments'];
				}
				$formated[] = array(
					'company' => ($this->mshc_dir_view  == '' ? '<div align="right"><strong>Total:</strong></div>' : 'Total:'),
					'charges' => $charges_total,
					'payments' => $payments_total,
					'adjustments' => $adjustments_total,
					'balance' => $charges_total + $payments_total + $adjustments_total
				);
			}

			$result['company'] = $company;
			
			if (count($unupplieds)) {
				$formated[] = array(
					'company' => '<div align="right"><strong>Unapplied payments:</strong></div>',
					'charges' => '&nbsp;',
					'payments' => '&nbsp;',
					'adjustments' => '&nbsp;',
					'balance' => '&nbsp;'
				);
				foreach ($unupplieds as $unupplied)
				{
					$formated[] = array(
						'company' => $unupplied['company'],
						'charges' => $unupplied['charges'],
						'payments' => $unupplied['payments'],
						'adjustments' => $unupplied['adjustments'],
						'balance' => $unupplied['balance']
					);
					$unupplied_charges_total += $unupplied['charges'];
					$unupplied_payments_total += $unupplied['payments'];
					$unupplied_adjustments_total += $unupplied['adjustments'];
				}
				$formated[] = array(
					'company' => ($this->mshc_dir_view  == '' ? '<div align="right"><strong>Total:</strong></div>' : 'Total:'),
					'charges' => $unupplied_charges_total,
					'payments' => $unupplied_payments_total,
					'adjustments' => $unupplied_adjustments_total,
					'balance' => $unupplied_charges_total + $unupplied_payments_total + $unupplied_adjustments_total
				);
			}

			//print_r($formated);return;
			
			/*for ($i = 0; $i <= count($summaries); $i++) 
			{
				if ($summaries[$i]['is_charge'] == 1)
				{
					$charges_total += $summaries[$i]['charges'];
					$payments_total += $summaries[$i]['payments'];
					$adjustments_total += $summaries[$i]['adjustments'];
					$balance_total += $summaries[$i]['balance'];
				}
				else
				{
					$unapplied_charges_total += $summaries[$i]['charges'];
					$unapplied_payments_total += $summaries[$i]['payments'];
					$unapplied_adjustments_total += $summaries[$i]['adjustments'];
					$unapplied_balance_total += $summaries[$i]['balance'];
				}
				if ($i == count($summaries)) {
					$summaries[$i]['company'] = ($this->mshc_dir_view  == '' ? '<div align="right"><strong>Total:</strong></div>' : 'Total:');
					$summaries[$i]['charges'] = $charges_total;
					$summaries[$i]['payments'] = $payments_total;
					$summaries[$i]['adjustments'] = $adjustments_total;
					$summaries[$i]['balance'] = $balance_total;
					break;
				} else {
					$summaries[$i]['balance'] = $summaries[$i]['charges'] + $summaries[$i]['payments'] + $summaries[$i]['adjustments'];
				}
			}*/

			$result['Records'] = $formated;//$summaries;
		}
		else
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

        $result['msdHtml'] = $msdHtml;
        $result['MaxServiceDate'] = $maxServiceDate;
        $result['MaxServiceDateOpts'] = $maxServiceDateOpts;
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.';';
		$this->activity->add_activity_log('View Case Summary', 'both', $activity_info);
		
		print_r(json_encode($result));
	}
	
	/*
	* Get visit summary table for admin
	*/
	public function get_visits_summary_table()
	{
		$result = array();

		// short search parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);
		$client_cases_transactions_date = $this->input->post('sTransactionsDate', true);

		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '')) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');

		$conds = array();
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;

        if ($client_cases_transactions_date) {
            $conds['last_post_date'] = date('Y-m-d', $client_cases_transactions_date);
        }

		$visit_summaries = $this->mshc_connector->getVisits(array(1), 'all', array('conds' => $conds, 'debugReturn' => 'sample'));

		if (count($visit_summaries))
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($visit_summaries);
			$grand_balance = $grand_charges = $grand_payments = $grand_adjustments = 0;
			$charges_total = $payments_total = $adjustments_total = $balance_total = $k = 0;
			$company_name = '';
			$visits = array();

			for ($i = 0; $i <= count($visit_summaries); $i++) 
			{
				if ($i == count($visit_summaries)) 
				{
					$visits[$k]['company'] = ($this->mshc_dir_view  == '' ? '<div class="company-total" align="right"><strong>'.$company_name.' Total:</strong></div>' : 'Total '.$company_name);
					$visits[$k]['dov'] = '';
					$visits[$k]['charges'] = $charges_total;
					$visits[$k]['payments'] = $payments_total;
					$visits[$k]['adjustments'] = $adjustments_total;
					$visits[$k]['balance'] = $balance_total;
					$visits[$k]['companyName'] = '';
					$visits[$k]['SequenceNo'] = 0;
					$grand_adjustments += $adjustments_total;
					$grand_charges += $charges_total;
					$grand_payments += $payments_total;
					$grand_balance += $balance_total;
					$visits[$k + 1]['company'] = ($this->mshc_dir_view  == '' ? '<div class="grand-total" align="right"><strong>Grand Total:</strong></div>' : 'Grand Total:');
					$visits[$k + 1]['dov'] = '';
					$visits[$k + 1]['charges'] = $grand_charges;
					$visits[$k + 1]['payments'] = $grand_payments;
					$visits[$k + 1]['adjustments'] = $grand_adjustments;
					$visits[$k + 1]['balance'] = $grand_balance;
					$visits[$k + 1]['companyName'] = '';
					$visits[$k + 1]['SequenceNo'] = 0;
					break;
				} else {
					if ($company_name != strtoupper($visit_summaries[$i]['company'])) {
						if ($k > 0) {
							$visits[$k]['company'] = ($this->mshc_dir_view  == '' ? '<div align="right" class="company-total"><strong>'.$company_name.' Total:</strong></div>' : 'Total '.$company_name);
							$visits[$k]['dov'] = '';
							$visits[$k]['charges'] = $charges_total;
							$visits[$k]['payments'] = $payments_total;
							$visits[$k]['adjustments'] = $adjustments_total;
							$visits[$k]['balance'] = $balance_total;
							$visits[$k]['companyName'] = '';
							$visits[$k]['SequenceNo'] = 0;
							$grand_adjustments += $adjustments_total;
							$grand_charges += $charges_total;
							$grand_payments += $payments_total;
							$grand_balance += $balance_total;
							$charges_total = 0;
							$payments_total = 0;
							$adjustments_total = 0;
							$balance_total = 0;
							$k++;
						}
						$company_name = strtoupper($visit_summaries[$i]['company']);
						$visits[$k]['company'] = $company_name;
						$visits[$k]['dov'] = '';
						$visits[$k]['payments'] = '';
						$visits[$k]['charges'] = '';
						$visits[$k]['adjustments'] = '';
						$visits[$k]['balance'] = '';
						$visits[$k]['companyName'] = '';
						$visits[$k]['SequenceNo'] = '';
						$k++;
					}

                    $dov = $visit_summaries[$i]['dov'];
                    if ($dov instanceof DateTime) {
                        $visits[$k]['dov'] = $dov->format('m/d/Y');
                    }

					$visits[$k]['company'] = '';
					$visits[$k]['balance'] = $visit_summaries[$i]['charges'] + $visit_summaries[$i]['payments'] + $visit_summaries[$i]['adjustments'];
					$visits[$k]['payments'] = $visit_summaries[$i]['payments'];
					$visits[$k]['charges'] = $visit_summaries[$i]['charges'];
					$visits[$k]['adjustments'] = $visit_summaries[$i]['adjustments'];
					$visits[$k]['companyName'] = $company_name;
					$visits[$k]['SequenceNo'] = $visit_summaries[$i]['SequenceNo'];
					$charges_total += $visits[$k]['charges'];
					$payments_total += $visits[$k]['payments'];
					$adjustments_total += $visits[$k]['adjustments'];
					$balance_total += $visits[$k]['balance'];
					$k++;
				}
			}
			$result['Records'] = $visits;
		}
		else
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.';';
		$this->activity->add_activity_log('View Case Visits', 'both', $activity_info);
		
		print_r(json_encode($result));
	}
	
	/*
	* Get visit summary details table for admin
	*/
	public function get_visits_summary_details_table()
	{
		// short serach parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);
		$client_cases_company = $this->input->post('sCompany', true);
		$client_cases_dov = $this->input->post('sDOV', true);
		$client_cases_sequence_no = $this->input->post('sSequence', true);

		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '' || $client_cases_company == '' || $client_cases_dov == '' || $client_cases_sequence_no == '')) {
			echo "No data available";
			return;
		}

		$this->load->library('mshc_connector');

		$conds = array();
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;
		$conds['company'] = $client_cases_company;
		$conds['dov'] = $client_cases_dov;
		$conds['sequence'] = $client_cases_sequence_no;
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.'; Company: '.$client_cases_company.'; DOV: '
			.date('m/d/Y', strtotime($client_cases_dov)).';';
		$this->activity->add_activity_log('View Case Visit Summary', 'both', $activity_info);
		
		$visit_summaries_details = $this->mshc_connector->getVisitSummary(array(1), 'all', array('conds' => $conds, 'debugReturn' => 'sample'));
		//print_r($visit_summaries_details); return;

		if (count($visit_summaries_details)) {
			$balance_total = 0;
			$visit_summary_details_table = ($this->mshc_dir_view  == '' ? "<table class=\"visit-summary-detail-payment\"><tr><th style=\"text-align: left\">Office</th><th>Provider</th><th>Dov</th><th>Code/Sorce</th><th>Description</th><th style=\"text-align: right;padding-right: 10px;\">Amount</th></tr>" : "");

			for ($i = 0; $i <= count($visit_summaries_details); $i++) 
			{
				if ($i == count($visit_summaries_details))  {
					if ($balance_total < 0) {
						$style = 'color:red;';
					} else {
						$style = '';
					}

					if ($this->mshc_dir_view  == '') {
						$visit_summary_details_table .= '<tr class="visit-summary-detail2-total">
						<td colspan="5" style="text-align: right">Balance</td>
						<td style="text-align: right;'.$style.'">'.($balance_total < 0 ? '&ndash;' : '').'$'.
						($balance_total < 0 ? number_format(0 - $balance_total,2) : number_format($balance_total,2)).
						'</td></tr></table>';
					} else {
						$visit_summary_details_table .= '<div class="balance">
						<ul class="columns2-balance"><li>Balance</li>
						<li style="'.$style.'">'.($balance_total < 0 ? '&ndash;' : '').'$'.
						($balance_total < 0 ? number_format(0 - $balance_total,2) : number_format($balance_total,2)).
						'</li></ul><div class="clear"></div></div>';
					}
					break;
				} else {
					if ($this->mshc_dir_view  == '') {
						$class_even = "";
						if ($i % 2 == 1) $class_even = "background-color:#f2f2f2";
						$visit_summary_details_table .= "<tr style=".$class_even." data=\"{charge: ".$visit_summaries_details[$i]['charge_type']."}\"><td>".$visit_summaries_details[$i]['office']."</td><td>".$visit_summaries_details[$i]['provider']."</td><td>";
						if ($visit_summaries_details[$i]['dov'] != '') {
							$visit_summary_details_table .= date_format($visit_summaries_details[$i]['dov'], 'm/d/Y');
						}
						$visit_summary_details_table .= "</td><td>".$visit_summaries_details[$i]['code_source']."</td><td>".$visit_summaries_details[$i]['description']."</td>";
						
						if ($visit_summaries_details[$i]['amount'] < 0) {
							$visit_summary_details_table .= '<td style="text-align: right"><span style="color: red">
							&ndash;$'.number_format(0 - $visit_summaries_details[$i]['amount'],2).'</span></td></tr>';
						} else {
							$visit_summary_details_table .= "<td style=\"text-align: right\">$".number_format($visit_summaries_details[$i]['amount'],2)."</td></tr>";
						}
					} else {
						$visit_summary_details_table .= "<ul class=\"rows\"><li><ul class=\"columns2\"><li>";
						if ($visit_summaries_details[$i]['office'] != '') {
							$visit_summary_details_table .= "<strong>Office:</strong> ".$visit_summaries_details[$i]['office']."<br />";
						}
						$visit_summary_details_table .= "<strong>Dov:</strong> ".date_format($visit_summaries_details[$i]['dov'], 'm/d/Y')."</li><li>";
						if ($visit_summaries_details[$i]['provider'] != '') {
							$visit_summary_details_table .= "<strong>Provider:</strong> ".$visit_summaries_details[$i]['provider']."<br />";
						}
						$visit_summary_details_table .= "<strong>Code/Source:</strong> ".$visit_summaries_details[$i]['code_source']."</li></ul>
						<div class=\"clear\"></div><div><strong>Description: </strong><br />".$visit_summaries_details[$i]['description']."</div>
						<div style=\"text-align: right\" ".($visit_summaries_details[$i]['amount'] < 0 ? "class=\"negative-balance\"": "").">".
						($visit_summaries_details[$i]['amount'] < 0 ? '&ndash;' : '').'$'.
						number_format($visit_summaries_details[$i]['amount'] < 0 
							? 0 - $visit_summaries_details[$i]['amount'] 
							: $visit_summaries_details[$i]['amount'],2)."</div></li>";
					}
					
					$balance_total += $visit_summaries_details[$i]['amount'];
				}
			}
			
			$result = $visit_summary_details_table;
		} else {
			$result = "No data available";
		}

		echo $result;
	}
	
	/*
	* Get appointments case table for admin
	*/
	public function get_appointments_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$result = array();

		// short serach parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);
		
		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '')) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');
		$this->load->library('mshc_general');

		$conds = $this->mshc_general->get_appointments_params($search_data);
		
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;
		
		/*if (is_array($search_data) && get_array_value('sortingQriteria', $search_data) != NULL)
		{
			foreach($search_data as $field => $value)
			{
				if ($search_data['sortingQriteria'] == 'sorting-contains')
				{
					$compares = 'like';
				} 
				elseif ($search_data['sortingQriteria'] == 'sorting-equal')
				{
					$compares = ' = ';
				}
				else
				{
					$compares = ' != ';
				}
				switch ($search_data['sortingFieldName']) 
				{
					case 'provider': $conds['provider_oper'] = $compares;
					break;
					
					case 'reason': $conds['reason_oper'] = $compares;
					break;
					
					case 'location': $conds['loc_oper'] = $compares;
					break;
					
					case 'status': $conds['status_oper'] = $compares;
					break;
					
					case 'accident_date': $conds['date_oper'] = $compares;
					break;
				}
			}
			$conds[$search_data['sortingFieldName']] = $search_data['sortingValue'];
//			print_r($params);
		}		*/

		$fields = array();
		$total_appointments = $this->mshc_connector->getAppointments(
		    array(1,2,3,4,5),
            'all',
            array('fields' => $fields, 'conds' => $conds, 'order' => $this->sortingTable, 'debugReturn' => 'sample')
        );
		
		$page_size = get_array_value('jtPageSize',$data);
		$page_start = get_array_value('jtStartIndex',$data);
		$page_count = ceil(count($total_appointments) / $page_size);
		if (($page_count - 1) == ($page_start / $page_size)) {
			$page_size = count($total_appointments) % $page_size;
		}
		
		$limit[] = $page_size ? $page_size : get_array_value('jtPageSize',$data);
		$limit[] = $page_start;
		
		$this->sortingTable = $this->get_order_by_post($data);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$appointments = $this->mshc_connector->getAppointments(
		    array(1,2,3,4,5),
            'all',
            array('fields' => $fields, 'conds' => $conds,  'limit' => $limit, 'order' => $this->sortingTable, 'debugReturn' => 'sample', )
        );

		if (count($appointments)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($total_appointments);

            foreach ($appointments as &$item)
            {
                $date = $item['date'];
                if ($date instanceof DateTime) {
                    $item['date'] = $date->format('Y/m/d');
                }
                $time = $item['time'];
                if ($time instanceof DateTime) {
                    $item['time'] = $time->format('g:i a');
                }
            }

			$result['Records'] = $appointments;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.';';
		$this->activity->add_activity_log('View Case Appointments', 'both', $activity_info);
		
		print_r(json_encode($result));
	}
	
	/*
	* Get documents table for admin
	*/
	public function get_documents_table()
	{
		$data = $this->input->get(NULL, TRUE);
		
		$result = array();

		// short serach parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);

		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '')) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');

		$conds = array();
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;
	
//		print_r($conds); return;
		$fields = array();

		$requested_documents = $this->mshc_connector->getDocuments(
			array(1, 2, 3, 4, 5), 
			'all', 
			array(
				'fields' => $fields, 
				'conds' => $conds,  
				'order' => $this->get_order_by_post($data), 
				'debugReturn' => 'sample'
			)
		);
//		print_r($requested_documents); return;

		if (count($requested_documents))
		{
			$documents = array();
			foreach ($requested_documents as $doc)
			{
				if (!(array_key_exists($doc['id'], $documents)))
				{
					$_name = explode('page', $doc['document_name']);
					$document_name = rtrim(rtrim($_name[0]), ',');

                    $dos = $doc['date_of_service'];
                    if ($dos instanceof DateTime) {
                        $doc['date_of_service'] = $dos->format('Y-m-d');
                    }

					$documents[$doc['id']] = array(
						'id' => $doc['id'],
						'document_name' => $document_name,
						'document_type' => $doc['document_type'],
						'lPAGEID' => $doc['lPAGEID'],
						'date_of_service' => $doc['date_of_service'],
						'files' => array()
					);
				}
				if ($doc['lPAGEID'])
				{
					$documents[$doc['id']]['files'][$doc['lPAGEID']] = $doc['full_path'];
				}
				else
				{
					$documents[$doc['id']]['files'][] = $doc['full_path'];
				}
			}
			
			// sort files by pageID
			foreach ($documents as $key => $doc)
			{
				ksort($documents[$key]['files']);
				$documents[$key]['files'] = array_values($documents[$key]['files']);
			}

            // set sorting params
            $sort_dir = $this->get_order_by_post($data);
            $this->multiarray_sort_dir = reset($sort_dir);
            $sort_column = $this->get_order_by_post($data);
            $this->multiarray_sort_column = key($sort_column);
			
			// sort array
			uasort($documents, array($this, 'multiarray_sort'));
			
			//print_r($documents); return;
			
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($documents);			
			$result['Records'] = array_values($documents);
		}
		else
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.';';
		$this->activity->add_activity_log('View Case Documents List', 'both', $activity_info);
		
		print_r(json_encode($result));
	}
		
	/*
	* Get mileage report table for admin
	*/
	public function get_mileage_report_table()
	{
		$data = $this->input->get(NULL, TRUE);
		$search_data = $this->input->post(NULL, TRUE);
		$result = array();

		$this->load->library('mshc_connector');
		$fields = array('first_name', 'last_name', 'middle_name', 'account', 'ssn', 'accident_date', 'db_name', 'attorney_name', 'attorney_id', 'status', 'case_category', 'patient', 'case_no', 'practice');

		$conds = array();
//		print_r($conds); return;

		if (is_array($search_data) && get_array_value('sortingQriteria', $search_data) != NULL)
		{
			$compares = '';
			foreach($search_data as $field => $value)
			{
				if ($search_data['sortingQriteria'] == 'sorting-contains')
				{
					$compares = 'CONTAINS';
				} 
				elseif ($search_data['sortingQriteria'] == 'sorting-equal')
				{
					$compares = '';
				}
				else
				{
					$compares = 'NOT';
				}
				if ($compares != '')
				switch ($search_data['sortingFieldName']) 
				{
						case 'last_name': $conds['last_name']['op'] = $compares;
					break;
					
						case 'first_name': $conds['first_name']['op'] = $compares;
					break;
					
						case 'account': $conds['account']['op'] = $compares;
					break;
					
						case 'class': $conds['case_category']['op'] = $compares;
						$search_data['sortingFieldName'] = 'case_category';
					break;
					
						case 'accident_date': $conds['accident_date']['op'] = $compares;
					break;
				}
			}
			if ($compares != '')
				$conds[$search_data['sortingFieldName']]['value'] = $search_data['sortingValue'];
			else
			{
				switch ($search_data['sortingFieldName']) 
				{
					case 'class': $conds['case_category'] = $search_data['sortingValue']; break;
					default: $conds[$search_data['sortingFieldName']] = $search_data['sortingValue']; break;
				}
			}
			//print_r($conds); return;
		}
		
		$status['value'][] = 'active';
		$status['value'][] = 'discharged';
		$conds['status'] = $status;
		$conds['status']['op'] = 'IN';
			
		$this->load->library('mshc_general');
		$dbs = $this->mshc_connector->getDBArray();
		
		if ($this->_user['role_id'] == MSHC_AUTH_CASE_MANAGER)
		{
			$assigned_cases = $this->firms->get_assigned_cases(array('where' => array('user_id' => $this->_user['user_id'])));
			
			if (count($assigned_cases))
			{
				$conds['cases'] = array(
					'op' => 'include',
					'value' => array()
				);
				
				foreach ($assigned_cases as $case)
				{
					$conds['cases']['value'][] = array(
						'db_name' => $dbs[$case['ext_db_id']],
						'account' => $case['external_id1'],
						'practice' => $case['external_id2'],
						'case_no' => $case['external_id3'],
						'patient' => $case['external_id4']
					);
				}
			}
			else
			{
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
				print_r(json_encode($result));
				return;
			}
		}
		else
		{
			$attorneys_list = $this->mshc_general->getUserAttorneys();
			
			if (count($attorneys_list) > 0) 
			{
				$conds['attorney_id'] = array(
					'op' => 'or',
					'value' => array()
				);
				foreach ($attorneys_list as $atty) 
				{
					$conds['attorney_id']['value'][] = array(
						'attorney_id' => $atty['external_id'],
						'database' => $dbs[$atty['ext_db_id']]
					);
				}
			}
			else
			{
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
				print_r(json_encode($result));
				return;
			}
		}
		
		//echo '<pre>'.print_r($conds, true).'</pre>';return;
		
		$total_cases = $this->mshc_connector->getCases(
			array(1,2,3,4,5), 
			'count', 
			array(
				'fields' => $fields, 
				'conds' => $conds, 
				'debugReturn' => 'sample_all'
			)
		);

		$page_size = get_array_value('jtPageSize',$data);
		$page_start = get_array_value('jtStartIndex',$data);
		if (isset($total_cases['count'])) $total_count = $total_cases['count'];
		else $total_count = NULL;
		$page_count = ceil($total_count / $page_size);
		if (($page_count - 1) == ($page_start / $page_size))
		{
			$page_size = $total_count % $page_size;
			
		}
		
		$limit[] = $page_size ? $page_size : get_array_value('jtPageSize',$data);
		$limit[] = $page_start;
		
		$cases = $this->mshc_connector->getCases(
			array(1,2,3,4,5), 
			'all', 
			array(
				'fields' => $fields, 
				'conds' => $conds, 
				'limit' => $limit, 
				'order' => $this->get_order_by_post($data), 
				'debugReturn' => 'sample_all'
			)
		);

		if ($total_cases)
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = (PMS_CONN == 'live') ? $total_cases['count'] : count($total_cases);

            foreach ($cases as &$item)
            {
                $doa = $item['accident_date'];
                if ($doa instanceof DateTime) {
                    $item['accident_date'] = $doa->format('Y-m-d');
                }
            }

			$result['Records'] = $cases;
		}
		else
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}
		print_r(json_encode($result));
	}
	
	
	/*
	* Get calculate distance wish address report table for admin
	*/
	public function get_calculate_distance_table()
	{
		$data = $this->input->get(NULL, TRUE);
		$search_data = $this->input->post(NULL, TRUE);
		
		$result = array();

		// short serach parameters
		$client_cases_account_id = $this->input->post('sAccountID', true);
		$client_cases_patient = $this->input->post('sPatient', true);
		$client_cases_practice = $this->input->post('sPractice', true);
		$client_cases_case_no = $this->input->post('sCaseNo', true);
		$client_cases_db_name = $this->input->post('sDbName', true);
		$clients_address_wish = $this->input->post('typeDist', true);
		$clients_custom_address = $this->input->post('customAddress', true);
		
		// Add portal activity
		$activity_info = 'Account: '.$client_cases_account_id.'; Address: '
			.($clients_custom_address ? $clients_custom_address : ucfirst($clients_address_wish)).';';
		$this->activity->add_activity_log('View Calculated Distance', 'both', $activity_info);
		
		if (PMS_CONN == 'live' && ($client_cases_account_id == '' || $client_cases_patient == '' || $client_cases_practice == '' || $client_cases_case_no == '' || $client_cases_db_name == '' || $clients_address_wish == 'custom_address' && $clients_custom_address == '')) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$this->load->library('mshc_connector');
		
		$conds = array();
		$conds['account'] = $client_cases_account_id;
		$conds['db_name'] = $client_cases_db_name;
		$conds['practice'] = $client_cases_practice;
		$conds['case_no'] = $client_cases_case_no;
		$conds['patient'] = $client_cases_patient;
		
		$case_patient = $this->mshc_connector->getStatementHeader(array(1,2,3,4,5), 'all', array('conds' => $conds, 'debugReturn' => 'sample' ));
		if ( ! is_array($case_patient) || count($case_patient) == 0) 
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			$result['Message'] = 'Patient is not found.';
			print_r(json_encode($result));
			return;
		}
		
		if ($clients_address_wish == 'home') 
		{
			$address_origins = $case_patient[0]['pnt_address1'].', '.
				$case_patient[0]['pnt_addr_city'].', '.
				$case_patient[0]['pnt_addr_state'].', '.
				$case_patient[0]['pnt_addr_zip'];
		} 
		else if ($clients_address_wish == 'work') 
		{
			$address_origins = $case_patient[0]['pnt_work_address1'].', '.
				$case_patient[0]['pnt_work_addr_city'].', '.
				$case_patient[0]['pnt_work_addr_state'].', '.
				$case_patient[0]['pnt_work_addr_zip'];
		}
		else
		{
			$address_origins = '';
		}
		
		if (!$address_origins && ($clients_address_wish == 'work' || $clients_address_wish == 'home'))
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			$result['Message'] = 'Address is not available.';
			print_r(json_encode($result));
			return;
		}
		
		$fields = array();
		$this->load->library('mshc_general');
		unset($conds_app);
		$conds_app = $this->mshc_general->get_distance_params($search_data, $conds);
		$conds_app['status'] = 'kept';
		$conds_app['status_oper'] = 'like';
		$this->sortingTable = $this->get_order_by_post($data);
		if (array_key_exists('distance', $this->sortingTable))
		{
			$distance_sort = $this->sortingTable['distance'];
			$appt_params = array(
				'fields' => $fields, 
				'conds' => $conds_app, 
				'order' => array(), 
				'debugReturn' => 'sample'
			);
		}
		else
		{
			$distance_sort = NULL;
			$appt_params = array(
				'fields' => $fields, 
				'conds' => $conds_app, 
				'order' => $this->sortingTable, 
				'debugReturn' => 'sample'
			);
		}
		
		$this->session->set_userdata(
			array(
				'jtSorting' => $data['jtSorting'], 
				'clients_address_wish' => $clients_address_wish, 
				'clients_custom_address' => $clients_custom_address
			)
		);
		
		$appointments = $this->mshc_connector->getAppointments(array(1,2,3,4,5), 'all', $appt_params);
		
		if (count($appointments) == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			$result['Message'] = 'No appointments is found.';
			print_r(json_encode($result));
			return;
		}
		
		$distances = NULL;
		if (isset($conds_app['distance'])) {
			$distances['value'] = $conds_app['distance'];
			$distances['distance_oper'] = $conds_app['distance_oper'];
		}
		
		$results = $this->mshc_general->get_calculate_distance(
			$case_patient, 
			$appointments, 
			$clients_address_wish, 
			$clients_custom_address, 
			$distances
		);
		
		if ($results !== FALSE)
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = count($results);
			if (!is_null($distance_sort))
			{
				array_sort_by_column($results, 'distance', $distance_sort == 'ASC' ? SORT_ASC : SORT_DESC);
			}
			$result['Records'] = $results;
		}
		else
		{
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			$result['Message'] = 'Address is not found.';
		}
		print_r(json_encode($result));
	}
	
	/*
	* Get discharge and client list report table for admin
	*/
	public function get_discharge_clients_table()
	{
		$data = $this->input->get(NULL, TRUE);
		$search_data = $this->input->post(NULL, TRUE);
		$result = array();
		$discharge_attorney_id = $this->input->post('extAttyID', true);
		
		if ($discharge_attorney_id == '') {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}

		$conds = array();
		if ($discharge_attorney_id != '') {
			$this->load->library('mshc_general');
			$attys_list = $this->mshc_general->getUserAttorneys($discharge_attorney_id);
			
			if (is_array($attys_list) && count($attys_list)) {
				$conds['attorney_id'] = array(
					'op' => 'or',
					'value' => array()
				);
				$attys = array();
				foreach ($attys_list as $atty)
				{
					$attys[] = array(
						'attorney_id' => $atty['external_id'],
						'database' => $atty['ext_db_name']
					);
				}
				$conds['attorney_id']['value'] = $attys;
			} else {
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
				print_r(json_encode($result));
				return;
			}
		}

		$this->load->library('mshc_general');
		$conds = $this->mshc_general->get_discharge_params($search_data, NULL, $conds);
		$this->sortingTable = $this->get_order_by_post($data);
		$this->session->set_userdata(array('jtSorting' => $data['jtSorting']));
		$total_discharge_clients = $this->mshc_connector->getDischargeReportCases(array(1,2,3,4,5), 'count', array('conds' => $conds));
		
		$page_size = get_array_value('jtPageSize',$data);
		$page_start = get_array_value('jtStartIndex',$data);
		$page_count = ceil($total_discharge_clients['count'] / $page_size);

		if (($page_count - 1) == ($page_start / $page_size)) {
			$page_size = $total_discharge_clients['count'] % $page_size;
		}
		
		$limit[] = $page_size ? $page_size : get_array_value('jtPageSize',$data);
		$limit[] = $page_start;

		$discharge_clients = $this->mshc_connector->getDischargeReportCases(
			array(1, 2, 3, 4, 5), 
			'all', 
			array(
				'conds' => $conds,  
				'limit' => $limit,
				'order' => $this->get_order_by_post($data)
			)
		);

		if ($total_discharge_clients) {
            foreach ($discharge_clients as &$item)
            {
                $accident_date = $item['accident_date'];
                if ($accident_date instanceof DateTime) {
                    $item['accident_date'] = $accident_date->format('Y-m-d');
                }
                $discharge_date = $item['discharge_date'];
                if ($discharge_date instanceof DateTime) {
                    $item['discharge_date'] = $discharge_date->format('Y-m-d');
                }
                $dob = $item['dob'];
                if ($dob instanceof DateTime) {
                    $item['dob'] = $dob->format('Y-m-d');
                }
            }

			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = (PMS_CONN == 'live') ? $total_discharge_clients['count'] : count($total_discharge_clients);			
			$result['Records'] = $discharge_clients;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
		}

		print_r(json_encode($result));
	}
	
	// Get attorneys from external DBs
	public function get_ext_attys()
	{
		$atty_name = $data = $this->input->post('atty_name', TRUE);
		$this->load->library('mshc_connector');
		$attys = $this->mshc_connector->getAttorneys(
			array(1,2,3,4,5), 
			'all', 
			array(
				'conds' => array(
					'name' => $atty_name,
				), 
				'order' => array('employer_name'),
				'debugReturn' => 'sample',
			) 		
		);
		if ($attys)
		{
			$result['code'] = 200;
			$result['total'] = count($attys);
			$output = '<table class="jtable attys_searched_table">';
			$check_data = array(
				'name'        => 'select_all_attys_searched',
				'id'          => 'select_all_attys_searched',
				'value'       => '1',
				'checked'     => FALSE,
				'style'       => ''
			);
			$output .= '<thead><tr>
			<th style="width:50px;">'.form_checkbox($check_data).'</th>
			<th style="width:120px;">Database</th>
			<th style="width:165px;">Number</th>
			<th>Attorney</th>
			</tr></thead>';
			foreach($attys as $atty)
			{
				$check_data = array(
					'name' => 'attys_searched',
					'id' => 'attys_searched_'.$atty['employer_id'],
					'value' => $atty['employer_id'],
					'checked' => FALSE,
					'style' => '',
					'data' => '{ext_atty_id:'.$atty['employer_id'].', 
						ext_db_id:'.array_search($atty['database_name'], $this->mshc_connector->getDBArray()).', 
						ext_atty_name: \''.$atty['employer_name'].'\', 
						ext_db_name: \''.$atty['database_name'].'\'}'
				);
				$output .= '<tr>
				<td>'.form_checkbox($check_data).'</td>
				<td>'.$atty['database_name'].'</td>
				<td>'.$atty['employer_id'].'</td>
				<td>'.$atty['employer_name'].'</td>
				</tr>';
			}
			$output .= '</table>';
			$result['results'] = $output;
		}
		else
		{
			$result['code'] = 203;
			$result['error'] = $this->mshc_connector->getError();
		}
		print_r(json_encode($result));
	}
	
	
	/*
	* Get notifications table
	*/
	public function get_notifications_table()
	{
		$search_data = $this->input->post(NULL, TRUE);
		$data = $this->input->get(NULL, TRUE);
		$search_data['jtPageSize'] = $data['jtPageSize'];
		$search_data['jtStartIndex'] = $data['jtStartIndex'];
		$search_data['order'] = $this->get_order_by_post($data);
		
		$result = array();

		// Get total users count
		if ( isset($search_data['sortingFieldName']) && $search_data['sortingFieldName'] != '') {
			$search_data['where'] = ' AND '.$search_data['sortingFieldName'].' = 0  AND nu.deleted = 0 ';
			unset($search_data['sortingFieldName']);
		} else {
			$search_data['where'] = ' AND nu.deleted = 0 ';
		}
		
		$user_id = $this->_user['user_id'];

		// Get users
		$notifications = $this->notifications->get_notifications_by_user_id($user_id, $search_data, FALSE);
		$total_notifications = $this->notifications->get_notifications_by_user_id($user_id, array('where' => $search_data['where']), TRUE);
		$notifications_param_count['where'] = ' AND nu.read = 0 AND nu.deleted = 0 ';
		$total_new_notifications = $this->notifications->get_notifications_by_user_id($this->_user['user_id'], $notifications_param_count, TRUE);

		if (count($notifications)) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = $total_notifications;
			$result['Records'] = $notifications;
			$result['TotalRecordNew'] = $total_new_notifications;
		} else {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			$result['TotalRecordNew'] = 0;
		}

		print_r(json_encode($result));
	}
	
	public function process_get_notification_data() 
	{
		$notif_id = $this->input->get_post('id', TRUE);
		if ($notif_id)
		{
			$notif = $this->notifications->get_notifications(
				array(
					'from' => array(
						$this->notifications_table_name => 'n'
					),
					'where' => array(
						'n.id' => $notif_id
					),
					'join' => array(
						array(
							'table' => $this->notifications_users_table_name.' AS nu',
							'condition' => 'nu.notification_id = n.id AND nu.user_id = '.$this->_user['user_id']
						)
					)
				)
			);
			if (count($notif))
			{
				$notif = $notif[0];

				if ($notif['read'] == 0) $this->notifications->mark_as_read($this->_user['user_id'], $notif_id);
				
				$output = '<form id="document_open_form" name="document_open_form" method="post" action="'.
				base_url().MSHC_CASES_CONTROLLER_NAME.'/documents" target="_blank">
				<input type="hidden" value="" name="document_checkbox[]" />
				<h2 id="dialog-popup-content-title" class="icon_'.$notif['type'].'">'.$notif['title'].'</h2>';
				$output .= '<p>Create Date: '.date('m/d/Y H:i:s', strtotime($notif['created'])).'</p>';
				$output .= '<p>'.$notif['body'].'</p>';
				$output .= '</form>';
				$result['code'] = 200;
				$result['output'] = $output;
				
				// Add portal activity
				$activity_info = $notif['title'].';';
				$this->activity->add_activity_log('View Notification', 'portal', $activity_info);
			}
			else
			{
				$result['code'] = 400;
				$result['message'] = 'Notification not found.';
			}
		}
		else
		{
			$result['code'] = 400;
			$result['message'] = 'Errors occured while getting notification info. Please try again later.';
		}
		print_r(json_encode($result));
	} // process_get_notification_data
	
	public function process_delete_user_notification()
	{
		$notif_id = $this->input->get_post('id', TRUE);
		if ($notif_id)
		{
			
			//$this->notifications->delete_user_notification($this->_user['user_id'], $notif_id);
			$this->notifications->mark_as_deleted($this->_user['user_id'], $notif_id);
			
			// Add portal activity
			$notif = $this->notifications->get_notifications(
				array(
					'from' => array(
						$this->notifications_table_name => 'n'
					),
					'where' => array(
						'n.id' => $notif_id
					),
					'join' => array(
						array(
							'table' => $this->notifications_users_table_name.' AS nu',
							'condition' => 'nu.notification_id = n.id AND nu.user_id = '.$this->_user['user_id']
						)
					)
				)
			);
			$activity_info = $notif[0]['title'].';';
			$this->activity->add_activity_log('Delete User Notification', 'both', $activity_info);
			
			$result['code'] = 200;
			$result['message'] = 'Notification deleted successfully.';
			echo json_encode($result);
			return;
		}
		
		$result['code'] = 400;
		$result['message'] = 'Errors occured while deleting notification. Please try again later.';
		echo json_encode($result);		
	}
	
	/*
	* Get delete notification by id from table
	*/
	public function delete_notification_by_id()
	{
	}
	
	public function process_new_case_registration()
	{
	}
	
	
	public function get_ext_attorneys() 
	{
		$this->load->library('mshc_connector');
		$ext_dbs = $this->mshc_connector->getDBArray();
		
		if (count($ext_dbs) == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = '';
			print_r(json_encode($result));
			return;
		}
		
		$ext_attorneys = $this->firms->get_ext_attorneys();
		
		if (count($ext_attorneys) == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = '';
			print_r(json_encode($result));
			return;
		}
		
		if ($ext_attorneys['attorneys']) {
			$ext_attys_ul = array();
			foreach($ext_attorneys['attorneys'] as $ext_atty)
			{
				if (!array_key_exists($ext_atty['database_name'], $ext_attys_ul))
				{
					$ext_attys_ul[$ext_atty['database_name']] = array();
				}
				$ext_attys_ul[$ext_atty['database_name']][] = array(
					'employer_name' => $ext_atty['employer_name'],
					'employer_id' => $ext_atty['employer_id']
				);
			}

			$attorneys_list = $ext_attys_ul;
		} else {
			$attorneys_list = '<em>'.$ext_attorneys['error'].'</em>';
		}
		
		$result['Result'] = 'OK';
		$result['TotalRecordCount'] = count($ext_attorneys['attorneys']);
		$result['Records'] = $attorneys_list;
		$result['extDBs'] = $ext_dbs;
		print_r(json_encode($result));
	}


	public function send_email_with_number_phone()
	{
		$code_phone = $this->input->post('sCodePhone', true);
		$station_code = $this->input->post('sStationCode', true);
		$number_phone = $this->input->post('sNumberPhone', true);
		
		if (strlen($code_phone) == 3 && strlen($station_code) == 3 && strlen($number_phone) == 4) 
		{
			$now = time();
			//$now = strtotime(date('Y-m-d H:i:s', strtotime('+9 hours')));
			$dow = date('w', $now);
			$time = date('H:i', $now);

			switch ($dow)
			{
				case 1:
				case 2:
				case 3:
				case 4:
					if (strtotime($time) >= strtotime('8:30') && strtotime($time) <= strtotime('17:00'))
					{
						$msg = 'main';
					}
					else
					{
						$msg = 'alt';
					}
					break;
				case 5:
					if (strtotime($time) >= strtotime('8:00') && strtotime($time) <= strtotime('16:30'))
					{
						$msg = 'main';
					}
					else
					{
						$msg = 'alt';
					}
					break;
				default: $msg = 'alt'; break;
			}
			
			if ($msg == 'main')
			{
				$message = $this->load->view(
					'email/callback_working', 
					array(
						'code_phone' => $code_phone,
						'station_code' => $station_code,
						'number_phone' => $number_phone
					),
					TRUE
				);
			}
			else
			{
				$message = $this->load->view(
					'email/callback_holiday', 
					array(
						'code_phone' => $code_phone,
						'station_code' => $station_code,
						'number_phone' => $number_phone
					),
					TRUE
				);
			}
						
			$this->load->library('mshc_general');
			$send_to = '218@telerep.com';
			
			$this->mshc_general->send_call_me($send_to, $message);
			
			$data = 'Please wait while we connect you.';
			
			$activity_info = 'Phone Number: '.implode('-',array($code_phone,$station_code,$number_phone)).';';
			$this->activity->add_activity_log('Callback Request', 'both', $activity_info);
		} else {
			$data = 'Please enter correct phone number.';
		}
		echo $data;
	}
	
	
	/*
	* Get cases managers table for admin
	*/
	public function get_cases_managers_table()
	{
		$data = $this->input->get(NULL, TRUE);
		$result = array();
		$conds = array();

		// short search parameters
		$firm_id = $this->input->post('sFirmID', true);
		$atty_id = $this->input->post('sAttyID', true);
		$case_manager_id = $this->input->post('sUserID', true);

		if ($firm_id == 0 && $atty_id == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}
		
		$cases_type = $this->input->post('sCasesType', true);
		$select_from = $this->input->post('sSelectFrom', true);
		$filterName = $this->input->post('sName', true);
		
		if (isset($filterName) && $filterName != '') {
			$conds['name'] = $filterName;
		}
		
		if (isset($select_from) && $select_from != '') {
			switch ($select_from) {
				case 'dos_60':
					$service_date_between['value'][] = date('m/d/Y', strtotime('-60 days'));
					$service_date_between['value'][] = date('m/d/Y');
					$conds['service_date_between'] = $service_date_between;
					$conds['service_date_between']['op'] = 'BETWEEN';
					break;
				case 'dos_30':
					$service_date_between['value'][] = date('m/d/Y', strtotime('-30 days'));
					$service_date_between['value'][] = date('m/d/Y');
					$conds['service_date_between'] = $service_date_between;
					$conds['service_date_between']['op'] = 'BETWEEN';
					break; 
				case 'doa_60':
					$accident_date_between['value'][] = date('m/d/Y', strtotime('-60 days'));
					$accident_date_between['value'][] = date('m/d/Y');
					$conds['accident_date_between'] = $accident_date_between;
					$conds['accident_date_between']['op'] = 'BETWEEN';
					break;
				case 'doa_30':
					$accident_date_between['value'][] = date('m/d/Y', strtotime('-30 days'));
					$accident_date_between['value'][] = date('m/d/Y');
					$conds['accident_date_between'] = $accident_date_between;
					$conds['accident_date_between']['op'] = 'BETWEEN';
					break; 
			}
		}
		
		if ($firm_id != '' && $firm_id != 0) {
			$params = array(
				'fields' => array(
					'edla.ext_db_id, edla.external_id, edla.ext_db_name ' => ''
				),
				'join' => array(
					array(
						'table' => $this->ext_dbs_legal_attys_table_name.' AS edla',
						'condition' => $this->legal_attorneys_table_name.'.id = edla.legal_atty_id'
					)
				),
				'where' => array(
					$this->legal_attorneys_table_name.'.legal_firm_id' => $firm_id
				),
				'group' => array('edla.ext_db_id', 'edla.external_id')
			);
			$attorney_ids = $this->firms->get_attorneys($params);
		} else {
			$params = array(
				'fields' => array(
					'edla.ext_db_id, edla.external_id, lau.user_id, edla.ext_db_name ' => ''
				),
				'join' => array(
					array(
						'table' => $this->legal_attorneys_users_table_name.' AS lau',
						'condition' => $this->legal_attorneys_table_name.'.id = lau.legal_atty_id'
					),
					array(
						'table' => $this->ext_dbs_legal_attys_table_name.' AS edla',
						'condition' => 'lau.legal_atty_id = edla.legal_atty_id'
					)
				),
				'where' => array(
					$this->legal_attorneys_table_name.'.id' => $atty_id
				),
				'group' => array('edla.ext_db_id', 'edla.external_id')
			);
			$attorney_ids = $this->firms->get_attorneys($params);
		}
		
		if (count($attorney_ids) == 0) {
			$result['Result'] = 'OK';
			$result['TotalRecordCount'] = 0;
			$result['Records'] = array();
			print_r(json_encode($result));
			return;
		}
		
		$limit[] = 500;
		$limit[] = 0;
		$order = $this->get_order_by_post($data);
		
		$this->load->library('mshc_connector');
		$fields = array(
			'first_name', 
			'last_name', 
			'middle_name', 
			'account', 
			'ssn', 
			'accident_date', 
			'db_name', 
			'attorney_name', 
			'attorney_id', 
			'status', 
			'case_category', 
			'patient', 
			'case_no', 
			'practice'
		);
		
		$db_arrays = $this->mshc_connector->getDBArray();
		
		if ($cases_type == 'unassigned' && count($attorney_ids) > 0) {
			$conds['attorney_id'] = array(
				'op' => 'or',
				'value' => array()
			);

			foreach ($attorney_ids as $atty) 
			{
				$conds['attorney_id']['value'][] = array(
					'attorney_id' => $atty['external_id'],
					'database' => $atty['ext_db_name']
				);
			}
			
			$params = array(
				'fields' => array(
					'lfu2.user_id' => ''
				),
				'from' => array(
					$this->legal_firms_users_table_name => 'lfu',
					$this->users_table_name => 'u'
				),
				'join' => array(
					array(
						'table' => $this->legal_firms_users_table_name.' AS lfu2',
						'condition' => 'lfu2.legal_firm_id = lfu.legal_firm_id'
					)
				),
				'group' => array(
					'lfu2.user_id'
				)
			);

			if ($firm_id == '' || $firm_id == 0) {
				$firm = $this->firms->get_firms(array(
					'fields' => array(
						'la.legal_firm_id' => ''
					),
					'from' => array(
						$this->legal_attorneys_table_name => 'la'
					),
					'where' => array(
						'la.id' => $atty_id
					)
				));
				
				$firm_id = $firm[0]['legal_firm_id'];
			}
			
			$params['where'] = array(
				'lfu.user_id' => $case_manager_id,
				'u.role_id' => MSHC_AUTH_CASE_MANAGER,
				'lfu.legal_firm_id' => $firm_id
			);

			$firms_users = $this->firms->get_firms($params);
			$users_in = array();
			$assigned_cases = array();

			if (count($firms_users)) {
				foreach ($firms_users as $user)
				{
					$users_in[] = $user['user_id'];
				}
				$assigned_cases = $this->firms->get_assigned_cases(array('where_in' => array('user_id' => $users_in)));
			}
			
			if (count($assigned_cases)) {
				$conds['cases'] = array(
					'op' => 'exclude',
					'value' => array()
				);
				foreach ($assigned_cases as $case)
				{
					$conds['cases']['value'][] = array(
						'db_name' => $db_arrays[$case['ext_db_id']],
						'account' => $case['external_id1'],
						'practice' => $case['external_id2'],
						'case_no' => $case['external_id3'],
						'patient' => $case['external_id4']
					);
				}
			}
			
			$cases = $this->mshc_connector->getCases(
			    array(1,2,3,4,5),
                'all',
                array('fields' => $fields, 'conds' => $conds, 'order' => $order, 'limit' => $limit, 'debugReturn' => 'sample_all')
            );

			if (count($cases) > 0) {
                foreach ($cases as &$item)
                {
                    $doa = $item['accident_date'];
                    if ($doa instanceof DateTime) {
                        $item['accident_date'] = $doa->format('m/d/Y');
                    }
                }

				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = count($cases);
				$result['Records'] = $cases;
			} else {
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
			}

			print_r(json_encode($result));
		} else if ($cases_type == 'assigned' && count($attorney_ids) > 0) {
			$assigned_cases = $this->firms->get_assigned_cases(array('where' => array('user_id' => $case_manager_id)));
			
			if (count($assigned_cases)) {
				$conds['attorney_id'] = array(
					'op' => 'or',
					'value' => array()
				);

				foreach ($attorney_ids as $atty) 
				{
					$conds['attorney_id']['value'][] = array(
						'attorney_id' => $atty['external_id'],
						'database' => $atty['ext_db_name']
					);
				}

				$conds['cases'] = array(
					'op' => 'include',
					'value' => array()
				);

				foreach ($assigned_cases as $case)
				{
					$conds['cases']['value'][] = array(
						'db_name' => $db_arrays[$case['ext_db_id']],
						'account' => $case['external_id1'],
						'practice' => $case['external_id2'],
						'case_no' => $case['external_id3'],
						'patient' => $case['external_id4']
					);
				}

				$cases = $this->mshc_connector->getCases(
					array(1,2,3,4,5), 
					'all', 
					array(
						'fields' => $fields, 
						'conds' => $conds, 
						'limit' => $limit, 
						'order' => $order, 
						'debugReturn' => 'sample_all'
					)
				);

                foreach ($cases as &$item)
                {
                    $doa = $item['accident_date'];
                    if ($doa instanceof DateTime) {
                        $item['accident_date'] = $doa->format('m/d/Y');
                    }
                }

				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = count($cases);
				$result['Records'] = $cases;
			} else {
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
			}

			print_r(json_encode($result));
		} else if ($cases_type == 'all') {
			$conds['attorney_id'] = array(
				'op' => 'or',
				'value' => array()
			);

			foreach ($attorney_ids as $atty) 
			{
				$conds['attorney_id']['value'][] = array(
					'attorney_id' => $atty['external_id'],
					'database' => $atty['ext_db_name']
				);
			}

			$cases = $this->mshc_connector->getCases(
				array(1,2,3,4,5), 
				'all', 
				array(
					'fields' => $fields, 
					'conds' => $conds, 
					'order' => $order, 
					'limit' => $limit, 
					'debugReturn' => 'sample_all'
				)
			);
			
			if (count($cases) > 0) {
				for ($i = 0; $i < count($cases); $i++) 
				{
					$db_key = 0;
					foreach ($db_arrays as $key => $value) 
					{
						if ($value == strtoupper($cases[$i]['db_name'])) $db_key = $key;
					}
					$params['where'] = array(
						'ext_db_id' => $db_key, 
						'external_id1' => $cases[$i]['account'], 
						'external_id2' => $cases[$i]['practice'], 
						'external_id3' => $cases[$i]['case_no'], 
						'external_id4' => $cases[$i]['patient']
					);
					

					$cases_managers_assign = $this->firms->get_assigned_cases($params);
                    $cases[$i]['assigned'] = count($cases_managers_assign) ? 1 : 0;

                    $doa = $cases[$i]['accident_date'];
                    if ($doa instanceof DateTime) {
                        $cases[$i]['accident_date'] = $doa->format('m/d/Y');
                    }
				}
				
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = count($cases);
				$result['Records'] = $cases;
			} else {
				$result['Result'] = 'OK';
				$result['TotalRecordCount'] = 0;
				$result['Records'] = array();
			}

			print_r(json_encode($result));
		}
	}
	
	/*
	* Assign cases managers table for admin
	*/
	public function cases_managers()
	{
		$keys_array = $this->input->post('sArrayKeys', true);
		$type_action = $this->input->post('sType', true);
		$case_manager_id = $this->input->post('sUserID', true);
		
		$params = array(
			'from' => array(
				$this->users_table_name =>  'u'
			),
			'where' => array(
				'u.id' => $case_manager_id
			),
			'join' => array(
				array(
					'table' => $this->legal_users_table_name.' AS lu',
					'condition' => 'lu.user_id = u.id',
					'type' => 'left'
				)
			)
		);
		$user_data = $this->users->get_users($params);
		$array_cases = array();

		if (is_array($keys_array)) {
			foreach ($keys_array as $keys_params)
			{
				$array_cases[] = explode('|||', $keys_params);
			}
		}

		$this->load->library('mshc_connector');
		$db_arrays = $this->mshc_connector->getDBArray();
		
		foreach($array_cases as $cases)
		{
			$db_key = 0;
			foreach ($db_arrays as $key => $value)
			{
				if ($value == strtoupper($cases[1])) {
                    $db_key = $key;
                }
			}

			$log_type = 'Assign';
			if ($case_manager_id) {
				if ($type_action == 'assigned') {
				    // add relation
					$data_record['ext_db_id'] = $db_key;
					$data_record['user_id'] = $case_manager_id;
					$data_record['external_id1'] = $cases[2]; // account
					$data_record['external_id2'] = $cases[3]; // practice
					$data_record['external_id3'] = $cases[4]; // case_no
					$data_record['external_id4'] = $cases[5]; // patient
					$this->firms->add_legal_cases_legal_case_mgrs($data_record);
				}

				if ($type_action == 'unassigned') {
					// delete relation
					$params['where'] = array(
						'ext_db_id' => $db_key,
						/*'user_id' => $case_manager_id,*/
						'external_id1' => $cases[2],
						'external_id2' => $cases[3],
						'external_id3' => $cases[4],
						'external_id4' => $cases[5]
					);
					$log_type = 'Unassign';
					$this->firms->delete_legal_cases_legal_case_mgrs($params);
				}
				
				// Add portal activity
				$activity_info = 'Account: '.$cases[2].'; Case Manager: '.$user_data[0]['last_name'].', '.$user_data[0]['first_name'].';';
				$this->activity->add_activity_log($log_type.' Case Manager', 'portal', $activity_info);
			}
		}
	}
	
	public function get_near_locations()
	{
		$zip_code = $this->input->post('zipCode', true);
		$radius = $this->input->post('radius', true);
		
		$this->db->from($this->zip_codes_table_name);
		$this->db->where('id', $zip_code);
		$query = $this->db->get();
		
		if ($query->num_rows()) {
			$needle = $query->row_array();
			
			$this->load->library('mshc_connector');
			$queryParams = array(
				'conds' => array(
					'is_active' => 1,
				),
				'group' => array('display_name', 'zip_code'),
				'debugReturn' => 'sample',
			);
			$locations_list = $this->mshc_connector->getLocationNames(array(1,2,3,4,5), 'all', $queryParams);
            $tmp_tbl = time();

			if (count($locations_list)) {
				$this->db->query('
					CREATE TABLE IF NOT EXISTS `'.$tmp_tbl.'` (
						`display_name` text NOT NULL,
						`zip_code` char(5) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8
				');
				$this->db->insert_batch($tmp_tbl, $locations_list);
			}
			
			$this->db->select('
				tmp.display_name AS display_name, 
				zc.id AS zip,
				(3959 * acos(cos(radians('.
				$needle['latitude'].')) * cos(radians(zc.latitude) ) * cos(radians(zc.longitude) - radians('.
				$needle['longitude'].')) + sin(radians('.
				$needle['latitude'].')) * sin(radians(zc.latitude)))) AS distance
			');
			$this->db->from($this->zip_codes_table_name.' AS zc');
			$this->db->join($tmp_tbl.' AS tmp', 'tmp.zip_code = zc.id');
			$this->db->having('distance <=', $radius);
			$this->db->order_by('distance', 'ASC');
			$query = $this->db->get();
			$result = $query->result_array();

			$this->db->query('DROP TABLE `'.$tmp_tbl.'`');
			
			$locations = array();
			$locations[] = '--- Not selected ---';
			foreach ($result as $loc)
			{
				$locations[] = $loc['display_name'];//.' (Distance: '.round($loc['distance']).' miles)';
			}
			print_r(json_encode($locations));
		}
		else
		{
			$locations = array();
			$locations[] = '--- Not selected ---';
			print_r(json_encode($locations));
		}
	}
	
	public function get_all_locations()
	{
		$this->load->library('mshc_connector');
		$queryParams = array(
			'conds' => array(
                'is_active' => 1,
            ),
			'group' => array('display_name'),
            'debugReturn' => 'sample',
		);
		$locations_list = $this->mshc_connector->getLocationNames(array(1,2,3,4,5), 'all', $queryParams);
		
		$locations = array();
		$locations[] = '--- Not selected ---';
		foreach ($locations_list as $loc)
		{
			$locations[] = $loc['display_name'];
		}
		print_r(json_encode($locations));
	}
	
	public function get_ext_attys_by_database()
	{
		$db = $this->input->post('db', true);
		$ext_attorneys = $this->firms->get_ext_attorneys(
			0, 
			array(
				'order' => array(
					'database_name', 
					'employer_name'
				),
				'debugReturn' => 'sample'
			),
			array($db)
		);
		
		$db_attorneys_tree = "";
		if (count($ext_attorneys)) {
			$ext_attys_ul = array();

			foreach($ext_attorneys['attorneys'] as $ext_atty)
			{
				$db_attorneys_tree .= '<div class="fnExtAtty"><div class="check_off" data="{ext_atty_id:'
					.$ext_atty['employer_id'].', ext_db_id:'.$db.', ext_atty_name: \''
					.$ext_atty['employer_name'].'\', ext_db_name: \''.$ext_atty['database_name'].'\'}">'
					.$ext_atty['employer_name'].'</div></div>';
				$ext_attys_ul[$ext_atty['database_name']][] = array(
					'employer_name' => $ext_atty['employer_name'],
					'employer_id' => $ext_atty['employer_id']
				);
			}
		}
		echo $db_attorneys_tree;
	}
	
	public function create_notifications()
	{
		$date_from = $this->input->post('dateFrom', true);
		$date_to = $this->input->post('dateTo', true);
		$users_list = $this->input->post('usersList', true);
		/** @var array $notifications_types */
		$notifications_types = $this->input->post('notifTypes', true);

		ini_set('memory_limit','1024M');
		//error_reporting(E_ALL);
		set_time_limit(0);
        //$start = microtime(TRUE);
		
		$_begin = date('Y-m-d', strtotime($date_from)).' 00:00:00';
		$_end = date('Y-m-d', strtotime($date_to)).' 23:59:59';
		
		$this->db->query('
			delete from notifications_users 
			where user_id in ('.implode(',', $users_list).') 
			and notification_id in 
				(select id 
				from notifications 
				where type in ('.implode(',', $notifications_types).') 
				and date_format(notification_date, \'%Y-%m-%d\') between \''.$_begin.'\' and \''.$_end.'\')'
		);
		
		$this->db->query('delete from notifications where id not in (select distinct(notification_id) from notifications_users)');
		
		$result = array();
		$this->load->library('mshc_general');
		$this->load->library('mshc_connector');

		if (in_array('\'missed_apt\'', $notifications_types)) {
			$missed_appts = $this->mshc_general->create_missed_appts_notifications($_begin, $_end, $users_list);

			if (is_array($missed_appts)) {
				$counter = $missed_appts[1];
				$result[] = 'Total Missed Appts: '.$missed_appts[0];
			} else {
				$counter = $missed_appts;
			}
			$result[] = 'Missed Appt Alerts Created: '.$counter;
		}

        $docs = array(0, 0);

		if (in_array('\'docs\'', $notifications_types)) {
			$docs = $this->mshc_general->create_documents_notifications($_begin, $_end, $users_list);
			if (is_array($docs)) {
				$result[] = 'Total Docs: '.$docs[0];
				$counter = $docs[1];
			} else {
				$counter = $docs;
			}
			$result[] = 'New Docs Alerts Created: '.$counter;
		}
		
		if (in_array('\'discharged\'', $notifications_types)) {
			$discharge = $this->mshc_general->create_discharged_notifications($_begin, $_end, $users_list);
			if (is_array($discharge)) {
				$result[] = 'Total Discharged: '.$discharge[0];
				$counter = $discharge[1];
			} else {
				$counter = $docs;
			}
			$result[] = 'Discharged Alerts Created: '.$counter;
		}
		
		if (in_array('\'high_charge\'', $notifications_types)) {
			$result[] = 'High Charge Alerts Created: '.$this->mshc_general->create_high_charges($_begin, $_end, $users_list);
		}
		
		echo json_encode($result);
	}

    /*
     * Update legal user data via AJAX request
	 */
    public function process_update_legal_user()
    {
        $data = $this->input->get_post('data', TRUE);

        $this->users->update_legal_user($this->_user['user_id'], $data);
    }

    /*
	* Search cases for contact form
	*/
    public function contact_cases_search()
    {
        $result = array();

        // short search parameters
        $client_cases_name = $this->input->post('name', true);
        $client_cases_account = $this->input->post('account', true);

        $client_cases_attys = array();

        $this->load->library('mshc_connector');
        $fields = array('first_name', 'last_name', 'middle_name', 'account', 'ssn', 'accident_date', 'db_name', 'attorney_name', 'attorney_id', 'status', 'case_category', 'patient', 'case_no', 'practice');
        $conds = array();

        if ($client_cases_name != '') {
            $conds['name'] = $client_cases_name;
        }

        if ($client_cases_account != '') {
            $conds['account'] = $client_cases_account;
        }

        $dbs = $this->mshc_connector->getDBArray();

        $attorneys_list = array();
        $join_conds = '';
        /*if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
            $join_conds = ' AND edla.ext_db_id = 3';
        }*/

        if (count($client_cases_attys)) {
            $params = array(
                'fields' => array(
                    'la.*' => '',
                    'edla.*' => ''
                ),
                'from' => array(
                    $this->legal_attorneys_table_name => 'la'
                ),
                'join' => array(
                    array(
                        'table' => $this->ext_dbs_legal_attys_table_name.' AS edla',
                        'condition' => ' la.id = edla.legal_atty_id'.$join_conds
                    )
                )
            );
            $where_or = '';
            foreach ($client_cases_attys as $k=>$v) {
                if ($where_or != '') $where_or .= ' OR ';
                $where_or .= ' la.id = '.$v;
            }
            $params['where'][$where_or] = '';
            //print_r($params);
            $attorneys_list = $this->firms->get_attorneys($params, FALSE);
            //echo $this->db->last_query();
        } elseif ($this->_user['role_id'] == MSHC_AUTH_BILLER || $this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            /*$attys = $this->mshc_connector->getAttorneys(
                array(1,2,3,4,5),
                'all',
                array(
                    'order' => array('employer_name'),
                    'debugReturn' => 'sample',
                )
            );

            if ($attys && count($attys)) {
                foreach ($attys as $atty)
                {
                    $attorneys_list[] = array(
                        'external_id' => $atty['employer_id'],
                        'ext_db_id' => array_search(strtoupper($atty['database_name']), $dbs)
                    );
                }
            }*/
        } else {
            $this->load->library('mshc_general');
            $attorneys_list = $this->mshc_general->getUserAttorneys();
        }

        if (count($attorneys_list) == 0 && $this->_user['role_id'] != MSHC_AUTH_BILLER && $this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
            $result['list'] = array();
            print_r(json_encode($result));
            return;
        }

        if (count($attorneys_list) > 0) {
            $conds['attorney_id'] = array(
                'op' => 'or',
                'value' => array()
            );
            foreach ($attorneys_list as $atty)
            {
                $conds['attorney_id']['value'][] = array(
                    'attorney_id' => $atty['external_id'],
                    'database' => $dbs[$atty['ext_db_id']]
                );
            }
        }

        $total_cases = $this->mshc_connector->getCases(array(1,2,3,4,5), 'count', array('fields' => $fields, 'conds' => $conds, 'debugReturn' => 'sample_all'));
        if (is_array($total_cases) && $total_cases['count'] > 1000) {
            $result['list'] = array();
            print_r(json_encode($result));
            return;
        }

        $cases = $this->mshc_connector->getCases(
            array(1,2,3,4,5),
            'all',
            array(
                'fields' => $fields,
                'conds' => $conds,
                'debugReturn' => 'sample_all'
            )
        );

        foreach ($cases as &$item)
        {
            if ($item['accident_date'] instanceof DateTime) {
                $item['accident_date'] = $item['accident_date']->format('m/d/Y');
            } else {
                $item['accident_date'] = 'N/A';
            }
        }

        if (is_array($cases) && count($cases) > 0) {
            $result['list'] = $cases;
        } else {
            $result['list'] = array();
        }

        print_r(json_encode($result));
    }
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */