ALTER TABLE  `ext_dbs_legal_attys` ADD  `external_atty_name` TEXT NOT NULL ,
ADD  `ext_db_name` TEXT NOT NULL ;

ALTER TABLE  `ext_dbs_legal_attys` DROP FOREIGN KEY  `ext_dbs_legal_attys_ibfk_1` ,
ADD FOREIGN KEY (  `legal_atty_id` ) REFERENCES  `legal_attys` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;