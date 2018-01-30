ALTER TABLE  `ext_dbs_fin_classes` ADD INDEX (  `ext_db_id` ) ;

ALTER TABLE  `ext_dbs_fin_classes` ADD FOREIGN KEY (  `ext_db_id` ) REFERENCES  `ext_dbs` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `ext_dbs_practice_locs` ADD INDEX (  `ext_db_id` ) ;

ALTER TABLE  `ext_dbs_practice_locs` CHANGE  `practice_id`  `practice_id` INT( 11 ) NULL ;

ALTER TABLE  `ext_dbs_practice_locs` ADD FOREIGN KEY (  `ext_db_id` ) REFERENCES  `ext_dbs` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `ext_dbs_practice_locs` ADD FOREIGN KEY (  `practice_id` ) REFERENCES  `practices` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `practice_finances` ADD INDEX (  `ext_dbs_fin_class_id` ) ;

ALTER TABLE  `practice_finances` ADD INDEX (  `fin_grp_id` ) ;

ALTER TABLE  `ext_dbs_fin_classes` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

ALTER TABLE  `practice_finances` ADD FOREIGN KEY (  `ext_dbs_fin_class_id` ) REFERENCES  `ext_dbs_fin_classes` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `practice_finances` ADD FOREIGN KEY (  `fin_grp_id` ) REFERENCES  `fin_grps` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `practice_finances` ADD FOREIGN KEY (  `practice_id` ) REFERENCES  `practices` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `practices` ADD INDEX (  `client_id` ) ;

ALTER TABLE  `practices` ADD FOREIGN KEY (  `client_id` ) REFERENCES  `clients` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `ext_dbs_legal_apnmt_reasons` CHANGE  `practice_id`  `practice_id` INT( 11 ) NULL ;

ALTER TABLE  `ext_dbs_legal_apnmt_reasons` ADD INDEX (  `practice_id` ) ;

ALTER TABLE  `ext_dbs_legal_apnmt_reasons` ADD FOREIGN KEY (  `practice_id` ) REFERENCES  `practices` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE  `ext_dbs_legal_attys` CHANGE  `legal_atty_id`  `legal_atty_id` INT( 11 ) NULL ;

ALTER TABLE  `ext_dbs_legal_attys` ADD INDEX (  `legal_atty_id` ) ;

ALTER TABLE  `ext_dbs_legal_attys` ADD FOREIGN KEY (  `legal_atty_id` ) REFERENCES  `legal_attys` (
`id`
) ON DELETE RESTRICT ON UPDATE CASCADE ;
