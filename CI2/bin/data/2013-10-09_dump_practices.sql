INSERT INTO `ext_dbs` (`id`, `name`) VALUES
(1, 'AMM_LIVE'),
(2, 'BWR'),
(3, 'MD'),
(4, 'MRI'),
(5, 'PT');


INSERT INTO `ext_dbs_fin_classes` (`id`, `name`, `ext_db_id`, `external_id`) VALUES
(1, 'Pharmacy', NULL, NULL),
(2, 'Medical', NULL, NULL),
(3, 'Surgical', NULL, NULL),
(4, 'Braces', NULL, NULL),
(5, 'PT/Chiro', NULL, NULL),
(6, 'BWR', NULL, NULL),
(7, 'MRI', NULL, NULL);