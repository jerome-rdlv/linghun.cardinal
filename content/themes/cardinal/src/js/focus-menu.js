'use strict';

document.addEventListener('focusin', function (e) {
    if (e.target && e.target.matches('.PopupMenu-toggle')) {
        showMenu(e.target);
    }
});
document.addEventListener('focusin', function (e) {
    if (e.target && e.target.matches('.PopupMenu-menu *')) {
        showMenu(e.target.closest('.PopupMenu-menu').previousElementSibling);
    }
});

function showMenu(button) {
    var menu = button.nextElementSibling;
    
    button.setAttribute('aria-expanded', true);
    button.addEventListener('focusout', hideMenu);
    menu.addEventListener('focusout', hideMenu);
    
    function hideMenu() {
        button.setAttribute('aria-expanded', false);
        button.removeEventListener('blur', hideMenu);
        menu.removeEventListener('focusout', hideMenu);
    }
}
