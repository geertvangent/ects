(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.controller('TrainingSelectorController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.academicYears = trainingsService.academicYears;
                this.faculties = trainingsService.faculties;
                this.trainingTypes = trainingsService.trainingTypes;
                
                this.filterAcademicYear = trainingsService.filterAcademicYear;
                this.filterFaculty = trainingsService.filterFaculty;
                this.filterTrainingType = trainingsService.filterTrainingType;
                this.filterText = trainingsService.filterText;
                
                // Watch results
                $scope.$watch(function()
                {
                    return trainingsService.academicYears;
                }, angular.bind(this, function(newValue)
                {
                    this.academicYears = newValue;
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
                    return trainingsService.trainingTypes;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingTypes = newValue;
                }));
                
                // Watch filters
                $scope.$watch(function()
                {
                    return trainingsService.filterAcademicYear;
                }, angular.bind(this, function(newValue)
                {
                    this.filterAcademicYear = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.filterFaculty;
                }, angular.bind(this, function(newValue)
                {
                    this.filterFaculty = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.filterTrainingType;
                }, angular.bind(this, function(newValue)
                {
                    this.filterTrainingType = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.filterText;
                }, angular.bind(this, function(newValue)
                {
                    this.filterText = newValue;
                }));
                
                // Filter selections
                this.selectAcademicYear = function()
                {
                    trainingsService.changeAcademicYear(this.filterAcademicYear);
                };
                
                this.selectFaculty = function()
                {
                    trainingsService.changeFaculty(this.filterFaculty);
                };
                
                this.selectTrainingType = function()
                {
                    trainingsService.changeTrainingType(this.filterTrainingType);
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