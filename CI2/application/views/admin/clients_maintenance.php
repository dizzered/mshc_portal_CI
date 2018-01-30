<div id="dialog-add-name-client">
	<div class="dialog-popup-container">
		<div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add Client</h2>
            <form id="client-name-maintenance" style="background-color:#f7f7f7;border:1px solid #e8e8e8;padding:10px;">
                <fieldset>
                <input type="hidden" id="view_id" name="view_id" value="" />
                <label for="name" style="float:left;">Name:</label>
                <input type="text" name="name" id="name" value="Client Name" style="float:left;" />&nbsp;&nbsp;
                <img id="clientname-ajax-loader" src="/images/ajax_loader.gif" width="16" height="16" style="display:none;" />
                <span id="clientname-msg" style="float:left;display:none; margin-left:10px; margin-top:4px;"></span>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php require_once('dialog_practice.php'); ?>

<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td>
        <div id="btn_export_to_excel" name="btn_export_to_excel" class="ico-excel-15" style="float:left">Export to Excel</div><div id="btn_export_to_word" name="btn_export_to_word" class="ico-word-15" style="float:left">Export to Word</div>
        </td>
        <td align="right">
        	<div id="btn-add-new-client" class="tab-right-beige"><p>Add New Client</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>

<div class="main-container">
<script>
tableID = 'Client';
tableName = 'client';
dbName = 'clients';
</script>
<div id="client-table-container"></div>
</div>
