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
                this.trainings = [];
                
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
                        'trainingType' : this.trainingType == null ? null : this.trainingType.type_id,
                    }, angular.bind(this, function(result, status, headers, config)
                    {
                        this.trainings = result.properties.training;
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