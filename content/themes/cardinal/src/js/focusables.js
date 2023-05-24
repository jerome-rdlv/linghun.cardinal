import selector from 'focusable/index.js';

export default function getFocusables(container) {
    return Array.prototype.filter.call(container.querySelectorAll(selector), function (node) {
        return node.tabIndex >= 0;
    });
}

if (typeof jQuery === 'function') {
    // eslint-disable-next-line
    jQuery.fn.focusables = function () {
        return getFocusables(this.get(0));
    };
}
