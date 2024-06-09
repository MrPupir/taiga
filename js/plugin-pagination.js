(function($) {
    $.fn.pagination = function(options) {
        var settings = $.extend({
            currentPage: 1,
            maxPages: 1,
            limitPages: 5,
            onPageClick: function(page) {}
        }, options);
        
        return this.each(function() {
            var $container = $(this);
            var pageCount = settings.maxPages;
            var currentPage = settings.currentPage;
            var limitPages = settings.limitPages;

            if (pageCount < 2) {
                $container.html('');
                return;
            }

            var first = currentPage - Math.floor(limitPages / 2);
            if (first < 1) {
                first = 1;
            }

            var last = first + limitPages - 1;
            if (last > pageCount) {
                last = pageCount;
                first = last - limitPages + 1;
                if (first < 1) {
                    first = 1;
                }
            }

            var $pagination = $('<div class="pagination"></div>');

            if (first > 1) {
                $pagination.append(createPageLink(1));
            }

            if (first > 2) {
                $pagination.append(createPageLink(first - 1, '...'));
            }

            for (var i = first; i <= last; i++) {
                $pagination.append(createPageLink(i, i, i === currentPage));
            }

            if (last < pageCount - 1) {
                $pagination.append(createPageLink(last + 1, '...'));
            }

            if (last < pageCount) {
                $pagination.append(createPageLink(pageCount));
            }

            $container.html($pagination);

            function createPageLink(page, text, isActive) {
                var $link = $('<a href="#"></a>')
                    .text(text || page)
                    .data('page', page)
                    .on('click', function(e) {
                        e.preventDefault();
                        settings.onPageClick($(this).data('page'));
                    });
                
                if (isActive) {
                    $link.addClass('active');
                }

                return $link;
            }
        });
    };
}(jQuery));
