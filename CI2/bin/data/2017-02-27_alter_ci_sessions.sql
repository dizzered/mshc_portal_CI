ALTER TABLE `ci_sessions` DROP `user_agent`;

ALTER TABLE `ci_sessions` CHANGE `session_id` `id` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
CHANGE `last_activity` `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `user_data` `data` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;