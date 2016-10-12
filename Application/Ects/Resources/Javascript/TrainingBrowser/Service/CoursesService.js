(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('coursesService', [ 'chamiloUtilities', function(chamiloUtilities)
    {
        this.subTrajectory = null;
        this.course = null;
        
        this.isLoadingSubTrajectory = false;
        this.isLoadingCourse = false;
        
        this.changeSubTrajectory = function(subTrajectory)
        {
            this.subTrajectory = subTrajectory;
        };
        
        this.retrieveSubTrajectory = function(subTrajectory)
        {
            this.retrieveSubTrajectoryById(subTrajectory.id);
        };
        
        this.retrieveSubTrajectoryById = function(subTrajectoryId)
        {
            this.isLoadingSubTrajectory = true;
            
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Trajectory', {
                'sub_trajectory' : subTrajectoryId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.changeSubTrajectory(result.properties);
                this.isLoadingSubTrajectory = false;
            }));
        };
        
        this.retrieveCourseById = function(courseId)
        {
            this.isLoadingCourse = true;
            
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Course', {
                'course' : courseId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.course = result.properties;
                this.isLoadingCourse = false;
            }));
        }
    } ]);
})();