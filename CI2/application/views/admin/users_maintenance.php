<?php echo $users_dialog; ?>

<div class="main-container-header">
    <table border="0" class="main-container-header-tab">
        <tr>
            <td>
                <div id="btn_export_to_excel" class="ico-excel-15" style="float:left">Export
                    to Excel
                </div>
                <div id="btn_export_to_word" class="ico-word-15" style="float:left">Export to
                    Word
                </div>
            </td>
            <?php if ($this->_user['role_id'] != MSHC_AUTH_BILLER): ?>
                <td align="right">
                    <div id="btn-add-new-user" class="tab-right-beige"><p>Add New User</p></div>
                    <div class="tab-left-beige"></div>
                    <div style="clear:both"></div>
                </td>
            <?php endif; ?>
        </tr>
    </table>
</div>
<script type="text/javascript">
    tableID = 'User';
    tableName = 'user';
    dbName = 'users';
</script>
<div class="main-container">
    <div id="user-table-container"></div>
</div>
