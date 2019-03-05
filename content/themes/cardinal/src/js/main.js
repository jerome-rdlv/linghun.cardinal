/* global fallback, jQuery, imagesLoaded */
'use strict';

import 'web/objectFitPolyfill';

import './skip-links';
import './edit-key';
import './hide-on-scroll';
import './pagination-more';

import fadeIn from './fade-in';
import fd from './promise-fastdom';
import fluidCanvas from './fluid-canvas';
import focus from './focus-element';
import initNav from './nav-toggle';
import listen from './delegate-event-listener';
import loop from './render-loop';
import md from './mobile-detect';
import tcObserve from './transition-ctrl-observer';
import {trigger} from './create-event';

import * as sscroll from './smooth-scroll';

var body = document.body;
var html = document.documentElement;

function initSmoothScroll() {
    sscroll.setDefaultOffset(0);

    listen.call(document, 'click', 'a[href^="#"]:not(.smooth-off)', function (e) {
        e.preventDefault();
        var link = this;
        delayScroll();

        function delayScroll() {
            if (document.getElementById('nav-toggle').getAttribute('aria-expanded') === 'true') {
                setTimeout(delayScroll, 50);
            } else {
                sscroll.smoothScroll(link);
            }
        }
    });
}

function initNavBacks(nav) {
    var canvas, cache, links;
    var currentLoop = null;
    var duration = 250;
    var focused = false;
    var overed = false;
    var currentLink = null;

    function isLoaded(img) {
        return img && img.complete && img.naturalWidth !== 0;
    }

    function isReady(img) {
        return isLoaded(img) && img._width !== undefined && img._width !== 0;
    }

    function loadNaturalSize(img) {
        img._naturalSizePromise = new Promise(function (resolve, reject) {
            const cimg = new Image();
            cimg.addEventListener('load', function () {
                img._width = cimg.naturalWidth;
                img._height = cimg.naturalHeight;
                img._ratio = img._width / img._height;
                resolve(img);
            });
            cimg.addEventListener('error', function () {
                reject('canvasImg has not loaded');
            });
            cimg.src = img.currentSrc || img.getAttribute('src');
            return img;
        });
        return img._naturalSizePromise;
    }

    function attachImages(links) {
        return Promise.resolve()
            .then(function () {
                return fd.mutate(function () {
                    nav.insertAdjacentHTML(
                        'beforeend',
                        document.getElementById('nav-backs-tpl').innerText.trim()
                    );
                    return links;
                });
            })
            .then(function () {
                return fd.measure(function () {
                    var promises = [];
                    Array.prototype.forEach.call(links, function (link) {
                        var img = document.getElementById('item-back-' + link.getAttribute('data-id'));
                        link._back = img;

                        // event will be fired when a new currentSrc is loaded,
                        // in case of window resize for example, according to srcset
                        img.addEventListener('load', function () {
                            promises.push(loadNaturalSize(img));
                        });
                        img.addEventListener('error', function () {
                            console.warn('img has not loaded (' + img.currentSrc + ').');
                        });
                        if (isLoaded(img)) {
                            promises.push(loadNaturalSize(img));
                        }
                    });
                });
            })
            ;
    }

    function animate(step) {
        // stop current loop
        if (currentLoop) {
            currentLoop.stop();
        }

        return new Promise(function (resolve) {

            if (canvas.width === 0) {
                resolve();
                return;
            }

            // cache current canvas
            cache._ctx.clearRect(0, 0, canvas.width, canvas.height);
            cache._ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height);

            var progress = 0;
            currentLoop = loop(function (delta) {
                progress += delta;
                step(Math.max(0, Math.min(1, progress / duration)));
                if (progress < duration) {
                    return true;
                } else {
                    resolve();
                    return false;
                }
            });
        });
    }

    function fadeIn() {
        // console.log('fade in');
        if (this === currentLink) {
            return;
        }
        currentLink = this;
        var img = currentLink._back;
        nav.classList.add('back-on');
        if (img && isReady(img)) {
            animate(function (opacity) {
                var cw = canvas.width;
                var ch = canvas.height;

                // clean canvas
                canvas._ctx.clearRect(0, 0, cw, ch);

                // restore cache bitmap
                canvas._ctx.drawImage(cache, 0, 0, cw, ch);

                // draw image with opacity
                var scale = img._ratio >= cw / ch ? img._height / ch : img._width / cw;
                var offsetTop = (img._height - ch * scale) / 2;
                var offsetLeft = (img._width - cw * scale) / 2;
                canvas._ctx.globalAlpha = opacity;
                canvas._ctx.drawImage(
                    img,
                    offsetLeft, offsetTop, cw * scale, ch * scale,
                    0, 0, cw, ch
                );
                canvas._ctx.globalAlpha = 0.3 * opacity;
                canvas._ctx.fillRect(0, 0, cw, ch);
                canvas._ctx.globalAlpha = 1;
            });
        }
    }

    function fadeOut() {
        // console.log('fade out');
        currentLink = null;
        return animate(function (progress) {
            // clean canvas
            canvas._ctx.clearRect(0, 0, canvas.width, canvas.height);

            // restore cache bitmap with opacity
            canvas._ctx.globalAlpha = (1 - progress);
            canvas._ctx.drawImage(cache, 0, 0, canvas.width, canvas.height);

            canvas._ctx.globalAlpha = 1;
        })
            .then(function () {
                canvas._ctx.clearRect(0, 0, canvas.width, canvas.height);
                nav.classList.remove('back-on');
            });
    }

    if (md.mobile() === null) {

        links = document.querySelectorAll('.nav .menu a');

        Promise.all([
            attachImages(links),
            Promise.all([
                fluidCanvas().then(function (node) {
                    node.classList.add('nav__back');
                    nav.appendChild(node);
                    canvas = node;
                }),
                fluidCanvas().then(function (node) {
                    node.classList.add('nav__cache');
                    nav.appendChild(node);
                    cache = node;
                })
            ])
        ]);

        var linkSelector = '.nav .menu-item a';

        listen.call(document, 'focusin', linkSelector, function () {
            focused = true;
            fadeIn.call(this);
        });
        listen.call(document, 'focusout', linkSelector, function () {
            focused = false;
            // wait for focusin event to happen
            requestAnimationFrame(function () {
                if (!focused) {
                    fadeOut();
                }
            });
        });

        listen.call(document, 'mouseenter', linkSelector, function () {
            overed = true;
            fadeIn.call(this);
        });
        listen.call(document, 'mouseleave', linkSelector, function () {
            overed = false;
            // wait for mouseover event to happen
            requestAnimationFrame(function () {
                if (!overed) {
                    if (document.activeElement.matches(linkSelector)) {
                        // focus is on a link
                        fadeIn.call(document.activeElement);
                    } else {
                        fadeOut();
                    }
                }
            });
        });
    }
}

