-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 17, 2013 at 02:40 PM
-- Server version: 5.0.91
-- PHP Version: 5.3.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mshc_portal_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) character set utf8 collate utf8_bin NOT NULL default '0',
  `ip_address` varchar(16) character set utf8 collate utf8_bin NOT NULL default '0',
  `user_agent` varchar(150) character set utf8 collate utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text character set utf8 collate utf8_bin NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('c8822379c9c94b6e22414c24ee97ba64', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11', 1354529747, 0x613a383a7b733a393a22757365725f64617461223b733a303a22223b733a373a22757365725f6964223b733a313a2231223b733a383a22757365726e616d65223b733a353a2261646d696e223b733a363a22737461747573223b693a313b733a31303a2266697273745f6e616d65223b733a353a22526f6d616e223b733a393a226c6173745f6e616d65223b733a353a225a757a696e223b733a373a22726f6c655f6964223b733a33363a2265626633626663342d326534382d313165322d626434632d373536313836383065623161223b733a383a2274696d657a6f6e65223b733a31333a224575726f70652f4d6f73636f77223b7d),
('dc0c06e2930372ebe756456ab2902d16', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/534.56.5 (KHTML, like Gecko) Version/5.1.6 Safari/534.56.5', 1354606241, 0x613a323a7b733a393a22757365725f64617461223b733a303a22223b733a33313a22666c6173683a6f6c643a67656e6572616c5f666c6173685f6d657373616765223b733a34393a227b747970653a226e6f745f6c6f67676564222c746578743a22596f7520617265206e6f74206c6f6767656420696e2e227d223b7d);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  `is_inactive` tinyint(1) default NULL,
  `created` datetime default NULL,
  `created_by` char(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) default NULL,
  `email` varchar(100) NOT NULL,
  `including_me` tinyint(1) NOT NULL default '0',
  `cc_to` text,
  `body` text NOT NULL,
  `marketer_id` int(10) unsigned default NULL,
  `user_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `inquiry_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_attach`
--

CREATE TABLE IF NOT EXISTS `contacts_attach` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `contact_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ext_dbs`
--

CREATE TABLE IF NOT EXISTS `ext_dbs` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ext_dbs_fin_classes`
--

CREATE TABLE IF NOT EXISTS `ext_dbs_fin_classes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  `ext_db_id` bigint(20) default NULL,
  `external_id` varchar(99) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ext_db_id` (`ext_db_id`),
  KEY `ext_db_id_2` (`ext_db_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ext_dbs_legal_apnmt_reasons`
--

CREATE TABLE IF NOT EXISTS `ext_dbs_legal_apnmt_reasons` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  `ext_db_id` bigint(20) default NULL,
  `external_id` varchar(99) default NULL,
  `practice_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `practice_id` (`practice_id`),
  KEY `practice_id_2` (`practice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ext_dbs_legal_attys`
--

CREATE TABLE IF NOT EXISTS `ext_dbs_legal_attys` (
  `id` bigint(20) NOT NULL auto_increment,
  `ext_db_id` bigint(20) NOT NULL,
  `external_id` varchar(99) NOT NULL,
  `legal_atty_id` int(11) default NULL,
  `external_atty_name` text NOT NULL,
  `ext_db_name` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `legal_atty_id` (`legal_atty_id`),
  KEY `legal_atty_id_2` (`legal_atty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ext_dbs_practice_locs`
--

CREATE TABLE IF NOT EXISTS `ext_dbs_practice_locs` (
  `id` bigint(20) NOT NULL auto_increment,
  `external_id` varchar(99) NOT NULL,
  `ext_db_id` bigint(20) default NULL,
  `practice_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `practice_location_fk1` (`practice_id`),
  KEY `ext_db_id` (`ext_db_id`),
  KEY `ext_db_id_2` (`ext_db_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fin_grps`
--

CREATE TABLE IF NOT EXISTS `fin_grps` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(99) default NULL,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `description` text,
  `file_name` varchar(99) default NULL,
  `weight` bigint(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_attys`
--

CREATE TABLE IF NOT EXISTS `legal_attys` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) character set utf8 default NULL,
  `last_name` varchar(255) character set utf8 default NULL,
  `is_inactive` tinyint(1) default NULL,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `legal_firm_id` int(11) default NULL,
  `missed_appointment_notification_delivery_method` char(255) character set utf8 default NULL,
  `missed_appointment_threshold` smallint(6) default NULL,
  `statement_delivery_method` char(255) character set utf8 default NULL,
  `statement_frequency` char(255) character set utf8 default NULL,
  PRIMARY KEY  (`id`),
  KEY `legal_firm_id` (`legal_firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_attys_users`
--

CREATE TABLE IF NOT EXISTS `legal_attys_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `legal_atty_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `legal_atty_id` (`legal_atty_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_cases_legal_case_mgrs`
--

CREATE TABLE IF NOT EXISTS `legal_cases_legal_case_mgrs` (
  `id` bigint(20) NOT NULL auto_increment,
  `ext_db_id` bigint(20) NOT NULL,
  `external_id1` varchar(99) NOT NULL,
  `external_id2` varchar(99) NOT NULL,
  `external_id3` varchar(99) NOT NULL,
  `user_id` bigint(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_firms`
--

CREATE TABLE IF NOT EXISTS `legal_firms` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `is_inactive` tinyint(1) default NULL,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `legal_firms`
--

INSERT INTO `legal_firms` (`id`, `name`, `is_inactive`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 'Test 001', NULL, '2012-11-21 15:18:34', 1, '2012-11-21 15:18:34', 1),
(2, 'Test 002', NULL, '2012-11-21 15:19:28', 1, '2012-11-21 15:19:28', 1),
(3, 'Test 004', NULL, '2012-11-21 15:21:47', 1, '2012-11-21 15:28:47', 1),
(4, 'Test 005', NULL, '2012-11-29 17:20:19', 1, '2012-11-29 17:22:36', 1),
(5, 'fg', NULL, '2012-11-29 17:34:53', 1, '2012-11-29 17:35:23', 1),
(6, 'Test 006', NULL, '2012-11-29 17:37:33', 1, '2012-11-29 17:37:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `legal_firms_users`
--

CREATE TABLE IF NOT EXISTS `legal_firms_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `legal_firm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_primary` tinyint(1) default NULL,
  `all_attorneys` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_firms_fk1` (`user_id`),
  KEY `legal_firm_id` (`legal_firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `legal_firms_users`
--

INSERT INTO `legal_firms_users` (`id`, `legal_firm_id`, `user_id`, `is_primary`, `all_attorneys`) VALUES
(44, 1, 2, NULL, 1),
(45, 2, 2, 1, 1),
(46, 1, 5, 1, 1),
(47, 1, 6, 1, 1),
(48, 1, 7, 1, 1),
(49, 1, 8, 1, 1),
(50, 1, 9, 1, 1),
(51, 1, 10, 1, 1),
(52, 2, 10, NULL, 1),
(59, 6, 11, 1, 0),
(60, 1, 13, 1, 1),
(61, 1, 14, 1, 1),
(62, 6, 15, NULL, NULL),
(63, 6, 16, NULL, NULL),
(64, 6, 17, NULL, NULL),
(65, 1, 4, NULL, 1),
(66, 2, 4, 1, 1),
(67, 1, 12, NULL, 1),
(68, 6, 12, 1, 1),
(71, 1, 1, 1, 1),
(72, 2, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `legal_notice_sends`
--

CREATE TABLE IF NOT EXISTS `legal_notice_sends` (
  `id` int(11) NOT NULL auto_increment,
  `type` char(3) default NULL,
  `created` datetime default NULL,
  `date_from` datetime default NULL,
  `date_to` datetime default NULL,
  `case_count` int(11) default NULL,
  `sent_count` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_portal_activities`
--

CREATE TABLE IF NOT EXISTS `legal_portal_activities` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `legal_users`
--

CREATE TABLE IF NOT EXISTS `legal_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `missed_appointments_notified` bit(1) default NULL,
  `case_discharge_notified` bit(1) default NULL,
  `medical_report_notified` bit(1) default NULL,
  `pt_note_notified` bit(1) default NULL,
  `outside_medical_record_notified` bit(1) default NULL,
  `consult_notified` bit(1) default NULL,
  `ptbwr_referral_notified` bit(1) default NULL,
  `disability_notified` bit(1) default NULL,
  `pharmacy_notified` bit(1) default NULL,
  `maintain_attorneys_allowed` bit(1) default NULL,
  `maintain_firms_allowed` bit(1) default NULL,
  `register_cases_allowed` bit(1) default NULL,
  `view_cases_for_firm_allowed` bit(1) default NULL,
  `view_own_cases_allowed` bit(1) default NULL,
  `maintain_clients_allowed` bit(1) default NULL,
  `maintain_patient_forms_allowed` bit(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `legal_users`
--

INSERT INTO `legal_users` (`id`, `user_id`, `missed_appointments_notified`, `case_discharge_notified`, `medical_report_notified`, `pt_note_notified`, `outside_medical_record_notified`, `consult_notified`, `ptbwr_referral_notified`, `disability_notified`, `pharmacy_notified`, `maintain_attorneys_allowed`, `maintain_firms_allowed`, `register_cases_allowed`, `view_cases_for_firm_allowed`, `view_own_cases_allowed`, `maintain_clients_allowed`, `maintain_patient_forms_allowed`) VALUES
(1, 1, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', NULL),
(32, 2, '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '0', NULL, NULL, NULL),
(34, 4, '0', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', NULL, '0', NULL, '0', NULL),
(35, 5, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, NULL),
(36, 6, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, NULL),
(37, 7, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, NULL),
(38, 8, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, NULL),
(39, 9, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, NULL),
(40, 10, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '1', NULL, NULL, NULL),
(41, 11, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '0', NULL, '0', NULL),
(42, 12, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '1', NULL, '0', NULL, '0', NULL),
(43, 13, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, '0', NULL),
(44, 14, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, '0', NULL),
(45, 15, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, '0', NULL),
(46, 16, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, '0', NULL),
(47, 17, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `marketers`
--

CREATE TABLE IF NOT EXISTS `marketers` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  `middle_name` char(255) default NULL,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `email` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `body` text,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_users`
--

CREATE TABLE IF NOT EXISTS `notifications_users` (
  `notification_id` int(11) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `read` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`notification_id`,`user_id`),
  KEY `notification_id` (`notification_id`),
  KEY `notification_id_2` (`notification_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portal_activities`
--

CREATE TABLE IF NOT EXISTS `portal_activities` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `portal_activity_logs`
--

CREATE TABLE IF NOT EXISTS `portal_activity_logs` (
  `id` int(11) NOT NULL auto_increment,
  `created` datetime default NULL,
  `user_id` int(11) default NULL,
  `portal_activity_id` int(11) default NULL,
  `legal_portal_activity_id` int(11) default NULL,
  `info` text,
  `session_id` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `event_log_fk1` (`user_id`),
  KEY `event_log_fk2` (`portal_activity_id`),
  KEY `legal_portal_activity_id` (`legal_portal_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `portal_settings`
--

CREATE TABLE IF NOT EXISTS `portal_settings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `logo` varchar(100) default NULL,
  `server_url` varchar(100) default NULL,
  `username` varchar(100) default NULL,
  `server_port` int(5) default NULL,
  `email_from` varchar(100) default NULL,
  `password` varchar(255) default NULL,
  `failed_password_attempt_count` int(2) default NULL,
  `email_administrator` varchar(100) default NULL,
  `email_scheduling` varchar(100) default NULL,
  `email_settlements` varchar(100) default NULL,
  `email_patient_registration` varchar(100) default NULL,
  `email_it_contact` varchar(100) default NULL,
  `email_marketing_distribution_list` varchar(100) default NULL,
  `dashboard_banner` text,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `practices`
--

CREATE TABLE IF NOT EXISTS `practices` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `short_name` varchar(99) default NULL,
  `is_inactive` tinyint(1) default NULL,
  `created` datetime default NULL,
  `created_by` char(36) default NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `client_id` bigint(20) default NULL,
  `ext_db_id1` bigint(20) default NULL,
  `external_id1` varchar(99) default NULL,
  `ext_db_id2` bigint(20) default NULL,
  `external_id2` varchar(99) default NULL,
  `ext_db_id3` bigint(20) default NULL,
  `external_id3` varchar(99) default NULL,
  `split_charges` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `client_id` (`client_id`),
  KEY `client_id_2` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `practice_finances`
--

CREATE TABLE IF NOT EXISTS `practice_finances` (
  `id` int(11) NOT NULL auto_increment,
  `ext_dbs_fin_class_id` int(11) default NULL,
  `fin_grp_id` bigint(20) default NULL,
  `practice_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `practice_groupclasses_fk1` (`practice_id`),
  KEY `ext_dbs_fin_class_id` (`ext_dbs_fin_class_id`),
  KEY `fin_grp_id` (`fin_grp_id`),
  KEY `ext_dbs_fin_class_id_2` (`ext_dbs_fin_class_id`),
  KEY `fin_grp_id_2` (`fin_grp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` char(36) NOT NULL,
  `name` varchar(256) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
('2f12536a-2e49-11e2-bd4c-75618680eb1a', 'General User'),
('53e2325a-2e49-11e2-bd4c-75618680eb1a', 'Firm Administrator'),
('59abde66-2e49-11e2-bd4c-75618680eb1a', 'Attorney'),
('612f7e04-2e49-11e2-bd4c-75618680eb1a', 'Case Manager'),
('ebf3bfc4-2e48-11e2-bd4c-75618680eb1a', 'System Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  `is_inactive` tinyint(1) default NULL,
  `created` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `username` varchar(256) default NULL,
  `email` varchar(255) default NULL,
  `comment` text,
  `password` varchar(256) default NULL,
  `last_password_changed_date` datetime default NULL,
  `last_login_date` datetime default NULL,
  `last_activity_date` datetime default NULL,
  `failed_password_attempt_count` int(11) default NULL,
  `is_locked_out` tinyint(1) default NULL,
  `last_lockout_date` datetime default NULL,
  `maintain_marketers_allowed` bit(1) default NULL,
  `maintain_practices_allowed` bit(1) default NULL,
  `maintain_users_allowed` bit(1) default NULL,
  `view_portal_activity_logs_allowed` bit(1) default NULL,
  `role_id` varchar(36) default NULL,
  `timezone` varchar(100) NOT NULL default 'America/New_York',
  `autologin_key` varchar(32) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `is_inactive`, `created`, `created_by`, `modified`, `modified_by`, `username`, `email`, `comment`, `password`, `last_password_changed_date`, `last_login_date`, `last_activity_date`, `failed_password_attempt_count`, `is_locked_out`, `last_lockout_date`, `maintain_marketers_allowed`, `maintain_practices_allowed`, `maintain_users_allowed`, `view_portal_activity_logs_allowed`, `role_id`, `timezone`, `autologin_key`) VALUES
(1, 'Roman', 'Zuzin', NULL, '2012-11-01 00:00:00', NULL, '2012-11-29 19:30:04', 1, 'admin', 'dizzered@gmail.com', '', '$2a$08$pnSDh7xoVqIGBKw5hEDamOOm5fU4cSkluuZhbAyRU.yvDsL9hb0Em', NULL, '2012-12-03 01:45:13', '2012-12-03 01:45:13', 0, 0, NULL, '1', '1', '1', '1', 'ebf3bfc4-2e48-11e2-bd4c-75618680eb1a', 'Europe/Moscow', NULL),
(2, 'Test04', 'Test04', NULL, '2012-11-26 11:26:04', 1, '2012-11-26 17:06:56', 1, 'test04', 'test04@test.com', '', '$2a$08$/z3zmsDBe1qN/MCe4YplaOvhEgBYCccePVH4kLa0rSVaVAjPDxHc6', '2012-11-28 17:48:36', NULL, NULL, 15, 1, NULL, '1', NULL, NULL, '1', '2f12536a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(4, 'Test 01', 'Test 01', NULL, '2012-11-26 12:43:34', 1, '2012-11-29 19:17:46', 1, 'test01', 'test01@test.com', '', '$2a$08$rzRR4MQ3/Tu/kicWarmOZeoUobPqawoI4HJM5ws9y7M2K8QwZ2LuG', '2012-11-28 17:13:18', '2012-11-28 08:13:44', '2012-11-28 08:13:44', 0, 0, NULL, '0', NULL, NULL, '0', '612f7e04-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(5, 'Test05', 'Test 05', NULL, '2012-11-27 11:28:26', 1, '2012-11-27 11:28:26', 1, 'test05', 'test05@test.com', '', 'f8b4e8f9964dde3d45adeaadf2b9828d', '2012-11-27 11:28:26', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '59abde66-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(6, 'Test 06', 'Test06', NULL, '2012-11-27 11:28:58', 1, '2012-11-27 11:28:58', 1, 'test06', 'test06@test.com', '', '03acd7b801e752837b67655283dec48d', '2012-11-27 11:28:58', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '2f12536a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(7, 'Test07', 'Test07', NULL, '2012-11-27 11:29:35', 1, '2012-11-27 11:29:35', 1, 'test07', 'test07@test.com', '', '$2a$08$NPR2pSAPGtHnPV4KTZp9WuMmn7CA0lKFpv3XEYzP9pOkSHxvc.Apa', '2012-11-29 17:54:06', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '612f7e04-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(8, 'Test 08', 'Test08', NULL, '2012-11-27 11:30:16', 1, '2012-11-27 11:30:16', 1, 'test08', 'test08@gmail.com', '', 'cb9124f43a4f70b7fb2986d4dbf9a26b', '2012-11-27 11:30:16', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '53e2325a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(9, 'Test09', 'Test09', NULL, '2012-11-27 17:55:27', 1, '2012-11-27 17:55:27', 1, 'test09', 'test09@test.com', '', '$2a$08$pnSDh7xoVqIGBKw5hEDamOOm5fU4cSkluuZhbAyRU.yvDsL9hb0Em', '2012-11-27 17:55:27', '2012-11-28 10:11:01', '2012-11-28 10:11:01', 0, 0, NULL, '0', NULL, NULL, '0', '612f7e04-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(10, 'Test10', 'Test10', NULL, '2012-11-28 13:33:34', 1, '2012-11-28 13:33:34', 1, 'test10', 'test10@test.com', '', '$2a$08$9MiDV1EeGMrOVfQQrAQMJ.64p8Ozr6lfRgemRbsKPyoN7d.iZ1ECa', '2012-11-28 13:33:34', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '59abde66-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(11, 'Admin2', 'Admin2', NULL, '2012-11-29 18:14:37', 1, '2012-11-29 18:24:12', 1, 'admin2', 'goo@df.ru', '', '$2a$08$oTAV.tp8jUd.WGkDHLKfee6RrDsPJuur/RdSX4kUQ/oUQg1oNbRu6', '2012-11-29 18:14:37', NULL, NULL, 0, 0, NULL, '1', NULL, NULL, '1', 'ebf3bfc4-2e48-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(12, '', '', NULL, '2012-11-29 18:19:24', 1, '2012-11-29 19:18:58', 1, 'admin3', 'admin3@test.com', '', '$2a$08$f6REVXfyFl.FeHWBpCuDbevXuovW48pCqdidhbWfvtobcCsI7Mklu', '2012-11-29 18:19:24', NULL, NULL, 0, 0, NULL, '1', NULL, NULL, '1', 'ebf3bfc4-2e48-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(13, '', '', NULL, '2012-11-29 18:30:02', 1, '2012-11-29 18:30:02', 1, 'test11', 'test11@test.com', '', '$2a$08$pXOSDICQnC2ctDW.DCF00.JNjMi2Hul8Ym5aeeMOn0vcRjR8gzUCq', '2012-11-29 18:30:02', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '53e2325a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(14, '', '', NULL, '2012-11-29 18:30:34', 1, '2012-11-29 18:30:34', 1, 'test12', 'test12@test.com', '', '$2a$08$DnkoUk60unef3LaeY4b/1ux.J.KaXodomEa3Gnd2DodcUIlrH6XEu', '2012-11-29 18:30:34', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '612f7e04-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(15, '', '', NULL, '2012-11-29 18:31:10', 1, '2012-11-29 18:31:10', 1, 'test13', 'test13@test.com', '', '$2a$08$T6hc0cYLxKPG5xIM0/rAPOOG3WtYuOeUyn76R0lrkLhuXY2SiYlCy', '2012-11-29 18:31:10', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '2f12536a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(16, '', '', NULL, '2012-11-29 18:32:47', 1, '2012-11-29 18:32:47', 1, 'test14', 'test14@test.com', '', '$2a$08$5.nGRq6g8NkKg2tRv/sghe4F1y/K8peQBVno/9FfjvSBiLdIKJMSe', '2012-11-29 18:32:47', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '53e2325a-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL),
(17, '', '', NULL, '2012-11-29 18:34:56', 1, '2012-11-29 18:34:56', 1, 'test15', 'test15@test.com', '', '$2a$08$rdQQIo3uXLRbE5zFy2rXuebcsUUwNZKQ9cmVBosyCz0SQZ8e/A9pK', '2012-11-29 18:34:56', NULL, NULL, 0, 0, NULL, '0', NULL, NULL, '0', '612f7e04-2e49-11e2-bd4c-75618680eb1a', 'America/New_York', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts_attach`
--
ALTER TABLE `contacts_attach`
  ADD CONSTRAINT `contacts_attach_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ext_dbs_fin_classes`
--
ALTER TABLE `ext_dbs_fin_classes`
  ADD CONSTRAINT `ext_dbs_fin_classes_ibfk_1` FOREIGN KEY (`ext_db_id`) REFERENCES `ext_dbs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ext_dbs_fin_classes_ibfk_2` FOREIGN KEY (`ext_db_id`) REFERENCES `ext_dbs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ext_dbs_legal_apnmt_reasons`
--
ALTER TABLE `ext_dbs_legal_apnmt_reasons`
  ADD CONSTRAINT `ext_dbs_legal_apnmt_reasons_ibfk_2` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `ext_dbs_legal_apnmt_reasons_ibfk_1` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `ext_dbs_legal_attys`
--
ALTER TABLE `ext_dbs_legal_attys`
  ADD CONSTRAINT `ext_dbs_legal_attys_ibfk_3` FOREIGN KEY (`legal_atty_id`) REFERENCES `legal_attys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ext_dbs_legal_attys_ibfk_2` FOREIGN KEY (`legal_atty_id`) REFERENCES `legal_attys` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ext_dbs_practice_locs`
--
ALTER TABLE `ext_dbs_practice_locs`
  ADD CONSTRAINT `ext_dbs_practice_locs_ibfk_4` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `ext_dbs_practice_locs_ibfk_1` FOREIGN KEY (`ext_db_id`) REFERENCES `ext_dbs` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `ext_dbs_practice_locs_ibfk_2` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `ext_dbs_practice_locs_ibfk_3` FOREIGN KEY (`ext_db_id`) REFERENCES `ext_dbs` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `legal_attys`
--
ALTER TABLE `legal_attys`
  ADD CONSTRAINT `legal_attys_ibfk_1` FOREIGN KEY (`legal_firm_id`) REFERENCES `legal_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `legal_attys_users`
--
ALTER TABLE `legal_attys_users`
  ADD CONSTRAINT `legal_attys_users_ibfk_1` FOREIGN KEY (`legal_atty_id`) REFERENCES `legal_attys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `legal_attys_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `legal_firms_users`
--
ALTER TABLE `legal_firms_users`
  ADD CONSTRAINT `legal_firms_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `legal_firms_users_ibfk_2` FOREIGN KEY (`legal_firm_id`) REFERENCES `legal_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `legal_users`
--
ALTER TABLE `legal_users`
  ADD CONSTRAINT `legal_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `marketers`
--
ALTER TABLE `marketers`
  ADD CONSTRAINT `marketers_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `marketers_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifications_users`
--
ALTER TABLE `notifications_users`
  ADD CONSTRAINT `notifications_users_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `portal_activity_logs`
--
ALTER TABLE `portal_activity_logs`
  ADD CONSTRAINT `portal_activity_logs_ibfk_3` FOREIGN KEY (`portal_activity_id`) REFERENCES `portal_activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `portal_activity_logs_ibfk_4` FOREIGN KEY (`legal_portal_activity_id`) REFERENCES `legal_portal_activities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `practices`
--
ALTER TABLE `practices`
  ADD CONSTRAINT `practices_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `practices_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `practice_finances`
--
ALTER TABLE `practice_finances`
  ADD CONSTRAINT `practice_finances_ibfk_9` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `practice_finances_ibfk_4` FOREIGN KEY (`ext_dbs_fin_class_id`) REFERENCES `ext_dbs_fin_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `practice_finances_ibfk_5` FOREIGN KEY (`fin_grp_id`) REFERENCES `fin_grps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `practice_finances_ibfk_6` FOREIGN KEY (`practice_id`) REFERENCES `practices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `practice_finances_ibfk_7` FOREIGN KEY (`ext_dbs_fin_class_id`) REFERENCES `ext_dbs_fin_classes` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `practice_finances_ibfk_8` FOREIGN KEY (`fin_grp_id`) REFERENCES `fin_grps` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
