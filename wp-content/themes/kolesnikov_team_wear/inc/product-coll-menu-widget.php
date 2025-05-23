<?php


class Custom_Collections_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'custom_collections_widget',
            __('Коллекции товаров', 'text_domain'),
            [
                'classname' => 'widget_collections',
                'description' => __('Выводит список коллекций из таксономии prd_collection', 'text_domain'),
            ]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo "<div class='widgetMenuHeading'><a href='#'><div class='widgetMenuHeadingWrapper'><span class='widgetTitle'>".$instance['title']."</span><span class='widgetIcon'><img src='". get_template_directory_uri() . '/assets/images/menu-arrow.svg'."'></div></a></div>";

//        var_dump($instance);

//        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
//        $link  = !empty($instance['link']) ? esc_url($instance['link']) : '';

//        if ($title) {
//            echo $args['before_title'];
//            if ($link) {
//                echo '<a href="' . $link . '">' . $title . '</a>';
//            } else {
//                echo $title;
//            }
//            echo $args['after_title'];
//        }

        $collections = get_terms([
            'taxonomy' => 'prd_collection',
            'hide_empty' => true,
            'orderby' => 'name',
        ]);

        if (!empty($collections) && !is_wp_error($collections)) {
            echo '<ul class="custom-collections">';
            foreach ($collections as $collection) {
                echo '<li><a href="' . esc_url(get_term_link($collection)) . '">' . esc_html($collection->name) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Нет коллекций для отображения.</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Коллекции', 'text_domain');
        $link = isset($instance['link']) ? $instance['link'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Заголовок:'); ?></label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('link')); ?>"><?php _e('Ссылка на заголовок:'); ?></label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('link')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('link')); ?>"
                   type="url"
                   value="<?php echo esc_attr($link); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        return [
            'title' => sanitize_text_field($new_instance['title']),
            'link'  => esc_url_raw($new_instance['link']),
        ];
    }
}

// Регистрируем виджет
function register_custom_collections_widget() {
    register_widget('Custom_Collections_Widget');
}
add_action('widgets_init', 'register_custom_collections_widget');
