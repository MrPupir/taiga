(function($) {
    $.fn.styleSelect = function() {
        return this.each(function() {
            var $select = $(this);

            checkEmpty($select);

            $select.on('change', function() {
                checkEmpty($(this));
            });
        });
    };

    function checkEmpty($select) {
        if ($select.val() === '') {
            $select.addClass('empty');
        } else {
            $select.removeClass('empty');
        }
    }
})(jQuery);
