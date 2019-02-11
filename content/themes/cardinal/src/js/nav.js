// var jQuery = require('../../vendor/jquery/dist/jquery.js');

require('rAF');
require('transition-end');

var jailFocus = require('jail-focus');

(function ($) {

    function init(toggle, options) {

        var opts = $.extend({}, $.fn.nav.defaults, options);
        var $toggle = $(toggle);
        var $doc = $(document);
        var $body = $('body');

        // do not aria-hide outsides as it breaks focus in nav
        var $outsides = opts.outsides;

        var target = $toggle.is('button') ? $toggle.data('target') : $toggle.attr('href'); 
        var $nav = $(target).attr('tabindex', -1);

        if (typeof tapEvt === 'undefined') {
            tapEvt = 'click';
        }

        $toggle.removeClass('loading');

        $nav.addClass('ready');

        // transition control
        var $ctrl = $('<div></div>').addClass('Nav-transCtrl');
        $body.append($ctrl);

        $nav.prop('hidden', true);

        $toggle
            .attr('aria-label', 'Menu')
            .attr('role', 'button')
            .attr('aria-expanded', false)
        ;

        // body close button
        // $body.append(
        //     $('<button></button>')
        //         .attr('aria-label', 'Fermer')
        //         .addClass('Nav-bodyClose')
        // );

        $toggle.on('click', toggleNav);
        $doc.on('click', target + ' a, .Nav-bodyClose', closeNav);
        // $doc.on(tapEvt, 'body, .Nav', bodyClicked);
        $doc.on(window.transitionEnd, '.Nav-transCtrl', onTransitionEnd);

        $doc.on('keydown', function (e) {
            if (e.keyCode === 27) {
                closeNav();
            }
        });
        
        // nav state
        // detect window threshold to adapt nav hidden status
        var $statusCtrl = $('<div></div>').addClass('Nav-statusCtrl');
        $body.append($statusCtrl);
        $doc.on(window.transitionEnd, '.Nav-statusCtrl', updateStatus);

        function updateStatus() {
            var status = $statusCtrl.css('left') === '100px' ? 'desktop' : 'mobile';
            $body.data('nav-status', status);
            $body.trigger('nav-status', status);
        }
        updateStatus();
        
        
        jailFocus.jail($toggle, $nav, function () {
            return $toggle.attr('aria-expanded') === 'true';
        });

        function onTransitionEnd() {
            var opening = $body.hasClass('nav-opening');
            $body.removeClass('nav-opening nav-closing ');
            if (opening) {
                $toggle.attr('aria-expanded', true);
                $nav.focus();
                $toggle.trigger('nav-opened');
            }
            else {
                $toggle.attr('aria-expanded', false);
                $nav.prop('hidden', true);
                $toggle.trigger('nav-closed');
            }
            $toggle.removeClass('loading');
        }

        function openNav()
        {
            if (!$toggle.hasClass('loading') && !$body.hasClass('nav-on')) {
                $toggle.addClass('loading');
                requestAnimationFrame(function () {
                    $nav.prop('hidden', false);
                    requestAnimationFrame(function () {
                        $body.addClass('nav-opening nav-on');

                        if (!window.transitionEnd) {
                            onTransitionEnd();
                        }
                    });
                });
                $toggle.trigger('nav-open');
            }
        }

        function closeNav()
        {
            if (!$toggle.hasClass('loading') && $body.hasClass('nav-on')) {
                $toggle.addClass('loading');
                $body.addClass('nav-closing');
                $body.removeClass('nav-on');

                if (!window.transitionEnd) {
                    onTransitionEnd();
                }
                $toggle.trigger('nav-close');
            }
        }

        function toggleNav(e)
        {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            $toggle.trigger('nav-toggle');
            if ($toggle.attr('aria-expanded') === 'true') {
                closeNav();
            }
            else {
                openNav();
            }
        }
    }

    $.fn.nav = function (options) {
        return this.each(function () {
            init(this, options);
        });
    };

    $.fn.nav.defaults = {
        outsides: $([])
    };

})(jQuery);