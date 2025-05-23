<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;

/** @var Warehouse[] $warehousesList */
/** @var int $selectedId */
/** @var int $order_id */
?>

<div class="yandex-taxi-delivery_form__group">
	<label for="warehouse"><?php echo __( 'Использовать склад:', 'yandex-go-delivery' ) ?></label>
	<select id="warehouse" name="warehouse[<?php echo intval( $order_id ); ?>][id]" class="select js_yandex-taxi-delivery_form__param"
	        data-order_id="<?php echo intval( $order_id ); ?>">
		<?php foreach ( $warehousesList as $warehouse ): ?>
			<option
					value="<?php echo $warehouse->getId() ?>"
					data-json='<?php echo json_encode( $warehouse->toArray() ) ?>'
					<?php echo ( $selectedId == $warehouse->getId() ) ? 'selected' : '' ?>
			>
				<?php echo "№{$warehouse->getId()} {$warehouse->getAddress()}"; ?>
			</option>
		<?php endforeach ?>
	</select>
</div>
