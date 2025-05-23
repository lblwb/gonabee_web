jQuery(function () {
	const $ = jQuery;

	let price = null;

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
			updatePrice(result.price, result.due, result.tariff);
			$('#yandex-taxi-delivery-confirm-button').show();

			if (price === result.price) {
				hasChanges = false;
				$('#yandex-taxi-delivery_claim_form').submit();
				return;
			}

			let oldPrice = price;
			price = result.price;

			let message = translations.price_changed;
			message.replace('%1$s', oldPrice);
			message.replace('%2$s', price);
			if (confirm(message)) {
				hasChanges = false;
				$('#yandex-taxi-delivery_claim_form').submit();
			}
		});
	});

	$(document).on('click', '#yandex-taxi-add-point', function (event) {
		event.preventDefault();

		let orderAlreadyExists = false;
		let orderIdContainer = $('#add_order_id');
		let orderId = parseInt(orderIdContainer.val());

		if (!orderId) {
			alert(translations.order_number_required);
			return;
		}

		let container = $('.yandex-taxi-delivery_form__route_point_container');
		let routePoints = container.find('.yandex-taxi-delivery_form__route_point');

		routePoints.each((function () {
			let pointOrderId = parseInt($(this).find('.yandex-taxi-delivery_form__route_point__order_id').val());

			if (pointOrderId === orderId) {
				let message = translations.order_already_in_claim;
				message.replace('%1$s', orderId);
				alert(message);
				orderAlreadyExists = true;
			}
		}));

		if (orderAlreadyExists) {
			return;
		}

		addRoutePoint(orderId);
		orderIdContainer.val('');
	});

	$(document).on('click', '#yandex-taxi-add-fake-point', function (event) {
		event.preventDefault();

		addRoutePoint('fake');
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

	$(document).on('change', '[name="warehouse[due][is_on]"]', function () {
		if ($(this).is(":checked")) {
			$('[name="warehouse[due][date]"]').attr('disabled', false);
			$('[name="warehouse[due][hour]"]').attr('disabled', false);
			$('[name="warehouse[due][minute]"]').attr('disabled', false);
		} else {
			$('[name="warehouse[due][date]"]').attr('disabled', true);
			$('[name="warehouse[due][hour]"]').attr('disabled', true);
			$('[name="warehouse[due][minute]"]').attr('disabled', true);
		}
	});

	$(document).on('change', '#warehouse', function (event) {
		var json = $(this).find(':selected').data('json');

		$('[name="warehouse[address]"]').val(json.address).trigger('change');
		$('[name="warehouse[comment]"]').val(json.comment);
		$('[name="warehouse[flat]"]').val(json.flat);
		$('[name="warehouse[floor]"]').val(json.floor);
		$('[name="warehouse[porch]"]').val(json.porch);
		$('[name="warehouse[name]"]').val(json.contactName);
		$('[name="warehouse[email]"]').val(json.contactEmail);
		$('[name="warehouse[phone]"]').val(json.contactPhone);

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

	$(document).on('click', '.js_yandex-taxi-delivery_form__delete-route-point', function (event) {
		event.preventDefault();
		if (confirm(translations.delete_point)) {
			$(this).closest('.yandex-taxi-delivery_settings_grid').remove();
			resetRoutePointNumbersAndSum();
			hasChanges = true;
		}
	});

	$(document).on('click', '.js_yandex-taxi-delivery_form__move-up-route-point', function (event) {
		event.preventDefault();
		let container = $(this).closest('.yandex-taxi-delivery_settings_grid');

		if (container.prev().length > 0) {
			container.prev().insertAfter(container);
			resetRoutePointNumbersAndSum();
			hasChanges = true;
		}
	});

	$(document).on('click', '.js_yandex-taxi-delivery_form__move-down-route-point', function (event) {
		event.preventDefault();
		let container = $(this).closest('.yandex-taxi-delivery_settings_grid');

		if (container.next().length > 0) {
			container.next().insertBefore(container);
			resetRoutePointNumbersAndSum();
			hasChanges = true;
		}
	});

	let tariffLabels;

	$('#warehouse_coordinate').on('change', function (event) {
		let coordinate = $(this).val();

		if (coordinate) {
			sendPost('get-tariffs', {coordinate: coordinate}).then(function (result) {
				const tariffs = $(result.html);
				$('#tariffs_container').html(result.html);

				if (tariffs.hasClass('error')) {
					$('#yandex-taxi-delivery-calculate').hide();
				} else {
					$('#yandex-taxi-delivery-calculate').show();
					tariffLabels = result.labels;
				}
			});
		}
	});

	if ('Chile' === yandexSettings.country) {
		jQuery('#warehouse_coordinate').trigger('change');
	}

	function addRoutePoint(orderId) {
		let container = $('.yandex-taxi-delivery_form__route_point_container');
		let routePoints = container.find('.yandex-taxi-delivery_form__route_point');

		let pointCount = routePoints.length;

		sendPost(
			'get-order-route-point',
			{
				order_id: orderId,
				point_count: pointCount
			}
		).then(
			function (result) {
				container.append(result.html);
				disableSubmit('');

				$(document).trigger('yandex-taxi-delivery:initMapSuggestion', [
					container.find('.js_yandex-taxi-delivery_form__param_address:last'),
				]);

				$(document).trigger('yandex-taxi-delivery:initPhone', [
					container.find('input[type="tel"]:last'),
				]);

				hasChanges = true;
			});
	}

	function resetRoutePointNumbersAndSum() {
		let number = 2;

		$('.yandex-taxi-delivery_form__route_point_container')
			.find('.yandex-taxi-delivery__route_point_number').each((function () {
			$(this).html(number);
			number++;
		}));

		disableSubmit('');
	}

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

		let data = $('#yandex-taxi-delivery_claim_form').serializeArray();
		sendPost('create-claim', data).then(function (result) {
			// save key for calculations
			$('#claim-key').val(result.key);

			let requestCount = 1;
			let timerId = setInterval(function () {
				sendPost('get-claim', {key: result.key}).then(function (result) {
					requestCount++;
					if (result.calculated === false) {
						if (requestCount > 10) {
							clearInterval(timerId);
							updateCalculateHTML(translations.calculation_error);
						}
						return;
					}

					clearInterval(timerId);

					if (result.hasOwnProperty('warning')) {
						if (!confirm(`${result.warning} ${translations.continue}`)) {
							disableSubmit('');
							return;
						}
					}

					price = result.price;
					updatePrice(result.price, result.due, result.tariff);

					$('#yandex-taxi-delivery-confirm-button').show();
				}).catch(function () {
					clearInterval(timerId);
				});
			}, 3000); // 3 seconds wait
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
