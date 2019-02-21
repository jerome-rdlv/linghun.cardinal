'use strict';

import 'web/objectFitPolyfill';

import './skip-links';
import './edit-key';
import './hide-on-scroll';

import tcObserve from './transition-ctrl-observer';
import listen from './delegate-event-listener';
import initNav from './nav-toggle';
import { trigger } from './create-event';

import * as sscroll from './smooth-scroll';

var body = document.body;
var html = document.documentElement;

function initSmoothScroll() {
    sscroll.setDefaultOffset(86);
    
    listen.call(document, 'click', 'a[href^="#"]:not(.smooth-off)', function (e) {
        e.preventDefault();
        var link = this;
        delayScroll();

        function delayScroll() {
            var navOpen = body.classList.contains('nav-on') || body.classList.contains('nav-closing');
            if (navOpen > 0) {
                setTimeout(delayScroll, 50);
            }
            else {
                sscroll.smoothScroll(link);
            }
        }
    });
}

function init() {
    // create nav toggle button
    document.querySelector('.header__inner').insertAdjacentHTML(
        'beforeend',
        document.getElementById('nav-toggle-tpl').innerText.trim()
    );
    var navToggle = document.querySelector('#nav-toggle');
    var nav = document.querySelector('#nav');
    var navOn = false;
    initNav(navToggle);
    tcObserve('navDisplayCtrl', {
        now: true,
        on: function () {
            navOn = document.body.classList.contains('nav-on');
            nav.removeAttribute('hidden');
        },
        off: function () {
            if (!navOn) {
                nav.setAttribute('hidden', true);
            }
        }
    });
    
    // #nav link
    listen.call(document, 'click', 'a[href="#nav"]', function (e) {
        e.preventDefault();
        trigger.call(navToggle, 'click');
    });
    
    // #main link
    var main = document.getElementById('main');
    main.addEventListener('blur', function () {
        main.removeAttribute('tabindex');
    });
    listen.call(document, 'click', 'a[href="#main"]', function (e) {
        e.preventDefault();
        main.setAttribute('tabindex', -1);
        main.focus();
    });
    
    // #search link
    var search = document.getElementById('search');
    listen.call(document, 'click', 'a[href="#search"]', function (e) {
        e.preventDefault();
        
        // display nav if mobile mode and hidden
        trigger.call(navToggle, 'click');
        
        search.querySelector('input[name="s"]').focus();
    });
    
    initSmoothScroll();
    body.classList.remove('loading');
}


if (!html.classList.contains('js-off')) {
    if (!tcObserve('mainCssCtrl', {on: init, now: true})) {
        setTimeout(function () {
            requestAnimationFrame(init);
        }, 150);
    }
}
