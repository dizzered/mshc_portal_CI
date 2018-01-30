<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Converter extends MSHC_Controller 
{
    public function __construct() 
    {
        parent::__construct();
	}

	/*
	* Index Page for this controller.
	*/
	public function users()
	{
		$this->load->model('users');
		
		$this->db->from('user_details AS ud');
		$this->db->order_by('ud.CreationDate', 'ASC');
		$this->db->join('user_rights AS ur', 'ur.UserId = ud.UserId', 'left');
		$this->db->join('user_notification AS un', 'un.UserId = ud.UserId', 'left');
		$this->db->join('aspnet_users AS au', 'au.UserId = ud.UserId', 'left');
		$this->db->join('aspnet_membership AS am', 'am.UserId = ud.UserId', 'left');
		$this->db->join('aspnet_usersinroles AS url', 'url.UserId = ud.UserId', 'left');
		$this->db->join('aspnet_roles AS ar', 'ar.RoleId = url.RoleId', 'left');
		$query = $this->db->get();
		$result = $query->result_array();
		$data = array();
		foreach ($result as $user)
		{
			$role_id = '';
			switch ($user['LoweredRoleName'])
			{
				case 'system': $role_id = MSHC_AUTH_SYSTEM_ADMIN; break;
				case 'attorney': $role_id = MSHC_AUTH_ATTORNEY; break;
				case 'casemanager': $role_id = MSHC_AUTH_CASE_MANAGER; break;
			}
			$data[] = array(
				'old_id' => $user['UserId'],
				'first_name' => $user['FirstName'],
				'last_name' => $user['LastName'],
				'is_inactive' => $user['IsInactive'] == 0 ? 0 : 1,
				'created' => $user['CreationDate'],
				'modified' => $user['LastModifiedDate'],
				'username' => $user['LoweredUserName'],
				'email' => $user['LoweredEmail'],
				'comment' => $user['Comment'],
				'password' => $this->users->_secure_password($user['LoweredUserName']),
				'last_password_changed_date' => $user['LastPasswordChangedDate'],
				'last_login_date' => $user['LastLoginDate'],
				'last_activity_date' => $user['LastActivityDate'],
				'failed_password_attempt_count' => $user['FailedPasswordAttemptCount'],
				'is_locked_out' => $user['IsLockedOut'] == 0 ? 0 : 1,
				'last_lockout_date' => $user['LastLockoutDate'],
				'maintain_marketers_allowed' => $user['Maintain Marketers'] == 0 ? 0 : 1,
				'maintain_practices_allowed' => $user['Maintain Practices'] == 0 ? 0 : 1,
				'maintain_users_allowed' => $user['Maintain Users'] == 0 ? 0 : 1,
				'view_portal_activity_logs_allowed' => $user['View Audit Log'] == 0 ? 0 : 1,
				'role_id' => $role_id
			);
		}
		$this->db->insert_batch('users', $data);
		echo count($data).' users migrated.';
		
	}
	
	public function users_creation()
	{
		
		$this->db->from('user_details AS ud');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach ($result as $user)
		{
			$new_id = $this->get_id_by_old($user['UserId']);
			if ($new_id)
			{
				$created_by = $this->get_id_by_old($user['CreationUser']);
				$modified_by = $this->get_id_by_old($user['LastModifiedUser']);			
				$data = array(
					'created_by' => $created_by,
					'modified_by' => $modified_by,
				);
				$this->db->where('id', $new_id);
				$this->db->update('users', $data);
			}
		}
		
		echo 'Users updated.';
		
	}
	
	public function users_notifications()
	{		
		$this->db->from('user_details AS ud');
		$this->db->order_by('ud.CreationDate', 'ASC');
		$this->db->join('user_rights AS ur', 'ur.UserId = ud.UserId', 'left');
		$this->db->join('user_notification AS un', 'un.UserId = ud.UserId', 'left');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';
		$data = array();
		foreach ($result as $user)
		{
			$user_id = $this->get_id_by_old($user['UserId']);
			if ($user_id)
			{
				$data[] = array(
					'user_id' => $user_id,
					'missed_appointments_notified' => $user['MissedAppointments'] == 0 ? 0 : 1,
					'case_discharge_notified' => $user['CaseDischarge'] == 0 ? 0 : 1,
					'medical_report_notified' => $user['MedicalReport'] == 0 ? 0 : 1,
					'pt_note_notified' => $user['PTNote'] == 0 ? 0 : 1,
					'outside_medical_record_notified' => $user['OutsideMedicalRecord'] == 0 ? 0 : 1,
					'consult_notified' => $user['Consult'] == 0 ? 0 : 1,
					'ptbwr_referral_notified' => $user['PTBWRReferral'] == 0 ? 0 : 1,
					'disability_notified' => $user['Disability'] == 0 ? 0 : 1,
					'pharmacy_notified' => $user['Pharmacy'] == 0 ? 0 : 1,
					'maintain_attorneys_allowed' => $user['Maintain Attorneys'] == 0 ? 0 : 1,
					'maintain_firms_allowed' => $user['Maintain Firms'] == 0 ? 0 : 1,
					'register_cases_allowed' => $user['Register Cases'] == 0 ? 0 : 1,
					'view_cases_for_firm_allowed' => $user['View Cases For Firm'] == 0 ? 0 : 1,
					'view_own_cases_allowed' => $user['View Own Cases'] == 0 ? 0 : 1,
				);
			}
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_users', $data);
		echo count($data).' legal users migrated.';
	}
	
	public function users_legal()
	{
		/*$this->db->select('lu.*');
		$this->db->from('legal_users as lu');
		$this->db->where('not exists (select u.id from users as u where u.id = lu.user_id)', NULL, FALSE);
		$query = $this->db->get();*/
		
		$this->db->select('u.*');
		$this->db->from('users as u');
		$this->db->where('not exists (select lu.user_id from legal_users as lu where lu.user_id = u.id)', NULL, FALSE);
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';
		
		$data = array();
		foreach ($result as $user)
		{
			$data[] = array(
				'user_id' => $user['id'],
				'missed_appointments_notified' => 0,
				'case_discharge_notified' => 0,
				'medical_report_notified' => 0,
				'pt_note_notified' => 0,
				'outside_medical_record_notified' => 0,
				'consult_notified' => 0,
				'ptbwr_referral_notified' => 0,
				'disability_notified' => 0,
				'pharmacy_notified' => 0,
				'maintain_attorneys_allowed' => 0,
				'maintain_firms_allowed' => 0,
				'register_cases_allowed' => 0,
				'view_cases_for_firm_allowed' => 0,
				'view_own_cases_allowed' => 0,
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';return;
		$this->db->insert_batch('legal_users', $data);
		echo count($data).' legal users created.';
	}
	
	public function firms()
	{		
		$this->db->from('firms');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $firm)
		{
			$created_by = $this->get_id_by_old($firm['CreationUser']);
			$modified_by = $this->get_id_by_old($firm['LastModifiedUser']);
			$data[] = array(
				'id' => $firm['FirmId'],
				'name' => $firm['Name'],
				'is_inactive' => $firm['IsInactive'] == 0 ? 0 : 1,
				'created' => $firm['CreationDate'],
				'created_by' => $created_by,
				'modified' => $firm['LastModifiedDate'],
				'modified_by' => $modified_by,
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_firms', $data);
		echo count($data).' legal firms migrated.';
	}
	
	public function users_firms()
	{
		$this->db->from('user_firms');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $firm)
		{
			$user_id = $this->get_id_by_old($firm['UserId']);
			$data[] = array(
				'legal_firm_id' => $firm['FirmId'],
				'user_id' => $user_id,
				'is_primary' => $firm['IsPrimary'] == 0 ? 0 : 1,
				'all_attorneys' => $firm['AllAttorneys'] == 0 ? 0 : 1,
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_firms_users', $data);
		echo count($data).' legal firms users migrated.';
	}
	
	public function attys()
	{		
		$this->db->from('attorneys AS a');
		$this->db->join('attorney_firms AS af','af.AttorneyId = a.AttorneyId', 'left');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $atty)
		{
			$created_by = NULL;
			$modified_by = NULL;
			if ($atty['CreationUser']) $created_by = $this->get_id_by_old($atty['CreationUser']);
			if ($atty['LastModifiedUser']) $modified_by = $this->get_id_by_old($atty['LastModifiedUser']);
			$data[] = array(
				'id' => $atty['AttorneyId'],
				'first_name' => $atty['FirstName'],
				'last_name' => $atty['LastName'],
				'is_inactive' => $atty['IsInactive'] == 0 ? 0 : 1,
				'created' => $atty['CreationDate'],
				'created_by' => $created_by,
				'modified' => $atty['LastModifiedDate'],
				'modified_by' => $modified_by,
				'legal_firm_id' => $atty['FirmId'],
				'missed_appointment_notification_delivery_method' => $atty['MissedAppointmentNotificationDeliveryMethod'],
				'missed_appointment_threshold' => $atty['MissedAppointmentThreshold'],
				'statement_delivery_method' => $atty['StatementDeliveryMethod'],
				'statement_frequency' => $atty['StatementFrequency'],			
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_attys', $data);
		echo count($data).' legal attorneys migrated.';
	}
	
	public function users_attys()
	{
		$this->db->from('user_attorneys');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $atty)
		{
			$user_id = $this->get_id_by_old($atty['UserId']);
			$data[] = array(
				'legal_atty_id' => $atty['AttorneyId'],
				'user_id' => $user_id,
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_attys_users', $data);
		echo count($data).' legal attorneys users migrated.';
	}
	
	public function attys_external()
	{
		$this->load->library('mshc_connector');
		$this->db->from('attorney_micromd');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $atty)
		{
			$ext_db = explode('/',$atty['MmdDbKey']);
			if (count($ext_db)) $ext_db_name = strtoupper($ext_db[1]);
			else $ext_db_name = strtoupper($atty['MmdDbKey']);
			$data[] = array(
				'legal_atty_id' => $atty['AttorneyId'],
				'ext_db_name' => $ext_db_name,
				'ext_db_id' => array_search($ext_db_name, $this->mshc_connector->getDBArray()),
				'external_id' => $atty['MmdAttorneyId'],
				'external_atty_name' => $atty['MmdAttorneyName'],
			);
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('ext_dbs_legal_attys', $data);
		echo count($data).' legal external attorneys migrated.';
	}
	
	public function users_firms_attys()
	{
		$this->db->select('lfu.*');
		$this->db->from('legal_firms_users as lfu');
		$this->db->where('lfu.all_attorneys', 1);
		$this->db->order_by('lfu.user_id', 'ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		foreach ($result as $user_firm)
		{
			$this->db->where('la.legal_firm_id', $user_firm['legal_firm_id']);
			$this->db->where('la.id not in (select lau.legal_atty_id from legal_attys_users as lau where lau.user_id = '.$user_firm['user_id'].')', NULL, FALSE);
			$query = $this->db->get('legal_attys as la');
			//echo '<pre>'.print_r($query->result_array(), true).'</pre>';
			$attys = $query->result_array();
			foreach ($attys as $atty)
			{
				$data[] = array(
					'legal_atty_id' => $atty['id'],
					'user_id' => $user_firm['user_id'],
				);
			}
		}
		//echo '<pre>'.print_r($data, true).'</pre>';//return;
		$this->db->insert_batch('legal_attys_users', $data);
		echo count($data).' legal users attorneys migrated.';
	}
	
	public function case_managers()
	{
		$this->db->select(
			'cmc.*, 
			u.id AS user_id,
			IF(cmc.DatabaseAlias = "LIVE", "AMM_LIVE", cmc.DatabaseAlias) AS db_name',
			FALSE
		);
		$this->db->from('casemanager_cases AS cmc');
		$this->db->join('users AS u', 'u.old_id = cmc.UserId');
		$this->db->order_by('cmc.GuarantorId');
		$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>'.print_r($result, true).'</pre>';return;
		$data = array();
		$this->load->library('mshc_connector');
		foreach ($result as $case_mngr)
		{
			$conds = array();
			$conds['account'] = $case_mngr['GuarantorId'];
			$conds['db_name'] = $case_mngr['db_name'];
			$conds['accident_date'] = date('m/d/Y', strtotime($case_mngr['DOA']));
			$this->load->library('mshc_connector');
			
			$fields = array('practice');
			$case = $this->mshc_connector->getCases(
				array(1,2,3,4,5), 
				'all', 
				array(
					'fields' => $fields, 
					'conds' => $conds, 
					'debugReturn' => 'sample_all'
				)
			);

			if (element(0, $case))
			{
				$data[] = array(
					'ext_db_id' => array_search(strtoupper($case[0]['db_name']), $this->mshc_connector->getDBArray()),
					'external_id1' => $case[0]['account'],
					'external_id2' => $case[0]['practice'],
					'external_id3' => $case[0]['case_no'],
					'external_id4' => $case[0]['patient'],
					'user_id' => $case_mngr['user_id']
				);
			}
		}
		//echo '<pre>'.print_r($data, true).'</pre>';
		$this->db->insert_batch('legal_cases_legal_case_mgrs', $data);
		echo count($data).' legal case managers migrated.';
	}
	
	private function get_id_by_old($old)
	{
		$this->db->where('old_id', $old);
		$this->db->from('users');
		$query = $this->db->get();
		if ($query->num_rows())	return $query->row()->id;
		else return NULL;
	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */