<div class="custom-widget <?php echo esc_attr($this->widget_id); ?>">
    <div id="blog_slider">

        <?php for ($i = 1; $i <= count($this->widget_posts['title']); $i++) :
            $slide_url_parameters = $url_handler->get_link_parameters($this->widget_posts['url'][$i]); ?>

            <div class="post_slider_widget__item">
                <div class="post_slider_widget__overlay" style="background-image: url('<?php echo esc_attr($this->widget_posts['background'][$i]); ?>')"></div>

                <div class="post_slider_widget__overlay__mobile" style="background-image: url('<?php echo esc_attr($this->widget_posts['background_mobile'][$i]); ?>')"></div>
                <div class="post_slider_widget__content">
                    <?php if (!empty($this->widget_posts['title'][$i])) : ?>
                        <div class="post_slider_widget__title">
                            <a <?php echo $slide_url_parameters; ?>><?php echo esc_html($this->widget_posts['title'][$i]); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <a class="post_slider__url" <?php echo $slide_url_parameters; ?>></a>
            </div>
        <?php endfor; ?>

    </div>
</div>
