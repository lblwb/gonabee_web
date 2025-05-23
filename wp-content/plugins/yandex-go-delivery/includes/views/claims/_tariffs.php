<?php

defined( 'ABSPATH' ) || exit;

use YandexTaxi\Delivery\Entities\Claim\Tariff;

/** @var Tariff[] $tariffs */
?>
<div class="yandex-taxi-delivery_settings_grid">
    <div class="yandex-taxi-delivery_form__container_actions yandex-taxi-delivery_setting_form__group">
        <div class="yandex-taxi-delivery_form__group tariff-container">
            <label for="tariff" class="tariff"><?php echo __( 'Тариф', 'yandex-go-delivery' ) ?></label>
            <div>
                <div class="tariff-option-container">
                    <label>
                        <input type="radio" name="tariff" value="default" checked="checked">
                        <span><?php echo __( 'По умолчанию', 'yandex-go-delivery' ) ?></span>
                    </label>
                </div>
				<?php foreach ( $tariffs as $tariff ): ?>
                    <div class="tariff-option-container">
                        <label>
                            <input type="radio" name="tariff" value="<?php echo _wp_specialchars( $tariff->getName() ) ?>">
                            <span><?php echo _wp_specialchars( $tariff->getTitle() ) ?></span>
                            <p class="tariff-info"><?php echo _wp_specialchars( $tariff->getText() ) ?></p>
                        </label>
						<?php
						$i = 0;
						foreach ( $tariff->getRequirements() as $requirement ): ?>
                            <div class="tariff-requirement" style="display: none">
                                <label><?php echo $requirement->getTitle() ?></label>
								<?php if ( $requirement->isSelect() ): ?>
									<?php if ( ! $requirement->isRequired() ): ?>
                                        <label class="tariff-option">
                                            <input type="radio"
                                                   name="tariff_requirements[<?php echo $tariff->getName() ?>][<?php echo $requirement->getName() ?>]"
												<?php if ( $i === 0 ): ?> checked="checked" <?php endif ?>
                                                   value="false">
                                            <span><?php echo __( 'Не требуется', 'yandex-go-delivery' ) ?></span>
                                        </label>
										<?php $i ++; ?>
									<?php endif ?>
									<?php foreach ( $requirement->getOptions() as $option ): ?>
                                        <label class="tariff-option">
                                            <input type="radio"
                                                   name="tariff_requirements[<?php echo $tariff->getName() ?>][<?php echo $requirement->getName() ?>]"
												<?php if ( $i === 0 ): ?> checked="checked" <?php endif ?>
                                                   value="<?php echo $option->getValue() ?>">
                                            <span><?php echo $option->getTitle() ?></span>
                                            <p class="tariff-info"><?php echo ( $option->getText() !== $option->getTitle() ) ? $option->getText() : '' ?></p>
                                        </label>
										<?php $i ++; endforeach ?>
								<?php endif ?>

								<?php if ( $requirement->isMultiSelect() ): ?>
									<?php foreach ( $requirement->getOptions() as $option ): ?>
                                        <div class="checkbox">
                                            <input type="checkbox"
                                                   id="tariff_requirements_<?php echo $requirement->getName() ?>_<?php echo $option->getValue() ?>"
                                                   value="<?php echo $option->getValue() ?>"
                                                   name="tariff_requirements[<?php echo $tariff->getName() ?>][<?php echo $requirement->getName() ?>][]">
                                            <label
                                                    for="tariff_requirements_<?php echo $requirement->getName() ?>_<?php echo $option->getValue() ?>">
												<?php echo $option->getTitle() ?>
                                            </label>
                                        </div>
									<?php endforeach ?>
								<?php endif ?>
                            </div>
						<?php endforeach ?>
                    </div>
				<?php endforeach ?>
            </div>
        </div>
    </div>
</div>
