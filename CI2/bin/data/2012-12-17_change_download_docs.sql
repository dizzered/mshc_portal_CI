ALTER TABLE `download_docs` MODIFY COLUMN `created_by` INT DEFAULT NULL,
 MODIFY COLUMN `modified_by` INT;

ALTER TABLE `download_docs` RENAME TO `forms`;