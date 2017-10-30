
var personController = angular.module("personController",[]);
personController.controller("ListController", function($scope){
    $scope.name = 'hussien ahmad 1';
});


personController.controller("DetailsController", function($scope){
    $scope.details = 'Hussien Ahmad 2';
});
