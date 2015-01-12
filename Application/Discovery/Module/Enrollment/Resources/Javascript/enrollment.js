(function($) {

    function showCourses(e, ui) {
	e.preventDefault();
	var id = $(this).attr('id');
	
	$("div.enrollment_courses").hide();
	$("div.enrollment_courses#" + id + "_courses").show();
    }

    $(document).ready(function() {
	$('a.enrollment').live('click', showCourses);
    });

})(jQuery);