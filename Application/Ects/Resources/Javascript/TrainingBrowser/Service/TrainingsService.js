(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('trainingsService', [
            'chamiloUtilities',
            function(chamiloUtilities)
            {
                this.academicYear = null;
                this.faculty = null;
                this.trainingType = null;
                this.textFilter = null;
                
                this.academicYears = [];
                this.faculties = [];
                this.trainingTypes = [];
                this.trainingsByType = [];
                
                this.selectedTraining = null;
                
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
                        'year' : this.academicYear == null ? null : this.academicYear
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.faculties = result.properties.faculty;
                    }));
                };
                
                this.retrieveTrainingTypes = function()
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterTrainingTypes', {
                        'year' : this.academicYear == null ? null : this.academicYear,
                        'faculty' : this.faculty == null ? null : this.faculty.faculty_id
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.trainingTypes = result.properties.type;
                    }));
                };
                
                this.retrieveTrainings = function()
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'FilterTrainings', {
                        'year' : this.academicYear == null ? null : this.academicYear,
                        'faculty' : this.faculty == null ? null : this.faculty.faculty_id,
                        'type' : this.trainingType == null ? null : this.trainingType.type_id,
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.trainingsByType = result.properties.type;
                    }));
                };
                
                this.changeTraining = function(training)
                {
                    this.selectedTraining = training;
                };
                
                this.retrieveTraining = function(training)
                {
                    chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Training', {
                        'training' : training.id
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.changeTraining(result.properties);
                    }));
                };
                
                this.changeAcademicYear = function(academicYear)
                {
                    this.academicYear = academicYear;
                    this.faculty = null;
                    this.trainingType = null;
                    this.retrieveFaculties();
                    this.retrieveTrainingTypes();
                    this.retrieveTrainings();
                };
                
                this.changeFaculty = function(faculty)
                {
                    this.faculty = faculty;
                    this.trainingType = null;
                    this.retrieveTrainingTypes();
                    this.retrieveTrainings();
                };
                
                this.changeTrainingType = function(trainingType)
                {
                    this.trainingType = trainingType;
                    this.retrieveTrainings();
                };
                
                this.retrieveAcademicYears();
            } ]);
})();