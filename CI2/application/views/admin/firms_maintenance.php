<div id="dialog-add-name-firm" style="display:none">
	<div class="dialog-popup-container">
		<div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add Firm</h2>
            <form id="firm-name-maintenance" style="background-color:#f7f7f7;border:1px solid #e8e8e8;padding:10px;">
                <fieldset>
                <input type="hidden" id="view_id" name="view_id" value="" />
                <label for="name" style="float:left;">Name:</label>
                <input type="text" name="name" id="name" value="Firm Name" style="float:left;" />&nbsp;&nbsp;
                <img id="firmname-ajax-loader" src="/images/ajax_loader.gif" width="16" height="16" style="display:none;" />
                <span id="firmname-msg" style="float:left;display:none; margin-left:10px; margin-top:4px;"></span>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<div id="dialog-attorneys" title="" style="display:none">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add Attorney</h2>
            <table class="dialog-popup-tabs-table">
                <tr>
                <td class="dialog-popup-tabs-round-left">&nbsp;</td>
                <td class="dialog-popup-tabs-round-center">
                <ul id="tabs">
                    <li id="atty-general">
                        <span>General</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="atty-assigned">
                        <span>Assigned</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="atty-unassigned-search">
                        <span>Unassigned - Search</span>
                        <span>&nbsp;</span>
                    </li>
                    <li id="atty-unassigned-all">
                        <span>Unassigned - All</span>
                        <span>&nbsp;</span>
                    </li>
                </ul>
                </td>
                <td>&nbsp;</td>
                <td class="dialog-popup-tabs-round-right">&nbsp;</td>
                </tr>
            </table>
            <div class="tabs-container">
                <form id="atty-maintenance">
                <fieldset>
                <input type="hidden" id="atty_view_id" name="atty_view_id" value="" />
                <input type="hidden" id="atty_firm_id" name="atty_firm_id" value="" />
                <div class="atty-general fnTabs">
                    <div style="float:left;width:350px;">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" value="" class="text" /><br>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="lastname-msg" style="color:red;height:15px;">&nbsp;</div>
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" value="" class="text" /><br>
                        <div style="float:left;width:80px;">&nbsp;</div>
                        <div id="firstname-msg" style="color:red;height:15px;">&nbsp;</div>
                        <div style="border:1px solid #cccccc;background-color:#eaeaea;padding:10px;width:312px;">
                        	<div style="color:#454545;font-weight:bold;margin-bottom:10px;">Delivery</div>
                            <label for="statements" style="width:140px;">Statements:</label>
                            <select id="statements">
                            	<option value="hardcopy">Hardcopy</option>
                            </select>
                        	<div style="color:red;height:10px;">&nbsp;</div>
                            <label for="missed_appintment" style="width:140px;">Missed Appointment Notifications:</label>
                            <select id="missed_appintment">
                            	<option value="email">Email</option>
                                <option value="notification">Notification</option>
                            </select>
                        	<div style="color:red;height:10px;">&nbsp;</div>
                        </div>
                    </div>
                    <div style="float:left;width:345px;">
                        <label for="statement_frequency" style="width:137px;">Statement Frequency:</label>
                        <select id="statement_frequency" style="width:208px;">
                         	<option value="default">Default</option>
                        </select>
                        <div style="color:red;height:10px;">&nbsp;</div>
                        <label for="missed_appointment_threshold" style="width:137px;">Missed Appointment Threshold:</label>
                        <input id="missed_appointment_threshold" type="number" min="0" max="10" step="1" value="1">

                    </div>
                </div>
                
                <div class="atty-assigned fnTabs">
                    <div id="atty-assigned-list" style="float:left;width:350px; padding:5px 0;"></div>
                    <div style="float:left;width:345px;text-align:right;">
                    	<input type="button" value="Unassign Selected" id="btn-atty-unassign-selected" class="input-button-grey" />
                    </div>
                    <div style="clear:both; height:10px;"></div>
                    <table class="jtable attys_assigned_table">
                    <?php
					$check_data = array(
						'name'        => 'select_all_attys_assigned',
						'id'          => 'select_all_attys_assigned',
						'value'       => '1',
						'checked'     => FALSE,
						'style'       => ''
					);
					?>
					<thead><tr>
                    <th style="width:35px;"><?php echo form_checkbox($check_data); ?></th>
                    <th style="width:120px;">Database</th>
                    <th style="width:165px;">Number</th>
                    <th>Attorney</th>
                    </tr></thead>
                    <tbody></tbody>
                    </table>
                </div>
                
                <div class="atty-unassigned-search fnTabs">
                    <div style="background-color:#eaeaea;border:1px solid #cccccc;padding:10px;">
                    	<label for="name" style="float:left; width:100px; margin-top:6px;">Attorney Name:</label>
                        <input type="text" name="search-atty-name" id="search-atty-name" value="" style="height:20px; margin-right:20px;position:relative;top:1px;" />
                        <input type="button" value="Find" id="btn-search-atty-name" class="input-button-grey" style="position:relative;width:60px; height:23px;"> 
                        <input type="button" value="Assign Selected" id="btn-atty-search-assign-selected" class="input-button-grey" style="float:right;height:23px;position:relative;">
                    </div>
                    <div style="clear:both;"></div>
                    <div id="search-atty-results-msg"></div>
                    <div id="search-atty-results"></div>
                </div>
                <div class="atty-unassigned-all fnTabs">
                    <div id="ext_attorneys_div" style="float:left;width:350px;background-color:#eaeaea;border:1px solid #cccccc;padding:10px;">
						<?php
						/*$db_attorneys_tree = "";
						if (count($ext_attorneys))
						{
							$ext_attys_ul = array();
							$first = TRUE;
							foreach($ext_attorneys['attorneys'] as $ext_atty)
							{
								if (!array_key_exists($ext_atty['database_name'], $ext_attys_ul))
								{
									if (!$first) echo '</div>';
									$first = FALSE;
									echo '<div class="fnExtDB"><span class="lastExpandable">'.$ext_atty['database_name'].'</span>';
									$ext_attys_ul[$ext_atty['database_name']] = array();
								}
								echo '<div class="fnExtAtty"><div class="check_off" data="{ext_atty_id:'
									.$ext_atty['employer_id'].', ext_db_id:'.array_search($ext_atty['database_name'], $ext_dbs).', ext_atty_name: \''
									.$ext_atty['employer_name'].'\', ext_db_name: \''.$ext_atty['database_name'].'\'}">'
									.$ext_atty['employer_name'].'</div></div>';
								$ext_attys_ul[$ext_atty['database_name']][] = array(
									'employer_name' => $ext_atty['employer_name'],
									'employer_id' => $ext_atty['employer_id']
								);
							}
							echo '</div>';
						}
						echo $db_attorneys_tree;*/
						
						if ($ext_dbs && count($ext_dbs))
						{
							$ext_dbs[0] = '--- Select database ---';
							sort($ext_dbs);
							echo form_dropdown('ext_dbs', $ext_dbs, NULL, 'id="ext_dbs"');
						}
						?>
						<div id="ext_attorneys_list"></div>
					</div>
                    <div style="float:left;width:323px;text-align:right;">
                    <input type="button" value="Select All" id="btn-atty-selecting" class="input-button-grey" />
                    <input type="button" value="Assign Selected" id="btn-atty-all-assign-selected" class="input-button-grey" />
                    </div>
                </div>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$uri = uri_string();
