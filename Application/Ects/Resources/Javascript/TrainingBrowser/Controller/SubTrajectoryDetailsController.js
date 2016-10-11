(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('SubTrajectoryDetailsController', [ 'coursesService', '$scope', '$route',
            '$routeParams', function(coursesService, $scope, $route, $routeParams)
            {
                if ($routeParams.subTrajectoryId)
                {
                    coursesService.retrieveSubTrajectoryById($routeParams.subTrajectoryId);
                }
                
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