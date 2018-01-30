<script language="javascript">
	var calculate_account_id = -1;
	var count_records_on_page = 10;
	var number_mileage_report_page = 1;
	var filter_drop_down = 'div_filter_dropdown_mileage_report';
	var sorting_field = 'last_name ASC';
	var sFieldName = '';
	var sValue = '';
	var cases_account = new Array();
</script>

<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2">

        </div>

        <div id="case-summary-block">

            <div class="summary" id="div_mileage_report_detail">
            
            </div>
        </div>
   </div>
</div>


<div id="bottomUp" style="display: none">
	
	<div class="dialog-popup-container">
    	<div class="dialog-popup-content" id='dialog-mileage-text'>

	<!--<div class="close" id="close_calculate_distance">close</div>-->

	<h2 id="dialog-popup-content-title">Calculate Distance for <em>Xiomara Zalaya</em></h2>

    <div class="calculateBlock" style="line-height: 24px; vertical-align:middle;">

    	<input type="button" style="float: right; margin-bottom: 10px" value="Calculate" id="btn_calculate_wish_address">Choose clients address you with to use:<br />

        <input type="radio" id="calculate-home" name="clients_address_wish" value="home" style="margin-top: 4px" >

        <label for="calculate-home" style="margin-right: 30px;">Home</label>

        <input type="radio" id="calculate-work" name="clients_address_wish" value="work" style="margin-top: 4px">

        <label for="calculate-work" style="margin-right: 30px">Work</label>
        
        <input type="radio" id="calculate-address" name="clients_address_wish" value="address" style="margin-top: 4px">

        <label for="calculate-address">Address</label>
        
        <input id="custom_address" name="custom_address" style="display:none; background-color: white" value="" type="text"/>

    </div>

    <div class="tableHeader" style="display:none;height: 32px"></div>

    <div class="tableBody" id="div_calculate_distance">
    </div>
	
	</div>
	</div>
</div>

<script language="javascript">
$(function(){
	load_mileage_report();
});
</script>