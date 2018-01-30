ALTER TABLE  `portal_activity_logs` DROP FOREIGN KEY  `portal_activity_logs_ibfk_1` ,
ADD FOREIGN KEY (  `portal_activity_id` ) REFERENCES  `portal_activities` (
`id`
) ON DELETE NO ACTION ON UPDATE NO ACTION ;

ALTER TABLE  `portal_activity_logs` DROP FOREIGN KEY  `portal_activity_logs_ibfk_2` ,
ADD FOREIGN KEY (  `legal_portal_activity_id` ) REFERENCES  `legal_portal_activities` (
`id`
) ON DELETE NO ACTION ON UPDATE NO ACTION ;