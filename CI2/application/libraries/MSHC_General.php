<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class MSHC_General
 *
 * @property MSHC_Controller $ci
 */

class MSHC_General
{
    public function __construct() 
    {
		$this->ci =& get_instance();
	}
	
	/*
	* Send new password
	*/
	public function send_new_password($email, $mail_params)
	{
		$params = array();
		$params['send_to'] = $email;
		$params['subject'] = $mail_params['subject'];
		$params['message'] = $mail_params['message'];
		$params['alt_message'] = $mail_params['alt_message'];
		
		if ($this->ci->_send_mail($params))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	/*
	* Send contact
	*/
	public function send_contact($emailto, $message, $alt_message, $emailcc = array(), $attach = array(), $userdata = NULL)
	{		
		$params = array();
		$params['send_to'] = $emailto;
		if (isset($userdata['email']))	$params['reply_to'] = $userdata['email'];
		if (count($emailcc)) $params['cc'] = $emailcc;
		$params['subject'] = 'MSHC Attorney Portal: New Inquiry';
		$params['message'] = $message;
		$params['alt_message'] = $alt_message;
		$params['userdata'] = $userdata;
		if (count($attach)) $params['attach'] = $attach;

		if ($this->ci->_send_mail($params)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/*
	*	Get users by parametrs
	*/
	public function get_users_params($search_data, $input_order, $data)
    {
		$order = array();
		foreach ($input_order as $field => $type)
		{
			switch ($field) {
				case 'role_name': $order[] = array('role_name' => $type);break;
				case 'firm_name': $order[] = array('firm_name' => $type);break;
				default: $order[] = array('u.'.$field => $type);break;
			}
		}

        $users = array();

		$where_in = NULL;
		if ($this->ci->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN && $this->ci->_user['role_id'] != MSHC_AUTH_BILLER) {
			$this->ci->db->select('olfu.user_id');
			$this->ci->db->from($this->ci->legal_firms_users_table_name.' AS lfu');
			$this->ci->db->join($this->ci->legal_firms_users_table_name.' AS olfu','olfu.legal_firm_id = lfu.legal_firm_id');
			$this->ci->db->join($this->ci->users_table_name.' AS u','u.id = olfu.user_id AND u.role_id != "'.MSHC_AUTH_SYSTEM_ADMIN.'"');
			$this->ci->db->where('lfu.user_id', $this->ci->_user['user_id']);
			$this->ci->db->group_by('olfu.user_id');
			$query = $this->ci->db->get();
			$users = array($this->ci->_user['user_id']);

			if ($query->num_rows()) {
				$result = $query->result();
				foreach ($result as $user)
				{
					$users[] = $user->user_id;
				}
			}
			$where_in = TRUE;
		}

		$params = array(
			'fields' => array(
				'u.id' => '',
				'u.last_name' => '', 
				'u.first_name' => '', 
				'u.username' => '', 
				'DATE_FORMAT(u.last_login_date,\'%b %e, %Y, %k:%S\')' => 'last_login_date', 
				'r.name' => 'role_name', 
				'lf.name' => 'firm_name'
			),
			'from' => array(
				$this->ci->users_table_name => 'u'
			),
			'join' => array(
				array(
					'table' => $this->ci->roles_table_name.' AS r',
					'condition' => 'r.id = u.role_id'
				),
				array(
					'table' => $this->ci->legal_firms_users_table_name.' AS lfu',
					'condition' => 'lfu.user_id = u.id AND lfu.is_primary = 1',
					'type' => 'left'
				),
				array(
					'table' => $this->ci->legal_firms_table_name.' AS lf',
					'condition' => 'lf.id = lfu.legal_firm_id',
					'type' => 'left'
				)
			),
			'where' => array(
				'1' => '1'
			),
			'order' => $order,
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
		);

		if (is_array($search_data)) {
			foreach($search_data as $field => $value)
			{
				if ($search_data['sortingQriteria'] == 'sorting-contains') {
					$get_users_where_func = 'get_query_like';
				}  elseif ($search_data['sortingQriteria'] == 'sorting-equal') {
					$get_users_where_func = 'get_query_equal';
				} else {
					$get_users_where_func = 'get_query_not_equal';
				}

				switch ($search_data['sortingFieldName']) {
					case 'username': $params['where'] = call_user_func_array(
						$get_users_where_func,
						array(
							'field' => 'u.username',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'last_name': $params['where'] = call_user_func_array(
						$get_users_where_func,
						array(
							'field' => 'u.last_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'first_name': $params['where'] = call_user_func_array(
						$get_users_where_func,
						array(
							'field' => 'u.first_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'is_primary': $params = array(
						'fields' => array(
							'u.id' => '',
							'u.last_name' => '', 
							'u.first_name' => '', 
							'u.username' => '', 
							'DATE_FORMAT(u.last_login_date,\'%b %e, %Y, %k:%S\')' => 'last_login_date', 
							'r.name' => 'role_name', 
							'lf.name' => 'firm_name'
						),
						'from' => array(
							$this->ci->legal_firms_table_name => 'lf'
						),
						'join' => array(
							array(
								'table' => $this->ci->legal_firms_users_table_name.' AS lfu',
								'condition' => 'lfu.legal_firm_id = lf.id AND lfu.is_primary = 1'
							),
							array(
								'table' => $this->ci->users_table_name.' AS u',
								'condition' => 'u.id = lfu.user_id'
							),
							array(
								'table' => $this->ci->roles_table_name.' AS r',
								'condition' => 'r.id = u.role_id'
							)
						),
						'where' => call_user_func_array(
							$get_users_where_func,
							array(
								'field' => 'lf.name',
								'value' => $search_data['sortingValue']
							)
						),
						'order' => $order,
						'offset' => get_array_value('jtStartIndex',$data),
						'limit' => get_array_value('jtPageSize',$data),
					);
					break;

					case 'role_id': $params = array(
						'fields' => array(
							'u.id' => '',
							'u.last_name' => '', 
							'u.first_name' => '', 
							'u.username' => '', 
							'DATE_FORMAT(u.last_login_date,\'%b %e, %Y, %k:%i\')' => 'last_login_date', 
							'r.name' => 'role_name', 
							'lf.name' => 'firm_name'
						),
						'from' => array(
							$this->ci->roles_table_name => 'r'
						),
						'join' => array(
							array(
								'table' => $this->ci->users_table_name.' AS u',
								'condition' => 'u.role_id = r.id'
							),
							array(
								'table' => $this->ci->legal_firms_users_table_name.' AS lfu',
								'condition' => 'lfu.user_id = u.id AND lfu.is_primary = 1'
							),
							array(
								'table' => $this->ci->legal_firms_table_name.' AS lf',
								'condition' => 'lf.id = lfu.legal_firm_id'
							)
						),
						'where' => call_user_func_array(
							$get_users_where_func,
							array(
								'field' => 'r.name',
								'value' => $search_data['sortingValue']
							)
						),
						'order' => $order,
						'offset' => get_array_value('jtStartIndex',$data),
						'limit' => get_array_value('jtPageSize',$data),
					);
					break;
				}
			}
		}

		if (!is_null($where_in)) {
			$params['where_in'] = array(
				'u.id' => $users
			);
		}

		return $params;
	}

    public function get_activities_params($search_data, $input_order, $data)
    {
        $order = array();
        foreach ($input_order as $field => $type)
        {
            switch ($field)
            {
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
                $this->ci->activity_logs_table_name => 'pal',
                $this->ci->users_table_name => 'u',
                $this->ci->activities_table_name => 'pa'
            ),
            'join' => array(
                array(
                    'table' => $this->ci->legal_firms_users_table_name.' AS lfu_fn',
                    'condition' => 'lfu_fn.user_id = u.id AND lfu_fn.is_primary = 1',
                    'type' => 'left'
                ),
                array(
                    'table' => $this->ci->legal_firms_table_name.' AS lf_fn',
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

        if (is_array($search_data))
        {
            if ($search_data['event_name'] != '')
            {
                $params['where'] = array (
                    'pal.user_id = u.id' => '',
                    '( pal.portal_activity_id IS NOT NULL AND pal.portal_activity_id = pa.id AND pa.name = \''.$search_data['event_name'].'\' )' => ''
                );
            }
            if ($search_data['user_id'] != 0)
            {
                $params['where']['u.id'] = $search_data['user_id'];
            }
        }

        if ($this->ci->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN)
        {
            $this->ci->db->select('olfu.user_id');
            $this->ci->db->from($this->ci->legal_firms_users_table_name.' AS lfu');
            $this->ci->db->join($this->ci->legal_firms_users_table_name.' AS olfu','olfu.legal_firm_id = lfu.legal_firm_id');
            $this->ci->db->join($this->ci->users_table_name.' AS u','u.id = olfu.user_id AND u.role_id != "'.MSHC_AUTH_SYSTEM_ADMIN.'"');
            $this->ci->db->where('lfu.user_id', $this->ci->_user['user_id']);
            $this->ci->db->group_by('olfu.user_id');
            $query = $this->ci->db->get();
            $users = array($this->ci->_user['user_id']);
            if ($query->num_rows())
            {
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

        return $params;
    }

	/*
	*	Get marketers by parametrs
	*/
	public function get_marketers_params($search_data, $input_order, $data) {
		$order = array();
		foreach ($input_order as $field => $type)
		{
			$order[] = array($field => $type);
		}
		$params = array(
			'fields' => array(
				'id' => '',
				'last_name' => '', 
				'first_name' => '', 
				'middle_name' => '', 
				'phone' => '', 
				'email' => ''
			),
			'from' => array(
				$this->ci->marketers_table_name => ''
			),
			'order' => $order,
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
		);
		
		if (is_array($search_data))
		{
			foreach($search_data as $field => $value)
			{
				if ($search_data['sortingQriteria'] == 'sorting-contains')
				{
					$get_marketers_where_func = 'get_query_like';
				} 
				elseif ($search_data['sortingQriteria'] == 'sorting-equal')
				{
					$get_marketers_where_func = 'get_query_equal';
				}
				else
				{
					$get_marketers_where_func = 'get_query_not_equal';
				}
				switch ($search_data['sortingFieldName']) 
				{
					case 'middle_name': $params['where'] = call_user_func_array(
						$get_marketers_where_func,
						array(
							'field' => 'middle_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'last_name': $params['where'] = call_user_func_array(
						$get_marketers_where_func,
						array(
							'field' => 'last_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'first_name': $params['where'] = call_user_func_array(
						$get_marketers_where_func,
						array(
							'field' => 'first_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'phone': $params['where'] = call_user_func_array(
						$get_marketers_where_func,
						array(
							'field' => 'phone',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'email': $params['where'] = call_user_func_array(
						$get_marketers_where_func,
						array(
							'field' => 'email',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
				}
			}
		}
		
		return $params;
	}
	
	
	/*
	*	Get forms by parametrs
	*/
	public function get_forms_params($search_data, $input_order, $data)
    {
		$order = array();
		foreach ($input_order as $field => $type)
		{
			$order[] = array($field => $type);
		}

		$params = array(
			'fields' => array(
				'id' => '',
				'name' => '', 
				'file_name' => '', 
				'description' => '', 
				'weight' => ''
			),
			'from' => array(
				$this->ci->forms_table_name => ''
			),
			'order' => $order,
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
		);
		
		if (is_array($search_data)) {
			foreach($search_data as $field => $value)
			{
                $get_forms_where_func = $this->getWhereFunction($search_data['sortingQriteria']);

				switch ($search_data['sortingFieldName']) 
				{
					case 'name': $params['where'] = call_user_func_array(
						$get_forms_where_func,
						array(
							'field' => 'name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'file_name': $params['where'] = call_user_func_array(
						$get_forms_where_func,
						array(
							'field' => 'file_name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'description': $params['where'] = call_user_func_array(
						$get_forms_where_func,
						array(
							'field' => 'description',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'weight': $params['where'] = call_user_func_array(
						$get_forms_where_func,
						array(
							'field' => 'weight',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
				}
			}
		}
		
		return $params;
	}
	
	/*
	*	Get clients by parametrs
	*/
	public function get_clients_params($search_data, $input_order, $data)
    {
		$order = array();
		foreach ($input_order as $field => $type)
		{
			$order[] = array($field => $type);
		}
		$params = array(
			'fields' => array(
				'c.id' => '',
				'c.name' => '', 
				'COUNT(p.id)' => 'practices_count'
			),
			'from' => array(
				$this->ci->clients_table_name => 'c'
			),
			'join' => array(
				array(
					'table' => $this->ci->practices_table_name.' AS p',
					'condition' => 'p.client_id = c.id',
					'type' => 'left'
				)
			),
			'order' => $order,
			'group' => array(
				'c.id'
			),
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
		);
		
		if (is_array($search_data))
		{
			foreach($search_data as $field => $value)
			{
                $get_clients_where_func = $this->getWhereFunction($search_data['sortingQriteria']);

				switch ($search_data['sortingFieldName']) 
				{
					case 'client_name': $params['where'] = call_user_func_array(
						$get_clients_where_func,
						array(
							'field' => 'c.name',
							'value' => $search_data['sortingValue']
						)
					);
					break;
					
					case 'practices_count': $params['where'] = call_user_func_array(
							$get_clients_where_func,
							array(
								'field' => 'practices_count',
								'value' => $search_data['sortingValue']
							)
						);
						unset($params['fields']);
						unset($params['join']);
						unset($params['group']);
						unset($params['order']);
						$params['from'] = array(
							'(SELECT c.id, c.name, COUNT( p.id ) AS practices_count
							FROM  clients c 
							LEFT JOIN practices p ON p.client_id = c.id 
							GROUP BY c.id) AS temp' => ''
						);
					break;
				}
			}
		}
		return $params;
	}
	
	/*
	*	Get practices by parametrs
	*/
	public function get_practices_params($search_data, $input_order, $data) {
		$order = array();
		foreach ($input_order as $field => $type)
		{
			$order[] = array($field => $type);
		}
		$client_id = get_array_value('client_id', $data);
		
		$params = array(
			'fields' => array(
				'p.id' => '',
				'p.name' => 'practice_name', 
				'ed1.name' => 'micro_db_name', 
				'IF(p.ext_db_id2 != 0, ed2.name, IF(p.ext_db_id3 != 0, ed3.name, NULL))' => 'rundown_db_name', 
				'p.split_charges' => ''
			),
			'from' => array(
				$this->ci->practices_table_name => 'p'
			),
			'join' => array(
				array(
					'table' => $this->ci->ext_dbs_table_name.' AS ed1',
					'condition' => 'ed1.id = p.ext_db_id1',
					'type' => ''
				),
				array(
					'table' => $this->ci->ext_dbs_table_name.' AS ed2',
					'condition' => 'ed2.id = p.ext_db_id2 AND p.ext_db_id2 != 0',
					'type' => 'left'
				),
				array(
					'table' => $this->ci->ext_dbs_table_name.' AS ed3',
					'condition' => 'ed3.id = p.ext_db_id3 AND p.ext_db_id3 != 0',
					'type' => 'left'
				)				
			),
			'where' => array(
				'client_id' => $client_id
			),
			'order' => $order,
			'offset' => get_array_value('jtStartIndex',$data),
			'limit' => get_array_value('jtPageSize',$data),
		);
		
		if (is_array($search_data))
		{
			foreach($search_data as $field => $value)
			{
                $get_practice_where_func = $this->getWhereFunction($search_data['sortingQriteria']);

				switch ($search_data['sortingFieldName']) 
				{
					case 'practice_name': $params['where'] = call_user_func_array(
							$get_practice_where_func,
							array(
								'field' => 'p.name',
								'value' => $search_data['sortingValue']
							)
						);
					$params['where']['client_id'] = $client_id;
					break;
					
					case 'microdb': $params['where'] = call_user_func_array(
							$get_practice_where_func,
							array(
								'field' => 'ed1.name',
								'value' => $search_data['sortingValue']
							)
						);
					$params['where']['client_id'] = $client_id;
					break;

					case 'rundown': $params['where'] = call_user_func_array(
							$get_practice_where_func,
							array(
								'field' => 'rundown_db_name',
								'value' => $search_data['sortingValue']
							)
						);
						$params['where']['client_id'] = $client_id;
						$params['fields'] = array(
							'id' => '',
							'practice_name' => '', 
							'micro_db_name' => '', 
							'rundown_db_name' => '', 
							'split_charges' => ''
						);
						unset($params['join']);
						$params['from'] = array(
							'(SELECT p.client_id, 
							p.id, 
							p.name AS practice_name, 
							ed.name AS micro_db_name, 
							IF( p.ext_db_id2 != 0, ed1.name, IF( p.ext_db_id3 != 0, ed2.name, ed2.name ) ) AS rundown_db_name, 
							p.split_charges 
							FROM practices AS p 
							JOIN ext_dbs AS ed ON ed.id = p.ext_db_id1 
							LEFT JOIN ext_dbs AS ed1 ON ed1.id = p.ext_db_id2 AND p.ext_db_id2 != 0 
							LEFT JOIN ext_dbs AS ed2 ON ed2.id = p.ext_db_id3 AND p.ext_db_id3 != 0) AS temp' => ''
						);
					break;

					case 'split_charges': $params['where'] = call_user_func_array(
							$get_practice_where_func,
							array(
								'field' => 'p.split_charges',
								'value' => $search_data['sortingValue']
							)
						);
					$params['where']['client_id'] = $client_id;
					break;
				}
			}
		}
		
		return $params;
	}
	
	/*
	*	Get calculate diastance by parametrs
	*/
	public function get_distance_params($search_data, $conds) {
		
		if (is_array($search_data) && get_array_value('sortingQriteria', $search_data) != NULL)
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
					
					case 'location': $conds['location_oper'] = $compares;
					break;
					
					case 'distance': $conds['distance_oper'] = $compares;
					break;
					
					case 'date': $conds['date_oper'] = $compares;
					break;
				}
			}
			$conds[$search_data['sortingFieldName']] = $search_data['sortingValue'];
		}
		return $conds;		
	}
					
	public function get_calculate_distance($case_patient, $appointments, $clients_address_wish, $clients_custom_address = '', $distances = NULL) 
	{
		if ($clients_address_wish == 'home') {
			$address_origins = $case_patient[0]['pnt_address1'].', '.
				$case_patient[0]['pnt_addr_city'].', '.
				$case_patient[0]['pnt_addr_state'].', '.
				$case_patient[0]['pnt_addr_zip'];
		} else if ($clients_address_wish == 'work') {
			$address_origins = $case_patient[0]['pnt_work_address1'].', '.
				$case_patient[0]['pnt_work_addr_city'].', '.
				$case_patient[0]['pnt_work_addr_state'].', '.
				$case_patient[0]['pnt_work_addr_zip'];
		} else {
			$address_origins = $clients_custom_address;
		}
		
		$appts = array();
		$k = 0;
		for ($i = 0; $i < count($appointments); $i++)
		{
			$conds = array();
			$conds['origins'] = $address_origins;
			$conds['destinations'] = $appointments[$i]['street_address1'].', '.$appointments[$i]['city'].', '.$appointments[$i]['state'].', '.$appointments[$i]['zip_code'];
			$conds['mode'] = 'driving';
			$conds['sensor'] = 'false';
			$conds['units'] = 'imperial';
		
			$this->ci->load->library('mshc_connector');
			$calculate_distances = $this->ci->mshc_connector->getCalculateDistance($conds);

			if ($calculate_distances)
			{
				if (!isset($calculate_distances->rows[0]->elements[0]->distance->text))
				{
					return FALSE;
				}
				$appointments[$i]['distance'] = str_replace(' mi', ' miles', $calculate_distances->rows[0]->elements[0]->distance->text);
			}
			else
			{
				$appointments[$i]['distance'] = 0;
			}
			
			if ($distances != NULL) {
				if ($distances['distance_oper'] == 'like' && strpos($appointments[$i]['distance'], $distances['value']) > -1) {
					$appts[$k] = $appointments[$i];
					$k++; 
				} else if ($distances['distance_oper'] == ' = ' && $appointments[$i]['distance'] == $distances['value'] ) {
					$appts[$k] = $appointments[$i];
					$k++; 
				} else if ($distances['distance_oper'] == ' != ' && $appointments[$i]['distance'] != $distances['value'] ) {
					$appts[$k] = $appointments[$i];
					$k++; 
				}
			} else {
				$appts[$k] = $appointments[$i];
				$k++;
			}
		}
		
		return $appts;
	}
	
	/*
	*	Get discharge and client list by parametrs
	*/
	public function get_discharge_params($search_data, $sorting, $conds)
    {
		if (is_array($search_data)) {
            $compares = '';

			if (isset($search_data['sortingQriteria'])) {
				if ($search_data['sortingQriteria'] == 'sorting-between') {
					$compares = 'BETWEEN';
				} elseif ($search_data['sortingQriteria'] == 'sorting-contains') {
					$compares = 'CONTAINS';
				} elseif ($search_data['sortingQriteria'] == 'sorting-equal') {
					$compares = '';
				} else {
					$compares = 'NOT';
				}
			}

			if (isset($search_data['sortingFieldName'])) {
				if ($compares != '') {
					switch ($search_data['sortingFieldName']) {
						case 'patient': $conds['full_name']['op'] = $compares;
							$search_data['sortingFieldName'] = 'full_name';
						    break;
						
						case 'account': $conds['account']['op'] = $compares;
						    break;
						
						case 'case_category': $conds['case_category']['op'] = $compares;
						    break;
						
						case 'accident_date_between': 
							$accident_date_between['value'][] = $search_data['sortingValue'] ? $search_data['sortingValue'] : date('m/d/Y', strtotime('1/1/1990'));
							$accident_date_between['value'][] = $search_data['sortingValue2'] ? $search_data['sortingValue2'] : date('m/d/Y');
							$conds['accident_date'] = $accident_date_between;
							$conds['accident_date']['op'] = 'BETWEEN';
							break;
						
						case 'discharge_date_between': 
							$discharge_date_between['value'][] = $search_data['sortingValue'] ? $search_data['sortingValue'] : date('m/d/Y', strtotime('1/1/1990'));
							$discharge_date_between['value'][] = $search_data['sortingValue2'] ? $search_data['sortingValue2'] : date('m/d/Y');
							$conds['discharge_date'] = $discharge_date_between;
							$conds['discharge_date']['op'] = 'BETWEEN';
							break;
						
						case 'status': $conds['status']['op'] = $compares;
						    break;
					}
				}

				if ($search_data['sortingFieldName'] == 'patient') {
					$search_data['sortingFieldName'] = 'full_name';
				}
				if ($search_data['sortingFieldName'] != 'discharge_date_between' && $search_data['sortingFieldName'] != 'accident_date_between') {
					if ($compares != '')
						$conds[$search_data['sortingFieldName']]['value'] = $search_data['sortingValue'];
					else
						$conds[$search_data['sortingFieldName']] = $search_data['sortingValue'];
				}
			}
			
		}

		return $conds;
	}
	
	
	/*
	*	Get appointments list by parametrs
	*/
	public function get_appointments_params($search_data) {
		
		$params = array();
		//print_r($search_data);return;
		if (is_array($search_data) && get_array_value('sortingQriteria', $search_data) != NULL)
		{
			foreach($search_data as $field => $value)
			{
				if ($search_data['sortingQriteria'] == 'sorting-between')
				{
					$compares = 'BETWEEN';
				} 
				elseif ($search_data['sortingQriteria'] == 'sorting-equal')
				{
					$compares = ' = ';
				}
				elseif ($search_data['sortingQriteria'] == 'sorting-not-equal')
				{
					$compares = ' != ';
				}
				elseif ($search_data['sortingQriteria'] == 'sorting-more-than')
				{
					$compares = ' > ';
				}
				elseif ($search_data['sortingQriteria'] == 'sorting-less-than')
				{
					$compares = ' < ';
				}
				else
				{
					$compares = 'like';
				}
				switch ($search_data['sortingFieldName']) 
				{
					case 'provider': $params['provider_oper'] = $compares;
					break;
					
					case 'reason': $params['reason_oper'] = $compares;
					break;
					
					case 'location': $params['loc_oper'] = $compares;
					break;
					
					case 'status': $params['status_oper'] = $compares;
					break;
					
					case 'accident_date': $params['date_oper'] = $compares;
					break;
					
					case 'appt_date_between': $appt_date_between['value'][] = $search_data['sortingValue'] ? $search_data['sortingValue'] : date('m/d/Y', strtotime('1/1/1990'));
					$appt_date_between['value'][] = $search_data['sortingValue2'] ? $search_data['sortingValue2'] : date('m/d/Y');
					$search_data['sortingValue'] = $appt_date_between;
					$search_data['sortingFieldName'] = 'date';
					$params['date_oper'] = $compares;
					break;
				}
			}
			$params[$search_data['sortingFieldName']] = $search_data['sortingValue'];
		}

		return $params;
	}

	public function get_cases_params($search_data, $sortingTable, $data)
	{
        $client_cases_name = element('sName', $search_data);
        $client_cases_ssn = element('sSSN', $search_data);
        $client_cases_account = element('sAccount', $search_data);
        $client_cases_type_date = element('sTypeDate', $search_data);
        $client_cases_date_from = element('sDateFrom', $search_data);
        $client_cases_date_to = element('sDateTo', $search_data);
        $client_cases_class = element('sClass', $search_data);
        $client_cases_cases_type = element('sCasesType', $search_data);
        $client_cases_attys = element('sAttys', $search_data);
        $client_cases_my_cases = element('sMyCases', $search_data);
        $client_cases_company = element('sCompany', $search_data);
        $client_cases_financial = element('sFinancial', $search_data);

        if (!is_array($client_cases_attys)) $client_cases_attys = array();

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

        if ($client_cases_type_date != '' && ($client_cases_date_from != '' || $client_cases_date_to != ''))
        {
            if ($client_cases_type_date == 'accident')
            {
                $accident_date_between['value'][] = $client_cases_date_from ? $client_cases_date_from : date('m/d/Y', strtotime('1/1/1990'));
                $accident_date_between['value'][] = $client_cases_date_to ? $client_cases_date_to : date('m/d/Y');
                $conds['accident_date_val'] = $accident_date_between;
                $conds['accident_date_val']['op'] = 'BETWEEN';
            }
            else
            {
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

        $dbs = $this->ci->mshc_connector->getDBArray();

        if ($client_cases_my_cases === TRUE)
        {
            $assigned_cases = $this->ci->firms->get_assigned_cases(array('where' => array('user_id' => $this->ci->_user['user_id'])));

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
        }

        $attorneys_list = array();

        $join_conds = '';
        /*if ($this->ci->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
            $join_conds = ' AND edla.ext_db_id = 3';
        }*/

        if (count($client_cases_attys)) {
            $params = array(
                'fields' => array(
                    'la.*' => '',
                    'edla.*' => ''
                ),
                'from' => array(
                    $this->ci->legal_attorneys_table_name => 'la'
                ),
                'join' => array(
                    array(
                        'table' => $this->ci->ext_dbs_legal_attys_table_name.' AS edla',
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
            $attorneys_list = $this->ci->firms->get_attorneys($params, FALSE);
        } elseif ($this->ci->_user['role_id'] == MSHC_AUTH_BILLER) {
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
            $attorneys_list = $this->getUserAttorneys();
        }

        if (count($attorneys_list) == 0 && $this->ci->_user['role_id'] != MSHC_AUTH_BILLER) {
            return false;
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

        return $conds;
	}

	/*
	* Send call me by phone number
	*/
	public function send_call_me($email, $message)
	{
		$params = array();
		$params['send_to'] = $email;
		$params['subject'] = 'Multi-Specialty Appointment Request';
		$params['message'] = $message;
		$params['alt_message'] = $message;
		$params['mailtype'] = 'text';
		
		if ($this->ci->_send_mail($params))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	private function _build_file_source($file)
	{
		$docstar = strpos(strtolower($file), 'docstar');
		if ($docstar === FALSE) {
			$docs = strpos($file, 'DOCS');
			if ($docs === FALSE) {
				$mapped = explode(':', $file);
			} else {
				$mapped = explode('DOCS', $file);
			}
			$source = '\\\\docstar\\d$\\docs'.$mapped[1];
		} else {
			$mapped = explode(':', $file);
			$source = '\\\\docstar\\c$'.$mapped[1];
		}
		
		return $source;
	}

	public function fileExists($file)
    {
        if ($file) {
            $path = pathinfo($file);
            $input = $this->_build_file_source($file);
            $output = FCPATH . MSHC_CONVERT_FILE_PATH . DIRECTORY_SEPARATOR . $path['basename'];

            $exec = 'copy ' . $input . ' ' . $output . ' 2>&1';
            exec($exec, $yaks);

            if (false !== @file_get_contents($output, 0, null, 0, 1)) {
                return true;
            }
        }

        return false;
    }

	public function create_pdf($file = NULL, $type = 'html')
	{
		require_once("application/libraries/MPDF/mpdf.php");
		switch ($type)
		{
			case 'image': return $this->create_pdf_from_image($file); break;
			case 'doc': 
			case 'docx': return $this->create_pdf_from_doc($file); break;
			case 'pdf': return $this->create_pdf_from_pdf($file); break;
		}

		return false;
	}
	
	private function create_pdf_from_image($file)
	{
		if ($file && MSHC_PATH_TO_IMAGIK) {
            $path = pathinfo($file);
            $pdf_file = FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.pdf';

            // local
            //$exec = '"'.MSHC_PATH_TO_IMAGIK.'" '.$source.' '.$pdf_file.' 2>&1';

            // server
            $source = $this->_build_file_source($file);
            $exec = 'convert '.$source.' '.$pdf_file.' 2>&1';

            exec($exec, $yaks);
            log_message('debug', print_r($yaks, true));

            if (false !== @file_get_contents($pdf_file, 0, null, 0, 1)) {
                return $pdf_file;
            }
		}

		return false;
	}
	
	private function create_pdf_from_doc($file)
    {
		set_time_limit(0);
		//error_reporting(E_ALL);
		
		$path = pathinfo($file);
		$pdf_file = 'file:///'.str_replace('\\', '/', FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.pdf');
		$new_file = FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.'.$path['extension'];
		
		$source = $this->_build_file_source($file);
		$exec = 'copy '.$source.' '.$new_file.' 2>&1';
		exec($exec, $yaks);
		
		//$source = 'file:///'.$new_file;

		/*convCommand = '"C:\Program Files (x86)\LibreOffice 4\program\python.exe"' .
				' "C:\Program Files\unoconv-master\unoconv" ' .
				' -o ' . FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.pdf' .
				' -f pdf ' .
				' -l -p 9842 ' .
				$new_file;*/

        $convCommand = '"C:\Program Files (x86)\OfficeToPDF\officetopdf.exe" ' .
            ' /hidden /print /readonly ' .
            $new_file . ' ' .
            FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'] . '.pdf';

		exec($convCommand, $return);
        log_message('debug', $convCommand);
        log_message('debug', print_r($return, true));

        /**
		//Invoke the OpenOffice.org service manager
		$osm = new COM("com.sun.star.ServiceManager") or die("Please be sure that OpenOffice.org is installed.\n");
		
		//Set the application to remain hidden to avoid flashing the document onscreen
		$args = array($this->MakePropertyValue("Hidden", true, $osm));
		
		//Launch the desktop
		$oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
		
		//Load the .doc file, and pass in the "Hidden" property from above
		$oWriterDoc = $oDesktop->loadComponentFromURL($source, "_blank", 0, $args);
		
		//Set up the arguments for the PDF output
		$export_args = array($this->MakePropertyValue("FilterName", "writer_pdf_Export", $osm), $this->MakePropertyValue("PageRange", "1", $osm));
		//print_r($export_args);
		
		//Write out the PDF
		$oWriterDoc->storeToURL($pdf_file, $export_args);
		
		$oWriterDoc->close(true);

        exec("taskkill /F /IM soffice.bin", $output = array(), $return);
        exec("taskkill /F /IM soffice.exe", $output = array(), $return);
         */

		if (false !== @file_get_contents($pdf_file, 0, null, 0, 1)) {
			return $pdf_file;
		} else {
			return false;
		}
			
        // local
        //$exec = '"'.MSHC_PATH_TO_WORDTOPDF.'" "'.$source.'" "'.$pdf_file.'" 2>&1';

        // server
        /*$docstar = strpos('docstar', $file);
        if ($docstar === FALSE)
        {
            $mapped = explode(':', $file);
            $source = '\\docstar\Archive'.$mapped[1];
        }
        $exec = 'convert '.$source.' '.$pdf_file.' 2>&1';

        exec($exec, $yaks);
        //print_r($yaks);

        $word = new COM('Word.Application') or die('no word');

        $word->Visible = 0;
        $word->DisplayAlerts = 0;

        // Open an existing document
        /*$source = $file;
        $docstar = strpos('docstar', $file);
        if ($docstar === FALSE)
        {
            $mapped = explode(':', $file);
            $source = '\\\\docstar\\Archive'.$mapped[1];
        }
        $doc = $word->Documents->Open($source, true, true);
        //echo $word->Version;
        $path = pathinfo($file);
        $pdf_file = FCPATH.MSHC_UPLOAD_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.pdf';
        // Create PDF.
        $word->ActiveDocument->ExportAsFixedFormat($pdf_file, 17, false);
        return $pdf_file;*/
	}
	
	private function create_pdf_from_pdf($file)
	{
		set_time_limit(0);
		//error_reporting(E_ALL);
		
		$path = pathinfo($file);
		$pdf_file = FCPATH.MSHC_CONVERT_FILE_PATH.DIRECTORY_SEPARATOR.$path['filename'].'.'.$path['extension'];
		
		$source = $this->_build_file_source($file);
		$exec = 'copy '.$source.' '.$pdf_file.' 2>&1';
				
		exec($exec, $yaks);
        log_message('debug', print_r($yaks, true));
		//echo '<pre>'.print_r($yaks, true).'</pre>';
		
		if (false !== @file_get_contents($pdf_file, 0, null, 0, 1))
		{
			return $pdf_file;
		}

		return false;
	}

    /**
     * @param $name
     * @param $value
     * @param $osm
     * @return mixed
     */
	private function MakePropertyValue($name, $value, $osm)
	{
		$oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
		$oStruct->Name = $name;
		$oStruct->Value = $value;
		return $oStruct;
	}
	
	public function getUserAttorneys($atty_id = NULL) 
	{
        $join_conds = '';
		/*if ($this->ci->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
			$join_conds = ' AND edla.ext_db_id = 3';
		}*/

        $params = array(
            'fields' => array(
                'la.*' => '',
                'edla.*' => '',
                ' edla.external_id ' => ' employer_id',
                ' edla.external_atty_name ' => ' employer_name',
                ' edla.ext_db_name ' => ' database_name'
            ),
            'from' => array(
                $this->ci->legal_attorneys_users_table_name => 'lau'
            ),
            'where' => array(
                'lau.user_id' => $this->ci->_user['user_id']
            ),
            'join' => array(
                array(
                    'table' => $this->ci->ext_dbs_legal_attys_table_name . ' AS edla',
                    'condition' => ' lau.legal_atty_id = edla.legal_atty_id' . $join_conds
                ),
                array(
                    'table' => $this->ci->legal_attorneys_table_name . ' AS la',
                    'condition' => ' la.id = edla.legal_atty_id'
                )
            ),
            'order' => array(
                array(
                    'la.last_name' => 'ASC'
                )
            )
        );

		
		if ($atty_id) {
			$params['where'] = array(
				'lau.user_id' => $this->ci->_user['user_id'],
				'la.id' => $atty_id
			);
		}

		$attorneys_list = $this->ci->firms->get_attorneys($params, FALSE);
		return $attorneys_list;
	}
	
	public function create_missed_appts_notifications($begin, $end, $users_list = NULL)
	{
		$this->ci->load->library('mshc_connector');
		
		$conds['apmnt_time'][0] = $begin;
		$conds['apmnt_time'][1] = $end;
		$conds['status'] = array('Missed', 'Cancelled');
		
		unset($notifs);
		$notifs = $this->ci->mshc_connector->getCronAppointments(
			array(1, 2, 3, 4, 5), 
			'all', 
			array(
				'conds' => $conds, 
				'debugReturn' => 'sample'
			)
		);
		
		$counter = 0;
		$result = array();
		if (is_array($notifs)) {
			for ($i = 0; $i < count($notifs); $i++) 
			{
				unset($users);
				if (isset($notifs[$i]['employer_id']) && $notifs[$i]['employer_id'] > 0) {
					/*$conds = array(
						'ext_db_id' => array_search(strtoupper($notifs[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						'external_id1' => $notifs[$i]['guarantor_id'],
						'external_id2' => $notifs[$i]['practice_id'],
						'external_id3' => $notifs[$i]['case_no'],
						'external_id4' => $notifs[$i]['patient_no'],
					);*/

					$users = $this->ci->users->get_user_by_employer_id(
						$notifs[$i]['employer_id'], 
						array_search(strtoupper($notifs[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						$users_list, 
						array('missed_appointments_notified')
					);
					
					$result[] = array(
						'employer_id' => $notifs[$i]['employer_id'],
						'db' => array_search(strtoupper($notifs[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						'appt_date' => $notifs[$i]['appt_date'],
						'guarantor_id' => $notifs[$i]['guarantor_id'],
						'users_num' => count($users),
						'users' => $users
					);
				
					if (is_array($users) && count($users)) {
						unset($data);
						$data['title'] = 'Missed Appointment for '.$notifs[$i]['pnt_first_name'].' '.$notifs[$i]['pnt_last_name'];
						$data['type'] = 'missed_apt';

						/** @var DateTime $appt_date */
                        $appt_date = $notifs[$i]['appt_date'];
                        /** @var DateTime $appt_time */
                        $appt_time = $notifs[$i]['appt_time'];
						if ($appt_date instanceof DateTime && $appt_time instanceof DateTime) {
							$date = $appt_date->format('m/d/Y');
							$time = $appt_time->format('H:i');
							$data['notification_date'] = date('Y-m-d H:i:s', strtotime($date.' '.$time));
						} else {
							$date = 'N/A';
							$time = '';
							$data['notification_date'] = date('Y-m-d H:i:s');
						}

						$data['body'] = 'Date: '.$date.' '.$time.'; Reason: '.
							/*$notif_missed_appointments[$i]['provider'].' '.*/$notifs[$i]['reason'].'; Location: '.
							$notifs[$i]['location'].br().
							anchor(
								base_url().
								implode(
									'/',
									array(
										MSHC_CASES_CONTROLLER_NAME,
										MSHC_CASES_CLIENT_SEARCH_NAME,
										'0',
										'appts',
										$notifs[$i]['guarantor_id'],
										$notifs[$i]['database_name'],
										$notifs[$i]['practice_id'],
										$notifs[$i]['patient_no'],
										$notifs[$i]['case_no']
									)
								),
								'Click here to view Case Appointments.'
							);
						$data['created'] = date('Y-m-d H:i:s');
						$not_id = $this->ci->notifications->add_new_notifications($data);
						if ($not_id > 0) {
							foreach ($users as $user)
							{
                                if ($user['id'] != 3) {
                                    //continue;
                                }

								++$counter;
								$this->ci->notifications->add_notifications_users(array('notification_id' => $not_id, 'user_id' => $user['id']));
								
								$mail_params['send_to'] = $user['email'];
								$mail_params['send_from'] = 'scheduling@mshc.bz';
								$mail_params['subject'] = 'MSHC Portal Missed Appointment Alert';
								$mail_params['message'] = $this->ci->load->view('email/missed_appt_alert-html', $notifs[$i], TRUE);
								$mail_params['alt_message'] = $this->ci->load->view('email/missed_appt_alert-txt', $notifs[$i], TRUE);
																
								$sending = $this->ci->_send_mail($mail_params);
								
								if ($sending) $log_type = 'info';
								else $log_type = 'error';
								
								log_message(
									$log_type, 
									'Missed Appointment Alert; Send To: '.$user['email'].'; Date: '.$data['created'].
									'; For User: '.$user['username'].'; For Account: '.$notifs[$i]['guarantor_id'].'; ');
							}
						}
					}
				}
			}
		}
		
		//print_r($result);
		return array(count($notifs), $counter);
	}
	
	public function create_documents_notifications($begin = NULL, $end = NULL, $users_list = NULL)
	{
		$this->ci->load->library('mshc_connector');
		
		$conds_doc = array(
			'doc_created' => array(
				$begin, 
				$end
			)
		);
        //echo '<pre>'.print_r($conds_doc, true).'</pre>';

		unset($notif_documents);
		$notif_documents = $this->ci->mshc_connector->getCronDocuments(
			array(1, 2, 3, 4, 5), 
			'all', 
			array(
				'conds' => $conds_doc, 
				'debugReturn' => 'sample'
			)
		);
		//echo '<pre>'.print_r($notif_documents, true).'</pre>';return;
        //echo '<pre>'.print_r(count($notif_documents), true).'</pre>';

		$counter = 0;
		$result = array();
		if (is_array($notif_documents)) 
		{
			$sorted_notif = array();
			foreach ($notif_documents as $doc) 
			{
				if (!array_key_exists($doc['id'], $sorted_notif)) 
				{
					$sorted_notif[$doc['id']] = array();
				}
				$sorted_notif[$doc['id']][] = $doc;
			}
			$notif_documents = array();
			foreach ($sorted_notif as $docs) 
			{
				$full_path = array(
					$docs[0]['full_path']
				);
				
				if (count($docs) > 1) 
				{	
					for ($i = 1; $i < count($docs); ++$i) 
					{
						$full_path[] = $docs[$i]['full_path'];
						unset($docs[$i]);
					}
					
					$_name = explode('page', $docs[0]['document_name']);
					$document_name = rtrim(rtrim($_name[0]), ',');
					$docs[0]['lPAGEID'] = 0;
					$docs[0]['document_name'] = $document_name;
					$docs[0]['full_path'] = implode(',', $full_path);
				}
				$notif_documents[] = $docs[0];
			}
			//echo '<pre>'.print_r($notif_documents, true).'</pre>';exit;
			
			for ($i = 0; $i < count($notif_documents); $i++) 
			{
				if (isset($notif_documents[$i]['employer_id']) && $notif_documents[$i]['employer_id'] > 0) {
					unset($users);
					/*$conds = array(
						'ext_db_id' => array_search(strtoupper($notif_documents[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						'external_id1' => $notif_documents[$i]['guarantor_id'],
						'external_id2' => $notif_documents[$i]['practice_id'],
						'external_id3' => $notif_documents[$i]['case_no'],
						'external_id4' => $notif_documents[$i]['patient_no'],
					);*/
					
					$users_notifs = array();
					switch (strtolower($notif_documents[$i]['document_type'])) {
						case 'prog note': $users_notifs[] = 'pt_note_notified'; break;
						case 'pt-bwr referral': $users_notifs[] = 'ptbwr_referral_notified'; break;
						case 'os med rec': $users_notifs[] = 'outside_medical_record_notified'; break;
                        case 'nti report':
						case 'mri report':
						case 'letter':
						case 'medical report': $users_notifs[] = 'medical_report_notified'; break;
						case 'disability': $users_notifs[] = 'disability_notified'; break;
						case 'consult': $users_notifs[] = 'consult_notified'; break;
                        case 'rx req': $users_notifs[] = 'pharmacy_notified'; break;
					}

					if ($users_notifs) {
                        $users = $this->ci->users->get_user_by_employer_id(
                            $notif_documents[$i]['employer_id'],
                            array_search(strtoupper($notif_documents[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
                            $users_list,
                            $users_notifs
                        );
                    } else {
					    $users = array();
                    }

                    if (count($users)) {
                        //echo $this->ci->db->last_query();
                        //echo '<pre>' . print_r($users_list, true) . '</pre>';
                        //echo '<pre>' . print_r($users_notifs, true) . '</pre>';
                        //echo '<pre>' . print_r($notif_documents[$i], true) . '</pre>';
                        //echo '<pre>' . print_r($users, true) . '</pre>';
                    }

					//$this->users->get_user_by_case($conds);
					
					$doc_name = $notif_documents[$i]['document_name'];
								
					$pos_first_name = strpos(strtolower($doc_name), strtolower($notif_documents[$i]['first_name']));
					
					if ($pos_first_name !== FALSE)
					{
						$doc_name = substr($doc_name, 0, $pos_first_name + 1)
							.str_repeat('*', strlen($notif_documents[$i]['first_name']) - 2)
							.substr($doc_name, $pos_first_name + strlen($notif_documents[$i]['first_name']) - 1);
						$notif_documents[$i]['document_name'] = $doc_name;
					}
					
					$pos_last_name = strpos(strtolower($doc_name), strtolower($notif_documents[$i]['last_name']));
					
					if ($pos_last_name !== FALSE)
					{
						$doc_name = substr($doc_name, 0, $pos_last_name + 1)
							.str_repeat('*', strlen($notif_documents[$i]['last_name']) - 2)
							.substr($doc_name, $pos_last_name + strlen($notif_documents[$i]['last_name']) - 1);
						$notif_documents[$i]['document_name'] = $doc_name;
					}
								
					$result[] = array(
						'employer_id' => $notif_documents[$i]['employer_id'],
						'db' => array_search(strtoupper($notif_documents[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						'date_of_service' => $notif_documents[$i]['date_of_service'],
						'document_date' => $notif_documents[$i]['document_date'],
						'document_name' => $notif_documents[$i]['document_name'],
						'full_path' => $notif_documents[$i]['full_path'],
						'guarantor_id' => $notif_documents[$i]['guarantor_id'],
						'users_num' => count($users),
						'users' => $users
					);
					
					if (is_array($users) && count($users)) {
						unset($data);
						$data['title'] = 'Documents for '.$notif_documents[$i]['first_name'].' '.$notif_documents[$i]['last_name'];
						$data['type'] = 'docs';
						/** @var DateTime $date_of_service */
                        $date_of_service = $notif_documents[$i]['date_of_service'];
						if ($date_of_service instanceof DateTime) {
							$dos = $date_of_service->format('m/d/Y');
						} else {
							$dos = 'N/A';
						}

						/** @var DateTime $document_date */
                        $document_date = $notif_documents[$i]['document_date'];
						if ($document_date instanceof DateTime) {
							$dod = $document_date->format('m/d/Y');
							$data['notification_date'] = date('Y-m-d H:i:s', strtotime($dod));
						} else {
							$data['notification_date'] = date('Y-m-d H:i:s');
						}
						
						$data['body'] = 'Date of Service: '.$dos.'; '.
						'Type: '.$notif_documents[$i]['document_type'].'; Name: '.$notif_documents[$i]['document_name'].
						' <a href="'.$notif_documents[$i]['full_path'].'" class="fnOpenNotifiedDoc">click here to open document</a>';
						$data['created'] = date('Y-m-d H:i:s');
						$not_id = $this->ci->notifications->add_new_notifications($data);
						if ($not_id > 0) {
							foreach ($users as $user)
							{
                                if ($user['id'] != 3) {
                                    //continue;
                                }

								++$counter;
								$this->ci->notifications->add_notifications_users(array('notification_id' => $not_id, 'user_id' => $user['id']));
								
								$mail_params['send_to'] = $user['email'];
								$mail_params['send_from'] = 'BusinessOfficeManagers@mshc.bz';
								$mail_params['subject'] = 'MSHC Portal Documents Alert';
								$mail_params['message'] = $this->ci->load->view('email/documents_alert-html', $notif_documents[$i], TRUE);
								$mail_params['alt_message'] = $this->ci->load->view('email/documents_alert-txt', $notif_documents[$i], TRUE);
								
								$sending = $this->ci->_send_mail($mail_params);
								
								if ($sending) $log_type = 'info';
								else $log_type = 'error';
								
								log_message(
									$log_type, 
									'Documents Alert; Send To: '.$user['email'] .
                                    '; Date: ' . $data['created'].
									'; For User: ' . $user['username'] .
                                    '; For Account: ' . $notif_documents[$i]['guarantor_id'] .
                                    '; Document Name: ' . $notif_documents[$i]['document_name'] .
                                    '; Document Type: ' . $notif_documents[$i]['document_type'] .
                                    '; Document Path: ' . $notif_documents[$i]['full_path'] .
                                    '; '
                                );
							}
						}
					}
				}
			}
		}

		return array(count($notif_documents), $counter);
	}
	
	public function create_discharged_notifications($begin = NULL, $end = NULL, $users_list = NULL)
	{
		$this->ci->load->library('mshc_connector');
		
		$conds_pat_disc = array();
		$conds_pat_disc['tr_service_date'][0] = $begin;
		$conds_pat_disc['tr_service_date'][1] = $end;
		
		unset($notif_patient_dischargeds);
		$notif_patient_dischargeds = $this->ci->mshc_connector->getCronDischargedPatients(
			array(1, 2, 3, 4, 5), 
			'all', 
			array(
				'conds' => $conds_pat_disc, 
				'debugReturn' => 'sample'
			)
		);
		//echo '<pre>'.print_r($notif_patient_dischargeds, true).'</pre>';return;
		
		$counter = 0;
		$result = array();
		if (is_array($notif_patient_dischargeds)) {
			for ($i = 0; $i < count($notif_patient_dischargeds); $i++) 
			{				
				if (isset($notif_patient_dischargeds[$i]['employer_id']) && $notif_patient_dischargeds[$i]['employer_id'] > 0) {
					unset($users);
					/*$conds = array(
						'ext_db_id' => array_search(
							strtoupper($notif_patient_dischargeds[$i]['database_name']), 
							$this->ci->mshc_connector->getDBArray()
						),
						'external_id1' => $notif_patient_dischargeds[$i]['guarantor_id'],
						'external_id2' => $notif_patient_dischargeds[$i]['practice_id'],
						'external_id3' => $notif_patient_dischargeds[$i]['case_no'],
						'external_id4' => $notif_patient_dischargeds[$i]['patient_no'],
					);*/
					$users = $this->ci->users->get_user_by_employer_id(
						$notif_patient_dischargeds[$i]['employer_id'], 
						array_search(strtoupper($notif_patient_dischargeds[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						$users_list, 
						array('case_discharge_notified')
					);
					//$this->users->get_user_by_case($conds);
					
					$result[] = array(
						'employer_id' => $notif_patient_dischargeds[$i]['employer_id'],
						'db' => array_search(strtoupper($notif_patient_dischargeds[$i]['database_name']), $this->ci->mshc_connector->getDBArray()),
						'service_date_from' => $notif_patient_dischargeds[$i]['service_date_from'],
						'guarantor_id' => $notif_patient_dischargeds[$i]['guarantor_id'],
						'users_num' => count($users),
						'users' => $users
					);
					
					if (is_array($users) && count($users)) {
						unset($data);
						$data['title'] = 'Patient Discharge for '.
							$notif_patient_dischargeds[$i]['first_name'].' '.
							$notif_patient_dischargeds[$i]['last_name'];
						$data['type'] = 'discharged';

						/** @var DateTime $service_date_from */
                        $service_date_from = $notif_patient_dischargeds[$i]['service_date_from'];
						if ($service_date_from instanceof DateTime) {
							$date_from = $service_date_from->format('m/d/Y');
							$data['notification_date'] = date('Y-m-d H:i:s', strtotime($date_from));
						} else {
							$date_from = 'N/A';
							$data['notification_date'] = date('Y-m-d H:i:s');
						}
						$data['body'] = 'Guarantor ID: '.$notif_patient_dischargeds[$i]['guarantor_id'].'; Service Date: '.$date_from.br().
							anchor(
								base_url().
								implode(
									'/',
									array(
										MSHC_CASES_CONTROLLER_NAME,
										MSHC_CASES_CLIENT_SEARCH_NAME,
										'0',
										'summary',
										$notif_patient_dischargeds[$i]['guarantor_id'],
										$notif_patient_dischargeds[$i]['database_name'],
										$notif_patient_dischargeds[$i]['practice_id'],
										$notif_patient_dischargeds[$i]['patient_no'],
										$notif_patient_dischargeds[$i]['case_no']
									)
								),
								'Click here to view Case Summary.'
							);
						$data['created'] = date('Y-m-d H:i:s');
						$not_id = $this->ci->notifications->add_new_notifications($data);
						if ($not_id > 0) {
							foreach ($users as $user)
							{
                                if ($user['id'] != 3) {
                                    //continue;
                                }

								++$counter;
								$this->ci->notifications->add_notifications_users(array('notification_id' => $not_id, 'user_id' => $user['id']));
								
								$mail_params['send_to'] = $user['email'];
								$mail_params['send_from'] = 'settlement@mshc.bz';
								$mail_params['subject'] = 'MSHC Portal Discharged Client Alert';
								$mail_params['message'] = $this->ci->load->view('email/discharge_alert-html', $notif_patient_dischargeds[$i], TRUE);
								$mail_params['alt_message'] = $this->ci->load->view('email/discharge_alert-txt', $notif_patient_dischargeds[$i], TRUE);
								
								$sending = $this->ci->_send_mail($mail_params);
								
								if ($sending) $log_type = 'info';
								else $log_type = 'error';
								
								log_message(
									$log_type, 
									'Discharged Client Alert; Send To: '.$user['email'].'; Date: '.$data['created'].
									'; For User: '.$user['username'].'; For Account: '.$notif_patient_dischargeds[$i]['guarantor_id'].'; ');
							}
						}
					}
				}
			}
		}
		
		//print_r($result);
		return array(count($notif_patient_dischargeds), $counter);
	}
	
	public function create_high_charges($begin = NULL, $end = NULL, $users_list = NULL)
	{		
		$users_high_charges = $this->ci->notifications->get_users_high_charges($users_list);
		$counter = 0;
		
		if (count($users_high_charges)) {
			foreach ($users_high_charges as $user)
			{
                if ($user['user_id'] != 3) {
                    //continue;
                }

				unset($attys);
				$attys = array();
				foreach ($user['user_attys'] as $atty)
				{
					$attys[] = array(
						'attorney_id' => $atty['attorney_id'],
						'database' => strtolower($atty['database'])
					);
				}

				$high_charges = $this->ci->mshc_connector->getCronHighCharge(
					array(1, 2, 3, 4, 5), 
					'all', 
					array(
						'conds' => array(
							'attorney_id' => array(
								'op' => 'or', 
								'value' => $attys
							),
						), 
						'debugReturn' => 'sample',
					) 
				);
				
				foreach ($high_charges as $charge)
				{
					$UniqueCaseID = mb_convert_encoding($charge['UniqueCaseID'], 'UTF-8');
					$current_level = $this->ci->notifications->get_high_charge_level($user['user_id'], $UniqueCaseID);
					$next_level = 0;
					
					if ($charge['grand_total'] > $user['level1']) {
						$next_level = 1;
					}
					
					if ($user['level2'] > 0 && $charge['grand_total'] > $user['level2']) {
						$next_level = 2;
					}
					
					if ($user['level3'] > 0 && $charge['grand_total'] > $user['level3']) {
						$next_level = 3;
					}

					$create_notification = FALSE;
					
					if ($current_level !== FALSE && $next_level > $current_level) {
						$this->ci->notifications->set_high_charge_level($user['user_id'], $UniqueCaseID, $next_level);
						$create_notification = TRUE;
					} elseif ($current_level === FALSE) {
						$this->ci->notifications->add_high_charge_level($user['user_id'], $UniqueCaseID, $next_level);
						
						/*if ($next_level > 0) {
							$create_notification = TRUE;
						}*/
					}
					
					if ($create_notification) {
						$data['title'] = 'High Charge for '.
							mb_convert_encoding($charge['first_name'], 'UTF-8').' '.
							mb_convert_encoding($charge['last_name'], 'UTF-8');
						$data['type'] = 'high_charge';
						
						if ($charge['accident_date'] instanceof DateTime) {
							$accident_date = $charge['accident_date']->format('m/d/Y');
						} else {
							$accident_date = 'N/A';
						}
						if ($charge['birth_date'] instanceof DateTime) {
							$birth_date = $charge['birth_date']->format('m/d/Y');
						} else {
							$birth_date = 'N/A';
						}

						$data['body'] = '
							Date of accident: '.$accident_date.'
							<br />
							Date of birth: '.$birth_date.'
							<br />
							<br />
							Total charge threshold of $'.number_format($charge['grand_total'], 2);
						
						$data['created'] = $data['notification_date'] = date('Y-m-d H:i:s');

						$not_id = $this->ci->notifications->add_new_notifications($data);
						if ($not_id > 0) {
							++$counter;
							$this->ci->notifications->add_notifications_users(array('notification_id' => $not_id, 'user_id' => $user['user_id']));
							
							$mail_params['send_to'] = $user['email'];
							$mail_params['send_from'] = 'BusinessOfficeManagers@mshc.bz';
							$mail_params['send_from_name'] = 'MSHC Attorney Portal';
							$mail_params['subject'] = 'MSHC Portal High Charge Case Alert';
							$mail_params['message'] = $this->ci->load->view('email/high_charge_alert-html', $charge, TRUE);
							$mail_params['alt_message'] = $this->ci->load->view('email/high_charge_alert-txt', $charge, TRUE);
							
							$sending = $this->ci->_send_mail($mail_params);
							
							if ($sending) $log_type = 'info';
							else $log_type = 'error';
							
							log_message(
								$log_type, 
								'High Charge Case Alert; Send To: '.$user['email'].'; Date: '.$data['created'].
								'; For User: '.$user['username'].'; For Account: '.$charge['account'].'; ');
						}
					}
				}
			}
		}
		
		return $counter;
	}

    /**
     * @param $haystack
     * @param string
     * @return array
     */
    public function mergePDF($haystack, $path = MSHC_STATEMENTS_FILE_PATH)
    {
        $flag = true;
        $start = 0;
        $slicedArr = array();
        do {
            $slice = array_slice($haystack, $start, 50);
            if (count($slice)) {
                $start += 50;
                $output = FCPATH . $path . DIRECTORY_SEPARATOR . time() . '_tmp_'.$start . '.pdf';
                $exec = '"C:\Program Files (x86)\PDFtk Server\bin\pdftk.exe" '.implode(' ', $slice).' cat output '.$output . ' 2>&1';
                $slicedArr[] = $output;
                exec($exec, $return);
            } else {
                $flag = false;
            }
        } while ($flag);

        return $slicedArr;
    }

    /**
     * @param $search
     * @return string
     */
    public function getWhereFunction($search)
    {
        switch ($search) {
            case 'sorting-equal': $function = 'get_query_equal'; break;
            case 'sorting-not-equal': $function = 'get_query_not_equal'; break;
            case 'sorting-greater-than': $function = 'get_query_greater_than'; break;
            case 'sorting-less-than': $function = 'get_query_less_than'; break;
            default: $function = 'get_query_like'; break;
        }

        return $function;
    }
}

/* End of file mshc_general.php */
/* Location: ./application/libraries/mshc_general.php */