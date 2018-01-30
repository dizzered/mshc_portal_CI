<div class="main-container-header">
    <table border="0" class="main-container-header-tab">
        <tr><td><span class="ico-header-title" style="margin-right:15px;">Latest Activity Log</span></td></tr>
    </table>
</div>
<div class="main-container">
<?php 
    if (isset($latest_activity_log) && is_array($latest_activity_log)) {
            echo '<table class="jtable"><thead><tr><th class="jtable-column-header">Date</th><th class="jtable-column-header">User</th><th class="jtable-column-header">Attorney</th><th class="jtable-column-header">Firm</th><th class="jtable-column-header">Patient</th><th class="jtable-column-header">SSN</th><th class="jtable-column-header">Event</th><th class="jtable-column-header">Details</th></tr></thead><tbody>';
            for ($i = 0; $i < count($latest_activity_log); $i++) {
                echo '<tr';
                if ($i % 2 == 1) {
                    echo ' class="jtable-row-even" ';
                }
                echo '><td>';
                echo date('m/d/Y, g:iA', strtotime($latest_activity_log[$i]['created']));
                echo '</td><td>';
                echo $latest_activity_log[$i]['last_name'];
                if ($latest_activity_log[$i]['last_name'] != '' && $latest_activity_log[$i]['first_name'] != '') echo ', ';
                echo $latest_activity_log[$i]['first_name'];
                echo '</td><td>';
                echo '</td><td>';
                echo '</td><td>';
                echo '</td><td>';
                echo '</td><td>';
                echo $latest_activity_log[$i]['name'];
                echo '</td><td>';
                echo $latest_activity_log[$i]['info'];
                echo '</td></tr>'; 
            }
            echo '</tbody></table><br />';
            echo '<div style="text-align: right; padding-right: 15px">
                        <a href="'.base_url().MSHC_ADMIN_CONTROLLER_NAME.'/'.MSHC_ADMIN_ACTIVITIES_NAME.'" style="color:#bb8e01; text-decoration: none; font-weight: bold; cursor: pointer">view all ></a>
                    </div>';
    }
?>
</div>