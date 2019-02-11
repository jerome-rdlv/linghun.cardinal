'use strict';
export default function (type, selector, listener, options) {
    this.addEventListener(type, function (event) {
        var node = event.target;
        while (node && node !== this) {
            if (typeof node.matches === 'function' && node.matches(selector)) {
                listener.apply(node, arguments);
                break;
            }
            node = node.parentNode;
        }
    }, options);
}