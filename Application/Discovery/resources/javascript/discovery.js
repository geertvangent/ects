(function($) {
    $(document)
	    .ready(
		    function() {
			var legend = $('div#legend');
			var button = $('<li class="first"><a id="show_legend" href="#" title="Show legend"><img src="http://localhost//c2/common/libraries/resources/images/aqua/action_details.png" alt="Show legend" title="Show legend" class="labeled"/><span>Show legend</span></a></li>');

			legend.hide();

			$("div#extra_item div.toolbar ul.toolbar_horizontal").append(button);

			$("a#show_legend").bind('click', function(event) {
			    legend.toggle();
			    if (legend.css('display') == 'none') {
				$('span', this).html('Show legend');
			    } else {
				$('span', this).html('Hide legend');
			    }
			});
		    });

})(jQuery);