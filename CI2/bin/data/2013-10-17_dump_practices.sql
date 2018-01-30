DELETE FROM `practices`;

INSERT INTO `practices` (`id`, `name`, `short_name`, `is_inactive`, `created`, `created_by`, `modified`, `modified_by`, `client_id`, `ext_db_id1`, `external_id1`, `ext_db_id2`, `external_id2`, `ext_db_id3`, `external_id3`, `split_charges`, `medical_group`, `surgical_group`, `pt_group`) VALUES
(3, 'MDDC, LLC', NULL, NULL, '2013-10-17 05:02:49', '1', '2013-10-17 05:11:25', '1', 1, 1, '3', 0, '', 0, '', 1, 2, 0, 3),
(4, 'MED, LLC', NULL, NULL, '2013-09-27 17:56:52', '1', '2013-10-17 05:01:57', '1', 1, 1, '4', 0, '', 0, '', 1, 8, 0, 0),
(8, 'Multi-Specialty HealthCare', NULL, NULL, '2013-10-17 05:12:31', '1', '2013-10-17 07:50:51', '1', 1, 1, '1', 3, '1', 5, '1', 1, 2, 0, 3),
(9, 'Baltimore Work Rehab', NULL, NULL, '2013-10-17 05:14:33', '1', '2013-10-17 05:14:59', '1', 1, 1, '2', 2, '1', 0, '', 0, 0, 0, 0),
(10, 'MRImages', NULL, NULL, '2013-10-17 05:15:42', '1', '2013-10-17 05:15:42', '1', 1, 1, '1', 4, '1', 0, '', 0, 0, 0, 0);