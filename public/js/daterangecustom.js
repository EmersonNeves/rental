'use strict';

function customDaterangeFormat () {
	var sessionDateFinal = sessionDate.toUpperCase();
	var sepSign = "-";

	var dateFormat = 'YYYY MM DD';
	var showDateFormat = 'YYYY MM DD';

	if (sessionDateFinal.includes("/")){
		sepSign = '/';
	} else if (sessionDateFinal.includes(".")) {
		sepSign = '.';
	} else {
		sepSign = '-';
	}

	var dateSep = dateFormat.replace(/ /g,sepSign);

	switch(sessionDateFinal) {
		case 'YYYY' + sepSign + 'MM' + sepSign + 'DD':
				showDateFormat = 'YYYY MM DD';
				dateSep = showDateFormat.replace(/ /g,sepSign);
			break;
		case 'DD' + sepSign + 'MM' + sepSign + 'YYYY':
				showDateFormat = 'DD MM YYYY';
				dateSep = showDateFormat.replace(/ /g,sepSign);
			break;

		case 'MM' + sepSign + 'DD' + sepSign+ 'YYYY':
				showDateFormat = 'MM DD YYYY';
				dateSep = showDateFormat.replace(/ /g,sepSign);
			break;
		case 'DD' + sepSign + 'M' + sepSign + 'YYYY':
				showDateFormat = 'DD MMM YYYY';
				dateSep = showDateFormat.replace(/ /g,sepSign);
			break;

		case 'YYYY' + sepSign + 'M' + sepSign + 'DD':
				showDateFormat = 'YYYY MMM DD';
				dateSep = showDateFormat.replace(/ /g,sepSign);
			break;
		default:

	}

	return {
		dateFormat: dateFormat,
		showDateFormat: showDateFormat,
		sepSign:sepSign,
		dateSep:dateSep,
	};
}

function dateRangeBtn (startDate, endDate,dt=null) {
	var df = dt;
	var customFormat =	customDaterangeFormat();
	if(startDate == undefined || !startDate){
		var startDate = moment();
		startDate = moment(startDate, customFormat.showDateFormat);
		var endDate   = moment();
		endDate = moment(endDate, customFormat.showDateFormat);
	} else {
		startDate = moment(startDate, customFormat.showDateFormat);
		endDate = moment(endDate, customFormat.showDateFormat);
	}

	var init = moment();
	var initdate;
	if(dt == 1) {
		init = moment(0);
		initdate =  moment(init, customFormat.dateFormat);
		var today = moment();
		today =  moment(today, customFormat.dateFormat);

		$('#daterange-btn').daterangepicker({
			ranges   : {
							'Anytime'	  : [moment(0),moment()],
							'Today'       : [moment(), moment()],
							'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
							'Last 30 Days': [moment().subtract(29, 'days'), moment()],
							'This Month'  : [moment().startOf('month'), moment().endOf('month')],
							'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
						},
				"autoApply": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate").val(endDate);
				initdate = moment(initdate, customFormat.showDateFormat).format(customFormat.dateSep);
				today = moment(today, customFormat.showDateFormat).format(customFormat.dateSep);
				if (startDate == 'undefined' || endDate == 'undefined') {
					$('#daterange-btn span').html('Pick a date range');
				} else if (startDate == '' || endDate == '' || (startDate === initdate && endDate === today )) {
					$('#daterange-btn span').html('Anytime');

				} else {
						startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
						endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
						$("#startDate").val(startDate);
						$("#endDate").val(endDate);
						$('#daterange-btn span').text(startDate + '-' + endDate );
				}
			});




		$('#daterange-btn1').daterangepicker({
			ranges   : {
							'Anytime'	  : [moment(0),moment()],
							'Today'       : [moment(), moment()],
							'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
							'Last 30 Days': [moment().subtract(29, 'days'), moment()],
							'This Month'  : [moment().startOf('month'), moment().endOf('month')],
							'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
						},
				"autoApply": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate").val(endDate);
				initdate = moment(initdate, customFormat.showDateFormat).format(customFormat.dateSep);
				today = moment(today, customFormat.showDateFormat).format(customFormat.dateSep);
				if (startDate == 'undefined' || endDate == 'undefined') {
					$('#daterange-btn1 span').html('Pick a date range');
				} else if (startDate == '' || endDate == '' || (startDate === initdate && endDate === today )) {
					$('#daterange-btn1 span').html('Anytime');

				} else {
						startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
						endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
						$("#startDate").val(startDate);
						$("#endDate").val(endDate);
						$('#daterange-btn1 span').text(startDate + '-' + endDate );
				}
			});



	} else {
		initdate =  moment(init, customFormat.dateFormat);
		$('#daterange-btn').daterangepicker({
				"autoApply": true,
				"alwaysShowCalendars": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);

				$("#startDate").val(startDate);
				$("#endDate").val(endDate);

				var date = new Date(start);
				var tomorrow = new Date(date.getTime() + 24 * 60 * 60 * 1000);



				var endDate1          = moment(tomorrow, customFormat.showDateFormat).format(customFormat.dateSep);

					if(startDate=='' && endDate==''){
						$('#daterange-btn span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; Pick a date range');
					} else {

							startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
							endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);


							if(startDate==endDate)
							{
								$("#startDate").val(startDate);
								$("#endDate").val(endDate1);
							}
							else
							{
								$("#startDate").val(startDate);
								$("#endDate").val(endDate);
							}


							// $('#daterange-btn span').text(startDate + '-' + endDate );
							if(df == 'single') {
								price_calculation('', '', '');
							}
					}
			});



				$('#daterange-btn1').daterangepicker({
				"autoApply": true,
				"alwaysShowCalendars": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate").val(endDate);
					if(startDate=='' && endDate==''){
						$('#daterange-btn1 span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; Pick a date range');
					} else {

							startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
							endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
							$("#startDate").val(startDate);
							$("#endDate").val(endDate);
							// $('#daterange-btn1 span').text(startDate + '-' + endDate );
							if(df == 'single') {
								price_calculation('', '', '');
							}
					}
			});
	}
}


