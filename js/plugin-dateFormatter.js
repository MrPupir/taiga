(function ($) {
    $.fn.dateFormatter = function (options) {
        var settings = $.extend({
            format: 'dd/mm/yyyy',
            offset: 0,
            customDate: null
        }, options);

        var date;
        if (settings.customDate) {
            date = new Date(settings.customDate);
        } else {
            date = new Date();
            date.setDate(date.getDate() + settings.offset);
        }

        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        if (day < 10) {
            day = '0' + day;
        }
        if (month < 10) {
            month = '0' + month;
        }

        var formattedDate;
        switch (settings.format) {
            case 'dd/mm/yyyy':
                formattedDate = day + '/' + month + '/' + year;
                break;
            case 'mm/dd/yyyy':
                formattedDate = month + '/' + day + '/' + year;
                break;
            case 'yyyy/mm/dd':
                formattedDate = year + '/' + month + '/' + day;
                break;
            case 'yyyy/dd/mm':
                formattedDate = year + '/' + day + '/' + month;
                break;
            default:
                formattedDate = day + '/' + month + '/' + year;
                break;
        }

        return formattedDate;
    };
}(jQuery));
