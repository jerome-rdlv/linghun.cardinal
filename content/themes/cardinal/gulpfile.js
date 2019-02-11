const gulpfile = require('./node_modules/gulpfile/index.js');

/**
 * @see node_modules/gulpfile/defaults.js for possible options
 */
gulpfile({
    url: "cardinal.linghun.rdlv.me",
    basePath: __dirname,
    entries: [
        "js/main.js",
        "js/fonts.js",
        "js/slider.js",
        "js/editor-enable-fonts.js",
        "js/tac-overrides.js"
    ],
    inlines: [
        "js/body-top.js"
    ]
});
