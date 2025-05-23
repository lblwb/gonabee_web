<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use WCYandexTaxiDeliveryPlugin\View\View;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

/** @var Warehouse $warehouse */
/** @var string $geocodeToken */
/** @var array $hours */
/** @var bool $isDefault */
/** @var string|null $message */

wp_enqueue_script( 'jquery' );

$version  = WC_Yandex_Taxi_Delivery_Plugin_Version::get();
$locale   = get_locale();
$settings = ygo_get_settings();

// phones
wp_enqueue_script( 'phone-intl-lib-js', YGO_PLUGIN_URL . '/assets/lib/intlTelInput/js/intlTelInput-jquery.min.js', [], $version );
wp_enqueue_style( 'phone-intl-lib-css', YGO_PLUGIN_URL . '/assets/lib/intlTelInput/css/intlTelInput.min.css', [], $version );

// js translations
echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_translations.php' );

if ( $settings['geocoder'] == 'google' ) {
	// google map
	wp_enqueue_script( YGO_PLUGIN_ID . '-admin-map-js', YGO_PLUGIN_URL . '/assets/js/gmap.js', [ 'jquery' ], $version );
	wp_enqueue_script( YGO_PLUGIN_ID . '-map-js', "https://maps.googleapis.com/maps/api/js?key={$geocodeToken}", [], $version );
} else {
	// yandex map
	wp_enqueue_script( YGO_PLUGIN_ID . '-map-js', "https://api-maps.yandex.ru/2.1/?lang={$locale}&apikey={$geocodeToken}", [], $version );
	wp_enqueue_script( YGO_PLUGIN_ID . '-admin-map-js', YGO_PLUGIN_URL . '/assets/js/map.js', [ 'jquery' ], $version );
}
wp_enqueue_script( YGO_PLUGIN_ID . '-form-validation', YGO_PLUGIN_URL . '/assets/js/validation.js', [], $version );
wp_enqueue_script( YGO_PLUGIN_ID . '-edit-warehouse', YGO_PLUGIN_URL . '/assets/js/edit-warehouse.js', [], $version );

?>
<div class="ygo_menu_tabs">
	<?php echo ( new View() )->buildHtml( __DIR__ . '/../tabs.php', WC_Yandex_Taxi_Delivery_Base_Controller::admin_tabs( YGO_PLUGIN_ID . '_warehouses' ) ); ?>
</div>

<?php if ( is_null( $warehouse->getId() ) ): ?>
    <h1><?php echo __( 'Создание склада', 'yandex-go-delivery' ) ?></h1>
<?php else: ?>
    <h1><?php echo __( 'Редактирование склада №', 'yandex-go-delivery' ) ?><?php echo $warehouse->getId() ?></h1>
<?php endif ?>

<?php if ( ! empty( $message ) ): ?><?php echo $message ?><?php endif ?>