$uri = explode('/', $uri);
$alpha = get_array_value(2, $uri);
$order = get_array_value(3, $uri);
$controller = element(0, $uri, 'home');
$method = element(1, $uri, '');
$path = $controller ? $controller.($method ? '/'.$method.'/' : '/') : '';
if (is_null($alpha)) $alpha = 'all';
if (is_null($order)) $order = 'asc';

if ($alpha == 'all') $all_class = ' orange-button';
else $all_class = '';
?>

<?php
$letters = '';
if ($firms_table_header)
{
?>
<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td>
        <span class="ico-header-title" style="margin-right:15px;">Sort by Alphabetical</span>
        <a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_FIRMS_NAME; ?>" class="alpha-link<?php echo $all_class; ?>">All</a>&nbsp;
        <?php
		foreach(range('A','Z') as $i) {
			if (strtolower($i) == strtolower($alpha)) $alpha_class = ' orange-button';
			else $alpha_class = '';
			echo '<a href="'.base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_FIRMS_NAME.'/'.strtolower($i).'" class="alpha-link'.$alpha_class.'">'.$i.'</a> ';
		}
		?>
        </td>
        <td align="right">
        	<div id="btn-add-new-firm" class="tab-right-beige"><p>Add New Firm</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>
<?php
}
else
{
	foreach(range('A','Z') as $i) {
		if (strtolower($i) == strtolower($firms_table_alpha)) $alpha_class = ' orange-button';
		else $alpha_class = '';
		$letters .= '<a href="'.base_url().MSHC_HOME_CONTROLLER_NAME.'/'.strtolower($i).'" class="dashboard-alpha-link'.$alpha_class.'">'.$i.'</a> ';
	}
	$letters .= '<div class="clear" style="height:10px;"></div>';
}
?>

