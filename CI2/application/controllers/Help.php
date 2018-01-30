<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends MSHC_Controller 
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
		$this->faq();
	}
		
	public function faq()
	{
		// Set page name
		$this->_set_page_title('Frequently Asked Questions');
		
		if  ($this->_is_logged_in()) {
			// Add portal activity
			$activity_info = $this->session->userdata('ip_address').'; '.$this->session->userdata('user_agent').';';
            $this->activity->add_activity_log('Frequently Asked Questions', 'portal', $activity_info);
		}
		
		$this->_add_view('help_faq', 1, $data = array());
		$this->_render();
	}
	
}

/* End of file help.php */
/* Location: ./application/controllers/help.php */