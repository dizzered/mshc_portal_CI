ALTER FUNCTION "MicroMD"."get@dbprefix@UnappliedPayments" (@databaseName nvarchar(128), @guarantorID int, @caseNo int, @practiceID int, @patientNo int)
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
  Unit NUMERIC(5,2)
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
  PayorNo INT
)
*/
/*
@tblRefundNonCharge table 
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
  PayorNo INT
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
  PayorNo INT
)
*/
/*
@tblAdjustmentWriteoffNonCharge table 
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
  PayorNo INT
)
*/
/*
@tblPaymentTransactionDetail TABLE -- 3a
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
  DetailPaymentTypeID int
)
*/
/*
@tblRefundTransactionDetail TABLE -- 3b
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
  DetailPaymentTypeID int
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
  DetailPaymentTypeID int
)
*/
/*
@tblAdjustmentWriteoffTransactionDetail TABLE -- 3d
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
  DetailPaymentTypeID int
)
*/
/*
@tblTotalAmountByPaymentDetailLine TABLE                                                    -- 4a
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
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
  PayorNo int
)
*/
/*
@tblTotalAmountByRefundDetailLine TABLE                                                    -- 4b
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
)
*/
/*
@tblRefundAll table
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
  PayorNo int
)
*/
/*
@tblTotalAmountByWriteoffDetailLine TABLE                                                    -- 4c
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
)
*/
/*
@tblAdjustmentAll table
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
  PayorNo int
)
*/
/*
@tblTotalAmountByAdjustmentWriteoffDetailLine TABLE                                                    -- 4d
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
)
*/
/*
@tblAdjustmentWriteoffAll table
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
  PayorNo int
)
*/

@TransactionAllNonCharge table -- 5
(
  dkDBPracGuarPatSeqLine varchar(128),           
  dkDBPracGuarPatSeq   varchar(128),
  dkDBPracGuarPatSeqChargeLine      varchar(128),       
  dkDBPracGuarPatCase varchar(128),
  DatabaseName nvarchar(128),
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
  AmountApplied numeric(7,2),              
  AmountUnapplied numeric(7,2),        
  PayorNo int,
  ServiceFacility int
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
  Unit NUMERIC(5,2)
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
  ServiceFacility int
);

declare @tblRefundNonCharge table 
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
  ServiceFacility int
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
  ServiceFacility int
);

declare @tblAdjustmentWriteoffNonCharge table 
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
  ServiceFacility int
);

DECLARE @tblPaymentTransactionDetail TABLE -- 3a
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
  DetailPaymentTypeID int
);

DECLARE @tblRefundTransactionDetail TABLE -- 3b
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
  DetailPaymentTypeID int
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
  DetailPaymentTypeID int
);

DECLARE @tblAdjustmentWriteoffTransactionDetail TABLE -- 3d
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
  DetailPaymentTypeID int
);

DECLARE @tblTotalAmountByPaymentDetailLine TABLE                                                    -- 4a
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
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
  ServiceFacility int
);

DECLARE @tblTotalAmountByRefundDetailLine TABLE                                                    -- 4b
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
);

Declare @tblRefundAll table
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
  ServiceFacility int
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
  ServiceFacility int
);

DECLARE @tblTotalAmountByAdjustmentWriteoffDetailLine TABLE                                                    -- 4d
(
  dkDBPracGuarPatSeqDetLine     varchar(50) PRIMARY KEY,     
  TotalAmount                   numeric(8,2)
);

Declare @tblAdjustmentWriteoffAll table
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
  ServiceFacility int
);
/*
Declare @TransactionAllNonCharge table -- 5
(
  dkDBPracGuarPatSeqLine varchar(128),           
  dkDBPracGuarPatSeq   varchar(128),
  dkDBPracGuarPatSeqChargeLine      varchar(128),       
  dkDBPracGuarPatCase varchar(128),
  DatabaseName nvarchar(128),
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
  AmountApplied numeric(7,2),              
  AmountUnapplied numeric(7,2),        
  PayorNo int
);
*/


---------------------------------------------------------------------------------------
------------------------------------------ 1 ------------------------------------------
-- Charge Table 



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
    th.service_facility     as ServiceFacility
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
    th.service_facility     as ServiceFacility  
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
    th.service_facility     as ServiceFacility  
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
    th.service_facility     as ServiceFacility  
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 3
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo


-- RefundNonCharge Table 