function dateRangeBtn1 (startDate, endDate,dt=null) {
	var df = dt;
	var customFormat =	customDaterangeFormat();
	if(startDate == undefined || !startDate){
		var startDate = moment();
		startDate = moment(startDate, customFormat.showDateFormat);
		var endDate   = moment();
		endDate = moment(endDate, customFormat.showDateFormat);
	} else {
		startDate = moment(startDate, customFormat.showDateFormat);
		endDate = moment(endDate, customFormat.showDateFormat);
	}

	var init = moment();
	var initdate;
	if(dt == 1) {

		init = moment(0);
		initdate =  moment(init, customFormat.dateFormat);
		var today = moment();
		today =  moment(today, customFormat.dateFormat);

		$('#daterange-btn2').daterangepicker({
			ranges   : {
							'Anytime'	  : [moment(0),moment()],
							'Today'       : [moment(), moment()],
							'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
							'Last 30 Days': [moment().subtract(29, 'days'), moment()],
							'This Month'  : [moment().startOf('month'), moment().endOf('month')],
							'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
						},
				"autoApply": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate1").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate1").val(endDate);
				initdate = moment(initdate, customFormat.showDateFormat).format(customFormat.dateSep);
				today = moment(today, customFormat.showDateFormat).format(customFormat.dateSep);
				if (startDate == 'undefined' || endDate == 'undefined') {
					$('#daterange-btn2 span').html('Pick a date range');
				} else if (startDate == '' || endDate == '' || (startDate === initdate && endDate === today )) {
					$('#daterange-btn2 span').html('Anytime');

				} else {
						startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
						endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
						$("#startDate1").val(startDate);
						$("#endDate1").val(endDate);
						$('#daterange-btn2 span').text(startDate + '-' + endDate );
				}
			});

	} else {
		initdate =  moment(init, customFormat.dateFormat);
		$('#daterange-btn').daterangepicker({
				"autoApply": true,
				"alwaysShowCalendars": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);

				$("#startDate").val(startDate);
				$("#endDate").val(endDate);

				var date = new Date(start);
				var tomorrow = new Date(date.getTime() + 24 * 60 * 60 * 1000);



				var endDate1          = moment(tomorrow, customFormat.showDateFormat).format(customFormat.dateSep);

					if(startDate=='' && endDate==''){
						$('#daterange-btn span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; Pick a date range');
					} else {

							startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
							endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);


							if(startDate==endDate)
							{
								$("#startDate").val(startDate);
								$("#endDate").val(endDate1);
							}
							else
							{
								$("#startDate").val(startDate);
								$("#endDate").val(endDate);
							}


							// $('#daterange-btn span').text(startDate + '-' + endDate );
							if(df == 'single') {
								price_calculation('', '', '');
							}
					}
			});



				$('#daterange-btn1').daterangepicker({
				"autoApply": true,
				"alwaysShowCalendars": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": initdate,
				"drops": "auto",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate").val(endDate);
					if(startDate=='' && endDate==''){
						$('#daterange-btn1 span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; Pick a date range');
					} else {

							startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
							endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
							$("#startDate").val(startDate);
							$("#endDate").val(endDate);
							// $('#daterange-btn1 span').text(startDate + '-' + endDate );
							if(df == 'single') {
								price_calculation('', '', '');
							}
					}
			});



	}
}


