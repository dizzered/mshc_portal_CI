<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for maintenance of firms and attorneys
*/

class Clients extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
		$this->load->library('mshc_connector');
    }
	
	/*
	* Get clients
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_clients($query_params, $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('clients_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
		
	/*
	* Add new client
	*/
	public function add_new_client($data)
	{
		if ($this->db->insert(self::tableName('clients_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update client
	*/
	public function update_client($client_id, $data)
	{
		if ($client_id) {
			// set where clause
			$this->db->where('id', $client_id);
			$this->db->update(self::tableName('clients_table_name'), $data);

			if ($this->db->affected_rows()) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/*
	* Delete client
	*/
	public function delete_client($client_id)
	{
		if ($client_id)
		{
			$this->db->where('id', $client_id);
			$this->db->delete(self::tableName('clients_table_name'));
		}
	}
	
	/*
	* Get list of available practice locations
	*/
	public function get_external_locations($group = TRUE)
	{
		$queryParams = array(
			'conds' => array(
                'is_active' => 1,
            ), 
            'debugReturn' => 'sample',
		);
		if ($group) {
			$queryParams['group'] = array('display_name');
		}

		return $this->mshc_connector->getLocationNames(array(2,5), 'all', $queryParams);
	} // get_external_locations
	
	/*
	* Get list of external appointment reasons
	*/
	public function get_external_appt_reasons()
	{
		$queryParams = array(
            'debugReturn' => 'sample',
		);

		return $this->mshc_connector->getAppointmentReasons(array(1,2,3,4,5), 'all', $queryParams);
	} // get_external_appt_reasons
	
	/*
	* Get available financial classes
	*/
	public function get_available_fin_classes()
	{
		$result = $this->db->get(self::tableName('fin_classes_table_name'));
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	} // get_available_fin_classes

	/*
	* Get available financial groups
	*/
	public function get_available_fin_groups()
	{
		$result = $this->db->get(self::tableName('fin_groups_table_name'));
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	} // get_available_fin_groups
	
	/*
	* Add financial group
	*/
	public function add_fin_group($data)
	{
		if ($this->db->insert(self::tableName('fin_groups_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;		
	} // add_fin_group
	
	public function get_fin_groups($query_params = array())
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('fin_groups_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get external db
	*/
	public function get_external_dbs($query_params = array())
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('ext_dbs_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params);
		return ($result->num_rows() > 0) ? $result->result_array() : array();		
	}
	
	/*
	* Get practices
	*/
	public function get_practices($query_params, $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('practices_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}

	/*
	* Add practice
	*/
	public function add_practice($data)
	{
		if ($this->db->insert(self::tableName('practices_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;		
	} // add_practice

	/*
	* Add practice finances
	*/
	public function add_practice_finances($data)
	{
		if ($this->db->insert_batch(self::tableName('practices_finances_table_name'), $data)) {
			return TRUE;
		}

		return FALSE;		
	} // add_practice_finances

	/*
	* Delete practice finances
	*/
	public function delete_practice_finances($practice_id)
	{
		if ($practice_id) {
			$this->db->where('practice_id', $practice_id);
			$this->db->delete(self::tableName('practices_finances_table_name'));
		}
	} // delete_practice_finances

	/*
	* Add practice appt reasons
	*/
	public function add_practice_appt_reasons($data)
	{
		if ($this->db->insert_batch(self::tableName('practices_appt_reasons_table_name'), $data)) {
			return TRUE;
		}

		return FALSE;		
	} // add_practice_appt_reasons

	/*
	* Delete practice appt reasons
	*/
	public function delete_practice_appt_reasons($practice_id)
	{
		if ($practice_id) {
			$this->db->where('practice_id', $practice_id);
			$this->db->delete(self::tableName('practices_appt_reasons_table_name'));
		}
	} // delete_practice_appt_reasons
	
	public function update_practice_appt_reasons($practice_id, $appt_reason_data)
	{
		$ext_appt = $this->get_practice_appt_reasons($practice_id);
		
		if (is_array($appt_reason_data) && count($appt_reason_data)) {
			foreach ($appt_reason_data as $appt)
			{
				if ($appt['map_id'] == 0) {
					$this->mshc_connector->manageAppointmentReason(
						array(1, 2, 3, 4, 5),
						'insert',
						array(
							'values' => array(
								'practice' => $practice_id,
								'sys_reason' => $appt['code_id'],
								'ui_reason' => $appt['reason_id']
							)
						)
					);
				} else {
					$element = array_multi_search($ext_appt, 'MappingId', $appt['map_id']);
					if (!is_null($element)) {
						if ($ext_appt[$element]['PMSReason'] != $appt['code_id'] || $ext_appt[$element]['AMMReason'] != $appt['reason_id']) {
							$this->mshc_connector->manageAppointmentReason(
								array(1, 2, 3, 4, 5),
								'update',
								array(
									'values' => array(
										'practice' => $practice_id,
										'sys_reason' => $appt['code_id'],
										'ui_reason' => $appt['reason_id'],
										'id' => $appt['map_id']
									)
								)
							);
						}
					}
				}
			}
		}
		
		foreach ($ext_appt as $appt)
		{
			if (is_null($appt_reason_data)) $appt_reason_data = array();
			
			$element = array_multi_search($appt_reason_data, 'map_id', $appt['MappingId']);
			if (is_null($element)) {
				$this->mshc_connector->manageAppointmentReason(
					array(1, 2, 3, 4, 5),
					'delete',
					array(
						'values' => array(
							'id' => $appt['MappingId']
						)
					)
				);
			}
		}
	} // update_practice_appt_reasons
	
	/*
	* Add practice locations
	*/
	public function add_practice_locations($data)
	{
		if ($this->db->insert_batch(self::tableName('practices_locations_table_name'), $data)) {
			return TRUE;
		}

		return FALSE;		
	} // add_practice_appt_reasons

	/*
	* Delete practice locations
	*/
	public function delete_practice_locations($practice_id)
	{
		if ($practice_id) {
			$this->db->where('practice_id', $practice_id);
			$this->db->delete(self::tableName('practices_locations_table_name'));
		}
	} // delete_practice_locations
	
	public function update_practice_locations($practice_id, $locations, $practice_data)
	{
		
		$mapping = array();
		$ext_locs = $this->get_practice_locations($practice_id, $practice_data);
		$ext_dbs = array();
		if (isset($practice_data[0]['ext_db_id1']) && $practice_data[0]['ext_db_id1']) $ext_dbs[] = $practice_data[0]['ext_db_id1'];
		if (isset($practice_data[0]['ext_db_id2']) && $practice_data[0]['ext_db_id2']) $ext_dbs[] = $practice_data[0]['ext_db_id2'];
		if (isset($practice_data[0]['ext_db_id3']) && $practice_data[0]['ext_db_id3']) $ext_dbs[] = $practice_data[0]['ext_db_id3'];
			
		if (is_array($locations) && count($locations)) {
			foreach($locations as $loc)
			{
				if (! $loc['map_id']) {
					$all_locs = $this->mshc_connector->getLocationNames(
						$ext_dbs,
						'all',
						array(
							'conds' => array(
								'display_name' => $loc['name']
							)
						)
					);

					if (is_array($all_locs) && count($all_locs)) {
						$dbs = $this->mshc_connector->getDBArray();
						
						foreach ($all_locs as $all_loc)
						{
							$ext_practice_id = NULL;
							$ext_db_id = array_search(strtoupper($all_loc['database_name']), $dbs);

							if ($practice_data[0]['ext_db_id1'] == $ext_db_id) {
								$ext_practice_id = $practice_data[0]['external_id1'];
							} elseif ($practice_data[0]['ext_db_id2'] == $ext_db_id) {
								$ext_practice_id = $practice_data[0]['external_id2'];
							} elseif ($practice_data[0]['ext_db_id3'] == $ext_db_id) {
								$ext_practice_id = $practice_data[0]['external_id3'];
							}

							if ($ext_practice_id) {
								$this->mshc_connector->manageLocationPractice(
									$ext_dbs,
									'insert',
									array(
										'values' => array(
											'database_name' => $all_loc['database_name'],
											'practice_id' => $ext_practice_id,
											'cost_center_id' => $all_loc['cost_center_id'],
											'portal_practice_id' => $practice_id
										)
									)
								);
							}
						}
					}
				} else {
					$map_array = explode(',', $loc['map_id']);
					foreach ($map_array as $map_id)
					{
						$mapping[] = $map_id;
					}
					unset($map_array);
				}
			}
		}
		
		foreach ($ext_locs as $ext_loc)
		{
			foreach ($ext_loc['map_id'] as $ext_map_id)
			{
				if (! in_array($ext_map_id, $mapping)) {
					$this->mshc_connector->manageLocationPractice(
						$ext_dbs,
						'delete',
						array(
							'values' => array(
								'id' => $ext_map_id
							)
						)
					);
				}
			}
		}
	}
	
	/*
	* Get practice locations
	*/
	public function get_practice_locations($practice_id, $practice_data)
	{
		if ($practice_id) {
			/*$params = array(
				'fields' => array(
					'DISTINCT(external_name)' => ''
				),
				'from' => array(
					$this->practices_locations_table_name => ''
				),
				'where' => array(
					'practice_id' => $practice_id
				)
			);
			$result = $this->_get_query($params);
			
			return ($result->num_rows() > 0) ? $result->result_array() : array();*/
			
			$ext_dbs = array();
			if (isset($practice_data[0]['ext_db_id1']) && $practice_data[0]['ext_db_id1']) $ext_dbs[] = $practice_data[0]['ext_db_id1'];
			if (isset($practice_data[0]['ext_db_id2']) && $practice_data[0]['ext_db_id2']) $ext_dbs[] = $practice_data[0]['ext_db_id2'];
			if (isset($practice_data[0]['ext_db_id3']) && $practice_data[0]['ext_db_id3']) $ext_dbs[] = $practice_data[0]['ext_db_id3'];
			
			if (count($ext_dbs)) {
				$ext_practice_locs = $this->mshc_connector->manageLocationPractice(
					$ext_dbs, 
					'all', 
					array('portal_practice_id' => $practice_id)
				);

				$locs = array();
				if (count($ext_practice_locs)) {
					$dbs = $this->mshc_connector->getDBArray();

					foreach($ext_practice_locs as $loc)
					{
						if (in_array(array_search(strtoupper($loc['database_name']), $dbs), $ext_dbs)) {
							if (!array_key_exists($loc['display_name'], $locs)) {
								$locs[$loc['display_name']] = array(
									'name' => $loc['display_name'],
									'map_id' => array()
								);
							}
							$locs[$loc['display_name']]['map_id'][] = $loc['map_id'];
						}
					}
				}
				return $locs;
			}
		}

		return array();
	} // get_practice_locations

	/*
	* Get practice finances
	*/
	public function get_practice_finances($practice_id)
	{
		if ($practice_id) {
			$params = array(
				'fields' => array(
					'pf.*' => '',
					'fg.name' => 'fin_group_name'
				),
				'from' => array(
                    self::tableName('practices_finances_table_name') => 'pf'
				),
				'join' => array(
					array(
						'table' => self::tableName('fin_groups_table_name').' AS fg',
						'condition' => 'fg.id = pf.fin_grp_id',
						'type' => ''
					)
				),
				'where' => array(
					'pf.practice_id' => $practice_id
				)
			);

			$result = $this->_get_query($params);
			return ($result->num_rows() > 0) ? $result->result_array() : array();
		}

		return array();
	} // get_practice_finances

	/*
	* Get practice appt reasons
	*/
	public function get_practice_appt_reasons($practice_id)
	{
		if ($practice_id) {
			$appt_reasons = $this->mshc_connector->manageAppointmentReason(
				array(1, 2, 3, 4, 5),
				'all',
				array('practice' => $practice_id)
			);
			
			return $appt_reasons ? $appt_reasons : array();
			/*$params = array(
				'from' => array(
					$this->practices_appt_reasons_table_name => ''
				),
				'where' => array(
					'practice_id' => $practice_id
				)
			);
			$result = $this->_get_query($params);
			
			return ($result->num_rows() > 0) ? $result->result_array() : array();*/
		}

		return array();
	} // get_practice_appt_reasons

	/*
	* Update practice
	*/
	public function update_practice($practice_id, $data)
	{
		if ($practice_id) {
			// set where clause
			$this->db->where('id', $practice_id);
			$this->db->update(self::tableName('practices_table_name'), $data);

			if ($this->db->affected_rows()) {
				return TRUE;
			}
		}

		return FALSE;
	} // update_practice

	/*
	* Delete practice
	*/
	public function delete_practice($practice_id)
	{
		if ($practice_id) {
			$this->db->where('id', $practice_id);
			$this->db->delete(self::tableName('practices_table_name'));
		}
	} // delete_practice_locations

}

/* End of file clients.php */
/* Location: ./application/model/clients.php */