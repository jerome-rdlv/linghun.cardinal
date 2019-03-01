/* global fallback */
'use strict';
if (typeof fallback === 'object' && typeof XMLHttpRequest === 'function') {
    Array.prototype.forEach.call(document.querySelectorAll('[data-more]'), function (list) {
        var pagination = document.querySelector(list.getAttribute('data-more'));
        if (!pagination) {
            return;
        }
        
        var children = [];
        var numbers = pagination.querySelector('.pagination__numbers');
        var button = document.createElement('button');
        button.innerText = list.getAttribute('data-more-label') || pagination.getAttribute('data-more-label');
        button.setAttribute('data-more-url', pagination.getAttribute('data-more-url'));
        button.setAttribute('type', 'button');
        button.setAttribute('disabled', 'disabled');
        button.classList.add('pagination__more');
        button.classList.add('print-off');
        button.classList.add('cta');

        fallback.add(
            function () {
                while (pagination.firstChild) {
                    children.push(pagination.firstChild);
                    pagination.removeChild(pagination.firstChild);
                }
                pagination.appendChild(numbers);
                pagination.appendChild(button);
                pagination.classList.add('pagination--more-on');
            },
            function () {
                pagination.classList.remove('pagination--more-on');
                pagination.removeChild(button);
                pagination.removeChild(numbers);
                while (children.length) {
                    pagination.appendChild(children.shift());
                }
            }
        );
    });
}
