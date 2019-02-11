/* global webfonts, FontFaceObserver */
(function (fonts) {

    var toLoad = fonts.length;

    function checkLoad(font) {
        // eslint-disable-next-line
        console.debug('Font loaded: %s', font.family, font.weight, font.style);
        --toLoad;
        if (toLoad === 0) {
            document.querySelector('html').classList.add('fonts-on');
        }
    }

    for (var i = 0; i < fonts.length; ++i) {
        (new FontFaceObserver(
            fonts[i].family,
            fonts[i].opts
        )).load().then(checkLoad);
    }

})(webfonts);