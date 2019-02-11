/* global tarteaucitron */
'use strict';

// add cookie management link
(function () {
    var menu = document.querySelector('.footer__menu ul:last-child');
    if (menu) {
        var item = document.createElement('li');
        menu.appendChild(item);
        item.setAttribute('class', item.previousElementSibling.getAttribute('class'));
        var link = document.createElement('a');
        link.appendChild(document.createTextNode('Gestion des cookies'));
        link.setAttribute('href', '#');
        item.appendChild(link);

        link.addEventListener('click', function (e) {
            e.preventDefault();
            tarteaucitron.userInterface.openPanel();
        });
    }
})();