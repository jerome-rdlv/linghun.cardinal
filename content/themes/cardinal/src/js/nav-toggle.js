'use strict';

import transitionEnd from './transition-end-polyfill';
import tcObserve from './transition-ctrl-observer';
import listen from './delegate-event-listener';
import jailFocus from './jail-focus';
import {trigger} from './create-event';
import promiseRAF from './promise-raf';
import fd from './promise-fastdom';

export default function init(toggle) {
    var body = document.body;
    var target = toggle.getAttribute('data-target');
    var nav = document.querySelector(target);

    tcObserve('navTransCtrl', {
        toggle: onTransitionEnd
    });

    toggle.addEventListener('click', toggleNav);
    listen.call(nav, 'click', 'a', closeNav);
    document.addEventListener('keydown', function (e) {
        // press on ESC
        if (e.keyCode === 27) {
            toggle.focus();
            closeNav();
        }
    });

    jailFocus(nav, toggle);

    toggle.classList.remove('loading');
    toggle.setAttribute('aria-expanded', false);

    nav.setAttribute('tabindex', -1);
    nav.setAttribute('hidden', true);
    nav.classList.add('ready');

    function toggleNav() {
        trigger.call(toggle, 'nav-toggle');
        if (toggle.getAttribute('aria-expanded') === 'true') {
            closeNav();
        } else {
            openNav();
        }
    }

    function openNav() {
        if (!toggle.classList.contains('loading') && !body.classList.contains('nav-on')) {
            toggle.classList.add('loading');
            trigger.call(toggle, 'nav-open');

            return Promise.resolve()
                .then(function () {
                    return fd.mutate(function () {
                        nav.removeAttribute('hidden');
                    });
                })
                .then(promiseRAF)
                .then(function () {
                    return fd.mutate(function () {
                        body.classList.add('nav-opening');
                        body.classList.add('nav-on');

                        if (!transitionEnd) {
                            onTransitionEnd();
                        }
                    });
                })
                ;
        }
    }

    function closeNav() {
        if (!toggle.classList.contains('loading') && body.classList.contains('nav-on')) {
            toggle.classList.add('loading');
            body.classList.add('nav-closing');
            body.classList.remove('nav-on');
            trigger.call(toggle, 'nav-close');

            if (!transitionEnd) {
                onTransitionEnd();
            }
        }
    }

    function onTransitionEnd() {
        var opening = body.classList.contains('nav-opening');
        body.classList.remove('nav-opening');
        body.classList.remove('nav-closing');
        if (opening) {
            toggle.setAttribute('aria-expanded', true);
            nav.focus();
            trigger.call(toggle, 'nav-opened');
        } else {
            toggle.setAttribute('aria-expanded', false);
            nav.setAttribute('hidden', true);
            trigger.call(toggle, 'nav-closed');
        }
        toggle.classList.remove('loading');
    }
}
