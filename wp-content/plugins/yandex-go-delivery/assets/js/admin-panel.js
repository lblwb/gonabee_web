jQuery(function () {
	const $ = jQuery;

	let translations = {};

	sendPost('/wp-admin/admin-post.php?action=yandex-go-delivery/get-translations', {}, function (result) {
		translations = JSON.parse(JSON.stringify(result));
		addSendButton();
	});

	$(document).on('click', '.wc-action-button-cancel_yandex-go-delivery', function (event) {
		event.preventDefault();
		cancel_button = $(this);
		cancel_button.addClass('loader_yandex-go-delivery');
		sendPost($(this).attr('href'), {}, function (result) {
			if (!result.is_confirm) {
				alert(result.message);
				cancel_button.removeClass('loader_yandex-go-delivery');
				return;
			}

			if (confirm(result.message)) {
				sendPost(`/wp-admin/admin-post.php?action=yandex-go-delivery/cancel`,
					{
						order_id: result.id,
						version: result.version,
						cancel_status: result.cancel_status
					},
					function (result) {
						alert(result.message);
						location.reload();
					}
				);
			}
		});
	});

	function addSendButton() {
		if (!$('.post-type-shop_order').length) {
			return;
		}

		const postsFilter = $('#posts-filter');

		if (!postsFilter.length) {
			return;
		}

		const sendButton = $(`<button class="button yandex-taxi-delivery_send-button">${translations.send_to_button}</button>`);
		postsFilter.find('.tablenav.top .alignleft.actions:last').after(sendButton);

		sendButton.click(function (e) {
			e.preventDefault();

			const orderIds = getSelectedOrderIds();

			if (orderIds.length <= 0) {
				alert(translations.select_order);
				return;
			}

			window.location.href = yandexTaxiDeliverySendOrdersBaseUrl + '&' + $.param({'order_ids': orderIds});
		});
	}

	function getSelectedOrderIds() {
		let ids = [];

		$('#posts-filter').find('tbody input[type="checkbox"]:checked').each(function () {
			ids.push($(this).val());
		});

		return ids;
	}

	function sendPost(url, data, handler) {
		return $.post(
			url,
			data,
			function (json, status) {
				try {
					var result = JSON.parse(json);
				} catch (e) {
					alert('Error');
					return;
				}

				if (typeof result.error !== "undefined") {
					alert(result.error);
					return false;
				}

				handler(result);
				return true;
			});
	}
});
