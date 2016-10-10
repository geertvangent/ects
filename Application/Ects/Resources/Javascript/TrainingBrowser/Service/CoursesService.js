(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('coursesService', [ 'chamiloUtilities', function(chamiloUtilities)
    {
        this.selectedSubTrajectory = null;
        
        this.changeSubTrajectory = function(subTrajectory)
        {
            this.selectedSubTrajectory = subTrajectory;
        };
        
        this.retrieveSubTrajectory = function(subTrajectory)
        {
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Trajectory', {
                'sub_trajectory' : subTrajectory.id
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.changeSubTrajectory(result.properties);
            }));
        };
    } ]);
})();