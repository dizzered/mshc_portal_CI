<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for maintance of marketers
*/

class Marketers extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
    }

	/*
	* Get marketers
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_marketers($query_params = array(), $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from', $query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('marketers_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Add new marketer to MARKETERS table
	*/
	public function add_new_marketer($data)
	{
		if ($this->db->insert(self::tableName('marketers_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update marketer to MARKETERS table
	*/
	public function update_marketer($data)
	{
		if (!array_key_exists('id', $data) || !intval($data['id'])) {
			return FALSE;
		} else {
			// set where clause
			$this->db->where('id', $data['id']);
			$this->db->update(self::tableName('marketers_table_name'), $data);

			return $data['id'];
		}
	}
	
	/*
	* Delete marketer from MARKETERS table
	*/
	public function delete_marketer($marketer_id)
	{
		if ($marketer_id) {
			$this->db->where('id', $marketer_id);
			$this->db->delete(self::tableName('marketers_table_name'));
		}
	}
	
	/*
	* Get marketer by id
	*/
	public function get_marketer_by_id($marketer_id)
	{
		$this->db->from(self::tableName('marketers_table_name'));
		$this->db->where('id',$marketer_id);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}
	
	
	/*
	* Check email for uniqueness
	*/
	public function check_unique_email($email)
	{
		$this->db->where('email',$email);

		$result = $this->db->get(self::tableName('marketers_table_name'));
		return ($result->num_rows() > 0) ? FALSE : TRUE;
	}
	
}

/* End of file marketers.php */
/* Location: ./application/model/marketers.php */