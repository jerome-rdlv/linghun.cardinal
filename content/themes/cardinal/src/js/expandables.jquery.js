// var jQuery = require('../../vendor/jquery/dist/jquery.js');

require('rAF');
require('transition-end');
require('focusables');
require('jail-focus');

(function ($) {

    /**
     * classes:
     *   - exp-item is the item element within the list
     *   - exp-toggle is the element that triggers the opening (same as or child of exp-item)
     *   - exp-default is used on exp-toggle to have an initial panel opened
     */

    $.expandables = function (node, options) {

        var base = this;
        var $window = $(window);

        var defaults = {
            viewport: $(window)
        };

        var o = $.extend({}, defaults, options);

        base.$node = $(node);
        base.node = node;
        base.$node.data('exp', base);

        base.$toggles = base.$node.find('.exp-toggle');

        base.init = function () {
            base.$node.data('exp-init', true);
            base.$node.append(base.$ctrl);
            base.$node.on('click', '.exp-toggle', base.toggle);
            $window.on('resize', base.onResize);

            base.$toggles
                .attr('role', 'button')
                .attr('aria-expanded', false);

            base.$toggles.each(function () {
                var $toggle = $(this);
                var $target = $($toggle.attr('href'));
                var $ctrl = $('<div class="exp-transCtrl"></div>');

                $toggle.data('exp-target', $target);
                $toggle.data('exp-card', $toggle.hasClass('exp-card') ? $toggle : $toggle.closest('.exp-card'));
                $toggle.data('exp-ctrl', $ctrl);

                $target
                    .addClass('exp-target')
                    .prop('hidden', true)
                    .append($ctrl)
                    .attr('aria-live', 'off')
                ;

                // move target’s inner
                $target.find('> *').addClass('exp-target-inner');

                // insert target just after toggle for natural tab order
                $toggle.after($target);
            });

            base.$cards = base.$node.find('.exp-card');
            base.$targets = base.$node.find('.exp-target');

            // open default panel
            $('.exp-toggle.exp-default').trigger('click');

            // listen image load
            if (base.$node.imagesLoaded !== undefined) {
                base.$node.imagesLoaded(base.layout);
            }
        };

        base.scrollByToggle = function ($toggle, duration) {
            base.scrollCoords(
                $toggle.offset().top + $toggle.outerHeight(),
                $toggle.data('exp-target').height()
            );
        };

        base.scroll = function ($node, duration) {
            base.scrollCoords($node.offset().top, $node.outerHeight());
        };

        base.scrollCoords = function (top, height, duration) {

            var cs = o.viewport.scrollTop();
            var ct = o.viewport.offset().top;
            var cb = o.viewport.height();

            var nt = top - ct;
            if (!$.isWindow(o.viewport.get(0))) {
                nt += cs;
            }
            var nb = nt + height;

            var scroll = 0;

            // if node is taller than viewport
            if (nb - nt > cb - ct) {
                scroll = nt - cs;
            }
            else {
                // if top is too high, or bottom too low
                scroll = (nt - cs < 0 ? nt - cs : 0) +
                    (nb - cb - cs > 0 ? nb - cb - cs : 0);
            }

            return base.scrollTo(scroll + cs, duration);
        };

        base.scrollTo = function (scroll, duration) {

            var deferred = $.Deferred();

            if (scroll) {
                var $viewport;
                if ($.isWindow(o.viewport.get(0))) {
                    $viewport = $('html, body');
                }
                else {
                    $viewport = o.viewport;
                }
                duration = duration | 400;

                // scroll
                $viewport.animate({
                    scrollTop: scroll
                }, {
                    duration: duration,
                    complete: function () {
                        deferred.resolve();
                    }
                });

                // stop the animation if the user scrolls. Defaults on .stop() should be fine
                $viewport.one('scroll mousedown DOMMouseScroll mousewheel keyup', function (e) {
                    var isArrowKey = e.type === 'keyup' && [33, 34, 37, 38, 39, 40].indexOf(e.which) !== -1;
                    if (isArrowKey || e.type === 'mousedown' || e.type === 'mousewheel') {
                        $viewport.stop();
                    }
                });
            }
            else {
                deferred.resolve();
            }

            return deferred.promise();
        };

        base.onResize = function (e) {
            base.layout();
        };


        base.layout = function ($toggle) {
            base.$cards.css({transform: 'none'});

            if (typeof $toggle === 'undefined') {
                $toggle = base.$node.find('.exp-toggle[aria-expanded="true"]');
            }
            if ($toggle.length) {

                var $target = $toggle.data('exp-target');
                var height = parseInt($target.height());

                base.getCardsBelow($toggle.data('exp-card')).css({
                    transform: 'translateY('+ height +'px)'
                });

                $target.css('top', $toggle.offset().top + $toggle.outerHeight() - base.$node.offset().top);

                base.$node.css('paddingBottom', height);
            }
            else if (!base.$node.hasClass('exp-reopen')) {
                base.$node.css('paddingBottom', 0);
            }
        };

        base.toggle = function (e) {
            e.preventDefault();

            if (!base.$node.hasClass('exp-locked')) {
                base.$node.addClass('exp-locked');

                var $toggle = $(this);

                if ($toggle.attr('aria-expanded') !== 'true') {
                    var $previous = base.$node.find('.exp-toggle[aria-expanded="true"]');
                    var promise = null;
                    if ($previous.length) {
                        base.$node.addClass('exp-reopen');
                        if (base.isInView($previous.data('exp-target')) && $previous.offset().top < $toggle.offset().top) {
                            var scroll = $toggle.offset().top + ($.isWindow(o.viewport.get(0)) ? 0 : o.viewport.scrollTop());
                            promise = base.scrollTo(scroll, 300);
                        }
                    }
                    if (promise === null) {
                        var deferred = $.Deferred();
                        deferred.resolve();
                        promise = deferred.promise();
                    }
                    promise.then(function () {
                        base.close($previous).then(function () {
                            base.open($toggle).then(function () {
                                base.$node.removeClass('exp-locked');
                            });
                        });
                    });
                }
                else {
                    base.close($toggle).then(function () {
                        base.$node.removeClass('exp-locked');
                    });
                }
            }
        };

        base.toggleState = function (state, toggle, $toggle) {
            base.$node.toggleClass(state, toggle);
            if ($toggle) {
                $toggle.toggleClass(state, toggle);
                $toggle.data('exp-target').toggleClass(state, toggle);
            }
        };

        base.isInView = function ($target) {
            return !base.isAboveView($target) && !base.isBelowView($target);
        };

        base.isBelowView = function ($target) {
            return $target.offset().top > o.viewport.height() + o.viewport.offset().top;
        };

        base.isAboveView = function ($target) {
            return $target.offset().top + $target.height() < o.viewport.offset().top;
        };

        base.getCardsBelow = function ($card) {
            var cards = [];
            var $node = $card.next();
            var start = false;
            while ($node.length) {
                if (!start && $node.offset().left <= $card.offset().left) {
                    start = true;
                }
                if (start) {
                    cards.push($node.get()[0]);
                }
                $node = $node.next();
            }
            return $(cards);
        };

        base.open = function ($toggle) {

            var deferred = $.Deferred();
            var promise = deferred.promise();

            var $target = $toggle.data('exp-target');
            if ($target) {

                promise.then(base.openEnd);

                $target.css('top', $toggle.offset().top + $toggle.outerHeight() - base.$node.offset().top);

                if ($toggle.hasClass('exp-default')) {
                    $toggle.removeClass('exp-default');
                    $target.prop('hidden', false);

                    base.scrollByToggle($toggle);

                    deferred.resolve($toggle);
                    promise.then(function () {
                        base.layout();
                    });
                }
                else {
                    if (!window.transitionEnd) {
                        deferred.resolve($toggle);
                    }
                    else {
                        $toggle.data('exp-ctrl').one(window.transitionEnd, function () {
                            deferred.resolve($toggle);
                        });

                        $target.prop('hidden', false);

                        base.scrollByToggle($toggle);

                        var height = $target.height();
                        var $cardsBellow = base.getCardsBelow($toggle.data('exp-card'));

                        base.$node.trigger('exp-before-open', $target);

                        requestAnimationFrame(function () {
                            base.toggleState('exp-opening', true, $toggle);
                            base.toggleState('exp-opened', true, $toggle);

                            // if (base.$node.offset().top + base.$node.outerHeight() < o.viewport.offset().top + o.viewport.height()) {
                            //     base.$node.addClass('exp-animate');
                            // }

                            requestAnimationFrame(function () {
                                base.$node.css('paddingBottom', height);
                                $cardsBellow.css({
                                    transform: 'translateY('+ height +'px)'
                                });
                            });
                        });
                    }
                }
            }
            else {
                deferred.resolve($toggle);
            }
            return promise;
        };

        base.openEnd = function ($toggle) {
            base.toggleState('exp-opening', false, $toggle);
            base.toggleState('exp-opened', true, $toggle);
            base.$node.removeClass('exp-animate');

            var $target = $toggle.data('exp-target');

            base.$node.trigger('exp-after-open', $target);
            base.$node.trigger('exp-opened', $target);

            $target
                .prop('hidden', false);

            var scroll = o.viewport.scrollTop();
            $target.find('h2,h3,h4,h5,h6').first().attr('tabindex', -1).focus();
            o.viewport.scrollTop(scroll);

            $toggle.attr('aria-expanded', true);

            base.layout();
        };

        base.close = function ($toggle) {

            var deferred = $.Deferred();
            var promise = deferred.promise();

            if (typeof $toggle === 'undefined') {
                $toggle = base.$node.find('.exp-toggle[aria-expanded="true"]');
            }
            if ($toggle.length) {

                promise.then(base.closeEnd);

                base.$node.trigger('exp-before-close');
                var $target = $toggle.data('exp-target');
                if (!window.transitionEnd) {
                    deferred.resolve($toggle);
                }
                else if (!base.isInView($target)) {
                    deferred.resolve($toggle);
                }
                else {
                    $toggle.data('exp-ctrl').one(window.transitionEnd, function () {
                        deferred.resolve($toggle);
                    });

                    // maintain position only if top of target above viewport, because
                    // animation not understandable in this case
                    if ($target.offset().top < o.viewport.offset().top) {
                        base.scrollTo(o.viewport.scrollTop() - $target.height(), 300);
                    }

                    requestAnimationFrame(function () {
                        base.toggleState('exp-closing', true, $toggle);
                        base.toggleState('exp-opened', false, $toggle);

                        // if (base.$node.offset().top + base.$node.outerHeight() - $target.height() < o.viewport.offset().top + o.viewport.height()) {
                        //     base.$node.addClass('exp-animate');
                        // }

                        requestAnimationFrame(function () {
                            base.$cards.css({transform: 'none'});
                        });
                    });
                }
            }
            else {
                deferred.resolve($toggle);
            }
            return promise;
        };

        base.closeEnd = function ($toggle) {

            var $target = $toggle.data('exp-target');

            var scroll = o.viewport.scrollTop();
            if (!$toggle.hasClass('exp-closing') && base.isAboveView($target)) {
                scroll -= parseInt($target.height());
            }
            base.toggleState('exp-closing exp-opened', false, $toggle);
            base.$node.removeClass('exp-animate');

            $target
                // .css('height', '')
                .prop('hidden', true);

            $toggle
                // .css('margin-bottom', '')
                .attr('aria-expanded', false);

            o.viewport.scrollTop(scroll);

            base.layout();

            if (base.$node.hasClass('exp-reopen')) {
                base.$node.removeClass('exp-reopen');
            }

            base.$node.trigger('exp-after-close');
        };

        if (base.$node.data('exp-init') === undefined) {
            base.init();
        }

        return this;
    };

    $.fn.expandables = function (options) {
        return this.each(function () {
            (new $.expandables(this, options));
        });
    };

})(jQuery);