<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;
use WCYandexTaxiDeliveryPlugin\View\View;

/** @var Warehouse[] $warehouses */
/** @var int|null $defaultWarehouseId */

// js translations
echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_translations.php' );

$version = WC_Yandex_Taxi_Delivery_Plugin_Version::get();
wp_enqueue_script( YGO_PLUGIN_ID . '-warehouses-js', YGO_PLUGIN_URL . '/assets/js/warehouses.js', [], $version );
?>
<div class="ygo_menu_tabs">
	<?php echo ( new View() )->buildHtml( __DIR__ . '/../tabs.php', WC_Yandex_Taxi_Delivery_Base_Controller::admin_tabs( YGO_PLUGIN_ID . '_warehouses' ) ); ?>
</div>
<h2><?php echo __( 'Управление складами', 'yandex-go-delivery' ) ?></h2>

<div class="ygo_delivery_warehouses_table">
    <table class="widefat striped yandex-taxi-delivery_warehouse-list">
        <thead>
        <tr>
            <th>ID</th>
            <th class="ygo_delivery_warehouses_item_mobile"><?php echo __( 'Адрес', 'yandex-go-delivery' ) ?></th>
            <th>Email</th>
            <th><?php echo __( 'Имя', 'yandex-go-delivery' ) ?></th>
            <th><?php echo __( 'Телефон', 'yandex-go-delivery' ) ?></th>
            <th><?php echo __( 'По умолчанию', 'yandex-go-delivery' ) ?></th>
        </tr>
        </thead>

		<?php if ( empty( $warehouses ) ): ?>
            <tr>
                <span><?php echo __( 'Пока нет ни одного склада', 'yandex-go-delivery' ) ?></span>
            </tr>
		<?php endif ?>

		<?php foreach ( $warehouses as $warehouse ): ?>
            <tr>
                <td><?php echo $warehouse->getId() ?></td>
                <td class="ygo_delivery_warehouses_item_mobile">
                    <a href="<?php echo AdminUrlHelper::getWarehouseEditUrl( $warehouse->getId() ) ?>" class="ygo_delivery_warehouses_item_address"><?php echo $warehouse->getAddress() ?></a>
                    <div class="ygo_delivery_warehouses_item_actions">
                        <a class=""
                           href="<?php echo AdminUrlHelper::getWarehouseEditUrl( $warehouse->getId() ) ?>"><?php echo __( 'Редактировать', 'yandex-go-delivery' ) ?></a>
                        |
                        <a href="#" class="delete yandex-taxi-delivery__warehouse_delete_js"
                           data-warehouse-id="<?php echo $warehouse->getId() ?>"><?php echo __( 'Удалить', 'yandex-go-delivery' ) ?></a>
                    </div>
                </td>
                <td><?php echo $warehouse->getContactEmail() ?></td>
                <td><?php echo $warehouse->getContactName() ?></td>
                <td><?php echo $warehouse->getContactPhone() ?></td>
                <td><?php echo ( $warehouse->getId() === $defaultWarehouseId ) ? __( 'Да', 'yandex-go-delivery' ) : __( 'Нет', 'yandex-go-delivery' ) ?></td>
            </tr>
		<?php endforeach ?>
    </table>
</div>
<div>
    <a class="button"
       href="<?php echo AdminUrlHelper::getWarehouseEditUrl( null ) ?>"><?php echo __( 'Создать склад', 'yandex-go-delivery' ) ?></a>
</div>

<?php echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_support_contact.php' ) ?>
