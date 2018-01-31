// JavaScript Document
$(function() {
	display_text_message('',1,1);
	close_text_message();
	
	var selectedLocation = 0;
	if (patientLocation.length && locationsSource.length)
	{
		for (var i = 0; i < locationsSource.length; ++i)
		{
			if (patientLocation.toLowerCase() == locationsSource[i].toLowerCase())
				selectedLocation = i;
		}
	}
	$("#new_case_register_location").jqxDropDownList({
		source: locationsSource, 
		selectedIndex: selectedLocation,
		width: '434px', 
		height: '23px'
	});
	
	var selectedAccident = 0;
	if (patientAccident.length && accidentsTypeSource.length)
	{
		for (var i = 0; i < accidentsTypeSource.length; ++i)
		{
			if (patientAccident.toLowerCase() == accidentsTypeSource[i].toLowerCase())
				selectedAccident = i;
		}
	}
	$("#new_case_register_accidents").jqxDropDownList({
		source: accidentsTypeSource, 
		selectedIndex: selectedAccident,
		width: '434px', 
		height: '23px'
	});
	
	var selectedAttorney = 0;
	if (patientAttorney.length && attorneysSource.length)
	{
		for (var i = 0; i < attorneysSource.length; ++i)
		{
			if (patientAttorney.toLowerCase() == attorneysSource[i].toLowerCase())
				selectedAttorney = i;
		}
	}
	$("#new_case_register_attorneys").jqxDropDownList({
		source: attorneysSource, 
		selectedIndex: selectedAttorney,
		width: '434px', 
		height: '23px'
	});
	
	$('input[name="locations"]').on('click', function() {
		if ($(this).val() == 'near_locations')
		{
			if ($('#location_zip').val().length == 0)
			{
				$('#location_zip').val($('#zip').val());
			}
			$("#dialog-near-locations").slideDown();
		}
		else
		{
			$("#dialog-near-locations").slideUp();
			if (initialLocations != locationsSource.length)
			{
				getAllLocations();
			}
		}
	});
	
	$('#location_zip').jqxInput({
		width: '50px', 
		height: '21px'
	});
	
	$("#location_distance").jqxNumberInput({
		spinButtons: true,
		width: '70px', 
		height: '21px',
		inputMode: 'simple',
		decimalDigits: 0,
		min: 1
	}).val(10);
	
	$('#btn_get_near_locations').jqxButton({
		width: '50px', 
		height: '21px',
		theme: 'classic'
	});
	
	$('#btn_get_near_locations').on('click', function() {
		getNearLocations();
	});
});

function getNearLocations() {
	var zipCode = $('#location_zip').val(),
		distanceLimit = $('#location_distance').val(),
		error = false;
		
	if (zipCode.length < 5)
	{
		$('#location_zip').addClass('input_error');
		return;
	}
	
	display_please_wait();
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_near_locations',
		dataType: 'html',
		data: {
				zipCode: zipCode,
				radius: distanceLimit
		},
		success: function(data) {
			locationsSource = $.parseJSON(data);
			$('#locationsListCount').html((locationsSource.length - 1) + ' locations');
			selectedLocation = 0;
			$("#new_case_register_location").jqxDropDownList({
				source: locationsSource, 
				selectedIndex: selectedLocation,
				width: '434px', 
				height: '23px'
			});
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			display_text_message('Error', 400, 200);
		}
	});	
}

function getAllLocations() {
	display_please_wait();
	$.ajax({
		type: 'POST',
		async: true,
		url: baseURL + ajaxCONTROLLER + '/get_all_locations',
		dataType: 'html',
		success: function(data) {
			locationsSource = $.parseJSON(data);
			$('#locationsListCount').html((locationsSource.length - 1) + ' locations');
			selectedLocation = 0;
			$("#new_case_register_location").jqxDropDownList({
				source: locationsSource, 
				selectedIndex: selectedLocation,
				width: '434px', 
				height: '23px'
			});
		},
		complete: function() {
			close_please_wait();
		},
		error: function(data){
			// display Error message
			display_text_message('Error', 400, 200);
		}
	});	
}