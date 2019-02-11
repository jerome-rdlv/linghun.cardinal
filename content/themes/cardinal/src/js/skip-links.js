'use strict';

import './request-animation-frame-polyfill';

var skips = document.querySelector('.Skips');
var timeoutId;

skips.addEventListener('focusin', function () {
    clearTimeout(timeoutId);
    document.body.classList.add('skips-on');
});
skips.addEventListener('focusout', function () {
    // to let time for click event to trigger
    timeoutId = setTimeout(function () {
        document.body.classList.remove('skips-on');
    }, 10);
});
