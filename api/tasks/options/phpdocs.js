var config = require("../../config.js");

module.exports = {

    dist: {
        bin: config.composerBin+'/phpdoc.php',
        directory: config.phpFiles,
        target: config.reports+'/phpdocs',
        ignore: [
            '/database/*'
        ]
    }
};
