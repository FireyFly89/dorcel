<?php
/**
 * Widget: PostVideoFeedWidget class
 *
 * @package SLS
 * @subpackage Widgets
 * @since 5.2.2
 */

class PostVideoFeedWidget extends WP_Widget
{
    const TITLE = 'title';
    const TITLE_TYPE = 'title_type';
    const WIDGET_SUB_URL_TEXT = 'widget_sub_url_text';
    const WIDGET_SUB_URL = 'widget_sub_url';
    const ARTICLE_NUMBER = 'article_number';
    const TEMPLATE_PATH = 'post-video-feed.php';
    const LANGUAGE_SWITCHER = 'language_switcher';
    const ARTICLE_NUMBER_DEFAULT = 20;

    /**
     * @var string
     */
    private $widget_id = 'post_video_feed_widget';
    /**
     * @var string
     */
    private $widget_title;
    /**
     * @var string
     */
    private $widget_title_type;
    /**
     * @var string
     */
    private $widget_sub_url_text;
    /**
     * @var string
     */
    private $widget_sub_url;
    /**
     * @var WidgetHelper
     */
    private $widget_helper;
    /**
     * @var WP_Post
     */
    private $widget_posts;

    private $widget_field_settings = [];

    /**
     * PostVideoFeedWidget constructor.
     */
    public function __construct()
    {
        $this->widget_helper = new WidgetHelper();

        // Instantiate the parent object
        parent::__construct(
            $this->widget_id,
            __('Dorcel video feed slider', 'dorcel-widgets'),
            ['description' => __('Displays video widgets as a slider that comes from a feed', 'dorcel-widgets')]
        );

        add_action('wp_enqueue_scripts', [$this, 'widget_scripts']);
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
     * @param array $args
     * @param array $instance
     */
    public final function widget($args, $instance)
    {
        global $url_handler;
        $this->widget_title = $this->widget_helper->get_instance_data(self::TITLE, $instance);
        $this->widget_title_type = $this->widget_helper->get_instance_data(self::TITLE_TYPE, $instance, $this->widget_helper->get_array_first_element($this->widget_helper->get_title_types()));
        $this->widget_sub_url_text = $this->widget_helper->get_instance_data(self::WIDGET_SUB_URL_TEXT, $instance);
        $this->widget_sub_url = $this->widget_helper->get_instance_data(self::WIDGET_SUB_URL, $instance);
        $article_number = $this->widget_helper->get_instance_data(self::ARTICLE_NUMBER, $instance, self::ARTICLE_NUMBER_DEFAULT);
        $current_feed_file = $this->generate_video_feed_files();
        $xml_reader = simplexml_load_file(SLS_WIDGETS_POST_FEEDS_PATH . $current_feed_file, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->widget_posts = [];

        if (property_exists($xml_reader, 'channel') && property_exists($xml_reader->channel, 'item')) {
            foreach ($xml_reader->channel->item as $key => $xml_data) {
                if ($article_number <= count($this->widget_posts)) {
                    break;
                }

                $image_src = '';
                $description = new DOMDocument();
                $description->loadHTML('<?xml encoding="utf-8" ?>' . $xml_data->description);
                $image = $description->getElementsByTagName('img');

                foreach ($image as $video_image) {
                    if (!empty($image_src = $video_image->getAttribute('src'))) {
                        $image_src = (string)$image_src;
                    }
                }

                $this->widget_posts[] = [
                    'title' => (string)$xml_data->title,
                    'url' => (string)$xml_data->link,
                    'image_src' => $image_src,
                    'description' => wp_trim_words(strip_tags((string)$description->textContent), 14),
                    'category' => (string)$xml_data->category,
                ];
            }
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

        echo '<p>';

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('Switch Language', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::LANGUAGE_SWITCHER),
            'name' => $this->get_field_name(self::LANGUAGE_SWITCHER),
            'value' => $instance_new[self::LANGUAGE_SWITCHER],
            'options' => $this->widget_helper->get_all_available_languages(),
            'class' => 'language-switcher',
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('The number of articles to display', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::ARTICLE_NUMBER),
            'name' => $this->get_field_name(self::ARTICLE_NUMBER),
            'placeholder' => __('20 by default', 'dorcel-widgets'),
            'value' => $instance_new[self::ARTICLE_NUMBER],
            'type' => 'number',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('The widget title', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::TITLE),
            'name' => $this->get_field_name(self::TITLE),
            'placeholder' => __('The widget title', 'dorcel-widgets'),
            'value' => $instance_new[self::TITLE],
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('The title type of the widget', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::TITLE_TYPE),
            'name' => $this->get_field_name(self::TITLE_TYPE),
            'placeholder' => __('Select the type of title', 'dorcel-widgets'),
            'value' => $instance_new[self::TITLE_TYPE],
            'options' => $this->widget_helper->get_title_types(),
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('The text of The URL under the widget', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::WIDGET_SUB_URL_TEXT),
            'name' => $this->get_field_name(self::WIDGET_SUB_URL_TEXT),
            'placeholder' => __('Please insert text', 'dorcel-widgets'),
            'value' => $instance_new[self::WIDGET_SUB_URL_TEXT],
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('The URL under the widget', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::WIDGET_SUB_URL),
            'name' => $this->get_field_name(self::WIDGET_SUB_URL),
            'placeholder' => __('Please insert URL', 'dorcel-widgets'),
            'value' => $instance_new[self::WIDGET_SUB_URL],
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo '</p>';
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
                'name' => self::ARTICLE_NUMBER,
                'multilang' => true,
            ],
            [
                'name' => self::TITLE,
                'multilang' => true,
            ],
            [
                'name' => self::TITLE_TYPE,
                'multilang' => true,
            ],
            [
                'name' => self::WIDGET_SUB_URL_TEXT,
                'multilang' => true,
            ],
            [
                'name' => self::WIDGET_SUB_URL,
                'multilang' => true,
            ],
        ];
    }

    private final function generate_video_feed_files()
    {
        $available_languages = ['fr', 'en'];

        foreach($available_languages as $language) {
            $file_name = SLS_WIDGETS_POST_FEEDS_PATH . 'video_widget_feed_data_' . $language . '_' . time()  . '.xml';
            $tag = '';

            if ($language !== 'fr') {
                $tag = $language . "/";
            }

            $current_feed_file = $this->widget_helper->manage_feed_file('video_widget_feed_data_' . $language);

            if ($current_feed_file === true) {
                $rss_feed_content = file_get_contents("https://www.dorcelclub.com/" . $tag . "rss?soft=1");

                if (empty($rss_feed_content)) {
                    continue;
                }

                $fp = fopen($file_name, 'w');
                fwrite($fp, $rss_feed_content);
                fclose($fp);
            }
            $current_feed_file = $this->widget_helper->manage_feed_file('video_widget_feed_data_' . $language);
            if ($language === $this->widget_helper->get_current_language_code()) {
                return $current_feed_file;
            }
        }
    }
}
