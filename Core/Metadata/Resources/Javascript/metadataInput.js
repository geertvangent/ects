(function($) {

    $(document)
            .ready(
                    function() {

                        var ajaxUri = getPath('WEB_PATH') + 'index.php';

                        var parameters = {
                            'application' : 'Ehb\\Core\\Metadata\\Vocabulary\\Ajax',
                            'go' : 'Vocabulary'
                        };

                        $('.metadata-input')
                                .each(
                                        function(index) {
                                            var currentElement = $(this);

                                            currentElement
                                                    .tagsinput({
                                                        typeahead : {
                                                            name : 'tags',
                                                            source : function(
                                                                    query) {

                                                                var options = [];

                                                                parameters.schemaId = currentElement
                                                                        .data('schemaId');
                                                                parameters.schemaInstanceId = currentElement
                                                                        .data('schemaInstanceId');
                                                                parameters.elementId = currentElement
                                                                        .data('elementId');

                                                                var response = $
                                                                        .ajax(
                                                                                {
                                                                                    type : "POST",
                                                                                    url : ajaxUri,
                                                                                    data : parameters,
                                                                                    async : false,
                                                                                    dataType : 'json'
                                                                                })
                                                                        .done(
                                                                                function(
                                                                                        data,
                                                                                        textStatus,
                                                                                        jqXHR) {
                                                                                    options = data;
                                                                                });

                                                                return options;
                                                            }
                                                        }
                                                    });
                                        });
                    });

})(jQuery);
