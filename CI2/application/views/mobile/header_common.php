<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo MSHC_COMPANY_NAME.(strlen($page_title) > 0 ? ' - '.$page_title : ''); ?></title>
<link rel="stylesheet" type="text/css" media="all" href="/css/mobile.css" />
<link rel="icon" type="image/png" href="/favicon.png" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="description" content="<?php echo $page_description;?>" />
<meta name="keywords" content="<?php echo $page_keywords;?>" />
<meta name="viewport" content="width=640" />
<script src="/js/jquery/jquery-1.8.2.min.js"></script>
<script src="/js/jquery/jquery-ui/js/jquery-ui-1.9.1.custom.min.js"></script>
<script src="/js/jquery/jtable/jquery.jtable.js"></script>
<script src="/js/jquery/jquery-treeview/jquery.treeview.js"></script>
<script src="/js/jquery/jquery-metadata/jquery.metadata.js"></script>
<script src="/js/date.js"></script>
<script src="/js/mobile.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="/js/jquery/jquery-ui/css/custom-theme/jquery-ui-1.9.1.custom.css" />
<link rel="stylesheet" type="text/css" media="all" href="/js/jquery/jtable/themes/lightcolor/jtable_lightcolor_base.css" />

<?php
// Load css for Firefox
$nav = (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
if (stristr($nav, "firefox"))
{
	?>
	<link rel="stylesheet" type="text/css" media="all" href="/css/firefox.css" />
    <?php
}

//// Load custom scripts
//foreach($custom_scripts as $script)
//{
//	echo $script;
//}
//
//// Load custom css
//foreach($custom_styles as $style)
//{
//	echo $style;
//}
?>
<script>
var baseURL = '<?php echo base_url(); ?>';
var ajaxCONTROLLER = '<?php echo MSHC_AJAX_CONTROLLER_NAME; ?>';
var adminCONTROLLER = '<?php echo MSHC_ADMIN_CONTROLLER_NAME; ?>';
var uniqueUsername = 'false';
var uniqueEmail = 'false';
var uniqueFirmname = 'false';
var jtablePageSize = 10;
var rowID = 0;
var tableID ='';
var tableName = '';
var dbName = '';
var isFirmUpdated = false;
var isFormUpdated = false;
var isClientUpdated = false;
var isPracticeUpdated = false;
var firmDlgPrimary = new Array();
var counterFileUpload = 1;
var maxCounterFileUpload = 10;
var sQriteria = '';
var sortingFieldName = '';
var sVal = '';
var sVal2 = '';
var sSSN = '';
var sName = '';
var sAccount = '';
var clientID = 0;
var counterApptReason = 0;
var emailPattern = "^[A-z0-9_\-]+(\.[_A-z0-9\\-]+)*@([_A-z0-9\\-]+\.)+([A-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$";
var extFileFormsPattern = ".(pdf|doc|docx)$";
var integerPattern = "^[0-9]+$";
$(function() {
	// Metadata settings to attribute "data"
	$.metadata.setType('attr','data');
});
</script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-51449012-1', 'mshclegal.com');
    ga('send', 'pageview', {'dimension1': <?php echo isset($this->_user['user_id']) ? '\''.$this->_user['firm_name'].'\'' : '\'\''; ?>}, {'dimension2': <?php echo isset($this->_user['user_id']) ? '\''.$this->_user['username'].'\'' : '\'\''; ?>});
  </script>
</head>
<body>

<!-- dialog box for general messages -->
<div id='dialog-general-message' style='display:none;'>
	<div class="dialog-popup-container">
    	<div class="dialog-popup-content" id='dialog-general-message-text'></div>
    </div>
</div>
<!-- END dialog box for general messages -->

<!-- dialog box for prompt messages -->
<div id='dialog-prompt-message' style='display:none;'>
	<div class="dialog-popup-container">
    	<div class="dialog-popup-content" id='dialog-prompt-message-text'></div>
    </div>
    <input type='hidden' name='prompt_func_name' id='prompt_func_name' value=''/>
</div>
<!-- END dialog box for prompt messages -->

<!-- dialog box for please wait message -->
<div id='dialog-please-wait' style='display:none;'>
	<div class="dialog-popup-container" style='background:none;text-align:center;color:#ffffff;' id='dialog-please-wait-text'>
    <h2 id='dialog-please-wait-title'>Please wait...</h2>
    <img id="ajax-loader" src="/images/ajax_loader_white.gif" width="64" height="64" />
    </div>
</div>
<!-- END dialog box for please wait message -->

<div id="iphoneWrapper">