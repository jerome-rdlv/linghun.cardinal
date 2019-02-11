'use strict';

import './request-animation-frame-polyfill';
import transitionEnd from './transition-end-polyfill';
import fd from './promise-fastdom';

export default function(id, opts) {
    
    if (!transitionEnd) {
        return false;
    }

    // get node or create it
    fd.measure(function () {
        return document.getElementById(id) || fd.mutate(function transCtrlObsCreateNode () {
            var node = document.createElement('div');
            node.setAttribute('id', id);
            document.body.appendChild(node);
            return node;
        });
    })
        // add event listener
        .then(function (node) {
            node.addEventListener(transitionEnd, function () {
                onChange(node, opts);
            });

            // exec immediately by default
            if (typeof opts === 'undefined' || typeof opts.now === 'undefined' || opts.now) {
                onChange(node, opts);
            }
        })
    ;

    function onChange(node, opts) {
        if (typeof opts.toggle === 'function') {
            opts.toggle();
        }
        if (typeof opts.on === 'function' || typeof opts.off === 'function') {
            fd.measure(function onChangeRead () {
                var opacity = document.defaultView.getComputedStyle(node).getPropertyValue('opacity');
                var isOn = opacity === '1';
                if (isOn) {
                    if (typeof opts.on === 'function') {
                        opts.on(opacity);
                    }
                }
                else {
                    if (typeof opts.off === 'function') {
                        opts.off(opacity);
                    }
                }
            });
        }
    }
    
    return true;
}
