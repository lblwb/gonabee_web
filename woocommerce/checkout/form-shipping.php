<?php foreach (WC()->shipping->get_packages() as $i => $package) : ?>
    <div class="shipping-methods">
        <?php foreach ($package['rates'] as $method) : ?>
            <label class="shipping-method">
                <input type="radio" name="shipping_method[<?php echo $i; ?>]" data-index="<?php echo $i; ?>"
                       id="shipping_method_<?php echo $method->id; ?>" value="<?php echo esc_attr($method->id); ?>"
                       class="shipping_method"/>
                <div class="shipping-icon">
                    <?php
                    // Здесь можно поставить кастомную иконку по названию метода
                    if (strpos($method->label, 'Самовывоз') !== false) {
                        echo '<img src="/wp-content/themes/your-theme/icons/pickup.svg" alt="Самовывоз">';
                    } elseif (strpos($method->label, 'СДЭК') !== false) {
                        echo '<img src="/wp-content/themes/your-theme/icons/cdek.svg" alt="СДЭК">';
                    } elseif (strpos($method->label, 'Яндекс') !== false) {
                        echo '<img src="/wp-content/themes/your-theme/icons/yandex.svg" alt="Яндекс">';
                    }
                    ?>
                </div>
                <div class="shipping-description">
                    <div class="shipping-name"><?php echo $method->label; ?></div>
                    <div class="shipping-info"><?php echo $method->cost ? wc_price($method->cost) : 'Бесплатно'; ?></div>
                </div>
            </label>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
