'use strict';
export function trigger(type, detail) {
    this.dispatchEvent(createEvent(type, detail));
}

export function createEvent(type, data) {
    var event;
    if (window.CustomEvent) {
        event = new CustomEvent(type, {detail: data});
    } else {
        event = document.createEvent('CustomEvent');
        event.initCustomEvent(type, true, true, data);
    }
    return event;
}
