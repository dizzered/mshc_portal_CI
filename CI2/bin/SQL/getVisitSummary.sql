ALTER FUNCTION "MicroMD"."get@dbprefix@VisitSummary" (@databaseName nvarchar(128), @guarantorID int, @caseNo int, @practiceID int, @patientNo int, @Company varchar(128), @ServiceDate datetime, @SequenceNo int)
returns                                                                  -- returns
/*
@tblCharge table(
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),  
  dkDBPracGuarPat VARCHAR(128),          
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure VARCHAR(128),
  dkDBProcedure VARCHAR(128),              
  dkDBProviderRendering VARCHAR(128),            
  dkDBProviderBilling VARCHAR(128),  
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
  RenderingProviderID  int,
  BillingProviderID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  FinancialClassDesc    varchar(32),
  Company VARCHAR(128),
  UserName VARCHAR(10),
  Description VARCHAR(40),
  Unit NUMERIC(5,2),
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128)
)
*/
/*
@tblPaymentNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblAdjustmentNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblRefundNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblAdjustmentChargeNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblPaymentTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblAdjustmentTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblRefundTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/
/*
@tblAdjustmentChargeTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/

@visitSummary table (
  OriginalChargeDate datetime,             
  ServiceDate  datetime,              
  PaymentAmount  numeric(7,2),
  
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128),
  Description VARCHAR(40),
  Type varchar(16),
  IsCharge bit
)

AS 
begin


----------------------------------------------------------------------------------------------
------------------------------- Declares -----------------------------------------------------



declare @tblCharge table(
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),  
  dkDBPracGuarPat VARCHAR(128),          
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure VARCHAR(128),
  dkDBProcedure VARCHAR(128),              
  dkDBProviderRendering VARCHAR(128),            
  dkDBProviderBilling VARCHAR(128),  
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
  RenderingProviderID  int,
  BillingProviderID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  FinancialClassDesc    varchar(32),
  Company VARCHAR(128),
  UserName VARCHAR(10),
  Description VARCHAR(40),
  Unit NUMERIC(5,2),
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128)
);

declare @tblPaymentNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

declare @tblAdjustmentNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

declare @tblRefundNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

declare @tblAdjustmentChargeNonCharge table (
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeq   VARCHAR(128),
  dkDBPracGuarPatCase VARCHAR(128),
  dkDBPracProcedure      VARCHAR(128),
  dkDBProcedure VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT ,
  GuarantorID INT ,
  PatientNo INT,
  CaseNo INT,
  SequenceNo INT,
  LineNum INT,
  ServiceDate DATETIME,
  ProcedureCode VARCHAR(6),
  CostCenterID INT,
  Fee NUMERIC(7,2),
  PaymentTypeID INT,
  FinancialClass INT,
  PayorNo INT, 
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

DECLARE @tblPaymentTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

DECLARE @tblAdjustmentTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

DECLARE @tblRefundTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

DECLARE @tblAdjustmentChargeTransactionDetail TABLE (
  dkDBPracGuarPatSeqLineDetLine VARCHAR(128),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  dkDBPracGuarPatSeqDetLine VARCHAR(128),
  dkDBPracGuarPatSeq VARCHAR(128),
  DatabaseName VARCHAR(128),
  PracticeID INT,
  GuarantorID INT,
  PatientNo INT,
  SequenceNo INT,
  LineNum INT,
  DetailLineNum int,
  Amount numeric(7,2),
  DetailPaymentTypeID int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);
/*
declare @visitSummary table (
  OriginalChargeDate datetime,             
  ServiceDate  datetime,              
  PaymentAmount  numeric(7,2),
  
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128),
  Description VARCHAR(40),
  Type varchar(16),
  IsCharge bit
);
*/
--------------------------------------------------------------- 1.0 -----------------------------------------------------------------------------
INSERT INTO @tblCharge ---------------------------------------- 1.1
SELECT  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,              
  null,             
  null,                                                                
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
  null,
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
  pm_proc.financial_class,
  '',
CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END,
    tr.user_name,
    pm_proc.procedure_desc,
    tr.unit,
    mdl.display_name,
    mmd_up.last_name + ', ' + mmd_up.first_name,
    pm_proc.procedure_code
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join AMM_LIVE.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations AS mdl WITH (NOLOCK)
  on mdl.database_name = th.database_name
  and mdl.cost_center_id = th.cost_center
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo
  and CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END = @Company
    and tr.service_date_from = @ServiceDate
    and th.sequence_no = @SequenceNo
union   -------------------------------------------------------------- 1.2
SELECT  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,              
  null,             
  null,                                                                
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
  null,
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
  pm_proc.financial_class,
  '',
  CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END,
    tr.user_name,
    pm_proc.procedure_desc,
    tr.unit,
    mdl.display_name,
    mmd_up.last_name + ', ' + mmd_up.first_name,
    pm_proc.procedure_code
FROM BWR.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN BWR.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN BWR.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join BWR.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations AS mdl WITH (NOLOCK)
  on mdl.database_name = th.database_name
  and mdl.cost_center_id = th.cost_center
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo
  and CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END = @Company
    and tr.service_date_from = @ServiceDate
    and th.sequence_no = @SequenceNo
union --------------------------------------------------------------- 1.3
SELECT  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,              
  null,             
  null,                                                                
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
  null,
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
  pm_proc.financial_class,
  '',
  CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END,
    tr.user_name,
    pm_proc.procedure_desc,
    tr.unit,
    mdl.display_name,
    mmd_up.last_name + ', ' + mmd_up.first_name,
    pm_proc.procedure_code
FROM MD.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MD.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MD.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MD.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations AS mdl WITH (NOLOCK)
  on mdl.database_name = th.database_name
  and mdl.cost_center_id = th.cost_center
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo
  and CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END = @Company
    and tr.service_date_from = @ServiceDate
    and th.sequence_no = @SequenceNo
union ------------------------------------------------------------------ 1.4
SELECT  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,              
  null,             
  null,                                                                
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
  null,
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
  pm_proc.financial_class,
  '',
  CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END,
    tr.user_name,
    pm_proc.procedure_desc,
    tr.unit,
    mdl.display_name,
    mmd_up.last_name + ', ' + mmd_up.first_name,
    pm_proc.procedure_code
FROM MRI.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MRI.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MRI.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MRI.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations AS mdl WITH (NOLOCK)
  on mdl.database_name = th.database_name
  and mdl.cost_center_id = th.cost_center
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo
  and CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END = @Company
    and tr.service_date_from = @ServiceDate
    and th.sequence_no = @SequenceNo
union ----------------------------------------------------------------- 1.5
SELECT  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,              
  null,             
  null,                                                                
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
  null,
  tr.billing_provider,
  tr.fee,
  pt.PaymentTypeID,
  pm_proc.financial_class,
  '',
  CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END,
    tr.user_name,
    pm_proc.procedure_desc,
    tr.unit,
    mdl.display_name,
    mmd_up.last_name + ', ' + mmd_up.first_name,
    pm_proc.procedure_code
FROM PT.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN PT.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN PT.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join PT.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations AS mdl WITH (NOLOCK)
  on mdl.database_name = th.database_name
  and mdl.cost_center_id = th.cost_center
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo
  and CASE WHEN th.database_name = 'MD'
        THEN CASE WHEN th.cost_center IN (10,11) THEN 'MRI'
                ELSE 'MD'
            END
        WHEN th.database_name <> 'AMM_LIVE'
        THEN th.database_name
        WHEN th.service_facility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
        WHEN th.practice_id = 1 and th.cost_center = 23
        THEN 'BWR'
        WHEN th.practice_id = 1 and pm_proc.financial_class = 8
        THEN 'RX'
        WHEN th.practice_id = 1 and th.cost_center IN (25,35)
        THEN 'MRI'
        WHEN th.practice_id = 1 and pm_proc.financial_class IN (2,6,7)
        THEN 'MD'           
        WHEN th.practice_id = 1 and pm_proc.financial_class = 3       
        THEN 'PT'
        ELSE 'PT'
    END = @Company
    and tr.service_date_from = @ServiceDate
    and th.sequence_no = @SequenceNo
    
