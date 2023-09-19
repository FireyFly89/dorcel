<div class="custom-widget <?php echo $this->widget_id; ?>">
    <?php
    echo $args['before_title'];
    echo sprintf('<%s class="custom-widget__title">%s</%s>', $this->widget_title_type, $this->widget_title, $this->widget_title_type);
    echo $args['after_title'];

    if (!empty($this->widget_sub_url_text) && !empty($this->widget_sub_url)) {
        echo sprintf('<a class="custom-widget__title__sub-url" %s title="%s">%s</a>', $url_handler->get_link_parameters($this->widget_sub_url), $this->widget_sub_url_text, $this->widget_sub_url_text);
    }
    ?>

    <div id="magazine_slider" class="custom-widget__slides">

    <?php foreach($this->widget_posts as $movie) : ?>
        <div class="custom-widget__slide--container">
            <div class="custom-widget__slide">
                <a <?php echo $url_handler->get_link_parameters($movie['url']); ?>>
                    <img src="<?php echo esc_attr($movie['image']); ?>" />
                    <span class="custom-widget__slide--title"><?php echo ucfirst(esc_html($movie['date'])); ?></span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    </div>
</div>
