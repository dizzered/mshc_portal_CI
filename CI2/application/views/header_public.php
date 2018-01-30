<div class="header_user_bar">
	<div  class="header_user_bar_ins">
    	<div class="username">
        	
        </div>
        <div class="sign_help_contacts">
        	<div class="signout">
                <!--span class="divider">|</span--> Contact us: (410) 933-5678
            </div>
        	<!--div style="float:right;"-->
				<!--?php
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
            </div-->
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<?php echo $header_logos; ?>
<div class="container">
	<div class="container_ins">