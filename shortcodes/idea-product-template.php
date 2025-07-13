<?php global $product; ?>
<div class="ideaProduct">
    <div class="ideaProductWrapper gridWrap">
        <div class="ideaProductHeading" style="margin-bottom: 40px">
            <div class="ideaProductHeadingTitle"
                style="">
                Идеи для образа
            </div>
        </div>
        <div class="ideaProductBody">
            <div class="ideaProductWrapper" style="display: flex; flex-flow: row wrap; gap: 40px">
                <div class="ideaProductMain" style="flex: 1;display: block;">
                    <div class="ideaProductMainImage">
                        <?php
                        $main_image_id = $product->get_image_id();
                        $main_image_url = wp_get_attachment_image_url($main_image_id, 'large'); ?><img
                            src="<?php echo esc_url($main_image_url); ?>"
                            alt="<?php echo esc_attr($product->get_name()); ?>" />
                    </div>
                    <div class="sliderBlockItemHeading">
                        <div class="sliderBlockItemHeadingTitle">
                            <?php 
                            echo esc_attr($product->get_name()); 
                            ?>
                        </div>
                    </div>
                </div>
                <div class="ideaProductSlider">
                    <?php if (have_rows('ideas_product')): ?>
                        <?php while (have_rows('ideas_product')): the_row(); ?>
                            <div class="ideaProductSliderBlock" style="">
                                <div class="ideasProductWrapper">
                                    <?php if (have_rows('idea')): ?>
                                        <div class="ideaProductSliderBlockList" style="">

                                            <div class="swiper-wrapper">
                                                <?php while (have_rows('idea')): the_row();
                                                    $idea_name = get_sub_field('idea_name');
                                                    $idea_images = get_sub_field('idea_images'); // предположим, это массив изображений
                                                    $related_product = get_sub_field('idea_related_product'); ?>
                                                    <?php if ($idea_images): ?>
                                                        <?php foreach ($idea_images as $image): ?>
                                                            <div class="sliderBlockItemImages swiper-slide" <?php try { ?> onclick="window.states.prdModal.showModal('<?php echo $related_product[0]->ID;  ?>')<?php } catch (Exception $e) {
                                                                                                                                                                                                                        } ?>">
                                                                <div class="sliderBlockItemImage">
                                                                    <img src="<?php echo esc_url($image['url']); ?>"
                                                                        alt="<?php echo esc_attr($image['alt']); ?>" />
                                                                </div>
                                                                <div class="sliderBlockItemHeading">
                                                                    <div class="sliderBlockItemHeadingTitle">
                                                                        <?php if ($idea_name) { ?><?php echo esc_html($idea_name); ?><?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>

                                            </div>

                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="sliderNavBtnNext ideaProductSliderNext">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="40" width="40" height="40" rx="20" transform="rotate(90 40 0)" fill="white" />
                                        <path d="M20.9762 20.0014L16.8514 15.8766L18.03 14.6981L23.3333 20.0014L18.0299 25.3047L16.8514 24.1262L20.9762 20.0014Z"
                                            fill="#252525" />
                                    </svg>
                                </div>
                                <div class="sliderNavBtnPrev ideaProductSliderPrev">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="40" height="40" rx="20" transform="matrix(4.37114e-08 1 1 -4.37114e-08 0 0)"
                                            fill="white" />
                                        <path d="M19.0238 20.0014L23.1486 15.8766L21.9701 14.6981L16.6667 20.0014L21.9701 25.3047L23.1486 24.1262L19.0238 20.0014Z"
                                            fill="#252525" />
                                    </svg>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

            </div>
            <div class="ideaBottomPaginate" style=""></div>
        </div>
    </div>
</div>