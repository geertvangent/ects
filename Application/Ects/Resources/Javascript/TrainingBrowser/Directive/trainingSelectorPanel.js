(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.directive('trainingSelectorPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingSelectorController',
            controllerAs : 'trainingSelector'
        }
    });
})();
