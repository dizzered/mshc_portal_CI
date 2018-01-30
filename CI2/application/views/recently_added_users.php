<div class="main-container-header" style="background-color:#e4ecef">
    <table border="0" class="main-container-header-tab">
        <tr><td><span class="ico-header-title" style="margin-right:15px;">Recently Added Users</span></td></tr>
    </table>
</div>
<div class="main-container" style="padding-bottom:25px">
<?php 
    if (isset($last_users) && is_array($last_users)) {
            echo '<table width="100%">';
            for ($i = 0; $i < count($last_users); $i++) {
                echo '<tr style="line-height: 36px; border-bottom: 1px solid #ccc"><td width="40%" style="padding-left:15px;">';
                echo $last_users[$i]['last_name'];
                if ($last_users[$i]['last_name'] != '' && $last_users[$i]['first_name'] != '') echo ', ';
                echo $last_users[$i]['first_name'];
                echo '</td><td style="font-weight:bold">';
                echo $last_users[$i]['username'];
                echo '</td><td width="130px">';
                echo date('M j, Y, g:iA', strtotime($last_users[$i]['created']));
                echo '</td></tr>'; 
                //echo '<tr><td colspan="3" style="padding: 10px 0"><hr size="3" color="#ccc"/></td></tr>';
            }
            echo '</table>';
    }
?>
</div>