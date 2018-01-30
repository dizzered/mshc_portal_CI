ALTER FUNCTION "MicroMD"."get@dbprefix@CronDocuments" (@startDate datetime, @endDate datetime)
returns                                                            -- returns

/*
@docstarTbl table
(
  LDocID_ int,
  AcctNum varchar(128),
  DateOfAccident datetime,
	DateOfService datetime,
	doc_type varchar(128),
	document varchar(128),
	doc_date datetime, 
	FileType varchar(128),
	lPAGEID varchar(128),
	sPATH_ varchar(128),
	nTYPE_ varchar(128)
)
*/

/*
@micromdTbl table
(
  AcctNum varchar(128),               
  DateOfAccident datetime,
  DateOfService datetime,
  database_name varchar(128),
  practice_id int, 
  guarantor_id int,
  patient_no int,
  case_no int,
  employer_id int,
  first_name varchar(128),
  last_name varchar(128)
)
*/

/*
@docstarMicromdJoinTblIn table
(
id int,
date_of_service datetime,
document_type varchar(128),
document_name varchar(500),
document_date datetime, 
full_path varchar(128),
FileType varchar(128), 
lPAGEID int,
AcctNum varchar(128),
database_name varchar(128),
practice_id int, 
guarantor_id int,
patient_no int,
case_no int,
employer_id int,
first_name varchar(128),
last_name varchar(128)
)
*/


@docstarMicromdJoinTbl table
(
  id int,
							date_of_service datetime,
							document_type varchar(128),
							document_date datetime, 
							 document_name varchar(500),
							full_path varchar(128),
							FileType varchar(128), 
							lPAGEID int,
							AcctNum varchar(128),
							database_name varchar(128),
							practice_id int, 
							guarantor_id int,
							patient_no int,
							case_no int,
							employer_id int,
							first_name varchar(128),
							last_name varchar(128)
)


AS 
begin


----------------------------------------------------------------------------------------------
------------------------------- Declares -----------------------------------------------------

declare @docstarTbl table 
(
  LDocID_ int,
  AcctNum varchar(128),
  DateOfAccident datetime,
	DateOfService datetime,
	doc_type varchar(128),
	document varchar(500),
	doc_date datetime, 
	FileType varchar(128),
	lPAGEID varchar(128),
	sPATH_ varchar(128),
	nTYPE_ varchar(128)
); 

declare @micromdTbl table
(
  AcctNum varchar(386),               
  DateOfAccident datetime,
  DateOfService datetime,
  database_name varchar(128),
  practice_id int, 
  guarantor_id int,
  patient_no int,
  case_no int,
  employer_id int,
  first_name varchar(128),
  last_name varchar(128)
);


declare @docstarMicromdJoinTblIn table
(
id int,
date_of_service datetime,
document_type varchar(128),
document_name varchar(500),
document_date datetime, 
full_path varchar(128),
FileType varchar(128), 
lPAGEID int,
AcctNum varchar(128),
database_name varchar(128),
practice_id int, 
guarantor_id int,
patient_no int,
case_no int,
employer_id int,
first_name varchar(128),
last_name varchar(128)
);

/*
declare @docstarMicromdJoinTbl table
(
  id int,
							date_of_service datetime,
							document_type varchar(128),
							document_date datetime, 
							 document_name varchar(500),
							full_path varchar(128),
							FileType varchar(128), 
							lPAGEID int,
							AcctNum varchar(128),
							database_name varchar(128),
							practice_id int, 
							guarantor_id int,
							patient_no int,
							case_no int,
							employer_id int,
							first_name varchar(128),
							last_name varchar(128)
)
*/
------------------- Docstar ---------------------------------------------------
insert into @docstarTbl
SELECT doc.LDocID_,     
									   Acc.sValue as AcctNum,     
									   DSDOA.dtValue as DateOfAccident,
									   DSDOS.dtValue as DateOfService,
									   DSDocType.sVALUE as doc_type,
									   doc.sTITLE as document,
									   doc.dtCreated as doc_date, 
									   Ft.FileType,
									   Pg.lPAGEID,
									   doc.sPATH_,
									   doc.nTYPE_
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
								WHERE DSDocType.sValue in ('Medical Report','MRI Report','Prog Note','Disability','PT-BWR Referral','Consult','OS MED REC', 'Letter', 'Rx Req', 'NTI Report')
                and (doc.bDeleted_ is null or doc.bDeleted_ <> -1)
								and doc.dtCreated between @startDate and @endDate
								and (DSDOA.dtValue is not null or DSDOS.dtValue is not null)
			;
			
