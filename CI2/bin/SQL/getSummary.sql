ALTER FUNCTION "MicroMD"."get@dbprefix@Summary" (@databaseName nvarchar(128), @guarantorID int, @caseNo int, @practiceID int, @patientNo int)
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
  PayorNo int,
  FinancialClass int
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
  PayorNo int,
  FinancialClass int
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
  PayorNo int,
  FinancialClass int
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
  PayorNo int,
  FinancialClass int
)
*/
/*
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
  FinancialClass int 
)
*/
/*
@tbl6a table
(   
  dkDBPracGuarPatSeqChargeLine varchar(128),     
  dkDBPracGuarPatSeq varchar(128),     
  dkDBPracGuarPatCase varchar(128),     
  DatabaseName varchar(128),     
  CostCenterID int,     
  PaymentTypeID int,     
  PaymentAmount NUMERIC(7,2),     
  RefundAmount NUMERIC(7,2),     
  AdjustmentChargeAmount NUMERIC(7,2),     
  AdjustmentWriteoffAmount NUMERIC(7,2),
  FinancialClass int         
)
*/
/*
@tbl6b table
( 
  dkDBPracGuarPatSeqLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),    
  Company varchar(128),    
  DatabaseName varchar(128),    
  IsCharge int, --note:  here you see the first use of the “IsCharge” field.
  ChargeAmount NUMERIC(7,2),    
  ProcedureCode varchar(6),    
  FinancialClass int,    
  FinancialClassDesc varchar(32),    
  PaymentAmount int,    
  RefundAmount int,    
  AdjustmentChargeAmount int,    
  AdjustmentWriteoffAmount int,
  Unit NUMERIC(5,2)
) 
*/
/*
@tbl6c table
(
  dkDBPracGuarPatSeqChargeLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),
  Company varchar(128),     
  DatabaseName varchar(128),     
  IsCharge int,  --note:  of course this is 0 for the union since Query A is only non-charge type transactions.
  ChargeAmount NUMERIC(7,2), --note:  same as above
  emptyCol1 varchar(1),  --note:  “ProcedureCode” field for the union.  Blank since for non-charges it doesn’t matter.
  emptyCol2 varchar(1),  --note:  “FinancialClass” field for the union.  Same as above.
  emptyCol3 varchar(1),  --note:  “FinancialClassDesc” field for the union.  Same as above.
  PaymentAmount NUMERIC(7,2),     
  RefundAmount NUMERIC(7,2),     
  AdjustmentChargeAmount NUMERIC(7,2),     
  AdjustmentWriteoffAmount NUMERIC(7,2),     
  Unit NUMERIC(5,2)
)
*/
/*
@tbl6d table 
(
 dkDBPracGuarPatSeqLine varchar(128),    
 dkDBPracGuarPatSeq varchar(128),    
 dkDBPracGuarPatCase varchar(128),
 UniqueCaseID int,    
 Company varchar(128),    
 DatabaseName varchar(128),   
 IsCharge int,   
 ProcedureCode varchar(6),    
 FinancialClass int,    
 FinancialClassDesc varchar(128),    
 ChargeAmount NUMERIC(7,2),    
 PaymentAmount NUMERIC(7,2),    
 RefundAmount NUMERIC(7,2),    
 AdjustmentChargeAmount NUMERIC(7,2),    
 AdjustmentWriteoffAmount NUMERIC(7,2),    
 TotalUnits NUMERIC(7,2)
)
*/
/*
@tbl6e table
(
  dkDBPracGuarPatSeqLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),    
  dkDBCostCenter varchar(1), --dbo.udfCreateKey(c.DatabaseName, ch2.CostCenterID, '',  '',  '', '', '')  as dkDBCostCenter, 
  dkUniqueCaseCompany varchar(1), -- ISNULL(dbo.udfCreateKey(c.UniqueCaseID, c.Company, '', '', '', '',  ''), '') as dkUniqueCaseCompany,    
  dkDBProviderRendering varchar(128),
  UniqueCaseID varchar(128),    
  Company varchar(128),    
  DatabaseName varchar(128),    
  PracticeID int,    
  GuarantorID int,    
  PatientNo int,    
  SequenceNo int,    
  LineNum int,    
  ServiceDate datetime,    
  CostCenterID int,    
  IsCharge int,    
  ProcedureCode varchar(6),    
  FinancialClass int,    
  FinancialClassDesc varchar(128),    
  ChargeAmount NUMERIC(7,2),    
  PaymentAmount NUMERIC(7,2),    
  RefundAmount NUMERIC(7,2),    
  AdjustmentChargeAmount NUMERIC(7,2),    
  AdjustmentWriteoffAmount NUMERIC(7,2),    
  TotalUnits   NUMERIC(7,2)
)
*/

