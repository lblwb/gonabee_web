<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\View\View;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

/** @var array $settings */
/** @var array $config */
/** @var string|null $message */

$version = WC_Yandex_Taxi_Delivery_Plugin_Version::get();
wp_enqueue_script( YGO_PLUGIN_ID . '-settings-js', YGO_PLUGIN_URL . '/assets/js/settings.js', [], $version, true );
wp_localize_script( YGO_PLUGIN_ID . '-settings-js', 'yandexGoSettingsValidationTranslate', [
		'empty_geotoken' => __( 'Токен API Геосервиса не введён.', 'yandex-go-delivery' ),
] );

$groups = [
		[
				'country',
		],
		[
				'token',
		],
		[
				'geocoder',
				'geocode_token',
		],
		[
				'inn',
				'vat',
		],
		[
				'enabled',
				'post_payment',
				'payment_methods',
				'payment_method_label',
				'auto_change_status',
				'assembly_delay_minutes',
		],
		[
				'discount_is_on',
				'discount_size',
				'discount_from_price',
		],
		[
				'use_order_price_for_free',
				'order_price_for_free',
		],
		[
				'fixed_price_is_on',
				'fixed_price',
				'price_markup',
		],
		[
				'bulk_send_to_delivery',
		],
		[
				'default_weight',
				'default_width',
				'default_length',
				'default_height',
		],
];

?>
<?php echo ( new View() )->buildHtml( __DIR__ . '/../tabs.php', WC_Yandex_Taxi_Delivery_Base_Controller::admin_tabs( YGO_PLUGIN_ID . '_settings' ) ); ?>
<h2><?php echo __( 'Настройки плагина', 'yandex-go-delivery' ); ?></h2>

<?php if ( ! empty( $message ) ): ?><?php echo $message ?><?php endif ?>

