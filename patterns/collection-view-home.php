<?php
/**
 * Title: Collection View Home
 * Slug: ktsportwear/collection-view-home
 * @package WordPress
 */


//$popular_categories = get_terms([
//    'taxonomy' => 'product_cat',
//    'orderby' => 'count', // сортировка по количеству товаров в категории
//    'order' => 'DESC',
//    'hide_empty' => true,
//    'number' => 5, // получаем только 5
//]);


// Получаем все коллекции для вывода на фронте
$collectionPrds = get_terms(array(
    'taxonomy' => 'prd_collection', // Таксономия коллекций
    'orderby'    => 'term_order',
    'order' => 'ASC',
    'hide_empty' => false,
     'number' => 4, // получаем только 5
));

?>

<?php if (!empty($collectionPrds) && !is_wp_error($collectionPrds)) { ?>
    <div class="collectionView">
        <div class="collectionViewWrapper gridWrap">
            <div class="collectionViewHeading">
                <div class="blockHeadingTitle">
                    Коллекции
                </div>
            </div>
            <div class="collectionViewBody">
                <div class="collectionViewTabSel">
                    <div class="collectionViewTabSelList">
                        <?php
                        foreach ($collectionPrds as $collectionItem) {


                            // Получаем изображение категории
                            $thumbnail_id = get_term_meta($collectionItem->term_id, 'thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : get_stylesheet_directory_uri() . '/assets/images/banner_image_hero.png';
                            ?>

                            <a href="/shop/?collection=<?php echo esc_html($collectionItem->slug); ?>" class="tabSelListBlockItem"
                               style="background-image: url('<?php echo esc_url($image_url); ?>')">
                                <div class="tabSelListBlockItemTop">
                                    <div class="tabSelListBlockItemBtn">
                                        <div class="bannerBottomBtnTitle" style="font-size: 20px; font-weight: 400;">
                                            <?php echo esc_html($collectionItem->name); ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
