'use strict';

import fd from './promise-fastdom';

function updateSize(canvas) {
    return Promise.resolve()
        .then(function () {
            return fd.measure(function () {
                return {
                    width: canvas.clientWidth,
                    height: canvas.clientHeight
                };
            });
        })
        .then(function (size) {
            return fd.mutate(function () {
                canvas.setAttribute('width', size.width);
                canvas.setAttribute('height', size.height);
            });
        })
        ;
}

export default function fluidCanvas() {
    var canvas;

    window.addEventListener('resize', function () {
        updateSize(canvas);
    });

    return fd.mutate(function () {
        canvas = document.createElement('canvas');
        canvas._ctx = canvas.getContext('2d');
        updateSize(canvas);
        return canvas;
    });
}

