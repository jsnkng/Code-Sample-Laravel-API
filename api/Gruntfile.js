module.exports = function (grunt) {
    'use strict';

    var composer = require('./composer');
    var config = require("./config.js");

    // Make sure that Grunt doesn't remove BOM from our utf8 files
    // on read
    grunt.file.preserveBOM = true;

    // Helper function to load the config file
    function loadConfig(path) {
      var glob = require('glob');
      var object = {};
      var key;

      glob.sync('*', {cwd: path}).forEach(function(option) {
        key = option.replace(/\.js$/,'');
        object[key] = require(path + option);
      });

      return object;
    }

    // Load task options
    var gruntConfig = loadConfig('./tasks/options/');

    // Package data
    gruntConfig.pkg = grunt.file.readJSON("package.json");

    // Project config
    grunt.initConfig(gruntConfig);

    // Load all grunt-tasks in package.json
    require("load-grunt-tasks")(grunt);

    // show elapsed time at the end
    require('time-grunt')(grunt);

    // Register external tasks
    grunt.loadTasks("tasks/");

    // Task alias's

    grunt.registerTask('phploc', [
        'shell:phploc'
    ]);

    grunt.registerTask('phpmdMk', [
        'mkdir:phpmd',
        'phpmd'
    ]);
    grunt.registerTask('phpdocs', [
        'clean:phpdocumentor',
        'phpdocumentor'
    ]);
    grunt.registerTask('default', ['test']);
    grunt.registerTask('test', ['jsvalidate', 'jshint', 'jsonlint', 'phplint', 'phpmdMk', 'phpcs']);
    grunt.registerTask('lint', ['jsonlint', 'phplint', 'phpcs']);
};
