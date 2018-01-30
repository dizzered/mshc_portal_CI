ALTER FUNCTION "MicroMD"."get@dbprefix@Statement" (@databaseName nvarchar(128), @guarantorID int, @caseNo int, @practiceID int, @patientNo int)
returns                                                           -- returns
/*
@tblCharge table
(
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
@tblPaymentNonCharge table 
(
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
@tblAdjustmentNonCharge table 
(
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
@tblPaymentTransactionDetail TABLE -- 3c
(
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
@tblAdjustmentTransactionDetail TABLE -- 3c
(
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
@tblPaymentAll table
(
  dkDBPracGuarPatSeqLine varchar(128),           
  dkDBPracGuarPatSeq   varchar(128),
  dkDBPracGuarPatSeqChargeLine      varchar(128),       
  dkDBPracGuarPatCase varchar(128),
  DatabaseName  varchar(128),
  PracticeID   int,        
  GuarantorID  int,    
  PatientNo    int,      
  CaseNo       int,        
  SequenceNo   int,  
  LineNum      int,       
  ChargeLineNum int,             
  PaymentTypeID int,             
  CostCenterID  int,  
  ServiceDate  datetime,     
  AmountApplied NUMERIC(7,2),              
  AmountUnapplied NUMERIC(7,2),        
  PayorNo int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
)
*/

@visitSummary table
(
  OriginalChargeDate datetime,             
  ServiceDate  datetime,              
  PaymentAmount  numeric(7,2),
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128),
  Description VARCHAR(40),
  Type varchar(16),
  Company varchar(16),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  LineNum int,
  CostCenterID int,
  LocName nvarchar(128),
  Modifier1 varchar(2)
)

AS 
begin


----------------------------------------------------------------------------------------------
------------------------------- Declares -----------------------------------------------------

declare @tblCharge table
(
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
  Code varchar(128),
  Modifier1 varchar(2)
);

