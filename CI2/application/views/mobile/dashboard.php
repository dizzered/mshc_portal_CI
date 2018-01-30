<?php
if ($dashboard_banner)
{
	echo '<div class="dashboard-banner">'.$dashboard_banner.'</div>';
}
?>

<div style="padding: 11px;">

	<?php if (isset($notifications) && is_array($notifications)) { ?>

	<div class="roundbox">

    		<div class="roundbox-header header1">Notifications</div>

			<?php 
					
					for ($i = 0; $i < count($notifications); $i++) 
					{ 
			?> 
                    <div class="notification icon_<?php echo $notifications[$i]['type']; ?>">
                    
                        <h2 style="font-weight:<?php echo $notifications[$i]['read'] == 1 ? 'normal' : 'bold'; ?>;"><?php echo $notifications[$i]['title']; ?></h2>
            
                        <p><?php echo $notifications[$i]['body']; ?></p>
            
                    </div>
   			<?php } ?>
             <div class="notification-bottom">

                <div style="float: left"><a href="<?php echo base_url().MSHC_NOTIFICATIONS_CONTROLLER_NAME; ?>"><?php echo $count_new_notifications; ?> New notification</a></div>
    
                <div style="float: right"><a href="<?php echo base_url().MSHC_NOTIFICATIONS_CONTROLLER_NAME; ?>">view all ></a></div>
    
                <div class="clear"></div>

        	</div>

    </div>
    
    <?php } ?>
    
    <?php

$client_cases_name = array(
	'name'	=> 'client_cases_name',
	'id'	=> 'client_cases_name',
	'value' => '',
	'maxlength'	=> 30,
	'size'	=> 25,
	'class' => 'search_input'
);

$client_cases_ssn = array(
	'name'	=> 'client_cases_ssn',
	'id'	=> 'client_cases_ssn',
	'value' => '',
	'maxlength'	=> 30,
	'size'	=> 25,
	'class' => 'search_input'
);
$client_cases_account = array(
	'name'	=> 'client_cases_account',
	'id'	=> 'client_cases_account',
	'value' => '',
	'maxlength'	=> 30,
	'size'	=> 25,
	'class' => 'search_input'
);
?>

    <div class="roundbox">
    
            <div class="roundbox-header header2">Search by...</div>
    
            <form name="client_cases_search" id="client_cases_search" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME; ?>" method="post">
    
            <div class="search-box">
    
                <h2>Client Cases</h2>
    
                <div class="se">
    
                    <?php echo form_label('Name', $client_cases_name['id']); ?>
    
                     <?php echo form_input($client_cases_name); ?>
    
                </div>
    
                <div class="se">
    
                    <?php echo form_label('SSN', $client_cases_ssn['id']); ?>
    
                     <?php echo form_input($client_cases_ssn); ?>
    
                </div>
    
                <div class="se">
    
                    <?php echo form_label('Account', $client_cases_account['id']); ?>
    
                     <?php echo form_input($client_cases_account); ?>
    
                </div>
    			
				<div class="se">
				<?php
				if ($attys)
				{
					echo form_label('Attorney', 'client_cases_atty');
					$attys_list = array();
					$attys_list[0] = '--- Choose Attorney ---';
					foreach ($attys as $atty)
					{
						if (element('legal_atty_id', $atty)) $atty_id = element('legal_atty_id', $atty);
						else $atty_id = element('id', $atty);
						$attys_list[$atty_id] = $atty['last_name'].', '.$atty['first_name'];
					}
					echo '<div class="styled-select select_cases_search_container">';
					echo form_dropdown('client_cases_atty', $attys_list, NULL, 'class="select_cases_search" id="client_cases_atty" style="width:100%;"');
					echo '</div>';
				}
				elseif ($my_cases)
				{
					?>
					<label for="my_cases" style="margin-right:10px;">My Cases:</label>
					<input type="checkbox" name="client_cases_my_cases" id="client_cases_my_cases" checked="checked" value="true" style="margin:0; padding:0;margin-top:15px;" />
					<?php
				}
				
				?>
				</div>
                <div style="margin-top:20px;"><a class="advanced-search" href="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/'.MSHC_CASES_CLIENT_SEARCH_NAME.'/advanced'; ?>">advanced search ></a><input type="submit" id="btn_client_cases_dashboard" name="btn_client_cases_dashboard" value="search" class="black_search_input"/></div>
    
            </div>
    
            </form>
    
        </div>
    
    </div>
