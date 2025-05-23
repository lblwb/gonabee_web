<?php

defined( 'ABSPATH' ) || exit;

/** @var int $pointNumber */
/** @var string $id */
/** @var string $address */
/** @var string $flat */
/** @var string $porch */
/** @var string $floor */
/** @var string $fullName */
/** @var string $phone */
/** @var string $email */
/** @var string $editUrl */
/** @var boolean $isFake */
/** @var boolean $commentPlaceHolder */
/** @var string $chile_coordinate */

$is_bulk = $is_bulk ?? false;
?>
<div class="yandex-taxi-delivery_settings_grid">
	<div class="yandex-taxi-delivery_form__route_point yandex-taxi-delivery_setting_form__group">
		<div class="yandex-taxi-delivery_form__route_heading">
			<input class="yandex-taxi-delivery_form__route_point__order_id" type="hidden" value="<?php echo $id ?>">
			<h2 class="yandex-taxi-delivery_form__title">
				<?php echo __( 'Точка', 'yandex-go-delivery' ) ?> <span
						class="yandex-taxi-delivery__route_point_number"><?php echo $pointNumber ?></span>,
				<?php if ( ! $isFake ): ?>
					<a href="<?php echo $editUrl ?>"
					   target="_blank"><?php echo __( 'заказ №', 'yandex-go-delivery' ) ?><?php echo $id ?></a>
				<?php else: ?>
					<?php echo __( 'Точка без заказа', 'yandex-go-delivery' ) ?>
				<?php endif ?>
			</h2>
			<div class="yandex-taxi-delivery_form__controls">
				<?php if ( ! $is_bulk ) : ?>
					<a class="js_yandex-taxi-delivery_form__move-up-route-point"><span
								class="dashicons dashicons-arrow-up"></span></a>
					<a class="js_yandex-taxi-delivery_form__move-down-route-point"><span
								class="dashicons dashicons-arrow-down"></span></a>
					<a class="js_yandex-taxi-delivery_form__delete-route-point"><span
								class="dashicons dashicons-trash"></span></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="yandex-taxi-delivery_form__row">
			<div class="yandex-taxi-delivery_form__column_left">
				<div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
					<label for="customer_<?php echo $id ?>_address"><?php echo __( 'Адрес', 'yandex-go-delivery' ) ?>
						*</label>
					<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_address"
					       data-coordinate-name="customer[coordinate]"
					       type="text"
					       id="customer_<?php echo $id ?>_address"
					       name="customer[<?php echo $id ?>][address]"
					       required
					       value="<?php echo _wp_specialchars( $address ) ?>">
					<input type="hidden" name="customer[<?php echo $id ?>][coordinate]"
					       class="js_yandex-taxi-delivery_form__param js_yandex-taxi-delivery_form__param_coordinate"
					       value="<?php echo esc_html( $chile_coordinate ?? '' ); ?>">
					<span class="error-message"></span>
				</div>

				<div class="yandex-taxi-delivery_form__group row">
					<div class="yandex-taxi-delivery_form__group_small">
						<label for="customer_<?php echo $id ?>_flat"><?php echo __( 'Квартира', 'yandex-go-delivery' ) ?></label>
						<input class="js_yandex-taxi-delivery_form__param"
						       id="customer_<?php echo $id ?>_flat"
						       name="customer[<?php echo $id ?>][flat]"
						       type="text"
						       value="<?php echo _wp_specialchars( $flat ) ?>">
					</div>

					<div class="yandex-taxi-delivery_form__group_small">
						<label for="customer_<?php echo $id ?>_porch"><?php echo __( 'Подъезд', 'yandex-go-delivery' ) ?></label>
						<input class="js_yandex-taxi-delivery_form__param"
						       id="customer_<?php echo $id ?>_porch"
						       name="customer[<?php echo $id ?>][porch]"
						       type="number"
						       value="<?php echo _wp_specialchars( $porch ) ?>">
					</div>

					<div class="yandex-taxi-delivery_form__group_small">
						<label for="customer_<?php echo $id ?>_floor"><?php echo __( 'Этаж', 'yandex-go-delivery' ) ?></label>
						<input class="js_yandex-taxi-delivery_form__param"
						       id="customer_<?php echo $id ?>_floor"
						       name="customer[<?php echo $id ?>][floor]"
						       type="number"
						       value="<?php echo _wp_specialchars( $floor ) ?>">
					</div>
				</div>

				<div class="yandex-taxi-delivery_form__group">
					<label for="customer_<?php echo $id ?>_comment"><?php echo __( 'Комментарий', 'yandex-go-delivery' ) ?></label>
					<textarea
							class="yandex-taxi-delivery_form__group__input__textarea js_yandex-taxi-delivery_form__param"
							id="customer_<?php echo $id ?>_comment"
							name="customer[<?php echo $id ?>][comment]"><?php echo _wp_specialchars( $commentPlaceHolder ) ?></textarea>
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

				<h3 class="yandex-taxi-delivery_form__contact_heading"><?php echo __( 'Контакт клиента', 'yandex-go-delivery' ) ?></h3>

				<div class="yandex-taxi-delivery_form__group js_yandex-taxi-delivery_param_container">
					<label for="customer_<?php echo $id ?>_name"><?php echo __( 'Имя', 'yandex-go-delivery' ) ?>
						*</label>
					<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
					       type="text"
					       id="customer_<?php echo $id ?>_name"
					       name="customer[<?php echo $id ?>][name]"
					       required
					       value="<?php echo _wp_specialchars( $fullName ) ?>">
					<span class="error-message"></span>
				</div>

				<div class="yandex-taxi-delivery_form__group row">
					<div class="yandex-taxi-delivery_form__group_small js_yandex-taxi-delivery_param_container">
						<label for="customer_<?php echo $id ?>_phone"><?php echo __( 'Номер телефона', 'yandex-go-delivery' ) ?>
							*</label>
						<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
						       type="tel"
						       id="customer_<?php echo $id ?>_phone"
						       name="customer[<?php echo $id ?>][phone]"
						       required
						       value="<?php echo _wp_specialchars( $phone ) ?>">
						<span class="error-message"></span>
					</div>

					<div class="yandex-taxi-delivery_form__group_small js_yandex-taxi-delivery_param_container">
						<label for="customer_<?php echo $id ?>_email"><?php echo __( 'Email', 'yandex-go-delivery' ) ?>
							*</label>
						<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
						       type="text"
						       id="customer_<?php echo $id ?>_email"
						       name="customer[<?php echo $id ?>][email]"
						       required
						       value="<?php echo _wp_specialchars( $email ) ?>">
						<span class="error-message"></span>
					</div>
				</div>

				<div class="yandex-taxi-delivery_form__group checkbox">
					<input class="yandex-taxi-delivery_form__group__input js_yandex-taxi-delivery_form__param"
					       type="checkbox"
					       id="customer_<?php echo $id ?>_sms_on"
					       name="customer[<?php echo $id ?>][sms_on]"
					>
					<label for="customer_<?php echo $id ?>_sms_on"><?php echo __( 'Включить SMS подтверждение', 'yandex-go-delivery' ) ?></label>
				</div>
			</div>
		</div>
	</div>
</div>