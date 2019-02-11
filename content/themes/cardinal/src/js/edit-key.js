var editLink = document.querySelector('body.logged-in .edit-link a[href]');
if (editLink) {
    document.addEventListener('keypress', function (e) {
        if (e.target.matches('input, textarea, select, [contenteditable="true"]')) {
            return;
        }
        // https://caniuse.com/#search=keyCode
        // noinspection JSDeprecatedSymbols
        if (e.keyCode === 69) { // E (capital e)
            window.open(editLink.getAttribute('href'));
        }
    });
}