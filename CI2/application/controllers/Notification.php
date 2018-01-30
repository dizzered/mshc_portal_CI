<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends MSHC_Controller 
{
	
    public function __construct() 
    {
        parent::__construct();
		$this->load->model('notifications');
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
		$this->notifications();
	}
	
	private function notifications()
	{
		// Set page name
		$this->_set_page_title('Notifications');	
		
		// Add portal activity
		$activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
		$this->activity->add_activity_log('View User Notifications', 'portal', $activity_info);
		
		$new_notifications = $this->uri->segment(3);
		
		$data = array();
		if ($new_notifications == 'new')
			$data['new_notifications'] = 'true';
		
		$this->_add_view('notifications', 1, $data);
		$this->_render();
	}

}

/* End of file notification.php */
/* Location: ./application/controllers/notification.php */