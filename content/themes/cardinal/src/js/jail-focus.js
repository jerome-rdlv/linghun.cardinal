'use strict';

import focusables from './focusables';
import fd from './promise-fastdom';

export default function(jail, toggle) {
    
    var first, last;
    
    function isActive() {
        return first && last && (!toggle || toggle.getAttribute('aria-expanded') === 'true');
    }

    function onKeyDownToggle(e) {
        if (focusables && e.keyCode === 9 && isActive()) {
            e.preventDefault();
            if (e.shiftKey) {
                last.focus();
            }
            else {
                first.focus();
            }
        }
    }

    function onKeyDownLast(e) {
        if (e.keyCode === 9 && !e.shiftKey && isActive()) {
            e.preventDefault();
            toggle ? toggle.focus() : first.focus();
        }
    }

    function onKeyDownFirst(e) {
        if (e.keyCode === 9 && e.shiftKey && isActive()) {
            e.preventDefault();
            toggle ? toggle.focus() : last.focus();
        }
    }

    function onKeyDownJail(e) {
        if (e.target === this && e.keyCode === 9 && e.shiftKey && isActive()) {
            e.preventDefault();
            toggle ? toggle.focus() : first.focus();
        }
    }

    function update() {

        // drop previous listeners
        if (first && last) {
            first.removeEventListener('keydown', onKeyDownFirst);
            last.removeEventListener('keydown', onKeyDownLast);
        }

        // renew focusables
        first = last = null;
        
        var display;
        
        return fd
            .measure(function cacheDisplayValue () {
                display = jail.style.display;
            })
            .then(function () {
                return fd.mutate(function writeDisplayValue () {
                    jail.style.display = 'block';
                });
            })
            .then(function () {
                return fd.measure(function readNodesAndTabindex () {
                    var nodes = focusables(jail);
                    if (nodes.length > 0) {
                        first = nodes[0];
                        last = nodes[nodes.length - 1];
                    }
                });
            })
            .then(function () {
                return fd.mutate(function restoreDisplayValue () {
                    jail.style.display = display;

                    // at end of nav block, return focus to close button
                    last.addEventListener('keydown', onKeyDownLast);

                    // at begin of nav block, return focus to close button (with shift)
                    first.addEventListener('keydown', onKeyDownFirst);
                });
            })
        ;
    }
    
    update().then(function () {
        // in case focus was put on wrapper
        jail.addEventListener('keydown', onKeyDownJail);

        // from toggle
        if (toggle) {
            toggle.addEventListener('keydown', onKeyDownToggle);
        }

        if (jail && typeof MutationObserver === 'function') {
            var jailObserver = new MutationObserver(function () {
                update();
            });
            jailObserver.observe(jail, {
                subtree: true,
                childList: true
            });
        }
    });
    
    return {
        update: update
    };
}
