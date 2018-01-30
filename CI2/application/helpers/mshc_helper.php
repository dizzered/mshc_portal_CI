<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * get_array_value
 *
 * Checks for existence of the key and gets array value without throwing exception.
 *
 * @access	public
 * @param	string
 * @param	array
 * @return	mixed
 */
if (!function_exists('get_array_value')) 
{
	function get_array_value($key = '', $array = array())
	{
		if (!is_array($array)) return NULL;
		if (!array_key_exists($key, $array)) return NULL;
		return $array[$key];
	}
}

if (!function_exists('get_user_roles_array')) 
{
	function get_user_roles_array($user_role)
	{
		$ary = array();
		switch ($user_role)
		{
			case MSHC_AUTH_SYSTEM_ADMIN: $ary = array(
				MSHC_AUTH_SYSTEM_ADMIN => MSHC_AUTH_SYSTEM_ADMIN_NAME,
				//MSHC_AUTH_GENERAL_USER => MSHC_AUTH_GENERAL_USER_NAME,
				MSHC_AUTH_FIRM_ADMIN => MSHC_AUTH_FIRM_ADMIN_NAME,
				MSHC_AUTH_ATTORNEY => MSHC_AUTH_ATTORNEY_NAME,
				MSHC_AUTH_CASE_MANAGER => MSHC_AUTH_CASE_MANAGER_NAME,
				MSHC_AUTH_BILLER => MSHC_AUTH_BILLER_NAME
			); break;
			case MSHC_AUTH_GENERAL_USER: $ary = array(
				//MSHC_AUTH_GENERAL_USER => MSHC_AUTH_GENERAL_USER_NAME,
				MSHC_AUTH_FIRM_ADMIN => MSHC_AUTH_FIRM_ADMIN_NAME,
				MSHC_AUTH_ATTORNEY => MSHC_AUTH_ATTORNEY_NAME,
				MSHC_AUTH_CASE_MANAGER => MSHC_AUTH_CASE_MANAGER_NAME
			); break;
			case MSHC_AUTH_FIRM_ADMIN: $ary = array(
				MSHC_AUTH_FIRM_ADMIN => MSHC_AUTH_FIRM_ADMIN_NAME,
				MSHC_AUTH_ATTORNEY => MSHC_AUTH_ATTORNEY_NAME,
				MSHC_AUTH_CASE_MANAGER => MSHC_AUTH_CASE_MANAGER_NAME
			); break;
		}
		return $ary;
	}
}

if (!function_exists('get_user_role_name'))
{
    function get_user_role_name($user_role)
    {
        $roles = array(
            MSHC_AUTH_SYSTEM_ADMIN => MSHC_AUTH_SYSTEM_ADMIN_NAME,
            MSHC_AUTH_GENERAL_USER => MSHC_AUTH_GENERAL_USER_NAME,
            MSHC_AUTH_FIRM_ADMIN => MSHC_AUTH_FIRM_ADMIN_NAME,
            MSHC_AUTH_ATTORNEY => MSHC_AUTH_ATTORNEY_NAME,
            MSHC_AUTH_CASE_MANAGER => MSHC_AUTH_CASE_MANAGER_NAME,
            MSHC_AUTH_BILLER => MSHC_AUTH_BILLER_NAME
        );

        return element($user_role, $roles);
    }
}

if (!function_exists('get_user_permissions_array')) 
{
	function get_user_permissions_array()
	{
		return array();

		/*$ary = array(
			MSHC_AUTH_GENERAL_USER => array(
				'maintain_clients_allowed' => 'Client Maintenance',
				'maintain_firms_allowed' => 'Firm Maintenance',
				'maintain_attorneys_allowed' => 'Attorney Maintenance',
				'maintain_marketers_allowed' => 'Marketer Maintenance',
				'view_portal_activity_logs_allowed' => 'View Activity Log'
			),
			MSHC_AUTH_FIRM_ADMIN => array(
				'view_cases_for_firm_allowed' => 'View Cases for Firm'
			),
			MSHC_AUTH_ATTORNEY => array(
				'view_cases_for_firm_allowed' => 'View Cases for Firm'
			)
		);			
		return $ary;*/
	}
}

