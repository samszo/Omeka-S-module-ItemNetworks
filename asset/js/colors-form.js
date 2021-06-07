(function ($) {
    $(document).ready(function () {
        // TODO Make multiple assets form sortable.
        // TODO Use the removed base fieldset as a hidden base.
        $('#content').on('click', '.color-form-add', function () {
            var colors = $(this).closest('.colors-list');
            var current = $(this).closest('.color-data');
            var next = current.clone();
            var nextIndex = colors.attr('data-next-index');
            $(next)
                .attr('data-index', nextIndex)
                .find('.color-form-element input[type=hidden]').val('').end()
                .find('.color-form-element img.selected-color-image').attr('src', '').end()
                .find('.color-form-element .selected-color-name').html('').end()
                .find('.color-form-element .selected-color').hide().end();

            // Increment the index or each label and field.
            next
                .find('.inputs input, .inputs textarea').each(function() {
                    var name = $(this).attr('name');
                    var regex = /\[o:data\]\[colors\]\[\d+\]/gm;
                    var replace = '[o:data][colors][' + nextIndex + ']';
                    name = name.replace(regex, replace);
                    $(this)
                        .attr('id', name)
                        .attr('name', name);
                });

            next
                .find('.field-meta label').each(function() {
                    var name = $(this).attr('for');
                    var regex = /\[o:data\]\[colors\]\[\d+\]/gm;
                    var replace = '[o:data][colors][' + nextIndex + ']';
                    name = name.replace(regex, replace);
                    $(this)
                        .attr('for', name);
                });
            // Reset all values and content.
            next
                .find('.inputs input').val('').end()
                .find('.inputs textarea').html('');

            current.after(next);

            colors.attr('data-next-index', parseInt(nextIndex) + 1);
        });

        $('#content').on('click', '.color-form-remove', function () {
            $(this).closest('.color-data').remove();
        });
    });
})(jQuery);
