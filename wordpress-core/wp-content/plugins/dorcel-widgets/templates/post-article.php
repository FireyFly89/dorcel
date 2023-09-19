<div class="custom-widget <?php echo $this->widget_id; ?> <?php echo $this->appearance; ?>">
    <?php
    if (!empty($this->widget_title)) {
        echo $args['before_title'];
        echo sprintf('<%s class="custom-widget__title">%s</%s>', $this->widget_title_type, $this->widget_title, $this->widget_title_type);
        echo $args['after_title'];
    }

    if (!empty($this->widget_sub_url_text) && !empty($this->widget_sub_url)) {
        echo sprintf('<a class="custom-widget__title__sub-url" %s title="%s">%s</a>', $url_handler->get_link_parameters($this->widget_sub_url), $this->widget_sub_url_text, $this->widget_sub_url_text);
    }
    ?>

    <?php if ($this->appearance === "slider") : ?>
        <div id="article_slider" class="custom-widget__slides">
    <?php endif; ?>

        <?php foreach ($this->widget_posts as $post) : ?>
            <?php setup_postdata($post); ?>
            <?php $post_url = $url_handler->get_link_parameters(get_permalink($post->ID)); ?>

            <div class="custom-widget__article">
                <?php echo $this->widget_helper->get_widget_thumbnail($post, $this->appearance); ?>

                <div class="custom-widget__article__content">
                    <?php echo $this->widget_helper->get_the_post_categories($post->ID, $this->appearance); ?>
                    <?php echo sprintf('<a class="custom-widget__article__title" %s><%s>%s</%s></a>',
                        $post_url,
                        $this->article_title_types,
                        $this->widget_helper->trim_by_appearance(esc_html($post->post_title), $this->appearance),
                        $this->article_title_types);
                    ?>
                    <?php echo sprintf('<a class="custom-widget__article__excerpt" %s><span>%s</span></a>',
                        $post_url,
                        wp_kses_post((empty($post->post_excerpt) ? $this->widget_helper->trim_by_appearance($post->post_content, $this->appearance, 'content') : $post->post_excerpt)));
                    ?>
                    <a class="custom-widget__article__cta" href="<?php echo $post_url; ?>" target="_blank">
                        <?php _e("Lire la suite", THEME_LANGUAGE_DOMAIN); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php if ($this->appearance === "slider") : ?>
        </div>
    <?php endif; ?>
</div>

<?php
if (!empty($this->pagination)) {
    echo "<div class='custom-widget__pagination'>";

    foreach ($this->pagination as $page) {
        echo $page;
    }

    echo "</div>";
}
