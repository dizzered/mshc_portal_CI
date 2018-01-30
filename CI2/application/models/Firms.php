<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for maintenance of firms and attorneys
*/

class Firms extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
    }

	/*
	* Get firms
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_firms($query_params)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('firms_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get firm by user_id
	*/
	public function get_firm_by_user_id($user_id)
	{
		$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
		$this->db->join(self::tableName('firms_table_name').' AS f','f.id = lfu.legal_firm_id');
		$this->db->where('lfu.user_id',$user_id);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}

	/*
	* Add new firm to LEGAL_FIRMS table
	*/
	public function add_new_firm($data)
	{
		if ($this->db->insert(self::tableName('firms_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update firm to LEGAL_FIRMS table
	*/
	public function update_firm($data)
	{
		if (!array_key_exists('id', $data) || !intval($data['id'])) {
			return FALSE;
		} else {
			// set where clause
			$this->db->where('id', $data['id']);
			$this->db->update(self::tableName('firms_table_name'), $data);

			return $data['id'];
		}
	}

	/*
	* Delete firm from LEGAL_FIRMS table
	*/
	public function delete_firm($firm_id)
	{
		if ($firm_id) {
			$this->db->where('id', $firm_id);
			$this->db->delete(self::tableName('firms_table_name'));
		}
	}

	/*
	* Delete attorneys by firm
	*/	
	public function delete_firm_attorneys($firm_id)
	{
		if ($firm_id) {
			$this->db->where('legal_firm_id', $firm_id);
			$this->db->delete(self::tableName('legal_attorneys_table_name'));
		}
	}
	
	/*
	* Check firm name for uniqueness
	*/
	public function check_unique_firm_name($firmname, $firm_id = 0)
	{
		$this->db->where('name',$firmname);
		$result = $this->db->get(self::tableName('firms_table_name'));

		if ($result->num_rows() > 0) {
			$id = $result->row()->id;
			
			if ($id == $firm_id) return TRUE;
			else return FALSE;
		}

		return TRUE;
	}

	/*
	* Get user's firms
	*/
	public function get_firms_by_user_id($user_id)
	{
		$this->db->select('lfu.*, lf.name, la.id AS legal_atty_id, la.*');
		$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
		$this->db->join(self::tableName('legal_firms_table_name').' AS lf','lf.id = lfu.legal_firm_id');
		$this->db->join(self::tableName('legal_attorneys_table_name').' AS la','la.legal_firm_id = lfu.legal_firm_id','left');
		$this->db->where('lfu.user_id', $user_id);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}

	/*
	* Get user's firms/attorneys
	*/
	public function get_firms_attorneys_by_user_id($user_id, $external_attroneys = FALSE)
	{
		$this->db->select('
			lfu.legal_firm_id, 
			lf.name, 
			la.first_name, 
			la.last_name, 
			la.id as legal_atty_id, 
			lfu.is_primary, 
			lfu.all_attorneys,
			lau.id as is_linked
		');
		$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
		$this->db->join(self::tableName('legal_firms_table_name').' AS lf','lf.id = lfu.legal_firm_id');
		$this->db->join(self::tableName('legal_attorneys_table_name').' AS la','la.legal_firm_id = lf.id');

		if ($external_attroneys) {
			$this->db->join(self::tableName('ext_dbs_legal_attys_table_name').' AS edla','la.id = edla.legal_atty_id AND edla.id IS NOT NULL');
		}

		$this->db->join(self::tableName('legal_attorneys_users_table_name').' AS lau','lau.user_id = lfu.user_id AND lau.legal_atty_id = la.id', 'left');
		$this->db->where('lfu.user_id',$user_id);

		//$this->db->order_by('lfu.legal_firm_id');
		$this->db->order_by('lf.name');
		//$this->db->group_by('la.id');

        $result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}

	/*
	* Get user's linked firms/attorneys
	*/
	public function get_linked_firms_attorneys_by_user_id($user_id)
	{
		$this->db->select('lau.* , la.first_name, la.last_name, la.legal_firm_id, lf.name, lfu.is_primary, lfu.all_attorneys');
		$this->db->from(self::tableName('legal_attorneys_users_table_name').' AS lau');
		$this->db->join(self::tableName('legal_attorneys_table_name').' AS la','la.id = lau.legal_atty_id');
		$this->db->join(self::tableName('legal_firms_table_name').' AS lf','lf.id = la.legal_firm_id');
		$this->db->join(self::tableName('legal_firms_users_table_name').' AS lfu','lfu.user_id = lau.user_id AND lfu.legal_firm_id = lf.id');
		$this->db->where('lau.user_id',$user_id);
		$this->db->order_by('lf.name');

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get primary firm
	*/
	public function get_primary_firm_by_user_id($user_id)
	{
		$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
		$this->db->join(self::tableName('legal_firms_table_name').' AS lf','lf.id = lfu.legal_firm_id', 'left');
		$this->db->where('lfu.user_id', $user_id);
		$this->db->where('lfu.is_primary IS NOT NULL');
		$this->db->limit(1);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}
	
	/*
	* Create user's relation with firms | LEGAL_FIRMS_USERS
	*/
	public function add_user_firms($data)
	{
		if (is_array($data) && count($data)) $this->db->insert_batch(self::tableName('legal_firms_users_table_name'), $data);
	}
	
	/*
	* Create user's relation with attorneys | LEGAL_ATTYS_USERS
	*/
	public function add_user_attorneys($data)
	{
		if (is_array($data) && count($data)) $this->db->insert_batch(self::tableName('legal_attorneys_users_table_name'), $data);
	}
	
	/*
	* Delete user's relation with firms | LEGAL_FIRMS_USERS
	*/
	public function delete_user_firms($user_id)
	{
		if ($user_id) {
			$this->db->where('user_id', $user_id);
			$this->db->delete(self::tableName('legal_firms_users_table_name'));
		}
	}

	/*
	* Delete user's relation with attorneys | LEGAL_ATTYS_USERS
	*/
	public function delete_user_attorneys($user_id)
	{
		if ($user_id) {
			$this->db->where('user_id', $user_id);
			$this->db->delete(self::tableName('legal_attorneys_users_table_name'));
		}
	}
	
	public function get_firms_attorneys($sort_by = 'all', $order_by = 'asc', $parse = FALSE)
	{
		if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
			$this->db->select('lfu.legal_firm_id');
			$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
			$this->db->where('lfu.user_id', $this->_user['user_id']);
			$query = $this->db->get();

			$firms = NULL;
			if ($query->num_rows()) {
				$firms = array();
				$result = $query->result();
				foreach ($result as $firm)
				{
					$firms[] = $firm->legal_firm_id;
				}
				$this->db->where_in('lf.id', $firms);
			} else {
				return array();
			}
		}

		$this->db->select('lf.id AS firm_id, lf.name AS firm_name, la.id AS atty_id, la.first_name, la.last_name');
		$this->db->from(self::tableName('legal_firms_table_name').' AS lf');
		$this->db->join(self::tableName('legal_attorneys_table_name').' AS la','la.legal_firm_id = lf.id', 'left');

		if ($sort_by != 'all') {
			$sort_by = $sort_by[0];
			$this->db->like('lf.name', $sort_by, 'after');
		}

		if ($order_by == 'asc') $order_by = 'ASC';
		else $order_by = 'DESC';

		$this->db->order_by('lf.name',$order_by);
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			$result = $result->result_array();
		} else {
			$result = array();	
		}

		if ($parse) {
			if (count($result)) {
				$firms_ary = array();
				$firms_result = array();
				for ($i = 0; $i < count($result); ++$i)
				{
					if (!in_array($result[$i]['firm_id'], $firms_ary)) {
						$firms_ary[] = $result[$i]['firm_id'];
						$firms_result[$result[$i]['firm_id']] = array();
						$firms_result[$result[$i]['firm_id']]['firm_name'] = $result[$i]['firm_name'];
						$firms_result[$result[$i]['firm_id']]['firm_attorneys'] = array();
					}
					$firms_result[$result[$i]['firm_id']]['firm_attorneys'][$result[$i]['atty_id']] = array(
						'first_name' => $result[$i]['first_name'],
						'last_name' => $result[$i]['last_name']
					);
					
				}
				$result = $firms_result;
			}
		}

		return $result;
	}

	/*
	* Add new attorney to LEGAL_ATTYS table
	*/
	public function add_attorney($data)
	{
		if ($this->db->insert(self::tableName('legal_attorneys_table_name'), $data)) {
			return $this->db->insert_id();
		}
		return FALSE;
	}
	
	/*
	* Remove old links and add new
	*/
	public function add_ext_attorneys($atty_id, $ext_attys)
	{
		$this->db->where('legal_atty_id', $atty_id);
		$this->db->delete(self::tableName('ext_dbs_legal_attys_table_name'));

		if ($ext_attys) {
			for($i = 0; $i < count($ext_attys); ++$i)
			{
				$ext_attys[$i]['external_id'] = $ext_attys[$i]['ext_atty_id'];
				$ext_attys[$i]['legal_atty_id'] = $atty_id;
				$ext_attys[$i]['external_atty_name'] = $ext_attys[$i]['ext_atty_name'];
				unset($ext_attys[$i]['ext_atty_name']);
				unset($ext_attys[$i]['ext_atty_id']);
			}

			$this->db->insert_batch(self::tableName('ext_dbs_legal_attys_table_name'), $ext_attys);
		}
	}

	/*
	* Update attorney in LEGAL_ATTYS table
	*/
	public function update_attorney($atty_id, $data)
	{
		if ($atty_id) {
			$this->db->where('id', $atty_id);
			$this->db->update(self::tableName('legal_attorneys_table_name'), $data);

			if ($this->db->affected_rows()) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/*
	* Delete attorney from LEGAL_ATTYS table
	*/
	public function delete_attorney($atty_id)
	{
		if ($atty_id) {
			$this->db->where('id', $atty_id);
			$this->db->delete(self::tableName('legal_attorneys_table_name'));
		}
	}
	
	/*
	* Add new record to legal_cases_legal_case_mgrs table
	*/
	public function add_legal_cases_legal_case_mgrs($data)
	{
		if ($this->db->insert(self::tableName('cases_case_mgrs_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}
	
	/*
	* Delete relation from legal_cases_legal_case_mgrs table
	*/
	public function delete_legal_cases_legal_case_mgrs($params = array())
	{
		if (is_array($params['where']) &&  count($params['where']) > 0) {
			foreach($params['where'] as $key => $value)
				$this->db->where($key, $value);

			$this->db->delete(self::tableName('cases_case_mgrs_table_name'));
		}
	}

	/*
	* Get attorneys
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_attorneys($query_params, $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('legal_attorneys_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get attorney from external DBs
	*/
	public function get_ext_attorneys($user_id = 0, $query_params = array('order' => array('database_name', 'employer_name'),'debugReturn' => 'sample'), $dbs = array(1, 2, 3, 4, 5))
	{
		$this->load->library('mshc_connector');

		if ($user_id){
			$dbs = $this->mshc_connector->getDBArray();
			$this->load->library('mshc_general');
			$attorneys_list = $this->mshc_general->getUserAttorneys();

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

				$query_params['conds'] = $conds;
			}
		}

		$attorneys = $this->mshc_connector->getAttorneys($dbs, 'all', $query_params);
		if ($attorneys) {
			return array(
				'attorneys' => $attorneys
			);
		} else {
			return array(
				'attorneys' => NULL,
				'error' => $this->mshc_connector->getError()
			);
		}
	}
	
	/*
	* Get assigned external attorneys
	*/
	public function get_assigned_attorneys($atty_id)
	{
		$this->db->select('external_id AS ext_atty_id, ext_db_id, external_atty_name AS ext_atty_name, ext_db_name');
		$this->db->where('legal_atty_id', $atty_id);

		$result = $this->db->get(self::tableName('ext_dbs_legal_attys_table_name'));
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get assigned cases
	*/
	public function get_assigned_cases($params)
	{
		if (isset($params['where'])) {
			foreach ($params['where'] as $key => $value) {
				$this->db->where($key, $value);
			}
		}
		if (isset($params['where_in'])) {
			foreach ($params['where_in'] as $key => $values) {
				$this->db->where_in($key, $values);
			}
		}

		$result = $this->db->get(self::tableName('cases_case_mgrs_table_name'));
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	public function get_users_by_firm_id($legal_firm_id, $all_attys = TRUE, $users_data = FALSE)
	{
		$this->db->from(self::tableName('legal_firms_users_table_name').' AS lfu');
		$this->db->where('lfu.legal_firm_id', $legal_firm_id);

		if ($all_attys) {
			$this->db->where('lfu.all_attorneys', 1);
		}
		
		if ($users_data) {
			$this->db->join(self::tableName('users_table_name').' AS u', 'u.id = lfu.user_id');
		}
		
		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->result() : NULL;
		
	} // get_users_by_firm_id
}

/* End of file firms.php */
/* Location: ./application/model/firms.php */