<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for portal activity
*/

class Activity extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
    }
	
	/*
	* Create activity record
	* @param $activity_name
	* @param $activity_space - portal|legal|both
	* @param $activity_info
	*/
	public function add_activity_log($activity_name = '', $activity_space = 'both', $activity_info = '')
	{
		$activities_ary = array(
			'legal_id' => NULL,
			'portal_id' => NULL
		);
		switch ($activity_space)
		{
			case 'legal' :	$activities_ary['legal_id'] = $this->add_legal_activity($activity_name); break;
			case 'portal' :	$activities_ary['portal_id'] = $this->add_activity($activity_name); break;
			case 'both' :	$activities_ary['portal_id'] = $this->add_activity($activity_name); 
							$activities_ary['legal_id'] = $this->add_legal_activity($activity_name);
							break;
		}
		
		$data = array(
			'created' => date('Y-m-d H:i:s'),
			'user_id' => $this->session->userdata('user_id'),
			'portal_activity_id' => $activities_ary['portal_id'],
			'legal_portal_activity_id' => $activities_ary['legal_id'],
			'info' => $activity_info,
			'session_id' => $this->session->userdata('session_id'),
            'firm_id' => $this->session->userdata('firm_id'),
		);
		
		$this->db->insert(self::tableName('activity_logs_table_name'), $data);
	}
	
	protected function add_activity($name)
	{
		if ( ($id = $this->get_activity_id_by_name($name, self::tableName('activities_table_name'))) == 0) {
			$data = array(
				'name' => $name
			);
			$this->db->insert(self::tableName('activities_table_name'), $data);
			return $this->db->insert_id();
		}
		return $id;
	}
	
	protected function get_activity_id_by_name($name, $table_name) 
	{
		$this->db->select('id');
		$this->db->from($table_name);
		$this->db->where('name', $name);
		
		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row()->id : 0;
	}

	protected function add_legal_activity($name)
	{
		if ( ($id = $this->get_activity_id_by_name($name, self::tableName('legal_activities_table_name'))) == 0) {
			$data = array(
				'name' => $name
			);
			$this->db->insert(self::tableName('legal_activities_table_name'), $data);
			return $this->db->insert_id();
		}
		return $id;
	}
	
	public function get_activity_log()
	{
		$this->db->from(self::tableName('activity_logs_table_name').' AS al');
		$this->db->join(self::tableName('users_table_name').' AS u','u.id = al.user_id');
		$this->db->join(self::tableName('activities_table_name').' AS a','a.id = al.portal_activity_id');
		$this->db->join(self::tableName('legal_activities_table_name').' AS la','la.id = al.legal_portal_activity_id');

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}

    public function get_total_activities($query_params = array(), $escape = TRUE)
    {
        // Set FROM
        $from = get_array_value('from',$query_params);
        if ($from == NULL) {
            $query_params['from'] = array(
                self::tableName('activity_logs_table_name') => ''
            );
        }

        $query_params['fields'] = array('COUNT(pal.id)' => 'total');
        $query_params['group'] = array();

        $result = $this->_get_query($query_params, $escape);
        return ($result->num_rows() > 0) ? $result->row()->total : 0;
    }

	public function get_activities($query_params = array(), $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('activity_logs_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
        return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	public function get_latest_activity_log($query_params)
	{
		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('activity_logs_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	public function get_events_list() 
	{
		$query = "
			SELECT DISTINCT(name) FROM ".self::tableName('activities_table_name')." 
			UNION 
			SELECT DISTINCT(name) FROM ".self::tableName('legal_activities_table_name')."
			ORDER BY name ASC
		";
		
		$result = $this->db->query($query);
        return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
}

/* End of file activity.php */
/* Location: ./application/model/activity.php */