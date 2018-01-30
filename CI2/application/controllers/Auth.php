<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('application/libraries/phpass-0.3/PasswordHash.php');

class Auth extends MSHC_Controller 
{	
    public function __construct() 
    {
        parent::__construct();
		$this->load->library('form_validation');
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
		redirect(base_url());
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	public function login()
	{
		if ($this->_is_logged_in()) {
			// logged in
			redirect(base_url());	
		} else {
			$this->form_validation->set_rules('login', 'Login', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');
			
			$data['errors'] = array();

			// set captcha to FALSE. Only enable AFTER x logins fail
			$data['show_captcha'] = FALSE;
			
			if ($this->form_validation->run()) {
				if ($this->_login($this->form_validation->set_value('login'), $this->form_validation->set_value('password'), $this->form_validation->set_value('remember'))) {
					// success
					// Redirect to intended page, if given.
					if ($this->input->post('redirect_url') !== FALSE) {
						redirect(urldecode(base_url().$this->input->post('redirect_url')));
					} else {
						redirect(base_url());
					}
				} else {
					// could not log in
					if ($this->is_max_login_attempts_exceeded($this->form_validation->set_value('login'))) {
						$this->users->set_user_locked_out($this->form_validation->set_value('login'));
					}					
					$errors = $this->_auth_error;
					if (isset($errors['locked_out'])) {
						// locked out user
						$this->session->set_userdata('general_flash_message','{"type":"error","text":"'.$errors['locked_out'].'"}');
					} else {
						// fail
						foreach ($errors as $k => $v) $data['errors'][$k] = $v;
					}
				}
			}
			
			// Check for redirect variable in either get or post.
			if ($this->input->get('r') !== FALSE) {
				$data['redirect_url'] = $this->input->get('r');
			} else if ($this->input->post('redirect_url') !== FALSE) {
				$data['redirect_url'] = $this->input->post('redirect_url');
			}
			
			$this->_add_view('auth/login',1,$data);
			
			$this->_render();	
		}
	} // login

	/**
	 * Loggin out user
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->_logout();
		redirect(MSHC_AUTH_CONTROLLER_NAME.'/login');
	}
	
	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	public function is_max_login_attempts_exceeded($username)
	{
		$attempt_count = isset($this->_settings['failed_password_attempt_count']) && $this->_settings['failed_password_attempt_count']
			? $this->_settings['failed_password_attempt_count'] 
			: $this->config->item('login_max_attempts', 'auth');
			
		if ($this->config->item('login_count_attempts', 'auth')) 
		{
			return $this->users->get_login_attempts_num($username) >= $attempt_count;
		}
		return FALSE;
	}
	
	/**
	 * forgot password for user
	 *
	 * @return void 
	 */
	public function forgot_password() {
		
		$data = array();
		$this->_set_page_title('Forgot password');	
		$data['users'] = NULL;
		
		if ($this->_is_logged_in()) {
			// logged in
			redirect(base_url());	
		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
			
			$email = '';
			if ($this->form_validation->run()) {	
				$email = $this->form_validation->set_value('email');
			}
			
			if ($email) {
				$params = array(
					'where' => array(
						$this->users_table_name.'.email' => $email
					)
				);
				
				$user_id = $this->input->post('user_id', TRUE);
				if ($user_id) {
					$params['where'][$this->users_table_name.'.id'] = $user_id;
				}
				$users = $this->users->get_users($params);

				if (count($users) == 1) {
					$data_user = array();
					$new_password = get_random_password();
					$data_user['password'] = $this->users->_secure_password($new_password);
					$data_user['modified'] = date('Y-m-d H:i:s');
					$data_user['modified_by'] = $users[0]['id'];
					$this->users->update_user($users[0]['id'], $data_user);
					$this->load->library('mshc_general');
					$mail_params['message'] = $this->load->view(
						'email/forgot_password-html', 
						array(
							'username' => $users[0]['username'],
							'password' => $new_password
						), 
						TRUE
					);
					$mail_params['alt_message'] = $this->load->view(
						'email/forgot_password-txt', 
						array(
							'username' => $users[0]['username'],
							'password' => $new_password
						), 
						TRUE
					);
					$mail_params['subject'] = 'MSHC Portal: User Password Restoration';
					$send = $this->mshc_general->send_new_password($email, $mail_params);
					
					$data['message'] = 'New password was generated and sent to the specified e-mail address.';
				} elseif (count($users) > 1) {
					$data['users'] = $users;
				} else {
					$data['errors'] = "We couldn't find the email in our database.";
				}
			}
		}
		
		$this->_add_view('auth/forgot_password', 1, $data);
		$this->_render();	
	}
	
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */