

<!-- Stored in app/views/layouts/master.blade.php -->
<!doctype html>
<html xmlns:ng="http://angularjs.org" id="ng-app" ng-app="syn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/mobiscroll.custom-2.9.5.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <!--[if IE 7]>
        <link rel="stylesheet" href="css/ie7.css">
    <![endif]-->
    <!--[if IE 8]>
        <link rel="stylesheet" href="css/ie8.css">
    <![endif]-->

    <!--[if lte IE 8]>
        <script src="//cdn.jsdelivr.net/g/json3@3.3.0"></script>
    <![endif]-->
    <!-- // <script src="//cdn.jsdelivr.net/jquery/1.11.0/jquery.js"></script> -->
    <!-- // <script src="js/bootstrap.js"></script> -->
    <!-- // <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.js"></script> -->
    <!-- // <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular-route.js"></script> -->
    <!-- // <script src="js/ui-utils.js"></script> -->


    <script src="//cdn.jsdelivr.net/g/underscorejs@1.6.0,underscore.string@2.3.1,jquery@1.11.0,angularjs@1.2.15(angular.min.js+angular-resource.min.js+angular-animate.min.js+angular-cookies.min.js+angular-route.min.js+angular-sanitize.min.js),angularui@0.4.0"></script>
    <script src="js/ui-utils.js"></script>
    <script src="js/mobiscroll.custom-2.9.5.min.js"></script>
    <script src="js/app.js"></script>

    <!--[if lt IE 9]>
        <script src="//cdn.jsdelivr.net/g/html5shiv@3.7.0,respond@1.4.2"></script>
    <![endif]-->

</head>
    <body>

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>