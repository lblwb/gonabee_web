jQuery(function () {
	const $ = jQuery;

	jQuery(document).ready(function ($) {

		const cartCheckbox = $('[name="enabled"]');

		handleCartEnabled(cartCheckbox);

		cartCheckbox.change(function () {
			handleCartEnabled($(this));
		});

		const useOrderPriceForFreeCheckbox = $('[name="use_order_price_for_free"]');

		handleUseOrderPriceForFreeCheckboxState(useOrderPriceForFreeCheckbox);

		useOrderPriceForFreeCheckbox.change(function () {
			handleUseOrderPriceForFreeCheckboxState($(this));
		});

		const fixedPriceIsOnCheckbox = $('[name="fixed_price_is_on"]');

		handleFixedPriceIsOnCheckboxState(fixedPriceIsOnCheckbox);

		fixedPriceIsOnCheckbox.change(function () {
			handleFixedPriceIsOnCheckboxState($(this));
		});

		const discountIsOnCheckbox = $('[name="discount_is_on"]');

		handleDiscountIsOnCheckboxState(discountIsOnCheckbox);

		discountIsOnCheckbox.change(function () {
			handleDiscountIsOnCheckboxState($(this));
		});

		function handleCartEnabled(checkbox) {
			switchInput(checkbox, $('[name="payment_methods[]"]'));
			switchInput(checkbox, $('[name="payment_method_label"]'));
			switchInput(checkbox, $('[name="use_order_price_for_free"]'));
			switchInput(checkbox, $('[name="order_price_for_free"]'));
			switchInput(checkbox, $('[name="fixed_price_is_on"]'));
			switchInput(checkbox, $('[name="fixed_price"]'));
			switchInput(checkbox, $('[name="price_markup"]'));
			switchInput(checkbox, $('[name="discount_is_on"]'));
			switchInput(checkbox, $('[name="discount_size"]'));
			switchInput(checkbox, $('[name="discount_from_price"]'));
		}

		function handleUseOrderPriceForFreeCheckboxState(checkbox) {
			switchInput(checkbox, $('[name="order_price_for_free"]'));
		}

		function handleFixedPriceIsOnCheckboxState(checkbox) {
			switchInput(checkbox, $('[name="fixed_price"]'));
		}

		function handleDiscountIsOnCheckboxState(checkbox) {
			const input1 = $('[name="discount_size"]');
			const input2 = $('[name="discount_from_price"]');

			switchInput(checkbox, input1);
			switchInput(checkbox, input2);
		}

		function switchInput(checkbox, input) {
			if (checkbox.prop('checked')) {
				input.prop('disabled', false);
			} else {
				input.prop('disabled', true);
			}
		}

		$('.country-related-link').click(function () {
			const link = $(this);
			const source = link.data('source');
			const country = $("select[name='country']").val();

			if (country in source) {
				link.attr("href", source[country]);
				return;
			}

			link.attr("href", source.default);
		});

		const ygo_inn = $('[name="inn"]');
		ygo_inn.on('keypress', function (evt) {
			let theEvent = evt || window.event;
			let key = theEvent.keyCode || theEvent.which;
			key = String.fromCharCode(key);
			let regex = /[0-9]|\./;
			if (!regex.test(key)) {
				theEvent.returnValue = false;
				if (theEvent.preventDefault) theEvent.preventDefault();
			}
		});

		const ygo_post_payment = $('[name="post_payment"]');
		ygo_post_payment.on('change', function (event) {
			let self = $(this);
			let payments = [
				$("#payment_methods option[value='ygo_card']"),
				$("#payment_methods option[value='ygo_cash']")
			];
			if (this.checked) {
				payments.forEach(function (item, i, arr) {
					item.removeAttr('disabled');
					item[0].selected = true;
				});
			} else {
				payments.forEach(function (item, i, arr) {
					item.attr('disabled', 'disabled');
					item[0].selected = false;
				});

			}
		});
		const ygo_geocoder = $('[name="geocoder"]');
		ygo_geocoder.on('change', function (event) {
			let self = $(this);
			var value = self.val();
			if (value === 'dadata') {
				$('.dadata-description').show();
			} else {
				$('.dadata-description').hide();
			}
		});

		$('#geocode_token').on('change', function (e) {
			if ($('.yandex-taxi-delivery_setting_form').find('select[name="country"]').val() !== 'Chile') {
				if ($(this).val() == '') {
					$(this).addClass('error');
					$(this).next('.error-message').html(yandexGoSettingsValidationTranslate.empty_geotoken);
				} else {
					$(this).removeClass('error');
					$(this).next('.error-message').html('');
				}
			}
		});

		$('.yandex-taxi-delivery_setting_form').on('submit', function (event) {
			if ($(this).find('select[name="country"]').val() !== 'Chile') {
				event.preventDefault();
				const ygo_geocoder = $('#geocode_token');
				if ('' == ygo_geocoder.val()) {
					ygo_geocoder.addClass('error');
					ygo_geocoder.next('.error-message').html(yandexGoSettingsValidationTranslate.empty_geotoken);

					window.scrollTo(0, 0);
				} else {
					this.submit();
				}
			}
		});

	});
});
