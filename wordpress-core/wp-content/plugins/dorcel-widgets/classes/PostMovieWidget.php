<?php
/**
 * Widget: PostMovieWidget class
 *
 * @package SLS
 * @subpackage Widgets
 * @since 5.2.2
 */

class PostMovieWidget extends WP_Widget
{
    const TITLE = 'title';
    const TITLE_TYPE = 'title_type';
    const WIDGET_SUB_URL_TEXT = 'widget_sub_url_text';
    const WIDGET_SUB_URL = 'widget_sub_url';
    const TEMPLATE_PATH = 'post-movie.php';
    const LANGUAGE_SWITCHER = 'language_switcher';

    /**
     * @var string
     */
    private $widget_id = 'post_movie_widget';
    /**
     * @var string
     */
    private $widget_title;
    /**
     * @var string
     */
    private $widget_sub_url_text;
    /**
     * @var string
     */
    private $widget_sub_url;
    /**
     * @var string
     */
    private $widget_title_type;
    /**
     * @var WidgetHelper
     */
    private $widget_helper;
    /**
     * @var WP_Post
     */
    private $widget_posts;
    /**
     * @var string
     */
    private $api_key = "sd35f7qr7f3rf6q4zeRaDQzr454d5zerd1";

    private $widget_field_settings = [];

    /**
     * PostMovieWidget constructor.
     */
    public function __construct()
    {
        $this->widget_helper = new WidgetHelper();

        // Instantiate the parent object
        parent::__construct(
            $this->widget_id,
            __('Dorcel movies slider', 'dorcel-widgets'),
            ['description' => __('Displays movies that come from an API with a slider', 'dorcel-widgets')]
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
        wp_enqueue_script('slick-scripts', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], $sls_theme_version, true);
        wp_enqueue_script('dorcel-widget-scripts', SLS_WIDGETS_POST_SCRIPTS_URL . 'scripts.js', ['slick-scripts'], $sls_theme_version, true);
    }

    /**
     * Widget output
     *
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
        $this->widget_posts = [];
        $content = $this->get_api_json();

        if (!is_array($content) || !array_key_exists('data', $content) || empty($content['data'])) {
            return;
        }

        $movies = $content['data'];
        $movie_slug_by_lang = [
            'fr' => 'films',
            'en' => 'movies',
            'de' => 'filme',
        ];
        $current_language = $this->widget_helper->get_current_language_code();

        foreach ($movies as $movie) {
            if (!array_key_exists($current_language, $movie['attributes']['titles'])){
                continue;
            }

            $date = $movie['attributes']['tvodDate'];

            if (strtotime($date) <= 0) {
                continue;
            }

            $cover_image = $movie['relationships']['image-softcover']['data']['attributes']['url'];

            if (!empty($cover_image) && strpos($cover_image, 'fullsized') !== false) {
                $cover_image = str_replace('fullsized', 'dorcelcomcover', $cover_image);
            }

            $studio = $movie['relationships']['studio']['data']['attributes']['name'];
            $studio_slug = sanitize_title_with_dashes($studio);
            $this->widget_posts[] = [
                'title' => $movie['attributes']['titles'][$current_language],
                'excerpt' => $movie['attributes']['synopses'][$current_language],
                'date' => strftime('%B %Y', strtotime($date)),
                'url' => sprintf('https://www.dorcelvision.com/%s/%s/%s/%s',
                    $current_language,
                    $movie_slug_by_lang[$current_language],
                    $studio_slug,
                    sanitize_title_with_dashes(remove_accents($movie['attributes']['titles'][$current_language]))
                ),
                'image' => $cover_image
            ];
        }

        if (empty($this->widget_posts)) {
            return;
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
            'title' => __('The text of the URL under the widget', 'dorcel-widgets'),
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
     * Returns the JSON file for reading. It does not download a fresh one, but keeps reading the saved one until the file is older than x minutes
     *
     * @return array|mixed|object
     */
    private final function get_api_json()
    {
        $feed_file = $this->widget_helper->manage_feed_file('magazine_data');

        if ($feed_file === true) {
            $feed_content = $this->generate_api_json();
        } else {
            $feed_content = file_get_contents(SLS_WIDGETS_POST_FEEDS_PATH . $feed_file);
        }

        return json_decode($feed_content, true);
    }

    /**
     * Generates json file for later reading
     *
     * @return bool|false|string
     */
    private final function generate_api_json()
    {
        $file_name = SLS_WIDGETS_POST_FEEDS_PATH . 'magazine_data_' . time() . '.json';
        $api_token = $this->generate_api_token();
        $json_content = file_get_contents('https://www.dorcelvision.com/fr/api/movies/?token=' . $api_token . '&include=studios,images&filter[studio]=173&page[limit]=20&sort=date');

        if (empty($json_content)) {
            return false;
        }

        $fp = fopen($file_name, 'w');
        fwrite($fp, $json_content);
        fclose($fp);
        return $json_content;
    }

    /**
     * Generates API token from given api key and date as explained by dorcel API documentation
     *
     * @return string
     */
    private final function generate_api_token()
    {
        return md5($this->api_key . date('Ymd'));
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
}
