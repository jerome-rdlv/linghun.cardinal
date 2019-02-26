'use strict';

export default function (preventScroll) {
    var element = this;
    element.addEventListener('blur', function onBlur () {
        element.removeAttribute('tabindex');
        element.removeEventListener('blur', onBlur);
    });
    element.setAttribute('tabindex', -1);
    
    var scroll = {};
    if (preventScroll) {
        scroll.y = window.scrollY;
        scroll.x = window.scrollX;
    }
    element.focus();
    if (preventScroll) {
        window.scrollTo(scroll.x, scroll.y);
    }
}