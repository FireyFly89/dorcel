<?php

/**
 * Widget: PostSliderWidget class
 *
 * @package SLS
 * @subpackage Widgets
 * @since 5.2.2
 */
class PostSliderWidget extends WP_Widget
{
    const TITLE = 'title';
    const TITLE_URL = 'url';
    const MOVIE_BUTTON = 'movie_button';
    const MOVIE_BUTTON_URL = 'movie_button_url';
    const APPEARANCE = 'appearance';
    const DVD_BUTTON = 'dvd_button';
    const DVD_BUTTON_URL = 'dvd_button_url';
    const COVER_URL = 'cover_url';
    const COUNTRY_BLACKLIST = 'country_blacklist';
    const TEMPLATE_PATH = 'post-slider.php';
    const MEDIA_COVER = 'media_cover';
    const MEDIA_LOGO = 'media_logo';
    const MEDIA_BACKGROUND = 'media_background';
    const MEDIA_BACKGROUND_MOBILE = 'media_background_mobile';
    const LANGUAGE_SWITCHER = 'language_switcher';

    /**
     * @var string
     */
    private $appearance;
    /**
     * @var WidgetHelper
     */
    private $widget_helper;
    /**
     * @var WP_Post
     */
    private $widget_posts = [];
    /**
     * @var string
     */
    private $widget_id = 'post_slider_widget';
    private $widget_field_settings = [];

