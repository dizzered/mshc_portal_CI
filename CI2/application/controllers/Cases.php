<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Cases
 *
 */
class Cases extends MSHC_Controller
{
    public $dateType = '';
    public $dateFrom = false;
    public $dateTo = false;

    public function __construct()
    {
        parent::__construct();
    }

    /*
    * Index Page for this controller.
    */
    public function index()
    {
        $this->search();
    }

    public function search(
        $param_advanced = NULL,
        $type = NULL,
        $account = NULL,
        $db_name = NULL,
        $practice = NULL,
        $patient = NULL,
        $case_no = NULL,
        $name = NULL,
        $accident_date = NULL,
        $birth_date = NULL
    )
    {
        $this->load->driver('cache');
        $this->load->library('mshc_connector');

        if (!$this->cache->file->get('maxServiceDate')) {
            $EOD = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            $this->cache->file->save('maxServiceDate', $this->mshc_connector->getMaxServiceDate(array(1),'first'), $EOD - time());
        }

        // Set page name
        $this->_set_page_title('Search Cases');

        /*// Add portal activity
        $activity_info = 'IP: '.$this->session->userdata('ip_address').'; Browser: '.$this->agent->browser().' '.$this->agent->version().';';
        $this->activity->add_activity_log('View Search Cases', 'both', $activity_info);*/

        if ($param_advanced || $this->_user['role_id'] == MSHC_AUTH_BILLER) {
            $data['search_advanced'] = TRUE;
        } else {
            $data['search_advanced'] = FALSE;
        }

        $data['client_cases_atty'] = $this->input->post('client_cases_atty', true);
        $data['client_cases_name'] = $this->input->post('client_cases_name', true);
        $data['client_cases_ssn'] = $this->input->post('client_cases_ssn', true);
        $data['client_cases_account'] = $this->input->post('client_cases_account', true);
        $data['client_cases_my_cases'] = $this->input->post('client_cases_my_cases', true);
        $data['client_cases_company_id'] = $this->input->post('client_cases_company_id', true);

        if (
            $data['client_cases_name'] != '' ||
            $data['client_cases_ssn'] != '' ||
            $data['client_cases_account'] != '' ||
            $data['client_cases_my_cases'] != '' ||
            $data['client_cases_atty'] != ''
        ) {
            $data['case_search_now'] = "true";
        }

        // list firm
        if ($this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            $firm_query['fields'] = array(
                'id' => '',
                'name' => ''
            );
            $firm_query['order'][] = array(
                'name' => 'ASC'
            );
            $data['firm_list'] = $this->firms->get_firms($firm_query);
        } else {
            $data['firm_list'] = $this->firms->get_firms_by_user_id($this->_user['user_id']);
        }

        if ($this->_user['role_id'] == MSHC_AUTH_SYSTEM_ADMIN) {
            $atty_query['order'][] = array(
                'last_name' => 'ASC'
            );
            $data['attorneys_list'] = $this->firms->get_attorneys($atty_query);
        } else if ($data['client_cases_atty']) {
            $client_cases_atty = $this->firms->get_attorneys(array('where' => array('id' => $data['client_cases_atty'])));
            $data['client_cases_firm_id'] = $client_cases_atty[0]['legal_firm_id'];

            $params = array(
                'fields' => array(
                    'la.*' => ''
                ),
                'from' => array(
                    $this->legal_attorneys_table_name => 'la'
                ),
                'where' => array(
                    'la.legal_firm_id' => $data['client_cases_firm_id']
                ),
                'order' => array(
                    array('la.last_name' => 'ASC')
                )
            );

            $data['attorneys_list'] = $this->firms->get_attorneys($params);
        } else {
            //$data['client_cases_firm_id'] = 0;
            $params = array(
                'fields' => array(
                    'la.*' => ''
                ),
                'from' => array(
                    $this->legal_attorneys_table_name => 'la'
                ),
                'join' => array(
                    array(
                        'table' => $this->legal_attorneys_users_table_name . ' AS lau',
                        'condition' => 'la.id = lau.legal_atty_id'
                    )
                ),
                'where' => array(
                    'lau.user_id' => $this->_user['user_id']
                ),
                'order' => array(
                    array('la.last_name' => 'ASC')
                )
            );

            $data['attorneys_list'] = $this->firms->get_attorneys($params);
            //echo '<pre>'.print_r($data['attorneys_list'], true).'</pre>';
        }

        $data['client_cases_firm_id'] = $this->_user['cases_search_attys_type'] == 'my' ? 0 : 1;

        $data['summary_conds'] = $data['appts_conds'] = NULL;

        if (!is_null($name) && $accident_date && $birth_date) {
            $data['client_cases_name'] = urldecode($name);
            $data['client_cases_doa'] = urldecode(date('m/d/Y', strtotime($accident_date)));
            $data['client_cases_dob'] = urldecode(date('m/d/Y', strtotime($birth_date)));
            $data['case_search_now'] = "true";
        } elseif (!is_null($type) && !is_null($account) && !is_null($db_name) && !is_null($practice) && !is_null($patient) && !is_null($case_no)) {
            $data[$type . '_conds'] = $this->mshc_connector->getCases(
                array(1, 2, 3, 4, 5),
                'first',
                array(
                    'fields' => array(
                        'first_name',
                        'last_name',
                        'middle_name',
                        'account',
                        'ssn',
                        'accident_date',
                        'db_name',
                        'attorney_name',
                        'attorney_id',
                        'status',
                        'case_category',
                        'patient',
                        'case_no',
                        'practice'
                    ),
                    'conds' => array(
                        'cases' => array(
                            'op' => 'include',
                            'value' => array(
                                array(
                                    'db_name' => $db_name,
                                    'account' => $account,
                                    'practice' => $practice,
                                    'case_no' => $case_no,
                                    'patient' => $patient
                                )
                            )
                        )
                    ),
                    'debugReturn' => 'sample_all'
                )
            );

            $conds = array();
            $conds['account'] = $account;
            $conds['db_name'] = $db_name;
            $conds['practice'] = $practice;
            $conds['case_no'] = $case_no;
            $conds['patient'] = $patient;
            $appts = $this->mshc_connector->getApptStatus(
                array(1, 2, 3, 4, 5),
                'all',
                array(
                    'fields' => array(
                        'first_name',
                        'last_name',
                        'middle_name',
                        'account',
                        'ssn',
                        'accident_date',
                        'db_name',
                        'attorney_name',
                        'attorney_id',
                        'status',
                        'case_category',
                        'patient',
                        'case_no',
                        'practice'
                    ),
                    'conds' => $conds,
                    'debugReturn' => 'sample'
                )
            );
            $data[$type . '_conds']['appt_status'] = number_format($appts[0]['appt_status'] * 100, 2) . '%';
        }

        // Search param 'Company'
        $data['company_list'] = array(
            'Multi',
            'MED',
            'BWR',
            'MRI',
            'NTI'
        );

        $data['maxServiceDate'] = $this->cache->file->get('maxServiceDate');

        $this->_add_view('cases_search', 1, $data);
        $this->_render();
    }

