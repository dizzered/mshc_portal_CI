ALTER FUNCTION "MicroMD"."get@dbprefix@PatientCasesBalances" (@DbAttyPairsInsertId varchar(64))
returns                                                           -- returns

@tblCharge table
(      
  UniqueCaseID VARCHAR(128),  
  FirstName VARCHAR(128), 
  LastName VARCHAR(128), 
  DOA DATETIME,
  DOB DATETIME,  
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  PostingDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  BillingProviderID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  UserName VARCHAR(10),
  Unit NUMERIC(5,2)
)

AS 
begin


----------------------------------------------------------------------------------------------
------------------------------- Declares -----------------------------------------------------



---------------------------------------------------------------------------------------
------------------------------------------ 1 ------------------------------------------
-- Charge Table 

INSERT INTO @tblCharge
SELECT   
  LEFT  (REPLACE(REPLACE(REPLACE(pnt.last_name, '.', ''),',',''),' ','')   +     '_____', 5) + '.'  + 
            LEFT(REPLACE(REPLACE(REPLACE(pnt.first_name, '.', ''),',',''),' ','') +     '_____', 5) + '.' + 
            ISNULL(CONVERT(CHAR(8),pnt.dob,112),'00000000') + '.' + 
            ISNULL(LEFT(lcc.master_code_short_description + '____' ,4), '____') + '.' + 
            ISNULL(CONVERT(CHAR(8), poi.injury_date, 112), '00000000') as UniqueCaseID, 
  pnt.first_name,     
  pnt.last_name, 
  poi.injury_date,      
  pnt.dob,                                                                              -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  tr.procedure_code,
  th.cost_center, 
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
    tr.user_name,
    tr.unit
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
join AMM_LIVE.dbo.pm_patient as pnt with (nolock)
  on pnt.practice_id = th.practice_id 
  and pnt.guarantor_id = th.guarantor_id 
  and pnt.patient_no = th.patient_no 
  and pnt.database_name = th.database_name
join AMM_LIVE.dbo.pm_patient_other_info as poi with (nolock)
  ON poi.practice_id = th.practice_id
  AND poi.guarantor_id = th.guarantor_id
  AND poi.patient_no = th.patient_no
  AND poi.case_no = th.case_no
  AND poi.database_name = th.database_name
join AMM_LIVE.dbo.pm_master_code as lcc with (nolock)
  on poi.case_category = lcc.master_code_ansi_value
  and poi.database_name = lcc.database_name
LEFT JOIN AMM_LIVE.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN AMM_LIVE.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and poi.employer_id is not null
  and lcc.master_code_type = 37 
  and exists (select *
							            from PointsProcesses.MicroMD.tbl@dbprefix@TempDbsAttys as tmp
							            where tmp.atty_id = poi.employer_id 
							            and tmp.db_name = th.database_name
							            and tmp.insert_id = @DbAttyPairsInsertId)
UNION ALL
SELECT                                                   -- 1.2
  LEFT  (REPLACE(REPLACE(REPLACE(pnt.last_name, '.', ''),',',''),' ','')   +     '_____', 5) + '.'  + 
            LEFT(REPLACE(REPLACE(REPLACE(pnt.first_name, '.', ''),',',''),' ','') +     '_____', 5) + '.' + 
            ISNULL(CONVERT(CHAR(8),pnt.dob,112),'00000000') + '.' + 
            ISNULL(LEFT(lcc.master_code_short_description + '____' ,4), '____') + '.' + 
            ISNULL(CONVERT(CHAR(8), poi.injury_date, 112), '00000000') as UniqueCaseID, 
  pnt.first_name,     
  pnt.last_name, 
  poi.injury_date,      
  pnt.dob,                                                                              -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  tr.procedure_code,
  th.cost_center, 
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
    tr.user_name,
    tr.unit
FROM bwr.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
join bwr.dbo.pm_patient as pnt with (nolock)
  on pnt.practice_id = th.practice_id 
  and pnt.guarantor_id = th.guarantor_id 
  and pnt.patient_no = th.patient_no 
  and pnt.database_name = th.database_name
join bwr.dbo.pm_patient_other_info as poi with (nolock)
  ON poi.practice_id = th.practice_id
  AND poi.guarantor_id = th.guarantor_id
  AND poi.patient_no = th.patient_no
  AND poi.case_no = th.case_no
  AND poi.database_name = th.database_name
join bwr.dbo.pm_master_code as lcc with (nolock)
  on poi.case_category = lcc.master_code_ansi_value
  and poi.database_name = lcc.database_name
LEFT JOIN bwr.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN bwr.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'BWR'
  and poi.employer_id is not null
  and lcc.master_code_type = 37 
  and exists (select *
							            from PointsProcesses.MicroMD.tbl@dbprefix@TempDbsAttys as tmp
							            where tmp.atty_id = poi.employer_id 
							            and tmp.db_name = th.database_name
							            and tmp.insert_id = @DbAttyPairsInsertId)
UNION ALL
SELECT    
  LEFT  (REPLACE(REPLACE(REPLACE(pnt.last_name, '.', ''),',',''),' ','')   +     '_____', 5) + '.'  + 
            LEFT(REPLACE(REPLACE(REPLACE(pnt.first_name, '.', ''),',',''),' ','') +     '_____', 5) + '.' + 
            ISNULL(CONVERT(CHAR(8),pnt.dob,112),'00000000') + '.' + 
            ISNULL(LEFT(lcc.master_code_short_description + '____' ,4), '____') + '.' + 
            ISNULL(CONVERT(CHAR(8), poi.injury_date, 112), '00000000') as UniqueCaseID, 
  pnt.first_name,     
  pnt.last_name, 
  poi.injury_date,      
  pnt.dob,                                                                              -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  tr.procedure_code,
  th.cost_center, 
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
    tr.user_name,
    tr.unit
