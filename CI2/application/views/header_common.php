<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0">
<meta http-equiv="expires" content="0">
<meta http-equiv="Expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<title><?php echo MSHC_COMPANY_NAME.(strlen($page_title) > 0 ? ' - '.$page_title : ''); ?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $main_css; ?>?<?php echo release_date(); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="/css/dialogs.css?<?php echo release_date(); ?>" />
<link rel="icon" type="image/png" href="/favicon.png" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="description" content="<?php echo $page_description;?>" />
<meta name="keywords" content="<?php echo $page_keywords;?>" />
<script type="text/javascript" src="/js/jquery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery/jtable2/jquery.jtable.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-treeview/jquery.treeview.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-metadata/jquery.metadata.js"></script>
<script type="text/javascript" src="/js/date.js"></script>
<script type="text/javascript" src="/js/dialogs.js?<?php echo release_date(); ?>"></script>
<script type="text/javascript" src="/js/tables.js?<?php echo release_date(); ?>"></script>
<script type="text/javascript" src="/js/main.js?<?php echo release_date(); ?>"></script>
<link rel="stylesheet" type="text/css" media="all" href="/js/jquery/jquery-ui/css/custom-theme/jquery-ui-1.9.1.custom.css" />
<link rel="stylesheet" type="text/css" media="all" href="/js/jquery/jtable2/themes/lightcolor/jtable_lightcolor_base.css" />
<link rel="stylesheet" type="text/css" media="all" href="/js/jquery/jquery-treeview/jquery.treeview.css" />
<link rel="stylesheet" type="text/css" media="all" href="/css/tables.css" />
<link rel="stylesheet" type="text/css" media="all" href="/css/trees.css" />

<?php
// Load css for Firefox
$nav = (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
if (stristr($nav, "firefox")) {
	?>
	<link rel="stylesheet" type="text/css" media="all" href="/css/firefox.css" />
    <?php
}

// Load custom scripts
foreach($custom_scripts as $script)
{
	echo $script;
}

// Load custom css
foreach($custom_styles as $style)
{
	echo $style;
}
?>
<script>
var baseURL = '<?php echo base_url(); ?>';
var ajaxCONTROLLER = '<?php echo MSHC_AJAX_CONTROLLER_NAME; ?>';
var adminCONTROLLER = '<?php echo MSHC_ADMIN_CONTROLLER_NAME; ?>';
var casesCONTROLLER = '<?php echo MSHC_CASES_CONTROLLER_NAME; ?>';
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
var emailPattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var extFileFormsPattern = ".(pdf|doc|docx)$";
var integerPattern = "^[0-9]+$";
var userPrimaryFirm = {};
var locationsSource = [];
var accidentsTypeSource = [];
var attorneysSource = [];
var cases_account_id = -1;
var cases_account = [];
var cases_account_index = -1;
var loadTable = true;
var caseManagerUserID = 0;
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

<!-- dialog box for change password -->
<div id='dialog-change-password' style='display:none;'>
    <div class="dialog-popup-container">
    	<div class="dialog-popup-content" id='dialog-change-password-text'>
            <h2>Change Password</h2>
            Password should be at least 7 characters in length and include at least two non-alpha characters.<br /><br />
            <form id="change-password-form">
            <label for="new-password">New Password:</label><br />
            <input type="password" name="new-password" id="new-password" value="" class="password-width" tabindex="1" />
            <span id="show-password"><input type="checkbox" id="new-password-show" tabindex="3" /> Show password</span>
            <br />
            <div id="new-password-error" style="color:red;">&nbsp;</div>
            <label for="new-password-confirm">Confirm New Password:</label><br />
            <input type="password" name="new-password-confirm" id="new-password-confirm" value="" class="password-width" tabindex="2" />
            <div id="new-password-confirm-error" style="color:red;">&nbsp;</div>
            </form>
        </div>
    </div>
</div>
<!-- END dialog box for call back phone -->

<!-- dialog box for general messages -->
<div id='dialog-callback-form' style='display:none;'>
	<div class="dialog-popup-container">
    	<div class="dialog-popup-content" id='dialog-callback-form-text'>
			<p class="red_text">Requests recieved after 5:00 PM Monday - Thursday or after 4:30 PM Friday will be replied to the following business day.</p>
				
			<p class="red_text bold_text">For immediate appointment please call our Appointment Request Line at 888-807-2778 or enter your number below and we will call you immediately!</p>
			
			<div class="call_back">
			
				<p class="bold_text">Enter your number and we will call you.</p>
				<?php
				$callback_code = array(
					'name' => 'callback_code',
					'value' => '',
					'maxlength' => '3'
				);
				$callback_station_code = array(
					'name' => 'callback_station_code',
					'value' => '',
					'maxlength' => '3'
				);
				$callback_number = array(
					'name' => 'callback_number',
					'value' => '',
					'maxlength' => '4'
				);
				?>
				<span class="bold_text" style="margin-right:3px; margin-top:3px;">(</span>
				<?php echo form_input($callback_code); ?>
				<span class="bold_text" style="margin:3px 8px 0 3px;">)</span>
				<?php echo form_input($callback_station_code); ?>
				<span class="bold_text" style="margin:3px 7px 0 7px;">&ndash;</span>
				<?php echo form_input($callback_number); ?>
				<input type="button" value="Call" name="callback_btn" class="input-button-dark-grey" style="padding:6px 22px;margin-top:-1px; margin-left:15px;" />
				
				<div class="clear" style="height:10px;"></div>
				
				<div style="text-align:right;"><a href="http://www.patlive.com/" target="_blank">Powered by TeleRep</a></div>
				
				<div class="clear"></div>
				
			</div>
		</div>
    </div>
</div>
<!-- END dialog box for call back phone -->

<div id="sorting-date" class="jtable-sorting-container">
<div style="padding:6px 8px;" id="sorting-equal">Equal to</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-not-equal">Not Equal to</div>
</div>

<div id="sorting-alpha" class="jtable-sorting-container">
<div style="padding:6px 8px;" id="sorting-contains">Contains</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-equal">Equal to</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-not-equal">Not Equal to</div>
</div>

<div id="sorting-digit" class="jtable-sorting-container">
<div style="padding:6px 8px;" id="sorting-contains">Contains</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-equal">Equal to</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-not-equal">Not Equal to</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-greater-than">Greater than</div>
<hr style="padding:0;margin:2px 0;border-bottom:1px solid #d2d2d2;">
<div style="padding:6px 8px;" id="sorting-less-than">Less than</div>
</div>

<script>
var sortingDateHolder = $('#sorting-date');
var sortingAlphaHolder = $('#sorting-alpha');
var sortingDigitHolder = $('#sorting-digit');
var sortingFieldName = '';
$('#sorting-date').remove();
$('#sorting-alpha').remove();
$('#sorting-digit').remove();
if (jQuery.browser.version < 9.0) {
	$('#show-password').hide();
}
</script>