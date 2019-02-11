'use strict';

export default function observe(nodes, inCB, outCB) {
    if (!nodes.length) {
        return false;
    }
    if (!window.addEventListener || !nodes[0].getBoundingClientRect) {
        // eslint-disable-next-line no-console
        console.warn('Browser is not compatible, cannot observe nodes intersection.');
        return false;
    }

    if (typeof IntersectionObserver === 'function') {
        const observer = new IntersectionObserver(function (nodes) {
            nodes.forEach(function (node) {
                node.isIntersecting ? callback(inCB, node.target) : callback(outCB, node.target);
            });
        });

        for (let i = 0; i < nodes.length; ++i) {
            observer.observe(nodes[i]);
        }
    }
    else {
        window.addEventListener('scroll', updateNodesState, {passive: true});
        window.addEventListener('resize', updateNodesState, {passive: true});
        updateNodesState();
    }
    
    function callback(cb, node) {
        if (typeof cb === 'function') {
            cb(node);
        }
    }
    
    function updateNodesState() {
        const vh = window.innerHeight;
        for (let i = 0; i < nodes.length; ++i) {
            const rect = nodes[i].getBoundingClientRect();
            const intersect = rect.top < vh && rect.top + rect.height > 0;
            if (intersect !== nodes[i].ioIntersect) {
                nodes[i].ioIntersect = intersect;
                intersect ? callback(inCB, nodes[i]) : callback(outCB, nodes[i]);
            }
        }
    }
}
