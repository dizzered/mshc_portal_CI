<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for maintenance of marketers
*/

class Contacts extends MSHC_Model 
{
	public $inquiry_types = array(
		'billing_information' => 1,
		'marketers' => 2,
		'representation_status' => 3,
		'scheduling_question' => 4,
		'settlement_request' => 5,
		'web_portal_support' => 6,
		'feature_request' => 7
	);
	
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
	public function get_contacts($query_params = array(), $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from', $query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('contacts_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Add new marketer to MARKETERS table
	*/
	public function add_new_contact($data)
	{
		$inquiry_type_id = $this->inquiry_types[$data['inquiry_type_id']];
		$data['inquiry_type_id'] = $inquiry_type_id;
		
		if ($this->db->insert(self::tableName('contacts_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update contact to CONTACTS table
	*/
	public function update_contact($data)
	{
		if (!key_exists('id', $data) OR !intval($data['id'])) {
			return FALSE;
		} else {
			// set where clause
			$this->db->where('id', $data['id']);
			$this->db->update(self::tableName('contacts_table_name'), $data);
			return $data['id'];
		}
	}
	
	/*
	* Delete contact from CONTACTS table
	*/
	public function delete_contact($contact_id)
	{
		if ($contact_id) {
			$this->db->where('id', $contact_id);
			$this->db->delete(self::tableName('contacts_table_name'));
		}
	}
	
	/*
	* Add new contact attachment to CONTACT_ATTACH table
	*/
	public function add_new_attach($data)
	{
		if ($this->db->insert(self::tableName('contacts_attach_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}
	
}

/* End of file contacts.php */
/* Location: ./application/model/contacts.php */