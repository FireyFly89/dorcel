(function ($) {
    $(document).ready(function () {
        var $gallery_image_holder_empty = $('.gallery-image-holder').eq(0);

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '#actress_gallery .add-gallery-image', function (e) {
                e.preventDefault();
                var $button = $(this);

                wp.media.editor.send.attachment = function (props, attachment) {
                    set_image(attachment.id);
                };

                wp.media.editor.open($button);
                return false;
            });
        }

        function set_image(field_value) {
            if (!field_value) {
                return;
            }

            $.ajax({
                type: "post",
                dataType: "json",
                url: admin_vars.ajax_url,
                data: {
                    action: 'media_src_by_id',
                    media_field_id: field_value,
                    security_nonce: admin_vars.security_nonce,
                },
            }).done(function (src) {
                if (src) {
                    var $clone_holder = $gallery_image_holder_empty.clone();
                    $clone_holder.removeClass('no-image');
                    $clone_holder.find('input').val(field_value);
                    $clone_holder.find('img').attr('src', src);
                    $clone_holder.insertBefore('#actress_gallery .button-wrapper');
                }
            })
        }

        $(document).on('click', '.gallery-image-delete', function () {
            $(this).closest('.gallery-image-holder').remove();
        });

        var $tag_connection_combobox = $('select#tag_connection');

        if (typeof $tag_connection_combobox !== 'undefined' && $tag_connection_combobox.length) {
            $tag_connection_combobox.select2({
                tags: true,
                tokenSeparators: [',', ' '],
            });

            $tag_connection_combobox.on('select2:unselect', function (e) {
                $('select#tag_connection option[value="' + e.params.data.text + '"').remove();
            });
        }
    });
})(jQuery);
