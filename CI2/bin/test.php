<?php 
define('BASEPATH', 'C:\apache-webapps\portal\application');
define('PMS_CONN', 'live');
include_once('application/libraries/MSHC_Connector.php');
$mshcConn = new MSHC_Connector();
$params = array('conds' => array('account' => 5080));
$cases = $mshcConn->getCases(array(1,2,3,4,5), 'all', $params);
?>

<pre><?php print_r($cases); ?></pre>