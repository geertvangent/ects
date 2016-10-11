(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingDetailsController', [ 'trainingsService', '$scope', '$route',
            '$routeParams', function(trainingsService, $scope, $route, $routeParams)
            {
                if ($routeParams.trainingId)
                {
                    trainingsService.retrieveTrainingById($routeParams.trainingId);
                }
                
                this.training = trainingsService.training;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.training;
                }, angular.bind(this, function(newValue)
                {
                    this.training = newValue;
                }));
                
            } ]);
})();