@caseSummary table -- 6
(
  dkDBPracGuarPatSeqLine varchar(128),
  dkDBPracGuarPatSeq varchar(128),   
  dkDBPracGuarPatCase varchar(128), 
  dkDBCostCenter varchar(128),             
  dkUniqueCaseCompany varchar(128),              
  dkDBProviderRendering varchar(128),
  UniqueCaseID varchar(128),  
  Company varchar(128),            
  DatabaseName varchar(128),
  PracticeID int,           
  GuarantorID int,      
  PatientNo int,          
  SequenceNo int,     
  LineNum int,             
  IsCharge int,             
  ProcedureCode varchar(8),               
  FinancialClass int,    
  FinancialClassDesc varchar(32),          
  ChargeAmount NUMERIC(7,2),
  PaymentAmount NUMERIC(7,2),            
  RefundAmount NUMERIC(7,2),               
  AdjustmentChargeAmount NUMERIC(7,2),        
  AdjustmentWriteoffAmount NUMERIC(7,2),     
  ServiceDate DATETIME,       
  CostCenterID int,    
  AgingDays int,          
  TotalUnits NUMERIC(7,2),
    ServiceFacility INT
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
    ServiceFacility INT
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
    ServiceFacility INT
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
    ServiceFacility INT
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
    ServiceFacility INT
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
    ServiceFacility INT
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
  FinancialClass int,
    ServiceFacility INT
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
  FinancialClass int,
    ServiceFacility INT
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
  FinancialClass int,
    ServiceFacility INT
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
  FinancialClass int,
    ServiceFacility INT
);

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
  PayorNo int,
  FinancialClass int,
    ServiceFacility INT
);

declare @tbl6a table
(   
  dkDBPracGuarPatSeqChargeLine varchar(128),     
  dkDBPracGuarPatSeq varchar(128),     
  dkDBPracGuarPatCase varchar(128),     
  DatabaseName varchar(128),     
  CostCenterID int,     
  PaymentTypeID int,     
  PaymentAmount NUMERIC(7,2),     
  RefundAmount NUMERIC(7,2),     
  AdjustmentChargeAmount NUMERIC(7,2),     
  AdjustmentWriteoffAmount NUMERIC(7,2),
  FinancialClass int,
    ServiceFacility INT
);

declare @tbl6b table
( 
  dkDBPracGuarPatSeqLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),    
  Company varchar(128),    
  DatabaseName varchar(128),    
  IsCharge int, --note:  here you see the first use of the “IsCharge” field.
  ChargeAmount NUMERIC(7,2),    
  ProcedureCode varchar(6),    
  FinancialClass int,    
  FinancialClassDesc varchar(32),    
  PaymentAmount int,    
  RefundAmount int,    
  AdjustmentChargeAmount int,    
  AdjustmentWriteoffAmount int,
  Unit NUMERIC(5,2),
    ServiceFacility INT
);

