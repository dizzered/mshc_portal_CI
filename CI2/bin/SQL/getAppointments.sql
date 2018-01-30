ALTER FUNCTION "MicroMD"."get@dbprefix@Appointments" (@databaseName nvarchar(128), @guarantorID int, @caseNo int, @practiceID int, @patientNo int) 
returns                                                             -- returns
/*
@queryA table 
(
  database_name varchar(128),           
  practice_id int,           
  guarantor_id int,           
  patient_no int,           
  case_no int,           
  ApptClassID int,           
  ApptClassDesc varchar(10),           
  cost_center_id int,
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128)
)
*/
/*
@queryB table 
(
  database_name varchar(128),         
  practice_id int,         
  cost_center_id int,         
  PortalPracticeID int,         
  PortalPracticeName varchar(128)
)
*/
/*
@queryC table
(
  database_name varchar(128),
  practice_id int,
  guarantor_id int,
  patient_no int,
  case_no int,
  ApptClassID int,
  ApptClassDesc varchar(10),
  cost_center_id int,
  PortalPracticeID int,
  PortalPracticeName varchar(128),
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128)
)
*/

@queryD table
(
  database_name varchar(128),
  practice_id int,
  guarantor_id int,
  patient_no int,
  case_no int,
  ApptClassID int,
  ApptClassDesc varchar(10),
  cost_center_id int,
  PortalPracticeID int,
  PortalPracticeName varchar(128),
  AMMReason varchar(128),
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128),
  status varchar(128)
)

AS 
begin


----------------------------------------------------------------------------------------------
------------------------------- Declares -----------------------------------------------------

declare @queryA table 
(
  database_name varchar(128),           
  practice_id int,           
  guarantor_id int,           
  patient_no int,           
  case_no int,           
  ApptClassID int,           
  ApptClassDesc varchar(10),           
  cost_center_id int,
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128),
  status varchar(128)
);

declare @queryB table 
(
  database_name varchar(128),         
  practice_id int,         
  cost_center_id int,         
  PortalPracticeID int,         
  PortalPracticeName varchar(128)
);

declare @queryC table
(
  database_name varchar(128),
  practice_id int,
  guarantor_id int,
  patient_no int,
  case_no int,
  ApptClassID int,
  ApptClassDesc varchar(10),
  cost_center_id int,
  PortalPracticeID int,
  PortalPracticeName varchar(128),
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128),
  status varchar(128)
);
/*
declare @queryD table
(
  database_name varchar(128),
  practice_id int,
  guarantor_id int,
  patient_no int,
  case_no int,
  ApptClassID int,
  ApptClassDesc varchar(10),
  cost_center_id int,
  PortalPracticeID int,
  PortalPracticeName varchar(128),
  AMMReason varchar(128),
  appt_date datetime,
  appt_time datetime,
  last_name varchar(128),
  first_name varchar(128)
);
*/
---------------------------------------------------------------------------------------
------------------------------------------ 1 ------------------------------------------
-- A
insert into @queryA
SELECT  ap.database_name,           
        ap.practice_id,           
        ap.guarantor_id,           
        ap.patient_no,           
        ap.case_no,           
        ap.class as ApptClassID,           
        ac."desc"  as ApptClassDesc,           
        ap.cost_center_id,
        ap.date,
        t.time,
        mmd_up.last_name,
        mmd_up.first_name,
        'Active/Kept'
FROM AMM_LIVE.dbo.pm_appt as ap with (nolock)
LEFT JOIN AMM_LIVE.dbo.pm_appt_classification as ac with (nolock)
  ON ap.database_name    = ac.database_name
  and ap.class = ac.id
left join AMM_LIVE.dbo.TIME as t with (nolock)
  on t.time_id = ap.time_id
left join AMM_LIVE.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = ap.database_name
  and mmd_up.provider_id = ap.provider_id
where ap.database_name = @databaseName
and ap.guarantor_id = @guarantorID
and ap.case_no  = @caseNo
and ap.patient_no = @patientNo
and ap.practice_id = @practiceID
union
SELECT  ap.database_name,           
        ap.practice_id,           
        ap.guarantor_id,           
        ap.patient_no,           
        ap.case_no,           
        ap.class as ApptClassID,           
        ac."desc"  as ApptClassDesc,           
        ap.cost_center_id,
        ap.date,
        t.time,
        mmd_up.last_name,
        mmd_up.first_name,
        case when appt_log_type = 1 then 'Deleted'
              when appt_log_type = 2 then 'Cancelled'
              when appt_log_type = 3 then 'Missed'
              when appt_log_type = 4 then 'Rescheduled'
              when appt_log_type = 5 then 'Copied' 
        end
FROM AMM_LIVE.dbo.pm_appt_log as ap with (nolock)
LEFT JOIN AMM_LIVE.dbo.pm_appt_classification as ac with (nolock)
  ON ap.database_name    = ac.database_name
  and ap.class = ac.id
left join AMM_LIVE.dbo.TIME as t with (nolock)
  on t.time_id = ap.time_id
left join AMM_LIVE.dbo.pm_mmdUserPassword as mmd_up with (nolock)
  on mmd_up.database_name = ap.database_name
  and mmd_up.provider_id = ap.provider_id
where ap.database_name = @databaseName
and ap.guarantor_id = @guarantorID
and ap.case_no  = @caseNo
and ap.patient_no = @patientNo
and ap.practice_id = @practiceID

-- B -------------------------------------
insert into @queryB
SELECT lp.database_name,         
        lp.practice_id,         
        lp.cost_center_id,         
        lp.PortalPracticeID,         
        pr.Name as PortalPracticeName
FROM PointsProcesses.MicroMD.tbl@dbprefix@LocationPractice as lp with (nolock)          
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@Practice pr with (nolock)
  ON lp.PortalPracticeID = pr.PortalPracticeID
  
-- C ------------------------------------
insert into @queryC
SELECT  a.database_name,
        a.practice_id,
        a.guarantor_id,
        a.patient_no,
        a.case_no,
        a.ApptClassID,
        a.ApptClassDesc,
        a.cost_center_id,
        b.PortalPracticeID,
        b.PortalPracticeName,
        a.appt_date,
        a.appt_time,
        a.last_name,
        a.first_name,
        a.status
FROM @queryA as a 
LEFT JOIN @queryB as b
  ON a.database_name      =          b.database_name
  and     a.practice_id               =          b.practice_id
  and      a.cost_center_id        =          b.cost_center_id
  
-- D ----------------------------------
insert into @queryD
SELECT database_name,
       practice_id,
       guarantor_id,
       patient_no,
       case_no,
       ApptClassID,
       ApptClassDesc,
       cost_center_id,
       PortalPracticeID,
       PortalPracticeName,
       AMMReason,
       appt_date,
       appt_time,
       last_name,
       first_name,
       status
FROM @queryC as c
LEFT JOIN PointsProcesses.MicroMD.tbl@dbprefix@ApptReason ar with (nolock)
  ON c.PortalPracticeID      =          ar.PracticeID 
  and      c.ApptClassDesc         =          ar.PMSReason


return
end
