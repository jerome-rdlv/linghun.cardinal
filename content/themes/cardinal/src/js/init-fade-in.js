'use strict';
if (typeof fallback === 'object') {
    Array.prototype.forEach.call(document.querySelectorAll('.fade-in'), function (item) {
        fallback.add(null, function () {
            item.classList.remove('fade-in--hidden');
        });
    });
}