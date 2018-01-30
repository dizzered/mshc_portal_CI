<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MSHC_Controller 
{
	public $yesterday_begin;
	public $yesterday_end;
	
    public function __construct() 
    {
        parent::__construct();
		$this->load->library('mshc_general');
		$this->load->library('mshc_connector');
		
		$yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")); // yesterday
		$this->yesterday_begin = date('Y-m-d', $yesterday).' 00:00:00';
		$this->yesterday_end = date('Y-m-d', $yesterday).' 23:59:59';
		
		// FOR TEST
		//$this->yesterday_begin = '2015-6-18 00:00:00';
		//$this->yesterday_end = '2015-6-19 23:59:59';
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
	}
	
	/*
	* Create notifications
	*/
	public function create_notifications()
	{
		//$this->_ensure_post_method();
		
		ini_set('memory_limit','1024M');
		error_reporting(E_ALL);
		set_time_limit (0);
		
		//$start = microtime(TRUE);
		
		//delete notifications before month ago
		$this->notifications->delete_notifications_before_day(mktime(0, 0, 0, date("m"), date("d")-30, date("Y")));
		
		$yesterday_notifs = $this->notifications->get_notifications(
			array(
				'where' => array(
					' DATE_FORMAT(created, "%Y-%m-%d")  = ' => ' DATE_FORMAT(now(), "%Y-%m-%d") '
				)
			), 
			FALSE
		);
		
		if (is_array($yesterday_notifs) && count($yesterday_notifs)) return;
			
		$this->create_missed_appts_notifications();
		
		$this->create_documents_notifications();
		
		$this->create_discharged_notifications();

        /*$end = microtime(TRUE);
        echo '<pre>';
        echo 'Running time: '.gmdate('H:i:s.u',($end - $start)).'<br /><br />';
        echo '</pre>';*/
	}
	
	private function create_missed_appts_notifications()
	{
		return $this->mshc_general->create_missed_appts_notifications($this->yesterday_begin, $this->yesterday_end);
	}
	
	private function create_documents_notifications()
	{
		$this->mshc_general->create_documents_notifications($this->yesterday_begin, $this->yesterday_end);
	}
	
	private function create_discharged_notifications()
	{
		$this->mshc_general->create_discharged_notifications($this->yesterday_begin, $this->yesterday_end);
	}
	
	public function create_high_charges()
	{
		//$this->_ensure_post_method();
		
		// High Charges
		ini_set('memory_limit','1024M');
		//error_reporting(E_ALL);
		set_time_limit (0);
		
		//delete notifications before month ago
		$this->notifications->delete_notifications_before_day(mktime(0, 0, 0, date("m")  , date("d")-30, date("Y")));

		$this->load->library('mshc_connector');
		
		$yesterday_notifs = $this->notifications->get_notifications(
			array(
				'where' => array(
					' DATE_FORMAT(created, "%Y-%m-%d")  = ' => ' DATE_FORMAT(now(), "%Y-%m-%d") '
				)
			), 
			FALSE
		);
		
		if (is_array($yesterday_notifs) && count($yesterday_notifs)) return;
				
		$this->mshc_general->create_high_charges($this->yesterday_begin, $this->yesterday_end);
	}
	
	public function delete_converted($path = '')
	{
		$this->_ensure_post_method();
		
		$this->load->helper('file');
		
		if (empty($path)) $path = FCPATH.MSHC_CONVERT_FILE_PATH;
		
		$dir = get_dir_file_info($path);

		if (is_array($dir) && count($dir))
		{
			delete_files($path);
		}
	}

}

/* End of file cron.php */
/* Location: ./application/controllers/cron.php */