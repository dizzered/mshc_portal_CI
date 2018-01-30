<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('application/libraries/phpass-0.3/PasswordHash.php');

/*
* Model for maintenance of users
*/

class Users extends MSHC_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Save data for user's autologin
     *
     * @param    int
     */
    public function _create_autologin($user_id)
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        set_cookie(array(
            'name' => $this->config->item('autologin_cookie_name', 'auth'),
            'value' => serialize(array('user_id' => $user_id, 'key' => $key)),
            'expire' => $this->config->item('autologin_cookie_life', 'auth'),
        ));
        $this->db->where('id', $user_id);
        $data = array(
            'autologin_key' => $key
        );
        $this->db->update(self::tableName('users_table_name'), $data);
    }

    /**
     * Clear user's autologin data
     *
     * @param integer
     * @return void
     */
    public function _delete_autologin($user_id)
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie($this->config->item('autologin_cookie_name', 'auth'), TRUE)) {
            //$data = unserialize($cookie);
            delete_cookie($this->config->item('autologin_cookie_name', 'auth'));
            $this->db->where('id', $user_id);
            $data = array('autologin_key' => NULL);

            $this->db->update(self::tableName('users_table_name'), $data);
        }
    }

    /**
     * Get user data for auto-logged in user.
     * Return NULL if given key or user ID is invalid.
     *
     * @param    int
     * @param    string
     * @return    array
     */
    public function _get_autologin($user_id, $key)
    {
        $params = array(
            'where' => array(
                'id' => $user_id,
                'autologin_key' => $key
            )
        );
        $user = $this->get_users($params);
        if (count($user)) return $user[0];
        else return NULL;
    }

    /*
    * Method to secure random-generated password
    * Now used hashing with phpass library
    */
    public function _secure_password($password)
    {
        // Hash password using phpass
        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'auth'),
            $this->config->item('phpass_hash_portable', 'auth')
        );
        return $hashed_password = $hasher->HashPassword($password);
    }

    /*
    * Get info about current authorized user
    */
    public function _get_user()
    {
        $params = array(
            'where' => array(
                self::tableName('users_table_name') . '.id' => $this->session->userdata('user_id')
            ),
            'fields' => array(
                '*' => '',
                self::tableName('users_table_name') . '.id' => 'user_id',
                self::tableName('legal_firms_table_name') . '.name' => 'firm_name'
            ),
            'join' => array(
                array(
                    'table' => self::tableName('legal_users_table_name'),
                    'condition' => self::tableName('legal_users_table_name') . '.user_id = ' . self::tableName('users_table_name') . '.id',
                    'type' => 'left'
                ),
                array(
                    'table' => self::tableName('legal_firms_users_table_name'),
                    'condition' => self::tableName('legal_firms_users_table_name') .
                        '.user_id = ' . self::tableName('users_table_name') . '.id AND ' .
                        self::tableName('legal_firms_users_table_name') . '.is_primary = 1',
                    'type' => 'left'
                ),
                array(
                    'table' => self::tableName('legal_firms_table_name'),
                    'condition' => self::tableName('legal_firms_table_name') . '.id = ' . self::tableName('legal_firms_users_table_name') . '.legal_firm_id',
                    'type' => 'left'
                ),
            )
        );
        $user = $this->get_users($params);

        if (count($user)) return $user[0];
        else return $user;
    }

    /*
    * Get user by username for logging in
    */
    public function get_user_by_username($username)
    {
        if ($username) {
            $params = array(
                'where' => array(
                    'LOWER(username) = ' => strtolower($username)
                )
            );
            $user = $this->get_users($params);
            if (count($user)) return $user[0];
        }
        return NULL;
    }

    /*
    * Check username for uniqueness
    */
    public function check_unique_username($username)
    {
        $this->db->where('username', $username);
        $result = $this->db->get(self::tableName('users_table_name'));
        return ($result->num_rows() > 0) ? FALSE : TRUE;
    }

    /*
    * Check email for uniqueness
    */
    public function check_unique_email($email)
    {
        $this->db->where('email', $email);
        $result = $this->db->get(self::tableName('users_table_name'));
        return ($result->num_rows() > 0) ? FALSE : TRUE;
    }

    public function check_email_for_user($user_id, $email)
    {
        $this->db->where('email', $email);
        $result = $this->db->get(self::tableName('users_table_name'));
        if ($result->num_rows() > 0) {
            if ($result->row()->id == $user_id) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }

    /*
    * Get users
    * @param array $fields - key -> field name, value -> field alias (empty if not need)
    * @param array $from - key -> table name, value -> table alias (empty if not need)
    * @param array of array $join - table name, condition, type of join (left|right)
    * @param array $search - key -> field name, value -> field value
    * @param array $order - key -> field name, value -> direction (ASC|DESC)
    * @param array $group - value -> field name
    */
    public function get_users($query_params = array(), $escape = TRUE)
    {
        // Set FROM
        $from = get_array_value('from', $query_params);
        if ($from == NULL) {
            $query_params['from'] = array(
                self::tableName('users_table_name') => ''
            );
        }

        $result = $this->_get_query($query_params, $escape);
        return ($result->num_rows() > 0) ? $result->result_array() : array();
    }

    /*
    * Add new user to USERS table
    */
    public function add_new_user($data)
    {
        if ($this->db->insert(self::tableName('users_table_name'), $data)) {
            return $this->db->insert_id();
        }

        return FALSE;
    }

    /*
    * Update user to USERS table
    */
    public function update_user($user_id, $data)
    {
        if ($user_id) {
            $this->db->where('id', $user_id);
            $this->db->update(self::tableName('users_table_name'), $data);

            return TRUE;
        }

        return FALSE;
    }

    /*
    * Add new user in LEGAL_USERS table
    */
    public function add_new_legal_user($data)
    {
        if ($this->db->insert(self::tableName('legal_users_table_name'), $data)) {
            return TRUE;
        }

        return FALSE;
    }

    /*
    * Update user in LEGAL_USERS table
    */
    public function update_legal_user($user_id, $data)
    {
        if ($user_id) {
            $this->db->where('user_id', $user_id);
            $this->db->update(self::tableName('legal_users_table_name'), $data);

            return TRUE;
        }

        return FALSE;
    }

    /*
    * Delete user from USERS table
    */
    public function delete_user($user_id)
    {
        if ($user_id) {
            $this->db->where('id', $user_id);
            $this->db->delete(self::tableName('users_table_name'));
        }
    }

    /*
    * Delete user from LEGAL_USERS table
    */
    public function delete_legal_user($user_id)
    {
        if ($user_id) {
            $this->db->where('user_id', $user_id);
            $this->db->delete(self::tableName('legal_users_table_name'));
        }
    }

    /*
    * Chenge user password
    */
    public function change_user_password($user_id, $new_password)
    {
        if ($user_id) {
            $data['password'] = $this->_secure_password($new_password);
            $data['last_password_changed_date'] = date('Y-m-d H:i:s');
            $this->db->where('id', $user_id);
            $this->db->update(self::tableName('users_table_name'), $data);
        }
    }

    /**
     * Increase number of attempts for given login
     *
     * @param    string
     * @param    string
     * @return    void
     */
    public function increase_login_attempt($username)
    {
        /*		$data = array(
                    'failed_password_attempt_count' => 'failed_password_attempt_count + 1'
                );
                $this->db->where('username',$username);
        */
        $sql = "UPDATE " . self::tableName('users_table_name') . "
		SET failed_password_attempt_count = failed_password_attempt_count + 1 
		WHERE username = '" . $username . "'";
        $this->db->query($sql);
    }

    /**
     * Get number of attempts to login occured from given IP-address AND login
     *
     * @param    string
     * @param    string
     * @return    int
     */
    public function get_login_attempts_num($username)
    {
        $this->db->select('failed_password_attempt_count');
        $this->db->where('username', $username);
        $query = $this->db->get(self::tableName('users_table_name'));
        $row = $query->row_array();

        if (count($row)) return $row['failed_password_attempt_count'];
        else return 0;
    }

    /**
     * Clear all attempt records for given login.
     *
     * @param    string
     * @return    void
     */
    function clear_login_attempts($username)
    {
        $data = array(
            'failed_password_attempt_count' => 0
        );
        $this->db->where('username', $username);
        $this->db->update(self::tableName('users_table_name'), $data);
    }

    public function set_user_locked_out($username)
    {
        $data = array(
            'is_locked_out' => 1,
            'last_lockout_date' => date('Y-m-d H:i:s')
        );
        $this->db->where('username', $username);
        $this->db->update(self::tableName('users_table_name'), $data);
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param    int
     * @return    void
     */
    public function update_login_info($user_id)
    {
        $data = array(
            'last_login_date' => date('Y-m-d H:i:s'),
            'last_activity_date' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $user_id);
        $this->db->update(self::tableName('users_table_name'), $data);
    }

    public function is_user_editable($user_id = 0, $user_role = '')
    {
        if ($this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            return TRUE;
        }
        if ($user_id) {
            $params = array(
                'from' => array(
                    self::tableName('users_table_name') => 'u'
                ),
                'where' => array(
                    'u.id' => $user_id
                )
            );
            $user_data = $this->get_users($params);
            $user_role = $user_data[0]['role_id'];
        }
        if ($this->_user['role_id'] == MSHC_AUTH_GENERAL_USER && $user_role != MSHC_AUTH_SYSTEM_ADMIN) {
            return TRUE;
        }
        return FALSE;
    }

    // get user_id by guarantor_id

    public function get_user_by_employer_id($employer_id, $ext_db_id, $users_list = NULL, $users_notifs = NULL)
    {
        $this->db->from(self::tableName('users_table_name') . ' AS u');
        if ($users_notifs) {
            $join = array();
            foreach ($users_notifs as $notif) {
                $join[] = 'lu.' . $notif . ' = 1';
            }
            $this->db->join(self::tableName('legal_users_table_name') . ' AS lu', ' u.id = lu.user_id AND ' . implode(' AND ', $join));

            $join = array();
            foreach ($users_notifs as $notif) {
                $join[] = 'lu.' . $notif;
            }
            $this->db->select('DISTINCT(u.id), u.*, ' . implode(', ', $join));
        } else {
            $this->db->select('DISTINCT(u.id), u.*, lu.*');
            $this->db->join(self::tableName('legal_users_table_name') . ' AS lu', ' u.id = lu.user_id ');
        }

        $this->db->join(self::tableName('legal_attorneys_users_table_name') . ' AS lau', ' u.id = lau.user_id ');
        $this->db->join(
            self::tableName('ext_dbs_legal_attys_table_name') . ' AS edla',
            ' lau.legal_atty_id = edla.legal_atty_id AND edla.external_id = ' . $employer_id . ' AND edla.ext_db_id = ' . $ext_db_id
        );
        if ($users_list) {
            $this->db->where_in('u.id', $users_list);
        }

        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->result_array() : array();
    }

    public function get_user_by_case($conds = array())
    {
        if (is_array($conds) && count($conds)) {
            $this->db->from(self::tableName('cases_case_mgrs_table_name'));

            foreach ($conds as $field => $val) {
                $this->db->where($field, $val);
            }

            $this->db->join(self::tableName('users_table_name'), self::tableName('users_table_name') . '.id = ' . self::tableName('cases_case_mgrs_table_name') . '.user_id');
            $query = $this->db->get();

            if ($query->num_rows()) return $query->row();
        }

        return NULL;
    }

    public function get_legal_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get(self::tableName('legal_users_table_name'));

        if ($query->num_rows()) return $query->row();
        else return NULL;

    } // get_legal_user

    public function get_primary_firm($user_id)
    {
        $this->db->from(self::tableName('users_table_name') . ' AS u');
        $this->db->join(self::tableName('legal_firms_users_table_name') . ' as fu', 'u.id = fu.user_id');
        $this->db->where('u.id', $user_id);
        $this->db->where('fu.is_primary', TRUE);
        $query = $this->db->get();

        if ($query->num_rows()) return $query->row();
        return NULL;
    }

    public function is_mshc_internal_user($user_id)
    {
        $userPrimaryFirm = $this->get_primary_firm($user_id);
        if (!empty($userPrimaryFirm) and $userPrimaryFirm->legal_firm_id == MSHC_AMM_FIRM_ID) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

/* End of file users.php */
/* Location: ./application/model/users.php */