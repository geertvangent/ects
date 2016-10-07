(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingSelectorController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                // Academic year
                this.academicYears = trainingsService.academicYears;
                this.academicYear = trainingsService.academicYear;
                
                $scope.$watch(function()
                {
                    return trainingsService.academicYears;
                }, angular.bind(this, function(newValue)
                {
                    this.academicYears = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return trainingsService.academicYear;
                }, angular.bind(this, function(newValue)
                {
                    this.academicYear = newValue;
                }));
                
                this.selectAcademicYear = function(academicYear)
                {
                    trainingsService.changeAcademicYear(academicYear);
                };
                
                // Faculty
                this.faculties = trainingsService.faculties;
                this.faculty = trainingsService.faculty;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.faculties;
                }, angular.bind(this, function(newValue)
                {
                    this.faculties = newValue;
                }));
                
                this.selectFaculty = function()
                {
                    trainingsService.changeFaculty(this.faculty);
                };
                
                // Training type
                this.trainingTypes = trainingsService.trainingTypes;
                this.trainingType = trainingsService.trainingType;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingTypes;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingTypes = newValue;
                }));
                
                this.selectTrainingType = function()
                {
                    trainingsService.changeTrainingType(this.trainingType);
                };
                
            } ]);
})();