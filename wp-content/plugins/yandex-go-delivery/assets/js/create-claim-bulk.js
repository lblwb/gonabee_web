jQuery(function () {
	const $ = jQuery;

	let price = null;

	let warehouse_coordinate_change = false;

	let hasChanges = false;

	window.onbeforeunload = function (event) {
		if (hasChanges) {
			return '';
		}
	};

	$(document).on('input', 'input[type="tel"]', function () {
		let input = $(this);
		const formattedNumber = intlTelInputUtils.formatNumber(
			input.val(),
			null,
			intlTelInputUtils.numberFormat.E164
		);

		input.val(formattedNumber);
	});

	$(document).on('yandex-taxi-delivery:initPhone', function (event, phone) {
		phone.intlTelInput({
			initialCountry: intlTelInputUtilsCountry,
			formatOnDisplay: false,
			utilsScript: intlTelInputUtilsScriptUrl
		});
	});

	$(document).on('click', '#yandex-taxi-delivery-calculate', function (event) {
		event.preventDefault();
		calculate();
	});

	$(document).on('click', '#yandex-taxi-delivery-confirm-button', function (event) {
		event.preventDefault();

		if (!yandexTaxiDeliveryFormValidator.validateForm($(this).closest('form'), function () {
			disableSubmit(translations.claim_calculate_error);
		})) {
			return;
		}

		setUpdating();

		let data = $('#yandex-taxi-delivery_claim_form').serializeArray();
		sendPost('get-claim', data).then(function (result) {
			//updatePrice(result.price, result.due, result.tariff);
			$('#yandex-taxi-delivery-confirm-button').show();

			if (price === result.sum) {
				hasChanges = false;
				$('#yandex-taxi-delivery_claim_form').submit();
				return;
			}

			let oldPrice = price;
			price = result.sum;

			let message = translations.price_changed;
			message.replace('%1$s', oldPrice);
			message.replace('%2$s', price);
			if (confirm(message)) {
				hasChanges = false;
				$('#yandex-taxi-delivery_claim_form').submit();
			}
		});
	});

	$(document).on('change', '.js_yandex-taxi-delivery_form__param', function () {
		if (!$(this).attr("name").includes('coordinate')) {
			hasChanges = true;
		}

		yandexTaxiDeliveryFormValidator.validateField($(this));
		$('#yandex-taxi-delivery-confirm-button').hide();
	});

	$(document).on('input', '.js_yandex-taxi-delivery_form__param', function () {
		if (!$(this).attr("name").includes('coordinate')) {
			hasChanges = true;
		}
	});

	$(document).on('change', '.due_is_on', function (e) {
		e.preventDefault();

		let order_id = $(this).data('order_id');
		if ($(this).is(":checked")) {
			$('[name="warehouse[' + order_id + '][due][date]"]').attr('disabled', false);
			$('[name="warehouse[' + order_id + '][due][hour]"]').attr('disabled', false);
			$('[name="warehouse[' + order_id + '][due][minute]"]').attr('disabled', false);
		} else {
			$('[name="warehouse[' + order_id + '][due][date]"]').attr('disabled', true);
			$('[name="warehouse[' + order_id + '][due][hour]"]').attr('disabled', true);
			$('[name="warehouse[' + order_id + '][due][minute]"]').attr('disabled', true);
		}
	});

	$(document).on('change', '#warehouse', function (event) {
		let order_id = $(this).data('order_id');
		var json = $(this).find(':selected').data('json');

		$('[name="warehouse[' + order_id + '][address]"]').val(json.address).trigger('change');
		$('[name="warehouse[' + order_id + '][comment]"]').val(json.comment);
		$('[name="warehouse[' + order_id + '][flat]"]').val(json.flat ?? '');
		$('[name="warehouse[' + order_id + '][floor]"]').val(json.floor ?? '');
		$('[name="warehouse[' + order_id + '][porch]"]').val(json.porch ?? '');
		$('[name="warehouse[' + order_id + '][name]"]').val(json.contactName);
		$('[name="warehouse[' + order_id + '][email]"]').val(json.contactEmail);
		$('[name="warehouse[' + order_id + '][phone]"]').val(json.contactPhone);

		hasChanges = true;
	});

	$(document).on('change', "[name='tariff']", function (event) {
		$('.tariff-option-container').each((function () {
			const requirementToHide = $(this).find('.tariff-requirement');
			if (requirementToHide.length) {
				requirementToHide.css('display', 'none');
			}
		}));

		const requirementToShow = $(this).parent().parent().find('.tariff-requirement');

		if (requirementToShow.length) {
			requirementToShow.css('display', 'flex');
		}

		hasChanges = true;
		disableSubmit('');
	});

	$(document).on('change', '.tariff-requirement input', function () {
		hasChanges = true;
		disableSubmit('');
	});

	let tariffLabels;

	$(document).on('change', '#warehouse_coordinate', function (event) {
		if (warehouse_coordinate_change) {
			return;
		}
		warehouse_coordinate_change = true;
		var coordinates = $('.warehouse_coordinate').serializeArray();
		//let coordinate = $(this).val();

		if (Array.isArray(coordinates)) {
			sendPost('get-tariffs', {coordinates: coordinates, is_bulk_claim: true}).then(function (result) {
				const tariffs = $(result.html);
				$('#tariffs_container').html(result.html);

				if (tariffs.hasClass('error')) {
					$('#yandex-taxi-delivery-calculate').hide();
				} else {
					$('#yandex-taxi-delivery-calculate').show();
					tariffLabels = result.labels;
				}
			});
			warehouse_coordinate_change = false;
		}
	});

	$(document).on('click', '.js_yandex-taxi-delivery_form__delete-claim-order', function (event) {
		event.preventDefault();
		if (confirm(translations.delete_point)) {
			$(this).closest('.yandex-taxi-delivery_claim_order').remove();
			resetRoutePointNumbersAndSum();
			hasChanges = true;
		}
	});

	function setUpdating() {
		disableSubmit(translations.updating);
	}

	function calculate() {
		if (!yandexTaxiDeliveryFormValidator.validateForm($('.yandex-taxi-delivery_form'), function () {
			disableSubmit(translations.fix_validation_errors);
		})) {
			return;
		}

		setUpdating();
		$('#claim-key').val('');

		let data = $('#yandex-taxi-delivery_claim_form').serializeArray();
		sendPost('create-claim', data).then(function (result) {
			// save key for calculations

			let requestCount = 1;
			let items = [];
			if (Array.isArray(result)) {
				result.forEach(function (item, i, arr) {
					items[i] = item.key; //array of claim keys
				});

				$('#claim-key').val(items.join(','));

				let timerId = setInterval(function () {
				sendPost('get-claim', {key: items, is_bulk_claim: true})
					.then(function (data) {
						claims = data.claims;
						requestCount++;
						calculated = true;
						var due = '', tariff = '';
						if (Array.isArray(claims)) {
							claims.forEach(function (item, i, arr) {
								if (item.calculated === false) {
									if (requestCount > 10) {
										clearInterval(timerId);
										updateCalculateHTML(translations.calculation_error);
										calculated = false;
									}
									//updateCalculateHTML(translations.calculation_error);
									//calculated = false;
									//return;
								}

								clearInterval(timerId);

								if (item.hasOwnProperty('warning')) {
									if (!confirm(`${item.warning} ${translations.continue}`)) {
										disableSubmit('');
										return;
									}
								}
								due = item.due;
								tariff = item.tariff;

								$($('.price-container')[i]).html('<p>' + item.price + '</p>');
							});
							price = data.sum;
							if (calculated) updatePrice(data.sum_string, due, tariff);
						}

						$('#yandex-taxi-delivery-confirm-button').show();
					})
					.catch(function () {
						 clearInterval(timerId);
					});
				}, 3000); // 3 seconds wait
			}
		});
	}

	function updatePrice(price, due, tariff) {
		let label = tariffLabels.hasOwnProperty(tariff) ? tariffLabels[tariff] : tariff;
		updateCalculateHTML(`<p>${price}</p><p>${due}</p><p>${label}</p>`);
	}

	function updateCalculateHTML(message) {
		$('#calculation-message').html(message);
	}

	function disableSubmit(message) {
		updateCalculateHTML(message);
		$('#yandex-taxi-delivery-confirm-button').hide();
	}

	function sendPost(action, data) {
		return new Promise(function (resolve, reject) {
			$.post(
				`/wp-admin/admin-post.php?action=yandex-go-delivery/${action}`,
				data)
				.done(function (json, status) {
					try {
						var result = JSON.parse(json);
					} catch (e) {
						disableSubmit('');
						alert(translations.server_error);
						reject();
						return;
					}

					if (typeof result.error !== "undefined") {
						disableSubmit('');
						alert(`${translations.server_error}: ${result.error}`);
						reject();
						return;
					}

					resolve(result);
				})
				.fail(function (response, status) {
					disableSubmit('');
					alert(`${translations.server_error}: ${response.responseText}`);
					reject();
				});
		});
	}
});
