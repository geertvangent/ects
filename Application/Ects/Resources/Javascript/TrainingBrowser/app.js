(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp', [ 'ngRoute', 'chamilo' ]);
    
    trainingBrowserApp.config(function($routeProvider)
    {
        $routeProvider.when('/', {
            templateUrl : 'trainingSelectorPanel.html'
        }).when('/training/:trainingId', {
            templateUrl : 'trainingDetailsPanel.html'
        }).when('/trajectory/:subTrajectoryId', {
            templateUrl : 'subTrajectoryDetailsPanel.html'
        });
    });
    
    trainingBrowserApp.run(function()
    {
    });
    
})();