(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('trainingsService', [
            'chamiloUtilities',
            function(chamiloUtilities)
            {
                this.filterAcademicYear = null;
                this.filterFaculty = null;
                this.filterTrainingType = null;
                this.filterText = null;
                
                this.academicYears = [];
                this.faculties = [];
                this.trainingTypes = [];
                this.trainingsByType = [];
                
                this.training = null;
                
                this.isLoadingTrainingList = false;
                this.isLoadingTraining = false;
                
                this.retrieveAcademicYears = function()
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterYears', {}, angular.bind(
                            this, function(result, status, headers, config)
                            {
                                this.academicYears = result.properties.year;
                                this.changeAcademicYear(this.academicYears[0]);
                            }));
                };
                
                this.retrieveFaculties = function()
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterFaculties', {
                        'year' : this.filterAcademicYear == null ? null : this.filterAcademicYear
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.faculties = result.properties.faculty;
                    }));
                };
                
                this.retrieveTrainingTypes = function()
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterTrainingTypes', {
                        'year' : this.filterAcademicYear == null ? null : this.filterAcademicYear,
                        'faculty' : this.filterFaculty == null ? null : this.filterFaculty.faculty_id
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.trainingTypes = result.properties.type;
                    }));
                };
                
                this.retrieveTrainings = function()
                {
                    this.isLoadingTrainingList = true;
                    
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterTrainings', {
                        'year' : this.filterAcademicYear == null ? null : this.filterAcademicYear,
                        'faculty' : this.filterFaculty == null ? null : this.filterFaculty.faculty_id,
                        'type' : this.filterTrainingType == null ? null : this.filterTrainingType.type_id,
                        'text' : this.filterText
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.trainingsByType = result.properties.type;
                        this.isLoadingTrainingList = false;
                    }));
                };
                
                this.changeTraining = function(training)
                {
                    this.training = training;
                };
                
                this.retrieveTraining = function(training)
                {
                    this.retrieveTrainingById(training.id);
                };
                
                this.retrieveTrainingById = function(trainingId)
                {
                    this.isLoadingTraining = true;
                    
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Training', {
                        'training' : trainingId
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.changeTraining(result.properties);
                        this.isLoadingTraining = false;
                    }));
                };
                
                this.changeAcademicYear = function(academicYear)
                {
                    this.filterAcademicYear = academicYear;
                    this.filterFaculty = null;
                    this.filterTrainingType = null;
                    this.retrieveFaculties();
                    this.retrieveTrainingTypes();
                    this.retrieveTrainings();
                };
                
                this.setFaculty = function(faculty)
                {
                    this.filterFaculty = faculty;
                    this.filterTrainingType = null;
                };
                
                this.changeFaculty = function(faculty)
                {
                    this.setFaculty(faculty);
                    this.retrieveTrainingTypes();
                    this.retrieveTrainings();
                };
                
                this.setTrainingType = function(trainingType)
                {
                    this.filterTrainingType = trainingType;
                };
                
                this.changeTrainingType = function(trainingType)
                {
                    this.setTrainingType(trainingType);
                    this.retrieveTrainings();
                };
                
                this.setFilterText = function(filterText)
                {
                    this.filterText = filterText;
                };
                
                this.changeFilterText = function(filterText)
                {
                    this.setFilterText(filterText);
                    this.retrieveTrainings();
                };
            } ]);
})();