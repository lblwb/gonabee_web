<?php
$nonce = $args['nonce'] ?? '';
$product_id = $args['product_id'] ?? 0;
$product = $args['product'] ?? null;
?>

<!-- mob -->
<div class="shopProductDetailMobFixBar" id="singlePrdMob" data-product-id="<?php echo esc_attr($product_id); ?>"
    v-cloak
    data-nonce="<?php echo esc_attr($nonce); ?>"
    data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <div class="detailMobFixBarWrapper">

        <div class="selSizeProductBlock" style="padding: 10px;border-bottom: solid 1px #eee;"
            v-show="appMobShop.cart.mob.size.show">
            <div class="selSizeProductBlockHeading">
                <div class="selSizeProductBlockHeadingTitle"
                    style="font-family: Sofia Sans;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;">
                    Размеры
                </div>
            </div>
            <div class="selSizeProductBlockBody">
                <div class="detailCardInfoHeadingSizes">
                    <?php $sizes = wc_get_product_terms($product->get_id(), 'pa_sizes', array('fields' => 'names'));

                    if (!empty($sizes)):
                    ?>
                        <div class="size-selector">
                            <!--                                <div class="size-selector__header">-->
                            <!--                                    <a href="#size-chart" class="size-selector__link">Размерная сетка</a>-->
                            <!--                                </div>-->
                            <div class="size-selector__options">
                                <?php foreach ($sizes as $size): ?>
                                    <label class="size-selector__option"
                                        :class="{__Active: appMobShop.cart.select.size === '<?php echo $size; ?>'}">
                                        <input type="radio" name="attribute_pa_size"
                                            @click="selectCartSize('<?php echo $size ?>')"

                                            value="<?php echo esc_attr($size); ?>"
                                            required>
                                        <span><?php echo esc_html($size); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else:
                        echo '<div class="size-selector" style="padding: 10px 0">Размеры товара отсутствуют!</div>';
                    endif; ?>
                </div>
            </div>
        </div>

        <div class="detailMobFixBarSelectSize">
            <a @click="toggleMobSizePanel">Выберите размер</a>
        </div>
        <div class="detailMobFixBarSelectActions">
            <?php
            $main_thumb_url = "";
            $main_thumb_id = $product->get_image_id();
            if ($main_thumb_id) {
                $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
            }
            ?>
            <div class="detailMobFixBarSelectActionsWrap" style="">
                <div class="whiteListBtn"
                    v-if="getSelectedWhtLst(<?= esc_attr($product->get_id()); ?>)"
                    @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>'})">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="20" fill="#F0C224" />
                        <path
                            d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                            fill="#1F1F1F" />
                    </svg>
                </div>

                <div class="whiteListBtn"
                    v-else
                    @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>'})">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_181_11533)">
                            <path d="M40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40C31.0457 40 40 31.0457 40 20Z"
                                fill="#F0C224" />
                            <path d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                fill="#CE1B19" />
                            <path d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                fill="#CE1B19" />
                        </g>
                        <defs>
                            <clipPath id="clip0_181_11533">
                                <rect width="40" height="40" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </div>


                <div class="addToCartBtn" style=""
                    @click="addToCartMob($event, {imageUrl:'<?php echo $main_thumb_url ?>'})">
                    <div class="addToCartBtnWrapper" style="">
                        <div class="addToCartBtnTitle" style="">Добавить в корзину <?php echo $product->get_price_html(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>