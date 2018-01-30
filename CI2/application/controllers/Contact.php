<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MSHC_Controller 
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
		$this->contact();
	}
	
	protected function contact()
	{
		// Set page name
		$this->_set_page_title('Contact Us');
		
		//$this->_add_custom_script('/js/jquery/jquery.filestyle.js');
		
		/*if  ($this->_is_logged_in()) {
			// Add portal activity
			$activity_info = $this->session->userdata('ip_address').'; '.$this->session->userdata('user_agent').';';
			$this->activity->add_activity_log('View Contact Form', 'portal', $activity_info);
		}*/
				
		// Get list of marketers
		$marketer_query['fields'] = array(
			'id' => '',
			'IF(last_name != \'\',  IF(first_name != \'\', CONCAT(last_name, \' \', first_name), CONCAT(\'marketer_\', id) ) , IF(first_name != \'\', first_name, CONCAT(\'marketer_\', id) ) )' => 'name',
		);
		$marketer_query['order'][] = array(
			'last_name' => 'ASC',
			'first_name' => 'ASC'
		);
		$data['marketers_list'] = $this->marketers->get_marketers($marketer_query, FALSE);
		
		$this->load->library('user_agent');
		if ($this->agent->is_browser('Safari')) $this->_add_custom_style('/css/safari.css');

        $data['isCasesSearch'] = true;

		$this->_add_view('contact_us', 1, $data);
		$this->_render();
	}
	
	public function send() 
	{
	    /** @var array $data */
		$data = $this->input->post(NULL, TRUE);
		$data['created'] = date('Y-m-d H:i:s');

        unset($data['isAjax']);
        $isAjax = $this->input->post('isAjax', TRUE);

        $params = array(
            'case_contact_account' => $data['case_contact_account'],
            'case_contact_class' => $data['case_contact_class'],
            'case_contact_doa' => $data['case_contact_doa'],
        );
        unset($data['case_contact_account']);
        unset($data['case_contact_class']);
        unset($data['case_contact_doa']);
        unset($data['contact_client_cases_name']);
        unset($data['contact_client_cases_account']);
        unset($data['contact_cases_list']);
        $data['params'] = json_encode($params);

		$config['upload_path'] = MSHC_UPLOAD_FILE_PATH;
		$this->load->library('upload', $config);
		
		$contact_id = $this->contacts->add_new_contact($data);
		
		$email_attachs = array();
		for ($i = 1; $i <= count($_FILES); $i++)
		{
			$file_src = $contact_id.'_'.$_FILES["fileupload".$i]["name"];
			if ( ! move_uploaded_file($_FILES["fileupload".$i]["tmp_name"], MSHC_UPLOAD_FILE_PATH.'/'.$file_src)) {
				$data['error'] = "ERROR CANNOT UPLOAD FILE ".$_FILES["fileupload".$i]["name"];
			} else {
				$email_attachs[] = MSHC_UPLOAD_FILE_PATH.'/'.$file_src;
				$this->contacts->add_new_attach(
					array(
						'contact_id' => $contact_id, 
						'name' => $file_src, 
						'created' => date('Y-m-d H:i:s')
					)
				);
			}
		}

		$data['contact_id'] = $contact_id;
		
		switch ($data['inquiry_type_id']) {
			case 'billing_information': $data['inquiry_type'] = 'Document & Billing Assistance'; break;
			case 'marketers': $data['inquiry_type'] = 'Marketing Distribution List'; break;
			case 'representation_status': $data['inquiry_type'] = 'Representation Status'; break;
			case 'scheduling_question': $data['inquiry_type'] = 'Scheduling Question'; break;
			case 'settlement_request': $data['inquiry_type'] = 'Settlement Request'; break;
			case 'web_portal_support': $data['inquiry_type'] = 'Technical Support'; break;
			case 'feature_request': $data['inquiry_type'] = 'Feature Request'; break;
		}
		
		if ($data['cc_to'] != '') {
			$emailcc = explode(',', $data['cc_to']);
		} else {
			$emailcc = array();
		}
		
		$incl = get_array_value('including_me',$data);
		if (!is_null($incl) && $incl != '') {
			$emailcc[] = $data['email'];
		}

        $email_to = MSHC_ADMIN_EMAIL;

		if ($data['inquiry_type_id'] == 'marketers') {
			if ($data['marketer_id'] == 0) {
				$email_marketing_distribution_list = element('email_marketing_distribution_list', $this->_settings);
				$email_to = $email_marketing_distribution_list 
					? $email_marketing_distribution_list 
					: $this->_settings['email_administrator'];
			} else {
				$marketer = $this->marketers->get_marketer_by_id($data['marketer_id']);
				
				if (isset($marketer['email']) && $marketer['email'] != '') {
					$email_to = $marketer['email'];
					$data['marketer_info'] = $marketer;
				}
			}
		} else if ($data['inquiry_type_id'] == 'scheduling_question') {
			$email_scheduling = element('email_scheduling', $this->_settings);
			$email_to = $email_scheduling ? $email_scheduling : $this->_settings['email_administrator'];
		} else if ($data['inquiry_type_id'] == 'settlement_request') {
			$email_settlements = element('email_settlements', $this->_settings);
			$email_to = $email_settlements ? $email_settlements : $this->_settings['email_administrator'];
		} else if ($data['inquiry_type_id'] == 'web_portal_support') {
			$email_it_contact = element('email_it_contact', $this->_settings);
			$email_to = $email_it_contact ? $email_it_contact : $this->_settings['email_administrator'];
		} else if ($data['inquiry_type_id'] == 'representation_status') {
			$email_scheduling = element('email_scheduling', $this->_settings);
			$email_to = $email_scheduling ? $email_scheduling : $this->_settings['email_administrator'];
		} else {
			$email_to = $this->_settings['email_administrator'];
		}

		$data = $data + $params;

		$message = $this->load->view('email/contact_inquiry-html', $data, TRUE);
		$alt_message = $this->load->view('email/contact_inquiry-txt', $data, TRUE);
		
		$this->load->library('mshc_general');
		$this->mshc_general->send_contact($email_to, $message, $alt_message, $emailcc, $email_attachs, $data);
		
		// Add portal activity
		$activity_info = 'Inquiry Type: '.$data['inquiry_type'].';';
		$this->activity->add_activity_log('Send New Inquiry', 'portal', $activity_info);

        if ($isAjax) {
            echo json_encode(array(
                'status' => 'ok'
            ));
            exit;
        } else {
            $this->_add_view('contact_result', 1, $data);
            $this->_render();
        }
	}

}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */