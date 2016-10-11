(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingCardsController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.trainingsByType = trainingsService.trainingsByType;
                this.isLoadingTrainingList = trainingsService.isLoadingTrainingList;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingsByType;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingsByType = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return trainingsService.isLoadingTrainingList;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingTrainingList = newValue;
                }));
                
            } ]);
})();