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
                
                this.subTrajectory = coursesService.subTrajectory;
                
                $scope.$watchCollection(function()
                {
                    return coursesService.subTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.subTrajectory = newValue;
                }));
                
            } ]);
})();