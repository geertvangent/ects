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
                this.courseDetails = coursesService.courseDetails;
                this.isLoadingCourse = coursesService.isLoadingCourse;
                this.isLoadingCourseDetails = coursesService.isLoadingCourseDetails;
                
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
                
                $scope.$watchCollection(function()
                {
                    return coursesService.courseDetails;
                }, angular.bind(this, function(newValue)
                {
                    this.courseDetails = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingCourse;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingCourse = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingCourseDetails;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingCourseDetails = newValue;
                }));
                
                this.getCourseUrl = function()
                {
                    return $sce.trustAsResourceUrl('https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a='
                            + this.courseId + '&b=5&c=1');
                };
                
                this.getCourseDetails = function()
                {
                    if (this.courseDetails)
                    {
                        return $sce.trustAsHtml(this.courseDetails.content);
                    }
                    
                    return '';
                }

            } ]);
})();