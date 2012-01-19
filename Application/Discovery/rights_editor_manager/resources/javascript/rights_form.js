$(function() {
	$(document).ready(function() {
		$('.specific_rights_selector').live('click', function() {
			$('.specific_rights_selector_box').show();
		});
		$('.inherit_rights_selector').live('click', function() {
			$('.specific_rights_selector_box').hide();
		});

		$('.other_option_selected').live('click', function() {
			$('.entity_selector_box').hide();
		});

		$('.entity_option_selected').live('click', function() {
			$('.entity_selector_box').show();
		});

		$('.specific_rights_selector').each(function() {
			if ($(this).attr('checked')) {
				$('.specific_rights_selector_box').show();
			}
		});

		if ($('.entity_option_selected').attr('checked')) {

			$('.entity_selector_box').show();
		} else {
			$('.entity_selector_box').hide();
		}
	});

});