------------------------- MicroMD --------------------------------------------			
insert into @micromdTbl
SELECT cast(poi.practice_id as varchar(128)) + '.' + cast(poi.guarantor_id as varchar(128)) + '.' + cast(poi.patient_no as varchar(128))   as AcctNum,               
									   injury_date as DateOfAccident,
									   th.service_date_from as DateOfService,
									   poi.database_name,
									   poi.practice_id, 
									   poi.guarantor_id,
									   poi.patient_no,
									   poi.case_no,
									   poi.employer_id,
									   p.first_name,
									   p.last_name
								FROM AMM_LIVE.dbo.pm_patient_other_info as poi with (nolock)
								left join AMM_LIVE.dbo.pm_transaction_header as th with (nolock)
									 on poi.database_name = th.database_name
									 and poi.practice_id = th.practice_id
									 and poi.guarantor_id = th.guarantor_id
									 and poi.patient_no = th.patient_no
									 and poi.case_no = th.case_no
								left join AMM_LIVE.dbo.pm_patient as p with (nolock)
									 on poi.database_name = p.database_name
									 and poi.practice_id = p.practice_id
									 and poi.guarantor_id = p.guarantor_id
									 and poi.patient_no = p.patient_no
								WHERE       poi.database_name         = 'amm_live'
								and poi.employer_id is not null
								and (injury_date is not null or service_date_from is not null)
						--		and cast(poi.practice_id as varchar(128)) + '.' + cast(poi.guarantor_id as varchar(128)) + '.' + cast(poi.patient_no as varchar(128))  = '1.105450.0'
					;
--------- joins between MicroMD and Docstar -------------------------------------
insert into @docstarMicromdJoinTblIn
SELECT QueryDS.lDOCID_ as id,
								 QueryDS.DateOfService as date_of_service,
								 QueryDS.doc_type as document_type,
								 QueryDS.document as document_name,
								 QueryDS.doc_date as document_date, 
								 case when QueryDS.FileType is not null 
									   then QueryDS.sPATH_ + '\N' + cast(QueryDS.lDOCID_ as varchar(128)) + QueryDS.FileType
									  when  QueryDS.FileType is null 
										then case when QueryDS.nType_  in (0,1,2)
												   then QueryDS.sPATH_ + '\' + cast(QueryDS.lPageID as varchar(128)) + '.tif'
												  else 
														QueryDS.sPATH_ + '\N' + cast(QueryDS.lDOCID_ as varchar(128)) + '.pdf'
											 end
								  end as full_path,
								  QueryDS.FileType, 
								  QueryDS.lPAGEID,
								  QueryDS.AcctNum,
								  QueryMMD.database_name,
								  QueryMMD.practice_id, 
								  QueryMMD.guarantor_id,
								  QueryMMD.patient_no,
								  QueryMMD.case_no,
								  QueryMMD.employer_id,
								  QueryMMD.first_name,
								  QueryMMD.last_name
						  FROM @docstarTbl as QueryDS
						  JOIN @micromdTbl as QueryMMD
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
								   QueryDS.doc_date, 
								   FileType, 
								   lPAGEID,
								   QueryDS.AcctNum,
								   QueryDS.sPATH_,
								   QueryDS.nTYPE_,
								   QueryMMD.database_name,
								   QueryMMD.practice_id, 
								   QueryMMD.guarantor_id,
								   QueryMMD.patient_no,
								   QueryMMD.case_no,
								   QueryMMD.employer_id,
								   QueryMMD.first_name,
									QueryMMD.last_name
;
-------------- final query -------------------------------
insert into @docstarMicromdJoinTbl								
select  id,
							date_of_service,
							document_type,
							document_date, 
							case when DS_MMD_cross.lPAGEID is not null 
								 then document_name + ', page ' + cast(ROW_NUMBER() OVER (PARTITION BY id  ORDER BY date_of_service, id, lPAGEID, document_name) as varchar(128))
							else 
									  document_name
							end as document_name,
							full_path,
							FileType, 
							lPAGEID,
							AcctNum,
							DS_MMD_cross.database_name,
							DS_MMD_cross.practice_id, 
							DS_MMD_cross.guarantor_id,
							DS_MMD_cross.patient_no,
							DS_MMD_cross.case_no,
							DS_MMD_cross.employer_id,
							DS_MMD_cross.first_name,
							DS_MMD_cross.last_name 
					from @docstarMicromdJoinTblIn as DS_MMD_cross

return
end

