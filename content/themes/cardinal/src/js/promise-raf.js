'use strict';
export default function promisifiedRAF () {
    return new Promise(function (resolve) {
        requestAnimationFrame(resolve);
    });
}