declare @tblPaymentNonCharge table 
(
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

declare @tblAdjustmentNonCharge table 
(
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

declare @tblPaymentTransactionDetail TABLE -- 3a
(
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

DECLARE @tblAdjustmentTransactionDetail TABLE -- 3c
(
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

DECLARE @tblTotalAmountByPaymentDetailLine TABLE                                                    -- 4a
(
  dkDBPracGuarPatSeqDetLine     varchar(50),     
  TotalAmount                   numeric(8,2)
);

Declare @tblPaymentAll table
(
  dkDBPracGuarPatSeqLine varchar(128),           
  dkDBPracGuarPatSeq   varchar(128),
  dkDBPracGuarPatSeqChargeLine      varchar(128),       
  dkDBPracGuarPatCase varchar(128),
  DatabaseName  varchar(128),
  PracticeID   int,        
  GuarantorID  int,    
  PatientNo    int,      
  CaseNo       int,        
  SequenceNo   int,  
  LineNum      int,       
  ChargeLineNum int,             
  PaymentTypeID int,             
  CostCenterID  int,  
  ServiceDate  datetime,     
  AmountApplied NUMERIC(7,2),              
  AmountUnapplied NUMERIC(7,2),        
  PayorNo int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);

DECLARE @tblTotalAmountByAdjustmentDetailLine TABLE                                                    -- 4c
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
);

Declare @tblAdjustmentAll table
(
  dkDBPracGuarPatSeqLine varchar(128),           
  dkDBPracGuarPatSeq   varchar(128),
  dkDBPracGuarPatSeqChargeLine      varchar(128),       
  dkDBPracGuarPatCase varchar(128),
  DatabaseName  varchar(128),
  PracticeID   int,        
  GuarantorID  int,    
  PatientNo    int,      
  CaseNo       int,        
  SequenceNo   int,  
  LineNum      int,       
  ChargeLineNum int,             
  PaymentTypeID int,             
  CostCenterID  int,  
  ServiceDate  datetime,     
  AmountApplied NUMERIC(7,2),              
  AmountUnapplied NUMERIC(7,2),        
  PayorNo int,
  Code varchar(128),
  PostingDate datetime,
  Description varchar(128)
);
/*
declare @visitSummary table
(
  OriginalChargeDate datetime,             
  ServiceDate  datetime,              
  PaymentAmount  numeric(7,2),
  Office varchar(128),
  Provider varchar(128),
  Code varchar(128),
  Description VARCHAR(40),
  Type varchar(16),
  Company varchar(16),
  dkDBPracGuarPatSeqLine VARCHAR(128),
  LineNum int,
  CostCenterID int,
  LocName nvarchar(128)
)
*/



---------------------------------------------------------------------------------------
------------------------------------------ 1 ------------------------------------------
-- Charge Table 

INSERT INTO @tblCharge 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),            
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,               
  null,              
  null,                                                                -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  ISNULL(pc.procedure_insurance_code, tr.procedure_code) as tr_procedure_code,
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
    sf.service_facility_desc,
    mmd_up.last_name,
    ISNULL(pc.procedure_insurance_code, pm_proc.procedure_code) as pm_proc_procedure_code,
    tr.procedure_mod1
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
left join AMM_LIVE.dbo.pm_service_facility as sf with (nolock)
  on sf.database_name = th.database_name
  and sf.service_facility_id = th.service_facility
left join AMM_LIVE.dbo.pm_patient_plan  as pp WITH (NOLOCK)
  on th.database_name = pp.database_name
  and th.practice_id = pp.practice_id
  and th.guarantor_id = pp.guarantor_id
  and th.patient_no = pp.patient_no
  and th.plan_line_no = pp.line_no 
left join  AMM_LIVE.dbo.pm_plan as p WITH (NOLOCK)
  on p.database_name = pp.database_name
  and p.plan_id = pp.plan_id
left join AMM_LIVE.dbo.pm_procedure_code as pc WITH (NOLOCK)
  on pc.database_name = p.database_name
  and pc.insurance_class = p.insurance_class 
  and pc.procedure_code = tr.procedure_code
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo 
UNION ALL
SELECT                                                   -- 1.2
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),            
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,               
  null,              
  null,                                                                -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  ISNULL(pc.procedure_insurance_code, tr.procedure_code) as tr_procedure_code,
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
    sf.service_facility_desc,
    mmd_up.last_name,
    ISNULL(pc.procedure_insurance_code, pm_proc.procedure_code) as pm_proc_procedure_code,
    tr.procedure_mod1
FROM bwr.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join bwr.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
left join bwr.dbo.pm_service_facility as sf with (nolock)
  on sf.database_name = th.database_name
  and sf.service_facility_id = th.service_facility
left join bwr.dbo.pm_patient_plan  as pp WITH (NOLOCK)
  on th.database_name = pp.database_name
  and th.practice_id = pp.practice_id
  and th.guarantor_id = pp.guarantor_id
  and th.patient_no = pp.patient_no
  and th.plan_line_no = pp.line_no 
left join  bwr.dbo.pm_plan as p WITH (NOLOCK)
  on p.database_name = pp.database_name
  and p.plan_id = pp.plan_id
left join bwr.dbo.pm_procedure_code as pc WITH (NOLOCK)
  on pc.database_name = p.database_name
  and pc.insurance_class = p.insurance_class 
  and pc.procedure_code = tr.procedure_code
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo 
UNION ALL
SELECT    
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),            
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,               
  null,              
  null,                                                                -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  ISNULL(pc.procedure_insurance_code, tr.procedure_code) as tr_procedure_code,
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
    sf.service_facility_desc,
    mmd_up.last_name,
    ISNULL(pc.procedure_insurance_code, pm_proc.procedure_code) as pm_proc_procedure_code,
    tr.procedure_mod1
FROM md.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join md.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
left join md.dbo.pm_service_facility as sf with (nolock)
  on sf.database_name = th.database_name
  and sf.service_facility_id = th.service_facility
left join md.dbo.pm_patient_plan  as pp WITH (NOLOCK)
  on th.database_name = pp.database_name
  and th.practice_id = pp.practice_id
  and th.guarantor_id = pp.guarantor_id
  and th.patient_no = pp.patient_no
  and th.plan_line_no = pp.line_no 
left join  md.dbo.pm_plan as p WITH (NOLOCK)
  on p.database_name = pp.database_name
  and p.plan_id = pp.plan_id
left join md.dbo.pm_procedure_code as pc WITH (NOLOCK)
  on pc.database_name = p.database_name
  and pc.insurance_class = p.insurance_class 
  and pc.procedure_code = tr.procedure_code
WHERE  pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo 
UNION ALL
SELECT     
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),            
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,               
  null,              
  null,                                                                -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  ISNULL(pc.procedure_insurance_code, tr.procedure_code) as tr_procedure_code,
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
    sf.service_facility_desc,
    mmd_up.last_name,
    ISNULL(pc.procedure_insurance_code, pm_proc.procedure_code) as pm_proc_procedure_code,
    tr.procedure_mod1
FROM mri.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join mri.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
left join mri.dbo.pm_service_facility as sf with (nolock)
  on sf.database_name = th.database_name
  and sf.service_facility_id = th.service_facility
