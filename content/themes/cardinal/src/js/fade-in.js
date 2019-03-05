'use strict';

import intersectObserve from './intersection-observer';

export default (function initFadeIn() {

    var stagger = 100;
    var stack = [];
    var revealRunning = false;
    var hiddenClass = 'fade-in--hidden';

    initNodes(document.querySelectorAll('.fade-in'));

    function initNodes(nodes) {
        var anchors = [];
        Array.prototype.forEach.call(nodes, function (node) {
            // hide node
            node.classList.add('fade-in--hidden');

            /**
             * Anchor can be positioned with CSS to change
             * reveal triggering
             */
            var anchor = document.createElement('div');
            anchor.classList.add('fade-in__anchor');
            node.appendChild(anchor);
            anchor._node = node;
            anchors.push(anchor);
        });
        intersectObserve(anchors, reveal);
    }

    function reveal(anchor) {
        var node = anchor._node;
        if (node.classList.contains(hiddenClass)) {
            if (stagger > 0) {
                stack.push(node);
                if (!revealRunning) {
                    revealRunning = true;
                    (function revealLoop() {
                        if (stack.length) {
                            stack.shift().classList.remove(hiddenClass);
                            setTimeout(revealLoop, stagger);
                        } else {
                            revealRunning = false;
                        }
                    })();
                }
            } else {
                node.classList.remove(hiddenClass);
            }
        }
    }

    return {
        /**
         * Change the stagger delay. Setting to 0 disables it.
         * @param value
         */
        setStagger: function (value) {
            stagger = value;
        },
        /**
         * Allow to add new nodes after initialization (in case of
         * an Ajax loading feature for example).
         * @param nodes
         */
        initNodes: function (nodes) {
            initNodes(nodes);
        }
    };
})();
