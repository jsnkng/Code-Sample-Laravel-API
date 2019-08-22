// /*! console.loge v1.0.0 | (c) 2014 @toddmotto | github.com/toddmotto/console.loge */ ! function(a) {
//     "use strict";
//     if (a) {
//         var b = ["wink", "shake-space", "peepers", "prizza", "hat", "gradient", "fat", "rainbow", "sunglasses", "derp", "shake"],
//             c = ["", "wow! ", "amaze! ", "dazzle! "],
//             d = ["so", "such", "many", "much", "very"],
//             e = function(a) {
//                 return a[Math.floor(Math.random() * a.length)]
//             }, f = function(a) {
//                 return Object.prototype.toString.call(a).match(/\[object(.*)\]/)[1].replace(/\s/g, "")
//             };
//         a.loge || (a.loge = function(g) {
//             var h = "%c " + e(c) + e(d) + " " + f(g) + ": ",
//                 i = "background: url(http://d1e3ezyatlol8u.cloudfront.net/img/212/doge-" + e(b) + "-212.gif) no-repeat 0 0; background-size: 80px 80px;font-family: 'Comic Sans MS'; text-shadow: 0 1px 1px #000; font-size: 14px;padding: 25px; line-height: 70px; color: #fff; font-weight: 100;";
//             a.log.apply(a, "String" === f(g) ? [h += g, i] : [h, i, g])
//         })
//     }
// }(this.console);

// window.console.clog = function(log) {
//     var message = typeof log === 'object' ? '%cLooks like you\'re trying to log an ' : '%cLooks like you\'re trying to log a ',
//         style = 'background:url(http://i.imgur.com/SErVs5H.png);padding:5px 15px 142px 19px;line-height:280px;';
//     console.log.call(console, message + typeof log + '.', style);
// };

// 'use strict';

/* Services */
(function() {
    var app;

    app = angular.module('syn.services', []);

    app.factory('SubmissionService', function($http, $q) {
        return {
            postForm: function($params) {
                return $http({
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    url: 'process.php',
                    method: 'POST',
                    data: $params,
                }).then(function(response) {
                    if (typeof response.data === 'object') {
                        return response.data;
                    } else {
                        return $q.reject(response.data);
                    }
                }, function(response) {
                    return $q.reject(response.data);
                });
            }
        };
    });

    angular.module('underscore', []).factory('_', function() {
        return window._;
    });

}).call(this);

/* Controllers */
(function() {
    var app;

    app = angular.module('syn.controllers', ['syn.services']);

    app.controller('formController', function(SubmissionService, $scope, $window, $timeout, _) {

        $scope.invalidSubmit = false;
        $scope.formData = {
            PatientBirthWeightKg: '0000',
            PatientBirthWeightLb: 0,
            PatientBirthWeightOz: 0
        };

        $scope.test = function() {

            var valid = $scope.userForm.PatientBirthWeightLb.$valid && $scope.userForm.PatientBirthWeightOz.$valid || $scope.userForm.PatientBirthWeightKg.$valid;
            var invalid = $scope.userForm.PatientBirthWeightLb.$invalid && $scope.userForm.PatientBirthWeightOz.$invalid || $scope.userForm.PatientBirthWeightKg.$invalid;
            var pristine = $scope.userForm.PatientBirthWeightLb.$pristine && $scope.userForm.PatientBirthWeightOz.$pristine && $scope.userForm.PatientBirthWeightKg.$pristine;

            if (valid && !pristine) {
                return 'has-success';
            } else if (invalid && (!pristine || $scope.invalidSubmit)) {
                return 'has-error';
            }
        };

        $scope.resetForm = function(event) {
            _.each($scope.formData, function(val, key) {
                $scope.formData[key] = '';
            });
            $scope.userForm.$setPristine();
            $scope.invalidSubmit = false;
        };

        $scope.range = function(min, max, step) {
            step = (step === undefined) ? 1 : step;
            var input = [];
            for (var i = min; i <= max; i += step) input.push(i);
            return input;
        };

        $scope.clearMetric = function() {
            $scope.formData.PatientBirthWeightKg = '';
        };

        $scope.getKilograms = function() {
            var kg = parseFloat($scope.userForm.PatientBirthWeightKg.$viewValue);
            return kg;
        };

        $scope.calculateImperial = function() {

            var pK = parseFloat($scope.getKilograms()) || 0;
            var nearExact = pK / 0.453592;
            var lbs = Math.floor(nearExact);
            var oz = Math.round((nearExact - lbs) * 16);

            if (oz === 16) {
                lbs++;
                oz = 0;
            }

            // if (pK > 15.8) {
            //     lbs = oz = '';
            // }

            return {
                pounds: lbs,
                ounces: oz
            };
        };

        $scope.calculateMetric = function() {

            var lb = parseInt($scope.formData.PatientBirthWeightLb, 10) || 0;
            var oz = parseInt($scope.formData.PatientBirthWeightOz, 10) || 0;

            var pounds = lb + (oz / 16);

            var kg = pounds * 0.453592;

            if (kg === 0) {
                kg = '';
            } else if (kg < 10) {
                kg = '0' + kg;
            }

            return {
                kilograms: kg
            };
        };

        $scope.updateMetric = function() {
            var obj = $scope.calculateMetric();
            console.log(obj);

            $scope.formData.PatientBirthWeightKg = obj.kilograms;
        };

        $scope.updateImperial = function() {
            var obj = $scope.calculateImperial();
            console.log(obj);

            console.log('foo');
            $scope.formData.PatientBirthWeightLb = obj.pounds;
            $scope.formData.PatientBirthWeightOz = obj.ounces;
        };

        $scope.getClass = function(field) {
            // console.log(field);
            if (field.$invalid && (!field.$pristine || $scope.invalidSubmit)) {
                return 'has-error';
            } else if (field.$valid) {
                return 'has-success';
            }
            // return '';
        };

        $scope.processForm = function() {

            SubmissionService.postForm($.param($scope.formData))
                .then(function(data) {
                    console.log(data);
                    if (data.success === true) {

                        alert('successful submission');

                    } else {

                    }
                }, function(error) {

                });
        };

        // function to submit the form after all validation has occurred            
        $scope.submitForm = function(isValid) {
            if (!isValid) {
                console.log('invalid');
                $scope.invalidSubmit = true;
                return;
            } else {
                console.log('valid');
                $scope.invalidSubmit = false;
                $scope.processForm();
            }
        };

        $scope.$watch(
            'unit',
            function(newValue, oldValue) {

                if (!oldValue || newValue === oldValue) return;

                if (newValue === 'metric') {
                    $scope.updateMetric();
                } else {
                    $scope.updateImperial();
                }

            }
        );

        $scope.$watch(
            '[userForm.PatientBirthWeightKg.$viewValue,userForm.PatientBirthWeightLb.$viewValue,userForm.PatientBirthWeightOz.$viewValue]',
            function(newValue, oldValue) {

                if (!newValue) return;

                var minWeight = false;
                var maxWeight = false;

                var kg = parseFloat(newValue[0]) || 0;


                if (kg > 0 && kg <= 15.80) {
                    minWeight = true;
                    maxWeight = true;
                } else if (kg > 15.8) {
                    minWeight = true;
                    maxWeight = false;
                } else {
                    minWeight = false;
                    maxWeight = true;
                }

                _.each([$scope.userForm.PatientBirthWeightKg, $scope.userForm.PatientBirthWeightLb, $scope.userForm.PatientBirthWeightOz], function(element, index, list) {
                    element.$setValidity('minWeight', minWeight);
                    element.$setValidity('maxWeight', maxWeight);
                });

            }, true
        );

    });

}).call(this);

/* Directives */
(function() {
    var app;

    app = angular.module('syn.directives', []);

    app.directive('mobiscrollCalendar', function($window, $timeout) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var scrollPos = 0;

                var scrollFix = function() {
                    $window.scrollTo(0, scrollPos);
                };

                var defaults = {
                    onBeforeShow: function() {
                        scrollPos = angular.element($window).scrollTop();
                    },
                    onShow: function() {
                        $timeout(scrollFix, 300);
                    }
                };

                var options = angular.extend(defaults, scope.$eval(attrs.mobiscrollOptions));

                $(element).mobiscroll().calendar(options);

            }
        };
    });

}).call(this);

/* Filters */
(function() {
    var app;

    app = angular.module('syn.filters', []);

}).call(this);

/* App */
(function() {
    var app;

    app = angular.module('syn', ['syn.services', 'syn.controllers', 'syn.directives', 'syn.filters', 'ngRoute', 'ui.utils', 'underscore']);

    app.config(function($locationProvider, $routeProvider) {

        $routeProvider.when('/etoc', {
            templateUrl: 'partials/etoc.html',
            controller: 'formController'
        });

        $routeProvider.otherwise({
            redirectTo: '/etoc'
        });

    });

}).call(this);