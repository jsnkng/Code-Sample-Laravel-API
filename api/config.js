var composer = require('./composer');
var config = {};
module.exports = config;

config.composer = composer.config['vendor-dir'] || 'vendor';
config.composerBin = composer.config['bin-dir'] || 'vendor/bin';
config.reports = 'logs';
config.jsFiles = ['Gruntfile.js', 'tasks/{,*/}*.js', 'test/**/{,*/}*.js'];
config.jsonFiles = ['/{,*/}*.json',];
config.phpFiles = ['app/**/{,*/}*.php'];
config.phpDir = 'app';
config.nodeunit = ['test/**/*.js'];
config.phpTests = 'tests/php';