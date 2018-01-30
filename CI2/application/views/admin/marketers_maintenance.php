<div id="dialog-add-new-marketer" title="">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add New Marketer</h2>
            <div class="tabs-container">
                <form id="marketer-maintenance">
                    <fieldset>
                        <input type="hidden" id="view_id" name="view_id" value="" />
                        <div class="marketer-general fnTabs">
                            <div style="float:left;width:305px;">
                                 <label for="lastname">Last Name:</label>
                                <input type="text" name="lastname" id="lastname" value="" class="text" />
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="lastname-msg" style="color:red;height:15px;">&nbsp;</div>
                                 <label for="firstname">First Name:</label>
                                <input type="text" name="firstname" id="firstname" value="" class="text" /><br>
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="firstname-msg" style="color:red;height:15px;">&nbsp;</div>
                                <label for="middlename">Middle Name:</label>
                                <input type="text" name="middlename" id="middlename" class="text" /><br>
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="middlename-msg" style="color:red;height:15px;">&nbsp;</div>
                                <label for="phone">Phone:</label>
                                <input type="text" name="phone" id="phone" value="" class="text" />
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="phone-msg" style="color:red;height:15px;">&nbsp;</div>
                                <label for="email">Email:</label>
                                <input type="text" name="email" id="email" value="" class="text" />
                                <img id="email-ajax-loader" src="/images/ajax_loader.gif" width="16" height="16" style="display:none;" />
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="email-msg" style="color:red;height:15px;">&nbsp;</div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td>
        <div id="btn_export_to_excel" name="btn_export_to_excel" class="ico-excel-15" style="float:left">Export to Excel</div><div id="btn_export_to_word" name="btn_export_to_word" class="ico-word-15" style="float:left">Export to Word</div>
        </td>
        <td align="right">
        	<div id="btn-add-new-marketer" class="tab-right-beige"><p>Add New Marketer</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>
<script>
tableID = 'Marketer';
tableName = 'marketer';
dbName = 'marketers';
</script>
<div class="main-container">
<div id="marketer-table-container"></div>
</div>
