(function ($) {
    var $body = $('body');

    function handle_query_method() {
        var $query_method = $('.query-method');

        $body.on('change', '.query-method', function () {
            var $this = $(this);
            var $query_method_input = $this.nextAll('[id$="query_method_input"], [for$="query_method_input"]');
            var selected_value = $this.val();

            if ($query_method_input.length <= 0) {
                return;
            }

            handle_query_method_input($query_method_input, selected_value);
        });

        $.each($query_method, function (key, value) {
            $this = $(value);
            handle_query_method_input($this.nextAll('[id$="query_method_input"], [for$="query_method_input"]'), $this.val());
        });
    }

    handle_query_method();

    function handle_query_method_input($elems, selected) {
        var iterrator = 1;

        $.each(widget_vars.query_methods, function (key) {
            if (selected === key) {
                if (iterrator <= 1) {
                    $elems.addClass('hidden-widget-input');
                    return false;
                }

                $elems.removeClass('hidden-widget-input');
                return false;
            }

            iterrator++;
        });
    }

    $(document).ajaxComplete(function () {
        handle_query_method();
    });

    var $custom_image_selector = $('.select-custom-image');

    if ($custom_image_selector && $custom_image_selector.length > 0) {
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $body.on('click', '.select-custom-image', function (e) {
                e.preventDefault();
                var $button = $(this);

                wp.media.editor.send.attachment = function (props, attachment) {
                    $button.prev('.image-id-input').val(attachment.id);
                };

                wp.media.editor.open($button);
                enable_widget_save($button);
                return false;
            });
        }
    }

    $body.on('click', '.slide-toggler', function () {
        $(this).next('.slide-content').slideToggle(200);
    });

    $body.on('click', '.slide-delete', function () {
        delete_slide($(this).closest('.slide-wrapper'));
    });

    $body.on('click', '.slide-add', function (e) {
        e.preventDefault();
        var $slide_wrapper = $(this).prevAll('.slide-wrapper').first();
        var current_slide = $slide_wrapper.data('slide-num');
        var next_slide = current_slide + 1;
        var $slide_wrapper_clone = $slide_wrapper.clone();
        $slide_wrapper_clone.find('.slide-title span').text('');
        rewrite_slide_numbers($slide_wrapper_clone, next_slide);
        $slide_wrapper_clone.find('img').remove();
        $slide_wrapper_clone.css('display', 'none').insertBefore($(this)).fadeIn(200);
        enable_widget_save($slide_wrapper);
        $(".sortable-widget").sortable("refreshPositions");
    });

    function delete_slide($slide_elem) {
        $slide_elem.fadeOut(200, function () {
            $slide_elem.remove();
            reset_slide_numbers();
            enable_widget_save($slide_elem);
        });
    }

    function rewrite_slide_numbers($slide, slide_number, empty_values = true) {
        $slide.find('.slide-number').text('Slide ' + slide_number + ':');
        $slide.find('.slide-content label').each(function (key, val) {
            var $label = $(val);
            var next_label_id = $label.attr('for').slice(0, -1) + slide_number;
            $label.attr('for', next_label_id);
        });
        $slide.find('.slide-content input').each(function (key, val) {
            var $input = $(val);
            var input_id = $input.attr('id');

            if (input_id) {
                $input.attr('id', input_id.slice(0, -1) + slide_number);
            }

            $input.attr('name', $input.attr('name').slice(0, -2) + slide_number + "]");

            if (empty_values) {
                $input.attr('value', '');
            }
        });
        $slide.closest('.slide-wrapper').data('slide-num', slide_number);
    }

    function enable_widget_save($element) {
        $element.closest('form').find('.widget-control-save').prop('disabled', false).val('Save');
    }

    function reset_slide_numbers() {
        var $slides_wrappers = $('.slides-wrapper');

        if ($slides_wrappers.length > 0) {
            $slides_wrappers.each(function (slide_wrapper_key, slide_wrapper) {
                $(slide_wrapper).find('.slide-wrapper').each(function (key, val) {
                    var $elem = $(val);
                    rewrite_slide_numbers($elem, key + 1, false);
                    enable_widget_save($elem);
                });
            });
        }
    }

    $(document).ajaxSuccess(function (event, request, settings) {
        initialize_sortable_widgets();
    });

    function initialize_sortable_widgets() {
        var $sortable_widgets = $(".sortable-widget");

        if ($sortable_widgets.length <= 0) {
            return;
        }

        $sortable_widgets.sortable({
            forcePlaceholderSize: true,
            cursor: 'move',
            delay: 100,
            over: function () {
                removeIntent = false;
            },
            out: function () {
                removeIntent = true;
            },
            beforeStop: function (event, ui) {
                if (removeIntent === true) {
                    ui.item.hide();

                    if (confirm('Are you sure want to remove this slide?')) {
                        delete_slide(ui.item);
                        enable_widget_save($slide_elem);
                    } else {
                        ui.item.show();
                    }
                }
            },
            stop: function () {
                reset_slide_numbers();
            }
        });

        $sortable_widgets.disableSelection();
    }

    initialize_sortable_widgets();

    $('body').on('change', '.language-switcher', function () {
        var language_code = $(this).val();
        var $main_form = $(this).closest('form');
        var widget_id_base = $main_form.find('.id_base').val();
        var $widget_inside = $main_form.closest('.widget-inside');
        var widget_number_match = new RegExp(widget_id_base + '\\-([0-9]{1,})', 'i');
        var widget_number = parseInt($(this).attr('id').match(widget_number_match)[1]);

        $widget_inside.addClass('widget-ajax-action');

        $.ajax({
            type: "post",
            dataType: "json",
            url: widget_vars.ajax_url,
            data: {
                action: 'data_by_language',
                widget_name: widget_id_base,
                widget_number: widget_number,
                security_nonce: widget_vars.security_nonce,
            },
        }).done(function (data) {
            $widget_inside.removeClass('widget-ajax-action');

            // Selector for all usable fields except language switcher dropdown
            var $input_fields = $main_form.find('select:not(.language-switcher):not(.country-blacklist):not(#wpml-language), .image-id-input, input:not([type="hidden"]):not([type="submit"]), textarea');
            var slide_number = $input_fields.closest('.slide-wrapper').data('slide-num');

            // Changes the all the field's language code in their name parameter, and changes values that is being returned from ajax request
            $input_fields.each(function (key, val) {
                var $field = $(val);
                var new_name = $field.attr('name').replace(/\[[a-z]{2}\]/i, '[' + language_code + ']');
                var match_field_shortname = new RegExp(widget_id_base + '\\[' + widget_number + '\\]\\[([a-z0-9_-]{1,})\\]', 'i');
                var match_title_field = new RegExp(widget_id_base + '\\[' + widget_number + '\\]\\[title\\]', 'i');
                var multilang = new RegExp(widget_id_base + '\\[' + widget_number + '\\]\\[[a-z0-9_-]{1,}\\](\\[\\])?$', 'i');

                $field.attr('name', new_name);
                var field_name_match = $field.attr('name').match(match_field_shortname);

                if (field_name_match.length > 1 && !$field.attr('name').match(multilang)) {
                    var field_name = field_name_match[1];
                    var field_value = "";

                    if (typeof slide_number === 'number') {
                        if (typeof data[field_name][language_code] === 'string') {
                            field_value = data[field_name][language_code];
                        } else {
                            field_value = data[field_name][language_code][slide_number];
                        }
                        if ($field.attr('name').match(match_title_field)) {
                            $field.closest('.slide-wrapper').find('.slide-title span').text(field_value);
                        }
                    } else {
                        field_value = data[field_name][language_code];

                        if ($field.attr('name').match(match_title_field)) {
                            $field.closest('.widget').find('.in-widget-title').text(": " + field_value);
                        }
                    }

                    change_media_field_src($field, field_name, field_value);

                    if ($field.is('select')) {
                        refresh_select_field($field, field_value);
                    } else {
                        $field.val(field_value);
                    }
                }
            });
        }).fail(function (data) {
            alert(data.responseText);
        });

        function refresh_select_field($field, field_value) {
            if (field_value) {
                $field.find('option[value=' + field_value + ']').attr('selected', 'selected');
                $field.find('option[value=' + field_value + ']').prop('selected', true);
                $field.val(field_value);
                $field.trigger('change');
            } else {
                $field.find('option').first().attr("selected", "selected");
                $field.find('option').first().prop("selected", true);
                $field.val($field.find('option').first().val());
                $field.trigger('change');
            }
        }

        function change_media_field_src($field, field_name, field_value) {
            if (field_name.includes('media_')) {
                if (field_value > 0) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: widget_vars.ajax_url,
                        data: {
                            action: 'media_src_by_id',
                            media_field_id: field_value,
                            security_nonce: widget_vars.security_nonce,
                        },
                    }).done(function (src) {
                        var img_source = "";

                        if (src) {
                            img_source = src;
                        }

                        var $img_field = $field.prev('img');

                        if ($img_field.length > 0) {
                            $img_field.prop('src', img_source);
                        }
                    }).fail(function (data) {
                        alert(data.responseText);
                    });
                } else {
                    var $img_field = $field.prev('img');

                    if ($img_field.length > 0) {
                        $img_field.remove();
                    }
                }
            }
        }
    });
})(jQuery);
