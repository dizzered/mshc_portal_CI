<?php
// Account dialog
echo $account_dialog;
?>

<script type="text/javascript">
    var userRole = '<?php echo get_user_role_name($this->_user['role_id']); ?>';
</script>

<div class="header_user_bar">
	<div  class="header_user_bar_ins">
    	<div class="username">
        	<span>Welcome back,</span> 
            <?php echo anchor(base_url(), $user['username'], 'class="usernamelink"'); ?>
        </div>
        <div class="sign_help_contacts">
        	<div class="signout">
                <span class="divider">|</span> Contact us: (410) 933-5678 
                <?php echo anchor(base_url().MSHC_AUTH_CONTROLLER_NAME.'/logout', 'Signout', 'class="signout_button"'); ?>
            </div>
        	<div style="float:right;">
				<?php
                echo '<ul class="help"><li>';
                echo anchor(base_url().MSHC_HELP_CONTROLLER_NAME,'Help/Faq','class="helpfaq"');
                if (array_key_exists(MSHC_HELP_CONTROLLER_NAME, $sub_menus))
                {
                    echo '<ul class="help_menu">';
                    foreach($sub_menus[MSHC_HELP_CONTROLLER_NAME] as $method_name => $method_title)
                    {
                        echo '<li>'.anchor(base_url().MSHC_HELP_CONTROLLER_NAME.'/'.$method_name, $method_title).'</li>';
                    }
                    echo '</ul>';
                }
                echo '</li></ul>';
                ?>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<?php echo $header_logos; ?>

<?php
if (is_array($main_menu) && count($main_menu))
{
	?>
    <div class="heder_menu" >
    	<div  class="heder_menu_ins">
        	<div class="left_menu_ctr">
            	<ul class="h_menu">
				<?php
                foreach($main_menu as $path => $title)
                {
                    $uri = uri_string();
                    $uri_ary = explode('/',$uri);
					$active = '';
                    if ((strlen($uri_ary[0]) == 0 && $path == MSHC_HOME_CONTROLLER_NAME) || $uri_ary[0] == $path)
                    {
                        $active = 'class="h_m_active"';
                    }
					if ($path == MSHC_ADMIN_CONTROLLER_NAME)
					{
						echo '<li><span '.$active.'>'.$title.'</span>';
					}
					else
					{
						echo '<li>'.anchor(base_url().$path, $title, $active);
					}
					if (array_key_exists($path, $sub_menus))
					{
						echo '<ul>';
						foreach($sub_menus[$path] as $method_name => $method_title)
						{
							echo '<li>'.anchor(base_url().$path.'/'.$method_name, $method_title).'</li>';
						}
						echo '</ul>';
					}
					echo '</li>';
                }
                ?>
                </ul>
            </div>
            <div class="right_menu_ctr"><?php echo $page_title; ?></div>
            <div style="clear:both"></div>
        </div>
    </div>
	<?php
}
 	
if ( is_array($breadcrumbs) && ! array_key_exists(MSHC_HOME_CONTROLLER_NAME, $breadcrumbs) ) 
{ 
?>
<div class="breadcrumbs-container">
	<div class="breadcrumbs">
	<?php	
	$count = 0;
	$uri = '';
	foreach($breadcrumbs as $path => $title)
	{
		++$count;
		$uri .= $path.'/';
		if ($path != $current_path)
		{
			echo anchor(base_url().$uri,$title);
		}
		else
		{
			if ($path == MSHC_ADMIN_CLIENTS_NAME)
			{
				$uri_str = explode('/',uri_string());
				$client_id = get_array_value('2', $uri_str);
				if ($client_id)
				{
					echo anchor(base_url().$uri,$title).' > Practice Maintenance';
				}
				else
				{
					echo $title;
				}
			}
			else
			{
				echo $title;
			}
		}
		if ($count < count($breadcrumbs)) echo ' > ';
	}
	?>
    </div>
</div>
<?php 
} ?>

<div class="container">
	<div class="container_ins">