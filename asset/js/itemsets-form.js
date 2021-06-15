(function ($) {
    $(document).ready(function () {
        // TODO Make multiple assets form sortable.
        // TODO Use the removed base fieldset as a hidden base.
        $('#content').on('click', '.itemset-form-add', function () {
            var itemsets = $(this).closest('.itemsets-list');
            var current = $(this).closest('.itemset-data');
            var next = current.clone();
            var nextIndex = itemsets.attr('data-next-index');
            $(next)
                .attr('data-index', nextIndex)
                .find('.itemset-form-element input[type=hidden]').val('').end()
                .find('.itemset-form-element img.selected-itemset-image').attr('src', '').end()
                .find('.itemset-form-element .selected-itemset-name').html('').end()
                .find('.itemset-form-element .selected-itemset').hide().end();

            // Increment the index or each label and field.
            next
                .find('select').each(function() {
                    var name = $(this).attr('name');
                    var regex = /\[o:data\]\[itemsets\]\[\d+\]/gm;
                    var replace = '[o:data][itemsets][' + nextIndex + ']';
                    name = name.replace(regex, replace);
                    $(this)
                        .attr('id', name)
                        .attr('name', name);
                });

            next
                .find('.field-meta label').each(function() {
                    var name = $(this).attr('for');
                    var regex = /\[o:data\]\[itemsets\]\[\d+\]/gm;
                    var replace = '[o:data][itemsets][' + nextIndex + ']';
                    name = name.replace(regex, replace);
                    $(this)
                        .attr('for', name);
                });
            // Reset all values and content.
            next
                .find('.inputs input').val('').end()
                .find('.inputs textarea').html('');

            current.after(next);

            itemsets.attr('data-next-index', parseInt(nextIndex) + 1);
        });

        $('#content').on('click', '.itemset-form-remove', function () {
            $(this).closest('.itemset-data').remove();
        });
    });
})(jQuery);
