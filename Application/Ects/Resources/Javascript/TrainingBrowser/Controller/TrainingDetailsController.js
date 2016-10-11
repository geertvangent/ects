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
                
                this.selectedTraining = trainingsService.selectedTraining;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.selectedTraining;
                }, angular.bind(this, function(newValue)
                {
                    this.selectedTraining = newValue;
                }));
                
            } ]);
})();