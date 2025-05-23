<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Wrappers\OrderWrapper;
use WCYandexTaxiDeliveryPlugin\View\View;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

/** @var string $geocodeToken */
/** @var Warehouse $warehouse */
/** @var array $warehousesList */
/** @var string $warehouseCommentPlaceholder */
/** @var OrderWrapper[] $orders */
/** @var boolean $isCountryCurrency */
/** @var boolean $ordersHaveAllowedPaymentMethods */
/** @var string $chile_coordinate */

$version  = WC_Yandex_Taxi_Delivery_Plugin_Version::get();
$locale   = get_locale();
$settings = ygo_get_settings();

// js translations
echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_translations.php' ); //@phpcs:ignore

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-datepicker' );

// datetime
wp_enqueue_style( 'jquery-ui-css', YGO_PLUGIN_URL . '/assets/lib/jquery-ui/jquery-ui.css', [ 'jquery' ], $version );

// phones
wp_enqueue_script( 'phone-intl-lib-js', YGO_PLUGIN_URL . '/assets/lib/intlTelInput/js/intlTelInput-jquery.min.js', [ 'jquery' ], $version, false );
wp_enqueue_style( 'phone-intl-lib-css', YGO_PLUGIN_URL . '/assets/lib/intlTelInput/css/intlTelInput.min.css', [], $version );

if ( 'Chile' !== $settings['country'] ) {
	if ( 'google' === $settings['geocoder'] ) {
		// google map
		wp_enqueue_script( YGO_PLUGIN_ID . '-admin-map-js', YGO_PLUGIN_URL . '/assets/js/gmap.js', [ 'jquery' ], $version, false );
		wp_enqueue_script( YGO_PLUGIN_ID . '-map-js', "https://maps.googleapis.com/maps/api/js?key={$geocodeToken}", [], $version, false );
	} else {
		// yandex map
		wp_enqueue_script( YGO_PLUGIN_ID . '-map-js', "https://api-maps.yandex.ru/2.1/?lang={$locale}&apikey={$geocodeToken}", [], $version, false );
		wp_enqueue_script( YGO_PLUGIN_ID . '-admin-map-js', YGO_PLUGIN_URL . '/assets/js/map.js', [ 'jquery' ], $version, false );
	}
}
wp_enqueue_script( YGO_PLUGIN_ID . '-form-validation', YGO_PLUGIN_URL . '/assets/js/validation.js', [ 'jquery' ], $version, false );
wp_enqueue_script( YGO_PLUGIN_ID . '-create-claim-js', YGO_PLUGIN_URL . '/assets/js/create-claim-bulk.js', [ 'jquery' ], $version, false );
?>

<h1><?php echo __( 'Отправка заказов в', 'yandex-go-delivery' ); ?>
	&nbsp;<?php echo Constants::getToPluginName(); ?></h1>

<input type="hidden" name="setting_url" value="<?php echo AdminUrlHelper::getSettingsUrl(); ?>">

