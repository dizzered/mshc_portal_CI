<script language="javascript">
	var count_records_on_page = 10;
	var number_notifications_page = 1;
	var filter_drop_down = 'div_filter_dropdown_notifications';
	var sField = '';
</script>

<div style="padding: 11px;">
	
	<form id="document_open_form" name="document_open_form" method="post" action="<?php echo base_url().MSHC_CASES_CONTROLLER_NAME.'/documents'; ?>" target="_blank">
	<input type="hidden" value="" name="document_checkbox[]" />
	<input id="documents_account" name="documents_account" type="hidden" value="" />
	<div class="roundbox" id="div_notifications_detail">

    </div>
    </form>
 <script language="javascript">
 	<?php if (isset($new_notifications)) echo "sField = 'nu.read'; "; ?>
	load_notifications_table();
</script>