left join mri.dbo.pm_patient_plan  as pp WITH (NOLOCK)
  on th.database_name = pp.database_name
  and th.practice_id = pp.practice_id
  and th.guarantor_id = pp.guarantor_id
  and th.patient_no = pp.patient_no
  and th.plan_line_no = pp.line_no 
left join  mri.dbo.pm_plan as p WITH (NOLOCK)
  on p.database_name = pp.database_name
  and p.plan_id = pp.plan_id
left join mri.dbo.pm_procedure_code as pc WITH (NOLOCK)
  on pc.database_name = p.database_name
  and pc.insurance_class = p.insurance_class 
  and pc.procedure_code = tr.procedure_code
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo 
UNION ALL
SELECT    
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)),            
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,               
  null,              
  null,                                                                -- 1.1
  th.database_name,
  th.practice_id, 
  th.guarantor_id,
  th.patient_no,
  th.case_no,
  th.sequence_no,
  tr.line_no,
  tr.service_date_from,
  tr.posting_date,
  ISNULL(pc.procedure_insurance_code, tr.procedure_code) as tr_procedure_code,
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
    sf.service_facility_desc,
    mmd_up.last_name,
    ISNULL(pc.procedure_insurance_code, pm_proc.procedure_code) as pm_proc_procedure_code,
    tr.procedure_mod1
FROM pt.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join pt.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = th.database_name
  and mmd_up.provider_id = tr.billing_provider
left join pt.dbo.pm_service_facility as sf with (nolock)
  on sf.database_name = th.database_name
  and sf.service_facility_id = th.service_facility
left join pt.dbo.pm_patient_plan  as pp WITH (NOLOCK)
  on th.database_name = pp.database_name
  and th.practice_id = pp.practice_id
  and th.guarantor_id = pp.guarantor_id
  and th.patient_no = pp.patient_no
  and th.plan_line_no = pp.line_no 
left join  pt.dbo.pm_plan as p WITH (NOLOCK)
  on p.database_name = pp.database_name
  and p.plan_id = pp.plan_id
left join pt.dbo.pm_procedure_code as pc WITH (NOLOCK)
  on pc.database_name = p.database_name
  and pc.insurance_class = p.insurance_class 
  and pc.procedure_code = tr.procedure_code
WHERE pt.PaymentTypeID = 2
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo 

---------------------------------------------------------------------------------------
-------------------------------------- 2 ----------------------------------------------
-- PaymentNonCharge Table 
INSERT INTO @tblPaymentNonCharge
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM bwr.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join bwr.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM md.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN md.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN md.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join md.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM mri.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN mri.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN mri.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join mri.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM pt.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join pt.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo


-- tblAdjustmentNonCharge Table 
INSERT INTO @tblAdjustmentNonCharge
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) in (4,5,6)
  AND ISNULL(th.database_name, '') = 'AMM_LIVE'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM bwr.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join bwr.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) in (4,5,6)
  AND ISNULL(th.database_name, '') = 'BWR'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM md.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN md.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN md.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join md.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) in (4,5,6)
  AND ISNULL(th.database_name, '') = 'MD'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM mri.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
LEFT JOIN mri.dbo.pm_TRANSACTION AS tr WITH (NOLOCK)
  ON tr.practice_id = th.practice_id
  AND tr.guarantor_id = th.guarantor_id
  AND tr.patient_no = th.patient_no
  AND tr.sequence_no = th.sequence_no
  AND tr.database_name = th.database_name
LEFT JOIN mri.dbo.pm_procedure AS pm_proc WITH (NOLOCK)
  ON (pm_proc.practice_id = th.practice_id OR  pm_proc.practice_id = 9999)
  AND pm_proc.procedure_code = tr.procedure_code
  AND pm_proc.database_name = th.database_name
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@POSPaymentType AS pt WITH (NOLOCK)
  ON pt.pos = pm_proc.procedure_pos
left join mri.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) in (4,5,6)
  AND ISNULL(th.database_name, '') = 'MRI'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
union all 
SELECT   
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)) + '.' + cast(tr.line_no as varchar(10)),  
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.sequence_no as varchar(10)),           
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + cast(th.guarantor_id as varchar(10)) + '.' + cast(th.patient_no as varchar(10)) + '.' + cast(th.case_no as varchar(10)),        
  th.database_name + '.' + cast(th.practice_id as varchar(10)) + '.' + tr.procedure_code,
  th.database_name + '.' + tr.procedure_code,                                                        -- 2.1
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
FROM pt.dbo.pm_TRANSACTION_HEADER AS th WITH (NOLOCK)
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
left join pt.dbo.pm_plan as p with (nolock)
  on p.plan_id = tr.payor_no
  and th.database_name = p.database_name
