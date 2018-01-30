<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Model for maintance of notifications
*/

class Notifications extends MSHC_Model 
{
    public function __construct(){
        parent::__construct();
    }

	/*
	* Get notifications
	* @param array $fields - key -> field name, value -> field alias (empty if not need)
	* @param array $from - key -> table name, value -> table alias (empty if not need)
	* @param array of array $join - table name, condition, type of join (left|right)
	* @param array $search - key -> field name, value -> field value
	* @param array $order - key -> field name, value -> direction (ASC|DESC)
	* @param array $group - value -> field name
	*/
	public function get_notifications($query_params = array(), $escape = TRUE)
	{
		// Set FROM
		$from = get_array_value('from', $query_params);
		if ($from == NULL) {
			$query_params['from'] = array(
                self::tableName('notifications_table_name') => ''
			);
		}
				
		$result = $this->_get_query($query_params, $escape);
		return ($result->num_rows() > 0) ? $result->result_array() : array();
	}
	
	/*
	* Get notifications by user_id with parametrs
	*/
	public function get_notifications_by_user_id($user_id, $query_params =array(), $count_rows = FALSE)
	{
		$this->db->from(self::tableName('notifications_table_name').' AS n');
		$this->db->join(
            self::tableName('notifications_users_table_name').' AS nu ',
			' n.id = nu.notification_id AND nu.user_id = '.$user_id.$query_params['where'], 
			'inner'
        );
        /*$this->db->join(
            $this->notifications_users_table_name.' AS nu ',
            ' n.id = nu.notification_id AND nu.user_id = -1',
            'inner',
            FALSE);*/

        if (isset($query_params['order'])) {
			foreach ($query_params['order'] as $field => $order)
			{
				$this->db->order_by($field, $order);
			}
		} else {
			$this->db->order_by('n.created', 'DESC');
		}

		if (isset($query_params['jtStartIndex']))
			$this->db->limit($query_params['jtPageSize'], $query_params['jtStartIndex']);
		
		$result = $this->db->get();
		return ($result->num_rows() > 0) ? ($count_rows ? $result->num_rows() : $result->result_array()) : ($count_rows ? 0 : array());
	}
	
	/*
	* Add new notification to notifications table
	*/
	public function add_new_notifications($data)
	{
		if ($this->db->insert(self::tableName('notifications_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}
	
	/*
	* Add notification by user_id to notifications_users table
	*/
	public function add_notifications_users($data)
	{
		if ($this->db->insert(self::tableName('notifications_users_table_name'), $data)) {
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/*
	* Update notification to notifications table
	*/
	public function update_notifications($data)
	{
		if (!array_key_exists('id', $data) || !intval($data['id'])) {
			return FALSE;
		} else {
			// set where clause
			$this->db->where('id', $data['id']);
			unset($data['id']);

			$this->db->update(self::tableName('notifications_table_name'), $data);
			return true;
		}
	}
	
	/*
	* Delete notification from notifications table
	*/
	public function delete_notification($notification_id)
	{
		if ($notification_id) {
			$this->db->where('id', $notification_id);
			$this->db->delete(self::tableName('notifications_table_name'));
		}
	}
	
	/*
	* Delete notifications before day from notifications table
	*/
	public function delete_notifications_before_day($day)
	{
		if ($day) {
			$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") < ', date('Y-m-d', $day));
			$this->db->delete(self::tableName('notifications_table_name'));
		}
	}
	
	/*
	* Get notification by id
	*/
	public function get_notification_by_id($notification_id)
	{
		$this->db->from(self::tableName('notifications_table_name'));
		$this->db->where('id',$notification_id);

		$result = $this->db->get();
		return ($result->num_rows() > 0) ? $result->row_array() : array();
	}
	
	public function get_users_high_charges($users_list = NULL)
	{
		$this->db->from(self::tableName('legal_users_table_name').' AS lu');
		$this->db->where('lu.high_charges_notified', 1);
		$this->db->join(self::tableName('legal_attorneys_users_table_name').' AS lau', 'lau.user_id = lu.user_id');
		$this->db->join(self::tableName('ext_dbs_legal_attys_table_name').' AS edla', 'edla.legal_atty_id = lau.legal_atty_id');
		
		if ($users_list) {
			$this->db->join(self::tableName('users_table_name').' AS u', 'u.id = lu.user_id AND u.id IN ('.implode(',', $users_list).')');
		} else {
			$this->db->join(self::tableName('users_table_name').' AS u', 'u.id = lu.user_id');
		}
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result = $query->result();
			$users = array();
			$idx = array();

			foreach ($result as $user)
			{
				if (!in_array($user->user_id, $idx)) {
					$idx[] = $user->user_id;
					$users[$user->user_id] = array(
						'user_id' => $user->user_id,
						'email' => $user->email,
						'level1' => $user->high_charges_level1,
						'level2' => $user->high_charges_level2,
						'level3' => $user->high_charges_level3,
						'user_attys' => array()
					);
				}
				$users[$user->user_id]['user_attys'][] = array(
					'attorney_id' => $user->external_id,
					'database' => strtolower($user->ext_db_name)
				);
			}
			return $users;
		} else {
			return array();
		}
	}
	
	public function get_high_charge_level($user_id, $patient_id)
	{
		$this->db->select('level');
		$this->db->where('user_id', $user_id);
		$this->db->where('patient_id', $patient_id);

		$result = $this->db->get(self::tableName('high_charges_table_name'));
		return ($result->num_rows() > 0) ? $result->row()->level : FALSE;
	}
	
	public function set_high_charge_level($user_id, $patient_id, $level)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('patient_id', $patient_id);
		$data = array('level' => $level);
		$this->db->update(self::tableName('high_charges_table_name'), $data);
	}
	
	public function add_high_charge_level($user_id, $patient_id, $level)
	{
		$data = array(
			'user_id' => $user_id,
			'patient_id' => $patient_id,
			'level' => $level
		);
		$this->db->insert(self::tableName('high_charges_table_name'), $data);
	}
	
	public function mark_as_read($user_id, $notif_id)
	{
		$this->db->where('notification_id', $notif_id);
		$this->db->where('user_id', $user_id);
		$data = array('read' => 1);
		$this->db->update(self::tableName('notifications_users_table_name'), $data);
	}
	
	public function mark_as_deleted($user_id, $notif_id)
	{
		$this->db->where('notification_id', $notif_id);
		$this->db->where('user_id', $user_id);
		$data = array('deleted' => 1);
		$this->db->update(self::tableName('notifications_users_table_name'), $data);
	}
	
	public function delete_user_notification($user_id, $notif_id)
	{
		$this->db->where('notification_id', $notif_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete(self::tableName('notifications_users_table_name'));
	}
}

/* End of file notifications.php */
/* Location: ./application/model/notifications.php */