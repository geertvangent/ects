(function()
{
    var trainingBrowserApp = angular.module('trainingBrowserApp');
    
    trainingBrowserApp.service('trainingsService', [ '$http', function($http)
    {
        this.academicYear = null;
        this.faculty = null;
        this.trainingType = null;
        this.textFilter = null;
        
        this.academicYears = [];
        this.faculties = [];
        this.trainingTypes = [];
        this.trainings = [];
        
        this.retrieveTrainings = function()
        {
            $http.post('index.php?application=Ehb\\Application\\Ects\\Ajax&go=Filter', $.param({
                'year' : this.academicYear == null ? null : this.academicYear,
                'faculty' : this.faculty == null ? null : this.faculty.faculty_id,
                'type' : this.trainingType == null ? null : this.trainingType.type_id
            }), {
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
                }
            }).success(angular.bind(this, function(result, status, headers, config)
            {
                if (result && result.result_code == 200)
                {
                    this.academicYears = result.properties.year;
                    this.trainingTypes = result.properties.type;
                    this.faculties = result.properties.faculty;
                    this.trainings = result.properties.training;
                    
                    this.academicYear = result.properties.filter.year;
                    this.trainingType = result.properties.filter.type;
                    this.faculty = result.properties.filter.faculty;
                    this.filterText = result.properties.filter.text;
                    
                    console.log(this.faculty);
                }
            }));
        };
        
        this.changeAcademicYear = function(academicYear)
        {
            this.academicYear = academicYear;
            this.faculty = null;
            this.trainingType = null;
            this.retrieveTrainings();
        };
        
        this.changeFaculty = function(faculty)
        {
            this.faculty = faculty;
            this.trainingType = null;
            this.retrieveTrainings();
        };
        
        this.changeTrainingType = function(trainingType)
        {
            this.trainingType = trainingType;
            this.retrieveTrainings();
        };
        
        this.retrieveTrainings();
    } ]);
})();