(function($) {
    
    /* attempt to find a text selection */
    function getSelected() {
    	if(window.getSelection) { return window.getSelection(); }
    	else if(document.getSelection) { return document.getSelection(); }
    	else {
    		var selection = document.selection && document.selection.createRange();
    		if(selection.text) { return selection.text; }
    		return false;
    	}
    	return false;
    }
    
    $(document)
	    .ready(
		    function() {
			var legend = $('div#legend');
			var button = $('<li class="first"><a id="show_legend" href="#" title="Show legend"><img src="'+ getPath('WEB_PATH') +'application/discovery/resources/images/'+ getTheme() +'/action_legend.png" alt="Show legend" title="Show legend" class="labeled"/><span>Show legend</span></a></li>');

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
			
		    	$('#main').mouseup(function() {
		    		var selection = getSelected();
		    		if(selection && (selection = new String(selection).replace(/^\s+|\s+$/g,''))) {
		    		    //alert(selection);
// $.ajax({
// type: 'post',
// url: 'ajax-selection-copy.php',
// data: 'selection=' + encodeURI(selection)
// });
		    		}
		    	});
			
		    });


})(jQuery);