(function($) {
    $.fn.multipleImagePreview = function(options) {
        var settings = $.extend({
            maxImages: 5,
            imageContainerClass: 'image-preview-container',
            inputButtonClass: 'image-input-button',
            previewImageClass: 'preview-image',
            removeImageClass: 'remove-image'
        }, options);

        return this.each(function() {
            var $this = $(this);
            var imageCounter = 0;

            var $imageContainer = $('<div class="' + settings.imageContainerClass + '"></div>');
            var $inputButton = $('<input type="file" multiple accept="image/png, image/jpeg" class="' + settings.inputButtonClass + '">');

            $this.append($imageContainer);
            $this.append($inputButton);

            $inputButton.on('change', function() {
                var files = this.files;

                for (var i = 0; i < files.length; i++) {
                    if (imageCounter >= settings.maxImages) break;

                    var file = files[i];
                    if (!file.type.match('image/png') && !file.type.match('image/jpeg')) continue;

                    var reader = new FileReader();

                    reader.onload = function(event) {
                        if (imageCounter >= settings.maxImages) return;

                        var $previewImage = $('<div class="' + settings.previewImageClass + '"><img src="' + event.target.result + '" /></div>');
                        $imageContainer.append($previewImage);
                        imageCounter++;
                    };

                    reader.readAsDataURL(file);
                }
            });

            $imageContainer.on('click', '.' + settings.previewImageClass, function() {
                $(this).remove();
                imageCounter--;
            });
        });
    };
}(jQuery));
