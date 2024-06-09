(function($) {
    $.fn.moneyFormatter = function(options) {
        var settings = $.extend({
            currencySymbol: "₴",
            decimalSeparator: ".",
            thousandsSeparator: ",",
            spaceBetweenAmountAndSymbol: true,
            symbolAlign: "right"
        }, options);

        function formatMoney(amount) {
            var roundedAmount = Math.round(amount * 100) / 100;

            var parts = roundedAmount.toString().split(".");
            var integerPart = parts[0];
            var decimalPart = parts.length > 1 ? parts[1] : "00";

            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, settings.thousandsSeparator);

            var formattedAmount = integerPart + settings.decimalSeparator + decimalPart;

            if (settings.symbolAlign === "right") {
                formattedAmount = formattedAmount + (settings.spaceBetweenAmountAndSymbol ? " " : "") + settings.currencySymbol;
            } else {
                formattedAmount = settings.currencySymbol + (settings.spaceBetweenAmountAndSymbol ? " " : "") + formattedAmount;
            }

            return formattedAmount;
        }

        return this.each(function() {
            var $this = $(this);
            var amount = parseFloat($this.text());

            if (!isNaN(amount)) {
                $this.text(formatMoney(amount));
            }
        });
    };
})(jQuery);
