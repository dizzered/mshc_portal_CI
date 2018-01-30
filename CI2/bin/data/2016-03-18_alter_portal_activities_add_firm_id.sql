ALTER TABLE `portal_activity_logs`
ADD COLUMN `firm_id` int(11) DEFAULT null AFTER `session_id` ;