(function (window) {
    'use strict';
    const $ = jQuery;

    function yandexTaxiDeliveryFormValidator() {
        var _yandexTaxiDeliveryFormValidator = {};

        'use strict';

        _yandexTaxiDeliveryFormValidator.validateForm = function (form, callbackOnError) {
            let result = true;

            form.find("input[required]").each(function () {
                result = result && validateSingleField($(this));
            });

            form.find('.js_yandex-taxi-delivery_form__param_coordinate').each(function () {
                if (!$(this).val()) {
                    $(this).prev('.js_yandex-taxi-delivery_form__param_address').trigger('change');
                    result = false;
                }
            });

            if (!result) {
                callbackOnError();
                scrollToErrorIfNeeded();
            }

            return result;
        };

        _yandexTaxiDeliveryFormValidator.validateField = function (field) {
            validateSingleField(field);
        };

        function validateSingleField(field) {
            if (!field.prop('required')) {
                return true;
            }

            if (field.val().length === 0) {
                addError(field, translations.filed_required);
                return false;
            }

            if (field.attr('type') === 'tel') {
                if (!field.intlTelInput("isValidNumber")) {
                    addError(field, translations.wrong_phone);
                    return false;
                }
            }

            if (field.hasClass('js_yandex-taxi-delivery_form__param_address')) {
                var coordinate = field.closest(".js_yandex-taxi-delivery_param_container").find(".js_yandex-taxi-delivery_form__param_coordinate");
                if (!coordinate.val()) {
                    field.addClass('error');
                    return false;
                }
            }

            removeError(field);

            return true;
        }

        function addError(field, text) {
            let errorMessage = field.closest(".js_yandex-taxi-delivery_param_container").find(".error-message");
            field.addClass('error');
            errorMessage.html(text);
        }

        function removeError(field) {
            let errorMessage = field.closest(".js_yandex-taxi-delivery_param_container").find(".error-message");
            field.removeClass('error');
            errorMessage.html('');
        }

        function scrollToErrorIfNeeded() {
            let errorDiv = $('input.error').first();

            if (errorDiv.length <= 0) {
                return;
            }

            $('html, body').animate({
                scrollTop: (errorDiv.offset().top - 40)
            }, 1000);
        }

        return _yandexTaxiDeliveryFormValidator;
    }

    if (typeof (window.yandexTaxiDeliveryFormValidator) === 'undefined') {
        window.yandexTaxiDeliveryFormValidator = yandexTaxiDeliveryFormValidator();
    }
})(window);
