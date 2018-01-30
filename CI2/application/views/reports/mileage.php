<script language="javascript">
	var cases_account_id = -1;
</script>
<div id="dialog-calculate-distance" style="margin-bottom: 20px">
	<div class="dialog-popup-container">
        <div class="dialog-popup-content">
        	<h2 id="dialog-popup-content-title" style="float:left; margin-top: 0px">Calculate Distance for <i id="name_for"></i></h2>
            <div id="result_export_to_file" style="display:none; float:right" align="right;"><span id="btn_export_to_excel" name="btn_export_to_excel" class="ico-excel-15" >Export to Excel</span><span id="btn_export_to_word" name="btn_export_to_word" class="ico-word-15">Export to Word</span></div>
			<div style="height: 10px"></div>
            <div class="tabs-container2" style="float:left; width: 97%">
            	<div class="calculate-general" width="90%">
                     <form id="calculate-distance">
                        <fieldset>
                            <div style="float: left;">Choose clients address you wish to use: 
                              <input name="clients_address_wish" type="radio" value="home" /> Home 
                              <input name="clients_address_wish" type="radio" value="work" /> Work
                              <input name="clients_address_wish" type="radio" value="address" /> Custom address
                              &nbsp;<input id="custom_address" name="custom_address" style="width: 200px; display:none" value="" placeholder="6 Dumont Place, Morristown, NJ" type="text"/>
                            </div>
                            <div align="right"><input id="btn_calculate_wish_address" type="button" class="ui-button opacity25" disabled="disabled" style="color: white" value="Calculate"/></div>
                        </fieldset>
                     </form>
                 </div>
            </div>
            <div style="clear:both; height: 30px"></div>
           	<div id="distance-table-container" style="width: 99%"></div>
        </div>
    </div>
</div>

<div class="main-container-header">
</div>

<div class="main-container">
<script>
tableID = 'Mileage';
tableName = 'mileage';
dbName = 'mileage';
</script>
<div id="mileage-table-container"></div>
</div>