WHERE ISNULL(pt.PaymentTypeID, 0) in (4,5,6)
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
;



------------------------------------------------------------------------------------------------
------------------------------------------ 3 ---------------------------------------------------
-- Paymant Transaction Detail
insert into @tblPaymentTransactionDetail
SELECT    
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
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
UNION ALL
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                        -- 3a.2
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT   
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                  -- 3a.3
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL 
SELECT            
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                         -- 3a.4
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT   
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                   -- 3a.5
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblPaymentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE  PaymentTypeID = 3
AND ISNULL(td.database_name, '') = 'PT'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 
;

-- Adjustment Transaction Detail ------------------------------------------------------------  3c.0
insert into @tblAdjustmentTransactionDetail
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
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
WHERE PaymentTypeID in (4,5,6)
AND ISNULL(td.database_name, '') = 'AMM_LIVE'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 
UNION ALL
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID in (4,5,6)
AND ISNULL(td.database_name, '') = 'BWR'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 
UNION ALL
SELECT    
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM  MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr 
  ON td.database_name = tr.DatabaseName
  AND td.practice_id = tr.PracticeID
  AND td.guarantor_id = tr.GuarantorID
  AND td.patient_no = tr.PatientNo
  AND td.sequence_no = tr.SequenceNo
  AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID in (4,5,6)
  AND ISNULL(td.database_name, '') = 'MD'
  and td.database_name = @databaseName
  and td.guarantor_id = @guarantorID 
  and tr.CaseNo = @caseNo 
  and td.practice_id = @practiceID 
  and td.patient_no = @patientNo 
UNION ALL 
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE PaymentTypeID in (4,5,6)
AND ISNULL(td.database_name, '') = 'MRI'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 
UNION ALL
SELECT        
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                    -- 3a.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID,
    tr.Code,
    tr.PostingDate,
    tr.Description 
FROM
    PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE  PaymentTypeID in (4,5,6)
AND ISNULL(td.database_name, '') = 'PT'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 







-------------------------------------------------------------------------------------------------------------
---------------------------------------------- 4 ------------------------------------------------------------
-- Payment All  
                                                             
INSERT INTO @tblTotalAmountByPaymentDetailLine
SELECT dkDBPracGuarPatSeqDetLine,   
        Amount
FROM @tblPaymentTransactionDetail as ptd 
;


insert into @tblPaymentAll
SELECT 
  tr.dkDBPracGuarPatSeqLine,     
  tr.dkDBPracGuarPatSeq,     
  td.dkDBPracGuarPatSeqLine,     
  tr.dkDBPracGuarPatCase,
  tr.DatabaseName,     
        tr.PracticeID,     
        tr.GuarantorID,     
        tr.PatientNo,     
        tr.CaseNo,     
        tr.SequenceNo,     
        tr.LineNum,     
        td.LineNum,     
        tr.PaymentTypeID,     
        tr.CostCenterID,     
        tr.ServiceDate,     
        td.Amount,     
        0,                 ---note:  this field is amount of zero for the union because this is the unapplied amount
        tr.PayorNo,
        td.Code,
        td.PostingDate,
        td.Description
FROM @tblPaymentNonCharge tr 
JOIN @tblPaymentTransactionDetail td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
union all
SELECT  tr.dkDBPracGuarPatSeqLine,     
tr.dkDBPracGuarPatSeq,     
'0',                           --note:  this field is char of ‘0’ for the union because there is no original charge for the unapplied payments.
 tr.dkDBPracGuarPatCase,
  tr.DatabaseName,      
        tr.PracticeID,     
        tr.GuarantorID,     
        tr.PatientNo,     
        tr.CaseNo,     
        tr.SequenceNo,     
        tr.LineNum,     
        0,                             --note:  this field is 0 for the union because there is no original charge for the unapplied payments.
        tr.PaymentTypeID,     
        tr.CostCenterID,     
        tr.ServiceDate,     
        0,                       --note:  this field is amount of 0 for the union because this is the applied amount
        tr.Fee - ISNULL(td.TotalAmount ,0) as UnappliedAmount,     
        tr.PayorNo,
        tr.Code,
        tr.PostingDate,
        tr.Description
FROM @tblPaymentNonCharge tr 
LEFT JOIN @tblTotalAmountByPaymentDetailLine td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
WHERE       tr.Fee - ISNULL(td.TotalAmount ,0) <> 0



----------------------------------------------------------------------------------------------------------
-- Adjustment All                                                               4c
INSERT INTO @tblTotalAmountByAdjustmentDetailLine
SELECT dkDBPracGuarPatSeqDetLine,   
        SUM(Amount)
