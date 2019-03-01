/* global jQuery */
'use strict';

import focus from './focus-element';
import * as sscroll from './smooth-scroll';
import {trigger} from './create-event';

function initLoadMoreList(list) {
    var id = list.getAttribute('id');
    if (!id) {
        console.warn('load-more-list needs a persistent id to work: ', list);
        return;
    }

    var pagination = document.querySelector(list.getAttribute('data-more'));
    if (!pagination) {
        console.warn('load-more-list pagination not found for: ', list);
        return;
    }

    var button = pagination.querySelector('button[data-more-url]');
    var moreLabel = button.textContent;
    var loadingLabel = list.getAttribute('data-more-loading-label') || pagination.getAttribute('data-more-loading-label');
    var loadingText = list.getAttribute('data-more-loading-text') || pagination.getAttribute('data-more-loading-text');
    
    // add live region
    var live = document.createElement('div');
    live.classList.add('visually-hidden');
    live.classList.add('print-off');
    live.setAttribute('aria-live', 'assertive');
    live.setAttribute('role', 'alert');
    pagination.appendChild(live);
    
    button.addEventListener('click', function () {
        
        // prevent further clicks while loading
        if (button.classList.contains('loading')) {
            return;
        }
        
        live.textContent = loadingText;
        
        var url = button.getAttribute('data-more-url');
        
        button.classList.add('loading');
        button.textContent = loadingLabel;

        // load more
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onload = function (e) {
            // load response
            var fragment = document.createElement('div');
            fragment.insertAdjacentHTML('beforeend', e.target.response);

            // add new items
            var responseItems = fragment.querySelectorAll('#' + id + ' > *');
            if (responseItems.length) {

                // focus first item
                (function (firstItem) {
                    requestAnimationFrame(function () {
                        focus.call(firstItem, true);
                        sscroll.scrollTo(firstItem);
                    });
                })(responseItems[0]);

                Array.prototype.forEach.call(responseItems, function (item) {
                    list.appendChild(item);
                });
                
                if (typeof jQuery === 'function' && typeof jQuery(list).masonry === 'function') {
                    jQuery(list).masonry('appended', responseItems);
                }
            }

            var respList = fragment.querySelector('#' + id);
            var respPagination = fragment.querySelector(respList.getAttribute('data-more'));

            // update button
            url = respPagination.getAttribute('data-more-url');
            button.setAttribute('data-more-url', url);
            button.classList.remove('loading');
            if (!url) {
                button.parentNode.removeChild(button);
            }
            else {
                button.textContent = moreLabel;
            }

            // update numbers
            pagination.querySelector('.pagination__end').textContent = respPagination.querySelector('.pagination__end').textContent;
            pagination.querySelector('.pagination__total').textContent = respPagination.querySelector('.pagination__total').textContent;

            live.textContent = respList.getAttribute('data-more-loading-success') || respPagination.getAttribute('data-more-loading-success');
            
            // trigger success event
            trigger.call(list, 'more-loaded');
        };
        xhr.send();
    });
    button.removeAttribute('disabled');
}

// for each list
Array.prototype.forEach.call(document.querySelectorAll('[data-more]'), initLoadMoreList);