    /*
    * pdf files show
    */
    public function documents(
        $account = NULL,
        $practice = NULL,
        $patient = NULL,
        $case_no = NULL,
        $doc_id = NULL,
        $page_id = NULL,
        $db_name = 'amm_live'
    )
    {
        if (
            !is_null($account) &&
            !is_null($practice) &&
            !is_null($patient) &&
            !is_null($case_no) &&
            !is_null($doc_id)
        ) {
            $this->load->library('mshc_connector');
            $conds = array(
                'db_name' => $db_name,
                'account' => $account,
                'case_no' => $case_no,
                'practice' => $practice,
                'patient' => $patient,
                'doc_id' => $doc_id
            );

            if (is_numeric($page_id) && $page_id) {
                $conds['page_id'] = $page_id;
                $req = 'first';
            } else {
                $req = 'all';
            }

            $documents = $this->mshc_connector->getDocuments(
                array(1, 2, 3, 4, 5),
                $req,
                array(
                    'fields' => array(),
                    'conds' => $conds,
                    'debugReturn' => 'sample'
                )
            );

            //print_dump($conds);
            //print_dump($documents);
            //exit;

            if ($documents) {
                echo '<form id="document_open_form" name="document_open_form" method="post" action="' .
                    base_url() . MSHC_CASES_CONTROLLER_NAME . '/documents">';
                if ($req == 'first') {
                    echo '<input type="hidden" value="' . $documents['full_path'] . '" name="document_checkbox[]" />';
                    $document_name = $documents['document_name'];
                } else {
                    $document_name = $documents[0]['document_name'];
                    $_name = explode('page', $document_name);
                    $document_name = rtrim(rtrim($_name[0]), ',');
                    foreach ($documents as $doc) {
                        echo '<input type="hidden" value="' . $doc['full_path'] . '" name="document_checkbox[]" />';
                    }
                }
                echo '</form>
				Loading...
				' . img(
                        array(
                            'src' => '/images/ajax_loader.gif',
                            'width' => 24,
                            'style' => 'vertical-align:middle;'
                        )
                    ) . '
				<script src="/js/jquery/jquery-1.8.2.min.js"></script>
				<script>
				$(function() {
					$("#document_open_form").submit();
				});
				</script>';
                // Add portal activity
                $activity_info = 'Account: ' . $account . '; Document Name: ' . $document_name . ';';
                $this->activity->add_activity_log('View Case Documents', 'portal', $activity_info);
            } else {
                $this->session->set_userdata('general_flash_message', '{"type":"error","text":"Requested document was not found."}');
                redirect();
            }
        } else {
            $this->load->library('mshc_general');
            ini_set('allow_url_fopen', 1);
            require_once(APPPATH . 'libraries/PDFMerger/PDFMerger.php');

            /** @var array $_files */
            $_files = $this->input->post('document_checkbox', true);
            $account = $this->input->post('documents_account', true);
            $path_files = array();
            foreach ($_files as $files_str) {
                $arr = explode(',', $files_str);
                $path_files = array_merge($path_files, $arr);
            }
            $path_files = array_unique($path_files);

            $files = array();
            $activity_info = 'Account: ' . $account . '; Documents: ';
            foreach ($path_files as $filename) {
                if ($filename) {
                    $path = pathinfo($filename);
                    $extension = $path['extension'];
                    $activity_info .= $path['basename'] . '; ';
                    switch ($extension) {
                        case 'jpeg':
                        case 'jpg':
                        case 'tif':
                        case 'tiff':
                            $file = $this->mshc_general->create_pdf($filename, 'image');
                            if (FALSE !== $file) $files[] = $file;
                            break;
                        case 'doc':
                        case 'docx':
                            $files[] = $this->mshc_general->create_pdf($filename, 'doc');
                            break;
                        case 'pdf':
                            $files[] = $this->mshc_general->create_pdf($filename, 'pdf');
                            break;
                    }
                }
            }

            $pdf = new PDFMerger;
            foreach ($files as $filename) {
                $pdf->addPDF($filename, 'all');
            }

            // Add portal activity
            $this->activity->add_activity_log('View Case Documents', 'portal', $activity_info);

            $pdf->merge('browser');
        }
    }

    public function new_cases()
    {

        // Set page name
        $this->_set_page_title('New Cases');

        /*// Add portal activity
        $activity_info = $this->session->userdata('ip_address').'; '.$this->session->userdata('user_agent').';';
        $this->activity->add_activity_log('View New Cases', 'both', $activity_info);*/

        $data = array();
        $post_client_cases_name = $this->input->post('new_cases_name', true);
        $sess_client_cases_name = $this->session->userdata('client_cases_name');

        if ($post_client_cases_name != '') $data['client_cases_name'] = $post_client_cases_name;
        elseif ($sess_client_cases_name != '') $data['client_cases_name'] = $sess_client_cases_name;
        else $data['client_cases_name'] = '';

        $post_client_cases_ssn = $this->input->post('new_cases_ssn', true);
        $sess_client_cases_ssn = $this->session->userdata('client_cases_ssn');
        if ($post_client_cases_ssn != '') $data['client_cases_ssn'] = $post_client_cases_ssn;
        elseif ($sess_client_cases_ssn != '') $data['client_cases_ssn'] = $sess_client_cases_ssn;
        else $data['client_cases_ssn'] = '';

        $post_client_cases_phone = $this->input->post('new_cases_phone', true);
        $sess_client_cases_phone = $this->session->userdata('client_cases_phone');
        if ($post_client_cases_phone != '') $data['client_cases_phone'] = $post_client_cases_phone;
        elseif ($sess_client_cases_phone != '') $data['client_cases_phone'] = $sess_client_cases_phone;
        else $data['client_cases_phone'] = '';

        $post_client_cases_dob = $this->input->post('new_cases_dob', true);
        $sess_client_cases_dob = $this->session->userdata('client_cases_dob');
        if ($post_client_cases_dob != '') $data['client_cases_dob'] = $post_client_cases_dob;
        elseif ($sess_client_cases_dob != '') $data['client_cases_dob'] = $sess_client_cases_dob;
        else $data['client_cases_dob'] = '';

        if (
            $data['client_cases_name'] != ''
            || $data['client_cases_ssn'] != ''
            || $data['client_cases_phone'] != ''
            || $data['client_cases_dob'] != ''
        ) {
            $this->session->set_userdata('client_cases_name', $data['client_cases_name']);
            $this->session->set_userdata('client_cases_ssn', $data['client_cases_ssn']);
            $this->session->set_userdata('client_cases_phone', $data['client_cases_phone']);
            $this->session->set_userdata('client_cases_dob', $data['client_cases_dob']);
            $data['case_search_now'] = "true";
        }

        $this->_add_view('cases_new_search', 1, $data);
        $this->_render();
    }

    public function register()
    {

        $post = $this->input->post();
        $data = array();
        $values = array();
        $this->load->library('mshc_connector');

        if (is_array($post)) {
            $client_cases_name = $this->input->post('client_cases_name');
            $client_cases_ssn = $this->input->post('client_cases_ssn');
            $client_cases_phone = $this->input->post('client_cases_phone');
            $client_cases_dob = $this->input->post('client_cases_dob');
            if ($client_cases_name) $this->session->set_userdata('client_cases_name', $client_cases_name);
            //else $this->session->unset_userdata('client_cases_name');
            if ($client_cases_ssn) $this->session->set_userdata('client_cases_ssn', $client_cases_ssn);
            //else $this->session->unset_userdata('client_cases_ssn');
            if ($client_cases_phone) $this->session->set_userdata('client_cases_phone', $client_cases_phone);
            //else $this->session->unset_userdata('client_cases_phone');
            if ($client_cases_dob) $this->session->set_userdata('client_cases_dob', $client_cases_dob);
            //else $this->session->unset_userdata('client_cases_dob');

            if (
                array_key_exists('account', $post)
                || array_key_exists('client_cases_name', $post)
                || array_key_exists('client_cases_ssn', $post)
                || array_key_exists('client_cases_phone', $post)
                || array_key_exists('client_cases_dob', $post)
            ) {
                foreach ($post as $key => $val) {
                    $values[$key] = $val;
                }
                $data['data'] = $values;

                $data['patient_info'] = $this->mshc_connector->getStatementHeader(
                    array(1, 2, 3, 4, 5),
                    'first',
                    array(
                        'conds' => array(
                            'account' => $this->input->post('account', TRUE),
                            'db_name' => $this->input->post('db_name', TRUE),
                            'patient' => $this->input->post('patient', TRUE),
                            'case_no' => $this->input->post('case_no', TRUE),
                            'practice' => $this->input->post('practice', TRUE),
                        ),
                        'debugReturn' => 'sample',
                    )
                );

                $data['patient_insurance'] = $this->mshc_connector->getPatientIns(
                    array(1, 2, 3, 4, 5),
                    'first',
                    array(
                        'conds' => array(
                            'account' => $this->input->post('account', TRUE),
                            'db_name' => $this->input->post('db_name', TRUE),
                            'patient' => $this->input->post('patient', TRUE),
                            'case_no' => $this->input->post('case_no', TRUE),
                            'practice' => $this->input->post('practice', TRUE),
                            'plan_type' => 'p'
                        ),
                        'debugReturn' => 'sample',
                    )
                );
            } else {
                $email_patient_registration = element('email_patient_registration', $this->_settings);
                $params = array();
                $params['send_to'] = $email_patient_registration
                    ? $email_patient_registration
                    : $this->_settings['email_administrator'];
                $params['subject'] = 'MSHC Portal: New Appointment Request';
                $data['data'] = $post;
                $data['send_to'] = $params['send_to'];
                $params['message'] = $this->load->view('cases_new_email_html', $data, TRUE);
                $params['alt_message'] = $this->load->view('cases_new_email_txt', $data, TRUE);

                if ($this->_send_mail($params)) {
                    $this->session->set_userdata('general_flash_message', '{"type":"success","text":"New case registration request sent successfully.", "class": "wide"}');
                    // Add portal activity
                    $activity_info = 'Name: ' . element('name', $data) . '; DOA: ' . element('doa', $data) . ';';
                    $this->activity->add_activity_log('New Case Registration Request', 'portal', $activity_info);
                } else {
                    $this->session->set_userdata('general_flash_message', '{"type":"error","text":"Error sending email. Please check portal settings or contact with portal administrator.", "class": "wide"}');
                }
                redirect(base_url() . 'cases/new_cases');
                return;
            }
        }

        $sess_client_cases_name = $this->session->userdata('client_cases_name');
        $sess_client_cases_ssn = $this->session->userdata('client_cases_ssn');
        $sess_client_cases_phone = $this->session->userdata('client_cases_phone');
        $sess_client_cases_dob = $this->session->userdata('client_cases_dob');

        if ($sess_client_cases_name || $sess_client_cases_ssn || $sess_client_cases_phone || $sess_client_cases_dob) {
            $data['show_back'] = TRUE;
        } else {
            $data['show_back'] = FALSE;
        }

        $this->_add_custom_style('/js/jqwidgets-ver3.0.3/styles/jqx.base.css');
        $this->_add_custom_style('/js/jqwidgets-ver3.0.3/styles/jqx.classic.css');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxcore.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxbuttons.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxscrollbar.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxlistbox.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxdropdownlist.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxmaskedinput.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxinput.js');
        $this->_add_custom_script('/js/jqwidgets-ver3.0.3/jqwidgets/jqxnumberinput.js');
        $this->_add_custom_script('/js/jquery/jquery-caret/jquery.caret.js');
        $this->_add_custom_script('/js/register.js');

        // Set page name
        $this->_set_page_title('New Appointment Request');

        $this->load->model('firms');
        $params = array(
            'fields' => array(
                'la.*' => ''
            ),
            'group' => array('la.id'),
            'order' => array(
                array('la.last_name' => 'ASC', 'la.first_name' => 'ASC')
            )
        );

        $params['from'] = array(
            $this->legal_firms_users_table_name => 'lfu'
        );
        $params['where']['lfu.user_id'] = $this->_user['user_id'];
        $params['join'] = array(
            array(
                'table' => $this->legal_attorneys_table_name . ' AS la',
                'condition' => 'la.legal_firm_id = lfu.legal_firm_id'
            ),
            array(
                'table' => $this->ext_dbs_legal_attys_table_name . ' AS edla ',
                'condition' => ' la.id = edla.legal_atty_id AND edla.id IS NOT NULL '
            )
        );

        $data['attorneys_list'] = $this->firms->get_attorneys($params);

        $queryParams = array(
            'conds' => array(
                'is_active' => 1,
            ),
            'group' => array('display_name'),
            'debugReturn' => 'sample',
        );
        $data['locations_list'] = $this->mshc_connector->getLocationNames(array(1, 2, 3, 4, 5), 'all', $queryParams);
        //echo '<pre>'.print_r($data['locations_list'],true).'</pre>';
        $this->_add_view('cases_new_register', 1, $data);
        $this->_render();
    }

    public function assign()
    {
        $user_firms = array();
        if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
            $firms = $this->firms->get_firms_attorneys_by_user_id($this->_user['user_id'], TRUE);
            if (count($firms)) {
                foreach ($firms as $firm) {
                    if (!in_array($firm['legal_firm_id'], $user_firms)) {
                        $user_firms[] = $firm['legal_firm_id'];
                    }
                }
            }
        }

        $params = array(
            'fields' => array(
                'u.id' => 'user_id',
                'u.last_name' => 'user_last_name',
                'u.first_name' => 'user_first_name',
                'la.id' => 'atty_id',
                'la.last_name' => 'atty_last_name',
                'la.first_name' => 'atty_first_name',
                'lf.id' => 'firm_id',
                'lf.name' => 'firm_name'
            ),
            'from' => array(
                $this->users_table_name => 'u'
            ),
            'join' => array(
                array(
                    'table' => $this->legal_attorneys_users_table_name . ' AS lau',
                    'condition' => 'lau.user_id = u.id'
                ),
                array(
                    'table' => $this->ext_dbs_legal_attys_table_name . ' AS edla',
                    'condition' => 'edla.legal_atty_id = lau.legal_atty_id'
                ),
                array(
                    'table' => $this->legal_attorneys_table_name . ' AS la',
                    'condition' => 'la.id = lau.legal_atty_id'
                ),
                array(
                    'table' => $this->firms_table_name . ' AS lf',
                    'condition' => 'lf.id = la.legal_firm_id'
                ),
            ),
            'where' => array(
                'u.role_id' => MSHC_AUTH_CASE_MANAGER
            ),
            'group' => array(
                'lau.user_id',
                'lau.legal_atty_id'
            ),
            'order' => array(
                array(
                    'u.last_name' => 'ASC',
                    'lf.name' => 'ASC',
                    'la.last_name' => 'ASC'
                )
            )
        );

        if (!count($user_firms) && $this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN) {
            $case_managers = array();
        } else {
            if (count($user_firms)) {
                $params['where']['u.role_id'] = MSHC_AUTH_CASE_MANAGER;
                $where_firm_array = array();
                foreach ($user_firms as $firm) {
                    $where_firm_array[] = 'lf.id = \'' . $firm . '\'';
                }
                $where_firm = implode(' OR ', $where_firm_array);
                $params['where']['(' . $where_firm . ')'] = '';
            }

            $case_managers = $this->users->get_users($params, TRUE);
        }

        $data = array();
        $managers = array();

        foreach ($case_managers as $mngr) {
            if (!array_key_exists($mngr['user_id'], $managers)) {
                $managers[$mngr['user_id']] = array(
                    'user_id' => $mngr['user_id'],
                    'user_last_name' => $mngr['user_last_name'],
                    'user_first_name' => $mngr['user_first_name'],
                    'user_firms' => array(
                        $mngr['firm_id'] => array(
                            'firm_id' => $mngr['firm_id'],
                            'firm_name' => $mngr['firm_name'],
                            'attys' => array(
                                $mngr['atty_id'] => array(
                                    'atty_id' => $mngr['atty_id'],
                                    'atty_last_name' => $mngr['atty_last_name'],
                                    'atty_first_name' => $mngr['atty_first_name']
                                )
                            )
                        )
                    )
                );
            } else {
                if (!array_key_exists($mngr['firm_id'], $managers[$mngr['user_id']]['user_firms'])) {
                    $managers[$mngr['user_id']]['user_firms'][$mngr['firm_id']] = array(
                        'firm_name' => $mngr['firm_name'],
                        'attys' => array(
                            $mngr['atty_id'] => array(
                                'atty_id' => $mngr['atty_id'],
                                'atty_last_name' => $mngr['atty_last_name'],
                                'atty_first_name' => $mngr['atty_first_name']
                            )
                        )
                    );
                } else {
                    $managers[$mngr['user_id']]['user_firms'][$mngr['firm_id']]['attys'][$mngr['atty_id']] = array(
                        'atty_id' => $mngr['atty_id'],
                        'atty_last_name' => $mngr['atty_last_name'],
                        'atty_first_name' => $mngr['atty_first_name']
                    );
                }
            }
        }

        $data['case_managers'] = $managers;

        //echo '<pre>'.print_r($managers, true).'</pre>';
        //return;

        /*$k = 0;
        for ($i = 0; $i < count($case_managers); $i++) {
            $firm_attorneys = $this->firms->get_firms_attorneys_by_user_id($case_managers[$i]['id'], true);
            if (is_array($firm_attorneys) && count($firm_attorneys) > 0) {
                $case_managers[$i]['firm_attorneys'] = $firm_attorneys;
                $data['case_managers'][$k] = $case_managers[$i];
                $k++;
            }
        }*/
        //echo '<pre>'.print_r($data['case_managers'],true).'</pre>'; return;

        $this->_add_view('assign_case_managers', 1, $data);
        $this->_render();
    }

    /*
    * Get statements to pdf-file for admin
    */
    public function statements()
    {
        //error_reporting(E_ALL);
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $this->load->library('mshc_connector');

        $params = array();
        $billingPeriodFrom = '**/**/****';
        $billingPeriodTo = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accountArry = $this->input->post('sAccountID', true);//$account;
            $dbArry = $this->input->post('sDbName', true);//$db_name;
            $practiceArry = $this->input->post('sPractice', true);//$practice;
            $caseNoArry = $this->input->post('sCaseNo', true);//$case_no;
            $patientArry = $this->input->post('sPatient', true);//$patient;
            $withDocsArry = $this->input->post('sWithDocs', true);
            $typeStatementArry = $this->input->post('sTypeStatement', true);
            $transactionsArry = $this->input->post('sTransactions', true);
            $lobArry = $this->input->post('sLOB', true);
            $financeArry = $this->input->post('sFinance', true);
            $doSplit = $this->input->post('statementSplit', true);

            $this->dateType = $this->input->post('dateType');
            if ($this->dateType == 'service') {
                $this->dateFrom = $this->input->post('dateFrom');
                $this->dateTo = $this->input->post('dateTo');

                if ($this->dateFrom) {
                    $this->dateFrom = date('Y-m-d', strtotime($this->dateFrom));
                    $billingPeriodFrom = date('m/d/Y', strtotime($this->dateFrom));
                }

                if ($this->dateTo) {
                    $this->dateTo = date('Y-m-d', strtotime($this->dateTo));
                    $billingPeriodTo = date('m/d/Y', strtotime($this->dateTo));
                }
            }

            /** @var array $accountArry */
            foreach ($accountArry as $idx => $account)
            {
                $params[] = array(
                    'sAccountID' => $accountArry[$idx],
                    'sDbName' => $dbArry[$idx],
                    'sPractice' => $practiceArry[$idx],
                    'sCaseNo' => $caseNoArry[$idx],
                    'sPatient' => $patientArry[$idx],
                    'sWithDocs' => $withDocsArry[$idx],
                    'sTypeStatement' => $typeStatementArry[$idx],
                    'sTransactions' => $transactionsArry[$idx],
                    'sLOB' => $lobArry[$idx],
                    'sFinance' => $financeArry[$idx],
                );
            }
        } else {
            $doSplit = 1;

            $params[] = array(
                'sAccountID' => (int)$this->uri->segment(3),
                'sDbName' => $this->uri->segment(4),
                'sPractice' => (int)$this->uri->segment(5),
                'sCaseNo' => (int)$this->uri->segment(7),
                'sPatient' => (int)$this->uri->segment(6),
                'sWithDocs' => (int)$this->uri->segment(12, 0),
                'sTypeStatement' => $this->uri->segment(8),
                'sTransactions' => $this->uri->segment(9),
                'sLOB' => $this->uri->segment(10),
                'sFinance' => $this->uri->segment(11),
            );
        }

        $unsplitted_pdf_files = $pdf_files = $toDelete = array();

        foreach ($params as $param)
        {
            $conds = array();

            $conds['account'] = $param['sAccountID'];
            $conds['db_name'] = $param['sDbName'];
            $conds['practice'] = $param['sPractice'];
            $conds['patient'] = $param['sPatient'];
            $conds['case_no'] = $param['sCaseNo'];
            $type_statement = $param['sTypeStatement'];
            $transactions = $param['sTransactions'];
            $lob = $param['sLOB'];
            $finance = $param['sFinance'];
            $with_docs = $param['sWithDocs'];

            if ($transactions) {
                $conds['pend_trns_to'] = date('Y-m-d', $transactions);
            }

            // Add portal activity
            $activity_info = 'Account: ' . $conds['account'] . ';';
            $this->activity->add_activity_log('View Case Statements', 'portal', $activity_info);

            $data['header_statements'] = $this->mshc_connector->getStatementHeader(
                array(1, 2, 3, 4, 5),
                'all',
                array('conds' => $conds, 'debugReturn' => 'sample')
            );

            $data['max_service_date'] = $this->mshc_connector->getMaxServiceDate(
                array(1, 2, 3, 4, 5),
                'all',
                array('conds' => $conds, 'debugReturn' => 'sample')
            );

            $data['patients'] = $this->mshc_connector->getPatientIns(
                array(1, 2, 3, 4, 5),
                'all',
                array('conds' => $conds, 'debugReturn' => 'sample')
            );

            $case_conds['cases'] = array(
                'op' => 'include',
                'value' => array(
                    $conds
                )
            );

            $data['case'] = $this->mshc_connector->getCases(
                array(1, 2, 3, 4, 5),
                'all',
                array(
                    'conds' => $case_conds,
                    'debugReturn' => 'sample_all'
                )
            );

            $isActiveCase = false;
            if (isset($data['case'][0])) {
                $isActiveCase = $data['case'][0]['status'] == 'active';
            }

            if ($type_statement == 'Summary') {
                $data['summary'] = $this->mshc_connector->getSummary(
                    array(1, 2, 3, 4, 5),
                    'all',
                    array('conds' => $conds, 'order' => 'company ASC', 'debugReturn' => 'sample')
                );
            } else {
                if ($type_statement == 'Charges') {
                    $conds['trn_type'] = 'charge';
                }
                $data['visits'] = $this->mshc_connector->getStatement(
                    array(1, 2, 3, 4, 5),
                    'all',
                    array(
                        'conds' => $conds,
                        'order' => array(
                            'Company',
                            'ServiceDate',
                            'dkDBPracGuarPatSeqLine',
                            "case when Type = 'charge' then 1 when Type = 'payment' then 2 when Type = 'adjustment' then 3 end"
                        ),
                        'debugReturn' => 'sample'
                    )
                );
            }

            $html_code = $html_code_pt_chiro = $html_code_medical = $html_first_header = '';

            if ($type_statement == 'Full' || $type_statement == 'Summary') {
                $unappliedPayments = $this->mshc_connector->getUnappliedPayments(
                    array(1, 2, 3, 4, 5),
                    'all',
                    array(
                        'conds' => array(
                            'db_name' => $conds['db_name'],
                            'account' => $conds['account'],
                            'case_no' => $conds['case_no'],
                            'practice' => $conds['practice'],
                            'patient' => $conds['patient'],
                        ),
                        'fields' => array(
                            'statement_company',
                            'amount_unapplied',
                        ),
                        'group' => array(
                            'statement_company',
                            'amount_unapplied',
                        ),
                        'debugReturn' => 'sample',
                    )
                );

                if (is_array($unappliedPayments)  && count($unappliedPayments) && !isset($unappliedPayments[0])) {
                    $unappliedPayments = array($unappliedPayments);
                }

                $mshc = 0;
                foreach ($unappliedPayments as $item)
                {
                    if (strtolower($item['statement_company']) == 'md' || strtolower($item['statement_company']) == 'pt') {
                        $mshc += $item['amount_unapplied'];
                    }
                }

                if ($mshc) {
                    $unappliedPayments[] = array(
                        'statement_company' => 'MSHC',
                        'amount_unapplied' => $mshc
                    );
                }
            } else {
                $unappliedPayments = FALSE;
            }

            $documents = array();
            $visits_counter = 0;

            if ($data['header_statements']) {
                $splitted_statements = array();

                if ($with_docs == 1) {
                    if ($type_statement == 'Charges') {
                        $conds['trn_type'] = 'charge';
                    }
                    $conds['doc_types'] = array('Medical Report', 'MRI Report', 'Prog Note', 'RX REQ', 'NTI Report');
                    //echo '<pre>'.print_r($conds, true).'</pre>';
                    $doc_response = $this->mshc_connector->getDocuments(
                        array(1, 2, 3, 4, 5),
                        'all',
                        array(
                            'conds' => $conds,
                            'order' => array(
                                // 'date_of_service' => 'asc',
                                'document_type',
                                'date_of_service' => 'asc',
                                //  'document_name' => 'asc',
                            ),
                            'debugReturn' => 'sample'
                        )
                    );

                    if (count($doc_response)) {
                        $docTypes = array(
                            'md' => 'medical report',
                            'mri' => 'mri report',
                            'pt' => 'pt note',
                            'bwr' => 'pt note',
                            'rx' => 'rx req',
                            'nti' => 'nti report'
                        );

                        foreach ($doc_response as &$doc)
                        {
                            if (isset($docTypes[strtolower($doc['Company'])]) && $docTypes[strtolower($doc['Company'])] == strtolower($doc['document_type'])) {
                                /** @var DateTime|mixed $serviceDate */
                                $serviceDate = $doc['date_of_service'];
                                if ($this->outOfPeriod($serviceDate, $isActiveCase)) {
                                    continue;
                                }

                                if (strtolower($doc['Company']) == 'md' || strtolower($doc['Company']) == 'pt') {
                                    $doc['Company'] = 'MSHC';
                                }

                                if (!element($doc['Company'], $documents)) {
                                    $documents[$doc['Company']] = array();
                                }
                                $documents[$doc['Company']][] = $doc['full_path'];
                            }
                        }
                    }
                }

                $data['header_statements'][0]['practice_address1'] = 'PO Box 24902';
                $data['header_statements'][0]['practice_city'] = 'Middle River';
                $data['header_statements'][0]['practice_state'] = 'MD';
                $data['header_statements'][0]['practice_zip'] = '21220';

                if ($transactions) {
                    $date_through = date('m/d/Y', $transactions);
                } else {
                    $date_through = date_format($data['max_service_date'][0]['MaxServiceDate'], 'm/d/Y');
                }

                $html_header = '';
                $html_first_page = $html_code_result = '';

                if (isset($data['visits'])) {
                    $data['visits_full'] = array();
                    $visits = array();

                    for ($i = 0; $i < count($data['visits']); ++$i)
                    {
                        if ($data['visits'][$i]['Company'] == 'PT' || $data['visits'][$i]['Company'] == 'MD') {
                            $data['visits'][$i]['Company'] = 'MSHC';
                        }
                    }

                    foreach ($data['visits'] as $visit)
                    {
                        /** @var DateTime|mixed $serviceDate */
                        $serviceDate = $visit['ServiceDate'];
                        if ($this->outOfPeriod($serviceDate, $isActiveCase)) {
                            continue;
                        }

                        if (!element($visit['Company'], $visits)) {
                            $visits[$visit['Company']] = array();
                        }

                        if ($visit['UICompany'] == 'Medical') {
                            $uiCompany = 'MD';
                        } elseif ($visit['UICompany'] == 'PT/Chiro') {
                            $uiCompany = 'PT';
                        } else {
                            $uiCompany = $visit['UICompany'];
                        }

                        if (!element($uiCompany, $visits[$visit['Company']])) {
                            $visits[$visit['Company']][$uiCompany] = array(
                                'name' => $visit['UICompany'],
                                'visits' => array(),
                                'charges' => 0,
                                'payments' => 0,
                                'adjustments' => 0
                            );
                        }

                        $visits[$visit['Company']][$uiCompany]['visits'][] = $visit;

                        if ($visit['Type'] == 'charge') {
                            $visits[$visit['Company']][$uiCompany]['charges'] += $visit['PaymentAmount'];
                        } elseif ($visit['Type'] == 'payment') {
                            $visits[$visit['Company']][$uiCompany]['payments'] += $visit['PaymentAmount'];
                        } elseif ($visit['Type'] == 'adjustment') {
                            $visits[$visit['Company']][$uiCompany]['adjustments'] += $visit['PaymentAmount'];
                        }
                    }

                    foreach ($visits as $name => $company)
                    {
                        if ($lob == 'all' || strtolower($lob) == strtolower($name)) {
                            $html_code_result = '';

                            switch (strtolower($name)) {
                                case 'bwr':
                                    $logo = 'statement_logo_3.jpg';
                                    $logo_alt = $company_name = 'Baltimore Work Rehab';
                                    break;
                                case 'mri':
                                    $logo = 'statement_logo_2.jpg';
                                    $logo_alt = $company_name = 'MRImages';
                                    break;
                                case 'pharmacy':
                                case 'rx':
                                    $logo = 'statement_logo_5.jpg';
                                    $logo_alt = $company_name = 'MED LLC';
                                    break;
                                case 'nti':
                                    $logo = '';
                                    $logo_alt = $company_name = 'NeuroTrauma Institute at Multi-Specialty';
                                    break;
                                default:
                                    $logo = 'statement_logo_1.jpg';
                                    $logo_alt = $company_name = 'Multi-Specialty HealthCare';
                                    break;
                            }

                            $html_first_header = '
                            <div class="content">
                                <table class="header">
                                    <tr>
                                        <td class="logo">' . ($logo ? '<img src="./images/' . $logo . '" alt="' . $logo_alt . '">' : '') . '</td>
                                        <td class="address">' . $company_name . '<br />' .
                                $data['header_statements'][0]['practice_address1'] . '<br />' .
                                $data['header_statements'][0]['practice_city'] . ', ' .
                                $data['header_statements'][0]['practice_state'] . ' ' .
                                $data['header_statements'][0]['practice_zip'] . '</td>
                                        <td class="tax-id">Tax ID: ' .
                                substr($data['header_statements'][0]['practice_tax_id'], 0, 2) . '-' .
                                substr($data['header_statements'][0]['practice_tax_id'], 2) . '
                                        </td>
                                    </tr>
                                </table>
                            </div>';

                            $html_first_page = '<div class="content"><br /><table class="border"><tr><th colspan="2" class="th-with-background">Patient</th></tr>';
                            $html_first_page .= '<tr><td>' . $data['header_statements'][0]['pnt_last_name'] . ', ' . $data['header_statements'][0]['pnt_first_name'] . ' ' . $data['header_statements'][0]['pnt_middle_name'] . '<br />' . $data['header_statements'][0]['pnt_address1'] . '<br />' . $data['header_statements'][0]['pnt_addr_city'] . ', ' . $data['header_statements'][0]['pnt_addr_state'] . ' ' . $data['header_statements'][0]['pnt_addr_zip'] . '</td>';
                            $html_first_page .= '<td><table><tr><td>Account:</td><td>' . (int)$data['header_statements'][0]['pnt_account'] . '</td></tr><tr><td>Print Date:</td><td>' . date('m/d/Y') . '</td></tr>';
                            $html_first_page .= '<tr><td>Billing Period From:</td><td>' . $billingPeriodFrom . '</td></tr>' .
                                '<tr><td>Billing Period To:</td><td>' . ($billingPeriodTo ? $billingPeriodTo : $date_through) . '</td></tr></table></td></tr></table>';

                            $html_first_page .= '<table class="border"><tr><th colspan="2" class="th-with-background">Attorney</th></tr><tr><td>' . $data['header_statements'][0]['atty_name'] . '<br />';
                            $html_first_page .= $data['header_statements'][0]['atty_address1'] . '<br/>' . $data['header_statements'][0]['atty_address2'] . '<br />' . $data['header_statements'][0]['atty_addr_city'] . ', ' . $data['header_statements'][0]['atty_addr_state'] . ' ' . $data['header_statements'][0]['atty_addr_zip'] . '</td>';
                            $case_ind = count($data['patients']) - 1;

                            /** @var DateTime $case_accident_date */
                            $case_accident_date = $data['case'][0]['accident_date'];
                            $html_first_page .= '<td><table><tr><td>Case:</td><td>' . $data['header_statements'][0]['pnt_case_num'] . '</td></tr><tr><td>DOA:</td><td>' . $case_accident_date->format('M d, Y') . '</td></tr><tr><td>Ins Co:</td><td>' . $data['patients'][$case_ind]['InsuranceCompany'] . '</td></tr>';
                            $html_first_page .= '<tr><td>Policy #:</td><td>' . $data['patients'][$case_ind]['PolicyNum'] . '</td></tr></table></td></tr></table>';

                            $html_first_page .= '<table class="diagnosis">'
                                . '<tr>'
                                . '<td class="leftCol" style="padding-left: 0;">Diagnosis' . (empty($data['header_statements'][0]['pnt_diagnosis1']) ? '' : ' A:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis1']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis2']) ? '' : 'B:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis2']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis3']) ? '' : 'C:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis3']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis4']) ? '' : 'D:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis4']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis5']) ? '' : 'E:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis5']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis6']) ? '' : 'F:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis6']) . '</td>'
                                . '</tr>'
                                . "<tr>"
                                . '<td class="leftCol" style="padding-left: 0;">' . (empty($data['header_statements'][0]['pnt_diagnosis7']) ? '' : 'G:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis7']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis8']) ? '' : 'H:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis8']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis9']) ? '' : 'I:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis9']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis10']) ? '' : 'J:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis10']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis11']) ? '' : 'K:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis11']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis12']) ? '' : 'L:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis12']) . '</td>'
                                . "</tr>"
                                . '</table>';

                            $html_header = '<table class="patient"><tr><td style="width: 9%"><strong>Patient:</strong></td><td style="width: 9%">' . (int)$data['header_statements'][0]['pnt_account'] . '</td><td style="width: 43%">' . $data['header_statements'][0]['pnt_last_name'] . ', ' . $data['header_statements'][0]['pnt_first_name'] . ' ' . $data['header_statements'][0]['pnt_middle_name'] . '</td><td style="width: 9%"><strong>Case:</strong></td><td style="width: 30%">' . $data['header_statements'][0]['pnt_case_num'] . '</td></tr></table>';
                            $html_header .= '<table class="service"><tr><th class="service-dt">Service Dt</th><th class="trans-dt">Trans Dt</th><th class="office">Office</th><th class="provider">Provider</th><th class="service">Service</th><th class="charge-credit">Charge/Credit</th></tr></table>';

                            $html_first_page .= '<table class="service first">
                                <tr><th class="service-dt">Service Dt</th>
                                <th class="trans-dt">Trans Dt</th>
                                <th class="office">Office</th>
                                <th class="provider">Provider</th>
                                <th class="service">Service</th>
                                <th class="charge-credit">Charge/Credit</th>
                                </tr></table>';
                            $html_first_page .= '<table class="service first">';

                            $line_count = 1;
                            $second_page = 1;
                            $max_lines = 32;
                            $prev_date = '';
                            $counter = $total_charges = $total_payments = $total_adjustments = 0;
                            $output = 'html_first_page';

                            foreach ($company as $financeName => $visits)
                            {
                                if ($finance == 'all' || strtolower($finance) == strtolower($financeName)) {
                                    ++$counter;

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '<tr><th colspan="6" class="th-with-background">' . $visits['name'] . '</th></tr>';

                                    foreach ($visits['visits'] as $info) {
                                        ++$visits_counter;

                                        if ($info['ServiceDate'] instanceof DateTime) {
                                            $service_date = $info['ServiceDate']->format('m/d/Y');
                                        } else {
                                            $service_date = 'N/A';
                                        }
                                        if ($info['TransDate'] instanceof DateTime) {
                                            $trans_date = $info['TransDate']->format('m/d/Y');
                                        } else {
                                            $trans_date = 'N/A';
                                        }

                                        if ($prev_date != $service_date) {
                                            $prev_date = $service_date;

                                            ++$line_count;
                                            if ($line_count > $max_lines) {
                                                $output = 'html_code_result';
                                                $html_first_page .= '</table>';
                                                if ($second_page) {
                                                    $html_code_result .= '<table class="service">';
                                                    $second_page = 0;
                                                }
                                            } else {
                                                $output = 'html_first_page';
                                            }

                                            ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';
                                        }

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr>';
                                        ${$output} .= '<td class="service-dt" style="vertical-align:top;">' . $service_date . '</td>';
                                        ${$output} .= '<td class="trans-dt" style="vertical-align:top;">' . $trans_date . '</td>';
                                        ${$output} .= '<td class="office" style="vertical-align:top;">' . substr($info['LocName'], 0, 15) . '</td>';
                                        ${$output} .= '<td class="provider" style="vertical-align:top;">' . substr($info['Provider'], 0, 15) . '</td>';
                                        ${$output} .= '<td class="service" style="vertical-align:top;">' . substr($info['Service'], 0, 42) . '</td>';
                                        ${$output} .= '<td class="charge-credit" style="vertical-align:top;">' .
                                            ($info['PaymentAmount'] < 0 ? '&ndash;' : '') . '$' .
                                            ($info['PaymentAmount'] < 0 ? number_format(-1 * $info['PaymentAmount'], 2) : number_format($info['PaymentAmount'], 2)) .
                                            '</td>';
                                        ${$output} .= '</tr>';
                                        /*if (strlen($info['LocName']) > 16 || strlen($info['Provider']) > 16 || strlen($info['Service']) > 45)
                                        {
                                            ++$line_count;
                                        }*/
                                    }

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }

                                    ${$output} .= '
                                        <tr><td colspan="6" style="border-top:1px solid #000;">
                                        <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';

                                    if ($type_statement == 'Full') {
                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table></td></tr></table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">
                                        <tr><td colspan="6">
                                        <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr>
                                            <td align="right">&nbsp;</td>
                                            <td align="right" style="padding-right:20px;">Charges</td>
                                            <td align="right">$' . number_format($visits['charges'], 2) . '</td>
                                            </tr>';

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table></td></tr></table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">
                                        <tr><td colspan="6">
                                        <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr>
                                            <td align="right">&nbsp;</td>
                                            <td align="right" style="padding-right:20px;">Payments</td>
                                            <td align="right">' . ($visits['payments'] < 0 ? '&ndash;' : '') . '$' .
                                            number_format($visits['payments'] < 0 ? 0 - $visits['payments'] : $visits['payments'], 2) . '</td>
                                            </tr>';

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table></td></tr></table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">
                                                <tr><td colspan="6">
                                                <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr>
                                            <td align="right" style="border-bottom:1px solid #000;">&nbsp;</td>
                                            <td align="right" style="border-bottom:1px solid #000;padding-right:20px;padding-bottom:5px;">Adjustments</td>
                                            <td align="right" style="border-bottom:1px solid #000;padding-bottom:5px;">
                                            ' . ($visits['adjustments'] < 0 ? '&ndash;' : '') . '$' .
                                            number_format(($visits['adjustments'] < 0 ? -1 * $visits['adjustments'] : $visits['adjustments']), 2) . '</td>
                                            </tr>';
                                        $balane = $visits['charges'] + $visits['payments'] + $visits['adjustments'];
                                    } else {
                                        $balane = $visits['charges'];
                                    }

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table></td></tr></table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">
                                            <tr><td colspan="6">
                                            <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }

                                    ${$output} .= '<tr>
                                        <td colspan="2" align="right" style="padding-right:20px; padding-top:5px; ">
                                        Total ' . $visits['name'] . ' balance through ' . ($billingPeriodTo ? $billingPeriodTo : $date_through) . '</td>
                                        <td align="right" style="padding-top:5px; ">
                                        ' . ($balane < 0 ? '&ndash;' : '') . '$' . number_format($balane < 0 ? 0 - $balane : $balane, 2) . '</td>
                                        </tr>';

                                    ${$output} .= '</table>
                                        </td></tr>';

                                    $total_charges += $visits['charges'];
                                    $total_payments += $visits['payments'];
                                    $total_adjustments += $visits['adjustments'];

                                    if ($counter == count($company)) {
                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr class="summary"><td colspan="6">
                                            <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';

                                        if ($type_statement == 'Full') {
                                            ${$output} .= '
                                                <tr>
                                                <td align="right" class="summarytitle">SUMMARY</td>
                                                <td align="right" style="padding-right:20px;">Charges</td>
                                                <td align="right">$' . number_format(($total_charges), 2) . '</td>
                                                </tr>';
                                            ++$line_count;
                                            if ($line_count > $max_lines) {
                                                $output = 'html_code_result';
                                            } else {
                                                $output = 'html_first_page';
                                            }
                                            ${$output} .= '
                                                <tr>
                                                <td align="right">&nbsp;</td>
                                                <td align="right" style="padding-right:20px;">Payments</td>
                                                <td align="right">' .
                                                ($total_payments < 0 ? '&ndash;' : '') . '$' .
                                                number_format($total_payments < 0 ? 0 - $total_payments : $total_payments, 2) . '</td>
                                                </tr>';

                                            ++$line_count;
                                            if ($line_count > $max_lines) {
                                                $output = 'html_code_result';
                                            } else {
                                                $output = 'html_first_page';
                                            }
                                            ${$output} .= '
                                                <tr>
                                                <td align="right" style="border-bottom:1px solid #000; ">&nbsp;</td>
                                                <td align="right" style="padding-right:20px;padding-bottom:5px; border-bottom:1px solid #000;">Adjustments</td>
                                                <td align="right" style="padding-bottom:5px; border-bottom:1px solid #000;">
                                                ' . ($total_adjustments < 0 ? '&ndash;' : '') . '$' .
                                                number_format(($total_adjustments < 0 ? (0 - $total_adjustments) : ($total_adjustments)), 2) . '</td>
                                                </tr>';
                                            $total_balance = $total_charges + $total_payments + $total_adjustments;
                                        } else {
                                            $total_balance = $total_charges;
                                        }

                                        //echo '<pre>'.print_r($unappliedPayments, true);return;

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '
                                            <tr>
                                            <td colspan="2" align="right" style="padding-right:20px;padding-top:5px;font-weight:bold;">Total balance through ' .
                                            ($billingPeriodTo ? $billingPeriodTo : $date_through) . '</td>
                                            <td align="right" style="padding-top:5px;">
                                                ' . ($total_balance < 0 ? '&ndash;' : '') . '$' .
                                            number_format($total_balance < 0 ? 0 - $total_balance : $total_balance, 2) . '</td>
                                            </tr>';

                                        if (is_array($unappliedPayments) && count($unappliedPayments)) {
                                            foreach ($unappliedPayments as $unapplied) {
                                                if (strtolower($unapplied['statement_company']) == strtolower($name)) {
                                                    ++$line_count;
                                                    if ($line_count > $max_lines) {
                                                        $output = 'html_code_result';
                                                        $html_first_page .= '</table>';
                                                        if ($second_page) {
                                                            $html_code_result .= '<table class="service">';
                                                            $second_page = 0;
                                                        }
                                                    } else {
                                                        $output = 'html_first_page';
                                                    }
                                                    ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                                    ++$line_count;
                                                    if ($line_count > $max_lines) {
                                                        $output = 'html_code_result';
                                                        $html_first_page .= '</table>';
                                                        if ($second_page) {
                                                            $html_code_result .= '<table class="service">';
                                                            $second_page = 0;
                                                        }
                                                    } else {
                                                        $output = 'html_first_page';
                                                    }
                                                    ${$output} .= '<tr>
                                                        <td colspan="2" align="right" style="padding-right:20px;padding-top:5px;">Unapplied Payments</td>
                                                        <td align="right" style="padding-top:5px;">' .
                                                        ($unapplied['amount_unapplied'] < 0 ? '&ndash;' : '') . '$' .
                                                        ($unapplied['amount_unapplied'] < 0
                                                            ? number_format(-1 * $unapplied['amount_unapplied'], 2)
                                                            : number_format($unapplied['amount_unapplied'], 2)) .
                                                        '</td>
                                                        </tr>';
                                                }
                                            }
                                        }

                                        ${$output} .= '</table>
                                            </td></tr>';
                                    }
                                }
                            }

                            ${$output} .= '</table>';

                            $splitted_statements[] = array(
                                'name' => $name,
                                'first_header' => $html_first_header,
                                'first' => $html_first_page,
                                'others' => $html_code_result,
                                'documents' => element($name, $documents)
                            );
                        }
                    }
                } elseif (isset($data['summary'])) {
                    for ($i = 0; $i < count($data['summary']); ++$i)
                    {
                        if (strpos($data['summary'][$i]['company'], 'MD') !== FALSE || strpos($data['summary'][$i]['company'], 'MDMD')) {
                            $data['summary'][$i]['company'] = 'MSHC';
                            $data['summary'][$i]['statement'] = 'MD';
                            $data['summary'][$i]['statement_name'] = 'Medical';
                        } else if (strpos($data['summary'][$i]['company'], 'PT') !== FALSE || strpos($data['summary'][$i]['company'], 'MDPT')) {
                            $data['summary'][$i]['company'] = 'MSHC';
                            $data['summary'][$i]['statement'] = 'PT';
                            $data['summary'][$i]['statement_name'] = 'PT/Chiro';
                        } else if (strpos($data['summary'][$i]['company'], 'RX') !== FALSE || strpos($data['summary'][$i]['company'], 'MED') !== FALSE) {
                            $data['summary'][$i]['company'] = 'RX';
                            $data['summary'][$i]['statement_name'] = 'Pharmacy';
                        } else if (strpos($data['summary'][$i]['company'], 'MRI') !== FALSE) {
                            $data['summary'][$i]['statement_name'] = 'MRI';
                        } else if (strpos($data['summary'][$i]['company'], 'BWR') !== FALSE) {
                            $data['summary'][$i]['statement_name'] = 'BWR';
                        } else {
                            $data['summary'][$i]['statement_name'] = 'Medical';
                        }
                    }

                    $summaries = array();

                    for ($i = 0; $i < count($data['summary']); ++$i) {
                        if ($data['summary'][$i]['is_charge']) {
                            if (!element($data['summary'][$i]['company'], $summaries)) {
                                $summaries[$data['summary'][$i]['company']] = array();
                            }

                            if (!element($data['summary'][$i]['statement'], $summaries[$data['summary'][$i]['company']])) {
                                $summaries[$data['summary'][$i]['company']][$data['summary'][$i]['statement']] = array(
                                    'name' => $data['summary'][$i]['statement_name'],
                                    'charges' => $data['summary'][$i]['charges'],
                                    'payments' => $data['summary'][$i]['payments'],
                                    'adjustments' => $data['summary'][$i]['adjustments'],
                                    'is_charge' => $data['summary'][$i]['is_charge']
                                );
                            }
                        }
                    }

                    foreach ($summaries as $name => $company)
                    {
                        if ($lob == 'all' || strtolower($lob) == strtolower($name)) {
                            $html_code_result = '';

                            switch (strtolower($name)) {
                                case 'bwr':
                                    $logo = 'statement_logo_3.jpg';
                                    $logo_alt = $company_name = 'Baltimore Work Rehab';
                                    break;
                                case 'mri':
                                    $logo = 'statement_logo_2.jpg';
                                    $logo_alt = $company_name = 'MRImages';
                                    break;
                                case 'rx':
                                case 'pharmacy':
                                    $logo = 'statement_logo_5.jpg';
                                    $logo_alt = $company_name = 'MED LLC';
                                    break;
                                case 'nti':
                                    $logo = '';
                                    $logo_alt = $company_name = 'NeuroTrauma Institute at Multi-Specialty';
                                    break;
                                default:
                                    $logo = 'statement_logo_1.jpg';
                                    $logo_alt = $company_name = 'Multi-Specialty HealthCare';
                                    break;
                            }

                            $html_first_header = '
                            <div class="content">
                                <table class="header">
                                    <tr>
                                        <td class="logo">' . ($logo ? '<img src="./images/' . $logo . '" alt="' . $logo_alt . '">' : '') . '</td>
                                        <td class="address">' . $company_name . '<br />' .
                                    $data['header_statements'][0]['practice_address1'] . '<br />' .
                                    $data['header_statements'][0]['practice_city'] . ', ' .
                                    $data['header_statements'][0]['practice_state'] . ' ' .
                                    $data['header_statements'][0]['practice_zip'] . '</td>
                                        <td class="tax-id">Tax ID: ' .
                                    substr($data['header_statements'][0]['practice_tax_id'], 0, 2) . '-' .
                                    substr($data['header_statements'][0]['practice_tax_id'], 2) . '
                                        </td>
                                    </tr>
                                </table>
                            </div>';

                            $html_first_page = '<div class="content"><br /><table class="border"><tr><th colspan="2" class="th-with-background">Patient</th></tr>';
                            $html_first_page .= '<tr><td>' . $data['header_statements'][0]['pnt_last_name'] . ', ' . $data['header_statements'][0]['pnt_first_name'] . ' ' . $data['header_statements'][0]['pnt_middle_name'] . '<br />' . $data['header_statements'][0]['pnt_address1'] . '<br />' . $data['header_statements'][0]['pnt_addr_city'] . ', ' . $data['header_statements'][0]['pnt_addr_state'] . ' ' . $data['header_statements'][0]['pnt_addr_zip'] . '</td>';
                            $html_first_page .= '<td><table><tr><td>Account:</td><td>' . (int)$data['header_statements'][0]['pnt_account'] . '</td></tr><tr><td>Print Date:</td><td>' . date('m/d/Y') . '</td></tr>';
                            $html_first_page .= '<tr><td>Billing Period From:</td><td>**/**/****</td></tr><tr><td>Billing Period To:</td><td>' . $date_through . '</td></tr></table></td></tr></table>';

                            $html_first_page .= '<table class="border"><tr><th colspan="2" class="th-with-background">Attorney</th></tr><tr><td>' . $data['header_statements'][0]['atty_name'] . '<br />';
                            $html_first_page .= $data['header_statements'][0]['atty_address1'] . '<br/>' . $data['header_statements'][0]['atty_address2'] . '<br />' . $data['header_statements'][0]['atty_addr_city'] . ', ' . $data['header_statements'][0]['atty_addr_state'] . ' ' . $data['header_statements'][0]['atty_addr_zip'] . '</td>';
                            $case_ind = count($data['patients']) - 1;
                            /** @var DateTime $case_accident_date */
                            $case_accident_date = $data['case'][0]['accident_date'];
                            $html_first_page .= '<td><table><tr><td>Case:</td><td>' . $data['header_statements'][0]['pnt_case_num'] . '</td></tr><tr><td>DOA:</td><td>' . $case_accident_date->format('M d, Y') . '</td></tr><tr><td>Ins Co:</td><td>' . $data['patients'][$case_ind]['InsuranceCompany'] . '</td></tr>';
                            $html_first_page .= '<tr><td>Policy #:</td><td>' . $data['patients'][$case_ind]['PolicyNum'] . '</td></tr></table></td></tr></table>';

                            $html_first_page .= '<table class="diagnosis">'
                                . '<tr>'
                                . '<td class="leftCol" style="padding-left: 0;">Diagnosis' . (empty($data['header_statements'][0]['pnt_diagnosis1']) ? '' : ' A:') . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis1'] . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis2']) ? '' : 'B:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis2']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis3']) ? '' : 'C:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis3']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis4']) ? '' : 'D:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis4']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis5']) ? '' : 'E:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis5']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis6']) ? '' : 'F:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis6']) . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td class="leftCol" style="padding-left: 0;">' . (empty($data['header_statements'][0]['pnt_diagnosis7']) ? '' : 'G:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis7']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis8']) ? '' : 'H:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis8']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis9']) ? '' : 'I:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis9']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis10']) ? '' : 'J:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis10']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis11']) ? '' : 'K:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis11']) . '</td>'
                                . '<td class="leftCol">' . (empty($data['header_statements'][0]['pnt_diagnosis12']) ? '' : 'L:' . '</td><td>' . $data['header_statements'][0]['pnt_diagnosis12']) . '</td>'
                                . '</tr>'
                                . '</table>';

                            $html_first_page .= '<table class="service first">';
                            $line_count = 1;
                            $second_page = 1;
                            $max_lines = 32;
                            $counter = $total_charges = $total_payments = $total_adjustments = 0;
                            $output = 'html_first_page';

                            foreach ($company as $financeName => $info)
                            {
                                if ($finance == 'all' || strtolower($finance) == strtolower($financeName)) {
                                    ++$counter;

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '<tr><th colspan="6" class="th-with-background">' . $info['name'] . '</th></tr>';

                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '
                                        <tr><td colspan="6" style="border-top:1px solid #000;">
                                        <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">
                                        <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" style="padding-right:20px;">Charges</td>
                                        <td align="right">$' . number_format($info['charges'], 2) . '</td>
                                        </tr>';
                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '
                                        <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" style="padding-right:20px;">Payments</td>
                                        <td align="right">' . ($info['payments'] < 0 ? '&ndash;' : '') . '$' .
                                        number_format(($info['payments'] < 0
                                            ? (0 - $info['payments'])
                                            : $info['payments']), 2) . '</td>
                                        </tr>';
                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }
                                    ${$output} .= '
                                        <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" style="padding-right:20px;padding-bottom:5px;">Adjustments</td>
                                        <td align="right" style="padding-bottom:5px;">' .
                                        ($info['adjustments'] < 0 ? '&ndash;' : '') . '$' .
                                        number_format(($info['adjustments'] < 0
                                            ? (0 - $info['adjustments'])
                                            : $info['adjustments']), 2) . '</td>
                                        </tr>';
                                    ++$line_count;
                                    if ($line_count > $max_lines) {
                                        $output = 'html_code_result';
                                        $html_first_page .= '</table>';
                                        if ($second_page) {
                                            $html_code_result .= '<table class="service">';
                                            $second_page = 0;
                                        }
                                    } else {
                                        $output = 'html_first_page';
                                    }

                                    $tmp = $info['charges'] + $info['payments'] + $info['adjustments'];

                                    ${$output} .= '
                                        <tr>
                                        <td colspan="2" align="right" style="padding-right:20px; padding-top:5px; border-top:1px solid #000;">
                                            Total ' . $info['name'] . ' balance through ' . $date_through . '</td>
                                        <td align="right" style="border-top:1px solid #000;padding-top:5px; ">
                                            ' . ($tmp < 0 ? '&ndash;' : '') . '$' . number_format($tmp < 0 ? 0 - $tmp : $tmp, 2) . '</td>
                                        </tr>';

                                    ${$output} .= '</table>
                                        </td></tr>';

                                    $total_charges += $info['charges'];
                                    $total_payments += $info['payments'];
                                    $total_adjustments += $info['adjustments'];

                                    if ($counter == count($company)) {
                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                            $html_first_page .= '</table>';
                                            if ($second_page) {
                                                $html_code_result .= '<table class="service">';
                                                $second_page = 0;
                                            }
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '<tr class="summary"><td colspan="6">
                                            <table border="0" cellspacing="0" cellpadding="0" style="width:50%;" align="right">';

                                        ${$output} .= '
                                        <tr>
                                        <td align="right" class="summarytitle">SUMMARY</td>
                                        <td align="right" style="padding-right:20px;">Charges</td>
                                        <td align="right">$' . number_format(($total_charges), 2) . '</td>
                                        </tr>';
                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '
                                        <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" style="padding-right:20px;">Payments</td>
                                        <td align="right">' .
                                            ($total_payments < 0 ? '&ndash;' : '') . '$' .
                                            number_format($total_payments < 0 ? 0 - $total_payments : $total_payments, 2) . '</td>
                                        </tr>';
                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }
                                        ${$output} .= '
                                        <tr>
                                        <td align="right" style="border-bottom:1px solid #000; ">&nbsp;</td>
                                        <td align="right" style="padding-right:20px;padding-bottom:5px; border-bottom:1px solid #000;">Adjustments</td>
                                        <td align="right" style="padding-bottom:5px; border-bottom:1px solid #000;">
                                            ' . ($total_adjustments < 0 ? '&ndash;' : '') . '$' .
                                            number_format(($total_adjustments < 0 ? (0 - $total_adjustments) : ($total_adjustments)), 2) . '</td>
                                        </tr>';
                                        $total_balance = $total_charges + $total_payments + $total_adjustments;

                                        //echo '<pre>'.print_r($unappliedPayments, true);return;

                                        ++$line_count;
                                        if ($line_count > $max_lines) {
                                            $output = 'html_code_result';
                                        } else {
                                            $output = 'html_first_page';
                                        }

                                        ${$output} .= '
                                            <tr>
                                            <td colspan="2" align="right" style="padding-right:20px;padding-top:5px;font-weight:bold;">Total balance through ' . $date_through . '</td>
                                            <td align="right" style="padding-top:5px;">
                                                ' . ($total_balance < 0 ? '&ndash;' : '') . '$' .
                                            number_format($total_balance < 0 ? 0 - $total_balance : $total_balance, 2) . '</td>
                                            </tr>';

                                        if (is_array($unappliedPayments) && count($unappliedPayments)) {
                                            foreach ($unappliedPayments as $unapplied)
                                            {
                                                if ($unapplied['statement_company'] == $name) {
                                                    ++$line_count;
                                                    if ($line_count > $max_lines) {
                                                        $output = 'html_code_result';
                                                        $html_first_page .= '</table>';
                                                        if ($second_page) {
                                                            $html_code_result .= '<table class="service">';
                                                            $second_page = 0;
                                                        }
                                                    } else {
                                                        $output = 'html_first_page';
                                                    }

                                                    ${$output} .= '<tr><td colspan="6">&nbsp;</td></tr>';

                                                    ++$line_count;
                                                    if ($line_count > $max_lines) {
                                                        $output = 'html_code_result';
                                                        $html_first_page .= '</table>';
                                                        if ($second_page) {
                                                            $html_code_result .= '<table class="service">';
                                                            $second_page = 0;
                                                        }
                                                    } else {
                                                        $output = 'html_first_page';
                                                    }
                                                    ${$output} .= '<tr>
                                                        <td colspan="2" align="right" style="padding-right:20px;padding-top:5px;">Unapplied Payments</td>
                                                        <td align="right" style="padding-top:5px;">' .
                                                        ($unapplied['amount_unapplied'] < 0 ? '&ndash;' : '') . '$' .
                                                        ($unapplied['amount_unapplied'] < 0
                                                            ? number_format(-1 * $unapplied['amount_unapplied'], 2)
                                                            : number_format($unapplied['amount_unapplied'], 2)) .
                                                        '</td>
                                                        </tr>';
                                                }
                                            }
                                        }

                                        ${$output} .= '</table>
                                        </td></tr>';
                                    }
                                }
                            }

                            ${$output} .= '</table>';

                            $splitted_statements[] = array(
                                'name' => $name,
                                'first_header' => $html_first_header,
                                'first' => $html_first_page,
                                'others' => $html_code_result,
                                'documents' => element($name, $documents)
                            );
                        }
                    }
                } // if visits or summary

                //echo '<pre>'.print_r($splitted_statements, true);

                require_once(APPPATH . 'libraries/PDFMerger/PDFMerger.php');
                require_once(APPPATH . 'libraries/MPDF/mpdf.php');

                $this->load->library('mshc_general');

                if (count($splitted_statements) > 0) {
                    $docs_filename = NULL;

                    foreach ($splitted_statements as $one_statement)
                    {
                        $first_filename = 'statement_first_' .
                            $conds['account'] . '_' .
                            $conds['db_name'] . '_' .
                            $conds['practice'] . '_' .
                            $conds['case_no'] . '_' .
                            $conds['patient'] . '_' .
                            strtolower($type_statement) .
                            '_' . strtolower($one_statement['name']);

                        $first_filename = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . url_title($first_filename) . '.pdf';
                        $toDelete[] = $first_filename;

                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 30, 20, 10, 10);
                        $mpdf->debug = true;

                        $mpdf->SetDisplayMode('fullpage', 'two');

                        if ($visits_counter > 300) $mpdf->cacheTables = TRUE;

                        $mpdf->SetHTMLHeader($one_statement['first_header']);
                        $mpdf->SetHTMLFooter('<div style="border:0 solid #000;">Page #{PAGENO}/{nbpg}</div>');

                        $mpdf->AddPage('', '', 1, '', 'off');

                        $stylesheet = file_get_contents('css/pdf.css');
                        $mpdf->WriteHTML($stylesheet, 1);
                        $mpdf->list_indent_first_level = 0;
                        $mpdf->WriteHTML($one_statement['first'], 2);

                        if (strlen($one_statement['others']) > 0) {
                            $mpdf->AddPage('', 'E');

                            $mpdf->SetHTMLHeader($html_header);
                            $mpdf->SetHTMLFooter('<div style="border:0 solid #000;">Page #{PAGENO}/{nbpg}</div>');

                            $stylesheet = file_get_contents('css/pdf.css');
                            $mpdf->WriteHTML($stylesheet, 1);
                            $mpdf->list_indent_first_level = 0;
                            $mpdf->WriteHTML($one_statement['others'], 2);

                        }

                        $mpdf->Output($first_filename);
                        unset($mpdf);

                        //$pdf = new PDFMerger;
                        //$pdf->addPDF($first_filename, 'all');

                        $docs = array();

                        if (is_array($one_statement['documents']) && count($one_statement['documents'])) {
                            foreach ($one_statement['documents'] as $doc) {
                                $path = pathinfo($doc);
                                $extension = $path['extension'];
                                switch ($extension) {
                                    case 'jpeg':
                                    case 'jpg':
                                    case 'tif':
                                    case 'tiff':
                                        $file = $this->mshc_general->create_pdf($doc, 'image');
                                        if (FALSE !== $file) $docs[] = $file;
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $docs[] = $this->mshc_general->create_pdf($doc, 'doc');
                                        break;
                                    case 'pdf':
                                        $docs[] = $this->mshc_general->create_pdf($doc, 'pdf');
                                        break;
                                }
                            }

                            /*if (count($docs)) {
                                foreach ($docs as $statement) {
                                    $pdf->addPDF($statement, 'all');
                                }
                            }*/
                        }

                        $filename = 'statement_' .
                            $conds['account'] . '_' .
                            $conds['db_name'] . '_' .
                            $conds['practice'] . '_' .
                            $conds['case_no'] . '_' .
                            $conds['patient'] . '_' .
                            strtolower($type_statement) .
                            '_' . strtolower($one_statement['name']);

                        $pdf_files[] = url_title($filename) . '.pdf';

                        $filename = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . url_title($filename) . '.pdf';

                        //$pdf->merge('file', $filename);
                        //unset($pdf);
                        $tmp = array();
                        foreach ($docs as $doc){
                            $tmpFile = str_replace('file:///', '', $doc);
                            $tmp[] = str_replace('/', DIRECTORY_SEPARATOR, $tmpFile);
                        }

                        $merged = $this->mshc_general->mergePDF($tmp);
                        $exec = '"C:\Program Files (x86)\PDFtk Server\bin\pdftk.exe" '.$first_filename. ' ' .implode(' ', $merged).' cat output '.$filename . ' 2>&1';
                        exec($exec, $return);
                        //print_dump($return);
                        //unlink($first_filename);
                        //if (strlen($one_statement['others']) > 0) unlink($others_filename);
                        $toDelete = array_merge($toDelete, $merged);
                    }

                    if (!$doSplit) {
                        $main_filename = 'statement_' .
                            $conds['account'] . '_' .
                            $conds['db_name'] . '_' .
                            $conds['practice'] . '_' .
                            $conds['case_no'] . '_' .
                            $conds['patient'] . '_' .
                            strtolower($type_statement);

                        $filename = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . url_title($main_filename) . '.pdf';
                        $files = array();
                        foreach ($pdf_files as $file)
                        {
                            $files[] = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $file;
                        }
                        $exec = '"C:\Program Files (x86)\PDFtk Server\bin\pdftk.exe" '.implode(' ', $files).' cat output '.$filename . ' 2>&1';
                        exec($exec, $return);

                        $toDelete = array_merge($toDelete, $pdf_files);
                        $pdf_files = array();
                        $unsplitted_pdf_files[] = $main_filename.'.pdf';
                    }
                } else {
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 30, 20, 10, 10);

                    $mpdf->SetDisplayMode('fullpage', 'two');
                    $mpdf->SetHTMLHeader($html_first_header);
                    $mpdf->SetHTMLFooter('<div style="border:0 solid #000;">&nbsp;</div>');
                    $stylesheet = file_get_contents('css/pdf.css');
                    $mpdf->WriteHTML($stylesheet, 1);

                    $mpdf->list_indent_first_level = 0;
                    $mpdf->WriteHTML($html_code . $html_first_page, 2);
                    $main_filename = 'statement_first_' .
                        $conds['account'] . '_' .
                        $conds['db_name'] . '_' .
                        $conds['practice'] . '_' .
                        $conds['case_no'] . '_' .
                        $conds['patient'] . '_' .
                        strtolower($type_statement) . '.pdf';
                    $statements[] = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $main_filename;
                    $mpdf->debug = true;
                    $mpdf->Output(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $main_filename);

                    if ($html_code_result) {
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 30, 20, 10, 10);

                        $mpdf->SetDisplayMode('fullpage', 'two');
                        $mpdf->SetHTMLHeader($html_header);
                        //$mpdf->SetHTMLFooter('<strong>Page {PAGENO} of {nb}</strong>');

                        $stylesheet = file_get_contents('css/pdf.css');
                        $mpdf->WriteHTML($stylesheet, 1);

                        $mpdf->list_indent_first_level = 0;
                        $mpdf->WriteHTML($html_code_result, 2);
                        $main_filename = 'statement_' .
                            $conds['account'] . '_' .
                            $conds['db_name'] . '_' .
                            $conds['practice'] . '_' .
                            $conds['case_no'] . '_' .
                            $conds['patient'] . '_' .
                            strtolower($type_statement) . '.pdf';
                        $statements[] = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $main_filename;
                        $mpdf->Output(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $main_filename);
                    }
                }
            } // if header_statement
        }

        $unsplitted_pdf_file = array();
        if (!$doSplit && count($unsplitted_pdf_files)) {
            $main_filename = 'statement_'.time();

            //$pdf = new PDFMerger;
            $files = array();
            foreach ($unsplitted_pdf_files as $file)
            {
                //$pdf->addPDF(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $file, 'all');
                $files[] = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $file;
            }

            $filename = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . url_title($main_filename) . '.pdf';
            $merged = $this->mshc_general->mergePDF($files);
            $exec = '"C:\Program Files (x86)\PDFtk Server\bin\pdftk.exe" '.implode(' ', $merged).' cat output '.$filename . ' 2>&1';
            exec($exec, $return);
            //print_dump($return);

            $toDelete = array_merge($toDelete, $merged, $unsplitted_pdf_files);

            //$pdf->merge('file', $filename);

            $unsplitted_pdf_file[] = $main_filename.'.pdf';
            $unsplitted_pdf_files = array();
        }

        if (count($toDelete)) {
            foreach ($toDelete as $file)
            {
                $pos = strpos($file, FCPATH);
                if ($pos === false) {
                    $file = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $file;
                }

                @unlink($file);
            }
        }

        if (count($unsplitted_pdf_file)) {
            $data['pdf_files'] = $unsplitted_pdf_file;
        } else if (count($unsplitted_pdf_files)) {
            $data['pdf_files'] = $unsplitted_pdf_files;
        } else {
            $data['pdf_files'] = $pdf_files;
        }

        $this->_add_view('cases_statements', 1, $data);
        $this->_render();
    }

    public function statement($filename = NULL)
    {
        if (is_null($filename)) {
            return;
        }

        if (file_exists(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $filename)) {
            $file = FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $filename;
            $size = filesize($file);

            if ($size > 1024 * 1024 * 300) {
                redirect(base_url().'statements/'.$filename);
            } else {
                $this->load->helper('download');
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $filename));
                header('Accept-Ranges: bytes');

                @readfile(FCPATH . MSHC_STATEMENTS_FILE_PATH . DIRECTORY_SEPARATOR . $filename);
            }
        }
    }

    /**
     * Check if case service date is out of DOS period
     *
     * @param DateTime|mixed $serviceDate
     * @param bool $isActiveCase
     * @return bool
     */
    public function outOfPeriod($serviceDate, $isActiveCase)
    {
        $date = false;
        $less = false;
        $more = false;

        if ($serviceDate instanceof DateTime && $this->dateType == 'service' && $isActiveCase) {
            $date = $serviceDate->format('Y-m-d');
            if ($this->dateFrom) {
                $less = $date < $this->dateFrom;
            }
            if ($this->dateTo) {
                $more = $date > $this->dateTo;
            }
        }

        if ($date !== false && ($less || $more)) {
            return true;
        }

        return false;
    }

}

/* End of file cases.php */
/* Location: ./application/controllers/cases.php */