FROM @tblAdjustmentTransactionDetail as ptd 
GROUP BY dkDBPracGuarPatSeqDetLine
;

insert into @tblAdjustmentAll
SELECT 
  tr.dkDBPracGuarPatSeqLine,     
  tr.dkDBPracGuarPatSeq,     
  td.dkDBPracGuarPatSeqLine,     
  tr.dkDBPracGuarPatCase,
  tr.DatabaseName,     
        tr.PracticeID,     
        tr.GuarantorID,     
        tr.PatientNo,     
        tr.CaseNo,     
        tr.SequenceNo,     
        tr.LineNum,     
        td.LineNum,     
        tr.PaymentTypeID,     
        tr.CostCenterID,     
        tr.ServiceDate,     
        td.Amount,     
        0,                 ---note:  this field is amount of zero for the union because this is the unapplied amount
        tr.PayorNo,
        td.Code,
        td.PostingDate,
        td.Description
FROM @tblAdjustmentNonCharge tr 
JOIN @tblAdjustmentTransactionDetail td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
union all
SELECT  tr.dkDBPracGuarPatSeqLine,     
tr.dkDBPracGuarPatSeq,     
'0',                           --note:  this field is char of ‘0’ for the union because there is no original charge for the unapplied payments.
 tr.dkDBPracGuarPatCase,
  tr.DatabaseName,      
        tr.PracticeID,     
        tr.GuarantorID,     
        tr.PatientNo,     
        tr.CaseNo,     
        tr.SequenceNo,     
        tr.LineNum,     
        0,                             --note:  this field is 0 for the union because there is no original charge for the unapplied payments.
        tr.PaymentTypeID,     
        tr.CostCenterID,     
        tr.ServiceDate,     
        0,                       --note:  this field is amount of 0 for the union because this is the applied amount
        tr.Fee - ISNULL(td.TotalAmount ,0) as UnappliedAmount,     
        tr.PayorNo,
        tr.Code,
        tr.PostingDate,
        tr.Description
FROM @tblAdjustmentNonCharge tr 
LEFT JOIN @tblTotalAmountByAdjustmentDetailLine td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
WHERE       tr.Fee - ISNULL(td.TotalAmount ,0) <> 0



insert into @visitSummary
select c.ServiceDate,             
        c.ServiceDate,              
        c.Fee,
        c.Office,
        c.Provider,
        c.Code,
        c.Description,
        'charge',
        c.Company,
        c.dkDBPracGuarPatSeqLine,
        c.LineNum,
        c.CostCenterID,
        l.display_name as LocName,
        c.Modifier1 
from @tblCharge as c
left join PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations as l 
  on l.database_name = c.DatabaseName
  and l.cost_center_id = c.CostCenterID
union
SELECT ch.ServiceDate,             
        py.PostingDate,             
        pd.Amount,
         null,
        null,
        py.Code,
        py.Description,
        'payment',
        ch.Company,
        ch.dkDBPracGuarPatSeqLine,
        py.ChargeLineNum,
        ch.CostCenterID,
        l.display_name as LocName,
        ch.Modifier1 
FROM @tblCharge ch
LEFT JOIN @tblPaymentTransactionDetail  pd
  ON ch.dkDBPracGuarPatSeqLine = pd.dkDBPracGuarPatSeqLine
LEFT JOIN @tblPaymentAll py
  ON pd.dkDBPracGuarPatSeqDetLine = py.dkDBPracGuarPatSeqLine
  and ch.LineNum = py.ChargeLineNum
left join PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations as l 
  on l.database_name = ch.DatabaseName
  and l.cost_center_id = ch.CostCenterID
union
SELECT ch.ServiceDate,             
        ay.PostingDate,             
        ad.Amount,
         null,
        null,
        ay.Code,
        ay.Description,
        'adjustment',
        ch.Company,
        ch.dkDBPracGuarPatSeqLine,
        ay.ChargeLineNum,
        ch.CostCenterID,
        l.display_name as LocName,
        ch.Modifier1 
FROM @tblCharge as ch
LEFT JOIN @tblAdjustmentTransactionDetail as ad
  ON ch.dkDBPracGuarPatSeqLine = ad.dkDBPracGuarPatSeqLine
LEFT JOIN @tblAdjustmentAll as ay
  ON ad.dkDBPracGuarPatSeqDetLine = ay.dkDBPracGuarPatSeqLine
  and ch.LineNum = ay.ChargeLineNum
left join PointsProcesses.MicroMD.tbl@dbprefix@MicroMDLocations as l 
  on l.database_name = ch.DatabaseName
  and l.cost_center_id = ch.CostCenterID
  

return
end