----------------------------------------------------------------------------- 2, 2.1 -------------------------------------------------------------------------
INSERT INTO @tblPaymentNonCharge -------------------------------------------- 2.1.1
SELECT   th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join AMM_LIVE.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ----------------------------------------------------------------------------- 2.1.2
SELECT   th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM BWR.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN BWR.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN BWR.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join BWR.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ----------------------------------------------------------------- 2.1.3
SELECT   th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                       
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MD.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MD.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MD.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MD.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ------------------------------------------------------------------------------ 2.1.4
SELECT   th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MRI.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MRI.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MRI.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MRI.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ----------------------------------------------------------------------------- 2.1.5
SELECT   th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                       
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM PT.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN PT.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN PT.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join PT.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
 
----------------------------------------------------------------------- 2.2 --------------------------------------------
INSERT INTO @tblAdjustmentNonCharge ----------------------------------- 2.2.1
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join AMM_LIVE.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ---------------------------------------------------------------- 2.2.2
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM BWR.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN BWR.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN BWR.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join BWR.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ---------------------------------------------------------- 2.2.3
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MD.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MD.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MD.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MD.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ---------------------------------------------------------------- 2.2.4
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MRI.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MRI.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MRI.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MRI.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ------------------------------------------------------------------ 2.2.5
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                       
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM PT.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN PT.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN PT.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join PT.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo

------------------------------------------------------------ 2.3 ------------------------------------------
INSERT INTO @tblRefundNonCharge ------------------------------------------------------------------- 2.3.1
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join AMM_LIVE.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ------------------------------------------------------------------------------- 2.3.2
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                       
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM BWR.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN BWR.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN BWR.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join BWR.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ------------------------------------------------------------ 2.3.3
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MD.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MD.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MD.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MD.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union ---------------------------------------------------------------------------- 2.3.4
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MRI.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MRI.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MRI.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MRI.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union --------------------------------------------------------------------------- 2.3.5
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                      
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM PT.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN PT.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN PT.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join PT.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
  
-------------------------------------------------- 2.4  
INSERT INTO @tblAdjustmentChargeNonCharge
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.4.1
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM AMM_LIVE.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join AMM_LIVE.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.4.2
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM BWR.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN BWR.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN BWR.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join BWR.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.4.3
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MD.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MD.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MD.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MD.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.4.4
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM MRI.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN MRI.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN MRI.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join MRI.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
union
SELECT    th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)), 
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),          
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),       
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.4.5
    th.database_name        AS DatabaseName,
    th.practice_id          AS PracticeID,
    th.guarantor_id         AS GuarantorID,
    th.patient_no           AS PatientNo,
    th.case_no              AS CaseNo,
    th.sequence_no          AS SequenceNo,
    tr.line_no              AS LineNum,
    tr.service_date_from    AS ServiceDate,
    tr.procedure_code       AS ProcedureCode,
    th.cost_center          AS CostCenterID,
    tr.fee                  AS Fee,
    pt.PaymentTypeID        AS PaymentTypeID,
    pm_proc.financial_class AS FinancialClass,
    tr.payor_no             AS PayorNo,
    p.policy_group_name,
    tr.posting_date,
    pm_proc.procedure_desc 
FROM PT.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN PT.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN PT.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join PT.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and p.database_name = th.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID
  and th.case_no = @caseNo
  and th.practice_id = @practiceID
  and th.patient_no = @patientNo 
  and th.sequence_no = @SequenceNo
  
--------------------------------------------------------------------------- 3, 3.1 ----------------------------------------------
--------------------------------------------------------------------------- 3.1.1 
insert into @tblPaymentTransactionDetail
SELECT    null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 3
  AND ISNULL(td.database_name, '') = 'AMM_LIVE'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ---------------------------------------------------------------------------- 3.1.2
SELECT    null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 3
  AND ISNULL(td.database_name, '') = 'BWR'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union --------------------------------------------------------------------- 3.1.3
SELECT    null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 3
  AND ISNULL(td.database_name, '') = 'MD'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ----------------------------------------------------------------- 3.1.4
SELECT    null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 3
  AND ISNULL(td.database_name, '') = 'MRI'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ------------------------------------------------------------------------ 3.1.5
SELECT    null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 3
  AND ISNULL(td.database_name, '') = 'PT'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo

 
