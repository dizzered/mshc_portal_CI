<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for portal_settings
*/

class Portal_settings extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
    }

	/*
	* Get portal_settings
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_portal_settings($query_params = array(), $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from', $query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('portal_settings_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}
	
	/*
	* Add new portal_settings to PORTAL_SETTINGS table
	*/
	public function add_new_portal_settings($data)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->_user['user_id'];

		if ($this->db->insert(self::tableName('portal_settings_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update portal_settings to PORTAL_SETTINGS table
	*/
	public function update_portal_settings($data)
	{
		if (!key_exists('id', $data) OR !intval($data['id'])) {
			return FALSE;
		} else {
			// set where clause
			$this->db->where('id', $data['id']);
			$this->db->update(self::tableName('portal_settings_table_name'), $data);

			return $data['id'];
		}
	}
	
	/*
	* Delete portal_settings from PORTAL_SETTINGS table
	*/
	public function delete_form($form_id)
	{
		if ($form_id) {
			$this->db->where('id', $form_id);
			$this->db->delete(self::tableName('portal_settings_table_name'));
		}
	}
	
	/*
	* Get form by id
	*/
	public function get_portal_settings_by_id($portal_settings_id)
	{
		$this->db->from(self::tableName('portal_settings_table_name'));
		$this->db->where('id',$portal_settings_id);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}
		
}

/* End of file portal_settings.php */
/* Location: ./application/core/portal_settings.php */