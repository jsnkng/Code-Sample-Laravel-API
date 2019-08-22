var config = require("../../config.js");

module.exports = {
    phploc: {
        command: [
            'mkdir -p '+config.reports+'/phploc',
            'php '+config.composerBin+'/phploc --log-xml '+config.reports+'/phploc/<%= grunt.template.today("isoDateTime") %>.xml '+config.phpFiles
        ].join('&&')
    },
    securityChecker: {
        command: 'php '+config.composerBin+'/security-checker security:check composer.lock',
        options: {
            stdout: true
        }
    },
    pdepend: {
        command: function () {
            var now = grunt.template.today('isoDateTime'),
            directory = config.reports+'/pdepend/' + now,
            mkdir = 'mkdir -p ' + directory,
            summary = directory + '/summary.xml',
            chart = directory + '/chart.svg',
            pyramid = directory + '/pyramid.svg',
            pdepend = 'php '+config.composerBin+'/pdepend ';
            pdepend += '--summary-xml=' + summary + ' ';
            pdepend += '--jdepend-chart=' + chart + ' ';
            pdepend += '--overview-pyramid=' + pyramid + ' ';
            pdepend += '<%= directories.phpDir %>';

            return mkdir + ' && ' + pdepend;
        }
    }
};
