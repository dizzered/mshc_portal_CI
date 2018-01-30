ALTER TABLE  `legal_firms_users` ADD FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `legal_firms_users` ADD INDEX (  `legal_firm_id` ) ;

ALTER TABLE  `legal_firms_users` ADD FOREIGN KEY (  `legal_firm_id` ) REFERENCES  `legal_firms` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `legal_attys_users` ADD INDEX (  `legal_atty_id` ) ;

ALTER TABLE  `legal_attys_users` ADD INDEX (  `user_id` ) ;

ALTER TABLE  `legal_attys_users` ADD FOREIGN KEY (  `legal_atty_id` ) REFERENCES  `legal_attys` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

TRUNCATE TABLE  `legal_attys` ;

TRUNCATE TABLE  `legal_attys_users` ;

ALTER TABLE  `legal_attys_users` ADD FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `portal_activity_logs` ADD INDEX (  `legal_portal_activity_id` ) ;

ALTER TABLE  `portal_activity_logs` ADD FOREIGN KEY (  `portal_activity_id` ) REFERENCES  `portal_activities` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `portal_activity_logs` ADD FOREIGN KEY (  `legal_portal_activity_id` ) REFERENCES  `legal_portal_activities` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `legal_attys` ADD INDEX (  `legal_firm_id` ) ;

ALTER TABLE  `legal_attys` CHANGE  `legal_firm_id`  `legal_firm_id` INT( 11 ) NULL DEFAULT NULL ;

ALTER TABLE  `legal_attys` ADD FOREIGN KEY (  `legal_firm_id` ) REFERENCES  `legal_firms` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `legal_users` ADD INDEX (  `user_id` ) ;

ALTER TABLE  `legal_users` ADD FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;