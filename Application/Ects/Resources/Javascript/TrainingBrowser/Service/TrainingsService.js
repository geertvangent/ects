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
                'year' : this.academicYear == null ? null : this.academicYear.value
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
                }
            }));
        };
        
        this.changeAcademicYear = function(academicYear)
        {
            this.academicYear = academicYear;
            this.retrieveTrainings();
        };
        
        this.retrieveTrainings();
    } ]);
})();