    /**
     * PostSliderWidget constructor.
     */
    public function __construct()
    {
        $this->widget_helper = new WidgetHelper();

        // Instantiate the parent object
        parent::__construct(
            $this->widget_id,
            __('Dorcel cover slider', 'dorcel-widgets'),
            ['description' => __('Displays a big slider with multiple slides', 'dorcel-widgets')]
        );

        add_action('wp_enqueue_scripts', [$this, 'widget_scripts']);
        // Add WP Media Library JS
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media();
        });

        $this->widget_field_settings = $this->widget_field_settings();
    }

    /**
     * Enqueue and localize wp scripts
     */
    public final function widget_scripts()
    {
        global $sls_theme_version;
        wp_enqueue_style('slick-styles', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', [], $sls_theme_version);
        wp_enqueue_script('slick-scripts', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], $sls_theme_version, false);
        wp_enqueue_script('dorcel-widget-scripts', SLS_WIDGETS_POST_SCRIPTS_URL . 'scripts.js', ['slick-scripts'], $sls_theme_version, false);
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     * @return bool
     */
    public final function widget($args, $instance)
    {
        global $url_handler;
        $title = $this->widget_helper->get_instance_data(self::TITLE, $instance);
        $url = $this->widget_helper->get_instance_data(self::TITLE_URL, $instance);
        $movie_button = $this->widget_helper->get_instance_data(self::MOVIE_BUTTON, $instance);
        $movie_button_url = $this->widget_helper->get_instance_data(self::MOVIE_BUTTON_URL, $instance);
        $dvd_button = $this->widget_helper->get_instance_data(self::DVD_BUTTON, $instance);
        $dvd_button_url = $this->widget_helper->get_instance_data(self::DVD_BUTTON_URL, $instance);
        $cover_url = $this->widget_helper->get_instance_data(self::COVER_URL, $instance);
        $this->appearance = $this->widget_helper->get_instance_data(self::APPEARANCE, $instance);
        $media_cover = $this->widget_helper->get_instance_data(self::MEDIA_COVER, $instance);
        $media_logo = $this->widget_helper->get_instance_data(self::MEDIA_LOGO, $instance);
        $media_background = $this->widget_helper->get_instance_data(self::MEDIA_BACKGROUND, $instance);
        $media_background_mobile = $this->widget_helper->get_instance_data(self::MEDIA_BACKGROUND_MOBILE, $instance);
        $country_blacklist = $this->widget_helper->get_instance_data(self::COUNTRY_BLACKLIST, $instance);

        if (is_array($country_blacklist) && in_array($this->widget_helper->get_current_language_code(), $country_blacklist)) {
            return false;
        }

        $this->widget_posts = [
            'title' => $title,
            'url' => $url,
            'mov_btn' => $movie_button,
            'mov_btn_url' => $movie_button_url,
            'dvd_btn' => $dvd_button,
            'dvd_btn_url' => $dvd_button_url,
            'cover_url' => $cover_url,
        ];

        if (!empty($media_cover)) {
            foreach ($media_cover as $key => $image_id) {
                $this->widget_posts['cover'][$key] = "";

                if (!empty($image_obj = wp_get_attachment_image_src($image_id, 'fullhd'))) {
                    $this->widget_posts['cover'][$key] = $image_obj[0];
                    $this->widget_posts['cover_alt'][$key] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }
            }
        }

        if (!empty($media_logo)) {
            foreach ($media_logo as $key => $image_id) {
                $this->widget_posts['logo'][$key] = "";

                if (!empty($image_obj = wp_get_attachment_image_src($image_id, 'fullhd'))) {
                    $this->widget_posts['logo'][$key] = $image_obj[0];
                    $this->widget_posts['logo_alt'][$key] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }
            }
        }

        if (!empty($media_background)) {
            foreach ($media_background as $key => $image_id) {
                $this->widget_posts['background'][$key] = "";

                if (!empty($image_obj = wp_get_attachment_image_src($image_id, 'fullhd'))) {
                    $this->widget_posts['background'][$key] = $image_obj[0];
                    $this->widget_posts['background_alt'][$key] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }
            }
        }

        if (!empty($media_background_mobile)) {
            foreach ($media_background_mobile as $key => $image_id) {
                $this->widget_posts['background_mobile'][$key] = "";

                if (!empty($image_obj = wp_get_attachment_image_src($image_id, 'fullhd'))) {
                    $this->widget_posts['background_mobile'][$key] = $image_obj[0];
                    $this->widget_posts['background_mobile_alt'][$key] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }
            }
        }

        if (!array_key_exists('title', $this->widget_posts) || !is_array($this->widget_posts['title'])) {
            return false;
        }

        ob_start();
        echo $args['before_widget'];
        require SLS_WIDGETS_POST_TEMPLATES_PATH . self::TEMPLATE_PATH;
        echo $args['after_widget'];
        echo ob_get_clean();
    }

    /**
     * Save widget options
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public final function update($new_instance, $old_instance)
    {
        return $this->widget_helper->update_widget_values($this->widget_field_settings, $new_instance, $old_instance);
    }

    /**
     * Output admin widget options form
     *
     * @param array $instance
     * @return string|void
     */
    public final function form($instance)
    {
        $instance_new = $this->widget_helper->initialize_instance_values($this->widget_field_settings, $instance);

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('Switch Language', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::LANGUAGE_SWITCHER),
            'name' => $this->get_field_name(self::LANGUAGE_SWITCHER),
            'value' => $instance_new[self::LANGUAGE_SWITCHER],
            'options' => $this->widget_helper->get_all_available_languages(),
            'class' => 'language-switcher',
        ]);

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('Country blacklist', 'dorcel-widgets'),
            'description' => __('Blacklist languages where the slider should not be visible', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::COUNTRY_BLACKLIST),
            'name' => $this->get_field_name(self::COUNTRY_BLACKLIST),
            'placeholder' => __('No blacklisting', 'dorcel-widgets'),
            'placeholder_enabled' => true,
            'default' => $this->widget_helper->get_field_default($this->widget_field_settings, self::COUNTRY_BLACKLIST),
            'value' => $instance_new[self::COUNTRY_BLACKLIST],
            'options' => $this->widget_helper->get_all_available_languages(),
            'class' => 'country-blacklist',
            'multiselect' => true,
        ]);

        echo '<div class="slides-wrapper sortable-widget">';

        // Widget admin form
        for ($i = 1; $i <= count($instance_new[self::TITLE][$instance_new[self::LANGUAGE_SWITCHER]]); $i++) {
            echo '<div class="slide-wrapper" data-slide-num="' . $i . '">';
            echo '<div class="slide-delete" aria-hidden="true"></div>';
            echo sprintf('<div class="slide-number">Slide %s: </div><div class="slide-title"><span>%s</span></div>', $i, $instance_new[self::TITLE][$instance_new[self::LANGUAGE_SWITCHER]][$i]);
            echo '<div class="slide-toggler" aria-hidden="true"></div>';
            echo '<div class="slide-content">';

            echo $this->widget_helper->get_widget_form_field('dropdown', [
                'title' => __('The appearance of the slide', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::APPEARANCE),
                'name' => $this->get_field_name(self::APPEARANCE),
                'value' => $instance_new[self::APPEARANCE],
                'options' => [
                    'simple' => 'Simple',
                    'simple_dvd_cover' => 'Simple + DVD button, and cover image'
                ],
                'associative' => $i,
                'class' => 'appearance-selector',
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide title', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::TITLE),
                'name' => $this->get_field_name(self::TITLE),
                'placeholder' => __('The slide title', 'dorcel-widgets'),
                'value' => $instance_new[self::TITLE],
                'type' => 'text',
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide title URL', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::TITLE_URL),
                'name' => $this->get_field_name(self::TITLE_URL),
                'placeholder' => __('The URL of the slide title', 'dorcel-widgets'),
                'value' => $instance_new[self::TITLE_URL],
                'type' => 'text',
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide movie button text', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MOVIE_BUTTON),
                'name' => $this->get_field_name(self::MOVIE_BUTTON),
                'placeholder' => __('The text that will show on the button', 'dorcel-widgets'),
                'value' => $instance_new[self::MOVIE_BUTTON],
                'type' => 'text',
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide movie button url', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MOVIE_BUTTON_URL),
                'name' => $this->get_field_name(self::MOVIE_BUTTON_URL),
                'placeholder' => __('The URL of the movie button', 'dorcel-widgets'),
                'value' => $instance_new[self::MOVIE_BUTTON_URL],
                'type' => 'text',
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide dvd button text', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::DVD_BUTTON),
                'name' => $this->get_field_name(self::DVD_BUTTON),
                'placeholder' => __('URL here', 'dorcel-widgets'),
                'value' => $instance_new[self::DVD_BUTTON],
                'type' => 'text',
                'associative' => $i,
                'class' => 'dvd-button-text',
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide dvd button url', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::DVD_BUTTON_URL),
                'name' => $this->get_field_name(self::DVD_BUTTON_URL),
                'placeholder' => __('URL here', 'dorcel-widgets'),
                'value' => $instance_new[self::DVD_BUTTON_URL],
                'type' => 'text',
                'associative' => $i,
                'class' => 'dvd-button-url',
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('input', [
                'title' => __('The slide cover url', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::COVER_URL),
                'name' => $this->get_field_name(self::COVER_URL),
                'placeholder' => __('URL here', 'dorcel-widgets'),
                'value' => $instance_new[self::COVER_URL],
                'type' => 'text',
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('media_library', [
                'title' => __('Select desktop only slide image', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MEDIA_BACKGROUND),
                'name' => $this->get_field_name(self::MEDIA_BACKGROUND),
                'value' => $instance_new[self::MEDIA_BACKGROUND],
                'button_text' => __('Select or upload background image for desktop', 'dorcel-widgets'),
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('media_library', [
                'title' => __('Select mobile only slide image', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MEDIA_BACKGROUND_MOBILE),
                'name' => $this->get_field_name(self::MEDIA_BACKGROUND_MOBILE),
                'value' => $instance_new[self::MEDIA_BACKGROUND_MOBILE],
                'button_text' => __('Select or upload background image for mobile', 'dorcel-widgets'),
                'associative' => $i,
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('media_library', [
                'title' => __('Select slide cover image', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MEDIA_COVER),
                'name' => $this->get_field_name(self::MEDIA_COVER),
                'value' => $instance_new[self::MEDIA_COVER],
                'button_text' => __('Select or upload cover image', 'dorcel-widgets'),
                'associative' => $i,
                'class' => 'cover-image',
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo $this->widget_helper->get_widget_form_field('media_library', [
                'title' => __('Select slide logo image', 'dorcel-widgets'),
                'id' => $this->get_field_id(self::MEDIA_LOGO),
                'name' => $this->get_field_name(self::MEDIA_LOGO),
                'value' => $instance_new[self::MEDIA_LOGO],
                'button_text' => __('Select or upload logo image', 'dorcel-widgets'),
                'associative' => $i,
                'class' => 'logo-image',
                'language' => $instance_new[self::LANGUAGE_SWITCHER],
            ]);

            echo '</div>';
            echo '</div>';
        }

        echo '<button class="button button-primary slide-add">Add slide</button>';
        echo '</div>';
    }

    /**
     * Returns widget field settings that are needed for field initialization, and update
     *
     * @return array
     */
    private final function widget_field_settings()
    {
        return [
            [
                'name' => self::LANGUAGE_SWITCHER,
            ],
            [
                'name' => self::COUNTRY_BLACKLIST,
                'default_override' => true,
                'default' => 'allowall',
            ],
            [
                'name' => self::TITLE,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::TITLE_URL,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MOVIE_BUTTON,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MOVIE_BUTTON_URL,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::DVD_BUTTON,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::DVD_BUTTON_URL,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::COVER_URL,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::APPEARANCE,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MEDIA_COVER,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MEDIA_LOGO,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MEDIA_BACKGROUND,
                'multilang' => true,
                'associative' => true,
            ],
            [
                'name' => self::MEDIA_BACKGROUND_MOBILE,
                'multilang' => true,
                'associative' => true,
            ],
        ];
    }
}
