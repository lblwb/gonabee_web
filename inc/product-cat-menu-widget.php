<?php

class Custom_Product_Categories_Menu_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'custom_product_categories_menu_widget',
            __('[Menu] Категории с вложенностью + ссылка', 'text_domain'),
            ['description' => __('Отображает категории продуктов с вложенными подкатегориями и ссылкой в заголовке.', 'text_domain')]
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

//        $title = !empty($instance['title']) ? $instance['title'] : __('Категории', 'text_domain');
//        $link = !empty($instance['link']) ? esc_url($instance['link']) : '#';

        $parent_id = isset($instance['parent_id']) ? intval($instance['parent_id']) : 0;


//        var_dump($args);
//        echo $args['before_title'] . '<a href="' . $link . '">' . apply_filters('widget_title', $title) . '</a>' . $args['after_title'];

        echo "<div class='widgetMenuHeading'><a href='#'><div class='widgetMenuHeadingWrapper'><span class='widgetTitle'>" . $instance['title'] . "</span><span class='widgetIcon'><img src='" . get_template_directory_uri() . '/assets/images/menu-arrow.svg' . "'></div></a></div>";


        // Заголовок с ссылкой
//        echo $args['before_title'] . '<a href="' . $link . '">' . apply_filters('widget_title', $title) . '</a>' . $args['after_title'];

        // Вывод категорий товаров
        $args = [
            'taxonomy' => 'product_cat',
            'parent' => $parent_id,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
        ];

        $categories = get_terms($args);

        if (!empty($categories)) {
            echo '<ul class="custom-product-categories">';
            echo '<li>';
            echo '<a href="">Смотреть все</a>';
            echo '</li>';
            foreach ($categories as $category) {
                echo '<li>';
                echo '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';

                // Вложенные категории
                $child_args = [
                    'taxonomy' => 'product_cat',
                    'parent' => $category->term_id,
                    'hide_empty' => false,
                ];
//                $children = get_terms($child_args);
//                if (!empty($children)) {
//                    echo '<ul class="sub-categories">';
//                    foreach ($children as $child) {
//                        echo '<li><a href="' . get_term_link($child) . '">' . esc_html($child->name) . '</a></li>';
//                    }
//                    echo '</ul>';
//                }

                echo '</li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Категории', 'text_domain');
        $link = !empty($instance['link']) ? $instance['link'] : '';
        $parent_id = isset($instance['parent_id']) ? $instance['parent_id'] : 0;

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0,
        ]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Заголовок:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('link')); ?>"><?php _e('Ссылка на заголовок:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('link')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('link')); ?>" type="text"
                   value="<?php echo esc_attr($link); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('parent_id'); ?>"><?php _e('Родительская категория:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('parent_id'); ?>"
                    name="<?php echo $this->get_field_name('parent_id'); ?>">
                <option value="0"><?php _e('— Без родителя —'); ?></option>
                <?php foreach ($terms as $term): ?>
                    <option value="<?php echo $term->term_id; ?>" <?php selected($parent_id, $term->term_id); ?>>
                        <?php echo esc_html($term->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['link'] = (!empty($new_instance['link'])) ? esc_url_raw($new_instance['link']) : '';
        return $instance;
    }
}

// Регистрируем виджет
function register_custom_product_categories_menu_widget()
{
    register_widget('Custom_Product_Categories_Menu_Widget');
}

add_action('widgets_init', 'register_custom_product_categories_menu_widget');