<?php
if ($firms_table_collumns == 2)
{
	$width = '49%';
	$height = '';
	$border = '';
}
else
{
	$width = '400px';
	$height = ' style="height:335px;overflow:auto;border: 1px solid #cfcfcf;"';
	$border = ' style="border: 0px solid #cfcfcf;"';
}
?>
<div class="main-container">
<?php
echo $letters;
$left = array();
$right = array();
if (is_array($attorneys) && count($attorneys))
{
	$counter = 0;
	$middle = ceil(count($attorneys) / 2);
	foreach($attorneys as $firm_id => $firm_obj)
	{
		++$counter;
		if ($firms_table_collumns == 2)
		{
			if ($counter <= $middle)
			{
				$ary = 'left';
			}
			else
			{
				$ary = 'right';
			}
		}
		else
		{
			$ary = 'left';
		}
		${$ary}[] = $firm_id;
	}
	?>
	<div<?php echo $height; ?>>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td style="width:<?php echo $width; ?>;">
    
    <table cellpadding="0" cellspacing="0" border="0" class="table"<?php echo $border; ?>>
    <thead>
    <tr>
    <th class="table-header-sortable"><div class="table-header-container"><span>Firm Name</span></div></th>
    </tr>
    </thead>
    <tr>
    <td align="left" width="100%">
    
    <?php
    $treeview_script = '<script>$(function() {';
    for ($i = 0; $i < count($left); ++$i)
    {
        $j = $left[$i];
        $firm = $attorneys[$j];
        if ((($i + 1) % 2)) $li_bg_color = 'ffffff';
        else $li_bg_color = 'f5f5f5';
        $treeview_script .= '
        /*$("#firms-attorneys-tree-'.$j.'").treeview({
            collapsed: true
        });*/
        ';
        ?>
        <ul id="firms-attorneys-tree-<?php echo $j; ?>" class="filetree fnFirmsTree treeview">
        <li data="{firm_id: <?php echo $j; ?>, firm_name: '<?php echo addslashes($firm['firm_name']); ?>'}" 
        style="background-color:#<?php echo $li_bg_color; ?>;" class="expandable lastExpandable">
		<div class="hitarea expandable-hitarea lastExpandable-hitarea"></div>
        <div class="fnFirmMaintenanceBox">
        <div class="fnFirmEdit" style="float:left;"><img src="/images/edit-grey.png" style="border-right:1px solid #d5d5d5;" alt="Edit Firm" title="Edit Firm" /></div>
        <div class="fnFirmAddAttorney" style="float:left;"><img src="/images/add-grey.png" style="border-right:1px solid #d5d5d5;" alt="Add Attorney" title="Add Attorney" /></div>
        <div class="fnFirmDelete" style="float:left;"><img src="/images/delete-grey.png" alt="Delete Firm" title="Delete Firm" /></div>
        </div>
        <div class="folder fnTreeName"><?php echo $firm['firm_name']; ?></div>
        <?php
        if (count($firm['firm_attorneys']))
        {
            echo '<ul style="display:none;">';
			$counter = 0;
            foreach($firm['firm_attorneys'] as $atty_id => $atty)
            {
                if ($atty_id)
                {
					++$counter;
                    $atty_name = $atty['last_name'];
                    if (!empty($atty['first_name'])) $atty_name .= ', '.$atty['first_name'];
					if ($counter == count($firm['firm_attorneys'])) $li_class = ' class="last"';
					else $li_class = '';
                    ?>
                    <li data="{atty_id: <?php echo $atty_id; ?>}"<?php echo $li_class; ?>><span class="file"><?php echo $atty_name; ?></span>
                    <div class="fnAttyMaintenanceBox">
                    <div class="fnAttyEdit" style="float:left;">
                    <img src="/images/edit-grey.png" style="border-right:1px solid #d5d5d5;" alt="Edit Attorney" title="Edit Attorney" />
                    </div>
                    <div class="fnAttyDelete" style="float:left;"><img src="/images/delete-grey.png" alt="Delete Attorney" title="Delete Attorney" /></div>
                    </div>
                    </li>
                    <?php
                }
            }
            echo '</ul>';
        }
        ?>
        </li>
        </ul>
        <?php
    }
    ?>
    
    </td>
    </tr>
    </table>
    
    </td>
	<?php
	if ($firms_table_collumns == 2)
	{
		?>
		<td style="width:2%">&nbsp;</td>
		<td style="width:<?php echo $width; ?>;">
		<?php
		if (count($right))
		{
			?>
			<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			<th>&nbsp;</th>
			</tr>
			</thead>
			<tr>
			<td align="left" width="100%">
			<?php
			for ($i = 0; $i < count($right); ++$i)
			{
				$j = $right[$i];
				$firm = $attorneys[$j];
				if ((($i + 1) % 2)) $li_bg_color = 'ffffff';
				else $li_bg_color = 'f5f5f5';
				$treeview_script .= '
				/*$("#firms-attorneys-tree-'.$j.'").treeview({
					collapsed: true
				});*/
				';
				?>
				<ul id="firms-attorneys-tree-<?php echo $j; ?>" class="filetree fnFirmsTree treeview">
				<li data="{firm_id: <?php echo $j; ?>, firm_name: '<?php echo addslashes($firm['firm_name']); ?>'}" 
				style="background-color:#<?php echo $li_bg_color; ?>;" class="expandable lastExpandable">
				<div class="hitarea expandable-hitarea lastExpandable-hitarea"></div>
				<div class="fnFirmMaintenanceBox">
				<div class="fnFirmEdit" style="float:left;"><img src="/images/edit-grey.png" style="border-right:1px solid #d5d5d5;" alt="Edit Firm" title="Edit Firm" /></div>
			<div class="fnFirmAddAttorney" style="float:left;"><img src="/images/add-grey.png" style="border-right:1px solid #d5d5d5;" alt="Add Attorney" title="Add Attorney" /></div>
			<div class="fnFirmDelete" style="float:left;"><img src="/images/delete-grey.png" alt="Delete Firm" title="Delete Firm" /></div>
				</div>
				<div class="folder fnTreeName"><?php echo $firm['firm_name']; ?></div>
				<?php
				
				if (count($firm['firm_attorneys']))
				{
					echo '<ul style="display:none;">';					
					$counter = 0;
					foreach($firm['firm_attorneys'] as $atty_id => $atty)
					{
						if ($atty_id)
						{
							++$counter;
							$atty_name = $atty['last_name'];
							if (!empty($atty['first_name'])) $atty_name .= ', '.$atty['first_name'];
							if ($counter == count($firm['firm_attorneys'])) $li_class = ' class="last"';
							else $li_class = '';
							?>
							<li data="{atty_id: <?php echo $atty_id; ?>}"<?php echo $li_class; ?>><span class="file"><?php echo $atty_name; ?></span>
							<div class="fnAttyMaintenanceBox">
							<div class="fnAttyEdit" style="float:left;">
							<img src="/images/edit-grey.png" style="border-right:1px solid #d5d5d5;" alt="Edit Attorney" title="Edit Attorney" />
							</div>
							<div class="fnAttyDelete" style="float:left;"><img src="/images/delete-grey.png" alt="Delete Attorney" title="Delete Attorney" /></div>
							</div>
							</li>
							<?php
						}
					}
					echo '</ul>';
				}
				?>
				</li>
				</ul>
				<?php
			}
			?>
			</td>
			<?php
		}
		?>
        </tr>
        </table>
        <?php
    }
    $treeview_script .= '});</script>';
    //echo $treeview_script;
    ?>
    </td>
    </tr>
    </table>
	</div>
	<?php
	if ($firms_table_view_all)
	{
		?>
		<div style="text-align:right;padding-top:10px; margin-bottom:-15px;">
		<a href="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_FIRMS_NAME; ?>" style="color:#517c8c; text-decoration: none; font-weight: bold; cursor: pointer">view all ></a>
		</div>
		<?php
	}
	?>
    <script>
    $(function() {
        $('.table-header-sortable').addClass('table-header-sorted-<?php echo $order; ?>');
        
        $('.table-header-sortable').live('click', function() {
            var uri = '<?php echo base_url().$path.$alpha.'/'; ?>';
            if ($(this).hasClass('table-header-sorted-asc'))
            {
                $(this).addClass('table-header-sorted-desc');
                $(this).removeClass('table-header-sorted-asc');
                uri = uri + 'desc';
            }
            else
            {
                $(this).addClass('table-header-sorted-asc');
                $(this).removeClass('table-header-sorted-desc');
                uri = uri + 'asc';
            }
            $(location).attr('href', uri);
        });
    });
    </script>
   	<?php
}
else
{
	?>
	<h1>No data found.</h1>
	<?php
}
?>
</div>
