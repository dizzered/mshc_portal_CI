ALTER TABLE  `practices` CHANGE  `split_charges`  `split_charges` BOOLEAN NULL DEFAULT NULL ;

ALTER TABLE  `practice_finances` DROP FOREIGN KEY  `practice_finances_ibfk_1` ,
ADD FOREIGN KEY (  `ext_dbs_fin_class_id` ) REFERENCES  `ext_dbs_fin_classes` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `practice_finances` DROP FOREIGN KEY  `practice_finances_ibfk_2` ,
ADD FOREIGN KEY (  `fin_grp_id` ) REFERENCES  `fin_grps` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `practice_finances` DROP FOREIGN KEY  `practice_finances_ibfk_3` ,
ADD FOREIGN KEY (  `practice_id` ) REFERENCES  `practices` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;