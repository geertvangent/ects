(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingDetailsController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
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