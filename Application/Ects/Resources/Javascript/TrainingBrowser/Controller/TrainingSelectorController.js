(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingSelectorController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.academicYears = trainingsService.academicYears;
                this.academicYear = trainingsService.academicYear;
                this.faculties = trainingsService.faculties;
                this.faculty = trainingsService.faculty;
                this.trainingTypes = trainingsService.trainingTypes;
                this.trainingType = trainingsService.trainingType;
                this.filterText = trainingsService.filterText;
                
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
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.faculties;
                }, angular.bind(this, function(newValue)
                {
                    this.faculties = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.faculty;
                }, angular.bind(this, function(newValue)
                {
                    this.faculty = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingTypes;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingTypes = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingType;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingType = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.filterText;
                }, angular.bind(this, function(newValue)
                {
                    this.filterText = newValue;
                }));
                
                this.selectAcademicYear = function(academicYear)
                {
                    trainingsService.changeAcademicYear(academicYear);
                };
                
                this.selectFaculty = function()
                {
                    trainingsService.changeFaculty(this.faculty);
                };
                
                this.selectTrainingType = function()
                {
                    trainingsService.changeTrainingType(this.trainingType);
                };
                
                this.setFilterText = function()
                {
                    trainingsService.changeFilterText(this.filterText);
                };
                
                this.resetFilters = function()
                {
                    trainingsService.setFaculty('');
                    trainingsService.setFilterText('');
                    trainingsService.retrieveTrainings();
                };
                
                trainingsService.retrieveAcademicYears();
            } ]);
})();