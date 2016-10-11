(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('coursesService', [ 'chamiloUtilities', function(chamiloUtilities)
    {
        this.subTrajectory = null;
        
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
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Trajectory', {
                'sub_trajectory' : subTrajectoryId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.changeSubTrajectory(result.properties);
            }));
        };
    } ]);
})();