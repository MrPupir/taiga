(function ($) {
    $.fn.photoCarousel = function (options) {
        var settings = $.extend({
            images: [],
            modalSelector: '#carousel-modal',
            prevSelector: '.prev',
            nextSelector: '.next',
            imgSelector: '.carousel-img',
            contentSelector: '.modal-content'
        }, options);

        var $modal = $(settings.modalSelector);
        var $carouselImg = $modal.find(settings.imgSelector);
        var currentIndex;

        function showModal(index) {
            currentIndex = index;
            $carouselImg.attr('src', settings.images[currentIndex]);
            $modal.css('display', 'flex');
            $('body').css('overflow-y', 'hidden');
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % settings.images.length;
            $carouselImg.attr('src', settings.images[currentIndex]);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + settings.images.length) % settings.images.length;
            $carouselImg.attr('src', settings.images[currentIndex]);
        }

        this.on('click', function () {
            showModal(0);
        });

        $modal.on('click', function (e) {
            if ($(e.target).is($modal)) {
                $modal.hide();
                $('body').css('overflow-y', '');
            }
        });

        $modal.find(settings.nextSelector).on('click', function () {
            nextImage();
        });

        $modal.find(settings.prevSelector).on('click', function () {
            prevImage();
        });

        $(document).keydown(function (e) {
            if ($modal.is(':visible')) {
                if (e.key === "Escape") {
                    $modal.hide();
                    $('body').css('overflow-y', '');
                } else if (e.key === "ArrowRight") {
                    nextImage();
                } else if (e.key === "ArrowLeft") {
                    prevImage();
                }
            }
        });

        return this;
    };
}(jQuery));
