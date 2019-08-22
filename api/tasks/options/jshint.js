var config = require("../../config.js");

module.exports = {
    options: {
      curly: true,
      eqeqeq: true,
      eqnull: true,
      browser: true,
      globals: {
        jQuery: true
      }
    },
    all:  config.jsFiles
};