<form method='POST' id="yandex-taxi-delivery_claim_form" class="yandex-taxi-delivery_form"
      action="<?php echo admin_url( 'admin-post.php?action=' . YGO_PLUGIN_ID . '/confirm' ); ?>">

	<input id="claim-key" name="key" type="hidden">
	<input id="use-price" name="use_price" type="hidden" value="<?php echo $isCountryCurrency ? 1 : 0; ?>">
	<input id="orders-have-allowed-payment" name="orders_have_allowed_payment" type="hidden"
	       value="<?php echo $ordersHaveAllowedPaymentMethods ? 1 : 0; ?>">
	<input type="hidden" id="is_bulk_claim" name="is_bulk_claim" value="1">

	<?php foreach ( $orders as $key => $order ): ?>
		<?php $order_id = $order->getId(); ?>
		<div class="yandex-taxi-delivery_claim_order">
			<hr>
			<h2 class="yandex-taxi-delivery_bulk_order_id">
				<span class="dashicons dashicons-cart"></span>
				<?php echo __( 'заказ №', 'yandex-go-delivery' ); ?> <?php echo $order_id; ?>
				<a class="js_yandex-taxi-delivery_form__delete-claim-order"><span
							class="dashicons dashicons-trash"></span></a>

			</h2>

			<div class="yandex-taxi-delivery_settings_grid">
				<div class="yandex-taxi-delivery_form__route_point yandex-taxi-delivery_setting_form__group"
				     style="<?php echo 'Chile' === $settings['country'] ? 'display: none;' : ''; ?>">

					<div class="yandex-taxi-delivery_form__route_heading">
						<h2 class="yandex-taxi-delivery_form__title"><?php echo __( 'Точка 1 (Склад)', 'yandex-go-delivery' ); ?></h2>
					</div>

					<div class="yandex-taxi-delivery_form__row">
						<?php
						// @codingStandardsIgnoreStart
						echo ( new View() )->buildHtml( __DIR__ . '/_warehouses_list.php', [
								'order_id'       => $order_id,
								'warehousesList' => $warehousesList,
								'selectedId'     => $warehouse->getId(),
						] );
						// @codingStandardsIgnoreEnd
						?>
					</div>
					<div class="yandex-taxi-delivery_form__row">
						<div class="yandex-taxi-delivery_form__column_left">
							<div class="yandex-taxi-delivery_map__group">
								<div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
									<label for="warehouse_<?php echo $order_id; ?>_address"><?php echo __( 'Адрес', 'yandex-go-delivery' ); ?>
										*</label>
									<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_address"
									       id="warehouse_<?php echo $order_id; ?>_address"
									       type="text"
									       name="warehouse[<?php echo $order_id; ?>][address]"
									       required
									       value="<?php echo _wp_specialchars( $warehouse->getAddress() ); ?>">
									<input type="hidden"
									       id="warehouse_coordinate"
									       name="warehouse[<?php echo $order_id; ?>][coordinate]"
									       class="js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_coordinate warehouse_coordinate"
									       value="<?php echo esc_html( $chile_coordinate ?? '' ); ?>">
									<span class="error-message"></span>
								</div>
							</div>

							<div class="yandex-taxi-delivery_form__group row">
								<div class="yandex-taxi-delivery_form__group_small">

									<label for="warehouse_<?php echo $order_id; ?>_flat"><?php echo __( 'Квартира', 'yandex-go-delivery' ); ?></label>
									<input class="js_yandex-taxi-delivery_form__param"
									       id="warehouse_<?php echo $order_id; ?>_flat"
									       name="warehouse[<?php echo $order_id; ?>][flat]"
									       type="number"
									       value="<?php echo _wp_specialchars( $warehouse->getFlat() ); ?>">
								</div>

								<div class="yandex-taxi-delivery_form__group_small">
									<label for="warehouse_<?php echo $order_id; ?>_porch"><?php echo __( 'Подъезд', 'yandex-go-delivery' ); ?></label>
									<input class="js_yandex-taxi-delivery_form__param"
									       id="warehouse_<?php echo $order_id; ?>_porch"
									       name="warehouse[<?php echo $order_id; ?>][porch]"
									       type="text"
									       value="<?php echo _wp_specialchars( $warehouse->getPorch() ); ?>">
								</div>

								<div class="yandex-taxi-delivery_form__group_small">
									<label for="warehouse_<?php echo $order_id; ?>_floor"><?php echo __( 'Этаж', 'yandex-go-delivery' ); ?></label>
									<input class="js_yandex-taxi-delivery_form__param"
									       id="warehouse_<?php echo $order_id; ?>_floor"
									       name="warehouse[<?php echo $order_id; ?>][floor]"
									       type="number"
									       value="<?php echo _wp_specialchars( $warehouse->getFloor() ); ?>">
								</div>
							</div>

							<div class="yandex-taxi-delivery_form__group">
								<label for="warehouse_<?php echo $order_id; ?>_comment"><?php echo __( 'Комментарий', 'yandex-go-delivery' ); ?></label>
								<textarea
										class="yandex-taxi-delivery_form__group__input__textarea js_yandex-taxi-delivery_form__param"
										id="warehouse_<?php echo $order_id; ?>_comment"
										name="warehouse[<?php echo $order_id; ?>][comment]"><?php echo empty( $warehouse->getComment() ) ? $warehouseCommentPlaceholder : _wp_specialchars( $warehouse->getComment() ); ?></textarea>
							</div>
						</div>
						<div class="yandex-taxi-delivery_form__column_right">
							<div class="yandex-taxi-delivery_form__group row">
								<div class="yandex-taxi-delivery_map"></div>
								<div class="yandex-taxi-delivery_address-details"></div>
							</div>
						</div>
					</div>

					<div class="divider"></div>

					<div class="yandex-taxi-delivery_form__row">
						<div class="yandex-taxi-delivery_form__column_left">
							<h3 class="yandex-taxi-delivery_form__contact_heading"><?php echo __( 'Контакт отправителя', 'yandex-go-delivery' ); ?></h3>

							<div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
								<label for="warehouse_<?php echo $order_id; ?>_name"><?php echo __( 'Имя', 'yandex-go-delivery' ); ?>
									*</label>
								<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
								       id="warehouse_<?php echo $order_id; ?>_name"
								       type="text"
								       name="warehouse[<?php echo $order_id; ?>][name]"
								       required
								       value="<?php echo _wp_specialchars( $warehouse->getContactName() ); ?>">
								<span class="error-message"></span>
							</div>

							<div class="yandex-taxi-delivery_form__group row">
								<div class="yandex-taxi-delivery_form__group_small js_yandex-taxi-delivery_param_container">
									<label for="warehouse_<?php echo $order_id; ?>_phone"><?php echo __( 'Номер телефона', 'yandex-go-delivery' ); ?>
										*</label>
									<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
									       type="tel"
									       id="warehouse_<?php echo $order_id; ?>_phone"
									       name="warehouse[<?php echo $order_id; ?>][phone]"
									       required
									       value="<?php echo _wp_specialchars( $warehouse->getContactPhone() ); ?>"
									>
									<span class="error-message"></span>
								</div>

								<div class="yandex-taxi-delivery_form__group_small js_yandex-taxi-delivery_param_container">
									<label for="warehouse_<?php echo $order_id; ?>_email">Email *</label>
									<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
									       type="text"
									       id="warehouse_<?php echo $order_id; ?>_email"
									       name="warehouse[<?php echo $order_id; ?>][email]"
									       required
									       value="<?php echo _wp_specialchars( $warehouse->getContactEmail() ); ?>"
									>
									<span class="error-message"></span>
								</div>
							</div>

							<!-- Временно отличили подачу машины ко времени к режиме 1к1
						<div class="yandex-taxi-delivery_form__group checkbox">
							<input id="due_is_on_<?php echo $order_id; ?>" type="checkbox" class="due_is_on"
							       name="warehouse[<?php echo $order_id; ?>][due][is_on]"
							       data-order_id="<?php echo $order_id; ?>">
							<label for="due_is_on_<?php echo $order_id; ?>"><?php echo __( 'Забрать заказ в конкретное время', 'yandex-go-delivery' ); ?></label>
						</div>
						<div class="yandex-taxi-delivery_form__group">
							<label><?php echo __( 'Время подачи машины на склад', 'yandex-go-delivery' ); ?></label>

							<div class="yandex-taxi-delivery_form__datetime">

								<?php $date = current_time( 'mysql' ); ?>
								<input type="text" class="date-picker js_yandex-taxi-delivery_form__param"
								       name="warehouse[<?php echo $order_id; ?>][due][date]"
								       disabled
								       maxlength="10"
								       value="<?php echo esc_attr( date_i18n( 'Y-m-d', strtotime( $date ) ) ); ?>"
								       pattern="<?php echo esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ); ?>"/>
								@
								&lrm;
								<input type="number" class="hour js_yandex-taxi-delivery_form__param"
								       disabled
								       placeholder="<?php esc_attr_e( 'h', 'woocommerce' ); ?>"
								       name="warehouse[<?php echo $order_id; ?>][due][hour]"
								       min="0" max="23" step="1"
								       value="<?php echo esc_attr( date_i18n( 'H', strtotime( $date ) ) ); ?>"
								       pattern="([01]?[0-9]{1}|2[0-3]{1})"/>:
								<input type="number" class="minute js_yandex-taxi-delivery_form__param"
								       disabled
								       placeholder="<?php esc_attr_e( 'm', 'woocommerce' ); ?>"
								       name="warehouse[<?php echo $order_id; ?>][due][minute]" min="0" max="59"
								       step="1"
								       value="<?php echo esc_attr( date_i18n( 'i', strtotime( $date ) ) ); ?>"
								       pattern="[0-5]{1}[0-9]{1}"/>
								<span>
                            <?php
							$zoneOffset = get_option( 'gmt_offset' );
							echo $zoneOffset > 0 ? "UTC+{$zoneOffset}" : "UTC{$zoneOffset}";
							?>
                                </span>
							</div>
						</div>
						-->

							<div class="yandex-taxi-delivery_form__group checkbox">
								<input class="js_yandex-taxi-delivery_form__param"
								       id="warehouse_sms_on_<?php echo $order_id; ?>"
								       type="checkbox"
								       name="warehouse[<?php echo $order_id; ?>][sms_on]">
								<label for="warehouse_sms_on_<?php echo $order_id; ?>"><?php echo __( 'Включить SMS подтверждение', 'yandex-go-delivery' ); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="yandex-taxi-delivery_form__route_point_container">
				<?php
				// @codingStandardsIgnoreStart
				echo ( new View() )->buildHtml( __DIR__ . '/_route_point.php', [
						'id'                 => $order_id,
						'address'            => apply_filters( 'ygo/create_claim/point/address', $order->getAddress(), $order ),
						'flat'               => apply_filters( 'ygo/create_claim/point/flat', $order->getFlat(), $order ),
						'porch'              => apply_filters( 'ygo/create_claim/point/porch', '', $order ),
						'floor'              => apply_filters( 'ygo/create_claim/point/floor', '', $order ),
						'fullName'           => apply_filters( 'ygo/create_claim/point/full_name', $order->getFullName(), $order ),
						'phone'              => apply_filters( 'ygo/create_claim/point/phone', $order->getPhone(), $order ),
						'email'              => apply_filters( 'ygo/create_claim/point/email', $order->getEmail(), $order ),
						'editUrl'            => $order->getEditUrl(),
						'isFake'             => false,
						'commentPlaceHolder' => apply_filters( 'ygo/create_claim/point/comment', $order->getCommentPlaceHolder(), $order ),
						'pointNumber'        => 2,
						'chile_coordinate'   => $chile_coordinate,
						'is_bulk'            => true,
				] );
				// @codingStandardsIgnoreEnd
				?>

				<div class="yandex-taxi-delivery_form__cost_sum price-container">

				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<div id="tariffs_container">
		<div class="yandex-taxi-delivery_settings_grid">
			<div class="yandex-taxi-delivery_form__container_actions yandex-taxi-delivery_setting_form__group">
				<div class="yandex-taxi-delivery_form__group tariff-container">
					<label class="tariff"><?php echo __( 'Тариф', 'yandex-go-delivery' ); ?></label>

				</div>
			</div>
		</div>
	</div>

	<div class="yandex-taxi-delivery_form__cost_sum">
		<span id='calculation-message'></span>
	</div>

	<button class="button"
	        id="yandex-taxi-delivery-calculate"><?php echo __( 'Рассчитать', 'yandex-go-delivery' ); ?></button>
	<button class="button button-primary" id="yandex-taxi-delivery-confirm-button" style="display: none" type="submit">
		<?php echo __( 'Подтвердить заявку', 'yandex-go-delivery' ); ?>
	</button>
