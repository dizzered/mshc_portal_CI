<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_ALL);

class Files extends MSHC_Controller 
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
		redirect('');
	}
	
		
	/*
	* 	Export to Excel or Word
	*/
	public function export()
	{
		$type_file = $this->input->post('sTypeFile', true);
		$name_function = $this->input->post('sNameFunction', true);
		$fieldSorting['jtSorting'] = strtolower($this->input->post('sJtSorting', true)); 
		
		//$typeSorting =  $this->input->post('sTypeSorting', true);
		$nameFieldFilter = $this->input->post('sNameFieldFilter', true);
		$valueFieldFilter = $this->input->post('sValueFieldFilter', true);
		$valueFieldFilter2 = $this->input->post('sValueFieldFilter2', true);
		$extAttyID = $this->input->post('extAttyID', true);
		$typeFieldFilter = $this->input->post('sTypeFieldFilter', true);
		$client_id = $this->input->post('sClienID', true) ?: $this->session->userdata('clientID');
		
		$conds = array();
		$account_id = $this->input->post('sAccountID', true);
		if ($account_id != '')
			$conds['account'] = $account_id;
		$database_name = $this->input->post('sDbName', true);
		if ($database_name != '')
			$conds['db_name'] = $database_name;
		$practice = $this->input->post('sPractice', true);
		if ($practice != '')
			$conds['practice'] = $practice;
		$case_number = $this->input->post('sCaseNo', true);
		if ($case_number != '')
			$conds['case_no'] = $case_number;
		$patient = $this->input->post('sPatient', true);
		if ($patient != '')
			$conds['patient'] = $patient;
		
		$this->load->library('mshc_general');
		$get_func_library = 'get_'.$name_function.'_params';
		
		//$input_order = array( $fieldSorting, $typeSorting);
		$jtSorting['jtSorting'] = $this->session->userdata('jtSorting');
		if ($jtSorting['jtSorting'] !== FALSE) $this->sortingTable = $this->get_order_by_post($jtSorting);
		else $this->sortingTable = array();
		
		if (array_key_exists('distance', $this->sortingTable)) {
			$distance_sort = $this->sortingTable['distance'];
			$this->sortingTable = array();
		} else {
			$distance_sort = NULL;
		}

		if ($nameFieldFilter) {
            $nameFil = explode('-', $nameFieldFilter);
            $search_data = array('sortingQriteria' => $typeFieldFilter, 'sortingFieldName' => $nameFil[1], 'sortingValue' => $valueFieldFilter);
            if (isset($valueFieldFilter2) && $valueFieldFilter2 != '') {
                $search_data['sortingValue2'] = $valueFieldFilter2;
            }
        } else {
			$search_data = array();
		}

		$data = array('client_id' => $client_id);

        if ($name_function == 'cases') {
            $search_data = $this->input->post();
        } elseif ($name_function == 'activities') {
            $search_data['user_id'] = $this->input->post('user_id', true);
            $search_data['event_name'] = $this->input->post('event_name', true);
        }
        //print_r($search_data);

		$params = $this->mshc_general->$get_func_library($search_data, $this->sortingTable, $data);

		$get_func = 'get_'.$name_function;
		if ($name_function == 'appointments') $get_func = 'getAppointments';
		$name_controller = $name_function;
		if ($name_function == 'practices') $name_controller = 'clients';
        if ($name_function == 'users') {
            $params['fields']['u.email'] = '';
            $params['fields']['IF(lu.missed_appointments_notified = 1, \'yes\', \'no\')'] = 'missed_appointments_notified';
            $params['fields']['IF(lu.case_discharge_notified = 1, \'yes\', \'no\')'] = 'case_discharge_notified';
            $params['fields']['IF(lu.medical_report_notified = 1, \'yes\', \'no\')'] = 'medical_report_notified';
            $params['fields']['IF(lu.pt_note_notified = 1, \'yes\', \'no\')'] = 'pt_note_notified';
            $params['fields']['IF(lu.outside_medical_record_notified = 1, \'yes\', \'no\')'] = 'outside_medical_record_notified';
            $params['fields']['IF(lu.consult_notified = 1, \'yes\', \'no\')'] = 'consult_notified';
            $params['fields']['IF(lu.ptbwr_referral_notified = 1, \'yes\', \'no\')'] = 'ptbwr_referral_notified';
            $params['fields']['IF(lu.disability_notified = 1, \'yes\', \'no\')'] = 'disability_notified';
            $params['fields']['IF(lu.pharmacy_notified = 1, \'yes\', \'no\')'] = 'pharmacy_notified';
            $params['fields']['IF(lu.high_charges_notified = 1, \'yes\', \'no\')'] = 'high_charges_notified';
            $params['fields']['lu.high_charges_level1'] = '';
            $params['fields']['lu.high_charges_level2'] = '';
            $params['fields']['lu.high_charges_level3'] = '';
            $params['join'][] = array(
                'table' => $this->legal_users_table_name.' AS lu',
                'condition' => 'lu.user_id = u.id'
            );
        }

        if ($name_function == 'appointments') {
			$this->load->library('mshc_connector');
			$params['debugReturn'] = 'sample';
			$params['conds'] = $conds;
			$params['order'] = $this->sortingTable;
			$results = $this->mshc_connector->getAppointments(array(1,2,3,4,5), 'all', $params);
		} else if($name_function == 'distance') {
			$this->load->library('mshc_connector');
			$params['debugReturn'] = 'sample';
			$params['conds'] = $conds;
			$clients_address_wish = $this->input->post('typeDist', true) ?: $this->session->userdata('clients_address_wish');
			$clients_custom_address = $this->input->post('customAddress', true) ?: $this->session->userdata('clients_custom_address');
			$case_patient = $this->mshc_connector->getStatementHeader(array(1,2,3,4,5), 'all', $params);
			unset($conds_app);
			$conds_app = $this->mshc_general->get_distance_params($search_data, $conds);
			$conds_app['status'] = 'kept';
            $conds_app['status_oper'] = 'like';
			$appt_params = array(
				'conds' => $conds_app, 
				'order' => $this->sortingTable, 
				'debugReturn' => 'sample'
			);
			$appointments = $this->mshc_connector->getAppointments(array(1,2,3,4,5), 'all', $appt_params);
			
			$results = $this->mshc_general->get_calculate_distance($case_patient, $appointments, $clients_address_wish, $clients_custom_address);
			
			if (!is_null($distance_sort)) {
				array_sort_by_column($results, 'distance', $distance_sort == 'ASC' ? SORT_ASC : SORT_DESC);
			}
		} else if($name_function == 'discharge') {
            $this->load->library('mshc_connector');
            $conds = $params;

            if ($extAttyID != '') {
                $attys_list = $this->mshc_general->getUserAttorneys($extAttyID);
                //echo '<pre>'.print_r($attys_list, true); return;
                if (is_array($attys_list) && count($attys_list)) {
                    $conds['attorney_id'] = array(
                        'op' => 'or',
                        'value' => array()
                    );
                    $attys = array();
                    foreach ($attys_list as $atty) {
                        $attys[] = array(
                            'attorney_id' => $atty['external_id'],
                            'database' => $atty['ext_db_name']
                        );
                    }
                    $conds['attorney_id']['value'] = $attys;
                }
            }

            if ($this->_user['role_id'] != MSHC_AUTH_SYSTEM_ADMIN && $extAttyID == '') {
                $attorneys_list = $this->mshc_general->getUserAttorneys();

                $dbs = $this->mshc_connector->getDBArray();
                if (count($attorneys_list) > 0) {
                    $conds['attorney_id'] = array(
                        'op' => 'or',
                        'value' => array()
                    );
                    foreach ($attorneys_list as $atty) {
                        $conds['attorney_id']['value'][] = array(
                            'attorney_id' => $atty['external_id'],
                            'database' => $dbs[$atty['ext_db_id']]
                        );
                    }
                }
            }

            $results = $this->mshc_connector->getDischargeReportCases(
                array(1, 2, 3, 4, 5),
                'all',
                array(
                    'conds' => $conds,
                    'order' => $this->sortingTable
                )
            );
            //echo '<pre>'.print_r($results,TRUE); return;
        } elseif ($name_function == 'cases') {
            $fields = array('first_name', 'last_name', 'middle_name', 'account', 'ssn', 'accident_date', 'db_name', 'attorney_name', 'attorney_id', 'status', 'case_category', 'patient', 'case_no', 'practice');
            $results = $this->mshc_connector->getCases(
                array(1, 2, 3, 4, 5),
                'all',
                array(
                    'fields' => $fields,
                    'conds' => $params,
                    'order' => $this->sortingTable,
                    'debugReturn' => 'sample_all'
                )
            );
        } elseif ($name_function == 'activities') {
            $results = $this->activity->$get_func($params, FALSE);
		} else {
			//echo $name_controller;
			$results = $this->$name_controller->$get_func($params, FALSE);
		}
		//echo $this->db->last_query();
		//echo '<pre>'.print_r($results, true); return;

        $output = '';
		switch ($type_file) {
			case 'xls' :
                header("Content-Type: application/vnd.ms-excel");
				//header("Content-Type: application/force-download");
				//header("Content-Type: application/octet-stream");
				//header("Content-Type: application/download");
                $output = $this->getExcelOutput($name_function, $results);
				break;
			case 'doc' :
				header("Content-Type: application/vnd.ms-word");
				header("Expires: 0");
				header("Cache-Control:  must-revalidate, post-check=0, pre-check=0");
                $output = $this->getWordOutput($name_function, $results);
				break;
		}

		header('Content-Type: text/x-csv; charset=utf-8');
		header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.".$type_file);
		header("Content-Transfer-Encoding: binary ");

		echo $output;
	}

    private function cleanData(&$str)
    {
        // escape tab characters
        if (is_string($str)) {
            $str = preg_replace("/\t/", "\\t", $str);

            // escape new lines
            $str = preg_replace("/\r?\n/", "\\n", $str);

            // convert 't' and 'f' to boolean values
            if ($str == 't') $str = 'TRUE';
            if ($str == 'f') $str = 'FALSE';

            // force certain number/date formats to be imported as strings
            if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
                $str = "'$str";
            }

            // escape fields that include double quotes
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    private function getExcelOutput($func, $data)
    {
        $_output = '';
        $arr = array();

        /**
         * Header
         */
        switch ($func) {
            case 'marketers' :
                $arr = array('Last Name', 'First Name', 'Middle Name', 'Phone', 'Email');
                break;
            case 'forms' :
                $arr = array('Name', 'File Name', 'Description', 'Order By');
                break;
            case 'clients' :
                $arr = array('Client Name', 'Number of Practices');
                break;
            case 'users' :
                $arr = array(
                    'Username',
                    'Last Name',
                    'First Name',
                    'Primary Firm',
                    'Role',
                    'Email',
                    'Last Login',
                    'Missed Appointments',
                    'Patient Case Discharge',
                    'Medical Reports',
                    'PT Note',
                    'Outside Medical Record',
                    'Consult',
                    'PT-BWR Referral',
                    'Disability',
                    'Pharmacy',
                    'High Charges Notifications',
                    'High Charges Level 1',
                    'High Charges Level 2',
                    'High Charges Level 3'
                );
                break;
            case 'practices' :
                $arr = array('Practice Name', 'MicroMD Database', 'Rundown Database', 'Split Charges');
                break;
            case 'appointments' :
                $arr = array('Date', 'Time', 'Provider', 'Reason', 'Location', 'Status');
                break;
            case 'distance' :
                $arr = array('Date', 'Time', 'Provider', 'Reason', 'Location', 'Distance');
                break;
            case 'discharge' :
                $arr = array('Patient', 'Account', 'Class', 'DOA', 'Discharge Date', 'Status');
                break;
            case 'cases' :
                $arr = array('Attorney', 'Patient', 'Account', 'Class', 'DOA', 'Status', 'Database');
                break;
            case 'activities' :
                $arr = array('Date', 'User', 'Firm', 'Event', 'Details');
                break;
        }
        $_output .= implode("\t", array_values($arr)) . "\r\n";

        /**
         * Data with table
         */
        if (count($data)) {
            for ($i = 0; $i < count($data); $i++) {
                array_walk($data[$i], array($this, 'cleanData'));
                switch ($func) {
                    case 'marketers' :
                        $arr = array(
                            $data[$i]['last_name'],
                            $data[$i]['first_name'],
                            $data[$i]['middle_name'],
                            $data[$i]['phone'],
                            $data[$i]['email']
                        );
                        break;
                    case 'forms' :
                        $arr = array(
                            $data[$i]['name'],
                            $data[$i]['file_name'],
                            $data[$i]['description'],
                            $data[$i]['weight']
                        );
                        break;
                    case 'clients' :
                        $arr = array(
                            $data[$i]['name'],
                            $data[$i]['practices_count']
                        );
                        break;
                    case 'users' :
                        $arr = array(
                            $data[$i]['username'],
                            $data[$i]['last_name'],
                            $data[$i]['first_name'],
                            $data[$i]['firm_name'],
                            $data[$i]['role_name'],
                            $data[$i]['email'],
                            $data[$i]['last_login_date'],
                            $data[$i]['missed_appointments_notified'],
                            $data[$i]['case_discharge_notified'],
                            $data[$i]['medical_report_notified'],
                            $data[$i]['pt_note_notified'],
                            $data[$i]['outside_medical_record_notified'],
                            $data[$i]['consult_notified'],
                            $data[$i]['ptbwr_referral_notified'],
                            $data[$i]['disability_notified'],
                            $data[$i]['pharmacy_notified'],
                            $data[$i]['high_charges_notified'],
                            intval($data[$i]['high_charges_level1']),
                            intval($data[$i]['high_charges_level2']),
                            intval($data[$i]['high_charges_level3'])
                        );
                        break;
                    case 'practices' :
                        $arr = array(
                            $data[$i]['practice_name'],
                            $data[$i]['micro_db_name'],
                            $data[$i]['rundown_db_name'],
                            $data[$i]['split_charges']
                        );
                        break;
                    case 'appointments' :
                        $arr = array(
                            date_format($data[$i]['date'], 'Y/m/d'),
                            date_format($data[$i]['time'], 'g:i A'),
                            $data[$i]['provider'],
                            $data[$i]['reason'],
                            $data[$i]['location'],
                            $data[$i]['status']
                        );
                        break;
                    case 'distance' :
                        $arr = array(
                            date_format($data[$i]['date'], 'Y/m/d'),
                            date_format($data[$i]['time'], 'g:i A'),
                            $data[$i]['provider'],
                            $data[$i]['reason'],
                            $data[$i]['location'],
                            $data[$i]['distance']
                        );
                        break;
                    case 'discharge' :
                        $arr = array(
                            $data[$i]['last_name'].' '.$data[$i]['first_name'].' '.$data[$i]['middle_name'],
                            $data[$i]['account'],
                            $data[$i]['case_category'],
                            date_format($data[$i]['accident_date'], 'Y/m/d'),
                            date_format($data[$i]['discharge_date'], 'Y/m/d'),
                            $data[$i]['status']
                        );
                        break;
                    case 'cases' :
                        /** @var DateTime $accident_date */
                        $accident_date = $data[$i]['accident_date'];
                        $arr = array(
                            $data[$i]['attorney_name'],
                            $data[$i]['last_name'].' '.$data[$i]['first_name'],
                            $data[$i]['account'],
                            $data[$i]['case_category'],
                            $accident_date instanceof DateTime ? $accident_date->format('m/d/Y') : '',
                            $data[$i]['status'],
                            $data[$i]['db_name']
                        );
                        break;
                    case 'activities' :
                        $arr = array(
                            $data[$i]['created'],
                            $data[$i]['username'],
                            $data[$i]['firm_name'],
                            $data[$i]['event'],
                            $data[$i]['info']
                        );
                        break;
                }

                $_output .= implode("\t", array_values($arr)) . "\r\n";
            }
        }

        return $_output;
    }

    private function getWordOutput($func, $data)
    {
        $_output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="author" content="MSHC Portal" />
		<title>File Uploaded</title>
		</head>
		<body>';

        $_output .= '<table border="1">';

        /**
         * Headers
         */
        switch ($func) {
            case 'marketers' :
                $_output .= '<tr><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Phone</th><th>Email</th></tr>';
                break;
            case 'forms' :
                $_output .= '<tr><th>Name</th><th>File Name</th><th>Description</th><th>Order By</th></tr>';
                break;
            case 'clients' :
                $_output .= '<tr><th>Client Name</th><th>Number of Practices</th></tr>';
                break;
            case 'users' :
                $_output .= '<tr>
                    <th>Username</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Primary Firm</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Last Login</th>
                    <th>Missed Appointments</th>,
                    <th>Patient Case Discharge</th>,
                    <th>Medical Reports</th>,
                    <th>PT Note</th>,
                    <th>Outside Medical Record</th>,
                    <th>Consult</th>,
                    <th>PT-BWR Referral</th>,
                    <th>Disability</th>,
                    <th>Pharmacy</th>,
                    <th>High Charges Notifications</th>
                    <th>High Charges Level 1</th>
                    <th>High Charges Level 2</th>
                    <th>High Charges Level 3</th>
                </tr>';
                break;
            case 'practices' :
                $_output .= '<tr><th>Practice Name</th><th>MicroMD Database</th><th>Rundown Database</th><th>Split Charges</th></tr>';
                break;
            case 'appointments' :
                $_output .= '<tr><th>Date</th><th>Time</th><th>Provider</th><th>Reason</th><th>Location</th><th>Status</th></tr>';
                break;
            case 'distance' :
                $_output .= '<tr><th>Date</th><th>Time</th><th>Provider</th><th>Reason</th><th>Location</th><th>Distance</th></tr>';
                break;
            case 'discharge' :
                $_output .= '<tr><th>Patient</th><th>Account</th><th>Class</th><th>DOA</th><th>Discharge Date</th><th>Status</th></tr>';
                break;
            case 'cases' :
                $_output .= '<tr><th>Attorney</th><th>Patient</th><th>Account</th><th>Class</th><th>DOA</th><th>Status</th><th>Database</th></tr>';
                break;
            case 'activities' :
                $_output .= '<tr><th>Date</th><th>User</th><th>Firm</th><th>Event</th><th>Details</th></tr>';
                break;
        }

        /**
         * Data
         */
        if (count($data)) {
            for ($i = 0; $i < count($data); $i++) {
                switch ($func) {
                    case 'marketers' :
                        $_output .= '<tr><td>'.
                            $data[$i]['last_name'].'</td><td>'.
                            $data[$i]['first_name'].'</td><td>'.
                            $data[$i]['middle_name'].'</td><td>'.
                            $data[$i]['phone'].'</td><td>'.
                            $data[$i]['email'].'</td></tr>';
                        break;
                    case 'forms' :
                        $_output .= '<tr><td>'.
                            $data[$i]['name'].'</td><td>'.
                            $data[$i]['file_name'].'</td><td>'.
                            $data[$i]['description'].'</td><td>'.
                            $data[$i]['weight'].'</td></tr>';
                        break;
                    case 'clients' :
                        $_output .= '<tr><td>'.
                            $data[$i]['name'].'</td><td>'.
                            $data[$i]['practices_count'].'</td></tr>';
                        break;
                    case 'users' :
                        $_output .= '<tr><td>'.
                            $data[$i]['username'].'</td><td>'.
                            $data[$i]['last_name'].'</td><td>'.
                            $data[$i]['first_name'].'</td><td>'.
                            $data[$i]['firm_name'].'</td><td>'.
                            $data[$i]['role_name'].'</td><td>'.
                            $data[$i]['email'].'</td><td>'.
                            $data[$i]['last_login_date'].'</td><td>'.
                            $data[$i]['missed_appointments_notified'].'</td><td>'.
                            $data[$i]['case_discharge_notified'].'</td><td>'.
                            $data[$i]['medical_report_notified'].'</td><td>'.
                            $data[$i]['pt_note_notified'].'</td><td>'.
                            $data[$i]['outside_medical_record_notified'].'</td><td>'.
                            $data[$i]['consult_notified'].'</td><td>'.
                            $data[$i]['ptbwr_referral_notified'].'</td><td>'.
                            $data[$i]['disability_notified'].'</td><td>'.
                            $data[$i]['pharmacy_notified'].'</td><td>'.
                            $data[$i]['high_charges_notified'].'</td><td>'.
                            $data[$i]['high_charges_level1'].'</td><td>'.
                            $data[$i]['high_charges_level2'].'</td><td>'.
                            $data[$i]['high_charges_level3'].'</td>
                        </tr>';
                        break;
                    case 'practices' :
                        $_output .= '<tr><td>'.
                            $data[$i]['practice_name'].'</td><td>'.
                            $data[$i]['micro_db_name'].'</td><td>'.
                            $data[$i]['rundown_db_name'].'</td><td>'.
                            $data[$i]['split_charges'].'</td></tr>';
                        break;
                    case 'appointments' :
                        $_output .= '<tr><td>'.
                            date_format($data[$i]['date'], 'Y/m/d').'</td><td>'.
                            date_format($data[$i]['time'], 'g:i A').'</td><td>'.
                            $data[$i]['provider'].'</td><td>'.
                            $data[$i]['reason'].'</td><td>'.
                            $data[$i]['location'].'</td><td>'.
                            $data[$i]['status'].'</td></tr>';
                        break;
                    case 'distance' :
                        $_output .= '<tr><td>'.
                            date_format($data[$i]['date'], 'Y/m/d').'</td><td>'.
                            date_format($data[$i]['time'], 'g:i A').'</td><td>'.
                            $data[$i]['provider'].'</td><td>'.
                            $data[$i]['reason'].'</td><td>'.
                            $data[$i]['location'].'</td><td>'.
                            $data[$i]['distance'].'</td></tr>';
                        break;
                    case 'discharge' :
                        $_output .= '<tr><td>'.
                            $data[$i]['last_name'].' '.
                            $data[$i]['first_name'].' '.
                            $data[$i]['middle_name'].'</td><td>'.
                            $data[$i]['account'].'</td><td>'.
                            $data[$i]['case_category'].'</td><td>'.
                            date_format($data[$i]['accident_date'], 'Y/m/d').'</td><td>'.
                            date_format($data[$i]['discharge_date'], 'Y/m/d').'</td><td>'.
                            $data[$i]['status'].'</td></tr>';
                        break;
                    case 'cases' :
                        /** @var DateTime $accident_date */
                        $accident_date = $data[$i]['accident_date'];
                        $_output .= '<tr><td>'.
                            $data[$i]['attorney_name'].'</td><td>'.
                            $data[$i]['last_name'].' '.$data[$i]['first_name'].'</td><td>'.
                            $data[$i]['account'].'</td><td>'.
                            $data[$i]['case_category'].'</td><td>'.
                            ($accident_date instanceof DateTime ? $accident_date->format('m/d/Y') : '').'</td><td>'.
                            $data[$i]['status'].'</td><td>'.
                            $data[$i]['db_name'].'</td></tr>';
                        break;
                    case 'activities' :
                        $_output .= '<tr><td>'.
                            $data[$i]['created'].'</td><td>'.
                            $data[$i]['username'].'</td><td>'.
                            $data[$i]['firm_name'].'</td><td>'.
                            $data[$i]['event'].'</td><td>'.
                            $data[$i]['info'].'</td></tr>';
                        break;
                }
            }
        }

        // close table
        $_output .='</table>';
        // close body
        $_output .='</body></html>';

        return $_output;
    }
}

/* End of file files.php */
/* Location: ./application/controllers/files.php */