(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.directive('trainingDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingDetailsController',
            controllerAs : 'trainingDetails'
        }
    });
})();
