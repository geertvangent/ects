(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingDetailsController', [ 'trainingsService', '$scope', '$route',
            '$routeParams', function(trainingsService, $scope, $route, $routeParams)
            {
                this.training = trainingsService.training;
                this.isLoadingTraining = trainingsService.isLoadingTraining;
                
                if ($routeParams.trainingId)
                {
                    trainingsService.retrieveTrainingById($routeParams.trainingId);
                }
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.training;
                }, angular.bind(this, function(newValue)
                {
                    this.training = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return trainingsService.isLoadingTraining;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingTraining = newValue;
                }));
                
            } ]);
})();