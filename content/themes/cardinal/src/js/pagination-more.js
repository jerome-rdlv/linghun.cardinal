'use strict';

import focus from './focus-element';
import * as sscroll from './smooth-scroll';
import {trigger} from './create-event';

function getOptions(list, pagination) {
    var options = Object.assign(
        {
            url: null,
            label: 'Load more',
            loadingLabel: 'Loading…',
            loadingText: 'Loading next articles.',
            loadingSuccess: 'Article loaded.'
        },
        JSON.parse(pagination.getAttribute('data-more-options')),
        JSON.parse(list.getAttribute('data-more-options'))
    );

    var errors = false;
    // for (var key in options) {
    //     if (options.hasOwnProperty(key)) {
    //         if (options[key] === null) {
    //             console.warn('load-more option ' + key + ' must be set.', list);
    //             errors = true;
    //         }
    //     }
    // }
    
    return errors ? null : options;
}

function initLoadMoreList(list) {
    var id = list.getAttribute('id');
    if (!id) {
        console.warn('load-more list needs a persistent id to work: ', list);
        return;
    }

    var pagination = document.querySelector(list.getAttribute('data-more-pagination'));
    if (!pagination) {
        console.warn('load-more pagination not found for: ', list);
        return;
    }

    Array.prototype.forEach.call(pagination.querySelectorAll('.pagination__title,.pagination__list'), function (child) {
        child.parentNode.removeChild(child);
    });

    var options = getOptions(list, pagination);
    if (!options) {
        return;
    }

    // add load more button
    var button = document.createElement('button');
    button.textContent = options.label;
    button.setAttribute('type', 'button');
    button.classList.add('pagination__more');
    button.classList.add('print-off');
    pagination.appendChild(button);
    pagination.classList.add('pagination--more-on');

    // add live region
    var live = document.createElement('div');
    live.classList.add('visually-hidden');
    live.classList.add('print-off');
    live.setAttribute('aria-live', 'assertive');
    live.setAttribute('role', 'alert');
    pagination.appendChild(live);
    
    pagination.style.display = '';

    button.addEventListener('click', function () {

        // prevent further clicks while loading
        if (button.classList.contains('loading')) {
            return;
        }

        button.classList.add('loading');
        button.textContent = options.loadingLabel;
        live.textContent = options.loadingText;

        // load more
        var xhr = new XMLHttpRequest();
        xhr.open('GET', options.url);
        xhr.onload = function (e) {
            // load response
            var fragment = document.createElement('div');
            fragment.insertAdjacentHTML('beforeend', e.target.response);

            var respList = fragment.querySelector('#' + id);
            var respPagination = fragment.querySelector(respList.getAttribute('data-more-pagination'));
            
            options = getOptions(respList, respPagination);

            // add new items
            var respItems = [];
            var responseItems = fragment.querySelectorAll('#' + id + ' > *');
            if (responseItems.length) {
                Array.prototype.forEach.call(responseItems, function (item) {
                    respItems.push(item);
                    list.appendChild(item);
                });

                // focus first item
                (function (firstItem) {
                    requestAnimationFrame(function () {
                        focus.call(firstItem, true);
                        sscroll.scrollTo(firstItem);
                    });
                })(responseItems[0]);
            }
            
            if (options.url) {
                button.textContent = options.label;
                button.classList.remove('loading');

                // update numbers
                pagination.querySelector('.pagination__end').textContent = respPagination.querySelector('.pagination__end').textContent;
                pagination.querySelector('.pagination__total').textContent = respPagination.querySelector('.pagination__total').textContent;
            }
            else {
                button.parentNode.removeChild(button);
            }

            live.textContent = options.loadingSuccess;

            // trigger success event
            trigger.call(list, 'moreloaded', respItems);
        };
        xhr.send();
    });
    button.removeAttribute('disabled');
}

// for each list
if (typeof XMLHttpRequest === 'function') {
    Array.prototype.forEach.call(document.querySelectorAll('[data-more-pagination]'), initLoadMoreList);
}