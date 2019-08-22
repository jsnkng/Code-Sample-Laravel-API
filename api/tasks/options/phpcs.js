var config = require("../../config.js");

module.exports = {
    all: {
        dir: [config.phpDir]
    },
    options: {
        bin: config.composerBin+'/phpcs',
        standard: 'PSR2',
        ignore: 'database,m2osw', //ignoring m2osw = efax php wrapper
        extensions: 'php',
        errorSeverity: '5',
        warningSeverity: '6'
    }
};
