(function ($) {
    $.fn.smoothScroll = function (options) {
        var settings = $.extend({
            duration: 800,
            easing: 'swing'
        }, options);

        return this.each(function () {
            $(this).on('click', function (event) {
                event.preventDefault();

                var target = $(this).attr('scroll');

                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, settings.duration, settings.easing);
            });
        });
    };
}(jQuery));
