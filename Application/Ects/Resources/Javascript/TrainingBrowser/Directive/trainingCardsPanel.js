(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.directive('trainingCardsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingCardsController',
            controllerAs : 'trainingCards'
        }
    });
})();
