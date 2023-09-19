<div class="custom-widget <?php echo $this->widget_id; ?>">
    <?php
    echo $args['before_title'];
    echo sprintf('<%s class="custom-widget__title">%s</%s>', $this->widget_title_type, $this->widget_title, $this->widget_title_type);
    echo $args['after_title'];

    if (!empty($this->widget_sub_url_text) && !empty($this->widget_sub_url)) {
        echo sprintf('<a class="custom-widget__title__sub-url" %s title="%s">%s</a>', $url_handler->get_link_parameters($this->widget_sub_url), $this->widget_sub_url_text, $this->widget_sub_url_text);
    }
    ?>

    <div id="video_slider" class="custom-widget__slides">

    <?php foreach ($this->widget_posts as $post) : ?>
        <?php $post_url = $url_handler->get_link_parameters($post['url']); ?>
        <div class="custom-widget__article custom-widget__slide--container">
            <?php echo sprintf('<a class="custom-widget__thumbnail" target="_blank" %s>%s</a>', $post_url,
                sprintf('<img src="%s" />', $post['image_src'])
            );
            ?>
            <div class="custom-widget__article__content">
                <?php
                //echo $this->widget_helper->get_the_category($post['category'], $post_url);
                echo sprintf('<a class="custom-widget__article__title" target="_blank" %s><span>%s</span></a>',
                    $post_url,
                    esc_html($post['title'])
                );
                echo sprintf('<a class="custom-widget__article__excerpt" target="_blank" %s><span>%s</span></a>',
                    $post_url,
                    $post['description']
                );
                ?>
                <a class="custom-widget__article__cta" href="<?php echo $post_url; ?>" target="_blank">
                    <?php _e("Lire la suite", THEME_LANGUAGE_DOMAIN); ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    </div>
</div>
