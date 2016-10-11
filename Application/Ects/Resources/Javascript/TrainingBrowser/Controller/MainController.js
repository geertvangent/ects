(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('MainController', [ '$scope', '$location', function($scope, $location)
    {
        this.goToPath = function(path)
        {
            $location.path(path);
        }

    } ]);
    
})();