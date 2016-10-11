(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('coursesService', [ 'chamiloUtilities', function(chamiloUtilities)
    {
        this.selectedSubTrajectory = null;
        
        this.changeSelectedSubTrajectory = function(subTrajectory)
        {
            this.selectedSubTrajectory = subTrajectory;
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
                this.changeSelectedSubTrajectory(result.properties);
            }));
        };
    } ]);
})();