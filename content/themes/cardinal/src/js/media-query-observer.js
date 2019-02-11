require('rAF');
require('transition-end');

(function ($) {
    
    function onChange($node, opts) {
        if ($node.css('opacity') === '1') {
            if (typeof opts.on === 'function') {
                opts.on();
            }
        }
        else if (typeof opts.off === 'function') {
            opts.off();
        }
        if (typeof opts.toggle === 'function') {
            opts.toggle($node.css('opacity') === '1');
        }
    }
    
    $.mqo = function (className, opts) {
        var $node = $('.'+ className);
        if (!$node.length) {
            $node = $('<div></div>')
                .addClass(className)
                .on(window.transitionEnd, function () {
                    onChange($(this), opts);
                });
            $('body').append($node);
        }
        if (typeof opts !== 'undefined' && typeof opts.now !== 'undefined' && opts.now) {
            requestAnimationFrame(function () {
                onChange($node, opts);
            });
        }
    };
    
})(jQuery);