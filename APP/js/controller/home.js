
app.controller('homeCtrl', [ '$scope', '$http', function ( $scope, $http) {

    $scope.newShermark = {};
    $scope.position    = 0;
    $scope.articles   = [];
    $scope.articleOne   = {};

    $scope.edit        = false;

    getArticle = function(){
        var url = 'http://localhost:8888/Projets/framework/mode-simple/DEV/article.json';
        $http.get(url)
             .success(injectData)
             .error(function(){
                alert('Url non chargé');
        });

    };

    $scope.getOneArticle = function( id){
        var url = 'http://localhost:8888/Projets/framework/mode-simple/DEV/article/'+id+'.json';
        $http.get(url)
             .success(injectDataOne)
             .error(function(){
                alert('Url non chargé');
        });

    };

    injectData = function(data){
        $scope.articles = data;
    }

    injectDataOne = function(data){
        $scope.position = 1;
        $scope.articleOne  = data[0];
    }

    getArticle();
}]);