if (!function_exists('array_unshift_assoc')) {
	function array_unshift_assoc(&$arr, $key, $val) 
	{ 
    	$arr = array_reverse($arr, true); 
	    $arr[$key] = $val; 
    	return array_reverse($arr, true); 
	} 
}

if (!function_exists('array_multi_search')) {
	function array_multi_search($array, $key, $value) 
	{
		foreach ($array as $k => $val) 
		{
			if ($val[$key] == $value) 
			{
				return $k;
			}
		}
		return NULL;
	}
}

if ( ! function_exists('get_random_password'))
{
    /**
     * Generate a random password. 
     * 
     * get_random_password() will return a random password with length 6-8 of lowercase letters only.
     *
     * @access    public
     * @param  integer  $chars_min the minimum length of password (optional, default 6)
     * @param  integer  $chars_max the maximum length of password (optional, default 8)
     * @param  bool  $use_upper_case boolean use upper case for letters, means stronger password (optional, default false)
     * @param  bool  $include_numbers boolean include numbers, means stronger password (optional, default false)
     * @param  bool  $include_special_chars include special characters, means stronger password (optional, default false)
     *
     * @return    string containing a random password 
     */    
    function get_random_password($chars_min = 7, $chars_max = 10, $use_upper_case = TRUE, $include_numbers = TRUE, $include_special_chars = FALSE)
    {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if ($include_numbers)  {
            $selection .= "1234567890";
        }
        if ($include_special_chars) {
            $selection .= "!@c2ac6c09d91a8a2b2be78456fda8eb5f37ffba85quot;#$%&[]{}?|";
        }
                                
        $password = "";
        for($i = 0; $i < $length; $i++) 
		{
			if ($use_upper_case) {
				if (rand(0,1)) {
					$current_letter = strtoupper($selection[(rand() % strlen($selection))]);
				} else {
					$current_letter = $selection[(rand() % strlen($selection))];
				}
			} else {
				$current_letter = $selection[(rand() % strlen($selection))];            
			}

            $password .=  $current_letter;
        }                
        
        return $password;
    }
}

/*
* Generate UUID();
*/
if ( ! function_exists('get_uuid'))
{
	function get_uuid() 
	{
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	
			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),
	
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,
	
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,
	
			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
}

if ( ! function_exists('get_query_like')) {
	function get_query_like($field, $value) 
	{
		return array($field.' LIKE ' => '\'%'.$value.'%\'');
	}
}

if ( ! function_exists('get_query_equal')) {
	function get_query_equal($field, $value) 
	{
		return array($field => '\''.$value.'\'');
	}
}

if ( ! function_exists('get_query_not_equal')) {
	function get_query_not_equal($field, $value) 
	{
		return array($field.' != ' => '\''.$value.'\'');
	}
}

if ( ! function_exists('get_query_greater_than')) {
	function get_query_greater_than($field, $value) 
	{
		return array($field.' > ' =>  '\''.$value.'\'');
	}
}
if ( ! function_exists('get_query_less_than')) {
    function get_query_less_than($field, $value)
    {
        return array($field.' < '  =>  '\''.$value.'\'');
    }
}


if ( ! function_exists('array_sort_by_column')) {
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}
	
		array_multisort($sort_col, $dir, $arr);
	}
}

if ( ! function_exists('release_date')) {
    function release_date()
    {
        defined('RELEASE_DATE') or define('RELEASE_DATE', '2017-01-24 9:31:00');

        return ENVIRONMENT != 'production' ? time() : strtotime(RELEASE_DATE);
    }
}

/*
 * Хелпер для вывода архива
 */
function print_dump($var, $type = 1)
{
	if ($type == 1) {
		echo '<pre>' . print_r($var, true) . '</pre>';
	} elseif ($type == 2) {
		var_dump($var);
	}
}

/* End of file mshc_helper.php */
/* Location: ./application/helpers/mshc_helper.php */