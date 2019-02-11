(function (list, operamini) {
    list.remove('fonts-on');
    if (operamini) {
        list.add('light-on');
    } else {
        list.add('js-on');
        list.remove('js-off');
    }
})(
    document.documentElement.classList,
    Object.prototype.toString.call(window.operamini) === '[object OperaMini]' ||
    /Opera Mini/.test(navigator.userAgent)
);