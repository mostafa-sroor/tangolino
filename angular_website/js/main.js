
var app = angular.module('myApp', []);
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
