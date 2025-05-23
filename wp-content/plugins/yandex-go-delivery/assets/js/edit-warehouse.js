jQuery(function () {
    const $ = jQuery;

    $(document).on('input', 'input[type="tel"]', function () {
        let input = $(this);
        const formattedNumber = intlTelInputUtils.formatNumber(
            input.val(),
            null,
            intlTelInputUtils.numberFormat.E164
        );

        input.val(formattedNumber);
    });

    $(document).on('submit', '#yandex-taxi-delivery__edit-warehouse_form', function (event) {
        if (!yandexTaxiDeliveryFormValidator.validateForm($(this), function () {})) {
            event.preventDefault();
            return false;
        } else {
            return true;
        }
    });

    $(document).on('change', '.js_yandex-taxi-delivery_form__param', function () {
        yandexTaxiDeliveryFormValidator.validateField($(this));
    });
});
