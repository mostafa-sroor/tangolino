//creating new module
// module have many controllers
var mod2 = angular.module("ControllersModule",[]);
var mod = angular.module('FirstModule',['ControllersModule']);
mod2.controller('FisrtController',function($scope){
    $scope.name = 'Ahmad';

    $scope.friends = [{name:'m1',age:22},{name:'m2',age:33},{name:'m3',age:44}] ;
    /*
     *
     *  $scope.FirstName = 'Mostafa';
        $scope.Age       =  23;
        $scope.IsMarried = true;
    */

    $scope
    $scope.person = {FirstName :'Mostafa' , Age:23,IsMarried:true } ;
    $scope.insertDB = function(FirstName , Age , IsMarried){
        alert("FirstName = " + FirstName);
        alert("Age = " + Age);
        alert("IsMarried = " + IsMarried);
        //$http.post('/someUrl', data, config).then(successCallback, errorCallback);
    }

});

mod2.controller('SecondController',function($scope){
    $scope.name = 'Hasan';
});


/*
*
* var app = angular.module('myApp', []);
 app.controller('myCtr', function($scope){
 $scope.names   = ['Ahmad','Mohamad','Wael','Mahmoud','Ali'] ;
 $scope.degree  = "Master Degree";
 $scope.friends = [{name:'hussien',age:20,image:'a'},
 {name:'ahmad',  age:24,image:'b'},
 {name:'issam',  age:33,image:'c'},
 {name:'ghassan',age:36,image:'d'},
 {name:'ali',    age:30,image:'e'}];
 });


 app.controller('newsCtr', function($scope, $http){
 $http.get('https://api.rss2json.com/v1/api.json?rss_url=http%3A%2F%2Ffeeds.twit.tv%2Fbrickhouse.xml')
 .then(function(mydata){
 $scope.news = mydata.data.items;
 });
 $scope.clickme  = function(name, desc){
 console.log("name = " + name );
 console.log("desc = " + desc );
 $scope.updatename = name ;
 $scope.updatedesc = desc ;
 }
 });

 */