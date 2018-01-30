<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * Base class for all MSHC models
 * @author Zercel
 *
 * Modification History
 * --------------------
 *
 * @property CI_Config $config
 * @property CI_DB_active_record|CI_DB_mysqli_driver $db
 * @property CI_Loader $load
 * @property CI_Session $session
 *
 * @property MSHC_Connector $mshc_connector
 * @property MSHC_General $mshc_general
 *
 * @property array $_user
 */

class MSHC_Model extends CI_Model {
		
    public function __construct()
    {
        $this->load->database();
        parent::__construct();
    }
	
	public function _get_query($query_params, $escape = TRUE)
	{
		// Set SELECT
		$fields = get_array_value('fields',$query_params);
		if ($fields && count($fields)) {
			foreach($query_params['fields'] as $field => $alias)
			{
				if (strlen($alias)) $this->db->select($field.' AS '.$alias, $escape);
				else $this->db->select($field, $escape);
			}
		} else {
			$this->db->select('*', $escape);
		}

		// Set FROM
		$from = get_array_value('from',$query_params);
		if ($from) {
			foreach($from as $table => $alias)
			{
				if (strlen($alias)) $this->db->from($table.' AS '.$alias);
				else $this->db->from($table);
			}
		}
		
		// Set JOIN
		$joins = get_array_value('join',$query_params);
		if ($joins) {
			foreach($joins as $join)
			{
				if (!array_key_exists('type', $join)) $join['type'] = '';
				$this->db->join($join['table'], $join['condition'], $join['type']);
			}
		}
		
		// Set WHERE
		$where = get_array_value('where',$query_params);
		if ($where) {
			foreach($where as $field => $value)
			{
				if ( ! empty($value)) 
					$this->db->where($field, $value, $escape);
				else 
					$this->db->where($field, NULL, $escape);
			}
		}
		
		// Set WHERE OR
		$where_or = get_array_value('where_or',$query_params);
		if ($where_or) {
			foreach($where_or as $field => $value)
			{
				if ( ! empty($value)) 
					$this->db->or_where($field, $value, $escape);
				else 
					$this->db->or_where($field, NULL, $escape);
			}
		}
		
		// Set WHERE IN
		$where_in = get_array_value('where_in',$query_params);
		if ($where_in) {
			foreach($where_in as $field => $value)
			{
				if (is_array($value)) 
					$this->db->where_in($field, $value);
				else 
					$this->db->or_where($field, explode(',',$value), $escape);
			}
		}

		// Set GROUP BY
		$group = get_array_value('group',$query_params);
		if ($group) {
			foreach($group as $field)
			{
				$this->db->group_by($field);
			}
		}
		
		// Set ORDER BY
		$order = get_array_value('order',$query_params);
		if ($order) {
			if (count($order) > 0) {
				for ($i = 0; $i < count($order); ++$i)
				{
					foreach($order[$i] as $field => $type)
					{
						if (strlen($type)) $this->db->order_by($field, $type);
						else $this->db->order_by($field, 'ASC');
					}
				}
			}
		}
		
		// Set LIMIT
		$limit = get_array_value('limit',$query_params);
		$offset = get_array_value('offset',$query_params);
		if ($limit) {
			if ($offset) $this->db->limit($limit, $offset);
			else $this->db->limit($limit);
		}
		
		return $this->db->get();
	}

    /**
     * @param $table
     * @return string|mixed
     */
	public static function tableName($table)
    {
        /** @var MSHC_Controller $ci */
        $ci =& get_instance();

        return $ci->{$table};
    }
}

/* End of file MSHC_Model.php */
/* Location: ./application/core/MSHC_Model.php */