declare @tbl6c table
(
  dkDBPracGuarPatSeqChargeLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),
  Company varchar(128),     
  DatabaseName varchar(128),     
  IsCharge int,  --note:  of course this is 0 for the union since Query A is only non-charge type transactions.
  ChargeAmount NUMERIC(7,2), --note:  same as above
  emptyCol1 varchar(1),  --note:  “ProcedureCode” field for the union.  Blank since for non-charges it doesn’t matter.
  emptyCol2 varchar(1),  --note:  “FinancialClass” field for the union.  Same as above.
  emptyCol3 varchar(1),  --note:  “FinancialClassDesc” field for the union.  Same as above.
  PaymentAmount NUMERIC(7,2),     
  RefundAmount NUMERIC(7,2),     
  AdjustmentChargeAmount NUMERIC(7,2),     
  AdjustmentWriteoffAmount NUMERIC(7,2),     
  Unit NUMERIC(5,2),
    ServiceFacility INT
);

declare @tbl6d_a table
(
  dkDBPracGuarPatCase varchar(128)
);

declare @tbl6d table 
(
 dkDBPracGuarPatSeqLine varchar(128),    
 dkDBPracGuarPatSeq varchar(128),    
 dkDBPracGuarPatCase varchar(128),
 UniqueCaseID int,    
 Company varchar(128),    
 DatabaseName varchar(128),   
 IsCharge int,   
 ProcedureCode varchar(6),    
 FinancialClass int,    
 FinancialClassDesc varchar(128),    
 ChargeAmount NUMERIC(7,2),    
 PaymentAmount NUMERIC(7,2),    
 RefundAmount NUMERIC(7,2),    
 AdjustmentChargeAmount NUMERIC(7,2),    
 AdjustmentWriteoffAmount NUMERIC(7,2),    
 TotalUnits NUMERIC(7,2),
    ServiceFacility INT
);

