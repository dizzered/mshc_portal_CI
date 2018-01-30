DROP TABLE portal_settings;

--
-- Структура таблицы `portal_settings`
--

CREATE TABLE IF NOT EXISTS `portal_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(100) DEFAULT NULL,
  `server_url` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `server_port` int(5) DEFAULT NULL,
  `email_from` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `failed_password_attempt_count` int(2) DEFAULT NULL,
  `email_administrator` varchar(100) DEFAULT NULL,
  `email_scheduling` varchar(100) DEFAULT NULL,
  `email_settlements` varchar(100) DEFAULT NULL,
  `email_patient_registration` varchar(100) DEFAULT NULL,
  `email_it_contact` varchar(100) DEFAULT NULL,
  `email_marketing_distribution_list` varchar(100) DEFAULT NULL,
  `dashboard_banner` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
