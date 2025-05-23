<?php

add_action('wp_ajax_get_product_ideas', 'get_product_ideas');
add_action('wp_ajax_nopriv_get_product_ideas', 'get_product_ideas');

function get_product_ideas()
{
    $product_id = intval($_GET['product_id'] ?? 0);
    if (!$product_id) {
        wp_send_json_error('Missing product_id');
    }

    $ideas = [];

    if (have_rows('ideas_product', $product_id)) {
        while (have_rows('ideas_product', $product_id)) {
            the_row();
            if (have_rows('idea')) {
                while (have_rows('idea')) {
                    the_row();

                    $idea_name = get_sub_field('idea_name');
                    $idea_images = get_sub_field('idea_images') ?: [];
                    $related_product = get_sub_field('idea_related_product');


                    $product_data = null;
//                    print_r($related_product[0]->ID);
                    if ($related_product && get_post_type($related_product[0]->ID) === 'product') {
                        $product_data = [
                            'id' => $related_product[0]->ID,
                            'title' => get_the_title($related_product[0]->ID),
                            'image' => get_the_post_thumbnail_url($related_product[0]->ID, 'original'),
                            'price' => wc_get_product($related_product[0]->ID)->get_price_html(),
                            'permalink' => get_permalink($related_product[0]->ID),
                        ];
                    }

                    $ideas[] = [
                        'idea_name' => $idea_name,
                        'idea_images' => array_map(function ($img) {
                            return [
                                'url' => $img['url'],
                                'alt' => $img['alt'],
                            ];
                        }, $idea_images),
                        'related_product' => $product_data,
                    ];
                }
            }
        }
    }

    wp_send_json_success($ideas);
}


add_action('wp_ajax_get_product_modal', 'get_product_modal');
add_action('wp_ajax_nopriv_get_product_modal', 'get_product_modal');

function get_product_modal()
{
    $product_id = intval($_GET['product_id'] ?? 0);
    if (!$product_id) {
        wp_send_json_error('Missing product_id');
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
    }


    // Берём всё поле repeater как массив
    $colors = get_field('product_colors');

    $colors_data = [];

    // Получим размеры (pa_sizes) — таксономия
    $sizes = wc_get_product_terms($product_id, 'pa_sizes', ['fields' => 'names']);


    // ✅ Получаем поля ACF: product_colors (repeater)
    if (have_rows('product_colors', $product_id)) {
        while (have_rows('product_colors', $product_id)) {
            the_row();
            $colors_data[] = [
                'name' => get_sub_field('color_name'),
                'code' => get_sub_field('color_code'),
                'slug' => get_sub_field('color_slug'),
            ];
        }
    }

//    if (is_array($colors)) {
//        $colors_data = [
//            "default" => $colors[0],
//            "first" => $colors[0],
//            "list" => $colors,
//        ];
//    }


    $default_size_slug = "size_l";

    $data = [
        'id' => $product_id,
        'title' => get_the_title($product_id),
        'description' => wpautop($product->get_description()),
        'short_description' => wpautop($product->get_short_description()),
        'price' => $product->get_price_html(),
        'image' => get_the_post_thumbnail_url($product_id, 'large'),
        'link' => get_permalink($product_id),
        'colors' => $colors_data,
        'sizes' => $sizes
    ];

    wp_send_json_success($data);
}