<input type="hidden" name="setting_url" value="<?php echo AdminUrlHelper::getSettingsUrl() ?>">
<form method="POST" id="yandex-taxi-delivery__edit-warehouse_form" class="yandex-taxi-delivery__edit-warehouse_form">
    <div class="yandex-taxi-delivery_settings_grid">
        <div class="yandex-taxi-delivery_form__route_point yandex-taxi-delivery_setting_form__group">

            <div class="yandex-taxi-delivery_form__row">
                <div class="yandex-taxi-delivery_form__column_left">

                    <div class="yandex-taxi-delivery_map__group">
                        <div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
                            <label for="address"><?php echo __( 'Адрес', 'yandex-go-delivery' ) ?> *</label>
                            <input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_address"
                                   id="address"
                                   type="text"
                                   name="address"
                                   required
                                   value="<?php echo _wp_specialchars( $warehouse->getAddress() ) ?>">
                            <input type="hidden"
                                   id="coordinate"
                                   name="coordinate"
                                   class="js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_coordinate">
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="yandex-taxi-delivery_form__group row">
                        <div class="yandex-taxi-delivery_form__group_small">
                            <label for="flat"><?php echo __( 'Квартира', 'yandex-go-delivery' ) ?></label>
                            <input class="js_yandex-taxi-delivery_form__param" id="flat" name="flat" type="number"
                                   value="<?php echo _wp_specialchars( $warehouse->getFlat() ) ?>">
                        </div>

                        <div class="yandex-taxi-delivery_form__group_small">
                            <label for="porch"><?php echo __( 'Подъезд', 'yandex-go-delivery' ) ?></label>
                            <input class="js_yandex-taxi-delivery_form__param" id="porch" name="porch" type="text"
                                   value="<?php echo _wp_specialchars( $warehouse->getPorch() ) ?>">
                        </div>

                        <div class="yandex-taxi-delivery_form__group_small">
                            <label for="floor"><?php echo __( 'Этаж', 'yandex-go-delivery' ) ?></label>
                            <input class="js_yandex-taxi-delivery_form__param" id="floor" name="floor" type="number"
                                   value="<?php echo _wp_specialchars( $warehouse->getFloor() ) ?>">
                        </div>
                    </div>

                    <div class="yandex-taxi-delivery_form__group">
                        <label for="comment"><?php echo __( 'Комментарий', 'yandex-go-delivery' ) ?></label>
                        <textarea
                                class="yandex-taxi-delivery_form__group__input__textarea js_yandex-taxi-delivery_form__param"
                                id="comment"
                                name="comment"><?php echo _wp_specialchars( $warehouse->getComment() ) ?></textarea>
                    </div>

                    <div class="yandex-taxi-delivery_form__group row" style="margin-top: 5px;">
                        <label for="name"><?php echo __( 'Часы работы', 'yandex-go-delivery' ) ?> *</label>
                        <select id="start_time" name="start_time" class="js_yandex-taxi-delivery_form__param">
							<?php
							foreach ( $hours as $optionKey => $optionLabel ): ?>
                                <option value="<?php echo $optionKey ?>" <?php echo ( $warehouse->getStartTime() == $optionKey ) ? 'selected' : '' ?>>
									<?php echo $optionLabel ?>
                                </option>
							<?php endforeach ?>
                        </select>
                        <span>–</span>
                        <select id="end_time" name="end_time" class="js_yandex-taxi-delivery_form__param">
							<?php
							foreach ( $hours as $optionKey => $optionLabel ): ?>
                                <option value="<?php echo $optionKey ?>" <?php echo ( $warehouse->getEndTime() == $optionKey ) ? 'selected' : '' ?>>
									<?php echo $optionLabel ?>
                                </option>
							<?php endforeach ?>
                        </select>
                        <span class="error-message"></span>
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
                    <div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
                        <label for="name"><?php echo __( 'Имя', 'yandex-go-delivery' ) ?> *</label>
                        <input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
                               id="name" type="text" name="name" required
                               value="<?php echo _wp_specialchars( $warehouse->getContactName() ) ?>">
                        <span class="error-message"></span>
                    </div>

                    <div class="yandex-taxi-delivery_form__group row">
                        <div class="yandex-taxi-delivery_form__group_small js_yandex-taxi-delivery_param_container">
                            <label for="phone"><?php echo __( 'Номер телефона', 'yandex-go-delivery' ) ?> *</label>
                            <input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
                                   type="tel" id="phone" name="phone" required
                                   value="<?php echo _wp_specialchars( $warehouse->getContactPhone() ) ?>"
                            >
                            <span class="error-message"></span>
                        </div>

                        <div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
                            <label for="email">Email *</label>
                            <input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
                                   type="text" id="email" name="email" required
                                   value="<?php echo _wp_specialchars( $warehouse->getContactEmail() ) ?>"
                            >
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="yandex-taxi-delivery_form__group checkbox">
                        <input id="is_default" name="is_default" type="checkbox"
                               value="1" <?php echo $isDefault ? 'checked' : '' ?>>
                        <span class="error-message"></span>
                        <label for="is_default"><?php echo __( 'Использовать по умолчанию', 'yandex-go-delivery' ) ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="button button-primary" name="submit" type="submit"><?php echo __( 'Сохранить', 'yandex-go-delivery' ) ?></button>
</form>

<?php echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_support_contact.php' ) ?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        const intlTelInputUtilsScriptUrl = "<?php echo YGO_PLUGIN_URL . '/assets/lib/intlTelInput/js/utils.js' ?>";
        const intlTelInputUtilsCountry = "<?php echo CountryRelatedDataHelper::getPhoneCountry() ?>";

        $('input[type="tel"]').intlTelInput({
            initialCountry: intlTelInputUtilsCountry,
            formatOnDisplay: false,
            utilsScript: intlTelInputUtilsScriptUrl
        });
    });
</script>
