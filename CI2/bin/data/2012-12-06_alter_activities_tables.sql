TRUNCATE TABLE  `portal_activities` ;

ALTER TABLE  `portal_activities` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

TRUNCATE TABLE  `legal_portal_activities` ;

ALTER TABLE  `legal_portal_activities` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

TRUNCATE TABLE  `portal_activity_logs` ;

ALTER TABLE  `portal_activity_logs` CHANGE  `user_id`  `user_id` INT( 11 ) NULL ,
CHANGE  `portal_activity_id`  `portal_activity_id` INT( 11 ) NULL ,
CHANGE  `legal_portal_activity_id`  `legal_portal_activity_id` INT( 11 ) NULL ;