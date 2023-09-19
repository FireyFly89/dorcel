<div class="custom-widget <?php echo esc_attr($this->widget_id); ?>">
    <div class="post_slider_widget__wrapper">

        <?php for ($i = 1; $i <= count($this->widget_posts['title']); $i++) :
            $slide_url_parameters = $url_handler->get_link_parameters($this->widget_posts['url'][$i]); ?>

            <div class="post_slider_widget__item <?php echo esc_attr($this->appearance[$i]); ?>">
                <div class="post_slider_widget__overlay" style="background-image: url('<?php echo esc_attr($this->widget_posts['background'][$i]); ?>')"></div>

                <div class="post_slider_widget__overlay__mobile" style="background-image: url('<?php echo esc_attr($this->widget_posts['background_mobile'][$i]); ?>')"></div>
                <div class="post_slider_widget__content">

                    <?php if (!empty($this->widget_posts['logo'][$i])) : ?>
                        <a <?php echo $slide_url_parameters; ?> class="post_slider_widget__logo">
                            <img src="<?php echo esc_attr($this->widget_posts['logo'][$i]); ?>" alt="<?php echo esc_attr($this->widget_posts['logo_alt'][$i]); ?>">
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($this->widget_posts['title'][$i])) : ?>
                        <div class="post_slider_widget__title">
                            <a <?php echo $slide_url_parameters; ?>><?php echo esc_html($this->widget_posts['title'][$i]); ?></a>
                        </div>
                    <?php endif; ?>

                    <div class="post_slider_widget__buttons">
                        <?php if (!empty($this->widget_posts['mov_btn'][$i])) : ?>
                            <a class="btn btn--outline btn--icon" <?php echo $url_handler->get_link_parameters($this->widget_posts['mov_btn_url'][$i]); ?>>
                                <?php echo esc_html($this->widget_posts['mov_btn'][$i]); ?>
                                <i class="fas fa-play-circle"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($this->appearance[$i] === 'simple_dvd_cover' && !empty($this->widget_posts['dvd_btn'][$i])) : ?>
                            <a class="btn btn--outline btn--icon" <?php echo $url_handler->get_link_parameters($this->widget_posts['dvd_btn_url'][$i]); ?>>
                                <?php echo esc_html($this->widget_posts['dvd_btn'][$i]); ?>
                                <img class="btn-icon-dvd" src="<?php echo SLS_WIDGETS_POST_IMAGES_URL . "dvd.svg"; ?>" />
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if ($this->appearance[$i] === 'simple_dvd_cover' && !empty($this->widget_posts['cover'][$i])) : ?>
                    <?php $cover_url = !empty($this->widget_posts['url'][$i]) ? $url_handler->get_link_parameters($this->widget_posts['url'][$i]) : $slide_url_parameters; ?>

                    <a <?php echo $cover_url; ?> class="post_slider_widget__cover">
                        <img src="<?php echo esc_attr($this->widget_posts['cover'][$i]); ?>" alt="<?php echo esc_attr($this->widget_posts['cover_alt'][$i]); ?>">
                    </a>
                <?php endif; ?>
                <a class="post_slider__url" <?php echo $slide_url_parameters; ?>></a>
            </div>

        <?php endfor; ?>

    </div>
</div>
