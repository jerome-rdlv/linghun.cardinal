/* global fallback */
'use strict';
if (typeof fallback === 'object' && typeof XMLHttpRequest === 'function') {
    Array.prototype.forEach.call(document.querySelectorAll('[data-more-pagination]'), function (list) {
        var pagination = document.querySelector(list.getAttribute('data-more-pagination'));
        if (pagination) {
            fallback.add(
                function () {
                    pagination.style.display = 'none';
                },
                function () {
                    pagination.style.display = '';
                }
            );
        }
    });
}
