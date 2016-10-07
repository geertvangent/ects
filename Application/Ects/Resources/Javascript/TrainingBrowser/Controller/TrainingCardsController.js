(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingCardsController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.test = 'balbla';
                this.trainingsByType = trainingsService.trainingsByType;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingsByType;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingsByType = newValue;
                }));
                
            } ]);
})();