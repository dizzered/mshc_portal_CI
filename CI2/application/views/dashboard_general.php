<?php
if ($dashboard_banner)
{
	echo '<div class="dashboard-banner">'.$dashboard_banner.'</div>';
}
?>

<table width="100%">
	<tr>
    	<td width="40%" style="padding: 0px 10px">
        	<?php echo $recently_added_users; ?>
	</td>
    <td style="padding: 0px 10px">
    		<div class="main-container-header">
                <table border="0" class="main-container-header-tab">
                    <tr><td><span class="ico-header-title" style="margin-right:15px;">Recently Added Firm/Attorneys</span></td></tr>
                </table>
            </div>
            <div class="main-container">
<?php
if (isset($last_firms) && is_array($last_firms)) {
	echo '<table>';
		for ($i = 0; $i < count($last_firms); $i++) {
			echo '<tr><td style="padding-left:15px;">';
			echo $last_firms[$i]['name'];
			echo '</td><td>';
			echo $last_firms[$i]['attorneys'];
			echo '</td><td>';
			echo date('M j, Y, g:iA', strtotime($last_firms[$i]['created']));
			echo '</td></tr>';
			echo '<tr><td colspan="3" style="padding: 10px 0"><hr size="3" color="#ccc"/></td></tr>';
		}
	echo '</table>';
}
?>
		</div>
	</td>
    </tr>
</table>