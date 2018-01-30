DROP TABLE IF EXISTS `legal_cases_legal_case_mgrs`;
CREATE TABLE IF NOT EXISTS `legal_cases_legal_case_mgrs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ext_db_id` bigint(20) NOT NULL,
  `external_id1` varchar(99) NOT NULL,
  `external_id2` varchar(99) NOT NULL,
  `external_id3` varchar(99) NOT NULL,
  `external_id4` varchar(99) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `legal_cases_legal_case_mgrs`
  ADD CONSTRAINT `legal_cases_legal_case_mgrs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS `ext_dbs_legal_attys`;
CREATE TABLE IF NOT EXISTS `ext_dbs_legal_attys` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ext_db_id` bigint(20) NOT NULL,
  `external_id` varchar(99) NOT NULL,
  `legal_atty_id` int(11) DEFAULT NULL,
  `external_atty_name` text NOT NULL,
  `ext_db_name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `legal_atty_id` (`legal_atty_id`),
  KEY `legal_atty_id_2` (`legal_atty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `ext_dbs_legal_attys`
  ADD CONSTRAINT `ext_dbs_legal_attys_ibfk_1` FOREIGN KEY (`legal_atty_id`) REFERENCES `legal_attys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
