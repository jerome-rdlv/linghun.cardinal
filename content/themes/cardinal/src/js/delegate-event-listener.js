'use strict';
export default function (type, selector, listener, options) {
    var listenTo;
    switch (type) {
        case 'mouseenter':
            listenTo = 'mouseover';
            break;
        case 'mouseleave':
            listenTo = 'mouseout';
            break;
        default:
            listenTo = type;
    }
    this.addEventListener(listenTo, function (e) {
        var target = e.target;
        while (target && target !== this) {
            if (typeof target.matches === 'function' && target.matches(selector)) {
                switch (type) {
                    case 'mouseenter':
                    case 'mouseleave':
                        var related = e.relatedTarget !== undefined ? e.relatedTarget : e.fromElement;
                        target = target.closest(selector);
                        if (!target) {
                            return;
                        }
                        while (related && related !== target && related !== document) {
                            related = related.parentNode;
                        }
                        if (related === target) {
                            return;
                        }
                        listener.apply(target, arguments);
                        break;
                    default:
                        listener.apply(target, arguments);
                }
                break;
            }
            target = target.parentNode;
        }
    }, options);
}