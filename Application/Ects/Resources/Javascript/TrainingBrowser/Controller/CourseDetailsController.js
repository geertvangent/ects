(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('CourseDetailsController', [
            '$scope',
            '$route',
            '$routeParams',
            '$sce',
            function($scope, $route, $routeParams, $sce)
            {
                this.courseId = $routeParams.courseId;
                
                this.getCourseUrl = function()
                {
                    return $sce.trustAsResourceUrl('https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a='
                            + this.courseId + '&b=5&c=1');
                };
                
            } ]);
})();