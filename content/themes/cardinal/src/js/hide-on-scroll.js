'use strict';

import { trigger } from './create-event';

// import md from './mobile-detect';

if (/*md.mobile() === null && */typeof window.addEventListener === 'function') {
    init();
}

function init() {

    var
        body = document.body,
        delay = 100,
        delta = 20;

    var
        lastScrollTop,
        wasHidden = false,
        didScroll = false;

    // wait for layout to render
    // prevent header to immediately disappear if initial scroll is > 0 
    setTimeout(function () {

        lastScrollTop = window.pageYOffset;

        window.addEventListener('scroll', function () {
            didScroll = true;
        }, {passive: true});

        setInterval(function () {
            if (didScroll) {
                onScroll();
                didScroll = false;
            }
        }, delay);

    }, 2500);

    function onScroll() {
        var scrollTop = window.pageYOffset;
        body.classList.toggle('scrolled-down', scrollTop > delay);
        var scroll = lastScrollTop - scrollTop;
        var threshold = 120;
        if (Math.abs(scroll) > delta) {
            var hidden = scroll < 0 && scrollTop > threshold && !body.classList.contains('nav-on');
            if (hidden !== wasHidden) {
                body.classList.toggle('scrolling-down', hidden);
                wasHidden = hidden;
                trigger.call(document.body, 'scrolling-down', hidden);
            }
            lastScrollTop = scrollTop;
        }
    }
}
