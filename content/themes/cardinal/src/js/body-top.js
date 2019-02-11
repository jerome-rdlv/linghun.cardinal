(function () {
    // set loading class on bdy
    document.body.className += ' loading';

    // detect Opera Mini
    var operamini = Object.prototype.toString.call(window.operamini) === '[object OperaMini]' ||
        /Opera Mini/.test(navigator.userAgent);

    // set document flags
    var list = document.documentElement.classList;
    list.remove('fonts-on');
    if (operamini) {
        list.add('light-on');
    } else {
        list.add('js-on');
        list.remove('js-off');
    }
})();

var fallback = (function () {
    var jsOff = document.documentElement.classList.contains('js-off');
    var cancels = [];
    var timeoutId = setTimeout(function () {
        for (var i = 0; i < cancels.length; ++i) {
            cancels[i].call();
        }
    }, 3000);
    return {
        id: function () {
            return timeoutId;
        },
        add: function (init, cancel) {
            if (!jsOff) {
                init.call();
                cancels.unshift(cancel);
            }
        }
    };
})();

var print = (function () {

    var printing = false, beforeCallbacks = [], afterCallbacks = [];

    function execute(callbacks) {
        for (var i = 0; i < callbacks.length; ++i) {
            callbacks[i].call();
        }
    }
    function toggle(before) {
        if (before) {
            if (!printing) {
                printing = true;
                execute(beforeCallbacks);
            }
        }
        else {
            if (printing) {
                printing = false;
                execute(afterCallbacks);
            }
        }
    }

    window.addEventListener('beforeprint', function () {
        toggle(true);
    });

    window.addEventListener('afterprint', function () {
        toggle(false);
    });

    // noinspection JSDeprecatedSymbols
    window.matchMedia('print').addListener(function (mql) {
        toggle(mql.matches);
    });

    return {
        add: function (before, after) {
            beforeCallbacks.push(before);
            if (typeof after === 'function') {
                afterCallbacks.push(after);
            }
        }
    };
})();