------------------------------------------------------------------------ 3.2 ----------------------------------------
insert into @tblAdjustmentTransactionDetail ---------------------------- 3.2.1
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 6
  AND ISNULL(td.database_name, '') = 'AMM_LIVE'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union -------------------------------------------------- 3.2.2
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 6
  AND ISNULL(td.database_name, '') = 'BWR'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ---------------------------------------------------------------- 3.2.3
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 6
  AND ISNULL(td.database_name, '') = 'MD'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union --------------------------------------------------------------------------- 3.2.4
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 6
  AND ISNULL(td.database_name, '') = 'MRI'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ------------------------------------------------------------------ 3.2.5
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 6
  AND ISNULL(td.database_name, '') = 'PT'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo

------------------------------------------------------------------------ 3.3 ----------------------------------------
insert into @tblRefundTransactionDetail ---------------------------- 3.3.1
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 4
  AND ISNULL(td.database_name, '') = 'AMM_LIVE'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union -------------------------------------------------- 3.3.2
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 4
  AND ISNULL(td.database_name, '') = 'BWR'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ---------------------------------------------------------------- 3.3.3
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 4
  AND ISNULL(td.database_name, '') = 'MD'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union --------------------------------------------------------------------------- 3.3.4
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 4
  AND ISNULL(td.database_name, '') = 'MRI'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ------------------------------------------------------------------ 3.3.5
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 4
  AND ISNULL(td.database_name, '') = 'PT'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
  
------------------------------------------------------------------------ 3.4 ----------------------------------------
insert into @tblAdjustmentChargeTransactionDetail ---------------------------- 3.4.1
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 5
  AND ISNULL(td.database_name, '') = 'AMM_LIVE'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union -------------------------------------------------- 3.4.2
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 5
  AND ISNULL(td.database_name, '') = 'BWR'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ---------------------------------------------------------------- 3.4.3
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                    
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 5
  AND ISNULL(td.database_name, '') = 'MD'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union --------------------------------------------------------------------------- 3.4.4
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 5
  AND ISNULL(td.database_name, '') = 'MRI'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo
union ------------------------------------------------------------------ 3.4.5
SELECT     null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine,
  tr.dkDBPracGuarPatSeq,                                                                                   
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,  
    tr.Code ,
    tr.PostingDate,
    tr.Description
FROM PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
  ON  td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID = 5
  AND ISNULL(td.database_name, '') = 'PT'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID
  and tr.CaseNo = @caseNo
  and td.practice_id = @practiceID
  and td.patient_no = @patientNo  
  
---------------------------------------------------------------------- 4.0 -------------------------------------
insert into @visitSummary
select ServiceDate,            
        ServiceDate,             
        Fee,
         Office,
        Provider,
        Code,
        Description,
        'charge',
        1
from @tblCharge
union
SELECT ch.ServiceDate,            
        pd.PostingDate,            
        pd.Amount,
         null,
        null,
        pd.Code,
        pd.Description,
        'payment',
        0
FROM @tblPaymentTransactionDetail  pd
LEFT JOIN @tblCharge ch
  ON ch.dkDBPracGuarPatSeqLine = pd.dkDBPracGuarPatSeqLine
union
SELECT ch.ServiceDate,            
        ad.PostingDate,            
        ad.Amount,
         null,
        null,
        ad.Code,
        ad.Description,
        'adjustment',
        0
FROM @tblAdjustmentTransactionDetail ad
LEFT JOIN @tblCharge ch
  ON ch.dkDBPracGuarPatSeqLine = ad.dkDBPracGuarPatSeqLine
union
SELECT ch.ServiceDate,            
        ad.PostingDate,            
        ad.Amount,
         null,
        null,
        ad.Code,
        ad.Description,
        'refund',
        0
FROM @tblRefundTransactionDetail ad
LEFT JOIN @tblCharge ch
  ON ch.dkDBPracGuarPatSeqLine = ad.dkDBPracGuarPatSeqLine
union
SELECT ch.ServiceDate,            
        ad.PostingDate,            
        ad.Amount,
         null,
        null,
        ad.Code,
        ad.Description,
        'adjustment_charge',
        0
FROM @tblAdjustmentChargeTransactionDetail ad
LEFT JOIN @tblCharge ch
  ON ch.dkDBPracGuarPatSeqLine = ad.dkDBPracGuarPatSeqLine

return
end