</form>

<?php echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_support_contact.php' ); ?>

<script type="text/javascript">
	const intlTelInputUtilsScriptUrl = "<?php echo YGO_PLUGIN_URL . '/assets/lib/intlTelInput/js/utils.js'; ?>";
	const intlTelInputUtilsCountry = "<?php echo CountryRelatedDataHelper::getPhoneCountry(); ?>";

	jQuery(document).ready(function ($) {
		$('input[name*="warehouse[<?php echo $order_id; ?>][due][date]"], .datepicker').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: 0,
		});

		$('input[type="tel"]').intlTelInput({
			initialCountry: intlTelInputUtilsCountry,
			formatOnDisplay: false,
			utilsScript: intlTelInputUtilsScriptUrl
		});

		let message = '';

		if ($('#use-price').val() == 0) {
			message = "<?php echo sprintf( __( 'Мы доставляем товары со стоимостью в валюте %1$s. Поменяйте валюту ваших заказов или стоимость товаров не будет учтена при доставке.\n', 'yandex-go-delivery' ), CountryRelatedDataHelper::getCurrency() ); ?>";
		}

		if (message !== '') {
			message += " <?php echo __( 'Вы уверены, что хотите продолжить?', 'yandex-go-delivery' ); ?>";
			if (!confirm(message)) {
				window.location.href = "<?php echo AdminUrlHelper::getOrdersPageUrl(); ?>"
			}
		}

	});

	<?php if ( 'Chile' === $settings['country'] ) : ?>
	jQuery(window).load(function ($) {
		jQuery('#warehouse_coordinate').trigger('change');
	});
	<?php endif; ?>
</script>

