/*
 * @see node_modules/gulpfile/defaults.js for possible options
 */
module.exports = require('./node_modules/gulpfile/index.js')({
    url: "cardinal.linghun.rdlv.me",
    basePath: __dirname,
    tasks: {
        js: [
            "js/main.js",
            "js/fonts.js",
            "js/slider.js",
            "js/editor-enable-fonts.js",
            "js/tac-overrides.js"
        ],
        jsil: [
            "js/body-top.js",
            "js/init-*.js",
            "js/request-animation-frame-polyfill.js",
            "js/object-assign-polyfill.js",
        ]
    },
});
