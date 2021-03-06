<?php
// -------------------------------------------------------------------------------------
// Cron dispatcher
// -------------------------------------------------------------------------------------

if (!function_exists('curl_init')) die('CURL is not installed!');

// Timezone to UTC
date_default_timezone_set('UTC');

// Set basepath to anything just to define it. This will bypass checks for existence
DEFINE ('BASEPATH','1');

// Set the correct hash to secure call to the controller
$current_time = time();
//$md5_hash = md5($hash_value.$current_time);

DEFINE('CRON_CALL_URL','https://portal.mshclegal.com:8111/'); // make sure there's ending slash
//DEFINE('CRON_CALL_URL','http://mshc.local/'); // make sure there's ending slash

// sleep for 15 seconds to delay start. This works around the issue of new users being added to the server in the first few seconds of every minute
// which can screw up DNS entries
//sleep(15);

/*
| -----------------------------------------------------------------------------
|
| Set up and invoke a call to 'create_notifications'
|
| Schedule this job to run every day at 08:00
| -----------------------------------------------------------------------------
 */

$url = CRON_CALL_URL.'cron/create_notifications';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$output = curl_exec($ch);
curl_close($ch);

// ----------------- END notifications ---------------------------------

/*
| -----------------------------------------------------------------------------
|
| Set up and invoke a call to 'create_high_charges'
|
| Schedule this job to run every day at 08:30
| -----------------------------------------------------------------------------
 */


$url = CRON_CALL_URL.'cron/create_high_charges';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$output = curl_exec($ch);
curl_close($ch);

// ----------------- END notifications ---------------------------------

/* End of file cron1.php */
/* Location: cron1.php */