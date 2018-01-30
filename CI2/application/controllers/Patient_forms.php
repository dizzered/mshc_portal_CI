<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_forms extends MSHC_Controller 
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
		$this->forms();
	}
	
	protected function forms()
	{
		// Set page name
		$this->_set_page_title('Download Forms');
		
		if  ($this->_is_logged_in()) {
			// Add portal activity
			$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
			$this->activity->add_activity_log('View Patient Forms', 'portal', $activity_info);
		}
				
		// Get list of patient forms
		$forms_query['where'] = array(
			'file_name != ' => '\'\''
		);
		$forms_query['order'][] = array(
			'weight' => 'ASC'
		);
		$data['forms_list'] = $this->forms->get_forms($forms_query, FALSE);
		
		$this->_add_view('download_forms', 1, $data);
		$this->_render();
	}
	
}

/* End of file patient_forms.php */
/* Location: ./application/controllers/patient_forms.php */