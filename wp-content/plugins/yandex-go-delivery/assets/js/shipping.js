jQuery(function () {
    jQuery(document).on('change', '[name="shipping_due"]', function () {
        jQuery(document.body).trigger('update_checkout');
    });
});
