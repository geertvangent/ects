(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp', [ 'ngRoute', 'chamilo' ]);
    
    trainingBrowserApp.config(function($routeProvider)
    {
        $routeProvider.when('/', {
            templateUrl : 'trainingSelectorPanel.html',
            controller : 'TrainingSelectorController'
        }).when('/training/:trainingId', {
            templateUrl : 'trainingDetailsPanel.html',
            controller : 'TrainingDetailsController'
        }).when('/trajectory/:subTrajectoryId', {
            templateUrl : 'subTrajectoryDetailsPanel.html',
            controller : 'SubTrajectoryDetailsController'
        });
    });
    
    trainingBrowserApp.run(function()
    {
    });
    
})();