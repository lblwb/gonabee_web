<?php


add_action('wp_ajax_search_prd_or_cat', 'handle_custom_search'); // для авторизованных
add_action('wp_ajax_nopriv_search_prd_or_cat', 'handle_custom_search'); // для неавторизованных

function handle_custom_search()
{
    $query = isset($_GET['query']) ? sanitize_text_field($_GET['query']) : '';

    if (strlen($query) < 2) {
        wp_send_json([]);
    }

    // Поиск товаров
    $product_args = [
        'post_type' => 'product',
        's' => $query,
        'posts_per_page' => 8,
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock'
            ]
        ]
    ];
    $product_query = new WP_Query($product_args);
    $products = [];
    if ($product_query->have_posts()) {
        foreach ($product_query->posts as $post) {
            $product_id = $post->ID;
            $product = new WC_product($product_id);
            $thumbnail_url = get_the_post_thumbnail_url($product_id, 'original');

            // === Определяем цвета ===
            $colors = [];
            $acf_colors = [];
            $gallery_images_list = [];
            $gallery_images = $product->get_gallery_image_ids();

            $main_image_id = get_post_thumbnail_id($product_id);
            $all_images = array_merge([$main_image_id], $gallery_images);

            foreach ($all_images as $attachment_id) {
                $color_id = get_post_meta($attachment_id, '_color_id', true);
                $gallery_images_list[$color_id] = wp_get_attachment_image_url($attachment_id, 'woocommerce_thumbnail');
                if (!$color_id) $color_id = 'white';
                $colors[$color_id] = true;
            }

            if (have_rows('product_colors', $product_id)) {
                while (have_rows('product_colors', $product_id)) {
                    the_row();
                    $color_rel = get_sub_field('color_rel');
                    $color_post = is_array($color_rel) ? $color_rel[0] : null;

                    if ($color_post) {
                        $color_id = $color_post->ID;
                        $color_slug = get_field('color_slug', $color_id);
                        $color_name = get_the_title($color_id);
                        $color_code = get_field('color_code', $color_id);
                    }

                    if ($color_name && $color_slug && $color_code) {
                        $acf_colors[] = [
                            'name' => $color_name,
                            'slug' => $color_slug,
                            'code' => $color_code,
                        ];
                    }
                }
            }

            $products[] = [
                'ID' => $post->ID,
                'name' => get_the_title($post),
                'data' => $post,
                'link' => get_permalink($post),
                'image' => $thumbnail_url ? $thumbnail_url : '', // безопасно
                'gallery' => $gallery_images_list,
                'colors' => array_keys($colors), // список уникальных цветов
                'colors_list' => $acf_colors, // список уникальных цветов
                'price' => $product->get_price(), // список уникальных цветов
                'full_price' => wc_price($product->get_price()), // список уникальных цветов
                'type' => 'product',
                'is_stock' => $product->is_in_stock(),
            ];
        }
    }

    // Поиск категорий
    $categories_raw = get_terms([
        'taxonomy' => 'product_cat',
        'name__like' => $query,
        'number' => 3,
        'hide_empty' => false,
    ]);
    $categories = [];
    foreach ($categories_raw as $cat) {
        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'original') : '';
        //
        $parent_cat = null;
        if ($cat->parent) {
            $parent_term = get_term($cat->parent, 'product_cat');
            if ($parent_term && !is_wp_error($parent_term)) {
                $parent_cat = $parent_term;
            }
        }
        //
        $categories[] = [
            //            'id' => $cat->term_id,
            'name' => $cat->name,
            'data' => $cat,
            'link' => get_term_link($cat),
            'image' => $thumbnail_url ? $thumbnail_url : '', // безопасно
            'parent_cat' => $parent_cat,
            'type' => 'category'
        ];
    }

    wp_send_json(array(
        "categories" => $categories,
        "products" => $products,
    ));
}