FROM md.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
join md.dbo.pm_patient as pnt with (nolock)
  on pnt.practice_id = th.practice_id 
  and pnt.guarantor_id = th.guarantor_id 
  and pnt.patient_no = th.patient_no 
  and pnt.database_name = th.database_name
join md.dbo.pm_patient_other_info as poi with (nolock)
  ON poi.practice_id = th.practice_id
  AND poi.guarantor_id = th.guarantor_id
  AND poi.patient_no = th.patient_no
  AND poi.case_no = th.case_no
  AND poi.database_name = th.database_name
join md.dbo.pm_master_code as lcc with (nolock)
  on poi.case_category = lcc.master_code_ansi_value
  and poi.database_name = lcc.database_name
LEFT JOIN  md.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN  md.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (  pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
WHERE  pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MD'
  and poi.employer_id is not null
  and lcc.master_code_type = 37 
  and exists (select *
							            from PointsProcesses.MicroMD.tbl@dbprefix@TempDbsAttys as tmp
							            where tmp.atty_id = poi.employer_id 
							            and tmp.db_name = th.database_name
							            and tmp.insert_id = @DbAttyPairsInsertId)
UNION ALL
SELECT     
  LEFT  (REPLACE(REPLACE(REPLACE(pnt.last_name, '.', ''),',',''),' ','')   +     '_____', 5) + '.'  + 
            LEFT(REPLACE(REPLACE(REPLACE(pnt.first_name, '.', ''),',',''),' ','') +     '_____', 5) + '.' + 
            ISNULL(CONVERT(CHAR(8),pnt.dob,112),'00000000') + '.' + 
            ISNULL(LEFT(lcc.master_code_short_description + '____' ,4), '____') + '.' + 
            ISNULL(CONVERT(CHAR(8), poi.injury_date, 112), '00000000') as UniqueCaseID, 
  pnt.first_name,     
  pnt.last_name, 
  poi.injury_date,      
  pnt.dob,                                                                              -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  tr.procedure_code,
  th.cost_center, 
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
    tr.user_name,
    tr.unit
FROM mri.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
join mri.dbo.pm_patient as pnt with (nolock)
  on pnt.practice_id = th.practice_id 
  and pnt.guarantor_id = th.guarantor_id 
  and pnt.patient_no = th.patient_no 
  and pnt.database_name = th.database_name
join mri.dbo.pm_patient_other_info as poi with (nolock)
  ON poi.practice_id = th.practice_id
  AND poi.guarantor_id = th.guarantor_id
  AND poi.patient_no = th.patient_no
  AND poi.case_no = th.case_no
  AND poi.database_name = th.database_name
join mri.dbo.pm_master_code as lcc with (nolock)
  on poi.case_category = lcc.master_code_ansi_value
  and poi.database_name = lcc.database_name
LEFT JOIN  mri.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON  tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN mri.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (  pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON  pt.pos = pm_proc.procedure_pos
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MRI'
  and poi.employer_id is not null
  and lcc.master_code_type = 37 
  and exists (select *
							            from PointsProcesses.MicroMD.tbl@dbprefix@TempDbsAttys as tmp
							            where tmp.atty_id = poi.employer_id 
							            and tmp.db_name = th.database_name
							            and tmp.insert_id = @DbAttyPairsInsertId)
UNION ALL
SELECT    
  LEFT  (REPLACE(REPLACE(REPLACE(pnt.last_name, '.', ''),',',''),' ','')   +     '_____', 5) + '.'  + 
            LEFT(REPLACE(REPLACE(REPLACE(pnt.first_name, '.', ''),',',''),' ','') +     '_____', 5) + '.' + 
            ISNULL(CONVERT(CHAR(8),pnt.dob,112),'00000000') + '.' + 
            ISNULL(LEFT(lcc.master_code_short_description + '____' ,4), '____') + '.' + 
            ISNULL(CONVERT(CHAR(8), poi.injury_date, 112), '00000000') as UniqueCaseID, 
  pnt.first_name,     
  pnt.last_name, 
  poi.injury_date,      
  pnt.dob,                                                                              -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  tr.procedure_code,
  th.cost_center, 
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
    tr.user_name,
    tr.unit
FROM pt.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
join pt.dbo.pm_patient as pnt with (nolock)
  on pnt.practice_id = th.practice_id 
  and pnt.guarantor_id = th.guarantor_id 
  and pnt.patient_no = th.patient_no 
  and pnt.database_name = th.database_name
join pt.dbo.pm_patient_other_info as poi with (nolock)
  ON poi.practice_id = th.practice_id
  AND poi.guarantor_id = th.guarantor_id
  AND poi.patient_no = th.patient_no
  AND poi.case_no = th.case_no
  AND poi.database_name = th.database_name
join pt.dbo.pm_master_code as lcc with (nolock)
  on poi.case_category = lcc.master_code_ansi_value
  and poi.database_name = lcc.database_name
LEFT JOIN pt.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN pt.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'PT'
  and poi.employer_id is not null
  and lcc.master_code_type = 37 
  and exists (select *
							            from PointsProcesses.MicroMD.tbl@dbprefix@TempDbsAttys as tmp
							            where tmp.atty_id = poi.employer_id 
							            and tmp.db_name = th.database_name
							            and tmp.insert_id = @DbAttyPairsInsertId)
							            
							            
return
end
