(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('MainController', [ 'trainingsService', 'coursesService', '$scope',
            function(trainingsService, coursesService, $scope)
            {
                this.selectedTraining = trainingsService.selectedTraining;
                
                $scope.$watch(function()
                {
                    return trainingsService.selectedTraining;
                }, angular.bind(this, function(newValue)
                {
                    this.selectedTraining = newValue;
                }));
                
                this.selectTraining = function(training)
                {
                    trainingsService.retrieveTraining(training);
                };
                
                this.resetSelectedTraining = function()
                {
                    trainingsService.changeSelectedTraining(null);
                };
                
                this.selectedSubTrajectory = coursesService.selectedSubTrajectory;
                
                $scope.$watch(function()
                {
                    return coursesService.selectedSubTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.selectedSubTrajectory = newValue;
                }));
                
                this.selectSubTrajectory = function(subTrajectory)
                {
                    coursesService.retrieveSubTrajectory(subTrajectory);
                };
                
                this.resetSelectedSubTrajectory = function()
                {
                    coursesService.changeSelectedSubTrajectory(null);
                };
                
            } ]);
    
})();