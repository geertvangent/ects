(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp', [ 'ngRoute', 'chamilo' ]);
    
    trainingBrowserApp.config(function($routeProvider)
    {
        $routeProvider

        // route for the home page
        .when('/', {
            template : '<div class="alert alert-success" role="alert">Training Selector</div>',
            controller : 'TrainingSelectorController'
        })

        // route for the about page
        .when('/training', {
            template : '<div class="alert alert-danger" role="alert">Training details</div>',
            controller : 'TrainingDetailsController'
        })

        // route for the contact page
        .when('/trajectory', {
            template : '<div class="alert alert-warning" role="alert">Sub Trajectory</div>',
            controller : 'SubTrajectoryDetailsController'
        });
    });
    
    trainingBrowserApp.run(function()
    {
    });
    
})();