<form method="POST" class="yandex-taxi-delivery_setting_form">
	<div class="yandex-taxi-delivery_settings_grid">
		<?php foreach ( $groups as $group ): ?>
			<div class="yandex-taxi-delivery_setting_form__group">
				<?php foreach ( $group as $key ): $param = $config[ $key ]; ?>
					<?php if ( isset( $param['display'] ) && $param['display'] === false ) {
						continue;
					} ?>
					<div class="yandex-taxi-delivery_setting_form__param">
						<label class="<?php echo ( $param['type'] == 'checkbox' ) ? 'checkbox-label' : '' ?>"
						       for="<?php echo $key ?>"><?php echo $param['title']; ?></label>

						<?php if ( in_array( $param['type'], [ 'text', 'number' ] ) ): ?>
							<input id="<?php echo $key ?>" name="<?php echo $key ?>" type="<?php echo $param['type'] ?>"
							       value="<?php echo isset( $settings[ $key ] ) ? $settings[ $key ] : '' ?>"
									<?php echo ( isset( $param['disabled'] ) && $param['disabled'] === true ) ? 'disabled' : '' ?>
									<?php echo get_custom_attribute_html( $param ) ?>>
							<span class="error-message"></span>
						<?php endif ?>

						<?php if ( $param['type'] == 'decimal' ): ?>
							<input class="wc_input_decimal input-text regular-input"
							       id="<?php echo $key ?>" name="<?php echo $key ?>" type="text"
							       value="<?php echo isset( $settings[ $key ] ) ? esc_attr( wc_format_localized_decimal( $settings[ $key ] ) ) : '' ?>"
									<?php echo ( isset( $param['disabled'] ) && $param['disabled'] === true ) ? 'disabled' : '' ?>
									<?php echo get_custom_attribute_html( $param ) ?>>
						<?php endif ?>

						<?php if ( $param['type'] == 'checkbox' ): ?>
							<input id="<?php echo $key ?>" name="<?php echo $key ?>" type="<?php echo $param['type'] ?>"
							       value="1"
									<?php echo ( isset( $settings[ $key ] ) && $settings[ $key ] == 'yes' ) ? 'checked' : '' ?>
									<?php echo ( isset( $param['disabled'] ) && $param['disabled'] === true ) ? 'disabled' : '' ?>
									<?php echo get_custom_attribute_html( $param ) ?>>
						<?php endif ?>

						<?php if ( $param['type'] == 'select' ): ?>
							<select id="<?php echo $key ?>" name="<?php echo $key ?>"
									<?php echo ( isset( $param['disabled'] ) && $param['disabled'] === true ) ? 'disabled' : '' ?>>
								<?php foreach ( $param['options'] as $optionKey => $optionLabel ): ?>
									<option
											value="<?php echo $optionKey ?>" <?php echo ( isset( $settings[ $key ] ) && $settings[ $key ] == $optionKey ) ? 'selected' : '' ?>>
										<?php echo $optionLabel ?>
									</option>
								<?php endforeach ?>
							</select>
						<?php endif ?>

						<?php if ( $param['type'] == 'multiselect' ): ?>
							<select multiple id="<?php echo $key ?>" name="<?php echo $key ?>[]"
									<?php echo ( isset( $param['disabled'] ) && $param['disabled'] === true ) ? 'disabled' : '' ?>>
								<?php foreach ( $param['options'] as $option ): ?>
									<option
											value="<?php echo $option['value'] ?>" <?php echo isset( $settings[ $key ] ) && in_array( $option['value'], $settings[ $key ] ) ? 'selected' : '' ?>
											<?php echo ( isset( $option['disabled'] ) && $option['disabled'] === true ) ? 'disabled' : '' ?>>
										<?php echo $option['title'] ?>
									</option>
								<?php endforeach ?>
							</select>
						<?php endif ?>

						<?php if ( isset( $param['description'] ) ): ?>
							<div class="description"><span><?php echo $param['description'] ?></span></div>
						<?php endif ?>

						<?php if ( $key == 'token' ): ?>
							<?php if ( 'Chile' === $settings['country'] ) {
								echo "Para obtener el token, comuníquese con su administrador de Yango";
							} else {
								?>
								<a data-source='<?php echo json_encode( CountryRelatedDataHelper::getCabinetUrlSource() ) ?>'
								   class="button country-related-link" target="_blank">
									<?php echo __( 'Получить токен', 'yandex-go-delivery' ) ?>
								</a>
							<?php } ?>
							<a data-source='<?php echo json_encode( CountryRelatedDataHelper::getConnectUrlSource() ) ?>'
							   class="button country-related-link" target="_blank">
								<?php echo __( 'Подключить Яндекс Доставку', 'yandex-go-delivery' ) ?>
							</a>
							<div class="description">
								<span><?php echo __( 'Если у вас уже есть договор, повторное заключение не требуется, укажите в поле выше токен из текущего договора.', 'yandex-go-delivery' ) ?></span>
							</div>
						<?php endif ?>

						<?php if ( $key == 'geocode_token' ): ?>
							<?php $geocoder = $settings['geocoder'] ?? 'yandex'; ?>
							<?php if ( $geocoder == 'dadata' ) {
								$dadata_description_hidden = false;
							} else {
								$dadata_description_hidden = true;
							}
							?>
							<div class="description dadata-description"
							     style="display: <?php echo $dadata_description_hidden ? 'none' : 'inherit'; ?>;">
								<span><?php echo __( 'Формат: API_key::Secret_Key', 'yandex-go-delivery' ); ?></span>
							</div>
							<a data-source='<?php echo json_encode( CountryRelatedDataHelper::getGeocoderInstructionUrlSource( $geocoder ) ) ?>'
							   class="button country-related-link" target="_blank">
								<?php echo __( 'Как получить токен', 'yandex-go-delivery' ) ?>
							</a>
							<div class="description">
								<a data-source='<?php echo json_encode( CountryRelatedDataHelper::getGeocoderCabinetUrlSource( $geocoder ) ) ?>'
								   class="button country-related-link" target="_blank">
									<?php echo __( 'Перейти в личный кабинет Геосервиса', 'yandex-go-delivery' ) ?></a>
							</div>
						<?php endif ?>

					</div>
				<?php endforeach ?>
			</div>
		<?php endforeach ?>
	</div>
	<button class="button button-primary"
	        type="submit"><?php echo __( 'Сохранить настройки', 'yandex-go-delivery' ) ?></button>
</form>

<?php echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_support_contact.php' ) ?>
