<div class="main-container-header">
    <table border="0" class="main-container-header-tab">
        <tr>
            <td align="left" valign="middle"><a id="btn_activity_log_search" name="btn_activity_log_search"
                                                class="alpha-link orange-button-text-white">Search</a> <a
                    id="btn_activity_log_search_clear" name="btn_activity_log_search_clear"
                    class="alpha-link orange-button-text-white">Clear</a>
			
			<span style="margin-left: 30px">
            <strong>Users:</strong> <select id="activity_log_search_users" name="activity_log_search_users">
                    <option value=""> --- Not Selected ---</option>
                    <?php
                    if (isset($users_list) && is_array($users_list)) {
                        for ($i = 0; $i < count($users_list); $i++) {
                            $user_name = $users_list[$i]['last_name'];
                            if ($user_name != '' && $users_list[$i]['first_name'] != '') $user_name .= ', ';
                            $user_name .= $users_list[$i]['first_name'];
                            if ($user_name == '') {
                                $user_name = $users_list[$i]['username'];
                            }
                            echo '<option value="' . $users_list[$i]['id'] . '">' . $user_name . '</option>';
                        }
                    }
                    ?>
                </select></span>
			
			<span style="margin-left: 30px"><strong>Events:</strong> <select id="activity_log_search_events"
                                                                             name="activity_log_search_events">
                    <option value=""> --- All Events ---</option>
                    <?php
                    if (isset($events_list) && is_array($events_list)) {
                        for ($i = 0; $i < count($events_list); $i++)
                            echo '<option value="' . $events_list[$i]['name'] . '">' . $events_list[$i]['name'] . '</option>';
                    }
                    ?>
                </select></span>

                <span id="btn_export_to_excel" name="btn_export_to_excel" class="ico-excel-15">Export to Excel</span>
            </td>
            <td align="right">
                <div id="btn-advanced-activity-log-search" class="tab-right-beige"><p>Advanced Search</p></div>
                <div class="tab-left-beige"></div>
                <div style="clear:both"></div>
            </td>
        </tr>
    </table>
</div>
<script>
    tableID = '';
    tableName = 'activity-log';
    dbName = 'activities';
</script>
<div class="main-container">
    <div id="activity-log-table-container"></div>
</div>
