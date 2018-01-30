<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Email $email
 * @property MSHC_Connector $mshc_connector
 * @property MSHC_General $mshc_general
 */

class Claim extends CI_Controller
{
    private $sourcePath = '';
    private $combinedFile = '';
    private $xmlData = array();

    public function __construct() 
    {
        parent::__construct();

        $this->load->helper(array('date', 'url', 'html', 'form', 'mshc_helper', 'array', 'download'));
		$this->load->library('mshc_general');
		$this->load->library('mshc_connector');
	}

	/*
	* Index Page for this controller.
	*/
	public function index()
	{
	}

	public function claims_processing()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $this->input->get('auth') != 'mqdU3gWO') {
            die('closed');
        }
        
		error_reporting(E_ALL);
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $docIds = $this->mshc_connector->getDocIDs(
            array(2,5),
            'all'
        );

        log_message('debug', date('Y-m-d H:i:s') . ' Processing...');
        //log_message('debug', print_r($docIds, true));

        if (is_array($docIds) && count($docIds)) {
            ini_set('allow_url_fopen', 1);

            //$docIds = array_slice($docIds, 0, 15);
            /*$docIds1 = array_slice($docIds, 5, 5);
            $docIds2 = array_slice($docIds, 400, 5);
            $docIds = array_merge($docIds1, $docIds2);*/
            //print_dump($docIds);exit;

            $processed = array();
            $errors = array();

            foreach ($docIds as $doc)
            {
			    // if(strtoupper($doc['DocType']) == 'RX REQ' ) { log_message('debug', 'matched');  } else { continue; }
			    // if($doc['GuarantorID'] == 103415 ) { log_message('debug', 'matched');  } else { continue; }
                
				$files = array();

                $srvDate = element('ServiceDate', $doc);
                if ($srvDate instanceof DateTime) {
                    $date = $srvDate->format('Y-m-d');
                } else {
                    $date = date('Y-m-d');
                }

                if (!isset($processed[$doc['DocID']][$doc['GuarantorID']][$doc['PracticeID']][$doc['PatientNum']][$date])) {
                    $processed[$doc['DocID']][$doc['GuarantorID']][$doc['PracticeID']][$doc['PatientNum']][$date] = $doc['DocID'];

                    log_message('debug', 'Processing DocID: ' . element('DocID', $doc));
                    log_message('debug', print_r($doc, true));

                    if (element('DocID', $doc)) {
                        $docs = $this->mshc_connector->getDocumentsThroughDocID(
                            array(2, 5),
                            'all',
                            array(
                                'conds' => array(
                                    'doc_id' => $doc['DocID']
                                )
                            )
                        );

                        log_message('debug', 'Found files: ' . count($docs));
                        log_message('debug', print_r($docs, true));

                        if (is_array($docs) && count($docs)) {
                            foreach ($docs as $page) {
                                $filename = element('full_path', $page);

                                if (false === @$this->mshc_general->fileExists($filename)) {
                                    $errors[] = 'File not found: Document ID: ' . element('DocID', $doc) . '; Filename: ' . $filename;
                                    continue;
                                }

                                if ($filename) {
                                    $path = pathinfo($filename);
                                    $extension = $path['extension'];

                                    try {
                                        $file = false;
                                        switch ($extension) {
                                            case 'jpeg':
                                            case 'jpg':
                                            case 'tif':
                                            case 'tiff':
                                                $file = $this->mshc_general->create_pdf($filename, 'image');
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $file = $this->mshc_general->create_pdf($filename, 'doc');
                                                break;
                                            case 'pdf':
                                                $file = $this->mshc_general->create_pdf($filename, 'pdf');
                                                break;
                                        }

                                        if (false !== $file) {
                                            $files[] = $file;
                                        } else {
                                            log_message('error', 'Converting failed: Document ID: ' . element('DocID', $doc) . '; Filename: ' . $filename);
                                            $errors[] = 'Converting failed: Document ID: ' . element('DocID', $doc) . '; Filename: ' . $filename;
                                        }
                                    } catch (Exception $e) {
                                        log_message('error', 'Converting failed: Document ID: ' . element('DocID', $doc) . '; Filename: ' . $filename);
                                        $errors[] = 'Converting failed: Document ID: ' . element('DocID', $doc) . '; Filename: ' . $filename;
                                    }
                                }
                            }

                            if (count($files)) {
                                foreach ($files as &$file) {
                                    $file = str_replace('file:///', '', $file);
                                    $file = str_replace('/', '\\', $file);

                                }

                                $path = FCPATH . MSHC_CLAIMS_FILE_PATH . DIRECTORY_SEPARATOR . date('mdY');
                                if (!is_dir($path)) {
                                    mkdir($path, 0777, true);
                                }

                                $outputName = $this->createFilename($doc);
                                $merged = $this->mshc_general->mergePDF($files, MSHC_CLAIMS_FILE_PATH);

                                $exec = '"C:\Program Files (x86)\PDFtk Server\bin\pdftk.exe" ' . implode(' ', $merged) . ' cat output "' . $path . DIRECTORY_SEPARATOR . $outputName . '" 2>&1';
                                exec($exec, $return);

                                $this->sourcePath = '\\\\FSS001\\public\\Corporate\\Billing\\ECS Docs\\' . date('mdY') . '';
                                if (!is_dir('"' . $this->sourcePath . '"')) {
                                    exec('mkdir "' . $this->sourcePath . '"', $return);
                                }
                                $exec = 'copy "' . $path . DIRECTORY_SEPARATOR . $outputName . '" "' . $this->sourcePath . '\\' . $outputName . '" 2>&1';
                                exec($exec, $return);

                                $delete = array_merge($files, $merged);
                                foreach ($delete as $file) {
                                    if (false !== @file_get_contents($file, 0, null, 0, 1)) {
                                        unlink($file);
                                    }
                                }

                                if (false !== @file_get_contents($path . DIRECTORY_SEPARATOR . $outputName, 0, null, 0, 1)) {
                                    unlink($path . DIRECTORY_SEPARATOR . $outputName);
                                }

                                $this->combinedFile = $this->sourcePath . '\\' . $outputName;

                                /**
                                 * XML nodes
                                 */
                                $guid = element('base_table_guid', $doc);
                                $extendedData = element('extended_data', $doc);

                                log_message('debug', 'Managing XML DocID: ' . element('DocID', $doc));

                                $this->manageXmlData($guid, $extendedData, $doc, true);

                                log_message('debug', 'Checking sequences: ' . element('DocID', $doc));

                                $this->checkSequences($doc);
                            }
                        }
                    }
                } else {
                    log_message('debug', 'Skipping DocID: ' . element('DocID', $doc). ': already processed');
                }
            }

            if (count($errors)) {
                $this->send_error_notification($errors);
            }
        }
    }

    private function manageXmlData($guid, $extendedData, $document, $usePrev = false, $useSeq = null)
    {
        $reportType = element('ReportType', $document, '');
        //$reportType = $reportType ? $reportType . ' - ' . element('DocType', $document, '') : element('DocType', $document, '-');
        $controlNo = $this->createControlNo($document, $useSeq);

        $srvDate = element('ServiceDate', $document);
        if ($srvDate instanceof DateTime) {
            $date = $srvDate->format('Y-m-d');
        } else {
            $date = date('Y-m-d');
        }

        if ($usePrev) {
            if (isset($this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date])) {
                log_message('debug', 'Using previous extended data');
                log_message('debug', 'Current data: ' . $extendedData);
                $extendedData = $this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date];
                log_message('debug', 'Changed data: ' . $extendedData);
            }
        }

        $newFile = null;
        if ($useSeq) {
            $newFile = $this->createFilename($document, $useSeq);
            $newFile = $this->sourcePath . '\\' . $newFile;

            $exec = 'copy "' . $this->combinedFile . '" "' . $newFile  . '" 2>&1';
            exec($exec, $return);
        }

        if (!$guid) {
            $XMLData = '<?xml version="1.0"?><root>' .
                '<d_claim_addl_info_details>' .
                '<d_claim_addl_info_details_row>' .
                '<practice_id>0</practice_id>' .
                '<guarantor_id>0</guarantor_id>' .
                '<patient_no>0</patient_no>' .
                '<sequence_no>0</sequence_no>' .
                '<report_trans_code>EL</report_trans_code>' .
                '<attach_control_no>' . $controlNo . '</attach_control_no>' .
                '<file_path>' . ($newFile ? $newFile : $this->combinedFile) . '</file_path>' .
                ($reportType ? '<report_type_code>' . $reportType . '</report_type_code>' : '<report_type_code/>') .
                '<attach_control_qual/>' .
                '</d_claim_addl_info_details_row>' .
                '</d_claim_addl_info_details>' .
                '<d_wctx_details>' .
                '<d_wctx_details_row>' .
                '<practice_id>' . element('PracticeID', $document, 0) . '</practice_id>' .
                '<guarantor_id>' . element('GuarantorID', $document, 0) . '</guarantor_id>' .
                '<patient_no>' . element('PatientNum', $document, 0) . '</patient_no>' .
                '<case_no>0</case_no>' .
                '<sequence_no>' . element('SequenceNum', $document, 0) . '</sequence_no>' .
                '<claim_number>' . element('PolicyNum', $document, 0) . '</claim_number>' .
                '<attach_no>' . $controlNo . '</attach_no>' .
                '<attachment_code>OZ</attachment_code>' .
                '<transmission_code>EL</transmission_code>' .
                '<condition_indicator1 />' .
                '</d_wctx_details_row>' .
                '</d_wctx_details>' .
                '</root>';

            log_message('debug', 'No GUID, new extended data: ' . $extendedData);

            $result = $this->mshc_connector->manageInsHdr(
                array(1, 2),
                'insert',
                array(
                    'values' => array(
                        'practice_id' => element('PracticeID', $document, 0),
                        'guarantor_id' => element('GuarantorID', $document, 0),
                        'patient_no' => element('PatientNum', $document, 0),
                        'sequence_no' => $useSeq ? $useSeq : element('SequenceNum', $document, ''),
                        'database_name' => element('DatabaseName', $document, 0),
                        'extended_data' => $XMLData,
                    ),
                    'debugReturn' => 'sample',
                    'order' => array("db_name" => 'desc')
                )
            ); 
        } elseif ($guid && !$extendedData) {
            if (isset($this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date])) {
                $xml = new SimpleXMLElement($this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date]);
                $exists = $xml->d_claim_addl_info_details[0];

                if (!$exists) {
                    $xml->addChild('d_claim_addl_info_details');
                }

                /** @var SimpleXMLElement $row */
                $row = $xml->d_claim_addl_info_details[0]->addChild('d_claim_addl_info_details_row');
                $row->addChild('practice_id', '0');
                $row->addChild('guarantor_id', '0');
                $row->addChild('patient_no', '0');
                $row->addChild('sequence_no', '0');
                $row->addChild('report_trans_code', 'EL');
                $row->addChild('attach_control_no', $controlNo);
                $row->addChild('file_path', $newFile ? $newFile : $this->combinedFile);
                $row->addChild('report_type_code', $reportType);
                $row->addChild('attach_control_qual', '');

                $xml->d_wctx_details[0]->d_wctx_details_row[0]->practice_id = element('PracticeID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->guarantor_id = element('GuarantorID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->patient_no = element('PatientNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->sequence_no = element('SequenceNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->case_no = '0';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->claim_number = element('PolicyNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attach_no = $controlNo;
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attachment_code = 'OZ';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->transmission_code = 'EL';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->condition_indicator1 = '';

                $XMLData = $xml->asXML();

                log_message('debug', 'No GUID, no extended data, exists previous data, add new row: ' . $XMLData);

                $this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date] = $XMLData;
            } else {
                $XMLData = '<?xml version="1.0"?><root>' .
                    '<d_claim_addl_info_details>' .
                    '<d_claim_addl_info_details_row>' .
                    '<practice_id>0</practice_id>' .
                    '<guarantor_id>0</guarantor_id>' .
                    '<patient_no>0</patient_no>' .
                    '<sequence_no>0</sequence_no>' .
                    '<report_trans_code>EL</report_trans_code>' .
                    '<attach_control_no>' . $controlNo . '</attach_control_no>' .
                    '<file_path>' . ($newFile ? $newFile : $this->combinedFile) . '</file_path>' .
                    ($reportType ? '<report_type_code>' . $reportType . '</report_type_code>' : '<report_type_code/>') .
                    '<attach_control_qual/>' .
                    '</d_claim_addl_info_details_row>' .
                    '</d_claim_addl_info_details>' .
                    '<d_wctx_details>' .
                    '<d_wctx_details_row>' .
                    '<practice_id>' . element('PracticeID', $document, 0) . '</practice_id>' .
                    '<guarantor_id>' . element('GuarantorID', $document, 0) . '</guarantor_id>' .
                    '<patient_no>' . element('PatientNum', $document, 0) . '</patient_no>' .
                    '<case_no>0</case_no>' .
                    '<sequence_no>' . element('SequenceNum', $document, 0) . '</sequence_no>' .
                    '<claim_number>' . element('PolicyNum', $document, 0) . '</claim_number>' .
                    '<attach_no>' . $controlNo . '</attach_no>' .
                    '<attachment_code>OZ</attachment_code>' .
                    '<transmission_code>EL</transmission_code>' .
                    '<condition_indicator1 />' .
                    '</d_wctx_details_row>' .
                    '</d_wctx_details>' .
                    '</root>';

                log_message('debug', 'GUID, no extended data, no previous data, new extended data: ' . $XMLData);
            }

            $result = $this->mshc_connector->manageInsHdr(
                array(1,2),
                'update',
                array(
                    'values' => array(
                        'base_table_guid' => $guid,
                        'extended_data' => $XMLData,
                    ),
                    'debugReturn' => 'sample',
                    'order' => array("db_name" => 'desc')
                )
            ); 
        } else {
            $xml = new SimpleXMLElement($extendedData);
            $exists = $xml->d_claim_addl_info_details[0];

            if (!$exists) {
                $xml->addChild('d_claim_addl_info_details');
            } else {
				$row = $xml->d_claim_addl_info_details[0];
				for ($i = 0; $i < $row->children()->count(); ++$i)
                {
					$attachControlNoString = $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[$i]->attach_control_no;
					$attachControlNoPieces = explode("_", $attachControlNoString); // delete past entry for different sequences (keep only currnet sequece entries)
					if($attachControlNoPieces[1] != element('SequenceNum', $document, 0)) {
						unset($xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[$i]);
					}
                }
			} 

            if ($usePrev && isset($this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date])) {
                /** @var SimpleXMLElement $row */
                $row = $xml->d_claim_addl_info_details[0]->addChild('d_claim_addl_info_details_row');
                $row->addChild('practice_id', '0');
                $row->addChild('guarantor_id', '0');
                $row->addChild('patient_no', '0');
                $row->addChild('sequence_no', '0');
                $row->addChild('report_trans_code', 'EL');
                $row->addChild('attach_control_no', $controlNo);
                $row->addChild('file_path', $newFile ? $newFile : $this->combinedFile);
                $row->addChild('report_type_code', $reportType);
                $row->addChild('attach_control_qual', '');

                $xml->d_wctx_details[0]->d_wctx_details_row[0]->practice_id = element('PracticeID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->guarantor_id = element('GuarantorID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->patient_no = element('PatientNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->sequence_no = element('SequenceNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->case_no = '0';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->claim_number = element('PolicyNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attach_no = $controlNo;
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attachment_code = 'OZ';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->transmission_code = 'EL';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->condition_indicator1 = '';

                log_message('debug', 'use previous, exists previous data, add row: ');
            } else {
                /** @var SimpleXMLElement $row */
                $row = $xml->d_claim_addl_info_details[0];
                for ($i = 0; $i < $row->children()->count(); ++$i)
                {
                    unset($xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[$i]);
                }

                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->practice_id = '0';
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->guarantor_id = '0';
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->patient_no = '0';
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->sequence_no = '0';
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->report_trans_code = 'EL';
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->attach_control_no = $controlNo;
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->file_path = $newFile ? $newFile : $this->combinedFile;
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->report_type_code = $reportType;
                $xml->d_claim_addl_info_details[0]->d_claim_addl_info_details_row[0]->attach_control_qual = '';

                $xml->d_wctx_details[0]->d_wctx_details_row[0]->practice_id = element('PracticeID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->guarantor_id = element('GuarantorID', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->patient_no = element('PatientNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->sequence_no = element('SequenceNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->case_no = '0';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->claim_number = element('PolicyNum', $document, 0);
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attach_no = $controlNo;
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->attachment_code = 'OZ';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->transmission_code = 'EL';
                $xml->d_wctx_details[0]->d_wctx_details_row[0]->condition_indicator1 = '';

                log_message('debug', 'don\'t use previous or not exists previous data, clear all row, create new instead: ');
            }

            $XMLData = $xml->asXML();

            log_message('debug', $XMLData);

			log_message('debug', 'GUID, new extended data: ' . $XMLData);
            $result = $this->mshc_connector->manageInsHdr(
                array(1,2),
                'update',
                array(
                    'values' => array(
                        'base_table_guid' => $guid,
                        'extended_data' => $XMLData,
                    ),
                    'debugReturn' => 'sample',
                    'order' => array("db_name" => 'desc')
                )
            ); 
        }

        if ($usePrev) {
            $this->xmlData[element('GuarantorID', $document, 0)][element('PracticeID', $document, 0)][element('PatientNum', $document, 0)][$date] = $XMLData;
        }

        //print_dump($XMLData);
        //print_dump($this->xmlData);
        log_message('debug', 'External DB result: ' . var_dump($result) . print_r($result, true));

        if ($result === false) {
            log_message('debug', 'External DB error: ' . print_r($this->mshc_connector->getError(), true));
            print_dump($this->mshc_connector->getError());
        }
    }

    private function checkSequences($document)
    {
        $srvDate = element('ServiceDate', $document);
        if ($srvDate instanceof DateTime) {
            $date = $srvDate->format('Y-m-d');
        } else {
            $date = date('Y-m-d');
        }

        $result = $this->mshc_connector->getOtherSequences(
            array(2,5),
            'all',
            array(
                'conds' => array(
                    'database_name' => 'amm_live',
                    'service_date_from' => $date . ' 00:00:00',
                    'practice_id' => element('PracticeID', $document, 0),
                    'guarantor_id' => element('GuarantorID', $document, 0),
                    'patient_no' => element('PatientNum', $document, 0),
                    'sequence_no' => element('SequenceNum', $document, 0),
                ),
                'debugReturn' => 'sample',
            )
        );

        log_message('debug', 'Other sequences found: ' . count($result));
        log_message('debug', print_r($result, true));
        //print_dump('seq');
        //print_dump($result);
        if (is_array($result) && count($result)) {
            foreach ($result as $sequence)
            {
                $guid = element('base_table_guid', $sequence);
                $extendedData = element('extended_data', $sequence);

                //$document['SequenceNum'] = element('sequence_no', $sequence);

                log_message('debug', 'Manage XML for sequence: ' . print_r($sequence, true));

                $this->manageXmlData($guid, $extendedData, $document, false, element('sequence_no', $sequence));
            }
        }
    }

    private function createFilename($doc, $sequence = null)
    {
        return $this->createControlNo($doc, $sequence) . '.pdf';
    }

    private function createControlNo($doc, $sequence)
    {
        $srvDate = element('ServiceDate', $doc);
        if ($srvDate instanceof DateTime) {
            $date = $srvDate->format('m_d_Y');
        } else {
            $date = date('m_d_Y');
        }

        $result = strtoupper(
            implode(
                '_',
                array(
                    element('GuarantorID', $doc, ''),
                    $sequence ? $sequence : element('SequenceNum', $doc, ''),
                    element('DocType', $doc, ''),
                    $date,
                    $doc['DocID']
                )
            )
        );
        $result = url_title(str_replace('/', '_', $result), '_');

        if (strlen($result) > 46) {
            $this->load->helper('text');
            $result = substr($result, 0, 46);
        }

        return $result;
    }

    /**
     * @param array $errors
     * @return bool
     */
    public function send_error_notification($errors)
    {
        //$params['send_to'] = 'dizzered@gmail.com';
        $params['send_to'] = 'acurran@amm.bz';
        $params['subject'] = 'Claims Processing Error';

        $message = '<p>Some errors occurred while claims are being processed. Below are the excerpts from log. To see full details please check the log file and run script manually.</p>';
        $message .= '<ul>';
        foreach ($errors as $error)
        {
            $message .= '<li>' . $error . '</li>';
        }
        $message .= '</ul>';

        $params['message'] = $message;
        $params['cc'] = 'MPErway@amm.bz';

        $smtp_host = 'multiemail.multi-specialty.com';
        $smtp_user = 'attyportal';
        $smtp_pass = 'rf6T0ZMf';
        $smtp_port = 25;

        if ($smtp_host && $smtp_user && $smtp_pass && $smtp_port) {
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = $smtp_host;
            $config['smtp_user'] = $smtp_user;
            $config['smtp_pass'] = $smtp_pass;
            $config['smtp_port'] = $smtp_port;
            $config['mailtype'] = element('mailtype', $params) ? $params['mailtype'] : 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $this->email->initialize($config);
            $this->email->set_crlf("\r\n");

            $send_to = element('send_to', $params) ? $params['send_to'] : 'dizzered@gmail.com';
            $default_send_from = 'attyportal@multi-specialty.com';

            $send_from = element('send_from', $params) ? $params['send_from'] : $default_send_from;
            $send_from_name = element('send_from_name', $params) ? $params['send_from_name'] : 'MSHC Attorney Portal';
            $subject = element('subject', $params) ? $params['subject'] : 'Email from MSHC Attorney Portal';
            $message = element('message', $params) ? $params['message'] : '';
            $alt_message = element('alt_message', $params) ? $params['alt_message'] : '';
            $cc = element('cc', $params) ? $params['cc'] : NULL;
            $reply_to = element('reply_to', $params) ? $params['reply_to'] : NULL;
            $attach = element('attach', $params) ? $params['attach'] : NULL;

            $this->email->from($send_from, $send_from_name);
            $this->email->to($send_to);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->set_alt_message($alt_message);
            if ($cc) $this->email->cc($cc);
            if ($reply_to) {
                $reply_to_name = isset($params['userdata']['name']) ? $params['userdata']['name'] : $send_from_name;
                $this->email->reply_to($reply_to, $reply_to_name);
            }

            if ($attach) {
                foreach ($attach as $single) {
                    $this->email->attach($single);
                }
            }

            $sending = $this->email->send();

            //print_r($this->email->print_debugger());
            return $sending;
        }

        return false;
    }
}

/* End of file claim.php */
/* Location: ./application/controllers/claim.php */