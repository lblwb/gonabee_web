<?php

/**
 * Title: Store Reviews Single Product
 * Slug: ktsportwear/store-reviews-product
 * @package WordPress
 */

//
//$args = [
//    'status' => 'approve',         // Только одобренные
//    'type' => 'review',          // Только отзывы
//    'post_type' => 'product',         // Только товары
//    'number' => 6,                 // 0 = без ограничения
//];
//
//$comments_query = new WP_Comment_Query();
//$comments = $comments_query->query($args);

global $product;

$comments = get_comments([
    'post_id' => $product->get_id(),         // ID товара
    'status' => 'approve',           // Только одобренные
    'type' => 'review',            // Только отзывы
    'post_type' => 'product',         // Только товары
]);

if (!function_exists('render_star_svg')) {
    function render_star_svg($filled = true): string
    {
        $fill = $filled ? '#F0C224' : '#E0E0E0';
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:2px;"><path d="M9.99967 14.1663L5.10148 17.1582L6.43323 11.5752L2.07422 7.8412L7.79549 7.38252L9.99967 2.08301L12.2039 7.38252L17.9252 7.8412L13.5662 11.5752L14.8979 17.1582L9.99967 14.1663Z" fill="' . $fill . '"/></svg>';
    }
}

?>

<?php if (count($comments) > 0) { ?>
    <div class="storeReviews" style="margin-bottom: 60px">

        <div class="storeReviewsWrapper gridWrap">
            <div class="storeReviewsHeading" style="margin-bottom: 20px">
                <div class="storeReviewsHeadingTitle">
                    Отзывы о товаре
                </div>
            </div>

            <div class="storeReviewsBody">
                <div class="storeReviewsBodySlider" style="">
                    <?php
                    // Пример вывода
                    foreach (
                        $comments

                        as $comment
                    ) {
                        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                        $product_title = get_the_title($comment->comment_post_ID);
                        $product_link = get_permalink($comment->comment_post_ID);
                        $review_link = $product_link . '#comment-' . $comment->comment_ID;
                    ?>
                        <div class="storeReviewBodySliderItem">
                            <div class="reviewBodySliderItemHeading">
                                <div class="sliderItemHeadingTitle">
                                    <?php esc_html($comment->comment_author) ?>
                                </div>
                                <div class="sliderItemHeadingRate">
                                    <?php // Вывод звёзд
                                    echo '<div style="display:flex; flex-direction:row;">';
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo render_star_svg($i <= $rating);
                                    }
                                    echo '</div>';
                                    ?>
                                </div>

                                <div class="sliderItemHeadingDesc">
                                    <?php echo esc_html($comment->comment_content) ?>
                                </div>

                                <div class="sliderItemHeadingAction">
                                    <a class="sliderItemHeadingActionBtn" href="<?php echo esc_html($review_link) ?>">
                                        <span class="headingActionBtnTitle">
                                            Полный отзыв
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
<?php } ?>