INSERT INTO @tblRefundNonCharge
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 4
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 5
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo
;






-- AdjustmentWriteoffNonCharge Table 
INSERT INTO @tblAdjustmentWriteoffNonCharge
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
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
    th.service_facility     as ServiceFacility  
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
WHERE ISNULL(pt.PaymentTypeID, 0) = 6
  AND ISNULL(th.database_name, '') = 'PT'
  and th.database_name = @databaseName
  and tr.guarantor_id = @guarantorID 
  and th.case_no = @caseNo 
  and th.practice_id = @practiceID 
  and th.patient_no = @patientNo



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
    PaymentTypeID
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
    PaymentTypeID
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
    PaymentTypeID
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
    PaymentTypeID
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
    PaymentTypeID
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



-- Refund Transaction Detail ------------------------------------------------------------  3b
insert into @tblRefundTransactionDetail
SELECT   
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                     -- 3b.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID AS DetailPaymentTypeID
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblRefundNonCharge as tr 
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
UNION ALL
SELECT        
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                               -- 3b.2
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblRefundNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                  -- 3b.3
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblRefundNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL 
SELECT   
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                 -- 3b.4
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblRefundNonCharge as tr
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT     
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                               -- 3b.5
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblRefundNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE  PaymentTypeID = 4
AND ISNULL(td.database_name, '') = 'PT'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 
;



-- Writeoff Transaction Detail ------------------------------------------------------------  3c.0
insert into @tblAdjustmentTransactionDetail
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                      -- 3c.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
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
UNION ALL
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                      -- 3c.2
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
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
WHERE PaymentTypeID = 5
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
  tr.dkDBPracGuarPatSeq,                                                                                 -- 3c.3
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL 
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                   -- 3c.4
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
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
WHERE PaymentTypeID = 5
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
  tr.dkDBPracGuarPatSeq,                                                                             -- 3c.5
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
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
WHERE  PaymentTypeID = 5
AND ISNULL(td.database_name, '') = 'PT'
and td.database_name = @databaseName
and td.guarantor_id = @guarantorID 
and tr.CaseNo = @caseNo 
and td.practice_id = @practiceID 
and td.patient_no = @patientNo 





-- Adjustment Writeoff Transaction Detail ------------------------------------------------------------  3d.0
insert into @tblAdjustmentWriteoffTransactionDetail
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                        -- 3d.1
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID AS DetailPaymentTypeID
FROM AMM_LIVE.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentWriteoffNonCharge as tr 
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
UNION ALL
SELECT  
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                      -- 3d.2
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    BWR.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentWriteoffNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT     
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                 -- 3d.3
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    MD.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentWriteoffNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
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
UNION ALL 
SELECT     
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
 dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                 -- 3d.4
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    MRI.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentWriteoffNonCharge as tr
ON
    td.database_name = tr.DatabaseName
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
UNION ALL
SELECT   
  null,
  td.database_name + '.' + cast(td.practice_id as varchar(10)) + '.' + cast(td.guarantor_id as varchar(10)) + '.' + cast(td.patient_no as varchar(10)) + '.' + cast(td.sequence_no as varchar(10)) + '.' + cast(td.line_no as varchar(10)),
  dkDBPracGuarPatSeqLine, 
  tr.dkDBPracGuarPatSeq,                                                                                  -- 3d.5
    DatabaseName,
    PracticeID,
    GuarantorID,
    PatientNo,
    SequenceNo,
    td.line_no,
    td.detail_line_no,
    Amount,
    PaymentTypeID
FROM
    PT.dbo.pm_transaction_detail td WITH (NOLOCK)
JOIN @tblAdjustmentWriteoffNonCharge as tr 
ON
    td.database_name = tr.DatabaseName
AND td.practice_id = tr.PracticeID
AND td.guarantor_id = tr.GuarantorID
AND td.patient_no = tr.PatientNo
AND td.sequence_no = tr.SequenceNo
AND td.detail_line_no = tr.LineNum
WHERE  PaymentTypeID = 6
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
        SUM(Amount)
FROM @tblPaymentTransactionDetail as ptd 
GROUP BY dkDBPracGuarPatSeqDetLine
;


insert into @tblPaymentAll
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
        tr.ServiceFacility
FROM @tblPaymentNonCharge tr 
LEFT JOIN @tblTotalAmountByPaymentDetailLine td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
WHERE       tr.Fee - ISNULL(td.TotalAmount ,0) <> 0


