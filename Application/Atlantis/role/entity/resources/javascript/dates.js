(function($) {
	$(document)
			.ready(
					function() {
						// var creationDates = $("#start_date,#end_date")
						// .datetimepicker(
						// {
						// timeFormat : 'hh:mm',
						// dateFormat : 'dd-mm-yy',
						// separator : ' ',
						// onSelect : function(selectedDate) {
						// var option = (this.id == "start_date") ? "minDate"
						// : "maxDate", instance = $(this)
						// .data("datetimepicker"), date = $.datetimepicker
						// .parseDate(
						// instance.settings.dateFormat
						// || $.datetimepicker._defaults.dateFormat,
						// selectedDate,
						// instance.settings);
						// creationDates.not(this)
						// .datetimepicker("option",
						// option, date);
						// }
						// });

						$('#start_date')
								.datetimepicker(
										{
											timeFormat : 'hh:mm',
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
														.datetimepicker(
																'getDate');
												var instance = $(this).data(
														"datepicker");
												var date = $.datepicker
														.parseDate(
																instance.settings.dateFormat
																		|| $.datepicker._defaults.dateFormat,
																selectedDateTime,
																instance.settings);
												$('#end_date').datetimepicker(
														"option", "minDate",
														date);
											}
										});
						$('#end_date')
								.datetimepicker(
										{
											timeFormat : 'hh:mm',
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
														.datetimepicker(
																'getDate');
												var instance = $(this).data(
														"datepicker");
												var date = $.datepicker
														.parseDate(
																instance.settings.dateFormat
																		|| $.datetimepicker._defaults.dateFormat,
																selectedDateTime,
																instance.settings);
												$('#start_date')
														.datetimepicker(
																"option",
																"maxDate", date);
											}
										});

					})
})(jQuery);
