var config = require("../../config.js");

module.exports = {

        application: {
            dir: config.phpDir
        },
        options: {
            rulesets: 'codesize,unusedcode,naming',
            bin: config.composerBin+'/phpmd',
            reportFile: config.reports+'/phpmd/<%= grunt.template.today("isoDateTime") %>.xml'
        }
};
