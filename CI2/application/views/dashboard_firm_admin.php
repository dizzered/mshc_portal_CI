<?php echo $users_dialog; ?>

<?php
if ($dashboard_banner)
{
	echo '<div class="dashboard-banner">'.$dashboard_banner.'</div>';
}
?>

<table width="100%" class="firm_admin_dashboard" style="margin-top:40px;">
	<tr>
    	<td style="padding-right: 25px; width:505px">
			<div class="main-container-header" style="background-color:#e4ecef">
				<table border="0" class="main-container-header-tab">
					<tr><td><span class="ico-header-title" style="margin-right:15px;">Notifications</span></td></tr>
			    </table>
			</div>
			<div class="main-container">
				<?php echo $notifications; ?>
			</div>
			<!--<img src="/images/dashboard_live_chat.png" width="503" style="margin:25px 0;" class="fnCallbackDialog" />-->

			<div style="margin:25px 0;">
			<!-- BEGIN ProvideSupport.com Graphics Chat Button Code -->
			
			<div id="ciAAyL" style="z-index:100;position:absolute"></div><div id="scAAyL" style="display:inline"></div><div id="sdAAyL" style="display:none"></div><script type="text/javascript">var seAAyL=document.createElement("script");seAAyL.type="text/javascript";var seAAyLs=(location.protocol.indexOf("https")==0?"https":"http")+"://image.providesupport.com/js/1pbkr4y52h6us10r6sswgbgn3n/safe-standard.js?ps_h=AAyL&ps_t="+new Date().getTime();setTimeout("seAAyL.src=seAAyLs;document.getElementById('sdAAyL').appendChild(seAAyL)",1)</script><noscript><div style="display:inline"><a href="http://www.providesupport.com?messenger=1pbkr4y52h6us10r6sswgbgn3n">Customer Service</a></div></noscript>
			
			<!-- END ProvideSupport.com Graphics Chat Button Code -->
			</div>
			
			<img src="/images/dashboard_manual.png" width="503" style="display:none;" />
		</td>
    	<td>
    
    		<?php echo $cases_search_form; ?>
		
			<div class="clear" style="height:30px;"></div>
			
			<?php echo $users_table; ?>
            
	</td>
    </tr>
</table>
