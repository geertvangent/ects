(function()
{
    var trajectoryRendererApp = angular.module('trajectoryRendererApp', [ 'ngSanitize' ]);
    
    trajectoryRendererApp.directive('trajectoryRenderer', function()
    {
        return {
            restrict : 'E',
            transclude : true,
            scope : {
                'trajectoryId' : '@'
            },
            // template : '<ng-transclude></ng-transclude>',
            controller : 'MainController',
            controllerAs : 'mainController',
            link : function(scope, element, attrs, ctrl, transclude)
            {
                transclude(scope, function(clone, scope)
                {
                    element.append(clone);
                });
            }
        }
    });
    
    trajectoryRendererApp.controller('MainController', [ '$scope', '$http', function($scope, $http)
    {
        this.trajectoryProperties = [];
        
        $http.post('index.php?application=Ehb\\Application\\Ects\\Ajax&go=Trajectory', $.param({
            'sub_trajectory' : $scope.trajectoryId
        }), {
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        }).then(angular.bind(this, function(result)
        {
            if (result && result.data.properties)
            {
                this.trajectoryProperties = result.data.properties;
            }
        }));
        
    } ]);
    
})();