function init() {
    // create nav toggle button
    document.querySelector('.header__inner').insertAdjacentHTML(
        'beforeend',
        document.getElementById('nav-toggle-tpl').innerText.trim()
    );
    var navOpened = false;
    var desktopMode = false;
    var nav = document.getElementById('nav');
    var navToggle = document.getElementById('nav-toggle');
    initNav(navToggle);
    initNavBacks(nav);

    tcObserve('nav-mode-ctrl', {
        now: true,
        on: function () {
            desktopMode = true;
            navOpened = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', false);
        },
        off: function () {
            desktopMode = false;
            navToggle.setAttribute('aria-expanded', navOpened);
        }
    });

    // #nav link
    listen.call(document, 'click', 'a[href="#nav"]', function (e) {
        e.preventDefault();
        if (desktopMode || navToggle.getAttribute('aria-expanded') === 'true') {
            focus.call(nav, true);
        } else {
            navToggle.focus();
            trigger.call(navToggle, 'click');
        }
    });

    // #main link
    var main = document.getElementById('main');
    listen.call(document, 'click', 'a[href="#main"]', function (e) {
        e.preventDefault();
        // scroll is done smoothly by smooth-scroll
        focus.call(main, true);
    });

    // #search link
    var search = document.getElementById('search');
    listen.call(document, 'click', 'a[href="#search"]', function (e) {
        e.preventDefault();
        var searchInput = search.querySelector('input[name="s"]');
        if (desktopMode || navToggle.getAttribute('aria-expanded') === 'true') {
            searchInput.focus();
        } else {
            navToggle.addEventListener('nav-opened', function focusSearchInput() {
                searchInput.focus();
                navToggle.removeEventListener('nav-opened', focusSearchInput);
            });
            trigger.call(navToggle, 'click');
        }
    });

    initSmoothScroll();

    // init reveal with load-more
    listen.call(document, 'moreloaded', '.list-real,.list-search', function (e) {
        setTimeout(function () {
            sscroll.scrollTo(e.detail.item(0));
        }, 300);
        fadeIn.initNodes(Array.prototype.map.call(e.detail, function (item) {
            return item.querySelector('.fade-in');
        }));
    });

    // masonry and ajax load-more
    if (typeof jQuery === 'function') {
        if (typeof imagesLoaded === 'function') {
            Array.prototype.forEach.call(document.querySelectorAll('[data-masonry]'), function (list) {
                imagesLoaded(list).on('progress', function () {
                    jQuery(list).masonry('layout');
                });
            });
        }

        listen.call(document, 'moreloaded', '.masonry', function (e) {
            imagesLoaded(e.target).on('progress', function () {
                jQuery(e.target).masonry('layout');
            });
            var $list = jQuery(e.target);
            if (typeof $list.masonry === 'function') {
                $list.masonry('appended', e.detail);
            }
        });
    }

    body.classList.remove('loading');
}


if (!html.classList.contains('js-off')) {
    if (typeof fallback === 'object') {
        fallback.cancel();
    }
    if (!tcObserve('main-css-ctrl', {on: init, now: true})) {
        setTimeout(function () {
            requestAnimationFrame(init);
        }, 150);
    }
}
