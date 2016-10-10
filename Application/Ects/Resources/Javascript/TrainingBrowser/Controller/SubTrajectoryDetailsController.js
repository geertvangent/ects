(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('SubTrajectoryDetailsController', [ 'coursesService', '$scope',
            function(coursesService, $scope)
            {
                this.selectedSubTrajectory = coursesService.selectedSubTrajectory;
                
                $scope.$watchCollection(function()
                {
                    return coursesService.selectedSubTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.selectedSubTrajectory = newValue;
                }));
                
            } ]);
})();