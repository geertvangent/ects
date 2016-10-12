(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('CourseDetailsController', [
            'chamiloUtilities',
            'coursesService',
            '$scope',
            '$http',
            '$route',
            '$routeParams',
            '$sce',
            function(chamiloUtilities, coursesService, $scope, $http, $route, $routeParams, $sce)
            {
                this.courseId = $routeParams.courseId;
                this.course = coursesService.course;
                this.isLoadingCourse = coursesService.isLoadingCourse;
                
                if (this.courseId)
                {
                    coursesService.retrieveCourseById(this.courseId);
                }
                
                $scope.$watchCollection(function()
                {
                    return coursesService.course;
                }, angular.bind(this, function(newValue)
                {
                    this.course = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingCourse;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingCourse = newValue;
                }));
                
                this.getCourseUrl = function()
                {
                    return $sce.trustAsResourceUrl('https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a='
                            + this.courseId + '&b=5&c=1');
                };
                
                this.getCourseDetails = function()
                {
                    if (this.course)
                    {
                        return $sce.trustAsHtml(this.course.content);
                    }
                    
                    return '';
                }

            } ]);
})();