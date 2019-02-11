'use strict';

// import './request-animation-frame-polyfill';

export default function loop(render) {
    var running,
        endCb = null,
        rafId = null,
        lastFrame = null;

    function step(now) {
        // stop the loop if render returned false
        if (running !== false) {
            rafId = requestAnimationFrame(step);
            running = render(lastFrame ? now - lastFrame : 0);
            lastFrame = now;
        }
        else {
            rafId = null;
            if (endCb) {
                endCb();
            }
        }
    }

    rafId = requestAnimationFrame(step);
    
    return {
        running: function () {
            return rafId !== null;
        },
        stop: function () {
            if (rafId !== null) {
                cancelAnimationFrame(rafId);
            }
        },
        then: function (cb) {
            endCb = cb;
        }
    };
}
