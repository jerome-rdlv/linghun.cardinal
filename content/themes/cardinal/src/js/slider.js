/* global jQuery */

(function ($) {
    if (typeof $ === 'function' && typeof $.fn.slick === 'function') {
        $('.slider--auto').each(function () {
            var $slider = $(this);
            $slider
                .slick({
                    adaptiveHeight: true,
                    arrows: false,
                    autoplay: true,
                    dots: true,
                    draggable: true,
                })
                .on('swipe click', function () {
                    $slider.slick('slickPause');
                });
        });
    }
})(jQuery);