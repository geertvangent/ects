(function($) {

    $(document).ready(function() {
        $('ul.syllabus-group-list > li').click(function() {
            var subList = $('ul.syllabus-group-sublist', $(this));

            if (subList.is(":visible")) {
            subList.hide();
            } else {
            subList.show();
            }
        });
    });

})(jQuery);