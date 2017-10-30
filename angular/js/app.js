var myApp = angular.module('myApp',[
    'ngRoute' ,'personController'
]);

myApp.config(['$routeProvider' , function($routeProvider){
    $routeProvider.
    when('/item1',{
        templateUrl: 'partial/item1.html',
        controller : 'ListController'
    }).
    when('/item2',{
        templateUrl: 'partial/item2.html',
        controller : 'DetailsController'
    }).otherwise({
        redirectTo:'/item1'
    })
}]);