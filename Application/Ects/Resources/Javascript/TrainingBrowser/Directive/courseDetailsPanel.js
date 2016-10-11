(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.directive('courseDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'CourseDetailsController',
            controllerAs : 'courseDetails'
        }
    });
})();
