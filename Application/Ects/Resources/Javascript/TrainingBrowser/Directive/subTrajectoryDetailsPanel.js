(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.directive('subTrajectoryDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'SubTrajectoryDetailsController',
            controllerAs : 'subTrajectoryDetails'
        }
    });
})();
