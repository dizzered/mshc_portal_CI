<div id="dialog-add-new-form" title="">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title">Add New Forms</h2>
            <div class="tabs-container" style="height:227px;">
                <form id="form-maintenance" method="post" action="<?php echo base_url().MSHC_ADMIN_CONTROLLER_NAME.'/save_form'; ?>" enctype="multipart/form-data">
                    <fieldset>
                        <input type="hidden" id="form_view_id" name="form_view_id" value="0" />
                        <div class="form-general fnTabs">
                            <div style="float:left;width:600px;">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" value="" style="width:310px;" />
                                <div style="clear:both;"></div>
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="name-msg" style="color:red;height:15px;">&nbsp;</div>
                                <div id="file_upload" style="display:block; vertical-align::top">
                                	<label for="file_name">File Name:</label>
									<span class="file-wrapper" style="margin-bottom:0;">
                                    <input type="file" name="file_name" id="file_name" style="width:411px;" />
                                    <input type="text" class="file-holder" value="" style="width:310px;" />
                                    <span class="input-button-grey file-button">Choose File</span></span>
                                    <div style="clear:both;"></div>
                                    <div style="float:left;width:80px;">&nbsp;</div>
                                    <div id="file_name-msg" style="color:red;height:15px;">&nbsp;</div>
                                </div>
                                <div id="file_delete" style="display: none; margin-bottom: 15px">
                                    <span id="file_name_uploaded"></span>
									<input type="hidden" name="file_name_uploads" id="file_name_uploads" />
                                    <span class="input-button-grey" id="btn_forms_delete_file" style="margin-bottom: 0;">Delete File</span>
                                </div>
                                <div style="clear:both;"></div>
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" cols="50" rows="5" style="width:308px;height:90px;"></textarea><br /><br />
                                <label for="weight">Order By:</label>
                                <input type="text" name="weight" id="weight" value="" style="width:310px;" />
                                <div style="clear:both;"></div>
                                <div style="float:left;width:80px;">&nbsp;</div>
                                <div id="weight-msg" style="color:red;height:15px;">&nbsp;</div>
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
        	<div id="btn-add-new-form" class="tab-right-beige"><p>Add New Forms</p></div>
            <div class="tab-left-beige"></div>
            <div style="clear:both"></div>
        </td></tr>
    </table>
</div>
<script>
tableID = 'Form';
tableName = 'form';
dbName = 'forms';
</script>
<div class="main-container">
<div id="form-table-container"></div>
</div>

<script>
var VIGET = VIGET || {};
var styleClearFile = "margin: 0";
if (navigator.userAgent.search("MSIE") >= 0 || navigator.userAgent.search("Firefox") >= 0 || navigator.userAgent.search("Opera") >= 0){
	styleClearFile = "position:relative; top: -11px";
}
var btnClearFile = '<span class="input-button-grey file-clear" style="' + styleClearFile + '">Delete File</span>';
VIGET.fileInputs = function() {
	//$('.file-clear').remove();
	var $this = $(this),
				$val = $this.val(),
				valArray = $val.split('\\'),
				newVal = valArray[valArray.length-1],
				$button = $this.siblings('.file-button'),
				$fakeFile = $this.siblings('.file-holder');
	$this.parents('.file-wrapper').next('.file-clear').remove();
	if(newVal !== '') 
	{
		if($fakeFile.length === 0) {
			$button.after('' + newVal + '');
		} else {
			$fakeFile.val(newVal);
			var id = $this.parents('.file-wrapper').metadata().id;
			$this.parents('.file-wrapper').after(btnClearFile);
			$this.parents('.file-wrapper').next('.file-clear');
		}
	}
	else
	{
		$fakeFile.val('');
	}
};

$(function() { 
	$('.file-wrapper input[type="file"]').live('change focus click', VIGET.fileInputs);
	
	$('.file-clear').live('click', function() {
		$('#file_name').replaceWith($('#file_name').val('').clone(true));
		$('#file_name').trigger('change');
		$('#file_name').parents('.file-wrapper').next('.file-clear').remove();
	});	
});
</script>