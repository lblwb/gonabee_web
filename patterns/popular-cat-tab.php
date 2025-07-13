<?php
/**
 * Title: Popular Category with Tabs
 * Slug: ktsportwear/popular-cat-tab
 * @package WordPress
 */

// Шаг 1: Получаем ID родительских категорий с названиями "Мужская одежда" и "Женская одежда"
$parent_men_category = get_term_by('name', 'Мужская одежда', 'product_cat');
$parent_women_category = get_term_by('name', 'Женская одежда', 'product_cat');

// Получаем дочерние категории для каждой из родительских категорий
$ppl_cat_man = get_terms([
    'taxonomy' => 'product_cat',
    'order' => 'ASC',
    'hide_empty' => false,
    'parent' => $parent_men_category->term_id // Здесь правильно используем параметр 'parent'
]);

$ppl_cat_woman = get_terms([
    'taxonomy' => 'product_cat',
    'order' => 'ASC',
    'hide_empty' => false,
    'parent' => $parent_women_category->term_id // Здесь также
]);

$typeCat = [
    ["name" => "Мужское", "cat_slug" => "man", "data" => $ppl_cat_man],
    ["name" => "Женское", 'cat_slug' => "woman", "data" => $ppl_cat_woman]
];
?>

<?php
$filtered_categories = [];

foreach ($typeCat as $tc) {
    foreach ($tc['data'] as $category) {
        // Проверяем ACF поле popular_cat_view для каждой категории
        $popular_view = get_field('popular_cat_view', 'product_cat_' . $category->term_id);

        if ($popular_view) {
            // Добавляем в отфильтрованные категории
            $filtered_categories[] = $category;
        }
    }
}

// Ограничиваем количество отфильтрованных категорий до 6
$filtered_categories = array_slice($filtered_categories, 0,12);
?>

<?php if (!empty($filtered_categories) && !is_wp_error($filtered_categories)) : ?>
    <div class="popularCat" id="popularCat" v-cloak>
        <div class="popularCatWrapper gridWrap">
            <div class="popularCatHeading">
                <div class="blockHeadingTitle">
                    Популярные категории
                </div>
            </div>
            <div class="popularCatBody">
                <div class="popularCatTabs">
                    <div class="popularCatTabsWrapper">
                        <?php foreach ($typeCat as $tc) : ?>
                            <div class="popularCatTabsItem"
                                 :class="{ '__Active': popularCatSel === '<?php echo $tc['cat_slug']; ?>' }"
                                 @click="popularCatSel = '<?php echo $tc['cat_slug']; ?>'">
                                <div class="popularCatTabsItemTitle">
                                    <?php echo $tc['name']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

<!--                <input x-model="popularCatSel">-->


                <div class="popularCatTabSel">
                    <?php foreach ($typeCat as $tc) : ?>
                        <div v-show="popularCatSel === '<?php echo $tc['cat_slug']; ?>'" class="popularCatTabSelList">
                            <?php
                            // Отображаем категории для выбранной вкладки
                            foreach ($tc['data'] as $category) :
                                // Убедимся, что категория была отфильтрована по popular_cat_view
                                if (in_array($category, $filtered_categories)) :
                                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : get_stylesheet_directory_uri() . '/assets/images/banner_image_hero.png';
                                    ?>

                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="tabSelListBlockItem"
                                       style="background-image: url('<?php echo esc_url($image_url); ?>')">
                                        <div class="tabSelListBlockItemBottom">
                                            <div class="tabSelListBlockItemBtn">
                                                <div class="bannerBottomBtnTitle">
                                                    <?php echo esc_html($category->name); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