declare @tbl6e table
(
  dkDBPracGuarPatSeqLine varchar(128),    
  dkDBPracGuarPatSeq varchar(128),    
  dkDBPracGuarPatCase varchar(128),    
  dkDBCostCenter varchar(1), --dbo.udfCreateKey(c.DatabaseName, ch2.CostCenterID, '',  '',  '', '', '')  as dkDBCostCenter, 
  dkUniqueCaseCompany varchar(1), -- ISNULL(dbo.udfCreateKey(c.UniqueCaseID, c.Company, '', '', '', '',  ''), '') as dkUniqueCaseCompany,    
  dkDBProviderRendering varchar(128),
  UniqueCaseID varchar(128),    
  Company varchar(128),    
  DatabaseName varchar(128),    
  PracticeID int,    
  GuarantorID int,    
  PatientNo int,    
  SequenceNo int,    
  LineNum int,    
  ServiceDate datetime,    
  CostCenterID int,    
  IsCharge int,    
  ProcedureCode varchar(6),    
  FinancialClass int,    
  FinancialClassDesc varchar(128),    
  ChargeAmount NUMERIC(7,2),    
  PaymentAmount NUMERIC(7,2),    
  RefundAmount NUMERIC(7,2),    
  AdjustmentChargeAmount NUMERIC(7,2),    
  AdjustmentWriteoffAmount NUMERIC(7,2),    
  TotalUnits   NUMERIC(7,2),
    ServiceFacility INT
);
/*
declare @caseSummary table -- 6
(
  dkDBPracGuarPatSeqLine varchar(128),
  dkDBPracGuarPatSeq varchar(128),   
  dkDBPracGuarPatCase varchar(128), 
  dkDBCostCenter varchar(128),             
  dkUniqueCaseCompany varchar(128),              
  dkDBProviderRendering varchar(128),
  UniqueCaseID varchar(128),  
  Company varchar(128),            
  DatabaseName varchar(128),
  PracticeID int,           
  GuarantorID int,      
  PatientNo int,          
  SequenceNo int,     
  LineNum int,             
  IsCharge int,             
  ProcedureCode varchar(8),               
  FinancialClass int,    
  FinancialClassDesc varchar(32),          
  ChargeAmount NUMERIC(7,2),
  PaymentAmount NUMERIC(7,2),            
  RefundAmount NUMERIC(7,2),               
  AdjustmentChargeAmount NUMERIC(7,2),        
  AdjustmentWriteoffAmount NUMERIC(7,2),     
  ServiceDate DATETIME,       
  CostCenterID int,    
  AgingDays int,          
  TotalUnits NUMERIC(7,2)
);
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
    tr.description,
    tr.unit,
    th.service_facility
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
    tr.description,
    tr.unit,
    th.service_facility
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
    tr.description,
    tr.unit,
    th.service_facility
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
    tr.description,
    tr.unit,
    th.service_facility
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
    tr.description,
    tr.unit,
    th.service_facility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
    th.service_facility as ServiceFacility
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
        tr.FinancialClass,
        tr.ServiceFacility
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
        tr.FinancialClass,
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
        tr.FinancialClass,
        tr.ServiceFacility
FROM @tblRefundNonCharge tr 
JOIN @tblRefundTransactionDetail td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
union all
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
        tr.FinancialClass,
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
        tr.FinancialClass,
        tr.ServiceFacility
FROM @tblAdjustmentNonCharge tr 
JOIN @tblAdjustmentTransactionDetail td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
union all
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
        tr.FinancialClass,
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
        tr.FinancialClass,
        tr.ServiceFacility
FROM @tblAdjustmentWriteoffNonCharge tr 
JOIN @tblAdjustmentWriteoffTransactionDetail td
  ON tr.dkDBPracGuarPatSeqLine = td.dkDBPracGuarPatSeqDetLine
union all
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
        tr.FinancialClass,
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
  FinancialClass,
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
  FinancialClass,
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
  FinancialClass,
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
  FinancialClass,
    ServiceFacility
FROM @tblAdjustmentWriteoffAll 


-------------------------------------------------------------------------------------------------------
------------------------------------------ 6 ----------------------------------------------------------

insert into @tbl6a
SELECT CASE  WHEN tr.dkDBPracGuarPatSeqChargeLine = '0' THEN tr.dkDBPracGuarPatSeqLine
             ELSE tr.dkDBPracGuarPatSeqChargeLine
       END as dkDBPracGuarPatSeqChargeLine,     
       tr.dkDBPracGuarPatSeq,     
       tr.dkDBPracGuarPatCase,     
       tr.DatabaseName,     
       tr.CostCenterID,     
       tr.PaymentTypeID,     
       CASE WHEN PaymentTypeID = 3 THEN (tr.AmountApplied + tr.AmountUnapplied)
             ELSE 0
       END  as PaymentAmount,     
       CASE WHEN PaymentTypeID = 4 THEN (tr.AmountApplied + tr.AmountUnapplied)
            ELSE 0
       END  as RefundAmount,     
       CASE  WHEN PaymentTypeID = 5 THEN (tr.AmountApplied + tr.AmountUnapplied)
              ELSE 0
        END as AdjustmentChargeAmount,     
        CASE WHEN PaymentTypeID = 6 THEN (tr.AmountApplied + tr.AmountUnapplied)
              ELSE 0
        END as AdjustmentWriteoffAmount,
        tr.FinancialClass,
        tr.ServiceFacility
FROM @TransactionAllNonCharge  tr

insert into @tbl6b
SELECT ch.dkDBPracGuarPatSeqLine,    
        ch.dkDBPracGuarPatSeq,    
        ch.dkDBPracGuarPatCase,    
        ch.Company,    
        ch.DatabaseName,    
        1 as IsCharge, --note:  here you see the first use of the “IsCharge” field.
        ch.Fee as ChargeAmount,    
        ch.ProcedureCode,    
        ch.FinancialClass,    
        ch.FinancialClassDesc,    
        0 as PaymentAmount,    
        0 as RefundAmount,    
        0 as AdjustmentChargeAmount,    
        0 as AdjustmentWriteoffAmount,    
        ch.Unit,
        ch.ServiceFacility
FROM @tblCharge  ch

insert into @tbl6c
SELECT a.dkDBPracGuarPatSeqChargeLine,    
        a.dkDBPracGuarPatSeq,    
        a.dkDBPracGuarPatCase,
        CASE WHEN a.DatabaseName = 'AMM_LIVE'
              THEN CASE WHEN a.ServiceFacility BETWEEN 101 AND 121 
                                  THEN 'NTI' 
                        WHEN a.FinancialClass = 8
                        THEN 'RX'--                      
                         ELSE  'PT'
                    END --
              WHEN       a.DatabaseName = 'MD'   and a.CostCenterID IN (10,11)
            THEN 'MRI'
            ELSE a.DatabaseName
      END as Company ,     
       DatabaseName,     
       0 as IsCharge,  --note:  of course this is 0 for the union since Query A is only non-charge type transactions.
       0 as ChargeAmount, --note:  same as above
       '',  --note:  “ProcedureCode” field for the union.  Blank since for non-charges it doesn’t matter.
       '',  --note:  “FinancialClass” field for the union.  Same as above.
       '',  --note:  “FinancialClassDesc” field for the union.  Same as above.
       SUM(a.PaymentAmount)  as PaymentAmount,     
       SUM(a.RefundAmount) as RefundAmount,     
       SUM(a.AdjustmentChargeAmount)  as AdjustmentChargeAmount,     
       SUM(a.AdjustmentWriteoffAmount) as AdjustmentWriteoffAmount,     
       0 as Unit,
       a.ServiceFacility
FROM @tbl6a  a
GROUP BY a.dkDBPracGuarPatSeqChargeLine,     
          a.dkDBPracGuarPatSeq,     
          a.dkDBPracGuarPatCase,     
          a.DatabaseName,     
          a.CostCenterID,
          a.FinancialClass,
          a.ServiceFacility
  

insert into @tbl6d_a
select  database_name + '.' + cast(practice_id as varchar(10)) + '.' + cast(guarantor_id as varchar(10)) + '.' + cast(patient_no as varchar(10)) + '.' + cast(case_no as varchar(10)) 
from AMM_LIVE.dbo.pm_patient_other_info 
where ISNULL(database_name, '') = 'AMM_LIVE'
  and database_name = @databaseName
  and guarantor_id = @guarantorID 
  and case_no = @caseNo 
  and practice_id = @practiceID 
  and patient_no = @patientNo 
union all
select  database_name + '.' + cast(practice_id as varchar(10)) + '.' + cast(guarantor_id as varchar(10)) + '.' + cast(patient_no as varchar(10)) + '.' + cast(case_no as varchar(10)) 
from BWR.dbo.pm_patient_other_info 
where ISNULL(database_name, '') = 'BWR'
  and database_name = @databaseName
  and guarantor_id = @guarantorID 
  and case_no = @caseNo 
  and practice_id = @practiceID 
  and patient_no = @patientNo 
union all
select  database_name + '.' + cast(practice_id as varchar(10)) + '.' + cast(guarantor_id as varchar(10)) + '.' + cast(patient_no as varchar(10)) + '.' + cast(case_no as varchar(10)) 
from MD.dbo.pm_patient_other_info 
where ISNULL(database_name, '') = 'MD'
  and database_name = @databaseName
  and guarantor_id = @guarantorID 
  and case_no = @caseNo 
  and practice_id = @practiceID 
  and patient_no = @patientNo 
union all
select  database_name + '.' + cast(practice_id as varchar(10)) + '.' + cast(guarantor_id as varchar(10)) + '.' + cast(patient_no as varchar(10)) + '.' + cast(case_no as varchar(10)) 
from MRI.dbo.pm_patient_other_info 
where ISNULL(database_name, '') = 'MRI'
  and database_name = @databaseName
  and guarantor_id = @guarantorID 
  and case_no = @caseNo 
  and practice_id = @practiceID 
  and patient_no = @patientNo 
union all
select  database_name + '.' + cast(practice_id as varchar(10)) + '.' + cast(guarantor_id as varchar(10)) + '.' + cast(patient_no as varchar(10)) + '.' + cast(case_no as varchar(10)) 
from PT.dbo.pm_patient_other_info
where ISNULL(database_name, '') = 'PT'
  and database_name = @databaseName
  and guarantor_id = @guarantorID 
  and case_no = @caseNo 
  and practice_id = @practiceID 
  and patient_no = @patientNo 
       
insert into @tbl6d
select dkDBPracGuarPatSeqLine,    
        dkDBPracGuarPatSeq,    
        dkDBPracGuarPatCase,
        UniqueCaseID,    
        max(right_company) as Company,    
        DatabaseName,   
        max(IsCharge),   
        max(ProcedureCode),    
        max(FinancialClass),    
        max(FinancialClassDesc),    
        sum(ChargeAmount),    
        sum(PaymentAmount),    
        sum(RefundAmount),    
        sum(AdjustmentChargeAmount),    
        sum(AdjustmentWriteoffAmount),    
        sum(TotalUnits),
        ServiceFacility
from(SELECT b.dkDBPracGuarPatSeqLine,    
            b.dkDBPracGuarPatSeq,    
            b.dkDBPracGuarPatCase,
            null as UniqueCaseID,    
            b.Company,    
            b.DatabaseName,   
            MAX(b.IsCharge) as IsCharge,   
            MAX(b.ProcedureCode) as ProcedureCode,    
            MAX(b.FinancialClass) as FinancialClass,    
            MAX(b.FinancialClassDesc) as FinancialClassDesc,    
            SUM(b.ChargeAmount) as ChargeAmount,    
            SUM(b.PaymentAmount) as PaymentAmount,    
            SUM(b.RefundAmount) as RefundAmount,    
            SUM(b.AdjustmentChargeAmount) as AdjustmentChargeAmount,    
            SUM(b.AdjustmentWriteoffAmount) as AdjustmentWriteoffAmount,    
            SUM(b.Unit) as TotalUnits,
            case when len(max(b.ProcedureCode)) > 1 then b.Company else null end as right_company,
            b.ServiceFacility
    FROM  (select *
            from @tbl6b                      
            UNION ALL
            select *
            from @tbl6c
          ) b
    JOIN @tbl6d_a as poi 
      ON b.dkDBPracGuarPatCase = poi.dkDBPracGuarPatCase  
    GROUP BY b.dkDBPracGuarPatSeqLine,  
              b.dkDBPracGuarPatSeq,  
              b.dkDBPracGuarPatCase,  
            --  b.UniqueCaseID,  
              b.DatabaseName ,
              b.Company,
              b.ServiceFacility
) as innerTbl
group by dkDBPracGuarPatSeqLine,  
             dkDBPracGuarPatSeq,  
              dkDBPracGuarPatCase,  
            UniqueCaseID,  
              DatabaseName,
              ServiceFacility





insert into @tbl6e
SELECT c.dkDBPracGuarPatSeqLine,    
        c.dkDBPracGuarPatSeq,    
        c.dkDBPracGuarPatCase,    
        null as dkDBCostCenter, --dbo.udfCreateKey(c.DatabaseName, ch2.CostCenterID, '',  '',  '', '', '')  as dkDBCostCenter, 
        null as dkUniqueCaseCompany, -- ISNULL(dbo.udfCreateKey(c.UniqueCaseID, c.Company, '', '', '', '',  ''), '') as dkUniqueCaseCompany,    
        ch2.dkDBProviderRendering,
        c.UniqueCaseID,    
        c.Company,    
        c.DatabaseName,    
        ch2.PracticeID,    
        ch2.GuarantorID,    
        ch2.PatientNo,    
        ch2.SequenceNo,    
        ch2.LineNum,    
        ch2.ServiceDate,    
        ch2.CostCenterID,    
        c.IsCharge,    
        c.ProcedureCode,    
        c.FinancialClass,    
        c.FinancialClassDesc,    
        c.ChargeAmount,    
        c.PaymentAmount,    
        c.RefundAmount,    
        c.AdjustmentChargeAmount,    
        c.AdjustmentWriteoffAmount,    
        c.TotalUnits,
        c.ServiceFacility
FROM @tbl6d c
LEFT JOIN @tblCharge ch2 
  ON c.dkDBPracGuarPatSeqLine = ch2.dkDBPracGuarPatSeqLine





insert into @caseSummary
SELECT  ISNULL(ci.dkDBPracGuarPatSeqLine, '') as dkDBPracGuarPatSeqLine,     
        ci.dkDBPracGuarPatSeq,     
        ci.dkDBPracGuarPatCase, 
            
      --  COALESCE(
       --     ci.dkDBCostCenter, 
       --     dbo.udfCreateKey(ci.DatabaseName, py.CostCenterID, '', '', '', '', ''), 
       --     dbo.udfCreateKey(ci.DatabaseName, rf.CostCenterID, '', '', '', '', ''), 
      --      dbo.udfCreateKey(ci.DatabaseName, adjc.CostCenterID, '', '', '', '', ''), 
      --      dbo.udfCreateKey(ci.DatabaseName, adjw.CostCenterID, '', '', '', '', '')           
       -- ) as dkDBCostCenter, 
        null as dkDBCostCenter,
        ci.dkUniqueCaseCompany,     
        ci.dkDBProviderRendering,     
        ci.UniqueCaseID,     
        ci.Company,     
        ci.DatabaseName,      
        COALESCE(ci.PracticeID,py.PracticeID,rf.PracticeID,adjc.PracticeID,adjw.PracticeID) as PracticeID,     
        COALESCE(ci.GuarantorID, py.GuarantorID, rf.GuarantorID, adjc.GuarantorID, adjw.GuarantorID) as GuarantorID,     
        COALESCE(ci.PatientNo,py.PatientNo, rf.PatientNo, adjc.PatientNo, adjw.PatientNo) as PatientNo,     
        COALESCE(ci.SequenceNo,py.SequenceNo, rf.SequenceNo, adjc.SequenceNo, adjw.SequenceNo) as SequenceNo,     
        COALESCE(ci.LineNum,py.LineNum, rf.LineNum, adjc.LineNum, adjw.LineNum) as LineNum,     
        ci.IsCharge,     
        ci.ProcedureCode,     
        ci.FinancialClass,     
        ci.FinancialClassDesc,     
        ci.ChargeAmount,     
        ci.PaymentAmount,     
        ci.RefundAmount,     
        ci.AdjustmentChargeAmount,     
        ci.AdjustmentWriteoffAmount,     
        COALESCE(ci.ServiceDate,py.ServiceDate, rf.ServiceDate, adjc.ServiceDate, adjw.ServiceDate) as ServiceDate,     
        COALESCE(ci.CostCenterID,py.CostCenterID, rf.CostCenterID, adjc.CostCenterID, adjw.CostCenterID) as CostCenterID,     
        DATEDIFF(d, COALESCE(ci.ServiceDate,py.ServiceDate, rf.ServiceDate, adjc.ServiceDate, adjw.ServiceDate), GETDATE()) as AgingDays,     
        ci.TotalUnits,
        ci.ServiceFacility
FROM @tbl6e as ci
LEFT JOIN @tblPaymentNonCharge py 
  ON ci.dkDBPracGuarPatSeqLine = py.dkDBPracGuarPatSeqLine
LEFT JOIN @tblRefundNonCharge rf
  ON ci.dkDBPracGuarPatSeqLine = rf.dkDBPracGuarPatSeqLine
LEft jOIN @tblAdjustmentNonCharge adjc
  ON ci.dkDBPracGuarPatSeqLine = adjc.dkDBPracGuarPatSeqLine
LEFT JOIN @tblAdjustmentWriteoffNonCharge adjw 
  ON ci.dkDBPracGuarPatSeqLine = adjw.dkDBPracGuarPatSeqLine


return
end
