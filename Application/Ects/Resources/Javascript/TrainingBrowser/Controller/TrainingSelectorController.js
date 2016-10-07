(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingSelectorController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.academicYears = trainingsService.academicYears;
                this.academicYear = trainingsService.academicYear;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.academicYear;
                }, angular.bind(this, function(newValue)
                {
                    this.academicYear = newValue;
                }));
                
                this.selectAcademicYear = function()
                {
                    trainingsService.changeAcademicYear(this.academicYear);
                };
                
            } ]);
})();