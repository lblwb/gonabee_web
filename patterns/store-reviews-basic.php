<?php

/**
 * Title: Store Reviews Basic
 * Slug: ktsportwear/store-reviews-basic
 * @package WordPress
 */


$args = [
    'status' => 'approve',         // Только одобренные
    'type' => 'review',          // Только отзывы
    'post_type' => 'product',         // Только товары
    'number' => 6,                 // 0 = без ограничения
];

$comments_query = new WP_Comment_Query();
$comments = $comments_query->query($args);

function render_star_svg($filled = true)
{
    $fill = $filled ? '#F0C224' : '#E0E0E0';
    return '
    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:2px;">
        <path d="M9.99967 14.1663L5.10148 17.1582L6.43323 11.5752L2.07422 7.8412L7.79549 7.38252L9.99967 2.08301L12.2039 7.38252L17.9252 7.8412L13.5662 11.5752L14.8979 17.1582L9.99967 14.1663Z" fill="' . $fill . '"/>
    </svg>';
}

?>
<?php if (count($comments) > 0) { ?>
    <div class="storeReviews" id="store-reviews-app" style="margin-bottom: 60px">

        <div class="storeReviewsWrapper gridWrap">
            <div class="storeReviewsHeading" style="margin-bottom: 20px">
                <div class="storeReviewsHeadingTitle">
                    О нас пишут
                </div>
            </div>

            <div class="storeReviewsBody">
                <div class="storeReviewsBodySlider swiper">
                    <div class="swiper-wrapper">
                        <?php
                        // Пример вывода
                        foreach ($comments as $comment) {
                            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                            $product_title = get_the_title($comment->comment_post_ID);
                            $product_link = get_permalink($comment->comment_post_ID);
                            $review_link = $product_link . '#comment-' . $comment->comment_ID;
                            ?>
                            <div class="swiper-slide storeReviewBodySliderItem">
                                <div class="reviewBodySliderItemHeading">
                                    <div class="sliderItemHeadingTitle">
                                        <?php echo esc_html($comment->comment_author) ?>
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
                                        <!-- <a class="sliderItemHeadingActionBtn" href="<?php echo esc_html($review_link) ?>">
                                            <span class="headingActionBtnTitle">
                                                Полный отзыв
                                            </span>
                                        </a> -->
                                        <!-- <a class="sliderItemHeadingActionBtn" href="<?php echo esc_html($review_link) ?>"> -->
                                            <?php $commentInfo = [
                                                "author" => $comment->author,
                                                "date" => $comment->comment_date,
                                                "content" => $comment->comment_content,
                                                "stars" => $rating,
                                            ];
                                            ?>
                                        <a class="sliderItemHeadingActionBtn"
                                            @click="openModal('<?php echo esc_html(json_encode($commentInfo)); ?>')">
                                            <span class="headingActionBtnTitle">
                                                Полный отзыв
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="storeReviewsSliderPaginate"></div>
                </div>
            </div>
        </div>
        <div id="review-modal" v-if="isModalOpen" class="modalOverlay" @click.self="closeModal" v-cloak>
            <div class="modalContent" v-if="modalContent">
                <button class="modalCloseBtn" @click="closeModal">×</button>
                <div class="modalReviewText" v-if="modalContent.content">
                    {{ modalContent.content }}
                </div>
                <div class="modalReviewAuthor" v-if="modalContent.author">
                    {{modalContent.author}}
                </div>
                <div class="modalReviewRate" v-if="modalContent.stars" style="padding: 16px 0;">
                    <div style="display:flex; flex-direction:row;">
                        <svg v-for="(key,index) in [{},{},{},{},{}]" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:2px;" v-if="modalContent.stars">
                            <path d="M9.99967 14.1663L5.10148 17.1582L6.43323 11.5752L2.07422 7.8412L7.79549 7.38252L9.99967 2.08301L12.2039 7.38252L17.9252 7.8412L13.5662 11.5752L14.8979 17.1582L9.99967 14.1663Z" :fill="(modalContent.stars <= index) ? '#E0E0E0' : '#F0C224'"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
      const storeReviewsApp = Vue.createApp({
        setup() {
            const isModalOpen = Vue.ref(false);
            const modalContent = Vue.ref('');

            const openModal = (text) => {
                modalContent.value = JSON.parse(text);
                isModalOpen.value = true;
            };

            const closeModal = () => {
                isModalOpen.value = false;
                modalContent.value = '';
            };

            return {
                isModalOpen,
                modalContent,
                openModal,
                closeModal
            };
        }
    }).mount('#store-reviews-app');
</script>

<?php } ?>

<style>
    .modalOverlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modalContent {
        background: white;
        max-width: 600px;
        width: 90%;
        padding: 30px;
        border-radius: 12px;
        position: relative;
    }

    .modalCloseBtn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .modalReviewText {
        font-size: 16px;
        line-height: 1.5;
        color: #252525;
    }
</style>