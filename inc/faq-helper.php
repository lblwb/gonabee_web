<?php



// Регистрируем CPT для FAQ
add_action('init', function () {
    register_post_type('faq_item', [
        'labels' => [
            'name' => 'FAQ',
            'singular_name' => 'FAQ',
            'add_new' => 'Добавить вопрос',
            'add_new_item' => 'Новый вопрос',
            'edit_item' => 'Редактировать вопрос',
            'new_item' => 'Новый вопрос',
            'view_item' => 'Просмотр вопроса',
            'search_items' => 'Поиск FAQ',
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title'], // Вопрос
        'has_archive' => false,
        'rewrite' => ['slug' => 'faq'],
    ]);
});

// Регистрируем таксономию для категорий FAQ
add_action('init', function () {
    register_taxonomy('faq_category', ['faq_item'], [
        'labels' => [
            'name' => 'Категории FAQ',
            'singular_name' => 'Категория FAQ',
            'search_items' => 'Поиск категорий',
            'all_items' => 'Все категории',
            'edit_item' => 'Редактировать категорию',
            'update_item' => 'Обновить категорию',
            'add_new_item' => 'Добавить новую категорию',
            'new_item_name' => 'Название новой категории',
            'menu_name' => 'Категории FAQ',
        ],
        'hierarchical' => false,
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'faq-category'],
    ]);
});

// ACF-поля для FAQ
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key' => 'group_faq_fields',
        'title' => 'FAQ данные',
        'fields' => [
            [
                'key' => 'field_faq_category',
                'label' => 'Категория FAQ',
                'name' => 'faq_category',
                'type' => 'taxonomy',
                'taxonomy' => 'faq_category',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 1, // Разрешить добавлять термины прямо из ACF
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id', // или 'object', если хочешь больше данных
            ],
            [
                'key' => 'field_faq_answer',
                'label' => 'Ответ на вопрос',
                'name' => 'faq_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'faq_item',
                ]
            ]
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => [
            'excerpt',
            'discussion',
            'comments',
            'revisions',
            'author',
            'format',
            'page_attributes',
            'featured_image',
            'categories',
            'tags',
            'send-trackbacks',
        ],
        'active' => true,
    ]);
});


add_action('wp_ajax_get_faq_items', 'get_faq_items_callback');
add_action('wp_ajax_nopriv_get_faq_items', 'get_faq_items_callback');

function get_faq_items_callback()
{
    $search   = sanitize_text_field($_POST['search'] ?? '');
    $category = intval($_POST['category'] ?? 0);

    $args = [
        'post_type' => 'faq_item',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        's' => $search,
    ];

    if ($category > 0) {
        $args['tax_query'] = [[
            'taxonomy' => 'faq_category',
            'field' => 'term_id',
            'terms' => $category,
        ]];
    }

    $query = new WP_Query($args);
    $results = [];

    while ($query->have_posts()) {
        $query->the_post();
        $results[] = [
            'question' => get_the_title(),
            'answer' => apply_filters('the_content', get_field('faq_answer')),
            'category' => wp_get_post_terms(get_the_ID(), 'faq_category', ['fields' => 'names'])[0] ?? '',
        ];
    }

    wp_reset_postdata();
    wp_send_json_success($results);
}

