(function($) {
	$(document)
			.ready(
					function() {
						$('#start_date')
								.datepicker(
										{
										//	timeFormat : 'hh:mm',
											dateFormat : 'yy-mm-dd',
											separator : ' ',
											onClose : function(dateText, inst) {
												var endDateTextBox = $('#end_date');
												if (endDateTextBox.val() != '') {
													var testStartDate = new Date(
															dateText);
													var testEndDate = new Date(
															endDateTextBox
																	.val());

													if (testStartDate > testEndDate)
														endDateTextBox
																.val(dateText);
												} else {
													endDateTextBox
															.val(dateText);
												}
											},
											onSelect : function(
													selectedDateTime) {
												var start = $(this)
														.datepicker(
																'getDate');
												var instance = $(this).data(
														"datepicker");
												var date = $.datepicker
														.parseDate(
																instance.settings.dateFormat
																		|| $.datepicker._defaults.dateFormat,
																selectedDateTime,
																instance.settings);
												$('#end_date').datepicker(
														"option", "minDate",
														date);
											}
										});
						$('#end_date')
								.datepicker(
										{
										//	timeFormat : 'hh:mm',
											dateFormat : 'yy-mm-dd',
											separator : ' ',
											onClose : function(dateText, inst) {
												var startDateTextBox = $('#start_date');
												if (startDateTextBox.val() != '') {
													var testStartDate = new Date(
															startDateTextBox
																	.val());
													var testEndDate = new Date(
															dateText);
													if (testStartDate > testEndDate)
														startDateTextBox
																.val(dateText);
												} else {
													startDateTextBox
															.val(dateText);
												}
											},
											onSelect : function(
													selectedDateTime) {
												var end = $(this)
														.datepicker(
																'getDate');
												var instance = $(this).data(
														"datepicker");
												var date = $.datepicker
														.parseDate(
																instance.settings.dateFormat
																		|| $.datepicker._defaults.dateFormat,
																selectedDateTime,
																instance.settings);
												$('#start_date')
														.datepicker(
																"option",
																"maxDate", date);
											}
										});

					})
})(jQuery);
