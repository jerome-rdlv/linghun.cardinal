(function(window) {

    var transitions = {
            'transition': 'transitionend',
            'WebkitTransition': 'webkitTransitionEnd',
            'MozTransition': 'transitionend',
            'OTransition': 'otransitionend'
        },
        elem = document.createElement('div');

    for(var t in transitions){
        if(typeof elem.style[t] !== 'undefined'){
            window.transitionEnd = transitions[t];
            break;
        }
    }

    return window.transitionEnd;

})(window);