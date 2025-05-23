jQuery(function () {
    const $ = jQuery;

    $(document).on('click', '.yandex-taxi-delivery__warehouse_delete_js', function (event) {
        event.preventDefault();
        let id = $(this).data('warehouseId');

        let message = translations.warehouse_delete_confirm;
        message = message.replace('%1$s', id);

        if (confirm(message)) {
            $.post(
                '/wp-admin/admin-post.php?action=yandex-go-delivery/warehouses/delete',
                {"id": id})
                .done(function (json, status) {
                    window.location.reload();
                })
                .fail(function (response, status) {
                    alert(translations.server_error);
                });
        }
    });
});
