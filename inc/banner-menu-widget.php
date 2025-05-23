<?php

class Banner_Menu_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'banner_menu_widget',
            __('[Menu] Баннер с кнопкой', 'text_domain'),
            ['description' => __('Баннер с изображением и кнопкой', 'text_domain')]
        );
    }

    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Подробнее';
        $button_url = !empty($instance['button_url']) ? esc_url($instance['button_url']) : '#';

        echo $args['before_widget'];

//        if ($title) {
//            echo $args['before_title'] . esc_html($title) . $args['after_title'];
//        }

        if ($image_url) {
            // $button_url / $button_text
            echo '<div class="bannerMenuWidget" style="background-image: url(' . esc_url($image_url) . ');">';
            //
            echo '<div class="bannerMenuWidgetWrapper">';
            echo '<div class="bannerMenuWidgetContentBannerTop">';
            //
            echo '<div class="contentBannerTopHeading">';
            echo '<div class="contentBannerTopHeadingTitle">';
            echo $title;
            echo '</div>';
            echo '</div>';
            //
            echo '</div>';
            //
            echo '<div class="contentBannerFooterHeading">';
            echo '<a href="' . $button_url . '" class="bannerFooterBtn">';
            echo '<div class="bannerFooterBtnHeading">';
            echo '<div class="bannerFooterBtnHeadingTitle">';
            echo $button_text;
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
            echo '</div>';
            //
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? esc_attr($instance['title']) : '';
        $image_url = !empty($instance['image_url']) ? esc_url($instance['image_url']) : '';
        $button_text = !empty($instance['button_text']) ? esc_attr($instance['button_text']) : 'Подробнее';
        $button_url = !empty($instance['button_url']) ? esc_url($instance['button_url']) : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo $title; ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>">Баннер (URL изображения):</label>
            <input class="widefat image-upload" id="<?php echo $this->get_field_id('image_url'); ?>"
                   name="<?php echo $this->get_field_name('image_url'); ?>" type="text"
                   value="<?php echo $image_url; ?>">
            <button class="button select-image" style="margin-top: 5px;">Загрузить / выбрать</button>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>">Текст кнопки:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>"
                   name="<?php echo $this->get_field_name('button_text'); ?>" type="text"
                   value="<?php echo $button_text; ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>">Ссылка кнопки:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>"
                   name="<?php echo $this->get_field_name('button_url'); ?>" type="text"
                   value="<?php echo $button_url; ?>">
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        return [
            'title' => sanitize_text_field($new_instance['title']),
            'image_url' => esc_url_raw($new_instance['image_url']),
            'button_text' => sanitize_text_field($new_instance['button_text']),
            'button_url' => esc_url_raw($new_instance['button_url']),
        ];
    }
}

function register_menu_banner_widget()
{
    register_widget('Banner_Menu_Widget');
}

add_action('widgets_init', 'register_menu_banner_widget');