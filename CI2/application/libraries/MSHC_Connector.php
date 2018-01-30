<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * this class connects external database, could be more in the future for now it's
 * only MicroMD (practice managment system) and DocStar (keeps PDFs) of scanned
 * docs
 *
 * The class expects the connections to in the following order
 * 1 - Live (MicroMD default)
 * 2 - BWR (MicroMD)
 * 3 - MD (MicroMD)
 * 4 - MRI (MicroMD)
 * 5 - PT (MicroMD)
 * 6 - .... (most likely DocStar when we get there)
 *
 * The class makes only database connection to each database server, for example
 * when we query number 3 only, it will still connect to Live (#1) and use prefixed
 * to query number 3 while all queries on Live won't have to be prefixed
 */
class MSHC_Connector
{
    const microMD = 'microMD';
    const docStar = 'docStar';
    private $dbTblPrefix;
    private $dbFncPrefix;

    /*
	* holds database server connections, false by default
	*/
    private $conns = array(
        'microMD' => false,
        'docStar' => false
    );
    private $error;

    /*
	* database object prefixes
	*/
    private $dbPrefxs = array(
        'micorMD' => array(
            '1' => 'AMM_LIVE.dbo',
            '2' => 'BWR.dbo',
            '3' => 'MD.dbo',
            '4' => 'MRI.dbo',
            '5' => 'PT.dbo'
        ),
        'microMDNames' => array( // values from database_name columns not physical objects
            '1' => 'AMM_LIVE',
            '2' => 'BWR',
            '3' => 'MD',
            '4' => 'MRI',
            '5' => 'PT'
        ),
        'docStar' => array(// TODO
        )
    );


    private $fields = array(
        'microMD' => array(
            'cases' => array(
                'last_name' => "p.last_name",
                'first_name' => "p.first_name",
                'dob' => "p.dob",
                'phone' => "p.phone",
                'work_phone' => "p.work_phone",
                'cell_phone' => "p.cell_phone",
                'address1' => "p.address1",
                'address2' => "p.address2",
                'e_mail_address' => "p.e_mail_address",
                'zip4' => "p.zip4",
                'middle_name' => "p.mi",
                'account' => "p.guarantor_id",
                'ssn' => "p.ss_no",
                'accident_date' => "poi.injury_date",
                'accident_date_between' => "poi.injury_date",
                'accident_date_val' => "poi.injury_date",
                "service_date" => "t.service_date_from",
                "service_date_between" => "t.service_date_from",
                "service_date_val" => "t.service_date_from",
                "db_name" => "poi.database_name",
                'case_category' => 'cc.attorney_name',
                'attorney_id' => 'e.employer_id',
                'attorney_name' => 'e.employer_name',
                'case_name' => 'poi.case_name',
                'payment_type_id' => 'ppt.PaymentTypeID',
                'patient' => 'p.patient_no',
                'case_no' => 'poi.case_no',
                'practice' => 'p.practice_id',
                'database' => 'poi.database_name',
                'discharge_date' => "case when t.procedure_code in ('prn', 'dop') then t.service_date_from end",
                'full_name' => "case when len(p.mi) > 0 then p.first_name + ' ' + p.mi + ' ' + p.last_name else p.first_name + ' ' + p.last_name end",
                'discharge_date_having' => 'discharge_date',
                'company' => "CASE WHEN poi.database_name = 'MD'
                                    THEN CASE WHEN th.cost_center IN (10,11) 
                                                   THEN 'MRI'
                                               ELSE 'MD'
                                          END
                               WHEN poi.database_name <> 'AMM_LIVE'
                                  THEN poi.database_name
                               WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI'
                               WHEN poi.practice_id = 1 and th.cost_center = 23
                                  THEN 'BWR'
                               WHEN poi.practice_id = 1 and pm_proc.financial_class = 8
                                  THEN 'RX'
                               WHEN poi.practice_id = 1 and th.cost_center IN (25,35)
                                  THEN 'MRI'
                               WHEN poi.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
                                  THEN 'MD'           
                               WHEN poi.practice_id = 1 and pm_proc.financial_class = 3       
                                  THEN 'PT'
                               ELSE 'PT'
                          END"
            ),
            'high_charges' => array(
                'attorney_id' => 'poi.employer_id',
                'database' => 'poi.database_name'
            ),
            'attys' => array(
                'attorney_id' => 'e.employer_id',
                'database' => 'e.database_name',
            )
        )
    );

    /*
	* used to distinguish between database types, usually for calculated fields
	* the syntax differences require calling different function etc...
	*/
    private $dbTypes = array(
        'microMD' => 'sqlsrv'
    );

    //****************************************************************************
    public function __construct()
    {
        if (ENVIRONMENT == 'production') {
            $this->dbTblPrefix = ''; // no prefixes
            $this->dbFncPrefix = ''; // for live env
        } else {
            $this->dbTblPrefix = 'Dev'; // table prefix
            $this->dbFncPrefix = 'Dev'; // procedure/function prefix
        }
    }

    //****************************************************************************
    public function __destruct()
    {
        if ($this->conns[self::microMD] !== FALSE) {
            sqlsrv_close($this->conns[self::microMD]);
        }
    }

    /**
     * returns true on succesful resource grab, false on failure
     * @param $dbs
     * @return bool
     */
    private function getResources($dbs)
    {
        if (empty($dbs)) {
            $this->error = 'No database servers specified';
            return FALSE;
        } elseif (min($dbs) >= 1 and max($dbs) <= 5) {
            return $this->connectToMicroMD();
        } else {
            $this->error = 'Cross database reference';
            return false;
        }
    } // getResources

    /*****************************************************************************
     * MicroMD connection attempt
     */
    private function connectToMicroMD()
    {
        $server = '10.183.102.31';
        $connInfo = array(
            'UID' => 'PointsConnect',
            'PWD' => 'P01nt5',
            'database' => 'AMM_LIVE'
        );
        $this->conns[self::microMD] = sqlsrv_connect($server, $connInfo);
        if ($this->conns[self::microMD] === FALSE) {
            $this->error = 'Connection to MicroMD database server failed';
            return FALSE;
        } else {
            return TRUE;
        }
    } // connectToMicroMD

    /**
     * search for cases
     * @param array $dbs -> list of database to use for seaching
     * @param  string $queryType -> eather all or first, first returns only one record
     * $queryParams -> conditions and other query attributes like order or number of
     * records to return
     */
    public function getCases($dbs, $queryType = 'all', $queryParams = array())
    {    // print_r($queryParams);    exit();
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first', 'count'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $resultSetParams = array(
                    'query' => 'getCases'
                );
                if ((isset($queryParams['limit']) or isset($queryParams['order'])) and $queryType != 'count') { // added count query condtion, limit and order shouldn't be there
                    $resultSetParams['queryParams'] = $queryParams;
                    $resultSetParams['sortAndLimitQuery'] = TRUE;
                }
                $dbKey = $this->getDBKey($dbs);

                // set fields for query
                if ($queryType != 'count') {
                    $queryFieldList = array(
                        'attorney_id',
                        'attorney_name',
                        'first_name',
                        'last_name',
                        'address1',
                        'address2',
                        'dob',
                        'e_mail_address',
                        'phone',
                        'work_phone',
                        'zip4',
                        'middle_name',
                        'account',
                        'accident_date',
                        'db_name',
                        'ssn',
                        'case_category',
                        'patient',
                        'practice',
                        'case_no',
                        'cell_phone',
                    );
                } else {
                    $queryFieldList = array(
                        'account',
                        'db_name',
                        'patient',
                        'practice',
                        'case_no'
                    );
                }

                if ($queryType == 'count') {
                    $fields = array(
                        $this->constructFieldString($dbKey, 'cases', 'account'),
                        $this->constructFieldString($dbKey, 'cases', 'db_name'),
                        $this->constructFieldString($dbKey, 'cases', 'patient'),
                        $this->constructFieldString($dbKey, 'cases', 'practice'),
                        $this->constructFieldString($dbKey, 'cases', 'case_no'),
                    );
                } else {
                    $fields = array(
                        $this->constructFieldString($dbKey, 'cases', 'attorney_id'),
                        $this->constructFieldString($dbKey, 'cases', 'attorney_name'),
                        $this->constructFieldString($dbKey, 'cases', 'first_name'),
                        $this->constructFieldString($dbKey, 'cases', 'last_name'),
                        $this->constructFieldString($dbKey, 'cases', 'middle_name'),
                        $this->constructFieldString($dbKey, 'cases', 'account'),
                        $this->constructFieldString($dbKey, 'cases', 'accident_date'),
                        $this->constructFieldString($dbKey, 'cases', 'db_name'),
                        $this->constructFieldString($dbKey, 'cases', 'ssn'),
                        $this->constructFieldString($dbKey, 'cases', 'case_category'),
                        $this->constructFieldString($dbKey, 'cases', 'patient'),
                        $this->constructFieldString($dbKey, 'cases', 'practice'),
                        $this->constructFieldString($dbKey, 'cases', 'case_no'),
                        $this->constructFieldString($dbKey, 'cases', 'address1'),
                        $this->constructFieldString($dbKey, 'cases', 'address2'),
                        $this->constructFieldString($dbKey, 'cases', 'dob'),
                        $this->constructFieldString($dbKey, 'cases', 'zip4'),
                        $this->constructFieldString($dbKey, 'cases', 'e_mail_address'),
                        $this->constructFieldString($dbKey, 'cases', 'phone'),
                        $this->constructFieldString($dbKey, 'cases', 'work_phone'),
                        $this->constructFieldString($dbKey, 'cases', 'cell_phone'),
                    );
                }

                if ($queryType != 'count') {
                    $groupBys = array(
                        $this->constructFieldString($dbKey, 'cases', 'attorney_id', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'attorney_name', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'first_name', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'last_name', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'middle_name', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'account', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'accident_date', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'db_name', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'ssn', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'case_category', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'patient', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'practice', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'case_no', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'address1', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'address2', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'dob', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'zip4', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'e_mail_address', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'phone', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'work_phone', 'groupBy'),
                        $this->constructFieldString($dbKey, 'cases', 'cell_phone', 'groupBy'),
                    );
                } else {
                    $groupBys = array();
                }

                if (isset($queryParams['conds'])) {
                    //print_r($queryParams['conds']);
                    $conds = array();
                    $condParams = array();
                    if (key_exists('account', $queryParams['conds'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'account', $queryParams['conds']);
                    }
                    if (key_exists('ssn', $queryParams['conds'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'ssn', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['accident_date'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'accident_date', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['accident_date_val'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'accident_date_val', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['attorney_id'])) {
                        if (isset($queryParams['conds']['attorney_id']['op']) and
                            strtoupper($queryParams['conds']['attorney_id']['op']) == 'OR' and
                            isset($queryParams['conds']['attorney_id']['value'][0]) and
                            is_array($queryParams['conds']['attorney_id']['value'][0]) and
                            count($queryParams['conds']['attorney_id']['value']) > 250
                        ) { // 250 arrays with 2 elements = 500 times 5 databases = 2500, 3000 is the limit, 500 for other elements
                            $insertID = uniqid();
                            $this->constructCondString(
                                $conds,
                                $condParams,
                                $dbKey,
                                'cases',
                                'attorney_id',
                                $queryParams['conds'],
                                array('caseAttysOr', 'insertID' => $insertID)
                            );
                        } else {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'attorney_id', $queryParams['conds']);
                        }
                    }
                    if (isset($queryParams['conds']['case_category'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'case_category', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['phone'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'phone', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['dob'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'dob', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['tr_service_date'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'service_date', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['service_date'])) { // I noticed that Roman was passing it instead of tr_service_date
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'service_date', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['db_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'db_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['full_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'full_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['company'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'company', $queryParams['conds']);
                    }
                    //if(isset($queryParams['conds']['status']))
//					{
//						$this->constructCondString($conds, $condParams, $dbKey, 'cases', 'status', $queryParams['conds']);
//					}
                    if (isset($queryParams['conds']['name'])) {
                        $names = preg_split("/[\s,]+/", $queryParams['conds']['name']); // split string into name keywords
                        $names = array_filter($names, 'strlen'); // remove empty strings (keywords)
                        if (sizeof($names) > 4) { // just in case someone types a load of keywords
                            $this->error = 'Too many keywords';
                            return FALSE;
                        } else {
                            $refNames = array(); // to satisfy sqlsrv, (must be passed by reference)
                            $ct = 0;
                            foreach ($names as $name) {
                                $name = trim($name, ','); // just in case 
                                $name = trim($name, '.'); // just in case
                                if ($this->dbTypes[$dbKey] == 'sqlsrv') {
                                    $conds[] = "(ISNULL(" .
                                        $this->fields[$dbKey]['cases']['first_name'] . ", '') + ' ' + ISNULL(" .
                                        $this->fields[$dbKey]['cases']['last_name'] . ", '') + ' ' + ISNULL(" .
                                        $this->fields[$dbKey]['cases']['middle_name'] . ", '')) like ?";
                                }
                                $refNames[$ct] = '%' . $name . '%';
                                $condParams[] = &$refNames[$ct];
                                ++$ct;
                            }
                        }
                    }
                    if (isset($queryParams['conds']['last_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'last_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['first_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'first_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['cases'])) {
                        $insertID2 = uniqid();
                        if ($queryParams['conds']['cases']['op'] == 'include') {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'cases', $queryParams['conds'], array('includeCases', 'insertID' => $insertID2));
                        }
                        if ($queryParams['conds']['cases']['op'] == 'exclude') {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'cases', $queryParams['conds'], array('excludeCases', 'insertID' => $insertID2));
                        }
                    }
                } else {
                    $conds = array();
                    $condParams = array();
                }

                if ($dbKey == self::microMD) {
                    // MicroMD conditions
                    if (isset($queryParams['conds'])) {
                        if (isset($queryParams['conds']['service_date_between'])) {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'service_date_between', $queryParams['conds']);
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'payment_type_id', array('payment_type_id' => 2));
                        }
                        if (isset($queryParams['conds']['service_date_val'])) {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'service_date_val', $queryParams['conds']);
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'payment_type_id', array('payment_type_id' => 2));
                        }
                        if (isset($queryParams['conds']['accident_date_between'])) {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'accident_date_between', $queryParams['conds']);
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'payment_type_id', array('payment_type_id' => 2));
                        }
                    }


                    $this->fields['microMD']['cases']['status'] = "case when  isnull(poi.case_name, '') like 'dc%' then
							  case when (select sum(th2.balance) 
										from DBName.dbo.pm_TRANSACTION_HEADER as th2 with (nolock)
										where th2.practice_id = p.practice_id 
												 and th2.guarantor_id = p.guarantor_id 
												 and th2.patient_no = p.patient_no 
												 and th2.database_name = poi.database_name 
												 and th2.case_no = poi.case_no) = 0 then  
								   'closed' 
								   else 'discharged' 
							  end
						else 'active' 
						end";
                    if ($queryType != 'count') {
                        $fields[] = $this->constructFieldString($dbKey, 'cases', 'status');
                    }
                    $groupBys[] = $this->constructFieldString($dbKey, 'cases', 'case_name', 'groupBy');
                    if ($queryType != 'count') {
                        $queryFieldList[] = 'status';
                    }
                    if (isset($queryParams['conds']['status'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'status', $queryParams['conds']);
                    }

                    $microMdDbConds = array(
                        "isnull(poi.database_name, '') = ?",
                        "isnull(e.record_type, '') = ?",
                        'poi.employer_id is not null'
                    );

                    $condString = implode(' and ', array_merge($conds, $microMdDbConds));
                    $fieldString = implode(', ', $fields);
                    if ($queryType != 'count') {
                        $groupByString = 'group by ' . implode(', ', $groupBys);
                    } else {
                        $groupByString = '';
                    }

//						print_r($total_record);
                    $query = "select * from (select $fieldString
							from DBName.dbo.pm_patient_other_info as poi with (nolock)
							left join DBName.dbo.pm_patient as p with (nolock)
								on p.practice_id = poi.practice_id 
								and p.guarantor_id = poi.guarantor_id 
								and p.patient_no = poi.patient_no 
								and p.database_name = poi.database_name
							left join DBName.dbo.pm_employer as e with (nolock)
								on poi.employer_id = e.employer_id 
								and poi.database_name = e.database_name
							left join DBName.dbo.pm_TRANSACTION_HEADER as th with (nolock)
								on th.practice_id = poi.practice_id 
								and th.guarantor_id = poi.guarantor_id 
								and th.patient_no = poi.patient_no 
								and th.database_name = poi.database_name 
								and th.case_no = poi.case_no
							left join DBName.dbo.pm_TRANSACTION as t with (nolock)
								on t.practice_id = poi.practice_id 
								and t.guarantor_id = poi.guarantor_id 
								and t.patient_no = poi.patient_no 
								and t.sequence_no = th.sequence_no 
								and t.database_name = th.database_name 
							left join DBName.dbo.pm_procedure as pm_proc with (nolock)
								on (pm_proc.practice_id = t.practice_id or pm_proc.practice_id = 9999) 
								and pm_proc.procedure_code = t.procedure_code 
								and pm_proc.database_name = poi.database_name  
							left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}POSPaymentType as ppt with (nolock)
								on ppt.pos = pm_proc.procedure_pos
							left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDCaseCategories as cc with (nolock)
								on cc.database_name = poi.database_name
								and cc.category_code = poi.case_category
							where $condString
							$groupByString
						) as intbl
						group by " . implode(', ', $queryFieldList);

                }

                $query = str_replace('DBName', 'AMM_Live', $query) .
                    ' union ' .
                    str_replace('DBName', 'BWR', $query) .
                    ' union ' .
                    str_replace('DBName', 'MD', $query) .
                    ' union ' .
                    str_replace('DBName', 'MRI', $query) .
                    ' union ' .
                    str_replace('DBName', 'PT', $query);

                $microMdDbCondParams = array(
                    'AMM_Live',
                    'a',
                );
                $combinedCondParams = array_merge($condParams, $microMdDbCondParams);
                $microMdDbCondParams = array(
                    'BWR',
                    'a',
                );
                $combinedCondParams = array_merge($combinedCondParams, $condParams, $microMdDbCondParams);
                $microMdDbCondParams = array(
                    'MD',
                    'a',
                );
                $combinedCondParams = array_merge($combinedCondParams, $condParams, $microMdDbCondParams);
                $microMdDbCondParams = array(
                    'MRI',
                    'a',
                );
                $combinedCondParams = array_merge($combinedCondParams, $condParams, $microMdDbCondParams);
                $microMdDbCondParams = array(
                    'PT',
                    'a',
                );
                $combinedCondParams = array_merge($combinedCondParams, $condParams, $microMdDbCondParams);

                if (isset($insertID) or isset($insertID2)) {
                    $queryResult = $this->getResultSet($queryType, $this->conns[$dbKey], $query, $combinedCondParams, $resultSetParams);
                    if (isset($insertID)) {
                        $this->cleanuptblTempDbsAttys($insertID, $dbKey);
                    }
                    if (isset($insertID2)) {
                        $this->cleanuptblTempCases($insertID2, $dbKey);
                    }
                    return $queryResult;
                } else {
                    return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $combinedCondParams, $resultSetParams);
                }
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            //print_r('DEBUG');
            if ($queryType == 'count') {
                return array('count' => 5);
            }

            if ($queryType == 'empty') {
                return array();
            }

            if ($queryParams['debugReturn'] == 'sample_first') {
                return array(
                    'attorney_id' => 2700,
                    'attorney_name' => 'Kleid, Wallace',
                    'first_name' => 'DAVID',
                    'last_name' => 'TIEMANN',
                    'middle_name' => '',
                    'account' => '1939',
                    'status' => 'active',
                    'db_name' => 'amm_live',
                    'ssn' => '219943271',
                    'category_code' => '1',
                    'case_category' => 'Auto Accident',
                    'accident_date' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                    'service_date' => (new DateTime('2012-04-05 00:00:00', new DateTimeZone('America/New_York'))),
                    'e_mail_address' => 'test@.test.ru'
                );
            } elseif ($queryParams['debugReturn'] == 'sample_all') {
                return array(
                    array(
                        'attorney_id' => 2700,
                        'attorney_name' => 'Kleid, Wallace',
                        'first_name' => 'DAVID',
                        'last_name' => 'TIEMANN',
                        'middle_name' => '',
                        'account' => '1939',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '219943271',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => array(
                            'date' => '2006-02-28 00:00:00',
                            'timezone_type' => 3,
                            'timezone' => 'America\/New_York'
                        ),
                        'service_date' => (new DateTime('2012-04-03 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '2',
                        'case_no' => '3',
                        'practice' => '1',
                        'e_mail_address' => 'test@.test.ru'
                    ),
                    array(
                        'attorney_id' => 2701,
                        'attorney_name' => 'Rob, Wallace',
                        'first_name' => 'OLAF',
                        'last_name' => 'JACKSON',
                        'middle_name' => '',
                        'account' => '1935',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432712199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => array(
                            'date' => '2014-05-28 00:00:00',
                            'timezone_type' => 3,
                            'timezone' => 'America\/New_York'
                        ),
                        'service_date' => (new DateTime('2012-04-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2703,
                        'attorney_name' => 'Jeck, Loudin',
                        'first_name' => 'Fridrih',
                        'last_name' => 'Marks',
                        'middle_name' => '',
                        'account' => '1938',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432734199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => array(
                            'date' => '2010-02-12 00:00:00',
                            'timezone_type' => 3,
                            'timezone' => 'America\/New_York'
                        ),
                        'service_date' => (new DateTime('2012-04-08 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2709,
                        'attorney_name' => 'Bob, Redstock',
                        'first_name' => 'Clauth',
                        'last_name' => 'Bredly',
                        'middle_name' => '',
                        'account' => '1933',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432582199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => array(
                            'date' => '2006-02-18 00:00:00',
                            'timezone_type' => 3,
                            'timezone' => 'America\/New_York'
                        ),
                        'service_date' => (new DateTime('2012-04-20 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2700,
                        'attorney_name' => 'Rob, Wallace',
                        'first_name' => 'Gustaf',
                        'last_name' => 'Elm',
                        'middle_name' => '',
                        'account' => '1951',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '434812199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => array(
                            'date' => '2015-09-10 00:00:00',
                            'timezone_type' => 3,
                            'timezone' => 'America\/New_York'
                        ),
                        'service_date' => (new DateTime('2012-05-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    )
                );
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    } // getCases

    /*****************************************************************************
     * search for cases
     * $dbs -> list of database to use for seaching
     * $queryType -> eather all or first, first returns only one record
     * $queryParams -> conditions and other query attributes like order or number of
     * records to return
     */
    public function getDischargeReportCases($dbs, $queryType = 'all', $queryParams = array())
    {
        //print_r($queryParams);    exit();
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first', 'count'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $resultSetParams = array(
                    'query' => 'getCases'
                );
                if ((isset($queryParams['limit']) or isset($queryParams['order'])) and $queryType != 'count') // added count query condtion, limit and order shouldn't be there
                {
                    $resultSetParams['queryParams'] = $queryParams;
                    $resultSetParams['sortAndLimitQuery'] = TRUE;
                }
                $dbKey = $this->getDBKey($dbs);

                // set fields for query
                $queryFieldList = array(
                    'attorney_id',
                    'attorney_name',
                    'first_name',
                    'last_name',
                    'address1',
                    'address2',
                    'dob',
                    'e_mail_address',
                    'phone',
                    'work_phone',
                    'zip4',
                    'middle_name',
                    'account',
                    'accident_date',
                    'db_name',
                    'ssn',
                    'case_category',
                    'patient',
                    'practice',
                    'case_no',
                    'cell_phone',
                );
                $fields = array(
                    $this->constructFieldString($dbKey, 'cases', 'attorney_id'),
                    $this->constructFieldString($dbKey, 'cases', 'attorney_name'),
                    $this->constructFieldString($dbKey, 'cases', 'first_name'),
                    $this->constructFieldString($dbKey, 'cases', 'last_name'),
                    $this->constructFieldString($dbKey, 'cases', 'middle_name'),
                    $this->constructFieldString($dbKey, 'cases', 'account'),
                    $this->constructFieldString($dbKey, 'cases', 'accident_date'),
                    $this->constructFieldString($dbKey, 'cases', 'db_name'),
                    $this->constructFieldString($dbKey, 'cases', 'ssn'),
                    $this->constructFieldString($dbKey, 'cases', 'case_category'),
                    $this->constructFieldString($dbKey, 'cases', 'patient'),
                    $this->constructFieldString($dbKey, 'cases', 'practice'),
                    $this->constructFieldString($dbKey, 'cases', 'case_no'),
                    $this->constructFieldString($dbKey, 'cases', 'address1'),
                    $this->constructFieldString($dbKey, 'cases', 'address2'),
                    $this->constructFieldString($dbKey, 'cases', 'dob'),
                    $this->constructFieldString($dbKey, 'cases', 'zip4'),
                    $this->constructFieldString($dbKey, 'cases', 'e_mail_address'),
                    $this->constructFieldString($dbKey, 'cases', 'phone'),
                    $this->constructFieldString($dbKey, 'cases', 'work_phone'),
                    $this->constructFieldString($dbKey, 'cases', 'cell_phone'),
                );
                $groupBys = array(
                    $this->constructFieldString($dbKey, 'cases', 'attorney_id', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'attorney_name', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'first_name', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'last_name', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'middle_name', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'account', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'accident_date', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'db_name', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'ssn', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'case_category', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'patient', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'practice', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'case_no', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'address1', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'address2', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'dob', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'zip4', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'e_mail_address', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'phone', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'work_phone', 'groupBy'),
                    $this->constructFieldString($dbKey, 'cases', 'cell_phone', 'groupBy'),
                );

                if (isset($queryParams['conds'])) {
//					print_r($queryParams['conds']);
                    $conds = array();
                    $condParams = array();
                    if (key_exists('account', $queryParams['conds'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'account', $queryParams['conds']);
                    }
                    if (key_exists('ssn', $queryParams['conds'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'ssn', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['accident_date'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'accident_date', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['accident_date_val'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'accident_date_val', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['attorney_id'])) {
                        if (isset($queryParams['conds']['attorney_id']['op']) and
                            strtoupper($queryParams['conds']['attorney_id']['op']) == 'OR' and
                            isset($queryParams['conds']['attorney_id']['value'][0]) and
                            is_array($queryParams['conds']['attorney_id']['value'][0])
                        ) {
                            $insertID = uniqid();
                            $this->constructCondString(
                                $conds,
                                $condParams,
                                $dbKey,
                                'cases',
                                'attorney_id',
                                $queryParams['conds'],
                                array('caseAttysOr', 'insertID' => $insertID)
                            );
                        } else {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'attorney_id', $queryParams['conds']);
                        }
                    }
                    if (isset($queryParams['conds']['case_category'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'case_category', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['phone'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'phone', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['dob'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'dob', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['tr_service_date'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'service_date', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['db_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'db_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['full_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'full_name', $queryParams['conds']);
                    }
                    //if(isset($queryParams['conds']['status']))
//					{
//						$this->constructCondString($conds, $condParams, $dbKey, 'cases', 'status', $queryParams['conds']);
//					}
                    if (isset($queryParams['conds']['name'])) {
                        $names = preg_split("/[\s,]+/", $queryParams['conds']['name']); // split string into name keywords
                        $names = array_filter($names, 'strlen'); // remove empty strings (keywords)
                        if (sizeof($names) > 4) { // just in case someone types a load of keywords
                            $this->error = 'Too many keywords';
                            return FALSE;
                        } else {
                            $refNames = array(); // to satisfy sqlsrv, (must be passed by reference)
                            $ct = 0;
                            foreach ($names as $name) {
                                $name = trim($name, ','); // just in case 
                                $name = trim($name, '.'); // just in case
                                if ($this->dbTypes[$dbKey] == 'sqlsrv') {
                                    $conds[] = "(ISNULL(" .
                                        $this->fields[$dbKey]['cases']['first_name'] . ", '') + ' ' + ISNULL(" .
                                        $this->fields[$dbKey]['cases']['last_name'] . ", '') + ' ' + ISNULL(" .
                                        $this->fields[$dbKey]['cases']['middle_name'] . ", '')) like ?";
                                }
                                $refNames[$ct] = '%' . $name . '%';
                                $condParams[] = &$refNames[$ct];
                                ++$ct;
                            }
                        }
                    }
                    if (isset($queryParams['conds']['last_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'last_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['first_name'])) {
                        $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'first_name', $queryParams['conds']);
                    }
                    if (isset($queryParams['conds']['cases'])) {
                        $insertID2 = uniqid();
                        if ($queryParams['conds']['cases']['op'] == 'include') {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'cases', $queryParams['conds'], array('includeCases', 'insertID' => $insertID2));
                        }
                        if ($queryParams['conds']['cases']['op'] == 'exclude') {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'cases', $queryParams['conds'], array('excludeCases', 'insertID' => $insertID2));
                        }
                    }
                } else {
                    $conds = array();
                    $condParams = array();
                }

                if ($dbKey == self::microMD) {
                    // MicroMD conditions
                    $microMdConds = array();
                    $microMdCondParams = array();
                    if (isset($queryParams['conds'])) {
                        if (isset($queryParams['conds']['service_date_between'])) {
                            $this->constructCondString($conds, $microMdCondParams, $dbKey, 'cases', 'service_date_between', $queryParams['conds']);
                            $this->constructCondString($conds, $microMdCondParams, $dbKey, 'cases', 'payment_type_id', array('payment_type_id' => 2));
                        }
                        if (isset($queryParams['conds']['service_date_val'])) {
                            $this->constructCondString($conds, $microMdCondParams, $dbKey, 'cases', 'service_date_val', $queryParams['conds']);
                            $this->constructCondString($conds, $microMdCondParams, $dbKey, 'cases', 'payment_type_id', array('payment_type_id' => 2));
                        }
                    }

                    $query = '';
                    $combinedCondParams = array();
                    while ($db = current($dbs)) {
                        $microMdDbFields = array();
                        $microMdDbGroupBys = array();
                        $microMdDbConds = array(
                            "isnull(poi.database_name, '') = ?",
                            "isnull(e.record_type, '') = ?",
                            'poi.employer_id is not null'
                        );
                        $microMdDbCondParams = array(
                            &$this->dbPrefxs['microMDNames'][$db],
                            'a',
                        );

                        $this->fields['microMD']['cases']['status'] = "case when  isnull(poi.case_name, '') like 'dc%' then 
							  case when (select sum(th2.balance) 
										from " . $this->dbPrefxs['microMDNames'][$db] . ".dbo.pm_TRANSACTION_HEADER as th2 with (nolock)
										where th2.practice_id = p.practice_id 
												 and th2.guarantor_id = p.guarantor_id 
												 and th2.patient_no = p.patient_no 
												 and th2.database_name = poi.database_name 
												 and th2.case_no = poi.case_no) = 0 then  
								   'closed' 
								   else 'discharged' 
							  end
						else 'active' 
						end";
                        $this->fields['microMD']['cases']['discharge_date'] =
                            "case when  isnull(poi.case_name, '') like 'dc%' then 
							  case when (select sum(th2.balance) 
									from " . $this->dbPrefxs['microMDNames'][$db] . ".dbo.pm_TRANSACTION_HEADER as th2 with (nolock)
									where th2.practice_id = p.practice_id 
										 and th2.guarantor_id = p.guarantor_id 
										 and th2.patient_no = p.patient_no 
										 and th2.database_name = poi.database_name 
										 and th2.case_no = poi.case_no) = 0 then  
								   null
								   else max(t.service_date_from) 
							  end
						else null 
						end";
                        if ($queryType != 'count' or (isset($queryParams['conds']['discharge_date']) or isset($queryParams['conds']['discharge_date_val']) or isset($queryParams['conds']['status']))) {
                            $microMdDbFields[] = $this->constructFieldString($dbKey, 'cases', 'status');
                            $microMdDbFields[] = $this->constructFieldString($dbKey, 'cases', 'discharge_date');
                        }
                        $microMdDbGroupBys[] = $this->constructFieldString($dbKey, 'cases', 'case_name', 'groupBy');
                        if ($queryType != 'count' or (isset($queryParams['conds']['discharge_date']) or isset($queryParams['conds']['discharge_date_val']) or isset($queryParams['conds']['status']))) {
                            $queryFieldList[] = 'status';
                            $queryFieldList[] = 'discharge_date';
                        }
                        if (isset($queryParams['conds']['status'])) {
                            $this->constructCondString($conds, $condParams, $dbKey, 'cases', 'status', $queryParams['conds']);
                        }

                        // having section
                        $havings = array();
                        if (isset($queryParams['conds']['discharge_date'])) {
                            $queryParams['conds']['discharge_date_having'] = $queryParams['conds']['discharge_date']; // quick fix
                            $this->constructCondString($havings, $microMdDbCondParams, $dbKey, 'cases', 'discharge_date_having', $queryParams['conds']);
                        }
                        if (isset($queryParams['conds']['discharge_date_val'])) {
                            $queryParams['conds']['discharge_date_having'] = $queryParams['conds']['discharge_date']; // quick fix
                            $this->constructCondString($havings, $microMdDbCondParams, $dbKey, 'cases', 'discharge_date_having', $queryParams['conds']);
                        }
                        if (isset($queryParams['conds']['discharge_date']) or isset($queryParams['conds']['discharge_date_val'])) {
                            $havingString = 'having ' . implode(' and ', $havings);
                        } else {
                            $havingString = '';
                        }
                        $condString = implode(' and ', array_merge($conds, $microMdConds, $microMdDbConds));
                        $fieldString = implode(', ', array_merge($fields, $microMdDbFields));
                        $groupByString = implode(', ', array_merge($groupBys, $microMdDbGroupBys));
                        $combinedCondParams = array_merge($combinedCondParams, $condParams, $microMdCondParams, $microMdDbCondParams);

                        $query .= "select * from (select $fieldString
							from " . $this->dbPrefxs['micorMD'][$db] . ".pm_patient_other_info as poi with (nolock)
							left join " . $this->dbPrefxs['micorMD'][$db] . ".pm_patient as p with (nolock)
								on p.practice_id = poi.practice_id 
								and p.guarantor_id = poi.guarantor_id 
								and p.patient_no = poi.patient_no 
								and p.database_name = poi.database_name
							left join " . $this->dbPrefxs['micorMD'][$db] . ".pm_employer as e with (nolock)
								on poi.employer_id = e.employer_id 
								and poi.database_name = e.database_name
							left join " . $this->dbPrefxs['micorMD'][$db] . ".pm_TRANSACTION_HEADER as th with (nolock)
								on th.practice_id = poi.practice_id 
								and th.guarantor_id = poi.guarantor_id 
								and th.patient_no = poi.patient_no 
								and th.database_name = poi.database_name 
								and th.case_no = poi.case_no
							left join " . $this->dbPrefxs['micorMD'][$db] . ".pm_TRANSACTION as t with (nolock)
								on t.practice_id = poi.practice_id 
								and t.guarantor_id = poi.guarantor_id 
								and t.patient_no = poi.patient_no 
								and t.sequence_no = th.sequence_no 
								and t.database_name = th.database_name 
							left join " . $this->dbPrefxs['micorMD'][$db] . ".pm_procedure as pm_proc with (nolock)
								on (pm_proc.practice_id = t.practice_id or pm_proc.practice_id = 9999) 
								and pm_proc.procedure_code = t.procedure_code 
								and pm_proc.database_name = poi.database_name  
							left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}POSPaymentType as ppt with (nolock)
								on ppt.pos = pm_proc.procedure_pos
							left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDCaseCategories as cc with (nolock)
								on cc.database_name = poi.database_name
								and cc.category_code = poi.case_category
							where $condString
							group by $groupByString
						) as intbl
						group by " . implode(', ', $queryFieldList) . ' ' .
                            $havingString;
                        if (next($dbs) !== FALSE) { // remove last union
                            $query .= ' union ';
                        }
                    }
                }
                //print_r($query);
                if (isset($insertID) or isset($insertID2)) {
                    $queryResult = $this->getResultSet($queryType, $this->conns[$dbKey], $query, $combinedCondParams, $resultSetParams);
                    if (isset($insertID)) {
                        $this->cleanuptblTempDbsAttys($insertID, $dbKey);
                    }
                    if (isset($insertID2)) {
                        $this->cleanuptblTempCases($insertID2, $dbKey);
                    }
                    return $queryResult;
                } else {
                    return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $combinedCondParams, $resultSetParams);
                }
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            //print_r('DEBUG');
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample_first') {
                return array(
                    'attorney_id' => 2700,
                    'attorney_name' => 'Kleid, Wallace',
                    'first_name' => 'DAVID',
                    'last_name' => 'TIEMANN',
                    'middle_name' => '',
                    'account' => '2939',
                    'status' => 'active',
                    'db_name' => 'amm_live',
                    'ssn' => '219943271',
                    'category_code' => '1',
                    'case_category' => 'Auto Accident',
                    'accident_date' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                    'discharge_date' => (new DateTime('2012-04-10 00:00:00', new DateTimeZone('America/New_York'))),
                    'service_date' => (new DateTime('2012-04-05 00:00:00', new DateTimeZone('America/New_York')))
                );
            } elseif ($queryParams['debugReturn'] == 'sample_all') {
                return array(
                    array(
                        'attorney_id' => 2700,
                        'attorney_name' => 'Kleid, Wallace',
                        'first_name' => 'DAVID',
                        'last_name' => 'TIEMANN',
                        'middle_name' => '',
                        'account' => '1939',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '219943271',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => (new DateTime('2012-03-23 00:00:00', new DateTimeZone('America/New_York'))),
                        'discharge_date' => (new DateTime('2012-04-06 00:00:00', new DateTimeZone('America/New_York'))),
                        'service_date' => (new DateTime('2012-04-03 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '2',
                        'case_no' => '3',
                        'practice' => '1'
                    ),
                    array(
                        'attorney_id' => 2701,
                        'attorney_name' => 'Rob, Wallace',
                        'first_name' => 'OLAF',
                        'last_name' => 'JACKSON',
                        'middle_name' => '',
                        'account' => '1935',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432712199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'discharge_date' => (new DateTime('2012-04-08 00:00:00', new DateTimeZone('America/New_York'))),
                        'service_date' => (new DateTime('2012-04-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2703,
                        'attorney_name' => 'Jeck, Loudin',
                        'first_name' => 'Fridrih',
                        'last_name' => 'Marks',
                        'middle_name' => '',
                        'account' => '1938',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432734199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => (new DateTime('2012-04-04 00:00:00', new DateTimeZone('America/New_York'))),
                        'discharge_date' => (new DateTime('2012-04-10 00:00:00', new DateTimeZone('America/New_York'))),
                        'service_date' => (new DateTime('2012-04-08 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2709,
                        'attorney_name' => 'Bob, Redstock',
                        'first_name' => 'Clauth',
                        'last_name' => 'Bredly',
                        'middle_name' => '',
                        'account' => '1933',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '432582199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => (new DateTime('2012-04-15 00:00:00', new DateTimeZone('America/New_York'))),
                        'discharge_date' => (new DateTime('2012-04-18 00:00:00', new DateTimeZone('America/New_York'))),
                        'service_date' => (new DateTime('2012-04-20 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    ),
                    array(
                        'attorney_id' => 2700,
                        'attorney_name' => 'Rob, Wallace',
                        'first_name' => 'Gustaf',
                        'last_name' => 'Elm',
                        'middle_name' => '',
                        'account' => '1951',
                        'status' => 'active',
                        'db_name' => 'amm_live',
                        'ssn' => '434812199',
                        'category_code' => '1',
                        'case_category' => 'Auto Accident',
                        'accident_date' => (new DateTime('2012-04-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'discharge_date' => (new DateTime('2012-05-03 00:00:00', new DateTimeZone('America/New_York'))),
                        'service_date' => (new DateTime('2012-05-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'patient' => '1',
                        'case_no' => '2',
                        'practice' => '0'
                    )
                );
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    } // getDischargeReportCases

    //****************************************************************************
    public function getSummary($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );

                    if (!empty($queryParams['conds']['last_post_date'])) {
                        $maxServiceDateCond = "<= ?";
                        $condParams[] = $queryParams['conds']['last_post_date'];
                    } else {
                        $maxServiceDateCond = "<= (SELECT MIN(ds.day_sheet_date) - 1 as MaxServiceDate FROM AMM_LIVE.dbo.pm_day_sheet_info ds WHERE ds.database_name = 'amm_live')";
                    }

                    $query = "select case when gs.IsCharge = 1 
                              then case when gs.Company = 'RX'
                                          then 'MED, LLC'
                                        when gs.Company = 'MD'
                                          then 'MSHC - MD'
                                        when gs.Company = 'PT'
                                          then 'MSHC - PT'
                                        else gs.Company
                                  end
                            else case    
                                (CASE WHEN DatabaseName = 'MD'
                                    THEN CASE WHEN CostCenterID IN (10,11) THEN 'MRI'
                                            ELSE 'MD'
                                        END
                                    WHEN DatabaseName <> 'AMM_LIVE'
                                    THEN DatabaseName
                                    WHEN ServiceFacility BETWEEN 101 AND 121 
                                    THEN 'NTI'
                                    WHEN PracticeID = 1 and CostCenterID = 23
                                    THEN 'BWR'
                                    WHEN PracticeID = 1 and CostCenterID IN (25,35)
                                    THEN 'MRI'
                                    ELSE 'PT'
                                END) when 'BWR' then 'BWR'
                                     when 'MRI' then 'MRI'
                                     else 'MD' end 
                        end as company,
                    sum(gs.ChargeAmount + gs.AdjustmentChargeAmount) as charges,
                    sum(gs.PaymentAmount + gs.RefundAmount) as payments,
                    sum(gs.AdjustmentWriteOffAmount) as adjustments,
                    gs.IsCharge as is_charge
                from PointsProcesses.MicroMD.get{$this->dbFncPrefix}Summary(?,?,?,?,?) as gs
                left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice as ploc
                  on ploc.database_name = gs.DatabaseName
                  and ploc.cost_center_id = gs.CostCenterID
                  and ploc.practice_id = gs.practiceID
                left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}Practice as p 
                  on p.practice_id = gs.PracticeID
                  and ploc.PortalPracticeID = p.PortalPracticeId
                where gs.ServiceDate $maxServiceDateCond
                group by case when gs.IsCharge = 1 
                            then case when gs.Company = 'RX'
                                          then 'MED, LLC'
                                        when gs.Company = 'MD'
                                          then 'MSHC - MD'
                                        when gs.Company = 'PT'
                                          then 'MSHC - PT'
                                        else gs.Company
                                  end
                            else case    
                                  (CASE WHEN DatabaseName = 'MD'
                                      THEN CASE WHEN CostCenterID IN (10,11) THEN 'MRI'
                                              ELSE 'MD'
                                          END
                                      WHEN DatabaseName <> 'AMM_LIVE'
                                      THEN DatabaseName
                                      WHEN ServiceFacility BETWEEN 101 AND 121 
                                      THEN 'NTI'
                                      WHEN PracticeID = 1 and CostCenterID = 23
                                      THEN 'BWR'
                                      WHEN PracticeID = 1 and CostCenterID IN (25,35)
                                      THEN 'MRI'
                                      ELSE 'PT'
                                  END) when 'BWR' then 'BWR'
                                       when 'MRI' then 'MRI'
                                       else 'MD' end
                        end, 
                        gs.IsCharge
                order by is_charge desc, company";
                    //			print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'company' => 'MSHC - MD',
                        'charges' => '1997.00',
                        'payments' => '-732.00',
                        'adjustments' => '0.00',
                        'is_charge' => '1'
                    ),
                    array(
                        'company' => 'MRI',
                        'charges' => '3232.00',
                        'payments' => '-1768.00',
                        'adjustments' => '0.00',
                        'is_charge' => '1'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getUnappliedPayments($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    // conditions
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );
                    // fields
                    if (isset($queryParams['fields'])) {
                        $fields = array("case    
        (CASE WHEN DatabaseName = 'MD'
            THEN CASE WHEN CostCenterID IN (10,11) THEN 'MRI'
                    ELSE 'MD'
                END
            WHEN DatabaseName <> 'AMM_LIVE'
            THEN DatabaseName
            WHEN ServiceFacility BETWEEN 101 AND 121 
            THEN 'NTI'
            WHEN PracticeID = 1 and CostCenterID = 23
            THEN 'BWR'
            WHEN PracticeID = 1 and CostCenterID IN (25,35)
            THEN 'MRI'
            ELSE 'PT'
        END) when 'BWR' then 'BWR'
             when 'MRI' then 'MRI'
             else 'MD' end as statement_company");
                        if (in_array('sequence_no', $queryParams['fields'])) {
                            $fields[] = 'SequenceNo as sequence_num';
                        }
                        if (in_array('service_date', $queryParams['fields'])) {
                            $fields[] = 'ServiceDate as service_date';
                        }
                        if (in_array('amount_unapplied', $queryParams['fields'])) {
                            $fields[] = 'sum(AmountUnapplied) as amount_unapplied';
                        }
                    } else {
                        $fields = array('SequenceNo as sequence_num', 'ServiceDate as service_date', 'AmountUnapplied as amount_unapplied');
                    }
                    $fieldsString = implode(',', $fields);

                    // group by
                    if (isset($queryParams['group'])) {
                        $groupBys = array("case    
        (CASE WHEN DatabaseName = 'MD'
            THEN CASE WHEN CostCenterID IN (10,11) THEN 'MRI'
                    ELSE 'MD'
                END
            WHEN DatabaseName <> 'AMM_LIVE'
            THEN DatabaseName
            WHEN ServiceFacility BETWEEN 101 AND 121 
            THEN 'NTI'
            WHEN PracticeID = 1 and CostCenterID = 23
            THEN 'BWR'
            WHEN PracticeID = 1 and CostCenterID IN (25,35)
            THEN 'MRI'
            ELSE 'PT'
        END) when 'BWR' then 'BWR'
             when 'MRI' then 'MRI'
             else 'MD' end");
                        if (in_array('sequence_no', $queryParams['fields'])) {
                            $groupBys[] = 'SequenceNo';
                        }
                        if (in_array('service_date', $queryParams['fields'])) {
                            $groupBys[] = 'ServiceDate';
                        }
                        $groupByString = 'group by ' . implode(',', $groupBys);
                    } else {
                        $groupByString = '';
                    }

                    // order by
                    if (isset($queryParams['order'])) {
                        $resultSetParams['queryParams'] = $queryParams;
                        $resultSetParams['sortAndLimitQuery'] = TRUE;
                    } else {
                        $resultSetParams = array();
                    }

                    $query = "select $fieldsString
					from PointsProcesses.MicroMD.get{$this->dbFncPrefix}UnappliedPayments(?,?,?,?,?) as gs
					$groupByString";
                    //			print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $resultSetParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'sequence_num' => 13,
                        'service_date' => (new DateTime('2012-03-12 00:00:00', new DateTimeZone('America/New_York'))),
                        'amount_unapplied' => -50.60
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getStatementHeader($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $ammLiveCondParams = array(
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['patient'],
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['case_no'],
                    );


                    $ammLiveQuery = "select  p.first_name as pnt_first_name,
							p.mi as pnt_middle_name,
							p.last_name as pnt_last_name,
							p.address1 as pnt_address1,
							p.address2 as pnt_address2,
							pnt_addr.city as pnt_addr_city,
							pnt_addr.state as pnt_addr_state,
							pnt_addr.zip_code as pnt_addr_zip,
							cast(poi.guarantor_id as varchar(128)) + '.' + cast(poi.patient_no as varchar(128)) as pnt_account,
							poi.case_name as pnt_case_num,
							null as pnt_diagnosis1,
							null as pnt_diagnosis2,
							null as pnt_diagnosis3,
							null as pnt_diagnosis4,
							pw.employer_name as pnt_work_name,
							pw.address1 as pnt_work_address1,
							pw.address2 as pnt_work_address2,
							pnt_work_addr.city as pnt_work_addr_city,
							pnt_work_addr.state as pnt_work_addr_state,    
							pnt_work_addr.zip_code as pnt_work_addr_zip,
							e.employer_name as atty_name,
							e.address1 as atty_address1,
							e.address2 as atty_address2,
							atty_addr.city as atty_addr_city,
							atty_addr.state as atty_addr_state,    
							atty_addr.zip_code as atty_addr_zip,
							pr.pay_to_name as practice_name,
							pr.pay_to_address1 as practice_address1,
							pr.pay_to_address2 as practice_address2,
							pc3.city as practice_city,
							pc3.state as practice_state,
							pc3.zip_code as practice_zip,
							pr.federal_id as practice_tax_id,
							null as pnt_diagnosis5,
							null as pnt_diagnosis6,
							null as pnt_diagnosis7,
							null as pnt_diagnosis8,
							null as pnt_diagnosis9,
							null as pnt_diagnosis10,
							null as pnt_diagnosis11,
							null as pnt_diagnosis12,
              poi.diagnosis1,
							poi.diagnosis2,
							poi.diagnosis3,
							poi.diagnosis4,
              poi.diagnosis5,
							poi.diagnosis6,
							poi.diagnosis7,
							poi.diagnosis8,
							poi.diagnosis9,
							poi.diagnosisA as diagnosis10,
							poi.diagnosisB as diagnosis11,
							poi.diagnosisC as diagnosis12
					from AMM_LIVE.dbo.pm_patient_other_info as poi with (nolock)
					left join AMM_LIVE.dbo.pm_patient as p with (nolock)
					  on p.practice_id = poi.practice_id
					  and p.guarantor_id = poi.guarantor_id
					  and p.patient_no = poi.patient_no
					  and p.database_name = poi.database_name
					left join AMM_LIVE.dbo.pm_employer as e with (nolock)
					  on poi.employer_id = e.employer_id
					  and poi.database_name = e.database_name
					left join AMM_LIVE.dbo.pm_employer as pw with (nolock)
					  on p.employer_id = pw.employer_id
					  and p.database_name = pw.database_name
					left join AMM_LIVE.dbo.pm_city as pnt_addr with (nolock)
					  on pnt_addr.zip_id = p.zip_id
					  and pnt_addr.database_name = p.database_name
					left join AMM_LIVE.dbo.pm_city as atty_addr with (nolock)
					  on atty_addr.zip_id = e.zip_id
					  and atty_addr.database_name = e.database_name 
					left join AMM_LIVE.dbo.pm_city as pnt_work_addr with (nolock)
					  on pnt_work_addr.zip_id = pw.zip_id
					  and pnt_work_addr.database_name = pw.database_name 
					left join AMM_LIVE.dbo.pm_practice as pr with (nolock)
					  on pr.practice_id = poi.practice_id
					  and pr.database_name = poi.database_name
					left join AMM_LIVE.dbo.pm_city as pc3 with (nolock)
					  on pc3.zip_id = pr.pay_to_zip_id
					  and pc3.database_name = pr.database_name 
					where  poi.practice_id = ?
					  and poi.guarantor_id = ?
					  and poi.patient_no = ?
					  and poi.database_name = ?
					  and poi.case_no = ?";
                }

                $headerNoDiagnosis = $this->getResultSet($queryType, $this->conns[$dbKey], str_replace('AMM_LIVE', $queryParams['conds']['db_name'], $ammLiveQuery), $ammLiveCondParams);

                if ($queryType == 'first') {
                    $diagnosisCondParams = array(
                        $queryParams['conds']['db_name'],
                    );

                    $diagnosisConds = array();
                    foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12') as $diagIndex) {
                        if (!empty($headerNoDiagnosis['diagnosis' . $diagIndex])) {
                            $diagnosisCondParams[] = $headerNoDiagnosis['diagnosis' . $diagIndex];
                            $diagnosisConds[] = '?';
                        }
                    }
                    $diagnosisCondsString = implode(',', $diagnosisConds);

                    $diagnosisQuery = "select  diagnosis_code + diagnosis_type as poi_code,
                                ISNULL(diag.ICD10code, diag.ICD9code) as pnt_diagnosis
                        from AMM_LIVE.dbo.pm_diagnosis as diag with (nolock)
                        where    database_name = ? 
                        and diagnosis_code + diagnosis_type in ($diagnosisCondsString)";
                    $diagnosisResults = $this->getResultSet('all', $this->conns[$dbKey], str_replace('AMM_LIVE', $queryParams['conds']['db_name'], $diagnosisQuery), $diagnosisCondParams);

                    foreach ($diagnosisResults as $diagnosisResult) {
                        $field = array_search($diagnosisResult['poi_code'], $headerNoDiagnosis);
                        $headerNoDiagnosis['pnt_' . $field] = $diagnosisResult['pnt_diagnosis'];
                    }


                    return $headerNoDiagnosis;
                } else {
                    foreach ($headerNoDiagnosis as $key => $headerNoDiagnos) {
                        $diagnosisCondParams = array(
                            $queryParams['conds']['db_name'],
                        );

                        $diagnosisConds = array();
                        foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12') as $diagIndex) {
                            if (!empty($headerNoDiagnos['diagnosis' . $diagIndex])) {
                                $diagnosisCondParams[] = $headerNoDiagnos['diagnosis' . $diagIndex];
                                $diagnosisConds[] = '?';
                            }
                        }
                        $diagnosisCondsString = implode(',', $diagnosisConds);

                        $diagnosisQuery = "select  diagnosis_code + diagnosis_type as poi_code,
                                ISNULL(diag.ICD10code, diag.ICD9code) as pnt_diagnosis
                        from AMM_LIVE.dbo.pm_diagnosis as diag with (nolock)
                        where    database_name = ? 
                        and diagnosis_code + diagnosis_type in ($diagnosisCondsString)";
                        $diagnosisResults = $this->getResultSet('all', $this->conns[$dbKey], str_replace('AMM_LIVE', $queryParams['conds']['db_name'], $diagnosisQuery), $diagnosisCondParams);

                        if ($diagnosisResults) {
                            foreach ($diagnosisResults as $diagnosisResult) {
                                $field = array_search($diagnosisResult['poi_code'], $headerNoDiagnos);
                                $headerNoDiagnosis[$key]['pnt_' . $field] = $diagnosisResult['pnt_diagnosis'];
                            }
                        }
                    }


                    return $headerNoDiagnosis;
                }
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'pnt_first_name' => 'CRYSTAL',
                        'pnt_middle_name' => 'L',
                        'pnt_last_name' => 'CURTIS JOHNSON',
                        'pnt_address1' => '1019 President Street',
                        'pnt_address2' => '',
                        'pnt_addr_city' => 'ANNAPOLIS',
                        'pnt_addr_state' => 'MD',
                        'pnt_addr_zip' => 21403,
                        'pnt_account' => 10124.0,
                        'pnt_case_num' => 'AA101508',
                        'pnt_diagnosis1' => 847.10,
                        'pnt_diagnosis2' => 847.20,
                        'pnt_diagnosis3' => '',
                        'pnt_diagnosis4' => '',
                        'pnt_work_name' => 'Faustin & Associates',
                        'pnt_work_address1' => '11510 Georgia Avenue',
                        'pnt_work_address2' => 'Suite 105',
                        'pnt_work_addr_city' => 'SILVER SPRING',
                        'pnt_work_addr_state' => 'MD',
                        'pnt_work_addr_zip' => 20902,
                        'atty_name' => 'Oconnor, Cliff',
                        'atty_address1' => '100 Central Avenue',
                        'atty_address2' => '',
                        'atty_addr_city' => 'GLEN BURNIE',
                        'atty_addr_state' => 'MD',
                        'atty_addr_zip' => 21061,
                        'practice_name' => 'Multi-Specialty Healthcare',
                        'practice_address1' => 'P O Box 1048',
                        'practice_address2' => '',
                        'practice_city' => 'COCKEYSVILLE',
                        'practice_state' => 'MD',
                        'practice_zip' => 21030,
                        'practice_tax_id' => 522104653
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getStatement($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );

                    if (isset($queryParams['conds']['trn_type'])) {
                        $condString = "where PaymentAmount is not null
                      		and Type = ?";
                        $condParams[] = $queryParams['conds']['trn_type'];
                    } else {
                        $condString = 'where PaymentAmount is not null';
                    }

                    if (isset($queryParams['conds']['pend_trns_to'])) {
                        $condString .= ' and ServiceDate <= ?';
                        $condParams[] = $queryParams['conds']['pend_trns_to'];
                    } else {
                        $maxServiceDate = $this->getMaxServiceDate(array(1), 'first');
                        $condString .= ' and ServiceDate < ?';
                        $condParams[] = $maxServiceDate['MaxServiceDate']->format('Y-m-d');
                    }

                    if (isset($queryParams['order'])) {
                        $resultSetParams['queryParams'] = $queryParams;
                        $resultSetParams['sortAndLimitQuery'] = TRUE;
                    } else {
                        $resultSetParams = array();
                    }

                    $query = "select  Company,
                        case when Company in ('MD', 'MDMD') then 'Medical' 
                              when Company in ('PT', 'MDPT') then 'PT/Chiro'
                              when Company = 'RX' then 'Pharmacy' 
                              else Company
                        end as UICompany,
                        OriginalChargeDate as ServiceDate, 
                        ServiceDate as TransDate, 
                        LocName, 
                        Provider, 
                        case
                          when Type = 'charge' then
                                  case when len(Modifier1) > 1 then 
                                    Code + ': ' + Modifier1 + ', ' + Description
                                  else 
                                    Code + ': ' + Description
                                  end
                          when Type = 'payment' then
                            case when len(Modifier1) > 1 then 
                                    'PYMT:' + Code + ': ' + Modifier1 
                                  else 
                                    'PYMT:' + Code
                                  end
                          when Type = 'adjustment' then
                            case when len(Modifier1) > 1 then 
                                    'ADJ:' + Code + ': ' + Modifier1
                                  else 
                                    'ADJ:' + Code
                                  end
                        end Service, 
                        PaymentAmount,
                        dkDBPracGuarPatSeqLine,
                        Type
					from PointsProcesses.MicroMD.get{$this->dbFncPrefix}Statement(?, ?,	?,	?, ?) as gs
					$condString";
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $resultSetParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'sequence_num' => 13,
                        'service_date' => (new DateTime('2012-03-12 00:00:00', new DateTimeZone('America/New_York'))),
                        'amount_unapplied' => -50.60
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getMaxServiceDate($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $query = "SELECT MIN(ds.day_sheet_date) - 1 as MaxServiceDate
							FROM AMM_LIVE.dbo.pm_day_sheet_info ds
							WHERE ds.database_name = 'amm_live'";
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            $rows = array(
                array(
                    'MaxServiceDate' => new DateTime()
                )
            );

            //print_r($rows); return;
            return $rows;
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getPatientIns($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $ammLiveCondParams = array(
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['patient'],
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['case_no'],
                    );
                    if (isset($queryParams['conds']['plan_type'])) {
                        $ammLiveCondParams[] = $queryParams['conds']['plan_type'];
                        $planTypeCondString = "and pp.plan_type = ?";
                    } else {
                        $planTypeCondString = "";
                    }
                    $condParams = array_merge(
                        $ammLiveCondParams,
                        $ammLiveCondParams,
                        $ammLiveCondParams,
                        $ammLiveCondParams,
                        $ammLiveCondParams
                    );


                    $ammLiveQuery = "SELECT		 pl.policy_group_name	 as InsuranceCompany,	
									pp.plan_policy  as PolicyNum,
									pp.plan_group as Adjuster
						   FROM AMM_LIVE.dbo.pm_patient_other_info poi
						   LEFT JOIN AMM_LIVE.dbo.pm_patient_plan_set as pps with (nolock)
							 ON	poi.database_name  = pps.database_name
							 and poi.practice_id	   = pps.practice_id
							 and poi.guarantor_id   = pps.guarantor_id
							 and poi.default_set_no = pps.set_no
						   LEFT JOIN AMM_LIVE.dbo.pm_patient_plan as pp with (nolock)
							 ON	pps.database_name = pp.database_name
							 and pps.practice_id            = pp.practice_id
							 and pps.guarantor_id        = pp.guarantor_id
							 and pps.patient_no           = pp.patient_no
							 and pps.set_no	          = pp.set_no
						   LEFT JOIN AMM_LIVE.dbo.pm_plan as pl with (nolock)
							 ON	         pp.database_name = pl.database_name
							 and pp.plan_id		  = pl.plan_id
						   where  poi.practice_id = ? 
							 and poi.guarantor_id = ?
							 and poi.patient_no = ?
							 and poi.database_name = ?
							 and poi.database_name = 'amm_live'
							 and poi.case_no = ?
							 $planTypeCondString";

                    $query = $ammLiveQuery .
                        ' union ' .
                        str_replace('AMM_LIVE', 'bwr', $ammLiveQuery) .
                        ' union ' .
                        str_replace('AMM_LIVE', 'md', $ammLiveQuery) .
                        ' union ' .
                        str_replace('AMM_LIVE', 'mri', $ammLiveQuery) .
                        ' union ' .
                        str_replace('AMM_LIVE', 'pt', $ammLiveQuery);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'InsuranceCompany' => 'Attorney',
                        'PolicyNum' => ''
                    ),
                    array(
                        'InsuranceCompany' => 'Maryland Auto Insurance Fund',
                        'PolicyNum' => 'T94574'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    function getVisits($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );

                    if (!empty($queryParams['conds']['last_post_date'])) {
                        $maxServiceDateCond = "<= ?";
                        $condParams[] = $queryParams['conds']['last_post_date'];
                    } else {
                        $maxServiceDateCond = "<= (SELECT MIN(ds.day_sheet_date) - 1 as MaxServiceDate FROM AMM_LIVE.dbo.pm_day_sheet_info ds WHERE ds.database_name = 'amm_live')";
                    }

                    $query = "select case when gs.IsCharge = 1 
                            then case when p.Name = 'Multi-Specialty HealthCare' and gs.Company = 'RX'
                                      then 'MED, LLC'
                                      else gs.Company
                                  end
                            else p.Name 
                        end as practice_company,
						  gs.ServiceDate as dov,
						  sum(gs.ChargeAmount + gs.AdjustmentChargeAmount) as charges,
						  sum(gs.PaymentAmount + gs.RefundAmount) as payments,
						  sum(gs.AdjustmentWriteOffAmount) as adjustments,
						  gs.IsCharge as is_charge,
						  gs.Company as company,
						  SequenceNo
				  from PointsProcesses.MicroMD.get{$this->dbFncPrefix}Summary(?,?,?,?,?) as gs
				  left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice as ploc
            on ploc.database_name = gs.DatabaseName   
            and ploc.cost_center_id = gs.CostCenterID
            and ploc.practice_id = gs.practiceID
				  left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}Practice as p 
            on p.practice_id = gs.PracticeID
            and ploc.PortalPracticeID = p.PortalPracticeId
          where gs.ServiceDate $maxServiceDateCond
				  group by p.Name, gs.Company, gs.ServiceDate, gs.IsCharge, SequenceNo
				  order by is_charge desc, practice_company, dov";
                    //			print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'company' => 'MRImages - MRI',
                        'dov' => (new DateTime('2012-03-12 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '289.00',
                        'payments' => '-289.00',
                        'adjustments' => '0.00',
                        'is_charge' => '1',
                        'SequenceNo' => '1'
                    ),
                    array(
                        'company' => 'Multi-Specialty HealthCare-MDMD',
                        'dov' => (new DateTime('2012-03-15 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '131.00',
                        'payments' => '-131.00',
                        'adjustments' => '0.00',
                        'is_charge' => '1',
                        'SequenceNo' => '3'
                    ),
                    array(
                        'company' => 'Multi-Specialty HealthCare-MDMD',
                        'dov' => (new DateTime('2012-03-18 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '163.00',
                        'payments' => '-163.00',
                        'adjustments' => '0.00',
                        'is_charge' => '1',
                        'SequenceNo' => '2'
                    ),
                    array(
                        'company' => 'Multi-Specialty HealthCare-MDMD',
                        'dov' => (new DateTime('2012-03-20 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '1997.00',
                        'payments' => '-732.00',
                        'adjustments' => '-1265.00',
                        'is_charge' => '1',
                        'SequenceNo' => '1'
                    ),
                    array(
                        'company' => 'Multi-Specialty HealthCare-MDPT',
                        'dov' => (new DateTime('2012-03-25 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '1915.00',
                        'payments' => '-1768.00',
                        'adjustments' => '0.00',
                        'is_charge' => '0',
                        'SequenceNo' => '0'
                    ),
                    array(
                        'company' => 'Multi-Specialty HealthCare-MDPT',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'charges' => '272.00',
                        'payments' => '-272.00',
                        'adjustments' => '0.00',
                        'is_charge' => '0',
                        'SequenceNo' => '2'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }


    //****************************************************************************
    function getVisitSummary($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient'],
                        $queryParams['conds']['company'],
                        $queryParams['conds']['dov'],
                        $queryParams['conds']['sequence']
                    );
                    $query = "select gs.Office as office, 
							gs.Provider as provider, 
							gs.ServiceDate as dov, 
							gs.Code as code_source, 
							gs.Description as description, 
							sum(gs.PaymentAmount) as amount,
							gs.Type as charge_type,
							gs.IsCharge
					from PointsProcesses.MicroMD.get{$this->dbFncPrefix}VisitSummary(?, ?, ?, ?, ?, ?, ?, ?) as gs
					group by  gs.Office, gs.Provider, gs.ServiceDate, gs.Code, gs.Description, gs.Type, gs.IsCharge
					order by IsCharge desc, dov";
                    //			print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'office' => 'Glen Burnie-MSHC',
                        'provider' => 'Tymchenko, Paul',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'code_source' => 97010,
                        'description' => 'APPLICATION OF MODALITY HOT/COLD PACKS',
                        'amount' => 29.00,
                        'charge_type' => 'charge'
                    ),
                    array(
                        'office' => 'Glen Burnie-MSHC',
                        'provider' => 'Tymchenko, Paul',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'code_source' => 97014,
                        'description' => 'ELECTRICAL STIMULATION UNATTENDED',
                        'amount' => 38.00,
                        'charge_type' => 'charge'
                    ),
                    array(
                        'office' => 'Glen Burnie-MSHC',
                        'provider' => 'Tymchenko, Paul',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'code_source' => 97110,
                        'description' => 'THERAPEUTIC PROCEDURE-STRENGTH ENDURANCE',
                        'amount' => 70.00,
                        'charge_type' => 'charge'
                    ),
                    array(
                        'office' => 'Glen Burnie-MSHC',
                        'provider' => 'Tymchenko, Paul',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'code_source' => 98940,
                        'description' => 'CHIROPRACTIC MANIPULATION TREATMENT',
                        'amount' => 52.00,
                        'charge_type' => 'charge'
                    ),
                    array(
                        'office' => '',
                        'provider' => '',
                        'dov' => (new DateTime('2012-03-30 00:00:00', new DateTimeZone('America/New_York'))),
                        'code_source' => 'Allstate',
                        'description' => 'PAYMENT PIP',
                        'amount' => -189.00,
                        'charge_type' => 'payment'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function manageAppointmentReason($dbs, $queryType = 'insert', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update', 'delete', 'all'))) {
                $this->error = 'Wrong type of query (must be insert,update,delete)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);
                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['practice'],
                            $queryParams['values']['sys_reason'],
                            $queryParams['values']['ui_reason']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}ApptReason  
							  values (?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array(
                            $queryParams['values']['practice'],
                            $queryParams['values']['sys_reason'],
                            $queryParams['values']['ui_reason'],
                            $queryParams['values']['id']
                        );
                        $query = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}ApptReason  
							  set PracticeId = ?,
							   PMSReason = ?,
							   AMMReason = ?
							  where  MappingId  = ?";
                    } elseif ($queryType == 'delete') {
                        $valueParams = array(
                            $queryParams['values']['id']
                        );
                        $query = "delete 
						from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}ApptReason  
						where  MappingId  = ?";
                    } elseif ($queryType == 'all') {
                        $valueParams = array(
                            $queryParams['practice']
                        );
                        $query = "select * 
                		from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}ApptReason 
						where PracticeId = ? 
						order by PMSReason";
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getAppointmentReasons($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $query = "select distinct(PMSReason) 
                from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}ApptReason 
				order by PMSReason";
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'sample') {
                return array(
                    1 => array(
                        'PMSReason' => '1st PT Vis'
                    ),
                    3 => array(
                        'PMSReason' => '2nd Opinio'
                    ),
                    4 => array(
                        'PMSReason' => 'C/L FBlock'
                    ),
                    5 => array(
                        'PMSReason' => 'C/L RFA'
                    ),
                );
            }
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function managePractice($dbs, $queryType, $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['portal_practice_id'],
                            $queryParams['values']['name'],
                            $queryParams['values']['practice_id']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}Practice  
					  values (?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array();
                        $queryValuesSets = '';
                        if (isset($queryParams['values']['portal_practice_val'])) {
                            $valueParams[] = $queryParams['values']['portal_practice_val'];
                            $queryValuesSets .= 'PortalPracticeId = ?,';
                        }
                        if (isset($queryParams['values']['name'])) {
                            $valueParams[] = $queryParams['values']['name'];
                            $queryValuesSets .= 'Name = ?,';
                        }
                        if (isset($queryParams['values']['practice_id'])) {
                            $valueParams[] = $queryParams['values']['practice_id'];
                            $queryValuesSets .= 'practice_id = ?';
                        }
                        $valueParams[] = $queryParams['values']['portal_practice_id']; // for where condition at the end

                        $queryStart = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}Practice set ";
                        $queryEnd = "where  PortalPracticeId  = ?";
                        $query = $queryStart . ' ' . rtrim($queryValuesSets, ',') . ' ' . $queryEnd;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getLocationNames($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array();
                    $condStrings = array();

                    if (isset($queryParams['group'])) {
                        $fieldString = implode(',', $queryParams['group']);
                        $groupByString = 'group by ' . $fieldString;
                    } else {
                        $fieldString = '*';
                        $groupByString = '';
                    }

                    $dbPlaceHolders = array();
                    foreach ($dbs as $db) {
                        $condParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $dbPlaceHolders[] = '?';
                    }
                    $condStrings[] = 'database_name in (' . implode(',', $dbPlaceHolders) . ')';

                    if (isset($queryParams['conds'])) {
                        if (isset($queryParams['conds']['is_active'])) {
                            $condStrings[] = 'is_active = ?';
                            $condParams[] = $queryParams['conds']['is_active'];
                        }
                        if (isset($queryParams['conds']['display_name'])) {
                            $condStrings[] = 'display_name = ?';
                            $condParams[] = $queryParams['conds']['display_name'];
                        }
                    }

                    $condString = implode(' and ', $condStrings);

                    $query = "select $fieldString
						from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations
						where $condString 
            $groupByString
						order by display_name asc
						";
                    //print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array();
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    /**
     * @param $dbs
     * @param $queryType
     * @param array $queryParams
     * @return array|bool|false|null
     */
    public function manageLocationPractice($dbs, $queryType, $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update', 'delete', 'all'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['database_name'],
                            $queryParams['values']['practice_id'],
                            $queryParams['values']['cost_center_id'],
                            $queryParams['values']['portal_practice_id']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice values (?, ?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array();
                        $queryValuesSets = '';
                        if (isset($queryParams['values']['database_name'])) {
                            $valueParams[] = $queryParams['values']['database_name'];
                            $queryValuesSets .= 'database_name = ?,';
                        }
                        if (isset($queryParams['values']['cost_center_id'])) {
                            $valueParams[] = $queryParams['values']['cost_center_id'];
                            $queryValuesSets .= 'cost_center_id = ?,';
                        }
                        if (isset($queryParams['values']['practice_id'])) {
                            $valueParams[] = $queryParams['values']['practice_id'];
                            $queryValuesSets .= 'practice_id = ?,';
                        }
                        if (isset($queryParams['values']['portal_practice_id'])) {
                            $valueParams[] = $queryParams['values']['portal_practice_id'];
                            $queryValuesSets .= 'PortalPracticeID = ?,';
                        }
                        $valueParams[] = $queryParams['values']['id']; // for where condition at the end
                        $queryStart = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice set ";
                        $queryEnd = "where  id  = ?";
                        $query = $queryStart . ' ' . rtrim($queryValuesSets, ',') . ' ' . $queryEnd;
                    } elseif ($queryType == 'delete') {
                        $valueParams = array(
                            $queryParams['values']['id']
                        );
                        $query = "delete 
							from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice  
							where  id  = ?";
                    } elseif ($queryType == 'all') {
                        $valueParams = array(
                            $queryParams['portal_practice_id']
                        );

                        $query = "select lp.id as map_id, * 
						from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}LocationPractice as lp
						join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations as l 
              on l.cost_center_id = lp.cost_center_id 
						and upper(l.database_name) = upper(lp.database_name)
						where PortalPracticeID = ? order by l.display_name";
                    }

                    return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
                } else {
                    return false;
                }
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function manageCaseCategories($dbs, $queryType, $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['database_name'],
                            $queryParams['values']['category_code'],
                            $queryParams['values']['sys_name'],
                            $queryParams['values']['ui_name']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDCaseCategories  
					  values (?, ?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array();
                        $queryValuesSets = '';
                        if (isset($queryParams['values']['database_name'])) {
                            $valueParams[] = $queryParams['values']['database_name'];
                            $queryValuesSets .= 'database_name = ?,';
                        }
                        if (isset($queryParams['values']['category_code'])) {
                            $valueParams[] = $queryParams['values']['category_code'];
                            $queryValuesSets .= 'category_code = ?,';
                        }
                        if (isset($queryParams['values']['sys_name'])) {
                            $valueParams[] = $queryParams['values']['sys_name'];
                            $queryValuesSets .= 'db_name = ?,';
                        }
                        if (isset($queryParams['values']['ui_name'])) {
                            $valueParams[] = $queryParams['values']['ui_name'];
                            $queryValuesSets .= 'attorney_name = ?,';
                        }
                        $valueParams[] = $queryParams['values']['database_name']; // for where condition at the end, twice
                        $valueParams[] = $queryParams['values']['category_code'];

                        $queryStart = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDCaseCategories set ";
                        $queryEnd = "where  database_name  = ? and category_code = ?";
                        $query = $queryStart . ' ' . rtrim($queryValuesSets, ',') . ' ' . $queryEnd;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function manageLocations($dbs, $queryType, $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['database_name'],
                            $queryParams['values']['cost_center_id'],
                            $queryParams['values']['sys_name'],
                            $queryParams['values']['ui_name']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations  
					  values (?, ?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array();
                        $queryValuesSets = '';
                        if (isset($queryParams['values']['database_name'])) {
                            $valueParams[] = $queryParams['values']['database_name'];
                            $queryValuesSets .= 'database_name = ?,';
                        }
                        if (isset($queryParams['values']['cost_center_id'])) {
                            $valueParams[] = $queryParams['values']['cost_center_id'];
                            $queryValuesSets .= 'cost_center_id = ?,';
                        }
                        if (isset($queryParams['values']['sys_name'])) {
                            $valueParams[] = $queryParams['values']['sys_name'];
                            $queryValuesSets .= 'system_name = ?,';
                        }
                        if (isset($queryParams['values']['ui_name'])) {
                            $valueParams[] = $queryParams['values']['ui_name'];
                            $queryValuesSets .= 'display_name = ?,';
                        }
                        $valueParams[] = $queryParams['values']['database_name']; // for where condition at the end, twice
                        $valueParams[] = $queryParams['values']['cost_center_id'];

                        $queryStart = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations set ";
                        $queryEnd = "where  database_name  = ? and cost_center_id = ?";
                        $query = $queryStart . ' ' . rtrim($queryValuesSets, ',') . ' ' . $queryEnd;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function managePaymentType($dbs, $queryType, $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['pos_payment_type_id'],
                            $queryParams['values']['pos'],
                            $queryParams['values']['payment_type_id']
                        );
                        $query = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}POSPaymentType  
					  values (?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        $valueParams = array();
                        $queryValuesSets = '';
                        if (isset($queryParams['values']['pos_payment_type_val'])) {
                            $valueParams[] = $queryParams['values']['pos_payment_type_val'];
                            $queryValuesSets .= 'POSPaymentTypeID = ?,';
                        }
                        if (isset($queryParams['values']['pos'])) {
                            $valueParams[] = $queryParams['values']['pos'];
                            $queryValuesSets .= 'pos = ?,';
                        }
                        if (isset($queryParams['values']['payment_type_id'])) {
                            $valueParams[] = $queryParams['values']['payment_type_id'];
                            $queryValuesSets .= 'PaymentTypeID = ?,';
                        }
                        $valueParams[] = $queryParams['values']['pos_payment_type_id']; // for where condition at the end

                        $queryStart = "update PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}POSPaymentType set ";
                        $queryEnd = "where  POSPaymentTypeID  = ?";
                        $query = $queryStart . ' ' . rtrim($queryValuesSets, ',') . ' ' . $queryEnd;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getAppointments($dbs, $queryType = 'all', $queryParams = array())
    { // print_r($queryParams); exit();
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );

                    // aditional conditions
                    $extraConds = array();
                    //date
                    if (isset($queryParams['conds']['date'])) {
                        if (isset($queryParams['conds']['date_oper'])) {
                            if (strtoupper($queryParams['conds']['date_oper']) == 'BETWEEN') {
                                $condParams[] = $queryParams['conds']['date']['value'][0];
                                $condParams[] = $queryParams['conds']['date']['value'][1];
                                $extraConds[] = 'appnt.appt_date between ? and ?';
                            } elseif ($queryParams['conds']['date_oper'] == '>') {
                                $condParams[] = $queryParams['conds']['date']['value'];
                                $extraConds[] = 'appnt.appt_date > ?';
                            } elseif ($queryParams['conds']['date_oper'] == '<') {
                                $condParams[] = $queryParams['conds']['date']['value'];
                                $extraConds[] = 'appnt.appt_date < ?';
                            } else {
                                $condParams[] = $queryParams['conds']['date'];
                                $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], 'appnt.appt_date', 'date_oper', 'date');
                            }
                        } else {
                            $condParams[] = $queryParams['conds']['date'];
                            $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], 'appnt.appt_date', 'date_oper', 'date');
                        }
                    }
                    // provider
                    if (isset($queryParams['conds']['provider'])) {
                        if (isset($queryParams['conds']['provider_oper']) and $queryParams['conds']['provider_oper'] != 'like') {
                            $condParams[] = $queryParams['conds']['provider'];
                        } else {
                            $condParams[] = "%" . $queryParams['conds']['provider'] . "%";
                        }
                        $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], "appnt.last_name + ', ' + appnt.first_name", 'provider_oper');
                    }
                    // reason
                    if (isset($queryParams['conds']['reason'])) {
                        if (isset($queryParams['conds']['reason_oper']) and $queryParams['conds']['reason_oper'] != 'like') {
                            $condParams[] = $queryParams['conds']['reason'];
                        } else {
                            $condParams[] = "%" . $queryParams['conds']['reason'] . "%";
                        }
                        $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], "case when AMMReason is null then ApptClassDesc else AMMReason end", 'reason_oper');
                    }
                    // location
                    if (isset($queryParams['conds']['location'])) {
                        if (isset($queryParams['conds']['loc_oper']) and $queryParams['conds']['loc_oper'] != 'like') {
                            $condParams[] = $queryParams['conds']['location'];
                        } else {
                            $condParams[] = "%" . $queryParams['conds']['location'] . "%";
                        }
                        $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], "case when loc.display_name is null then loc.system_name else loc.display_name end", 'loc_oper');
                    }
                    // status
                    if (isset($queryParams['conds']['status'])) {
                        if (isset($queryParams['conds']['status_oper']) and $queryParams['conds']['status_oper'] != 'like') {
                            $condParams[] = $queryParams['conds']['status'];
                        } else {
                            $condParams[] = "%" . $queryParams['conds']['status'] . "%";
                        }
                        $extraConds[] = $this->getCondStringWithOperator($queryParams['conds'], "appnt.status", 'status_oper');
//			print_r($extraConds);
                    }

                    $extraCondString = '';
                    if (!empty($extraConds)) {
                        $extraCondString = ' and ' . implode(' and ', $extraConds);
                    }

                    $query = "select appnt.appt_date as date,
							appnt.appt_time as time,
							appnt.last_name + ', ' + appnt.first_name as provider,
							case when AMMReason is null then ApptClassDesc else AMMReason end as reason,
							case when loc.display_name is null then loc.system_name else loc.display_name end as location,
							appnt.status,
							loc.street_address1,
							loc.street_address2,
							loc.city,
							loc.state,
							loc.zip_code
					from PointsProcesses.MicroMD.get{$this->dbFncPrefix}Appointments(?,?,?,?,?) as appnt
					left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations as loc 
					  on loc.database_name = appnt.database_name
					  and loc.cost_center_id = appnt.cost_center_id
					where appnt.status in ('Active/Kept', 'Missed', 'Cancelled', 'Rescheduled')" .
                        $extraCondString;

                    $params = array();
                    if (isset($queryParams['limit']) or isset($queryParams['order'])) {
                        $params['sortAndLimitQuery'] = TRUE;
                        $params['queryParams'] = $queryParams;
                    }
                }
                //print_r($params);
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'date' => (new DateTime('2012-03-01 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-01 10:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Haroun, Naji',
                        'reason' => 'New Patient',
                        'location' => 'Name of Location',
                        'status' => 'Active/Kept',
                        'street_address1' => '7138 Ritchie Highway',
                        'street_address2' => '',
                        'city' => 'Glen Burnie',
                        'state' => 'MD',
                        'zip_code' => '21061'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-03 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-03 15:15:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Sampson, Jahan',
                        'reason' => '1st PT Visit',
                        'location' => 'Name of Location',
                        'status' => 'Missed',
                        'street_address1' => '7138 Ritchie Highway',
                        'street_address2' => '',
                        'city' => 'Glen Burnie',
                        'state' => 'MD',
                        'zip_code' => '21061'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-05 14:45:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Willam, Lauson',
                        'reason' => 'XRAY ONLY',
                        'location' => 'Name of Location',
                        'status' => 'Canceled',
                        'street_address1' => '7138 Ritchie Highway',
                        'street_address2' => '',
                        'city' => 'Glen Burnie',
                        'state' => 'MD',
                        'zip_code' => '21061'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-06 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-06 10:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Willam, Lauson',
                        'reason' => 'PT Visit',
                        'location' => 'Name of Location',
                        'status' => 'Active/Kept',
                        'street_address1' => '7138 Ritchie Highway',
                        'street_address2' => '',
                        'city' => 'Glen Burnie',
                        'state' => 'MD',
                        'zip_code' => '21061'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-08 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-08 08:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Sampson, Jahan',
                        'reason' => 'Medical Visit',
                        'location' => 'Name of Location',
                        'status' => 'Missed',
                        'street_address1' => '7138 Ritchie Highway',
                        'street_address2' => '',
                        'city' => 'Glen Burnie',
                        'state' => 'MD',
                        'zip_code' => '21061'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getCronAppointments($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['apmnt_time'][0],
                        $queryParams['conds']['apmnt_time'][1],
                    );

                    // aditional conditions
                    $extraConds = array();
                    // status
                    if (isset($queryParams['conds']['status'])) {
                        if (is_array($queryParams['conds']['status'])) {
                            $condParams = array_merge($condParams, $queryParams['conds']['status']);
                            $qmArray = array(); // question mare array
                            foreach ($queryParams['conds']['status'] as $statusCond) {
                                $qmArray[] = '?';
                            }
                            $extraConds[] = 'appnt.status in (' . implode(',', $qmArray) . ')';
                        } else {
                            $condParams[] = $queryParams['conds']['status'];
                            $extraConds[] = 'appnt.status = ?';
                        }
                    }

                    $extraCondString = '';
                    if (!empty($extraConds)) {
                        $extraCondString = ' and ' . implode(' and ', $extraConds);
                    }

                    $query = "select appnt.database_name,
							appnt.practice_id,
							appnt.guarantor_id,
							appnt.patient_no,
							appnt.case_no,
							appnt.ApptClassID,
							appnt.cost_center_id,
							appnt.PortalPracticeID,
							appnt.PortalPracticeName,
							appnt.appt_date,
							appnt.appt_time,
							appnt.last_name as doc_last_name,
							appnt.first_name as doc_first_name,
							appnt.status,
							appnt.employer_id,
							case when appnt.AMMReason is null then appnt.ApptClassDesc else appnt.AMMReason end as reason,
							case when loc.display_name is null then loc.system_name else loc.display_name end as location,
							appnt.status,
							p.first_name as pnt_first_name,
							p.last_name as pnt_last_name
					from PointsProcesses.MicroMD.get{$this->dbFncPrefix}AppointmentsForTimeRange(?, ?) as appnt
					left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDLocations as loc 
					  on loc.database_name = appnt.database_name
					  and loc.cost_center_id = appnt.cost_center_id
					left join AMM_LIVE.dbo.pm_patient as p
					  on p.database_name = appnt.database_name
					  and p.practice_id = appnt.practice_id
					  and p.guarantor_id = appnt.guarantor_id
					  and p.patient_no = appnt.patient_no
					where appnt.employer_id is not null " .
                        $extraCondString;

                    $params = array();
                    if (isset($queryParams['order'])) {
                        $params['sortAndLimitQuery'] = TRUE;
                        $params['queryParams'] = $queryParams;
                    }
                }


                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'date' => (new DateTime('2012-03-01 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-01 10:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Haroun, Naji',
                        'reason' => 'New Patient',
                        'location' => 'Name of Location',
                        'status' => 'Active/Kept'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-03 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-03 15:15:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Sampson, Jahan',
                        'reason' => '1st PT Visit',
                        'location' => 'Name of Location',
                        'status' => 'Missed'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-05 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-05 14:45:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Willam, Lauson',
                        'reason' => 'XRAY ONLY',
                        'location' => 'Name of Location',
                        'status' => 'Canceled'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-06 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-06 10:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Willam, Lauson',
                        'reason' => 'PT Visit',
                        'location' => 'Name of Location',
                        'status' => 'Active/Kept'
                    ),
                    array(
                        'date' => (new DateTime('2012-03-08 00:00:00', new DateTimeZone('America/New_York'))),
                        'time' => (new DateTime('2012-03-08 08:30:00', new DateTimeZone('America/New_York'))),
                        'provider' => 'Sampson, Jahan',
                        'reason' => 'Medical Visit',
                        'location' => 'Name of Location',
                        'status' => 'Missed'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getCronHighCharge($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    if (isset($queryParams['conds'])) {
                        if (isset($queryParams['conds']['attorney_id'])) {
                            $insertID = uniqid();
                            $insertQueryStart = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempDbsAttys 
							  (insert_id, db_name, atty_id) 
							  values ";

                            $insertQueryValueStrings = array(); // query string with all the 
                            $insertQueryValueValues = array(); // the real values for value part of the query
                            foreach ($queryParams['conds']['attorney_id']['value'] as $dbAttyPair) {
                                $insertQueryValueStrings[] = "(?, ?, ?)";
                                $insertQueryValueValues[] = $insertID;
                                $insertQueryValueValues[] = $dbAttyPair['database'];
                                $insertQueryValueValues[] = $dbAttyPair['attorney_id'];
                            }
                            $insertQuery = $insertQueryStart . implode(',', $insertQueryValueStrings);

                            if ($this->getResultSet('insert', $this->conns[$dbKey], $insertQuery, $insertQueryValueValues) !== TRUE) {
                                $this->error = 'Could not save database and attorney pairs';
                                return FALSE;
                            }

                            $query = "select UniqueCaseID,
                          FirstName as first_name,
                          LastName as last_name,
                          DOA as accident_date,
                          DOB as birth_date,
                          sum(Fee) as grand_total
                  from PointsProcesses.MicroMD.get{$this->dbFncPrefix}PatientCasesBalances(?) 
                  group by UniqueCaseID, 
                            FirstName, 
                            LastName,
                            DOA,
                            DOB";
                            $results = $this->getResultSet($queryType, $this->conns[$dbKey], $query, array($insertID));


                            // clean up, last thing to do 
                            $this->cleanuptblTempDbsAttys($insertID, $dbKey);

                            return $results;
                        } else {
                            $this->error = 'For this type of query conditions are required to not choke the system';
                            return false;
                        }
                    } else {
                        $this->error = 'For this type of query conditions are required to not choke the system';
                        return false;
                    }

                }

                return $this->getResultSet($queryType, $this->conns[$dbKey], $query);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array();
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    private function getCondStringWithOperator($conds, $condField, $operatorKey, $fieldType = 'string')
    {
        $operatorString = 'like';
        if ($fieldType == 'date') {
            $operatorString = '=';
        }
        if (isset($conds[$operatorKey])) {
            $operatorString = $conds[$operatorKey];
        }

        return $condField . " " . $operatorString . " ?";
    }


    //****************************************************************************
    public function getApptStatus($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['case_no'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['patient']
                    );
                    $query = "select case when t1.all_appts = 0 then null else t1.good_appts/t1.all_appts end as appt_status
					from  (select sum(case when status = 'Active/Kept' then 1.0 else 0.0 end) as good_appts,
								  sum(case when status in ('Active/Kept', 'Missed', 'Cancelled') then 1.0 else 0.0 end) as all_appts
						  from PointsProcesses.MicroMD.get{$this->dbFncPrefix}Appointments(?,?,?,?,?) 
						  where status in ('Active/Kept', 'Missed', 'Cancelled')
						  ) as t1";
                    //			print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                return array(0 => array('appt_status' => .952918));
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    public function getDocuments($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);
                if ($dbKey == self::microMD) {
                    $condParams = array( // init cond
                        $queryParams['conds']['db_name'],
                        $queryParams['conds']['practice'],
                        $queryParams['conds']['account'],
                        $queryParams['conds']['patient'],
                        $queryParams['conds']['case_no'],
                    );

                    // company forumula
                    $company = "CASE WHEN poi.database_name = 'MD'
                                    THEN CASE WHEN th.cost_center IN (10,11) 
                                                   THEN 'MRI'
                                               ELSE 'MD'
                                          END
                               WHEN poi.database_name <> 'AMM_LIVE'
                                  THEN poi.database_name
                               WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI'
                               WHEN poi.practice_id = 1 and th.cost_center = 23
                                  THEN 'BWR'
                               WHEN poi.practice_id = 1 and pm_proc.financial_class = 8
                                  THEN 'RX'
                               WHEN poi.practice_id = 1 and th.cost_center IN (25,35)
                                  THEN 'MRI'
                               WHEN poi.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
                                  THEN 'MD'           
                               WHEN poi.practice_id = 1 and pm_proc.financial_class = 3       
                                  THEN 'PT'
                               ELSE 'PT'
                          END";

                    // company
                    $companyCond = '';
                    if (isset($queryParams['conds']['company'])) {
                        if (is_array($queryParams['conds']['company'])) {
                            $condParams = array_merge($condParams, $queryParams['conds']['company']);
                            foreach ($queryParams['conds']['company'] as $companyCondVal) {
                                $companyPlaceholders[] = '?';
                            }
                            $companyCond = 'and (' . $company . ') in (' . implode(',', $companyPlaceholders) . ')';
                        } else {
                            $condParams[] = $queryParams['conds']['company'];
                            $companyCond = 'and (' . $company . ') = ?';
                        }
                    }

                    // doc types cond
                    if (isset($queryParams['conds']['doc_types'])) {
                        $placeHolderArray = array();
                        foreach ($queryParams['conds']['doc_types'] as $typeCond) {
                            $placeHolderArray[] = '?';
                        }
                        $docTypePlaceholder = implode(',', $placeHolderArray);
                        $condParams = array_merge($condParams, $queryParams['conds']['doc_types']);
                    } else {
                        $docTypePlaceholder = '?, ?, ?, ?, ?, ?, ?, ?, ?, ?';
                        $condParams[] = 'Medical Report';
                        $condParams[] = 'MRI Report';
                        $condParams[] = 'Prog Note';
                        $condParams[] = 'Disability';
                        $condParams[] = 'PT-BWR Referral';
                        $condParams[] = 'Consult';
                        $condParams[] = 'OS MED REC';
                        $condParams[] = 'Rx Req';
                        $condParams[] = 'NTI Report';
                        $condParams[] = 'Letter';
                    }

                    // account conds
                    $condParams[] = $queryParams['conds']['practice'];
                    $condParams[] = $queryParams['conds']['account'];
                    $condParams[] = $queryParams['conds']['patient'];

                    $docIdCond = ''; // doc ID cond
                    if (isset($queryParams['conds']['doc_id'])) {
                        $docIdCond = ' and doc.LDocID_ = ? ';
                        $condParams[] = $queryParams['conds']['doc_id'];
                    }

                    // page ID cond
                    if (isset($queryParams['conds']['page_id'])) {
                        $condParams[] = $queryParams['conds']['page_id'];
                        $docIdCond .= 'and Pg.lPAGEID = ? ';
                    }

                    $backslash = "\\";
                    $docstarSharePath = "'" . $backslash . $backslash . "Docstar" . $backslash . "'";
                    $query = "select  id,
							date_of_service,
							document_type,
							case when DS_MMD_cross.lPAGEID is not null 
									then document_name + ', page ' + cast(ROW_NUMBER() OVER (PARTITION BY id  ORDER BY date_of_service, id, lPAGEID, document_name) as varchar(128))
								 else 
									document_name
							 end as document_name,
							full_path,
							FileType, 
							lPAGEID,
							 AcctNum,
               Company
							from (SELECT QueryDS.lDOCID_ as id,
								  QueryDS.DateOfService as date_of_service,
								  QueryDS.doc_type as document_type,
								  QueryDS.document as document_name,
								  replace(replace(case when QueryDS.FileType is not null 
                                            then QueryDS.sPATH_ + '\N' + cast(QueryDS.lDOCID_ as varchar(128)) + QueryDS.FileType
                                        when  QueryDS.FileType is null 
                                            then case when QueryDS.nType_  in (0,1,2)
                                                          then CASE WHEN QueryDS.nIMAGETYPE = 2
                                                                        THEN QueryDS.sPATH_ + '\' + right('00000000' + cast(QueryDS.lPageID as varchar(128)), 8) + '.pdf'
                                                                    ELSE 
                                                                        QueryDS.sPATH_ + '\' + right('00000000' + cast(QueryDS.lPageID as varchar(128)), 8) + '.tif'
                                                                END
                                                      else
                                                          QueryDS.sPATH_ + '\N' + cast(QueryDS.lDOCID_ as varchar(128)) + '.pdf'
                                                  end
                                  end, 'D:\docs', 'W:'), 'C:', 'Z:') as full_path,
								  QueryDS.FileType, 
								  QueryDS.lPAGEID,
								   QueryDS.AcctNum,
						Company
							FROM (SELECT cast(poi.practice_id as varchar(128)) + '.' + cast(poi.guarantor_id as varchar(128)) + '.' + cast(poi.patient_no as varchar(128))   as AcctNum,               
										injury_date as DateOfAccident,
										th.service_date_from as DateOfService,
							$company as Company
								FROM AMM_LIVE.dbo.pm_patient_other_info as poi with (nolock)
								left join AMM_LIVE.dbo.pm_transaction_header as th with (nolock)
								  on poi.database_name = th.database_name
								  and poi.practice_id = th.practice_id
								  and poi.guarantor_id = th.guarantor_id
								  and poi.patient_no = th.patient_no
								  and poi.case_no = th.case_no
                left join AMM_LIVE.dbo.pm_TRANSACTION as t with (nolock)
                  on t.practice_id = poi.practice_id 
                  and t.guarantor_id = poi.guarantor_id 
                  and t.patient_no = poi.patient_no 
                  and t.sequence_no = th.sequence_no 
                  and t.database_name = th.database_name 
                left join AMM_LIVE.dbo.pm_procedure as pm_proc with (nolock)
                  on (pm_proc.practice_id = t.practice_id or pm_proc.practice_id = 9999) 
                  and pm_proc.procedure_code = t.procedure_code 
                  and pm_proc.database_name = poi.database_name  
								WHERE poi.database_name = ?
								and poi.practice_id = ? 
								and poi.guarantor_id = ?
								and poi.patient_no = ?
								and poi.case_no = ? 
                $companyCond) as QueryMMD
						  JOIN (SELECT doc.LDocID_,     
										Acc.sValue as AcctNum,     
										DSDOA.dtValue as DateOfAccident,
										DSDOS.dtValue as DateOfService,
										case when DSDocType.sVALUE = 'PROG NOTE' then 'PT Note' else DSDocType.sVALUE end as doc_type,
										doc.sTITLE as document,
										Ft.FileType,
										Pg.lPAGEID,
										doc.sPATH_,
										doc.nTYPE_,
                    Pg.nIMAGETYPE
								from DOCSTAR.DOCSTAR.DSUSER.tblDOCUMENT as doc with (nolock)
								left join DOCSTAR.DOCSTAR.DSUSER.tblFILETYPES as Ft with (nolock)
								  on Doc.nTYPE_ = Ft.DocType
								left join DOCSTAR.DOCSTAR.DSUSER.tblPAGE as Pg with (nolock)
								  on Doc.lDOCID_ = Pg.lDOCID
								left join DOCSTAR.DOCSTAR.DSUSER.tblCFD_ACCTNO as Acc with (nolock)
								  on Doc.lDOCID_ = Acc.lDOCID
								LEFT JOIN DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOA as DSDOA with (nolock)
								  ON DSDOA.LDocID = doc.LDocID_   
								LEFT JOIN DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOCTYPE as DSDocType with (nolock)
								  ON DSDocType.LDocID = doc.LDocID_ 
								left join DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOS as DSDOS with (nolock)
												on        DSDOS.LDocID = doc.LDocID_   
								WHERE DSDocType.sValue in ($docTypePlaceholder)
                and (doc.bDeleted_ is null or doc.bDeleted_ != -1)
								and Acc.sValue = cast(? as varchar(128)) + '.' + cast(? as varchar(128)) + '.' + cast(? as varchar(128)) 
						$docIdCond
						  ) as QueryDS
							ON  QueryMMD.AcctNum = QueryDS.AcctNum
							and (
					       (QueryMMD.DateOfAccident = QueryDS.DateOfAccident and QueryMMD.DateOfService = QueryDS.DateofService and QueryMMD.DateOfAccident is not null and QueryDS.DateOfAccident is not null and QueryMMD.DateOfService is not null and QueryDS.DateofService is not null)
					       or (QueryMMD.DateOfAccident = QueryDS.DateOfAccident  and QueryMMD.DateOfAccident is not null and QueryDS.DateOfAccident is not null and (QueryMMD.DateOfService is null or QueryDS.DateofService is null))
					       or (QueryMMD.DateOfService = QueryDS.DateofService and (QueryMMD.DateOfAccident is null or QueryDS.DateOfAccident is null) and QueryMMD.DateOfService is not null and QueryDS.DateofService is not null)
					     ) 
						group by QueryDS.lDOCID_,
							QueryDS.DateOfService,
							QueryDS.doc_type,
							QueryDS.document,
							FileType, 
							lPAGEID,
							 QueryDS.AcctNum,
							 QueryDS.sPATH_,
							 QueryDS.nTYPE_,
               QueryDS.nIMAGETYPE,
					Company
					) as DS_MMD_cross";

                    $params = array();

                    if (isset($queryParams['order'])) {
                        $params['sortAndLimitQuery'] = TRUE;
                        $params['queryParams'] = $queryParams;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'id' => '1',
                        'date_of_service' => (new DateTime('2012-03-01 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Disability',
                        'document_name' => '1.42105.0 ALICE ACCIDENT AA022511 OS Me, page 2',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\02_Zyuzin-Dogovor-Akt.doc',
                        'FileType' => 'pdf',
                        'lPAGEID' => '122',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '1',
                        'date_of_service' => (new DateTime('2012-03-02 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'PT_BWR Referral',
                        'document_name' => '1.42105.0 ALICE ACCIDENT AA022511 OS Me, page 1',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\01_dashboard_case_manager.tif',
                        'FileType' => 'pdf',
                        'lPAGEID' => '121',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '1',
                        'date_of_service' => (new DateTime('2012-03-13 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => '1.42105.0 ALICE ACCIDENT AA022511 OS Me, page 3',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\03_dashboard_attorney.tif',
                        'FileType' => 'tif',
                        'lPAGEID' => '123',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '2',
                        'date_of_service' => (new DateTime('2012-03-20 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'KEVIN SHIELDS AA0131210 OS MBV',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '3',
                        'date_of_service' => (new DateTime('2012-02-11 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'ROBERT SMITH AA022511 OS Me, page 2',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\02_index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '232',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '3',
                        'date_of_service' => (new DateTime('2012-02-11 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'ROBERT SMITH AA022511 OS Me, page 3',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\03_index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '',
                        'AcctNum' => '233'
                    ),
                    array(
                        'id' => '3',
                        'date_of_service' => (new DateTime('2012-02-11 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'ROBERT SMITH AA022511 OS Me, page 1',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\01_index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '231',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '3',
                        'date_of_service' => (new DateTime('2012-02-11 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'ROBERT SMITH AA022511 OS Me, page 4',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\04_index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '234',
                        'AcctNum' => ''
                    ),
                    array(
                        'id' => '4',
                        'date_of_service' => NULL,
                        'document_type' => 'Outside Medical Record',
                        'document_name' => 'THURSTON MOORE AA022511 OS MRI',
                        'full_path' => 'C:\\xampp\\htdocs\\mshc\\uploads\\index.docx',
                        'FileType' => 'doc',
                        'lPAGEID' => '234',
                        'AcctNum' => ''
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getDocIDs($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);
                if ($dbKey == self::microMD) {
                    $condParams = array('AMM_LIVE');
                    $params = array();

                    $query = "select c.DocID, 
                              c.GuarantorID,
                              c.PracticeID,
                              c.PatientNum,
                              c.SequenceNum,
                              c.ReportType,
                              c.DocTitle,
                              c.DatabaseName,
                              ih.extended_data,
                              ih.base_table_guid,
                              c.DocType,
                              c.ServiceDate,
                              c.PolicyNum
                            from MicroMDProcesses.ClaimsProcessing.tblECSClaims as c with (nolock)
                            left join AMM_LIVE.dbo.pm_ins_hdr as ih with (nolock)
                              on c.GuarantorID = ih.guarantor_id  
                              and c.PracticeID = ih.practice_id 
                              and c.PatientNum = ih.patient_no 
                              and c.SequenceNum = ih.sequence_no
                              and c.DatabaseName = ih.database_name
                            where c.DatabaseName = ?";

                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    Array(
                        'DocID' => 4976702,
                        'GuarantorID' => 26447,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 2,
                        'ReportType' => 'M1',
                        'DocTitle' => 'THOMAS, CYNTHIA 01/04/2016',
                        'extended_data' => "<root><d_case_notes><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-07-08</note_date><note_category>7</note_category><note_text>bld atty Ingerman md dos 6/25/10*******************6/25/10********************</note_text><user_name>mshwartz</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-07-01</note_date><note_category>7</note_category><note_text>stmnt on hold missing md dos 6/25/10**************6/25/10*************************</note_text><user_name>mshwartz</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-25</note_date><note_category>4</note_category><note_text>Called Injusred Worker Fund Spoke to Carrol who staed the calimhas been made Claim # 6253915 Adj Sharlot Diverly the Claim is opened... She stated that when you call you have to go though the auto service </note_text><user_name>aruffner</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-25</note_date><note_category>9</note_category><note_text>Patient was at work driving a MTA bus when she was hurt she said she filled a first injury report but does not have claim inf with her she has BCBS but did not have INS card with her she think the insurance company is Injured worker Fund... Did not yet ver wc Is being Rep by Bruce Ingerman </note_text><user_name>aruffner</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-18</note_date><note_category/><note_text>atty office called and stated they want all her old records from 05 once the tx office recieves them for her next appt.</note_text><user_name>clull</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-03-04</note_date><note_category>8</note_category><note_text>patient cancelled she feels more comfortable seeing her internal medicine doctor. atty's office notified( Mike)</note_text><user_name>munitas</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row></d_case_notes></root>",
                        'base_table_guid' => '7B3990EB-3F54-4B74-99EC-F5B7074DD447',
                    ),
                    Array(
                        'DocID' => 4976980,
                        'GuarantorID' => 26833,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 59,
                        'ReportType' => 'M1',
                        'DocTitle' => 'WALL, COLYN 02/26/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => '728CEDE3-5EC1-46FC-A594-A1EC53C0FE26',
                    ),
                    Array
                    (
                        'DocID' => 4974921,
                        'GuarantorID' => 30586,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 56,
                        'ReportType' => 'M1',
                        'DocTitle' => 'SWIFT, CAROLE 02/24/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => '87B0CF12-25C7-4016-8D83-E88B22AFBB09',
                    ),
                    Array
                    (
                        'DocID' => 4976908,
                        'GuarantorID' => 33543,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 84,
                        'ReportType' => 'M1',
                        'DocTitle' => 'MYERS, BETTY 02/25/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => 'A33B9418-84D4-4AF8-9C5D-BBB44F7C2DF4',
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getOtherSequences($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);
                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['database_name'],
                        $queryParams['conds']['service_date_from'],
                        $queryParams['conds']['guarantor_id'],
                        $queryParams['conds']['practice_id'],
                        $queryParams['conds']['patient_no'],
                        $queryParams['conds']['sequence_no'],
                    );
                    $params = array();

                    $query = "select th.sequence_no,
                                      ih.base_table_guid,
                                      ih.extended_data
                              from amm_live.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
                              left join AMM_LIVE.dbo.pm_ins_hdr as ih with (nolock)
                                on th.guarantor_id = ih.guarantor_id  
                                and th.practice_id = ih.practice_id 
                                and th.patient_no = ih.patient_no 
                                and th.database_name = ih.database_name
                                and th.sequence_no   = ih.sequence_no
                              where th.database_name = ?
                              and th.service_date_from = ?
                              and th.guarantor_id = ?
                              and th.practice_id = ?
                              and th.patient_no = ?
                              and th.sequence_no != ?
                              and 1 = 2";

                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    Array(
                        'DocID' => 4976702,
                        'GuarantorID' => 26447,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 2,
                        'ReportType' => 'M1',
                        'DocTitle' => 'THOMAS, CYNTHIA 01/04/2016',
                        'extended_data' => "<root><d_case_notes><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-07-08</note_date><note_category>7</note_category><note_text>bld atty Ingerman md dos 6/25/10*******************6/25/10********************</note_text><user_name>mshwartz</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-07-01</note_date><note_category>7</note_category><note_text>stmnt on hold missing md dos 6/25/10**************6/25/10*************************</note_text><user_name>mshwartz</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-25</note_date><note_category>4</note_category><note_text>Called Injusred Worker Fund Spoke to Carrol who staed the calimhas been made Claim # 6253915 Adj Sharlot Diverly the Claim is opened... She stated that when you call you have to go though the auto service </note_text><user_name>aruffner</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-25</note_date><note_category>9</note_category><note_text>Patient was at work driving a MTA bus when she was hurt she said she filled a first injury report but does not have claim inf with her she has BCBS but did not have INS card with her she think the insurance company is Injured worker Fund... Did not yet ver wc Is being Rep by Bruce Ingerman </note_text><user_name>aruffner</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-06-18</note_date><note_category/><note_text>atty office called and stated they want all her old records from 05 once the tx office recieves them for her next appt.</note_text><user_name>clull</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row><d_case_notes_row><practice_id>1</practice_id><guarantor_id>26447</guarantor_id><patient_no>0</patient_no><sequence_no>0</sequence_no><line_no>0</line_no><note_date>2010-03-04</note_date><note_category>8</note_category><note_text>patient cancelled she feels more comfortable seeing her internal medicine doctor. atty's office notified( Mike)</note_text><user_name>munitas</user_name><cprint>1</cprint><todo/><not_secured/><last_name>THOMAS</last_name><first_name>CYNTHIA</first_name><middle_initial/><case_number>1</case_number><case_name>WC022210</case_name><case_description>WORKERS COMP 2/22/10</case_description></d_case_notes_row></d_case_notes></root>",
                        'base_table_guid' => '7B3990EB-3F54-4B74-99EC-F5B7074DD447',
                    ),
                    Array(
                        'DocID' => 4976980,
                        'GuarantorID' => 26833,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 59,
                        'ReportType' => 'M1',
                        'DocTitle' => 'WALL, COLYN 02/26/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => '728CEDE3-5EC1-46FC-A594-A1EC53C0FE26',
                    ),
                    Array
                    (
                        'DocID' => 4974921,
                        'GuarantorID' => 30586,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 56,
                        'ReportType' => 'M1',
                        'DocTitle' => 'SWIFT, CAROLE 02/24/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => '87B0CF12-25C7-4016-8D83-E88B22AFBB09',
                    ),
                    Array
                    (
                        'DocID' => 4976908,
                        'GuarantorID' => 33543,
                        'PracticeID' => 1,
                        'PatientNum' => 0,
                        'SequenceNum' => 84,
                        'ReportType' => 'M1',
                        'DocTitle' => 'MYERS, BETTY 02/25/2016',
                        'extended_data' => NULL,
                        'base_table_guid' => 'A33B9418-84D4-4AF8-9C5D-BBB44F7C2DF4',
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getDocumentsThroughDocID($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);
                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['doc_id']
                    );
                    $params = array();

                    $query = "SELECT doc.LDocID_ as id,         
										DSDOS.dtValue as date_of_service,
										case when DSDocType.sVALUE = 'PROG NOTE' then 'PT Note' else DSDocType.sVALUE end as document_type,
										case when Pg.lPAGEID is not null 
                        then doc.sTITLE + ', page ' + cast(ROW_NUMBER() OVER (PARTITION BY doc.LDocID_  ORDER BY DSDOS.dtValue, doc.LDocID_, lPAGEID, doc.sTITLE) as varchar(128))
                       else 
                        doc.sTITLE
                     end as document_name,
                    replace(replace(case when Ft.FileType is not null 
                                            then doc.sPATH_ + '\N' + cast(doc.lDOCID_ as varchar(128)) + Ft.FileType
                                        when  Ft.FileType is null 
                                            then case when doc.nType_  in (0,1,2)
                                                          then CASE WHEN Pg.nIMAGETYPE = 2
                                                                        THEN doc.sPATH_ + '\' + right('00000000' + cast(Pg.lPageID as varchar(128)), 8) + '.pdf'
                                                                    ELSE 
                                                                        doc.sPATH_ + '\' + right('00000000' + cast(Pg.lPageID as varchar(128)), 8) + '.tif'
                                                                END
                                                      else
                                                          doc.sPATH_ + '\N' + cast(doc.lDOCID_ as varchar(128)) + '.pdf'
                                                  end
                                  end, 'D:\docs', 'W:'), 'C:', 'Z:') as full_path,
										Ft.FileType,
										Pg.lPAGEID,
										Acc.sValue as AcctNum
								from DOCSTAR.DOCSTAR.DSUSER.tblDOCUMENT as doc with (nolock)
								left join DOCSTAR.DOCSTAR.DSUSER.tblFILETYPES as Ft with (nolock)
								  on Doc.nTYPE_ = Ft.DocType
								left join DOCSTAR.DOCSTAR.DSUSER.tblPAGE as Pg with (nolock)
								  on Doc.lDOCID_ = Pg.lDOCID
								left join DOCSTAR.DOCSTAR.DSUSER.tblCFD_ACCTNO as Acc with (nolock)
								  on Doc.lDOCID_ = Acc.lDOCID
								LEFT JOIN DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOA as DSDOA with (nolock)
								  ON DSDOA.LDocID = doc.LDocID_   
								LEFT JOIN DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOCTYPE as DSDocType with (nolock)
								  ON DSDocType.LDocID = doc.LDocID_ 
								left join DOCSTAR.DOCSTAR.DSUSER.tblCFD_DOS as DSDOS with (nolock)
												on        DSDOS.LDocID = doc.LDocID_   
								WHERE DSDocType.sValue in ( 'Medical Report','MRI Report', 'Prog Note', 'Disability', 'PT-BWR Referral', 'Consult', 'OS MED REC', 'Rx Req', 'NTI Report', 'Letter')
                        and (doc.bDeleted_ is null or doc.bDeleted_ != -1)
								and doc.LDocID_ = ?
								group by doc.LDocID_,
								        DSDOS.dtValue,
								        DSDocType.sVALUE,
								        Pg.lPageID,
								        doc.sTITLE,
								        Ft.FileType,
								        doc.sPATH_,
								        doc.nType_,
								        Pg.nIMAGETYPE,
								        Acc.sValue";

                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    Array
                    (
                        'id' => 4604229,
                        'date_of_service' => NULL,
                        'document_type' => 'OS MED REC',
                        'document_name' => '1.93414.0 PROV WC013014 OS MED REC, page 1',
                        'full_path' => 'W:\VOLUME_0.140\ARCHIVE\00000029\16542004.tif',
                        'FileType' => NULL,
                        'lPAGEID' => 16542004,
                        'AcctNum' => '1.93414.0',
                    ),
                    Array
                    (
                        'id' => 4604229,
                        'date_of_service' => NULL,
                        'document_type' => 'OS MED REC',
                        'document_name' => '1.93414.0 PROV WC013014 OS MED REC, page 2',
                        'full_path' => 'W:\VOLUME_0.140\ARCHIVE\00000029\16542005.tif',
                        'FileType' => NULL,
                        'lPAGEID' => 16542005,
                        'AcctNum' => '1.93414.0',
                    ),
                    Array
                    (
                        'id' => 4604229,
                        'date_of_service' => NULL,
                        'document_type' => 'OS MED REC',
                        'document_name' => '1.93414.0 PROV WC013014 OS MED REC, page 3',
                        'full_path' => 'W:\VOLUME_0.140\ARCHIVE\00000029\16542006.tif',
                        'FileType' => NULL,
                        'lPAGEID' => 16542006,
                        'AcctNum' => '1.93414.0',
                    ),
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //**************************************************************************
    public function manageInsHdr($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('insert', 'update'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    libxml_use_internal_errors(true);
                    $xmlString = simplexml_load_string($queryParams['values']['extended_data']);
                    $xml = explode("\n", $xmlString);
                    $errors = libxml_get_errors();

                    if (!empty($errors)) {
                        return FALSE;
                    }
                    if ($queryType == 'insert') {
                        $valueParams = array(
                            $queryParams['values']['practice_id'],
                            $queryParams['values']['guarantor_id'],
                            $queryParams['values']['patient_no'],
                            $queryParams['values']['sequence_no'],
                            $queryParams['values']['database_name'],
                            $queryParams['values']['extended_data']
                        );
                        $query = "insert 
                                  into AMM_LIVE.dbo.pm_ins_hdr (practice_id, guarantor_id, patient_no, sequence_no, database_name, extended_data)
                                  values (?, ?, ?, ?, ?, ?)";
                    } elseif ($queryType == 'update') {
                        if (empty($queryParams['values']['base_table_guid'])) {
                            return FALSE;
                        } elseif (!empty ($errors)) {
                            return FALSE;
                        }

                        $valueParams = array(
                            $queryParams['values']['extended_data'],
                            $queryParams['values']['base_table_guid'],
                        );

                        $query = "update AMM_LIVE.dbo.pm_ins_hdr
                                  set extended_data = ?
                                  where base_table_guid = ?";
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $valueParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'true') {
                return TRUE;
            }
            if ($queryParams['debugReturn'] == 'false') {
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    public function getCronDocuments($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }

            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['doc_created'][0],
                        $queryParams['conds']['doc_created'][1],
                    );
                    $query = "select  id,
                                date_of_service,
                                document_type,
                                document_date, 
                                document_name,
                                full_path,
                                FileType, 
                                lPAGEID,
                                AcctNum,
                                database_name,
                                practice_id, 
                                guarantor_id,
                                patient_no,
                                case_no,
                                employer_id,
                                first_name,
                                last_name 
                            from PointsProcesses.MicroMD.get{$this->dbTblPrefix}CronDocuments(?, ?)";

                    $params = array();
                    if (isset($queryParams['order'])) {
                        $params['sortAndLimitQuery'] = TRUE;
                        $params['queryParams'] = $queryParams;
                    }
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $params);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'id' => '1',
                        'date_of_service' => (new DateTime('2012-03-01 00:00:00', new DateTimeZone('America/New_York'))),
                        'document_type' => 'Disability',
                        'document_name' => 'Disability 1.42105.0 ALICE ACCIDENT',
                        'full_path' => 'http://www.all-impex.ru/upload/product/pdf_file/1293449822472.pdf',
                        'FileType' => 'doc',
                        'lPAGEID' => '',
                        'AcctNum' => '',
                        'database_name' => 'amm_live',
                        'practice_id' => 1,
                        'guarantor_id' => 35265,
                        'patient_no' => 0,
                        'case_no' => 2,
                        'employer_id' => 1151
                    ),
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getAttorneys($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                $resultSetParams = array();
                if (isset($queryParams['limit']) or isset($queryParams['order'])) {
                    $resultSetParams['queryParams'] = $queryParams;
                    $resultSetParams['sortAndLimitQuery'] = TRUE;
                }


                if ($dbKey == self::microMD) {
                    $ammLiveParams = array('a');
                    $bwrParams = array('a');
                    $mdParams = array('a');
                    $mriParams = array('a');
                    $ptParams = array('a');

                    $dbHolders = array();
                    foreach ($dbs as $db) {
                        $ammLiveParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $bwrParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $mdParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $mriParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $ptParams[] = $this->dbPrefxs['microMDNames'][$db];
                        $dbHolders[] = "?";
                    }

                    $condStringArray = array();
                    if (isset($queryParams['conds']['name'])) {
                        $condStringArray[] = "employer_name like ?";
                        $ammLiveParams[] = '%' . $queryParams['conds']['name'] . '%';
                        $bwrParams[] = '%' . $queryParams['conds']['name'] . '%';
                        $mdParams[] = '%' . $queryParams['conds']['name'] . '%';
                        $mriParams[] = '%' . $queryParams['conds']['name'] . '%';
                        $ptParams[] = '%' . $queryParams['conds']['name'] . '%';
                    }

                    if (isset($queryParams['conds']['attorney_id'])) {
                        $attayParams = array();
                        $this->constructCondString($condStringArray, $attayParams, $dbKey, 'attys', 'attorney_id', $queryParams['conds']);
                        $ammLiveParams = array_merge($ammLiveParams, $attayParams);
                        $bwrParams = array_merge($bwrParams, $attayParams);
                        $mdParams = array_merge($mdParams, $attayParams);
                        $mriParams = array_merge($mriParams, $attayParams);
                        $ptParams = array_merge($ptParams, $attayParams);
                    }

                    if (!empty($condStringArray)) {
                        $condString = ' and ' . implode(' and ', $condStringArray);
                    } else {
                        $condString = '';
                    }

                    $condParams = array_merge($ammLiveParams, $bwrParams, $mdParams, $mriParams, $ptParams);
                    $query = "select employer_id,
                        employer_name,
                        UPPER(database_name) AS database_name 
                          from(select employer_id,
                                  employer_name,
                                  database_name
                          from AMM_LIVE.dbo.pm_employer as e
                          where record_type = ?
                          and database_name in (" . implode(',', $dbHolders) . ")
                          $condString
                          union
                          select employer_id,
                                  employer_name,
                                  database_name
                          from bwr.dbo.pm_employer as e
                          where record_type = ?
                          and database_name in (" . implode(',', $dbHolders) . ")
                          $condString
                          union
                          select employer_id,
                                  employer_name,
                                  database_name
                          from md.dbo.pm_employer as e
                          where record_type = ?
                          and database_name in (" . implode(',', $dbHolders) . ")
                          $condString
                          union
                          select employer_id,
                                  employer_name,
                                  database_name
                          from mri.dbo.pm_employer as e
                          where record_type = ?
                          and database_name in (" . implode(',', $dbHolders) . ")
                          $condString
                          union
                          select employer_id,
                                  employer_name,
                                  database_name
                          from pt.dbo.pm_employer as e
                          where record_type = ?
                          and database_name in (" . implode(',', $dbHolders) . ")
                          $condString
                    ) as attrnys ";
                    //print_r($query);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $resultSetParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array(
                    array(
                        'employer_id' => '1',
                        'employer_name' => 'Robert Smith',
                        'database_name' => 'AMM_LIVE'
                    ),
                    array(
                        'employer_id' => '5',
                        'employer_name' => 'Kevin Shields',
                        'database_name' => 'BWR'
                    ),
                    array(
                        'employer_id' => '11',
                        'employer_name' => 'Lee Ranaldo',
                        'database_name' => 'AMM_LIVE'
                    ),
                    array(
                        'employer_id' => '13',
                        'employer_name' => 'Edward Ka-spell',
                        'database_name' => 'BWR'
                    ),
                    array(
                        'employer_id' => '15',
                        'employer_name' => 'Tom yorke',
                        'database_name' => 'AMM_LIVE'
                    )
                );
                //			print_r($rows); return;
                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    //****************************************************************************
    public function getCronDischargedPatients($dbs, $queryType = 'all', $queryParams = array())
    {
        if (PMS_CONN == 'live') {
            if (!in_array($queryType, array('all', 'first'))) {
                $this->error = 'Wrong type of query (must be first or all)';
                return FALSE;
            }
            if ($this->getResources($dbs)) {
                $dbKey = $this->getDBKey($dbs);

                $resultSetParams = array();
                if (isset($queryParams['limit']) or isset($queryParams['order'])) {
                    $resultSetParams['queryParams'] = $queryParams;
                    $resultSetParams['sortAndLimitQuery'] = TRUE;
                }


                if ($dbKey == self::microMD) {
                    $condParams = array(
                        $queryParams['conds']['tr_service_date'][0],
                        $queryParams['conds']['tr_service_date'][1],
                        $queryParams['conds']['tr_service_date'][0],
                        $queryParams['conds']['tr_service_date'][1],
                        $queryParams['conds']['tr_service_date'][0],
                        $queryParams['conds']['tr_service_date'][1],
                        $queryParams['conds']['tr_service_date'][0],
                        $queryParams['conds']['tr_service_date'][1],
                        $queryParams['conds']['tr_service_date'][0],
                        $queryParams['conds']['tr_service_date'][1],
                    );


                    // query independant of the schema, placeholder needs to subsituted later
                    // to specify the schema (schema free query)
                    $SFquery = "select tr.practice_id,
							tr.guarantor_id, 
							tr.patient_no, 
							tr.database_name,
							poi.case_no,
							poi.employer_id,
							p.first_name,
							p.last_name,
							tr.service_date_from,
							cc.attorney_name,
							poi.injury_date
							from non_db.dbo.pm_patient_other_info as poi with (nolock)
							left join non_db.dbo.pm_patient as p with (nolock)
								on p.practice_id = poi.practice_id 
								and p.guarantor_id = poi.guarantor_id 
								and p.patient_no = poi.patient_no 
								and p.database_name = poi.database_name
							left join non_db.dbo.pm_employer as e with (nolock)
								on poi.employer_id = e.employer_id 
								and poi.database_name = e.database_name
							left join non_db.dbo.pm_TRANSACTION_HEADER as th with (nolock)
								on th.practice_id = poi.practice_id 
								and th.guarantor_id = poi.guarantor_id 
								and th.patient_no = poi.patient_no 
								and th.database_name = poi.database_name 
								and th.case_no = poi.case_no  
							left join non_db.dbo.pm_TRANSACTION as tr with (nolock)
								on tr.practice_id = poi.practice_id 
								and tr.guarantor_id = poi.guarantor_id 
								and tr.patient_no = poi.patient_no 
								and tr.sequence_no = th.sequence_no 
								and tr.database_name = th.database_name 
							left join PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}MicroMDCaseCategories as cc with (nolock)
							  on cc.database_name = poi.database_name
							  and cc.category_code = poi.case_category 
					where tr.service_date_from between ? and ? 
					and tr.procedure_code in ('prn', 'dop')
					and tr.procedure_code is not null
					and tr.database_name = 'non_db'
					and tr.database_name is not null
					and tr.service_date_from is not null
					and poi.employer_id is not null
					group by tr.practice_id,
							tr.guarantor_id, 
							tr.patient_no, 
							tr.database_name,
							poi.case_no,
							poi.employer_id,
							p.first_name,
							p.last_name,
							tr.service_date_from,
							cc.attorney_name,
							cc.attorney_name,
							poi.injury_date";

                    $query = str_replace('non_db', 'AMM_LIVE', $SFquery) .
                        " union all " .
                        str_replace('non_db', 'bwr', $SFquery) .
                        " union all " .
                        str_replace('non_db', 'md', $SFquery) .
                        " union all " .
                        str_replace('non_db', 'mri', $SFquery) .
                        " union all " .
                        str_replace('non_db', 'pt', $SFquery);
                }
                return $this->getResultSet($queryType, $this->conns[$dbKey], $query, $condParams, $resultSetParams);
            } else {
                return false;
            }
        } elseif (PMS_CONN == 'debug') {
            if ($queryParams['debugReturn'] == 'empty') {
                return array();
            } elseif ($queryParams['debugReturn'] == 'sample') {
                $rows = array();

                return $rows;
            } else {
                $this->error = 'Missing debug query';
                return FALSE;
            }
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }

    public function getCalculateDistance($params)
    {
        if (PMS_CONN == 'live') {
            $HOST = 'http://maps.googleapis.com';
            $SUBMIT_URI = 'maps/api/distancematrix/json';

            //open connection
            $ch = curl_init();
//	  print_r($params);
//	  print_r(http_build_query($params));

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $HOST . '/' . $SUBMIT_URI . '?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            //execute post
            $result = curl_exec($ch);
//	  print_r($result);

            //close connection
            curl_close($ch);

            $decodedResult = json_decode($result);
//	  print_r($decodedResult);
            if ($decodedResult === NULL) {
                return FALSE;
            } else {
                if ($decodedResult->status == 'OK') {
                    return $decodedResult;
                } else {
                    return FALSE;
                }
            }

        } elseif (PMS_CONN == 'debug') {
            $sampleDistance = new stdClass();
            $sampleDistance->destination_addresses = array('Sampe Start Address');
            $sampleDistance->origin_addresses = array('Sampe End Address');
            $firstElem = new stdClass();
            $innerFirstElem = new stdClass();
            $innerFirstElem->distance = new stdClass();
            $innerFirstElem->distance->text = '24.8 mi';
            $innerFirstElem->distance->value = 39917;
            $innerFirstElem->duration = new stdClass();
            $innerFirstElem->duration->text = '30 mins';
            $innerFirstElem->duration->value = 1803;
            $innerFirstElem->status = 'OK';
            $firstElem->elements = array($innerFirstElem);
            $sampleDistance->rows = array($firstElem);
            $sampleDistance->status = 'OK';

            return $sampleDistance;
        } else {
            $this->error = 'Wrong PMS connection (must be live or debug';
            return FALSE;
        }
    }


    private function getOrderByForLimit($order)
    {
        foreach ($order as $field => $direction) {
            if (!in_array(strtoupper($direction), array('DESC', 'ASC'))) {
                $field = $direction;
                $direction = 'DESC';
            } else {
                if ($direction == 'ASC')
                    $direction = 'DESC';
                else
                    $direction = 'ASC';
            }
            $order[$field] = $direction;
        }
        return $order;
    }

    //****************************************************************************
    private function getOrderByString($order, $params, $tableAlias)
    {
        $orderByFields = array();
        foreach ($order as $field => $direction) {
            if (!in_array(strtoupper($direction), array('DESC', 'ASC'))) {
                $field = $direction;
                $direction = 'ASC';
            }

            if (isset($params['query']) and $params['query'] == 'getCases') {
                if ($field == 'patient') {
                    $orderByFields[] = $tableAlias . '.' . 'last_name' . ' ' . $direction;
                    $orderByFields[] = $tableAlias . '.' . 'first_name' . ' ' . $direction;
                } else {
                    $orderByFields[] = $tableAlias . '.' . $field . ' ' . $direction;
                }
            } else {
                if (gettype($field) == 'object') $field = ' convert(datetime, ' . $field . ', 112) '; // ISO yymmdd
                $orderByFields[] = $field . ' ' . $direction;
            }
        }

        return implode(',', $orderByFields);
    }

    /*****************************************************************************
     * returns database query or in case of error set the error, purpose of this
     * thethod is to lower number of code lines as error setting would repeat in
     * each query
     */
    private function getResultSet($queryType, $dbConn, $query, $combinedCondParams = array(), $params = array())
    {
        if (isset($params['sortAndLimitQuery'])) {
            $queryParams = $params['queryParams'];
            if (isset($queryParams['limit']) and isset($queryParams['order']) and !empty($queryParams['order'])) { // both order and limit
                if (is_array($queryParams['limit'])) {
                    $query = 'select * 
						  from (select top ' . (int)$queryParams['limit'][0] . ' * 
								from (select top ' . (int)($queryParams['limit'][0] + $queryParams['limit'][1]) . ' * 
									  from (' . $query . ') as t1 order by ' . $this->getOrderByString($queryParams['order'], $params, 't1') . '
									  ) as t2 order by ' . $this->getOrderByString($this->getOrderByForLimit($queryParams['order']), $params, 't2') . '
								) as t3 order by ' . $this->getOrderByString($queryParams['order'], $params, 't3');
                } else {
                    $query = 'select top ' . (int)$queryParams['limit'] . ' * 
						from (' . $query . ') as t1 
						order by ' . $this->getOrderByString($queryParams['order'], $params, 't1');
                }
                //print_r($query); return;
            } elseif (isset($queryParams['limit'])) { // only limit
                if (is_array($queryParams['limit'])) {
                    $query = 'select * 
						from (select top ' . (int)$queryParams['limit'][0] . ' * 
							  from (select top ' . (int)($queryParams['limit'][0] + $queryParams['limit'][1]) . ' * 
									from ( ' . $query . '      ) as t1
									) as t2
							  ) as t3';
                } else {
                    $query = 'select top ' . (int)$queryParams['limit'] . ' * 
						from (' . $query . ') as t1';
                }
            } elseif (isset($queryParams['order']) and !empty($queryParams['order'])) { // only order
                $query = 'select * 
					  from (' . $query . ') as t1 
					  order by ' . $this->getOrderByString($queryParams['order'], $params, 't1');
            }
        }
        //print_dump($query);
        //print_dump($params);
        //print_dump($combinedCondParams);

        if ($queryType == 'all') {
            $stmt = sqlsrv_query($dbConn, $query, $combinedCondParams);
            //echo '<pre>'.print_r($stmt,true).'=3=';
            //print_dump($stmt, 2);

            if ($stmt === FALSE) {

                $sqlsrvErrors = sqlsrv_errors();
                if (empty($sqlsrvErrors)) {
                    $this->error = 'Could not execute the query';
                } else {
                    $this->error = $sqlsrvErrors;
                }
                //print_r($this->error);
                return FALSE;
            } else {
                $results = array();
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $results[] = $row;
                }
                return $results;
            }
        } elseif ($queryType == 'first') {
            $stmt = sqlsrv_query($dbConn, 'select top 1 * from (' . $query . ') as unions_query', $combinedCondParams);
            if ($stmt === FALSE) {
                $sqlsrvErrors = sqlsrv_errors();
                if (empty($sqlsrvErrors)) {
                    $this->error = 'Could not execute the query';
                } else {
                    $this->error = $sqlsrvErrors;
                }
                //print_dump($this->error);
                return FALSE;
            } else {
                return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            }
        } elseif (in_array($queryType, array('insert', 'update', 'delete'))) {
            $stmt = sqlsrv_query($dbConn, $query, $combinedCondParams);
            if ($stmt === FALSE) {
                $sqlsrvErrors = sqlsrv_errors();
                if (empty($sqlsrvErrors)) {
                    $this->error = 'Could not execute the query';
                } else {
                    $this->error = $sqlsrvErrors;
                }
                //print_dump($this->error);
                return FALSE;
            } else {
                return TRUE;
            }
        } elseif ($queryType == 'count') {
            $stmt = sqlsrv_query($dbConn, 'select count(*) as count from (' . $query . ') as unions_query', $combinedCondParams);
            if ($stmt === FALSE) {
                $sqlsrvErrors = sqlsrv_errors();
                if (empty($sqlsrvErrors)) {
                    $this->error = 'Could not execute the query';
                } else {
                    $this->error = $sqlsrvErrors;
                }
                //print_dump($this->error);
                return FALSE;
            } else {
                return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            }
        }
    }


    /*****************************************************************************
     * returns the error message that has occured during the class call
     */
    public function getError()
    {
        return $this->error;
    }

    /*****************************************************************************
     * returns databse array key for fields and other attributes
     */
    private function getDBKey($dbs)
    {
        if (min($dbs) >= 1 and max($dbs) <= 5) {
            return self::microMD;
        }
    }

    /*****************************************************************************
     * constructs field into a string for query based on the key and parameters passed
     * into it
     */
    private function constructFieldString($dbKey, $query, $fieldName, $type = 'select', $params = array())
    {
        if ($type == 'select') {
            return $this->fields[$dbKey][$query][$fieldName] . ' as ' . $fieldName;
        } elseif ($type == 'groupBy') {
            return $this->fields[$dbKey][$query][$fieldName];
        } else {
            return '';
        }
    }

    /*****************************************************************************
     * delete all temprary records from tblTempDbsAttys table after they are all
     * used for queries
     */
    private function cleanuptblTempDbsAttys($insertID, $dbKey)
    {
        $deleteQuery = "delete 
              from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempDbsAttys 
              where insert_id = ?";
        $this->getResultSet('delete', $this->conns[$dbKey], $deleteQuery, array($insertID));
    }

    /*****************************************************************************
     * delete all temprary records from tblTempCases table after they are all
     * used for queries
     */
    private function cleanuptblTempCases($insertID, $dbKey)
    {
        $deleteQuery = "delete 
              from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempCases 
              where insert_id = ?";
        $this->getResultSet('delete', $this->conns[$dbKey], $deleteQuery, array($insertID));
    }


    /*****************************************************************************
     * contracts simple where statments
     */
    private function constructCondString(
        &$condStrings,
        &$condParams,
        $dbKey = null,
        $queryName = null,
        $fieldName = null,
        $condVals = null,
        $params = array())
    {
        if (!empty($params)) {
            if (in_array('caseAttysOr', $params)) {
                // remove duplicates
                $stringPairs = array();
                foreach ($condVals[$fieldName]['value'] as $key => $dbAttyPair) {
                    if (in_array(strtoupper($dbAttyPair['database']) . $dbAttyPair['attorney_id'], $stringPairs)) {
                        unset($condVals[$fieldName]['value'][$key]);
                    } else {
                        $stringPairs[] = strtoupper($dbAttyPair['database']) . $dbAttyPair['attorney_id'];
                    }
                }
                unset($stringPairs);

                $insertQueryStart = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempDbsAttys 
                    (insert_id, db_name, atty_id)
                    values ";

                $insertQueryValueStrings = array(); // query string with all the 
                $insertQueryValueValues = array(); // the real values for value part of the query
                foreach ($condVals[$fieldName]['value'] as $dbAttyPair) {
                    $insertQueryValueStrings[] = "(?, ?, ?)";
                    $insertQueryValueValues[] = $params['insertID'];
                    $insertQueryValueValues[] = $dbAttyPair['database'];
                    $insertQueryValueValues[] = $dbAttyPair['attorney_id'];
                    if (count($condVals[$fieldName]['value']) % 1000 == 0) {
                        $insertQuery = $insertQueryStart . implode(',', $insertQueryValueStrings);
                        if ($this->getResultSet('insert', $this->conns[$dbKey], $insertQuery, $insertQueryValueValues) !== TRUE) {
                            $this->error = 'Could not save database and attorney pairs';
                            return FALSE;
                        }
                        $insertQueryValueStrings = array(); // query string with all the 
                        $insertQueryValueValues = array(); // the real values for value part of the query
                    }
                }
                // save leftovers if not empty
                if (!empty($insertQueryValueValues)) {
                    $insertQuery = $insertQueryStart . implode(',', $insertQueryValueStrings);
                    if ($this->getResultSet('insert', $this->conns[$dbKey], $insertQuery, $insertQueryValueValues) !== TRUE) {
                        $this->error = 'Could not save database and attorney pairs';
                        return FALSE;
                    }
                }

                $condStrings[] = "exists (select *
							            from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempDbsAttys as tmp
							            where tmp.atty_id = e.employer_id 
							            and tmp.db_name = poi.database_name
							            and tmp.insert_id = ?)";
                $condParams[] = $params['insertID'];
            }
            if (in_array('includeCases', $params) or in_array('excludeCases', $params)) {
                // remove duplicates
                $stringPairs = array();
                foreach ($condVals[$fieldName]['value'] as $key => $dbAttyPair) {
                    if (in_array($dbAttyPair['account'] . '.' . $dbAttyPair['patient'] . '.' . strtoupper($dbAttyPair['db_name']) . '.' . $dbAttyPair['practice'] . '.' . $dbAttyPair['case_no'], $stringPairs)) {
                        unset($condVals[$fieldName]['value'][$key]);
                    } else {
                        $stringPairs[] = $dbAttyPair['account'] . '.' . $dbAttyPair['patient'] . '.' . strtoupper($dbAttyPair['db_name']) . '.' . $dbAttyPair['practice'] . '.' . $dbAttyPair['case_no'];
                    }
                }
                unset($stringPairs);

                $insertQueryStart = "insert into PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempCases 
                    (insert_id, account, db_name, patient, practice, case_no)
                    values ";

                $insertQueryValueStrings = array(); // query string with all the 
                $insertQueryValueValues = array(); // the real values for value part of the query
                foreach ($condVals[$fieldName]['value'] as $dbAttyPair) {
                    $insertQueryValueStrings[] = "(?, ?, ?, ?, ?, ?)";
                    $insertQueryValueValues[] = $params['insertID'];
                    $insertQueryValueValues[] = $dbAttyPair['account'];
                    $insertQueryValueValues[] = $dbAttyPair['db_name'];
                    $insertQueryValueValues[] = $dbAttyPair['patient'];
                    $insertQueryValueValues[] = $dbAttyPair['practice'];
                    $insertQueryValueValues[] = $dbAttyPair['case_no'];
                    if (count($condVals[$fieldName]['value']) % 500 == 0) {
                        $insertQuery = $insertQueryStart . implode(',', $insertQueryValueStrings);
                        if ($this->getResultSet('insert', $this->conns[$dbKey], $insertQuery, $insertQueryValueValues) !== TRUE) {
                            $this->error = 'Could not save database and attorney pairs';
                            return FALSE;
                        }
                        $insertQueryValueStrings = array(); // query string with all the 
                        $insertQueryValueValues = array(); // the real values for value part of the query
                    }
                }
                // save leftovers if not empty
                if (!empty($insertQueryValueValues)) {
                    $insertQuery = $insertQueryStart . implode(',', $insertQueryValueStrings);
                    if ($this->getResultSet('insert', $this->conns[$dbKey], $insertQuery, $insertQueryValueValues) !== TRUE) {
                        $this->error = 'Could not save database and attorney pairs';
                        return FALSE;
                    }
                }

                $notString = '';
                if (in_array('excludeCases', $params)) {
                    $notString = 'not';
                }
                $condStrings[] = "$notString exists (select *
							            from PointsProcesses.MicroMD.tbl{$this->dbTblPrefix}TempCases as tmp
							            where tmp.account = p.guarantor_id
							            and tmp.db_name = poi.database_name
                          and tmp.patient = p.patient_no
                          and tmp.practice = p.practice_id
                          and tmp.case_no = poi.case_no
							            and tmp.insert_id = ?)";
                $condParams[] = $params['insertID'];


                /*
                  $queryStrings = array();
                  foreach ($condVals[$fieldName]['value'] as $caseSet) {
                    $queryStrings[] = "(p.guarantor_id = ? and  poi.database_name = ? and p.patient_no = ? and p.practice_id = ? and poi.case_no = ?)";
                    $condParams[] = $caseSet['account'];
                    $condParams[] = $caseSet['db_name'];
                    $condParams[] = $caseSet['patient'];
                    $condParams[] = $caseSet['practice'];
                    $condParams[] = $caseSet['case_no'];
                  }
                  $stringStart = '';
                  if(in_array('excludeCases', $params)) {
                    $stringStart = ' not ';
                  }
                  $condStrings[] = $stringStart . '(' . implode(' or ', $queryStrings) . ')';
                }
                */
            }
        } else {
            if (!is_array($condVals[$fieldName])) {
                $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . ' = ?';
                $condParams[] = &$condVals[$fieldName];
            } else {
                if (isset($condVals[$fieldName]['sp']) and strtoupper($condVals[$fieldName]['sp']) == 'NULL') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . ' is null';
                } elseif (isset($condVals[$fieldName]['sp']) and strtoupper($condVals[$fieldName]['sp']) == 'NOT NULL') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . ' is not null';
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == 'BETWEEN') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " between ? and ?";
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " is not null";
                    $condParams[] =  &$condVals[$fieldName]['value'][0];
                    $condParams[] =  &$condVals[$fieldName]['value'][1];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == 'NOT') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " != ? ";
                    $condParams[] = $condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == 'CONTAINS') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " like ? ";
                    $condParams[] = '%' . $condVals[$fieldName]['value'] . '%';
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == '>') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " > ? ";
                    $condParams[] =  &$condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == '<') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " < ? ";
                    $condParams[] =  &$condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == '>=') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " >= ? ";
                    $condParams[] =  &$condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == '<=') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " >= ? ";
                    $condParams[] =  &$condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == '%VAL') {
                    $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " like ? ";
                    $condParams[] = "%" . $condVals[$fieldName]['value'];
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == 'IN') {
                    if (empty($condVals[$fieldName]['value'])) {
                        $condStrings[] = "1 != 1"; // condition that will never be true
                    } else {
                        $placeHolders = array();
                        foreach ($condVals[$fieldName]['value'] as $val) {
                            $placeHolders[] = '?';
                            $condParams[] = $val;
                        }
                        $condStrings[] = $this->fields[$dbKey][$queryName][$fieldName] . " in (" . implode(',', $placeHolders) . ")";
                    }
                } elseif (isset($condVals[$fieldName]['op']) and strtoupper($condVals[$fieldName]['op']) == 'OR') {
                    if (empty($condVals[$fieldName]['value'])) {
                        $condStrings[] = "1 != 1"; // condition that will never be true
                    } else {
                        $placeHolders = array();
                        $string = '('; // start of the cond string, open prenthesis
                        foreach ($condVals[$fieldName]['value'] as $val) {
                            if (is_array($val)) { // ( (field1 = val1 and field2 = val2) or (field1 = val1 and field2 = val2) )
                                $string .= '(';
                                foreach ($val as $subFieldName => $subVal) {
                                    $string .= $this->fields[$dbKey][$queryName][$subFieldName] . ' = ? and ';
                                    $condParams[] = $subVal;
                                }
                                $string = rtrim($string, ' and ');
                                $string .= ') or ';
                            } else { // (field1 = val1 or field2 = val2)
                                $string .= $this->fields[$dbKey][$queryName][$fieldName] . ' = ? or ';
                                $condParams[] = $val;
                            }
                        }
                        $string = rtrim($string, 'or ');
                        $string .= ')';
                        $condStrings[] = $string;
                    }
                }
            }
        }
    }

    /*
	* return array of ext databases with ext_db_id
	*/
    public function getDBArray($db = 'microMDNames')
    {
        return $this->dbPrefxs[$db];
    }

}

/* End of file mshc_connector.php */
/* Location: ./application/libraries/mshc_connector.php */
