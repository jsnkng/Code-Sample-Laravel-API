var config = require("../../config.js");

module.exports = {

   options: {
      globals: {},
      esprimaOptions: {},
      verbose: false
    },
    all:{
      files: {
        src: config.jsFiles
      }
    }
};
