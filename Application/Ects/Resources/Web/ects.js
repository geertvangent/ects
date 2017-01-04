(function()
{
    var chamilo = angular.module('chamilo', []);
    
    chamilo.service('chamiloUtilities', [ '$http', function($http)
    {
        
        this.callChamiloAjax = function(context, action, parameters, successCallback, errorCallback)
        {
            var ajaxURI = 'http://desiderius.ehb.be/index.php?application={{ application }}&go={{ action }}';
            ajaxURI = ajaxURI.replace('{{ application }}', context);
            ajaxURI = ajaxURI.replace('{{ action }}', action);
            
            if (errorCallback == undefined)
            {
                errorCallback = function()
                {
                };
            }
            
            $http.post(ajaxURI, $.param(parameters), {
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
                }
            }).success(successCallback).error(errorCallback);
        };
        
        this.callChamiloAjaxPromise = function(context, action, parameters)
        {
            var ajaxURI = 'http://desiderius.ehb.be/index.php?application={{ application }}&go={{ action }}';
            ajaxURI = ajaxURI.replace('{{ application }}', context);
            ajaxURI = ajaxURI.replace('{{ action }}', action);
            
            return $http.post(ajaxURI, $.param(parameters), {
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
                }
            });
        }

    } ]);
    
    var trainingBrowserApp = angular.module('trainingBrowserApp', [ 'ngRoute', 'chamilo' ]);
    
    trainingBrowserApp.config(function($routeProvider)
    {
        $routeProvider.when('/', {
            templateUrl : 'trainingSelectorPanel.html'
        }).when('/training/:trainingId', {
            templateUrl : 'trainingDetailsPanel.html'
        }).when('/trajectory/:subTrajectoryId', {
            templateUrl : 'subTrajectoryDetailsPanel.html'
        }).when('/course/:courseId', {
            templateUrl : 'courseDetailsPanel.html'
        });
    });
    
    trainingBrowserApp.run(function()
    {
    });
    
    trainingBrowserApp.controller('MainController', [ '$scope', '$location', function($scope, $location)
    {
        this.goToPath = function(path)
        {
            $location.path(path);
        }

    } ]);
    
    trainingBrowserApp.controller('TrainingCardsController', [ 'trainingsService', '$scope',
            function(trainingsService, $scope)
            {
                this.trainingsByType = trainingsService.trainingsByType;
                this.isLoadingTrainingList = trainingsService.isLoadingTrainingList;
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.trainingsByType;
                }, angular.bind(this, function(newValue)
                {
                    this.trainingsByType = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return trainingsService.isLoadingTrainingList;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingTrainingList = newValue;
                }));
                
            } ]);
    
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
    
    trainingBrowserApp.controller('TrainingDetailsController', [ 'trainingsService', '$scope', '$route',
            '$routeParams', function(trainingsService, $scope, $route, $routeParams)
            {
                this.training = trainingsService.training;
                this.isLoadingTraining = trainingsService.isLoadingTraining;
                
                if ($routeParams.trainingId)
                {
                    trainingsService.retrieveTrainingById($routeParams.trainingId);
                }
                
                $scope.$watchCollection(function()
                {
                    return trainingsService.training;
                }, angular.bind(this, function(newValue)
                {
                    this.training = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return trainingsService.isLoadingTraining;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingTraining = newValue;
                }));
                
            } ]);
    
    trainingBrowserApp.controller('SubTrajectoryDetailsController', [ 'coursesService', '$scope', '$route',
            '$routeParams', function(coursesService, $scope, $route, $routeParams)
            {
                this.subTrajectory = coursesService.subTrajectory;
                this.isLoadingSubTrajectory = coursesService.isLoadingSubTrajectory;
                
                if ($routeParams.subTrajectoryId)
                {
                    coursesService.retrieveSubTrajectoryById($routeParams.subTrajectoryId);
                }
                
                $scope.$watchCollection(function()
                {
                    return coursesService.subTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.subTrajectory = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingSubTrajectory;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingSubTrajectory = newValue;
                }));
                
            } ]);
    
    trainingBrowserApp.controller('CourseDetailsController', [
            'chamiloUtilities',
            'coursesService',
            '$scope',
            '$http',
            '$route',
            '$routeParams',
            '$sce',
            function(chamiloUtilities, coursesService, $scope, $http, $route, $routeParams, $sce)
            {
                this.courseId = $routeParams.courseId;
                this.course = coursesService.course;
                this.courseDetails = coursesService.courseDetails;
                this.isLoadingCourse = coursesService.isLoadingCourse;
                this.isLoadingCourseDetails = coursesService.isLoadingCourseDetails;
                
                if (this.courseId)
                {
                    coursesService.retrieveCourseById(this.courseId);
                }
                
                $scope.$watchCollection(function()
                {
                    return coursesService.course;
                }, angular.bind(this, function(newValue)
                {
                    this.course = newValue;
                }));
                
                $scope.$watchCollection(function()
                {
                    return coursesService.courseDetails;
                }, angular.bind(this, function(newValue)
                {
                    this.courseDetails = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingCourse;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingCourse = newValue;
                }));
                
                $scope.$watch(function()
                {
                    return coursesService.isLoadingCourseDetails;
                }, angular.bind(this, function(newValue)
                {
                    this.isLoadingCourseDetails = newValue;
                }));
                
                this.getCourseUrl = function()
                {
                    return $sce.trustAsResourceUrl('https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a='
                            + this.courseId + '&b=5&c=1');
                };
                
                this.getCourseDetails = function()
                {
                    if (this.courseDetails)
                    {
                        return $sce.trustAsHtml(this.courseDetails.content);
                    }
                    
                    return '';
                }

            } ]);
    
    trainingBrowserApp.directive('trainingCardsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingCardsController',
            controllerAs : 'trainingCards'
        }
    });
    
    trainingBrowserApp.directive('trainingSelectorPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingSelectorController',
            controllerAs : 'trainingSelector'
        }
    });
    
    trainingBrowserApp.directive('trainingDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'TrainingDetailsController',
            controllerAs : 'trainingDetails'
        }
    });
    
    trainingBrowserApp.directive('subTrajectoryDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'SubTrajectoryDetailsController',
            controllerAs : 'subTrajectoryDetails'
        }
    });
    
    trainingBrowserApp.directive('courseDetailsPanel', function()
    {
        return {
            restrict : 'AE',
            controller : 'CourseDetailsController',
            controllerAs : 'courseDetails'
        }
    });
    
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
    
    trainingBrowserApp.service('coursesService', [ 'chamiloUtilities', function(chamiloUtilities)
    {
        this.subTrajectory = null;
        this.course = null;
        
        this.isLoadingSubTrajectory = false;
        this.isLoadingCourse = false;
        this.isLoadingCourseDetails = false;
        
        this.changeSubTrajectory = function(subTrajectory)
        {
            this.subTrajectory = subTrajectory;
        };
        
        this.retrieveSubTrajectory = function(subTrajectory)
        {
            this.retrieveSubTrajectoryById(subTrajectory.id);
        };
        
        this.retrieveSubTrajectoryById = function(subTrajectoryId)
        {
            this.isLoadingSubTrajectory = true;
            
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Trajectory', {
                'sub_trajectory' : subTrajectoryId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.changeSubTrajectory(result.properties);
                this.isLoadingSubTrajectory = false;
            }));
        };
        
        this.retrieveCourseById = function(courseId)
        {
            this.isLoadingCourse = true;
            
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'Course', {
                'course' : courseId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.course = result.properties;
                this.isLoadingCourse = false;
                
                this.retrieveCourseDetailsById(courseId);
            }));
        }

        this.retrieveCourseDetailsById = function(courseId)
        {
            this.isLoadingCourseDetails = true;
            
            chamiloUtilities.callChamiloAjax('Ehb\\Application\\Ects\\Ajax', 'CourseDetails', {
                'course' : courseId
            }, angular.bind(this, function(result, status, headers, config)
            {
                this.courseDetails = result.properties;
                this.isLoadingCourseDetails = false;
            }));
        }
    } ]);
})();