-----------------------------------------------------------------------------------------------------------
-- Refund All                                                                
INSERT INTO @tblTotalAmountByRefundDetailLine
SELECT dkDBPracGuarPatSeqDetLine,   
        SUM(Amount)
FROM @tblRefundTransactionDetail as ptd 
GROUP BY dkDBPracGuarPatSeqDetLine
;

insert into @tblRefundAll
SELECT 
tr.dkDBPracGuarPatSeqLine,     
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
        tr.ServiceFacility
FROM @tblRefundNonCharge tr 
LEFT JOIN @tblTotalAmountByRefundDetailLine td
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
        tr.ServiceFacility
FROM @tblAdjustmentNonCharge tr 
LEFT JOIN @tblTotalAmountByAdjustmentDetailLine td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
WHERE       tr.Fee - ISNULL(td.TotalAmount ,0) <> 0


-----------------------------------------------------------------------------------------------------
-- AdjustmentWriteoff All                                                               4d
INSERT INTO @tblTotalAmountByAdjustmentWriteoffDetailLine
SELECT dkDBPracGuarPatSeqDetLine,   
        SUM(Amount)
FROM @tblAdjustmentWriteoffTransactionDetail as ptd 
GROUP BY dkDBPracGuarPatSeqDetLine
;

insert into @tblAdjustmentWriteoffAll
SELECT tr.dkDBPracGuarPatSeqLine,     
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
        tr.ServiceFacility
FROM @tblAdjustmentWriteoffNonCharge tr 
LEFT JOIN @tblTotalAmountByAdjustmentWriteoffDetailLine td
   ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
WHERE       tr.Fee - ISNULL(td.TotalAmount ,0) <> 0



-------------------------------------------------------------------------------------------------
------------------------------------------------ 5 ----------------------------------------------
insert into @TransactionAllNonCharge
SELECT 
  dkDBPracGuarPatSeqLine,           
  dkDBPracGuarPatSeq,
  dkDBPracGuarPatSeqChargeLine,       
  dkDBPracGuarPatCase,
  DatabaseName,
  PracticeID,        
  GuarantorID,    
  PatientNo,      
  CaseNo,        
  SequenceNo,  
  LineNum,       
  ChargeLineNum,             
  PaymentTypeID,             
  CostCenterID,  
  ServiceDate,     
  AmountApplied,              
  AmountUnapplied,        
  PayorNo,
  ServiceFacility
FROM @tblPaymentAll 
union all
SELECT 
  dkDBPracGuarPatSeqLine,           
  dkDBPracGuarPatSeq,
  dkDBPracGuarPatSeqChargeLine,       
  dkDBPracGuarPatCase,
  DatabaseName,
  PracticeID,        
  GuarantorID,    
  PatientNo,      
  CaseNo,        
  SequenceNo,  
  LineNum,       
  ChargeLineNum,             
  PaymentTypeID,             
  CostCenterID,  
  ServiceDate,     
  AmountApplied,              
  AmountUnapplied,        
  PayorNo,
  ServiceFacility
FROM @tblRefundAll 
union all
SELECT 
  dkDBPracGuarPatSeqLine,           
  dkDBPracGuarPatSeq,
  dkDBPracGuarPatSeqChargeLine,       
  dkDBPracGuarPatCase,
  DatabaseName,
  PracticeID,        
  GuarantorID,    
  PatientNo,      
  CaseNo,        
  SequenceNo,  
  LineNum,       
  ChargeLineNum,             
  PaymentTypeID,             
  CostCenterID,  
  ServiceDate,     
  AmountApplied,              
  AmountUnapplied,        
  PayorNo,
  ServiceFacility
FROM @tblAdjustmentAll 
union all
SELECT 
  dkDBPracGuarPatSeqLine,           
  dkDBPracGuarPatSeq,
  dkDBPracGuarPatSeqChargeLine,       
  dkDBPracGuarPatCase,
  DatabaseName,
  PracticeID,        
  GuarantorID,    
  PatientNo,      
  CaseNo,        
  SequenceNo,  
  LineNum,       
  ChargeLineNum,             
  PaymentTypeID,             
  CostCenterID,  
  ServiceDate,     
  AmountApplied,              
  AmountUnapplied,        
  PayorNo,
  ServiceFacility
FROM @tblAdjustmentWriteoffAll 



return
end
