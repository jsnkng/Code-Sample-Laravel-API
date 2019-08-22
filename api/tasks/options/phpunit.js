var config = require("../../config.js");

module.exports = {

        classes: {
            dir: config.phpTests
        },
        options: {
            bin: config.composerBin+'/phpunit',
            bootstrap: 'bootstrap/autoload.php',
            staticBackup: false,
            colors: true,
            noGlobalsBackup: false
        }
};
