<?php require_once('dialog_practice.php'); ?>

<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td>
        <span class="ico-header-title" style="float:left; padding-right:50px;">
        <?php echo $client[0]['name']; ?>
        </span>
        <div id="btn_export_to_excel" name="btn_export_to_excel" class="ico-excel-15" style="float:left">Export to Excel</div><div id="btn_export_to_word" name="btn_export_to_word" class="ico-word-15" style="float:left">Export to Word</div>
        </td>
        <td align="right">
        	<div id="btn-add-new-practice" class="tab-right-beige"><p>Add New Practice</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>

<div class="main-container">
<script>
tableID = 'Practice';
tableName = 'practice';
dbName = 'practices';
clientID = <?php echo $client_id; ?>;
</script>
<div id="practice-table-container"></div>
</div>
