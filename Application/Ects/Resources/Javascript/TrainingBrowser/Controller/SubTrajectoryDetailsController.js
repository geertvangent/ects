(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('SubTrajectoryDetailsController', [ 'coursesService', '$scope', '$route',
            '$routeParams', function(coursesService, $scope, $route, $routeParams)
            {
                this.subTrajectory = coursesService.subTrajectory;
                this.isLoadingSubTrajectory = coursesService.isLoadingSubTrajectory;
                
                if ($routeParams.subTrajectoryId)
                {
                    coursesService.retrieveSubTrajectoryById($routeParams.subTrajectoryId);
                }
                
                $scope.$watchCollection(function()
                {
                    return coursesService.subTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.subTrajectory = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingSubTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingSubTrajectory = newValue;
                }));
                
            } ]);
})();