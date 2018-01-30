<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MSHC_Controller 
{
    public function __construct() 
    {
        parent::__construct();
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
		$this->discharge_clients();
	}
	
	public function mileage()
	{		
		// Set page name
		$this->_set_page_title('Mileage Report');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Mileage Report', 'both', $activity_info);
		
		$this->_add_view('reports/mileage',1, array());
		$this->_render();
	}
	
	
	public function discharge_clients()
	{		
		// Set page name
		$this->_set_page_title('Discharge Report & Client List');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View Discharge Report & Client List', 'both', $activity_info);
		
		//$params = array(
//			'fields' => array(
//				'la.*' => ''
//			),
//			'from' => array(
//				$this->legal_attorneys_table_name => 'la'
//			),
//			'join' => array(
//				array(
//					'table' => $this->legal_attorneys_users_table_name.' AS lau',
//					'condition' => 'la.id = lau.legal_atty_id'
//				)
//			),
//			'group' => array(
//				'la.id'
//			)
//		);
		
//		$data['attorneys_list'] = $this->firms->get_attorneys($params);

		/*$this->load->library('mshc_connector');
		$this->load->library('mshc_general');
		$conds = array();*/
		
		/*if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN)
		{
			$data['attorneys_list'] = $this->mshc_general->getUserAttorneys();
			
//			$dbs = $this->mshc_connector->getDBArray();
//			if (count($attorneys_list) > 0) 
//			{
//				$conds['attorney_id'] = array(
//					'op' => 'or',
//					'value' => array()
//				);
//				foreach ($attorneys_list as $atty) 
//				{
//					$conds['attorney_id']['value'][] = array(
//						'attorney_id' => $atty['external_id'],
//						'database' => $dbs[$atty['ext_db_id']]
//					);
//				}
//			}
		} else {
//			print_r($conds);
			$data['attorneys_list'] = $this->mshc_general->getUserAttorneys();
//			$data['attorneys_list'] = $this->mshc_connector->getAttorneys(array(1,2,3,4,5), 'all', array('conds' => $conds, 'debugReturn' => 'sample'));
		}*/
		
		$attys_params = array(
			'fields' => array(
				'la.*' => ''
			),
			'from' => array(
				$this->legal_attorneys_users_table_name => 'lau'
			),
			'join' => array(
				array(
					'table' => $this->ext_dbs_legal_attys_table_name.' AS edla',
					'condition' => 'edla.legal_atty_id = lau.legal_atty_id'
				),
				array(
					'table' => $this->legal_attorneys_table_name.' AS la',
					'condition' => 'la.id = lau.legal_atty_id'
				)
			),
			'where' => array(
				'lau.user_id' => $this->_user['user_id']
			),
			'order' => array(
				array(
					'la.last_name' => 'ASC'
				)
			),
			'group' => array(
				'la.id'
			)
		);
		
		$data['attorneys_list'] = $this->firms->get_attorneys($attys_params);
		
		$this->_add_view('reports/discharge_clients',1, $data);
		$this->_render();
	}

}

/* End of file reports.php */
/* Location: ./application/controllers/reports.php */