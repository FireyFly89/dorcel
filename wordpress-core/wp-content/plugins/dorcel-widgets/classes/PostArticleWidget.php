<?php

/**
 * Widget: PostArticleWidget class
 *
 * @package SLS
 * @subpackage Widgets
 * @since 5.2.2
 */
class PostArticleWidget extends WP_Widget
{
    const TITLE = 'title';
    const TITLE_TYPE = 'title_type';
    const WIDGET_SUB_URL_TEXT = 'widget_sub_url_text';
    const WIDGET_SUB_URL = 'widget_sub_url';
    const ARTICLE_TITLE_TYPE = 'article_title_type';
    const ARTICLE_NUMBER = 'article_number';
    const ARTICLE_NUMBER_DEFAULT = 20;
    const QUERY_METHOD = 'query_method';
    const QUERY_METHOD_INPUT = 'query_method_input';
    const APPEARANCE = 'appearance';
    const TEMPLATE_PATH = 'post-article.php';
    const LANGUAGE_SWITCHER = 'language_switcher';

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
     * @var string
     */
    private $article_title_types;
    /**
     * @var int
     */
    private $article_number;
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
    private $appearance;
    /**
     * @var string
     */
    private $query;
    /**
     * @var string
     */
    private $query_result;
    /**
     * @var string
     */
    private $pagination;

    /**
     * @var string
     */
    private $page_number;

    /**
     * @var string
     */
    private $widget_id = 'post_article_widget';

    private $widget_field_settings = [];

    /**
     * PostArticleWidget constructor.
     */
    public function __construct()
    {
        $this->widget_helper = new WidgetHelper();

        // Instantiate the parent object
        parent::__construct(
            $this->widget_id,
            __('Dorcel articles', 'dorcel-widgets'),
            ['description' => __('Displays a number of articles by given settings', 'dorcel-widgets')]
        );

        add_action('parse_query', [$this, 'get_page_number']);
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

    public final function get_page_number(&$wp)
    {
        $paged = get_query_var("paged");
        $this->page_number = !empty($paged) ? $paged : 0;
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
        $this->article_title_types = $this->widget_helper->get_instance_data(self::ARTICLE_TITLE_TYPE, $instance, $this->widget_helper->get_array_first_element($this->widget_helper->get_title_types()));
        $this->article_number = $this->widget_helper->get_instance_data(self::ARTICLE_NUMBER, $instance);
        $this->appearance = $this->widget_helper->get_instance_data(self::APPEARANCE, $instance);
        $this->query = $this->get_query($instance);
        $this->query_result = $this->widget_helper->store_get_posts($this->query);
        $this->widget_posts = $this->query_result->get_posts();
        $this->pagination = (!empty($args['pagination']) && $args['pagination'] === true) ? $this->widget_helper->widget_pagination($this->query_result->max_num_pages) : "";

        ob_start();
        echo $args['before_widget'];
        require SLS_WIDGETS_POST_TEMPLATES_PATH . self::TEMPLATE_PATH;
        echo $args['after_widget'];
        echo ob_get_clean();
        wp_reset_query();
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

        // Widget admin form
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

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('The title type of the articles', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::ARTICLE_TITLE_TYPE),
            'name' => $this->get_field_name(self::ARTICLE_TITLE_TYPE),
            'placeholder' => __('Select the type of article titles', 'dorcel-widgets'),
            'value' => $instance_new[self::ARTICLE_TITLE_TYPE],
            'options' => $this->widget_helper->get_title_types(),
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('The method to get articles', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::QUERY_METHOD),
            'name' => $this->get_field_name(self::QUERY_METHOD),
            'placeholder' => __('Select the method to get articles', 'dorcel-widgets'),
            'value' => $instance_new[self::QUERY_METHOD],
            'options' => $this->widget_helper->get_query_methods(),
            'class' => 'query-method',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('Categories or tags divided by commas', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::QUERY_METHOD_INPUT),
            'name' => $this->get_field_name(self::QUERY_METHOD_INPUT),
            'value' => $instance_new[self::QUERY_METHOD_INPUT],
            'placeholder' => __('example: cooking,exciting (spaces will be deleted)', 'dorcel-widgets'),
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
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

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('The appearance of the widget', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::APPEARANCE),
            'name' => $this->get_field_name(self::APPEARANCE),
            'placeholder' => __('Select the appearance type', 'dorcel-widgets'),
            'value' => $instance_new[self::APPEARANCE],
            'options' => WIDGET_APPEARANCES,
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo '</p>';
    }

    /**
     * Assembles query request by given input parameters
     *
     * @param $instance
     * @return array
     */
    public final function get_query($instance)
    {
        $query_methods = $this->widget_helper->get_query_methods();
        $methods = array_keys($query_methods);
        $instance_query_method = $this->widget_helper->get_instance_data(self::QUERY_METHOD, $instance);
        $instance_query_method_input = $this->widget_helper->get_instance_data(self::QUERY_METHOD_INPUT, $instance);
        $query_args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__not_in' => ArticleStorage::get_instance()->get_articles(),
            'posts_per_page' => (!empty($this->article_number) ? $this->article_number : self::ARTICLE_NUMBER_DEFAULT),
            'paged' => $this->page_number,
        ];

        if (empty($instance_query_method) && empty($instance_query_method_input)) {
            $instance_query_method = $methods[0];
        }

        $query_method_input = "";
        $matches = [];

        if ($instance_query_method !== $methods[0] && $instance_query_method !== $methods[5]) {
            $query_method_input = preg_replace('/\s+/', '', $instance_query_method_input);
            preg_match('/([a-zA-Z0-9_\|-]{1,},?){1,}/', $query_method_input, $matches);
        }

        $query_method_input = explode(',', $query_method_input);
        $extra_query = [];

        if (!empty($matches)) {
            switch ($instance_query_method) {
                case $methods[1]:
                    $extra_query['category_name'] = implode(',', $query_method_input);
                    break;
                case $methods[2]:
                    $extra_query['category_name'] = implode('+', $query_method_input);
                    break;
                case $methods[3]:
                    $extra_query['tag'] = implode(',', $query_method_input);
                    break;
                case $methods[4]:
                    $extra_query['tag'] = implode('+', $query_method_input);
                    break;
            }
        } else if ($instance_query_method === $methods[5]) {
            if (is_category()) {
                $category = get_category(get_queried_object()->term_id);

                if (!empty($cat_slug = $category->slug)) {
                    $extra_query['category_name'] = $cat_slug;
                }
            } else if (is_single()) {
                $categories = get_the_category();

                if (!empty($categories)) {
                    $cat_slugs = array_column($categories, 'slug');
                    $extra_query['category_name'] = implode(',', $cat_slugs);
                }
            }
        }
        return array_merge($query_args, $extra_query);
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
            [
                'name' => self::ARTICLE_TITLE_TYPE,
                'multilang' => true,
            ],
            [
                'name' => self::ARTICLE_NUMBER,
                'multilang' => true,
            ],
            [
                'name' => self::QUERY_METHOD,
                'multilang' => true,
            ],
            [
                'name' => self::QUERY_METHOD_INPUT,
                'multilang' => true,
            ],
            [
                'name' => self::APPEARANCE,
                'multilang' => true,
            ],
        ];
    }
}