function formDate (startDate, endDate) {
	var customFormat =	customDaterangeFormat();
	var init = moment(0);
	var initdate;
	initdate =  moment(init, customFormat.showDateFormat).format(customFormat.dateSep);
	var today = moment();
	today =  moment(today, customFormat.showDateFormat).format(customFormat.dateSep);



	if(startDate == undefined || !startDate){
		var startDate = moment();
		startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
		var endDate   = moment();
		endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
	} else {
		startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
		endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
	}

	if (startDate == 'undefined' || endDate == 'undefined') {
		$('#daterange-btn span').html('Pick a date range');
	} else if (startDate == '' || endDate == '' || (startDate === initdate && endDate === today )) {
		$('#daterange-btn span').html('Anytime');

	} else {
			startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
			endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
			$("#startDate").val(startDate);
			$("#endDate").val(endDate);
			$('#daterange-btn span').text(startDate + '-' + endDate );
			$('#daterange-btn2 span').text(startDate + '-' + endDate );

	}
}

/* home page search */

function homedateRangeBtn (startDate, endDate,dt=null) {
	var df = dt;
	var customFormat =	customDaterangeFormat();
	if(startDate == undefined || !startDate){
		var startDate = moment();
		startDate = moment(startDate, customFormat.showDateFormat);
		var endDate   = moment();
		endDate = moment(endDate, customFormat.showDateFormat);
	} else {
		startDate = moment(startDate, customFormat.showDateFormat);
		endDate = moment(endDate, customFormat.showDateFormat);
	}

	var init = moment();
	var initdate;

		init = moment(0);
		initdate =  moment(init, customFormat.dateFormat);
		var today = moment();
		today =  moment(today, customFormat.dateFormat);

		$('#daterange-btn5').daterangepicker({

				"autoApply": true,
				"startDate": startDate,
				"endDate": endDate,
				"minDate": today,
				"drops": "down",
        "locale": CUSTOM_DATEPICKER_LOCALE, //CUSTOM
			}, function(start, end) {

				var startDate        = moment(start, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#startDate1").val(startDate);
				var endDate          = moment(end, customFormat.showDateFormat).format(customFormat.dateSep);
				$("#endDate1").val(endDate);
				initdate = moment(initdate, customFormat.showDateFormat).format(customFormat.dateSep);
				today = moment(today, customFormat.showDateFormat).format(customFormat.dateSep);
				if (startDate == 'undefined' || endDate == 'undefined') {
					$('#daterange-btn5 span').html('Pick a date range');
				} else if (startDate == '' || endDate == '' || (startDate === initdate && endDate === today )) {
					$('#daterange-btn5 span').html('Anytime');

				} else {
						startDate = moment(startDate, customFormat.showDateFormat).format(customFormat.dateSep);
						endDate = moment(endDate, customFormat.showDateFormat).format(customFormat.dateSep);
						$("#startDate_home").val(startDate);
						$("#endDate_home").val(endDate);
						$('#daterange-btn5 span').text(startDate + '-' + endDate );
				}
			});


}
