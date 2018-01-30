<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
*/
$config['phpass_hash_portable'] = FALSE;
$config['phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Registration settings
|
| 'allow_registration' = Registration is enabled or not
| 'captcha_registration' = Registration uses CAPTCHA
| 'email_activation' = Requires user to activate their account using email after registration.
| 'email_activation_expire' = Time before users who don't activate their account getting deleted from database. Default is 48 hours (60*60*24*2).
| 'email_account_details' = Email with account details is sent after registration (only when 'email_activation' is FALSE).
| 'use_username' = Username is required or not.
|
| 'username_min_length' = Min length of user's username.
| 'username_max_length' = Max length of user's username.
| 'password_min_length' = Min length of user's password.
| 'password_max_length' = Max length of user's password.
| 'password_regexp' 	= Regular expression specifying password strength.
|--------------------------------------------------------------------------
*/
$config['allow_registration'] = TRUE;
$config['captcha_registration'] = FALSE;
$config['email_activation'] = TRUE;
$config['email_activation_expire'] = 60*60*24*2;
$config['email_account_details'] = TRUE;
$config['use_username'] = TRUE;

$config['username_min_length'] = 4;
$config['username_max_length'] = 20;
$config['password_min_length'] = 7;
$config['password_max_length'] = 20;
//$config['password_regexp'] = '/^.*(?=.*[\d])(?=.*[a-z])(?=.*[A-Z]).*$/'; // error message is in the lang file
$config['password_regexp'] = '/^[a-zA-Z0-9]*[\d_\W+][a-zA-Z0-9]*[\d_\W+][a-zA-Z0-9]*$/';

/*
|--------------------------------------------------------------------------
| Login settings
|
| 'login_by_username' = Username can be used to login.
| 'login_by_email' = Email can be used to login.
| You have to set at least one of 2 settings above to TRUE.
| 'login_by_username' makes sense only when 'use_username' is TRUE.
|
| 'login_record_ip' = Save in database user IP address on user login.
| 'login_record_time' = Save in database current time on user login.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
|--------------------------------------------------------------------------
*/
$config['login_by_username'] = TRUE;
$config['login_by_email'] = TRUE;
$config['login_record_ip'] = FALSE;
$config['login_record_time'] = TRUE;
$config['login_count_attempts'] = TRUE;
$config['login_max_attempts'] = 5;
$config['login_attempt_expire'] = 60*60*24;

/*
|--------------------------------------------------------------------------
| Auto login settings
|
| 'autologin_cookie_name' = Auto login cookie name.
| 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60*60*24*31*2).
|--------------------------------------------------------------------------
*/
$config['autologin_cookie_name'] = 'autologin';
$config['autologin_cookie_life'] = 60*60*24*31*2;

/*
|--------------------------------------------------------------------------
| Forgot password settings
|
| 'forgot_password_expire' = Time before forgot password key become invalid. Default is 15 minutes (60*15).
|--------------------------------------------------------------------------
*/
$config['forgot_password_expire'] = 60*15;
$config['new_password_expire'] = 60*60*24*2; // two days for new user password activation

/*
|--------------------------------------------------------------------------
| Captcha
|
| You can set captcha that created by Auth library in here.
| 'captcha_path' = Directory where the catpcha will be created.
| 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' = Show grid in created captcha.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' = Captcha case sensitive or not.
|--------------------------------------------------------------------------
*/
$config['captcha_path'] = 'captcha/';
$config['captcha_fonts_path'] = 'captcha/fonts/5.ttf';
$config['captcha_width'] = 200;
$config['captcha_height'] = 50;
$config['captcha_font_size'] = 14;
$config['captcha_grid'] = FALSE;
$config['captcha_expire'] = 180;
$config['captcha_case_sensitive'] = TRUE;

/*
|--------------------------------------------------------------------------
| reCAPTCHA
|
| 'use_recaptcha' = Use reCAPTCHA instead of common captcha
| You can get reCAPTCHA keys by registering at http://recaptcha.net
|--------------------------------------------------------------------------
*/
$config['use_recaptcha'] = TRUE;
$config['recaptcha_public_key'] = '6LfdG8ESAAAAADSLnPWuDa3VM4L6HhncrOSarZit';
$config['recaptcha_private_key'] = '6LfdG8ESAAAAALADhwfrUcSsPBCSjGi3re1EnGOO';

/*
|--------------------------------------------------------------------------
| Database settings
|
| 'db_table_prefix' = Table prefix that will be prepended to every table name used by the library
| (except 'ci_sessions' table).
|--------------------------------------------------------------------------
*/
$config['db_table_prefix'] = '';

/*
|--------------------------------------------------------------------------
| Company table settings
|
| 'company_name_max_length' = Max length of company's name.
|--------------------------------------------------------------------------
*/
$config['first_name_max_length'] = 45;
$config['last_name_max_length'] = 45;
$config['nickname_max_length'] = 45;
$config['phone_max_length'] = 45;
$config['mobile_max_length'] = 45;
$config['email_max_length'] = 100;

$config['company_name_max_length'] = 45;
$config['company_address1_max_length'] = 45;
$config['company_address2_max_length'] = 45;
$config['company_city_max_length'] = 45;
$config['company_state_max_length'] = 2;
$config['company_postal_code_max_length'] = 45;
$config['company_phone_max_length'] = 45;
$config['company_fax_max_length'] = 30;

$config['company_from_address_max_length'] = 255;
$config['company_from_name_max_length'] = 255;
$config['company_privacy_url_max_length'] = 255;

/*
|--------------------------------------------------------------------------
| Various membership settings
|
| 'membership_grace_period' = Grace period in DAYS from membership expiration date until account is locked out.
|--------------------------------------------------------------------------
*/
$config['membership_grace_period'] = 14;
$config['orders_page_limit'] = 25;


/*
|--------------------------------------------------------------------------
| Various print settings
|
| 'print_hot_folder' = print hot folder path.
|--------------------------------------------------------------------------
*/
$config['print_hot_folder'] = 'erebos/wip1/_TWOBOLT/Platform/';
$config['print_pdf_engine'] = 'mpdf';  // mpdf or dompdf 

/* End of file auth.php */
/* Location: ./application/config/auth.php */