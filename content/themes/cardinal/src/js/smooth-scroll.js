'use strict';

import easing from 'web/bezier-easing/src/index';
import loop from './render-loop';

var defaults = {
    viewport: window,
    offset: 0,
    behavior: 'smooth',
    duration: 600,
    ease: easing(0.2, 0, 0.5, 1)
};

// noinspection JSUnusedGlobalSymbols
export function setDefaultViewport(node) {
    defaults.viewport = node;
}

// noinspection JSUnusedGlobalSymbols
export function setDefaultOffset(offset) {
    defaults.offset = offset;
}

export function smoothScroll(link, opts) {
    var href = link.getAttribute('href');
    if (href.length && href !== '#') {
        var target = document.querySelector(href);
        if (target) {
            scrollTo(target, opts);
        }
    }
}

function merge(o1, o2) {
    var o = {};
    var name;
    for (name in o1) {
        // noinspection JSUnfilteredForInLoop
        o[name] = o1[name];
    }
    for (name in o2) {
        // noinspection JSUnfilteredForInLoop
        o[name] = o2[name];
    }
    return o;
}

// function loop(render) {
//     var running, lastFrame = null;
//     function step(now) {
//         // stop the loop if render returned false
//         if (running !== false) {
//             requestAnimationFrame(step);
//             running = render(lastFrame ? now - lastFrame : 0);
//             lastFrame = now;
//         }
//     }
//     requestAnimationFrame(step);
// }

export function scrollTo(target, opts) {
    var o = merge(defaults, opts);
    var start = o.viewport === window ? o.viewport.pageYOffset : o.viewport.scrollTop;
    var end = target.getBoundingClientRect().top + start;
    
    end -= typeof o.offset === 'function' ? o.offset(end) : o.offset;
    
    var progress = 0;
    var scroll;
    
    if (o.viewport === window) {
        scroll = function (scroll) {
            o.viewport.scroll(0, scroll);
        }
    }
    else {
        scroll = function (scroll) {
            o.viewport.scrollTop = scroll;
        }
    }

    loop(function (delta) {
        progress += delta;
        scroll(start + (end - start) * o.ease(progress / o.duration));
        return progress < o.duration;
    });
}
