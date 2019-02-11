/* global tinymce */
(function (tinymce) {
    'use strict';
    tinymce.PluginManager.add('editor-enable-fonts', function (editor) {
        editor.on('init', function () {
            editor.getDoc().documentElement.classList.add('fonts-on');
        });
    });
})(tinymce);